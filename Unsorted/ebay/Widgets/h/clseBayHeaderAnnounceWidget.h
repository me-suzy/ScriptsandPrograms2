/*	$Id: clseBayHeaderAnnounceWidget.h,v 1.2.390.1 1999/08/01 02:51:21 barry Exp $	*/
//
//	File:	clseBayHeaderAnnounceWidget.h
//
//	Class:	clseBayHeaderAnnounceWidget
//
//	Author:	Craig Huang
//
//	Function:
//			Widget that emits the eBay header announcements.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()			
//
// Modifications:
//				- 3/13/97	tini - Created
//
#ifndef CLSEBAYHEADERANNOUNCEWIDGET_INCLUDED
#define CLSEBAYHEADERANNOUNCEWIDGET_INCLUDED

#include "clseBayWidget.h"
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */

class clseBayHeaderAnnounceWidget : public clseBayWidget
{
public:

	// Header widget requires access to the marketplace.
	clseBayHeaderAnnounceWidget(clsMarketPlace *pMarketPlace = 0);

	// Empty dtor
	virtual ~clseBayHeaderAnnounceWidget() {};
	

	virtual bool EmitHTML(ostream *pStream);
	// Emit the HTML for the header.
	//  Should return whether or not it was successful.
	//bool EmitHTML(ostrstream *pStream);

	bool EmitPrefix(ostream *pStream);
	bool EmitSuffix(ostream *pStream);


protected:
private:

	clsMarketPlace *mpMarketPlace;

};

#endif // CLSEBAYHEADERANNOUNCEWIDGET_INCLUDED