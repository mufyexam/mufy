<?php
    require_once 'config/index.php';

    try {
        $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", "$dbUsername", "$dbPassword");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }