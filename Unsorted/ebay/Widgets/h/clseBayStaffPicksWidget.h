/*	$Id: clseBayStaffPicksWidget.h,v 1.2 1998/10/16 01:01:08 josh Exp $	*/
//
//	File:	clseBayStaffPicksWidget.h
//
//	Class:	clseBayStaffPicksWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows staff-picked eBay auctions.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the staff-picked items from the
//										  database and stuff their ids into
//										  the given vector. 
//
//			Example code of how to invoke the clseBayStaffPicksWidget:
//
//				clseBayStaffPicksWidget *spw = new clseBayStaffPicksWidget(mpMarketPlace);
//				spw->SetNumItems(3);
//				spw->SetCellPadding(2);
//				spw->SetColor("#FFFFCC");
//				spw->EmitHTML(mpStream);
//				delete spw;
//
// Modifications:
//				- 10/15/97	Poon - Created
//
#ifndef CLSEBAYSTAFFPICKSWIDGET_INCLUDED
#define CLSEBAYSTAFFPICKSWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBayStaffPicksWidget : public clseBayItemWidget
{
public:

	// Staff-picks widget requires having access to the marketplace
	clseBayStaffPicksWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayStaffPicksWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayStaffPicksWidget(pMarketPlace); }

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
	// Retrieve ids of all staff-picked items and stuff them into pvItemIds.
	virtual void GetItemIds(vector<int> *pvItemIds);

private:
	CategoryId					mCatId;	// if 0, seledt from all items.
										//  if >0, select ony items that are in this
										//  catId or one of its descendants
};

#endif // CLSEBAYSTAFFPICKSWIDGET_INCLUDED
