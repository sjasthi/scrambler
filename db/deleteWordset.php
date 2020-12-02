<?php 

    if(session_id() == '' || !isset($_SESSION)){
    session_start();
    }

    $nav_selected = "ADMIN";
    $left_buttons = "NO";
    $left_selected = "WORDSETS";

    include("../includes/innerNav.php");

?>
<style>#title {text-align: center; color: darkgoldenrod;}</style>
<?php

if (isset($_POST['ident'])){

    $id = mysqli_real_escape_string($db, $_POST['ident']);
    
    $sql = "SELECT * from word_sets_meta WHERE set_id = '$id'";

    $result = mysqli_query($db, $sql);

    if($result) {
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $wordId = $row['word_id'];
                $sql = "DELETE FROM word_sets
                        WHERE word_id = '$wordId'";
                mysqli_query($db, $sql);
            }
        }
    }

    $sql = "DELETE FROM word_sets_meta
            WHERE set_id = '$id'";

    mysqli_query($db, $sql);

    header('location: ../otherPages/admin_wordsets.php?edited=deleted');
}

?>

</div>