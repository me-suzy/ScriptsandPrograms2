//	File:		clsCategoryFilters.cpp
//
// Class:	clsCategoryFilters
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//			Manages clsCategoryFilter objects
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsCategoryFilter.h"
#include "clsCategoryFilters.h"
#include "clsFilters.h"


//
// AddCategoryFilter
//

bool clsCategoryFilters::AddCategoryFilter(clsCategoryFilter * pCategoryFilter) 
{
	bool	success = false;

	if (pCategoryFilter != NULL)
	{
		success = gApp->GetDatabase()->AddCategoryFilter(pCategoryFilter);
		if (success)
		{
			mpMarketPlace->GetCategories()->FlagCategory(pCategoryFilter->GetCategoryId(), true);
			mpMarketPlace->GetFilters()->SetDirtyCache();
		}
	}

	return success;
}


//
// AddCategoryFilter
//

bool clsCategoryFilters::AddCategoryFilter(CategoryId categoryId,
										   FilterId filterId) const
{
	bool				success = false;
	clsCategoryFilter	categoryFilter(categoryId, filterId);

	success = gApp->GetDatabase()->AddCategoryFilter(&categoryFilter);
	if (success)
	{
		mpMarketPlace->GetCategories()->FlagCategory(categoryId, true);
		mpMarketPlace->GetFilters()->SetDirtyCache();
	}

	return success;
}


//
// AddCategoryFilter
//

bool clsCategoryFilters::AddCategoryFilter(CategoryId categoryId,
										   const char * const pFilterName) const
{
	bool			success = false;
	clsFilter *		pFilter = NULL;
	clsMarketPlace *pMarketPlace;

	if (pFilterName != NULL)
	{
		pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
		if (pMarketPlace != NULL)
		{
			pFilter = pMarketPlace->GetFilters()->GetFilter(pFilterName);
			if (pFilter != NULL)
			{
				clsCategoryFilter	categoryFilter(categoryId, pFilter->GetId());
				success = gApp->GetDatabase()->AddCategoryFilter(&categoryFilter);
				if (success)
				{
					mpMarketPlace->GetCategories()->FlagCategory(categoryId, true);
					mpMarketPlace->GetFilters()->SetDirtyCache();
				}
				delete pFilter;
			}
		}
	}

	return success;
}


//
// DeleteCategoryFilter
//

void clsCategoryFilters::DeleteCategoryFilter(CategoryId categoryId,
											  FilterId filterId) const
{
	gApp->GetDatabase()->DeleteCategoryFilter(categoryId, filterId);
	mpMarketPlace->GetFilters()->SetDirtyCache();
}


//
// UpdateCategoryFilter
//

bool clsCategoryFilters::UpdateCategoryFilter(CategoryId categoryId,
											  FilterId filterId,
											  clsCategoryFilter * const pCategoryFilter) const
{
	bool	success = false;

	if (pCategoryFilter != NULL)
	{
		success = gApp->GetDatabase()->UpdateCategoryFilter(categoryId,
															filterId,
															pCategoryFilter->GetFilterId());
		if (success)
			mpMarketPlace->GetFilters()->SetDirtyCache();
	}

	return success;
}


//
// GetCategoryFilter
//

clsCategoryFilter * clsCategoryFilters::GetCategoryFilter(CategoryId categoryId,
														  FilterId filterId) const
{
	return gApp->GetDatabase()->GetCategoryFilter(categoryId, filterId);
}


//
// GetCategoryFiltersByCategoryId
//

void clsCategoryFilters::GetCategoryFiltersByCategoryId(CategoryId categoryId,
									vector<FilterId> *pvFilterIds) const
{
	if (pvFilterIds != NULL)
		gApp->GetDatabase()->GetCategoryFiltersByCategoryId(categoryId, pvFilterIds);
}


//
// GetCategoryFiltersByFilterId
//

void clsCategoryFilters::GetCategoryFiltersByFilterId(FilterId filterId,
									vector<CategoryId> *pvCategoryIds) const
{
	if (pvCategoryIds != NULL)
		gApp->GetDatabase()->GetCategoryFiltersByFilterId(filterId, pvCategoryIds);
}


//
// GetFiltersByCategoryId
//

void clsCategoryFilters::GetFiltersByCategoryId(CategoryId categoryId,
												FilterVector * const pvFilters) const
{
	if (pvFilters == NULL)
		return;

	// Get all the category filters with the given category ID
	gApp->GetDatabase()->GetFilters(categoryId, pvFilters);
}

