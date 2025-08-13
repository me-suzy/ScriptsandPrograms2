/*	$Id: clsFeedback.h,v 1.8 1999/02/21 02:46:34 josh Exp $	*/
//
//	File:		clsFeedback.h
//
// Class:	clsFeedback
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents the feedback for a user
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/20/97 michael	- added transfer to handle renaming users.
//				- 09/20/97 chad		- added *_SUSPENDED feedback cases
//				- 08/25/98 mila		- fixed spelling of 'paginated'
//

#ifndef CLSFEEDBACK_INCLUDED

#include "eBayTypes.h"
#include "time.h"
#include "vector.h"
#include "iterator.h"

#define USERHOURLYFEEDBACKLIMIT	1
#define HOSTHOURLYFEEDBACKLIMIT	24

// Class Foward
class clsUser;
class clsDatabase;
 
//
// clsFeedbackItem
//
//		Describes an individual piece of feedback
//		for a user. This is the "robust" version,
//		which contains everything about a particular
//		piece of feedback and who left it.
//
//		clsFeedbackItems are ONLY produced when 
//		feedback is retrieved from the database.
//		When feedback is "written", it's in
//		response to an Add*Feedback call, which
//		just writes the data directly.
//
//		This class is only used in 
//		conjunction with clsFeedback, so it's
//		defined here.
//

typedef enum
{
	FEEDBACK_UNKNOWN			= 0,
	FEEDBACK_POSITIVE			= 1,	// yp
	FEEDBACK_NEGATIVE			= 2,	// yp
	FEEDBACK_NEUTRAL			= 3,
	FEEDBACK_POSITIVE_SUSPENDED	= 4,
	FEEDBACK_NEGATIVE_SUSPENDED	= 5
} FeedbackTypeEnum;


class clsFeedbackItem
{
	public:
		//
		// This constructor is for clients
		// who know about FeedbackTypeEnum
		//
		// Note: this class is used both for 
		// getting feedback left for specific user
		// as well as feedback left BY a specific user
		// commentedUserFlag is used only by the latter
		// to indicate hidden feedback by the receiving user
		//
		clsFeedbackItem(int id,
						time_t theTime,
						FeedbackTypeEnum type,
						int commentingId,
						char *pCommentingUserId,
						const char *pCommentingEmail,
						int commentingUserState,
						int commentingUserScore,
						const char *pHost,
						const char *pText,
						long commentingUserIdLastModified,
						const char *pRowid,
						int commentedUserFlag=0,
						int commentingUserFlags=0,
						int	item = 0,
						const char *pResponse = NULL,
						const char *pFollowUp = NULL) :
			mId(id),
			mTime(theTime),
			mType(type),
			mCommentingId(commentingId),
			mCommentingUserState(commentingUserState),
			mCommentingUserScore(commentingUserScore),
			mCommentingUserIdLastModified(commentingUserIdLastModified),
			mCommentedUserFlag(commentedUserFlag),
			mCommentingUserFlags(commentingUserFlags),
			mItem(item)
		{
			mCommentingUserId[sizeof (mCommentingUserId) - 1] = 0;
			mCommentingEmail[sizeof (mCommentingEmail) - 1] = 0;
			mHost[sizeof (mHost) - 1] = 0;
			mText[sizeof (mText) - 1] = 0;
			mResponse[sizeof (mResponse) - 1] = 0;
			mFollowUp[sizeof (mFollowUp) - 1] = 0;
			mRowId[sizeof (mRowId) - 1] = 0;

			if (pCommentingUserId != NULL)
			{
				strncpy(mCommentingUserId, pCommentingUserId,
						sizeof(mCommentingUserId) - 1);
			}

			if (pCommentingEmail != NULL)
			{
				strncpy(mCommentingEmail, pCommentingEmail,
					sizeof (mCommentingEmail) - 1);
			}

			if (pRowid != NULL)
			{
				strncpy(mRowId, pRowid, sizeof (mRowId) - 1);
			}

			if (pHost != NULL)
			{
				strncpy(mHost, pHost, sizeof(mHost) - 1);
			}

			if (pText != NULL)
			{
				strncpy(mText, pText, sizeof(mText) - 1);
			}

			if (pResponse != NULL)
			{
				strncpy(mResponse, pResponse, sizeof(mResponse) - 1);
			}

			if (pFollowUp != NULL)
			{
				strncpy(mFollowUp, pFollowUp, sizeof(mFollowUp) - 1);
			}
		}

		~clsFeedbackItem()
		{
		}

		int						mId;					// User feedback is for
		time_t					mTime;					// Time o'feedback
		FeedbackTypeEnum		mType;					// Type
		int						mCommentingId;			// Commenting user
		char					mCommentingUserId[EBAY_MAX_USERID_SIZE+1];	// Their UserId
		char					mCommentingEmail[EBAY_MAX_EMAIL_SIZE+1];	// Their Email
		int						mCommentingUserState;	// Commenting user's state
		int						mCommentingUserScore;	// Their Score
		char					mHost[32];				// Commenting host
		char					mText[128];				// Feedback text
		char					mRowId[20];				// rowid of the feedback detail record
		int						mCommentedUserFlag;		// commenting user's left-for feedback flag
		long					mCommentingUserIdLastModified;	// last time commenting user changed his id
		int						mCommentingUserFlags;	// commenting user's feedback flag
		int						mItem;					// transaction based, item id
		char					mResponse[81];			// response text
		char					mFollowUp[81];			// followup text
};

//
// clsMinimalFeedbackItem
//
// A little deviant for recomputing a user's feedback
// score. Have the minimum information needed
//
class clsMinimalFeedbackItem
{
	public:
		//
		// This constructor is for clients
		// who know about FeedbackTypeEnum
		//
		clsMinimalFeedbackItem(FeedbackTypeEnum type,
							   int commentingId,
							   char *pRowId) :
			mType(type),
			mCommentingId(commentingId)
		{
			mRowId[sizeof (mRowId) - 1] = 0;
			strncpy(mRowId, pRowId, sizeof (mRowId) - 1);
		};


		~clsMinimalFeedbackItem()
		{
		}

		FeedbackTypeEnum		mType;					// Type
		int						mCommentingId;			// Commenting user
		char					mRowId[20];
};

//
// A Vector o'feedbacks
//
typedef vector<clsFeedbackItem *>	FeedbackItemVector;
typedef vector<clsMinimalFeedbackItem *> MinimalFeedbackItemVector;

//
//	clsFeedbackExtendedScore
//
//	This little class describes in more detail the user's
//	feedback score
//
class clsFeedbackExtendedScore
{
	public:
		clsFeedbackExtendedScore()
		{
			mScore								= 0;
			mPositiveComments					= 0;
			mPositiveCommentsThatCount			= 0;
			mNegativeComments					= 0;
			mNegativeCommentsThatCount			= 0;
			mNeutralComments					= 0;
			mNeutralCommentsFromSuspendedUsers	= 0;
			mInterval1Boundry					= 0;
			mInterval2Boundry					= 0;
			mInterval3Boundry					= 0;
			mCommentsInInterval1				= 0;
			mCommentsInInterval2				= 0;
			mCommentsInInterval3				= 0;
			mPositiveCommentsInInterval1		= 0;
			mPositiveCommentsInInterval2		= 0;
			mPositiveCommentsInInterval3		= 0;
			mNegativeCommentsInInterval1		= 0;
			mNegativeCommentsInInterval2		= 0;
			mNegativeCommentsInInterval3		= 0;
			mNeutralCommentsInInterval1			= 0;
			mNeutralCommentsInInterval2			= 0;
			mNeutralCommentsInInterval3			= 0;
			return;
		};

		clsFeedbackExtendedScore(int score,
									int PositiveComments,
									int PositiveCommentsThatCount,
									int	NegativeComments,
									int NegativeCommentsThatCount,
									int NeutralComments,
									int NeutralCommentsFromSuspendedUsers,
									int Interval1Boundry,
									int Interval2Boundry,
									int Interval3Boundry,
									int CommentsInInterval1,
									int CommentsInInterval2,
									int CommentsInInterval3,
									int PositiveCommentsInInterval1,
									int PositiveCommentsInInterval2,
									int PositiveCommentsInInterval3,
									int NegativeCommentsInInterval1,
									int NegativeCommentsInInterval2,
									int NegativeCommentsInInterval3,
									int NeutralCommentsInInterval1,
									int NeutralCommentsInInterval2,
									int NeutralCommentsInInterval3
									)
		{
			mScore								= score;
			mPositiveComments					= PositiveComments;
			mPositiveCommentsThatCount			= PositiveCommentsThatCount;
			mNegativeComments					= NegativeComments;
			mNegativeCommentsThatCount			= NegativeCommentsThatCount;
			mNeutralComments					= NeutralComments;
			mNeutralCommentsFromSuspendedUsers	= NeutralCommentsFromSuspendedUsers;
			mInterval1Boundry					= Interval1Boundry;
			mInterval2Boundry					= Interval2Boundry;
			mInterval3Boundry					= Interval3Boundry;
			mCommentsInInterval1				= CommentsInInterval1;
			mCommentsInInterval2				= CommentsInInterval2;
			mCommentsInInterval3				= CommentsInInterval3;
			mPositiveCommentsInInterval1		= PositiveCommentsInInterval1;
			mPositiveCommentsInInterval2		= PositiveCommentsInInterval2;
			mPositiveCommentsInInterval3		= PositiveCommentsInInterval3;
			mNegativeCommentsInInterval1		= NegativeCommentsInInterval1;
			mNegativeCommentsInInterval2		= NegativeCommentsInInterval2;
			mNegativeCommentsInInterval3		= NegativeCommentsInInterval3;
			mNeutralCommentsInInterval1			= NeutralCommentsInInterval1;
			mNeutralCommentsInInterval2			= NeutralCommentsInInterval2;
			mNeutralCommentsInInterval3			= NeutralCommentsInInterval3;
			return;
		};

		~clsFeedbackExtendedScore()
		{
			return;
		}

		int						mScore;
		int						mPositiveComments;
		int						mPositiveCommentsThatCount;
		int						mNegativeComments;
		int						mNegativeCommentsThatCount;
		int						mNeutralComments;
		int						mNeutralCommentsFromSuspendedUsers;

		time_t					mInterval1Boundry;
		time_t					mInterval2Boundry;
		time_t					mInterval3Boundry;

		int						mCommentsInInterval1;
		int						mCommentsInInterval2;
		int						mCommentsInInterval3;

		int						mPositiveCommentsInInterval1;
		int						mPositiveCommentsInInterval2;
		int						mPositiveCommentsInInterval3;

		int						mNegativeCommentsInInterval1;
		int						mNegativeCommentsInInterval2;
		int						mNegativeCommentsInInterval3;

		int						mNeutralCommentsInInterval1;
		int						mNeutralCommentsInInterval2;
		int						mNeutralCommentsInInterval3;
};

// Feedback flags
#define FEEDBACK_FLAG_HIDE	0x01	
#define FEEDBACK_FLAG_SPLIT	0x02	// 10 x

//
// clsFeedback
//
class clsFeedback
{
	public:
		//
		// Constructor, Destructor
		//
		clsFeedback();
		clsFeedback(int id,
					bool hasFeedback,
					int score = 0,
					int flag = 0,
					bool ValidExt = false,
					long DateCalc = 0,
					clsFeedbackExtendedScore *pFeedbackExtendedScore = 0,
					bool split=false);

		~clsFeedback();

		//
		// UserHasFeedback
		//
		bool UserHasFeedback();
		void SetUserHasFeedback(bool dothey);

		// 
		// UserHasFeedbackFromUser
		// 
		//	Returns true or false depending on whether the 
		//	passed user has left non-neutral feedback for
		//	this user
		//
		bool UserHasFeedbackFromUser(int commentingId);

		//
		// RecentFeedbackFromUser
		//
		//	Checks to see if the passed user has left 
		//	feedback for the target user within the 
		//	specified time period. If they have, it
		//	returns a pointer to the clsFeedbackItem 
		//	object, otherwise NULL.
		//
		clsFeedbackItem *RecentFeedbackFromUser(int commentingId,
												time_t timePeriod,
												bool NegativeFeedbackOnly);

		//
		// RecentFeedbackFromHost
		//
		//	Checks to see if the passed HOST has left 
		//	feedback for the target user within the 
		//	specified time period. If they have, it
		//	returns a pointer to the clsFeedbackItem 
		//	object, otherwise NULL.
		//
		clsFeedbackItem *RecentFeedbackFromHost(char *pCommentingHost,
												time_t timePeriod,
												bool NegativeFeedbackOnly);


		// 
		// Routines to Add feedback of various types
		//
		void AddFeedback(int commentingId,
						char *pCommentingHost,
						char *pComment,
						FeedbackTypeEnum Type, 
						int Item=0);

		bool AddFollowUp(int Commentor, time_t CommentDate, const char* pFollowUp);
		bool AddResponse(int Commentor, time_t CommentDate, const char* pResponse);

		bool HasFollowUp(int Commentor, time_t CommentDate);
		bool HasResponse(int Commentor, time_t CommentDate);
		//
		// RecomputeScore
		//
		//	Recomputes the user's feedback score. Not a 
		//	trivial operation, given the rules!
		//  server side version
		int RecomputeScore();
		// client side
		int OldRecomputeScore();

		//
		// SetScore, GetScore
		//		Returns the user's score
		//
		void	SetScore(int score);
		int		GetScore();

		void	SetExtendedScore(clsFeedbackExtendedScore *pFeedbackExtendedScore);
		bool	IsValidExtendedScore();
		clsFeedbackExtendedScore	*GetExtendedScore();

		void	SetId(int uid);
		int		GetId();

		//
		// SetFlag, GetFlag
		//
		void	SetFlag(int flag, bool on);
		int		GetFlag();

		//
		// Is Split
		//
		bool	IsSplit() {return mSplit;}

		//
		// GetItems
		//		GetItemsVector just returns a pointer to the vector, 
		//		Pass it the starting item and the number of items to
		//		fetch.
		//
		FeedbackItemVector			*GetItems(int itemStart = 0,
											  int itemCount = 0,
											  int *pTotalItems = NULL);

		MinimalFeedbackItemVector	*GetMinimalItems();
		int							GetItemCount();

		//
		// Get a specific feedback item
		clsFeedbackItem* GetItem(int CommentorId, time_t CommentTime);

		// used to refresh the ItemVector after a transfer
		void ReleaseMinimalItems(); 
		void ReleaseItems();

		// FeedbackItemVector	*GetItemsVector();


		//
		// GetLeftItems
		//		Gets the feedback LEFT by ths user
		//
		FeedbackItemVector	*GetItemsLeft();

		//
		// GetExtendedScore
		//	If extended score is valid it returns, otherwise
		// this creates an extended score object and returns it
		//
		clsFeedbackExtendedScore	*GetExtendedScore(
											time_t interval1,
											time_t interval2,
											time_t interval3
													 );
		//
		// Transfer
		//		Transfers all a user's feedback (theirs,
		//		and theirs they've left) to a new user.
		//
		void Transfer(clsUser *pFromUser, clsUser *pToUser);
		void VoidFeedbackLeft();
		void RestoreFeedbackLeft();

		void InvalidateExtendedFeedback();
		void UpdateExtendedFeedback();

		long GetExtDateCalc();

	private:
		// Our user's Id
		int						mId;

		//
		// mGotItems and mGotMinItems indicates whether or not this
		// feedback object has been populated from
		// the database. There's no "refresh" so delete the feedback
		// object and repopulate to get the updated feedback scores.
		//
		// NOTE:
		// mGotItems is also overloaded for feedback items left by user
		// as well as a user's feedback.
		//
		bool					mUserHasFeedback;
		bool					mGotItems;
		bool					mGotItemsLeft;
		bool					mGotMinItems;
		int						mScore;
		int						mFlag;

		// used for extended feedback score caching
		bool					mValidExt;
		long					mDateCalc;
		bool					mSplit;

		// this object is used for the feedback extended score cache
		clsFeedbackExtendedScore *mpExtScore;

		//
		// Vector of feedback items
		//
		FeedbackItemVector			mvFeedbackItems;
		MinimalFeedbackItemVector	mvMinimalFeedbackItems;
};
		
#define CLSFEEDBACK_INCLUDED 1
#endif /* CLSFEEDBACK_INCLUDED */


	

	
