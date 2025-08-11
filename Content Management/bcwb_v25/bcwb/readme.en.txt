Readme.En.txt

BCWB: Business Card Web Builder
Copyright © 2004 Dmitry Sheiko
Project site: http://bcwb.cmsdevelopment.com


CONTENT

Content					1
Introduction				1
ADVANTAGES				1
System requirements			2
Installation				2
GETTING STARTED (Hello world!)		2
REPRESENTATION PATTERNS CREATION	3
SPECIAL features			3
Possible faults				3
CONCLUSION				4


INTRODUCTION

First of all I would like to thank you for the shown interest to my development. BCWB is freely distributed according to GNU license conditions software with an open code (Open Source) (see a file Licence.Ru in a complete set of delivery).The program represents a WYSIWYG- content management system (CMS) for web  visit cards, personal sites, promo-sites, VIP - sites and other small web - projects. I have been engaged in development of web-content management systems for corporate information spaces for a long time. However it was unreasonable to use any of similar systems for personal site realization. Therefore I have developed diminutive WCM-system with minimal system requirements. BCWB does not require database presence. However I wanted to develop flexible and universal system. In result I have chosen XML format as a data carrier and XSLT as management of data presentation (http://www.w3.org/TR/xslt). As a result, a website assembled in BCWB can be given any representation form with the help of XSLT full functionalities including vector-graphical representation in SVG format. (http://www.w3.org/TR/SVG11/). Besides XML is universal and you can operate in BCWB with site content displayed, for example, in Macromedia Flash. And certainly the system should satisfy functional requirements of personal site. All these tasks are successfully solved in BCWB.

More detailed information about BCWB you can read on my site www.cmsdevelopment.com

ADVANTAGES

·	Minimal requirements to a hosting - provider (Apache+PHP+200KB of disk space for BCWB + content size of your site). Economy on an inexpensive tariff plan for your site.
·	Easy contents management. Direct visual editing (WYSIWYG) of site pages. There is an interface of images and files addition and loading and latent enclosed pages creation.
·	Qualitative pages addressing. Each documents URL correctly reflects its hierarchical belonging.
·	Modularity. You can set the necessary content blocks number on the page independently together with an accompanying XSLT pattern.
·	Universal language (XSLT) of management displayed data formatting.
·	Independent from platform format of data presentation. Publication in SGV/SMIL, Flash, PDF.
SYSTEM REQUIREMENTS
Server:
- 	Apache Web Server / with on mod_rewrite / (http://www.apache.org)
-	PHP version 4.1.x  (http://www.php.net)
-	It is recommended Sablotron (http://www.gingerall.com/) (xslt extension for PHP) 


The system correctly works both on platform UNIX and under Windows management 
- TESTS WERE CARRIED OUT ON HOSTING - PROVIDER SERVERS http://www.friendlyhosting.net

Client: 
-	MS Internet Explorer 5.x (It is recommended to use version 6)

INSTALLATION 
ATTENTIVELY FOLLOW INSTRUCTIONS AND YOU WILL COLLECT YOU VISITING WEB - CARD IN A FEW MINUTES.

Create a domain (subdomain) for a projected site. Unpack archive bcwb.zip in its HTTPDOCS-folder. Change values of variables configuration in config.inc.php file. 

$http_path  http-site address (complete with a slash) 
$default_charset  coding
$FEEDBACK_EMAIL  your email
$admin_login login for content management
$admin_password  password for content management
$admin_subdomain  subdomain name for authorization in administration field (by default "login")

Now you need to establish access rights for dcontent folder and its files 777 as well as for file script/structure.inc.php attribute 777.

GETTING STARTED (Hello world!)

Type in browser address line URL of your future site.
If you have correctly executed installation you see page Hello World!.
To add site structure and content it is necessary to proceed in administrative area.
For this purpose add to you site address "login" and press Enter.
On question of data authorization enter data that you have specified in config.inc.php.
In case of successful authorization you will see system menu.
For editing current page content choose "Edit", for new page addition press «Add section », for creation of an enclosed page in a current section press «Create subsection ».
It is possible to choose transition "Structure" for viewing site structure. During editing page you can use menu of a visual editor (a button "+"). A button "Image" allows inserting and loading an image from a disk, the button "File" allows to load a file and to insert into the content reference to it, "document" allows creating reference to enclosed page which is not displayed in menu. It is necessary to proceed at reference for editing the enclosed page.

BCWB partly supports syntax of a references marking Wacko Wiki (http://wiki.oversite.ru). You can set new references in syntax: ((variable Reference content)).
REPRESENTATION PATTERNS CREATION

You can create unlimited amount of own XSLT-patterns and appoint them as site pages. Patterns are placed in dcontent folder (see patterns of examples). After addition of file with expansion .xsl to folder it will be displayed while page editing in opening list.
You can set site pages display format independently. However you should add special BCWB tags to each pattern:

<BCWB form=start /> - form opening in the beginning of document
<BCWB form=finish /> - closing at the end of document
<BCWB content=header /> - reference on page heading
<BCWB content=any variable /> - reference on content block
<BCWB content= any variable /> - additional reference on content block

If you need to place Feedback-forms use XSLT-pattern example. The function which is carrying out Email sending is located in file include/startup/common_functions.inc.php.

You can use SVG marking in XSLT-pattern, it will allow to organize page scaled graphic representation.

Official information about XSLT http://www.w3.org/TR/xslt
Official information about SVG http://www.w3.org/TR/SVG11/
Examples of XSLT use http://www.zvon.org/download2.php/XSLTutorial?title=XSLT+Tutorial
Examples of SVG use http://www.zvon.org/download2.php/svgReference?title=SVG+Reference


SPECIAL FEATURES
You can operate content of Flash-site through BCWB. For this purpose specify in config.inc.php value $http_path = http://yoursite.com/noxsl/. In data presentation mode BCWB will show XML-structure to which you can be addressed from Flash (see http://macromedia.com)

To task system correct heading for specialized document type a prefix in a variable (URL) of page is used. For example http://site.com/svg_about/

You can organize an additional display format for any page. For example «print version». For this purpose to current page reference add a name of XSLT-pattern after slash.

POSSIBLE FAULTS
If a browser informs about not found page, try to specify in .htaccess a full route to a script index.php. Check up value $root_path.

There is HTML2XHTML converter processing data from WYSIWYG forms in BCWB system. However complex HTML designs the converter can ignore. In that case the system will return XHTML mistake and you should create new page avoiding complex HTML designs.
CONCLUSION
I would be grateful for new language interface localizations (/lang/ÿçûê.inc.php). With big pleasure I will take into consideration and place on central BCWB site references on interesting projects collected on BCWB platform. Certainly, the reference on your site is welcome <a href=http://bcwb.cmsdevelopment.com>Powered by BCWB</a>.

******************************************
* BCWB URL: http://bcwb.cmsdevelopment.com  *
******************************************

I wish you all successes in all your creative initiatives!

-- Dmitry Sheiko (sheiko@cmsdevelopment.com)
