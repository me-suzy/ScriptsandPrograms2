/*	$Id: clsUserIdWidget.cpp,v 1.4.200.5.68.1 1999/08/01 02:51:23 barry Exp $	*/
//
//	File:		clsUserIdWidget.cc
//
//	Class:		clsUserIdWidget
//
//	Author:		Wen Wen (wwen@ebay.com)
//
//	Function:
//		Widget to create User Id html segment
//
//
//	Modifications:
//				- 12/20/97 Wen	- Created
//				- 01/28/99 mila	- Modified EmitHTML() and EmitFeedback()
//								  to make user ID in URLs safe (replaced
//								  '&' with '%26')
//				- 07/01/99 nsacco - use GetPicsPath() for image urls
//
//////////////////////////////////////////////////////////////////////
#include "widgets.h"

#define RATING_DEF
#include "clsUserIdWidget.h"
#include "clsUserValidation.h"


#ifdef RATING_DEF
int   Ratings[] = {	10, 100, 500, 1000, 10000 };
char* Stars[]   = { "star-1.gif",
						"star-2.gif",
						"star-3.gif",
						"star-4.gif",
						"star-5.gif"
};
#else
extern const int   Ratings[];
extern const char* Stars[];
#endif

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

struct clsUserIdWidgetOptionsStruct
{
    char    mShowFeedback;
    char    mShowUserStatus;
    char    mShowMask;
    char    mShowStar;
    char    mBoldId;
    char    mIncludeEmail;
    char    mIsLinked;
    char    mIsWordFiller; // Not used by the widget -- just for packing.
    int32_t mExpansionOffset;
};

clsUserIdWidget::clsUserIdWidget(clsWidgetHandler *pHandler,
                                 clsMarketPlace *pMarketPlace,
                                 clsApp *pApp)
                                 : clseBayWidget(pHandler, pMarketPlace, pApp)
{
    mShowFeedback = true;
	mShowUserStatus = true;
	mShowMask		= true;
	mShowStar		= true;
	mBoldId			= false;
	mIsLinked		= true;
	mIncludeEmail	= false;
    mUseContext     = true;
	mShowAboutMe    = false;
	mShowUserId		= true;

	mpUserId		= NULL;
	mpEmail			= NULL;
	mUserFeedback	= 0;
	mUserState		= UserUnknown;
	mpDescription	= NULL;
	mUserValidated	= false;
	mUserIdOnly		= false;
	mRecentlyChanged = false;
	mUserFlags = 0;
}

clsUserIdWidget::clsUserIdWidget(clsMarketPlace *pMarketPlace, clsApp *pApp)
	: clseBayWidget(pMarketPlace, pApp)
{
	mShowFeedback	= true;
	mShowUserStatus = true;
	mShowMask		= true;
	mShowStar		= true;
	mBoldId			= false;
	mIsLinked		= true;
	mIncludeEmail	= false;
    mUseContext     = false;
	mUserIdOnly		= false;
	mShowAboutMe    = false;
	mShowUserId		= true;
	mUserValidated	= pMarketPlace->GetUsers()->GetUserValidation()->IsSoftValidated();

	mpUserId		= NULL;
	mpEmail			= NULL;
	mUserFeedback	= 0;
	mUserState		= UserUnknown;
	mpDescription	= NULL;
	mRecentlyChanged = false;
	mUserFlags = 0;
}

clsUserIdWidget::~clsUserIdWidget()
{
	delete [] mpUserId;
	delete [] mpEmail;
	delete [] mpDescription;
}

void clsUserIdWidget::DrawTag(ostream *pStream, const char *pName, bool /* comments = true */)
{
	*pStream << "<"
			 << pName;

	if (!mShowFeedback)
		*pStream << " NOFEEDBACK";

	if (!mShowUserStatus)
		*pStream << " NOSTATUS";

	if (!mShowMask)
		*pStream << " NOMASK";

	if (mBoldId)
		*pStream << " BOLD";

	if (!mIsLinked)
		*pStream << " NOLINK";

	if (mIncludeEmail)
		*pStream << " EMAIL";

	if (mShowAboutMe)
		*pStream << " ABOUTME";

	*pStream << ">";
}

bool clsUserIdWidget::EmitHTML(ostream *pStream)
{
	bool	UserIdIsEmail;
	char*	pMailToMail = NULL;
	bool	NeedToCloseA = false;

	char*	pSafeUserId = NULL;

	if (mpMarketPlace == NULL || mpApp == NULL)
		return false;

    if (mUseContext)
    {
        clsUser *pUser;
        pUser = mpWidgetHandler->GetWidgetContext()->GetUser();
        if (!pUser)
            return false;

        SetUser(pUser);
    }

	// Added by AlexP: allows hiding the userid and showing only the icons
	if (mShowUserId)
	{

		// check if user id is an email
		if (!mpUserId)
			return false;

		UserIdIsEmail = (strchr(mpUserId, '@') != NULL);

		// set the user id bold
		if (mBoldId)
			*pStream << "<b>";

		// Get mail for mailto:
		pMailToMail = clsUtilities::DrawSafeEmail(mpEmail);

		// Get safe user ID
		pSafeUserId = clsUtilities::MakeSafeString(mpUserId);

		if (mIsLinked)
		{
			// if user id is an email or user want to including email
			if (UserIdIsEmail || (mpDescription && (mIncludeEmail || mUserValidated)))
			{
				if (mpEmail && strchr(mpEmail, '@') != NULL)
				{
					*pStream << "<a href=\"mailto:" <<	pMailToMail << "\">";
					NeedToCloseA = true;
				}
				else if (strchr(pSafeUserId, '@') != NULL)
				{
					*pStream << "<a href=\"mailto:" <<	pSafeUserId << "\">";
					NeedToCloseA = true;
				}
			}
			else
			{
				// if user id is an email, user has to login to get other's emails
				*pStream	<< "<a href=\""
							<< mpMarketPlace->GetCGIPath(PageReturnUserEmail)
							<< "eBayISAPI.dll?ReturnUserEmail&requested="
							<< pSafeUserId
							<< "\">";

				NeedToCloseA = true;
			}
		}

		// description or user id
		if (mpDescription)
		{
			*pStream << mpDescription;
		}
		else
		{
			*pStream << mpUserId;
		}

		if (NeedToCloseA)
		{
			*pStream << "</a>";
		}

		if ((mIncludeEmail || mUserValidated) && !UserIdIsEmail && !mpDescription && !mUserIdOnly)
		{
			// for admin function show the email directly
			if(mpEmail && strchr(mpEmail, '@') != NULL)
			{
				*pStream	<<	" <a href=\"mailto:" 
							<<	pMailToMail 
							<<	"\">"
							<<	"("
							<<	mpEmail
							<<	")</a>";
			}
		}

		// end the user id bold
		if (mBoldId) 
			*pStream << "</b>";

	}	

//	if (strcmp(mpUserId, "guernseys") != 0)
//	{
		if (mShowFeedback) 
			EmitFeedback(pStream);

		if (mShowStar) 
			EmitStar(pStream);

		if (mShowUserStatus)
			EmitUserStatus(pStream);

		if (mShowMask && NeedMask()) 
			EmitMask(pStream);

		if (mShowAboutMe && HasAboutMe()) 
			EmitAboutMe(pStream);
//	}

	delete pMailToMail;
	delete pSafeUserId;

	return true;
}

bool clsUserIdWidget::NeedMask()
{
	assert(mpUserId);
	return (mRecentlyChanged && strchr(mpUserId, '@') == NULL);
}

void clsUserIdWidget::EmitFeedback(ostream *pStream)
{

	char *	pSafeUserId;

	// Get safe user ID
	pSafeUserId = clsUtilities::MakeSafeString(mpUserId);

	if (mUserFeedback == INT_MIN) mUserFeedback = 0;

//	if (mUserFeedback)
	{
		*pStream	<<	" <A HREF="
					<<	"\""
					<<	mpMarketPlace->GetCGIPath(PageViewFeedback)
					<<	"eBayISAPI.dll?ViewFeedback&userid="
					<<	pSafeUserId
					<<	"\""
					<<	">("
					<<	mUserFeedback
					<<	")</A>";

	}

	delete pSafeUserId;
}

void clsUserIdWidget::EmitStar(ostream *pStream)
{
	int		i;
	
	if (mUserFeedback >= Ratings[0])
	{
		for (i = sizeof(Ratings)/sizeof(int) - 1; i >= 0; i--)
		{
			if (mUserFeedback >= Ratings[i])
			{
				*pStream <<	" <A HREF=\""
						<<	mpMarketPlace->GetHTMLPath()
						<<	"help/basics/g-stars.html"
						<<	"\">"
							"<IMG "
							"align=\"absmiddle\" border=0 "
							"alt=\"star\" "
							"height=23 "
							"width=23 "
							"src=\""
						<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
						<<	Stars[i]
						<<	"\">"
						<<	"</A>";
				break;
			}
		}
	}


}

void clsUserIdWidget::EmitAboutMe(ostream *pStream)
{
	*pStream	<<	" <A HREF="
					"\""
				<<  mpMarketPlace->GetMembersPath()
				<< "aboutme/"
				<<	mpUserId
				<<	"/\""
					">"
					"<IMG "
					"align=\"absmiddle\" border=0 "
					"alt=\"about me\" "
					"height=8 "
					"width=23 "
					"src=\""
				<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				<<	"aboutme-small.gif"
					"\">"
					"</A> ";
}

void clsUserIdWidget::EmitUserStatus(ostream *pStream)
{
	// poon 03/09/98. if registered, don't need to show anything
	if (mUserState == UserConfirmed || mUserState == UserGhost)
		return;

	// append registration status
	*pStream	<<	" <A HREF=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"help/myinfo/user-not-registered.html"
				<<	"\""
				<<	">"
				<<	"<FONT size=2>"
				<<	"(not a registered user)"
				<<	"</FONT></A>";
	
}

void clsUserIdWidget::EmitMask(ostream *pStream)
{
	*pStream	<<	" <A HREF=\""
//				<<	mpMarketPlace->GetHTMLPath()
//				<<	"faq-UserId.html#16"
				<<	mpMarketPlace->GetCGIPath(PageGetUserEmail)
				<<	"eBayISAPI.dll?GetUserEmail&userid="
				<<	mpUserId
				<<	"\">"
				<<	"<IMG "
					"border=0 "
					"alt=\"mask\" "
					"height=15 "
					"width=21 "
					"src=\""
				<<	mpMarketPlace->GetPicsPath()	// nsacco 07/01/99
				<<	"mask.gif"
					"\">"
				<<	"</A>";

}

void clsUserIdWidget::SetUser(clsUser* pUser)
{
	clsFeedback* pFeedback;

	delete [] mpUserId;
	mpUserId = NULL;

	if (pUser->GetUserId())
	{
		mpUserId = new char[strlen(pUser->GetUserId())+1];
		strcpy(mpUserId, pUser->GetUserId());
	}

	delete [] mpEmail;
	mpEmail = NULL;

	if (pUser->GetEmail())
	{
		mpEmail = new char[strlen(pUser->GetEmail()) + 1];
		strcpy(mpEmail, pUser->GetEmail());
	}

	mUserState = pUser->GetUserState();
	mRecentlyChanged = pUser->UserIdRecentlyChanged();

	if (mShowAboutMe && pUser->HasAboutMePage())
		mShowAboutMe = true;
	else
		mShowAboutMe = false;

	if (mShowFeedback)
	{
		// DON'T delete the pFeedback object because clsUser will do it
		pFeedback = pUser->GetFeedback();
		mUserFeedback = pFeedback->GetScore();
	}

	delete [] mpDescription;
	mpDescription = NULL;

}

void clsUserIdWidget::SetUserInfo(char* pUserId, 
								  char* pUserEmail,
								  UserStateEnum	UserState,
								  bool	RecentlyChanged,
								  int	Feedback /*=0*/,
								  int   UserFlags /*=0*/)
{
	
	delete [] mpUserId;
	mpUserId = NULL;

	if (pUserId)
	{
		mpUserId = new char[strlen(pUserId)+1];
		strcpy(mpUserId, pUserId);
	}

	delete [] mpEmail;
	mpEmail = NULL;

	if (pUserEmail)
	{
		mpEmail = new char[strlen(pUserEmail) + 1];
		strcpy(mpEmail, pUserEmail);
	}

	mUserState       = UserState;
	mRecentlyChanged = RecentlyChanged;
	mUserFeedback    = Feedback;
	mUserFlags       = UserFlags;

	// set description to null
	delete [] mpDescription;
	mpDescription = NULL;
}

void clsUserIdWidget::SetDescription(char* pDescription)
{
	delete [] mpDescription;
	mpDescription = NULL;

	if (pDescription)
	{
		mpDescription = new char [strlen(pDescription)+1];
		strcpy(mpDescription, pDescription);
	}

	return;
}


bool clsUserIdWidget::HasAboutMe()
{
	// UserFlagHasAboutMePage is defined in clsUser.h.
	return ( (mUserFlags & UserFlagHasAboutMePage) > 0);
}

void clsUserIdWidget::SetParams(vector<char *> *pvArgs)
{
    // Let's run through our known attributes and check them out.
    if (GetParameterValue("BOLD", pvArgs))
        mBoldId = true;

    if (GetParameterValue("NOLINK", pvArgs))
        mIsLinked = false;

    if (GetParameterValue("NOFEEDBACK", pvArgs))
        mShowFeedback = false;

    if (GetParameterValue("NOSTATUS", pvArgs))
        mShowUserStatus = false;

    if (GetParameterValue("NOMASK", pvArgs))
        mShowMask = false;

    if (GetParameterValue("NOSTAR", pvArgs))
        mShowStar = false;

    if (GetParameterValue("EMAIL", pvArgs))
        mIncludeEmail = true;

	if (GetParameterValue("ABOUTME", pvArgs))
		mShowAboutMe = true;
}

void clsUserIdWidget::SetParams(const void *pData, const char *, bool)
{
    clsUserIdWidgetOptionsStruct *pOptions;

    pOptions = (clsUserIdWidgetOptionsStruct *) pData;

    mShowFeedback = pOptions->mShowFeedback != 0;
    mShowUserStatus = pOptions->mShowUserStatus != 0;
    mShowMask = pOptions->mShowMask != 0;
    mShowStar = pOptions->mShowStar != 0;
    mBoldId = pOptions->mBoldId != 0;
    mIncludeEmail = pOptions->mIncludeEmail != 0;
    mIsLinked = pOptions->mIsLinked != 0;

    return;
}

long clsUserIdWidget::GetBlob(clsDataPool *pDataPool, bool mReverseBytes)
{
    clsUserIdWidgetOptionsStruct theOptions;

    theOptions.mShowFeedback = mShowFeedback;
    theOptions.mShowUserStatus = mShowUserStatus;
    theOptions.mShowMask = mShowMask;
    theOptions.mShowStar = mShowStar;
    theOptions.mBoldId = mBoldId;
    theOptions.mIncludeEmail = mIncludeEmail;
    theOptions.mIsLinked = mIsLinked;
    theOptions.mIsWordFiller = '\0';
    theOptions.mExpansionOffset = -1;

    if (mReverseBytes)
    {
        theOptions.mExpansionOffset = clsUtilities::FixByteOrder32(theOptions.mExpansionOffset);
    }

    return pDataPool->AddData(&theOptions, sizeof (theOptions));
}
