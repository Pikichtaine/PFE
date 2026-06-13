<?php 
session_start();
/* ==========================================
   SEGURIDAD (Opcional, descomentar para usar)
   Verifica que quien entra sea un admin
========================================== */
/*
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: Login.php');
    exit;
}
*/
/* ==========================================
   CONEXIÓN A LA BASE DE DATOS
========================================== */
require 'Database.php'; // <-- CAMBIA ESTO por tu archivo de conexión a la base de datos

/* ==========================================
   PROCESAR ACCIONES (Aprobar, Rechazar, Revocar)
========================================== */
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action']; // 'approved', 'rejected', 'pending'
    $id = (int)$_GET['id'];
    
    if (in_array($action, ['approved', 'rejected', 'pending'])) {
        // Actualizar el estado en la tabla dealer_requests
        $stmt = $pdo->prepare("UPDATE dealer_requests SET status = ? WHERE id = ?");
        $stmt->execute([$action, $id]);
        
        $msg = "Solicitud actualizada correctamente.";
        if ($action == 'approved') $msg = "Solicitud aprobada con éxito.";
        if ($action == 'rejected') $msg = "Solicitud rechazada / revocado.";
        if ($action == 'pending') $msg = "Solicitud puesta en revisión.";
        // Redirigir para evitar re-envíos y pasar el mensaje
        header("Location: admin.php?msg=" . urlencode($msg));
        exit;
    }
}
/* ==========================================
   OBTENER DATOS (Consultas SQL)
========================================== */
// Unimos dealer_requests con utilisateur para sacar el email
$sql = "SELECT dr.id, dr.username, dr.dealer_name, dr.city, dr.phone, dr.status, dr.created_at, u.email 
        FROM dealer_requests dr 
        LEFT JOIN utilisateur u ON dr.id_utilisateur = u.id
        ORDER BY dr.created_at DESC";
$stmt = $pdo->query($sql);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AutoPost — Panel de Administración</title>
  <meta name="description" content="Panel de administración de AutoPost." />
  
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
      <a href="#" class="btn-primary">
        <?php echo isset($_SESSION['utilisateur']) ? htmlspecialchars($_SESSION['utilisateur']) : 'Admin'; ?>
      </a>
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
          <i class="ti ti-users" aria-hidden="true"></i> Gestión de cuentas
        </div>
        <div class="sidebar-nav-item active" data-tab="solicitudes">
          <i class="ti ti-file-description" aria-hidden="true"></i> Solicitudes
        </div>
        <div class="sidebar-nav-item" data-tab="estadisticas">
          <i class="ti ti-chart-bar" aria-hidden="true"></i> Estadísticas
        </div>
        <div class="sidebar-nav-item" data-tab="configuracion">
          <i class="ti ti-settings" aria-hidden="true"></i> Configuración
        </div>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <div class="sidebar-user-avatar">
            <?php echo isset($_SESSION['utilisateur']) ? strtoupper(substr($_SESSION['utilisateur'], 0, 2)) : 'AD'; ?>
          </div>
          <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?php echo isset($_SESSION['utilisateur']) ? htmlspecialchars($_SESSION['utilisateur']) : 'Admin'; ?></div>
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
        <?php foreach ($requests as $req): 
            // Generar iniciales (ej: Pikichtaine -> PI)
            $initials = strtoupper(substr($req['username'], 0, 2));
            
            // Asignar color de avatar según estado
            $avatarClass = 'avatar-blue';
            if ($req['status'] == 'approved') $avatarClass = 'avatar-green';
            if ($req['status'] == 'rejected') $avatarClass = 'avatar-red';
            if ($req['status'] == 'pending')  $avatarClass = 'avatar-yellow';
            
            // Generar el badge de estado
            $badge = '';
            if ($req['status'] == 'pending')  $badge = '<span class="badge badge-pending">Pendiente</span>';
            if ($req['status'] == 'approved') $badge = '<span class="badge badge-approved">Aprobado</span>';
            if ($req['status'] == 'rejected') $badge = '<span class="badge badge-rejected">Rechazado</span>';
            // Cadena oculta para la búsqueda en JS
            
        ?>
        <div class="request-card" id="card-<?php echo $req['id']; ?>" 
             data-status="<?php echo htmlspecialchars($req['status']); ?>" 
             data-username="<?php echo htmlspecialchars($req['username']); ?>" 
             data-company="<?php echo htmlspecialchars($req['dealer_name']); ?>">
             
          <div class="req-avatar <?php echo $avatarClass; ?>"><?php echo htmlspecialchars($initials); ?></div>
          <div class="req-user">
            <div class="req-username">@<?php echo htmlspecialchars($req['username']); ?></div>
            <div class="req-email"><?php echo htmlspecialchars($req['email'] ?? 'Sin email'); ?></div>
          </div>
          <div class="req-details">
            <div class="req-detail"><i class="ti ti-building" aria-hidden="true"></i><?php echo htmlspecialchars($req['dealer_name']); ?></div>
            <div class="req-detail"><i class="ti ti-phone" aria-hidden="true"></i><?php echo htmlspecialchars($req['phone']); ?></div>
            <div class="req-detail"><i class="ti ti-map-pin" aria-hidden="true"></i><?php echo htmlspecialchars($req['city']); ?></div>
            <div class="req-detail"><i class="ti ti-clock" aria-hidden="true"></i><?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></div>
          </div>
          <div class="req-actions">
            <?php echo $badge; ?>
            <div class="btn-row">
              <?php if ($req['status'] == 'pending'): ?>
                <button class="btn-approve" onclick="openModal('approve', <?php echo $req['id']; ?>)">
                  <i class="ti ti-check" aria-hidden="true"></i>Aprobar
                </button>
                <button class="btn-reject" onclick="openModal('reject', <?php echo $req['id']; ?>)">
                  <i class="ti ti-x" aria-hidden="true"></i>Rechazar
                </button>
              <?php elseif ($req['status'] == 'approved'): ?>
                <button class="btn-revoke" onclick="openModal('revoke', <?php echo $req['id']; ?>)">
                  <i class="ti ti-ban" aria-hidden="true"></i>Revocar
                </button>
              <?php elseif ($req['status'] == 'rejected'): ?>
                <button class="btn-review" onclick="openModal('review', <?php echo $req['id']; ?>)">
                  <i class="ti ti-refresh" aria-hidden="true"></i>Revisar
                </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($requests) == 0): ?>
          <div class="empty-state">
            <div class="empty-state-icon"><i class="ti ti-inbox-off" aria-hidden="true"></i></div>
            <h3>No se encontraron solicitudes</h3>
            <p>Aún no hay datos en la base de datos.</p>
          </div>
        <?php endif; ?>
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
    // ── Referencias ──────────────────────────────────────
    const cards = document.querySelectorAll('.request-card');
    
    const filterTabs = document.querySelectorAll('.filter-tab');
    const footerEl = document.getElementById('list-footer');
    
    let currentFilter = 'all';
    // ── Toast ────────────────────────────────────────────
    function showToast(message, type = 'success') {
      const toastContainer = document.getElementById('toast-container');
      const iconMap = { success: 'ti-circle-check', error: 'ti-alert-circle', info: 'ti-info-circle' };
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `<i class="ti ${iconMap[type]}" aria-hidden="true"></i><span>${message}</span>`;
      toastContainer.appendChild(toast);
      setTimeout(() => {
        toast.classList.add('toast-exit');
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
    // Mostrar mensaje si venimos de PHP
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    if (msg) {
      const isError = msg.includes('Rechazada') || msg.includes('revocado');
      showToast(msg, isError ? 'error' : 'success');
      // Limpiar URL
      window.history.replaceState({}, document.title, window.location.pathname);
    }
    // ── Filtros y Búsqueda (UI Client-Side) ──────────────
    function applyFilters() {
      let visibleCount = 0;
      let pendingCount = 0;
      cards.forEach(card => {
        const status = card.dataset.status;
        const matchFilter = currentFilter === 'all' || status === currentFilter;
        if (matchFilter) {
          card.style.display = 'flex';
          visibleCount++;
          if (status === 'pending') pendingCount++;
        } else {
          card.style.display = 'none';
        }
      });
      if (footerEl) {
        footerEl.innerHTML = `${visibleCount} solicitud${visibleCount !== 1 ? 'es' : ''} <span class="separator"></span> ${pendingCount} pendiente${pendingCount !== 1 ? 's' : ''}`;
      }
    }
    filterTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        filterTabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentFilter = tab.dataset.filter;
        applyFilters();
      });
    });

    // Llamar al inicio para actualizar el footer
    applyFilters();
    // ── Modal Logic ──────────────────────────────────────
    let modalAction = null;
    let modalRequestId = null;
    const modalConfig = {
      approve: { title: '¿Aprobar solicitud?', class: 'confirm-approve', text: 'Aprobar', newStatus: 'approved' },
      reject:  { title: '¿Rechazar solicitud?', class: 'confirm-reject', text: 'Rechazar', newStatus: 'rejected' },
      revoke:  { title: '¿Revocar acceso?', class: 'confirm-revoke', text: 'Revocar', newStatus: 'rejected' },
      review:  { title: '¿Revisar solicitud?', class: 'confirm-review', text: 'Revisar', newStatus: 'pending' }
    };
    function openModal(action, id) {
      const config = modalConfig[action];
      const card = document.getElementById('card-' + id);
      if (!config || !card) return;
      const username = card.dataset.username;
      const company = card.dataset.company;
      modalAction = action;
      modalRequestId = id;
      document.getElementById('modal-title').textContent = config.title;
      document.getElementById('modal-desc').innerHTML = `¿Seguro que deseas proceder con <strong>${username}</strong> (${company})?`;
      
      const btnConfirm = document.getElementById('modal-confirm');
      btnConfirm.textContent = config.text;
      btnConfirm.className = 'modal-btn modal-btn-confirm ' + config.class;
      
      document.getElementById('modal-overlay').classList.add('show');
    }
    function closeModal() {
      document.getElementById('modal-overlay').classList.remove('show');
    }
    function confirmAction() {
      if (!modalAction || !modalRequestId) return;
      const config = modalConfig[modalAction];
      
      // REDIRIGIR a PHP para que actualice la Base de Datos
      window.location.href = `admin.php?action=${config.newStatus}&id=${modalRequestId}`;
    }
    document.getElementById('modal-cancel').addEventListener('click', closeModal);
    document.getElementById('modal-confirm').addEventListener('click', confirmAction);
    // Sidebar Menu (solo estético para "Solicitudes")
    document.querySelectorAll('.sidebar-nav-item').forEach(item => {
      item.addEventListener('click', () => {
        document.querySelectorAll('.sidebar-nav-item').forEach(i => i.classList.remove('active'));
        item.classList.add('active');
        if (item.dataset.tab !== 'solicitudes') {
          document.getElementById('requests-list').innerHTML = `
            <div class="empty-state">
              <div class="empty-state-icon"><i class="ti ti-tools" aria-hidden="true"></i></div>
              <h3>Próximamente</h3>
              <p>Esta sección aún no está programada en base de datos.</p>
            </div>`;
        } else {
          location.reload(); // Recargar para mostrar solicitudes de nuevo
        }
      });
    });
  </script>
</body>
</html>