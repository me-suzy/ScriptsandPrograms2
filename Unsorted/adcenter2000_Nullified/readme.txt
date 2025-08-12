1. INSTALLATION NOTES:

1.1. Configure adc.cfg file
     $owntitle - your exchange system title (name)
     $sysid - specify operating system of your server. Set it to "Unix" if
              you set up system on Unix based server, or set it to "Windows"
              if on WinNT based server. Please, note, if you are using Apache
              Web server on WinNT server, you must set it to "Unix".
     $adcpath - specify full path to ADC2000 root directory
     $basepath - path to databases directory. Its a path to directory where
                 you places files from /bases directory. Please, note, you
                 must place your databases in the directory, not accessible
                 via Web for security reason.
     $adcenter - specify full url to ADC2000 root directory
     $cgi - specify full url to the directory where you placed all *.pl files
            for ADC2000
     $progmail - path to sendmail program on your server (use it with -t option
                 if available). If your server is NT based and you havent
                 sendmail, ask us for free copy of SimpleMail program. System
                 will send messages via sendmail program only if SMTP Server
                 variable is "NULL" (empty).
     $smtpserver - specify host name of your SMTP server. Leave empty if you
                   will use sendmail for mail sending.
     $smtpport - port for connection to your SMTP server. Usually, 25.
     $email - your email address
     $furl - full url to your site
     $yourname - specify account username what will be your default exchange
                 account. Please, note, you must create account before and
                 upload banners for all spots and services
     $reclimit - how many records will be placed per page for numerated data
     $gmtzone - Difference between your local time your want to use with
                system and GMT

     Cheat analyzer variables:
     $logrefererfault - Log event if system cant resolve referer page
                        (URL where banner was shown/clicked) (1-yes,0-no)
     $logagentfault - Log event if system cant resolve user agent (browser
                      of visitor) (1-yes,0-no)
     $logallowimpperbrowser - How many banners can be showed for one browser
                              per session (until it will be closed) without
                              logging
     $logallowclcperbrowser - How many banners can be clicked from one browser
                              per session (until it will be closed) without
                              logging
     $logallowipperbrowser - How many IPs can be used by one browser per
                             session (until it will be closed) without logging
     $logcookieduplicates - In fact it cant be duplicated, because each banner
                            call using randomize number (banner session).
                            If you'll find it - there is big probability of
                            cheating by software (99,9% of programs cant
                            generate random numbers, and without this parameter
                            banner will not be shown) (1-yes,0-no)
     $minctr - Min allowed value for users CTR (click/impressions*100 %)
     $maxctr - Max allowed value for users CTR (click/impressions*100 %)

     Banner exchange variables:
     $defaultreason - specify default reason for banner rejecting
     $defaultlanguage - if language is not specified, default language will
                        be used
     $defaultratio - Default ratio for banner exchange. Showing how many
                     impression credits user earn for each banner impression
                     on his site. "1:2" means - user will earn 1 credit for
                     2 impressions
     $clickratio - Default click ratio for banner (or click) exchange. Showing
                   how many impression (or click - if click exchange is
                   enabled) credits user earn when someone click on banner
                   shown on his site.
     $refratio - showing how many impression credits user get for each
                 impression on the site of user refered by him.
     $enablece - Set it to 1 if enabled, 0 if disabled. Allow users to earn
                 click credits.
     $weighttype - how many records this account will has in banner pool
     $startcred - how many impression credits user get when register with
                  exchange system
     $bxalt - ALT text for banners
     $totalbanner - set here number of banner spots you purchased, do not set
                    it more, or system will not work properly.
     @banwidth - Width for each banner spot, delimeted by comma
     @banheight - Height for each banner spot, delimeted by comma
     @enablemb - Enable (1) of disable (0) minibanner for each banner spot,
                 delimeted by comma
     $mbanheight - height for minibanner
     @mfilesize - Max allowed weight for each banner spot, delimeted by
                  comma (bytes)

     SwimBanner exchange variables:
     $defaultratiosb - Default ratio for swimbanner exchange. Showing how
                       many impression credits user earn for each banner
                       impression on his site. "1:2" means - user will earn
                       1 credit for 2 impressions
     $clickratiosb - Default click ratio for banner (or click) exchange.
                     Showing how many impression (or click - if click exchange
                     is enabled) credits user earn when someone click on
                     banner shown on his site.
     $enableswim - Enable (1) or disable (0) this service. Do not enable it if
                   you not purchased module for this service, or system will
                   not work properly
     $enablecesb - Set it to 1 if enabled, 0 if disabled. Allow users to earn
                   click credits.
     $startcredsb - how many impression credits user get when register with
                    exchange system
     $sbsize - Max allowed weight for banner (bytes)
     $sbwidth - Width for banner (pixels)

     TX variables:
     $defaultratiotx - Default ratio for text exchange. Showing how many
                       impression credits user earn for each banner impression
                       on his site. "1:2" means - user will earn 1 credit for
                       2 impressions
     $clickratiotx - Default click ratio for banner (or click) exchange.
                     Showing how many impression (or click - if click exchange
                     is enabled) credits user earn when someone click on
                     banner shown on his site.
     $enabletex - Enable (1) or disable (0) this service. Do not enable it if
                  you not purchased module for this service, or system will
                  not work properly
     $enablecetx - Set it to 1 if enabled, 0 if disabled. Allow users to earn
                   click credits.
     $startcredtx - how many impression credits user get when register with
                    exchange system
     $stxw - Min allowed width for tickerline (pixels)
     $etxw - Max allowed width for tickerline (pixels)

     Counter variables:
     $enablecounter - Enable (1) or disable (0) this service. Do not enable it
                      if you not purchased module for this service, or system
                      will not work properly
     $counteralt - ALT text for counter picture.

     Maillist variables:
     $enablemaillist - Enable (1) or disable (0) this service. Do not enable
                       it if you not purchased module for this service, or
                       system will not work properly

1.2. Edit first line of each *.pl file to path to Perl on your server

1.3. Upload all files and directories in ASCII mode (except images)

1.4. Set permissions:
     /bannersX - 777
     /bases - 777
     /bases/adcenter.pwd - 666
     /bases/category.db - 666
     /bases/country.db - 666
     /bases/domains.db - 666
     /cgi-bin/adc2000ng/*.pl - 755
     /cgi-bin/adc2000ng/adc.cfg - 666
     /faq - 777
     /queye - 777 (also set 777 to all subdirectories under /queye directory)
     /sb - 777
     /tx - 777

1.5. Join the exchange form index page and create default account. Banners for
     this account will be shown when system cannot show other banners from
     the system. Write down username of your default account. You will need it
     on the next step of installation

1.6. Login to admin section from admin.html using "admin" as login and
     "password" as password

1.7. Go to General settings and change Default internal account to username you
     just written

1.8. System is ready


2. HOW TO MODIFY DESIGN

2.1. All HTML templates are kept in /template directory. You can modify these
     files with notepad or any other text editor. Also you can use HTML editor,
     but if this editor not re-format document. 

2.2. Admin section templates - /template/*.tpl

2.3. Members section and common templates - /template/english/*.tpl


3. HOW TO MODIFY MAIL MESSAGES FOOTER AND HEADER

3.1. All footers and headers are kept in /mail directory and can be modified
     with any text editor


4. HOW TO TRANSLATE OR ADD YOUR LANGUAGE

4.1. Copy all files from /template/english directory to your local hard drive

4.2. Translate these files

4.3. Create subdirectory /your_language (where your_language is unique
     identifier for new language) under /template directory and copy there
     translated files

4.4. Copy files english.* from /langpack directory to your local drive

4.5. Translate these files

4.6. Rename translated files to your_language.* and copy to /langpack directory

4.7. Copy images from /images/english directory to your local drive

4.8. Translate these files

4.9. Copy translated images to /images/your_language directory

4.10. Create a link in index.html page to your_language part of system. For
     example:
     <a href="/cgi-bin/adc2000ng/adcindex.pl?lang=your_language">your_language</a>


5. HOW TO ADD ADDITIONAL BANNER SPOTS (GROUPS) OR ADDITIONAL MODULES

   Contact with us by email: trxx@trxx.co.uk
   or phone: +7 8112 468895
