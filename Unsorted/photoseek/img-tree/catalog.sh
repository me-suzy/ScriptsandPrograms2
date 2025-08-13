#!/bin/bash
#  catalog.sh
#    catalog a repository by number, used as a helper script for
#    Photoseek
#
#  code : jeff b (jeff@univrel.pr.uconn.edu)
#  lic  : GPL, v2

# ///////////////////////////////////////////////////////////////////
#                Set your database variables here
# ///////////////////////////////////////////////////////////////////

ADMIN_USER="admin"
ADMIN_PASS="password"
PHOTOSEEK_URL="http://localhost/photoseek"
LOG_FILE="/var/log/photoseek.log"


# ///////////////////////////////////////////////////////////////////
#                   don't modify the code below
# ///////////////////////////////////////////////////////////////////

CURRENT_DATESTAMP=`date`
touch $LOG_FILE
echo "PhotoSeek Cataloging Repository $1"                 >> $LOG_FILE
echo "$CURRENT_DATESTAMP"                                 >> $LOG_FILE
echo " ------------------------------------------------ " >> $LOG_FILE
wget --http-user=$ADMIN_USER --http-passwd=$ADMIN_PASS -T 0 -t 0 -nv \
     -O -         $PHOTOSEEK_URL/discoverRepository.php?repository=$1 \
     2>&1 >> $LOG_FILE
echo " ==================== done ====================== " >> $LOG_FILE
echo ""                                                   >> $LOG_FILE
