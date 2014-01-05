#Counts lines of code in .php files
find . -name '*.php' -not -path "./securimage/*" | xargs wc -l
