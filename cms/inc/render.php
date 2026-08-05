<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/categories.php';
$languages = site_languages();
if (!isset($languages[$lang])) {
    $lang = 'no';
}
$enLangFile = __DIR__ . '/lang/en.php';
$langFile = __DIR__ . '/lang/' . $lang . '.php';
$en_t = is_file($enLangFile) ? require $enLangFile : [];
$t_local = is_file($langFile) ? require $langFile : [];
if (!is_array($en_t)) {
    $en_t = [];
}
if (!is_array($t_local)) {
    $t_local = [];
}
// 100% coverage: local overrides English base
$t = ($lang === 'en') ? $en_t : array_replace_recursive($en_t, $t_local);
$catSlugs = category_slugs();

require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/popups.php';
require_once __DIR__ . '/footer.php';
$s = get_settings();

$currencySuffix = defined('COMPANY_CURRENCY') ? (' ' . COMPANY_CURRENCY) : ' NOK';
foreach ($t['prices']['full_course'] as &$__row) {
    if (isset($s['price_full'][$__row['key']])) {
        $__row['price'] = number_format((float) $s['price_full'][$__row['key']], 0, ',', ' ') . $currencySuffix;
    }
}
unset($__row);
foreach ($t['prices']['extra_lessons'] as &$__row) {
    if (isset($s['price_extra'][$__row['key']])) {
        $__row['price'] = number_format((float) $s['price_extra'][$__row['key']], 0, ',', ' ') . $currencySuffix . ' ' . $__row['unit'];
    }
}
unset($__row);

if (!empty($s['description_' . $lang])) {
    $t['meta_description'] = $s['description_' . $lang];
    $t['og_description'] = $s['description_' . $lang];
}

$canonical = SITE_URL . $languages[$lang]['path'];
$year = date('Y');
?><!DOCTYPE html>
<html lang="<?= $t['html_lang'] ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($t['meta_title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($t['meta_description']) ?>">
<meta name="keywords" content="<?= htmlspecialchars($t['meta_keywords']) ?>">
<meta name="author" content="<?= htmlspecialchars(SITE_NAME) ?>">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="<?= $canonical ?>">
<?php foreach ($languages as $code => $l): ?>
<link rel="alternate" hreflang="<?= $code ?>" href="<?= SITE_URL . $l['path'] ?>">
<?php endforeach; ?>
<link rel="alternate" hreflang="x-default" href="<?= SITE_URL . ($languages['no']['path'] ?? $languages['en']['path'] ?? '/') ?>">
<link rel="alternate" hreflang="nb-NO" href="<?= SITE_URL . ($languages['no']['path'] ?? '/') ?>">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= htmlspecialchars(SITE_NAME) ?>">
<meta property="og:title" content="<?= htmlspecialchars($t['og_title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($t['og_description']) ?>">
<meta property="og:url" content="<?= $canonical ?>">
<meta property="og:image" content="<?= SITE_URL ?>/assets/img/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="<?= $t['locale'] ?>">
<?php foreach ($languages as $code => $l): if ($code === $lang) continue; ?>
<meta property="og:locale:alternate" content="<?= (require __DIR__ . '/lang/' . $code . '.php')['locale'] ?>">
<?php endforeach; ?>

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($t['og_title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($t['og_description']) ?>">
<meta name="twitter:image" content="<?= SITE_URL ?>/assets/img/og-image.png">

<meta name="theme-color" content="#0b1220">
<link rel="icon" href="<?= BASE_PATH ?>/assets/img/favicon-v3.png" type="image/png">
<link rel="apple-touch-icon" href="<?= BASE_PATH ?>/assets/img/apple-touch-icon.png">
<link rel="manifest" href="<?= BASE_PATH ?>/manifest.json">
<script>window.DS_BASE=<?= json_encode(BASE_PATH) ?>;</script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= asset_url('/assets/css/style.css') ?>">

<!-- JSON-LD: DrivingSchool -->
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "DrivingSchool",
  "name" => SITE_NAME,
  "alternateName" => "Nordisk Trafikkskole demo â€” Driving School CMS",
  "url" => SITE_URL,
  "image" => SITE_URL . "/assets/img/og-image.png",
  "logo" => SITE_URL . "/assets/img/favicon-v3.png",
  "telephone" => [$s['phone1'], $s['phone2']],
  "email" => $s['email'],
  "priceRange" => "NOK",
  "currenciesAccepted" => "NOK",
  "paymentAccepted" => "Vipps, Card, Invoice",
  "areaServed" => [
    ["@type" => "City", "name" => "Oslo"],
    ["@type" => "Country", "name" => "Norway"]
  ],
  "address" => [
    "@type" => "PostalAddress",
    "streetAddress" => $s['address'],
    "addressLocality" => defined('COMPANY_CITY') ? COMPANY_CITY : 'Oslo',
    "postalCode" => "0154",
    "addressCountry" => defined('COMPANY_COUNTRY') ? COMPANY_COUNTRY : 'NO'
  ],
  "geo" => [
    "@type" => "GeoCoordinates",
    "latitude" => defined('COMPANY_GEO_LAT') ? COMPANY_GEO_LAT : 59.9139,
    "longitude" => defined('COMPANY_GEO_LNG') ? COMPANY_GEO_LNG : 10.7522
  ],
  "openingHoursSpecification" => [
    ["@type" => "OpeningHoursSpecification", "dayOfWeek" => ["Monday","Tuesday","Wednesday","Thursday","Friday"], "opens" => "08:00", "closes" => "18:00"],
    ["@type" => "OpeningHoursSpecification", "dayOfWeek" => ["Saturday"], "opens" => "10:00", "closes" => "14:00"]
  ],
  "founder" => ["@type" => "Person", "name" => COMPANY_MANAGER],
  "makesOffer" => array_map(function($row) {
    return [
      "@type" => "Offer",
      "name" => $row['cat'],
      "price" => (float) preg_replace('/[^0-9]/', '', $row['price']),
      "priceCurrency" => defined('COMPANY_CURRENCY') ? COMPANY_CURRENCY : 'NOK',
      "availability" => "https://schema.org/InStock"
    ];
  }, $t['prices']['full_course'])
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>

<!-- JSON-LD: FAQPage -->
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "FAQPage",
  "mainEntity" => array_map(function($item) {
    return [
      "@type" => "Question",
      "name" => $item['q'],
      "acceptedAnswer" => ["@type" => "Answer", "text" => $item['a']]
    ];
  }, $t['faq']['items'])
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>

<!-- JSON-LD: BreadcrumbList -->
<script type="application/ld+json">
<?php echo json_encode([
  "@context" => "https://schema.org",
  "@type" => "BreadcrumbList",
  "itemListElement" => [
    ["@type" => "ListItem", "position" => 1, "name" => $t['nav']['home'], "item" => $canonical]
  ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
</script>
</head>
<body>
<a href="#main" class="skip-link"><?= htmlspecialchars($t['skip_link']) ?></a>

<div class="bg-fx" aria-hidden="true"><div class="blob blob-1"></div><div class="blob blob-2"></div></div>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="<?= htmlspecialchars(site_href($t['dir_prefix'] ?: '/')) ?>" class="logo" aria-label="<?= htmlspecialchars(SITE_NAME) ?>">
      <span class="logo-mark"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="38" height="38"></span>
      <span class="logo-text"><?= htmlspecialchars(SITE_NAME) ?></span>
    </a>

    <nav class="main-nav" id="mainNav" aria-label="Main">
      <a href="#about"><?= htmlspecialchars($t['nav']['about']) ?></a>
      <a href="#categories"><?= htmlspecialchars($t['nav']['categories']) ?></a>
      <a href="#prices"><?= htmlspecialchars($t['nav']['prices']) ?></a>
      <a href="#process"><?= htmlspecialchars($t['nav']['process']) ?></a>
      <a href="#faq"><?= htmlspecialchars($t['nav']['faq']) ?></a>
      <a href="<?= htmlspecialchars(site_href(rtrim($t['dir_prefix'] ?: '', '/') . '/news.php')) ?>"><?= htmlspecialchars($t['nav']['news']) ?></a>
      <a href="<?= htmlspecialchars(site_href(rtrim($t['dir_prefix'] ?: '', '/') . '/gallery.php')) ?>"><?= htmlspecialchars($t['nav']['gallery']) ?></a>
      <a href="#contact"><?= htmlspecialchars($t['nav']['contact']) ?></a>
    </nav>

    <div class="header-actions">
      <div class="lang-dropdown" id="langDropdown">
        <button class="lang-trigger" id="langTrigger" aria-haspopup="true" aria-expanded="false">
          <span class="lang-code"><?= htmlspecialchars((string) ($languages[$lang]['code'] ?? strtoupper($lang)), ENT_QUOTES, 'UTF-8') ?></span>
          <?= icon('chevron', 'icon lang-chevron') ?>
        </button>
        <ul class="lang-menu" role="menu">
          <?php foreach ($languages as $code => $l): ?>
          <li role="none">
            <a role="menuitem" href="<?= htmlspecialchars(site_href($l['path']), ENT_QUOTES, 'UTF-8') ?>" hreflang="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>" class="<?= $code === $lang ? 'active' : '' ?>">
              <span class="lang-code"><?= htmlspecialchars((string) $l['code'], ENT_QUOTES, 'UTF-8') ?></span>
              <span class="lang-name"><?= htmlspecialchars((string) $l['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <a href="#contact" class="btn btn-primary btn-sm"><?= htmlspecialchars($t['nav']['cta']) ?></a>
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav" aria-label="Menu">
        <?= icon('menu', 'icon icon-open') ?><?= icon('close', 'icon icon-close') ?>
      </button>
    </div>
  </div>
</header>

<main id="main">

  <!-- HERO -->
  <section class="hero">
    <div class="container hero-inner">
      <div class="hero-copy reveal">
        <span class="eyebrow"><?= icon('star') ?> <?= htmlspecialchars($t['hero']['eyebrow']) ?></span>
        <h1><?= htmlspecialchars($t['hero']['title_line1']) ?><br><span class="grad-text"><?= htmlspecialchars($t['hero']['title_line2']) ?></span></h1>
        <p class="lead"><?= htmlspecialchars($t['hero']['subtitle']) ?></p>
        <div class="hero-cta">
          <a href="#contact" class="btn btn-primary btn-lg"><?= icon('arrow') ?> <?= htmlspecialchars($t['hero']['cta_primary']) ?></a>
          <a href="#prices" class="btn btn-ghost btn-lg"><?= htmlspecialchars($t['hero']['cta_secondary']) ?></a>
        </div>
      </div>
      <div class="hero-stats reveal">
        <?php foreach ($t['hero']['stats'] as $stat): ?>
        <div class="stat-card">
          <span class="stat-num" data-count="<?= htmlspecialchars($stat['num']) ?>"><?= htmlspecialchars($stat['num']) ?></span>
          <span class="stat-label"><?= htmlspecialchars($stat['label']) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section" id="about">
    <div class="container">
      <div class="about-grid">
        <div class="about-media reveal">
          <img src="<?= BASE_PATH ?>/assets/img/about.webp" alt="<?= htmlspecialchars(SITE_NAME) ?>" loading="lazy">
        </div>
        <div class="section-head reveal" style="text-align:left;margin:0">
          <span class="eyebrow"><?= htmlspecialchars($t['about']['eyebrow']) ?></span>
          <h2><?= htmlspecialchars($t['about']['title']) ?></h2>
          <p class="lead"><?= htmlspecialchars($t['about']['text']) ?></p>
        </div>
      </div>
      <div class="feature-grid">
        <?php foreach ($t['about']['features'] as $i => $f): ?>
        <div class="feature-card reveal" style="--d: <?= $i * 80 ?>ms">
          <span class="feature-icon"><?= icon($f['icon']) ?></span>
          <h3><?= htmlspecialchars($f['title']) ?></h3>
          <p><?= htmlspecialchars($f['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CATEGORIES -->
  <section class="section section-alt" id="categories">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow"><?= htmlspecialchars($t['categories']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['categories']['title']) ?></h2>
        <p class="lead"><?= htmlspecialchars($t['categories']['subtitle']) ?></p>
      </div>
      <div class="cat-grid">
        <?php foreach ($t['categories']['items'] as $i => $c): ?>
        <a class="cat-card reveal" style="--d: <?= $i * 60 ?>ms;display:block" href="<?= category_url($languages, $lang, $catSlugs[$i] ?? 'b') ?>">
          <span class="cat-icon"><?= icon($c['icon']) ?></span>
          <span class="cat-code"><?= htmlspecialchars($c['code']) ?></span>
          <h3><?= htmlspecialchars($c['title']) ?></h3>
          <p><?= htmlspecialchars($c['text']) ?></p>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PRICES -->
  <section class="section" id="prices">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow"><?= htmlspecialchars($t['prices']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['prices']['title']) ?></h2>
        <p class="lead"><?= htmlspecialchars($t['prices']['subtitle']) ?></p>
      </div>
      <div class="price-tables">
        <div class="price-card reveal">
          <h3><?= htmlspecialchars($t['prices']['full_course_title']) ?></h3>
          <table>
            <thead><tr><th><?= htmlspecialchars($t['prices']['col_category']) ?></th><th><?= htmlspecialchars($t['prices']['col_price']) ?></th></tr></thead>
            <tbody>
            <?php foreach ($t['prices']['full_course'] as $row): ?>
              <tr><td><?= htmlspecialchars($row['cat']) ?></td><td class="price-cell"><?= htmlspecialchars($row['price']) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="price-card reveal" style="--d:100ms">
          <h3><?= htmlspecialchars($t['prices']['extra_title']) ?></h3>
          <table>
            <thead><tr><th><?= htmlspecialchars($t['prices']['col_category']) ?></th><th><?= htmlspecialchars($t['prices']['col_price']) ?></th></tr></thead>
            <tbody>
            <?php foreach ($t['prices']['extra_lessons'] as $row): ?>
              <tr><td><?= htmlspecialchars($row['cat']) ?></td><td class="price-cell"><?= htmlspecialchars($row['price']) ?></td></tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <p class="price-note reveal"><?= htmlspecialchars($t['prices']['note']) ?></p>
      <div class="center reveal"><a href="#contact" class="btn btn-primary btn-lg"><?= icon('arrow') ?> <?= htmlspecialchars($t['prices']['cta']) ?></a></div>
    </div>
  </section>

  <!-- PROCESS -->
  <section class="section section-alt" id="process">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow"><?= htmlspecialchars($t['process']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['process']['title']) ?></h2>
      </div>
      <div class="process-grid">
        <?php foreach ($t['process']['steps'] as $i => $step): ?>
        <div class="process-card reveal" style="--d: <?= $i * 80 ?>ms">
          <span class="process-num"><?= htmlspecialchars($step['num']) ?></span>
          <h3><?= htmlspecialchars($step['title']) ?></h3>
          <p><?= htmlspecialchars($step['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- MANAGER -->
  <section class="section">
    <div class="container">
      <div class="manager-card reveal">
        <span class="feature-icon"><?= icon('shield') ?></span>
        <div>
          <span class="eyebrow"><?= htmlspecialchars($t['manager']['eyebrow']) ?></span>
          <h3><?= htmlspecialchars($t['manager']['name']) ?></h3>
          <p class="manager-role"><?= htmlspecialchars($t['manager']['role']) ?></p>
          <p><?= htmlspecialchars($t['manager']['text']) ?></p>
          <div class="manager-contacts">
            <a href="tel:<?= $s['phone1'] ?>"><?= icon('phone') ?> <?= $s['phone1'] ?></a>
            <a href="mailto:<?= $s['email'] ?>"><?= icon('mail') ?> <?= $s['email'] ?></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- REVIEWS -->
  <section class="section section-alt">
    <div class="container center reveal">
      <span class="eyebrow"><?= icon('star') ?> <?= htmlspecialchars($t['reviews']['eyebrow']) ?></span>
      <h2><?= htmlspecialchars($t['reviews']['title']) ?></h2>
      <p class="lead"><?= htmlspecialchars($t['reviews']['text']) ?></p>
      <a class="btn btn-ghost" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=<?= urlencode('Vilniaus vairavimo mokykla, ' . $s['address']) ?>"><?= icon('star') ?> <?= htmlspecialchars($t['reviews']['cta']) ?></a>
    </div>
  </section>

  <!-- FAQ -->
  <section class="section" id="faq">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow"><?= htmlspecialchars($t['faq']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['faq']['title']) ?></h2>
      </div>
      <div class="faq-list reveal">
        <?php foreach ($t['faq']['items'] as $idx => $item): ?>
        <div class="faq-item">
          <button class="faq-q" aria-expanded="false">
            <span><?= htmlspecialchars($item['q']) ?></span>
            <?= icon('chevron', 'icon faq-chevron') ?>
          </button>
          <div class="faq-a"><p><?= htmlspecialchars($item['a']) ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CONTACT -->
  <section class="section section-alt" id="contact">
    <div class="container">
      <div class="section-head reveal">
        <span class="eyebrow"><?= htmlspecialchars($t['contact']['eyebrow']) ?></span>
        <h2><?= htmlspecialchars($t['contact']['title']) ?></h2>
        <p class="lead"><?= htmlspecialchars($t['contact']['subtitle']) ?></p>
      </div>

      <div class="contact-grid">
        <div class="contact-info reveal">
          <div class="info-row"><?= icon('pin') ?><div><span><?= htmlspecialchars($t['contact']['address_label']) ?></span><strong><?= htmlspecialchars($s['address']) ?></strong></div></div>
          <div class="info-row"><?= icon('phone') ?><div><span><?= htmlspecialchars($t['contact']['phone_label']) ?></span><strong><a href="tel:<?= $s['phone1'] ?>"><?= $s['phone1'] ?></a> / <a href="tel:<?= $s['phone2'] ?>"><?= $s['phone2'] ?></a></strong></div></div>
          <div class="info-row"><?= icon('mail') ?><div><span><?= htmlspecialchars($t['contact']['email_label']) ?></span><strong><a href="mailto:<?= $s['email'] ?>"><?= $s['email'] ?></a></strong></div></div>
          <div class="info-row"><?= icon('clock') ?><div><span><?= htmlspecialchars($t['contact']['hours_label']) ?></span><strong><?= htmlspecialchars($s['hours_' . $lang]) ?></strong></div></div>

          <div class="map-wrap">
            <p class="map-title"><?= htmlspecialchars($t['contact']['map_title']) ?></p>
            <iframe loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=<?= urlencode($s['address']) ?>&output=embed" title="Map"></iframe>
          </div>
        </div>

        <form class="contact-form reveal" id="contactForm" novalidate>
          <input type="hidden" name="lang" value="<?= $t['html_lang'] ?>">
          <div class="form-row-hp"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
          <input type="hidden" name="ts" value="<?= time() ?>">

          <label class="form-field">
            <span><?= htmlspecialchars($t['contact']['form_name']) ?> *</span>
            <input type="text" name="name" required>
          </label>
          <label class="form-field">
            <span><?= htmlspecialchars($t['contact']['form_phone']) ?> *</span>
            <input type="tel" name="phone" required>
          </label>
          <label class="form-field">
            <span><?= htmlspecialchars($t['contact']['form_email']) ?></span>
            <input type="email" name="email">
          </label>
          <label class="form-field">
            <span><?= htmlspecialchars($t['contact']['form_category']) ?> *</span>
            <select name="category" required>
              <?php foreach (['B','A / A1 / A2','C','C+CE','C+CE+D','D','CE','BE'] as $cat): ?>
              <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
              <?php endforeach; ?>
            </select>
          </label>
          <label class="form-field form-field-full">
            <span><?= htmlspecialchars($t['contact']['form_message']) ?></span>
            <textarea name="message" rows="3"></textarea>
          </label>
          <label class="form-consent form-field-full">
            <input type="checkbox" name="consent" required>
            <span><?= htmlspecialchars($t['contact']['form_consent']) ?><a href="<?= $t['dir_prefix'] ?>/privacy.php"><?= htmlspecialchars($t['contact']['form_privacy_link']) ?></a></span>
          </label>
          <?php if (!empty($s['recaptcha_site_key'])): ?>
          <div class="g-recaptcha form-field-full" data-sitekey="<?= htmlspecialchars($s['recaptcha_site_key']) ?>"></div>
          <?php endif; ?>

          <button type="submit" class="btn btn-primary btn-lg form-field-full">
            <span class="btn-label"><?= icon('arrow') ?> <?= htmlspecialchars($t['contact']['form_submit']) ?></span>
          </button>
          <p class="form-status" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>
  </section>

</main>

<?php render_site_footer($t, $lang, $s, $languages); ?>

<button id="backToTop" class="back-to-top" aria-label="<?= htmlspecialchars($t['back_to_top']) ?>"><?= icon('top') ?></button>

<?php render_popups($t, $lang); ?>

<script>
window.SITE_I18N = <?= json_encode([
  'sending' => $t['contact']['form_sending'],
  'submit' => $t['contact']['form_submit'],
  'success' => $t['contact']['form_success'],
  'error' => $t['contact']['form_error'],
  'errorFields' => $t['contact']['form_error_fields'],
  'recaptchaRequired' => $t['contact']['form_recaptcha_required'] ?? '',
], JSON_UNESCAPED_UNICODE) ?>;
</script>
<script src="<?= asset_url('/assets/js/main.js') ?>" defer></script>
</body>
</html>
