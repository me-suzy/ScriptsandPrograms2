//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

Table of Contents

I. General
	a. About
	b. Changelog
	c. Legal
II. Installation
	a. Initial Configuration
	b. Uploading
	c. Running the Install script
	d. Entering Data
III. Upgrading
	a. From v1.8
	b. From v1.7
	c. From older versions
IV. Using the Script
	a. Adding, Editing, and Deleting DJs
	b. Editing your Settings
		1. Automatic Mode
		2. Manual Mode
	c. Uninstalling
V. Troubleshooting
	a. Error messages

//////////////////////////////////////////////////////////////////////////////
// I. General ////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

a. About

DJ Status is an attempt at managing the DJs for your shoutcast radio station.
It allows to show information on the currently active DJ that could be useful
to your visitors. Information such as IM contacts and a link to their song
request page can prove invaluable. DJ Status is incredibly easy to use from
all angles. It features an easy-to-use administration panel for editing
settings and managing DJs.

DJ Status is actively developed by coding newcomer, Nathan Bolender. Feel free
to contact Nathan for any suggestions, errors, problems, help, or comments.

Website: www.nathanbolender.com
E-mail: Nathan@nathanbolender.com

Current Features:
-> Grabs stats from your shoutcast server such as Current title, current song,
current # of listeners.
-> If a DJ isn't connected, a message is displayed stating so.
-> If the server isn't up, it displays a message stating so.
-> Checks the server title for the DJ's name, and assigns that DJ's ID.
-> Pulls DJ name, IM info, and link to song request page (Using the
SongRequester plugin) from your MySQL database, and displays all that applies.
-> Easy config file.
-> Up to 3 aliases per DJ to check for in the server title.
-> Also checks database AIM & ICQ entries against the server's current AIM &
ICQ settings to detect the DJ.
-> Administration panel for adding, editing, and deleting DJs
-> Simple installation script
-> Manual Mode--Optional mode to disable automatic DJ detection and have DJs
log in and out.


b. Changelog


8/11/05 - v1.8.2
-------------------------
-> Fixed sparratic login failures! 
-> New readme troubleshooting section

3/22/05 - v1.8.1
-------------------------
-> New legal documentation

7/13/04 - v1.8
-------------------------
-> Can now check AIM & ICQ settings on server against each DJ's AIM & ICQ
entried in the database.
-> New setting that will display what method successfully detected the DJ.

7/9/04 - v1.7.3
-------------------------
-> Minor bugfix

7/8/04 - v1.7
-------------------------
-> Manual Mode--Optional mode to disable automatic DJ detection and have DJs
log in and out.

7/6/04 - v1.5.2
-------------------------
-> Several Bugfixes

7/6/04 - v1.5
-------------------------
-> Administration panel for adding, editing, and deleting DJs
-> Simple installation script

7/5/04 - v1.2
-------------------------
-> Easy config file.
-> Up to 3 aliases per DJ to check for in the server title.

7/3/04 - v1.1
-------------------------
-> Grabs stats from your shoutcast server such as Current title, current song,
current # of listeners.
-> If a DJ isn't connected, a message is displayed stating so.
-> If the server isn't up, it displays a message stating so.
-> Checks the server title for the DJ's name, and assigns that DJ's ID.
-> Pulls DJ name, IM info, and link to song request page (Using the
SongRequester plugin) from your MySQL database, and displays all that applies.

6/28/04 - v1.0
-------------------------
-> Manual only


c. Legal

This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License located at http://creativecommons.org/licenses/by-nc-nd/2.0/ . A full copy of the license is printed below.

THE WORK (AS DEFINED BELOW) IS PROVIDED UNDER THE TERMS OF THIS CREATIVE COMMONS PUBLIC LICENSE ("CCPL" OR "LICENSE"). THE WORK IS PROTECTED BY COPYRIGHT AND/OR OTHER APPLICABLE LAW. ANY USE OF THE WORK OTHER THAN AS AUTHORIZED UNDER THIS LICENSE OR COPYRIGHT LAW IS PROHIBITED. 
BY EXERCISING ANY RIGHTS TO THE WORK PROVIDED HERE, YOU ACCEPT AND AGREE TO BE BOUND BY THE TERMS OF THIS LICENSE. THE LICENSOR GRANTS YOU THE RIGHTS CONTAINED HERE IN CONSIDERATION OF YOUR ACCEPTANCE OF SUCH TERMS AND CONDITIONS. 

1. Definitions 
	a.	"Collective Work" means a work, such as a periodical issue, anthology or encyclopedia, in which the Work in its entirety in unmodified form, along with a number of other contributions, constituting separate and independent works in themselves, are assembled into a collective whole. A work that constitutes a Collective Work will not be considered a Derivative Work (as defined below) for the purposes of this License. 
	b.	"Derivative Work" means a work based upon the Work or upon the Work and other pre-existing works, such as a translation, musical arrangement, dramatization, fictionalization, motion picture version, sound recording, art reproduction, abridgment, condensation, or any other form in which the Work may be recast, transformed, or adapted, except that a work that constitutes a Collective Work will not be considered a Derivative Work for the purpose of this License. For the avoidance of doubt, where the Work is a musical composition or sound recording, the synchronization of the Work in timed-relation with a moving image ("synching") will be considered a Derivative Work for the purpose of this License. 
	c.	"Licensor" means the individual or entity that offers the Work under the terms of this License. 
	d.	"Original Author" means the individual or entity who created the Work. 
	e.	"Work" means the copyrightable work of authorship offered under the terms of this License. 
	f.	"You" means an individual or entity exercising rights under this License who has not previously violated the terms of this License with respect to the Work, or who has received express permission from the Licensor to exercise rights under this License despite a previous violation. 
	
2. Fair Use Rights. Nothing in this license is intended to reduce, limit, or restrict any rights arising from fair use, first sale or other limitations on the exclusive rights of the copyright owner under copyright law or other applicable laws. 

3. License Grant. Subject to the terms and conditions of this License, Licensor hereby grants You a worldwide, royalty-free, non-exclusive, perpetual (for the duration of the applicable copyright) license to exercise the rights in the Work as stated below: 
	a.	to reproduce the Work, to incorporate the Work into one or more Collective Works, and to reproduce the Work as incorporated in the Collective Works; 
	b.	to distribute copies or phonorecords of, display publicly, perform publicly, and perform publicly by means of a digital audio transmission the Work including as incorporated in Collective Works; 
The above rights may be exercised in all media and formats whether now known or hereafter devised. The above rights include the right to make such modifications as are technically necessary to exercise the rights in other media and formats, but otherwise you have no rights to make Derivative Works. All rights not expressly granted by Licensor are hereby reserved, including but not limited to the rights set forth in Sections 4(d) and 4(e).

4. Restrictions.The license granted in Section 3 above is expressly made subject to and limited by the following restrictions: 
	a.	You may distribute, publicly display, publicly perform, or publicly digitally perform the Work only under the terms of this License, and You must include a copy of, or the Uniform Resource Identifier for, this License with every copy or phonorecord of the Work You distribute, publicly display, publicly perform, or publicly digitally perform. You may not offer or impose any terms on the Work that alter or restrict the terms of this License or the recipients' exercise of the rights granted hereunder. You may not sublicense the Work. You must keep intact all notices that refer to this License and to the disclaimer of warranties. You may not distribute, publicly display, publicly perform, or publicly digitally perform the Work with any technological measures that control access or use of the Work in a manner inconsistent with the terms of this License Agreement. The above applies to the Work as incorporated in a Collective Work, but this does not require the Collective Work apart from the Work itself to be made subject to the terms of this License. If You create a Collective Work, upon notice from any Licensor You must, to the extent practicable, remove from the Collective Work any reference to such Licensor or the Original Author, as requested.
	b.	You may not exercise any of the rights granted to You in Section 3 above in any manner that is primarily intended for or directed toward commercial advantage or private monetary compensation. The exchange of the Work for other copyrighted works by means of digital file-sharing or otherwise shall not be considered to be intended for or directed toward commercial advantage or private monetary compensation, provided there is no payment of any monetary compensation in connection with the exchange of copyrighted works. 
	c.	If you distribute, publicly display, publicly perform, or publicly digitally perform the Work, You must keep intact all copyright notices for the Work and give the Original Author credit reasonable to the medium or means You are utilizing by conveying the name (or pseudonym if applicable) of the Original Author if supplied; the title of the Work if supplied; and to the extent reasonably practicable, the Uniform Resource Identifier, if any, that Licensor specifies to be associated with the Work, unless such URI does not refer to the copyright notice or licensing information for the Work. Such credit may be implemented in any reasonable manner; provided, however, that in the case of a Collective Work, at a minimum such credit will appear where any other comparable authorship credit appears and in a manner at least as prominent as such other comparable authorship credit. 
	d.	For the avoidance of doubt, where the Work is a musical composition:
		i.	Performance Royalties Under Blanket Licenses. Licensor reserves the exclusive right to collect, whether individually or via a performance rights society (e.g. ASCAP, BMI, SESAC), royalties for the public performance or public digital performance (e.g. webcast) of the Work if that performance is primarily intended for or directed toward commercial advantage or private monetary compensation.
		ii.	Mechanical Rights and Statutory Royalties. Licensor reserves the exclusive right to collect, whether individually or via a music rights agency or designated agent (e.g. Harry Fox Agency), royalties for any phonorecord You create from the Work ("cover version") and distribute, subject to the compulsory license created by 17 USC Section 115 of the US Copyright Act (or the equivalent in other jurisdictions), if Your distribution of such cover version is primarily intended for or directed toward commercial advantage or private monetary compensation.
	e.	Webcasting Rights and Statutory Royalties. For the avoidance of doubt, where the Work is a sound recording, Licensor reserves the exclusive right to collect, whether individually or via a performance-rights society (e.g. SoundExchange), royalties for the public digital performance (e.g. webcast) of the Work, subject to the compulsory license created by 17 USC Section 114 of the US Copyright Act (or the equivalent in other jurisdictions), if Your public digital performance is primarily intended for or directed toward commercial advantage or private monetary compensation.

5. Representations, Warranties and Disclaimer
UNLESS OTHERWISE MUTUALLY AGREED BY THE PARTIES IN WRITING, LICENSOR OFFERS THE WORK AS-IS AND MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND CONCERNING THE WORK, EXPRESS, IMPLIED, STATUTORY OR OTHERWISE, INCLUDING, WITHOUT LIMITATION, WARRANTIES OF TITLE, MERCHANTIBILITY, FITNESS FOR A PARTICULAR PURPOSE, NONINFRINGEMENT, OR THE ABSENCE OF LATENT OR OTHER DEFECTS, ACCURACY, OR THE PRESENCE OF ABSENCE OF ERRORS, WHETHER OR NOT DISCOVERABLE. SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES, SO SUCH EXCLUSION MAY NOT APPLY TO YOU.

6. Limitation on Liability. EXCEPT TO THE EXTENT REQUIRED BY APPLICABLE LAW, IN NO EVENT WILL LICENSOR BE LIABLE TO YOU ON ANY LEGAL THEORY FOR ANY SPECIAL, INCIDENTAL, CONSEQUENTIAL, PUNITIVE OR EXEMPLARY DAMAGES ARISING OUT OF THIS LICENSE OR THE USE OF THE WORK, EVEN IF LICENSOR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. 

7. Termination 
	a.	This License and the rights granted hereunder will terminate automatically upon any breach by You of the terms of this License. Individuals or entities who have received Collective Works from You under this License, however, will not have their licenses terminated provided such individuals or entities remain in full compliance with those licenses. Sections 1, 2, 5, 6, 7, and 8 will survive any termination of this License. 
	b.	Subject to the above terms and conditions, the license granted here is perpetual (for the duration of the applicable copyright in the Work). Notwithstanding the above, Licensor reserves the right to release the Work under different license terms or to stop distributing the Work at any time; provided, however that any such election will not serve to withdraw this License (or any other license that has been, or is required to be, granted under the terms of this License), and this License will continue in full force and effect unless terminated as stated above. 

8. Miscellaneous 
	a.	Each time You distribute or publicly digitally perform the Work or a Collective Work, the Licensor offers to the recipient a license to the Work on the same terms and conditions as the license granted to You under this License. 
	b.	If any provision of this License is invalid or unenforceable under applicable law, it shall not affect the validity or enforceability of the remainder of the terms of this License, and without further action by the parties to this agreement, such provision shall be reformed to the minimum extent necessary to make such provision valid and enforceable. 
	c.	No term or provision of this License shall be deemed waived and no breach consented to unless such waiver or consent shall be in writing and signed by the party to be charged with such waiver or consent. 
	d.	This License constitutes the entire agreement between the parties with respect to the Work licensed here. There are no understandings, agreements or representations with respect to the Work not specified here. Licensor shall not be bound by any additional provisions that may appear in any communication from You. This License may not be modified without the mutual written agreement of the Licensor and You. 



//////////////////////////////////////////////////////////////////////////////
// II. Installation //////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

a. Initial Configuration

Open config.php in a plain text editor such as notepad or Kwrite. Edit each
line in the configuration section according to your settings. Be sure to type
between the quotations. Do not edit past the configuration section. Each
setting is described to the right.

Note: make sure that the database info is correct. You will need to make a new
database in MySQL. Ask your webhost for more details about making databases.


b. Uploading

Upload all files to a folder on your server via FTP, or any method you prefer
to use. Be sure to maintain the same directory structure, or the script will
not work correctly.


c. Running the Install script

In your web browser, go to http://www.yourhost.com/yourfolder/admin/install.
If the install is successful, you will see a message saying it was successful.
If it was not successful, you will see an error message. Please check all of
your database settings if it does not work.

After installing, be sure to delete the "install" folder on your server before
continuing to the administration panel.


d. Entering Data

Log in to the administration panel using the password you defined in
config.php.

Click "Add" under DJ management to add your first DJ. Be sure to fill in all
of the fields marked with a asterisk "*", and any other fields you wish to fill
in. If you make a mistake, you can always use the "Edit" and "Delete" functions
at your disposal.



//////////////////////////////////////////////////////////////////////////////
// III. Upgrading ////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

a. From v1.8.1	You do not have to do anything to upgrade from v1.8.

b. From v1.7 or 1.7.3

Replace the files, making sure that you fill in the values in the new
config.php. Be sure to upload the admin/install folder. Then run
admin/install/upgrade_from_1.7.x.php. It should add the values to your
database that are needed. Delete the install folder when you are done.

c. From older versions

Completely reinstall the script, after clearing out your database.



//////////////////////////////////////////////////////////////////////////////
// IV. Using the Script //////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


a. Adding, Editing, and Deleting DJs

In the administration panel, click "Add" to add a DJ, "Edit" to edit a DJ, and
"Delete" to delete a DJ. Everything in these sections is rather
straightforward.


b. Editing your Settings

Click "Edit" under "Settings" to edit your settings.


1. Automatic Mode

Automatic mode is the default settings. This searches the shoutcast server
title for each alias defined for each DJ. If one is found, that DJ is set to
active, and their stats are displayed.


2. Manual Mode

If you choose to use manual mode, DJ Status will not look at the server title,
but will check for the "logged in" DJ. DJs can log in and out by going to
update.php and using their own password. You may also use your administrator
password in all cases to override the current DJ.

This is a perfect solution if your DJs do not put their name in the server
title, or you want a more concrete way of showing DJ stats. Please be aware,
that stats do not display if a DJ is not logged on--even if the radio is
active.


3. Display how your DJ was detected

If set to Yes, you will be able to see what was the determining factor in
detecting your DJ. This is displayed on index.php. Please be aware that it may
show one thing, when it could of also been detected by something else. 

This is the order the checks are done in: Alias 1, Alias 2, Alias 3, AIM, ICQ.
The last one found will be the one displayed.


c. Uninstalling

If you wish to uninstall this script, just delete the files from your server
and delete the database used. Simple as that.


//////////////////////////////////////////////////////////////////////////////
// V. Troubleshooting ////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

a. Error messages

-	Incorrect Password
	- You entered the wrong password.
	
-	The radio is currently down. Please check again later.
	- The script cannont connect to your shoutcast server. Check your configuration, and make sure that your webhost allows outgoing connections with fsockopen().

-	A DJ is not currently connected to the radio. Please check again later.
	- The script connected to your server, but a DJ is not currently streaming audio.

-	A DJ is not currently signed on to the system. Please check again later.
	- The script was not able to detect a proper DJ (automatic mode) or a DJ is not logged in to the system (manual mode).


//////////////////////////////////////////////////////////////////////////////
///////////////////////////// End of Readme //////////////////////////////////
//////////////////////////////////////////////////////////////////////////////