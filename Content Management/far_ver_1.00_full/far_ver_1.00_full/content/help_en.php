<?php
/* =====================================================================
* 	The manual of the FAR-PHP project in English
*	Translated by Krimket after the Romanian manual
*	Manual partial adapted for version: 1.00
*	Copyright: (C) 2004 - 2005 the FAR-PHP Group
*	E-mail translator: krimket@far-php.ro
*	Start date: 05-05-2005
*	Last update: 16-06-2005
*
*	This program is free for non-commercial use (non-profit) 
*	and it is distributed under the licence terms of GNU General Public License
*	as published by  Free Software Foundation; version 2, 
*	or (at your choice) any subsequent version.
** ======================================================================== */

/* mentiune pentru toti colaboratorii 
- orice modificare noua in manual se va face cu o culoare diferita pentru a fi usor de gasit si tradus doar modificarea
*/

?>
<style type="text/css">
<!--
.adaugari_manual 
	{
	color: #FF0000;
	}
-->
</style>


<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><div align="center">
     <p><strong>FAR-PHP Manual (English version)<br>
          (updated for 1.0 version)          </strong></p>
   	  <p><em>The collaborators of the FAR-PHP project</em></p>
      <p align="center"> Thank you to everyone who have participated in one way or another through ideas, suggestions, help or collaboration. <br>
        As this project is open-source everybody is welcome to be a part of it. </p>
      <p>Permanent collaborators :</p>
      <p>Project coordinator : <strong>Birkoff</strong> - <strong>contact</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong> <br>
        Translator ro-en: <strong>Krimket</strong> - <strong>krimket</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong><br>
        Php-MySQL programmer: <strong>Dexter</strong> - <strong>dexter</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong><br>
      </p>
      <p>Another collaborators: </p>
      <p>- <strong>Gyzzard</strong> - <strong>gyzzard</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - add-on &quot;contact.php&quot; module<br>
        - <strong>Alin4lex</strong> - <strong>spookykid</strong> <em>at</em> <strong>4x</strong> <em>dot</em> <strong>ro</strong> - &quot;corp&quot; theme <br>
        - <strong>Cata</strong> - <strong>barosanu_catalin</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - &quot;blue&quot; theme + new css <br>
        - <strong>Tudy</strong> - <strong>trd002200</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - the ideea to create the module of application install + help at the code part <br>
        - <strong>Aniflaviu</strong> - <strong>addyanni</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - ideas and suggestions regarding the project + collaboration at MySQL and php code. <br>
        - <strong>Stalker</strong> - <strong>numaitu2002</strong> <em>at</em> <strong>yahoo</strong> <em>dot</em> <strong>com</strong> - designer &quot;clasic&quot; theme + css of  &quot;red&quot; si &quot;mountain&quot;</p>
      <p><br>
          <br>
          Copyright: 2004, 2005 FAR-PHP development group</p>
      <hr>
      <p>Table of Contents</p>
      <p align="justify"><a href="#Prefata">Preface</a><br>
        I. <a href="#introducere">Introduction</a><br>
		&nbsp;&nbsp;&nbsp;<a href="#licenta">The project licence</a><br>
		&nbsp;&nbsp;&nbsp;1. <a href="#instalare">Installation</a><br>
		&nbsp;&nbsp;&nbsp;2. <a href="#configurare">Configuration</a><br>
		&nbsp;&nbsp;&nbsp;3. <a href="#mesaje_eroare">Changing error messages</a><br>
		&nbsp;&nbsp;&nbsp;4. <a href="#instal_problem">Problems when installing</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) <a href="#instal_problem_a">System requirements </a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) <a href="#instal_problem_b">Possible errors when installing</a><br>
		II.1. <a href="#lucrul_cu_far_php">Working with FAR-PHP</a><br>
&nbsp;&nbsp;&nbsp;a. <a href="#modul_logare">The login module</a> - login.php, login_ver.php, login_new.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#login">Login</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#drept_acces">Rights and access levels</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#creare_user">Creating new user</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#schimbare_drepturi">Changing user's access rights</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#schimbare_parola">Changing user's password</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. <a href="#stergere_user">Erasing user</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. <a href="#deconectare_user">Disconnecting the user</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8. <a href="#viev_useri">Displaying registered users</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#modul_meniuri">The menus module </a>- menu.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#creare_meniu">Creating new menus</a><br> 
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#schimbare_meniu">Changing the menu status</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#stergere_meniu">Erasing the  menus</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#afisare_lista_meniuri">Displaying existent menus </a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#schimbare_meniu">Modifying a menu	</a><br>
&nbsp;&nbsp;&nbsp;c. <a href="#modul_continut">The content module </a>	- content.php, content_2.php <br>		
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#modul_continut_nou">Adding new content </a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.a) <a href="#continut_html">Adding new content in text/html format </a> <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.b) <a href="#continut_varianta_5">Adding new content php/sql format </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#modul_content_2">Modifying the existent content</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#modul_stergere_continut">Erasing the existent content</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#afisare_articole_limba">Displaying existent articles for a certain language</a><br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#afisare_toate_articolele">Displaying all the articles from the database </a> <br>
&nbsp;&nbsp;&nbsp;d. <a href="#modul_stiri">The news module </a>- news.php <br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. Adding news<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. Modifying news<br>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. Erasing news<br>
&nbsp;&nbsp;&nbsp;e. <a href="#modul_language">The language module </a> - language.php, ch_language.php, language_xx.php <br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#setare_limbaj_principal">Setting the main language</a><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#schimbare_limbaj">Changing between different languages</a><br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#creare_limbaj">Creating a new language (translating an existent one) </a><br>
&nbsp;&nbsp;&nbsp;f. <a href="#cpanel">Control panel Module</a> - cpanel.php <br>
&nbsp;&nbsp;&nbsp;g. <a href="#robots">Robots Module</a> - robots.txt, robots.php, index.php, meta.php <br>
&nbsp;&nbsp;&nbsp;h. <a href="#install">Installation Module</a> - install.php<br>
&nbsp;&nbsp;&nbsp;i. <a href="#mod_ch_template">Theme changing Module </a> - ch_template.php<br>
&nbsp;&nbsp;&nbsp;j. <a href="#blockip">IP blocking Module</a> - blockip.php <br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#blockip_lista">Displaying blocked IP list </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#blockip_modificare">Modifying IP data </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#blockip_adaugare">Adding IP</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#blockip_stergere">Erasing IP </a><br>
II.2. <a href="#addon_modules">Additional modules </a> (add-on)<br>
&nbsp;&nbsp;&nbsp;a. <a href="#banner">Banner display module </a> (add-on) - banner.php <br> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <a href="#adaugare_banner">Adding banners </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <a href="#modificare_banner">Modifying banners </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <a href="#sterg_banner">Erasing banners </a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <a href="#toate_banner">Displaying all banners</a><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <a href="#log_banner">Banner Log</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#online">Visitors online Displaying module </a> (add-on) - online.php <br>
&nbsp;&nbsp;&nbsp;c. <a href="#uninstall">Uninstall module </a> (add-on) - uninstall.php<br>
&nbsp;&nbsp;&nbsp;d. <a href="#adduserphpbb">Adding users in phpbb module </a> (add-on) - adduserphpbb.php<br>
&nbsp;&nbsp;&nbsp;e. <a href="#newsletter">Newsletter module </a> (add-on) - newsletter.php <br>
II.3. <a href="#add_scripts">Additional scripts </a> <br>
&nbsp;&nbsp;&nbsp;a. <a href="#contact_1">Contact page</a> (add-on) - contact.php<br>
&nbsp;&nbsp;&nbsp;b. <a href="#demo_page">Demo page</a> - demo_page.php<br>
		III. <a href="#schimba_template">Template changing</a><br>
&nbsp;&nbsp;&nbsp;a. <a href="#adaug_tema">How do I add a new theme</a><br>
&nbsp;&nbsp;&nbsp;b. <a href="#sterg_tema">How do I erase a theme from the site</a><br>
&nbsp;&nbsp;&nbsp;c. <a href="#creare_tema_site">How do I create a theme for the site </a><br>
		IV. <a href="#specificatii_teme">What changes must be done to a template in order to be compatible with the FAR-PHP code</a><br>
		V. <a href="#variabile_ses_cook">Session variables and cookies </a> <br>
		VI. <a href="#log_ver">Version changes </a> <br>
		VII. <a href="#faq">Frequently asked questions (F.A.Q.)	</a> <br>
        VIII <a href="#creare_config_manual">Creating by hand the configuration file config.php </a></p>
	  <hr>
      <p align="justify"><strong>Preface<a name="Prefata"></a> <br>
        <br>
        </strong>The present manual is rather a guide which will help you to understand what is a CMS (Content Management System) and what it does. The FAR-PHP project is a CMS, meaning a work interface, for the administrator of a site and a web page for the visitors. In this manual you will find detailed explanations for the project and also for the modules that make the project.<br>
        <br>
       The latest versions of this manual (updated as soon as the content changes) may be viewed (and downloaded) at <a href="http://www.far-php.ro" target="_blank">http://www.far-php.ro</a> <br>
        <br>
        The Romanian version of this manual is voluntarily written by the FAR-PHP project team and translated in to English.<br>
        In order to take part at the translation and/or development of this manual you may send an email to <a href="mailto:contact@far-php.ro?Subject=Inscriere la proiect FAR-PHP in EN">contact at far-php dot ro</a><br>
      </p>
      <p align="justify"><strong>I. Introduction</strong><a name="introducere"></a><br>
        <br>
        <em>What is FAR-PHP?</em><br>
        It is a CMS (Content Management System), meaning a web interface through which you can manage the content of a site. FAR-PHP is easy to install and configure and also it is very easy to work with this interface. As the project is made of modules and each module is independent from the rest, there can be added anytime new modules with new facilities thus making your page more accurate to your wishes and attracting more visitors.<br>
		<br>
        <em>The licence of the FAR-PHP project</em> <a name="licenta"></a><br>
        The FAR-PHP project is distributed under the terms of licence of GNU/GPL version 2 (or any subsequent version). The project is open-source and it can be used/modified the code only by respecting the terms of licence.<br>
        In order to use the project or some parts of code form the project in commercial purpose (by commercial it is understood reselling the code, using the project inside a site by a company, any other using by which results a material profit) it must be paid the amount of 35/euro for each site where the code from the project is used. <br>
		In order to use the project or some parts of code form the project in personal purpose (by personal it is understood testing, using the project inside a company in non-commercial purpose, any other use by which it results no material profit) there is no fee, only respecting the copyright terms mentioned in the code. <br>
		For other details regarding the licence, or distributing the project or its using, send a message with subject &quot;FAR-PHP licence&quot; on the address <strong>contact</strong> <em>at</em> <strong>far-php</strong> <em>dot</em> <strong>ro</strong> in which please specify what is that you want.( If the  subject is not the one that is specified it is possible that the message to be erased automatedly by  anti-spam programs.) </p>		
      <p align="justify"><em><strong>I.1. Installing</strong></em><a name="instalare"></a><br>
        <br>
		In order to use this project you must download the zip file and extract it. After extracting it, copy on your web server all the files (attention, the files must be uploaded on the server with the correct name with small letters - all the file names that make this application are written in original with small letters - web servers make the difference between file name written with capital letters or small letter). After all the files and directories (folders) were copied on the server open the browser and write the address where the files were saved (for instance www.youraddress.ro). The installation part of the project will appear, and from this point forward please follow the instructions. <br>
      </p>
      <p align="justify"><em><strong>I.2 Configuration <a name="configurare"></a><br>
<br> </strong></em>
	a) Requested configuration before the installation :</span><br>
	Before working with this application, you must have a web server which should respect the minimum system requests (see I.4.a). The FAR-PHP project is tested very much before distributing it, but we cannot be sure that it will work 100% on any web configuration. If you face some problems you may specify the problem that raised up in the forum on the address <a href="http://www.far-php.ro/forum/" target="_blank">www.far-php.ro/forum/</a> (in English or Romanian)<br>
        <br>
	b) Project configuration when installing :</span><br>
	During the installation it will be attempted to automatedly create the tables in the database (the database must already be created), it will be attempted to save the content (predefined menus) and it will be attempted to generate and save the config.php file. If config.php cannot be written, the needed data will be displayed in the browser and they will have to manually be copied saved on the server, according to the specifications displayed in the browser at that time. <br>
        <br>
	c) Configurations after the installation :</span><br>
    If after the installation of the application you wish to modify some data, as the predefined language, or another data, you must manually edit the config.php file what you need. All the modules and files of the project work with the data saved in this file (it is, if you wish, the heart of the application) so be careful what you modify. For your safety be careful to set the access rights to this file to chmod 664, and from the server settings please configure so that it is not allowed the access from outside the domain to this file. <br>
      <p align="justify"><em><strong>I.3. Changing error messages</strong></em><a name="mesaje_eroare"></a><br>
        <br>
        The message file in English is called <em>language_en.php</em> and it can be found in <em>content/</em> directory. Within it there are all the messages that appear in the applications modules and also the warnings or the texts for the buttons and the forms. You may modify the text according to the language you desire, but do NOT erase the variables and do NOT modify the syntax of the message (meaning that if the message contains html code it is recommended NOT to erase the html code but only the message itself). You can rename the message file ONLY in its standard form (<em>language_en.php</em> (example-en=2 letter shortcut for language)) because it is used to display the content of the pages in the specified language and also the menus appear in the specified language. You can mention inside <em>config.php</em> the new name of the message file. This file is included in every page and module of the application thus every message that appears in the web page must first exist in this file.<br>
		Beginning with version 1.0 the additional modules have their own error messages, usually saved in a function or in an array. If you use other languages besides those that come with the project, you will have to add manually in every additional module the messages for the language used in your site. </span></p>
      <p align="justify"><em><strong>I.4. Problems when installing </strong></em> <a name="instal_problem"></a><br>
        <br>
        It is possible to appear errors especially due to specific configuration of the server that the application is installed or the errors that appeared at creating the database and the config.php file. Next will be described the minimum requirements necessary for FAR-PHP and the eventual problems that may appear (if the error is not described beneath you can send details by email in order to solve it).<br>
        <br>
        a) <em>System requirements </em><a name="instal_problem_a"></a><br>
		The server must have support for PHP minimum version 4.1.x + MySQL  minimum version 4.x.<br>
        In order to function properly it must be active the following libraries and php extensions: <br>
        ftp support = enabled<br>
        gd support = enabled<br>
        mysql support = enabled<br>
        session support = enabled<br>
        mail support = enabled <br>
	On the client-side, it is recommended a browser with java script activated (for instance Opera browser is distributed both with java and without). If the java script part is not active (or updated) it will not be possible to change the language and the template but the site will function with the default settings. <br>
        <br>
        b) <em>Possible errors when installing:</em><a name="instal_problem_b"></a><br>
        - <u>Slow loading pages</u><br>
        It is possible that you do not have a fine Internet connection (even if you work on localhost, there are used some functions like 'gethostbyaddr' or 'mail' that may slow down the script parsing in case of a slow Internet connection).<br>
 - <u>It does not redirect correctly</u><br>
 The address where is installed FAR-PHP is written incorrectly in <em>config.php</em> file. Please open the <em>config.php</em> file and    correct the value of <em>'$adresa_url'</em> variable.<br>
 - <u>Appears a message like &quot;the table cannot be created...&quot;</u><br>
 It is possible that the data regarding the database is wrong. Start again the install process and verify that all requested data is  correct. If the database is not on localhost, verify if you have access to create tables frorm a php script from the address where is saved FAR-PHP (access to the database from outside).<br>
 - <u>Apears the message &quot;Error: Wrong install - config.php incorrect&quot;</u><br>
 It is possible that the configuration file resulted after installation is corrupt. (It might happen on the servers that modify the out coming data of the script, in order to insert advertisements in web pages hosted on that server). In this case, you have to create manually the 'config.php' file and upload it on the server by ftp. Please check the chapter <a href="#creare_config_manual">Creating by hand the configuration file config.php</a>. <br>
 - <u>The chosen theme or the specified language is not maintained </u><br>
 This problem is about the browser you work with or a problem with the operating system (probably viruses). Check the page with another browser, from another PC or update your browser to a new version. Likewise check if the firewall allows access to the specified web address (some  settings of the firewall might block cookies or transmitting the session variables). <br>
 - <u>Cannot change the language or the template</u><br>
 This problem is still in the client part; verify if the browser has activated the option for java script, allows cookies and session variables. Verify the settings of the firewall and eventually update your browser to a newer version or try with another browser (we recommend Firefox).<br>
 - <u>Cannot modify the content in the main page</u><br>
 The name of the main page is &quot;default&quot;. Add an article in the database with &quot;default&quot; file name (more details in the chapter <a href="#modul_continut"> The content module </a> )<br>
 - <u>I have no menu in the page.</u><br>
 When installing, the menus are saved in the database in the language in which the installation was performed, so in order to see the preinstalled menus, select the language during the installation and the menus will appear in the page. To  add new menus please read the chapter <a href="#modul_meniuri">The menus module</a>.<br>
 <br>
 For another errors that are not mentioned here verify the chapter <a href="#intrebari_frecvente">Frequently asked questions (F.A.Q.)</a> <br>
 </p>

      <p align="justify"><strong>II. Working with FAR-PHP</strong><a name="lucrul_cu_far_php"></a><br>
        <br>
        After having installed and configured the application, the first thing we do is to connect with the user and the password chosen when installed the site. After connecting, a message will be shown specifying which rights you have (in this case you have administrator rights) and a menu will appear from where you can manage the site. This menu allows you to access various modules installed with FAR-PHP. In the following part it is shown how to work with every module separately and the way that are accessed different characteristics of the modules. These modules are in fact php files which receive the data sent through GET and POST and they display the result after having parsed it. Every module resides in the MODULES directory. The modules can be accessed through the <strong>admin.php</strong> page using as parameter m=module_name (example: admin.php?m=login - will display the users status - as you may notice the extension of the file must not 
be written, it is automatedly placed inside the script).<br>
        The parameters being used inside the FAR-PHP application are as follow:<br>
        1. Inside index.php page <br>
        - p= a request is sent to display a page from the database
        <br>
        - c= a request is sent to parse and display a php page within the CONTENT directory<br>
        - m= a request is sent to parse a module within the MODULES directory (it is not recommended to load modules in index.php page but only in admin.php page)<br>
        2. Inside admin.php page<br>
        - m= a request is sent to parse a module <br>
- c= a request is sent to parse and display a php page situated in CONTENT directory.  <br>
          Likewise, the modules may perform different actions, according to the parameter received by GET. These actions are performed using the following syntax :<br>
          <a href="admin.php?m=module_name&action=action_type" target="_blank">admin.php?m=module_name&action=action_type</a><br>
          Example: To record a new user the request will be as follows:<br>
          <a href="admin.php?m=login_new&action=new_user" target="_blank">admin.php?m=login_new&action=new_user</a><br>
          The actions and the requests are sent through GET method in order to be able to use them in the links and the menus within the site. The result of actions and the request from the forms is sent through POST so that it cannot be modified by every user... </p>
      <p align="justify"><em><strong>II.a. The login module - login.php, login_ver.php si login_new.php</strong></em><a name="modul_logare"></a><br>
        <br>
        That module is in fact made of 3 files :<br>
        - login.php - which contains the login form and the part of session verification and displaying the user and the access rights.<br>
        - login_ver.php - which contains the part of user verification and also to start the session and session variables.<br>
        - login_new.php - which contains the part of creating new users, modifying user's rights, user erasing, changing user password and the part of disconnecting and destroying the session and the session variables.<br>
        In the next part it is a description of every file separately. (It is possible in the next versions to join the 3 files in one module.)</p>
      <p align="justify"><em><strong>II.a.1. Login - (login.php)</strong></em><a name="login"></a><br>
        <br> 
         In the first page of the site there is a login form through user and password. This part is controlled by the <em>login.php</em> file. If someone connects, this module verifies if the session was initiated and also the name and the password that were used. If the session is initiated the data from the session variables is absorbed and it is displayed the connected user name and the access rights. If the session is not initiated, the login form appears. After sending the connection request from the form, it is appealed the file <em>login_ver.php</em>. When a new user enrolling is wanted, there is a link below the form which through the sent parameters appeals the file <em>login_new.php</em> that initiates the procedure of creating a new user. Likewise, if wanted a password change, there is a link which through the sent parameters appeals the file <em>login_new.php</em> that initiates the procedure of changing the password. 
After sending the data from the form, there are verified in the database the user and the password and if there is a match the session variables are initiated. If the user and the password do not match, the login attempt is saved in the database and there is another request for user and password. If in config.php file is mentioned a maximum number of login attempts, after this number the visitor will not be able to login even if he types the accurate user and password. This blocking is active until another visitor logs in. When logging in there is an automated erasing from the list of unsuccessful attempts to log in and thus the visitor temporarily blocked because of unsuccessful attempts will be able to log in again. <br>
         Beginning with version  1.0 were added 2 new options for login:<br>
         - permanent password and<br>
         - hidden user<br>
         For security reasons, it can only be selected only one of the 2 options. If you select &quot;Permanent password&quot; it will be created a cookie in which will be saved the login data, and for 100 zile (the value set for this cookie) you will not have to  login.<br>
         If you select &quot;Hidden user&quot;, you may enter the site with the same rights but without saving the  in the session variables the name of the real user. It will also be created a cookie for 1 hour and you will have to login again after 1 hour. <br>
        For your safety, do not use simple passwords for the users with rights between 1-4 because they can be broken quite easily (use longer passwords and with different characters like uppercases + lowercases + special characters) - a password formed of 4 letters for instance all with lowercase can be find in less than 30 seconds, and a password of 4 letters using both lowercase and uppercase and special characters can be find in 3-4 days so if you use a password over 7 characters there are weak chances to be find. Still for your safety, specify <strong>config.php</strong> the no of unsuccessful attempts that a user is allowed, thus if someone tries to break a password by repeated attempts, the access should be blocked after <strong>x</strong> attempts. </p>
      <p align="justify"><em><strong>II.a.2. Rights and access levels</strong></em><a name="drept_acces"></a><br>
        <br>
        There are 6 access levels to the site, each level having access to certain modules and being able to perform certain operations within the pages from the site. The access levels reach from 1 to 6 and are suggestively named this way:<br>
        1- Administrator<br>
        2 - Sub-Administrator<br>
        3 - Moderator<br>
        4 - Editor<br>
        5 - User<br>
        6 - Guest (or visitor)<br>
        A visitor who is not logged in has got access level 6. After recording a new user, the last automatedly receives access level 5. Beginning from the level 4, each user that has access level between 1 and 4 may give another access rights to another users who have access level smaller than theirs. For example, a Moderator (who has access level 3) may give to every user an access level between 3 and 5. In the same way an Editor may give access rights between levels 4 and 5. As you may notice there can not be given access rights higher than the one who wants to give the rights nor he can change the rights of a user who has an access level higher than his. Thus if User_1 (access level 4) wants to modify the access rights of User_2 (access level 3) shall not succeed because User_2 has higher rights than User_1.<br>
         The menus appear according to the access level. The menus have access levels just like users and appear only to the users with access level equal or higher (meaning a Moderator shall not see the menus for Administrator for instance). Still according to the access level, a user is allowed to have access to certain modules or to perform certain actions...<br>
        Starting from version 1.0 it was created a demo page that is saved in &quot;CONTENT&quot; directory and is named <em>demo_page.php.</em> Use the infos and the variables from this page to create by yourself content pages that can be accessed only by users with a certain access level. </p>
      <p align="justify"><em><strong>II.a.3. Creating new user</strong></em><a name="creare_user"></a><br>
        <br>
        To create a new user it must be accessed the module <strong>login_new.php</strong> which has the following parameters:<br>
        <a href="admin.php?m=login_new&action=new_user" target="_blank">http://www.site_name/admin.php?m=login_new&action=new_user</a><br>
There are 3 requested parameters initially: User name, Password and e-mail. <br>
User name must be composed of minimum 3 characters and the password of minimum 4 characters. The e-mail address must be a valid address. After sending the data it is checked if the user name or the email address does not already exist in the database. If it does, then it is displayed an error message and it is another request for a user name, a password and an email address. If the email address already exists in the database it is displayed a message with the recommendation that the user should try to change the password in case he/she forgot the password. If the verification is ok and the data does not exist in the database, than the data about the new user is saved and an email is sent to the specified address. The user may log in already and visit the site. The enrolled users automated receive access level 5 (User level). <br>
If you do not have an active mail function on the server, the message for he enrolled user can not be delivered, but the login operation will proceed perfectly.</p>
      <p align="justify"><em><strong>II.a.4. Changing user's access rights</strong></em><a name="schimbare_drepturi"></a><br>
        <br>
        In order to change a user's access level, it must be accessed the module login_new.php with the following parameters: <br>
        <a href="admin.php?m=login_new&action=new_right" target="_blank">http://www.site_name/admin.php?m=login_new&action=new_right</a><br>
        Access to this section is allowed only for users with access level between 1 and 4. A user with access level 4 may give access rights only for levels 4, 5. A user with access level 3 may give rights for levels 3, 4, 5. Access levels of users with higher levels cannot be changed.  <br>
        In the form that appears when changing access rights it is requested user name (Attention: User searching is "case sensitive" meaning there is a difference between upper case and lower case; thus the user named User_1 is not the same with user_1) and it is requested the  access type that you wish to  give to that user. If the change was successfully executed, a confirmation message will appear and it will redirect to main page. If during the attempt to change the rights appeared an error the last will be displayed. It is recommended to give administrator rights, for security reasons, only to trusted persons. Likewise, when you create menus, be careful what access level you give to that menu in order to be accessed by users with adequate rights.</p>
      <p align="justify"><em><strong>II.a.5. Changing user's password</strong></em><a name="schimbare_parola"></a><br>
        <br>
        Access to password changing is done by sending the following request:<br>
        <a href="admin.php?m=login_new&action=new_pass" target="_blank">http://www.site_name/admin.php?m=login_new&action=new_pass</a><br>
        To change a password it is requested the following data: user, new password and the email by which the user was enrolled.<br>
        If the user is logged, inside the form will appear his name in user field. If the field for generating a new password is chosen, it will be generated a random password and the data inserted in "new password" field will be ignored. After sending the data requested by the form it is verified if the email address matches user name. If affirmative, the request of new password is saved within a temporary field in the database, a link is generated and the last one is sent at the specified email to be confirmed. The new password will be active only when the user clicks on the sent activation link. After activation, the user may connect with the new password. If you do not have an active mail function on the server, only the administrator password can be changed, by direct access to the database. </p>
      <p align="justify"><em><strong>II.a.6.  Erasing user</strong></em><a name="stergere_user"></a><br>
        <br>
        To erase a user the following request must be sent:<br>
        <a href="admin.php?m=login_new&action=new_del" target="_blank">http://www.site_name/admin.php?m=login_new&action=new_del</a><br>
        User erasing can be done only by a user with access level 1,2 or 3. It is requested the user name (case sensitive) and after the verification it is erased from the database.</p>
      <p align="justify"><em><strong>II.a.7. Disconnecting the user </strong></em> <a name="deconectare_user"></a><br>
        <br>
        In case of log-out you must access the <strong>login_new</strong> module with the following parameters: <br>
        <a href="admin.php?m=login_new&action=dec" target="_blank">http://www.site_name/admin.php?m=login_new&action=dec</a><br>
        This command destroys the session and the session variables and redirects to main page to clean the cookies. There is no access restraint at this level. It is possible that some browser versions cannot erase the cookies, but beginning with version 1.0 before attempting to erase the cookie it will be changed the stored data thus the cookie is not valid and won't be used again.. After a new login it will be created  another cookie with the new values. <span class="adaugari_manual"><br>
        <em><strong><br>
        </strong></em></span><em><strong>II.a.8. Displaying registered users</strong></em><span class="adaugari_manual"><a name="viev_useri"></a> <br>
        <br>
        </span>In order to see all enrolled users and details about them, use the following link: <br>
        <a href="index.php?c=viev_user">index.php?c=viev_user        </a><br>
        This file is an add-on type and is not a part of login module, is used only to display  details about users. </p>
      <p align="justify"><em><strong>II.b. The menus module</strong></em><a name="modul_meniuri"></a><br>
        <br>
        The menus module is probably the most important module after the login one. To access it  use the command:<br>
        <a href="admin.php?m=menu" target="_blank">http://www.site_name/admin.php?m=menu</a><br>
        This module may be accessed only if you have one of access levels 1-4. You may see all existent menus in the database even the ones created by other users, but you may edit or erase only the menus with access level equal or smaller than of connected user. (If you have access level 3 you may see all the menus but you can edit or erase only the menus with access level from 3 to 6). You may create any type of menu (text/html/css/java) but be careful at the access level of the menu (if you gave access level larger than yours, the next time you may not access it). It is recommended to create the same menu for each language mentioned in the site (the links from the menus will remain the same only the language will be changed). Thus for each language there will be displayed the specific menus. You do not need to change the content because it may exist more pages with the same name but with different languages, and when it is requested a certain page, the application will display the page witch matches the specified language. <br>
To be more accurate for the main page there is in the menu <HOME> the &lt;HOME&gt;  button that has the following address: <a href="index.php?p=default"><em>index.php?p=default</em></a>. If the settings are for English, the  default page will be displayed in English and if the settings are for Romanian, the default page will be displayed in Romanian (both have the same link and the same name, the only difference is the language). </p>
      </p>
      <p align="justify"><em><strong>II.b.1. Creating new menus </strong></em><a name="creare_meniu"></a><br>
        <br>
        In order to create a new menu, the menu module is accessed this way:
        <br>
        <a href="admin.php?m=menu&action=new" target="_blank">http://www.site_name/admin.php?m=menu&action=new</a><br>
      Inside the page that appears it is requested the menu type (which can be horizontal or vertical) and according to the type of menu it is chosen the place to display the menu. Thus, for horizontal menus it can be displayed a menu above the logo, at the middle (meaning below) and down the page. For vertical menus it can be displayed on the right or the left. If a template does not allow a certain adjustment, the menus will be all displayed in the section allowed by the template (thus in the administration page for instance, the menus from the right are still displayed on the left, arranged below the first). <br>
        Next it is requested the menu priority. Priority means which menu will be displayed first (if there are more menus and they have the same displaying place, the one with priority 1 will be displayed first, afterwards the one with priority 2 and so on. If 2 menus have identical settings (type and location and priority) then the first will be displayed the one which has the id smaller in the database (meaning the one that was first created). It can be set 30 priorities (in general I do not think that exist web sites that will display more than 30 menus on the page). <br>
        The next requested thing is the language. If you wish the menu to be displayed only if the visitor sets a certain language, write the name of that language (i.e. for Romanian write ro, for English write en, for both languages write ro, en - separated by comma and 1 space); it may be mentioned as many languages as you desire. If the menus does not have correctly set the language it will not be displayed (you may try to save it with <all> for the language bit it is possible to give up to this agreement in the next versions). Likewise you must create the same menus for each language that exists on the site, keeping the links but changing the language. <br>
        The menu rights were previously discussed at the section  
        <a href="#schimbare_drepturi">II.a.4. Changing user's access rights.</a> According to the rights you allocate to that menu, only the users that have the same access level or larger may access that menu. In order to be seen by all users no matter the rights, select Guest and if you wish the menu to be accessed only after login, select User.<br>
        The last field is for inserting the html code of the menu. If inside the menu you use images, these must be saved before on the server in the adequate location (preferably in that template in BUTTONS directory, so that for each theme you have the adequate images). The menu is created before in an editor so that the resulted code to be saved in this field. The menu can be of any type (text, html, css or java) and after the editing it will be saved in the database. It can be used div instead of tables and style css for colours (in this case add the style in css.php file of the theme).<br>
        If the menu was added it will show up in the list when displaying the menus from the database. <br>
        Begging with version 1.0 it must be specified exactly the language for the menu or it cannot be displayed (it was removed the &quot;all&quot; option).<br>
        The menu code and the requested data will be saved in the database, so it will only be allowed css, html and javascript code. You may use images as buttons at the menus; likewise you may create drop-down menus,  but you must  manually the css.php files (if you create the menus using css) from the &quot;THEMES&quot; directory and to save the images for the buttons  in the subdirectory of that theme  (example &quot;themes/red/butons/&quot;). The name of the default style for the  menus created in css is &quot;glink&quot;, so in order to modify the style or the colors of existent menus verify in each theme the css.php file.<br>
        All created menus can be seen by users with access levels 1-4 form the module of modifying the menus, but each user may modify or erase only the menus that have access lower or equal to their (meaning that a  user with access 4 will not be able to  modify or erase a  menu set on levels 1-3 but will see it and access it from the menu of modifying the  menus). </p>
      <p align="justify"> <em><strong>II.b.2. Changing the menu status</strong></em><a name="schimbare_meniu"></a><br>
        <br> 
        If you wish to change a menu already created  or add another button or just want to modify something, you may access the menus modules by specifying the id of that menu and the action of modifying, namely:<br> 
        <a href="admin.php?m=menu&id=1&action=m" target="_blank">http://www.site_name/admin.php?m=menu&id=1&action=m</a><br>
        In the example from above, m=menu is the module that is loaded in admin.php page, id=1 is the id of the menu that resides in the database and action=m is the action that will be performed for the chosen menu (in this case m=modification). If the requested id does not exist in the database it will be displayed an error message. Likewise if the requested menu has access level larger than of the user that requested the modification, it will appear an error message.<br>
        If the verification is all right it is displayed a form which initially has set the status of menu taken from the database. After the user performs the modifications, the menu with the new settings is updated in the database (it is not written a new menu, but overwritten the settings from din the database that match the id). Attention to access level that is set; if it is larger than yours, the next time you will not be able to access it.</p>
      <p align="justify"><em><strong>II.b.3. Erasing the menus</strong></em><a name="stergere_meniu"></a><br>
        <br>
        To erase a menu, it is accessed the menus module with the following parameter:<br>
         <a href="admin.php?m=menu&id=1&action=s" target="_blank">http://www.site_name/admin.php?m=menu&id=1&action=s</a><br>
         where m=menu is the module that is loaded in admin.php page, id=1 is the id of the menu that is found in the database and action=s is the action that will performed for the chosen menu (in this case s=erasing). If the requested id does not exists in the database it will be displayed an error message. Likewise, if the requested menu has access level larger than of user that requested the action, it will appear an error message. (a user with access level 4 - for instance - will not be capable of erasing or modify the menu set on level 1-3).<br>
        If the access verification is all right, the menu corresponding to the requested id will be erased from the database. <br>
		<em><strong><br>
        II.b.4. Displaying existent menus </strong></em><a name="afisare_lista_meniuri"></a><br>
        <br>
        In order to see what menus exist in the database use the following link:<br>
        <a href="admin.php?m=menu">admin.php?m=menu </a><br>
        It will appear a list with the existent menus in the database  created for a level equal or lower than of current user. (In order to see all the menus you must be logged in as  administrator).</p>
      <p align="justify"><em><strong>II.c. The content module</strong></em><a name="modul_continut"></a><br>
        <br>
        This module is in MODULES directory and is named  <em>content.php</em>. It is accessed through the following link:<br>
        <a href="admin.php?m=content" target="_blank">http://www.site_name/admin.php?m=content</a><br>
        After accessing this module, you must be logged in order to be able to work with this module and the access level must be between 1-4. You can use this module to insert content in your pages (articles, images, files). With this module you can also verify the installed version of FAR-PHP (useful in the case of an update). To verify the installed version perform the following command: <br>
        <a href="admin.php?m=content&ver=ok" target="_blank">http://www.site_name/admin.php?m=content&ver=ok</a><br>
        It will be displayed the installed version, the installation address, the installation date and  the name of the administrator (that was requested at the initial installation) with the email address. <br>
        If you wish to insert an article, please access this module and a form will appear. This form asks if the article shall contain pictures (if so, you have to check the cassette and to select the number of pictures). If you select this option a form will appear to upload the pictures on the server. (If you wish to transfer it by ftp, all the images from the articles must be saved in <em>content/images/</em> directory and the path to display these images must be written in the article this way:<br>
        <em>&lt;a href=&quot;content/images/picture_name.extension&quot; target=&quot;_blank&quot;&gt;<br>
        &lt;img src=&quot;content/images/picture_name.extension&quot; alt=&quot;text alternativ&quot; width=&quot;4&quot; height=&quot;4&quot; border=&quot;0&quot;&gt;<br>
        &lt;/a&gt;</em><br>
The next point in the form is whether you wish to upload files on the server (if the article mentions zip archives or another type of file that will be downloaded you must select this option). A form will appear to upload these files on the server. If you wish to upload these files by ftp, they must be saved in <em>content/files/</em><br> directory.
The last point from the form is whether the article shall contain php code that must be parsed on the server. If so, you must have this file with the php extension, that will be uploaded on the server in <em>content/</em> directory and the access to this file shall be done this way:<br>
<a href="#">http://www.site_name/index.php?c=file_name</a><br>
Attention: in order to access the files that contain php code there is no need to specify the path or the extension, FAR-PHP shall automately put the extension and will search the file in <em>content/</em><br> directory.
If you wish to insert a simple article without pictures or other files do not select anything just push the Next button. Another form will appear in which you have to insert the text of the article (the text may be formatted using html or java code), the page title (this title will appear if you wish to display all existent articles), the file name (you must specify a name by which this file will be saved - the name must be without spaces or special characters and without extension), the author of the article (you may insert your nick if the article is written by yourself), the email address of the author (it must be a valid one), and the language of the article (if the article may be displayed regardless of  user's preferences, please specify: 0 (zero)).  You may check the article by pushing the 'Verify' button; the article will be shown exactly the way it will appear on the site and you can make the necessary adjustments. If you push the 'Send' button, the article will be saved in the database and it will be visible for the users from the menus or from the 'Content' page. <br>
It is possible for the next versions to be inserted new facilities for this module ...<br>
<br>
<em><strong>II.c.1. Adding new content (new articles)</strong></em><a name="modul_continut_nou"></a><br>
<br>
It may be inserted 2 types of content: <br>
a. Simple content in text format (html, css or java formatted)
<br>
b. Content with php scripts that will be parsed on the server.<br>
<br>
<em><strong>II.c.1.a. Adding content in text format</strong></em><a name="continut_html"></a> <br>
<br>
This type of content may be simple text, formatted text with html or css code or client side scripts (java). The articles saved this way may contain images, links or files for download. Next is detailed the way to insert the article according to the situation :<br>
<br>
<strong><a name="continut_Alternative_1"></a>Alternative 1:</strong> Simple article, that does not contain images or files, but it may contain html formatted code <br>

You appeal next the module of inserting content and you push the 'Next' button. In the form that appears, in the field that requests text insertions you have to write the text of the  article together with the html code for the article shaping (you can use copy-paste if the article is already written in a text editor).<br>
In the field <em>Page title </em> you will insert the article title (preferably maxim 256 characters, but in this version there is no limit). The title will be displayed in the content page of the articles from the site and it will appear also in the article itself as title.<br>
The next filed is  <em>File name</em>. Here it must be inserted a name by which the article will be saved in the database. By this name the article will be accessed from the menus this way:<br>
<a href="#">http://www.site_name/index.php?p=article_name</a><br>
where <em>article_name</em> will be in fact the file name that you specified (it is recommendable that you do not specify file names too long, or that contain spaces or special characters. Likewise as the article is saved in the database, you do not need to specify an extension.<br>
The next field from the form is <em>Author</em>, where you insert the name of article' author (if the article is written by another person the Copyright Law mentions that you must specify the source of the article - in this case the author's name). If the article is written by you, specify your name or your nick-ul, in order to acknowledge your author rights.<br>
Next is the field to insert the e-mail address of the author (it must be a valid address). If the author's address is not known, it will be inserted the e-mail address of the person that posted the article, in order to be contacted if someone is interested by another details regarding that article. <br>
In the last field will be inserted the article's language. If the article is to be displayed regardless of visitor's preferences, it must be specified 0 (zero).<br>
If you wish to see how the article looks before being saved in the database push the "Verify" button and the article will be displayed exactly how it will appear on the site. You can make the eventual modifications before pushing the "Send" button (in this case, the article will be saved in the database and it will be accessed by the visitors from the site)<br>
<br>
<a name="continut_Alternative_2"></a><strong>Alternative 2</strong>: Simple article, but that contains images.<br>
Identical with the first alternative, you only have to select the number of images that you wish to upload on the server before inserting the article. The images will be saved in <em>content/images/</em> directory so the links from the article for the images will be vor like<br>
<a href="#">&lt;a href=&quot;content/images/picture_name.jpg&quot; target=&quot;_blank&quot;&gt;<br> 
&lt;img src=&quot;content/images/picture_name.jpg&quot; alt=&quot;alternative text&quot; width=&quot;152&quot; height=&quot;240&quot; border=&quot;0&quot;&gt;<br>
&lt;/a&gt;</a><br>
Attention! In order to load  images on the server, the server must allow it and chmod must be set correct for the &quot;CONTENT&quot; directory.<br> 
        <br>
		<a name="continut_Alternative_3"></a><strong>Alternative 3:</strong> Simple article, but that contains links to the files for download.<br>
Just like the second alternative the only difference is that instead of pictures there will be uploaded files of any type (so it must be select the number of files for download instead of pictures). The files are uploaded in <em>content/files/ </em>directory. <br>
Attention! In order to load  images on the server, the server must allow it and chmod must be set correct for the &quot;CONTENT&quot; directory. <br>
<br>
<a name="continut_Alternative_4"></a><strong>Alternative 4:</strong> Simple article, but that contains images and links to files for download.<br>
          The same as the previous alternatives, only it has to be selected, before posting the article, the number of pictures and files that will be uploaded on the server with the article. In case of errors, the pictures and the files can be manually transferred by ftp in the specified directories, and you will post through this module only the article itself. <br>
Attention! In order to load  images on the server, the server must allow it and chmod must be set correct for the &quot;CONTENT&quot; directory <br>
<br>
<a name="continut_Alternative_5"></a><strong>Alternative 5.</strong> Article or page that contains php scripts that must be parsed on the server.
<br>
          If you check this option the text that you insert will be saved as a file with the name specified by you in <em>content/ </em>directory. In case of upload errors,  you can save manualy the file by ftp in the mentioned directory. Attention! In order to load images on the server through the interface, the server must allow it and chmod must be set correct for the &quot;CONTENT&quot; directory.<br>
          <br>
<br>
<em><strong>II.c.1.b. Adding pages with php script that are parsed on the server </strong></em><a name="continut_varianta_5"></a><br>
		<br>
         Is is preferably that pages with php scripts be saved in <em>content/</em> directory. This way, your pages will be included in the site's theme through the link:<br>

         <a href="#">&lt;a href=&quot;index.php?c=file_name&quot;&gt;File_name&lt;/a&gt;</a> <br>
        <br>
        <em><strong>II.c.2. Modifying the existent content </strong></em><a name="modul_content_2"></a><br>
        <br>
        In order to modify the content of a page that is saved in the database, use the module  <em>content_2.php</em> this way:<br>
        <a href="admin.php?m=content_2">admin.php?m=content_2</a><br>
        A page will appear with a link that contains all the titles from the database. Select from the list the page that you wish to modify (or erase) and after it was selected specify the desired action (modification or erasing). Attention, you cannot specify manually the page name; it must be selected from the list. After specifying the modify action it will next be displayed the chosen page and you will be able to modify the content. <br>
        <br>
        <em><strong>II.c.3. Erasing the existent content <a name="modul_stergere_continut"></a><br>
        <br>
        </strong></em>To erase the content of a page that is saved in the database, use  content_2.php module just as we mentioned before in chapter II.c.2. Attention, erased data cannot be recovered without a back-up of the database that has to be made before. <br>
        <br>
        <em><strong>II.c.4. Displaying existent articles for a certain language</strong></em><a name="afisare_articole_limba"></a><br>
        <br>
        In order to see what articles there are in the database for the current language you may create a  menu using the following link:<br>
        <a href="index.php?p=content%20">index.php?p=content 
        </a><br>
        It will be displayed a list with the titles of all the articles from the database in the current language. In order to see also the other articles written for the other languages, you must change the current language. <br>
        <br>
        <em><strong>II.c.5. Displaying all the articles from the database  </strong></em>        <a name="afisare_toate_articolele"></a> <br>
        <br>
        If you wish to see all the articles from the database, nomatter what language they were written for, you may enter in the module of modifying the content and see it on the  list. You may also use this link that will appeal the modifying module and will display the list:<br>
        <a href="admin.php?m=content_2&action=list">admin.php?m=content_2&amp;action=list </a></p>
      <p align="left"><em><strong>II.d. The news module</strong></em><a name="modul_stiri"></a><br>
        <br>
        This module in an idea at this moment, we cannot say anything about it until it is finished.</p>
      <div align="justify"><br>
          <em><strong>II.e. The language module <a name="modul_language"></a><br>
          <br>
          </strong></em>This module was inserted afterwards creating the initial alternative of the project, and at this moment it only functions 2 languages: Romanian and English. It is mainly based on the language files from  <em>codes/ </em> directory and on the <em>language.php</em> module from the modules directory (<em>modules/</em>). Through its use it is displayed the content in every page according to the chosen language, so it may exist in the database 2 articles with the same name but with different language. In this way is is easier when creating the menus, as there is the same link for the same page, the difference is given by the chosen language. <span class="adaugari_manual">Incepand de la versiunea 1.0 fiecare modul adaugat ulterior are propria functie sau variabila care contine mesajele necesare acelui modul. Modulele nu influenteaza continutul pagini dar contin mesajele de atentionare sau eroare care se afiseaza in functie de limbajul specificat. In cazul in care creati pagini pentru un limbaj care nu este inclus in versiunea originala, unele module vor afisa mesajele de eroare si atentionare in limba engleza, in cazul in care nu au fost traduse si pentru limba respectiva.</span><br>
          <br>
          <em><strong>II.e.1 Setting the main language</strong></em><a name="setare_limbaj_principal"></a><br>
          <br>
          Setting the main language is performed when installing the project on the server, by choosing from the list the default language. If you wish to change the main language afterwards you must manually edit config.php file and to modify the lines :<br>
          $mesaje = "codes/language_ro.php"; // here specify the main message file<br>
          $primary_language = "ro"; // here specify in short the main language (so if the message file is language_en.php here specify the letters en; if it is language_jp.php specify the letters jp)<br>
          Likewise, when inserting the articles and the menus in the database you must specify exactly these letters for each language separately, or the article will not be displayed in the page. <br>
          <br>
          <em><strong>II.e.2. Changing between different languages <a name="schimbare_limbaj"></a> </strong></em><br>
          <br>
          Changing between languages is achieved by simply selecting from the language.php module the favorite language. If your web page contains only one language, you may give up at displaying this module inside the pages by erasing from the theme pages the line that loads this module. Attention! You must not erase the module but only the code line that appeals the module, or it might appear problems.<br>
          The code line that appeals this module is:<br>
          $cerere = 12;<br>
          include ("codes/body.php"); <br>
          Please do not remove something else because it is possible to appear errors.<br>
          <span class="adaugari_manual">Incepand de la versiunea 1.0 s-a renuntat la acesta abordare si a fost simplificata printr-o singura linie de cod si anume:<br>
          <em>body_far(&quot;language&quot;); // includere partea de limbaj </em><br>
Aceasta linie se poate sterge si nu se va mai include modulul de limbaj si in acest caz se va utiliza doar limba specificata in fisierul config.php</span><br>
          <br>
          <br>
          <em><strong>II.e.3. Creating a new language (translating an existent one) </strong></em><a name="creare_limbaj"></a><br>
          <br>
          In order to accept a new language and to display the articles and menus specific to that language, it must be translated the message file to the new language and saved with a new name in <em>codes/</em> directory. Thus if you translate the message file in Russian for instance, the file must then be saved in <em>codes</em> directory by the name  <em>language_ru.php</em> and you must modify the <em>language.php</em> module from the <em>modules</em> directory in order to load the new language. If you translate the message file you may send it to us and we will automatedly integrate into the project, and you will be given the credit for translating that file. You will receive the next version of the project that will contain the settings for the new language. 
          </p>
          <span class="adaugari_manual"> Incepand de la versiunea 1.0 fiecare modul aditional contine propriile mesaje de eroare si atentionare, care trebuie tradus separat. In general fiecare modul contine o functie sau un array cu mesajele care sunt folosite de acel modul si care se poate modifica usor pentru noul limbaj. </span><br>
          <br>
          <em class="adaugari_manual"><strong>II.f. Modulul panoul de comanda</strong></em> <a name="cpanel"></a><br>
          <br>
Panoul de comanda este de fapt un simplu fisier php care contine linkuri catre toate modulele existente, facand astfel mai usoara administrarea. In functie de ce module sunt instalate, vor apare linkurile pentru respectivul modul (daca adaugati un modul si acesta nu apare in panoul de control trebuie sa faceti un update la cpanel.php - pentru aceasta vizitati regulat pagina de download de la www.far-php.ro)<br>
<br>
<span class="adaugari_manual"><em><strong>II.g. Modulul pentru monitorizarea accesarilor nepermise si al botilor</strong></em></span> <a name="robots"></a><br>
<br>
Incepand de la versiunea 1.0 a fost adaugata si o monitorizare minimala a accesarilor nepermise. Pentru aceasta a fost creat un tabel nou in baza de date numit &quot;robots&quot; iar in fiecare subdirector a fost creat fisierul &quot;index.php&quot;. In cazul in care cineva incearca sa intre intr-unul din aceste subdirectoare scriptul existent in index.php salveaza un log in baza de date care contine data, ip, browser, referer (daca este) si adresa la pe care s-a incercat sa se intre. Dupa salvarea datelor in baza de date se redirecteaza vizitatorul catre pagina principala. Puteti vedea acest log folosind adresa <br>
<a href="admin.php?m=robots">admin.php?m=robots</a><br>
Pentru a bloca o adresa ip puteti folosi modulul <a href="#blockip">blockip</a>. In mod normal, robotii de cautare folosesc informatiile scrise in header si in fisierul <em>robots.txt</em> pentru a indexa paginile de pe site. Puteti edita headerul si fisierul robots.txt pentru a specifica robotilor in care directoare si fisiere nu au voie sa umble. In cazul in care unul din acesti roboti nu respecta specificatiile date il puteti vedea in log, si astfel ii puteti bloca accesul pe viitor. Pentru alte intrebari referitoare la indexarea unui site sau la robotii de cautare cititi <a href="http://www.robotstxt.org/wc/faq.html" target="_blank">http://www.robotstxt.org/wc/faq.html</a><br>
<br>
<em class="adaugari_manual"><strong>II.h. Modulul de instalare a proiectului FAR-PHP</strong></em> <a name="install"></a><br>
<br>
Dupa copierea fisierelor pe server, in browser va apare partea de instalare a proiectului. In acest modul puteti selecta limba in care se va face instalarea. Dupa selectarea limbajului, informatiile cerute in continuare vor fi folosite pentru crearea fisierului <em>config.php</em> si a tabelelor din baza de date. In functie de limba selectata pentru instalare, se vor crea in baza de date meniurile predefinite (daca limbajul e setat pe engleza meniurile vor apare in site doar in engleza, si invers). Datele pentru configurare se impart in 3 parti:<br>
<br>
<em>Setari pentru Baza de date MySQL</em><br>
Gazda (Host): = aici se cere adresa unde se afla baza de date (de obicei este <em>localhost</em>) <br>
User: = userul cu care va conectati la baza de date (de obicei <em>root</em>) <br>
Parola: = parola setata pentru MySQL (in cazul in care lucrati pe local si nu aveti setata o parola, instalarea nu va rula mai departe fara o parola)<br>
Nume baza de date: = numele bazei de date unde vor fi create tabelele pentru FAR-PHP<br>
Prefixul tabelelor din baza de date: = se cere prefixul care va fi pus inaintea tabelelor, astfel ca puteti avea mai multe proiecte FAR-PHP in aceeasi baza de date dar care difera prin prefix.<br>
<em>Setari pentru server: </em><br>
Prefixul la cookies si variabilele de sesiune: = prefixul care va fi pus inaintea variabilelor de sesiune si a cookies, in cazul in care aveti mai multe proiecte FAR-PHP pe acelasi domeniu sa poata fi diferentiate prin prefix.<br>
Diferenta de ora de pe server fata de ora locala (+ sau - x ore): = in cazul in care ora de pe server difera fata de ora locala, puteti seta aici diferenta.<br>
Adresa web a paginii: = aici scrieti exact adresa unde este instalat proiectul FAR-PHP (exemplu http://localhost/far-php/ ) aceasta adresa este folosita pentru redirectare catre pagina principala in toate modulele proiectului.<br>
Tema principala a site-ului: = template-ul principal pe care vreti sa il vada vizitatorii<br>
Limbajul principal: = limbajul principal in care vor fi afisate paginile site-ului (poate diferii de limbajul selectat pentru instalare)<br>
<em>Setari de administrare:</em><br>
Parola criptata: = felul cum se vor salva parolele in baza de date (este bine sa fie salvate criptate pentru mai multa siguranta)<br>
Nr. de incercari de logare nereusite: = in cazul in care un user incearca de mai multe ori sa se logheze dar nu stie userul/parola corecte, peste nr de incercari setate i se va bloca temporar accesul. Este recomandat pentru siguranta un nr de maxim 5 incercari, astfel se evita incercarea de obtinere a accesului prin combinatii succesive de user/parola.<br>
Numele userului cu drept de adminstrator: = Aici se cere userul care va fi salvat in baza de date cu drepturi de administrare.<br>
E-mail administrator: = adresa de e-mail folosita de modulele proiectului pentru a primi/trimite mesaje.<br>
Parola admin: = parola pentru userul cu drepturi de administrator (recomandam sa aiba peste 7 caractere inclusiv litere mici si mari si caractere speciale, pentru a fi mai greu de spart)<br>
Numele userului cu drept de Sub-administrator (daca exista): = optional se cere userul care va fi salvat in baza de date cu drepturi de sub-admin.<br>
E-mail sub-administrator: = optional adresa de mail pentru sub-admin<br>
Parola sub-administrator: = optional parola pentru sub-admin<br>
Mesaj pentru partea de jos a paginii: = mesajul care va apare in josul paginii (se accepta orice cod html)<br>
<br>
In cazul in care au fost completate corect campurile cerute se vor genera tabelele in baza de date, se vor crea meniurile pentru administrare si se va incerca generarea fisierului config.php. In cazul in care nu se reuseste generarea acestui fisier, va apare in browser textul care trebuie copiat identic in fisierul config.php si apoi salvat manual prin ftp pe server. Dupa salvarea acestui fisier pe server, proiectul este instalat corespunzator si se poate sterge fisierul <em>install.php</em> <br>
In cazul in care apar alte mesaje de eroare la instalare, sau aplicatia nu functioneaza corect dupa instalare, cititi capitolul <a href="#instal_problem">Probleme aparute la instalare</a><br>
<br>
<em class="adaugari_manual"><strong>II.i. Modulul de schimbare teme (template)</strong></em> <a name="mod_ch_template"></a><br>
<br>
Acest modul schimba tema initiala a paginii cu o alta tema existenta pe site. Pentru accesare utilizati adresa de mai jos:<br>
<a href="admin.php?m=ch_template">admin.php?m=ch_template</a><br>
Acest modul citeste numele temelor existente in directorul THEMES si le afiseaza in lista. Dupa selectarea noii teme se modifica valoarea variabilei de sesiune cu noua tema si se modifica cookies. In cazul in care nu se poate salva noul cookies, data viitoare tema afisata va fi tot cea initiala. <br>
<br>
<em class="adaugari_manual"><strong>II.j. Modulul pentru blocare ip</strong></em> <a name="blockip"></a><br>
<br>
Acest modul permite blocarea accesului anumitor vizitatori pe o anumita perioada la site. Cu ajutorul acestui modul se pot adauga/modifica/sterge adrese de ip. Doar useri cu nivel de acces 1-4 pot avea acces la acest modul. <br>
<br>
<em class="adaugari_manual"><strong>II.j.1. Afisare lista cu ip-uri blocate</strong></em><a name="blockip_lista"></a><br>
<br>
Pentru a vedea ce adrese de ip sunt blocate si pe ce perioada folositi adresa de mai jos:<br>
<a href="admin.php?m=blockip&action=show">admin.php?m=blockip&amp;action=show</a> <br>
Va apare o lista cu ip, data de start, data de stop, data si ora cand a fost adaugat in lista si 2 link-uri.<br>
Ip =&gt; este ip-ul care este blocat<br>
Data si ora =&gt; este data si ora cand acel ip a fost adaugat in lista<br>
Data start =&gt; este data cand acel ip nu va mai avea acces pe site<br>
Data stop =&gt; este data cand blocarea pentru acel ip va fi terminata si va avea acces pe site<br>
M =&gt; (primul link) permite modificarea informatiilor despre acel ip<br>
D =&gt; (al doilea link) permite stergerea acelui ip din lista <br>
<br>
<em class="adaugari_manual"><strong>II.j.2. Modificare date ip</strong></em><a name="blockip_modificare" id="blockip_modificare"></a><br>
<br>
Pentru a modifica datele despre un ip, puteti folosi link-ul urmator:<br>
<a href="admin.php?m=blockip&action=modify">admin.php?m=blockip&amp;action=modify</a> <br>
Introduceti ip-ul pe care doriti sa il modificati si va apare un formular cu datele existente in baza de date. Dupa modificare, datele noi se vor salva in baza de date in locul celor vechi. <br>
<br>
<em class="adaugari_manual"><strong>II.j.3. Adaugare ip</strong></em><a name="blockip_adaugare"></a><br>
<br>
Pentru a adauga un ip nou in baza de date, folositi urmatorul link:<br>
<a href="admin.php?m=blockip">admin.php?m=blockip</a> <br>
Dupa adaugare, blocarea va deveni activa incepand de la data de start. <br>
<br>
<em class="adaugari_manual"><strong>II.j.4. Stergere ip</strong></em> <a name="blockip_stergere"></a><br>
<br>
Pentru a sterge un ip din baza de date, folositi:<br>
<a href="admin.php?m=blockip&action=del">admin.php?m=blockip&amp;action=del </a><br>
Introduceti ip-ul pe care doriti sa il stergeti si acesta va fi sters automat din baza de date. <br>
<br>
<em class="adaugari_manual"><strong>II.2. Module aditionale (add-on)</strong></em> <a name="addon_modules"></a><br>
<br>
Modulele aditionale (add-on) sunt modulele care nu fac parte din proiectul initial, dar care adaugate in proiect, aduc diferite imbunatatiri si noi facilitati. Aceste module pot fi adaugate/sterse oricand fara a modifica codul initial al proiectului. Pentru adaugarea unui modul acesta trebuie salvat in directorul MODULES si apoi adaugat optional codul in template pentru a fi inclus in acea tema. Adaugarea modulului in tema se face scriind codul de mai jos in locul unde vreti sa se afiseze acel modul (in template) astfel:<br>
body_far(&quot;nume_modul&quot;);<br>
In cazul in care doriti ca un modul aditional sa fie accesat din meniu sau dintr-un link puneti acest cod in acel link:<br>
<a href="#">admin.php?m=nume_modul </a><br>
In continuare sunt descrise modulele oficiale care se pot adauga.<br>
<span class="adaugari_manual"><br>
<em><strong>II.2.a. Modulul pentru control bannere</strong></em></span> <a name="banner"></a><br>
<br>
Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>banner.php</em> in directorul MODULES. Modulul este distribuit in 2 variante, varianta simpla care nu permite monitorizarea dupa click si nici afisarea bannerelor doar odata unui singur vizitator, si care este distribuita in regim GNU/GPL (gratuit pentru utilizare personala, necomerciala, non-profit) si varianta full, care permite monitorizarea bannerelor dupa clik si afisarea bannerelor selectiv (adica un banner se poate afisa aceluiasi vizitato de mai multe ori sau doar o singura data). Varianta full este distribuita doar la cerere in regim GNU/GPL si costa 5 euro/modul/site + 35 euro/site proiectul FAR-PHP (modulul nu functioneaza independent de proiect, iar proiectul este distribuit in aceleasi conditii ca si acest modul - pentru alte detalii cititi capitolul <a href="#licenta">Licenta proiectului</a>) <br>
Pentru a se putea afisa bannerele trebuie inclus codul de mai jos in teme acolo unde doriti sa fie afisat bannerul:<br>
body_far(&quot;banner&quot;);<br>
Nu se poate afisa decat un banner pe pagina. Prima data cand va fi rulat acest modul va genera automat tabelele necesare in baza de date folosind datele din fisierul config.php <br>
Acest modul afiseaza si permite adaugarea/modificarea/stergerea de bannere. Modulul banner.php genereaza 2 variabile de sesiune si anume:<br>
prefix_modul_banner = contine nr bannerului care trebuie afisat<br>
prefix_modul_banner2 = contine una din cele 3 valori posibile pentru a nu se putea afisa de 2 ori in pagina bannerul (in cazul in care se acceseaza modulul cu parametrii)<br>
<br>
<em class="adaugari_manual"><strong>II.2.a.1. Adaugare bannere</strong></em><a name="adaugare_banner"></a><br>
<br>
Pentru a adauga un banner nou folositi urmatoarea comanda:<br>
<a href="admin.php?m=banner&action=new">admin.php?m=banner&amp;action=new</a><br>
Va apare un formular cu urmatoarele campuri:<br>
Codul reclamei: = aici puteti introduce codul html/java script care va afisa bannerul.<br>
Numele reclamei: = aici scrieti numele reclamei (pentru a putea sa o identificati in lista cu bannere)<br>
Adresa web: = adresa web la care se va duce in cazul in care vizitatorul da clik pe banner (optional) <br>
Data start: = data cand doriti sa inceapa afisarea bannerului (in format YYYY-mm-dd)<br>
Data stop: = data cand nu se va mai afisa bannerul (optional)<br>
Bifati daca doriti monitorizare afisare: = in cazul in care se bifeaza se va salva in baza de date nr de cate ori a fost afisat acest banner (optional) <br>
Nr. afisari stop: = in cazul in care introduceti un nr, bannerul se va afisa pana cand nr total de afisari va fi egal cu nr specificat. (optional) <br>
Bifati daca doriti monitorizare click: = in cazul in care aveti versiunea full, puteti selecta aceasta optiune pentru a vedea cati vizitatori au dat clik pe acest banner (optional)<br>
Nr. clik stop: = in cazul in care aveti optiunea full, si specificati aici un nr, bannerul se va afisa pana cand numarul de clik-uri va fi egal cu nr specificat. (optional)<br>
Deci puteti afisa un banner incepand de la data start pana la data stop, sau pana cand se va afisa de x ori, sau pana cand x vizitatori vor da clik pe el.<br>
<br>
<em class="adaugari_manual"><strong>II.2.a.2. Modificare bannere</strong></em> <a name="modificare_banner"></a><br>
<br>
Pentru a modifica datele unui banner existent folositi urmatoarea comanda:<br>
<a href="admin.php?m=banner&action=change">admin.php?m=banner&amp;action=change</a> <br>
Va apare o pagina in care sunt scrise cate bannere exista in baza de date si un formular in care se cere sa introduceti id-ul bannerului pe care doriti sa il modificati (id-ul bannerului il aflati din pagina care va afiseaza toate bannerele - vezi capitolul Afisare lista bannere) Dupa introducerea id-ului va apare un formular cu datele care exista in baza de date pentru bannerul ales si puteti modifica ceea ce doriti. <br>
<br>
<em class="adaugari_manual"><strong>II.2.a.3. Stergere bannere</strong></em><a name="sterg_banner"></a> <br>
<br>
Pentru a sterge un anumit banner folositi urmatoarea comanda:<br>
<a href="admin.php?m=banner&action=del">admin.php?m=banner&amp;action=del</a><br>
Va apare aceeasi pagina ca la partea de <a href="#modificare_banner">modificare bannere</a> unde trebuie introdus id-ul bannerului pe care doriti sa il stergeti. (Atentie! Stergerea este ireversibila) <br>
<br>
<em class="adaugari_manual"><strong>II.2.a.4. Afisare lista bannere</strong></em><a name="toate_banner" id="toate_banner"></a> <br>
<br>
Pentru a vedea toate bannerele existente in baza de date folositi comanda:<br>
<a href="admin.php?m=banner&action=all">admin.php?m=banner&amp;action=all</a><br>
Va fi afisat fiecare banner in parte, id-ul lui si detalii despre el. <br>
<br>
<em class="adaugari_manual"><strong>II.2.a.5. Afisare log banner</strong></em><a name="log_banner"></a> <br>
<br>
Pentru a putea vedea informatii detaliate despre un anumit banner comanda este urmatoarea:<br>
<a href="admin.php?m=banner&action=log">admin.php?m=banner&amp;action=log</a><br>
Va apare un formular in care trebuie introdus id-ul bannerului pentru care doriti informatii (id-ul il puteti afla afisand lista de bannere existente). Dupa introducerea id-ului vor fi afisate informatii detaliate despre acel banner (nr de afisari facute, nr de clik facute etc) <br>
<br>
<em class="adaugari_manual"><strong>II.2.b. Modulul de afisare vizitatori online</strong></em> <a name="online"></a><br>
          <br>
Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>online.php</em> in directorul MODULES. Modulul are 2 moduri si anume: <br>
- In varianta simpla afiseaza nr total de vizitatori existenti pe site in ultimele 5 minute din care nr de useri logati existenti pe site, nr de vizitatori nelogati si nr de useri logati dar ascunsi.<br>
- In varianta extinsa se afiseaza detalii pentru fiecare vizitator existent pe site si anume:<br>
Numele userului (daca nu este ascuns), timpul de cand este online, pagina pe care o viziteaza in acel moment, adresa ip si host.<br>
Pentru a include in site acest modul introduceti in fiecare tema acolo unde doriti sa fie afisat acest modul, urmatorul cod: <br>
body_far(&quot;online&quot;);<br>
Pentru a afisa varianta extinsa a acestui modul folositi urmatoarea comanda:<br>
<a href="admin.php?m=online&action=see">admin.php?m=online&amp;action=see </a><br>
Modulul online.php genereaza o variabila de sesiune si anume:<br>
prefix_modul_online = contine o valoare pentru a nu se afisa eronat informatiile in cazul in care modulul este apelat cu parametrii. <br>
<span class="adaugari_manual"><br>
<em><strong>II.2.c. Modulul pentru dezinstalare</strong></em></span> <a name="uninstall"></a><br>
<br>
Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <em>uninstall.php</em> in directorul MODULES.<br>
Pentru dezinstalarea proiectului FAR-PHP de pe server folositi urmatoarea comanda:<br>
<a href="admin.php?m=uninstall">admin.php?m=uninstall</a><br>
Dupa rularea acestui modul, se va incerca stergerea tabelelor din baza de date care corespund proiectului (atentie, nu se sterg si tabelele create ulterior de catre modulele aditionale), se va incerca stergerea continutului fisierului config.php si dupa aceea se va incerca stergerea fizica a tuturor fisierelor si directoarelor existente pe server in directorul unde a fost instalat proiectul (atentie, se va incerca stergerea tuturor fisierelor din acest director, indiferent ca apartin proiectului sau nu). Toate informatiile privind starea dezinstalarii vor fi afisate in browser, iar dupa terminarea dezinstalarii se va redirecta catre pagina oficiala a proiectului FAR-PHP.<br>
<br>
<em class="adaugari_manual"><strong>II.2.d. Modulul pentru adaugare useri in PHPBB</strong></em> <a name="adduserphpbb"></a> <br>
<br>
Acest modul este de tip add-on (separat de proiect) si se poate adauga copiind fisierul <strong>adduserphpbb.php</strong> in directorul MODULES. Pentru a putea fi functional acest modul, trebuie inlocuita valoarea variabilei <br>
$prefix_tabel_forum_phpbb = &quot;phpbb_&quot;; // modificati daca nu corespunde prefixul tablelelor phpbb<br>
cu prefixul specificat la instalarea forumului PHPBB.<br>
Forumul PHPBB trebuie instalat inainte de a utiliza acest modul.<br>
(deci instalati proiectul FAR-PHP, dupa care instalati PHPBB, dupa care setati valoarea variabilei din modul si copiati modulul in directorul MODULES de pe server) <br>
In cazul in care un vizitator se inscrie in FAR-PHP, acest modul va crea acelasi user/parola si in tabelul din PHPBB, astfel vizitatorul nu va trebui sa se inscrie de 2 ori. <br>
<br>
<em class="adaugari_manual"><strong>II.2.e. Modulul pentru control newsletter</strong></em> <a name="newsletter"></a> <br>
<br>
In lucru<br>
<br>
<em class="adaugari_manual"><strong>II.3. Scripturi aditionale</strong></em> <a name="add_scripts"></a><br>
<br>
Scripturile aditionale (add-on) sunt fisiere php care nu fac parte din proiectul initial, dar care adaugate in proiect, aduc diferite imbunatatiri si noi facilitati. Aceste fisiere pot fi adaugate/sterse oricand fara a modifica codul initial al proiectului. Pentru adaugarea unui script acesta trebuie salvat in directorul CONTENT si apoi adaugat codul in template pentru a fi inclus in acea tema. Unele scripturi se pot folosi si ca module, dar in acest caz trebuie salvate in directorul MODULES. In cazul in care doriti ca un script aditional sa fie accesat din meniu sau dintr-un link puneti acest cod in acel link:<br>
<a href="#">index.php?c=nume_script</a><br>
In continuare sunt descrise scripturile oficiale care se pot adauga.<br>
<br>
<em class="adaugari_manual"><strong>II.3.a. Pagina de contact</strong></em> <a name="contact_1"></a> <br>
<br>
Aceasta pagina este de tipul add-on (separat de proiect) si se poate pune pe server ulterior instalarii. Acest script trimite un mesaj prin intermediul paginii php catre adresa specificata. Pentru a putea fi functional, trebuie modificate corespunzator urmatoarele variabile:<br>
<em>$adresa_pagina_contact = 'index.php?c=contact'; // adresa unde se afla acest fisier<br>
$adresa_de_trimis = $email_admin; // adresa de e-mail folosita</em><br>
In cazul in care scriptul va fi salvat in directorul CONTENT valoarea variabilei <em>$adresa_pagina_contact</em> va fi:<br>
<em>'index.php?c=contact'</em><br>
In cazul in care se doreste folosit ca modul, si va fi salvat in directorul MODULES, valoarea variabilei <em>$adresa_pagina_contact </em>va fi:<br>
<em>'admin.php?m=contact'</em><br>
Pentru cealalta variabila <em>$adresa_de_trimis</em> in mod implicit se foloseste adresa de e-mail a administratorului, care a fost specificata la instalarea proiectului. In cazul in care doriti sa folositi alta adresa de e-mail unde vor veni mesajele, modificati valoarea variabilei <em>$adresa_de_trimis</em> in mod corespunzator:<br>
<em>$adresa_de_trimis = 'adresa@domeniu.com'; </em><br>
Dupa modificarile de mai sus puteti copia fisierul pe server si puteti crea un meniu sau un link catre el. <br>
<br>
<em class="adaugari_manual"><strong>II.3.b. Pagina pentru demonstratii</strong></em> <a name="demo_page"></a><br>
<br>
Aceasta pagina este de tipul add-on (separat de proiect) si se poate pune pe server ulterior instalarii. Acest script este folosit pentru demonstrarea accesului la o pagina si pentru demonstrarea afisarii mesajelor specifice in functie de limbajul ales. Prima parte verifica nivelul de acces, dupa care se creaza o variabila care contine toate mesajele care pot aparea in acea pagina in ambele limbi (romana si engleza) dupa care in functie de drepturile de acces ale vizitatorului afiseaza informatia ceruta in limba specificata. Puteti testa aceasta pagina inainte de logare si dupa logare, in ambele limbi ca sa vedeti ce anume se afiseaza si cum. Puteti folosi variabilele si informatiile din aceasta pagina pentru a va crea propriile scripturi si module pentru pagina voastra.</div>
      <p align="justify"><em><strong>III. Template changing</strong></em><a name="schimba_template"></a><br>
        <br>
        Initially the site's theme is saved in config.php file. In order to change a template (a theme) you must access ch_template.php module this way :<br>
<a href="admin.php?m=ch_template" target="_blank">http://www.site_name/admin.php?m=ch_template</a><br>
It is accessed without a parameter because the module reads the structure from the THEMES directory and displays in the form the themes that were found. After selecting the theme, the old variables are erased (the session and cookie variables) and new ones are initiated that contain the name of the new theme. If the visitor does not accept cookies, the chosen theme can not be selected, or if it is selected, after closing the browser the settings disappear. (it is first initiated the value of a session variable that reads the template  that is saved in config.php file). After initiating this variable it is checked id there is a cookie. <br>
If there is, it is rewritten the value of the variable with the new value from the cookie. <br>
If is does not, it is created a cookie with default theme's value. <br>
After having set the session variable and the cookie, there is a redirection to reinitialize the session. <br>
<br>
<em><strong>III.a. How do I add a new theme </strong></em><a name="adaug_tema"></a><br>
<br>
First of all, the chosen theme must be compatible with the project specifications regarding the themes. If it respects the specifications, you may copy the files of the new theme on the server and only add ypu own css code to the existent one in the theme to match the specific of your page. If you created a theme and yu do not know how to make it compatible with the project specifications, you may send it to us and we will modify it to match the project and after that ypu will be able to download it for free from the download page of the project.  <span class="adaugari_manual">In cazul in care ati descarcat o tema si doar doriti sa o adaugati la pagina dvs. dupa dezarhivare copiati fisierele temei pe server (incluziv continutul directorului &quot;themes&quot;) dupa care tema noua va apare in lista de teme din modulul ch_template.php</span> <span class="adaugari_manual">In cazul in care doriti ca noua tema sa fie tema principala a site-ului, modificati fisierul config.php specificand numele noi teme in locul celei initiale. Daca aveti module aditionale instalate, nu uitati sa adaugati codul php respectiv pentru acele module in tema noua. La fel adaugati si codul css in cazul in care la celelalte teme a fost modificat fata de cel original. </span><br>
<br>
<em><strong>III.b. How do I erase a theme from the site </strong></em><a name="sterg_tema"></a><br>
<br>
To erase a theme from the site, you must first be sure that it is not the main theme of the site. (Verify the line <br>
$pagina_finala = "red.php";<br>
from config.php file). If it is not the main theme, you may erase without worries the file with the namele of the theme from the server's root and the directory wioth the same from themes. After erasing, that theme will not appear in the themes list and any user that had saved that theme will come back to the main theme of the page.<br>
<br>
<em><strong>III.c. How do I create a theme for the site. </strong></em><a name="creare_tema_site"></a><br>
<br>
In order to create a theme for the sit an to be compatible with the project you must follow the specifications from IV. You may create a template and sent it to us and we will adapt it to the project. All the sent themes will be distributed for free in the next versions of the project. The copyright rights on the theme will be of that person that sent the template and it will be mentioned in the download page. <span class="adaugari_manual">Incepand de la versiunea 1.0 a fost simplificata procedura de creare template, astfel codul care trebuie introdus in template putand fi copiat de la alta tema deja existenta. In principiu, creati o tema noua, iar acolo unde doriti sa apara meniurile si continutul doar introduceti codul php necesar. Nu uitati sa introduceti si codul pentru afisarea copyright-ului. </span><br>
<br>
<br>
<em class="adaugari_manual"><strong>IV. Ce trebuie modificat la un template pentru a fi compatibil cu codul FAR-PHP</strong></em><a name="specificatii_teme"></a><br>
<br>
Daca doriti sa va creati propria tema pentru site, puteti crea orice tema doriti cu conditia ca acel template sa respecte cateva reguli generale, si anume:<br>
a) numele temei trebuie sa fie acelasi cu numele directorului<br>
b) pozele si imaginile folosite in template trebuie sa se afle in subdirectorul &quot;themes/nume_tema/images/&quot;<br>
c) daca folositi butoane tip imagine trebuiesc salvate in subdirectorul &quot;themes/nume_tema/butons/&quot;<br>
d) codul css pentru tema creata trebuie salvat in fisierul cu numele css.php in subdirectorul &quot;themes/nume_tema/css/&quot;<br>
In mod normal la un template clasic, pagina principala se numeste index.php si in ea se integreaza toate imaginile, pozele si butoanele pentru tema respectiva. Deoarece proiectul FAR-PHP are posibilitatea de a schimba tema in functie de preferintele vizitatorului, dupa creerea template-ului, fisierul index.php se va redenumi cu numele_temei pentru a putea fi integrat in proiect.<br>
Mai jos va este aratata o schema de comparatie intre un template clasic si un template compatibil FAR-PHP:</p>
      <table width="100%"  border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><div align="center">Template clasic </div></td>
          <td><div align="center">Template FAR-PHP </div></td>
        </tr>
        <tr>
          <td valign="top">index.php<br>
&nbsp;&nbsp;+ BUTONS<br>
      - css.php<br>
&nbsp;&nbsp;+ IMAGES<br></td>
          <td valign="top">nume_tema.php<br>
      + THEMES<br>
&nbsp;&nbsp;+ NUME_TEMA <br>
&nbsp;&nbsp;&nbsp;&nbsp;- top.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;+ BUTONS<br>
&nbsp;&nbsp;&nbsp;&nbsp;+ CSS<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- css.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;+ IMAGES </td>
        </tr>
      </table>
      <p align="justify"> Pentru alte detalii studiati una din temele distribuite impreuna cu proiectul, sau una din temele distribuite ca add-on. In cazul in care doriti sa colaborati la proiect, puteti trimite template-ul vostru si noi o sa il adaptam pentru proiect. <br>
          <br>
          <em class="adaugari_manual"><strong>V. Variabile de sesiune si cookies folosite</strong></em> <a name="variabile_ses_cook"></a><br>
          <br>
  In versiunea 1.0 exista doar un singur cookies generat care contine informatiile despre limbajul setat, tema selectata, numele userului, adresa de e-mail a userului, suma MD5 a parolei, starea userului si tipul de logare efectual de user. Numele cookies-ului este salvat cu prefix si anume:<br>
  <em>prefix_far</em><br>
  Valorile din cookies sunt salvate in forma serializata (pentru a se evita crearea mai multor cookies cu o singura valoare) <br>
  In continuare este detaliat mai pe larg continutul cookies-ului:<br>
  Cookies-ul este generat doar daca user-ul executa una din urmatoarele comenzi:<br>
  - se logheaza<br>
  - schimba tema site-ului<br>
  - schimba limbajul<br>
  In functie de actiunea executata, in cookies sunt salvate doar informatiile specifice actiunii respective astfe:<br>
  in cazul logarii se genereaza urmatoarele valori in cookies:<br>
  - user_far = contine numele userului<br>
  - email_far = contine adresa de e-mail a userului<br>
  - password_far = contine suma MD5 a parolei<br>
  - hidden_far = contine starea userului - 0 daca este logat normal si 1 daca este user ascuns<br>
  - permanently_far = contine tipul de logare - 0 daca este logare normala (1 ora) si 1 daca este logare permanenta (100 zile)<br>
  in cazul schimbarii limbajului se genereaza urmatoarea valoare:<br>
  - language_far = contine limbajul setat - &quot;ro&quot; pentru romana si &quot;en&quot; pentru engleza<br>
  in cazul schimbarii temei se genereaza urmatoarea valoare:<br>
  - themes_far = contine numele temei selectate (exemplu &quot;corp.php&quot; sau &quot;red.php&quot;)<br>
  <br>
  In cazul in care exista cookies creat, variabilele de sesiune vor contine valorile din cookies, altfel vor contine valorile din fisierul config.php. Variabilele de sesiune care se creaza sunt create cu prefix (la fel si numele cookies-ului):<br>
  prefix_language_far = contine limbajul setat<br>
  prefix_themes_far = contine tema selectata<br>
  prefix_rights_far = contine drepturile userului (care sunt specificate in baza de date)<br>
  prefix_cheia_far = contine cheia unica de sesiune<br>
  prefix_user_far = contine numele userului<br>
  prefix_email_far = contine adresa de email a userului<br>
  <br>
  In cazul in care este salvat cookies si contine datele de logare, ele sunt comparate cu informatiile existente in baza de date si daca corespund atunci se creaza cheia de sesiune si se salveaza valorile in variabilele de sesiune, iar in caz contrar se incearca distrugerea cookies-ului si se folosesc valorile din fisierul config.php.<br>
  Unele module aditionale pot crea alte cookies si variabile de sesiune, care sunt specificate pentru fiecare modul in parte. <br>
  <br>
  <em class="adaugari_manual"><strong>VI. Modificari de versiune </strong></em><a name="log_ver"></a><br>
  <br>
  Proiectul FAR-PHP a fost initiat in anul 2004 si a fost conceput ca o alternativa la managementul unui site. Pentru a vedea toate modificarile de versiune existente, puteti apela fisierul de log folosind comanda:<br>
  <a href="index.php?c=ver">index.php?c=ver</a> <br>
  In cazul in care doriti sa vedeti modificarile de versiune actualizate zilnic (doar in limba romana in acest moment) puteti sa urmati link-ul de mai jos: <br>
  <a href="http://www.far-php.ro/index.php?c=ver%20" target="_blank">http://www.far-php.ro/index.php?c=ver </a><br>
  <br>
  <em class="adaugari_manual"><strong>VII. Intrebari frecvente </strong></em><a name="faq"></a><br>
  <br>
  <strong>Nu pot da disconect</strong><br>
  - Este o problema de la cookies. Inchideti browserul si stergeti cookies-urile. Este posibil ca numele de domeniu sa fie specificat incorect la instalare (exemplu pentru instalare a fost folosita adresa http://localhost/test-far/ in loc sa se specifice adresa corecta si anume http://192.168.xxx.xxx/test-far/ (unde xxx este adresa reala de ip atribuita pentru acel nume de domeniu)<br>
  <br>
  <strong>Cum adaug un articol nou? </strong><br>
  Cititi capitolul <a href="#modul_continut">Modulul de continut</a><br>
  <br>
  <strong>Nu se instaleaza sau da erori la instalare.</strong><br>
  Cititi capitolul <a href="#creare_config_manual">Crearea manuala a fisierului de configurare config.php</a><br>
  <br>
  <em><strong>VIII. Crearea manuala a fisierului de configurare config.php</strong></em><a name="creare_config_manual"></a><br>
  <br>
  Pentru a crea manual fisierul de configurare trebuie sa folositi modulul install.php deoarece doar el genereaza tabelele in baza de date. In cazul in care doriti sa modificati fisierul de configurare aveti mai jos variabilele existente in acest fisier:</p>
      <p align="left">// Setari pentru Baza de date MySQL<br>
  $server_bd = &quot;localhost&quot;; // numele serverului sql<br>
  $user_bd = &quot;root&quot;; // numele de conectare la bd sql<br>
  $parola_bd = &quot;parola&quot;; // parola pentru conectarea la sql<br>
  $nume_bd = &quot;far_sql&quot;; // numele bazei de date sql<br>
  $prefix_tabel_bd = &quot;prefix_&quot;; // prefixul la numele tabelelor din bd</p>
      <p align="left">// Setari pentru server<br>
  $prefix_sesiuni = &quot;prefix&quot;; // prefixul pentru numele sesiunilor si cookies<br>
  $diferenta_de_ora = &quot;0&quot;; // diferenta de ora de pe server fata de ora reala<br>
  $diferenta_de_ora_2 = &quot;+&quot;; // diferenta in + sau in -<br>
  $adresa_url = &quot;http://www.adresata.com/&quot;; // adresa unde se gaseste pagina web (nu uitati sa puneti / la sfarsit)<br>
  $pagina_finala = &quot;blue.php&quot;; // tema default pentru site<br>
  $pagina_deconectare = &quot;index.php&quot;; // pagina la care se va face redirectarea dupa logout (disconect) <br>
  $mesaje = &quot;codes/language_ro.php&quot;; // fisierul de mesaje de eroare</p>
      <p align="left">// Setari de administrare<br>
  $functii = &quot;codes/functions.php&quot;; // adresa unde se afla fisierul cu functii<br>
  $ip_stop = array(&quot;0.0.0.0&quot;, &quot;255.255.255.255&quot;, &quot;0.0.0.1&quot;); // adresele de ip pe care doriti sa le blocati - ip block<br>
  $parola_criptata = &quot;da&quot;; // puneti &quot;da&quot; daca parola este criptata in sql cu md5, sau &quot;nu&quot; daca este salvata ca text<br>
  $nr_incercari = &quot;5&quot;; // nr de incercari in caz de logare nereusita - 0 pentru infinit<br>
  $email_admin = &quot;adresata@domeniu.com&quot;; // adresa de e-mail a administratorului site-ului<br>
  $email_moderator = &quot;&quot;; // adresa pe care se primesc mesajele pentru moderatorul site-ului<br>
  $limbaj_primar = &quot;ro&quot;; // limba default pentru site<br>
  $chestii_copyright = '&lt;br&gt;&lt;strong&gt;Copyright eu&lt;/strong&gt;&lt;br&gt;'; // partea de jos a pagini pentru chestii de copyright<br>
      </p>
      <p align="left"> </p>
    </div></td>
  </tr>
</table>
