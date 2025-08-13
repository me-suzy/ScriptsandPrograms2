/*	$Id: clsBidEngine.cpp,v 1.4 1998/08/25 03:20:37 josh Exp $	*/
//
//	File:	clsBidEngine.cpp
//
//	Class:	clsBidEngine
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Base BidEngine functionality
//
// Modifications:
//				- 02/10/97 michael	- Created
//
#pragma warning( disable : 4786 )

#include "eBayKernel.h"
#include "clsAnnouncements.h"

#include "hash_map.h"

//
// CTOR
//
clsBidEngine::clsBidEngine(clsItem *pItem)
{
	mpItem			= pItem;
	//
	// Get the item's marketplace object
	//
	// *** NOTE ***
	//	This code assumes the the item is in the
	//	CURRENT marketplace. This needs to be 
	//	changed when we allow users to bid across
	//	marketplaces
	// *** NOTE ***
	//
	mpMarketPlace	= gApp->GetMarketPlaces()->GetCurrentMarketPlace();
	mpUsers			= mpMarketPlace->GetUsers();
	mpAnnouncements = mpMarketPlace->GetAnnouncements();
	mpUser = NULL;
	return;
}

//
// DTOR
//
clsBidEngine::~clsBidEngine()
{
	mpItem			= NULL;
	mpMarketPlace	= NULL;
	mpAnnouncements = NULL;
	mpUsers = NULL;
	mpUser = NULL;
}

