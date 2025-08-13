/*	$Id: clseBayCategorySelectorWidget.h,v 1.2 1998/10/16 01:00:48 josh Exp $	*/
//
//	File:	clseBayCategorySelectorWidget.h
//
//	Class:	clseBayCategorySelectorWidget
//
//	Author:	Poon
//
//	Function:
//			Widget that emits a category selector composed of drop-down selection boxes
//			This widget was derived from clseBayWidget by overriding
//			 the following routines:
//				* EmitHTML()	
//				* SetParams()		
//
// Modifications:
//				- 05/20/98	Poon - Created
//
#ifndef CLSEBAYCATEGORYSELECTORWIDGET_INCLUDED
#define CLSEBAYCATEGORYSELECTORWIDGET_INCLUDED

#include "eBayPageTypes.h"
#include "clseBayWidget.h"

class clseBayCategorySelectorWidget : public clseBayWidget
{

public:

	// URL widget needs marketplace.
	clseBayCategorySelectorWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor
	virtual ~clseBayCategorySelectorWidget() {};

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayCategorySelectorWidget(pMarketPlace); }

	void SetSelectedCategory(CategoryId c)			{ mSelectedCategory = c; }
	void SetNumColumns(int i)						{ mNumColumns = i;}
	void SetMenuPrefix(char *c)						{ strncpy(mMenuPrefix, c, sizeof(mMenuPrefix)-1); } 
	void SetIncludeNonLeaves(bool b)				{ mIncludeNonLeaves = b; }

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	
	// Emit the HTML. Incluces the beginning and ending quotes.
	//  Should return whether or not it was successful.
	virtual bool EmitHTML(ostream *pStream);

protected:

private:

	CategoryId			mSelectedCategory;		// the pre-selected category
	int					mNumColumns;			// default is 1
	char				mMenuPrefix[64];		// default is "category"
	bool				mIncludeNonLeaves;		// if true, show non leaves too; default is false

};

#endif // CLSEBAYCATEGORYSELECTORWIDGET_INCLUDED
