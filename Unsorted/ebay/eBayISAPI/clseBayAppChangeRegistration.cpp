/*	$Id: clseBayAppChangeRegistration.cpp,v 1.12.2.3.72.2 1999/08/05 18:58:53 nsacco Exp $	*/
//
//	File:	clseBayAppChangeRegistration.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Update a user's registration information. 
//		
//		** NOTE **
//		Uses ValidateRegistrationInfo in clseBayAppRegister.cpp
//		** NOTE **
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clseBayUserDemoInfoWidget.h"

// Error Messages
static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry. You have not yet confirmed your registration."
"You should have received an E-mail with instructions for "
"confirming your registration. "
"If you did not receive this e-mail, or if you have lost it, "
"please return to "
"<a href=\"http://pages.ebay.com/services/registration/register-by-country.html\">Registration</a>"
" and re-register "
"(with the same e-mail address) to have it sent to "
"you again.";

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry. Registration is blocked for this account. ";

// kakiyama 07/09/99 - commented out
// resourced using clsIntlResource::GetFResString
/*
static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry. There was a problem confirming your registration. "
"Please report this to "
"<a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.";
*/

void clseBayApp::ChangeRegistration(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									char * pName,
									char * pCompany,
									char * pAddress,
									char * pCity,
									char * pState,
									char * pOtherState,
									char * pZip,
									char * pCountry,
									int    countryId,
									char * pDayPhone,
									char * pNightPhone,
									char * pFaxPhone,
									char * pGender,
									int UsingSSL
									)
{
	bool	error		= false;
	char NullStr = '\0';
	CategoryVector				vCategoriesForDemo;
	int UVrating;
	int UVdetail;
	int usersCountry;

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Registration Information"
					"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	//
	// Now let's try the encripted password
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

		*mpStream <<	clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry. You have not yet confirmed your registration."
							"You should have received an E-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}/services/registration/register-by-country.html\">Registration</a>"
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
							"Sorry. There was a problem confirming your registration. "
							"Please report this to "
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
 

	// Ok, user appears to be legit. Let's see if the rest
	// of the information if valid
	error =	ValidateNonRequiredRegistrationInfo(
									 pCompany,
									 pNightPhone,
									 NULL,
									 NULL,
									 NULL,
									 pFaxPhone,
									 NULL,
									 NULL,
									 NULL,
									 pGender,
									 NULL);

	// If we got an error, let's bail
	if (error)
	{
		*mpStream <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();

		return;
	}

	// Check required info again in case someone tried to hack the preview page
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, false, NULL, pName, pAddress, pCity, pState, pZip, pCountry, countryId,
		pDayPhone, NULL, NULL, NULL))
	{
		*mpStream <<	"<p>Sorry, invalid data<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();

		return;
	}

	// Now, let's touch everything. We don't know what's changed,
	// so, we'll change  it all!
	mpUser->SetName(pName);

	if (FIELD_OMITTED(pCompany))
		mpUser->SetCompany("");
	else
		mpUser->SetCompany(pCompany);

	mpUser->SetAddress(pAddress);
	mpUser->SetCity(pCity);

	// We've already set pState to pOtherState in the preview phase if
	// that was the appropriate thing to do.
	mpUser->SetState(pState);
 
	mpUser->SetZip(pZip);

	// Update the user's country info.
	usersCountry = mpUser->GetCountryId();
	if (usersCountry != countryId &&
		countryId != Country_None &&
		usersCountry != Country_None)
	{
		// The user has moved to a new country! Send email to support.
		// Note that we're doing this before updating the user object 
		// or this won't work right, as written.
		// petra 06/28/99 pass in old and new Id - that's neater anyway
// petra 	MailSupportAboutCountryChange(mpUser, pCountry);		
		MailSupportAboutCountryChange(mpUser, mpUser->GetCountryId(), countryId);
	}

	
// petra don't set name in user object
// petra	mpUser->SetCountry(pCountry);

	// Update the user's country id.
	mpUser->SetCountryId(countryId);

	if (FIELD_OMITTED(pDayPhone))
		mpUser->SetDayPhone(NULL);
	else
		mpUser->SetDayPhone(pDayPhone);

	if (FIELD_OMITTED(pNightPhone))
		mpUser->SetNightPhone(NULL);
	else
		mpUser->SetNightPhone(pNightPhone);

	if (FIELD_OMITTED(pFaxPhone))
		mpUser->SetFaxPhone(NULL);
	else
		mpUser->SetFaxPhone(pFaxPhone);

	mpUser->SetGender(pGender);

	// UV stuff
	mpUser->SetUVRating(UVrating);
	mpUser->SetUVDetail(UVdetail);

	mpUser->UpdateUser();


	// Now, we can finally tell the user how wonderful they are
	*mpStream <<	"<h2>Your registered user information has been updated</h2>"
					"Thank you for taking the time to let us know about "
					"changes to your user information. The new information "
					"has been recorded, and is reproduced below for your "
					"reference."
					"<br>";


	// Now, we delete the user object and re-fetch it. The primary
	// reason for this is to make sure the "last modified" date
	// if correct.
	delete mpUser;
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

	// And show it.
//	ShowRegistration(pUserId, pPass);
	*mpStream <<	"<p><table border=\"1\" width=\"590\" "
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
					"<td width=\"75%\">"
			  <<	mpUser->GetName()
			  <<	"</td></tr>";

	pCompany	= mpUser->GetCompany();
	if (!pCompany)
		pCompany	= "&nbsp";

	*mpStream <<	"<tr>"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\">Company</td>"
					"<td width=\"75%\">"
			  <<	pCompany
			  <<	"</td></tr>"
			  <<	"<tr>"
			  <<	"<td width=\"25%\" bgcolor=\"#EFEFEF\">"
			  <<	"<font color=\"#006600\"><strong>Address</strong></font></td>"
			  <<	"<td width=\"75%\">"
			  <<	mpUser->GetAddress()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>City</strong></font></td>"
					"<td width=\"75%\">"
			  <<	mpUser->GetCity()
			  <<	"</td></tr>";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>State, Province, or Region</strong></font></td>"
					"<td width=\"75%\">"
			  <<	mpUser->GetState()
			  <<	"</td></tr>";					
								
					
	*mpStream <<	"<tr>"
					"<td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Postal Code (Zip)</strong></font></td>"
					"<td width=\"75%\"> ";
	if (mpUser->GetZip())
		*mpStream  <<	mpUser->GetZip();

	*mpStream  <<	"</td></tr>";


	*mpStream <<	"<tr> <td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Country</strong></font></td>"
					"<td width=\"75%\">"
// petra don't use country name from user object  <<	mpUser->GetCountry()
			  << 	pCountry
			  <<	"</td>"
					"</tr>";

	pDayPhone	= mpUser->GetDayPhone();
	if (!pDayPhone)
		pDayPhone	= "&nbsp;";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">"
					"<font color=\"#006600\"><strong>Primary phone #</strong></font></td>"
					"<td width=\"75%\">"
			  <<	pDayPhone
			  <<	"</td></tr>";

	pNightPhone	= mpUser->GetNightPhone();
	if (!pNightPhone)
		pNightPhone	= "&nbsp";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Secondary phone #</td>"
					"<td width=\"75%\">"
			  <<	pNightPhone
			  <<	"</td></tr>";

	pFaxPhone	= mpUser->GetFaxPhone();
	if (!pFaxPhone)
		pFaxPhone	= "&nbsp;";

	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Fax #</td>"
					"<td width=\"75%\">"
			  <<	pFaxPhone
			  <<	"</td></tr>";

	pGender	= mpUser->GetGender();
	if (strcmp(pGender, "u") == 0)
		pGender	= "Unspecified";
	if (strcmp(pGender, "m") == 0)
		pGender	= "Male";
	if (strcmp(pGender, "f") == 0)
		pGender	= "Female";



	*mpStream <<	"<tr><td width=\"25%\" bgcolor=\"#EFEFEF\">Gender </td>"
			  <<	"<td width=\"75%\">"
			  <<	pGender;
	
	*mpStream <<	"</td></tr></table></form>";

/*	//optional part, will not show from change registration page now!
	*mpStream <<	"<form><br><table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">";

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
								   mpUser->GetGender(),
								   "u",
								   "Unspecified");
	
	*mpStream <<	"</td></tr></table></form>"; */

	*mpStream <<	"<br>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();

	CleanUp();
	return;
}

void clseBayApp::ChangeRegistrationPreview(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									char * pName,
									char * pCompany,
									char * pAddress,
									char * pCity,
									char * pState,
									char * pOtherState,
									char * pZip,
									int    countryId,
									char * pDayPhone,
									char * pNightPhone,
									char * pFaxPhone,
									char * pGender,
									int UsingSSL
									)
{
	bool	error		= false;
	char NullStr = '\0';
	CategoryVector				vCategoriesForDemo;
	int UVrating;
	int UVdetail;
	clsCountries *pCountries = NULL;
	char pCountry[EBAY_MAX_COUNTRY_SIZE];	// petra

	// Setup
	SetUp();

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Registration Information"
					"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	//
	// Now let's try the encripted password
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

		*mpStream <<	clsIntlResource::GetFResString(-1,
							"<h2>Unconfirmed Registration</h2>"
							"Sorry. You have not yet confirmed your registration."
							"You should have received an E-mail with instructions for "
							"confirming your registration. "
							"If you did not receive this e-mail, or if you have lost it, "
							"please return to "
							"<a href=\"%{1:GetHTMLPath}/services/registration/register-by-country.html\">Registration</a>"
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
							"Sorry. There was a problem confirming your registration. "
							"Please report this to "
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
 

	// Ok, user appears to be legit. Let's see if the rest
	// of the information if valid
	error =	ValidateNonRequiredRegistrationInfo(
									 pCompany,
									 pNightPhone,
									 NULL,
									 NULL,
									 NULL,
									 pFaxPhone,
									 NULL,
									 NULL,
									 NULL,
									 pGender,
									 NULL);

	// If we got an error, let's bail
	if (error)
	{
		*mpStream <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();

		return;
	}

	pCountries = mpMarketPlace->GetCountries();
	if (pCountries)
		pCountries->GetCountryName(countryId, pCountry);
	else
	{
	//	*mpStream  <<	ErrorMsgUnknownState

	// kakiyama 07/07/99

		*mpStream  << clsIntlResource::GetFResString(-1,
							"<h2>Internal Error</h2>"
							"Sorry. There was a problem confirming your registration. "
							"Please report this to "
							"<a href=\"%{1:GetHTMLPath}eBayISAPI.dll?SendQueryEmailShow&subject=registering\">Customer Support</a>.",
							clsIntlResource::ToString(mpMarketPlace->GetHTMLPath(PageSendQueryEmailShow)),
							NULL)
				   <<	"<br>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// If the user is not a US or Canadian resident, set their state to what's in the OtherState field.
	if (countryId != Country_US && countryId != Country_CA)
		strcpy(pState, pOtherState);

	// let users review their required info 
	if (!ValidateAndReviewRequiredInfo(&UVrating, &UVdetail, true, NULL, pName, pAddress, pCity, pState, pZip, pCountry, countryId,
		pDayPhone, NULL, NULL, NULL))
	{
		*mpStream <<	"<p>";

		if (UsingSSL == 0)
			*mpStream <<	mpMarketPlace->GetFooter();
		else
			*mpStream <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		return;
	}

	// Warn the user if they are changing their country. Why? Because we need
	// to be careful about this for at least two reasons:
	// 1. The user will circumvent our most strict user verification checks
	//    by changing to a country outside the U.S.
	// 2. A user might be trying to switch countries to take part in a free
	//    listing promotion when, really, they're not eligible.
// petra don't compare strings when you have ids..
// petra	if (!strcmp(mpUser->GetCountry(), pCountry) == 0)
	if (mpUser->GetCountryId() != countryId)	// petra
	{
		*mpStream << "<p><b>Congratulations on your move to "
			      << pCountry
				  << "!</b> When you submit your new registration information, "
				     "support will receive an email of your move to "
				  << pCountry
				  << " so that they are aware of your move and can provide you even better "
					 "service in your new country."
					 "<p>(If you did not mean to change countries, use the "
					 "back button on your browser to correct your country "
					 "information.)<br>";
	}

	// output hidden fields for ChangeRegistration
	*mpStream << "<br><form method=\"POST\" action=\""
			 <<	"eBayISAPI.dll?\">\n"
				"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"ChangeRegistration\">\n"
				"<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
			 << UsingSSL
			 << "\">\n"
			 << "<input TYPE=\"hidden\" NAME=\"userid\" VALUE=\""
			 << pUserId
			 << "\">\n<input TYPE=\"hidden\" NAME=\"pass\" VALUE=\""
			 << pPass
			 << "\">\n<input TYPE=\"hidden\" NAME=\"name\" VALUE=\""
			 << pName
			 << "\">\n<input TYPE=\"hidden\" NAME=\"company\" VALUE=\""
			 << pCompany
			 << "\">\n<input TYPE=\"hidden\" NAME=\"address\" VALUE=\""
			 << pAddress
			 << "\">\n<input TYPE=\"hidden\" NAME=\"city\" VALUE=\""
			 << pCity
			 << "\">\n<input TYPE=\"hidden\" NAME=\"state\" VALUE=\""
			 << pState
			 << "\">\n<input TYPE=\"hidden\" NAME=\"otherstate\" VALUE=\""
			 << pOtherState
			 << "\">\n<input TYPE=\"hidden\" NAME=\"zip\" VALUE=\""
			 << pZip
			 << "\">\n<input TYPE=\"hidden\" NAME=\"country\" VALUE=\""
			 << pCountry
			 << "\">\n<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
			 << countryId
			 << "\">\n<input TYPE=\"hidden\" NAME=\"dayphone\" VALUE=\""
			 << pDayPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"nightphone\" VALUE=\""
			 << pNightPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"faxphone\" VALUE=\""
			 << pFaxPhone
			 << "\">\n<input TYPE=\"hidden\" NAME=\"gender\" VALUE=\""
			 << pGender
			 << "\">\n"
				"<B>Click the Back button on your browser if you want to change any of the listed information.</B> <p>"
				"<b> Click "
				"<input type=\"submit\" value=\"submit\"> "
				"to commit your changes. </b>\n"
				"</form>\n";

	*mpStream <<	"<br>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();

	CleanUp();
	return;
}

int clseBayApp::MailSupportAboutCountryChange(clsUser *pUser,
						int oldCountryId,	// petra
						int newCountryId)	// petra
// petra					  char *pNewCountry)
{
	clsMail			*pMail;
	ostream			*pMStream;
	char			 pSubject[256];
	int				 mailRc;
	char            *pTo = "legbuds@ebay.com";
	char pOldCountry[EBAY_MAX_COUNTRY_SIZE];	// petra
	char pNewCountry[EBAY_MAX_COUNTRY_SIZE];	// petra

	mpMarketPlace->GetCountries()->GetCountryName(newCountryId, pNewCountry);	// petra
	mpMarketPlace->GetCountries()->GetCountryName(oldCountryId, pOldCountry);	// petra

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Emit
	*pMStream 
		<<	"To Customer Support"
		<<	",\n";


	*pMStream	<<	"The user "
		        <<  pUser->GetUserId()
				<<  " has changed their registered country from "
// petra				<<  pUser->GetCountry()
				<<  pOldCountry	// petra
				<<  " to "
				<<  pNewCountry
				<<  ".\n\n"
				<<  "(This is an automatic message sent from the "
				<<  "Change Registration form to help track whether users "
				<<  "are changing their country information in good faith.)"
				<<  "\n\n";

	*pMStream	<<	flush;

	// Send
	// 07/02/99 nsacco - removed use of mpMarketPlace->GetName()
	sprintf(pSubject, "Registration Country Change to %s",
			pNewCountry);

	mailRc = pMail->Send(pTo, pTo /* from */, pSubject); 

	// All done!
	delete	pMail;

	return mailRc;
}
