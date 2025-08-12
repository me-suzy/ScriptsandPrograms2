#!/bin/sh
# workaround für das Deployment, damit die
# folgenden Datein nicht automatisch geupdated werden.

rm config/easyfaq.config.php.default
rm config/config.inc.php.default

rm -R custom
rm -R doc

rm -R extern/nusoap
rm -R extern/OLE
rm -R Spreadsheet_Excel_Writer

rm -R javascripts/extern

rm -R modules/contacts
rm -R modules/docs
rm -R modules/notes
rm -R modules/stats
rm -R modules/tickets
rm -R modules/todos
rm -R modules/workflow

rm -R tests
rm -R webservices

