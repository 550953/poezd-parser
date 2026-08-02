<?php
// Скопируй этот файл в connection.php и заполни реальными значениями
// cp connection.sample.php connection.php

require_once __DIR__ . '/../vendor/autoload.php';

\Sentry\init([
    'dsn' => 'https://0a4b9a9ba8d0bdf36d1c06376470d33b@o4511841982873600.ingest.de.sentry.io/4511842063941712',
    'traces_sample_rate' => 1.0,
    'enable_logs' => true,
]);

$host     = '127.0.0.1'; // адрес сервера
$database = '';           // имя БД (например: provodnik)
$user     = '';           // пользователь MySQL
$password = '';           // пароль MySQL

$host_book     = '127.0.0.1';
$database_book = ''; // имя БД (например: book_info)
$user_book     = '';
$password_book = '';
