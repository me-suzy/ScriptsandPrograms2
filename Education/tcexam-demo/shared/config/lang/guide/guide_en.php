<?php
//============================================================+
// File name   : guide_en.php                                     
// Begin       : 2004-06-14                                    
// Last Update : 2005-07-06                                    
//                                                             
// Description : TCExam Guide                                  
//               Language module                               
//               (contains translated texts)                   
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

/**
 * TCExam Guide :: Language module (contains translated texts)
 * @author Nicola Asuni
 * @copyright Copyright © 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2004-06-14
 */

// INGLESE - ENGLISH
?>
TCExam is a software to generate and manage online tests and exams.<br />
<br />
An e-exam is an electronic exam that can be executed by using a personal computer or an equivalent electronic device (e.g. handheld computer).<br />
<br />
The use of e-exam systems, instead of traditional paper-based tests, allows you to simplify the entire exam cycle, including generation, execution, evaluation, presentation and archiving. This simplification allows you to save time and money while
improving exams reliability.<br />
<br />
Beyond the aforementioned advantages, TCExam introduces a large number of tools and features to improve the exams total quality.<br />
<br /><a name="index" id="index"></a>

<h2>Index</h2><ul>
<li><a href="#features">Main Features</a></li>
<li><a href="#structure">Framework</a></li>
<li><a href="#install1">
Minimum Requirements</a></li>
<li><a href="#install2">Operating System Configuration</a></li>
<li><a href="#install3">
TCExam Installation</a><ul>
<li><a href="#install3_1">Automatic Installation</a></li>
<li><a href="#install3_2">
Manual Installation</a><ul>
<li><a href="#install3_2_1">Configuration Files Personalization
</a></li>
<li><a href="#install3_2_2">Database Installation</a></li></ul></li>
<li><a href="#install3_3">
Post installation</a></li></ul></li>
<li><a href="#install4">System Configuration</a></li>
<li><a href="#install5">
Access and Security</a></li>
<li><a href="#use">Use</a><ul>
<li><a href="#use_public">Public Area</a></li>
<li><a href="#use_admin">Administration Area</a></li></ul></li></ul><a name="features" id="features"></a>

<h2>Main Features</h2><ul>
<li>Automatic Installation System</li>
<li>platform-independent server-side software</li>
<li>multilanguage support</li>
<li>multilanguage support (include support for <a href="http://www.lisa.org/tmx" target="_blank" title="Translation Memory eXchange [this link will open a new browser window]">TMX</a> standard and UTF-8 Unicode)</li>
<li>based on standard and open standard technologies
(<a href="http://www.php.net" target="_blank" title="www.php.net [this link will open a new browser window]">PHP5</a>, <a href="http://www.mysql.com" target="_blank" 
title="www.mysql.com [this link will open a new browser window]">MySQL</a>, <a href="http://www.postgresql.org" target="_blank" 
title="www.postgresql.org [this link will open a new browser window]">PostgreSQL</a>, <a href="http://www.w3.org/TR/xhtml1/" target="_blank" title="XHTML 1.0 The Extensible HyperText Markup Language [this link will open a new browser window]">XHTML</a>, 
<a href="http://developer.netscape.com/tech/javascript/index.html" target="_blank" title="JavaScript Developer Central [this link will open a new browser window]">JavaScript</a>, 
<a href="http://www.adobe.com/products/acrobat/adobepdf.html" target="_blank" title="Portable Document Format (PDF) [this link will open a new browser window]">PDF</a>)</li>
<li>includes a Database Abstraction Layer with drivers for <a href="http://www.mysql.com" target="_blank" 
title="www.mysql.com [this link will open a new browser window]">MySQL</a> AND <a href="http://www.postgresql.org" target="_blank" 
title="www.postgresql.org [this link will open a new browser window]">PostgreSQL</a></li>
<li>Web-based interface
<a href="http://www.w3.org/WAI/" target="_blank" title="Web Accessibility Initiative (WAI) [this link will open a new browser window]">accessible</a> compatible with almost all modern internet browsers</li>
<li>includes a protected administration area from which it is possible to manage the whole system</li>
<li>supports different access levels both for users and resources (pages, forms, sections)</li>
<li>includes an additional security system to access the tests based on the check of the IP addresses</li>
<li>supports an unlimited number of tests, topics, questions and answers</li>
<li>supports text formatting and use of images, videos and audio, in the description of the tests, the questions and the answers</li>
<li>once defined, the topics can be used alone or together for different tests</li>
<li>supports both multiple-answer questions and free-answer questions</li>
<li>automatically calculates the cumulative score for multiple answers</li>
<li>includes a system to manually evaluate the free answers</li>
<li>generates unique tests for each user by randomly selecting questions and possible answers</li>
<li>supports timing for starting time and duration of the tests</li>
<li>can generate PDF versions of the tests so that they can be done without using a computer</li>
<li>generates reports and statistical data both in XHTML and PDF formats</li>
<li>allows the customization of the format and the headers of the PDF documents</li>
<li>supports the output of the test results for the final user</li>
<li>sends test results to users via email</li>
</ul>[<a href="#topofdoc">index</a>]<a name="structure" id="structure"></a>

<h2>Framework</h2>
TCExam is composed by three main parts:<ul>
<li>The <strong>Database</strong> where data are stored in records and tables.</li>
<li>The <strong>TCExam software</strong> as files hierarchically organized in folders and sub-folders.</li>
<li>The <strong>users</strong>, all the people that access the system, both administrators and basic users.</li></ul><img src="../../images/tcexam_structure.png" alt="TCExam Structure" name="tcexamstruct" id="tcexamstruct" width="520" height="245" border="0" /><br />
<br />The database and the TCExam system files can be located in one or more internet/intranet servers, 
and the users can access the system with a normal web browser via the internet/intranet connection.
<br /><br /><br />

<h2>File System</h2>
For of a more secure and rational structure, the TCExam program files are located in three different areas:
<br />

<h3>admin</h3>includes the administration area of the system.
<br />Only the administrators can access this area.
<br /><br /><table title="information on the sub-folder admin" border="1" cellspacing="0" cellpadding="2">
<tr><th>folder</th><th>description</th></tr>
<tr><td>code</td><td>contains the program files of the administration area</td></tr>
<tr><td>config</td><td>contains the configuration files of the administration area</td></tr>
<tr><td>doc</td><td>contains the documentation and the licences</td></tr>
<tr><td>log</td><td>contains the log files of the administration area</td></tr>
<tr><td>phpMyAdmin</td><td>contains a third-part application to manage the MySQL database</td></tr>
<tr><td>phpPgAdmin</td><td>contains a third-part application to manage the PostgreSQL database</td></tr>
<tr><td>styles</td><td>contains the CSS style sheets for the administration area</td></tr></table>

<h3>
shared</h3>Contains the resources shared between the administration system (admin) and the public site (public).
<br /><br /><table title="information on the sub-folder shared" border="1" cellspacing="0" cellpadding="2">
<tr><th>folder</th><th>
description</th></tr>
<tr><td>barcode</td><td>contains a third-part application to generate bar codes</td></tr>
<tr><td>
code</td><td>contains the shared program files (main functions of TCExam)</td></tr>
<tr><td>
config</td><td>contains the general configuration files</td></tr>
<tr><td>jscripts</td><td>contains shared javascript programs
</td></tr>
<tr><td>pdf</td><td>contains a third-part library to generate PDF documents</td></tr>
<tr><td>phpmailer</td><td>contains a third-part library for sending emails</td></tr>
</table>

<h3>public</h3>
Contains the resources accessible by the basic users.<br /><br /><table title="information on the sub-folder public" border="1" cellspacing="0" cellpadding="2">

<tr><th>folder</th><th>description</th></tr>
<tr><td>code</td><td>contains the program files of the public area</td></tr>
<tr><td>config</td>
<td>contains the configuration files of the public area</td></tr>
<tr><td>log</td><td>contains the log files of the public area
</td></tr>
<tr><td>styles</td><td>contains the CSS style sheets of the public area</td></tr></table>

<h3>other folders
</h3><table title="information on the other folders" border="1" cellspacing="0" cellpadding="2">
<tr><th>folder</th><th>
description</th></tr>
<tr><td>cache</td><td>contains the temporary files and the images transferred by the upload module
</td></tr>
<tr><td>fonts</td><td>contains the PDF fonts</td></tr>
<tr><td>images</td><td>contains the images used by the system
</td></tr>
<tr><td>install</td><td>contains the installation files of TCExam. For security reasons it is advisable to delete this folder after the installation.
</td></tr></table><br /><br />

<h2>Notes</h2><ul>
<li>
The structure and the names of the folders can be modified according to the guidelines of this configuration file: 
<em class="path">/shared/tce_paths.php</em>.<br /><br /></li></ul>[<a href="#topofdoc">index</a>]<a name="install1" id="install1"></a>

<h2>Minimum Requirements</h2>Before installing TCExam it is advisable to check the the system minimum requirements:<ul><li>
A web server (e.g.: Apache [<a href="http://httpd.apache.org/" target="_blank" title=" [this link will open a new browser window]">http://httpd.apache.org/</a>], Microsoft<sup>®</sup> IIS [<a href="http://www.microsoft.com" 
target="_blank" title="[this link will open a new browser window]">http://www.microsoft.com</a>]).</li><li>PHP 5 (<a href="http://www.php.net" target="_blank" title="this link will open a new browser window">http://www.php.net</a>) - required</li><li>The following libraries:<ul><li>
PHP GD Library 2.0.1 (<a href="http://www.boutell.com/gd" target="_blank" title="this link will open a new browser window">http://www.boutell.com/gd</a>) - required for true color images and plots<br />This library will be installed with:
<ul><li>libpng 1.2.2 - PNG immages support</li><li>jpegsrc.v6b - JPEG images support</li></ul></li></ul></li><li>One of the following databases:
<ul>
<li>MySQL 4.1 (<a href="http://www.mysql.com" target="_blank" title="this link will open a new browser window">http://www.mysql.com</a>)
<ul><li>Please check the following link if you experienced authentication problems: <a href="http://dev.mysql.com/doc/mysql/en/Old_client.html" target="_blank" title="this link will open a new browser window">http://dev.mysql.com/doc/mysql/en/Old_client.html</a></li></ul>
</li>
<li>PostgreSQL 7.4 (<a href="http://www.postgresql.org" target="_blank" title="this link will open a new browser window">http://www.postgresql.org</a>)</li>
</ul>
</li><li><a href="http://www.zend.com/store/products/zend-optimizer.php" 
target="_blank" title="this link will open a new browser window">Zend Optimizer<sup>TM</sup></a> 2.5.7 to run the coded files (not required for the uncoded version of the software).
</li><li>At least 20MB for the program files and 10MB for the database</li></ul>
For help with the installation and configuration of the web server and the required libraries please refer to the specific manuals.
<br /><br />The clients need to be equipped with a common web browser with XHTML 1.0 and JavaScript 1.2 support.<br /><br />[<a href="#topofdoc">index</a>]

<a name="install2" id="install2">
</a><h2>Operating system configuration</h2>For the correct use of TCExam, PHP has to be configured to support the systems and libraries indicated above; 
Some parameters of PHP must also be set like this:<br /><br />on <strong>php.ini</strong><ul>
<li>safe_mode = Off</li>
<li>arg_separator.output = "&amp;amp;"</li>
<li>magic_quotes_gpc = On</li>
<li>register_long_arrays = On</li>
</ul><br />or in the Apache module (<strong>/etc/httpd/conf/httpd.conf</strong>):<br /><pre>
&lt;IfModule mod_php5.c&gt;
	php_admin_flag safe_mode off
	php_value arg_separator.output "&amp;amp;"
	php_value magic_quotes_gpc On
	php_flag register_long_arrays On
&lt;/IfModule&gt;</pre>To configure other general aspects of PHP or for other configuration modes, 
please refer to the offical guide and additional information in the <a href="http://www.php.net" target="_blank" title="this link will open a new browser window">www.php.net</a>.<br /> website
<br />It is also necessary to assure that the disc quota of the different users are appropriate to manage all the files and the database.
<br /><br /><strong>NOTE:</strong>If you are using IIS in a non-Server version of Microsoft Windows, use the Microsoft application <em>MetaEdit 2.2</em> 
to modify the maximum number of allowed simultaneous connections and the IIS timeout:<ul><li>LM/W3SVC/MaxConnections 40</li><li>LM/W3SVC/CGITimeout 300
</li><li>LM/W3SVC/ROOT/CGITimeout 300</li></ul>  
Note: 300 indicates the number of seconds in 5 minutes.<br /><br />[<a href="#topofdoc">index</a>]

<a name="install3" id="install3"></a><h2>
Installation</h2>Verify that the operating system is correctly installed and configured as described in the sections above.
<br /><br />Copy the whole content of the TCExam folder into the root of your web server or in one of its subfolders.
<br />I you are using FTP to transfer the encoded version of TCExam, be sure to set the transfer mode to binary before sending the files to the server.
<br /><br />At this point you can proceed in two ways:<br />

<a name="install3_1" id="install3_1"></a><h3>Automatic Installation</h3>
This type of installation will automatically install the database and will configure the main system parameters.<br /><br />
Be sure to modify the access permission of the following files and folders so that the software can write into them (chmod 666 su sistemi unix-like):<ul><li>install/</li><li>
shared/config/cp_db_config.php</li><li>shared/config/cp_paths.php</li><li>admin/phpMyAdmin/config.inc.php</li><li>admin/phpPgAdmin/conf/config.inc.php</li></ul>
You can reset the permissions of these files to <em>read-only</em> at the end of the installation process.<br />
In the eventuality that the installation program cannot modify these files, it will always be possible to modify them manually as indicated in the following section.
<br /><br />By using a common web browser (internet navigation software like Microsoft® Internet Explorer, Mozilla o Netscape®) 
go to the address where the TCExam installation program is located: <strong>http://&lt;host&gt;/install/install.php</strong><br /><br />
If the operating environment is configured correctly, you should be able to see the installation module of TCExam.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_install.png" alt="screenshot: installation" width="776" height="748" border="1" />
<br />

<br />Note that:<br />
Due to the critical importance of this component, the installation program is provided only in english.<br />
The installation process will delete any data of previous installations of TCExam, reason why in this case it is advisable to make backup copy of these data.
To start the installation you must fill up the form completely and press the button INSTALL.<br /><br />
It follows a list of the fields required by the installation module:<ul><li><strong>db type</strong>: type of database utilized (the default is <em>MySQL</em>)</li>
<li><strong>db host</strong>: name of the database host (usually <em>localhost</em></li>
<li><strong>db port</strong>: database port (usually <em>3306</em> for MySQL or <em>5432</em> for PostgreSQL)</li>
<li><strong>db user</strong>: name of the database user (usually it is <em>root</em>)</li><li><strong>db password</strong>: user password to access the database </li><li><strong>db name</strong>: 
nome of the database (usually TCExam). This name has to be changed just when there are other copies of TCExam in the same system .</li><li><strong>
tables prefix</strong>: prefix that will be added to the table names (usually <em>tce_</em>)</li><li><strong>host URL</strong>: the domain name of your site 
(e.g.: <em>http://www.host.com</em>)</li><li><strong>relative URL</strong>: relative path from the root of your webserver where the TCExam files are located
(usually / or /&lt;TCExam folder&gt;/)</li><li><strong>TCExam path</strong>: complete path of the folder where TCExam is installed
(e.g.: <em>/usr/local/apache/htdocs/TCExam/</em> or <em>c:/Inetpub/wwwroot/TCExam/</em>)</li><li><strong>TCExam port</strong>: default http connection port (usually 80).</li></ul>If the installation completed succesfully the system is ready for the first execution.<br />
At this point you can remove the <em>install</em> folder from the server and restore the read-only permissions for the configuration files.<br />
In case the installation did not complete succesfully you can complete it or repeat it using the manual procedure described in the next section.<br /><br />[<a href="#topofdoc">
index</a>]

<a name="install3_2" id="install3_2"></a><h3>Manual Installation</h3>
In order to manually install TCExam you must modify the configuration files and install the database.

<a name="install3_2_1" id="install3_2_1"></a><h4>
Modification of the configuration files</h4>The required files and configuration parameters for TCExam to start are:<ul><li>shared/config/cp_db_config.php<ul>
<li>K_DATABASE_TYPE 
(database type, usually <em>MYSQL</em> or <em>POSTGRESQL</em>)</li><li>K_DATABASE_HOST (name of the database host, usually <em>localhost</em>)</li><li>K_DATABASE_NAME 
(database name, usually <em>TCExam</em>)</li><li>K_DATABASE_USER_NAME (nome of the database user, it usually is <em>root</em>)
</li><li>K_DATABASE_USER_PASSWORD 
(password to access the database)</li><li>K_TABLE_PREFIX (prefix that will be added to the table names, usually <em>tce_</em>)</li></ul>
</li><li>shared/config/cp_paths.php<ul><li>K_PATH_HOST (the domain name of your site, e.g.: <em>http://www.host.com</em>)</li>
<li>K_PATH_PHPMYEXAM (relative path from the root of your webserver where the TCExam files are located, usually / or /&lt;TCExam path&gt;/)
</li><li>K_PATH_MAIN (complete path to the folder where TCExam is installed, ad example: <em>/usr/local/apache/htdocs/TCExam/</em> or <em>
c:/Inetpub/wwwroot/TCExam/</em>)</li><li>K_STANDARD_PORT (http communication port, usually 80)</li></ul></li><li>admin/phpMyAdmin/config.inc.php<ul>
<li>cfg['PmaAbsoluteUri'] 
(complete internet address where the application phpMyAdmin is installed, usually <em>http://&lt;host&gt;/admin/phpMyAdmin/</em>)</li>
<li>cfg['Servers'][$i]['host'] 
(name of the host of the MySQL database, usually <em>localhost</em>)</li><li>cfg['Servers'][$i]['user'] (user name of the MySQL database, usually 
<em>root</em>)</li><li>cfg['Servers'][$i]['password'] (user password to access the MySQL database)</li></ul></li>
<li>admin/phpPgAdmin/conf/config.inc.php
<ul>
<li>$conf['servers'][0]['host'] (hostname or IP address for server)</li>
<li>$conf['servers'][0]['port'] (database port on server, 5432 is the PostgreSQL default)</li>
</ul></li>
</ul>

<a name="install3_2_2" id="install3_2_2">
</a><h4>Database Installation</h4>In the <em>install</em> folder there are all the SQL files with the structure and data of the database:
<ul>
<li>mysql_db_structure.sql - contains the MySQL database structure</li>
<li>mysql_db_data.sql - contains the MySQL database data</li>
<li>pgsql_db_structure.sql - contains the PostgreSQL database structure</li>
<li>pgsql_db_data.sql - contains the PostgreSQL database data</li>
</ul>
If you want to change the prefix of the tables you must use a text editor with the <em>search and replace</em> function 
and perform the following substitutions:<ul><li>In the ..._db_structure.sql file substitute <em>CREATE TABLE tce_</em> with <em>CREATE TABLE yourprefix</em></li><li>In the ..._db_data.sql file substitute 
<em>INSERT INTO tce_</em> with <em>INSERT INTO yourprefix</em></li></ul>
To execute the SQL file you can use the database commands from the command shell of the server.<br />
MySQL example:<pre>
mysql
mysql&gt; CREATE DATABASE TCExam;
shell&gt; mysql TCExam &lt; db_structure.sql
shell&gt; mysql TCExam &lt; db_data.sql</pre>In the previous example we assumed that the database name was <em>TCExam</em>.
<br />
<br />
As another option (if you already configured the file <em>config.inc.php</em>) you can use the utility 
<strong>http://&lt;host&gt;/admin/phpMyAdmin/index.php</strong> for MySQL or <strong>http://&lt;host&gt;/admin/phpPgAdmin/index.php</strong> for PostgreSQL to create the database and run the SQL files by using the specific command.<br />
<br />[<a href="#topofdoc">index</a>]

<a name="install3_3" id="install3_3"></a><h3>Post installation</h3>Once the installation is completed you must:<ul>
<li>delete the <em>install</em> folder since it is not necessary anymore and may represent a security issue for the system</li>
<li>set as read-only (chmod -R 644 in unix-like systems) the permissions for the files and folders:<ul><li>admin/config/</li><li>shared/config/</li>
<li>public/config/</li></ul></li><li>properly set the write permissions for the folders where TCExam will have to write
(chmod -R 666):<ul><li>admin/log</li><li>cache</li><li>images</li><li>public/log</li></ul></li><li>
configure in detail the configuration files as described in the following section</li></ul>[<a href="#topofdoc">index</a>]

<a name="install4" id="install4"></a>
<h2>System Configuration</h2>Once the automatic installation procedure is completed, TCExam should be fully functional.
<br />It is possible to personalize some settings and basic features by modifying the following configuration files:
<ul>
<li>shared/config/tce_config.php - system general configuration</li>
<li>shared/config/tce_db_config.php - database configuration</li>
<li>shared/config/tce_extension.inc - file extension used by the system (.php)</li>
<li>shared/config/tce_general_constants.php - general constants</li>
<li>shared/config/lang/ - language files</li>
<li>shared/config/tce_paths.php - file and folder paths within the system</li>
<li>shared/config/tce_pdf.php - configuration of the format and the headers of the PDF documents</li>
<li>shared/config/tce_email_config.php - general configuration of the email system</li>
<li>admin/config/tce_config.php - general configuration of the administration panel</li>
<li>admin/config/tce_auth.php - access levels configuration for the administration modules</li>
<li>public/config/tce_config.php - general configuration of the public area</li>
</ul>[<a href="#topofdoc">index</a>]

<a name="install5" id="install5"></a><h2>Access and Security</h2>
Once the installation and configuration procedures are completed, you can access the system going with you internet browser to the address 
http://&lt;host&gt;/admin/code/index.php and using the following username and password:<ul><li>nome: admin</li><li>password: 1234</li></ul>
<br />
<img src="../../images/screenshots/eng/screenshot_login.png" alt="screenshot: admin user login" width="776" height="354" border="1" />
<br />
In order to protect your system and be granted with an unique personal access, remember to change the password with the module <em>Users</em>. 
With this module you can also insert your personal data and preferences.<br /><br />
In order to achieve a better level of security we suggest to protect the whole <em>admin</em> folder with a web-based user autentication system.
<br />One of the most efficient and simple ways to protect a folder on a server <a href="http://www.apache.org" target="_blank" title="this link will open a new browser window">
Apache</a> is to use the Htaccess autentication. For more informations refer to  
<a href="http://httpd.apache.org/docs/howto/htaccess.html" target="_blank" title="this link will open a new browser window">http://httpd.apache.org/docs/howto/htaccess.html</a>.<br />
If you are using a different web server refer to the specific documentation.<br /><br />[<a href="#topofdoc">index</a>]

<a name="use" id="use"></a>

<h2>Use</h2>
As described in the <a href="#structure">general structure</a>, the TCExam interface consists in two distinct areas, a <a href="#use_public">
public</a> one and an <a href="#use_admin">administrative</a> one, physically separated in the server filesystem:<br /><br />[<a href="#topofdoc">index</a>]
<a name="use_public" id="use_public"></a>

<h3>Public Area</h3>Is accessible with the browser going to the address <strong>http://&lt;host&gt;/admin/public/index.php</strong>.
<br />This area containts the forms and the interfaces that will be used by the users to do the tests.<br /><br />
In order to access this area, the users must log in inserting their username and password in the specific form.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_login_public.png" alt="screenshot: public user login" width="776" height="247" border="1" />
<br />
<br />
Once logged in, the users will see a page with the list of the tests to do and possibly the tests already done. 
The latters will be visualized just if the constant <em>K_ENABLE_RESULTS_TO_USERS</em> has been set to <em>true</em> in the configuration file  
<em>shared/config/tce_config.php</em>.<br />The list of the tests visualized depends on the relative time frames, the user IP address 
and the condition if they have already been performed or not.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_test_list.png" alt="screenshot: test list" width="776" height="244" border="1" />
<br />
<br />
The list of active tests shows, other than the test name, a list of links different case by case:<ul>

<li><strong>[info]</strong><br />Opens a popup window with detailed informations about the specific test.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_test_info.png" alt="screenshot: test info" width="700" height="300" border="1" />
<br />
<br /></li>

<li><strong>[execute]</strong><br />Generates the test for the current user and open the form to execute it.</li>
<li><strong>[continue]</strong><br />
Apperas as an alternative to [run], if clicked, it allows to resume and continue the test if its time is not expired yet.</li>
<li><strong>
[results]</strong><br />Appears when the test has been completed and only if the constant <em>K_ENABLE_RESULTS_TO_USERS</em> 
has been set to <em>true</em> in the configuration file <em>shared/config/tce_config.php</em>. 
If clicked, this button opens a page with the test results (the evaluation is related just to the questions with multiple answers). 
Note that the action of showing the results halts the execution of the test.</li></ul>

<h4>Test Execution</h4>
The form to execute the test is composed by several parts:<ul>
<li>A clock to remind the user of the actual time.</li>
<li>
A countdown clock to remind the user about how much time he/she has left to complete the test.</li>
<li>
A menu to select the questions and show their status (completed, not completed).</li>
<li>
A link on the test name that, if selected, opens a popup windows with detailed informations about the test in execution.</li>
<li>
The question body, that may include also text formatting and images.</li>
<li>A field to submit the answers, that may vary case by case:<ul>
<li>
A list of possible answers on a random order, of which one is correct, one is not to provide any answer (default) and the others are wrong. 
Each time the user selects a different answer, the page is refreshed and the data are sent to the server.</li>
<li>
A field to insert a free text. Once inserted the text, it will be necessary to press the button [update] 
or to switch to another question.</li></ul></li>
<li>A status bar that shows the user name and a link to log out the system.</li></ul>
<br />
<img src="../../images/screenshots/eng/screenshot_test_execute.png" alt="screenshot: test execution" width="776" height="552" border="1" />
<br />
<br />
The user is allowed to change the answers with no limitation throughout the whole duration of the test.
It is not necessary to confirm the end of the test since it will be considered concluded when the expiration time is reached.<br />
<br />[<a href="#topofdoc">index</a>]<a name="use_admin" id="use_admin"></a>

<h3>Administration Area</h3> Is accesible with the browser going to the address  
<strong>http://&lt;host&gt;/admin/code/index.php</strong>.<br />
Contains the forms and the interfaces to manage the whole system, included the user and database management, the generation of the tests and the results.
<br /><br />All the forms of this area are characterized by some common features:<ul>
<li>A clock to inform about the actual time.</li>

<li>A floating navigation menu.</li>
<li>
An information area about the current form.</li>
<li>A status bar with the current user name and a link to log out the system.</li></ul>

<h4>Forms</h4><ul>
<li><strong>User Management</strong><br />
In this form you can add, modify or delete the users allowed to use the system.
For each user you can chose a username, a password and specify an access level.
Level 0 indicates an anonymous user, level 1 a basic user (e.g.: a student willing to perform the test),
level 10 indicates an administrator with full access rights to the system.<br />
The access level to the resources of the administration area is defined in the configuration file <em>admin/config/tce_auth.php</em>.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_user_edit.png" alt="screenshot: user management" width="776" height="596" border="1" />
<br />
<br /></li>
<li><strong>
Users Selection</strong><br />This form shows the registered users and allows their selection.<br />
You can arrange them in the order you whish by clicking on the column headers.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_user_select.png" alt="screenshot: user selection" width="776" height="354" border="1" />
<br />
<br /></li>
<li><strong>Online Users</strong><br />
This form shows the users currently logged in.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_user_online.png" alt="screenshot: users online" width="776" height="354" border="1" />
<br />
<br /></li>
<li><strong>Topics Management</strong><br />
With this form you can add, modify and delete the topics used for the tests. All the questions defined in the system will be organized according to these topics.
<br />A test can comprehend an arbitrary number of topics.<br />
You cannot modify or delete a topic that has already been used in previous tests. In this case clicking on the button [delete] you can just disable it.
<br />The button [modify] opens a popup form that allows to insert images and use text formatting for the text description.<br />
The link [elenco] opens the Questions List form.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_subject.png" alt="screenshot: topics management" width="776" height="433" border="1" />
<br />
<br /></li>

<li><strong>Questions List</strong><br />
This form list all questions and answers related to the selected topic.<br />
The [modify] links allows you to edit the relative item.<br />
The button [PDF] outputs the list to a PDF file.<br />
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_questions_list.png" alt="screenshot: questions list" width="776" height="810" border="1" />
<br />
<br /></li>

<li><strong>Questions Management</strong><br />With this form you can add, modify or delete the questions related to the selected topic.
<br />The different questions can require either multiple answers or free answers to be written in an apposite text field.<br />
As for the topics, you cannot modify or delete a question if it has already been used in previous tests. In this case clicking on the button [delete] you can just
disable it.<br />
The button [modify] opens a popup windows that allows to insert images and use text formatting.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_question.png" alt="screenshot: question management" width="776" height="475" border="1" />
<br />
<br /></li>
<li><strong>Multiple Answers Management</strong><br />
With this form you can add, delete or modify the possible answers for the selected question.<br />
To each question you can associate an arbitrary number of correct and wrong answers; during the test the system will automatically choose and present the user with only
one correct answers among the other wrong ones.<br />
You are not allowed to modify or delete an answer that has already been used in previous tests, you can just disable it by pressing the button [delete].
<br />The button [modify] opens a popup form that allows to insert images and use text formatting.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_answer.png" alt="screenshot: multiple-answer management" width="776" height="485" border="1" />
<br />
<br /></li>
<li><strong>Test Management</strong><br />
With this form you can add, modify and delete the different tests.<br />
In order to insert a new test, you must first define its topic or topics with an appropriate number of questions and answers.<br />
The tests can be accessed and performed only by the users registered and with a valid IP address.
The IP field may contain a list separated by commas of all the IP addresses allowed to access the system. An IP address can also contain wildcards
(* = any number).<br />The button [modify] opens a popup form that allows to insert images and use text formatting.<br />
The test will remain active only within the specified time frame and, once generated, it will have to be completed within the maximum defined duration.<br />
The tests can be composed by one or more topics.<br />
You can specify the maximum number of questions and related answers that the system will choose on a random basis.
You can also define the score for each type of answer (correct, wrong, not given).<br />
You cannot modify a test that has already been performed. The cancelation of a test will result in the cancelation of all its logs and the test results.<br />
The button [generate] allows to generate an arbitrary number (specified in the field next to the button) of unique tests in PDF format, so that they can be printed
and utilized for a classic type of exam (without a computer).
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_test.png" alt="screenshot: test management" width="776" height="859" border="1" />
<br />
<br /></li>
<li><strong>Free Answers Evaluation</strong><br />
With this form you can give a score to the free answers.<br />
By default the name of the author of the answer is not shown so as to guarantee an objective evaluation, but if you whish you can always see its data  
by selecting the field '<em>user data</em>'.<br />
The field '<em>show all</em>' allows to see and go through also the answers already evaluated.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_rating.png" alt="screenshot: answer evaluation" width="776" height="459" border="1" />
<br />
<br /></li>
<li><strong>Test Results Summary</strong><br />
This form summarizes the results of all users for the selected test.<br />
You can change the visualization order by clicking on the column headers.<br />
The data enclosed by brackets show the details of the multiple and free answers.<br />
You can see the details of a specific test by clicking on the row number.<br />
The button [PDF] outputs the results to a PDF file.<br />
The button [PDF tests] generates a PDF file with the details of all tests.<br />
The button [send emails] sends a PDF test report via email to each user.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_results.png" alt="screenshot: test results summary" width="776" height="387" border="1" />
<br />
<br /></li>
<li>
<strong>Test Results</strong><br />This form shows the details of the test for the selected user.<br />The data on each line are:<br /><em>
question number. [score] (user IP | visualization time in hh:mm:ss| time of last modification in hh:mm:ss| time to answer in mm:ss)</em><br />
The symbol ® indicates the correct answers, while the answers given by the user are marked with an 'x'.<br />
The button [PDF] outputs the results to a PDF file.<br />
The button [send emails] sends a PDF test report via email to selected user.<br />
<br />
<img src="../../images/screenshots/eng/screenshot_result_user.png" alt="screenshot: test results" width="776" height="803" border="1" />
<br />
<br /></li>
<li><strong>
Questions Statistics</strong><br />This form shows statistical data about the questions of the selected test.<br />
You can change the visualization order by clicking on some of the column headers.<br />
If you click on the row number of a particular question you are redirected to a form that allows you to modify it.<br />
The button [PDF] outputs these data to a PDF file.<br />
The button [send email] sends an email to user containing the PDF copy of his/her test.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_stats.png" alt="screenshot: question statistics" width="776" height="637" border="1" />
<br />
<br /></li>

<li><strong>Mark-up Editor</strong><br />
This form allows to insert images and use text formatting using a special mark-up language.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_markup.png" alt="screenshot: mark-up editor" width="574" height="436" border="1" />
<br />
<br />
The button [preview] opens a popup window that displays the formatted text preview.
<br />
<br />
<img src="../../images/screenshots/eng/screenshot_markup_preview.png" alt="screenshot: mark-up editor preview" width="500" height="650" border="1" />
<br />
<br />
</li>

</ul><br /><br />
[<a href="#topofdoc">index</a>]<br /><br />NOTE: The PDF documents generated by the system support only png images or jpg images not interlaced and with no alpha channels.

<?php
//============================================================+
// END OF FILE                                                 
//============================================================+
?>
