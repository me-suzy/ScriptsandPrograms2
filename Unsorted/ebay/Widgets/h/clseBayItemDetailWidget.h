/*	$Id: clseBayItemDetailWidget.h,v 1.3 1999/05/19 02:34:08 josh Exp $	*/
//
//	File:	clseBayItemDetailWidget.h
//
//	Class:	clseBayItemDetailWidget
//
//	Author:	Poon
//
//	Function:
//			Shows item details.
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()
//
//			Example code of how to invoke the clseBayItemDetailWidget:
//
//				clseBayItemDetailWidget *idw = new clseBayItemDetailWidget(mpMarketPlace);
//				idw->SetItemId(2137312);
//				idw->SetColor("#FFECEA");
//				idw->EmitHTML(mpStream);
//				delete idw;
//
// Modifications:
//				- 11/18/97	Poon - Created
//				- 05/06/99	Gurinder - Added a flag whether this page is display old item

#ifndef CLSEBAYITEMDETAILWIDGET_INCLUDED
#define CLSEBAYITEMDETAILWIDGET_INCLUDED

#include "clsWidgetHandler.h"
#include "clseBayWidget.h"

// forwards
class	clsItem;

class clseBayItemDetailWidget : public clseBayWidget
{

public:

	typedef enum
	{
		Generic,
		Bidder,
		Seller
	} modeEnum;

	// Needs marketplace
	clseBayItemDetailWidget(clsMarketPlace *pMarketPlace);
	clseBayItemDetailWidget(clsWidgetHandler *pWidgetHandler, clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayItemDetailWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayItemDetailWidget(pMarketPlace); }
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void SetItem(clsItem *Item)					{ mpItem = Item; }
	void SetColor(char *Color)					{ strncpy(mColor, Color, sizeof(mColor) - 1); }
	void SetMode(modeEnum mode)					{ mMode = mode; }
	void SetShowTitleBar(bool b)				{ mShowTitleBar = b; }
	void SetShowDescription(bool b)				{ mShowDescription = b; }
	void SetIsViewOldItemPage(bool b)			{mIsViewOldItemPage = b;} //gurinder - 05/06/99

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

protected:
	virtual bool EmitHTML(ostream *pStream, clsWidgetHandler *)
	{ return EmitHTML(pStream); }

private:
	clsItem		*mpItem;			// item whose details we will show
	char		mColor[32];			// background color of header; default = ""
	modeEnum	mMode;				// influences what about the item gets displayed	
	bool		mShowTitleBar;		// whether or not to show item title and item # bar
	bool		mShowDescription;	// whether or not to show description
	bool		mIsViewOldItemPage; //whether or not we are displaying old item
};

#endif // CLSEBAYITEMDETAILWIDGET_INCLUDED
