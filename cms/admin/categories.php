<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/icons.php';
require_once __DIR__ . '/../inc/categories.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

// Content languages with category data files; labels from admin_lang_options() (unicode-safe).
$contentLangs = ['no', 'en', 'uk', 'ru', 'lt'];
$langOpts = admin_lang_options();
$langs = [];
foreach ($contentLangs as $code) {
    if (isset($langOpts[$code])) {
        $langs[$code] = $langOpts[$code];
    }
}
$lang = (string) ($_GET['lang'] ?? 'no');
if (!isset($langs[$lang])) {
    $lang = 'no';
}

$slugs = category_slugs();
$meta = category_meta();
$slug = $_GET['slug'] ?? 'b';
if (!in_array($slug, $slugs, true)) $slug = 'b';

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } else {
        $fields = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'h1' => trim((string)($_POST['h1'] ?? '')),
            'meta_title' => trim((string)($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')),
            'intro' => trim((string)($_POST['intro'] ?? '')),
            'who_for' => trim((string)($_POST['who_for'] ?? '')),
            'duration' => trim((string)($_POST['duration'] ?? '')),
            'image' => trim((string)($_POST['image'] ?? '')),
            'requirements' => array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['requirements'] ?? ''))))),
            'includes' => array_values(array_filter(array_map('trim', explode("\n", (string)($_POST['includes'] ?? ''))))),
            'related' => array_values(array_intersect($_POST['related'] ?? [], $slugs)),
        ];
        $faq = [];
        $qs = $_POST['faq_q'] ?? [];
        $as = $_POST['faq_a'] ?? [];
        foreach ($qs as $i => $q) {
            $q = trim((string)$q);
            $a = trim((string)($as[$i] ?? ''));
            if ($q !== '' && $a !== '') $faq[] = ['q' => $q, 'a' => $a];
        }
        $fields['faq'] = $faq;

        save_category_override($lang, $slug, $fields);
        log_activity('category_save', "lang={$lang} slug={$slug}");
        $notice = 'ok:' . at('cat_saved');
    }
}

$cat = get_category($lang, $slug);

admin_header(at('cat_title'), 'categories');
?>
<h1><?= htmlspecialchars(at('cat_title')) ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div class="admin-card">
  <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap">
    <?php foreach ($langs as $code => $label): ?>
      <a class="btn <?= $code === $lang ? '' : 'btn-outline' ?> btn-sm" href="<?= BASE_PATH ?>/admin/categories.php?lang=<?= urlencode($code) ?>&slug=<?= urlencode($slug) ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a>
    <?php endforeach; ?>
  </div>
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <?php foreach ($slugs as $sl): ?>
      <a class="btn <?= $sl === $slug ? '' : 'btn-outline' ?> btn-sm" href="<?= BASE_PATH ?>/admin/categories.php?lang=<?= urlencode($lang) ?>&slug=<?= urlencode($sl) ?>"><?= htmlspecialchars(strtoupper($sl), ENT_QUOTES, 'UTF-8') ?></a>
    <?php endforeach; ?>
  </div>
</div>

<?php if ($cat): ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

  <h2><?= htmlspecialchars(at('cat_section_main')) ?></h2>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('cat_field_title')) ?><input type="text" name="title" value="<?= htmlspecialchars($cat['title']) ?>"></label>
    <label><?= htmlspecialchars(at('cat_field_h1')) ?><input type="text" name="h1" value="<?= htmlspecialchars($cat['h1']) ?>"></label>
  </div>
  <label><?= htmlspecialchars(at('cat_field_meta_title')) ?><input type="text" name="meta_title" value="<?= htmlspecialchars($cat['meta_title']) ?>"></label>
  <label><?= htmlspecialchars(at('cat_field_meta_desc')) ?><textarea name="meta_description" rows="2"><?= htmlspecialchars($cat['meta_description']) ?></textarea></label>

  <hr class="section-divider">
  <h2><?= htmlspecialchars(at('cat_section_image')) ?></h2>
  <?php image_uploader('image', $cat['image'] ?? '', csrf_token()); ?>

  <hr class="section-divider">
  <h2><?= htmlspecialchars(at('cat_section_text')) ?></h2>
  <label><?= htmlspecialchars(at('cat_field_intro')) ?><textarea name="intro" rows="3"><?= htmlspecialchars($cat['intro']) ?></textarea></label>
  <label><?= htmlspecialchars(at('cat_field_who_for')) ?><textarea name="who_for" rows="2"><?= htmlspecialchars($cat['who_for']) ?></textarea></label>
  <label><?= htmlspecialchars(at('cat_field_duration')) ?><input type="text" name="duration" value="<?= htmlspecialchars($cat['duration']) ?>"></label>

  <div class="grid-2">
    <label><?= htmlspecialchars(at('cat_field_requirements')) ?><textarea name="requirements" rows="6"><?= htmlspecialchars(implode("\n", $cat['requirements'])) ?></textarea></label>
    <label><?= htmlspecialchars(at('cat_field_includes')) ?><textarea name="includes" rows="6"><?= htmlspecialchars(implode("\n", $cat['includes'])) ?></textarea></label>
  </div>

  <hr class="section-divider">
  <h2><?= htmlspecialchars(at('cat_section_faq')) ?></h2>
  <?php for ($i = 0; $i < 6; $i++): $item = $cat['faq'][$i] ?? ['q' => '', 'a' => '']; ?>
    <div class="grid-2">
      <label><?= htmlspecialchars(at('cat_faq_q')) ?> <?= $i + 1 ?><input type="text" name="faq_q[]" value="<?= htmlspecialchars($item['q']) ?>"></label>
      <label><?= htmlspecialchars(at('cat_faq_a')) ?> <?= $i + 1 ?><input type="text" name="faq_a[]" value="<?= htmlspecialchars($item['a']) ?>"></label>
    </div>
  <?php endfor; ?>

  <hr class="section-divider">
  <h2><?= htmlspecialchars(at('cat_section_related')) ?></h2>
  <div class="grid-3">
    <?php foreach ($slugs as $sl): if ($sl === $slug) continue; ?>
      <label style="flex-direction:row;align-items:center;gap:8px">
        <input type="checkbox" name="related[]" value="<?= $sl ?>" style="width:auto" <?= in_array($sl, $cat['related'] ?? [], true) ? 'checked' : '' ?>>
        <span><?= htmlspecialchars(strtoupper($sl), ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars(get_category($lang, $sl)['title'] ?? $sl, ENT_QUOTES, 'UTF-8') ?></span>
      </label>
    <?php endforeach; ?>
  </div>

  <button type="submit" class="btn" style="margin-top:16px"><?= htmlspecialchars(at('cat_save')) ?></button>
</form>
<?php endif; ?>
<?php admin_footer(); ?>
