#! /bin/sh

LOCK=/export/home/www/search/.active.search.lock

trap 'rm -f ${LOCK}; exit 1' 1 2 3 15
if /usr/local/bin/shlock -p $$ -f ${LOCK}; then

	/usr/bin/echo "Active Search Update job started                -- \c"
	date

	. /export/home/www/.kshenv

	cd /export/home/www/search

	now=`date +"%Y-%m-%d %H:%M:%S"`
	hour=`date +"%H"`

	# Check for primetime 6-8 pm
	inPrime=0
	if [ $hour -gt 18 ]; then
        	if [ $hour -lt 20 ]; then
                	inPrime=1
        	fi
	fi

	if [ $inPrime -eq 1 ]; then
		echo "In prime Time -- \c"
		if [ ! -s ActiveStatePrimeTime.txt ]; then
			cp ActiveState.txt ActiveStatePrimeTime.txt
		fi
		./ItemsToTextApp -s 24
	else
		echo "Not in prime Time -- \c"
                if [ -s ActiveStatePrimeTime.txt ]; then
                        mv ActiveStatePrimeTime.txt ActiveState.txt
                fi
		./ItemsToTextApp -m 24
	fi

	if [ $? -eq 0 ]; then
		/usr/bin/echo "Items list created             -- \c"
		date

		./do-pictures.sh  &

		/usr/local/bin/make -i -s -j 3 -f copy-and-build.mk NOW="$now"
		if [ $? -ne 0 ]; then
			/usr/bin/echo "Active Search Update job FAILED (copy and update)- \c"
			date
			exit 1
		fi
		mv ActiveState.new ActiveState.txt
	fi

	/usr/bin/echo "Active Search Update job finished               -- \c"
	date
fi

exit 0
