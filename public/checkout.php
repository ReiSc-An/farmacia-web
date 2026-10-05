<?php
require_once __DIR__ . '/../includes/funciones.php';

$items = carrito_items($pdo);
if (!$items) { header('Location: carrito.php'); exit; }

$d = ['nombre' => '', 'telefono' => '', 'email' => '', 'direccion' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($d as $k => $_) $d[$k] = trim($_POST[$k] ?? '');
    if ($d['nombre'] === '' || $d['telefono'] === '' || $d['direccion'] === '') {
        $error = 'Completa nombre, teléfono y dirección.';
    } elseif ($d['email'] !== '' && !filter_var($d['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo no es válido.';
    } else {
        try {
            $pdo->beginTransaction();
            // Verificar stock real antes de vender
            foreach ($items as $i) {
                $s = $pdo->prepare("SELECT stock FROM productos WHERE id = ? FOR UPDATE");
                $s->execute([$i['id']]);
                if ((int)$s->fetchColumn() < $i['cantidad']) throw new Exception("No hay stock suficiente de {$i['nombre']}.");
            }
            $pdo->prepare("INSERT INTO pedidos (nombre, telefono, email, direccion, total) VALUES (?,?,?,?,?)")
                ->execute([$d['nombre'], $d['telefono'], $d['email'] ?: null, $d['direccion'], carrito_total($items)]);
            $pedidoId = (int)$pdo->lastInsertId();
            foreach ($items as $i) {
                $pdo->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)")
                    ->execute([$pedidoId, $i['id'], $i['cantidad'], $i['precio']]);
                $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?")->execute([$i['cantidad'], $i['id']]);
            }
            $pdo->commit();
            unset($_SESSION['carrito']);
            $_SESSION['ultimo_pedido'] = $pedidoId;
            header("Location: pedido_confirmado.php?id=$pedidoId");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    }
}

$titulo = 'Finalizar compra';
require __DIR__ . '/../includes/header.php';
?>
<h1>Finalizar compra</h1>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<div class="dos-col">
  <form class="formulario" method="post">
    <label>Nombre completo <input name="nombre" value="<?= h($d['nombre']) ?>" required></label>
    <label>Teléfono <input name="telefono" value="<?= h($d['telefono']) ?>" required></label>
    <label>Correo (opcional) <input type="email" name="email" value="<?= h($d['email']) ?>"></label>
    <label>Dirección de entrega <input name="direccion" value="<?= h($d['direccion']) ?>" required></label>
    <button class="btn">Confirmar pedido</button>
  </form>
  <aside class="formulario">
    <h2>Resumen</h2>
    <?php foreach ($items as $i): ?>
      <p><?= (int)$i['cantidad'] ?> × <?= h($i['nombre']) ?> <strong><?= precio($i['subtotal']) ?></strong></p>
    <?php endforeach; ?>
    <p class="total">Total: <?= precio(carrito_total($items)) ?></p>
  </aside>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
