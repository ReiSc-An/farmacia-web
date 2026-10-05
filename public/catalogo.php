<?php
require_once __DIR__ . '/../includes/funciones.php';
$titulo = 'Catálogo';

$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['categoria'] ?? 0);

$sql = "SELECT p.*, c.nombre AS categoria FROM productos p JOIN categorias c ON c.id = p.categoria_id
        WHERE (p.nombre LIKE ? OR p.descripcion LIKE ?)";
$params = ["%$q%", "%$q%"];
if ($cat) { $sql .= " AND p.categoria_id = ?"; $params[] = $cat; }
$sql .= " ORDER BY p.nombre";
$s = $pdo->prepare($sql);
$s->execute($params);
$productos = $s->fetchAll();
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();

require __DIR__ . '/../includes/header.php';
?>
<h1>Catálogo</h1>
<div class="chips">
  <a href="catalogo.php" class="<?= $cat ? '' : 'activo' ?>">Todos</a>
  <?php foreach ($categorias as $c): ?>
    <a href="catalogo.php?categoria=<?= (int)$c['id'] ?>" class="<?= $cat === (int)$c['id'] ? 'activo' : '' ?>"><?= h($c['nombre']) ?></a>
  <?php endforeach; ?>
</div>

<?php if ($q): ?><p>Resultados para «<?= h($q) ?>»: <?= count($productos) ?></p><?php endif; ?>

<?php if (!$productos): ?>
  <p class="vacio">No encontramos productos. Prueba con otra palabra o <a href="catalogo.php">mira todo el catálogo</a>.</p>
<?php else: ?>
  <div class="grid">
    <?php foreach ($productos as $p) include __DIR__ . '/../includes/tarjeta_producto.php'; ?>
  </div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
