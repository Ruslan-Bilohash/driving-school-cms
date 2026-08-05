<?php
function icon($name, $class = 'icon') {
    $icons = [
        'car' => '<path d="M5 17h14M5 17a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm14 0a2 2 0 1 0 4 0 2 2 0 0 0-4 0M3 17V11l2.2-5.2A2 2 0 0 1 7.06 4.5h9.88a2 2 0 0 1 1.86 1.3L21 11v6M3 11h18M7 11V7.5M17 11V7.5"/>',
        'moto' => '<circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/><path d="M8 17h5l3-7h3M13 10 10 5H7M5 17l3-7"/>',
        'truck' => '<path d="M3 17h1M14 17H9M3 7v10h11V7H3Zm11 4h4l3 3v3h-2M14 17a2 2 0 1 0 4 0m-11 0a2 2 0 1 0 4 0"/>',
        'truck-trailer' => '<path d="M1 17h2M1 8v9h9V8H1Zm9 3h3l3 3v3h-1M18 17H12M18 8v9h4v-4l-2-3h-2Z"/><path d="M6 17a2 2 0 1 0 4 0m8 0a2 2 0 1 0 4 0"/>',
        'bus-truck' => '<path d="M3 6h9v11H3zM3 9h9M12 10h4l3 3v4h-2"/><circle cx="6" cy="19" r="1.6"/><circle cx="9" cy="19" r="1.6"/><circle cx="16" cy="19" r="1.6"/>',
        'bus' => '<rect x="3" y="5" width="18" height="12" rx="2"/><path d="M3 11h18M7 5v6M17 5v6"/><circle cx="7" cy="19" r="1.6"/><circle cx="17" cy="19" r="1.6"/>',
        'car-trailer' => '<path d="M2 16h9M2 10l1.5-3.6A1.6 1.6 0 0 1 5 5.4h6.4a1.6 1.6 0 0 1 1.5 1L14 10v6H2v-6Z"/><path d="M17 9h3l2 2v5h-2M14 16h3"/><circle cx="5" cy="16" r="1.6"/><circle cx="10" cy="16" r="1.6"/><circle cx="19" cy="16" r="1.6"/>',
        'refresh' => '<path d="M4 4v5h5M20 20v-5h-5M4.6 15a8 8 0 0 0 14.5 2.5M19.4 9a8 8 0 0 0-14.5-2.5"/>',
        'shield' => '<path d="M12 3 4 6v6c0 4.6 3.2 8 8 9 4.8-1 8-4.4 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
        'coin' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v10M9.5 9.5c0-1.4 1.2-2.2 2.7-2.2 1.6 0 2.6.9 2.6 2 0 2.6-5.3 1.6-5.3 4.4 0 1.2 1.2 2.1 2.8 2.1 1.6 0 2.8-.8 2.8-2.1"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'phone' => '<path d="M5 4h3.5l1.5 5-2.5 1.5a11 11 0 0 0 5 5L14 13l5 1.5V18a2 2 0 0 1-2 2A15 15 0 0 1 3 6a2 2 0 0 1 2-2Z"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'pin' => '<path d="M12 21s7-6.1 7-11.5A7 7 0 0 0 5 9.5C5 14.9 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
        'check' => '<path d="m5 12 5 5 9-10"/>',
        'chevron' => '<path d="m6 9 6 6 6-6"/>',
        'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'close' => '<path d="m5 5 14 14M19 5 5 19"/>',
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'star' => '<path d="m12 3 2.6 5.6 6.1.8-4.5 4.2 1.2 6-5.4-3-5.4 3 1.2-6-4.5-4.2 6.1-.8Z"/>',
        'telegram' => '<path d="m3 11 18-7-4 17-6-5-4 3-1-6Z"/><path d="m8 13 11-8-9 9"/>',
        'whatsapp' => '<path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Z"/><path d="M8.5 8.3c.2-.5.4-.5.6-.5h.5c.2 0 .4 0 .6.4s.6 1.5.7 1.6.1.3 0 .4a3 3 0 0 1-.4.6c-.2.2-.4.4-.2.7a6 6 0 0 0 2.6 2.3c.3.1.5.1.6-.1l.5-.7c.2-.3.4-.2.6-.1l1.4.7c.2.1.4.2.4.3 0 .5-.2 1.1-.5 1.4-.4.4-1 .7-1.6.7a6.8 6.8 0 0 1-3-.9 8 8 0 0 1-3-3 6.6 6.6 0 0 1-1-2.9c0-.6.3-1.2.6-1.6Z"/>',
        'top' => '<path d="M12 19V5M5 12l7-7 7 7"/>',
        'code' => '<path d="m8 6-5.5 6L8 18M16 6l5.5 6L16 18M13.5 4l-3 16"/>',
        'chat' => '<path d="M4 4h16v11H8l-4 4V4Z"/><path d="M8 9h8M8 12h5"/>',
        'send' => '<path d="M4 12 20 4l-6 16-2.5-7L4 12Z"/>',
    ];
    $body = $icons[$name] ?? $icons['check'];
    return '<svg class="' . $class . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $body . '</svg>';
}
