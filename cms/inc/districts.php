<?php
require_once __DIR__ . '/store.php';

function district_slugs(): array {
    return ['senamiestis', 'naujamiestis', 'zirmunai', 'antakalnis', 'snipiskes', 'fabijoniskes', 'pasilaiciai', 'karoliniskes', 'lazdynai', 'justiniskes'];
}

function default_district_content(string $lang): array {
    $file = __DIR__ . '/districts/data_' . $lang . '.php';
    return file_exists($file) ? require $file : [];
}

function get_district_content(string $lang): array {
    $defaults = default_district_content($lang);
    $overrides = read_json('districts_' . $lang . '.json', []);
    if (!is_array($overrides)) $overrides = [];
    foreach ($overrides as $slug => $fields) {
        if (isset($defaults[$slug])) {
            $defaults[$slug] = array_merge($defaults[$slug], array_filter($fields, fn($v) => $v !== '' && $v !== null));
        }
    }
    return $defaults;
}

function get_district(string $lang, string $slug): ?array {
    $all = get_district_content($lang);
    return $all[$slug] ?? null;
}

function district_url(array $languages, string $lang, string $slug): string {
    $path = ($languages[$lang]['path'] ?? '/') . 'rajonas-' . $slug . '.php';
    return function_exists('site_href') ? site_href($path) : $path;
}
