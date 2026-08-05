(function(){
  "use strict";

  /* Sticky header shrink */
  var header = document.getElementById('siteHeader');
  var onScroll = function(){
    if (window.scrollY > 12) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
    toggleBackToTop();
  };
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* Language dropdown */
  var langDropdown = document.getElementById('langDropdown');
  var langTrigger = document.getElementById('langTrigger');
  if (langDropdown && langTrigger) {
    langTrigger.addEventListener('click', function(e){
      e.stopPropagation();
      var isOpen = langDropdown.classList.toggle('open');
      langTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
    document.addEventListener('click', function(e){
      if (!langDropdown.contains(e.target)) {
        langDropdown.classList.remove('open');
        langTrigger.setAttribute('aria-expanded', 'false');
      }
    });
    document.addEventListener('keydown', function(e){
      if (e.key === 'Escape') {
        langDropdown.classList.remove('open');
        langTrigger.setAttribute('aria-expanded', 'false');
      }
    });
  }

  /* Mobile nav toggle */
  var navToggle = document.getElementById('navToggle');
  var mainNav = document.getElementById('mainNav');
  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function(){
      var open = mainNav.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.classList.toggle('nav-open', open);
    });
    mainNav.querySelectorAll('a').forEach(function(a){
      a.addEventListener('click', function(){
        mainNav.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-open');
      });
    });
  }

  /* Scroll reveal + stat counters */
  var revealEls = document.querySelectorAll('.reveal');
  var counted = new WeakSet();

  function animateCounter(el){
    if (counted.has(el)) return;
    counted.add(el);
    var raw = el.getAttribute('data-count') || el.textContent;
    var match = raw.match(/^(\d+)(.*)$/);
    if (!match) return;
    var target = parseInt(match[1], 10);
    var suffix = match[2] || '';
    var start = 0;
    var duration = 1100;
    var startTime = null;
    function step(ts){
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      var value = Math.round(start + (target - start) * eased);
      el.textContent = value + suffix;
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(entry){
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          entry.target.querySelectorAll('.stat-num[data-count]').forEach(animateCounter);
          if (entry.target.classList.contains('stat-card')) {
            var num = entry.target.querySelector('.stat-num[data-count]');
            if (num) animateCounter(num);
          }
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(function(el){ io.observe(el); });
  } else {
    revealEls.forEach(function(el){ el.classList.add('in-view'); });
  }

  /* FAQ accordion */
  document.querySelectorAll('.faq-item').forEach(function(item){
    var btn = item.querySelector('.faq-q');
    var panel = item.querySelector('.faq-a');
    btn.addEventListener('click', function(){
      var isOpen = item.getAttribute('data-open') === 'true';
      document.querySelectorAll('.faq-item[data-open="true"]').forEach(function(other){
        if (other !== item) {
          other.setAttribute('data-open', 'false');
          other.querySelector('.faq-q').setAttribute('aria-expanded', 'false');
          other.querySelector('.faq-a').style.maxHeight = null;
        }
      });
      item.setAttribute('data-open', isOpen ? 'false' : 'true');
      btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
      panel.style.maxHeight = isOpen ? null : (panel.scrollHeight + 'px');
    });
  });

  /* Back to top */
  var backToTop = document.getElementById('backToTop');
  function toggleBackToTop(){
    if (!backToTop) return;
    if (window.scrollY > 480) backToTop.classList.add('visible');
    else backToTop.classList.remove('visible');
  }
  function getRecaptchaResponse(formEl){
    if (!window.RECAPTCHA_SITE_KEY || !window.grecaptcha || !window.__recaptchaWidgets) return { id: null, token: '', widgetLoaded: false };
    var container = formEl.querySelector('.g-recaptcha');
    if (!container || !window.__recaptchaWidgets.has(container)) return { id: null, token: '', widgetLoaded: false };
    var id = window.__recaptchaWidgets.get(container);
    try {
      return { id: id, token: grecaptcha.getResponse(id) || '', widgetLoaded: true };
    } catch (e) {
      return { id: id, token: '', widgetLoaded: false };
    }
  }

  if (backToTop) {
    backToTop.addEventListener('click', function(){
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* Contact form submit */
  var form = document.getElementById('contactForm');
  if (form) {
    var status = form.querySelector('.form-status');
    var submitBtn = form.querySelector('button[type="submit"]');
    var btnLabelHtml = submitBtn ? submitBtn.innerHTML : '';
    var i18n = window.SITE_I18N || {};

    form.addEventListener('submit', function(e){
      e.preventDefault();
      status.className = 'form-status';
      status.textContent = '';

      if (!form.checkValidity()) {
        status.textContent = i18n.errorFields || 'Please fill required fields.';
        status.className = 'form-status err';
        return;
      }

      var rc = getRecaptchaResponse(form);
      if (rc.widgetLoaded && !rc.token) {
        status.textContent = i18n.recaptchaRequired || 'Please confirm you are not a robot.';
        status.className = 'form-status err';
        return;
      }

      submitBtn.disabled = true;
      submitBtn.innerHTML = i18n.sending || 'Sending...';

      var data = new FormData(form);
      data.append('recaptcha_token', rc.token);

      fetch('/api/contact.php', { method: 'POST', body: data })
        .then(function(r){ return r.json(); })
        .then(function(res){
          if (res && res.success) {
            status.textContent = i18n.success || 'Thank you!';
            status.className = 'form-status ok';
            form.reset();
            if (window.grecaptcha && rc.widgetLoaded) grecaptcha.reset(rc.id);
          } else {
            status.textContent = (res && res.message) || i18n.error || 'Something went wrong.';
            status.className = 'form-status err';
          }
        })
        .catch(function(){
          status.textContent = i18n.error || 'Something went wrong.';
          status.className = 'form-status err';
        })
        .finally(function(){
          submitBtn.disabled = false;
          submitBtn.innerHTML = btnLabelHtml;
        });
    });
  }

  /* Cookie banner */
  var cookieBanner = document.getElementById('cookieBanner');
  var cookieAccept = document.getElementById('cookieAccept');
  if (cookieBanner) {
    if (!localStorage.getItem('cookieConsent')) {
      setTimeout(function(){ cookieBanner.classList.add('show'); }, 800);
    }
    if (cookieAccept) {
      cookieAccept.addEventListener('click', function(){
        localStorage.setItem('cookieConsent', '1');
        cookieBanner.classList.remove('show');
      });
    }
  }

  /* Callback popup */
  var callbackOverlay = document.getElementById('callbackOverlay');
  var callbackModal = document.getElementById('callbackModal');
  var callbackClose = document.getElementById('callbackClose');
  var callbackForm = document.getElementById('callbackForm');

  function openCallback(){
    if (!callbackOverlay || !callbackModal) return;
    callbackOverlay.classList.add('open');
    callbackModal.classList.add('open');
  }
  function closeCallback(){
    if (!callbackOverlay || !callbackModal) return;
    callbackOverlay.classList.remove('open');
    callbackModal.classList.remove('open');
  }

  if (callbackModal && !sessionStorage.getItem('callbackShown') && !localStorage.getItem('callbackDismissedAt')) {
    setTimeout(function(){
      openCallback();
      sessionStorage.setItem('callbackShown', '1');
    }, 20000);
  }

  if (callbackClose) callbackClose.addEventListener('click', function(){
    closeCallback();
    localStorage.setItem('callbackDismissedAt', String(Date.now()));
  });
  if (callbackOverlay) callbackOverlay.addEventListener('click', closeCallback);

  if (callbackForm) {
    var cbStatus = callbackForm.querySelector('.form-status');
    var cbBtn = callbackForm.querySelector('button[type="submit"]');
    var cbI18n = window.CALLBACK_I18N || {};
    callbackForm.addEventListener('submit', function(e){
      e.preventDefault();
      if (!callbackForm.checkValidity()) {
        cbStatus.textContent = cbI18n.errorFields || 'Please fill required fields.';
        cbStatus.className = 'form-status err';
        return;
      }
      var cbRc = getRecaptchaResponse(callbackForm);
      if (cbRc.widgetLoaded && !cbRc.token) {
        cbStatus.textContent = cbI18n.recaptchaRequired || 'Please confirm you are not a robot.';
        cbStatus.className = 'form-status err';
        return;
      }

      cbBtn.disabled = true;
      var cbData = new FormData(callbackForm);
      cbData.append('recaptcha_token', cbRc.token);
      fetch('/api/contact.php', { method: 'POST', body: cbData })
        .then(function(r){ return r.json(); })
        .then(function(res){
          if (res && res.success) {
            cbStatus.textContent = cbI18n.success || 'Thank you!';
            cbStatus.className = 'form-status ok';
            callbackForm.reset();
            if (window.grecaptcha && cbRc.widgetLoaded) grecaptcha.reset(cbRc.id);
            localStorage.setItem('callbackDismissedAt', String(Date.now()));
            setTimeout(closeCallback, 1600);
          } else {
            cbStatus.textContent = (res && res.message) || cbI18n.error || 'Error';
            cbStatus.className = 'form-status err';
          }
        })
        .catch(function(){
          cbStatus.textContent = cbI18n.error || 'Error';
          cbStatus.className = 'form-status err';
        })
        .finally(function(){ cbBtn.disabled = false; });
    });
  }

  /* Chat widget */
  var chatBubble = document.getElementById('chatBubble');
  var chatPanel = document.getElementById('chatPanel');
  var chatClose = document.getElementById('chatPanelClose');
  var chatForm = document.getElementById('chatForm');
  var chatMessages = document.getElementById('chatMessages');
  var chatInput = document.getElementById('chatInput');
  var chatHistory = [];

  function chatToggle(){ if (chatPanel) chatPanel.classList.toggle('open'); }
  if (chatBubble) chatBubble.addEventListener('click', chatToggle);
  if (chatClose) chatClose.addEventListener('click', chatToggle);

  function addMsg(text, who){
    var div = document.createElement('div');
    div.className = 'chat-msg chat-msg-' + who;
    div.textContent = text;
    chatMessages.appendChild(div);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return div;
  }

  if (chatForm) {
    chatForm.addEventListener('submit', function(e){
      e.preventDefault();
      var msg = chatInput.value.trim();
      if (!msg) return;
      var i18n = window.CHAT_I18N || {};
      addMsg(msg, 'user');
      chatHistory.push({ role: 'user', content: msg });
      chatInput.value = '';
      chatInput.disabled = true;
      var thinking = addMsg(i18n.thinking || '...', 'bot');

      var fd = new FormData();
      fd.append('message', msg);
      fd.append('history', JSON.stringify(chatHistory.slice(0, -1)));

      fetch('/api/chat.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(res){
          thinking.remove();
          if (res && res.success) {
            addMsg(res.reply, 'bot');
            chatHistory.push({ role: 'assistant', content: res.reply });
          } else {
            addMsg(i18n.error || 'Error', 'bot');
          }
        })
        .catch(function(){
          thinking.remove();
          addMsg(i18n.error || 'Error', 'bot');
        })
        .finally(function(){
          chatInput.disabled = false;
          chatInput.focus();
        });
    });
  }
})();
