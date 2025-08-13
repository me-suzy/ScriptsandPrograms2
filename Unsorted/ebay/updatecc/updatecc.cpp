//
//	File: 	updatecc.cpp
//
//	Author: Sam Paruchuri
//
//	Function:
//			main()
//
// Modifications:
//			- 02/06/98
//
// Run this program daily as a cron job to update and generate Credit Card (CC)
// related information. The program has 3 run options which are as follows:
// 1. 'update' -- In this option the program reads the raw data file 'RawCCData'
// containing tab delimited CC information fields. It then generates a 'ProcessedCCData'
// file that formats each date entry in RawCCData to be of format MM-DD-YYYY, bad entries
// in RawCCData are reportted in ErrorLog file. Further, the Database is updated with the
// CC information.
// 2. 'explist ndays1 ndays2...ndaysn' -- This option creates a list of all CC's in the
// database that are expiring in ndays1, ndays2...ndaysn days. The file that is created is
// CCExpiryList.
// 3. 'email' -- *MUST* only be be run after 'explist' option. This option sends email 
// notification to all users in CCExpiryList. The email body contains instructions on how
// to provide updated CC information.


#define _MAIN_

#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsUpdateCCApp.h"
#include "stdio.h"


int main (int argc, char **argv)
{

	clsUpdateCCApp *pApp = new clsUpdateCCApp();
//	pApp->InitShell();
	pApp->InitProfile();

	if (argc == 1) // no arguments specified
	{
		printf("Run program with  one of the following arguments : \n");
		printf("update  : update DB with Credit Card details\n");
		printf("explist : print list of CC's which expire in X days\n");
		printf("email   : email users in CCExpiryList\n");
	}
	else if (strcmp(argv[1], "update")==0)
		pApp->UpdateCC();
	else if (strcmp(argv[1], "explist")==0)
	{
		if (argc <= 2)
		{	
			printf("Please Provide List of days to expiry after \"explist.\"\n");
			printf("Each Day must be seperated by a space.\n");
			exit(-1);
		}
		// Pass the list of number of days to expiry
		pApp->GenerateExpiryList(argc, argv);
	}
	else if (strcmp(argv[1], "email")==0)
		pApp->SendEmailToExpiryList();
	else
		printf("Incorrect program argument specified\n");

	delete pApp;

	return 0;
}
