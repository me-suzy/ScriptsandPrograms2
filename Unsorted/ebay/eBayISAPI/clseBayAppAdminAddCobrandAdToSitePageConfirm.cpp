/*	$Id: clseBayAppAdminAddCobrandAdToSitePageConfirm.cpp,v 1.1.8.1 1999/06/13 21:48:17 wwen Exp $	*/
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

static const int kAddAction = 0;
static const int kModifyAction = 1;
static const int kDeleteAction = 2;

static const int kPageTypeBrowse		= PageType1;
static const int kPageSubTypeFeatured	= PageType2;
static const int kPageSubTypeHot		= PageType3;
static const int kPageSubTypeGrabBag	= PageType4;
static const int kPageSubTypeAllItems	= PageType7;
static const int kPageSubTypeGallery	= PageType8;

void clseBayApp::AdminAddCobrandAdToSitePageConfirm(CEBayISAPIExtension *pThis,
													int adId,
													int siteId,
													int partnerId,
													PageTypeEnum primaryPageType,
													PageTypeEnum secondaryPageType,
													eBayISAPIAuthEnum authLevel)
{
	CategoryVector		vCategories;

	// Setup
	SetUp();	

	// Title
	EmitHeader("Confirm Page Type for Partner Ad");

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	if (adId <= 0)
	{
		*mpStream	<<	"<h2>Banner Ad Not Selected</h2>"
						"Sorry, you did not select a banner ad.  Please go back and select "
						"a banner ad from the ad dropdown menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (siteId < 0)
	{
		*mpStream	<<	"<h2>eBay Site Not Selected</h2>"
						"Sorry, you did not select a site.  Please go back and select "
						"a site from the site dropdown menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (partnerId < 0)
	{
		*mpStream	<<	"<h2>Cobrand Partner Not Selected</h2>"
						"Sorry, you did not select an eBay cobrand partner.  Please go "
						"back and select a partner from the cobrand partner dropdown "
						"menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (primaryPageType == PageTypeUnknown)
	{
		*mpStream	<<	"<h2>Primary Page Type Not Selected</h2>"
						"Sorry, you did not select a primary page type.  Please go "
						"back and select a primary page type from the primary page "
						"type dropdown menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (secondaryPageType == PageTypeUnknown)
	{
		*mpStream	<<	"<h2>Secondary Page Type Not Selected</h2>"
						"Sorry, you did not select a secondary page type.  Please go "
						"back and select a secondary page type from the secondary page "
						"type dropdown menu."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n"
					"<h2>Confirm Banner Ad Partner and Page Type</h2>\n";

	//Start form
	*mpStream	<<	"<form method=post action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminAddCobrandAdToSitePage)
				<<	"eBayISAPI.dll?AdminAddCobrandAdToSitePage\">\n";
					
	// Emit hidden fields			
	*mpStream <<	"<input type=hidden name=\"ad\" value=\""
			  <<	adId
			  <<	"\">\n"
					"<input type=hidden name=\"site\" value=\""
			  <<	siteId
			  <<	"\">\n"
					"<input type=hidden name=\"partner\" value=\""
			  <<	partnerId
			  <<	"\">\n"
					"<input type=hidden name=\"pagetype1\" value=\""
			  <<	primaryPageType
			  <<	"\">\n"
					"<input type=hidden name=\"pagetype2\" value=\""
			  <<	secondaryPageType
			  <<	"\">\n";

	// Check the page type to see if we need to prompt the user for any context-
	// specific info
	if (primaryPageType == kPageTypeBrowse && 
		(secondaryPageType == kPageSubTypeFeatured || 
		 secondaryPageType == kPageSubTypeHot ||
		 secondaryPageType == kPageSubTypeGrabBag ||
		 secondaryPageType == kPageSubTypeAllItems ||
		 secondaryPageType == kPageSubTypeGallery))
	{
		*mpStream	<<	"The page type you have chosen references a listings page. "
						"<p>If you would like to specify a category for the listings page, "
						"please select a category from the list below.  Otherwise, the "
						"banner ad will appear on the listings page for the top-level "
						"category."
						"<p><b>You may select a category from below (optional):<b>"
						"<p>";

		//Display all the categories and the leafs
		mpCategories->EmitHTMLLeafSelectionList(mpStream,
												"contextvalue", 
												-1,
												"0",
												"--",
												&vCategories, 
												true, 
												true);
	
		vCategories.erase(vCategories.begin(), vCategories.end());
	}

	// Display submit button
	*mpStream	<<	"<p>Press "
				<<	"<input type=\"submit\" value=\"submit\">"
					" to begin displaying the banner ad on the selected page.</p>";

	// End form
	*mpStream	<<	"</form>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;
	CleanUp();

	return;
}

