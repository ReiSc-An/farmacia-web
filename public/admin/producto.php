<?php
require_once __DIR__ . '/../../includes/admin_auth.php';

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$p = ['nombre' => '', 'categoria_id' => '', 'descripcion' => '', 'precio' => '', 'stock' => 0, 'requiere_receta' => 0, 'imagen' => null];
if ($id) {
    $s = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $s->execute([$id]);
    $p = $s->fetch();
    if (!$p) die('Producto no encontrado.');
}
$imagenActual = $p['imagen'];
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $p = [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'categoria_id' => (int)($_POST['categoria_id'] ?? 0),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio' => $_POST['precio'] ?? '',
        'stock' => (int)($_POST['stock'] ?? 0),
        'requiere_receta' => isset($_POST['requiere_receta']) ? 1 : 0,
        'imagen' => $imagenActual,
    ];
    if ($p['nombre'] === '' || $p['descripcion'] === '' || !$p['categoria_id'] || !is_numeric($p['precio']) || $p['precio'] < 0 || $p['stock'] < 0) {
        $error = 'Completa todos los campos. El precio y el stock no pueden ser negativos.';
    }

    // Subida de imagen (opcional)
    if (!$error && !empty($_FILES['imagen']['name'])) {
        $f = $_FILES['imagen'];
        $mime = $f['error'] === UPLOAD_ERR_OK ? (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']) : '';
        $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
        if (!$ext || $f['size'] > 2 * 1024 * 1024) {
            $error = 'La imagen debe ser JPG, PNG o WEBP y pesar menos de 2 MB.';
        } else {
            $carpeta = __DIR__ . '/../uploads/productos/';
            if (!is_dir($carpeta)) mkdir($carpeta, 0775, true);
            $nombreArchivo = uniqid('prod_') . '.' . $ext;
            move_uploaded_file($f['tmp_name'], $carpeta . $nombreArchivo);
            if ($imagenActual) @unlink($carpeta . $imagenActual);
            $p['imagen'] = $nombreArchivo;
        }
    }

    if (!$error) {
        $datos = [$p['categoria_id'], $p['nombre'], $p['descripcion'], $p['precio'], $p['stock'], $p['requiere_receta'], $p['imagen']];
        if ($id) {
            $pdo->prepare("UPDATE productos SET categoria_id=?, nombre=?, descripcion=?, precio=?, stock=?, requiere_receta=?, imagen=? WHERE id=?")
                ->execute([...$datos, $id]);
        } else {
            $pdo->prepare("INSERT INTO productos (categoria_id, nombre, descripcion, precio, stock, requiere_receta, imagen) VALUES (?,?,?,?,?,?,?)")
                ->execute($datos);
        }
        $_SESSION['msg'] = $id ? 'Producto actualizado.' : 'Producto agregado.';
        header('Location: index.php');
        exit;
    }
}
require __DIR__ . '/../../includes/admin_header.php';
?>
<h1><?= $id ? 'Editar producto' : 'Agregar producto' ?></h1>
<?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>
<form class="formulario" method="post" enctype="multipart/form-data" style="max-width:520px">
  <input type="hidden" name="id" value="<?= $id ?>">
  <label>Nombre <input name="nombre" value="<?= h($p['nombre']) ?>" required></label>
  <label>Categoría
    <select name="categoria_id" required>
      <option value="">Elige una categoría</option>
      <?php foreach ($categorias as $c): ?>
        <option value="<?= (int)$c['id'] ?>" <?= (int)$p['categoria_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= h($c['nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Descripción <textarea name="descripcion" rows="3" maxlength="255" required><?= h($p['descripcion']) ?></textarea></label>
  <label>Precio (Bs) <input type="number" step="0.01" min="0" name="precio" value="<?= h($p['precio']) ?>" required></label>
  <label>Stock <input type="number" min="0" name="stock" value="<?= (int)$p['stock'] ?>" required></label>
  <label class="check"><input type="checkbox" name="requiere_receta" <?= $p['requiere_receta'] ? 'checked' : '' ?>> Requiere receta</label>
  <?php if ($imagenActual): ?><img class="foto" src="../uploads/productos/<?= h($imagenActual) ?>" alt="Imagen actual"><?php endif; ?>
  <label>Imagen (JPG, PNG o WEBP, máx. 2 MB) <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp"></label>
  <button class="btn">Guardar producto</button>
</form>
</main></body></html>
