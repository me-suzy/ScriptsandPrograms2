/*	$Id: clsFeedback.cpp,v 1.6 1999/02/21 02:47:35 josh Exp $	*/
//
//	File:	clsFeedback.cc
//
//	Class:	clsFeedback
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
//				- 08/22/98 mila		- deleted stray semicolon from AddResponse
//				- 08/25/98 mila		- fixed spelling of 'paginated'
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )
#include "eBayKernel.h"

#include <stdio.h>

#include "hash_map.h"
//
// Constructor
//
clsFeedback::clsFeedback()
{
	mId					= 0;
	mScore				= 0;
	mFlag				= 0;
	mUserHasFeedback	= false;
	mGotItems			= false;
	mGotItemsLeft		= false;
	mGotMinItems		= false;
	mValidExt			= false;
	mDateCalc			= 0;
	mpExtScore			= NULL;
	mSplit				= false;
}

clsFeedback::clsFeedback(int id,
						 bool userHasFeedback,
						 int score,
					 int flag,
						 bool ValidExt,
						 long DateCalc,
						 clsFeedbackExtendedScore *pFeedbackExtendedScore,
						 bool split)
{
	mId					= id;		// yp
	mUserHasFeedback	= userHasFeedback;
	mScore				= score;
	mFlag				= flag;
	mGotItems			= false;	// yp
	mGotItemsLeft		= false;
	mGotMinItems		= false;
	mValidExt			= ValidExt;
	mDateCalc			= DateCalc;
	mpExtScore			= pFeedbackExtendedScore;
	mSplit				= split;
	return;
}

//
// Destructor
//
clsFeedback::~clsFeedback()
{
	delete mpExtScore;
	ReleaseItems();
	ReleaseMinimalItems();

	return;
}

//
// UserHasFeedback
//
// The constructor should have gotten this information out
// of the database for us.
//
bool clsFeedback::UserHasFeedback()
{
	return	mUserHasFeedback;
}

void clsFeedback::SetId(int uid)
{
	mId		= uid;
}

	
//
// GetScore
//
// The constructor should have gotten this out of the 
// database for us (if it's to be had). Somehow, I think
// we should throw an exception otherwise, but for now 
// I just return 0.
//
int clsFeedback::GetScore()
{
	if (mUserHasFeedback)
		return mScore;
	else
		return 0;
}

void clsFeedback::SetScore(int score)
{
	mScore		= score;
	gApp->GetDatabase()->SetFeedbackScore(mId, mScore);

	//
	// If the user didn't have feedback before, well, they
	// do now ;-)
	//
	mUserHasFeedback	= true;

	return;
}

void clsFeedback::SetExtendedScore(clsFeedbackExtendedScore *pFeedbackExtendedScore)
{
	// clean up old score if any
	if (mpExtScore != 0)
		delete mpExtScore;

	mpExtScore = pFeedbackExtendedScore;
	mValidExt = true;
	mDateCalc  = time(0);

	UpdateExtendedFeedback();
	return;
}

// checks if an extended score is valid
bool clsFeedback::IsValidExtendedScore()
{
	return mValidExt;
}

clsFeedbackExtendedScore	*clsFeedback::GetExtendedScore()
{
	return mpExtScore;
}

//
// RecomputeScore
//
//	This routine takes all the feedback detail records, 
//	and recomputes the user's score. We can't do this
//	in SQL because we only count ONE praise and ONE
//	complaint on a user from another given user. E.g.,
//	you can praise or complain all you want, but it only
//	counts once.
//
// server side version
int clsFeedback::RecomputeScore()
{
	return 	gApp->GetDatabase()->GetRecomputedFeedbackScore(mId, mSplit);

}

// client side version
int clsFeedback::OldRecomputeScore()
{
	int					newScore	= 0;

	// The raw vector of feedback items
	MinimalFeedbackItemVector			*pFeedbackItems;

	// An itcherator
	MinimalFeedbackItemVector::iterator	i;

	clsMinimalFeedbackItem				*pItem;

	// The hash map of people who have left feedback
	// for this individual
	hash_map<const int, int, hash<int>, eqint>
		feedbackTracks;

	// A hasherator
	hash_map<const int, int, hash<int>, eqint>::
		const_iterator				ii;


	// Ok, first, we get all the feedback items
	GetMinimalItems();
	pFeedbackItems	=	&mvMinimalFeedbackItems;

	// If there aren't any items, their aint' not score, 
	// I say
	if (pFeedbackItems->size() == 0)
		return 0;

	// Ok, now let's iterate through them
	for (i = pFeedbackItems->begin();
		 i != pFeedbackItems->end();
		 i++)
	{
		pItem	= (*i);

		// If the user who left the feedback has been
		// suspended, don't count it. We change these
		// types at the time the user is suspended.
		if (pItem->mType == FEEDBACK_NEUTRAL ||
			pItem->mType == FEEDBACK_POSITIVE_SUSPENDED ||
			pItem->mType == FEEDBACK_NEGATIVE_SUSPENDED)
			continue;

		// Let's see if we've seen this user before
		ii	= feedbackTracks.find((const int)(pItem->mCommentingId));

		// If not, make a tracker
		if (ii == feedbackTracks.end())
		{
			feedbackTracks[pItem->mCommentingId]	= 0;
		}

		// If it's a praise, then see if we want it
		if (pItem->mType == FEEDBACK_POSITIVE)
		{
			feedbackTracks[pItem->mCommentingId]++;	
			continue;
		}

		// Same for complaints, those bastards!
		if (pItem->mType == FEEDBACK_NEGATIVE)
		{
			feedbackTracks[pItem->mCommentingId]--;
			continue;
		}

		// And that's it!
	}

	// Now, Compute the score
   for (ii = feedbackTracks.begin();
        ii != feedbackTracks.end();
        ii++)
   {
		if ((*ii).second > 0)
			newScore++;
		else if ((*ii).second < 0)
			newScore--;		 
   }	

	feedbackTracks.erase(feedbackTracks.begin(),
						 feedbackTracks.end());

	for (i = pFeedbackItems->begin();
		 i != pFeedbackItems->end();
		 i++)
	{
		delete	(*i);
	}

	pFeedbackItems->erase(pFeedbackItems->begin(),
						  pFeedbackItems->end());


	return newScore;
}

//
// GetItems
//	Gets a list of feedback left FOR this user
//
FeedbackItemVector *clsFeedback::GetItems(int itemStart, int itemCount,
										  int *pTotalItems)
{
	if (!mGotItems)
	{
		if (mGotItemsLeft)
			ReleaseItems();
	
		gApp->GetDatabase()->GetFeedbackDetailFromList(mId,
					&mvFeedbackItems, mSplit, itemStart - 1, itemCount, pTotalItems);
	
		mGotItems = true;
	}

	return &mvFeedbackItems; 
}


//
// ReleaseItems
//	Releases the vector of feedback Items
//
void clsFeedback::ReleaseItems()
{
	FeedbackItemVector::iterator		i;

	if (mGotItems || mGotItemsLeft)
	{
		for (i = mvFeedbackItems.begin();
			 i != mvFeedbackItems.end();
			 i++)
		{
			delete (*i);
		}

		mvFeedbackItems.erase(mvFeedbackItems.begin(),
							  mvFeedbackItems.end());

		// no more items in vector
		mGotItemsLeft = false;
		mGotItems = false;
	}
}

//
// ReleaseMinimalItems
//	Releases the vector of minimal feedback Items
//
void clsFeedback::ReleaseMinimalItems()
{
	MinimalFeedbackItemVector::iterator	ii;
	
	if (mGotMinItems)
	{
		// Let's see if there's anything to clean up
		if (mvMinimalFeedbackItems.size() > 0)
		{
			for (ii = mvMinimalFeedbackItems.begin();
				 ii != mvMinimalFeedbackItems.end();
				 ii++)
			{
				delete (*ii);
			}

			mvMinimalFeedbackItems.erase(
							mvMinimalFeedbackItems.begin(),
							mvMinimalFeedbackItems.end()
									);
		}
		mGotMinItems = false;
	}

	return; 
}

//
// GetMinimalItems
//	Gets a list of minimal feedback left FOR this user
//
MinimalFeedbackItemVector *clsFeedback::GetMinimalItems()
{
   if (!mGotMinItems)
	{
		gApp->GetDatabase()->GetFeedbackDetailFromListMinimal(mId,
					&mvMinimalFeedbackItems, mSplit, 0, 0);

		mGotMinItems = true;
	}
	return &mvMinimalFeedbackItems; 
}


//
// GetItemsLeft
//	Gets a list of feedback left BY this user
//
FeedbackItemVector *clsFeedback::GetItemsLeft()
{
	if (!mGotItemsLeft)
	{
		if (mGotItems)
			ReleaseItems();

		gApp->GetDatabase()->GetFeedbackDetailLeftByUser(
								mId,
								&mvFeedbackItems);
		mGotItemsLeft = true;
	}
	return &mvFeedbackItems; 
}

//
// Gets commenting user feedback flag
//

int clsFeedback::GetFlag()
{
	return mFlag;
}

void clsFeedback::SetFlag(int flag, bool on)
{
	if (on)
	{
		mFlag	|= flag;	// turn it on
	}
	else
	{
		mFlag	&= ~flag;	// turn it off
	}
	gApp->GetDatabase()->UpdateFeedbackFlags(mId, 
											 mFlag);
	return;
}

//
// UserHasFeedbackFromUser
//
bool clsFeedback::UserHasFeedbackFromUser(int commentingId)
{
	// Let the DB do it for us...
	return 
		gApp->GetDatabase()->UserHasFeedbackFromUser(mId,
													 commentingId,
													 mSplit);
}

//
// AddFeedback
//
void clsFeedback::AddFeedback(int commentingId,
							  char *pCommentingHost,
							  char *pComment,
							  FeedbackTypeEnum Type, 
							  int Item/*=0*/)
{
	// Add the detail item to their feedback
	gApp->GetDatabase()->AddFeedbackDetail(mId,
										   commentingId,
										   pCommentingHost,
										   Type,
										   (Type == FEEDBACK_POSITIVE ? 
												1 : (Type == FEEDBACK_NEUTRAL ? 0 : -1)),
										   pComment,
										   mSplit,
										   Item
										   );

#if 0	// not yet (mila)
	// if the user's feedback is split, then we have to put
	// a copy of the feedback in the unsplit table
	if (mSplit)
	{
		gApp->GetDatabase()->AddFeedbackDetail(mId,
										   commentingId,
										   pCommentingHost,
										   Type,
										   (Type == FEEDBACK_POSITIVE ? 
												1 : (Type == FEEDBACK_NEUTRAL ? 0 : -1)),
										   pComment,
										   false,
										   Item
										   );
	}
#endif

	gApp->GetDatabase()->InvalidateFeedbackList(mId);
	InvalidateExtendedFeedback();
	// Recompute the user's feedback score
	SetScore(RecomputeScore());
	return;
}

//
// AddResponse
//
bool clsFeedback::AddResponse(int Commentor, time_t CommentDate, const char* pResponse)
{
	// Only one response allowed
	if (gApp->GetDatabase()->HasResponse(Commentor, CommentDate, mId, mSplit))
		return false;

	gApp->GetDatabase()->UpdateResponse(Commentor, CommentDate, mId, pResponse, mSplit);

#if 0	// not yet (mila)
	// if the user's feedback is in a split table, then we have to put
	// a copy of the updated feedback in the unsplit table
	if (mSplit)
	{
		gApp->GetDatabase()->UpdateResponse(Commentor, CommentDate, mId, pResponse, false);
	}
#endif

	return true;
}
//
// AddFollowUp
//
bool clsFeedback::AddFollowUp(int Commentor, time_t CommentDate, const char* pFollowUp)
{
	// Only one followup allowed and no followup without response
	if(gApp->GetDatabase()->HasFollowUp(Commentor, CommentDate, mId, mSplit) || 
	   !gApp->GetDatabase()->HasResponse(Commentor, CommentDate, mId, mSplit))
		return false;

	gApp->GetDatabase()->UpdateFollowUp(Commentor, CommentDate, mId, pFollowUp, mSplit);

#if 0	// not yet (mila)
	// if the user's feedback is in a split table, then we have to put
	// a copy of the updated feedback in the unsplit table
	if (mSplit)
	{
		gApp->GetDatabase()->UpdateFollowUp(Commentor, CommentDate, mId, pFollowUp, false);
	}
#endif

	return true;
}

//
// Check whether there is a followup already
//
bool clsFeedback::HasFollowUp(int Commentor, time_t CommentDate)
{
	return gApp->GetDatabase()->HasFollowUp(Commentor, CommentDate, mId, mSplit);
}

//
// Check whether there is a response already
//
bool clsFeedback::HasResponse(int Commentor, time_t CommentDate)
{
	return gApp->GetDatabase()->HasResponse(Commentor, CommentDate, mId, mSplit);
}

//
// Get a specified feedback item
//
clsFeedbackItem* clsFeedback::GetItem(int CommentorId, time_t CommentTime)
{
	return gApp->GetDatabase()->GetFeedbackItem(CommentorId, CommentTime, mId, mSplit);
}


//
// GetFeedbackDetailCount
//
// Get the number of feedbacks an user has
//
int clsFeedback::GetItemCount()
{
	if (mGotItems)
	{
		return mvFeedbackItems.size(); 
	}

	if (mGotMinItems)
	{
		return mvMinimalFeedbackItems.size();
	}

	return gApp->GetDatabase()->GetFeedbackDetailCount(mId, mSplit);
}

int clsFeedback::GetId()
{
	return mId;
}


//
// Transfers all feedback from "from" user to "to" user
// 
void clsFeedback::Transfer(clsUser *pFromUser,
						   clsUser *pToUser)
{
	// First, we do their feedback
	gApp->GetDatabase()->TransferFeedback(pFromUser, pToUser);

	// Now, we do the feedback they've LEFT
	gApp->GetDatabase()->TransferFeedbackLeft(pFromUser, pToUser);

	InvalidateExtendedFeedback();
	ReleaseMinimalItems();
	// cannot recompute score because feedback has been transferred away to another user
	SetScore(RecomputeScore());

	return;
}

void clsFeedback::VoidFeedbackLeft()
{
	gApp->GetDatabase()->VoidFeedbackLeftByUser(mId);
	return;
}

void clsFeedback::RestoreFeedbackLeft()
{
	gApp->GetDatabase()->RestoreFeedbackLeftByUser(mId);
	return;
}

void clsFeedback::InvalidateExtendedFeedback()
{
	gApp->GetDatabase()->InvalidateExtendedFeedback(mId);
	return;
}


long clsFeedback::GetExtDateCalc()
{
	return mDateCalc;
}

void clsFeedback::UpdateExtendedFeedback()
{
	gApp->GetDatabase()->UpdateExtendedFeedback(this);
}

//
// RecentFeedbackFromUser
//
//	Checks to see if the passed user has left 
//	feedback for the target user within the 
//	specified time period. If they have, it
//	returns a pointer to the clsFeedbackItem 
//	object, otherwise NULL.
//
clsFeedbackItem *clsFeedback::RecentFeedbackFromUser(int commentingId,
										time_t timePeriod,
										bool NegativeFeedbackOnly)
{
	clsFeedbackItem		*pFeedbackItem;

	pFeedbackItem	= 
		gApp->GetDatabase()->RecentFeedbackFromUser(
										mId,
										commentingId,
										timePeriod,
										NegativeFeedbackOnly);

	return pFeedbackItem;
}


//
// RecentFeedbackFromHost
//
//	Checks to see if the passed host has left 
//	feedback for the target user within the 
//	specified time period. If they have, it
//	returns a pointer to the clsFeedbackItem 
//	object, otherwise NULL.
//
clsFeedbackItem *clsFeedback::RecentFeedbackFromHost(char *pHost,
										time_t timePeriod,
										bool NegativeFeedbackOnly)
{
	clsFeedbackItem		*pFeedbackItem;

	pFeedbackItem	= 
		gApp->GetDatabase()->RecentFeedbackFromHost(
										mId,
										pHost,
										timePeriod,
										NegativeFeedbackOnly);

	return pFeedbackItem;
}

//
//	clsExtendedFeedbackTrack
//
class clsExtendedFeedbackTrack
{
	public:
		clsExtendedFeedbackTrack()
		{
			mScore				= 0;
			mHasLeftPositive	= false;
			mHasLeftNegative	= false;
		};

		~clsExtendedFeedbackTrack()
		{
			return;
		}

		int			mScore;
		bool		mHasLeftPositive;
		bool		mHasLeftNegative;
};

//
// GetExtendedScore
//
//	Builds and computes a clsFeedbackExtendedScore object. Very
//	simaler to RecomputeScore, except it does a lot more. We may
//	want to change the database definition and make this the 
//	default one day.
//
//	Right now, this is used in conjuntion with displaying feedback,
//	so it uses the complete feedback items, not the minimal ones.
//
//  Include caching - if clsFeedback has no valid extended feedback
//  score object, we create one here and update the record.
//
clsFeedbackExtendedScore	*clsFeedback::GetExtendedScore(
											time_t interval1,
											time_t interval2,
											time_t interval3
													 )
{
	time_t							nowTime;

	clsFeedbackExtendedScore		*pScore;

	// A vector pointer
	FeedbackItemVector				*pFeedbackItems;
	// An itcherator
	FeedbackItemVector::iterator	i;

	clsFeedbackItem					*pItem;
	int								count;

	clsExtendedFeedbackTrack		*pTracker;

	// The hash map of people who have left feedback
	// for this individual
	hash_map<const int, clsExtendedFeedbackTrack *, hash<int>, eqint>
		feedbackTracks;

	// A hasherator
	hash_map<const int, clsExtendedFeedbackTrack *, hash<int>, eqint>::
		const_iterator				ii;

	// Does clsFeedback have a valid extended feedback score?
	// only this code needs to be wired off temporarily

	if (IsValidExtendedScore() && clsUtilities::IsToday(mDateCalc))
	{
		return GetExtendedScore();
	}

	// otherwise, calculate and store
	// We get all the feedback items
	GetItems(1, 0, &count);
	pFeedbackItems	=	&mvFeedbackItems;

	//
	//	Ok, we can create a score object
	//
	pScore	= new clsFeedbackExtendedScore;

	// If there aren't any items, their aint' not score, 
	// I say, and we're done.
	if (pFeedbackItems->size() == 0)
		return pScore;

	nowTime						= time(0);
	pScore->mInterval1Boundry	= nowTime - interval1;
	pScore->mInterval2Boundry	= nowTime - interval2;
	pScore->mInterval3Boundry	= nowTime - interval3;

	// Ok, now let's iterate through them
	for (i = pFeedbackItems->begin();
		 i != pFeedbackItems->end();
		 i++)
	{
		pItem	= (*i);

		if (pItem->mTime >= pScore->mInterval1Boundry)
			pScore->mCommentsInInterval1++;
		if (pItem->mTime >= pScore->mInterval2Boundry)
			pScore->mCommentsInInterval2++;
		if (pItem->mTime >= pScore->mInterval3Boundry)
			pScore->mCommentsInInterval3++;


		// If the user who left the feedback has been
		// suspended, don't count it. We change these
		// types at the time the user is suspended.
		if (pItem->mType == FEEDBACK_NEUTRAL ||
			pItem->mType == FEEDBACK_POSITIVE_SUSPENDED ||
			pItem->mType == FEEDBACK_NEGATIVE_SUSPENDED)
		{
			pScore->mNeutralComments++;

			if (pItem->mTime >= pScore->mInterval1Boundry)
				pScore->mNeutralCommentsInInterval1++;
			if (pItem->mTime >= pScore->mInterval2Boundry)
				pScore->mNeutralCommentsInInterval2++;
			if (pItem->mTime >= pScore->mInterval3Boundry)
				pScore->mNeutralCommentsInInterval3++;

			if (pItem->mType == FEEDBACK_POSITIVE_SUSPENDED ||
				pItem->mType == FEEDBACK_NEGATIVE_SUSPENDED)
			{
				pScore->mNeutralCommentsFromSuspendedUsers++;
			}

			// We're all done with Neutral comments, actually
			continue;
		}

		// Let's see if we've seen this user before
		ii	= feedbackTracks.find((const int)(pItem->mCommentingId));

		// If not, make a tracker
		if (ii == feedbackTracks.end())
		{
			pTracker	= new clsExtendedFeedbackTrack;
			feedbackTracks[pItem->mCommentingId]	= pTracker;
		}
		else
			pTracker	= (*ii).second;

		//	If it's a praise, then account for it. The boolean
		//	in the tracker helps tell us if it counts
		if (pItem->mType == FEEDBACK_POSITIVE)
		{
			pScore->mPositiveComments++;

			if (pItem->mTime >= pScore->mInterval1Boundry)
				pScore->mPositiveCommentsInInterval1++;
			if (pItem->mTime >= pScore->mInterval2Boundry)
				pScore->mPositiveCommentsInInterval2++;
			if (pItem->mTime >= pScore->mInterval3Boundry)
				pScore->mPositiveCommentsInInterval3++;

			if (!pTracker->mHasLeftPositive)
			{
				pScore->mPositiveCommentsThatCount++;

				pTracker->mHasLeftPositive	= true;
			}
			pTracker->mScore++;	
			continue;
		}

		// Same for complaints, those bastards!
		if (pItem->mType == FEEDBACK_NEGATIVE)
		{
			pScore->mNegativeComments++;

			if (pItem->mTime >= pScore->mInterval1Boundry)
				pScore->mNegativeCommentsInInterval1++;
			if (pItem->mTime >= pScore->mInterval2Boundry)
				pScore->mNegativeCommentsInInterval2++;
			if (pItem->mTime >= pScore->mInterval3Boundry)
				pScore->mNegativeCommentsInInterval3++;

			if (!pTracker->mHasLeftNegative)
			{
				pScore->mNegativeCommentsThatCount++;

				pTracker->mHasLeftNegative	= true;
			}
			pTracker->mScore--;	
			continue;
		}

		// And that's it!
	}

	// Now, Compute the score
	for (ii = feedbackTracks.begin();
		ii != feedbackTracks.end();
		ii++)
	{
		if ((*ii).second->mScore > 0)
			pScore->mScore++;
		else if ((*ii).second->mScore < 0)
			pScore->mScore--;		 
	}	

	SetExtendedScore(pScore);

	for (ii	= feedbackTracks.begin();
		 ii != feedbackTracks.end();
		 ii++)
	{
		delete	(*ii).second;
	}

	feedbackTracks.erase(feedbackTracks.begin(),
						 feedbackTracks.end());

	//
	//	We do NOT clean up the feedback items. Someone 
	//	else might want them ;-)
	//
	return pScore;
}


