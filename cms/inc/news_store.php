<?php
require_once __DIR__ . '/store.php';

function news_file(string $lang): string {
    return 'news_' . $lang . '.json';
}

function slugify(string $text): string {
    $text = trim($text);
    $map = ['ą'=>'a','č'=>'c','ę'=>'e','ė'=>'e','į'=>'i','š'=>'s','ų'=>'u','ū'=>'u','ž'=>'z'];
    $text = strtr(mb_strtolower($text), $map);
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
    return trim($text, '-') ?: 'post';
}

function get_all_news(string $lang): array {
    $posts = read_json(news_file($lang), []);
    return is_array($posts) ? $posts : [];
}

function get_published_news(string $lang): array {
    $posts = array_values(array_filter(get_all_news($lang), fn($p) => !empty($p['published'])));
    usort($posts, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
    return $posts;
}

function get_news_by_slug(string $lang, string $slug): ?array {
    foreach (get_all_news($lang) as $p) {
        if (($p['slug'] ?? '') === $slug) return $p;
    }
    return null;
}

function get_news_by_id(string $lang, int $id): ?array {
    foreach (get_all_news($lang) as $p) {
        if ((int)($p['id'] ?? 0) === $id) return $p;
    }
    return null;
}

function save_news_post(string $lang, array $post): int {
    $posts = get_all_news($lang);
    $isUpdate = !empty($post['id']);
    $baseSlug = $post['slug'] !== '' ? slugify($post['slug']) : slugify($post['title']);
    $slug = $baseSlug;
    $i = 2;
    foreach ($posts as $p) {
        if ($p['slug'] === $slug && (int)($p['id'] ?? 0) !== (int)($post['id'] ?? 0)) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }
    }
    $post['slug'] = $slug;

    if ($isUpdate) {
        foreach ($posts as &$p) {
            if ((int)$p['id'] === (int)$post['id']) {
                $post['created_at'] = $p['created_at'];
                $p = array_merge($p, $post);
                $p['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }
        unset($p);
    } else {
        $nextId = 1;
        foreach ($posts as $p) { if (($p['id'] ?? 0) >= $nextId) $nextId = $p['id'] + 1; }
        $post['id'] = $nextId;
        $post['created_at'] = date('Y-m-d H:i:s');
        $post['updated_at'] = $post['created_at'];
        $posts[] = $post;
    }
    write_json(news_file($lang), $posts);
    return (int)$post['id'];
}

function delete_news_post(string $lang, int $id): void {
    $posts = array_values(array_filter(get_all_news($lang), fn($p) => (int)$p['id'] !== $id));
    write_json(news_file($lang), $posts);
}
