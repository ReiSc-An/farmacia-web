<?php
require_once __DIR__ . '/../includes/funciones.php';
$titulo = 'Contacto';
$d = ['nombre' => '', 'email' => '', 'mensaje' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($d as $k => $_) $d[$k] = trim($_POST[$k] ?? '');
    if ($d['nombre'] === '' || $d['mensaje'] === '' || !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Completa tu nombre, un correo válido y el mensaje.';
    } else {
        $pdo->prepare("INSERT INTO mensajes (nombre, email, mensaje) VALUES (?,?,?)")
            ->execute([$d['nombre'], $d['email'], $d['mensaje']]);
        $_SESSION['msg'] = 'Mensaje enviado. Te responderemos pronto.';
        header('Location: contacto.php');
        exit;
    }
}
require __DIR__ . '/../includes/header.php';
?>
<h1>Contacto</h1>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<div class="dos-col">
  <form class="formulario" method="post">
    <label>Nombre <input name="nombre" value="<?= h($d['nombre']) ?>" required></label>
    <label>Correo <input type="email" name="email" value="<?= h($d['email']) ?>" required></label>
    <label>Mensaje <textarea name="mensaje" rows="5" required><?= h($d['mensaje']) ?></textarea></label>
    <button class="btn">Enviar mensaje</button>
  </form>
  <aside class="formulario">
    <h2>Visítanos</h2>
    <p>Av. Principal #123</p>
    <p>Teléfono: 70000000</p>
    <p>Lunes a sábado, 8:00 a 20:00</p>
  </aside>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
