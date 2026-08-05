<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/languages.php';
require_once __DIR__ . '/inc/categories.php';
require_once __DIR__ . '/inc/districts.php';
require_once __DIR__ . '/inc/news_store.php';

header('Content-Type: application/xml; charset=utf-8');

$languages = site_languages();
$langCodes = array_keys($languages);
$slugs = category_slugs();

function url_block(string $loc, array $languages, array $altPaths, string $changefreq, string $priority): string {
    $out = "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n";
    foreach ($altPaths as $code => $path) {
        $out .= '    <xhtml:link rel="alternate" hreflang="' . $code . '" href="' . htmlspecialchars(SITE_URL . $path) . "\"/>\n";
    }
    $out .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . htmlspecialchars(SITE_URL . $altPaths['lt']) . "\"/>\n";
    $out .= "    <changefreq>{$changefreq}</changefreq>\n    <priority>{$priority}</priority>\n  </url>\n";
    return $out;
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

// Homepage per language
foreach ($languages as $code => $l) {
    $alt = [];
    foreach ($languages as $c2 => $l2) $alt[$c2] = $l2['path'];
    echo url_block(SITE_URL . $l['path'], $languages, $alt, 'weekly', $code === 'lt' ? '1.0' : '0.9');
}

// Category pages
foreach ($slugs as $slug) {
    $alt = [];
    foreach ($languages as $c2 => $l2) $alt[$c2] = category_url($languages, $c2, $slug);
    foreach ($languages as $code => $l) {
        echo url_block(SITE_URL . category_url($languages, $code, $slug), $languages, $alt, 'monthly', '0.8');
    }
}

// District pages
foreach (district_slugs() as $dslug) {
    $alt = [];
    foreach ($languages as $c2 => $l2) $alt[$c2] = district_url($languages, $c2, $dslug);
    foreach ($languages as $code => $l) {
        echo url_block(SITE_URL . district_url($languages, $code, $dslug), $languages, $alt, 'monthly', '0.6');
    }
}

// News posts
foreach ($languages as $code => $l) {
    foreach (get_published_news($code) as $post) {
        $loc = SITE_URL . $l['path'] . 'news-post.php?slug=' . urlencode($post['slug']);
        echo "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <changefreq>monthly</changefreq>\n    <priority>0.6</priority>\n  </url>\n";
    }
    $newsLoc = SITE_URL . $l['path'] . 'news.php';
    echo "  <url>\n    <loc>" . htmlspecialchars($newsLoc) . "</loc>\n    <changefreq>weekly</changefreq>\n    <priority>0.7</priority>\n  </url>\n";
}

// Gallery pages
foreach ($languages as $code => $l) {
    $loc = SITE_URL . $l['path'] . 'gallery.php';
    echo "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <changefreq>monthly</changefreq>\n    <priority>0.5</priority>\n  </url>\n";
}

// Privacy pages
foreach ($languages as $code => $l) {
    $loc = SITE_URL . $l['path'] . 'privacy.php';
    echo "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <changefreq>yearly</changefreq>\n    <priority>0.2</priority>\n  </url>\n";
}

// Terms pages
foreach ($languages as $code => $l) {
    $loc = SITE_URL . $l['path'] . 'terms.php';
    echo "  <url>\n    <loc>" . htmlspecialchars($loc) . "</loc>\n    <changefreq>yearly</changefreq>\n    <priority>0.2</priority>\n  </url>\n";
}

echo '</urlset>';
