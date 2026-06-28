/* ============================================
   GBÔ AFRICA GROUP — DASHBOARD SYSTEM JS
   Admin · Coach · Client
   ============================================ */

// Toggle Sidebar
toggleSidebar = function() {
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  sidebar.classList.toggle('collapsed');
  localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
};

// Mobile menu
toggleMobileMenu = function() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('active');
};

// Restore sidebar state
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  if (sidebar && localStorage.getItem('sidebarCollapsed') === 'true') {
    sidebar.classList.add('collapsed');
  }
});

// Toast notification
showToast = function(message, type = 'success') {
  const container = document.getElementById('toastContainer') || createToastContainer();
  const toast = document.createElement('div');
  toast.className = 'toast';
  toast.innerHTML = `
    <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
    <span>${message}</span>
  `;
  container.appendChild(toast);

  requestAnimationFrame(() => toast.classList.add('show'));

  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3000);
};

function createToastContainer() {
  const container = document.createElement('div');
  container.id = 'toastContainer';
  container.className = 'toast-container';
  document.body.appendChild(container);
  return container;
}

// Modal
openModal = function(modalId) {
  document.getElementById(modalId).classList.add('active');
  document.body.style.overflow = 'hidden';
};

closeModal = function(modalId) {
  document.getElementById(modalId).classList.remove('active');
  document.body.style.overflow = '';
};

// Close modal on overlay click
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('active');
    document.body.style.overflow = '';
  }
});

// Tabs
initTabs = function(containerId) {
  const container = document.getElementById(containerId);
  if (!container) return;

  const buttons = container.querySelectorAll('.tab-btn');
  const panels = container.querySelectorAll('.tab-panel');

  buttons.forEach(btn => {
    btn.addEventListener('click', () => {
      const tab = btn.dataset.tab;

      buttons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      panels.forEach(p => {
        p.classList.toggle('active', p.dataset.panel === tab);
      });
    });
  });
};

// Confirm action
confirmAction = function(message, callback) {
  if (confirm(message)) {
    callback();
  }
};

// Format currency
formatFCFA = function(amount) {
  return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
};

// Format date
formatDate = function(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
};

// Search table
searchTable = function(inputId, tableId) {
  const input = document.getElementById(inputId);
  const table = document.getElementById(tableId);
  if (!input || !table) return;

  input.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(term) ? '' : 'none';
    });
  });
};

// Animate numbers
countUp = function(element, target, duration = 1000) {
  const start = 0;
  const increment = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    element.textContent = Math.floor(current).toLocaleString('fr-FR');
  }, 16);
};

// Initialize count-up animations
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count);
    if (!isNaN(target)) {
      countUp(el, target);
    }
  });
});
