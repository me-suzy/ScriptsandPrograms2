/*	$Id: clseBayGalleryWidget.h,v 1.1.4.1 1999/06/04 19:13:39 jpearson Exp $	*/
//
//	File:	clseBayGalleryWidget.h
//
//	Class:	clseBayGalleryWidget
//
//	Author:	Bill Wang
//
//	Function:
//			Widget that shows random set of Gallery pictures.
//			This widget was derived from clseBayItemWidget by overriding
//			 the following routines:
//				* GetItemIds()			= get the current items from the
//										  database and stuff their ids into
//										  the given vector. 
//
//			Example code of how to invoke the clseBayGalleryWidget:
//
//				clseBayGalleryWidget *riw = new clseBayGalleryWidget(mpMarketPlace);
//				riw->SetName("Box #");
//				riw->SetNumItems(3);
//				riw->SetCellPadding(2);
//				riw->SetColor("#F2F8FF");
//				riw->EmitCell(mpStream, 5);
//				delete riw;
//
// Modifications:
//				- 05/25/99	Bill - Created
//
#ifndef CLSEBAYGALLERYWIDGET_INCLUDED
#define CLSEBAYGALLERYWIDGET_INCLUDED

#include "clseBayItemWidget.h"
#include "clsItem.h"

class clseBayGalleryWidget : public clseBayItemWidget
{
public:

	// Random item widget requires having access to the marketplace
	clseBayGalleryWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayGalleryWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayGalleryWidget(pMarketPlace); }

	void SetCategoryId(CategoryId id) { mCatId = id; }
	void SetPictureHeight(int height) { mPictureHeight = height; }
	void SetPictureWidth (int width ) { mPictureWidth  = width;  }

	// overwrite EmitCell()
	virtual bool EmitCell(ostream *pStream, int n);

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
	CategoryId		mCatId;	// if 0, seledt from all items.
							//  if >0, select ony items that are in this
							//  catId or one of its descendants
	int		mPictureHeight;		// in pixels; default = 50
	int		mPictureWidth;		// in pixels; default = 50

};

#endif // CLSEBAYGALLERYWIDGET_INCLUDED
