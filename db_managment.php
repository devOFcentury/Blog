<?php

function search_posts(string $search, int $limit = 10, int $offset = 0)
{
     global $pdo;

     $search = trim($search);
     $search = stripslashes($search);
     $search = htmlspecialchars($search);

     $response = $pdo->prepare("SELECT 
          posts.id, title, SUBSTR(content, 1, 100) AS content, user_id, DATE(posts.created_at) creation_date, users.pseudo
          FROM posts
          INNER JOIN users
               ON posts.user_id = users.id
               WHERE title LIKE :title
          ORDER BY id DESC 
          LIMIT :limit OFFSET :offset
     ");

     $response->bindValue(':title', '%' . $search . '%', PDO::PARAM_STR);
     $response->bindParam(':limit', $limit, PDO::PARAM_INT);
     $response->bindParam(':offset', $offset, PDO::PARAM_INT);
     $response->execute();

     return $response->fetchAll();
}

function add_post(int $user_id, array $posts, array $categories_id)
{
     global $pdo;

     if (count($posts) == 0 || count($categories_id) == 0) {
          throw new Error('Remplissez correctement les arguments');
     }
     // insert post
     $query = $pdo->prepare("INSERT INTO posts(title, content, user_id, created_at, updated_at)
          VALUES(:title, :content, :user_id, NOW(), NOW())");
     $query->execute(array(
          'title' => $posts["title"],
          'content' => $posts["content"],
          'user_id' => $user_id
     ));
     $post_id = $pdo->lastInsertId();

     // insert in pivot table
     add_link_posts_categories($categories_id, $post_id);

     return 1;
}

function delete_myPost(int $user_id, int $post_id)
{
     global $pdo;

     // we retrieve user_id of post
     $queryCheck = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
     $queryCheck->execute(array($post_id));
     $user_id_check = $queryCheck->fetch();


     if ($user_id_check['user_id'] != $user_id) {
          throw new Error("Vous ne pouvez pas supprimer ce post car il ne vous appartient pas");
     }

     $queryCheck->closeCursor();


     $query = $pdo->prepare("DELETE FROM posts WHERE id = ?");
     $query->execute(array($post_id));
     return 1;
}

function update_myPost(int $user_id, array $posts, int $post_id)
{
     global $pdo;

     // we retrieve user_id of post
     $queryCheck = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
     $queryCheck->execute(array($post_id));
     $user_id_check = $queryCheck->fetch();


     if ($user_id_check['user_id'] != $user_id) {
          throw new Error("Vous ne pouvez pas modifier ce post car il ne vous appartient pas");
     }

     $queryCheck->closeCursor();


     // make my dynamic query
     $out = "UPDATE posts SET ";
     $out1 = '';

     foreach ($posts as $column => $value) {
          $out1 .= $column . " = :$column, ";
     }

     $out1 .= "updated_at=NOW(), ";

     $out2 = "WHERE id = :id";
     $output = $out . rtrim($out1, ", ") . " " . $out2;

     $finalData = array(...$posts, 'id' => $post_id);

     $query = $pdo->prepare($output);
     $query->execute($finalData);
     return 1;
}

function get_myPosts(int $user_id)
{
     global $pdo;

     $query = $pdo->query("SELECT * from posts WHERE user_id = {$user_id}");

     $my_posts = $query->fetchAll();

     return $my_posts;
}

function get_post(int $id)
{
     global $pdo;

     $response = $pdo->prepare("SELECT 
          p.id, title, content, user_id, 
          DAY(p.created_at) AS day_creation, MONTH(p.created_at) AS month_creation, YEAR(p.created_at) year_creation,
          HOUR(p.created_at) AS hour_creation, MINUTE(p.created_at) AS minute_creation, SECOND(p.created_at) AS second_creation,
          DAY(p.updated_at) as day_update, MONTH(p.updated_at) AS month_update, YEAR(p.updated_at) year_update,
          HOUR(p.updated_at) AS hour_update, MINUTE(p.updated_at) AS minute_update, SECOND(p.updated_at) AS second_update,
          u.pseudo
          FROM posts AS p
          INNER JOIN users AS u
               ON p.user_id = u.id
          WHERE p.id = ?
     ");

     $response->execute(array($id));

     return $response->fetch();
}

function get_posts(int $limit = 10, int $offset = 0)
{
     global $pdo;

     $response = $pdo->prepare("SELECT 
               posts.id, SUBSTR(title, 1, 20) AS title, SUBSTR(content, 1, 100) AS content, user_id, DATE(posts.created_at) creation_date, users.pseudo
               FROM posts
               INNER JOIN users
                    ON posts.user_id = users.id
               ORDER BY id DESC 
               LIMIT :limit OFFSET :offset
          ");
     $response->bindParam(':limit', $limit, PDO::PARAM_INT);
     $response->bindParam(':offset', $offset, PDO::PARAM_INT);
     $response->execute();

     return $response->fetchAll();
}

function get_categories()
{
     global $pdo;
     $query = $pdo->query('SELECT * FROM categories');
     return $query->fetchAll();
}

function get_categories_of_a_post(int $post_id) {
     global $pdo;

     $query = $pdo->prepare("SELECT categories.id AS id_categories
          FROM categories
          INNER JOIN posts_categories as pc
               ON categories.id = pc.category_id
          INNER JOIN posts
               ON pc.post_id = posts.id
          WHERE posts.id = :id_post;
     ");
     $query->execute(array(
          'id_post' => $post_id,
     ));

     $response = $query->fetchAll();
     $results = array_column($response, 'id_categories');

     return $results;
}

function delete_link_posts_categories(int $post_id)
{
     global $pdo;

     $query = $pdo->prepare("DELETE FROM posts_categories WHERE post_id = ?");

     $query->execute(array($post_id));


     return 1;
}


function add_link_posts_categories(array $categories_id, int $post_id)
{
     global $pdo;

     if (count($categories_id) == 0) {
          throw new Error('Remplissez correctement les arguments');
     }

     foreach ($categories_id as $category_id) {

          $query = $pdo->prepare("INSERT INTO posts_categories(post_id, category_id)
               VALUES(:post_id, :category_id)");
          $query->execute(array(
               'post_id' => $post_id,
               'category_id' => $category_id
          ));
     }

     return 1;
}


function update_link_posts_categories(array $new_categories_id, array $old_categories_id, int $post_id)
{
     global $pdo;

     if (count($old_categories_id) == 0 || count($new_categories_id) == 0) {
          throw new Error('Remplissez correctement les arguments');
     }

     // delete in posts_categories
     foreach ($old_categories_id as $old_category_id) {

          $query = $pdo->prepare("DELETE FROM posts_categories WHERE post_id = :post_id AND category_id = :category_id");
          $query->execute(array(
               'post_id' => $post_id,
               'category_id' => $old_category_id
          ));
     }

     add_link_posts_categories($new_categories_id, $post_id);

     return 1;
}

function like_post(int $user_id, int $post_id)
{
     global $pdo;

     $query = $pdo->prepare("INSERT INTO users_like_posts(user_id, post_id)
          VALUES(:user_id, :post_id)");
     $query->execute(array(
          'user_id' => $user_id,
          'post_id' => $post_id,
     ));

     return 1;
}

function dislike_post(int $user_id, int $post_id)
{
     global $pdo;

     $query = $pdo->prepare("DELETE FROM users_like_posts where user_id = :user_id AND post_id = :post_id");
     $query->execute(array(
          'user_id' => $user_id,
          'post_id' => $post_id,
     ));

     return 1;
}

function add_comments(array $comments)
{
     global $pdo;

     $query = $pdo->prepare("INSERT INTO comments(email, title, content, user_id, post_id, created_at, updated_at)
          VALUES(:email, :title, :content, :user_id, :post_id, NOW(), NOW())");
     $query->execute(array(
          'email' => $comments['email'],
          'title' => $comments['title'],
          'content' => $comments['content'],
          'user_id' => $comments['user_id'],
          'post_id' => $comments['post_id'],
     ));

     return 1;
}

function delete_comments(int $comment_id, int $user_id)
{
     global $pdo;

     $query = $pdo->prepare("DELETE FROM comments WHERE id = :id AND user_id = :user_id");
     $query->execute(array(
          'id' => $comment_id,
          'user_id' => $user_id,
     ));

     return 1;
}

function show_comments($post_id, int $limit = 10, int $offset = 0)
{
     global $pdo;
     $query = $pdo->prepare("SELECT 
          comments.email, title, content, post_id, user_id, comments.created_at, comments.updated_at, users.pseudo
          FROM comments 
          INNER JOIN users
          ON comments.user_id = users.id
          WHERE post_id= :post_id
          ORDER BY comments.id DESC 
          LIMIT :limit OFFSET :offset
     ");
     $query->bindParam(':limit', $limit, PDO::PARAM_INT);
     $query->bindParam(':offset', $offset, PDO::PARAM_INT);
     $query->bindParam(':post_id', $post_id, PDO::PARAM_INT);
     $query->execute();

     return $query->fetchAll();
}

function update_comments($comments, $comment_id)
{
     global $pdo;

     // make my dynamic query
     $out = "UPDATE comments SET ";
     $out1 = '';

     foreach ($comments as $column => $value) {
          $out1 .= $column . " = :$column, ";
     }

     $out1 .=  "updated_at = NOW(), ";

     $out2 = "WHERE id = :id";
     $output = $out . rtrim($out1, ", ") . " " . $out2;

     $finalData = array(...$comments, 'id' => $comment_id);

     $query = $pdo->prepare($output);
     $query->execute($finalData);
     return 1;
}
