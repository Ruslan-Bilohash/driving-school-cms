/*
 * Driving School CMS — marketing.js
 * - Captures UTM / referrer / landing page / click IDs and attaches them to lead forms (hidden mk_* fields)
 * - Loads GA4, Google Tag Manager and Meta Pixel (optionally only after cookie consent)
 * - Sends conversion events on successful requests (event "ds:lead" from main.js)
 * - Tracks clicks on phone / email / WhatsApp links
 * - Promo banner show / dismiss
 */
(function(){
  "use strict";

  var cfg = window.DS_MARKETING || {};
  var STORE_KEY = 'dsAttribution';
  var CONSENT_KEY = 'cookieConsent';
  var UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
  var CLICK_KEYS = ['gclid', 'fbclid', 'msclkid', 'ttclid'];
  var attributionDays = Math.max(1, Math.min(365, parseInt(cfg.attributionDays, 10) || 90));

  /* ---------- Safe storage helpers ---------- */
  function storageGet(store, key){
    try { return window[store].getItem(key); } catch (e) { return null; }
  }
  function storageSet(store, key, value){
    try { window[store].setItem(key, value); } catch (e) { /* private mode / blocked */ }
  }
  function storageRemove(store, key){
    try { window[store].removeItem(key); } catch (e) { /* ignore */ }
  }

  function hasConsent(){
    return storageGet('localStorage', CONSENT_KEY) === '1';
  }

  /* ---------- Attribution ---------- */
  function readStored(){
    var raw = storageGet('localStorage', STORE_KEY) || storageGet('sessionStorage', STORE_KEY);
    if (!raw) return null;
    try {
      var data = JSON.parse(raw);
      if (!data || typeof data !== 'object') return null;
      if (data.expires && Date.now() > data.expires) {
        storageRemove('localStorage', STORE_KEY);
        storageRemove('sessionStorage', STORE_KEY);
        return null;
      }
      return data;
    } catch (e) {
      return null;
    }
  }

  function persist(data){
    var raw = JSON.stringify(data);
    // Without consent keep attribution only for the current browser session (GDPR-friendly)
    if (!cfg.requireConsent || hasConsent()) {
      storageSet('localStorage', STORE_KEY, raw);
      storageRemove('sessionStorage', STORE_KEY);
    } else {
      storageSet('sessionStorage', STORE_KEY, raw);
    }
  }

  function externalReferrer(){
    var ref = document.referrer || '';
    if (!ref) return '';
    try {
      var u = new URL(ref);
      if (u.hostname === window.location.hostname) return '';
      return u.hostname;
    } catch (e) {
      return '';
    }
  }

  function captureAttribution(){
    var params;
    try { params = new URLSearchParams(window.location.search); } catch (e) { params = null; }
    var current = { utm: {}, clickType: '', clickId: '' };
    var hasCampaign = false;

    if (params) {
      UTM_KEYS.forEach(function(k){
        var v = params.get(k);
        if (v) { current.utm[k] = v.slice(0, 100); hasCampaign = true; }
      });
      CLICK_KEYS.forEach(function(k){
        var v = params.get(k);
        if (v && !current.clickType) { current.clickType = k; current.clickId = v.slice(0, 200); hasCampaign = true; }
      });
    }

    var ref = externalReferrer();
    var stored = readStored();
    var now = Date.now();

    // New campaign click or new external referrer overrides the stored touch (last non-direct click)
    if (hasCampaign || (ref && (!stored || !stored.hasCampaign))) {
      stored = {
        utm: current.utm,
        clickType: current.clickType,
        clickId: current.clickId,
        referrer: ref,
        landing: window.location.pathname,
        firstSeen: stored && stored.firstSeen ? stored.firstSeen : Math.floor(now / 1000),
        hasCampaign: hasCampaign,
        expires: now + attributionDays * 86400000
      };
      persist(stored);
    } else if (!stored) {
      stored = {
        utm: {}, clickType: '', clickId: '', referrer: '',
        landing: window.location.pathname,
        firstSeen: Math.floor(now / 1000),
        hasCampaign: false,
        expires: now + attributionDays * 86400000
      };
      persist(stored);
    } else if (cfg.requireConsent && hasConsent() && !storageGet('localStorage', STORE_KEY)) {
      // Consent given later — move session data to persistent storage
      persist(stored);
    }
    return stored;
  }

  var attribution = captureAttribution();

  function setHidden(form, name, value){
    var input = form.querySelector('input[type="hidden"][name="' + name + '"]');
    if (!input) {
      input = document.createElement('input');
      input.type = 'hidden';
      input.name = name;
      form.appendChild(input);
    }
    input.value = value || '';
  }

  function decorateForm(form){
    if (!form || !attribution) return;
    UTM_KEYS.forEach(function(k){ setHidden(form, 'mk_' + k, (attribution.utm || {})[k] || ''); });
    setHidden(form, 'mk_referrer', attribution.referrer || '');
    setHidden(form, 'mk_landing', attribution.landing || '');
    setHidden(form, 'mk_click_type', attribution.clickType || '');
    setHidden(form, 'mk_click_id', attribution.clickId || '');
    setHidden(form, 'mk_first_seen', attribution.firstSeen ? String(attribution.firstSeen) : '');
  }

  function leadForms(){
    return document.querySelectorAll('#contactForm, #callbackForm, form[data-ds-lead]');
  }

  function decorateAll(){
    Array.prototype.forEach.call(leadForms(), decorateForm);
  }

  // Refresh hidden fields right before submit (capture phase runs before main.js handlers build FormData)
  document.addEventListener('submit', function(e){
    var f = e.target;
    if (f && f.matches && f.matches('#contactForm, #callbackForm, form[data-ds-lead]')) {
      decorateForm(f);
    }
  }, true);

  /* ---------- Trackers ---------- */
  var trackersLoaded = false;
  window.dataLayer = window.dataLayer || [];

  function addScript(src){
    var s = document.createElement('script');
    s.async = true;
    s.src = src;
    document.head.appendChild(s);
  }

  function loadTrackers(){
    if (trackersLoaded) return;
    trackersLoaded = true;

    if (cfg.gtm && /^GTM-[A-Z0-9]{4,12}$/.test(cfg.gtm)) {
      window.dataLayer.push({ 'gtm.start': Date.now(), event: 'gtm.js' });
      addScript('https://www.googletagmanager.com/gtm.js?id=' + encodeURIComponent(cfg.gtm));
    }

    if (cfg.ga4 && /^G-[A-Z0-9]{4,15}$/.test(cfg.ga4)) {
      window.gtag = window.gtag || function(){ window.dataLayer.push(arguments); };
      window.gtag('js', new Date());
      window.gtag('config', cfg.ga4, { anonymize_ip: true });
      addScript('https://www.googletagmanager.com/gtag/js?id=' + encodeURIComponent(cfg.ga4));
    }

    if (cfg.pixel && /^\d{10,20}$/.test(cfg.pixel) && !window.fbq) {
      var n = window.fbq = function(){
        if (n.callMethod) { n.callMethod.apply(n, arguments); } else { n.queue.push(arguments); }
      };
      if (!window._fbq) window._fbq = n;
      n.push = n; n.loaded = true; n.version = '2.0'; n.queue = [];
      addScript('https://connect.facebook.net/en_US/fbevents.js');
      window.fbq('init', cfg.pixel);
      window.fbq('track', 'PageView');
    }
  }

  function anyTracker(){
    return !!(cfg.ga4 || cfg.gtm || cfg.pixel);
  }

  function track(eventName, params, fbEvent){
    params = params || {};
    var payload = { event: 'ds_' + eventName };
    Object.keys(params).forEach(function(k){ payload[k] = params[k]; });
    window.dataLayer.push(payload);
    if (typeof window.gtag === 'function' && cfg.ga4) {
      window.gtag('event', eventName, params);
    }
    if (typeof window.fbq === 'function' && fbEvent) {
      window.fbq('track', fbEvent, params);
    }
  }

  if (anyTracker()) {
    if (!cfg.requireConsent || hasConsent()) {
      loadTrackers();
    } else {
      document.addEventListener('click', function(e){
        var btn = e.target && e.target.closest ? e.target.closest('#cookieAccept') : null;
        if (!btn) return;
        // main.js stores consent in the same click — load right after it
        setTimeout(function(){
          if (hasConsent()) {
            loadTrackers();
            if (attribution) persist(attribution);
          }
        }, 0);
      });
    }
  } else {
    // No trackers: still move session attribution to localStorage after consent
    document.addEventListener('click', function(e){
      var btn = e.target && e.target.closest ? e.target.closest('#cookieAccept') : null;
      if (btn && attribution) setTimeout(function(){ persist(attribution); }, 0);
    });
  }

  /* ---------- Conversion events ---------- */
  if (cfg.trackLeads) {
    document.addEventListener('ds:lead', function(e){
      var d = (e && e.detail) || {};
      var utm = (attribution && attribution.utm) || {};
      track('generate_lead', {
        form: d.form || 'contact',
        category: d.category || '',
        lang: cfg.lang || '',
        campaign: utm.utm_campaign || '',
        source: utm.utm_source || (attribution && attribution.referrer) || '(direct)'
      }, 'Lead');
    });
  }

  if (cfg.trackClicks) {
    document.addEventListener('click', function(e){
      var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
      if (!a) return;
      var href = a.getAttribute('href') || '';
      var method = '';
      if (href.indexOf('tel:') === 0) method = 'phone';
      else if (href.indexOf('mailto:') === 0) method = 'email';
      else if (/^https?:\/\/(wa\.me|api\.whatsapp\.com)\//i.test(href)) method = 'whatsapp';
      else if (/^https?:\/\/(t\.me|telegram\.me)\//i.test(href)) method = 'telegram';
      if (method) {
        track('contact_click', { method: method, page: window.location.pathname }, 'Contact');
      }
    });
  }

  /* ---------- Promo banner ---------- */
  function initBanner(){
    var banner = document.getElementById('dsPromo');
    if (!banner) return;
    var key = 'dsPromoClosed:' + (cfg.bannerVersion || '1');
    if (storageGet('localStorage', key) || storageGet('sessionStorage', key)) {
      banner.parentNode.removeChild(banner);
      return;
    }
    // Move banner above the sticky header
    document.body.insertBefore(banner, document.body.firstChild);
    banner.hidden = false;

    var close = document.getElementById('dsPromoClose');
    if (close) {
      close.addEventListener('click', function(){
        banner.parentNode.removeChild(banner);
        storageSet(hasConsent() ? 'localStorage' : 'sessionStorage', key, '1');
        track('promo_close', { version: cfg.bannerVersion || '' });
      });
    }
    var cta = document.getElementById('dsPromoCta');
    if (cta) {
      var href = cta.getAttribute('href') || '';
      var fallback = cta.getAttribute('data-fallback');
      if (fallback && href.charAt(0) === '#' && !document.getElementById(href.slice(1))) {
        cta.setAttribute('href', fallback);
      }
      cta.addEventListener('click', function(){
        track('promo_click', { version: cfg.bannerVersion || '', link: cta.getAttribute('href') || '' }, 'ViewContent');
      });
    }
  }

  function onReady(){
    decorateAll();
    initBanner();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', onReady);
  } else {
    onReady();
  }
})();
