/*	$Id: clsRebalanceAllAccountsApp.cpp,v 1.4 1999/02/21 02:23:38 josh Exp $	*/
//
//	File:	clsRebalanceAllAccountsApp.cpp
//
//	Class:	clsRebalanceAllAccountsApp
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
#include "clsRebalanceAllAccountsApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"

#include "vector.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>


clsRebalanceAllAccountsApp::clsRebalanceAllAccountsApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	return;
}


clsRebalanceAllAccountsApp::~clsRebalanceAllAccountsApp()
{
	return;
};


void clsRebalanceAllAccountsApp::Run()
{
	// This is the great mother vector of users
	vector<unsigned int>	vUsers;

	// And it's iterator
	vector<unsigned int>::iterator	i;

	clsUser							*pUser;
	clsAccount						*pAccount;
	double							oldBalance;

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();

	// First, let's get the users
	mpUsers->GetUsersWithAccounts(&vUsers);

	printf("*** %d Accounts to rebalance ***\n", vUsers.size());
	// Now, we loop through them
	for (i = vUsers.begin();
		 i != vUsers.end();
		 i++)
	{
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

		oldBalance	= pAccount->GetBalance();

		printf("Balance %s (%d)\t\t\tOld: $%8.2f\n",
				pUser->GetUserId(),
				pUser->GetId(),
				(float)oldBalance);

		pAccount->Rebalance();

	}
}

static clsRebalanceAllAccountsApp *pTestApp = NULL;

int main()
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp	= new clsRebalanceAllAccountsApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
