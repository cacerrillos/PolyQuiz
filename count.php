<?
$time_start = microtime(true); 
#Counts lines of code in .php files
$string = shell_exec("find . -name '*.php' -not -path './securimage/*' -not -path './phpmyadmin/*' -not -path './jpgraph/*' | xargs wc -l");
preg_match_all('/[0-9]+/',$string,$matches);
echo $matches[0][count($matches[0])-1]." Lines of Code in PolyQuiz!";
$time_end = microtime(true);
$execution_time = ($time_end - $time_start)/60;
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
?>