/*	$Id: clsSubHTMLPage.h,v 1.2 1999/02/21 02:24:09 josh Exp $	*/
//
//	File:	clsSubHTMLPage.h
//
//	Class:	clsSubHTMLPage
//
//	Author:	Wen Wen
//
//	Function:
//			Create a HTML page for a category
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSSUBHTMLPAGE_INCLUDED
#define CLSSUBHTMLPAGE_INCLUDED

#include "clsHTMLPage.h"

class clsSubHTMLPage : public clsHTMLPage
{
public:
	// Should not used this constructor
	clsSubHTMLPage(clsCategory* pCategory,
				CategoryVector* pCategories,
			    ListingItemVector* pItems,
				TimeCriterion TimeStamp,
				clsFileName* pFileName);

	~clsSubHTMLPage();

	// create and initialize necessary objects based on the template.
	bool Initialize();

	// separate items
	void SeparateItems();

protected:
	int mHotItemCount;

};

#endif // CLSSUBHTMLPAGE_INCLUDED
