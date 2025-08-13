/*	$Id: clsDatabaseOracleCategoryFilters.cpp,v 1.2 1999/05/19 02:34:51 josh Exp $	*/
//	File:		clsDatabaseOracleCategoryFilters.cpp
//
// Class:	clsDatabaseOracle
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				Methods to access information in ebay_category_filters table
//
// Modifications:
//				- 04/13/99 mila		- Created
//


#include "eBayKernel.h"
#include "clsCategoryFilters.h"

//-------------------------------------------------------------------------------------
// Category Filters
//-------------------------------------------------------------------------------------

//
// AddCategoryFilter
//

static char *SQL_AddCategoryFilter =
"insert into ebay_category_filters "
"	( "
"		category_id, "
"		filter_id "
"	) "
"	values "
"	( "
"		:category_id, "
"		:filter_id "
"	)";

bool clsDatabaseOracle::AddCategoryFilter(clsCategoryFilter *pCategoryFilter)
{
	int		categoryId;
	int		filterId;

	bool	success = false;

	if (pCategoryFilter == NULL)
		return false;

	categoryId = (int)pCategoryFilter->GetCategoryId();
	filterId = (int)pCategoryFilter->GetFilterId();

	OpenAndParse(&mpCDAOneShot, SQL_AddCategoryFilter);

	// Bind it, baby
	Bind(":category_id", &categoryId);
	Bind(":filter_id", &filterId);

	// Do it...
	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return success;
}

//
// DeleteCategoryFilter
//

static char *SQL_DeleteCategoryFilter =
"delete from ebay_category_filters "
"	where	category_id = :cat_id "
"	and		filter_id = :filter_id";

void clsDatabaseOracle::DeleteCategoryFilter(CategoryId categoryId,
												FilterId filterId)
{
	OpenAndParse(&mpCDADeleteCategoryFilter, SQL_DeleteCategoryFilter);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":filter_id", (int *)&filterId);

	Execute();
	Commit();

	Close(&mpCDADeleteCategoryFilter);
	SetStatement(NULL);

	return;
}

//
// UpdateCategoryFilter
//

static char *SQL_UpdateCategoryFilter =
"update ebay_category_filters "
"	set		filter_id = :new_filter_id"
"	where	category_id = :cat_id "
"	and		filter_id = :filter_id";

bool clsDatabaseOracle::UpdateCategoryFilter(CategoryId categoryId,
												FilterId filterId,
												FilterId newFilterId)
{
	bool success = false;

	OpenAndParse(&mpCDAUpdateCategoryFilter, SQL_UpdateCategoryFilter);

	Bind(":new_filter_id", (int *)&newFilterId);
	Bind(":cat_id", (int *)&categoryId);
	Bind(":filter_id", (int *)&filterId);

	Execute();

	if (!CheckForNoRowsUpdated())
	{
		Commit();
		success = true;
	}

	Close(&mpCDAUpdateCategoryFilter);
	SetStatement(NULL);

	return success;
}

//
// GetCategoryFilter
//

static char *SQL_GetCategoryFilter =
"select	category_id, "
"		filter_id "
"	from ebay_category_filters "
"	where	category_id = :cat_id "
"	and		filter_id = :filter_id";

clsCategoryFilter * clsDatabaseOracle::GetCategoryFilter(CategoryId categoryId,
															   FilterId filterId)
{
	int	catId = 0;
	int	filtId = 0;

	clsCategoryFilter *pCategoryFilter = NULL;

	OpenAndParse(&mpCDAGetCategoryFilter, SQL_GetCategoryFilter);

	Bind(":cat_id", (int *)&categoryId);
	Bind(":filter_id", (int *)&filterId);

	Define(1, &catId);
	Define(2, &filtId);

	ExecuteAndFetch();

	if (!CheckForNoRowsFound())
		pCategoryFilter = new clsCategoryFilter((CategoryId)catId,
												   (FilterId)filtId);

	Close(&mpCDAGetCategoryFilter);
	SetStatement(NULL);

	return pCategoryFilter;
}

#define ORA_CATFILTER_ARRAYSIZE	 100

//
// GetCategoryFiltersByCategoryId
//

static char *SQL_GetCategoryFiltersByCategoryId =
"select	filter_id "
"	from ebay_category_filters "
"	where	category_id = :cat_id";

bool clsDatabaseOracle::GetCategoryFiltersByCategoryId(CategoryId categoryId,
														  vector<FilterId> *pvFilterIds)
{
	int		filterId[ORA_CATFILTER_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	OpenAndParse(&mpCDAGetCategoryFiltersByCategoryId,
				 SQL_GetCategoryFiltersByCategoryId);

	Bind(":cat_id", &categoryId);

	Define(1, (int *)&filterId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCategoryFiltersByCategoryId, true);
		SetStatement(NULL);
		return false;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATFILTER_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCategoryFiltersByCategoryId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATFILTER_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pvFilterIds->push_back((FilterId)filterId[i]);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetCategoryFiltersByCategoryId);
	SetStatement(NULL);

	return true;
}

//
// GetCategoryFilterCountByCategoryId
//

static char *SQL_GetCategoryFilterCountByCategoryId =
"select	count (*) "
"	from ebay_category_filters "
"	where	category_id = :cat_id";

unsigned int clsDatabaseOracle::GetCategoryFilterCountByCategoryId(CategoryId categoryId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetCategoryFilterCountByCategoryId,
				 SQL_GetCategoryFilterCountByCategoryId);

	Bind(":cat_id", (int *)&categoryId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetCategoryFilterCountByCategoryId);
	SetStatement(NULL);

	return (unsigned int)count;
}

//
// GetCategoryFiltersByFilterId
//

static char *SQL_GetCategoryFiltersByFilterId =
"select	category_id "
"	from ebay_category_filters "
"	where	filter_id = :filter_id";


bool clsDatabaseOracle::GetCategoryFiltersByFilterId(FilterId filterId,
														vector<CategoryId> *pvCategoryIds)
{
	int		categoryId[ORA_CATFILTER_ARRAYSIZE];
	int		rowsFetched = 0;
	int		i, n;
	int		rc = 0;

	OpenAndParse(&mpCDAGetCategoryFiltersByFilterId,
				 SQL_GetCategoryFiltersByFilterId);

	Bind(":filter_id", (int *)&filterId);

	Define(1, &categoryId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetCategoryFiltersByFilterId, true);
		SetStatement(NULL);
		return false;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATFILTER_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetCategoryFiltersByFilterId, true);
			SetStatement(NULL);
			return false;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATFILTER_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pvCategoryIds->push_back((CategoryId)categoryId[i]);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetCategoryFiltersByFilterId);
	SetStatement(NULL);

	return true;
}

//
// GetCategoryFilterCountByFilterId
//

static char *SQL_GetCategoryFilterCountByFilterId =
"select	count (*) "
"	from ebay_category_filters "
"	where	filter_id = :filter_id";

unsigned int clsDatabaseOracle::GetCategoryFilterCountByFilterId(FilterId filterId)
{
	int	count = 0;

	OpenAndParse(&mpCDAGetCategoryFilterCountByFilterId,
				 SQL_GetCategoryFilterCountByFilterId);

	Bind(":filter_id", (int *)&filterId);

	Define(1, &count);

	ExecuteAndFetch();

//	if (CheckForNowRowsFound())
//		count = 0;

	Close(&mpCDAGetCategoryFilterCountByFilterId);
	SetStatement(NULL);

	return (unsigned int)count;
}


//
// GetAllCategoryFilters
//

static char *SQL_GetAllCategoryFilters =
"select	category_id, "
"		filter_id "
"	from ebay_category_filters";

void clsDatabaseOracle::GetAllCategoryFilters(CategoryFilterVector *pvCategoryFilters)
{
	CategoryId				categoryId[ORA_CATFILTER_ARRAYSIZE];
	FilterId				filterId[ORA_CATFILTER_ARRAYSIZE];
	int						rowsFetched = 0;
	int						i, n;
	int						rc = 0;
	clsCategoryFilter *	pCategoryFilter = NULL;

	OpenAndParse(&mpCDAGetAllCategoryFilters, SQL_GetAllCategoryFilters);

	Define(1, (int *)&categoryId[0]);
	Define(2, (int *)&filterId[0]);

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetAllCategoryFilters, true);
		SetStatement(NULL);
		return;
	}

	// do array fetch...
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_CATFILTER_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAGetAllCategoryFilters, true);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_CATFILTER_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i = 0; i < n; i++)
		{
			pCategoryFilter = new clsCategoryFilter(categoryId[i],
														  filterId[i]);

			pvCategoryFilters->push_back(pCategoryFilter);
		}

	} while (!CheckForNoRowsFound());

	Close(&mpCDAGetAllCategoryFilters);
	SetStatement(NULL);

	return;
}


