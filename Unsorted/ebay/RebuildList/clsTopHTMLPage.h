/*	$Id: clsTopHTMLPage.h,v 1.2 1999/02/21 02:24:15 josh Exp $	*/
//
//	File:	clsTopHTMLPage.h
//
//	Class:	clsTopHTMLPage
//
//	Author:	Wen Wen
//
//	Function:
//			Create a HTML page for a top listing page
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSTOPHTMLPAGE_INCLUDED
#define CLSTOPHTMLPAGE_INCLUDED

#include "clsHTMLPage.h"

class clsTopHTMLPage : public clsHTMLPage
{
public:
	clsTopHTMLPage(CategoryVector* pCategories,
			    ListingItemVector*		pFeaturedItems,
				ListingItemVector*		pHotItems,
				TimeCriterion	TimeStamp,
				clsFileName*	pFileName);

	bool Initialize();

protected:
	void RemoveAdultItems();
	bool IsAdultItem(clsListingItem* pItem);
};

#endif // CLSTOPHTMLPAGE_INCLUDED
