/*	$Id: clseBayAppAdminViewDailyStats.cpp,v 1.6.390.1 1999/08/05 20:42:08 nsacco Exp $	*/
//
//	File:		clseBayAppAdminViewDailyStats.cpp
//
//	Class:		clseBayApp
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/18/97 wen	- Created
//

#include "ebihdr.h"
#include "clsStatistics.h"

static char* WeekDay[] = {"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"}; 

void clseBayApp::AdminViewDailyStats(CEBayISAPIExtension *pServer,
							 int	StartMon,
							 int	StartDay,
							 int	StartYear,
							 int	EndMon,
							 int	EndDay,
							 int	EndYear,
							 char *pEmail,
							 char *pPass)
{
	time_t	StartTime;
	time_t	EndTime;

	SetUp();

	// title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	"Daily Statistics"
			  <<	"</title>"
					"</head>\n"
			  <<	"<body><h2>eBay Daily Statistics</h2>\n";

	// Check whether the dates are valid
	if (ConvertToTime_t(StartMon, StartDay, StartYear, &StartTime) == false)
	{
		*mpStream << "Invalid starting date"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (ConvertToTime_t(EndMon, EndDay, EndYear, &EndTime) == false)
	{
		*mpStream << "Invalid ending date"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (StartTime > EndTime)
	{
		*mpStream << "Invalid starting and ending date range"
				  << "<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
	}

	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword(0)) == 0 || 
		strcmp(pPass, mpMarketPlace->GetSpecialPassword(1)) == 0)
	{
		mpUser = NULL;
	}
	else
	{
		mpUser	= mpUsers->GetAndCheckUserAndPassword(pEmail, pPass, mpStream);
	}

	// If we didn't get the user, we're done
	if (!mpUser)
	{   *mpStream <<    "Not a valid user or password.";
		*mpStream <<	"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the user can administer categories
	if (!mpUser->HasAdmin(Statistics))
	{
		*mpStream <<	"<p>"
						"You do not have Statistics Administration privileges."
						"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Show statistics
	GetAndShowDailyStatistics(StartTime, EndTime);

	// Notes:
	*mpStream <<	"<UL><b>Please note:</b><br>\n"
					"<LI>These statistics do not include any item whose "
					"starting price or ending price is over $10,000.\n"
					"<LI>The U.S. dollar amount for dutch auctions is the "
					"sum of starting prices in the New Auction table, "
					"or the sum of the ending prices in the Auction Completed "
					"table."
					" They do not take into account of the quantity.\n"
					"<LI>The date in the table New Auction is the item's "
					"auction starting date, while the one in the table "
					"Auction Completed is item's auction ending date.\n"
					"<LI>A bid count in the New Auctions table is the total "
					"number of bids on all items on a specified date; "
					"A bid count in the Auctions Completed table is the "
					"total number of bids on all items ending on a specified "
					"date."
					"<LI>The data were not regularly collected "
					"until 10/17/97.\n"
					"</UL>\n";

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;

}

// expecting pTime in format: mm/dd/yy
//
bool clseBayApp::ConvertToTime_t(int Month, int Day, int Year, time_t* pTimeTValue)
{
	struct tm*	pTimeTm;

	Month--;
	Year -= 1900;

	// put the day, month, and year together
	*pTimeTValue = time(0);
	pTimeTm = localtime(pTimeTValue);
	pTimeTm->tm_sec = 0;
	pTimeTm->tm_min = 0;
	pTimeTm->tm_hour = 0;
	pTimeTm->tm_mday = Day;
	pTimeTm->tm_mon = Month;
	pTimeTm->tm_year = Year;
	if ( (*pTimeTValue = mktime(pTimeTm)) == (time_t)-1)
	{
		return false;
	}

	// check whether the date is valid
	pTimeTm = localtime(pTimeTValue);
	if (pTimeTm->tm_mday != Day   || 
		pTimeTm->tm_mon  != Month || 
		pTimeTm->tm_year != Year)
	{
		return false;
	}

	return true;
}


// Get and show the statistics
//
void clseBayApp::GetAndShowDailyStatistics(time_t StartTime, time_t EndTime)
{
	DailyStatsVector*	pvDailyStats;
	StatsTransactionVector				TransVector;
	StatsTransactionVector::iterator	iTransaction;
	DailyStatsVector::iterator	iStats;

	int		Index;

	// transaction types
	mpStatistics->GetStatisticsDesc(DIALY_STATISTICS, &TransVector);

	// allocate vector to hold the daily stats
	pvDailyStats = new DailyStatsVector [TransVector.size()];

	// get daily stats for each transaction type
	Index = 0;
	for (iTransaction = TransVector.begin(); 
		 iTransaction < TransVector.end();
		 iTransaction++)
	{
		mpStatistics->GetDailyStatistics(StartTime, 
										EndTime, 
										(*iTransaction)->GetId(), 
										&(pvDailyStats[Index]));

		if (pvDailyStats[Index].size())
		{
			sort(pvDailyStats[Index].begin(), pvDailyStats[Index].end(), clseBayApp::SortByTime);
		}
		Index++;
	}

	// show statistics on the new auctions
	ShowNewDailyStatistics( 
						&(pvDailyStats[0]), 
						&(pvDailyStats[1]), 
						&(pvDailyStats[3]));

	// show statistics on auctions completed
	ShowCompletedDailyStatistics(&(pvDailyStats[2]), &(pvDailyStats[4]));

	// clean up
	while (--Index >= 0)
	{
		for (iStats  = pvDailyStats[Index].begin(); 
			 iStats != pvDailyStats[Index].end();
			 iStats++)
		{
			delete *iStats;
		}
	}

	delete [] pvDailyStats;
	
	return;
}

//
// Show stats
//
void clseBayApp::ShowNewDailyStatistics(
								void* pBidStats,
								void* pRegularStats, 
								void* pDutchStats)
{
	DailyStatsVector::iterator	iBidStats;
	DailyStatsVector::iterator	iRegularStats;
	DailyStatsVector::iterator	iDutchStats;
	bool	BidEnd = false;
	bool	RegularEnd = false;
	bool	DutchEnd = false;
	bool	RegularAdvanced;
	bool	DutchAdvanced;
	int		Index;

	int		AuctionItems;
	int		AuctionBids;
	int		SumBids = 0;
	double	AuctionDollar;
	int		RegularSumItems = 0;
	double	RegularSumDollar = 0;
	int		DutchSumItems = 0;
	double	DutchSumDollar = 0;


	time_t	TheDate;
	struct tm*	DateTm;
	char	cDate[20];

	bool	ColorSwitch = false;
	char*	pColor;

	char*	pWhiteColor = "#FFFFFF";
	char*	pYellowColor = "#FFFFCC";

	DailyStatsVector* pvBidStats     = (DailyStatsVector*) pBidStats;
	DailyStatsVector* pvRegularStats = (DailyStatsVector*) pRegularStats;
	DailyStatsVector* pvDutchStats   = (DailyStatsVector*) pDutchStats;

	// Show table header and caption
	*mpStream <<	"<table border=1 cellpadding=1 "
					"width=\"100%\" cellspacing=0>\n"
					"<caption><font size=\"+1\"><big><strong>";

	*mpStream <<	"New Auctions";

	// Print the table header
	*mpStream <<	"</strong></big></font></caption>\n"
					"<tr bgcolor=\"#DDDDAA\"><th width=\"20%\" rowspan=2 valign=bottom>"
			  <<	"Date</th>\n"
					"<th width=\"10%\" colspan=2>"
			  <<	"Regular</th>\n"
					"<th width=\"10%\" colspan=2>"
			  <<	"Dutch</th>\n"
					"<th width=\"10%\" colspan=3>"
			  <<	"Total</th></tr>\n"
					"<tr bgcolor=\"#DDDDAA\"><th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th>\n"
					"<th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th>\n"
					"<th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Bids</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th></tr>\n";

	Index = 0;
	iBidStats	  = pvBidStats->begin();
	iRegularStats = pvRegularStats->begin();
	iDutchStats   = pvDutchStats->begin();

	BidEnd	   = (iBidStats == pvBidStats->end());
	RegularEnd = (iRegularStats == pvRegularStats->end());
	DutchEnd   = (iDutchStats   == pvDutchStats->end());

	while (!RegularEnd || !DutchEnd)
	{
		// init
		AuctionItems  = 0;
		AuctionBids   = 0;
		AuctionDollar = 0;
		RegularAdvanced = false;
		DutchAdvanced = false;

		if (ColorSwitch)
		{
			pColor		= pYellowColor;
			ColorSwitch	= false;
		}
		else
		{
			pColor		= pWhiteColor;
			ColorSwitch	= true;
		}

		//
		// determind what to print for Regular
		//
		if (!RegularEnd && 
			(DutchEnd || (*iRegularStats)->GetDate() <= (*iDutchStats)->GetDate()))
		{
			// print te time and Regular auctin stats
			TheDate = (*iRegularStats)->GetDate();
			DateTm = localtime(&TheDate);
			sprintf(cDate, "%02d/%02d/%02d %s", 
				DateTm->tm_mon+1, 
				DateTm->tm_mday, 
				DateTm->tm_year,
				WeekDay[DateTm->tm_wday]);

			*mpStream <<	"<tr bgcolor=\""
					  <<	pColor
					  <<	"\"><td width=\"10%\" align=\"left\">"
					  <<	cDate
					  <<	"</td>";

			PrintDailyStatsPortion(
				(*iRegularStats)->GetNumberOfItems(),
				-1,
				(*iRegularStats)->GetDollarAmount());

			// sum
			AuctionItems  += (*iRegularStats)->GetNumberOfItems();
			AuctionDollar += (*iRegularStats)->GetDollarAmount();
			
			// get bid count for the day
			if (!BidEnd && (*iRegularStats)->GetDate() == (*iBidStats)->GetDate())
			{
				AuctionBids = (*iBidStats)->GetBidCount();
				iBidStats++;
				BidEnd = (iBidStats == pvBidStats->end());
			}

			RegularSumItems += (*iRegularStats)->GetNumberOfItems();
			RegularSumDollar += (*iRegularStats)->GetDollarAmount();

			RegularAdvanced = true;
		}
		else
		{
			// use the Dutch auction time
			// print te time and Regular auctin stats
			TheDate = (*iDutchStats)->GetDate();
			DateTm = localtime(&TheDate);
			sprintf(cDate, "%02d/%02d/%02d %s", 
				DateTm->tm_mon+1, 
				DateTm->tm_mday, 
				DateTm->tm_year,
				WeekDay[DateTm->tm_wday]);

			*mpStream <<	"<tr bgcolor=\""
					  <<	pColor
					  <<	"\"><td width=\"10%\" align=\"left\">"
					  <<	cDate
					  <<	"</td>";

			PrintDailyStatsPortion(0, -1, 0.0);

			// get bid count for the day
			if (!BidEnd && (*iDutchStats)->GetDate() == (*iBidStats)->GetDate())
			{
				AuctionBids = (*iBidStats)->GetBidCount();
				iBidStats++;
				BidEnd = (iBidStats == pvBidStats->end());
			}
		}

		//
		// determind what to print for Dutch
		//
		if (!DutchEnd && (*iRegularStats)->GetDate() >= (*iDutchStats)->GetDate())
		{
			PrintDailyStatsPortion(
				(*iDutchStats)->GetNumberOfItems(), 
				-1,
				(*iDutchStats)->GetDollarAmount());

			// sum
			AuctionItems  += (*iDutchStats)->GetNumberOfItems();
			AuctionDollar += (*iDutchStats)->GetDollarAmount();

			DutchSumItems += (*iDutchStats)->GetNumberOfItems();
			DutchSumDollar += (*iDutchStats)->GetDollarAmount();

			DutchAdvanced = true;
		}
		else
		{
			PrintDailyStatsPortion(0, -1, 0.0);
		}

		// Print the Total
		PrintDailyStatsPortion(AuctionItems, AuctionBids,  AuctionDollar);
		SumBids += AuctionBids;

		// determine the prograss
		if (RegularAdvanced)
		{
			iRegularStats++;
			RegularEnd = (iRegularStats == pvRegularStats->end());
		}

		if (DutchAdvanced)
		{
			iDutchStats++;
			DutchEnd = (iDutchStats == pvDutchStats->end());
		}


		*mpStream <<	"</tr>\n";
	}

	// Print the summary
	//
	// Print regular
	if (ColorSwitch)
	{
		pColor		= pYellowColor;
		ColorSwitch	= false;
	}
	else
	{
		pColor		= pWhiteColor;
		ColorSwitch	= true;
	}

	*mpStream <<	"<tr bgcolor=\""
			  <<	pColor
			  <<	"\"><td width=\"10%\" align=\"left\">"
			  <<	"<b>Total</b>"
			  <<	"</td>";

	PrintDailyStatsPortion(RegularSumItems, -1, RegularSumDollar);

	// Print Dutch
	PrintDailyStatsPortion(DutchSumItems, -1, DutchSumDollar);

	// Print Total
	PrintDailyStatsPortion(	RegularSumItems  + DutchSumItems, 
							SumBids,
							RegularSumDollar + DutchSumDollar);

	*mpStream <<	"</tr></table>"
			  <<	"<p>&nbsp;</p>\n";
}

//
// Show stats
//
void clseBayApp::ShowCompletedDailyStatistics(
								void* pRegularStats, 
								void* pDutchStats)
{
	DailyStatsVector::iterator	iRegularStats;
	DailyStatsVector::iterator	iDutchStats;
	bool	RegularEnd = false;
	bool	DutchEnd = false;
	bool	RegularAdvanced;
	bool	DutchAdvanced;
	int		Index;

	int		AuctionItems;
	int		AuctionBids;
	double	AuctionDollar;
	int		RegularSumItems = 0;
	int		RegularSumBids = 0;
	double	RegularSumDollar = 0;
	int		DutchSumItems = 0;
	int		DutchSumBids = 0;
	double	DutchSumDollar = 0;


	time_t	TheDate;
	struct tm*	DateTm;
	char	cDate[20];

	bool	ColorSwitch = false;
	char*	pColor;

	char*	pWhiteColor = "#FFFFFF";
	char*	pYellowColor = "#FFFFCC";

	DailyStatsVector* pvRegularStats = (DailyStatsVector*) pRegularStats;
	DailyStatsVector* pvDutchStats   = (DailyStatsVector*) pDutchStats;

	// Show table header and caption
	*mpStream <<	"<table border=1 cellpadding=1 "
					"width=\"100%\" cellspacing=0>\n"
					"<caption><font size=\"+1\"><big><strong>";

	*mpStream <<	"Auctions Completed";

	// Print the table header
	*mpStream <<	"</strong></big></font></caption>\n"
					"<tr bgcolor=\"#DDDDAA\"><th width=\"20%\" rowspan=2 valign=bottom>"
			  <<	"Date</th>\n"
					"<th width=\"10%\" colspan=3>"
			  <<	"Regular</th>\n"
					"<th width=\"10%\" colspan=3>"
			  <<	"Dutch</th>\n"
					"<th width=\"10%\" colspan=3>"
			  <<	"Total</th></tr>\n"
					"<tr bgcolor=\"#DDDDAA\"><th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Bids</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th>\n"
					"<th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Bids</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th>\n"
					"<th width=\"10%\">"
			  <<	"Items</th>\n"
					"<th width=\"10%\">"
			  <<	"Bids</th>\n"
					"<th width=\"10%\">"
			  <<	"Dollars</th></tr>\n";

	Index = 0;
	iRegularStats = pvRegularStats->begin();
	iDutchStats   = pvDutchStats->begin();

	RegularEnd = (iRegularStats == pvRegularStats->end());
	DutchEnd   = (iDutchStats   == pvDutchStats->end());

	while (!RegularEnd || !DutchEnd)
	{
		// init
		AuctionItems  = 0;
		AuctionBids   = 0;
		AuctionDollar = 0;
		RegularAdvanced = false;
		DutchAdvanced = false;

		if (ColorSwitch)
		{
			pColor		= pYellowColor;
			ColorSwitch	= false;
		}
		else
		{
			pColor		= pWhiteColor;
			ColorSwitch	= true;
		}

		//
		// determind what to print for Regular
		//
		if (!RegularEnd && 
			(DutchEnd || (*iRegularStats)->GetDate() <= (*iDutchStats)->GetDate()))
		{
			// print te time and Regular auctin stats
			TheDate = (*iRegularStats)->GetDate();
			DateTm = localtime(&TheDate);
			sprintf(cDate, "%02d/%02d/%02d %s", 
				DateTm->tm_mon+1, 
				DateTm->tm_mday, 
				DateTm->tm_year,
				WeekDay[DateTm->tm_wday]);

			*mpStream <<	"<tr bgcolor=\""
					  <<	pColor
					  <<	"\"><td width=\"10%\" align=\"left\">"
					  <<	cDate
					  <<	"</td>";

			PrintDailyStatsPortion(
				(*iRegularStats)->GetNumberOfItems(),
				(*iRegularStats)->GetBidCount(),
				(*iRegularStats)->GetDollarAmount());

			// sum
			AuctionItems  += (*iRegularStats)->GetNumberOfItems();
			AuctionBids   += (*iRegularStats)->GetBidCount();
			AuctionDollar += (*iRegularStats)->GetDollarAmount();

			RegularSumItems += (*iRegularStats)->GetNumberOfItems();
			RegularSumBids  += (*iRegularStats)->GetBidCount();
			RegularSumDollar += (*iRegularStats)->GetDollarAmount();

			RegularAdvanced = true;
		}
		else
		{
			// use the Dutch auction time
			// print te time and Regular auctin stats
			TheDate = (*iDutchStats)->GetDate();
			DateTm = localtime(&TheDate);
			sprintf(cDate, "%02d/%02d/%02d %s", 
				DateTm->tm_mon+1, 
				DateTm->tm_mday, 
				DateTm->tm_year,
				WeekDay[DateTm->tm_wday]);

			*mpStream <<	"<tr bgcolor=\""
					  <<	pColor
					  <<	"\"><td width=\"10%\" align=\"left\">"
					  <<	cDate
					  <<	"</td>";

			PrintDailyStatsPortion(0, 0, 0.0);
		}

		//
		// determind what to print for Dutch
		//
		if (!DutchEnd && (*iRegularStats)->GetDate() >= (*iDutchStats)->GetDate())
		{
			PrintDailyStatsPortion(
				(*iDutchStats)->GetNumberOfItems(), 
				(*iDutchStats)->GetBidCount(), 
				(*iDutchStats)->GetDollarAmount());

			// sum
			AuctionItems  += (*iDutchStats)->GetNumberOfItems();
			AuctionBids   += (*iDutchStats)->GetBidCount();
			AuctionDollar += (*iDutchStats)->GetDollarAmount();

			DutchSumItems += (*iDutchStats)->GetNumberOfItems();
			DutchSumBids  += (*iDutchStats)->GetBidCount();
			DutchSumDollar += (*iDutchStats)->GetDollarAmount();

			DutchAdvanced = true;
		}
		else
		{
			PrintDailyStatsPortion(0, 0, 0.0);
		}

		// Print the Total
		PrintDailyStatsPortion(AuctionItems, AuctionBids,  AuctionDollar);

		// determine the prograss
		if (RegularAdvanced)
		{
			iRegularStats++;
			RegularEnd = (iRegularStats == pvRegularStats->end());
		}

		if (DutchAdvanced)
		{
			iDutchStats++;
			DutchEnd = (iDutchStats == pvDutchStats->end());
		}


		*mpStream <<	"</tr>\n";
	}

	// Print the summary
	//
	// Print regular
	if (ColorSwitch)
	{
		pColor		= pYellowColor;
		ColorSwitch	= false;
	}
	else
	{
		pColor		= pWhiteColor;
		ColorSwitch	= true;
	}

	*mpStream <<	"<tr bgcolor=\""
			  <<	pColor
			  <<	"\"><td width=\"10%\" align=\"left\">"
			  <<	"<b>Total</b>"
			  <<	"</td>";

	PrintDailyStatsPortion(RegularSumItems, RegularSumBids, RegularSumDollar);

	// Print Dutch
	PrintDailyStatsPortion(DutchSumItems, DutchSumBids, DutchSumDollar);

	// Print Total
	PrintDailyStatsPortion(	RegularSumItems  + DutchSumItems, 
							RegularSumBids   + DutchSumBids,
							RegularSumDollar + DutchSumDollar);

	*mpStream <<	"</tr></table>"
			  <<	"<p>&nbsp;</p>\n";
}

// print a protion of the data
void clseBayApp::PrintDailyStatsPortion(int Items, int Bids, double Dollars)
{
	char	cItems[20];
	char	cBids[20];
	char	cDollars[20];

	// format data
	FormatString(Items, cItems);
	FormatString(Dollars, cDollars);
	if (Bids != -1)
	{
		FormatString(Bids, cBids);
	}


	*mpStream <<	"<td width=\"10%\" align=\"right\">"
			  <<	cItems
			  <<	"</td>";

	if (Bids != -1)
	{
		*mpStream	<<	"<td width=\"10%\" align=\"right\">"
					<<	cBids
					<<	"</td>";
	}
	
	*mpStream <<	"<td width=\"10%\" align=\"right\">"
			  <<	cDollars
			  <<	"</td>\n";

}
// sorting function
bool clseBayApp::SortByTime(clsDailyStatistics *pStats1, clsDailyStatistics *pStats2)
{
	return pStats1->GetDate() < pStats2->GetDate();
}

//
// format the value into xxx,xxx,xxx form
//
void clseBayApp::FormatString(int Value, char* pFormatedValue)
{
	int		Million = 1000000;
	int		Thousand = 1000;
	int		MPart;
	int		TPart;
	int		Left;

	MPart = Value / Million;
	TPart = (Value % Million) / Thousand;
	Left  = (Value % Million) % Thousand;
	if (MPart > 0)
	{
		sprintf(pFormatedValue, "%d,%03d,%03d", MPart, TPart, Left);
	}
	else if (TPart)
	{
		sprintf(pFormatedValue, "%d,%03d", TPart, Left);
	}
	else
	{
		sprintf(pFormatedValue, "%d", Left);
	}
}

//
// format the value into xxx,xxx,xxx form
//
void clseBayApp::FormatString(double Value, char* pFormatedValue)
{
	char	cValue[20];
	int		ValueLen;
	int		FormatedLen;
	int		i, j;

	sprintf(cValue, "%.02f", Value);
	ValueLen = strlen(cValue);
	FormatedLen = ValueLen + (ValueLen - 4) / 3;
	if (ValueLen > 6 && (ValueLen - 4) % 3 == 0  && cValue[0] == '-') 
	{
		FormatedLen--;
	}

	// convert the string
	for (i = FormatedLen, j = 0; j <= ValueLen; i--, j++)
	{
		if (j > 6 && (j - 4) % 3 == 0 && cValue[ValueLen - j] != '-')
		{
			pFormatedValue[i] = ',';
			i--;
		}

		pFormatedValue[i] = cValue[ValueLen - j];
	}
}
