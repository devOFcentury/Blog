<?php
     require_once '../db_config/db.php';
     require_once '../db_managment.php';
     require_once '../checks/check_datas.php';
     if (!isset($_SESSION['id'])) {
          header('location: ../auth/signin.php');
     }
     
     if (!isset($_GET['post_id'])) {
          header('location: ../index.php');               
     }

     // On détermine sur quelle page on se trouve
     if (isset($_GET['page']) && !empty($_GET['page'])) {
          $currentPage = (int) strip_tags($_GET['page']);
     } else {
          $currentPage = 1;
     }


     // On détermine le nombre de commentaire par page
     define('PER_PAGE', 10);

     // On détermine le nombre total de commentaires
     $query = $pdo->prepare("SELECT COUNT(*) AS nb_comments FROM comments WHERE post_id=?");
     $query->execute(array($_GET['post_id']));
     $results = $query->fetch();
     $nb_comments = (int) $results['nb_comments'];

     // le nombre total de page
     $pages = ceil($nb_comments / PER_PAGE);

     if (isset($_GET['page']) && !empty($_GET['page'])) {
          // on détermine à partir de quel nombre on récupère les commentaires
          $offset = (((int) strip_tags($_GET['page'])) - 1) * PER_PAGE;
          try {
               $comments = show_comments($_GET['post_id'], PER_PAGE, $offset);
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     } else {
          try {
               $comments = show_comments($_GET['post_id']);
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }
     }

     
     
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Show Post</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <link rel="stylesheet" href="../style/style.css?<?php echo filemtime("../style/style.css"); ?>" type="text/css">
</head>
<body>
     
     <?php require_once '../partials/headers/connected-header.php' ?>
     <?php
          try {
               $post = get_post($_GET['post_id']);
               // $comments = show_comments($_GET['post_id']);
               $timestamp_creation = mktime(
                    $post['hour_creation'], $post['minute_creation'], $post['second_creation'],
                    $post['month_creation'], $post['day_creation'], $post['year_creation']
     
               );
               
               $getDate_creation = getdate($timestamp_creation);
               $date_creation = $getDate_creation['weekday'] .' '. $getDate_creation['mday'] .' '. $getDate_creation['month'] .' '. $getDate_creation['year'] .' | '. $getDate_creation['hours'] .':'. $getDate_creation['minutes'];
               
               $timestamp_update = mktime(
                    $post['hour_update'], $post['minute_update'], $post['second_update'],
                    $post['month_update'], $post['day_update'], $post['year_update']
     
               );
               
               $getDate_update = getdate($timestamp_update);
               $date_update = $getDate_update['weekday'] .' '. $getDate_update['mday'] .' '. $getDate_update['month'] .' '. $getDate_update['year'].' | '. $getDate_update['hours'] .':'. $getDate_update['minutes'];
     
          } catch (\Throwable $e) {
               echo $e->getMessage();
          }

          $title = $content = $error_post =  '';

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
               if (empty($_POST['title']) || empty($_POST['content'])) {
                    $error_post = 'Les champs ne doivent pas être vides';
               } else {
                    $_POST['title'] = test_input($_POST['title']);
                    $_POST['content'] = test_input($_POST['content']);

                    if ($_POST['title'] == '' || $_POST['content'] == '') {
                         $error_post = 'Les champs ne doivent pas être vides';
                    } else {
                         $new_comments = array(
                              'email' => $_SESSION['email'],
                              'title' => $_POST['title'],
                              'content' => $_POST['content'],
                              'user_id' => $_SESSION['id'],
                              'post_id' => $_GET['post_id'],
                         );

                         try {
                              add_comments($new_comments);
                              header("location: ./show_post.php?post_id={$_GET['post_id']}");
                              
                              
                         } catch (\Throwable $e) {
                              echo $e->getMessage();
                         }
                    }
               }
          }
     ?>
     <div class="container mt-3">
          <div class="wrapper_show_post">
               <h1 class="text-center"><?= $post['title'] ?></h1>
               <div class="info_post d-flex align-items-center">
                    <div class="author_profile">
                         <img src="../images_profile/<?= !empty($_SESSION['ft_image']) ? $_SESSION['ft_image'] : 'default-image.png' ?>" alt="author profile" class="author_profile">
                    </div>
                    <div class="text_info d-flex flex-column justify-content-center">
                         <span><?= $post['pseudo'] ?></span>
                         <span>Posted at: <?= $date_creation ?></span>
                         <span>Updated at: <?= $date_update ?></span>
                    </div>
               </div>
               <div class="text_post">
                    <?= $post['content'] ?>
               </div>

               <div class="my-3">
                    <div class="wrapper_comments mb-3">
                         <h4>Comments</h4>
                         <?php foreach ($comments as $comment) : ?>
                              <div class="comments">
                                   <p><?php echo $comment['content'] ?></p>
                                   <div class="d-flex flex-column bg-info align-self-end">
                                        <?php
                                             $date = new DateTime($comment['created_at']);
                                             $format = $date->format('d M Y \a\t H:i');
                                        ?>
                                        <span><?php echo $format;  ?></span>
                                        <strong><?php echo $comment['pseudo'] ?></strong>
                                   </div>
                              </div>
                         <?php endforeach; ?>
                    </div>

                    <?php if ($nb_comments > 10) : ?>
                              <nav class="offset-2 col-8">
                                   <ul class="pagination">
                                        <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                                        <li class="page-item <?= ($currentPage == 1) ? "disabled" : "" ?>">
                                             <a href="./show_post.php?post_id=<?= $_GET['post_id'] ?>&amp;page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                                        </li>
                                        <?php for ($page = 1; $page <= $pages; $page++) : ?>
                                             <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                                             <li class="page-item <?= ($currentPage == $page) ? "active" : "" ?>">
                                                  <a href="./show_post.php?post_id=<?= $_GET['post_id'] ?>&amp;page=<?= $page ?>" class="page-link"><?= $page ?></a>
                                             </li>
                                        <?php endfor; ?>
                                        <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                                        <li class="page-item <?= ($currentPage == $pages) ? "disabled" : "" ?>">
                                             <a href="./show_post.php?post_id=<?= $_GET['post_id'] ?>&amp;page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                                        </li>
                                   </ul>
                              </nav>
                    <?php endif;  ?>
                    <form action="<?php echo htmlspecialchars("http://blog.test". $_SERVER['REQUEST_URI']); ?>" method="post">
                         <?php if (isset($error_post)) : ?>
                              <h5 class="text-danger text-center mb-3"><?= $error_post; ?></h5>
                         <?php endif; ?>
                         <div class="row mb-3">
                              <div class="col-12 col-md-8 offset-md-2">
                                   <input type="text" placeholder="title" name="title" id="title" class="form-control input">
                              </div>
                         </div>
                         <div class="row mb-3">
                              <div class="col-12 col-md-8 offset-md-2">
                                   <textarea name="content" id="content" placeholder="content..." class="form-control" cols="30" rows="7"></textarea>
                              </div>
                         </div>
                         <div class="text-center">
                              <button type="submit" class="button">Send</button>
                         </div>
                    </form>
               </div>
          </div>
     </div>
</body>
</html>