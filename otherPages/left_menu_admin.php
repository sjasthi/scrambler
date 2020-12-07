<?php
  if(session_id() == '' || !isset($_SESSION)){
    session_start();
  }

  if(!is_logged_in() || !is_admin()){}
  else {
?>
<div id="menu-left">

	<a href="admin_wordsets.php">
		<div <?php if($left_selected == "WORDSETS")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/wordsets.png">
		<br/>Wordsets<br/></div>
  	</a>

	<a href="admin_preferences.php">
		<div <?php if($left_selected == "PREFERENCES")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/preferences.png">
		<br/>Preferences<br/></div>
  	</a>


	<a href="admin_batchmode.php">
		<div <?php if($left_selected == "BATCHMODE")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/batch.png">
		<br/>Batch Mode<br/></div>
	</a>

	<a href = "admin_backup.php">
		<div <?php if($left_selected == "BACKUP")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/backup.png">
		<br/>Backup<br/></div>
	</a>

	<a href = "admin_import.php">
		<div <?php if($left_selected == "IMPORT")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/import.png">
		<br/>Import<br/></div>
  	</a>	

	<a href = "admin_export.php">
		<div <?php if($left_selected == "EXPORT")
		{ echo 'class="menu-left-current-page"'; } ?>>
		<img src="../images/export.png">
		<br/>Export<br/></div>
	</a>


</div>
  <?php } ?>