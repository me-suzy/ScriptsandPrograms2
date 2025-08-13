/*	$Id: clsSummaryReportApp.cpp,v 1.6.54.2 1999/05/28 18:53:50 inna Exp $	*/
//
//	File:	clsSummaryReportApp.cpp
//
//	Class:	clsSummaryReportApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Generates the total items and total price for a given time period
//
// Modifications:
//				02/05/98	inna	-changed output of the main report to 
//									go to ebay_summary_report table instead
//									change wacko report to relect date
//				04/29/99	inna   -skip items that have currency foreight currency
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsSummaryReportApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsBid.h"
#include "clsUtilities.h"

#include "vector.h"
#include "hash_map.h"
#include "iterator.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>


clsSummaryReportApp::clsSummaryReportApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpCategories	= (clsCategories *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsSummaryReportApp::~clsSummaryReportApp()
{
	return;
};


void clsSummaryReportApp::Run(char *fromdate, char *todate)
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
	//FILE							*pReportFile;
	FILE							*pReportWako;
	char							fname[FILENAME_MAX];

	char							*pCatName;


	// count of items listed in a category and those that sold
	int								AllItem;
	int								AllItemSold;

	// count of reserve items in a category, sold, not sold
	int								RItem;
	int								RItemSold;
	int								RItemNot;

	// count of non-reserve chinese auctions in a category, sold, not sold 
//	int								NRItem;
	int								NRItemSold;
	int								NRItemNot;

	// count of dutch auctions in a category, sold, not sold
	int								DItemSold;
	int								DItemNot;

	// an item's selling price = current price if not dutch;
	// = current price * quantities sold if dutch; added to SumPrices below
	double							ItemSellingPrice;
	// price used to figure out the listing fee for an item
	double							ItemListPrice;
	double							ItemListFee;
	double							ItemBoldFee;
	double							ItemFeaturedFee;
	double							ItemSuperFeaturedFee;
	double							ItemFVFee;
	double							ItemBaseFee;
	double							ItemGalleryFee;
	double							ItemFeatureGalleryFee;
	double							ItemGiftIconFee;

	// items' fees totals in category
	double							AllItemFees;
	//inna double							AllItemSoldFees;

	double							RItemSoldFees;
	double							RItemNotFees;

	double							NRItemSoldFees;
	double							NRItemNotFees;

	double							DItemSoldFees;
	double							DItemNotFees;

	// running total of item selling price, reserve, non reserve, and dutch
	double							SumAllItemSoldPrice;
	double							SumRItemSoldPrice;
	double							SumNRItemSoldPrice;
	double							SumDItemSoldPrice;

	//running total of fees per type
	double							SumBoldFee;
	double							SumFeaturedFee;
	double							SumSuperFeaturedFee;
	double							SumListFee;
	double							SumFVFee;
	double							SumGalleryFee;
	double							SumFeatureGalleryFee;
	double							SumGiftIconFee;

	// indicates first time through catTracks, no category and data
	bool							firstPass;

	// simple and stupid; don't need hash maps since we sort by category
	int								curCategory;
	clsCategory						*pCategory;
	int								j;//needed for name format

	// File shenanigans
	//pReportFile	= fopen("Report.txt", "w+");
	sprintf(fname,"Wacko%c%c%c%c%c%c",fromdate[5],fromdate[6],fromdate[8],fromdate[9],fromdate[2],fromdate[3]);
	
	pReportWako	= fopen(fname, "w+");

	/*if (!pReportFile)
	{
		fprintf(stderr,"%s:%d Unable to open report file. \n",
			  __FILE__, __LINE__);

		// cleanup?
		return;
	}*/
	
	if (!pReportWako)
	{
		fprintf(stderr,"%s:%d Unable to open wako report file. \n",
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
	AllItem = 0;
	AllItemSold = 0;

	RItem = 0;
	RItemSold = 0;
	RItemNot = 0;

	NRItemSold = 0;
	NRItemNot = 0;

	DItemSold = 0;
	DItemNot = 0;

	AllItemFees = 0;
	//inna AllItemSoldFees = 0;
	RItemSoldFees = 0;
	RItemNotFees = 0;
	NRItemSoldFees = 0;
	NRItemNotFees = 0;
	DItemSoldFees = 0;
	DItemNotFees = 0;

	SumAllItemSoldPrice = 0;
	SumRItemSoldPrice = 0;
	SumNRItemSoldPrice = 0;
	SumDItemSoldPrice = 0;
	
	curCategory = 0;

	SumBoldFee = 0;
	SumFeaturedFee = 0;
	SumSuperFeaturedFee = 0;
	SumListFee = 0;
	SumFVFee = 0;
	SumGalleryFee = 0;
	SumFeatureGalleryFee = 0;
	SumGiftIconFee = 0;

	// reset for each item
	ItemSellingPrice = 0;
	ItemListPrice = 0;

	firstPass = true;

	// First, let's get the item ids within the date range we want
	gApp->GetDatabase()->GetItemsByEndDateSortedByCat(mpMarketPlace->GetId(),
			&vItems, fromdate, todate);

	// Now, we loop through them
	for (i = vItems.begin();
		 i != vItems.end();
		 i++)
	{
		// Get the item
		pItem	= mpItems->GetItem((*i));
		if (!pItem)
		{
			fprintf(stderr, "** Error ** Could not get item %d\n",
					(*i));
			continue;
		}
		else 
		{ 
			//mpMarketPlace->SetCurrentCurrency(pItem->GetCurrencyId());
			//let's skip all foreign currency items this month!
			if ((pItem->GetCurrencyId()== Currency_USD || pItem->GetCurrencyId()==Currency_None)
				&&(!pItem->IsItemWacko()) && (!pItem->ChargeNoFinalValueFee()) 
				&& pItem->GetPrice()< 10000)
			{
			//another WACKO hardecoded price filter check
			if (pItem->mAuctionType == AuctionDutch)
			{	
				pItem->GetDutchHighBidders(&vBids);
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

				// price
				ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());
			
			}//got stuff needed for Dutch

			//!!here is a check itself:
			if ((pItem->mAuctionType == AuctionDutch && ItemSellingPrice < 25000) 
				|| (pItem->mAuctionType != AuctionDutch)) 
			{
				// first time we see this category?
				// close the previous category's data/summary
				// since result is sorted by category
				if (curCategory != pItem->mCategory)
				{
					// report all the data for previous category; 
					if (!firstPass)
					{
						// spit out category name
						pCategory = mpCategories->GetCategory(curCategory);
						pCatName = new char[86];
						j=0;
						// spit out category name
						pCategory = mpCategories->GetCategory(curCategory);
						if (strlen(pCategory->GetName3())>0)
							j = sprintf(pCatName,"%s:",pCategory->GetName3());
						if (strlen(pCategory->GetName2())>0)
							j += sprintf(pCatName+j,"%s:",pCategory->GetName2());
						if (strlen(pCategory->GetName1())>0)
							j += sprintf(pCatName+j,"%s:",pCategory->GetName1());
					
							j += sprintf(pCatName+j,"%s\t",pCategory->GetName());	

						// add to ebay_summary_report table
							gApp->GetDatabase()->AddRawSummaryReportData(pCatName,
							AllItem,
							RItem,
							RItemSold,
							RItemNot,
							NRItemSold,
							NRItemNot,
							DItemSold,
							DItemNot,
							AllItemSold,
							(float)SumAllItemSoldPrice,
							(float)SumRItemSoldPrice,
							(float)SumNRItemSoldPrice,
							(float)SumDItemSoldPrice,
							(float)SumBoldFee,
							(float)SumFeaturedFee,
							(float)SumSuperFeaturedFee,
							(float)SumListFee,
							(float)SumFVFee,
							(float)SumGalleryFee,
							(float)SumFeatureGalleryFee,
							(float)SumGiftIconFee,
							(float)RItemSoldFees,
							(float)RItemNotFees,
							(float)NRItemSoldFees,
							(float)NRItemNotFees,
							(float)DItemSoldFees,
							(float)DItemNotFees,
							(float)AllItemFees,
							fromdate,
							pCategory->GetId());
						
						// cleanup
						delete pCategory;
						delete pCatName;
					}
					else
					{
						firstPass = false;
					/*	fprintf(pReportFile,
						"Category_Name\tAllCount\tRCount\tRSoldCount\tRNotCount\tNRCSoldCount\tNRCNotCount\tDSoldCount\tDNotCount\tAllSoldCount\t");
						fprintf(pReportFile,
						"SumSoldPrice\tRSoldPrice\tNRCSoldPrice\tDSoldPrice\tSumBoldFees\tSumFeatFees\tSumSuperFeatFees\tSumListFees\tSumFVFees\t");
						fprintf(pReportFile,
						"RSoldFees\tRNotSoldFees\tNRCSoldFees\tNRCNotSoldFees\tDSoldFees\tDNotSoldFees\tSumFees\n");
					*/				
						fprintf(pReportWako,"Item\tPrice\tQuantity\tSeller\n");
					}
					
					curCategory = pItem->mCategory;	
					
					// reset variables for category
					AllItem = 0;
					RItem = 0;
					RItemSold = 0;
					RItemNot = 0;
					NRItemSold = 0;
					NRItemNot = 0;
					DItemSold = 0;
					DItemNot = 0;
					AllItemSold = 0;
					SumAllItemSoldPrice = 0;
					SumRItemSoldPrice = 0;
					SumNRItemSoldPrice = 0;
					SumDItemSoldPrice = 0;
					AllItemFees = 0;
					RItemSoldFees = 0;
					RItemNotFees = 0;
					NRItemSoldFees = 0;
					NRItemNotFees = 0;
					DItemSoldFees = 0;
					DItemNotFees = 0;
					SumBoldFee = 0;
					SumFeaturedFee = 0;
					SumSuperFeaturedFee = 0;
					SumListFee = 0;
					SumFVFee = 0;
					SumGalleryFee = 0;
					SumFeatureGalleryFee = 0;
					SumGiftIconFee = 0;
					//inna AllItemSoldFees = 0;

				} // end of switch category and reporting results on the category

				// process the item for the category; reset variables used
				ItemListPrice = 0;
				ItemFVFee = 0;

				// for each item, do item counts, calculate fees, 
				// calculate value of item
				// add to various groups
				AllItem = AllItem + 1;

				// figure out price for listing price to find listing fee
				if (pItem->GetQuantity() > 1)
				{
					ItemListPrice = pItem->GetQuantity() * pItem->GetStartPrice();
				}
				else
				{
					if (pItem->GetReservePrice() > 0)
					{
						if (pItem->GetReservePrice() >= pItem->GetStartPrice())
							ItemListPrice = pItem->GetReservePrice();
						else
							ItemListPrice = pItem->GetStartPrice();
					}
					else
					{
						ItemListPrice = pItem->GetStartPrice();
					}
				}

				// cheat and get the fees up front except for FVF
				ItemListFee = pItem->GetInsertionFee(ItemListPrice);

				if (pItem->GetBoldTitle())
					ItemBoldFee = pItem->GetBoldFee(ItemListPrice);
				else
					ItemBoldFee = 0;

				if (pItem->GetSuperFeatured())
					ItemSuperFeaturedFee = pItem->GetFeaturedFee(pItem->GetStartTime());
				else
					ItemSuperFeaturedFee = 0;

				if (pItem->GetFeatured())
					ItemFeaturedFee = pItem->GetCategoryFeaturedFee(pItem->GetStartTime());
				else
					ItemFeaturedFee = 0;

				//new fees: gallery, feature gallery and gift icon
				if (pItem->IsGallery() 
						&& (clsUtilities::CompareTimeToGivenDate(pItem->GetStartTime(), 6, 4, 99, 0, 0, 0) >= 0)
					&& (clsUtilities::CompareTimeToGivenDate(pItem->GetStartTime(), 5, 20, 99, 0, 0, 0) < 0))
					ItemGalleryFee = pItem->GetGalleryFee();
				else
					ItemGalleryFee = 0;

				if (pItem->IsFeaturedGallery())
					ItemFeatureGalleryFee = pItem->GetFeaturedGalleryFee();
				else
					ItemFeatureGalleryFee = 0;

				// old if (pItem->GetGiftIconType() == 1 )
				if (pItem->GetGiftIconType() >0 && pItem->GetGiftIconType() != 2 /*Rosie*/)
					ItemGiftIconFee = pItem->GetGiftIconFee();
				else
					ItemGiftIconFee = 0;
				
				ItemBaseFee = ItemListFee + ItemBoldFee + ItemFeaturedFee + 
					         ItemSuperFeaturedFee + ItemGalleryFee +
					         ItemFeatureGalleryFee + ItemGiftIconFee;

				// start calculating the counts and what's sold and not sold per auction type
				if (pItem->GetReservePrice() > 0)
				{ 
					// reserve priced item
					RItem = RItem + 1;

					if (pItem->GetPrice() >= pItem->GetReservePrice())
					{	
						// successful item counts
						RItemSold = RItemSold + 1;
						AllItemSold = AllItemSold + 1;
						
						// price
						SumRItemSoldPrice = SumRItemSoldPrice + pItem->GetPrice();
						SumAllItemSoldPrice = SumAllItemSoldPrice + pItem->GetPrice();
						
						// fees
						if (!pItem->CheckForRealEstateListing())
							ItemFVFee = RoundToCents(pItem->GetListingFee());
						else 
							ItemFVFee = 0;

						RItemSoldFees = RItemSoldFees + ItemBaseFee + ItemFVFee;

					}
					else
					{	// not successful item
						RItemNot = RItemNot + 1;
						RItemNotFees = RItemNotFees + ItemBaseFee;

					}
				}
				else
				{ 
					// non reserve auction
					if (pItem->GetBidCount() > 0)
					{ 
						// successful sold item
						AllItemSold = AllItemSold + 1;

						// get qtySold in the item for sum and fees
						if (pItem->mAuctionType == AuctionDutch)
						{
							// set counts
							DItemSold = DItemSold + 1;
							
							//pItem->GetDutchHighBidders(&vBids);
							//QtySold = 0;

							// iterate counting the qtySold
							//for	(vI = vBids.begin();
							//	 vI != vBids.end();
							//	 vI++)
							//{
							//	QtySold = QtySold + (*vI)->mQuantity;
							//	delete(*vI);
							// };
							
							//vBids.erase(vBids.begin(), vBids.end());

							//if (QtySold > pItem->GetQuantity())
							//	QtySold = pItem->GetQuantity();

							// price
							//ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());
							SumDItemSoldPrice = SumDItemSoldPrice + ItemSellingPrice;

							// fees
							if (!pItem->CheckForRealEstateListing())
								ItemFVFee = RoundToCents(pItem->GetListingFee(ItemSellingPrice));
							else 
								ItemFVFee = 0;
						
							DItemSoldFees = DItemSoldFees + ItemBaseFee + ItemFVFee;
							
						}
						else
						{
							// successful chinese auction
							NRItemSold = NRItemSold + 1;
							QtySold = pItem->GetQuantity();

							// price
							ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());
							SumNRItemSoldPrice = SumNRItemSoldPrice + ItemSellingPrice;

							// fees
							if (!pItem->CheckForRealEstateListing())
								ItemFVFee = RoundToCents(pItem->GetListingFee(ItemSellingPrice));
							else
								ItemFVFee = 0;
							
							NRItemSoldFees = NRItemSoldFees + ItemBaseFee + ItemFVFee;

						};

						SumAllItemSoldPrice = SumAllItemSoldPrice + ItemSellingPrice;

					} // end successful non reserve auction
					else
					{ // not sold items
						if (pItem->mAuctionType == AuctionDutch)
						{   // unsuccessful dutch
							// count
							DItemNot = DItemNot + 1;
							// fees
							DItemNotFees = DItemNotFees + ItemBaseFee;
						}
						else
						{   // unsuccessful chinese
							NRItemNot = NRItemNot + 1;

							// fees
							NRItemNotFees = NRItemNotFees + ItemBaseFee;
						}
					} // end not sold non reserve auction

				}; // end of non reserve auctions
					
				//inna no more sold only 
				//AllItemSoldFees = AllItemSoldFees + ItemBaseFee + ItemFVFee;
				AllItemFees = AllItemFees + ItemBaseFee + ItemFVFee;
				//inna  accumulate different fee types for all auctions
				SumBoldFee = SumBoldFee + ItemBoldFee;
				SumFeaturedFee = SumFeaturedFee + ItemFeaturedFee;
				SumSuperFeaturedFee = SumSuperFeaturedFee + ItemSuperFeaturedFee;
				SumListFee = SumListFee + ItemListFee;
				SumFVFee = SumFVFee + ItemFVFee;
				SumFeatureGalleryFee = SumFeatureGalleryFee + ItemFeatureGalleryFee;
				SumGalleryFee = SumGalleryFee + ItemGalleryFee;
				SumGiftIconFee = SumGiftIconFee + ItemGiftIconFee;

				delete pItem;
			} //end of items not passed hard coded price chech for dutch
			} // end of all items < 10K
			else
			{
				//DID WE SKIP IT A FOREGN CURRENCY ITEM?
			if (pItem->GetCurrencyId()!=Currency_USD && pItem->GetCurrencyId()!=Currency_None)
			{
				fprintf(pReportWako,"%d\t%8.2f\t%d\t%d\tForeign Currency\n",
					pItem->GetId(),pItem->GetPrice(), pItem->GetQuantity(), pItem->GetSeller());		
			}
			else
			{
				//this item either Priced over 10K or FVF is > than 300;
				//or both
				if (!pItem->ChargeNoFinalValueFee())
				{
					//need to get fvf for dutch auction!
					if (pItem->mAuctionType == AuctionDutch)
					{
						//pItem->GetDutchHighBidders(&vBids);
						//QtySold = 0;

						// iterate counting the qtySold
						//for	(vI = vBids.begin();
						//	 vI != vBids.end();
						//	 vI++)
						//{
						//	QtySold = QtySold + (*vI)->mQuantity;
						//	delete(*vI);
						// };
							
					//	vBids.erase(vBids.begin(), vBids.end());

					//	if (QtySold > pItem->GetQuantity())
					//		QtySold = pItem->GetQuantity();

						// price
						//ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());	

						// fees
						if (!pItem->CheckForRealEstateListing())
							ItemFVFee = 
								RoundToCents(pItem->GetListingFee(ItemSellingPrice));
						else
							ItemFVFee = 0;

						fprintf(pReportWako,"%d\t%8.2f\t%d\t%d\n",
							pItem->GetId(),pItem->GetPrice(), QtySold, pItem->GetSeller());
					}
					else
						fprintf(pReportWako,"%d\t%8.2f\t%d\t%d\n",
							pItem->GetId(),pItem->GetPrice(), pItem->GetQuantity(), pItem->GetSeller());			
				}
			}//end of else NOT Foreign item

			}

		} // else bogus item
			
	  } // for loop

		  // spit out last category name & data
		  if (curCategory != 0)
		  { // emit last category data
						pCategory = mpCategories->GetCategory(curCategory);
						j=0;
						pCatName = new char[86];
						// spit out category name
						pCategory = mpCategories->GetCategory(curCategory);
						if (strlen(pCategory->GetName3())>0)
							j = sprintf(pCatName,"%s:",pCategory->GetName3());
						if (strlen(pCategory->GetName2())>0)
							j += sprintf(pCatName+j,"%s:",pCategory->GetName2());
						if (strlen(pCategory->GetName1())>0)
							j += sprintf(pCatName+j,"%s:",pCategory->GetName1());
					
							j += sprintf(pCatName+j,"%s\t",pCategory->GetName());

							// add to ebay_summary_report table
							gApp->GetDatabase()->AddRawSummaryReportData(pCatName,
							AllItem,
							RItem,
							RItemSold,
							RItemNot,
							NRItemSold,
							NRItemNot,
							DItemSold,
							DItemNot,
							AllItemSold,
							(float)SumAllItemSoldPrice,
							(float)SumRItemSoldPrice,
							(float)SumNRItemSoldPrice,
							(float)SumDItemSoldPrice,
							(float)SumBoldFee,
							(float)SumFeaturedFee,
							(float)SumSuperFeaturedFee,
							(float)SumListFee,
							(float)SumFVFee,
							(float)SumGalleryFee,
							(float)SumFeatureGalleryFee,
							(float)SumGiftIconFee,
							(float)RItemSoldFees,
							(float)RItemNotFees,
							(float)NRItemSoldFees,
							(float)NRItemNotFees,
							(float)DItemSoldFees,
							(float)DItemNotFees,
							(float)AllItemFees,
							fromdate,
							pCategory->GetId());
				delete pCatName;
						
		  };

//	fclose(pReportFile);
	fclose(pReportWako);
	vItems.erase(vItems.begin(), vItems.end());
};

// expecting pTime in format: dd:mm:yy
//
bool ConvertToTime_t(char* pTime, time_t* pTimeTValue)
{
	int		Day;
	int		Month;
	int		Year;
	struct tm*	pTimeTm;

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

	// put the day, month, and year together
	*pTimeTValue = time(0);
	pTimeTm = localtime(pTimeTValue);
	pTimeTm->tm_mday = Day;
	pTimeTm->tm_mon = Month-1;
	pTimeTm->tm_year = Year;
	*pTimeTValue = mktime(pTimeTm);

	return true;
}

static clsSummaryReportApp *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tSummaryReport [-s mm/dd/yy] [-e mm/dd/yy]\n");
}

int main(int argc, char* argv[])
{
	int		Index = 1;
	char*	pStartDate = NULL;
	char*	pEndDate = NULL;
	time_t	StartTime = 0;
	time_t	EndTime = 0;
	char fromdate[64];
	char todate[64];
	struct tm*	psTime;
	struct tm*	peTime;

	// we need this for Oracle to be able to connect
	#ifdef _MSC_VER
		g_tlsindex = 0;
	#endif

	while (--argc)
	{
		switch (argv[Index][1])
		{
		// Get starting date
		case 's':
			pStartDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pStartDate, &StartTime))
			{
				InputError();
				exit(0);
			}
			break;

		// Get ending date
		case 'e':
			pEndDate = argv[++Index];
			Index++;
			argc--;

			if (!ConvertToTime_t(pEndDate, &EndTime))
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

	if (StartTime == 0 && EndTime != 0)
	{
		printf("Start Date is required when End Date is provided.\n");
		return 0;
	}

	if (!pTestApp)
	{
		pTestApp	= new clsSummaryReportApp(0);
	}

	psTime = localtime(&StartTime);

	// make fromdate and todate = StartTime and EndTime
	sprintf(fromdate, "19%2.2d-%2.2d-%2.2d 00:00:00", 
			psTime->tm_year, 
			psTime->tm_mon+1,
			psTime->tm_mday);

	peTime = localtime(&EndTime);
	sprintf(todate, "19%2.2d-%2.2d-%2.2d 00:00:00", 
			peTime->tm_year, 
			peTime->tm_mon+1,
			peTime->tm_mday);

	pTestApp->InitShell();
	pTestApp->Run((char *)&fromdate, (char *)&todate);

//	pApp->Run(StartTime, EndTime, StatisticsId, XactionId);

//	delete pTestApp;

	return 0;
}

