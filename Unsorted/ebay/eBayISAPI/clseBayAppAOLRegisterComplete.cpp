/*	$Id: clseBayAppAOLRegisterComplete.cpp	*/
//
//	File:	clseBayAppAOLRegisterComplete.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Registration Complete
//
// Modifications:
//				- 06/08/99 Lou	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//

#include "ebihdr.h"

//
// Support for our own personal crypt
#include "malloc.h"		// Crypt uses malloc
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Header text
static const char *HeaderText =
" Retrieve confirmation email";

// Registration Banner
static const char *RegistrationBannerPart1 =
"<table border=\"1\" width=\"669\" cellspacing=\"0\" bgcolor=\"#99CCCC\" cellpadding=\"2\">"
"<tr>"
"<td width=\"663\"><p align=\"center\"><strong><font size=\"5\">"
"eBay at AOL Registration</font>&nbsp;&nbsp;</strong>\n";

//Name of Image
static const char *RegBannerImage =
"flag-us.gif";

//Leave space for the image that will go along with the text
static const char *RegistrationBannerPart2 =
"</td></tr></table>\n";

// Title text
static const char *TitleText1 =
"<blockquote><h2>Congratulations '<u><em>";

// Title text part 2
static const char *TitleText2 =
"</em></u>'!&nbsp; You are now a registered eBay member.</h2>"
"<p>Thank you for completing your eBay registration. Your registration is "
"confirmed and is effective immediately. You can start bidding and selling right away.</p>\n"
"<p>We're glad you're here! eBay is the world's largest personal online "
"trading community. &nbsp; Individuals use eBay to buy and sell items in more "
"than 1000 categories - from antiques to collectibles to computers - you'll find "
"whatever you're looking for here at eBay! You'll also have a lot of fun doing it "
"- because everything you buy here is sold in an auction format.</p>\n"
"<p>Before you get started, we would really appreciate if you could spend a "
"moment taking our <a href=\"../prototype2/survey\"><b>New eBay Member Survey."
"</b></a>&nbsp; Your feedback will help us make eBay a more efficient and fun place to visit.</p>\n"
"<hr><p>Now for a few helpful tips about trading at eBay.</p>\n"
"<h4>Bid smart, bid safe!</h4><ul><li>When you are considering placing a bid on an "
"item, first check the Seller's feedback rating.&nbsp; Next to every User ID is a "
"number in parentheses - for example, <u><font color=\"#0000FF\">skippy (173)</font></u>.&nbsp;"
" The number represents the member's 'feedback rating', a summary of all the "
"feedback comments other eBay members have left about this person. If you click on "
"the number, you can read the details of what other members have said about that "
"particular member.</li><li>Get to know your trading partner.&nbsp; If you have "
"questions about the item up for bid in an auction listing, email the seller! "
"The seller will appreciate your interest in their auction. </li>\n"
"<li>Ask questions! You can get help by clicking on Help located at the top of "
"every eBay page. You can also click on Community to locate a Chat Board where "
"you can chat with other users.</li>\n"
"</ul><h4>Sell wise, sell well!</h4><ul><li>Describe your item or service as "
"fully as possible. Try to anticipate questions people may have, and include all "
"the relevant information in your description. </li>\n"
"<li>A picture is worth a thousand words. Add a photo so that buyers can see "
"your item in more detail. </li>\n"
"<li>Provide your terms for sale. Include your preferred payment method and "
"shipping terms.</li></ul><h4>Above all else, enjoy yourself on eBay!&nbsp; "
"Our members think this is a great place to be, and we hope you do too.</h4>"
"</blockquote>\n";

// Title text part 3

// kakiyama 07/08/99 - commented out
// resourced using clsIntlResource::GetFResString

/*
static const char *TitleText3 =
"<div ALIGN=\"CENTER\"><p>Click on a button below for more information</p>\n"
"<table WIDTH=\"580\" BORDER=\"0\" CELLSPACING=\"20\" CELLPADDING=\"0\">\n"
"<tr><td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"http://pages.ebay.com/help/help-newto.html\">"
"<img SRC=\"http://pics.ebay.com/aw/pics/new-to-ebay.gif\" WIDTH=\"90\" "
"HEIGHT=\"28\" BORDER=\"0\"></a></td>\n"
"<td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"http://pages.ebay.com/help/help-new-bidding.html\">"
"<img SRC=\"http://pics.ebay.com/aw/pics/new-to-bidding.gif\" WIDTH=\"90\" "
"HEIGHT=\"28\" BORDER=\"0\"></a></td>\n"
"<td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"http://pages.ebay.com/help/help-new-selling.html\">"
"<img SRC=\"http://pics.ebay.com/aw/pics/new-to-selling.gif\" WIDTH=\"90\" "
"HEIGHT=\"28\" BORDER=\"0\"></a></td></tr></table></div>\n";
*/


void clseBayApp::AOLRegisterComplete(CEBayISAPIExtension *pServer,
							char * pUserId)
{
	// Setup
	SetUp();	

	// Whatever happens, we need a title and a standard header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	HeaderText
				<<	"</TITLE>"
				<<	"</HEAD>";

//	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
//	else
//		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";

	//Display the Registration Banner with image
	*mpStream	<<	RegistrationBannerPart1
				<<	"	<strong><img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	RegBannerImage
				<<	"\" width=\"60\" height=\"37\" align=\"absmiddle\">"
				<<	"</strong>\n"
				<<	RegistrationBannerPart2
				<<	TitleText1
				<<	pUserId
				<<	TitleText2
//				<<  TitleText3
// kakiyama 07/08/99
				<<  clsIntlResource::GetFResString(-1,
								"<div ALIGN=\"CENTER\"><p>Click on a button below for more information</p>\n"
								"<table WIDTH=\"580\" BORDER=\"0\" CELLSPACING=\"20\" CELLPADDING=\"0\">\n"
								"<tr><td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"%{1:GetHTMLPath}help/help-newto.html\">"
								"<img SRC=\"%{2:GetPicsPath}new-to-ebay.gif\" WIDTH=\"90\" "
								"HEIGHT=\"28\" BORDER=\"0\"></a></td>\n"
								"<td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"%{3:GetHTMLPath}help/help-new-bidding.html\">"
								"<img SRC=\"%{4:GetPicsPath}new-to-bidding.gif\" WIDTH=\"90\" "
								"HEIGHT=\"28\" BORDER=\"0\"></a></td>\n"
								"<td WIDTH=\"33%\" ALIGN=\"CENTER\"><a HREF=\"%{5:GetHTMLPath}help/help-new-selling.html\">"
								"<img SRC=\"%{6:GetPicsPath}new-to-selling.gif\" WIDTH=\"90\" "
								"HEIGHT=\"28\" BORDER=\"0\"></a></td></tr></table></div>\n",
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
								clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
								clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
								clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
								clsIntlResource::ToString(mpMarketPlace->GetPicsPath()),
								NULL);

	//Add the footer
//	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
//	else
//		*mpStream <<	mpMarketPlace->GetSecureFooter();
	

	CleanUp();
	return;
}

