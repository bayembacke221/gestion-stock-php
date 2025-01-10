<?php
$base = '/login-registration-with-jwt';
$title = "Accueil - AuthSystem";
?>

<div class="hero-section d-flex align-items-center">
    <div class="container text-center">
        <h1 class="display-4 mb-4">Bienvenue sur AuthSystem</h1>
        <p class="lead mb-4">Un système d'authentification sécurisé pour votre application</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?= $base ?>/register" class="btn btn-primary btn-lg">S'inscrire</a>
            <a href="<?= $base ?>/login" class="btn btn-outline-light btn-lg">Se connecter</a>
        </div>
    </div>
</div>