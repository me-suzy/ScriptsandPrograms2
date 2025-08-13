/*	$Id: clseBayMyFeedbackWidget.cpp,v 1.7.132.1 1999/08/01 02:51:27 barry Exp $	*/
//
//	File:	clseBayMyFeedbackWidget.cpp
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
//				- 07/01/99	nsacco - use GetPicsPath()
//
#include "widgets.h"
#include "clseBayMyFeedbackWidget.h"

int   Ratings1[] = { 10, 100, 500, 1000, 10000 };
char* Stars1[]   = { "star-1.gif",
						"star-2.gif",
						"star-3.gif",
						"star-4.gif",
						"star-5.gif"
};

struct clseBayMyFeedbackWidgetOptions
{
    int32_t mTitleColor;
    int32_t mSize;
    int32_t mTableOptionsOffset;
    int32_t mExpansionOffset;
};

clseBayMyFeedbackWidget::clseBayMyFeedbackWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	SetScoreMinForStar(10);
	SetNumberOfItemToDisplay(3);
	mscore				= 0;
	mpvItemFeedback		= NULL;
	mpUser				= NULL;
	mNumberOfFeedback	= 0;
	mTitleColor[0]		= '\0';
    mUseContext = false;
    mpUserIdWidget      = NULL;
}

clseBayMyFeedbackWidget::clseBayMyFeedbackWidget(clsWidgetHandler *pHandler,
    clsMarketPlace *pMarketPlace,
    clsApp *pApp) : clseBayTableWidget(pHandler, pMarketPlace, pApp)
{
    SetScoreMinForStar(10);
    SetNumberOfItemToDisplay(3);
    mscore              = 0;
    mpvItemFeedback     = NULL;
    mpUser              = NULL;
    mNumberOfFeedback   = 0;
    mTitleColor[0]      = '\0';
    mpUserIdWidget      = NULL;
    mUseContext = true;
}

clseBayMyFeedbackWidget::~clseBayMyFeedbackWidget()
{

//	FeedbackItemVector::iterator i;

	if(mpUserIdWidget)
	{
		delete mpUserIdWidget;
	}
	
	// don't clean up. clsFeedback will do it
	//if(mpvItemFeedback)
	//{
	//	for(i=mpvItemFeedback->begin();
	//		i != mpvItemFeedback->end();
	//		++i)
	//		{
	//			delete *i;
	//		}
//
//			mpvItemFeedback->erase(mpvItemFeedback->begin(),
//									mpvItemFeedback->end());
//	}

	// DON'T delete the Feedback object because clsUser will do it
	//if(mpUser)
	//{
	//	delete mpUser->GetFeedback();
	//	mpUser->SetFeedback(NULL);
	//}

}


// Initializing the number of cells to display
// and loading the feedback details
bool clseBayMyFeedbackWidget::Initialize()
{
	int iTotalNumCell = 0;
	
	SetNumCols(EBAY_NUMBER_FEEDBACK_COLUMNS);
	SetNumItems(mNumberOfItemsToDisplay);

	// safety
	if (!mpMarketPlace)
		return false;

	// Create the User ID Widget
	mpUserIdWidget = new clsUserIdWidget(mpMarketPlace, GetApp());

    if (mUseContext)
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
	if(mNumberOfFeedback < mNumItems)
	{
		// The user have asked more items than he have
		SetNumItems(mNumberOfFeedback);
	}

	
	// Total number of cell to display
	// Number of Items * Number of cell per Item
	// mNumItems is the number of cells of feedback to display
	iTotalNumCell = mNumItems * EBAY_FEEDBACK_CELLS_PER_ITEM;

	//
	// Changing the number of cells to display
	//
	SetNumItems(iTotalNumCell);

	return true;
}


// Before the table create a header 
bool clseBayMyFeedbackWidget::EmitPreTable(ostream *pStream)
{
	//clsUserIdWidget		*pUserIdWidget;
	char				titleFeedback[EBAY_FEEDBACK_STRING_LENGTH]; // My Feedback

	memset(titleFeedback,0,sizeof(titleFeedback));
	// Write the title and the score of the user
	// Write the score of the user score = praise - complaint
	// change pItem
	// Write the title
	if (mTitleColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		0
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		0
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
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		0
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		0
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

	*pStream	<<	"<TR><TH ALIGN=CENTER>";

	// define the title of the feedback section
	strcpy(titleFeedback,"<FONT face=\"arial, helvetica\" size=\"3\">My FeedBack </FONT>");

/*
	// create the User ID widget
	if(mpUser)
	{
		mpUserIdWidget->SetUser(mpUser);
		mpUserIdWidget->SetShowUserStatus(false);
		mpUserIdWidget->SetDescription(titleFeedback);
		mpUserIdWidget->SetShowMask(false);
		mpUserIdWidget->SetUserIdLink(false);
		mpUserIdWidget->SetUserIdBold(true);	
		mpUserIdWidget->SetShowFeedback(true);	
		mpUserIdWidget->SetShowStar(true);	
		mpUserIdWidget->EmitHTML(pStream);
	}
*/

	*pStream	<<	"<strong><FONT face=\"arial, helvetica\" size=\"3\">"
				<<	"My Feedback ("
				<<	mpUser->GetFeedback()->GetScore()
				<<	")"
				<<	"</FONT></strong>";

	// Write the star here if necessary
	if (mpUser->GetFeedback()->GetScore() >= (int)Ratings1[0])
	{
		for(int i = sizeof(Ratings1)/sizeof(int) - 1 ; i >= 0; i--)
		{
			if(mpUser->GetFeedback()->GetScore() >= (int)Ratings1[i])
			{
				*pStream <<	"<img src="
						 << "\""
						 << mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
						 <<	Stars1[i]
						 << "\""
						 << " WIDTH=\"23\" HEIGHT=\"23\" align=\"absmiddle\" border=\"0\" alt=\"star\""
					     <<	">";
				break;
			}
		}
	}

	*pStream	<<	"</TH></TR></TABLE>";

	if(mNumberOfFeedback > 0)
	{
		// Write the footer of the header 
		*pStream	<<	"<STRONG>"
			<<	"Recent feedback about me:"
			<<	"</STRONG>";
	}
	else
	{
		// Write the footer of the header 
		*pStream	<<	"<P>"
			<<	"(I have no feedback yet)"
			<<	"</P>";
	}
	
	*pStream	<<	flush;

	return true;
}


// End the List of Items
// Write ----> See all feedback about me
// And   ----> See all feedback I had left to others
bool clseBayMyFeedbackWidget::EmitPostTable(ostream *pStream)
{

	if(mNumberOfFeedback > 0)
	{
		// Open a table
		*pStream	<<		"<TABLE "
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
		
		// Write ----> See all feedback about me
		*pStream	<<	"<TR><TD>"
					<<	"<STRONG> <BR></STRONG>"
					<<	"<A HREF="
					<<	"\""
					<<	mpMarketPlace->GetCGIPath(PageViewFeedback)
					<<	"eBayISAPI.dll?ViewFeedback&"
					<<	"userid="
					<<	mpUser->GetUserId()
					<<	"\""
					<<	">"
					<<	"See all feedback about me"
					<<	"</A></TD></TR>";

		// Write ----> Review and respond to feedback about me
		*pStream	<<	"<TR><TD>"
					<<	"<A HREF="
					<<	"\""
					<<	mpMarketPlace->GetCGIPath(PageViewPersonalizedFeedback)
					<<	"eBayISAPI.dll?ViewPersonalizedFeedback"
					<<	"&userid="
					<<	mpUser->GetUserId()
					<<	"&pass="
					<<	mpUser->GetPasswordNoSalt()
					<<	"\""
					<<	">"
					<<	"Review and respond to feedback about me"
					<<	"</A></TD></TR>";

		// Write ----> See all feedback I have left about others
		*pStream	<<	"<TR><TD>"
					<<	"<A HREF="
					<<	"\""
					<<	mpMarketPlace->GetCGIPath(PageViewFeedbackLeft)
					<<	"eBayISAPI.dll?ViewFeedbackLeft&"
					<<	"userid="
					<<	mpUser->GetUserId()
					<<	"&pass="
					<<	mpUser->GetPasswordNoSalt()
					<<  "\""
					<<	">"
					<<	"See all feedback I have left about others"
					<<	"</A><BR></TD></TR>";

		// Close the table
		*pStream	<< "</TABLE>"
					<<	"<BR>"
					<<	flush;
	
	}

	return true;

}


// This routine have to be called n = 0..mNumItems-1 times 
bool clseBayMyFeedbackWidget::EmitCell( ostream *pStream, int n)
{

	// Commenting user caracteristics
//	clsUser				*pCommentingUser;			// The commenting user
//	FeedbackItemVector  *pvItemFeedbackComment;		// The feedback details of the commenting user
//	FeedbackItemVector::iterator k;
//	clsUserIdWidget		*pUserIdWidget;


	// Interesting formatting things
// petra	time_t		theTimeT;
// petra	struct tm	*pTheTime;
// petra	char		theDate[EBAY_FEEDBACK_STRING_LENGTH]; // The date mm/dd/yy
// petra	char		theTime[EBAY_FEEDBACK_STRING_LENGTH]; // The time HH:MI:SS
	char		typeOfFeedback[EBAY_FEEDBACK_STRING_LENGTH]; // Complaint, Praise or Neutral
	int			indiceItem = 0;
	//samuel au, 4/8/99
	clseBayTimeWidget	theTimeWidget(mpMarketPlace, -1, -1);	// petra
// petra	TimeZoneEnum		timeZone;
	//end

//	pCommentingUser = NULL;
//	pvItemFeedbackComment = NULL;
// petra	memset(theDate,0,sizeof(theDate));
// petra	memset(theTime,0,sizeof(theTime));
	memset(typeOfFeedback,0,sizeof(typeOfFeedback));

	indiceItem = (int) (n / EBAY_FEEDBACK_CELLS_PER_ITEM);

	// Keep the status of the feedback for future use
	// and write it in a string
	assert(mpvItemFeedback);
	switch((*mpvItemFeedback)[indiceItem]->mType)
	{
		case FEEDBACK_NEGATIVE:
			{
				// This is a Complain
				strcpy(typeOfFeedback,"<FONT color=\"red\">(complaint)</FONT>");
				break;
			}

		case FEEDBACK_POSITIVE:
			{
				// This is a praise
				strcpy(typeOfFeedback,"<FONT color=\"green\">(praise)</FONT>");
				break;
			}

		case FEEDBACK_NEUTRAL:
		case FEEDBACK_NEGATIVE_SUSPENDED:
		case FEEDBACK_POSITIVE_SUSPENDED:
			{
				// This is neutral
				strcpy(typeOfFeedback,"(neutral)");
				break;
			}

		default:
			{
				// No status to print
				memset(typeOfFeedback,0,sizeof(typeOfFeedback));
				break;
			}

	}
					

	switch(n % EBAY_FEEDBACK_CELLS_PER_ITEM)
	{
		case FeedbackCellDate:
			{
				// This is the first row
				// Writing the date in a cell
// petra				theTimeT	= (*mpvItemFeedback)[indiceItem]->mTime;
// petra				pTheTime	= localtime(&theTimeT); //yp
				//samuel au, 4/8/99
// petra				timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra				theTimeWidget.SetTimeZone(timeZone);
				//strftime(theDate, sizeof(theDate),"%m/%d/%y",pTheTime);
// petra				theTimeWidget.BuildDateString(theDate);
				//end
				*pStream	<<	"<TD ALIGN=\"CENTER\" width=\"15%\"";
				if(mColor[0] == '\0')
				{
					*pStream	<<	">";
				}
				else
				{
					*pStream	<<	" BGCOLOR =\""
								<<	mColor
								<<	"\""
								<<	">";
				}

				// if it's not the first row put the space
				if(n > 0 )
				{
					*pStream	<<	"<STRONG> <BR></STRONG>";
				}
				*pStream	<<	"<FONT size=\"2\">";
// petra							<<	theDate
				theTimeWidget.SetTime((*mpvItemFeedback)[indiceItem]->mTime);	// petra
				theTimeWidget.SetDateTimeFormat (1, -1);	// petra
				theTimeWidget.EmitHTML (pStream);	// petra
				*pStream	<<	"</FONT></TD>"
							<<	"\n";
				break;
			}

		case FeedbackCellCommentIdAndScore:
			{
				// Writing the commenting user ID
				*pStream	<<	" "
							<<	"<TD ALIGN=\"LEFT\" width=\"85%\"";
				
				if(mColor[0] == '\0')
				{
					*pStream	<<	">";
				}
				else
				{
					*pStream	<<	" BGCOLOR =\""
								<<	mColor
								<<	"\""
								<<	">";
				}

				// if it's not the first row put the space
				if(n > 1)
				{
					*pStream	<<	"<STRONG> <BR></STRONG>";
				}

				//
				// Writing the commenting user score
				// Caracteristics of the commenting user
				//
				assert(mpUserIdWidget);
				mpUserIdWidget->SetUserInfo((*mpvItemFeedback)[indiceItem]->mCommentingUserId, 
											(*mpvItemFeedback)[indiceItem]->mCommentingEmail,
											UserStateEnum((*mpvItemFeedback)[indiceItem]->mCommentingUserState),
											mpMarketPlace->UserIdRecentlyChanged((*mpvItemFeedback)[indiceItem]->mCommentingUserIdLastModified),
											(*mpvItemFeedback)[indiceItem]->mCommentingUserScore);
				mpUserIdWidget->SetShowUserStatus(false);
				mpUserIdWidget->SetUserIdLink(true);
				mpUserIdWidget->SetIncludeEmail(true);
				mpUserIdWidget->SetUserIdBold(false);
				mpUserIdWidget->EmitHTML(pStream);

				// Writing the type of feedback
				*pStream	<<	" ";
				*pStream	<<	typeOfFeedback
							<<	"</TD>"
							<<	"\n";

				break;
			}

		case FeedbackCellTime:
			{
				// Writing the time in a cell
// petra				theTimeT	= (*mpvItemFeedback)[indiceItem]->mTime;
// petra				pTheTime	= localtime(&theTimeT); //yp
				//samuel au, 4/8/99
// petra				timeZone = mpMarketPlace->GetCurrentTimeZone();
// petra				theTimeWidget.SetTimeZone(timeZone);
				//strftime(theTime, sizeof(theTime),"%H:%M:%S",pTheTime);
// petra				theTimeWidget.BuildTimeString(theTime);
				//end

				*pStream	<<	"<TD ALIGN=CENTER";
				if(mColor[0] == '\0')
				{
					*pStream	<<	">";
				}
				else
				{
					*pStream	<<	" BGCOLOR =\""
								<<	mColor
								<<	"\""
								<<	">";
				}

				*pStream	<<	"<FONT size=\"2\">";
// petra							<<	theTime
				theTimeWidget.SetTime((*mpvItemFeedback)[indiceItem]->mTime);	// petra
				theTimeWidget.SetDateTimeFormat (-1, 2);						// petra
				theTimeWidget.EmitHTML (pStream);								// petra
				*pStream	<<	"</FONT></TD>"
							<<	"\n";
				break;
			}
	
		case FeedbackCellMessage:
			{
				// Now we have to write the message sent by the commenting user
				*pStream	<<	"<TD ALIGN=LEFT";
				if(mColor[0] == '\0')
				{
					*pStream	<<	">";
				}
				else
				{
					*pStream	<<	" BGCOLOR =\""
								<<	mColor
								<<	"\""
								<<	">";
				}
				
				*pStream	<<	(*mpvItemFeedback)[indiceItem]->mText
							<<	"</TD>"
							<<	"\n";
				break;
			}

		default :
			{
				*pStream	<<	flush;
				break;
			}

	}


	*pStream	<<	flush;
	return true;

}

// For translation to and from text.
void clseBayMyFeedbackWidget::SetParams(vector<char *> *pvArgs)
{
    const char *pValue;
    int i;

    // Let's run through our known attributes and check them out.
	pValue = GetParameterValue("TITLECOLOR", pvArgs);
    if (pValue && *pValue)
        SetTitleColor(pValue);
	pValue = GetParameterValue("SIZE", pvArgs);
    if (pValue)
    {
        i = atoi(pValue);
        if (i > 0)
            SetNumberOfItemToDisplay(i);
    }

    clseBayTableWidget::SetParams(pvArgs);
}

void clseBayMyFeedbackWidget::SetParams(const void *pData, 
                                        const char *pStringBase, 
                                        bool mFixBytes)
{
    clseBayMyFeedbackWidgetOptions *pOptions;

    pOptions = (clseBayMyFeedbackWidgetOptions *) pData;

    // Reverse if needed.
    if (mFixBytes)
    {
        pOptions->mTitleColor = clsUtilities::FixByteOrder32(pOptions->mTitleColor);
        pOptions->mSize = clsUtilities::FixByteOrder32(pOptions->mSize);
        pOptions->mTableOptionsOffset = clsUtilities::FixByteOrder32(pOptions->mTableOptionsOffset);
//      Expansion is so far unused. This is so we can add to the format without breaking all the parsed data.
//        pOptions->mExpansionOffset = clsUtilities::FixByteOrder32(pOptions->mExpansionOffset);
    }

    // Fix the title color.
    if (pOptions->mTitleColor != -1)
        SetTitleColor(pStringBase + pOptions->mTitleColor);

    // Fix the number of items.
    if (pOptions->mSize)
        SetNumberOfItemToDisplay(pOptions->mSize);

    // And let us fix the one higher up.
    if (pOptions->mTableOptionsOffset != -1)
        clseBayTableWidget::SetParams((const void *) (pStringBase + pOptions->mTableOptionsOffset), pStringBase, mFixBytes);
}

long clseBayMyFeedbackWidget::GetBlob(clsDataPool *pDataPool, 
                                      bool mReverseBytes)
{
    clseBayMyFeedbackWidgetOptions theOptions;

    if (*mTitleColor)
        theOptions.mTitleColor = pDataPool->AddString(mTitleColor);
    else
        theOptions.mTitleColor = -1;

    theOptions.mSize = mNumberOfItemsToDisplay;

    theOptions.mTableOptionsOffset = clseBayTableWidget::GetBlob(pDataPool, mReverseBytes);

    theOptions.mExpansionOffset = -1;

    if (mReverseBytes)
    {
        theOptions.mTitleColor = clsUtilities::FixByteOrder32(theOptions.mTitleColor);
        theOptions.mSize = clsUtilities::FixByteOrder32(theOptions.mSize);
        theOptions.mTableOptionsOffset = clsUtilities::FixByteOrder32(theOptions.mTableOptionsOffset);
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}

#if 0
// temp disable this widget
bool clseBayMyFeedbackWidget::EmitHTML(ostream *pStream)
{
	bool ok;	// return status
	
	// start off with everything cool...
	ok = true;

	// initialize
	// ok = ok && this->Initialize();

	// emit pre-table HTML.
	// ok = ok && this->EmitPreTable(pStream);

	// emit <TABLE properties> tag. if client asked for incremental load,
	//  then don't emit table tag because it will be emitted for each row.
	// if (!mIncremental) ok = ok && this->EmitBeginTableTag(pStream);

	// emit caption
	// if (mpCaption) ok = ok && EmitCaption(pStream);

	*pStream << "<br><font color=red>The <b>\"My Feedback\"</b> section has been temporarily disabled. We apologize for the inconvenience.</font><br><br>\n" << flush; 

	// emit </TABLE> tag
	// if (!mIncremental) ok = ok && this->EmitEndTableTag(pStream);

	// emit post-table HTML
	// ok = ok && this->EmitPostTable(pStream);

	return ok;
}
#endif
