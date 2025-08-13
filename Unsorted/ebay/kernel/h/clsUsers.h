/*	$Id: clsUsers.h,v 1.9.64.1.98.1 1999/08/06 02:26:59 nsacco Exp $	*/
//
//	File:		clsUsers.h
//
// Class:	clsUsers
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Represents a collection of users
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 06/20/97 tini		- added functions to handle renaming users.
//				- 12/15/97 michael	- added GetUserByEmail, ChangeUserId
//				- 08/05/99 nsacco	- removed clseBayFeedbackLeadersWidget.h
//
#ifndef CLSUSERS_INCLUDED
#define CLSUSERS_INCLUDED 1

#include "eBayTypes.h"

#include <vector.h>
#include <list.h>
#include "hash_map.h"
#include "clsUserCodes.h"
#include "clsUser.h"
// nsacco 08/05/99 removed
//#include "clseBayFeedbackLeadersWidget.h"

// Class forward
class clsMarketPlace;
class clsUserCodes;
class ostream;
class clsAccount;
class clsUserValidation;

//
// Nice Enum
//
// An Enum
//
typedef enum
{
	UserSearchByUserIdSubstring		= 1,
	UserSearchByNameSubstring		= 2,
	UserSearchByAddressSubstring	= 3,
	UserSearchByAllSubstring		= 4,
	UserSearchByUserIdExact			= 5,
	UserSearchByCitySubstring       = 6,
	UserSearchByStateSubstring      = 7,
	UserSearchByAccountId			= 8
} UserSearchTypeEnum;

//
// I wonder what this is?
//
typedef struct
{
        int id;
        clsAccount *pAccount;
} sUserAccount;

//
// Or, this?
//
typedef vector<sUserAccount *> AccountsVector;


//
// clsUsersCacheHash
//
//	An object to represent a user, keyed by id, and with a 
//	pointer to the user object. Don't be scared! This is
//	used exclusivly by the CacheUsers() method
//
class clsUsersCacheHash
{
	public:
		unsigned int	mId;				// Paranoia
		bool			mValid;				// Are we valid?
		clsUser			*mpUser;			// Pointer to user object


		clsUsersCacheHash()
		{
			mId		= 0;
			mValid	= false;
			mpUser	= NULL;
		}

		clsUsersCacheHash(unsigned int id)
		{
			mId		= id;
			mValid	= false;
			mpUser	= NULL;
		}

		~clsUsersCacheHash()
		{
			delete	mpUser;
			mpUser	= NULL;
			return;
		}

};


class clsUsers
{
	public:
		clsUsers(clsMarketPlace *pMarketPlace);
		~clsUsers();

		//
		// GetUser retrieves a user by userid or id
		//
		clsUser *GetUser(char *pUserid,
						 bool needFeedback = false,
						 bool needAccount = false);
		clsUser *GetUser(int id,
						 bool needFeedback = false,
						 bool needAccount = false);
		clsUser *GetUserByEmail(char *pEmail,
								bool needFeedback = false,
								bool needAccount = false);

		//
		// AddUser adds a User
		//
		void AddUser(clsUser *pUser);

		//
		// UpdateUser
		// 
		void UpdateUser(clsUser *pUser);

		//
		// DeleteUser deletes a user
		//
		void DeleteUserLists(clsUser *pUser);
		void DeleteUserInfo(clsUser *pUser);
		void DeleteUser(clsUser *pUser);

		//
		// DeleteAllUser removes ALL users
		//
		void DeleteAllUsers();

		//
		// RenameUser renames a user
		//
		void RenameUser(clsUser *pOldUser,
						clsUser *pNewUser,
						char	*pOrigOldUserId,
						char	*pOrigNewUserId);

		//
		// GetAndCheckUser
		//	A common routine, which can emit a common error
		//	message, to get a clsUser object.
		//
		//	*** NOTE ***
		//	Tries to get the user as if the pass pUserId is 
		//	the userid, and THEN as if it's the email address.
		//	*** NOTE ***
		//
		clsUser	*GetAndCheckUser(char *pUserId,
								 ostream *pStream,
								 bool headerSent = true);

		//
		// GetAndCheckUserAndPassword
		//
		//
		//	*** NOTE ***
		//	Tries to get the user as if the pass pUserId is 
		//	the userid, and THEN as if it's the email address.
		//	*** NOTE ***
		//
		clsUser *GetAndCheckUserAndPassword(char *pUserId,
											char *pPassword,
											ostream *pStream,
											bool headerSent = true,
											char *pAction = NULL,
											bool ghostOk = false,
											bool needFeedback = false,
											bool needAccount = false,
											bool checkEncryptedPassword = false,
											bool adminCheck = false);


		//
		// GetUsersBySubstring
		//
		void GetUsersBySubstring(UserSearchTypeEnum how,
								 char *pString, 
								 vector<clsUser *> *pvUsers);

		//
		// GetUsersByFeedback
		//
		//	Get all users with a feedback >= minFeedback
		//
		void GetUserIdsByFeedback(int minFeedback,
							vector<int> *pvUsers);

		void GetUserIdsAndFeedbackByFeedback(
						    int minFeedback,
							vector<clsUserPtr> *pvUsers);


		//
		// GetIdByAlias
		//
		void GetIdByAlias(char *alias,
						  UserIdAliasHistoryVector *pvUsers);
		//
		// GetUsersWithAccounts
		//
		//	Returns a vector of ids corresponding to users who
		//	have accounts.
		//
		void GetUsersWithAccounts(vector<unsigned int> *pvIds);

        //
        // Get All User Accounts
        //
        void GetUserAccounts(AccountsVector *pvAccounts);

		// Get all users with bad account detail records
        void GetUsersWithBadAccounts(vector<unsigned int> *pvIds);

		//
		// GetManyUsersForCreditBatch
		//
		//	Tweaked.
		//
		void GetManyUsersForCreditBatch(list<unsigned int> *pUserIdList,
										UserList *pUsers);

		clsUserCodes	*GetUserCodes();

		// check whether an email is an anonymous email
		bool IsAnonymousEmail(char* pEmail);

		clsUserValidation *GetUserValidation();

		// check whether user participated the survey
		bool IsParticipatedSurvey(int survey_id, int userid);


		//GetActiveUsers
		void GetActiveUsers(vector<int> *pvIds);

		//
		// Users Cache
		//
		// The following methods are used when you're fetching a 
		// bunch of data for what could be a smaller set of users
		// (as in board posts, bidder lists, etc). 
		//
		// Right now, you have to explicitly tell clsUsers to look
		// in the cache (to avoid impacting existing code with my
		// bugs ;-) ). Later, we should think about making this 
		// more automatic, including have GetUser automatcially
		// cache the users. 
		//

		//
		// StartCaching
		//
		// Sets a flag to tell GetUser to look in the cache, and
		// to put users in the cache. 
		//
		// *** NOT USED BY ANYONE YET ***
		//
		void StartCaching();

		//
		// StopCaching
		//
		// The opposite of StartCaching. Leaves the cache intact.
		//
		// *** NOT USED BY ANYONE YET ***
		//
		void StopCaching();

		//
		// BuildCache
		//
		// Pass a vector of UserIds (unsigned ints) to this, and it
		// will fetch all the users and their feedback. Don't worry
		// about passing duplicate ids, they'll be caught.
		//
		// Returns "true' if all ids were found, and "false" if not.
		//
		// Turns on the "caching" bit.
		//
		bool BuildCache(list <UserId> *plUsers,
						list <UserId> *plMissingUsers);

		//
		// ClearCache
		// 
		// Duh. Turns off the "caching" bit, too.
		//
		void ClearCache();

		//
		// GetUserFromCache
		//
		// Peeks in the cache to see if the user is there,
		// and returns it from there if so. Otherwise, just
		// gets it and sticks it in the cache.
		//
		clsUser *GetUserFromCache(UserId id);

		// Top Seller
		void GetTopSellers(int level, vector<int> *pvIds);

		//
		// GetUserIntoCache
		//
		//	Used to get one user into the cache. Returns true if
		//	found, false it not.
		//
		clsUser *GetUserIntoCache(UserId id);

		// Get All Users
		void GetAllUsers(vector<unsigned int> *pvIds, int minId = 0, int maxId = 0);

		// Get unconfirmed users...
		void GetUnconfirmedUsers(vector<int> &pvIds, int age);


private:

		//
		// FindUserInCache
		//
		// Internal method to see if a user's in the cache.
		//
		clsUser *FindUserInCache(UserId id);

		clsMarketPlace	*mpMarketPlace;		
		clsUserCodes	*mpUserCodes;
		clsUserValidation *mpUserValidation;

		// This is the hash map of the user cache. It's only 
		// used by the User cache methods.
		hash_map<const int, clsUsersCacheHash *, hash<int>, eqint>
			mhUsersCache;

		// Indicates whether or not the user cache is filled
		bool			mUsersCacheValid;

		// Indicates whether caching is being used
		bool			mCachingUsers;

};

#endif /* CLSUSERS_INCLUDED */

