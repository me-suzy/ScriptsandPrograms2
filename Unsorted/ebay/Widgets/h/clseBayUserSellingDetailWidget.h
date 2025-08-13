/*	$Id: clseBayUserSellingDetailWidget.h,v 1.2 1998/10/16 01:01:19 josh Exp $	*/
//
//	File:	clseBayUserSellingDetailWidget.h
//
//	Class:	clseBayUserSellingDetailWidget
//
//	Author:	Poon
//
//	Function:
//			Shows all items a user is selling, in detail
//
//			This widget was derived from clseBayTableWidget by overriding
//			 the following routines:
//				* EmitCell(int n)			= emits the HTML for each hot item, 
//											  including the <TD> and </TD> tags
//				* EmitPreTable()			= emits pre-table stuff
//				* Initialize()				= query the database for the items
//
//
//			Example code of how to invoke a clseBayUserSellingDetailWidget:
//
//				clseBayUserSellingDetailWidget *usdw = new clseBayUserSellingDetailWidget(mpMarketPlace);
//				usdw = new clseBayUserSellingDetailWidget(mpMarketPlace);
//				usdw->SetUserId(mpUser->GetId());
//				usdw->SetSortCode((ItemListSortEnum)sort);
//				usdw->SetColor("#FFCC99");
//				usdw->SetCellSpacing(5);
//				usdw->EmitHTML(mpStream);
//				delete usdw;
//
// Modifications:
//				- 11/10/97	Poon - Created
//
#ifndef CLSEBAYUSERSELLINGDETAILWIDGET_INCLUDED
#define CLSEBAYUSERSELLINGDETAILWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsItem.h"


class clseBayUserSellingDetailWidget : public clseBayTableWidget
{

public:

	// User selling detail widget requires having access to the marketplace.
	// Clients must specify the user.
	clseBayUserSellingDetailWidget(clsMarketPlace *pMarketPlace);

	// dtor.
	virtual ~clseBayUserSellingDetailWidget();

	static clseBayWidget *MakeWidget(clsWidgetHandler *,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayUserSellingDetailWidget(pMarketPlace); }

	// Set parameters of the user selling widget
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetDaysSince(int DaysSince)			{ mDaysSince = DaysSince; }
	void SetSortCode(ItemListSortEnum SortCode)	{ mSortCode = SortCode; }	// see clsItems.h for
																			// valid sort values
	void SetHeaderColor(char *Color)					{strncpy(mHeaderColor, Color, sizeof(mHeaderColor) - 1);}

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

private:

	ItemList			mvItems;			// the items to show
	clsUser				*mpUser;			// the user whose items we're showing
	int					mDaysSince;			// how far back to go (default = 7)
	ItemListSortEnum	mSortCode;			// how to sort the list (default = SortItemsByEndTimeReverse)
	char				mHeaderColor[32];	// color of item headers
	bool				mNeedToDeleteUser;	// signify need to delete user

};

#endif // CLSEBAYUSERSELLINGDETAILWIDGET_INCLUDED
