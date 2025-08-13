/*	$Id: clseBayAppPSDeleteSearch.cpp,v 1.2.158.1 1999/08/01 03:01:20 barry Exp $	*/
//
//	File:	clseBayAppPSDeleteSearch.cpp
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen
//
//	Function:
//
//		Contains the method to save personal shopper search to remind responder
//
// Modifications:
//				- 02/03/99 wen		- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsNameValue.h"
#include "clsPSHeaderWidget.h"
#include "clsPSSearches.h"

// display the search criteria and let user to confirm before deleting it
//
void clseBayApp::PersonalShopperDeleteSearchView( CEBayISAPIExtension *pServer, 
											char *pUserId,
											char *pPassword,
											char *pQuery,
											char *pSearchDesc,
											char *pMinPrice,
											char *pMaxPrice,
											char *pEmailFrequency,
											char *pEmailDuration,
											char *pRegId)
{
	clsUserValidation*		pUserValidation;
	clsPSHeaderWidget		PSHeaderWidget(mpMarketPlace);

	SetUp();

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		FIELD_OMITTED(pUserId))
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[8];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperDeleteSearchView));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("PersonalShopperDeleteSearchView");
		theNameValuePairs[1].SetName("query");
		theNameValuePairs[1].SetValue(pQuery);
		theNameValuePairs[2].SetName("srchdesc");
		theNameValuePairs[2].SetValue(pSearchDesc);
		theNameValuePairs[3].SetName("minPrice");
		theNameValuePairs[3].SetValue(pMinPrice);
		theNameValuePairs[4].SetName("maxPrice");
		theNameValuePairs[4].SetValue(pMaxPrice);
		theNameValuePairs[5].SetName("psfreq");
		theNameValuePairs[5].SetValue(pEmailFrequency);
		theNameValuePairs[6].SetName("psdura");
		theNameValuePairs[6].SetValue(pEmailDuration);
		theNameValuePairs[7].SetName("psreg");
		theNameValuePairs[7].SetValue(pRegId);

		// show login page
		LoginDialog(Action, 8, theNameValuePairs, false, eLoginPersonalShopper);

		CleanUp();

		return;
	}

	// Heading, etc
	EmitHeader("Personal Shopper Delete");

	// validate the requestor
	if (pUserValidation->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char*)pUserValidation->GetValidatedUserId(), mpStream);
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, 
												  pPassword, 
												  mpStream,
												  true,
												  NULL,
												  false,
												  false,
												  false,
												  true);
	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	PSHeaderWidget.EmitHTML(mpStream, "Delete Search");

	if (FIELD_OMITTED(pRegId))
	{
		*mpStream	<<	"<h2>Invalid input data</h2>"
					<<	"Invalid input data has been detected, please go back to try again."
					<<	"</p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;

		CleanUp();
		return;
	}

	DisplaySearchDetailsStatic(mpUser->GetEmail(), pPassword, pQuery, pSearchDesc, 
						 pMinPrice, pMaxPrice, pEmailFrequency, pEmailDuration, pRegId);

	// the footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;
}

//
// display search details in a static from
//
void clseBayApp::DisplaySearchDetailsStatic(char *pEmail,
									char *pPassword,
									char *pQuery,
									char *pSearchDesc,
									char *pMinPrice,
									char *pMaxPrice,
									char *pEmailFrequency,
									char *pEmailDuration,
									char *pRegId)
{
	// display the search details
	*mpStream	<<	"<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperDeleteSearch)
				<<	"eBayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\""
				<<	"PersonalShopperDeleteSearch"
				<<	"\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pEmail
				<<	"\">\n"
					"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psreg\" value=\""
				<<	pRegId
				<<	"\">\n"
					"<table border=\"1\" cellspacing=\"2\">"
					"<tr><td bgcolor=\"#EFEFEF\"><small>Search</small></td>"
					"<td><strong>"
				<<	pQuery
				<<	"</strong></td></tr>"
					"<tr><td bgcolor=\"#EFEFEF\"><small>Search Scope</small></td>"
					"<td><strong>";

	if (pSearchDesc[0] == 'y' || pSearchDesc[0] == 'Y')
		*mpStream	<<	"Item Title and Description";
	else
		*mpStream	<<	"Item Title Only";

	*mpStream	<<	"</strong></td></tr>"
					"<tr><td bgcolor=\"#EFEFEF\"><small>Price Range</small></td>"
					"<td><strong>";
	
	if (FIELD_OMITTED(pMaxPrice) && FIELD_OMITTED(pMinPrice))
	{
		*mpStream	<<	"Over $0.00";
	}
	else if (FIELD_OMITTED(pMaxPrice))
	{
		*mpStream	<<	"Over $"
					<<	pMinPrice;
	}
	else
	{
		*mpStream	<<	"$"
					<<	pMinPrice
					<<	" - $"
					<<	pMaxPrice;
	}

	*mpStream	<<	"</strong></td></tr>"
					"<tr><td bgcolor=\"#EFEFEF\"><small>Email Frequency</small></td>"
					"<td><strong>";

	if (pEmailFrequency[0] == '1')
		*mpStream	<<	"Daily";
	else
		*mpStream	<<	"Every 3 days";

	*mpStream	<<	"</strong></td></tr>"
					"<tr><td bgcolor=\"#EFEFEF\"><small>Email Duration</small></td>"
					"<td><strong>"
				<<	pEmailDuration
				<<	" days</strong></td></tr></table>\n"
					"<p>Please click <input type=\"submit\" value=\"Delete\"> to confirm this request."
					"&nbsp; Thank you!</p></form>\n"
					"<p>If you do not want to delete this Search, "
					"you can go back to the previous page by clicking on BACK.<br></p>";

}

//
// delete the search
//
void clseBayApp::PersonalShopperDeleteSearch( CEBayISAPIExtension *pServer, 
											char *pUserId,
											char *pPassword,
											char *pRegId)
{
	clsUserValidation*		pUserValidation;
	clsPSHeaderWidget		PSHeaderWidget(mpMarketPlace);
	clsPSSearches*			pPSSearches;

	SetUp();

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		FIELD_OMITTED(pUserId))
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[2];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperDeleteSearch));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("PersonalShopperDeleteSearch");
		theNameValuePairs[1].SetName("psreg");
		theNameValuePairs[1].SetValue(pRegId);

		// show login page
		LoginDialog(Action, 2, theNameValuePairs, false, eLoginPersonalShopper);

		CleanUp();

		return;
	}

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Personal Shopper Delete"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// validate the requestor
	if (pUserValidation->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char*)pUserValidation->GetValidatedUserId(), mpStream);
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, 
												  pPassword, 
												  mpStream,
												  true,
												  NULL,
												  false,
												  false,
												  false,
												  true);

	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

	PSHeaderWidget.EmitHTML(mpStream, "Delete Search");

	if (FIELD_OMITTED(pRegId))
	{
		*mpStream	<<	"<h2>Invalid input data</h2>"
					<<	"Invalid input data has been detected, please go back to try again."
					<<	"</p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;

		CleanUp();
		return;
	}

	// call PSSearches code to delete
	pPSSearches = GetPSSearches();
	if (pPSSearches->DeletePSSearch(mpUser->GetEmail(), mpUser->GetPassword(), pRegId))
	{
		// tell the user we are done
		*mpStream	<<	"The search has been deleted. Thank you!";
	}
	else
	{
		*mpStream	<<	"Failed to delete the search. Please report it to <a href://\""
					<<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
					<<	"eBayISAPI.dll?SendQueryEmailShow\">Customer Support</a>.";
	}

	// display the buttons to add more search and view existing searches
	*mpStream	<<	"<table><tr><td>\n"
					"<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperAddSearch)
				<<	"eBayISAPI.dll\">"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"PersonalShopperAddSearch\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pUserId
				<<	"\">\n"
				<<	"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"<input type=\"submit\" value=\"Add a New Search\">"
				<<	"</form></td>\n"
					"<td><form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperViewSearches)
				<<	"eBayISAPI.dll\">"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" value=\"PersonalShopperViewSearches\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pUserId
				<<	"\">\n"
				<<	"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"View Existing Searches\">"
				<<	"</form></td></tr></table>";

	// the footer
	*mpStream	<<	"</p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();
	return;
}

