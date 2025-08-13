/*	$Id: clseBayHotWidget.h,v 1.2 1998/10/16 01:00:57 josh Exp $	*/
//
//	File:	clseBayHotWidget.h
//
//	Class:	clseBayHotWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows eBay hot auctions.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the current, hot items from the
//										  database and stuff their ids into
//										  the given vector. 
//
//			Example code of how to invoke the clseBayHotWidget:
//
//				clseBayHotWidget *hw = new clseBayHotWidget(mpMarketPlace);
//				hw->SetShowBidCount(true);
//				hw->SetNumItems(3);
//				hw->SetCellPadding(2);
//				hw->SetColor("#FFECEA");
//				hw->EmitHTML(mpStream);
//				delete hw;
//
// Modifications:
//				- 10/15/97	Poon - Created
//
#ifndef CLSEBAYHOTWIDGET_INCLUDED
#define CLSEBAYHOTWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBayHotWidget : public clseBayItemWidget
{
public:

	// Hot item widget requires having access to the marketplace
	clseBayHotWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayHotWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayHotWidget(pMarketPlace); }

	void SetCategoryId(CategoryId id) { mCatId = id; }

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	
protected:
	// Retrieve ids of all current, hot items and stuff them into pvItemIds.
	virtual void GetItemIds(vector<int> *pvItemIds);

private:
	CategoryId					mCatId;	// if 0, seledt from all items.
										//  if >0, select ony items that are in this
										//  catId or one of its descendants
};

#endif // CLSEBAYHOTWIDGET_INCLUDED
