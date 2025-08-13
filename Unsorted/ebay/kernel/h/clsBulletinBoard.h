/*	$Id: clsBulletinBoard.h,v 1.5 1998/10/30 00:39:36 josh Exp $	*/
//
//	File:	clsBulletinBoard.h
//
//	Class:	clsBulletinBoard
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Represents a billetin board
//
// Modifications:
//				- 05/06/97 michael	- Created
//
#ifndef CLSBULLETINBOARD_INCLUDED
#include "list.h"
#include "algo.h"
#include "time.h"


//
// The following flags define various bulletin board
// control flags
//
#define BULLETIN_BOARD_HTML_ENABLED		0x00000001
#define BULLETIN_BOARD_RESTRICTED_POSTS	0x00000002
#define BULLETIN_BOARD_INVISIBLE		0x00000004	// board not shown in the dropdown
#define BULLETIN_BOARD_NOT_POSTABLE		0x00000008	// user can't post to board
#define BULLETIN_BOARD_NOT_AVAILABLE	0x00000010	// not available to user

//
// The following flags define various bulletin board
// types. They're "flags", as opposed to enums because
// a board can be in multiple groups (or so I assume)
//
#define BULLETIN_BOARD_GENERAL				0x00000001
#define BULLETIN_BOARD_CUSTOMER_SUPPORT		0x00000002
#define BULLETIN_BOARD_NEWS					0x00000004
#define BULLETIN_BOARD_CATEGORY_SPECIFIC	0x00000008
#define BULLETIN_BOARD_ESSAY				0x00000010

//
// These #defines tell us the maximum lengths of some
// bulletin board fields
//
#define	BULLETIN_BOARD_MAX_NAME_LENGTH			256
#define	BULLETIN_BOARD_MAX_SHORT_NAME_LENGTH	256
#define BULLETIN_BOARD_MAX_SHORT_DESC_LENGTH	256
#define BULLETIN_BOARD_MAX_PICTURE_LENGTH		256

// Convienent typedefs
class clsBulletinBoardEntry
{
	public:
		
		// Default ctor, dtor
		clsBulletinBoardEntry() :
		  mTime(0),
		  mId(0),
		  mpUserId(NULL),
		  mpEmail(NULL),
		  mUserIdLastChangeTime(0),
		  mFeedbackScore(0),
		  mLen(0),
		  mpEntry(NULL),
		  mUserFlags(0)
		{ 
			memset(mHost, 0x00, sizeof(mHost));
			return;
		};

		~clsBulletinBoardEntry() 
		{
			delete	mpEntry;
			delete	mpUserId;
			delete	mpEmail;
		}

		// Fancy CTOR
		clsBulletinBoardEntry(
				time_t	when,
				int id,
				char *pUserId,
				char *pEmail,
				time_t userIdLastChangeTime,
				int score,
				char *pHost,
				int len,
				char *pEntry,
				int userFlags = 0
							 ) :
			mTime(when),
			mId(id),
			mpUserId(pUserId),
			mpEmail(pEmail),
			mUserIdLastChangeTime(userIdLastChangeTime),
			mFeedbackScore(score),
			mLen(len),
			mpEntry(pEntry),
			mUserFlags(userFlags)
		{
			memset(mHost, 0x00, sizeof(mHost));
			strncpy(mHost, pHost, sizeof(mHost) - 1);
			return;
		};



		time_t		mTime;
		int			mId;
		char		*mpUserId;
		char		*mpEmail;
		time_t		mUserIdLastChangeTime;
		int			mFeedbackScore;
		char		mHost[16];
		int			mLen;
		char		*mpEntry;
		int			mUserFlags;
};


typedef list<clsBulletinBoardEntry *> 
						BulletinBoardEntryList;


// Class forward
class	clsUser;

class clsBulletinBoard
{

	public:
		clsBulletinBoard(BulletinBoardId id,
						 const char *pBoardName,
						 const char *pBoardShortName,
						 const char *pBoardShortDescription,
						 const char *pBoardPicture,
						 const char *pBoardDescription,
						 int maxPostCount,
						 int maxPostAge,
						 unsigned int controlFlags,
						 unsigned int type,
						 time_t lastPostTime);

		~clsBulletinBoard();

		//
		// GetId
		//
		int GetId();

		//
		// GetName
		//
		char *GetName();

		// 
		// GetShortName
		//
		char *GetShortName();

		// 
		// GetShortDescription
		//
		char *GetShortDescription();

		//
		// GetPicture
		//
		char *GetPicture();

		//
		// GetDescription
		//
		char *GetDescription();

		
		//
		// GetOtherThings
		//
		int GetMaxPostAge();
		int GetMaxPostCount();
		unsigned int GetControlFlags();
		unsigned int GetType();
		time_t GetLastPostTime();

		//
		// Setters (as needed)
		//
		void SetId(BulletinBoardId id);
		
		//
		// Add an entry to the board
		//
		void AddEntry(clsUser *pUser, char *pEntry);

		//
		// Get all Entries in a board
		//
		void GetAllEntries(BulletinBoardEntryList *plEntries,
						   int maxPostAgeMinutes = 0);

		//
		// Helpers
		//
		const bool IsRestricted();
		const bool IsHTMLEnabled();
		const bool IsInvisible();
		const bool IsPostable();
		const bool IsAvailable();
		//
		// Type Helpers
		//
		const bool IsGeneral();
		const bool IsCustomerSupport();
		const bool IsNews();
		const bool IsCategorySpecific();
		const bool IsEssay();


	private:
		BulletinBoardId	mId;
		char			*mpName;
		char			*mpShortName;
		char			*mpShortDescription;
		char			*mpPicture;
		char			*mpDescription;
		int				mMaxPostCount;
		int				mMaxPostAge;
		unsigned int	mControlFlags;
		unsigned int	mType;
		time_t			mLastPostTime;
};

typedef vector<clsBulletinBoard *> BulletinBoardVector;

#define CLSBULLETINBOARD_INCLUDED 1
#endif CLSBULLETINBOARD_INCLUDED