#! /bin/sh

LOCK=/export/home/www/search/.complete.search.lock

trap 'rm -f ${LOCK}; exit 1' 1 2 3 15
if /usr/local/bin/shlock -p $$ -f ${LOCK}; then

	/usr/bin/echo "Daily job started                 -- \c"
	date

	. /export/home/www/.kshenv

	cd /export/home/www/search

	now=`date +"%Y-%m-%d %H:%M:%S"`

	if ./ItemsToTextApp -c; then

		/usr/bin/echo "Complete list created             -- \c"
		date

		rcp CompletedSearchItems.txt thunder@crocodile.ebay.com:/catalogs/import
		if [ $? -ne 0 ]; then
			/usr/bin/echo "Daily job FAILED (copy)           -- \c"
			date
			exit 1
		else
			rsh crocodile.ebay.com -l thunder /usr/local/morph3/common/daily-complete "$now"
			if [ $? -ne 0 ]; then
				/usr/bin/echo "Daily job FAILED (update)         -- \c"
				date
				exit 1
			fi

			mv CompleteState.new CompleteState.txt
		fi
	fi

	/usr/bin/echo "Daily job finished                -- \c"
	date
fi

exit 0
