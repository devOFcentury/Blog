<?php
require_once './db_config/db.php';
require_once './db_managment.php';

// On détermine sur quelle page on se trouve
if (isset($_GET['page']) && !empty($_GET['page'])) {
     $currentPage = (int) strip_tags($_GET['page']);
} else {
     $currentPage = 1;
}


// On détermine le nombre d'articles par page
define('PER_PAGE', 10);


if (isset($_GET['search']) and !empty($_GET['search'])) {
     // On détermine le nombre total de posts
     $query = $pdo->prepare("SELECT COUNT(*) AS nb_posts 
          FROM posts
          WHERE title LIKE :title
     ");
     $query->bindValue(':title', '%' . $_GET['search'] . '%', PDO::PARAM_STR);
     $query->execute();
     $results = $query->fetch();
     $nb_posts = (int) $results['nb_posts'];

     // le nombre total de page
     $pages = ceil($nb_posts / PER_PAGE);

     if (isset($_GET['page']) && !empty($_GET['page'])) {
          // on détermine à partir de quel nombre on récupère les posts
          $offset = (((int) strip_tags($_GET['page'])) - 1) * PER_PAGE;
          try {
               $posts = search_posts($_GET['search'], PER_PAGE, $offset);
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     } else {
          try {
               $posts = search_posts($_GET['search']);
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     }
} else {
     // On détermine le nombre total de posts
     $query = $pdo->query("SELECT COUNT(*) AS nb_posts FROM posts");
     $results = $query->fetch();
     $nb_posts = (int) $results['nb_posts'];

     // le nombre total de page
     $pages = ceil($nb_posts / PER_PAGE);

     if (isset($_GET['page']) && !empty($_GET['page'])) {
          // on détermine à partir de quel nombre on récupère les posts
          $offset = (((int) strip_tags($_GET['page'])) - 1) * PER_PAGE;
          try {
               $posts = get_posts(PER_PAGE, $offset);
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     } else {
          try {
               $posts = get_posts();
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <!-- <link rel="stylesheet" href="style/style.css"> -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <link href="style/style.css?<?php echo filemtime("style/style.css"); ?>" rel="stylesheet" type="text/css" />
</head>

<body>
     <!-- Header -->
     <?php
     if (isset($_SESSION['id'])) {
          include_once('partials/headers/connected-header.php');
     } else {
          include_once('partials/headers/guest-header.php');
     }
     ?>
     <div class="container">
          <div class="row d-md-none">
               <div class="offset-2 col-8 d-flex flex-column flex-md-row justify-content-center">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
                         <input type="search" name="search" class="input" placeholder="Search Here...">
                         <div>
                              <button type="submit" class="button mx-md-3 mb-2">Search</button>
                              <button onclick="event.preventDefault();window.location='index.php'" class="button mx-md-3">Clear</button>
                         </div>
                    </form>
               </div>
          </div>
          <div class="row pt-md-5">

               <div class="col-8">
                    <div class="row">
                         <?php foreach ($posts as $post) : ?>
                              <div class="col-12 col-md-4 py-3 d-flex align-items-stretch">
                                   <div class="card">
                                        <div class="card-header">
                                             <h5 class="card-title"><?= $post['title'] ?></h5>
                                        </div>
                                        <div class="card-body">
                                             <p class="card-text"><?= $post['content'] ?></p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between flex-wrap">
                                             <p class="card-text"><?= (isset($_SESSION['id']) AND ($_SESSION['id'] == $post['user_id'])) ? 'Vous' : $post['pseudo'] ?></p>
                                             <p class="card-text"><?= $post['creation_date'] ?></p>
                                        </div>
                                        <?php if(isset($_SESSION['id']) AND ($_SESSION['id'] == $post['user_id'])): ?>
                                             <a href="./pages/edit_post.php?post_id=<?= $post['id'] ?>" class="modify_button"><i class="fa-solid fa-pen"></i></a>
                                        <?php endif; ?>
                                        <a href="#" class="see_more_button"><i class="fa-solid fa-eye"></i></i></a>
                                   </div>
                              </div>
                         <?php endforeach; ?>

                         <?php if ($nb_posts > 10) : ?>
                              <nav class="offset-2 col-8">
                                   <ul class="pagination">
                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                             <a href="./?page=<?= (isset($_GET['search']) and !empty($_GET['search'])) ? ($currentPage - 1) . '&amp;search=' . $_GET['search'] : $currentPage - 1 ?>" class="page-link">Précédente</a>
                                        </li>
                                        <?php for ($page = 1; $page <= $pages; $page++) : ?>
                                             <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                             <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                  <a href="./?page=<?= (isset($_GET['search']) and !empty($_GET['search'])) ? $page . '&amp;search=' . $_GET['search'] : $page ?>" class="page-link"><?= $page ?></a>
                                             </li>
                                        <?php endfor; ?>
                                        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                             <a href="./?page=<?= (isset($_GET['search']) and !empty($_GET['search'])) ? ($currentPage + 1) . '&amp;search=' . $_GET['search'] : $currentPage + 1 ?>" class="page-link">Suivante</a>
                                        </li>
                                   </ul>
                              </nav>
                         <?php endif;  ?>
                    </div>
               </div>

               <aside class="col-4">
                    <div>
                         <div class="mb-3 text-center d-none d-md-block">
                              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
                                   <input type="search" name="search" class="input mb-2 form-control" placeholder="Search Here...">
                                   <button type="submit" class="btn btn-info mb-2 mb-md-0 mx-md-3">Search</button>
                                   <button onclick="event.preventDefault();window.location='index.php'" class="btn btn-info mb-2 mb-md-0 mx-md-3">Clear</button>
                              </form>
                         </div>
                         <div class="text-md-center mt-3">
                              <a href="./pages/add_post.php" type="button" title="Add a post" class="btn btn-info"><i class="fa-solid fa-circle-plus"></i> POST</a>
                         </div>
                    </div>
               </aside>

          </div>

     </div>


     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>