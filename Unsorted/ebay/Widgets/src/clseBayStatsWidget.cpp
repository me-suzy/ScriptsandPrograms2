/*	$Id: clseBayStatsWidget.cpp,v 1.5.166.3.96.2 1999/08/04 03:21:22 phofer Exp $	*/
//
//	File:	clseBayStatsWidget.cpp
//
//	Class:	clseBayStatsWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows eBay statistics using clseBayTableWidget.
//
// Modifications:
//				- 10/01/97	Poon - Created
//				- 08/02/99	petra - format number according to locale
//
#include "widgets.h"
#include "clseBayStatsWidget.h"

clseBayStatsWidget::clseBayStatsWidget(clsMarketPlace *pMarketPlace, 
									  clsApp *pApp) :
	clseBayTableWidget(pMarketPlace, pApp)
{
	memset(mCurrentItemCount, 0, sizeof(mCurrentItemCount));
	memset(mCategoryCount, 0, sizeof(mCategoryCount));
	memset(mItemCountSinceInception, 0, sizeof(mItemCountSinceInception));
	memset(mBidCountSinceInception, 0, sizeof(mBidCountSinceInception));
	memset(mHitsPerWeek, 0, sizeof(mHitsPerWeek));
	memset(mPageViewsPerDay, 0, sizeof(mPageViewsPerDay));
	memset(mFont, 0, sizeof(mFont));
	mFontSize = 2;
}

// Retrieve the stats from the database
bool clseBayStatsWidget::Initialize()
{
	clsDatabase *pDatabase;
	int			Million = 1000000;
	
	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	// get current time
	time_t CurrentTime;
	CurrentTime = time(0);

	// seed the random number generator with the current time
	srand((unsigned int)CurrentTime);

	// there will be 4 stats lines.
	// SetNumItems(4);
	SetNumItems(2);


	// get the database (will change this to clsItems once Wen fixes his code)
	pDatabase = mpApp->GetDatabase();

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " Start Getting Stats\n";
	}

	// get the stats
	if (mpMarketPlace && pDatabase)
	{
// petra		FormatValue(pDatabase->GetItemsCountOn(mpMarketPlace->GetId(), CurrentTime), mCurrentItemCount);
// petra		FormatValue(pDatabase->GetCategoryCount(mpMarketPlace->GetId()), mCategoryCount);
// petra		FormatValue(pDatabase->GetItemCountSinceInception(mpMarketPlace->GetId()) / Million, mItemCountSinceInception);
// petra		FormatValue(pDatabase->GetBidCountSinceInception(mpMarketPlace->GetId()) / Million, mBidCountSinceInception);

		clsIntlLocale *pLocale = mpMarketPlace->GetSites()->GetCurrentSite()->GetLocale();	// petra
		pLocale->GetNumberFormatted (&mCurrentItemCount[0],									// petra
									 pDatabase->GetItemsCountOn(mpMarketPlace->GetId(), CurrentTime), // petra
									 0);													// petra
		pLocale->GetNumberFormatted (&mCategoryCount[0],									// petra
									 pDatabase->GetCategoryCount(mpMarketPlace->GetId()),	// petra
									 0);													// petra
		pLocale->GetNumberFormatted (&mItemCountSinceInception[0],									// petra
									 pDatabase->GetItemCountSinceInception(mpMarketPlace->GetId()) / Million, // petra
									 0);													// petra
		pLocale->GetNumberFormatted (&mBidCountSinceInception[0],									// petra
									 pDatabase->GetBidCountSinceInception(mpMarketPlace->GetId()) / Million, // petra
									 0);													// petra

// petra		strcpy(mHitsPerWeek, "350,000,000");
// petra		strcpy(mPageViewsPerDay, "20,000,000");
		pLocale->GetNumberFormatted (&mHitsPerWeek[0], 350000000, 0);		// petra
		pLocale->GetNumberFormatted (&mPageViewsPerDay[0], 20000000, 0);	// petra
	}
	else
		return false;

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " End Getting Stats\n\n";
	}


	return true;
}


void clseBayStatsWidget::SetParams(vector<char *> *pvArgs)
{

	const char *pV;

	// for stats reporting
	time_t t;
	char pDate[128];
	char pTime[128];

	if (mpLoggingStream)
	{
		t = time(0);
		clsUtilities::GetDateAndTime(t, pDate, pTime);
		*mpLoggingStream << pDate << " " << pTime << " === BEGIN WIDGET " << (*pvArgs)[0] << " ===\n";
	}

	if ((pV = GetParameterValue("FONTSIZE", pvArgs)))
		SetFontSize(atoi(pV));

	if ((pV = GetParameterValue("FONT", pvArgs)))
		SetFont(pV);

	if ((pV = GetParameterValue("FONTCOLOR", pvArgs)))
		SetFontColor(pV);

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayTableWidget::SetParams(pvArgs);
}

// This will be called 4 times n=0..3
bool clseBayStatsWidget::EmitCell(ostream *pStream, int n)
{
	// set font for the cell
	*pStream <<		"<TD valign=\"top\">";

	// emit begin tags if user supplied them
	if (mBeginTags[0]!='\0')
		*pStream	<<	mBeginTags;

	*pStream <<		"<FONT size=\""
			 <<		mFontSize
			 <<		"\"";

	if (mFont[0]!='\0')
		*pStream	<<		" face=\""
					<<		mFont
					<<		"\"";

	if (mFontColor[0]!='\0')
		*pStream	<<		" color=\""
					<<		mFontColor
					<<		"\"";

	*pStream <<		">";

	// emit the appropriate stat
	switch (n)
	{
		case 0:
			*pStream <<		"<B>"
					 <<		mCurrentItemCount
//					 <<		"Over a million"
					 <<		"</B>"
							" items for sale in "
							"<B>"
					 <<		mCategoryCount
					 <<		"</B>"
					 <<		" categories now!";
			break;
/*
		case 1:
			*pStream <<		"Over <B>"
			  		 <<		mItemCountSinceInception
					 <<		" million</B>"
							" items for sale on eBay since inception!";
			break;
		case 2:
			*pStream <<		"Over <B>"
					 <<		mBidCountSinceInception
					 <<		" million</B>"
							" bids made since inception!";
			break;	

 */
		case 1:
			/*
			if ((rand() % 2)==0)
			{
				*pStream <<		"Over "
								"<B>"
						 <<		mHitsPerWeek
						 <<		"</B>"
								" hits per week!";
			}
			else
			{
				*pStream <<		"Over "
								"<B>"
						 <<		mPageViewsPerDay
						 <<		"</B>"
								" page views per day!";
			}
			*/

			*pStream <<		"Over "
							"<B>"
					 <<		"1.5 billion"
					 <<		"</B>"
							" page views per month!";
			break;				

	}

	// emit end tags if user supplied them
	if (mEndTags[0]!='\0')
		*pStream	<<	mEndTags;	

	*pStream <<		"</FONT></TD>\n";

	return true;
}

/* petra
//
// format the value into xxx,xxx,xxx form
//
void clseBayStatsWidget::FormatValue(int Value, char* pFormatedValue)
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
*/
