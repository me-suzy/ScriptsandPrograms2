/*	$Id: clsNoteAddress.cpp,v 1.4 1999/02/21 02:47:48 josh Exp $	*/
//
//	File:	clsNoteAddress.cpp
//
//	Class:	clsNoteAddress
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

#include "stdio.h"
#include <time.h>

clsNoteAddress::clsNoteAddress()
{
	mType	= eClsNoteAddressUnknown;
	mUser	= 0;
	mItem	= 0;
}

clsNoteAddress::~clsNoteAddress()
{
};

void clsNoteAddress::SetAddressUser(UserId id)
{
	mUser	= id;
	mItem	= 0;
	mType	= eClsNoteAddressUser;
}

void clsNoteAddress::SetAddressItem(UserId id)
{
	mItem	= id;
	mUser	= 0;
	mType	= eClsNoteAddressItem;

}

void clsNoteAddress::SetType(eClsNoteAddressType type)
{
	mType	= type;
}

eClsNoteAddressType clsNoteAddress::GetType()
{
	return	mType;
}

UserId clsNoteAddress::GetAddressUser()
{
	return	mUser;
}

UserId clsNoteAddress::GetAddressItem()
{
	return	mItem;
}

