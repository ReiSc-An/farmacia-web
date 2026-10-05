<?php
require_once __DIR__ . '/../../includes/funciones.php';
unset($_SESSION['admin']);
header('Location: login.php');
