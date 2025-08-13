/*	$Id: clsLoadAccountSplitApp.cpp,v 1.3 1999/02/21 02:23:24 josh Exp $	*/
//
//	File:	clsLoadAccountSplitApp.cpp
//
//	Class:	clsLoadAccountSplitApp
//
//	Author:	inna markov
//
//	Function:
//
//		Loads New ebay_accounts_# tables from bay_account table
//
// Modifications:
//

#include "clsLoadAccountSplitApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"

#include <stdio.h>
//#include <errno.h>
#include <time.h>


clsLoadAccountSplitApp::clsLoadAccountSplitApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers	= (clsUsers *)0;

	return;
}


clsLoadAccountSplitApp::~clsLoadAccountSplitApp()
{
	return;
};


void clsLoadAccountSplitApp::Run(int FromUser, int ToUser, time_t StartTime)
{
	// This is the vector of users taht have account_balances record 
	// and are in the range and have not been "split" into new tables yet
	vector<unsigned int>	vUsers;
	// And it's iterator
	vector<unsigned int>::iterator	i;

	//we need a place to hold user data
	clsUser		*pUser;


	//this table indicator tells class database which new table user belongs to:
	int tableIndicator;

	// save current state temporarily
	UserStateEnum	currUserState;

	//this flag used to mark records taht already are in the new table
	//in case of reprocessing killed job
	bool	skipFlag;

	//keep count to know when to commit
	int count=0;

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	// First, let's get the users in the Range from account balances
	mpDatabase->GetUsersWithUnsplitAccountsRange(&vUsers,FromUser,ToUser);


	// Now, we loop through users
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
		//set up table indicator based on User ID
		tableIndicator = *i %10;

		// This vector holds ebay_accounts records + items id for each user 
		// in the vUsers vector. The ebay_account records in this vector
		// are from date in the copmmand line arg forward
		AccountDetailVector				vAccount;
		// And it's iterator
		AccountDetailVector::iterator	ii;

		// This vector holds ebay_accounts_x records. These records will 
		//exist only if jopb was killed when writing records to ebay_accounts_x
		AccountDetailVector				vSplitAccount;
		// And it's iterator
		AccountDetailVector::iterator	iii;

		// regardless of this user having current details or NOT we should
		// set user in maintenace, even if no need to split preven him from 
		// creting new ebay_accounts records until his ebay_account_balances
		// are updated with current indicator.

		pUser=mpUsers->GetUser(*i);
		currUserState = pUser->GetUserState();
		pUser->SetInMaintenance();

		printf("In Maintenance set for %d user.Old Status=%d.\n", (*i), currUserState);
		pUser->UpdateUser();

		//let create the account vector for each user, one by one
		mpDatabase->GetAccountDetail((*i), -1, &vAccount,StartTime);
		//let create the account vector for each user, one by one
		mpDatabase->GetAccountDetail((*i), tableIndicator, &vSplitAccount,StartTime);

	// Now, we loop through accounts 
		if (vAccount.size() == 0)
		{
			printf("No detailes for %d user.", (*i));
			printf(" No Split.\n");
		}

		else
		{	
			printf("Start Update for %d user.\n", (*i));

			for (ii = vAccount.begin();
				 ii != vAccount.end();
				ii++)
			{
				skipFlag=false;
				// let's see if any records are already in the ebay_accounts_X?
				if (vSplitAccount.size() > 0)
				{
					// check if this vAccount node has a match in vAccountSplit
					for (iii = vSplitAccount.begin();
						iii != vSplitAccount.end() && (!skipFlag);
						iii++)
					{
						if ((*ii)->mTransactionId == (*iii)->mTransactionId)
						{
							// get rid of this SplitAcounts node - not needed
							delete (*iii);
							vSplitAccount.erase(iii);
							skipFlag=true;
						}
					}
				}

				
				// Go ahead and write this to ebay_accounts-X table
				// this function has no commit; commit happens when you update 
				// ebay_account_balances with a new table indicator
				if (!skipFlag)
				{
					mpDatabase->LoadAccountDetail((*i),tableIndicator,(*ii));
					count++;
					if (count%20 == 0)
						mpDatabase->End();
				}
						
			}
			
			//now let's free the memory used by this vector
			for (ii = vAccount.begin();
				ii != vAccount.end();
				ii++)
			{
				delete (*ii);
			}



			// since I delclare it inside the loop, no  need to erase it
			//vAccount.erase(vAccount.begin(), vAccount.end());

		}

		//just to be sure, this will only hppen if someone chaged accountsX table
		for (iii = vSplitAccount.begin();
			iii != vSplitAccount.end();
			iii++)
		{
			delete (*iii);
		}

		// now we need to update ebay_account_balnces table 
		// to tell it where the new records are
		// it also commits at this point
		mpDatabase->UpdateIndicator((*i),tableIndicator);
		
		printf("End Update for %d user.Commited.\n", (*i));

		pUser->SetUserState(currUserState);
		pUser->UpdateUser();
		printf("In Maintenance Unset for %d user.\n\n", (*i));
			
	}

	// since I only use it once, no need to erase,
	// it is also vector of int - so no clean up needed
	//vUsers.erase(vUsers.begin(), vUsers.end());

	//debug
	printf("got here\n");
	
	return;
}


// expecting pTime in format: dd:mm:yy
//
bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	// need to parse input
	int		Day;
	int		Month;
	int		Year;
	struct tm	theTimeTm;

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

	// put the day, month, and year together; make it 00:00:00 time	
	memset(&theTimeTm, 0x00, sizeof(theTimeTm));
	theTimeTm.tm_mday = Day;
	theTimeTm.tm_mon = Month-1;
	theTimeTm.tm_year = Year;
	// this will prevent from adding 1 to 00 hour for Daylight savings time
	theTimeTm.tm_isdst	= -1;

	// need to convert to time_t, this function modified this input parameter.
	*pTimeTValue = mktime(&theTimeTm);

	return true;
}

static clsLoadAccountSplitApp *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tLoadAccoutSplit [-s int] [-e int] [-d mm/dd/yy]\n");
}


int main(int argc, char* argv[])
{
	int		Index = 1;
	char*	pStartDate = NULL;
	time_t	StartTime = 0;

	int FromUser;
	int ToUser;

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
				FromUser = atoi(argv[++Index]);
				Index++;
				argc--;
				break;
					
			//Get End User
			case 'e':
				ToUser = atoi(argv[++Index]);
				Index++;
				argc--;
				break;

			// Get start date
			case 'd':
				pStartDate = argv[++Index];
				Index++;
				argc--;
				if (!ConvertToTime_t(pStartDate, &StartTime))
				{
					InputError();
					exit(0);
				}
				break;

			default:
				InputError();
				return 0;
		}
	}

	// users must be in ortder smaller start, greater end
	if (FromUser > ToUser)
	{
		printf("Invalid user Range: Start User is greater than End User.\n");
		return 0;
	}
	// due to vector reserve problem lets limit the number ofvector cells
	// to reserve to 9,999,999
	if ((ToUser-FromUser)>9999999)
	{
		printf("Invalid user Range: End User - Start User must be <= 9,999,9999.\n");
		return 0;
	}

	if (!pTestApp)
	{
		pTestApp	= new clsLoadAccountSplitApp(0);
	}


	pTestApp->InitShell();
	pTestApp->Run(FromUser, ToUser, StartTime);

	return 0;
}

