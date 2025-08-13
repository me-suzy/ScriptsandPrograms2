/*	$Id: clseBayAppPSAddSearch.cpp,v 1.2.2.2.76.1 1999/08/05 18:58:59 nsacco Exp $	*/
//
//	File:	clseBayAppPSAddSearch.cpp
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen
//
//	Function:
//
//		Contains the method to add or modify personal shopper searche
//
// Modifications:
//				- 02/03/99 wen		- Created
//

#include "ebihdr.h"
#include "clsNamevalue.h"
#include "clsPSHeaderWidget.h"

void clseBayApp::PersonalShopperAddSearch( CEBayISAPIExtension *pServer, 
											char *pUserId,
											char *pPassword,
											char *pQuery,
											char *pSearchDesc,
											char *pMinPrice,
											char *pMaxPrice,
											char *pEmailFrequency,
											char *pEmailDuration,
											char *pRegId,
											char *pAgree)
{
	clsUserValidation*	pUserValidation;
	clsPSHeaderWidget	PSHeaderWidget(mpMarketPlace);
	char*				pCleanQuery;

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

	// replace the html tag with escape codes
	pCleanQuery = clsUtilities::StripHTML(pQuery);

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		FIELD_OMITTED(pUserId))
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[8];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperAddSearch));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("PersonalShopperAddSearch");
		theNameValuePairs[1].SetName("query");
		theNameValuePairs[1].SetValue(pCleanQuery);
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

		delete [] pCleanQuery;
		CleanUp();

		return;
	}

	// Heading, etc
	EmitHeader("Personal Shopper Add Search");

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

		delete [] pCleanQuery;
		CleanUp();
		return;
	}

	// check if the user has been a personl shopper 
	if (!mpUser->GetOneUserFlag(UserFlagPersonalShopper))
	{
		// this is the first time the user uses personal shopper
		//
		// check whether the user has agreed on the T&C
		if (!FIELD_OMITTED(pAgree) && (pAgree[0] == 'y' || pAgree[0] == 'Y'))
		{
			mpUser->SetSomeUserFlags(true, UserFlagPersonalShopper);
		}
		else
		{
			// display the terms and condision
			char Action[255];
			clsNameValuePair theNameValuePairs[10];

			// Create the actions tring
			sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperAddSearch));

			// create the name value pairs
			theNameValuePairs[0].SetName("MfcISAPICommand");
			theNameValuePairs[0].SetValue("PersonalShopperAddSearch");
			theNameValuePairs[1].SetName("query");
			theNameValuePairs[1].SetValue(pCleanQuery);
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
			theNameValuePairs[8].SetName("userid");
			theNameValuePairs[8].SetValue(mpUser->GetEmail());
			theNameValuePairs[9].SetName("pass");
			theNameValuePairs[9].SetValue(mpUser->GetPassword());

			// show login page
			DisplayPSNetMindTC(Action, 10, theNameValuePairs);

			// the footer
			*mpStream	<<	"<p>"
						<<	mpMarketPlace->GetFooter()
						<<	flush;

			delete [] pCleanQuery;
			CleanUp();

			return;
		}
	}

	// Display Personal Shopper Details
	if (FIELD_OMITTED(pRegId))
	{
		PSHeaderWidget.EmitHTML(mpStream, "Add a New Search");
		*mpStream	<< "<p>Please fill in the following information for your Personal Shopper search.</p>";
	}
	else
	{
		PSHeaderWidget.EmitHTML(mpStream, "Modify an Existing Search");
	}

	DisplaySearchDetails(mpUser->GetEmail(), mpUser->GetPassword(), pCleanQuery, pSearchDesc, 
						 pMinPrice, pMaxPrice, pEmailFrequency, pEmailDuration, pRegId);

	// the footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	delete [] pCleanQuery;
	CleanUp();
	return;
}

//
// display search details for modification
//
void clseBayApp::DisplaySearchDetails(char *pEmail,
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
	//				"http://search.ebay.com/cgi-bin/texis/ebay/results.html\">\n"
	// kakiyama 07/20/99
				<<  mpMarketPlace->GetSearchPath()
				<<  "texis/ebay/results.html\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pEmail
				<<	"\">\n"
					"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"<input type=\"hidden\" name=\"ht\" value=\"1\">\n"	// ht=1 signifes headertype=1 for new ui
					"<input type=\"hidden\" name=\"psreg\" value=\"";

	if (!FIELD_OMITTED(pRegId))
		*mpStream	<<	pRegId;

	*mpStream	<<	"\">\n"
					"<table border=\"1\" width=\"590\" cellpadding=\"2\">\n"
					"<tr><td bgcolor=\"#EFEFEF\" width=\"25%\">"
					"<strong>Search</strong></td>\n"
					"<td width=\"75%\"><input type=\"text\" name=\"query\" size=\"40\" value=\"";

	if (!FIELD_OMITTED(pQuery))
		*mpStream	<<	pQuery;
	
	*mpStream	<<	"\">\n"
					"<br><small>Type in what are you looking for (<em>e.g. teddy bear</em>). "
					"Click here for </small>"
					"<a href=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"tips-search.html\">search tips</a> \n"
					"<p><input type=\"radio\" name=\"srchdesc\" value=\"n\"";
					
	if (FIELD_OMITTED(pSearchDesc) || pSearchDesc[0] != 'y')
		*mpStream	<<	" checked ";
	
	*mpStream	<<	"><small>Search Item Title only</small><br>\n"
					"<input type=\"radio\" name=\"srchdesc\" value=\"y\"";
	
	if (!FIELD_OMITTED(pSearchDesc) && pSearchDesc[0] == 'y')
		*mpStream	<<	" checked ";
	
	*mpStream	<<	"><small>Search Item Title and Description</small></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\" width=\"25%\">"
					"<strong>Price Range</strong></td>"
					"<td width=\"75%\"><small>Between $"
					"<input type=\"text\" name=\"minPrice\" size=\"10\" value=\"";
	
	if (!FIELD_OMITTED(pMinPrice))
		*mpStream	<<	pMinPrice;
	
	*mpStream	<<	"\"> and $<input type=\"text\" name=\"maxPrice\" size=\"10\" value=\"";
	
	if (!FIELD_OMITTED(pMaxPrice))
		*mpStream	<<	pMaxPrice;
	
	*mpStream	<<	"\"> (optional) </small></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\" width=\"25%\">"
					"<strong>Email Frequency </strong></td>\n"
					"<td width=\"75%\"><select name=\"psfreq\" size=\"1\">\n"
					"<option value=\"1\" ";
	if (FIELD_OMITTED(pEmailFrequency) || pEmailFrequency[0] == '1')
		*mpStream	<<	" selected ";
	
	*mpStream	<<	">Daily</option>\n"
					"<option value=\"3\"";
	if (!FIELD_OMITTED(pEmailFrequency) && pEmailFrequency[0] == '3')
		*mpStream	<<	" selected ";
	
	*mpStream	<<	">Every 3 days</option>\n"
					"</select><br>\n"
					"<small>How often would you like to receive your Personal Shopper email?</small></td></tr>\n"
					"<tr><td bgcolor=\"#EFEFEF\" width=\"25%\">"
					"<strong>Email Duration </strong></td>\n"
					"<td width=\"75%\"><select name=\"psdura\" size=\"1\">\n"
					"<option value=\"30\"";
	
	if (FIELD_OMITTED(pEmailDuration) || pEmailDuration[0] == '3')
		*mpStream	<<	" selected ";
	
	*mpStream	<<	">30 days</option>\n"
					"<option value=\"60\"";
	
	if (!FIELD_OMITTED(pEmailDuration) && pEmailDuration[0] == '6')
		*mpStream	<<	" selected ";
	
	*mpStream	<<	">60 days</option>\n"
					"<option value=\"90\"";
	
	if (!FIELD_OMITTED(pEmailDuration) && pEmailDuration[0] == '9')
		*mpStream	<<	" selected ";
	
	*mpStream	<<	">90 days</option>\n"
					"</select><br>\n"
					"<small>How long would you like to receive your Personal Shopper email?</small></td></tr></table>\n"
					"<input type=\"hidden\" name=\"tc\" value=\"psreview\">\n"
					"<input type=\"hidden\" name=\"maxRecordsPerPage=\" value=\"20\">\n"
					"<p><input type=\"submit\" value=\"Preview\">&nbsp;&nbsp;"
					"<strong>your Personal Shopper search.</strong>"
					"</p>\n"
					"<p><input type=\"reset\" value=\"&nbsp;&nbsp;Undo&nbsp;&nbsp;\">&nbsp;&nbsp;"
					"<strong>your Personal Shopper search.</strong>"
					"</p>\n"
					"</form>";

	// display the button to view the existing searches
	if (FIELD_OMITTED(pRegId))
	{
		*mpStream	<<	"<form method=\"post\" action=\""
					<<	mpMarketPlace->GetCGIPath(PagePersonalShopperViewSearches)
					<<	"eBayISAPI.dll\">"
						"<input type=\"hidden\" name=\"MfcISAPICommand\" "
						"value=\"PersonalShopperViewSearches\">\n"
						"<input type=\"hidden\" name=\"userid\" value=\""
					<<	pEmail
					<<	"\">\n"
						"<input type=\"hidden\" name=\"pass\" value=\""
					<<	pPassword
					<<	"\">\n<p><input type=\"submit\" value=\"&nbsp;&nbsp;View&nbsp;&nbsp;\">"
						"&nbsp;&nbsp;<strong>your Personal Shopper searches.</strong>"
						"</form>";
	}
}

