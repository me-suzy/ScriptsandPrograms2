/*	$Id: clseBayAppAdminSelectCobrandAdPartnerAndPageShow.cpp,v 1.1.8.1.62.1 1999/08/01 03:01:02 barry Exp $	*/
//
//	File:		clseBayAppAdminAddPageTypeAd.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:	clseBayAppAdminAddPageTypeAd
//
//
//	Modifications:
//				- 05/31/99 mila		- Created
//				- 06/21/99 nsacco	- added siteId when calling ShowCobrandPartners
//


#include "ebihdr.h"
#include "clsAds.h"

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif

static const int kAddAction = 0;
static const int kModifyAction = 1;
static const int kDeleteAction = 2;

static const int kMinPrimaryPageType = PageType0;
static const int kMaxPrimaryPageType = PageType6;

static const int kMinSecondaryPageType = PageType0;
static const int kMaxSecondaryPageType = PageType12;


static const int kPageTypeBrowse = PageType1;

typedef struct
{
	int		mType;
	char *	mName;
} PageTypeInfo;

static PageTypeInfo primaryPageTypes[] =
{
//	{ PageType0,	"Home Page" },
	{ PageType1,	"Browse" },
//	{ PageType2,	"Sell" },
//	{ PageType3,	"Services" },
//	{ PageType4,	"Search" },
//	{ PageType5,	"Help" },
//	{ PageType6,	"Community" }
};

static PageTypeInfo secondaryPageTypes[] =
{
//	{ PageType0,	"Home Page" },
	{ PageType1,	"Category Index" },
	{ PageType2,	"Featured" },
	{ PageType3,	"Hot" },
	{ PageType4,	"Grab Bag" },
	{ PageType5,	"Gift" },
	{ PageType6,	"Big Ticket" },
	{ PageType7,	"All Items" },
	{ PageType8,	"Gallery" },
	{ PageType9,	"View Item" }
};

//
// sort_message_name
//
//	A private sort routine sort all filters by name
//
static bool sort_ad_names(clsPartnerAd *pA, clsPartnerAd *pB)
{
	if (strcasecmp(pA->GetName(), pB->GetName()) <= 0)
		return true;

	return false;
}


// Emit partner names
void clseBayApp::EmitHTMLPartnerNameOptions(const char *pOptionMenuName,
											vector<clsPartner *> *pvPartners)
{
	vector<clsPartner *>::iterator	i;
	char *							pPartnerName = NULL;

	if (pOptionMenuName == NULL)
		return;

	// Emit site options
	*mpStream	<<	"<select size=\"1\" name=\""
				<<	pOptionMenuName
				<<	"\">\n";

	*mpStream	<<	"<option value=\"-1\" selected>"
				<<	"--"
				<<	"</option>\n";

	if (pvPartners != NULL && !pvPartners->empty())
	{
		for (i = pvPartners->begin(); i != pvPartners->end(); i++)
		{
			if (*i == NULL)
				continue;

			pPartnerName = (char *)(*i)->GetName();
			if (pPartnerName == NULL)
				continue;

			*mpStream	<<	"<option value=\""
						<<	(*i)->GetId()
						<<	"\">"
						<<	pPartnerName
						<<	"</option>\n";
		}
	}

	*mpStream	<<	"</select><br>\n";
}


// Emit valid page types
void clseBayApp::EmitHTMLPageTypeOptions(const char *pOptionMenuName, bool primary)
{
	if (pOptionMenuName == NULL)
		return;

	// Setup combo box for min list
	*mpStream	<<	"<select size=\"1\" name=\""
				<<	pOptionMenuName
				<<	"\">\n";

	*mpStream	<<	"<option value=\""
				<<	PageTypeUnknown
				<<	"\" selected>"
				<<	"--"
				<<	"</option>\n";

	if (primary)
	{
		int count = sizeof(primaryPageTypes) / sizeof(PageTypeInfo);
		for (int i = 0; i < count; i++)
		{
			*mpStream	<<	"<option value=\""
						<<	(int)primaryPageTypes[i].mType
						<<	"\">"
						<<	primaryPageTypes[i].mName
						<<	"</option>\n";
		}
	}
	else
	{
		int count = sizeof(secondaryPageTypes) / sizeof(PageTypeInfo);
		for (int i = 0; i < count; i++)
		{
			*mpStream	<<	"<option value=\""
						<<	(int)secondaryPageTypes[i].mType
						<<	"\">"
						<<	secondaryPageTypes[i].mName
						<<	"</option>\n";
		}
	}

	*mpStream	<<	"</select><br>\n";
}


void clseBayApp::AdminSelectCobrandAdPartnerAndPageShow(CEBayISAPIExtension *pThis,
														int adId,
														int siteId,
														eBayISAPIAuthEnum authLevel)
{
	clsSite *				pSite = NULL;
	vector<clsPartner *>	vPartners;

	// Setup
	SetUp();	
				
	// Title
	EmitHeader("Add Cobrand Partner Ad");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	if (siteId < 0)
	{
		*mpStream	<<	"<h2>Site Not Selected</h2>"
						"You did not select a site.  Please go back and select a "
						"site from the site menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (adId <= 0)
	{
		*mpStream	<<	"<h2>Banner Ad Not Selected</h2>"
						"You did not select a banner ad.  Please go back and select a "
						"banner ad from the banner ad menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Get all the partner ads from the database
	pSite = mpMarketPlace->GetSites()->GetSite(siteId);
	if (pSite == NULL)
	{
		*mpStream	<<	"<h2>Invalid Site ID</h2>"
						"Sorry, the site you selected is invalid.  Please "
						"go back and select another site ID."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	pSite->GetPartners()->GetAllPartners(&vPartners);
	if (vPartners.empty())
	{
		*mpStream	<<	"<h2>No Cobrand Partners for Site</h2>"
						"Sorry, there are no cobrand partners for this site.  Please "
						"go back and select a different site, or click <a href=\""
					<<	mpMarketPlace->GetCGIPath()
					<<	"eBayISAPI.dll?ShowCobrandPartners&siteid="	// nsacco 06/21/99
					<<	pSite->GetId()
					<<	"\">here</a> to add a new cobrand partner."
					<<	
						"<p>"
					<<	mpMarketPlace->GetFooter();
		delete pSite;
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>Select Partner and Page for Banner Ad</h2>\n";

	*mpStream	<<	"<p><b>Now select an eBay partner and a primary and secondary page "
					"type for the banner ad:</b><br>\n";

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddCobrandAdToSitePageConfirm)
				<<	"eBayISAPI.dll?AdminAddCobrandAdToSitePageConfirm\">\n";
	
	// Emit hidden fields			
	*mpStream <<	"<input type=hidden name=\"ad\" value=\""
			  <<	adId
			  <<	"\">\n"
					"<input type=hidden name=\"site\" value=\""
			  <<	siteId
			  <<	"\">\n";

	// Prompt for input verification
	*mpStream <<	"<p>"
					"<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">"
					"<tr>"
					"<td width=\"100\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">eBay Cobrand Partner</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td>";

	// Display site names
	EmitHTMLPartnerNameOptions("partner", &vPartners);

	*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Primary Page Type</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td width=\"300\" align=\"left\">";

	// Display all the possible values for primary page type
	EmitHTMLPageTypeOptions("pagetype1", true);

	*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Secondary Page Type</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td width=\"300\" align=\"left\">";

	//Display all the possible values for secondary page type
	EmitHTMLPageTypeOptions("pagetype2", false);
	
	*mpStream <<	"</td>"
					"</tr>"
					"</table><p>";

	// Display submit button
	*mpStream	<<	"<p>Press "
				<<	"<input type=submit value=\"Submit\">"
					" to continue.</p>";

	// Display clear button
	*mpStream	<<	"<P>Press "
					"<input type=\"reset\" value=\"clear form\" name=\"reset\">"
				<<	" to clear the form and start over.</p>\n";

	// End form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

