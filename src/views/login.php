
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Ingreso al sistema</h1>

    <?php if (!empty($error)): ?>
        <p style="color:red"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="POST" action="/login">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">

        <label>Usuario</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>