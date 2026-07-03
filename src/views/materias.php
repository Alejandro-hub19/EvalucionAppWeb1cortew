<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Materias</title>
</head>
<body>
    <h1>Listado de materias</h1>

    <form method="POST" action="/logout">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
        <button type="submit">Cerrar sesión</button>
    </form>

    <br>

    <a href="/materias/nueva">Nueva materia</a>

    <br><br>

    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nombre</th>
            <th>Créditos</th>
            <th>Semestre</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($materias as $m): ?>
            <tr>
                <td><?= htmlspecialchars((string)$m['id'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($m['codigo'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($m['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)$m['creditos'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)$m['semestre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <a href="/materias/<?= $m['id'] ?>/editar">Editar</a>

                    <form method="POST" action="/materias/<?= $m['id'] ?>/eliminar" style="display:inline">
                        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">
                        <button type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>