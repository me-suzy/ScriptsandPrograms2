/*	$Id: clsBDReportsApp.cpp,v 1.2 1999/02/21 02:20:59 josh Exp $	*/
//  Class:	clsBDReportsApp
//
//	Author:	Chad Musick (chad@ebay.com)
//
//	Function: Integrate all the various parts of the business development stats programs
//

#include "clsBDReportsApp.h"
#include "clsBDRunPages.h"

#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <time.h>

#ifdef _MSC_VER
#include <direct.h>
#define mkdir _mkdir
#define chdir _chdir
#else
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#define mkdir(x) mkdir(x, 0777)
#endif

clsBDReportsApp::clsBDReportsApp(unsigned char *pRequest)
{
	return;
}


clsBDReportsApp::~clsBDReportsApp()
{
	return;
}

// All we really do for Run is get a clsBDRunPages and
// call run in it. We're ever so helpful.

void clsBDReportsApp::Run(time_t startTime, time_t endTime)
{
	clsBDRunPages *pRunner;

	pRunner = new clsBDRunPages(startTime, endTime);

	pRunner->Run();

	delete pRunner;

	return;
}

// Stores the class instance.
static clsBDReportsApp *pBDReportsApp = NULL;

// main function for the BDReports project.
// Figures out what day(s) we want to report, gets the time_t
// for them, figures out what directory we want the pages in,
// and switches to that.
int main(int argc, char *argv[])
{
	int day, month, year;
	struct tm theTimeStart;
	time_t theNumTimeStart;
	time_t theNumTimeEnd;
	struct tm *pTheTimeEnd;

	if (argc != 3)
	{
		cerr << "Syntax: " << argv[0] << " mm-dd-yyyy directory\n" << flush;
		exit(1);
	}

	if (sscanf(argv[1], "%d-%d-%d", &month, &day, &year) != 3)
	{
		cerr << "Syntax: " << argv[0] << " mm-dd-yyyy directory\n << flush";
		exit(1);
	}

	if (chdir(argv[2]))
	{
		cerr << "Could not change to " << argv[2] << " for directory.\n";
		exit(1);
	}

	if (chdir(argv[1]))
	{
		mkdir(argv[1]);
		if (chdir(argv[1]))
		{
			cerr << "Could not change to or create date directory " << argv[1] << endl;
			exit(1);
		}
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

	theNumTimeEnd = theNumTimeStart + 86400;
	pTheTimeEnd = localtime(&theNumTimeEnd);

	// Correct for DST.
	if (pTheTimeEnd->tm_hour == 1)
		theNumTimeEnd -= 3600;
	else if (pTheTimeEnd->tm_hour == 23)
		theNumTimeEnd += 3600;

	if (!pBDReportsApp)
	{
		pBDReportsApp	= new clsBDReportsApp(0);
	}

	pBDReportsApp->InitShell();
	pBDReportsApp->Run(theNumTimeStart, theNumTimeEnd);

	return 0;
}
