<?php
  $nav_selected = "ADMIN";
  $left_buttons = "YES";
  $left_selected = "";

  include("../includes/innerNav.php");
  
  if(!is_logged_in()) {
		?>
		
		
		  <div class="right-content">
			<div class="container">
	  
			<h3 style = "color: red;">Please log in to view this page</h3>
	  
			</div>
		  </div>
		
		<?php
	  } else if (!is_admin()) {
		?>
		
		
		  <div class="right-content">
			<div class="container">
	  
			<h3 style = "color: red;">Admin privileges are required to view this page</h3>
	  
			</div>
		  </div>
		
		<?php
	  } else {

 ?>

<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">ADMINISTRATIVE FUNCTIONS</h3>

    </div>
    <br>
    <p style="text-align:center; font-weight:bold; font-size:20px">Please select an option from the left hand menu.
</div>

<?php include("../includes/footer.php"); }?>
