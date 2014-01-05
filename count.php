<?
#Counts lines of code in .php files
echo shell_exec("find . -name '*.php' -not -path './securimage/*' -not -path './phpmyadmin/*' | xargs wc -l");
?>