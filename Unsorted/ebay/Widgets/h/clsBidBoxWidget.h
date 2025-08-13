/*	$Id: clsBidBoxWidget.h,v 1.2 1999/04/07 05:42:19 josh Exp $	*/
//
//	File:		clsBidBoxWidget.h
//
//	Class:		clsBidBoxWidget
//
//	Author:		Barry Boone (barry@ebay.com)
//
//	Function:
//		Present a form for the user to place a bid.
//
//	Modifications:
//				- 2/24/99 Barry	- Created
//
// Usage:
//			clsBidBoxWidget bidBox(clsBidBoxWidget(mpMarketPlace, mpItem, minimumBid);
//			bidBox.EmitHTML(pStream);
//
//////////////////////////////////////////////////////////////////////

#ifndef CLSBIDBOXWIDGET_INCLUDED
#define CLSBIDBOXWIDGET_INCLUDED

#include "clseBayWidget.h"


class clsBidBoxWidget : public clseBayWidget  
{
public:
	clsBidBoxWidget(clsWidgetHandler *pHandler, clsMarketPlace *pMarketPlace, clsApp *pApp);
	clsBidBoxWidget(clsMarketPlace *pMarketPlace, clsItem *mpItem, double minimumBid);
	virtual ~clsBidBoxWidget();

	// The heart of it.
	bool EmitHTML(ostream *pStream);	// Must be implemented, it's a pure virtual function above

    // For translation to and from text.
	void   SetParams(vector<char *> *pvArgs);
    void   SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long   GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clsBidBoxWidget(pHandler, pMarketPlace, pApp); }

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);


private:	
	clsItem		   *mpItem;
	double			mMinimumBid;
};

#endif // ifndef CLSBIDBOXWIDGET_INCLUDED
