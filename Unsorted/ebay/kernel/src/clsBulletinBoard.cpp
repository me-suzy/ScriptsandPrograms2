/*	$Id: clsBulletinBoard.cpp,v 1.6 1998/12/06 05:31:45 josh Exp $	*/
//
//	File:	clsBulletinBoard.cpp
//
//	Class:	clsBulletinBoard
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				The repository for all marketplaces
//
//	Modifications:
//				- 05/07/97 michael	- Created
//

#include "eBayKernel.h"


//
// Default Constructor
//
clsBulletinBoard::clsBulletinBoard(BulletinBoardId id,
								   const char *pName,
								   const char *pShortName,
								   const char *pShortDescription,
								   const char *pPicture,
								   const char *pDescription,
								   int maxPostCount,
								   int maxPostAge,
								   unsigned int controlFlags,
								   unsigned int type,
								   time_t lastPostTime)

{
	mId					=	id;
	if (pName)
	{
		mpName	=	new char [strlen(pName) + 1];
		strcpy(mpName, pName);
	}
	else
		mpName = NULL;

	if (pShortName)
	{
		mpShortName	= new char [strlen(pShortName) + 1];
		strcpy(mpShortName, pShortName);
	}
	else
		mpShortName = NULL;

	if (pShortDescription)
	{
		mpShortDescription = new char [strlen(pShortDescription) + 1];
		strcpy(mpShortDescription, pShortDescription);
	}
	else
		mpShortDescription = NULL;

	if (pPicture)
	{
		mpPicture = new char [strlen(pPicture) + 1];
		strcpy(mpPicture, pPicture);
	}
	else
		mpPicture = NULL;

	if (pDescription)
	{
		mpDescription = new char [strlen(pDescription) + 1];
		strcpy(mpDescription, pDescription);
	}
	else
		mpDescription = NULL;

	mMaxPostCount		=	maxPostCount;
	mMaxPostAge			=	maxPostAge;
	mControlFlags		=	controlFlags;
	mType				=	type;
	mLastPostTime		=	lastPostTime;
	return;
}

//
// Destructor
//
clsBulletinBoard::~clsBulletinBoard()
{
	delete[]	mpName;
	delete[]	mpShortName;
	delete[]	mpShortDescription;
	delete[]	mpPicture;
	delete[]	mpDescription;
	return;
}


//
// AddEntry
//	Adds an entry to the bulletin board
//
void clsBulletinBoard::AddEntry(clsUser *pUser,
								char *pEntry)
{
	// Just do it!
	gApp->GetDatabase()->AddBulletinBoardEntry(mId,
											 pUser->GetId(),
											 pEntry);

	// Trim to n entries if it is not an essay board
	if ((mType & BULLETIN_BOARD_ESSAY) == 0)
	{
		gApp->GetDatabase()->TrimBulletinBoard(mId, 200);
	}

	return ;
}


//
// GetAllEntries
//
void clsBulletinBoard::GetAllEntries(BulletinBoardEntryList *plEntries,
									 int maxPostAgeMinutes)
{
	int				maxPostAgeSeconds;

	maxPostAgeSeconds	= maxPostAgeMinutes * 60;

	// Just let the db do it
	gApp->GetDatabase()->GetAllBulletinBoardEntries(mId,
													plEntries,
													maxPostAgeSeconds);

	return;
}

int clsBulletinBoard::GetId()
{
	return	mId;
}

//
// GetName
//
char *clsBulletinBoard::GetName()
{
	return	mpName;
}

// 
// GetShortName
//
char *clsBulletinBoard::GetShortName()
{
	return	mpShortName;
}

// 
// GetShortDescription
//
char *clsBulletinBoard::GetShortDescription()
{
	return	mpShortDescription;
}

// 
// GetPicture
//
char *clsBulletinBoard::GetPicture()
{
	return	mpPicture;
}

//
// GetDescription
//
char *clsBulletinBoard::GetDescription()
{
	return	mpDescription;
}

//
// Other Getters ;-)
//
int clsBulletinBoard::GetMaxPostCount()
{
	return mMaxPostCount;
}

int clsBulletinBoard::GetMaxPostAge()
{
	return mMaxPostAge;
}

unsigned int clsBulletinBoard::GetControlFlags()
{
	return mControlFlags;
}

unsigned int clsBulletinBoard::GetType()
{
	return mType;
}


//
// Setters
//
void clsBulletinBoard::SetId(BulletinBoardId id)
{
	mId	= id;
	return;
}

//
// IsRestricted
//
const bool clsBulletinBoard::IsRestricted()
{
	return	(mControlFlags & BULLETIN_BOARD_RESTRICTED_POSTS) ? true : false;
}

//
// IsAvailable 
//
// whether the board is available for users
//
const bool clsBulletinBoard::IsAvailable()
{
	return	(mControlFlags & BULLETIN_BOARD_NOT_AVAILABLE) ? false : true;
}

//
// IsHTMLEnabled
//
const bool clsBulletinBoard::IsHTMLEnabled()
{
	return	(mControlFlags & BULLETIN_BOARD_HTML_ENABLED) ? true : false;
}

//
// IsInvisible
//
const bool clsBulletinBoard::IsInvisible()
{
	return	(mControlFlags & BULLETIN_BOARD_INVISIBLE) ? true : false;
}

//
// IsPostable
//
const bool clsBulletinBoard::IsPostable()
{
	return	(mControlFlags & BULLETIN_BOARD_NOT_POSTABLE) ? false : true;
}

//
// Is*
//
const bool clsBulletinBoard::IsGeneral()
{
	return	(mType & BULLETIN_BOARD_GENERAL) ? true : false;
}

const bool clsBulletinBoard::IsNews()
{
	return	(mType & BULLETIN_BOARD_NEWS) ? true : false;
}

const bool clsBulletinBoard::IsCustomerSupport()
{
	return	(mType & BULLETIN_BOARD_CUSTOMER_SUPPORT) ? true : false;
}

const bool clsBulletinBoard::IsCategorySpecific()
{
	return	(mType & BULLETIN_BOARD_CATEGORY_SPECIFIC) ? true : false;
}

const bool clsBulletinBoard::IsEssay()
{
	return	(mType & BULLETIN_BOARD_ESSAY) ? true : false;
}

//
// GetLastPostTime
//
//	Be CAREFUL. The ISAPI's build these objects ONCE, so this is
//	out of date. It's really for batch jobs
//
time_t clsBulletinBoard::GetLastPostTime()
{
	return	mLastPostTime;
}
