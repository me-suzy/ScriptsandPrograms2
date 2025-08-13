#! /bin/sh

perl trim.pl
rcp Pictures.txt cooter.ebay.com:d:/updates/`date +%y%m%d%H%M`.txt
