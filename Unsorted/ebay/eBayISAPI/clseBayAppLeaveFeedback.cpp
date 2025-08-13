/*	$Id: clseBayAppLeaveFeedback.cpp,v 1.11.2.4.34.2 1999/08/05 20:42:16 nsacco Exp $	*/
//
//	File:	clseBayAppLeaveFeedback.cc
//
//	Class:	clseBayApp
//
//	File:	clseBayAppViewBids.cpp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods used to confirm
//		and store feedback left by a user.
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 08/13/98 mila		- new feedback forum stuff
//				- 08/22/98 mila		- fixed grammatical error
//				- 08/24/98 mila		- validate transaction for transaction-related
//									  feedback
//				- 08/27/98 mila		- put link on confirmation page that jumps to
//									  updated feedback profile
//				- 09/12/98 mila		- changed error message headers and text
//				- 09/22/98 mila		- error-checking clean up
//				- 10/16/98 mila		- Modified method LeaveFeedback()
//									  so that users who are suspended and/or
//									  have a feedback rating <= -4 are not
//									  allowed to leave feedback
//				- 12/14/98 mila		- Modified method LeaveFeedback() to disallow
//									  non-transaction-related negative comments
//				- 02/06/99 mila		- Modified method LeaveFeedback() to check for
//									  matching bidder and seller user ids
//				- 02/10/99 mila		- Modified warning text emitted in method 
//									  LeaveFeedbackConfirm() for release of E110_prod
//									  as per John Dex
//				- 02/23/99 anoop	- Check to see if the user verification completes properly.
//				- 05/13/99 mila		- Verify that auction reserve price was met for transaction-
//									  related comments.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

#include "ebihdr.h"
#include "hash_map.h"

// *** NOTE ***
// Only used for interim logging
// *** NOTE ***
#include <stdio.h>
#include <errno.h>

const int secondsPerHour = 60 * 60 * 1;	// one hour
const int secondsPerDay = 60 * 60 * 24;	// one day

//
// Strip out quotes to put in hidden input fields
//
static char *cleanUpQuotes(char *pDescription)
{
	char		*pIt;
	char		*pItOut;
	int			quoteCount;
	int			newSize;
	char		*pNewDescription;

	// First, count the evil double quotes
	quoteCount	= 0;

	for (pIt	= pDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
		if ((*pIt == '\"'))
			quoteCount++;
	}

	// The new size is the origional size + 6
	// tims the quote count. We transform '"'
	// to &quot;
	newSize	= strlen(pDescription) +
			  6 * quoteCount +
			  1;

	pNewDescription	= new char[newSize];

	// Now, go through and replace those evil quotes
	for (pIt	= pDescription,
		 pItOut	= pNewDescription;
		 *pIt	!= '\0';
		 pIt++)
	{
		if (*pIt != '\"')
		{
			*pItOut	= *pIt;
			pItOut++;
			continue;
		}

		memcpy(pItOut, "&quot;", 6);
		pItOut	=	pItOut + 6;
	}

	*pItOut	= '\0';

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewDescription;
}

//
// LeaveFeedbackConfirm
//
void clseBayApp::LeaveFeedbackConfirm(char *pUserId,
									  char *pPass,
									  char *pForUser,
									  char *pItemNo,
									  char *pComment)
{
	char *pNewUserId;
	char *pNewPass;
	char *pNewForUser;
	char *pNewItemNo;
	char *pNewComment;

	SetUp();

	pNewUserId = cleanUpQuotes(pUserId);
	pNewPass = cleanUpQuotes(pPass);
	pNewForUser = cleanUpQuotes(pForUser);
	pNewItemNo = cleanUpQuotes(pItemNo);
	pNewComment = cleanUpQuotes(pComment);

	// Title is printed in LeaveFeedback

	// Emit form tag and its associated input tags
	*mpStream <<	"\n"
					"<form method=post action="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageLeaveFeedback)
			  <<	"eBayISAPI.dll"
					"\""
					">"
					"<INPUT TYPE=HIDDEN "
					"NAME=\"MfCISAPICommand\" "
					"VALUE=\"LeaveFeedback\">"
					"\n"
					"<input type=hidden name=userid value=\""
			  <<	pNewUserId
			  <<	"\">"
					"<input type=hidden name=pass value=\""
			  <<	pNewPass
			  <<	"\">"
					"\n"
					"<input type=hidden "
					"name=otheruserid VALUE=\""
			  <<	pNewForUser
			  <<	"\">"
					"\n"
					"<input type=hidden "
					"name=itemno VALUE=\""
			  <<	pNewItemNo
			  <<	"\">"
					"\n"
			  <<	"<input type=hidden name=which value=\"negative\">\n"
					"<input type=hidden name=comment value=\""
			  <<	pNewComment
			  <<	"\">\n"
			  <<	"<input type=hidden name=confirm value=1>";

	// Emit warning text.
	*mpStream <<	"<P>\n "
					"<FONT COLOR=\"#FF0033\"><B><FONT SIZE=+1><FONT SIZE=+1>"
					"CAUTION!</FONT></FONT></B></FONT>\n"
					"<P>\n"
					"You are about to leave a negative feedback comment for "
			  <<	pForUser
			  <<	". Are you sure you want to leave this comment?"
					"<p><b>A NEGATIVE FEEDBACK COMMENT CANNOT BE TAKEN BACK OR EDITED</b>. "
					"Due to legal restrictions, eBay cannot and will not remove feedback comments "
					"once they are submitted, except under exceptional circumstances (which include "
					"blatantly defaming, vulgar, or profane comments, or those which contain personal "
					"contact information of other users).\n";

	*mpStream <<	"<P>\n "
					"If you are considering leaving a negative comment, please review these suggestions:\n"
					"<ul>\n"
					"<li>Try to contact the other user directly by email or by telephone before you "
					"leave a negative comment. Generally, a misunderstanding or dispute can be resolved "
					"by telephone. You can request another person's contact information using the "
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"search/members/user-query.html\">Registered User Information Request</a> form.</li>\n"
					"<li>If you leave another person negative feedback and then are able to resolve "
					"your misunderstanding, go to Review Feedback You Have Left for Others (accessible "
					"from the "
					"<a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/forum/feedback.html\">Feedback Forum</a>) to leave a follow-up comment to "
					"explain directly within their Feedback Profile.</li>\n"
					"<li>Do not leave a negative comment to retaliate against someone who left you one. "
					"Contact the person and address their concern.  Go to Review Feedback Others Have "
					"Left About You (accessible from the <a href=\""
			  <<	mpMarketPlace->GetHTMLPath()
			  <<	"services/forum/feedback.html\">Feedback Forum</a>) to leave a response comment to clarify their "
					"original comment directly within your Feedback Profile. If the problem has been "
					"resolved, encourage the other person to provide a follow up comment directly "
					"within your Feedback Profile that explains the solution.</li>\n"
					"</ul>\n";

	*mpStream <<	"<P>\n "
					"<FONT COLOR=\"#FF0033\"><B><FONT SIZE=+1><FONT SIZE=+1>"
					"WARNING!</FONT></FONT></B></FONT>\n"
					"<P>\n"
					"The following abuses violate the User Agreement and will result in suspension from eBay:\n"
					"<ul>\n"
					"<li>Using a second User ID or registered email address (an \"alias\") to leave "
					"negative feedback for another user.</li>\n"
					"<li>Use of profane or vulgar language.</li>\n"
					"<li>Use of another person's personal contact information within the Feedback Forum.</li>\n"
					"<li>Duplication of comments in a malicious attempt to destroy another person's "
					"Feedback Profile.</li>\n"
					"</ul>\n";

	*mpStream <<	"<P>\n "
					"<P>Thank you for your cooperation.\n"
					"<P>\n";

	*mpStream <<	"<b>REMEMBER:</b> Once placed, this negative feedback comment cannot be retracted "
					"except in the exceptional circumstances described above. If you are certain you "
					"want to leave your comment, press the submit button to leave negative feedback "
					"about this person.\n";

	// Emit submit button
	*mpStream <<	"<P>\n"
					"<input type=submit value=submit>\n";

	// End form tag
	*mpStream <<	"</form><p>";

	// Emit page footer
	*mpStream <<	mpMarketPlace->GetFooter();

	// Clean up
	delete [] pNewUserId;
	delete [] pNewPass;
	delete [] pNewForUser;
	delete [] pNewComment;

	return;
}

//
// LeaveFeedback
//
void clseBayApp::LeaveFeedback(CEBayISAPIExtension *pThis,
							   char *pUserId,
							   char *pPass,
							   char *pForUser,
							   char *pItemNo,
							   char *pType,
							   char *pComment,
							   char *pHostAddr,
							   int confirmNegative)
{
	clsUser			*pTargetUser;
	clsFeedback		*pFeedback;
	clsFeedbackItem	*pFeedbackItem;

	int				feedbackScore;

	char			*pTypeString;
	int				badWordClass;
	char			*pBadWord;

	clsItem			dbItem;
	int				itemNo = 0;
	int				commentorId;
	int				commenteeId;

	time_t			nowTime;
	time_t			memberSinceLimitTime;
	bool			SellerLeavingFeedback = false;
	bool			BidderLeavingFeedback = false;

	SetUp();

	// Title
	*mpStream <<	"<html><head>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Leaving Feedback for "
			  <<	pForUser
			  <<	"</title>"
					"</head>"
			  <<	mpMarketPlace->GetHeader();

	// Check for omitted fields.
	if (FIELD_OMITTED(pUserId))
	{
		*mpStream <<	"\n"
						"<h2>User ID field empty</h2>"
						"Sorry, your User ID field was empty. Please go back "
						"and enter your User ID to leave your feedback comment."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pPass))
	{
		*mpStream <<	"\n"
						"<h2>Password field empty</h2>"
						"Sorry, your password field was empty. Please go back "
						"and enter your password to leave your feedback comment."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pForUser))
	{
		*mpStream <<	"\n"
						"<h2>Target User ID field empty</h2>"
						"Sorry, the user ID field for the user you are commenting on was empty. Please go back "
						"and enter a User ID to leave your feedback comment."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	if (FIELD_OMITTED(pType))
	{
		*mpStream <<	"\n"
						"<h2>Comment type not selected</h2>"
						"Sorry, the type of comment was not selected. Please go back "
						"and specify a positive, negative, or neutral comment type."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Let's not let them leave blank feedback
	if (FIELD_OMITTED(pComment))
	{
		*mpStream <<	"\n"
						"<h2>Comment field empty</h2>"
						"Sorry, the comment field was empty. Please go back "
						"and include a comment with your feedback."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Or overlength feedback
	if  (strlen(pComment) > 80)
	{
		*mpStream <<	"\n"
						"<h2>Comment too long</h2>"
						"Sorry, your comment is too long.  Please go back "
						"and enter a comment that is no longer than 80 characters."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Don't allow negative feedback that's not transaction-related
	if (strcmp(pType, "negative") == 0 && 
		(FIELD_OMITTED(pItemNo) || (pItemNo != NULL && atoi(pItemNo) == 0)))
	{
		*mpStream <<	"\n"
						"<h2>Negative comment not transaction-related</h2>"
						"Sorry, negative comments must be associated with a "
						"transaction.  Please go back and enter a valid item "
						"number to associate with your negative comment."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// First, let's see if the leaver is legit
	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Check the commentor's feedback score and status.
	// If the commentor has a feedback score <= -4 or is
	// suspended, don't let him/her leave feedback.
	if (mpUser->IsSuspended())
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You are not allowed to leave Feedback while you are "
						"suspended."
						"<p>"
						"<b><font color=red>Warning!</font></b> "
						"Using a second email address (an \"alias\") to leave "
						"negative Feedback for another or positive Feedback for yourself "
						"may result in immediate suspension of your eBay registration. "
						"The use of an alias account for any purpose regarding Feedback "
						"is strictly prohibited."
						"<p>"
						"Thank you for your cooperation."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// New from Mar 15, 1999
	// Check to see if the user verification completes properly.
	if (ValidateOrBlockAction() == FALSE)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// get the commentor's feedback
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback		= mpUser->GetFeedback();

	if (!pFeedback)
	{
		*mpStream <<	"\n"
						"<h2>Internal Error</h2>"
						"Unable to obtain your feedback rating. "
				  <<	"Please report this to "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	feedbackScore = pFeedback->GetScore();
	if (feedbackScore <= -4)
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You are not allowed to leave Feedback if your Feedback "
						"rating is "
				  <<	feedbackScore
				  <<	"."
						"<p>"
						"<b><font color=red>Warning!</font></b> "
						"Using a second email address (an \"alias\") to leave "
						"negative Feedback for another or positive Feedback for yourself "
						"may result in immediate suspension of your eBay registration. "
						"The use of an alias account for any purpose regarding Feedback "
						"is strictly prohibited."
						"<p>"
						"Thank you for your cooperation."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Now check the person we're leaving feedback
	// for. We can't use GetAndCheckUser because
	// the error message doesn't make any sense

	clsUtilities::StringLower(pForUser);
	pTargetUser	= mpUsers->GetUser(pForUser);

	if (!pTargetUser)
	{
		*mpStream <<	"\n"
						"<h2>Target user not found</h2>"
						"Sorry, the user you are commenting on, "
						"<font color=\"green\">"
				  <<	pForUser
				  <<	"</font>"
				  <<	", is not a registered user. "
						"Please go back and try again. "
						"\n"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// Verify that source and target IDs are different.  This should cover the case where a user
	// tries to trick us by entering his/her userid or email for the commentor's user ID, and
	// email or userid (whichever is left) for the commentee's user ID.
	if (mpUser->GetId() == pTargetUser->GetId())
	{
		*mpStream <<	"\n"
						"<h2>Invalid Target User ID</h2>"
						"Sorry, you cannot leave feedback for yourself. Please go back "
						"and enter a different target User ID for your feedback comment."
						"\n"
				  <<	mpMarketPlace->GetFooter();

		delete	pTargetUser;
		CleanUp();
		return;
	}

	{
		// -- Don't leave negative feedback for some users.
		int target_id = pTargetUser->GetId();

		if (target_id == 2573939) // Guernsey's
		{
			*mpStream <<	"\n"
							"<h2>Target user not found</h2>"
							"Sorry, the user you are commenting on, "
					  <<	pForUser
					  <<	", is not currently a registered user. "
							"Please go back and try again. "
							"\n"
					  <<	mpMarketPlace->GetFooter();

			delete	pTargetUser;
			CleanUp();
			return;
		}
	}
	// -- End of not leaving negative feedback for some users.

	// Check the commentee's status.  If the commentee is suspended, don't allow
	// him/her to receive feedback.
	if (pTargetUser->IsSuspended())
	{
		*mpStream <<	"<h2>Target User Not Registered</h2>"
						"The user for whom you are leaving feedback, "
						"<font color=\"green\">"
				  <<	pForUser
				  <<	"</font>"
						", is not a registered user. You will be unable to leave feedback "
						"at this time. The two most common reasons for an eBay user to be "
						"classified as not a registered user are their own request to be "
						"removed from active membership in eBay, or suspension.  See the "
				  <<	"<a href=\""
				  <<	mpMarketPlace->GetHTMLPath()
				  <<	"help/myinfo/user-not-registered.html"
						"\">"
						"not a registered user"
						"</a>"
						" page for details."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		delete pTargetUser;
		CleanUp();
		return;
	}


	// Check their state
	if (!pTargetUser->IsConfirmed() &&
		(pTargetUser->GetUserState() != UserGhost))
	{
		*mpStream <<	"\n"
						"<h2>Unconfirmed Target User</h2>"
						"The user that you are leaving feedback for, "
						"<font color=\"green\">"
				  <<	pForUser
				  <<	"</font>"
				  <<	", has registered but has not confirmed their registration. "
						"We're sorry, but you are unable to leave feedback for this "
						"user until they have completed their registration."
						"\n"
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		delete	pTargetUser;
		CleanUp();
		return;
	}

	// Now check the item number that was given, if any.
	if (!FIELD_OMITTED(pItemNo))
	{
		itemNo = atoi(pItemNo);

		// Verify that a transaction between the commentor and commentee took
		// place on the given item.
		commentorId = mpUser->GetId();
		commenteeId = pTargetUser->GetId();

		SellerLeavingFeedback = mpItems->IsValidTransactionFeedback(itemNo, commentorId, commenteeId, TRANSACT_USED_BY_SELLER);
		BidderLeavingFeedback = mpItems->IsValidTransactionFeedback(itemNo, commenteeId, commentorId, TRANSACT_USED_BY_BIDDER);

		if (SellerLeavingFeedback == false && BidderLeavingFeedback == false)
		{
			*mpStream <<	"\n"
							"<h2>Item number not valid</h2>"
							"Sorry, the item number you entered, "
					  <<	pItemNo
					  <<	", either does not exist or does not apply to a <i>completed</i> "
							"transaction between "
					  <<	pUserId
					  <<	" and "
					  <<	pForUser
					  <<	". Please go back and enter a valid item number to leave your "
							"transaction-related feedback comment.<p>"
							"\n"
					  <<	"Note: You may have received this message if more than 60 days "
							"have passed from the completion of the auction. To leave "
							"transaction-related feedback, you must post a comment within 60 "
							"days of the end of the transaction. If you have exceeded this "
							"time limit, please go back and leave your feedback comment without "
							"the item number.<p>"
							"\n"
							"<b>You may also receive this message if you have already "
							"left a feedback comment for the same user with the same item number.</b>"
							"\n"
					  <<	mpMarketPlace->GetFooter();
			delete	pTargetUser;
			CleanUp();
			return;
		}

		mpItem = mpItems->GetItem(itemNo);
		if (mpItem != NULL)
		{
			if (mpItem->GetPrice() < mpItem->GetReservePrice())
			{
				*mpStream <<	"\n"
								"<h2>Auction Reserve Price Not Met</h2>"
								"Sorry, you cannot leave transaction-related feedback "
						  <<	"on item #"
						  <<	itemNo
						  <<	" because the reserve price was not met."
						  <<	"\n"
						  <<	mpMarketPlace->GetFooter();
				
				delete	pTargetUser;
				CleanUp();
				return;
			}
		}
	}

	// use max length of vulgar words ok.
	pBadWord = new char[EBAY_MAX_USERID_SIZE + 1];

	if (clsUtilities::TooVulgar(pComment, &badWordClass, pBadWord))
	{
		if (badWordClass == clsUtilities::VULGAR)
		{
			*mpStream <<	"<h2>Comment Too Vulgar.</h2>"
					 "Sorry, our vulgarity-checking program has determined that "
					 "your comment may contain the word <font color=\"red\">\""
						<< pBadWord
						<< ".\"</font>"
					 "If you did not use a vulgar word, it's possible that our "
					 "program is incorrect.<p>"
					 "For example, suppose that <font color=\"red\">\"dingo\"</font> "
					 "is a vulgar word. If you leave feedback that reads "
					 "\"Merchandise received in good order,\" the program will piece "
					 "together \"receive<font color=\"red\">d in go</font>od\" and "
					 "give you this warning message. If this happened to you, "
					 "just change your feedback a little (e.g., \"Merchandise received "
					 "in fine order\") and the program will let it through. Sorry for "
					 "the inconvenience, but we strive to ensure a pleasant experience "
					 "for all of those in the eBay community, and in some cases, we may"
					 "be overly cautious just to be safe."
					 "\n"
					  <<	mpMarketPlace->GetFooter();

			delete	pTargetUser;
			delete [] pBadWord;
			CleanUp();
			return;
		}
	}

	delete [] pBadWord;

	// Ok, we can get their feedback object now
	// DON'T delete the pFeedback object because clsUser will do it
	pFeedback		= pTargetUser->GetFeedback();

	if (!pFeedback)
	{
		*mpStream <<	"\n"
						"<h2>Internal Error</h2>"
						"Unable to obtain feedback for "
				  <<	pForUser
				  <<	". Please report this to "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();
		
		delete	pTargetUser;
		CleanUp();
		return;
	}

	//
	// Certain limits on negative feedback
	//
	if (strcmp(pType, "negative") == 0)
	{
		//
		// Let's see if they've whined recently
		//
		pFeedbackItem	= 
			pFeedback->RecentFeedbackFromUser(mpUser->GetId(),
											  secondsPerDay,
											  false);

		if (pFeedbackItem)
		{
			if (pFeedbackItem->mType == FEEDBACK_NEGATIVE)
			{
				*mpStream <<	"\n"
								"<b>Sorry!</b>"
								"<br>"
								"You have already posted a negative comment about this person."
								"<p>"
								"If you feel the need to leave another negative comment, "
								"you may want to try to resolve your situation civilly by "
								"phone. We have found that many complaints are the result "
								"of simple misunderstandings. We encourage you to give "
								"people the benefit of the doubt and to try to resolve "
								"any misunderstanding together."
								"<p>"
								"If you are leaving a negative comment for another user "
								"and have not yet tried to contact him or her directly, "
								"we suggest that you try to do so.  You can request a "
								"user\'s contact information "
								"<A HREF="
								"\""
						  <<	mpMarketPlace->GetHTMLPath()
						  <<	"search/members/user-query.html"
								"\""
								">"
								"here"
								"</A>"
								"."
								"<p>"
								"<b><font color=red>Warning!</font></b> "
								" Using a second email address (an \"alias\") to leave "
								"negative feedback for another or positive feedback for "
								"yourself may result in immediate suspension of your eBay "
								"registration.  The use of an alias account for any purpose "
								"regarding feedback is strictly prohibited."
								"<p>"
								"Thank you for your cooperation.  We hope your future dealings "
								"are more successful."
						  <<	"<p>"
						  <<	mpMarketPlace->GetFooter();

				delete	pFeedbackItem;
				delete	pTargetUser;
				CleanUp();

				return;
			}
			else	// recent feedback was positive or neutral
			{
				// was recent feedback left in the past hour?
				if (time(0) - pFeedbackItem->mTime <= secondsPerHour)
				{
					*mpStream <<	"\n"
									"<b>Sorry!</b>"
									"<br>"
									"You have already posted a comment about this person."
									"<p>"
									"If you feel the need to leave another comment, you can "
									"do so later. "
									"<p>"
									"Thank you for your cooperation. "
									"<p>"
							  <<	mpMarketPlace->GetFooter();

					delete	pFeedbackItem;
					delete	pTargetUser;
					CleanUp();

					return;
				}
			}
		}
			
		pFeedbackItem	= 
			pFeedback->RecentFeedbackFromHost(pHostAddr,
											  secondsPerDay,
											  false);

		if (pFeedbackItem)
		{
			if (pFeedbackItem->mType == FEEDBACK_NEGATIVE)
			{
				*mpStream <<	"\n"
								"<b>Sorry!</b>"
								"<br>"
								"A negative comment about this user has already "
								"been made from this location. If you felt the need "
								"to leave another negative comment, you may want to try "
								"to resolve your situation civilly by phone. We "
								"encourage you to give people the benefit of the doubt "
								"and to try to resolve any misunderstanding together."
								"<p>"
								"If you are leaving a negative comment for another user "
								"and have not yet tried to contact him or her directly, "
								"we suggest that you try to do so.  You can request a "
								"user's contact information "
								"<A HREF="
								"\""
						  <<	mpMarketPlace->GetHTMLPath()
						  <<	"search/members/user-query.html"
								"\""
								">"
								"here"
								"</A>"
								"."
								"<p>"
								"<b><FONT COLOR=\"#FF0033\">Warning!</font></b> "
								"Using a second email address (an \"alias\") to leave negative "
								"feedback for another or positive feedback for yourself may result "
								"in immediate suspension of your eBay registration.  "
								"The use of an alias account for any purpose regarding feedback "
								"is strictly prohibited."
								"<p>"
								"Thank you for your cooperation.  We hope your future dealings "
								"are more successful."
								"<p>"
						  <<	mpMarketPlace->GetFooter();

				delete	pFeedbackItem;
				delete	pTargetUser;
				CleanUp();

				return;
			}
			else	// recent positive or neutral feedback
			{
				// was recent feedback left in the past hour?
				if (time(0) - pFeedbackItem->mTime <= secondsPerHour)
				{
					*mpStream <<	"\n"
									"<b>Sorry!</b>"
									"<br>"
									"A comment about this user has already "
									"been made from this location. If you felt the need "
									"to leave another comment, please do so later. "
									"<p>"
									"Thank you for your cooperation. "
									"<p>"
							  <<	mpMarketPlace->GetFooter();

					delete	pFeedbackItem;
					delete	pTargetUser;
					CleanUp();

					return;
				}
			}
		}
	}
	else	// current feedback is positive or neutral
	{
		// check for recent feedback and host
		pFeedbackItem	= 
			pFeedback->RecentFeedbackFromUser(mpUser->GetId(),
											  secondsPerHour,
											  false);
	
		if (pFeedbackItem)
		{
			*mpStream <<	"\n"
							"<b>Sorry!</b>"
							"<br>"
							"You have already posted a comment about this person."
							"<p>"
							"If you feel the need to leave another comment, you can "
							"do so later. "
							"<p>"
							"Thank you for your cooperation. "
							"<p>"
					  <<	mpMarketPlace->GetFooter();

			delete	pFeedbackItem;
			delete	pTargetUser;
			CleanUp();

			return;
		}
			
		pFeedbackItem	= 
			pFeedback->RecentFeedbackFromHost(pHostAddr,
											  secondsPerHour,
											  false);

		if (pFeedbackItem)
		{
			*mpStream <<	"\n"
							"<b>Sorry!</b>"
							"<br>"
							"A comment about this user has already "
							"been made from this location. If you felt the need "
							"to leave another comment, please do so later. "
							"<p>"
							"Thank you for your cooperation. "
							"<p>"
					  <<	mpMarketPlace->GetFooter();

			delete	pFeedbackItem;
			delete	pTargetUser;
			CleanUp();

			return;
		}
	}

	nowTime					= time(0);
	memberSinceLimitTime	= nowTime - (secondsPerDay * 5);

	if (mpUser->GetCreated() > memberSinceLimitTime)
	{
		*mpStream <<	"<b>Sorry!</b>"
						"<br>"
						"You must be a member of eBay for more than five (5) days to "
						"leave Feedback for any user."
						"<p>"
						"<b><font color=red>Warning!</font></b> "
						"Using a second email address (an \"alias\") to leave "
						"negative Feedback for another or positive Feedback for yourself "
						"may result in immediate suspension of your eBay registration. "
						"The use of an alias account for any purpose regarding Feedback "
						"is strictly prohibited."
						"<br>"
						"Thank you for your cooperation."
						"<p>"
				  <<	mpMarketPlace->GetFooter();

		delete	pFeedbackItem;
		delete	pTargetUser;
		CleanUp();

		return;
	}

	// Ok, we can leave the feedback now
	
	if (strcmp(pType, "positive") == 0)
	{
		pTypeString	= "praise about";
		pFeedback->AddFeedback(mpUser->GetId(),
							   pHostAddr,
							   pComment,
							   FEEDBACK_POSITIVE,
							   itemNo);
	}
	else if (strcmp(pType, "negative") == 0)
	{
		if (!confirmNegative)
		{
			LeaveFeedbackConfirm(pUserId,
								 pPass,
								 pForUser,
								 pItemNo,
								 pComment);
			delete	pFeedbackItem;
			delete	pTargetUser;
			CleanUp();
			return;
		}

		pTypeString	= "complaint about";
		pFeedback->AddFeedback(mpUser->GetId(),
							   pHostAddr,
							   pComment,
							   FEEDBACK_NEGATIVE,
							   itemNo);
	}
	else if (strcmp(pType, "neutral") == 0)
	{
		pTypeString	= "neutral comment about";
		pFeedback->AddFeedback(mpUser->GetId(),
							   pHostAddr,
							   pComment,
							   FEEDBACK_NEUTRAL,
							   itemNo);
	}
	else if (strcmp(pType, "default") == 0)
	{
		*mpStream <<	"<h2>No Feedback Type Selected</h2>"
						"You did not select a feedback type (neutral, "
						"positive, or negative) when entering your "
						"feedback. Please go back and try again."
						"\n"
				  <<	mpMarketPlace->GetFooter();
		delete	pTargetUser;
		CleanUp();
		return;
	}
	else
	{
		*mpStream <<	"<h2>Internal Error</h2>"
						"The feedback type "
				  <<	pType
				  <<	" is unrecognized."
						"Please report this problem to "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" "
				  <<	mpMarketPlace->GetSupportEmail()
				  <<	"."
				  <<	"\n"
				  <<	mpMarketPlace->GetFooter();
		delete	pTargetUser;
		CleanUp();
		return;
	}

	// Now, announce that we're done
	*mpStream <<	"<p>"
			  <<	"Thank you. Your "
			  <<	pTypeString
			  <<	" "
			  <<	"<font color=\"green\">"
			  <<	pForUser
			  <<	"</font>"
			  <<	" has been recorded, and his/her Feedback Rating is now "
			  <<	"<A HREF="
					"\""
			  <<	mpMarketPlace->GetCGIPath(PageViewFeedback)
			  <<	"eBayISAPI.dll?ViewFeedback"
			  <<	"&userid="
			  <<	pForUser
			  <<	"&page="
			  <<	1
			  <<	"&items="
			  <<	0
			  <<	"\">"
			  <<	pFeedback->GetScore()
			  <<	"</a>"
			  <<	". Click "
			  <<	"<a href=\""
			  <<	mpMarketPlace->GetCGIPath(PageViewPersonalizedFeedback)
			  <<	"eBayISAPI.dll?ViewFeedback&userid="
			  <<	pForUser
			  <<	"&page="
			  <<	1
			  <<	"&items="
			  <<	0
			  <<	"\">here</a> to view "
			  <<	"<font color=\"green\">"
			  <<	pForUser
			  <<	"</font>"
			  <<	"'s updated feedback profile."
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	// set the transaction used flag accordingly
	if (SellerLeavingFeedback)
		mpItems->SetTransactionUsed(itemNo, commentorId, commenteeId, TRANSACT_USED_BY_SELLER);
	else if (BidderLeavingFeedback)
		mpItems->SetTransactionUsed(itemNo, commenteeId, commentorId, TRANSACT_USED_BY_BIDDER);

	//
	// *** NOTE ***
	// Temporary code to log feedback in the "old" style
	// in case we have to back out
	// *** NOTE ***
	FILE	*pFeedbackLogFile;
	time_t	logTime	= time(0);
	char	logType;

	pFeedbackLogFile	= fopen("D:\\feedback.log", "a");

	if (!pFeedbackLogFile)
	{
		Trace("%s:%d Unable to open feedback log. Error <%s>\n",
			  __FILE__, __LINE__, strerror(errno));
	}
	else
	{
		if (strcmp(pType, "positive") == 0)
			logType	= 'C';
		else if (strcmp(pType, "negative") == 0)
			logType	= 'D';
		else 
			logType	= 'N';

		fprintf(pFeedbackLogFile,"%ld\t%c\t%s\t%s\t%s\t%s\n",
				logTime,
				logType,
				pTargetUser->GetUserId(),
				mpUser->GetUserId(),
				pHostAddr,
				pComment);

		fclose(pFeedbackLogFile);
	}

	delete	pTargetUser;

	CleanUp();

	return;
}

	

