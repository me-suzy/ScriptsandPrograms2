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
	siteid=$1
	shift
	pageid=$1
	shift
	outputfile=$1
	shift
	tablelist=$@

	if [ -z "$tablelist" -o -z "$pageid" ]; then
		echo "No table or pageid"
	else
		if [ -z "$mysqlpassword" ]; then
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} ${newdbname} --tables ${tablelist} -c -t -w "pageid=${pageid}" >> ${outputfile}`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump page ${pageid} for site ${siteid}: ${status}"
			else
				output="Page ${pageid} for site ${siteid} dumped to file ${outputfile}."
			fi
		else
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} --password=${mysqlpassword} ${newdbname} --tables ${tablelist} -c -t -w "pageid=${pageid}" >> ${outputfile}`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump page ${pageid} for site ${siteid}: ${status}"
			else
				output="Page ${pageid} for site ${siteid} dumped to file ${outputfile}."
			fi
		fi
	fi
	echo ${output}
}

run_site_dump() {
	siteid=$1
	shift
	outputfile=$1
	shift
	tablelist=$@

	if [ -z "$tablelist" -o -z "$siteid" ]; then
		echo "No table or siteid"
	else

		echo ""
		echo " *** It is safe to ignore warnings like: 'mysqldump: Got error: 1054: Unknown column 'siteid' in 'where clause' when retrieving data from server'."
		echo " *** Not all site tables have a site id in their definition."
		echo ""

		if [ -z "$mysqlpassword" ]; then
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} ${newdbname} -f --tables ${tablelist} -c -t -w "siteid=${siteid}" >> ${outputfile}`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump site ${siteid}: ${status}"
			else
				output="Site ${siteid} dumped to file ${outputfile}."
			fi
		else
			status=`mysqldump -u ${mysqluser} -h ${mysqlhost} --password=${mysqlpassword} ${newdbname} -f --tables ${tablelist} -c -t -w "siteid=${siteid}" >> ${outputfile}`
			if [ $? -gt 0 ]; then
				output="There was a problem trying to dump site ${siteid}: ${status}"
			else
				output="Site ${siteid} dumped to file ${outputfile}."
			fi
		fi
	fi
	echo ${output}
}

newdbname=`echo $PPID`
echo ""
echo "Use with caution!"
echo "This will restore pages from a database dump (based on the siteids you supply)."
echo "You will need the database dump and the tarball of the backup."
echo "This will *only* restore the siteids you supply."
echo ""

echo -n "Site ID(s) to restore: "
read siteids
if [ -z "$siteids" ]; then
	echo "Need a site id. Aborting."
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

	all_pageids=""

	for sid in ${siteids}; do
		dumpfile="${dbdump}-site-${sid}.restore.sql"

		if [ -f ${dumpfile} ]; then
			echo -n "Dumpfile ${dumpfile} already exists. Removing.. "
			rm -f ${dumpfile}
			if [ $? -gt 0 ]; then
				echo "*** Unable to remove ${dumpfile}. Skipping site ${sid}"
				continue
			fi
			echo "Ok."
		fi

		tablelist=`run_query "SHOW TABLES LIKE 'xtra_site_extension_%'"`
		output=`run_site_dump ${sid} ${dumpfile} ${tablelist} site site_access_grant site_admin site_allowed_designid site_allowed_extension site_allowed_template site_design site_design_customisation site_editor site_url access_group`
		echo $output

		pageids=`run_query "SELECT pageid FROM page WHERE siteid='${sid}'"`
		all_pageids="${all_pageids} ${pageids}"
		for pid in ${pageids}; do
			template=`run_query "SELECT template FROM page WHERE pageid='${pid}'"`

			if [ -z ${template} ]; then
				echo "The page ( ${pid} ) doesn't have a template and therefore might not exist. Skipping."
				continue
			fi

			tablelist=`run_query "SHOW TABLES LIKE 'xtra_page_template_${template}%'"`

			output=`run_dump ${sid} ${pid} ${dumpfile} ${tablelist} page page_editor page_admin file page_access_grant log_page_hit`
			echo $output
		done
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
if [ ${restore_database} != "Y" -a ${restore_database} != "y" ]; then
	echo "*** WARNING: Restoring files without restoring the database won't get much back - only design customisations."
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
	for sid in ${siteids}; do
		sitelist=`${tarcommand} ${tarball} | egrep "site/${sid}/\$"`
		filerestore="${filerestore} ${sitelist}"
	done
	for pid in ${all_pageids}; do
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
echo "Sites ( ${siteids} ) have been extracted."
echo "Pages ( ${pageids} ) have been extracted."
echo ""
echo ""

exit 0
