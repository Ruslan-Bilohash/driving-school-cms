<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';
require_once __DIR__ . '/../inc/news_store.php';
require_once __DIR__ . '/../inc/ai_translate.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$langs = ['lt' => 'LietuviÅ³', 'uk' => 'Ð£ÐºÑ€Ð°Ñ—Ð½ÑÑŒÐºÐ°', 'en' => 'English', 'ru' => 'Ð ÑƒÑÑÐºÐ¸Ð¹'];
$lang = $_GET['lang'] ?? 'lt';
if (!isset($langs[$lang])) $lang = 'no';

$notice = '';
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } elseif (($_POST['action'] ?? '') === 'save') {
        $post = [
            'id' => (int)($_POST['id'] ?? 0) ?: null,
            'title' => trim((string)($_POST['title'] ?? '')),
            'slug' => trim((string)($_POST['slug'] ?? '')),
            'excerpt' => trim((string)($_POST['excerpt'] ?? '')),
            'meta_description' => trim((string)($_POST['meta_description'] ?? '')),
            'content' => sanitize_ai_html((string)($_POST['content'] ?? '')),
            'image' => trim((string)($_POST['image'] ?? '')),
            'published' => !empty($_POST['published']),
        ];
        if ($post['id'] === null) unset($post['id']);
        if ($post['title'] === '') {
            $notice = 'err:' . at('news_title_required');
        } else {
            save_news_post($lang, $post);
            log_activity('news_save', "lang={$lang} title=" . $post['title']);

            if (!empty($_POST['translate_others'])) {
                set_time_limit(120);
                $settingsForAi = get_settings();
                $otherLangs = array_diff(['lt', 'uk', 'en', 'ru'], [$lang]);
                $translatedCount = 0;
                foreach ($otherLangs as $targetLang) {
                    $translated = ai_translate_article($post, $targetLang, $settingsForAi);
                    if ($translated) {
                        save_news_post($targetLang, [
                            'title' => $translated['title'],
                            'slug' => '',
                            'excerpt' => $translated['excerpt'] ?? '',
                            'meta_description' => $translated['meta_description'] ?? '',
                            'content' => $translated['content'],
                            'image' => $post['image'],
                            'published' => $post['published'],
                        ]);
                        $translatedCount++;
                    }
                }
                $notice = 'ok:' . at('news_saved') . ' (' . $translatedCount . '/3 ' . at('news_translated_suffix', 'translations done') . ')';
            } else {
                $notice = 'ok:' . at('news_saved');
            }
        }
    } elseif (($_POST['action'] ?? '') === 'delete') {
        delete_news_post($lang, (int)($_POST['id'] ?? 0));
        log_activity('news_delete', "lang={$lang} id=" . (int)($_POST['id'] ?? 0));
        $notice = 'ok:' . at('news_deleted');
    }
}

if (isset($_GET['edit'])) {
    $editing = get_news_by_id($lang, (int)$_GET['edit']);
}

$posts = get_all_news($lang);
usort($posts, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$s = get_settings();
$hasAiKey = !empty($s['openai_key']) || !empty($s['grok_key']);

admin_header(at('news_title'), 'news');
?>
<h1><?= htmlspecialchars(at('news_title')) ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div class="admin-card">
  <div style="display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap">
    <?php foreach ($langs as $code => $label): ?>
      <a class="btn <?= $code === $lang ? '' : 'btn-outline' ?> btn-sm" href="<?= BASE_PATH ?>/admin/news.php?lang=<?= $code ?>"><?= $label ?></a>
    <?php endforeach; ?>
  </div>

  <?php if ($hasAiKey): ?>
  <div class="ai-assist-box" id="aiAssistBox">
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <input type="text" id="aiTopicInput" placeholder="<?= htmlspecialchars(at('news_ai_placeholder', 'ÐŸÑ€Ð¾ Ñ‰Ð¾ Ð½Ð°Ð¿Ð¸ÑÐ°Ñ‚Ð¸ ÑÑ‚Ð°Ñ‚Ñ‚ÑŽ?')) ?>" style="flex:1;min-width:220px">
      <button type="button" class="btn btn-sm" id="aiGenerateBtn"><?= htmlspecialchars(at('news_ai_button')) ?></button>
    </div>
    <p class="ai-assist-status" id="aiAssistStatus"></p>
  </div>
  <?php endif; ?>

  <form method="post" id="newsForm">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
    <input type="hidden" name="action" value="save">
    <input type="hidden" name="id" value="<?= (int)($editing['id'] ?? 0) ?>">
    <input type="hidden" id="newsLangField" value="<?= htmlspecialchars($lang) ?>">
    <div class="grid-2">
      <label><?= htmlspecialchars(at('news_field_title')) ?><input type="text" name="title" id="newsTitleField" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required></label>
      <label><?= htmlspecialchars(at('news_field_slug')) ?><input type="text" name="slug" value="<?= htmlspecialchars($editing['slug'] ?? '') ?>"></label>
    </div>
    <label><?= htmlspecialchars(at('news_field_image')) ?></label>
    <?php image_uploader('image', $editing['image'] ?? '', csrf_token()); ?>
    <label><?= htmlspecialchars(at('news_field_excerpt')) ?><textarea name="excerpt" id="newsExcerptField" rows="2"><?= htmlspecialchars($editing['excerpt'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('news_field_meta_desc')) ?><textarea name="meta_description" id="newsMetaField" rows="2"><?= htmlspecialchars($editing['meta_description'] ?? '') ?></textarea></label>

    <div class="seo-checklist" id="seoChecklist">
      <h3><?= htmlspecialchars(at('seo_checklist_title', 'SEO checklist')) ?></h3>
      <ul>
        <li data-check="title"><span class="seo-dot"></span><span class="seo-text"></span></li>
        <li data-check="meta"><span class="seo-dot"></span><span class="seo-text"></span></li>
        <li data-check="excerpt"><span class="seo-dot"></span><span class="seo-text"></span></li>
        <li data-check="heading"><span class="seo-dot"></span><span class="seo-text"></span></li>
        <li data-check="length"><span class="seo-dot"></span><span class="seo-text"></span></li>
        <li data-check="image"><span class="seo-dot"></span><span class="seo-text"></span></li>
      </ul>
    </div>

    <label><?= htmlspecialchars(at('news_field_content')) ?></label>
    <?php rich_text_editor('content', $editing['content'] ?? ''); ?>
    <label style="flex-direction:row;align-items:center;gap:8px">
      <input type="checkbox" name="published" <?= !empty($editing['published']) ? 'checked' : '' ?> style="width:auto">
      <span><?= htmlspecialchars(at('news_field_published')) ?></span>
    </label>
    <?php if ($hasAiKey): ?>
    <label style="flex-direction:row;align-items:center;gap:8px">
      <input type="checkbox" name="translate_others" style="width:auto">
      <span><?= htmlspecialchars(at('news_translate_others', 'Translate to other 3 languages via AI on save')) ?></span>
    </label>
    <?php endif; ?>
    <div style="display:flex;gap:10px;align-items:center;margin-top:8px">
      <button type="submit" class="btn"><?= htmlspecialchars($editing ? at('news_save') : at('news_add')) ?></button>
      <?php if ($editing): ?><a href="<?= BASE_PATH ?>/admin/news.php?lang=<?= $lang ?>" class="btn btn-outline"><?= htmlspecialchars(at('cancel')) ?></a><?php endif; ?>
    </div>
  </form>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('news_all_articles')) ?> (<?= $langs[$lang] ?>)</h2>
  <?php if (empty($posts)): ?>
    <p class="empty"><?= htmlspecialchars(at('news_empty')) ?></p>
  <?php else: ?>
    <table>
      <thead><tr><th><?= htmlspecialchars(at('news_col_title')) ?></th><th><?= htmlspecialchars(at('news_col_status')) ?></th><th><?= htmlspecialchars(at('news_col_date')) ?></th><th></th></tr></thead>
      <tbody>
      <?php foreach ($posts as $p): ?>
        <tr>
          <td data-label="<?= htmlspecialchars(at('news_col_title')) ?>"><?= htmlspecialchars($p['title']) ?></td>
          <td data-label="<?= htmlspecialchars(at('news_col_status')) ?>"><span class="badge <?= !empty($p['published']) ? 'badge-done' : 'badge-new' ?>"><?= !empty($p['published']) ? htmlspecialchars(at('news_status_published')) : htmlspecialchars(at('news_status_draft')) ?></span></td>
          <td data-label="<?= htmlspecialchars(at('news_col_date')) ?>"><?= htmlspecialchars(substr($p['created_at'] ?? '', 0, 10)) ?></td>
          <td data-label="">
            <a class="btn btn-outline btn-sm" href="<?= BASE_PATH ?>/admin/news.php?lang=<?= $lang ?>&edit=<?= (int)$p['id'] ?>"><?= htmlspecialchars(at('edit')) ?></a>
            <form method="post" style="display:inline" onsubmit="return confirm('<?= htmlspecialchars(at('news_delete_confirm')) ?>')">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm"><?= htmlspecialchars(at('delete')) ?></button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
<?php admin_footer(); ?>
