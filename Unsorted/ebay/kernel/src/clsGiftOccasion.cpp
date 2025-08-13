/*	$Id: clsGiftOccasion.cpp,v 1.2 1998/12/06 05:32:09 josh Exp $	*/
//
//	File:		clsGiftOccasion.cc
//
// Class:	clsGiftOccasion
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Represents a gift occasion
//
// Modifications:
//				- 10/27/98 mila		- Created
//

#include "eBayKernel.h"

#include <stdio.h>

#include "clsGiftOccasions.h"

// A nice little macro
#define STRING_METHODS(variable)				\
char *clsGiftOccasion::Get##variable()			\
{												\
	return mp##variable;						\
}												\
void clsGiftOccasion::Set##variable(char *pNew)	\
{												\
	if (mp##variable)							\
		delete mp##variable;					\
	mp##variable = new char[strlen(pNew) + 1];	\
	strcpy(mp##variable, pNew);					\
	mDirty	= true;								\
	return;										\
}

#define INT_METHODS(variable)					\
int clsGiftOccasion::Get##variable()			\
{												\
	return m##variable;							\
}												\
void clsGiftOccasion::Set##variable(int newval)	\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	return;										\
} 

#define INT_METHODS_WITH_UPDATE(variable)		\
int clsGiftOccasion::Get##variable()			\
{												\
	return m##variable;							\
}												\
void clsGiftOccasion::Set##variable(int newval)	\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	UpdateGiftOccasion();						\
	return;										\
} 

#define LONG_METHODS(variable)					\
long clsGiftOccasion::Get##variable()			\
{												\
	return m##variable;							\
}												\
void clsGiftOccasion::Set##variable(long newval)\
{												\
	m##variable	= newval;						\
	mDirty	= true;								\
	return;										\
} 


void clsGiftOccasion::ClearAll()
{
	mId					= GiftOccasionUnknown;
	mpName				= (char *)0;
	mpGreeting			= (char *)0;
	mpHeader			= (char *)0;
	mpFooter			= (char *)0;
	mFlags				= (long)0;
}


clsGiftOccasion::clsGiftOccasion()
{
	ClearAll();
	return;
}

clsGiftOccasion::clsGiftOccasion(int id)
{
	ClearAll();
	mId	= id;
	return;
}

clsGiftOccasion::~clsGiftOccasion()
{
	delete	mpName;
	delete	mpGreeting;
	delete	mpHeader;
	delete	mpFooter;

	return;
}

// short constructor
clsGiftOccasion::clsGiftOccasion(MarketPlaceId marketplace,
								 int id,
								 char *pName,
								 char *pGreeting,
								 char *pHeader,
								 char *pFooter,
								 int flags)
{
	ClearAll();

	mMarketPlaceId	= marketplace;
	mId				= id;

	mpName			= new char[strlen(pName) + 1];
	strcpy(mpName, pName);

	mpGreeting		= new char[strlen(pGreeting) + 1];
	strcpy(mpGreeting, pGreeting);

	mpHeader		= new char[strlen(pHeader) + 1];
	strcpy(mpHeader, pHeader);

	mpFooter		= new char[strlen(pFooter) + 1];
	strcpy(mpFooter, pFooter);

	mFlags			= flags;
	mDirty			= false;

	return;
}

void clsGiftOccasion::Set(MarketPlaceId marketplace,
						  int id,
						  char *pName,
						  char *pGreeting,
						  char *pHeader,
						  char *pFooter,
						  int flags)
{
	mMarketPlaceId	= marketplace;
	mId				= id;
	mpName			= pName;
	mpGreeting		= pGreeting;
	mpHeader		= pHeader;
	mpFooter		= pFooter;
	mFlags			= flags;
}

void clsGiftOccasion::UpdateGiftOccasion()
{
	if (mDirty)
	{
		gApp->GetDatabase()->UpdateGiftOccasion(this);
	}

	mDirty = false;
}

//
// IsDirty
//
bool clsGiftOccasion::IsDirty()
{
	return mDirty;
}

//
// SetDirty
//
void clsGiftOccasion::SetDirty(bool dirty)
{
	mDirty	= dirty;

	return;
}

INT_METHODS(MarketPlaceId);			// marketplace id
INT_METHODS(Id);					// occasion id
STRING_METHODS(Name);				// name
STRING_METHODS(Greeting);			// greeting
STRING_METHODS(Header);				// header image filename
STRING_METHODS(Footer);				// footer image filename
INT_METHODS(Flags);					// flags

//
// Functions to access the bit flags for the occasion.
//

// 
// Retrieve a single occasion flag.
//
bool clsGiftOccasion::GetOneGiftOccasionFlag(GiftOccasionFlag bit)
{
	int flags = GetFlags();
	return ( (flags & bit) > 0);
}

//
// Set one or more occasion flags, and indicate whether to toggle them
// on or off (the other flags are untouched). 
// You can logical-or the bit masks together in mask.
//
int clsGiftOccasion::SetSomeGiftOccasionFlags(bool on, int mask)
{
	long flags;
	long oldFlags = GetFlags();

	if (on)
		flags = oldFlags | mask;
	else 
		flags = oldFlags & ~mask;

	SetFlags(flags);

	return oldFlags;
}

// 
// Return true if the occasion's active flag is set.
//
bool clsGiftOccasion::IsActive()
{
	return GetOneGiftOccasionFlag(GiftOccasionFlagActive);
}

// 
// Get all of the user flags at once.
//
int clsGiftOccasion::GetAllGiftOccasionFlags()
{
	return gApp->GetDatabase()->GetGiftOccasionFlags(mMarketPlaceId, mId);
}

//
// Set all of the user flags at once.
//
int clsGiftOccasion::SetAllGiftOccasionFlags(int flags)
{
	int oldFlags = GetAllGiftOccasionFlags();

	gApp->GetDatabase()->SetGiftOccasionFlags(mMarketPlaceId, mId, flags);

	return oldFlags;
}

