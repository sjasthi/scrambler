
<?php 
  if(session_id() == '' || !isset($_SESSION)){
      session_start();
  }
  $nav_selected = "ADMIN";
  $left_buttons = "NO";
  $left_selected = "WORDSETS";

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

$wordsString = '';

if ($result->num_rows > 0) {
	
    $count = 0;
    while($row = $result->fetch_assoc()){
      if($count == 0){
        echo  '<form action="modifyTheWordset.php" method="POST" enctype="multipart/form-data">
              <br>
              <h2 id="title">Modify Wordset</h2><br>
              <p style="text-align:center; font-weight:bold">';
              
        switch ($row['type']) {
          case 'dabble':
            echo 'Enter the pyramid words each on a new line.  
            Word lengths should be unique and have no gaps in numbers.<br>
            Example: 3 letter word, 4 letter word, 5 letter word, etc.';
            break;
          case 'dabbleplus':
            echo 'Enter the pyramid words each on a new line.  
            Word lengths should be unique and have no gaps in numbers.<br>
            Example: 3 letter word, 4 letter word, 5 letter word, etc.';
            break;
          case 'lines':
            echo 'Enter multiple words each on a new line.
            <br>Words can be any variety of lengths.';
            break;
          case 'stack':
            echo 'Enter multiple words each on a new line.
            <br>Words can be any variety of lengths.';
            break;
          case 'scrambler':
            echo 'Enter multiple words each on a new line.
            <br>All words should be equal in length.';
            break;
          case 'shapes':
            echo 'Enter multiple words each on a new line.
            <br>Words must be the same length.
            <br>Maximum of 5 words with maximum length of 5 letters';
            break;
          case 'swim':
            echo 'Enter multiple words each on a new line.
            <br>All words should be equal in length.';
            break;
        }
        echo '</p><br>';
        echo  '<table>
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
                  <td> <input type="text" class="form-control" name="subtitle" value="'.$row["subtitle"].'"  maxlength="255" required></td>
                </tr>
                    
                <tr>
                  <td style="width:100px>   <label for="type">Type</label></td>
                  <td>      <input type="text" class="form-control" name="type" value="'.$row["type"].'"  maxlength="255" size="200"  readonly></td>
                </tr>
              ';
      }
      $count++;

              $sql = 'SELECT word FROM word_sets WHERE word_id = "'.$row['word_id'].'"';
              $data = mysqli_query($db, $sql);
              $word = $data->fetch_assoc();
              $wordsString = $wordsString.$word['word'].'
';
            
      
    
    }//end while
    echo '<tr>
            <td style="width:100px><label for="wordinput">Words</label></td>
            <td><textarea class="form-control" name="wordinput" rows="10"  required>'.$wordsString.'</textarea></td>
          </tr>';
    echo '</table>

          <input type="number" name="count" style="display: none" value="'.$count.'">

          <br>
          <div class="text-left">
              <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify</button>
          </div>
          <br> <br>
    
          </form>';
}//end if
}
else {
    echo "0 results";
}//end else

?>

</div>
<?php }?>




