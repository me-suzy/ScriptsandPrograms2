/*	$Id: clseBayAppViewFeedback.cpp,v 1.17.2.12.34.4 1999/08/06 20:31:54 nsacco Exp $	*/
//
//	File:	clseBayAppViewFeedback.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewFeedback.cpp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods used to emit a
//		user's feedback profile.
//
// Modifications:
//				- 05/29/97 michael/yp	- Created
//				- 10/01/98 Vicki		- added REFFERRER
//				- 08/13/98 mila			- new feedback forum stuff; fixed table
//										  formatting for response/follow-up
//				- 08/14/98 mila			- color feedback items based on date
//										  relative to feature implementation date;
//										  pagination fixes; change page title;
//										  change mRebuttal to mResponse and
//										  mResponse to mFollowUp
//				- 08/15/98 mila			- make pagination work with regular and
//										  personalized feedback profiles; add
//										  links to pagination controls
//				- 08/22/98 mila			- changed Leave Feedback and View Personalized
//										  Feedback pages from static to dynamic
//				- 08/24/98 mila			- added missing endquote
//				- 09/22/98 mila			- change how bg color is set (was doing
//										  a strcpy for each feedback item...bad)
//				- 10/01/98 mila			- lighten blue bg; get rid of white gap
//										  between adjacent blue table cells
//				- 10/12/98 mila			- changed #define of NEW_FEEDBACK_FEATURE_DATE
//										  to static const int NewFeedbackFeatureDate;
//										  changed PrintNewFeedbackFeaturesMessage
//										  to construct implementation date string on 
//										  the fly
//				- 10/16/98 mila			- modified PrintFeedbackItem to attach a
//										  link to the item number only if the item is
//										  still in the database and it's not an adult
//										  item
//				- 10/30/98 mila			- added code to check for adult cookie
//				- 11/06/98 mila			- changed wording of "If you are..." statement
//				- 11/06/98 mila			- changed to abbreviated time zone names
//				- 11/06/98 mila			- modified PrintFeedbackItem to fix problem
//										  with missing background in empty
//										  <td>...</td> in Netscape -- fixes bug #675
//				- 11/10/98 mila			- changed feature implementation date to 11/23/98;
//										  deleted new feature message at bottom of feedback
//										  list; don't display user state as part of user ID
//										  widget at top of page; display summary info only
//										  on first page of feedback.
//				- 02/04/99 mila			- added line below title to draw attention to new 
//										  feedback changes
//				- 06/23/99 jennifer     - commented out member since
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "hash_map.h"
#include "clseBayTimeWidget.h"

//static const int NewFeedbackFeatureDate = 897634800;	// 6/12/98 00:00:00 -- test
//static const int NewFeedbackFeatureDate =  918720000;	// 2/11/99 00:00:00 -- prod
static const int NewFeedbackFeatureDate = 918806400;


// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *MsgForBlock1 =
"<html><head>"
"<title>eBay - Your Personal Trading Community</title>"
"</head>\n"
"<body bgcolor=\"#FFFFFF\">\n"
"<MAP NAME=\"navbarmap\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"351, 0, 407, 24\" HREF=\"http://pages.ebay.com/sitemap.html\" ALT=\"SITE MAP\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"285, 0, 350, 24\" HREF=\"http://pages.ebay.com/newschat.html\" ALT=\"NEWS/CHAT\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"248, 0, 284, 24\" HREF=\"http://pages.ebay.com/help/help-start.html\" ALT=\"HELP\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"199, 0, 247, 24\" HREF=\"http://pages.ebay.com/search.html\" ALT=\"SEARCH\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"146, 0, 198, 24\" HREF=\"http://pages.ebay.com/seller-services.html\" ALT=\"SELLERS\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"97, 0, 145, 24\" HREF=\"http://pages.ebay.com/ps.html\" ALT=\"BUYERS\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"41, 0, 96, 24\" HREF=\"http://listings.ebay.com/aw/listings/list\" ALT=\"LISTINGS\">\n"
"<AREA SHAPE=\"RECT\" COORDS=\"0, 0, 40, 24\" HREF=\"http://www.ebay.com\" ALT=\"HOME\">\n"
"</MAP>\n"
"<div align=\"center\"><div align=\"center\"><center>\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
"<tr><td width=\"170\" valign=\"top\" align=\"center\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
"<tr><td width=\"170\" height=\"90\" valign=\"middle\" align=\"center\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/logo_home_tb.gif\" width=\"170\" height=\"73\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"eBay logo\"> </td>\n"
"</tr>\n"
"<tr><td width=\"100%\" valign=\"middle\" align=\"center\">\n"
"<a href=\"http://listings.ebay.com/aw/listings/list/index.html#categories\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/h_category.gif\" width=\"169\" height=\"28\" hspace=\"0\" "
"vspace=\"0\" border=\"0\" alt=\"Categories\"></a><br>\n"
"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/antiques-index.html\">Antiques</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/books-index.html\">Books, Movies, Music</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/coins-index.html\">Coins &amp; Stamps</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/collectibles-index.html\">Collectibles</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/computer-index.html\">Computers</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/dolls-index.html\">Dolls, Figures</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/jewelry-index.html\">Jewelry, Gemstones</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/photo-index.html\">Photo &amp;Electronics</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/pottery-index.html\">Pottery &amp; Glass</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/sports-index.html\">Sports Memorabilia</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/toys-index.html\">Toys &amp; Beanie Babies</a></strong></font></td></tr>\n"
"<tr><td><font size=\"3\"><strong><a href=\"http://pages.ebay.com/misc-index.html\">Miscellaneous</a></strong></font></td></tr>\n"
"</table>\n"
"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n"
"<tr><td><font size=\"2\"><strong><em>&nbsp;<a href=\"http://listings.ebay.com/aw/listings/list/index.html#categories\">all "
"categories...</a></em></strong></font></td></tr></table>\n"
"</td></tr></table></td>\n"
"<td width=\"5\"></td><td width=\"425\" valign=\"top\" align=\"center\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>\n"
"<td width=\"425\" height=\"30\" valign=\"top\" align=\"right\">\n"
"<IMG SRC=\"http://pics.ebay.com/aw/pics/navbar.gif\" ALT=\"eBay Main Navbar\" WIDTH=\"407\" HEIGHT=\"24\" BORDER=\"0\" USEMAP=\"#navbarmap\">\n"
"</td></tr>\n"
"<tr><td width=\"100%\" valign=\"top\" align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"18\">\n"
"<tr><td width=\"290\" valign=\"top\" align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
"<tr valign=\"middle\"><td width=\"100%\" align=\"center\" height=\"13\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/yptc_mid.gif\" width=\"250\" height=\"13\"> </td></tr>\n"
"<tr><td width=\"100%\" valign=\"middle\" align=\"center\">\n"
"<form ACTION=\"http://search.ebay.com/cgi-bin/texis/ebay/results.html\" METHOD=\"GET\">\n"
"<input type=\"hidden\" name=\"maxRecordsReturned\" value=\"300\">\n"
"<input type=\"hidden\" name=\"maxRecordsPerPage\" value=\"50\">\n"
"<input type=\"hidden\" name=\"SortProperty\" value=\"MetaEndSort\">\n"
"<font size=\"2\"><p><input TYPE=\"TEXT\" NAME=\"query\" SIZE=\"20\" MAXLENGTH=\"100\" VALUE> \n"
"<input TYPE=\"SUBMIT\" VALUE=\"Search\"> \n"
"<a href=\"http://pages.ebay.com/tips-search.html\">tips</a></font> </p></form>\n"
"</td></tr><tr>\n "
"<td width=\"100%\" valign=\"top\" align=\"center\" height=\"132\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"290\" height=\"120\">\n"
"<tr><td valign=\"middle\" align=\"center\" width=\"145\" height=\"62\">\n"
"<a href=\"#FEEDBACK\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/viewsellerfeedback.gif\" width=\"128\" height=\"62\" "
"alt=\"viewsellerfeedback.gif (1357 bytes)\" border=\"0\"></a></td>\n"
"<td valign=\"middle\" align=\"center\" width=\"145\" height=\"62\">\n"
"<a href=\"http://pages.ebay.com/newschat.html\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/news_chat.gif\" width=\"128\" height=\"62\" "
"hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Get News &amp; Chat\"></a></td></tr>\n"
"<tr><td valign=\"middle\" align=\"center\" width=\"145\" height=\"58\">\n"
"<a href=\"http://pages.ebay.com/help/basics/n-index.html\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/ww-homepage.gif\" width=\"117\" "
"height=\"59\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Welcome Wagon\"></a></td>\n"
"<td valign=\"middle\" align=\"center\" width=\"145\" height=\"58\">\n"
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/p_register_tb.gif\" width=\"128\" height=\"58\" hspace=\"0\" "
"vspace=\"0\" border=\"0\" alt=\"Register. It's free and fun\"></a></td></tr></table></td></tr>\n"
"<tr><td width=\"100%\" valign=\"top\" align=\"center\">\n"
"<a href=\"http://www.ebay.com\"><img src=\"http://pics.ebay.com/aw/pics/welcome2.gif\" "
"width=\"290\" height=\"25\" border=\"0\" alt=\"welcome2.gif (688 bytes)\"></a>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\"><font size=\"2\"><strong>Welcome ";
*/

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *MsgForBlock2 =
"<font FACE=\"Symbol\">Ò</font>\n"
"traveler!</strong> We&#146;re glad you stopped by, and we hope you enjoy your visit "
"to eBay, the world&#146;s personal trading community<font FACE=\"Symbol\">Ô</font>. Stop and "
"take a look around our community, meet some of our members, and make sure to leave room in "
"your suitcase for those wonderful items that you&#146;ll want to take "
"home. After just one visit you'll see why our members think eBay is the greatest "
"place on earth and why so many people come to eBay for fun, pleasure, and "
"great winnings.</font></td></tr></table>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\"><em><strong>We&#146;ve got something for everyone. "
"</strong></em></td></tr></table>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\"><em><strong>You want it. Somebody&#146;s got it"
"<font FACE=\"Symbol\">Ô</font>.</strong></em></td></tr></table>\n"
"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\"> "
"<tr><td align=\"right\"></td>\n"
"</tr></table></td></tr></table></td>\n"
"<td width=\"5\"></td>\n"
"<td width=\"130\" valign=\"top\" align=\"center\">\n"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\" valign=\"top\" align=\"left\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/h_stats.gif\" width=\"130\" height=\"25\" "
"hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Stats\"></td></tr>\n"
"<tr><td width=\"100%\"><small>Over <b>2,000,000</b> items for sale in <b>1627</b> categories "
"daily!</small></td></tr></table>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\"><small>Over <b>1.5</b> billion page views per month!"
"</small></td></tr></table>\n"
"<table border=\"0\" width=\"100%\"><tr><td width=\"100%\"> "
"<small>Over 90 million bids made since inception!</small></td></tr>\n"
"<tr><!--data cell for the bottom right promotion-->\n"
"<td width=\"100%\" valign=\"top\">\n"
"<img src=\"http://pics.ebay.com/aw/pics/widget-support.gif\" "
"width=\"130\" height=\"25\" alt=\"widget-support.gif (549 bytes)\">\n"
"<table border=\"0\" width=\"100%\"> "
"<tr><td width=\"100%\"><small>Our customer support staff is available 24 hours "
"a day, 7 days a week, 365 days a year to help you out with anything you " 
"need.</small></td></tr></table>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\">"
"<a href=\"";
*/



static const char *MsgForBlock3 =
"eBayISAPI.dll?ViewBoard&amp;name=aolsupport\">"
"Customer support for new users.</a></td></tr></table>\n"
"<table border=\"0\" width=\"100%\">\n"
"<tr><td width=\"100%\">"
"<a href=\"";

static const char *MsgForBlock4 = 
"eBayISAPI.dll?ViewBoard&amp;name=support\">"
"Live customer support on our message boards</a> </td></tr></table>\n"
"<p>&nbsp;</td></tr></table>\n"
"</td></tr></table>\n"
"</td></tr></table></td></tr></table></center></div></div><hr>\n"
"<a name=\"FEEDBACK\"></a>";

static const char *BgColorLightBlue	= "#cff0ff";
static const char *BgColorLightGray	= "#efefef";


void clseBayApp::PrintNewFeedbackFeaturesMessage(ostream *mpStream)
{
	time_t implTime = NewFeedbackFeatureDate;
	struct tm *pImplTimeTm = localtime(&implTime);

	*mpStream	<<	"<i>On "
				<<	pImplTimeTm->tm_mon + 1
				<<	"/"
				<<	pImplTimeTm->tm_mday
				<<	"/"
				<<	pImplTimeTm->tm_year
				<<	" eBay introduced two new feedback features. "
					"Users can now designate transaction-related "
					"feedback and can provide response and follow-up "
					"comments. The feedback entries displayed with "
					"the blue bar indicate this change.</i>";
}


static void	ShowBlockingMessage(clsMarketPlace * pMarketPlace, ostream *pStream, const char* pTitle)
{
//	*pStream	<< MsgForBlock1

// kakiyama 08/02/99

	// TODO replace eBay with pMarketPlace->GetCurrentPartnerName()
	*pStream    << clsIntlResource::GetFResString(-1,
						"<html><head>"
						"<title>eBay - Your Personal Trading Community</title>"
						"</head>\n"
						"<body bgcolor=\"#FFFFFF\">\n"
						"<MAP NAME=\"navbarmap\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"351, 0, 407, 24\" HREF=\"%{1:GetHTMLPath}sitemap.html\" ALT=\"SITE MAP\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"285, 0, 350, 24\" HREF=\"%{2:GetHTMLPath}newschat.html\" ALT=\"NEWS/CHAT\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"248, 0, 284, 24\" HREF=\"%{3:GetHTMLPath}help/help-start.html\" ALT=\"HELP\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"199, 0, 247, 24\" HREF=\"%{4:GetHTMLPath}search.html\" ALT=\"SEARCH\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"146, 0, 198, 24\" HREF=\"%{5:GetHTMLPath}seller-services.html\" ALT=\"SELLERS\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"97, 0, 145, 24\" HREF=\"%{6:GetHTMLPath}ps.html\" ALT=\"BUYERS\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"41, 0, 96, 24\" HREF=\"%{7:GetListingPath}list\" ALT=\"LISTINGS\">\n"
						"<AREA SHAPE=\"RECT\" COORDS=\"0, 0, 40, 24\" HREF=\"%{8:GetHTMLPath}\" ALT=\"HOME\">\n"
						"</MAP>\n"
						"<div align=\"center\"><div align=\"center\"><center>\n"
						"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"600\">\n"
						"<tr><td width=\"170\" valign=\"top\" align=\"center\">\n"
						"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n"
						"<tr><td width=\"170\" height=\"90\" valign=\"middle\" align=\"center\">\n"
						"<img src=\"%{9:GetPicsPath}logo_home_tb.gif\" width=\"170\" height=\"73\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"eBay logo\"> </td>\n"
						"</tr>\n"
						"<tr><td width=\"100%\" valign=\"middle\" align=\"center\">\n"
						"<a href=\"%{10:GetListingPath}list/index.html#categories\">\n"
						"<img src=\"%{11:GetPicsPath}h_category.gif\" width=\"169\" height=\"28\" hspace=\"0\" "
						"vspace=\"0\" border=\"0\" alt=\"Categories\"></a><br>\n"
						"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{12:GetHTMLPath}antiques-index.html\">Antiques</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{13:GetHTMLPath}books-index.html\">Books, Movies, Music</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{14:GetHTMLPath}coins-index.html\">Coins &amp; Stamps</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{15:GetHTMLPath}collectibles-index.html\">Collectibles</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{16:GetHTMLPath}computer-index.html\">Computers</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{17:GetHTMLPath}dolls-index.html\">Dolls, Figures</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{18:GetHTMLPath}jewelry-index.html\">Jewelry, Gemstones</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{19:GetHTMLPath}photo-index.html\">Photo &amp;Electronics</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{20:GetHTMLPath}pottery-index.html\">Pottery &amp; Glass</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{21:GetHTMLPath}sports-index.html\">Sports Memorabilia</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{22:GetHTMLPath}toys-index.html\">Toys &amp; Beanie Babies</a></strong></font></td></tr>\n"
						"<tr><td><font size=\"3\"><strong><a href=\"%{23:GetHTMLPath}misc-index.html\">Miscellaneous</a></strong></font></td></tr>\n"
						"</table>\n"
						"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n"
						"<tr><td><font size=\"2\"><strong><em>&nbsp;<a href=\"%{24:GetListingPath}list/index.html#categories\">all "
						"categories...</a></em></strong></font></td></tr></table>\n"
						"</td></tr></table></td>\n"
						"<td width=\"5\"></td><td width=\"425\" valign=\"top\" align=\"center\">\n"
						"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tr>\n"
						"<td width=\"425\" height=\"30\" valign=\"top\" align=\"right\">\n"
						"<IMG SRC=\"%{25:GetPicsPath}navbar.gif\" ALT=\"eBay Main Navbar\" WIDTH=\"407\" HEIGHT=\"24\" BORDER=\"0\" USEMAP=\"#navbarmap\">\n"
						"</td></tr>\n"
						"<tr><td width=\"100%\" valign=\"top\" align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"18\">\n"
						"<tr><td width=\"290\" valign=\"top\" align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
						"<tr valign=\"middle\"><td width=\"100%\" align=\"center\" height=\"13\">\n"
						"<img src=\"%{26:GetPicsPath}yptc_mid.gif\" width=\"250\" height=\"13\"> </td></tr>\n"
						"<tr><td width=\"100%\" valign=\"middle\" align=\"center\">\n"
						"<form ACTION=\"%{27:GetSearchPath}texis/ebay/results.html\" METHOD=\"GET\">\n"
						"<input type=\"hidden\" name=\"maxRecordsReturned\" value=\"300\">\n"
						"<input type=\"hidden\" name=\"maxRecordsPerPage\" value=\"50\">\n"
						"<input type=\"hidden\" name=\"SortProperty\" value=\"MetaEndSort\">\n"
						"<font size=\"2\"><p><input TYPE=\"TEXT\" NAME=\"query\" SIZE=\"20\" MAXLENGTH=\"100\" VALUE> \n"
						"<input TYPE=\"SUBMIT\" VALUE=\"Search\"> \n"
						"<a href=\"%{28:GetHTMLPath}tips-search.html\">tips</a></font> </p></form>\n"
						"</td></tr><tr>\n "
						"<td width=\"100%\" valign=\"top\" align=\"center\" height=\"132\">\n"
						"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"290\" height=\"120\">\n"
						"<tr><td valign=\"middle\" align=\"center\" width=\"145\" height=\"62\">\n"
						"<a href=\"#FEEDBACK\">\n"
						"<img src=\"%{29:GetPicsPath}viewsellerfeedback.gif\" width=\"128\" height=\"62\" "
						"alt=\"viewsellerfeedback.gif (1357 bytes)\" border=\"0\"></a></td>\n"
						"<td valign=\"middle\" align=\"center\" width=\"145\" height=\"62\">\n"
						"<a href=\"%{30:GetHTMLPath}newschat.html\">\n"
						"<img src=\"%{31:GetPicsPath}news_chat.gif\" width=\"128\" height=\"62\" "
						"hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Get News &amp; Chat\"></a></td></tr>\n"
						"<tr><td valign=\"middle\" align=\"center\" width=\"145\" height=\"58\">\n"
						"<a href=\"%{32:GetHTMLPath}help/basics/n-index.html\">\n"
						"<img src=\"%{33:GetPicsPath}ww-homepage.gif\" width=\"117\" "
						"height=\"59\" hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Welcome Wagon\"></a></td>\n"
						"<td valign=\"middle\" align=\"center\" width=\"145\" height=\"58\">\n"
						"<a href=\"%{34:GetHTMLPath}services/registration/register-by-country.html\">\n"
						"<img src=\"%{35:GetPicsPath}p_register_tb.gif\" width=\"128\" height=\"58\" hspace=\"0\" "
						"vspace=\"0\" border=\"0\" alt=\"Register. It's free and fun\"></a></td></tr></table></td></tr>\n"
						"<tr><td width=\"100%\" valign=\"top\" align=\"center\">\n"
						"<a href=\"%{36:GetHTMLPath}\"><img src=\"%{37:GetPicsPath}welcome2.gif\" "
						"width=\"290\" height=\"25\" border=\"0\" alt=\"welcome2.gif (688 bytes)\"></a>\n"
						"<table border=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\"><font size=\"2\"><strong>Welcome ",
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 1
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 2
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 3
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 4	
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 5
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 6
												clsIntlResource::ToString(pMarketPlace->GetListingPath()),			// 7
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 8
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 9
												clsIntlResource::ToString(pMarketPlace->GetListingPath()),			// 10
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 11
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 12
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 13		
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 14
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 15							
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 16
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 17
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 18
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 19
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 20
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 21
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 22	
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 23	 
												clsIntlResource::ToString(pMarketPlace->GetListingPath()),			// 24	
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 25	
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 26
												clsIntlResource::ToString(pMarketPlace->GetSearchPath()),			// 27
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 28
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 29
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 30	
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 31
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 32
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 33
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 34
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 35
												clsIntlResource::ToString(pMarketPlace->GetHTMLPath()),			// 36
												clsIntlResource::ToString(pMarketPlace->GetPicsPath()),			// 37
												NULL)

				<< pTitle
//				<< MsgForBlock2
				<< clsIntlResource::GetFResString(-1,
						"<font FACE=\"Symbol\">Ò</font>\n"
						"traveler!</strong> We&#146;re glad you stopped by, and we hope you enjoy your visit "
						"to eBay, the world&#146;s personal trading community<font FACE=\"Symbol\">Ô</font>. Stop and "
						"take a look around our community, meet some of our members, and make sure to leave room in "
						"your suitcase for those wonderful items that you&#146;ll want to take "
						"home. After just one visit you'll see why our members think eBay is the greatest "
						"place on earth and why so many people come to eBay for fun, pleasure, and "
						"great winnings.</font></td></tr></table>\n"
						"<table border=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\"><em><strong>We&#146;ve got something for everyone. "
						"</strong></em></td></tr></table>\n"
						"<table border=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\"><em><strong>You want it. Somebody&#146;s got it"
						"<font FACE=\"Symbol\">Ô</font>.</strong></em></td></tr></table>\n"
						"<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\"> "
						"<tr><td align=\"right\"></td>\n"
						"</tr></table></td></tr></table></td>\n"
						"<td width=\"5\"></td>\n"
						"<td width=\"130\" valign=\"top\" align=\"center\">\n"
						"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\" valign=\"top\" align=\"left\">\n"
						"<img src=\"%{1:GetPicsPath}h_stats.gif\" width=\"130\" height=\"25\" "
						"hspace=\"0\" vspace=\"0\" border=\"0\" alt=\"Stats\"></td></tr>\n"
						"<tr><td width=\"100%\"><small>Over <b>2,000,000</b> items for sale in <b>1627</b> categories "
						"daily!</small></td></tr></table>\n"
						"<table border=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\"><small>Over <b>1.5</b> billion page views per month!"
						"</small></td></tr></table>\n"
						"<table border=\"0\" width=\"100%\"><tr><td width=\"100%\"> "
						"<small>Over 90 million bids made since inception!</small></td></tr>\n"
						"<tr><!--data cell for the bottom right promotion-->\n"
						"<td width=\"100%\" valign=\"top\">\n"
						"<img src=\"%{2:GetPicsPath}widget-support.gif\" "
						"width=\"130\" height=\"25\" alt=\"widget-support.gif (549 bytes)\">\n"
						"<table border=\"0\" width=\"100%\"> "
						"<tr><td width=\"100%\"><small>Our customer support staff is available 24 hours "
						"a day, 7 days a week, 365 days a year to help you out with anything you " 
						"need.</small></td></tr></table>\n"
						"<table border=\"0\" width=\"100%\">\n"
						"<tr><td width=\"100%\">"
						"<a href=\"",
										clsIntlResource::ToString(pMarketPlace->GetPicsPath()),
										clsIntlResource::ToString(pMarketPlace->GetPicsPath()),
										NULL)

				

				<< pMarketPlace->GetCGIPath(PageViewBoard)
				<< MsgForBlock3
				<< pMarketPlace->GetCGIPath(PageViewBoard)
				<< MsgForBlock4;
}


void clseBayApp::PrintFeedbackItem(ostream *mpStream,
								   clsFeedbackItem *pItem)

{
	// Interesting formatting things
// petra	time_t					theTimeT;
// petra	struct tm				*pTheTime;
// petra	char					cTheTime[40];
	char					*pSafeText;
// petra	clseBayTimeWidget		theTimeWidget;
// petra	TimeZoneEnum			timeZone;

	clsItem					item;
//	bool					bIsAdultItem;
//	bool					bHasAdultCookie;

	clsUserIdWidget*		pUserIdWidget;

	const int				userDivWidth = pItem->mItem > 0 ? 82 : 100;

// petra	theTimeT	= pItem->mTime;
// petra	pTheTime	= localtime(&theTimeT); //yp

	//samuel au, 4/6/99
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	theTimeWidget.SetTime(theTimeT);
// petra	theTimeWidget.SetTimeZone(timeZone);

// petra	if (pTheTime->tm_isdst)
// petra	{
// petra		strftime(cTheTime, sizeof(cTheTime),
// petra				 "%m/%d/%y %H:%M:%S PDT ",
// petra				 pTheTime);
// petra	}
// petra	else
// petra	{
// petra		strftime(cTheTime, sizeof(cTheTime),
// petra				 "%m/%d/%y %H:%M:%S PST ",
// petra				 pTheTime);
// petra	}

	// Start table for feedback items.
	if (pItem->mTime > NewFeedbackFeatureDate)	// light blue background
	{
		*mpStream	<<	"<table WIDTH=\"100%\" BORDER=\"1\">"
					<<	"\n"
					<<	"  <tr>"
					<<	"\n"
					<<	"    <td>"
					<<	"\n"
					<<	"      <table WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"0\">"
					<<	"\n"
					<<	"        <tr>"
					<<	"\n"
					<<	"          <td WIDTH=\""
					<<	userDivWidth
					<<	"%\" ALIGN=\"left\" BGCOLOR=\""
					<<	BgColorLightBlue
					<<	"\">"
					<<	"\n"
					<<	"            <div ALIGN=\"left\"><b>User:</b> ";
	}
	else	// light gray background
	{
		*mpStream	<<	"<table WIDTH=\"100%\" BORDER=\"1\">"
					<<	"\n"
					<<	"  <tr>"
					<<	"\n"
					<<	"    <td>"
					<<	"\n"
					<<	"      <table WIDTH=\"100%\" BORDER=\"0\" CELLSPACING=\"0\">"
					<<	"\n"
					<<	"        <tr>"
					<<	"\n"
					<<	"          <td WIDTH=\""
					<<	userDivWidth
					<<	"%\" ALIGN=\"left\" BGCOLOR=\""
					<<	BgColorLightGray
					<<	"\">"
					<<	"\n"
					<<	"            <div ALIGN=\"left\"><b>User:</b> ";
	}

	// Output user info.
	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);
	pUserIdWidget->SetUserInfo(pItem->mCommentingUserId, 
								pItem->mCommentingEmail,
								UserStateEnum(pItem->mCommentingUserState),
								mpMarketPlace->UserIdRecentlyChanged(pItem->mCommentingUserIdLastModified),
								pItem->mCommentingUserScore,
								pItem->mCommentingUserFlags);
	pUserIdWidget->SetShowAboutMe();
	pUserIdWidget->EmitHTML(mpStream);
	delete pUserIdWidget;

	// Output date & time.

	*mpStream	<<	" <b>Date:</b> ";

	clseBayTimeWidget theTimeWidget (mpMarketPlace,					// petra
									 EBAY_TIMEWIDGET_MEDIUM_DATE,	// petra
									 EBAY_TIMEWIDGET_LONG_TIME,		// petra
									 pItem->mTime);					// petra
	//samuel au, 4/6/99
	theTimeWidget.EmitHTML(mpStream);
	
	*mpStream	<<	"            </div>"
				<<	"\n"
				<<	"          </td>"
				<<	"\n";

	// Output item number if transaction-based.
	if (pItem->mItem > 0)
	{
		if (pItem->mTime > NewFeedbackFeatureDate)	// light blue background
		{
			*mpStream	<<	"          <td BGCOLOR=\""
						<<	BgColorLightBlue
						<<	"\" ALIGN=\"left\" WIDTH=\"18%\">";
		}
		else
		{
			*mpStream	<<	"          <td BGCOLOR=\""
						<<	BgColorLightGray
						<<	"\" ALIGN=\"left\" WIDTH=\"18%\">";
		}

// Lena - wiring off GetItem 
/*
		if (gApp->GetDatabase()->GetItem(pItem->mItem, &item, NULL, 0))
		{
			bHasAdultCookie = HasAdultCookie();
			bIsAdultItem = item.IsAdult();
			if (!bIsAdultItem || (bIsAdultItem && bHasAdultCookie))
			{
				// If the item isn't an adult item, or if it is an adult item
				// and the user has an adult cookie, then go ahead an provide
				// a link to the view item page.
				*mpStream	<<	"<b>Item:</b> "
							<<	"<A HREF="
							<<	"\""
							<<	mpMarketPlace->GetCGIPath(PageViewItem)
							<<	"eBayISAPI.dll?ViewItem&amp;item="
							<<	pItem->mItem
							<<	"\">"
							<<	pItem->mItem
							<<	"</A>"
							<<	"\n";
			}
			else if (bIsAdultItem && !bHasAdultCookie)
			{
				// Don't provide a link to the view item page if the
				// item is an adult item but the user doesn't have an
				// adult cookie.
				*mpStream	<<	"<b>Item:</b> "
							<<	pItem->mItem
							<<	"\n";
			}
		}
		else
		{
Lena - end wiring off
*/
			// Don't provide a link to the view item page if the item
			// is no longer in the database.
// Lena - just display the link and if they item is not there anymore they'll get a message
				*mpStream	<<	"<b>Item:</b> "
							<<	"<A HREF="
							<<	"\""
							<<	mpMarketPlace->GetCGIPath(PageViewItem)
							<<	"eBayISAPI.dll?ViewItem&amp;item="
							<<	pItem->mItem
							<<	"\">"
							<<	pItem->mItem
							<<	"</A>"
							<<	"\n";
//			*mpStream	<<	"<b>Item:</b> "
//						<<	pItem->mItem
//						<<	"\n";
//		}

		// End of item number.
		*mpStream	<<	"          </td>"
					<<	"\n";
	}

	*mpStream	<<	"        </tr>"
				<<	"\n"
				<<	"      </table>"
				<<	"\n";

	// Start new table for feedback comment.
	*mpStream	<<	"      <table WIDTH=\"100%\" BGCOLOR=\"#ffffff\">"
				<<	"\n"
				<<	"        <tr>"
				<<	"\n"
				<<	"          <td>"
				<<	"\n";

	*mpStream	<<	"            <p><strong>";

	// Output Complaint, Praise, or Neutral, with color coding.
	switch (pItem->mType)
	{

		case FEEDBACK_NEGATIVE:
			*mpStream << "<font color=red>Complaint</font>:"
							 "</strong>"
							 " ";
			break;
		case FEEDBACK_POSITIVE:
			*mpStream << "<font color=green>Praise</font>:"
							 "</strong>"
							 "    ";
			break;
		case FEEDBACK_NEUTRAL:
		case FEEDBACK_NEGATIVE_SUSPENDED:
		case FEEDBACK_POSITIVE_SUSPENDED:
			*mpStream << "Neutral:"
							 "</strong>"
							 "   ";
			break;
		default:
			*mpStream << ":"
							 "</strong>"
							 "          ";
			break;
	}
		
	// Pass the text through a filter to make it "safe".
	pSafeText	= clsUtilities::StripHTML(pItem->mText);

	*mpStream <<	pSafeText
			  <<	"</p>"
			  <<	"\n"
			  <<	"          </td>"
			  <<	"\n"
			  <<	"        </tr>"
			  <<	"\n"
			  <<	flush;

	delete pSafeText;

	// Output response and follow-up comments, if any.
	if (strlen(pItem->mResponse) > 0)
	{
		// Start new row for response.
		*mpStream <<	"        <tr>"
				  <<	"\n"
				  <<	"          <td>"
				  <<	"\n";

		*mpStream <<	"            <i>Response</i>: ";

		pSafeText = clsUtilities::StripHTML(pItem->mResponse);
		*mpStream <<	pSafeText
				  <<	"\n"
				  <<	"          </td>"
				  <<	"\n"
				  <<	"        </tr>"
				  <<	"\n"
				  <<	flush;
		delete pSafeText;

		// Follow-up comment cannot exist without an existing response
		// to same feedback.
		if (strlen(pItem->mFollowUp) > 0)
		{
			// Start new row for response.
			*mpStream <<	"        <tr>"
					  <<	"\n"
					  <<	"          <td>"
					  <<	"\n";

			*mpStream	<<	"            <i>Follow-up</i>: ";

			pSafeText = clsUtilities::StripHTML(pItem->mFollowUp);
			*mpStream	<<	pSafeText
						<<	"\n"
						<<	"          </td>"
						<<	"\n"
						<<	"        </tr>"
						<<	"\n"
						<<	flush;
			delete pSafeText;
		}
	}
	
	// End of tables.
	*mpStream	<<	"      </table>"
				<<	"\n"
				<<	"    </td>"
				<<	"\n"
				<<	"  </tr>"
				<<	"\n"
				<<	"</table>"
				<<	"\n";
		
	return;
}



//
// GetAndShowFeedback
//
//	This routine actually retrieves and emits
//	the feedback a user has received. It's a seperate
//	method so that it can be called independantly
//	of ViewFeedback. The latter emits a <TITLE>
//	and other goodies.
//
void clseBayApp::GetAndShowFeedback(clsUser *pUser, 
									int startingPage,
									int itemsPerPage,
									bool honorHidden)
{

	clsFeedback*		pFeedback = NULL;
	clsUserIdWidget*	pUserIdWidget = NULL;
	bool				hasSomeFeedback = true;

	// create an empty one
	clsFeedbackExtendedScore emptyFeedbackExtendedScore;

	// Feedback about the user
	FeedbackItemVector				*pvItems;
	FeedbackItemVector::iterator	i;
	int								score;

	clsFeedbackExtendedScore		*pScore;

	bool				newFeaturesMessagePrinted = false;
	
	// Pagination variables.
	int					itemStart;
	int					itemCount;

	int					endingItem;
	int					totalItems;

	int					index;

	if (!pUser)
		return;

	// Get User Feedback
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback	= pUser->GetFeedback();

	pUserIdWidget = new clsUserIdWidget(mpMarketPlace, this);

	/*
	// display header
	*mpStream <<	"\n"
					"<H2>"
					"Feedback Profile for ";

	pUserIdWidget->SetUserInfo(pUser->GetUserId(), 
								pUser->GetEmail(),
								pUser->GetUserState(),
								mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
								pFeedback->GetScore(),
								pUser->GetUserFlags());
	pUserIdWidget->SetShowFeedback(true);
	pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetShowAboutMe();
	pUserIdWidget->EmitHTML(mpStream);

	*mpStream <<	"</H2>"
					"\n";
	*/

	// Let's see if there's anything to do here
	if (pFeedback == NULL || !pFeedback->UserHasFeedback())
		hasSomeFeedback = false;

	
	if (hasSomeFeedback)
	{
		itemStart	= (startingPage - 1) * itemsPerPage + 1;
		itemCount	= itemsPerPage;

		// Let's get the vector of feedback items
		if (startingPage == 1)
		{
			if (pFeedback->IsValidExtendedScore()					&& 
				clsUtilities::IsToday(pFeedback->GetExtDateCalc())		)
			{
				pvItems		= pFeedback->GetItems(itemStart, itemCount, &totalItems);
			}
			else
			{
				pvItems		= pFeedback->GetItems(itemStart, 0, &totalItems);
			}
		}
		else
		{
			pvItems		= pFeedback->GetItems(itemStart, itemCount, &totalItems);
		}

		if (pFeedback->GetFlag() & FEEDBACK_FLAG_HIDE)
		{
			itemStart	= 1;
			itemCount	= 0;
		}
		else
		{
			itemStart	= (startingPage - 1) * itemsPerPage + 1;
			itemCount	= itemsPerPage;
		}

		// Let's see if they have nuthin!
		if (pvItems->size() == 0)
			hasSomeFeedback = false;

	}

	// Only output the summary info on the first page.
	if (startingPage == 1)
	{
		// Let's get the user's current feedback score and
		// show it.
		if (hasSomeFeedback)
			score	= pFeedback->GetScore();
		else
			score	= 0;

		if (hasSomeFeedback)
			pScore	= pFeedback->GetExtendedScore(7*24*60*60,
													  30*24*60*60,
													  180*24*60*60);
		else
			pScore	= &emptyFeedbackExtendedScore;		// everything set to 0

		// begin 2-column table
		*mpStream	<<	"<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"590\">"
					<<	"<tr>"

					// Column for Overall profile makeup
					<<	"<td valign=\"top\" width=\"40%\"><strong>Overall profile makeup</strong><table border=\"0\""
					<<	"cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
					<<	"<tr>"
					<<	"<td><br>"
					<<	"</td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td width=\"5%\"></td>"
					<<	"<td width=\"95%\"><font size=\"3\"><b>"
					<<	pScore->mPositiveComments
					<<	"</b> <font color=\"green\">positives</font>. <b>"
					<<	pScore->mPositiveCommentsThatCount
					<<	"</b>"
					<<	" are from unique users and count toward the final rating. </font><p><font size=\"3\"><b>"
					<<	pScore->mNeutralComments
					<<	"</b>"
					<<	" neutrals. <b>"
					<<	pScore->mNeutralCommentsFromSuspendedUsers
					<<	"</b> are from users <a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/myinfo/user-not-registered.html\">no longer registered</a>.</font></p>"
					<<	"<p><font size=\"3\"><b>"
					<<	pScore->mNegativeComments
					<<	"</b> <font color=\"red\">negatives</font>. <b>"
					<<	pScore->mNegativeCommentsThatCount
					<<	"</b> are from unique "
					<<	"users and count toward the final rating.</font></td>"
					<<	"</tr>"
					<<	"</table>"
					<<	"</td>"

					// Column for Summary of Most Recent comments
					<<	"<td valign=\"top\" width=\"60%\">"

					// Ebay ID Card
					//  Wrap it all around a single-celled table (to give it a 3d effect)
					<<	"<TABLE border=\"6\" bordercolor=\"#EFEFEF\" borderColorDark=\"#999999\" borderColorLight=\"#EFEFEF\" cellPadding=\"3\" cellSpacing=\"0\" width=\"100%\">\n"
						"<tr>\n"
						"<td>\n"
					
						"<table border=\"0\" cellpadding=\"0\" width=\"100%\" cellspacing=\"3\">\n"
						"<tr>\n"


						// ebay logo and "ID Card"
						"<td width=\"50%\" valign=\"top\">\n"
						"<img alt=\"eBay\" border=\"0\" height=\"24\" hspace=\"0\" width=\"55\" "
						"src=\""
					<<	mpMarketPlace->GetPicsPath()
					<<	"navbar/ebay_logo_home.gif\">"
						" &nbsp;\n"
						"<font face=\"Arial,Helvetica\" size=\"5\" color=\"#FF0000\">"
						"<b>ID card</b>"
						"</font>"
						"</td>\n";
						
						// userid and feedback score
		*mpStream	<<	"<td width=\"50%\" align=\"center\">\n";

		pUserIdWidget->SetUserInfo(pUser->GetUserId(), 
									pUser->GetEmail(),
									pUser->GetUserState(),
									mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
									pFeedback->GetScore(),
									pUser->GetUserFlags());
		pUserIdWidget->SetShowFeedback(true);
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowAboutMe(false);
		pUserIdWidget->SetShowStar(false);
		pUserIdWidget->SetShowMask(false);
		pUserIdWidget->SetUserIdBold(true);
		pUserIdWidget->EmitHTML(mpStream);

		*mpStream	<<	"</td>\n"
						"</tr>\n"

						"<tr>"
					<<  "<td width=\"50%\">&nbsp;</td>\n" //placeholder for member since

/* don't show member since for new UI phase I.

						// member since
					<<	"<td width=\"50%\">\n"
						"<font face=\"Arial,Helvetica\" size=\"1\">"
						"member since ";

		char pMemberSince[25];

		time_t theTime = pUser->GetCreated();
		struct tm* pTimeTm = localtime(&theTime);
		if (pTimeTm)
			strftime(pMemberSince, sizeof(pMemberSince), "%b %d, %Y", pTimeTm);

		*mpStream	<<	pMemberSince	
					<<	"</font>\n"
						"</td>\n"

end of the member since */

						// all of users' icons
						"<td valign=\"middle\" width=\"50%\" align=\"center\">";

		pUserIdWidget->SetUserInfo(pUser->GetUserId(), 
									pUser->GetEmail(),
									pUser->GetUserState(),
									mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
									pFeedback->GetScore(),
									pUser->GetUserFlags());
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowAboutMe(true);
		pUserIdWidget->SetShowStar(true);
		pUserIdWidget->SetShowMask(true);
		pUserIdWidget->SetShowUserId(false);		// don't show userid
		pUserIdWidget->SetShowFeedback(false);		// don't show feedback score
		pUserIdWidget->SetUserIdBold(false);
		pUserIdWidget->EmitHTML(mpStream);

		*mpStream	<<	"</td>\n"
						"</tr>\n"

						"<tr>"

						// Summary of recent comments
						"<td colspan=\"2\">\n"


					<<	"<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\""
					<<	"width=\"100%\">"
					<<	"<tr>"
					<<	"<td width=\"100%\"><font face=\"arial,helvetica\" size=\"2\"><strong>Summary "
					<<	"of Most Recent Comments</strong></font></td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td width=\"100%\">"
					<<	"<table border=\"0\" cellpadding=\"3\" width=\"100%\""
					<<	"cellspacing=\"0\">"
					<<	"<tr>"
					<<	"<td></td>"
					<<	"<td><font size=\"2\">Past 7 days</font></td>"
					<<	"<td><font size=\"2\">Past month</font></td>"
					<<	"<td><font size=\"2\">Past 6 mo.</font></td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td><font size=\"2\">Positive</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#008000\" size=\"3\">"
					<<	pScore->mPositiveCommentsInInterval1
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#008000\" size=\"3\">"
					<<	pScore->mPositiveCommentsInInterval2
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#008000\" size=\"3\">"
					<<	pScore->mPositiveCommentsInInterval3
					<<	"</font></td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td><font size=\"2\">Neutral</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\">"
					<<	pScore->mNeutralCommentsInInterval1
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\">"
					<<	pScore->mNeutralCommentsInInterval2
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\">"
					<<	pScore->mNeutralCommentsInInterval3
					<<	"</font></td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td><font size=\"2\">Negative</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#FF0000\" size=\"3\">"
					<<	pScore->mNegativeCommentsInInterval1
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#FF0000\" size=\"3\">"
					<<	pScore->mNegativeCommentsInInterval2
					<<	"</font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font color=\"#FF0000\" size=\"3\">"
					<<	pScore->mNegativeCommentsInInterval3
					<<	"</font></td>"
					<<	"</tr>"
					<<	"<tr>"
					<<	"<td><font size=\"2\"><strong>Total</strong></font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\"><strong>"
					<<	pScore->mCommentsInInterval1
					<<	"</strong></font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\"><strong>"
					<<	pScore->mCommentsInInterval2
					<<	"</strong></font></td>"
					<<	"<td valign=\"middle\" align=\"center\"><font size=\"3\"><strong>"
					<<	pScore->mCommentsInInterval3
					<<	"</strong></font></td>"
					<<	"</tr>"
					<<	"</table>"
					<<	"</td>"
					<<	"</tr>"
					<<	"</table>";

		*mpStream	<<	"</td>\n"
						"</tr>\n"	
						"</table>\n"	
						
						"</td>\n"
						"</tr>\n"	
						"</table>\n";	// end bordered table

		//adding all actions link:
		*mpStream	<<	"<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 COLS=1 WIDTH=\"100%\" >"
						"<TR>"
						"<TD ALIGN=right>"
					<<	"<A HREF=\""
					<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
					<<	"eBayISAPI.dll"
						"?ViewListedItems"
						"&userid="
					<<	mpUser->GetUserId()
					<<	"&completed=0&sort=0&since=-1"
						"\">Auctions</A>"
						" by "
						"<font color=\"darkgreen\">"
					<<	pUser->GetUserId()
					<< "</font></TD>"
						"</TR>"
						"</TABLE>"
					<<	"</td>"
					<<	"</tr>";

		// explain score discrepancy if there is one

#if 0 // JUST FOR NOW, until we clean up this page and fix bugs with computing the score.
		if ((pScore->mPositiveCommentsThatCount - pScore->mNegativeCommentsThatCount) != score)
		{
			*mpStream	<<	"<tr>"
						<<	"<td colspan=\"2\">"
						<<	"<font size=\"2\"><b>Note:</b> The reason that this user's feedback rating ("
						<<	score
						<<	") does not equal "
						<<	pScore->mPositiveCommentsThatCount
						<<	" unique positive comments minus " 
						<<	pScore->mNegativeCommentsThatCount
						<<	" unique negative comments is that some users have left both positive and negative comments about this user. "
						<<	"For example, if a user leaves 2 positive feedbacks and 1 negative feedback for someone, then that user's "
						<<	"total contribution to the feedback rating will be +1. However, the profile makeup above would report "
						<<	"these 3 feedback comments as just 1 unique positive and 1 unique negative comment, even though the final "
						<<	"contribution to the feedback rating is really +1.</font><br>"
						<<	"</td>"
						<<	"</tr>";
		}
#endif

		// explain conversion of suspended users feedback comments to neutral
		if (pScore->mNeutralCommentsFromSuspendedUsers > 0)
		{
			if (pScore->mNeutralCommentsFromSuspendedUsers > 1)	// use plural grammar
			{
				*mpStream	<<	"<tr>"
							<<	"<td colspan=\"2\">"
							<<	"<font size=\"2\"><b>Note:</b> There are "
							<<	pScore->mNeutralCommentsFromSuspendedUsers
							<<	" comments that were converted to neutral because the commenting users"
							<<	" are <a href=\""
							<<	mpMarketPlace->GetHTMLPath()
							<<	"help/myinfo/user-not-registered.html\">no longer registered</a>.</font><br>"
							<<	"</td>"
							<<	"</tr>";
			}
			else	// use singular grammar
			{
				*mpStream	<<	"<tr>"
							<<	"<td colspan=\"2\">"
							<<	"<font size=\"2\"><b>Note:</b> There is "
							<<	pScore->mNeutralCommentsFromSuspendedUsers
							<<	" comment that was converted to neutral because the commenting"
							<<	" user is <a href=\""
							<<	mpMarketPlace->GetHTMLPath()
							<<	"help/myinfo/user-not-registered.html\">no longer registered</a>.</font><br>"
							<<	"</td>"
							<<	"</tr>";
			}

		}

		// end the table
		*mpStream	<<	"</table>"
					<<	"<br>";
	}

	*mpStream	<<	"You can "
					"<A HREF=\""
				<<	mpMarketPlace->GetCGIPath(PageLeaveFeedbackShow)
				<<	"eBayISAPI.dll?LeaveFeedbackShow"
					"&useridto="
				<<	pUser->GetUserId()
				<<	"\">"
					"leave feedback"
					"</A>"
				<<	" for this user.  ";
	*mpStream	<<	"Visit the "
					"<A HREF=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/forum/feedback.html\">"
					"Feedback Forum"
					"</A>"
					" for more info on feedback profiles."
					"<br><br>"
					"\n";

	if (hasSomeFeedback)
	{

		*mpStream	<<	"If you are ";

		pUserIdWidget->SetShowFeedback(true);
		pUserIdWidget->SetUserInfo(pUser->GetUserId(), "ERROR",
									pUser->GetUserState(),
									mpMarketPlace->UserIdRecentlyChanged(pUser->GetUserIdLastModified()),
									pFeedback->GetScore());
		pUserIdWidget->SetShowUserStatus(false);
		pUserIdWidget->SetShowAboutMe();
		pUserIdWidget->SetShowUserId(true);
		pUserIdWidget->SetUserIdBold(false);
		pUserIdWidget->EmitHTML(mpStream);

		*mpStream	<<	", you can "
						"<A HREF=\""
					<<	mpMarketPlace->GetCGIPath(PagePersonalizedFeedbackLogin)
					<<	"eBayISAPI.dll?PersonalizedFeedbackLogin&userid="
					<<	pUser->GetUserId()
					<<	"&items="
					<<	itemsPerPage
					<<	"\">"
						"respond to comments"
						"</A>"
						" in this Feedback Profile. <br><br>";
	}

	// Check for hidden feedback
	if (honorHidden &&
		(pFeedback->GetFlag() & FEEDBACK_FLAG_HIDE))
	{
		*mpStream <<	"<blockquote>"
						"<strong>"
						"Please contact ";
		
		pUserIdWidget->EmitHTML(mpStream);
		
		*mpStream <<	" directly for information about specific feedback comments."
						"</strong>"
						"</blockquote>"
						"";
		*mpStream <<	"\n"
						"<hr>"
						"<A NAME=setoption>"
						"    <I>"
						"If you are the user "
				  <<	pUser->GetUserId()
				  <<	", you can view your private feedback:"
						"</I>"
						"<form method=post action="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageChangeFeedbackOptions)
				  <<	"eBayISAPI.dll"
						"\""
				  <<	">"
						"<INPUT TYPE=HIDDEN "
						"NAME=\"MfcISAPICommand\" "
						"VALUE=\"ChangeFeedbackOptions\">"
						"<input type=hidden name=\"userid\" "
						"value="
						"\""
				  <<	pUser->GetUserId()
				  <<	"\""
						">"
						"<p>"
						"My "
						"password"
						": "
						"<input type=password name=\"pass\" size=\"30\" maxlength=\"64\">"
						"<input type=hidden name=\"option\" value=\"showme\">"
						"<input type=hidden name=\"page\" value=\""
				  <<	startingPage
				  <<	"\">"
						"<input type=hidden name=\"items\" value=\""
				  <<	itemsPerPage
				  <<	"\">"
						"<input type=submit value=\"submit\">"
						"</form><br>";
	}
	else
	{
		if (hasSomeFeedback)
		{
			// Do calculations for pagination.
			if (itemsPerPage == 0)
				endingItem = totalItems;
			else
			{
				if (startingPage == 1)
					endingItem = itemsPerPage;
				else
					endingItem =  min(itemsPerPage * startingPage, totalItems);
			}

			if (pFeedback->GetFlag() & FEEDBACK_FLAG_HIDE)
			{
				// Don't paginate hidden feedback.
				endingItem = totalItems;

				// Write "Item 1-m of n total"
				PrintFeedbackPageStats(mpStream, itemStart, endingItem, totalItems);
			}
			else
			{
				// Do calculations for pagination.
				endingItem = itemsPerPage == 0 ? totalItems
											   : min(itemsPerPage * startingPage, totalItems);

				// Display pagination controls.
				PrintFeedbackPaginationControl(mpStream, itemStart, endingItem,
											   totalItems, itemsPerPage, true,
											   PageViewFeedback, pUser);
			}

			// Now, iterate through the feedback and get the
			// score of each person who LEFT feedback
			for (i = pvItems->begin(), index = itemStart;
				 i != pvItems->end() && index <= endingItem;
				 i++, index++)
			{

				// If this is the first feedback item after the
				// new feature implementation date, then put in
				// the new feature message.
				if (!newFeaturesMessagePrinted && (*i)->mTime < NewFeedbackFeatureDate)
				{
					PrintNewFeedbackFeaturesMessage(mpStream);
					newFeaturesMessagePrinted = true;
				}

				// Now, let's print the feedback!
				PrintFeedbackItem(mpStream, *i);
			}

			if (pFeedback->GetFlag() & FEEDBACK_FLAG_HIDE)
			{
				// Write "Item 1-m of n total"
				PrintFeedbackPageStats(mpStream, itemStart, endingItem, totalItems);
			}
			else
			{
				// Display pagination controls.
				PrintFeedbackPaginationControl(mpStream, itemStart, endingItem,
											   totalItems, itemsPerPage, false,
											   PageViewFeedback, pUser);
			}

			*mpStream	<<	"\n"
				"<p>"
				"This feedback is ordered most-recent first. Each comment is "
				"attributed to its author who takes full responsibility for the "
				"comment. If you have any questions or concerns about a particular "
				"comment, please contact the author directly using the e-mail link "
				"provided with the author\'s User ID."
				"<p>";
		}
	}

	delete pUserIdWidget;

	return;

}

//
// ViewFeedback
//
void clseBayApp::ViewFeedback(CEBayISAPIExtension *pThis,
								 char *pUserId, 
								 int startingPage,
								 int itemsPerPage)
{
	SetUp();

	char *BlockSite[] = {"http://auctions.yahoo.com", 
						 "http://profiles.yahoo.com",
						 "http://auctions.amazon.com",
						 "http://hello123.whatever.com"};
	
	SetUp();

	//get refferrer site name
	const char* pReferrer = gApp->GetEnvironment()->GetReferrer();
	
//	do loop if more than one site need to block
	if ((strnicmp(BlockSite[1], pReferrer, strlen(BlockSite[1])) == 0) ||
		(strnicmp(BlockSite[0], pReferrer, strlen(BlockSite[0])) == 0))
	{
		//show special msg in top
		ShowBlockingMessage(mpMarketPlace, mpStream, "Yahoo!");

	}
	else if (strnicmp(BlockSite[2], pReferrer, strlen(BlockSite[2])) == 0)
	{
		ShowBlockingMessage(mpMarketPlace, mpStream, "Amazon");
	}
	else if (strnicmp(BlockSite[3], pReferrer, strlen(BlockSite[3])) == 0)
	{
		ShowBlockingMessage(mpMarketPlace, mpStream, "Amazon");
	}
	else
	{
		SetCurrentPage(PageViewFeedback);

		// show regular title
		*mpStream <<	"<html><head>"
						"<title>"
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" View User Feedback for "
				  <<	pUserId
				  <<	"</title>"
						"</head>"
				  <<	mpMarketPlace->GetHeader();
	}

	mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
	if (!mpUser)
	{
		*mpStream << "<p>"
			      << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

    //UserUnConfirmed= mpUsers->GetUserState()
	if(mpUser->GetUserState() == UserUnconfirmed ||
	   mpUser->GetUserState() == UserCCVerify) {
		*mpStream << "<H2>User not yet confirmed </H2>"  
			      << "The User ID \""
				  << mpUser->GetUserId()
				  << " \" is not a registered eBay user. "
				  << "Please go back and try again. "
				  << "Make sure you are not using any uppercase characters "
				  << "or allowing blank space before or after or in the User ID.";

		*mpStream << "<p>"
			      << mpMarketPlace->GetFooter();

		return;
	}

	if (mpUser->GetId() == 2573939) // Guernsey's
	{
		*mpStream << "<H2>User not yet confirmed </H2>"  
			      << "The User ID \""
				  << mpUser->GetUserId()
				  << " \" is not a registered eBay user. "
				  << "Please go back and try again. "
				  << "Make sure you are not using any uppercase characters "
				  << "or allowing blank space before or after or in the User ID.";

		*mpStream << "<p>"
			      << mpMarketPlace->GetFooter();

		return;
	}

	GetAndShowFeedback(mpUser, startingPage, itemsPerPage, true);

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}

