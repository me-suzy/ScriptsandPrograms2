#!/bin/sh

####################################################
# Secure script for production application developed with MyDB
# See licence.txt file
# Copyright 2002 SQLFusion LLC
####################################################

if [ -d $1 ]; then

  echo "Securing : $1 with user $2";

  cd $1

  rm *.bak -f
  rm *.copy -f
  rm */*.bak -rf
  rm */*.copy -rf

  chmod 640 * -R
  chmod a+X * -R
  chown $2.apache * -R
  if [ -d dbimage ]; then
    chmod g+w dbimage -R
  fi
  if [ -d Uploads ]; then
    chmod g+w Uploads -R
  fi
  if [ -d .data ]; then
    chmod g+w .data -R
  fi
  chmod g+w backup*
  chown $2.apache $1
  chmod 750 $1

else
  echo "usage : secureapp <approotdirectory> <username>"
fi


