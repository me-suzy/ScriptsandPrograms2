/*	$Id: clseBayUserBiddingWidget.h,v 1.2.350.2 1999/07/23 16:55:10 bwang Exp $	*/
//
//	File:	clseBayUserBiddingWidget.h
//
//	Class:	clseBayUserBiddingWidget
//
//	Author:	Poon
//
//	Function:
//			Shows all items a user is bidding on
//
//			This widget was derived from clseBayTableWidget by overriding
//			 the following routines:
//				* EmitCell(int n)			= emits the HTML for each hot item, 
//											  including the <TD> and </TD> tags
//				* EmitPreTable()			= emits the header stuff
//				* EmitPostTable()			= emits the legend for color coding
//				* Initialize()				= query the database for the items
//
//
//			Example code of how to invoke a clseBayUserBiddingWidget:
//
//				clseBayUserBiddingWidget *ubw = new clseBayUserBiddingWidget(mpMarketPlace);
//				ubw->SetUserId(77498);
//				ubw->SetCellPadding(2);
//				ubw->SetColor("#CCCCFF");
//				ubw->EmitHTML(mpStream);
//				delete ubw;
//
// Modifications:
//				- 11/21/97	Poon - Created
//				- 04/26/99	Bill - Added table footer to display total
//
#ifndef CLSEBAYUSERBIDDINGWIDGET_INCLUDED
#define CLSEBAYUSERBIDDINGWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsItem.h"


class clseBayUserBiddingWidget : public clseBayTableWidget
{

public:

	// User bidding widget requires having access to the marketplace.
	// Clients must specify the user.
	clseBayUserBiddingWidget(clsMarketPlace *pMarketPlace);

	// dtor.
	virtual ~clseBayUserBiddingWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayUserBiddingWidget(pMarketPlace); }

	// Set parameters of the user bidding widget
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetUserPassword(char *Pass)			{ strncpy(mUserPassword, Pass, sizeof(mUserPassword)-1); } 
	void SetDaysSince(int DaysSince)			{ mDaysSince = DaysSince; }
	void SetSortCode(ItemListSortEnum SortCode)	{ mSortCode = SortCode; }	// see clsItems.h for
																			// valid sort values
	void SetCurrentURL(char *URL)				{ strncpy(mURL, URL, sizeof(mURL)-1); } 
	void SetRestrictedAccess(bool RA)			{ mRestrictedAccess = RA; }

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

protected:

	// Choose the items from the database and put them into mvItems
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

	// Emit HTML before the table
	virtual bool EmitPreTable(ostream *pStream);

	// Emit HTML footer
	virtual bool EmitTableFooter(ostream *pStream,
								char*	CellColor,
								int		TotalItem,
								double	TotalStartPrice,
								double	TotalPrice,
								double	TotalMyMaxPrice,
								int		TotalQuantity,
								int		TotalBidCount);

	// Emit HTML after the table
	virtual bool EmitPostTable(ostream *pStream);

private:

	ItemList			mvItems;			// the items to show
	char				mUserPassword[65];	// used only to pass on to item detail page
	clsUser				*mpUser;			// the user whose items we're showing
	int					mDaysSince;			// how far back to go (default = 7)
	ItemListSortEnum	mSortCode;			// how to sort the list (default = SortItemsByEndTimeReverse)
	char				mURL[1024];			// the current URL
	bool				mRestrictedAccess;	// if true, don't show max price
	bool				mNeedToDeleteUser;	// signify need to delete user
	clsBid				*mpBid;				// local cache for efficiency

	int					mTotalItem;
	double				mTotalStartPrice;
	double				mTotalPrice;
	double				mTotalMyMaxPrice;
	int					mTotalQuantity;
	int					mTotalBidCount;

	int					mWonTotalItem;
	double				mWonTotalStartPrice;
	double				mWonTotalPrice;
	double				mWonTotalMyMaxPrice;
	int					mWonTotalQuantity;
	int					mWonTotalBidCount;

	bool				mItemWon;		// indicate whether item bid has won or not

};

#endif // CLSEBAYUSERBIDDINGWIDGET_INCLUDED
