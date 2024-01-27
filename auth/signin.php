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
     <title>Sign in</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <link rel="stylesheet" href="../style/style.css?<?php echo filemtime("../style/style.css"); ?>" type="text/css">
</head>
<body>
     <?php require_once '../partials/headers/guest-header.php'; ?>
     
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
          <div class="wrapper mx-auto px-1 pb-3">
               <h1 class="text-center text-uppercase">SIGN IN</h1>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <?php if (isset($error_credential)): ?>
                         <h4 class="bg-danger text-light text-center mb-3 rounded"><?php echo $error_credential; ?></h4>
                    <?php endif; ?>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <input type="email" placeholder="blog123@gmail.com" name="email" id="email" class="form-control input" value="<?php echo $email ?>">
                         </div>
                    </div>
                    <div class="row mb-3">
                         <div class="col-12 col-md-8 offset-md-2">
                              <input type="password" placeholder="Password" name="password" id="password" class="form-control input">
                         </div>
                    </div>
                    <div class="text-center">
                         <button type="submit" class="button">Validate</button>
                    </div>
               </form>
          </div>
     </div>
     
</body>
</html>