/*	$Id: clseBayAppBetaConfirmationShow.cpp,v 1.6.166.1.108.2 1999/08/05 20:42:10 nsacco Exp $	*/
//
//	File:	clseBayAppBetaConfirmationShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Handle a registration request
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 12/23/97 charles  - Changed
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
//#include "clseBayUserDemoInfoWidget.h"


// Error Messages
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. If you did not receive this "
"mail, or lost it, please return to the registration page and "
"re-register (with the same user ID and e-mail address) to have it sent to "
"you again."
"<br>";

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. ";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this along with all pertinent information (your selected "
"userid, e-mail, name, address, etc.) to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=system%20technical%20issue\">Customer Support</a>.";


//
// A routine (which others can use) to show registration 
// information.
//
void clseBayApp::ShowConfirmation(char *pUserId,
								  char *pPass)
{
	time_t						theTime;
	struct tm					*pTheTime;
	char						cTime[64];
	char						cModifiedTime[64];
	CategoryVector				vCategories;

	ScrollingSelection *pAllCountries;
	clsCountries *pCountries = mpMarketPlace->GetCountries();

	*mpStream <<	"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageBetaConfirmationPreview)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfcISAPICommand\" "
					"VALUE=\"BetaConfirmationPreview\">"
					"\n";


	// Emit the email address and password so someone else can't sneak
	// in and help this user...
	*mpStream <<	"<input type=hidden name=userid value=\""
			  <<	pUserId
			  <<	"\">\n"
			  <<	"<input type=hidden name=email value=\""
			  <<	mpUser->GetEmail()
			  <<	"\">\n"
			  <<	"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n";

	// Now, the goods
	*mpStream <<	"<p>"
					"<pre>";

	// Showing the User ID 
	*mpStream <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" User ID:           "
			  <<	mpUser->GetUserId()
			  <<	"\n";


	*mpStream <<	"E-mail address:         "
			  <<	mpUser->GetEmail()
			  <<	"\n";

	*mpStream <<	"Full name:              "
					"<input type=text name=name "
					"size=" << EBAY_MAX_NAME_SIZE << " " 
			  <<	"maxlength=" << EBAY_MAX_NAME_SIZE << " "
			  <<	">\n";

	*mpStream <<	"Company (optional):     "
					"<input type=text name=company "
			  <<	"size=" << EBAY_MAX_COMPANY_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_COMPANY_SIZE <<  " "
			  <<	">\n";

	*mpStream <<	"Address:                "
					"<input type=text name=address "
					"size=" << EBAY_MAX_ADDRESS_SIZE << " " 
			  <<	"maxlength=" << EBAY_MAX_ADDRESS_SIZE << " " 
			  <<	">\n";

	*mpStream <<	"City:                   "
					"<input type=text name=city "
					"size=" << EBAY_MAX_CITY_SIZE << " " 
			  <<	"maxlength=" << EBAY_MAX_CITY_SIZE << " "
			  <<	">\n";

	*mpStream <<	"State or Province:      ";
	EmitDropDownList(mpStream,
				    "state",
				    (DropDownSelection *)&StateSelection,
				    NULL,
				    "other",
				    "Not Selected");

	*mpStream <<	"\n";

	*mpStream <<	"(or region:             "
					"<input type=text name=otherstate "
			  <<	"size=" << EBAY_MAX_STATE_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_STATE_SIZE << " "
					">)\n";



	*mpStream <<	"Postal Code (Zip):      "
					"<input type=text name=zip "
			  <<	"size=" << EBAY_MAX_ZIP_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_ZIP_SIZE << " "
			  <<	">\n";
	
	*mpStream <<	"Country:                ";

	if (pCountries)
	{
		pAllCountries = new ScrollingSelection[pCountries->GetNumCountries() + 1];
			// + 1 for NULL entry at the end

		pCountries->FillScrollingSelection(pAllCountries);

		EmitScrollingList(mpStream,
			              "countryid",
						  5,
						  pAllCountries,					  
						  mpUser->GetCountryId(),
						  true);

		delete [] pAllCountries;
	}

	*mpStream <<	"\n";


	*mpStream <<	"Daytime Phone number:   "
					"<input type=text name=dayphone "
			  <<	"size=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	">\n";


	*mpStream <<	"Nighttime Phone number: "
					"<input type=text name=nightphone "
			  <<	"size=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	">\n";

	*mpStream <<	"Fax Phone number:       "
					"<input type=text name=faxphone "
			  <<	"size=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	">\n";


	*mpStream <<	"\n"
			  <<	"<b>Optional</b> Demographic Information:\n"
			  <<	"\n";

	*mpStream <<	"Gender:                 ";
	EmitDropDownList(mpStream,
					 "gender",
					 (DropDownSelection *)&GenderSelection,
					 NULL,
					 "u",
					 "Unspecified");

	*mpStream <<	"\n";
/*
	*mpStream <<	"Areas of interest:      ";

	mpCategories->EmitHTMLLeafSelectionList(mpStream,
											"interest1",
											NULL,
											"0",
											"Not Selected",
											&vCategories, false, true);

	*mpStream <<	"\n"
					"                        ";
	mpCategories->EmitHTMLLeafSelectionList(mpStream,
											"interest2",
											NULL,
											"0",
											"Not Selected",
											&vCategories, false, true);

	*mpStream <<	"\n"
					"                        ";
	mpCategories->EmitHTMLLeafSelectionList(mpStream,
											"interest3",
											NULL,
											"0",
											"Not Selected",
											&vCategories, false, true);

	*mpStream <<	"\n"
					"                        ";
	mpCategories->EmitHTMLLeafSelectionList(mpStream,
											"interest4",
											NULL,
											"0",
											"Not Selected",
											&vCategories, false, true);
*/

	// For demographic stuff	
/*	clseBayUserDemoInfoWidget *pDemoInfoWidget= new clseBayUserDemoInfoWidget(mpMarketPlace, NULL);
	if (pDemoInfoWidget)
		{			
			pDemoInfoWidget->EmitHTML(mpStream);
			delete pDemoInfoWidget;
		}*/

	theTime		= time(0);     // mpUser->GetCreated();
	pTheTime	= localtime(&theTime);
	strftime(cTime, sizeof(cTime),
			 "%m/%d/%y %H:%M:%S %Z",
			  pTheTime);

	theTime		= mpUser->GetLastModified();
	pTheTime	= localtime(&theTime);
	strftime(cModifiedTime, sizeof(cModifiedTime),
			 "%m/%d/%y %H:%M:%S %Z",
			  pTheTime);


	*mpStream <<	"\n"
					"Registration date:      "
			  <<	cTime
			  <<	"\n";

	*mpStream <<	"\n"
					"Last change date:       "
			  <<	cModifiedTime
			  <<	"\n";
	
	*mpStream <<	"\n"
					"</pre>"
					"\n"
					"<strong>Click this button to confirm your eBay registration:</strong>"
					"<p>"
					"<blockquote>"
					"<input type=submit value=\"confirm registration information\">"
					"</blockquote>"
					"<p>"
					"\n"
					"Click this button to clear and start all over:"
					"<p>"
					"<blockquote>"
					"<input type=reset value=\"clear form\">"
					"</blockquote>"
					"</form>\n";

	vCategories.erase(vCategories.begin(), vCategories.end());


	return;
}

void clseBayApp::BetaConfirmationShow(CEBayISAPIExtension *pServer,
									  char * pUserId,
									  char * pPass)
{

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<html><head><TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Beta Registration Confirmation"
					"</TITLE></head>"
			  <<	"\n"
			  <<	mpMarketPlace->GetHeader()
			  <<	"<p>";


	// Let's get the user.
	_strlwr(pUserId);
	mpUser = mpUsers->GetUser(pUserId);
	if (!mpUser)
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" user, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// Let's check the password
	_strlwr(pPass);
	if (!mpUser->TestPass(pPass))
	{
		*mpStream <<	"<h2>Invalid User or Password</h2>"
						"Sorry, "
				  <<	pUserId
				  <<	" does not appear to be a valid "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" userid, or the password is invalid. "
						"Please go back and try again."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();

		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (mpUser->IsConfirmed())
	{
		*mpStream <<	"<h2>eBay Beta Registration already confirmed!</h2>"
						"Our records indicate that you have already confirmed "
						"your eBay Beta registration, and there is no need to "
						"do so again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<h2>eBay Beta Registration blocked</h2>"
						"Our records indicate that your current eBay registration "
						"is blocked. Please resolve any outstanding issues and "
						"try again."
				  <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (mpUser->GetUserState() != UserGhost)
	{
		*mpStream  <<	"<h2>eBay Beta Registration unknown state</h2>"
						"Your userid is in unknown state, and we are unable "
						"to confirm your Beta registration. Please contact "
					<<  mpMarketPlace->GetSupportEmail()
				   <<	"<br>"
				   <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Allll righty! Let's start emitting the user..
	*mpStream <<	"<h2>Confirming your eBay Beta registration</h2>"
					"Thank you for participating in the Beta test of the new eBay! "
					"Before you proceed, we'd like you to help us ensure that your  "
					"registration information is accurate by completing this page. "
					"<p>"
					"<b>Note:</b> You must supply either a daytime or nighttime phone "
					"number, or both. "
					"<p>"
					"<b>Note:</b> The demographic information is <i>optional</i>, and is "
					"subject to the eBay "
					"<A HREF="
					"\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/registration/register-by-country.html"
					"\""
					">"
					"privacy notice"
					"</a>"
					"."
					"<p>";

	// Show the stuff!
	ShowConfirmation(pUserId, pPass);

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();


	CleanUp();
	return;
}

