<?php
     require '../db_config/db.php';

     if (isset($_SESSION['id'])) {
          header('Location: ../index.php');
     }
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Sign up</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <link rel="stylesheet" href="../style/style.css?<?php echo filemtime("../style/style.css"); ?>" type="text/css">
</head>
<body>
     <?php require_once '../partials/headers/guest-header.php'; ?>
     
     <?php
          require_once './auth_check.php';
          $lastNameError = $firstNameError = $pseudoError = $emailError = $ft_image_error = $passwordError = "";
          $firstname = $lastname = $pseudo = $email = $ft_image= $password = $confirm_password = "";
          $error_db = null;
     
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
               $no_error = checkSignupInput();
     
               
     
               if ($no_error) {
                    try {
                         signup();
                    } catch (\Throwable $e) {
                         $error_db =  $e->getCode();
                    }
               }
               
          }
          
     ?>
     
     <div class="container mt-3">
          <div class="wrapper mx-auto px-1 pb-3">
               <h1 class="text-center text-uppercase">SIGN UP</h1>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" enctype="multipart/form-data">
                    <?php if (isset($error_db) && $error_db == 23000): ?>
                         <h4 class="bg-danger text-light text-center mb-3 rounded">Pseudo ou email déjà existant</h4>
                    <?php endif; ?>
                    <div class="row mb-3">
                         <div class="col-12 col-md-6 mb-3 mb-md-0">
                              <input type="text" placeholder="FirstName" name="firstname" id="firstname" class="form-control input" value="<?= $firstname  ?>">
                              <?php if (!empty($firstNameError)): ?>
                                   <div class="text-danger"><?= $firstNameError  ?></div>
                              <?php endif; ?>
                         </div>
                         <div class="col-12 col-md-6">
                              <input type="text" placeholder="LastName" name="lastname" id="lastname" class="form-control input" value="<?= $lastname ?>">
                              <?php if (!empty($lastNameError)): ?>
                                   <div class="text-danger"><?= $lastNameError  ?></div>
                              <?php endif; ?>
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-6 mb-3 mb-md-0">
                              <input type="text" placeholder="pseudo" name="pseudo" id="pseudo" class="form-control input" value="<?= $pseudo ?>">
                              <?php if (!empty($pseudoError)): ?>
                                   <div class="text-danger"><?= $pseudoError  ?></div>
                              <?php endif; ?>
                         </div>
                         <div class="col-12 col-md-6">
                              <input type="email" placeholder="blog123@gmail.com" name="email" id="email" class="form-control input" value="<?= $email ?>">
                              <?php if (!empty($emailError)): ?>
                                   <div class="text-danger"><?= $emailError  ?></div>
                              <?php endif; ?>
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12">
                              <input type="file" name="ft_image" id="ft_image" class="form-control input" value="<?= $ft_image ?>">
                              <?php if (!empty($ft_image_error)): ?>
                                   <div class="text-danger"><?= $ft_image_error  ?></div>
                              <?php endif; ?>
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-6 mb-3 mb-md-0">
                              <input type="password" placeholder="Password" name="password" id="password" class="form-control input">
                              <?php if (!empty($passwordError)): ?>
                                   <div class="text-danger"><?= $passwordError  ?></div>
                              <?php endif; ?>
                         </div>
                         <div class="col-12 col-md-6">
                              <input type="password" placeholder="Confirm password" name="confirm_password" id="confirm_password" class="form-control input">
                         </div>
                    </div>
                    <button type="submit" class="button">Valider</button>
               </form>
          </div>
     </div>
     
</body>
</html>