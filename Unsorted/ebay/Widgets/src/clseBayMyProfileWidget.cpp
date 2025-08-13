/*	$Id: clseBayMyProfileWidget.cpp,v 1.3.238.2 1999/06/25 17:46:16 poon Exp $	*/
//
//	File:	clseBayMyProfileWidget.cpp
//
//	Class:	clseBayMyProfileWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows the profile of a user using clseBayTableWidget.
//
// Modifications:
//				- 11/18/97	Charles - Created
//
#include "widgets.h"
#include "clseBayMyProfileWidget.h"


clseBayMyProfileWidget::clseBayMyProfileWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mpUser			= NULL;
	mTitleColor[0]	= '\0';
	mpUserIdWidget	= NULL;
	mpMyUserId		= NULL;
}


clseBayMyProfileWidget::~clseBayMyProfileWidget()
{
	delete mpUserIdWidget;
	mpMyUserId = NULL;
	mpUserIdWidget	= NULL;
	mpUser = NULL;
}



// Initializing the number of cells to display
// and loading the feedback details
bool clseBayMyProfileWidget::Initialize()
{
	int iTotalNumCell = 0;		

	SetNumItems(EBAY_NUMBER_PROFILE_DISPLAY);
	// safety
	if (!mpMarketPlace)
		return false;

	// safety
	if (!mpUser)
		return false;

	//
	// Let's get the vector of feedback detail items 
	// and the user's score and the user's Id
	//
	mpMyUserId = mpUser->GetUserId();

	// Create the User ID Widget
	mpUserIdWidget = new clsUserIdWidget(mpMarketPlace, GetApp());

	// Total number of cell to display
	// Number of Items * Number of cell per Item
	iTotalNumCell = mNumItems * EBAY_PROFILE_CELLS_PER_ITEM;

	//
	// Changing the number of cells to display
	//
	SetNumItems(iTotalNumCell);
	//
	// Set the color of the second row like the title
	// if mColor is not specified
	//
	if( mColor[0] == '\0' )
	{
		if(mTitleColor[0] != '\0')	
			SetColor(mTitleColor);
	}

	return true;
}


// Before the table create a header 
bool clseBayMyProfileWidget::EmitPreTable(ostream *pStream)
{

	// Write the title
	if (mTitleColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		mBorder
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		mCellPadding
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		mCellSpacing
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\""
				 <<		">"
				 <<		"\n";
	}
	else
	{
		// emit begin table tag with the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		mBorder
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		mCellPadding
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		mCellSpacing
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\" "
				 <<		"BGCOLOR=\""
				 <<		mTitleColor
				 <<		"\""
				 <<		">"
				 <<		"\n";
	}
	
	*pStream	<<	"<TR><TH ALIGN=CENTER>"
				<<	"<strong><FONT face=\"arial, helvetica\" size=\"4\">"
				<<	"My eBay!"
				<<	"</FONT></strong>"
				<<	"</TH></TR>"
				<<	"</TABLE>"
				<<	flush;

	return true;
}


// End the List of Items
bool clseBayMyProfileWidget::EmitPostTable(ostream *pStream)
{
	*pStream	<< " <BR>"

//				<<	"<FONT size=\"3\">"
//				<<	"<img width=\"28\" height=\"11\" src=\""
//				<<	mpMarketPlace->GetPicsPath()
//				<<	"new.gif"
//					"\">"
//					"<a href=\""
//				<<	mpMarketPlace->GetHTMLPath()
//				<<	"services/myebay/optin-login.html"
//					"\">"
//				<<	"Set your email preferences</a>.</font><br><br>"

				<<	flush;

	return true;

}


// This routine have to be called n = 0..mNumItems-1 times
// here mNumItems = 1 so it will be called once 
bool clseBayMyProfileWidget::EmitCell( ostream *pStream, int n)
{
	assert(mpUserIdWidget);
	mpUserIdWidget->SetUser(mpUser);
	mpUserIdWidget->SetShowUserStatus(false);
	mpUserIdWidget->SetShowFeedback(false);
	mpUserIdWidget->SetShowStar(false);
	mpUserIdWidget->SetUserIdLink(false);
	mpUserIdWidget->SetIncludeEmail(false);
	mpUserIdWidget->SetUserIdBold(false);
	mpUserIdWidget->SetShowMask(true);

	if(n == 0)
	{
		// This is the first row
		// Writing the user's id in a cell
		if(mColor[0] == '\0')
		{
			*pStream	<<	"<TD ALIGN=CENTER>";
			mpUserIdWidget->EmitHTML(pStream);
			*pStream	<<	"</TD>";
		}
		else
		{
			*pStream	<<	"<TD ALIGN=CENTER BGCOLOR=\""
						<<	mColor
						<<	"\""
						<<	">";
			mpUserIdWidget->EmitHTML(pStream);
			*pStream	<<	"</TD>";
		}

		*pStream	<< flush;
	}

	return true;

}

