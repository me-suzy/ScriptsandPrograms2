/*	$Id: clsOneTimeArchSummaryApp.cpp,v 1.4 1999/04/07 05:42:18 josh Exp $	*/
//
//	File:	clsOneTimeArchSummaryApp.cpp
//
//	Class:	clsOneTimeArchSummaryApp
//
//	Author:	Tini Widjojo (tini@ebay.com)
//
//	Function:
//
//		Generates the total items and total price for a given time period
//      do not count items with GetPrice > 10K and FVF > 300
//      look over archive table only; do NOT read categories table
//  
//
// Modifications:
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsOneTimeArchSummaryApp.h"
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


clsOneTimeArchSummaryApp::clsOneTimeArchSummaryApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpCategories	= (clsCategories *)0;
	mpItems			= (clsItems *)0;
	return;
}


clsOneTimeArchSummaryApp::~clsOneTimeArchSummaryApp()
{
	return;
};


void clsOneTimeArchSummaryApp::Run(char *fromdate, char *todate)
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
	FILE							*pReportWako;


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

	// items' fees totals in category
	double							AllItemFees;
	double							AllItemSoldFees;

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

	// indicates first time through catTracks, no category and data
	bool							firstPass;

	// File shenanigans
	pReportFile	= fopen("archreport.txt", "w+");
	// File shenanigans
	pReportWako	= fopen("archwako.txt", "w+");


	if (!pReportFile)
	{
		fprintf(stderr,"%s:%d Unable to open report file. \n",
			  __FILE__, __LINE__);

		// cleanup?
		return;
	}
	
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
	AllItemSoldFees = 0;
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
	

	// reset for each item
	ItemSellingPrice = 0;
	ItemListPrice = 0;

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
		else 
		{ 
			// valid item
			// let's calculate FVFs. Only applicable if getPrice is <10K;
			mpMarketPlace->SetCurrentCurrency(pItem->GetCurrencyId());

			if (pItem->GetPrice() < 10000)
			{
				ItemFVFee = 0;

				if (pItem->GetReservePrice() > 0)
				{ 
					if (pItem->GetPrice() >= pItem->GetReservePrice())
					{	
						ItemFVFee = 
							RoundToCents(pItem->GetListingFee());
					}
				}//end of reserved price auction
				else
				{ 
					// non reserve auction
					if (pItem->GetBidCount() > 0)
					{ 
						if (pItem->mAuctionType == AuctionDutch)
						{
							//successful dutch auction
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

							// price
							ItemSellingPrice = 
								RoundToCents(QtySold * pItem->GetPrice());

							// fees
							// calculate FVF
							ItemFVFee = 
								RoundToCents(pItem->GetListingFee(ItemSellingPrice));
							
						}// end of dutch
						else
						{
							// successful chinese auction
							QtySold = pItem->GetQuantity();

							// price
							ItemSellingPrice = 
								RoundToCents(QtySold * pItem->GetPrice());

							// fees
							// calculate FVF
							ItemFVFee = 
								RoundToCents(pItem->GetListingFee(ItemSellingPrice));

						}//end of non dutch - else
					}//end of if any bids, no reserve price
				}//end of not reserved price - else
			}//end of valid item
	
			//at this point ItemFVFee is calculated or is 0 if not applicable

			if (pItem->GetPrice() < 10000 && ItemFVFee <= 300)
			{
				// report all the data for previous category; 
				if (firstPass)
				{	
					firstPass = false;
					fprintf(pReportFile,"Category\tAllCount\tRCount\tRSoldCount\tRNotCount\tNRCSoldCount\tNRCNotCount\tDSoldCount\tDNotCount\tAllSoldCount\t");
					fprintf(pReportFile, "SumSoldPrice\tRSoldPrice\tNRCSoldPrice\tDSoldPrice\tSumFees\tRSoldFees\tRNotSoldFees\tNRCSoldFees\tNRCNotSoldFees\tDSoldFees\tDNotSoldFees\tSumSoldFees\n");						
					fprintf(pReportFile,"Totals\t");

					fprintf(pReportWako,"Item Number\tPrice\tFinal Value Fee\n");
				}
					
				// process the item for the category; reset variables used
				ItemListPrice = 0;
				//ItemFVFee = 0; Done before filter condition

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
					ItemSuperFeaturedFee = pItem->GetFeaturedFee();
				else
					ItemSuperFeaturedFee = 0;

				if (pItem->GetFeatured())
					ItemFeaturedFee = pItem->GetCategoryFeaturedFee();
				else
					ItemFeaturedFee = 0;
				
				ItemBaseFee = ItemListFee + ItemBoldFee + ItemFeaturedFee + ItemSuperFeaturedFee;

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
						// calculate FVF-done in a filter
						//ItemFVFee = RoundToCents(pItem->GetListingFee());
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
							/* QtySold, ItemSellingPrice -done in a filter
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

							// price
							ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());*/
							SumDItemSoldPrice = SumDItemSoldPrice + ItemSellingPrice;

							// fees
							// calculate FVF-done in a filter
							//ItemFVFee = RoundToCents(pItem->GetListingFee(ItemSellingPrice));
							DItemSoldFees = DItemSoldFees + ItemBaseFee + ItemFVFee;
							
						}
						else
						{
							// successful chinese auction
							NRItemSold = NRItemSold + 1;
							//done in filter
							//QtySold = pItem->GetQuantity();

							// price
							//done in filter
							//ItemSellingPrice = RoundToCents(QtySold * pItem->GetPrice());
							SumNRItemSoldPrice = SumNRItemSoldPrice + ItemSellingPrice;

							// fees
							// calculate FVF-done in filter
							//ItemFVFee = RoundToCents(pItem->GetListingFee(ItemSellingPrice));
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
					
				AllItemSoldFees = AllItemSoldFees + ItemBaseFee + ItemFVFee;
				AllItemFees = AllItemFees + ItemBaseFee + ItemFVFee;

				delete pItem;
			} // end of all items < 10K
			else
			{
				//this item either Priced over 10K or FVF is > than 300;
				//or both
				if (pItem->GetPrice() >=10000)
					fprintf(pReportWako,"%d\t%8.2f\t\n",pItem->GetId(),pItem->GetPrice());
				else
					fprintf(pReportWako,"%d\t%8.2f\t%8.2f\n",pItem->GetId(),pItem->GetPrice(),ItemFVFee);


			}

		} // else bogus item
			
	 } // for loop


fprintf(pReportFile,"%d\t%d\t%d\t%d\t%d\t%d\t%d\t%d\t%d \t\t$%8.2f\t\t$%8.2f \t",
							AllItem,
							RItem,
							RItemSold,
							RItemNot,
							NRItemSold,
							NRItemNot,
							DItemSold,
							DItemNot,
							AllItemSold,
							SumAllItemSoldPrice,
							SumRItemSoldPrice);

fprintf(pReportFile,"$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f \t$%8.2f\n",
							SumNRItemSoldPrice,
							SumDItemSoldPrice,
							AllItemFees,
							RItemSoldFees,
							RItemNotFees,
							NRItemSoldFees,
							NRItemNotFees,
							DItemSoldFees,
							DItemNotFees,
							AllItemSoldFees);

	fclose(pReportFile);
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

static clsOneTimeArchSummaryApp *pTestApp = NULL;

void InputError()
{
	// wrong syntax
	printf("Input syntax error!\n");
	printf("Usage:\n\tOneTimeArchSummary [-s mm/dd/yy] [-e mm/dd/yy]\n");
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
		pTestApp	= new clsOneTimeArchSummaryApp(0);
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

