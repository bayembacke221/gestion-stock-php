<?php
$base = '/login-registration-with-jwt';
$title = "Inscription - AuthSystem";
?>

<div class="container py-5">
    <div class="auth-form">
        <h2 class="text-center mb-4">Inscription</h2>
        <form id="registerForm">
            <div class="mb-3">
                <label for="name" class="form-label">Nom Prenom</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
            <div id="registerMessage" class="mt-3"></div>
        </form>
        <div class="text-center mt-3">
            <p>Déjà inscrit ? <a href="<?= $base ?>/login">Se connecter</a></p>
        </div>
    </div>
</div>
