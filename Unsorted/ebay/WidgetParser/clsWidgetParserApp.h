/*	$Id: clsWidgetParserApp.h,v 1.2.402.1 1999/08/03 01:16:07 phofer Exp $	*/
//
//	File:		clsWidgetParserApp.h
//
// Class:	clsWidgetParserApp
//
//	Author:	Alex Poon
//
//	Function:
//			Parses HTML that includes eBay widgets. The app's main purpose
//				is to call yylex(), but it also creates the widget handler,
//				provides the database, etc..
//
// Modifications:
//				- 1/6/98	Poon - Created
//				- 07/26/99	petra	- added site id as a parameter
//

#ifndef CLSWIDGETPARSERAPP_INCLUDED
#define	CLSWIDGETPARSERAPP_INCLUDED

#include <stdio.h>
#include <string.h>
#include "clsApp.h"

// Class forward
class clsWidgetHandler;

class clsWidgetParserApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsWidgetParserApp(int siteId);	// petra
		~clsWidgetParserApp();

		// Action
		bool Run();

		clsWidgetHandler *mpWidgetHandler;

	private:
		int mSiteID;		// petra

};

#endif // CLSWIDGETPARSERAPP_INCLUDED
