<?php
require_once '../db_config/db.php';
require_once '../db_managment.php';
require_once '../partials/auth-layout/part-1.php';

if (!isset($_SESSION['id'])) {
     header('location: ../auth/signin.php');
}
?>
<?php require_once '../partials/headers/connected-header.php' ?>

<?php
$title = $content = '';
try {
     $categories = get_categories();
} catch (\Throwable $e) {
     $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     try {
          signin();
     } catch (\Throwable $e) {
          $error_db =  $e->getCode();
     }
}
?>

<h1>ADD POST</h1>

<div class="container mt-3">
     <div class="wrapper mx-auto px-1 pb-3">
          <h1 class="text-center text-uppercase">ADD POST</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

               <div class="row mb-3">
                    <div class="col-12 col-md-8 offset-md-2">
                         <input type="test" placeholder="title" name="title" id="title" class="form-control input" value="<?php echo $title ?>">
                    </div>
               </div>
               <div class="row mb-3">
                    <div class="col-12 col-md-8 offset-md-2">
                         <?php foreach($categories as $category): ?>
                              <div class="form-check form-check-inline">
                                   <input class="form-check-input" type="checkbox" id="<?= $category['category'] ?>" value="<?= $category['category'] ?>">
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