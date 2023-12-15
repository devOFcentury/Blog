<?php
require_once '../db_config/db.php';
require_once '../db_managment.php';
require_once '../partials/auth-layout/part-1.php';
require_once '../checks/check_datas.php';

if (!isset($_SESSION['id'])) {
     header('location: ../auth/signin.php');
}
?>
     <?php require_once '../partials/headers/connected-header.php' ?>

     <?php
          $title = $content = $error_post = $success_post = '';
          try {
               $categories = get_categories();
          } catch (\Throwable $e) {
               $e->getMessage();
          }


          if ($_SERVER["REQUEST_METHOD"] == "POST") {
               if (empty($_POST['title']) || empty($_POST['content'])) {
                    $error_post = 'Les champs ne doivent pas être vides';
               } 
               else {
                    $_POST['title'] = test_input($_POST['title']);
                    $_POST['content'] = test_input($_POST['content']);

                    if ($_POST['title'] == '' || $_POST['content'] == '') {
                         $error_post = 'Les champs ne doivent pas être vides';
                    } 
                    else {
                         
                         $allSelectedNumbers = array_map(function ($number) {
                              return (int) $number;
                         }, $_POST['numbers']);
                    
                         $post = array(
                              'title' => $_POST['title'],
                              'content' => $_POST['content']
                         );
                    
                         try {
                              add_post($_SESSION['id'], $post, $allSelectedNumbers);
                              $success_post = "Post créé avec succès";
                         } catch (\Throwable $e) {
                              echo  $e->getMessage();
                         }
                    }
               
               }
          }
     ?>

     <div class="container mt-3">
          <div class="wrapper mx-auto px-1 pb-3">
               <h1 class="text-center text-uppercase">ADD POST</h1>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <?php if (isset($error_post)) : ?>
                         <h4 class="bg-danger text-light text-center mb-3 rounded"><?= $error_post; ?></h4>
                    <?php endif; ?>
                    <?php if (isset($success_post)) : ?>
                         <h4 class="bg-success text-light text-center mb-3 rounded"><?= $success_post; ?></h4>
                    <?php endif; ?>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <input type="test" placeholder="title" name="title" id="title" class="form-control input" value="<?php echo $title ?>">
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <?php foreach ($categories as $category) : ?>
                                   <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="numbers[]" type="checkbox" id="<?= $category['category'] ?>" value="<?= $category['id'] ?>">
                                        <label class="form-check-label" for="<?= $category['category'] ?>"><?= $category['category'] ?></label>
                                   </div>
                              <?php endforeach; ?>
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <textarea name="content" id="content" placeholder="content..." class="form-control" cols="30" rows="10"></textarea>
                         </div>
                    </div>
                    <div class="text-center">
                         <button type="submit" class="button">Validate</button>
                    </div>
               </form>
          </div>
     </div>

<?php require_once '../partials/auth-layout/part-2.php' ?>