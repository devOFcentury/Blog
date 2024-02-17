<?php

     require "db.php";

     $pdo->exec('set FOREIGN_KEY_CHECKS = 0');
     $pdo->exec('DROP TABLE IF EXISTS users');
     $pdo->exec('DROP TABLE IF EXISTS posts');
     $pdo->exec('DROP TABLE IF EXISTS comments');
     $pdo->exec('DROP TABLE IF EXISTS categories');
     $pdo->exec('DROP TABLE IF EXISTS posts_categories');
     $pdo->exec('DROP TABLE IF EXISTS users_like_posts');
     $pdo->exec('set FOREIGN_KEY_CHECKS = 1');

     echo 'DATABASES TABLE were deleted successfully';