<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/gallery_store.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } elseif (($_POST['action'] ?? '') === 'update_caption') {
        save_gallery_item(['id' => (int)($_POST['id'] ?? 0), 'caption' => trim((string)($_POST['caption'] ?? ''))]);
        log_activity('gallery_caption_save', "id=" . (int)($_POST['id'] ?? 0));
        $notice = 'ok:' . at('gallery_saved', 'Saved');
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        foreach (get_gallery_items() as $it) {
            if ((int)$it['id'] === $id) {
                $file = dirname(__DIR__) . $it['image'];
                if (is_file($file)) @unlink($file);
                break;
            }
        }
        delete_gallery_item($id);
        log_activity('gallery_delete', "id={$id}");
        $notice = 'ok:' . at('gallery_deleted', 'Deleted');
    }
}

$items = get_gallery_items();
usort($items, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

admin_header(at('gallery_title', 'Gallery'), 'gallery');
?>
<h1><?= htmlspecialchars(at('gallery_title', 'Gallery')) ?> <?php if (!empty($items)): ?><span class="muted">(<?= count($items) ?>)</span><?php endif; ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('gallery_upload_title', 'Upload photos')) ?></h2>
  <div class="image-uploader" id="galleryUploader" data-csrf="<?= htmlspecialchars(csrf_token()) ?>">
    <div class="image-drop" tabindex="0" id="galleryDrop">
      <span class="image-drop-hint"><?= htmlspecialchars(at('gallery_upload_hint', 'Drag photos here or click to browse (JPG/PNG/WebP, multiple allowed)')) ?></span>
    </div>
    <input type="file" accept="image/*" multiple id="galleryFileInput" hidden>
  </div>
  <p class="ai-assist-status" id="galleryUploadStatus"></p>
</div>

<div class="admin-card">
  <h2><?= htmlspecialchars(at('gallery_all_photos', 'All photos')) ?></h2>
  <?php if (empty($items)): ?>
    <p class="empty"><?= htmlspecialchars(at('gallery_empty', 'No photos yet.')) ?></p>
  <?php else: ?>
    <div class="gallery-admin-grid">
      <?php foreach ($items as $it): ?>
        <div class="gallery-admin-item">
          <img src="<?= htmlspecialchars($it['image']) ?>" alt="" loading="lazy">
          <form method="post" class="gallery-caption-form">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
            <input type="hidden" name="action" value="update_caption">
            <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
            <input type="text" name="caption" value="<?= htmlspecialchars($it['caption'] ?? '') ?>" placeholder="<?= htmlspecialchars(at('gallery_caption_placeholder', 'Caption (optional)')) ?>">
            <button type="submit" class="btn btn-outline btn-sm"><?= htmlspecialchars(at('save')) ?></button>
          </form>
          <form method="post" onsubmit="return confirm('<?= htmlspecialchars(at('gallery_delete_confirm', 'Delete this photo?')) ?>')">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
            <button type="submit" class="btn btn-danger btn-sm"><?= htmlspecialchars(at('delete')) ?></button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
(function(){
  var drop = document.getElementById('galleryDrop');
  var input = document.getElementById('galleryFileInput');
  var status = document.getElementById('galleryUploadStatus');
  var csrf = document.getElementById('galleryUploader').getAttribute('data-csrf');

  function uploadFiles(files){
    var list = Array.prototype.slice.call(files).filter(function(f){ return f.type.indexOf('image/') === 0; });
    if (!list.length) return;
    var done = 0;
    status.textContent = '0 / ' + list.length;
    list.forEach(function(file){
      var fd = new FormData();
      fd.append('csrf', csrf);
      fd.append('image', file);
      fetch(BASE_PATH + '/admin/upload-gallery-image.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(res){
          done++;
          status.textContent = done + ' / ' + list.length;
          if (done === list.length) window.location.reload();
        })
        .catch(function(){
          done++;
          status.textContent = done + ' / ' + list.length + ' (' + '<?= htmlspecialchars(at('gallery_upload_error', 'error')) ?>' + ')';
        });
    });
  }

  drop.addEventListener('click', function(){ input.click(); });
  drop.addEventListener('keydown', function(e){ if (e.key === 'Enter' || e.key === ' ') input.click(); });
  input.addEventListener('change', function(){ uploadFiles(input.files); });
  ['dragenter','dragover'].forEach(function(evt){
    drop.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); drop.classList.add('drag-over'); });
  });
  ['dragleave','drop'].forEach(function(evt){
    drop.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); drop.classList.remove('drag-over'); });
  });
  drop.addEventListener('drop', function(e){
    if (e.dataTransfer.files && e.dataTransfer.files.length) uploadFiles(e.dataTransfer.files);
  });
})();
</script>

<?php admin_footer(); ?>
