/*	$Id: clsNewBigSellers.cpp,v 1.2 1999/02/21 02:23:34 josh Exp $	*/
//
// clsNewBigSellers.cpp: implementation of the clsNewBigSellers class.
//
//	Author: Josh Gordon
//	Date: 12/17/1998
//
//  Function: Get a list of all users newer than a specified age who have more
//		than a specified number of auctions.
//
//////////////////////////////////////////////////////////////////////

#include "clsApp.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"

#include "clsNewBigSellers.h"

#ifdef _MSC_VER
// These things aren't declared for MSC.
extern "C" int getopt(int, char**, char*);
extern "C" char *optarg;
extern "C" int optind;
#endif

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsNewBigSellers::clsNewBigSellers() : 
	mpDatabase(NULL), mpMarketPlaces(NULL), 
	mpMarketPlace(NULL), mpUsers(NULL), mpItems(NULL)
{
    mpDatabase = GetDatabase();
    mpMarketPlaces = GetMarketPlaces();
    mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
    mpUsers = mpMarketPlace->GetUsers();
    mpItems = mpMarketPlace->GetItems();
#ifdef _MSC_VER
    g_tlsindex = 0;
#endif
    SetApp(this);
}

clsNewBigSellers::~clsNewBigSellers()
{
    mpDatabase = NULL;
    mpMarketPlaces = NULL;
    mpMarketPlace = NULL;
    mpUsers = NULL;
    mpItems = NULL;
}

/* void clsDatabaseOracle::GetNewBigSellers(MarketPlaceId marketplace,
										 int maxAge,
										 int minItems,
										 vector<int> &userVector) */

int clsNewBigSellers::Run()
{
	vector<int> newBigSellers;

	mpDatabase->GetNewBigSellers(mpMarketPlace->GetId(), mAgeOfUsers, mNumberOfAuctions, newBigSellers);

	if (newBigSellers.empty())
		cout << "No qualifying users\n";
	else
		copy(newBigSellers.begin(), newBigSellers.end(), ostream_iterator<int>(cout, "\n"));
	cout << flush;
	return 0;
}

int clsNewBigSellers::mNumberOfAuctions = 20;
int clsNewBigSellers::mAgeOfUsers = 10;

void clsNewBigSellers::usage()
{
	cout <<
		 "Usage: NewBigSellers [flags]\n"
		 "  where flags include:\n"
		 "  -n ##  -- number of auctions to qualify (default is "
		 << mNumberOfAuctions << ")\n" <<
		 "  -a ## -- age of users to qualify (default is "
		 << mAgeOfUsers << ")\n";
}


int main(int argc, char **argv)
{
	// Parse the command line arguments.
	int c;

	while ((c = getopt(argc, argv, "n:a:")) != EOF)
	{
		switch(c)
		{
		case 'n':
			clsNewBigSellers::mNumberOfAuctions = atoi(optarg);
			break;
		case 'a':
			clsNewBigSellers::mAgeOfUsers = atoi(optarg);
			break;
		case '?':
		default:
			clsNewBigSellers::usage();
			return 0;
		}
	}

	if (++optind < argc)
	{
		clsNewBigSellers::usage();
		return -1;
	}

	return clsNewBigSellers().Run();
}

