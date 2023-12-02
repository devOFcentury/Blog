<?php
     require './db_config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Document</title>
     <!-- <link rel="stylesheet" href="style/style.css"> -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <link href="style/style.css?<?php echo filemtime("style/style.css"); ?>" rel="stylesheet" type="text/css" />
</head>

<body>
     <!-- Header -->
     <?php
          if (isset($_SESSION['id'])) {
               include_once('partials/headers/connected-header.php');
          }
          else {
               include_once('partials/headers/guest-header.php');
          }
     ?>
     <div class="container">
          
          <h1>SALUT : <?php if(isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) echo $_SESSION['firstname']. ' ' .$_SESSION['lastname'];?></h1>
     </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>