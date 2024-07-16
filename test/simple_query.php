<?php
include '../DB.php';

include('template/top.html');
$_db = new DB;

$text = '';
$_db->sql_set(sql:'SELECT * FROM players');
$res = $_db->query_run(clear_after:false);

$text .= "$"."_db->sql_set(sql:'SELECT * FROM players');<br>";
?>
<div class="card mb-2">
  <div class="card-body">
    <h5 class="card-title">Simple Query</h5>
    <?php
    echo $text;
    ?>
  </div>
  
  <div class="card-body bg-success  bg-opacity-10 mb-2">
    <h5>Show the DB Object</h5>
    <pre><code>
      $_db->show_db_object();
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <pre>
        <?php $_db->show_db_object(); ?>
      </pre>
    </div>
</div>

<?php

$text = '';
$_db->sql_set(sql:'SELECT * FROM players');
$res = $_db->query_run(clear_after:false);

$text .= "$"."_db->sql_set(sql:'SELECT * FROM players');<br>";
$text .= "<i>Does not clear existing content of _db so the sql would fail</i><br>";
$text .= $_db->sql_show();
?>
<div class="card mb-2">
  <div class="card-body">
    <h5 class="card-title">Simple Query repeated without clearing previous</h5>
    <?php
    echo $text;
    ?>
  </div>
  
</div>

<?php

$text = '';
$_db->sql_set(sql:'SELECT * FROM players', clear:true);
$res = $_db->query_run(clear_after:false);

$text .= "$"."_db->sql_set(sql:'SELECT * FROM players', clear:true);<br>";
$text .= "<i>Adding clear:true to sql_set <b>will</b> clear the content of _db</i><br>";
?>
<div class="card mb-2">
  <div class="card-body">
    <h5 class="card-title">Simple Query repeated with clearing previous</h5>
    <?php
    echo $text;
    ?>
  </div>
  
  <div class="card-body bg-success  bg-opacity-10 mb-2">
    <h5>Show the DB Object</h5>
    <pre><code>
      $_db->show_db_object();
    </code></pre>
    <div class="overflow-auto" style="max-height:200px">
      <pre>
        <?php $_db->show_db_object(); ?>
      </pre>
    </div>
  </div>
  <div class="card-footer">
      You can also force automatic, clearing of the content of db at the time of running the query with $res = $_db->query_run(clear_after:false);

  </div>
</div>

<?php



?>

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
          echo $key .' - '. $value->FirstName . ' ' .$value->LastName . ' ('.$value->Nickname.')'.'<br>';
        }
      ?>
    </div>
  </div>
</div>


<?php
include('template/bottom.html');

