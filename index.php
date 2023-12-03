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
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
          <div class="row d-md-none">
               <div class="offset-2 col-8 d-flex flex-column flex-md-row justify-content-center">
                    <input type="search" name="" class="input" placeholder="Search Here...">
                    <div class="text-center">
                         <button class="button mx-md-3">search</button>
                    </div>
               </div>
          </div>
          <div class="row pt-md-5">

               <div class="col-8">
                    <div class="row">
                         <div class="col-12 col-md-4 py-3">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                   </div>
                                   <a href="#" class="btn card-button">See More</a>
                              </div>
                         </div>
                         <div class="col-12 col-md-4 py-3">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                   </div>
                                   <a href="#" class="btn card-button">See More</a>
                              </div>
                         </div>
                         <div class="col-12 col-md-4 py-3">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                   </div>
                                   <a href="#" class="btn card-button">See More</a>
                              </div>
                         </div>
                         <div class="col-12 col-md-4 py-3">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Special title treatment</h5>
                                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                   </div>
                                   <a href="#" class="btn card-button">See More</a>
                              </div>
                         </div>
                    </div>
               </div>

               <aside class="col-4">
                    <div>
                         <div class="mb-3 text-center d-none d-md-block">
                              <form action="" method="post">
                                   <input type="search" name="" class="input mb-2 form-control" placeholder="Search Here...">  
                                   <button type="submit" class="btn btn-info mb-2 mb-md-0 mx-md-3">search</button>
                                   
                              </form>
                         </div>
                         <div class="text-md-center">
                              <button title="Add a post" class="btn btn-info"><i class="fa-solid fa-circle-plus"></i> POST</button>
                         </div>
                    </div>
               </aside>

          </div>
          
     </div>


     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>