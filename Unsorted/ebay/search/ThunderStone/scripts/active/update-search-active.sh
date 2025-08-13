#! /bin/sh

LOCK=/export/home/www/search/.start.update.active.search.lock

trap 'rm -f ${LOCK}; exit 1' 1 2 3 15
if /usr/local/bin/shlock -p $$ -f ${LOCK}; then
	(/export/home/www/search/get-items-active.sh 2>&1) | /usr/ucb/mail -s "SEARCH: Update job results (active)" www oper@ebay.com rajesh@ebay.com ken@ebay.com jonathan@ebay.com
fi

exit 0
