/*	$Id: clsCategories.h,v 1.8.22.2.104.4 1999/08/04 04:28:41 phofer Exp $	*/
//
//	File:	clsCategories.h
//
//	Class:	clsCategories
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Repository for all sites of a marketplace holding categories
//
// Modifications:
//				- 05/06/97 michael	- Created
//				- 07/08/97 tini		- modified for hierarchical categories
//				- 05/11/99 jnace	- added region ID parameter to GetCategoryOpenItemCounts
//				- 06/08/99 petra	- split off clsCategoriesSite
//				- 08/03/99 nsacco	- default site to SITE_EBAY_MAIN
//

#ifndef CLSCATEGORIES_INCLUDED

#include "eBayTypes.h"
#include "clsCategoriesSite.h"
#include "clsCategory.h"

#include <vector.h>

// Typedefs
typedef vector<clsCategory *> CategoryVector;

// Class forward
class clsMarketPlace;
class clsCategory;
class ostream;
class clsCategoriesSite;
class clsSites;
class clsListingFileName;

// used to return qualified name of a category
// qualified name is the name of the ancestor(s) as well as the category itself
class clsQualifiedCategoryName
{
public:
	char *names[5];
	CategoryId ids[5];

	clsQualifiedCategoryName(char *pName0,
							char *pName1,
							char *pName2,
							char *pName3,
							char *pName4,
							CategoryId pId0,
							CategoryId pId1,
							CategoryId pId2,
							CategoryId pId3,
							CategoryId pId4);

	~clsQualifiedCategoryName();
		
};

class clsCategories
{

	public:
		clsCategories(clsMarketPlace *pMarketPlace);
		~clsCategories();
		
		//
		// GetCategory returns a category, by 
		// name or by number
		//
		clsCategory *GetCategory(CategoryId id, bool useCache = false);
		clsCategory *GetCategoryDefault(bool useCache = false);
		clsCategory *GetCategoryRoot();
		clsCategory *GetCategoryFirstChild(clsCategory *pCategory);

		
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

		// Gets all ancestors of a given category
		void Ancestors(CategoryVector *pvCategories, clsCategory *pCategory, bool useCache = false);

		// list all categories in no particular order
		void All(CategoryVector *pvCategories, bool useCache = false);

		// list all categories sorted in traversal order
		// petra only used in BuildAdvancedSearchPage which nobody admits to owning..?
		void AllSorted(CategoryVector *pvCategories, bool useCache = false);

		// list all leaf categories in no order
		void Leaves(CategoryVector *pvCategories, bool useCache = false);

		// List of not leaf category ids
        void GetNotLeafCategoryIds(vector<int> *pvCatIds);


		// Gets fully qualified category name (up to 4 levels only)
		clsQualifiedCategoryName *GetQualifiedName(clsCategory *pCategory);

		void EmitHTMLQualifiedName(ostream *pStream, clsCategory *pCategory);

		// Emit a HTML selection list (drop down) of all leaf categories.
		// It's here because we use this for both Item functionality 
		// and for user Interests
		void EmitHTMLLeafSelectionList(ostream *pStream,
									   char *pListName,
									   CategoryId selectedValue,
									   char *pUnSelectedValue,
									   char *pUnSelectedLabel,
									   CategoryVector *vCategories,
										bool includeNonLeaves = false, bool useCache = false);

		// Emit HTML selection list of all top level categories.
		void EmitHTMLTopSelectionList(ostream *pStream,
											  char *pListName,
											  CategoryId selectedValue,	
											  char *pUnSelectedValue,
											  char *pUnSelectedLabel,
											  CategoryVector *vCategories, bool useCache = false);

		// Emits an HTML drop-down menu for *each* top-level category,
		// so that users don't have to wade through hundreds of
		// leaf categories.
		// Uses a table for layout
		void EmitHTMLLeafSelectionMultipleDropdown(ostream *pStream,
										CategoryId selectedValue,
										CategoryVector *vCategories,
										int numColumns,
										char *menuNamePrefix  = "category",
										bool includeNonLeaves = false, bool useCache = false);

		// Emit javascript version of category selector
		void EmitHTMLJavascriptCategorySelector(ostream *pStream, char *cFormName, char* cOption, 
									char* cCategoryName, CategoryId selectedValue, 
									bool emitCategoryArray);

		/* petra: not used
		void EmitHTMLCategoriesRadio(ostream *pStream,
									 char *pListName,
									 CategoryVector *vCategories, bool useCache = false);
		*/

		void EmitHTMLCategoriesList(ostream *pStream,
								    char *pListName,
									CategoryVector *vCategories, bool useCache = false);

		// returns the link for the category
		const char* GetLinkPath(int CategoryId, 
							   TimeCriterion TimeStamp = LISTING,
							   int PageNumber =1);
		// returns the link for the category
		const char* GetLinkPath(clsCategory* pCategory, 
							   TimeCriterion TimeStamp = LISTING,
							   int PageNumber =1);

		// returns the relative link for the category
		const char* GetRelativeLinkPath(int CategoryId, 
							   TimeCriterion TimeStamp = LISTING,
							   int PageNumber =1);
		// returns the relative link for the category
		const char* GetRelativeLinkPath(clsCategory* pCategory, 
							   TimeCriterion TimeStamp = LISTING,
							   int PageNumber =1);

		// return total number of categories
		int GetCategoryCount();

		// return Maximum category id
		int GetMaxCategoryId();

		// Get first two level category count
		int GetFirstTwoLevelCategoryCount();

		// Get Child Leaf Categories
		void GetChildLeafCategoryIds(int CatId, int** pIds);

		// Get open item counts for all categories, optionally for a given region
		vector<int> *GetCategoryOpenItemCounts(int iRegionID = 0);
		void ClearOpenItemCounts();

		// Checks the adultness of a category
		bool IsAdultCategory(CategoryId catId);

        bool IsDescendant(CategoryId parent, CategoryId child);

		//
		// IsBidderMaskedCategory
		//
		bool	IsBidderMaskedCategory(CategoryId id) const;

		//
		// GetBidderMaskedCategories
		//
		void	GetBidderMaskedCategories(CategoryVector * const pvCategories) const;

		//
		// FlagCategory
		//
		void	FlagCategory(CategoryId id, bool on);

		//
		// IsBuddyFlaggedCategory
		//
		bool	IsBuddyFlaggedCategory(CategoryId id);

		//
		// GetBuddyFlaggedCategories
		//
		void	GetBuddyFlaggedCategories(CategoryVector * const pvCategories) const;

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
		void PurgeCategoriesSiteCache();
		void PopulateCategoriesSiteCache();
		//
		// Parent MarketPlace
		//
		clsMarketPlace	*mpMarketPlace;

		// vector for caching the count of open items per category
		vector<int> *mpvCategoryOpenItemCounts;
		// the region that mpvCategoryOpenItemCounts was calculated for (0=all regions)
		int				mRegionID;

		// a vector of categoryids for caching adult categories
		vector<CategoryId> mvAdultCategories;

		//
		// Listing file name
		//
		clsListingFileName	*mpListingFileName;

		// for speed, the category site cache consists of an array of pointers to category sites
		//  and a flag indicating the dirtyness of the cache
		clsCategoriesSite**	mpCategoriesSiteCache;
		int					mCategoriesSiteCacheSize;			// will be equal to the max site id
		bool				mDirtyCache;					// if true, then the cache needs updating


};

#define CLSCATEGORIES_INCLUDED 1
#endif CLSCATEGORIES_INCLUDED
