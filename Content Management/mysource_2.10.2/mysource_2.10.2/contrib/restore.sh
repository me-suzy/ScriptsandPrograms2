#!/bin/sh
if [ -d backup ]; then
	MYBASE="."
else 
	if [ -d ../backup ]; then
		MYBASE=".."
	else
		exit 1;
	fi
fi

if [ -z $1 -o ! -f $1 ]; then
	echo "Backup file $1 not found, exiting";
else 
	BACKUP=`echo $1 | awk -F 'backup-' '{ print "backup-" $2 }'`
	BACKUPDIR="$MYBASE/backup"
	(cd $BACKUPDIR ; tar -xzf $BACKUP);
fi

THEN=`echo $BACKUP | awk -F 'backup-' '{ print $2 }' | awk -F '.tar.gz' '{ print $1 }'`

MYCONF="$BACKUPDIR/$THEN/mysource.conf"


WebDB=`cat $MYCONF | grep 'WebDatabase' | awk '{ print $2 }'`
WebDBHost=`cat $MYCONF | grep 'WebDatabase' | awk '{ print $3 }'`
WebDBUser=`cat $MYCONF | grep 'WebDatabase' | awk '{ print $4 }'`
WebDBPass=`cat $MYCONF | grep 'WebDatabase' | awk '{ print $5 }'`

UserDB=`cat $MYCONF | grep 'UserDatabase' | awk '{ print $2 }'`
UserDBHost=`cat $MYCONF | grep 'UserDatabase' | awk '{ print $3 }'`
UserDBUser=`cat $MYCONF | grep 'UserDatabase' | awk '{ print $4 }'`
UserDBPass=`cat $MYCONF | grep 'UserDatabase' | awk '{ print $5 }'`

# Backup the data directory
if cp -a $BACKUPDIR/$THEN/data $MYBASE ; then
	echo 'Data directory restored';
else 
	echo 'Data directory NOT restored';
fi

# Backup the mysoruce config
if cp -a $BACKUPDIR/$THEN/mysource.conf $MYBASE/conf ; then
	echo 'MySource Config restored';
else 
	echo 'MySource Config NOT restored';
fi

mysqladmin \
	--host=$WebDBHost \
	--user=$WebDBUser \
	create $WebDB

mysqladmin \
	--host=$UserDBHost \
	--user=$UserDBUser \
	create $UserDB

# Backup the web db
if [ -z $WebDBPass ]; then
	mysql \
	--host=$WebDBHost \
	--user=$WebDBUser \
	$WebDB < $BACKUPDIR/$THEN/backup-web.sql
else 
	mysql \
	--host=$WebDBHost \
	--user=$WebDBUser \
	--password=$WebDBPass \
	$WebDB < $BACKUPDIR/$THEN/backup-web.sql
fi

# Backup the user db
if [ -z $UserDBPass  ]; then
	mysql \
	--host=$UserDBHost \
	--user=$UserDBUser \
	$UserDB < $BACKUPDIR/$THEN/backup-user.sql
else 
	mysql \
	--host=$UserDBHost \
	--user=$UserDBUser \
	--password=$UserDBPass \
	$UserDB < $BACKUPDIR/$THEN/backup-user.sql
fi