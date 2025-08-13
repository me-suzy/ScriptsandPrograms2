/*	$Id: clsBidListCleanupApp.h,v 1.2 1999/02/21 02:21:12 josh Exp $	*/
// clsBidListCleanupApp.h: interface for the clsBidListCleanupApp class.
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_CLSBIDLISTCLEANUPAPP_H__BC6F80D9_A456_11D2_96D1_00C04F990638__INCLUDED_)
#define AFX_CLSBIDLISTCLEANUPAPP_H__BC6F80D9_A456_11D2_96D1_00C04F990638__INCLUDED_

#if _MSC_VER >= 1000
#pragma once
#endif // _MSC_VER >= 1000

typedef bool (clsDatabaseOracle::*pfGetItemList)(MarketPlaceId, unsigned int, list<clsBidderOrSellerItem>*);
typedef void (clsDatabaseOracle::*pfDeleteCache)(MarketPlaceId, unsigned int);


class clsBidListCleanupApp : public clsApp  
{
public:
	int Run(bool bDoBidders, bool bDoSellers);
	clsBidListCleanupApp(bool bDebug, int processLimit, bool verbose);
	virtual ~clsBidListCleanupApp();

private:
	clsDatabaseOracle *mpDatabase;
	clsMarketPlaces *mpMarketPlaces;
	clsMarketPlace *mpMarketPlace;
	clsUsers *mpUsers;
	clsItems *mpItems;
	MarketPlaceId mMarketPlaceId;
	int mProcessLimit;
	unsigned int mNumberToProcess;
	bool mbDebug;
	void clsBidListCleanupApp::ClearAList(vector<unsigned int>ListToClear, 
									 pfGetItemList pGetItemList,
									 pfDeleteCache pCacheDeleter);
	int mNumberDeleted;
	int mTotalProcessed;
	bool mbVerbose;
	
};

#endif // !defined(AFX_CLSBIDLISTCLEANUPAPP_H__BC6F80D9_A456_11D2_96D1_00C04F990638__INCLUDED_)
