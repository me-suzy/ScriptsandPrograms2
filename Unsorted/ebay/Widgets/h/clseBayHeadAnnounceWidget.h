/*	$Id: clseBayHeadAnnounceWidget.h,v 1.4 1999/03/07 08:15:16 josh Exp $	*/
//
//	File:	clseBayHeadAnnounceWidget.h
//
//	Class:	clseBayHeadAnnounceWidget
//
//	Author:	Tini
//
//	Function:
//			Widget that emits the eBay header announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/20/97	tini - Created
//
#ifndef CLSEBAYHEADANNOUNCEWIDGET_INCLUDED
#define CLSEBAYHEADANNOUNCEWIDGET_INCLUDED

#include "clseBayWidget.h"

class clseBayHeadAnnounceWidget : public clseBayWidget
{

public:

	// Header widget requires access to the marketplace.
	clseBayHeadAnnounceWidget(clsMarketPlace *pMarketPlace = 0, int iType = 0);

	// Empty dtor
	virtual ~clseBayHeadAnnounceWidget() {};
	
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:
private:

	int						mType; // the type of announcement

};

#endif // CLSEBAYHEADANNOUNCEWIDGET_INCLUDED