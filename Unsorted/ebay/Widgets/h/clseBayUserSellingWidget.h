/*	$Id: clseBayUserSellingWidget.h,v 1.2.350.2 1999/07/23 16:55:31 bwang Exp $	*/
//
//	File:	clseBayUserSellingWidget.h
//
//	Class:	clseBayUserSellingWidget
//
//	Author:	Poon
//
//	Function:
//			Shows all items a user is selling
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
//			Example code of how to invoke a clseBayUserSellingWidget:
//
//				clseBayUserSellingWidget *usw = new clseBayUserSellingWidget(mpMarketPlace);
//				usw->SetUserId(77498);
//				usw->SetCellPadding(2);
//				usw->SetColor("#FFCC99");
//				usw->EmitHTML(mpStream);
//				delete usw;
//
// Modifications:
//				- 11/10/97	Poon - Created
//				- 04/26/99	Bill - Added table footer to display total
//
#ifndef CLSEBAYUSERSELLINGWIDGET_INCLUDED
#define CLSEBAYUSERSELLINGWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsItem.h"


class clseBayUserSellingWidget : public clseBayTableWidget
{

public:

	// User selling widget requires having access to the marketplace.
	// Clients must specify the user.
	clseBayUserSellingWidget(clsMarketPlace *pMarketPlace);

	// dtor.
	virtual ~clseBayUserSellingWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayUserSellingWidget(pMarketPlace); }

	// Set parameters of the user selling widget
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

	// Emit the HTML for footor, showing the total count
	// including the <TD> and </TD> tags
	virtual bool EmitTableFooter(ostream *pStream,
								char*	CellColor,
								int		TotalItem,
								double	TotalStartPrice,
								double	TotalPrice,
								double	TotalReservePrice,
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
	bool				mRestrictedAccess;	// if true, don't show reserve price
	bool				mNeedToDeleteUser;	// signify need to delete user

	int					mTotalItem;
	double				mTotalStartPrice;
	double				mTotalPrice;
	double				mTotalReservePrice;
	int					mTotalQuantity;
	int					mTotalBidCount;

	int					mSoldTotalItem;
	double				mSoldTotalStartPrice;
	double				mSoldTotalPrice;
	double				mSoldTotalReservePrice;
	int					mSoldTotalQuantity;
	int					mSoldTotalBidCount;

	bool				mItemSold;		// indicate whether item is sold or not
};

#endif // CLSEBAYUSERSELLINGWIDGET_INCLUDED
