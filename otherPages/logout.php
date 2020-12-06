<?php
  $nav_selected = "LIST";
  $left_buttons = "NO";
  $left_selected = "";

  include("../includes/innerNav.php");
  
  if(session_id() == '' || !isset($_SESSION)){
		session_start();
  }
  
  if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['role']);
    unset($_SESSION['user']);
    unset($_SESSION['email']);
?>
    <div class="right-content">
      <div class="container">

      <h3 style = "color: red;">Successfully logged out</h3>

      </div>
    </div>
<?php header("refresh: 1; url=../index.php");
  } else {
?>
    <div class="right-content">
      <div class="container">

      <h3 style = "color: red;">Successfully logged out</h3>

      </div>
    </div>
<?php header("refresh: 1; url=../index.php");
  }

 ?>

 <div class="right-content">
    <div class="container">


    </div>
</div>

<?php include("../includes/footer.php"); ?>
