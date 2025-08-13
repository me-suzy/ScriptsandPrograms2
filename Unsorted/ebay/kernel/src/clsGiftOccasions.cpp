/*	$Id: clsGiftOccasions.cpp,v 1.3 1999/03/07 08:16:52 josh Exp $	*/
//
//	File:		clsGiftOccasions.cc
//
// Class:	clsGiftOccasions
//
//	Author:	Mils Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a collection of gift occasions
//
// Modifications:
//				- 10/27/98 mila		- Created
//
#include "eBayKernel.h"

#include <stdio.h>
#include <time.h>

#include "clsGiftOccasions.h"

//
// Constructor
//
clsGiftOccasions::clsGiftOccasions()
{
	return;
}

//
// Destructor
//
clsGiftOccasions::~clsGiftOccasions()
{		
	return;
}

//
// GetActiveGiftOccasions (with flags > 2)
//
void clsGiftOccasions::GetActiveGiftOccasions(MarketPlaceId marketplace,
							GiftOccasionVector *pvOccasions)
{
	gApp->GetDatabase()->GetActiveGiftOccasions(marketplace, pvOccasions);
}

//
// GetGiftOccasion (by id)
//	Just ask the Database to do it!
//	NOTE: calling method must free allocated here.
//
clsGiftOccasion *clsGiftOccasions::GetGiftOccasion(MarketPlaceId marketplace,
												   int id)
{
	bool gotIt;
	clsGiftOccasion	*pOccasion;
	
	pOccasion = new clsGiftOccasion();

	gotIt = gApp->GetDatabase()->GetGiftOccasion(marketplace, id, pOccasion);
	if (!gotIt)
	{
		delete pOccasion;
		return NULL;
	}
	else
		return pOccasion;
}

//
// AddGiftOccasion
//
void clsGiftOccasions::AddGiftOccasion(clsGiftOccasion *pOccasion)
{
	int	id;

	// Let's see if we need an id
	if (pOccasion->GetId() == 0)
	{
		id = gApp->GetDatabase()->GetNextGiftOccasionId();
		pOccasion->SetId(id);
	}

	gApp->GetDatabase()->AddGiftOccasion(pOccasion);

	return;
}

//
// UpdateGiftOccasion
//
void clsGiftOccasions::UpdateGiftOccasion(clsGiftOccasion *pOccasion)
{
	// Need a try/catch around the database call
	if (pOccasion->IsDirty())
	{
		gApp->GetDatabase()->UpdateGiftOccasion(pOccasion);
	}

	return;
}

//
// DeleteUser
//
void clsGiftOccasions::DeleteGiftOccasion(clsGiftOccasion *pOccasion)
{
	gApp->GetDatabase()->DeleteGiftOccasion(pOccasion->GetMarketPlaceId(),
											pOccasion->GetId());

	return;
}

//
// DeleteAllGiftOccasions
//
void clsGiftOccasions::DeleteAllGiftOccasions(MarketPlaceId marketplace)
{
	gApp->GetDatabase()->DeleteAllGiftOccasions(marketplace);

	return;
}

