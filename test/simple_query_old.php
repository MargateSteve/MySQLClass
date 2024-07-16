<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$text = '';
$_db->sql_set(sql:'SELECT * FROM players');
$res = $_db->query_run(clear_after:false);

$text .= "$"."_db->sql_set(sql:'SELECT * FROM players');<br>";
$text .= $_db->show_db_object();?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Simple Query</h5>
    <?php
    echo $text;
    ?>
  </div>
  
  <div class="card-body bg-success  bg-opacity-10 mb-2">
    <h5>DB Object</h5>
    <pre><code>
      $_db->show_db_object();
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <?php
        $_db->show_db_object();
      ?>
    </div>
  </div>
<?php



$text = '';
$_db->sql_set(sql:'SELECT * FROM testtable');
$text .= "$"."_db->sql_set(sql:'SELECT * FROM testtableddd');<br>";
$text .= $_db->show_db_object();
$text .= '<hr>';
$_db->sql_set(sql:'SELECT * FROM testtable');
$text .= "$"."_db->sql_set(sql:'SELECT * FROM testtable');<br><i>Does not clear existing content of _db</i><br>";
$text .= $_db->sql_show();
$text .= '<hr>';
$_db->sql_set(sql:'SELECT * FROM testtable', clear:true);
$text .= "$"."_db->sql_set(sql:'SELECT * FROM testtable', clear:true);<br><i><b>Does</b> clear existing content of _db</i><br>";
$res = $_db->query_run(clear_after:false);?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Simple Query</h5>
    <?php
    echo $text;
    ?>
  </div>
  
  <div class="card-body bg-success  bg-opacity-10 mb-2">
    <h5>DB Object</h5>
    <pre><code>
      $_db->show_db_object();
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <?php
        $_db->show_db_object();
      ?>
    </div>
  </div>
<?php
$res = $_db->query_run(clear_after:false);

$_db->sql_set(sql:'SELECT Col1, Col2 FROM testtable', clear:true);
$text .= "$"."_db->sql_set(sql:'SELECT Col1, Col2 FROM testtable', clear:true);<br><i><b>Does</b> clear existing content of _db</i><br>";
$text .= $_db->sql_show();

$res = $_db->query_run(clear_after:false);

?>
<div class="card">
  <div class="card-body">
    <h5 class="card-title">Simple Query</h5>
    <?php
    echo $text;
    ?>
  </div>
  
  <div class="card-body bg-success  bg-opacity-10 mb-2">
    <h5>DB Object</h5>
    <pre><code>
      $_db->show_db_object();
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <?php
        $_db->show_db_object();
      ?>
    </div>
  </div>

  <div class="card-body bg-primary" style="--bs-bg-opacity: .2;">
    <h5>Results Loop</h5>
    <pre><code>
      foreach ($_db->results() as $key => $value) {
        echo $key .' - '. $value->Col1.'&lt;br&gt;';
      }
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <?php
        foreach ($_db->results() as $key => $value) {
          echo $key .' - '. $value->Col1.'<br>';
        }
      ?>
    </div>
  </div>
</div>


<?php
include('template/bottom.html');

