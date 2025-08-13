/*	$Id: clseBayAppViewListedItems.cpp,v 1.14.22.1.102.3 1999/08/06 20:31:55 nsacco Exp $	*/
//
//	File:	clseBayAppViewListedItems.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewBids.cpp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods user to retrieve
//		and show all items listed by a user.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 01/05/98 Charles  - modified for privacy User ID
//				- 04/02/98 wen		- added function to show high bidder emails
//				- 05/13/99 Jennifer - put (*) for all ended auction and a notes
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//									  
//

#include "ebihdr.h"
#include "clsNameValue.h"
#include "clseBayTimeWidget.h"	// petra

// Added by Charles
#include "clsUserIdWidget.h"

#include "clsUserValidation.h"

// TODO - use resource?
#define NewIconURL	"http://pics.ebay.com/aw/pics/new.gif"

const char ErrorMsgTooManyRequest[] = "<h2>Too Many E-mail Addresses Requested </h2>"
"You have requested too many e-mail addresses today. ";

//
// InsertPaginationControl
//
// this ugly thing puts in a pagination controller for the item list
//

void clseBayApp::InsertPaginationControl(ItemList *pItemList,
	clsUser *pUser,
	bool showCount,
	int *currentItem, int *lastItem, int *startingItem,
	int include,
	int startingPage, 
	int rowsPerPage,
	int daysSince,
	int sortOrder,
	char *pDLLCmd,
	char *pRequester,
	char *pPass,
	char *pAllFlag,		// ViewBidItems
	bool showCompleted)	// ViewBidItems
{
	int numOfColumns;
	int numberOfItems;
	int NumberOfPages;
	int j;
	int CurrentPage;
	char	PageNumber[10];
	char str[255];
	int		Limit = 5;
	
	// pagination stuff - let's figure out how where we'll start in the set of auctions
	numberOfItems = pItemList->size();
	
	if (include)
        numOfColumns = 7;
	else
        numOfColumns = 6;
	
	if ((startingPage != 0) && (rowsPerPage != 0)) {
        if (rowsPerPage > MAX_ROWS)
			rowsPerPage = MAX_ROWS;
		
        if (pItemList->size() < rowsPerPage)
			*startingItem = 1;
        else
			*startingItem = ((startingPage - 1 ) * rowsPerPage) + 1;
		
        // check to make sure we haven't overflowed
        // reset startingItem & startingPage if so
        if (*startingItem > pItemList->size()) {
			*startingItem = 1;
			startingPage = 1;
        }
		
        // set the starting & endpoints
        *currentItem = 1;
        //	if((startingItem + rowsPerPage) < pItemList->size())
        *lastItem = *startingItem + rowsPerPage; 
        //	else
        //		lastItem = pItemList->size();	
	} else {
		// set up simple set of numbers for display on "all items on one page" display
		*startingItem = 1;
	}
			
	// tell the users how many items of the total number we are showing
	if ((pItemList->size() >= 1) && (startingPage != 0) && (rowsPerPage != 0)) {
//		HACK! put this next line back in (and remove the above line) when going to production! 
//		it'll show the item count whether they are paginating or not - pvh
//		if ((pItemList->size() >= 1)) {
        {
			
			if(showCount) {
				*mpStream <<   "<tr>\n";
				//	*mpStream <<	"<th>\n";
				//	*mpStream <<	"<td width=\"100%\" valign=\"middle\" align=\"center\" colspan=\"2\">\n";
				*mpStream <<   "<td align=\"center\" colspan=\"";
				sprintf(str, "%d", numOfColumns);
				*mpStream   << str
					<< "\">\n";

				// set a smaller font size
				*mpStream   << "<font size=-1>";
				
				*mpStream   << "Items ";
				sprintf(str, "%d", *startingItem);
				*mpStream   << str
					<< " - ";
				sprintf(str, "%d", ((*lastItem - 1) < numberOfItems) ? (*lastItem - 1) : numberOfItems);
				*mpStream   << str;
				*mpStream   << " of ";
				sprintf(str, "%d", pItemList->size());
				*mpStream   << str
					<< " total<br>";
				
				*mpStream <<   "</font></td>\n";
				*mpStream <<   "</tr>\n";
			}	
		}

		// start of control row
		
		// the first test checks to see if all items will fit on one page, if so don't show the number of pages
		if(!((*startingItem == 1) && (*lastItem > numberOfItems))) {
			// makes sure we want to show any pagination
			if ((pItemList->size() >= 1) && (startingPage != 0) && (rowsPerPage != 0)) {
				*mpStream <<   "<tr>\n";
				//	*mpStream <<	"<td width=\"50%\" valign=\"middle\" align=\"left\">\n";
				*mpStream <<   "<td align=\"center\" colspan=\"";
				sprintf(str, "%d", numOfColumns);
				*mpStream   << str
					<< "\">\n";
								
				// set a smaller font size
				*mpStream   << "<font size=-1>";

				// Wen's modified stuff
				
				// down arrow
				if (*startingItem == 1) {
					//				*mpStream	<<  "<<";
					
				}
				else 
				{
					*mpStream   <<  "<A href=\""
						<< mpMarketPlace->GetCGIPath(PageViewListedItems)
						<< "eBayISAPI.dll?"
						<< pDLLCmd;

				if (include)
				{
					*mpStream	<<	"&requested=";
				}
				else
				{
					*mpStream	<<	"&userid=";
				}

				*mpStream << pUser->GetUserId()
						<< "&sort="
						<< sortOrder
						//                 << "&since="
						//                 << daysSince
						<< "&page="
						<< startingPage - 1
						<< "&rows="
						<< rowsPerPage;
					
					// ViewListedItems
					if(daysSince != -100)  {
						*mpStream << "&since="
							<< daysSince;
					}
					
					// ViewAllBidItems
					if(showCompleted)  {
						*mpStream <<	"&completed="
							<<	showCompleted;
					}		  
					if(pAllFlag != NULL)  {
						*mpStream <<	"&all="
							<<	pAllFlag;
					}
					
					
					// this is ugly... check to see if we want emails as well
					if (include) {
						*mpStream <<   "&userid="
							<< pRequester
							<< "&pass="
							<< pPass;
					}
					*mpStream <<   "\"><< </a>";
					
					// space between arrows and page guides
					*mpStream   <<  "&nbsp;&nbsp;";
				}
				
				
				//			*mpStream <<	"<tr>\n";
				//			*mpStream <<	"<td align=\"center\" colspan=\"6\">\n";
				
				// figure out how many total pages there are
				NumberOfPages = (numberOfItems-1)/rowsPerPage + 1;
				// get the current page we're on
				if (rowsPerPage != 1)
					CurrentPage = (*startingItem + rowsPerPage) / rowsPerPage;
				else
					CurrentPage = *startingItem;
				
				if (NumberOfPages < 25) {
					for (j = 1; j <= NumberOfPages; j++) {
						if (j != CurrentPage) {
							
							// build the URL
							*mpStream <<   ""
								"<A href=\""
								<< mpMarketPlace->GetCGIPath(PageViewListedItems)
								<< "eBayISAPI.dll?"
								<< pDLLCmd;

								if (include)
								{
									*mpStream	<<	"&requested=";
								}
								else
								{
									*mpStream	<<	"&userid=";
								}

							*mpStream << pUser->GetUserId()
								<< "&sort="
								<< sortOrder
								//                       << "&since="
								//                       << daysSince
								<< "&page="
								<< j
								<< "&rows="
								<< rowsPerPage;
							
							// ViewListedItems
							if(daysSince != -100)  {
								*mpStream << "&since="
									<< daysSince;
							}
							
							// ViewAllBidItems
							if(showCompleted)  {
								*mpStream <<	"&completed="
									<<	showCompleted;
							}		  
							if(pAllFlag != NULL)  {
								*mpStream <<	"&all="
									<<	pAllFlag;
							}
							
							
							// this is ugly... check to see if we want emails as well
							if (include) {
								*mpStream <<   "&userid="
									<< pRequester
									<< "&pass="
									<< pPass;
							}
							*mpStream <<   "\""
								">";
							
							sprintf(PageNumber, "[%d]</a>\n", j);
							*mpStream << PageNumber;
						} else {
							sprintf(PageNumber, " = %d = ", j);
							*mpStream << "<b>"
								<< PageNumber
								<< "</b>";
						}
						
					}
				} else {
					for (j = 1; j <= NumberOfPages; j++) {
						if (j != CurrentPage) {
							if (abs(CurrentPage - j) <= Limit || j % 10 == 0 || j == 1 || j == NumberOfPages) {
								// build the URL
								*mpStream <<   ""
									"<A href=\""
									<< mpMarketPlace->GetCGIPath(PageViewListedItems)
									<< "eBayISAPI.dll?"
									<< pDLLCmd;

								if (include)
								{
									*mpStream	<<	"&requested=";
								}
								else
								{
									*mpStream	<<	"&userid=";
								}

								*mpStream << pUser->GetUserId()
									<< "&sort="
									<< sortOrder
									//                           << "&since="
									//                          << daysSince
									<< "&page="
									<< j
									<< "&rows="
									<< rowsPerPage;
								
								// ViewListedItems
								if(daysSince != -100)  {
									*mpStream << "&since="
										<< daysSince;
								}
								
								// ViewAllBidItems
								if(showCompleted)  {
									*mpStream <<	"&completed="
										<<	showCompleted;
								}		  
								if(pAllFlag != NULL)  {
									*mpStream <<	"&all="
										<<	pAllFlag;
								}
								
								// this is ugly... check to see if we want emails as well
								if (include) {
									*mpStream <<   "&userid="
										<< pRequester
										<< "&pass="
										<< pPass;
								}
								*mpStream <<   "\""
									">";
								
								sprintf(PageNumber, "[%d]</a>\n", j);
								*mpStream << PageNumber;
								
								if ((CurrentPage - j) > Limit+1 || 
									((j - CurrentPage) >= Limit && (NumberOfPages - j) > 1) && (10 - j % 10) > 1) {
									*mpStream << "...";
								}
							}
						} else {
							sprintf(PageNumber, " = %d = ", j);
							*mpStream << "<b>"
								<< PageNumber
								<< "</b>";
						}
					}
				}
				
				// up arrow
				if (*lastItem > pItemList->size()) {
					//				*mpStream	<<  ">> ";
					
				} else {
					// space between arrows and page guides
					*mpStream   <<  "&nbsp;&nbsp;";
					
					*mpStream   <<  "<A href=\""
						<< mpMarketPlace->GetCGIPath(PageViewListedItems)
						<< "eBayISAPI.dll?"
						<< pDLLCmd;

					if (include)
					{
						*mpStream	<<	"&requested=";
					}
					else
					{
						*mpStream	<<	"&userid=";
					}

					*mpStream << pUser->GetUserId()
						<< "&sort="
						<< sortOrder
						//                 << "&since="
						//                 << daysSince
						<< "&page="
						<< startingPage + 1
						<< "&rows="
						<< rowsPerPage;
					
					// ViewListedItems
					if(daysSince != -100)  {
						*mpStream << "&since="
							<< daysSince;
					}
					
					// ViewAllBidItems
					if(showCompleted)  {
						*mpStream <<	"&completed="
							<<	showCompleted;
					}		  
					if(pAllFlag != NULL)  {
						*mpStream <<	"&all="
							<<	pAllFlag;
					}
					
					// this is ugly... check to see if we want emails as well
					if (include) {
						*mpStream <<   "&userid="
							<< pRequester
							<< "&pass="
							<< pPass;
					}
					*mpStream <<   "\">>> </a>";
					
					
				}
				
				*mpStream <<   "</font></td>\n";
				*mpStream <<   "</tr>\n";
				
				// Wen's stuff
				
			}
			
			
			//		<<	" of "
			//		<<	printf("%d", pItemList->size())
			//		<<	" total<br>";
			
			//		<<	printf("%d - %d of %d total<br>", startingItem, lastItem, pItemList->size());
		 }
	 }
    // end of pagination	 
}


//
// GetAndShowListedItems
//
//	This routine actually retrieves and emits
//	the items a user has listed. It's a seperate
//	method so that it can be called independantly
//	of ViewListedItems. The latter emits a <TITLE>
//	and other goodies
//
void clseBayApp::GetAndShowListedItems(clsUser *pUser,
									   int daysSince,
									   ItemListSortEnum sortOrder,
									   char *pCmd,
									   bool include /*=false*/,
									   char* pRequester /*=NULL*/,
									   char* pPass /*=NULL*/,
									   int startingPage,
									   int rowsPerPage)
{
    // This is a vector of the items
    ItemList					*pItemList;
	char *cleanTitle = NULL;
	
    // And an iterator for it
    ItemList::iterator			i;
	
    // Added by Charles
    clsUserIdWidget				*pUserIdWidget;
	
    //
    char						*pDLLCmd;
	
    // Time Fields
    time_t						curTime;
// petra    time_t						startTime;
// petra    time_t						endTime;
// petra    struct tm					*pTheTime;
	
// petra    char						cStartTime[32];
// petra    char						cEndTime[32];

	bool						bHasCookie = false;
	bool						bAuctionEnded = false;
	
	double						maxAmount;

    // email count
    int ThisReq=0;
	
	/*
	// pagination stuff
	int		Limit = 5, numberOfItems, NumberOfPages, j, CurrentPage, numOfColumns;
	char	PageNumber[10];
	*/
	int startingItem, currentItem, lastItem;
	
    // Let's get the items listed by this user
    pItemList	= new ItemList;
	//	mpDatabase->Begin();
	//	mpDatabase->SetSerializable();
    pUser->GetListedItems(pItemList, daysSince, false, sortOrder);
	//	mpDatabase->End();
	
	
    // Well, THAT was nice. Let's start emitting
    // stuff.
    curTime	= time(0);
	
    // Prepare the stream
    mpStream->setf(ios::fixed, ios::floatfield);
    mpStream->setf(ios::showpoint, 1);
    mpStream->precision(2);
	
    // Added by Charles
    pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
    pUserIdWidget->SetUser(pUser);
    pUserIdWidget->SetShowUserStatus(false);
	pUserIdWidget->SetIncludeEmail(include);

	
    if (daysSince != -1)
    {
		*mpStream	<<	"<h2>"
			<<	"Current and recent auctions by ";
		// Added by Charles
		pUserIdWidget->EmitHTML(mpStream);
		
		*mpStream	<<	"</h2>"
			<<	"Includes ongoing auctions and auctions which ended "
			<<	"in the last "
			<<	daysSince
			<<	" days. Bold price means at least "
			"one bid has been received."
			"<p>"
			"\n";
    }
    else
    {
		*mpStream	<<	"<h2>"
			<<	"Current auctions by ";
		// Added by Charles
		pUserIdWidget->EmitHTML(mpStream);
		
		*mpStream	<<	"</h2>"
			"Includes ongoing auctions only. Bold price "
			"means at least one bid has been received."
			"<p>"
			"\n";
    }


    // Table Heading
    if (pCmd)
    	pDLLCmd	= pCmd;
    else
    {
    	if (include)
    		pDLLCmd = "ViewListedItemsWithEmails";
    	else
    		pDLLCmd	= "ViewListedItems";
    }

    // make it easy for a user to show the emails of high bidders
    if (include == false)
    {
		*mpStream <<	"<p>\n";
		*mpStream <<	" If you have a registered User ID and password, and would like also to see the <b>e-mail addresses</b> of the high bidders, click <a href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&userid="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	sortOrder
			<<	"&since="
			<<	daysSince
			<<	"&include=1"		// this is the trick
			<<	"&rows="
			<<	rowsPerPage			// for pagination
			<<	"\""
			">"
			"here"
			"</a>.";
		
    }
	
    *mpStream <<	"<p>\n";
	
    *mpStream <<		"You can click on the <strong>Start Time</strong>, "
		"<strong>End Time</strong>, or "
		"<strong>Price</strong> links to sort "
		"the list."
		"<p>";
	
	// beginning of table
	*mpStream <<	"<table border=1>\n";
	
	InsertPaginationControl(pItemList, pUser, true, &currentItem, &lastItem, 
		&startingItem, include, startingPage, rowsPerPage, 
		daysSince, sortOrder, pDLLCmd, pRequester, pPass, NULL /*pAllFlag*/, false /*showCompleted*/);
	
    if (include)
    {
		*mpStream <<	"<tr>"
    		"<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&requested="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsById
			<<	"&since="
			<<	daysSince;
			// tsk tsk, we can have null cases here
			if(pRequester != NULL) {
				*mpStream <<	"&userid="
				<<	pRequester;
			}
			// tsk tsk, we can have null cases here
			if(pPass != NULL) {
				*mpStream <<	"&pass="
				<<	pPass;
			}
		*mpStream		<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"
			"Item"
			"</a>"
			"</th>\n";

		*mpStream << "<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&requested="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByStartTime
			<<	"&since="
			<<	daysSince
			<<	"&userid="
			<<	pRequester
			<<	"&pass="
			<<	pPass
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"	
			"Start"
			"</a>"
			"</th>\n"
			"<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&requested="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByEndTime
			<<	"&since="
			<<	daysSince
			<<	"&userid="
			<<	pRequester
			<<	"&pass="
			<<	pPass
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"
			"End"
			"</a>"
			"</th>\n"
			"<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&requested="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByPrice
			<<	"&since="
			<<	daysSince
			<<	"&userid="
			<<	pRequester
			<<	"&pass="
			<<	pPass
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"
			"Price"
			"</a>"
			"</th>\n"
			"<th>Title</th>\n"
			"<th>High Bidder</th>\n"
			<<	"<th>High Bidder's Email</th>\n";
    }
    else
	{
		*mpStream <<	"<tr>"
    					"<th>"
						"<A href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
						<<	"eBayISAPI.dll?"
						<<	pDLLCmd
						<<	"&userid="
						<<	pUser->GetUserId()
						<<	"&sort="
						<<	SortItemsById
						<<	"&since="
						<<	daysSince
						<<	"&page="
						<<	startingPage
						<<	"&rows="
						<<	rowsPerPage
						<<	"\""
						">"
						"Item"
						"</a>"
						"</th>\n";

		*mpStream << "<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&userid="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByStartTime
			<<	"&since="
			<<	daysSince
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"	
			"Start"
			"</a>"
			"</th>\n"
			"<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&userid="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByEndTime
			<<	"&since="
			<<	daysSince
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"
			"End"
			"</a>"
			"</th>\n"
			"<th>"
			"<A href=\""
			<<	mpMarketPlace->GetCGIPath(PageViewListedItems)
			<<	"eBayISAPI.dll?"
			<<	pDLLCmd
			<<	"&userid="
			<<	pUser->GetUserId()
			<<	"&sort="
			<<	SortItemsByPrice
			<<	"&since="
			<<	daysSince
			<<	"&page="
			<<	startingPage
			<<	"&rows="
			<<	rowsPerPage
			<<	"\""
			">"
			"Price"
			"</a>"
			"</th>\n"
			"<th>Title</th>\n"
			"<th>High Bidder</th>\n";
    }

    *mpStream << "</tr>\n";
	
    // If we didn't get any items, then, well,
    // we're done.
    if (pItemList->size() < 1)
    {
		*mpStream <<	"</table>";
		delete pUserIdWidget;
		delete pItemList;
		return;
    }
	

	// check for the adult cookie
	bHasCookie = HasAdultCookie();
	
	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // We'll set the values in the loop

	// Now, iterate over the items
	for (i = pItemList->begin(); i != pItemList->end(); i++)
	{
		// if startingPage == 0, then we show them all, else make sure we're in range
		if((startingPage == 0) || (rowsPerPage == 0) || ((currentItem < lastItem) && (currentItem >= startingItem))) {
			
			// First, the item Number
			*mpStream <<	"<tr>"
				"<td>"
				"<a href="
				"\""
				<<	mpMarketPlace->GetCGIPath(PageViewItem)
				<<	"eBayISAPI.dll?ViewItem&item="
				<<	(*i).mpItem->GetId()
				<<	"\""
				">"
				<<	(*i).mpItem->GetId()
				<<	"</a>"
				"</td>";
			
			
			// Now, let's format the dates (ick)
// petra			startTime	= (*i).mpItem->GetStartTime();
// petra			pTheTime	= localtime(&startTime);
// petra			strftime(cStartTime, sizeof(cStartTime), 
// petra				"%m/%d/%y",
// petra				pTheTime);
			
// petra			endTime		= (*i).mpItem->GetEndTime();
// petra			pTheTime	= localtime(&endTime);
// petra			strftime(cEndTime, sizeof(cEndTime),
// petra				"%m/%d/%y %H:%M:%S",
// petra				pTheTime);

			clseBayTimeWidget timeWidget (mpMarketPlace, 1, -1,				// petra
										  (*i).mpItem->GetStartTime() );	// petra
    	
			// And Show them
			*mpStream <<	"<td>";
			timeWidget.EmitHTML (mpStream);	// petra
// petra				<<	cStartTime
			*mpStream <<	"</td>"
							"<td>";
			timeWidget.SetDateTimeFormat (1, 1);	// petra
			timeWidget.EmitHTML (mpStream);			// petra
// petra				<<	cEndTime
			*mpStream <<	"</td>";
			
			// Price (Bold if there are bids)
			*mpStream <<	"<td align=right>";

			maxAmount = mpMarketPlace->GetMaxAmount((*i).mpItem->GetCurrencyId());
			
			if ((*i).mpItem->GetPrice() > maxAmount ||
				(*i).mpItem->GetStartPrice() > maxAmount)
				*mpStream << "<b>Error</b>";
			else
			{
				if ((*i).mpItem->GetBidCount() > 0 &&
					(*i).mpItem->GetPrice() > 0)
				{
					currencyWidget.SetNativeAmount((*i).mpItem->GetPrice());
					currencyWidget.SetNativeCurrencyId((*i).mpItem->GetCurrencyId());
					currencyWidget.SetBold(true);
					currencyWidget.EmitHTML(mpStream);
				}
				else
				{
					currencyWidget.SetNativeAmount((*i).mpItem->GetStartPrice());
					currencyWidget.SetNativeCurrencyId((*i).mpItem->GetCurrencyId());
					currencyWidget.SetBold(false);
					currencyWidget.EmitHTML(mpStream);
				}
			}
			
			*mpStream <<	"</td>";
			
			
			// Title
			*mpStream	<<	"<td>";

			if ((*i).mpItem->IsAdult() && !bHasCookie)
			{
				 *mpStream	<< "<font color=\"red\">Hidden - Requires Adult Verification</font>";
			}
			else
			{
				cleanTitle = clsUtilities::StripHTML((*i).mpItem->GetTitle());
				*mpStream << cleanTitle;
				delete [] cleanTitle;
			}
			*mpStream	<<	"</td>";
			
			
			// High Bidder (if NOT private ;-))
			if ((*i).mpItem->GetPrivate())
			{
				if (include)
					*mpStream << "<td colspan=2>";
				else
					*mpStream << "<td>";
				
				*mpStream <<	"Private Auction";

				if ((*i).mpItem->GetEndTime() < curTime)
				{
					*mpStream	<< "(*)";
					bAuctionEnded = true;
				}				
				
				*mpStream << "</td>";
			}
			else if ((*i).mpItem->GetQuantity() > 1)
			{
				if (include)
					*mpStream << "<td colspan=2>";
				else
					*mpStream << "<td>";
				
				*mpStream <<	"<A HREF="
					"\""
					<<	mpMarketPlace->GetCGIPath(PageViewBids)
					<<	"eBayISAPI.dll?ViewBids"
					<<	"&item="
					<<	(*i).mpItem->GetId()
					<<	"#dutch"
					"\""
					">"
					<<	(*i).mpItem->GetBidCount()
					<<	" Dutch bids"
					"</A>";
				if ((*i).mpItem->GetEndTime() < curTime)
				{
					*mpStream	<< "(*)";
					bAuctionEnded = true;
				}				
				*mpStream << "</td>";

			}
			else if ((*i).mpItem->GetBidCount() > 0 &&
				(*i).mpItem->GetPrice() > 0)
			{
				*mpStream << "<td>";
				
				// Added by Charles
				pUserIdWidget->SetUserInfo((*i).mpItem->GetHighBidderUserId(), 
										(*i).mpItem->GetHighBidderEmail(),
										UserStateEnum(0), false, (*i).mpItem->GetHighBidderFeedbackScore());
				pUserIdWidget->SetShowUserStatus(false);
				pUserIdWidget->SetShowStar(true);
				pUserIdWidget->SetUserIdOnly();
				pUserIdWidget->EmitHTML(mpStream);
				
				if ((*i).mpItem->GetEndTime() < curTime)
				{
					*mpStream	<< "(*)";
					bAuctionEnded = true;
				}
				
				*mpStream << "</td>";
				
				if (include)
				{
					*mpStream << "<td><a href=\"mailto:"
								<<	(*i).mpItem->GetHighBidderEmail()
								<<	"\">"
								<<	(*i).mpItem->GetHighBidderEmail()
								<<	"</a></td>";
					ThisReq++;
				}
			}
			else
			{
				if (include)
					*mpStream << "<td colspan=2>";
				else
					*mpStream << "<td>";
				
				if ((*i).mpItem->GetEndTime() < curTime)
				{
					*mpStream <<	"No Bids (*) </td>";
					bAuctionEnded = true;
				}
				else
				{
					*mpStream <<    "No Bids Yet </td>";
				}
			}
			
			*mpStream <<	"</tr>"
				"\n";
		} else { 
		} // end pagination if
	
		currentItem++;
	} // end if
 
	// show controls
	InsertPaginationControl(pItemList, pUser, false, &currentItem, &lastItem, 
					&startingItem, include, startingPage, rowsPerPage, 
					daysSince, sortOrder, pDLLCmd, pRequester, pPass, NULL /*pAllFlag*/, false /*showCompleted*/);

	*mpStream <<	"</table>";

	if (bAuctionEnded == true)
	{
		*mpStream << "<br> (*) indicates that auction has ended.";
	}

    // Added by Charles
    delete pUserIdWidget;

    // Clean up
    for (i = pItemList->begin();
    	 i != pItemList->end();
    	 i++)
    {
    	delete	(*i).mpItem;
    }

    pItemList->erase(pItemList->begin(), 
    				  pItemList->end());

    delete	pItemList;

    // update user email requesting account
    if (include && mpUsers->GetUserValidation()->IsSoftValidated())
    	mpUser->AddReqEmailCount(ThisReq);

    return;
}

  
//
// ViewListedItems
//
void clseBayApp::ViewListedItems(CEBayISAPIExtension *pThis,
								 char *pRequested,
								 bool completed,
								 ItemListSortEnum sort,
								 int daysSince,
								 bool include,
								 int startingPage,
								 int rowsPerPage)
{

    SetUp();

	if (include)
	{
		if (mpUsers->GetUserValidation()->IsSoftValidated())
		{
			ViewListedItemsWithEmails(pThis, "default", "default", pRequested,
				completed, sort, daysSince, startingPage, rowsPerPage);
		}
		else
		{
			char Action[255];
			char Sort[10];
			char Since[10];
			char Rows[10];
			clsNameValuePair theNameValuePairs[6];

			// Create the actions tring
			sprintf(Action, "%seBayISAPI.dll", mpMarketPlace->GetCGIPath(PageViewListedItemsWithEmails));
			sprintf(Sort, "%d", sort);
			sprintf(Since, "%d", daysSince);
			sprintf(Rows, "%d", rowsPerPage);

			// create the name value pairs
			theNameValuePairs[0].Set("MfcISAPICommand", "ViewListedItemsWithEmails");
			theNameValuePairs[1].Set("requested", pRequested);
			theNameValuePairs[2].Set("completed", completed ? "1" : "0");
			theNameValuePairs[3].Set("sort", Sort);
			theNameValuePairs[4].Set("since", Since);
			theNameValuePairs[5].Set("rows", Rows);

			// show login page
			LoginDialog(Action, 6, theNameValuePairs);

			CleanUp();
		}
		return;
	}

    // Title
    *mpStream <<	"<html><head>"
    				"<title>"
    		  <<	mpMarketPlace->GetCurrentPartnerName()
    		  <<	" Seller List: "
    		  <<	pRequested
    		  <<	"</title>"
    				"</head>"
    		  <<	mpMarketPlace->GetHeader();

    mpUser = mpUsers->GetAndCheckUser(pRequested, mpStream);
    if (!mpUser)
    {
    	CleanUp();
    	return;
    }

	    // People may have bookmarks with "completed" in them, 
    // but no "since". We have to account for them, plus
    // the default of "completed" to "false".
    //
    // Completed	since		:	Completed	since
    //	false		-1				false		-1
    //	false		!= -1			true		!= -1
    //	true		-1				true		30
    //	true		!= -1			true		!= -1
    //
    if (!completed)
    {
    	// If daysSince is -1, then we're fine, since it
    	// defaulted. If it's not, then they really DO 
    	// want completed
    	if (daysSince != -1)
    	{
    		completed	= true;
    	}
    }
    else
    {
    	// If completed is true, and daysSince has been
    	// defaulted to -1, we need to set it to 30 days
    	if (daysSince == -1)
    		daysSince = 30;
    }

    GetAndShowListedItems(mpUser,
    					  daysSince,
  					  sort,
  					  NULL,
  					  false,
  					  NULL,
  					  NULL,
  					  startingPage,
  				      rowsPerPage);
 
    *mpStream <<	"<p>"
    		  <<	mpMarketPlace->GetFooter();

    CleanUp();

    return;
}


void clseBayApp::ViewListedItemUserIdPassword(char* pUserId,
    							 bool completed,
    							 ItemListSortEnum sort,
  							 int daysSince,
   							 int startingPage,
  							 int rowsPerPage)
{
    // header
    *mpStream	<<	"<h2>Please Provide Your User ID and Password</h2>\n";

    // legal, rule
    *mpStream	<< "<font size=\"3\">" 
    		    << "eBay kindly requests that you submit your User ID and password to "
                << "view the e-mail addresses of high bidders. <I>Thank you!</I></font>\n";
              
    // form
    *mpStream	<<	"<form method=\"POST\" action=\""
    			<<	mpMarketPlace->GetCGIPath(PageViewListedItemsWithEmails)
    			<<	"eBayISAPI.dll\">\n"
    				"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
    				"VALUE=\"ViewListedItemsWithEmails\">\n"
    				"<input TYPE=HIDDEN NAME=\"userid\" "
    				"VALUE=\""
    			<<	pUserId
    			<<	"\">\n"
    				"<input TYPE=HIDDEN NAME=\"completed\" "
    				"VALUE=\""
    			<<	completed
    			<<	"\">\n"
    				"<input TYPE=HIDDEN NAME=\"sort\" "
    				"VALUE=\""
    			<<	sort
    			<<	"\">\n"
    				"<input TYPE=HIDDEN NAME=\"since\" "
    				"VALUE=\""
    			<<	daysSince
    			<<	"\">\n"
     				"<input TYPE=HIDDEN NAME=\"rows\" "
    				"VALUE=\""
    			<<	rowsPerPage
    			<<	"\">\n";

    *mpStream	<< "<table border=0 cellspacing=3 cellpadding=2>\n"
    			<<	"<tr><td>Your "
    			<<	mpMarketPlace->GetLoginPrompt()
    			<<	":</td>\n"
    				"<td><input type=\"text\" name=\"requester\" size=40></td></tr>\n"
    				"<tr><td>"
    			<<	mpMarketPlace->GetPasswordPrompt()
    			<<	":</td>\n"
    				"<td><input type=\"password\" name=\"pass\" size=40></td></tr>\n"
    				"</table>\n";

	*mpStream <<"<input type=\"checkbox\" name=acceptcookie value=1>Remember me\n";

	*mpStream << "<p><input type=\"submit\" value=\"Submit\"></p>\n"
    				"</form>\n";
    *mpStream   <<  "<p>"
    		    <<  "You must be a registered eBay user to view the high bidder emails. "
    			<<  "If you are not a registered eBay "
    			<<  "user, you can "
    				"<a href="
    				"\""
    		  <<	mpMarketPlace->GetHTMLPath()
    		  <<	"services/registration/register-by-country.html"
    				"\""
    				">"
    			<<  "register</A> now "
    			<<  "for <strong> free </strong>."
    			<<	flush;


}

void clseBayApp::ViewListedItemsWithEmails(CEBayISAPIExtension *pThis,
										   char* pRequestor,
										   char* pPass,
										   char* pUserId,
										   bool completed,
										   ItemListSortEnum sort,
										   int daysSince,
										   int startingPage,
										   int rowsPerPage)
{
    clsUser*	pUser;
    int			CurrentReq;
	char passHolder[65];

    SetUp();

    // Title
    *mpStream <<	"<html><head>"
    				"<title>"
    		  <<	mpMarketPlace->GetCurrentPartnerName()
    		  <<	" Seller List: "
    		  <<	pUserId
    		  <<	"</title>"
    				"</head>"
    		  <<	mpMarketPlace->GetHeader();

	if (mpUsers->GetUserValidation()->IsSoftValidated())
	{
		mpUser = mpUsers->GetAndCheckUser((char *) mpUsers->GetUserValidation()->GetValidatedUserId(), mpStream);
	}
	else
	{
	    // we can pass either a plaintext or crypted password
		mpUser = mpUsers->GetAndCheckUserAndPassword(pRequestor, pPass, mpStream, true, NULL,
			false, false, false, true);
	}

    if (!mpUser)
    {
    	*mpStream << "<p>"
    				<< mpMarketPlace->GetFooter();

    	CleanUp();
    	return;
    }

    CurrentReq = mpUser->GetReqEmailCount();

    if (CurrentReq >= EBAY_EMAILS_REQUEST_PER_DAY)
    {
    	*mpStream <<	ErrorMsgTooManyRequest
    			  <<	"<p>"
    			  <<	mpMarketPlace->GetFooter()
    			  <<	flush;

    	CleanUp();
    	return;
    }

    pUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
    if (!pUser)
    {
    	CleanUp();
    	return;
    }


    // People may have bookmarks with "completed" in them, 
    // but no "since". We have to account for them, plus
    // the default of "completed" to "false".
    //
    // Completed	since		:	Completed	since
    //	false		-1				false		-1
    //	false		!= -1			true		!= -1
    //	true		-1				true		30
    //	true		!= -1			true		!= -1
    //
    if (!completed)
    {
    	// If daysSince is -1, then we're fine, since it
    	// defaulted. If it's not, then they really DO 
    	// want completed
    	if (daysSince != -1)
    	{
    		completed	= true;
    	}
    }
    else
    {
    	// If completed is true, and daysSince has been
    	// defaulted to -1, we need to set it to 30 days
    	if (daysSince == -1)
    		daysSince = 30;
    }

	// get the encrypted password from the database
	memset(passHolder,0,sizeof(passHolder));
	strcpy(passHolder, mpUser->GetPassword());

    GetAndShowListedItems(pUser,
		daysSince,
		sort,
		NULL,
		true,
		pRequestor,
		passHolder,
		startingPage,
		rowsPerPage);

    delete pUser;

    *mpStream <<	"<p>"
    		  <<	mpMarketPlace->GetFooter();

    CleanUp();

    return;
}
