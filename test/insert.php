<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$text = 'insert';
$_db->insert(table:'testtable', fields:['col1'=>'col1data', 'col2'=>'col2data', 'col3'=>'col3data']);
$_db->show_db_object();
?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Insert</h5>
    <?php
    echo $text;
    ?>
  </div>
  
</div>


<?php
include('template/bottom.html');

