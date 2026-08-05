<?php
require_once __DIR__ . '/store.php';

function get_gallery_items(): array {
    $items = read_json('gallery.json', []);
    return is_array($items) ? $items : [];
}

function save_gallery_item(array $item): int {
    $items = get_gallery_items();
    if (!empty($item['id'])) {
        foreach ($items as &$it) {
            if ((int)$it['id'] === (int)$item['id']) {
                $it['caption'] = $item['caption'];
                write_json('gallery.json', $items);
                return (int)$it['id'];
            }
        }
        unset($it);
    }
    $nextId = 1;
    foreach ($items as $it) { if (($it['id'] ?? 0) >= $nextId) $nextId = $it['id'] + 1; }
    $items[] = [
        'id' => $nextId,
        'image' => $item['image'],
        'caption' => $item['caption'] ?? '',
        'created_at' => date('Y-m-d H:i:s'),
    ];
    write_json('gallery.json', $items);
    return $nextId;
}

function delete_gallery_item(int $id): void {
    $items = array_values(array_filter(get_gallery_items(), fn($it) => (int)$it['id'] !== $id));
    write_json('gallery.json', $items);
}
