<?php
  $nav_selected = "LIST";
  $left_buttons = "NO";
  $left_selected = "";

  include("../includes/innerNav.php");
  
 ?>

 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Login (TO BE DONE LATER)</h3>
      <br>
      <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" value="admin" name="uname" required>
        <br><br>
        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" value="abc123" name="psw" required>
        <br><br>
        <button type="submit">Login</button>
        
      </div>

    </div>
</div>

<?php include("../includes/footer.php"); ?>
