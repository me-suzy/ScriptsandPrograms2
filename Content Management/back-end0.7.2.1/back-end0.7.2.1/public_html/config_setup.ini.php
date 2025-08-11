; <?php die(); DO NOT REMOVE THIS LINE
;;;;;;;;;;
;; config.ini.php - Configuration File for Back-End and PHPSlash
;; $Id: config_setup.ini.php,v 1.23 2005/05/25 15:41:47 mgifford Exp $
;;;;;;;;;;

;;;;
;; Site determinant variables
basedir  = "<PUBLIC_DIR>"
classdir = "<CLASS_DIR>"
rooturl  = "<ROOT_DIR>"

;; The directories and URLs below have default values based on the above
;;
; templatedir =      ;; Default: ${basedir}/templates
; adminurl    =      ;; Default: ${baseurl}/admin
; imageurl    =      ;; Default: ${rooturl}/images
; localedir   =      ;; Default: ${classdir}/locale
; phplibdir   =      ;; Default: ${classdir}/phplib/php/
; peardir     =      ;; Default: ${classdir}/pear


;; Subsites

;; Domain/Sub-domain information
;; If you use $_BE['languagedomains'], in BE_config.php, you will need to define
;; rootsubdomain and have those subdomains working on your webserver
;; NOTE: the rootdomain localhost doesn't authenticate.  A '.' is presently required
rootdomain     = "<ROOT_DOMAIN>"            ; Overrridden if you set $_BE['languagedomains']
rootsubdomain  = "<ROOT_SUB_DOMAIN>"        ; NB Include trailing '.' if needed (eg 'www.')

;;;
;; Database variables
DB_Host     = "<DB_HOST>"
DB_Database = "<DB_DB>"
DB_User     = "<DB_USER>"
DB_Password = "<DB_PASSWORD>"

;;;
;; RSS Exporter Values:
site_name = "Back-End CMS"
site_owner = "webmaster@back-end.org"
site_slogan = "An all-CSS, templated, XHTML-compliant content management system"
site_title = "Back-End CMS"

;;;
;; randomization
magic = "<RANDOMIZATION>"

;;;
;; Caching of viewed page
;; off - no output cache done
;; internal - output cache using either database or file storage
;; static - output cache written as html files to the basedir.(still in development)
jpcache.enable = off

;;;;;
;; Optional Configuration
defaultskin = "allcss";
authmode = "reg"  ; could also be log
show_admin_on_navbar = true



;;;
;; Comment Options
comment_defaultmode = "nested"
default_pending = false
commentnotify = false

;;;
;; Poll Options
poll_min_answers = 8

;;;
;; Search Page Options
search_maxresults = 10
allow_comment_search = true

;;;
;; Block Options
block_optioncount = 4

;;;
;; Homepage options
;; Shared with BACK-END
site_homesection = "Home"
home_section_id = 1
mainpage = "index.php"



;;;
;; Debug options
;debug = true
debug_type                 = "now" ; log | mail
; debug.mailto               = name@example.com
debug.max_recursion_level  = 2     ; 10
debug.escape_html          = true
debug.templates            = true
debug.templates.logPath    = "/tmp/templatesUsed.txt"

;;;
;; Informational Logging
;; Log messages from Back-End to administrators in the database
use_infolog = true


;; ******************************
;; **** BACK-END OPTIONS     ****

;; rootdomain & rootsubdomain were moved up to the top of this file with the other critical info.

;; Specifify authorisation mechanism
;; 1:MySQL System Database (default if not specified)
;; 2:LDAP
auth_type = 1

;; LDAP Settings - uncomment and customise if auth_type has been set to 2
;LDAP_Host         = "localhost"
;LDAP_Port         = "389"        ; default LDAP port # 389
;LDAP_Base_dn      = "dc=localhost,dc=back-end,dc=org"
;LDAP_Search_detail= "cn"
;LDAP_BE_uid       = "uid"        ; name of uid in the ldap that coresponds to BE uid

;; user and password for updating LDAP directory
;LDAP_edit_user     = ""
;LDAP_edit_user_pass= "secret"
;LDAP_edit_user_dn  = "cn=Manager,dc=waiter,dc=thepinecone,dc=com"

;; If this is a new configuration, leave this set to true.
;; Otherwise set it to false for better performance.
newconfig = true

;; As of BE 0.7.1.4 locale files will not have htmlentites applied to them
;; if you already have language files defined, you can enable them here
locale2htmlentities = false

;; What should be the default cache expiry time (seconds)?
cacheExpiryTime = 86400

;; If off, filecaching is disabled (useful for seeing changes instantly when debugging)
filecache.enable = on

;; **** END BACK_END OPTIONS ****
;; ******************************


;;;
;; Locale Options
;;
;; Default language ( ISO format)
language = "en"

[locale]
;; Use this locale for formatting date and time strings
;; (DEFAULT ONLY - if language is changed, this changes accordingly)
LC_TIME = "en_CA"

[LC_TIME_ARY]
en = "en_CA"
fr = "fr_CA"

;;;
;; Timezone Options
[timezone]
;; Set this to something true if you want times to be calculated in a
;; different time zone.
engine = false ; true
;;
;; Now you have to choose the implementation of time zones.  If you are
;; on a UNIX platform and can set environment variables, native is probably
;; the implemention for you.
native = false ; Windows
;native = true
;;
;; Otherwise, you can use the time zone classes which interface the
;; time zone database, if you have one.  Specify the directory here (good
;; to do even if you are using native time zones):
;dir = "/usr/share/zoneinfo"
;;
;; If you can't do that either, you are stuck specifying time zones as
;; POSIX-compatible strings.  In any case, you need to specify the
;; server's default time zone.  If you are using native timezones and
;; want the default to be the actual server's setting, enter it this
;; way (find the right path):
; default = ":/etc/localtime";
;;
;; If you are not using native timezones, but are using the time zone
;; database, specify a meaningful time zone name here.  You can also use
;; this to fake the server's time zone.
;name = "America/Chicago";
;;
;; Finally, if all you are able to do is specify a string, do it like this:
name = "GMT+0BST,M4.1.0/1,M10.5.0/2";


;;;
;; Metatag Definitions
[metatags]
keywords = "Back-End, phpSlash, CMS, blog, weblog, content management, php, open source, free, download, XHTML, CSS, templated, ttw, htmlarea3"
description = "An all-CSS, templated, XHTML-compliant content management system"


;;;
;; phpSlash Modules
[module]

Comment     = true

NavBar      = true
Poll        = true

Infolog     = true
Author      = true

Group       = true
About       = true
Variable    = false ; Optional
Glossary    = false ; Optional

;; Core Back-End Modules
BE_Language     = true
BE_Section      = true
BE_Article      = true
Block           = true

;; Optional Back-End Modules
BE_Subsite      = false  ;   Optional (extra_modules.sql required)
BE_Categories   = false  ;   Optional (extra_modules.sql required)
BE_Downloads    = true   ;   Optional - required to display inline documents
BE_Link         = true   ;   Optional
BE_EditTemplate = true   ;   Optional - Note see INSTALL.html for securty concerns.
BE_EditLocale   = false  ;   Optional Edit class/locale/*.php files
BE_Upload       = true   ;   Optional; required by BE_Bibliography - Note see INSTALL.html for securty concerns.
BE_Events       = true   ;   Optional Events Calendar
BE_History      = true   ;   Optional versioning of articles and sections
BE_BlockCache   = true   ;   Optional block caching
BE_Feedback     = true   ;   Optional feedback tool

BE_Catalog      = false  ;   Optional Resource catalog
BE_Bibliography = false  ;   Optional Library tool - Requires BE_Upload - Note see INSTALL.html for securty concerns.
BE_TAF          = true   ;   Optional Tell-A-Friend message tools

;; Back-End's eCampaign Tools
BE_Action       = true   ;   Optional; requires BE_Contact, BE_Fax, ECclient
BE_Contact      = true   ;   Optional; required by BE_Action
BE_Fax          = true   ;   Optional; required by BE_Action
ECclient        = true   ;   Optional; required by BE_Action
BE_Petition     = true   ;   Optional; requires BE_Action
BE_Followup     = true   ;   Optional; requires BE_Action

;; Advanced Search Options
BE_advancedSearch   = false   ;   Dependent on htdig.org
BE_googleSearch     = false   ;   Dependent on google's api - password required


;; Experimental modules for Back-End
BE_Survey       = false   ;   Presently Experimental
BE_Strikes      = false   ;   Presently Experimental
BE_Chat         = false   ;   Presently Experimental
BE_Register     = false   ;   Presently Experimental
BE_Gallery      = false   ;   Presently Experimental
BE_Phplist      = false   ;   Presently Experimental
phpOpenTracker  = false   ;   Presently Experimental

;;;
;; Approved HTML tags for user comments
;;
;; Default uses php's strip_tags with the approved tags, but without the qualifiers
;; In BE_config.php you can set BE to use PSL's stripBadHTML function for more control
;; 2 means accept all qualifiers: <foo bar>
;; 1 means accept the tag only: <foo>
;; Comment out tags if not in use.
[approvedtags]
p = 2
b = 2
i = 1
a = 2
em = 1
br = 2
strong = 2
blockquote = 1
tt = 1
hr = 1
li = 1
ol = 1
ul = 1
table = 2
tr = 2
td = 2
style = 2
h1 = 2
h2 = 2
h3 = 2
h4 = 2
h5 = 2
img = 2
font = 2
center = 2
div = 2
span = 2
u = 2

;; phplist integration - uncomment this if phpList has been incorporated
;[phplist]
;database_host = "phplisthostname.domain"
;database_name = "phplist"
;database_user = "username"
;database_password = "password"

; DO NOT REMOVE THIS LINE ?>
