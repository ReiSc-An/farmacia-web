<?php
session_start();

$cfg = require __DIR__ . '/../config/database.php';
try {
    $pdo = new PDO("mysql:host={$cfg['host']};dbname={$cfg['bd']};charset=utf8mb4", $cfg['user'], $cfg['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('No se pudo conectar a la base de datos. Revisa config/database.php');
}

function h($t) { return htmlspecialchars((string)$t, ENT_QUOTES, 'UTF-8'); }
function precio($n) { return 'Bs ' . number_format((float)$n, 2); }
function carrito_cantidad() { return array_sum($_SESSION['carrito'] ?? []); }

// Devuelve los productos del carrito con cantidad y subtotal
function carrito_items(PDO $pdo) {
    $ids = array_keys($_SESSION['carrito'] ?? []);
    if (!$ids) return [];
    $in = implode(',', array_fill(0, count($ids), '?'));
    $s = $pdo->prepare("SELECT * FROM productos WHERE id IN ($in) AND stock > 0");
    $s->execute($ids);
    $items = [];
    foreach ($s->fetchAll() as $p) {
        $p['cantidad'] = min($_SESSION['carrito'][$p['id']], $p['stock']);
        $p['subtotal'] = $p['cantidad'] * $p['precio'];
        $items[] = $p;
    }
    return $items;
}
function carrito_total($items) { return array_sum(array_column($items, 'subtotal')); }
