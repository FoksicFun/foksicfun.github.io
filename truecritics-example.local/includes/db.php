<?php

$host = 'MySQL-8.4';
$user = 'root';
$password = '';
$database = 'truecritics';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>