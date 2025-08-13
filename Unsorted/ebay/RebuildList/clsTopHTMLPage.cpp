/*	$Id: clsTopHTMLPage.cpp,v 1.2 1999/02/21 02:24:14 josh Exp $	*/
//
//	File:	clsTopHTMLPage.cpp
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

#include "clsRebuildListApp.h"
#include "clsTopHTMLPage.h"

clsTopHTMLPage::clsTopHTMLPage(CategoryVector* pCategories,
						 ListingItemVector*	 pFeaturedItems,
						 ListingItemVector*	 pHotItems,
						 TimeCriterion	 TimeStamp,
						 clsFileName*	 pFileName)
	: clsHTMLPage(NULL, 
				  pCategories, 
				  NULL, 
				  pFeaturedItems, 
				  pHotItems, 
				  TimeStamp, 
				  pFileName)
{
}

bool clsTopHTMLPage::Initialize()
{
	RemoveAdultItems();

	return clsHTMLPage::Initialize();
}

void clsTopHTMLPage::RemoveAdultItems()
{
	ListingItemVector::iterator	iItem;

	if (mpHotItems && mpHotItems->size())
	{
		for (iItem = mpHotItems->begin(); iItem != mpHotItems->end(); iItem++)
		{
			if (IsAdultItem(*iItem))
			{
				mpHotItems->erase(iItem);
				iItem--;
			}
		}
	}
}

bool clsTopHTMLPage::IsAdultItem(clsListingItem* pItem)
{
	CategoryId		Id;
	clsCategory*	pCategory;
	bool			IsAdultCategory;

	Id = pItem->GetCategoryId();

	pCategory = mpApp->GetCategories()->GetCategory(Id);

	IsAdultCategory = pCategory->GetAdult();

	delete pCategory;

	return IsAdultCategory;
}
