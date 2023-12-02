<?php
     require '../db_config/db.php';
     require_once '../partials/auth-layout/part-1.php';

     if (isset($_SESSION['id'])) {
          header('Location: ../index.php');
     }
?>

     <?php
          require_once '../partials/headers/guest-header.php';
     ?>

     <?php
          require_once './auth_check.php';
          $error_credential = $email = $password = '';

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                         signin();
                    } catch (\Throwable $e) {
                         $error_db =  $e->getCode();
                    }
               
          }
          
     ?>

     <div class="container mt-3">
          <div class="signup-wrapper mx-auto px-1 pb-3">
               <h1 class="text-center text-uppercase">SIGN IN</h1>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                         <?php
                              if (isset($error_credential)) {
                         ?>
                              <h4 class="bg-danger text-light text-center mb-3 rounded"><?php echo $error_credential; ?></h4>
                         <?php
                              }
                         ?>
                    <div class="mb-3">
                              <input type="email" placeholder="blog123@gmail.com" name="email" id="email" class="form-control input" value="<?php echo $email ?>">
                    </div>
                    <div class="mb-3">
                         <input type="password" placeholder="Password" name="password" id="password" class="form-control input">
                    </div>
                    <button type="submit" class="button">Valider</button>
               </form>
          </div>
     </div>
     
<?php
     require_once '../partials/auth-layout/part-2.php';
?>