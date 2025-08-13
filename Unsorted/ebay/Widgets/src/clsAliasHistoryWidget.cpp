/*	$Id: clsAliasHistoryWidget.cpp,v 1.4.206.1 1999/06/10 19:11:43 mason Exp $	*/
// clsAliasHistoryWidget.cpp: implementation of the clsAliasHistoryWidget class.
//
//////////////////////////////////////////////////////////////////////
#include "widgets.h"
#include "clsAliasHistoryWidget.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsAliasHistoryWidget::clsAliasHistoryWidget(clsMarketPlace *pMarketPlace, 
											   clsApp *pApp,
											   UserAliasTypeEnum AliasType)
	: clseBayTableWidget(pMarketPlace, pApp)
{
	mpUser = NULL;
	mHistoryBegin = NULL;
	mAliasType = AliasType;
	mNumRows = 0;
}

clsAliasHistoryWidget::~clsAliasHistoryWidget()
{
	UserAliasHistoryVector::iterator	iHistory;

	// clean up
	for (iHistory = mVHistory.begin(); iHistory < mVHistory.end(); iHistory++)
	{
		delete (*iHistory);
	}
	mpUser = NULL;
	mHistoryBegin = NULL;
}

bool clsAliasHistoryWidget::Initialize()
{
	UserAliasHistoryVector::iterator	iHistory;

	if (mpUser == NULL)
		return false;

	// Get user id change history (userid, date)
	mpUser->GetAliasHistory(&mVHistory);

	// filter out the not related change history
	if (mVHistory.size() > 0)
	{
		for (iHistory = mVHistory.end() - 1; iHistory >= mVHistory.begin(); iHistory--)
		{
			if ((*iHistory)->mType != mAliasType)
			{
				delete (*iHistory);
				mVHistory.erase(iHistory);
			}
		}
	}

	mNumRows = mVHistory.size() + 1;
	mHistoryBegin = mVHistory.begin();

	// set caption
	if (mAliasType == EMailAlias)
	{
		SetCaption("<strong>Email History</strong>");
	}
	else
	{
		SetCaption("<strong>User ID History</strong>");
	}

	// set table parameters
	SetBorder(1);
	SetTableWidth(0);
	SetCellPadding(3);
	SetCellSpacing(2);

	return true;
}

bool clsAliasHistoryWidget::EmitHTML(ostream *pStream)
{
	bool ok;	// return status
	
	// start off with everything cool...
	ok = true;

	// initialize
	ok = ok && Initialize();

	// emit pre-table HTML.
	ok = ok && EmitPreTable(pStream);

	// emit <TABLE properties> tag. if client asked for incremental load,
	//  then don't emit table tag because it will be emitted for each row.
	if (!mIncremental) ok = ok && EmitBeginTableTag(pStream);

	// emit caption if there is one
	if (mpCaption)	ok = ok && EmitCaption(pStream);

	// emit header cells
	ok = ok && EmitHeaderCelles(pStream);

	// emit the rows and data cells
	for (mCurrentRow = 0; mCurrentRow < mNumRows; mCurrentRow++)
	{
		// emit a new row
		ok = ok && EmitBeginRowTag(pStream, mCurrentRow);

		ok = ok && EmitRow(pStream, mCurrentRow);

		// end previous row
		ok = ok && EmitEndRowTag(pStream);
	}


	// emit </TABLE> tag
	if (!mIncremental)
		ok = ok && EmitEndTableTag(pStream);

	// emit post-table HTML
	ok = ok && EmitPostTable(pStream);

	return ok;
}


bool clsAliasHistoryWidget::EmitRow(ostream *pStream, int CurrentRow)
{
	clsUserAliasHistory*	pHistory;
	clsUserAliasHistory*	pPrevHistory;

	char					TimeString[15];
	struct tm*				pTimeTm;
	time_t					TheTime;

	// determines which row
	if (CurrentRow < mNumRows - 1)
	{
		// Get the historical data
		assert(mHistoryBegin);
		pHistory = *(mHistoryBegin + CurrentRow);

		// the alias
		*pStream	<<	"<td>"
					<<	pHistory->mAlias
					<<	"</td>";

		// effect date
		if (CurrentRow == 0)
		{
			// get the creation date
			TheTime = mpUser->GetCreated();
		}
		else
		{
			// Get the previous alias change time if it exists
			pPrevHistory = *(mHistoryBegin + CurrentRow - 1);
			TheTime  = pPrevHistory->mModified;
		}
		pTimeTm = localtime(&TheTime);
		if (pTimeTm)
		{
			strftime(TimeString, sizeof(TimeString), "%b %d, %Y", pTimeTm);
		}
		else
		{
			TimeString[0] = '\0';
		}

		*pStream	<<	"<td>"
					<<	TimeString
					<<	"</td>";

		// Ending date
		TheTime = pHistory->mModified;
		pTimeTm = localtime(&TheTime);
		if (pTimeTm)
		{
			strftime(TimeString, sizeof(TimeString), "%b %d, %Y", pTimeTm);
		}
		else
		{
			TimeString[0] = '\0';
		}

		*pStream	<<	"<td>"
					<<	TimeString
					<<	"</td>";
	}
	else
	{
		// this is the last row. Get data from mpUser
		//
		// Alias
		*pStream	<<	"<td>";
		if (mAliasType == EMailAlias)
		{
			*pStream	<<	mpUser->GetEmail();
		}
		else
		{
			
			*pStream	<<	mpUser->GetUserId();
		}
		*pStream	<<	"</td>";

		// the effect date
		if (CurrentRow == 0)
		{
			// get the creation date
			TheTime = mpUser->GetCreated();
		}
		else
		{
			assert(mHistoryBegin);
			// Get the previous alias change time if it exists
			pPrevHistory = *(mHistoryBegin + CurrentRow - 1);
			TheTime  = pPrevHistory->mModified;
		}
		pTimeTm = localtime(&TheTime);
		if (pTimeTm)
		{
			strftime(TimeString, sizeof(TimeString), "%b %d, %Y", pTimeTm);
		}
		else
		{
			TimeString[0] = '\0';
		}

		*pStream	<<	"<td>"
					<<	TimeString
					<<	"</td>";
		
		// Ending date
		if (mpUser->GetUserState() != UserConfirmed  && 
			mpUser->GetUserState() != UserGhost )
		{
			*pStream	<<	"<td><a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/myinfo/user-not-registered.html\">"
						"not a registered user"
						"</a></td>";
		}
		else
			*pStream	<<	"<td>Present</td>";
	}

	return true;
}

bool clsAliasHistoryWidget::EmitHeaderCelles(ostream *pStream)
{
	*pStream	<<	"<tr><th>";

	if (mAliasType == EMailAlias)
	{
		*pStream << "Email";
	}
	else
	{
		*pStream << "User ID";
	}
		
	*pStream	<<	"</th>"
				<<	"<th>Effective Date</th>"
				<<	"<th>End Date</th></tr>";

	return true;
}

