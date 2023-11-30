<?php

require 'db.php';

// create users table
$pdo->exec("CREATE TABLE users (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     firstname VARCHAR(100) NOT NULL,
     lastname VARCHAR(100) NOT NULL,
     pseudo VARCHAR(100) NOT NULL,
     email VARCHAR(255) UNIQUE NOT NULL,
     ft_image VARCHAR(255),
     password CHAR(255) NOT NULL,
     created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
     updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4");
echo 'Tables: USERS, ';

// create categories table
$pdo->exec("CREATE TABLE categories (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     categorie VARCHAR(100) NOT NULL,
     created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
     updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4");
echo 'CATEGORIES, ';

// create posts table
$pdo->exec("CREATE TABLE posts (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     title VARCHAR(255) NOT NULL,
     content TEXT NOT NULL,
     user_id INT UNSIGNED DEFAULT NULL,
     created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     CONSTRAINT posts_user_id_foreign 
          FOREIGN KEY (user_id) 
          REFERENCES users (id) 
          ON DELETE NO ACTION 
          ON UPDATE NO ACTION
) DEFAULT CHARSET=utf8mb4");
echo 'POSTS, ';

// create comments table
$pdo->exec("CREATE TABLE comments (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     email VARCHAR(255) NOT NULL,
     title VARCHAR(255) NOT NULL,
     content TEXT NOT NULL,
     user_id INT UNSIGNED DEFAULT NULL,
     post_id INT UNSIGNED DEFAULT NULL,
     created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     CONSTRAINT commments_user_id_foreign 
          FOREIGN KEY (user_id) 
          REFERENCES users (id) 
          ON DELETE NO ACTION 
          ON UPDATE NO ACTION,
     CONSTRAINT commments_post_id_foreign 
          FOREIGN KEY (post_id) 
          REFERENCES posts (id) 
          ON DELETE NO ACTION 
          ON UPDATE NO ACTION
) DEFAULT CHARSET=utf8mb4");
echo 'COMMENTS, ';

// create posts_categories table
$pdo->exec("CREATE TABLE posts_categories (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     post_id INT UNSIGNED NOT NULL,
     category_id INT UNSIGNED NOT NULL,
     CONSTRAINT posts_categories_post_id_foreign
          FOREIGN KEY (post_id)
          REFERENCES posts (id)
          ON UPDATE CASCADE
          ON DELETE RESTRICT,
     CONSTRAINT posts_categories_category_id_foreign
          FOREIGN KEY (category_id)
          REFERENCES categories (id)
          ON UPDATE CASCADE
          ON DELETE RESTRICT
) DEFAULT CHARSET=utf8mb4");
echo 'POSTS_CATEGORIES, ';

// create users_like_posts table
$pdo->exec("CREATE TABLE users_like_posts (
     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
     user_id INT UNSIGNED NOT NULL,
     post_id INT UNSIGNED NOT NULL,
     CONSTRAINT users_like_posts_user_id_foreign
          FOREIGN KEY (user_id)
          REFERENCES users (id)
          ON UPDATE CASCADE
          ON DELETE RESTRICT,
     CONSTRAINT users_like_posts_post_id_foreign
          FOREIGN KEY (post_id)
          REFERENCES posts (id)
          ON UPDATE CASCADE
          ON DELETE RESTRICT
) DEFAULT CHARSET=utf8mb4");
echo 'USERS_LIKE_POSTS were created successfully';