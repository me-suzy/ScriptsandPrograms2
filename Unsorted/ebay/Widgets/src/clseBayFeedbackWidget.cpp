/* $Id: clseBayFeedbackWidget.cpp,v 1.6.134.1 1999/08/01 02:51:24 barry Exp $ */
//
//	File:	clseBayFeedbackWidget.cpp
//
//	Class:	clseBayFeedbackWidget
//
//	Function:
//			Widget that shows Feedback items for a user using clseBayTableWidget,
//          via blob.
//
#include "widgets.h"
#include "clseBayFeedbackWidget.h"

//static int   Ratings1[] = { 10, 100, 500, 1000, 10000 };
//static char* Stars1[]   = { "star.gif",
//						"starGreen.gif",
//						"starBlue.gif",
//						"starRed.gif",
//						"StarCircleGreen.gif"
//};

struct clseBayFeedbackWidgetOptions
{
    int32_t mAlternateColor;
    int32_t mSize;
    int32_t mTableOptionsOffset;
    int32_t mIncludeEmail;
    int32_t mExpansionOffset;
};

clseBayFeedbackWidget::clseBayFeedbackWidget(clsWidgetHandler *pHandler,
    clsMarketPlace *pMarketPlace,
    clsApp *pApp) : clseBayTableWidget(pHandler, pMarketPlace, pApp)
{
    SetNumberOfItemToDisplay(3);
    mscore              = 0;
    mpvItemFeedback     = NULL;
    mpUser              = NULL;
    mNumberOfFeedback   = 0;
    mAlternateColor[0]  = '\0';
    mpUserIdWidget      = NULL;
    mIncludeEmail       = false;
}

clseBayFeedbackWidget::~clseBayFeedbackWidget()
{
    delete mpUserIdWidget;
}

void clseBayFeedbackWidget::DrawTag(ostream *pStream, const char *pName, bool comments /* = true */)
{
	if (comments)
		*pStream << "\n <!-- Feedback comments --> \n";

	*pStream << "<"
			 << pName;

	clseBayTableWidget::DrawOptions(pStream);

	if (mNumberOfItemsToDisplay != 3) // default
		*pStream << " SIZE=" << mNumberOfItemsToDisplay;
	
	if (*mAlternateColor)
		*pStream << " ALTERNATECOLOR=\"" << mAlternateColor << "\"";

	*pStream << ">";

	if (comments)
		*pStream << "\n";

}

// Initializing the number of cells to display
// and loading the feedback details
bool clseBayFeedbackWidget::Initialize()
{
	//int iTotalNumCell = 0;
	
	SetNumCols(1);
	SetNumItems(mNumberOfItemsToDisplay);

	// safety
	if (!mpMarketPlace) 
		return false;

	// Create the User ID Widget
	mpUserIdWidget = new clsUserIdWidget(mpMarketPlace, GetApp());
	mpUserIdWidget->SetShowUserStatus(true);
	mpUserIdWidget->SetUserIdLink(true);
	mpUserIdWidget->SetIncludeEmail(false);
	mpUserIdWidget->SetUserIdBold(false);

    mpUser = mpWidgetHandler->GetWidgetContext()->GetUser();

	// safety
	if (!mpUser)
		return false;

	//
	// Let's get the vector of feedback detail items,
	// the number of feedback items in the vector,
	// the user's score, and the user's Id
	//
	mpvItemFeedback = mpUser->GetFeedback()->GetItems(1,
													  mNumberOfItemsToDisplay,
													  &mNumberOfFeedback);
	mscore = mpUser->GetFeedback()->GetScore();
	mpMyUserId = mpUser->GetUserId();

	//
	// The user cannot ask more Items than he have
	//
	if(mNumberOfFeedback < mNumberOfItemsToDisplay)
	{
		// The user have asked more items than he have
		SetNumItems(mNumberOfFeedback);
	}

	// Total number of cell to display
	// Number of Items * Number of cell per Item
	// mNumItems is the number of cells of feedback to display
	//iTotalNumCell = mNumItems;

	return true;
}

// This routine have to be called n = 0..mNumItems-1 times 
bool clseBayFeedbackWidget::EmitCell( ostream *pStream, int n)
{
	// Interesting formatting things
// petra	time_t					theTimeT;
// petra	struct tm				*pTheTime;
// petra	char					theTime[40];
	char					*pSafeText;
    clsFeedbackItem         *pItem;
	//samuel au, 4/8/99
// petra	clseBayTimeWidget		theTimeWidget;
// petra	TimeZoneEnum			timeZone;
	//end

	assert(mpvItemFeedback);
    if (mpvItemFeedback->size() <= n)
        return false;

    pItem = *(mpvItemFeedback->begin() + n);

// petra	theTimeT	= pItem->mTime;
// petra	pTheTime	= localtime(&theTimeT); //yp
	//samuel au, 4/8/99
// petra	timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra	theTimeWidget.SetTime(theTimeT);
	clseBayTimeWidget theTimeWidget(mpMarketPlace, 1, 2, pItem->mTime);	// petra
// petra	theTimeWidget.SetTimeZone(timeZone);
	//end
// petra	strftime(theTime, sizeof(theTime), "%m/%d/%y %H:%M:%S %z", pTheTime);

	// output table stuff
	*pStream	<<	"<td><table WIDTH=\"100%\" BORDER=\"1\"><tr><td>"
				<<	"<table WIDTH=\"100%\" BORDER=\"0\">"
				<<	"<tr><td BGCOLOR=\""
                <<  (*mAlternateColor ? mAlternateColor : "#EFEFEF")
                <<  "\" ALIGN=\"left\"><b>User: </b>";	

	// output user 
	assert(mpUserIdWidget);
	mpUserIdWidget->SetUserInfo(pItem->mCommentingUserId, 
//								pCommenter->GetEmail(),
								"ERROR",
			 					UserStateEnum(pItem->mCommentingUserState),
								mpMarketPlace->UserIdRecentlyChanged(pItem->mCommentingUserIdLastModified),
								pItem->mCommentingUserScore);
	mpUserIdWidget->EmitHTML(pStream);

	// output date & time
	*pStream	<<	" <b>Date:</b> ";
	//samuel au, 4/8/99
	theTimeWidget.EmitHTML(pStream);
	//			<<	theTime;
	//end

	// output table stuff
	*pStream	<<	"</td></tr></table><table WIDTH=\"100%\"";
    
    if (*mColor)
        *pStream << " BGCOLOR=\"" << mColor << "\"";
    
    *pStream << "><tr><td>";


	*pStream	<< "<strong>";

	// output Complaint, Praise, or Neutral, with color coding
	switch (pItem->mType)
	{
		
	case FEEDBACK_NEGATIVE:
		*pStream << "<font color=red>Complaint</font>:"
			"</strong>"
			" ";
		break;
	case FEEDBACK_POSITIVE:
		*pStream << "<font color=green>Praise</font>:"
			"</strong>"
			"    ";
		break;
	case FEEDBACK_NEUTRAL:
	case FEEDBACK_NEGATIVE_SUSPENDED:
	case FEEDBACK_POSITIVE_SUSPENDED:
		*pStream << "Neutral:"
			"</strong>"
			"   ";
		break;
	default:
		*pStream << ":"
			"</strong>"
			"          ";
		break;
	}
	
	
	// Pass the text through a filter to make it "safe"
	pSafeText	= clsUtilities::StripHTML(pItem->mText);

	*pStream <<	pSafeText
			  <<	"\n"
			  <<	flush;

	delete pSafeText;

	// output table stuff
	*pStream	<<	"</td></tr></table></td></tr></table></td>";
		
	return true;
}

// For translation to and from text.
void clseBayFeedbackWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pValue;
    int i;

    // Let's run through our known attributes and check them out.
	pValue = GetParameterValue("SIZE", pvArgs);
    if (pValue)
    {
        i = atoi(pValue);
        if (i > 0)
            SetNumberOfItemToDisplay(i);
    }

    if (GetParameterValue("EMAIL", pvArgs))
        mIncludeEmail = true;
	pValue = GetParameterValue("ALTERNATECOLOR", pvArgs);
    if (pValue && *pValue)
        SetAlternateColor(pValue);

    clseBayTableWidget::SetParams(pvArgs);
}

void clseBayFeedbackWidget::SetParams(const void *pData, 
                                        const char *pStringBase, 
                                        bool mFixBytes)
{
    clseBayFeedbackWidgetOptions *pOptions;

    pOptions = (clseBayFeedbackWidgetOptions *) pData;

    // Reverse if needed.
    if (mFixBytes)
    {
        pOptions->mAlternateColor = clsUtilities::FixByteOrder32(pOptions->mAlternateColor);
        pOptions->mSize = clsUtilities::FixByteOrder32(pOptions->mSize);
        pOptions->mTableOptionsOffset = clsUtilities::FixByteOrder32(pOptions->mTableOptionsOffset);
        pOptions->mIncludeEmail = clsUtilities::FixByteOrder32(pOptions->mIncludeEmail);
//      Expansion is so far unused. This is so we can add to the format without breaking all the parsed data.
//        pOptions->mExpansionOffset = clsUtilities::FixByteOrder32(pOptions->mExpansionOffset);
    }

    // Fix the alternate color.
    if (pOptions->mAlternateColor != -1)
        SetAlternateColor(pStringBase + pOptions->mAlternateColor);

    // Fix the number of items.
    if (pOptions->mSize)
        SetNumberOfItemToDisplay(pOptions->mSize);

    if (pOptions->mIncludeEmail)
        mIncludeEmail = true;

    // And let us fix the one higher up.
    if (pOptions->mTableOptionsOffset != -1)
        clseBayTableWidget::SetParams((const void *) (pStringBase + pOptions->mTableOptionsOffset), pStringBase, mFixBytes);
}

long clseBayFeedbackWidget::GetBlob(clsDataPool *pDataPool, 
                                      bool mReverseBytes)
{
    clseBayFeedbackWidgetOptions theOptions;

    if (*mAlternateColor)
        theOptions.mAlternateColor = pDataPool->AddString(mAlternateColor);
    else
        theOptions.mAlternateColor = -1;

    theOptions.mSize = mNumberOfItemsToDisplay;
    theOptions.mIncludeEmail = mIncludeEmail;

    theOptions.mTableOptionsOffset = clseBayTableWidget::GetBlob(pDataPool, mReverseBytes);

    theOptions.mExpansionOffset = -1;

    if (mReverseBytes)
    {
        theOptions.mAlternateColor = clsUtilities::FixByteOrder32(theOptions.mAlternateColor);
        theOptions.mSize = clsUtilities::FixByteOrder32(theOptions.mSize);
        theOptions.mTableOptionsOffset = clsUtilities::FixByteOrder32(theOptions.mTableOptionsOffset);
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

