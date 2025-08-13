/*	$Id: ValidateInternals.cpp,v 1.4.330.1 1999/08/05 18:58:49 nsacco Exp $	*/

// ValidateInternals.cpp. Run some tests to validate internal structures and report on them.
//

#include "ebihdr.h"
#include <sys/types.h>
#include <sys/stat.h>
#include "smsmtp.h"
#include <fstream.h>

static char VersionStamp[512] = "eBayISAPI Version Watermark";

void clseBayApp::ValidateInternals()
{
	// First validate the server names. None of them may be NULL.
	extern const char **gServerNames;
	extern char *sMailmachines[];
	extern char *sMailmachines_Reg[];
	extern bool authorizationEnabled;
	extern char *CurrentDatabase;
	extern const char *eBayHTMLPath;
	extern const char *eBayImagePath;
	extern const char *eBaySSLImagePath;
	extern const char *eBaySecureHTMLPath;
	extern const char *eBayCGIPath;
	extern const char *eBaySSLCGIPath;
	extern const char *eBaySSLHTMLPath;
// kakiyama 07/20/99
	extern const char *eBaySearchPath;
	extern const char *eBayGalleryListingPath;

	extern const char *eBayAdminPath;
	extern const char *eBayListingPath;
	extern const char *eBayMembersPath;
	extern const char *eBayHomeURL;
	extern const char *serverMeanings[];
	extern bool dumping_for_debugging;
	int i;

	SetUp();
	EmitHeader("Validate Internals");
	
	*mpStream << "<hr><center><h1>eBayISAPI internals validation</h1></center><p>\n";

	*mpStream << __FILE__
		" built on "
		__DATE__
		" "
		__TIME__
		";<br>";

	HMODULE dllHandle = GetModuleHandle("eBayISAPI.dll");
	char dllPath[MAX_PATH];
	int filepathlen = GetModuleFileName(dllHandle, dllPath, sizeof dllPath);

	// Get the statistics for this file.
	struct _stat statbuf;
	_stat(dllPath, &statbuf);

	*mpStream << dllPath
		<< " last modified "
		<< ctime(&statbuf.st_mtime)
		<< ";<p><p>";
	
#if 0
	*mpStream << "Version stamp says <font color=green><bold>" 
		<< (VersionStamp + 1)
		<< "</bold></font><p>\n";
#endif
	*mpStream << "Dumping for debugging is <font color=";
	if (dumping_for_debugging)
		*mpStream << "red><bold>Enabled";
	else
		*mpStream << "green><bold>Disabled";
	*mpStream << "</bold></font> (eBayISAPI\\eBayISAPI.cpp)<p>";

	*mpStream << "Authorization is <font color=";
	if (authorizationEnabled)
		*mpStream << "green><bold>Enabled";
	else
		*mpStream << "red><bold>Disabled";
	*mpStream << "</bold></font> (eBayISAPI\\clseBayApp.cpp)<p>";

	*mpStream << "Current \"machine name\" is <b>"
		<< machineName
		<< "</b> (kernel\\src\\clsMarketPlaces.cpp) <p>";

	*mpStream << "Current database is <b>"
		<< CurrentDatabase
		<< "</b> (kernel\\src\\clsDatabaseOracle.cpp)<p>";

	 
	
	*mpStream << "Server names (kernel\\src\\smsmtp.cpp); first one should read \"Error Here\":<br>";

	*mpStream << "<table border=1>";

	for (i = 0; i < LAST_Machine; ++i)
	{
		*mpStream << "<tr>";
		*mpStream << "<td>" << serverMeanings[i] << "</td>";		
		
		*mpStream << "<td>";
		if (gServerNames[i] == NULL)
		{
			*mpStream << "<font color=red>WARNING: Bad server name "
				<< "</font>";
		}
		else
			*mpStream << gServerNames[i];

		*mpStream << "</td></tr>\n";
	}


	*mpStream << "<tr><td>CGI path</td><td>" << eBayCGIPath << "</td></tr>\n";
	*mpStream << "<tr><td>HTML path</td><td>" << eBayHTMLPath << "</td></tr>\n";
	*mpStream << "<tr><td>Image path</td><td>" << eBayImagePath << "</td></tr>\n";
	*mpStream << "<tr><td>SSL image path</td><td>" << eBaySSLImagePath << "</td></tr>\n";
	*mpStream << "<tr><td>Secure HTML path</td><td>" << eBaySecureHTMLPath << "</td></tr>\n";
	*mpStream << "<tr><td>SSL CGI path</td><td>" << eBaySSLCGIPath << "</td></tr>\n";
	*mpStream << "<tr><td>SSL HTML path</td><td>" << eBaySSLHTMLPath << "</td></tr>\n";
	*mpStream << "<tr><td>Admin path</td><td>" << eBayAdminPath << "</td></tr>\n";	
	*mpStream << "<tr><td>Listing path</td><td>" << eBayListingPath << "</td></tr>\n";
	*mpStream << "<tr><td>Members path</td><td>" << eBayMembersPath << "</td></tr>\n";
	*mpStream << "<tr><td>Home URL</td><td>" << eBayHomeURL << "</td></tr>\n";
// kakiyama 07/21/99
	*mpStream << "<tr><td>Search URL</td><td>" << eBaySearchPath << "</td></tr>\n";
	*mpStream << "<tr><td>Gallery Listing URL</td><td>" << eBayGalleryListingPath << "</td></tr>\n";
		
		
	*mpStream << "</table>";
	
	// Now check the mail names...
	*mpStream << "<pre>Mail server names:\n";
	for (i = 0; i < smtp::nummachines; i++)
		*mpStream << sMailmachines[i] << "\n";

	*mpStream << "\nRegistration mail server names:\n";
	for (i = 0; i < smtp::nummachines_Reg; i++)
		*mpStream << sMailmachines_Reg[i] << "\n";
	*mpStream << "\n</pre>";

	
	// Next, validate that the page table is in order.
	*mpStream << "<p><bold>CoBrandArray validation (clsPartners.cpp):</bold>\n";
	extern CoPageRec CoBrandArray[];
	bool foundbad = false;
	int pos;
	for (pos = 0, i = PageUnknown; i <= PageLastPossiblePage; ++i, ++pos)
	{
		if (CoBrandArray[pos].ePageEnum != i)
		{
			*mpStream << "<b><font color=red>WARNING: Bad entry in CoBrandArray at position "
				<< i
				<< "</font></b><br>";
			foundbad = true;
		}
	}
	if (foundbad)
		*mpStream << "<b><font color=red>ERROR: Bad CoBrandArray entries found</font></b>";
	else
		*mpStream << "<b><font color=green>CoBrandArray table appears correct.</font></b>";


	*mpStream << mpMarketPlace->GetFooter()
		<< flush;

	CleanUp();

}



