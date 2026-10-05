<?php
require_once __DIR__ . '/../includes/funciones.php';
$titulo = 'Inicio';
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
$destacados = $pdo->query("SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON c.id = p.categoria_id WHERE p.stock > 0 ORDER BY p.id DESC LIMIT 4")->fetchAll();
require __DIR__ . '/../includes/header.php';
?>
<section class="hero">
  <h1>Tu farmacia, ahora en línea</h1>
  <p>Busca tus medicamentos, agrégalos al carrito y recógelos o pídelos a domicilio.</p>
  <a class="btn" href="catalogo.php">Ver catálogo</a>
</section>

<h2>Categorías</h2>
<div class="chips">
  <?php foreach ($categorias as $c): ?>
    <a href="catalogo.php?categoria=<?= (int)$c['id'] ?>"><?= h($c['nombre']) ?></a>
  <?php endforeach; ?>
</div>

<h2>Productos recientes</h2>
<div class="grid">
  <?php foreach ($destacados as $p) include __DIR__ . '/../includes/tarjeta_producto.php'; ?>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
