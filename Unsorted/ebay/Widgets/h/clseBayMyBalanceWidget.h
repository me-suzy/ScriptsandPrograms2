/*	$Id: clseBayMyBalanceWidget.h,v 1.2 1998/10/16 01:01:03 josh Exp $	*/
//
//	File:	clseBayMyBalanceWidget.h
//
//	Class:	clseBayMyBalanceWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows the balance of a user using clseBayTableWidget.
//
// Modifications:
//				- 11/19/97	Charles - Created
//
//			HOW TO CALL clseBayMyBalanceWidget
//
//		clseBayMyBalanceWidget *mpfb = new clseBayMyBalanceWidget(mpMarketPlace);
//		mpfb->SetUserId(47244);
//		mpfb->SetTitleColor("gray");
//		mpfb->SetLimitCreditCardOnFile(12);
//		mpfb->SetColor("#FFFFFF");
//		mpff->SetPassword(pPassToDisplay);
//		mpff->SetEntire(true);
//		mpfb->EmitHTML(mpStream);
//		if(mpfb) delete mpfb;
//
#ifndef CLSEBAYMYBALANCEWIDGET_INCLUDED
#define CLSEBAYMYBALANCEWIDGET_INCLUDED

#include <stdio.h>
#include <time.h>
#include <vector.h>
#include <iterator.h>
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

#define EBAY_BALANCE_STRING_LENGTH 64

class clseBayMyBalanceWidget : public clseBayTableWidget
{
public:

	// Profile widget requires having access to the marketplace
	clseBayMyBalanceWidget(clsMarketPlace *pMarketPlace);

	// clseBayProfileWidget destructor
	virtual ~clseBayMyBalanceWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetTitleColor(char *color)				{strncpy(mTitleColor,color,sizeof(mTitleColor) - 1);}
	void SetLimitCreditCardOnFile(double limit)	{mLimit = limit;}
	void SetPassword(char *password)			{strncpy(mPassWord, password, sizeof(mPassWord) - 1);}
	
protected:
	
	// Emit HTML after the table
	bool EmitPostTable(ostream *pStream);

	// Before the table create a header 
	bool EmitPreTable(ostream *pStream);

	bool Initialize();

	// Print an Item of the profile
	bool EmitCell(ostream *pStream,int n);

private:

	char				mTitleColor[32];// background color of header; default = ""
	char				mPassWord[65];	// The encripted password of the user

	clsUser				*mpUser; // The user
	clsAccount			*mpAccount;
	double				myBalance;
	// More than this limit the user have to had his credit card on file
	double				mLimit;	// default = 10.0
	char				*mpTheDate;
};

#endif // CLSEBAYMYMYBALANCEWIDGET_INCLUDED
