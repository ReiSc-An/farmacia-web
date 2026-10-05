<?php
require_once __DIR__ . '/funciones.php';
if (empty($_SESSION['admin'])) { header('Location: login.php'); exit; }
