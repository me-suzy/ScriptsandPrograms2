                            DOWNLOAD.CGI  V2.0
                                
                            By Metertek   1999
                            Metertek@Yahoo.com

            By inserting the code below into a webpage you will
          create a download button for the file to be downloaded.

 <FORM METHOD="POST" ACTION="http://your.server.com/cgi-bin/download.cgi">
 <INPUT TYPE="hidden" NAME="url" VALUE="http://your.server.com/yourdir/downloads/downfile.zip">
 <INPUT TYPE="SUBMIT" NAME="download" VALUE="DOWNLOAD FILE">
 </FORM>

  To view the download stats page call the scripts url from your browser.
     (eg) type in : http://your.server.com/cgi-bin/download.cgi 


i) Author/User Agreement.

	The user by installing and therefore using this Perl script
recognises that this script remains the property of the Metertek Perl
Script Archive, and therefore should not be altered and/or redistributed
in any way without the prior consent of the Author and the Metertek
Perl Script Archive. In return the Author provides this script for your 
personal use free of charge and without obligation.



			        Instructions



1. OVERVIEW

	Download.cgi will count all file downloads from your site and display
 the data to you in a HTML page. It will also help you make your downloads 
page more attractive as the script is called by the use of a Form Button 
instead of a boring text link. The script is easily installed and 
does not require SSI.

	The script works by placing the script tag into a html page, the tag
 in this case being a FORM which submits the url of the file to be downloaded
 to the script. The script opens the download counter file and updates it, 
then the download file procedure is returned.

	The download counter file is created by the script upon first use 
making the script easily installed and better still the script has only 
1 variable (which directory to put the counter file).

	The download stats page can be called by simply entering into your 
browser the url of the script.


2. INSTALLATION

	Change the script variable to suit your requirements and copy the
script to your cgi-bin with the filename download.cgi. 

	Nothing else to do - DOWNLOAD.CGI will create the necessary counter 
file when initialized upon first use.


3. DOWNLOAD.CGI VARIABLE

$countdir = $ENV{'DOCUMENT_ROOT'}.'/logs'    : this will tell the script to
					       create the counter file in a
					       directory named logs.
					       Change this to suit your
					       directory (ie) If your URL is
					       http://your.server.com/you/
					       and you want the counter file
					       in a sub directory named
					       downloads, (ie)
					http://your.server.com/you/downloads
					then change the variable to this :
                        $countdir = $ENV{'DOCUMENT_ROOT'}.'/you/downloads';


4. FEEDBACK

	Feel free to send any comments or suggestions as feedback is
             encouraged, Email Metertek at metertek@yahoo.com


5. FOOTNOTE

	All Metertek scripts are provided free for use but remain the 
property of the Metertek Script Archive and the Author. Any Distribution of
files downloaded from the Metertek Perl Script Archive either in part or 
in any way edited from the original format shall be in violation of the 
Author/User agreement.