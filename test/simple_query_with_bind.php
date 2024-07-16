<?php
include '../DB.php';
$_db = new DB;



$text = '';
$_db->sql_set(sql:'SELECT * FROM testtable WHERE id = ?');
$_db->bind_set(array:[2]);
$text .= "$"."_db->sql_set(sql:'SELECT * FROM testtable WHERE id = ?');<br>";
$text .= "$"."_db->bind_set(array:[2]);<br>";
$text .= $_db->query_show();
$text .= '<hr>';

echo $text;
$res = $_db->query_run(clear_after:false);
echo '<pre>';
print_r($res);
echo '</pre>';
echo '<hr>';
$_db->show_row();
$_db->bind_clear();
$_db->sql_clear();

echo '<hr>';

$text = '';
$_db->sql_set(sql:'SELECT * FROM testtable WHERE Col1 = ? AND Col3 LIKE ?');
$_db->bind_set(array:['Col', 'B%']);
$text .= "$"."_db->sql_set(sql:'SELECT * FROM testtable WHERE Col1 = ? AND Col3 LIKE ?');<br>";
$text .= "$"."_db->bind_set(array:[col1data, 'col3%']);<br>";
$text .= $_db->query_show();
$text .= '<hr>';

echo $text;
$res = $_db->query_run(clear_after:false);
echo '<pre>';
print_r($res);
echo '</pre>';
echo '<hr>';
$_db->show_results();
echo '<hr>';

