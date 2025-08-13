/*	$Id: clsCategory.h,v 1.6.22.1.104.1 1999/08/01 03:02:05 barry Exp $	*/
//
//	File:	clsCategory.h
//
//	Class:	clsCategory
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Representation of a category
//
// Modifications:
//				- 02/07/97 michael	- Created
//
//				- 11/19/97 charles added
//						GetCategoryItemsCurrentCount()
//						GetCategoryItemsStartCount()
//						GetCategoryItemsEndingCount()
//						GetCategoryItemsCompletedCount()
//						GetCategoryItemsGoingCount()
//				- 05/11/99 jnace	- added region ID parameter to GetItemCountStillOpen
//				- 06/03/99 petra	- modified to include site ID. cleaned up unused functions.
//
//

#ifndef CLSCATEGORY_INCLUDED
#include "eBayTypes.h"
#include "vector.h"

#include "clsItem.h"	// For ItemVector
#include "clsItems.h"	// for sorting items
// class forward
class clsCategory;

typedef enum
{
	CategoryFlagScreenItems			= 0x00000001,
	CategoryFlagMaskBidders			= 0x00000002
} CategoryFlagEnum;

// Some convienent macros
#define CCAT_VARIABLE(name)					\
private:									\
	CategoryId		m##name;				\
public:										\
	CategoryId		Get##name();			\
	void	Set##name(CategoryId new_value);

#define CINT_VARIABLE(name)					\
private:									\
	int		m##name;						\
public:										\
	int		Get##name();					\
	void	Set##name(int new_value);

#define CSTRING_VARIABLE(name)				\
private:									\
	char	*mp##name;						\
public:										\
	char	*Get##name();					\
	void	Set##name(char *pNew);

#define  CBOOL_VARIABLE(name)				\
private:									\
	bool	m##name;						\
public:										\
	bool	Get##name();					\
	void	Set##name(bool new_value);

#define  CCHAR_VARIABLE(name)				\
private:									\
	char	m##name;						\
public:										\
	char	Get##name();					\
	void	Set##name(char new_value);

#define  CLONG_VARIABLE(name)				\
private:									\
	long	m##name;						\
public:										\
	long	Get##name();					\
	void	Set##name(long new_value);

#define  CDOUBLE_VARIABLE(name)				\
private:									\
	double	m##name;						\
public:										\
	double	Get##name();					\
	void	Set##name(double new_value);

// petra added 06/03/99

#define  CINT_VARIABLE(name)				\
private:									\
	int	m##name;							\
public:										\
	int	Get##name();						\
	void	Set##name(int new_value);

class clsCategory
{

	public:
	//
	// vanilla ctor
	//
    clsCategory(CategoryId id);

	// petra ctor that takes a site ID
	clsCategory (CategoryId id,
					int siteId);

	//
	// with parent; assume add to last sibling of parent
	//
	clsCategory(CategoryId id, 
			    char *pName,
			    CategoryId parent);

	// full blown constructor
	clsCategory::clsCategory(MarketPlaceId marketplace,
				CategoryId id, 
				char *pName,
				char *pDescription,
				char	adult,
				bool	isleaf,
				bool	isexpired,
				CategoryId level1,
				CategoryId level2,
				CategoryId level3,
				CategoryId level4,
				char *pname1,
				char *pname2,
				char *pname3,
				char *pname4,
				CategoryId prevSibling,
				CategoryId nextSibling,
				double featuredCost,
				long createDate,
				char *pFileRef,
				long modifiedDate,
				bool maskBidders,
				bool isFlagged,
				int siteId);	// petra
						 
	// DTOR
	~clsCategory();

	// petra removed UpdateCategory

	// getters and setters

	//
	// GetName - Returns the name of the Category
	//		

	char *GetName();
	void SetName(char *pName);

	MarketPlaceId	GetMarketPlaceId();
	void			SetMarketPlaceId(MarketPlaceId id);

	// Returns the category id of the parent.
	// A top-level category is its own parent.
	CategoryId GetParent();

	// Returns the top-level category's category id.
	CategoryId GetRootCategory();

	// Returns true if this category is in the subtree of category 'id', ie. if
	// 'id' category is a parent or grand-parent or so on of this category.
	// Returns false otherwise.
	// Remember, a category belongs to its own sub-tree.
	bool BelongsToSubtreeOf(CategoryId id);

	// returns category level in hierarchy
    int catLevel();

	// returns true if leaf category
    bool isLeaf();

	// returns true if already at bottom
	bool isBottomLevel();

	// can't bid or list or view
	bool isAdult();

	// can't bid or list, but can view
	bool NoBidAndListForMinor();

	// fills item vector with items in the category with ending date
	// before endDate
	void GetItems(ItemVector *pItemVector, long endDate,
						   ItemListSortEnum SortCode = SortItemsByUnknown);

	// petra removed GetItemsCompleted


	// return number of descendant categories include itself
	int GetNumberOfDescendants();

	// returns the number of items in the category with the ending date
	// before endDate
	int GetItemCount(long endDate);
	// petra removed GetItemCompletedCount
	// petra removed GetItemCountStartAfter
	// petra removed GetItemCountBetween
	int GetItemCountStillOpen(int iRegionID = 0);


	//
	// returns the number of items in the category
	// current, new today, ending today, completed, going
	//
	// petra removed GetCategoryItemsCurrentCount
	// petra removed GetCategoryItemsStartCount
	// petra removed GetCategoryItemsEndingCount
	// petra removed GetCategoryItemsCompletedCount
	// petra removed GetCategoryItemsGoingCount

	//tell us if category is special priced
	bool  CheckForAutomotiveListing(int nCategory=0);
	bool  CheckForRealEstateListing(int nCategory=0);

	//
	// returns true if items listed in this category must be screened
	// (either this category or an ancestor of this category is flagged)
	//
	bool GetScreenItems();
	
	MarketPlaceId	mMarketPlaceId;	

	CCHAR_VARIABLE(Adult);
	CBOOL_VARIABLE(IsLeaf);
	CBOOL_VARIABLE(IsExpired);

	CCAT_VARIABLE(Id);
	CCAT_VARIABLE(Level1);
	CCAT_VARIABLE(Level2);
	CCAT_VARIABLE(Level3);
	CCAT_VARIABLE(Level4);
	CSTRING_VARIABLE(Name1);
	CSTRING_VARIABLE(Name2);
	CSTRING_VARIABLE(Name3);
	CSTRING_VARIABLE(Name4);
	CCAT_VARIABLE(PrevSibling);
	CCAT_VARIABLE(NextSibling);
	CDOUBLE_VARIABLE(FeaturedCost);
	CSTRING_VARIABLE(FileRef);
	CSTRING_VARIABLE(Description);
	CLONG_VARIABLE(ModifiedDate);
	CLONG_VARIABLE(CreateDate);

	CBOOL_VARIABLE(MaskBidders);
	CBOOL_VARIABLE(IsFlagged);
	CINT_VARIABLE(SiteId);		// petra

  private:
	char				*mpName;
		
};

#define CLSCATEGORY_INCLUDED 1
#endif CLSCATEGORY_INCLUDED
