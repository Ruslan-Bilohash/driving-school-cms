<?php

function recaptcha_verify(string $token, string $remoteIp = ''): bool {
    $settings = get_settings();
    $secret = $settings['recaptcha_secret_key'] ?? '';
    if ($secret === '') return true;
    // Fail open when no token was produced (script blocked by an ad-blocker,
    // slow network, etc.) — honeypot + timing-trap checks in contact.php still apply.
    if ($token === '') return true;

    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $remoteIp,
        ]),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 6,
        CURLOPT_CONNECTTIMEOUT => 4,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    if ($result === false) return true;

    $data = json_decode($result, true);
    if (!is_array($data) || empty($data['success'])) return false;
    if (isset($data['score']) && $data['score'] < 0.5) return false;

    return true;
}
