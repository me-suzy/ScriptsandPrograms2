/*	$Id: clseBayAppAdminSelectCobrandAdSiteShow.cpp,v 1.1.8.1 1999/06/13 21:48:20 wwen Exp $	*/
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
//


#include "ebihdr.h"
#include "clsAds.h"

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif

// to be used in the near future
static const int kAddAction = 0;
static const int kModifyAction = 1;
static const int kDeleteAction = 2;


//
// sort_ad_names
//
//	A private sort routine sort all ads by name
//
static bool sort_ad_names(clsAd *pA, clsAd *pB)
{
	if (strcasecmp(pA->GetName(), pB->GetName()) <= 0)
		return true;

	return false;
}


// Emit site names
void clseBayApp::EmitHTMLSiteNameOptions(const char *pOptionMenuName,
										 vector<clsSite *> *pvSites)
{
	vector<clsSite *>::iterator	i;
	char *						pName;

	if (pOptionMenuName == NULL)
		return;

	// Emit site options
	*mpStream	<<	"<select size=\"1\" name=\""
				<<	pOptionMenuName
				<<	"\">\n";

	*mpStream	<<	"<option value=\"-1\" selected>"
				<<	"--"
				<<	"</option>\n";

	if (pvSites != NULL && !pvSites->empty())
	{
		for (i = pvSites->begin(); i != pvSites->end(); i++)
		{
			if ((*i) == NULL)
				continue;

			pName = (*i)->GetName();
			if (pName == NULL)
				continue;

			*mpStream	<<	"<option value=\""
						<<	(*i)->GetId()
						<<	"\">"
						<<	pName
						<<	"</option>\n";
		}
	}

	*mpStream	<<	"</select>";
}


// Build list box of ad names
void clseBayApp::EmitAdNameList(char *pListName, AdVector *pvAds)
{
	AdVector::iterator	i;
	char *				pAdName = NULL;

	if (pListName == NULL)
		return;

	// Set up option menu for ad names
	*mpStream	<<	"<select size=\"1\" name=\""
				<<	pListName
				<<	"\"> "
				<<	"<option value=\"0\">"
				<<	"--"
				<<	"</option>\n";

	// If we have any cobrand partner ads, sort them
	if (pvAds != NULL && !pvAds->empty())
	{
		sort(pvAds->begin(), pvAds->end(), sort_ad_names);

		// Loop through ads and add to list
		for (i = pvAds->begin(); i != pvAds->end(); i++)
		{
			if ((*i) == NULL)
				continue;

			pAdName = (char *)((*i)->GetName());
			if (pAdName == NULL)
				continue;

			*mpStream	<<	"<option value=\""
						<<	(int)(*i)->GetId()
						<<	"\">"
						<<	pAdName
						<<	"</option>\n";	
		}
	}

	// We're done adding close code					
	*mpStream	<<	"</select>"
				<<	"<br>\n";
}


void clseBayApp::AdminSelectCobrandAdSiteShow(CEBayISAPIExtension *pThis,
											  eBayISAPIAuthEnum authLevel)
{
	vector<clsSite *>			vSites;
	vector<clsSite *>::iterator	i;

	clsAds *					pAds = NULL;
	AdVector					vAds;
	AdVector::iterator			ii;


	// Setup
	SetUp();	
				
	// Title
	EmitHeader("Select Cobrand Ad Site");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Get all the cobrand ads from the database
	pAds = new clsAds;
	pAds->GetAllAds(&vAds);

	if (vAds.empty())
	{
		*mpStream	<<	"<h2>No Cobrand Ads</h2>"
						"Sorry, there are no cobrand ads in the database.  Please"
						"go back and add one or more ads to the database before"
						"attempting to associate an ad with a specific page."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Get a vector of all the sites
	mpMarketPlace->GetSites()->GetAllSites(&vSites);
	if (vSites.empty())
	{
		*mpStream	<<	"<h2>No Sites</h2>"
						"Sorry, there are no eBay sites in the database. Please report this "
						"problem to <a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>Select Banner Ad and eBay Site</h2>\n";

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminSelectCobrandAdPartnerAndPageShow)
				<<	"eBayISAPI.dll?AdminSelectCobrandAdPartnerAndPageShow\">\n";
					
	// Display some information text
	*mpStream	<<	"<p>You can add one or more cobrand partner ads to certain pages. "
					"The page is determined by the site, the partner, and the primary "
					"and secondary page types.  If the page is a category listings page, then "
					"the specific page is also determined by the listings category.<br>\n";

	// Display label for ad name list
	*mpStream	<<	"<p><b>First select a site and a banner ad from the menus below:"
					"</b><br>\n";

	// Prompt for input verification
	*mpStream <<	"<p>"
					"<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\">"
					"<tr>"
					"<td width=\"100\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Site</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td>";

	// Display site names
	EmitHTMLSiteNameOptions("site", &vSites);

	*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" align=\"right\" bgcolor=\"#EEFFEE\" valign=\"top\">"
					"<strong><font size=\"3\">Banner Ad</font></strong><br>"
					"<font size=\"2\" color=\"#006600\">(required)</font>"
					"</td>"
					"<td width=\"300\" align=\"left\">";

	// Display list of existing ad names
	EmitAdNameList("ad", &vAds);

	*mpStream <<	"</td>"
					"</tr>"
					"</table><p>";

	// Give them a link to add new cobrand ads
	*mpStream	<<	"To create a new cobrand banner ad, "
					"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddCobrandAdShow)
				<<	"eBayISAPI.dll?AdminAddCobrandAdShow"
				<<	"\">"
					" click here.</a>\n"
					"<p>";

	// Display submit button
	*mpStream	<<	"<p>Press "
				<<	"<input type=submit value=\"Submit\">"
					" to continue</p>";

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

	for (i = vSites.begin(); i != vSites.end(); i++)
		delete (*i);
	vSites.erase(vSites.begin(), vSites.end());

	for (ii = vAds.begin(); ii != vAds.end(); ii++)
		delete (*ii);
	vAds.erase(vAds.begin(), vAds.end());

	CleanUp();

	return;
}

