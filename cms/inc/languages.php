<?php
/**
 * Language registry — labels built via JSON unicode escapes so file encoding
 * cannot break Cyrillic in the language switcher.
 */
function site_languages(): array {
    $u = static function (string $jsonEscaped): string {
        $v = json_decode('"' . $jsonEscaped . '"');
        return is_string($v) ? $v : $jsonEscaped;
    };

    return [
        'no' => [
            'label' => 'Norsk',
            'code'  => 'NO',
            'path'  => '/',
            'flag'  => 'NO',
        ],
        'en' => [
            'label' => 'English',
            'code'  => 'EN',
            'path'  => '/en/',
            'flag'  => 'EN',
        ],
        'sv' => [
            'label' => 'Svenska',
            'code'  => 'SV',
            'path'  => '/sv/',
            'flag'  => 'SV',
        ],
        'pl' => [
            'label' => 'Polski',
            'code'  => 'PL',
            'path'  => '/pl/',
            'flag'  => 'PL',
        ],
        // Українська
        'uk' => [
            'label' => $u('\\u0423\\u043a\\u0440\\u0430\\u0457\\u043d\\u0441\\u044c\\u043a\\u0430'),
            'code'  => 'UA',
            'path'  => '/uk/',
            'flag'  => 'UA',
        ],
        // Русский
        'ru' => [
            'label' => $u('\\u0420\\u0443\\u0441\\u0441\\u043a\\u0438\\u0439'),
            'code'  => 'RU',
            'path'  => '/ru/',
            'flag'  => 'RU',
        ],
    ];
}
