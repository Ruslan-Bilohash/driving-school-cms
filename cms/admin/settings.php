<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/store.php';
require_once __DIR__ . '/../inc/settings.php';
require_once __DIR__ . '/../inc/activity_log.php';
require_once __DIR__ . '/../inc/admin_layout.php';

require_login();

$notice = '';
$priceKeysFull = ['B' => 'B', 'A' => 'A, A1, A2', 'C' => 'C', 'CCE' => 'C+CE', 'CCED' => 'C+CE+D', 'D' => 'D', 'CE' => 'CE', 'BE' => 'BE', 'LOST' => 'Praradusiems / Lost licence'];
$priceKeysExtra = ['B' => 'B', 'A' => 'A, A1, A2', 'CCE' => 'C, CE', 'D' => 'D', 'BE' => 'BE'];

$sections = [
    'contacts' => at('settings_section_contacts'),
    'seo' => at('settings_section_seo'),
    'prices' => at('settings_section_prices_full') . ' / ' . at('settings_section_prices_extra'),
    'ai' => at('settings_section_ai'),
    'security' => at('settings_section_security', 'Security / reCAPTCHA'),
    'password' => at('settings_section_password'),
];
// Public demo: do not expose API/password sections as editable secrets
if (defined('DS_DEMO_MODE') && DS_DEMO_MODE) {
    $sections['ai'] = at('settings_section_ai') . ' (demo)';
    $sections['security'] = at('settings_section_security', 'Security / reCAPTCHA') . ' (demo)';
    $sections['password'] = at('settings_section_password') . ' (demo)';
}
$section = $_GET['section'] ?? 'contacts';
if (!isset($sections[$section])) $section = 'contacts';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check($_POST['csrf'] ?? null)) {
        $notice = 'err:' . at('session_expired');
    } elseif (defined('DS_DEMO_MODE') && DS_DEMO_MODE && in_array((string) ($_POST['form'] ?? ''), ['ai', 'security', 'password'], true)) {
        $notice = 'err:Demo mode — API keys, reCAPTCHA secrets and password changes are disabled on the public portfolio demo.';
    } else {
        $form = $_POST['form'] ?? '';
        $s = get_settings();

        if ($form === 'contacts') {
            $s['phone1'] = trim((string)($_POST['phone1'] ?? $s['phone1']));
            $s['phone2'] = trim((string)($_POST['phone2'] ?? $s['phone2']));
            $email = trim((string)($_POST['email'] ?? $s['email']));
            $s['email'] = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : $s['email'];
            $s['address'] = trim((string)($_POST['address'] ?? $s['address']));
            foreach (['no', 'en', 'sv', 'pl', 'uk', 'ru', 'lt'] as $lc) {
                $s['hours_' . $lc] = trim((string)($_POST['hours_' . $lc] ?? $s['hours_' . $lc] ?? ''));
            }
            save_settings($s);
            log_activity('settings_save', "section={$form}");
            $notice = 'ok:' . at('settings_saved');
        } elseif ($form === 'seo') {
            foreach (['no', 'en', 'sv', 'pl', 'uk', 'ru', 'lt'] as $lc) {
                $s['description_' . $lc] = mb_substr(trim((string)($_POST['description_' . $lc] ?? $s['description_' . $lc] ?? '')), 0, 300);
            }
            save_settings($s);
            log_activity('settings_save', "section={$form}");
            $notice = 'ok:' . at('settings_saved');
        } elseif ($form === 'prices') {
            foreach ($priceKeysFull as $key => $label) {
                if (isset($_POST['price_full'][$key]) && is_numeric($_POST['price_full'][$key])) {
                    $s['price_full'][$key] = (float)$_POST['price_full'][$key];
                }
            }
            foreach ($priceKeysExtra as $key => $label) {
                if (isset($_POST['price_extra'][$key]) && is_numeric($_POST['price_extra'][$key])) {
                    $s['price_extra'][$key] = (float)$_POST['price_extra'][$key];
                }
            }
            save_settings($s);
            log_activity('settings_save', "section={$form}");
            $notice = 'ok:' . at('settings_saved');
        } elseif ($form === 'ai') {
            if (trim((string)($_POST['openai_key'] ?? '')) !== '') {
                $s['openai_key'] = trim((string)$_POST['openai_key']);
            }
            if (trim((string)($_POST['grok_key'] ?? '')) !== '') {
                $s['grok_key'] = trim((string)$_POST['grok_key']);
            }
            if (!empty($_POST['clear_openai_key'])) $s['openai_key'] = '';
            if (!empty($_POST['clear_grok_key'])) $s['grok_key'] = '';
            $s['chat_widget_enabled'] = !empty($_POST['chat_widget_enabled']);
            $s['chat_widget_provider'] = in_array($_POST['chat_widget_provider'] ?? '', ['openai', 'grok']) ? $_POST['chat_widget_provider'] : 'openai';
            save_settings($s);
            log_activity('settings_save', "section={$form}");
            $notice = 'ok:' . at('settings_saved');
        } elseif ($form === 'security') {
            $s['recaptcha_site_key'] = trim((string)($_POST['recaptcha_site_key'] ?? $s['recaptcha_site_key']));
            if (trim((string)($_POST['recaptcha_secret_key'] ?? '')) !== '') {
                $s['recaptcha_secret_key'] = trim((string)$_POST['recaptcha_secret_key']);
            }
            if (!empty($_POST['clear_recaptcha'])) {
                $s['recaptcha_site_key'] = '';
                $s['recaptcha_secret_key'] = '';
            }
            save_settings($s);
            log_activity('settings_save', "section={$form}");
            $notice = 'ok:' . at('settings_saved');
        } elseif ($form === 'password') {
            $admin = get_admin();
            $current = (string)($_POST['current_password'] ?? '');
            $new = (string)($_POST['new_password'] ?? '');
            $newUsername = trim((string)($_POST['username'] ?? $admin['username']));
            if ($admin['password_hash'] !== '' && !password_verify($current, $admin['password_hash'])) {
                $notice = 'err:' . at('settings_password_wrong');
            } elseif (mb_strlen($new) < 8) {
                $notice = 'err:' . at('settings_password_short');
            } else {
                save_admin($newUsername !== '' ? $newUsername : $admin['username'], $new);
                log_activity('password_change', '');
                $notice = 'ok:' . at('settings_password_updated');
            }
        }
    }
}

$s = get_settings();
admin_header(at('settings_title'), 'settings');
?>
<h1><?= htmlspecialchars(at('settings_title')) ?></h1>
<?php if ($notice): [$type, $text] = explode(':', $notice, 2); ?>
  <div class="alert alert-<?= $type === 'ok' ? 'ok' : 'err' ?>"><?= htmlspecialchars($text) ?></div>
<?php endif; ?>

<div class="admin-card">
  <label style="margin:0"><?= htmlspecialchars(at('settings_section_label', 'Section')) ?>
    <select onchange="location.href='?section='+this.value">
      <?php foreach ($sections as $code => $label): ?>
        <option value="<?= $code ?>" <?= $code === $section ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
</div>

<?php if ($section === 'contacts'): ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="contacts">
  <h2><?= htmlspecialchars(at('settings_section_contacts')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_contacts_note', '')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_phone1')) ?><input type="text" name="phone1" value="<?= htmlspecialchars($s['phone1']) ?>"></label>
    <label><?= htmlspecialchars(at('settings_phone2')) ?><input type="text" name="phone2" value="<?= htmlspecialchars($s['phone2']) ?>"></label>
    <label><?= htmlspecialchars(at('settings_email')) ?><input type="email" name="email" value="<?= htmlspecialchars($s['email']) ?>"></label>
    <label><?= htmlspecialchars(at('settings_address')) ?><input type="text" name="address" value="<?= htmlspecialchars($s['address']) ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (NO)<input type="text" name="hours_no" value="<?= htmlspecialchars($s['hours_no'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (EN)<input type="text" name="hours_en" value="<?= htmlspecialchars($s['hours_en'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (SV)<input type="text" name="hours_sv" value="<?= htmlspecialchars($s['hours_sv'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (PL)<input type="text" name="hours_pl" value="<?= htmlspecialchars($s['hours_pl'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (UA)<input type="text" name="hours_uk" value="<?= htmlspecialchars($s['hours_uk'] ?? '') ?>"></label>
    <label><?= htmlspecialchars(at('settings_hours')) ?> (RU)<input type="text" name="hours_ru" value="<?= htmlspecialchars($s['hours_ru'] ?? '') ?>"></label>
  </div>
  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>

<?php elseif ($section === 'seo'): ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="seo">
  <h2><?= htmlspecialchars(at('settings_section_seo')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_seo_note', '')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_desc')) ?> (NO)<textarea name="description_no" rows="3"><?= htmlspecialchars($s['description_no'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('settings_desc')) ?> (EN)<textarea name="description_en" rows="3"><?= htmlspecialchars($s['description_en'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('settings_desc')) ?> (SV)<textarea name="description_sv" rows="3"><?= htmlspecialchars($s['description_sv'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('settings_desc')) ?> (PL)<textarea name="description_pl" rows="3"><?= htmlspecialchars($s['description_pl'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('settings_desc')) ?> (UA)<textarea name="description_uk" rows="3"><?= htmlspecialchars($s['description_uk'] ?? '') ?></textarea></label>
    <label><?= htmlspecialchars(at('settings_desc')) ?> (RU)<textarea name="description_ru" rows="3"><?= htmlspecialchars($s['description_ru'] ?? '') ?></textarea></label>
  </div>
  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>

<?php elseif ($section === 'prices'): ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="prices">
  <h2><?= htmlspecialchars(at('settings_section_prices_full')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_prices_note', '')) ?></p>
  <div class="grid-3">
    <?php foreach ($priceKeysFull as $key => $label): ?>
      <label><?= htmlspecialchars($label) ?><input type="number" step="1" min="0" name="price_full[<?= $key ?>]" value="<?= htmlspecialchars((string)$s['price_full'][$key]) ?>"></label>
    <?php endforeach; ?>
  </div>
  <hr class="section-divider">
  <h2><?= htmlspecialchars(at('settings_section_prices_extra')) ?></h2>
  <div class="grid-3">
    <?php foreach ($priceKeysExtra as $key => $label): ?>
      <label><?= htmlspecialchars($label) ?><input type="number" step="1" min="0" name="price_extra[<?= $key ?>]" value="<?= htmlspecialchars((string)$s['price_extra'][$key]) ?>"></label>
    <?php endforeach; ?>
  </div>
  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>

<?php elseif ($section === 'ai'): ?>
<?php if (defined('DS_DEMO_MODE') && DS_DEMO_MODE): ?>
<div class="admin-card">
  <h2><?= htmlspecialchars(at('settings_section_ai')) ?> — demo</h2>
  <div class="alert alert-ok">API keys (OpenAI / Grok) are <strong>not shown</strong> on the public portfolio demo. Fields stay empty; chat widget stays off.</div>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_openai_key')) ?>
      <input type="password" value="" placeholder="•••••••• hidden" disabled autocomplete="off">
    </label>
    <label><?= htmlspecialchars(at('settings_grok_key')) ?>
      <input type="password" value="" placeholder="•••••••• hidden" disabled autocomplete="off">
    </label>
  </div>
  <p class="muted">On a real client install you can paste keys here. Demo never stores visitor-visible secrets.</p>
</div>
<?php else: ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="ai">
  <h2><?= htmlspecialchars(at('settings_section_ai')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_ai_note')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_openai_key')) ?>
      <input type="password" name="openai_key" placeholder="<?= $s['openai_key'] ? '••••••••' : 'sk-...' ?>" autocomplete="off">
    </label>
    <label><?= htmlspecialchars(at('settings_grok_key')) ?>
      <input type="password" name="grok_key" placeholder="<?= $s['grok_key'] ? '••••••••' : 'xai-...' ?>" autocomplete="off">
    </label>
    <label style="flex-direction:row;align-items:center;gap:8px"><input type="checkbox" name="clear_openai_key" style="width:auto"><span><?= htmlspecialchars(at('settings_clear_openai')) ?></span></label>
    <label style="flex-direction:row;align-items:center;gap:8px"><input type="checkbox" name="clear_grok_key" style="width:auto"><span><?= htmlspecialchars(at('settings_clear_grok')) ?></span></label>
  </div>
  <div class="grid-2">
    <label style="flex-direction:row;align-items:center;gap:8px"><input type="checkbox" name="chat_widget_enabled" <?= !empty($s['chat_widget_enabled']) ? 'checked' : '' ?> style="width:auto"><span><?= htmlspecialchars(at('settings_chat_enabled')) ?></span></label>
    <label><?= htmlspecialchars(at('settings_chat_provider')) ?>
      <select name="chat_widget_provider">
        <option value="openai" <?= $s['chat_widget_provider'] === 'openai' ? 'selected' : '' ?>>OpenAI (GPT)</option>
        <option value="grok" <?= $s['chat_widget_provider'] === 'grok' ? 'selected' : '' ?>>xAI (Grok)</option>
      </select>
    </label>
  </div>
  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>
<?php endif; ?>

<?php elseif ($section === 'security'): ?>
<?php if (defined('DS_DEMO_MODE') && DS_DEMO_MODE): ?>
<div class="admin-card">
  <h2><?= htmlspecialchars(at('settings_section_security', 'Security / reCAPTCHA')) ?> — demo</h2>
  <div class="alert alert-ok">reCAPTCHA site/secret keys are <strong>hidden</strong> on this public demo. No secrets are printed in HTML.</div>
  <div class="grid-2">
    <label>reCAPTCHA Site Key
      <input type="text" value="" placeholder="•••••••• hidden" disabled autocomplete="off">
    </label>
    <label>reCAPTCHA Secret Key
      <input type="password" value="" placeholder="•••••••• hidden" disabled autocomplete="off">
    </label>
  </div>
</div>
<?php else: ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="security">
  <h2><?= htmlspecialchars(at('settings_section_security', 'Security / reCAPTCHA')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_recaptcha_note', 'Protects the contact and callback forms from spam bots. Get keys at google.com/recaptcha/admin.')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_recaptcha_site_key', 'reCAPTCHA Site Key (public)')) ?>
      <input type="text" name="recaptcha_site_key" value="<?= htmlspecialchars($s['recaptcha_site_key']) ?>" autocomplete="off">
    </label>
    <label><?= htmlspecialchars(at('settings_recaptcha_secret_key', 'reCAPTCHA Secret Key')) ?>
      <input type="password" name="recaptcha_secret_key" placeholder="<?= $s['recaptcha_secret_key'] ? '••••••••' : '' ?>" autocomplete="off">
      <span style="font-size:.8rem;margin-top:4px;display:block;color:<?= $s['recaptcha_secret_key'] ? 'var(--success, #1a9d5c)' : 'var(--danger, #d33)' ?>">
        <?= $s['recaptcha_secret_key']
          ? '✓ ' . htmlspecialchars(at('settings_recaptcha_secret_set', 'Key is saved (hidden for security).'))
          : '✗ ' . htmlspecialchars(at('settings_recaptcha_secret_missing', 'No key saved yet')) ?>
      </span>
    </label>
  </div>
  <label style="flex-direction:row;align-items:center;gap:8px"><input type="checkbox" name="clear_recaptcha" style="width:auto"><span><?= htmlspecialchars(at('settings_recaptcha_clear', 'Disable reCAPTCHA (clear both keys)')) ?></span></label>
  <button type="submit" class="btn"><?= htmlspecialchars(at('settings_save')) ?></button>
</form>
<?php endif; ?>

<?php elseif ($section === 'password'): ?>
<?php if (defined('DS_DEMO_MODE') && DS_DEMO_MODE): ?>
<div class="admin-card">
  <h2><?= htmlspecialchars(at('settings_section_password')) ?> — demo</h2>
  <div class="alert alert-ok">Portfolio demo account is fixed: <strong>demo / demo</strong>. Username and password cannot be changed here. Real client installs use their own credentials (never shown on this public demo).</div>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_username')) ?><input type="text" value="demo" disabled></label>
    <label><?= htmlspecialchars(at('settings_current_password')) ?><input type="password" value="" placeholder="••••••••" disabled></label>
    <label><?= htmlspecialchars(at('settings_new_password')) ?><input type="password" value="" placeholder="••••••••" disabled></label>
  </div>
</div>
<?php else: ?>
<form method="post" class="admin-card">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
  <input type="hidden" name="form" value="password">
  <h2><?= htmlspecialchars(at('settings_section_password')) ?></h2>
  <p class="muted"><?= htmlspecialchars(at('settings_password_note', '')) ?></p>
  <div class="grid-2">
    <label><?= htmlspecialchars(at('settings_username')) ?><input type="text" name="username" value="<?= htmlspecialchars(get_admin()['username']) ?>"></label>
    <label><?= htmlspecialchars(at('settings_current_password')) ?><input type="password" name="current_password"></label>
    <label><?= htmlspecialchars(at('settings_new_password')) ?><input type="password" name="new_password"></label>
  </div>
  <button type="submit" class="btn btn-outline"><?= htmlspecialchars(at('settings_password_update')) ?></button>
</form>
<?php endif; ?>
<?php endif; ?>

<?php admin_footer(); ?>
