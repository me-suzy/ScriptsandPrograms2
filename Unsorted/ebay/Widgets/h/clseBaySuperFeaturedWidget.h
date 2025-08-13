/*	$Id: clseBaySuperFeaturedWidget.h,v 1.2 1998/10/16 01:01:10 josh Exp $	*/
//
//	File:	clseBaySuperFeaturedWidget.h
//
//	Class:	clseBaySuperFeaturedWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that shows eBay super-featured auctions.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the current, super-featured items from the
//										  database and stuff their ids into
//										  the given vector. 
//				* GetMoreLink()			= returns URL to the hot section of the
//										  listings
//
//			Example code of how to invoke the clseBaySuperFeaturedWidget:
//
//				clseBaySuperFeaturedWidget *sfw = new clseBaySuperFeaturedWidget(mpMarketPlace);
//				sfw->SetNumItems(3);
//				sfw->SetCellPadding(2);
//				sfw->SetColor("#F2F8FF");
//				sfw->EmitHTML(mpStream);
//				delete sfw;
//
// Modifications:
//				- 10/15/97	Poon - Created
//
#ifndef CLSEBAYSUPERFEATUREDWIDGET_INCLUDED
#define CLSEBAYSUPERFEATUREDWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBaySuperFeaturedWidget : public clseBayItemWidget
{
public:

	// Super-featured item widget requires having access to the marketplace
	clseBaySuperFeaturedWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBaySuperFeaturedWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBaySuperFeaturedWidget(pMarketPlace); }

	void SetCategoryId(CategoryId id) { mCatId = id; }
	void SetIncludeCategoryFeatured(bool cf) { mIncludeCategoryFeatured = cf; }

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
	bool						mIncludeCategoryFeatured;	// default is false.
};

#endif // CLSEBAYSUPERFEATUREDWIDGET_INCLUDED
