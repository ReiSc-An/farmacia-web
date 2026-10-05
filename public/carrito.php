<?php
require_once __DIR__ . '/../includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $id = (int)($_POST['id'] ?? 0);
        $s = $pdo->prepare("SELECT stock FROM productos WHERE id = ?");
        $s->execute([$id]);
        $stock = (int)$s->fetchColumn();
        if ($stock > 0) {
            $_SESSION['carrito'][$id] = min(($_SESSION['carrito'][$id] ?? 0) + 1, $stock);
            $_SESSION['msg'] = 'Producto agregado al carrito.';
        }
        $volver = $_POST['volver'] ?? '';
        if (!preg_match('#^/(?!/)#', $volver)) $volver = 'catalogo.php';
        header("Location: $volver");
        exit;
    }

    if (isset($_POST['quitar'])) {
        unset($_SESSION['carrito'][(int)$_POST['quitar']]);
    } elseif ($accion === 'actualizar') {
        foreach (($_POST['cantidad'] ?? []) as $pid => $c) {
            if ((int)$c <= 0) unset($_SESSION['carrito'][(int)$pid]);
            else $_SESSION['carrito'][(int)$pid] = (int)$c;
        }
    }
    header('Location: carrito.php');
    exit;
}

$titulo = 'Carrito';
$items = carrito_items($pdo);
require __DIR__ . '/../includes/header.php';
?>
<h1>Tu carrito</h1>
<?php if (!$items): ?>
  <p class="vacio">Tu carrito está vacío. <a href="catalogo.php">Ir al catálogo</a></p>
<?php else: ?>
  <form method="post">
    <input type="hidden" name="accion" value="actualizar">
    <div class="tabla-wrap">
      <table>
        <thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $i): ?>
          <tr>
            <td><?= h($i['nombre']) ?><?php if ($i['requiere_receta']): ?> <span class="tag receta">Con receta</span><?php endif; ?></td>
            <td><?= precio($i['precio']) ?></td>
            <td><input class="cant" type="number" name="cantidad[<?= (int)$i['id'] ?>]" value="<?= (int)$i['cantidad'] ?>" min="0" max="<?= (int)$i['stock'] ?>"></td>
            <td><?= precio($i['subtotal']) ?></td>
            <td><button class="link-peligro" name="quitar" value="<?= (int)$i['id'] ?>">Quitar</button></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="resumen">
      <button class="btn secundario">Actualizar cantidades</button>
      <p class="total">Total: <?= precio(carrito_total($items)) ?></p>
      <a class="btn" href="checkout.php">Finalizar compra</a>
    </div>
  </form>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
