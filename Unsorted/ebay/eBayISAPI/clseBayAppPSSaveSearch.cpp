/*	$Id: clseBayAppPSSaveSearch.cpp,v 1.2 1999/05/19 02:34:29 josh Exp $	*/
//
//	File:	clseBayAppPSSaveSearch.cpp
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
//

#include "ebihdr.h"
#include "clsNameValue.h"
#include "clsPSHeaderWidget.h"
#include "clsPSSearches.h"
#include "clsPSSearch.h"

const char ERR_EMPTY_QUERY[] = 
"<p>Please enter keywords or special words for your search.";

const char ERR_TOO_MANY_SEARCHES[] = 
"<p>Each user is allowed to save no more than 3 searches. "
"Please delete an existing search before adding a new one.";

const char ERR_FAILED_TO_ADD[] =
"<p>Failed to save the new search. Please try again later. ";

const char ERR_FAILED_TO_MODIFY[] =
"<p>Failed to modify the search. Please try again later. ";


void clseBayApp::PersonalShopperSaveSearch( CEBayISAPIExtension *pServer, 
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
	clsUserValidation*	pUserValidation;
	clsPSHeaderWidget	PSHeaderWidget(mpMarketPlace);
	clsPSSearches*		pPSSearches;
	clsPSSearch*		pPSSearch;

	SetUp();

	if (FIELD_OMITTED(pRegId) && !FIELD_OMITTED(pQuery) && strlen(pQuery) > 100)
	{
		// Heading, etc
		EmitHeader("Personal Shopper Add Search");
		PSHeaderWidget.EmitHTML(mpStream, "Query String Too Long");
		*mpStream	<<	"Query string is limited to 100 characters, please go back and try again."
					<<	"<p>"
					<<	mpMarketPlace->GetFooter()
					<<	flush;

		return;
	}

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		FIELD_OMITTED(pUserId))
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[8];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperSaveSearch));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("PersonalShopperSaveSearch");
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

	// tell the use we are done
	// Heading, etc
	EmitHeader(" Personal Shopper Saved");

	if (FIELD_OMITTED(pQuery))
	{
		PSHeaderWidget.EmitHTML(mpStream, "Empty Query String");
		*mpStream <<	ERR_EMPTY_QUERY;
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;

		CleanUp();
		return;
	}

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

	// call the function from PSSearches to save or modify the search
	pPSSearches = GetPSSearches();

	// Display Personal Shopper Details
	pPSSearch = new clsPSSearch(mpUser->GetEmail(),
							  mpUser->GetPassword(),
							  pQuery,
							  FIELD_OMITTED(pSearchDesc) ? "n" : pSearchDesc,
							  FIELD_OMITTED(pMinPrice) ? "" : pMinPrice,
							  FIELD_OMITTED(pMaxPrice) ? "" : pMaxPrice,
							  FIELD_OMITTED(pEmailFrequency) ? "1" : pEmailFrequency,
							  FIELD_OMITTED(pEmailDuration) ? "60" : pEmailDuration,
							  FIELD_OMITTED(pRegId) ? NULL : pRegId);

	if (FIELD_OMITTED(pRegId))
	{
		if (!pPSSearches->AddPSSearch(pPSSearch))
		{
			if (pPSSearches->GetErrorMessage() && strcmp(pPSSearches->GetErrorMessage(), "TOO_MANY_BOOKMARKS") == 0)
			{
				PSHeaderWidget.EmitHTML(mpStream, "Too Many Searches");
				*mpStream	<<	ERR_TOO_MANY_SEARCHES;
			}
			else
			{
				PSHeaderWidget.EmitHTML(mpStream, "Search failed to be saved");
				*mpStream	<<	ERR_FAILED_TO_ADD;
			}
			*mpStream <<	"<p>"
					  <<	mpMarketPlace->GetFooter()
					  <<	flush;

			CleanUp();
			delete pPSSearch;
			return;
		}
		PSHeaderWidget.EmitHTML(mpStream, "Search Recorded");
	}
	else
	{
		if (!pPSSearches->ModifyPSSearch(pPSSearch))
		{
			PSHeaderWidget.EmitHTML(mpStream, "Search failed to be modified");
			*mpStream <<	ERR_FAILED_TO_MODIFY
					  <<	"<p>"
					  <<	mpMarketPlace->GetFooter()
					  <<	flush;

			CleanUp();
			delete pPSSearch;
			return;
		}
		PSHeaderWidget.EmitHTML(mpStream, "Search Modified");
	}

	delete pPSSearch;

	// message for the user
	*mpStream	<<	"<p>Your search has been saved. You will be notified by email "
					"when there are new auctions of items you are looking for. "
					"You can also access, modify, and delete your searches anytime "
					"in the Personal Shopper area located in Buyer Services "
					"(accessible from the Site Map). </p>\n"
					"<p>Thank you for using Personal Shopper!</p>\n";

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


