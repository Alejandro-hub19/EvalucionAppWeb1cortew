<?php
declare(strict_types=1);

function iniciarSesionSegura(): void
{
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Strict',
        'secure' => false
    ]);

    session_start();
}

function requiereLogin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /login', true, 302);
        exit;
    }
}