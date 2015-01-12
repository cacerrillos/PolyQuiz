<?
#Counts lines of code in .php files
$string = shell_exec("find . -name '*.php' -not -path './securimage/*' -not -path './phpmyadmin/*' -not -path './jpgraph/*' | xargs wc -l");
preg_match_all('/[0-9]+/',$string,$matches);
echo $matches[0][count($matches[0])-1]." Lines of Code in PolyQuiz!";
?>