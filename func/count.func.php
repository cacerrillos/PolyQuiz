<?
#Counts lines of code in .php files
function lines(){
$string = shell_exec("find . -name '*.php' -not -path './securimage/*' -not -path './phpmyadmin/*' | xargs wc -l");
preg_match_all('/[0-9]+/',$string,$matches);
$linesofcode = $matches[0][count($matches[0])-1];
return $linesofcode;
}
?>