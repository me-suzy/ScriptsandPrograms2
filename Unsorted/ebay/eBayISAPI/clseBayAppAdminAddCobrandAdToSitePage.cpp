/*	$Id: clseBayAppAdminAddCobrandAdToSitePage.cpp,v 1.1.8.1 1999/06/13 21:48:17 wwen Exp $	*/
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

static const int kListingsPrimaryPageType = PageType1;
static const int kTextListingsSecondaryPageType = PageType7;
static const int kGalleryListingsSecondaryPageType = PageType8;

void clseBayApp::AdminAddCobrandAdToSitePage(CEBayISAPIExtension *pThis,
											 int adId,
											 int siteId,
											 int partnerId,
											 PageTypeEnum primaryPageType,
											 PageTypeEnum secondaryPageType, 
											 int contextSensitiveValue, 
											 eBayISAPIAuthEnum authLevel)
{
	clsCategory *	pCategory = NULL;
	clsSite *		pSite = NULL;
	clsPartner *	pPartner = NULL;
	clsPartnerAds *	pPartnerAds = NULL;
	clsAds			ads;
	clsAd *			pAd = NULL;

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

	// XXX do some error checking.  be sure to check that the pt3 value
	// is valid for the pt1/pt2 combo...
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

	// Is the primary/secondary page type indicate a text or gallery 
	// listings page, check the category.
	if (primaryPageType == kListingsPrimaryPageType)
//	if (primaryPageType == kListingsPrimaryPageType &&
//		(secondaryPageType == kTextListingsSecondaryPageType ||
//		 secondaryPageType == kGalleryListingsSecondaryPageType))
	{
		if (contextSensitiveValue <= 0)
		{
			*mpStream	<<	"<h2>Category Not Selected</h2>"
							"Sorry, you did not select a listings page category.  Please go "
							"back and select a category for the listings page from the "
							"category list box."
							"<p>"
						<<	mpMarketPlace->GetFooter();
			CleanUp();
			return;
		}
		else
		{
			pCategory = mpCategories->GetCategory(contextSensitiveValue, true);
			if (pCategory == NULL)
			{
				// The category is invalid, but the list should contain only valid
				// categories.  This should be reported...
				*mpStream	<<	"<h2>Invalid Category</h2>"
								"Sorry, the category you selected is invalid.  Please "
								"report this problem to <a href=\"mailto:bugs@ebay.com\">"
								"bugs@ebay.com</a>."
								"<p>"
							<<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}

			if (!pCategory->isLeaf())
			{
				*mpStream	<<	"<h2>Non-Leaf Category Selected</h2>"
								"Sorry, you selected a non-leaf category.  Please go "
								"back and select a leaf category for the listings page "
								"from the category list box."
								"<p>"
							<<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}
		}
	}

	// Get the ad with the given ID
	pAd = ads.GetAd(adId);
	if (pAd == NULL)
	{
		*mpStream	<<	"<h2>Invalid Banner Ad</h2>"
						"Sorry, the banner ad you selected is invalid. Please report this "
						"problem to <a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();
		
		CleanUp();
		return;
	}


	// Associate the site/partner/page type combo to this partner ad.
	pSite = mpMarketPlace->GetSites()->GetSite(siteId);
	if (pSite == NULL)
	{
		*mpStream	<<	"<h2>Invalid Site</h2>"
						"Sorry, the eBay site you selected is invalid. Please report this "
						"problem to <a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();

		// Do NOT delete pAd cuz it points into a cache

		CleanUp();
		return;
	}

	pPartner = pSite->GetPartners()->GetPartner(partnerId);
	if (pPartner == NULL)
	{
		*mpStream	<<	"<h2>Cannot Get Cobrand Partner</h2>"
						"Sorry, an error occurred while trying to access the cobrand "
						"partner. Please report this problem to "
						"<a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();

		// Do NOT delete pAd or pSite cuz they point into a cache

		CleanUp();
		return;
	}

	pPartnerAds = pPartner->GetPartnerAds();
	if (pPartnerAds == NULL)
	{
		*mpStream	<<	"<h2>Cannot Get Cobrand Partner Ads</h2>"
						"Sorry, an error occurred while trying to access the cobrand "
						"partner ads. Please report this problem to "
						"<a href=\"mailto:bugs@ebay.com\">bugs@ebay.com</a>."
						"<p>"
					<<	mpMarketPlace->GetFooter();

		// Do NOT delete pAd, pSite, or pPartner cuz they point into a cache

		CleanUp();
		return;
	}
	
	// Store the ad-page association
	pPartnerAds->SetPartnerAdPage(adId,
								  siteId,
								  partnerId,
								  primaryPageType, 
								  secondaryPageType, 
								  contextSensitiveValue);

	// Success message
	*mpStream	<<	"\n"
					"<h2>Banner Ad Added to Site Page</h2>\n"
				<<	"The banner ad will now be displayed on ";
	
	if (primaryPageType == PageType1)
	{
		switch (secondaryPageType)
		{
		case PageType1:
			*mpStream	<<	"category index pages";
			break;

		case PageType2:
			if (contextSensitiveValue > 0)
				*mpStream	<<	"Category ";
			*mpStream	<<	"Featured listings pages";
			break;

		case PageType3:
			*mpStream	<<	"Hot Items listings pages";
			break;

		case PageType4:
			*mpStream	<<	"Grab Bag listings pages";
			break;

		case PageType5:
			*mpStream	<<	"Great Gifts listings pages";
			break;

		case PageType6:
			*mpStream	<<	"Big Ticket Items listings pages";
			break;

		case PageType7:
			*mpStream	<<	"All Items listings pages";
			break;

		case PageType8:
			*mpStream	<<	"Gallery listings pages";
			break;

		default:
			*mpStream	<<	"listings pages";
			break;
		}

	}
	else
	{
		*mpStream	<<	"the selected pages";
	}

	if (secondaryPageType != PageType1 && contextSensitiveValue > 0)
	{
		*mpStream	<<	" in category ";

		if (pCategory != NULL)
			mpCategories->EmitHTMLQualifiedName(mpStream, pCategory);
		else
			*mpStream	<<	contextSensitiveValue
						<<	".\n";

		*mpStream	<<	"\".\n";
	}
	else
	{
		*mpStream	<<	" in the top-level category.";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

