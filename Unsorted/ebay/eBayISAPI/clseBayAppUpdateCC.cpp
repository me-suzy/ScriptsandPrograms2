/*	$Id: clseBayAppUpdateCC.cpp,v 1.5.320.1 1999/08/01 03:01:31 barry Exp $	*/
//
//	File:	clseBayAppUpdateCC.cpp
//
//	Class:	clseBayApp
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:
//
//		Update Credit Card Info.
//
// Modifications:
//				- 02/23/98 Sam	- Created
//				- 06/12/98 Sam	- Prevent CC Expiration Date Compare Check
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"
#include "clsAuthorizationQueue.h"


// Last Day of Month
const int LastDayOfMonth[]  =  { 31, 28, 31, 30, 31, 30, 31,
									 31, 30, 31, 30, 31 };

#define lastday(dd, mm,yyyy)	if (mm == 2 && ((yyyy % 4)==0) )		\
									dd=29;								\
								else									\
									dd = LastDayOfMonth[mm-1];				

// Error Messages
static const char *ErrorMsgBlankField =
"<h2>Value Not Entered</h2>"
"Sorry, \"%s\" is required to process the form. "
"Please go back and try again.";


static const char *ErrorMsgNotConfirmed =
"<h2>Unconfirmed Registration</h2>"
"Sorry, you have not yet confirmed your registration."
"You should have received an email with instructions for "
"confirming your registration. If you did not receive this "
"mail, or lost it, please return to the registration page and "
"re-register (with the same email address) to have it sent to "
"you again."
"<br>"
"Please contact customer support if you have any questions.";

static const char *ErrorMsgSuspended =
"<h2>Registration Blocked</h2>"
"Sorry, registration is blocked for this account. ";

static const char *ErrorMsgUnknownState =
"<h2>Internal Error</h2>"
"Sorry, there was a problem confirming your registration. "
"Please report this to customer support.";

static const char *ErrorMsgUnknown =
"<h2>Internal Error</h2>"
"Sorry, there was a problem processing your request. "
"Please press the back button on your browser and try again.";


static const char *ErrorMsgBadCreditCard =
"<h2>Invalid Credit Card Number</h2>"
"Sorry, The Credit Card Number you entered is not valid. "
"We currently accept Visa and MasterCard only. "
"Please press the back button on your browser and check the number again.";

static const char *ErrorMsgBadCreditCardDate =
"<h2>Invalid Credit Card Date</h2>"
"Sorry, The Credit Card Expiration Date you entered is invalid. "
"Please press the back button on your browser and enter a correct date.";

static const char *ErrorMsgCreditCardAlreadyExpired =
"<h2>Credit Card Update Error</h2>"
"Sorry, The Credit Card Expiration date you entered has already expired. "
"Please go back and check it again.";


static const char *ErrorMsg =
"<h2>No new password</h2>"
"Sorry, you <b>must</b> supply a new password. "
"Please go back and try again.";


static const char *InformationCCAlreadyCurrent =
"<h2>Credit Card Already On File! <i>(replace?)</i></h2>"
"Our records indicate that you already have a credit card on file. "
"Do you wish to replace <i>%d-XXXX-XXXX-XXXX</i> on file with <i>%d-XXXX-XXXX-XXXX</i>?";


static const char *InformationCCDateAlreadyCurrent =
"<h2>Credit Card Already On File!</h2>"
"Our records indicate that the credit card you specified is already on file and has a valid "
"expiry date.";


static const char *InformationCCWillBeUpdated =
"<h2>Credit Card Update Process Started!</h2>"
"Our records are now being updated with the following information you provided us. <br><br>"
"<b>Credit Card Account Number :</b> <i>%d-XXXX-XXXX-XXXX</i> <br>"
"<b>Expiration Date :</b> <i>%02d-%02d-%d</i> <br><br>"
"You will be informed within next 24 hours via email on the approval status of your credit card. "
"Thank you for using eBay!. ";

static const char *InformationCCUpdateInProgress =
"<h2>Credit Card Update Process-<i>Already Started!</i></h2>"
"Your request to update credit card information is <i><b> already </i></b> in progress.<br>"
"You will be informed via email on the approval status of your credit card.<br>"
"Thank you for using eBay!. ";




void clseBayApp::UpdateCC(CEBayISAPIExtension *pServer,
						  CHttpServerContext *pCtxt,
						  char * pUserId,
						  char * pPass,
						  char * pccNumber,
						  char * pMonth,
						  char * pDay,
						  char * pYear)
{
	bool			 error		= false;
	time_t			 expTime;
	char			 client_hostname[512];
	char			 client_hostaddr[512];
	ULONG			 bufLen;
	clsAccount		*pAccount;
	int				 CC4Id, DD, MM, YYYY;
	char			 str[5];
	char			 buf[512];
    time_t			 todaysDate;
	eAccountType	 accType=CC_ON_FILE;
	char			 pAccType[5];
	clsAuthorizationQueue *pAuthorizationQueue;


	// Setup & initialize
	SetUp();
	memset(buf, '\0', sizeof(buf));
	memset(str, '\0', sizeof(str));
	memset(client_hostname, '\0', sizeof(client_hostname));
	memset(client_hostaddr, '\0', sizeof(client_hostaddr));

	// Whatever happens, we need a title and a standard
	// header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Credit Card Update"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetSecureHeader()
			  <<	"\n";

	// If the called passed the special password, then we'll do an admin
	// check. Otherwise, we won't ;-).
	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												true);			// Admin Query
	}
	else
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												false);			// Admin Query
	}

	if (!mpUser)
	{
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}
	// Prevent users in suspended or ghost users to update cc info.
	if (mpUser->GetUserState() == UserGhost ||
		mpUser->GetUserState() == UserSuspended)
	{
		*mpStream	<<	ErrorMsgSuspended
					<<	"<br>"
					<<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}

	// Check user entered fields, day, month and yearpart1 are 
	// selectable and will have default values set
	// Check Credit Card field
	// Perform Credit Card Checksum check
	if (!CheckCCChecksum(pccNumber))
	{
		*mpStream <<	ErrorMsgBadCreditCard
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}

	// Card is probably valid, check date validity
	expTime = CheckCCDate(pDay, pMonth, pYear);
	if (expTime == (time_t)0)
	{
		*mpStream <<	ErrorMsgCreditCardAlreadyExpired
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}
	else if (expTime == (time_t)-1)
	{
		*mpStream <<	ErrorMsgBadCreditCardDate
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}

	// Stage 3, Insert CC authorization here

	pAccount	= mpUser->GetAccount();
	// Warn user if 
	// 1. User is trying to update same card with valid date
	// 2. User is trying to provide a seperate CC than that is on file
	if (!GetAndCheckCCInfo(pUserId, pccNumber, expTime, pAccount))
	{
		CleanUp();
		if (pAccount)
			delete pAccount;
		return;
	}

	// 
	// Set them!
	//
	// And update
	pCtxt->GetServerVariable("REMOTE_ADDR", client_hostaddr, &bufLen);
	pCtxt->GetServerVariable("REMOTE_HOST", client_hostname, &bufLen);

	// Update Processing Machine database here
	// Update with new cc details
	CC4Id = atoi(strncpy(str,pccNumber,4));

	time(&todaysDate);

	// Stage 1.55, Write to cc_* tables
	sprintf(pAccType, "%d", accType);
	// write to cc_authorize
	pAuthorizationQueue = mpAuthorizationQueue->Enqueue(pccNumber,
														expTime,
														1,					// Real time prioirty
														mpUser->GetId(),
														1.0,				// $1.0 Amount charge
														Authorization,		// Transaction type
														"",					
														""
														"",
														"",
														"",
														"",
														pAccType);			// account_type
	if (pAuthorizationQueue)
	{
		// Now commit data to cc_billing
		pAuthorizationQueue->StoreCCUpdate(	pAuthorizationQueue->GetId(), 
											pAuthorizationQueue->GetReferenceId());

		pAuthorizationQueue->Remove(pAuthorizationQueue->GetReferenceId());
	}

	// Stage 1.5
	if (!AddEntryToAccessImportFile(mpUser->GetEmail(),
									pccNumber,
									expTime))
	{
		*mpStream <<	ErrorMsgUnknown
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();

		CleanUp();
		if (pAccount)
			delete pAccount;

		return;
	}		


	DD		= atoi(pDay);
	MM		= atoi(pMonth);
	YYYY	= atoi(pYear);
	if (DD == 0)
		lastday(DD, MM, YYYY);

	sprintf(buf, InformationCCWillBeUpdated, 
				 CC4Id, MM, DD, YYYY);
	*mpStream	<< buf;		
	*mpStream	<< "<p>"
				<< mpMarketPlace->GetSecureFooter();

	CleanUp();

	if (pAccount)
		delete pAccount;
	if (pAuthorizationQueue)
		delete pAuthorizationQueue;

	return;

}



void clseBayApp::UpdateCCConfirm(CEBayISAPIExtension *pServer,
								 CHttpServerContext  *pCtxt,
								 char   *pUserId,
								 char   *pCCNumber,
								 int    expDate)
{
	int 			 CCId;
    time_t			 todaysDate;
	clsAccount		*pAccount;
	char			 buf[512];
	struct tm		*aTime;
	char			 client_hostname[512];
	char			 client_hostaddr[512];
	ULONG			 bufLen;
	char			 str[5];
	eAccountType	 accType=CC_ON_FILE;
	char			 pAccType[5];
	clsAuthorizationQueue *pAuthorizationQueue;


	// Setup & Initialize
	SetUp();
	memset(str, '\0', sizeof(str));
	memset(buf, '\0', sizeof(buf));
	memset(client_hostname, '\0', sizeof(client_hostname));
	memset(client_hostaddr, '\0', sizeof(client_hostaddr));
	
	// Whatever happens, we need a title and a standard header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Credit Card Update"
					"</TITLE>"
					"</HEAD>"
			  <<	mpMarketPlace->GetSecureHeader()
			  <<	"\n";

	// So, our user did press submit[yes] button to update
	// Get user details from DB and update 
	mpUser		= mpUsers->GetUser(strlwr(pUserId));
	if (!mpUser)
	{
		*mpStream <<	ErrorMsgUnknown
				  <<	"<br>"
				  <<	mpMarketPlace->GetSecureFooter();
		CleanUp();
		return;
	}
	pAccount	= mpUser->GetAccount();

	// *ADD Logic to check processing state
	time(&todaysDate);
	CCId = atoi(strncpy(str,pCCNumber,4));

	pCtxt->GetServerVariable("REMOTE_ADDR", client_hostaddr, &bufLen);
	pCtxt->GetServerVariable("REMOTE_HOST", client_hostname, &bufLen);

	if (pAccount->GetCCIdForUser() == CCId)
		// User may press the Update Now button more than once, check status
		// and warn user of the process
		sprintf(buf, InformationCCUpdateInProgress);
	else
	{
		// Stage 1.55, Write to cc_* tables
		sprintf(pAccType, "%d", accType);
		// write to cc_authorize
		// Set priority to 1, in this version, no real time authorization is provided
		pAuthorizationQueue = mpAuthorizationQueue->Enqueue(pCCNumber,
															expDate,
															1,					// Real time prioirty
															mpUser->GetId(),
															1.0,				// $1.0 Amount charge
															Authorization,		// Transaction type
															"",					
															""
															"",
															"",
															"",
															"",
															pAccType);			// account_type
		if (pAuthorizationQueue)
		{
			// Now commit data to cc_billing
			pAuthorizationQueue->StoreCCUpdate(	pAuthorizationQueue->GetId(), 
												pAuthorizationQueue->GetReferenceId());

			pAuthorizationQueue->Remove(pAuthorizationQueue->GetReferenceId());
		}

		// Stage 1.5
		if (!AddEntryToAccessImportFile(mpUser->GetEmail(),
										pCCNumber,
										expDate))
		{
			*mpStream <<	ErrorMsgUnknown
					  <<	"<br>"
					  <<	mpMarketPlace->GetSecureFooter();
			CleanUp();
			if (pAccount)
				delete pAccount;
			return;
		}

		aTime = localtime((long *)&expDate);	
		sprintf(buf, InformationCCWillBeUpdated, 
						CCId, aTime->tm_mon+1, aTime->tm_mday, aTime->tm_year+1900);

	}
	
	*mpStream	<< buf;
	*mpStream	<< "<p>"
				<< mpMarketPlace->GetSecureFooter();
	CleanUp();

	if (pAccount)
		delete pAccount;
	if (pAuthorizationQueue)
		delete pAuthorizationQueue;


	return;
}


bool clseBayApp::CheckCCChecksum(char *pccNumber)
{

	char inNumber[20];
	short length = 0;

	// Filter out the garbage and retain only digits
	for (short i=0, j=strlen(pccNumber); i<j; i++)
		if (isdigit(pccNumber[i]))
			inNumber[length++] = pccNumber[i];

	inNumber[length] = '\0';

	// Credit Cards can have lengths from 13 to 16.
	if (strlen(inNumber) < 13 || strlen(inNumber) > 16)
		return false;

	// Credit Card has to be one of the several types
	// Currently only support Visa and Mastercard
	if((inNumber[0] != '4') && (inNumber[0] != '5'))
		return false;

    length = strlen(inNumber);
    char lastChar = inNumber[length-1];
    // Now Verify CheckSum
    short checkSum = 0;
    for( i = 1; i < length; i++)
    {
		// don't include the last digit
        short digit = inNumber[length - i-1] - '0';
        // get the digits from r to l
        short temp = digit * (1 + (i % 2));
        if(temp < 10)
			checkSum += temp;
        else
            checkSum += temp - 9;
    }
     checkSum = (10 - (checkSum % 10)) % 10;

     if((lastChar - '0') == checkSum)
	 {		
		strcpy(pccNumber, inNumber);
		return true;
	 }
     else
		return false;
}


time_t clseBayApp::CheckCCDate( char *pDay, 
								char *pMonth, 
								char *pYear)
{
	int  DD, MM, YYYY, cDD;
	struct tm expTime, *todaysTime;
	time_t cc_expiry_date, todaysDate;

	YYYY = atoi(pYear); 
	DD = cDD = atoi(pDay);
	MM	 = atoi(pMonth);

	// Check if user specified date correctly
	lastday(cDD,MM,YYYY)
	if (DD > cDD) // not that many days in month
		return (time_t)-1;

	// Get last day of month 
	if (DD == 0)
		lastday(DD,MM,YYYY);

    expTime.tm_year   = YYYY-1900;
    expTime.tm_mon = MM-1;

    if (DD != 0)
		expTime.tm_mday    = DD;
    expTime.tm_isdst  = -1;

	// Get todays date and check if specified expiry date is less or equal to it
    time(&todaysDate);
	todaysTime = localtime(&todaysDate);

	// Set time
	expTime.tm_sec  = todaysTime->tm_sec;
	expTime.tm_min  = todaysTime->tm_min;
	expTime.tm_hour = todaysTime->tm_hour;

	// Convert to time_t long value
	cc_expiry_date = mktime(&expTime);

	if (cc_expiry_date <= todaysDate)
		return (time_t)0;

	return cc_expiry_date;
}


bool clseBayApp::GetAndCheckCCInfo(char *pUserId, char *pccNumber, 
								   time_t expTime, clsAccount *pAccount)
{
	int		    dbCCId=0, CC4Id=0;
	time_t		dbExpTime;
	bool		rc=true;
	char		buf[512];
	char		str[5];

	// Initialize
	memset(buf, '\0', sizeof(buf));
	memset(str, '\0', sizeof(str));

	// Get user details from DB and perform checks
	dbCCId		= pAccount->GetCCIdForUser();
	dbExpTime	= pAccount->GetCCExpiryDate();

	CC4Id = atoi(strncpy(str,pccNumber,4));

	// First check if user specified same CC
	// If user specified same CC but an earlier exp date then flag error
	if (CC4Id == dbCCId)
	{
		if ((expTime - dbExpTime) <= 200)
		{
			// Change for Billing, don't flag any errors for this one
			// Identical card and expiration date provided
			// time is in time_t units, so unless there is date that is provided that
			// is atleast 24 hours or more to expiry then update it.
			return rc;
/*
			*mpStream << InformationCCDateAlreadyCurrent;
			*mpStream	<< mpMarketPlace->GetSecureFooter();
			rc = false;
*/
		}

	}
	if ((dbCCId > 0) && (CC4Id != dbCCId))
	{
		// expiry date check is already done for the new card
		// Warn user that he is providing a different card than one on file
		*mpStream	<<	"<TITLE>"
					<<	"Credit Card Update"
					<<	"</TITLE>"
					<<	flush;


//		sp, Hardcoded, we need to create 
//		<<	mpMarketPlace->GetSecureHTMLPath()
//		<<	"https://arribada.ebay.com/aw-secure/"
		sprintf(buf, InformationCCAlreadyCurrent, dbCCId, CC4Id);
		*mpStream	<< buf;
		*mpStream	<<	"<form method=post action="
						"\""
					<<	mpMarketPlace->GetSecureHTMLPath()
					<<	"eBayISAPI.dll"
						"\""
						">"
						"<INPUT TYPE=HIDDEN NAME=\"MfcISAPICommand\" VALUE=\"UpdateCCConfirm\">"
						"\n"
						"<p>";

		sprintf(buf, "<INPUT TYPE=hidden NAME=\"userid\" VALUE=\"%s\">\n",
															pUserId);
		*mpStream	<< buf;

		sprintf(buf, "<INPUT TYPE=hidden NAME=\"ccnumber\" VALUE=\"%s\">\n",
														pccNumber);
		*mpStream	<< buf;
		sprintf(buf, "<INPUT TYPE=hidden NAME=\"date\" VALUE=\"%d\">\n",
														expTime);
		*mpStream	<< buf;
		*mpStream	<< "Click ";
		*mpStream	<< "<INPUT TYPE=Submit VALUE=\"Replace Now\">\n";
		*mpStream	<< " to replace it.";

		*mpStream	<< mpMarketPlace->GetSecureFooter();

		rc = false;
	}

	return rc;
}


bool clseBayApp::AddEntryToAccessImportFile(char	*pEmail,
											char	*pccNumber,
											time_t	expDate)

{
	struct tm	*pExpDate;
	char		buf[256];
	char		tbuf[9], dbuf[9], expbuf[5];
	bool		bImportOpen=true;
	int			Year;
	char		lpPath[128];
	char		lpFilePath[128];
	HMODULE		hmod;
	CFile f;
	CFileException e;

	// Initialize buffers
	memset(buf, '\0', sizeof(buf));
	memset(lpPath, '\0', sizeof(lpPath));
	memset(lpFilePath, '\0', sizeof(lpFilePath));
	memset(tbuf, '\0', sizeof(tbuf));
	memset(dbuf, '\0', sizeof(dbuf));
	memset(expbuf, '\0', sizeof(expbuf));

	// Get ebayisapi dll handle from which we get the dir. path
	hmod = GetModuleHandle("ebayisapi.dll");

	if (hmod != 0)
	{
		GetModuleFileName(hmod,lpPath, sizeof(lpPath));
		strncpy(lpFilePath, lpPath, strrchr(lpPath, '\\')-lpPath);
		strcat(lpFilePath, "\\import\\import.txt");
	}
	else
		strcpy(lpFilePath, ".\\import\\import.txt");

	if(!f.Open(lpFilePath, CFile::modeCreate | CFile::modeNoTruncate | 
		CFile::modeWrite | CFile::shareExclusive, &e ))    
	{
		return false;
	}

	// Collect current date/time and Normalize expiration date
	_strdate(dbuf);
	_strtime(tbuf);
	pExpDate = localtime(&expDate);
	Year = pExpDate->tm_year;
	if (Year >=100)
		Year = Year - 100; // Year is 2000 or higher, normalize to 00, 01..
	sprintf(expbuf, "%02d/%02d", pExpDate->tm_mon+1, Year);
    sprintf(buf, "%s,%s PST,%s,%s,%s\n", dbuf, tbuf, pEmail, pccNumber, expbuf);
       
	// Add entry to the file, Seek the beginning of the file
	// Entries are added in order 
	f.Seek(f.GetLength(), CFile::begin);
	f.Write(buf,strlen(buf));
	f.Close();

	return true;
}
