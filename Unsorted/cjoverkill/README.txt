##################################
#                                #
#   CjOverkill 2.0.1             #
#                                #
##################################


Copyright Kaloyan Olegov Georgiev
ice [at] icefire.org

This script has limited open source lisence. Read LICENSE at the end of this
file for more info.
If you are a developer or you need to modify the script then you must read
tle license before continuing.


##################################
#                                #
#   REQUIREMENTS                 #
#                                #
##################################



1) Any PHP capable web server (Apache is OK)
2) PHP 4.1.2 or new version. PHP 4.2.2 or newer recommended.
3) MySQL database 3.23.x
4) A valid database login and password


##################################
#                                #
#   INSTALLATION                 #
#                                #
##################################

Extract the GZIP file with the path names intact. (If using WinZIP, make sure
'Use Folder Names' is checked).
Upload all of the files to your home directory keeping the path names
intact. (Upload with all files in the proper directories)
- Be sure to upload all .php and .css files in ASCII mode (Most FTP clients
do that by default when detecting an ASCII file).
Chmod "toplist" directory to 777.
Chmod "toplist" directory contents (toplist templates) to 666 or 777
Chmod "cj-conf.inc.php" to 666 or 777.
Go to cjoverkill-install.php and follow the instructions on the page.

After you have finished the installation delete cjoverkill-install.php,
cjoverkill-update.php and the directory cjoverkill_filter_base and all it's
contents.

If you are using .SHTML pages then:
Add 
<!--#include file="in.php" -->
as the first line of your main page.

If you are using .PHP pages then:
Add:
include ("in.php");
at the beginning of your index.php file.

Login to your CjOverkill admin at:
http://www.YOURDOMAIN.com/cjadmin/index.php
and setup trades.

##################################
#                                #
#   UPDATE                       #
#                                #
##################################

Read the file UPGRADE.txt

##################################
#                                #
#   LICENSE                      #
#                                #
##################################

BY INSTALLING OR USING CjOverkill SOFTWARE "PRODUCT", YOU CONSENT ON BEHALF OF
YOURSELF AND/OR THE ENTITY YOU REPRESENT TO BE BOUND BY, AND BECOME A PARTY
TO, THIS AGREEMENT "LICENSE" AS THE "LICENSEE". IF YOU DO NOT AGREE TO ALL OF THE
TERMS OF THIS AGREEMENT, YOU MUST NOT INSTALL CjOverkill OR USE THE
CjOverkill SOFTWARE, AND YOU DO NOT BECOME A LICENSEE UNDER THIS AGREEMENT.

1) THIS PRODUCT IS PROVIDED BY Kaloyan Olegov Georgiev "THE AUTHOR" "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

2) This version of the LICENSE supersedes any prior versions.

3) LICENSEE of the PRODUCT (all versions, including any beta versions,
inclomplete, damaged, hacked or downloaded from a non official site
versions) must accept this license agreement in full and the disclaimer.

4) LICENSEE is strictly prohibited from redistributing the source code or
precompiled code of the PRODUCT unless LICENSEE has a written permission by
THE AUTHOR.

5) LICENSEE may attempt to reproduce or alter the source code of the PRODUCT
in order to adapt the code to his particular needs. IN NO CASE LICENSEE CAN
SELL PRODUCT OR PRODUCT DERIVATES.

6) If LICENSEE alters the PRODUCT code, then LICENSEE is not allowed to
distribute the altered code or leave this code to a third party persons or
entities unless point #7 and point #8 requerements are met.

7) If LICENSEE wants to distribute altered copies of the PRODUCT, then
LICENSEE must have the written consentment of THE AUTHOR of the PRODUCT and
depending on the modifications point #8 will need to be met or not.

8) LICENSEE is strictly prohibited from removing the 1% traffic payload or
the ads code present in the trades section of the PRODUCT.

9) By modifying the PRODUCT LICENSEE agrees to submit the altered code to
THE AUTHOR in order it to be reviewed and perhaps included in future
releases of the PRODUCT. In this case LICENSEE must provide some information
if LICENSEE wants his name to appear in the PRODUCT developement team credits.

10) LICENSEE may terminate this license agreement at any time provided that
LICENSEE destroy all copies of the PRODUCT. This LICENSE will automatically
terminate if LICENSEE fails to comply with any part of the agreement, at
which time, LICENSEE must destroy all copies of the PRODUCT.
THE AUTHOR, at any time, may terminate this LICENSE agreement, in which case
LICENSEE must destroy all copies of the PRODUCT.

11) LICENSEE agrees that the use of any version of the PRODUCT that has
removed payload traffic or ads code in the administration area (use of
hacked versions, tricked versions or non offical and unnaproved by THE
AUTHOR versions of the PRODUCT that have removed and/or modifyed payload
traffic and/or ads code without THE AUTHOR's authorization are included here)
WILL NOT TAKE LEGAL (OR OTHER) ACTIONS OF ANY KIND AGAINST THE AUTHOR if THE
AUTHOR executes his right to destroy all the versions of the PRODUCT that
met the mentioned unathorized modifications. This action could lead but not
limited to any kind of data damage, data loss, network saturation and third
party software used by LICENSEE security defeat.

12) WHEN DISTRIBUTING OFFICIAL OR ANY KIND OF MODIFYED VERSIONS (NON
AUTHORIZED MODIFYED VERSION ARE INCLUDED TOO) OF THE PRODUCT THIS LICENSE
AGREEMENT MUST BE INCLUDED INTACT.

