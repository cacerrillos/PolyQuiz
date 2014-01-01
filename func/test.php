<?
session_start();
var_dump($_SESSION);
include_once("polysession.func.php");
$test = new PolySessionRestore("2ea1ba", "ba");
$test->pull();
var_dump($test);
echo "<br>";
var_dump($_SESSION);
?>