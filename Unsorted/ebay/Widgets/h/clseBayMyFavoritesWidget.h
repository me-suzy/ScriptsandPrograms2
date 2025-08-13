/*	$Id: clseBayMyFavoritesWidget.h,v 1.2 1998/10/16 01:01:04 josh Exp $	*/
//
//	File:	clseBayMyFavoritesWidget.h
//
//	Class:	clseBayMyFavoritesWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows Favorites items for a user using clseBayItemWidget.
//
// Modifications:
//				- 11/10/97	Charles - Created
//
//		HOW TO CALL		clseBayMyFavoritesWidget
//
//		clseBayMyFavoritesWidget *mpff = new clseBayMyFavoritesWidget(mpMarketPlace);
//		mpff->SetUserId(47244);
//		mpff->SetPassword(pPassToDisplay);
//		mpff->SetTitleColor("green");
//		mpff->EmitHTML(mpStream);
//		if(mpff) delete mpff;
//
//
//
#ifndef CLSEBAYMYFAVORITESWIDGET_INCLUDED
#define CLSEBAYMYFAVORITESWIDGET_INCLUDED

#include <stdio.h>
#include <time.h>
#include "eBayTypes.h"
#include "clsCategories.h"
#include "clseBayTableWidget.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsItems.h"
#include "clsItem.h"
#include "clsUtilities.h"

////////////////////////////////////////////
// #define for clseBayMyFavoritesWidget() //
////////////////////////////////////////////
#define EBAY_FAVORITES_SIZE_OF_NAME			64
#define EBAY_FAVORITES_SIZE_OF_LINK			128
//
// The maximum number of cells per category
//
#define EBAY_FAVORITES_CELLS_PER_CATEGORY	2
#define EBAY_FAVORITES_DEFINED_ITEMS		5
//
// The maximum number of interests for a user
//
#define EBAY_NUMBER_OF_INTERESTS			4
#define EBAY_NUMBER_OF_FAVORITES_COLUMNS	1

//
// The names of the categories
//
typedef struct
{
	char	*pName0;
	char	*pName1;
	char	*pName2;
	char	*pName3;
	char	*pName4;
	char	*pLink1;
	char	*pLink2;
	char	*pLink3;
	char	*pLink4;
	char	*pLink5;
} mNames;	

// 
// This Enum tells us for witch interest
// we are working for
//
typedef enum
{
	FavoritesInterest_1	= 0,
	FavoritesInterest_2	= 1,
	FavoritesInterest_3	= 2,
	FavoritesInterest_4	= 3
} FavoritesInterests; 


// 
// This Enum tells us how to sort
// a list of items
//
typedef enum
{
	FavoritesNamesRow	= 0,
	FavoritesCountRow	= 1
} FavoritesExpectedCells; 



class clseBayMyFavoritesWidget : public clseBayTableWidget
{
public:

	// Feedback item widget requires having access to the marketplace
	clseBayMyFavoritesWidget(clsMarketPlace *pMarketPlace);

	// clseBayFeedbackWidget destructor
	virtual ~clseBayMyFavoritesWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetTitleColor(char *color)		{strncpy(mTitleColor, color, sizeof(mTitleColor) - 1);}
	void SetPassword(char *password)	{strncpy(mPassWord, password, sizeof(mPassWord) - 1);}
	
protected:
	
	bool EmitPreTable(ostream *pStream);  // Before the table create a header
	bool EmitPostTable(ostream *pStream); // After the table create a footer
	bool Initialize();	
	bool EmitCell(ostream *pStream,int n);// Print an Item of the favorites

private:

	char			mTitleColor[32];	 // background color of header; default = ""		
	mNames			*mpNames;			 // The names of the categories and path of the links
	int				mTotalNumberOfCells; // Total number of cells
	char			mPassWord[65];		 // The encripted password of the user
	char			mUserId[65];		 // The User ID
	clsUser			*mpUser;			// the user

};

#endif // CLSEBAYMYFAVORITESWIDGET_INCLUDED
