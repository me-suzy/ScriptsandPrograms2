/* $Id: clseBayItemListWidget.cpp,v 1.5.258.1 1999/08/01 02:51:26 barry Exp $ */
// Widget to print a list of items.
#include "widgets.h"
#include "clseBayItemListWidget.h"

struct clseBayItemListWidgetOptions
{
    int32_t mCategory;
    int32_t mSortOrder;
    int32_t mTableOptionsOffset;
    int32_t mExpansionOffset;
    int32_t mBidList;
    int32_t mSince;
};

// This class partitions out items which are in a specific category
// or its subcategories.
class clseBayItemListWidgetPartition
{
public:
    clseBayItemListWidgetPartition(int category) : mCategory(category),
        mpCategories(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCategories())
    { }

    int mCategory;
    clsCategories *mpCategories;

    bool operator()(const clsItemPtr &theItem) const
    {  return mCategory == theItem.mpItem->GetCategory() || mpCategories->IsDescendant(mCategory, theItem.mpItem->GetCategory()); }
};

// No adult items allowed on these pages.
bool no_adult_items(const clsItemPtr &theItem)
{
    return !(theItem.mpItem->IsAdult());
}

clseBayItemListWidget::clseBayItemListWidget(clsWidgetHandler *pHandler,
                                             clsMarketPlace *pMarketPlace,
                                             clsApp *pApp) : clseBayTableWidget(pHandler, pMarketPlace, pApp),
                                             mCategory(0),
                                             mSortOrder(SortItemsByStartTime),
                                             mSince(-1),
                                             mBidList(false),
                                             mpUser(NULL),
                                             mpUserIdWidget(NULL),
                                             mItemListIter(mItemList.end()),
                                             mIterPosition(-1)
{
    // Set some default formatting.
    // The user can override this if they want.
    mCellSpacing = -1;
    mCellPadding = -1;
    mTableWidth = 0;
    mBorder = 1;
}

clseBayItemListWidget::~clseBayItemListWidget()
{
    for (mItemListIter = mItemList.begin(); mItemListIter != mItemList.end(); ++mItemListIter)
        delete (*mItemListIter).mpItem;

    delete mpUserIdWidget;
	mpUser = NULL;
}

// For translation to and from text.
void clseBayItemListWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pValue;
    int i;

	pValue = GetParameterValue("SORT", pvArgs);
    if (pValue)
    {
        i = atoi(pValue);
        if (i > SortItemsByUnknown && i < SortItemsByBADVALUE)
            mSortOrder = i;
    }
	
	pValue = GetParameterValue("CATEGORY", pvArgs);
    if (pValue)
    {
        i = atoi(pValue);
        if (i > 0)
            mCategory = i;
    }

    if (GetParameterValue("BIDS", pvArgs))
        mBidList = true;
	
	pValue = GetParameterValue("SINCE", pvArgs);
    if (pValue)
    {
        i = atoi(pValue);
        if (i != 0)
            mSince = i;
    }

    clseBayTableWidget::SetParams(pvArgs);
}

// This translates from binary saved form to 'live' form.
void clseBayItemListWidget::SetParams(const void *pData, 
                                      const char *pStringBase, 
                                      bool mFixBytes)
{
    clseBayItemListWidgetOptions *pOptions;

    pOptions = (clseBayItemListWidgetOptions *) pData;

    if (mFixBytes)
    {
        pOptions->mCategory = clsUtilities::FixByteOrder32(pOptions->mCategory);
        pOptions->mSortOrder = clsUtilities::FixByteOrder32(pOptions->mSortOrder);
        pOptions->mTableOptionsOffset = clsUtilities::FixByteOrder32(pOptions->mTableOptionsOffset);
        pOptions->mBidList = clsUtilities::FixByteOrder32(pOptions->mBidList);
        pOptions->mSince = clsUtilities::FixByteOrder32(pOptions->mSince);
        
        // Expansion is not used yet.
//        pOptions->mExpansionOffset = clsUtilities::FixByteOrder32(pOptions->mExpansionOffset);
    }

    mCategory = pOptions->mCategory;
    mSortOrder = pOptions->mSortOrder;

    if (pOptions->mBidList)
        mBidList = true;
    if (pOptions->mSince)
        mSince = pOptions->mSince;

    clseBayTableWidget::SetParams((const void *) (pStringBase + pOptions->mTableOptionsOffset),
        pStringBase, mFixBytes);
}

// This translates from 'live' form to binary saved form.
long clseBayItemListWidget::GetBlob(clsDataPool *pDataPool, bool mReverseBytes)
{
    clseBayItemListWidgetOptions theOptions;

    theOptions.mCategory = mCategory;
    theOptions.mSortOrder = mSortOrder;
    theOptions.mExpansionOffset = -1;
    theOptions.mBidList = mBidList;
    theOptions.mSince = mSince;

    theOptions.mTableOptionsOffset = clseBayTableWidget::GetBlob(pDataPool, mReverseBytes);

    if (mReverseBytes)
    {
        theOptions.mCategory = clsUtilities::FixByteOrder32(theOptions.mCategory);
        theOptions.mSortOrder = clsUtilities::FixByteOrder32(theOptions.mSortOrder);
        theOptions.mTableOptionsOffset = clsUtilities::FixByteOrder32(theOptions.mTableOptionsOffset);
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
        theOptions.mBidList = clsUtilities::FixByteOrder32(theOptions.mBidList);
        theOptions.mSince = clsUtilities::FixByteOrder32(theOptions.mSince);
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

void clseBayItemListWidget::DrawTag(ostream *pStream, const char *pName, bool comments /* = true */)
{
	if (comments)
		*pStream << "\n <!-- Items for sale --> \n";

	*pStream << "<"
			 << pName;


	clseBayTableWidget::DrawOptions(pStream);

	*pStream << " SORT=" << mSortOrder;

	if (mCategory)
		*pStream << " CATEGORY=" << mCategory;

	if (mBidList)
		*pStream << " BIDS";

	*pStream << " SINCE=" << mSince;

	*pStream << ">";

	if (comments)
		*pStream << "\n";
}

bool clseBayItemListWidget::Initialize()
{
    ItemList::iterator i;
    
	SetNumCols(6);

	// safety
	if (!mpMarketPlace) 
        return false;

	// Create the User ID Widget
	mpUserIdWidget = new clsUserIdWidget(mpMarketPlace, GetApp());

    mpUser = mpWidgetHandler->GetWidgetContext()->GetUser();

	// safety
	if (!mpUser) 
        return false;

    // Let's get the items listed by the user. We need 'more stuff' for 'adult'.
    if (mBidList)
        mpUser->GetBidItems(&mItemList, mSince, true, (ItemListSortEnum) mSortOrder);
    else
        mpUser->GetListedItems(&mItemList, mSince, true, (ItemListSortEnum) mSortOrder);

    // If we're cutting up by category, do that now.
    if (!mItemList.empty() && mCategory)
    {
//		if (mCategory)
		{
			mItemListIter = stable_partition(mItemList.begin(), mItemList.end(), clseBayItemListWidgetPartition(mCategory));
			for (i = mItemListIter; i != mItemList.end(); ++i)
				delete (*i).mpItem;
			mItemList.erase(mItemListIter, mItemList.end());
		}

		// Check again, in case the category partition forced us empty.
		if (!mItemList.empty())
		{
			mItemListIter = stable_partition(mItemList.begin(), mItemList.end(), no_adult_items);
			for (i = mItemListIter; i != mItemList.end(); ++i)
				delete (*i).mpItem;
			mItemList.erase(mItemListIter, mItemList.end());
		}
    }

	// Number of items, we add an extra mNumCols for the titling.
	mNumItems = mItemList.size() * mNumCols + mNumCols;

	return true;
}

	// Print an Item of the item list
bool clseBayItemListWidget::EmitCell(ostream *pStream, int n)
{
    int whichItem;
    clsItem *pItem;
// petra    time_t						theTime;
// petra    struct tm					*pTheTime;
	clseBayTimeWidget			timeWidget (mpMarketPlace, 0, 0);	// petra
	
// petra    char						cTime[32];

    // We subtract one so that our first row can be titling.
    whichItem = n / mNumCols - 1;

    if (mIterPosition != whichItem)
    {
        if (mIterPosition == -1)
        {
            mIterPosition = 0;
            mItemListIter = mItemList.begin();
        }
        else
        {
            for ( ; mIterPosition < whichItem; ++mIterPosition, ++mItemListIter)
                if (mItemListIter == mItemList.end())
                    return false;
        }

        // Prepare the stream
        pStream->setf(ios::fixed, ios::floatfield);
        pStream->setf(ios::showpoint, 1);
        pStream->precision(2);
    }

    // Draw out our entire title bar at once.
    if (whichItem == -1)
    {
        if (!(n % mNumCols))
        {
            *pStream << "<td><tr>\n"
						"<th BGCOLOR=\"#CCCCCC\" align=center>Item</th>"
                        "<th BGCOLOR=\"#CCCCCC\" align=center>Start</th>"
                        "<th BGCOLOR=\"#CCCCCC\" align=center>End</th>"
                        "<th BGCOLOR=\"#CCCCCC\" align=center>Price</th>"
                        "<th BGCOLOR=\"#CCCCCC\" align=center>Title</th>"
                        "<th BGCOLOR=\"#CCCCCC\" align=center>High Bidder</th>"
						"</tr></td>\n";
        }

        return true;
    }

    pItem = (*mItemListIter).mpItem;

    switch (n % mNumCols)
    {
    case cellItemNumber:
        *pStream << "<td><a href=\""
                 << mpMarketPlace->GetCGIPath(PageViewItem)
                 << "eBayISAPI.dll?ViewItem&item="
                 << pItem->GetId()
                 << "\">"
                 << pItem->GetId()
                 << "</a></td>";
        break;

    case cellItemStart:
		timeWidget.SetTime (pItem->GetStartTime() );	// petra
		timeWidget.SetDateTimeFormat (1, -1);		// petra
// petra        theTime = pItem->GetStartTime();
// petra        pTheTime = localtime(&theTime);
// petra		strftime(cTime, sizeof (cTime),
// petra            "%m/%d/%y",
// petra            pTheTime);

        *pStream << "<td>";
// petra		<< cTime 
		timeWidget.EmitHTML (pStream);				// petra
		*pStream << "</td>";
        break;

    case cellItemEnd:
		timeWidget.SetTime (pItem->GetEndTime() );	// petra
		timeWidget.SetDateTimeFormat (1, 1);		// petra
// petra        theTime = pItem->GetEndTime();
// petra        pTheTime = localtime(&theTime);
// petra        strftime(cTime, sizeof (cTime),
// petra            "%m/%d/%y %H:%M:%S",
// petra            pTheTime);

        *pStream << "<td>";
// petra		<< cTime 
		timeWidget.EmitHTML (pStream);				// petra
		*pStream << "</td>";
        break;

    case cellItemPrice:
        *pStream << "<td align=right>";

        if (pItem->GetBidCount() > 0 && pItem->GetPrice() > 0)
		{
			clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetPrice()); 
			currencyWidget.SetBold(true);
			currencyWidget.SetLimitCheck(true);
			currencyWidget.EmitHTML(pStream);
		}

        else
		{
			clsCurrencyWidget currencyWidget(mpMarketPlace, pItem->GetCurrencyId(), pItem->GetStartPrice()); 
			currencyWidget.SetLimitCheck(true);
			currencyWidget.EmitHTML(pStream);
		}

        *pStream << "</td>";
        break;

    case cellItemTitle:
        *pStream << "<td>" << pItem->GetTitle() << "</td>";
        break;

    case cellItemHighBidder:
        *pStream << "<td>";

        if (pItem->GetPrivate())
            *pStream << "Private Auction";
        else if (pItem->GetQuantity() > 1)
        {
			*pStream <<	"<A HREF="
				"\""
				<<	mpMarketPlace->GetCGIPath(PageViewBids)
				<<	"eBayISAPI.dll?ViewBids"
				<<	"&item="
				<<	pItem->GetId()
				<<	"#dutch"
				"\""
				">"
				<<	pItem->GetBidCount()
				<<	" Dutch bids"
				"</A>";
        } 
        else if (pItem->GetBidCount() > 0 && pItem->GetPrice() > 0)
        {
			assert(mpUserIdWidget);
			mpUserIdWidget->SetUserInfo(pItem->GetHighBidderUserId(), 
				"ERROR",
				UserStateEnum(0), false, pItem->GetHighBidderFeedbackScore());
			mpUserIdWidget->SetUserIdLink(true);
			mpUserIdWidget->SetShowUserStatus(false);
			mpUserIdWidget->SetShowStar(true);
			mpUserIdWidget->EmitHTML(pStream);

            if (pItem->GetEndTime() < time(NULL))
                *pStream << "(*)";
        }
        else
            *pStream << "No Bids Yet";

        *pStream << "</td>\n";
        break;
        
    default:
        break;
    }
    
    return true;
}

