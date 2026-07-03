<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';

iniciarSesionSegura();

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("Content-Security-Policy: default-src 'self'");

$pdo = db();

$ruta = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'];

if ($ruta === '/login' && $metodo === 'GET') {
    require __DIR__ . '/views/login.php';
    exit;
}

if ($ruta === '/login' && $metodo === 'POST') {
    verificarCsrf();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['username'] = $usuario['username'];

        header('Location: /materias');
        exit;
    }

    $error = 'Credenciales incorrectas';
    require __DIR__ . '/views/login.php';
    exit;
}

if ($ruta === '/logout' && $metodo === 'POST') {
    verificarCsrf();

    session_destroy();

    header('Location: /login');
    exit;
}

requiereLogin();

if ($ruta === '/' || $ruta === '/materias') {
    $stmt = $pdo->query("SELECT * FROM materias WHERE activa = TRUE ORDER BY id DESC");
    $materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/views/materias.php';
    exit;
}

if ($ruta === '/materias/nueva' && $metodo === 'GET') {
    $materia = null;

    require __DIR__ . '/views/form_materia.php';
    exit;
}

if ($ruta === '/materias' && $metodo === 'POST') {
    verificarCsrf();

    $codigo = strtoupper(trim($_POST['codigo'] ?? ''));
    $nombre = trim($_POST['nombre'] ?? '');
    $creditos = (int)($_POST['creditos'] ?? 0);
    $semestre = (int)($_POST['semestre'] ?? 0);

    if (!preg_match('/^[A-Z]{3}[0-9]{3}$/', $codigo)) {
        http_response_code(422);
        exit('El código debe tener el formato APW501');
    }

    if (strlen($nombre) < 5 || strlen($nombre) > 80) {
        http_response_code(422);
        exit('El nombre debe tener entre 5 y 80 caracteres');
    }

    if ($creditos < 1 || $creditos > 6) {
        http_response_code(422);
        exit('Los créditos deben estar entre 1 y 6');
    }

    if ($semestre < 1 || $semestre > 10) {
        http_response_code(422);
        exit('El semestre debe estar entre 1 y 10');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO materias (codigo, nombre, creditos, semestre) 
         VALUES (:codigo, :nombre, :creditos, :semestre)"
    );

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'creditos' => $creditos,
        'semestre' => $semestre
    ]);

    header('Location: /materias');
    exit;
}

if (preg_match('#^/materias/(\d+)/editar$#', $ruta, $m) && $metodo === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM materias WHERE id = :id");
    $stmt->execute(['id' => $m[1]]);
    $materia = $stmt->fetch(PDO::FETCH_ASSOC);

    require __DIR__ . '/views/form_materia.php';
    exit;
}

if (preg_match('#^/materias/(\d+)$#', $ruta, $m) && $metodo === 'POST') {
    verificarCsrf();

    $codigo = strtoupper(trim($_POST['codigo'] ?? ''));
    $nombre = trim($_POST['nombre'] ?? '');
    $creditos = (int)($_POST['creditos'] ?? 0);
    $semestre = (int)($_POST['semestre'] ?? 0);

    $stmt = $pdo->prepare(
        "UPDATE materias 
         SET codigo = :codigo, nombre = :nombre, creditos = :creditos, semestre = :semestre 
         WHERE id = :id"
    );

    $stmt->execute([
        'codigo' => $codigo,
        'nombre' => $nombre,
        'creditos' => $creditos,
        'semestre' => $semestre,
        'id' => $m[1]
    ]);

    header('Location: /materias');
    exit;
}

if (preg_match('#^/materias/(\d+)/eliminar$#', $ruta, $m) && $metodo === 'POST') {
    verificarCsrf();

    $stmt = $pdo->prepare("UPDATE materias SET activa = FALSE WHERE id = :id");
    $stmt->execute(['id' => $m[1]]);

    header('Location: /materias');
    exit;
}

http_response_code(404);
echo 'Página no encontrada';