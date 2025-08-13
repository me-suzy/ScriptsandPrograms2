/*	$Id: clsBidListCleanupApp.cpp,v 1.2.202.2 1999/05/28 04:33:49 josh Exp $	*/
// clsBidListCleanupApp.cpp: implementation of the clsBidListCleanupApp class.
//
//////////////////////////////////////////////////////////////////////

#include <fstream.h>
#include "clsApp.h"
#include "clsDatabaseOracle.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"

#include "clsBidListCleanupApp.h"

#ifdef _MSC_VER
// These things aren't declared for MSC.
extern "C" int getopt(int, char**, char*);
extern "C" char *optarg;
extern "C" int optind;
#endif
//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsBidListCleanupApp::clsBidListCleanupApp(bool bDebug, int processLimit, bool verbose) : 
	mpDatabase(NULL),
	mpMarketPlaces(NULL),
	mpMarketPlace(NULL),
	mpUsers(NULL),
	mpItems(NULL),
	mbDebug(bDebug), 
	mProcessLimit(processLimit),
	mTotalProcessed(0),
	mNumberDeleted(0),
	mbVerbose(verbose)
{
    mpDatabase = (clsDatabaseOracle *) GetDatabase();
    mpMarketPlaces = GetMarketPlaces();
    mpMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mMarketPlaceId = mpMarketPlace->GetId();
    mpUsers = mpMarketPlace->GetUsers();
    mpItems = mpMarketPlace->GetItems();

#ifdef _MSC_VER
    g_tlsindex = 0;
#endif
    SetApp(this);
}

clsBidListCleanupApp::~clsBidListCleanupApp()
{

}
				

void clsBidListCleanupApp::ClearAList(vector<unsigned int>ListToClear, 
									 pfGetItemList pGetItemList,
									 pfDeleteCache pCacheDeleter)
{
	int user;
	time_t currentTime = time(0);
	for (user = 0; mTotalProcessed < mProcessLimit && user < ListToClear.size(); ++user)
	{
		int current_user = ListToClear[user];
		mTotalProcessed++;
		if (mbVerbose && !((user + 1) % 1000))
		{
			cout << user << " caches processed ("
				<< mTotalProcessed / (float) mProcessLimit
				<< "%); "
				<< mNumberDeleted << " records "
				<< "("
				<< mNumberDeleted / (float) mTotalProcessed
				<< ")"
				<< (mbDebug ? " would be " : "")
				<< "deleted."
				<< endl;
		}
		
		BidderOrSellerItemList lItems;
		(mpDatabase->*pGetItemList)(mMarketPlaceId,
			current_user,
			&lItems);

		BidderOrSellerItemList::iterator iB;
		bool bKillme = true;
		time_t latest_time = 0;
		for (iB = lItems.begin(); iB != lItems.end(); ++iB)
		{
			time_t endtime = (*iB).mSaleEnd;
			if (endtime > latest_time)
				latest_time = endtime;
			if (endtime > currentTime - (60 * 60 * 24 * 30))
			{
				bKillme = false;;
				if (!mbDebug)
					break;
			}
		}
		if (bKillme) 
		{
			++mNumberDeleted;
			if (!mbDebug)
				(mpDatabase->*pCacheDeleter)(mMarketPlaceId, current_user);
			if (mbVerbose)
				cout << "User " << current_user << (mbDebug ? " would be " : " ") << "deleted." << endl;
		}
		else
		{
			if (mbVerbose)
			{
				cout << "User " << current_user << " not deleted; newest auction ends ";
				struct tm *tmtime = localtime(&latest_time);
				cout << asctime(tmtime);
			}
		}
	}
}



int clsBidListCleanupApp::Run(bool bDoBidders, bool bDoSellers)
{
	vector<unsigned int>AllBidLists;
	vector<unsigned int>AllSellLists;

	if (bDoBidders)
		mpDatabase->GetUsersWithBidderLists(AllBidLists);
	if (bDoSellers)
		mpDatabase->GetUsersWithSellerLists(AllSellLists);


	int blSize = AllBidLists.size() + AllSellLists.size();
	if (mProcessLimit > blSize)
		mProcessLimit = blSize;

	cout << "Processing ";
	if (mProcessLimit != -1)
		cout << mProcessLimit << " of ";
	cout << AllBidLists.size()
		 << " bid lists and "
		 << AllSellLists.size()
		 << " sell lists." << endl;

	if (mProcessLimit == -1)
		mProcessLimit = blSize;


	mNumberDeleted = 0;

	if (bDoBidders)
	{
		ClearAList(AllBidLists, 
			(pfGetItemList) clsDatabaseOracle::GetBidderItemListFromBidderList, 
			(pfDeleteCache) clsDatabaseOracle::DeleteBidderList);
	}
	if (bDoSellers)
	{
		ClearAList(AllSellLists, 
			(pfGetItemList) clsDatabaseOracle::GetSellerItemListFromSellerList, 
			(pfDeleteCache) clsDatabaseOracle::DeleteSellerList);
	}		

	cout << mTotalProcessed << " caches processed, "
		<< mNumberDeleted << " records "
		<< (mbDebug ? "would be " : "")
		<< "deleted." << endl;


	return 1;
}

void usage(void)
{
	cerr << "Usage: BidListCleanup [flags]" << endl
		<< "  where flags include" << endl
		<< "  -b       Do bidder caches" << endl
		<< "  -s       Do seller caches" << endl
		<< "  -d       debug" << endl
		<< "  -v       verbose" << endl
		<< "  -n ##    number to proces" << endl
		<< " At least one of -b and -s must be specified" << endl
		;
}


int main(int argc, char **argv)
{
	bool bDebug = false;
	int processLimit = -1;
	bool bDoBidders = false;
	bool bDoSellers = false;
	bool bVerbose = false;

	// Parse the command line arguments.
	int c;
	
	while ((c = getopt(argc, argv, "n:dbsv")) != EOF)
	{
		switch(c)
		{
		case 'd':
			bDebug = true;
			break;
		case 'n':
			processLimit = atoi(optarg);
			break;
		case 'b':
			bDoBidders = true;
			break;
		case 's':
			bDoSellers = true;
			break;
		case 'v':
			bVerbose = true;
			break;
		case '?':
		default:
			usage();
			return 0;
		}
	}
	
	if (++optind < argc || !(bDoBidders | bDoSellers))
	{
		usage();
		return -1;
	}
	
	return clsBidListCleanupApp(bDebug, processLimit, bVerbose).Run(bDoBidders, bDoSellers);
}
