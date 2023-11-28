<?php

require dirname(__DIR__) . '../vendor/autoload.php';

// use the factory to create a Faker\Generator instance
$faker = Faker\Factory::create('fr_FR');

require 'db.php';

$posts = [];
$categories = [];
$comments = [];
$users = [];

// clean database
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE posts_categories');
$pdo->exec('TRUNCATE TABLE users_like_posts');
$pdo->exec('TRUNCATE TABLE posts');
$pdo->exec('TRUNCATE TABLE users');
$pdo->exec('TRUNCATE TABLE categories');
$pdo->exec('TRUNCATE TABLE comments');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

echo 'DATEBASES TABLE cleaned successfully ';

// create users
$hashPassword = null;
for ($i=0; $i < 20; $i++) { 
     $hashPassword = password_hash('12345678', PASSWORD_BCRYPT);
     $req = $pdo->prepare("INSERT INTO users(firstname, lastname, pseudo, email, password, created_at, updated_at)
     VALUES(:firstname, :lastname, :pseudo, :email, :password, :created_at, :updated_at)");
     $req->execute(array(
          'firstname' => $faker->firstName(),
          'lastname' => $faker->lastName(),
          'pseudo' => $faker->userName(),
          'email' => $faker->email(),
          'password' => $hashPassword,
          'created_at' => "{$faker->date()} {$faker->time()}",
          'updated_at' => "{$faker->date()} {$faker->time()}"
     ));
     $users[] = $pdo->lastInsertId();
}
echo 'USERS, ';

$categories_array = ['SPORT', 'MANGA', 'ANIME', 'TRAVEL', 'COOK', 'MUSIC', 'DANCE', 'HISTORY', 'MOVIES'];

// create categories
for ($i=0; $i < count($categories_array); $i++) { 
     $req = $pdo->prepare("INSERT INTO categories(categorie, created_at, updated_at)
     VALUES(:categorie, :created_at, :updated_at)");
     $req->execute(array(
          'categorie' => $categories_array[$i],
          'created_at' => "{$faker->date()} {$faker->time()}",
          'updated_at' => "{$faker->date()} {$faker->time()}"
     ));
     $categories[] = $pdo->lastInsertId();
}
echo 'CATEGORIES, ';

// create posts
for ($i=0; $i < 30; $i++) { 
     $req = $pdo->prepare("INSERT INTO posts(title, content, user_id, created_at, updated_at)
     VALUES(:title, :content, :user_id, :created_at, :updated_at)");
     $req->execute(array(
          'title' => $faker->sentence(),
          'content' => $faker->paragraph(rand(3, 15)) ,
          'user_id' => rand(1, 10),
          'created_at' => "{$faker->date()} {$faker->time()}",
          'updated_at' => "{$faker->date()} {$faker->time()}"
     ));
     $posts[] = $pdo->lastInsertId();
}
echo 'POSTS, ';

// create comments
for ($i=0; $i < 10; $i++) { 
     $req = $pdo->prepare("INSERT INTO comments(email, title, content, user_id, post_id, created_at, updated_at)
     VALUES(:email, :title, :content, :user_id, :post_id, :created_at, :updated_at)");
     $req->execute(array(
          'email' => $faker->email(),
          'title' =>$faker->sentence(2),
          'content' =>$faker->paragraph(rand(3, 15)),
          'user_id' => rand(1, 10),
          'post_id' => rand(1, 15),
          'created_at' => "{$faker->date()} {$faker->time()}",
          'updated_at' => "{$faker->date()} {$faker->time()}"
     ));
     $comments[] = $pdo->lastInsertId();
}
echo 'COMMENTS, ';

// link posts with categories
foreach ($posts as $post) {

     $randomCategories = $faker->randomElements($categories, rand(1, 3));

     foreach ($randomCategories as $category) {
          $req = $pdo->prepare("INSERT INTO posts_categories(post_id, category_id)
          VALUES(:post_id, :category_id)");
          $req->execute(array(
               'post_id' => $post,
               'category_id' => $category,
          ));
     }

}
echo 'POSTS_CATEGORIES, ';

// link users with posts
for ($i=0; $i < 10; $i++) {

     $randomPosts = $faker->randomElements($posts, count($posts));

     foreach ($randomPosts as $post) {
          $req = $pdo->prepare("INSERT INTO users_like_posts(user_id, post_id)
          VALUES(:user_id, :post_id)");
          $req->execute(array(
               'user_id' => $users[$i],
               'post_id' => $post,
          ));
     }
}
echo 'USERS_LIKE_POSTS, were created successfully';