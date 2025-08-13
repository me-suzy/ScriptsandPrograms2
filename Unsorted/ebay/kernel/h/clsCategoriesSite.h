/*	$Id: clsCategoriesSite.h,v 1.1.6.2 1999/08/04 05:26:48 phofer Exp $	*/
//
//	File:	clsCategoriesSite.h
//
//	Class:	clsCategoriesSite
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Repository for all Categories of a site in a marketplace
//
// Modifications:
//				- 05/06/97 michael	- Created
//				- 07/08/97 tini		- modified for hierarchical categories
//				- 06/08/99 petra	- split off from clsCategories
//

#ifndef CLSCATEGORIESSITE_INCLUDED

#include "eBayTypes.h"
#include "clsCategory.h"
#include "clsCategoryFilters.h"
#include "clsCategoryMessages.h"

#include <vector.h>

// Typedefs
typedef vector<clsCategory *> CategoryVector;

// Class forward
class clsMarketPlace;
class clsCategory;
class ostream;

class clsCategoriesSite
{

	public:
		clsCategoriesSite(clsMarketPlace *pMarketPlace, int siteId);
		~clsCategoriesSite();
		
		//
		// GetCategory returns a category, by 
		// name or by number
		//
		clsCategory *GetCategory(CategoryId id, bool useCache = false);
		clsCategory *GetCategoryDefault(bool useCache = false);

		// Get parent of a category
		clsCategory *Parent(clsCategory *pCategory, bool useCache = false);

		// selects all the children and returns a category vector
		void Children(CategoryVector *pvCategories, clsCategory *pCategory, bool useCache = false);

		// selects all the children and returns a category vector - sorted
		void ChildrenSorted(CategoryVector *pvCategories, clsCategory *pCategory);

		// finds all top level categories
		void TopLevel(CategoryVector *pvCategories, bool useCache = false);

		// find all descendants of a category - ordered & depth-first.
		// if pCategory==NULL, return all categories & subcategories
		void DescendantsOrdered(CategoryVector *pvCategories, clsCategory *pParent);

		// list all categories in no particular order
		void All(CategoryVector *pvCategories, bool useCache = false);

		// list all categories sorted in traversal order
		// only used in BuildAdvancedSearchage which nobody admits to owning..?
		void AllSorted(CategoryVector *pvCategories, bool useCache = false);

		// list all leaf categories in no order
		void Leaves(CategoryVector *pvCategories, bool useCache = false);

/*		//
		// IsBidderMaskedCategory
		//
		bool	IsBidderMaskedCategory(CategoryId id) const;

		//
		// GetBidderMaskedCategories
		//
		void	GetBidderMaskedCategories(CategoryVector * const pvCategories) const;
*/
		//
		// IsBuddyFlaggedCategory
		//
		bool	IsBuddyFlaggedCategory(CategoryId id) const;
/*
		//
		// GetBuddyFlaggedCategories
		//
		void	GetBuddyFlaggedCategories(CategoryVector * const pvCategories) const;
*/
		//
		// GetCategoryFilters
		//
		clsCategoryFilters *GetCategoryFilters();

		//
		// GetCategoryMessages
		//
		clsCategoryMessages *GetCategoryMessages();

	private:
		// Category cache management
		void PurgeCategoryCaches();
		void PopulateCategoryCaches();
		//
		// Parent MarketPlace
		//
		clsMarketPlace	*mpMarketPlace;

		// Filters and messages applicable to category
		clsCategoryFilters	*mpCategoryFilters;
		clsCategoryMessages	*mpCategoryMessages;

		// for speed, the category cache consists of two arrays of pointers to categories
		//  and a flag indicating the dirtyness of the cache
		// also there's a cache for the Children() function now, called mpCategoryCacheChildren
		clsCategory**	mpCategoryCacheById;			// sorted and indexed by cat id
		clsCategory**	mpCategoryCacheByOrderNo;		// sorted and indexed by order no
		CategoryVector**	mpCategoryCacheChildren;	// indexed by cat id (each vector is a vector of categories representing the children of the cat id)
		int				mCategoryCacheByIdSize;			// will be equal to the max category id
		int				mCategoryCacheByOrderNoSize;	// will be equal to the number of categories
		bool			mDirtyCache;					// if true, then the cache needs updating

		int mSiteId;		// this is the site this class hold the categories for
};

#define CLSCATEGORIESSITE_INCLUDED 1
#endif CLSCATEGORIESSITE_INCLUDED
