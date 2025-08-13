/*	$Id: clsComputeAllPastARApp.cpp,v 1.2 1999/02/21 02:21:31 josh Exp $	*/
//
//	File:	clsComputeAllPastARApp.cpp
//
//	Class:	clsComputeAllPastARApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//

//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsComputeAllPastARApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

//
//	This little structure describes, for each month,
//	things about what happened.
//
typedef struct
{
	// These are for Paranoia
	int			mUserId;
	time_t		mBegins;
	time_t		mEnds;

	// These are "Revenue"
	double		mRevenue;
	double		mRevenueFilteredOut;
	double		mDebits;
	double		mDebitsFilteredOut;
	double		mCollections;
	double		mCredits;
	double		mCreditsFilteredOut;
	double		mCreditBalances;
	double		meBayDebits;
	double		meBayCredits;
	double		mDetailTotals[AccountDetailFinalEntry];
	int			mDetailCounts[AccountDetailFinalEntry];
	double		mBalanceFiltered;
	double		mBeginningBalanceUnfiltered;
	double		mBeginningBalanceFiltered;
	double		mBalanceUnfiltered;
} BillingPeriodSummary;

//
// Sort routine
//
static bool sort_account_detail_time(clsAccountDetail *pA, clsAccountDetail *pB)
{
	if (pA->mTime < pB->mTime)
		return true;

	return false;
}


//
// Sort routine
//
static bool sort_ints(int a, int b)
{
	if (a < b)
		return true;

	return false;
}


clsComputeAllPastARApp::clsComputeAllPastARApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsComputeAllPastARApp::~clsComputeAllPastARApp()
{
	return;
};


void clsComputeAllPastARApp::Run(int id, int start, int count)
{
	// Filter Limits
	float							debitFilterLimit = -300.00;
	float							creditFilterLimit = 300.00;
	// Here's the array for all of time. It covers from
	// 1/1/96 until 3/1/98. The first is a "model" summary
	// used to reintialize the per-customer summary for
	// each new customer.
	BillingPeriodSummary			modelSummary[26];
	BillingPeriodSummary			customerSummary[26];
	BillingPeriodSummary			systemSummary[26];

	int								summarySize	
		= sizeof(modelSummary)/sizeof(modelSummary[0]);

	int								summaryCount;

	BillingPeriodSummary			*pSummary;
	BillingPeriodSummary			*pSystemSummary;

	struct tm						beginTimeTM;
	time_t							beginTime;
	struct tm						endTimeTM;
	time_t							endTime;
	const struct tm					*pTheTime;
	char							theTime[32];

	// This is the vector of userids who've got accounts
	vector<unsigned int>						vUsers;
	vector<unsigned int>::iterator				i;
	int											userCount			= 0;
	int											skippedUserCount	= 0;
	int											processedUserCount	= 0;

	clsUser							*pUser;
	bool							iseBayUser;
	clsAccount						*pAccount;

	AccountDetailVector				vAccount;
	AccountDetailVector::iterator	ii;

	char							logFileName[32];
	FILE							*pLogFile;
	char							dataFileName[32];
	FILE							*pDataFile;
	FILE							*pCustFile;

	int								anEye;

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();


	sprintf(logFileName, "pastar.log.%d", id);
	sprintf(dataFileName, "pastar.data.%d", id);

	// Let's populate the array with it's begin/end times. 
	memset(&beginTimeTM, 0x00, sizeof(beginTimeTM));
	beginTimeTM.tm_sec			= 0;
	beginTimeTM.tm_min			= 0;
	beginTimeTM.tm_hour			= 0;
	beginTimeTM.tm_mon			= 00;
	beginTimeTM.tm_mday			= 1;
	beginTimeTM.tm_year			= 96;
	beginTimeTM.tm_isdst		= -1;
	beginTime					= mktime(&beginTimeTM);

	// Populate the table...
	for (summaryCount = 0,
		 pSummary	= &modelSummary[0];
		 summaryCount < summarySize;
		 summaryCount++,
		 pSummary++)
	{
		// Zero it out!
		memset(pSummary, 0x00, sizeof(*pSummary));

		// We assume beginTimeTM is set to the begin time
		// for the current slot.
		pSummary->mBegins	= beginTime;

		// Now, the end time is the beginning of the next
		// month, minus one second. Of course, we have to
		// adjust for going into the next year.
		memcpy(&endTimeTM, &beginTimeTM, sizeof(endTimeTM));
		if (endTimeTM.tm_mon == 11)
		{
			endTimeTM.tm_year++;
			endTimeTM.tm_mon	= 0;
		}
		else
		{
			endTimeTM.tm_mon++;
		}

		endTime	= mktime(&endTimeTM);
		endTime--;

		pSummary->mEnds		= endTime;

		// And, it's so nice, endTime PLUS one second is the 
		// next beginTime.
		memcpy(&beginTimeTM, &endTimeTM, sizeof(beginTimeTM));
		beginTime			= endTime + 1;
	}
		 
	modelSummary[summarySize - 1].mEnds = INT_MAX; 

	for (summaryCount = 0,
		 pSummary	= &modelSummary[0];
		 summaryCount < summarySize;
		 summaryCount++,
		 pSummary++)
	{
		pTheTime	= localtime(&pSummary->mBegins);
		strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
				   pTheTime);
		printf("DaysBack %d, %s-", summaryCount, theTime);
		pTheTime	= localtime(&pSummary->mEnds);
		if (pTheTime > 0)
			strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
					   pTheTime);
		else
			printf("0x%x", pSummary->mEnds);

		printf("%s\n", theTime);
	}	

	// Now, copy the model summary to the system summary
	memcpy(&systemSummary, &modelSummary, sizeof(systemSummary));


	// Ok, let's get all the users who have accounts...
	mpUsers->GetUsersWithAccounts(&vUsers);
	userCount				= vUsers.size();
	processedUserCount		= 0;

	printf("*** %d Users to Process ***\n",
			 userCount);

	sort(vUsers.begin(), vUsers.end(), sort_ints);

	//pCustFile   = fopen("/oracle09/ebay/work/pastar.cust", "a");
	//if (!pCustFile)
	//{
	//   printf("Unable to open pastar.cust! %d (%s)\n",
	//          errno, strerror(errno));
	//   exit(1);
	//}

	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		if  (skippedUserCount < start)
		{
			skippedUserCount++;
			continue;
		}

		if (processedUserCount >= count)
			break;

		pUser	= mpUsers->GetUser((*i));
		if (!pUser)
		{
			printf("** Error ** Can not get user %d\n", (*i));
			processedUserCount++;
			continue;
		}

		printf("%d of %d: %s(%d)", processedUserCount, userCount,
             pUser->GetUserId(), *i);

		// Let's re-initialize the customer summary
		memcpy(&customerSummary, &modelSummary, sizeof(modelSummary));

		// Let's see if it's an eBay user. We do this the "weak" way, 
		// via strstr
		if (strstr(pUser->GetUserId(), "@ebay.com") != NULL)
			iseBayUser	= true;
		else
			iseBayUser	= false;

		pAccount	= pUser->GetAccount();

		if (!pAccount)
		{
			printf("\n** Error ** Could not get account for %s (%d)\n",
					pUser->GetUserId(),
					pUser->GetId());
			processedUserCount++;
			continue;
		}

		// Let's get all their account records. 
		pAccount->GetAccountDetail(&vAccount);

		printf("(%d)\n", vAccount.size());

		// If no entries, no past due!
		if (vAccount.size() == 0)
		{
			delete   pAccount;
			delete   pUser;
			continue;
		}

		// and sort them...
		sort(vAccount.begin(), vAccount.end(), sort_account_detail_time);

		//
		// Loops through the records, which are sorted by date now 
		// (earliest first).
		//
		pSummary			= &customerSummary[0];
		pSummary->mUserId	= pUser->GetId();
		summaryCount	= 0;

		for (ii = vAccount.begin();
			 ii != vAccount.end();
			 ii++)
		{
			// Let's see if it's tooo early
			if ((*ii)->mTime < customerSummary[0].mBegins)
			{
				pTheTime = localtime(&(*ii)->mTime);
				strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
							pTheTime);
				printf("Record at %s for %s(%d) is too young!\n",
						 theTime, pUser->GetUserId(), pUser->GetId());
				continue;
			}

			// First, compare the time of this entry to the 
			// "time" in the NEXT summary entry. If it's
			// later, we need to advance....Remember that
			// the mWhen represents the earliest day in the
			// period. For example, the period for debits 
			// accrued from 90 - 119 days agao, is specified
			// as 90 days ago.
			if ((*ii)->mTime > pSummary->mEnds)
			{
				// Before we advance, we need to see if the 
				// customer has a "credit balance", in which 
				// case we make a note.
				if (pSummary->mBalanceFiltered > 0)
					pSummary->mCreditBalances	+= pSummary->mBalanceFiltered;

				// Yes, advance
				for (summaryCount = summaryCount + 1,
					 pSummary = pSummary + 1;
					 summaryCount < summarySize;
					 summaryCount++,
					 pSummary++)
				{
					// Propagate customer id to next period
					pSummary->mUserId	= (pSummary - 1)->mUserId;

					// Ok, if we're here, we advanced a period,
					// and, thus, the customer's ending balance
					// should be added to the new beginning balance
					pSummary->mBeginningBalanceFiltered	+= 
						(pSummary - 1)->mBalanceFiltered;

					pSummary->mBeginningBalanceUnfiltered +=
						(pSummary - 1)->mBeginningBalanceUnfiltered;

					// And, the current balance should be added to the balance
					// for the period
					pSummary->mBalanceFiltered			+=
						(pSummary - 1)->mBalanceFiltered;

					pSummary->mBalanceUnfiltered		+=
						(pSummary - 1)->mBalanceUnfiltered;

					// Let's see if we're in the right period
					// yet.
					if ((*ii)->mTime <= pSummary->mEnds)
						break;
				}
			}

			// pTheTime = localtime(&(*ii)->mTime);
 			// strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
            // 		pTheTime);

			//printf("%s\t%d\t%8.2f\t%8.2f\n", theTime, (*ii)->mType,
			//        (*ii)->mAmount, pPastDue->mBalance);

			// If it's an eBay user, we just do the credit/debut thang.
			// eBay users don't affect the rest
			if (iseBayUser)
			{
				if ((*ii)->mAmount < 0)
					pSummary->meBayDebits	+=	(*ii)->mAmount;
				else
					pSummary->meBayCredits	+=	(*ii)->mAmount;
			}

			// Revenue and Debits
			if ((*ii)->mAmount < 0)
			{
				if ((*ii)->mType == AccountDetailFeeInsertion	||
					(*ii)->mType == AccountDetailFeeBold		||
					(*ii)->mType == AccountDetailFeeFeatured	||
					(*ii)->mType == AccountDetailFeeFinalValue	||
					(*ii)->mType == AccountDetailFeePartialSale		)
				{
					if ((*ii)->mAmount < debitFilterLimit	||
						iseBayUser)
						pSummary->mRevenueFilteredOut	+= (*ii)->mAmount;
					else
						pSummary->mRevenue				+= (*ii)->mAmount;
				}
				else
				{
					if ((*ii)->mAmount < debitFilterLimit	||
						iseBayUser)
						pSummary->mDebitsFilteredOut	+=	(*ii)->mAmount;
					else
						pSummary->mDebits				+=	(*ii)->mAmount;
				}
			}

			// Collections and Credits
			if ((*ii)->mAmount >= 0)
			{
				if ((*ii)->mType == AccountDetailPaymentCheck		||
					(*ii)->mType == AccountDetailPaymentCC			||
					(*ii)->mType == AccountDetailPaymentCCOnce		||
					(*ii)->mType == AccountDetailPaymentCash		||
					(*ii)->mType == AccountDetailPaymentMoneyOrder	)
				{
					pSummary->mCollections	+= (*ii)->mAmount;
				}
				else
				{
					if ((*ii)->mAmount > creditFilterLimit ||
						iseBayUser)
						pSummary->mCreditsFilteredOut	+=	(*ii)->mAmount;
					else
						pSummary->mCredits				+=	(*ii)->mAmount;
				}
			}

			// Balancation
			if ((*ii)->mAmount < 0)
			{
				if ((*ii)->mAmount < debitFilterLimit	||
					iseBayUser)
					pSummary->mBalanceUnfiltered	+=	(*ii)->mAmount;
				else
					pSummary->mBalanceFiltered		+=	(*ii)->mAmount;

			}
			else
			{
				if ((*ii)->mAmount > creditFilterLimit ||
					iseBayUser)
					pSummary->mBalanceUnfiltered	+=	(*ii)->mAmount;
				else
					pSummary->mBalanceFiltered		+=	(*ii)->mAmount;
			}

			// Ok, WHATEVER it was, let's accumulate the transaction
			// type.
			if ((*ii)->mType == AccountDetailUnknown		||
				(*ii)->mType > AccountDetailFinalEntry			)
			{
				pTheTime = localtime(&(*ii)->mTime);
				strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S",
							pTheTime);

				printf("Record at %s for %s(%d) has invalid type %d!\n",
						 theTime, pUser->GetUserId(), pUser->GetId(),
						 (*ii)->mType);

				continue;
			}

			pSummary->mDetailTotals[(*ii)->mType]	+=
					(*ii)->mAmount;
			pSummary->mDetailCounts[(*ii)->mType]++;


		}

		//
		// Now...let's write the results to a file (for the auditors), and
		// combine it into the system's data.
		//

		for (summaryCount = 0,
			 pSummary	= &customerSummary[0],
			 pSystemSummary	= &systemSummary[0];
			 summaryCount < summarySize;
			 summaryCount++,
			 pSummary++,
			 pSystemSummary++)
		{
			pTheTime	= localtime(&pSummary->mBegins);
			strftime(theTime, sizeof(theTime), "%m/%d/%y",
					   pTheTime);

			//fwrite(pSummary, sizeof(*pSummary), 1, pCustFile);
			pSystemSummary->mBeginningBalanceFiltered
												+=	pSummary->mBeginningBalanceFiltered;
			pSystemSummary->mBeginningBalanceUnfiltered
												+= pSummary->mBeginningBalanceUnfiltered;

			pSystemSummary->mRevenue			+=	pSummary->mRevenue;
			pSystemSummary->mRevenueFilteredOut	+=	pSummary->mRevenueFilteredOut;
			pSystemSummary->mDebits				+=	pSummary->mDebits;
			pSystemSummary->mDebitsFilteredOut	+=	pSummary->mDebitsFilteredOut;
			pSystemSummary->mCollections		+=	pSummary->mCollections;
			pSystemSummary->mCredits			+=	pSummary->mCredits;
			pSystemSummary->mCreditsFilteredOut	+=	pSummary->mCreditsFilteredOut;
			pSystemSummary->mCreditBalances		+=	pSummary->mCreditBalances;
			pSystemSummary->meBayDebits			+=	pSummary->meBayDebits;
			pSystemSummary->meBayCredits		+=	pSummary->meBayCredits;
			pSystemSummary->mBalanceFiltered	+=	pSummary->mBalanceFiltered;
			pSystemSummary->mBalanceUnfiltered	+=	pSummary->mBalanceUnfiltered;

			for (anEye = 0;
				 anEye <= AccountDetailFinalEntry;
				 anEye++)
			{
				pSystemSummary->mDetailTotals[anEye] +=
					pSummary->mDetailTotals[anEye];

				pSystemSummary->mDetailCounts[anEye] +=
					pSummary->mDetailCounts[anEye];
			}
		}

		//fflush(pCustFile);

		// Ok, we're done with this account. Clean up.
		for (ii = vAccount.begin();
			  ii != vAccount.end();
			  ii++)
		{
			delete (*ii);
		}

		vAccount.erase(vAccount.begin(), vAccount.end());

		processedUserCount++;

		//
		// Help us restart by dumping out the accumulated info
		//
		pLogFile	= fopen(logFileName, "w");
		if (!pLogFile)
		{
			printf("Unable to open %s! %d (%s)\n",
					logFileName,
					errno, strerror(errno));
			exit(1);
		}

		//
		// We also log it "ugly" for SQL*Loader
		//
		pDataFile	= fopen(dataFileName, "w");
		if (!pDataFile)
		{
			printf("Unable to open %s! %d (%s)\n",
					dataFileName,
					errno, strerror(errno));
			exit(1);
		}

		fprintf(pLogFile, "User %d of %d: %s (%d)\n",
				processedUserCount,
				vUsers.size(),
				pUser->GetUserId(),
				pUser->GetId());

		for (summaryCount = 0,
			 pSummary	= &systemSummary[0];
			 summaryCount < summarySize;
			 summaryCount++,
			 pSummary++)
		{
			pTheTime	= localtime(&pSummary->mBegins);
			strftime(theTime, sizeof(theTime), "%m/%d/%y",
					   pTheTime);

			fprintf(pLogFile,
					"%s\tbb:%8.2f\tbbuf:%8.2f\tr:%8.2f\trf:%8.2f\td:%8.2f\tdf:%8.2f\tco:%8.2f\tcr:%8.2f\tcrf:%8.2f\tcb:%8.2f\ted:%8.2f\tec:%8.2f\teb:%8.2f\tebuf:%8.2f\n",
					theTime,
					pSummary->mBeginningBalanceFiltered,
					pSummary->mBeginningBalanceUnfiltered,
					pSummary->mRevenue,
					pSummary->mRevenueFilteredOut,
					pSummary->mDebits,
					pSummary->mDebitsFilteredOut,
					pSummary->mCollections,
					pSummary->mCredits,
					pSummary->mCreditsFilteredOut,
					pSummary->mCreditBalances,
					pSummary->meBayDebits,
					pSummary->meBayCredits,
					pSummary->mBalanceFiltered,
					pSummary->mBalanceUnfiltered);

			fprintf(pDataFile,
					"%s\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%03.2f\t%8.2f\t%03.2f\t%03.2f",
					theTime,
					pSummary->mBeginningBalanceFiltered,
					pSummary->mBeginningBalanceUnfiltered,
					pSummary->mRevenue,
					pSummary->mRevenueFilteredOut,
					pSummary->mDebits,
					pSummary->mDebitsFilteredOut,
					pSummary->mCollections,
					pSummary->mCredits,
					pSummary->mCreditsFilteredOut,
					pSummary->mCreditBalances,
					pSummary->meBayDebits,
					pSummary->meBayCredits,
					pSummary->mBalanceFiltered,
					pSummary->mBalanceUnfiltered);

			
			//
			// Now, the transaction counts
			//
			fprintf(pLogFile,
					"%s\t", theTime);


			for (anEye = 0;
				 anEye <= AccountDetailFinalEntry;
				 anEye++)
			{
				fprintf(pLogFile, "%0d:%0d/%03.2f\t",
						anEye, 
						pSummary->mDetailCounts[anEye],
						pSummary->mDetailTotals[anEye]);
				fprintf(pDataFile, "\t%0d\t%03.2f",
						pSummary->mDetailCounts[anEye],
						pSummary->mDetailTotals[anEye]);

			}

			fprintf(pLogFile, "\n");
			fprintf(pDataFile, "\n");

		}
			 
		fclose(pLogFile);
		fclose(pDataFile);

      delete   pAccount;
      delete   pUser;
	}

	//fclose(pCustFile);
}

static clsComputeAllPastARApp *pTestApp = NULL;

int main(int argc, char* argv[])
{
	int		id;
	int		start;
	int		count;

	if (argc != 3)
	{
		printf("Error!!! Not enought arguments!\n");
	}
	else
	{
		id		= atoi(argv[1]);
		start	= atoi(argv[2]);
		count	= atoi(argv[3]);	
	}

	if (!pTestApp)
	{
		pTestApp	= new clsComputeAllPastARApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(id, start, count);

	return 0;
}
