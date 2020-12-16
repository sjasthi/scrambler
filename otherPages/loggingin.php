<?php
  $nav_selected = "LIST";
  $left_buttons = "NO";
  $left_selected = "";

  include("../includes/innerNav.php");
  
  if(session_id() == '' || !isset($_SESSION)){
		session_start();
  }
  
  if($_POST['psw'] == 'ilp123' && $_POST['uname'] == 'admin') {
    $_SESSION['logged_in'] = true;
    $_SESSION['role'] = 'ADMIN';
    $_SESSION['user'] = $_POST['uname'];
    $_SESSION['email'] = 'placeholder@example.com';
    header("location:login.php?status=loggedin");
  } else {
  header("location:login.php?status=notloggedin");
  }
 ?>

 <!-- <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Login (TO BE DONE LATER)</h3>
      <br>
      <div class="container">
        <form name="login" action="loggingin.php">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" value="admin" name="uname" required>
        <br><br>
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" value="abc123" name="psw" required>
        <br><br>
        <button type="submit">Login</button>
        </form>
        
      </div>

    </div>
</div> -->

<?php include("../includes/footer.php"); ?>
