<?php
$token = $_SESSION['jwt_token'] ?? null;

$categories = $categories ?? [];
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

<div class="profile-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="mb-3"><i class="fas fa-tags me-2"></i>Gestion des catégories</h1>
            <p class="mb-0">Gérez les catégories de vos produits</p>
        </div>
        <div class="col-auto">
            <button class="btn btn-light" onclick="showAddCategoryForm()">
                <i class="fas fa-plus me-2"></i>Nouvelle Catégorie
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Nombre de produits</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light p-2 rounded me-3">
                                    <i class="fas fa-folder text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($category['name']) ?></h6>
                                </div>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($category['description']) ?></td>
                        <td><?= $category['userId'] ?> produits</td>
                        <td>
                            <button class="btn btn-sm btn-light me-2" onclick="editCategory(<?= $category['id'] ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-light" onclick="deleteCategory(<?= $category['id'] ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($categories)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">Aucune catégorie disponible</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>