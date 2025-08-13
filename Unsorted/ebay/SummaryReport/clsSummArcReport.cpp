/*	$Id: clsSummArcReport.cpp,v 1.2 1999/02/21 02:24:35 josh Exp $	*/
//
//	File:	clsSummaryArcReportApp.cpp
//
//	Class:	clsSummaryArcReportApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Generates the total items and total price for a given time period
//		from the archived data in the database
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsSummArcReport.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsBid.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>


clsSummaryArcReportApp::clsSummaryArcReportApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpCategories	= (clsCategories *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsSummaryArcReportApp::~clsSummaryArcReportApp()
{
	return;
};


void clsSummaryArcReportApp::Run(char *fromdate, char *todate)
{
	// This is the vector of itemids that met our criteria
	// (date range)
	vector<int>						vItems;
	vector<int>::iterator			i;
	clsItem							*pItem;

	// for dutch highbidders
	BidVector						vBids;
	BidVector::iterator				vI;

	int								QtySold;

	// report output file
	FILE							*pReportFile;

	// count of items listed in a category
	int								ItemInCategory;

	// count of items sold in a category
	int								ItemSoldInCategory;

	// an item's selling price = current price if not dutch;
	// = current price * quantities sold if dutch
	double							ItemSellingPrice;

	// running total of item selling price
	double							SumItemSellingPrice;

	// indicates first time through catTracks, no category and data
	bool							firstPass;


	// simple and stupid; don't need hash maps since we sort by category
	int								curCategory;
	clsCategory						*pCategory;

	// File shenanigans
	pReportFile	= fopen("Report.txt", "a+");

	if (!pReportFile)
	{
		fprintf(stderr,"%s:%d Unable to open report file. \n",
			  __FILE__, __LINE__);

		// cleanup?
		return;
	}

	// The things we need
	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces = gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpCategories)
		mpCategories	= mpMarketPlace->GetCategories();
	
	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	// initialize category level variables
	ItemInCategory = 0;
	ItemSoldInCategory = 0;
	ItemSellingPrice = 0;
	SumItemSellingPrice = 0;
	curCategory = 0;
	firstPass = true;

	// First, let's get the item ids within the date range we want
	gApp->GetDatabase()->GetItemsByEndDateArc(mpMarketPlace->GetId(),
			&vItems, fromdate, todate);

	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		// Get the item
		pItem	= mpItems->GetItemArcDet((*i));
		if (!pItem)
		{
			fprintf(stderr, "** Error ** Could not get item %d\n",
					(*i));
			continue;
		}

		else if (pItem->GetPrice() < 10000)
		{
			// first time we see this category?
			// close the previous category's data/summary
			// since result is sorted by category
			if (curCategory != pItem->mCategory)
			{
				// report all the data; 
				if (!firstPass)
				{
					// spit out category name
					pCategory = mpCategories->GetCategory(curCategory);
					fprintf(pReportFile,"%s:%s:%s:%s\t", 
						pCategory->GetName3(),
						pCategory->GetName2(), 
						pCategory->GetName1(),
						pCategory->GetName());

					// counts
					fprintf(pReportFile,"%d\t%d\t$%8.2f\n",
						ItemInCategory,
						ItemSoldInCategory,
						SumItemSellingPrice);
				}
				else
				{
					firstPass = false;
					fprintf(pReportFile,"CategoryName\tItemCount\tItemSold\tSumPrice\n");
				}
				
				curCategory = pItem->mCategory;			 
				 // reset variables
		 		ItemInCategory = 0;
				ItemSoldInCategory = 0;
				ItemSellingPrice = 0;
				SumItemSellingPrice = 0;
			} // end of switch category

			// process the item for the category; add 1 to counter, etc.
			ItemInCategory = ItemInCategory + 1;

			// if successful listing, add 1 to ItemSoldInCategory
			if ((pItem->GetPrice() >= pItem->GetReservePrice()) && (pItem->GetBidCount() > 0))
			{
				// successful listing; add counter and get ItemSellingPrice
				 ItemSoldInCategory = ItemSoldInCategory + 1;

				// get qtySold in the item
				if (pItem->mAuctionType == AuctionDutch)
				{
					pItem->GetDutchHighBidders(&vBids, true);
					QtySold = 0;

					// iterate counting the qtySold
					for	(vI = vBids.begin();
						 vI != vBids.end();
						 vI++)
					{
						QtySold = QtySold + (*vI)->mQuantity;
						delete(*vI);
					 };
					
					vBids.erase(vBids.begin(), vBids.end());

					if (QtySold > pItem->GetQuantity())
						QtySold = pItem->GetQuantity();
				}
				else
					QtySold = pItem->GetQuantity();

				ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());

				SumItemSellingPrice = SumItemSellingPrice + ItemSellingPrice;
			}; // successful listing

			// if not successful, do nothing
			delete pItem;
		}
		// else bogus item
	}; // for loop
	fclose(pReportFile);
	vItems.erase(vItems.begin(), vItems.end());
};


static clsSummaryArcReportApp *pTestApp = NULL;

int main()
{
	char fromdate[64];
	char todate[64];

	if (!pTestApp)
	{
		pTestApp	= new clsSummaryArcReportApp(0);
	}

	pTestApp->InitShell();

	strcpy(fromdate, "1997-12-01 00:00:00");
	strcpy(todate,   "1997-12-31 23:59:59");

	pTestApp->Run((char *)&fromdate, (char *)&todate);

	return 0;
}
