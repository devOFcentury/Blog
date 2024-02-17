<?php
 if (isset($_POST['logout'])) {
    session_destroy();
    header('location: ../../index.php');
 } 
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary header">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item d-flex justify-content-center">
          <input type="checkbox" id="toggle-1" hidden>
          <label class="nav-link border rounded" role="button" for="toggle-1">Blogs</label>
          <div class="list list-1">
            <a href="#" class="item nav-link text-capitalize">
              <i class="fa-solid fa-blog"></i>  
              My Blogs
            </a>
            <a href="#" class="item nav-link text-capitalize">
              <i class="fa-solid fa-star"></i>
              Favorites
            </a>
          </div>
        </li>

        <li class="nav-item d-flex justify-content-center">
          <input type="checkbox" id="toggle-2" class="input_img" hidden>
          <label class="label_profile nav-link" role="button" for="toggle-2">
          <?php if(!empty($_SESSION['ft_image'])): ?>   
            <img src="../../images_profile/<?= $_SESSION['ft_image'] ?>" alt="image profile" class="image_profile">
          <?php else: ?> 
              <img src="../../images_profile/default-image.png" alt="image profile" class="image_profile">
          <?php endif; ?>
          </label>
          <div class="list list-2">
            <span class="item nav-link text-capitalize border-bottom"><?= $_SESSION['pseudo'] ?></span>
            <a href="../../index.php" class="item nav-link text-capitalize">
                <i class="fa-solid fa-house"></i> 
                Dashboard
            </a>
            <a href="#" class="item nav-link text-capitalize">
              <i class="fa-solid fa-gear"></i>
              setting
            </a>
            <a class="item nav-link text-capitalize" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()">
              <i class="fa-solid fa-right-from-bracket"></i>
              logout
            </a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="logout-form" method="post" style="display:none">
              <input type="hidden" name="logout" >
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>