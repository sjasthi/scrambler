<?php

  if(session_id() == '' || !isset($_SESSION)){
    session_start();
  }

  if(isset($_GET['edited'])) {
    switch ($_GET['edited']){
      case 'modified':
        $editMessage = 'Successfully modified word set metadata';
        break;
      case 'deleted':
        $editMessage = 'Successfully deleted word set';
        break;
      case 'invalidinput':
        $editMessage = 'Input for word list was not valid for the puzzle type, no changes made';
        break;
      default:
        $editMessage = 'Unknown error editing word sets';
    }
  }

  $nav_selected = "ADMIN";
  $left_buttons = "YES";
  $left_selected = "WORDSETS";

  include("../includes/innerNav.php");

  $query = "SELECT * FROM word_sets_meta INNER JOIN word_sets WHERE word_sets_meta.word_id = word_sets.word_id";

  $set_ids = [];
  $wordsets = [];
?>
<html>
<style>
  [data-title] {
    position: relative;
    cursor: help;
  }

  [data-title]:hover::before {
    content: attr(data-title);
    position: absolute;
    bottom: -46px;
    padding: 10px;
    background: #000;
    color: #fff;
    font-size: 14px;
    white-space: nowrap, pre-line;
  }

</style>
<?php if(isset($editMessage)) { ?>
	<br>
        <div class="form-group">
			<div class="col-sm-1"></div>
			<div class="col-sm-10">
				<label class="charLabel" style="color:red;font-size:14px;" name="charName" value="">
				<?php
						echo($editMessage);
				?>
				</label>
			</div>
		</div>
    <br>
	<?php } ?>
<div class="right-content">
  <div class="container">
    <h3 style = "color: #01B0F1;">Wordsets</h3>
      <form method="post">
      <button type = "submit" formaction ="../db/modifyWordset.php">Modify</button>
	    <button type = "submit" formaction ="../db/deleteWordset.php">Delete</button>
      <div id="customerTableView">
	      <table class="display" id="ceremoniesTable" style="width:100%">
          <div class="table responsive">
              <thead>
                <tr>
                  <th></th>
                  <th>Set ID</th>
                  <th>Title</th>
                  <th>Subtitle</th>
                  <th>Words</th>
                  <th>Type</th>
                  <th>Date Created</th>
                </tr>
              </thead>
				      <tbody>
                <?php

                  $data = mysqli_query($db, $query);
                  if($data) {
                    if ($data->num_rows > 0) {
                      $count = -1;
                      while($row = $data->fetch_assoc()) {
                        if(in_array($row["set_id"], $set_ids)){
                          $wordsets[$count] = $wordsets[$count].", ".$row["word"];
                         } else {
                          array_push($set_ids, $row['set_id']);
                          $count++;
                          $wordsets[$count] = $row["word"];
                        }
                      }
                      $array_length = count($set_ids);
                      for($i = 0; $i < $array_length; $i++) {
                        unset($set_ids[$i]);
                      }
                    }
                  }
                  $data = mysqli_query($db, $query);
                  if($data) {
                    if($data->num_rows > 0) {
                      $count = 0;
                      while($row = $data->fetch_assoc()) {
                        if(in_array($row["set_id"], $set_ids)){
                        }
                        else {
                          array_push($set_ids, $row['set_id']);
                          echo '<tr>
                            <td><input type ="radio" name ="ident" value ='.$row["set_id"].'></td>
                            <td>'.$row["set_id"].'</td>
                            <td>'.$row["title"].'</td>
                            <td>'.$row["subtitle"].' </span> </td>';
                            if(strlen($wordsets[$count]) > 15 ) {
                            echo '<td data-title="'.$wordsets[$count].'">'.substr($wordsets[$count],0,15).'... </td>';
                            } else {
                              echo '<td>'.$wordsets[$count].'</td>';
                            }
                            echo '<td>'.$row["type"].'</td>
                            <td>'.$row["created_date"].' </span> </td>
                            </tr>';
                            $count++;
                        }
                      }
                    } else {
                      echo "0 results";
                    }
                  }
                ?>
				      </tbody>
            </div>
          </table>
		    </form>
      </div>
    </div>
  </div>
</div>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 


<!--Data Table-->
<script type="text/javascript" charset="utf8"
        src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript" language="javascript">
  $(document).ready( function () {
        
    $('#ceremoniesTable').DataTable( {
      dom: 'lfrtBip',
      buttons: [
        'copy', 'excel', 'csv', 'pdf'
      ] }
    );

    $('#ceremoniesTable thead tr').clone(true).appendTo( '#ceremoniesTable thead' );
    $('#ceremoniesTable thead tr:eq(1) th').each( function (i) {
      if($(this).text() != ''){
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    
        $( 'input', this ).on( 'keyup change', function () {
          if ( table.column(i).search() !== this.value ) {
            table
              .column(i)
              .search( this.value )
              .draw();
          }
        } );
      }
    } );
    
    var table = $('#ceremoniesTable').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
    } );
       
  } );
</script>

<?php include("../includes/footer.php"); ?>
