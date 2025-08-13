/*	$Id: clsEmitCreditCardChargesApp.cpp,v 1.4 1999/02/21 02:21:39 josh Exp $	*/
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsEmitCreditCardChargesApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsAccount.h"
#include "clsAccountDetail.h"
#include "vector.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _WIN32
FILE *popen(const char *, const char *);
int pclose(FILE *);
#endif


clsEmitCreditCardCharges::clsEmitCreditCardCharges(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpUsers			= (clsUsers *)0;
	return;
}

clsEmitCreditCardCharges::~clsEmitCreditCardCharges()
{
	return;
};

//==============================================================================

void clsEmitCreditCardCharges::Enqueue( clsUser *pUser, float amount )
{
	clsAuthorizationQueue *pQueue;

	if ( !pUser->HasCreditCardOnFile() )
		return;

	pQueue = mQueue.Enqueue ( NULL, // credit card number
							time_t(0), // expiry date
							1, // priority 1 for batch
							pUser->GetId(), 
							-amount,
							Settlement, // transaction type
							NULL, // account holder name
							NULL, // street address
							NULL, // city address
							NULL, // state - province
							NULL, // zipcode
							NULL, // country
							0 // billing account type
							);

	if ( pQueue )
		delete pQueue;
	return;

}  // Enqueue

//==============================================================================

void clsEmitCreditCardCharges::Run(int userStart, int userEnd, vector<unsigned int> &requestedUsers)
{
	time_t						beginInvoiceTime;
	time_t						endInvoiceTime;

	InterimBalanceList				lInterimBalances;
	InterimBalanceList::iterator	i;

	float							amount;

	int								invoiceCount	= 0;
	int								billedCount		= 0;
	float							totalBilled		= 0;

	clsUser							*pUser;

	FILE							*pICVerify;
	char							fileName[30];
	int								j = 0;
	int								cell = 0;
	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpUsers)
		mpUsers			= mpMarketPlace->GetUsers();


	//
	// Get the last invoice time. Since we do monthly
	// billing right now, and all invoices show the same
	// date, beginInvoiceTime == endInvoiceTime == the 
	beginInvoiceTime	= mpDatabase->GetLastInvoiceDate();
	endInvoiceTime		= beginInvoiceTime;

	if ( requestedUsers.size() == 0 )
		mpDatabase->GetInvoices(beginInvoiceTime,
							endInvoiceTime,
							&lInterimBalances);
 	else
	{
		for (int k = 0; k < requestedUsers.size(); k++ )
		{
			mpDatabase->GetInvoices(beginInvoiceTime, endInvoiceTime, 
				&lInterimBalances, requestedUsers[k] );
		}
	}
	printf("*** %d Invoices to check\n",
			 lInterimBalances.size());

	// Open the file
    j  = sprintf(fileName, "icverify");
    j += sprintf(fileName + j, "%d", userStart);
    j += sprintf(fileName + j, "%s", ".in");
	pICVerify = fopen(fileName, "w");
	if (!pICVerify)
	{
		printf("*** Error! Could not open %s. Error %s (%d)\n",
			   fileName,	
			   errno,
			   strerror(errno));
		return;
	}
				

	// Now, we loop through them
	for (i = lInterimBalances.begin();
		 i != lInterimBalances.end();
		 i++)
	{
		if ((*i)->mId < userStart)
			continue;
		if ((*i)->mId > userEnd)
			break;
/*		if (((*i)->mId == 131470) || ((*i)->mId == 137003) ||
			((*i)->mId == 186395) || ((*i)->mId == 204342) ||
			((*i)->mId == 237173) || ((*i)->mId == 484114) ||
			((*i)->mId == 565917) || ((*i)->mId == 673320) ||
			((*i)->mId == 651386) || ((*i)->mId == 838295) ||
			((*i)->mId == 882921) || ((*i)->mId == 1004053) ||
			((*i)->mId == 340710) || ((*i)->mId == 1154182) ||
			((*i)->mId == 1135229) || ((*i)->mId == 297672) ||
			((*i)->mId == 74405))
			continue;
*/
		invoiceCount++;

		if ((*i)->mBalance > -1 )
		{
			printf("** Skipping %d - %f\n", (*i)->mId, (*i)->mBalance);
			continue;
		}

		// Get the user
		pUser	= mpUsers->GetUser((*i)->mId);

		if (!pUser)
		{
			printf("*** Error! Unable to get user %d\n",
				   (*i)->mId);
			continue;
		}

		if ( !pUser->HasCreditCardOnFile() )
		{
			printf("** Skipping %d - %f\n", (*i)->mId, (*i)->mBalance);
			continue;
		}
		amount	= (*i)->mBalance;

		// This is sooo simple....
		fprintf(pICVerify,
				"c\t%d\t%s\t%8.2f\n",
				pUser->GetId(),
				pUser->GetUserId(),
				amount);

		// Lena - to add enqueue stuff
		Enqueue( pUser, amount );
		billedCount++;
		totalBilled	+=	amount;
		delete	pUser;
	}

	// Clean up....
	for (i = lInterimBalances.begin();
		 i != lInterimBalances.end();
		 i++)
	{
		delete (*i);
	}

	lInterimBalances.erase(lInterimBalances.begin(), lInterimBalances.end());
	fprintf(pICVerify, "** %d Invoices, %d Billed, Total $%8.2f\n",
		   invoiceCount,
		   billedCount,
		   totalBilled);

}
static clsEmitCreditCardCharges *pTestApp = NULL;
int main( int argc, char* argv[] )
{
	int userStart = 0;
	int userEnd = -1;
	char			*pFileName = "user-list.txt";
	FILE			*pFile;
	vector<unsigned int> requestedUsers;
	char			buf[1024];
	int				recLen;
	bool done			= false;

#ifdef _MSC_VER
	g_tlsindex = 0;
#endif

	if (!pTestApp)
	{
		pTestApp	= new clsEmitCreditCardCharges(0);
	}
	if ( argc == 2 )
	{
			pFileName = argv[1];
			pFile	= fopen(pFileName, "r");
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
				requestedUsers.push_back( atoi( buf ) );
			} while (!done);

	}
	if (argc == 3)
	{
		userStart = atoi(argv[1]);
		userEnd = atoi(argv[2]);
	}
	pTestApp->InitShell();
	if ((requestedUsers.size() == 0 ) || (userEnd == -1))
		userEnd = 200000000;
	pTestApp->Run(userStart, userEnd, requestedUsers);

	return 0;
}
