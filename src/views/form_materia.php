<?php
$editando = $materia !== null;
$action = $editando ? "/materias/" . $materia['id'] : "/materias";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $editando ? 'Editar' : 'Nueva' ?> materia</title>
</head>
<body>
    <h1><?= $editando ? 'Editar' : 'Nueva' ?> materia</h1>

    <form method="POST" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="csrf" value="<?= csrfToken() ?>">

        <label>Código</label>
        <input 
            type="text" 
            name="codigo" 
            required 
            minlength="6" 
            maxlength="6"
            pattern="[A-Za-z]{3}[0-9]{3}"
            value="<?= htmlspecialchars($materia['codigo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <label>Nombre</label>
        <input 
            type="text" 
            name="nombre" 
            required 
            minlength="5" 
            maxlength="80"
            value="<?= htmlspecialchars($materia['nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <label>Créditos</label>
        <input 
            type="number" 
            name="creditos" 
            required 
            min="1" 
            max="6"
            value="<?= htmlspecialchars((string)($materia['creditos'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <label>Semestre</label>
        <input 
            type="number" 
            name="semestre" 
            required 
            min="1" 
            max="10"
            value="<?= htmlspecialchars((string)($materia['semestre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
        >

        <br><br>

        <button type="submit">Guardar</button>
        <a href="/materias">Cancelar</a>
    </form>
</body>
</html>