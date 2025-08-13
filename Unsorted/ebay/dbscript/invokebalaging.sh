#!/bin/sh
#. /oracle04/export/home/www/.kshenv
#cd ./notices
case $# in
1) # log we have started
    echo `date` do-state-BalanceAging started. >> cron.log
       BalanceAging $1
#log we are done
    echo `date` do-state-BalanceAging ended. >> cron.log
;;

6) #reprocess lets get PID, sed takes out no from no rows returned
#INVOICE_TIME=`echo $2 |sed 's/x/ /'`
INVOICE_TIME=$2
#echo $2
INVOICE_MONTH=`echo $INVOICE_TIME |sed 's/..\(..\)-\(..\)-..-..:..:../\2\/\1/'`
#echo $INVOICE_MONTH

PID_NAME=`sqlplus -s ebayqa/pipsky @invandbalaging_getpid.sql $INVOICE_TIME $4 $6 2 |sed 's/ .*$//'`
        if [ $PID_NAME = "YES" ]; then
          echo `date` do-state-BalanceAging for $4 - $6 already done. >> cron.log
        elif [ $PID_NAME = "no" ]; then
          echo `date` do-state-BalanceAging for $INVOICE_TIME $4 $6 bad parameters. >> cron.log
        else
          #we got pid from oracle, see if it still running
          STATE=`/usr/bin/ps -p $PID_NAME -o comm= |cut -c1-12`
          if [ -n "$STATE" ]; then
            if [ $STATE = "BalanceAging" ]; then
              echo `date` do-state-BalanceAging for $4 $6 is still running.PID is $PID_NAME >> cron.log
            else
              sqlplus -s ebayqa/pipsky @invandbalaging_updatepid.sql $INVOICE_TIME $4 $6 2
              #process has another name - actually call invoice
              echo `date` do-state-BalanceAging $4 $6 started. >> cron.log
              BalanceAging $1 $INVOICE_MONTH $3 $4 $5 $6
              #log we are done
              echo `date` do-state-BalanceAging $4 $6 ended. >> cron.log
            fi
          else
            #process number not found amoung living -we can call invoice
            sqlplus -s ebayqa/pipsky @invandbalaging_updatepid.sql $INVOICE_TIME $4 $6 2
            echo `date` do-state-BalanceAging $4 $6 started. >> cron.log
            BalanceAging $1 $INVOICE_MONTH $3 $4 $5 $6
            #log we are done
            echo `date` do-state-BalanceAging $4 $6 ended. >> cron.log
          fi
        fi
;;

*) echo "error "
;;
esac
