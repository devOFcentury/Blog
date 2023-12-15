<?php
require_once '../db_config/db.php';
require_once '../db_managment.php';
require_once '../partials/auth-layout/part-1.php';
require_once '../checks/check_datas.php';

if (!isset($_SESSION['id'])) {
     header('location: ../auth/signin.php');
}

if (!isset($_GET['post_id'])) {
     header('location: ../index.php');               
}

?>
     <?php require_once '../partials/headers/connected-header.php' ?>

     <?php

          try {
               $categories = get_categories();
               $post = get_post((int) $_GET['post_id']);
               $categories_post = get_categories_of_a_post((int) $_GET['post_id']);
          } catch (\Throwable $e) {
               $e->getMessage();
          }
          
          $title = $post['title'] ?? '';
          $content = $post['content'] ?? '';
          $error_post = $success_post = '';


          if ($_SERVER["REQUEST_METHOD"] == "POST") {
               if (empty($_POST['title']) || empty($_POST['content'])) {
                    $error_post = 'Les champs ne doivent pas être vides';
               } else {
                    $_POST['title'] = test_input($_POST['title']);
                    $_POST['content'] = test_input($_POST['content']);

                    if ($_POST['title'] == '' || $_POST['content'] == '') {
                         $error_post = 'Les champs ne doivent pas être vides';
                    } 
                    else { 
                         $updated_post = array();
     
                         if ($post['title'] != $_POST['title']) {
                              // array_push($updated_post, $_POST['title']);
                              $updated_post['title'] = $_POST['title'];
                         }
     
                         if ($post['content'] != $_POST['content']) {
                              // array_push($updated_post, $_POST['content']);
                              $updated_post['content'] = $_POST['content'];

                         }
                         
                    
                         $allSelectedNumbers = array_map(function ($number) {
                              return (int) $number;
                         }, $_POST['numbers']);

                         if (($title != $_POST['title']) || ($content != $_POST['content'])) {
                              update_myPost($_SESSION['id'], $updated_post, $post['id']);
                              $success_post = "Post modifié avec succès";
                         }



                         // Compare the values of two arrays, and return the matches
                         $result=array_intersect($allSelectedNumbers,$categories_post);
                         if (count($allSelectedNumbers) != count($result)) {
                              try {
                                   update_link_posts_categories($allSelectedNumbers, $categories_post, $post['id']);
                                   $success_post = "Post modifié avec succès";
                              } catch (\Throwable $e) {
                                   echo  $e->getMessage();
                              }
                         }
                         elseif (count($allSelectedNumbers) != count($categories_post)) {
                              try {
                                   update_link_posts_categories($allSelectedNumbers, $categories_post, $post['id']);
                                   $success_post = "Post modifié avec succès";
                              } catch (\Throwable $e) {
                                   echo  $e->getMessage();
                              }
                         }

                         // if there is a success message we update the categories of the post
                         if ($success_post != '') {
                              try {
                                   $categories = get_categories();
                                   $categories_post = get_categories_of_a_post((int) $_GET['post_id']);
                              } catch (\Throwable $e) {
                                   $e->getMessage();
                              }
                              
                         }
                    
                    }
               
               }
          }
     ?>

     <div class="container mt-3">
          <div class="wrapper mx-auto px-1 pb-3">
               <h1 class="text-center text-uppercase">EDIT POST</h1>
               <form action="<?php echo htmlspecialchars("http://blog.test". $_SERVER['REQUEST_URI']); ?>" method="POST">
                    <?php if (isset($error_post)) : ?>
                         <h4 class="bg-danger text-light text-center mb-3 rounded"><?= $error_post; ?></h4>
                    <?php endif; ?>
                    <?php if (isset($success_post)) : ?>
                         <h4 class="bg-success text-light text-center mb-3 rounded"><?= $success_post; ?></h4>
                    <?php endif; ?>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <input type="test" placeholder="title" name="title" id="title" class="form-control input" value="<?= isset($_POST['title']) ? $_POST['title'] : $post['title']  ?>">
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <?php foreach ($categories as $category) : ?>
                                   <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="numbers[]" type="checkbox" id="<?= $category['category'] ?>" value="<?= $category['id'] ?>" <?= (in_array($category['id'], $categories_post)) ? 'checked' : null ?> >
                                        <label class="form-check-label" for="<?= $category['category'] ?>"><?= $category['category'] ?></label>
                                   </div>
                              <?php endforeach; ?>
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <textarea name="content" id="content" placeholder="content..." class="form-control" cols="30" rows="10"><?= isset($_POST['content']) ? $_POST['content'] : $post['content'] ?></textarea>
                         </div>
                    </div>
                    <div class="text-center">
                         <button type="submit" class="button">Validate</button>
                    </div>
               </form>
          </div>
     </div>

<?php require_once '../partials/auth-layout/part-2.php' ?>