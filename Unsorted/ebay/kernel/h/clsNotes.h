/*	$Id: clsNotes.h,v 1.4 1999/02/21 02:46:48 josh Exp $	*/
//
//	File:	clsNotes.h
//
//	Class:	clsNotes
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	clsNotes describes a collection of notes to, from, and 
//	about a user (yes, I agnoized over the name. clsMessages
//	will be used some day for localization, and clsMailbox
//	had other connotations).
//
//	An instance of clsNotes is also needed to "Send" notes,
//	but this is hierchial formality at this point, more than
//	anything else. 
// 
//
// Modifications:
//				- 04/08/97 michael	- Created
//
#ifndef CLSNOTES_INCLUDED

#include "eBayTypes.h"
#include "clsNote.h"
#include "clsNoteAddressList.h"

//
// Class forward
//
class clsUser;
class clsItem;

class clsNotes
{
	public:

		// 
		// This enum describes the "type" of notes we're
		// handling. It's a bitmask, so you can add them
		// together if you want.
		//
		enum
		{
			eClsNotesFilterNotesTo		= 1,
			eClsNotesFilterNotesFrom	= 2,
			eClsNotesFilterNotesCC		= 4,
			eClsNotesFilterNotesAbout	= 8
		};

		// 
		// CTOR, DTOR
		//
		clsNotes() :
			mAddressFilter(0),
			mAboutFilter(0),
			mCategoryFilter(0),
			mpFrom(NULL),
			mpTo(NULL),
			mpCC(NULL),
			mpAbout(NULL),
			mpSupportUser(NULL)
		{
			;
		}

		~clsNotes();

		//
		// Reset
		//	This method resets everything to a fresh state. Right now,
		//	it's used so we can have a persistent clsNotes object
		//
		void Reset();


		//
		// Setters and Getters. 
		// 
		// Note that these don't actually _do_ anything
		// except set member variables. To get them to 
		// _do_ something, you have to call Load();
		//
		unsigned int		GetAddressFilter();
		unsigned int		GetAboutFilter();
		unsigned int		GetCategoryFilter();

		clsNoteAddressList	*GetFrom();
		clsNoteAddressList	*GetTo();
		clsNoteAddressList	*GetCC();
		clsNoteAddressList	*GetAbout();

		void SetAddressFilter(unsigned int filterType);
		void SetAboutFilter(unsigned int aboutType);
		void SetCategoryFilter(unsigned int categoryFilter);
		void SetFrom(clsNoteAddressList *pFrom);
		void SetTo(clsNoteAddressList *pTo);
		void SetCC(clsNoteAddressList *pTo);
		void SetAbout(clsNoteAddressList *pUser);

		//
		// Load
		//	Loads up the right notes.
		//
		void Load();

		//
		// GetNotes
		//	Return the address of a list of qualifying notes.
		//
		clsNoteList		*GetNotes();

		//
		// AddNote
		//	"Send", or "add" a new note
		//
		void			AddNote(clsNote *pNote);

		//
		// GetSupportUser
		//
		// The "support" user is the "to" for all notes generated
		// by admin functions, so here it is ;-)
		//
		clsUser			*GetSupportUser();

		//
		// IsSupportUser
		//
		// This function tests to see if the passed userid is
		// the "support" user. 
		//
		bool			IsSupportUser(UserId id);



	private:
		clsUser				*mpSupportUser;

		unsigned int		mAddressFilter;
		unsigned int		mAboutFilter;
		unsigned int		mCategoryFilter;
		clsNoteAddressList	*mpFrom;
		clsNoteAddressList	*mpTo;
		clsNoteAddressList	*mpCC;
		clsNoteAddressList	*mpAbout;

		clsNoteList			mlNotes;
};

#define CLSNOTES_INCLUDED
#endif /* CLSNOTES_INCLUDED */
