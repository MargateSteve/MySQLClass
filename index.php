<?php
include 'DB.php';
$_db = new DB;



$text = '';
$_db->set_sql(sql:'SELECT * FROM continent');
$text .= "$"."_db->set_sql(sql:'SELECT * FROM continent');<br>";
$text .= $_db->show_sql();
$text .= '<hr>';
$_db->set_sql(sql:'SELECT * FROM continent');
$text .= "$"."_db->set_sql(sql:'SELECT * FROM continent');<br><i>Does not clear existing content of _db</i><br>";
$text .= $_db->show_sql();
$text .= '<hr>';
$_db->set_sql(sql:'SELECT * FROM continent', clear:true);
$text .= "$"."_db->set_sql(sql:'SELECT * FROM continent', clear:true);<br><i><b>Does</b> clear existing content of _db</i><br>";
$text .= $_db->show_sql();
$text .= '<hr>';

echo $text;

$res = $_db->run_query();
echo '<pre>';
print_r($res);
echo '</pre>';
echo '<hr>';

foreach ($res->_results as $key => $value) {
    echo $key .' - '. $value->ContinentName.'<br>';
}

