document.addEventListener('DOMContentLoaded', function () {
  var toggle = document.querySelector('[data-sidebar-toggle]');
  var sidebar = document.querySelector('.kk-sidebar');
  var overlay = document.querySelector('.kk-overlay');

  function closeSidebar() {
    sidebar && sidebar.classList.remove('open');
    overlay && overlay.classList.remove('open');
  }

  if (toggle && sidebar && overlay) {
    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('open');
    });
    overlay.addEventListener('click', closeSidebar);
  }

  // Confirmation for any form marked data-confirm="message"
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!window.confirm(form.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });

  // Auto-dismiss alerts after 4s
  document.querySelectorAll('[data-alert]').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .3s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 300);
    }, 4000);
  });
});
