/*	$Id: clsItemToHTMLApp.h,v 1.2 1999/02/21 02:22:49 josh Exp $	*/
//
//	File:	clsItemsToHTMLApp.h
//
//	Class:	clsItemsToHTMLApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 05/11/97 michael	- Created
//
#ifndef CLSITEMSTOHTMLAPP_INCLUDED

#include "clsApp.h"


class clsItemsToHTMLApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsItemsToHTMLApp();
		~clsItemsToHTMLApp();
		
		// Runner
		void Run();


	private:

};

#define CLSITEMSTOHTMLAPP_INCLUDED 1
#endif /* CLSITEMSTOHTMLAPP_INCLUDED */
