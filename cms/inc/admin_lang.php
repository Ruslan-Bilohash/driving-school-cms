<?php
require_once __DIR__ . '/auth.php';

/**
 * Admin UI languages. Cyrillic labels via unicode escapes (no mojibake).
 */
function admin_lang_options(): array {
    $u = static function (string $jsonEscaped): string {
        $v = json_decode('"' . $jsonEscaped . '"');
        return is_string($v) ? $v : $jsonEscaped;
    };

    return [
        'no' => 'Norsk',
        'en' => 'English',
        'sv' => 'Svenska',
        'pl' => 'Polski',
        // Українська
        'uk' => $u('\\u0423\\u043a\\u0440\\u0430\\u0457\\u043d\\u0441\\u044c\\u043a\\u0430'),
        // Русский
        'ru' => $u('\\u0420\\u0443\\u0441\\u0441\\u043a\\u0438\\u0439'),
        // Lietuvių
        'lt' => $u('Lietuvi\\u0173'),
    ];
}

function get_admin_lang(): string {
    admin_session_start();
    if (isset($_GET['alang']) && isset(admin_lang_options()[$_GET['alang']])) {
        $_SESSION['admin_lang'] = $_GET['alang'];
    }
    return $_SESSION['admin_lang'] ?? 'no';
}

function admin_dict(): array {
    static $dict = null;
    static $langCached = null;
    $lang = get_admin_lang();
    if ($dict === null || $langCached !== $lang) {
        $file = __DIR__ . '/admin_lang/' . $lang . '.php';
        $enFile = __DIR__ . '/admin_lang/en.php';
        $en = is_file($enFile) ? require $enFile : [];
        $local = is_file($file) ? require $file : [];
        if (!is_array($en)) {
            $en = [];
        }
        if (!is_array($local)) {
            $local = [];
        }
        $dict = array_replace_recursive($en, $local);
        $langCached = $lang;
    }
    return $dict;
}

function at(string $key, string $default = ''): string {
    $dict = admin_dict();
    if (isset($dict[$key]) && is_string($dict[$key])) {
        return $dict[$key];
    }
    return $default !== '' ? $default : $key;
}

function admin_greeting(string $name): string {
    admin_session_start();
    $dict = admin_dict();
    $greetings = $dict['greetings'] ?? [];
    if (empty($greetings) || !is_array($greetings)) {
        return '';
    }
    $key = 'admin_greeting_idx_' . get_admin_lang();
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = array_rand($greetings);
    }
    $template = $greetings[$_SESSION[$key]] ?? $greetings[0];
    return str_replace('{name}', $name, (string) $template);
}
