/*	$Id: clseBayFooterWidget.h,v 1.2 1998/10/16 01:00:53 josh Exp $	*/
//
//	File:	clseBayFooterWidget.h
//
//	Class:	clseBayFooterWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits the eBay footer.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/20/97	Poon - Created
//
#ifndef CLSEBAYFOOTERWIDGET_INCLUDED
#define CLSEBAYFOOTERWIDGET_INCLUDED

#include "clseBayWidget.h"
class clsAnnouncement;

class clseBayFooterWidget : public clseBayWidget
{

public:

	// Footer widget requires access to the marketplace.
	clseBayFooterWidget(clsMarketPlace *pMarketPlace = NULL);

	// Empty dtor
	virtual ~clseBayFooterWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayFooterWidget(pMarketPlace); }
	
	// Emit the HTML for the footer.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:

};

#endif // CLSEBAYFOOTERWIDGET_INCLUDED
