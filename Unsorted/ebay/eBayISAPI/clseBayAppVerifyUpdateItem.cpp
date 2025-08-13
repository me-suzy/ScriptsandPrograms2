/*	$Id: clseBayAppVerifyUpdateItem.cpp,v 1.5.22.5.34.1 1999/08/01 03:01:36 barry Exp $	*/
// clseBayAppVerifyUpdateItem.cpp 
//
//		Verify an item update
//
//	Author: Josh Gordon (josh@ebay.com)
//
//	Date: 02/09/99
//
//	Modifications:
//				- 04/07/99 kaz	- added check for Police Badge T&C page
//				- 04/08/99 kaz	- changed PB date to 4/16/99
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//				- 07/27/99 nsacco - added shipping options to VerifyUpdateItem() and CheckUpdatedItemInfo()

#include "ebihdr.h"
#include "clsItemValidator.h"

#define CHECKED(x)	(!strcmp(x,"on"))

static const char ENoteTextNewItemByFlaggedSellerTemplate[] =
"eBay user %s has been previously flagged for selling "
"items which were blocked, and has just attempted to "
"update item %d (%s) with information including these "
"words/phrases: \n"
"%s";

static const char ENoteTextSuspiciousItemListedTemplate[] =
"eBay user %s has just listed item %d (%s), which contains "
"these words/phrases in the title and/or description: \n"
"%s";

static const char ENoteTextSuspiciousItemBlockedTemplate[] =
"eBay user %s has just attempted to list item %d (%s), which contains "
"these words/phrases in the title and/or description: \n"
"%s";

// nsacco 07/27/99 added new params
bool clseBayApp::CheckUpdatedItemInfo(const char *pTitle,
									  const char *pDesc,
									  const char *pPicUrl,
									  const char *pCategory,
									  const char *pMoneyOrderAccepted,
									  const char *pPersonalChecksAccepted,
									  const char *pVisaMasterCardAccepted,
									  const char *pDiscoverAccepted,
									  const char *pAmExAccepted,
									  const char *pOtherAccepted,
									  const char *pOnlineEscrowAccepted,
									  const char *pCODAccepted,
									  const char *pPaymentSeeDescription,
									  const char *pSellerPaysShipping,
									  const char *pBuyerPaysShippingFixed,
									  const char *pBuyerPaysShippingActual,
									  const char *pShippingSeeDescription,
									  const char *pShippingInternationally,
									  const char *pShipToNorthAmerica,
									  const char *pShipToEurope,
									  const char *pShipToOceania,
									  const char *pShipToAsia,
									  const char *pShipToSouthAmerica,
									  const char *pShipToAfrica,
									  int siteId,
									  int descLang,
									  const char *pUserId,		// kaz: 4/15/99 add userId & pwd so we can check
									  const char *pPass)
{
	bool error = false;


	// title is empty?
	if (FIELD_OMITTED(pTitle))
	{
		*mpStream <<	"<h2>"
					"Empty Title"
					"</h2>"
					"You must enter a title for your item. "
					"Please go back and try again.<p>\n ";
		error = true;
	}

   // title too long?
	if (strlen(pTitle) > EBAY_MAX_TITLE_SIZE)
	{
		int countingIso = clsUtilities::CountIsoInTitle((char *)pTitle);

		if (strlen(pTitle) > 250 || (strlen(pTitle) - (countingIso * 5)) > 45)
		{
			*mpStream <<	"<h2>"
							"Title too long"
							"</h2>"
							"Listing titles are limited to 45 characters."
							" Please go back and shorten your title<p>\n";

			error = true;
		}
	}

	// Description
	if (FIELD_OMITTED(pDesc))
	{
		*mpStream << "<h2>No description</h2>"
				     "There was no description provided. You must provide a description "
					 "for your item. Please go back and try again."
				     "<p>" << endl;		
       error = true;
	}

	// PicURL
	if (!FIELD_OMITTED(pPicUrl) &&
		strncmp(pPicUrl, "http://", 7) != 0 &&
		strncmp(pPicUrl, "HTTP://", 7) != 0 &&
		strncmp(pPicUrl, "ftp://", 6)  != 0 &&
		strncmp(pPicUrl, "FTP://", 6)  != 0)
	{
		*mpStream <<	"<h2>Error in Picture URL</h2>"
						"The URL you supplied for a picture of your item: "
				  <<	pPicUrl
				  <<	", does not begin with a valid http reference. "
						"Please go back and check it again."
						"<p>" << endl;

		error = true;
	}

	// Category checks
	clsCategory	*pRealCategory = NULL;

	if (!FIELD_OMITTED(pCategory))
		pRealCategory	= mpCategories->GetCategory(atoi(pCategory), true);

	if (FIELD_OMITTED(pCategory) || (!pRealCategory))
	{
		*mpStream <<	"<h2>"
						"Invalid category"
						"</h2>"
						"The category was not properly transmitted to our server. "
						"If problem is persistant, it may be due to a problem with your "
						"browser or incorrect use of our service. Please report this "
						"to <a href=mailto:support@ebay.com>Customer Support</a>."
						"<p>";

		error = true;
	}
	
	if (pRealCategory && !pRealCategory->isLeaf())
	{

		*mpStream <<	"<h2>Category does not allow listings</h2>"
						"The new category is not a \'leaf\' "
						"category, and items cannot be listed in it. ";
		error = true;
	}


	
	// 02/24/99 Alex Poon
	// Block firearms listing beginning on Sat 2/27/99
	int nCategory	= atoi(pCategory);
	if (CheckForFirearmsListing(nCategory))
		error = true;

	// kaz: 4/7/99: Support for Police Badge T&C
	if (nCategory == kPoliceBadgeCatID)
		if (clsUtilities::CompareTimeToGivenDate(time(0),4,16,99,0,0,0) >= 0) 
			if (! mpUser->AcceptedPoliceBadgeAgreement())
	{
		PoliceBadgeAgreementForSelling((char *) pUserId,(char *) pPass);
		error = true;		// the footer is emitted later, so we don't need to do it here
	}	//

	if (!CHECKED(pMoneyOrderAccepted) &&
		!CHECKED(pPersonalChecksAccepted) &&
		!CHECKED(pVisaMasterCardAccepted) &&
		!CHECKED(pDiscoverAccepted) &&
		!CHECKED(pAmExAccepted) &&
		!CHECKED(pOtherAccepted) &&
		!CHECKED(pOnlineEscrowAccepted) &&
		!CHECKED(pCODAccepted) &&
		!CHECKED(pPaymentSeeDescription) )
	{
		*mpStream <<	"<h2>"
						"Payment methods are not specified. "
						"Please go back and make your selection.</H2>"
						"<p>";

		error	= true;
	}
	
	if (!CHECKED(pSellerPaysShipping) &&
		!CHECKED(pBuyerPaysShippingFixed) &&
		!CHECKED(pBuyerPaysShippingActual) &&
		!CHECKED(pShippingSeeDescription))
	{
		*mpStream <<	"<h2>"
						"Shipping terms are not specified. "
						"Please go back and make your selection.</H2>"
						"<p>";

		error = true;
	}

	if ((((CHECKED(pSellerPaysShipping)) && (CHECKED(pBuyerPaysShippingFixed))) ||
		(CHECKED(pSellerPaysShipping)) && (CHECKED(pBuyerPaysShippingActual)))	||
		(CHECKED(pBuyerPaysShippingFixed)) && (CHECKED(pBuyerPaysShippingActual)))
	{
		*mpStream <<	"<h2>"
						"Contradictory shipping terms."
						"</h2>"
						"Contradictory shipping terms specified. Please go back and make your selection."
						"<p>";
		error = true;
	}

	// check new shipping terms
	// check that regions were not selected unless siteplusregions
	if (strcmp(pShippingInternationally, "siteplusregions") != 0)	 
	{
		if (CHECKED(pShipToNorthAmerica) ||
			CHECKED(pShipToEurope) ||
			CHECKED(pShipToOceania) ||
			CHECKED(pShipToAsia) ||
			CHECKED(pShipToSouthAmerica) ||
			CHECKED(pShipToAfrica)
			)
		{
		
			*mpStream <<	"<h2>"
							"Contradictory shipping locations."
							"</h2>"
							"Contradictory shipping locations were specified. Individual "
							"shipping regions can not be selected when choosing the single "
							"country only or ship internationally options."
							"<p>";
			error = true;
		}
	}
	else
	{
		// check that a region was selected if siteplusregions
		if (!( CHECKED(pShipToNorthAmerica) ||
			CHECKED(pShipToEurope) ||
			CHECKED(pShipToOceania) ||
			CHECKED(pShipToAsia) ||
			CHECKED(pShipToSouthAmerica) ||
			CHECKED(pShipToAfrica)
			))
		{
		
			*mpStream <<	"<h2>"
							"Missing shipping region"
							"</h2>"
							"The ship to a country and regions option was selected but "
							"no region was selected from the list."
							"<p>";
			error = true;
		}
	}
	// end check new shipping terms

	return error;
}

static void show_option(ostream *mpStream, const char *test_value, const char *prompt)
{
	char *checked = CHECKED(test_value) ? "yes" : "no";

	*mpStream << prompt << "<strong>" << checked << "</strong>" << endl;
}

// nsacco 07/27/99 added new shipping options
bool clseBayApp::VerifyUpdateItem(const char *pUserId,
								  const char *pPass,
								  const char *pItemNo,
								  const char *pTitle,
								  const char *pDesc,
								  const char *pPicUrl,
								  const char *pCategory1,
								  const char *pCategory2,
								  const char *pCategory3,
								  const char *pCategory4,
								  const char *pCategory5,
								  const char *pCategory6,
								  const char *pCategory7,
								  const char *pCategory8,
								  const char *pCategory9,
								  const char *pCategory10,
								  const char *pCategory11,
								  const char *pCategory12,
								  const char *pMoneyOrderAccepted,
								  const char *pPersonalChecksAccepted,
								  const char *pVisaMasterCardAccepted,
								  const char *pDiscoverAccepted,
								  const char *pAmExAccepted,
								  const char *pOtherAccepted,
								  const char *pOnlineEscrowAccepted,
								  const char *pCODAccepted,
								  const char *pPaymentSeeDescription,
								  const char *pSellerPaysShipping,
								  const char *pBuyerPaysShippingFixed,
								  const char *pBuyerPaysShippingActual,
								  const char *pShippingSeeDescription,
								  const char *pShippingInternationally,
								  const char *pShipToNorthAmerica,
								  const char *pShipToEurope,
								  const char *pShipToOceania,
								  const char *pShipToAsia,
								  const char *pShipToSouthAmerica,
								  const char *pShipToAfrica,
								  int		 siteId,
								  int		 descLang
								  )
{
	clsCategory *pCategory = NULL;

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<HTML><head><TITLE>"
		<<	mpMarketPlace->GetCurrentPartnerName() 
		<<	" Item Update Verification"
		<<	"</TITLE></head>"
		<<	mpMarketPlace->GetHeader()
		<<	"<br>";

	mpUser	= mpUsers->GetAndCheckUserAndPassword((char *)pUserId, (char *)pPass, mpStream);

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return false;
	}

	int	item    = atoi(pItemNo);

	// Let's get the item
	if (item && !GetAndCheckItem((char *)pItemNo))
	{
		CleanUp();
		return false;
	}


	if(mpItem->GetAllBidCount() > 0)
	{
		
        *mpStream <<    "<br><H2>The item  ("
			<<    pItemNo
			<<    ") has received bids</H2>"
			"<p>"
			"You're not allowed to update the item information, "
			"if the item has received any bid even the bid is canceled. <p>"
			<<    mpMarketPlace->GetFooter()
			<<    flush;
		
		
        CleanUp();
        return false;;
    }

		
	// Now check all the data.	
	// First, grab the category and reduce it.
	// check to make sure exactly one category was selected.
	int numSelectedCategories = 0;
	const char *selectedCategory = NULL;
	bool error = false;
	const char *categories[] =
	{
		pCategory1,
		pCategory2,		
		pCategory3,
		pCategory4,		
		pCategory5,
		pCategory6,		
		pCategory7,
		pCategory8,		
		pCategory9,
		pCategory10,		
		pCategory11,
		pCategory12
	};

	for (int j = 0; j < 12; j++)
	{
		if ((!FIELD_OMITTED(categories[j])) && (atoi(categories[j]) != 0))
		{
			numSelectedCategories++;
			selectedCategory = categories[j];
		}
	}
	
	// now do the checks
	if (numSelectedCategories < 1)
	{
		*mpStream <<	"<h2>"
			"No category selected"
			"</h2>"
			"You must choose a category in which to list your item."
			"<p>";
		error = true;
	}
	if (numSelectedCategories > 1)
	{
		*mpStream <<	"<h2>"
			"More than one category selected"
			"</h2>"
			"You can choose only one category in which to list your item."
			<<		"<p>Please go back and try again!"
			<<		"<p>";
		error = true;
	}
	//if move into Cars Or Real Estate - it can not be Dutch
	if (selectedCategory && mpItem->GetQuantity() > 1)
	{
		int cat = atoi(selectedCategory);
		if (mpItem->CheckForAutomotiveListing(cat) ||
			mpItem->CheckForRealEstateListing(cat))
		{
			*mpStream <<	"<h2>Category Move Invalid</h2>"
					"Dutch Auctions are not allowed in this category, because of "
					"the fixed fee structure that applies to items listed there. "
					"For details, see the <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/sellerguide/fixed.html\">"
					"list</a>."
					"of these categories.  For this reason, you may not move this"
					"Dutch Auction listing into this category.  If you "
					"wish to list individual items which you are selling via a Dutch Auction "
					"within this category, you must <a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"end-auction.html\">"
					"end the auction</a> "
					"and relist the individual item in the desired category. "
					"We regret the inconvenience."
					"<p>";
			error = true;
		}
	}

	if (!error)
	{
		// ok, now verify the turkey
		// nsacco 07/27/99 added new shipping options
		error = CheckUpdatedItemInfo(pTitle,
			pDesc,
			pPicUrl,
			selectedCategory,
			pMoneyOrderAccepted,
			pPersonalChecksAccepted,
			pVisaMasterCardAccepted,
			pDiscoverAccepted,
			pAmExAccepted,
			pOtherAccepted,
			pOnlineEscrowAccepted,
			pCODAccepted,
			pPaymentSeeDescription,
			pSellerPaysShipping,
			pBuyerPaysShippingFixed,
			pBuyerPaysShippingActual,
			pShippingSeeDescription,
			pShippingInternationally,
			pShipToNorthAmerica,
			pShipToEurope,
			pShipToOceania,
			pShipToAsia,
			pShipToSouthAmerica,
			pShipToAfrica,
			siteId,
			descLang,
			pUserId,
			pPass);
	}

	if (error)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return false;
	}
	
	// Get the category object (but don't delete it 'cuz it points
	// directly into the category cache!!!
	pCategory	= mpCategories->GetCategory(atoi(selectedCategory), true);

	// First we must check the category for screening and, if necessary,
	// emit one or more messages for/to the seller
	if (pCategory != NULL && pCategory->GetScreenItems())
	{
		EmitSellerMessages(pCategory->GetId(), mpStream);
	}

	char *pNewTitle = UnstripHTML((char *)pTitle);
	
	// Display the thing for review.
	*mpStream <<	"<p>Please verify your changes as they appear below. If there are any "
					"errors, please use the back button on your browser to go back and "
					"correct your entry. Once you are satisfied with the entry, please "
					"press the <b>update</b> button.\n";

	// nsacco 07/27/99
	// modified to use currency widget

	// TODO - switch to using billing currency!!!
	// int accountCurrencyId = mpItem->GetBillingCurrency();
	int	accountCurrencyId = Currency_USD;	// US dollars for now
	clsCurrencyWidget accountCurrencyWidget(mpMarketPlace, accountCurrencyId, 0);

	// fees are charged in the account currency
	clsFees objFees(accountCurrencyId);

	//if changed in or out of cars or real estate, notify user of fees changes
 	if (( (mpItem->CheckForAutomotiveListing() 
			&&  !mpItem->CheckForAutomotiveListing(atoi(selectedCategory)))) 
		|| ((!mpItem->CheckForAutomotiveListing() 
			&& mpItem->CheckForAutomotiveListing(atoi(selectedCategory))))
		|| ((mpItem->CheckForRealEstateListing() 
			&& !mpItem->CheckForRealEstateListing(atoi(selectedCategory)))) 
		|| ((!mpItem->CheckForRealEstateListing()
			&& mpItem->CheckForRealEstateListing(atoi(selectedCategory)))))
	{
		//set category to get new price
		int savedCategory=mpItem->GetCategory();
		mpItem->SetCategory(atoi(selectedCategory));
		*mpStream	<<	"<BR><b>ATTENTION</b>--Your fees will change! The insertion fee for "
						" the new category you selected is different from the old one. "
						"eBay will credit your account for the old insertion fee and "
						"charge you the insertion fee that applies to the new one. "
						"The insertion fee for this item will be ";

		accountCurrencyWidget.SetNativeAmount(mpItem->GetInsertionFee());
		accountCurrencyWidget.EmitHTML(mpStream);	
					
		*mpStream	<<	".\n";
		//reset category back 
		mpItem->SetCategory(savedCategory);
	}
	// end new insertion fee code

	*mpStream <<	"<pre><hr width=50%>"
					"Your ";
	*mpStream <<	mpMarketPlace->GetLoginPrompt()
			  <<	":                             <strong>"
              <<    mpUser->GetUserId()
			  <<	"</strong>\n"
					"The item's title:                         <strong>";

	char		*cleanTitle = NULL;
	//clean up title if there is a html tag
	cleanTitle = clsUtilities::StripHTML(pNewTitle);
	*mpStream << cleanTitle;
	delete [] cleanTitle;

	*mpStream <<	"</strong>\n";

		
	pCategory = mpCategories->GetCategory(atoi(selectedCategory), true);
	*mpStream <<	"The item's category:                      <strong>";
	mpCategories->EmitHTMLQualifiedName(mpStream, pCategory);
	*mpStream <<	"</strong>\n";


	show_option(mpStream, pMoneyOrderAccepted,      "Money order/Cashiers checks:              ");
	show_option(mpStream, pPersonalChecksAccepted,  "Personal checks:                          ");
	show_option(mpStream, pVisaMasterCardAccepted,  "Visa/MasterCard:                          ");
	show_option(mpStream, pDiscoverAccepted,        "Discover:                                 ");
	show_option(mpStream, pAmExAccepted,            "American Express:                         ");
	show_option(mpStream, pOtherAccepted,           "Other:                                    ");
	show_option(mpStream, pOnlineEscrowAccepted,    "Online Escrow:                            ");
	show_option(mpStream, pCODAccepted,             "COD (collect on delivery):                ");
	show_option(mpStream, pPaymentSeeDescription,   "See Item Description for payment methods: ");
	show_option(mpStream, pSellerPaysShipping,      "Seller pays for shipping:                 ");
	show_option(mpStream, pBuyerPaysShippingFixed,  "Buyer pays fixed amount for shipping:     ");
	show_option(mpStream, pBuyerPaysShippingActual, "Buyer pays actual shipping cost:          ");
	// nsacco 07/27/99 removed the show_option for pShipInternationally as it no longer applies
	show_option(mpStream, pShippingSeeDescription,	"See item description for shipping costs:  ");

	// nsacco 07/27/99
	// display shipping options
	*mpStream <<		"\n"
						"<strong>Where you will ship:</strong>\n";

	if (strcmp(pShippingInternationally, "worldwide") == 0)
	{
		*mpStream <<	"Will Ship Internationally (worldwide)\n";
	}
	else if (strcmp(pShippingInternationally, "siteonly") == 0)
	{
		*mpStream <<	"Will Ship to ";

		switch (siteId)
		{
		case SITE_EBAY_DE:
			*mpStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*mpStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*mpStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*mpStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*mpStream << "United States";
			break;
		}
				  
		*mpStream <<	" Only\n";
	}
	else if (strcmp(pShippingInternationally, "siteplusregions") == 0)
	{
		*mpStream <<	"Will Ship to ";

		switch (siteId)
		{
		case SITE_EBAY_DE:
			*mpStream << "Germany";
			break;
		case SITE_EBAY_AU:
			*mpStream << "Australia";
			break;
		case SITE_EBAY_UK:
			*mpStream << "United Kingdom";
			break;
		case SITE_EBAY_CA:
			*mpStream << "Canada";
			break;
		case SITE_EBAY_US:
		case SITE_EBAY_MAIN:
			*mpStream << "United States";
			break;
		}
				  
		*mpStream <<	" and the following regions:\n";

		if (CHECKED(pShipToNorthAmerica))
			*mpStream << "  North America\n";

		if (CHECKED(pShipToEurope))
			*mpStream << "  Europe\n";

		if (CHECKED(pShipToOceania))
			*mpStream << "  Australia / NZ\n";

		if (CHECKED(pShipToAsia))
			*mpStream << "  Asia\n";

		if (CHECKED(pShipToSouthAmerica))
			*mpStream << "  South America\n";

		if (CHECKED(pShipToAfrica))
			*mpStream << "  Africa\n";

		*mpStream << "\n";
	}
	// end shipping options

// Lena Ts and Cs
	*mpStream << "<hr width=50%>";

	*mpStream <<	"\n\nThe description of the item:\n"
					"</pre>";
	
	*mpStream << "<blockquote>\n";
	
	*mpStream << pDesc;
			
	*mpStream <<    "\n</blockquote>\n"
		<<    "<hr width=50%>\n";


	if (!FIELD_OMITTED(pPicUrl) && 	strcmp(pPicUrl, "http://") != 0)
	{
		*mpStream <<	"<blockquote>"
						"<h3>Picture URL</h3>"
						"You have provided a URL to a picture of the item, which is shown below. "
						"If it shows up as a \'broken image\', the URL may be incorrect, and "
						"you should go back and correct it. This can also occur if the image "
						"hasn't been loaded yet. "
						"<p>"
						"<b>Picture URL:</b> "
				  <<	pPicUrl
				  <<	"</blockquote>"
				  <<	"<p>"
						"<center>"
						"<img src="
						"\""
				  <<	pPicUrl
				  <<	"\""
						">"
						"</center>"
						"<p>";
	}
	clsCategory	*pDefaultCategory = mpCategories->GetCategoryDefault(true);
	int nCategory;

	if (nCategory  == pDefaultCategory->GetId())
	{
		*mpStream <<	"<h3>Miscellaneous?</h3>"
				  <<	"You are listing your item in the Miscellaneous category. Are you sure you can't "
				  <<	"find a more appropriate category? You might want to check again by going back "
				  <<	"and looking at the categories available in the menu. Otherwise, you buyers "
						"may not see your listing.<p>\n";
	}

	char *pNewDescription = CleanUpDescription((char *)pDesc);
	pNewTitle = CleanUpTitle((char *)pTitle);;


	*mpStream <<	"<p><form method=post action=\"eBayISAPI.dll?UpdateItemInfo\">"
		<< "<input type=hidden name=userid value=\""
		<<	pUserId
		<<	"\">" << endl
		<< "<input type=hidden name=pass value=\""
		<<	pPass
		<<	"\">" << endl
		<< "<input type=hidden name=itemno value=\""
		<<	pItemNo
		<<	"\">" << endl
		<< "<input type=hidden name=title value=\""
		<<	pNewTitle
		<<	"\">" << endl
		<< "<input type=hidden name=moneyOrderAccepted value=\""
		<<	pMoneyOrderAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=personalChecksAccepted value=\""
		<<	pPersonalChecksAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=visaMasterCardAccepted value=\""
		<<	pVisaMasterCardAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=discoverAccepted value=\""
		<<	pDiscoverAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=amExAccepted value=\""
		<<	pAmExAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=otherAccepted value=\""
		<<	pOtherAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=onlineEscrow value=\""
		<<	pOnlineEscrowAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=paymentCOD value=\""
		<<	pCODAccepted
		<<	"\">" << endl
		<< "<input type=hidden name=paymentSeeDescription value=\""
		<<	pPaymentSeeDescription
		<<	"\">" << endl
		<< "<input type=hidden name=sellerPaysShipping value=\""
		<<	pSellerPaysShipping
		<<	"\">" << endl
		<< "<input type=hidden name=buyerPaysShippingFixed value=\""
		<<	pBuyerPaysShippingFixed
		<<	"\">" << endl
		<< "<input type=hidden name=buyerPaysShippingActual value=\""
		<<	pBuyerPaysShippingActual
		<<	"\">" << endl
		<< "<input type=hidden name=shippingSeeDescription value=\""
		<<	pShippingSeeDescription
		<<	"\">" << endl
		<< "<input type=hidden name=shippingInternationally value=\""
		<<	pShippingInternationally
		<<	"\">" << endl
		<< "<input type=hidden name=desc value=\""
		<<	pNewDescription
		<<	"\">" << endl 
		<<	"<input type=hidden name=picurl value=\""
		<<	pPicUrl
		<<	"\">" << endl 
		<<	"<input type=hidden name=category value=\""
		<<	selectedCategory
		<<	"\">" << endl
		// nsacco 07/27/99 pass new params
		<<	"<input type=\"hidden\" name=\"northamerica\" value=\""
		<<	pShipToNorthAmerica
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"europe\" value=\""
		<<	pShipToEurope
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"oceania\" value=\""
		<<	pShipToOceania
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"asia\" value=\""
		<<	pShipToAsia
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"southamerica\" value=\""
		<<	pShipToSouthAmerica
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"africa\" value=\""
		<<	pShipToAfrica
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"siteid\" value=\""
		<<	siteId
		<<  "\">" << endl
		<<	"<input type=\"hidden\" name=\"language\" value=\""
		<<	descLang
		<<  "\">" << endl;
		// end new params
	

	*mpStream <<    "<p><strong>Press "
		<<    "<input type=submit value=\"update\">"
		" to update your listing.</strong></p>"
		" <p>(You may need to reload your item page to see your changes).</p>\n";

	
	*mpStream 	  <<	"</form>";
	
	*mpStream <<	"<p>"
		<<	mpMarketPlace->GetFooter()
		<<	flush;

		 
	CleanUp();

	return true;
}
