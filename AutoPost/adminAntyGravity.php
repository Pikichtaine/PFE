<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AutoPost — Panel de Administración</title>
  <meta name="description" content="Panel de administración de AutoPost. Gestiona solicitudes de dealers, cuentas y configuración." />
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet" />
  
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
  <link rel="stylesheet" href="CSS/admin.css" />

</head>
<body>
  <h1 class="sr-only">Panel de Administración — AutoPost</h1>
  <!-- ═══════════════════ NAV ════════════════════════════ -->
  <nav class="nav">
    <div class="header-left">
      <div class="nav-logo">
        <div class="nav-logo-icon">▲</div>
        <div class="nav-logo-text">Auto<span>Post</span></div>
      </div>
      <div class="nav-links">
        <button class="btn-luminous" id="btn-coches">Coches</button>
        <button class="btn-luminous" id="btn-concesionarios">Concesionarios</button>
      </div>
    </div>
    <div class="header-right">
      <div class="nav-search">
        <span class="nav-search-icon">⌕</span>
        <input type="text" placeholder="Buscar marca, modelo, ciudad…" />
      </div>
      <button class="nav-location">
        <span class="nav-location-dot"></span>
        Tánger, MA
      </button>
      <a href="#" class="btn-primary">Admin</a>
    </div>
  </nav>
  <!-- ═══════════════════ ADMIN LAYOUT ═══════════════════ -->
  <div class="admin-wrapper">
    <!-- ── Sidebar ──────────────────────────────────────── -->
    <aside class="admin-sidebar">
      <div class="sidebar-header">
        <h2>Panel Admin</h2>
        <div class="sidebar-admin-badge">
          <i class="ti ti-shield-check" aria-hidden="true"></i>
          Administrador
        </div>
      </div>
      <nav class="sidebar-nav">
        <div class="sidebar-nav-item" data-tab="cuentas">
          <i class="ti ti-users" aria-hidden="true"></i>
          Gestión de cuentas
        </div>
        <div class="sidebar-nav-item active" data-tab="solicitudes">
          <i class="ti ti-file-description" aria-hidden="true"></i>
          Solicitudes
        </div>
        <div class="sidebar-nav-item" data-tab="estadisticas">
          <i class="ti ti-chart-bar" aria-hidden="true"></i>
          Estadísticas
        </div>
        <div class="sidebar-nav-item" data-tab="configuracion">
          <i class="ti ti-settings" aria-hidden="true"></i>
          Configuración
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar">AD</div>
          <div class="sidebar-user-info">
            <div class="sidebar-user-name">Admin</div>
            <div class="sidebar-user-role">Super Administrador</div>
          </div>
        </div>
      </div>
    </aside>
    <!-- ── Main Content ─────────────────────────────────── -->
    <main class="admin-main">
      <!-- Page Header -->
      <div class="page-header">
        <div class="page-title-group">
          <h1 id="page-main-title">Solicitudes de Dealer</h1>
          <p id="page-main-subtitle">Revisa y gestiona las solicitudes de nuevos concesionarios</p>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar" id="filter-bar">
        <div class="filter-tabs">
          <button class="filter-tab active" data-filter="all">Todas</button>
          <button class="filter-tab" data-filter="pending">Pendientes</button>
          <button class="filter-tab" data-filter="approved">Aprobadas</button>
          <button class="filter-tab" data-filter="rejected">Rechazadas</button>
        </div>

      </div>
      <!-- Requests List -->
      <div class="requests-list" id="requests-list">
        <!-- Cards rendered by JS -->
      </div>
      <!-- Footer -->
      <div class="list-footer" id="list-footer"></div>
    </main>
  </div>
  <!-- ── Confirm Modal ──────────────────────────────────── -->
  <div class="modal-overlay" id="modal-overlay">
    <div class="modal-box">
      <div class="modal-title" id="modal-title"></div>
      <div class="modal-desc" id="modal-desc"></div>
      <div class="modal-actions">
        <button class="modal-btn modal-btn-cancel" id="modal-cancel">Cancelar</button>
        <button class="modal-btn modal-btn-confirm" id="modal-confirm">Confirmar</button>
      </div>
    </div>
  </div>
  <!-- ── Toast Container ────────────────────────────────── -->
  <div class="toast-container" id="toast-container"></div>
  <!-- ═══════════════════ JAVASCRIPT ═════════════════════ -->
  <script>
    // ── Data ─────────────────────────────────────────────
    const requests = [
      {
        id: 1,
        initials: 'KZ',
        avatarClass: 'avatar-blue',
        username: '@kzracing',
        email: 'kz@gmail.com',
        company: 'Speed Motors SA',
        phone: '+212 661 234 567',
        city: 'Casablanca, MA',
        timeAgo: 'hace 23h',
        status: 'pending'
      },
      {
        id: 2,
        initials: 'MR',
        avatarClass: 'avatar-green',
        username: '@mrmotor',
        email: 'mrmotor@hotmail.com',
        company: 'MR Auto Rabat',
        phone: '+212 537 891 200',
        city: 'Rabat, MA',
        timeAgo: 'hace 3 días',
        status: 'approved'
      },
      {
        id: 3,
        initials: 'SL',
        avatarClass: 'avatar-red',
        username: '@slcars',
        email: 'slcars@gmail.com',
        company: 'SL Premium Cars',
        phone: '+212 522 007 800',
        city: 'Marrakech, MA',
        timeAgo: 'hace 1 semana',
        status: 'rejected'
      },
      {
        id: 4,
        initials: 'AT',
        avatarClass: 'avatar-purple',
        username: '@autotanger',
        email: 'info@autotanger.ma',
        company: 'Auto Tánger SARL',
        phone: '+212 539 112 233',
        city: 'Tánger, MA',
        timeAgo: 'hace 2 días',
        status: 'approved'
      },
      {
        id: 5,
        initials: 'FX',
        avatarClass: 'avatar-yellow',
        username: '@fxmotors',
        email: 'fx@outlook.com',
        company: 'FX Motors Fès',
        phone: '+212 535 667 880',
        city: 'Fès, MA',
        timeAgo: 'hace 5 días',
        status: 'rejected'
      }
    ];
    // ── State ────────────────────────────────────────────
    let currentFilter = 'all';
    let searchQuery   = '';
    let modalAction   = null;
    let modalRequestId = null;
    // ── DOM References ───────────────────────────────────
    const listEl        = document.getElementById('requests-list');
    const footerEl      = document.getElementById('list-footer');
    const searchInput   = document.getElementById('search-input');
    const modalOverlay  = document.getElementById('modal-overlay');
    const modalTitle    = document.getElementById('modal-title');
    const modalDesc     = document.getElementById('modal-desc');
    const modalConfirm  = document.getElementById('modal-confirm');
    const modalCancel   = document.getElementById('modal-cancel');
    const toastContainer = document.getElementById('toast-container');
    // ── Render Functions ─────────────────────────────────
    function getStatusBadge(status) {
      const map = {
        pending:  '<span class="badge badge-pending">Pendiente</span>',
        approved: '<span class="badge badge-approved">Aprobado</span>',
        rejected: '<span class="badge badge-rejected">Rechazado</span>'
      };
      return map[status] || '';
    }
    function getActions(request) {
      switch(request.status) {
        case 'pending':
          return `
            <div class="btn-row">
              <button class="btn-approve" onclick="openModal('approve', ${request.id})">
                <i class="ti ti-check" aria-hidden="true"></i>Aprobar
              </button>
              <button class="btn-reject" onclick="openModal('reject', ${request.id})">
                <i class="ti ti-x" aria-hidden="true"></i>Rechazar
              </button>
            </div>`;
        case 'approved':
          return `
            <div class="btn-row">
              <button class="btn-revoke" onclick="openModal('revoke', ${request.id})">
                <i class="ti ti-ban" aria-hidden="true"></i>Revocar
              </button>
            </div>`;
        case 'rejected':
          return `
            <div class="btn-row">
              <button class="btn-review" onclick="openModal('review', ${request.id})">
                <i class="ti ti-refresh" aria-hidden="true"></i>Revisar
              </button>
            </div>`;
      }
    }
    function renderCard(r) {
      return `
        <div class="request-card" id="card-${r.id}">
          <div class="req-avatar ${r.avatarClass}">${r.initials}</div>
          <div class="req-user">
            <div class="req-username">${r.username}</div>
            <div class="req-email">${r.email}</div>
          </div>
          <div class="req-details">
            <div class="req-detail"><i class="ti ti-building" aria-hidden="true"></i>${r.company}</div>
            <div class="req-detail"><i class="ti ti-phone" aria-hidden="true"></i>${r.phone}</div>
            <div class="req-detail"><i class="ti ti-map-pin" aria-hidden="true"></i>${r.city}</div>
            <div class="req-detail"><i class="ti ti-clock" aria-hidden="true"></i>${r.timeAgo}</div>
          </div>
          <div class="req-actions">
            ${getStatusBadge(r.status)}
            ${getActions(r)}
          </div>
        </div>`;
    }
    function getFiltered() {
      return requests.filter(r => {
        const matchFilter = currentFilter === 'all' || r.status === currentFilter;
        const q = searchQuery.toLowerCase();
        const matchSearch = !q ||
          r.username.toLowerCase().includes(q) ||
          r.email.toLowerCase().includes(q) ||
          r.company.toLowerCase().includes(q) ||
          r.city.toLowerCase().includes(q) ||
          r.phone.includes(q);
        return matchFilter && matchSearch;
      });
    }
    function render() {
      const filtered = getFiltered();
      if (filtered.length === 0) {
        listEl.innerHTML = `
          <div class="empty-state">
            <div class="empty-state-icon"><i class="ti ti-inbox-off" aria-hidden="true"></i></div>
            <h3>No se encontraron solicitudes</h3>
            <p>Prueba con otro filtro o término de búsqueda</p>
          </div>`;
        footerEl.innerHTML = '';
      } else {
        listEl.innerHTML = filtered.map(renderCard).join('');
        const total = filtered.length;
        const pending = filtered.filter(r => r.status === 'pending').length;
        footerEl.innerHTML = `
          ${total} solicitud${total !== 1 ? 'es' : ''}
          <span class="separator"></span>
          ${pending} pendiente${pending !== 1 ? 's' : ''}`;
      }
      updateStats();
    }
    function updateStats() {
      const total    = requests.length;
      const pending  = requests.filter(r => r.status === 'pending').length;
      const approved = requests.filter(r => r.status === 'approved').length;
      const rejected = requests.filter(r => r.status === 'rejected').length;
      animateValue('stat-total', total);
      animateValue('stat-pending', pending);
      animateValue('stat-approved', approved);
      animateValue('stat-rejected', rejected);
      // Update sidebar badge
      const badge = document.getElementById('pending-count-badge');
      badge.textContent = pending;
      badge.style.display = pending > 0 ? 'flex' : 'none';
    }
    function animateValue(elementId, newValue) {
      const el = document.getElementById(elementId);
      const current = parseInt(el.textContent);
      if (current === newValue) return;
      el.style.transform = 'scale(1.2)';
      el.style.transition = 'transform .2s var(--ease)';
      el.textContent = newValue;
      setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
    }
    // ── Modal ────────────────────────────────────────────
    const modalConfig = {
      approve: {
        title: '¿Aprobar solicitud?',
        descFn: (r) => `Vas a aprobar la solicitud de <strong>${r.username}</strong> (${r.company}). El dealer podrá comenzar a publicar vehículos.`,
        confirmClass: 'confirm-approve',
        confirmText: 'Aprobar',
        newStatus: 'approved',
        toastMsg: 'Solicitud aprobada correctamente',
        toastType: 'success'
      },
      reject: {
        title: '¿Rechazar solicitud?',
        descFn: (r) => `Vas a rechazar la solicitud de <strong>${r.username}</strong> (${r.company}). Se le notificará por correo.`,
        confirmClass: 'confirm-reject',
        confirmText: 'Rechazar',
        newStatus: 'rejected',
        toastMsg: 'Solicitud rechazada',
        toastType: 'error'
      },
      revoke: {
        title: '¿Revocar acceso?',
        descFn: (r) => `Vas a revocar el acceso de <strong>${r.username}</strong> (${r.company}). El dealer perderá la capacidad de publicar.`,
        confirmClass: 'confirm-revoke',
        confirmText: 'Revocar',
        newStatus: 'rejected',
        toastMsg: 'Acceso revocado',
        toastType: 'error'
      },
      review: {
        title: '¿Revisar solicitud?',
        descFn: (r) => `Vas a volver a poner en revisión la solicitud de <strong>${r.username}</strong> (${r.company}).`,
        confirmClass: 'confirm-review',
        confirmText: 'Revisar',
        newStatus: 'pending',
        toastMsg: 'Solicitud puesta en revisión',
        toastType: 'info'
      }
    };
    function openModal(action, requestId) {
      const config  = modalConfig[action];
      const request = requests.find(r => r.id === requestId);
      if (!config || !request) return;
      modalAction    = action;
      modalRequestId = requestId;
      modalTitle.textContent = config.title;
      modalDesc.innerHTML    = config.descFn(request);
      modalConfirm.textContent = config.confirmText;
      modalConfirm.className   = 'modal-btn modal-btn-confirm ' + config.confirmClass;
      modalOverlay.classList.add('show');
    }
    function closeModal() {
      modalOverlay.classList.remove('show');
      modalAction    = null;
      modalRequestId = null;
    }
    function confirmAction() {
      if (!modalAction || !modalRequestId) return;
      const config  = modalConfig[modalAction];
      const request = requests.find(r => r.id === modalRequestId);
      if (!request) return;
      const cardEl = document.getElementById(`card-${request.id}`);
      // Animate card
      const animClass = (config.newStatus === 'approved') ? 'card-approving' :
                        (config.newStatus === 'rejected') ? 'card-rejecting' : 'card-approving';
      cardEl.classList.add(animClass);
      closeModal();
      setTimeout(() => {
        cardEl.classList.add('card-exit');
        setTimeout(() => {
          request.status = config.newStatus;
          render();
          showToast(config.toastMsg, config.toastType);
        }, 400);
      }, 300);
    }
    modalCancel.addEventListener('click', closeModal);
    modalConfirm.addEventListener('click', confirmAction);
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeModal();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });
    // ── Toast ────────────────────────────────────────────
    function showToast(message, type = 'success') {
      const iconMap = {
        success: 'ti-circle-check',
        error:   'ti-alert-circle',
        info:    'ti-info-circle'
      };
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `<i class="ti ${iconMap[type]}" aria-hidden="true"></i><span>${message}</span>`;
      toastContainer.appendChild(toast);
      setTimeout(() => {
        toast.classList.add('toast-exit');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
    // ── Filter Tabs ──────────────────────────────────────
    document.querySelectorAll('.filter-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentFilter = tab.dataset.filter;
        render();
      });
    });
    // ── Search ───────────────────────────────────────────
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value;
      render();
    });
    // ── Sidebar Nav (decorative, only solicitudes active) ──
    document.querySelectorAll('.sidebar-nav-item').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-nav-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        // Only solicitudes tab shows content, others show "coming soon"
        const tab = item.dataset.tab;
        const mainTitle = document.getElementById('page-main-title');
        const mainSubtitle = document.getElementById('page-main-subtitle');
        const statsRow = document.getElementById('stats-row');
        const filterBar = document.getElementById('filter-bar');
        const footer = document.getElementById('list-footer');
        if (tab === 'solicitudes') {
          mainTitle.textContent = 'Solicitudes de Dealer';
          mainSubtitle.textContent = 'Revisa y gestiona las solicitudes de nuevos concesionarios';
          statsRow.style.display = '';
          filterBar.style.display = '';
          footer.style.display = '';
          render();
        } else {
          const titles = {
            cuentas: ['Gestión de Cuentas', 'Administra las cuentas de usuarios y dealers'],
            estadisticas: ['Estadísticas', 'Analiza el rendimiento y las métricas de la plataforma'],
            configuracion: ['Configuración', 'Ajusta los parámetros generales de la plataforma']
          };
          const icons = {
            cuentas: 'ti-users-group',
            estadisticas: 'ti-chart-dots-3',
            configuracion: 'ti-adjustments-horizontal'
          };
          mainTitle.textContent = titles[tab][0];
          mainSubtitle.textContent = titles[tab][1];
          statsRow.style.display = 'none';
          filterBar.style.display = 'none';
          footer.style.display = 'none';
          listEl.innerHTML = `
            <div class="empty-state">
              <div class="empty-state-icon"><i class="ti ${icons[tab]}" aria-hidden="true"></i></div>
              <h3>${titles[tab][0]}</h3>
              <p>Esta sección estará disponible próximamente</p>
            </div>`;
        }
      });
    });
    // ── Refresh Button ───────────────────────────────────
    document.getElementById('btn-refresh').addEventListener('click', () => {
      const btn = document.getElementById('btn-refresh');
      const icon = btn.querySelector('i');
      icon.style.transition = 'transform .5s var(--ease)';
      icon.style.transform = 'rotate(360deg)';
      setTimeout(() => { icon.style.transform = ''; }, 550);
      render();
      showToast('Datos actualizados', 'info');
    });
    // ── Export Button ────────────────────────────────────
    document.getElementById('btn-export').addEventListener('click', () => {
      showToast('Exportación iniciada…', 'info');
    });
    // ── Initial Render ───────────────────────────────────
    render();
  </script>
</body>
</html>
