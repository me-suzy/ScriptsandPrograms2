#!/bin/sh
while :
do
clear
echo ""
echo ""
echo ""
echo ""
echo "                +----------------------------------------------+"
echo "                | In-Link 2 file permissions batch script 1.0  |"
echo "                +----------------------------------------------+"
echo "                |         [1] Set all permissions              |"
echo "                |         [2] Exit                             |"
echo "                +----------------------------------------------+"
echo -n "                 Enter your choice [1-2]: "
read selection
case $selection in
1)	
	chmod 777 ../themes/*/*
	chmod 777 ../includes/config.php
	chmod 777 ../languages/*/*
	chmod 777 ./backup
	chmod 777 ./backup/dump.txt
	echo "permissions set"
	exit 0;;
2)	exit 0;;
esac
done