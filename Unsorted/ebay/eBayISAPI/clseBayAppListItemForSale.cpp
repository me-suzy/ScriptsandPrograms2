/*	$Id: clseBayAppListItemForSale.cpp,v 1.1.6.28.4.7 1999/08/04 16:51:26 nsacco Exp $	*/
//
//	File:		clseBayAppListItemForSale.cpp
//
//	Class:		clseBayApp
//
//	Author:		Alex Poon
//
//	Function:	clseBayApp::clseBayAppListItemForSale
//
//
//	Modifications:
//				- 04/15/99 AlexP (copied from NewItemQuick essentially)
//				- 05/24/99 nsacco - ship to country fixes
//				- 06/14/99 nsacco - Australia fixes including auction currency
//				- 07/19/99 nsacco - Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco - Changed shipping options, added siteid, language 
//									as hidden fields.
//				- 07/30/99 nsacco - Handle intl reslistings
//				- 08/02/99 nsacco - Fixed the item's currency to use locales and default listing
//										currency.



#include "ebihdr.h"

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


void clseBayApp::ListItemForSale(CEBayISAPIExtension *pServer, char *pItemNo, char *pCatNo, bool oldStyle)
{
	CategoryVector				vCategories;
	int itemNo = 0;
	int catNo = 0;
	int days = 0;
	char		*cleanTitle = NULL;

	clsCategory*		pCategory = NULL;

	const char *pSafeDescription = NULL;
	char*		pCryptedItemNo;
	char		cSalt[10];

	// Used to set the page's expiration to now + 4 weeks
	int			rc;
	time_t		nowTime;
	time_t		expirationTime;
	struct tm	*pExpirationTimeAsTM;
	char		expiresHeader[128];

	int			maxSize;

	int			currencyId;
	int			countryId;

	bool		browserIsJScompatible;

	clsSite*	theSite = NULL;		// nsacco 06/14/99
	int			theDescLang = English;		// nsacco 07/27/99

	static const char* surveyURL = "http://www.esurvey.com/ebay/ssurvey.rti?L=121";

	// Setup
	SetUp();

	// Determine if browser is JS compatible
	browserIsJScompatible = ((GetEnvironment()->GetMozillaLevel() >= 4) && (!GetEnvironment()->IsWebTV()) && (!GetEnvironment()->IsWin16()) && (!GetEnvironment()->IsOpera()));

	// nsacco 06/14/99
	theSite = mpMarketPlace->GetSites()->GetCurrentSite();

	// Get item and category numbers
	if (pItemNo) itemNo = atoi(pItemNo);
	if (pCatNo) catNo = atoi(pCatNo);

	// Let's try and get the item
	if (itemNo && !GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	if (mpItem)
	{
		currencyId = mpItem->GetCurrencyId();
		countryId  = mpItem->GetCountryId();
	}
	else
	{
		// nsacco 08/02/99
		// assume default currency and country of the site
		// until we have user preferences.
		currencyId = theSite->GetDefaultListingCurrency();
		countryId = theSite->GetLocale()->GetCountryId();
	}

	clsFees		objFees(currencyId);
	
	// display fees in default currency for the site
	// NOTE: previously this was always Currency_USD
	clsCurrencyWidget currencyWidget(mpMarketPlace, currencyId, 0); // set below.
		

	// Let's try and get the category
	if (catNo) pCategory = mpCategories->GetCategory(catNo, true);

	// Punt if a category was actually passed in, but we couldn't get it from the db
	if (catNo && !pCategory)
	{
		CleanUp();
		return;
	}


	maxSize = mpMarketPlace->GetMaxAmountSize(currencyId);

	// Calculate length of auction
	if (itemNo) days = (mpItem->GetEndTime() - mpItem->GetStartTime()) / 86400;

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";

	// Set the page to expire 4 weeks from now
	nowTime				= time(0);
	expirationTime		= nowTime + (60*60*24*30);

	pExpirationTimeAsTM	= gmtime(&expirationTime);

	if (pExpirationTimeAsTM)
	{
		// Make it the evil RFC1123 format.
		rc = strftime(expiresHeader,
			 		  sizeof(expiresHeader),
					  "%a, %d %b %Y, %H:%M:%S GMT",
					  pExpirationTimeAsTM);

		if (rc != 0)
		{
			*mpStream <<	"<meta http-equiv=\"Expires\" "
							"content=\""
					  <<	expiresHeader
					  <<	"\">";
		}
	}

	// Usual Title and Header
	*mpStream <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  // nsacco 07/30/99 switch title if relisting
			  <<	(itemNo ? " Relist Item" : " Sell Item");

	if (pCategory)	// show which category if it was specified
	{
		*mpStream	<<	" (";
		
		// if (strlen(pCategory->GetName4())) *mpStream <<	pCategory->GetName4() << ":";
		// if (strlen(pCategory->GetName3())) *mpStream <<	pCategory->GetName3() << ":";
		// if (strlen(pCategory->GetName2())) *mpStream <<	pCategory->GetName2() << ":";
		if (strlen(pCategory->GetName1())) *mpStream <<	pCategory->GetName1() << "->";
		*mpStream	<<	pCategory->GetName();

		*mpStream	<<	")";

	}


	*mpStream <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// nsacco 07/30/99
	// check if relisting an item from another site
	if (mpItem)
	{
		// an item can only be relisted on the site it was originally listed on
		// so the current site id and item's site id must match to relist
		if (mpItem->GetSiteId() != theSite->GetId())
		{
			// TODO - fix the building of the url
			*mpStream << "<h2>"
						 "Relisting Item Error"
						 "</h2>"
						 "<p>Items can only be relisted on the site on which "
						 "they were originally listed.</p>"
						 "<p>To relist your item click "
						 "<a href=\"http://cgi5"
					  << clsUtilities::GetDomainToken(mpItem->GetSiteId(), PARTNER_EBAY)
					  << "/aw-cgi/"
					  << "eBayISAPI.dll?ListItemForSale&item="
					  << pItemNo
					  << "\">here</a>.</p>";

			*mpStream << mpMarketPlace->GetFooter();
			return;
		}
	}

	// Redirect to the oldstyle page if the user's browser is js-capable, but has
	// javascript disabled for some reason. (Nathan's cool idea)
	if (!oldStyle && !pCategory && browserIsJScompatible)
	{
		*mpStream	<<	"<NOSCRIPT>These pages require Javascript to be turned on. You will be "
						"redirected to a non-Javascript version of the page. "
						"<meta http-equiv=\"refresh\" content=\"0;"
						"URL="
					<<	mpMarketPlace->GetCGIPath(PageListItemForSale)
					<<	"eBayISAPI.dll?ListItemForSale&oldStyle=1";

		if (itemNo)
			*mpStream	<<	"&item=" << itemNo;

		*mpStream	<<	"\"></NOSCRIPT>";

	} // end of redirect if-then

/*
	// Show temporary survey link
	*mpStream <<	"<font size=2>"
					"<a href=\""
			  <<	surveyURL
			  <<	"\">"
					"Tell us what you think of this sell your item page.</a>"
					"</font><br><br>";
*/

	// Sell Your Item graphic title in column 1
	// Related links in column 2
	*mpStream	<<	"<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"600\">\n"
					"<tr>\n"
					"<td valign=top>"
				<<	"<font size=\"5\" face=\"Verdana, Arial, Helvetica, sans-serif\">"
				<<	"<b>"
				<<	(itemNo ? "Relist Your Item" : "Sell Your Item")
				<<	"</b></font>"
					"</td>"
					"<td valign=middle align=right>"
					"<font size=\"2\"><i>Related<br>Links:</i></font>\n"
					"</td>"
					"<td valign=top>"
					"<font size=\"4\">&nbsp;<b>&#183;</b></font>\n"
					"<font size=\"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/basics/n-selling.html\">"
				<<	"New to Selling?</a></font>\n"
					"<font size=\"4\">&nbsp;<b>&#183;</b></font>\n"
					"<font size=\"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/selling-tips.html\">"
				<<	"Seller Tips</a></font>\n"
					"<font size=\"4\">&nbsp;<b>&#183;</b></font>\n"
					"<font size=\"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/selling-fees.html\">"
				<<	"Fees</a></font>\n"
					"<font size=\"4\">&nbsp;<b>&#183;</b></font>\n"
					"<font size=\"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"services/registration/register.html\">"
				<<	"Registration</a></font>\n"
					"<br>"
					"<font size=\"4\">&nbsp;<b>&#183;</b></font>\n"
					"<font size=\"2\">"
					"<a href=\""
				<<	"http://ebay.iship.com/ebay/price.asp\">"
				<<	"Free Shipping Estimates from iShip.com</a></font>\n";

/*
	*mpStream	<<	"<font size=\"2\">";

	if (pCategory)	// show which category if it was specified
	{
		*mpStream <<	" in ";
				 
		if (strlen(pCategory->GetName4())) *mpStream <<	pCategory->GetName4() << ":";
		if (strlen(pCategory->GetName3())) *mpStream <<	pCategory->GetName3() << ":";
		if (strlen(pCategory->GetName2())) *mpStream <<	pCategory->GetName2() << ":";
		if (strlen(pCategory->GetName1())) *mpStream <<	pCategory->GetName1() << ":";

		*mpStream <<	pCategory->GetName();		// actual
	}

	*mpStream <<	"</font>";


*/

	*mpStream <<	"</td></tr></table>"
					"\n";
	*mpStream <<	"<b><br>Registration required.</b> "
			  <<	"You must be a "
			  <<	"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/registration/register.html\">"
			  <<	"registered eBay user</a> to sell your item.\n";


	// Begin the form					
	*mpStream <<	"<form name=\"ListItemForSale\" method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageVerifyNewItem)
			  <<	"eBayISAPI.dll?VerifyNewItem"
			  <<	"\""
					">\n";

	// --------------------------------------------------------------------------------------
	// Table for title
	// --------------------------------------------------------------------------------------

	// put a green border around each group of items
	*mpStream <<	"<table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";

	// Row for title
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<font size=\"3\">"
					"<b>Title</b>"
					"<br><font size=\"2\" color=\"#006600\">required</font>"
					"</font>";

	*mpStream <<	"</td>"
					"<td width=\"75%\">"
					"<input type=\"text\" name=\"title\" ";

	if (itemNo)
	{
		// in case the original title has quotes in it, we need
		//  to change them to &#34
		cleanTitle = clsUtilities::StripHTML(mpItem->GetTitle());
		if (cleanTitle)
		{
			*mpStream <<	"value=\""
					  <<	cleanTitle
					  <<	"\" ";
			delete [] cleanTitle;
		}
	}
	*mpStream <<	"size=" << EBAY_MAX_TITLE_SIZE << " "
					"maxlength=" << EBAY_MAX_TITLE_SIZE << ">"
					"<br><font size=\"2\"> (45 characters max; no HTML tags, "
					"asterisks,  or quotes, as they interfere with Search)";

	*mpStream <<	"&nbsp; <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Title\">see tips</a>"
			  <<	"</font>";

					
	*mpStream <<	"</td></tr>";

	// end table title
	*mpStream <<	"</table>";

	// --------------------------------------------------------------------------------------
	// Table for category selector
	// --------------------------------------------------------------------------------------

	// show full category selector if category wasn't specifed
	if (!pCategory)
	{

		// Link to the same form, but forcing the old style selector
		//  Show this link only if
		//  1. The category isn't already set
		//  2. The user's browser is new enough that the new style would be the
		//     the default one
		//	3. User didn't already explcitly choose to use the old style
		if (!oldStyle && !pCategory && browserIsJScompatible)
		{
			*mpStream <<    "<p>"
					  <<	"<font size=\"3\">";
			*mpStream <<	"If you prefer to use the old-style method of choosing a category, click "
					  <<	"<a href=\""
					  <<	mpMarketPlace->GetCGIPath(PageListItemForSale)
					  <<	"eBayISAPI.dll?ListItemForSale&oldStyle=1";

			if (itemNo)
				*mpStream	<<	"&item=" << itemNo;

			*mpStream <<	"\">here</a>.<br></font>";
		}
/*
		// Temporary note about Vehicles and Real Estate
		*mpStream	<<	"<br><font size=\"3\"><b>Note:</b> "
						"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/selling-fees.html\">"
					<<	"Fees</a>"
					<<	" for Vehicles and Real Estate have recently changed.</font><br>";
*/
		// Use javascript version if browser supports it
		if (!oldStyle && browserIsJScompatible)
		{
			mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"ListItemForSale",
									"", 
									"Category1",
									(itemNo ? mpItem->GetCategory() : NULL), 
									true);

		}
		else	// use old-style category selector
		{

			// Begin category table
			*mpStream <<	"<br><table border=\"1\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>\n"
							"<b><font size=\"3\">Category</b>";

			*mpStream <<	"<font size=\"2\">&nbsp; (Choose <b>one</b> category only; be as specific as possible. )"
							"&nbsp; "
							"</font><br><font size=\"2\" color=\"#006600\">required</font></font>";

			*mpStream <<	"</td>\n"
							"</tr>\n"
							"<tr>\n" 
							"<td width=100%>"
							"<table border=\"0\" cellpadding=\"0\" cellspacing=\"6\" width=\"100%\">"
							"<tr>\n"
							"<td width=\"50%\" valign=\"middle\">";

			// Emit Category choice list using 2 columns
			mpCategories->EmitHTMLLeafSelectionMultipleDropdown(mpStream,
													(itemNo ? mpItem->GetCategory() : NULL),
													&vCategories,
													2,
													"category",
													false,
													true);


			*mpStream <<	"</td>\n"
							"</tr>\n";	
							
			// End table for category selector
			*mpStream <<	"</table>\n";


			// End category table
			*mpStream <<	"</td>"
							"</tr>"
							"</table>\n";
		}



	}
	else
	{
		// Begin category table
		*mpStream <<	"<br><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\">"
						"<tr>"
						"<td width=\"25%\" align=\"right\">"
						"<b><font size=\"2\">Category</font></b></td>";

		// ok, category was specified, so just reinforce to user which one it is
		*mpStream <<	"<td width=\"10\">&nbsp;</td>"
						"<td width=\"75%\"><font size=\"2\"> &nbsp;";

		if (strlen(pCategory->GetName4())) *mpStream <<	pCategory->GetName4() << ":";
		if (strlen(pCategory->GetName3())) *mpStream <<	pCategory->GetName3() << ":";
		if (strlen(pCategory->GetName2())) *mpStream <<	pCategory->GetName2() << ":";
		if (strlen(pCategory->GetName1())) *mpStream <<	pCategory->GetName1() << ":";

		*mpStream <<	pCategory->GetName()		// actual
				  <<	" &nbsp;(#"
				  <<	pCategory->GetId()
				  <<	")</font>";

		// put it in a hidden field
		*mpStream << "<input type=hidden name=category1 value=\""
				  << pCategory->GetId()
				  << "\">";

		// End category table
		*mpStream <<	"</td>"
						"</tr>"
						"</table>\n";
	}
	// End of category

	// --------------------------------------------------------------------------------------
	// Table for desc, picture, and gallery
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";
	*mpStream <<	"<tr>"
 					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\""
					"><font size=\"3\">"
					"<b>Description</b>"
					"<br><font size=\"2\" color=\"#006600\">required</font></font>"
					"</td>"
					"<td width=\"75%\">"
					"<textarea name=\"desc\" cols=\"56\" rows=\"8\">";


	if (itemNo)
	{
		if (mpItem->GetDescription())
		{
//			pSafeDescription = clsUtilities::DrawSafeHTML(mpItem->GetDescription());
//			if (pSafeDescription)
//				*mpStream << pSafeDescription;
//			else
				*mpStream << mpItem->GetDescription();
//			char *transformed = TransformInput(mpItem->GetDescription(), TRUE);
//			*mpStream <<	transformed;
//			delete [] transformed;

		}
		else
		{
			pSafeDescription = NULL;
			*mpStream <<	"none";
		}
	}
	else
		pSafeDescription = NULL;


	*mpStream <<	"</textarea><br><font size=\"2\">\n"
					"You can use basic HTML tags to spruce up your listing.\n";

	*mpStream <<	"&nbsp; <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Description\">see tips</a>"
					"<br><br>"
					"You can add links to additional photos, but enter your primary "
					"photo in the Picture URL below. If you want more than one photo "
					"for your item, insert its URL in the Description section in the "
					"following format:<br>&lt;img src=http://www.anywhere.com/mypicture.jpg&gt;"
			  <<	"</font>";


	*mpStream <<	"</td>"
					"</tr>";

	// row for picture url
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Picture URL</b>"
					"<font size=\"2\" color=\"#990000\"><br>optional</font>"
					"</font>"
					"</td>"
					"<td width=\"75%\" valign=\"top\">"
					"<br>"
					"<input type=\"text\" name=\"picurl\" "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PICURL_SIZE << " ";
	if (itemNo)
	{
		*mpStream <<	"value=\"";
		*mpStream <<	((mpItem->GetPictureURL()) ? mpItem->GetPictureURL() : "http://");
		*mpStream <<	"\"";
	}
	else
	{
		*mpStream <<	"value=\"http://\"";
	}
	*mpStream <<	">"
			  <<	"<font size=\"2\">"
					" <img src=\""
			<<		mpMarketPlace->GetPicsPath()
			<<		"listings/browse-icon-pic.gif\" alt=\"[PIC!]\" width=\"16\" height=\"14\">"
			<<		"<br>It's easy! Learn the basics in the "
			<<		"<a href=\""
			<<		mpMarketPlace->GetHTMLPath()
			<<		"help/basics/n-phototut-index.html\">"
					"tutorial</a>, and enter your URL here.</font>";
	
	*mpStream <<	"</td>"
					"</tr>\n";

	// row for gallery 
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\"><font size=\"3\">"
					"<br>"
					"<b>The Gallery</b></a></font><br>"
				<<	"<font size=\"2\" color=\"red\"><b>Don't get left out! "
					"Items in the Gallery get more bids!</b></font>"
					"<br>"
					"<font size=\"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/gallery-faq.html\">"
					"learn more</a>"
					"</font></td>\n";


	 *mpStream <<	"<td width=\"75%\">\n"
					"<br>"
					"<table><tr><td>"
					"<input type=\"radio\" name=gallery value=\"0\"";

	if (itemNo)
	{
		if((mpItem->GetGalleryType() == NoneGallery))
			*mpStream <<	" checked";
	}
	else
		*mpStream <<	" checked";

    *mpStream	<<	"><font size=\"2\">Do not include my item in the Gallery &nbsp<br>";

	*mpStream	<< "<input type=\"radio\" name=gallery value=\"1\"";

	if (itemNo && (mpItem->GetGalleryType() == Gallery))
		*mpStream <<	" checked";
	*mpStream	<<	">Add my item to the Gallery (only <b>";
	
	currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFee));
	currencyWidget.EmitHTML(mpStream);	
		
	*mpStream   << "!</b>)<br>";
	
	*mpStream	<< "<input type=\"radio\" name=gallery value=\"2\"";
	if (itemNo && (mpItem->GetGalleryType() == FeaturedGallery))
		*mpStream <<	" checked";
	*mpStream	<<	">Feature my item in the Gallery &nbsp;(Featured fee of ";

	currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFeaturedFee));
	currencyWidget.EmitHTML(mpStream);			

	*mpStream	<<	")</font>";

	*mpStream	<<	"</td><td width=\"50\">"
				<<	"<img src=\""
				<<	mpMarketPlace->GetPicsPath()
				<<	"gallery/gallery-pic.gif\" width=\"41\" height=\"51\" align=\"right\">"
					"</td></tr></table>";

	*mpStream	<<	"<br>"
					"<input type=\"text\" name=\"galleryurl\" "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PICURL_SIZE << " ";
	if (itemNo)
	{
		*mpStream <<	"value=\"";

		*mpStream <<	((mpItem->GetGalleryURL()) ? mpItem->GetGalleryURL() : "http://");
		*mpStream <<	"\"";
	}
	else
	{
		*mpStream <<	"value=\"http://\"";
	}
	*mpStream <<	">"
					"<br>"
					"<font size=\"2\">"
			  <<	"If you leave the Gallery URL empty, your Pic URL "
					"will be used as your Gallery URL. (Only jpg, bmp, or tif files "
					"can be used in the Gallery. Please note that "
					"<b>gif</b> files will <b>not</b> appear in the Gallery!)</font>";

	*mpStream	<<	"</td></tr>";


	*mpStream  <<	"</table>\n";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";


	// --------------------------------------------------------------------------------------
	// Table for bold, featured, cat featured, and great gift
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";

	// Row for "Make your item stand out and get more bids! Try these winning options."
	*mpStream <<	"<tr>\n"
			  <<	"<td align=\"center\" colspan=\"3\">"
			  <<	"<font size=\"3\" color=\"red\">"
			  <<	"<b>"
			  <<	"Make your item stand out and get more bids! Try these winning options."
			  <<	"</b>"
			  <<	"</font>"
			  <<	"</td>"
			  <<	"</tr>\n";

	// Row for boldface title 
	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<font size=\"3\">"
					"<b>Boldface Title?</b>"
					"</font>"
					"</td>"
					"<td width=\"75%\">"
					"<input type=checkbox name=bold";
	
	if (itemNo && mpItem->GetBoldTitle())
	{
		*mpStream <<	" checked";
	}

	*mpStream <<	">"
					"<font size=\"2\">";
					
	currencyWidget.SetNativeAmount(objFees.GetFee(BoldFee));
	currencyWidget.EmitHTML(mpStream);
					
	*mpStream <<    " charge</font>";
					
	*mpStream <<	"</td>\n"
						"</tr>\n";	

	// Row for super featured
	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Featured?</b>"
					"</font></td>"
					"<td width=\"75%\">"
					"<br>"
					"<input type=checkbox name=superfeatured";
	if (itemNo && mpItem->GetSuperFeatured())
	{
		*mpStream <<	" checked";
	}

	*mpStream <<	">"
					" <font size=\"2\">";

	currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
	currencyWidget.EmitHTML(mpStream);

	*mpStream <<    " charge";

	*mpStream <<	"&nbsp; <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Featured\">learn more</a>"
			  <<	"</font>";	

	*mpStream <<	"</td>\n"
					"</tr>\n";


	// Row for category featured
	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Featured in Category?</b>"
					"</font></td>"
					"<td width=\"75%\">"
					"<br>"
					"<input type=checkbox name=featured";
	if (itemNo && mpItem->GetFeatured())
	{
		*mpStream <<	" checked";
	}

	*mpStream <<	">"
					" <font size=\"2\">";

	currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
	currencyWidget.EmitHTML(mpStream);	
	
	*mpStream <<	" charge";

	*mpStream <<	"&nbsp; <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#FeaturedCategory\">learn more</a>"
			  <<	"</font>";	
						
	*mpStream <<	"</td>\n"
					"</tr>\n";



	// Row for gift icon
	int			giftIconType;
	char		pGiftIcon[4];

	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Great Gift icon?</b>"
					"</font></td>"
					"<td width=\"75%\">"
					"<br>";

	// for mutiple gift icons 
	if (itemNo && mpItem != NULL )
	{
		giftIconType = mpItem->GetGiftIconType();
		sprintf(pGiftIcon, "%d", giftIconType);
		EmitDropDownList(mpStream,
					 "giftIcon",
					 (DropDownSelection *)&GiftIconSelection,
					 pGiftIcon,
					 "0",
					 "Not Selected"); 
	}
	else
	{
		EmitDropDownList(mpStream,
						 "giftIcon",
						 (DropDownSelection *)&GiftIconSelection,
						 NULL,
						 "0",
						 "Not Selected"); 
	}


	*mpStream <<	"<font size=\"2\">&nbsp;";
	
	currencyWidget.SetNativeAmount(objFees.GetFee(GiftIconFee));
	currencyWidget.EmitHTML(mpStream);	
		
	*mpStream	 << " charge";

	*mpStream <<	"&nbsp; <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#GreatGift\">learn more</a>"
			  <<	"</font>";
	
	*mpStream <<	"</td>\n"
					"</tr>\n";

	*mpStream <<	"</table>";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";

	// --------------------------------------------------------------------------------------
	// Table for location, zip and country
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";

	// Row for location
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<font size=\"3\">"
					"<b>Item location</b>"
					" <br><font size=\"2\" color=\"#006600\">required</font></font>"
					"<td width=\"75%\">";

	*mpStream << "<input type=\"text\" name=\"location\" ";

	if (itemNo)
	{
		*mpStream <<	"value=\""
				  <<	mpItem->GetLocation()
				  <<	"\" ";
	}

	*mpStream <<	"size=" << EBAY_MAX_LOCATION_SIZE << " "
					"maxlength=" << EBAY_MAX_LOCATION_SIZE << ">";

	// nsacco 07/27/99 changed example text
	// nsacco 6/14/99 use clsSite
	// display a location example
	switch (theSite->GetId())
	{
	case SITE_EBAY_UK:
		*mpStream << "<br><font size=\"2\">City,  Region (e.g., Aylesbury, Bucks)</font>"
			"<p>";
		break;
	case SITE_EBAY_CA:
		*mpStream << "<br><font size=\"2\">City,  Region (e.g., Vancouver, BC)</font>"
			"<p>";
		break;
	case SITE_EBAY_AU:
		*mpStream << "<br><font size=\"2\">City,  Region (e.g., North Sydney, NSW)</font>"
			"<p>";
		break;
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		*mpStream << "<br><font size=\"2\">City,  Region (e.g., Redwood City, CA)</font>"
			"<p>";
		break;
	}

	// zip code

	*mpStream << "<input type=\"text\" name=\"zip\" ";

	if (itemNo)
	{
		*mpStream <<	"value=\"";
		if (mpItem->GetZip())
			*mpStream << mpItem->GetZip();
		*mpStream << "\" ";
	}

	*mpStream <<	"size=" << EBAY_MAX_ZIP_SIZE << " "
					"maxlength=" << EBAY_MAX_ZIP_SIZE << ">"
			  <<	"<br>";

	// nsacco 07/27/99 changed example text
	// nsacco 6/14/99 use clsSite
	// output the correct postal code format for the site
	switch(theSite->GetId())
	{
	case SITE_EBAY_UK:
		*mpStream <<	"<font size=\"2\">Zip or postal code (e.g., HP18 9BX)</font>";
		break;
	case SITE_EBAY_AU:
		*mpStream <<	"<font size=\"2\">Zip or postal code (e.g., 2060)</font>";
		break;
	case SITE_EBAY_CA:
		*mpStream <<	"<font size=\"2\">Zip or postal code (e.g., 5J5 8K3)</font>";
		break;
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		*mpStream <<	"<font size=\"2\">Zip or postal code (e.g., 94062)</font>";
		break;
	}

	*mpStream <<  "<p>";

	// country item is located in
	// nsacco 06/14/99
	ScrollingSelection *pAllCountries;

	clsCountries *pCountries = mpMarketPlace->GetCountries();

	if (pCountries)
	{
		pAllCountries = new ScrollingSelection[pCountries->GetNumCountries() + 1];
		// + 1 for NULL entry at the end

		pCountries->FillScrollingSelection(pAllCountries);

		// select the country the item is located in or the site default
		// countryId is already set to this
		EmitScrollingList(mpStream,
						  "countryid",
						  1,
						  pAllCountries,					  
						  countryId,
						  true);

		delete [] pAllCountries;
	}
	
	// NOTE: shouldn't there be some error handling here in case pCountries fails?
	// nsacco 07/27/99 removed font face
	*mpStream <<	"<br><font size=\"2\">Country</font>\n";

	*mpStream <<	"</td>"
					"</tr>";			
	*mpStream <<	"</table>\n";

	// end table			
	*mpStream <<	"\n</td></tr></table>\n";

	// nsacco 07/01/99 auction currency

	clsCurrency* theCurrency = mpMarketPlace->GetCurrencies()->GetCurrency(currencyId);
	char pCountry[256];
	// nsacco 07/27/99
	// set pCountry to be the country of the site
	switch (theSite->GetId())
	{
	case SITE_EBAY_DE:
		strcpy(pCountry, "Germany");
		break;
	case SITE_EBAY_UK:
		strcpy(pCountry, "United Kingdom");
		break;
	case SITE_EBAY_AU:
		strcpy(pCountry, "Australia");
		break;
	case SITE_EBAY_CA:
		strcpy(pCountry, "Canada");
		break;
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
		strcpy(pCountry, "United States");
		break;
	}

	if (itemNo)
	{
		// set the currency for the item
		*mpStream << "<input type=\"hidden\" name=\"currencyid\" value=\"";
		
		// nsacco 08/02/99
		// this is the currency stored with the item
		*mpStream	<<	currencyId
					<<	"\">";
	}
	else	// new item
	{
		*mpStream << "<input type=\"hidden\" name=\"currencyid\" value=\"";
		
		// nsacco 08/02/99
		// get the currency from the site
		*mpStream << theSite->GetDefaultListingCurrency()
				  << "\">";
		
	}


	// --------------------------------------------------------------------------------------
	// Table for payment methods, who pays for shipping, and where will you ship
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n"
					"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\"><font size=\"3\">"
					"<b>Payment Methods</b></font>"
					"<br>"
					"<font size=\"2\">"
					"Choose all that you will accept"
					"</font>"
					"</td>"
					"<td width=\"75%\">"
					"<table cellpadding=0 cellspacing=0>"
					"<tr valign=\"top\">";
	if (itemNo && (mpItem->AcceptsPaymentMOCashiers()))
		*mpStream <<	"<td align = \"left\">"
						"<input type=\"checkbox\" name=\"moneyOrderAccepted\" checked value=\"on\"> </td>"
						"<td width=170> <font size=\"2\">Money Order/Cashiers Check</font>" 
						"</td>";
	else
		*mpStream <<	"<td align = \"left\">"
						"<input type=\"checkbox\" name=\"moneyOrderAccepted\" value=\"on\"> </td>"
						"<td width=170> <font size=\"2\">Money Order/Cashiers Check</font>" 
						"</td>";
	if ((itemNo) && (mpItem->AcceptsPaymentPersonalCheck()))
		*mpStream <<	"<td align = \"right\">"
						"<input type=\"checkbox\" name=\"personalChecksAccepted\" checked "
						"value=\"on\"> </td>"
						"<td width=105> <font size=\"2\">Personal Check </font>"
						"</td>";			
	else
		*mpStream <<	"<td align = \"right\">"
						"<input type=\"checkbox\" name=\"personalChecksAccepted\" "
						"value=\"on\"> </td>"
						"<td width=105> <font size=\"2\">Personal Check </font>"
						"</td>";			
	if ((itemNo) && (mpItem->AcceptsPaymentVisaMaster()))
		*mpStream <<	"<td align = \"right\">" 
						"<input type=\"checkbox\" name=\"visaMasterCardAccepted\" checked value=\"on\"> </td>"
						"<td align = \"left\"><font size=\"2\">Visa/MasterCard </font>"
						"</td></tr>";	
	else
		*mpStream <<	"<td align = \"right\">" 
						"<input type=\"checkbox\" name=\"visaMasterCardAccepted\" value=\"on\"> </td>"
						"<td align = \"left\"><font size=\"2\">Visa/MasterCard </font>"
						"</td></tr>";	
	if ((itemNo) && (mpItem->AcceptsPaymentCOD()))
		*mpStream <<	"<tr valign=\"middle\"><td>"
						"<input type=\"checkbox\" name=\"paymentCOD\" checked value=\"on\"> </td>"
						"<td><font size=\"2\">COD (collect on delivery) </font>"
						"</td>";
	else
		*mpStream <<	"<tr valign=\"middle\"><td>"
						"<input type=\"checkbox\" name=\"paymentCOD\" value=\"on\"> </td>"
						"<td><font size=\"2\">COD (collect on delivery) </font>"
						"</td>";
	if ((itemNo) && (mpItem->AcceptsPaymentEscrow()))
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"onlineEscrow\" checked value=\"on\"></td>"
						"<td>"
						"<font size=\"2\">On-line Escrow</font></td>";
	else
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"onlineEscrow\" value=\"on\"></td>"
						"<td>"
						"<font size=\"2\">On-line Escrow</font></td>";

	if ((itemNo) && (mpItem->AcceptsPaymentAmEx()))
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"amExAccepted\" checked value=\"on\"></td>"
						"<td>"
						"<font size=\"2\">American Express</font></td>"
						"</tr>";
	else
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"amExAccepted\" value=\"on\"></td>"
						"<td>"
						"<font size=\"2\">American Express</font></td>"
						"</tr>";
	if (itemNo) 
	{
		*mpStream <<	"<tr valign=\"middle\">"
						"<td><input type=\"checkbox\" name=\"paymentSeeDescription\" ";
		if (mpItem->MorePaymentSeeDescription())
			*mpStream << "checked ";

		*mpStream <<    "value=\"on\"> </td>"
						"<td>"
						"<font size=\"2\">See Item Description</font> </td>";
	}
	else
		*mpStream <<	"<tr valign=\"middle\">"
						"<td><input type=\"checkbox\" name=\"paymentSeeDescription\" checked value=\"on\"> </td>"
						"<td>"
						"<font size=\"2\">See Item Description</font> </td>";
	if ((itemNo) && (mpItem->AcceptsPaymentOther()))
		*mpStream <<	"<td><input type=\"checkbox\" name=\"otherAccepted\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\">Other </font> </td>\n";
	else
		*mpStream <<	"<td><input type=\"checkbox\" name=\"otherAccepted\" value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\">Other </font> </td>\n";
	if ((itemNo) && (mpItem->AcceptsPaymentDiscover()))
		*mpStream <<	"<td><input type=\"checkbox\" name=\"discoverAccepted\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\">Discover </font> </td>\n"
						"</tr>\n";
	else
		*mpStream <<	"<td><input type=\"checkbox\" name=\"discoverAccepted\" value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\">Discover </font> </td>\n"
						"</tr>\n";
						
	*mpStream <<	"</table>\n"
					"</td>\n"
					"</tr>\n";

	// row for where will you ship
	*mpStream <<		"<tr>\n"
						"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\"><font size=\"3\">"
						"<br>"
						"<b>Where will you ship?</b></font></td>\n"
						"<td width=\"75%\">\n"
						"<br>"
						"<table cellpadding=0 cellspacing=0 width=\"100%\">\n"
						"<tr valign=\"middle\">\n";


	// nsacco 07/27/99
	// new shipping options
	if (itemNo)
	{
		*mpStream <<	"<td>"
						"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteonly\" ";
		
		if (mpItem->IsShippingToSiteOnly())
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">Will ship to "
				  <<		pCountry
				  <<		" only</font><br>\n"
							"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteplusregions\" ";
		if (mpItem->IsShippingToSiteAndRegions())
			*mpStream <<	"checked ";
		
		*mpStream <<		">"
							"<font size=\"2\">Will ship to "
				  <<		pCountry
				  <<		" and the following regions: (Check all that apply)</font>\n"
							"<blockquote>"
							"<input type=\"checkbox\" name=\"northamerica\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_NorthAmerica))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">North America</font><br>"
							"<input type=\"checkbox\" name=\"europe\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_Europe))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">Europe</font><br>"
							"<input type=\"checkbox\" name=\"oceania\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_Oceania))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">Australia / NZ</font><br>"
							"<input type=\"checkbox\" name=\"asia\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_Asia))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">Asia</font><br>"
							"<input type=\"checkbox\" name=\"southamerica\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_SouthAmerica))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">South America</font><br>"
							"<input type=\"checkbox\" name=\"africa\" value=\"on\" ";
		if (mpItem->IsShippingToRegion(ShipRegion_Africa))
			*mpStream <<	"checked ";

		*mpStream <<		">"
							"<font size=\"2\">Africa</font>"
							"</blockquote>\n"
							"<input type=\"radio\" name=\"shippingInternationally\" value=\"worldwide\" ";
		if (mpItem->IsShippingInternationally())
			*mpStream <<	"checked ";
		
		*mpStream <<		">"
							"<font size=\"2\">Will ship internationally (worldwide)</font>"
							"</td>\n";
	}
	else
	{
		*mpStream <<		"<td>"
							"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteonly\" checked >"
							"<font size=\"2\">Will ship to "
				  <<		pCountry
				  <<		" only</font><br>\n"
							"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteplusregions\" >"
							"<font size=\"2\">Will ship to "
				  <<		pCountry
				  <<		" and the following regions: (Check all that apply)</font>\n"
							"<blockquote>"
							"<input type=\"checkbox\" name=\"northamerica\" value=\"on\" >"
							"<font size=\"2\">North America</font><br>"
							"<input type=\"checkbox\" name=\"europe\" value=\"on\" >"
							"<font size=\"2\">Europe</font><br>"
							"<input type=\"checkbox\" name=\"oceania\" value=\"on\" >"
							"<font size=\"2\">Australia / NZ</font><br>"
							"<input type=\"checkbox\" name=\"asia\" value=\"on\" >"
							"<font size=\"2\">Asia</font><br>"
							"<input type=\"checkbox\" name=\"southamerica\" value=\"on\" >"
							"<font size=\"2\">South America</font><br>"
							"<input type=\"checkbox\" name=\"africa\" value=\"on\" >"
							"<font size=\"2\">Africa</font>"
							"</blockquote>\n"
							"<input type=\"radio\" name=\"shippingInternationally\" value=\"worldwide\" >"
							"<font size=\"2\">Will ship internationally (worldwide)</font>"
							"</td>\n";
	}
	// end new shipping options

	*mpStream <<		"</tr>\n"
						"</table>\n";

	*mpStream <<	" <font size=\"2\"><a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Shipping\">see tips</a>"
			  <<	"</font>";

	*mpStream <<		"</td>\n"
						"</tr>\n";

	// row for who pays for shipping
	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\"><font size=\"3\">"
					"<br>"
					"<b>Who pays for shipping?</b></font>"
					"</td>\n"
					"<td width=\"75%\">\n"
					"<br>"
					"<table cellpadding=0 cellspacing=0 width=\"100%\">\n"
					"<tr valign=\"middle\">\n";
	if ((itemNo) && (mpItem->SellerPaysForShipping()))
		*mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"sellerPaysShipping\" checked value=\"on\">"
						"<font size=\"2\">Seller Pays Shipping &nbsp;&nbsp;</font>" 
						"</td>\n";
	else
		*mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"sellerPaysShipping\" value=\"on\">"
						"<font size=\"2\">Seller Pays Shipping &nbsp;&nbsp;</font>" 
						"</td>\n";
	if ((itemNo) && (mpItem->BuyerPaysForShippingFixed()))
	  *mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"buyerPaysShippingFixed\" checked value=\"on\">"
					"<font size=\"2\">Buyer Pays Fixed Amount&nbsp;</font>" 
					"</td>\n"
					"</tr>\n";
	else
	  *mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"buyerPaysShippingFixed\" value=\"on\">"
					"<font size=\"2\">Buyer Pays Fixed Amount&nbsp;</font>" 
					"</td>\n"
					"</tr>\n";
	if ((itemNo) && (mpItem->BuyerPaysForShippingActual()))
		*mpStream <<	"<tr valign=\"middle\">\n"
						"<td> <input type=\"checkbox\" name=\"buyerPaysShippingActual\" checked value=\"on\">"
						"<font size=\"2\">Buyer Pays Actual Shipping Cost&nbsp;</font>" 
						"</td>\n";
	else
		*mpStream <<	"<tr valign=\"middle\">\n"
						"<td> <input type=\"checkbox\" name=\"buyerPaysShippingActual\" value=\"on\">"
						"<font size=\"2\">Buyer Pays Actual Shipping Cost&nbsp;</font>" 
						"</td>\n";
	if (itemNo)
	{
		*mpStream <<	"<td> <input type=\"checkbox\" name=\"shippingSeeDescription\" ";
		if (mpItem->MoreShippingSeeDescription())
			*mpStream << "checked ";

		*mpStream << "value=\"on\">"
					 "<font size=\"2\">See Item Description</font></td>\n"
					 "</tr>\n"
					 "</table>";
	}
	else
		*mpStream <<	"<td> <input type=\"checkbox\" name=\"shippingSeeDescription\" checked value=\"on\">"
						"<font size=\"2\">See Item Description</font></td>\n"
						"</tr>\n"
						"</table>\n";

	*mpStream <<		"</td>\n"
						"</tr>\n";

	*mpStream <<		"</table>\n";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";

	// --------------------------------------------------------------------------------------
	// Table for quantity, minimum bid, and duration
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<font size=\"3\"><b>Quantity</b>"
					"<font size=\"2\" color=\"#006600\"><br>required</font>"
					"</font></td>"
					"<td width=\"75%\">"
					"<input type=\"text\" name=\"quant\" ";
	if (itemNo)
	{
	*mpStream <<	"value=\""
			  <<	mpItem->GetQuantity()
			  <<	"\" ";
	}
	else
	{
	*mpStream <<	"value=\""
			  <<	"1"
			  <<	"\" ";
	}	
	*mpStream <<	"size=\"6\" "
			  <<	"maxlength=" << EBAY_MAX_QUANTITY_SIZE << ">";

	// dutch rules
	*mpStream <<		"<br>"
						"<font size=\"2\">If quantity is more than one, then you will have a "
						"<a href=\""
			  <<		mpMarketPlace->GetHTMLPath()
			  <<		"help/sellerguide/new-dutch-rules.html\">"
						"Dutch auction</a>.";

	*mpStream <<	"<br><a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Quantity\">see tips</a>"
			  <<	"</font>";					

	*mpStream <<	"</td>"
					"</tr>\n";

	// row for Minimum bid
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Minimum bid</b>"
					"</font>"
					// nsacco 07/15/99 show currency
					"<br><font size=\"2\">(in "
			  <<	theCurrency->GetSymbol()
			  <<	")</font>"
					"<br><font size=\"2\" color=\"#006600\">required</font>"
					"</font></td>"
					"<td width=\"75%\" valign=\"top\">"
					"<br>"
					"<input type=\"text\" name=\"startprice\" ";
	if (itemNo)
	{
		*mpStream <<	"value=\""
		<<	mpItem->GetStartPrice()
		<<	"\" ";
	}
	*mpStream <<	"size=" << maxSize << " "
					"maxlength=" << maxSize << ">"
					"<font size=\"2\"> per item";

	*mpStream <<	" <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Minimum\">see tips</a>"
					"<br>"
					"(e.g., 2.00 -- Please do not include commas or currency "
					"symbols, such as $.)\n"
			  <<	"</font>";	
					
	*mpStream <<	"</td>"
					"</tr>";

	//row for duration
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\"><b>Duration</b>"
					"<font size=\"2\" color=\"#006600\"><br>required</font>"
					"<br>"
					"</td>"
					"<td width=\"75%\">"
					"<br>";

	// for a limited time, offer 14 day auctions
	if ((clsUtilities::CompareTimeToGivenDate(time(0), 12, 16, 98, 0, 0, 0) >= 0) &&
		(clsUtilities::CompareTimeToGivenDate(time(0), 12, 31, 98, 23, 59, 59) <= 0))
	{
		if (itemNo)
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3"
					  <<	(((days >= 3) && (days < 5)) ? " selected>3\n" : ">3\n")
					  <<	"<option value=5"
					  <<	(((days >= 5) && (days < 7)) ? " selected>5\n" : ">5\n")
					  <<	"<option value=7"
					  <<	(((days < 3) || ((days >= 7) && (days < 10))) ? " selected>7\n" : ">7\n")
					  <<	"<option value=10"
					  <<	(((days >= 10) && (days < 14)) ? " selected>10\n" : ">10\n")
					  <<	"<option value=14"
					  <<	(((days >= 14)) ? " selected>14\n" : ">14\n")
					  <<	"</select>\n";
		}
		else
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3>3\n"
							"<option value=5>5\n"
							"<option value=7 selected>7\n"
							"<option value=10>10\n"	
							"<option value=14>14\n"			// added by Alex 12/17/98
							"</select>\n";
		}

		*mpStream <<	"<font size=\"2\">days</font>\n"	 
						"</td></tr>";
	}
	else	// no more 14-day auctions
	{
		if (itemNo)
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3"
					  <<	(((days >= 3) && (days < 5)) ? " selected>3\n" : ">3\n")
					  <<	"<option value=5"
					  <<	(((days >= 5) && (days < 7)) ? " selected>5\n" : ">5\n")
					  <<	"<option value=7"
					  <<	(((days < 3) || ((days >= 7) && (days < 10))) ? " selected>7\n" : ">7\n")
					  <<	"<option value=10"
					  <<	(((days >= 10)) ? " selected>10\n" : ">10\n")
					  <<	"</select>\n";
		}
		else
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3>3\n"
							"<option value=5>5\n"
							"<option value=7 selected>7\n"
							"<option value=10>10\n"	
							"</select>\n";
		}

		*mpStream <<	"<font size=\"2\">days</font>\n"
						"</td></tr>";

	}
	*mpStream <<	"</table>";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";

	// --------------------------------------------------------------------------------------
	// Table for reserve and private
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n";


	// reserve price?
	*mpStream <<	"<tr>"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<font size=\"3\">"
					"<b>Reserve Price</b>"
					"</font>"
					// nsacco 07/15/99 show currency symbol
					"<br><font size=\"2\">(in "
			  <<	theCurrency->GetSymbol()
			  <<	")</font>"
					"<br><font size=\"2\" color=\"#990000\">optional</font>"
					"</font></td>"
					"<td width=\"75%\">"
					"<input type=\"text\" name=\"reserve\" ";

	if ((itemNo) && (mpItem->GetReservePrice() != 0))
	{
		*mpStream <<	"value=\""
				  <<	"update needed"
				  <<	"\" ";
	}
	
	*mpStream <<	"size=" << (maxSize + 2)<< " "
					"maxlength=" << maxSize << ">";

	if ((itemNo) && (mpItem->GetReservePrice() !=0))
	{
		*mpStream <<	"<font size=\"2\">"
						"&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To look up your previous reserve, please check "
						"<a href=\""
				 <<		mpMarketPlace->GetHTMLPath()
				 <<		"services/myebay/myebay.html\">My eBay</a>.</font>";
	}

	*mpStream <<	" <font size=\"2\"><a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Reserve\">see tips</a>"
			  <<	"<br>"
					"(e.g., 15.00 -- Please do not include commas or currency "
					// TODO - should this use GetSymbol()?
					"symbols, such as $.)\n"
			  <<	"</font>";	


	*mpStream <<	"</td></tr>\n";

	// Row for private
	*mpStream <<	"<tr>\n"
					"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<br>"
					"<font size=\"3\">"
					"<b>Private Auction?</b>"
					"<font size=\"2\" color=\"#990000\"><br>optional</font>"
					"</font></td>"
					"<td width=\"75%\">"
					"<br>"
					"<input type=checkbox name=private";
	if (itemNo && mpItem->GetPrivate())
	{
		*mpStream <<	" checked";
	}
	*mpStream <<	">"
					"<font size=\"2\">"
					"&nbsp;&nbsp;Please don't use this unless you have a "
					"specific reason.<br>";

	*mpStream <<	" <a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#Private\">learn more</a>"
			  <<	"</font>";					

	*mpStream <<	"</td>\n"
					"</tr>\n";


	*mpStream <<	"</table>";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";

	// --------------------------------------------------------------------------------------
	// Table for userid and password
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<br><table border=\"1\" width=\"600\" cellpadding=\"3\" cellspacing=\"0\">\n"
					"<tr>";

	// Row for userid / password
	*mpStream <<	"<td width=\"25%\" align=\"left\" valign=\"top\" bgcolor=\"#efefef\">"
					"<b><font size=\"3\">"
					"UserID / Password</b><br><font size=\"2\" color=\"#006600\">required</font></font></td>"
					"<td width=\"75%\"><table border=\"0\" cellpadding=\"0\" "
					"cellspacing=\"0\" width=\"100%\">"
					"<tr>";

	// if relisting, make the userid non-editable
	if (itemNo)
	{
		*mpStream <<	"<td width=\"50%\" valign=\"top\">"
						"<input type=\"hidden\" name=\"userid\" ";

		*mpStream <<	"value=\""
				  <<	mpItem->GetSellerUserId()
				  <<	"\" ";	
		
		*mpStream <<	">"
				  <<	mpItem->GetSellerUserId()
				  <<	"<br>"
						"<font size=\"2\"><b>"
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	"</b> or E-mail address</font></td>";
	}
	else
	{
		*mpStream <<	"<td width=\"50%\" valign=\"top\">"
						"<input type=\"text\" name=\"userid\" ";
						
		*mpStream <<	"size=\"24\" "
				  <<	"maxlength="
				  <<	EBAY_MAX_USERID_SIZE
				  <<	">"
						"<br>"
						"<font size=\"2\"><b>"
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	"</b> or E-mail address</font></td>";
	}

	// password box
	*mpStream <<	"<td width=\"50%\" valign=\"top\">"
					"<input type=\"password\" name=\"pass\" size=\"20\" "
					"maxlength=" << EBAY_MAX_PASSWORD_SIZE << ">"
					"<br>"
					"<font size=\"2\"><b>"
					"Password</b> ("
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html\">forgotten</a>"
					" it?)</font></td>"
					"</tr>"
					"</table>";
					"</td>"
					"</tr></table>\n";

	*mpStream <<	"\n</td></tr></table>\n";

	// end green bordered table			
	//*mpStream <<	"\n</td></tr></table>\n";

	// --------------------------------------------------------------------------------------

	//spacer
	*mpStream <<	"<p><br></p>";

	// --------------------------------------------------------------------------------------
	// Table for legal text and buttons
	// --------------------------------------------------------------------------------------
	*mpStream <<	"<table border=\"0\" cellpadding=\"0\" width=\"590\">\n";
					
	// Fees stuff
	*mpStream <<	"<tr>\n"
					"<td><font size=\"3\">\n"
					"<p>"
					"Press the &quot;review&quot; button below to see what fees are due immediately "
					"and what fees may be due if your item sells. You will not incur any "
					"fees until you accept the terms disclosed in the next screen.</p>";
	// Row for Review
	*mpStream <<	"<p><b>Press "
					"<img src="
					"\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"dot_clear.gif"
					"\" "
					"width=1 vspace=6 border=0 ALT="">"
			  <<	"<input type=submit value=\"review\">"
					" to review and place your listing.</b></p>";	

	// Row for Clear
	*mpStream <<	"<P><br>Press "
					"<input type=\"reset\" value=\"clear form\" name=\"reset\">"
			  <<	" to clear the form and start over.</p>";

	// Row for Back-button blanking help
	*mpStream <<	"<P><br><font size=2><b>Note:</b>"
					" If the Back button on your browser erases"
			  <<	" your information on this form, "
			  <<	"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"sell/help.html#BackButton\">find out</a>"
			  <<	" how to fix this.</font></p>";

	*mpStream <<	"</font></td>\n"
					"</tr>\n";	

	// End table for last  fields
	*mpStream <<	"</table>\n";

	// pass the orginal item # if any
	if (itemNo)
	{
		*mpStream << "<input type=\"hidden\" name=\"olditem\" value=\""
				  << pItemNo
				  << "\">";

		// crypt the old number
		sprintf(cSalt, "%d", mpItem->GetSeller() + itemNo + 3);
		pCryptedItemNo = crypt(pItemNo, cSalt);

		*mpStream << "<input type=\"hidden\" name=\"oldkey\" value=\""
				  << pCryptedItemNo
				  << "\">";

		free(pCryptedItemNo);
	}

	// nsacco 07/14/99 removed old currency_GBP code here

	// nsacco 07/27/99 hidden fields for siteid, language
	// set the siteid
	*mpStream << "<input type=\"hidden\" name=\"siteid\" value=\""
			  << mpMarketPlace->GetCurrentSiteId()
			  << "\" >";
	// set the description language
	
	switch (mpMarketPlace->GetCurrentSiteId())
	{
	case SITE_EBAY_DE:
		theDescLang = German;
		break; 
	case SITE_EBAY_UK:
	case SITE_EBAY_AU:
	case SITE_EBAY_CA:
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		theDescLang = English;
	}
				
	*mpStream << "<input type=\"hidden\" name=\"language\" value=\""
			  << theDescLang
			  << "\" >";
	// end new hidden fields

	// End form
	*mpStream <<	"\n</form>";

	*mpStream <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	delete (char *) pSafeDescription;
	CleanUp();

	return;

}

