<?php
declare(strict_types=1);

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function verificarCsrf(): void
{
    if (
        empty($_POST['csrf']) ||
        empty($_SESSION['csrf']) ||
        !hash_equals($_SESSION['csrf'], $_POST['csrf'])
    ) {
        http_response_code(403);
        exit('Token CSRF inválido');
    }
}