<?php
require_once __DIR__ . '/../../includes/admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    try {
        $s = $pdo->prepare("SELECT imagen FROM productos WHERE id = ?");
        $s->execute([$id]);
        $img = $s->fetchColumn();
        $pdo->prepare("DELETE FROM productos WHERE id = ?")->execute([$id]);
        if ($img) @unlink(__DIR__ . '/../uploads/productos/' . $img);
        $_SESSION['msg'] = 'Producto eliminado.';
    } catch (PDOException $e) {
        $_SESSION['msg'] = 'No se puede eliminar: el producto ya tiene ventas. Ponle stock 0 para ocultarlo de la compra.';
    }
}
header('Location: index.php');
