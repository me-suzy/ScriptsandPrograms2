/*<tab>$Id: clseBayLACategoryWidget.h,v 1.1.4.1 1999/05/21 20:47:15 jnace Exp $<tab>*/
//
//	File:	clseBayLACategoryWidget.h
//
//	Class:	clseBayLACategoryWidget
//
//	Author:	Janet Nace
//
//  Modifications:
//				- 04/23/99 jnace	- Created
//

#ifndef CLSEBAYLACATEGORYWIDGET_INCLUDED
#define CLSEBAYLACATEGORYWIDGET_INCLUDED

#include "clseBayCategoryWidget.h"


// The following defines the top level categories that are specific to L.A.
// These are used only for counting items under the top level category for
// the home page item counts. In all other cases, such as when creating the 
// item counts for the category index pages, we use the list of category
// ids obtained from the input template file, as usual.
// Note that some items may be counted more than once, since they may exist
// under an L.A.-specific category as well as under a standard top level
// category (such as Garden and Sporting Goods).
// The mChildCategoryIds vector stores the child (not grandchild) categories
// of the top level category. This list may or may not match the list that
// would be obtained from the database.
struct LATopLevelCategory
{
	short mId;							// top level category id
	char  *mName;						// top level category name
	vector<short> mChildCategoryIds;	// immediate child categories of top level category
};


class clseBayLACategoryWidget : public clseBayCategoryWidget
{

public:

	// Category widget requires having access to the marketplace
	clseBayLACategoryWidget(clsMarketPlace *pMarketPlace);

	~clseBayLACategoryWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayLACategoryWidget(pMarketPlace); }

protected:
	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

private:
	vector<LATopLevelCategory> mvLATopLevelCategories;
};

#endif // CLSEBAYLACATEGORYWIDGET_INCLUDED
