/*	$Id: clsBDStatsApp.cpp,v 1.2 1999/02/21 02:30:29 josh Exp $	*/
//
//	File:	clsBDStatsApp.cc
//
//  Class:	clsBDStatsApp
//
//	Author:	Chad Musick (chad@ebay.com)
//
//	Function: Integrate all the various parts of the business development stats programs
//

#include "clsBDStatsApp.h"
#include "clsBDTallyData.h"
#include "clsLogReadData.h"

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>

// Constructor
clsBDStatsApp::clsBDStatsApp(unsigned char *pRequest)
{
	return;
}

// Destructor
clsBDStatsApp::~clsBDStatsApp()
{
	return;
}

// Run function, does the work.
void clsBDStatsApp::Run(time_t startTime, time_t endTime)
{
	clsBDTallyData *pTally;
	time_t theNumTimeEnd;
	struct tm *pTheTimeEnd;
	clsLogReadData *pLogRead;

	pLogRead = new clsLogReadData(0, startTime);

	pTally = new clsBDTallyData(pLogRead);
	pTally->Initialize(startTime, endTime);
	pLogRead->Run(pTally);
	
	do
	{
/*		theNumTimeEnd = startTime + 86400;
		pTheTimeEnd = localtime(&theNumTimeEnd);

		// Correct for DST
		if (pTheTimeEnd->tm_hour == 1)
			theNumTimeEnd -= 3600;
		else if (pTheTimeEnd->tm_hour == 23)
			theNumTimeEnd += 3600;

		if (theNumTimeEnd > endTime)
			theNumTimeEnd = endTime;

		pTally->ResetTimeToTally(startTime, theNumTimeEnd);
*/		pTally->Tally();
		pTally->StoreAndClearTallies();

		startTime = theNumTimeEnd;
	} while (0); //startTime < endTime);


	pLogRead->Output();
	
	delete pLogRead;
	delete pTally;

	return;
}

// Class instance
static clsBDStatsApp *pBDStatsApp = NULL;

// main function for BDStats project.
// Creates a clsBDStatsApp object and calls
// it with the provided dates.
//
int main(int argc, char *argv[])
{
	int day, month, year;
	struct tm theTimeStart;
	time_t theNumTimeStart;
	time_t theNumTimeEnd;
	struct tm *pTheTimeEnd;

	if (argc != 2 && argc != 3)
	{
		cerr << "Syntax: " << argv[0] << " mm-dd-yyyy\n" << flush;
		exit(1);
	}

	if (sscanf(argv[1], "%d-%d-%d", &month, &day, &year) != 3)
	{
		cerr << "Syntax: " << argv[0] << " mm-dd-yyyy\n << flush";
		exit(1);
	}


	if (month < 1 || month > 12)
	{
		cerr << "Valid months are 1-12.\n";
		exit(1);
	}

	if (day < 1 || day > 31)
	{
		cerr << "Valid days are 1-31 (or the last day of the month).\n";
		exit(1);
	}

	if (year < 1990)
	{
		cerr << "Please specify a valid four digit year.\n";
		exit(1);
	}

	memset(&theTimeStart, '\0', sizeof (struct tm));

	theTimeStart.tm_mday = day;
	theTimeStart.tm_mon = month - 1;
	theTimeStart.tm_year = year - 1900;

	theNumTimeStart = mktime(&theTimeStart);
	if (theNumTimeStart == -1)
	{
		cerr << "Invalid date format.\n";
		exit(1);
	}

	if (argc == 2)
	{
		theNumTimeEnd = theNumTimeStart + 86400;
		pTheTimeEnd = localtime(&theNumTimeEnd);

		// Correct for DST.
		if (pTheTimeEnd->tm_hour == 1)
			theNumTimeEnd -= 3600;
		else if (pTheTimeEnd->tm_hour == 23)
			theNumTimeEnd += 3600;
	}
	else
	{
		if (sscanf(argv[2], "%d-%d-%d", &month, &day, &year) != 3)
		{
			cerr << "Syntax: " << argv[0] << " mm-dd-yyyy\n << flush";
			exit(1);
		}


		if (month < 1 || month > 12)
		{
			cerr << "Valid months are 1-12.\n";
			exit(1);
		}

		if (day < 1 || day > 31)
		{
			cerr << "Valid days are 1-31 (or the last day of the month).\n";
			exit(1);
		}

		if (year < 1990)
		{
			cerr << "Please specify a valid four digit year.\n";
			exit(1);
		}

		memset(&theTimeStart, '\0', sizeof (struct tm));

		theTimeStart.tm_mday = day;
		theTimeStart.tm_mon = month - 1;
		theTimeStart.tm_year = year - 1900;

		theNumTimeEnd = mktime(&theTimeStart);

		if (theNumTimeEnd == -1)
		{
			cerr << "Invalid date format.\n";
			exit(1);
		}
	}

	if (!pBDStatsApp)
	{
		pBDStatsApp	= new clsBDStatsApp(0);
	}

	pBDStatsApp->InitShell();
	pBDStatsApp->Run(theNumTimeStart, theNumTimeEnd);

	return 0;
}
