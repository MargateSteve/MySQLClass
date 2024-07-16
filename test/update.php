<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$text = 'insert';
$_db->update(table:'testtable', 
             fields:['col1'=>'col1datanew', 'col2'=>'col2datanew', 'col3'=>'col3datanew'], 
             clause: [['id', '=', '3'], ['id', '=', '4']], 
             clause_type: 'OR');
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

