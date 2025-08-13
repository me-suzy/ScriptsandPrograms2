/*	$Id: clseBayAppChangeRegistrationShow.cpp,v 1.11.54.3.74.2 1999/08/05 18:58:54 nsacco Exp $	*/
//
//	File:	clseBayAppChangeRegistrationShow.cpp
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
//				- 12/09/97 charles  - Added the function to allow encripted password
//				- 05/14/98 vicki	- new layout
//				- 07/02/99 nsacco	- removed use of mpMarketPlace->GetName()
//				- 07/07/99 nsacco	- set country list to display only 1 row
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//									- Fixed international reg. process in ShowRegistration()
//

#include "ebihdr.h"
#include "clseBayUserDemoInfoWidget.h"
#include "clseBayTimeWidget.h"		// petra

// Error Messages
// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an e-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";
*/

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, Registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

//
// A routine (which others can use) to show registration 
// information.
//
void clseBayApp::ShowRegistration(char *pUserId,
								  char *pPass,
								  int  UsingSSL)
{
	char						*pCompany;
	char						*pDayPhone;
	char						*pNightPhone;
	char						*pFaxPhone;

// petra	time_t						theTime;
// petra	struct tm					*pTheTime;
// petra	char						cTime[64];
// petra	char						cModifiedTime[64];

	CategoryVector				vCategories;
	CategoryVector				vCategoriesForDemo;
	CategoryVector::iterator	vI;
	bool						valueInList;
	bool						isEmail = false;

	ScrollingSelection *pAllCountries;
	clsCountries *pCountries = mpMarketPlace->GetCountries();

#ifdef PURIFY_H_VERSION
    int	  num_bytes_leaked = 0;
    num_bytes_leaked = PurifyNewLeaks();
	PurifyPrintf("1. ShowRegistration(); Number of bytes leaked = %d\n",num_bytes_leaked);
#endif

	*mpStream <<	"<form method=post action="
			  <<	"\""
			  <<	"eBayISAPI.dll"
			  <<	"\""
			  <<	">"
			  <<	"<INPUT TYPE=HIDDEN "
			  <<	"NAME=\"MfcISAPICommand\" "
			  <<	"VALUE=\"ChangeRegistrationPreview\">"
			  <<	"\n";

	// set the ssl value
	*mpStream <<	"<INPUT TYPE=HIDDEN NAME=UsingSSL VALUE=\""
			  <<	UsingSSL
			  <<	"\">\n";

	// Emit the email address and password so someone else can't sneak
	// in and help this user...
	*mpStream <<	"<input type=hidden name=userid value=\""
			  <<	pUserId
			  <<	"\">\n"
			  <<	"<input type=hidden name=pass value=\""
			  <<	pPass
			  <<	"\">\n";

	// Showing the User ID with the link to Change User Id Form
	*mpStream <<	"<table border=\"1\" width=\"590\" "
			  <<	"cellspacing=\"0\" cellpadding=\"4\">"
					"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\"><strong>"
					"<font size=\"3\" color=\"#006600\">E-mail address</font></strong>"
					"</td>"
					"<td width=\"75%\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
					"<tr><td width=\"50%\">";
					
	*mpStream <<	mpUser->GetEmail()
			  <<	"</td>";

	*mpStream <<	"<td width=\"50%\" align=\"right\"><font size=\"2\">("
					"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageChangeEmail)
			  <<	"eBayISAPI.dll?ChangeEmail"
			  <<	"\""
			  <<	">"
					"change</a>"
					" your e-mail address)</font></td>"
					"</tr></table></td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">User ID</td>"
					"<td width=\"75%\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">"
					"<tr><td width=\"50%\">"
			  <<	mpUser->GetUserId()
			  <<	"</td><td width=\"50%\" align=\"right\"><font size=\"2\">("
			  <<	"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageChangeUserId)
			  <<	"eBayISAPI.dll?ChangeUserId&amp;userid="
			  <<	mpUser->GetUserId()
			  <<	"\">change</a>"
			  <<	" your User ID)</font></td>"
			  <<	"</tr></table></td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Full name</strong></font></td>"
					"<td width=\"75%\"><input type=\"text\" name=\"name\" "
					"size=\"40\" " 
			  <<	"maxlength=" << EBAY_MAX_NAME_SIZE << " "
					"value=\""
			  <<	mpUser->GetName()
			  <<	"\">"
					"<font size=\"2\" color=\"#008000\"> (required)</font>"
					"<br><font size=\"2\">First M. Last (for example, John H. Doe)</font></td>"
					"</tr>";

	pCompany	= mpUser->GetCompany();
	if (!pCompany)
		pCompany	= "";

	*mpStream <<	"<tr>"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\">Company</td>"
					"<td width=\"75%\"><input type=\"text\" name=\"company\" "
			  <<	"size=\"40\" "
			  <<	"maxlength=" << EBAY_MAX_COMPANY_SIZE <<  " "
			  <<	"value=\""
			  <<	pCompany
			  <<	"\"></td></tr>"
			  <<	"<tr>"
			  <<	"<td width=\"25%\" bgcolor=\"#EFEFEF\">"
			  <<	"<font color=\"#006600\"><strong>Address</strong></font></td>"
			  <<	"<td width=\"75%\"><input type=\"text\" name=\"address\" "
					"size=\"40\" " 
			  <<	"maxlength=" << EBAY_MAX_ADDRESS_SIZE << " " 
			  <<	"value=\""
			  <<	mpUser->GetAddress()
			  <<	"\">"
			  <<	"<font size=\"2\" color=\"#008000\">(required)</font></td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>City</strong></font></td>"
					"<td width=\"75%\"><input type=\"text\" name=\"city\""
					"size=\"40\" " 
			  <<	"maxlength=" << EBAY_MAX_CITY_SIZE << " "
			  <<	"value=\""
			  <<	mpUser->GetCity()
			  <<	"\">"
			  <<	"<font size=\"2\" color=\"#008000\">(required)</font></td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>State, Province, or Region</strong></font></td>"
					"<td width=\"75%\">";

	valueInList = EmitDropDownList(mpStream,
								   "state",
								   (DropDownSelection *)&StateSelection,
								   mpUser->GetState(),
								   "other",
								   "Not Selected");

	*mpStream <<	"<font size=\"2\" color=\"#008000\"> (required for US and Canada)</font><br>"
					"&nbsp; <font size=\"3\">or region</font>: <input type=\"text\" name=\"otherstate\" size=\"30\" "
					"maxlength=\"63\" ALIGN=\"TOP\"";

	if (!valueInList)
	{
		*mpStream <<	" value=\""
				  <<	mpUser->GetState()
				  <<	"\" ";
	}
	*mpStream <<	"></td></tr>\n";					
						
					
					
	*mpStream <<	"<tr>"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Postal Code (Zip)</strong></font></td>"
					"<td width=\"75%\"><input type=\"text\" name=\"zip\" "
					<<	"size=\"40\" "
			  <<	"maxlength=" << EBAY_MAX_ZIP_SIZE << " "
			  <<	"value=\"";
			  
	if (mpUser->GetZip())
		*mpStream <<	mpUser->GetZip();

	*mpStream  <<	"\"><font size=\"2\" color=\"#008000\">(required)</font></td></tr>";


	*mpStream <<	"<tr> <td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Country</strong></font></td>"
					"<td width=\"75%\">";

	if (pCountries)
	{
		pAllCountries = new ScrollingSelection[pCountries->GetNumCountries() + 1];
			// + 1 for NULL entry at the end

		pCountries->FillScrollingSelection(pAllCountries);

		// nsacco 07/07/99 set country list to show only the user's country
		EmitScrollingList(mpStream,
			              "countryid",
						  1,
						  pAllCountries,					  
						  mpUser->GetCountryId(),
						  true);

		delete [] pAllCountries;
	}

	*mpStream <<	"<font size=\"2\" color=\"#008000\">(required)</font></td>"
					"</tr>";

	pDayPhone	= mpUser->GetDayPhone();
	if (!pDayPhone)
		pDayPhone	= "";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
					"<td width=\"75%\"><input type=\"text\" name=\"dayphone\" "
			  <<	"size=\"40\" "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"value=\""
			  <<	pDayPhone
			  <<	"\"><font size=\"2\" color=\"#008000\">(required)</font>"
			  <<	"<br><font size=\"2\">for example, ";
	
	// nsacco 07/19/99
	switch(mpMarketPlace->GetCurrentSiteId())
	{
	case SITE_EBAY_AU:
		*mpStream << "(02) 5555 5555";
		break;
	case SITE_EBAY_UK:
		*mpStream << "01703-333555";
		break;
	case SITE_EBAY_CA:
		*mpStream << "(702) 555-1234";
		break;
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
	default:
		*mpStream << "(408) 555-1234";
		break;
	}

	*mpStream <<	"</font></td></tr>";

	pNightPhone	= mpUser->GetNightPhone();
	if (!pNightPhone)
		pNightPhone	= "";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Secondary phone #</td>"
					"<td width=\"75%\"><input type=\"text\" name=\"nightphone\" "
			  <<	"size=\"40\" "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"value=\""
			  <<	pNightPhone
			  <<	"\"></td></tr>";

	pFaxPhone	= mpUser->GetFaxPhone();
	if (!pFaxPhone)
		pFaxPhone	= "";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Fax #</td>"
					"<td width=\"75%\"><input type=\"text\" name=\"faxphone\" "
			  <<	"size=\"40\" "
			  <<	"maxlength=" << EBAY_MAX_PHONE_SIZE << " "
			  <<	"value=\""
			  <<	pFaxPhone
			  <<	"\"></td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Gender </td>"
			  <<	"<td width=\"75%\">";
	valueInList = EmitDropDownList(mpStream,
								   "gender",
								   (DropDownSelection *)&GenderSelection,
								   mpUser->GetGender(),
								   "u",
								   "Unspecified"); 
	*mpStream <<	"</td></tr></table>";
   
	//will not show the optional part
	//optional part
/*	*mpStream <<	"<br><table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">";

	// For demographic stuff	
	clseBayUserDemoInfoWidget *pDemoInfoWidget= new clseBayUserDemoInfoWidget(mpMarketPlace, NULL, mpCategories, &vCategoriesForDemo);
	if (pDemoInfoWidget)
	{			
		pDemoInfoWidget->EmitHTML(mpStream, mpUser);
		delete pDemoInfoWidget;
	}

	//put gender into optional table (gender not belong to ebay_user_code table) 
	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Gender </td>"
			  <<	"<td width=\"75%\">";
	valueInList = EmitDropDownList(mpStream,
								   "gender",
								   (DropDownSelection *)&GenderSelection,
								   NULL,
								   "u",
								   "Unspecified"); 

	*mpStream <<	"</td></tr></table>";
*/
// petra	theTime		= mpUser->GetCreated();
// petra	pTheTime	= localtime(&theTime);
// petra	strftime(cTime, sizeof(cTime),
// petra			 "%m/%d/%y %H:%M:%S %Z",
// petra			  pTheTime);

// petra	theTime		= mpUser->GetLastModified();
// petra	pTheTime	= localtime(&theTime);
// petra	strftime(cModifiedTime, sizeof(cModifiedTime),
// petra			 "%m/%d/%y %H:%M:%S %Z",
// petra			  pTheTime);


	*mpStream <<	"\n"
					"Registration date:      ";
	clseBayTimeWidget timeWidget (mpMarketPlace, 1, 2, mpUser->GetCreated() );	// petra
	timeWidget.EmitHTML (mpStream);		// petra
// petra			  <<	cTime
	*mpStream <<	"<p>\n";

	*mpStream <<	"\n"
					"Last change date:       ";
	timeWidget.SetTime (mpUser->GetLastModified () );	// petra
	timeWidget.EmitHTML (mpStream);						// petra
// petra			  <<	cModifiedTime
	*mpStream <<	"\n";
	
	
	*mpStream <<	"<strong><p></strong>By submitting your updated registration "
					"information, you agree to "
					"abide by eBay's "
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"help/community/png-user.html\">"
					"User Agreement</a>. <strong></p>"
					"</strong><blockquote>"
					"<p><strong>Click&nbsp; "
					"<input type=submit value=\"change registration information\">"
					"&nbsp; to submit your changes."
					"</strong></p>"
					"<p>Click&nbsp; <input type=\"reset\" value=\"clear form\">&nbsp; "
					"to reset the form to the original values and start over.</p> "
					"</blockquote>"
					"</form>";
					
					
					
	// Clean up Category Vector
	for (vI	= vCategories.begin();
		 vI	!= vCategories.end();
		 vI++)
	{
		delete	(*vI);
	}

	vCategories.erase(vCategories.begin(), vCategories.end());

	for (vI	= vCategoriesForDemo.begin();
		 vI	!= vCategoriesForDemo.end();
		 vI++)
	{
		delete	(*vI);
	}

	vCategoriesForDemo.erase(vCategoriesForDemo.begin(), vCategoriesForDemo.end());

#ifdef PURIFY_H_VERSION
    num_bytes_leaked = PurifyNewLeaks();
	PurifyPrintf("2. ShowRegistration(); Number of bytes leaked = %d\n",num_bytes_leaked);
#endif
	return;
}

void clseBayApp::ChangeRegistrationShow(CEBayISAPIExtension *pServer,
										char * pUserId,
										char * pPass,
										int UsingSSL)
{

#ifdef PURIFY_H_VERSION
    int	  num_bytes_leaked = 0;
#endif

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Registration"
					"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	// The last parameter allows the method to check if the password 
	// is the encrypted one stored in the database
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream, true, NULL,
													false, false, false, true);
	if (!mpUser)
	{
		*mpStream	<<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// We got the user. Let's ensure they're in the right
	// state.
	if (!mpUser->IsConfirmed())
	{
	//	*mpStream <<	ErrorMsgNotConfirmed

	// kakiyama 07/07/99

		*mpStream <<    clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry, you have not yet confirmed your registration."
							"You should have received an e-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}services/registration/register-by-country.html\">Registration</a>"
							" and re-register "
							"(with the same e-mail address) to have it sent to "
							"you again.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath()),
							NULL)
				  <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();
		
		CleanUp();
		return;
	}

	if (mpUser->IsSuspended())
	{
		*mpStream <<	ErrorMsgSuspended
				  <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	if (!mpUser->IsConfirmed())
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream  << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
				                        "Sorry, there was a problem confirming your registration. "
					                "<a href=\"%{1:GetCGIPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetCGIPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	if (!mpUser->HasDetail())
	{
		*mpStream <<	"<h2>Error</h2>"
						"Our records do not show registration information for "
				  <<	mpUser->GetUserId()
				  <<	" on file. This is an <font color=red><b>error</b></font> "
						"and should be reported to "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
						"<p>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// Allll righty! Let's start emitting the user..
	*mpStream <<	"<br>"
					"<table border=\"1\" width=\"590\" cellspacing=\"0\" bgcolor=\"#99CCCC\" cellpadding=\"2\">"
					"<tr><td>"
					"<p align=\"center\"><strong><font size=\"5\">eBay Change Registration Information</font></strong>"
					"</td></tr></table>"
					"<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"590\">"
					"<tr><td>"
					"Thank you for letting us know about changes to your user information. "
					"Please make any changes necessary, "
					"and then click the submit button on the bottom of the page."
					"</td></tr></table>"
					"<p><strong>Required entries </strong>are shown in "
					"<font color=\"#006600\"><strong>green</strong></font>.";

	ShowRegistration(pUserId, pPass, UsingSSL);

	*mpStream <<	"<br>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();

	CleanUp();
	return;
}

