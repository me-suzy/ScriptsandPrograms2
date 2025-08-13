/*	$Id: clsPartner.h,v 1.4.208.1.98.3 1999/08/10 01:19:50 nsacco Exp $	*/
//
// File Name: clsPartner.h
//
// Description:  Header for the clsPartner object
//
// Author:       Chad Musick
//
//			05/25/99	nsacco	-Moved pAnnouncementHeaderPrefix and 
//								Suffix here from clsPartner.cpp. Added
//								methods to return mpvHeaders, 
//								mpvHeaderWithAnnouncements, mpvFooters,
//								and mpvDeletes.
//			08/09/99	nsacco	- Added GetAdPicsPath().
//
#ifndef CLSPARTNER_INCLUDE
#define CLSPARTNER_INCLUDE

#include "vector.h"
#include "eBayTypes.h"

#include "clsPartnerAd.h"
#include "clsPartnerAds.h"

class clsPartner
{
public:
	// nsacco 05/25/99 added siteId
	clsPartner(const char *pName, int id, const char *pDesc, int SiteId=0, const char *pParsedString="");

	~clsPartner();

	const char *GetName() { return mpName; }
	const char *GetDesc() { return mpDesc; }
	// nsacco 05/25/99 changed GetID to GetId for consistency
	int GetId() { return mId; }
	int GetSiteId() { return mSiteId;}
	
	const char *GetHeader(PageTypeEnum pageType, PageTypeEnum secondaryPageType, bool getAds = false);
	const char *GetFooter(PageTypeEnum pageType, PageTypeEnum secondaryPageType, bool getAds = false);

	const char *GetHeaderWithoutAnnouncement(PageTypeEnum pType, PageTypeEnum secondaryPageType, bool getAds = false);

	const char *GetCGIPath(PageEnum ePage); 
	const char *GetHTMLPath(PageEnum ePage); 
	const char *GetPicsPath(PageEnum ePage); 
// kakiyama 07/20/99
	const char *GetSearchPath(PageEnum ePage);
	const char *GetGalleryListingPath() { return mpGalleryListingPath; }
	// nsacco 08/09/99
	const char *GetAdPicsPath();

	const char *GetCGIRelativePath() { return mpCGIRelativePath; }
	const char *GetHTMLRelativePath() { return mpHTMLRelativePath; }
	const char *GetPicsRelativePath() { return mpPicsRelativePath; }
	const char *GetListingPath() { return mpListingPath; }
	const char *GetListingRelativePath() { return mpListingRelativePath; }
	const char *GetMembersPath() { return mpMembersPath; }

	void SetHeader(PageTypeEnum pType, PageTypeEnum secondaryPageType, const char *pNewDescription);
	void SetFooter(PageTypeEnum pType, PageTypeEnum secondaryPageType, const char *pNewDescription);

	void WriteHeaderToFile(PageTypeEnum pType, const char *pDirectory);
	void WriteFooterToFile(PageTypeEnum pType, const char *pDirectory);

	// given a pageType and a secondaryPageType, returns an index into the cache (vectors)
	//  of headers/footers (AlexP)
	static int GetUniqueIndex(PageTypeEnum pageType, PageTypeEnum secondaryPageType);
	
	// nsacco 05/25/99, made public
	void LoadPartner();
	
	// nsacco 05/25/99 accessor for mIsLoaded
	bool IsLoaded() { return mIsLoaded; }

	// nsacco 05/25/99 accessor methods
	vector<char *> *GetHeaders() { return mpvHeaders; }
	vector<char *> *GetHeaderWithAnnouncements() { return mpvHeaderWithAnnouncements; }
	vector<char *> *GetFooters() { return mpvFooters; }
	vector<char *> *GetDeletes() { return mpvDeletes; }

	// kakiyama 06/23/99
	char * GetParsedString() { return mpParsedString; }

	clsPartnerAds *GetPartnerAds();

	// Get all ads for this partner.  (mila)
	void GetAllPartnerAds(PartnerAdVector *pvAds);
	void SetParsedString(char *pParsedString);


private:
	int mId;		// the partner id
	// nsacco 05/25/99 added mSiteId for cobranding/intl
	int mSiteId;	// the site id
	const char *mpName;
	const char *mpDesc;
	char **mppCGIPath;
	char **mppHTMLPath;
	char **mppPicsPath;
// kakiyama 07/20/99
	char **mppSearchPath;
	const char *mpGalleryListingPath;

	const char *mpCGIRelativePath;
	const char *mpHTMLRelativePath;
	const char *mpPicsRelativePath;
	const char *mpListingPath;
	const char *mpListingRelativePath;
	const char *mpMembersPath;

	bool mIsLoaded;	// whether or not the headers/footers have already been loaded

	// The headers/footers themselves.
	//  Note: before the notion of a secondary page type was created, the vectors below
	//	were indexed simply by pageType. When secondary page types were introduced by me, AlexP,
	//  I now index these vectors by a combination of pageType and secondaryPageType, as defined
	//  in GetUniqueIndex().
	vector<char *> *mpvHeaders;
	vector<char *> *mpvHeaderWithAnnouncements;
	vector<char *> *mpvFooters;
	vector<char *> *mpvDeletes;

	int		mCurrentCGIServer;
	int		mCurrentHTMLServer;
	int		mCurrentPicsServer;
	
	clsPartnerAds *mpPartnerAds;
	char *mpParsedString;
// kakiyama 07/20/99
	int		mCurrentSearchServer;

};

// nsacco 05/25/99 moved these statics here
// AlexP 05/14/99
// These two static const char*'s are for making the header announcements
//  appear next to a search box on every eBay page.
// They are used in clsPartner::GetHeader() below.
static const char* pAnnouncementHeaderPrefix =
"<FORM ACTION=\"http://search.ebay.com/cgi-bin/texis/ebayui/results.html\" METHOD=\"GET\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsReturned\" VALUE=\"300\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"maxRecordsPerPage\" VALUE=\"50\">\n"
"	<INPUT TYPE=\"hidden\" NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n"
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
"	<input NAME=\"query\" SIZE=\"12\" MAXLENGTH=\"100\"> <input type=\"Image\" name=\"searchButton\" src=\"http://pics.ebay.com/aw/pics/cat/search-button.gif\" border=\"0\" width=\"60\" height=\"20\" alt=\"Search\"> <font size=\"1\" face=\"arial,helvetica\"><a href=\"http://pages-new.ebay.com/aw/help/topics/tips-search.html\">tips</a></font>\n"
"   <br><font size=\"1\" face=\"Arial,Helvetica\"><input type=checkbox name=srchdesc value=\"y\">Search titles <font color=\"red\">and</font> descriptions</font>"
"	</TD>\n"
"  </TR>\n"
"</TABLE>\n"
"<TABLE border=\"0\" cellspacing=\"0\" width=\"600\" cellpadding=\"0\">\n"
"  <TR>\n"
"		<TD><hr></TD>\n"
"  </TR>\n"
"</TABLE>\n"
"</FORM>\n";
#endif
