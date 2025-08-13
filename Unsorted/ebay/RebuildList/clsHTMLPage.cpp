/*	$Id: clsHTMLPage.cpp,v 1.5.390.5 1999/08/10 01:19:48 nsacco Exp $	*/
//
//	File:	clsHTMLPage.cpp
//
//	Class:	clsHTMLPage
//
//	Author:	Wen Wen
//
//	Function:
//			Create a HTML page for a category
//
// Modifications:
//				- 07/07/97	Wen - Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "clsRebuildListApp.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsFileName.h"
#include "clsHTMLPage.h"
#include "clsHTMLPortion.h"
#include "clsItemPortion.h"
#include "clsCategoryPortion.h"
#include "clsCategoryNavigator.h"
#include "clsTimePortion.h"
#include "clsTemplate.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsPageLink.h"
#include "clsFocalLink.h"
#include "clsAdWidget.h"
#include "clsSponsorPortion.h"

#include <stdlib.h>
#include <stdio.h>
#include <time.h>
#include "errno.h"

#define ITEM_PER_PAGE	50

// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *eBayMicrosoftHeader   =
"<body bgcolor=\"#FFFFFF\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
"<tr><td width=\"120\">"
"<a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=permanent&url=http://www.buycomp.com/bc/office97.asp?ad=200004\">"
"<img src=\"http://cayman.ebay.com/aw/ads/microsoft/eBayMS_header_final.gif\" width=\"468\" hspace=\"0\" vspace=\"0\" height=\"78\" "
"alt=\"eBay's PC Systems Sponsored by: Microsoft Office 97.  Click here for Microsoft Office 97.\" border=\"0\"></a></td>"
"</tr></table>"
                        "<p><strong><font size=\"3\"><a "
                        "href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
                        "href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>"
                        "<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
			"Check out what's "
                        "<a href=\"http://pages.ebay.com/buzz.html\">new</a> at eBay!</font>"
			"<br></p>";
*/


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *eBayEggheadHeader   =
"<body bgcolor=\"#FFFFFF\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
"<tr> <td valign=\"middle\"><a href=\"http://www.ebay.com\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
"<td valign=\"middle\"><a href=\"http://www.egghead.com\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/egghead/egghead.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"Business software is sponsored by Egghead\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
"</tr> </table>\n"
                        "<p><strong><font size=\"3\"><a "
                        "href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
                        "href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>"
                        "<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
			"Check out what's "
                        "<a href=\"http://pages.ebay.com/buzz.html\">new</a> at eBay!</font>"
			"<br></p>";
*/


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *eBayEddieBauerHeader   =
"<body bgcolor=\"#FFFFFF\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
"<tr> <td valign=\"middle\"><a href=\"http://www.ebay.com\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
"<td valign=\"middle\"><a href=\"http://www.eddiebauer.com/cgi-bin/eb/enterstore.pl/318_557_582\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/eddiebauer/eddiebauer.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"Clothing is sponsored by Eddie Bauer\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
"</tr> </table>\n"
                        "<p><strong><font size=\"3\"><a "
                        "href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
                        "href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>"
                        "<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
			"Check out what's "
                        "<a href=\"http://pages.ebay.com/buzz.html\">new</a> at eBay!</font>"
			"<br></p>";
*/


// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *eBayOfficeDepotHeader   =
"<body bgcolor=\"#FFFFFF\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
"<tr> <td valign=\"middle\"><a href=\"http://www.ebay.com\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
"<td valign=\"middle\"><a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=officeDepot1&url=http://www.officedepot.com\">\n"
"<img src=\"http://cayman.ebay.com/aw/ads/officedepot/officed.gif\" hspace=\"0\" vspace=\"0\"\n"
"alt=\"Business, Office Category Sponsored by Office Depot\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
"</tr> </table>\n"
                        "<p><strong><font size=\"3\"><a "
                        "href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
                        "href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
                        "href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>"
                        "<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
			"Check out what's "
                        "<a href=\"http://pages.ebay.com/buzz.html\">new</a> at eBay!</font>"
			"<br></p>";
*/


clsHTMLPage::clsHTMLPage(clsCategory*		pCategory,
						 CategoryVector*	pCategories,
						 ListingItemVector*		pItems,
						 ListingItemVector*		pFeaturedItems,
						 ListingItemVector*		pHotItems,
						 TimeCriterion		TimeStamp,
						 clsFileName*		pFileName
						 )
{
	mpCategory   = pCategory;
	mpCategories = pCategories;
	mpItems		 = pItems;
	mpFeaturedItems = pFeaturedItems;
	mpHotItems		= pHotItems;
	mTimeStamp   = TimeStamp;
	mpFileName   = pFileName;

	mpApp = (clsRebuildListApp*) gApp;

	mpHeader			  = NULL;
	mpTrailer			  = NULL;
	mpFocalLink			  = NULL;
	mpHotItemPortion	  = NULL;
	mpFeaturedItemPortion = NULL;
	mpItemPortion		  = NULL;
	mpCategoryPortion	  = NULL;
	mpTimePortion		  = NULL;
	mpPageLink			  = NULL;
	mpSponsorPortion	  = NULL;
	mpCategoryNavigator   = new clsCategoryNavigator* [2];
	memset(mpCategoryNavigator, 0, 2 *sizeof(clsCategoryNavigator*));

	//mpDefHeader = mpApp->GetMarketPlace()->GetRelativeHeader();
	//mpDefFooter = mpApp->GetMarketPlace()->GetRelativeFooter();

	// nsacco 08/05/99
	clsMarketPlace *mpMarketPlace = mpApp->GetMarketPlaces()->GetCurrentMarketPlace();

	// temp for microsoft ad
	if (mpCategory)
	{
		switch (mpCategory->GetId())
 		{
			case 179:
		//	mpDefHeader = eBayMicrosoftHeader;
		// kakiyama 07/19/99
				mpDefHeader = clsIntlResource::GetFResString(-1,
															"<body bgcolor=\"#FFFFFF\">\n"
															"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
															"<tr><td width=\"120\">"
															"<a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=permanent&url=http://www.buycomp.com/bc/office97.asp?ad=200004\">"
															"<img src=\"%{10:GetAdPicsPath}microsoft/eBayMS_header_final.gif\" width=\"468\" hspace=\"0\" vspace=\"0\" height=\"78\" "
															"alt=\"eBay's PC Systems Sponsored by: Microsoft Office 97.  Click here for Microsoft Office 97.\" border=\"0\"></a></td>"
															"</tr></table>"
																					"<p><strong><font size=\"3\"><a "
															//						"href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
																					"href=\"%{1:GetHTMLPath}\">Home</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a "
																					"href=\"%{2:GetHTMLPath}aw/listings/list\">Listings</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a "
																					"href=\"%{3:GetHTMLPath}ps.html\">Buyers</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a "
																					"href=\"%{4:GetHTMLPath}seller-services.html\">Sellers</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a "
																					"href=\"%{5:GetHTMLPath}search.html\">Search</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a "
																					"href=\"%{6:GetHTMLPath}contact.html\">Help</a>&nbsp; <a "
															//						"href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
																					"href=\"%{7:GetCGIPath}eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
															//						"href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>"
																					"href=\"%{8:GetHTMLPath}sitemap.html\">Site Map</a></font></strong></p>"
															//						"<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
															"Check out what's "
															//			"<a href=\"http://pages.ebay.com/buzz.html\">new</a> at eBay!</font>"
																		"<a href=\"%{9:GetHTMLPath}buzz.html\">new</a> at eBay!</font>"
															"<br></p>",
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 1
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 2
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 3
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 4
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 5
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 6
															clsIntlResource::ToString(mpMarketPlace->GetCGIPath()),		// 7
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 8
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 9
															clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),	// 10
															NULL);
				// todo - check listings, cayman, need a homepath?


			break;

			case 184:
		//	mpDefHeader = eBayEggheadHeader;
		// kakiyama 07/19/99
				mpDefHeader = clsIntlResource::GetFResString(-1,
															"<body bgcolor=\"#FFFFFF\">\n"
															"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
															"<tr> <td valign=\"middle\"><a href=\"%{1:GetHTMLPath}\">\n"
															"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
															"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
															"<td valign=\"middle\"><a href=\"http://www.egghead.com\">\n"
															"<img src=\"%{11:GetAdPicsPath}egghead/egghead.gif\" hspace=\"0\" vspace=\"0\"\n"
															"alt=\"Business software is sponsored by Egghead\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
															"</tr> </table>\n"
																					"<p><strong><font size=\"3\"><a "
																					"href=\"%{2:GetHTMLPath}\">Home</a>&nbsp; <a "
																					"href=\"%{3:GetHTMLPath}aw/listings/list\">Listings</a>&nbsp; <a "
																					"href=\"%{4:GetHTMLPath}ps.html\">Buyers</a>&nbsp; <a "
																					"href=\"%{5:GetHTMLPath}seller-services.html\">Sellers</a>&nbsp; <a "
																					"href=\"%{6:GetHTMLPath}search.html\">Search</a>&nbsp; <a "
																					"href=\"%{7:GetHTMLPath}contact.html\">Help</a>&nbsp; <a "
																					"href=\"%{8:GetCGIPath}eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
																					"href=\"%{9:GetHTMLPath}sitemap.html\">Site Map</a></font></strong></p>"
																					"<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
															"Check out what's "
																		"<a href=\"%{10:GetHTMLPath}buzz.html\">new</a> at eBay!</font>"
															"<br></p>",
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 1
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 2
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 3
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 4
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 5
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 6
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 7
															clsIntlResource::ToString(mpMarketPlace->GetCGIPath()),		// 8
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 9
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 10
															clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),	// 11
															NULL);
				// TODO - check listings and cayman above


			break;

			case 302:
		//	mpDefHeader = eBayOfficeDepotHeader;
		// kakiyama 07/19/99
				mpDefHeader = clsIntlResource::GetFResString(-1,
															"<body bgcolor=\"#FFFFFF\">\n"
															"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
															"<tr> <td valign=\"middle\"><a href=\"{%{1:GetHTMLPath}\">\n"
															"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
															"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
															"<td valign=\"middle\"><a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=officeDepot1&url=http://www.officedepot.com\">\n"
															"<img src=\"%{11:GetAdPicsPath}officedepot/officed.gif\" hspace=\"0\" vspace=\"0\"\n"
															"alt=\"Business, Office Category Sponsored by Office Depot\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
															"</tr> </table>\n"
																					"<p><strong><font size=\"3\"><a "
															//						"href=\"http://www.ebay.com\">Home</a>&nbsp; <a "
																					"href=\"%{2:GetHTMLPath}\">Home</a>&nbsp; <a "
																					"href=\"%{3:GetHTMLPath}aw/listings/list\">Listings</a>&nbsp; <a "
																					"href=\"%{4:GetHTMLPath}ps.html\">Buyers</a>&nbsp; <a "
																					"href=\"%{5:GetHTMLPath}seller-services.html\">Sellers</a>&nbsp; <a "
																					"href=\"%{6:GetHTMLPath}search.html\">Search</a>&nbsp; <a "
																					"href=\"%{7:GetHTMLPath}contact.html\">Help</a>&nbsp; <a "
																					"href=\"%{8:GetCGIPath}eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
																					"href=\"%{9:GetHTMLPath}sitemap.html\">Site Map</a></font></strong></p>"
																					"<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
															"Check out what's "
																		"<a href=\"%{10:GetHTMLPath}buzz.html\">new</a> at eBay!</font>"
															"<br></p>",
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 1
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 2
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 3
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 4
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 5
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 6
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 7
															clsIntlResource::ToString(mpMarketPlace->GetCGIPath()),		// 8
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 9
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 10
															clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),	// 11
															NULL);
				// TODO - check listings above
															
			break;

			case 311:
		//	mpDefHeader = eBayEddieBauerHeader;
		// kakiyama 07/19/99
			mpDefHeader = clsIntlResource::GetFResString(-1,

														"<body bgcolor=\"#FFFFFF\">\n"
														"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
														"<tr> <td valign=\"middle\"><a href=\"%{1:GetHTMLPath}\">\n"
														"<img src=\"%{12:GetAdPicsPath}ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
														"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
														"<td valign=\"middle\"><a href=\"http://www.eddiebauer.com/cgi-bin/eb/enterstore.pl/318_557_582\">\n"
														"<img src=\"%{11:GetAdPicsPath}eddiebauer/eddiebauer.gif\" hspace=\"0\" vspace=\"0\"\n"
														"alt=\"Clothing is sponsored by Eddie Bauer\" border=\"0\" width=\"235\" height=\"75\"></a></td>\n"
														"</tr> </table>\n"
																				"<p><strong><font size=\"3\"><a "
																				"href=\"%{2:GetHTMLPath}\">Home</a>&nbsp; <a "
																				"href=\"%{3:GetHTMLPath}aw/listings/list\">Listings</a>&nbsp; <a "
																				"href=\"%{4:GetHTMLPath}ps.html\">Buyers</a>&nbsp; <a "
																				"href=\"%{5:GetHTMLPath}seller-services.html\">Sellers</a>&nbsp; <a "
																				"href=\"%{6:GetHTMLPath}search.html\">Search</a>&nbsp; <a "
																				"href=\"%{7:GetHTMLPath}contact.html\">Help</a>&nbsp; <a "
																				"href=\"%{8:GetCGIPath}eBayISAPI.dll?ViewBoard&amp;name=cafe\">Cafe</a>&nbsp; <a "
																				"href=\"%{9:GetHTMLPath}sitemap.html\">Site Map</a></font></strong></p>"
																				"<p>&nbsp;&nbsp;&nbsp;&nbsp;<font size=\"2\"><font color=\"green\">The Latest Buzz.</font> "
															"Check out what's "
																		"<a href=\"%{10:GetHTMLPath}buzz.html\">new</a> at eBay!</font>"
															"<br></p>",
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 1
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 2
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 3
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 4
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 5
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 6
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 7
															clsIntlResource::ToString(mpMarketPlace->GetCGIPath()),		// 8
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 9
															clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),	// 10
															clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),	// 11
															clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),	// 12
															NULL);
			// TODO - should listings (look for listings) use GetHTMLPATH or GetListingsPath - it looks like listing path doesn't work right

			break;

			default:
			mpDefHeader = mpApp->GetMarketPlace()->GetHeader();
			break;
		}
	}
	else
	{
		mpDefHeader = mpApp->GetMarketPlace()->GetHeader();
	}

	mpDefFooter = mpApp->GetMarketPlace()->GetFooter();
	mpMarketPlaceName = mpApp->GetMarketPlace()->GetCurrentPartnerName();
/*
	switch (mTimeStamp)
	{
	case LISTING:
		mpDefHeading = LIST_HEADING;
		break;
	
	case NEW_TODAY:
		mpDefHeading = NEWTODAY_HEADING;
		break;
	
	case END_TODAY:
		mpDefHeading = ENDTODAY_HEADING;
		break;
	
	case COMPLETED:
		mpDefHeading = COMPLETED_HEADING;
		break;

	case GOING:
		mpDefHeading = GOING_HEADING;
		break;
	}
*/
}

clsHTMLPage::~clsHTMLPage()
{
	int		i;

//	delete mpTemplate;
	delete mpHeader;
	delete mpTrailer;
	delete mpFocalLink;
	delete mpHotItemPortion;
	delete mpFeaturedItemPortion;
	delete mpItemPortion;
	delete mpCategoryPortion;
	delete mpTimePortion;
	delete mpPageLink;
	delete mpSponsorPortion;

	for (i = 0; i < 2; i++)
	{
		delete mpCategoryNavigator[i];
	}
	delete [] mpCategoryNavigator;
}

// create and initialize necessary objects based on the template.
bool clsHTMLPage::Initialize()
{
	if (CreateAndParseTemplate())
	{
		return CreatePortions();
	}

	mpApp->LogMessage("Parse template failed");

	return false;
}

// create and parse the template
bool clsHTMLPage::CreateAndParseTemplate()
{
	const char*	pRefFile;

	// Create the template
	if (!mpCategory)
	{
		pRefFile = mpApp->GetDefTopCategoryTemplate(mTimeStamp);
	}
	else
	{
		if (mpItems)
		{
				pRefFile = mpApp->GetItemTemplate(mpCategory->GetFileRef(), mTimeStamp);
		}
		else
		{
				pRefFile = mpApp->GetCategoryTemplate(mpCategory->GetFileRef(), mTimeStamp);
		}
	}

	mpTemplate = mpApp->GetTemplate(pRefFile);

	return (mpTemplate != NULL);

} 

// create portion objects
bool clsHTMLPage::CreatePortions()
{
	Portion	PagePortion;
	int		CurrentNavigator = 0;

	PagePortion = mpTemplate->GetFirstPortion();
	while (PagePortion != END_PORTION)
	{
		switch (PagePortion)
		{
		case HEADER:
			mpHeader = new clsHTMLPortion(mpTemplate->GetHeaderFileName());
			break;

		case TRAILER:
			mpTrailer = new clsHTMLPortion(mpTemplate->GetTrailerFileName());
			break;

		case FOCAL_LINK:
			if (mpCategory && mpCategory->isAdult())
				break;

			mpFocalLink = new clsFocalLink(mpTemplate->GetFocalLinkFileName());
			break;

		case CATEGORY_NAVIGATOR:
			mpCategoryNavigator[CurrentNavigator] = new clsCategoryNavigator(mpCategory, mTimeStamp, !CurrentNavigator);
			mpCategoryNavigator[CurrentNavigator]->Initialize();
			CurrentNavigator++;
			break;

		case FEATURE_ITEM:
			//if (mpFeaturedItems && mpFeaturedItems->size())
			{
				mpFeaturedItemPortion = new clsItemPortion(mpCategory,
													   mpFeaturedItems,
													   mTimeStamp,
													   FEATURED,
													   ITEM_PER_PAGE);
				mpFeaturedItemPortion->Initialize();
			}
			break;

		case PAGE_LINK:
			if (mpItems && /*mpItems->size() > ITEM_PER_PAGE && */ mpPageLink == NULL)
			{
				mpPageLink = new clsPageLink(mpCategory,
											 mpItems->size(),
											 mTimeStamp,
											 mpFileName,
											 ITEM_PER_PAGE);
			}
			break;

		case CATEGORY:
			if (mpCategories && mpCategories->size())
			{
				mpCategoryPortion = new clsCategoryPortion(mpCategory, mpCategories, mTimeStamp);
				mpCategoryPortion->Initialize();
			}
			break;

		case ITEM_LIST:
			if (mpItems && mpItems->size())
			{
				mpItemPortion = new clsItemPortion(mpCategory,
											   mpItems,
											   mTimeStamp,
											   NORMAL,
											   ITEM_PER_PAGE,
											   HasHotOrFeaturedPortion());
				mpItemPortion->Initialize();
			}
			break;

		case HOT_ITEM:
			if (mpHotItems && mpHotItems->size())
			{
				mpHotItemPortion = new clsItemPortion(mpCategory,
												  mpHotItems,
											      mTimeStamp,
												  HOT,
												  ITEM_PER_PAGE);
				mpHotItemPortion->Initialize();
			}
			break;

		case TIME:
			mpTimePortion = new clsTimePortion();
			break;

		case SPONSOR:
			if (mpCategory)
			{
				mpSponsorPortion = new clsSponsorPortion(mpCategory,
										mpTemplate->GetSponsorFileName());
			}
			break;
		}

		mpTemplate->GetNextPortion(PagePortion);
	}

	return true;
}

// Check where there is featured or hot items
bool clsHTMLPage::HasHotOrFeaturedPortion()
{
	return ((mpTemplate->HasPortion(FEATURE_ITEM) && mpFeaturedItems && mpFeaturedItems->size()) || 
	    (mpTemplate->HasPortion(HOT_ITEM) && mpHotItems && mpHotItems->size()));
}

// generate a HTML page
bool clsHTMLPage::CreatePage()
{
	int			CurrentPrintPage;
	bool		ContinuePrint;
	int			CurrentNavigator;
	Portion		PagePortion;
	char*		pOutFileName;
	ofstream	OutputStream;
	bool		IsFirstPageLink = true;
	char		Msg[256];
	time_t		ExpiringTime;
	struct tm*	pGMTime;
	char		TimeString[30];
	clsAdWidget*	pAdWidget = mpApp->GetMarketPlace()->GetAdWidget();

	double		FileOpenDuration;
	double		FileCloseDuration;
	double		StartOpen;
	double		EndOpen;
	double		StartClose;
	double		EndClose;
	double		StartWrite;
	double		EndWrite;
	double		WriteDuration = 0;
	int		NumberOfFiles = 0;
	
	CurrentPrintPage = 1;

	ContinuePrint = false;

	FileOpenDuration = 0;
	FileCloseDuration = 0;
			
	do
	{
		CurrentNavigator = 0;
		NumberOfFiles++;

		// create output file stream
		pOutFileName = mpFileName->GetName(mpCategory, mTimeStamp, CurrentPrintPage, mpApp->GetBuildDay());
		OutputStream.open(pOutFileName, ios::out /*, filebuf::sh_none*/);

		// detect error
		if (OutputStream.fail())
		{
			sprintf(Msg, "Failed during opening: %s due to error: %d (%d)", pOutFileName, OutputStream.rdstate(), errno);
			mpApp->LogMessage(Msg);
			return false;
		}

		// Print title
		OutputStream << "<html><head><TITLE>"
					 << mpMarketPlaceName
					 << " Listings";
		if (mpCategory != NULL)
		{
			OutputStream << ": "
						 << mpCategory->GetName();
		}
		OutputStream << "</TITLE>\n";

		// Expired the page in one hour and 15 minutes
		// i.e. pages are built every hour and it took at 
		// lease 15 minutes to build
		ExpiringTime = mpApp->GetCreatingTime() + 75*60;
		pGMTime = gmtime(&ExpiringTime);
		strftime(TimeString, sizeof(TimeString), "%a, %d %b %Y %H:%M:%S GMT", pGMTime);
		OutputStream << "<meta http-equiv=\"Expires\" content=\""
					 << TimeString
					 << "\">\n";

		// Set last modified tag
		ExpiringTime = time(0);
		pGMTime = gmtime(&ExpiringTime);
		strftime(TimeString, sizeof(TimeString), "%a, %d %b %Y %H:%M:%S GMT", pGMTime);
		OutputStream << "<meta http-equiv=\"Last-Modified\" content=\""
					 << TimeString
					 << "\">\n"
					 << "</head>\n";
		
		// Print header
		OutputStream << mpDefHeader;

		// AdWidget; no show ad if it MS sponsored
		if (mpCategory && mpCategory->GetId() != 179 &&
				  mpCategory->GetId() != 184 && 
				  mpCategory->GetId() != 302 &&
				  mpCategory->GetId() != 311)
		{
			OutputStream << "<center>\n";
			pAdWidget->SetCategoryId(mpCategory->GetId());
			pAdWidget->EmitHTMLOnTop(&OutputStream);
			OutputStream << "</center>\n";
		}

		// call each portion to print itself
		PagePortion = mpTemplate->GetFirstPortion();
		while (PagePortion != END_PORTION)
		{
			switch (PagePortion)
			{
			case HEADER:
				mpHeader->Print(&OutputStream);
				break;

			case TRAILER:
				mpTrailer->Print(&OutputStream);
				break;

			case FOCAL_LINK:
				if ((mpCategory && mpCategory->isAdult()))
					break;

				mpFocalLink->Print(&OutputStream);
				break;

			case CATEGORY_NAVIGATOR:
				mpCategoryNavigator[CurrentNavigator]->Print(&OutputStream, 
					CurrentNavigator == 0 && CurrentPrintPage == 1 && mpItemPortion);
				CurrentNavigator++;
				break;

			case FEATURE_ITEM:
				if (mpFeaturedItemPortion && CurrentPrintPage == 1)
				{
					mpFeaturedItemPortion->Print(&OutputStream, CurrentPrintPage);
				}

				break;

			case PAGE_LINK:
				if (mpPageLink)
				{
					mpPageLink->Print(&OutputStream, CurrentPrintPage);
				}
				break;

			case CATEGORY:
				if (mpCategoryPortion && CurrentPrintPage == 1)
					mpCategoryPortion->Print(&OutputStream);

				break;

			case ITEM_LIST:
				if (mpItemPortion)
				{
					mpItemPortion->Print(&OutputStream, CurrentPrintPage);
					ContinuePrint = mpItemPortion->MoreItems();
				}
				break;

			case HOT_ITEM:
				if (mpHotItemPortion && CurrentPrintPage == 1)
				{
					mpHotItemPortion->Print(&OutputStream, CurrentPrintPage);
				}
				break;

			case SPONSOR:
				if (mpCategory)
				{					
					OutputStream << "\n<center>\n";
					mpSponsorPortion->Print(&OutputStream);
					OutputStream << "\n</center>\n";
				}
				break;

			case TIME:
				OutputStream << "<p align=left><table cellpadding=2 width=\"100%\">"
					<< "<tr><td valign=top>";

				mpTimePortion->Print(&OutputStream);

				OutputStream << "</td>\n";

// thunderstone
				OutputStream << "<td rowspan=2>&nbsp;&nbsp;</td>\n";
				OutputStream << "<td align=left valign=top rowspan=2>"
//					<< "<FORM ACTION=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html\" METHOD=\"GET\">"
// kakiyama 07/20/99
					<< "<FORM ACTION=\""
					<< mpApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSearchPath()
					<< "texis/ebay/results.html\" METHOD=\"GET\">"
					<< "\n<font size=\"-1\">"
					<< "\n<INPUT TYPE=TEXT NAME=query SIZE=20 MAXLENGTH=100 VALUE=\"\">"
					<< "\n<INPUT TYPE=SUBMIT VALUE=Search>";

				if (mpCategory)
				{
					OutputStream << "<br><input type=checkbox name=category"
						<< (mpCategory->catLevel() - 1)
						<< " value="
				    		 << mpCategory->GetId()
				     		<< ">Search only in <b>";

					if (strlen(mpCategory->GetName1()) > 1)
					{
						OutputStream << mpCategory->GetName1()
									 << " : ";
					}

				    OutputStream << mpCategory->GetName()
				     			 << "</b>\n";
				}

				OutputStream << "<br><input type=checkbox name=srchdesc value=\"y\">"
						<< "Search within titles <strong>and</strong> descriptions";

				OutputStream << "\n</font>"
					<< "\n<INPUT TYPE=HIDDEN NAME=\"maxRecordsReturned\" value=\"300\">" 
					<< "\n<INPUT TYPE=hidden NAME=\"maxRecordsPerPage\" VALUE=\"100\">"
					<< "\n<INPUT TYPE=hidden NAME=\"SortProperty\" VALUE=\"MetaEndSort\">"
					<< "\n</form></td>";
// end thunderstone
				OutputStream << "</tr></table>\n";
				break;

			default:
				cout << "Unknown portion found in template file";
				break;
			}

			mpTemplate->GetNextPortion(PagePortion);
		}

		OutputStream << mpDefFooter;
		OutputStream << "</body></html>";

		// close the file
		OutputStream.close();

		// detect error
		if (OutputStream.fail())
		{
			sprintf(Msg, "Failed during closing: %s due to error: %d (%d)", pOutFileName, OutputStream.rdstate(), errno);
			mpApp->LogMessage(Msg);
			return false;
		}

		// make a copy of a file if need
		time_t Now = time(0);
		Now -= ONE_DAY;
		struct tm*	pNow = localtime(&Now);

		if (mpApp->GetBuildDay() == pNow->tm_mday && mTimeStamp == COMPLETED && CurrentPrintPage == 1)
		{
			char* pNewFileName = new char[strlen(pOutFileName)+1];
			strcpy(pNewFileName, pOutFileName);
			char* p = strrchr(pNewFileName, '/');
			strcpy(p+1, "index.html");

			char* pCmd = new char[strlen(pOutFileName) + strlen(pNewFileName) + 10];
			sprintf(pCmd, "cp %s %s", pOutFileName, pNewFileName);
			
			system(pCmd);

			delete [] pNewFileName;
			delete [] pCmd;
		}

		CurrentPrintPage++;

	} while (ContinuePrint);

	return true;
}

