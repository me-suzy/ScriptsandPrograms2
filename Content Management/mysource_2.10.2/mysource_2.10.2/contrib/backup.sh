#!/bin/sh

# Really simple backup script.
# Dumps out the databases into their respective names if they are different
# Tar's up the mysource directory excluding mysource/cache/*

currentdate=`date +%Y-%m-%d`
currenttime=`date +%H-%M-%S`

if [ -z "$1" ]; then
	echo "Sorry, I need to know what to back up."
	echo "To use:"
	echo -e "\t$0 mysourcedirectory"
	echo "This will create a tarball of the mysource directory with the database dump included."
	echo "The dump will also exclude the mysource cache directory."
	echo "When it has finished, you will be prompted to archive the new tarball."
	echo "If you specify a second argument (and it's a directory),"
	echo "we'll put the backup there instead of in the current directory."
	echo "If the second argument ends in .tgz or .tar.gz, the tarball created will be named as such."
	echo "Else, it's a directory."
	echo "The third argument can be --prompt=no, --prompt=0, --noprompt and all questions are ignored"
	echo "and default to 'N'."
	exit 1
fi

if [ ! -d "$1" ]; then
	echo "Hmm, directory $1 doesn't exist."
	echo "Exiting."
	exit 1
fi

if [ ! -f "$1/conf/mysource.conf" ]; then
	echo "Is this really a mysource system? There is no $1/conf/mysource.conf file."
	echo "Exiting."
	exit 1
fi

mysourcedir=$1

if [ ! -z $2 ]; then
	tempvar=$2
	# If the last two chars are "gz" then we're specifying a filename.
	# Otherwise, it's a directory.
	lastchars=`echo ${tempvar} | awk -F'/' '{ print $(NF) }' | awk -F'.' '{ print $(NF) }'`
	# If it doesn't exist or is a space, the next check for tgz or gz gives an error
	# [ too many arguments
	# So set it temporarily. We only need to check it here, so setting it like this doesn't matter.
	if [ -z ${lastchars} ]; then
		lastchars="tmp"
	fi
	if [ ${lastchars} = "tgz" -o ${lastchars} = "gz" ]; then
		# It's a specific file.
		temp=${tempvar}
		backupdir=`dirname $temp`
		if [ ! -d $backupdir ]; then
			mkdir -p $backupdir
			if [ $? -gt 0 ]; then
				echo "Unable to create directory ${backupdir}. Your problem."
				exit 1
			fi
		fi
		backupfilename=`basename $temp`
	else
		backupdir=${tempvar}
		if [ ! -d ${backupdir} ]; then
			mkdir -p ${backupdir}
			if [ $? -gt 0 ]; then
				echo "Problem trying to create dir ${backupdir}"
				echo "Aborting."
				exit 1
			fi
		fi
	fi
fi

if [ ! -z $3 ]; then
	if [ $3="--prompt=no" -o $3="--prompt=n" -o $3="--prompt=0" -o $3="--noprompt" ]; then
		noprompt=1
	fi
fi

# We have to do it like this because for some reason, saving cat mysource.conf to a temp var stripped out some spaces.
# so instead of it being 
# WebDatabase database host user <blankpass> /path/to/log
# it would become
# WebDatabase database host user /path/to/log

# Also, we can't use awk - it converts multi spaces into one space - which had the same effect.

web_db=`cat ${mysourcedir}/conf/mysource.conf | grep 'WebDatabase' | cut -d' ' -f2`
web_host=`cat ${mysourcedir}/conf/mysource.conf | grep 'WebDatabase' | cut -d' ' -f3`
web_user=`cat ${mysourcedir}/conf/mysource.conf | grep 'WebDatabase' | cut -d' ' -f4`
web_pass=`cat ${mysourcedir}/conf/mysource.conf | grep 'WebDatabase' | cut -d' ' -f5`

user_db=`cat ${mysourcedir}/conf/mysource.conf | grep 'UserDatabase' | cut -d' ' -f2`
user_host=`cat ${mysourcedir}/conf/mysource.conf | grep 'UserDatabase' | cut -d' ' -f3`
user_user=`cat ${mysourcedir}/conf/mysource.conf | grep 'UserDatabase' | cut -d' ' -f4`
user_pass=`cat ${mysourcedir}/conf/mysource.conf | grep 'UserDatabase' | cut -d' ' -f5`

if [ -z "$web_db" -o -z "$web_host" -o -z "$web_user" ]; then
	echo "Sorry, couldn't work out the web database details."
	echo "Aborting."
	exit 1
fi

if [ -z "$user_db" -o -z "$user_host" -o -z "$user_user" ]; then
	echo "Sorry, couldn't work out the user database details."
	echo "Aborting."
	exit 1
fi

# See if they are the same database first ..
if [ "$web_db" = "$user_db" -a "$web_host" = "$user_host" -a "$web_user" = "$user_user" -a "$web_pass" = "$user_pass" ]; then
	echo -n "Dumping out the database .."

	dumpname="${mysourcedir}/database_${currentdate}_${currenttime}.dump"

	if [ -z ${web_pass} ]; then
		mysqldump --add-drop-table -u ${web_user} -h ${web_host} ${web_db} > ${dumpname}
		dumpstatus=`echo $?`
	else
		mysqldump --add-drop-table -u ${web_user} --password=${web_pass} -h ${web_host} ${web_db} > ${dumpname}
		dumpstatus=`echo $?`
	fi

	if [ "$dumpstatus" -ne 0 ]; then
		echo ""
		echo "*** Problem trying to dump the web database."
		if [ -z ${noprompt} ]; then
			echo -n "Should we continue ? [y/N] "
			read continue
		fi
		if [ -z ${continue} ]; then
			exit 1
		fi
		if [ ${continue} ="n" -o ${continue} ="N" ]; then
			exit 1
		fi
	else
		echo "OK."
	fi

else
	echo -n "Dumping out the web database .. "

	webdumpname="${mysourcedir}/web-database_${currentdate}_${currenttime}.dump"

	# Now we have the db details, let's dump them out.
	if [ -z "$web_pass" ]; then
		mysqldump --add-drop-table -u ${web_user} -h ${web_host} ${web_db} > ${webdumpname}
		dumpstatus=`echo $?`
	else
		mysqldump --add-drop-table -u ${web_user} --password=${web_pass} -h ${web_host} ${web_db} > ${webdumpname}
		dumpstatus=`echo $?`
	fi
	if [ "$dumpstatus" -ne 0 ]; then
		echo ""
		echo "*** Problem trying to dump the web database."
		if [ -z ${noprompt} ]; then
			echo -n "Should we continue ? [y/N] "
			read continue
		fi
		if [ -z ${continue} ]; then
			exit 1
		fi
		if [ ${continue} ="n" -o ${continue} ="N" ]; then
			exit 1
		fi
	else
		echo "OK."
	fi

	echo -n "Dumping out the user database .. "
	userdumpname="${mysourcedir}/user-database_${currentdate}_${currenttime}.dump"

	# Now we have the db details, let's dump them out.
	if [ -z "$user_pass" ]; then
		mysqldump --add-drop-table -u ${user_user} -h ${user_host} ${user_db} > ${userdumpname}
		dumpstatus=`echo $?`
	else
		mysqldump --add-drop-table -u ${user_user} --password=${user_pass} -h ${user_host} ${user_db} > ${userdumpname}
		dumpstatus=`echo $?`
	fi

	if [ "$dumpstatus" -ne 0 ]; then
		echo ""
		echo "*** Problem trying to dump the user database."
		if [ -z ${noprompt} ]; then
			echo -n "Should we continue ? [y/N]"
			read continue
		fi
		if [ -z ${continue} ]; then
			exit 1
		fi
		if [ ${continue} ="n" -o ${continue} ="N" ]; then
			exit 1
		fi
	else
		echo "OK."
	fi
fi

mysourcesystem=`basename ${mysourcedir}`
if [ -z ${backupfilename} ]; then
	tarname="${mysourcesystem}_${currentdate}_${currenttime}.tar.gz"
else
	tarname=${backupfilename}
fi

if [ -n ${backupdir} ]; then
	lastchar=`echo ${backupdir} | awk -F'/' '{ print $(NF) }'`
	if [ -z ${lastchar} ]; then
		tarname="$backupdir$tarname"
	else
		tarname="$backupdir/$tarname"
	fi
fi

echo "Tarring up $mysourcedir to $tarname ... "

tar -zcf ${tarname} ${mysourcedir} --exclude=${mysourcedir}/cache/*
tarstatus=`echo $?`
if [ "$tarstatus" -ne 0 ]; then
	if [ -z ${noprompt} ]; then
		echo ""
		echo "*** Problem tarring up the directories."
		echo -n "Should we continue ? [y/N] "
		read continue
	fi
	if [ -z ${continue} ]; then
		exit 1
	fi
	if [ ${continue} ="n" -o ${continue} ="N" ]; then
		exit 1
	fi
else
	echo "OK."
fi

echo -n "Cleaning up .. "
# Now clean up after ourselves.
if [ -n "${dumpname}" -a -f "${dumpname}" ]; then
	rm -f "${dumpname}"
fi
if [ -n "${webdumpname}" -a -f "${webdumpname}" ]; then
	rm -f "${webdumpname}"
fi
if [ -n "${userdumpname}" -a -f "${userdumpname}" ]; then
	rm -f "${userdumpname}"
fi

echo "Done."

exit 0
