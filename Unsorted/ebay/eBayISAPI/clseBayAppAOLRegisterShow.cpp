/*	$Id: clseBayAppAOLRegisterShow.cpp	*/
//
//	File:	clseBayAppAOLRegistrationShow.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		Emits the registration form for AOL users.
//
// Modifications:
//				- 06/02/99 lou		- Created
//				- 07/07/99 nsacco	- added siteid and copartnerid
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//

#include "ebihdr.h"
//#include "clseBayUserDemoInfoWidget.h"

// Registration Banner
static const char *RegistrationBannerPart1 =
"<table border=\"1\" width=\"669\" cellspacing=\"0\" bgcolor=\"#99CCCC\" cellpadding=\"2\">"
"<tr>"
"<td width=\"663\"><p align=\"center\"><strong><font size=\"5\">"
"eBay at AOL Registration</font>&nbsp;&nbsp;</strong>\n";

//Name of Image
static const char *RegBannerImage =
"flag-us.gif";

//Leave space for the image that will go along with the text
static const char *RegistrationBannerPart2 =
"</td></tr></table>\n";

//Large Title
static const char *RegistrationLargeTitle =
"<div align=\"left\"><p><big><big><big>eBay "
"Registration - it's free, fast and easy! "
"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</big></big></big></p>"
"</div>\n";

//Directions part 1
static const char *RegistrationDirectionsPart1 =
"<table border=\"0\" cellspacing=\"1\" width=\"90%\">"
"<tr><td width=\"100%\"><font size=\"3\"><strong>\n"
"Welcome";

//Directions part 2
static const char *RegistrationDirectionsPart2 =
"!&nbsp Registering on eBay is easy. &nbsp"
"You can start buying and selling items in no time.&nbsp"
"Registration can be accomplished by following these three basic steps:"
"</strong></font>\n"
"<p><div align=\"left\"><table border=\"1\" width=\"480\" "
"bordercolor=\"#FFFFFF\" bordercolorlight=\"#FFFFFF\" bordercolordark=\"#FFFFFF\"><tr>\n"
"<td width=\"63\"><font face=\"Arial\" size=\"3\" color=\"#000000\">"
"<strong>Step 1: </strong></font></td>\n"
"<td width=\"405\"><strong>"
"Enter and confirm your contact information and user ID</strong></td></tr>\n"
"<tr><td width=\"63\"><font face=\"Arial\" size=\"3\" "
"color=\"#000000\"><strong>Step 2: </strong></font></td>\n"
"<td width=\"405\"><strong>"
"Accept the eBay User Agreement</strong></td></tr>\n"
"<tr><td width=\"63\"><font face=\"Arial\" size=\"3\" color=\"#000000\">"
"<strong>Step 3: </strong></font></td>\n"
"<td width=\"405\"><strong>"
"Retrieve registration confirmation email</strong></td></tr>\n"
"</table></div><p>\n"
"<font size=\"3\" color=\"#FF0000\"><strong>"
"Note: You must be at least 18 years old to register on eBay."
"</strong></font></p></td></tr></table>\n";

//Top part of contact info form Email address
static const char *EnterEmailPart1 =
"<table border=\"1\" width=\"590\" cellspacing=\"0\" cellpadding=\"4\">\n"
"<tr><td width=\"100%\" bgcolor=\"#C0C0C0\" colspan=\"2\">"
"<font size=\"2\"><div align=\"center\"><center>\n"
"</font><strong><font size=\"4\">Enter your contact information</font>"
"</strong></td></tr>\n"
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p>\n"
"<font size=\"3\" color=\"#006600\"><strong>Email address</strong></font><br>"
"<font size=\"2\">e.g., username@aol.com</font></td>\n";

//Rest of Email info
static const char *EnterEmailPart2 =
"<font size=\"2\"><strong><em>Note: </em></strong>\n"
"We suggest you enter your AOL email address here.&nbsp; "
"AOL users have a known email provider and will NOT have to enter credit card \n"
"information to register.&nbsp; If you choose to enter an email address from an "
"unverifiable provider (e.g., yahoo.com, hotmail.com), you will be asked to provide credit \n"
"card information to verify your identity.&nbsp; </font></td>\n";
//"Your card would not be charged.</font></td></tr>\n";

//Full Name
static const char *EnterFullName =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p><font color=\"#006600\"><strong>"
"Full name</strong><br></font><font size=\"2\">e.g., John H. Doe</font>\n"
"<font color=\"#006600\" size=\"2\"></font></td>\n"
"<td width=\"75%\" align=\"left\"><input type=\"text\" name=\"name\""
"size=\"40\" maxlength=\"63\"> <font size=\"2\" color=\"#008000\">"
" (required)</font><br><font size=\"2\">First MI. Last</font></td></tr>\n";

//Company Name
static const char *EnterCompany =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p>Company</td><td width=\"75%\" align=\"left\">"
"<input type=\"text\" name=\"company\" size=\"40\" maxlength=\"63\">"
"<font size=\"2\"> (optional)</font></td></tr>\n";

//Address
static const char *EnterAddress =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p><font color=\"#006600\"><strong>Address"
"</strong></font></td><td width=\"75%\" align=\"left\"><input type=\"text\""
"name=\"address\" size=\"40\" maxlength=\"63\"> <font size=\"2\" "
"color=\"#008000\"> (required)</font></td></tr>\n";


//City
static const char *EnterCity =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p><font color=\"#006600\"><strong>City</strong></font></td>"
"<td width=\"75%\" align=\"left\"><input type=\"text\" name=\"city\" size=\"40\" "
" maxlength=\"63\"><font size=\"2\" color=\"#008000\"> (required)</font></td></tr>\n";

//State
static const char *EnterState =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p><strong><font color=\"#006600\">"
"State</font></strong></td><td width=\"75%\" align=\"left\">\n"
"<select NAME=\"state\" size=\"1\">"
"<option selected VALUE=\"other\">Select State</option>\n"
"<option VALUE=\"AL\">Alabama</option><option VALUE=\"AK\">Alaska</option>\n"
"<option VALUE=\"AZ\">Arizona</option><option VALUE=\"AR\">Arkansas</option>\n"
"<option VALUE=\"CA\">California</option><option VALUE=\"CO\">Colorado</option>\n"
"<option VALUE=\"CT\">Connecticut</option><option VALUE=\"DE\">Delaware</option>\n"
"<option VALUE=\"DC\">District of Columbia</option><option VALUE=\"FL\">Florida</option>\n"
"<option VALUE=\"GA\">Georgia</option><option VALUE=\"HI\">Hawaii</option>\n"
"<option VALUE=\"ID\">Idaho</option><option VALUE=\"IL\">Illinois</option>\n"
"<option VALUE=\"IN\">Indiana</option><option VALUE=\"IA\">Iowa</option>\n"
"<option VALUE=\"KS\">Kansas</option><option VALUE=\"KY\">Kentucky</option>\n"
"<option VALUE=\"LA\">Louisiana</option><option VALUE=\"ME\">Maine</option>\n"
"<option VALUE=\"MD\">Maryland</option><option VALUE=\"MA\">Massachusetts</option>\n"
"<option VALUE=\"MI\">Michigan</option><option VALUE=\"MN\">Minnesota</option>\n"
"<option VALUE=\"MS\">Mississippi</option><option VALUE=\"MO\">Missouri</option>\n"
"<option VALUE=\"MT\">Montana</option><option VALUE=\"NE\">Nebraska</option>\n"
"<option VALUE=\"NV\">Nevada</option><option VALUE=\"NH\">New Hampshire</option>\n"
"<option VALUE=\"NJ\">New Jersey</option><option VALUE=\"NM\">New Mexico</option>\n"
"<option VALUE=\"NY\">New York</option><option VALUE=\"NC\">North Carolina</option>\n"
"<option VALUE=\"ND\">North Dakota</option><option VALUE=\"OH\">Ohio</option>\n"
"<option VALUE=\"OK\">Oklahoma</option><option VALUE=\"OR\">Oregon</option>\n"
"<option VALUE=\"PA\">Pennsylvania</option><option VALUE=\"RI\">Rhode Island</option>\n"
"<option VALUE=\"SC\">South Carolina</option><option VALUE=\"SD\">South Dakota</option>\n"
"<option VALUE=\"TN\">Tennessee</option><option VALUE=\"TX\">Texas</option>\n"
"<option VALUE=\"UT\">Utah</option><option VALUE=\"VT\">Vermont</option>\n"
"<option VALUE=\"VA\">Virginia</option><option VALUE=\"WA\">Washington</option>\n"
"<option VALUE=\"WV\">West Virginia</option><option VALUE=\"WI\">Wisconsin</option>\n"
"<option VALUE=\"WY\">Wyoming</option><option VALUE=\"PR\">Puerto Rico</option>\n"
"<option VALUE=\"VI\">Virgin Island</option><option VALUE=\"MP\">Northern Mariana Islands</option>\n"
"<option VALUE=\"GU\">Guam</option><option VALUE=\"AS\">American Samoa</option>\n"
"<option VALUE=\"PW\">Palau</option></select>\n"
"<font size=\"2\" color=\"#008000\"> (required)</font></td></tr>\n";

//Zip
static const char *EnterZip =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p><strong><font color=\"#006600\">\n"
"Zip Code</font></strong></td>\n"
"<td width=\"75%\" align=\"left\">"
"<input type=\"text\" name=\"zip\" size=\"12\" maxlength=\"12\">"
"<font size=\"2\" color=\"#008000\"> (required)</font></td></tr>\n";

//Primary Phone
static const char *EnterPrimaryPhone =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\"> "
"<div align=\"left\"><p><font color=\"#006600\">\n"
"<strong><u>Primary phone #</u><br></strong></font>\n"
"<font size=\"2\">e.g., (408) 555 - 1234</font>\n"
"<font color=\"#006600\"><strong></strong></font></td>\n"
"<td width=\"75%\" align=\"left\">( <input type=\"text\" "
"name=\"dayphone1\" size=\"4\" maxlength=\"3\"> )\n"
"<input type=\"text\" name=\"dayphone2\" size=\"4\" maxlength=\"3\">\n"
" - <input type=\"text\" name=\"dayphone3\" size=\"5\" maxlength=\"4\">\n"
"<font size=\"2\" color=\"#008000\">(required)</font>\n"
"&nbsp;&nbsp;&nbsp;&nbsp;"
"<input type=\"text\" name=\"dayphone4\" size=\"5\" maxlength=\"10\">\n"
"<font size=\"2\">(extension)</font> </td></tr>\n";

//Secondary Phone
static const char *EnterSecondaryPhone =
"<tr align=\"center\"><td width=\"25%\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\"><p>Secondary phone #</td>\n"
"<td width=\"75%\" align=\"left\">( <input type=\"text\" name=\"nightphone1\" "
"size=\"4\" maxlength=\"3\"> ) <input type=\"text\" name=\"nightphone2\" "
"size=\"4\" maxlength=\"3\"> - <input type=\"text\" name=\"nightphone3\" "
"size=\"5\" maxlength=\"4\"><font size=\"2\"> (optional)</font>\n"
"&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" name=\"nightphone4\" "
"size=\"5\" maxlength=\"10\"><font size=\"2\"> (extension)</font></td></tr>\n";

//Reference
static const char *EnterReference =
"<tr align=\"center\"><td width=\"25%\" valign=\"top\" bgcolor=\"#EFEFEF\">"
"<div align=\"left\">\n"
"<p><font size=\"3\">How did you first hear about eBay?</font></td>\n"
"<td width=\"75%\" align=\"left\"><select NAME=\"Q1\" size=\"1\">\n"
"<option VALUE=\"18\">Business Associate</option><option VALUE=\"17\">Friend or Family Member</option>\n"
"<option VALUE=\"35\">Internet Site</option><option VALUE=\"19\">Media News Story</option>\n"
"<option VALUE=\"36\">Magazine Ad</option><option VALUE=\"37\">Radio Ad</option>\n"
"<option VALUE=\"44\">Talk Show</option><option VALUE=\"21\">Trade Show or Event</option>\n"
"<option VALUE=\"23\">Other</option><option selected VALUE=\"43\">Select here</option>\n"
"</select>&nbsp;<font size=\"2\">(optional)</font></td></tr></table>\n";


//Bottom of form and button
static const char *BottomButton =
"<div align=\"center\"><center><p>&nbsp;</p>"
"</center></div><div align=\"center\"><center>"
"<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"590\">"
"<tr><td align=\"left\"><div align=\"left\"><left>"
"<div align=\"center\"><center><p><input type=\"submit\" value=\" Continue \">"
"&nbsp; </p></center></div></div></td></tr></table></center></div></form>\n";

// Email Register link
static const char *EmailRegisterText =
"<p><br><br><br>If you have any problems registering, send an email to "
"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n";


void clseBayApp::AOLRegisterShow(CEBayISAPIExtension *pExt,
								   char *pAOLName)
{
	int UsingSSL = 0;	//LL: Fix
	int	CountryID = Country_US;	//LL: Fix this also
	
	// Setup
	SetUp();	

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" at AOL Registration"
					"</TITLE>"
					"</HEAD>";

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetHeader();
	else
		*mpStream <<	mpMarketPlace->GetSecureHeader();

	*mpStream  <<	"\n";


	//Display the Registration Banner with image
	*mpStream	<<	RegistrationBannerPart1
				<<	"	<strong><img src=\""
				<<	mpMarketPlace->GetImagePath()
				<<	RegBannerImage
				<<	"\" width=\"60\" height=\"37\" align=\"absmiddle\">"
				<<	"</strong>\n"
				<<	RegistrationBannerPart2;	
	
	//Display Registration Large Title and Directions
	*mpStream	<<	RegistrationLargeTitle
				<<	RegistrationDirectionsPart1;

	//See if we got a AOL Name
	if (!FIELD_OMITTED(pAOLName))
	{
		//Display the AOLUser name
		*mpStream	<<	"&nbsp"
					<<	pAOLName;
	}
	else
	{
		//No name so default it to empty string
		pAOLName = "\0";
	}

	//Finish up with the directions
	*mpStream	<<	RegistrationDirectionsPart2;

	//Set up ISAPI call Register
	*mpStream	<<	"<form method=\"POST\" action=\"";

	//Do we need a SSL
	if (UsingSSL == 0)
		*mpStream	<<  mpMarketPlace->GetCGIPath(PageAOLRegisterPreview);
	else
		*mpStream	<<	mpMarketPlace->GetSSLCGIPath(PageAOLRegisterPreview);


	// TODO - ask Wen for correct siteid and copartnerid
	int siteId = SITE_EBAY_MAIN;
	int coPartnerId = PARTNER_AOL;

	//Finish the ISAPI call with some of the hidden stuff
	*mpStream	<<	"eBayISAPI.dll?\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AOLRegisterPreview\">\n"
				<<	"\n<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
				<<	UsingSSL
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"country\" VALUE=\"United States\">"
				<<	"<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
				<<	CountryID
				<<	"\">\n"
				// nsacco 07/07/99 added siteid and copartnerid
				<<	"<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
				<<	siteId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
				<<	coPartnerId
				<<	"\">\n";


	//Start form to enter contact info
	*mpStream	<<	EnterEmailPart1;

	//Now set up AOLUsername if we have it
	*mpStream	<<	"<td width=\"75%\" align=\"left\">"
				<<	"<input type=\"text\" name=\"email\" size=\"40\""
				<<	"maxlength=\"63\" value=\""
				<<	pAOLName
				<<	"\"><font size=\"2\" color=\"#008000\"> (required)</font><br>\n";

	//Finish Email address
	*mpStream	<<	EnterEmailPart2;
	
	//Finish the form
	*mpStream	<<	EnterFullName
				<<	EnterCompany
				<<	EnterAddress
				<<	EnterCity
				<<	EnterState
				<<	EnterZip
				<<	EnterPrimaryPhone
				<<	EnterSecondaryPhone
				<<	EnterReference;

	
	//Bottom part of form and button
	*mpStream	<<	BottomButton;

	//Add link to register email
	*mpStream	<<	EmailRegisterText;

	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	
	CleanUp();
	return;
}


