/*	$Id: clseBayMyFeedbackWidget.h,v 1.3 1999/02/21 02:28:38 josh Exp $	*/
//
//	File:	clseBayMyFeedbackWidget.h
//
//	Class:	clseBayMyFeedbackWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows Feedback items for a user using clseBayTableWidget.
//
// Modifications:
//				- 11/4/97	Charles - Created
//
//		HOW TO CALL	clseBayMyFeedbackWidget
//
//		clseBayMyFeedbackWidget *mpfw = new clseBayMyFeedbackWidget(mpMarketPlace);
//		mpfw->SetUserId(47244);
//		mpfw->SetTitleColor("teal");
//		mpfw->SetColor("#FFFFFF");
//		mpfw->SetNumberOfItemToDisplay(2);
//		mpfw->SetScoreMinForStar(12);
//		mpfw->EmitHTML(mpStream);
//		if(mpfw) delete mpfw;
//
//
//
//
#ifndef CLSEBAYMYFEEDBACKWIDGET_INCLUDED
#define CLSEBAYMYFEEDBACKWIDGET_INCLUDED

#include <stdio.h>
#include <time.h>
#include <vector.h>
#include "clsFeedback.h"
#include "eBayTypes.h"
#include "clseBayTableWidget.h"
#include "clsMarketPlace.h"
#include "clsMarketPlaces.h"
#include "clsUsers.h"
#include "clsUser.h"
#include "clsUtilities.h"
#include "clsUserIdWidget.h"

#define EBAY_NUMBER_FEEDBACK_COLUMNS	2
#define EBAY_FEEDBACK_CELLS_PER_ITEM	4
#define EBAY_FEEDBACK_STRING_LENGTH		64
//#define EBAY_NUMBER_OF_STARS			5

// 
// This Enum tells us for witch cell
// we are filling
//
typedef enum
{
	FeedbackCellDate				= 0,
	FeedbackCellCommentIdAndScore	= 1,
	FeedbackCellTime				= 2,
	FeedbackCellMessage				= 3
} FeedbackCells; 


class clseBayMyFeedbackWidget : public clseBayTableWidget
{
public:

	// Feedback item widget requires having access to the marketplace
	clseBayMyFeedbackWidget(clsMarketPlace *pMarketPlace);
    // Construct via a blob.
    clseBayMyFeedbackWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

	// clseBayFeedbackWidget destructor
	virtual ~clseBayMyFeedbackWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
	void SetTitleColor(const char *color)				{ strncpy(mTitleColor, color, sizeof(mTitleColor) - 1); mTitleColor[sizeof (mTitleColor) - 1] = '\0'; }
	void SetScoreMinForStar(int mini)			{ mScoreMinForStar = mini; }
	void SetNumberOfItemToDisplay(int items)	{ mNumberOfItemsToDisplay = items; }

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayMyFeedbackWidget(pHandler, pMarketPlace, pApp); }

#if 0
	// temp hack to disable this widget
	virtual bool EmitHTML(ostream *pStream);
#endif


protected:
	
	// Emit HTML after the table
	virtual bool EmitPostTable(ostream *pStream);

	// Before the table create a header 
	virtual bool EmitPreTable(ostream *pStream);

	virtual bool Initialize();

	// Print an Item of the feedback detail
	virtual bool EmitCell(ostream *pStream,int n);

	//int	mNumItems;	// generic number of feedback items to use

private:

    bool                mUseContext;
	char				*mpMyUserId;
	int					mscore;
	clsUser				*mpUser;		  // The user who ask his feedback
	FeedbackItemVector  *mpvItemFeedback; // The feedback details of this user
	clsUserIdWidget		*mpUserIdWidget;  // The User ID Widget used for My Feedback

	int					mScoreMinForStar;	// minimum score to have a star, default = 10
	int					mNumberOfFeedback;
	int					mNumberOfItemsToDisplay; // default = 3
	char				mTitleColor[32];// background color of header; default = ""

};

#endif // CLSEBAYMYFEEDBACKWIDGET_INCLUDED
