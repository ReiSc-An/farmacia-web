<?php
require_once __DIR__ . '/../../includes/funciones.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $okUser = hash_equals($cfg['admin_user'] ?? 'admin', $_POST['usuario'] ?? '');
    $okPass = hash_equals($cfg['admin_pass'] ?? 'admin123', $_POST['clave'] ?? '');
    if ($okUser && $okPass) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresar | Farmacia Salud</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<main style="max-width:380px">
  <h1>Ingresar al panel</h1>
  <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
  <form class="formulario" method="post">
    <label>Usuario <input name="usuario" required autofocus></label>
    <label>Contraseña <input type="password" name="clave" required></label>
    <button class="btn">Ingresar</button>
  </form>
</main>
</body>
</html>
