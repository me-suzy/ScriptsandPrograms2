#!/bin/sh
. /oracle04/export/home/www/.kshenv
cd ./notices
case $# in
0) # log we have started
    echo `date` do-state-eoa started. >> log/cron.log
       EndOfAuctionNotice.new
#log we are done
    echo `date` do-state-eoa ended. >> log/cron.log
;;

2) #reprocess lets get PID, sed takes out no from no rows returned
PID_NAME=`sqlplus -s ebayqa/pipsky @get_pid.sql $1 $2 |sed 's/ .*$//'`
        if [ $PID_NAME = "YES" ]; then
          echo `date` do-state-eoa for $1 $2 already done. >> log/cron.log
        elif [ $PID_NAME = "no" ]; then
          echo `date` do-state-eoa for $1 $2 bad parameters. >> log/cron.log
        else
          #we got pid from oracle, see if it still running
          STATE=`/usr/bin/ps -p $PID_NAME -o comm= |cut -c1-12`
          if [ -n "$STATE" ]; then
            if [ $STATE = "EndOfAuction" ]; then
              echo `date` do-state-eoa for $1 $2 is still running.PID is $PID_NAME >> log/cron.log
            else
              sqlplus -s ebayqa/pipsky @update_pid.sql $1 $2
              #process has another name - actually call eoa
              echo `date` do-state-eoa $1 $2 started. >> log/cron.log
              EndOfAuctionNotice.new -s $1 -e $2
              #log we are done
              echo `date` do-state-eoa $1 $2 ended. >> log/cron.log
            fi
          else
            #process number not found amoung living -we can call EOA
            sqlplus -s ebayqa/pipsky @update_pid.sql $1 $2
            echo `date` do-state-eoa $1 $2 started. >> log/cron.log
            EndOfAuctionNotice.new -s $1 -e $2
            #log we are done
            echo `date` do-state-eoa $1 $2 ended. >> log/cron.log
          fi
        fi
;;

*) echo "error "
;;
esac
