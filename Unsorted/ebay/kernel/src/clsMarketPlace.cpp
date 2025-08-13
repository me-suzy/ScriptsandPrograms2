/*	$Id: clsMarketPlace.cpp,v 1.14.2.5.10.4 1999/08/10 01:19:51 nsacco Exp $	*/
//
//	File:		clsMarketPlace.cc
//
// Class:	clsMarketPlace
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a Marketplace
//
// Modifications:
//				- 02/09/97 michael	- Created
//				- 06/18/98 inna     - added CCardEmail
//				- 08/22/98 mila		- added methods for getting pics/ directory
//									  path, relative path, etc...
//				- 04/13/99 mila		- added mpFilters and mpMessages, along with
//									  accessors GetFilters() and GetMessages()
//				- 05/25/99 nsacco	- Cobranding/intl changes. Removed mpPartners from 
//									  constructor and destructor. Removed GetPartners.
//									  Added GetCurrentSite, GetCurrentSiteID, and
//									  GetCurrentPartnerID. Modified GetXXXPath to use
//									  mpCurrentSite
//				- 05/24/99 nsacco	- updated for Canada and Australia
//				- 06/14/99 nsacco	- set TimeZone based on site
//				- 07/19/99 nsacco	- added GetCurrentPartnerName() and modified GetName()
//				- 08/04/99 nsacco	- rewrote GetLoginPrompt() and GetPasswordPrompt().
//
#include "eBayKernel.h"
#include "clsStatistics.h"
#include "clsAnnouncements.h"
#include "clsBulletinBoards.h"
#include "clsAdWidget.h"
#include "clsAdRelated.h"
#include "ostream.h"
#include "clsPartners.h"

#include <stdio.h>
#include <string.h>
#include "clsRegions.h"

#ifdef _MSC_VER
#include <strstrea.h>
#include "clsSynchronize.h"
#endif

#include "clsFilters.h"
#include "clsMessages.h"

const int clsMarketPlace::mMinFeedbackForDutch = 10;
const int clsMarketPlace::mMinUserAgeForDutch = 60;


const IconInfo GiftIconInfo[] =
{		
	{	1, 	"Father",		"gft/dad.gif"		},			
	{	2,	"Rosie Icon",	"rosie_ro.gif"			},
	{	3,	"Anniversary",	"gft/ann.gif"	},
	{	4,	"Baby",			"gft/bab.gif"			},
	{	5,	"Birthday",		"gft/bir.gif"		},
	{	6,	"Christmas",	"gft/chr.gif"	},
	{	7,	"Easter",		"gft/eas.gif"		},
	{	8,	"Graduation",	"gft/gra.gif"	},
	{	9,	"Halloween",	"gft/hal.gif"	},
	{	10,	"Hanukah",		"gft/han.gif"		},
	{	11, "July4th",		"gft/ind.gif"		},
	{	12,	"Mother",		"gft/mom.gif"		},
	{	13,	"Stpatrick",	"gft/pat.gif"	},
	{	14,	"Thanksgiving",	"gft/tha.gif"	},
	{	15,	"Valentine",	"gft/val.gif"	},
	{	16,	"Wedding",		"gft/wed.gif"		}
};

//#define UK_ONLY

// A non-reentrant global to do thread updates.
int gPartnersVersion = 1;

/*
#ifdef UK_ONLY
char *sUKHeader = "<body bgcolor=#EEEEFF>"
				  "<table border=0 cellpadding=0 cellspacing=0 width=100%>"
				  "<tr>"
				  "<td width=120><a href=\"http://uk.ebay.com\"><img "
			      "src=\"http://pics.ebay.com/aw/pics/uk/uk-logo-lower-tb.gif\" width=96 hspace=0 vspace=0 "
				  "height=73 alt=\"eBay logo\" border=0></a></td>"
				  "<td><strong><font size=3><a "
			      "href=\"http://pages.ebay.com/uk/\">Home</a>&nbsp; <a "
				  "href=\"http://listings.uk.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a " 
				  "href=\"http://pages.ebay.com/uk/ps.html\">Buyers</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/seller-services.html\">Sellers</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/search.html\">Search</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/help/help-start.html\">Help</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/newschat.html\">News/Chat</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/sitemap.html\">Site Map</a></font></strong>"
				  "</td>"
				  "</tr>"
				  "<tr>"
                  "<td width=120>&nbsp;</td>"
                  "<td width=480 ALIGN=LEFT><font size=-1>Over 400 new categories!  Check them out in our <A HREF=\"http://listings.uk.ebay.com/aw/listings/overview.html\">category overview</A>!</font></td>"
                  "</tr>"
                  "<tr>"
                  "<td width=120>&nbsp;</td>"
                  "<td><font size=-1>Manage all your transactions in one place -- <A HREF=\"http://pages.ebay.com/aw/uk/myebay.html\">My eBay</A>, the <font color=#009900>best kept secret</font> on eBay.</font></td>"
                  "</tr>"
				  "</table>"
				  "<br>";

char *sUKFooter = "<hr>"
				  "<table border=0 cellpadding=0 cellspacing=0 width=600> "
				  "<tr>"
				  "<td width=120 VALIGN=TOP><a href=\"http://uk.ebay.com\"><img "
				  "src=\"http://pages.ebay.com/aw/pics/uk/uk-logo-lower-tb.gif\" width=96 hspace=0 vspace=0"
				  "height=73 alt=\"eBay logo\" border=0></a></td>"
				  "<td><strong><font size=3><a "
				  "href=\"http://uk.ebay.com\">Home</a>&nbsp; <a "
				  "href=\"http://listings.uk.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/ps.html\">Buyers</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/seller-services.html\">Sellers</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/search.html\">Search</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/help/help-start.html\">Help</a>&nbsp; <a " 
				  "href=\"http://pages.ebay.com/uk/newschat.html\">News/Chat</a>&nbsp; <a "
				  "href=\"http://pages.ebay.com/uk/sitemap.html\">Site Map</a></font></strong>"
				  "</TD>"
				  "</TR>"
				  "<TR>"
				  "<TD COLSPAN=2>"
				  "<font size=2>Thank you for using eBay!</font>"
				  "<P>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2><A HREF=\"http://calculus.ebay.com/aw-cgi/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/uk/registration-show.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/uk/myebay.html\">My eBay</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/uk/safeharbor-index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/uk/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebay.com/aboutebay/index.html\">About eBay</A></FONT></P>"
				  "<address><font size=2>"
				  "Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. "
				  "</font></address>"
				  "<font size=2>All trademarks and brands are the property of their respective owners. "
			      "<BR>Use of this web site constitutes acceptance of the eBay <a href=\"http://pages.ebay.com/aw/uk/user-agreement.html\">User Agreement</A> and <a href=\"http://pages.ebay.com/aw/uk/privacy-policy.html\">Privacy Policy</A>.</FONT><BR>"
				  "<!--auctions, auction, computer, bid, bidding, sale, books, coins, stamps, trading cards, memorabilia, sporting goods, music, dolls, comics, antiques, jewellery -->"
				  "</td>"
			      "</tr>"
				  "</table>"
				  "</body>"
				  "</html>";

#endif
*/

//
// Default Constructor
//
clsMarketPlace::clsMarketPlace() : mpLocations(NULL), mpUserVerificationServices(NULL),mpRegions(NULL)
{
	int i;

	mId		= MARKETPLACE_UNKNOWN;
	mpName	= (const char *)0;
	mpAdWidget = NULL;

	mPartnersVersion = 0;

	for (i = 0; i < MAIL_CLASSES; i++)
		mMailIndex[i] = 0;

	// nsacco 05/25/99
	mePage = PageUnknown;
	mpSites = NULL;

	return;
}

//
// Real constructor
//
clsMarketPlace::clsMarketPlace(MarketPlaceId id, 
							const char *pName,
							const char *pHeader,
							const char *pAboutMeHeader,
							const char *pSecureHeader,
							const char *pFooter,
							const char *pSecureFooter,
							const char *pRelativeHeader,
							const char *pRelativeFooter,
							const char *pHTMLPath,
							const char *pHTMLRelativePath,
							const char *pImagePath,
							const char *pSecureHTMLPath,
							const char *pSecureHTMLRelativePath,
							const char *pCGIPath,
							const char *pCGIRelativePath,
							const char *pPicsPath,
							const char *pPicsRelativePath,
				// kakiyama 07/20/99
							const char *pSearchPath,
							const char *pGalleryListingPath,

							const char *pSSLCGIPath,
							const char *pSSLHTMLPath,
							const char *pSSLImagePath,
							const char *pAdminPath,
							const char *pListingPath,
							const char *pListingRelativePath,
							const char *pMembersPath,
							const char *pLoginPrompt,
							const char *pPasswordPrompt,
							const char *pHomeURL,
							const char *pThankYouText,
							const char *pConfirmEmail,
							const char *pAdminEmail,
							const char *pSupportEmail,
							const char *pBillingEmail,
							const char *pRegistrationEmail,
							const char *pBillingPolicyText,
							int bidIncrementCount,
							clsMarketPlaceBidIncrement *pIncrements,
							int	insertionFeeCount,
							clsMarketPlaceInsertionFees *pFees,
							double featuredFee,
							double categoryFeaturedFee,
							double boldFee,
							double giftIconFee,
							double galleryFee,
							double galleryFeaturedFee,
							double itemMoveFee,
							int	hotItemCount,
							const char *pSpecialPassword0,
							const char *pSpecialPassword1,
							const char *pAdminSpecialPassword,
							const clsMarketPlaceUserCriteria *pListCriteria,
							const clsMarketPlaceUserCriteria *pFeatureCriteria,
							const clsMarketPlaceUserCriteria *pBidCriteria,
							const char *pCCardEmail,
							const char *pReportInfringingEmail
								)
{
	
	int i;

	mId					= id;
	mpName				= pName;
	mpCategories		= NULL;
	mpItems				= NULL;
	mpUsers				= NULL;
	mpStatistics		= NULL;
	mpAnnouncements		= NULL;
	mpBulletinBoards	= NULL;
	mpNotes				= NULL;
	mpLocations			= NULL;
	mpRegions=NULL;
	mpUserVerificationServices = NULL;
	mpHeader			= pHeader;
	mpAboutMeHeader		= pAboutMeHeader;
	mpSecureHeader		= pSecureHeader;
	mpFooter			= pFooter;
	mpSecureFooter		= pSecureFooter;
	mpRelativeHeader	= pRelativeHeader;
	mpRelativeFooter	= pRelativeFooter;
	mpHTMLPath			= pHTMLPath;
	mpHTMLRelativePath	= pHTMLRelativePath;
	mpImagePath         = pImagePath;
	mpSecureHTMLPath	= pSecureHTMLPath;
	mpSecureHTMLRelativePath	= pSecureHTMLRelativePath;
	mpCGIPath			= pCGIPath;
	mpCGIRelativePath	= pCGIRelativePath;
	mpPicsPath			= pPicsPath;
	mpPicsRelativePath	= pPicsRelativePath;
// kakiyama 07/20/99
	mpSearchPath		= pSearchPath;
	mpGalleryListingPath		= pGalleryListingPath;

	mpSSLCGIPath		= pSSLCGIPath;
	mpSSLHTMLPath		= pSSLHTMLPath;
	mpSSLImagePath		= pSSLImagePath;
	mpAdminPath			= pAdminPath;
	mpListingPath		= pListingPath;
	mpListingRelativePath		= pListingRelativePath;
	mpMembersPath		= pMembersPath;
	mpLoginPrompt		= pLoginPrompt;
	mpPasswordPrompt	= pPasswordPrompt;
	mpHomeURL			= pHomeURL;
	mpThankYouText		= pThankYouText;
	mpConfirmEmail		= pConfirmEmail;
	mpAdminEmail		= pAdminEmail;
	mpSupportEmail		= pSupportEmail;

	mpBillingEmail		= pBillingEmail;
	mpRegistrationEmail = pRegistrationEmail;
	mpBillingPolicyText	= pBillingPolicyText;	

	mBidIncrementCount		= bidIncrementCount;
	mpBidIncrements			= pIncrements;

	mInsertionFeeCount		= insertionFeeCount;
	mpInsertionFees			= pFees;

	// These are currently unused.
	// We need to revisit the design and see if we ever
	// really want to pass them into the creation of
	// clsMarketPlace. Will we ever really have different
	// marketplaces?
	mFeaturedFee			= featuredFee;
	mCategoryFeaturedFee	= categoryFeaturedFee;
	mBoldFee				= boldFee;
	mGiftIconFee			= giftIconFee;
	mGalleryFee				= galleryFee;
	mGalleryFeaturedFee		= galleryFeaturedFee;
	mItemMoveFee			= itemMoveFee;
	mHotItemCount			= hotItemCount;

	mpSpecialPassword[0]		= pSpecialPassword0;
	mpSpecialPassword[1]		= pSpecialPassword1;
	mpAdminSpecialPassword	= pAdminSpecialPassword;

	mpListCriteria			= pListCriteria;
	mpFeatureCriteria		= pFeatureCriteria;
	mpBidCriteria			= pBidCriteria;

	mpAdWidget = NULL;
	mpAdRelated = NULL;

	mPartnersVersion = 0;
	
	//inna 
	mpCCardEmail		= pCCardEmail;

	mpReportInfringingEmail		= pReportInfringingEmail;

	mpCountries = NULL;
	mpCurrencies = NULL;

	// for Legal Buddy project
	mpFilters = NULL;
	mpMessages = NULL;


	for (i = 0; i < MAIL_CLASSES; i++)
		mMailIndex[i] = 0;

	// only on NT FOR THE MOMENT
#ifdef _MSC_VER
	// mail control
	mpMailControl = NULL;
#endif
	
// petra	mCurrentTimeZone =  SanFrancisco; // 0

	// nsacco 05/25/99
	mePage = PageUnknown;
	mpSites = NULL;

	return;
}

//
// Destructor
//
clsMarketPlace::~clsMarketPlace()
{
	delete		mpCategories;
	delete		mpItems;
	delete		mpUsers;
	delete		mpStatistics;
	delete		mpAnnouncements;
	delete		mpAdWidget;
	delete		mpAdRelated;
	delete		mpBulletinBoards;
	delete		mpNotes;
	delete		mpCountries;
	// only on NT FOR THE MOMENT
#ifdef _MSC_VER
	delete		mpMailControl;
#endif
	delete		mpCurrencies;
	delete		mpFilters;
	delete		mpMessages;
	delete		mpLocations;
	delete		mpUserVerificationServices;
	delete		mpLogging;
	// nsacco 05/25/99
	delete		mpSites;

	return;
}


//
// GetId
//
MarketPlaceId clsMarketPlace::GetId()
{
	return mId;
}


//
// GetName
//
const char *clsMarketPlace::GetName()
{
	// old
	// return mpName;

	// 07/19/99
	return GetCurrentPartnerName();
}

//
// GetCategories
//
clsCategories *clsMarketPlace::GetCategories()
{
	if (!mpCategories)
		mpCategories = new clsCategories(this);
	return mpCategories;
}

//
// GetItems
//
clsItems *clsMarketPlace::GetItems()
{
	if (!mpItems)
		mpItems	= new clsItems(this);
	return mpItems;
}

//
// GetUsers
//
clsUsers *clsMarketPlace::GetUsers()
{
	if (!mpUsers)
		mpUsers	= new clsUsers(this);
	return mpUsers;
}

//
// GetStatistics
//
clsStatistics* clsMarketPlace::GetStatistics()
{
	if (!mpStatistics)
		mpStatistics = new clsStatistics(this);

	return mpStatistics;
}

//
// GetAdStuff
//
clsAdRelated* clsMarketPlace::GetAdRelated()
{
	if (!mpAdRelated)
		mpAdRelated = new clsAdRelated(this);

	return mpAdRelated;
}

//
// GetAnnouncements
//
clsAnnouncements *clsMarketPlace::GetAnnouncements()
{
	if (!mpAnnouncements)
		mpAnnouncements = new clsAnnouncements(this);

	return mpAnnouncements;
}

//
// GetBulletinBoards
//
clsBulletinBoards *clsMarketPlace::GetBulletinBoards()
{
	if (!mpBulletinBoards)
		mpBulletinBoards = new clsBulletinBoards;

	return mpBulletinBoards;
}

//
// ResetBulletinBoards
//
void clsMarketPlace::ResetBulletinBoards()
{
	delete	mpBulletinBoards;
	mpBulletinBoards	= NULL;
}

//
// GetLocations
//
clsLocations *clsMarketPlace::GetLocations()
{
	if (!mpLocations)
		mpLocations = new clsLocations(this);
	return mpLocations;
}

//
// GetUserVerificationServices
//
clsUserVerificationServices *clsMarketPlace::GetUserVerificationServices()
{
	if (!mpUserVerificationServices)
		mpUserVerificationServices = new clsUserVerificationServices(this);
	return mpUserVerificationServices;
}

//
// GetCountries
//
clsCountries *clsMarketPlace::GetCountries()
{
	if (!mpCountries)
		mpCountries = new clsCountries(this);
		// This will populate the cache, too.

	return mpCountries;
}

// only on NT FOR THE MOMENT
#ifdef _MSC_VER
// GetMailControl
//
clsMailControl *clsMarketPlace::GetMailControl()
{
	if (!mpMailControl)
		mpMailControl = new clsMailControl(this);

	return mpMailControl;
}
#endif

// GetCurrencies
//
clsCurrencies *clsMarketPlace::GetCurrencies()
{
	if (!mpCurrencies)
		mpCurrencies = new clsCurrencies(this);
		// This will populate the cache, too.

	return mpCurrencies;
}

//
// GetFilters
//
clsFilters *clsMarketPlace::GetFilters()
{
	if (!mpFilters)
		mpFilters = new clsFilters(this);
		// This will populate the cache, too.

	return mpFilters;
}

//
// GetMessages
//
clsMessages *clsMarketPlace::GetMessages()
{
	if (!mpMessages)
		mpMessages = new clsMessages(this);
		// This will populate the cache, too.

	return mpMessages;
}

//
// GetHTMLPath
//
const char *clsMarketPlace::GetHTMLPath(PageEnum page /* = PageUnknown */)
{
	const char *pRet;
	clsPartners *pPartners;
	
	// nsacco 05/25/99 use mpSites to get partners
	pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if (pPartners)
	{
		pRet = pPartners->GetCurrentHTMLPath(page);
		if (pRet && *pRet)
			return pRet;
	}
	return mpHTMLPath; 
}

// kakiyama 07/20/99
//
// GetSearchPath
//


const char *clsMarketPlace::GetSearchPath(PageEnum page /* = PageUnknown */)
{
	const char *pRet;
	clsPartners *pPartners;
	
	// nsacco 05/25/99 use mpSites to get partners
	pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if (pPartners)
	{
		pRet = pPartners->GetCurrentSearchPath(page);
		if (pRet && *pRet)
			return pRet;
	}
	return mpSearchPath; 
}


// kakiyama 07/20/99
//
// GetGalleryListingPath
//
const char *clsMarketPlace::GetGalleryListingPath()
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentGalleryListingPath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpGalleryListingPath;
}

// kakiyama 07/20/99
//
// GetGalleryListingNoCobrandPath
//
const char *clsMarketPlace::GetGalleryListingNoCobrandPath()
{
	return mpListingPath;
}


const char *clsMarketPlace::GetHTMLRelativePath()
{
	const char *pRet;
	// nsacco 05/25/99 use mpSites to get partners
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentHTMLRelativePath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpHTMLRelativePath;
}
const char *clsMarketPlace::GetImagePath()
{
	return mpImagePath;
}
const char *clsMarketPlace::GetHTMLNoCobrandPath(PageEnum page /* = PageUnknown */)
{
	return mpHTMLPath;
}
const char *clsMarketPlace::GetHTMLRelativeNoCobrandPath()
{
	return mpHTMLRelativePath;
}

//
// GetCGIPath
//
const char *clsMarketPlace::GetCGIPath(PageEnum page /* = PageUnknown */)
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentCGIPath(page);
		if (pRet && *pRet)
			return pRet;
	}
	return mpCGIPath;
}
const char *clsMarketPlace::GetCGIRelativePath()
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentCGIRelativePath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpCGIRelativePath;
}
const char *clsMarketPlace::GetCGINoCobrandPath(PageEnum page /* = PageUnknown */)
{
	return mpCGIPath;
}
const char *clsMarketPlace::GetCGIRelativeNoCobrandPath()
{
	return mpCGIRelativePath;
}

//
// GetPicsPath
//
const char *clsMarketPlace::GetPicsPath(PageEnum ePage)
{
	const char *pRet;
	
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if (pPartners)
	{
		pRet = pPartners->GetCurrentPicsPath(ePage);
		if (pRet && *pRet)
			return pRet;
	}
	return mpPicsPath;
}

const char *clsMarketPlace::GetPicsRelativePath()
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentPicsRelativePath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpPicsRelativePath;
}

const char *clsMarketPlace::GetPicsNoCobrandPath(PageEnum page /* = PageUnknown */)
{
	return mpPicsPath;
}

const char *clsMarketPlace::GetPicsRelativeNoCobrandPath()
{
	return mpPicsRelativePath;
}


//
// GetAdPicsPath
//
const char *clsMarketPlace::GetAdPicsPath()
{
	const char *pRet;
	
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if (pPartners)
	{
		pRet = pPartners->GetCurrentAdPicsPath();
		if (pRet && *pRet)
			return pRet;
	}

	// this should never happen
	return "http://cayman.ebay.com/aw/ads/";
}


//
// for SSL
//
const char *clsMarketPlace::GetSSLCGIPath(PageEnum page /* = PageUnknown */)
{
	return mpSSLCGIPath;
}

const char *clsMarketPlace::GetSSLHTMLPath(PageEnum page /* = PageUnknown */)
{
	return mpSSLHTMLPath;
}

const char *clsMarketPlace::GetSSLImagePath(PageEnum page /* = PageUnknown */)
{
	return mpSSLImagePath;
}

//
// GetAdminPath
//
const char *clsMarketPlace::GetAdminPath()
{
	return mpAdminPath;
}

//
// GetListingPath
//
const char *clsMarketPlace::GetListingPath()
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentListingPath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpListingPath;
}

const char *clsMarketPlace::GetListingNoCobrandPath()
{
	return mpListingPath;
}

const char *clsMarketPlace::GetListingRelativePath()
{
	const char *pRet;
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentListingRelativePath();
		if (pRet && *pRet)
			return pRet;
	}
	return mpListingRelativePath;
}

const char *clsMarketPlace::GetListingRelativeNoCobrandPath()
{
	return mpListingRelativePath;
}

const char *clsMarketPlace::GetMembersPath()
{
	return mpMembersPath;
}

//
// Get...
//
const char *clsMarketPlace::GetLoginPrompt()
{
	// nsacco 08/04/99
	// rewritten to use dynamically created strings
	// OLD: return mpLoginPrompt;
	strcpy(mDynLoginPrompt,
		 clsIntlResource::GetFResString(-1,
							  "<a href=\""
							  "%{1:GetHTMLPath}"
							  "help/myinfo/userid.html"
							  "\">User ID</a>",
							  clsIntlResource::ToString(GetHTMLPath()),
							  NULL));
	return mDynLoginPrompt;
}

const char *clsMarketPlace::GetPasswordPrompt()
{
	// nsacco 08/04/99
	// rewritten to use dynamically created strings
	// OLD: return mpPasswordPrompt;
	strcpy(mDynPasswordPrompt,
		 clsIntlResource::GetFResString(-1,
							  "<a href=\""
							  "%{1:GetHTMLPath}"
							  "services/registration/reqpass.html"
							  "\">Password</a>",
							  clsIntlResource::ToString(GetHTMLPath()),
							  NULL));
	return mDynPasswordPrompt;

}


const char *clsMarketPlace::GetHomeURL()
{
	return mpHomeURL;
}

const char *clsMarketPlace::GetSecureHTMLPath()
{
	return mpSecureHTMLPath;
}

const char *clsMarketPlace::GetSecureHeader()
{
	return mpSecureHeader;
}

const char *clsMarketPlace::GetSecureFooter()
{
	return mpSecureFooter;
}

const char *clsMarketPlace::GetThankYouText()
{
	return mpThankYouText;
}

const char *clsMarketPlace::GetConfirmEmail()
{
	return mpConfirmEmail;
}

const char *clsMarketPlace::GetAdminEmail()
{
	return mpAdminEmail;
}

const char *clsMarketPlace::GetSupportEmail()
{
	return mpSupportEmail;
}

const char *clsMarketPlace::GetReportInfringingEmail()
{
	return mpReportInfringingEmail;
}

const char *clsMarketPlace::GetBillingEmail()
{
	return mpBillingEmail;
}

const char *clsMarketPlace::GetRegistrationEmail()
{
	return mpRegistrationEmail;
}


const char *clsMarketPlace::GetBillingPolicyText()
{
	return mpBillingPolicyText;
}

//inna 
const char *clsMarketPlace::GetCCardEmail()
{
	return mpCCardEmail;
}

int clsMarketPlace::GetHotItemCount()
{
	return mHotItemCount;
}

// GetSpecialPassword
const char *clsMarketPlace::GetSpecialPassword(int i /*=0*/)
{
	if ((i>=0) && (i<NUM_SPECIAL_PASS))
		return	mpSpecialPassword[i];
	else
		return NULL;
}

// GetAdminSpecialPassword
const char *clsMarketPlace::GetAdminSpecialPassword()
{
	return	mpAdminSpecialPassword;
}

// GetXCriteria
const clsMarketPlaceUserCriteria *clsMarketPlace::GetListCriteria()
{
	return	mpListCriteria;
}

const clsMarketPlaceUserCriteria *clsMarketPlace::GetFeaturedCriteria()
{
	return	mpFeatureCriteria;
}

const clsMarketPlaceUserCriteria *clsMarketPlace::GetBidCriteria()
{
	return	mpBidCriteria;
}

//
// EvaluateUser
//	A common, private method which evaluates a user
//	against criteria
//
bool clsMarketPlace::EvaluateUser(
				const clsMarketPlaceUserCriteria *pCriteria,
				char *pAttemptedAction,
				clsUser *pUser,
				ostream *pStream,
				bool	CheckBalance /*=true*/
								 )
{
	clsFeedback		*pFeedback	= NULL;
	clsAccount		*pAccount	= NULL;

	bool			balanceExceeded				= false;
	bool			pastDueBalanceExceeded		= false;

	// Check User State
	if (pCriteria->mUserStateCriteria)
	{
		if (pCriteria->mMustBeRegistered &&
			!pUser->IsConfirmed())
		{
			if (pStream)
			{
				*pStream <<	"<h2>"
							"Unregistered user or registration not confirmed"
							"</h2>"
							"Unregistered users or users with who have not "
							"completed their registration can not "
						 <<	pAttemptedAction
						 << GetName()
						 << ". Please complete your registration and try again. ";
			}
			return false;
		}

		if (pCriteria->mMustNotBeSuspended &&
			pUser->IsSuspended())
		{
			if (pStream)
			{
				*pStream <<	"<h2>"
							"Registration blocked"
							"</h2>"
							"Users whose registered status is blocked cannot "
						 <<	pAttemptedAction
						 <<	GetName()
						 <<	". Please resolve any outstanding complaints on file "
							"before proceeding.";
			}
			return false;
		}
	}

	// Check feedback
	if (pCriteria->mFeedbackCriteria)
	{
		// DON'T delete the pFeedback object because clsUser will do it
		pFeedback	= pUser->GetFeedback();
		if (pFeedback->GetScore() < pCriteria->mMinimumFeedbackScore)
		{
			if (pStream)
			{
				*pStream <<	"<h2>Feedback rating too low</h2>"
							"Users whose feedback rating is less than "
						 <<	pCriteria->mMinimumFeedbackScore 
						 << " cannot "
						 <<	pAttemptedAction
						 <<	GetName()
						 <<	". "
							"<a href="
							"\""
						 <<	GetHTMLPath()
//						 <<	"feedback-list.plx"
						 << "Feedback.html"
							"\""
							">"
							"Review your feedback rating"
							"</a>"
							" and resolve any outstanding complaints "
							"before proceeding."
							"\n";
			}
			return false;
		}
	}

	if (!pCriteria->mAccountBalanceCriteria &&
		!pCriteria->mAccountPastDueCriteria)
		return true;

	// Check Credit balance. We just CHECK the balance here, but 
	// don't emit any messages. This is because an overbalance 
	// condition can be negated by the user having a credit card
	// on file or good credit.
	//
	// *** NOTE ***
	//	Since balance due is NEGATIVE, the comparision is adjusted
	//	accordingly.
	// *** NOTE ***
	//
	pAccount	= pUser->GetAccount();
	if (pAccount->GetBalance() < pCriteria->mMaximumBalance)
	{
		balanceExceeded	= true;
	}
	else
	{
		balanceExceeded	= false;
	}

	//
	// Check past due balance
	//
	if (pAccount->GetPastDue60Days() < pCriteria->mMaximumPastDueBalance)
	{
		pastDueBalanceExceeded	= true;
	}
	else
	{
		pastDueBalanceExceeded	= false;
	}

	//
	// *** NOTE ***
	// VERY eBay specific. Needs work.
	// *** NOTE ***
	//

	// 
	// First, we check past-due status. Past due can be 
	// overridden by having a credit card on file
	//
	if (pastDueBalanceExceeded)
	{
		if (pUser->HasCreditCardOnFile())
			pastDueBalanceExceeded	= false;
	}

	//
	// Now, check for Balance exceeded, which can be nullified
	// by "Blessed" accounts or having a credit card on file
	//
	if (balanceExceeded)
	{
		if (pUser->HasCreditCardOnFile() ||
			pUser->HasGoodCredit())
		{
			balanceExceeded	= false;
		}
	}

	//
	// Ok, by now, we've modified pastDueBalanceExceeded
	// and/or balanceExceeded. If either are true, warn the
	// user
	if (CheckBalance &&
			(balanceExceeded || pastDueBalanceExceeded)
	   )
	{
		if (pStream)
		{
			*pStream <<	"<font color=red size=+1>"
						"<blink>"
						"<b>"
						"WARNING"
						"</b>"
						"</blink>"
						"</font>"
						"<br>"
						"Your account has exceeded the $10.00 limit!"
						"<p>"
						"Unfortunately, you will not be able to "
					 <<	pAttemptedAction
					 <<	" "
					 <<	" until this is rectified. To ensure uninterrupted service, please place "
					 <<	"a credit card on file through our secure server. Just follow the easy steps "
					 <<	"outlined on the "
					 << "<a href=\""
					 << GetHTMLPath()
					 << "help/basics/n-account.html\">accounts page</a>."
						"<p>"
						"**Note: We currently accept Visa and MasterCard only**"
						"<p>"
						"You may also remit payment via check or money order, but please "
						"remember to estimate your usage and prepay accordingly. Include your email address and account number on "
						"your check please, and never send cash."
						"<p>"
						"Payments may be sent to:"
						"<p>"
						"eBay, Inc"
						"<br>"
						"2005 Hamilton Ave"
						"<br>"
						"Suite 350"
						"<br>"
						"San Jose, CA 95125"
						"<br>"
						"ATTN: Billing"
						"<p>";
		}
		return false;
	}


	// If we made it here, we're golden
	return true;
}


//
// UserCanList
//
bool clsMarketPlace::UserCanList(clsUser *pUser,
								ostream *pStream,
								bool CheckBalance /*=true*/)
{
	return	EvaluateUser(mpListCriteria,
						 " list items ",
						 pUser,
						 pStream,
						 CheckBalance);
}


//
// UserCanFeature
//
bool clsMarketPlace::UserCanFeature(clsUser *pUser,
								ostream *pStream)
{
	return	EvaluateUser(mpFeatureCriteria,
						 " list featured items in ",
						 pUser,
						 pStream);
}

//
// UserCanBid
//
bool clsMarketPlace::UserCanBid(clsUser *pUser,
								ostream *pStream)
{
	return	EvaluateUser(mpBidCriteria,
						 " bid on items listed in ",
						 pUser,
						 pStream);
}

// New Rule As of Feb 2, 1999
// In order for a user to list dutch auctions, they must:
// 1. Have a feedback rating of at least 10 -and-
// 2. Must have been registered on eBay for at least 60 days.
//   -or-
// 1. Have a credit card on file (THIS CHECK HAS BEEN REMOVED)
//
// Note: Didn't use EvaluateUser() here, because it would have taken
//  too long to figure out how to make it do an -or- comparison.
bool clsMarketPlace::UserCanListDutchAuction(clsUser *pUser)
{
	clsFeedback		*pFeedback	= NULL;
	time_t userTime;
	time_t currentTime;
	double userAge;
	int days;

	// If CC on file, then it's ok; skip other checks
//	if (pUser->HasCreditCardOnFile()) return true;

	// Check the age for this user
	userTime	= pUser->GetCreated();
	currentTime = time(0);
	userAge = difftime(currentTime, userTime);
	days = (int)(userAge / (24 * 60 * 60));
	if (days < clsMarketPlace::mMinUserAgeForDutch) return false;

	// Check Feedback score
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	= pUser->GetFeedback();
	if (pFeedback->GetScore() < clsMarketPlace::mMinFeedbackForDutch) return false;

	return true;
}



// UserIdRecentlyChanged
bool clsMarketPlace::UserIdRecentlyChanged(time_t userId_last_modified)
{
	time_t	changeTime;
	time_t	Now = time(0);
	// changed to 
	int		EmbargoInSecs = EBAY_USERID_EMBARGO_OLD_USERID_DAYS * SECS_PER_DAY;

	changeTime = userId_last_modified;

	// either 0 or null value from the database
	if ((changeTime == 0) || (changeTime == -1))
		return false;
	else 
		return difftime(Now, changeTime) < EmbargoInSecs;

}

// CanUserChangeUserId
bool clsMarketPlace::CanUserChangeUserId(time_t userId_last_modified)
{
	time_t	changeTime;
	time_t	Now = time(0);
	// changed to 
	int		EmbargoInSecs = EBAY_USERID_CHANGE_DAYS * SECS_PER_DAY;

	changeTime = userId_last_modified;

	// either 0 or null value from the database
	if ((changeTime == 0) || (changeTime == -1))
		return false;
	else 
		return difftime(Now, changeTime) < EmbargoInSecs;

};


//
// Get AdWidget
//
clsAdWidget* clsMarketPlace::GetAdWidget()
{
	if (mpAdWidget == NULL)
	{
		mpAdWidget = new clsAdWidget(this, gApp);
		mpAdWidget->Initialize();
	}

	return mpAdWidget;
}

const char *clsMarketPlace::GetRelativeHeader()
{
//	return sUKHeader;

//	const char *pRet;

	
	PageEnum ePage = GetCurrentPage();
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();
	

	/*if(pPartners)
	{	
		pRet = pPartners->GetCurrentHeader( ePage );
		if (pRet && *pRet)
			return pRet;
	}*/
	return mpRelativeHeader;
}

const char *clsMarketPlace::GetHeader(bool withAnnouncements /*=true*/)
{
//	return sUKHeader;

	
	const char *pRet;
	PageEnum ePage = GetCurrentPage();
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentHeader( ePage, withAnnouncements );
		if (pRet && *pRet)
			return pRet;
	}

	return mpHeader;	

}

const char *clsMarketPlace::GetAboutMeHeader()
{
	return mpAboutMeHeader;
}

const char *clsMarketPlace::GetRelativeFooter()
{
	const char *pRet;
	PageEnum ePage = GetCurrentPage();
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentFooter( ePage );

		// nsacco 06/29/99
		if (pRet && *pRet)
			return pRet;
	}

	// no footer was found so return default footer
	return mpRelativeFooter;	
}

const char *clsMarketPlace::GetFooter(bool getAds)
{
	const char *pRet;
	PageEnum ePage = GetCurrentPage();
	// nsacco 05/25/99 
	clsPartners *pPartners = GetSites()->GetCurrentSite()->GetPartners();

	if(pPartners)
	{	
		pRet = pPartners->GetCurrentFooter( ePage, getAds );
	
		if (pRet && *pRet)
			return pRet;
	}

	// no footer was found so return default footer
	return mpFooter;
}

//
// GetNotes
//
clsNotes *clsMarketPlace::GetNotes()
{
	if (!mpNotes)
	{
		mpNotes	= new clsNotes();
	}
	else
	{
		mpNotes->Reset();
	}

	return mpNotes;
}

double clsMarketPlace::GetMaxAmount(int currencyId)
{
	switch (currencyId)
	{
	case Currency_GBP:
		return EBAY_MAX_POUND_AMOUNT;

	// PH added 04/26/99
	case Currency_DEM:
		return EBAY_MAX_DEM_AMOUNT;

	// nsacco 05/24/99 Canada and Australia
	case Currency_CAD:
		return EBAY_MAX_CAD_AMOUNT;

	case Currency_AUD:
		return EBAY_MAX_AUD_AMOUNT;

	// nsacco 07/13/99 more currencies
	case Currency_FRF:
		return EBAY_MAX_FRF_AMOUNT;

	case Currency_JPY:
		return EBAY_MAX_JPY_AMOUNT;

	case Currency_EUR:
		return EBAY_MAX_EUR_AMOUNT;

	case Currency_SEK:
		return EBAY_MAX_SEK_AMOUNT;

	case Currency_CNY:
		return EBAY_MAX_CNY_AMOUNT;

	case Currency_ESP:
		return EBAY_MAX_ESP_AMOUNT;

	case Currency_NOK:
		return EBAY_MAX_NOK_AMOUNT;

	case Currency_DKK:
		return EBAY_MAX_DKK_AMOUNT;

	case Currency_FIM:
		return EBAY_MAX_FIM_AMOUNT;

	case Currency_USD:
	default:
		return EBAY_MAX_DOLLAR_AMOUNT;
	}
}

int clsMarketPlace::GetMaxAmountSize(int currencyId)
{
	switch (currencyId)
	{
	case Currency_GBP:
		return EBAY_MAX_POUND_SIZE;

	// PH added 04/26/99
	case Currency_DEM:
		return EBAY_MAX_DEM_SIZE;

	// nsacco 05/24/99 Canada and Australia
	case Currency_CAD:
		return EBAY_MAX_CAD_SIZE;

	case Currency_AUD:
		return EBAY_MAX_AUD_SIZE;

	// nsacco 07/13/99 new currencies
	case Currency_FRF:
		return EBAY_MAX_FRF_SIZE;

	case Currency_JPY:
		return EBAY_MAX_JPY_SIZE;

	case Currency_EUR:
		return EBAY_MAX_EUR_SIZE;

	case Currency_SEK:
		return EBAY_MAX_SEK_SIZE;

	case Currency_CNY:
		return EBAY_MAX_CNY_SIZE;

	case Currency_ESP:
		return EBAY_MAX_ESP_SIZE;

	case Currency_NOK:
		return EBAY_MAX_NOK_SIZE;

	case Currency_DKK:
		return EBAY_MAX_DKK_SIZE;

	case Currency_FIM:
		return EBAY_MAX_FIM_SIZE;

	case Currency_USD:
	default:
		return EBAY_MAX_DOLLAR_SIZE;
	}
}


//
// GetGiftIconImage
const char *clsMarketPlace::GetGiftIconImage(int icon)
{
	int i;
	int IconInfoSize = sizeof(GiftIconInfo)/sizeof(IconInfo);
	for(i=0; i<IconInfoSize; i++)
	{
		if (icon == GiftIconInfo[i].IconType)
			return GiftIconInfo[i].IconImage;
	}
	return ""; // error: if no icon type matched.
}

/*
double GetExchangeRate(CurrencyIdEnum fromCurrency, CurrencyIdEnum toCurrency, time_t when)
{
	double rate = 1.0;

	clsExchangeRates *pExchangeRates = NULL;

	// See if we have to recache.
	pExchangeRates = GetExchangeRates();

	if (pExchangeRates)
		rate = pExchangeRates->GetRate(fromCurrency, toCurrency, when);

	return rate;
}
*/

// GetRegions
//
clsRegions* clsMarketPlace::GetRegions()
{
	if (!mpRegions)
	{
		mpRegions = new clsRegions;

		// fill the region with the region info
		mpRegions->Initialize();
	}

	return mpRegions;
}

clsLogging*	clsMarketPlace::GetLogging()
{
	if (!mpLogging)
	{
		mpLogging = new clsLogging();
	}

	return mpLogging;
}

// nsacco 05/25/99 added 
clsSites* clsMarketPlace::GetSites()
{
	if( mpSites == NULL )
	{
		mpSites = new clsSites;
	}
	return	mpSites;
}

// wen 06/09/99
int clsMarketPlace::GetCurrentSiteId()
{
	return GetSites()->GetCurrentSite()->GetId();
}

// wen 06/09/99
int clsMarketPlace::GetCurrentPartnerId()
{
	return GetSites()->GetCurrentSite()->GetPartners()->GetCurrentPartner()->GetId();
}

// nsacco 07/19/99
//
// GetCurrentPartnerName
//
const char *clsMarketPlace::GetCurrentPartnerName()
{
	return GetSites()->GetCurrentSite()->GetPartners()->GetCurrentPartner()->GetName();
}


