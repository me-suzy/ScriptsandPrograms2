/*	$Id: clsSubHTMLPage.cpp,v 1.2 1999/02/21 02:24:07 josh Exp $	*/
//
//	File:	clsSubHTMLPage.cpp
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

#include "clsRebuildListApp.h"
#include "clsSubHTMLPage.h"
#include "clsTemplate.h"
#include "clsMarketPlace.h"


clsSubHTMLPage::clsSubHTMLPage(clsCategory* pCategory,
						 CategoryVector* pCategories,
						 ListingItemVector* pItems,
						 TimeCriterion TimeStamp,
						 clsFileName* pFileName
						 )
	: clsHTMLPage(pCategory, 
				  pCategories, 
				  pItems, 
				  NULL, 
				  NULL, 
				  TimeStamp, 
				  pFileName
				  )
{
	mHotItemCount = mpApp->GetMarketPlace()->GetHotItemCount();
}

clsSubHTMLPage::~clsSubHTMLPage()
{
	delete mpHotItems;
	delete mpFeaturedItems;
}

// create and initialize necessary objects based on the template.
bool clsSubHTMLPage::Initialize()
{
	// Create the template
	/*
	if (!mpCategory)
	{
		mpApp->LogMessage("Null Category pointer is used in clsSubHTMLPage");

		return false;
	}
*/
	if (CreateAndParseTemplate())
	{
		// Separate items into featured items, hot items, normal listing items
		if (mpItems)
		{	
			SeparateItems();
		}

		return CreatePortions();
	}

	mpApp->LogMessage("Parse template failed");

	return false;
}

// separate the items into three different vectors as needed
void clsSubHTMLPage::SeparateItems()
{
	ListingItemVector::iterator	iItem;

	bool	HasFeaturedPortion;
	bool	HasHotPortion;
	bool	EraseIt;

	if (HasFeaturedPortion = mpTemplate->HasPortion(FEATURE_ITEM))
	{
		mpFeaturedItems = new ListingItemVector;
	}
	
	if (HasHotPortion = mpTemplate->HasPortion(HOT_ITEM))
	{
		mpHotItems = new ListingItemVector;
	}

	if (!HasFeaturedPortion && !HasHotPortion)
		return;

	// separate items
	for (iItem = mpItems->begin(); iItem != mpItems->end(); iItem++)
	{
		EraseIt = false;

		if (HasFeaturedPortion && (*iItem)->IsFeatured())
		{
			// Add to featured item vector
			mpFeaturedItems->push_back(*iItem);

			EraseIt = true;
		}

		if ( HasHotPortion && 
			(*iItem)->GetBidCount() > mHotItemCount &&
			!(*iItem)->IsReserved())

		{
			// Add to hot item vector
			mpHotItems->push_back(*iItem);

			EraseIt = true;
		}

		if (EraseIt)
		{
			// remove it from item vector
			mpItems->erase(iItem);
			iItem--;
		}
	}
}
