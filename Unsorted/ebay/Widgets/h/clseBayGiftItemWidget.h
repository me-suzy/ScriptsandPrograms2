/*	$Id: clseBayGiftItemWidget.h,v 1.2 1998/12/06 05:22:35 josh Exp $	*/
//
//	File:	clseBayGiftItemWidget.h
//
//	Class:	clseBayGiftItemWidget
//
//	Author:	Mila Bird
//
//	Function:
//			Shows gift item title and description.
//			This widget was derived from clseBayWidget by overriding
//			the following routines:
//				* EmitHTML()
//
//			Example code of how to invoke the clseBayGiftItemWidget:
//
//				clseBayGiftItemWidget *idw = new clseBayGiftItemWidget(mpMarketPlace);
//				idw->SetItemId(2137312);
//				idw->SetColor("#FFECEA");
//				idw->EmitHTML(mpStream);
//				delete idw;
//
// Modifications:
//				- 10/24/98	mila	 - Created
//
#ifndef CLSEBAYGIFTITEMWIDGET_INCLUDED
#define CLSEBAYGIFTITEMWIDGET_INCLUDED

#include "clsWidgetHandler.h"
#include "clseBayWidget.h"

// forwards
class	clsItem;

class clseBayGiftItemWidget : public clseBayWidget
{

public:

	// Needs marketplace
	clseBayGiftItemWidget(clsMarketPlace *pMarketPlace);
	clseBayGiftItemWidget(clsWidgetHandler *pWidgetHandler, clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayGiftItemWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayGiftItemWidget(pMarketPlace); }
	
	// Emit the HTML for this widget to the specified stream.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

	void SetItem(clsItem *Item)					{ mpItem = Item; }
	void SetColor(char *Color)					{ strncpy(mColor, Color, sizeof(mColor) - 1); }
	void SetShowTitleBar(bool b)				{ mShowTitleBar = b; }
	void SetShowDescription(bool b)				{ mShowDescription = b; }

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
	bool		mShowTitleBar;		// whether or not to show item title and item # bar
	bool		mShowDescription;	// whether or not to show description

};

#endif // CLSEBAYGIFTITEMWIDGET_INCLUDED
