/*	$Id: clseBayAppNewItem.cpp,v 1.18.2.8.14.1 1999/08/01 03:01:19 barry Exp $	*/
//
//	File:		clseBayAppNewItem.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)/yp
//
//	Function:	clseBayApp::NewItem
//
//
//	Modifications:
//				- 06/14/97 michael/yp	- Created
//				- 09/16/97 poon	- modified layout
//				- 04/12/99 soc  - added 10 and 14 day auctions
//              - 04/14/99 soc  - comment out 14 day auctions for now
//				- 05/06/99 nsacco - changed strings for shipping
//				- 06/09/99 nsacco - Australia and intl sites
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()

#include "ebihdr.h"

// NOTE: This page is now redirected to ListItemForSale

// TODO - nsacco - remove this file, it is unused

extern "C" char *crypt(char *pPassword, char *pSalt);

void clseBayApp::NewItem(CEBayISAPIExtension *pServer, char *pItemNo, char *pCatNo)
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

	// Used to set the page's expiration to now + 5 minutes
	int			rc;
	time_t		nowTime;
	time_t		expirationTime;
	struct tm	*pExpirationTimeAsTM;
	char		expiresHeader[128];

	bool		verbose=true;
	int			maxSize;

	ScrollingSelection *pAllCountries;

	int currencyId;
	int countryId;

	int			giftIconType;
	char		pGiftIcon[4];

	// Setup
	SetUp();	

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
		// Assume US for now until we have user preferences.
		currencyId = Currency_USD;
		countryId  = Country_US;
	}

	clsFees		objFees(currencyId);
	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // set below.
				// for now, all fees are in US dollars.

	// Let's try and get the category
	if (catNo) 
		pCategory = mpCategories->GetCategory(catNo, true);

	// Punt if a category was actually passed in, but we couldn't get it from the db
	if (catNo && !pCategory)
	{
		CleanUp();
		return;
	}

	// Calculate length of auction
	if (itemNo) days = (mpItem->GetEndTime() - mpItem->GetStartTime()) / 86400;

	maxSize = mpMarketPlace->GetMaxAmountSize(currencyId);

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
			  <<	" Sell Item";

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

	// Spacer
	*mpStream <<	"<br>";

	// Title using black on darkgrey table
	*mpStream <<	"<table border=\"1\" cellspacing=\"0\" width=\"590\" "
					"bgcolor=\"#99CCCC\">"
					"<tr>"
					"<td align=\"center\" width=\"100%\">"
					"<font size=\"5\" color=\"#000000\">"
					"<b>";
	*mpStream <<	(itemNo ? "Relist Your Item" : "Sell Your Item");
	*mpStream <<	"</b></font></td></tr>";
	
	if (pCategory)	// show which category if it was specified
	{
		*mpStream <<	"<tr>"
						"<td align=\"center\" width=\"100%\">"
				  <<	"<font size=\"4\">";
				 
		if (strlen(pCategory->GetName4())) *mpStream <<	pCategory->GetName4() << ":";
		if (strlen(pCategory->GetName3())) *mpStream <<	pCategory->GetName3() << ":";
		if (strlen(pCategory->GetName2())) *mpStream <<	pCategory->GetName2() << ":";
		if (strlen(pCategory->GetName1())) *mpStream <<	pCategory->GetName1() << ":";

		*mpStream <<	pCategory->GetName()		// actual
				  <<	"</font></td></tr>";
	}


	*mpStream <<	"</table>"
					"\n";
	
	// "Please be familiar..."
	*mpStream <<	"<table border=\"0\" width=\"590\">"
					"<tr>"
					"<td>";
					
	*mpStream <<	"Please refer to our "
					"<a href=\"";
	*mpStream <<	mpMarketPlace->GetHTMLPath();
	*mpStream <<	"help/community/index.html\">";
	*mpStream <<	" Community Standards</a>.\n";
					
	*mpStream <<    "<p>";
	*mpStream <<    "eBay is committed to guaranteeing your satisfaction as a valued user.";		  
	*mpStream <<    " <a href=\"";
	*mpStream <<    mpMarketPlace->GetHTMLPath();
	*mpStream <<    "help/basics/f-faq.html#29\">"
		      <<    "Find out</a> "
			  <<    "how you can relist your item at possibly no extra charge if your item does not sell.";
	
	// "If you're not sure what to put in the fields..."
	*mpStream <<	"<p>Hint: Use the "
					"<a href="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageNewItem)
			  <<	"eBayISAPI.dll?NewItemQuick";

	if (itemNo)
	{
		*mpStream <<	"&item="
				  <<	itemNo;
	}
	if (catNo)
	{
		*mpStream <<	"&category="
				  <<	catNo;
	}

	*mpStream <<	"\""
					">"
					"quick entry form.</a>"
					" if you are already familiar to this process.";

	// Spacer
	*mpStream <<	"</td>"
					"</tr>"
					"</table>";

	// Begin the form					
	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageVerifyNewItem)
			  <<	"eBayISAPI.dll?VerifyNewItem"
			  <<	"\""
					">\n";

	// Make a table to hold the first five fields
	*mpStream <<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>";

	// Row for email or userid
	*mpStream <<	"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<strong><font size=\"3\" color=\"#006600\">"
					"UserID / Password</font></strong></td>"
					"<td width=\"430\"><table border=\"0\" cellpadding=\"0\" "
					"cellspacing=\"0\" width=\"100%\">"
					"<tr>"
					"<td width=\"50%\" valign=\"top\">"
					"<input type=\"text\" name=\"userid\" ";

	if (itemNo)
	{
	*mpStream <<	"value=\""
			  <<	mpItem->GetSellerUserId()
			  <<	"\" ";
	}							
					
	*mpStream <<	"size=\"24\" "
			  <<	"maxlength="
			  <<	EBAY_MAX_USERID_SIZE
			  <<	">"
					"<br>"
					"<font size=\"2\"><strong>"
			  <<	mpMarketPlace->GetLoginPrompt()
			  <<	"</strong> or E-mail address</font></td>"
					"<td width=\"50%\" valign=\"top\">"
					"<input type=\"password\" name=\"pass\" size=\"20\" "
					"maxlength=" << EBAY_MAX_PASSWORD_SIZE << ">"
					"<br>"
					"<font size=\"2\">Password ("
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/buyandsell/reqpass.html\">forgotten</a>"
					" it?)</font></td>"
					"</tr>"
					"</table>"
					"</td>"
					"</tr>\n";


	// Row for title
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\"><font color=\"#006600\">"
					"<strong>Title</strong></font>"
					"</font><font size=\"2\"> (no HTML)</font></td>"
					"<td width=\"430\">"
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
					"<font size=\"2\""
					"color=\"#006600\"> (required)</font><br>"
					"<i><font size=\"2\">e.g.: Rare collection of 100 beanie babies</font></i>";
	if (verbose)
	{
		*mpStream <<	"<p><font size=\"2\">One line (45 characters maximum); no HTML tags, please. "
						"Neatness counts. Please don't use all caps, lots of &quot;!!!&quot;, "
						"etc. We reserve the right to clean up titles we find ugly. [Tip: Avoid "
						"using asterisks, plus signs, and other special characters in your item "
						"title. Our search feature may overlook words that contain these characters "
						"-- and that can make it harder for bidders to find your item.]</font><br>";
	}

	*mpStream <<	"</td></tr>";
				

	// Row for location
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>Item location</strong></font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"location\" ";

	if (itemNo)
	{
		*mpStream <<	"value=\""
				  <<	mpItem->GetLocation()
				  <<	"\" ";
	}
	*mpStream <<	"size=" << EBAY_MAX_LOCATION_SIZE << " "
					"maxlength=" << EBAY_MAX_LOCATION_SIZE << ">"
			  <<	"<font size=\"2\" color=\"#006600\"> (required)</font><br>";

// nsacco
	// TODO - should this use site? Yes but the file is not used anymore
	switch (countryId)
	{
	case Country_UK:
		*mpStream <<	"<i><font size=\"2\">e.g.: Cambridge</font></i>";
		break;
	case Country_CA:
		*mpStream <<	"<i><font size=\"2\">e.g.: Toronto, ON</font></i>";
		break;
	case Country_AU:
		*mpStream <<	"<i><font size=\"2\">e.g.: Sydney, NSW</font></i>";
		break;
	case Country_US:
	default:
		*mpStream <<	"<i><font size=\"2\">e.g.: San Jose, California</font></i>";
		break;
	}

	if (verbose)
	{
		*mpStream <<	"<p><font size=\"2\">Please specify the geographic location of the item(s) "
						"you are offering. This gives the potential bidder an idea of shipping "
						"costs, etc.</font><br>";
	}

	*mpStream <<	"</td>"
					"</tr>";

	// Row for zip
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>Item location zip code</strong></font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"zip\" ";

	if (itemNo)
	{
		*mpStream <<	"value=\"";

		if (mpItem->GetZip())
			*mpStream <<	mpItem->GetZip();
		*mpStream  <<	"\" ";
	}
	*mpStream <<	"size=" << EBAY_MAX_ZIP_SIZE << " "
					"maxlength=" << EBAY_MAX_ZIP_SIZE << ">"
			  <<	"<font size=\"2\" color=\"#006600\"> (required for items in USA)</font><br>";

	// nsacco
	// TODO - should this use site? yes but this file is not used anymore
	switch (countryId)
	{
	case Country_UK:
		*mpStream <<	"<i><font size=\"2\">e.g.: CB4 4WS</font></i>";
		break;
	case Country_AU:
		*mpStream <<	"<i><font size=\"2\">e.g.: 2000</font></i>";
		break;
	case Country_CA:
		*mpStream <<	"<i><font size=\"2\">e.g.: M5G 2C4</font></i>";
		break;
	case Country_US:
	default:
		*mpStream <<	"<i><font size=\"2\">e.g.: 95125</font></i>";
		break;
	}

	if (verbose)
	{
		*mpStream <<	"<p><font size=\"2\">Please specify the location zip code of the item(s) "
						"you are offering. ";
						"Your zip code will be used to improve the visibility of your item(s) "
						"in upcoming regional search and listing features. "
						"It will not be displayed to people viewing your item(s).</font><br>";
	}

	*mpStream <<	"</td>"
					"</tr>";

	//row for country
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\" color=\"#006600\">"
					"<strong>Country where the item is located</strong></font></td>"
					"<td width=\"430\">";

	if (currencyId == Currency_GBP)
	{
		// The user can only list this in the UK.
		*mpStream <<  "<b>United Kingdom</b>";
	}

	// PH added 04/26/99 (this is gonna get ugly..) >>
	else {
		if (currencyId == Currency_DEM) 
		{
			// The user can only list this in Germany	
			*mpStream << "<b>Germany</b>";
		}
		// <<
	
		else
		{

			clsCountries *pCountries = mpMarketPlace->GetCountries();
	
			if (pCountries)
			{
				pAllCountries = new ScrollingSelection[pCountries->GetNumCountries() + 1];
			
				pCountries->FillScrollingSelection(pAllCountries);

				// use USA as default
				EmitScrollingList(mpStream,
					              "countryid",
								  1,
								  pAllCountries,					  
								  Country_US,
								  true);

				delete [] pAllCountries;
			}

			*mpStream << "<font size=\"2\" color=\"#006600\"> (required)</font>\n";
		}
	}

	*mpStream <<	"</td>"
					"</tr>";

					
	// End first table
	*mpStream <<	"</table>\n";
	
	// show full category selector if category wasn't specifed
	if (!pCategory)
	{
		// Begin category table
		*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
						"<tr>"
						"<td width=\"100%\" bgcolor=\"#EFEFEF\">"
						"<strong><font size=\"3\" color=\"#006600\">Category</font></strong>";

		*mpStream <<	"<font size=\"2\">&nbsp; (choose <b>one category</b> only)"
						"<font color=\"#006600\">&nbsp; "
						"</font></font><font size=\"2\" color=\"#006600\">(required)</font>";

		if (verbose)
		{
			*mpStream <<	"<br>"
							"<font size=\"2\">Try to choose the <b>most specific category</b>, "
							"especially for computer-related items</font>";
		}

		*mpStream <<	"<font size=\"2\">"
						"<BR>"
					    "<b> Fees for items within Vehicles and Real Estate categories "
						"were changed <font color=\"red\"> on April 24, 1999 </font>."
						"These changes will limit the total fees charged to sellers for items "
						"listed in these categories, regardless of the "
						"selling price of the item. Many sellers will find that this new pricing "
						"structure provides lower total fees for items sold in these categories.<br>"
						"See "					
						"<a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/selling-fees.html\">"
						" eBay fees</A>"
						" for details.</b></font>";
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
	else
	{
		// Begin category table
		*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
						"<tr>"
						"<td width=\"160\" bgcolor=\"#EFEFEF\">"
						"<strong><font size=\"3\" color=\"#006600\">Category</font></strong></td>";

		// ok, category was specified, so just reinforce to user which one it is
		*mpStream <<	"<td width=\"430\"><font size=\"2\"> &nbsp;";

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

  
	// Make a table for description and picture url -- 3rd table
//	*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
//					"<tr>"
 //					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\""
//					"><font size=\"3\" color=\"#006600\">"
//					"<strong>Description</strong></font>"
//					"<font size=\"2\"> (HTML&nbsp;ok)</font></td>"
//					"<td width=\"430\">"
//					"<textarea name=\"desc\" cols=\"56\" rows=\"8\">";
//  Lena - Ts and Cs

	*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">\n"
					"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\" color=\"#006600\">"
					"<strong>Accepted Payment Methods</strong></font><font size=\"2\" "
					"color=\"#006600\"> (choose as many as apply) "
					"</font>&nbsp;</td>\n"
					"<td width=\"430\">\n"
					"<table cellpadding=0 cellspacing=0>\n"
					"<tr valign=\"top\">\n";
	if (itemNo && (mpItem->AcceptsPaymentMOCashiers()))
		*mpStream <<	"<td align = \"left\">"
						"<input type=\"checkbox\" name=\"moneyOrderAccepted\" checked value=\"on\"> </td>\n"
						"<td width=170> <font size=\"2\"> Money Order/Cashiers Check</font>" 
						"</td>\n";
	else
		*mpStream <<	"<td align = \"left\">"
						"<input type=\"checkbox\" name=\"moneyOrderAccepted\" value=\"on\"> </td>\n"
						"<td width=170> <font size=\"2\"> Money Order/Cashiers Check</font>" 
						"</td>\n";
	if ((itemNo) && (mpItem->AcceptsPaymentPersonalCheck()))
		*mpStream <<	"<td align = \"right\">"
						"<input type=\"checkbox\" name=\"personalChecksAccepted\" checked "
						"value=\"on\"> </td>\n"
						"<td width=105> <font size=\"2\">Personal Check </font>"
						"</td>\n";			
	else
		*mpStream <<	"<td align = \"right\">"
						"<input type=\"checkbox\" name=\"personalChecksAccepted\" "
						"value=\"on\"> </td>\n"
						"<td width=105> <font size=\"2\">Personal Check </font>"
						"</td>\n";			
	if ((itemNo) && (mpItem->AcceptsPaymentVisaMaster()))
		*mpStream <<	"<td align = \"right\">" 
						"<input type=\"checkbox\" name=\"visaMasterCardAccepted\" checked value=\"on\"> </td>\n"
						"<td align = \"left\"><font size=\"2\">Visa/MasterCard </font>"
						"</td></tr>\n";	
	else
		*mpStream <<	"<td align = \"right\">" 
						"<input type=\"checkbox\" name=\"visaMasterCardAccepted\" value=\"on\"> </td>\n"
						"<td align = \"left\"><font size=\"2\">Visa/MasterCard </font>"
						"</td></tr>\n";	
	if ((itemNo) && (mpItem->AcceptsPaymentCOD()))
		*mpStream <<	"<tr valign=\"middle\"><td>"
						"<input type=\"checkbox\" name=\"paymentCOD\" checked value=\"on\"> </td>\n"
						"<td><font size=\"2\">COD (collect on delivery) </font>"
						"</td>\n";
	else
		*mpStream <<	"<tr valign=\"middle\"><td>\n"
						"<input type=\"checkbox\" name=\"paymentCOD\" value=\"on\"> </td>\n"
						"<td><font size=\"2\">COD (collect on delivery) </font>"
						"</td>\n";
	if ((itemNo) && (mpItem->AcceptsPaymentEscrow()))
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"onlineEscrow\" checked value=\"on\"></td>\n"
						"<td>"
						"<font size=\"2\">On-line Escrow</font></td>\n";
	else
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"onlineEscrow\" value=\"on\"></td>\n"
						"<td>"
						"<font size=\"2\">On-line Escrow</font></td>\n";

	if ((itemNo) && (mpItem->AcceptsPaymentAmEx()))
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"amExAccepted\" checked value=\"on\"></td>\n"
						"<td>"
						"<font size=\"2\"> American Express</font></td>\n"
						"</tr>";
	else
	    *mpStream <<    "<td><input type=\"checkbox\" name=\"amExAccepted\" value=\"on\"></td>\n"
						"<td>"
						"<font size=\"2\"> American Express</font></td>\n"
						"</tr>";
	if ((itemNo) && (mpItem->MorePaymentSeeDescription()))
		*mpStream <<	"<tr valign=\"middle\">"
						"<td><input type=\"checkbox\" name=\"paymentSeeDescription\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> See Item Description</font> </td>\n";
	else
		*mpStream <<	"<tr valign=\"middle\">"
						"<td><input type=\"checkbox\" name=\"paymentSeeDescription\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> See Item Description</font> </td>\n";
	if ((itemNo) && (mpItem->AcceptsPaymentOther()))
		*mpStream <<	"<td><input type=\"checkbox\" name=\"otherAccepted\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> Other </font> </td>";
	else
		*mpStream <<	"<td><input type=\"checkbox\" name=\"otherAccepted\" value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> Other </font> </td>";
	if ((itemNo) && (mpItem->AcceptsPaymentDiscover()))
		*mpStream <<	"<td><input type=\"checkbox\" name=\"discoverAccepted\" checked value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> Discover </font> </td>\n"
						"</tr>";
	else
		*mpStream <<	"<td><input type=\"checkbox\" name=\"discoverAccepted\" value=\"on\"> </td>\n"
						"<td>"
						"<font size=\"2\"> Discover </font> </td>\n"
						"</tr>\n";
	*mpStream <<	"</table>\n"
					"</td>\n"
					"</tr>\n";

	*mpStream <<    "<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\" color=\"#006600\">"
					"<strong>Payment Terms</strong></font></td>\n";
	*mpStream <<	"<td width=\"430\">\n"
					"<table cellpadding=0 cellspacing=0 width=\"100%\">\n"
					"<tr valign=\"middle\">\n";
	if ((itemNo) && (mpItem->SellerPaysForShipping()))
		*mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"sellerPaysShipping\" checked value=\"on\">"
						"<font size=\"2\"> Seller Pays &nbsp;&nbsp;</font>" 
						"</td>\n";
	else
		*mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"sellerPaysShipping\" value=\"on\">"
						"<font size=\"2\"> Seller Pays &nbsp;&nbsp;</font>" 
						"</td>\n";
	if ((itemNo) && (mpItem->BuyerPaysForShippingFixed()))
	  *mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"buyerPaysShippingFixed\" checked value=\"on\">"
					"<font size=\"2\"> Buyer Pays Fixed Amount&nbsp;</font>" 
					"</td>\n"
					"</tr>\n";
	else
		*mpStream <<	"<td width=\"50%\"> <input type=\"checkbox\" name=\"buyerPaysShippingFixed\" value=\"on\">"
					"<font size=\"2\"> Buyer Pays Fixed Amount&nbsp;</font>" 
					"</td>\n"
					"</tr>\n";
	if ((itemNo) && (mpItem->BuyerPaysForShippingActual()))
		*mpStream <<	"<tr valign=\"middle\">\n"
						"<td> <input type=\"checkbox\" name=\"buyerPaysShippingActual\" checked value=\"on\">"
						"<font size=\"2\"> Buyer Pays Actual Shipping Cost&nbsp;</font>" 
						"</td>\n";
	else
	  *mpStream <<	"<tr valign=\"middle\">"
						"<td> <input type=\"checkbox\" name=\"buyerPaysShippingActual\" value=\"on\">"
						"<font size=\"2\"> Buyer Pays Actual Shipping Cost&nbsp;</font>" 
						"</td>\n";

	if ((itemNo) && (mpItem->MoreShippingSeeDescription()))
		*mpStream <<	"<td> <input type=\"checkbox\" name=\"shippingSeeDescription\" checked value=\"on\">"
						"<font size=\"2\"> See Item Description</font>"
						"</td>\n";
	else
		*mpStream <<	"<td> <input type=\"checkbox\" name=\"shippingSeeDescription\" checked value=\"on\">"
						"<font size=\"2\"> See Item Description</font>"
						"</td>\n";
						
	*mpStream <<	"</tr>\n"
						"</table>\n"
						"</td>\n"
						"</tr>\n"
						"<tr>\n"
						"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\" color=\"#006600\">"
						"<strong>Shipping Terms</strong></font> </td>\n"
						"<td width=\"430\">\n"
						"<table cellpadding=0 cellspacing=0 width=\"100%\">\n"
						"<tr valign=\"middle\">\n";

	if (currencyId == Currency_USD)
	{
		if ((itemNo) && (mpItem->IsShippingInternationally()))
			*mpStream <<	"<td width=\"50%\"> <input type=\"radio\" name=\"shippingInternationally\" value=\"off\">"
							// nsacco, changed string, 05/06/99
							"<font size=\"2\"> Ship Within Home Country Only</font></td>\n"
							"<td width=\"50%\"> <input type=\"radio\" name=\"shippingInternationally\" checked value=\"on\">"
							"<font size=\"2\"> Will Ship Internationally</font></td>\n";
		else
			*mpStream <<	"<td width=\"50%\"> <input type=\"radio\" name=\"shippingInternationally\" checked value=\"off\">"
							// nsacco, changed string, 05/06/99
							"<font size=\"2\"> Ship Within Home Country Only</font></td>\n"
							"<td width=\"50%\"> <input type=\"radio\" name=\"shippingInternationally\" value=\"on\">"
							"<font size=\"2\"> Will Ship Internationally</font></td>\n";

		*mpStream <<		"</tr>\n"
							"</table>\n"
							"<P><FONT SIZE=\"-1\">This information will indicate to users that "
							"they can bid on your item even if they live outside of your home "
							"country.  Please note that most international buyers expect to "
							"send $US Dollars.</FONT></P>\n"
							"</td>\n";
	}
	else
	{
		*mpStream << "<td><font size=2 halign=\"center\">To <a href=\""
				  << mpMarketPlace->GetHTMLPath()
				  << "newitem.html\">ship internationally</a>, you must sell "
				  << "your item in US$</center></p></td>";
	}

// <!-- lena end -->

	*mpStream <<	"</tr>\n"
					"<tr>"
 					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\""
					"><font size=\"3\" color=\"#006600\">"
					"<strong>Description</strong></font>"
					"<font size=\"2\"> (HTML&nbsp;ok)</font></td>"
					"<td width=\"430\">"
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


	*mpStream <<	"</textarea><font size=\"2\" color=\"#006600\"> (required)</font>";

	if (verbose)
	{
		*mpStream <<	"<p><font size=\"2\">You may use HTML tags. Please, keep it "
						"neat! You'll have a chance to review how it looks before submitting your listing. Feel "
						"free to include links to your own graphics. Also, it is recommended that you include "
						"information about <b>who should pay for shipping;</b> this is a very common question. "
						"Also, don't use <b>quotes</b> in your HTML, since this will break your code. </font></p>"
						"<p><font size=\"2\">You can add an image to your description text by including the following "
						"HTML:<br>"
						"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;img src=http://www.anywhere.com/mypicture.jpg &gt;<br>"
						"(Of course, you should replace http://www.anywhere.com/mypicture.jpg with your image URL, "
						"and include the &lt; and &gt; characters). </font>";
	}

	*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">Picture URL</font></td>"
					"<td width=\"430\" valign=\"top\">"
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
			  <<	"<font size=\"3\">"
					"<img src=\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"pic.gif\" alt=\"[PIC!]\"></font>"
					"<font size=\"2\" color=\"#800000\"> (optional)</font>";

	if (verbose)
	{

		*mpStream <<	"<p><font size=\"2\">If you have a URL to a picture, you may "
						"enter the URL here. By entering it here, the </font>"
						"<img src=\""
			  <<		 mpMarketPlace->GetImagePath()
			  <<		"pic.gif\" alt=\"[PIC!]\"><font size=\"2\"> icon "
						"will appear next to your item in the listings.\n\n"
						"<p>Please click here if you want to view the <A HREF=\""
			  <<		mpMarketPlace->GetHTMLPath()
			  <<		"phototut-index.html\">eBay photo tutorial</A>.<br></font>\n";
					
/*					<p><font size=\"2\">If "
						"you have a URL to a <strong>picture</strong> you would like to include in, but did not "
						"enter HTML code for it in the description above, you may enter the URL here. By entering "
						"it here, the </font><img src=\""
			  <<		 mpMarketPlace->GetImagePath()
			  <<		"pic.gif\" "
						"alt=\"[PIC!]\"><font size=\"2\"> icon will appear next to your item in the listings. "
						"Note that you must enter a complete URL to a picture on your own Web site.<br> "
						"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you'd like more information on including pictures with "
						"your listings, two <i>excellent</i> tutorials on the subject can be found at <a "
						"HREF=\"http://www.pongo.com/tutorials/aweb-images/\">http://www.pongo.com/tutorials/aweb-images/</a> "
						"and <a HREF=\"http://www.twaze.com/aolpix/\">http://www.twaze.com/aolpix/</a>. (These "
						"tutorials are maintained by eBay Community members, and eBay is not responsible for their "
						"content or accuracy). <br>"
						"</font>";
*/
	}
	
	
	*mpStream <<	"</td>"
					"</tr>\n";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">Quantity </font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"quant\" ";
	if (itemNo)
	{
	*mpStream <<	"value=\""
			  <<	mpItem->GetQuantity()
			  <<	"\" ";
	}
	*mpStream <<	"size=\"6\" "
			  <<	"maxlength=" << EBAY_MAX_QUANTITY_SIZE << ">"
			  <<	"<font size=\"2\">&nbsp;&nbsp;(type numerals only)&nbsp; "
					"</font><font size=\"2\" color=\"#800000\">(optional)</font>";

	if (verbose)
	{

		*mpStream <<	"<p><font "
						"size=\"2\">For "
						"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#dutch\">Dutch auctions</a>, enter "
						"the number of identical items you are offering for sale at this price. Otherwise, leave it "
						"as 1. Remember, if you are selling three items as one set to one buyer, then your quantity "
						"should be one, not three. <b>Multiple listings for identical items are not permitted, so "
						"you must use this option if you are selling multiple identical items.</b>. Please enter "
						"only numerals in this field. Omit commas (',').<br>"
						"</font>";
	}

	// Temporary text about new Dutch Auction rules
	*mpStream <<		"<br><font size=2><font color=red>Note: </font><a href=\""
			  <<		mpMarketPlace->GetHTMLPath()
			  <<		"help/sellerguide/new-dutch-rules.html\">"
						"Read</a>"
						" about the new requirements for Dutch Auctions."
						"<br>Categories with fixed fees do not allow Dutch Auctions. "
						"See a <a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/fixed.html\">"
						"list</a>."
						"</font>";

	*mpStream <<	"</td>"
					"</tr>\n";

	//row for Minimum bid
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/basics/g-minimum-bid.html\">"
					"<font size=\"3\">Minimum bid</font></a>"
					"</td>"
					"<td width=\"430\" valign=\"top\">"
					"<input type=\"text\" name=\"startprice\" ";
	if (itemNo)
	{
		*mpStream <<	"value=\""
		<<	mpItem->GetStartPrice()
		<<	"\" ";
	}

	*mpStream <<	"size=" << maxSize << " "
					"maxlength=" << maxSize << ">"
					"<font size=\"2\"> per item&nbsp;&nbsp;"
					"(numerals and decimal point '.' only) </font>"
					"<font size=\"2\" color=\"#006600\">&nbsp; (required)<br>"
					"</font><i><font size=\"2\">e.g.: 2.00</font></i>";

	if (verbose)
	{

		*mpStream <<	"<p><font size=\"2\">This is the price at which the auction will start, and "
						"is, generally, the lowest price at which you are willing to sell your "
						"item. Setting this too high may discourage bidding! Omit ";
		
		// PH added 04/26/99 >>
		// we really want to get this stuff translated, and not have
		// a zillion if-else in the code. So let's do this from now on.
		// nsacco 06/03/99 rewrote as a switch
		switch (currencyId)
		{
			case Currency_GBP:
				*mpStream << "pound signs ('&pound;') and commas (',')<br></font>";
				break;
			case Currency_USD:
				*mpStream << "dollar signs ('$') and commas (',')<br></font>";
				break;
			default:
				*mpStream << "dollar signs ('$') and commas (',')<br></font>";
				break;
		}
	}

	*mpStream <<	"</td>"
					"</tr>";

	//row for duration

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<strong><font size=\"3\" "
					"color=\"#006600\">Duration</font></strong></td>"
					"<td width=\"430\">";

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
					  <<	(((days < 3) || ((days >= 7) && (days < 14))) ? " selected>7\n" : ">7\n")
					  <<	"<option value=14"								// added by Alex 12/17/98
					  <<	((days >= 14) ? " selected>14\n" : ">14\n")		// added by Alex 12/17/98
					  <<	"</select>\n";		
		}
		else
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3>3\n"
							"<option value=5>5\n"
							"<option value=7 selected>7\n"
							"<option value=14>14\n"			// added by Alex 12/17/98
							"</select>\n";
		}

		*mpStream <<	"<font size=\"2\"> days&nbsp; "
						"</font><font size=\"2\" color=\"#006600\">(required)</font>"
						"<br><br><font size=2>For a limited time! Use the 14-day auction to sell to the post-holiday crowds!</font><br>"	// added by Alex 12/17/98
						"</td></tr>";
	}
	else	// add 10 and 14 day auctions Soc 4/12/99
	{
		if (itemNo)
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3"
					  <<	(((days >= 3) && (days < 5)) ? " selected>3\n" : ">3\n")
					  <<	"<option value=5"
					  <<	(((days >= 5) && (days < 7)) ? " selected>5\n" : ">5\n")
					  <<	"<option value=7"
					  <<	((((days < 3) || (days >= 7)) && (days < 10))? " selected>7\n" : ">7\n")
					  <<    "<option value=10"
					  <<    ((days >= 10) ? " selected>10\n" : ">10\n")
        			  // - 04/14/99 soc  - comment out 14 day auctions for now
					  //<<    (((days >= 10) && (days < 14)) ? " selected>10\n" : ">10\n")
					  //<<    "<option value=14"
					  //<<    ((days >= 14) ? " selected>14\n" : ">14\n")
					  <<	"</select>\n";
		}
		else
		{
			*mpStream <<	"<select name=duration>\n"
							"<option value=3>3\n"
							"<option value=5>5\n"
							"<option value=7 selected>7\n"
							"<option value=10>10\n"
        					// - 04/14/99 soc  - comment out 14 day auctions for now
							//"<option value=14>14\n"
							"</select>\n";
		}

		*mpStream <<	"<font size=\"2\"> days&nbsp; "
						"</font><font size=\"2\" color=\"#006600\">(required)</font>"
						"</td></tr>";

	}
		
		
	//reserve price?
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\"><a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/buyerguide/bidding-type.html#reserve\">Reserve price</a>"
			  <<	"</font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"reserve\" ";

	if ((itemNo) && (mpItem->GetReservePrice() != 0))
	{
		*mpStream <<	"value=\""
				  <<	"update needed"
				  <<	"\" ";
	}
	
	*mpStream <<	"size=" << (maxSize + 2)<< " "
					"maxlength=" << maxSize << ">"
					"<font size=\"2\">&nbsp;&nbsp;"
					"(numerals and decimal point '.' only)&nbsp; "
					"</font><font size=\"2\" color=\"#800000\">(optional)<br>"
					"</font><i><font size=\"2\">e.g.: 5.00</font></i> ";

	if ((itemNo) && (mpItem->GetReservePrice() !=0))
	{
		*mpStream <<	"<font size=\"2\">"
						"&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To look up your previous reserve, please check "
						"<a href=\""
				 <<		mpMarketPlace->GetHTMLPath()
				 <<		"services/myebay/myebay.html\">My eBay</a>.</font>";
	}


	if (verbose)
	{

		*mpStream <<	"<p><font size=\"2\">If you want to start the bidding at a price lower than "
				  <<	"you are willing to sell your item, use the Reserve Price option. This "
				  <<	"option is not popular among bidders! To use the Reserve Price option, set the "
				  <<	"real amount you are willing to accept for your item. This price will "
				  <<	"be hidden from bidders, but your item information will indicate whether "
				  <<	"or not the Reserve Price has been met. <b>Leave this blank</b> if you "
				  <<	"don't want a Reserve Price! Please refer to the "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#reserve\">Reserve "
				  <<	"Prices</a> section of the Guidelines for more information. Note: Reserve "
				  <<	"Price items are not eligible for the Hot Items section. Also, Reserve "
				  <<	"Prices are not allowed for "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#dutch\">Dutch "
				  <<	"auctions</a>. </font>";
	}

	*mpStream <<	"</td></tr>\n";

	// Row for boldface title 
	      
	*mpStream <<	"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
			  <<	"<font size=\"3\">"
			  <<	"Boldface title?"
			  <<	"</font></td>"
					"<td width=\"430\">"
					"<input type=checkbox name=bold";
	if (itemNo && mpItem->GetBoldTitle())
	{
		*mpStream <<	" checked";
	}
	*mpStream <<	">"
					"<font size=2>(";

	currencyWidget.SetNativeAmount(objFees.GetFee(BoldFee));
	currencyWidget.EmitHTML(mpStream);	
	
	*mpStream <<    " charge)</font>"
					"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";


	if (verbose)
	{

		*mpStream <<	"<p><font size=\"2\">For "
				  <<	"an additional fee of ";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(BoldFee));
		currencyWidget.EmitHTML(mpStream);	
	
		*mpStream <<    ", you may choose to have your listing title appear in boldface "
				  <<	"in the listing pages, making it stand out among the other listings.</font><br>";
	}

		*mpStream <<	"</td>\n"
						"</tr>\n";	

	// Row for super featured
	*mpStream <<	"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=3>"
			  <<	"Featured Auction?"
			  <<	"</font></td>"
					"<td width=\"430\">"
					"<input type=checkbox name=superfeatured";
	time_t when = time(0);
	if (itemNo && mpItem->GetSuperFeatured())
	{
		*mpStream <<	" checked";
	}
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
	{
		*mpStream <<	">"
						"<font size=2>(";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(FeaturedFee));
		currencyWidget.EmitHTML(mpStream);	
		
		*mpStream  <<   " charge)</font>"
						"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";
	}
	else
	{
		*mpStream <<	">"
						"<font size=2>(";

		currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
		currencyWidget.EmitHTML(mpStream);	

		*mpStream <<	" charge)</font>"
						"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";
	}

	if (verbose)
	{
		if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		{
			*mpStream <<	"<p><font size=\"2\">For "
					  <<	"a flat fee of <b>";
			
			currencyWidget.SetNativeAmount(objFees.GetFee(FeaturedFee));
			currencyWidget.EmitHTML(mpStream);	
		
			*mpStream <<    "</b>, you may have your listing title rotate at random intervals on "
					  <<	"the eBay home page. You must have a feedback rating of at least 10 to place a featured "
					  <<	"auction."
							"<p><font color=\"red\">As part of Featured Auction changes, prices for Featured Auctions "
							"will be increased to ";
		
			currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
			currencyWidget.EmitHTML(mpStream);	
			
			*mpStream <<    " on 2/15/99.</font></font>";
		}
		else
		{
			*mpStream <<	"<p><font size=\"2\">For "
					  <<	"a fee of <b>";

			currencyWidget.SetNativeAmount(objFees.GetFee(NewFeaturedFee));
			currencyWidget.EmitHTML(mpStream);	
			
			*mpStream <<    "</b>, a Featured Auction listing will appear at the top of "
					  <<	"the main listings page, accessible from the top of every page on eBay. In "
					  <<	"addition, Featured Auctions are randomly selected for display on the main "
					  <<	"Home page and on category Home pages, though eBay does not guarantee that a "
					  <<	"specific auction will appear on either a category Home page or on the main "
					  <<	"Home page.  You must have a feedback rating of at least 10 to place a Featured "
					  <<	"Auction.</font>";
		}
	}

	*mpStream <<	"</td>\n"
					"</tr>\n";


	// Row for category featured
	*mpStream <<	"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=3>"
			  <<	"Featured Category Auction?"
			  <<	"</font></td>"
					"<td width=\"430\">"
					"<input type=checkbox name=featured";
	if (itemNo && mpItem->GetFeatured())
	{
		*mpStream <<	" checked";
	}
	if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
	{
		*mpStream <<	">"
						"<font size=2>(";

		currencyWidget.SetNativeAmount(objFees.GetFee(CategoryFeaturedFee));
		currencyWidget.EmitHTML(mpStream);	
	
		*mpStream <<    " charge)</font>"
						"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";
	}
	else
	{
		*mpStream <<	">"
						"<font size=2>(";
		
		currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
		currencyWidget.EmitHTML(mpStream);	
		
		*mpStream <<    " charge)</font>"
						"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";
	}

	if (verbose)
	{
		if (clsUtilities::CompareTimeToGivenDate(when, 02, 15, 99, 0, 0, 0) < 0) 
		{
			*mpStream <<	"<p><font size=\"2\">For "
					  <<	"a flat fee of <b>";

			currencyWidget.SetNativeAmount(objFees.GetFee(CategoryFeaturedFee));
			currencyWidget.EmitHTML(mpStream);	
		
			*mpStream <<    "</b>, you may have your listing title appear at the top of the "
					  <<	"category in which the item is listed. You must have a feedback rating of at least "
					  <<	"10 to place a featured category auction."
							"<p><font color=\"red\">Prices for Category Featured Auctions "
							"will be increased to ";
		
			currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
			currencyWidget.EmitHTML(mpStream);	
		
			*mpStream <<    " on 2/15/99.</font></font>";
		}
		else
		{
			*mpStream <<	"<p><font size=\"2\">For "
					  <<	"a fee of <b>";

			currencyWidget.SetNativeAmount(objFees.GetFee(NewCategoryFeaturedFee));
			currencyWidget.EmitHTML(mpStream);	

			*mpStream <<    "</b>, a Category Featured Auction listing will appear at the "
					  <<	"top of the category listings page. In addition, Category Featured Auctions "
					  <<	"are randomly selected for display on category Home pages, though eBay does "
					  <<	"not guarantee that a specific auction will appear there.  You must have a "
					  <<	"feedback rating of at least 10 to place a Category Featured Auction.</font>";
		}
	}

	*mpStream <<	"</td>\n"
					"</tr>\n";
//  Lena - gift icon 
	// Row for gift icon
	 *mpStream <<	"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">Great Gift icon?</font><br>"
					"<font size=\"3\" color=red>"
					"<br>You get to choose your icon! Draw attention to your listing!"
					"<img border=\"0\" height=\"11\" width=\"28\" alt=\"[NEW!]\"" 
					" src=\""
				<<	 mpMarketPlace->GetImagePath()
				<<	"new.gif\">"
				<<  "</font></td>"
					"<td width=\"430\">";


//need change to dropdow box --vicki
	if (itemNo && mpItem != NULL )
	{
		giftIconType=mpItem->GetGiftIconType();
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

     *mpStream	<<	"<font size=\"2\">(";

	 currencyWidget.SetNativeAmount(objFees.GetFee(GiftIconFee));
	 currencyWidget.EmitHTML(mpStream);	
		
	 *mpStream <<   " charge)&nbsp;</font>"
					"<font size=\"2\" color=\"#800000\">(optional)</font>";
	 if (verbose)
	 {
		 *mpStream <<	"<p><p><font size=\"2\" color=red>Highlight your listing!</font>"
						"<font size=\"2\"> For "
						"an additional fee of ";

		 currencyWidget.SetNativeAmount(objFees.GetFee(GiftIconFee));
		 currencyWidget.EmitHTML(mpStream);	
	 
		
		 *mpStream <<	"<font size=\"2\"> For an additional $1.00, you get to choose which " 
						"one of many special icons appear next to your listing title in the listing "
						"pages. Your item will appear in our special gift section. Gift seller tip: "
						"Indicate in your item description what special gift giving accommodations, "
						"if any, you will make. See "
					<<  "<a href=\""
					<<	 mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/gift-icon.html\">Gift Icon</a>"
						" for more information.</font><br>";
	 }
	  *mpStream <<	"</td>\n"
					"</tr>\n";
	
// Lena gift icon end


		// Row for private
	*mpStream <<	"<tr>\n"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=3>"
					"<a href="
					"\""
			  <<	mpMarketPlace->GetHTMLPath() << "help/buyerguide/bidding-type.html#private"
			  <<	"\""
					">"
			  <<	"Private auction?"
					"</a>"
			  <<	"</font></td>"
					"<td width=\"430\">"
					"<input type=checkbox name=private";
	if (itemNo && mpItem->GetPrivate())
	{
		*mpStream <<	" checked";
	}
	*mpStream <<	">"
					"<font size=2>"
					"&nbsp;&nbsp;Please don't use this unless you have a "
					"specific reason.</font>"
					"<font size=\"2\" color=\"#800000\">&nbsp;(optional)</font>";

	if (verbose)
	{

		*mpStream <<	"<p><font size=\"2\">This option specifies that bidders' "
				  <<	"identities not be visible on your auction page. Only the seller and the high bidder will "
				  <<	"be notified of the final outcome of the auction, and all bidders' addresses will be "
				  <<	"protected. For more information, see the "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#private\">Private auctions</a> description. Not "
				  <<	"applicable for "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/buyerguide/bidding-type.html#dutch\">Dutch auctions</a>. "
				  <<	"Most auctions are <strong>not</strong> private auctions. <strong>Don't use this option</strong> "
				  <<	"unless you have a specific reason; for example, avoiding embarassment for buyers.</font><br>";
	}

	*mpStream <<	"</td>\n"
					"</tr>\n";

	//End 3rd table
	*mpStream <<	"</table>";

	// table for gallery -- vicki
	*mpStream <<	"<br><table border=\"1\" cellspacing=\"0\" width=\"590\">\n"
					"<tr  bgcolor=\"#99CCCC\">"
					"<td align=\"center\"><font size=\"5\" color=\"#000000\"> <i>The Gallery</i>\n"
					"<img border=0 height=11 width=28 alt=\"[NEW!]\" src=\"" 
				<<	 mpMarketPlace->GetImagePath()
			    <<	"new.gif\">"
					"</font></td>"
					"</tr>\n";
	*mpStream <<	"<tr>"
					"<td>"
					"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">\n"
					"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\">"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/gallery-faq.html\">Gallery?</a></font></td>\n";


	 *mpStream <<	"<td width=\"430\">\n"
					"<input type=\"radio\" name=gallery value=\"0\"";

	if (itemNo)
	{
		if((mpItem->GetGalleryType() == NoneGallery))
			*mpStream <<	" checked";
	}
	else
		*mpStream <<	" checked";

    *mpStream	<<	"><font size=2>Do not include my item in the Gallery &nbsp<br>";

	*mpStream	<< "<input type=\"radio\" name=gallery value=\"1\"";

	if (itemNo && (mpItem->GetGalleryType() == Gallery))
		*mpStream <<	" checked";
	*mpStream	<<	">Add my item to the Gallery &nbsp (";
	
	currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFee));
	currencyWidget.EmitHTML(mpStream);	
	 
	*mpStream << "charge)<br>";
	
	*mpStream	<< "<input type=\"radio\" name=gallery value=\"2\"";
	if (itemNo && (mpItem->GetGalleryType() == FeaturedGallery))
		*mpStream <<	" checked";
	*mpStream	<<	">Feature my item in the Gallery &nbsp (Featured fee of ";

    currencyWidget.SetNativeAmount(objFees.GetFee(GalleryFeaturedFee));
	currencyWidget.EmitHTML(mpStream);		

	*mpStream  << ")</font>";
	
	*mpStream <<	"</td>\n"
					"</tr>\n";

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\">"
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/sellerguide/gallery-faq.html\">Gallery Image URL</a></font>"
					"</td>"
					"<td width=\"430\" valign=\"top\">"
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
					"<font size=\"2\" color=\"#800000\"> (optional)</font>"
					"<p>"
					"<font size=\"2\"><p> If you do not supply a URL for your Gallery image, "
					"your Pic URL will appear in the Gallery. (If you enter a picture URL in this field, "
					"you may use jpg, bmp, or tif files. Please note that <b>gif</b> files will "
					"<b>not</b> appear in the Gallery!)</font>"
					"</td>"
					"</tr>"
					"</table>";

	*mpStream	<<	"</td></tr></table>";

	//spacer
	*mpStream <<	"<p><br></p>";

	// Make a table for last  fields
	*mpStream <<	"<table border=\"0\" cellpadding=\"0\" width=\"590\">\n";
					
	// Fees stuff
	*mpStream <<	"<tr>\n"
					"<td>\n"
					"<p>You will be advised of all <b>fees</b> due before you place your listing. "
					"Press the &quot;review&quot; button below to see what fees are due immediately "
					"and what fees may be due if your item sells. You will not incur any "
					"fees until you accept the terms disclosed in the next screen.</p>";
	// Row for Review
	*mpStream <<	"<p><strong>Press "
					"<img src="
					"\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"dot_clear.gif"
					"\" "
					"width=1 vspace=6 border=0 ALT="">"
			  <<	"<input type=submit value=\"review\">"
					" to review and place your listing.</strong></p>";	

	// Row for Clear
	*mpStream <<	"<P>Press "
					"<input type=\"reset\" value=\"clear form\" name=\"reset\">"
			  <<	" to clear the form and start over.</p>"
			  <<	"</td>\n"
					"</tr>\n";	

	// End table for last  fields
	*mpStream <<	"</table>\n";

	// pass the orginal item # if any
	if (itemNo)
	{
		*mpStream << "<input type=hidden name=olditem value=\""
				  << pItemNo
				  << "\">";

		// crypt the old number
		sprintf(cSalt, "%d", mpItem->GetSeller() + itemNo + 3);
		pCryptedItemNo = crypt(pItemNo, cSalt);

		*mpStream << "<input type=hidden name=oldkey value=\""
				  << pCryptedItemNo
				  << "\">";

		free(pCryptedItemNo);
	}

	if (currencyId != Currency_USD)  // PH changed 04/26/99
	{
		*mpStream << "<input type=hidden name=currencyid value=\""
				  << currencyId
				  << "\">";
		//you already passed countryid before!!!
		//		  << "<input type=hidden name=countryid value=\""
		//		  << countryId
		//		  << "\">";
	}

	// End form
	*mpStream <<	"\n</form>";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	vCategories.erase(vCategories.begin(), vCategories.end());

	delete (char *) pSafeDescription;
	CleanUp();

	return;

}

