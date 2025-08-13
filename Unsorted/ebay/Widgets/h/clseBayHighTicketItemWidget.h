/*	$Id: clseBayHighTicketItemWidget.h,v 1.2 1998/10/16 01:00:56 josh Exp $	*/
//
//	File:	clseBayHighTicketItemWidget.h
//
//	Class:	clseBayHighTicketItemWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows eBay high ticket price auctions.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the current, high ticket items from the
//										  database and stuff their ids into
//										  the given vector. 
//
//			Example code of how to invoke the clseBayHighTicketItemWidget:
//
//				clseBayHighTicketItemWidget *sfw = new clseBayHighTicketItemWidget(mpMarketPlace);
//				sfw->SetNumItems(3);
//				sfw->SetCellPadding(2);
//				sfw->SetColor("#F2F8FF");
//				sfw->EmitHTML(mpStream);
//				delete sfw;
//
// Modifications:
//				- 09/09/15	Wen Wen - Created
//
#ifndef CLSEBAYHIGHTICKETITEMWIDGET_INCLUDED
#define CLSEBAYHIGHTICKETITEMWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBayHighTicketItemWidget : private clseBayItemWidget
{
public:

	// Super-featured item widget requires having access to the marketplace
	clseBayHighTicketItemWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayHighTicketItemWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayHighTicketItemWidget(pMarketPlace); }

	void SetPrice(double Price) { mPrice = Price; }
	void SetFullPage(int FullPage) { mFullPage = FullPage; }

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

	// emit html based on whether it is printing full page
	virtual bool EmitHTML(ostream *pStream);
	
protected:
	// Retrieve ids of all current, hot items and stuff them into pvItemIds.
	virtual void GetItemIds(vector<int> *pvItemIds);

private:
	float	mPrice;		// select items which price is equal or higher
	int		mFullPage;	// determine to print a full listing page or not.
};

#endif // CLSEBAYHIGHTICKETITEMWIDGET_INCLUDED
