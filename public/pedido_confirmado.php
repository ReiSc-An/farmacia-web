<?php
require_once __DIR__ . '/../includes/funciones.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id || ($_SESSION['ultimo_pedido'] ?? 0) !== $id) { header('Location: index.php'); exit; }

$s = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$s->execute([$id]);
$pedido = $s->fetch();
$s = $pdo->prepare("SELECT d.*, p.nombre FROM detalle_pedido d JOIN productos p ON p.id = d.producto_id WHERE d.pedido_id = ?");
$s->execute([$id]);
$detalle = $s->fetchAll();

$titulo = 'Pedido confirmado';
require __DIR__ . '/../includes/header.php';
?>
<h1>¡Gracias por tu compra, <?= h($pedido['nombre']) ?>!</h1>
<p>Tu pedido <strong>#<?= $id ?></strong> fue registrado. Te llamaremos al <?= h($pedido['telefono']) ?> para coordinar la entrega en <?= h($pedido['direccion']) ?>.</p>
<div class="formulario">
  <?php foreach ($detalle as $d): ?>
    <p><?= (int)$d['cantidad'] ?> × <?= h($d['nombre']) ?> <strong><?= precio($d['cantidad'] * $d['precio_unitario']) ?></strong></p>
  <?php endforeach; ?>
  <p class="total">Total: <?= precio($pedido['total']) ?></p>
</div>
<p><a class="btn" href="catalogo.php">Seguir comprando</a></p>
<?php require __DIR__ . '/../includes/footer.php'; ?>
