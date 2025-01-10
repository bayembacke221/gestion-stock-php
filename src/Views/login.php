<?php
$base = '/login-registration-with-jwt';
$title = "Connexion - AuthSystem";
?>

<div class="container py-5">
    <div class="auth-form">
        <h2 class="text-center mb-4">Connexion</h2>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
            <div id="loginMessage" class="mt-3"></div>
        </form>
        <div class="text-center mt-3">
            <p>Pas encore de compte ? <a href="<?= $base ?>/register">S'inscrire</a></p>
        </div>
    </div>
</div>