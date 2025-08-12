#!/bin/sh

run_query() {
	query=$1
	if [ -z "$query" ]; then
		echo "No query!"
		return -1
	fi
	if [ -z "${mysqlpassword}" ]; then
		output=`mysql -u ${mysqluser} -h ${mysqlhost} ${newdbname} -N -e "${query}"`
	else
		output=`mysql -u ${mysqluser} --password=${mysqlpassword} -h ${mysqlhost} ${newdbname} -N -e "${query}"`
	fi
	echo ${output}
}

run_dump() {
	pageid=$1
	shift
	tablelist=$@

	if [ -z "$tablelist" -o -z "$pageid" ]; then
		echo "No table or pageid"
	else
		dumpfile="${dbdump}-page-${pageid}.restore.sql"
		if [ -z "$mysqlpassword" ]; then
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} ${newdbname} --tables ${tablelist} -c -t -r ${dumpfile} -w "pageid=${pageid}"`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump page ${pageid}: ${status}"
			else
				output="Page ${pageid} dumped to file ${dumpfile}."
			fi
		else
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} --password=${mysqlpassword} ${newdbname} --tables ${tablelist} -c -t -r ${dumpfile} -w "pageid=${pageid}"`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump page ${pageid}: ${status}"
			else
				output="Page ${pageid} dumped to file ${dumpfile}."
			fi
		fi
	fi
	echo ${output}
}

newdbname=`echo $PPID`
echo ""
echo "Use with caution!"
echo "This will restore pages from a database dump (based on the pageids you supply)."
echo "You will need the database dump and the tarball of the backup."
echo "This will *only* restore the pageids you supply - any subpages this page has are *not* restored."
echo ""

echo -n "Page ID(s) to restore: "
read pageids
if [ -z "$pageids" ]; then
	echo "Need a page id. Aborting."
	exit 1
fi

echo -n "Restore Database [Y/n] : "
read restore_database
if [ -z ${restore_database} ]; then
	restore_database="y"
fi

if [ ${restore_database} = "Y" -o ${restore_database} = "y" ]; then

	echo -n "Database Dump: "
	read dbdump

	if [ -z "${dbdump}" ]; then
		echo "Need the database dump to restore from. Aborting."
		exit 1
	fi

	if ! [ -f "${dbdump}" ]; then
		echo "That database dump doesn't exist! Aborting."
		exit 1
	fi

	echo -n "MySQL User [root]: "
	read mysqluser
	if [ -z "${mysqluser}" ]; then
		mysqluser="root"
	fi

	echo -n "MySQL Password []: "
	read mysqlpassword

	echo -n "MySQL Host [localhost]: "
	read mysqlhost
	if [ -z "${mysqlhost}" ]; then
		mysqlhost="localhost"
	fi

	olddb=`head -n1 ${dbdump} | grep 'CREATE DATABASE' | awk -F' ' '{ print $(NF) }' | awk -F';' '{ print $1 }'`

	if [ -n "$olddb" ]; then
		echo -n "Stripping old database name out of dump .. "
		cat ${dbdump} | egrep -v "CREATE DATABASE|USE ${olddb};" > new.dump && mv new.dump $dbdump
		echo "Done."
	fi

	if [ -z "$mysqlpassword" ]; then
		status=`mysqladmin -u $mysqluser -h $mysqlhost create ${newdbname}`
		if [ $? -gt 0 ]; then
			echo "Aborting. Something went wrong. Unable to create new database ( ${newdbname} )."
			exit 1
		fi
	else
		status=`mysqladmin -u $mysqluser -h $mysqlhost --password=$mysqlpassword create ${newdbname}`
		if [ $? -gt 0 ]; then
			echo "Aborting. Something went wrong. Unable to create new database ( ${newdbname} )."
			exit 1
		fi
	fi

	echo -n "Importing old data .. "
	if [ -z "${mysqlpassword}" ]; then
		mysql -u ${mysqluser} -h ${mysqlhost} ${newdbname} < ${dbdump}
	else
		mysql -u ${mysqluser} -h ${mysqlhost} --password=${mysqlpassword} ${newdbname} < ${dbdump}
	fi
	echo "Done."

	for pid in ${pageids}; do

		template=`run_query "SELECT template FROM page WHERE pageid='${pid}'"`

		if [ -z ${template} ]; then
			echo "The page ( ${pid} ) doesn't have a template and therefore might not exist. Skipping."
			continue
		fi

		tablelist=`run_query "SHOW TABLES LIKE 'xtra_page_template_${template}%'"`

		output=`run_dump ${pid} ${tablelist} page page_editor page_admin file page_access_grant log_page_hit`
		echo $output
	done

	echo -n "Dropping now unused database .. "
	if [ -z "$mysqlpassword" ]; then
		status=`mysqladmin -u $mysqluser -h $mysqlhost -f drop ${newdbname}`
		if [ $? -gt 0 ]; then
			echo "Aborting. Something went wrong. Unable to drop database ( ${newdbname} )."
			exit 1
		fi
	else
		status=`mysqladmin -u $mysqluser -h $mysqlhost --password=$mysqlpassword -f drop ${newdbname}`
		if [ $? -gt 0 ]; then
			echo "Aborting. Something went wrong. Unable to drop database ( ${newdbname} )."
			exit 1
		fi
	fi
	echo "OK."

fi

echo -n "Restore files [Y/n] : "
read restore_files
if [ -z ${restore_files} ]; then
	restore_files="y"
fi
if [ ${restore_files} = "y" -o ${restore_files} = "Y" ]; then
	echo -n "Tarball of backup []: "
	read tarball
	if [ -z ${tarball} ]; then
		echo "Need a tarball name. Aborting"
		exit 0
	fi
	if ! [ -f ${tarball} ]; then
		echo "Tarball ${tarball} doesn't exist. Aborting."
		exit 0
	fi

	tarext=`echo ${tarball} | awk -F'.' '{ print $(NF) }'`

	if [ ${tarext} = "gz" -o ${tarext} = "tgz" ]; then
		tarcommand="tar -ztf"
	else
		tarcommand="tar -tf"
	fi

	echo -n "Generating list of files to retrieve .. "

	filerestore=""
	for pid in ${pageids}; do
		pagelist=`${tarcommand} ${tarball} | egrep "page/${pid}/\$"`
		filerestore="${filerestore} ${pagelist}"
	done

	echo " Done."

	echo -n "Extracting files .. "

	extractlist=""
	for f in ${filerestore}; do
		extractlist="${extractlist} ${f}"
	done

	if [ ${tarext} = "gz" -o ${tarext} = "tgz" ]; then
		tarcommand="tar -z -f ${tarball} -x"
	else
		tarcommand="tar -f ${tarball} -x"
	fi

	restoredlist=`${tarcommand} ${extractlist}`
	echo "Done."
fi

echo ""
echo ""
echo "Pages ( ${pageids} ) have been extracted."
echo ""
echo ""

exit 0
