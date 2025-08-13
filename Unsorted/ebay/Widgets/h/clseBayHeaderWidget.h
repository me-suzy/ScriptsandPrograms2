/*	$Id: clseBayHeaderWidget.h,v 1.2 1998/10/16 01:00:55 josh Exp $	*/
//
//	File:	clseBayHeaderWidget.h
//
//	Class:	clseBayHeaderWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits the eBay header.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/20/97	Poon - Created
//
#ifndef CLSEBAYHEADERWIDGET_INCLUDED
#define CLSEBAYHEADERWIDGET_INCLUDED

#include "clseBayWidget.h"

class clsAnnouncement;

class clseBayHeaderWidget : public clseBayWidget
{

public:

	// Header widget requires access to the marketplace.
	clseBayHeaderWidget(clsMarketPlace *pMarketPlace = NULL);

	// Empty dtor
	virtual ~clseBayHeaderWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayHeaderWidget(pMarketPlace); }
	
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:

private:
	clsAnnouncement *mpAnnounce;
};

#endif // CLSEBAYHEADERWIDGET_INCLUDED
