//	File:		clsCategoryFilters.h
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


#ifndef CLSCATEGORYFILTERS_INCLUDED

#include "eBayTypes.h"
#include "clsCategory.h"
#include "clsFilter.h"
#include "clsCategoryFilter.h"


class clsCategoryFilters {

public:

	// Default constructor
	clsCategoryFilters(clsMarketPlace *pMarketPlace)
		: mpMarketPlace(pMarketPlace)
	{
	}

	// Destructor
	virtual ~clsCategoryFilters()
	{
	}

	//
	// AddCategoryFilter
	//
	bool		AddCategoryFilter(clsCategoryFilter * pCategoryFilter);

	bool		AddCategoryFilter(CategoryId categoryId,
								  FilterId filterId) const;

	bool		AddCategoryFilter(CategoryId categoryId,
								  const char * const pFilterName) const;
	//
	// DeleteCategoryFilter
	//
	void		DeleteCategoryFilter(CategoryId categoryId,
									 FilterId filterId) const;

	//
	// UpdateCategoryFilter
	//
	bool		UpdateCategoryFilter(CategoryId categoryId,
									 FilterId filterId,
									 clsCategoryFilter * const pCategoryFilter) const;

	//
	// GetCategoryFilter
	//
	clsCategoryFilter *GetCategoryFilter(CategoryId categoryId,
										 FilterId filterId) const;

	//
	// GetCategoryFiltersByCategoryId
	//
	void		GetCategoryFiltersByCategoryId(CategoryId categoryId,
											   vector<FilterId> *pvFilterIds) const;

	//
	// GetCategoryFiltersByFilterId
	//
	void		GetCategoryFiltersByFilterId(FilterId filterId,
											 vector<CategoryId> *pvCategoryIds) const;

	//
	// GetFiltersByCategoryId
	//
	void		GetFiltersByCategoryId(CategoryId categoryId,
									   FilterVector * const pvFilters) const;

protected:

private:
	clsMarketPlace *	mpMarketPlace;

};


#define CLSCATEGORYFILTERS_INCLUDED
#endif // CLSCATEGORYFILTERS_INCLUDED
