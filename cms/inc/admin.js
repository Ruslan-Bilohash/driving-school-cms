(function(){
  "use strict";

  function initUploader(el){
    var csrf = el.getAttribute('data-csrf');
    var drop = el.querySelector('.image-drop');
    var input = el.querySelector('.image-file-input');
    var hidden = el.querySelector('.image-value');
    var preview = el.querySelector('.image-preview');
    var hint = el.querySelector('.image-drop-hint');
    var form = el.closest('form');
    var submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    function showPreview(url){
      if (!url) {
        preview.style.display = 'none';
        hint.style.display = 'block';
        return;
      }
      preview.src = url;
      preview.style.display = 'block';
      hint.style.display = 'none';
    }

    function setUploading(isUploading){
      el.dataset.uploading = isUploading ? '1' : '0';
      if (submitBtn) submitBtn.disabled = isUploading;
    }

    function upload(file){
      if (!file || file.type.indexOf('image/') !== 0) return;
      setUploading(true);
      hint.textContent = 'Ð—Ð°Ð²Ð°Ð½Ñ‚Ð°Ð¶ÐµÐ½Ð½Ñ...';
      hint.style.display = 'block';
      preview.style.display = 'none';
      var fd = new FormData();
      fd.append('csrf', csrf);
      fd.append('image', file);
      fetch(BASE_PATH + '/admin/upload-image.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(res){
          if (res && res.success) {
            hidden.value = res.url;
            showPreview(res.url);
            document.dispatchEvent(new Event('imageUploaded'));
          } else {
            hint.textContent = (res && res.message) || 'ÐŸÐ¾Ð¼Ð¸Ð»ÐºÐ° Ð·Ð°Ð²Ð°Ð½Ñ‚Ð°Ð¶ÐµÐ½Ð½Ñ';
            hint.style.display = 'block';
          }
        })
        .catch(function(){
          hint.textContent = 'ÐŸÐ¾Ð¼Ð¸Ð»ÐºÐ° Ð·Ð°Ð²Ð°Ð½Ñ‚Ð°Ð¶ÐµÐ½Ð½Ñ';
          hint.style.display = 'block';
        })
        .finally(function(){ setUploading(false); });
    }

    if (form) {
      form.addEventListener('submit', function(e){
        if (el.dataset.uploading === '1') {
          e.preventDefault();
          alert('Ð—Ð°Ñ‡ÐµÐºÐ°Ð¹Ñ‚Ðµ, Ð¿Ð¾ÐºÐ¸ Ð·Ð°Ð²Ð°Ð½Ñ‚Ð°Ð¶Ð¸Ñ‚ÑŒÑÑ Ð·Ð¾Ð±Ñ€Ð°Ð¶ÐµÐ½Ð½Ñ...');
        }
      });
    }

    drop.addEventListener('click', function(){ input.click(); });
    drop.addEventListener('keydown', function(e){ if (e.key === 'Enter' || e.key === ' ') input.click(); });
    input.addEventListener('change', function(){ if (input.files[0]) upload(input.files[0]); });

    ['dragenter', 'dragover'].forEach(function(evt){
      drop.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); drop.classList.add('drag-over'); });
    });
    ['dragleave', 'drop'].forEach(function(evt){
      drop.addEventListener(evt, function(e){ e.preventDefault(); e.stopPropagation(); drop.classList.remove('drag-over'); });
    });
    drop.addEventListener('drop', function(e){
      var file = e.dataTransfer.files && e.dataTransfer.files[0];
      if (file) upload(file);
    });

    if (hidden.value) showPreview(hidden.value);
  }

  document.querySelectorAll('.image-uploader').forEach(initUploader);

  function initRichEditor(el){
    var toolbar = el.querySelector('.rte-toolbar');
    var editor = el.querySelector('.rte-editor');
    var source = el.querySelector('.rte-source');

    function sync(){ source.value = editor.innerHTML; }

    toolbar.addEventListener('click', function(e){
      var btn = e.target.closest('.rte-btn');
      if (!btn) return;
      e.preventDefault();
      editor.focus();
      var cmd = btn.getAttribute('data-cmd');
      var value = btn.getAttribute('data-value') || undefined;
      if (cmd === 'createLink') {
        var url = window.prompt('URL:', 'https://');
        if (!url) return;
        document.execCommand('createLink', false, url);
      } else {
        document.execCommand(cmd, false, value);
      }
      sync();
    });

    editor.addEventListener('input', sync);
    editor.addEventListener('blur', sync);

    var form = el.closest('form');
    if (form) form.addEventListener('submit', sync);
  }

  document.querySelectorAll('.rte').forEach(initRichEditor);

  /* SEO checklist */
  var seoBox = document.getElementById('seoChecklist');
  if (seoBox) {
    var titleEl = document.getElementById('newsTitleField');
    var metaEl = document.getElementById('newsMetaField');
    var excerptEl = document.getElementById('newsExcerptField');
    var rteEditorEl = document.querySelector('.rte-editor');
    var imageValueEl = document.querySelector('.image-uploader .image-value');

    function setCheck(name, level, text){
      var li = seoBox.querySelector('li[data-check="' + name + '"]');
      if (!li) return;
      li.querySelector('.seo-dot').className = 'seo-dot ' + level;
      li.querySelector('.seo-text').textContent = text;
    }

    function scoreLength(len, min, idealMin, idealMax, max){
      if (len === 0) return 'red';
      if (len >= idealMin && len <= idealMax) return 'green';
      if (len >= min && len <= max) return 'orange';
      return 'red';
    }

    function updateSeoChecklist(){
      var titleLen = (titleEl.value || '').length;
      var tLevel = scoreLength(titleLen, 15, 30, 60, 70);
      setCheck('title', tLevel, 'Title: ' + titleLen + ' chars (ideal 30-60)');

      var metaLen = (metaEl.value || '').length;
      var mLevel = scoreLength(metaLen, 60, 120, 160, 180);
      setCheck('meta', mLevel, 'Meta description: ' + metaLen + ' chars (ideal 120-160)');

      var excerptLen = (excerptEl.value || '').trim().length;
      setCheck('excerpt', excerptLen > 0 ? 'green' : 'red', excerptLen > 0 ? 'Excerpt filled in' : 'Excerpt is empty');

      var html = rteEditorEl ? rteEditorEl.innerHTML : '';
      var hasHeading = /<h[23][ >]/i.test(html);
      setCheck('heading', hasHeading ? 'green' : 'orange', hasHeading ? 'Content has subheadings' : 'No H2/H3 subheadings yet');

      var textOnly = rteEditorEl ? rteEditorEl.textContent.trim() : '';
      var wordCount = textOnly ? textOnly.split(/\s+/).length : 0;
      var lenLevel = wordCount >= 200 ? 'green' : (wordCount >= 80 ? 'orange' : 'red');
      setCheck('length', lenLevel, 'Content length: ' + wordCount + ' words (aim for 200+)');

      var hasImage = imageValueEl && imageValueEl.value;
      setCheck('image', hasImage ? 'green' : 'orange', hasImage ? 'Cover image set' : 'No cover image yet');
    }

    [titleEl, metaEl, excerptEl].forEach(function(el){
      if (el) el.addEventListener('input', updateSeoChecklist);
    });
    if (rteEditorEl) rteEditorEl.addEventListener('input', updateSeoChecklist);
    document.addEventListener('imageUploaded', updateSeoChecklist);

    updateSeoChecklist();
  }

  /* AI news assistant */
  var aiBtn = document.getElementById('aiGenerateBtn');
  if (aiBtn) {
    var aiTopic = document.getElementById('aiTopicInput');
    var aiStatus = document.getElementById('aiAssistStatus');
    var newsForm = document.getElementById('newsForm');
    var csrfInput = newsForm ? newsForm.querySelector('input[name="csrf"]') : null;
    var langInput = document.getElementById('newsLangField');

    aiBtn.addEventListener('click', function(){
      var topic = (aiTopic.value || '').trim();
      if (!topic) {
        aiStatus.textContent = 'Ð’Ð²ÐµÐ´Ñ–Ñ‚ÑŒ Ñ‚ÐµÐ¼Ñƒ ÑÑ‚Ð°Ñ‚Ñ‚Ñ–.';
        aiStatus.className = 'ai-assist-status err';
        return;
      }
      aiBtn.disabled = true;
      aiStatus.textContent = 'Ð“ÐµÐ½ÐµÑ€ÑƒÑ”Ñ‚ÑŒÑÑ...';
      aiStatus.className = 'ai-assist-status';

      var fd = new FormData();
      fd.append('csrf', csrfInput ? csrfInput.value : '');
      fd.append('topic', topic);
      fd.append('lang', langInput ? langInput.value : 'lt');

      fetch(BASE_PATH + '/admin/ai-generate.php', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(res){
          if (res && res.success) {
            document.getElementById('newsTitleField').value = res.title || '';
            document.getElementById('newsExcerptField').value = res.excerpt || '';
            document.getElementById('newsMetaField').value = res.meta_description || '';
            var rteEditor = document.querySelector('.rte-editor');
            var rteSource = document.querySelector('.rte-source');
            if (rteEditor && rteSource) {
              rteEditor.innerHTML = res.content || '';
              rteSource.value = res.content || '';
            }
            aiStatus.textContent = 'Ð“Ð¾Ñ‚Ð¾Ð²Ð¾! ÐŸÐµÑ€ÐµÐ²Ñ–Ñ€Ñ‚Ðµ Ñ‚Ð° Ð²Ñ–Ð´Ñ€ÐµÐ´Ð°Ð³ÑƒÐ¹Ñ‚Ðµ Ñ‚ÐµÐºÑÑ‚ Ð¿ÐµÑ€ÐµÐ´ Ð¿ÑƒÐ±Ð»Ñ–ÐºÐ°Ñ†Ñ–Ñ”ÑŽ.';
            aiStatus.className = 'ai-assist-status ok';
          } else {
            aiStatus.textContent = (res && res.message) || 'ÐŸÐ¾Ð¼Ð¸Ð»ÐºÐ° Ð³ÐµÐ½ÐµÑ€Ð°Ñ†Ñ–Ñ—.';
            aiStatus.className = 'ai-assist-status err';
          }
        })
        .catch(function(){
          aiStatus.textContent = 'ÐŸÐ¾Ð¼Ð¸Ð»ÐºÐ° Ð·\'Ñ”Ð´Ð½Ð°Ð½Ð½Ñ.';
          aiStatus.className = 'ai-assist-status err';
        })
        .finally(function(){ aiBtn.disabled = false; });
    });
  }
})();
