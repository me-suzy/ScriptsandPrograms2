/*	$Id: clsWidgetParserApp.cpp,v 1.3.362.1 1999/08/03 01:16:07 phofer Exp $	*/
//
//	File:		clsWidgetParserApp.cpp
//
// Class:	clsWidgetParserApp
//
//	Author:	Alex Poon
//
//	Function:
//			Build eBay home page
//
// Modifications:
//				- 1/6/98	Poon - Created
//				- 07/26/99	petra	- add site ID as a parameter
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsWidgetParserApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsWidgetHandler.h"

extern yylex();

clsWidgetParserApp::clsWidgetParserApp(int siteId)		// petra
{
	mpWidgetHandler = NULL;
	mSiteID = siteId;			// petra
}


clsWidgetParserApp::~clsWidgetParserApp()
{
	if (mpWidgetHandler) delete mpWidgetHandler;
}

// All this really does is call yylex
bool clsWidgetParserApp::Run()
{
	// make a widgethandler
	mpWidgetHandler = new clsWidgetHandler(this->GetMarketPlaces()->GetCurrentMarketPlace(), this);

	// petra set the current site id
	this->GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->SetCurrentSite (mSiteID);	// petra

	// set read only for all database transactions.
	//  NOTE: this assumes that none of the widgets modify the database
	// this->GetDatabase()->Begin();
	
	// if we want the cache to be updated by the WidgetParser we need to leave this commented out!
	// this->GetDatabase()->SetReadOnly();

	// The real work
	yylex();

	// the read only thing
	// this->GetDatabase()->End();

	return true;
}


