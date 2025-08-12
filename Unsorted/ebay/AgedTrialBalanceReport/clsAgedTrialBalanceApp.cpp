/*	$Id: clsAgedTrialBalanceApp.cpp,v 1.3.202.3 1999/07/21 20:36:54 sliang Exp $	*/
//
//	File:	clsBalanceAgingApp.cpp
//
//	Class:	clsBalanceAgingApp
//
//	Author:	inna markov (inna@ebay.com)
//
//	Function:
//

//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsAgedTrialBalanceApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsAccount.h"
#include "clsUsers.h"
#include "clsUser.h"

#include <stdio.h>
#include <string.h>
#include <errno.h>
#include <time.h>

clsAgedTrailBalanceApp::clsAgedTrailBalanceApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;

	return;
}


clsAgedTrailBalanceApp::~clsAgedTrailBalanceApp()
{
	return;
};


void clsAgedTrailBalanceApp::Run(time_t tInvoiceDate, int idStart, int idEnd)
{
	// This is the vector of userids who've got accounts
	vector<unsigned int>						vUsers;
	vector<unsigned int>::iterator				i;

	struct tm		*pInvoiceDateAsTm;

	clsEndOfMonthBalance *pEndOfMonthBalance;

	
	//Sonya add the reportTime to name the report file
	struct tm		*pThisReportTimeTM;
	time_t			thisReportTime=0;
	char			cThisReportTime[32];
	char			cReportFileName[64];
	//const char		cReportFileNameConst[64];
	char			cIdStart[32];
	char			cIdEnd[32];
	//Sonya:end
	time_t			thisInvoiceTime = 0;
	struct tm		*pThisInvoiceTimeTM;
	char			cThisInvoiceTime[32];

	clsUser			*pUser;
	char			*userid;

	//sonya: add status and the powerlevel
	int status;
	int topSellerLevel;
	//sonya: end
	
	


	// report output file
	FILE							*pReportFile;
	
	//Sonya Name the report file using format EOAmm_dd_yysnnnnnnn_ennnnnn
	//n stands for digits in start id and end id.

	time(&thisReportTime);
	pThisReportTimeTM=localtime(&thisReportTime);
	strftime(cThisReportTime, sizeof(cThisReportTime), "%m_%d_%y",pThisReportTimeTM);
	sprintf(cIdStart,"%d", idStart);
	sprintf(cIdEnd, "%d", idEnd);
	strcpy(cReportFileName, "EOM");
	strcat(cReportFileName, cThisReportTime);
	strcat(cReportFileName,"s");
	strcat(cReportFileName, cIdStart);
	strcat(cReportFileName, "_");
	strcat(cReportFileName,"e");
	strcat(cReportFileName, cIdEnd);
	
	pReportFile	= fopen(cReportFileName, "w+");
	//Sonya: end

	if (!pReportFile)
	{
		fprintf(stderr,"%s:%d Unable to open report file. \n",
			  __FILE__, __LINE__);
		return;
	}

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();
	
	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	//actually get invoice time; tInvoiceTime is mon and year of a running month
	pInvoiceDateAsTm	= localtime(&tInvoiceDate);
	gApp->GetDatabase()->InvoiceTime((*pInvoiceDateAsTm),pInvoiceDateAsTm->tm_mon);
	tInvoiceDate = mktime(pInvoiceDateAsTm);

	//get users with accounts
	mpDatabase->GetUsersForThisMonth(&vUsers,tInvoiceDate, idStart, idEnd);
	
	
		//for each user get balance information and print it
		for (i = vUsers.begin();
				i != vUsers.end();
							i++)
		{			
			pEndOfMonthBalance=mpDatabase->GetEndOfMonthBalanceById((*i),tInvoiceDate);
			
			//little time conversion
			if (thisInvoiceTime==0)
			{
				thisInvoiceTime = pEndOfMonthBalance->GetPastDueBase();
				pThisInvoiceTimeTM	= localtime(&thisInvoiceTime);
				strftime(cThisInvoiceTime, sizeof(cThisInvoiceTime),
				"%m/%d/%y", pThisInvoiceTimeTM);
				//print heading
				fprintf(pReportFile,"eBay Inc\t\tAged Trial Balance\n");
				fprintf(pReportFile,"\t\t\tAs Of %s\n",cThisInvoiceTime);
				fprintf(pReportFile,"-------- User -------\t \tLast\tBalance\tPast\tPast\tPast\tPast\tPast\tStatus\tTopSellerLevel\n");
				fprintf(pReportFile,"         Id\tAccount\tInvoice\tDue\tDue\t30-59\t60-89\t90-119\t120+\t\t\n");
			}

			pUser	= mpDatabase->GetUserAndInfoById((*i));
			if (!pUser)
				userid="";
			else
			{
				userid=pUser->GetUserId();
				status=pUser->GetUserState();
				topSellerLevel=pUser->GetTopSellerLevel();
			}

			fprintf(pReportFile,"%s\te%d\t%s\t%8.2f\t%8.2f\t%8.2f\t%8.2f\t%8.2f\t%8.2f\t%d\t%d\n", 
							userid,
							pEndOfMonthBalance->GetId(),
							cThisInvoiceTime,
							RoundToCents(pEndOfMonthBalance->GetBalance()),
							RoundToCents(pEndOfMonthBalance->GetPastDue30Days()),
							RoundToCents(pEndOfMonthBalance->GetPastDue30Days())-
								RoundToCents(pEndOfMonthBalance->GetPastDue60Days()),
							RoundToCents(pEndOfMonthBalance->GetPastDue60Days())-
								RoundToCents(pEndOfMonthBalance->GetPastDue90Days()),
							RoundToCents(pEndOfMonthBalance->GetPastDue90Days())-
								RoundToCents(pEndOfMonthBalance->GetPastDue120Days()),
							RoundToCents(pEndOfMonthBalance->GetPastDue120Days()),
							status,
							topSellerLevel);
			
			delete pEndOfMonthBalance;
		}
	

	fclose(pReportFile);

	return;
}


void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tAgedTrialBalance [-d mm/yy]\n");
	printf("OR\n");
	printf("Usage:\n\tAgedTrialBalance\n");;
}

// expecting pTime in format: dd:mm:yy
//
time_t ConvertToTime_t(char* date)
{
	struct tm	theTimeTm;
	time_t		tTime;
	int			mon;
	int			year;

	char	Sep[] = "/";
	char*	p;

	// Get month
	p = strtok(date, Sep);
	mon = atoi(p);
	if (mon < 1 || mon > 12)
	{
		return 0;
	}

	// Get Year
	p = strtok(NULL, Sep);
	year = atoi(p);
	if (year < 0)
	{
		return 0;
	}
	//preset tm struct year and month
	memset(&theTimeTm, 0x00, sizeof(theTimeTm));
	//parse date

	theTimeTm.tm_year = year;
	theTimeTm.tm_mon = mon;

	tTime = mktime(&theTimeTm);

	if (tTime == -1)
		return 0;

	return tTime;
}


static clsAgedTrailBalanceApp *pTestApp = NULL;

int main(int argc, char* argv[])
{	
	int		Index = 1;

	//sonya add idStart and idEnd
	int idStart = 0;
	int idEnd = 0;

	time_t	tInvoiceDate = 0;
	char*	pInvoiceDate = NULL;
	
	// we need this for Oracle to be able to connect
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif

	while (--argc)
	{
		switch (argv[Index][1])
		{
			// Get start month/year
			case 'd':
				pInvoiceDate = argv[++Index];
				Index++;
				argc--;
				tInvoiceDate= ConvertToTime_t(pInvoiceDate);
				if (tInvoiceDate==0)
				{
					InputError();
					exit(0);
				}
				break;

			case 's':
				idStart=atoi(argv[++Index]);
				Index++;
				argc--;
				break;
			case 'e':
				idEnd=atoi(argv[++Index]);
				Index++;
				argc--;
				break;

			default:
				InputError();
				return 0;
		}
	}
	//populate date if not sent in run-time parametrs
	if (tInvoiceDate==0)
		tInvoiceDate=time(0);

	if (!pTestApp)
	{
		pTestApp	= new clsAgedTrailBalanceApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(tInvoiceDate,idStart, idEnd);
	printf("got here");

	return 0;
}
