/*	$Id: clsFilters.cpp,v 1.2 1999/05/19 02:34:56 josh Exp $ */
//	File:		clsFilters.cpp
//
// Class:		clsFilters
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Manages clsFilter objects and caches
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"

#include "clsFilter.h"
#include "clsFilters.h"
#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif


//
// AddFilter
//
bool clsFilters::AddFilter(clsFilter *pFilter)
{
	bool success = false;

	if (pFilter != NULL)
	{
		if (pFilter->GetId() == 0)
			pFilter->SetId(gApp->GetDatabase()->GetNextFilterId());

		success = gApp->GetDatabase()->AddFilter(pFilter);

		mDirtyCache = success;
	}

	return success;
}


//
// UpdateFilter
//
bool clsFilters::UpdateFilter(FilterId id, clsFilter *pFilter)
{
	bool success = false;

	if (pFilter != NULL)
	{
		success = gApp->GetDatabase()->UpdateFilter(id, pFilter);
		mDirtyCache = success;
	}

	return success;
}


//
// UpdateFilter
//
bool clsFilters::UpdateFilter(const char *pName, clsFilter *pFilter)
{
	bool success = false;

	if (pName != NULL && pFilter != NULL)
	{
		success = gApp->GetDatabase()->UpdateFilter(pName, pFilter);
		mDirtyCache = success;
	}

	return success;
}


//
// DeleteFilter
//
void clsFilters::DeleteFilter(FilterId id)
{
	gApp->GetDatabase()->DeleteFilter(id);
	mDirtyCache = true;
}


//
// DeleteFilter
//
void clsFilters::DeleteFilter(const char *pName)
{
	if (pName != NULL)
	{
		gApp->GetDatabase()->DeleteFilter(pName);
		mDirtyCache = true;
	}
}


//
// GetFilter
//
clsFilter * clsFilters::GetFilter(FilterId id, bool useCache)
{
	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateFilterCaches();

		return mpFilterCacheById[id];
	}

	// otherwise, get it from the database
	return gApp->GetDatabase()->GetFilter(id);
}


//
// GetFilter
//
clsFilter * clsFilters::GetFilter(const char *pName, bool useCache)
{
	clsFilter *		pFilter = NULL;
	char *			pFilterName = NULL;
	unsigned int	i = 0;

	if (pName == NULL)
		return NULL;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateFilterCaches();

		for (i = 1; i < mFilterCacheByIdSize; i++)
		{
			if (mpFilterCacheById[i] != NULL)
			{
				pFilterName = mpFilterCacheById[i]->GetName();
				if (pFilterName != NULL &&
					strcasecmp(pFilterName, (char *)pName) == 0)
				{
					pFilter = mpFilterCacheById[i];
					break;
				}
			}
		}
	}
	else
		pFilter = gApp->GetDatabase()->GetFilter(pName);

	return pFilter;
}


//
// GetThisAndParentCategoryFilters
//

void clsFilters::GetThisAndParentCategoryFilters(CategoryId categoryId,
												 FilterVector *pvFilters,
												 bool useCache)
{
	FilterVector *			pvCatFilters;
	FilterVector::iterator	i;
	clsCategories *         pCategories = NULL;
	clsCategory *           category;
	int                     level;

	if (pvFilters == NULL)
		return;
	
	// Use cache till we get the database access code in place.
	useCache = true;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateFilterCaches();

		pCategories = mpMarketPlace->GetCategories();
		category    = pCategories->GetCategory(categoryId, true);
		for (level = category->catLevel(); level > 0; level--)
		{
			// find the message bucket for the category
			pvCatFilters = &mCategoryFilterMap[categoryId];

			// iterate...
			for (i = pvCatFilters->begin(); i != pvCatFilters->end(); i++)
			{
				// Make sure we have a valid pointer
				if ((*i)  != NULL)
					pvFilters->push_back(*i);
			}

			categoryId = category->GetParent();
			category   = pCategories->GetCategory(categoryId, true);
		}
	}
//	else
//		gApp->GetDatabase()->GetFilters(categoryId, pvFilters);
}


//
// GetAllFilters
//
void clsFilters::GetAllFilters(FilterVector * const pvFilters, bool useCache)
{
	unsigned int	i;

	if (pvFilters == NULL)
		return;

	if (useCache)
	{
		// get it from the cache
		if (mDirtyCache)
			PopulateFilterCaches();

		for (i = 1; i < mFilterCacheByIdSize; i++)
		{
			if (mpFilterCacheById[i] != NULL )
				pvFilters->push_back(mpFilterCacheById[i]);
		}
	}
	else
		gApp->GetDatabase()->GetAllFilters(pvFilters);

	return;
}

//
// IsThisOrParentCategoryFilter
//
bool clsFilters::IsThisOrParentCategoryFilter(CategoryId categoryId,
											  FilterId filterId)
{
	FilterVector *			pvFilter = NULL;
	FilterVector::iterator	i;
	clsCategories *         pCategories = NULL;
	clsCategory   *         category;
	int                     level;

	// get it from the cache, only way to get messages of ancestors also
	if (mDirtyCache)
		PopulateFilterCaches();

	pCategories = mpMarketPlace->GetCategories();
	category    = pCategories->GetCategory(categoryId, true);
	for (level = category->catLevel(); level > 0; level--)
	{
		// select the bucket to iterate through
		pvFilter = &mCategoryFilterMap[categoryId];

		// iterate through the bucket
		for (i = pvFilter->begin(); i != pvFilter->end(); i++)
		{
			// Check to make sure that we have a valid pointer
			if ((*i) != NULL)
			{
				// Check if message id already exists
				if ((*i)->GetId() == filterId)
				{
					return true;
				}
			}
		}
		categoryId = category->GetParent();
		category   = pCategories->GetCategory(categoryId, true);
	}

	return false;
}


//
// GetMaxFilterId
//
FilterId clsFilters::GetMaxFilterId() const
{
	return gApp->GetDatabase()->GetMaxFilterId();
}


//
// PopulateFilterCaches
//
void clsFilters::PopulateFilterCaches()
{
	unsigned int						i;

	FilterVector						vFilters;
	FilterVector::iterator				iFilter;

	clsFilter *							pFilter;

	CategoryVector						vCategories;

	CategoryId							categoryId;
	clsCategory *						pCategory;
	clsCategories *						pCategories;

	CategoryFilterVector				vCategoryFilters;
	CategoryFilterVector::iterator		iMin;


	// First purge the current caches
	PurgeFilterCaches();

	// Get all filters from the database
	gApp->GetDatabase()->GetAllFilters(&vFilters);
	mFilterCacheByIdSize = GetMaxFilterId() + 1;

	// Allocate memory for filter cache ordered by filter ID
	mpFilterCacheById = new clsFilter *[mFilterCacheByIdSize];
	memset(mpFilterCacheById, 0, mFilterCacheByIdSize * sizeof(clsFilter *));

	// Create a cache of filters ordered by filter ID
	for (iFilter = vFilters.begin(); iFilter != vFilters.end(); iFilter++)
	{
		// Populate the filter cache ordered by filter ID
		if ((*iFilter) != NULL)
			mpFilterCacheById[(*iFilter)->GetId()] = (*iFilter);
	}

	// We're done with that cache, so on to setting up the next one...

	pCategories = mpMarketPlace->GetCategories();

	// Get all categories from the category cache
	// NOTE:  do NOT delete the elements of vCategories when done,
	// cuz the vector contains pointers INTO THE CACHE, not pointers
	// to copies
	pCategories->All(&vCategories, true);
	mCategoryFilterMapSize = vCategories.size();

	// Allocate an array of filter buckets, one per category
#ifdef _MSC_VER
	mpCategoryFilterBuckets = new FilterVector[mCategoryFilterMapSize];
#else
	// The following assignment is to get around a compiler bug in gcc
	FilterVector *bozo = new FilterVector[mCategoryFilterMapSize];
	mpCategoryFilterBuckets = bozo;
#endif
	memset(mpCategoryFilterBuckets, 0, mCategoryFilterMapSize * sizeof(FilterVector));
	// For each category, map the category ID at index i in the
	// category vector to the filter bucket at index i in the filter
	// buckets vector
	for (i = 0; i < mCategoryFilterMapSize; i++)
	{
		categoryId = vCategories[i]->GetId();
		mCategoryFilterMap[categoryId] = mpCategoryFilterBuckets[i];
	}

	// Now we're ready to populate the buckets...

	// Get all category filters from the database
	gApp->GetDatabase()->GetAllCategoryFilters(&vCategoryFilters);

	for (iMin = vCategoryFilters.begin(); iMin != vCategoryFilters.end(); iMin++)
	{
		// Sanity Check
		if ((*iMin) != NULL)
		{
			// Get the category ID so we know which bucket we want
			categoryId = (*iMin)->GetCategoryId();

			pCategory = pCategories->GetCategory(categoryId, true);
			pFilter = mpFilterCacheById[(*iMin)->GetFilterId()];
			if (categoryId != 0)
			{
				// Drop the filter in the appropriate bucket
				mCategoryFilterMap[categoryId].push_back(pFilter);
			}

			// Delete the category filter cuz we're done with it
			delete (*iMin);

			// Don't delete the filter cuz it points into the cache!
		}
	}

	// Do some clean up
	vCategoryFilters.erase(vCategoryFilters.begin(), vCategoryFilters.end());
	vCategories.erase(vCategories.begin(), vCategories.end());
	vFilters.erase(vFilters.begin(), vFilters.end());

	// Now we have:
	//   - a vector of filters ordered by ID
	//   - a vector of categories, each with its own filter bucket

	mDirtyCache = false;
}


//
// PurgeFilterCaches
//
void clsFilters::PurgeFilterCaches()
{
	unsigned int	i;

	// Delete memory allocated for vector of filters ordered by filter ID
	for (i = 0; i < mFilterCacheByIdSize; i++)
	{
		if (mpFilterCacheById[i] != NULL)
		{
			delete mpFilterCacheById[i];
			mpFilterCacheById[i] = NULL;
		}
	}
	if (mpFilterCacheById != NULL)
	{
		delete [] mpFilterCacheById;
		mpFilterCacheById = NULL;
		mFilterCacheByIdSize = 0;
	}

	if (mpCategoryFilterBuckets != NULL)
	{
		// Delete memory allocated for filter buckets
		for (i = 0; i < mCategoryFilterMapSize; i++)
		{
			mpCategoryFilterBuckets[i].erase(mpCategoryFilterBuckets[i].begin(),
											 mpCategoryFilterBuckets[i].end());
		}
		delete [] mpCategoryFilterBuckets;
	}

	// Delete memory allocated for category-filter map
	mCategoryFilterMap.erase(mCategoryFilterMap.begin(), mCategoryFilterMap.end());
	mCategoryFilterMapSize = 0;

	mDirtyCache = true;
}

