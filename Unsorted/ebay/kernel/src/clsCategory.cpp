/*	$Id: clsCategory.cpp,v 1.7.22.1.104.1 1999/08/01 03:02:18 barry Exp $	*/
//
//	File:	clsCategory.cpp
//
//	Class:	clsCategory
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all marketplaces
//
//	Modifications:
//				- 05/07/97 michael	- Created
//				- 01/07/97 tini		- revised to include hierarchy
//				- 05/11/99 jnace	- added region ID parameter to GetItemCountStillOpen
//				- 06/03/99 petra	- modified to include site IDs.
//							- cleaned up unused functions in an attempt to 
//							  make life easier.
//

#include "eBayKernel.h"

// Some convenient macros

#define ISTRING_METHODS(variable)					\
char *clsCategory::Get##variable()					\
{													\
	return mp##variable;							\
}													\
void clsCategory::Set##variable(char *pNew)			\
{													\
	delete[] mp##variable;						\
	mp##variable = new char[strlen(pNew) + 1];		\
	strcpy(mp##variable, pNew);						\
	return;											\
}													\

#define ICAT_METHODS(variable)						\
CategoryId clsCategory::Get##variable()				\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(CategoryId newval)	\
{													\
	m##variable	= newval;							\
	return;											\
}													\

#define IBOOL_METHODS(variable)						\
bool clsCategory::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(bool newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 

#define ICHAR_METHODS(variable)						\
char clsCategory::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(char newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 

#define ILONG_METHODS(variable)						\
long clsCategory::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(long newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 													

#define IDOUBLE_METHODS(variable)					\
double clsCategory::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(double newval)		\
{													\
	m##variable	= newval;							\
	return;											\
} 	

// petra added 06/03/99
#define IINT_METHODS(variable)						\
int clsCategory::Get##variable()					\
{													\
	return m##variable;								\
}													\
void clsCategory::Set##variable(int newval)			\
{													\
	m##variable	= newval;							\
	return;											\
} 	
												
//
// Default Constructors
//
clsCategory::clsCategory(CategoryId id)
{
	mId				= id;
	mpDescription	= NULL;
	mpName			= NULL;
	mpName1			= NULL;
	mpName2			= NULL;
	mpName3			= NULL;
	mpName4			= NULL;
	mpFileRef		= NULL;

	mAdult = '0';
	mIsLeaf = 0;
	mIsExpired = 0;

	mLevel1 = 0;
	mLevel2 = 0;
	mLevel3 = 0;
	mLevel4 = 0;

	mPrevSibling = 0;
	mNextSibling = 0;

	mFeaturedCost = 0.0;

	mModifiedDate = 0;
	mCreateDate = 0;
	mMarketPlaceId = 0;
	mSiteId = 0;	// petra
}

//
// Constructor with site Id
// petra added 06/03/99
//
clsCategory::clsCategory(CategoryId id,
						 int siteId)
{
	mId				= id;
	mpDescription	= NULL;
	mpName			= NULL;
	mpName1			= NULL;
	mpName2			= NULL;
	mpName3			= NULL;
	mpName4			= NULL;
	mpFileRef		= NULL;

	mAdult = '0';
	mIsLeaf = 0;
	mIsExpired = 0;

	mLevel1 = 0;
	mLevel2 = 0;
	mLevel3 = 0;
	mLevel4 = 0;

	mPrevSibling = 0;
	mNextSibling = 0;

	mFeaturedCost = 0.0;

	mModifiedDate = 0;
	mCreateDate = 0;
	mMarketPlaceId = 0;

	mSiteId = siteId;	// petra
}

//
// new with parent
//
clsCategory::clsCategory(MarketPlaceId marketplace,
						 CategoryId id, 
						 char *pName,
						 char *pDescription,
						 char	adult,
						 bool	isleaf,
						 bool   isexpired,
						 CategoryId level1,
						 CategoryId level2,
						 CategoryId level3,
						 CategoryId level4,	
						 char *pName1,
						 char *pName2,
						 char *pName3,
						 char *pName4,
						 CategoryId prevSibling,
						 CategoryId nextSibling,
						 double featuredCost,
						 long create_date,
						 char *pFileRef,
						 long modified_date,
						 bool maskBidders,
						 bool isFlagged,
						 int siteId)	// petra
{
	mMarketPlaceId = marketplace;
	mId			= id;
	mpName		= pName;
	mpDescription = pDescription;
	mAdult		= adult;
	mIsLeaf		= isleaf;
	mIsExpired	= isexpired;
	mLevel1		= level1;
	mLevel2		= level2;
	mLevel3		= level3;
	mLevel4		= level4;
	mpName1		= pName1;
	mpName2		= pName2;
	mpName3		= pName3;
	mpName4		= pName4;
	mPrevSibling	= prevSibling;
	mNextSibling	= nextSibling;
	mFeaturedCost	= featuredCost;
	mCreateDate		= create_date;
	mpFileRef		= pFileRef;
	mModifiedDate	= modified_date;
	mMaskBidders	= maskBidders;
	mIsFlagged		= isFlagged;
	mSiteId		= siteId;	// petra

	return;
}

//
// Destructor
//
clsCategory::~clsCategory()
{
	delete []	mpDescription;
	mpDescription	= NULL;
	delete []	mpName;
	mpName			= NULL;
	delete []	mpName1;
	mpName1			= NULL;
	delete []	mpName2;
	mpName2			= NULL;
	delete []	mpName3;
	mpName3			= NULL;
	delete []	mpName4;
	mpName4			= NULL;
	delete []	mpFileRef;
	mpFileRef		= NULL;
	return;
}

// petra removed clsCategory::UpdateCategory (see E117 to restore)

//
// Name getter and setter
//

char *clsCategory::GetName()
{
	return	mpName;
}

// name doesn't change after initial setting?
void clsCategory::SetName(char *pName)
{
	mpName = pName;
}


//
// Marketplace getter and setter
//
MarketPlaceId clsCategory::GetMarketPlaceId()
{
	return mMarketPlaceId;
}

void clsCategory::SetMarketPlaceId(MarketPlaceId id)
{
	mMarketPlaceId = id;
}


CategoryId clsCategory::GetParent()
{
	if (mLevel1 != 0)
	{
		// Is not a top-level category. Return its parent.
		return mLevel1;
	}
	else
	{
		// Is a top-level category. It is its own parent. Return itself.
		return mId;
	}
}  /* clsCategory::GetParent */


bool clsCategory::BelongsToSubtreeOf(CategoryId id)
{
	if ((id == mId)     ||
		(id == mLevel1) ||
		(id == mLevel2) ||
		(id == mLevel3) ||
		(id == mLevel4))
	{
		return true;
	}

	return false;
}  /* clsCategory::BelongsToSubtreeOf */
	
	
CategoryId clsCategory::GetRootCategory()
{
	int  level;

	level = catLevel();
	switch (level)
	{
		case 1:
			return mId;
		case 2:
			return mLevel1;
		case 3:
			return mLevel2;
		case 4:
			return mLevel3;
		case 5:
			return mLevel4;
		default:
			// Error situation - should never happen.
			return 0;
	}
}  /* clsCategory::GetRootCategory */


// returns category level in hierarchy
int clsCategory::catLevel()
{
	if (mLevel1 == 0) 
		return 1;
	else if (mLevel2 == 0)
		return 2;
	else if (mLevel3 == 0)
		return 3;
	else if (mLevel4 == 0)
		return 4;
	else
		return 5;
}


// returns true if leaf category
bool clsCategory::isLeaf()
{
	return GetIsLeaf();
}

// returns true if already at bottom
bool clsCategory::isBottomLevel()
{
	// need to check
	return (mLevel3 > 0);
}

bool clsCategory::isAdult()
{
	return (mAdult == '1');
}

bool clsCategory::NoBidAndListForMinor()
{
	return (mAdult == '2');
}

// fills item vector with items in the category with ending date
// before endDate. This will return items across all sites since we
// don't store a site id with the item.
void clsCategory::GetItems(ItemVector *pvItems, long endDate,
						   ItemListSortEnum SortCode /* = SortItemsByUnknown */)
{
	gApp->GetDatabase()->GetCategoryItems(
										GetMarketPlaceId(),
										GetId(),
										1,
										endDate,
										pvItems,
										SortCode
										);
}

// petra removed clsCategory::GetItemsCompleted (see E117 to restore)

// petra removed clsCategory::GetItemsByCategoryCount (see ? to restore; wasn't in E117;
// 						       was commented out in E118_prod_intl3_de1)

// return number ofcategories under the category including itself
int clsCategory::GetNumberOfDescendants()
{
	return gApp->GetDatabase()->GetCategoryCountInCategory(GetMarketPlaceId(),
								mId);
}

// returns the number of items in the category with the ending date
// before endDate
// this returns the number of items across all sites - we don't store
// a site id with the item
int clsCategory::GetItemCount(long endDate)
{
	return gApp->GetDatabase()->GetCategoryItemsCount(
										GetMarketPlaceId(),
										GetId(),
										1,
										0,
										endDate
										);
}

// petra removed clsCategory::GetItemCompletedCount (see E117 to restore)

// petra removed clsCategory::GetItemCountStartAfter (see E117 to restore)

// petra removed clsCategory::GetItemCountBetween (see E117 to restore)

// petra removed clsCategory::GetCategoryItemsCurrentCount (see E117 to restore)

// petra removed clsCategory::GetCategoryItemsStartCount (see E117 to restore)

// petra removed clsCategory::GetCategoryItemsEndingCount (see E117 to restore)

// petra removed clsCategory::GetCategoryItemsCompletedCount (see E117 to restore)

// petra removed clsCategory::GetCategoryItemsGoingCount (see E117 to restore)

// this returns the count across all sites since we don't store a site id
// with the item
int clsCategory::GetItemCountStillOpen(int iRegionID /*=0*/)
{
	clsCategories *pCategories;
	vector<int> *pvCategoryCounts;
	int *pChildren;
	int total;
	int i;

	pCategories = gApp->GetMarketPlaces()->
		GetCurrentMarketPlace()->GetCategories();

	pvCategoryCounts = pCategories->GetCategoryOpenItemCounts(iRegionID);
	pCategories->GetChildLeafCategoryIds(GetId(), &pChildren);

	total = 0;
	
	for (i = 0; pChildren[i] != -1; ++i)
	{
		total += (*pvCategoryCounts)[pChildren[i]];		//lint !e55 !e56 !e48 Lint says "bad type". Hooey.
	}

	delete [] pChildren;

	return total;
}

//tell us if category is special priced
bool clsCategory::CheckForAutomotiveListing(int nCategory)
{
	if (nCategory==0)
		nCategory=mId;

	// return true beginning on 4/15/99
	if ((nCategory==1258 || nCategory==1259 || 
		nCategory==1260 || nCategory==2030) &&
		(clsUtilities::CompareTimeToGivenDate(time(0), 4, 24, 99, 0, 0, 0) >= 0))
	{

		return true;
	}

	return false;
}


// check for real estate listing
bool clsCategory::CheckForRealEstateListing(int nCategory)
{
	if (nCategory==0)
		nCategory=mId;

	// return true beginning on 4/15/99
	if ((nCategory==1607) &&		
		(clsUtilities::CompareTimeToGivenDate(time(0), 4, 24, 99, 0, 0, 0) >= 0))
	{

		return true;
	}

	return false;
}


bool clsCategory::GetScreenItems()
{
	bool screen = mIsFlagged;

	clsMarketPlace *pMarketPlace;
	CategoryVector vAncestors;
	CategoryVector::iterator i;

	if (!screen)
	{
		// make sure we have a valid marketplace pointer
		pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();
		if (pMarketPlace == NULL)
			return false;

		// get this category's ancestors
		pMarketPlace->GetCategories()->Ancestors(&vAncestors, this, true);

		// see if any of the ancestor categories is flagged
		for (i = vAncestors.begin(); i != vAncestors.end(); ++i)
		{
			if ((*i)->GetIsFlagged())
			{
				screen = true;
				break;
			}
		}

		// delete the vector of ancestor categories, but not the pointers
		// within, cuz they point directly into the cache!!!
		vAncestors.erase(vAncestors.begin(), vAncestors.end());
	}

	return screen;
}


// standard getters and setters

  ICHAR_METHODS(Adult);
  IBOOL_METHODS(IsLeaf);
  IBOOL_METHODS(IsExpired);

  ISTRING_METHODS(Description);
  ICAT_METHODS(Id);
  ICAT_METHODS(Level1);
  ICAT_METHODS(Level2);
  ICAT_METHODS(Level3);
  ICAT_METHODS(Level4);
  ISTRING_METHODS(Name1);
  ISTRING_METHODS(Name2);
  ISTRING_METHODS(Name3);
  ISTRING_METHODS(Name4);
  ICAT_METHODS(PrevSibling);
  ICAT_METHODS(NextSibling);
  IDOUBLE_METHODS(FeaturedCost);
  ISTRING_METHODS(FileRef);
  ILONG_METHODS(CreateDate);
  ILONG_METHODS(ModifiedDate);

  IBOOL_METHODS(MaskBidders);
  IBOOL_METHODS(IsFlagged);
  
  IINT_METHODS(SiteId);	// petra