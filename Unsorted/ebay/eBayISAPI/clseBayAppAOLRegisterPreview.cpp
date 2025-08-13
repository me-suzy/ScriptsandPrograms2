/*	$Id: clseBayAppAOLRegisterPreview.cpp	*/
//
//	File:	clseBayAppAOLRegisterPreview.cpp
//
//	Class:	clseBayApp
//
//	Author:	Lou Leonardo (lou@ebay.com)
//
//	Function:
//
//		PreView Registration
//
// Modifications:
//				- 06/03/99 Lou	- Created
//				- 07/07/99 nsacco - added siteId and coPartnerId
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//

#include "ebihdr.h"
#include "clsUserVerificationServices.h"
#include "clsCountries.h"
#include "clsInternationalUtilities.h"

//
// Support for our own personal crypt
#include "malloc.h"		// Crypt uses malloc
extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

// Header text
static const char *HeaderText[] =
{
" at AOL Registration - Confirm contact info",
" at AOL Registration - Error in contact info"
};

// Title text
static const char *TitleText[] =
{
"<p><font size=\"4\"><strong>&nbsp;&nbsp; Review and confirm your "
"contact information.</strong></font>\n"
"<p><strong><font size=\"4\">&nbsp;&nbsp; </font><font size=\"3\">"
"If all the information you entered is accurate, click on the Confirm "
"button at the bottom of the page.</font></strong></p>\n",

"<p><font color=\"#FF0000\" size=\"4\"><strong>&nbsp;&nbsp; Errors were "
"detected in your contact information.&nbsp; </strong></font></p>\n"
"<p><font size=\"4\"><strong>&nbsp;&nbsp; Make the appropriate corrections "
" below.</strong></font></p>\n"
};

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

//Top of Table Part 1
static const char *TableTop1 =
"<table border=\"1\" width=\"595\" cellspacing=\"0\" cellpadding=\"4\">\n"
"<td width=\"585\" colspan=\"2\"><strong><font size=\"3\"><div align=\"center\">"
"<center><p></font><big>\n";

//Top of Table Part 2
static const char *TableTop2 =
"</big></strong></td></tr>\n";

//Table Title
static const char *TableTitle[] =
{
"Review and confirm your contact information",
"Correct the information highlighted in red"
};

//This works for all the text in the left side of the table
static const char *TableLeftSide =
"<tr align=\"center\"><td width=\"164\" align=\"left\">"
"<strong><font size=\"3\"";

//Email Text
static const char *TableEmail1 =
">Email address</font></strong><br><font size=\"2\">e.g., username@aol.com</font></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableEmailData =
"<input type=\"text\" name=\"email\" size=\"33\" maxlength=\"63\" value=\"";

//Name Text
static const char *TableName1 =
">Full name</font></strong><br><font size=\"2\">e.g., John H. Doe</font></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableNameData =
"<input type=\"text\" name=\"name\" size=\"33\" maxlength=\"63\" value=\"";

//Company Text
static const char *TableCompany1 =
">Company</font></strong></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableCompanyData =
"<input type=\"text\" name=\"company\" size=\"33\" maxlength=\"63\" value=\"";

//Address Text
static const char *TableAddress1 =
">Address</font></strong></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableAddressData =
"<input type=\"text\" name=\"address\" size=\"33\" maxlength=\"63\" value=\"";

//City Text
static const char *TableCity1 =
">City</font></strong></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableCityData =
"<input type=\"text\" name=\"city\" size=\"33\" maxlength=\"63\" value=\"";

//State Text
static const char *TableState1 =
">State</font></strong></td>\n"
"<td width=\"411\" align=\"left\"><select NAME=\"state\" size=\"1\">\n";

//Zip Text
static const char *TableZip1 =
">Zip</font></strong></td>\n"
"<td width=\"411\" align=\"left\">";

static const char *TableZipData =
"<input type=\"text\" name=\"zip\" size=\"33\" maxlength=\"63\" value=\"";

//Primary Phone
static const char *TablePrimaryPhone1 =
">Primary phone #</font></strong><br><font size=\"2\">e.g., (408) 555 - 1234</font></td>\n"
"<td width=\"411\" align=\"left\">(&nbsp;";

static const char *TablePrimaryPhoneData1 =
"<input type=\"text\" name=\"dayphone1\" size=\"4\" maxlength=\"3\" value=\"";

static const char *TablePrimaryPhoneData2 =
"\"> ) <input type=\"text\" name=\"dayphone2\" size=\"4\" maxlength=\"3\" value=\"";

static const char *TablePrimaryPhoneData3 =
"\"> - <input type=\"text\" name=\"dayphone3\" size=\"5\" maxlength=\"4\" value=\"";

static const char *TablePrimaryPhoneData4 =
"\">&nbsp;Extension:&nbsp; <input type=\"text\" name=\"dayphone4\" size=\"5\" maxlength=\"10\" value=\"";

//Secondary Phone
static const char *TableSecondaryPhone1 =
">Secondary phone #</font></strong></td>\n"
"<td width=\"411\" align=\"left\">(&nbsp;";

static const char *TableSecondaryPhoneData1 =
"<input type=\"text\" name=\"nightphone1\" size=\"4\" maxlength=\"3\" value=\"";

static const char *TableSecondaryPhoneData2 =
"\"> ) <input type=\"text\" name=\"nightphone2\" size=\"4\" maxlength=\"3\" value=\"";

static const char *TableSecondaryPhoneData3 =
"\"> - <input type=\"text\" name=\"nightphone3\" size=\"5\" maxlength=\"4\" value=\"";

static const char *TableSecondaryPhoneData4 =
"\">&nbsp;Extension:&nbsp; <input type=\"text\" name=\"nightphone4\" size=\"5\" maxlength=\"10\" value=\"";

//Referal Text
static const char *TableReferalText =
">How did you first hear about eBay?</font></strong></td>\n"
"<td width=\"411\" align=\"left\"><select NAME=\"Q1\" size=\"1\">\n";

//Bottom text and confirm button
static const char *BottomTextAndButton =
"<div align=\"center\"><center><table width=\"595\" cellspacing=\"0\" "
"cellpadding=\"4\" height=\"131\"><tr><td valign=\"top\" height=\"123\" "
"align=\"left\"><div align=\"center\"><center><p>\n"
"<strong>Note:</strong> Make sure your mail filters are off or set to accept "
"mail from ebay.com. For more information, go to AOL keyword &quot;mailcontrol&quot;"
" (click the &quot;Keyword&quot; button on the upper-right corner of your AOL screen).\n"
"</center></div><div align=\"center\"><center><p><br><br>"
"<input type=\"submit\" value=\"   Confirm   \"></td></tr></table></center></div>"
"</form></td></tr></table>\n";

// Email Register link
static const char *EmailRegisterText =
"<p><br><br><br>If you have any problems registering, send an email to "
"<a href=\"mailto:register@ebay.com\">register@ebay.com</a>\n";

//Email Error Text
static char *EmailErrorText =
"<br><strong><font color=\"red\">Enter a valid email address</font></strong>";

//Name Error Text
static char *NameErrorText =
"<br><strong><font color=\"red\">Enter your full name</font></strong>";

static char *NameToLongErrorText =
"<br><strong><font color=\"red\">The name you entered was to long</font></strong>";

//Address Error Text
static char *AddressErrorText =
"<br><strong><font color=\"red\">Enter your complete address</font></strong>";

//City Error Text
static char *CityErrorText =
"<br><strong><font color=\"red\">Enter the correct city</font></strong>";

//State Error Text
static char *StateErrorText =
"<br><strong><font color=\"red\">Select a state</font></strong>";

//Zip Error Text
static char *ZipErrorText =
"<br><strong><font color=\"red\">Enter a valid zip code</font></strong>";

//Phone# Error Text
static char *PhoneErrorText =
"<br><strong><font color=\"red\">Enter a complete phone number with are code</font></strong>";

char	*pEmailError;
char	*pNameError;
char	*pAddressError;
char	*pCityError;
char	*pStateError;
char	*pZipError;
char	*pPhoneError;

// nsacco 07/07/99 added siteId and coPartnerId
void clseBayApp::AOLRegisterPreview(CEBayISAPIExtension *pServer,
							char * pUserId,
							char * pEmail,
							char * pName,
							char * pCompany,
							char * pAddress,
							char * pCity,
							char * pState,
							char * pZip,
							char * pCountry,
							int countryId,
							char * pDayPhone1,
							char * pDayPhone2,
							char * pDayPhone3,
							char * pDayPhone4,
							char * pNightPhone1,
							char * pNightPhone2,
							char * pNightPhone3,
							char * pNightPhone4,
							char * pFaxPhone1,
							char * pFaxPhone2,
							char * pFaxPhone3,
							char * pFaxPhone4,
							char * pGender,
							int referral,
						    char * pTradeshow_source1,
						    char * pTradeshow_source2,
						    char * pTradeshow_source3,
						    char * pFriend_email,
						    int purpose,
						    int interested_in,
						    int age,
						    int education,
						    int income,
						    int survey,
							LPTSTR pNewPass,
							LPTSTR pNewPass2,
							int nPartnerId,
							int siteId,
							int coPartnerId,
							int UsingSSL,
							int nVerify
							)
{
	ostrstream   tempStream;
	ostream		*pTempStream; 

	bool	bGood = false;
	int		UVrating = 0;
	int		UVdetail = 0;

	char	szEmptyStr[] = "";
	
	//Init the strings
	pEmailError = szEmptyStr;
	pNameError = szEmptyStr;
	pAddressError = szEmptyStr;
	pCityError = szEmptyStr;
	pStateError = szEmptyStr;
	pZipError = szEmptyStr;
	pPhoneError = szEmptyStr;

	// Setup
	SetUp();	

	//Force email text to lower case first
	clsUtilities::StringLower(pEmail);

	//See if we are going to do the verify part now.
	if (nVerify)
	{
		//Setup the stream
		pTempStream = &tempStream;

		//Call the verify method
		bGood = AOLValidateRegistration(&UVrating, &UVdetail, pEmail, pName, pAddress,
										pCity, pState, pZip, pCountry, countryId,
										pDayPhone1, pDayPhone2,	pDayPhone3,
										pDayPhone4, pTempStream);

		//See if there were any problems
		if (bGood)
		{
			// pUserId is null so we will use the E-mail as the ID
			pUserId = pEmail;

			//Everything checked out ok... SwitchGears to the next Reg page.
			CleanUp();

			//Set the page of who we are going to call....like eBayISAPI does
			SetCurrentPage(PageRegisterPreview);

			// nsacco 07/07/99 added siteId and coPartnerId
			AOLRegisterUserID(pServer,
								pUserId,
								pEmail,
								(char *) pName,
								(char *) pCompany,
								(char *) pAddress,
								(char *) pCity,
								(char *) pState,
								(char *) pZip,
								(char *) pCountry,
							   countryId,
							   (char *) pDayPhone1,
							   (char *) pDayPhone2,
							   (char *) pDayPhone3,
							   (char *) pDayPhone4,
							   (char *) pNightPhone1,
							   (char *) pNightPhone2,
							   (char *) pNightPhone3,
							   (char *) pNightPhone4,
							   (char *) pFaxPhone1,
							   (char *) pFaxPhone2,
							   (char *) pFaxPhone3,
							   (char *) pFaxPhone4,
							   (char *) pGender,
							   referral,
							   (char *) pTradeshow_source1,
							   (char *) pTradeshow_source2,
							   (char *) pTradeshow_source3,
							   (char *) pFriend_email,
							   purpose,
							   interested_in,
							   age,
							   education,
							   income,
							   survey,   
							   pNewPass,
							   pNewPass2,
							   nPartnerId,
							   siteId,
							   coPartnerId,
							   UsingSSL
							   );
			return;
		}
		else
		{
			//Error happened when we verified the data

		}
		
	}

	// Whatever happens, we need a title and a standard header
	*mpStream	<<	"<HTML>"
				<<	"<HEAD>"
				<<	"<TITLE>"
				<<	mpMarketPlace->GetCurrentPartnerName()
				<<	HeaderText[nVerify]
				<<	"</TITLE>"
				<<	"</HEAD>";

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


	//1st time here, so just show the info to the user so they can confirm
	*mpStream << "<br><form method=\"POST\" action=\"";

	//Do we need a SSL
	if (UsingSSL == 0)
		*mpStream	<<  mpMarketPlace->GetCGIPath(PageAOLRegisterPreview);
	else
		*mpStream	<<	mpMarketPlace->GetSSLCGIPath(PageAOLRegisterPreview);


	//Setup the call
	// nsacco 07/07/99 added siteId and coPartnerId
	*mpStream	<<	"eBayISAPI.dll?\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AOLRegisterPreview\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"UsingSSL\" VALUE=\""
				<<	UsingSSL
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"country\" VALUE=\""
				<<	pCountry
				<<	"\">"
				<<	"<input TYPE=\"hidden\" NAME=\"countryid\" VALUE=\""
				<<	countryId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"partnerId\" VALUE=\""
				<<	nPartnerId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"verify\" VALUE=\"1\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"siteid\" VALUE=\""
				<<	siteId
				<<	"\">\n"
				<<	"<input TYPE=\"hidden\" NAME=\"copartnerid\" VALUE=\""
				<<	coPartnerId
				<<	"\">\n";

	//Display the Title
	*mpStream	<<	TitleText[nVerify];

	//Start building the table top and title
	*mpStream	<<	TableTop1
				<<	TableTitle[nVerify]
				<<	TableTop2;

	//Add Email Block
	*mpStream	<<	TableLeftSide
				<<	TableEmail1
				<<	TableEmailData
				<<	pEmail
				<<	"\">"
				<<	pEmailError
				<<	"</td></tr>\n";

	//Add Name Block
	*mpStream	<<	TableLeftSide
				<<	TableName1
				<<	TableNameData
				<<	pName
				<<	"\">"
				<<	pNameError
				<<	"</td></tr>\n";

	//Add Company Block
	*mpStream	<<	TableLeftSide
				<<	TableCompany1
				<<	TableCompanyData
				<<	pCompany
				<<	"\"></td></tr>\n";

	//Add Address Block
	*mpStream	<<	TableLeftSide
				<<	TableAddress1
				<<	TableAddressData
				<<	pAddress
				<<	"\">"
				<<	pAddressError
				<<	"</td></tr>\n";

	//Add City Block
	*mpStream	<<	TableLeftSide
				<<	TableCity1
				<<	TableCityData
				<<	pCity
				<<	"\">"
				<<	pCityError
				<<	"</td></tr>\n";

	//Add State Block
	*mpStream	<<	TableLeftSide
				<<	TableState1;

	//Now build the combobox and select the default item 
	if (strcmp(pState, "other") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"other\">Select State</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"other\">Select State</OPTION>";

	if (strcmp(pState, "AL") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"AL\">Alabama</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"AL\">Alabama</OPTION>";

	if (strcmp(pState, "AK") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"AK\">Alaska</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"AK\">Alaska</OPTION>";

	if (strcmp(pState, "AZ") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"AZ\">Arizona</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"AZ\">Arizona</OPTION>";

	if (strcmp(pState, "CA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"CA\">California</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"CA\">California</OPTION>";

	if (strcmp(pState, "CO") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"CO\">Colorado</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"CO\">Colorado</OPTION>";

	if (strcmp(pState, "CT") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"CT\">Connecticut</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"CT\">Connecticut</OPTION>";

	if (strcmp(pState, "DE") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"DE\">Delaware</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"DE\">Delaware</OPTION>";

	if (strcmp(pState, "DC") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"DC\">District of Columbia</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"DC\">District of Columbia</OPTION>";

	if (strcmp(pState, "FL") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"FL\">Florida</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"FL\">Florida</OPTION>";

	if (strcmp(pState, "GA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"GA\">Georgia</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"GA\">Georgia</OPTION>";

	if (strcmp(pState, "HI") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"HI\">Hawaii</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"HI\">Hawaii</OPTION>";

	if (strcmp(pState, "ID") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"ID\">Idaho</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"ID\">Idaho</OPTION>";

	if (strcmp(pState, "IL") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"IL\">Illinois</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"IL\">Illinois</OPTION>";

	if (strcmp(pState, "IN") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"IN\">Indiana</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"IN\">Indiana</OPTION>";

	if (strcmp(pState, "IA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"IA\">Iowa</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"IA\">Iowa</OPTION>";

	if (strcmp(pState, "KS") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"KS\">Kansas</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"KS\">Kansas</OPTION>";

	if (strcmp(pState, "KY") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"KY\">Kentucky</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"KY\">Kentucky</OPTION>";

	if (strcmp(pState, "LA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"LA\">Louisiana</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"LA\">Louisiana</OPTION>";

	if (strcmp(pState, "ME") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"ME\">Maine</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"ME\">Maine</OPTION>";

	if (strcmp(pState, "MD") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MD\">Maryland</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MD\">Maryland</OPTION>";

	if (strcmp(pState, "MA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MA\">Massachusetts</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MA\">Massachusetts</OPTION>";

	if (strcmp(pState, "MI") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MI\">Michigan</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MI\">Michigan</OPTION>";

	if (strcmp(pState, "MN") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MN\">Minnesota</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MN\">Minnesota</OPTION>";

	if (strcmp(pState, "MS") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MS\">Mississippi</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MS\">Mississippi</OPTION>";

	if (strcmp(pState, "MO") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MO\">Missouri</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MO\">Missouri</OPTION>";

	if (strcmp(pState, "MT") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MT\">Montana</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MT\">Montana</OPTION>";

	if (strcmp(pState, "NE") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NE\">Nebraska</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NE\">Nebraska</OPTION>";

	if (strcmp(pState, "NV") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NV\">Nevada</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NV\">Nevada</OPTION>";

	if (strcmp(pState, "NH") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NH\">New Hampshire</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NH\">New Hampshire</OPTION>";

	if (strcmp(pState, "NJ") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NJ\">New Jersey</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NJ\">New Jersey</OPTION>";

	if (strcmp(pState, "NM") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NM\">New Mexico</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NM\">New Mexico</OPTION>";

	if (strcmp(pState, "NY") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NY\">New York</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NY\">New York</OPTION>";

	if (strcmp(pState, "NC") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"NC\">North Carolina</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"NC\">North Carolina</OPTION>";

	if (strcmp(pState, "ND") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"ND\">North Dakota</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"ND\">North Dakota</OPTION>";

	if (strcmp(pState, "OH") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"OH\">Ohio</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"OH\">Ohio</OPTION>";

	if (strcmp(pState, "OK") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"OK\">Oklahoma</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"OK\">Oklahoma</OPTION>";

	if (strcmp(pState, "OR") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"OR\">Oregon</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"OR\">Oregon</OPTION>";

	if (strcmp(pState, "PA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"PA\">Pennsylvania</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"PA\">Pennsylvania</OPTION>";

	if (strcmp(pState, "RI") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"RI\">Rhode Island</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"RI\">Rhode Island</OPTION>";

	if (strcmp(pState, "SC") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"SC\">South Carolina</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"SC\">South Carolina</OPTION>";

	if (strcmp(pState, "SD") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"SD\">South Dakota</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"SD\">South Dakota</OPTION>";

	if (strcmp(pState, "TN") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"TN\">Tennessee</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"TN\">Tennessee</OPTION>";

	if (strcmp(pState, "TX") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"TX\">Texas</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"TX\">Texas</OPTION>";

	if (strcmp(pState, "UT") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"UT\">Utah</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"UT\">Utah</OPTION>";

	if (strcmp(pState, "VT") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"VT\">Vermont</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"VT\">Vermont</OPTION>";

	if (strcmp(pState, "VA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"VA\">Virginia</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"VA\">Virginia</OPTION>";

	if (strcmp(pState, "WA") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"WA\">Washington</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"WA\">Washington</OPTION>";

	if (strcmp(pState, "WV") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"WV\">West Virginia</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"WV\">West Virginia</OPTION>";

	if (strcmp(pState, "WI") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"WI\">Wisconsin</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"WI\">Wisconsin</OPTION>";

	if (strcmp(pState, "WY") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"WY\">Wyoming</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"WY\">Wyoming</OPTION>";

	if (strcmp(pState, "PR") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"PR\">Puerto Rico</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"PR\">Puerto Rico</OPTION>";

	if (strcmp(pState, "VI") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"VI\">Virgin Island</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"VI\">Virgin Island</OPTION>";

	if (strcmp(pState, "MP") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"MP\">Northern Mariana Islands</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"MP\">Northern Mariana Islands</OPTION>";

	if (strcmp(pState, "GU") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"GU\">Guam</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"GU\">Guam</OPTION>";

	if (strcmp(pState, "AS") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"AS\">American Samoa</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"AS\">American Samoa</OPTION>";

	if (strcmp(pState, "PW") == 0)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"PW\">Palau</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"PW\">Palau</OPTION>";

	//Finish up the state row and the table
	*mpStream	<<	"</select>"
				<<	pStateError
				<<	"</td></tr>\n";

	//Add Zip Block
	*mpStream	<<	TableLeftSide
				<<	TableZip1
				<<	TableZipData
				<<	pZip
				<<	"\">"
				<<	pZipError
				<<	"</td></tr>\n";

	//Add Primary Phone#
	*mpStream	<<	TableLeftSide
				<<	TablePrimaryPhone1
				<<	TablePrimaryPhoneData1
				<<	pDayPhone1
				<<	TablePrimaryPhoneData2
				<<	pDayPhone2
				<<	TablePrimaryPhoneData3
				<<	pDayPhone3
				<<	TablePrimaryPhoneData4
				<<	pDayPhone4
				<<	"\">"
				<<	pPhoneError
				<<	"</td></tr>\n";

	//Add Secondary Phone#
	*mpStream	<<	TableLeftSide
				<<	TableSecondaryPhone1
				<<	TableSecondaryPhoneData1
				<<	pNightPhone1
				<<	TableSecondaryPhoneData2
				<<	pNightPhone2
				<<	TableSecondaryPhoneData3
				<<	pNightPhone3
				<<	TableSecondaryPhoneData4
				<<	pNightPhone4
				<<	"\"></td></tr>\n";



	//Add referal combobox
	*mpStream	<<	TableLeftSide
				<<	TableReferalText;
	
	//Now build the combobox and select the default item 
	if (referral == 18)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"18\">Business Associate</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"18\">Business Associate</OPTION>";

	if (referral == 17)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"17\">Friend or Family Member</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"17\">Friend or Family Member</OPTION>";

	if (referral == 35)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"35\">Internet Site</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"35\">Internet Site</OPTION>";

	if (referral == 19)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"19\">Media News Story</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"19\">Media News Story</OPTION>";

	if (referral == 36)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"36\">Magazine Ad</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"36\">Magazine Ad</OPTION>";

	if (referral == 37)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"37\">Radio Ad</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"37\">Radio Ad</OPTION>";

	if (referral == 44)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"44\">Talk Show</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"44\">Talk Show</OPTION>";

	if (referral == 21)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"21\">Trade Show or Event</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"21\">Trade Show or Event</OPTION>";

	if (referral == 23)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"23\">Other</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"23\">Other</OPTION>";

	if (referral == 43)
		*mpStream	<<	"<OPTION SELECTED VALUE=\"43\">Select here</OPTION>";
	else
		*mpStream	<<	"<OPTION VALUE=\"43\">Select here</OPTION>";

	//Finish up the referal row and the table
	*mpStream	<<	"</select></td></tr></table>\n";

	//Add bottom note and confirm button
	*mpStream	<<	BottomTextAndButton;

	//Add link to register email
	*mpStream	<<	EmailRegisterText;

	//Add the footer
	if (UsingSSL == 0)
		*mpStream <<	mpMarketPlace->GetFooter();
	else
		*mpStream <<	mpMarketPlace->GetSecureFooter();
	
	CleanUp();
	return;
}


bool clseBayApp::AOLValidateRegistration( int * pUVrating,
											int * pUVdetail,
											char * pEmail, 
											char * pName, 
											char * pAddress, 
											char * pCity, 
											char * pState, 
											char * pZip, 
											char * pCountry, 
											int countryId,			// PH 05/04/99
											char * pDayPhone1,
											char * pDayPhone2,
											char * pDayPhone3,
											char * pDayPhone4,
											ostream *pTheStream) const
{
	
	bool informationOK = true;

	clsCountries *pCountries = NULL;

 	clsInternationalUtilities objIntlUtils;
	pCountries = mpMarketPlace->GetCountries();
	objIntlUtils.SetCurrentCountry(countryId);   // PH 05/04/99

	// first check UVrating 
	// Calculate user verification rating
	mpUserVerificationServices->CalculateUVRatingAndDetail(	pUVrating,
															pUVdetail,
															pCity,
															pState,
															pZip,
// petra													pCountry,
															countryId,	// petra
															pDayPhone1,
															pDayPhone2,
															pDayPhone3,
															pDayPhone4);

	
	if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
		informationOK = (*pUVrating >= 0);
	else
		informationOK = true;

	// Email address
	if (pEmail)
	{
		if (FIELD_OMITTED(pEmail) || !ValidateEmail(pEmail))
		{
			informationOK = false;
			pEmailError = EmailErrorText;
		}
	}

	// Full name
	if (pName)
	{
		// Name check is minimal--makes sure there is at least one space
		if (FIELD_OMITTED(pName) || !strchr(pName, ' '))
		{
			informationOK = false;
			pNameError = NameErrorText;
		}
		else
		{
			if (strlen(pName) > EBAY_MAX_NAME_SIZE)
			{
				informationOK = false;
				pNameError = NameToLongErrorText;
			}
		}
	}

	// Address
	if (pAddress)
	{
		// Address check is minimal--makes sure there is at least one space
		if (FIELD_OMITTED(pAddress) || !strchr(pAddress, ' '))
		{
			informationOK = false;
			pAddressError = AddressErrorText;

		}
		else
		{
			if (strlen(pAddress) > EBAY_MAX_ADDRESS_SIZE)
			{
				informationOK = false;
				pAddressError = AddressErrorText;
			}
		}
	}

	// City
	if (pCity)
	{
		// Check for empty one
		if (FIELD_OMITTED(pCity))
		{
			informationOK = false;
			pCityError = CityErrorText;
		}
		else
		{
			// Show the city check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
//				*pTheStream <<	(((*pUVdetail & UVDetailValidCity) &&
//								((*pUVdetail & UVDetailZipMatchesCity) || (*pUVdetail & UVDetailAreaCodeMatchesCity) || (*pUVdetail & UVDetailCityMatchesState)))
//								? pOk : pNotOk);

			}
		}
	}

	// State
	if (pState && countryId != Country_UK)
	{
		// Check for empty one
		if (FIELD_OMITTED(pState) || strcmp(pState,"other")==0)
		{
			informationOK = false;
			pStateError = StateErrorText;
		}
		else
		{
			// Show the state check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
//				*pTheStream <<	(((*pUVdetail & UVDetailZipMatchesState) ||
//								(*pUVdetail & UVDetailAreaCodeMatchesState) || (*pUVdetail & UVDetailCityMatchesState))
//								? pOk : pNotOk);
			}
		}
	}

	// Zip
	if (pZip)
	{
		// Check for empty one
		if (FIELD_OMITTED(pZip))
		{
			informationOK = false;
			pZipError = ZipErrorText;
		}
		else
		{
			// Show the zip check only if we actually have a valid UV rating for this country
			if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
			{
//				*pTheStream <<	(((*pUVdetail & UVDetailValidZipCode) &&
//								((*pUVdetail & UVDetailZipMatchesState) || (*pUVdetail & UVDetailZipCloseToAreaCode)))
//								? pOk : pNotOk);
			}
		}
	}
  
	// Check for empty one
	if (FIELD_OMITTED(pDayPhone1))
	{
		informationOK = false;
		pPhoneError = PhoneErrorText;
	}
	else
	{
		// Show the phone check only if we actually have a valid UV rating for this country
		if (*pUVrating != clsUserVerificationServices::UV_RATING_FOR_COUNTRY_NOT_AVAILABLE)
		{
			if (pDayPhone1)
			{
				// special checks for really bad phone #s
				if (!(*pUVdetail & UVDetailPhonePrefixNot555) || !(*pUVdetail & UVDetailPhoneNumberLength))
				{
					pPhoneError = PhoneErrorText;
				}
				else
				{
//					*pTheStream	<<	(((*pUVdetail & UVDetailValidAreaCode)  &&
//									((*pUVdetail & UVDetailAreaCodeMatchesState) || 
//									(*pUVdetail & UVDetailZipCloseToAreaCode) || 
//									(*pUVdetail & UVDetailAreaCodeMatchesCity)))
//									? pOk : pNotOk);
				}
			}
		}
	}

	return informationOK;
}

