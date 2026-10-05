<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($titulo ?? 'Inicio') ?> | Farmacia Salud</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="top">
  <a class="logo" href="index.php">Farmacia Salud</a>
  <form class="buscar" action="catalogo.php">
    <input type="search" name="q" placeholder="Buscar medicamentos" value="<?= h($_GET['q'] ?? '') ?>" aria-label="Buscar productos">
    <button class="btn claro">Buscar</button>
  </form>
  <nav>
    <a href="index.php">Inicio</a>
    <a href="catalogo.php">Catálogo</a>
    <a href="contacto.php">Contacto</a>
    <a href="carrito.php">Carrito (<?= carrito_cantidad() ?>)</a>
  </nav>
</header>
<main>
<?php if (!empty($_SESSION['msg'])): ?>
  <p class="aviso"><?= h($_SESSION['msg']) ?></p>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>
