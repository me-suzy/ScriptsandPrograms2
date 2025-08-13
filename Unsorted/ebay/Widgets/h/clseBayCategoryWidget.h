/*	$Id: clseBayCategoryWidget.h,v 1.3.206.1 1999/05/21 22:22:08 jnace Exp $	*/
//
//	File:	clseBayCategoryWidget.h
//
//	Class:	clseBayCategoryWidget
//
//	Author:	Chad Musick
//
//	Note: NumItems in the widget refers to the number of categories
//			to show. If there are too many categories, then the widget
//			will randomly choose categories to remove.
//
//  Modifications:
//				- 10/14/97	Poon - Created
//				- 10/17/97	Chad - Copied for clseBayCategoryWidget
//				- 12/18/98  poon - began changes to support category home pages
//				- 05/11/99  jnace - made private members protected so can derive clseBayLACategoryWidget
//

#ifndef CLSEBAYCATEGORYWIDGET_INCLUDED
#define CLSEBAYCATEGORYWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsCategories.h"
#include <vector.h>

class clsUser;

class clseBayCategoryWidget : public clseBayTableWidget
{

public:

	// Category widget requires having access to the marketplace
	clseBayCategoryWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayCategoryWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayCategoryWidget(pMarketPlace); }

	void SetCategoryId(CategoryId id)			{ mId = id; }
	void SetCategoryList(const char *c)			{ strncpy(mCategoryList, c, sizeof(mCategoryList) - 1); }
	void SetMoreText(const char *c)				{ strncpy(mMoreText, c, sizeof(mMoreText) - 1); }
	void SetMoreLink(const char *c)				{ strncpy(mMoreLink, c, sizeof(mMoreLink) - 1); }
	void SetHowDeepToGo(int i)					{ mHowDeepToGo = i; }
	void SetIncludeParent(bool b)				{ mIncludeParent = b; }
	void SetAutoMoreLinks(bool b)				{ mAutoMoreLinks = b; }

	void SetFont(const char *c)						{ strncpy(mFont, c, sizeof(mFont) - 1);}
	void SetFontSize(int FontSize)					{ mFontSize = FontSize;}

	void SetLinkNonLeaves(bool b)					{ mLinkNonLeaves = b; }
	void SetShowItemCounts(bool b)					{ mShowItemCounts = b; }

	void SetLinkPrefix(const char *c)				{ strncpy(mLinkPrefix, c, sizeof(mLinkPrefix) - 1);}


	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);
	

protected:
	// Choose the categories from the database and put them into mvCategories
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

	CategoryVector				mvCategories;
	CategoryId					mId;					// if 0, display top-level categories.
														//  if >0, display subcategories of given
														//  category id.
	char				mCategoryList[256];				// if not "", then use given category list, which
														//  is a space delimited list of categoryids
	char				mMoreText[32];					// "more..."
	char				mMoreLink[256];					// link to where more... points
	int					mHowDeepToGo;					// how many levels deep to consider (0 means no limit)
	bool				mIncludeParent;					// if true, include the parent itself as first category
	bool				mAutoMoreLinks;					// if true, automatically puts in more links

	char				mFont[256];						// font, e.g. arial,helvetica
	int					mFontSize;						// font size, e.g. 3

	bool				mLinkNonLeaves;					// if true, links will appear on non-leaf categories (default = true)
	bool				mShowItemCounts;				// if true, show item counts (default = true)

	char				mLinkPrefix[256];				// if not "", use this for the link path instead of eBayListingPath, which is "http://listings.ebay.com/aw/listings/list/"
	

};

#endif // CLSEBAYCATEGORYWIDGET_INCLUDED
