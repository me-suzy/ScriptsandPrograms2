/* $Id: clseBayFeedbackWidget.h,v 1.3 1999/02/21 02:28:35 josh Exp $ */
//
//	File:	clseBayFeedbackWidget.h
//
//	Class:	clseBayFeedbackWidget
//
//	Author:	Chad Musick
//
//	Function:
//			Widget that shows Feedback items for a user using clseBayTableWidget,
//          in same format as ViewFeedback
//
#ifndef clseBayFeedbackWidget_h
#define clseBayFeedbackWidget_h

#include "clseBayTableWidget.h"


class clseBayFeedbackWidget : public clseBayTableWidget
{
public:

    // Construct via a blob.
    clseBayFeedbackWidget(clsWidgetHandler *pHandler,
        clsMarketPlace *pMarketPlace,
        clsApp *pApp);

	// clseBayFeedbackWidget destructor
	virtual ~clseBayFeedbackWidget();

	// set parameters
	void SetUser(clsUser *User)					{ mpUser = User; }
    void SetAlternateColor(const char *color) { strncpy(mAlternateColor, color, sizeof(mAlternateColor) - 1); mAlternateColor[sizeof (mAlternateColor) - 1] = '\0'; }
	void SetNumberOfItemToDisplay(int items)	{ mNumberOfItemsToDisplay = items; }

    // For translation to and from text.
	void SetParams(vector<char *> *pvArgs);
    void SetParams(const void *pData, const char *pStringBase, bool mFixBytes);
    long GetBlob(clsDataPool *pDataPool, bool mReverseBytes);

	static clseBayWidget *MakeWidget(clsWidgetHandler *pHandler,
		clsMarketPlace *pMarketPlace = NULL, clsApp *pApp = NULL)
	{ return (clseBayWidget *) new clseBayFeedbackWidget(pHandler, pMarketPlace, pApp); }

	void DrawTag(ostream *pStream, const char *pName, bool comments = true);


protected:
	
	// In the table create a top line 
	bool EmitTopLine(ostream *pStream);

	bool Initialize();

	// Print an Item of the feedback detail
	bool EmitCell(ostream *pStream,int n);

private:

    bool                mIncludeEmail;
	char				*mpMyUserId;
	int					mscore;
	clsUser				*mpUser;		  // The user who ask his feedback
	FeedbackItemVector  *mpvItemFeedback; // The feedback details of this user
	clsUserIdWidget		*mpUserIdWidget;  // The User ID Widget used for My Feedback

	int					mNumberOfFeedback;
	int					mNumberOfItemsToDisplay; // default = 3
    char                mAlternateColor[32]; // Alternate color for cells.
};

#endif /* clseBayFeedbackWidget_h */
