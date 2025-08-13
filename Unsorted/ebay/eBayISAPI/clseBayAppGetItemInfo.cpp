/*	$Id: clseBayAppGetItemInfo.cpp,v 1.7.54.12.14.3 1999/08/09 17:23:54 nsacco Exp $	*/
//
//	File:		clseBayAppNewItemQuick.cpp
//
//	Class:		clseBayApp
//
//	Author:		Vicki Shu (vicki@ebay.com)
//
//	Function:	clseBayApp::GetitemInfo
//				
//				allow user to edit unbid item info
//
//	Modifications:
//				
//				- 09/18/98 vicki - create
//				- 05/06/99 nsacco	- changed strings for shipping
//				- 05/10/99 bill  - Added a new style of category selector
//				- 05/13/99 jennifer - added call to IsMSIE30 to check browser version
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco	- Changed shipping options

#include "ebihdr.h"
#include "clseBayTimeWidget.h"		// petra



// Used to reference functions in our caller.
// It's probably more "portable" to handle
// this stuff through clsEnvironment.


void clseBayApp::GetItemInfo(CEBayISAPIExtension *pServer,
						   char *pItemNo,
						   char *pUser,
						   char *pPass,
						   bool	oldStyle)
{	
	CategoryVector				vCategories;
	int			itemNo = 0;
	char		*cleanTitle = NULL;

	// Used to set the page's expiration to now + 5 minutes
	int			rc;
	time_t		nowTime;
	const struct tm	*pTimeAsTm; 
	char			cEndDate[16]; 
	char			cEndTime[32]; 
		

	time_t			curtime; 
	time_t			endtime; 

	time_t		expirationTime;
	struct tm	*pExpirationTimeAsTM;
	char		expiresHeader[128];

	bool		browserIsJScompatible;

	bool		browserIsMSIE30 = false;

	// Setup
	SetUp();
	
	// Determine if browser is JS compatible
	browserIsJScompatible = ((GetEnvironment()->GetMozillaLevel() >= 4) && (!GetEnvironment()->IsWebTV()) && (!GetEnvironment()->IsWin16()) && (!GetEnvironment()->IsOpera()));

	// Determine if browser is MSIE 3.0
	browserIsMSIE30 = GetEnvironment()->IsMSIE30();

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Get item and category numbers
	if (pItemNo) 
		itemNo = atoi(pItemNo);

	// GetAndCheckItem gets the item in such a way
	// that the seller userid is populated. Let's 
	// see if this item belongs to this user before
	// going any furthur
	_strlwr(pUser);						
	_strlwr(mpItem->GetSellerUserId());

	// Usual Title and Header
	// Headers
	*mpStream <<	"<HTML>"
					"<HEAD>";

	// Set the page to expire 1 day from now
	nowTime				= time(0);
	expirationTime		= nowTime + (ONE_DAY);

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

	*mpStream <<	"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName() 
			  <<	" Edit Unbid Item Information"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	*mpStream <<	mpMarketPlace->GetHeader();

	// Spacer
	*mpStream <<	"<br>";

	// Now, let's see if the user's legitimate 
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUser, pPass, mpStream);


	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the auction's ended
	curtime	= time(0);

	if (mpItem->GetEndTime() < curtime)
	{
		endtime		= mpItem->GetEndTime();
		clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1, endtime);	// petra
		pTimeAsTm	= localtime(&endtime);
		if (pTimeAsTm)
		{
			timeWidget.EmitString (cEndDate);	// petra
			timeWidget.SetDateTimeFormat (-1, 2);	// petra
			timeWidget.EmitString (cEndTime);	// petra
// petra			strftime(cEndDate, sizeof(cEndDate),
// petra					 "%m/%d/%y",
// petra					 pTimeAsTm);

// petra			strftime(cEndTime, sizeof(cEndTime),
// petra					 "%H:%M:%S %z",
// petra					pTimeAsTm);
		}
		else
		{
			strcpy(cEndDate, "*Error*");
			strcpy(cEndTime, "*Error*");
		}

		*mpStream <<	"<h2>Bidding already closed</h2>"
						"The bidding on the item: "
				  <<	mpItem->GetTitle()
				  <<	" (item #"
				  <<	pItemNo
				  <<	") ended on "
				  <<	cEndDate
				  <<	" at "
				  <<	cEndTime
		          <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<H2>"
				  <<	pUser
				  <<	" is not the seller for item "
				  <<	pItemNo
				  <<	"</H2>"
						"<p>"
						"Only the seller is allowed to modify the item information. "
						"If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
		CleanUp();
		return;

	}

	//let's see the num of bid count include conceled and retracted
	if(mpItem->GetAllBidCount() > 0)
	{
		*mpStream <<	"<H2>The item  ("
				  <<	pItemNo
				  <<	") has received bids</H2>"
						"<p>"
						"You are not allowed to update an item's information "
						"if the item has received any bids, even if the bid is "
						"canceled or retracted.<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		
				  	
		CleanUp();
		return;
	}

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);


	// Title using black on darkgrey table
	*mpStream <<	"<table border=\"1\" cellspacing=\"0\" width=\"590\" "
					"bgcolor=\"#99CCCC\">"
					"<tr>"
					"<td align=\"center\" width=\"100%\">"
					"<font size=\"5\" color=\"#000000\">"
					"<b>";
	*mpStream <<	"Update Your Item Information";
	*mpStream <<	"</b></font></td></tr>";
	
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
	
	// Spacer
	*mpStream <<	"</td>"
					"</tr>"
					"</table>";
	
	// Link to the same form, but forcing the old style selector
	if (!oldStyle && browserIsJScompatible)
	{
		*mpStream <<    "<p>";
		*mpStream <<	"If you prefer to use the old-style method of choosing a category, click "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetCGIPath(PageGetItemInfo)
				  <<	"eBayISAPI.dll?GetItemInfo&oldStyle=1"
				  <<	"&userid=" 
				  <<	pUser		// pUser
				  <<	"&pass=" 
				  <<	pPass;		// pPass

		if (itemNo)
			*mpStream	<<	"&item=" << itemNo;

		*mpStream <<	"\">here</a>.";
	}	

	// Begin the form					
	*mpStream <<	"<form name=\"GetItemInfo\" method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageVerifyUpdateItem)
			  <<	"eBayISAPI.dll?VerifyUpdateItem"
			  <<	"\""
					">\n";

	*mpStream <<	"<input type=hidden name=userid value=\""
			  <<	pUser
			  <<	"\">\n"
			  <<	"<input type=hidden name=pass value=\""
			  <<	pPass				//pPass
			  <<	"\">\n";
	*mpStream <<	"<input type=hidden name=item value=\""
			  <<	pItemNo
			  <<	"\">\n";

	// nsacco 07/27/99
	// pass siteid and desc lang
	*mpStream <<	"<input type=\"hidden\" name=\"siteid\" value=\""
			  <<	mpItem->GetSiteId()
			  <<	"\">\n";

	*mpStream <<	"<input type=\"hidden\" name=\"language\" value=\""
			  <<	mpItem->GetDescLang()
			  <<	"\">\n";
	// end siteid and desc lang

	// Make a table 
	*mpStream <<	"<table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">";


	// Row for title
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">"
					"<strong>Title</b></strong>"
					"</font><font size=\"2\"> (no HTML)</font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"title\" ";

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

	*mpStream <<	"size=" << EBAY_MAX_TITLE_SIZE << " "
					"maxlength=" << EBAY_MAX_TITLE_SIZE << ">"
					"<font size=\"2\""
					"color=\"#006600\"> (required)</font><br>"
					"<i><font size=\"2\">e.g.: Rare collection of 100 beanie babies</font></i>";

	*mpStream <<	"</td></tr>";
				

	// Row for 
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">"
					"<strong>Item location</strong></font></td>"
					"<td width=\"430\">"
					<<	mpItem->GetLocation();

	*mpStream <<	"</td>"
					"</tr>";

	// nsacco 07/27/99
	// display country
	char pCountry[256];
	mpMarketPlace->GetCountries()->GetCountryName(mpItem->GetCountryId(), pCountry);

	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">"
					"<strong>Country</strong></font></td>"
					"<td width=\"430\">"
			  <<	pCountry
			  <<	"</td>"
					"</tr>";
	// end display country
					
	// End first table
	*mpStream <<	"</table>\n";

	// show full category selector if category wasn't specifed
	// Begin category table
	*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
					"<tr>"
					"<td width=\"100%\" bgcolor=\"#EFEFEF\">"
					"<strong><font size=\"3\">Category</font></strong>";

	*mpStream <<	"<font size=\"2\">&nbsp; (choose <b>one category</b> only)"
					"<font color=\"#006600\">&nbsp; "
					"</font></font><font size=\"2\" color=\"#006600\">(required)</font>"
					"<BR>"
					"If you change your item's category, and the pricing structure "
					"for the new category is different than that of the old one, "
					"eBay will credit your account for insertion fees for the old "
					"category and charge insertion fees for the new one. See "
					"<a href=\"";
	*mpStream	<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/selling-fees.html\">"
					" eBay fees</A>"
					" for details.</b>";

	*mpStream <<	"</td>\n"
					"</tr>\n"
					"<tr>\n" 
					"<td width=100%>"
					"<table border=\"0\" cellpadding=\"0\" cellspacing=\"6\" width=\"100%\">"
					"<tr>\n"
					"<td width=\"50%\" valign=\"middle\">";

	if (!oldStyle && browserIsJScompatible)
	{
		mpCategories->EmitHTMLJavascriptCategorySelector(
									mpStream, 
									"GetItemInfo",
									"", 
									"Category1",
									(itemNo ? mpItem->GetCategory() : NULL), 
									true);
	}
	else	// use old-style category selector
	{
		// Emit Category choice list using 2 columns
		mpCategories->EmitHTMLLeafSelectionMultipleDropdown(
									mpStream,
									mpItem->GetCategory(),
									&vCategories,
									2,
									"category",
									false,
									true);
	}

	*mpStream <<	"</td>\n"
					"</tr>\n";	
					
	// End table for category selector
	*mpStream <<	"</table>\n";


		// End category table
		*mpStream <<	"</td>"
						"</tr>"
						"</table>\n";

	// Make a table for description and picture url -- 3rd table
	
	// Add extra notes if the browser is MSIE 3.0
	if (browserIsMSIE30)
	{
		*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
						"<tr>"
						"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\""
						"><font size=\"3\">"
						"<strong>Description</strong></font>"
						"<font size=\"2\"> (HTML&nbsp;ok)</font><br>"
						"<font color=\"#ff0000\">Note: </font>"
						"<font size=\"2\">"
						"If you are using Microsoft Internet Explorer 3.0,"
						" and if you had a picture URL link in the description,"
						" you may have to re-enter it again.</font></td>"
						"<td width=\"430\">"
						"<textarea name=\"desc\" cols=\"56\" rows=\"8\">";
	}
	else
	{
		*mpStream <<	"<br><table border=\"1\" cellpadding=\"3\" cellspacing=\"0\">"
						"<tr>"
						"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\""
						"><font size=\"3\">"
						"<strong>Description</strong></font>"
						"<font size=\"2\"> (HTML&nbsp;ok)</font></td>"
						"<td width=\"430\">"
						"<textarea name=\"desc\" cols=\"56\" rows=\"8\">";
	}

	if (mpItem->GetDescription())
	{

		*mpStream << mpItem->GetDescription();
	}
	else
	{
		*mpStream <<	"none";
	}

	*mpStream <<	"</textarea><font size=\"2\" color=\"#006600\"> (required)</font>";


	*mpStream <<	"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\"><strong>Picture URL</strong></font></td>"
					"<td width=\"430\" valign=\"top\">"
					"<input type=\"text\" name=\"picurl\" "
					"size=" << "45" << " "
					"maxlength=" << EBAY_MAX_PICURL_SIZE << " ";

	*mpStream <<	"value=\"";
	*mpStream <<	((mpItem->GetPictureURL()) ? mpItem->GetPictureURL() : "http://");
	*mpStream <<	"\"";
	*mpStream <<	">"
			  <<	"<font size=\"3\">"
					"<img src=\""
// kakiyama 07/16/99
			<<      mpMarketPlace->GetPicsPath()
			<<      "pic.gif\" alt=\"[PIC!]\"></font>"
					"<font size=\"2\" color=\"#800000\"> (optional)</font>";

	
	*mpStream <<	"</td>"
					"</tr>\n";
/*
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<font size=\"3\">Quantity </font></td>"
					"<td width=\"430\">"
					"<input type=\"text\" name=\"quant\" ";

	*mpStream <<	"value=\""
			  <<	mpItem->GetQuantity()
			  <<	"\" ";
	*mpStream <<	"size=\"6\" "
			  <<	"maxlength=" << EBAY_MAX_QUANTITY_SIZE << ">"
			  <<	"<font size=\"2\">&nbsp;&nbsp;(type numerals only)&nbsp; "
					"</font><font size=\"2\" color=\"#800000\">(optional)</font>";

	*mpStream <<	"</td>"
					"</tr>\n";
*/
	//row for terms & conditions:
	*mpStream <<	"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\">"
					"<strong><font size=\"3\">"
					"Accepted Payment Methods </font></strong><font size=\"2\" color=\"#006600\">"
					"(required)</font><br>"
					"<font size=2>(choose as many as apply)</font></td>"
					"<td width=\"430\">"
					"<table cellpadding=0 cellspacing=0>"
					"<tr valign=\"top\">";

	*mpStream <<	"<td align = \"left\">"
					"<input type=\"checkbox\" name=\"moneyOrderAccepted\"";
	if (mpItem->AcceptsPaymentMOCashiers())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td width=170> <font size=\"2\"> Money Order/Cashiers Check</font>" 
					"</td>";

	*mpStream <<	"<td align = \"right\">"
					"<input type=\"checkbox\" name=\"personalChecksAccepted\"";

	if (mpItem->AcceptsPaymentPersonalCheck())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td width=105> <font size=\"2\">Personal Check </font>"
					"</td>";			
				
	*mpStream <<	"<td align = \"right\">" 
					"<input type=\"checkbox\" name=\"visaMasterCardAccepted\"";

	if (mpItem->AcceptsPaymentVisaMaster())
		*mpStream <<	" checked ";
	*mpStream <<	"value=\"on\"> </td>"
					"<td align = \"left\"><font size=\"2\">Visa/MasterCard </font>"
					"</td></tr>";	

	*mpStream <<	"<tr valign=\"middle\"><td>"
						"<input type=\"checkbox\" name=\"paymentCOD\"";

	if (mpItem->AcceptsPaymentCOD())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td><font size=\"2\">COD (collect on delivery) </font>"
					"</td>";

	*mpStream <<    "<td><input type=\"checkbox\" name=\"onlineEscrow\"";
	if (mpItem->AcceptsPaymentEscrow())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"></td>"
					"<td>"
					"<font size=\"2\">On-line Escrow</font></td>";
	
    *mpStream <<    "<td><input type=\"checkbox\" name=\"amExAccepted\"";

	if (mpItem->AcceptsPaymentAmEx())	
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"></td>"
					"<td>"
					"<font size=\"2\"> American Express</font></td>"
					"</tr>";
	
	
	*mpStream <<	"<tr valign=\"middle\">"
					"<td><input type=\"checkbox\" name=\"paymentSeeDescription\"";

	if (mpItem->MorePaymentSeeDescription())					
		*mpStream <<	"checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td>"
					"<font size=\"2\"> See Item Description</font> </td>";

	
	*mpStream <<	"<td><input type=\"checkbox\" name=\"otherAccepted\"";

	if (mpItem->AcceptsPaymentOther())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td>"
					"<font size=\"2\"> Other </font> </td>";
	

	*mpStream <<	"<td><input type=\"checkbox\" name=\"discoverAccepted\"";
	if (mpItem->AcceptsPaymentDiscover())
		*mpStream <<	" checked ";

	*mpStream <<	"value=\"on\"> </td>"
					"<td>"
					"<font size=\"2\"> Discover </font> </td>"
					"</tr>";

	*mpStream <<	"</table>"
					"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\">"
					"<strong>Payment Terms</strong></font><font size=\"2\" "
					"color=\"#006600\"><br> (required)</font></td>"
					"<td width=\"430\">"
					"<table cellpadding=0 cellspacing=0 width=\"100%\">"
					"<tr valign=\"middle\">";


	*mpStream <<	"<td> <input type=\"checkbox\" name=\"sellerPaysShipping\" ";
	if (mpItem->SellerPaysForShipping())	
		*mpStream <<	"checked ";
	*mpStream <<	"value=\"on\">"
					"<font size=\"2\"> Seller Pays &nbsp;&nbsp;</font>" 
					"</td>"
					"</tr>";
	
	*mpStream <<	"<tr valign=\"middle\">"
					"<td> <input type=\"checkbox\" name=\"buyerPaysShippingFixed\" ";
	if(mpItem->BuyerPaysForShippingFixed())
		*mpStream <<	"checked ";
	*mpStream <<	"value=\"on\">"
					"<font size=\"2\"> Buyer Pays Fixed Amount&nbsp;</font>" 
					"</td>"
					"</tr>";

	*mpStream <<	"<tr valign=\"middle\">"
					"<td> <input type=\"checkbox\" name=\"buyerPaysShippingActual\" ";
	if(mpItem->BuyerPaysForShippingActual())
		*mpStream <<	"checked ";
	*mpStream <<	"value=\"on\">"
					"<font size=\"2\"> Buyer Pays Actual Shipping Cost&nbsp;</font>" 
					"</td>"
					"</tr>";
	
	*mpStream <<	"<tr valign=\"middle\">"
					"<td> <input type=\"checkbox\" name=\"shippingSeeDescription\" ";
	if(mpItem->MoreShippingSeeDescription())					
		*mpStream <<	"checked ";

	*mpStream <<	"value=\"on\">"
					"<font size=\"2\"> See Item Description</font></td>"
					"</tr>"
					"</table>"
					"</td>"
					"</tr>"
					"<tr>"
					"<td width=\"160\" bgcolor=\"#EFEFEF\" valign=\"top\"><font size=\"3\">"
					"<strong>Shipping Terms</strong></font><font size=\"2\" "
					"color=\"#006600\"><br> (required)</font></td>"
					"<td width=\"430\">"
					"<table cellpadding=0 cellspacing=0 width=\"100%\">";

	// nsacco 07/27/99
	// new shipping options
	switch (mpItem->GetSiteId())
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

	*mpStream <<		"<tr valign=\"middle\">\n"
						"<td>"
						"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteonly\" ";
	
	if(mpItem->IsShippingToSiteOnly())
		*mpStream <<	"checked ";

	*mpStream <<		">"
						"<font size=\"2\">Will ship to "
			  <<		pCountry
			  <<		" only</font><br>\n"
						"<input type=\"radio\" name=\"shippingInternationally\" value=\"siteplusregions\" ";
	if (mpItem->IsShippingToSiteAndRegions())
		*mpStream <<	"checked ";
		
	*mpStream	  <<	">"
						"<font size=\"2\">Will ship to "
				  <<	pCountry
				  <<	" and the following regions: (Check all that apply)</font>\n"
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
						"</td></tr>\n";
	// end new shipping options

	*mpStream <<	"</table>"
					"</td>"
					"</tr>";


	//End 3rd table
	*mpStream <<	"</table>";

	//spacer
	*mpStream <<	"<p><br></p>";

	// Make a table for last  fields
	*mpStream <<	"<table border=\"0\" cellpadding=\"0\" width=\"590\">\n";
					
	// Fees stuff
	*mpStream <<	"<tr>\n"
					"<td>\n";
/*					"<p>You will be advised of all <b>fees</b> due before you place your listing. "
					"Press the &quot;review&quot; button below to see what fees are due immediately "
					"and what fees may be due if your item sells. You will not incur any "
					"fees until you accept the terms disclosed in the next screen.</p>";
*/	// Row for Review
	*mpStream <<	"<p><strong>Press "
					"<img src="
					"\""
			  <<	 mpMarketPlace->GetImagePath()
			  <<	"dot_clear.gif"
					"\" "
					"width=1 vspace=6 border=0>"
			  <<	"<input type=submit value=\"verify\">"
					" to verify your changes.</strong></p>";	

	// Row for Clear
	*mpStream <<	"<p>Press "
					"<input type=\"reset\" value=\"clear changes\" name=\"reset\">"
			  <<	" to clear the form and start over.</p>"
			  <<	"</td>\n"
					"</tr>\n";	

	// End table for last  fields
	*mpStream <<	"</table>\n";

	// End form
	*mpStream <<	"\n</form>";

	*mpStream <<	"<p>"
			  <<	mpMarketPlace->GetFooter()
			  <<	flush;

	// don't delete Categories because we use caching; but erase the vector
	vCategories.erase(vCategories.begin(), vCategories.end());

//	delete (char *) pSafeDescription;

	CleanUp();

	return;

} 

