/*	$Id: clseBayRandomItemWidget.h,v 1.2 1998/10/16 01:01:07 josh Exp $	*/
//
//	File:	clseBayRandomItemWidget.h
//
//	Class:	clseBayRandomItemWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows random eBay auctions.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the current items from the
//										  database and stuff their ids into
//										  the given vector. 
//
//			Example code of how to invoke the clseBayRandomItemWidget:
//
//				clseBayRandomItemWidget *riw = new clseBayRandomItemWidget(mpMarketPlace);
//				riw->SetName("Box #");
//				riw->SetNumItems(3);
//				riw->SetCellPadding(2);
//				riw->SetColor("#F2F8FF");
//				riw->EmitHTML(mpStream);
//				delete riw;
//
// Modifications:
//				- 10/15/97	Poon - Created
//
#ifndef CLSEBAYRANDOMITEMWIDGET_INCLUDED
#define CLSEBAYRANDOMITEMWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBayRandomItemWidget : public clseBayItemWidget
{
public:

	// Random item widget requires having access to the marketplace
	clseBayRandomItemWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayRandomItemWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayRandomItemWidget(pMarketPlace); }

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
	// Retrieve ids of all current items and stuff them into pvItemIds.
	virtual void GetItemIds(vector<int> *pvItemIds);

private:
	CategoryId					mCatId;	// if 0, seledt from all items.
										//  if >0, select ony items that are in this
										//  catId or one of its descendants
};

#endif // CLSEBAYRANDOMITEMWIDGET_INCLUDED
