/*	$Id: clseBayItemWidget.h,v 1.5.62.1 1999/06/04 19:13:39 jpearson Exp $	*/
//
//	File:	clseBayItemWidget.h
//
//	Class:	clseBayItemWidget
//
//	Author:	Poon
//
//	Function:
//			Abstract base class for widgets that show a random set
//			of items. Will not show adult or black-listed items.
//
//			This widget was derived from clseBayTableWidget by overriding
//			 the following routines:
//				* EmitCell(int n)			= emits the HTML for each hot item, 
//											  including the <TD> and </TD> tags
//				* Initialize()				= query the database for the items
//				* EmitPreTable()			= just an HTML comment showing the pool size
//
//			Use clseBayItemWidget as a base class by overriding the 
//			following routines:
//
//				(mandatory override)
//				* GetItemIds()				= get the relevant items from the
//											  database and stuff their ids into
//											  the given vector. 
//
//
// Modifications:
//				- 10/23/97	Poon - Created
//
#ifndef CLSEBAYITEMWIDGET_INCLUDED
#define CLSEBAYITEMWIDGET_INCLUDED

#include "clseBayTableWidget.h"
#include "clsItem.h"


class clseBayItemWidget : public clseBayTableWidget
{

public:

	// Item widget requires having access to the marketplace.
	clseBayItemWidget(clsMarketPlace *pMarketPlace);

	// Empty dtor.
	virtual ~clseBayItemWidget();

	// Set parameters of the item widget
	void SetShowPrice(bool ShowPrice)		{mShowPrice = ShowPrice;}
	void SetShowBidCount(bool BidCount)		{mShowBidCount = BidCount;}
	void SetName(char *c)					{strncpy(mName, c, sizeof(mName) - 1);}
	void SetMoreText(char *c)				{strncpy(mMoreText, c, sizeof(mMoreText) - 1);}
	void SetMoreLink(char *c)				{strncpy(mMoreLink, c, sizeof(mMoreLink) - 1);}
	void SetFont(char *c)					{strncpy(mFont, c, sizeof(mFont) - 1);}
	void SetFontSize(int FontSize)			{mFontSize = FontSize;}
	void SetCountry(int countryId)			{mCountryId = countryId;}
	void SetCurrency(int Currency)		    {mCurrency = Currency;}
	void SetRegion(int Region)				{mRegion = Region;}

	// Get parameters of the item widget
	char*	GetItemName(void)				{return mName; }

	// set parameters using a vector of strings, with the first string being
	//  the widget tagname.
	// the convention is that this routine should handle any parameters it
	//  understands, erase (and delete) them from the vector, then call the parent
	//  class's SetParams(vector<char *> *) to handle the rest.
	// this widget handles all parameters specified above in the Set# routines.
	// each parameter, except for (*pvArgs)[0], is of the form "name=value"
	virtual void SetParams(vector<char *> *pvArgs);

	// Emit HTML before the table
	virtual bool EmitPreTable(ostream *pStream);

	clsItem* GetItem(int n);

protected:

	// Retrieve ids of all items that you want to choose
	//  randomly from. Stuff them into pvItemIds.
	virtual void GetItemIds(vector<int> *pvItemIds)=0;

	// Choose the items from the database and put them into mvItems
	virtual bool Initialize();

	// Emit the HTML for cell n, including the <TD> and </TD> tags.
	virtual bool EmitCell(ostream *pStream, int n);

	vector<clsItem*>	mvItems;		// the items to show

private:
	bool				mShowPrice;		// show price?
	bool				mShowBidCount;	// show bid count?
	char				mName[255];		// generic item name to use
	char				mMoreText[32];	// "more..."
	char				mMoreLink[256];	// link to where more... points
	int					mNumPool;		// number of items in the pool to choose from (used
											// only for the HTML comments
	char				mFont[256];		// font, e.g. arial,helvetica
	int					mFontSize;		// font size, e.g. 3
	int					mCountryId;     // if 0 then international otherwise country id from Barry's table
	int					mCurrency;		// which currency to find items in
	int					mRegion;		// if 0 for 
};

#endif // CLSEBAYITEMWIDGET_INCLUDED
