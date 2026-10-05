<article class="tarjeta">
  <img class="foto" src="<?= !empty($p['imagen']) ? 'uploads/productos/' . h($p['imagen']) : 'assets/img/sin-imagen.svg' ?>" alt="<?= h($p['nombre']) ?>" loading="lazy">
  <span class="cat"><?= h($p['categoria']) ?></span>
  <h3><?= h($p['nombre']) ?></h3>
  <p><?= h($p['descripcion']) ?></p>
  <?php if ($p['requiere_receta']): ?><span class="tag receta">Con receta</span><?php endif; ?>
  <div class="pie">
    <strong><?= precio($p['precio']) ?></strong>
    <?php if ($p['stock'] > 0): ?>
      <form method="post" action="carrito.php">
        <input type="hidden" name="accion" value="agregar">
        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
        <input type="hidden" name="volver" value="<?= h($_SERVER['REQUEST_URI']) ?>">
        <button class="btn">Agregar al carrito</button>
      </form>
    <?php else: ?>
      <span class="tag agotado">Agotado</span>
    <?php endif; ?>
  </div>
</article>
