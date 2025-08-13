/*	$Id: clsBDRunPages.cpp,v 1.2 1999/02/21 02:21:02 josh Exp $	*/
//
// Class Name:		clsBDRunPages
//
// Purpose:			Draw the HTML pages for the business development reports
//
// Author:			Chad Musick
//

#include "clsBDRunPages.h"
#include "clsBDTallyLists.h"
#include "clsBDCategoryInfo.h"
#include "clsWidgetContext.h"
#include "clsWidgetHandler.h"
#include "clsApp.h"
#include "clseBayWidget.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsDatabase.h"

#include <fstream.h>
#include <stdio.h>
#include <stdlib.h>

#ifdef _MSC_VER
#include <direct.h>
#else
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#define mkdir(x) mkdir(x, 0777)
#endif

// Constructor
clsBDRunPages::clsBDRunPages(time_t startTime, time_t endTime) :
	mStartTime(startTime), mEndTime(endTime)
{ 
	mpvPartners = new vector<const char *>;
	// Fetch the partners.
	gApp->GetDatabase()->GetPartnerIds(mpvPartners);

	return; 
}

// Destroy the partners as we destroy ourselves.
// Et tu Brute.
clsBDRunPages::~clsBDRunPages()
{
	vector<const char *>::iterator i;

	for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
	{
		// Bah. Compiler bug.
		delete (void *) *i;
	}
	delete mpvPartners;
}

// OpenFileForCategory
// Using the partnerId and categoryId, open pStream
// to a file named partnerName/category#.html with
// the following caveats:
//
// If the categoryId is 0, OpenFileForPartner is called
// If the partnerId is -1, the partner name is allpartners
// If the partnerId is -2, the partner name is allsites
//
// Also makes sure we are in the correct directory.
// pStream should not be open when passed.
bool clsBDRunPages::OpenFileForCategory(int partnerId,
										int categoryId,
										ofstream *pStream)
{
	char fileName[256];
	char directoryName[256];

	if (categoryId == 0)
		return OpenFileForPartner(partnerId, pStream);

	sprintf(fileName, "category%d.html", categoryId);
	if (partnerId == -1)
		strcpy(directoryName, "../allpartners");
	else if (partnerId == -2)
		strcpy(directoryName, "../allsites");
	else if (partnerId >= 0 && partnerId < mpvPartners->size() && 
		(*mpvPartners)[partnerId])
		sprintf(directoryName, "../%s", (*mpvPartners)[partnerId]);
	else
		sprintf(directoryName, "../partner%d", partnerId);

	if (chdir(directoryName))
	{
		mkdir(directoryName);
		if (chdir(directoryName))
			return false;
	}

	pStream->open(fileName);
	if (!pStream->good())
	{
		pStream->close();
		return false;
	}

	return true;
}

// OpenFileForPartner
// Using the partnerId, open pStream
// to a file named partnerName/index.html with
// the following caveats:
//
// If the partnerId is -1, the partner name is allpartners
// If the partnerId is -2, the partner name is allsites
//
// Also makes sure we are in the correct directory.
// pStream should not be open when passed.
bool clsBDRunPages::OpenFileForPartner(int partnerId,
									   ofstream *pStream)
{
	char fileName[256];
	char directoryName[256];

	strcpy(fileName, "index.html");
	if (partnerId == -1)
		strcpy(directoryName, "../allpartners");
	else if (partnerId == -2)
		strcpy(directoryName, "../allsites");
	else if (partnerId >= 0 && partnerId < mpvPartners->size() &&
		(*mpvPartners)[partnerId])
		sprintf(directoryName, "../%s", (*mpvPartners)[partnerId]);
	else
		sprintf(directoryName, "../partner%d", partnerId);

	if (chdir(directoryName))
	{
		mkdir(directoryName);
		if (chdir(directoryName))
			return false;
	}

	pStream->open(fileName);
	if (!pStream->good())
	{
		pStream->close();
		return false;
	}

	return true;
}

// A table of the strings used to generate the summary (partner)
// html pages.
static const char *sSummaryPagesHTMLTable[] =
{
	"<HTML>\n"
	"<HEAD>\n"
	"<META NAME=\"GENERATOR\" Content=\"Microsoft Developer Studio\">\n"
	"<META HTTP-EQUIV=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n"
	"<TITLE>",

	" -- ",

	" (",

	")</TITLE>\n"
	"</HEAD>\n"
	"<BODY>\n"
	"<FONT SIZE=+1>\n"
	"<H2>Covers period: ",

	"<BR>\n"
	"Partner ",

	"<BR>\n",

	" : ",

	"</H2><BR>\n"
	"<P>\n"
	"\n"
	"<TABLE WIDTH=80% COLS=2 BORDER=1>\n"
	"<TR><TD>Number of new registered users:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Total number of registered users:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Total number of any pages viewed:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of listing pages viewed:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new items placed for auction:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new bold items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new featured items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new category featured items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of bids made on items placed by partner users:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Number of bids made by partner users:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Number of successful auctions:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average number of bids per auction (successful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average number of bids per auction (all):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Percentage of auctions successful:</TD><TD>",
	
	"%</TD></TR>\n"
	"<TR><TD>Average length of auction in days (successful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average length of auction in days (unsuccessful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average length of auction in days (all):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average closing price for sold items:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (successful):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (unsuccessful):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (all):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Highest Closing Bid:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total value of items for auction:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total value of successful auctions:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Percentage of total auction value sold:</TD><TD>",
	
	"%</TD></TR>\n"
	"<TR><TD>Total revenue:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total auctions included:</TD><TD>",
	
	"</TD></TR>\n"
	"</TABLE>\n"
	"\n",
	
	"\n"
	"</FONT>\n"
	"</BODY>\n"
	"</HTML>\n",

	NULL
};

// A table of the strings used to generate a category page.
static const char *sCategoryPagesHTMLTable[] =
{
	"<HTML>\n"
	"<HEAD>\n"
	"<META NAME=\"GENERATOR\" Content=\"Microsoft Developer Studio\">\n"
	"<META HTTP-EQUIV=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n"
	"<TITLE>",

	" -- ",

	" (",

	")</TITLE>\n"
	"</HEAD>\n"
	"<BODY>\n"
	"<FONT SIZE=+1>\n"
	"<H2>Covers period: ",

	"<BR>\n"
	"Partner ",

	"<BR>\n",

	" : ",

	"</H2><BR>\n"
	"<P>\n"
	"\n"
	"<TABLE WIDTH=80% COLS=2 BORDER=1>\n"
	"<TR><TD>Number of listing pages viewed:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new items placed for auction:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Number of new bold items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new featured items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of new category featured items:</TD><TD>",

	"</TD></TR>\n"
	"<TR><TD>Number of bids made on items placed by partner users:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Number of bids made by partner users:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Number of successful auctions:</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average number of bids per auction (successful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average number of bids per auction (all):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Percentage of auctions successful:</TD><TD>",
	
	"%</TD></TR>\n"
	"<TR><TD>Average length of auction in days (successful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average length of auction in days (unsuccessful):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average length of auction in days (all):</TD><TD>",
	
	"</TD></TR>\n"
	"<TR><TD>Average closing price for sold items:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (successful):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (unsuccessful):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Average starting minimum price (all):</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Highest Closing Bid:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total value of items for auction:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total value of successful auctions:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Percentage of total auction value sold:</TD><TD>",
	
	"%</TD></TR>\n"
	"<TR><TD>Total revenue:</TD><TD>$",
	
	"</TD></TR>\n"
	"<TR><TD>Total auctions included:</TD><TD>",
	
	"</TD></TR>\n"
	"</TABLE>\n"
	"\n",
	
	"\n"
	"</FONT>\n"
	"</BODY>\n"
	"</HTML>\n",

	NULL
};

// An ordered list of the widgets in a summary page.
static eBayKnownWidgets sSummaryWidgetsTypes[] =
{
	wtTextWidget,
	wtBDPartnerNameWidget,
	wtTextWidget,
	wtBDDatesCoveredWidget,
	wtTextWidget,
	wtBDCategoryTitleWidget,
	wtTextWidget,
	wtBDDatesCoveredWidget,
	wtTextWidget,
	wtBDPartnerNameWidget,
	wtTextWidget,
	wtBDSuperCategoryTitleWidget,
	wtTextWidget,
	wtBDCategoryTitleWidget,
	wtTextWidget,
	wtBDNewUsersWidget,
	wtTextWidget,
	wtBDTotalUsersWidget,
	wtTextWidget,
	wtBDPartnerAllViewsWidget,
	wtTextWidget,
	wtBDCategoryViewsWidget,
	wtTextWidget,
	wtBDNewItemsCountWidget,
	wtTextWidget,
	wtBDNewBoldWidget,
	wtTextWidget,
	wtBDNewSuperFeaturedWidget,
	wtTextWidget,
	wtBDNewFeaturedWidget,
	wtTextWidget,
	wtBDBidsOnWidget,
	wtTextWidget,
	wtBDBidsByWidget,
	wtTextWidget,
	wtBDSuccessfulAuctionsWidget,
	wtTextWidget,
	wtBDBidsPerAuctionWidget,
	wtTextWidget,
	wtBDBidsPerAllAuctionsWidget,
	wtTextWidget,
	wtBDPercentSuccessfulAuctionsWidget,
	wtTextWidget,
	wtBDSuccessfulAuctionLengthWidget,
	wtTextWidget,
	wtBDUnsuccessfulAuctionLengthWidget,
	wtTextWidget,
	wtBDTotalAuctionLengthWidget,
	wtTextWidget,
	wtBDAverageClosePriceWidget,
	wtTextWidget,
	wtBDSuccessfulMinimumPriceWidget,
	wtTextWidget,
	wtBDUnsuccessfulMinimumPriceWidget,
	wtTextWidget,
	wtBDAllMinimumPriceWidget,
	wtTextWidget,
	wtBDHighestCloseWidget,
	wtTextWidget,
	wtBDTotalValueWidget,
	wtTextWidget,
	wtBDClosedBidTotalWidget,
	wtTextWidget,
	wtBDPercentageSoldValueWidget,
	wtTextWidget,
	wtBDTotalRevenueWidget,
	wtTextWidget,
	wtBDTotalAuctionsWidget,
	wtTextWidget,
	wtBDSubCategoriesTitlesWidget,
	wtTextWidget,
	wtTextWidget
};

// An ordered list of the widgets in a category page
static eBayKnownWidgets sCategoryWidgetsTypes[] =
{
	wtTextWidget,
	wtBDPartnerNameWidget,
	wtTextWidget,
	wtBDDatesCoveredWidget,
	wtTextWidget,
	wtBDCategoryTitleWidget,
	wtTextWidget,
	wtBDDatesCoveredWidget,
	wtTextWidget,
	wtBDPartnerNameWidget,
	wtTextWidget,
	wtBDSuperCategoryTitleWidget,
	wtTextWidget,
	wtBDCategoryTitleWidget,
	wtTextWidget,
	wtBDCategoryViewsWidget,
	wtTextWidget,
	wtBDNewItemsCountWidget,
	wtTextWidget,
	wtBDNewBoldWidget,
	wtTextWidget,
	wtBDNewSuperFeaturedWidget,
	wtTextWidget,
	wtBDNewFeaturedWidget,
	wtTextWidget,
	wtBDBidsOnWidget,
	wtTextWidget,
	wtBDBidsByWidget,
	wtTextWidget,
	wtBDSuccessfulAuctionsWidget,
	wtTextWidget,
	wtBDBidsPerAuctionWidget,
	wtTextWidget,
	wtBDBidsPerAllAuctionsWidget,
	wtTextWidget,
	wtBDPercentSuccessfulAuctionsWidget,
	wtTextWidget,
	wtBDSuccessfulAuctionLengthWidget,
	wtTextWidget,
	wtBDUnsuccessfulAuctionLengthWidget,
	wtTextWidget,
	wtBDTotalAuctionLengthWidget,
	wtTextWidget,
	wtBDAverageClosePriceWidget,
	wtTextWidget,
	wtBDSuccessfulMinimumPriceWidget,
	wtTextWidget,
	wtBDUnsuccessfulMinimumPriceWidget,
	wtTextWidget,
	wtBDAllMinimumPriceWidget,
	wtTextWidget,
	wtBDHighestCloseWidget,
	wtTextWidget,
	wtBDTotalValueWidget,
	wtTextWidget,
	wtBDClosedBidTotalWidget,
	wtTextWidget,
	wtBDPercentageSoldValueWidget,
	wtTextWidget,
	wtBDTotalRevenueWidget,
	wtTextWidget,
	wtBDTotalAuctionsWidget,
	wtTextWidget,
	wtBDSubCategoriesTitlesWidget,
	wtTextWidget,
	wtTextWidget
};

// Fills the passed vector with the widgets for category pages
// This function does it this way:
// loop through the widget types table for category pages --
// If it encounters a text widget, it gets the next string
// from the category page string table and makes a text widget.
// Otherwise it makes the widget specified.
//
// pHandler is necessary because it knows how to make the widgets.
// The vector is not emptied before filling it, and inserts at the
// back.
void clsBDRunPages::SetUpCategoryPages(vector<clseBayWidget *> *pvWidgets,
									   clsWidgetHandler *pHandler)
{
	clseBayWidget *pCurrentWidget;
	const char *pText;

	eBayKnownWidgets wType;
	int i, ctr;

	ctr = 0;

	for (i = 0; 1 == 1; ++i)
	{
		wType = sCategoryWidgetsTypes[i];
		if (wType == wtTextWidget)
		{
			pText = sCategoryPagesHTMLTable[ctr];
			++ctr;
			if (pText == NULL)
				return;

			pCurrentWidget = pHandler->GetWidget(wtTextWidget);
			pCurrentWidget->SetParams(strlen(pText) + 1, pText);
		}
		else
			pCurrentWidget = pHandler->GetWidget(wType);

		pvWidgets->push_back(pCurrentWidget);
	}

	return;
}

// Fills the passed vector with the widgets for summary pages
// This function does it this way:
// loop through the widget types table for summary pages --
// If it encounters a text widget, it gets the next string
// from the summary page string table and makes a text widget.
// Otherwise it makes the widget specified.
//
// pHandler is necessary because it knows how to make the widgets.
// The vector is not emptied before filling it, and inserts at the
// back.
void clsBDRunPages::SetUpSummaryPages(vector<clseBayWidget *> *pvWidgets,
									  clsWidgetHandler *pHandler)
{
	clseBayWidget *pCurrentWidget;
	const char *pText;

	eBayKnownWidgets wType;
	int i, ctr;

	ctr = 0;

	for (i = 0; 1 == 1; ++i)
	{
		wType = sSummaryWidgetsTypes[i];
		if (wType == wtTextWidget)
		{
			pText = sSummaryPagesHTMLTable[ctr];
			++ctr;
			if (pText == NULL)
				return;

			pCurrentWidget = pHandler->GetWidget(wtTextWidget);
			pCurrentWidget->SetParams(strlen(pText) + 1, pText);
		}
		else
			pCurrentWidget = pHandler->GetWidget(wType);

		pvWidgets->push_back(pCurrentWidget);
	}

	return;
}

// Run
// Does the actual output of the pages, in this way:
//
// Makes a new clsWidgetHandler and sets up the widget
// vectors for the two types of pages.
//
// Makes a new clsBDTallyLists to contain the information.
// Sets that object up and sets it as the context for the
// widget handler.
// 
// Makes the directory allsites.
//
// Loops through all categories, drawing the page for each
// (by using the widget handler). At each page, it asks for
// the stream to be opened to the appropriate page.
//
// Adds up all parnters except eBay. Draws as above.
//
// Adds up all partners including eBay. Draws as above.
//
void clsBDRunPages::Run()
{
	ofstream theFileStream;
	clsBDTallyLists *pLists;
	clsBDCategoryTally *pCategory;

	clsWidgetHandler *pWidgetHandler;
	clsWidgetContext *pWidgetContext;

	vector<clseBayWidget *> vCategoryWidgets;
	vector<clseBayWidget *> vSummaryWidgets;
	vector<clseBayWidget *> *pvWidgets;
	vector<clseBayWidget *>::iterator i;

	pWidgetHandler = new clsWidgetHandler(gApp->GetMarketPlaces()->GetCurrentMarketPlace(),
		gApp);
	pLists = new clsBDTallyLists(mStartTime, mEndTime);

	SetUpCategoryPages(&vCategoryWidgets, pWidgetHandler);
	SetUpSummaryPages(&vSummaryWidgets, pWidgetHandler);

	mkdir("allsites");
	if (chdir("allsites"))
	{
		cerr << "Could not create directories for storage.\n";
		return;
	}


	pWidgetContext = pWidgetHandler->GetWidgetContext();

	pWidgetContext->SetBDTallyObject(pLists);

	pLists->ResetPartner();
	pLists->ResetCategory();

	// In each case, alter the file opened by theFileStream as appropriate.
	while ((pCategory = pLists->NextCategory()) != NULL)
	{
		OpenFileForCategory(pCategory->mPartnerId, pCategory->mCategoryId, &theFileStream);
		if (pCategory->mPartnerId >= 0 && pCategory->mPartnerId < mpvPartners->size() &&
			(*mpvPartners)[pCategory->mPartnerId])
			pWidgetContext->SetBDPageName((*mpvPartners)[pCategory->mPartnerId]);
		else
			pWidgetContext->SetBDPageName("Unknown Partner");

		if (pCategory->mCategoryId)
			pvWidgets = &vCategoryWidgets;
		else
			pvWidgets = &vSummaryWidgets;

		// Drawing thing here for category pages.
		for (i = pvWidgets->begin(); i != pvWidgets->end(); ++i)
		{
			pWidgetHandler->OutputWidget(*i, &theFileStream);
		}
		theFileStream << endl << ends;
		theFileStream.close();
	}

	pLists->SumExcepteBay();

	pLists->ResetPartner();
	pLists->ResetCategory();

	pWidgetContext->SetBDPageName("All Partners");

	while ((pCategory = pLists->NextCategory()) != NULL)
	{
		// Skip eBay this time around.
		if (pCategory->mPartnerId == 0)
			continue;

		OpenFileForCategory(-1, pCategory->mCategoryId, &theFileStream);
		// Drawing thing here for summation category pages.
		if (pCategory->mCategoryId)
			pvWidgets = &vCategoryWidgets;
		else
			pvWidgets = &vSummaryWidgets;

		for (i = pvWidgets->begin(); i != pvWidgets->end(); ++i)
		{
			pWidgetHandler->OutputWidget(*i, &theFileStream);
		}
		theFileStream << endl << ends;
		theFileStream.close();
	}

	pLists->SumExceptNone();

	pLists->ResetPartner();
	pLists->ResetCategory();

	pWidgetContext->SetBDPageName("All Sites");

	while ((pCategory = pLists->NextCategory()) != NULL)
	{
		OpenFileForCategory(-2, pCategory->mCategoryId, &theFileStream);

		// Drawing thing here for super summation category pages.
		if (pCategory->mCategoryId)
			pvWidgets = &vCategoryWidgets;
		else
			pvWidgets = &vSummaryWidgets;

		for (i = pvWidgets->begin(); i != pvWidgets->end(); ++i)
		{
			pWidgetHandler->OutputWidget(*i, &theFileStream);
		}
		theFileStream << endl << ends;
		theFileStream.close();
	}

	delete pWidgetHandler;
	delete pLists;

	return;
}
