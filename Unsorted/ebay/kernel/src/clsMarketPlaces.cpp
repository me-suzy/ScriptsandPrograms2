/*	$Id: clsMarketPlaces.cpp,v 1.20.2.17.4.3 1999/08/09 18:45:06 nsacco Exp $	*/
//
//	File:		clsMarketPlaces.cpp
//
// Class:	clsMarketPlaces
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all marketplaces
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 21/10/97 tini  
//                replaced: categories notice with post-auction notices
//				- 06/18/98 inna		- added eBayCCardEmail
//				- 08/22/98 mila		- added defines for PICSPATH
//				- 09/04/98 mila		- replaced secure.ebay.com with 
//									  arribada.ebay.com
//
//				- 01/29/99 josh     - rewrite of definitions

#include "eBayKernel.h"
//
// This enum defines the "marketplaces" -- actually, build
// and test environments -- used to generate parts of paths
// for URLS by routines such as GetHTMLPath and GetCGIPath.
//
enum {
	prod = 0,
	admin,
	localhost,
	developer,
	testy,
	lizard,
	asp,
	cobrand,
	intl
};

// * * * * * * * * * * * * * * * * 
// * * * DEFINE MACHINE HERE * * * 
// * * * * * * * * * * * * * * * * 

//#define EBAY_MACHINE testy
//#define EBAY_MACHINE localhost
//#define EBAY_MACHINE prod
#define EBAY_MACHINE developer
//#define EBAY_MACHINE lizard
//#define EBAY_MACHINE admin
//#define EBAY_MACHINE intl
//#define EBAY_MACHINE cobrand

// Change the next line to change what developer machine is used.
// In normal development and testing, nothing below the next
// line should need to be changed.

#define DEVELOPER_SERVER "nsacco.corp.ebay.com"

// Each one of these arrays contains settings for various
// globals used by the various "marketplaces", which at the
// moment are really our build environments.

// Also here are the server definitions used for pooling 
// (CGI1, CGI2, and so on.)

// These arrays are used by GetHTMLPath(), GetCGIPath(), and so
// on to determine the destination for dynamically generated links.

// The arrays are in the order defined in the anonymous enum at the top
// of this file. file. In general, you shouldn't have to change these tables --
// the most common change involves testing on a particular server,
// in which case you can just set the #define of DEVELOPER_SERVER
// to be the server you want to set as your test bed. 

// If you need to ADD a "marketplace" dependent variable, follow the
// template here -- create an array of static const char *, with an
// entry for every "marketplace" in use, and then define a static const
// char that refers to it. For example: we want to have a path from which
// to run surveys; we're going to want a routine called GetSurveyPath(). Set up
//
// static const char *eBaySurveyPaths[]
//
// with one entry for each value in the enum; then set up 
//
// static const char *eBaySurveyPath = eBaySurveyPaths[EBAY_MACHINE]
//
// which you'll probably end up passing to the clsMarketPlace constructor
// in clsMarketPlaces::GetCurrentMarketPlace() (which is the last thing in
// this file.) 
//
// Adding a new pool is similar. If you want to add the pool called CGI25,
// copy the model shown by CGI0s, CGI1s below, and then add a new entry to
// the array sServerNames. Also add a new entry to serverMeanings so that
// ValidateInternals will know what it is for.

// Adding a new "marketplace" is the most work. Usually you won't need
// to do this -- developers can get by with at least one of localhost or
// developer. However, if a new one is needed, you'll need to first add
// an entry to the enum above to "label" your new build; add a new line to
// every array containing the paths you want in your new marketplace; and
// add a new entry to machineNames for the benefit of ValidateInternals.


static const char *eBayCGIPaths[] = 
{
	"http://cgi.ebay.com/aw-cgi/",				// production
	"http://skippy.ebay.com/aw-cgi/",			// admin
	"http://localhost/aw-cgi/",					// localhost
	"http://" DEVELOPER_SERVER "/aw-cgi/",		// developer
	"http://testy.corp.ebay.com/aw-cgi/",		// testy
	"http://members.ebay.com/aw-cgi/",			// lizard
	"http://asp.ebay.com/aw-cgi/",				// asp
	"http://cgi-qa.corp.ebay.com/aw-cgi/",		// cobrand
	"http://icgi.ebay.com/aw-cgi/"				// intl
};

static const char *eBaySSLCGIPaths[] = {
	"https://scgi.ebay.com/saw-cgi/",		// production
	"https://scgi.ebay.com/saw-cgi/",		// admin
	"https://localhost/saw-cgi/",				// localhost
	"https://" DEVELOPER_SERVER "/saw-cgi/",	// developer
	"https://testy.corp.ebay.com/saw-cgi/",		// testy
	"https://scgi.ebay.com/saw-cgi/",		// lizard
	"https://scgi.ebay.com/saw-cgi/",		// asp
	"https://scgi.ebay.com/saw-cgi/",		// cobrand
	"https://scgi.ebay.com/saw-cgi/"		// intl
};												

static const char *eBaySSLHTMLPaths[] =
{
	"https://scgi.ebay.com/secure/",			// production
	"https://scgi.ebay.com/secure/",			// admin
	"https://localhost/saw/",					// localhost
	"https://" DEVELOPER_SERVER "/saw/",		// developer
	"https://testy.corp.ebay.com/secure/",			// testy
	"https://scgi.ebay.com/saw/",			// lizard
	"https://scgi.ebay.com/saw/",			// asp
	"https://scgi.ebay.com/secure/",			// cobrand
	"https://scgi.ebay.com/saw/"			// intl
};

static const char *eBaySSLImagePaths[] =
{
	"https://scgi.ebay.com/saw/pics/",		// production
	"https://scgi.ebay.com/saw/pics/",		// admin,
	"https://localhost/saw/pics/",				// localhost
	"https://" DEVELOPER_SERVER "/saw/pics/",	// developer
	"https://testy.corp.ebay.com/saw/pics/",	// testy
	"https://scgi.ebay.com/saw/pics/",		// lizard
	"https://scgi.ebay.com/saw/pics/",		// asp
	"https://scgi.ebay.com/saw/pics/",		// cobrand
	"https://scgi.ebay.com/saw/pics/"		// intl
};

static const char *eBayHTMLPaths[] = 
{
	"http://pages.ebay.com/",				// production
	"http://pages.ebay.com/",				// admin
	"http://localhost/",						// localhost
	"http://" DEVELOPER_SERVER "/"	,		// developer
	"http://testy.corp.ebay.com/",			// testy
	"http://members.ebay.com/",				// lizard
	"http://asp.ebay.com/",					// asp
	"http://pages-qa.corp.ebay.com/",	// cobrand -- IS THIS RIGHT, MILA?
	"http://pages.ebay.com/uk/"				// intl
};

// TODO - check
// kakiyama 07/20/99
// please someone double-check the following paths
// since I'm not sure if they are correct
static const char *eBaySearchPaths[] = 
{
	"http://search.ebay.com/cgi-bin/",				// production
	"http://search.ebay.com/cgi-bin/",				// admin
	"http://search.ebay.com/cgi-bin/",				// localhost
	"http://search.ebay.com/cgi-bin/",				// developer
	"http://search.ebay.com/cgi-bin/",				// testy
	"http://search.ebay.com/cgi-bin/",				// lizard
	"http://search.ebay.com/cgi-bin/",				// asp
	"http://search.ebay.com/cgi-bin/",				// cobrand
	"http://search.ebay.com/cgi-bin/",				// international
};


static const char *eBayGalleryListingPaths[] = 
{
	"http://listings.ebay.com/aw/glistings",		// production
	"http://listings.ebay.com/aw/glistings",		// admin
	"http://listings.ebay.com/aw/glistings",		// localhost
	"http://listings.ebay.com/aw/glistings",		// developer
	"http://godzilla.corp.ebay.com/aw/glistings",	// testy
	"http://listings.ebay.com/aw/glistings",		// lizard
	"http://listings.ebay.com/aw/glistings",		// asp
	"http://listings-qa.corp.ebay.com/aw/glistings",// cobrand
	"http://listings.uk.ebay.com/aw/glistings",		// intl
};


static const char *eBayImagePaths[] = 
{
	"http://pics.ebay.com/aw/pics/",			// production
	"http://pics.ebay.com/aw/pics/",			// admin
	"http://pics.ebay.com/aw/pics/",			// localhost
	"http://pics.ebay.com/aw/pics/",			// developer
	"http://pics.ebay.com/aw/pics/",		// testy
	"http://members.ebay.com/aw/pics/",			// lizard
	"http://asp.ebay.com/aw/pics/",				// asp
	"http://pics.ebay.com/aw/pics/",			// cobrand
	"http://pics.ebay.com/aw/pics/",			// intl
};

static const char *eBaySecureHTMLPaths[] =
{
	"https://arribada.ebay.com/aw-secure/",		// production
	"https://arribada.ebay.com/aw-secure/",		// admin
	"https://arribada.ebay.com/aw-secure/",		// localhost
	"https://secure-test.ebay.com/aw-secure/",	// developer
	"https://secure-test.ebay.com/aw-secure/",	// testy
	"https://arribada.ebay.com/aw-secure/",		// lizard
	"https://arribada.ebay.com/aw-secure/",		// asp
	"https://arribada.ebay.com/aw-secure/",		// cobrand
	"https://arribada.ebay.com/aw-secure/",		// intl
};

static const char *eBayListingPaths[] = 
{
	"http://listings.ebay.com/aw/listings",		// production
	"http://listings.ebay.com/aw/listings",		// admin
	"http://listings.ebay.com/aw/listings",		// localhost
	"http://listings.ebay.com/aw/listings",		// developer
	"http://godzilla.corp.ebay.com/aw/listings",		// testy
	"http://listings.ebay.com/aw/listings",		// lizard
	"http://listings.ebay.com/aw/listings",		// asp
	"http://listings-qa.corp.ebay.com/aw/listings",	// cobrand
	"http://listings.uk.ebay.com/aw/listings",	// intl
};

static const char *eBayMembersPaths[] =
{
	"http://members.ebay.com/",					// production
	"http://members.ebay.com/",					// admin
	"http://members.ebay.com/",					// localhost
	"http://members.ebay.com/",					// developer
	"http://members.ebay.com/",					// testy
	"http://members.ebay.com/",					// lizard
	"http://members.ebay.com/",					// asp
	"http://members-qa.ebay.com/",					// cobrand
	"http://members.ebay.com/"					// intl
};

static const char *eBayAdminPaths[] =
{
	"http://skippy.ebay.com/aw-cgi/",			// production
	"http://skippy.ebay.com/aw-cgi/",			// admin
	"http://localhost/aw-cgi/",					// localhost
	"http://" DEVELOPER_SERVER "/aw-cgi/",		// developer
	"http://testy.corp.ebay.com/aw-cgi/admin/",	// testy
	"http://members.ebay.com/aw-cgi/admin/",	// lizard
	"http://asp.ebay.com/aw-cgi/admin",			// asp
	"http://skippy.ebay.com/aw-cgi/",			// cobrand
	"http://skippy.ebay.com/aw-cgi/",			// intl
};

static const char *CGI0s[] =
{
	"http://cgi.ebay.com",			// prod
	"http://cgi.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI1s[] = 
{
	"http://cgi1.ebay.com",			// prod
	"http://cgi1.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi1-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI2s[] = 
{
	"http://cgi2.ebay.com",			// prod
	"http://cgi2.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi2-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI3s[] = 
{
	"http://cgi3.ebay.com",			// prod
	"http://cgi3.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi3-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI4s[] = 
{
	"http://cgi4.ebay.com",			// prod
	"http://cgi4.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi4-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI5s[] = 
{
	"http://cgi5.ebay.com",			// prod
	"http://cgi5.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi5-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *CGI6s[] = 
{
	"http://cgi6.ebay.com",			// prod
	"http://cgi6.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi6-qa.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *HTMLs[] = 
{
	"http://pages.ebay.com",		// prod
	"http://skippy.ebay.com",		// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://pages-qa.corp.ebay.com",	// cobrand
	"http://bluetongue.ebay.com",	// intl
};

static const char *Admins[] = 
{
	"http://skippy.ebay.com",		// prod
	"http://skippy.ebay.com",		// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://skippy.corp.ebay.com",	// cobrand
	"http://admin.corp.ebay.com",	// intl
};

static const char *Betas[] =
{
	"http://cgi1.ebay.com",			// prod
	"http://cgi1.ebay.com",			// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://asp.ebay.com",			// asp
	"http://cgi.corp.ebay.com",	// cobrand
	"http://icgi.ebay.com",			// intl
};

static const char *Secures[] =
{
	"https://arribada.ebay.com",	// prod
	"https://arribada.ebay.com",	// admin
	"https://arribada.ebay.com",	// localhost
	"https://arribada.ebay.com",	// developer
	"https://arribada.ebay.com",	// testy
	"https://arribada.ebay.com",	// lizard
	"https://arribada.ebay.com",	// asp
	"https://arribada.ebay.com",	// cobrand
	"https://arribada.ebay.com",	// intl
};

static const char *AboutMes[] =
{
	"http://members.ebay.com",		// prod
	"http://members.ebay.com",		// admin
	"http://localhost",				// localhost
	"http://" DEVELOPER_SERVER "",	// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com",		// lizard
	"http://members.ebay.com",      // asp
	"http://members-qa.corp.ebay.com",		// cobrand
	"http://members.ebay.com",		// intl
};

static const char *Pics[] =
{
	"http://pics.ebay.com",			// prod
	"http://pics.ebay.com",			// admin
	"http://pics.ebay.com",			// localhost
	"http://pics.ebay.com",			// developer
	"http://pics.ebay.com",			// testy
	"http://members.ebay.com",		// lizard
	"http://pics.ebay.com",			// asp
	"http://pics.ebay.com",			// cobrand
	"http://pics.ebay.com",			// intl
};

static const char *eBayHomeURLs[] =
{
	"http://www.ebay.com",				// prod
	"http://www.ebay.com",				// admin
	"http://localhost/aw",				// localhost
	"http://" DEVELOPER_SERVER "/aw",		// developer
	"http://testy.corp.ebay.com",	// testy
	"http://members.ebay.com/aw",		// lizard
	"http://www.ebay.com",				// asp
	"http://pages-qa.corp.ebay.com/",	// cobrand
	"http://uk.ebay.com",				// intl
};


tServerMachines sServerNames = {
	"Error Here",
	CGI0s[EBAY_MACHINE],
	HTMLs[EBAY_MACHINE],
	CGI1s[EBAY_MACHINE],
	Admins[EBAY_MACHINE],
	Betas[EBAY_MACHINE],
	Secures[EBAY_MACHINE],
	AboutMes[EBAY_MACHINE],
	Pics[EBAY_MACHINE],
	CGI2s[EBAY_MACHINE],
	CGI3s[EBAY_MACHINE],
	CGI4s[EBAY_MACHINE],
	CGI5s[EBAY_MACHINE],
	CGI6s[EBAY_MACHINE],
};

// The next two arrays are used only for ValidateInternals.
const char *serverMeanings[] = {
	"Ignored",
	"CGI",
	"HTML",
	"CGI1",
	"Admin",
	"Beta",
	"Secure",
	"AboutMe",
	"Pics",
	"CGI2",
	"CGI3",
	"CGI4",
	"CGI5",
	"CGI6"
};

static const char *machineNames[] = 
{
	"production",
	"admin",
	"localhost",
	"developer",
	"testy",
	"lizard",
	"asp",
	"cobrand",
	"intl"
};




const char *eBayHTMLPath = eBayHTMLPaths[EBAY_MACHINE];
const char *eBayImagePath = eBayImagePaths[EBAY_MACHINE];
const char *eBaySSLImagePath = eBaySSLImagePaths[EBAY_MACHINE];
const char *eBaySecureHTMLPath = eBaySecureHTMLPaths[EBAY_MACHINE];
const char *eBayCGIPath = eBayCGIPaths[EBAY_MACHINE];
const char *eBaySSLCGIPath = eBaySSLCGIPaths[EBAY_MACHINE];
const char *eBaySSLHTMLPath = eBaySSLHTMLPaths[EBAY_MACHINE];

// kakiyama 07/20/99
const char *eBaySearchPath = eBaySearchPaths[EBAY_MACHINE];
const char *eBayGalleryListingPath = eBayGalleryListingPaths[EBAY_MACHINE];

const char *eBayAdminPath = eBayAdminPaths[EBAY_MACHINE];
const char *eBayListingPath = eBayListingPaths[EBAY_MACHINE];
const char *eBayMembersPath = eBayMembersPaths[EBAY_MACHINE];
const char *eBayHomeURL = eBayHomeURLs[EBAY_MACHINE];
const char *const machineName = machineNames[EBAY_MACHINE];

// End of configuration-specific initializations. Everything below
// this line should be invariant.

const char **gServerNames = sServerNames;
int gServerNamesSize = sizeof (sServerNames) / sizeof (const char *);



//
// GetCurrentMarketplace
//
static const char *eBayName	= "eBay";

// poon added for relative paths
static const char *eBayCGIRelativePath =
	"/aw-cgi/";

static const char *eBayHTMLRelativePath =
	"/";

static const char *eBayPicsRelativePath =
	"/aw/pics/";

static const char *eBayListingRelativePath =
	"/aw/listings";

static const char *eBaySecureHTMLRelativePath =
	"/aw-secure/";

// nsacco 08/04/99
// no longer used
// TODO - remove
static const char *eBayLoginPrompt =
	"<a href=\"http://pages.ebay.com/help/myinfo/userid.html\">User ID</a>";

// nsacco 08/04/99
// no longer used
// TODO - remove
static const char *eBayPasswordPrompt =
	"<a href=\"http://pages.ebay.com/services/registration/reqpass.html\">Password</a>";


static const char *eBayThankYouText =
	"Thank you for using eBay!";

static const char *eBayConfirmEmail = "aw-confirm@ebay.com";
static const char *eBaySupportEmail = "queue@support.ebay.com";
static const char *eBayAdminEmail	= "admin@ebay.com";
static const char *eBayBillingEmail	= "billing@ebay.com";
static const char *eBayRegistrationEmail	= "reg@ebay.com";

static const char *eBayCCardEmail	= "ccard@ebay.com";
static const char *eBayReportInfringingEmail = "ctywatch@ebay.com"; 

// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString

static const char *eBayHeader	=
	"<body bgcolor=\"#FFFFFF\">\n"
	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
		"<tr>"
			"<td width=\"120\"><a href=\"http://www.ebay.com\"><img "
			"src=\"http://pics.ebay.com/aw/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" "
			"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
			"<td><strong><font size=\"3\"><a "
			"href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
			"href=\"http://listings.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/newschat.html\">News/Chat</a>&nbsp; <a "
			"href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong>"
			"</td>"
		"</tr>"
		"<tr>"
			"<td width=\"120\">&nbsp;</td>"
			"<td>"
			"<font size=\"2\"><font color=\"darkgreen\">I have mine. </font>&nbsp; Do you have your <a "
			"href=\"http://pages.ebay.com/userid.html\">User ID</a>?</font>"
			"</td>"
		"</tr>"
	"</table><br>";


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
// TODO - check used
static const char *eBayAboutMeHeader	=
	"<body bgcolor=\"#FFFFFF\"> \n"
	"<MAP NAME=\"titlemap\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"437, 10, 482, 35\" HREF=\"http://pages.ebay.com/sitemap.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"371, 10, 429, 35\" HREF=\"http://pages.ebay.com/newschat.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"335, 10, 362, 35\" HREF=\"http://pages.ebay.com/contact.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"285, 10, 325, 35\" HREF=\"http://pages.ebay.com/search.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"233, 10, 276, 35\" HREF=\"http://pages.ebay.com/seller-services.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"183, 10, 223, 35\" HREF=\"http://pages.ebay.com/ps.html\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"129, 10, 172, 35\" HREF=\"http://listings.ebay.com/aw/listings/list\"> \n"
	"        <AREA SHAPE=\"RECT\" COORDS=\"0, 0, 116, 40\" HREF=\"http://www.ebay.com/\"> \n"
	"</MAP> \n"
	"<IMG SRC=\"http://pics.ebay.com/aw/pics/aboutme-home-title.gif\" ALT=\"eBay\" WIDTH=\"547\" HEIGHT=\"48\" BORDER=\"0\" USEMAP=\"#titlemap\">\n"
	"<!-- End of nav bar with the About Me title --> \n";


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
// TODO - check used?
static const char *eBaySecureHeader	=
	"<BODY BGCOLOR=\"#FFFFFF\">\n"
	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
	"<tr>\n"
	"<td width=\"150\">\n"
	"<a href=\"http://www.ebay.com/index.html\"><img src=\"https://scgi.ebay.com/saw/pics/navbar/ebay_logo_home.gif\" width=\"150\" hspace=\"0\" vspace=\"0\" height=\"70\" alt=\"eBay logo\" border=\"0\"></a>\n"
	"</td>\n"
	"<td width=\"450\" align=\"right\">\n"
	"<map name=\"home_myebay_map\">\n"
	"<area shape=rect coords=\"271,0,316,15\" href=\"http://www.ebay.com/index.html\" alt=\"Home\">\n"
	"<area shape=rect coords=\"317,0,378,15\" href=\"http://pages.ebay.com/services/myebay/myebay.html\" alt=\"My eBay\">\n"
	"<area shape=rect coords=\"379,0,444,15\" href=\"http://pages.ebay.com/sitemap.html\" alt=\"Site Map\">\n"
	"</map>\n"
	"<img src=\"https://scgi.ebay.com/saw/pics/navbar/home_myebay_map.gif\" width=450 height=15 alt=\"to Home, My eBay, and Sitemap\" border=0 usemap=\"#home_myebay_map\" align=\"right\"><br clear=\"all\">\n"
	"<MAP NAME=\"top_nav\">\n"
	"<area shape=rect coords=\"1,1,66,24\" href=\"http://pages.ebay.com/buy/index.html\" alt=\"Browse\">\n"
	"<area shape=rect coords=\"70,1,120,24\" href=\"http://cgi5.ebay.com/aw-cgi/eBayISAPI.dll?ListItemForSale\" alt=\"Sell\">\n"
	"<area shape=rect coords=\"124,1,196,24\" href=\"http://pages.ebay.com/services/index.html\" alt=\"Services\">\n"
	"<area shape=rect coords=\"201,1,262,24\" href=\"http://pages.ebay.com/search/items/search.html\" alt=\"Search\">\n"
	"<area shape=rect coords=\"266,1,315,24\" href=\"http://pages.ebay.com/help/index.html\" alt=\"Help\">\n"
	"<area shape=rect coords=\"319,1,414,24\" href=\"http://pages.ebay.com/community/index.html\" alt=\"Community\">\n"
	"</MAP>\n"
	"<img src=\"https://scgi.ebay.com/saw/pics/navbar/services-top.gif\" width=\"415\" height=\"25\" border=\"0\" alt=\"to Browse, Sell, Services, Search, Help, and Community\" usemap=\"#top_nav\" align=\"right\"><br clear=\"all\">\n"
	"<MAP NAME=\"browse_nav\">\n"
	"<AREA SHAPE=RECT COORDS=\"1,6,56,28\" HREF=\"http://pages.ebay.com/services/index.html\" alt=\"Overview\">\n"
	"<AREA SHAPE=RECT COORDS=\"57,7,127,28\" HREF=\"http://pages.ebay.com/services/registration/register.html\" alt=\"Registration\">\n"
	"<AREA SHAPE=RECT COORDS=\"126,6,223,28\" HREF=\"http://pages.ebay.com/services/buyandsell/index.html\" alt=\"Buy and Sell\">\n"
	"<AREA SHAPE=RECT COORDS=\"223,7,259,28\" HREF=\"http://pages.ebay.com/services/myebay/myebay.html\" alt=\"My eBay\">\n"
	"<AREA SHAPE=RECT COORDS=\"258,7,297,28\" HREF=\"http://pages.ebay.com/services/aboutme/aboutme-login.html\" alt=\"About Me\">\n"
	"<AREA SHAPE=RECT COORDS=\"297,6,353,28\" HREF=\"http://pages.ebay.com/services/forum/feedback.html\" alt=\"Feedback Forum\">\n"
	"<AREA SHAPE=RECT COORDS=\"353,6,395,28\" HREF=\"http://pages.ebay.com/services/safeharbor/index.html\" alt=\"SafeHarbor\">\n"
	"<AREA SHAPE=default HREF=\"http://www.ebay.com\">\n"
	"</MAP>\n"
	"<img src=\"https://scgi.ebay.com/saw/pics/navbar/services-registration.gif\" width=\"415\" height=\"30\" border=\"0\" alt=\"within Services, to Overview, Registration, Buy and Sell, My eBay, About Me, Feedback Forum, and SafeHarbor\" usemap=\"#browse_nav\" align=\"right\">\n"
	"</td>\n"
	"</tr>\n"
	"</table>\n"
	"<P>&nbsp;\n";


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
// TODO - check used
static const char *eBayRelativeHeader	=
	"<body bgcolor=\"#FFFFFF\">\n"
	"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
		"<tr>"
			"<td width=\"120\"><a href=\"/aw\"><img "
			"src=\"/aw/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" "
			"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
			"<td><strong><font size=\"3\"><a "
			"href=\"/\">Home</a>&nbsp; <a "
			"href=\"/aw/listings/list\">Listings</a>&nbsp; <a "
			"href=\"/ps.html\">Buyers</a>&nbsp; <a "
			"href=\"/seller-services.html\">Sellers</a>&nbsp; <a "
			"href=\"/search.html\">Search</a>&nbsp; <a "
			"href=\"/contact.html\">Help</a>&nbsp; <a "
			"href=\"/newschat.html\">News/Chat</a>&nbsp; <a "
			"href=\"/sitemap.html\">Site Map</a></font></strong>"
			"</td>"
		"</tr>"
		"<tr>"
			"<td width=\"120\">&nbsp;</td>"
			"<td>"
			"<font size=\"2\"><font color=\"darkgreen\">I have mine. </font>&nbsp; Do you have your <a "
			"href=\"http://pages.ebay.com/userid.html\">User ID</a>?</font>"
			"</td>"
		"</tr>"
	"</table><br>";




// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
// TODO - check used
static const char *eBayFooter =
	"<!-- footer -->\n"
	"<!-- begin copyright notice -->\n"
	"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
	"	<TR>\n"
	"		<TD COLSPAN=\"2\">\n"
	"			<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
	"			<br>\n"
	"			<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com\">eBay Store</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
	"			<br>\n"
	"		</TD>\n"
	"	</TR>\n"
	"	<TR>\n"
	"		<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
	"			<FONT SIZE=\"2\">\n"
	"			 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
	"			<BR>\n"
	"			 Designated trademarks and brands are the property of their respective owners. \n"
	"			<BR>\n"
	"			 Use of this Web site constitutes acceptance of the eBay \n"
	"			<A HREF=\"http://pages.ebay.com/help/basics/uarevision1-faq.html\">User Agreement</A>\n"
	"			</FONT>\n"
	"			<BR>\n"
	"		</TD>\n"
	"		<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
	"			<FONT SIZE=\"2\">\n"
	"			<A HREF=\"http://pages.ebay.com/help/community/png-priv.html\"><IMG SRC=\"http://pics.ebay.com/aw/pics/truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
	"			</FONT>\n"
	"		</TD>\n"
	"	</TR>\n"
	"</TABLE>\n"
	"<!-- end copyright notice -->\n"
	"<!-- footer -->\n";


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString

static const char *eBaySecureFooter =
	"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
	"<TR>\n"
	"<TD COLSPAN=\"2\">\n"
	"<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
	"<br>\n"
	"<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|"
	"&nbsp;&nbsp;<A HREF=\"http://cgi4.ebay.com/aw-cgi/eBayISAPI.dll?RegisterShow\">Register</A>&nbsp;&nbsp;|"
	"&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com/\">eBay Store</A>&nbsp;&nbsp;|"
	"&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|"
	"&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|"
	"&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
	"<br>\n"
	"</TD>\n"
	"</TR>\n"
	"<TR>\n"
	"<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
	"<font size=\"-1\">\n"
	"Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved.\n"
	"<BR>\n"
	"Designated trademarks and brands are the property of their respective owners.\n"
	"<BR>\n"
	"Use of this Web site constitutes acceptance of the eBay \n"
	"<A HREF=\"http://pages.ebay.com/help/community/png-user.html\">User Agreement</A>\n"
	"</FONT>\n"
	"<BR>\n"
	"</TD>\n"
	"<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
	"<font size=\"-1\">\n"
	"<a href=\"http://pages.ebay.com/help/community/png-priv.html\"><IMG SRC=\"https://scgi.ebay.com/saw/pics/truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
	"</FONT>\n"
	"</TD>\n"
	"</TR>\n"
	"</TABLE>\n";


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString

static const char *eBayRelativeFooter =
	"<!-- footer -->\n"
	"<!-- begin copyright notice -->\n"
	"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
	"	<TR>\n"
	"		<TD COLSPAN=\"2\">\n"
	"			<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
	"			<br>\n"
	"			<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com\">eBay Store</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
	"			<br>\n"
	"		</TD>\n"
	"	</TR>\n"
	"	<TR>\n"
	"		<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
	"			<FONT SIZE=\"2\">\n"
	"			 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
	"			<BR>\n"
	"			 Designated trademarks and brands are the property of their respective owners. \n"
	"			<BR>\n"
	"			 Use of this Web site constitutes acceptance of the eBay \n"
	"			<A HREF=\"http://pages.ebay.com/help/basics/uarevision1-faq.html\">User Agreement</A>\n"
	"			</FONT>\n"
	"			<BR>\n"
	"		</TD>\n"
	"		<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
	"			<FONT SIZE=\"2\">\n"
	"			<A HREF=\"http://pages.ebay.com/help/community/png-priv.html\"><IMG SRC=\"http://pics.ebay.com/aw/pics/truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
	"			</FONT>\n"
	"		</TD>\n"
	"	</TR>\n"
	"</TABLE>\n"
	"<!-- end copyright notice -->\n"
	"<!-- footer -->\n";




static const char *eBayBillingPolicyText = 
"\n"
"Make checks payable to: eBay Inc.\n"
"Please write your Account # or e-mail address on your check and send payments to:\n"
"Note: This address is for check and money order payments only!\n"
"\n"
"    eBay, Inc.\n"
"    P.O. Box 200945\n"
"    Dallas, TX 75320-0945\n"
"\n"
"Note: This address is for check and money order payments only!\n\n"
"Checks or money orders drawn on foreign banks must include an additional\n"
"US $10.00 to cover bank fees. Returned checks subject to $15.00 service charge.\n"
"Past due accounts are billed 1.5% monthly finance charge, minimum $0.50. Past\n"
"due accounts subject to termination and $5.00 reactivation fee. We reserve the\n"
"right to recover costs of collection. To preserve your rights, all billing\n"
"inquiries should be made in writing. Fees and policies are subject to change\n"
"without notice.\n"
"\n"
"All other correspondence should still be mailed to:\n"
"\n"
"    eBay, Inc.\n"
"    2005 Hamilton Avenue, Ste. 350\n"
"    San Jose, CA  95125\n";

static const double eBayFeaturedFee					= 49.95;
static const double eBayCategoryFeaturedFee			= 9.95;

static const double eBayBoldFee						= 2.00;
//static const double eBayGiftIconFee					= 1.00;

static const double eBayGalleryFee					= 0.25;
static const double eBayGalleryFeaturedFee			= 19.95;

static const double eBayItemMoveFee					= 0.00;

static const int eBayHotItemCount = 30;

static const char *eBaySpecialPasswordLevel1		= "not1but2";
static const char *eBaySpecialPasswordLevel2		= "2108fx9q";
static const char *eBayAdminSpecialPassword			= "3smart2";

static const clsMarketPlaceUserCriteria eBayListCriteria =
{	true,	true,	true,	
	true,	-3,
	false,	false,	false,	
	true,	-10.00,
	true,	-5.00,
	true	
};

static const clsMarketPlaceUserCriteria eBayFeatureCriteria =
{	true,	true,	true,
	true,	10,
	false,	false,	false,	
	true,	-10.00,
	true,	-5.00,
	true	
};

static const clsMarketPlaceUserCriteria eBayBidCriteria =
{	true,	true,	true,	
	true,	-3,
	false,	false,	false,	
	false,	0.0,
	false,	0.0,
	true		};


//
// Default Constructor
//
clsMarketPlaces::clsMarketPlaces() : mpCurrentMarketPlace(NULL)
{
}

//
// Destructor
//
clsMarketPlaces::~clsMarketPlaces()
{
	delete	mpCurrentMarketPlace;
	mpCurrentMarketPlace = NULL;
}



clsMarketPlace *clsMarketPlaces::GetCurrentMarketPlace()
{
	if (mpCurrentMarketPlace == NULL)
	{
		mpCurrentMarketPlace	= 
			new clsMarketPlace((MarketPlaceId)0, 
										eBayName,
										eBayHeader,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
														"<body bgcolor=\"#FFFFFF\">\n"
														"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
															"<tr>"
																"<td width=\"120\"><a href=\"%{1:GetHTMLPath}\"><img "
																"src=\"%{2:GetPicsPath}logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" "
																"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
																"<td><strong><font size=\"3\"><a "
																"href=\"%{3:GetHTMLPath}\">Home</a>&nbsp; <a "
																"href=\"%{4:GetListingPath}list\">Listings</a>&nbsp; <a "
																"href=\"%{5:GetHTMLPath}ps.html\">Buyers</a>&nbsp; <a "
																"href=\"%{6:GetHTMLPath}seller-services.html\">Sellers</a>&nbsp; <a "
																"href=\"%{7:GetHTMLPath}search.html\">Search</a>&nbsp; <a "
																"href=\"%{8:GetHTMLPath}contact.html\">Help</a>&nbsp; <a "
																"href=\"%{9:GetHTMLPath}newschat.html\">News/Chat</a>&nbsp; <a "
																"href=\"%{10:GetHTMLPath}sitemap.html\">Site Map</a></font></strong>"
																"</td>"
															"</tr>"
															"<tr>"
																"<td width=\"120\">&nbsp;</td>"
																"<td>"
																"<font size=\"2\"><font color=\"darkgreen\">I have mine. </font>&nbsp; Do you have your <a "
																"href=\"%{11:GetHTMLPath}userid.html\">User ID</a>?</font>"
																"</td>"
															"</tr>"
															"</table><br>",
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 1
																clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),			// 2
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 3
																clsIntlResource::ToStirng(mpMarketPlace->GetListingPath()),			// 4
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 5
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 6
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 7
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 8
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 9
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 10
																clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),			// 11
																NULL),													"</table><br>",
														
*/

										eBayAboutMeHeader,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
													"<body bgcolor=\"#FFFFFF\"> \n"
													"<MAP NAME=\"titlemap\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"437, 10, 482, 35\" HREF=\"%{1:GetHTMLPath}sitemap.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"371, 10, 429, 35\" HREF=\"%{2:GetHTMLPath}newschat.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"335, 10, 362, 35\" HREF=\"%{3:GetHTMLPath}contact.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"285, 10, 325, 35\" HREF=\"%{4:GetHTMLPath}search.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"233, 10, 276, 35\" HREF=\"%{5:GetHTMLPath}seller-services.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"183, 10, 223, 35\" HREF=\"%{6:GetHTMLPath}ps.html\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"129, 10, 172, 35\" HREF=\"%{7:GetListingPath}list\"> \n"
													"        <AREA SHAPE=\"RECT\" COORDS=\"0, 0, 116, 40\" HREF=\"%{8:GetHTMLPath}\"> \n"
													"</MAP> \n"
													"<IMG SRC=\"%{9:GetPicsPath}aboutme-home-title.gif\" ALT=\"eBay\" WIDTH=\"547\" HEIGHT=\"48\" BORDER=\"0\" USEMAP=\"#titlemap\">\n"
													"<!-- End of nav bar with the About Me title --> \n",
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetListingPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
																		clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
																		NULL),
*/
										eBaySecureHeader,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
													"<BODY BGCOLOR=\"#FFFFFF\">\n"
													"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
													"<tr>\n"
													"<td width=\"150\">\n"
													"<a href=\"%{1:GetHTMLPath}index.html\"><img src=\"https://scgi.ebay.com/saw/pics/navbar/ebay_logo_home.gif\" width=\"150\" hspace=\"0\" vspace=\"0\" height=\"70\" alt=\"eBay logo\" border=\"0\"></a>\n"
													"</td>\n"
													"<td width=\"450\" align=\"right\">\n"
													"<map name=\"home_myebay_map\">\n"
													"<area shape=rect coords=\"271,0,316,15\" href=\"%{2:GetHTMLPath}index.html\" alt=\"Home\">\n"
													"<area shape=rect coords=\"317,0,378,15\" href=\"%{3:GetHTMLPath}services/myebay/myebay.html\" alt=\"My eBay\">\n"
													"<area shape=rect coords=\"379,0,444,15\" href=\"%{4:GetHTMLPath}sitemap.html\" alt=\"Site Map\">\n"
													"</map>\n"
													"<img src=\"https://scgi.ebay.com/saw/pics/navbar/home_myebay_map.gif\" width=450 height=15 alt=\"to Home, My eBay, and Sitemap\" border=0 usemap=\"#home_myebay_map\" align=\"right\"><br clear=\"all\">\n"
													"<MAP NAME=\"top_nav\">\n"
													"<area shape=rect coords=\"1,1,66,24\" href=\"%{5:GetHTMLPath}buy/index.html\" alt=\"Browse\">\n"
													"<area shape=rect coords=\"70,1,120,24\" href=\"%{6:GetCGIPath}eBayISAPI.dll?ListItemForSale\" alt=\"Sell\">\n"
													"<area shape=rect coords=\"124,1,196,24\" href=\"%{7:GetHTMLPath}services/index.html\" alt=\"Services\">\n"
													"<area shape=rect coords=\"201,1,262,24\" href=\"%{8:GetHTMLPath}search/items/search.html\" alt=\"Search\">\n"
													"<area shape=rect coords=\"266,1,315,24\" href=\"%{9:GetHTMLPath}help/index.html\" alt=\"Help\">\n"
													"<area shape=rect coords=\"319,1,414,24\" href=\"%{10:GetHTMLPath}community/index.html\" alt=\"Community\">\n"
													"</MAP>\n"
													"<img src=\"https://scgi.ebay.com/saw/pics/navbar/services-top.gif\" width=\"415\" height=\"25\" border=\"0\" alt=\"to Browse, Sell, Services, Search, Help, and Community\" usemap=\"#top_nav\" align=\"right\"><br clear=\"all\">\n"
													"<MAP NAME=\"browse_nav\">\n"
													"<AREA SHAPE=RECT COORDS=\"1,6,56,28\" HREF=\"%{11:GetHTMLPath}services/index.html\" alt=\"Overview\">\n"
													"<AREA SHAPE=RECT COORDS=\"57,7,127,28\" HREF=\"%{12:GetHTMLPath}services/registration/register.html\" alt=\"Registration\">\n"
													"<AREA SHAPE=RECT COORDS=\"126,6,223,28\" HREF=\"%{13:GetHTMLPath}services/buyandsell/index.html\" alt=\"Buy and Sell\">\n"
													"<AREA SHAPE=RECT COORDS=\"223,7,259,28\" HREF=\"%{14:GetHTMLPath}services/myebay/myebay.html\" alt=\"My eBay\">\n"
													"<AREA SHAPE=RECT COORDS=\"258,7,297,28\" HREF=\"%{15:GetHTMLPath}services/aboutme/aboutme-login.html\" alt=\"About Me\">\n"
													"<AREA SHAPE=RECT COORDS=\"297,6,353,28\" HREF=\"%{16:GetHTMLPath}services/forum/feedback.html\" alt=\"Feedback Forum\">\n"
													"<AREA SHAPE=RECT COORDS=\"353,6,395,28\" HREF=\"%{17:GetHTMLPath}services/safeharbor/index.html\" alt=\"SafeHarbor\">\n"
													"<AREA SHAPE=default HREF=\"%{18:GetHTMLPath}\">\n"
													"</MAP>\n"
													"<img src=\"https://scgi.ebay.com/saw/pics/navbar/services-registration.gif\" width=\"415\" height=\"30\" border=\"0\" alt=\"within Services, to Overview, Registration, Buy and Sell, My eBay, About Me, Feedback Forum, and SafeHarbor\" usemap=\"#browse_nav\" align=\"right\">\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n"
													"<P>&nbsp;\n",
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetCGIPath(PageListItemForSale)),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToStirng(mpMarketPlace->GetHTMLPath()),
													NULL),

*/
										eBayFooter,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
													"<!-- footer -->\n"
													"<!-- begin copyright notice -->\n"
													"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
													"	<TR>\n"
													"		<TD COLSPAN=\"2\">\n"
													"			<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
													"			<br>\n"
													"			<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{1:GetHTMLPath}services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com\">eBay Store</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{2:GetHTMLPath}services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{3:GetHTMLPath}services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{4:GetHTMLPath}community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
													"			<br>\n"
													"		</TD>\n"
													"	</TR>\n"
													"	<TR>\n"
													"		<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
													"			<FONT SIZE=\"2\">\n"
													"			 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
													"			<BR>\n"
													"			 Designated trademarks and brands are the property of their respective owners. \n"
													"			<BR>\n"
													"			 Use of this Web site constitutes acceptance of the eBay \n"
													"			<A HREF=\"http:%{5:GetHTMLPath}help/basics/uarevision1-faq.html\">User Agreement</A>\n"
													"			</FONT>\n"
													"			<BR>\n"
													"		</TD>\n"
													"		<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
													"			<FONT SIZE=\"2\">\n"
													"			<A HREF=\"%{6:GetHTMLPath}/help/community/png-priv.html\"><IMG SRC=\"%{7:GetPicsPath}truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
													"			</FONT>\n"
													"		</TD>\n"
													"	</TR>\n"
													"</TABLE>\n"
													"<!-- end copyright notice -->\n"
													"<!-- footer -->\n",
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
													NULL),

*/
										eBaySecureFooter,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
													"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
													"<TR>\n"
													"<TD COLSPAN=\"2\">\n"
													"<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
													"<br>\n"
													"<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|"
													"&nbsp;&nbsp;<A HREF=\"%{1:GetCGIPath}eBayISAPI.dll?RegisterShow\">Register</A>&nbsp;&nbsp;|"
													"&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com/\">eBay Store</A>&nbsp;&nbsp;|"
													"&nbsp;&nbsp;<A HREF=\"%{2:GetHTMLPath}services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|"
													"&nbsp;&nbsp;<A HREF=\"%{3:GetHTMLPath}services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|"
													"&nbsp;&nbsp;<A HREF=\"%{4:GetHTMLPath}community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
													"<br>\n"
													"</TD>\n"
													"</TR>\n"
													"<TR>\n"
													"<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
													"<font size=\"-1\">\n"
													"Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved.\n"
													"<BR>\n"
													"Designated trademarks and brands are the property of their respective owners.\n"
													"<BR>\n"
													"Use of this Web site constitutes acceptance of the eBay \n"
													"<A HREF=\"%{5:GetHTMLPath}help/community/png-user.html\">User Agreement</A>\n"
													"</FONT>\n"
													"<BR>\n"
													"</TD>\n"
													"<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
													"<font size=\"-1\">\n"
													"<a href=\"%{6:GetHTMLPath}help/community/png-priv.html\"><IMG SRC=\"https://scgi.ebay.com/saw/pics/truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
													"</FONT>\n"
													"</TD>\n"
													"</TR>\n"
													"</TABLE>\n",
													clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageRegisterShow)),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													NULL),
*/
										eBayRelativeHeader,
// kakiyama 08/02/99
/*										clsIntlResource;:GetFResString(-1,
													"<body bgcolor=\"#FFFFFF\">\n"
													"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
														"<tr>"
															"<td width=\"120\"><a href=\"/aw\"><img "
															"src=\"/aw/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" "
															"height=\"42\" alt=\"eBay logo\" border=\"0\"></a></td>"
															"<td><strong><font size=\"3\"><a "
															"href=\"/\">Home</a>&nbsp; <a "
															"href=\"/aw/listings/list\">Listings</a>&nbsp; <a "
															"href=\"/ps.html\">Buyers</a>&nbsp; <a "
															"href=\"/seller-services.html\">Sellers</a>&nbsp; <a "
															"href=\"/search.html\">Search</a>&nbsp; <a "
															"href=\"/contact.html\">Help</a>&nbsp; <a "
															"href=\"/newschat.html\">News/Chat</a>&nbsp; <a "
															"href=\"/sitemap.html\">Site Map</a></font></strong>"
															"</td>"
														"</tr>"
														"<tr>"
															"<td width=\"120\">&nbsp;</td>"
															"<td>"
															"<font size=\"2\"><font color=\"darkgreen\">I have mine. </font>&nbsp; Do you have your <a "
															"href=\"%{1:GetHTMLPath}userid.html\">User ID</a>?</font>"
															"</td>"
														"</tr>"
													"</table><br>",
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													NULL),

*/

										eBayRelativeFooter,
// kakiyama 08/02/99
/*										clsIntlResource::GetFResString(-1,
													"<!-- footer -->\n"
													"<!-- begin copyright notice -->\n"
													"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
													"	<TR>\n"
													"		<TD COLSPAN=\"2\">\n"
													"			<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
													"			<br>\n"
													"			<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{1:GetHTMLPath}services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com\">eBay Store</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{2:GetHTMLPath}services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{3:GetHTMLPath}services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{4:GetHTMLPath}community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
													"			<br>\n"
													"		</TD>\n"
													"	</TR>\n"
													"	<TR>\n"
													"		<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
													"			<FONT SIZE=\"2\">\n"
													"			 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
													"			<BR>\n"
													"			 Designated trademarks and brands are the property of their respective owners. \n"
													"			<BR>\n"
													"			 Use of this Web site constitutes acceptance of the eBay \n"
													"			<A HREF=\"%{5:GetHTMLPath}help/basics/uarevision1-faq.html\">User Agreement</A>\n"
													"			</FONT>\n"
													"			<BR>\n"
													"		</TD>\n"
													"		<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
													"			<FONT SIZE=\"2\">\n"
													"			<A HREF=\"%{6:GetHTMLPath}help/community/png-priv.html\"><IMG SRC=\"%{7:GetPicsPath}truste_button.gif\" ALIGN=\"middle\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
													"			</FONT>\n"
													"		</TD>\n"
													"	</TR>\n"
													"</TABLE>\n"
													"<!-- end copyright notice -->\n"
													"<!-- footer -->\n",
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
													clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
													NULL),
*/
										eBayHTMLPath,
										eBayHTMLRelativePath,
										eBayImagePath,
										eBaySecureHTMLPath,
										eBaySecureHTMLRelativePath,
										eBayCGIPath,
										eBayCGIRelativePath,
										eBayImagePath,
										eBayPicsRelativePath,
										NULL, // pSearchPath
										NULL, // pGalleryListingPath
										eBaySSLCGIPath,
										eBaySSLHTMLPath,
										eBaySSLImagePath,
										eBayAdminPath,
										eBayListingPath,
										eBayListingRelativePath,
										eBayMembersPath,
										eBayLoginPrompt,
										eBayPasswordPrompt,
										eBayHomeURL,
										eBayThankYouText,
										eBayConfirmEmail,
										eBayAdminEmail,
										eBaySupportEmail,
										eBayBillingEmail,
										eBayRegistrationEmail,
										eBayBillingPolicyText,
										0,
										NULL,
										0,
										NULL,
										eBayFeaturedFee,
										eBayCategoryFeaturedFee,
										eBayBoldFee,
										0.0, // obsolete eBayGiftIconFee,
										eBayGalleryFee,
										eBayGalleryFeaturedFee,
										eBayItemMoveFee,
										eBayHotItemCount,
										eBaySpecialPasswordLevel1,
										eBaySpecialPasswordLevel2,
										eBayAdminSpecialPassword,
										&eBayListCriteria,
										&eBayFeatureCriteria,
										&eBayBidCriteria,
										eBayCCardEmail,
										eBayReportInfringingEmail
								);
	}
	return mpCurrentMarketPlace;
}


