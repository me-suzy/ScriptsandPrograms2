#!/bin/sh
echo ''
echo Please be sure that the 'java' command that is executed is a
echo true Sun JRE and not kaffe.  You may need to modify the command
echo below and specify the full path to the Sun JRE 'java' command.
echo ''
umask 022
java -jar ./jar/FCInstall.jar
