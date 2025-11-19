// Small JS: image preview and basic client-side helpers
document.addEventListener('DOMContentLoaded', function(){
  // image preview for inputs with data-preview-target
  document.querySelectorAll('input[type=file][data-preview-target]').forEach(function(input){
    input.addEventListener('change', function(e){
      const file = e.target.files[0];
      const target = document.querySelector(e.target.getAttribute('data-preview-target'));
      if (!target) return;
      if (!file) { target.style.display='none'; return; }
      if (!file.type.startsWith('image/')) { target.style.display='none'; return; }
      const reader = new FileReader();
      reader.onload = function(ev){ target.src = ev.target.result; target.style.display='block'; }
      reader.readAsDataURL(file);
    });
  });
});
