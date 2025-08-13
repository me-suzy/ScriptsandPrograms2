/*	$Id: clsNoteAddressList.h,v 1.3 1999/02/21 02:46:47 josh Exp $	*/
//
//	File:	clsNoteAddressList.h
//
//	Class:	clsNoteAddress
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	A list of Note Addresses. Duh.  
//
// Modifications:
//				- 04/08/97 michael	- Created
//
#ifndef CLSNOTEADDRESSLIST_INCLUDED

#include "eBayTypes.h"
#include "clsNoteAddress.h"
#include <list.h>


typedef list<clsNoteAddress> clsNoteAddressList;

#define CLSNOTEADDRESSLIST_INCLUDED
#endif /* CLSNOTEADDRESSLIST_INCLUDED */
