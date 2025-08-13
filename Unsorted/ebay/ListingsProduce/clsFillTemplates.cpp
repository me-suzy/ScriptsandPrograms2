/* $Id: clsFillTemplates.cpp,v 1.11.2.2.74.5 1999/08/10 01:19:47 nsacco Exp $ */
#include "clsDatabase.h"
#include "clsHeader.h"
#include "clsFooter.h"
#include "clsFillTemplates.h"
#include "clsTextPool.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsApp.h"
#include <iostream.h>
#include "eBayTypes.h"

clsFillTemplates::clsFillTemplates()
{
	mpPartners = NULL;
	mNumPartners = 0;
	mpText = new clsTextPool;
	mpPieces = new list<templatesPieceEntry>;
	mpAds = new list<int32_t>;
//	mpHeaders = new list<templatesCategoryHeaderEntry>;
	memset(&mHeader, '\0', sizeof (templatesHeaderEntry));
}

clsFillTemplates::~clsFillTemplates()
{
	delete mpText;
	delete [] mpPartners;

//	if (mpHeaders)
//		mpHeaders->clear();

	if (mpPieces)
		mpPieces->clear();

	if (mpAds)
		mpAds->clear();

//	delete mpHeaders;
	delete mpPieces;
	delete mpAds;
}

// Some static text. Ugly. What we'd like to ultimately
// see is all text _and_ all pieces in external files which
// this reads.

// kakiyama 07/19/99 - commented out
// resourced using getPicsPath(), getHTMLPath(), and getCGIPath()

//static const char *sNewURL = "<img height=11 width=28 alt=\"[NEW!]\" src=\"http://pics.ebay.com/aw/pics/new.gif\">";
//static const char *sHotURL = "<img height=11 width=28 alt=\"[HOT!]\" src=\"http://pics.ebay.com/aw/pics/hot.gif\">";
//static const char *sPicURL = "<img height=11 width=28 alt=\"[PIC!]\" src=\"http://pics.ebay.com/aw/pics/pic.gif\">";
//static const char *sViewItemURL = "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?ViewItem&item=";
//static const char *sFeaturedPath = "http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?Featured";
//static const char *sFatherGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/father.gif\"></A>"; // chad!
//static const char *sRosieGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/rosie_ro.gif\"></A>"; //
//static const char *sAnniversaryGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/anniversary.gif\"></A>";
//static const char *sBabyGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/baby.gif\"></A>";
//static const char *sBirthdayGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/birthday.gif\"></A>";
//static const char *sChristmasGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/christmas.gif\"></A>";
//static const char *sEasterGiftURL =       "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/easter.gif\"></A>";
//static const char *sGraduationGiftURL =   "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/graduation.gif\"></A>";
//static const char *sHalloweenGiftURL =    "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/halloween.gif\"></A>";
//static const char *sHanukahGiftURL =      "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/hanukah.gif\"></A>";
//static const char *sJuly4thGiftURL =      "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/July4th.gif\"></A>";
//static const char *sMotherGiftURL =       "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/mother.gif\"></A>";
//static const char *sStpatrickGiftURL =    "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/stpatrick.gif\"></A>";
//static const char *sThanksgivingGiftURL = "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/thanksgiving.gif\"></A>";
//static const char *sValentineGiftURL =    "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/valentine.gif\"></A>";
//static const char *sWeddingGiftURL =      "<A HREF=\"http://pages.ebay.com/aw/gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"http://pics.ebay.com/aw/pics/gift/wedding.gif\"></A>";


static const char *sSearchLink = "http://mudpuppy.ebay.com/scripts/ebaySearch/search.idq";
static const char *sPrevPicURL = "<img height=18 width=12 border=0 alt=\"[Previous]\" src=\"http://pics.ebay.com/aw/pics/greyleft.gif\">";
static const char *sNextPicURL = "<img height=18 width=12 border=0 alt=\"[Next]\" src=\"http://pics.ebay.com/aw/pics/greyright.gif\">";

// kakiyama 07/19/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *sBidText =
	"<p><p><br>"
	"<font size=-1>Click on a title to get a description and to bid on that item.\n"
	"A <font color=\"red\">red</font> ending time indicates that an auction is ending in less than five hours.\n"
	"These items are not verified by eBay;\n"
	"<a href=\"http://pages.ebay.com/help/community/png.html\">caveat emptor.</a>\n"
	"This page is updated regularly; don't forget to use your browser's <strong>reload</strong>\n"
	"button for the latest version. The system may be unavailable during regularly scheduled maintenance,\n"
	"Mondays, 12 a.m. to 4 a.m. Pacific Time (Mondays, 00:01 a.m. to 04:00 a.m., eBay time).</font> <P>\n";
*/

// Ad for category 92
static const char *sCommonAd =
"Hello, World!\n";

//"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
//"<tr> <td valign=\"middle\"><a href=\"http://www.ebay.com\">\n"
//"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
//"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
//"<td valign=\"middle\"><a href=\"http://www.lenox.com/\">\n"
//"<img src=\"http://cayman.ebay.com/aw/ads/lenox/lenox.gif\" hspace=\"0\" vspace=\"0\"\n"
//"alt=\"Lenox is sponsored by Lenox\" border=\"0\" width=\"300\" height=\"75\"></a></td>\n"
//"</tr> </table>\n"
//"<p><strong><font size=\"3\"><a href=\"http://www.ebay.com\">Home</a>&nbsp; <a href=\"http://listings.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a href=\"http://pages.ebay.com/newschat.html\">News/Chat</a>&nbsp; <a href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>\n"
//"\n";


// Ad for category 1062
// TODO - unused remove
//static const char *sJCrewAd =
//"<body bgcolor=\"#FFFFFF\">\n"
//"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
//"<tr> <td valign=\"middle\"><a href=\"http://www.ebay.com\">\n"
//"<img src=\"http://cayman.ebay.com/aw/ads/ebay/eBay.gif\" hspace=\"0\" vspace=\"0\"\n"
//"alt=\"eBay - The world's Personal Trading Community\" border=\"0\" width=\"237\" height=\"75\"></a></td>\n"
//"<td valign=\"middle\"><a href=\"http://www.jcrew.com/\">\n"
//"<img src=\"http://cayman.ebay.com/aw/ads/jcrew/jcrew1.gif\" hspace=\"0\" vspace=\"0\"\n"
//"alt=\"Women's Clothing is sponsored by JCrew\" border=\"0\" width=\"300\" height=\"75\"></a></td>\n"
//"</tr> </table>\n"
//"<p><strong><font size=\"3\"><a href=\"http://www.ebay.com\">Home</a>&nbsp; <a href=\"http://listings.ebay.com/aw/listings/list\">Listings</a>&nbsp; <a href=\"http://pages.ebay.com/ps.html\">Buyers</a>&nbsp; <a href=\"http://pages.ebay.com/seller-services.html\">Sellers</a>&nbsp; <a href=\"http://pages.ebay.com/search.html\">Search</a>&nbsp; <a href=\"http://pages.ebay.com/contact.html\">Help</a>&nbsp; <a href=\"http://pages.ebay.com/newschat.html\">News/Chat</a>&nbsp; <a href=\"http://pages.ebay.com/sitemap.html\">Site Map</a></font></strong></p>\n"
//"\n";

// This function fills in the structures, but does not write them.
void clsFillTemplates::Run()
{
	int i;

	// kakiyama 07/19/99
	char tempURL[512];
	clsMarketPlace *theMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	// Hard code to 1 until we implement cobranding for this.
	mNumPartners = 1;

	// Fill in what we know from the start about the
	// header.
	mHeader.magicNumber = TEMPLATES_PAGEINDEX_MAGIC;

	// We know the partners immediately follow the header,
	// so their offset will be the size of the header.
	mHeader.partnersOffset = sizeof (mHeader);

	// For now, these are hardcoded.
	
//	mHeader.newURLOffset = mpText->AddString(sNewURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<img height=11 width=28 alt=\"[NEW!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "new.gif\">");
	mHeader.newURLOffset = mpText->AddString(tempURL);

//	mHeader.hotURLOffset = mpText->AddString(sHotURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<img height=11 width=28 alt=\"[HOT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "hot.gif\">");
	mHeader.hotURLOffset = mpText->AddString(tempURL);


//	mHeader.picURLOffset = mpText->AddString(sPicURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<img height=11 width=28 alt=\"[PIC!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "pic.gif\">");
	mHeader.picURLOffset = mpText->AddString(tempURL);

//	mHeader.viewItemURLOffset = mpText->AddString(sViewItemURL);
// kakiyama 07/19/99
	strcpy(tempURL, theMarketPlace->GetCGIPath());
	strcat(tempURL, "eBayISAPI.dll?ViewItem&item=");
	mHeader.viewItemURLOffset = mpText->AddString(tempURL);


//	mHeader.featuredURLOffset = mpText->AddString(sFeaturedPath);
// kakiyama 07/19/99
	strcpy(tempURL, theMarketPlace->GetCGIPath());
	strcat(tempURL, "eBayISAPI.dll?Featured");
	mHeader.featuredURLOffset = mpText->AddString(tempURL);



//	mHeader.giftFatherURLOffset = mpText->AddString(sFatherGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/father.gif\"></A>");
	mHeader.giftFatherURLOffset = mpText->AddString(tempURL);


//	mHeader.giftRosieURLOffset = mpText->AddString(sRosieGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "rosie_ro.gif\"></A>");
	mHeader.giftRosieURLOffset = mpText->AddString(tempURL);


//	mHeader.giftAnniversaryURLOffset = mpText->AddString(sAnniversaryGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/anniversary.gif\"></A>");
	mHeader.giftAnniversaryURLOffset = mpText->AddString(tempURL);

//	mHeader.giftBabyURLOffset = mpText->AddString(sBabyGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/baby.gif\"></A>");
	mHeader.giftBabyURLOffset = mpText->AddString(tempURL);


//	mHeader.giftBirthdayURLOffset = mpText->AddString(sBirthdayGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetCGIPath());
	strcat(tempURL, "gift/birthday.gif\"></A>");
	mHeader.giftBirthdayURLOffset = mpText->AddString(tempURL);


//	mHeader.giftChristmasURLOffset = mpText->AddString(sChristmasGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/christmas.gif\"></A>");
	mHeader.giftChristmasURLOffset = mpText->AddString(tempURL);

//	mHeader.giftEasterURLOffset = mpText->AddString(sEasterGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/easter.gif\"></A>");
	mHeader.giftEasterURLOffset = mpText->AddString(tempURL);


//	mHeader.giftGraduationURLOffset = mpText->AddString(sGraduationGiftURL);
// kakiyama 07/19/99 
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/graduation.gif\"></A>");
	mHeader.giftGraduationURLOffset = mpText->AddString(tempURL);

//	mHeader.giftHalloweenURLOffset = mpText->AddString(sHalloweenGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/halloween.gif\"></A>");
	mHeader.giftHalloweenURLOffset = mpText->AddString(tempURL);




//	mHeader.giftHanukahURLOffset = mpText->AddString(sHanukahGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/hanukah.gif\"></A>");
	mHeader.giftHanukahURLOffset = mpText->AddString(tempURL);




//	mHeader.giftJuly4thURLOffset = mpText->AddString(sJuly4thGiftURL);
// kakiyama 07/19/99
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/July4th.gif\"></A>");
	mHeader.giftJuly4thURLOffset = mpText->AddString(tempURL);


//	mHeader.giftMotherURLOffset = mpText->AddString(sMotherGiftURL);
// kakiyama 07/19/99 mother.gif\"></A>"
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/mother.gif\"></A>");
	mHeader.giftMotherURLOffset = mpText->AddString(tempURL);


//	mHeader.giftStpatrickURLOffset = mpText->AddString(sStpatrickGiftURL);
// kakiyama 07/19/99 stpatrick.gif\"></A>"
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/mother.gif\"></A>");
	mHeader.giftStpatrickURLOffset = mpText->AddString(tempURL);


//	mHeader.giftThanksgivingURLOffset = mpText->AddString(sThanksgivingGiftURL);
// kakiyama 07/19/99 thanksgiving.gif\"></A>"
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/thanksgiving.gif\"></A>");
	mHeader.giftThanksgivingURLOffset = mpText->AddString(tempURL);

//	mHeader.giftValentineURLOffset = mpText->AddString(sValentineGiftURL);
// kakiyama 07/19/99 valentine.gif\"></A>"
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/valentine.gif\"></A>");
	mHeader.giftValentineURLOffset = mpText->AddString(tempURL);

//	mHeader.giftWeddingURLOffset = mpText->AddString(sWeddingGiftURL);
// kaiyama 07/19/99 wedding.gif\"></A>"
	strcpy(tempURL, "<A HREF=\"");
	strcat(tempURL, theMarketPlace->GetHTMLPath());
	strcat(tempURL, "gift-icon.html\"><img border=0 hspace=2 height=14 width=24 alt=\"[GIFT!]\" src=\"");
	strcat(tempURL, theMarketPlace->GetPicsPath());
	strcat(tempURL, "gift/wedding.gif\"></A>");
	mHeader.giftWeddingURLOffset = mpText->AddString(tempURL);

	mHeader.searchURLOffset = mpText->AddString(sSearchLink);

	mHeader.numPartners = mNumPartners;
	mHeader.textOffset = mHeader.partnersOffset + (sizeof (templatesPartnerEntry) * mNumPartners);

	// And make the partner(s)
	mpPartners = new templatesPartnerEntry[1];

	// And walk through filling them.
	for (i = 0; i < mNumPartners; ++i)
		FillPartner(mpPartners + i);

	mHeader.AdsOffset = mHeader.textOffset + mpText->GetSafeWriteSize();
	mHeader.piecesOffset = mHeader.AdsOffset + sizeof(int32_t) * mpAds->size(); 

	// Set the category pieces.
	//mHeader.headerInfosOffset = mHeader.textOffset + mpText->GetSafeWriteSize();

	// And this is the last thing we needed.
	//mHeader.piecesOffset = mHeader.headerInfosOffset + (sizeof (templatesCategoryHeaderEntry) * mpHeaders->size());
}

// A couple of macros to make the code more readable.
#define FILL_PIECE(x) thePiece.pieceType = (x); thePiece.textOffset = 0;	\
	mpPieces->push_back(thePiece)
#define FILL_TEXT_PIECE(x, y) thePiece.pieceType = (x); thePiece.textOffset = (y);	\
	mpPieces->push_back(thePiece);

// This fills one partner entry structure.
void clsFillTemplates::FillPartner(templatesPartnerEntry *pPartner)
{

	const int maxCatNumber = 3000;

	// Used by the macro to fill the templates.
	templatesPieceEntry thePiece;
//	templatesCategoryHeaderEntry theHeader;
	int32_t theAds;

	// vectors for headers and footers
	HeaderVector vHeader;
	FooterVector vFooter;

	HeaderVector::iterator j;
	FooterVector::iterator k;

	const char * theHead;
	const char * theFoot;


	// Vectors for the ads
	PartnerAdVector vAdsAll;
	PartnerAdVector vAdsGallery;
	PartnerAdVector vAdsFeatured;
	PartnerAdVector vAdsHot;
	PartnerAdVector vAdsGrabbag;
	PartnerAdVector vAdsCategory;

	PartnerAdVector::iterator m;

	int i;
//	int32_t zeroHeader;
//	int32_t zeroFooter;
	int32_t zeroAd;

	const char * theAd;

	// Here are the headers for listings


	// The offset and size.
	int32_t startingOffset;
	int32_t theSize;

	// A number for some text.
	int32_t bidDescriptionNumber;

	// Set bidDescriptionNumber;
//	bidDescriptionNumber = mpText->AddString(sBidText);
// kakiyama 07/19/99
	// TODO - localize this
	bidDescriptionNumber = mpText->AddString(clsIntlResource::GetFResString(-1,
										"<p><p><br>"
										"<font size=-1>Click on a title to get a description and to bid on that item.\n"
										"A <font color=\"red\">red</font> ending time indicates that an auction is ending in less than five hours.\n"
										"These items are not verified by eBay;\n"
										"<a href=\"%{1:GetHTMLPath}help/community/png.html\">caveat emptor.</a>\n"
										"This page is updated regularly; don't forget to use your browser's <strong>reload</strong>\n"
										"button for the latest version. The system may be unavailable during regularly scheduled maintenance,\n"
										"Mondays, 12 a.m. to 4 a.m. Pacific Time (Mondays, 00:01 a.m. to 04:00 a.m., eBay time).</font> <P>\n",
										clsIntlResource::ToString(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetHTMLPath()),
										NULL));


	// We fill in the templates here for the pages, then do the header
	// and footer after that's done.

	// Here's the level 0 going type for page 1.
	// We do this and then fill in the header structure.

	// First, get the current offset, which is just how many pieces
	// we already have.
	startingOffset = mpPieces->size();

	// Do the actual pieces.
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(categoryPiece);
	FILL_PIECE(allHeaderPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(itemsPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber); // The bidding text.
	FILL_PIECE(bidSponsorPiece);

	// The size is how much the size of the pieces has changed.
	theSize = mpPieces->size() - startingOffset;

	// Now fill the templates. We only have to fill 1 spot for this.
	// 0 is the template number, for level 0 page 1.
	pPartner->theTemplates[0].goingTemplateOffset = startingOffset;
	pPartner->theTemplates[0].goingTemplateSize = theSize;

	// Now we set the offset ahead again.
	startingOffset += theSize;

	// Level 0 page 1 types other than going
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(featuredPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(categoryPiece);
	FILL_PIECE(hotPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber); // The bidding text.
	FILL_PIECE(bidSponsorPiece);

	// Calculate the size.
	theSize = mpPieces->size() - startingOffset;

	// We have to fill the whole structure this time (except, of course, for going).
	// We're still on template 0 at this point.
	pPartner->theTemplates[0].normalTemplateOffset = startingOffset;
	pPartner->theTemplates[0].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[0].endingTemplateOffset = startingOffset;
	pPartner->theTemplates[0].completedTemplateOffset = startingOffset;

	pPartner->theTemplates[0].normalTemplateSize = theSize;
	pPartner->theTemplates[0].newTodayTemplateSize = theSize;
	pPartner->theTemplates[0].endingTemplateSize = theSize;
	pPartner->theTemplates[0].completedTemplateSize = theSize;

	// Now we set the offset ahead again.
	startingOffset += theSize;

	// Do the actual pieces.
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(itemsPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber); // The bidding text.
	FILL_PIECE(bidSponsorPiece);

	// The size is how much the size of the pieces has changed.
	theSize = mpPieces->size() - startingOffset;

	// Now fill the templates. We only have to fill 1 spot for this.
	// 1 is the template number, for level 0 page 2+.
	pPartner->theTemplates[1].goingTemplateOffset = startingOffset;
	pPartner->theTemplates[1].goingTemplateSize = theSize;

	// And reset the offset.
	startingOffset += theSize;

	// Level 1 only 'current' page type
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(categoryPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber);
	FILL_PIECE(bidSponsorPiece);

	// Get the size.
	theSize = mpPieces->size() - startingOffset;

	// We have to fill two structures this time. We'll fill one and then just
	// copy it to the other one. We're on templates 2 and 3 right now.
	// 1 is unused in the current scheme of things, so we'll go back and
	// zero it before we finish, but not right now.
	pPartner->theTemplates[2].normalTemplateOffset = startingOffset;
	pPartner->theTemplates[2].completedTemplateOffset = startingOffset;

	pPartner->theTemplates[2].normalTemplateSize = theSize;
	pPartner->theTemplates[2].completedTemplateSize = theSize;

	// Copy it to template 3.
	memcpy(&(pPartner->theTemplates[3]), &(pPartner->theTemplates[2]), 
		sizeof (templatesListingTypeEntry));

	// Reset the offset.
	startingOffset += theSize;

	// All other levels, all types, page 1, and going level 1
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(categoryPiece);
	FILL_PIECE(featuredPiece);
	FILL_PIECE(allHeaderPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(itemsPiece);
	FILL_PIECE(hotPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(bottomTitleBoxPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber);
	FILL_PIECE(bidSponsorPiece);

	// Get the size.
	theSize = mpPieces->size() - startingOffset;

	// These will be templates 4 6 and 8 (5 7 and 9 are page > 1)
	// These are also the template 2 except for current and completed
	pPartner->theTemplates[4].normalTemplateOffset = startingOffset;
	pPartner->theTemplates[4].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[4].endingTemplateOffset = startingOffset;
	pPartner->theTemplates[4].completedTemplateOffset = startingOffset;
	pPartner->theTemplates[4].goingTemplateOffset = startingOffset;

	pPartner->theTemplates[2].goingTemplateOffset = startingOffset;
	pPartner->theTemplates[2].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[2].endingTemplateOffset = startingOffset;

	pPartner->theTemplates[4].normalTemplateSize = theSize;
	pPartner->theTemplates[4].newTodayTemplateSize = theSize;
	pPartner->theTemplates[4].endingTemplateSize = theSize;
	pPartner->theTemplates[4].completedTemplateSize = theSize;
	pPartner->theTemplates[4].goingTemplateSize = theSize;

	pPartner->theTemplates[2].goingTemplateSize = theSize;
	pPartner->theTemplates[2].newTodayTemplateSize = theSize;
	pPartner->theTemplates[2].endingTemplateSize = theSize;

	// Copy it to template 6.
	memcpy(&(pPartner->theTemplates[6]), &(pPartner->theTemplates[4]), 
		sizeof (templatesListingTypeEntry));
	// Copy it to template 8.
	memcpy(&(pPartner->theTemplates[8]), &(pPartner->theTemplates[4]), 
		sizeof (templatesListingTypeEntry));

	// Reset the offset.
	startingOffset += theSize;

	// All other levels, all types, pages > 1
	FILL_PIECE(adPiece);
	FILL_PIECE(timeAndSearchPiece);
	FILL_PIECE(topTitleBoxPiece);
	FILL_PIECE(listingTypesPiece);
	FILL_PIECE(jumpLinkPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(itemsPiece);
	FILL_PIECE(pageLinksPiece);
	FILL_PIECE(bottomTitleBoxPiece);
	FILL_TEXT_PIECE(textPiece, bidDescriptionNumber);
	FILL_PIECE(bidSponsorPiece);

	// Get the size.
	theSize = mpPieces->size() - startingOffset;

	// These will be templates 5 7 and 9 (4 6 and 8 are page == 1)
	pPartner->theTemplates[5].normalTemplateOffset = startingOffset;
	pPartner->theTemplates[5].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[5].endingTemplateOffset = startingOffset;
	pPartner->theTemplates[5].completedTemplateOffset = startingOffset;
	pPartner->theTemplates[5].goingTemplateOffset = startingOffset;
	pPartner->theTemplates[3].goingTemplateOffset = startingOffset;

	pPartner->theTemplates[3].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[3].endingTemplateOffset = startingOffset;

	pPartner->theTemplates[5].normalTemplateSize = theSize;
	pPartner->theTemplates[5].newTodayTemplateSize = theSize;
	pPartner->theTemplates[5].endingTemplateSize = theSize;
	pPartner->theTemplates[5].completedTemplateSize = theSize;
	pPartner->theTemplates[5].goingTemplateSize = theSize;
	pPartner->theTemplates[3].goingTemplateSize = theSize;

	pPartner->theTemplates[3].newTodayTemplateSize = theSize;
	pPartner->theTemplates[3].endingTemplateSize = theSize;

	// Copy it to template 7.
	memcpy(&(pPartner->theTemplates[7]), &(pPartner->theTemplates[5]), 
		sizeof (templatesListingTypeEntry));
	// Copy it to template 9.
	memcpy(&(pPartner->theTemplates[9]), &(pPartner->theTemplates[5]), 
		sizeof (templatesListingTypeEntry));

	// We need to clear template 1 before we're done.
	// We set bogus values to do so.
	startingOffset = -1;
	theSize = 0;

	pPartner->theTemplates[1].normalTemplateOffset = startingOffset;
	pPartner->theTemplates[1].newTodayTemplateOffset = startingOffset;
	pPartner->theTemplates[1].endingTemplateOffset = startingOffset;
	pPartner->theTemplates[1].completedTemplateOffset = startingOffset;

	pPartner->theTemplates[1].normalTemplateSize = theSize;
	pPartner->theTemplates[1].newTodayTemplateSize = theSize;
	pPartner->theTemplates[1].endingTemplateSize = theSize;
	pPartner->theTemplates[1].completedTemplateSize = theSize;


	// Here is the new code from AOL co-branding
	// At this moment, this is only for the AOL cobranding,
	// But in the near future, this code will be changed to generate multiple template files
	// for different sites, cobrand. --- Stevey

	// Now get the header
	gApp->GetDatabase()->GetSitePartnerHeadersAndFooters((int)SITE_EBAY_MAIN, (int)PARTNER_AOL, &vHeader, &vFooter);

	// Find the headers (we have 4 different headers and footers for listings) 
	// (both top category and real categories have the same header/footer stored in db)

	cout << "# of headers are : " << vHeader.size() << endl;
	for (j = vHeader.begin(); j != vHeader.end(); ++j)
	{

		cout << "pagetype: " << (*j)->GetPageType() << '\t' << "2nd type: " << (*j)->GetSecondaryPageType() << '\n' ;
		// Find the header for the all item pages
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubAllItem == (*j)->GetSecondaryPageType()))
		{	
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetAll = mpText->AddString(theHead);

		}

		// Find the header for the featured item pages
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubFeatured == (*j)->GetSecondaryPageType()))
		{
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetFeatured = mpText->AddString(theHead);
		}

		// Find the header for the hot item pages
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubHot == (*j)->GetSecondaryPageType()))
		{
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetHot = mpText->AddString(theHead);
		}

		// Find the header for the gallery item pages
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubGallery == (*j)->GetSecondaryPageType()))
		{
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetGallery = mpText->AddString(theHead);
		}

		// Find the header for the grabbag page
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubGrabbag == (*j)->GetSecondaryPageType()))
		{
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetGrabbag = mpText->AddString(theHead);
		}

		// Find the header for the category pages (overview, adult, etc)
		if (((int)HeaderBrowse == (*j)->GetPageType()) && ((int)HeaderSubCategory == (*j)->GetSecondaryPageType()))
		{
			theHead = (*j)->GetText();
			assert(theHead);
			pPartner->headersOffsetCategory = mpText->AddString(theHead);
		}

	} // end of header


	// Find the footers (we have 4 different headers and footers for listings) 
	// (both top category and real categories have the same header/footer stored in db)
//	for (k = vFooter.begin(); k != vFooter.end(); ++k)
//	{

		k = vFooter.begin();

		theFoot = (*k)->GetText();
		assert(theFoot);
		pPartner->footersOffsetAll = mpText->AddString(theFoot);
		pPartner->footersOffsetFeatured = mpText->AddString(theFoot);
		pPartner->footersOffsetHot = mpText->AddString(theFoot);
		pPartner->footersOffsetGallery = mpText->AddString(theFoot);
		pPartner->footersOffsetGrabbag = mpText->AddString(theFoot);
		pPartner->footersOffsetCategory = mpText->AddString(theFoot);

/*
		// Find the footer for the all item pages
		if (((int)HeaderBrowse == (*k)->GetPageType()) && ((int)HeaderSubAllItem == (*k)->GetSecondaryPageType()))
		{
			theFoot = (*k)->GetText();
			assert(theFoot);
			pPartner->footersOffsetAll = mpText->AddString(theFoot);
		}

		// Find the footer for the featured item pages
		if (((int)HeaderBrowse == (*k)->GetPageType()) && ((int)HeaderSubFeatured == (*k)->GetSecondaryPageType()))
		{
			theFoot = (*k)->GetText();
			assert(theFoot);
			pPartner->footersOffsetFeatured = mpText->AddString(theFoot);
		}

		// Find the footer for the hot item pages
		if (((int)HeaderBrowse == (*k)->GetPageType()) && ((int)HeaderSubHot == (*k)->GetSecondaryPageType()))
		{
			theFoot = (*k)->GetText();
			assert(theFoot);
			pPartner->footersOffsetHot = mpText->AddString(theFoot);
		}

		// Find the footer for the gallery item pages
		if (((int)HeaderBrowse == (*k)->GetPageType()) && ((int)HeaderSubGallery == (*k)->GetSecondaryPageType()))
		{
			theFoot = (*k)->GetText();
			assert(theFoot);
			pPartner->footersOffsetGallery = mpText->AddString(theFoot);
		}


		// Find the footer for the grabbag page
		if (((int)HeaderBrowse == (*k)->GetPageType()) && ((int)HeaderSubGrabbag == (*k)->GetSecondaryPageType()))
		{
			theFoot = (*k)->GetText();
			assert(theFoot);
			pPartner->footersOffsetGrabbag = mpText->AddString(theFoot);
		}
*/

//	} // end of footer

	clsMarketPlace *marketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	clsSite * site = marketPlace->GetSites()->GetSite((int)SITE_EBAY_MAIN);
	clsPartners * pPartners = site->GetPartners();
	clsPartner * pAdPartner = pPartners->GetPartner((int)PARTNER_AOL);
	clsPartnerAds * pAds = pAdPartner->GetPartnerAds();		

	// Retrieve the ads for all item pages, featured item pages, gallery pages, and hot item pages
	pAds->GetPartnerAdsByPageType(&vAdsAll, PageType1, PageType7);
	pAds->GetPartnerAdsByPageType(&vAdsFeatured, PageType1, PageType2);
	pAds->GetPartnerAdsByPageType(&vAdsGallery, PageType1, PageType8);
	pAds->GetPartnerAdsByPageType(&vAdsHot, PageType1, PageType3);
	pAds->GetPartnerAdsByPageType(&vAdsGrabbag, PageType1, PageType4);
	pAds->GetPartnerAdsByPageType(&vAdsCategory, PageType1, PageType4);


	bool bCategoryIsFound;

	// Fill out the template for the ads of all item pages
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);

	//zeroFooter = mpText->AddString(sCommonAd);
	//theHeader.headerOffset = zeroHeader;
	//theHeader.footerOffset = zeroFooter;

	for (i = 0; i <= maxCatNumber; ++i)
	{
		// Reset this bool to false for each category
		bCategoryIsFound = false;

		for (m = vAdsAll.begin(); m != vAdsAll.end(); ++m)
		{
			// Find the category
			if((*m)->GetContextSensitiveValue() == i)
			{
				bCategoryIsFound = true;
				theAd = (*m)->GetText();

				// This category has ad, write it to the template
				if (theAd)
				{
					theAds = mpText->AddString(theAd);
					mpAds->push_back(theAds);
	cout << "The all ad: " << theAds << endl;
				}
				// This category has no ad, point to the default (default will not be displayed in listings)
				else
				{
					mpAds->push_back(zeroAd);
				}

				break;
			}
		}

		// If the category can not be found, point to the default 
		if (!bCategoryIsFound)
		{
			mpAds->push_back(zeroAd);
		}
	}

	pPartner->adsOffsetAll = startingOffset;
	pPartner->adsSizeAll = mpAds->size() - startingOffset;

	// We are done for filing out the template for all item pages


	// Fill out the template for the ads of featured item pages
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);

	for (i = 0; i <= maxCatNumber; ++i)
	{
		// Reset this bool to false for each category
		bCategoryIsFound = false;

		for (m = vAdsFeatured.begin(); m != vAdsFeatured.end(); ++m)
		{
			// Find the category
			if((*m)->GetContextSensitiveValue() == i)
			{
				bCategoryIsFound = true;
				theAd = (*m)->GetText();

				// This category has ad, write it to the template
				if (theAd)
				{
					theAds = mpText->AddString(theAd);
					mpAds->push_back(theAds);
	cout << "The featured ad: " << theAds << endl;
				}
				// This category has no ad, point to the default (default will not be displayed in listings)
				else
				{
					mpAds->push_back(zeroAd);
				}

				break;
			}
		}

		// If the category can not be found, point to the default 
		if (!bCategoryIsFound)
		{
			mpAds->push_back(zeroAd);
		}
	}

	pPartner->adsOffsetFeatured = startingOffset;
	pPartner->adsSizeFeatured = mpAds->size() - startingOffset;

	// We are done for filing out the template for featured item pages


	// Fill out the template for the ads of hot item pages
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);

	for (i = 0; i <= maxCatNumber; ++i)
	{
		// Reset this bool to false for each category
		bCategoryIsFound = false;

		for (m = vAdsHot.begin(); m != vAdsHot.end(); ++m)
		{
			// Find the category
			if((*m)->GetContextSensitiveValue() == i)
			{
				bCategoryIsFound = true;
				theAd = (*m)->GetText();

				// This category has ad, write it to the template
				if (theAd)
				{
					theAds = mpText->AddString(theAd);
					mpAds->push_back(theAds);
	cout << "The hot ad: " << theAds << endl;
				}
				// This category has no ad, point to the default (default will not be displayed in listings)
				else
				{
					mpAds->push_back(zeroAd);
				}

				break;
			}
		}

		// If the category can not be found, point to the default 
		if (!bCategoryIsFound)
		{
			mpAds->push_back(zeroAd);
		}
	}

	pPartner->adsOffsetHot = startingOffset;
	pPartner->adsSizeHot = mpAds->size() - startingOffset;

	// We are done for filing out the template for hot item pages


	// Fill out the template for the ads of gallery item pages
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);
cout << "zZero ads: " << '\n' << mpText->GetBuffer() << endl;
cout << "sCommonAd: " << sCommonAd << endl;
	for (i = 0; i <= maxCatNumber; ++i)
	{
		// Reset this bool to false for each category
		bCategoryIsFound = false;

		for (m = vAdsGallery.begin(); m != vAdsGallery.end(); ++m)
		{
			// Find the category
			if((*m)->GetContextSensitiveValue() == i)
			{
				bCategoryIsFound = true;
				theAd = (*m)->GetText();

				// This category has ad, write it to the template
				if (theAd)
				{
					theAds = mpText->AddString(theAd);
					mpAds->push_back(theAds);
				}
				// This category has no ad, point to the default (default will not be displayed in listings)
				else
				{
					mpAds->push_back(zeroAd);
				}

				break;
			}
		}

		// If the category can not be found, point to the default 
		if (!bCategoryIsFound)
		{
			mpAds->push_back(zeroAd);
		}
	}

	pPartner->adsOffsetGallery = startingOffset;
	pPartner->adsSizeGallery = mpAds->size() - startingOffset; // done with ads for gallery


	// We have no more than one ad for Grabbag
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);

	if (vAdsGrabbag.size() > 0)
	{
		theAd = (*(vAdsGrabbag.begin()))->GetText();

		if (theAd)
		{
			theAds = mpText->AddString(theAd);
			mpAds->push_back(theAds);
		}
		// This category has no ad, point to the default (default will not be displayed in listings)
		else
		{
			mpAds->push_back(zeroAd);
		}
	}
	else
	{
		mpAds->push_back(zeroAd);
	}

	pPartner->adsOffsetGrabbag = startingOffset; // done with ad for grabbag

	// We have no more than one ad for overview
	startingOffset = mpAds->size();
	zeroAd = mpText->AddString(sCommonAd);

	if (vAdsCategory.size() > 0)
	{
		theAd = (*(vAdsCategory.begin()))->GetText();

		if (theAd)
		{
			theAds = mpText->AddString(theAd);
			mpAds->push_back(theAds);
		}
		// This category has no ad, point to the default (default will not be displayed in listings)
		else
		{
			mpAds->push_back(zeroAd);
		}
	}
	else
	{
		mpAds->push_back(zeroAd);
	}

	pPartner->adsOffsetCategory = startingOffset; // done with ad for category

}

// Clean up the macros.
#undef FILL_PIECE
#undef FILL_TEXT_PIECE

// A macro to reverse the byte order. Define this to be semantically null
// if the byte order of the producing machine and the byte
// order of the target machine are the same.

#ifdef _MSC_VER
#define FIX_BYTE_ORDER32(x)
#define FIX_BYTE_ORDER16(x)
#else
// long
#define FIX_BYTE_ORDER32(x)	(x) = ((((x) >> 24) & 0xFF) | \
				       (((x) >> 16) & 0xFF) << 8 | \
				       (((x) >> 8) & 0xFF) << 16 | \
					((x) & 0xFF) << 24)

// short
#define FIX_BYTE_ORDER16(x)	(x) = ((((x) >> 8) & 0xFF) | \
					((x) & 0xFF) << 8)
#endif

// Here we write the binary file necessary for the 'listings'
// project.
// First we fix up the structures before writing them out,
// by calling the FIX_BYTE_ORDER macro.
void clsFillTemplates::WriteBinaryToStream(ostream *pStream)
{
	int i;
	int k;
	templatesPartnerEntry *pPartner;
	list<templatesPieceEntry>::iterator j;
//	list<templatesCategoryHeaderEntry>::iterator n;


	// Fix the headerEntry first.
	FIX_BYTE_ORDER32(mHeader.magicNumber);
	FIX_BYTE_ORDER32(mHeader.partnersOffset);
	FIX_BYTE_ORDER32(mHeader.numPartners);
	FIX_BYTE_ORDER32(mHeader.textOffset);
	FIX_BYTE_ORDER32(mHeader.piecesOffset);
	FIX_BYTE_ORDER32(mHeader.newURLOffset);
	FIX_BYTE_ORDER32(mHeader.hotURLOffset);
	FIX_BYTE_ORDER32(mHeader.picURLOffset);
	FIX_BYTE_ORDER32(mHeader.viewItemURLOffset);
	FIX_BYTE_ORDER32(mHeader.featuredURLOffset);
	FIX_BYTE_ORDER32(mHeader.searchURLOffset);
	FIX_BYTE_ORDER32(mHeader.AdsOffset);
	FIX_BYTE_ORDER32(mHeader.giftFatherURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftRosieURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftAnniversaryURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftBabyURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftBirthdayURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftChristmasURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftEasterURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftGraduationURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftHalloweenURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftHanukahURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftJuly4thURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftMotherURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftStpatrickURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftThanksgivingURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftValentineURLOffset);
	FIX_BYTE_ORDER32(mHeader.giftWeddingURLOffset);


	// And write it out.
	pStream->write((const char *) &mHeader, sizeof (mHeader));

	// Now we fix the partners.
	for (i = 0; i < mNumPartners; ++i)
	{
		pPartner = mpPartners + i;

		// Fix all the templates.
		for (k = 0; k < 10; ++k)
		{
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].normalTemplateOffset);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].newTodayTemplateOffset);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].endingTemplateOffset);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].completedTemplateOffset);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].goingTemplateOffset);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].normalTemplateSize);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].newTodayTemplateSize);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].endingTemplateSize);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].completedTemplateSize);
			FIX_BYTE_ORDER32(pPartner->theTemplates[k].goingTemplateSize);
		}

		FIX_BYTE_ORDER32(pPartner->headersOffsetAll);
		FIX_BYTE_ORDER32(pPartner->headersOffsetFeatured);
		FIX_BYTE_ORDER32(pPartner->headersOffsetHot);
		FIX_BYTE_ORDER32(pPartner->headersOffsetGallery);
		FIX_BYTE_ORDER32(pPartner->headersOffsetGrabbag);
		FIX_BYTE_ORDER32(pPartner->headersOffsetCategory);
		FIX_BYTE_ORDER32(pPartner->footersOffsetAll);
		FIX_BYTE_ORDER32(pPartner->footersOffsetFeatured);
		FIX_BYTE_ORDER32(pPartner->footersOffsetHot);
		FIX_BYTE_ORDER32(pPartner->footersOffsetGallery);
		FIX_BYTE_ORDER32(pPartner->footersOffsetGrabbag);
		FIX_BYTE_ORDER32(pPartner->footersOffsetCategory);
		FIX_BYTE_ORDER32(pPartner->adsOffsetAll);
		FIX_BYTE_ORDER32(pPartner->adsOffsetFeatured);
		FIX_BYTE_ORDER32(pPartner->adsOffsetHot);
		FIX_BYTE_ORDER32(pPartner->adsOffsetGallery);
		FIX_BYTE_ORDER32(pPartner->adsSizeAll);
		FIX_BYTE_ORDER32(pPartner->adsSizeFeatured);
		FIX_BYTE_ORDER32(pPartner->adsSizeHot);
		FIX_BYTE_ORDER32(pPartner->adsSizeGallery);
		FIX_BYTE_ORDER32(pPartner->adsOffsetGrabbag);
		FIX_BYTE_ORDER32(pPartner->adsOffsetCategory);


		// And write it out.
		pStream->write((const char *) pPartner, sizeof (templatesPartnerEntry));
	}

	// Write out the text. No need to reverse the order, since it's
	// not multibyte (at least, not yet.)
	pStream->write(mpText->GetBuffer(), mpText->GetSafeWriteSize());
cout << "mpText is : \n" << mpText->GetBuffer() << endl;
	// Write out the header pieces.
/*
	for (n = mpHeaders->begin(); n != mpHeaders->end(); ++n)
	{
		// Fix it.
		FIX_BYTE_ORDER32((*n).headerOffset);
		FIX_BYTE_ORDER32((*n).footerOffset);

		// And write it.
		pStream->write((const char *) &(*n), sizeof (templatesCategoryHeaderEntry));
	}
*/

	// And write out the template pieces. Then we're done.

	for (j = mpPieces->begin(); j != mpPieces->end(); ++j)
	{
		// Fix it.
		FIX_BYTE_ORDER32((*j).pieceType);
		FIX_BYTE_ORDER32((*j).textOffset);

		// And write it.
		pStream->write((const char *) &(*j), sizeof (templatesPieceEntry));
	}


	// That's it

}

// Clean up macros.
#undef FIX_BYTE_ORDER32
#undef FIX_BYTE_ORDER16
