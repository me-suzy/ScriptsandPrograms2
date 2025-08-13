/* $Id: clseBayCountWidget.h,v 1.2 1998/10/16 01:00:50 josh Exp $ */
// Just draws the number of times a page has been
// accessed, as stored in the widget context.
#ifndef clseBayCountWidget_h
#define clseBayCountWidget_h

#include "clseBayWidget.h"

class clseBayCountWidget : public clseBayWidget
{
public:

	clseBayCountWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL) :
        clseBayWidget(pHandler, pMarketPlace, pApp)
    { }
    ~clseBayCountWidget() { }
	
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	bool EmitHTML(ostream *pStream);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayCountWidget(pHandler, pMarketPlace, pApp); }
};
#endif /* clseBayCountWidget_h */