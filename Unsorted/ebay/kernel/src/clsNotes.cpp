/*	$Id: clsNotes.cpp,v 1.4 1999/02/21 02:47:49 josh Exp $	*/
//
//	File:	clsNotes.cpp
//
//	Class:	clsNotes
//
//	Author:	michael (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 07/05/98 michael	- Created
//
#include "eBayKernel.h"
#include "clsNotes.h"

#include "stdio.h"
#include <time.h>

//
// DTOR
//
clsNotes::~clsNotes()
{
	Reset();
}


//
// Reset
//
void clsNotes::Reset()
{
	clsNoteList				*pNoteList;
	clsNoteList::iterator	i;

	pNoteList	= GetNotes();

	for (i = pNoteList->begin();
		 i != pNoteList->end();
		 i++)
	{
		delete	(*i);
	}

	pNoteList->erase(pNoteList->begin(), pNoteList->end());

	mAddressFilter	= 0;
	mAboutFilter	= 0;
	mCategoryFilter	= 0;
	mpSupportUser	= NULL;

}

// 
// Setters and Getters
//
unsigned int clsNotes::GetAddressFilter()
{
	return mAddressFilter;
}

void clsNotes::SetAddressFilter(unsigned int type)
{
	mAddressFilter	= type;
}

unsigned int clsNotes::GetAboutFilter()
{
	return mAboutFilter;
}

void clsNotes::SetAboutFilter(unsigned int type)
{
	mAboutFilter	= type;
}

unsigned int clsNotes::GetCategoryFilter()
{
	return mCategoryFilter;
}

void clsNotes::SetCategoryFilter(unsigned int type)
{
	mCategoryFilter	= type;
}


clsNoteAddressList *clsNotes::GetFrom()
{
	return	mpFrom;
}

void clsNotes::SetFrom(clsNoteAddressList *pIt)
{
	mpFrom	= pIt;
};

clsNoteAddressList *clsNotes::GetTo()
{
	return	mpTo;
}

void clsNotes::SetTo(clsNoteAddressList *pIt)
{
	mpTo	= pIt;
};


clsNoteAddressList *clsNotes::GetCC()
{
	return	mpCC;
}

void clsNotes::SetCC(clsNoteAddressList *pIt)
{
	mpCC	= pIt;
};

clsNoteAddressList *clsNotes::GetAbout()
{
	return	mpAbout;
}

void clsNotes::SetAbout(clsNoteAddressList *pIt)
{
	mpAbout	= pIt;
};

clsNoteList *clsNotes::GetNotes()
{
	return &mlNotes;
}

//
// Load
//
//	This is the meat. Basically, all we do is pass the query to
//	Oracle. 
//
void clsNotes::Load()
{
	gApp->GetDatabase()->LoadNotes(mAddressFilter, 
								  mAboutFilter,
								  mCategoryFilter,
								  mpFrom,
								  mpTo,
								  mpCC,
								  mpAbout,
								  &mlNotes);

	return;
}

//
// AddNote
//
//	Right now, this just reflects the call to the
//	database. Might do something else some day
//
void clsNotes::AddNote(clsNote *pNote)
{
	gApp->GetDatabase()->AddNote(pNote);
}

//
// GetSupportUser
//
clsUser *clsNotes::GetSupportUser()
{
	if (mpSupportUser != NULL)
		return	mpSupportUser;

	mpSupportUser	= 
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUser("support");

	return	mpSupportUser;
}

//
// IsSupportUser
//
bool clsNotes::IsSupportUser(UserId id)
{
	if (id == GetSupportUser()->GetId())
		return true;
	else
		return false;
}

