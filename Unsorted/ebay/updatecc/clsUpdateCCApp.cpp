#include <stdio.h>
#include <time.h>
#include <ctype.h>
#include <string.h>
#include <sys/systeminfo.h>
#include "clsDatabaseOracle.h"
#include "clsUpdateCCApp.h"
#include "clsUsers.h"

// Absolutely Raw File
#define rawDBFile 		"RawCCData"
// Treated Raw File after dates have been formatted, input to update
#define processedDBFile 	"ProcessedCCData"
#define OracleVer 		"7.3.3"


// helper functions and data declarations
const int LastDayOfMonth[]  =  { 31, 28, 31, 30, 31, 30, 31,
                                 31, 30, 31, 30, 31 };

const char *Month[]	    = { "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
				"JUL", "AUG", "SEP", "OCT", "NOV", "DEC" };


int GetMonth(char *month);
int nAccsProcessed;
char mMailList[10][60];

int GetMonth(char *month)
{
	int i=0;

	if (!month || strcmp(month, "") == 0)
		return 13;

	while ( i < 12 && (strcasecmp(Month[i], month) != 0) )
		i++;

	return ++i;
}

void clsUpdateCCApp::InitProfile()
{
	FILE *fp;
	char scanBuf[500];
	char seps[] = " =\n,";
	char *name, *value;
	int n=0;

	meMailOnNewEntry = true;

	if(!(fp = fopen("profile", "r")))
		return;

	while (fgets(scanBuf, 500, fp) !=NULL)
	{
		if (strlen(scanBuf) < 2)
			continue;

		// Get All Name-Value Pairs
		name = strtok(scanBuf, seps);
		if (name && strlen(name) !=0)
			value=strtok(NULL, seps);
		if (value && strlen(value)!=0)
		{
			if (strcasecmp("MailList", name)==0)
			{
				// Check for more email recepients
				strcpy(mMailList[n], value);
				while ((value = strtok(NULL, seps)) != NULL)
				{
					value[strlen(value)] = '\0';
					n++;
					strcpy(mMailList[n], value);
				}
			}
			// Add future name-value pairs here
		}
	}

	fclose(fp);	
}
	
int clsUpdateCCApp::FormatDates()
{
	FILE *rfp, *wfp, *efp;
	char scanBuf[500];
	char emailId[80];
	char dateOfIssue[20];
	char CCId[20], alphaMon[10];
	int  result, pos, rpos, nErr=0;
	bool bMMYY = true, alphaDate=false;
	char ch;
	char seps[] = " \t\n";
	char *n;
	char sDD[5], sMM[5], sYYYY[10];
	int  MM, DD, YYYY;
	char scanStr[80];
        struct tm expTime, *expITime;
        time_t cc_expiry_date;


	// Open Files for Read and Write
	if (!(rfp = fopen(rawDBFile, "r")))
	{
		printf("**Database Data file not on disk : %s**\n", rawDBFile);
		exit(-1);
	}
	if (!(wfp = fopen(processedDBFile, "w")))
	{
		printf("**Error occured while opening : %s**\n", processedDBFile);
		exit(-1);
	}
	if (!(efp = fopen("UpdateErr.log", "w")))
	{
		printf("**Error occured while opening UpdateErr.log**\n");
	}

	fprintf(efp, "Subject: Error Log\n");	
	fprintf(efp, "** List of badly formated dates, Not Processed **\n\n");

	nAccsProcessed = 0;
	while (fgets(scanBuf, 500, rfp) !=NULL)
	{
		nAccsProcessed++;
		MM = DD = YYYY = 0;

		n = strtok(scanBuf, seps);
		if (n && strlen(n) !=0)
			strcpy(emailId, n);

		n = strtok(NULL, seps);
		if (n && strlen(n) !=0)
			strcpy(CCId, n);
		n = strtok(NULL, seps);
		if (n && strlen(n) !=0)
			strcpy(dateOfIssue, n);
		// Remaining all tokens are part of dateString
		while(n = strtok(NULL, seps))
		{
			if (n && strlen(n) != 0)
				strcat(dateOfIssue, n);
		}
	
		// process free-style dates
		// Check to see if date contains '/', '-'
		ch = '/';
		if (n = strchr(dateOfIssue, ch))
			bMMYY = false;
		else
		{
			ch = '-';
			if (n = strchr(dateOfIssue, ch))
				bMMYY = false;
		}

		// Check if date is like "AUG 99" or "AUG-99" or "AUG/99"
		if (isalpha(dateOfIssue[0]))
		{
		        alphaDate = true;	
			// date in "AUG 99" format
			if (bMMYY)
				sscanf(dateOfIssue, "%3s%s", alphaMon, sYYYY);
			else // date in "AUG-99" or "AUG/99" format
				sscanf(dateOfIssue, "%3s%c%s", alphaMon, &ch, sYYYY);

			if (strcmp(alphaMon, "") == 0)
				MM=0;
			else if ((MM = GetMonth(alphaMon)) > 12)
				// Invalid month specified
				MM=0;

			YYYY = atoi(sYYYY);
		}
		if (!bMMYY && !alphaDate)
		{
			//  Date with '-' or '/' 
			pos = n - dateOfIssue + 1;
			// Check if date has 2 '-' or '/'
			if (n = strrchr(dateOfIssue, ch))
				rpos = n - dateOfIssue + 1;

			if (pos == rpos)
			{
				// date is in MM/YY or MM/YYYY or MM-YY or MM-YYYY format
				sprintf(scanStr, "%%%dd%%c%%%dd", 
						pos-1, strlen(dateOfIssue)-rpos);
				sscanf(dateOfIssue, scanStr, &MM, &ch, &YYYY);
			}
			else
			{
				// date is in MM/DD/YY or MM/DD/YYYY or MM-DD-YY or MM-DD-YYYY 
				// format
				sprintf(scanStr, "%%%dd%%c%%%dd%%c%%%dd", 
						pos-1, rpos-pos-1, strlen(dateOfIssue)-rpos);
				sscanf(dateOfIssue, scanStr, &MM, &ch, &DD, &ch, &YYYY);
			}
		}
		if (bMMYY && !alphaDate)
		{
			// Date with no '-' or '/' seperator
			switch (strlen(dateOfIssue))
			{
				case 3 :
					 // Format is MYY
					 sscanf(dateOfIssue, "%1d%2d", &MM, &YYYY);
					 break;
				case 4 :
					 // Format is MMYY
					 sscanf(dateOfIssue, "%2d%2d", &MM, &YYYY);
					 break;
				case 6 :
					 // Format is MMYYYY or MMDDYY, assume MMDDYY for now
					 sscanf(dateOfIssue, "%2d%2d%2d", &MM, &DD, &YYYY);
					 break;
				case 8 :
					 // Foramt is MMDDYYYY
					 sscanf(dateOfIssue, "%2d%2d%4d", &MM, &DD, &YYYY);
					 break;

				default :
					 // To Error Log, bad date 
					 // fprintf(efp, "%s %s %s\n", emailId, CCId, dateOfIssue);
					 break;
			}
			
		}
	
		bMMYY = true;
		alphaDate = false;
		// Check Month and Day validity
		if (MM > 12 || MM == 0 || DD > 31)
		{
			nErr++;
			fprintf(efp, "%s  %s  %s\n", emailId, CCId, dateOfIssue);
			continue;
		}	
			
		if (YYYY-1900 > 0)
			YYYY = YYYY-1900;
		else if (YYYY  < 60)
			YYYY = YYYY + 100; // Y2K

        	expTime.tm_year   = YYYY;
		expTime.tm_mon = MM-1;


		if (DD != 0)
			expTime.tm_mday    = DD;
		else
		{
			expTime.tm_mday = LastDayOfMonth[MM-1];
			// Leap Year Check
			if ( ((YYYY+1900) % 4) == 0)
				if (MM == 2)
					expTime.tm_mday++;
		}
        	expTime.tm_isdst  = -1;
		expTime.tm_hour   = 23;
		expTime.tm_min    = 59;
		expTime.tm_sec    = 59;

        	cc_expiry_date = mktime(&expTime);
		expITime = localtime(&cc_expiry_date); // Full conversion if no day was specified
				
		// output to processed file
		fprintf(wfp, "%s %4s %2d-%2d-%4d\n", 
			emailId, CCId, expITime->tm_mon+1, expITime->tm_mday, 
								expITime->tm_year+1900);

	}

	fprintf(efp, "\nTotals:\t%d\n", nErr);
	// Go home
	fclose(rfp);
	fclose(wfp);
	fclose(efp);

	return nErr;

}



clsUpdateCCApp::clsUpdateCCApp()
{
 	mpDatabase      = (clsDatabase *)0;
        mpMarketPlaces  = (clsMarketPlaces *)0;
        mpMarketPlace   = (clsMarketPlace *)0;
        mpUsers         = (clsUsers *)0;

	return;
}

/* clsUser * clsUpdateCCApp::IsUserInDB(vector<unsigned int> vUsers, char *userId)
{
	clsUser *pUser;
	vector<unsigned int>::iterator i;

	// Check if update is really required
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		pUser = mpUsers->GetUser(*i);
		// User may have changegd his email, so dig in that list
		if (strcasecmp(pUser->GetUserId(), userId)==0)
		{
			// found our user
			return pUser;
		}
		delete pUser;
	}
	pUser = NULL;
	if (i == vUsers.end()) // No user found
	{
		// Now check if there is an user alias
	        if (mpDatabase)
			pUser = mpDatabase->GetUserByUserId(mpMarketPlace->GetId(), userId);
	}

	return pUser;				
}

*/

strstream *clsUpdateCCApp::SetupStream(strstream *pStr)
{
	// Initialize
	pStr = new strstream();
	// prepare the stream
	pStr->setf(ios::fixed, ios::floatfield);
	pStr->setf(ios::showpoint, 1);
	pStr->precision(2);

	return pStr;
}


clsUpdateCCApp::~clsUpdateCCApp()
{
 return;
}


bool clsUpdateCCApp::UpdateCC()
{
	int ccID;
	int nBadDates = 0, nBadUsers=0, nBadAccounts=0;
	int nAlreadyUpdated=0, nNoCCOnFile=0, nExpiredDate=0;
	int nUserAlias=0, nTotalRecords=0;
	int nRecordsUpdated = 0;
        struct tm expTime, *ltime;
        time_t cc_expiry_date, cc_update_time, endTime, dbExpTime;
	FILE *fp, *efp, *dfp;
	char scanBuf[500];
	char userId[128], userName[80], hostName[100];
	clsUser *pUser;
	vector<char *> vUserDetails;
	char *userDetailsBuf;
	vector<char *>::iterator iuser;
	clsAccount *pAccount;
	strstream *oStr, *badUserStr, *noAccStr, *renUserStr;
	strstream *accCurrStr, *noCCOnFile, *ccExpStr;
	char *pTheNotice;
	char mailCommand[64 + EBAY_MAX_USERID_SIZE +1];
	FILE *pPipe;
	int  mailRc;
	char   emailYN[2], *p;


 	// use local time to mark the update time
       	time( &cc_update_time );

	// Translate the random dates to formatted style
	printf("Now Formatting dates..\n");
	nBadDates = FormatDates();

	printf("Now updating Database.\n\n");

	// Initialize
	if (!mpDatabase)
       		mpDatabase = gApp->GetDatabase();
	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();
	if (!mpMarketPlace)
		mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	if (!mpUsers)
		mpUsers = mpMarketPlace->GetUsers();

	// Initialize streams
	badUserStr = SetupStream(badUserStr);
	*badUserStr << "** List of Users Not in DataBase or in Suspended, Ghost state **\n\n";
	noAccStr = SetupStream(noAccStr);
	*noAccStr << "** List of Users with No Account in DataBase **\n\n";
	accCurrStr = SetupStream(accCurrStr);
	*accCurrStr << "** List of Users with Already Updated Accounts **\n\n";
	renUserStr = SetupStream(renUserStr);
	*renUserStr << "** List of Users with Account Alias **\n"
		    << "(Important : Please update old user Id's with New User Id's)\n\n"
		    << "Old User ID\t\tNew User ID\n\n";
	noCCOnFile = SetupStream(noCCOnFile);
	*noCCOnFile << "** List of Users with No Credit Card On File **\n";
	ccExpStr = SetupStream(ccExpStr);
	*ccExpStr << "** List of Users with Expired Credit Card Dates **\n";


	if (!(fp = fopen(processedDBFile, "r")))
	{
		printf("**Error occured while opening : %s**\n", processedDBFile);
		exit(-1);
	}
	// Error Log
	if (!(efp = fopen("UpdateErr.log", "a+")))
	{
		printf("**Error occured while opening UpdateErr.log\n");
		exit(-1);
	}

	// Stats Log
	if (!(dfp = fopen("UpdateStats.log", "w")))
	{
		printf("**Error occured while opening : UpdateStats.log**\n");
		exit(-1);
	}

	// Stuff for mail header
	fprintf(dfp, "Subject: Report for Access Export Data File\n");	


	while (fgets(scanBuf, 500, fp) !=NULL)
	{
        	sscanf(scanBuf, "%s%d%2d-%2d-%4d",
			userId,
			&ccID,
                        &expTime.tm_mon,
                        &expTime.tm_mday,
                        &expTime.tm_year);

	        expTime.tm_year -= 1900;
		expTime.tm_mon--; 
       		expTime.tm_isdst        = -1;
		expTime.tm_hour = 23;
		expTime.tm_min = 59;
		expTime.tm_sec = 59;

       	 	cc_expiry_date = mktime(&expTime);

		// printf("Processing User : %s\t\n", userId);
		if (nTotalRecords!=0 && nTotalRecords%5 == 0)
			printf("Processed %d Users\n", nTotalRecords);

		nTotalRecords++;

		if ((pUser = mpUsers->GetUser(userId, false, false)) == NULL)
		{
			// User not in DB, no Alias found either
			nBadUsers++;
			*badUserStr << userId
			  	    << "\n";
			continue;
		}
		// Check to see if user was suspended, if yes then skip
		if (pUser->GetUserState() == UserGhost ||
		    pUser->GetUserState() == UserSuspended)
		{
			// User is suspended or a ghost user
			nBadUsers++;
			*badUserStr << userId
			  	    << "\n";
			delete pUser;
			continue;
		}

		// found our user
		// Check to see if user alias was found
		if (strcasecmp(pUser->GetUserId(), userId) != 0)
		{
			// User Alias exists, must update access DB
			nUserAlias++;
			*renUserStr << userId;
			*renUserStr << "\t";
			*renUserStr << pUser->GetEmail();
			*renUserStr << "\n";
			// Account status in DB is available for new user
			// strcpy(userId, pUser->GetUserId());
			strcpy(userId, pUser->GetEmail());	
		}
		pAccount        = pUser->GetAccount();
       		if (!pAccount)
       		{
			// Create Account
			mpDatabase->CreateAccount(pUser->GetId(), 0);
			pAccount = pUser->GetAccount();
			if (!pAccount)
				printf("--Account not found--");
		}

		// Check if credit card of file
		// According to Jeff Dvorak, a flag is set in DB
		// to indicate if user had provided CC and it was
		// validated via the ICVerify process
		if (!pUser->HasCreditCardOnFile())
		{	
			nNoCCOnFile++;
 			*noCCOnFile << userId;
			*noCCOnFile << "\n";	
			if(pUser)
				delete pUser;
			if(pAccount)
				delete pAccount;
			printf("\n");
			continue;
		}
		
		dbExpTime = (time_t)pAccount->GetCCExpiryDate();

		// If same CC and newer exp. date or different CC and
		// newer date than today's date., then update
		if (((ccID == pAccount->GetCCIdForUser()) &&
			      (cc_expiry_date - dbExpTime > 0)) ||
			      ((ccID != pAccount->GetCCIdForUser()) && 
			      (cc_expiry_date - cc_update_time > 0))) 
		{
			pAccount->UpdateCCDetails(
       					pUser->GetId(),
					ccID,
                       	        	cc_expiry_date,
                      	        	cc_update_time);

			printf("-- Updating account for %s --\n", userId);

			userDetailsBuf = (char *)malloc(128);
			sprintf(userDetailsBuf, "%s,%s,%d", 
					pUser->GetEmail(), pUser->GetName(), ccID);
		 	vUserDetails.push_back(userDetailsBuf);
			nRecordsUpdated++;
		}
		else
		{
			if ((ccID == pAccount->GetCCIdForUser()) &&
					(dbExpTime - cc_expiry_date >= 0))
			{
				nAlreadyUpdated++;
	 			*accCurrStr << userId;
				*accCurrStr << "\n";
			}
			else
			{	
				nExpiredDate++;
	 			*ccExpStr << userId;
				*ccExpStr << "\n";	
			}	
		}
		if(pUser)
			delete pUser;
		if(pAccount)
			delete pAccount;

	}

	// Print silly stats and send mail to the Mail Man
	time(&endTime);
	ltime = localtime(&cc_update_time);
	fprintf(dfp, "Access Import File Processor Run On : %s\n\n", asctime(ltime));
	fprintf(dfp, "\t\t** System Summary **\n\n");
	fprintf(dfp, "Oracle DataBase Version : %s\n", OracleVer);
	sysinfo(SI_HOSTNAME, hostName, sizeof(hostName));
	if (strlen(hostName) != 0)
		fprintf(dfp, "Program was run on host : %s\n", hostName);
	fprintf(dfp, "Estimated time to process %d entries was %d min. %d sec.\n", 
		nAccsProcessed, (endTime-cc_update_time)/60, (endTime-cc_update_time)%60);
	fprintf(dfp, "A total of %d entries were bad and have been written to UpdateErr.log.\n\n",
			nBadDates+nBadAccounts+nBadUsers+nAlreadyUpdated+nNoCCOnFile+nExpiredDate);
	fprintf(dfp, "\n\t\t** Summary of Error Records breakup **\n\n");
	fprintf(dfp, "%5d Entries had badly formatted dates.\n", nBadDates);
	fprintf(dfp, "%5d Users have not registered or not on file.\n", nBadUsers);
	fprintf(dfp, "%5d User Accounts are not on file.\n", nBadAccounts);
	fprintf(dfp, "%5d User Accounts have \"credit card on file\" flag not set.\n", nNoCCOnFile);
	fprintf(dfp, "%5d User Accounts have already been updated.\n", nAlreadyUpdated);
	fprintf(dfp, "%5d User Accounts need to be updated in Access.\n", nUserAlias);
	fprintf(dfp, "%5d Users have expired Credit Card Dates.\n", nExpiredDate);
	if (meMailOnNewEntry)
		fprintf(dfp, "\n\n\n%5d User Accounts were updated and sent email.\n", 
							nRecordsUpdated);
	else
		fprintf(dfp, "\n\n\n%5d User Accounts were updated.\n", 
							nRecordsUpdated);


	// Output Data to Error Log
	*badUserStr << endl;
	OutPutData(efp, badUserStr, nBadUsers);
	if (badUserStr)
		delete badUserStr;	
	*noAccStr << endl;
	OutPutData(efp, noAccStr, nBadAccounts);
	if (noAccStr)
		delete noAccStr;	
	*accCurrStr << endl;
	OutPutData(efp, accCurrStr, nAlreadyUpdated);
	if (accCurrStr)
		delete accCurrStr;	
	*ccExpStr<< endl;
	OutPutData(efp, ccExpStr, nExpiredDate);
	if (ccExpStr)
		delete ccExpStr;	
	*noCCOnFile << endl;
	OutPutData(efp, noCCOnFile, nNoCCOnFile);
	if (noCCOnFile)
		delete noCCOnFile;
	*renUserStr << endl;
	OutPutData(efp, renUserStr, nUserAlias);
	if (renUserStr)
		delete renUserStr;	

	fclose(fp); fclose(efp); fclose(dfp);
	
	// mail stuff to Mailing List
	// First send the stats file
	for (int i=0; strlen(mMailList[i])!=0; i++)
	{
		printf("\n\nNow Sending Log Reports to : %s\n", mMailList[i]);
		sprintf(mailCommand,
			"/usr/bin/mail %s < UpdateStats.log",
			mMailList[i]);

		pPipe = popen(mailCommand, "w");
		mailRc = pclose(pPipe);						
		if (mailRc != 0)
			printf("**Error Sendmail returned %d mailing to %s**\n",
				mailRc, mMailList[i]);

		// Now the error log file
		sprintf(mailCommand,
			"/usr/bin/mail %s < UpdateErr.log",
			mMailList[i]);

		pPipe = popen(mailCommand, "w");
		mailRc = pclose(pPipe);						
		if (mailRc != 0)
			printf("**Error Sendmail returned %d mailing to %s**\n",
				mailRc, mMailList[i]);
	}

	if (nRecordsUpdated == 0)
	{
		printf("\n\nNo credit card accounts were updated.\n");
		return true;
	}

	// Now mail auto update notes to users:
	// Check if mail should be sent
	
	while (emailYN[0]!='y' || emailYN[0]!='n')
	{
		printf("\n\nDo you wish email be sent to each of %d users ", nRecordsUpdated);
		printf("who's Credit Card Account was updated? [y|n] : ");
		scanf("%c", emailYN);
		if (emailYN[0] == 'y')
		{
			printf("\n\nAutoMailers will be sent to %d users.\n\n", nRecordsUpdated);
			for (iuser = vUserDetails.begin(); iuser != vUserDetails.end(); 
				iuser++)
			{
				if (!*iuser || strcmp(*iuser, "")==0)
					continue;

				p = strtok(*iuser, ",");
 				if (p)
					strcpy(userId, p);
				p = strtok(NULL, ",");
				if (p)
					strcpy(userName, p);
				p = strtok(NULL, ",");

				oStr =	SetupStream(oStr);
				// Format the message to user
				*oStr	<< "To: ";
				*oStr	<< userId;
				*oStr	<< "\n"
					<< "From: billing@@ebay.com";

				*oStr	<< "\n"
					<< "Subject: eBay Credit Card Approval "
					<< "Email"
					<< "\n";
			
				*oStr 	<< "Customer Account:\t"
					<< userId
					<< "\n";

				*oStr 	<< "Customer Name:\t\t"
					<< userName
					<< "\n";

				*oStr 	<< "Credit Card Account:\t"
					<< p
					<< "-XXXX-XXXX-XXXX"
					<< "\n\n";

				*oStr 	<< "Dear "
					<< userName
					<< ",\n\n";

				*oStr 	<< "Your Credit Card has been approved "
					<< "and placed on file at eBay. "
					<< "Per our policy, please note that "
					<< "$10 was charged to your credit card for "
					<< "verification purposes. " 
					<< "This $10 was credited to your eBay account. "
					<< "Check your Account Status for details.\n\n"
					<< "Please see the following for our complete "
					<< "billing policies: "
					<< mpMarketPlace->GetHTMLPath()
					<< "billing.html."
					<< "\n\nThank you for using eBay!";

				*oStr	<< endl;

				// Send Confirmation note to user
				SendAutoMail(userId, oStr);
				if (oStr)
					delete oStr;
			}
			vUserDetails.erase(vUserDetails.begin(), vUserDetails.end());
			break;
		}
		else if (emailYN[0] == 'n')
		{
			meMailOnNewEntry = false;
			printf("\n\nNo email will be sent to a user who's account was updated.\n");
			break;
		}
		else
		{
			printf("Incorrect Response!.\n\n\n");
			fflush(stdin);
		}	
	}

        return true;
}


void clsUpdateCCApp::OutPutData(FILE *fp, strstream *oStr, int nEntries)
{
	char *pStr;

	if (!fp)
		return;

	*oStr 	<< "\n"
	     	<< "Totals :\t"
		<< nEntries
		<< "\n"
		<< endl;

	pStr = oStr->str();
	pStr[oStr->pcount()] = '\0';

	fprintf(fp, "\n\t-----------------------------\n\n");
	fprintf(fp, "%s", pStr);

	if (pStr)	
		free(pStr);
	
}


void clsUpdateCCApp::GenerateExpiryList(int nParms, char **ParmsList)
{
	AccountsVector vAccounts;
	AccountsVector::iterator i;
	clsUser *pUser;
	clsAccount *pAccount;
	FILE *fp;
	time_t todaysDate, expTime, dbExpTime;
	struct tm *expDate;
	int dayOfYear, Day, Month, Year;
	
	printf("Please wait while the list is being generated.\n");

	fp = fopen("CCExpiryList", "w"); // generate new for every run

	// Initialize
	if (!mpDatabase)
       		mpDatabase = gApp->GetDatabase();
	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();
	if (!mpMarketPlace)
		mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	if (!mpUsers)
		mpUsers = mpMarketPlace->GetUsers();

	// Get all user accounts
	mpUsers->GetUserAccounts(&vAccounts);
	
	printf("** %d Accounts to process for Credit Card Expiry **\n", vAccounts.size());

	
	// parmsList contains the list of expiry dates to process
	for (int j = nParms; j > 2; j--)
	{  	
		printf("Processing all accounts for %s days to expiry.\n\n", ParmsList[j-1]);
		// Get Current machine Time
		time(&todaysDate);
		// convert local time to the struct tm structure
		expDate   = localtime(&todaysDate);

		// Now add ParmsList[j-1] Days to expTime to get time to expiry in t_time format
		expDate->tm_mday += atoi(ParmsList[j-1]);
		expDate->tm_isdst = -1;

		// Change hour to 11:59:59 for precise comparison with DB date-time
	
		expDate->tm_hour  = 23;
		expDate->tm_min   = 59;
		expDate->tm_sec   = 59;
		expTime = mktime((struct tm *)expDate);
		
		// Convert time back to exp time in struct tm * format
		expDate = localtime(&expTime);
		dayOfYear = expDate->tm_yday;
		Day       = expDate->tm_mday;
		Month     = expDate->tm_mon+1;
		Year      = expDate->tm_year+1900;

		// Go thru account list and fish out the ones that are expiring 
		// in ParmsList[j-1] days
		for (i = vAccounts.begin();
			 i != vAccounts.end();
			 i++)
		{
			if (!*i)
				continue;

			pAccount        = (*i)->pAccount;

                	if (!pAccount)
			{
				printf("Error retrieving account for: %d\n", (*i)->id);
                        	continue;
			}
			// Calculate if CC is expiring in ParmsList[j-1] days.
			// Check if a user's CC details are available
			if (pAccount->GetCCIdForUser() <= 0)
			{
				// if (pAccount)
			//		delete pAccount;
				continue;
			}

			dbExpTime = (time_t)pAccount->GetCCExpiryDate();

			if (dbExpTime - expTime == 0)
			{
				pUser  = mpUsers->GetUser((*i)->id, false, false); 
				if (!pUser)
				{
					printf("Error retrieving user information for: %d\n", 
									(*i)->id);
					continue;
				}
				// Check to see if user was suspended or CC_ON_FILE is OFF 
				// if yes then skip
				if (pUser->GetUserState() == UserGhost     ||
		    		    pUser->GetUserState() == UserSuspended ||
				   !pUser->HasCreditCardOnFile()	)
				{
					// User is suspended or a ghost user
					delete pUser;
					continue;
				}
				printf("Found User: %s\n", pUser->GetEmail());
				fprintf(fp, "%d, %s, %d, ", 
					(*i)->id, pUser->GetEmail(), 
					pAccount->GetCCIdForUser());
				fprintf(fp, "%s, ", pUser->GetName());
				fprintf(fp, "%d-%d-%d\n", 
						Month, Day, Year);
				if (pUser)
					delete pUser;
			}

			// if (pAccount)
		//		delete pAccount;
		
		} // for
	} // for

	// Delete vector 
	for (i = vAccounts.begin();
	     i != vAccounts.end();
	     i++)
	{
		delete (*i)->pAccount;
		delete (*i);
	}
	vAccounts.erase(vAccounts.begin(), vAccounts.end());

	fclose(fp);

}


void clsUpdateCCApp::SendEmailToExpiryList()
{
	FILE *fp;
	char *id, *CCId, *expiryDate;
	char scanBuf[500], securePage[100], webtvSecurePage[100];
	char accountOrderPage[100], accountStatusPage[100];
	char *emailId, *userName;
	strstream *oStr;
	struct tm *todaysDate;
	time_t    todaysTime, last_cc_notice_sent;
	int Day, Month, Year;
	clsUser	*pUser;
	clsAccount *pAccount;
	clsMarketPlaces *pMarketPlaces;
	clsMarketPlace  *pMarketPlace;


	// Initialize	
	if (!mpDatabase)
       		mpDatabase = gApp->GetDatabase();


	if (!(fp = fopen("CCExpiryList", "r")))
	{
		printf("CCExpiryList data file is required to run this program.\n");
		return;
	}

	pMarketPlaces = new clsMarketPlaces();
	pMarketPlace  = pMarketPlaces->GetCurrentMarketPlace();

	// sprintf(securePage, "%saccount.html", pMarketPlace->GetHTMLPath());
	// Sam, 06/23/98, After merging kernel changes, the secure page path should
	// be obtained from GetSecureHTMLPath()
	// Sam, 10/20/98, Change Secure server to point to Arribada instead of secure
	// sprintf(securePage, "https://arribada.ebay.com/aw-secure/cc-update.html");
	sprintf(securePage, "https://arribada.ebay.com/aw-secure/cc-update.html");
	sprintf(accountOrderPage, "%saccount-order.html", pMarketPlace->GetHTMLPath());
	sprintf(accountStatusPage, "%saccount-status.html", pMarketPlace->GetHTMLPath());
	// Sam, 01/20/99, Webtv support page link
	sprintf(webtvSecurePage, "%ssecure-webtv-support.html", pMarketPlace->GetHTMLPath());


	// Convert time back to exp time in struct tm * format
	todaysTime	= time(&todaysTime);
	todaysDate	= localtime(&todaysTime);
	Day     	= todaysDate->tm_mday;
	Month   	= todaysDate->tm_mon+1;
	Year    	= todaysDate->tm_year+1900;

	// Change hour to 11:59:59 for precise comparison with DB date-time
	// Give 24 hours window, ie, don't send email again if email was sent
	// in the last 24 hours
	todaysDate->tm_hour  = 23;
	todaysDate->tm_min   = 59;
	todaysDate->tm_sec   = 59;
	todaysTime = mktime((struct tm *)todaysDate);


	while (fgets(scanBuf, 500, fp))
	{
		// Tokenize
		// Eliminate the formatting shit
		if (isalpha(scanBuf[0]) || isspace(scanBuf[0])) // move on buster
			continue;

		// Initialize
		oStr = new strstream();
		// prepare the stream
		oStr->setf(ios::fixed, ios::floatfield);
		oStr->setf(ios::showpoint, 1);
		oStr->precision(2);

		// Tokenize our data from CCExpiryList File
		id 		= strtok(scanBuf, ",");
		emailId 	= strtok(NULL, ",");
		CCId 		= strtok(NULL, ",");
		userName 	= strtok(NULL, ",");
		expiryDate 	= strtok(NULL, ",");
		// Get rid of the newline character from expiryDate
		expiryDate[strlen(expiryDate)-1] = '\0';

		pUser 		= new clsUser(atoi(id));	
		pAccount        = pUser->GetAccount();

		// Do a check to see if mail already sent today before sending mail

		if(pAccount->GetLastCCNoticeSent() == todaysTime)
		{

			printf("Mail was already sent to : %s..skipping..\n", emailId);
			if (pUser)
				delete pUser;
			if (pAccount)
				delete pAccount;
			if (oStr)
			{
				delete oStr;
				oStr = NULL;
			}
			continue; // move on as we have already sent email

		}

		// Mail being sent first time today, so update column in ebay_account_balances
		mpDatabase->UpdateExpiredNoticeSent(atoi(id), todaysTime);		

 
		// Format the message to user
		*oStr	<< "To: ";
		*oStr	<< emailId;
		*oStr	<< "\n"
			<< "From: ccard@@ebay.com";

		*oStr	<< "\n"
			<< "Subject: eBay Credit Card Expiration Notice Email"
			<< "\n";
			
		*oStr 	<< "Customer Account:\t"
			<< emailId 
			<< "\n";

		*oStr 	<< "Customer Name:\t\t"
			<< userName 
			<< "\n";
 
		*oStr 	<< "Credit Card Account:\t"
			<< CCId
			<< "-XXXX-XXXX-XXXX"
			<< "\n";

		*oStr 	<< "Expiration Date:\t"
			<< expiryDate
			<< "\n";

		*oStr 	<< "Date Reminder Issued:\t "
			<< Month
			<< "-"
			<< Day
			<< "-"
			<< Year
			<< "\n\n\n";

		*oStr 	<< "Dear"
			<< userName
			<< ",\n\n";

		*oStr 	<< "This is a courtesy reminder that the credit card on file for your "
			<< "account with eBay will expire soon. "
			<< "In order to provide you with uninterrupted service, "
			<< "please update your "
			<< "Credit Card account information using one of the following methods.\n"
			<< "Important: For security reasons, please DO NOT email us your credit "
			<< "card information. "
			<< "We recommend that you use secure web site link for the easiest, " 
			<< "fastest service.\n\n"
			<< "1) Use the secure web site at "
			<< securePage
			<< "\n"
			<< "The processing time using this method is 12-24 hours."
			<< "\n\n"
			<< "2) Print out the form "
			<< accountOrderPage
			<< " and mail it to us at:\n" 
			<< "EBay Inc.\n"
			<< "2005 Hamilton Ave. Ste. 350\n"
			<< "San Jose, California 95125"
			<< "\n\n"
			<< "The processing time using this method is 5-7 business days."
			<< "\n\n"
			<< "Please be advised that a $10 charge will be applied to your "
			<< "credit card and subsequently be credited to your eBay account. "
			<< "This helps us verify that your credit card was recorded properly. "
			<< "To see your $10 credit please see your eBay account status at the "
			<< "following URL:\n"
			<< accountStatusPage
			<< "\n\n\n"
			<< "Webtv users please visit the link below for instructions on "
			<< "accessing secure pages.\n"
			<< webtvSecurePage
			<< "\n\n"
			<< "Please ignore this email if you have already provided us updated "
			<< "account information.\n\n"
			<< "Thank you for your prompt attention to this matter and thank you for "
			<< "using eBay!\n";

		*oStr	<< endl;

		// Shoot mail to user
		SendAutoMail(emailId, oStr);
		if (oStr)
			delete oStr;		
		oStr = NULL;
		if (pUser)
			delete pUser;
		if (pAccount)
			delete pAccount;

	}
	
	// All done

	if (pMarketPlaces)
		delete pMarketPlaces;

	fclose(fp);
 
}


void clsUpdateCCApp::SendAutoMail(char *emailId, strstream *oStr)
{
	char *pTheNotice;
	char mailCommand[64 + EBAY_MAX_USERID_SIZE +1];
	FILE *pPipe;
	int  mailRc;


	printf("Sending email to : %s\n", emailId);

	pTheNotice = oStr->str();
	pTheNotice[oStr->pcount()] = '\0';

	// strcpy(emailId, "sam@@ebay.com");
	sprintf(mailCommand,
		"/usr/lib/sendmail -odq -f ccard@@ebay.com -F \'eBay Billing\' %s",
		emailId);

	pPipe = popen(mailCommand, "w");
	fprintf(pPipe, "%s", pTheNotice);
	mailRc = pclose(pPipe);						

	if (mailRc != 0)
		printf("**Error Sendmail returned %d mailing to %s**\n",
				mailRc, emailId);
	
	delete pTheNotice;
}
