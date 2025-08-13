/*	$Id: clsNoteAddress.h,v 1.4 1999/02/21 02:46:46 josh Exp $	*/
//
//	File:	clsNoteAddress.h
//
//	Class:	clsNoteAddress
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	clsNotes describes the addressing scheme for a note.
//	Right now, it's a single user or item, but encapsulating
//	it here will one day allow CC: lists, etc.
// 
//
// Modifications:
//				- 04/08/97 michael	- Created
//
#ifndef CLSNOTEADDRESS_INCLUDED

#include "eBayTypes.h"

//
// Class forward
//
class clsUser;
class clsItem;

// 
// This enum describes the "type" of address we've
// got here.
//
typedef enum
{
	eClsNoteAddressUnknown	= 0,
	eClsNoteAddressUser		= 1,
	eClsNoteAddressItem		= 2
} eClsNoteAddressType;

class clsNoteAddress
{
	public:

		// CTOR, DTOR
		clsNoteAddress();
		~clsNoteAddress();

		// Getter, Setter
		void SetType(eClsNoteAddressType type);
		void SetAddressUser(UserId user);
		void SetAddressItem(ItemId item);
		void SetAddressUserFeedbackScore(int score);
		void SetAddressUserIdLastChanged(time_t when);
		void SetAddressUserUserId(char *pUserId);
		void SetAddressUserEmail(char *pEmail);

		eClsNoteAddressType	GetType();
		UserId GetAddressUser();
		ItemId GetAddressItem();
		int GetAddressUserFeedbackScore();
		time_t GetAddressUserUserIdLastChanged();
		char *GetAddressUserUserId();
		char *GetAddressUserEmail();
		

	private:

		eClsNoteAddressType	mType;
		UserId				mUser;
		ItemId				mItem;
};

#define CLSNOTEADDRESS_INCLUDED
#endif /* CLSNOTEADDRESS_INCLUDED */
