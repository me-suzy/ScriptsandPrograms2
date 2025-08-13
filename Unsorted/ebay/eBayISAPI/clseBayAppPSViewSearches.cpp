/*	$Id: clseBayAppPSViewSearches.cpp,v 1.2 1999/05/19 02:34:29 josh Exp $	*/
//
//	File:	clseBayAppPSViewSearches.cc
//
//	Class:	clseBayApp
//
//	Author:	Wen Wen
//
//	Function:
//
//		Contains the method to view personal shopper searches
//
// Modifications:
//				- 02/03/99 wen		- Created
//

#include "ebihdr.h"
#include "clsNameValue.h"
#include "clsPSHeaderWidget.h"
#include "clsPSSearches.h"
#include "clsPSSearch.h"

void clseBayApp::PersonalShopperViewSearches(CEBayISAPIExtension *pServer, 
							 char *pUserId,
							 char *pPassword,
							 char *pAgree)
{
	clsUserValidation*		pUserValidation;
	clsPSHeaderWidget		PSHeaderWidget(mpMarketPlace);
	clsPSSearches*			pPSSearches;
	PSSearchVector			vSearches;
	int						i;

	SetUp();

	// check cookie
	pUserValidation = mpUsers->GetUserValidation();
	if (pUserValidation->IsSoftValidated() == false && 
		FIELD_OMITTED(pUserId))
	{
		char Action[255];
		clsNameValuePair theNameValuePairs[1];

		// Create the actions tring
		sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperViewSearches));

		// create the name value pairs
		theNameValuePairs[0].SetName("MfcISAPICommand");
		theNameValuePairs[0].SetValue("PersonalShopperViewSearches");

		// show login page
		LoginDialog(Action, 1, theNameValuePairs, false, eLoginPersonalShopper);

		CleanUp();

		return;
	}

	// Heading, etc
	EmitHeader("Personal Shopper searches");

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
			clsNameValuePair theNameValuePairs[3];

			// Create the actions tring
			sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PagePersonalShopperViewSearches));

			// create the name value pairs
			theNameValuePairs[0].SetName("MfcISAPICommand");
			theNameValuePairs[0].SetValue("PersonalShopperViewSearches");
			theNameValuePairs[1].SetName("userid");
			theNameValuePairs[1].SetValue(mpUser->GetEmail());
			theNameValuePairs[2].SetName("pass");
			theNameValuePairs[2].SetValue(mpUser->GetPassword());

			// show login page
			DisplayPSNetMindTC(Action, 3, theNameValuePairs);

			// the footer
			*mpStream	<<	"<p>"
						<<	mpMarketPlace->GetFooter()
						<<	flush;

			CleanUp();

			return;
		}
	}

	PSHeaderWidget.EmitHTML(mpStream, "Existing Searches");

	// Get information from PSSearch
	pPSSearches = GetPSSearches();
	
	if (pPSSearches->GetSearches(mpUser->GetEmail(), mpUser->GetPassword(), &vSearches))
	{
		EmitPSSearch(mpUser->GetEmail(), mpUser->GetPassword(), &vSearches);
	}
	else
	{
		if (strcmp(pPSSearches->GetErrorMessage(), "EMAIL_NOT_FOUND") == 0)
		{
			// first time
			EmitPSSearch(mpUser->GetEmail(), mpUser->GetPassword(), &vSearches);
		}
		else
		{
			*mpStream	<<	"Failed to retrieve the existing searches. "
							"Please report it to <a href=\""
						<<	mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)
						<<	"eBayISAPI.dll?SendQueryEmailShow\">Customer Support</a>.";
		}
	}

	// display the buttons to add more searches
	if (vSearches.size() < 3)
	{
		*mpStream	<<	"<br>&nbsp;&nbsp;&nbsp;\n"
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
						"</form>";
	}

	// the footer
	*mpStream	<<	"</p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	for (i = 0; i < vSearches.size(); i++)
	{
		delete vSearches[i];
	}

	CleanUp();
	return;
}


//
// Emit searches into HTML
//
void clseBayApp::EmitPSSearch(const char* pEmail, const char* pPassword, PSSearchVector* pSearches)
{
	int			i;
	char*		pOrigQuery;
	int			OrigQueryLength;
	const char*	pQuery;
	time_t		EndingDay;
	struct tm*	pEndingDayTm;
	char		EndingDate[50];

	if (pSearches->empty())
	{
		*mpStream	<<	"You do not have any existing Personal Shopper search.\n";
	}
	else
	{
		// table and the table header
		*mpStream	<<	"<table border=\"0\" width=\"100%\">"
						"<tr BGCOLOR=\"#99CCCC\">"
						"<th>Searches</th><th>Status</th><th>Actions</th></tr>\n";

		for (i=0; i< pSearches->size(); i++) 
		{
			pQuery = (*pSearches)[i]->GetQuery();
			OrigQueryLength = strlen(pQuery) + 1;

			// get the original query (i.e. replacing escaped cahr)
			pOrigQuery = new char[OrigQueryLength];
			clsUtilities::ExcapeToAscii(pQuery, pOrigQuery, OrigQueryLength);
			*mpStream	<<	"<tr><td><font COLOR=\"#000000\" size=\"2\">"
						<<	pOrigQuery
						<<	"</font></td>\n"
							"<td align=\"center\"><small>";
			delete [] pOrigQuery;

			// Get the email ending date
			EndingDay = atoi((*pSearches)[i]->GetStartingDate()) + atoi((*pSearches)[i]->GetEmailDuration()) * ONE_DAY;
			pEndingDayTm = localtime(&EndingDay);

			if (pEndingDayTm)
			{
				strftime(EndingDate, sizeof(EndingDate)-1, "%B %d, %Y", pEndingDayTm);

				if (difftime(time(0), atoi((*pSearches)[i]->GetStartingDate())) 
						> atoi((*pSearches)[i]->GetEmailDuration()) * ONE_DAY)
				{
					*mpStream	<<	"Expired (ended on ";
				}
				else
				{
					*mpStream	<<	"Active (ending on ";
				}
				*mpStream	<<	EndingDate
							<<	")";
			}
			else
			{
				*mpStream	<<	"Status unknown";
			}
			*mpStream	<<	"</small></td>\n" 
							"<td align=\"center\">";
			
			// create the buttons
			EmitSearchButton((*pSearches)[i]);
			EmitModifyButton(pEmail, pPassword, (*pSearches)[i]);
			EmitDeleteButton(pEmail, pPassword, (*pSearches)[i]);
			
			*mpStream	<<	"</td></tr>\n";
		}

		// table ended
		*mpStream	<<	"</table>\n";
	}
}


//
// Emit Search button
//
void clseBayApp::EmitSearchButton(clsPSSearch* pPSSearch)
{
	*mpStream	<<	"<a href=\""
				<<	pPSSearch->GetURLForSearch()
				<<	"\">Search</a>&nbsp;&nbsp;";
/*
	*mpStream	<<	"&nbsp;<form method=\"post\" action=\""
				<<	pPSSearch->GetURLForSearch()
				<<	"\">\n"
					"<input type=\"submit\" value=\"Search\">\n"
					"</form>&nbsp;\n";
*/
}

//
// Emit Modify Button
//
void clseBayApp::EmitModifyButton(const char* pEmail, const char* pPassword, clsPSSearch* pPSSearch)
{
	*mpStream	<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperAddSearch)
				<<	"eBayISAPI.dll?PersonalShopperAddSearch&"
					"userid="
				<<	pEmail
				<<	"&pass="
				<<	pPassword
				<<	"&acceptcookie=0&query="
				<<	pPSSearch->GetQuery()
				<<	"&srchdesc="
				<<	pPSSearch->GetSearchDesc()
				<<	"&minPrice="
				<<	pPSSearch->GetMinPrice()
				<<	"&maxPrice="
				<<	pPSSearch->GetMaxPrice()
				<<	"&psfreq="
				<<	pPSSearch->GetEmailFrequency()
				<<	"&psdura="
				<<	pPSSearch->GetEmailDuration()
				<<	"&psreg="
				<<	pPSSearch->GetReg()
				<<	"\">Modify</a>&nbsp;&nbsp;";
/*	*mpStream	<<	"&nbsp;<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperAddSearch)
				<<	"eBayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" "
				<<	"value=\"PersonalShopperAddSearch\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pEmail
				<<	"\">\n"
					"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"<input type=\"hidden\" name=\"acceptcookie\" value=\"0\">\n"
					"<input type=\"hidden\" name=\"query\" value=\""
				<<	pPSSearch->GetQuery()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"srchdesc\" value=\""
				<<	pPSSearch->GetSearchDesc()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"minPrice\" value=\""
				<<	pPSSearch->GetMinPrice()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"maxPrice\" value=\""
				<<	pPSSearch->GetMaxPrice()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psfreq\" value=\""
				<<	pPSSearch->GetEmailFrequency()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psdura\" value=\""
				<<	pPSSearch->GetEmailDuration()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psreg\" value=\""
				<<	pPSSearch->GetReg()
				<<	"\">\n"
					"<input type=\"submit\" value=\"Modify\">\n"
					"</form>&nbsp;\n";
*/
}

//
// Emit Delete Button
//
void clseBayApp::EmitDeleteButton(const char* pEmail, const char* pPassword, clsPSSearch* pPSSearch)
{
	*mpStream	<<	"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperDeleteSearchView)
				<<	"eBayISAPI.dll?PersonalShopperDeleteSearchView"
					"&userid="
				<<	pEmail
				<<	"&pass="
				<<	pPassword
				<<	"&acceptcookie=0&query="
				<<	pPSSearch->GetQuery()
				<<	"&srchdesc="
				<<	pPSSearch->GetSearchDesc()
				<<	"&minPrice="
				<<	pPSSearch->GetMinPrice()
				<<	"&maxPrice="
				<<	pPSSearch->GetMaxPrice()
				<<	"&psfreq="
				<<	pPSSearch->GetEmailFrequency()
				<<	"&psdura="
				<<	pPSSearch->GetEmailDuration()
				<<	"&psreg="
				<<	pPSSearch->GetReg()
				<<	"\">Delete</a>&nbsp;&nbsp;";

/*	*mpStream	<<	"&nbsp;<form method=\"post\" action=\""
				<<	mpMarketPlace->GetCGIPath(PagePersonalShopperDeleteSearchView)
				<<	"eBayISAPI.dll\">\n"
					"<input type=\"hidden\" name=\"MfcISAPICommand\" "
				<<	"value=\"PersonalShopperDeleteSearchView\">\n"
					"<input type=\"hidden\" name=\"userid\" value=\""
				<<	pEmail
				<<	"\">\n"
					"<input type=\"hidden\" name=\"pass\" value=\""
				<<	pPassword
				<<	"\">\n"
					"<input type=\"hidden\" name=\"acceptcookie\" value=\"0\">\n"
					"<input type=\"hidden\" name=\"query\" value=\""
				<<	pPSSearch->GetQuery()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"srchdesc\" value=\""
				<<	pPSSearch->GetSearchDesc()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"minPrice\" value=\""
				<<	pPSSearch->GetMinPrice()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"maxPrice\" value=\""
				<<	pPSSearch->GetMaxPrice()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psfreq\" value=\""
				<<	pPSSearch->GetEmailFrequency()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psdura\" value=\""
				<<	pPSSearch->GetEmailDuration()
				<<	"\">\n"
					"<input type=\"hidden\" name=\"psreg\" value=\""
				<<	pPSSearch->GetReg()
				<<	"\">\n"
					"<input type=\"submit\" value=\"Delete\">\n"
					"</form>&nbsp;\n";
*/
}
