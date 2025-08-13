/*	$Id: clsPartner.cpp,v 1.6.168.12.4.5 1999/08/10 01:19:52 nsacco Exp $	*/
//
// File Name: clsPartner.cpp
//
// Description: Does the headers and footers and cgi and html path
// and just all sorts of cool things for cobranding (and everything
// is cobranding now)
//
// Authors: Chad Musick, Craig Huang
//
//			05/25/99	nsacco	- Moved pAnnouncementHeaderPrefix and 
//								Suffix to clsPartner.h. Added a mSiteId
//								to LoadPartners.
//			07/12/99	nsacco	- Modified GetFooter() to pull footers out of the 
//								database now for international sites. 
//			07/12/99	nsacco	- Added a new constructor
//			08/09/99	nsacco	- Added GetAdPicsPath().
//
#include "eBayKernel.h"
#include "clsPartner.h"
#include "clseBayHeaderAnnounceWidget.h"
#include <stdio.h>

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif

extern int sNumPageToPageType;

extern CoPageRec CoBrandArray[];			   																										
extern const char **gServerNames;
extern int gServerNamesSize;
extern const char *serverMeanings[];

const char * DEFAULTCGI = "/aw-part000-cgi/";
const char * EBAYCGI	  = "/aw-cgi/";
const char * DEFAULTHTML = "/aw-part000/";
const char * EBAYHTML	  = "/";
const char * DEFAULTPICS = "/aw-part000/pics";
const char * EBAYPICS	  = "/aw/pics/";

// kakiyama 07/19/99
const char * EBAYSEARCH	= "/cgi-bin/";

// Constructor. We set up all the Relative paths, but not
// the absolute HTML and CGI paths because those may change.
// nsacco 05/25/99 added mSiteId and SiteId
clsPartner::clsPartner(const char *pName, int id, const char *pDesc, int SiteId/*=0*/, const char *pParsedString/*=NULL*/) 
	: mId(id), 
	  mIsLoaded(false),
	  mpvHeaders(NULL), 
	  mpvFooters(NULL), 
	  mpvDeletes(NULL), 
	  mpvHeaderWithAnnouncements(NULL),
	  mSiteId(SiteId), 
	  mpPartnerAds(NULL)
{
	const char *pString;
	char *pStr;
	char *pConstruct;
	char buffer[9];
	clsMarketPlace *pMarket;
	int i;

	mpName = strdup(pName);
	mpDesc = strdup(pDesc);
	mpParsedString = strdup(pParsedString);

	mppCGIPath = new char *[gServerNamesSize];
	i = gServerNamesSize;
	while (i--)
		mppCGIPath[i] = NULL;

	mppHTMLPath = new char *[gServerNamesSize];
	i = gServerNamesSize;
	while (i--)
		mppHTMLPath[i] = NULL;

	mppPicsPath = new char *[gServerNamesSize];
	i = gServerNamesSize;
	while (i--)
		mppPicsPath[i] = NULL;

// kakiyama 07/20/99
	mppSearchPath = new char *[gServerNamesSize];
	i = gServerNamesSize;
	while (i--)
		mppSearchPath[i] = NULL;


	pMarket = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	if (!strcasecmp(pName, "ebay"))
	{
		// don't use strdup here.
		pConstruct = new char [strlen(pMarket->GetCGIRelativeNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetCGIRelativeNoCobrandPath());
		mpCGIRelativePath = pConstruct;

		pConstruct = new char [strlen(pMarket->GetHTMLRelativeNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetHTMLRelativeNoCobrandPath());
		mpHTMLRelativePath = pConstruct;

		pConstruct = new char [strlen(pMarket->GetPicsRelativeNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetPicsRelativeNoCobrandPath());
		mpPicsRelativePath = pConstruct;

		pConstruct = new char [strlen(pMarket->GetListingNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetListingNoCobrandPath());
		mpListingPath = pConstruct;

		pConstruct = new char [strlen(pMarket->GetListingRelativeNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetListingRelativeNoCobrandPath());
		mpListingRelativePath = pConstruct;

	// kakiyama 07/20/99
		pConstruct = new char [strlen(pMarket->GetGalleryListingNoCobrandPath()) + 1];
		strcpy(pConstruct, pMarket->GetGalleryListingNoCobrandPath());
		mpGalleryListingPath = pConstruct;

		mCurrentCGIServer = 0;
		mCurrentHTMLServer = 0;
		mCurrentPicsServer = 0;
// kakiyama 07/20/99
		mCurrentSearchServer = 0;


		return;
	}

	pString = pMarket->GetCGIRelativeNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	pStr = strstr(pConstruct, "-cgi/");
	if (pStr)
	{
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 8, pStr, strlen(pStr) + 1);
		memcpy(pStr, buffer, 8);
		mpCGIRelativePath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpCGIRelativePath = NULL;
	}

	pString = pMarket->GetHTMLRelativeNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	// nsacco 08/02/99  - was /aw/
	// changed since /aw is no longer in html paths
	pStr = strstr(pConstruct, "/");
	if (pStr)
	{
		// Add 3 to pStr to skip /aw
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 11, pStr + 3, strlen(pStr + 3) + 1);
		memcpy(pStr + 3, buffer, 8);
		mpHTMLRelativePath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpHTMLRelativePath = NULL;
	}

	pString = pMarket->GetPicsRelativeNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	pStr = strstr(pConstruct, "/aw/pics");
	if (pStr)
	{
		// Add 8 to pStr to skip /aw/pics
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 11, pStr + 8, strlen(pStr + 8) + 1);
		memcpy(pStr + 8, buffer, 8);
		mpPicsRelativePath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpPicsRelativePath = NULL;
	}

	pString = pMarket->GetListingNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	// nsacco 08/02/99
	// changed since /aw is no longer in the html path
	pStr = strstr(pConstruct, "/");
	if (pStr)
	{
		// Add 3 to pStr to skip /aw
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 11, pStr + 3, strlen(pStr + 3) + 1);
		memcpy(pStr + 3, buffer, 8);
		mpListingPath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpListingPath = NULL;
	}



// kakiyama 07/20/99

	pString = pMarket->GetGalleryListingNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	// TODO - this may need to be removed since /aw is no longer in the html path
	pStr = strstr(pConstruct, "/");
	if (pStr)
	{
		// Add 3 to pStr to skip /aw
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 11, pStr + 3, strlen(pStr + 3) + 1);
		memcpy(pStr + 3, buffer, 8);
		mpListingPath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpGalleryListingPath = NULL;
	}







	pString = pMarket->GetListingRelativeNoCobrandPath();
	pConstruct = new char [strlen(pString) + 8 + 1]; // The 8 is '-part000'

	strcpy(pConstruct, pString);
	// nsacco 08/02/99
	// changed since /aw is no longer in the html path
	pStr = strstr(pConstruct, "/");
	if (pStr)
	{
		// Add 3 to pStr to skip /aw
		sprintf(buffer, "-part%03d", mId);
		memmove(pStr + 11, pStr + 3, strlen(pStr + 3) + 1);
		memcpy(pStr + 3, buffer, 8);
		mpListingRelativePath = pConstruct;
	}
	else
	{
		delete pConstruct;
		mpListingRelativePath = NULL;
	}
	mCurrentCGIServer = 0;
	mCurrentHTMLServer = 0;
	mCurrentPicsServer = 0;
	mCurrentSearchServer = 0;
}

// Destructor. Silly VC++ bug that won't let us
// delete a const char * -- what's that all about?
clsPartner::~clsPartner()
{
	vector<char *>::iterator i;
	int j;
	char *cp;

	j = gServerNamesSize;
	while (j--)
		delete mppCGIPath[j];
	delete [] mppCGIPath;

	j = gServerNamesSize;
	while (j--)
		delete mppHTMLPath[j];
	delete mppHTMLPath;

	j = gServerNamesSize;
	while (j--)
		delete mppPicsPath[j];
	delete mppPicsPath;

	cp = (char *) mpDesc;
	if( cp )
		free (cp);
	cp = (char *) mpName;
	if ( cp )
		free (cp);
	cp = (char *) mpCGIRelativePath;
	if ( cp )
		delete cp;
	cp = (char *) mpHTMLRelativePath;
	if ( cp )
		delete cp;
	cp = (char *) mpPicsRelativePath;
	if ( cp )
		delete cp;
	cp = (char *) mpListingPath;
	if ( cp )
		delete cp;
// kakiyama 07/20/99
	cp = (char *) mpGalleryListingPath;
	if ( cp )
		delete cp;
	cp = (char *) mpListingRelativePath;
	if ( cp )
		delete cp;

	if (mpvHeaders)
	{
		mpvHeaders->erase(mpvHeaders->begin(), mpvHeaders->end());
		delete mpvHeaders;
	}

	if (mpvFooters)
	{
		mpvFooters->erase(mpvFooters->begin(), mpvFooters->end());
		delete mpvFooters;
	}

	if ( mpvHeaderWithAnnouncements )
	{	
		for (i = mpvHeaderWithAnnouncements->begin(); i != mpvHeaderWithAnnouncements->end(); ++i)
		{
			if(*i)
				delete *i;
		}
		mpvHeaderWithAnnouncements->erase(mpvHeaderWithAnnouncements->begin(), mpvHeaderWithAnnouncements->end());
		delete mpvHeaderWithAnnouncements;
	}
	if (mpvDeletes)
	{
		for (i = mpvDeletes->begin(); i != mpvDeletes->end(); ++i)
		{
			delete *i;
		}
		mpvDeletes->erase(mpvDeletes->begin(), mpvDeletes->end());
		delete mpvDeletes;
	}

	delete mpPartnerAds;

	// kakiyama 06/23/99
	delete mpParsedString;

	return;
}

/*
// AlexP 05/14/99
// These two static const char*'s are for making the header announcements
//  appear next to a search box on every eBay page.
// They are used in clsPartner::GetHeader() below.
static const char* pAnnouncementHeaderPrefix =
"<FORM ACTION=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html\" METHOD=\"GET\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsReturned\" VALUE=\"300\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsPerPage\" VALUE=\"50\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"ht\" value=\"1\">\n"
"<!-- table for search and announcements -->\n"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"2\">\n"
"  <TR>\n"
"	<!-- announcements -->\n"
"	<TD width=\"65%\" bgcolor=\"#FFFFCC\">\n";

static const char* pAnnouncementAOLHeaderPrefix =
"<FORM ACTION=\"http://sungazer.ebay.com/cgi-bin/texis/ebaywin/results.html\" METHOD=\"GET\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsReturned\" VALUE=\"300\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsPerPage\" VALUE=\"50\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"ht\" value=\"2\">\n"
"<!-- table for search and announcements -->\n"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"2\">\n"
"  <TR>\n"
"	<!-- announcements -->\n"
"	<TD width=\"65%\" bgcolor=\"#FFFFCC\">\n";

static const char* pAnnouncementHeaderSuffix =
"\n"
"   </TD>\n"
"	<!-- search box -->\n"
"	<TD width=\"35%\">\n"
"	<input NAME=\"query\" SIZE=\"12\" MAXLENGTH=\"100\"> <input TYPE=\"SUBMIT\" VALUE=\"Search\"> <font size=\"1\" face=\"arial,helvetica\"><a href=\"http://pages.ebay.com/help/buyerguide/search.html\">tips</a></font>\n"
"   <br><font size=\"1\" face=\"Arial,Helvetica\"><input type=checkbox name=srchdesc value=\"y\">Search titles <b>and</b> descriptions</font>"
"	</TD>\n"
"  </TR>\n"
"</TABLE>\n"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"0\">\n"
"  <TR>\n"
"		<TD><hr></TD>\n"
"  </TR>\n"
"</TABLE>\n"
"</FORM>\n";
*/

// Get the header for the page type.
const char *clsPartner::GetHeader(PageTypeEnum pageType, 
								  PageTypeEnum secondaryPageType,
								  bool getAds)
{
	const char *pRet;
	int	dwLen;
	ostrstream *pStream;	
	int headerLen;
	clseBayHeaderAnnounceWidget *pHeaderAnnounce;
	int i;

	// new ui didn't have a good place for the announcement headers yet, so....
	// return GetHeaderWithoutAnnouncement(pageType, secondaryPageType);

	// anything less than zero is PageTypeUnknown
	if ((pageType < 0) || (secondaryPageType < 0))
		return "";	

	i = GetUniqueIndex(pageType, secondaryPageType);

	// load if not yet in cache
	if (!mIsLoaded)
		LoadPartner();

	if (i >= mpvHeaders->size())
		return "";

	pRet = (*mpvHeaders)[i];

	// safety
	if (!pRet)
		return "";

	// The header announcements
	if( mpvHeaderWithAnnouncements  )
	{		
		if( !(*mpvHeaderWithAnnouncements)[i] )
		{
			pStream	= new ostrstream;
			pHeaderAnnounce = new clseBayHeaderAnnounceWidget;
			pHeaderAnnounce->EmitPrefix(pStream);
			pHeaderAnnounce->EmitHTML(pStream);		
			pHeaderAnnounce->EmitSuffix(pStream);
			headerLen = strlen(pRet);
			dwLen = headerLen + 
					strlen(pAnnouncementHeaderSuffix) + 	// AlexP 05/14/99
//					strlen(pAnnouncementHeaderPrefix) +		// AlexP 05/14/99
					pStream->pcount() + 
//					strlen(pAnnouncementHeaderSuffix) + 	// AlexP 05/14/99
					1;		

			(*mpvHeaderWithAnnouncements)[i] = new char[dwLen];
			if( (*mpvHeaderWithAnnouncements)[i] )
			{			
				memset((*mpvHeaderWithAnnouncements)[i], '\0', dwLen);

				strcat((*mpvHeaderWithAnnouncements)[i], pRet);
//				strcat((*mpvHeaderWithAnnouncements)[i], pAnnouncementHeaderPrefix);	// AlexP 05/14/99
				strncat((*mpvHeaderWithAnnouncements)[i], pStream->str(), pStream->pcount());					
//				strcat((*mpvHeaderWithAnnouncements)[i], pAnnouncementHeaderSuffix);	// AlexP 05/14/99

				pRet = (*mpvHeaderWithAnnouncements)[i]; 
			}
			else if (!(*mpvHeaderWithAnnouncements)[i])
			{
				delete pHeaderAnnounce;
				delete pStream;
				return "";		
			}
			delete pStream;
			delete pHeaderAnnounce;
			return pRet;
		}
		else
		{
			pRet = (*mpvHeaderWithAnnouncements)[i];	
			if( !pRet )
				return "";
			else
				return pRet;
		}
	}		
	else
		return "";
}

const char *clsPartner::GetHeaderWithoutAnnouncement(PageTypeEnum pageType, 
													PageTypeEnum secondaryPageType,
													bool getAds)
{
	const char *pRet;
//	int	dwLen;
//	int headerLen;
//	clseBayHeaderAnnounceWidget *pHeaderAnnounce;
	int i;


	// anything less than zero is PageTypeUnknown
	if ((pageType < 0) || (secondaryPageType < 0))
		return "";	

	i = GetUniqueIndex(pageType, secondaryPageType);

	// load if not yet in cache
	if (!mIsLoaded)
		LoadPartner();

	if (i >= mpvHeaders->size())
		return "";

	pRet = (*mpvHeaders)[i];	

	// safety
	if (!pRet)
		return "";
	
	return pRet;
}

// Get the footer for the page type.
const char *clsPartner::GetFooter(PageTypeEnum pageType, 
								  PageTypeEnum secondaryPageType,
								  bool getAds)
{
	const char *pRet = NULL;
	int i;

	// anything less than zero is PageTypeUnknown
	if ((pageType < 0) || (secondaryPageType < 0))
		return "";	

	// nsacco 07/12/99
	// TODO - remove the check for the site once footers are in db 
	// and always use pageType and secondaryPageType
	if ((GetSiteId() == SITE_EBAY_MAIN) ||
		(GetSiteId() == SITE_EBAY_US))
	{
		// main site code
		i = GetUniqueIndex(PageType1, PageType0);
	}
	else
	{
		// international code
		i = GetUniqueIndex(pageType, secondaryPageType);
	}

	// load if not yet in cache
	if (!mIsLoaded)
		LoadPartner();

	if (i >= mpvFooters->size())
		return "";

	pRet = (*mpvFooters)[i];

	// safety
	if (!pRet)
		return "";
	
	return pRet;
}

// Set the header, and also write it out to a file.
void clsPartner::SetHeader(PageTypeEnum pType, PageTypeEnum pType2, const char *pNewDescription)
{
	char descBuffer[256];

	sprintf(descBuffer, "%s %d %d Header",
		mpName, (int) pType, (int) pType2);

	gApp->GetDatabase()->SetCobrandHeader(mId, 1, pType, pType2,
		(char *) descBuffer, pNewDescription, mSiteId);

	WriteHeaderToFile(pType, "/ebay/cobrandHTML/Debug");

	return;
}

// Set the footer, and also write it out to a file.
void clsPartner::SetFooter(PageTypeEnum pType, PageTypeEnum pType2, const char *pNewDescription)
{
	char descBuffer[256];

	sprintf(descBuffer, "%s %d Footer",
		mpName, (int) pType);

	gApp->GetDatabase()->SetCobrandHeader(mId, 0, pType, pType2,
		(char *) descBuffer, pNewDescription, mSiteId);

	WriteFooterToFile(pType, "/ebay/cobrandHTML/Debug");

	return;
}

// Load up the headers and footers for this partner,
// presumably we've been asked what they are.
void clsPartner::LoadPartner()
{
	int j=0;
	
	// don't load if already loaded into cache
	if (mIsLoaded)
		return;

	mpvHeaders = new vector<char *>;
	mpvFooters = new vector<char *>;
	mpvDeletes = new vector<char *>;
	mpvHeaderWithAnnouncements = new vector<char *>;
	// nsacco 05/25/99 added mSiteId	
	gApp->GetDatabase()->LoadPartnerHeaderAndFooter(mId, mpvHeaders, mpvFooters, mpvDeletes, mSiteId);

	while(j < mpvHeaders->size())
	{
		mpvHeaderWithAnnouncements->push_back((char *)NULL);
		j++;
	}
	mIsLoaded = true;
	return;
}

// Write a header to a text file, so that the
// cobrand filter can use it.
void clsPartner::WriteHeaderToFile(PageTypeEnum pType, const char *pDirectory)
{
	char *pFileName;
	FILE *pFile;
	const char *pToWrite;

	pToWrite = GetHeader(pType, (PageTypeEnum)0);

	pFileName = new char [strlen(pDirectory) + 1 + 32]; // Give us some breathing room for the name.
	sprintf(pFileName, "%s/part%03d%dh.htm", pDirectory, mId, (int) pType);

	remove(pFileName);

	if (!pToWrite || !*pToWrite)
		return;

	pFile = fopen(pFileName, "w");
	if (!pFile)
	{
		return; // Couldn't open the file? Bad directory?
	}

	fputs(pToWrite, pFile);
	fclose(pFile);

	delete pFileName;
	return;
}

// Write a footer to a text file, so that the
// cobrand filter can use it.
void clsPartner::WriteFooterToFile(PageTypeEnum pType, const char *pDirectory)
{
	char *pFileName;
	FILE *pFile;
	const char *pToWrite;

	pToWrite = GetFooter(pType, (PageTypeEnum)0);

	pFileName = new char [strlen(pDirectory) + 1 + 32]; // Give us some breathing room for the name.
	sprintf(pFileName, "%s/part%03d%df.htm", pDirectory, mId, (int) pType);

	remove(pFileName);

	if (!pToWrite || !*pToWrite)
		return;

	pFile = fopen(pFileName, "w");
	if (!pFile)
	{
		return; // Couldn't open the file? Bad directory?
	}

	fputs(pToWrite, pFile);
	fclose(pFile);

	delete pFileName;
	return;
}

// Get the path for CGI for a particular task.
// We reconstruct the path each time that the server
// designation changes, but leave it alone if it hasn't
// changed.
// nsacco 06/01/99 TODO: needs to handle changing the path as well as the server name
const char *clsPartner::GetCGIPath(PageEnum ePage) 
{ 
	char *pConstruct;
	char *pRet;
	const char *pServer;
	int iCurrentServer;
	int iIncrease, iLen;

	if (ePage >= sNumPageToPageType || ePage < 0)
		ePage = PageUnknown;

	iCurrentServer = CoBrandArray[ePage+1].eCGIServerType;

	// nsacco 06/01/99 changed from 4 to ADMIN_Machine
	if (iCurrentServer == ADMIN_Machine) // Ugly hack to return AdminPath when it's admin.
		return gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetAdminPath();

	if( mCurrentCGIServer == 0 || iCurrentServer != mCurrentCGIServer)
	{
		pRet = mppCGIPath[iCurrentServer];
		if (pRet)
			return pRet;

		pServer = gServerNames[iCurrentServer];		

		// wen 06/03/99
		// TODO - remove check for my machine!!!
		if ((GetSiteId() == SITE_EBAY_MAIN && (GetId() == PARTNER_NONE || GetId() == PARTNER_EBAY))
		|| (strcmp(pServer, "http://nsacco.corp.ebay.com") == 0))
		{
			// keep this for compatibility
			iIncrease = strlen( EBAYCGI );
			iLen = strlen(pServer) + iIncrease + 1;
			pConstruct = new char [iLen];
			memset (pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s/aw-cgi/", pServer);
		}
		else
		{
			// nsacco 06/01/99
			// TODO - finish
			// base path on the site and partner id
			// get the domain

			pServer = clsUtilities::GetDomainToken(GetSiteId(), GetId());

			// increase the length of the new url
			iIncrease = strlen("http://cgix") + strlen("/aw-cgi/");
			iLen = strlen(pServer) + iIncrease + 1;	
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);

			// build the new path
			sprintf(pConstruct, "http://%s%s/aw-cgi/", serverMeanings[iCurrentServer], pServer );
		}

		pRet = mppPicsPath[iCurrentServer] = pConstruct;
		mCurrentPicsServer = iCurrentServer;
		return pRet;
	}		
	else 
		return mppCGIPath[iCurrentServer];
}


// Get the path for HTML for a particular task.
// We reconstruct the path each time that the server
// designation changes, but leave it alone if it hasn't
// changed.
// nsacco 06/01/99 TODO: needs to handle changing the path as well as the server name
const char *clsPartner::GetHTMLPath(PageEnum ePage) 
{ 	
	char *pConstruct;
	char *pRet;
	const char *pServer;
	int iCurrentServer;
	int iIncrease, iLen;
	char *pSiteDir;	// nsacco 06/23/99
	char *pSiteString;		// nsacco 07/12/99
	
	pSiteDir = new char[256];	// nsacco 07/12/99

	if (ePage >= sNumPageToPageType || ePage < 0)
		ePage = PageUnknown;

	iCurrentServer = CoBrandArray[ePage+1].eHTMLServerType;

	if( mCurrentHTMLServer == 0 || iCurrentServer != mCurrentHTMLServer)
	{			
		pRet = mppHTMLPath[iCurrentServer];
		if (pRet)
			return pRet;

		pServer = gServerNames[iCurrentServer];

		// wen 06/03/99
		if (GetSiteId() == SITE_EBAY_MAIN && (GetId() == PARTNER_NONE || GetId() == PARTNER_EBAY))
		{
			// keep this for compatibility
			iIncrease = strlen( EBAYHTML );
			iLen = strlen(pServer) + iIncrease + 1;	
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s/", pServer );
		}
		else
		{
			// nsacco 06/01/99
			// TODO - finish
			// base path on the site and partner id
			// get the domain
			//pServer = clsUtilities::GetDomainToken(GetSiteId(),GetId());

			// increase the length of the new url
			//iIncrease = strlen("http://pages") + strlen("/");
			//iLen = strlen(pServer) + iIncrease + 1;	
			//pConstruct = new char [iLen];
			//memset(pConstruct, '\0', iLen);

			// build the new path
			//sprintf(pConstruct, "http://pages%s/", pServer );

			// nsacco 06/23/99
			// NOTE: until we have separate pages machines we need to use a single machine
			// the dir uses parsed-strings from site and partner 
			// TODO - use GetPathToken
			pSiteString = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->GetCurrentSite()->GetParsedString();
			strcpy(pSiteDir, pSiteString);

			if (strcmp(pSiteDir, "") != 0)
			{
				strcat(pSiteDir, "/");
			}

			// The partner string is NOT used in the html path. Instead,
			// the partner cobranding is added via a filter.

			// increase the length of the url
			iIncrease = strlen( EBAYHTML ) + strlen(pSiteDir);	
			iLen = strlen(pServer) + iIncrease + 1;
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s%s%s", pServer, EBAYHTML, pSiteDir );
		}

		pRet = mppPicsPath[iCurrentServer] = pConstruct;
		mCurrentPicsServer = iCurrentServer;
		return pRet;
	}
	else 
		return mppHTMLPath[iCurrentServer];
}

// Get the path for Pics for a particular task.
// We reconstruct the path each time that the server
// designation changes, but leave it alone if it hasn't
// changed.
// nsacco 06/01/99 TODO: needs to handle changing the path as well as the server name
const char *clsPartner::GetPicsPath(PageEnum ePage) 
{ 	
	char *pConstruct;
	char *pRet;
	const char *pServer;
	int iCurrentServer;
	int iIncrease, iLen;

	if (ePage >= sNumPageToPageType || ePage < 0)
		ePage = PageUnknown;

	iCurrentServer = CoBrandArray[ePage+1].ePicsServerType;

	if( mCurrentPicsServer == 0 || iCurrentServer != mCurrentPicsServer)
	{			
		pRet = mppPicsPath[iCurrentServer];
		if (pRet)
			return pRet;

		pServer = gServerNames[iCurrentServer];

		// wen 06/03/99
		// For now the pic server is not cobranded.
//		if (GetSiteId() == SITE_EBAY_MAIN && (GetId() == PARTNER_NONE || GetId() == PARTNER_EBAY))
		{
			// keep this for compatibility
			iIncrease = strlen( EBAYPICS );
			iLen = strlen(pServer) + iIncrease + 1;	
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s/aw/pics/", pServer );
		}
/*		else
		{
			// nsacco 06/01/99
			// TODO - finish
			// base path on the site and partner id
			// get the domain
			pServer = clsUtilities::GetDomainToken(GetSiteId(),GetId());

			// increase the length of the new url
			iIncrease = strlen("http://pics") + strlen("/aw/pics/");
			iLen = strlen(pServer) + iIncrease + 1;	
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);

			// build the new path
			sprintf(pConstruct, "http://pics%s/aw/pics/", pServer );
		}
*/
		pRet = mppPicsPath[iCurrentServer] = pConstruct;
		mCurrentPicsServer = iCurrentServer;
		return pRet;
		
	}
	else 
		return mppPicsPath[iCurrentServer];
}


// kakiyama 07/19/99
// get search path
const char *clsPartner::GetSearchPath(PageEnum ePage) 
{ 	
	char *pConstruct;
	char *pRet;
	const char *pServer;
	int iCurrentServer;
	int iIncrease, iLen;
	const char *pCountryDir;	// nsacco 06/23/99

	if (ePage >= sNumPageToPageType || ePage < 0)
		ePage = PageUnknown;

	iCurrentServer = CoBrandArray[ePage+1].eSearchServerType;

	if( mCurrentSearchServer == 0 || iCurrentServer != mCurrentSearchServer)
	{			
		pRet = mppSearchPath[iCurrentServer];
		if (pRet)
			return pRet;

		pServer = gServerNames[iCurrentServer];

		// wen 06/03/99
		if (GetSiteId() == SITE_EBAY_MAIN && (GetId() == PARTNER_NONE || GetId() == PARTNER_EBAY))
		{
			// keep this for compatibility
			iIncrease = strlen( EBAYSEARCH );
			iLen = strlen(pServer) + iIncrease + 1;	
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s/", pServer );
		}
		else
		{
			// nsacco 06/01/99
			// TODO - finish
			// base path on the site and partner id
			// get the domain
			//pServer = clsUtilities::GetDomainToken(GetSiteId(),GetId());

			// increase the length of the new url
			//iIncrease = strlen("http://pages") + strlen("/");
			//iLen = strlen(pServer) + iIncrease + 1;	
			//pConstruct = new char [iLen];
			//memset(pConstruct, '\0', iLen);

			// build the new path
			//sprintf(pConstruct, "http://pages%s/", pServer );

			// nsacco 06/23/99
			// NOTE: until we have separate pages machines we need to use a single machine
			// TODO - this should really be based on the site and not country? maybe a html dir
			// needs to be defined for a site?
			pCountryDir = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->GetCurrentCountryDir();
			// increase the length of the url
			iIncrease = strlen( EBAYSEARCH ) + strlen(pCountryDir);	
			iLen = strlen(pServer) + iIncrease + 1;
			pConstruct = new char [iLen];
			memset(pConstruct, '\0', iLen);
			sprintf(pConstruct, "%s%s%s", pServer, EBAYSEARCH, pCountryDir );
		}

		pRet = mppPicsPath[iCurrentServer] = pConstruct;
		mCurrentPicsServer = iCurrentServer;
		return pRet;
	}
	else 
		return mppSearchPath[iCurrentServer];
}


// given a pageType and a secondaryPageType, returns an index into the cache (vectors)
//  of headers/footers (AlexP)
int	clsPartner::GetUniqueIndex(PageTypeEnum pageType, PageTypeEnum secondaryPageType)
{
	int base = (int)PageTypeLast;
	return (base * (int)pageType) + (int)secondaryPageType;
}

clsPartnerAds *clsPartner::GetPartnerAds()
{
	if (mpPartnerAds == NULL)
	{
		mpPartnerAds = new clsPartnerAds(mSiteId, mId);
	}

	return mpPartnerAds;
}

// set parsed string
void clsPartner::SetParsedString(char *pParsedString)
{
		delete [] mpParsedString;
	mpParsedString = NULL;

	if (pParsedString != NULL)
	{
		mpParsedString = new char [strlen(pParsedString) + 1];
		strcpy(mpParsedString, pParsedString);
	}
}

// Get the path for Pics for ads 
const char *clsPartner::GetAdPicsPath() 
{ 	
	// For now, these are always on cayman
	return "http://cayman.ebay.com/aw/ads/";
}
