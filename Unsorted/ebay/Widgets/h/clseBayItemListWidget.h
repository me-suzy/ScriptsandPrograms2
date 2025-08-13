/* $Id: clseBayItemListWidget.h,v 1.2 1998/10/16 01:00:59 josh Exp $ */
// Draws a list of items -- either seller or bidder list.
#ifndef clseBayItemListWidget_h
#define clseBayItemListWidget_h

#include "clseBayTableWidget.h"
#include "clsItem.h"

class clsUserIdWidget;

class clseBayItemListWidget : public clseBayTableWidget
{
public:

    // Construct via a blob.
    clseBayItemListWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

	// clseBayItemListWidget destructor
	virtual ~clseBayItemListWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
    void SetCategory(int category)              { mCategory = category; }
    void SetSortOrder(int sortOrder)            { mSortOrder = sortOrder; }

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayItemListWidget(pHandler, pMarketPlace, pApp); }

protected:
	
	bool Initialize();

	// Print an Item of the item list
	bool EmitCell(ostream *pStream, int n);

private:

    int                 mCategory;
    int                 mSortOrder;
    int                 mSince;
    bool                mBidList;   // Show our bid items, rather than our selling items.
	clsUser				*mpUser;		  // The user who ask his item list
	clsUserIdWidget		*mpUserIdWidget;  // The User ID Widget used for item list
    ItemList            mItemList;
    ItemList::iterator  mItemListIter;
    int                 mIterPosition;

    enum
    {
        cellItemNumber = 0,
        cellItemStart = 1,
        cellItemEnd = 2,
        cellItemPrice = 3,
        cellItemTitle = 4,
        cellItemHighBidder = 5
    };
};

#endif /* clseBayItemListWidget_h */