<?php
require_once __DIR__ . '/store.php';

function bilohash_fetch_unpaid_invoices(string $email, string $password): ?array {
    if ($email === '' || $password === '') return null;

    $cacheFile = sys_get_temp_dir() . '/bh_invoices_cache_' . md5($email) . '.json';
    if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < 600) {
        $cached = json_decode((string)file_get_contents($cacheFile), true);
        if (is_array($cached)) return $cached;
    }

    $cookieJar = tempnam(sys_get_temp_dir(), 'bhck');
    $base = 'https://bilohash.com/client';

    $ch = curl_init("$base/login.php");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $loginPage = curl_exec($ch);
    curl_close($ch);
    if ($loginPage === false) { @unlink($cookieJar); return null; }

    if (!preg_match('/name="csrf"\s+value="([^"]+)"/', $loginPage, $m)) {
        @unlink($cookieJar);
        return null;
    }
    $csrf = $m[1];

    $ch = curl_init("$base/login.php");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['csrf' => $csrf, 'email' => $email, 'password' => $password]),
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    curl_exec($ch);
    $loginStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($loginStatus !== 302 && $loginStatus !== 200) { @unlink($cookieJar); return null; }

    $ch = curl_init("$base/invoices.php");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_COOKIEJAR => $cookieJar,
        CURLOPT_COOKIEFILE => $cookieJar,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $invoicesPage = curl_exec($ch);
    curl_close($ch);
    @unlink($cookieJar);
    if ($invoicesPage === false) return null;

    if (!preg_match_all('/<tr>\s*<td>([^<]*)<\/td>\s*<td>([^<]*)<\/td>\s*<td><span class="mk-badge[^"]*">([^<]*)<\/span><\/td>\s*<td>([^<]*)<\/td>\s*<td[^>]*>(.*?)<\/td>\s*<\/tr>/s', $invoicesPage, $matches, PREG_SET_ORDER)) {
        return null;
    }

    $unpaid = [];
    foreach ($matches as $row) {
        $hasPayLink = str_contains($row[5], 'invoice-pay.php');
        if (!$hasPayLink) continue;
        $unpaid[] = [
            'number' => trim($row[1]),
            'amount' => trim($row[2]),
            'status' => trim($row[3]),
            'date' => trim($row[4]),
        ];
    }

    write_json_at($cacheFile, $unpaid);
    return $unpaid;
}

function write_json_at(string $path, array $data): void {
    file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
}
