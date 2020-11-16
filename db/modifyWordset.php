
<?php 
  if(session_id() == '' || !isset($_SESSION)){
      session_start();
  }
  $nav_selected = "ADMIN";
  $left_buttons = "NO";
  $left_selected = "WORDSETS";

	include("../includes/innerNav.php");
?>


<div class="container">
<style>#title {text-align: center; color: darkgoldenrod;}</style>

<?php
$db->set_charset("utf8");
  $sql = "SELECT * FROM words_sets_meta
            WHERE id = -1";
	$sleep=true;
  $touched=isset($_POST['ident']);
if (!$touched) {
	echo "You need to select an entry.";
      ?>
		  <button><a class="btn btn-sm" href="../otherPages/admin_wordsets.php">Go back</a></button>
	<?php
		
		
} else {     $id = $_POST['ident'];
    $sql = "SELECT * FROM word_sets_meta
            WHERE set_id = '$id'";
    

	
}
if ($touched) {
    if (!$result = $db->query($sql)) {
        die ('There was an error running query[' . $connection->error . ']');
    }//end if
//end if

if ($result->num_rows > 0) {
	
    $row = $result->fetch_assoc();

      echo '<form action="modifyTheWordset.php" method="POST" enctype="multipart/form-data">
      <br>
     <h2 id="title">Modify Wordset</h2><br>
      
           <table>
		   <tr>
       <td style="width:100px>   <label for="setid">Set ID</label> </td>
       <td> <input type="number" class="form-control" name="setid" value="'.$row["set_id"].'"  maxlength="255" readonly></td>
      </tr>
      
    <tr>
        <td style="width:100px>  <label for="title">Title</label></td>
     <td>    <input type="text" class="form-control" name="title" value="'.$row["title"].'"  maxlength="255"  required></td>
  </tr>
      
      <tr>
        <td style="width:100px>  <label for="subtitle">Subtitle</label></td>
    <td>     <input type="text" class="form-control" name="subtitle" value="'.$row["subtitle"].'"  maxlength="255" required></td>
     </tr>
          
   <tr>
       <td style="width:100px>   <label for="type">Type</label></td>
   <td>      <input type="text" class="form-control" name="type" value="'.$row["type"].'"  maxlength="255" size="200"  readonly></td>
     </tr>
          
    </table>

      <br>
      <div class="text-left">
          <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify</button>
      </div>
      <br> <br>
      
      </form>';
    
    //}//end while
}//end if
}
else {
    echo "0 results";
}//end else

?>

</div>




