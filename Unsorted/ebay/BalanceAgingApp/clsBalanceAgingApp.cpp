/*	$Id: clsBalanceAgingApp.cpp,v 1.3.202.2 1999/07/29 19:44:18 sliang Exp $	*/
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
#include "clsBalanceAgingApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUser.h"
#include "clsAccount.h"
#include "clsInvAndBalAgingState.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _MSC_VER
#include <process.h>
#else
#include <sys/types.h>
#include <unistd.h>
#include <signal.h> 
#endif

#ifdef _MSC_VER
#include <strstrea.h>
#else
#include <strstream.h>
#endif


clsBalanceAgingApp::clsBalanceAgingApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	return;
}


clsBalanceAgingApp::~clsBalanceAgingApp()
{
	return;
};


void clsBalanceAgingApp::Run(vector<unsigned int>& requestedUsers, int month, 
						int idStart, int idEnd)
{
	// This is the vector of userids who've got accounts
	vector<unsigned int>						vUsers;
	vector<unsigned int>::iterator				i;
	int											userCount;
	int											processedUserCount;

	clsUser							*pUser;
	//clsUser							*pPassedUser;
	clsAccount						*pAccount;

	//dates needed for Past Due recalculations
	struct tm	*pStartDateAsTm;
	int			fromMonth;
	time_t		tStartDate;
	struct tm	*pEndDateAsTm;
	int			toMonth;
	time_t		tEndDate;

	struct tm	*pTodayDateAsTm;
	time_t		tTodayDate;
	int			today_month;
	int			today_year;

	struct tm	*pPastDueBaseAsTm;
	time_t		tPastDueBase;

	//dates needed for end of month recalculatoin
	struct tm	*pInvoiceDateAsTm;
	time_t		tInvoiceDate;

	time_t		lastInvoiceTimeForUser;
	double		interimBalance;

	double		pastEomPayment;
	clsEndOfMonthBalance  *pEndOfMonthBalance;

	//makes it easier to call SetPastDue only one time, if set temp variables
	double	PastDue30,PastDue60,PastDue90,PastDue120,PastDue121,PastDueOld121;
	//dump eom past dues in array
	double	EomPastDues[5];
	//dump past dues in array
	double	PastDues[5];

	int		currentPeriod;
	int		count;

	time_t		nowTime;
	clsInvAndBalAgingState	*pInvAndBalAgingState;
	char							pPid[11];
	int								our_pid;
	char							cThisInvoiceDate[32];
	PidCheckEnum					stateInfo;

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	//INTERIM BLANCE DATE FOR AS OF DATE
	tInvoiceDate = time(0);
	pInvoiceDateAsTm	= localtime(&tInvoiceDate);
	//find last invoice for this date
	gApp->GetDatabase()->InvoiceTime((*pInvoiceDateAsTm), month);
	//refotmat tm into time_t????
	tInvoiceDate = mktime(pInvoiceDateAsTm);
	strftime(cThisInvoiceDate, sizeof(cThisInvoiceDate), "%m/%d/%y",
             pInvoiceDateAsTm);

	/****************** Sonya: Here is the code for checking the state table *****/
	
	//Sonya: get pid
	#ifdef _MSC_VER
		our_pid = getpid();
	#else
		our_pid = _getpid();
	#endif
	sprintf(pPid,"%d", our_pid);

	//Sonya: Now we figured out the tInvoiceTime, pid of this process with the startid, end id and 
	//this invoice time, we can construct a clsInvAndBalAgingState object
	
	//First, construct a object with program type of Invoice, we check if invoice has finish on this range or not
	pInvAndBalAgingState = new clsInvAndBalAgingState(tInvoiceDate, idStart, idEnd, Invoice , pPid);

	stateInfo=pInvAndBalAgingState->GetInvAndBalAgingStateInfo();
	if(stateInfo!=BatchDown)
	{
		printf(" Balance Aging can't run. Invoice at range from %d to %d hasn't been down. \n",
			idStart, idEnd);
		return;
	}
	
	//Now, we are sure we can run this range for balance aging.
	pInvAndBalAgingState->SetProgramType(BalanceAging);
	pInvAndBalAgingState->SetProcessCount(0);

	stateInfo=pInvAndBalAgingState->GetInvAndBalAgingStateInfo();
	switch( stateInfo)
	{
		case  BatchDown :
			printf("the batch from %d to %d for invoice date %s has been down.\n", idStart, idEnd, 
					cThisInvoiceDate);
			return;

		case  BatchNoClearance :
			printf(" the batch record:  from %d to %d for invoice date %s hasn't been cleaned in the state table.\n",
				idStart, idEnd, cThisInvoiceDate);
			return;

		case  BatchRerunOK :
			time(&nowTime);
			pInvAndBalAgingState->SetStartTime(nowTime);
			pInvAndBalAgingState->IncrementProcessCount();
			if(!pInvAndBalAgingState->UpdateInvAndBalAgingStateInfo())
			{
				printf("Can't update the record in the table. Balance Aging exit\n");
				return;
			}
			break;

		case  BatchNotExist :
			
			if(pInvAndBalAgingState->IsRangeOverlap())
			{
				printf(" Current batch has a range from %d to %d overlapped with existing ranges\n",
					pInvAndBalAgingState->GetStartId(), pInvAndBalAgingState->GetEndId());
				return;
			}

			time(&nowTime);
			pInvAndBalAgingState->SetStartTime(nowTime);
			pInvAndBalAgingState->IncrementProcessCount();
			if(!pInvAndBalAgingState->CreateInvAndBalAgingStateInfo())
			{
				printf("Can't create the record in the table. Invoice exit\n");
				return;
			}
			pInvAndBalAgingState->IncrementRangeOverlapCount();

			if(pInvAndBalAgingState->IsRangeOverlap())
			{	
				pInvAndBalAgingState->CleanUpOverlappedRecord();
				printf(" Warning: two instances overlapped on range and run at about the same time!\n");
				return;
			}
			
			
			break;

		default :
			printf(" An error occured when trying to get the state info form state table\n");
			return;
	}
			
	/*****************************/
	
	
	// If we were passed a user, then we just do that one,
	// otherwise we do ALL the users with accounts
	if ( requestedUsers.size() > 0 )
		vUsers = requestedUsers;
	
		/* Sonya: this part is modified to sync with Invoice
		//		mpUsers->GetUsersWithAccounts(&vUsers);
		if (pUserId)
		{
			pPassedUser	= mpUsers->GetUser(pUserId);
			if (!pPassedUser)
			{
				printf("** Error %s could not be found\n",
					   pUserId);
				return;
			}

			vUsers.push_back(pPassedUser->GetId());
			userCount=1;

			delete	pPassedUser;
			pPassedUser	= NULL;
		}*/
	else
	{
		/*	if (pFromUser)
			{
		*/
		//a range of users have been passed to this program
		mpDatabase->GetAllUsersWithAccountsRange(&vUsers,idStart,
														idEnd);
		userCount = vUsers.size();
		/*
		}
		else
		{
			// Ok, let's get all the users who have accounts...
			mpUsers->GetUsersWithAccounts(&vUsers);
			userCount = vUsers.size();
		}
		*/
	}
	//we are done creating user vector!

	
	
	printf("*** %d Users to Process ***\n",
			 userCount);

	//let get our interim balances dates here so we do it only one time
	//INTERIM BALANACE DATE FOR OVER 150 DAYS
	tStartDate = time(0);
	pStartDateAsTm	= localtime(&tStartDate);
	fromMonth = pStartDateAsTm->tm_mon - 6;
	if (fromMonth < 0 )
	{
		fromMonth = (pStartDateAsTm->tm_mon - 6) + 12 ;
		pStartDateAsTm->tm_year --; 
	}
	//update tm structre 
	pStartDateAsTm->tm_mon = fromMonth;
	//find last invoice for this date
	gApp->GetDatabase()->InvoiceTime((*pStartDateAsTm),fromMonth);
	//refotmat tm into time_t????
	tStartDate = mktime(pStartDateAsTm);

	//INTERIM BALANACE DATE FOR 30 DAYS
	tEndDate = time(0);
	pEndDateAsTm	= localtime(&tEndDate);
	toMonth = pEndDateAsTm->tm_mon - 1;
	if (toMonth < 0 )
	{
		toMonth = (pEndDateAsTm->tm_mon - 1) + 12;
		pEndDateAsTm->tm_year --; 
	}
	//update tm structre 
	pEndDateAsTm->tm_mon = toMonth;
	//find last invoice for this date
	gApp->GetDatabase()->InvoiceTime((*pEndDateAsTm),toMonth);
	//refotmat tm into time_t????
	tEndDate = mktime(pEndDateAsTm);

/* Sonya: We move this part of code to the start part for use of read state table

  //INTERIM BLANCE DATE FOR AS OF DATE
	tInvoiceDate = time(0);
	pInvoiceDateAsTm	= localtime(&tInvoiceDate);
	//find last invoice for this date
	gApp->GetDatabase()->InvoiceTime((*pInvoiceDateAsTm),pInvoiceDateAsTm->tm_mon);
	//refotmat tm into time_t????
	tInvoiceDate = mktime(pInvoiceDateAsTm);
*/


	// Now, we loop through our users
	processedUserCount		= 0;
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		//declare inside the loop, will not need to eraise this way
		InterimBalanceList				lInterimBalances;
		InterimBalanceList::iterator	ii;

		pUser	= mpUsers->GetUser((*i));
		if (!pUser)
		{
			printf("** Error ** Can not get user %d\n", (*i));
			continue;
		}

		pAccount	= pUser->GetAccount();

		if (!pAccount)
		{
			printf("** Error ** Could not get account for %s (%d)\n",
					pUser->GetUserId(),
					pUser->GetId());
			continue;
		}

		//check if aging was already done for this month:
		tPastDueBase = pAccount->GetPastDueBase();
		//only if pasduebase was ever set do next check
					
		//let's get system date in a tm structure:
		tTodayDate = time(0);
		pTodayDateAsTm	= localtime(&tTodayDate);

		if (tPastDueBase)
		{
			//save neede parts, new local time will replace this tm struct
			today_month = pTodayDateAsTm->tm_mon;
			today_year  =pTodayDateAsTm->tm_year;

			pPastDueBaseAsTm	= localtime(&tPastDueBase );

			if (pPastDueBaseAsTm->tm_mon == today_month &&
					pPastDueBaseAsTm->tm_year == today_year)
			{
				printf("** Error ** Account for %s (%d) has been aged already\n",
						pUser->GetUserId(),
						pUser->GetId());
				continue;
			}
		}

		//printf("Processing Account for %s (%d)\n",pUser->GetUserId(),pUser->GetId());

		//lets set all varables to be new past due amounts
		PastDue30=0;
		PastDue60=0;
		PastDue90=0;
		PastDue120=0;
		PastDue121=0;
		PastDueOld121=0;
		//this for loop execute at the most once for each past due = 5 times
		//if we see that current info is good past due, we break from this loop
		for (currentPeriod=30; currentPeriod <= 180; currentPeriod = currentPeriod+30)
		{
			switch (currentPeriod)
			{
			case 30: 
				PastDue30 = pAccount->CalculateXPastDue(30, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("30:");
				break;
			case 60: 
				PastDue60 = pAccount->CalculateXPastDue(60, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("60:");
				break;
			case 90: 
				PastDue90 = pAccount->CalculateXPastDue(90, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("90:");
				break;
			case 120: 
				PastDue120 = pAccount->CalculateXPastDue(120, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("120:");
				break;
			case 150: 
				PastDue121 = pAccount->CalculateXPastDue(121, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("121:");
				break;
			case 180: 
				PastDueOld121 = pAccount->CalculateXPastDue(150, (*i), 
								&lInterimBalances, tStartDate, tEndDate);
				//DEBUG printf("Old121:");
				break;
			}

			//if this PastDue Happen to be 0, we are in luck
			//this user has no past due any more, starting with current bucker
			//and we set all buckets to 0 to start with!
			if ((PastDue30 == 0 && currentPeriod == 30) ||  
				(PastDue60 == 0 && currentPeriod == 60) ||  
				(PastDue90 == 0 && currentPeriod == 90) || 
				(PastDue120 == 0 && currentPeriod == 120) ||  
				(PastDue121 == 0 && currentPeriod == 150)||
				(PastDueOld121 == 0 && currentPeriod == 180))  
				break;

			//whatever period we just read in did not have past due 0

			//if this is first period,PastDue30 is set-all we need for the first period
			if (currentPeriod == 30)
				continue;

			//for other periods,let see if they are NOT correct now in account balances
			if ((PastDue60 != pAccount->GetPastDue30Days()  && currentPeriod == 60) ||  
				(PastDue90 !=  pAccount->GetPastDue60Days() && currentPeriod == 90) || 
				(PastDue120 !=  pAccount->GetPastDue90Days() && currentPeriod == 120) ||  
				(PastDue121 !=  pAccount->GetPastDue120Days() && currentPeriod == 150)||
				(PastDueOld121 !=  pAccount->GetPastDueOver120Days() && currentPeriod == 180))  
			//and if they were not correct in the account balances we need to keep 
			//newly calculated past due as good value and deal with next bucket
			//newly calculate past due is already in PastDueX
				continue;

			//if we are here this must be a time when past due calculate actualy
			///was equal to the value inside account balances - which should 
			//be for most users, if rebalance ran regularly.
			switch (currentPeriod)
			{
			case 60: 
				PastDue60 = pAccount->GetPastDue30Days();
				PastDue90 =  pAccount->GetPastDue60Days();
				PastDue120 =  pAccount->GetPastDue90Days();
				PastDue121 =  pAccount->GetPastDue120Days();
				PastDueOld121 = pAccount->GetPastDueOver120Days();
				break;
			case 90: 
				PastDue90 = pAccount->GetPastDue60Days();
				PastDue120 =  pAccount->GetPastDue90Days();
				PastDue121 =  pAccount->GetPastDue120Days();
				PastDueOld121 = pAccount->GetPastDueOver120Days();
				break;
			case 120: 
				PastDue120 = pAccount->GetPastDue90Days();
				PastDue121 =  pAccount->GetPastDue120Days();
				PastDueOld121 = pAccount->GetPastDueOver120Days();
				break;
			case 150: 
				PastDue121 = pAccount->GetPastDue120Days();
				PastDueOld121 = pAccount->GetPastDueOver120Days();
				break;
			case 180: 
				PastDueOld121 = pAccount->GetPastDueOver120Days();
				break;
			}
			break;
		}

		/* DEBUG 
		printf ("After PAST DUE before EOM:\n");
		for (ii = lInterimBalances.begin();
				 ii!= lInterimBalances.end();
						ii++)
		{ 
			printf("balance is %8.2f\n", (*ii)->mBalance);
		}*/
		
		 //DEAL WITH End Of Month Table here
		//get interim balance
		if ((pAccount->GetInterimBalance((*i), 
			lastInvoiceTimeForUser,interimBalance,0)))
		{
			//is this correct interim_balance?
			if ((lastInvoiceTimeForUser == tInvoiceDate) && (interimBalance !=0))
			{
				//so far we know that interim balance exists,get payments
				pAccount->GetPaymentsSince(tInvoiceDate,pastEomPayment);
					
				//if there were no payments, just take buckets and write to 
				//end of month table:
				if (pastEomPayment==0)
				{
					pEndOfMonthBalance = new clsEndOfMonthBalance((*i),
														tTodayDate,
														interimBalance,
														tInvoiceDate,
														PastDue60,
														PastDue90,
														PastDue120,
														PastDue121,
														PastDueOld121);

					pEndOfMonthBalance->AddEndOfMonthBalanceDelayed();

					delete pEndOfMonthBalance;
				}
				else 
				{
					//let's see if our payments affected past due

					//clear end of month past due array;populate past due array
					for (int x = 0; x < 5; x++)
					{
						  EomPastDues[x] = 0.00;
					}
					PastDues[0] = PastDue60;
					PastDues[1] = PastDue90;
					PastDues[2] = PastDue120;
					PastDues[3] = PastDue121;
					PastDues[4] = PastDueOld121;

					//we need to do this to each past due or until we hit 1st 0
					//as soon as 0 past due detected, the  rest are 0s, too
					for (x = 0; x < 5; x++)
					{
						if (PastDues[x] < 0)
						{
							EomPastDues[x] = PastDues[x] - pastEomPayment;
						}
						else
						{

									/* DEBUG */
		printf ("Inside EOM User %d:\n", (*i));
		for (ii = lInterimBalances.begin();
				 ii!= lInterimBalances.end();
						ii++)
		{ 
			printf("balance is %8.2f\n", (*ii)->mBalance);
		}
							//let's see if interim balance is here and is it 0?

							//our interim balances are in the lInterimBalances list
							//can we stop here? is node there?  
							if ( lInterimBalances.size() == 0 
								|| lInterimBalances.size() < x+2)
							{
								//no interim balance; we are lucky; just leave 0s
								break;
							}

							//pick a node by period - traverse list until found
							count=0;
							for (ii = lInterimBalances.begin();
								 ii!= lInterimBalances.end();
								ii++)
							{ 
								count++;
								if (count == (x+2))
									break;
							}
							//is interim balance 0 for this month?
							if ((*ii)->mBalance >= 0)
							{
								//no interim balance; we are lucky; just leave 0s
								break;
							}
				
							//if we are here, we are unlucky; we need to caluculate eom past due 
				
							//get all payement up to eom date
							pAccount->GetPaymentsByDate((*ii)->mWhen,tInvoiceDate,pastEomPayment);

							EomPastDues[x] = (*ii)->mBalance + pastEomPayment;
							if (EomPastDues[x] >= 0)		
							{
								//we are lucky again, if past due is 0 all after are 0s too
								EomPastDues[x] = 0.00;
								break;
							}
						
						}//end of else inside the loop: 0 past due algorithm

					}//end of for loop to caluculate past due - payments
			
					//we have all the info now, lets make a record in eom table

					pEndOfMonthBalance = new clsEndOfMonthBalance((*i),
												tTodayDate,
												interimBalance,
												tInvoiceDate,
												EomPastDues[0],
												EomPastDues[1],
												EomPastDues[2],
												EomPastDues[3],
												EomPastDues[4]);

					pEndOfMonthBalance->AddEndOfMonthBalanceDelayed();
					
					delete pEndOfMonthBalance;
				}//end of if payments/no payments, record is written

			}//end of 0 interim balance, no eom records written

		}//end of no interim balacne found, no eom records written
		//end of dealing with End Of Month Table

        //ok we got out Past Dues now, lets call DB update
		pAccount->SetPastDue(tTodayDate,
							PastDue30,
							PastDue60,
							PastDue90,
							PastDue120,
							PastDue121);

		
		//DEBUG printf("30 Past Due SET to %f\n", PastDue30);
		//DEBUG printf("60 Past Due SET to %f\n", PastDue60);
		//DEBUG printf("90 Past Due SET to %f\n", PastDue90);
		//DEBUG printf("120 Past Due SET to %f\n", PastDue120);
		//DEBUG printf("121 Past Due SET to %f\n", PastDue121);

		processedUserCount++;
		//let's report progress
		if (processedUserCount % 100 == 0)
			printf("Updated %d users.\n", processedUserCount);

		for (ii = lInterimBalances.begin(); 
		     ii != lInterimBalances.end(); 
			 ii++)	
		{
			delete (*ii);
		}
		lInterimBalances.erase(lInterimBalances.begin(), lInterimBalances.end());

		if(pAccount)
			delete	pAccount;
	}

	printf("Updated %d users.\n", processedUserCount);

	time(&nowTime);
	pInvAndBalAgingState->SetEndTime(nowTime);
	pInvAndBalAgingState->MakeInstanceComplete();

}


bool clsBalanceAgingApp::ReadData( char *fileName, int &month, 
							 vector<unsigned int> &requestedUsers,
							 int &idStart, int &idEnd )
{
	char			*pFileName = "user-list.txt";
	FILE			*pFile;
	char			buf[1024];
	char            bufMonth[10];
	int				recLen;
	bool done			= false;
	int currentInvoiceMonth = 0;

	pFileName = fileName;
	pFile	= fopen(pFileName, "r");
	if (!pFile)
	{
		fprintf(stderr,
			"Error %s opening %s\n",
			strerror(errno), 
			pFileName);
		return false;
	}

	do
	{
		if (!fgets(buf, sizeof(buf), pFile))
		{
			done = true;
			break;
		}
	// Remove pesky trailing newline
		if ( buf[0] == '/' )
			continue;
		recLen	= strlen(buf);
		if (buf[recLen - 1] == '\n')
			buf[recLen - 1]	= '\0';
		if ( buf[2] == '/' )
		{
			bufMonth[0] = buf[0];
			bufMonth[1] = buf[1];
			bufMonth[2] = '\0';
			month = atoi( bufMonth );
		}
		else
		{
			if ( buf[0] == 's' )
				idStart = atoi( &buf[1] );
			else
			{
				if ( buf[0] == 'e' )
					idEnd = atoi( &buf[1] );
				else
					requestedUsers.push_back( atoi( buf ) );
			}
		}

	} while (!done);
	return true;

}// clsBalanceAgingApp::ReadData

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tBalanceAging [-d mm/yy -s StartId -e EndId]\n");
	printf("OR\n");
	printf("Usage:\n\tBalanceAging [-s StartId -e EndId]\n\n");
	printf("NOTE:\t1. mm/yy specifies the invoice month\n");
	printf("\t2. Without -d mm/yy, invoice month will be the last month of current month.\n");
	printf("\t3. StarId must be smaller than the EndId.\n");
}

/* Sonya: the code before we sync with invoice
void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tBalanceAging [-s int] [-e int]\n");
	printf("OR\n");
	printf("Usage:\n\tBalanceAging [-u userid]\n");
	printf("OR\n");
	printf("Usage:\n\tBalanceAging\n");
}*/


static clsBalanceAgingApp *pTestApp = NULL;

int main(int argc, char* argv[])
{		
	vector<unsigned int> requestedUsers;
	int currentInvoiceMonth = 0;
	int idStart = 0;
	int idEnd = 0;
	int Index=1;
	char InvoiceMonth[10];
	time_t  startTime, endTime;
	

#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp = new clsBalanceAgingApp(0);
	}

	// Sonya: When invoked with one paramether, it should be a file name, and Invoice will read
	// the range and (invoice month, this is opitional)  input info from the file.
	if ( argc == 2 )
	{
		if ( !pTestApp->ReadData( argv[1], currentInvoiceMonth, requestedUsers, idStart, idEnd ))
		{
			fprintf(stderr,
				"Error reading data\n",
				strerror(errno) );
			return 1;
		}
	}

	//Sonya: when the Invoice is invoked with more than 2 parameters, the range and invoice month is specified 
	// directly by these  parametes 
	if (argc >2) 
	{
		while (--argc)
		{
			switch (argv[Index][1])
			{
				// Get invoice month, just take the first two character in the
				// parameter following -d and tail it with null character
				case 'd':
					InvoiceMonth[0] = argv[++Index][0];
					InvoiceMonth[1] = argv[Index][1];
					InvoiceMonth[2] = '\0';
					Index++;
					argc--;
					currentInvoiceMonth= atoi(InvoiceMonth);
					if ((currentInvoiceMonth) >12||(currentInvoiceMonth<=0))
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

	}

	if(idStart>=idEnd)
	{
		InputError();
		exit(0);
	}

	pTestApp->InitShell();

#if _DEBUG
	time( &startTime );
#endif

	pTestApp->Run( requestedUsers, currentInvoiceMonth, idStart, idEnd);
#if _DEBUG
	time( &endTime );
#endif

	return 0;


/* Sonya: this is the code used before we sync balance aging with invoice
	int		Index = 1;
	char	*pUserId=NULL;
	char	*pFromUser=NULL;
	char	*pToUser=NULL;
	int		FromUser, ToUser;
	
	// we need this for Oracle to be able to connect
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif


	while (--argc)
	{
		switch (argv[Index][1])
		{
			// Get starting user id
			case 's':
				pFromUser = argv[++Index];
				FromUser=atoi(pFromUser);
				Index++;
				argc--;
				break;
					
			//Get End User
			case 'e':
				pToUser = argv[++Index];
				ToUser=atoi(pToUser);
				Index++;
				argc--;
				break;

			// Get a userid
			case 'u':
				pUserId = argv[++Index];
				Index++;
				argc--;
				break;

			default:
				InputError();
				return 0;
		}
	}

	//if there is From User there must be To User
	if (((pFromUser) && (!pToUser)) || ((!pFromUser) && (pToUser)))
	{
			InputError();
			return 0;
	}

	//if there is a specific user NO From User or To User allowed!
	if (((pUserId) && (pToUser)) || ((pUserId) && (pFromUser)))
	{
			InputError();
			return 0;
	}

	// users must be in ortder smaller start, greater end
	if ((pFromUser) && (FromUser > ToUser))
	{
		printf("Invalid user Range: Start User is greater than End User.\n");
		return 0;
	}
	// due to vector reserve problem lets limit the number ofvector cells
	// to reserve to 9,999,999
	if ((pFromUser) && ((ToUser-FromUser)>9999999))
	{
		printf("Invalid user Range: End User - Start User must be <= 9,999,9999.\n");
		return 0;
	}

	if (!pTestApp)
	{
		pTestApp	= new clsBalanceAgingApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run(pUserId, pFromUser, pToUser);
	printf("got here");

	return 0;
	*/  
}
