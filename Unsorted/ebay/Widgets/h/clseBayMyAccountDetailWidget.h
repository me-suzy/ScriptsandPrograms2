/*	$Id: clseBayMyAccountDetailWidget.h,v 1.2 1998/10/16 01:01:02 josh Exp $	*/
//
//	File:	clseBayMyAccountDetailWidget.h
//
//	Class:	clseBayMyAccountDetailWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows the profile of a user using clseBayTableWidget.
//
// Modifications:
//				- 11/4/97	Charles - Created
//
//
//
#ifndef CLSEBAYMYACCOUNTDETAILWIDGET_INCLUDED
#define CLSEBAYMYACCOUNTDETAILWIDGET_INCLUDED

#include <stdio.h>
#include <time.h>
#include <vector.h>
#include <iterator.h>
//#include "clseBayApp.h"
#include "clsDatabase.h"
#include "eBayTypes.h"
#include "clseBayTableWidget.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUtilities.h"

#define EBAY_NUMBER_ACCOUNT_DISPLAY		1
#define EBAY_NUMBER_ACCOUNT_COLUMNS		7
#define EBAY_ACCOUNT_CELLS_PER_ITEM		7
#define EBAY_ACCOUNT_STRING_LENGTH		64

// 
// This Enum tells us how to sort
// a list of transactions
//
typedef enum
{
	AccountReferenceNumber		= 0,
	AccountTransactionDate		= 1,
	AccountTransactionType		= 2,
	AccountTransactionItem		= 3,
	AccountCredit				= 4,
	AccountDebit				= 5,
	AccountTransactionBalance	= 6
} AccountCells; 


class clseBayMyAccountDetailWidget : public clseBayTableWidget
{
public:

	// Profile widget requires having access to the marketplace
	clseBayMyAccountDetailWidget(clsMarketPlace *pMarketPlace);

	// clseBayProfileWidget destructor
	virtual ~clseBayMyAccountDetailWidget();

	// set parameters
	void SetUserId(int UserId)					{ mUserId = UserId; }
	void SetTitleColor(char *color)				{ strncpy(mTitleColor, color, sizeof(mTitleColor) - 1); }
	
protected:
	
	// Emit HTML after the table
	bool EmitPostTable(ostream *pStream);

	// Before the table create a header 
	bool EmitPreTable(ostream *pStream);

	bool Initialize();

	// Print an Item of the profile
	bool EmitCell(ostream *pStream,int n);

private:

	// char				*mpMyUserId;
	int					mUserId; // Id of user
	clsUser				*mpUser; // The user
	clsAccount			*mpAccount;
	AccountDetailVector	*mpvDetail;
	bool				mColorSwitch;
	char				*mpColor;
	double				mBalance;
	// background color of header; default = ""
	char				mTitleColor[EBAY_ACCOUNT_STRING_LENGTH];

};

#endif // CLSEBAYMYACCOUNTDETAILWIDGET_INCLUDED
