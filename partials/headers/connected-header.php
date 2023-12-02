<?php
 if (isset($_POST['logout'])) {
    session_destroy();
    header('location: ../../index.php');
 } 
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="#">BLOGS</a>
        <a class="nav-link" href="#">FAVORITES</a>
        <a class="nav-link" href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit()">LOGOUT</a>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" id="logout-form" method="post" style="display:none">
          <input type="hidden" name="logout" >
        </form>
      </div>
    </div>
  </div>
</nav>