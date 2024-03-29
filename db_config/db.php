<?php

     session_start();
     $host = 'localhost';
     $db ='blog';
     $user = 'root';
     $password="";
     $port = "3306";
     $charset ="utf8mb4";
     $dsn = "mysql:host={$host};dbname={$db};port={$port};charset={$charset}";
     $options = [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
          \PDO::ATTR_EMULATE_PREPARES => false,
     ];

     try {
          $pdo = new \PDO($dsn, $user, $password, $options);
          // echo 'database connexion established: ';
     } catch (\PDOException $e) {
          throw new \PDOException($e->getMessage(), $e->getCode());
          // echo $e->getMessage();
     }