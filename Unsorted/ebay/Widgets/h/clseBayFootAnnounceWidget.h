/*	$Id: clseBayFootAnnounceWidget.h,v 1.4 1999/03/07 08:15:16 josh Exp $	*/
//
//	File:	clseBayFootAnnounceWidget.h
//
//	Class:	clseBayFootAnnounceWidget
//
//	Author:	Tini
//
//	Function:
//			Widget that emits the eBay footer announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 10/24/97	Tini - Created
//
#ifndef CLSEBAYFOOTANNOUNCEWIDGET_INCLUDED
#define CLSEBAYFOOTANNOUNCEWIDGET_INCLUDED

#include "clseBayWidget.h"


class clseBayFootAnnounceWidget : public clseBayWidget
{

public:

	// Footer widget requires access to the marketplace.
	clseBayFootAnnounceWidget(clsMarketPlace *pMarketPlace = 0, int iType = 0);

	// Empty dtor
	virtual ~clseBayFootAnnounceWidget() {};
	
	// Emit the HTML for the footer.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:
private:

	int						mType; // the type of announcement

};

#endif // CLSEBAYFOOTANNOUNCEWIDGET_INCLUDED
