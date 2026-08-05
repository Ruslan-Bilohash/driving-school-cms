<?php
/**
 * Admin logout — animated goodbye screen, then redirect to login.
 */
declare(strict_types=1);

require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/auth.php';
require_once __DIR__ . '/../inc/admin_lang.php';

admin_session_start();

// Keep UI language for the goodbye screen + login redirect
$alang = get_admin_lang();
if (!isset(admin_lang_options()[$alang])) {
    $alang = 'no';
}

// Destroy auth session fully (cookie too)
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        [
            'expires'  => time() - 42000,
            'path'     => $p['path'] ?? '/',
            'domain'   => $p['domain'] ?? '',
            'secure'   => (bool) ($p['secure'] ?? false),
            'httponly' => (bool) ($p['httponly'] ?? true),
            'samesite' => $p['samesite'] ?? 'Lax',
        ]
    );
}
session_destroy();

// Fresh session only for language preference on login
admin_session_start();
$_SESSION['admin_lang'] = $alang;

$loginUrl = BASE_PATH . '/admin/login.php?alang=' . rawurlencode($alang);
$delayMs = 2800;

$u = static function (string $jsonEscaped): string {
    $v = json_decode('"' . $jsonEscaped . '"');
    return is_string($v) ? $v : $jsonEscaped;
};

$i18n = [
    'en' => [
        'title'   => 'Signed out',
        'heading' => 'You have been logged out',
        'text'    => 'Session closed securely. Redirecting to the login page…',
        'btn'     => 'Go to login now',
        'done'    => 'Redirecting…',
    ],
    'no' => [
        'title'   => 'Logget ut',
        'heading' => 'Du er logget ut',
        'text'    => 'Økten er avsluttet sikkert. Sender deg til innlogging…',
        'btn'     => 'Gå til innlogging nå',
        'done'    => 'Omdirigerer…',
    ],
    'sv' => [
        'title'   => 'Utloggad',
        'heading' => 'Du har loggats ut',
        'text'    => 'Sessionen stängdes säkert. Skickar dig till inloggningen…',
        'btn'     => 'Gå till inloggning nu',
        'done'    => 'Omdirigerar…',
    ],
    'pl' => [
        'title'   => 'Wylogowano',
        'heading' => 'Zostałeś wylogowany',
        'text'    => 'Sesja zamknięta bezpiecznie. Przekierowanie do logowania…',
        'btn'     => 'Przejdź do logowania',
        'done'    => 'Przekierowanie…',
    ],
    'uk' => [
        'title'   => $u('\\u0412\\u0438\\u0439\\u0448\\u043b\\u0438'),
        'heading' => $u('\\u0412\\u0438 \\u0432\\u0438\\u0439\\u0448\\u043b\\u0438 \\u0437 \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u0438'),
        'text'    => $u('\\u0421\\u0435\\u0441\\u0456\\u044e \\u0431\\u0435\\u0437\\u043f\\u0435\\u0447\\u043d\\u043e \\u0437\\u0430\\u043a\\u0440\\u0438\\u0442\\u043e. \\u041f\\u0435\\u0440\\u0435\\u043d\\u0430\\u043f\\u0440\\u0430\\u0432\\u043b\\u044f\\u0454\\u043c\\u043e \\u043d\\u0430 \\u0441\\u0442\\u043e\\u0440\\u0456\\u043d\\u043a\\u0443 \\u0432\\u0445\\u043e\\u0434\\u0443\\u2026'),
        'btn'     => $u('\\u041f\\u0435\\u0440\\u0435\\u0439\\u0442\\u0438 \\u0434\\u043e \\u0432\\u0445\\u043e\\u0434\\u0443 \\u0437\\u0430\\u0440\\u0430\\u0437'),
        'done'    => $u('\\u041f\\u0435\\u0440\\u0435\\u043d\\u0430\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d\\u043d\\u044f\\u2026'),
    ],
    'ru' => [
        'title'   => $u('\\u0412\\u044b\\u0448\\u043b\\u0438'),
        'heading' => $u('\\u0412\\u044b \\u0432\\u044b\\u0448\\u043b\\u0438 \\u0438\\u0437 \\u0441\\u0438\\u0441\\u0442\\u0435\\u043c\\u044b'),
        'text'    => $u('\\u0421\\u0435\\u0441\\u0441\\u0438\\u044f \\u0431\\u0435\\u0437\\u043e\\u043f\\u0430\\u0441\\u043d\\u043e \\u0437\\u0430\\u043a\\u0440\\u044b\\u0442\\u0430. \\u041f\\u0435\\u0440\\u0435\\u043d\\u0430\\u043f\\u0440\\u0430\\u0432\\u043b\\u044f\\u0435\\u043c \\u043d\\u0430 \\u0441\\u0442\\u0440\\u0430\\u043d\\u0438\\u0446\\u0443 \\u0432\\u0445\\u043e\\u0434\\u0430\\u2026'),
        'btn'     => $u('\\u041f\\u0435\\u0440\\u0435\\u0439\\u0442\\u0438 \\u043a \\u0432\\u0445\\u043e\\u0434\\u0443 \\u0441\\u0435\\u0439\\u0447\\u0430\\u0441'),
        'done'    => $u('\\u041f\\u0435\\u0440\\u0435\\u043d\\u0430\\u043f\\u0440\\u0430\\u0432\\u043b\\u0435\\u043d\\u0438\\u0435\\u2026'),
    ],
    'lt' => [
        'title'   => $u('Atsijungta'),
        'heading' => $u('J\\u016bs atsijung\\u0117te'),
        'text'    => $u('Sesija saugiai u\\u017edaryta. Nukreipiame \\u012f prisijungimo puslap\\u012f\\u2026'),
        'btn'     => $u('Eiti \\u012f prisijungim\\u0105 dabar'),
        'done'    => $u('Nukreipiama\\u2026'),
    ],
];
$L = $i18n[$alang] ?? $i18n['en'];
$siteName = defined('SITE_NAME') ? SITE_NAME : 'Admin';
?><!DOCTYPE html>
<html lang="<?= htmlspecialchars($alang, ENT_QUOTES, 'UTF-8') ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($L['title'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="refresh" content="<?= (int) ceil($delayMs / 1000) ?>;url=<?= htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') ?>">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Sora:wght@700;800&display=swap" rel="stylesheet">
<style>
:root {
  --bg0: #0b1220;
  --bg1: #12203a;
  --card: #ffffff;
  --text: #0f172a;
  --muted: #64748b;
  --accent: #c2410c;
  --accent2: #ea580c;
  --ok: #059669;
  --ring: rgba(194, 65, 12, .28);
}
* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; }
body {
  font-family: Manrope, system-ui, sans-serif;
  color: var(--text);
  background:
    radial-gradient(1200px 600px at 10% -10%, rgba(234, 88, 12, .35), transparent 55%),
    radial-gradient(900px 500px at 100% 0%, rgba(14, 165, 233, .25), transparent 50%),
    linear-gradient(160deg, var(--bg0), var(--bg1) 60%, #0f172a);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  overflow: hidden;
}
.bg-orb {
  position: fixed;
  border-radius: 50%;
  filter: blur(40px);
  opacity: .45;
  pointer-events: none;
  animation: float 8s ease-in-out infinite;
}
.bg-orb.a { width: 220px; height: 220px; background: #ea580c; top: 12%; left: 8%; }
.bg-orb.b { width: 280px; height: 280px; background: #0284c7; bottom: 8%; right: 10%; animation-delay: -2.5s; }
@keyframes float {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-18px) scale(1.05); }
}

.card {
  position: relative;
  width: min(420px, 100%);
  background: var(--card);
  border-radius: 22px;
  padding: 36px 28px 28px;
  text-align: center;
  box-shadow:
    0 25px 60px rgba(2, 6, 23, .45),
    0 0 0 1px rgba(255,255,255,.08);
  animation: cardIn .55s cubic-bezier(.2, .9, .2, 1) both;
}
@keyframes cardIn {
  from { opacity: 0; transform: translateY(22px) scale(.96); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

.icon-wrap {
  width: 88px;
  height: 88px;
  margin: 0 auto 18px;
  border-radius: 50%;
  background: linear-gradient(145deg, #fff7ed, #ffedd5);
  border: 1px solid #fed7aa;
  display: grid;
  place-items: center;
  position: relative;
  animation: pop .7s cubic-bezier(.2, 1.2, .3, 1) .15s both;
}
@keyframes pop {
  from { opacity: 0; transform: scale(.5); }
  to   { opacity: 1; transform: scale(1); }
}
.icon-wrap::after {
  content: '';
  position: absolute;
  inset: -8px;
  border-radius: 50%;
  border: 2px solid transparent;
  border-top-color: var(--accent);
  border-right-color: var(--accent2);
  animation: spin 1.1s linear infinite;
  opacity: .85;
}
@keyframes spin { to { transform: rotate(360deg); } }
.icon-wrap.done::after {
  animation: none;
  border-color: var(--ok);
  opacity: 1;
}
.icon-wrap svg { width: 40px; height: 40px; color: var(--accent); }
.icon-wrap.done svg { color: var(--ok); }

h1 {
  font-family: Sora, Manrope, sans-serif;
  font-size: 1.45rem;
  margin: 0 0 10px;
  letter-spacing: -.02em;
  animation: fadeUp .5s ease .25s both;
}
p {
  margin: 0 0 22px;
  color: var(--muted);
  line-height: 1.55;
  font-size: .98rem;
  animation: fadeUp .5s ease .35s both;
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(10px); }
  to   { opacity: 1; transform: translateY(0); }
}

.bar {
  height: 6px;
  background: #e2e8f0;
  border-radius: 999px;
  overflow: hidden;
  margin: 0 0 18px;
  animation: fadeUp .5s ease .4s both;
}
.bar > i {
  display: block;
  height: 100%;
  width: 0;
  border-radius: inherit;
  background: linear-gradient(90deg, var(--accent), var(--accent2), #f59e0b);
  background-size: 200% 100%;
  animation: fillBar var(--delay, 2.8s) linear forwards, shimmer 1.2s linear infinite;
}
@keyframes fillBar {
  from { width: 0; }
  to   { width: 100%; }
}
@keyframes shimmer {
  0% { background-position: 0% 50%; }
  100% { background-position: 200% 50%; }
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 44px;
  padding: 0 18px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--accent), var(--accent2));
  color: #fff;
  font-weight: 700;
  text-decoration: none;
  box-shadow: 0 10px 24px var(--ring);
  transition: transform .15s ease, filter .15s ease;
  animation: fadeUp .5s ease .5s both;
}
.btn:hover { filter: brightness(1.05); transform: translateY(-1px); }
.btn:active { transform: translateY(0); }

.meta {
  margin-top: 16px;
  font-size: .8rem;
  color: #94a3b8;
  animation: fadeUp .5s ease .55s both;
}
.brand {
  font-weight: 700;
  color: #64748b;
}

/* confetti dots */
.confetti {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  border-radius: inherit;
}
.confetti span {
  position: absolute;
  top: -10px;
  width: 8px;
  height: 8px;
  border-radius: 2px;
  opacity: 0;
  animation: confettiFall 1.6s ease-out forwards;
}
@keyframes confettiFall {
  0%   { opacity: 1; transform: translateY(0) rotate(0deg); }
  100% { opacity: 0; transform: translateY(280px) rotate(420deg); }
}

@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: .01ms !important;
    animation-iteration-count: 1 !important;
    transition: none !important;
  }
  .bar > i { width: 100%; }
}
</style>
</head>
<body>
  <div class="bg-orb a" aria-hidden="true"></div>
  <div class="bg-orb b" aria-hidden="true"></div>

  <main class="card" role="status" aria-live="polite">
    <div class="confetti" id="confetti" aria-hidden="true"></div>
    <div class="icon-wrap" id="iconWrap">
      <!-- door / logout icon -->
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
      </svg>
    </div>
    <h1 id="heading"><?= htmlspecialchars($L['heading'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p id="text"><?= htmlspecialchars($L['text'], ENT_QUOTES, 'UTF-8') ?></p>
    <div class="bar" style="--delay: <?= (int) $delayMs ?>ms"><i></i></div>
    <a class="btn" id="loginBtn" href="<?= htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') ?>">
      <?= htmlspecialchars($L['btn'], ENT_QUOTES, 'UTF-8') ?>
    </a>
    <div class="meta"><span class="brand"><?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?></span> · Admin</div>
  </main>

<script>
(function () {
  var loginUrl = <?= json_encode($loginUrl, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  var delay = <?= (int) $delayMs ?>;
  var doneLabel = <?= json_encode($L['done'], JSON_UNESCAPED_UNICODE) ?>;
  var icon = document.getElementById('iconWrap');
  var heading = document.getElementById('heading');
  var confetti = document.getElementById('confetti');
  var redirected = false;

  function go() {
    if (redirected) return;
    redirected = true;
    if (icon) icon.classList.add('done');
    if (heading) heading.textContent = doneLabel;
    window.location.replace(loginUrl);
  }

  // soft confetti burst
  if (confetti) {
    var colors = ['#c2410c', '#ea580c', '#f59e0b', '#0ea5e9', '#10b981', '#8b5cf6'];
    for (var i = 0; i < 18; i++) {
      var s = document.createElement('span');
      s.style.left = (8 + Math.random() * 84) + '%';
      s.style.background = colors[i % colors.length];
      s.style.animationDelay = (0.15 + Math.random() * 0.55) + 's';
      s.style.width = (6 + Math.random() * 6) + 'px';
      s.style.height = s.style.width;
      confetti.appendChild(s);
    }
  }

  setTimeout(function () {
    if (icon) icon.classList.add('done');
  }, Math.max(400, delay - 500));

  setTimeout(go, delay);

  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'visible' && !redirected) {
      // if user came back late, go immediately
    }
  });
})();
</script>
</body>
</html>
