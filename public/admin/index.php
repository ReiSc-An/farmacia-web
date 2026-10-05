<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
$productos = $pdo->query("SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON c.id = p.categoria_id ORDER BY p.id DESC")->fetchAll();
require __DIR__ . '/../../includes/admin_header.php';
?>
<h1>Productos (<?= count($productos) ?>)</h1>
<p><a class="btn" href="producto.php">Agregar producto</a></p>
<div class="tabla-wrap">
  <table>
    <thead><tr><th>Imagen</th><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($productos as $p): ?>
      <tr>
        <td><img class="mini" src="<?= $p['imagen'] ? '../uploads/productos/' . h($p['imagen']) : '../assets/img/sin-imagen.svg' ?>" alt=""></td>
        <td><?= h($p['nombre']) ?></td>
        <td><?= h($p['categoria']) ?></td>
        <td><?= precio($p['precio']) ?></td>
        <td><?= (int)$p['stock'] ?></td>
        <td class="acciones">
          <a href="producto.php?id=<?= (int)$p['id'] ?>">Editar</a>
          <form method="post" action="eliminar.php" onsubmit="return confirm('¿Eliminar este producto?');">
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <button class="link-peligro">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</main></body></html>
