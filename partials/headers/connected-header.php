<?php
 if (isset($_POST['logout'])) {
    session_destroy();
    header('location: ../../index.php');
 } 
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <!-- <a class="nav-link user" href="#"><?php echo $_SESSION['pseudo'];  ?></a> -->
          <input type="checkbox" id="drop-4" hidden>
          <label class="dropHeader dropHeader-4 nav-link" role="button" for="drop-4"><?php echo $_SESSION['pseudo'];  ?></label>
          <div class="list list-4">
            <a href="../../index.php" class="item nav-link text-capitalize">Dashboard</a>
            <a href="#" class="item nav-link text-capitalize">favorites</a>
            <a href="#" class="item nav-link text-capitalize">setting</a>
            <a class="item nav-link text-capitalize" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()">LOGOUT</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="logout-form" method="post" style="display:none">
              <input type="hidden" name="logout" >
            </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- <nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="../../index.php">BLOGS</a>
        <a class="nav-link" href="#">FAVORITES</a>
        <a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()">LOGOUT</a>
        <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="logout-form" method="post" style="display:none">
          <input type="hidden" name="logout" >
        </form>
      </div>
    </div>
  </div>
</nav> -->