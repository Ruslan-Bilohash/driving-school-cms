<?php
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/icons.php';
require_once __DIR__ . '/marketing.php';

function render_popups(array $t, string $lang): void {
    $s = get_settings();
    ?>
    <div class="callback-overlay" id="callbackOverlay"></div>
    <div class="callback-modal" id="callbackModal" role="dialog" aria-modal="true" aria-labelledby="callbackTitle">
      <button class="callback-close" id="callbackClose" aria-label="Close">&times;</button>
      <h3 id="callbackTitle"><?= htmlspecialchars($t['callback']['title']) ?></h3>
      <p><?= htmlspecialchars($t['callback']['text']) ?></p>
      <form id="callbackForm">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
        <input type="hidden" name="category" value="Perskambinimas / Callback">
        <input type="hidden" name="ts" value="<?= time() ?>">
        <div class="form-row-hp"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <label class="form-field"><span><?= htmlspecialchars($t['callback']['name']) ?> *</span><input type="text" name="name" required></label>
        <label class="form-field"><span><?= htmlspecialchars($t['callback']['phone']) ?> *</span><input type="tel" name="phone" required></label>
        <label class="form-consent"><input type="checkbox" name="consent" required><span><?= htmlspecialchars($t['callback']['consent']) ?></span></label>
        <?php if (!empty($s['recaptcha_site_key'])): ?>
        <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($s['recaptcha_site_key']) ?>" style="margin-bottom:12px"></div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><?= htmlspecialchars($t['callback']['submit']) ?></button>
        <p class="form-status" role="status" aria-live="polite"></p>
      </form>
    </div>

    <div class="cookie-banner" id="cookieBanner">
      <p><?= htmlspecialchars($t['cookies']['text']) ?> <a href="<?= $t['dir_prefix'] ?>/privacy.php"><?= htmlspecialchars($t['cookies']['link']) ?></a></p>
      <button class="btn btn-primary btn-sm" id="cookieAccept"><?= htmlspecialchars($t['cookies']['accept']) ?></button>
    </div>

    <script>
    window.CALLBACK_I18N = <?= json_encode(['success' => $t['callback']['success'], 'errorFields' => $t['contact']['form_error_fields'] ?? '', 'error' => $t['contact']['form_error'] ?? '', 'recaptchaRequired' => $t['contact']['form_recaptcha_required'] ?? ''], JSON_UNESCAPED_UNICODE) ?>;
    <?php if (!empty($s['recaptcha_site_key'])): ?>
    window.RECAPTCHA_SITE_KEY = <?= json_encode($s['recaptcha_site_key']) ?>;
    window.__recaptchaWidgets = new Map();
    window.onRecaptchaLoad = function(){
      document.querySelectorAll('.g-recaptcha').forEach(function(el){
        var id = grecaptcha.render(el, { sitekey: el.getAttribute('data-sitekey') });
        window.__recaptchaWidgets.set(el, id);
      });
    };
    <?php endif; ?>
    </script>
    <?php if (!empty($s['recaptcha_site_key'])): ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>
    <?php endif; ?>

    <?php if (!empty($s['chat_widget_enabled'])): ?>
    <div class="chat-widget" id="chatWidget">
      <button class="chat-bubble" id="chatBubble" aria-label="<?= htmlspecialchars($t['chat']['title']) ?>"><?= icon('chat') ?></button>
      <div class="chat-panel" id="chatPanel">
        <div class="chat-panel-head">
          <span><?= icon('chat') ?> <?= htmlspecialchars($t['chat']['title']) ?></span>
          <button id="chatPanelClose" aria-label="Close">&times;</button>
        </div>
        <div class="chat-messages" id="chatMessages">
          <div class="chat-msg chat-msg-bot"><?= htmlspecialchars($t['chat']['greeting']) ?></div>
        </div>
        <form class="chat-input-row" id="chatForm">
          <input type="text" id="chatInput" placeholder="<?= htmlspecialchars($t['chat']['placeholder']) ?>" autocomplete="off" required>
          <button type="submit" aria-label="<?= htmlspecialchars($t['chat']['send']) ?>"><?= icon('send') ?></button>
        </form>
        <a href="https://bilohash.com/" target="_blank" rel="noopener" class="chat-credit">AI CHAT Consultant — bilohash.com</a>
      </div>
    </div>
    <script>
    window.CHAT_I18N = <?= json_encode(['thinking' => $t['chat']['thinking'], 'error' => $t['chat']['error'] . ($s['phone1'] ?? '')], JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <?php endif; ?>
    <?php
    render_marketing($t, $lang);
}
