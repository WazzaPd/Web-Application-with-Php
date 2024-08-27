<?php
$pdo = new PDO('mysql:host=localhost;port=3307;dbname=misc', 'username', 'pass');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>