/*	$Id: clsCategoriesSite.cpp,v 1.1.6.2 1999/08/04 05:26:49 phofer Exp $	*/
//
//	File:	clsCategoriesSite.cpp
//
//	Class:	clsCategoriesSite
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all marketplaces
//
//	Modifications:
//				  mm/dd/yy
//				- 05/07/97 michael	- Created
//				- 07/01/97 tini		- modified for hierarchical categories
//				- 07/23/97 wen		- added GetLinkPath
//				- 09/17/97 poon		- added EmitHTMLLeafSelectionMultipleDropdown
//				- 11/14/97 charles	- added a test in GetCategory() 
//				- 06/08/99 petra	- split off from clsCategories
//

#include "eBayKernel.h"
#include "clsCategoryFilters.h"
#include "clsCategoryMessages.h"
#include "clsCategoriesSite.h"

//
// Default Constructor
//
clsCategoriesSite::clsCategoriesSite(clsMarketPlace *pMarketPlace, int siteId)
{
	mpMarketPlace	= pMarketPlace;
	
	// set the category cache to be dirty
	mpCategoryCacheById = NULL;
	mpCategoryCacheByOrderNo = NULL;
	mCategoryCacheByIdSize = 0;
	mCategoryCacheByOrderNoSize = 0;
	mpCategoryCacheChildren = NULL;
	mDirtyCache = true;
	
	mpCategoryFilters = new clsCategoryFilters(mpMarketPlace);
	mpCategoryMessages = new clsCategoryMessages(mpMarketPlace);

	mSiteId = siteId;

	return;
}

//
// Destructor
//
clsCategoriesSite::~clsCategoriesSite()
{
	delete mpCategoryFilters;
	delete mpCategoryMessages;

	PurgeCategoryCaches();

	return;
}

//
// GetCategory
//	Get a category by Category Id
// If using cache, caller should not delete the categories--they are owned by clsCategories
clsCategory *clsCategoriesSite::GetCategory(CategoryId id, bool useCache /*=false*/)
{
	// using the cache is faster, and caller need not delete the returned category
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoryCaches();

		// safety
		if ((id < 0) || (id >= mCategoryCacheByIdSize)) 
			return NULL;

		// sanity
		if (!mpCategoryCacheById) 
			return NULL;

		// return the cached array element
		return mpCategoryCacheById[id];
	}

	// get the category form the database instead. this is slower and caller
	//  must delete the returned category
	return gApp->GetDatabase()->GetCategoryById(mpMarketPlace->GetId(),id, mSiteId);
}


// Default category is top level miscellaneous
// If using cache, caller should not delete the categories--they are owned by clsCategories
clsCategory *clsCategoriesSite::GetCategoryDefault(bool useCache /*=false*/)
{
	return GetCategory(324, useCache);
}

// Get parent of a category
clsCategory *clsCategoriesSite::Parent(clsCategory *pCategory, bool useCache /*=false*/)
{
// actually, GetLevel1 of category.
	return GetCategory(pCategory->GetLevel1(), useCache);
}


//
// getting category vectors use the same set of
// declarations, etc; only the sql statement differ.
// all = 1; topLevel = 2
// children = 3, descendants = 4, siblings = 5, leaves = 6
//

// get all categories from the database, sorted by order no
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategoriesSite::All(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	int i;

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoryCaches();

		// sanity
		if (!mpCategoryCacheByOrderNo) 
			return;

		// for efficiency, tell the vector how big it will be
		pvCategories->reserve(mCategoryCacheByOrderNoSize);

		// fill 'er up
		for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
			if (mpCategoryCacheByOrderNo[i])
				pvCategories->push_back(mpCategoryCacheByOrderNo[i]);

		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	pvCategories->reserve(1100);
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 1, pvCategories, mSiteId);
	return;
}


// retained for backwards compatibility
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategoriesSite::AllSorted(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	All(pvCategories, useCache);

	return;
}

// finds all top level categories
void clsCategoriesSite::TopLevel(CategoryVector *pvCategories, bool useCache /*=false*/)
{	
	int i;

	// for efficiency, tell the vector approximately how big it will be
	pvCategories->reserve(12);

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoryCaches();

		// sanity
		if (!mpCategoryCacheByOrderNo)
			return;

		// fill 'er up
		for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
			if ((mpCategoryCacheByOrderNo[i]) && (mpCategoryCacheByOrderNo[i]->GetLevel1() == 0))
				pvCategories->push_back(mpCategoryCacheByOrderNo[i]);

		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 2, pvCategories, mSiteId);

	return;
}


// find all leaf categories
// If using cache, caller should not delete the categories--they are owned by clsCategories
void clsCategoriesSite::Leaves(CategoryVector *pvCategories, bool useCache /*=false*/)
{
	int i;

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache)
			PopulateCategoryCaches();

		// for efficiency, tell the vector approximately how big it will be
		pvCategories->reserve(mCategoryCacheByOrderNoSize);

		assert(mpCategoryCacheByOrderNo);
		// fill 'er up
		for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
			if ((mpCategoryCacheByOrderNo[i]) && (mpCategoryCacheByOrderNo[i]->isLeaf()))
				pvCategories->push_back(mpCategoryCacheByOrderNo[i]);

		return;
	}

	// get the categories form the database instead. this is slower and caller
	//  must delete the returned categories
	pvCategories->reserve(1100);
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 6, pvCategories, mSiteId);
	return;


}

// selects all the children and returns a category vector
void clsCategoriesSite::Children(CategoryVector *pvCategories, clsCategory *pCategory, bool useCache /*=false*/)
{
	int i;
	int catId;

	// using the cache is faster, and caller need not delete the returned categories
	if (useCache)
	{
		// populate the caches if necessary
		if (mDirtyCache) PopulateCategoryCaches();

		// get the category id (NULL means top-level)
		catId = pCategory ? pCategory->GetId() : 0;

		// fill 'er up
		if (mpCategoryCacheChildren[catId])
		{
			for (i=0; i<mpCategoryCacheChildren[catId]->size(); i++)
				if ((*(mpCategoryCacheChildren[catId]))[i])
					pvCategories->push_back((*(mpCategoryCacheChildren[catId]))[i]);
		}
		return;
	}

	// get the categories from the database instead. this is slower and caller
	// must delete the returned categories
	gApp->GetDatabase()->
		GetCategoryVector(mpMarketPlace->GetId(), 
				pCategory->GetId(), 3, pvCategories, mSiteId);
	return;
}


// selects all the children and returns a category vector - sorted
void clsCategoriesSite::ChildrenSorted(CategoryVector *pvCategories, 
							 clsCategory *pRoot)
{

	gApp->GetDatabase()->GetCategoryVector(pRoot->GetMarketPlaceId(),
				pRoot->GetId(), 7, pvCategories, mSiteId);

	return;
}


// find all descendants of a category - ordered & depth-first.
// if pCategory==NULL, return all categories & subcategories.
void clsCategoriesSite::DescendantsOrdered(CategoryVector *pvCategories, clsCategory *pParent)
{
	CategoryVector		vCategories;	// for holding the immediate children
	CategoryVector::iterator	i;		// for iterating through the children


	// base case - stop at leaves
	if ((pParent) && (pParent->isLeaf())) 
		return;

	// ok, we know the pParent is a real parent, so recurse on each child

	// get the parent's immediate children
	if (pParent)
		ChildrenSorted(&vCategories, pParent);
	else
		TopLevel(&vCategories, false);

	// for each immediate child, add it to the big vector and recurse
	for (i = vCategories.begin(); i != vCategories.end(); i++)
	{
		pvCategories->push_back(*i);
		DescendantsOrdered(pvCategories, *i);
	}

}


// clear and delete the caches
void clsCategoriesSite::PurgeCategoryCaches()
{
	int i;

	// clear the existing by orderno cache
	if (mpCategoryCacheByOrderNo)
	{
		for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
		{
			if (mpCategoryCacheByOrderNo[i])
			{
				delete mpCategoryCacheByOrderNo[i];
				mpCategoryCacheByOrderNo[i] = NULL;
			}
		}
		delete [] mpCategoryCacheByOrderNo;
	}
	mpCategoryCacheByOrderNo = NULL;
	mCategoryCacheByOrderNoSize = 0;
	
	// clear the existing by id cache
	if (mpCategoryCacheById)
	{
		for (i = 0; i < mCategoryCacheByIdSize; i++)
		{
			if (mpCategoryCacheById[i])
			{
				// delete mpCategoryCacheById[i];	// don't delete, because the categories are shared by the two arrays
				mpCategoryCacheById[i] = NULL;
			}
		}
		delete [] mpCategoryCacheById;
	}
	mpCategoryCacheById = NULL;

	// clear the existing by category children cache
	if (mpCategoryCacheChildren)
	{
		for (i = 0; i < mCategoryCacheByIdSize; i++)
		{
			if (mpCategoryCacheChildren[i])
			{
				mpCategoryCacheChildren[i]->erase(mpCategoryCacheChildren[i]->begin(), mpCategoryCacheChildren[i]->end());
				delete mpCategoryCacheChildren[i];	// delete the vector
				mpCategoryCacheChildren[i] = NULL;
			}
		}
		delete [] mpCategoryCacheChildren;
	}
	mpCategoryCacheChildren = NULL;
	
	mCategoryCacheByIdSize = 0;

	// cache is dirty now
	mDirtyCache = true;
}

// fetch categories from the database and populate the caches
//  there are two caches--one indexed/sorted by category id, and one indexed/sorted by order no
void clsCategoriesSite::PopulateCategoryCaches()
{
	int i;
	CategoryVector vCategories;
	CategoryVector::iterator	j;
	int parentId;

	// purge first just in case
	PurgeCategoryCaches();

	// fetch all the categories from the database
	gApp->GetDatabase()->GetCategoryVector(mpMarketPlace->GetId(), 0, 1, &vCategories,
		mSiteId); // 1 is the querycode for getting all categories

	// set the sizes of each cache
	mCategoryCacheByIdSize = mpMarketPlace->GetCategories()->GetMaxCategoryId()+1;
	mCategoryCacheByOrderNoSize = vCategories.size();

	// allocate memory for the caches
	mpCategoryCacheById = new clsCategory*[mCategoryCacheByIdSize];
	mpCategoryCacheByOrderNo = new clsCategory*[mCategoryCacheByOrderNoSize];
	mpCategoryCacheChildren = new CategoryVector*[mCategoryCacheByIdSize];

	// safety
	if (!mpCategoryCacheById || !mpCategoryCacheByOrderNo)
		return;

	// initialize the caches
	for (i = 0; i < mCategoryCacheByIdSize; i++)
		mpCategoryCacheById[i] = NULL;
	for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
		mpCategoryCacheByOrderNo[i] = NULL;
	for (i = 0; i < mCategoryCacheByIdSize; i++)
		mpCategoryCacheChildren[i] = NULL;

	// fill up the arrays
	for (i = 0, j = vCategories.begin(); j != vCategories.end(); j++)
	{
		mpCategoryCacheById[(*j)->GetId()] = *j;	// indexed by id
		mpCategoryCacheByOrderNo[i++] = *j;			// indexed by orderno
	}

	// fill up the children cache. the cache is indexed by id, but the categories inside
	//  the vector are sorted by orderno
	for (i = 0; i < mCategoryCacheByOrderNoSize; i++)
	{
		// get this category's parent
		parentId = mpCategoryCacheByOrderNo[i]->GetLevel1();
		
		// create the parent's children vector if it doesn't yet exist
		if (!mpCategoryCacheChildren[parentId])
			mpCategoryCacheChildren[parentId] = new CategoryVector;

		// add this category to the parent's children vector
		mpCategoryCacheChildren[parentId]->push_back(mpCategoryCacheByOrderNo[i]);
	}
		
	// cache is now fresh
	mDirtyCache = false;

}

clsCategoryFilters * clsCategoriesSite::GetCategoryFilters()
{
	if (!mpCategoryFilters)
		mpCategoryFilters = new clsCategoryFilters(mpMarketPlace);

	return mpCategoryFilters;
}

clsCategoryMessages * clsCategoriesSite::GetCategoryMessages()
{
	if (!mpCategoryMessages)
		mpCategoryMessages = new clsCategoryMessages(mpMarketPlace);

	return mpCategoryMessages;
}

//
// IsBuddyFlaggedCategory
//
bool clsCategoriesSite::IsBuddyFlaggedCategory(CategoryId id) const
{
	return mpCategoryCacheById[id]->GetIsFlagged();
}
