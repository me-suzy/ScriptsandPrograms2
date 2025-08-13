/*	$Id: dailystats.cpp,v 1.3 1999/02/21 02:30:51 josh Exp $	*/
//
//	File:		dailystats.cpp
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
#include "clsDailyStatsApp.h"
#include "string.h"
#include "stdlib.h"
#include "stdio.h"

// expecting pTime in format: dd:mm:yy
//
bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	int		Day;
	int		Month;
	int		Year;
	struct tm*	pTimeTm;

	char	Sep[] = "/";
	char*	p;

	// Get day
	p = strtok(pTime, Sep);
	Month = atoi(p);
	if (Month < 1 || Month > 12)
	{
		return false;
	}

	// Get month
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
        struct tm*      pTimeTm;

        pTimeTm = localtime(pTheTime);
        pTimeTm->tm_sec = 0;
        pTimeTm->tm_min = 0;
        pTimeTm->tm_hour = (pTimeTm->tm_isdst) ? 1 : 0;
        *pTheTime = mktime(pTimeTm);
}


void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tdailystats [-s dd-mm-yy] [-e dd-mm-yy] [-c StatistcsId] [-t XactionId]\n");
}

int main(int argc, char* argv[])
{
	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	char*	pStatisticsId = NULL;
	char*	pXactionId;
	time_t	StartTime = 0;
	time_t	EndTime = 0;
	int		StatisticsId = 0;
	int		XactionId = -1;

	while (--argc)
	{
		switch (argv[Index][1])
		{
		// Get starting date
		case 's':
			pStartDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pStartDate, &StartTime))
			{
				InputError();
				exit(0);
			}
			break;

		// Get ending date
		case 'e':
			pEndDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pEndDate, &EndTime))
			{
				InputError();
				exit(0);
			}
			break;

		case 'c':
			pStatisticsId = argv[++Index];
			Index++;
			argc--;

			StatisticsId = atoi(pStatisticsId);
			break;

		// Get transactiom id
		case 't':
			pXactionId = argv[++Index];
			Index++;
			argc--;

			XactionId = atoi(pXactionId);
			break;

		default:
			InputError();
			return 0;
		}
	}

	if (StartTime == 0 && EndTime != 0)
	{
		printf("Start Date is required when End Date is provided.\n");
		return 0;
	}

	if (StartTime > EndTime && EndTime != 0)
	{
		printf("Start Date should be earlier than the end date.\n");
		return 0;
	}

	if (StartTime == 0)
	{
		// Get the current time and update statitstics for the day before.
		// Set the endtime is the same as the starttime.
		StartTime = time(0) - ONE_DAY;
		RoundToDay(&StartTime);
		EndTime = StartTime;
	}

	clsDailyStatsApp*	pApp = new clsDailyStatsApp();

	pApp->InitShell();

	pApp->Run(StartTime, EndTime, StatisticsId, XactionId);

	delete pApp;

	return 0;
}

