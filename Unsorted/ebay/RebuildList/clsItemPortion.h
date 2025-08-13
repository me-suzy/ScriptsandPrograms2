/*	$Id: clsItemPortion.h,v 1.3 1999/02/21 02:23:59 josh Exp $	*/
//
//	File:	clsItemPortion.h
//
//	Class:	clsItemPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Listing the child items for the current category
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#ifndef CLSITEMPORTION_INCLUDED
#define CLSITEMPORTION_INCLUDED

class clsListingItem;
class clsFileName;

//typedef vector<clsListingItem*>	ListingItemVector;

class clsItemPortion
{
public:
	// Constructor
	clsItemPortion(clsCategory* pCurrentCategory,
				   ListingItemVector*	pItems,
				   TimeCriterion ItemTime,
				   ItemType ItemType,	// e.g. Featured, or hot
				   int NumberItemPerPage,
				   bool	HasFeaturedOrHot = true);

	~clsItemPortion();

	// Retrieve items under the current category
	void Initialize();

	// Set the icon image path for the hot items
	void SetHotIconPath(char* pPath);

	// Set the icon image path for the new items
	void SetNewIconPath(char* pPath);

	// Print the items in the specifyed page to the file
	void Print(ostream* pOutputFile, int CurrentPage);

	// Check whethere there is more items to print
	bool MoreItems();

private:
	void PrintTitle(ostream* pOutputFile, int CurrentPage);
	void PrintCompletedItems(ostream* pOutputFile, int CurrentPage);
	void PrintNoCompletedItems(ostream* pOutputFile, int CurrentPage);
	bool IsNewItem(clsListingItem* pItem);
	bool IsHotItem(clsListingItem* pItem);

	clsCategory*	mpCategory;
	int				mNumberItemsPerPage;
	TimeCriterion	mItemsWanted;

	char*			mpHotIconPath;
	char*			mpNewIconPath;

	ListingItemVector*		mpItems;
	ListingItemVector::iterator	mIndex;

	TimeCriterion	mTimeStamp;
	ItemType		mType;
	clsRebuildListApp*	mpApp;

	bool			mHasFeaturedOrHot;
	time_t			mCreatingTime;
	clsMarketPlace* mpMarketPlace;

	clsFileName*	mpFileName;

	int				mHotItemCount;
	char			mHotItemCountText[6];
	char			mTimeName[4];

	char			mCompletedHeading[256];
};

#endif // CLSITEMPORTION_INCLUDED
