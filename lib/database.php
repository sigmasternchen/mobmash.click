<?php

require_once __DIR__ . "/../credentials.php";

$dsn = "pgsql:host=" . POSTGRES_HOST . ";dbname=" . POSTGRES_DBNAME . ";port=" . POSTGRES_PORT;

$pdo = new PDO($dsn, POSTGRES_USER, POSTGRES_PASSWORD);
