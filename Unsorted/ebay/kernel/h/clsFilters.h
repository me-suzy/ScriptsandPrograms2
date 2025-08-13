//	File:		clsFilters.h
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


#ifndef CLSFILTERS_INCLUDED
#define CLSFILTERS_INCLUDED

#include "map.h"

#include "eBayTypes.h"
#include "clsFilter.h"


typedef map<unsigned int, FilterVector, less<unsigned int> >	CategoryFilterMap;


class clsFilters {

public:

	// Default constructor
	clsFilters(clsMarketPlace *pMarketPlace) :
		mpMarketPlace(pMarketPlace),
		mDirtyCache(true),
		mpFilterCacheById(NULL),
		mFilterCacheByIdSize(0),
		mCategoryFilterMapSize(0),
		mpCategoryFilterBuckets(NULL)
	{
	}

	// Destructor
	virtual ~clsFilters()
	{
		PurgeFilterCaches();
	}

	//
	// AddFilter
	//
	bool		AddFilter(clsFilter *pFilter);

	//
	// UpdateFilter
	//
	bool		UpdateFilter(FilterId id, clsFilter *pFilter);
	bool		UpdateFilter(const char *pName, clsFilter *pFilter);

	//
	// DeleteFilter
	//
	void		DeleteFilter(FilterId id);
	void		DeleteFilter(const char *pName);

	//
	// GetFilter
	//
	clsFilter *	GetFilter(FilterId id, bool useCache = false);
	clsFilter *	GetFilter(const char *pName, bool useCache = false);

	//
	// GetThisAndParentCategoryFilters
	//
	void				GetThisAndParentCategoryFilters(CategoryId categoryId,
														FilterVector *pvFilters,
														bool useCache = false);

	//
	// GetAllFilters
	//
	void				GetAllFilters(FilterVector * const pvFilters,
									  bool useCache = false);

	//
	// GetMaxFilterId
	//
	FilterId			GetMaxFilterId() const;

	//
	// SetDirtyCache
	//
	void				SetDirtyCache() { mDirtyCache = true; }

	//
	// IsThisOrParentCategoryFilter
	//
	bool				IsThisOrParentCategoryFilter(CategoryId categoryId,
													 FilterId filterId);


protected:

	//
	// PopulateFilterCaches
	//
	void				PopulateFilterCaches();

	//
	// PurgeFilterCaches
	//
	void				PurgeFilterCaches();

private:

	clsMarketPlace *	mpMarketPlace;
	bool				mDirtyCache;

	clsFilter **		mpFilterCacheById;
	unsigned int		mFilterCacheByIdSize;

	// Only leaf category filters will hash to exactly one bucket.  Filters
	// for non-leaf categories will be dropped into each descendant leaf
	// category's bucket.  This will have to do until I can figure out how
	// to write my own hash function, or come up with an alternative.
	// (mila 4/10/99)
	CategoryFilterMap	mCategoryFilterMap;
	unsigned int		mCategoryFilterMapSize;

	FilterVector *		mpCategoryFilterBuckets;

};


#endif // CLSFILTERS_INCLUDED

