<?php
$token = $_SESSION['jwt_token'] ?? null;
$products = $products ?? [];
$totalProducts = $totalProducts ?? 0;
$percentageProductUp = $percentageProductUp ?? 0;
if (!$token) {
    header('Location: /login-registration-with-jwt/login');
    exit;
}

$user = json_decode(base64_decode(explode('.', $token)[1]), true);
if (!$user) {
    header('Location: /login-registration-with-jwt/login');
    exit;
}

$base = '/login-registration-with-jwt';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a237e;
            --secondary-color: #0d47a1;
            --accent-color: #f50057;
            --success-color: #2e7d32;
            --warning-color: #ff6f00;
            --light-gray: #f5f5f5;
            --sidebar-width: 280px;
        }

        body {
            background-color: var(--light-gray);
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            width: var(--sidebar-width);
            min-height: 100vh;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            left: calc(-1 * var(--sidebar-width));
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: all 0.3s;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .toggle-btn {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .toggle-btn:hover {
            background-color: var(--secondary-color);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            border-radius: 0 25px 25px 0;
            margin: 0.2rem 0;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(10px);
        }

        .sidebar .nav-link.active {
            background-color: white;
            color: var(--primary-color);
        }

        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            background: white;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2.5rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .avatar-container {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .table th {
            background-color: rgba(26, 35, 126, 0.05);
            font-weight: 600;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .stock-status {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .stock-critical { background-color: var(--accent-color); }
        .stock-low { background-color: var(--warning-color); }
        .stock-good { background-color: var(--success-color); }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<!--<div id="loadingScreen" class="position-fixed top-0 start-0 w-100 h-100 bg-white d-flex justify-content-center align-items-center" style="z-index: 9999;">-->
<!--    <div class="spinner-border text-primary" role="status">-->
<!--        <span class="visually-hidden">Chargement...</span>-->
<!--    </div>-->
<!--</div>-->

<button id="sidebarToggle" class="toggle-btn">
    <i class="fas fa-bars"></i>
</button>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="text-center py-4">
                <h3><i class="fas fa-box-open me-2"></i>StockMaster</h3>
            </div>
            <div class="profile-section text-center mb-4">
                <div class="avatar-container mb-3">
                    <i class="fas fa-user fa-2x text-primary"></i>
                </div>
                <h6 class="mb-2"><?= $user['data']['username'] ?></h6>
                <span class="badge bg-light text-primary">Gestionnaire Stock</span>
            </div>
            <hr class="my-4">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-chart-pie me-2"></i>Vue d'ensemble</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-boxes me-2"></i>Inventaire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-truck me-2"></i>Livraisons</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-exchange-alt me-2"></i>Mouvements</a>
                </li>
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="#"><i class="fas fa-exclamation-triangle me-2"></i>Alertes</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="#"><i class="fas fa-file-alt me-2"></i>Rapports</a>-->
<!--                </li>-->
<!--                <li class="nav-item">-->
<!--                    <a class="nav-link" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a>-->
<!--                </li>-->
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="#" id="logoutBtn">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Welcome Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="mb-3"><i class="fas fa-chart-pie me-2"></i>Vue d'ensemble</h1>
                        <p class="mb-0">Tableau de bord de gestion des stocks</p>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-light"><i class="fas fa-plus me-2"></i>Nouveau Produit</button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Total Produits</h6>
                                <h3 class="mb-0"><?=$totalProducts?></h3>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+<?=$percentageProductUp?>%</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="fas fa-box fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Stock Faible</h6>
                                <h3 class="mb-0">23</h3>
                                <small class="text-danger"><i class="fas fa-arrow-up me-1"></i>+2.8%</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Livraisons en attente</h6>
                                <h3 class="mb-0">8</h3>
                                <small class="text-success"><i class="fas fa-arrow-down me-1"></i>-1.2%</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="fas fa-truck fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted">Valeur du Stock</h6>
                                <h3 class="mb-0">245k€</h3>
                                <small class="text-success"><i class="fas fa-arrow-up me-1"></i>+5.2%</small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="fas fa-euro-sign fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Stock & Activity -->
            <div class="row g-4">
                <!-- Stock Table -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">État des stocks</h5>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary">Exporter</button>
                                    <button class="btn btn-sm btn-primary">+ Ajouter</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Catégorie</th>
                                        <th>Stock</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($products as $product):
                                        $stockPercentage = ($product['min_stock'] / $product['max_stock']) * 100;

                                        // Détermination du statut et de la classe CSS
                                        $statusClass = 'bg-success';
                                        $statusLabel = 'Normal';
                                        $stockStatus = 'stock-good';

                                        if ($stockPercentage <= 25) {
                                            $statusClass = 'bg-danger';
                                            $statusLabel = 'Critique';
                                            $stockStatus = 'stock-critical';
                                        } elseif ($stockPercentage <= 50) {
                                            $statusClass = 'bg-warning';
                                            $statusLabel = 'Faible';
                                            $stockStatus = 'stock-low';
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light p-2 rounded me-3">
                                                        <i class="fas fa-box text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($product['name']) ?></h6>
                                                        <small class="text-muted">#<?= htmlspecialchars($product['barcode']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($product['category_id']) ?></td>
                                            <td>
                                                <div class="progress" style="height: 5px;">
                                                    <div class="progress-bar <?= $statusClass ?>" style="width: <?= $stockPercentage ?>%"></div>
                                                </div>
                                                <small class="text-muted"><?= htmlspecialchars($product['min_stock']) ?> <?= htmlspecialchars($product['unit']) ?></small>
                                            </td>
                                            <td><span class="stock-status <?= $stockStatus ?>"></span><?= $statusLabel ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-light me-2" onclick="editProduct('<?= $product['id'] ?>','<?= $user['data']['id'] ?>')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-light" onclick="viewProduct('<?= $product['id'] ?>')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if (empty($products)): ?>
                                    <div class="text-center py-4">
                                        <p class="text-muted">Aucun produit disponible</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Alerts -->
                <div class="col-lg-4">
                    <!-- Stock Alerts -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Alertes Stock</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>
                                    <strong>Stock critique :</strong> Casque BT-500
                                </div>
                            </div>
                            <div class="alert alert-warning d-flex align-items-center mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>Stock faible :</strong> Smartphone Y20
                                </div>
                            </div>
                            <div class="alert alert-info d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Commande à prévoir :</strong> Laptop Pro X1
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Activités Récentes</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="d-flex mb-3">
                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-plus text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Stock mis à jour</h6>
                                        <p class="text-muted small mb-0">Laptop Pro X1 (+15 unités)</p>
                                        <small class="text-muted">Il y a 2 heures</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-truck text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Livraison reçue</h6>
                                        <p class="text-muted small mb-0">Commande #LIV-789</p>
                                        <small class="text-muted">Il y a 5 heures</small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="bg-danger bg-opacity-10 p-2 rounded me-3">
                                        <i class="fas fa-exclamation-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Alerte stock</h6>
                                        <p class="text-muted small mb-0">Casque BT-500 (Stock critique)</p>
                                        <small class="text-muted">Il y a 1 jour</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="<?= $base ?>/public/assets/js/dashboard.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Masquer l'écran de chargement
        // document.getElementById('loadingScreen').classList.add('d-none');

        // Gérer la déconnexion
        document.getElementById('logoutBtn').addEventListener('click', () => {
            localStorage.removeItem('token');
            window.location.href = '/login-registration-with-jwt/login';
        });

        // Gérer le toggle du sidebar
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);

            const icon = toggleBtn.querySelector('i');
            if (isCollapsed) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);

        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            toggleSidebar();
        }

        function handleResize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                toggleBtn.style.display = 'block';
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                toggleBtn.style.display = 'none';
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    });
</script>
</body>
</html>