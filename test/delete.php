<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$text = 'insert';
$_db->delete(table:'testtable', 
             clause: [['id', '=', '7']]);
$_db->show_db_object();
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Delete</h5>
    <?php
    echo $text;
    ?>
  </div>
  
</div>


<?php
include('template/bottom.html');

