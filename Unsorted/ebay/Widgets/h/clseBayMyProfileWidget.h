/*	$Id: clseBayMyProfileWidget.h,v 1.2 1998/10/16 01:01:06 josh Exp $	*/
//
//	File:	clseBayMyProfileWidget.h
//
//	Class:	clseBayMyProfileWidget
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
//		HOW TO CALL clseBayMyProfileWidget
//
//		clseBayMyProfileWidget *mppw = new clseBayMyProfileWidget(mpMarketPlace);
//		mppw->SetUserId(47244);
//		mppw->SetTitleColor("aqua");
//		mppw->EmitHTML(mpStream);
//		if(mppw) delete mppw;
//
//
//
#ifndef CLSEBAYMYPROFILEWIDGET_INCLUDED
#define CLSEBAYMYPROFILEWIDGET_INCLUDED

#include <stdio.h>
#include <time.h>
#include "eBayTypes.h"
#include "clseBayTableWidget.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUtilities.h"
#include "clsUserIdWidget.h"

#define EBAY_NUMBER_PROFILE_DISPLAY		1
#define EBAY_NUMBER_PROFILE_COLUMNS		1
#define EBAY_PROFILE_CELLS_PER_ITEM		1

class clseBayMyProfileWidget : public clseBayTableWidget
{
public:

	// Profile widget requires having access to the marketplace
	clseBayMyProfileWidget(clsMarketPlace *pMarketPlace);

	// clseBayProfileWidget destructor
	virtual ~clseBayMyProfileWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetTitleColor(char *color)		{strncpy(mTitleColor, color, sizeof(mTitleColor) - 1);}

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
	clsUserIdWidget		*mpUserIdWidget;  // The User ID Widget used for My Profile

	char				*mpMyUserId;
	clsUser				*mpUser; // The user
};

#endif // CLSEBAYMYPROFILEWIDGET_INCLUDED
