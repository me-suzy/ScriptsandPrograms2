#!/bin/sh
# workaround für das Deployment, damit die
# folgenden Datein nicht automatisch geupdated werden.

rm config/easyfaq.config.php.default
rm config/treemanager.config.php.default
rm config/config.inc.php.default
rm config/.cvsignore

rm -R deployment
rm -R custom
rm -R doc
rm -R extern/nusoap
rm -R extern/OLE
#rm -R Spreadsheet_Excel_Writer
rm -R javascripts/extern
rm -R modules/docs
rm -R modules/stats
rm -R modules/pics_0.0.1
rm -R modules/simpleOffers_0.0.1



rm -R tests
rm -R webservices

