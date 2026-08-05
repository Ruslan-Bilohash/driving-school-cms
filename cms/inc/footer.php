<?php
require_once __DIR__ . '/categories.php';
require_once __DIR__ . '/districts.php';

function render_site_footer(array $t, string $lang, array $s, array $languages): void {
    $catSlugs = category_slugs();
    $distSlugs = district_slugs();
    $prefix = $t['dir_prefix'];
    $home = $prefix ?: '/';
    $year = date('Y');
    ?>
    <footer class="site-footer">
      <div class="container footer-grid">
        <div>
          <div class="logo"><span class="logo-mark"><img src="<?= BASE_PATH ?>/assets/img/logo-v3.webp" alt="" width="38" height="38"></span><span class="logo-text"><?= htmlspecialchars(SITE_NAME) ?></span></div>
          <p><?= htmlspecialchars($t['footer']['about']) ?></p>
          <div class="footer-social">
            <a href="tel:<?= htmlspecialchars($s['phone1']) ?>" aria-label="Phone"><?= icon('phone') ?></a>
            <a href="mailto:<?= htmlspecialchars($s['email']) ?>" aria-label="Email"><?= icon('mail') ?></a>
            <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $s['phone1'])) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><?= icon('whatsapp') ?></a>
          </div>
        </div>
        <div>
          <h4><?= htmlspecialchars($t['footer']['nav_title']) ?></h4>
          <a href="<?= $home ?>#about"><?= htmlspecialchars($t['nav']['about']) ?></a>
          <a href="<?= $home ?>#categories"><?= htmlspecialchars($t['nav']['categories']) ?></a>
          <a href="<?= $home ?>#prices"><?= htmlspecialchars($t['nav']['prices']) ?></a>
          <a href="<?= $home ?>#faq"><?= htmlspecialchars($t['nav']['faq']) ?></a>
          <a href="<?= $prefix ?>/news.php"><?= htmlspecialchars($t['nav']['news']) ?></a>
          <a href="<?= $prefix ?>/gallery.php"><?= htmlspecialchars($t['nav']['gallery']) ?></a>
        </div>
        <div>
          <h4><?= htmlspecialchars($t['categories']['eyebrow']) ?></h4>
          <?php foreach ($t['categories']['items'] as $i => $c): ?>
            <a href="<?= category_url($languages, $lang, $catSlugs[$i] ?? 'b') ?>"><?= htmlspecialchars($c['title']) ?></a>
          <?php endforeach; ?>
        </div>
        <div>
          <h4><?= htmlspecialchars($t['footer']['contact_title']) ?></h4>
          <a href="tel:<?= htmlspecialchars($s['phone1']) ?>"><?= htmlspecialchars($s['phone1']) ?></a>
          <a href="mailto:<?= htmlspecialchars($s['email']) ?>"><?= htmlspecialchars($s['email']) ?></a>
          <span><?= htmlspecialchars($s['address']) ?></span>
        </div>
      </div>
      <div class="container footer-areas">
        <span class="footer-areas-label"><?= htmlspecialchars($t['district_page']['other_areas']) ?>:</span>
        <?php foreach ($distSlugs as $ds): $d = get_district($lang, $ds); if (!$d) continue; ?>
          <a href="<?= district_url($languages, $lang, $ds) ?>"><?= htmlspecialchars($d['name']) ?></a>
        <?php endforeach; ?>
      </div>
      <div class="container footer-bottom">
        <span>&copy; <?= $year ?> <?= htmlspecialchars(SITE_NAME) ?>. <?= htmlspecialchars($t['footer']['rights']) ?></span>
        <span><?= htmlspecialchars($t['footer']['legal']) ?></span>
        <a href="<?= $prefix ?>/privacy.php"><?= htmlspecialchars($t['footer']['privacy']) ?></a>
        <a href="<?= $prefix ?>/terms.php"><?= htmlspecialchars($t['footer']['terms']) ?></a>
      </div>
      <div class="container dev-credit-wrap">
        <a class="dev-credit" href="https://bilohash.com" target="_blank" rel="noopener">
          <?= icon('code') ?>
          <span><?= $t['footer']['dev_credit'] ?></span>
        </a>
      </div>
    </footer>
    <?php
}
