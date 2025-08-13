/*	$Id: clsLoadTestDataApp.cpp,v 1.2 1999/02/21 02:23:26 josh Exp $	*/
//
//	File:	clsLoadTestDataApp.cc
//
//	Class:	clsLoadTestDataApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 04/06/97 michael	- Created
//				- 07/16/97 tini		- changed to include different leaf categories
//						super featured, featured and hot items
//						hot items are by brute force, not with real bids this time
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsLoadTestDataApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsItems.h"
#include "clsItem.h"

#include <stdio.h>
#include <errno.h>
#include <time.h>

#ifdef _WIN32
#define	srand48(arg)	srand(arg)
#define	lrand48			rand
#endif


clsLoadTestDataApp::clsLoadTestDataApp(unsigned char *pRequest)
{
	mpDatabase		= NULL;
	mpMarketPlaces	= NULL;
	mpMarketPlace	= NULL;
	mpItems			= NULL;
	mpUsers			= NULL;

	return;
}


clsLoadTestDataApp::~clsLoadTestDataApp()
{
	return;
}

// Important Defines

const char *Description = 
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>"
"Test Item %d Description <br>";

void clsLoadTestDataApp::Run()
{
	int			iUser;
	int			iHiBidder;

	clsUser		*pUser;

	int			id;
	char		userId[32];
	time_t		lastModified;

	// total items needed to add
	int			itemCount	= 5000;

	// number of featured items per category (must be <= than itemCount)
	int			featuredCount = 20;
	bool		isFeatured;

	// number of boldfaced title in items per category (not including featuredCount)
	int			boldTitleCount = 20;
	bool		isBold;

	// number of super featured items per category (must be <= itemCount)
	int			superFeaturedCount = 10;
	bool		isSuperFeatured;

	// number of hot items per category
	int			hotCount = 10;
	bool		isHot;

	int			iItem;
	int			iItemSFF;	// super featured + featured total
	int			iItemSFFB;	// super featured + featured + bold total
	int			iItemSFFBH;	// iItemSFFB + hot
	int			iDurationHours;

	char		title[40];
	char		description[512];
	char		*pTitle;
	char		*pDescription;
	time_t		startTime;
	time_t		endTime;
	double		price;
	int			randPrice;


	int			numberLeave;

	const int	FourteenDays = 14 * 24 * 60 * 60; // 14 days
	const int	SevenDays    =  7 * 24 * 60 * 60; // 7 days

	clsItem		*pItem;
	CategoryVector				vCategories;
	CategoryVector::iterator	iCat;

	if (!mpMarketPlaces)	
		mpMarketPlaces	= gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpUsers)
		mpUsers		= mpMarketPlace->GetUsers();

	if (!mpCategories)
		mpCategories = mpMarketPlace->GetCategories();

	if (!mpItems)
		mpItems		= mpMarketPlace->GetItems();


	const int   UserIdStart = 1;

	// number of users as seed (divisible by itemCount)
	int			userCount = 100;

	// We generate our own userids because, well, we want 
	// to  
	// starting user id number
	id			= UserIdStart + 3;
/*
	// 
	// Let's add some users first
	//
	for	(iUser = 0;
		 iUser < userCount;
		 iUser++)
	{
		sprintf(userId, "testuser%d@ebay.com", id);
		lastModified	= time(0);

		pUser	= new clsUser(mpMarketPlace->GetId(),
							  id,
							  userId,
							  UserConfirmed,
							  "password",
							  "0000",
							  lastModified,
							  "test.ebay.com",
							  "Bulk Loaded Test User",
							  "eBay, Inc",
							  "2005 Hamilton Ave",
							  "San Jose",
							  "california",
							  "95030",
							  "usa",
							  "(555)555-1212",
							  "(555)555-1212",
							  "(555)555-1212",
							  lastModified,
							  userId,
							  0,
							  false,
							  false,
							  "u",
							  99,
							  99,
							  99,
							  99);

		mpUsers->AddUser(pUser);

		delete	pUser;

		id++;
	}
*/
	// Get leaf Categories
	mpCategories->Leaves(&vCategories);
	numberLeave = vCategories.size() - 1;

	iItemSFF = featuredCount + superFeaturedCount;
	iItemSFFB = iItemSFF + boldTitleCount;
	iItemSFFBH = iItemSFFB + hotCount;

	iUser = UserIdStart;
	iHiBidder = UserIdStart + 1;
	iDurationHours	= 1;

	// set seed for renadom generator
	srand48( (unsigned)time( NULL ) );

	// we want to add 70,000 items to the system
	for (iItem = 0; iItem < itemCount; iItem++)
	{
		// pick a leave category
		iCat = vCategories.begin() + (lrand48() % numberLeave);

		// pick a user
		iUser = UserIdStart + (lrand48() % userCount);

		// pick a hibidder
		iHiBidder  = UserIdStart + (lrand48() % userCount);

		// pick starting and ending times
		startTime	= time(0) + (lrand48() % FourteenDays) - SevenDays;
		endTime		= startTime + (lrand48() % SevenDays);

		// One super feature per 2000 items
		isSuperFeatured = ((lrand48() % 2000) == 1);

		if (!isSuperFeatured)
		{
			// featured? but not superfeatured
			isFeatured = ((lrand48() % 1000) == 1);
		}
		else
		{
			isFeatured = false;
		}

		// bold title? not including featured or superfeatured
		isBold = ((lrand48() % 1000) == 1);

		// Hot item
		isHot = ((lrand48() % 2000) == 1);

		sprintf(title, "FIRE SALE!!!! L@@K! Test Item %d", iItem);
		sprintf(description, Description,
				iItem, iItem, iItem, iItem, iItem,
				iItem, iItem, iItem, iItem, iItem);

		pTitle	= new char[strlen(title) + 1];
		strcpy(pTitle, title);
		pDescription	= new char[strlen(description) + 1];
		strcpy(pDescription, description);

		randPrice = lrand48() % 1000000;
		price	  = randPrice ? randPrice / 100.00 : 1.00;

		pItem	= new clsItem(mpMarketPlace->GetId(),
						  0,
						  AuctionChinese,
						  title,
						  description,
						  "The land of the Test items",
						  iUser,
						  iUser,
						  (*iCat)->GetId(),
						  1,
						  startTime,
						  endTime,
						  0,
						  price,
						  0,
						  isFeatured,
						  isSuperFeatured,
						  isBold,
						  false,
						  true,
						  NULL,
						  NULL,
						  startTime);

		mpItems->AddItem(pItem);

		if (isHot)
		{
			pItem->SetBidCount(30 + lrand48() % 100);
		};

		randPrice = lrand48() % 100;
		pItem->SetPrice(randPrice + price);
		pItem->SetHighBidder(iHiBidder);

		if (isHot || (lrand48() % 10 == 1))
		{
			pItem->UpdateItem();
		}

		delete	pItem;

	}

	return;
}

static clsLoadTestDataApp *pTestApp = NULL;

int main()
{

	if (!pTestApp)
	{
		pTestApp	= new clsLoadTestDataApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}

