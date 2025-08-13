/*	$Id: clsUsers.cpp,v 1.12.64.2.82.1 1999/08/01 03:02:34 barry Exp $	*/
//
//	File:		clsUsers.cc
//
// Class:	clsUsers
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a collection of items
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/20/97 tini		- added renameUser to handle renaming users.
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "eBayKernel.h"

#include <stdio.h>
#include <time.h>

const int MAXUSERCODECOUNT= 20;

//
// Constructor
//
clsUsers::clsUsers(clsMarketPlace *pMarketPlace)
{
	mpMarketPlace		= pMarketPlace;
	mpUserCodes			= NULL;
	mpUserValidation	= NULL;
	mUsersCacheValid	= false;
	mCachingUsers		= false;
	return;
}

//
// Destructor
//
clsUsers::~clsUsers()
{		
	delete mpUserCodes;
	delete mpUserValidation;
	return;
}

//
// GetUser (by character userid)
//	Just ask the Database to do it!
//
clsUser *clsUsers::GetUser(char *pUserId,
						   bool needFeedback,
						   bool needAccount)
{
	clsUser		*pUser = NULL;
	clsFeedback	*pFeedback;
	char		*pCleanUserId = NULL;

	// clean up the userid/email first (which also lowercases it)
	if (pUserId) pCleanUserId = clsUtilities::CleanUpUserId(pUserId);

	// safety
	if (!pCleanUserId) return NULL;

	// now go to the database
	if (!needFeedback && !needAccount)
	{
		pUser	= gApp->GetDatabase()->GetUserByUserId(
										mpMarketPlace->GetId(),
										pCleanUserId);
		if (!pUser && strchr(pCleanUserId, '@'))
		{
			pUser	= gApp->GetDatabase()->GetUserByEmail(
										mpMarketPlace->GetId(),
										pCleanUserId);
		}
	}
	else if (needFeedback && !needAccount)
	{
		gApp->GetDatabase()->GetUserAndFeedbackByUserId(
										mpMarketPlace->GetId(),
										pCleanUserId,
										&pUser,
										&pFeedback);
		if (pUser)
			pUser->SetFeedback(pFeedback);
		else
		{
			if (strchr(pCleanUserId, '@'))
			{
				gApp->GetDatabase()->GetUserAndFeedbackByEmail(
											mpMarketPlace->GetId(),
											pCleanUserId,
											&pUser,
											&pFeedback);
				if (pUser)
					pUser->SetFeedback(pFeedback);
			}
		}

	}
	else if (needFeedback && needAccount)
	{
	}

	if (pCleanUserId) delete [] pCleanUserId;

	return pUser;
}
			


//
// GetUser (by id)
//	Just ask the Database to do it!
//
clsUser *clsUsers::GetUser(int id,
						   bool needFeedback,
						   bool needAccount)
{
	clsUser		*pUser;
	clsFeedback	*pFeedback;


	if (!needFeedback && !needAccount)
	{
		pUser	= 
			gApp->GetDatabase()->GetUserById(id);
	}
	else if (needFeedback && !needAccount)
	{
		gApp->GetDatabase()->GetUserAndFeedbackById(id,
											  &pUser,
											  &pFeedback);
		if (pUser)
			pUser->SetFeedback(pFeedback);
	}
	else if (needFeedback && needAccount)
	{
	}

	return pUser;
}

// Get user by email
clsUser *clsUsers::GetUserByEmail(char *pEmail,
								bool needFeedback/*= false*/,
								bool needAccount /*= false*/)
{
	clsUser		*pUser;
	clsFeedback	*pFeedback;

	if (!needFeedback && !needAccount)
	{
		pUser	= gApp->GetDatabase()->GetUserByEmail(
							mpMarketPlace->GetId(),
							pEmail);
	}
	else if (needFeedback && !needAccount)
	{
		gApp->GetDatabase()->GetUserAndFeedbackByEmail(
							mpMarketPlace->GetId(),
							pEmail,
							&pUser,
							&pFeedback);
		if (pUser)
			pUser->SetFeedback(pFeedback);
	}

	return pUser;
}


//
// AddUser
//
void clsUsers::AddUser(clsUser *pUser)
{
	int	id;

	// Let's see if we need an id
	if (pUser->GetId() == 0)
	{
		id	= gApp->GetDatabase()->GetNextUserId();
		pUser->SetId(id);
	}

	// Need a try/catch around the database call
	// begin transaction
	gApp->GetDatabase()->Begin();
	gApp->GetDatabase()->AddUser(pUser);

	//  *** NOTE ***
	//	If the user's NOT a ghost, then we need to 
	//	add the user-info too
	//	*** NOTE ***
	if (pUser->GetUserState() != UserGhost)
		gApp->GetDatabase()->AddUserInfo(pUser);

	gApp->GetDatabase()->End();
	return;
}

//
// UpdateUser
//
void clsUsers::UpdateUser(clsUser *pUser)
{
	// Need a try/catch around the database call
	if (pUser->IsDirty())
		gApp->GetDatabase()->UpdateUser(pUser);

	if (pUser->IsInfoDirty() || (pUser->GetUserState() != UserGhost))
		gApp->GetDatabase()->UpdateUserInfo(pUser);
// should actually commit, but commit is private
	// gApp->GetDatabase()->Commit();
	return;
}

void clsUsers::DeleteUserLists(clsUser *pUser)
{
	gApp->GetDatabase()->DeleteUserLists(pUser);
	return;
}
//
// DeleteUserInfo
//
void clsUsers::DeleteUserInfo(clsUser *pUser)
{
	gApp->GetDatabase()->DeleteUserInfo(pUser);
	return;
}

//
// DeleteUser
//
void clsUsers::DeleteUser(clsUser *pUser)
{
	gApp->GetDatabase()->DeleteUser(pUser);
	return;
}

//
// DeleteAllUsers
//
void clsUsers::DeleteAllUsers()
{
	return;
}

// New rename function: using no transactions
// 1. change old user to state InMaintenance
// 2. move feedback detail to new user
// 3. move all other data user is involved in to new user
// 4. move user info, relevant dates to new user
// 5. delete account of old; rebalance of new user
// 6. set user state: OLD user = deleted; 		  
// name is misleading from history: its actually combining 2 users
void clsUsers::RenameUser(clsUser *pOldUser,
						  clsUser *pNewUser,
						  char	*pOrigOldUserId,
						  char	*pOrigNewUserId)
{
	clsFeedback	*pFeedback;
	char		oldUserId[256];
	char		newUserId[256];
	char		oldUserEmail[256];
	char		newUserEmail[256];
	char		xoldUserId[256];
	char		xoldUserEmail[256];
	int			oldId;
	int			newId;
	int			awid;
	time_t		changeTime;
	FILE		*pRenameLog;

	pRenameLog	= fopen("rename.log", "a");

	if (!pRenameLog)
	{
		fprintf(stderr,"%s:%d Unable to open end of auction log. \n",
			  __FILE__, __LINE__);
	}

	// check if user is already renamed
	// newId = oldId means they both refer to the same user already
	newId = pNewUser->GetId();
	oldId = pOldUser->GetId();
	// remember previous userids
	strcpy(newUserId, pNewUser->GetUserId());
	strcpy(oldUserId, pOldUser->GetUserId());
	// remember previous emails
	strcpy(oldUserEmail, pOldUser->GetEmail());
	strcpy(newUserEmail, pNewUser->GetEmail());
	// Ack. Need to check for overflow!
	sprintf(xoldUserId, "%d", oldId);
	strcat( xoldUserId, "@" );
	strcat( xoldUserId, oldUserId );
//	xoldUserId[0]= '@';
	sprintf(xoldUserEmail, "%d", oldId);
	strcat( xoldUserEmail, "@" );
	strcat( xoldUserEmail, oldUserEmail );
//	xoldUserEmail[0] = '@';
//	strcpy(xoldUserId+1, oldUserId);
//	strcpy(xoldUserEmail+1, oldUserEmail);

	if (newId != oldId)
	{
		// begin transaction
//		gApp->GetDatabase()->Begin();
		// set user states 
		pOldUser->SetInMaintenance();
		pOldUser->UpdateUser();

		// Instead of checking if the user has any feedback, we
		// put in a neutral feedback to old user saying feedback 
		// for OldUser is transferred to NewUser so we'll have 
		// at least one feedback.

		// DON'T delete the pFeedback object because clsUser will do it
		pFeedback = pNewUser->GetFeedback();

		// Now, transfer the feedback from new user to old user
		// because there are less feedback for new user and we want to keep
		// the old user's identity instead of using the new one.
		// note: transfer is from pNewUser to pOldUser
		pFeedback->Transfer(pOldUser, pNewUser);

		// Now, it's safe to delete the "old" user's feedback record
		// which should have no more feedback items
		gApp->GetDatabase()->DeleteFeedback(pOldUser->GetId());
	
		// transfer all users' 'assets' from the old user to new user
		// i.e.: ebay_accounts (id), ebay_bids (user_id), 
		//       ebay_items (owner, seller, high_bidder)
		//		 BB board, QA board.
		// note: change id from newId to oldId - reversed
		gApp->GetDatabase()->RenameIdInUserAssets(oldId, newId);

		// now we can invalidate the new user's seller and bidder lists and
		// delete the old user's seller and bidder lists.
		gApp->GetDatabase()->InvalidateSellerList(mpMarketPlace->GetId(),newId);
		gApp->GetDatabase()->InvalidateBidderList(mpMarketPlace->GetId(),newId);

		gApp->GetDatabase()->DeleteSellerList(mpMarketPlace->GetId(),oldId);
		gApp->GetDatabase()->DeleteBidderList(mpMarketPlace->GetId(),oldId);

		// do the new aliases for email and userid
		changeTime	= time(0);
		gApp->GetDatabase()->AddEmailAlias(mpMarketPlace->GetId(),
				  						   newId,
										   oldUserEmail,
										   "host",
										   changeTime);

		// track userid change even if userid = email
		gApp->GetDatabase()->AddUserAlias(mpMarketPlace->GetId(),
				  						  newId,
										  oldUserId,
										  "host",
										  changeTime);

		// delete user's account balance record for the new user
		// and rebalance_account for the old user id
		// if there's an account
		gApp->GetDatabase()->CombineInterimBalanceForUsers( oldId, newId );
		pOldUser->GetAccount()->DeleteAccountBalance();
		pNewUser->GetAccount()->Rebalance();
			
		// transfer all newUser's data to OldUser
		// ***EXCEPT FOR USERID AND EMAIL
		pNewUser->CopyUserData(pOldUser,changeTime);

		// add to ebay_renamed_users table OLD userid and OLD id
		/* obsolete 
		gApp->GetDatabase()->AddRenamedUser(oldUserId,newUserId);
		*/

		// kludge to go around ebay_account_xref constraint violation
		// if there is a record with the old id in ebay_account_xref,
		// we need to delete the old record and keep a record in logfile.
		awid = gApp->GetDatabase()->IsUserAccountXref(oldId);
		if ( awid != 0)
		{
			gApp->GetDatabase()->DeleteUserAccountXref(oldId);
			fprintf(pRenameLog, "Deleted from ebay_account_xref id:%d : aw-id:%d",
					awid,
					oldId);
		}

		// delete new user's info so email could be reused
		DeleteUserLists(pOldUser);
		
		pOldUser->SetDeleted();
		pOldUser->SetUserId(xoldUserId);
		pOldUser->SetEmail(xoldUserEmail);
		/* instead of doing just UpdateUser, we need to go update both
		for the interim period while emailmove is rolled out.
		pOldUser->UpdateUser();
		*/
		gApp->GetDatabase()->UpdateUser(pOldUser);
		gApp->GetDatabase()->UpdateUserInfo(pOldUser);

		// do we need to set new user's userid to old user's userid?
		if ((strcmp(newUserEmail,newUserId) == 0) && 
			(strcmp(oldUserEmail,oldUserId) != 0) && 
			(strchr(oldUserId, '@') == NULL))
		{
			// assign old user id to new user for use
			// this can't be in merge user data because userid is a constraint!
			pNewUser->SetUserId(oldUserId);
			pNewUser->UpdateUser();
		}

			// rename userid in "old" user to newUserId
		/* obsolete
		gApp->GetDatabase()->RenameUser(mpMarketPlace->GetId(),
								oldUserId, newUserId);
								*/

		// make sure all references to the old user is redirected in
		// ebay_renamed_users table
		/* obsolete
		gApp->GetDatabase()->RenameRenamedUser(oldUserId, newUserId);

		*/

		// Recompute score for the "new" user 
		// need to get the object again so it can get the actual feedback
		// items instead of caching it in clsFeedback.
		// this is done in the transfer
//			pFeedback = pNewUser->GetFeedback();
//			pFeedback->SetScore(pFeedback->RecomputeScore());

		// all done!
//		gApp->GetDatabase()->End();

	}
	return;
}	

//
// GetAndCheckUser
//
clsUser *clsUsers::GetAndCheckUser(char *pUserId,
								   ostream *pStream,
								   bool headerSent)
{
	clsUser	*pUser = NULL;

	// get the user
	pUser = this->GetUser(pUserId);

	if (!pUser && pStream != NULL)
	{
		if (!headerSent)
		{
			*pStream  <<	mpMarketPlace->GetHeader()
					  <<	"\n";
		}
		*pStream  <<	"<H2>";

		*pStream  <<	mpMarketPlace->GetLoginPrompt()
				  <<	" invalid"
				  <<	"</H2>";

		*pStream  <<	"The ";

		*pStream  <<	mpMarketPlace->GetLoginPrompt()
				  <<	" "
				  <<	"\""
				  <<	pUserId
				  <<	"\""
				  <<	" is not a registered "
				  <<	mpMarketPlace->GetCurrentPartnerName()
				  <<	" user. "
				  <<	"Please go back and try again. "
				  <<	"Make sure you are not using any uppercase "
				  <<	"characters or allowing blank space before "
				  <<	"or after or in the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	"."
			      <<    "\n";
	}

	return pUser;
}

//
// Common routine to do basic user checks
//
clsUser *clsUsers::GetAndCheckUserAndPassword(char *pUserId,
												char *pPassword,
												ostream *pStream,
												bool headerSent,
												char *pAction,
												bool ghostOk,
												bool feedbackScoreNeeded,
												bool accountBalanceNeeded,
												bool checkEncryptedPassword,
												bool adminCheck)
{
	char	myPassword[256];
	bool	ok	= true;

	char	myEncryptedPassword[65];		// Added by Charles
	char	myEncryptedPasswordNoSalt[65];	// added by poon

	clsUser	*pUser = NULL;

	if (ok && pPassword && strlen(pPassword) > 0 && 
		(strlen(pPassword) + 1) <= sizeof(myPassword))
	{
		strcpy(myPassword, pPassword);
#ifdef _MSC_VER
		strlwr(myPassword);
#endif
	}
	else
		ok	= false;


	if (ok)
	{
		pUser	= GetUser(pUserId,
						  feedbackScoreNeeded,
						  accountBalanceNeeded);

		if (pUser)
		{
			if (pUser->TestPass(myPassword))
				ok	= true;
			else
				ok	= false;

			// 01/09/98 Added by Charles
			if((ok == false) && checkEncryptedPassword)
			{
				memset(myEncryptedPassword,0,sizeof(myEncryptedPassword));
				// get the encrypted password from the database
				strcpy(myEncryptedPassword,pUser->GetPassword());
#ifdef _MSC_VER
				strlwr(myEncryptedPassword);
#endif
				if (!strcmp(myEncryptedPassword,myPassword))
					ok = true;

				// Added by poon
				// ok, let's try the encrypted password with the salt removed,
				//  in case the test password (myPassword) had the salt stripped from it
				if (ok == false)
				{
					strcpy(myEncryptedPasswordNoSalt, pUser->GetPasswordNoSalt());
#ifdef _MSC_VER
					strlwr(myEncryptedPasswordNoSalt);
#endif
					// compare
					if (!strcmp(myEncryptedPasswordNoSalt,myPassword))
						ok = true;
				}
			}

		}
		else
			ok	= false;
	}

	// If it wasn't ok, let's dispose of the user
	if (!ok && pUser != NULL)
	{
		delete	pUser;
		pUser	= NULL;
	}

	// Now, we don't know yet if the user HAS to be registered
	// to bid. But we don't want to emit the fact that they
	// are suspended to anyone who can enter their userid.
	// Soooo, we test the password first. Cool, huh?

	if (!ok)
	{
		if (pStream != NULL)
		{
			if (!headerSent)
			{
				*pStream <<	mpMarketPlace->GetHeader()
						  <<	"\n";
			}

			*pStream  <<	"<H2>";
			*pStream  <<	mpMarketPlace->GetLoginPrompt()
					  <<	" or password invalid"
							"</H2>"
							"Either the ";
			*pStream  <<	mpMarketPlace->GetLoginPrompt()
					  <<	" "
							"\""
					  <<	pUserId
					  <<	"\""
							" is not a registered "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" user, or the password is incorrect. "
							"Please go back and try again. "
					  <<	"Make sure you are not using any uppercase "
					  <<	"characters or allowing blank space before, "
					  <<	" after or inside the ";
			*pStream  <<	mpMarketPlace->GetLoginPrompt()
					  <<	" or password."
							"<p>"
							"If you are not a registered "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" user, you can proceed to our <b>Free</b> "
							"<A HREF="
							"\""
					  <<	mpMarketPlace->GetHTMLPath()
					  <<	"services/registration/register.html"
							"\""
							">"
							"registration page"
							"</a>"
							" to become a registered "
					  <<	mpMarketPlace->GetCurrentPartnerName()
					  <<	" user."
							"<p>";
		}

		return NULL;	
	}

    if (!ok)
        return NULL;

	//
	// User Exists (in some state) and passed the password check. If
	// this is an "admin" check, then we can leave now, since the
	// caller doesn't "care" what the state of the user is.
	//
	if (adminCheck)
		return pUser;

	// First, handle ghostliness
	if ((pUser->GetUserState() == UserGhost) && ghostOk)
		return pUser;

	if ((pUser->GetUserState() == UserGhost) && !ghostOk)
	{
		if (pStream)
		{
			if (!headerSent)
			{
				*pStream <<	mpMarketPlace->GetHeader()
						  <<	"\n";
			}

			*pStream <<	"<h2>eBay Beta Confirmation Needed</h2>"
						"Before you can use the eBay Beta system, eBay would "
						"like you to "
						"<A HREF="
						"\""
					 <<	mpMarketPlace->GetHTMLPath()
					 <<	"beta-confirm.html"
						"\" TARGET=NEW"
						">"
						"confirm your registration information"
						"</a>"
						" by following the preceding link. This will only take "
						"a moment, and will help us ensure your registration "
						"information is correct."
						"<p>"
						"<b>Note:</b> On most browsers, clicking on the link will "
						"open a new browser window in which you can confirm your "
						"registration information. Once you are done, you can close "
						"this window and go back to complete your request."
						"<p>";
		}

		delete pUser;
		return NULL;
	}

	if (pUser->IsSuspended())
	{
		if (pStream)
		{
			if (!headerSent)
			{
				*pStream <<	mpMarketPlace->GetHeader()
						  <<	"\n";
			}
			*pStream <<	"<h2>"
						"Registration blocked"
						"</h2>";

			if (pAction)
			{
				*pStream <<	"Users whose registered status is blocked cannot "
						 <<	pAction
						 <<	mpMarketPlace->GetCurrentPartnerName()
						 <<	". Please resolve any outstanding complaints on file "
							"before proceeding.";
			}
			else
			{
				*pStream <<	"Users whose registered status is blocked must resolve "
							"any outstanding complaints on file before proceeding. "
							"Please resolve these issues and try again.";
			}
		}

		delete	pUser;
		return NULL;
	}


	if (!pUser->IsConfirmed())
	{
		if (pStream)
		{
			if (!headerSent)
			{
				*pStream <<	mpMarketPlace->GetHeader()
						  <<	"\n";
			}
			*pStream <<	"<h2>"
						"Unregistered user or registration not confirmed"
						"</h2>";

			if (pAction)
			{
				*pStream << "Unregistered users or users who have not "
							"completed their registration can not "
						 <<	pAction
						 << mpMarketPlace->GetCurrentPartnerName()
						 << ". Please complete your registration and try again";
			}
			else
			{
				*pStream <<	"You must register with "
						 <<	mpMarketPlace->GetCurrentPartnerName()
						 <<	" or complete your registration before proceeding. "
							"Please complete your registration and try again.<P>";
			}
		}
		delete pUser;
		return NULL;
	}


	return pUser;
}



//
// GetUserBySubString
//
void clsUsers::GetUsersBySubstring(UserSearchTypeEnum how,
								   char *pString, 
								   vector<clsUser *> *pvUsers)
{
	gApp->GetDatabase()->GetUsersBySubstring(mpMarketPlace->GetId(),
											 how,
											 pString,
											 pvUsers);
	return;
}

void clsUsers::GetUserIdsByFeedback(int minFeedback, 
								   vector<int> *pvUsers)
{
	gApp->GetDatabase()->GetUserIdsByFeedback(minFeedback, 
											  pvUsers);
}


void clsUsers::GetUserIdsAndFeedbackByFeedback(
								   int minFeedback, 
								   vector<clsUserPtr> *pvUsers)
{
	gApp->GetDatabase()->GetUserIdsAndFeedbackByFeedback(
								   minFeedback, 
								   pvUsers);
}

//
// GetUsersWithAccounts
//
//	Returns a vector of user ids which have eBay accounts. Note
//	that the database call is in clsDatabase*Accounts
//
void clsUsers::GetUsersWithAccounts(vector<unsigned int> *pvIds)
{
	gApp->GetDatabase()->GetUsersWithAccounts(pvIds);
	return;
}

// GetUserswithBadAccount
// Returns a vector of user ids which have bad ebay account detail
// records. Used only in relisting fix.
//
void clsUsers::GetUsersWithBadAccounts(vector<unsigned int> *pvUsers)
{
	gApp->GetDatabase()->GetUsersWithBadAccounts(pvUsers);
	return;
}


clsUserCodes *clsUsers::GetUserCodes()
{	
	if(! mpUserCodes )
	{
		mpUserCodes= new clsUserCodes;		
		return mpUserCodes;		
	}
	else		
		return mpUserCodes;	
}

//
// GetIdByAlias
//

void clsUsers::GetIdByAlias(char *alias, UserIdAliasHistoryVector *pvUsers)
{
	gApp->GetDatabase()->GetIdByAlias(mpMarketPlace->GetId(), alias, pvUsers);
	return;
}

//
// GetManyUsersForCreditBatch
//
void clsUsers::GetManyUsersForCreditBatch(list<unsigned int> *pUserIdList,
			   							  UserList *pUsers)
{
	gApp->GetDatabase()->GetManyUsersForCreditBatch(mpMarketPlace->GetId(),
													pUserIdList,
													pUsers);
}

//
// Check whether an email is an anonymous email
//
bool clsUsers::IsAnonymousEmail(char* pEmail)
{
	return gApp->GetDatabase()->IsAnonymousEmail(pEmail);
}

//
// Get All User Accounts
//
void clsUsers::GetUserAccounts(AccountsVector *pvAccounts)
{
        gApp->GetDatabase()->GetUserAccounts(pvAccounts);
}


clsUserValidation *clsUsers::GetUserValidation()
{
	if (!mpUserValidation)
		mpUserValidation = new clsUserValidation;

	return mpUserValidation;
}

//
// Get ActiveUsers with flags > 2
//
void clsUsers::GetActiveUsers(vector<int> *pvIds)
{
	gApp->GetDatabase()->GetActiveUsers(mpMarketPlace->GetId(),
										pvIds);
}

//
// StartCaching
//
void clsUsers::StartCaching()
{
	mCachingUsers		= true;
}

//
// StopCaching
//
void clsUsers::StopCaching()
{
	mCachingUsers		= false;
}


//
// BuildCache
//
bool clsUsers::BuildCache(list <UserId> *plUsersIdsRequested,
						  list <UserId> *plMissingUserIds)
{
	// An iterator for lists of userids
	list<unsigned int>::iterator	ilUserIds;

	// An haserator for the user cache
	hash_map<const int, clsUsersCacheHash *, hash<int>, eqint>::
		const_iterator				ihUsers;

	// User hash object
	clsUsersCacheHash				*pUserHash;

	// List o'users we'll fetch
	list<unsigned int>				lUserIds;

	// List o'users we got back and it's iterator
	UserList						lUsers;
	UserList::iterator				ilUsers;


	//
	// First, we create "unfilled" clsUsersCacheHash objects
	// for all the entries in the passed list. This also allows
	// us to handle duplicates in the list, along with items
	// which are already in the cache.
	//
	for (ilUserIds = plUsersIdsRequested->begin();
		 ilUserIds != plUsersIdsRequested->end();
		 ilUserIds++)
	{
		// Let's see if we've seen them
		ihUsers	= mhUsersCache.find(*ilUserIds);

		// If we haven't got it, add it
		if (ihUsers == mhUsersCache.end())
		{
			pUserHash	= new clsUsersCacheHash(*ilUserIds);

			mhUsersCache[(*ilUserIds)]	= pUserHash;
		}
	}

	//
	// Now, we construct a new list to be passed to the database.
	// Note that we only pass entries which aren't "valid" in the
	// cache, so we don't fetch them twice.
	//
	for (ihUsers = mhUsersCache.begin();
		 ihUsers != mhUsersCache.end();
		 ihUsers++)
	{
		// We only put it on the list if it isn't
		// valid
		if (!(*ihUsers).second->mValid)
			lUserIds.push_back((*ihUsers).first);
	}

	//
	// Let's ask the database for them now
	//
	gApp->GetDatabase()->GetManyUsers(gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetId(),
									  &lUserIds,
									  plMissingUserIds,
									  &lUsers);

	//
	// Now, run thru and plug the cache entries with the
	// addresses of the clsUser objects, and mark them
	// valid
	//
	for (ilUsers = lUsers.begin();
		 ilUsers != lUsers.end();
		 ilUsers++)
	{
		ihUsers	= mhUsersCache.find((*ilUsers).mpUser->GetId());

		if (ihUsers == mhUsersCache.end())
		{
			// What!
			continue;
		}

		(*ihUsers).second->mpUser	= (*ilUsers).mpUser;
		(*ihUsers).second->mValid	= true;


	}

	// Let's clean our private list of userids
	lUserIds.erase(lUserIds.begin(), lUserIds.end());

	// Set cache valid
	mUsersCacheValid	= true;


	// And return, depending on whether we got them all or not
	if (plMissingUserIds->empty())
		return true;
	return false;
}

//
// ClearCache
//
// Does NOT clear the Caching bit!
//
void clsUsers::ClearCache()
{
	// An haserator for the user cache
	hash_map<const int, clsUsersCacheHash *, hash<int>, eqint>::
		const_iterator				ihUsers;

	// Delete each user object in the cache. Deleting the user will delete
	// their feedback object, also
	for (ihUsers = mhUsersCache.begin();
		 ihUsers != mhUsersCache.end();
		 ihUsers++)
	{
		// Deleting the hash deletes the user, and thus the feedback.
		delete	(*ihUsers).second;
	}

	// Now, trash dee cache
	mhUsersCache.erase(mhUsersCache.begin(), mhUsersCache.end());

	mUsersCacheValid	= false;
}

//
// GetUserFromCache
//
// *** NOTE ***
// Does NOT fetch the user right now if they're not there
// *** NOTE ***
clsUser *clsUsers::GetUserFromCache(UserId id)
{
	clsUser	*pUser	= NULL;

	pUser	= FindUserInCache(id);

	if (pUser != NULL)
		return pUser;

	pUser	= GetUserIntoCache(id);

	return	pUser;
}

// 
// FindUserInCache
//
clsUser *clsUsers::FindUserInCache(UserId id)
{
	// An haserator for the user cache
	hash_map<const int, clsUsersCacheHash *, hash<int>, eqint>::
		const_iterator				ihUsers;

	// Let's see if we can find them
	ihUsers	= mhUsersCache.find(id);

	// If the user's not in the cache, then put them
	// there
	if (ihUsers == mhUsersCache.end())
		return NULL;
	else
	{
		if ((*ihUsers).second->mValid)
			return (*ihUsers).second->mpUser;
		else
			return NULL;
	}
}

// Top Sellers
void clsUsers::GetTopSellers(int level, vector<int> *pvIds)
{
	gApp->GetDatabase()->GetTopSellers(mpMarketPlace->GetId(), level,
										pvIds);
}

// 
// GetUserIntoCache
//
//	Little internal routine to get a user into the cache
//
clsUser *clsUsers::GetUserIntoCache(UserId id)
{
	bool					gotIt				= false;
	list <UserId>			lUsersIdsRequested;
	list <UserId>			lMissingUserIds;

	list<UserId>::iterator	i;

	lUsersIdsRequested.push_back(id);

	BuildCache(&lUsersIdsRequested,
			   &lMissingUserIds);

	if (lMissingUserIds.size() != 0)
	{
		gotIt	= true;
		mUsersCacheValid	= true;
	}

	// Destructors will clean up lUsersIdsRequested and lMissingUserIds
	return	FindUserInCache(id);
}


//
// Get All Users regardless of everything
//
void clsUsers::GetAllUsers(vector<unsigned int> *pvIds, int minId, int maxId)
{
	gApp->GetDatabase()->GetAllUsers(mpMarketPlace->GetId(), pvIds, minId, maxId);
}

//
// Get unconfirmed users...
//
void clsUsers::GetUnconfirmedUsers(vector<int> &vIds, int days)
{
	gApp->GetDatabase()->GetUnconfirmedUsers(mpMarketPlace->GetId(), vIds, days);
}
