/*	$Id: clseBayUserWidget.cpp,v 1.3 1998/12/06 05:23:26 josh Exp $	*/
//
//	File:	clseBayUserWidget.cpp
//
//	Class:	clseBayUserWidget
//
//	Author:	Chad Musick
//
//	Function:
//			Widget that shows comments about a user via clseBayTableWidget.
//
// Modifications:
//				- 10/14/97	Chad - Created
//
#include "widgets.h"
#include "clseBayUserWidget.h"

clseBayUserWidget::clseBayUserWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace), mAdjustForHeader(0)
{
	mpvFeedback = NULL;
	mpUser = NULL;
	mTargetUser[0] = '\0';
}

clseBayUserWidget::~clseBayUserWidget()
{
//	FeedbackItemVector::iterator i;

	mvFeedbackIndices.erase(mvFeedbackIndices.begin(),
		mvFeedbackIndices.end());

	// don't clean up, clsFeedback will do it
	//if (mpvFeedback)
	//{
	//	for (i = mpvFeedback->begin();
	//		i != mpvFeedback->end();
	//		++i)
	//	{
	//		delete *i;
	//	}
	//	mpvFeedback->erase(mpvFeedback->begin(),
	//		mpvFeedback->end());
	//}

	// DON'T delete the pFeedback object because clsUser will do it
	//if (mpUser)
	//{
	//	delete mpUser->GetFeedback();
	//	delete mpUser;
	//}
	mpUser = NULL;
	mpvFeedback = NULL;
}

void clseBayUserWidget::SetParams(vector<char *> *pvArgs)
{
	int p;
	char *cArg;
	char cArgCopy[256];
	char *cName;
	char *cValue;
	bool handled = false;
	int x;

	// reverse through these so that deletions are safe.
	//  stop at 1, because we don't care about the tagname
	for (p=pvArgs->size()-1; p>=1; p--)
	{
		cArg = (*pvArgs)[p];
		handled = false;

		// separate the name from the value
		strncpy(cArgCopy, cArg, sizeof(cArgCopy)-1);
		cName = cArgCopy;
		cValue = strchr(cArgCopy, '=');
		if (cValue) 
		{
			cValue[0]='\0';		// lock in cName
			cValue++;			// set cValue
		}
		else
			cValue="";

		// remove start & end quotes if they were provided
		x = strlen(cValue);
		if ((x>1) && (cValue[0]=='\"' && cValue[x-1]=='\"'))
		{
			cValue[x-1]='\0';		// remove ending "
			cValue++;				// remove beginning "
		}

		// try to handle this parameter
		if ((!handled) && (strcmp("targetuser", cName)==0))
		{
			SetTargetUser(cValue);
			handled=true;
		}

		// if this parameter was handled, remove (and delete the char*) it from the vector
		if (handled)
		{
			pvArgs->erase(pvArgs->begin()+p);	
			delete [] cArg;	// don't need the parameter anymore
		}
	}

	// ok, now pass the rest of the parameters up to the parent to handle
	clseBayTableWidget::SetParams(pvArgs);

}

// Get the users
bool clseBayUserWidget::Initialize()
{
	clsUsers	*pUsers;
	vector<int> vIds;
	int			i, x, vectorSize;
	time_t		CurrentTime;

	CurrentTime = time(0);

	// seed the random number generator with the current time
	srand((unsigned int)CurrentTime);

	// get the marketplace's clsItems object
	if (mpMarketPlace)
		pUsers = mpMarketPlace->GetUsers();
	else
		return false;

	// Get the user ids of all users with sufficiently high feedback
	pUsers->GetUserIdsByFeedback(50, &vIds);

	if (vIds.empty())
		return false;

	// Adjust mNumItems to account for the header string
	if (!mAdjustForHeader)
	{
		++mNumItems;
		mAdjustForHeader = 1;
	}

	if (mTargetUser[0] == '\0') // no target
	{
		// just select a random user
		mpUser = pUsers->GetUser(vIds[rand() % vIds.size()]);
	}
	else
	{
		// select a random user
		mpUser = pUsers->GetUser(vIds[rand() % vIds.size()]);
		
		// keep selecting until the randomly selected user matches the target,
		//  or we've already tried 5 times the number of users
		i= 0;
		while ((!(strstr(mpUser->GetUserId(), mTargetUser))) && (i < vIds.size()*5))
		{
			delete mpUser;	// don't need it anymore
			mpUser = pUsers->GetUser(vIds[rand() % vIds.size()]);
			i++;
		}

	}

	// ok, now we've got a user


	vIds.erase(vIds.begin(), vIds.end());

	mpvFeedback = mpUser->GetFeedback()->GetItems();

	// get the size of the vector
	vectorSize = mpvFeedback->size();

	// if there aren't enough items in the vector, then reset mNumItems
	if (mNumItems > vectorSize) 
		mNumItems = vectorSize + 1;

	mvFeedbackIndices.reserve(mNumItems - 1);

	// randomly choose the appropriate number of items
	for (i = 1 /* Yes, 1 */; i < mNumItems; i++)
	{
		// keep choosing a random item until you get one that's not already chosen
		//  (don't want to show an item twice in the same list!)
		x = rand() % vectorSize;
		while (1)
		{
			x = rand() % vectorSize;

			if (find(mvFeedbackIndices.begin(),
				mvFeedbackIndices.end(), x) != mvFeedbackIndices.end())
				continue;

			if (((*mpvFeedback)[x])->mType != FEEDBACK_POSITIVE)
				continue;

			// Don't show the feedback on themselves.
			if (((*mpvFeedback)[x])->mId == ((*mpvFeedback)[x])->mCommentingId)
				continue;

			// check for vulgarity
			if (clsUtilities::TooVulgar(((*mpvFeedback)[x])->mText))
				continue;

			break;
		}

		// ok, you got one, so add the item id to the "chosen" vector
		mvFeedbackIndices.push_back(x);
	}

	return true;
}

// This will be called mNumItems times n=0..mNumItems-1
bool clseBayUserWidget::EmitCell(ostream *pStream, int n)
{
	clsFeedbackItem		*pFeedbackItem;
	char				nameBuffer[256];
	char				*pShortName;
	char				*cSuperCleanText;
	char				*cDelimitedSuperCleanText;
	char				*cSafeDelimitedSuperCleanText;
	char				*cDelimitedUserName = NULL;

	if (n == 0)
	{
		// in case username is really long
		cDelimitedUserName = clsUtilities::Delimit(mpUser->GetUserId());

		*pStream <<	"<TD><FONT size = \"2\">"
					"<a href=\""
				<<	mpMarketPlace->GetCGIPath(PageViewFeedback)
				<<	"eBayISAPI.dll?ViewFeedback&userid="
				<<	mpUser->GetUserId()
				<<	"\">"
					"Comments about "
				<<	cDelimitedUserName
				<<	" ("
				<<	mpUser->GetFeedback()->GetScore()
				<<	")</A>"
					"</FONT><img align=\"absmiddle\" border=0 alt=\"star\" "
					"height=23 "
					"width=23 "
					"src=\""
				<<	mpMarketPlace->GetHTMLPath()
				<<	"pics/star-1.gif"
					"\""
					"></TD>\n";

		delete [] cDelimitedUserName;
		return true;
	}

	pFeedbackItem = (*mpvFeedback)[mvFeedbackIndices[n - 1]];
	strncpy(nameBuffer, pFeedbackItem->mCommentingUserId,
		255);
	nameBuffer[255] = '\0';
	pShortName = strtok(nameBuffer, "@");

	if (!pShortName || !*pShortName)
	{
		strcpy(nameBuffer, "anon");
		pShortName = nameBuffer;
	}

	// make the text super-clean
	cSuperCleanText = clsUtilities::SuperClean(pFeedbackItem->mText);

	// delimit text in case it's one big word
	cDelimitedSuperCleanText = clsUtilities::Delimit(cSuperCleanText);

	// make the text safe
	cSafeDelimitedSuperCleanText = clsUtilities::StripHTML(cDelimitedSuperCleanText);

	*pStream <<		"<TD><FONT size = \"2\">"
					"\""
				<<	cSafeDelimitedSuperCleanText
				<< "\""
//				<<	" - "
//				<<	pShortName
				<<	"</FONT></TD>\n";

	// delete the new strings
	delete [] cSuperCleanText;
	delete [] cDelimitedSuperCleanText;
	delete [] cSafeDelimitedSuperCleanText;
	delete [] cDelimitedUserName;

	return true;
}

