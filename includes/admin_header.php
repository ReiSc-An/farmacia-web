<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administración | Farmacia Salud</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="top">
  <a class="logo" href="index.php">Panel de administración</a>
  <nav>
    <a href="producto.php">Agregar producto</a>
    <a href="../index.php">Ver tienda</a>
    <a href="salir.php">Salir</a>
  </nav>
</header>
<main>
<?php if (!empty($_SESSION['msg'])): ?>
  <p class="aviso"><?= h($_SESSION['msg']) ?></p>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>
