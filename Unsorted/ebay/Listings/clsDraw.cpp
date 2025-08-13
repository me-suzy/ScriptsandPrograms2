/*	$Id: clsDraw.cpp,v 1.14.2.58.2.4 1999/08/10 01:19:42 nsacco Exp $	*/
//
// File: clsDraw
// 
// Author: Chad Musick (chad@ebay.com)
//
// Description: This file knows how to 'draw' three things:
// Normal listing pages
// A category overview
// An adult intermediate page to warn of possibly explicit content.
// (It also knows, of course, how to draw all the parts of which
// these three things are constructed.)
//
// It is created 1 per partner, per thread.
// Some of the information included in this class comes from static
// and non-re-entrant sources, and that is noted where it happens.
//
// It has dependencies on clsTemplatesMap and clsItemMap.
//
//	Modifications
//		- 07/01/99	nsacco	-use GetPicsPath() for image urls
//

#include "eBayTypes.h"
#include "clsItemMap.h"
#include "clsDraw.h"
#include "clsTemplatesMap.h"
#include <iomanip.h>
#include "clsDailyAd.h"
#include "categoryNumber.h"
#include "clsGalleryChangedItem.h"

#include <Httpext.h>

//
// LOCALS
//

// Categories in which ads should appear.
// Need not be in order, but the largest number must be last.

static int Sponsor[] = {92,	134, 135, 174, 177, 179, 192, 222, 224, 246, 293, 302, 309,
						412, 419, 436, 926, 1049, 1062, 1189};
// An array for effecient lookup of Sponsor
// Initialized in SetupDraw and destroyed in CleanupDraw
static int *sHasAd = NULL;
static int sNumHasAd = 0;

// Our base listings link.
// todo - fix?
static const char *sBaseListingsLink = "/aw/listings/";
static const char *sBaseGalleryListingsLink = "/aw/glistings/";


// Cleans up after SetupDraw.
static void CleanupDraw()
{
	delete [] sHasAd;
	sHasAd = NULL;
}

// A non-reentrant function we call on loading to do the sponsor stuff
// effeciently.
void SetupDraw()
{
	int maxNumber;
	int i, j;

	// Make the array.
	maxNumber = Sponsor[sizeof (Sponsor) / sizeof(int) - 1];
//	sHasAd = new int[maxNumber - 1];
	sHasAd = new int[maxNumber];
//	sHasAd = new int[maxNumber + 1];
	sNumHasAd = maxNumber;

	// Set it all to 0.
	memset(sHasAd, '\0', sizeof (int) * maxNumber);

	// And put in the ones actually used.
	for (i = 0, j = sizeof (Sponsor) / sizeof(int); i < j; ++i)
		sHasAd[Sponsor[i]] = 1;

	// And register to be called at exit.
	atexit(CleanupDraw);
}

// Fixed banners
// nsacco 08/09/99 - moved to where actually used

// Lenox ad
//static const char * sAds92 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.lenox.com/\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/lenox/lenox.gif\" \n"
//"	alt=\"Lenox is sponsored by Lenox\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";

//static const char * sAds174 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.sony.com/factory/\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/sony/sony.gif\" \n"
//"	alt=\"Monitors is sponsored by Sony\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds177 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://athome.compaq.com/store/config.asp?cModel=5600i%2D500/3SBE2&cpqsid=50E520S0QMSH2MSA00G7BLXB932K3586/\">\n"
//"	<img width=300 height=50 border=0 src=\"http://cayman.ebay.com/aw/ads/compaq/cpq_athome_speed.gif\" \n"
//"	alt=\"Notebooks category is sponsored by Compaq\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";

//static const char * sAds179 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://athome.compaq.com/store/config.asp?cModel=5600i%2D500/3SBE2&cpqsid=50E520S0QMSH2MSA00G7BLXB932K3586/\">\n"
//"	<img width=300 height=50 border=0 src=\"http://cayman.ebay.com/aw/ads/compaq/cpq_athome_power.gif\" \n"
//"	alt=\"PC Systems category is sponsored by Compaq\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds192 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\" http://www.kinkos.com/ebay/scan.html\">\n"
//"	<img width=300 height=50 border=0 src=\"http://cayman.ebay.com/aw/ads/kinko/mini_kinkos.gif\" \n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";

//static const char * sAds222 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.krause.com/promo/tsebay\">\n"
//"	<img width=300 height=50 border=0 src=\"http://cayman.ebay.com/aw/ads/krause/ts_hotwheels.gif\" \n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";



//static const char * sAds224 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.hotwheels.com/collectors/\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/hotwheels/hotwheels-banner.gif\" \n"
//"	alt=\"HotWheels is sponsored by HotWheels\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds246 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.krause.com/promo/tsebay\">\n"
//"	<img width=300 height=50 border=0 src=\"http://cayman.ebay.com/aw/ads/krause/ts_actionfig.gif\" \n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds293 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://national.sidewalk.msn.com/\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/sidewalk/sidewalk.gif\" \n"
//"	alt=\"Consumer Electronics is sponsored by Sidewalk.com\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds302 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\" http://www.kinkos.com/ebay/scan.html\">"
//"	<img src=\"http://cayman.ebay.com/aw/ads/kinko/mini_kinkos.gif\" hspace=\"0\" vspace=\"0\" border=\"0\" width=\"300\" height=\"50\"></a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds309 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.warnerbrothers.com/frame_moz_day.html\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/warnerbros/warnerbros.gif\" \n"
//"	alt=\"Videos is sponsored by Warner Brothers\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds436 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"<td valign=\"TOP\"><IMG SRC=\"http://cayman.ebay.com/aw/ads/beanies/sponsor-beanies.gif\"\n"
//"ALT=\"Category is sponsored by Mary Beth's Beanie World\" height=25 width=300 border=0 hspace=0 vspace=0><BR>\n"
//"<A HREF=\"http://www.beanbagworld.net?ebay\">\n"
//"<IMG SRC=\"http://cayman.ebay.com/aw/ads/beanies/beanieworld.gif\"\n"
//"height=50 width=300 hspace=0 vspace=0 border=0 alt=\"Category is sponsored by Mary Beth's Beanie World\"></A>\n"
//"</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds1049 =
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.cdnow.com/from=max:h:eba:smug:ebay1\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/cdnow/cdnow.gif\" \n"
//"	alt=\"CDs is sponsored by CDNow\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


//static const char * sAds1062 = 
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.jcrew.com/\">\n"
//"	<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/jcrew/jcrew1.gif\" \n"
//"	alt=\"Women's Clothing is sponsored by JCrew\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


// Moffet ads for category 419, 1189, 135, 134, 926, and 412
//static const char * sAdsMoffet = 
//"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
//"<tr>\n"
// //"	<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
// //"	</td>\n"
//"	<td align=\"RIGHT\"><a href=\"http://www.moffettairshow.com\">\n"
//"	<img width=468 height=60 border=0 src=\"http://cayman.ebay.com/aw/ads/ebay/mas.gif\" \n"
//"	alt=\"Moffett Air Show\">\n"
//"	</a><br>\n"
//"	</td>\n"
//"</tr>\n"
//"</table>\n";


// mlh 4/13/99 this is old and can be deleted
// Gallery picture urls
//static const char* gGalleryViewByPhotoImage = "http://pics.ebay.com/aw/pics/viewbyphoto.gif";
//static const char* gGalleryViewByTextImage = "http://pics.ebay.com/aw/pics/viewbytext.gif";
//static const char* gGalleryModeImage = "http://pics.ebay.com/aw/pics/nowviewingphoto.gif";
//static const char* gGalleryTextModeImage = "http://pics.ebay.com/aw/pics/nowviewingtext.gif";



/*
static const char * sFootNote =
"<p><font face=\"Arial, Helvetica\" size=\"-1\">Click on a title to get a description and to bid on that item. "
"A <font color=\"ff0000\">red</font> ending time indicates that an auction is ending in less than five hours. "
"These items are not verified by eBay; "
"<a href=\"http://pages.ebay.com/help/community/trustnsfty.html\">caveat emptor.</a>"
" This page is updated regularly; don't forget to use your browser's <strong>reload</strong>"
" button for the latest version. The system may be unavailable during regularly scheduled maintenance, "
"Mondays, 12 a.m. to 4 a.m. Pacific Time (Mondays, 00:01 a.m. to 04:00 a.m., eBay time).</font> <br>";
*/
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char * sFootNote =
"<p><font face=\"Arial, Helvetica\" size=\"-1\">Click on a title to get a description and to bid on that item. "
"A <font color=\"ff0000\">red</font> ending time indicates that an auction is ending in less than five hours. "
"These items are not verified by eBay; "
"<a href=\"http://pages.ebay.com/help/community/trustnsfty.html\">caveat emptor.</a>"
" This page is updated regularly; don't forget to use your browser's <strong>reload</strong>"
" button for the latest version. The system may be unavailable during regularly scheduled maintenance -"
" Please note <b>the new regularly scheduled maintenance time is Fridays, 12 a.m. to 4 a.m. Pacific Time</b>"
" (Fridays, 00:00 a.m. to 04:00 a.m., eBay time).</font> <br>";
*/

// Here are the hardcoded headers for the new UI

// Footer of new UI
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char * sNewUIFooter =
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
	"<TR>\n"
	"	<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
		"	<FONT SIZE=\"2\">\n"
		"	 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
		"	<BR>\n"
		"	 Designated trademarks and brands are the property of their respective owners\n." 
		"	<BR>\n"
		"	 Use of this Web site constitutes acceptance of the eBay\n "
		"	<A HREF=\"http://pages.ebay.com/help/community/png-user.html\">User Agreement</A>\n"
		"	</FONT>\n"
		"	<BR>\n"
		"</TD>\n"
		"<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
			"<FONT SIZE=\"2\">\n"
		"	<A HREF=\"http://pages.ebay.com/help/community/png-privacy.html\"><IMG SRC=\"http://pics.ebay.com/aw/pics/truste_button.gif\" ALIGN=\"center\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
		"	</FONT>\n"
		"</TD>\n"
	"</TR>\n"
"</TABLE>\n"
"<!-- end copyright notice -->\n"
"<!-- footer -->\n"
"</BODY>\n"
"</HTML>";
*/






// New UI with pics --- Stevey
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char * sNewUIHeaderPart1 = 
"<BODY BGCOLOR=\"#FFFFFF\">\n"
"<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
"<TR>\n"
"<TD width=\"150\">\n"
"<a href=\"http://www-new.ebay.com/\"><img \n"
"src=\"http://pics.ebay.com/aw/pics/navbar/ebay_logo_home.gif\" width=\"150\" \n"
"hspace=\"0\" vspace=\"0\" height=\"70\" alt=\"eBay logo\" border=\"0\"><BR>\n"
"</TD>\n"
"<TD width=\"450\" align=\"right\">\n"
"<MAP NAME=\"home_myebay_map\">\n"
"<AREA SHAPE=RECT COORDS=\"280,0,309,12\" \n"
"HREF=\"http://pages.ebay.com/index.html\" ALT=\"Home\">\n"
"<AREA SHAPE=RECT COORDS=\"325,0,370,12\" \n"
"HREF=\"http://pages.ebay.com/services/myebay/myebay.html\" ALT=\"My \n"
"eBay\">\n"
"<AREA SHAPE=RECT COORDS=\"386,0,432,12\" \n"
"HREF=\"http://pages.ebay.com/sitemap.html\" ALT=\"Site Map\">\n"
"<AREA SHAPEÞfault HREF=\"http://pages.ebay.com/\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/home_myebay_map.gif\" width=450 \n"
"height=15  alt=\"Home, My eBay, Site Map\" border=0 usemap=\"#home_myebay_map\" \n"
"align=\"right\"><br clear=\"all\">\n"
"<MAP NAME=\"top_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,1,66,24\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Browse\">\n"
"<AREA SHAPE=RECT COORDS=\"70,1,120,24\" \n"
"HREF=\"http://cgi5.ebay.com/aw-cgi/eBayISAPI.dll?ListItemForSale\" \n"
"ALT=\"Sell\">\n"
"<AREA SHAPE=RECT COORDS=\"124,1,196,24\" \n"
"HREF=\"http://pages.ebay.com/services/index.html\" ALT=\"Services\">\n"
"<AREA SHAPE=RECT COORDS=\"201,1,262,24\" \n"
"HREF=\"http://pages.ebay.com/search/items/search.html\" ALT=\"Search\">\n"
"<AREA SHAPE=RECT COORDS=\"266,1,315,24\" \n"
"HREF=\"http://pages.ebay.com/help/index.html\" ALT=\"Help\">\n"
"<AREA SHAPE=RECT COORDS=\"319,1,414,24\" \n"
"HREF=\"http://pages.ebay.com/community/index.html\" ALT=\"Community\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-top.gif\" width=\"415\" \n"
"height=\"55\" border=\"0\" alt=\"Main Navigation\" usemap=\"#top_nav\"\n"
"align=\"right\"><br clear=\"all\">\n";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char * sNewUITopCatHeaderPart2 = 
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/featured/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/hot/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket/index.html\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";
*/


// Highlight the "Featured" sub menu for top category (category 0)
static const char * sNewUITopFeatureHeaderPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/featured/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/hot/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket/index.html\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

// Highlight the "Hot" sub menu for top category (category 0)
static const char * sNewUITopHotHeaderPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/featured/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/hot/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket/index.html\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Big ticket" sub menu for top category (category 0)
static const char * sNewUITopBigticketHeaderPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/featured/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/hot/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket/index.html\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-bigticket.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

// Highlight the "grabbag" sub menu for top category (category 0)
static const char * sNewUITopGrabbagHeaderPart2 =
// TODO - fix listings-new? 
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/buy/index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/featured/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/hot/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket/index.html\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-grabbag.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";



////////// antiq //////////////////////

// Highlight the "Categories" for level one category (antiq)
static const char * sNewUILevelOneCatHeaderAntiqPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/antiques-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category353/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category353/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (antiq)
static const char * sNewUILevelOneFeatureHeaderAntiqPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/antiques-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category353/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category353/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";




// Highlight the "Hot" sub menu for level one category (antiq)
static const char * sNewUILevelOneHotHeaderAntiqPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/antiques-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category353/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category353/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

/////////////////////// Books //////////////////////////////

// Highlight the "Categories" for level one category (books)
static const char * sNewUILevelOneCatHeaderBooksPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/books-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category266/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category266/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

// Highlight the "Featured" sub menu for level one category (books)
static const char * sNewUILevelOneFeatureHeaderBooksPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/books-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category266/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category266/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (books)
static const char * sNewUILevelOneHotHeaderBooksPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/books-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category266/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category266/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

//////////////////// Coins ////////////////////////////

// Highlight the "Categories" for level one category (Coins)
static const char * sNewUILevelOneCatHeaderCoinsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/coins-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category866/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category866/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Coins)
static const char * sNewUILevelOneFeatureHeaderCoinsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/coins-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category866/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category866/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Coins)
static const char * sNewUILevelOneHotHeaderCoinsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/coins-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category866/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category866/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

//////////////////// Collectible /////////////////////////////

// Highlight the "Categories" for level one category (Collectible)
static const char * sNewUILevelOneCatHeaderCollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/collectibles-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Collectible)
static const char * sNewUILevelOneFeatureHeaderCollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/collectibles-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Collectible)
static const char * sNewUILevelOneHotHeaderCollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/collectibles-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


/////////////////// Computers //////////////////////////

// Highlight the "Categories" for level one category (Computers)
static const char * sNewUILevelOneCatHeaderCompsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/computer-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category160/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category160/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Computers)
static const char * sNewUILevelOneFeatureHeaderCompsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/computer-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category160/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category160/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Computers)
static const char * sNewUILevelOneHotHeaderCompsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/computer-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category160/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category160/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


/////////////////////// Dolls //////////////////////////////////////////////////////

// Highlight the "Categories" for level one category (Dolls)
static const char * sNewUILevelOneCatHeaderDollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/dolls-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category237/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category237/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Dolls)
static const char * sNewUILevelOneFeatureHeaderDollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/dolls-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category237/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category237/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Dolls)
static const char * sNewUILevelOneHotHeaderDollsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/dolls-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category237/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category237/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


/////////////////////// Jewels /////////////////////////

// Highlight the "Categories" for level one category (Jewels)
static const char * sNewUILevelOneCatHeaderJewelsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/jewelry-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category281/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category281/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";

// Highlight the "Featured" sub menu for level one category (Jewels)
static const char * sNewUILevelOneFeatureHeaderJewelsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/jewelry-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category281/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category281/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Jewels)
static const char * sNewUILevelOneHotHeaderJewelsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/jewelry-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category281/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category281/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


///////////////////// Pottery ///////////////////////

// Highlight the "Categories" for level one category (Pots)
static const char * sNewUILevelOneCatHeaderPotsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/pottery-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category870/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category870/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Pots)
static const char * sNewUILevelOneFeatureHeaderPotsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/pottery-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category870/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category870/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Pots)
static const char * sNewUILevelOneHotHeaderPotsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/pottery-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category870/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category870/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


////////////////////////// photos //////////////////////

// Highlight the "Categories" for level one category (photos)
static const char * sNewUILevelOneCatHeaderPhotosPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/photo-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1047/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1947/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (photos)
static const char * sNewUILevelOneFeatureHeaderPhotosPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/photo-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1047/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1947/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (photos)
static const char * sNewUILevelOneHotHeaderPhotosPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/photo-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category1047/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category1047/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


/////////////////// Sports ///////////////////////////////

// Highlight the "Categories" for level one category (Sports)
static const char * sNewUILevelOneCatHeaderSportsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/sports-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category888/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category888/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Sports)
static const char * sNewUILevelOneFeatureHeaderSportsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/sports-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category888/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category888/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Sports)
static const char * sNewUILevelOneHotHeaderSportsPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/sports-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category888/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category888/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


///////////////////// Toys ///////////////////////////////

// Highlight the "Categories" for level one category (Toys)
static const char * sNewUILevelOneCatHeaderToysPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/toys-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category220/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category220/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Toys)
static const char * sNewUILevelOneFeatureHeaderToysPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/toys-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category220/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category220/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Toys)
static const char * sNewUILevelOneHotHeaderToysPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/toys-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category220/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category220/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


//////////////// Others ///////////////////////

// Highlight the "Categories" for level one category (Others)
static const char * sNewUILevelOneCatHeaderOthersPart2 = 
// TODO - fix listings-new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/misc-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category99/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category99/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-categories.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Featured" sub menu for level one category (Others)
static const char * sNewUILevelOneFeatureHeaderOthersPart2 = 
// TODO - fix listings new?
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/misc-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category99/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category99/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-featured.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


// Highlight the "Hot" sub menu for level one category (Others)
static const char * sNewUILevelOneHotHeaderOthersPart2 = 
// TODO - fix listings-new
"<MAP NAME=\"browse_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
"HREF=\"http://pages.ebay.com/misc-index.html\" ALT=\"Categories\">\n"
"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/featured/category99/index.html\" \n"
"ALT=\"Featured\">\n"
"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/hot/category99/index.html\" \n"
"ALT=\"Hot\">\n"
"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
"ALT=\"Grab Bag\">\n"
"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
"HREF=\"http://pages.ebay.com/buy/greatgifts/gift-section.html\" \n"
"ALT=\"Great Gifts\">\n"
"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
"HREF=\"http://pages.ebay.com/buy/bigticket\" ALT=\"Big \n"
"Ticket\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-hot.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#browse_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";




// These are new UI headers for Level 2-4 categories
// TODO - fix listings-new?
static const char * sNewUIHigherLevelHeaderItemsPart2 = 

"<MAP NAME=\"listings_nav\">\n"
"<AREA SHAPE=RECT COORDS=\"1,7,56,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/listings/list/category"; 

static const char * sNewUIHigherLevelHeaderItemsPart3 = 

"\" ALT=\"All Items\">\n"
"<AREA SHAPE=RECT COORDS=\"58,7,134,28\" \n"
"HREF=\"http://listings-new.ebay.com/aw/glistings/list/category";

static const char * sNewUIHigherLevelHeaderPart4 = 

"\" ALT=\"Gallery Items\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-all.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#listings_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";


static const char * sNewUIHigherLevelHeaderGalleryPart4 = 

"\" ALT=\"Gallery Items\">\n"
"</MAP>\n"
"<img src=\"http://pics.ebay.com/aw/pics/navbar/browse-gallery.gif\" \n"
"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
"usemap=\"#listings_nav\" align=\"right\">\n"
"</TD>\n"
"</TR>\n"
"</TABLE><br>\n";



/*
      "<!-- Sub nav -->\n"
      "<TD width=\"240\" ALIGN=\"LEFT\">\n"
	"<TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" bgcolor=\"#EFEFEF\">\n"
        "<TR>\n"
          "<TD align=\"center\"><font face=\"Arial,Helvetica\" size=\"2\"><a href=\"http://listings-new.ebay.com/aw/listings/list/category";
		  
static const char * sNewUIHigherLevelHeaderItemsPart3 = 		  
		  "\"><b>All</b></font></a></TD>\n"
          "<TD align=\"center\"><font face=\"Arial,Helvetica\" size=\"1\"><a href=\"http://listings-new.ebay.com/aw/glistings/list/category";


static const char * sNewUIHigherLevelHeaderGalleryPart2 = 

      "<!-- Sub nav -->\n"
      "<TD width=\"240\" ALIGN=\"LEFT\">\n"
	"<TABLE border=\"0\" width=\"100%\" cellspacing=\"0\" bgcolor=\"#EFEFEF\">\n"
        "<TR>\n"
          "<TD align=\"center\"><font face=\"Arial,Helvetica\" size=\"1\"><a href=\"http://listings-new.ebay.com/aw/listings/list/category";
		  
static const char * sNewUIHigherLevelHeaderGalleryPart3 = 		  
		  "\"><b>All</b></font></a></TD>\n"
          "<TD align=\"center\"><font face=\"Arial,Helvetica\" size=\"2\"><a href=\"http://listings-new.ebay.com/aw/glistings/list/category";


		  
static const char * sNewUIHigherLevelHeaderPart4 = 		  
"		  \">Gallery</font></a></TD>\n"
      "</TR>\n"
      "</TABLE>\n"
      "</TD>\n"
    "</TR>\n"
"</TABLE>\n";


static const char * sNewUIHigherLevelHeaderGalleryPart4 = 		  
"		  \"><b>Gallery</b></font></a></TD>\n"
      "</TR>\n"
      "</TABLE>\n"
      "</TD>\n"
    "</TR>\n"
"</TABLE>\n";

*/


//
// CLASS clsDraw
//

//const char* clsDraw::mpImagesURL = "http://echidna.corp.ebay.com/pict/";
const char* clsDraw::mpImagesURL = "http://thumbs.ebay.com/pict/";

// Print only one item for listings
// The 'color' parameter is used to alternate
// the color of the tables in the listings for
// easier reading.
// We print each item in a table because, although
// it takes longer for the page to finish downloading,
// it renders immediately so the perception is of a
// faster load.
void clsDraw::DrawOneEntryItem(itemEntry *pItem, int color)
{
	struct tm*	pEndTime;
	static const char*	BGColor[] = {"#EFEFEF", "#FFFFFF"};

	const char* statusIconTemplate = 
			"<td align=\"CENTER\" width=\"80\">\n"
			"<img height=15 width=76 border=0 alt=\"Status\" "
			"usemap=\"#status_icon_map\""
			"src=\"http://pics.ebay.com/aw/pics/lst/%s.gif\">\n"
			"</td>\n";

	char statusGif[5];
	char finalStatusGIF[1024];		// includes td's and stuff

	// sanity check
	if (pItem->itemNumber <= 0)
		return;

	if (pItem->itemNumber == mCurrentItem)
	{
		// Paint it a special color if they're looking for it.
		// And put an anchor on it.
		*mpStream << "<a name=\"findit\"><table width=\"100%\" cellpadding=4 border=0 cellspacing=0 "
			"bgcolor=\"#ffff00\">\n<tr valign=middle>";
	}
	else
	{
		*mpStream << "<table width=\"100%\" cellpadding=4 border=0 cellspacing=0 bgcolor=\""
				  << BGColor[color % 2] // Here we alternate colors.
				  << "\">\n<tr valign=middle>";
	}

	// Draw Gallery icon if any
	if(NoneGallery != (GalleryTypeEnum)pItem->galleryType)
		statusGif[0]='g';
	else
		statusGif[0]='_';

	// Picture icon if any
	if (pItem->hasPicture)
		statusGif[1]='p';
	else
		statusGif[1]='_';

	// Hot icon if any
	if (pItem->numBids > 30 && !pItem->isReserved)
		statusGif[2]='h';
	else
		statusGif[2]='_';

	// new icon if any (If it started within the last 24 hours, it's new.)
	if ((mTime - pItem->startTime) < (3600 * 24))
		statusGif[3]='n';
	else
		statusGif[3]='_';

	statusGif[4]='\0';

	sprintf(finalStatusGIF, statusIconTemplate, statusGif);

	// Output the status gif
	*mpStream	<<	finalStatusGIF;

	// Print the title with a link to view the item.
	*mpStream << "<td width=\"58%\">\n"
	
			  << "<A HREF=\""
//			  << mpViewItemURL
// kakiyama 08/03/99
			  << mpMarketPlace->GetCGIPath(PageViewItem)
			  << "eBayISAPI.dll?ViewItem&item="
			  << pItem->itemNumber
//			  << "&r="
//			  << pItem->rowId
//			  << "&t="
//			  << mpData->GetTimeGenerated()
			  << "\">";

	if (pItem->isBold)
		*mpStream << "<b>";

	*mpStream << mpData->GetTitle(pItem)
			  << "</a>";

	if (pItem->isBold)
		*mpStream << "</B>";

	/*
	if (pItem->hasPicture)
		*mpStream << mpPicURL;
	*/

	// Lena - gift icon
	if (pItem->whichGift == 1)
//		*mpStream << mpFatherGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  <<  mpMarketPlace->GetHTMLPath()
				  <<  "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  <<  mpMarketPlace->GetPicsPath()
				  <<  "gft/dad.gif\"></A>";

	if (pItem->whichGift == 2 /*Rosie icon*/)
//		*mpStream << mpRosieGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=14 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "rosie_ro.gif\"></A>";
	
	if (pItem->whichGift == 3)
//		*mpStream << mpAnniversaryGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
			      << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/ann.gif\"></A>";
	
	if (pItem->whichGift == 4)
//		*mpStream << mpBabyGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/bab.gif\"></A>";

	if (pItem->whichGift == 5)
//		*mpStream << mpBirthdayGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << "gft/bir.gif\"></A>";

	if (pItem->whichGift == 6)
//		*mpStream << mpChristmasGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/chr.gif\"></A>";

	if (pItem->whichGift == 7)
//		*mpStream << mpEasterGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << gft/eas.gif\"></A>";

	if (pItem->whichGift == 8)
//		*mpStream << mpGraduationGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/gra.gif\"></A>";

	if (pItem->whichGift == 9)
//		*mpStream << mpHalloweenGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/hal.gif\"></A>";

	if (pItem->whichGift == 10)
//		*mpStream << mpHanukahGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/han.gif\"></A>";
	if (pItem->whichGift == 11)
//		*mpStream << mpJuly4thGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/ind.gif\"></A>";

	if (pItem->whichGift == 12)
//		*mpStream << mpMotherGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/mot.gif\"></A>";

	if (pItem->whichGift == 13)
//		*mpStream << mpStpatrickGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << "gft/pat.gif\"></A>"

	if (pItem->whichGift == 14)
//		*mpStream << mpThanksgivingGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/tha.gif\"></A>";

	if (pItem->whichGift == 15)
//		*mpStream << mpValentineGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/val.gif\"></A>";

	if (pItem->whichGift == 16)
//		*mpStream << mpWeddingGiftURL;
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\""
				  << mpMarketPlace->GetPicsPath()
				  << "gft/wed.gif\"></A>";

	// Print high bid. (Current price)
	// setw sets the minumum width,
	// setfill sets the left fill character for extra width,
	// so setw(2) << setfill('0') gives us a guaranteed 2
	// characters starting with 0 if it's less than 10
	// our bids are stored as cents, so we divide by 100 to get
	// the dollars and mod by 100 to get the cents
	*mpStream << "<br></TD>";




	// check which currency is used here
	if (Currency_GBP == (CurrencyIdEnum)pItem->currencyID)
		*mpStream << "<td align=right width=\"12%\"><B>&pound;";
	else
		*mpStream << "<td align=right width=\"12%\"><B>$";


	*mpStream << (pItem->highBid / 100)
			  << '.'
			  << setw(2) << setfill('0') << (pItem->highBid % 100)
			  << "</B></TD>"
				 "<TD ALIGN=CENTER WIDTH=\"6%\">";

    if (pItem->numBids == 0)
        *mpStream << "-";
    else
        *mpStream << pItem->numBids;

    *mpStream << "</TD>";

	pEndTime = localtime(&(pItem->endTime));

	*mpStream << "<TD ALIGN=CENTER WIDTH=\"15%\">";

	// If it ends within 5 hours, it's going, so we print the time in red.
	if ((pItem->endTime - mTime) < (3600 * 5))
		*mpStream << "<font color=\"#FF0000\">";

	// Format and print the date.
	*mpStream << setw(2) << setfill('0') << pEndTime->tm_mon + 1
			  << '/'
			  << setw(2) << setfill('0') << pEndTime->tm_mday
			  << ' '
			  << setw(2) << setfill('0') << pEndTime->tm_hour
			  << ':'
			  << setw(2) << setfill('0') << pEndTime->tm_min;

	// We have to end the red font if we started it.
	if ((pItem->endTime - mTime) < (3600 * 5))
		*mpStream << "</font>";

	*mpStream << "</td></tr></table>\n";
}

// Draws a link to a user's page.
void clsDraw::DrawOneUserPage(userEntry *pPage, int color)
{
#if 0
	static const char*	BGColor[] = {"#EFEFEF", "#FFFFFF"};

	*mpStream << "<table width=\"100%\" cellpadding=4 border=0 cellspacing=0 bgcolor=\""
			  << BGColor[color % 2] // Here we alternate colors.
              << "\">\n<tr valign=middle><td>";

    *mpStream << "<A HREF=\"http://localhost/aw-cgi/eBayISAPI.dll?ViewUserPage&userid="
              << pPage->userNumber
              << "&page="
              << pPage->pageNumber
              << "\">";

    *mpStream << mpData->GetTitle(pPage);

    *mpStream << "</a></td></tr></table>";
#endif
}

// Common routine to draw an entry link to
// a listing page.
// if 'adult' is true, we draw instead an
// intermediate link to tell them that they're
// perverts before sending them to the adult
// stuff.
// type is the listing type (e.g. ending)
// page is the page (we use index.html for page 1)
//
void clsDraw::DrawCategoryLink(int category,
							   int type,
							   int page,
							   bool adult,
							   bool finding,
							   bool gallery,
							   int featureType)
{
/*	
	*mpStream << " category = " << category << "\n"
			<< " mCurrentCategory = " << mCurrentCategory << "\n"
			<< " type = " << type << "\n"
			<< " mCurrentListingType = " << mCurrentListingType << "\n";
*/


//	if (category != mCurrentCategory ||
//		type != mCurrentListingType)
//	{
		// If these are the completed listings, we go directly to Cayman.
		
		if (type == CompletedListingType)
			*mpStream << "<A HREF=\""
			// TODO - cayman
					     "http://cayman.ebay.com/aw/listings/completed/";
		else
		{
			if (type == GalleryListingType)
			{
				*mpStream << "<A HREF=\""
						  << sBaseGalleryListingsLink
						  << mppListingDirectories[type]
						  << '/';
			}
			else
			{
				*mpStream << "<A HREF=\""
						  << mpBaseListingsLink
						  << mppListingDirectories[type]
						  << '/';
			}

			*mpStream << mppFeatureTypes[featureType]
					  << '/';
		}



		if (category)
			*mpStream << "category"
					  << category
					  << '/';
//	}
//	else
//		*mpStream << "<A HREF=\"";

	if (finding)
		*mpStream << "find" << mCurrentItem << ".html#findit";
	else /*if (adult)
		*mpStream << "adult.html";
	else */ if (page > 1)
		*mpStream << "page"
				  << page
				  << ".html";
	else
		*mpStream << "index.html";

	*mpStream << "\">";
}

// Routine to draw only one of the
// links which appear in the nav bar.
// Draws [page] with a link, unless
// it's the current page, which gets
// = page = with no link.
//
// If our number of pages is greater than
// 25 (an arbitrary threshold)
// we print the link in bold if it's
// the current page.
void clsDraw::DrawOnePageLink(int page, bool gallery, int featureType)
{
	if (page == mCurrentPage)
	{
		if (mNumPages >= 25)
			*mpStream << "<B>";

		*mpStream << "&nbsp;&nbsp; = "
			      << page
				  << " = &nbsp;&nbsp;";

		/*
		if (gallery)
			*mpStream << " = P" << page << " = ";
		else
			*mpStream << " = " << page << " = ";
		*/

		if (mNumPages >= 25)
			*mpStream << "</B>";

		return;
	}

	DrawCategoryLink(mCurrentCategory,
		mCurrentListingType, page, false, false, gallery, featureType);

//	if (gallery)
//	{
//		*mpStream << "P"
		*mpStream << page
				  << "</A>\n";
//	}
//	else
//	{
//		*mpStream << "["
//				  << page
//				  << "]</A>\n";
//	}

}

// Draws the bar which contains the page
// navigation links.
// If we have 25 or more pages, we do some
// special logic in not printing every page
// number. If we're less than 25, we just
// print them all.
//
// Always displays current page, 5 on each
// side, and a 'previous' and 'next' category
// link where appropriate.
//
void clsDraw::DrawPageLinksSection(bool gallery, int featureType)
{
	int i;
	int pageGaps = 20;

	if (mNumPages == 1)
		return;

	*mpStream << "<font face=\"Arial, Helvetica\" size=\"-1\"> <center> \n";
	
	*mpStream << "For more items in this category, "
				     "click these pages:<br>\n";

	// First page gets no 'back' link.
	if (mCurrentPage != 1)
	{
		*mpStream << "&nbsp;&nbsp;";

		DrawCategoryLink(mCurrentCategory,
				mCurrentListingType,
				mCurrentPage - 1,
				false,
				false,
				gallery, featureType);

		*mpStream << "(previous page)</A>&nbsp;&nbsp;\n";
	}

	// As explained above. Abritrary limit (the same as above
	// in DrawOnePageLink)
	if (mNumPages < 25)
	{
		for (i = 1; i <= mNumPages; ++i)
		{
			DrawOnePageLink(i, gallery, featureType);
		}
	}
	else
	{
		for (i = 1; i <= mNumPages; ++i)
		{
			if (i == mCurrentPage)
				DrawOnePageLink(i, gallery, featureType);
			else
			{
				// This thing here only prints every pageGaps for really long lists.
				// It always prints the first, last, and current, though,
				// and the five to each side of current.
				// Sometimes, it prints '...'
				if (abs(mCurrentPage - i) <= mPagesLimit ||
					(i % pageGaps == 0) ||
					(i == 1) ||
					(i == mNumPages))
				{
					DrawOnePageLink(i, gallery, featureType);

					// We print the ... if:
					// we're not within the 5 pages either side (first two tests)
					// and we're a multiple of pageGaps, and we're not at the end.
					if ((mCurrentPage - i) > mPagesLimit + 1 ||
						((i - mCurrentPage) >= mPagesLimit &&
						(mNumPages - i) > 1) && (pageGaps - i % pageGaps) > 1)
						*mpStream << " ... ";
				}
			}
		}
	}

	// Don't draw a 'next' link
	// for the last page.
	if (mCurrentPage != mNumPages)
	{
		*mpStream << "&nbsp;&nbsp;";

		DrawCategoryLink(mCurrentCategory,
				mCurrentListingType,
				mCurrentPage + 1,
				false,
				false,
				gallery,
				featureType);

		*mpStream << "(next page)</A>&nbsp;&nbsp;\n";
	}

	*mpStream << "</center></font><p>\n";

	return;
}





void clsDraw::DrawSimplifiedPageLink(bool gallery, int featureType)
{
	int pageGaps = 20;

	*mpStream	<< "<p><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
				<< "<tr><td>\n"
				<< "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"4\" ";

	switch (featureType)
	{
		case hotEntry:
			*mpStream << "color=\"#FF6666\"";
			break;
		case featuredEntry:
			*mpStream << "color=\"#669900\"";
			break;
		default:
			break;
	}
				
	*mpStream	<< ">\n";

	if (mJumpAlreadyDraw)
		*mpStream << "<a name=\"eBayListings\">&nbsp;</a>";
				
	*mpStream	<< "<b>";

	if (hotEntry == (entryTypeEnum)featureType)
	{
		*mpStream << "Hot Items";
	}
	else if (featuredEntry == (entryTypeEnum)featureType)
	{
		*mpStream << "Featured Auctions";
	}
	else
	{
		switch (mCurrentListingType)
		{
		case CurrentListingType: // The normal case.
			//*mpStream	<< "Current Items";
			*mpStream	<< "Items";
			break;
			
		case NewListingType:
			//*mpStream	<< "Items New Today";
			*mpStream	<< "Items";
			break;
			
		case EndingListingType:
			//*mpStream	<< "Items Ending Today";
			*mpStream	<< "Items";
			break;
			
		case GoingListingType:
			//*mpStream	<< "Items Ending Within 5 Hours";
			*mpStream	<< "Items";
			break;
			
		default:
			//*mpStream	<< "Current Items";
			*mpStream	<< "Items";
			break; 
		}
	}

	if (mpCategory->categoryNumber)
        *mpStream << " in "
				  << mpData->GetTitle(mpCategory);

//	*mpStream	<< "mpData->GetTitle(mpCategory)
	*mpStream	<< "</b><br></td>";

	if (mNumPages == 1)
	{
		//*mpStream << "</tr></table><p></p>";
		*mpStream << "</tr></table>";
		return;
	}

	*mpStream	<< "<td align=\"RIGHT\">"; 
	*mpStream	<< "<p align=\"RIGHT\">"; 

	if (mCurrentPage != 1)
	{
		*mpStream
				<< "<font face=\"Arial,Helvetica\" size=\"-1\">"
				<< "<a HREF=\"" 
				<< mpBaseListingsLink
				<< mppListingDirectories[mCurrentListingType]
				<< '/' << mppFeatureTypes[featureType] << '/';

		if (mpCategory->categoryNumber)
			*mpStream
				<< "category" << mCurrentCategory << '/';

			*mpStream
				<< "page" << (mCurrentPage-1) << ".html" << "\">"
				<< "<img SRC=\""
				<< mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				<< "gallery/gallery-arrowleft.gif\" WIDTH=\"6\" HEIGHT=\"7\""
				<< " BORDER=\"0\"></a>&nbsp; "

				<< "<a HREF=\"" 
				<< mpBaseListingsLink
				<< mppListingDirectories[mCurrentListingType]
				<< '/' << mppFeatureTypes[featureType] << '/';

		if (mpCategory->categoryNumber)
			*mpStream
				<< "category" << mCurrentCategory << '/';

			*mpStream
				<< "page" << (mCurrentPage-1) << ".html" << "\">"
				<< "Previous Page</a></font>";
	}


	*mpStream
			<< "<font face=\"Arial,Helvetica\" size=\"-1\">&nbsp;&nbsp; "
			<< "You are on page <b>"
			<< mCurrentPage
			<< "</b> of "
			<< mNumPages
			<< ".&nbsp; &nbsp;</font>";


	if (mCurrentPage != mNumPages)
	{
		*mpStream
				<< "<font face=\"Arial,Helvetica\" size=\"-1\">"
				<< "<a HREF=\"" 
				<< mpBaseListingsLink
				<< mppListingDirectories[mCurrentListingType]
				<< '/' << mppFeatureTypes[featureType] << '/';

		if (mpCategory->categoryNumber)
			*mpStream
				<< "category" << mCurrentCategory << '/';

			*mpStream
				<< "page" << (mCurrentPage+1) << ".html" << "\">"
				<< "Next Page</a> &nbsp; "
			
				<< "<a HREF=\"" 
				<< mpBaseListingsLink
				<< mppListingDirectories[mCurrentListingType]
				<< '/' << mppFeatureTypes[featureType] << '/';

		if (mpCategory->categoryNumber)
			*mpStream
				<< "category" << mCurrentCategory << '/';

			*mpStream
				<< "page" << (mCurrentPage+1) << ".html" << "\">"
				<< "<img SRC=\""
				<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				<<	"gallery/gallery-arrowright.gif\" WIDTH=\"6\" HEIGHT=\"7\""
				<< " BORDER=\"0\"></a></font>&nbsp;&nbsp;<br>";
	}

	//*mpStream << "</td></tr></table><p></p>";
	*mpStream << "</td></tr></table>";

	return;
}

// This function draw the icons of "Gallery", "Picture" and "Hot"
void clsDraw::DrawItemStatusIcons()
{
	*mpStream <<

"<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\">\n"
	"<tr>\n"
		"<td colspan=\"5\">\n"
			// write the listing type, left align
			"<table align=\"LEFT\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
				"<tr>\n"
					"<td align=\"LEFT\">\n"
						"<p><b>";
	
	switch (mCurrentListingType)
	{
	case CurrentListingType: // The normal case.
		*mpStream	<< "Current";
		break;

	case NewListingType:
		*mpStream	<< "New Today";
		break;

	case EndingListingType:
		*mpStream	<< "Ending Today";
		break;

	case GoingListingType:
		*mpStream	<< "Ending Within 5 Hours";
		break;

	default:
		*mpStream	<< "Current";
		break; 
	}
	
	*mpStream <<
		
						"</b>\n"
					"</td>\n"
				"</TR>\n"
			"</table>\n"

			// draw status icon, right align
			"<table align=\"RIGHT\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
				"<tr>\n"
					"<td align=\"LEFT\">\n"
						"<a href=\""
			// "http://pages.ebay.com/help/basics/g-gallery.html\"><img height=15 width=16 border=0 alt=\"Gallery\" src=\"http://pics.ebay.com/aw/pics/lst/gal.gif\"></a> <font size=\"2\">= Gallery </font>\n"
			// kakiyama 08/03/99
				<< mpMarketPlace->GetHTMLPath()
				<< "help/basics/g-gallery.html\"><img height=15 width=16 border=0 alt=\"Gallery\" src=\""
				<< mpMarketPlace->GetPicsPath()
				<< "lst/gal.gif\"></a> <font size=\"2\">= Gallery </font>\n"
						"&nbsp;&nbsp;&nbsp;"
						"<a href=\""			
			//			"<a href=\"http://pages.ebay.com/help/basics/g-pic.html\"><img height=15 width=16 border=0 alt=\"Picture\" src=\"http://pics.ebay.com/aw/pics/lst/pic.gif\"></a> <font size=\"2\">= Picture</font>\n"
			// kakiyama 08/03/99
				<< mpMarketPlace->GetHTMLPath()
				<< "help/basics/g-pic.html\"><img height=15 width=16 border=0 alt=\"Picture\" src=\""
				<< mpMarketPlace->GetPicsPath()
				<< "lst/pic.gif\"></a> <font size=\"2\">= Picture</font>\n"
						"&nbsp;&nbsp;&nbsp;"
						"<a href=\""
			//			"<a href=\"http://pages.ebay.com/help/basics/g-hot-items.html\"><img height=15 width=16 border=0 alt=\"Hot!\" src=\"http://pics.ebay.com/aw/pics/lst/hot.gif\"></a> <font size=\"2\">= Hot!</font>\n"
			// kakiyama 08/03/99
				<< mpMarketPlace->GetHTMLPath()
				<< "help/basics/g-hot-items.html\"><img height=15 width=16 border=0 alt=\"Hot!\" src=\""
				<< mpMarketPlace->GetPicsPath()
				<< "lst/hot.gif\"></a> <font size=\"2\">= Hot!</font>\n"
						"&nbsp;&nbsp;&nbsp;"
						"<a href=\""
			//			"<a href=\"http://pages.ebay.com/help/basics/g-new.html\"><img height=15 width=16 border=0 alt=\"New!\" src=\"http://pics.ebay.com/aw/pics/lst/new.gif\"></a> <font size=\"2\">= New!</font>\n"
			// kakiyama 08/03/99
				<< mpMarketPlace->GetHTMLPath()
				<< "help/basics/g-new.html\"><img height=15 width=16 border=0 alt=\"New!\" src=\""
				<< mpMarketPlace->GetPicsPath()
				<< "lst/new.gif\"></a> <font size=\"2\">= New!</font>\n"

					"</td>\n"
				"</tr>\n"
			"</table>\n"

		"</td>\n"
	"</tr>\n"
"</table>\n";

/*
	*mpStream << 

	"<table width=\"400\" cellpadding=1 border=0 cellspacing=0>\n"
	"<tr>\n"
		"<td colspan=\"5\">\n"
		"<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n"
		"<tr>\n"
		"<td align=\"LEFT\" width=\"400\">\n"
		"<img height=15 width=16 border=0 alt=\"Gallery\" src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
	    << "lst/gal.gif\"> <font size=\"2\">= Gallery </font>\n"
		"&nbsp;&nbsp;&nbsp;"
//		"</td>\n"
//		"<td align=\"LEFT\" width=\"100\">\n"
		"<img height=15 width=16 border=0 alt=\"Picture\" src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<< "lst/pic.gif\"> <font size=\"2\">= Picture</font>\n"
//		"</td>\n"
		"&nbsp;&nbsp;&nbsp;"
//		"<td align=\"CENTER\" width=\"100\">\n"
		"<img height=15 width=16 border=0 alt=\"Hot!\" src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<< "lst/hot.gif\"> <font size=\"2\">= Hot!</font>\n"
//		"</td>\n"
//		"<td align=\"CENTER\" width=\"100\">\n"
		"&nbsp;&nbsp;&nbsp;"
		"<img height=15 width=16 border=0 alt=\"New!\" src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<< "lst/new.gif\"> <font size=\"2\">= New!</font>\n"
		"</td>\n"
		"</tr>\n"
		"</table>\n"
		"</td>\n"
	"</tr>\n"
	"</table>\n";
*/
	return;
}


void clsDraw::DrawItemStatusIconsMap()
{

	*mpStream << 
			"<map name=\"status_icon_map\">\n"
			"<area shape=rect coords=\"1,0,16,15\"\n" 
//			"	href=\"http://pages.ebay.com/help/basics/g-gallery.html\" alt=\"Gallery\">\n"
// kakiyama 08/03/99
			"   href=\""
		<<  mpMarketPlace->GetHTMLPath()
		<<  "help/basics/g-gallery.html\" alt=\"Gallery\">\n"
		<<  "<area shape=rect coords=\"20,0,36,15\"\n" 
//			"	href=\"http://pages.ebay.com/help/basics/g-pic.html\" alt=\"Picture\">\n"
// kakiyama 08/03/99
			"   href=\""
		<<  mpMarketPlace->GetHTMLPath()
		<<  "help/basics/g-pic.html\" alt=\"Picture\">\n"
			"<area shape=rect coords=\"40,0,56,15\"\n" 
//			"	href=\"http://pages.ebay.com/help/basics/g-hot-items.html\" alt=\"Hot!\">\n"
// kakiyama 08/03/99
			"   href=\""
		<<  mpMarketPlace->GetHTMLPath()
		<<  "help/basics/g-hot-items.html\" alt=\"Hot!\">\n"
			"<area shape=rect coords=\"60,0,76,15\"\n" 
//			"	href=\"http://pages.ebay.com/help/basics/g-new.html\" alt=\"New!\">\n"
// kakiyama 08/03/99
		<<  "   href=\""
		<<  mpMarketPlace->GetHTMLPath()
		<<  "help/basics/g-new.html\" alt=\"New!\">\n"
			"</map>\n";

	return;
}

void clsDraw::DrawGalleryPageLinksSection()
{
	*mpStream
	<< "<FONT SIZE=\"2\" FACE=\"HELVETICA\">"

	// This is the user survey link
//		<< "<a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=108\">\n"
//		<< "Tell us what you think of this page.</a><br>\n"
	// end of survey link

	<< "You are on page <b>" 
	<< mCurrentPage 
	<< "</b> of " 
	<< mNumPages 
	<< ".<BR>\n";

	if (mCurrentPage > 1)
	{
		DrawCategoryLink(mCurrentCategory, mCurrentListingType, mCurrentPage - 1, false, false, true);
		*mpStream << 
			"<IMG SRC=\""
			<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			<<	"gallery/gallery-arrowleft.gif\" WIDTH=\"6\" HEIGHT=\"7\" BORDER=\"0\">"
			"</A>&nbsp; ";
		DrawCategoryLink(mCurrentCategory, mCurrentListingType, mCurrentPage - 1, false, false, true);
		*mpStream <<
			"Previous Page</A> &nbsp; ";
	}

	if (mCurrentPage < mNumPages)
	{
		DrawCategoryLink(mCurrentCategory, mCurrentListingType, mCurrentPage + 1, false, false, true);
		*mpStream << 
			"Next Page</A> &nbsp; ";
		DrawCategoryLink(mCurrentCategory, mCurrentListingType, mCurrentPage + 1, false, false, true);
		*mpStream << 
			"<IMG SRC=\""
			<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			<<	"gallery/gallery-arrowright.gif\" WIDTH=\"6\" HEIGHT=\"7\" BORDER=\"0\">"
			"</A>";
	}
		
	*mpStream << 	
		"</FONT><BR>";

	*mpStream << 	
		"<FONT SIZE=\"3\" FACE=\"HELVETICA\">";

	// Draw the category breadcrumbs
	// Put Top: in front of it
//	*mpStream	<< "<A HREF=\"http://pages.ebay.com/"
// kakiyama 08/03/99
	*mpStream   << "<A HREF=\""
				<< mpMarketPlace->GetHTMLPath()
				<< "buy/gallery.html"
				<< "\">"
				<< "Top"
				<< "</A> : ";

	DrawGalleryTitleWithLinks(mCurrentCategory);

//	DrawTitleWithLinks(mCurrentCategory, false, true);

	*mpStream << 	
		"</FONT><BR CLEAR=\"all\">\n";
}

// Prints a fully qualified name, with links
// to those listings, recursively.
void clsDraw::DrawTitleWithLinks(int categoryNumber, bool finding, bool gallery, int featureType)
{
	categoryEntry *pCategory;

	if (categoryNumber == 0 && gallery)
		return;

	pCategory = mpData->GetCategory(categoryNumber);

	// Don't recurse if we've at the top.
	// Otherwise, recurse _before_ we do ourselves.
	if (categoryNumber != 0)
		DrawTitleWithLinks(pCategory->parentCategory, finding, gallery, featureType);

	// We don't link to our category.
	if (categoryNumber != mCurrentCategory)
	{
		DrawCategoryLink(categoryNumber, mCurrentListingType,
			1, finding ? pCategory->isAdult != 0 : false, false, gallery, featureType);
	}

	// Print 'eBay Categories' instead of 'top'
	// if we're viewing the top category.
	if (!categoryNumber && !mCurrentCategory)
		// do not print 'eBay Categories' if we are in top featured or top hot page
		if (hotEntry == (entryTypeEnum)featureType || featuredEntry == (entryTypeEnum)featureType)
			*mpStream << "&nbsp";
		else
			*mpStream << "eBay Categories";
	else
		*mpStream << mpData->GetTitle(pCategory);

	// And end if we linked.
	if (categoryNumber != mCurrentCategory)
		*mpStream << "</A> : ";

	return;
}

// This function draw Gallery links for any level # in the category hierachy (for Grabbag)
void clsDraw::DrawGalleryTitleWithLinks(int categoryNumber, int catLevel, bool bGrabbag)
{
	if (categoryNumber == 0 || catLevel > 4)
		return;

	categoryEntry* pCategory = mpData->GetCategory(categoryNumber);

	// Recurse before we do ourselves.
	DrawGalleryTitleWithLinks(pCategory->parentCategory, catLevel, bGrabbag);

	int indexCategory = GetCategoryIndex(categoryNumber);
	const char* indexCategoryUrl = NULL;

	// Are we at the top level?
	// If so then we will use the static gallery category index page
	// instead of a generated index page
	if (indexCategory == categoryNumber)
	{
		switch (indexCategory)
		{
		case 353:	// antiques
			indexCategoryUrl = "buy/gallery-antiques.html";
//			indexCategoryUrl = "antiques-index.html";
			break;
		case 160:	// computers
			indexCategoryUrl = "buy/gallery-computers.html";
//			indexCategoryUrl = "computer-index.html";
			break;
		case 237:	// dolls-figures
			indexCategoryUrl = "buy/gallery-dolls.html";
//			indexCategoryUrl = "dolls-index.html";
			break;
		case 281:	// jewelry-gemstones
			indexCategoryUrl = "buy/gallery-jewelry.html";
//			indexCategoryUrl = "jewelry-index.html";
			break;
		case 1047:	// photo-electronics
			indexCategoryUrl = "buy/gallery-photo.html";
//			indexCategoryUrl = "photo-index.html";
			break;
		case 870:	// pottery-glass
			indexCategoryUrl = "buy/gallery-pottery.html";
//			indexCategoryUrl = "pottery-index.html";
			break;
		case 888:	// sports-memorabilia
			indexCategoryUrl = "buy/gallery-sports.html";
//			indexCategoryUrl = "sports-index.html";
			break;
		case 220:	// toys-beanies
			indexCategoryUrl = "buy/gallery-toys.html";
//			indexCategoryUrl = "toys-index.html";
			break;
		case 266:	// books-movies-music
			indexCategoryUrl = "buy/gallery-books.html";
//			indexCategoryUrl = "books-index.html";
			break;
		case 866:	// coins-stamps
			indexCategoryUrl = "buy/gallery-coins.html";
//			indexCategoryUrl = "coins-index.html";
			break;
		case 1:		// collectibles
			indexCategoryUrl = "buy/gallery-collectibles.html";
//			indexCategoryUrl = "collectibles-index.html";
			break;
		case 99:	// miscellaneous
			indexCategoryUrl = "buy/gallery-misc.html";
//			indexCategoryUrl = "misc-index.html";
			break;
		}

//		*mpStream << "<A HREF=\"http://pages.ebay.com/"
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << indexCategoryUrl
				  << "\">";

		*mpStream << mpData->GetTitle(pCategory);

		if (categoryNumber != mCurrentCategory)
			if (pCategory->categoryLevel < catLevel)
			{
				if (bGrabbag)
					*mpStream << "</A>: ";
				else
					*mpStream << "</A> : ";
			}
			else
				*mpStream << "</A>";

	}
	else if (pCategory->categoryLevel < catLevel)
	{
		if(categoryNumber != mCurrentCategory)
			// We don't link to our category.
			DrawCategoryLink(categoryNumber, (int)GalleryListingType,
				1, false, false, true);

		*mpStream << mpData->GetTitle(pCategory);

		if(categoryNumber != mCurrentCategory)
		{
			if (bGrabbag)
				*mpStream << "</A>: ";
			else
				*mpStream << "</A> : ";
		}

	}
	else if (catLevel == pCategory->categoryLevel)
	{
		if(categoryNumber != mCurrentCategory)
			// We don't link to our category.
			DrawCategoryLink(categoryNumber, (int)GalleryListingType,
				1, false, false, true);

		*mpStream << mpData->GetTitle(pCategory);

		if(categoryNumber != mCurrentCategory)
			*mpStream << "</A>";

	}

	else
		;


}


void clsDraw::DrawGalleryTitleWithLinks(int categoryNumber)
{
	// first check for Top.
	//  if it is, then just stop recursing up
	if (categoryNumber == 0)
	{
		return;
	}

	categoryEntry* pCategory = mpData->GetCategory(categoryNumber);

	// Recurse before we do ourselves.
	DrawGalleryTitleWithLinks(pCategory->parentCategory);

	int indexCategory = GetCategoryIndex(categoryNumber);
	const char* indexCategoryUrl = NULL;

	// Are we at the top level?
	// If so then we will use the static gallery category index page
	// instead of a generated index page
	if (indexCategory == categoryNumber)
	{
		switch (indexCategory)
		{
		case 353:	// antiques
			indexCategoryUrl = "buy/gallery-antiques.html";
//			indexCategoryUrl = "antiques-index.html";
			break;
		case 160:	// computers
			indexCategoryUrl = "buy/gallery-computers.html";
//			indexCategoryUrl = "computer-index.html";
			break;
		case 237:	// dolls-figures
			indexCategoryUrl = "buy/gallery-dolls.html";
//			indexCategoryUrl = "dolls-index.html";
			break;
		case 281:	// jewelry-gemstones
			indexCategoryUrl = "buy/gallery-jewelry.html";
//			indexCategoryUrl = "jewelry-index.html";
			break;
		case 1047:	// photo-electronics
			indexCategoryUrl = "buy/gallery-photo.html";
//			indexCategoryUrl = "photo-index.html";
			break;
		case 870:	// pottery-glass
			indexCategoryUrl = "buy/gallery-pottery.html";
//			indexCategoryUrl = "pottery-index.html";
			break;
		case 888:	// sports-memorabilia
			indexCategoryUrl = "buy/gallery-sports.html";
//			indexCategoryUrl = "sports-index.html";
			break;
		case 220:	// toys-beanies
			indexCategoryUrl = "buy/gallery-toys.html";
//			indexCategoryUrl = "toys-index.html";
			break;
		case 266:	// books-movies-music
			indexCategoryUrl = "buy/gallery-books.html";
//			indexCategoryUrl = "books-index.html";
			break;
		case 866:	// coins-stamps
			indexCategoryUrl = "buy/gallery-coins.html";
//			indexCategoryUrl = "coins-index.html";
			break;
		case 1:		// collectibles
			indexCategoryUrl = "buy/gallery-collectibles.html";
//			indexCategoryUrl = "collectibles-index.html";
			break;
		case 99:	// miscellaneous
			indexCategoryUrl = "buy/gallery-misc.html";
//			indexCategoryUrl = "misc-index.html";
			break;
		}

//		*mpStream << "<A HREF=\"http://pages.ebay.com/"
// kakiyama 08/03/99
		*mpStream << "<A HREF=\""
				  << mpMarketPlace->GetHTMLPath()
				  << indexCategoryUrl
				  << "\">";
	}
	else if (categoryNumber != mCurrentCategory)
	{
		// We don't link to our category.
		DrawCategoryLink(categoryNumber, mCurrentListingType,
			1, false, false, true);
	}

	*mpStream << mpData->GetTitle(pCategory);

	// And end if we linked.
	if (categoryNumber != mCurrentCategory)
		*mpStream << "</A> : ";
}


// Draw a link to our 'previous' (left) sibling,
// with picture.
void clsDraw::DrawPreviousSibling(bool gallery, int featureType)
{
	if (!mpCategory->leftSibling)
	{
		*mpStream << "&nbsp;";
		return;
	}

	// Draw the link, being careful
	// of throwing them into an 'adult' category.
	DrawCategoryLink(mpCategory->leftSibling,
		mCurrentListingType, 1, 
		!(mpCategory->isAdult) && (mpData->GetCategory(mpCategory->leftSibling)->isAdult), false, gallery, featureType);

//	*mpStream << mpPreviousPicURL;
// kakiyama 08/03/99
	*mpStream << "<img height=18 width=12 border=0 alt=\"[Previous]\" src=\""
			  << mpMarketPlace->GetPicsPath()
			  << "listings/browse-arrow-l.gif\">";

	return;
}

// Draw a link to our 'next' (right) sibling,
// with picture.
void clsDraw::DrawNextSibling(bool gallery, int featureType)
{
	if (!mpCategory->rightSibling)
	{
		*mpStream << "&nbsp;";
		return;
	}

	DrawCategoryLink(mpCategory->rightSibling,
		mCurrentListingType, 1,
		!(mpCategory->isAdult) && (mpData->GetCategory(mpCategory->rightSibling)->isAdult),
		gallery, featureType);

//	*mpStream << mpNextPicURL;
// kakiyama 08/03/99
    *mpStream << "<img height=18 width=12 border=0 alt=\"[Next]\" src=\""
			  << mpMarketPlace->GetPicsPath()
			  << "listings/browse-arrow-r.gif\">";

	return;
}

// Draw the block which links to the different
// listing types (current, ending, new, ...)
// within the same category.
void clsDraw::DrawListingTypesSection(bool gallery, int featureType)
{
	int i;
	int lastType;

	// In the new UI, the gallery is not confined to category 353,
	// so I commented out the old code. ---- Stevey
	// Draw the link to the Gallery if we're in Antiques (category 353).
//	if (mCurrentCategory == 353 ||
//		mpCategory->parentCategory == 353)

	    lastType = GalleryListingType; 
//	else 

//	lastType = GalleryListingType;

	*mpStream << "<p align=center><small>";

	for (i = 0; i < lastType; ++i)
	{
		if ((featuredEntry == (entryTypeEnum)mCurrentFeatureType) && (CompletedListingType == (listingTypeEnum)i))
			continue;

		if ((hotEntry == (entryTypeEnum)mCurrentFeatureType) && (CompletedListingType == (listingTypeEnum)i))
			continue;

		// We don't link to ourselves.
		if (i == mCurrentListingType)
			*mpStream << "<strong>";
		else
			DrawCategoryLink(mCurrentCategory,
				(listingTypeEnum) i, 1, false, false, gallery, featureType);

		*mpStream << mppListingTypesLinkDescription[i];

		if (i == mCurrentListingType)
			*mpStream << "</strong>";
		else
			*mpStream << "</a>";

		// Print dividers, but not after the last one.
		if ((i + 1) != lastType)
			*mpStream << " || ";
	}

	*mpStream << "</small></p>";

	return;
}

// The title box section is the
// one which contains
// the sibling links, the fully qualified names
// and, if we're on top, the listing type
//
// If we're on the top, we also color the table
// and put borders in.
void clsDraw::DrawTitleBoxSection(int top, bool gallery, int featureType)
{
	if (mpCategory->categoryNumber)
	{
		*mpStream << "<p><table border="
	   			  << (top ? '1' : '0')
				  << " cellspacing=0 width=\"100%\"";

		// Only color for top.
		if (top)
			*mpStream << " bgcolor=\"#cccccc\"";

		*mpStream << ">"
					 "<tr><td align=center width=\"5%\">";

		DrawPreviousSibling(gallery, featureType);

		*mpStream << "</a></td>"
					 "<td align=center width=\"90%\">"
					 "<font size=4 face=\"arial,helvetica\"><strong>";

		DrawTitleWithLinks(mCurrentCategory, false, gallery, featureType);

		*mpStream << "</strong></font></td>"
					 "<td align=center width=\"5%\">";

		DrawNextSibling(gallery, featureType);

		*mpStream << "</a></td></tr>";

		if (top && !mDrawingUsers)
			*mpStream << "<tr><td align=center width=\"100%\" colspan=3><font size=2>"
					  << mppListingTypeDescriptions[mCurrentListingType]
					  << "</font></td></tr>";


		*mpStream << "</table>";
	}
}


// This function draws the title and listing type in the same bar --- new UI (Stevey)
void clsDraw::DrawTitleAndListingTypeSection(bool gallery, int featureType)
{
	int i;
	int lastType;

	lastType = GalleryListingType;

	//if (mpCategory->categoryNumber)
	//{
		*mpStream << "<p><table border=0"
				  << " cellspacing=0 width=\"100%\"";


		*mpStream << " bgcolor=\"#cccccc\"";

		*mpStream << ">"
					 "<tr><td align=center width=\"5%\">";

		DrawPreviousSibling(gallery, featureType);

		*mpStream << "</a></td>"
					 "<td align=center width=\"90%\">"
					 "<font size=4 face=\"arial,helvetica\"><strong>";

		DrawTitleWithLinks(mCurrentCategory, false, gallery, featureType);

		*mpStream << "</strong></font></td>"
					 "<td align=center width=\"5%\">";

		DrawNextSibling(gallery, featureType);

		*mpStream << "</a></td></tr>";

		*mpStream << "<tr><td align=center width=\"100%\" colspan=3>";


		*mpStream << "<p align=center><small><font face=\"Arial, Helvetica\" size=\"-1\">";

		for (i = 0; i < lastType; ++i)
		{
			if ((featuredEntry == (entryTypeEnum)mCurrentFeatureType) && (CompletedListingType == (listingTypeEnum)i))
				continue;

			if ((hotEntry == (entryTypeEnum)mCurrentFeatureType) && (CompletedListingType == (listingTypeEnum)i))
				continue;

			// We don't link to ourselves.
			if (i == mCurrentListingType)
				*mpStream << "<strong>";
			else
				DrawCategoryLink(mCurrentCategory,
					(listingTypeEnum) i, 1, false, false, gallery, featureType);

			*mpStream << mppListingTypesLinkDescription[i];

			if (i == mCurrentListingType)
				*mpStream << "</strong>";
			else
				*mpStream << "</a>";

			// Print dividers, but not after the last one.
			if ((i + 1) != lastType)
				*mpStream << " || ";
		}

		*mpStream	<< "</small></font></p>"
					<< "</td></tr>"
					<< "</table>";

	//}

}


// Draw the link which says when our listings
// were updated and gives a link to the
// current time.
//
// We could actually print the current time
// here, except that proxies might cache these
// pages, and they shouldn't cache one that
// specifies cgi (at least, that's the theory.)
const char *sBarnesAndNobleLinkCat357 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=folk+art&match=exact&options=and\">books</a> about folk art now, "
"or search for <a href=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html?query=folk+art&CategoryID=&category1=267&maxRecordsReturned=300&maxRecordsPerPage=100&SortProperty=MetaEndSort\">books</a> on eBay about folk art.</font>";

const char *sBarnesAndNobleLinkCat359 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=antique+musical+instrument&match=exact&options=and\">books</a> about musical instruments now, "
"or search for <a href=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html?cobrandpartner=x&maxRecordsReturned=300&maxRecordsPerPage=100&category1=267&textonly=n&query=musical+instrument*&SortProperty=MetaEndSort\"> books</a> on eBay about musical instruments.</font>";

const char *sBarnesAndNobleLinkCat360 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=antique+prints&match=exact&options=and\">books</a> about antique prints now.</font>";

const char *sBarnesAndNobleLinkCat272 =
"<br><font size=\"2\">Buy mystery <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=mystery\">books</a> now, or "
"browse mystery <a href=\"#featured\">books</a> on eBay.</font>";


const char *sBarnesAndNobleLinkCat273 =
"<br><font size=\"2\">Buy science fiction <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=scifi\">books</a> now, or "
"browse science fiction <a href=\"#featured\">books</a> on eBay.</font>";

const char *sBarnesAndNobleLinkCat276 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=cooking\">cookbooks</a> now, or "
"browse <a href=\"#featured\">cookbooks</a> on eBay.</font>";

const char *sBarnesAndNobleLinkCat527 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=United+States+Gold+Coins&match=exact&options=and\">books</a> about US gold coins now.</font>";

const char *sBarnesAndNobleLinkCat525 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=United+States+coin+lots&match=exact&options=and\">books</a> about US coin collections now.</font>";

const char *sBarnesAndNobleLinkCat687 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=United+States+stamps+first+day+covers&match=exact&options=and\">books</a> about US first day covers now.</font>";

const char *sBarnesAndNobleLinkCat699 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=Stamps+Europe&match=exact&options=and\">books</a> about European stamps now.</font>";

const char *sBarnesAndNobleLinkCat386 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=collectible+bears&match=exact&options=and\">books</a> about collectible bears now.</font>";

const char *sBarnesAndNobleLinkCat63 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=collecting+comic+books&match=exact&options=and\">books</a> about collecting comic books now.</font>";

const char *sBarnesAndNobleLinkCat137 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=disney\">books </a> about Disney now.</font>";

const char *sBarnesAndNobleLinkCat113 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=vintage+sewing&match=exact&options=and\">books</a> about vintage sewing now.</font>";

const char *sBarnesAndNobleLinkCat164 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=hardware\">books</a> about computer hardware now.</font>";

const char *sBarnesAndNobleLinkCat178 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=hardware\">books</a> about computer hardware now.</font>";

const char *sBarnesAndNobleLinkCat190 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=games\">books</a> about computer games now.</font>";

const char *sBarnesAndNobleLinkCat326 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=antique+dolls&match=exact&options=and\">books</a> about antique dolls now.</font>";

const char *sBarnesAndNobleLinkCat338 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=vogue+dolls&match=exact&options=and\">books</a> about Vogue dolls now.</font>";

const char *sBarnesAndNobleLinkCat247 = 
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=barbie+dolls&match=exact&options=and\">books</a> about Barbie dolls now.</font>";

const char *sBarnesAndNobleLinkCat488 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=jewelry+beads&match=exact&options=and\">books</a> about beads now.</font>";

const char *sBarnesAndNobleLinkCat514 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=victorian+jewelry&match=exact&options=and\">books</a> about Victorian jewelry now.</font>";

const char *sBarnesAndNobleLinkCat290 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=watches&match=exact&options=and\">books</a> about watches now.</font>";

const char *sBarnesAndNobleLinkCat295 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=audio+equipment&match=exact&options=and\">books</a> about audio equipment now.</font>";

const char *sBarnesAndNobleLinkCat629 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=darkroom+equipment&match=exact&options=and\">books</a> about darkroom equipment now.</font>";

const char *sBarnesAndNobleLinkCat298 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=video+equipment&match=exact&options=and\">books</a> about video equipment now.</font>";

const char *sBarnesAndNobleLinkCat1023 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=fostoria+glass&match=exact&options=and\">books</a> about Fostoria glass now.</font>";

const char *sBarnesAndNobleLinkCat89 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=mccoy+pottery&match=exact&options=and\">books</a> about McCoy pottery now.</font>";

const char *sBarnesAndNobleLinkCat94 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=noritake+porcelain&match=exact&options=and\">books</a> about Noritake now.</font>";

const char *sBarnesAndNobleLinkCat428 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=golf\">books</a> about golf now.</font>";

const char *sBarnesAndNobleLinkCat204 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=baseball\">books</a> about baseball now.</font>";

const char *sBarnesAndNobleLinkCat780 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=hockey&match=exact&options=and\">books</a> about hockey now.</font>";

const char *sBarnesAndNobleLinkCat246 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=action+figures&match=exact&options=and\">books</a> about action figures now.</font>";

const char *sBarnesAndNobleLinkCat436 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=beanie+babies&match=exact&options=and\">books</a> about Beanies now.</font>";

const char *sBarnesAndNobleLinkCat222 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=diecast+cars&match=exact&options=and\">books</a> about Diecast now.</font>";

const char *sBarnesAndNobleLinkCat292 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=automotive&match=exact&options=and\">books</a> about automotive now.</font>";

const char *sBarnesAndNobleLinkCat1065 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&categoryid=fashion\">books</a> about fashion now.</font>";

const char *sBarnesAndNobleLinkCat1048 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=equestrian+equipment&match=exact&options=and\">books</a> about equestrian equipment now.</font>";

const char *sBarnesAndNobleLinkCat619 =
"<br><font size=\"2\">Buy <a href=\"http://barnesandnoble.bfast.com/booklink/click?sourceid=334743&is_search=Y&keyword=musical+instruments&match=exact&options=and\">books</a> about musical instruments now.</font>";


void clsDraw::DrawSellItemLink(int featureType)
{

	/* Commented out by AlexP on 7/13/99 -- moved survey link to header area
	// Links connected to the survey
	if (featuredEntry == (entryTypeEnum)featureType)
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=103\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";

	else if (hotEntry == (entryTypeEnum)featureType)
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=104\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";

	else
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=108\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";
	*/

	// Make a 2-column table
	//  * column 1 will have the Sell your item links
	//  * column 2 will have the update time and the check official time link
	*mpStream	<<	"<table border=0 cellspacing=0 width=\"100%\">"
					"<tr>"
					"<td width=\"50%\" align=\"left\">";

	// Sell Item link
	if (mpCategory->isLeaf)
	{
// TODO - fix cgi5 path
		*mpStream 
				  << "<font size=\"2\">\n"
//				  << "<a href=\"http://cgi5.ebay.com/aw-cgi/eBayISAPI.dll?ListItemForSale&category="
				  << "<a href=\""
				  << mpMarketPlace->GetCGIPath(PageListItemForSale)
				  << mpCategory->categoryNumber
				  << "eBayISAPI.dll?ListItemForSale&category="
				  << "\">"
				  << "Sell your item"
				  << "</a>"
				  << " in the <b>";
		if (mpCategory->parentCategory)
		{
			*mpStream << mpData->GetTitle(mpData->GetCategory(mpCategory->parentCategory))
						  << " : ";
		}

		*mpStream << mpData->GetTitle(mpCategory);
		*mpStream << "</b> category";
		*mpStream << "</font><br>\n";
	//	*mpStream << mpNewURL;
	}

	*mpStream	<<	"</td>"
					"<td width=\"50%\" align=\"right\">";

	*mpStream	<< 	"<font size=\"2\">"
					"<b>Updated:</b> "
				<<	mpUpdateTimeString
//				<<	" &nbsp;<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?TimeShow\">"
				<<  " &nbsp;<a href=\""
				<<  mpMarketPlace->GetCGIPath(PageTimeShow)
				<< "eBayISAPI.dll?TimeShow\">"
					"Check eBay official time</a>\n"
					"</font>";

	*mpStream	<<	"</td>"
					"</tr>"
					"</table>";
	return;
}


void clsDraw::DrawTimeLink()
{

	*mpStream << "<strong>Updated: "
			  << mpUpdateTimeString
//			  << "</strong> <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?TimeShow\">"
//	kakiyama 08/03/99
			  << "</strong> <a href=\""
			  << mpMarketPlace->GetCGIPath(PageTimeShow)
			  << "eBayISAPI.dll?TimeShow\">"
			     "Check eBay official time</a>\n"
				 "<br><font size=\"2\">Use your browser's <strong>reload</strong> button "
				 "to see the latest version.</font>\n";
//				 "<br>Updated hourly except between 3 p.m. and 8 p.m. PDT</font>\n";
// Lena - to put the link to list your item in current category
	if (mpCategory->isLeaf)
	{
//		*mpStream << "<p><a href=\"http://cgi5.ebay.com/aw-cgi/eBayISAPI.dll?ListItemForSale&category="
		*mpStream << "<p><a href=\""
				  << mpMarketPlace->GetCGIPath(PageListItemForSale)
				  << "eBayISAPI.dll?ListItemForSale&category="
				  << mpCategory->categoryNumber
				  << "\">"
				  << "Sell your item"
				  << "</a>"
				  << " in the <b>";
		if (mpCategory->parentCategory)
		{
			*mpStream << mpData->GetTitle(mpData->GetCategory(mpCategory->parentCategory))
						  << " : ";
		}

		*mpStream << mpData->GetTitle(mpCategory);
		*mpStream << "</b> category";
	//	*mpStream << mpNewURL;
	}

/*
	switch (mpCategory->categoryNumber)
	{
	case 63:
		*mpStream << sBarnesAndNobleLinkCat63;
		break;
	case 89:
		*mpStream << sBarnesAndNobleLinkCat89;
		break;
	case 94:
		*mpStream << sBarnesAndNobleLinkCat94;
		break;
	case 357:
		*mpStream << sBarnesAndNobleLinkCat357;
		break;
	case 359:
		*mpStream << sBarnesAndNobleLinkCat359;
		break;
	case 360:
		*mpStream << sBarnesAndNobleLinkCat360;
		break;
	case 272:
		*mpStream << sBarnesAndNobleLinkCat272;
		break;
	case 273:
		*mpStream << sBarnesAndNobleLinkCat273;
		break;
	case 276:
		*mpStream << sBarnesAndNobleLinkCat276;
		break;
	case 527:
		*mpStream << sBarnesAndNobleLinkCat527;
		break;
	case 525:
		*mpStream << sBarnesAndNobleLinkCat525;
		break;
	case 687:
		*mpStream << sBarnesAndNobleLinkCat687;
		break;
	case 699:
		*mpStream << sBarnesAndNobleLinkCat699;
		break;
	case 386:
		*mpStream << sBarnesAndNobleLinkCat386;
		break;
	case 137:
		*mpStream << sBarnesAndNobleLinkCat137;
		break;
	case 113:
		*mpStream << sBarnesAndNobleLinkCat113;
		break;
	case 164:
		*mpStream << sBarnesAndNobleLinkCat164;
		break;
	case 178:
		*mpStream << sBarnesAndNobleLinkCat178;
		break;
	case 190:
		*mpStream << sBarnesAndNobleLinkCat190;
		break;
	case 326:
		*mpStream << sBarnesAndNobleLinkCat326;
		break;
	case 338:
		*mpStream << sBarnesAndNobleLinkCat338;
		break;
	case 247:
		*mpStream << sBarnesAndNobleLinkCat247;
		break;
	case 488:
		*mpStream << sBarnesAndNobleLinkCat488;
		break;
	case 514:
		*mpStream << sBarnesAndNobleLinkCat514;
		break;
	case 290:
		*mpStream << sBarnesAndNobleLinkCat290;
		break;
	case 295:
		*mpStream << sBarnesAndNobleLinkCat295;
		break;
	case 629:
		*mpStream << sBarnesAndNobleLinkCat629;
		break;
	case 298:
		*mpStream << sBarnesAndNobleLinkCat298;
		break;
	case 1023:
		*mpStream << sBarnesAndNobleLinkCat1023;
		break;
	case 428:
		*mpStream << sBarnesAndNobleLinkCat428;
		break;
	case 204:
		*mpStream << sBarnesAndNobleLinkCat204;
		break;
	case 780:
		*mpStream << sBarnesAndNobleLinkCat780;
		break;
	case 246:
		*mpStream << sBarnesAndNobleLinkCat246;
		break;
	case 436:
		*mpStream << sBarnesAndNobleLinkCat436;
		break;
	case 222:
		*mpStream << sBarnesAndNobleLinkCat222;
		break;
	case 292:
		*mpStream << sBarnesAndNobleLinkCat292;
		break;
	case 1065:
		*mpStream << sBarnesAndNobleLinkCat1065;
		break;
	case 1048:
		*mpStream << sBarnesAndNobleLinkCat1048;
		break;
	case 619:
		*mpStream << sBarnesAndNobleLinkCat619;
		break;
	}

*/

	return;
}

// The box that heads up featured auctions.
void clsDraw::DrawFeaturedHeading()
{
	if (mJumpAlreadyDraw)
		*mpStream << "<a name=\"eBayListings\">&nbsp;</a>";

	*mpStream << "<table border=\"1\" cellspacing=\"0\" width=\"100%\" bgcolor=\""
				 "#99CC00\"><tr><td align=\"center\"><font size=4 face=\"arial,helvetica\"><strong>";

	if (mpCategory->categoryNumber)
		*mpStream << "<a name=\"featured\">Featured</a> Auctions in "
				  << mpData->GetTitle(mpCategory);
	
	else
		*mpStream << "<a name=featured>Featured Auctions</A>";
	
	*mpStream << "</strong></font></td></tr></table>\n";

/*
				 "<tr><td align=\"center\" width=\"100%\"><font size=2>"
			  << mppListingTypeDescriptions[mCurrentListingType]
			  << "</font></td></tr>";


				 "<p align=\"center\"><font size=\"2\">"
				 "To find out how to be listed in this section and seen by thousands, "
				 "please <a href=\"" << mpFeaturedPath << "\">visit this link</a>."
				 "</font></p>\n";
*/

	return;
}

// The box that heads up hot auctions
void clsDraw::DrawHotHeading()
{

	*mpStream << "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"4\""
			  << "color=\"#FF6666\"><b>";
	
 	if (mpCategory->categoryNumber)
		*mpStream << "Hot Items in "
				  << mpData->GetTitle(mpCategory);
	
	else
		*mpStream << "<a name=hot>Hot Items</b></font>\n";
	
	*mpStream << "</b></font>";

		/*		 "<tr><td align=\"center\" width=\"100%\"><font size=2>"
			  << mppListingTypeDescriptions[mCurrentListingType]
			  << "</font></td></tr>
		
			 
				 "<p align=\"center\"><font size=\"2\">"
				 "These items have received more than 30 bids. (No reserve price auctions.)"
				 "</font>";
	*/

	return;
}

// The box that heads up the 'real' item
// listings. This currently only does so
// on pages which also have hot or featured listed.
void clsDraw::DrawAllHeadingSection()
{
	*mpStream << "<p><table border=\"1\" cellspacing=\"0\" width=\"100%\" bgcolor=\""
				 "#cccccc\"><tr><td align=\"center\"><font size=4 face=\"arial,helvetica\"><strong>";
	
	if (mpCategory->categoryNumber)
        *mpStream << "All " << (mDrawingUsers ? "Users" : "Items") << " in "
				  << mpData->GetTitle(mpCategory);
	
	
	if (!mDrawingUsers)
    {
	    *mpStream << "</strong></font></td></tr>"
				     "<tr><td align=\"center\" width=\"100%\"><font size=2>"
			      << mppListingTypeDescriptions[mCurrentListingType]
			      << "</font></td></tr></table>\n";
    }
    else
    {
        *mpStream << "</strong></font></td></tr></table>\n";
    }

	return;
}

int clsDraw::GetCategoryIndex(int categoryNumber)
{
	categoryEntry* pCategory = mpData->GetCategory(categoryNumber);

	if (pCategory->parentCategory == 0)
		return categoryNumber;
	else
		return GetCategoryIndex(pCategory->parentCategory);
}

void clsDraw::DrawGalleryCategoryHeading(int category)
{
	int indexCategory = GetCategoryIndex(category);
	const char* indexCategoryImage = NULL;

	const char * sAds;

	switch (indexCategory)
	{
	case 353:	// antiques
		indexCategoryImage = "gallery-antiques.gif";
		break;
	case 160:	// computers
		indexCategoryImage = "gallery-computers.gif";
		break;
	case 237:	// dolls-figures
		indexCategoryImage = "gallery-dolls.gif";
		break;
	case 281:	// jewelry-gemstones
		indexCategoryImage = "gallery-jewelry.gif";
		break;
	case 1047:	// photo-electronics
		indexCategoryImage = "gallery-photo.gif";
		break;
	case 870:	// pottery-glass
		indexCategoryImage = "gallery-pottery.gif";
		break;
	case 888:	// sports-memorabilia
		indexCategoryImage = "gallery-sports.gif";
		break;
	case 220:	// toys-beanies
		indexCategoryImage = "gallery-toys.gif";
		break;
	case 266:	// books-movies-music
		indexCategoryImage = "gallery-books.gif";
		break;
	case 866:	// coins-stamps
		indexCategoryImage = "gallery-coins.gif";
		break;
	case 1:		// collectibles
		indexCategoryImage = "gallery-collectibles.gif";
		break;
	case 99:	// miscellaneous
		indexCategoryImage = "gallery-misc.gif";
		break;
	default:	// We default to miscellaneous so something shows
		indexCategoryImage = "gallery-misc.gif";
		break;
	}


	// Ad
	sAds = mpTemplates->GetAdsGallery(mPartner, category);
	if (mpAdGallery != sAds)  // Determine if it is a real ad
		*mpStream << sAds;

/*
	*mpStream << 
		"<TR>"
		"	<TD><img src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<<  "gallery/";
	*mpStream << indexCategoryImage;

	*mpStream << "\" ALT=\""
		<< indexCategoryImage
		<< "\" WIDTH=\"379\" HEIGHT=\"74\" ALIGN=\"left\" BORDER=\"0\" hspace=\"30\" vspace=\"0\">\n"
		"<A HREF=\"http://www.kodak.com/go/ebay2\"><IMG \n"
		"SRC=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<< "promo/kodak_pn_sponsor.gif\" WIDTH=\"104\" \n"
		"HEIGHT=\"59\" BORDER=\"0\" ALT=\"Sponsored by Kodak PhotoNet Online\" \n"
		"ALIGN=\"RIGHT\"></A>\n"		
		
		"</TD>"
		"</TR>"
		"<BR>";

*/

}

 //heading for gallery
void clsDraw::DrawAllGalleryHeadingSection(int category)
{
	*mpStream << 
		/*
"<MAP NAME=\"navbarmap\">"
"    <AREA SHAPE=\"RECT\" COORDS=\"351, 0, 407, 24\" HREF=\"http://pages.ebay.com/sitemap.html\" ALT=\"SITE MAP\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"285, 0, 350, 24\" HREF=\"http://pages.ebay.com/newschat.html\" ALT=\"NEWS/CHAT\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"248, 0, 284, 24\" HREF=\"http://pages.ebay.com/contact.html\" ALT=\"HELP\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"199, 0, 247, 24\" HREF=\"http://pages.ebay.com/search.html\" ALT=\"SEARCH\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"146, 0, 198, 24\" HREF=\"http://pages.ebay.com/seller-services.html\" ALT=\"SELLERS\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"97, 0, 145, 24\" HREF=\"http://pages.ebay.com/ps.html\" ALT=\"BUYERS\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"41, 0, 96, 24\" HREF=\"http://listings.ebay.com/aw/listings/list\" ALT=\"LISTINGS\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"0, 0, 40, 24\" HREF=\"http://www.ebay.com\" ALT=\"HOME\">\n"
"</MAP>\n" */
/*
"\n"
"<MAP NAME=\"gallerymap\">\n"
"    <AREA SHAPE=\"RECT\" COORDS=\"514, 0, 546, 17\" HREF=\"http://pics.ebay.com/aw/cr/gallery-faq.html\" ALT=\"FAQ\">\n"
"</MAP>\n"
"\n"
"<!-- begin logo/nav table -->\n"
*/
 "<DIV ALIGN=\"center\">\n"

  
	"<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
/*
"	<TR>\n"
"		<TD>\n"
"\n"
"			<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
"			  <tr>\n"
"				<td width=\"120\">\n"
"				  <a href=\"http://www.ebay.com\"><img src=\"http://pics.ebay.com/aw/pics/logo_lower_tb.gif\" width=\"96\" hspace=\"0\" vspace=\"0\" height=\"42\" alt=\"eBay logo\" border=\"0\"></a>\n"
"				</td>\n"
"				<td>\n"
"				  <IMG SRC=\"http://pics.ebay.com/aw/pics/navbar.gif\" ALT=\"eBay Main Navbar\" WIDTH=\"407\" HEIGHT=\"24\" BORDER=\"0\" USEMAP=\"#navbarmap\">\n"
"				</td>\n"
"			  </tr>\n"
"			</table>\n"
"		</TD>\n"
"	</TR>\n"
"<!-- end logo/nav table -->\n" */
"	\n"
"	\n";
	DrawGalleryCategoryHeading(category);
	*mpStream <<
"	\n"
"	<TR>\n"
"		<TD>\n"
"			<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
"				<TR>\n"
"					<TD ALIGN=\"right\" VALIGN=\"bottom\"><IMG SRC=\""
			<< mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			<< "gallery/gallery-logo.gif\" ALT=\"Gallery\" WIDTH=\"156\" HEIGHT=\"47\" BORDER=\"0\" ALIGN=\"right\" HSPACE=\"0\" VSPACE=\"0\"></TD>\n"
"					<TD ALIGN=\"right\" VALIGN=\"bottom\" COLSPAN=\"5\">";
	
	DrawGalleryPageLinksSection();
		
	*mpStream <<
"					<BR CLEAR=\"all\">\n"
"					<IMG SRC=\""
			  <<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			  <<	"gallery/gallery-blueline.gif\" ALT=\"Gallery\" WIDTH=\"444\" HEIGHT=\"1\" BORDER=\"0\" VSPACE=\"8\"></TD>		"	
"					\n"
"				</TR>\n"
"		\n"
"				<!-- Categories will go here -->\n"
"			\n"
"			</TABLE>\n"
"  </TD>\n"
"</TR>\n"
"</TABLE>\n";

	if (mpCategory->firstChild)
	{
		if (mCurrentPage == 1)
		{
			*mpStream << "<font size=\"-1\">";
			DrawCategorySection(true, (int)normalEntry);

			*mpStream << "</font><p>";

			*mpStream <<
				"<TABLE cellpadding=\"0\" cellspacing=\"0\" width=\"600\" height=\"30\">\n"
				"<!-- blueline divider -->\n"
				"<TR><TD border=\"1\" width=\"600\" >\n"
				"<IMG SRC=\""
				<< mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				<< "gallery/gallery-blueline.gif\" ALT=\"Gallery\" WIDTH=\"600\" HEIGHT=\"1\"  vspace=\"25\">\n"
				"</TD></TR> \n"
				"</TABLE>\n";
			
		}

	}



	DrawGalleryFeaturedSection();

}

	// Draw a heading for an item list.
	// color is the color the box should be --
	// we use "#cccccc" for normal,
	// "#99CC00" for featured
	// "#FF9999" for hot, but
	// we do that logic elsewhere.

void clsDraw::DrawItemsHeading(const char *color)
{
	*mpStream << 

	"<table width=\"100%\" cellpadding=0 border=0 cellspacing=1 bgcolor=\"#FFFFFF\">\n"
	"<tr valign=middle bgcolor=\"#FFFFFF\">\n"
		"<td align=center valign=top width=\"80\" bgcolor=\""
		      << color
			  << "\">\n"
		"<img src=\""
		<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		<< "home/spacer.gif\" width=80 height=1 alt=\"\" border=\"0\"><br>\n"
		"<b>Status</b><br>\n"
		"</td>\n"
		"<td align=center valign=top width=\"60%\" bgcolor=\""
		      << color
			  << "\">\n"
		"<b>Item</b><br>\n"
		"</td>\n"
		"<td align=center valign=top width=\"12%\" bgcolor=\""
		      << color
			  << "\">\n"
		"<b>Price</b><br>\n"
		"</td>\n"
		"<td align=center valign=top width=\"6%\" bgcolor=\""
		      << color
			  << "\">\n"
		"<b>Bids</b><br>\n"
		"</td>\n"
		"<td align=center valign=top  width=\"15%\" bgcolor=\""
		      << color
			  << "\">\n"
		"<b>Ends PDT</b><br>\n"
		"</td>\n"
	"</tr>\n"
	"</table>\n";

	return;

}


/*
void clsDraw::DrawItemsHeading(const char *color)
{
	*mpStream << "<table border=1 cellspacing=0 width=\"100%\" bgcolor=\""
		      << color
			  << "\"><tr><td align=center valign=top width=\"62%\"><font size=2>" 
			 	 "<strong>Item</strong></font></td>\n"
				 "<td align=center valign=top width=\"12%\"><font size=2>" 
				 "<strong>Price</strong></font></td>\n"
				 "<td align=center width=\"6%\"><font size=2>" 
				 "<strong>Bids</strong></font></td>\n"
				 "<td align=center valign=top width=\"15%\"><font size=2>" 
				 "<strong>Ends "
			  << mpTimeName
			  << "</strong></font></td></tr></table>\n";
	return;
}
*/


// Draw the category search block,
// which allows all or category specific searches with a checkbox.
void clsDraw::DrawCategorySearchBlock()
{
	*mpStream << "<FORM ACTION=\""
//				 "http://search.ebay.com/cgi-bin/texis/ebay/results.html"
// kakiyama 08/03/99
			  << mpMarketPlace->GetSearchPath()
			  << "texis/ebay/results.html"
//			  << mpSearchLink
			  << "\" METHOD=\"GET\">\n"
				 "<font size=\"-1\">\n"
				 "<INPUT TYPE=TEXT NAME=query SIZE=20 MAXLENGTH=100 VALUE=\"\">\n"
				 "<INPUT TYPE=SUBMIT VALUE=Search>\n"
				 "<INPUT TYPE=hidden NAME=CategoryID VALUE=\"\">";

	// We don't do this for the top level category, since it would
	// be extraneous and silly.
	if (mpCategory->categoryNumber)
	{
		*mpStream << "<br><INPUT TYPE=checkbox name=category"
				  << int(mpCategory->categoryLevel - 1)
				  << " value="
				  << mpCategory->categoryNumber
				  << ">Search only in <b>";

		if (mpCategory->parentCategory)
		{
			*mpStream << mpData->GetTitle(mpData->GetCategory(mpCategory->parentCategory))
					  << " : ";
		}

		*mpStream << mpData->GetTitle(mpCategory)
				  << "</b>\n";
	}

	*mpStream << "<br><input type=checkbox name=srchdesc value=\"y\">Search within titles "
			     "<strong>and</strong> descriptions";

	*mpStream << "</font><INPUT TYPE=HIDDEN NAME=\"maxRecordsReturned\" value=\"300\">\n"
			     "<INPUT TYPE=hidden NAME=\"maxRecordsPerPage\" VALUE=\"100\">\n"
				 "<INPUT TYPE=hidden NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n"
				 "</form></td></tr></table>\n";

/*	*mpStream << "<FORM ACTION=\""
				 "http://mudpuppy.ebay.com/scripts/ebaySearch/search.idq\" METHOD=\"GET\">\n"
				 "<font size=\"-1\">\n"
				 "<INPUT TYPE=TEXT NAME=TextRestriction SIZE=20 MAXLENGTH=100 VALUE=\"\">\n"
				 "<INPUT TYPE=SUBMIT VALUE=\"Use Old Search\">\n";

	*mpStream << "<INPUT TYPE=hidden NAME=CategoryId VALUE=\"\">\n";

	// We don't do this for the top level category, since it would
	// be extraneous and silly.
	if (mpCategory->categoryNumber)
	{
		*mpStream << "<br><input type=checkbox name=CategoryID value="
				  << mpCategory->categoryNumber
				  << ">Search only in <b>";

		if (mpCategory->parentCategory)
		{
			*mpStream << mpData->GetTitle(mpData->GetCategory(mpCategory->parentCategory))
					  << " : ";
		}

		*mpStream << mpData->GetTitle(mpCategory)
				  << "</b>\n";
	}

	*mpStream << "</font>\n"
				 "<INPUT TYPE=HIDDEN NAME=\"HTMLQueryForm\" VALUE=\"/scripts/ebaySearch/search.htm\">\n"
				 "<INPUT TYPE=hidden NAME=\"SortOrder\" VALUE=\"[a]\">\n"
				 "<INPUT TYPE=hidden NAME=\"maxRecordsPerPage\" VALUE=75>\n"
				 "<INPUT TYPE=hidden NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n"
				 "<INPUT TYPE=hidden NAME=\"whichIndex\" VALUE=\"current\">\n"
				 "</form>"; 
	*/
}

// Jump to the anchor.
void clsDraw::DrawJumpSection(int featureType)
{
	int i, num;

	// We don't jump for leaves for page 1.
	if (mpCategory->isLeaf || mCurrentPage > 1 )
		return;

	// Get the item count.
	num = mpData->GetItems(mppItems, &i, mpCategory->categoryNumber,
		(entryTypeEnum)featureType, (listingTypeEnum) mCurrentListingType,
		0, 1);

	// We don't jump if we don't have any items.
	if (!i)
		return;

	if (mpCategory->categoryNumber)
	{
		*mpStream << "<p align=center><small><a href=\"#eBayListings\">"
				  << mppJumpStrings[mCurrentListingType]
				  << " in "
				  << mpData->GetTitle(mpCategory)
				  << "</A></small></p>\n";
	}
	else
	{
		*mpStream << "<p align=center><small><a href=\"#eBayListings\"><strong>"
				  << mppJumpStrings[mCurrentListingType]
				  << "</strong></a></small></p>\n";
	}
	mJumpAlreadyDraw = true;
}


// This function randomly picks 7 featured items and draw them, and provides a 
// link to the featured items page for that category.
void clsDraw::DrawSomeRandomFeaturedItems(int category)
{
	// Find the Grandparent category 1st, toys for example, then we know which featured icon to display
	int indexCategory = GetCategoryIndex(category);
	const char* featureIcon = NULL;

	switch (indexCategory)
	{
	case 353:	// antiques
		featureIcon = "ant-wtitle-fea.gif";
		break;
	case 160:	// computers
		featureIcon = "com-wtitle-fea.gif";
		break;
	case 237:	// dolls-figures
		featureIcon = "dol-wtitle-fea.gif";
		break;
	case 281:	// jewelry-gemstones
		featureIcon = "jwl-wtitle-fea.gif";
		break;
	case 1047:	// photo-electronics
		featureIcon = "pho-wtitle-fea.gif";
		break;
	case 870:	// pottery-glass
		featureIcon = "pot-wtitle-fea.gif";
		break;
	case 888:	// sports-memorabilia
		featureIcon = "spo-wtitle-fea.gif";
		break;
	case 220:	// toys-beanies
		featureIcon = "toy-wtitle-fea.gif";
		break;
	case 266:	// books-movies-music
		featureIcon = "boo-wtitle-fea.gif";
		break;
	case 866:	// coins-stamps
		featureIcon = "coi-wtitle-fea.gif";
		break;
	case 1:		// collectibles
		featureIcon = "col-wtitle-fea.gif";
		break;
	case 99:	// miscellaneous
		featureIcon = "mis-wtitle-fea.gif";
		break;
	default:	// We default to miscellaneous so something shows
		featureIcon = "mis-wtitle-fea.gif";
		break;
	}

	int i, numToShow;
	// TODO - fix?
	char * featurePath = "/aw/listings/list/featured/category";

	int numItems = mpData->GetRandomItems((listingTypeEnum) mCurrentListingType,
						   featuredEntry,
						   category,
						   mNumRandomFeatureItems,
						   mppRandomFeatureItems);

	if (numItems > 0)
	{

		numToShow = (numItems > mNumRandomFeatureItems)? mNumRandomFeatureItems : numItems;

		*mpStream << "<br>";

		// featured title bar
		*mpStream	<< "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
		*mpStream	<< "<tr>\n"
//					<< "<td><img src=\"http://pics.ebay.com/aw/pics/cat/"
				    << "<td><img src=\""
					<< mpMarketPlace->GetPicsPath()
					<< "cat/"
					<< featureIcon
					<< "\" "
						"border=0 alt=\"Featured Auctions\"><br>\n"
					<< "</td>\n"
					<< "</tr>\n"
					<< "</table>\n";

		// draw the heading
		DrawItemsHeading("#99CC00");

		// draw the items
		for (i = 0; i < numToShow; ++i)
			DrawOneEntryItem(mppRandomFeatureItems[i], i);

		// "all featured..." link
		*mpStream	<< "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
		*mpStream	<< "<tr>\n"
					<< "<td><font size=\"2\" face=\"arial,helvetica\">"
					<< "click "
					<< "<a href=\""
					<< featurePath
					<< category 
					<< "\"><b>here</b></a> "
					<< "to see <b>all featured items</b> in this category"
					<< "</font><br>"
					<< "</td>\n"
					<< "</tr>\n"
					<< "</table>\n";

		*mpStream		<< "<hr><br>";


	}
	else // There are no featured items in this listing type
	{

		switch (mCurrentListingType)
		{
		case CurrentListingType: // The normal case.
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		case NewListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items which are new today in this category. Click <a href=\""
						<< featurePath
						<< category 
						<< "\">here</a> for all featured items in this category.</font>";
			break;

		case EndingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items ending today in this category. Click <a href=\""
						<< featurePath
						<< category 
						<< "\">here</a> for all featured items in this category.</font>";
			break;

		case GoingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items which are going, going, gone in this category. Click <a href=\""
						<< featurePath
						<< category 
						<< "\">here</a> for all featured items in this category.</font>";
			break;

		default:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		}

		*mpStream		<< "<br><hr>";

	}
}


// A recursive function
// to draw categories for our 'overview' page
// which lists every extant category.
void clsDraw::DrawCategoryForOverView(categoryEntry *pCategory,
						              int level, bool gallery, int featureType)
{
	if (level == 1)
	{
		// If we've 'filled up' the column.
		if (mCurrentLineNo >= mMaxLinesInOverView &&
			(mCurrentColumn < mMaxColumnsInOverView))
		{
			*mpStream << "</td><td align=left valign=top>\n";
			++mCurrentColumn;
			mCurrentLineNo = 1;
		}
		else if (mCurrentLineNo != 1)
			*mpStream << "<hr width=\"100%\">";
	}

	++mCurrentLineNo;

	if (!mDrawingOverviewForNumbers)
		DrawCategoryLink(pCategory->categoryNumber,
			CurrentListingType, 1, 
			(pCategory->isAdult && !mpCategory->isAdult), false, gallery, featureType);

	if (pCategory->isLeaf)
	{
		if (mDrawingOverviewForNumbers)
		{
			*mpStream << "<font size=\"-1\"> (#"
					  << pCategory->categoryNumber
					  << ") "
					  << mpData->GetTitle(pCategory)
					  << "</font><br>\n";
		}
		else
		{
			*mpStream << "<font size=\"-1\">"
					  << mpData->GetTitle(pCategory)
					  << " ("
					  << pCategory->numCurrent
					  << ")</font></a><br>\n";
		}
	}
	else
	{
		*mpStream << mpData->GetTitle(pCategory)
				  << "</a><br>\n";

		// And print the children.
		// We walk through the children by getting
		// the 'first child' and then using his
		// right sibling, ad nihil. This gets all
		// of them, as all children of a parent
		// are siblings.
		if (pCategory->firstChild && level < mMaxDepth)
		{
			*mpStream << "<UL>";

			pCategory = mpData->GetCategory(pCategory->firstChild);

			while (pCategory)
			{
				DrawCategoryForOverView(pCategory, level + 1, gallery, featureType);

				if (pCategory->rightSibling)
					pCategory = mpData->GetCategory(pCategory->rightSibling);
				else
					pCategory = NULL;
			}

			*mpStream << "</UL>";
		}
	}
}

// Recursively draw categories for the listing index page.
// We limit our depth based on mMaxDepth which right now
// means we only print the first two levels for the 
// top category, and everything for everything else.
void clsDraw::DrawCategoryForListing(categoryEntry *pCategory, 
									 int level, bool gallery, int featureType)
{
	int i, j, numItems;

    if (!mDrawingUsers)
    {
	    // Get the item count.
	    numItems = mpData->GetItems(NULL, &j, pCategory->categoryNumber,
		    (entryTypeEnum)featureType, (listingTypeEnum) mCurrentListingType,
		    1, 0);
    }
    else
    {
//        mpData->GetUsers(&i, &numItems, pCategory->categoryNumber, 1, 0);
    }

	if (level == 1)
	{
		// Find out if we've filled the column.
		if (mCurrentLineNo > mMaxLinesInOverView &&
			(mCurrentColumn < mMaxColumnsInOverView))
		{
			*mpStream << "</td><td width=\"33%\" align=left valign=top>\n";
			++mCurrentColumn;
			mCurrentLineNo = 1;
		}
		else if (mCurrentLineNo != 1 && !pCategory->isLeaf)
			*mpStream << "<p>";

		*mpStream << "<strong>";

		DrawCategoryLink(pCategory->categoryNumber,
			mCurrentListingType,
			1,
			(pCategory->isAdult && !mpCategory->isAdult), false, gallery, featureType);

		*mpStream << mpData->GetTitle(pCategory)
				  << " ("
				  << numItems
				  << ")</a>"
					 "</strong><br>\n";

	}
	else
	{
		for (i = 0; i < (level - 1) * 4; ++i)
			*mpStream << "&nbsp;";

		*mpStream << "<font size=2>";

		DrawCategoryLink(pCategory->categoryNumber,
			mCurrentListingType,
			1,
			(pCategory->isAdult && !mpCategory->isAdult), false, gallery, featureType);

		*mpStream << mpData->GetTitle(pCategory)
				  << " ("
				  << numItems
				  << ")</a>"
					 "</font><br>\n";
	}
	++mCurrentLineNo;

	// And recursively do the children.
	// We walk through the children by getting
	// the 'first child' and then using his
	// right sibling, ad nihil. This gets all
	// of them, as all children of a parent
	// are siblings.
	if (pCategory->firstChild && level < mMaxDepth)
	{
		pCategory = mpData->GetCategory(pCategory->firstChild);

		while (pCategory)
		{
			DrawCategoryForListing(pCategory, level + 1, gallery, featureType);

			if (pCategory->rightSibling)
				pCategory = mpData->GetCategory(pCategory->rightSibling);
			else
				pCategory = NULL;
		}

		*mpStream << "<P>";
	}

	*mpStream << "\n";
}

// This prints the page with links to the item listing page.
bool clsDraw::CategorySelection(bool gallery)
{
    categoryEntry *pCategory;

	const char * sAds;

    *mpStream << "HTTP/1.0 200 OK\r\n"
              << mHTTPHeader << "\r\n";

    *mpStream << "<html><head><title>"
              << mpMarketName
              << " Listings: Category Selection</title>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "</head>\n"
			  << mpHeaderCategory;

	// ad
	sAds = mpTemplates->GetAdsCategory(mPartner, 0);
	if (mpAdCategory != sAds) // Determine if it is a real ad
		*mpStream << sAds;

	*mpStream << "<center><h2>Choose a Category in which to list your item</h2></center>\n";

	*mpStream << "<p><table border=1 width=\"100%\">\n<tr><td align=left valign=top>\n";

	// Are we drawing the category overview to get the numbers of the categories?
	mDrawingOverviewForNumbers = false;
    mDrawingForItemListing = true;

	try
	{
		// We're always listing 'current' for the overview.
		mCurrentListingType = CurrentListingType;
		// List all categories
		mMaxDepth = 10;
		mMaxColumnsInOverView = 3;
		// Try to split the columns evenly.
		mMaxLinesInOverView = mpData->GetNumberOfCategories() / mMaxColumnsInOverView;

		mCurrentColumn = 1;
		mCurrentLineNo = 1;
		mCurrentCategory = 0;
		mNumPages = 0;
		mCurrentPage = 1;

		mpCategory = mpData->GetCategory(0);

		pCategory = mpData->GetCategory(mpCategory->firstChild);

		// Walk through the top level categories and print them.
		// We walk through the children by getting
		// the 'first child' and then using his
		// right sibling, ad nihil. This gets all
		// of them, as all children of a parent
		// are siblings.
		while (pCategory)
		{
			DrawCategoryForOverView(pCategory, 1, gallery, (int)normalEntry);

			if (pCategory->rightSibling)
				pCategory = mpData->GetCategory(pCategory->rightSibling);
			else
				pCategory = NULL;
		}

		*mpStream << "</td>";

		// And fill in the empty columns.
		for ( ; mCurrentColumn < mMaxColumnsInOverView; ++mCurrentColumn)
		{
			*mpStream << "<td width=\"33%\"></td>";
		}

		*mpStream << "</tr></table></p>\n";

		*mpStream << mpFooterCategory;
		*mpStream << "</body></html>";
		*mpStream << flush;
	}
	catch (...)
	{
		mDrawingForItemListing = false;
		throw;
	}

	mDrawingForItemListing = false;
	return true;
}

// This prints the entire over view page.
bool clsDraw::CategoryOverView(bool isForNumbers, bool gallery)
{
	categoryEntry *pCategory;

	const char * sAds;

    mDrawingUsers = false;

    // Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	*mpStream << "<html><head><title>"
			  << mpMarketName
			  << " Listings: Category Overview</title>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "</head>\n"
			  << mpHeaderCategory;
	
	// ad
	sAds = mpTemplates->GetAdsCategory(mPartner, 0);
	if (mpAdCategory != sAds) // Determine if it is a real ad
		*mpStream << sAds;


	*mpStream << "<center><h2>eBay Category Overview</h2></center>\n";

	*mpStream << "<p><table border=1 width=\"100%\">\n<tr><td align=left valign=top>\n";

	// Are we drawing the category overview to get the numbers of the categories?
	mDrawingOverviewForNumbers = isForNumbers;

	// We're always listing 'current' for the overview.
	mCurrentListingType = CurrentListingType;
	// List all categories
	mMaxDepth = 10;
	mMaxColumnsInOverView = 3;
	// Try to split the columns evenly.
	mMaxLinesInOverView = mpData->GetNumberOfCategories() / mMaxColumnsInOverView;

	mCurrentColumn = 1;
	mCurrentLineNo = 1;
	mCurrentCategory = 0;
	mNumPages = 0;
	mCurrentPage = 1;

	mpCategory = mpData->GetCategory(0);

	pCategory = mpData->GetCategory(mpCategory->firstChild);

	// Walk through the top level categories and print them.
	// We walk through the children by getting
	// the 'first child' and then using his
	// right sibling, ad nihil. This gets all
	// of them, as all children of a parent
	// are siblings.
	while (pCategory)
	{
		DrawCategoryForOverView(pCategory, 1, gallery, (int)normalEntry);

		if (pCategory->rightSibling)
			pCategory = mpData->GetCategory(pCategory->rightSibling);
		else
			pCategory = NULL;
	}

	*mpStream << "</td>";

	// And fill in the empty columns.
	for ( ; mCurrentColumn < mMaxColumnsInOverView; ++mCurrentColumn)
	{
		*mpStream << "<td width=\"33%\"></td>";
	}

	*mpStream << "</tr></table></p>\n";

	*mpStream << mpFooterCategory;
    *mpStream << "</body></html>";
	*mpStream << flush;

	return true;
}

//
// Prints the pervert page.
//
bool clsDraw::Adult(int category, 
                    int type,
					int featureType,
                    int page, 
                    int prevcategory)
{

	const char * sAds;

    // Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

    *mpStream << "<html><head><title>eBay Adult Only Information"
				 "</title></head>\n"
				<< mpHeaderCategory;

	// ad
	sAds = mpTemplates->GetAdsCategory(mPartner, 0);
	if (mpAdCategory != sAds) // Determine if it is a real ad
		*mpStream << sAds;


	*mpStream << mpAdultText;

	// Draw the continuation link.
	*mpStream << "View ";

	// We don't link the to the gallery form of the adult category 
	DrawCategoryLink(category, (listingTypeEnum) type, page, false, false, (int)featureType);

	*mpStream << mpData->GetTitle(mpData->GetCategory(category));

	*mpStream << "</a> listings\n<p>";

	// We don't link the to the gallery form of the adult category 
	DrawCategoryLink(prevcategory, (listingTypeEnum) type, page, false, false, (int)featureType);

	*mpStream << "Go back to "
			  << mpData->GetTitle(mpData->GetCategory(prevcategory))
			  << "</a>\n";

	*mpStream << mpFooterCategory;
    *mpStream << "</body></html>";
	*mpStream << flush;

	return true;
}

// Draws the featured section
// which includes:
// the featured heading
// the green items heading
// all featured items
void clsDraw::DrawFeaturedSection()
{

	int i, j;
	int start;

	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * mNumItemsPerPage;

	mpData->GetItems(mppItems,
		&i, mCurrentCategory, featuredEntry,
		(listingTypeEnum) mCurrentListingType,
		start, start + mNumItemsPerPage - 1);

    if (!i)
        return;

	//DrawFeaturedHeading();
	DrawItemStatusIcons();
	DrawItemsHeading("#99CC00");

    // And each item.
	for (j = 0; j < i; ++j)
		DrawOneEntryItem(mppItems[j], j);

	return;



/*

	// i is the number of items returned.
	// we get them in sections because we don't
	// know how many we have, and we need all
	// of them. Once i is 0, we've run out.
	while (i)
	{
		
		mpData->GetItems(mppItems,
			&i, mCurrentCategory, featuredEntry,
			(listingTypeEnum) mCurrentListingType,
			start, start + mNumItemsPerPage - 1);
		start += mNumItemsPerPage;

		if( itemHeadingNotDrawed && i )
		{
			DrawItemsHeading("#99CCCC");
			itemHeadingNotDrawed = false;
		}
		for (j = 0; j < i; ++j)
			DrawOneEntryItem(mppItems[j], j);
	}

	return;
*/
}

void clsDraw::DrawFeaturedSection(int category)
{
	// Find the Grandparent category 1st, toys for example, then we know which featured icon to display
	int indexCategory = GetCategoryIndex(category);

	int i, j;
	int start;

	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * mNumItemsPerPage;

	mpData->GetItems(mppItems,
		&i, mCurrentCategory, featuredEntry,
		(listingTypeEnum) mCurrentListingType,
		start, start + mNumItemsPerPage - 1);

	// TODI - fix?
	char * featurePath = "/aw/listings/list/featured/category";


	// featured title bar
	*mpStream	<< "<br>\n";

    if (i > 0)
	{
		// draw the status icons
		DrawItemStatusIcons();

		//DrawFeaturedHeading();
		DrawItemsHeading("#99CC00");

		// And each item.
		for (j = 0; j < i; ++j)
			DrawOneEntryItem(mppItems[j], j);
	}
	else // There are no featured items in this listing type
	{

		switch (mCurrentListingType)
		{
		case CurrentListingType: // The normal case.
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		case NewListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category which are new today.</font>";
			break;

		case EndingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category ending today.</font>";
			break;

		case GoingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category which are ending in five hours.</font>";
			break;

		default:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		}
	}

	*mpStream		<< "<br><hr><br>";

	return;
}


// This includes all featured items in that category, includes all pages
void clsDraw::DrawFeaturedSectionAllPages(int category)
{
	// Find the Grandparent category 1st, toys for example, then we know which featured icon to display
	int indexCategory = GetCategoryIndex(category);

	int i, j;
	int start;

	bool itemHeadingNotDrawed = true;
	start = 0;
	i = 1;

	// TODO - fix?
	char * featurePath = "/aw/listings/list/featured/category";

	if (mJumpAlreadyDraw)
		*mpStream << "<a name=\"eBayListings\">&nbsp;</a>";

	// featured title bar
	*mpStream	<< "<br>\n";

	*mpStream	<< "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	*mpStream	<< "<tr>\n"
				<<	"<td><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"4\""
				<<	"color=\"#669900\"><b>Featured Auctions";
	
	if (mpCategory->categoryNumber)
        *mpStream << " in "
				  << mpData->GetTitle(mpCategory);
		
	*mpStream	<< "</b></font>"
				<< "</td>\n"
				<< "</tr>\n"
				<< "</table>\n";

	// i is the number of items returned.
	// we get them in sections because we don't
	// know how many we have, and we need all
	// of them. Once i is 0, we've run out.
	while (i)
	{
		
		mpData->GetItems(mppItems,
			&i, mCurrentCategory, featuredEntry,
			(listingTypeEnum) mCurrentListingType,
			start, start + mNumItemsPerPage - 1);
		start += mNumItemsPerPage;

		if( itemHeadingNotDrawed && i )
		{
			// draw the status icons
			DrawItemStatusIcons();

			//DrawFeaturedHeading();
			DrawItemsHeading("#99CC00");

			itemHeadingNotDrawed = false;
		}
		for (j = 0; j < i; ++j)
			DrawOneEntryItem(mppItems[j], j);
	}

    if (itemHeadingNotDrawed) // There are no featured items in this listing type
	{

		switch (mCurrentListingType)
		{
		case CurrentListingType: // The normal case.
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		case NewListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category which are new today.</font>";
			break;

		case EndingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category ending today.</font>";
			break;

		case GoingListingType:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category which are ending in five hours.</font>";
			break;

		default:
			*mpStream	<< "<br>"
						<< "<font size=\"2\">There are no featured items in this category.</font>";
			break;

		}
	}

	*mpStream		<< "<br>"
					   "<font size=\"2\">"
					   "To find out how to be listed in this section and seen by thousands, please visit this link "
//					   "<a href=\"http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?Featured\">"
					   "<a href=\""
					<< mpMarketPlace->GetCGIPath(PageFeatured)
					<< "eBayISAPI.dll?Featured\">"
					   "Featured Auctions</a>"
					   "</font>";

	*mpStream		<< "<br><hr>";

	return;
}

// Draws the hot section
// which includes:
// the hot heading
// the red items heading
// all hot items
void clsDraw::DrawHotSection()
{
	int i, j;
	int start;

	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * mNumItemsPerPage;

	mpData->GetItems(mppItems,
		&i, mCurrentCategory, hotEntry,
		(listingTypeEnum) mCurrentListingType,
		start, start + mNumItemsPerPage - 1);

    if (!i)
        return;

//	DrawHotHeading();               

	// draw status icon
	DrawItemStatusIcons();				

	// use table format for the heading
/*
	*mpStream << "<table border=\"1\" cellspacing=\"0\" width=\"100%\" bgcolor=\""
				 "#FF9999\"><tr><td align=\"center\"><font size=4 face=\"arial,helvetica\"><strong>";
	
	if (mpCategory->categoryNumber)
		*mpStream << "Hot Items in "
				  << mpData->GetTitle(mpCategory);
	
	else
		*mpStream << "<a name=hot>Hot Items</A>\n"
		"\n";
	
	*mpStream << "</strong></font></td></tr></table>\n";
*/
	DrawItemsHeading("#FF9999");	

    // And each item.
	for (j = 0; j < i; ++j)
		DrawOneEntryItem(mppItems[j], j);

	return;
}


// This includes all hot items in the current category, not paginated
void clsDraw::DrawHotSectionAllPages()
{
	int i, j;
	int start;

	// the first page is 1, but we start at item 0.
	start = 0;

	if (!mpData->GetItems(mppItems,
		&i, mCurrentCategory, hotEntry,
		(listingTypeEnum) mCurrentListingType,
		1, 0))
		return;

	*mpStream	<< "<br><hr><br>";

	DrawHotHeading();               
	// draw status icon
	DrawItemStatusIcons();				
	DrawItemsHeading("#FF9999");	

	i = 1;

	// i is the number of items returned.
	// we get them in sections because we don't
	// know how many we have, and we need all
	// of them. Once i is 0, we've run out.
	while (i)
	{
		mpData->GetItems(mppItems,
			&i, mCurrentCategory, hotEntry,
			(listingTypeEnum) mCurrentListingType,
			start, start + mNumItemsPerPage - 1);
		start += mNumItemsPerPage;

		for (j = 0; j < i; ++j)
			DrawOneEntryItem(mppItems[j], j);
	}

	return;
}

//#if 0
// Draws the items section
// which includes 25 items (mNumItemsPerPage)
// and the grey items heading.
// We don't do the 'header' here since it's
// not used on pages 2+
void clsDraw::DrawItemsSectionText()
{
	int i, j;
	int start;

	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * mNumItemsPerPage;

    // We get the items first, so that we can just
    // not draw if we don't have any.
	mpData->GetItems(mppItems,
		&i, mCurrentCategory, normalEntry,
		(listingTypeEnum) mCurrentListingType,
		start, start + mNumItemsPerPage - 1);

    if (!i)
        return;

//	*mpStream << "<p>";

    // Draw the heading
	DrawItemsHeading("#CCCCCC");

    // And each item.
	for (j = 0; j < i; ++j)
		DrawOneEntryItem(mppItems[j], j);

	return;
}

// Draws the users section
// #endif

int clsDraw::MakePath(int key, char* destination)
{
	strcpy(destination, mpImagesURL);

	int end = strlen(destination);

	// Make sure we have room for the key path and key file name
	if (end > (kMaxImagesURL - 100))
		return -1;

	char numBuff[20];

	itoa(key, numBuff, 10);
	strcat(destination, numBuff);
	strcat(destination, ".jpg");

	return 0;
}

#include <ctype.h>

// This is a font width table for times 12 on windows
// The numbers are in pixels
// This is to support the line breaking routine DrawTextWithPointBreaks
static int gFontWidthTable[256] = {	
	5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 
	5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 
	4, 4, 6, 8, 8, 13, 12, 4, 5, 5, 7, 9, 4, 5, 4, 5, 
	8, 8, 8, 8, 8, 8, 8, 8, 8, 8, 4, 4, 9, 9, 9, 7, 
	15, 11, 11, 11, 12, 10, 9, 12, 12, 5, 6, 11, 10, 15, 12, 12, 
	9, 12, 10, 9, 9, 12, 11, 15, 11, 11, 10, 5, 5, 5, 7, 8, 
	5, 7, 8, 7, 8, 8, 5, 8, 8, 4, 4, 8, 4, 12, 8, 8, 
	8, 8, 5, 6, 4, 8, 8, 12, 8, 8, 7, 7, 3, 7, 9, 5, 
	5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 
	5, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 
	4, 4, 8, 8, 8, 8, 4, 8, 5, 13, 4, 8, 9, 5, 13, 8, 
	5, 9, 4, 4, 5, 10, 7, 4, 5, 4, 5, 8, 11, 11, 11, 7, 
	11, 11, 11, 11, 11, 11, 14, 11, 10, 10, 10, 10, 5, 5, 5, 5, 
	12, 12, 12, 12, 12, 12, 12, 9, 12, 12, 12, 12, 12, 11, 9, 8, 
	7, 7, 7, 7, 7, 7, 11, 7, 8, 8, 8, 8, 4, 4, 4, 4, 
	8, 8, 8, 8, 8, 8, 8, 9, 8, 8, 8, 8, 8, 8, 8, 8, 
};

// This gets the next translated token from an html string
// HTML can have escaped characters so to do accurate
// line breaking we need to translate those escape sequences
// into actual characters. Called by DrawTextWithPointBreaks.
static int GetToken(const char*& text, const char* end)
{
	if (text >= end)
		return -1;

	if (*text != '&')
		return *text++;

	int length = end - text;
	if (length < 3)
		return -1;

	++text;

	long value;

	if (*text == '#' && length > 3)
	{
		if ((*(text+1) == 'x' || *(text+1) == 'X')
			&& (unsigned char)*(text+2) < 127
			&& isxdigit((unsigned char)*(text+2)))
		{
			text += 2;
			value = strtol(text, const_cast<char**>(&text), 16);
		}
		else if ((unsigned char)*(text+1) < 127
			&& isdigit(*(text+1)))
		{
			text += 1;
			value = strtol(text, const_cast<char**>(&text), 10);
		}
		else
		{
			return -1;
		}
	}
	else if ((unsigned char)*text < 127
		&& isalpha(*text))
	{
		if (length >= 3 && !strcmp(text, "lt"))
		{
			value = '<';
			text += 2;
		}
		else if (length >= 3 && !strcmp(text, "gt"))
		{
			value = '>';
			text += 2;
		}
		else if (length >= 4 && !strcmp(text, "amp"))
		{
			value = '&';
			text += 3;
		}
		else if (length >= 5 && !strcmp(text, "quot"))
		{
			value = '"';
			text += 4;
		}
		else
		{
			return '&';
		}
	}
	else
	{
		return -1;
	}

	if (*text == ';')
		++text;

	return value;
}


// To get the columns of seperate tables to line up we insert
// <wbr> tags into long words that might be wider than a cell.
// breakWidth is the widest we want any word to be in pixels
//  if the word is wider than we insert a <wbr> in it
// widthTable is an array of 256 ints that are the pixel widths
//  of the characters
static void DrawTextWithPointBreaks(ostream& outStream,
									const char* text, 
									int breakWidth, 
									const int* widthTable)
{
	const char* next = text;
	const char* end = text + strlen(text);

	while (next < end)
	{
		// If we have a space just output it and go on
		if (isspace(*next))
		{
			outStream << *next++;
			continue;
		}

		// Found a regular character so we measure to the next breakpoint
		const char* endPoint = next;
		int accumulatedWidth = 0;
		bool forcedBreak = false;
		const char* lastBestBreakPoint = 0;

		while (endPoint < end)
		{
			int c = GetToken(endPoint, end);
			if (c == -1)
				break;

			if (isspace(c))
				break;

			int charWidth = widthTable[c];
			if ((charWidth + accumulatedWidth) > breakWidth)
			{
				if (lastBestBreakPoint)
					endPoint = lastBestBreakPoint;

				forcedBreak = true;
				break;
			}

			if (!isalpha(c))
				lastBestBreakPoint = endPoint;

			accumulatedWidth += charWidth;
		}

		// Output the text
		while (next < endPoint)
		{
			outStream << *next++;
		}

		if (forcedBreak)
			outStream << ' ';
//			outStream << "<WBR>";
	}
}

void clsDraw::InsertSpace(int rows)
{
	int i;
	*mpStream << "<TABLE WIDTH=\"110\" CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"> \n";

	for (i = 0; i < rows; i++)
	{
		*mpStream << "<TR><TD COLSPAN=\"6\">&nbsp;</TD></TR> \n";
	}

	*mpStream << "</TABLE> \n";
}

#if 0
// Draws all the gallery items for this section
void clsDraw::DrawItemsSectionGallery()
{
	int lastItem;
	int start;
	int row;
	int nextItem;
	int numWanted;

	const int kMaxItemsPerRow  = 5;
	const int kMaxRows		   = 6;
	const int kMaxItemsPerPage = kMaxItemsPerRow * kMaxRows;

	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * kMaxItemsPerPage;

    // We get the items first, so that we can just
    // not draw if we don't have any.
	mpData->GetItems(mppItems,
		&lastItem, mCurrentCategory, normalEntry,
		(listingTypeEnum) GalleryListingType,
		start, start + kMaxItemsPerPage - 1);

    if (!lastItem)
        return;

	lastItem  = lastItem > kMaxItemsPerPage ? kMaxItemsPerPage : lastItem; 
	nextItem  = 0;
	row       = 0;
	numWanted = kMaxItemsPerRow;

	while (nextItem < lastItem && row < kMaxRows)
	{
		if (nextItem + numWanted - 1 > lastItem)
			numWanted = lastItem - nextItem;

		DrawGallery1Row(numWanted, kMaxItemsPerRow, nextItem, false);

		nextItem += numWanted;

		if (nextItem < lastItem - 1)
		{
			InsertSpace(3);
		}

		row++;
	}
}
#else
// Draws all the gallery items for this section
void clsDraw::DrawItemsSectionGallery()
{
	const int kMaxItemsPerRow  = 5;
	const int kMaxRows		   = 6;
	const int kMaxItemsPerPage = kMaxItemsPerRow * kMaxRows;

	// the first page is 1, but we start at item 0.
	int start = (mCurrentPage - 1) * kMaxItemsPerPage;

    // We get the items first, so that we can just
    // not draw if we don't have any.
	int itemsToRender;

	mpData->GetItems(mppItems,
		&itemsToRender, mCurrentCategory, normalEntry,
		(listingTypeEnum) GalleryListingType,
		start, start + kMaxItemsPerPage - 1);

    if (!itemsToRender)
        return;

	if (itemsToRender > kMaxItemsPerPage)
		itemsToRender = kMaxItemsPerPage;

	int renderedSoFar = 0;
	int nextItem = 0;

	while (renderedSoFar < itemsToRender)
	{
		int itemsLeftToRender = itemsToRender - renderedSoFar;
		int renderThisPass = min(kMaxItemsPerRow, itemsLeftToRender);
		
		DrawGallery1Row(renderThisPass, kMaxItemsPerRow, nextItem, false);

		itemsToRender -= renderThisPass;
		nextItem += renderThisPass;

		if (renderThisPass)
		{
			InsertSpace(3);
		}
	}
}
#endif

// Draws a text style items section
// which includes 25 items (mNumItemsPerPage)
// and the grey items heading.
// We don't do the 'header' here since it's
// not used on pages 2+
#if 0
void clsDraw::DrawItemsTextSection()
{

	int i, j;
	int start;
    userEntry *pUsers;
	// the first page is 1, but we start at item 0.
	start = (mCurrentPage - 1) * mNumItemsPerPage;

    pUsers = mpData->GetUsers(&i, &j, mCurrentCategory,
        start, start + mNumItemsPerPage - 1);

    if (!i)
        return;

    DrawAllHeadingSection();

    // Draw each user.
    for (j = 0; j < i; ++j)
        DrawOneUserPage(pUsers + j, j);

	return;
}
#endif

void clsDraw::ExtraColumns(int numCols, bool featured)
{
	int i;

	for (i = 0; i < numCols; i++)
	{
		if (featured)
			*mpStream << "<TD WIDTH=\"25%\">&nbsp;</TD> \n";
		else
			*mpStream << "<TD WIDTH=\"19%\">&nbsp;</TD> \n";
	}
}

void clsDraw::DrawOneItemImage(itemEntry *pItem, bool featured)
{

	*mpStream << "<TD WIDTH=\"";
	
	if (featured)
		*mpStream << "25%";
	else
		*mpStream << "19%";
	
	*mpStream << "\" VALIGN=\"TOP\" ALIGN=\"CENTER\"> \n"
			  << "<A HREF=\""
//			  << mpViewItemURL
// kakiyama 08/03/99
			  << mpMarketPlace->GetCGIPath(PageViewItem)
			  << "eBayISAPI.dll?ViewItem&item="
			  << pItem->itemNumber
			  << "&tc=photo"
			  << pItem->categoryNumber
			  << "\">"
                 "<IMG SRC=\"";

    if (1/*pItem->hasPicture*/) // FIXME mlh 7/9/98 needs to use auxillary db
	{
		char imageURL[kMaxImagesURL+1];

		if (!MakePath(pItem->itemNumber, imageURL))
		{
			*mpStream << imageURL;
		}
	}
	else
	{
		*mpStream << mpImagesURL
			<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
			<< "default.jpg";
	}
			  
	if (featured)
	{
		*mpStream << "\" WIDTH=\"140\" HEIGHT=\"140\" BORDER=\"0\">";
	}
	else
	{
		*mpStream << "\" WIDTH=\"96\" HEIGHT=\"96\" BORDER=\"0\">";
	}

	*mpStream << "</A>"
			     "</TD> \n";

}

void clsDraw::WriteOneItemDescription(itemEntry *pItem, bool featured, bool bGrabbag)
{
	*mpStream << "<TD WIDTH=\"";
	
	if (featured)
		*mpStream << "25%";
	else
		*mpStream << "19%";
	
	*mpStream << "\" VALIGN=\"TOP\" ALIGN=\"CENTER\"> \n"
		         "<A HREF=\""
//			  << mpViewItemURL
// kakiyama 08/03/99
			  << mpMarketPlace->GetCGIPath(PageViewItem)
			  << "eBayISAPI.dll?ViewItem&item="
			  << pItem->itemNumber
			  << "&tc=photo"
			  << pItem->categoryNumber
			  << "\">";

	if (featured)
		*mpStream << "<b>";

	DrawTextWithPointBreaks(*mpStream, mpData->GetTitle(pItem), 90, gFontWidthTable);

	if (featured)
		*mpStream << "</b>";
	
	*mpStream << "</A></FONT> \n";

	// Here are the links for the category hierarchy
	if (bGrabbag)
	{
		*mpStream	<< "<br><font face=\"Arial, Helvetica\" size=\"1\">in<br>";

		DrawGalleryTitleWithLinks(pItem->categoryNumber, 2, true);

		*mpStream	<< "</font>";
	}


	*mpStream	<< "</TD>";			     
}

void clsDraw::WriteOneItemInfo(itemEntry *pItem, bool featured)
{
	struct tm*	pEndTime;
	int bids = pItem->numBids;

	*mpStream << "<TD WIDTH=\"";
	
	if (featured)
		*mpStream << "25%";
	else
		*mpStream << "19%";
	
	*mpStream << "\" VALIGN=\"BOTTOM\" ALIGN=\"CENTER\"> \n";

	if (featured)
        *mpStream << "<TABLE WIDTH=\"145\" ";
	else
		*mpStream << "<TABLE WIDTH=\"110\" ";

	*mpStream << "CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"> \n"
				 " <TR> \n"
                 "<TD HEIGHT=\"18\"><IMG SRC=\"";

//	if (featured)
	{
		*mpStream << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		*mpStream << "feat-curr.gif\" WIDTH=\"64\" HEIGHT=\"12\"> \n";
	}
//	else
//	{
//		*mpStream << "http://pics.ebay.com/aw/pics/scurrently.gif\" WIDTH=\"48\" HEIGHT=\"9\"> \n";
//	}

	*mpStream << "</TD> \n"
                 "<TD ALIGN=\"RIGHT\"><FONT FACE=\"arial,helvetica\" color=\"006633\" SIZE=-1> \n";


	// check which currency is used here
	if (Currency_GBP == (CurrencyIdEnum)pItem->currencyID)
		*mpStream << "&pound;";
	else
		*mpStream << "$";


	*mpStream << (pItem->highBid / 100)
			  << '.'
			  << setw(2) << setfill('0') << (pItem->highBid % 100);

	/*
	if (bids > 0)
	{
		*mpStream << " ("
		          << bids
				  << ") ";
	}
	*/

	*mpStream << "</FONT></TD> \n"
                 "</TR> \n"
				 "</TABLE> \n";

	if (featured)
        *mpStream << "<TABLE WIDTH=\"145\" ";
	else
		*mpStream << "<TABLE WIDTH=\"110\" ";

	*mpStream << "CELLPADDING=\"0\" CELLSPACING=\"0\" BORDER=\"0\"> \n"  
			     "<TR> \n"
                 "<TD HEIGHT=\"18\"><IMG SRC=\"";

//	if (featured)
	{
		*mpstream << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
		*mpStream << "feat-ends.gif\" WIDTH=\"33\" HEIGHT=\"12\"> \n";
	}
//	else
//	{
//		*mpStream << "http://pics.ebay.com/aw/pics/sends.gif\" WIDTH=\"26\" HEIGHT=\"9\"> \n";
//	}

	*mpStream << "</TD> \n"
                 "<TD ALIGN=\"RIGHT\"><FONT FACE=\"arial,helvetica\" color=\"006633\" SIZE=-1> \n";

	pEndTime = localtime(&(pItem->endTime));

	// If it ends within 3 hours, it's going, so we print the time in red.
	if ((pItem->endTime - mTime) < (3600 * 5))
		*mpStream << "<font color=\"#FF0000\">";

	// Format and print the date.
	*mpStream << setw(2) << setfill('0') << pEndTime->tm_mon + 1
			  << '/'
			  << setw(2) << setfill('0') << pEndTime->tm_mday
			  << ' '
			  << setw(2) << setfill('0') << pEndTime->tm_hour
			  << ':'
			  << setw(2) << setfill('0') << pEndTime->tm_min;

	*mpStream << "</FONT></TD> \n"
                 "</TR></TABLE> \n"
                 "</TD> \n";
}

//draw gallery featured auction 
void clsDraw::DrawGalleryFeaturedSection()
{
	int numWanted = 3;
	int numItems = mpData->GetRandomItems((listingTypeEnum) GalleryListingType,
						   featuredEntry,
						   mCurrentCategory,
						   numWanted,
						   mppItems);


	if (numItems > 0)
	{
	*mpStream <<
"<TABLE cellpadding=\"0\" cellspacing=\"0\" width=\"600\" height=\"20\">\n"
"<TR>\n"
//  "	<TD align=\"center\"><P><img src=\"http://pics.ebay.com/aw/pics/gallery/gallery-featured.gif\" width=\"227\" height=\"23\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Featured Items\"></TD>\n"
// kakiyama 08/03/99
"	<TD align=\"center\"><P><img src=\""
<< mpMarketPlace->GetPicsPath()
<< "gallery/gallery-featured.gif\" width=\"227\" height=\"23\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Featured Items\"></TD>\n"      
"</TR>\n"
"</TABLE>\n";
		InsertSpace(1);

		DrawGallery1Row(numItems, numWanted, 0, true);
		InsertSpace(1);

	*mpStream <<
"<TABLE cellpadding=\"0\" cellspacing=\"0\" width=\"600\" height=\"30\">\n"
"<!-- blueline divider -->\n"
"<TR><TD border=\"1\" width=\"600\" >\n"
//  "<IMG SRC=\"http://pics.ebay.com/aw/pics/gallery/gallery-blueline.gif\" ALT=\"Gallery\" WIDTH=\"600\" HEIGHT=\"1\"  vspace=\"25\">\n"
// kakiyama 08/03/99
"<IMG SRC=\""
<< mpMarketPlace->GetPicsPath()
<< "gallery/gallery-blueline.gif\" ALT=\"Gallery\" WIDTH=\"600\" HEIGHT=\"1\"  vspace=\"25\">\n"
"</TD></TR> \n"
"</TABLE>\n";
	}
}

void clsDraw::DrawGallery1Row(int numItems, int numCols, int start, bool featured, bool bGrabbag)
{
	int i;
	*mpStream << "<!--1 Gallery row--> \n";

	*mpStream << "<TABLE WIDTH=\"100%\" CELLPADDING=\"0\" CELLSPACING=\"5\" BORDER=\"0\"> \n"
			     "<TR> \n"
                 "<TD>&nbsp;</TD> \n";

	for (i = 0; i < numItems; i++)
	{
		DrawOneItemImage(mppItems[start + i], featured);

		if (i < numItems - 1 && featured)
			*mpStream << "<TD WIDTH=\"10%\">&nbsp;</TD> \n";
	}

	// To left justify, I need extra, blank columns if there are 
	// fewer than numCols.
	if (numItems < numCols)
		ExtraColumns(numCols - numItems, featured);

	*mpStream << "</TR> \n"
				 "<TR> \n"
                 "<TD>&nbsp;</TD> \n";

	for (i = 0; i < numItems; i++)
	{
		WriteOneItemDescription(mppItems[start + i], featured, bGrabbag);

		if (i < numItems - 1 && featured)
			*mpStream << "<TD WIDTH=\"10%\">&nbsp;</TD> \n";
	}

	// To left justify, I need extra, blank columns if there are 
	// fewer than numCols.
	if (numItems < numCols)
		ExtraColumns(numCols - numItems, featured);


	*mpStream << "</TR> \n"
				 "<TR> \n"
                 "<TD>&nbsp;</TD> \n";

	
	for (i = 0; i < numItems; i++)
	{
		WriteOneItemInfo(mppItems[start + i], featured);

		if (i < numItems - 1 && featured)
			*mpStream << "<TD WIDTH=\"10%\">&nbsp;</TD> \n";
	}

	// To left justify, I need extra, blank columns if there are 
	// fewer than numCols.
	if (numItems < numCols)
		ExtraColumns(numCols - numItems, featured);


	*mpStream << "</TR> \n"
				 "</TABLE> \n";

	*mpStream << "<!-- end of 1 Gallery row--> \n";

	return;
}
	

void clsDraw::DrawItemsSection(bool gallery)
{
	if (gallery)
	{
		DrawItemsSectionGallery();
	}
	else
	{
		DrawItemsSectionText(); 
	}
}

// Draw the time and search block
// These don't really belong together logically
// (and we have them as seperate functions)
// but they're printed in a table together.
void clsDraw::DrawTimeAndSearchSection(int featureType)
{
	*mpStream << "<p align=left><table cellpadding=2 width=\"100%\">"
				 "<tr><td valign=top>";

	// Links connected to the survey
	/*
	if (featuredEntry == (entryTypeEnum)featureType)
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=103\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";

	else if (hotEntry == (entryTypeEnum)featureType)
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=104\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";

	else
		*mpStream	<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=108\">\n"
					<< "Tell us what you think of this page.</a></font><br>\n";
	*/

	DrawTimeLink();

	*mpStream << "</td>\n<td rowspan=2>&nbsp;&nbsp;</td>\n"
				 "<td align=left valign=top rowspan=2>";

	DrawCategorySearchBlock();

	*mpStream << "</td></tr></table>\n";

	return;
}


// Draw announcements and search

void clsDraw::DrawAnnouncementAndSearchSection()
{

	*mpStream << 

		"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n"
		"<tr>\n"
		"<td width=\"60%\"  class=\"small_font\">\n"
		"<font size=\"2\">\n";

	// Here is the announcements
	*mpStream << 

//		"<img src=\"http://pics.ebay.com/aw/pics/home/arrow_red.gif\" width=5 height=9 alt=\"\" border=\"0\">\n"
// kakiyama 08/03/99
		"<img src=\""
	<<  mpMarketPlace->GetPicsPath()
	<<  "/home/arrow_red.gif\" width=5 height=9 alt=\"\" border=\"0\">\n"
		"Keep up to date on eBay's new site with the "
//		"<a href=\"http://pages.ebay.com/welcome.html\">Bulletin</a>!\n"
// kakiyama 08/03/99
		"<a href=\""
	<<   mpMarketPlace->GetHTMLPath()
	<<  "welcome.html\">Bulletin</a>!\n"
		"<br>\n";

	*mpStream << 
//		"<img src=\"http://pics.ebay.com/aw/pics/home/arrow_red.gif\" width=5 height=9 alt=\"\" border=\"0\">\n"
// kakiyama 08/03/99
	<<  "<img src=\""
	<<  mpMarketPlace->GetPicsPath()
	<<  "home/arrow_red.gif\" width=5 height=9 alt=\"\" border=\"0\">\n"
		"<font color=\"darkgreen\">Cool Happenings.</font> Find out what's \n"
//		"<a href=\"http://pages.ebay.com/community/news/happenings.html\">cool</a> at \n"
// kakiyama 08/03/99
	<<  "<a href=\""
	<<  mpMarketPlace->GetPicsPath()
	<<  "community/news/happenings.html\">cool</a> at \n"
		"eBay!<br>\n";

	*mpStream << "</font>";
//	*mpStream << "<hr>";

	// Here is the search box
	*mpStream << 

		"</td>\n"
		"<td rowspan=\"2\">&nbsp;&nbsp;</td>\n"
//		"<form action=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html\" method=\"GET\">\n"
// kakiyama 08/03/99
		"<form action=\""
	<<  mpMarketPlace->GetSearchPath()
	<<  "texis/ebay/results.html\" method=\"GET\">\n"
		"<td width=\"40%\" align=center valign=top rowspan=2>\n"
		"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"CENTER\">\n";

	*mpStream << 

		"<tr>\n"
		"<td class=\"small_font\">\n"
		"<input type=text name=query size=16 maxlength=100 value=\"\">\n"
//		"<input type=\"Image\" name=\"searchButton\" src=\"http://pics.ebay.com/aw/pics/cat/search-button.gif\" border=\"0\" width=\"60\" height=\"20\" alt=\"Search\">\n"
		"<input TYPE=\"SUBMIT\" VALUE=\"Search\">\n"
//		"<font size=\"2\" ><a href=\"http://pages.ebay.com/help/buyerguide/search.html\">tips</a></font>\n"
// kakiyama 08/03/99
		"<font size=\"2\" ><a href=\""
	<<  mpMarketPlace->GetHTMLPath()
	<<  "help/buyerguide/search.html\">tips</a></font>\n"
		"<input type=hidden name=categoryid value=\"\">\n"
		"<input type=hidden name=ht value=1>\n";			// ht=1 signifes headertype=1 for new ui

	if (mpCategory->categoryNumber)
	{
		*mpStream << "<br><INPUT TYPE=checkbox name=category"
				  << int(mpCategory->categoryLevel - 1)
				  << " value="
				  << mpCategory->categoryNumber
				  << "><font size=\"2\">Search only in <b>";

		if (mpCategory->parentCategory)
		{
			*mpStream << mpData->GetTitle(mpData->GetCategory(mpCategory->parentCategory))
					  << " : ";
		}

		*mpStream << mpData->GetTitle(mpCategory)
				  << "</b></font>\n";
	}

	*mpStream << "<br><input type=checkbox name=srchdesc value=\"y\">"
		         "<font size=\"2\">Search titles "
			     "<b>and</b> descriptions</font>";

	*mpStream << "<INPUT TYPE=HIDDEN NAME=\"maxRecordsReturned\" value=\"300\">\n"
			     "<INPUT TYPE=hidden NAME=\"maxRecordsPerPage\" VALUE=\"100\">\n"
				 "<INPUT TYPE=hidden NAME=\"SortProperty\" VALUE=\"MetaEndSort\">\n";

	*mpStream	<<	"<br></td>\n"
				<<	"</tr>\n"
				<<	"</table>\n"
				<<	"</td></form>\n"
				<<	"</tr>\n"
				<<	"<tr>\n"
				<<	"<td valign=\"bottom\">\n"

				// temporary survey link
//				<<	"<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=108\">\n"
//				<< "Tell us what you think of this page.</a></font>"


				<<	"</td>\n"
				<<	"</tr>\n"
				<<	"</table>\n";

	*mpStream	<<	"<hr>\n";

}

// CategorySection is the part
// that lets you into all the subcategories
// of a category.
// This puts it all together.
void clsDraw::DrawCategorySection(bool gallery, int featureType)
{
	*mpStream << "\n<!-- DrawCategorySection -->\n";

	categoryEntry *pCategory;

	if (!mpCategory->firstChild)
		return;

	mMaxColumnsInOverView = 3;
	// Get the number of categories which we'll print, which tells us
	// how many lines we want in each column.
	mMaxLinesInOverView = mpData->CountSelfAndDescendants(mpCategory, mMaxDepth) / 
		mMaxColumnsInOverView;

	mCurrentLineNo = 1;
	mCurrentColumn = 1;

	if (gallery)
	{
		*mpStream << "<a name=\"categories\">"
			"<p><table width=\"600\" align=center>\n<tr><td width=\"33%\" align=left valign=top>\n";
	}
	else
	{
		*mpStream << "<a name=\"categories\">"
			"<p><table width=\"100%\">\n<tr><td width=\"33%\" align=left valign=top>\n";
	}

	pCategory = mpData->GetCategory(mpCategory->firstChild);

	// Iterate through here,
	// using rightSibling to walk all the children.
	while (pCategory)
	{
		DrawCategoryForListing(pCategory, 1, gallery, featureType);

		if (pCategory->rightSibling)
			pCategory = mpData->GetCategory(pCategory->rightSibling);
		else
			pCategory = NULL;
	}

	*mpStream << "</td>";

	// And fill in the empty columns.
	for ( ; mCurrentColumn < mMaxColumnsInOverView; ++mCurrentColumn)
	{
		if (gallery)
		{
			*mpStream << "<td width=\"25%\"></td>";
		}
		else
		{
			*mpStream << "<td width=\"33%\"></td>";
		}
	}

	*mpStream << "</tr></table>";

	return;
}

// A 'head' page -- just headers.
bool clsDraw::Head()
{
	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";
	
	return true;
}

bool clsDraw::UnmodifiedSince()
{
	*mpStream << "HTTP/1.0 304 Not Modified\r\n\r\n";
	return true;
}

bool clsDraw::GrabBag()
{
	const char * sAds;

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " GrabBag";

	time_t theTime = time(NULL);
	char lastModifiedTime[32];
	struct tm* pTime = gmtime(&theTime);
	strftime(lastModifiedTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTime);

	// 1 second expire, for the Expires header.
	theTime += 1;
	char expireTime[32];
	pTime = gmtime(&theTime);
	strftime(expireTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTime);

	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << expireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << lastModifiedTime
			  << "\">\n</head>\n";
	
//	*mpStream << "<BODY BGCOLOR=\"#FFFFFF\">";

//	*mpStream << sNewUIHeaderPart1 
//		<< sNewUITopGrabbagHeaderPart2;

	// header
	*mpStream << mpHeaderGrabbag;

	// ad
	sAds = mpTemplates->GetAdsGrabbag(mPartner, 0);
	if(mpAdGrabbag != sAds)
		*mpStream << sAds;


	*mpStream	<< "<p align=\"center\"><font face=\"Arial, Helvetica, sans-serif\">\n"

				// Link to user survey
//				<< "<font size=\"2\"><a href=\"http://www.esurvey.com/ebay/ssurvey.rti?L=105\">\n"
//				<< "Tell us what you think of this page.</a></font><br>\n"
				// End of survey link

				<< "<b>Thirty items chosen "
				  "randomly from the "
//				  "<a href=\"http://pages.ebay.com/buy/gallery.html\">gallery</a>"
// kakiyama 08/03/99
				  ""<a href=\""
				<< mpMarketPlace->GetHTMLPath()
				<< "buy/gallery.html\">gallery</a>"
				  "."
				  "</b></font>"
//				  "<br>"
//				  "<font face=\"Arial, Helvetica, sans-serif\" size=\"2\">Click the refresh button "
//				  "on your browser to see thirty more.</font>"
				  "</p>";
  
	const int kMaxItemsPerRow  = 5;
	const int kMaxRows		   = 6;
	const int kMaxItemsPerPage = kMaxItemsPerRow * kMaxRows;

	// the first page is 1, but we start at item 0.
	int start = (mCurrentPage - 1) * kMaxItemsPerPage;

    // We get the items first, so that we can just
    // not draw if we don't have any.
	int itemsToRender = 0;

 	itemsToRender = mpData->GetRandomItems(
						(listingTypeEnum) GalleryListingType,
						normalEntry,
						0,
						kMaxItemsPerPage,
						mppItems);

	if (itemsToRender)
	{
		if (itemsToRender > kMaxItemsPerPage)
			itemsToRender = kMaxItemsPerPage;

		int renderedSoFar = 0;
		int nextItem = 0;

		while (renderedSoFar < itemsToRender)
		{
			int itemsLeftToRender = itemsToRender - renderedSoFar;
			int renderThisPass = min(kMaxItemsPerRow, itemsLeftToRender);
		
			DrawGallery1Row(renderThisPass, kMaxItemsPerRow, nextItem, false, true);

			itemsToRender -= renderThisPass;
			nextItem += renderThisPass;

			if (renderThisPass)
			{
				InsertSpace(3);
			}
		}
	}
/*
	*mpStream <<
		"<hr WIDTH=\"600\">\n"
		"<P>\n"
		"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
		"	   <TR>\n"
		"			   <TD COLSPAN=\"2\">\n"
		"					   <DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\" http://pages.ebay.com/services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/myebay.html\">My eBay</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/safeharbor-index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebay.com/aboutebay/index.html\">About eBay</A></FONT></DIV><br>\n"
		"			   </TD>\n"
		"	   </TR>\n"
		"	   <TR>\n"
		"			   <TD>\n"
		"					   <address><font size=\"2\">\n"
		"					   Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved.\n"
		"					   </font></address>\n"
		"					   <font size=\"2\">All trademarks and brands are the property of their respective owners.\n"
		"					   <BR>Use of this web site constitutes acceptance of the eBay <a href=\"http://pages.ebay.com/user-agreement.html\">User Agreement</A> & <a href=\"http://pages.ebay.com/privacy-policy.html\">Privacy Policy</A>.</FONT><BR>\n"
		"					   <!--auctions, auction, computer, bid, bidding, sale, books, coins, stamps, trading cards, memorabilia, sporting goods, music, dolls, comics, antiques, jewelry -->\n"
		"			   </td>\n"
		"			   <td valign=\"top\" width=\"117\">\n"
		"					   <a href=\"http://pages.ebay.com/privacy-policy-reg.html\"><img src=\"http://pics.ebay.com/aw/pics/truste_button.gif\" align=\"center\" width=\"116\" height=\"31\" alt=\"TrustE\" border=\"0\" vspace=\"0\"></a>\n"
		"			   </td>\n"
		"	   </tr>\n"
		"</table>\n"
		"</DIV>\n";
*/
//	*mpStream << sNewUIFooter;
// kakiyama 08/02/99
	*mpStream << clsIntlResource::GetFResString(-1,
												"<!-- footer -->\n"
												"<!-- begin copyright notice -->\n"
												"<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"600\">\n"
												"	<TR>\n"
												"		<TD COLSPAN=\"2\">\n"
												"			<BR><HR WIDTH=\"500\" ALIGN=\"CENTER\">\n"
												"			<br>\n"
												// TODO - www2
												"			<DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{1:GetHTMLPath}services/registration/register.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebaystore.com\">eBay Store</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{2:GetHTMLPath}services/safeharbor/index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{3:GetHTMLPath}services/forum/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"%{4:GetHTMLPath}community/aboutebay/index.html\">About eBay</A></FONT></DIV>\n"
												"			<br>\n"
												"		</TD>\n"
												"	</TR>\n"
												"<TR>\n"
												"	<TD WIDTH=\"450\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"left\">\n"
													"	<FONT SIZE=\"2\">\n"
													"	 Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved. \n"
													"	<BR>\n"
													"	 Designated trademarks and brands are the property of their respective owners\n." 
													"	<BR>\n"
													"	 Use of this Web site constitutes acceptance of the eBay\n "
													"	<A HREF=\"%{5:GetHTMLPath}help/community/png-user.html\">User Agreement</A>\n"
													"	</FONT>\n"
													"	<BR>\n"
													"</TD>\n"
													"<TD WIDTH=\"150\" HEIGHT=\"31\" VALIGN=\"top\" ALIGN=\"right\">\n"
														"<FONT SIZE=\"2\">\n"
													"	<A HREF=\"%{6:GetHTMLPath}help/community/png-privacy.html\"><IMG SRC=\"%{7:GetPicsPath}/truste_button.gif\" ALIGN=\"center\" WIDTH=\"116\" HEIGHT=\"31\" ALT=\"TrustE\" BORDER=\"0\"></A>\n"
													"	</FONT>\n"
													"</TD>\n"
												"</TR>\n"
												"</TABLE>\n"
												"<!-- end copyright notice -->\n"
												"<!-- footer -->\n"
												"</BODY>\n"
												"</HTML>",
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												clsIntlResource::ToString(mpmarketPlace->GetPicsPath()),
												NULL);

															

    *mpStream << "</body></html>";
	*mpStream << flush;

	return true;
}

bool clsDraw::UserPage(int category, int page)
{
	return false;
#if 0
    int categorySize;
    int i;

    mDrawingUsers = true;

	// Make sure it's a real category.
	mpCategory = mpData->GetCategory(category);
	if (!mpCategory)
		return false;

	// Set some member variables.
	mCurrentPage = page;
	mCurrentCategory = category;
	mMaxDepth = (category ? 10 : 2);

	// Get the size of the items, which tells
	// us how many pages we have.
	mpData->GetUsers(&i, &categorySize, category, 1, 0);

	mCurrentListingType = CurrentListingType;
	mNumPages = 1 + (categorySize / mNumItemsPerPage);

	// If we're out of pages.
	if (page > mNumPages)
	{
		return false;
	}

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " User Listings";

	if (mpCategory->categoryNumber)
	{
		*mpStream << ": "
				  << mpData->GetTitle(mpCategory);
	}

	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n"
			  << mpHeaderCategory;

    DrawTimeLink();
    DrawTitleBoxSection(true);
	if (mCurrentPage == 1)
	    DrawCategorySection();
    DrawPageLinksSection();
    DrawUsersSection();
    DrawTitleBoxSection(false);

	*mpStream << mpFooterCategory;
    *mpStream << "</body></html>";
	*mpStream << flush;
	return true;
#endif
}

// To display the hot items for the new UI
// There are no hot galleries --- Stevey
bool clsDraw::HotPage(int category, int type, int featureType, int page, bool findingItem /* = false */, bool gallery)
{
//	templatesPieceEntry *pPiecesList;
//	const char *pHeader;
//	int piecesSize;
	int categorySize;
	int i;

	const char * sAds;

    mDrawingUsers = false;

	if (!findingItem)
		mCurrentItem = -1;

	// Make sure it's a real category.
	mpCategory = mpData->GetCategory(category);
	if (!mpCategory)
		return false;

	// Set some member variables.
	mCurrentPage = page;
	mCurrentCategory = category;
	mCurrentListingType = type;
	mCurrentFeatureType = featureType;
	mMaxDepth = (category ? 10 : 2);

	// Make sure it's an okay type. Otherwise, return
	// failure.
	if (type < 0 || type >= UnknownListingType)
		return false;

	// The 'pieces list' is the parsed template
	// which tells us where (almost) everything goes.
	// it doesn't include our title, header, or footer.
/*
	if (mpTemplates)
		pPiecesList = mpTemplates->GetTemplatePieces(mPartner,
			mpCategory->categoryLevel,
			type,
			page,
			&piecesSize);
*/

	// Get the size of the hot items, which tells
	// us how many pages we have.
	categorySize = mpData->GetItems(NULL, &i, category,
		(entryTypeEnum)featureType, (listingTypeEnum) type, 1, 0);

	mCurrentListingType = (listingTypeEnum) type;
	mNumPages = 1 + (categorySize / mNumItemsPerPage);

	// If we're out of pages.
	if (page > mNumPages)
	{
		return false;
	}

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " Listings";

	if (mpCategory->categoryNumber)
	{
		*mpStream << ": "
				  << mpData->GetTitle(mpCategory);
	}


	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n";


	// Find out if we have fixed banner for this category
/*
		if (sHasAd[mCurrentCategory])
			mAdDrawn = true;
		else
			mAdDrawn = false;

 */

//		*mpStream << pHeader;
/*
	if (0 == category)
		*mpStream << sNewUIHeaderPart1
				  << sNewUITopHotHeaderPart2;
		
	else if (1 == mpCategory->categoryLevel)
	{
		*mpStream << sNewUIHeaderPart1;

		switch (category)
				{
				case CatNumAntiq:
					*mpStream << sNewUILevelOneHotHeaderAntiqPart2;
					break;

				case catNumBooks:
					*mpStream << sNewUILevelOneHotHeaderBooksPart2;
					break;

				case catNumCoins:
					*mpStream << sNewUILevelOneHotHeaderCoinsPart2;
					break;

				case catNumCollectible:
					*mpStream << sNewUILevelOneHotHeaderCollsPart2;
					break;

				case catNumComputer:
					*mpStream << sNewUILevelOneHotHeaderCompsPart2;
					break;

				case catNumDolls:
					*mpStream << sNewUILevelOneHotHeaderDollsPart2;
					break;

				case catNumJewelry:
					*mpStream << sNewUILevelOneHotHeaderJewelsPart2;
					break;

				case catNumPottery:
					*mpStream << sNewUILevelOneHotHeaderPotsPart2;
					break;

				case catNumPhoto:
					*mpStream << sNewUILevelOneHotHeaderPhotosPart2;
					break;

				case catNumSports:
					*mpStream << sNewUILevelOneHotHeaderSportsPart2;
					break;

				case catNumToys:
					*mpStream << sNewUILevelOneHotHeaderToysPart2;
					break;

				case catNumMiscellaneous:
					*mpStream << sNewUILevelOneHotHeaderOthersPart2;
					break;

				default:
					// Bad type here, but ignore it?
					break;
				}

	}
	else
	
		*mpStream << sNewUIHeaderPart1 << sNewUIHigherLevelHeaderItemsPart2 << category << sNewUIHigherLevelHeaderItemsPart3
					<< category << sNewUIHigherLevelHeaderPart4;
	
*/


	// Header
	headerHot = new char[strlen(mpHeaderHot) + 10];
	sprintf(headerHot, mpHeaderHot, category);
	*mpStream << headerHot;

	//Ads
	sAds = mpTemplates->GetAdsHot(mPartner, category);
	if (mpAdHot != sAds) // Determine if it is a real ad
		*mpStream << sAds;

	// Footer
	//footerHot= new char[strlen(mpFooterHot) + 10];
	//sprintf(footerHot, mpFooterHot, category);

	// Meaning, we didn't find the item.
	if (findingItem && mCurrentItem == -1)
	{
		*mpStream << "The item you requested was not found in this category.\n<br>";
		*mpStream << mpFooterHot;
		*mpStream << "</body></html>";
		*mpStream << flush;
		return true;
	}


	DrawAnnouncementAndSearchSection();
	DrawSellItemLink(featureType);
//	DrawTimeAndSearchSection(featureType);
//	DrawTitleBoxSection(true, gallery, featureType);

//	DrawListingTypesSection(gallery, featureType);

	DrawTitleAndListingTypeSection(gallery, featureType);
//	DrawJumpSection(featureType);

//	if (category != 0)
//		DrawPageLinksSection(gallery, featureType);

	DrawSimplifiedPageLink(gallery, featureType);

// 	DrawItemStatusIcons();

	// draw img map (status_icon_map)
	DrawItemStatusIconsMap();

	DrawHotSection();

//	if (category != 0)
		DrawPageLinksSection(gallery, featureType);

//	DrawTitleBoxSection(false, gallery, featureType);

//	*mpStream << sFootNote;
// kakiyama 08/02/99
		*mpStream << clsIntlResource::GetFResString(-1,
							"<p><font face=\"Arial, Helvetica\" size=\"-1\">Click on a title to get a description and to bid on that item. "
							"A <font color=\"ff0000\">red</font> ending time indicates that an auction is ending in less than five hours. "
							"These items are not verified by eBay; "
							"<a href=\"%{1:GetHTMLPath}help/community/trustnsfty.html\">caveat emptor.</a>"
							" This page is updated regularly; don't forget to use your browser's <strong>reload</strong>"
							" button for the latest version. The system may be unavailable during regularly scheduled maintenance -"
							" Please note <b>the new regularly scheduled maintenance time is Fridays, 12 a.m. to 4 a.m. Pacific Time</b>"
							" (Fridays, 00:00 a.m. to 04:00 a.m., eBay time).</font> <br>",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL);

	DrawUpdateTimeAndSponsor();
		
	*mpStream << mpFooterHot;
    *mpStream << "</body></html>";
	*mpStream << flush;
	return true;
}


// To display the featured items for the new UI
// Galleries are not handled in this function --- Stevey
bool clsDraw::FeaturePage(int category, int type, int featureType, int page, bool findingItem /* = false */, bool gallery)
{
//	templatesPieceEntry *pPiecesList;

//	int piecesSize;
	int categorySize;
	int i;

	const char * sAds;

    mDrawingUsers = false;

	if (!findingItem)
		mCurrentItem = -1;

	// Make sure it's a real category.
	mpCategory = mpData->GetCategory(category);
	if (!mpCategory)
		return false;

	// Set some member variables.
	mCurrentPage = page;
	mCurrentCategory = category;
	mCurrentListingType = type;
	mCurrentFeatureType = featureType;
	mMaxDepth = (category ? 10 : 2);

	// Make sure it's an okay type. Otherwise, return
	// failure.
	if (type < 0 || type >= UnknownListingType)
		return false;

	// The 'pieces list' is the parsed template
	// which tells us where (almost) everything goes.
	// it doesn't include our title, header, or footer.
/*
	if (mpTemplates)
		pPiecesList = mpTemplates->GetTemplatePieces(mPartner,
			mpCategory->categoryLevel,
			type,
			page,
			&piecesSize);
*/	

	// Get the size of the items, which tells
	// us how many pages we have.
	categorySize = mpData->GetItems(NULL, &i, category,
		(entryTypeEnum)featureType, (listingTypeEnum) type, 1, 0);

	mCurrentListingType = (listingTypeEnum) type;
	mNumPages = 1 + (categorySize / mNumItemsPerPage);

	// If we're out of pages.
	if (page > mNumPages)
	{
		return false;
	}

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " Listings";

	if (mpCategory->categoryNumber)
	{
		*mpStream << ": "
				  << mpData->GetTitle(mpCategory);
	}


	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n";

	/*
	pHeader = mpTemplates->GetHeader(mPartner, mCurrentCategory);
	if (pHeader != mpHeader)
		mAdDrawn = true;
	else
		mAdDrawn = false;
	*/
/*
	// Find out if we have fixed banner for this category
	if (sHasAd[mCurrentCategory])
		mAdDrawn = true;
	else
		mAdDrawn = false;

*/

//		*mpStream << pHeader;
/*	if (0 == category)


		*mpStream << sNewUIHeaderPart1
				  << sNewUITopFeatureHeaderPart2;

		
	else if (1 == mpCategory->categoryLevel)
	{
		*mpStream << sNewUIHeaderPart1;

		switch (category)
				{
				case CatNumAntiq:
					*mpStream << sNewUILevelOneFeatureHeaderAntiqPart2;
					break;

				case catNumBooks:
					*mpStream << sNewUILevelOneFeatureHeaderBooksPart2;
					break;

				case catNumCoins:
					*mpStream << sNewUILevelOneFeatureHeaderCoinsPart2;
					break;

				case catNumCollectible:
					*mpStream << sNewUILevelOneFeatureHeaderCollsPart2;
					break;

				case catNumComputer:
					*mpStream << sNewUILevelOneFeatureHeaderCompsPart2;
					break;

				case catNumDolls:
					*mpStream << sNewUILevelOneFeatureHeaderDollsPart2;
					break;

				case catNumJewelry:
					*mpStream << sNewUILevelOneFeatureHeaderJewelsPart2;
					break;

				case catNumPottery:
					*mpStream << sNewUILevelOneFeatureHeaderPotsPart2;
					break;

				case catNumPhoto:
					*mpStream << sNewUILevelOneFeatureHeaderPhotosPart2;
					break;

				case catNumSports:
					*mpStream << sNewUILevelOneFeatureHeaderSportsPart2;
					break;

				case catNumToys:
					*mpStream << sNewUILevelOneFeatureHeaderToysPart2;
					break;

				case catNumMiscellaneous:
					*mpStream << sNewUILevelOneFeatureHeaderOthersPart2;
					break;

				default:
					// Bad type here, but ignore it?
					break;
				}

	}
	else
	
		*mpStream << sNewUIHeaderPart1 << sNewUIHigherLevelHeaderItemsPart2 << category << sNewUIHigherLevelHeaderItemsPart3
					<< category << sNewUIHigherLevelHeaderPart4;
	
*/

	// Header
	headerFeatured = new char[strlen(mpHeaderFeatured) + 10];
	sprintf(headerFeatured, mpHeaderFeatured, category);
	*mpStream << headerFeatured;

	// Ads
	sAds = mpTemplates->GetAdsFeatured(mPartner, category);
	if (mpAdFeatured != sAds) // Determine if it is a real ad
		*mpStream << sAds;


	// Footer, multiple footers not supported now, in the future -- stevey
	//footerFeatured = new char[strlen(mpFooterFeatured) + 10];
	//sprintf(footerFeatured, mpFooterFeatured, category);

	// Meaning, we didn't find the item.
	if (findingItem && mCurrentItem == -1)
	{
		*mpStream << "The item you requested was not found in this category.\n<br>";
		*mpStream << mpFooterFeatured;

		*mpStream << "</body></html>";
		*mpStream << flush;

		return true;
	}


	DrawAnnouncementAndSearchSection();
	DrawSellItemLink(featureType);

	DrawTitleAndListingTypeSection(gallery, featureType);
//	DrawJumpSection(featureType);

//	if (category != 0)
//		DrawPageLinksSection(gallery, featureType);

	DrawSimplifiedPageLink(gallery, featureType);

//	DrawItemStatusIcons();

	// draw img map (status_icon_map)
	DrawItemStatusIconsMap();

	DrawFeaturedSection();

	DrawPageLinksSection(gallery, featureType);


//	*mpStream << sFootNote;
// kakiyama 08/02/99
	*mpStream << clsIntlResource::GetFResString(-1,
												"<p><font face=\"Arial, Helvetica\" size=\"-1\">Click on a title to get a description and to bid on that item. "
												"A <font color=\"ff0000\">red</font> ending time indicates that an auction is ending in less than five hours. "
												"These items are not verified by eBay; "
												"<a href=\"%{1:GetHTMLPath}help/community/trustnsfty.html\">caveat emptor.</a>"
												" This page is updated regularly; don't forget to use your browser's <strong>reload</strong>"
												" button for the latest version. The system may be unavailable during regularly scheduled maintenance -"
												" Please note <b>the new regularly scheduled maintenance time is Fridays, 12 a.m. to 4 a.m. Pacific Time</b>"
												" (Fridays, 00:00 a.m. to 04:00 a.m., eBay time).</font> <br>",
												clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
												NULL);

	DrawUpdateTimeAndSponsor();


	*mpStream << mpFooterFeatured;
    *mpStream << "</body></html>";
	*mpStream << flush;
	return true;
}


bool clsDraw::BigticketPage(int category, int type, int featureType, int page, bool findingItem /* = false */, bool gallery)
{
	return true;
}


// A 'normal' page.
// This is the most complex of the things we do, since
// it's the only one that operates on a template.

// No galleries in this page
bool clsDraw::AllItemsPage(int category, int type, int featureType, int page, bool findingItem /* = false */, bool gallery)
{

//	templatesPieceEntry *pPiecesList;
//	const char *pHeader;
//	int piecesSize;
	int categorySize;
	int i;

	const char * sAds;

    mDrawingUsers = false;

	if (!findingItem)
		mCurrentItem = -1;

	// The check for top categories is commented out.  Be careful not to query for
	// all items at top category because there might not be any indexes in items.map
	// for it? Need to clean this up later  -- dnguyen

	// Make sure it's a real category, and make sure it is not category 0 or one of the
	// 12 top categories.
	//if (0 == category)
	//	return false;
	//else
	//{
		mpCategory = mpData->GetCategory(category);
		//if (!mpCategory)
		//{
		//	return false;
		//}
		//if (1 == mpCategory->categoryLevel)
		//	return false;
	//}

	// Set some member variables.
	mCurrentPage = page;
	mCurrentCategory = category;
	mCurrentListingType = type;
	mCurrentFeatureType = featureType;
	mMaxDepth = (category ? 10 : 2);

	// Make sure it's an okay type. Otherwise, return
	// failure.
	if (type < 0 || type >= UnknownListingType)
		return false;

	// The 'pieces list' is the parsed template
	// which tells us where (almost) everything goes.
	// it doesn't include our title, header, or footer.
/*
	if (mpTemplates)
		pPiecesList = mpTemplates->GetTemplatePieces(mPartner,
			mpCategory->categoryLevel,
			type,
			page,
			&piecesSize);
*/

	// Get the size of the items, which tells
	// us how many pages we have.
	categorySize = mpData->GetItems(NULL, &i, category,
		(entryTypeEnum)featureType, (listingTypeEnum) type, 1, 0);

	mCurrentListingType = (listingTypeEnum) type;
	mNumPages = 1 + (categorySize / mNumItemsPerPage);

	// If we're out of pages.
	if (page > mNumPages)
	{
		return false;
	}

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " Listings";

	if (mpCategory->categoryNumber)
	{
		*mpStream << ": "
				  << mpData->GetTitle(mpCategory);
	}


	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n";

//	pHeader = mpTemplates->GetHeader(mPartner, mCurrentCategory);
//	if (pHeader != mpHeader)
//		mAdDrawn = true;
//	else
//		mAdDrawn = false;

	// Find out if we have fixed banner for this category
/*		if (sHasAd[mCurrentCategory])
			mAdDrawn = true;
		else
			mAdDrawn = false;
		
*/
//		*mpStream << sNewUIHeaderPart1 << sNewUIHigherLevelHeaderItemsPart2 << category << sNewUIHigherLevelHeaderItemsPart3
//					<< category << sNewUIHigherLevelHeaderPart4;
	
	
	// header
	headerAll = new char[strlen(mpHeaderAll) + 10];
	sprintf(headerAll, mpHeaderAll, mCurrentCategory);
	*mpStream << headerAll;


//	DrawAd();
	sAds = mpTemplates->GetAdsAllItem(mPartner, category);
	if (mpAdAll != sAds) // Determine if it is a real ad
		*mpStream << sAds;


	// Footer
	//footerAll = new char[strlen(mpFooterAll) + 10];
	//sprintf(footerAll, mpFooterAll, mCurrentCategory);

	// Meaning, we didn't find the item.
	if (findingItem && mCurrentItem == -1)
	{
		*mpStream << "The item you requested was not found in this category.\n<br>";
		*mpStream << mpFooterAll;
		*mpStream << "</body></html>";
		*mpStream << flush;
		return true;
	}
	

	DrawAnnouncementAndSearchSection();
	DrawSellItemLink(featureType);

//	DrawTimeAndSearchSection(featureType);

	// Only draw the child categories if it is not a leaf

//	DrawTitleBoxSection(true, gallery, featureType);
//	DrawListingTypesSection(gallery, featureType);

	DrawTitleAndListingTypeSection(gallery, featureType);

	DrawJumpSection(featureType);

	if (!mpCategory->isLeaf && page == 1)
		DrawCategorySection(gallery, featureType);

//	if (category != 0)
//		DrawPageLinksSection(gallery, featureType);

	//DrawSomeRandomFeaturedItems(category);

	// draw img map (status_icon_map)
	DrawItemStatusIconsMap();

	// for category 0 or the top categories, only list the categories if the listing type is "current"
	if ((0 == category || 1 == mpCategory->categoryLevel) && CurrentListingType == (listingTypeEnum)type)
	{

	}
	// for category 0 and going, going, gone list
	else if ((0 == category) && GoingListingType == (listingTypeEnum)type)
	{
		DrawSimplifiedPageLink(gallery, featureType);
		DrawItemStatusIcons();
		DrawItemsSection(gallery);
		DrawPageLinksSection(gallery, featureType);
	}
	else if (0 == category) // other listing types for category 0, do not display "hot" section 
	{
		if (page == 1)
		{
//			DrawItemStatusIcons();
			DrawFeaturedSectionAllPages(category);
		}
	}

	else if (1 == mpCategory->categoryLevel) // other listing type for 12 top categories
	{
		if (page == 1)
		{
//			DrawItemStatusIcons();
			DrawFeaturedSectionAllPages(category);
		}

//		DrawAllHeadingSection();
//		DrawPageLinksSection(gallery, featureType);
		DrawSimplifiedPageLink(gallery, featureType);
		DrawItemStatusIcons();
		DrawItemsSection(gallery);
		DrawPageLinksSection(gallery, featureType);

//		DrawItemStatusIcons();
		if (page == 1)
			DrawHotSectionAllPages();
	}
	else // non top categories
	{
		if (page == 1)
		{
//			DrawItemStatusIcons();
			DrawFeaturedSectionAllPages(category);
		}

//		DrawAllHeadingSection();
//		DrawPageLinksSection(gallery, featureType);
		DrawSimplifiedPageLink(gallery, featureType);
		DrawItemStatusIcons();
		DrawItemsSection(gallery);
		DrawPageLinksSection(gallery, featureType);

//		DrawItemStatusIcons();
		if (page == 1)
			DrawHotSectionAllPages();
	}


	/*
		if (page == 1)
			DrawFeaturedSection(category);

		// Determine which category and listing type we are in
		// and display approriately
		// Need to clean this up later -- dnguyen
		if (category == 0 & type == 2)
		{
			DrawHotSection();
		}
		// do not display items for top page or top 12 category 
		// in "Current" listing type??? -- dnguyen
		else if (type != 0 || (category != 0 && mpCategory->categoryLevel != 1))
		{
			DrawSimplifiedPageLink(gallery, featureType);

			DrawItemStatusIcons();

		//	DrawAllHeadingSection();

			DrawItemsSection(gallery);

			DrawHotSection();

		//	if (category != 0)
			DrawPageLinksSection(gallery, featureType);

		//	DrawTitleBoxSection(false, gallery, featureType);
		}

	*/


//	*mpStream << sFootNote;
// kakiyama 08/02/99

	*mpStream << clsIntlResource::GetFResString(-1,
						"<p><font face=\"Arial, Helvetica\" size=\"-1\">Click on a title to get a description and to bid on that item. "
						"A <font color=\"ff0000\">red</font> ending time indicates that an auction is ending in less than five hours. "
						"These items are not verified by eBay; "
						"<a href=\"%{1:GetHTMLPath}help/community/trustnsfty.html\">caveat emptor.</a>"
						" This page is updated regularly; don't forget to use your browser's <strong>reload</strong>"
						" button for the latest version. The system may be unavailable during regularly scheduled maintenance -"
						" Please note <b>the new regularly scheduled maintenance time is Fridays, 12 a.m. to 4 a.m. Pacific Time</b>"
						" (Fridays, 00:00 a.m. to 04:00 a.m., eBay time).</font> <br>",
						clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
						NULL);

	DrawUpdateTimeAndSponsor();

	*mpStream << mpFooterAll;
	*mpStream << "</body></html>";
	*mpStream << flush;
	return true;
}


// This is for gallery page only
bool clsDraw::GalleryPage(int category, int type, int page, bool findingItem /* = false */, bool gallery)
{
//	templatesPieceEntry *pPiecesList;
//	int piecesSize;
	int categorySize;
	int i;

    mDrawingUsers = false;

	mCurrentPage = page;
	mCurrentCategory = category;
	mCurrentListingType = type;

	if (!findingItem)
		mCurrentItem = -1;

	// Make sure it's a real category.
	mpCategory = mpData->GetCategory(category);
	if (!mpCategory)
		return false;

	// Make sure it's a real category, and make sure it is not category 0 or one of the
	// 12 top categories.
	if (0 == category)
		return false;
	else
	{
		mpCategory = mpData->GetCategory(category);
		if (!mpCategory)
			return false;
		if (1 == mpCategory->categoryLevel)
			return false;
	}

	// Set some member variables.
	mCurrentPage = page;
	mCurrentCategory = category;
	mMaxDepth = (category ? 10 : 2);

	// Make sure it's an okay type. Otherwise, return
	// failure.
	if (type < 0 || type >= UnknownListingType)
		return false;

	// The 'pieces list' is the parsed template
	// which tells us where (almost) everything goes.
	// it doesn't include our title, header, or footer.
/*
	if (mpTemplates)
		pPiecesList = mpTemplates->GetTemplatePieces(mPartner,
			mpCategory->categoryLevel,
			type,
			page,
			&piecesSize);
*/

	if (gallery)
		type = GalleryListingType;
	

	// Get the size of the items, which tells
	// us how many pages we have.
	categorySize = mpData->GetItems(NULL, &i, category,
		normalEntry, (listingTypeEnum) type, 1, 0);

	mCurrentListingType = (listingTypeEnum) type;
	mNumPages = 1 + (categorySize / mNumItemsPerPage);

	// If we're out of pages.
	if (page > mNumPages)
	{
		return false;
	}

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	// Set this time once so we don't call it every time we
	// print an item.
	mTime = time(NULL);	
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " Listings";

	if (mpCategory->categoryNumber)
	{
		*mpStream << ": "
				  << mpData->GetTitle(mpCategory);
	}


	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n";

	// Header and Footer for this category
	headerGallery = new char[strlen(mpHeaderGallery) + 10];
	sprintf(headerGallery, mpHeaderGallery, category);

	//footerGallery = new char[strlen(mpFooterGallery) + 10];
	//sprintf(footerGallery, mpFooterGallery, category);

	*mpStream << headerGallery;

	// Meaning, we didn't find the item.
	if (findingItem && mCurrentItem == -1)
	{
		*mpStream << "The item you requested was not found in this category.\n<br>";
		*mpStream << mpFooterGallery;
		*mpStream << "</body></html>";
		*mpStream << flush;
		return true;
	}

	// We might draw this various places, but it should only
	// be done once.
	mJumpAlreadyDraw = false;

	// *mpStream << "<BODY BGCOLOR=\"#FFFFFF\">";

//	*mpStream << sNewUIHeaderPart1 << sNewUIHigherLevelHeaderItemsPart2 << category << sNewUIHigherLevelHeaderItemsPart3
//				<< category << sNewUIHigherLevelHeaderGalleryPart4;


	//heading
	DrawAllGalleryHeadingSection(category);

	//item piece
	DrawItemsSection(gallery);


	InsertSpace(1);
	DrawPageLinksSection(gallery, (int)normalEntry);	
	InsertSpace(1);
	DrawTimeStamp();

/*
	*mpStream <<
		"<hr WIDTH=\"600\">\n"
		"<P>\n"
		"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
		"	   <TR>\n"
		"			   <TD COLSPAN=\"2\">\n"
		"					   <DIV ALIGN=\"CENTER\"><font size=\"2\"><A HREF=\"http://www2.ebay.com/aw/announce.shtml\">Announcements</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/registration-show.html\">Register</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/myebay.html\">My eBay</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/safeharbor-index.html\">SafeHarbor</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://pages.ebay.com/aw/feedback.html\">Feedback Forum</A>&nbsp;&nbsp;|&nbsp;&nbsp;<A HREF=\"http://www.ebay.com/aboutebay/index.html\">About eBay</A></FONT></DIV><br>\n"
		"			   </TD>\n"
		"	   </TR>\n"
		"	   <TR>\n"
		"			   <TD>\n"
		"					   <address><font size=\"2\">\n"
		"					   Copyright &copy; 1995-1999 eBay Inc. All Rights Reserved.\n"
		"					   </font></address>\n"
		"					   <font size=\"2\">All trademarks and brands are the property of their respective owners.\n"
		"					   <BR>Use of this web site constitutes acceptance of the eBay <a href=\"http://pages.ebay.com/user-agreement.html\">User Agreement</A> & <a href=\"http://pages.ebay.com/aw/privacy-policy.html\">Privacy Policy</A>.</FONT><BR>\n"
		"					   <!--auctions, auction, computer, bid, bidding, sale, books, coins, stamps, trading cards, memorabilia, sporting goods, music, dolls, comics, antiques, jewelry -->\n"
		"			   </td>\n"
		"			   <td valign=\"top\" width=\"117\">\n"
		"					   <a href=\"http://pages.ebay.com/aw/privacy-policy-reg.html\"><img src=\"http://pics.ebay.com/aw/pics/truste_button.gif\" align=\"center\" width=\"116\" height=\"31\" alt=\"TrustE\" border=\"0\" vspace=\"0\"></a>\n"
		"			   </td>\n"
		"	   </tr>\n"
		"</table>\n"
		"</DIV>\n";

    *mpStream << "</body></html>";
	*mpStream << flush;
	return true; 
*/

	*mpStream << mpFooterGallery;
	*mpStream << "</body></html>";
	*mpStream << flush;
	return true;
}



// This just tells us where to find an item in the listing pages,
// within a particular category and page type.
bool clsDraw::FindItem(int category, int type, int item)
{
	long itemPosition;
	int whichPage;
	bool bTopCategory = false;

	const char * sAds;


	if (0 == category)
		bTopCategory = true;
	else
	{
		mpCategory = mpData->GetCategory(category);
		if (!mpCategory)
			bTopCategory = true;
		if (1 == mpCategory->categoryLevel)
			bTopCategory = true;
	}

	if (bTopCategory) // We do not do FindItem in top categories
	{

		// Write the HTTP headers.
		*mpStream << "HTTP/1.0 200 OK\r\n"
				  << mHTTPHeader << "\r\n";

		*mpStream << "<html><head><TITLE>"
				  << mpMarketName
				  << " Listings";

		if (mpCategory->categoryNumber)
		{
			*mpStream << ": "
					  << mpData->GetTitle(mpCategory);
		}


		// Do the header stuff.
		*mpStream << "</TITLE>\n"
					 "<meta http-equiv=\"Expires\" content=\""
				  << mpExpireTime
				  << "\">\n"
				  << "<meta http-equiv=\"Last-Modified\" content=\""
				  << mpLastModifiedTime
				  << "\">\n</head>\n";
			
		*mpStream << "Please choose a child category in order to find the item.\n<br>";
		*mpStream << mpFooterCategory;
		*mpStream << "</body></html>";
		*mpStream << flush;
		return true;
	}

	else
	{

		// See if we can find the item.
		itemPosition = mpData->FindItemInCategory(mpCategory, type, 
			(int32_t) mpData->FindItemIndex(item));

		// Find out what page the item is on.
		if (itemPosition >= 0)
			whichPage = (itemPosition / mNumItemsPerPage) + 1;
		else
		{
			whichPage = 1; // Print the index if we didn't find it?
			item = -1; // So we know we didn't find it.
		}

		mCurrentItem = item;
		return AllItemsPage(category, type, (int)normalEntry, whichPage, true);
	}
}

bool clsDraw::FindAllListingsOfItem(int item)
{
    itemEntry *pItem;
    long itemIndex;
    int currentCategory;
	bool isHot;

	const char * sAds;

	// Write the HTTP headers.
	*mpStream << "HTTP/1.0 200 OK\r\n"
			  << mHTTPHeader << "\r\n";

	mTime = time(NULL);
	*mpStream << "<html><head><TITLE>"
			  << mpMarketName
			  << " Listings Locator Item #" << item;

	// Do the header stuff.
	*mpStream << "</TITLE>\n"
				 "<meta http-equiv=\"Expires\" content=\""
			  << mpExpireTime
			  << "\">\n"
			  << "<meta http-equiv=\"Last-Modified\" content=\""
			  << mpLastModifiedTime
			  << "\">\n</head>\n";

	// Find out if we have fixed banner for this category
	if (mCurrentCategory >= 0 && mCurrentCategory <= sNumHasAd && sHasAd[mCurrentCategory])
		mAdDrawn = true;
	else
		mAdDrawn = false;

//    *mpStream << sNewUIHeaderPart1 << sNewUITopCatHeaderPart2 << "<P>";

// kakiyama 08/02/99
	*mpStream << clsIntlResource::GetFResString(-1,
									"<BODY BGCOLOR=\"#FFFFFF\">\n"
									"<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
									"<TR>\n"
									"<TD width=\"150\">\n"
									"<a href=\"http://www-new.ebay.com/\"><img \n"
									"src=\"%{1:GetPicsPath}navbar/ebay_logo_home.gif\" width=\"150\" \n"
									"hspace=\"0\" vspace=\"0\" height=\"70\" alt=\"eBay logo\" border=\"0\"><BR>\n"
									"</TD>\n"
									"<TD width=\"450\" align=\"right\">\n"
									"<MAP NAME=\"home_myebay_map\">\n"
									"<AREA SHAPE=RECT COORDS=\"280,0,309,12\" \n"
									"HREF=\"%{2:GetHTMLPath}index.html\" ALT=\"Home\">\n"
									"<AREA SHAPE=RECT COORDS=\"325,0,370,12\" \n"
									"HREF=\"%{3:GetHTMLPath}services/myebay/myebay.html\" ALT=\"My \n"
									"eBay\">\n"
									"<AREA SHAPE=RECT COORDS=\"386,0,432,12\" \n"
									"HREF=\"%{4:GetHTMLPath}sitemap.html\" ALT=\"Site Map\">\n"
									"<AREA SHAPEÞfault HREF=\"%{5:GetHTMLPath}\">\n"
									"</MAP>\n"
									"<img src=\"%{6:GetPicsPath}navbar/home_myebay_map.gif\" width=450 \n"
									"height=15  alt=\"Home, My eBay, Site Map\" border=0 usemap=\"#home_myebay_map\" \n"
									"align=\"right\"><br clear=\"all\">\n"
									"<MAP NAME=\"top_nav\">\n"
									"<AREA SHAPE=RECT COORDS=\"1,1,66,24\" \n"
									"HREF=\"%{7:GetHTMLPath}buy/index.html\" ALT=\"Browse\">\n"
									"<AREA SHAPE=RECT COORDS=\"70,1,120,24\" \n"
									"HREF=\"%{8:GetCGIPath}eBayISAPI.dll?ListItemForSale\" \n"
									"ALT=\"Sell\">\n"
									"<AREA SHAPE=RECT COORDS=\"124,1,196,24\" \n"
									"HREF=\"%{9:GetHTMLPath}services/index.html\" ALT=\"Services\">\n"
									"<AREA SHAPE=RECT COORDS=\"201,1,262,24\" \n"
									"HREF=\"%{10:GetHTMLPath}search/items/search.html\" ALT=\"Search\">\n"
									"<AREA SHAPE=RECT COORDS=\"266,1,315,24\" \n"
									"HREF=\"%{11:GetHTMLPath}help/index.html\" ALT=\"Help\">\n"
									"<AREA SHAPE=RECT COORDS=\"319,1,414,24\" \n"
									"HREF=\"%{12:GetHTMLPath}community/index.html\" ALT=\"Community\">\n"
									"</MAP>\n"
									"<img src=\"%{13:GetHTMLPath}navbar/browse-top.gif\" width=\"415\" \n"
									"height=\"55\" border=\"0\" alt=\"Main Navigation\" usemap=\"#top_nav\"\n"
									"align=\"right\"><br clear=\"all\">\n",
								//	clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace_>GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageListItemForSale)),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
									NULL)

				<< clsIntlResource::GetFResString(-1,
									"<MAP NAME=\"browse_nav\">\n"
									"<AREA SHAPE=RECT COORDS=\"1,7,64,28\" \n"
									"HREF=\"%{1:GetHTMLPath}buy/index.html\" ALT=\"Categories\">\n"
									"<AREA SHAPE=RECT COORDS=\"66,7,118,28\" \n"
									"HREF=\"%{2:GetListingPath}list/featured/index.html\" \n"
									"ALT=\"Featured\">\n"
									"<AREA SHAPE=RECT COORDS=\"119,6,146,28\" \n"
									"HREF=\"%{3:GetListingPath}list/hot/index.html\" \n"
									"ALT=\"Hot\">\n"
									"<AREA SHAPE=RECT COORDS=\"147,7,201,28\" \n"
									// TODO - fix?
									"HREF=\"http://listings-new.ebay.com/aw/listings/list/grabbag.html\" \n"
									"ALT=\"Grab Bag\">\n"
									"<AREA SHAPE=RECT COORDS=\"202,7,265,28\" \n"
									"HREF=\"%{4:GetHTMLPath}buy/greatgifts/gift-section.html\" \n"
									"ALT=\"Great Gifts\">\n"
									"<AREA SHAPE=RECT COORDS=\"266,7,326,28\" \n"
									"HREF=\"%{5:GetHTMLPath}buy/bigticket/index.html\" ALT=\"Big \n"
									"Ticket\">\n"
									"</MAP>\n"
									"<img src=\"%{6:GetPicsPath}navbar/browse-categories.gif\" \n"
									"width=\"415\" height=\"30\" border=\"0\" alt=\"Browse Sub-Navigation\" \n"
									"usemap=\"#browse_nav\" align=\"right\">\n"
									"</TD>\n"
									"</TR>\n"
									"</TABLE><br>\n",
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetListingPath()),
									clsIntlResource::ToString(mpMarketPlace->GetListingPath()),
							//		clsIntlResource::ToString(mpMarketPlace->GetListingPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
									clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
									NULL)




//   *mpStream << sNewUIHeaderPart1 << sNewUITopCatHeaderPart2 << "<P>";
//    *mpStream << mpHeaderCategory;


	*mpStream << "<strong>Updated: "
			  << mpUpdateTimeString
//			  << "</strong> <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?TimeShow\">"
// kakiyama 08/03/99
			  << "</strong> <a href=\""
			  << mpMarketPlace->GetCGIPath(PageTimeShow)
			  << "eBayISAPI.dll?TimeShow\">"
				 "Check eBay official time</a>\n<p><p>";

    // Now that that's out of the way --
    // Let's try to find the item.
    itemIndex = mpData->FindItemIndex((int32_t) item);
    pItem = mpData->GetItem(itemIndex);

    // Darn, we didn't find the item.
    if (!pItem)
    {
        // Now write out that we could not find it.
        *mpStream << "Could not find item #" << item << "<br>\n"
					 "The auction may be over, or may have been started since "
					 "the listings were last updated.<br>"
                  << mpFooterCategory
					<< "</body></html>"
					<< flush;
        return true;
    }

    // Great, we found it, and we have the index. Now, let's walk through
    // the various sorts of places it might be located.
    currentCategory = pItem->categoryNumber;

    // Draw the item now.
    mCurrentItem = item;
    DrawOneEntryItem(pItem, 0);

	isHot = (pItem->numBids > 30 && !pItem->isReserved);

    // Draw links.
    for ( ; ; )
    {
        DrawFoundLink(currentCategory, itemIndex, CurrentListingType, normalEntry, NULL, 
			(currentCategory == pItem->categoryNumber ? "In Current Items:<BR>" : NULL));

//		if (isHot)
//	        DrawFoundLink(currentCategory, itemIndex, CurrentListingType, hotEntry, " (as hot)");
		if (currentCategory && pItem->isFeatured && mpData->GetCategory(currentCategory)->categoryLevel > 1)
			DrawFoundLink(currentCategory, itemIndex, CurrentListingType, featuredEntry, " (as featured)");
		if (!currentCategory && pItem->isSuperFeatured)
			DrawFoundLink(0, itemIndex, CurrentListingType, featuredEntry, " (as featured)");

        if (!currentCategory)
            break;

        currentCategory = mpData->GetCategory(currentCategory)->parentCategory;

		if (1 == mpData->GetCategory(currentCategory)->categoryLevel)
			break;
    }
    currentCategory = pItem->categoryNumber;
    for ( ; ; )
    {
        DrawFoundLink(currentCategory, itemIndex, NewListingType, normalEntry, NULL, 
			(currentCategory == pItem->categoryNumber ? "In Today's New Items:<BR>" : NULL));

//		if (isHot)
//	        DrawFoundLink(currentCategory, itemIndex, NewListingType, hotEntry, " (as hot)");
		if (currentCategory && pItem->isFeatured)
			DrawFoundLink(currentCategory, itemIndex, NewListingType, featuredEntry, " (as featured)");
		if (!currentCategory && pItem->isSuperFeatured)
			DrawFoundLink(0, itemIndex, NewListingType, featuredEntry, " (as featured)");
        if (!currentCategory)
            break;

        currentCategory = mpData->GetCategory(currentCategory)->parentCategory;

		if (1 == mpData->GetCategory(currentCategory)->categoryLevel)
			break;
    }
    currentCategory = pItem->categoryNumber;
    for ( ; ; )
    {
        DrawFoundLink(currentCategory, itemIndex, EndingListingType, normalEntry, NULL, 
			(currentCategory == pItem->categoryNumber ? "In Items Ending Today:<BR>" : NULL));

//		if (isHot)
//	        DrawFoundLink(currentCategory, itemIndex, EndingListingType, hotEntry, " (as hot)");
		if (currentCategory && pItem->isFeatured)
			DrawFoundLink(currentCategory, itemIndex, EndingListingType, featuredEntry, " (as featured)");
		if (!currentCategory && pItem->isSuperFeatured)
			DrawFoundLink(0, itemIndex, EndingListingType, featuredEntry, " (as featured)");
        if (!currentCategory)
            break;

        currentCategory = mpData->GetCategory(currentCategory)->parentCategory;

		if (1 == mpData->GetCategory(currentCategory)->categoryLevel)
			break;
    }
    currentCategory = pItem->categoryNumber;
    for ( ; ; )
    {
        DrawFoundLink(currentCategory, itemIndex, GoingListingType, normalEntry, NULL, 
			(currentCategory == pItem->categoryNumber ? "In Items Ending in 5 Hours:<BR>" : NULL));

//		if (isHot)
//	        DrawFoundLink(currentCategory, itemIndex, GoingListingType, hotEntry, " (as hot)");
		if (currentCategory && pItem->isFeatured)
			DrawFoundLink(currentCategory, itemIndex, GoingListingType, featuredEntry, " (as featured)");
		if (!currentCategory && pItem->isSuperFeatured)
			DrawFoundLink(0, itemIndex, GoingListingType, featuredEntry, " (as featured)");
        if (!currentCategory)
            break;

        currentCategory = mpData->GetCategory(currentCategory)->parentCategory;

		if (1 == mpData->GetCategory(currentCategory)->categoryLevel)
			break;
    }
    currentCategory = pItem->categoryNumber;
    {
        DrawFoundLink(currentCategory, itemIndex, GalleryListingType, normalEntry, NULL, 
			(currentCategory == pItem->categoryNumber ? "In the Gallery:<BR>" : NULL));
    }

    *mpStream << "<P><font size=\"2\"><font color=\"green\">"
        "Page numbers are current as of time of viewing.</font></font><p>";

    *mpStream << mpFooterCategory << "</body></html>" << flush;
	return true;
}

void clsDraw::DrawFoundLink(int category, int itemIndex, int listingType, int entryType, const char *pText,
							const char *pFirstText)
{
    long itemPosition;
    int whichPage;
	int featureType = (int)normalEntry;

	// Get the category.
    mpCategory = mpData->GetCategory(category);
    mCurrentCategory = -1; // Set this so that complete links are drawn.
	mCurrentListingType = listingType;

	// Find the item if we can.
	switch (entryType)
	{
	case normalEntry:
		itemPosition = mpData->FindItemInCategory(mpCategory, listingType, itemIndex);
		featureType = (int)normalEntry;
		break;
	case hotEntry:
		itemPosition = (mpData->ItemIsHotInCategory(mpCategory, listingType, itemIndex)) ? 0 : -1;
		featureType = (int)hotEntry;
		break;
	case featuredEntry:
		itemPosition = (mpData->ItemIsFeaturedInCategory(mpCategory, listingType, itemIndex)) ? 0 : -1;
		featureType = (int)featuredEntry;
		break;
	default:
		itemPosition = -1;
		break;
	}

    if (itemPosition >= 0)
    {
		if (pFirstText)
			*mpStream << pFirstText;

        // Draw out the title with links.
        DrawTitleWithLinks(category, (entryType == normalEntry), false, featureType);
   		whichPage = (itemPosition / mNumItemsPerPage) + 1;

        DrawCategoryLink(category, listingType,
            whichPage, mpCategory->isAdult != 0, (entryType == normalEntry), false, featureType);

        *mpStream << "Page " << whichPage << "</A>";
		if (pText)
			*mpStream << pText;

		*mpStream << "<BR>\n";
    }
}

// These are non-reentrant -- they'll be initialized when
// we start and then not again. We just don't want to duplicate
// the arrays for every thread -- we'll set a class member variable
// to point to them (this also takes care of the casting.)

static const char *sJumpStrings[] =
{
	"Jump to a list of all items",
	"Jump to a list of all new items",
	"Jump to a list of all items ending today",
	"Jump to a list of all completed items",
	"Jump to a list of all items ending in 5 hours",
	"Jump to the Gallery"
};

static const char *sListingTypesLinkDescription[] =
{
	"Current",
	"New Today",
	"Ending Today",
	"Completed",
	"Going, Going, Gone",
	"Gallery"
};

static const char *sListingTypeDescriptions[] =
{
	"Current Auctions",
	"Today's New Items",
	"Items Ending Today",
	"Completed Auction Items",
	"Items Ending in 5 Hours",
	"The Gallery"
};

// This list is located in Listings.cpp
extern const char **gListingDirectories;
extern const char **gListingFeatureTypes;

// Another non-reentrant thing -- this is the big page that
// is displayed when the user tries to enter an 'adult'
// section

static const char *sAdultText =
"<h2>eBay Adults Only Information</h2>\n\n"

"eBay has a listing category known as <strong>Erotic,\n"
"Adults Only.</strong> Like all listings at eBay, listings\n"
"in this category are the sole responsibility of the seller,\n"
"and eBay does not verify, endorse, or recommend any\n"
"of the items therein.\n"
"<p>\n"
"We created this category so that users could avoid (or seek\n"
"out) this merchandise as they wish. Prior to the existence of\n"
"this category, such items were listed in Miscellaneous, making\n"
"accidental exposure more likely.\n"
"<p>\n"
"We don't take a moral stand on the items which are listed at\n"
"eBay -- we act only as a listing agent. If users\n"
"are offended by the material herein, such as smut, porn, \n"
"adult, erotic, or sexual content, they are encouraged to\n"
"avoid accessing this category.\n"
"<p>\n"
"Items listed in this category are omitted from the New Items\n"
"page, but they still appear in chronological order with a\n"
"NEW icon in the appropriate section. They are also omitted\n"
"from the HOT section. In this way we attempt to avoid offending\n"
"users who may be sensitive to this subject matter.\n"

"<h3>Notice to sellers</h3>\n"

"Please take responsibility for your actions by listing such\n"
"merchandise. Various legal statutes exist regulating the sale\n"
"and distribution of pornography and adult material, and by\n"
"listing any such item herein you indicate your intent and\n"
"ability to abide by all applicable laws. This may include,\n"
"but not be limited to, not providing such material to minors.\n"

"<h3>Notice to buyers</h3>\n"

"Various legal statutes exist regulating the sale and\n"
"distribution of pornography and adult material. eBay\n"
"does not regulate the listings in this category. Please\n"
"exercise caution and obey all applicable laws when dealing\n"
"with sellers in this category.\n"

"<h3>View the listings</h3>\n"

"By following this link, you indicate that you are of legal\n"
"age in your community and have the right to view products\n"
"intended for adults only. You agree that the listings herein\n"
"do not violate your community standards. Please do not visit\n"
"this section if you do not want to see explicit materials. Sellers may\n"
"link to graphics on their own site, and they act as the sole publisher\n"
"of such material, even if it appears within the context of a listing at this\n"
"site.\n"
"<p>\n";

// Our constructor.
clsDraw::clsDraw(int partner,
				 ostream *pStream,
				 const clsItemMap *pData,
				 clsTemplatesMap *pTemplates) :
	mpData(pData), 
	mpTemplates(pTemplates),
	mItemStyle(kGallery)

{
	struct tm *pTime;
	templatesPartnerEntry *pPartner;
	int bufferSize;

	time_t theTime;

	mpStream = pStream;
	mPartner = partner;

    mDrawingUsers = false;

	// We use this for the header and footer,
	// and might as well check that it exists.
	pPartner = mpTemplates->GetPartner(mPartner);
	if (!pPartner)
		return;

	// Initialize some time strings.
	theTime = mpData->GetTimeGenerated();

	// For the Last-Modified field.
	mpLastModifiedTime = new char[32];
	pTime = gmtime(&theTime);
	strftime((char *) mpLastModifiedTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTime);

	mpUpdateTimeString = new char[32];
	pTime = localtime(&theTime);

	// We display this in the item headers and in the time string which is
	// displayed to users.
	if (pTime->tm_isdst)
		mpTimeName = "PDT";
	else
		mpTimeName = "PST";

	// This is the string we show users.
	sprintf((char *) mpUpdateTimeString, "%2.2d/%2.2d/%2.2d, %2.2d:%2.2d %s",
		pTime->tm_mon + 1, pTime->tm_mday, pTime->tm_year,
		pTime->tm_hour, pTime->tm_min, mpTimeName);

	// 70 minute expire, for the Expires header.
	theTime += 70 * 60;
	if (theTime <= time(0)) theTime = time(0) + 120;
	mpExpireTime = new char[32];
	pTime = gmtime(&theTime);
	strftime((char *) mpExpireTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTime);

    // Set the HTTP header.
	// We use a content-length of ####### here,
	// and then just before the stream is written
	// the length is calculated and inserted here.
    sprintf(mHTTPHeader, "Content-Type: text/html\r\n"
        "Expires: %s\r\n"
        "Last-Modified: %s\r\n"
        "Content-Length: ########\r\n",
        mpExpireTime,
        mpLastModifiedTime);
    mHTTPHeaderLength = strlen(mHTTPHeader);

	// Get the headers and footers.
	mpHeaderAll			= mpTemplates->GetHeaderAll(mPartner);
	mpFooterAll			= mpTemplates->GetFooterAll(mPartner);
	mpHeaderFeatured	= mpTemplates->GetHeaderFeatured(mPartner);
	mpFooterFeatured	= mpTemplates->GetFooterFeatured(mPartner);
	mpHeaderHot			= mpTemplates->GetHeaderHot(mPartner);
	mpFooterHot			= mpTemplates->GetFooterHot(mPartner);
	mpHeaderGallery		= mpTemplates->GetHeaderGallery(mPartner);
	mpFooterGallery		= mpTemplates->GetFooterGallery(mPartner);
	mpHeaderGrabbag		= mpTemplates->GetHeaderGrabbag(mPartner);
	mpFooterGrabbag		= mpTemplates->GetFooterGrabbag(mPartner);
	mpHeaderCategory	= mpTemplates->GetHeaderCategory(mPartner);
	mpFooterCategory	= mpTemplates->GetFooterCategory(mPartner);


	headerAll		= NULL;
	footerAll		= NULL;
	headerFeatured	= NULL;
	footerFeatured	= NULL;
	headerHot		= NULL;
	footerHot		= NULL;
	headerGallery	= NULL;
	footerGallery	= NULL;

	// mpAdAll will never be displayed, but we need it to maintain the index structure
	mpAdAll	= mpTemplates->GetAdsAllItem(mPartner, -1); 
	mpAdFeatured	= mpTemplates->GetAdsFeatured(mPartner, -1); 
	mpAdHot	= mpTemplates->GetAdsHot(mPartner, -1); 
	mpAdGallery	= mpTemplates->GetAdsGallery(mPartner, -1); 
	mpAdGrabbag	= mpTemplates->GetAdsGallery(mPartner, -1); 
	mpAdCategory	= mpTemplates->GetAdsCategory(mPartner, -1); 

	mpMarketName = "eBay"; // Always, right now.

	// This is pretty much fixed.
	mNumTextItemsPerPage            = 50;
	mNumGalleryItemsPerPage         = 30;
	mNumFeaturedGalleryItemsPerPage = 3;

	// Allocate _one_ buffer so we don't have to do it over and over.
	bufferSize = max(mNumTextItemsPerPage, mNumGalleryItemsPerPage);
	mppItems = new itemEntry *[bufferSize]; 

	mNumRandomFeatureItems = 7;
	mppRandomFeatureItems = new itemEntry *[mNumRandomFeatureItems + 1];

	// How many pages we always show the links for on either side of current.
	mPagesLimit = 5;

	// Set some URLs from the template.	
	//mpNewURL = mpTemplates->GetNewURL();
	//MLH 10/22/98 fixme bad hack to get border=0 into tag
//	mpNewURL = "<img height=11 width=28 border=0 alt=\"[NEW!]\" src=\"http://pics.ebay.com/aw/pics/new.gif\">";
	strcpy(mpNewURL, "<img height=11 width=28 border=0 alt=\"[NEW!]\" src=\"");
	strcat(mpNewURL, mpMarketPlace->GetPicsPath());
	strcat(mpNewURL, "new.gif\">");

	mpHotURL = mpTemplates->GetHotURL();
	mpPicURL = mpTemplates->GetPicURL();
	mpSearchLink = mpTemplates->GetSearchURL();

	// Lena gift icon
	// I copied the gift urls here since we have different template map files now, which have 
	// different gift urls, and people use the wrong template file quite pften. ---- Stevey

// kakiyama 08/0/99 - commented out
// resourced using mpMarketPlace->getHTMLPath()

//	mpViewItemURL		= "http://cgi-new.ebay.com/aw-cgi/eBayISAPI.dll?ViewItem&item=";
	mpFeaturedPath		= "http://cgi-new.ebay.com/aw-cgi/eBayISAPI.dll?Featured";

//	mpFatherGiftURL		= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/dad.gif\"></A>"; // chad!
//	mpRosieGiftURL		= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=14 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/rosie_ro.gif\"></A>"; //
//	mpAnniversaryGiftURL = "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/ann.gif\"></A>";
//	mpBabyGiftURL		= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/bab.gif\"></A>";
//	mpBirthdayGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/bir.gif\"></A>";
//	mpChristmasGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/chr.gif\"></A>";
//	mpEasterGiftURL		= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/eas.gif\"></A>";
//	mpGraduationGiftURL = "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/gra.gif\"></A>";
//	mpHalloweenGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/hal.gif\"></A>";
//	mpHanukahGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/han.gif\"></A>";
//	mpJuly4thGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/ind.gif\"></A>";
//	mpMotherGiftURL		= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/mot.gif\"></A>";
//	mpStpatrickGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/pat.gif\"></A>";
//	mpThanksgivingGiftURL = "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/tha.gif\"></A>";
//	mpValentineGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/val.gif\"></A>";
//	mpWeddingGiftURL	= "<A HREF=\"http://pages.ebay.com/help/buyerguide/gift-icon.html\"><img border=0 hspace=2 height=15 width=16 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gft/wed.gif\"></A>";

//	mpPreviousPicURL = "<img height=18 width=12 border=0 alt=\"[Previous]\" src=\"http://pics.ebay.com/aw/pics/listings/browse-arrow-l.gif\">";
//	mpNextPicURL = "<img height=18 width=12 border=0 alt=\"[Next]\" src=\"http://pics.ebay.com/aw/pics/listings/browse-arrow-r.gif\">";

	// Get a 'partner safe' link.
	if (mPartner == 0) // Partner 0 is eBay, not the lack of a partner.
	{
		mpBaseListingsLink = new char[strlen(sBaseListingsLink) + 1];
		strcpy((char *) mpBaseListingsLink, sBaseListingsLink);
	}
	else
	{
		mpBaseListingsLink = new char[strlen("/part000") + strlen(sBaseListingsLink) + 1];
		sprintf((char *) mpBaseListingsLink, "/part%03d%s", mPartner, sBaseListingsLink);
	}

	// Initialize these from the statics.
	mppJumpStrings = (const char **) sJumpStrings;
	mppListingTypesLinkDescription = (const char **) sListingTypesLinkDescription;
	mppListingTypeDescriptions = (const char **) sListingTypeDescriptions;
	mppListingDirectories = (const char **) gListingDirectories;
	mppFeatureTypes = (const char **)gListingFeatureTypes;

	mpAdultText = sAdultText;

	// For ad	
	mpTopAds = new clsDailyAd(0, 0, 60, 
		"http://ad.doubleclick.net/jump/www.ebay.com",
		"http://ad.doubleclick.net/ad/www.ebay.com");
	mUpper = 2;
	mRange = 10;
	mAdId  = 0;

	mDrawingForItemListing = false;

	return;
}   //lint !e1401 We're content that many things are uninitialized here.

clsDraw::~clsDraw()
{
	// Visual C++ has this horrible bug where it refuses to delete allocated const char *
	// so, we'll cast them to this pointer first -- note that this will give
	// a type mismatch error under purify, but should be safe.
	char *pNonConst;

	delete [] mppItems;

	delete [] mppRandomFeatureItems;

	pNonConst = (char *) mpLastModifiedTime;
	delete [] pNonConst;

	pNonConst = (char *) mpUpdateTimeString;
	delete [] pNonConst;

	pNonConst = (char *) mpExpireTime;
	delete [] pNonConst;

	pNonConst = (char *) mpBaseListingsLink;
	delete [] pNonConst;

	delete mpTopAds;

	if (headerAll)
		delete [] headerAll;

	if (footerAll)
		delete [] footerAll;

	if (headerFeatured)
		delete [] headerFeatured;

	if (footerFeatured)
		delete [] footerFeatured;

	if (headerHot)
		delete [] headerHot;

	if (footerHot)
		delete [] footerHot;

	if (headerGallery)
		delete [] headerGallery;

	if (footerGallery)
		delete [] footerGallery;


}   //lint !e1540 we know we don't free or zero lots of pointers here.

void clsDraw::SetDisplayProperties(bool gallery)
 { 
	if (gallery)
		mNumItemsPerPage = mNumGalleryItemsPerPage;
	else
		mNumItemsPerPage = mNumTextItemsPerPage;
}

// TODO unused - remove
//static const char *sOfficeDepotAd1 =
//"<br><center>\n"
//"<TABLE WIDTH=468 HEIGHT=60 BORDER=0 CELLSPACING=0 CELLPADDING=0 BGCOLOR=\"#FFFFFF\">\n"
//"<FORM ACTION=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl\">\n"
//"<INPUT TYPE=hidden NAME=url VALUE=\"http://www.officedepot.com\">\n"
//"<INPUT TYPE=hidden NAME=log VALUE=\"officedepot\">\n"
//"<TR>\n"
//"<TD WIDTH=468 HEIGHT=30 COLSPAN=2>\n"
//"<a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=officedepot&url=http://www.officedepot.com\">\n"
//"<IMG SRC=\"http://cayman.ebay.com/aw/ads/officedepot/od_whattodo.gif\" ALT=\"OfficeDepot.com\" BORDER=0></a></TD>\n"
//"</TR>\n"
//"<TR>\n"
//"<TD HEIGHT=30><CENTER><SELECT>\n"
//"<OPTION SELECTED>Sit outside and get some sun\n"
//"<OPTION>Shop for new hats\n"
//"<OPTION>Take a nap in the square\n"
//"<OPTION>Eat a leisurely meal\n"
//"<OPTION>Shop for Office Products</SELECT></CENTER></TD>\n"
//"<TD WIDTH=103 HEIGHT=30><CENTER><INPUT TYPE=\"IMAGE\"\n"
//" src=\"http://cayman.ebay.com/aw/ads/officedepot/od_selectbutton2.gif\" VALIGN=\"MIDDLE\" ALIGN=\"CENTER\" BORDER=0 NAME=\"\"></CENTER></TD>\n"
//"</TR></FORM></TABLE>\n"
//"</center>\n";

// TODO unused - remove
//static const char *sOfficeDepotAd2 = 
//"<br><center>\n"
//"<TABLE WIDTH=468 HEIGHT=60 BORDER=0 CELLSPACING=0 CELLPADDING=0 BGCOLOR=\"#FFFF00\">\n"
//"<FORM ACTION=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl\">\n"
//"<INPUT TYPE=hidden NAME=url VALUE=\"http://www.officedepot.com\">\n"
//"<INPUT TYPE=hidden NAME=log VALUE=\"officedepot\">\n"
//"<TR>\n"
//"<TD WIDTH=410 HEIGHT=29 COLSPAN=2><a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=officedepot&url=http://www.officedepot.com\">\n"
//"<IMG SRC=\"http://cayman.ebay.com/aw/ads/officedepot/od_ineed.gif\" BORDER=0></a></TD>\n"
//"<TD WIDTH=58 HEIGHT=60 ROWSPAN=2><a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=officedepot&url=http://www.officedepot.com\">\n"
//"<IMG SRC=\"http://cayman.ebay.com/aw/ads/officedepot/od_whatwouldyou_logo.gif\" BORDER=0></a></TD>\n"
//"</TR><TR BGCOLOR=\"#000000\">\n"
//"<TD WIDTH=300 HEIGHT=29 ALIGN=LEFT>&nbsp;&nbsp;&nbsp;&nbsp;<SELECT>\n"
//"<OPTION SELECTED>is open 24 hours\n"
//"<OPTION>has huge selection\n"
//"<OPTION>is convenient\n"
//"<OPTION>has low prices\n"
//"<OPTION>is as near as my computer\n"
//"<OPTION>is all of the above</SELECT></TD>\n"
//"<TD WIDTH=110 HEIGHT=29 ALIGN=CENTER>\n"
//"<INPUT TYPE=\"IMAGE\" src=\"http://cayman.ebay.com/aw/ads/officedepot/od_selectbutton.gif\" HEIGHT=29 BORDER=0 NAME=\"Select\"></TD>\n"
//"</TR> </FORM></TABLE>\n";     

// TODO - unused remove
//const char *sMicrosoftAd1 =
//"<br><center>\n"
//"<TABLE WIDTH=\"100%\"><tr><td align=\"right\"><font size=\"1\">Click <a href=\"http://ads.adnetusa.com/cgi-bin/ebay/click.pl?log=text1&url=http://www.buycomp.com/bc/office97.asp?ad=200004\">here</a> to order<br>Microsoft Office 97</font></td></tr></table></center>\n";

//
// Emit the variable ad tag
//
void clsDraw::DrawAd()
{
	int mWidth, mHeight, mBorder; 
	mWidth  = 468;
	mHeight = 60;
	mBorder = 1;	

	// See if we've already drawn it.
	if (mAdDrawn) // Draw the fixed banners
	{
		switch (mCurrentCategory)
		{
		case 92:
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.lenox.com/\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}lenox/lenox.gif\" alt=\""
													"Lenox is sponsored by Lenox"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 174:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.sony.com/factory/\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}sony/sony.gif\" alt=\""
													"Monitors is sponsored by Sony"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 177:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://athome.compaq.com/store/config.asp?cModel=5600i%2D500/3SBE2&cpqsid=50E520S0QMSH2MSA00G7BLXB932K3586/\">\n"
													"<img width=300 height=50 border=0 src=\"%{1:GetAdPicsPath}compaq/cpq_athome_speed.gif\" alt=\""
													"Notebooks category is sponsored by Compaq"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 179:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://athome.compaq.com/store/config.asp?cModel=5600i%2D500/3SBE2&cpqsid=50E520S0QMSH2MSA00G7BLXB932K3586/\">\n"
													"<img width=300 height=50 border=0 src=\"%{1:GetAdPicsPath}compaq/cpq_athome_power.gif\" alt=\""
													"PC Systems category is sponsored by Compaq"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 192:	
			*mpStream << clsIntlResource::GetFResString(-1,					
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\" http://www.kinkos.com/ebay/scan.html\">\n"
													"<img width=300 height=50 border=0 src=\"%{1:GetAdPicsPath}kinko/mini_kinkos.gif\" \n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 222:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.krause.com/promo/tsebay\">\n"
													"<img width=300 height=50 border=0 src=\"%{1:GetAdPicsPath}krause/ts_hotwheels.gif\" \n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 224:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.hotwheels.com/collectors/\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}hotwheels/hotwheels-banner.gif\" alt=\""
													"HotWheels is sponsored by HotWheels"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 246:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.krause.com/promo/tsebay\">\n"
													"<img width=300 height=50 border=0 src=\"%{1:GetAdPicsPath}krause/ts_actionfig.gif\" \n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 293:
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://national.sidewalk.msn.com/\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}sidewalk/sidewalk.gif\" alt=\""
													"Consumer Electronics is sponsored by Sidewalk.com"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 302:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\" http://www.kinkos.com/ebay/scan.html\">"
													"<img src=\"%{1:GetAdPicsPath}kinko/mini_kinkos.gif\" hspace=\"0\" vspace=\"0\" border=\"0\" width=\"300\" height=\"50\"></a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 309:	
			*mpStream <<  clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.warnerbrothers.com/frame_moz_day.html\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}warnerbros/warnerbros.gif\" alt=\""
													"Videos is sponsored by Warner Brothers"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 436:
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td valign=\"TOP\"><IMG SRC=\"%{1:GetAdPicsPath}beanies/sponsor-beanies.gif\" ALT=\""
													"Category is sponsored by Mary Beth's Beanie World"
													"\" height=25 width=300 border=0 hspace=0 vspace=0><BR>\n"
													"<A HREF=\"http://www.beanbagworld.net?ebay\">\n"
													"<IMG SRC=\"{2:GetAdPicsPath}beanies/beanieworld.gif\"\n"
													"height=50 width=300 hspace=0 vspace=0 border=0 alt=\""
													"Category is sponsored by Mary Beth's Beanie World"
													"\"></A>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 1049:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.cdnow.com/from=max:h:eba:smug:ebay1\">\n"
													"<img width=300 height=75 border=0 src=\"%{1:GetAdPicsPath}cdnow/cdnow.gif\" alt=\""
													"CDs is sponsored by CDNow"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;
		case 1062:	
			*mpStream << clsIntlResource::GetFResString(-1,
													"<table width=\"450\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
													"<tr>\n"
													"<td align=\"RIGHT\"><a href=\"http://www.jcrew.com/\">\n"
													"<img width=300 height=75 border=0 src=\"http://cayman.ebay.com/aw/ads/jcrew/jcrew1.gif\" alt=\""
													"Women's Clothing is sponsored by JCrew"
													"\">\n"
													"</a><br>\n"
													"</td>\n"
													"</tr>\n"
													"</table>\n",
													clsIntlResource::ToString(mpMarketPlace->GetAdPicsPath()),
													NULL);
			break;

		default:
			break;

		}
			
	}
	else // Draw the random banners
	{

		// Check to see if we should draw it this time.
		mAdId = (mAdId + 1 ) % mRange;
		if (mAdId >= mUpper)
			return;

//		*mpStream		<< "<center>";

		*mpStream	<<	"<table width=\"600\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"

						"<tr>\n";
					/*	"<td valign=\"TOP\"><img src=\"http://pics.ebay.com/aw/pics/icons/browse-icon.gif\" border=0 alt=\"Shopping Bag\"><br>\n"
						"</td>\n";
					*/

						"<tr>\n";


		*mpStream	<<	"<td align=\"RIGHT\">\n";


		// get url
		if (mpTopAds->GetURL() != NULL)
		{
			*mpStream	<< "<A HREF=\""
						<< mpTopAds->GetURL()
						<< "/cat"
						<< mCurrentCategory
						<< "\">\n";
		}

		// get image source
		if (mpTopAds->GetImageSource() != NULL)
		{
			*mpStream	<< "<IMG width="
						<< mWidth
						<< " height="
						<< mHeight
						<< " border="
						<< mBorder;

			if (mpTopAds->GetAlt() != NULL)
			{
				*mpStream << " alt=\""
						 << mpTopAds->GetAlt()
						 << "\"";
			}

			*mpStream	<< " SRC=\""
						<< mpTopAds->GetImageSource()
						<< "/cat"
						<< mCurrentCategory
						<< "\">\n";
		}
		*mpStream	<< "</A>";

		*mpStream	<< "<br></td></tr>";



		// other description whicn is in html format
		if (mpTopAds->GetOther() != NULL)
		{
			*mpStream	<< "<br><tr><td>\n";
			*mpStream	<< mpTopAds->GetOther();
			*mpStream	<< "</td></tr>\n";
		}

		*mpStream	<< "</table>\n";

	}
}


void clsDraw::DrawUpdateTimeAndSponsor()
{
	*mpStream <<

		"<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n"
		"<tr>\n"
		"	<td>\n"
		"	<p></p><br>\n";

	// This is the time link
	*mpStream <<

		"<center>\n"
		"<font face=\"Arial, Helvetica\" size=\"-1\">\n"
		"<b>Updated: " 
		<< mpUpdateTimeString << "</b> \n"
// kakiyama 08/03/99
	<<  "<a href=\""
	<<  mpMarketPlace->GetCGIPath(PageTimeShow)
	<<  "eBayISAPI.dll?TimeShow\">Check eBay official time</a><br>\n"
		"Use your browser's <b>reload</b> button to see the latest version.<br>\n";

/*  This is the code for DrawBidSponsor()	
	<p>
	<!-- BEGIN INTERNET LINK EXCHANGE CODE -- THIS IS LINE 1 -->
	<a href="http://ad.linkexchange.com/90/X961116/gotoad.map" target="_top">
	<img width=440 height=40 border=1 ismap 
	alt="Internet Link Exchange" 
	src="http://ad.linkexchange.com/90/X961116/logoshowad?free">
	</a>
	&nbsp;<br><a href="http://www.linkexchange.com/">Member of the Internet Link Exchange</a><br>
	<!-- END INTERNET LINK EXCHANGE CODE --></font>
	<p>
	
	</center><br>
*/

	DrawBidSponsor();

	*mpStream <<

		"	</td>\n"
		"</tr>\n"
		"</table>\n"
		"<p>\n";
}

void clsDraw::DrawBidSponsor()
{
	bool isSponsor = false;

	// If we've already drawn an ad, return.
	if (mAdDrawn)
		return;

	if (mCurrentCategory >= 0 && mCurrentCategory <= sNumHasAd &&
		sHasAd[mCurrentCategory])
		isSponsor = true;
	if (isSponsor)
	{
		*mpStream << "<P> <center>\n"
					 " <A HREF=\"http://ad.doubleclick.net/jump/www.ebay.com/sponsor-button/cat"
				  << mCurrentCategory
				  << "/\">\n"
					 " <IMG width=234 height=60 border=0 SRC=\"http://ad.doubleclick.net/ad/www.ebay.com/sponsor-button/cat"
				  << mCurrentCategory
				  << "/\"></a></font>\n"
					 "</center>\n";
	}	
	else
	{
		*mpStream << "<p><center>\n"
					 "<!-- BEGIN INTERNET LINK EXCHANGE CODE -- THIS IS LINE 1 -->\n"
					 "<a href=\"http://ad.linkexchange.com/90/X961116/gotoad.map\" target=\"_top\">\n"
					 "<img width=440 height=40 border=1 ismap \n"	"alt=\"Internet Link Exchange\" \n"
					 "src=\"http://ad.linkexchange.com/90/X961116/logoshowad?free\">\n"
					 "</a>\n"
					 "<br><a href=\"http://www.linkexchange.com/\">Member of the Internet Link Exchange</a>\n"
					 "\n"
					 "<br>\n"
					 "<!--  END INTERNET LINK EXCHANGE CODE  --></font>\n"
					 "</center><p>\n";
	}
}


void clsDraw::DrawTimeStamp()
{
	*mpStream	<< "<P><font size=\"2\" face=\"times, courier\">"
				   "<strong>Last updated: "
				<< mpUpdateTimeString
//				<< "</strong> <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?TimeShow\">Check eBay official time</a>\n"
// kakiyama 08/03/99
				<< "</strong> <a href=\""
				<< mpMarketPlace->GetCGIPath(PageTimeShow)
				<< "eBayISAPI.dll?TimeShow\">Check eBay official time</a>\n"
				   "<br>Use your browser's <strong>reload</strong> button to see the latest "
				   "version.</font><BR><BR>";
}



