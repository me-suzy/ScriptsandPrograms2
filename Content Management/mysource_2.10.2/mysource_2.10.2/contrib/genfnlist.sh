#!/bin/sh

FUNCTIONLIST=./doc/functionlist.txt
rm -f $FUNCTIONLIST
echo '<? /*' >> $FUNCTIONLIST;
for i in `find . -path './cache' -prune -o -path './data' -prune -o -type f -exec egrep -l '^[[:space:]]*function [&]?[a-zA-Z0-9_-]+\(' {} \;`; do 
	echo $i >> $FUNCTIONLIST; 
	egrep -B 2 '^[[:space:]]*function [&]?[a-zA-Z0-9_-]+\(' $i |\
	sed -e 's%^[[:space:]]*%    %' \
	-e 's%[[:space:]]*$%%' \
	-e 's%{$%%' >> $FUNCTIONLIST ;
	echo '' >> $FUNCTIONLIST; 
done
echo ' */ ?>' >> $FUNCTIONLIST
