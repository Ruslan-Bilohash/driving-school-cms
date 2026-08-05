<?php

/**
 * Defensive cleanup for AI-generated article HTML: some models mix markdown
 * bullets/bold with HTML tags despite instructions. This repairs the common
 * cases (stray "* item" bullets, orphaned </li>, **bold**, # headers, \r) so
 * the output always renders as clean, properly nested HTML.
 */
function sanitize_ai_html(string $html): string {
    $html = str_replace("\r", '', $html);
    // repair escaped closing tags some models emit, e.g. "<\/h2>" -> "</h2>"
    $html = preg_replace('#<\\\\/#', '</', $html);

    // **bold** -> <b>bold</b>, leftover markdown headers -> <h2>/<h3>
    $html = preg_replace('/\*\*(.+?)\*\*/s', '<b>$1</b>', $html);
    $html = preg_replace('/^###\s+(.+)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^##\s+(.+)$/m', '<h2>$1</h2>', $html);

    // If markdown-style bullets exist as raw lines (not already inside <li>),
    // rebuild them into a proper <ul><li>...</li></ul> block.
    if (preg_match('/^\s*[*\-]\s+/m', $html)) {
        $lines = preg_split('/\r?\n/', $html);
        $out = [];
        $inList = false;
        foreach ($lines as $line) {
            if (preg_match('/^\s*[*\-]\s+(.*)$/', $line, $m)) {
                if (!$inList) { $out[] = '<ul>'; $inList = true; }
                $item = trim($m[1]);
                // strip an orphaned closing </li> the model may have appended
                $item = preg_replace('#</li>\s*$#i', '', $item);
                $item = preg_replace('#^<li>\s*#i', '', $item);
                $out[] = '<li>' . $item . '</li>';
            } else {
                if ($inList) { $out[] = '</ul>'; $inList = false; }
                $out[] = $line;
            }
        }
        if ($inList) $out[] = '</ul>';
        $html = implode("\n", $out);
    }

    // Collapse any now-invalid "<li>...</li>" that ended up outside a <ul>/<ol>
    // (defensive: wrap orphan <li> runs that slipped through without a parent list)
    if (preg_match('/<li>/i', $html) && !preg_match('/<ul>|<ol>/i', $html)) {
        $html = '<ul>' . $html . '</ul>';
    }

    return trim($html);
}

function ai_translate_article(array $post, string $targetLang, array $settings): ?array {
    $langNames = ['lt' => 'Lithuanian', 'uk' => 'Ukrainian', 'en' => 'English', 'ru' => 'Russian'];
    $provider = $settings['chat_widget_provider'] ?? 'openai';
    $apiKey = $provider === 'grok' ? ($settings['grok_key'] ?? '') : ($settings['openai_key'] ?? '');
    if ($apiKey === '') return null;

    $systemPrompt = "You are a professional translator for the website of Autoteisės, a driving school in Vilnius, Lithuania.\n\n"
        . "Translate the given article (provided as JSON) into " . ($langNames[$targetLang] ?? $targetLang) . ".\n"
        . "Preserve all HTML tags exactly as they are (e.g. <h2>, <h3>, <p>, <ul>, <li>, <a href=\"...\">) — only translate the visible text content, including link text. Keep href attribute values unchanged.\n"
        . "Translate naturally and fluently, not word-for-word. Do not add, remove, or invent information.\n\n"
        . "Respond ONLY with a single valid JSON object with exactly these keys: "
        . "{\"title\": string, \"excerpt\": string, \"meta_description\": string, \"content\": string (HTML)}";

    $userContent = json_encode([
        'title' => $post['title'] ?? '',
        'excerpt' => $post['excerpt'] ?? '',
        'meta_description' => $post['meta_description'] ?? '',
        'content' => $post['content'] ?? '',
    ], JSON_UNESCAPED_UNICODE);

    $messages = [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userContent],
    ];

    if ($provider === 'grok') {
        $url = 'https://api.x.ai/v1/chat/completions';
        $payload = ['model' => 'grok-2-latest', 'messages' => $messages, 'max_tokens' => 1800, 'temperature' => 0.3];
    } else {
        $url = 'https://api.openai.com/v1/chat/completions';
        $payload = ['model' => 'gpt-4o-mini', 'messages' => $messages, 'max_tokens' => 1800, 'temperature' => 0.3, 'response_format' => ['type' => 'json_object']];
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 50,
        CURLOPT_CONNECTTIMEOUT => 8,
    ]);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($result === false || $httpCode >= 400) {
        error_log('ai_translate_article error [' . $targetLang . ']: ' . $err . ' | HTTP ' . $httpCode);
        return null;
    }

    $data = json_decode((string)$result, true);
    $raw = trim((string)($data['choices'][0]['message']['content'] ?? ''));
    $raw = preg_replace('/^```(json)?/i', '', $raw);
    $raw = preg_replace('/```$/', '', $raw);
    $translated = json_decode(trim($raw), true);

    if (!is_array($translated) || empty($translated['title']) || empty($translated['content'])) {
        return null;
    }
    $translated['content'] = sanitize_ai_html((string)($translated['content'] ?? ''));
    return $translated;
}
