<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);

/* ─── Auth: solo admin ──────────────────────────────── */
if (!$is_logged_in || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: Login.php');
    exit;
}

/* ─── Conexión PDO ──────────────────────────────────── */
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=autopost;charset=utf8mb4',
        'root', '',
        [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('DB Error: ' . $e->getMessage());
}

/* ─── Acciones POST ─────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act  = $_POST['action']      ?? '';
    $sid  = (int)($_POST['sol_id']  ?? 0);
    $uid  = (int)($_POST['user_id'] ?? 0);
    $ctab = $_POST['current_tab']   ?? 'solicitudes';

    switch ($act) {
        case 'aprobar':
            $pdo->prepare("UPDATE solicitudes_dealer SET statut='aprobado' WHERE id=?")->execute([$sid]);
            /* Promueve el usuario a dealer */
            $pdo->prepare("UPDATE users u INNER JOIN solicitudes_dealer s ON u.id=s.user_id SET u.role='dealer' WHERE s.id=?")->execute([$sid]);
            break;
        case 'rechazar':
            $pdo->prepare("UPDATE solicitudes_dealer SET statut='rechazado' WHERE id=?")->execute([$sid]);
            break;
        case 'revocar':
            $pdo->prepare("UPDATE solicitudes_dealer SET statut='rechazado' WHERE id=?")->execute([$sid]);
            $pdo->prepare("UPDATE users u INNER JOIN solicitudes_dealer s ON u.id=s.user_id SET u.role='client' WHERE s.id=?")->execute([$sid]);
            break;
        case 'ban_user':
            $pdo->prepare("UPDATE users SET status='banned' WHERE id=? AND role!='admin'")->execute([$uid]);
            break;
        case 'unban_user':
            $pdo->prepare("UPDATE users SET status='active' WHERE id=?")->execute([$uid]);
            break;
        case 'del_user':
            $pdo->prepare("DELETE FROM users WHERE id=? AND role!='admin'")->execute([$uid]);
            break;
    }

    header("Location: admin.php?tab=$ctab");
    exit;
}

$tab = htmlspecialchars($_GET['tab'] ?? 'solicitudes');

/* ─── Datos ─────────────────────────────────────────── */
/*
 * TODO: Ajusta los nombres de columnas si difieren en tu tabla.
 * Columnas esperadas en solicitudes_dealer:
 *   id, user_id, nom_concessionaire, telephone, localisation, statut, created_at
 */
$sols = $pdo->query("
    SELECT s.id, s.user_id, s.nom_concessionaire, s.telephone,
           s.localisation, s.statut, s.created_at,
           u.username, u.email
    FROM solicitudes_dealer s
    INNER JOIN users u ON u.id = s.user_id
    ORDER BY FIELD(s.statut,'pendiente','aprobado','rechazado'), s.created_at DESC
")->fetchAll();

$users = $pdo->query("
    SELECT id, username, email, role, status, created_at
    FROM users
    ORDER BY created_at DESC
")->fetchAll();

$n_users   = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$n_dealers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='dealer'")->fetchColumn();
$n_coches  = (int)$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(); /* TODO: ajusta el nombre de tabla */
$n_pend    = (int)$pdo->query("SELECT COUNT(*) FROM solicitudes_dealer WHERE statut='pendiente'")->fetchColumn();

/* ─── Helpers ───────────────────────────────────────── */
function ago(string $dt): string {
    $s = time() - strtotime($dt);
    if ($s < 60)     return 'ahora';
    if ($s < 3600)   return floor($s / 60) . 'min';
    if ($s < 86400)  return floor($s / 3600) . 'h';
    if ($s < 604800) return floor($s / 86400) . ' días';
    return date('d/m/Y', strtotime($dt));
}

function abg(string $n): string {
    static $p = ['#7F77DD','#FF6B35','#4ade80','#FFB800','#5E9BFF','#e05252','#00c9a7'];
    return $p[abs(crc32($n)) % 7];
}

function ini(string $n): string {
    $w = explode(' ', trim($n));
    return strtoupper(substr($w[0], 0, 1) . (isset($w[1]) ? substr($w[1], 0, 1) : ''));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — AutoPost</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
/* ─── Reset ─────────────────────────────────────────── */
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
button { font-family: var(--f-body); cursor: pointer; }
a { text-decoration: none; }

/* ─── Design tokens ─────────────────────────────────── */
:root {
  /* Backgrounds */
  --bg:  #070708;
  --s1:  #0D0D0F;
  --s2:  #141416;
  --s3:  #1C1C20;
  --s4:  #222228;

  /* Borders */
  --border:    rgba(255,255,255,.07);
  --border2:   rgba(255,255,255,.12);
  --border-hi: rgba(255,255,255,.20);

  /* Accent principal — naranja AutoPost */
  --accent:      #FF4500;
  --accent-dim:  rgba(255,69,0,.12);
  --accent-glow: rgba(255,69,0,.30);

  /* Admin accent — violeta */
  --adm:      #7F77DD;
  --adm-dim:  rgba(127,119,221,.13);
  --adm-glow: rgba(127,119,221,.30);

  /* Semánticos */
  --green: #4ade80; --green-dim: rgba(74,222,128,.10); --green-b: rgba(74,222,128,.22);
  --amber: #FFB800; --amber-dim: rgba(255,184,0,.10);  --amber-b: rgba(255,184,0,.22);
  --red:   #ff5252; --red-dim:   rgba(255,82,82,.10);  --red-b:   rgba(255,82,82,.22);
  --blue:  #5E9BFF; --blue-dim:  rgba(94,155,255,.10); --blue-b:  rgba(94,155,255,.22);

  /* Texto */
  --txt:  #F0EBE3;
  --txt2: #9896A0;
  --txt3: #5A5860;

  /* Fuentes */
  --f-display: 'Bebas Neue', sans-serif;
  --f-body:    'Syne', sans-serif;
  --f-mono:    'DM Mono', monospace;

  /* Radios */
  --r:    10px;
  --r-lg: 16px;
  --r-xl: 22px;
}

/* ─── Base ──────────────────────────────────────────── */
html, body {
  background: var(--bg);
  color: var(--txt);
  font-family: var(--f-body);
  font-size: 14px;
  line-height: 1.5;
}

/* ═══════════════════════════════════════════════════════
   NAV
════════════════════════════════════════════════════════ */
.nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  height: 60px;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 28px; gap: 20px;
  background: rgba(7,7,8,.90);
  backdrop-filter: blur(24px) saturate(180%);
  border-bottom: 1px solid var(--border);
}
.header-left  { display: flex; align-items: center; gap: 32px; }
.header-right { display: flex; align-items: center; gap: 12px; }

.nav-logo { display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
.nav-logo-icon {
  width: 32px; height: 32px;
  background: var(--accent); border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 14px; font-weight: 900;
}
.nav-logo-text { font-family: var(--f-display); font-size: 22px; letter-spacing: 1px; color: var(--txt); }
.nav-logo-text span { color: var(--accent); }

.nav-links { display: flex; align-items: center; gap: 12px; }

.btn-luminous {
  background: transparent; color: var(--txt2);
  font-family: var(--f-body); font-size: 13px; font-weight: 600;
  padding: 6px 14px; border-radius: var(--r);
  border: 1px solid var(--border);
  box-shadow: 0 0 5px rgba(255,255,255,.05);
  transition: all .3s ease;
  display: inline-flex; align-items: center; justify-content: center;
}
.btn-luminous:hover {
  color: var(--txt); border-color: var(--border-hi);
  box-shadow: 0 0 12px var(--border-hi), inset 0 0 4px var(--border-hi);
  text-shadow: 0 0 6px var(--border-hi);
  transform: translateY(-1px);
}

.nav-search { flex: 1; max-width: 480px; position: relative; }
.nav-search input {
  width: 100%; height: 38px;
  background: var(--s3); border: 1px solid var(--border); border-radius: 8px;
  padding: 0 14px 0 38px;
  font-family: var(--f-body); font-size: 13px; color: var(--txt);
  outline: none; transition: border-color .2s;
}
.nav-search input::placeholder { color: var(--txt3); }
.nav-search input:focus { border-color: var(--border-hi); }
.nav-search-icon {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  color: var(--txt3); font-size: 14px; pointer-events: none;
}

.nav-location {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: var(--txt2);
  background: none; padding: 6px 10px; border-radius: var(--r);
  border: 1px solid var(--border); transition: border-color .2s, color .2s;
}
.nav-location:hover { color: var(--txt); border-color: var(--border-hi); }
.nav-location-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--accent); flex-shrink: 0; }

.btn-primary {
  background: var(--accent); color: #fff;
  padding: 7px 18px; border-radius: var(--r);
  font-size: 13px; font-weight: 700; letter-spacing: .3px;
  border: none; transition: opacity .2s, transform .15s;
  text-decoration: none; display: inline-flex; align-items: center; justify-content: center;
}
.btn-primary:hover { opacity: .88; transform: translateY(-1px); }

/* Badge admin en nav */
.nav-admin-badge {
  font-size: 10px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
  background: var(--adm-dim); color: var(--adm);
  border: 1px solid rgba(127,119,221,.25);
  padding: 3px 10px; border-radius: 20px;
  display: flex; align-items: center; gap: 5px;
}

/* ═══════════════════════════════════════════════════════
   LAYOUT
════════════════════════════════════════════════════════ */
.admin-wrapper { padding-top: 60px; min-height: 100vh; }

/* ─── Subheader con tabs ────────────────────────────── */
.admin-subheader {
  position: sticky; top: 60px; z-index: 90;
  background: rgba(7,7,8,.92);
  backdrop-filter: blur(20px) saturate(150%);
  border-bottom: 1px solid var(--border);
  padding: 0 28px;
  display: flex; align-items: stretch;
}
.admin-tabs { display: flex; }
.admin-tab {
  display: flex; align-items: center; gap: 8px;
  padding: 0 22px; height: 52px;
  font-size: 13px; font-weight: 600; letter-spacing: .2px;
  color: var(--txt3); background: none; border: none;
  border-bottom: 2px solid transparent;
  transition: color .2s, border-color .2s;
}
.admin-tab i { font-size: 15px; }
.admin-tab:hover { color: var(--txt2); }
.admin-tab.active { color: var(--txt); border-bottom-color: var(--accent); }
.tab-badge {
  font-size: 10px; font-weight: 700;
  padding: 2px 7px; border-radius: 20px;
  background: var(--accent-dim); color: var(--accent);
}

/* ─── Área de contenido ─────────────────────────────── */
.admin-content { padding: 32px 28px; max-width: 1400px; margin: 0 auto; }

.tab-panel { display: none; animation: fadein .2s ease; }
.tab-panel.active { display: block; }
@keyframes fadein {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: none; }
}

/* ─── Cabecera de sección ───────────────────────────── */
.sec-header { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 22px; }
.sec-title  { font-family: var(--f-display); font-size: 32px; letter-spacing: 1.5px; line-height: 1; }
.sec-count  { font-size: 12px; color: var(--txt3); font-family: var(--f-mono); margin-top: 4px; }

/* ─── Filtros ───────────────────────────────────────── */
.filter-bar { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
.chip {
  padding: 6px 16px; border-radius: 20px;
  border: 1px solid var(--border2); background: var(--s2);
  color: var(--txt2); font-size: 12px; font-weight: 600; font-family: var(--f-body);
  cursor: pointer; transition: all .2s;
}
.chip:hover { border-color: var(--border-hi); color: var(--txt); }
.chip.active { background: var(--accent-dim); border-color: rgba(255,69,0,.35); color: var(--accent); }

/* ═══════════════════════════════════════════════════════
   ROW CARDS
════════════════════════════════════════════════════════ */
.row-card {
  display: flex; align-items: center; gap: 16px;
  padding: 15px 20px;
  background: var(--s1); border: 1px solid var(--border);
  border-left: 3px solid transparent;
  border-radius: var(--r-lg); margin-bottom: 10px;
  transition: border-color .25s, background .25s;
}
.row-card:hover { border-right-color: var(--border2); border-top-color: var(--border2); border-bottom-color: var(--border2); background: var(--s2); }

/* Color-coded left border según estado */
.row-card.pending  { border-left-color: var(--amber); }
.row-card.approved { border-left-color: var(--green); }
.row-card.rejected { border-left-color: var(--red); opacity: .6; }

/* Avatar */
.ava {
  width: 44px; height: 44px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; font-family: var(--f-mono);
  color: #fff; flex-shrink: 0;
}

/* Columna usuario */
.col-user { min-width: 148px; flex-shrink: 0; }
.uname { font-size: 14px; font-weight: 700; color: var(--txt); }
.uemail { font-size: 11px; color: var(--txt3); font-family: var(--f-mono); margin-top: 2px; }

/* Columna detalles */
.col-details {
  flex: 1; display: grid; grid-template-columns: 1fr 1fr;
  gap: 6px 28px;
}
.det {
  display: flex; align-items: center; gap: 7px;
  font-size: 12px; color: var(--txt2);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.det i { font-size: 13px; color: var(--txt3); flex-shrink: 0; }

/* Columna acciones */
.col-actions {
  display: flex; flex-direction: column;
  align-items: flex-end; gap: 10px;
  flex-shrink: 0; min-width: 165px;
}

/* ─── Badges ────────────────────────────────────────── */
.badge {
  font-size: 10px; font-weight: 700;
  padding: 3px 11px; border-radius: 20px;
  letter-spacing: .4px; text-transform: uppercase;
}
.b-pending  { background: var(--amber-dim); color: var(--amber); border: 1px solid var(--amber-b); }
.b-approved { background: var(--green-dim); color: var(--green); border: 1px solid var(--green-b); }
.b-rejected { background: var(--red-dim);   color: var(--red);   border: 1px solid var(--red-b);   }
.b-client   { background: var(--blue-dim);  color: var(--blue);  border: 1px solid var(--blue-b);  }
.b-dealer   { background: var(--accent-dim);color: var(--accent);border: 1px solid rgba(255,69,0,.25); }
.b-admin    { background: var(--adm-dim);   color: var(--adm);   border: 1px solid rgba(127,119,221,.25); }
.b-banned   { background: var(--red-dim);   color: var(--red);   border: 1px solid var(--red-b);   }
.b-active   { background: var(--green-dim); color: var(--green); border: 1px solid var(--green-b); }

/* ─── Botones de acción ─────────────────────────────── */
.btn-row { display: flex; gap: 7px; }
.btn-a {
  padding: 5px 13px; border-radius: var(--r);
  font-size: 11px; font-weight: 700; font-family: var(--f-body);
  cursor: pointer; border: 1px solid;
  display: inline-flex; align-items: center; gap: 5px;
  transition: opacity .2s, transform .15s;
}
.btn-a:hover { opacity: .8; transform: translateY(-1px); }
.ba-approve { background: var(--green-dim); color: var(--green); border-color: var(--green-b); }
.ba-reject  { background: var(--red-dim);   color: var(--red);   border-color: var(--red-b);   }
.ba-revoke  { background: var(--amber-dim); color: var(--amber); border-color: var(--amber-b); }
.ba-ban     { background: var(--red-dim);   color: var(--red);   border-color: var(--red-b);   }
.ba-unban   { background: var(--green-dim); color: var(--green); border-color: var(--green-b); }
.ba-view    { background: var(--blue-dim);  color: var(--blue);  border-color: var(--blue-b);  }
.ba-del     { background: transparent;      color: var(--txt3);  border-color: var(--border);  }
.ba-del:hover { color: var(--red); border-color: var(--red-b); background: var(--red-dim); }

/* ─── Empty state ───────────────────────────────────── */
.empty-state {
  text-align: center; padding: 64px 20px;
  color: var(--txt3); font-size: 13px;
}
.empty-state i { font-size: 40px; display: block; margin-bottom: 12px; opacity: .4; }

/* ═══════════════════════════════════════════════════════
   ESTADÍSTICAS
════════════════════════════════════════════════════════ */
.stats-grid {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 16px; margin-bottom: 28px;
}
.stat-card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 22px 24px;
  transition: border-color .2s, transform .2s;
}
.stat-card:hover { border-color: var(--border2); transform: translateY(-2px); }
.stat-icon { font-size: 20px; margin-bottom: 12px; }
.stat-label {
  font-size: 11px; color: var(--txt3);
  text-transform: uppercase; letter-spacing: .6px;
  font-weight: 600; margin-bottom: 4px;
}
.stat-value { font-family: var(--f-display); font-size: 52px; line-height: 1; letter-spacing: 1px; }
.stat-sub { font-size: 11px; color: var(--txt3); margin-top: 8px; font-family: var(--f-mono); }

.ratio-card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 22px 24px;
  max-width: 640px; margin-bottom: 16px;
}
.ratio-head {
  font-size: 13px; font-weight: 700; margin-bottom: 16px;
  display: flex; align-items: center; gap: 8px;
}
.ratio-head i { color: var(--txt3); }
.ratio-bar { display: flex; gap: 3px; border-radius: 6px; overflow: hidden; height: 8px; margin-bottom: 14px; }
.ratio-legend { display: flex; gap: 24px; font-size: 12px; color: var(--txt2); }
.ratio-dot {
  display: inline-block; width: 10px; height: 10px;
  border-radius: 2px; margin-right: 6px; vertical-align: middle;
}

/* ═══════════════════════════════════════════════════════
   CONFIGURACIÓN
════════════════════════════════════════════════════════ */
.settings-section { max-width: 660px; }
.settings-card {
  background: var(--s1); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 24px; margin-bottom: 14px;
}
.settings-head {
  font-size: 13px; font-weight: 700; color: var(--txt);
  padding-bottom: 14px; margin-bottom: 18px;
  border-bottom: 1px solid var(--border);
  display: flex; align-items: center; gap: 8px;
}
.settings-head i { color: var(--txt3); font-size: 15px; }
.form-group { margin-bottom: 14px; }
.form-label {
  font-size: 11px; color: var(--txt2); margin-bottom: 6px;
  display: block; text-transform: uppercase; letter-spacing: .4px; font-weight: 600;
}
.form-input {
  width: 100%; background: var(--s3); border: 1px solid var(--border);
  border-radius: var(--r); padding: 10px 14px;
  font-family: var(--f-body); font-size: 13px; color: var(--txt);
  outline: none; transition: border-color .2s;
}
.form-input:focus { border-color: var(--border-hi); }
.btn-save {
  background: var(--accent); color: #fff;
  padding: 8px 20px; border-radius: var(--r);
  font-size: 13px; font-weight: 700; border: none;
  cursor: pointer; margin-top: 6px;
  transition: opacity .2s, transform .15s;
}
.btn-save:hover { opacity: .88; transform: translateY(-1px); }

/* Toggles */
.form-toggle {
  display: flex; justify-content: space-between; align-items: center;
  padding: 13px 0; border-bottom: 1px solid var(--border);
}
.form-toggle:last-child { border-bottom: none; }
.toggle-label { font-size: 13px; color: var(--txt2); }
.toggle-sub { font-size: 11px; color: var(--txt3); margin-top: 2px; }
.toggle-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
  position: absolute; cursor: pointer; inset: 0;
  background: var(--s4); border: 1px solid var(--border);
  border-radius: 22px; transition: .3s;
}
.toggle-slider::before {
  content: ''; position: absolute;
  width: 16px; height: 16px; border-radius: 50%;
  background: var(--txt3); left: 3px; top: 50%; transform: translateY(-50%);
  transition: .3s;
}
.toggle-switch input:checked + .toggle-slider { background: var(--accent-dim); border-color: rgba(255,69,0,.4); }
.toggle-switch input:checked + .toggle-slider::before { background: var(--accent); transform: translate(18px,-50%); }
</style>
</head>
<body>

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
      <input type="text" placeholder="Buscar marca, modelo, ciudad…">
    </div>

    <button class="nav-location">
      <span class="nav-location-dot"></span>
      Tánger, MA
    </button>

    <?php if ($is_logged_in): ?>
      <span class="nav-admin-badge">
        <i class="ti ti-shield-check" style="font-size:11px;"></i>Admin
      </span>
      <a href="favoritos.php" class="btn-luminous">Favoritos</a>
      <a href="carrito.php"   class="btn-luminous">Carrito</a>
      <a href="Profil.php"    class="btn-primary">Mi Perfil</a>
    <?php else: ?>
      <a href="Login.php" class="btn-primary" id="btn-connexion">Connexion</a>
    <?php endif; ?>
  </div>
</nav>

<!-- ═══════════════════ DASHBOARD ══════════════════════ -->
<div class="admin-wrapper">

  <!-- Subheader con tabs -->
  <div class="admin-subheader">
    <div class="admin-tabs">

      <button class="admin-tab<?= $tab === 'cuentas'      ? ' active' : '' ?>" data-target="cuentas">
        <i class="ti ti-users"></i> Gestión de cuentas
      </button>

      <button class="admin-tab<?= $tab === 'solicitudes'  ? ' active' : '' ?>" data-target="solicitudes">
        <i class="ti ti-file-check"></i> Solicitudes
        <?php if ($n_pend > 0): ?>
          <span class="tab-badge"><?= $n_pend ?></span>
        <?php endif; ?>
      </button>

      <button class="admin-tab<?= $tab === 'estadisticas' ? ' active' : '' ?>" data-target="estadisticas">
        <i class="ti ti-chart-bar"></i> Estadísticas
      </button>

      <button class="admin-tab<?= $tab === 'configuracion'? ' active' : '' ?>" data-target="configuracion">
        <i class="ti ti-settings"></i> Configuración
      </button>

    </div>
  </div>

  <div class="admin-content">

    <!-- ══════════════════════════════════════════════
         TAB: SOLICITUDES
    ═══════════════════════════════════════════════ -->
    <div class="tab-panel<?= $tab === 'solicitudes' ? ' active' : '' ?>" id="tab-solicitudes">

      <div class="sec-header">
        <div>
          <div class="sec-title">Solicitudes dealer</div>
          <div class="sec-count" id="sol-count"><?= count($sols) ?> solicitudes</div>
        </div>
      </div>

      <div class="filter-bar">
        <button class="chip active" data-filter="all">Todas</button>
        <button class="chip" data-filter="pendiente">Pendientes <?php if ($n_pend): ?><span style="opacity:.6">(<?= $n_pend ?>)</span><?php endif; ?></button>
        <button class="chip" data-filter="aprobado">Aprobadas</button>
        <button class="chip" data-filter="rechazado">Rechazadas</button>
      </div>

      <div id="sol-rows">

        <?php if (empty($sols)): ?>
          <div class="empty-state">
            <i class="ti ti-file-off"></i>
            Sin solicitudes por el momento.
          </div>

        <?php else: foreach ($sols as $s):
          $cls = match ($s['statut'] ?? 'pendiente') {
            'aprobado'  => 'approved',
            'rechazado' => 'rejected',
            default     => 'pending'
          };
          $bg = abg($s['username'] ?? '?');
          $in = ini($s['username'] ?? '?');
        ?>

        <div class="row-card <?= $cls ?>" data-status="<?= htmlspecialchars($s['statut']) ?>">

          <!-- Avatar -->
          <div class="ava"
               style="background:<?= $bg ?>22; color:<?= $bg ?>; border:1px solid <?= $bg ?>44;">
            <?= $in ?>
          </div>

          <!-- Usuario -->
          <div class="col-user">
            <div class="uname">@<?= htmlspecialchars($s['username']) ?></div>
            <div class="uemail"><?= htmlspecialchars($s['email']) ?></div>
          </div>

          <!-- Detalles concesionario -->
          <div class="col-details">
            <div class="det">
              <i class="ti ti-building"></i>
              <?= htmlspecialchars($s['nom_concessionaire'] ?? '—') ?>
            </div>
            <div class="det">
              <i class="ti ti-phone"></i>
              <?= htmlspecialchars($s['telephone'] ?? '—') ?>
            </div>
            <div class="det">
              <i class="ti ti-map-pin"></i>
              <?= htmlspecialchars($s['localisation'] ?? '—') ?>
            </div>
            <div class="det">
              <i class="ti ti-clock"></i>
              <?= ago($s['created_at']) ?>
            </div>
          </div>

          <!-- Estado + acciones -->
          <div class="col-actions">

            <?php if ($s['statut'] === 'pendiente'): ?>
              <span class="badge b-pending">Pendiente</span>
              <div class="btn-row">
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('¿Aprobar esta solicitud?')">
                  <input type="hidden" name="action"      value="aprobar">
                  <input type="hidden" name="sol_id"      value="<?= $s['id'] ?>">
                  <input type="hidden" name="current_tab" value="solicitudes">
                  <button type="submit" class="btn-a ba-approve">
                    <i class="ti ti-check"></i> Aprobar
                  </button>
                </form>
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('¿Rechazar esta solicitud?')">
                  <input type="hidden" name="action"      value="rechazar">
                  <input type="hidden" name="sol_id"      value="<?= $s['id'] ?>">
                  <input type="hidden" name="current_tab" value="solicitudes">
                  <button type="submit" class="btn-a ba-reject">
                    <i class="ti ti-x"></i> Rechazar
                  </button>
                </form>
              </div>

            <?php elseif ($s['statut'] === 'aprobado'): ?>
              <span class="badge b-approved">Aprobado</span>
              <form method="POST" onsubmit="return confirm('¿Revocar el acceso de dealer?')">
                <input type="hidden" name="action"      value="revocar">
                <input type="hidden" name="sol_id"      value="<?= $s['id'] ?>">
                <input type="hidden" name="current_tab" value="solicitudes">
                <button type="submit" class="btn-a ba-revoke">
                  <i class="ti ti-ban"></i> Revocar
                </button>
              </form>

            <?php else: ?>
              <span class="badge b-rejected">Rechazado</span>
              <form method="POST" onsubmit="return confirm('¿Aprobar igualmente?')">
                <input type="hidden" name="action"      value="aprobar">
                <input type="hidden" name="sol_id"      value="<?= $s['id'] ?>">
                <input type="hidden" name="current_tab" value="solicitudes">
                <button type="submit" class="btn-a ba-approve">
                  <i class="ti ti-check"></i> Aprobar
                </button>
              </form>
            <?php endif; ?>

          </div>
        </div>

        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         TAB: GESTIÓN DE CUENTAS
    ═══════════════════════════════════════════════ -->
    <div class="tab-panel<?= $tab === 'cuentas' ? ' active' : '' ?>" id="tab-cuentas">

      <div class="sec-header">
        <div>
          <div class="sec-title">Gestión de cuentas</div>
          <div class="sec-count" id="user-count"><?= count($users) ?> usuarios</div>
        </div>
      </div>

      <div class="filter-bar">
        <button class="chip active" data-filter2="all">Todos</button>
        <button class="chip" data-filter2="client">Clientes</button>
        <button class="chip" data-filter2="dealer">Dealers</button>
        <button class="chip" data-filter2="admin">Admins</button>
      </div>

      <div id="user-rows">

        <?php if (empty($users)): ?>
          <div class="empty-state">
            <i class="ti ti-user-off"></i>
            Sin usuarios registrados.
          </div>

        <?php else: foreach ($users as $u):
          $bg      = abg($u['username'] ?? '?');
          $in      = ini($u['username'] ?? '?');
          $is_admin  = ($u['role'] ?? '') === 'admin';
          $is_banned = ($u['status'] ?? 'active') === 'banned';
        ?>

        <div class="row-card" data-role="<?= htmlspecialchars($u['role']) ?>">

          <!-- Avatar -->
          <div class="ava"
               style="background:<?= $bg ?>22; color:<?= $bg ?>; border:1px solid <?= $bg ?>44;">
            <?= $in ?>
          </div>

          <!-- Usuario -->
          <div class="col-user">
            <div class="uname">@<?= htmlspecialchars($u['username']) ?></div>
            <div class="uemail"><?= htmlspecialchars($u['email']) ?></div>
          </div>

          <!-- Detalles -->
          <div class="col-details">
            <div class="det">
              <i class="ti ti-id-badge"></i>
              <span class="badge b-<?= htmlspecialchars($u['role']) ?>">
                <?= ucfirst($u['role']) ?>
              </span>
            </div>
            <div class="det">
              <i class="ti ti-activity"></i>
              <span class="badge <?= $is_banned ? 'b-banned' : 'b-active' ?>">
                <?= $is_banned ? 'Baneado' : 'Activo' ?>
              </span>
            </div>
            <div class="det">
              <i class="ti ti-calendar"></i>
              <?= date('d/m/Y', strtotime($u['created_at'])) ?>
            </div>
            <div class="det">
              <i class="ti ti-hash"></i>
              ID: <?= $u['id'] ?>
            </div>
          </div>

          <!-- Acciones -->
          <div class="col-actions">
            <div class="btn-row">

              <a href="Profil.php?id=<?= $u['id'] ?>" class="btn-a ba-view">
                <i class="ti ti-eye"></i> Ver
              </a>

              <?php if ($is_banned): ?>
                <form method="POST" style="display:inline">
                  <input type="hidden" name="action"      value="unban_user">
                  <input type="hidden" name="user_id"     value="<?= $u['id'] ?>">
                  <input type="hidden" name="current_tab" value="cuentas">
                  <button type="submit" class="btn-a ba-unban">
                    <i class="ti ti-user-check"></i> Desbanear
                  </button>
                </form>

              <?php elseif (!$is_admin): ?>
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('¿Banear a <?= htmlspecialchars($u['username'], ENT_QUOTES) ?>?')">
                  <input type="hidden" name="action"      value="ban_user">
                  <input type="hidden" name="user_id"     value="<?= $u['id'] ?>">
                  <input type="hidden" name="current_tab" value="cuentas">
                  <button type="submit" class="btn-a ba-ban">
                    <i class="ti ti-user-off"></i> Banear
                  </button>
                </form>
              <?php endif; ?>

              <?php if (!$is_admin): ?>
                <form method="POST" style="display:inline"
                      onsubmit="return confirm('¿Eliminar cuenta de <?= htmlspecialchars($u['username'], ENT_QUOTES) ?>?\nEsta acción es irreversible.')">
                  <input type="hidden" name="action"      value="del_user">
                  <input type="hidden" name="user_id"     value="<?= $u['id'] ?>">
                  <input type="hidden" name="current_tab" value="cuentas">
                  <button type="submit" class="btn-a ba-del">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              <?php endif; ?>

            </div>
          </div>
        </div>

        <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         TAB: ESTADÍSTICAS
    ═══════════════════════════════════════════════ -->
    <div class="tab-panel<?= $tab === 'estadisticas' ? ' active' : '' ?>" id="tab-estadisticas">

      <div class="sec-header">
        <div class="sec-title">Estadísticas</div>
      </div>

      <!-- Tarjetas de métricas -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon" style="color:var(--blue)">
            <i class="ti ti-users"></i>
          </div>
          <div class="stat-label">Usuarios registrados</div>
          <div class="stat-value" style="color:var(--blue)"><?= $n_users ?></div>
          <div class="stat-sub">Total acumulado</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="color:var(--accent)">
            <i class="ti ti-certificate"></i>
          </div>
          <div class="stat-label">Dealers activos</div>
          <div class="stat-value" style="color:var(--accent)"><?= $n_dealers ?></div>
          <div class="stat-sub">Con panel aprobado</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="color:var(--green)">
            <i class="ti ti-car"></i>
          </div>
          <div class="stat-label">Coches publicados</div>
          <div class="stat-value" style="color:var(--green)"><?= $n_coches ?></div>
          <div class="stat-sub">Anuncios en plataforma</div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="color:<?= $n_pend > 0 ? 'var(--amber)' : 'var(--txt3)' ?>">
            <i class="ti ti-clock-hour-4"></i>
          </div>
          <div class="stat-label">Solicitudes pendientes</div>
          <div class="stat-value" style="color:<?= $n_pend > 0 ? 'var(--amber)' : 'var(--txt3)' ?>">
            <?= $n_pend ?>
          </div>
          <div class="stat-sub"><?= $n_pend > 0 ? 'Requieren atención' : 'Todo al día' ?></div>
        </div>
      </div>

      <!-- Barra de distribución de roles -->
      <div class="ratio-card">
        <div class="ratio-head">
          <i class="ti ti-chart-donut"></i> Distribución de roles
        </div>
        <?php
          $n_clients = max(0, $n_users - $n_dealers);
          $pct_d = $n_users > 0 ? round($n_dealers / $n_users * 100) : 0;
          $pct_c = 100 - $pct_d;
        ?>
        <div class="ratio-bar">
          <div style="flex:<?= $pct_c ?>; background:var(--blue); opacity:.75;"></div>
          <div style="flex:<?= $pct_d ?>; background:var(--accent); opacity:.85;"></div>
        </div>
        <div class="ratio-legend">
          <div>
            <span class="ratio-dot" style="background:var(--blue);opacity:.75;"></span>
            Clientes — <?= $n_clients ?> (<?= $pct_c ?>%)
          </div>
          <div>
            <span class="ratio-dot" style="background:var(--accent);opacity:.85;"></span>
            Dealers — <?= $n_dealers ?> (<?= $pct_d ?>%)
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════════════════════════════════
         TAB: CONFIGURACIÓN
    ═══════════════════════════════════════════════ -->
    <div class="tab-panel<?= $tab === 'configuracion' ? ' active' : '' ?>" id="tab-configuracion">

      <div class="sec-header">
        <div class="sec-title">Configuración</div>
      </div>

      <div class="settings-section">

        <!-- Info del sitio -->
        <div class="settings-card">
          <div class="settings-head">
            <i class="ti ti-world"></i> Información del sitio
          </div>
          <div class="form-group">
            <label class="form-label">Nombre del sitio</label>
            <input class="form-input" type="text" value="AutoPost" placeholder="Nombre del sitio">
          </div>
          <div class="form-group">
            <label class="form-label">Localización por defecto</label>
            <input class="form-input" type="text" value="Tánger, MA" placeholder="Ciudad, País">
          </div>
          <div class="form-group">
            <label class="form-label">Email de contacto</label>
            <input class="form-input" type="email" placeholder="admin@autopost.com">
          </div>
          <button class="btn-save">Guardar cambios</button>
        </div>

        <!-- Control de acceso -->
        <div class="settings-card">
          <div class="settings-head">
            <i class="ti ti-shield"></i> Control de acceso
          </div>
          <div class="form-toggle">
            <div>
              <div class="toggle-label">Registro de nuevos usuarios</div>
              <div class="toggle-sub">Permite que nuevos usuarios creen una cuenta</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="form-toggle">
            <div>
              <div class="toggle-label">Solicitudes de dealer</div>
              <div class="toggle-sub">Habilita el botón "Devenir Dealer" en perfiles</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="form-toggle">
            <div>
              <div class="toggle-label">Publicación de anuncios</div>
              <div class="toggle-sub">Permite a dealers publicar nuevos coches</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="form-toggle">
            <div>
              <div class="toggle-label">Modo mantenimiento</div>
              <div class="toggle-sub">Redirige a todos los usuarios a una página de aviso</div>
            </div>
            <label class="toggle-switch">
              <input type="checkbox">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

      </div>
    </div>

  </div><!-- /.admin-content -->
</div><!-- /.admin-wrapper -->

<script>
/* ─── Cambio de tabs ─────────────────────────────────── */
const tabs   = document.querySelectorAll('.admin-tab');
const panels = document.querySelectorAll('.tab-panel');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    const target = tab.dataset.target;
    tabs.forEach(t  => t.classList.remove('active'));
    panels.forEach(p => p.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById('tab-' + target)?.classList.add('active');
    history.replaceState(null, '', '?tab=' + target);
  });
});

/* ─── Filtros — Solicitudes ──────────────────────────── */
document.querySelectorAll('.chip[data-filter]').forEach(chip => {
  chip.addEventListener('click', () => {
    const f = chip.dataset.filter;
    document.querySelectorAll('.chip[data-filter]').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');

    let visible = 0;
    document.querySelectorAll('#sol-rows .row-card').forEach(row => {
      const show = f === 'all' || row.dataset.status === f;
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    document.getElementById('sol-count').textContent = visible + ' solicitudes';
  });
});

/* ─── Filtros — Usuarios ─────────────────────────────── */
document.querySelectorAll('.chip[data-filter2]').forEach(chip => {
  chip.addEventListener('click', () => {
    const f = chip.dataset.filter2;
    document.querySelectorAll('.chip[data-filter2]').forEach(c => c.classList.remove('active'));
    chip.classList.add('active');

    let visible = 0;
    document.querySelectorAll('#user-rows .row-card').forEach(row => {
      const show = f === 'all' || row.dataset.role === f;
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    document.getElementById('user-count').textContent = visible + ' usuarios';
  });
});
</script>
</body>
</html>