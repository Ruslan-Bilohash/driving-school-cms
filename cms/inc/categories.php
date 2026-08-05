<?php
require_once __DIR__ . '/store.php';

function category_slugs(): array {
    return ['b', 'a', 'c', 'cce', 'cced', 'd', 'ce', 'be', 'lost'];
}

function category_meta(): array {
    return [
        'b' => ['icon' => 'car', 'price_key' => 'B', 'price_group' => 'full_course'],
        'a' => ['icon' => 'moto', 'price_key' => 'A', 'price_group' => 'full_course'],
        'c' => ['icon' => 'truck', 'price_key' => 'C', 'price_group' => 'full_course'],
        'cce' => ['icon' => 'truck-trailer', 'price_key' => 'CCE', 'price_group' => 'full_course'],
        'cced' => ['icon' => 'bus-truck', 'price_key' => 'CCED', 'price_group' => 'full_course'],
        'd' => ['icon' => 'bus', 'price_key' => 'D', 'price_group' => 'full_course'],
        'ce' => ['icon' => 'truck-trailer', 'price_key' => 'CE', 'price_group' => 'full_course'],
        'be' => ['icon' => 'car-trailer', 'price_key' => 'BE', 'price_group' => 'full_course'],
        'lost' => ['icon' => 'refresh', 'price_key' => 'LOST', 'price_group' => 'full_course'],
    ];
}

function default_category_content(string $lang): array {
    $file = __DIR__ . '/categories/data_' . $lang . '.php';
    return file_exists($file) ? require $file : [];
}

function get_category_content(string $lang): array {
    $defaults = default_category_content($lang);
    $overrides = read_json('categories_' . $lang . '.json', []);
    if (!is_array($overrides)) $overrides = [];
    foreach ($overrides as $slug => $fields) {
        if (isset($defaults[$slug])) {
            $defaults[$slug] = array_merge($defaults[$slug], array_filter($fields, fn($v) => $v !== '' && $v !== null));
        }
    }
    return $defaults;
}

function get_category(string $lang, string $slug): ?array {
    $all = get_category_content($lang);
    return $all[$slug] ?? null;
}

function save_category_override(string $lang, string $slug, array $fields): void {
    $overrides = read_json('categories_' . $lang . '.json', []);
    if (!is_array($overrides)) $overrides = [];
    $overrides[$slug] = $fields;
    write_json('categories_' . $lang . '.json', $overrides);
}

function category_url(array $languages, string $lang, string $slug): string {
    $path = ($languages[$lang]['path'] ?? '/') . 'kategorija-' . $slug . '.php';
    return function_exists('site_href') ? site_href($path) : $path;
}
