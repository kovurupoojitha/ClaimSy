<?php
include 'config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') die('Access denied.');

$lost   = $conn->query("SELECT COUNT(*) AS c FROM lost_items  WHERE status='pending'")    ->fetch_assoc()['c'] ?? 0;
$claims = $conn->query("SELECT COUNT(*) AS c FROM claims      WHERE claim_status='pending'")->fetch_assoc()['c'] ?? 0;
$msgs   = $conn->query("SELECT COUNT(*) AS c FROM contact_messages")                       ->fetch_assoc()['c'] ?? 0;
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{--sidebar-width:220px;--bg:#f5f7fa;--card:#fff;--border:#e0e6ed;}
body{font-family:Inter,"Segoe UI",Arial,sans-serif;background:var(--bg);overflow-x:hidden}

/* sidebar */
#sidebar{width:var(--sidebar-width);position:fixed;inset:0 auto 0 0;background:#1c2534;color:#fff;padding:24px 0;transition:.3s}
#sidebar .nav-link{color:#cbd5e1;padding:10px 22px;font-weight:500}
#sidebar .nav-link:hover,#sidebar .nav-link.active{background:#111827;color:#fff}

/* main */
#main{margin-left:var(--sidebar-width);padding:18px 20px}
.card{background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 3px 8px rgba(0,0,0,.05);padding:0}
.metric{display:flex;align-items:center;gap:8px;padding:10px}
.metric-icon{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1rem;color:#fff}

/* mobile */
@media (max-width:991.98px){
  #sidebar{transform:translateX(-100%)}
  #sidebar.show{transform:none}
  #main{margin-left:0}
}
</style>
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
  <h6 class="text-center mb-3">Lost&nbsp;&&nbsp;Found</h6>
  <ul class="nav flex-column">
    <li class="nav-item"><a href="approve.php"        class="nav-link"><i class="bi bi-check2-square me-2"></i>Approve Lost</a></li>
    <li class="nav-item"><a href="approve_claims.php" class="nav-link"><i class="bi bi-shield-check me-2"></i>Approve Claims</a></li>
    <li class="nav-item"><a href="view_contact.php"   class="nav-link"><i class="bi bi-envelope-paper me-2"></i>Contacts</a></li>
    <li class="nav-item mt-4"><a href="logout.php"    class="nav-link"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
  </ul>
</nav>

<!-- Main -->
<div id="main">
  <button class="btn btn-outline-secondary d-lg-none mb-3" id="toggleBtn"><i class="bi bi-list"></i></button>
  <h5 class="fw-semibold mb-3">Dashboard</h5>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card">
        <div class="metric">
          <div class="metric-icon bg-primary"><i class="bi bi-clipboard-check"></i></div>
          <div><span class="fw-semibold"><?= $lost ?></span><br><small class="text-muted">Pending&nbsp;Lost</small></div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="metric">
          <div class="metric-icon bg-success"><i class="bi bi-shield-check"></i></div>
          <div><span class="fw-semibold"><?= $claims ?></span><br><small class="text-muted">Pending&nbsp;Claims</small></div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card">
        <div class="metric">
          <div class="metric-icon bg-secondary"><i class="bi bi-envelope-paper"></i></div>
          <div><span class="fw-semibold"><?= $msgs ?></span><br><small class="text-muted">Messages</small></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
/* sidebar toggle on mobile */
document.getElementById('toggleBtn')
        .addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('show'));
</script>
</body>
</html>
