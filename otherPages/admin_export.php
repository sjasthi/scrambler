<?php

  if(session_id() == '' || !isset($_SESSION)){
    session_start();
  }

  $nav_selected = "ADMIN";
  $left_buttons = "YES";
  $left_selected = "";

  include("../includes/innerNav.php");

  if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
    ?>
    
    
      <div class="right-content">
        <div class="container">
  
        <h3 style = "color: red;">Please log in to view this page</h3>
  
        </div>
      </div>
    
    <?php
  } else if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    ?>
    
    
      <div class="right-content">
        <div class="container">
  
        <h3 style = "color: red;">Admin privileges are required to view this page</h3>
  
        </div>
      </div>
    
    <?php
  } else {
?>
<html>
<div class="right-content">
  <div class="container">
    <h3 style = "color: #01B0F1;">Export</h3>
  </div>
  <br>
  <p style="text-align:center; font-weight:bold; font-size:20px">To be completed</p>
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

<?php include("../includes/footer.php"); }?>
