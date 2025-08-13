/*	$Id: RebuildList.cpp,v 1.4 1999/02/21 02:23:42 josh Exp $	*/
//
//	File:		RebuildList.cpp
//
//	Author:	Wen Wen
//
//	Function:
//			main()
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#define __MAIN__

#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsRebuildListApp.h"

// expecting pTime in format: mm/dd/yy
//
bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	int Day;
	int Month;
	int Year;
	struct tm*pTimeTm;

	char    Sep[] = "/";
	char*   p;

        p = strtok(pTime, Sep);
        // Get month
        Month = atoi(p);
        if (Month < 1 || Month > 12)
        {
                return false;
        }

        // Get day
        p = strtok(NULL, Sep);
        Day = atoi(p);
        if (Day < 1 || Day > 31)
        {
                return false;
        }

        // Get Year
        p = strtok(NULL, Sep);
        Year = atoi(p);
        if (Year < 0)
        {
                return false;
        }

        // put the day, month, and year together
        *pTimeTValue = time(0);
        pTimeTm = localtime(pTimeTValue);
        pTimeTm->tm_sec = 0;
        pTimeTm->tm_min = 0;
        pTimeTm->tm_hour = (pTimeTm->tm_isdst) ? 1 : 0;
        pTimeTm->tm_mday = Day;
        pTimeTm->tm_mon = Month-1;
        pTimeTm->tm_year = Year;
        *pTimeTValue = mktime(pTimeTm);

        return true;
}

// Round the input time to the day, i.e. set hour, minute, and second to 0
void RoundToDay(time_t *pTheTime)
{
	struct tm*	pTimeTm;

	pTimeTm = localtime(pTheTime);
        pTimeTm->tm_sec = 0;
        pTimeTm->tm_min = 0;
        pTimeTm->tm_hour = (pTimeTm->tm_isdst) ? 1 : 0;
	*pTheTime = mktime(pTimeTm);
}


int main(int argc, char* argv[])
{
	int	Index = 1;
	char*	pTestingPath = NULL;
	bool	BuildingCompleted = false;
	time_t	BuildDate = time(0);
	time_t	RequestedDate;
	int	Days;
	int	BuildTwoDays = false; // build for one day
	int	IsSecondTime;

	// use the default one
	strcpy(TEMP_LISTING_FILE_PATH, LISTING_FILE_PATH);

	while (--argc)
	{
		switch (argv[Index][1])
		{
		case 'l':
			strcpy(TEMP_LISTING_FILE_PATH, argv[++Index]);
			Index++;
			argc--;
			break;

		case 't':
			pTestingPath = new char[strlen(argv[++Index])+1];
			strcpy(pTestingPath, argv[Index]);
			Index++;
			argc--;
			break;

		case 'c':
			BuildingCompleted = true;
			RoundToDay(&BuildDate);
			Index++;

			if (argc-1 > 0 && argv[Index][0] != '-')
			{

				// rebuild for a specific date
				if (!ConvertToTime_t(argv[Index], &RequestedDate))
				{
					// wrong syntax
					printf("Syntax error!\n");
					printf("Usage:\n\tRebuildList [-l new_listing_path] [-t testing_path] [-c [mm/dd/yy]]\n");
					return 0;
				}

				// check if the requested date is later than today 
				if (RequestedDate > BuildDate)
				{
					// wrong date
					printf("Invalid date specified!\n");
					printf("RebuildList only builds the list of completed items ending on the previous day or earlier.\n");
					return 0;
				}
				BuildDate = RequestedDate + ONE_DAY;
				argc--;
				Index++;
			}
			else
				BuildTwoDays = true;	// user does not specify a date, we build the listings for
							// yesterday and the day earlier.
						
			break;

		default:
			// wrong syntax
			printf("Syntax error!\n");
			printf("Usage:\n\tRebuildList [-l new_listing_path] [-t testing_path] [-c [days | mm/dd/yy]]\n");
			return 0;
		}
	}
	
	// prepare for the loop
	BuildDate += ONE_DAY;
	IsSecondTime = 0;

	do
	{
		IsSecondTime++;
		if (IsSecondTime == 2)
		{
			BuildTwoDays = false;
		}

		BuildDate -= ONE_DAY;

		clsRebuildListApp*	pApp = new clsRebuildListApp();

		pApp->InitShell();
		if (BuildTwoDays)
			pApp->LogMessage("Build for two days");

		pApp->LogMessage("Rebuild started");
		pApp->SetBuildDate(BuildDate);

		if (!pApp->Run(pTestingPath, BuildingCompleted))
		{
			pApp->LogMessage("Rebuild failed");
			delete [] 	pTestingPath;
			delete pApp;
			return 0;
		}

		pApp->LogMessage("Rebuild success");
		pApp->LogMessage(" ");

		delete pApp;

	} while (BuildTwoDays);

	delete [] pTestingPath;

	return 1;
}
