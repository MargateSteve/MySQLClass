<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$_db->select(table:'testtable');

?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Basic Select </h5>
    $_db->select(table:'testtable');<br>
    $_db->show_db_object();
    <?php
    $_db->show_db_object();
    ?>
  </div>
  
</div>
<?php
$_db->select(table:'testtable', clause: ['Col3', 'LIKE', '%new']);

?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Basic Select with clause </h5>
    $_db->select(table:'testtable', clause: ['Col3', 'LIKE', '%new']);<br>
    $_db->show_db_object();
    <?php
    $_db->show_db_object();
    ?>
  </div>
  
</div>
<?php
$_db->select(table:'players', clause: ['FirstName', 'LIKE', '%mon'], cols:'Nickname as Nick,FirstName, LastName');

?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Basic Select with clause </h5>
    $_db->select(table:'testtable', clause: ['FirstName', 'LIKE', '%mon'], cols:'Nickname as Nick,FirstName, LastName');<br>
    $_db->show_db_object();
    <?php
    $_db->show_db_object();
    ?>
  </div>
  
</div>


<?php
include('template/bottom.html');

