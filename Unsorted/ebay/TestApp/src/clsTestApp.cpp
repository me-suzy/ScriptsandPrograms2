/*	$Id: clsTestApp.cpp,v 1.2 1999/02/21 02:24:41 josh Exp $	*/
//
//	File:		clsTestApp.cc
//
// Class:	clsTestApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsTestApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsItems.h"
#include "clsItem.h"

#include "WINDOWS.H"
#include <time.h>

// #include <vector.h>

clsTestApp::clsTestApp(unsigned char *pRequest)
{
	mpDatabase		= (clsDatabase *)0;
	mpMarketPlaces	= (clsMarketPlaces *)0;
	mpMarketPlace	= (clsMarketPlace *)0;
	mpItems			= (clsItems *)0;

	return;
}


clsTestApp::~clsTestApp()
{
	return;
}


void clsTestApp::Run()
{
	clsItem	*pItem, *pItem2;
	EWHERE('a');EDEBUG('a', "Hello, World!\n");

	if (!mpDatabase)
		mpDatabase	= gApp->GetDatabase();

	if (!mpMarketPlaces)
		mpMarketPlaces	= gApp->GetMarketPlaces();

	if (!mpMarketPlace)
		mpMarketPlace	= mpMarketPlaces->GetCurrentMarketPlace();

	if (!mpItems)
		mpItems			= mpMarketPlace->GetItems();

	// mpDatabase->ClearAllItems();

	pItem	= new clsItem(0,				// Marketplace Id
						  0,				// Id
						  AuctionChinese,	// Auction type
						  "Test Item",		// Title
						  "Warp Cores",		// Description
						  "Starbase 11",	// Location
						  1,				// Seller
						  1,				// Owner
						  1,				// Category
						  1,				// Quantity
						  time(0), time(0), 0,// Start, End, Status
						  15.00, 200.00,	// Price, reserver
						  0, 0, 0, 1, 0, NULL, NULL, 0);

	mpDatabase->AddItem(pItem);

	pItem2 = mpItems->GetItem(6, true);
}

static clsTestApp *pTestApp = NULL;

int main()
{
	if (!pTestApp)
	{
		pTestApp	= new clsTestApp(0);
	}

	pTestApp->InitShell();
	pTestApp->Run();

	return 0;
}
