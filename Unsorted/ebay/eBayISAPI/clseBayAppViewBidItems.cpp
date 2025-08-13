/*	$Id: clseBayAppViewBidItems.cpp,v 1.11.92.1.76.2 1999/08/05 20:42:23 nsacco Exp $	*/
//
//	File:	clseBayAppViewBidItems.cc
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
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "clsUserIdWidget.h"
#include "clsUser.h"
#include "hash_map.h"
#include "clseBayTimeWidget.h"	// petra


//
// GetAndShowListedItems
//
//	This routine actually retrieves and emits
//	the items a user has bid on. It's a seperate
//	method so that it can be called independantly
//	of ViewBidItems. The latter emits a <TITLE>
//	and other goodies
//
void clseBayApp::GetAndShowBidItems(clsUser *pUser,
									bool showCompleted,
									ItemListSortEnum sortOrder,
									bool allItems,
									char *pCmd, 
									int startingPage, 
									int rowsPerPage)
{
    // This is a vector of the items
    ItemList					itemList;
	char *cleanTitle = NULL;

    // And an iterator for it
    ItemList::iterator			i;

    // The command we'll put in links
    char						*pDLLCmd;

    // Time Fields
    time_t						curTime;
// petra    time_t						startTime;
// petra    time_t						endTime;
// petra    struct tm					*pTheTime;

// petra    char						cStartTime[32];
// petra    char						cEndTime[32];

    char						*pAllFlag;
    // Added by Charles
    clsUserIdWidget				*pUserIdWidget;

	bool						bHasCookie = false;

	double						maxAmount;

// pagination stuff
// 	int startingItem, currentItem, lastItem;
// 	int		Limit = 5, numberOfItems, NumberOfPages, j, CurrentPage, numOfColumns;
// 	char	PageNumber[10];

	// for pagination
	int startingItem, currentItem, lastItem;

	// Let's get the items listed by this user
//	mpDatabase->Begin();
//	mpDatabase->SetSerializable();

    if (allItems)
    	pUser->GetBidItems(&itemList, showCompleted ? 1000 : -1, false, sortOrder);
    else
    	pUser->GetHighBidItems(&itemList, showCompleted, sortOrder);

//	mpDatabase->End();
    // Well, THAT was nice. Let's start emitting
    // stuff.

    if (showCompleted)
    	curTime	= time(0);

    // Prepare the stream
    mpStream->setf(ios::fixed, ios::floatfield);
    mpStream->setf(ios::showpoint, 1);
    mpStream->precision(2);

    // Added by vicki
    pUserIdWidget	= new clsUserIdWidget(mpMarketPlace, this);
    pUserIdWidget->SetUser(pUser);
    pUserIdWidget->SetShowUserStatus(false);




    if (showCompleted)
    {
    	*mpStream <<	"<h2>"
    					"Current and recent auctions bid on by ";
    	pUserIdWidget->EmitHTML(mpStream);


    	*mpStream <<	"</h2>"
    					"Includes ongoing auctions and auctions which ended "
    					"in the last 30 days. Bold price means at least "
    					"one bid has been received."
    					"<p>"
    					"\n";
    }
    else
    {
    	*mpStream <<	"<h2>"
    					"Current auctions bid on by ";
    	
    	pUserIdWidget->EmitHTML(mpStream);

    	*mpStream 		  <<	"</h2>"
    					"Includes ongoing auctions only. Bold price "
    					"means at least one bid has been received. "
    					"<p>"
    					"\n";
    }

    if (allItems)
    {
    	*mpStream <<	"This list includes <b>all</b> auctions ";

    	pUserIdWidget->EmitHTML(mpStream);	
    	
    	*mpStream <<	" has bid on, and in some cases may no longer be the high bidder."
    					"<p>";
    }

    *mpStream <<	"<p>\n";

    *mpStream <<		"You can click on the <strong>Start Time</strong>, "
    					"<strong>End Time</strong>, or "
    					"<strong>Price</strong> links to sort "
    					"the list."
    					"<p>";


    // Table Heading
	*mpStream <<	"<table border=1>\n";
    if (pCmd)
    	pDLLCmd	= pCmd;
    else
    	pDLLCmd	= "ViewBidItems";

    // "All" Flag
    if (allItems)
    	pAllFlag	= "1";
    else
    	pAllFlag	= "0";

 	// insert the pagination thingie
	InsertPaginationControl(&itemList, pUser, true, &currentItem, &lastItem, 
					&startingItem, 0/*include*/, startingPage, rowsPerPage, 
					-100 /*daysSince*/, sortOrder, pDLLCmd, NULL/*pRequester*/, NULL/*pPass*/,
					pAllFlag, showCompleted);

  	*mpStream <<	""
    				"<tr>"
    				"<th>"
    				"<A href=\""
    		  <<	mpMarketPlace->GetCGIPath(PageViewBidItems)
    		  <<	"eBayISAPI.dll?"
    		  <<	pDLLCmd
    		  <<	"&userid="
    		  <<	pUser->GetUserId()
    		  <<	"&completed="
    		  <<	showCompleted
    		  <<	"&sort="
    		  <<	SortItemsById
    		  <<	"&all="
    		  <<	pAllFlag
  		  <<	"&page="
  		  <<	startingPage
  		  <<	"&rows="
  		  <<	rowsPerPage
    		  <<	"\""
    				">"
    				"Item"
    				"</a>"
    				"</th>"
    				"<th>"
    				"<A href=\""
    		  <<	mpMarketPlace->GetCGIPath(PageViewBidItems)
    		  <<	"eBayISAPI.dll?"
    		  <<	pDLLCmd
    		  <<	"&userid="
    		  <<	pUser->GetUserId()
    		  <<	"&completed="
    		  <<	showCompleted
    		  <<	"&sort="
    		  <<	SortItemsByStartTime
    		  <<	"&all="
    		  <<	pAllFlag
  		  <<	"&page="
  		  <<	startingPage
  		  <<	"&rows="
  		  <<	rowsPerPage
    		  <<	"\""
    				">"	
    				"Start"
    				"</a>"
    				"</th>"
    				"<th>"
    				"<A href=\""
    		  <<	mpMarketPlace->GetCGIPath(PageViewBidItems)
    		  <<	"eBayISAPI.dll?"
    		  <<	pDLLCmd
    		  <<	"&userid="
    		  <<	pUser->GetUserId()
    		  <<	"&completed="
    		  <<	showCompleted
    		  <<	"&sort="
    		  <<	SortItemsByEndTime
    		  <<	"&all="
    		  <<	pAllFlag
  		  <<	"&page="
  		  <<	startingPage
  		  <<	"&rows="
  		  <<	rowsPerPage
    		  <<	"\""
    				">"
    				"End"
    				"</a>"
    				"</th>"
    				"<th>"
    				"<A href=\""
    		  <<	mpMarketPlace->GetCGIPath(PageViewBidItems)
    		  <<	"eBayISAPI.dll?"
    		  <<	pDLLCmd
    		  <<	"&userid="
    		  <<	pUser->GetUserId()
    		  <<	"&completed="
    		  <<	showCompleted
    		  <<	"&sort="
    		  <<	SortItemsByPrice
    		  <<	"&all="
    		  <<	pAllFlag
  		  <<	"&page="
  		  <<	startingPage
  		  <<	"&rows="
  		  <<	rowsPerPage
    		  <<	"\""
    				">"
    				"Price"
    				"</a>"
    				"</th>"
    				"<th>Title</th>"
    				"<th>High Bidder</th>"
    				"</tr>\n";

    // If we didn't get any items, then, well,
    // we're done.
    if (itemList.size() < 1)
    {
    	delete pUserIdWidget;
    	return;
    }

	// check for the adult cookie
	bHasCookie = HasAdultCookie();

	clsCurrencyWidget currencyWidget(mpMarketPlace, Currency_USD, 0); // We'll set this when we need this.

    // Now, iterate over the items
    for (i = itemList.begin();
    	 i != itemList.end();
    	 i++)
    {
    	
  	// pagination - if startingPage == 0, then we show them all, else make sure we're in range
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
// petra    	startTime	= (*i).mpItem->GetStartTime();
// petra    	pTheTime	= localtime(&startTime);
// petra	strftime(cStartTime, sizeof(cStartTime), 
// petra    			"%m/%d/%y",
// petra    			pTheTime);
// petra
// petra    	endTime		= (*i).mpItem->GetEndTime();
// petra    	pTheTime	= localtime(&endTime);
// petra    	strftime(cEndTime, sizeof(cEndTime),
// petra    			"%m/%d/%y %H:%M:%S",
// petra    			pTheTime);

		clseBayTimeWidget timeWidget (mpMarketPlace, 0, 0);		// petra
    	
    	// And Show them
    	*mpStream <<	"<td>";
		timeWidget.SetDateTimeFormat (1, -1);				// petra
		timeWidget.SetTime ( (*i).mpItem->GetStartTime() );	// petra
		timeWidget.EmitHTML (mpStream);						// petra
// petra		<<	cStartTime
    	*mpStream <<	"</td>"
    					"<td>";
		timeWidget.SetDateTimeFormat (1, 2);				// petra
		timeWidget.SetTime ( (*i).mpItem->GetEndTime() );	// petra
		timeWidget.EmitHTML (mpStream);						// petra
// petra    			  <<	cEndTime
    	*mpStream <<	"</td>";

    	// Price (Bold if there are bids)
    	*mpStream <<	"<td>";

		maxAmount = mpMarketPlace->GetMaxAmount((*i).mpItem->GetCurrencyId());

    	if ((*i).mpItem->GetPrice() > maxAmount  ||
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
    	*mpStream		<<	"<td>";
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
    	*mpStream <<	"<td>";

    	if ((*i).mpItem->GetPrivate())
    	{
    		*mpStream <<	"Private Auction";
    	}
    	else if ((*i).mpItem->GetQuantity() > 1)
    	{
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
    	}
    	else if ((*i).mpItem->GetBidCount() > 0 &&
    			 (*i).mpItem->GetPrice() > 0)
    	{
    		pUserIdWidget->SetUserInfo((*i).mpItem->GetHighBidderUserId(), 
									(*i).mpItem->GetHighBidderEmail(),
    									UserStateEnum(0), false, 0);
			
			pUserIdWidget->SetShowUserStatus(false);
    		pUserIdWidget->SetShowFeedback(false);
     		pUserIdWidget->SetShowStar(false);
   			pUserIdWidget->EmitHTML(mpStream);

    		if (showCompleted)
    		{
    			if ((*i).mpItem->GetEndTime() < curTime)
    			{
    				*mpStream	<< "(*)";
    			}
    		}
    	}
    	else
    	{
    		*mpStream <<	"No Bids Yet";
    	}

    	*mpStream <<	"</td>"
    					"</tr>"
    					"\n";
  	} else { 
  	} // end pagination if

  	currentItem++;
  	
} // for loop

 	// insert the pagination thingie
	InsertPaginationControl(&itemList, pUser, false, &currentItem, &lastItem, 
					&startingItem, 0/*include*/, startingPage, rowsPerPage, 
					-100 /*daysSince*/, sortOrder, pDLLCmd, NULL/*pRequester*/, NULL/*pPass*/,
					pAllFlag, showCompleted);

	*mpStream <<	"</table>";

    delete pUserIdWidget;
    // Clean up
    for (i = itemList.begin();
    	 i != itemList.end();
    	 i++)
    {
    	delete	(*i).mpItem;
    }

    itemList.erase(itemList.begin(), 
    				 itemList.end());

    return;
}


//
// ViewBidItems
//
void clseBayApp::ViewBidItems(CEBayISAPIExtension *pThis,
    							 char *pUserId,
    							 bool completed,
    							 ItemListSortEnum sort,
  							 bool allItems,
  							 int startingPage,
  							 int rowsPerPage)
{

    SetUp();

    // Title
    *mpStream <<	"<html><head>"
    				"<title>"
    		  <<	mpMarketPlace->GetCurrentPartnerName()
    		  <<	" Bidder List: "
    		  <<	pUserId
    		  <<	"</title>"
    				"</head>"
    		  <<	mpMarketPlace->GetHeader();

    mpUser = mpUsers->GetAndCheckUser(pUserId, mpStream);
    if (!mpUser)
    {
    	return;
    }

    GetAndShowBidItems(mpUser,
    				   completed,
    				   sort,
  				   allItems,
  				   NULL,		// pCmd
  				   startingPage,
  				   rowsPerPage);

    *mpStream <<	"</table>"
    		  <<    "<p>"
    		  <<	mpMarketPlace->GetFooter();

    CleanUp();

    return;
}


