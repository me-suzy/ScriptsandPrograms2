/*	$Id: clsDatabaseOracleUsers.cpp,v 1.15.2.4.26.2 1999/08/03 05:39:33 nsacco Exp $	*/
//
//	File:	clsDatabaseOracleUsers.cc
//
//	Class:	clsDatabaseOracleUsers
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//  All user related database access for Oracle
//
// Modifications:
//				- 06/09/97 michael	- Extracted from clsDatabaseOracle.cpp
//				- 06/13/97 tini     - replaced boolean to int conversion to be
//					consistent with the other modules.
//				- 06/17/97 tini		- modified LastModified to system date
//									  instead of using the date from the object
//									  AddManyUsers are unchanged.
//				- 06/20/97 tini		- changed getUserByUserId to include getting
//									  id from ebay_renamed_users.
//									- added functions to handle renaming users.
//				- 08/03/98 inna		- added GetUserIdsUnsplit method used by 
//									table split program
//				- 12/04/98 wen		- Added missing parameters for new clsFeedback
//				- 07/02/99 nsacco	- added siteId to clsUser
//				- 07/06/99 nsacco	- Added a brand partner id to clsUser


#include "eBayKernel.h"

// **********
// Users
// **********

// 
// GetUserById
//	Given an id, make a user object (from ebay_users table only). 
//
// *****
// NOTE - Assumes that user ids (not userids) are UNIQUE
//		  in a MarketPlace
// *****  
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserById =
  "select	marketplace,					\
			userid,							\
			email,							\
			user_state,						\
			password,						\
			salt,							\
			TO_CHAR(last_modified,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
			flags,							\
			country_id,						\
			NVL(uvrating, -99999),			\
			NVL(uvdetail, 0),				\
			siteid,							\
			co_partnerid					\
		from ebay_users						\
	where	id = :id";	

clsUser *clsDatabaseOracle::GetUserById(int id)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		marketPlace;
	char	userid[128];
	char	email[128];
	int		user_state;
	char	password[128];
	char	salt[128];
	char	last_modified[32];
	time_t	last_modified_time;
	int		user_flags;
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	sb2		last_change_ind;
	sb2     country_id_ind;
	int     country_id;
	int		uvrating;
	int		uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	clsUser	*pUser;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserById, SQL_GetUserById);

	// Ok, let's do some binds
	Bind(":id", &id);

	// And zee defines
	Define(1, &marketPlace);
	Define(2, userid, sizeof(userid));
	Define(3, email, sizeof (email));
	Define(4, &user_state);
	Define(5, password, sizeof(password));
	Define(6, salt, sizeof(salt));
	Define(7, last_modified, sizeof(last_modified));
	Define(8, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(9, &user_flags);
	Define(10, &country_id, &country_id_ind);
	Define(11, &uvrating);
	Define(12, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(13, &siteId);
	Define(14, &coPartnerId);

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserById);
		SetStatement(NULL);
		return NULL;
	}

	ORACLE_DATEToTime(last_modified, &last_modified_time);

	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;

	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// Make it !
	// nsacco 07/06/99 - use fancy constructor with site and partner id
	pUser	= new clsUser(marketPlace,
						  id, 
						  userid,
						  email,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  last_modified_time,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	// Clean up 
	Close(&mpCDAGetUserById);
	SetStatement(NULL);

	return pUser;
}

// 
// GetUserAndFeedbackScoreById
//	Given an id, retrieves user info and relevant feedback
//	and accounting information. 
//
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserAndFeedbackById =
  "select	u.marketplace,					\
			u.userid,						\
			u.email,						\
			u.user_state,					\
			u.password,						\
			u.salt,							\
			TO_CHAR(u.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),	\
			u.flags,						\
			u.country_id,                   \
			NVL(u.uvrating, -99999),		\
			NVL(u.uvdetail, 0),				\
			u.siteid,						\
			u.co_partnerid,					\
			f.flags,						\
			f.score,						\
			f.split							\
	from	ebay_users u,					\
			ebay_feedback f					\
	where	u.id = :id						\
	and		u.id = f.id (+)";

void clsDatabaseOracle::GetUserAndFeedbackById(int id,
											   clsUser **ppUser,
											   clsFeedback **ppFeedback)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		marketPlace;
	char	userid[128];
	char	email[128];
	int		user_state;
	char	password[128];
	char	salt[128];
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	int		score;
	sb2		score_ind;
	sb2		last_change_ind;
	int		user_flags;
	char	split[2];
	int		fb_flags;
	int     country_id;
	sb2     country_id_ind;
	int		uvrating;
	int		uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	clsUser		*pUser;
	clsFeedback	*pFeedback;

	// In case
	*ppUser			= NULL;
	*ppFeedback		= NULL;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserAndFeedbackById, 
				 SQL_GetUserAndFeedbackById);

	// Ok, let's do some binds
	Bind(":id", &id);

	// And zee defines
	Define(1, &marketPlace);
	Define(2, userid, sizeof(userid));
	Define(3, email, sizeof (email));
	Define(4, &user_state);
	Define(5, password, sizeof(password));
	Define(6, salt, sizeof(salt));
	Define(7, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(8, &user_flags);
	Define(9, &country_id, &country_id_ind);
	Define(10, &uvrating);
	Define(11, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(12, &siteId);
	Define(13, &coPartnerId);
	Define(14, &fb_flags);
	Define(15, &score, &score_ind);
	Define(16, split, sizeof(split));
	

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserAndFeedbackById);
		SetStatement(NULL);
		return;

	}

	// Construct the basic user object

	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;
	
	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// Clean up 
	Close(&mpCDAGetUserAndFeedbackById);
	SetStatement(NULL);

	// nsacco 07/06/99 - use fancy constructor with site and partner id
	pUser	= new clsUser(marketPlace,
						  id, 
						  userid,
						  email,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  0,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	*ppUser	= pUser;

	// 
	// Construct the feedback object
	//
	if (score_ind != -1)
	{
		pFeedback	= new clsFeedback(id, true, score, fb_flags, false, 0, NULL, split[0] == '1');
		*ppFeedback	= pFeedback;
	}
	else
		*ppFeedback	= NULL;

	return;
}


// 
// GetUserAndInfoById
//	Given an id, make a user object include data from ebay_users_info. 
//
// *****
// NOTE - Assumes that user ids (not userids) are UNIQUE
//		  in a MarketPlace
// *****  
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserAndInfoById =
  "select	marketplace,						\
			userid,								\
			user_state,							\
			u.password,							\
			u.salt,								\
			TO_CHAR(last_modified,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),		\
			flags,								\
			country_id,                         \
			NVL(uvrating, -99999),				\
			NVL(uvdetail, 0),					\
			siteid,								\
			co_partnerid,						\
			host,								\
			name,								\
			company,							\
			address,							\
			city,								\
			state,								\
			zip,								\
			country,							\
			dayphone,							\
			nightphone,							\
			faxphone,							\
			TO_CHAR(creation,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			count,								\
			credit_card_on_file,				\
			good_credit,						\
			gender,								\
			interests_1	,						\
			interests_2,						\
			interests_3,						\
			interests_4,						\
			NVL(partner_id, 0),					\
			NVL(req_email_count, 0),			\
			TO_CHAR(topsellerinitiateddate,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(topsellerlevel, 0)				\
	from ebay_users u, ebay_user_info ui		\
	where	u.id = :id		        			\
		and ui.id = :id";	

clsUser *clsDatabaseOracle::GetUserAndInfoById(int id)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		marketPlace;
	char	userid[128];
	int		user_state;
	char	password[128];
	char	salt[128];
	char	last_modified[32];
	char	userid_last_change[32];
	int		user_flags;
	int     country_id;
	int		uvrating;
	int		uvdetail;
	char	host[128];
	char	name[128];
	char	company[128];
	sb2		company_ind;
	char	address[128];
	char	city[128];
	char	state[128];
	char	zip[128];
	char	country[128];
	char	dayphone[33];
	sb2		dayphone_ind;
	char	nightphone[33];
	sb2		nightphone_ind;
	char	faxphone[33];
	sb2		faxphone_ind;
	char	creation[32];
	char	email[128];
	int		count;
	char	credit_card_on_file;
	char	good_credit;
	char	gender[32];
	int		interests_1;
	int		interests_2;
	int		interests_3;
	int		interests_4;
	int		partnerId;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;
	int		reqEmailCount;
	time_t	creation_time;
	time_t	last_modified_time;
	time_t	userid_last_change_time;
	time_t	topsellerinitiateddate_time;
	bool	bccof;
	bool	bgc;
	sb2		last_change_ind;
	sb2     country_id_ind;
	sb2		topSellerInitiatedDate_ind;
	char	topSellerInitiatedDate[32];
	int		topSellerLevel;

	char	*pDayPhone;
	char	*pNightPhone;
	char	*pFaxPhone;
	char	*pCompany;

	clsUser	*pUser;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserAndInfoById, SQL_GetUserAndInfoById);

	// Ok, let's do some binds
	Bind(":id", &id);

	// And zee defines
	Define(1, &marketPlace);
	Define(2, userid, sizeof(userid));
	Define(3, &user_state);
	Define(4, password, sizeof(password));
	Define(5, salt, sizeof(salt));
	Define(6, last_modified, sizeof(last_modified));
	Define(7, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(8, &user_flags);
	Define(9, &country_id, &country_id_ind);
	Define(10, &uvrating);
	Define(11, &uvdetail);
	Define(12, host, sizeof(host));
	Define(13, name, sizeof(name));
	Define(14, company, sizeof(company), &company_ind);
	Define(15, address, sizeof(address));
	Define(16, city, sizeof(city));
	Define(17, state, sizeof(state));
	Define(18, zip, sizeof(zip));
	Define(19, country, sizeof(country));
	Define(20, dayphone, sizeof(dayphone), &dayphone_ind);
	Define(21, nightphone, sizeof(nightphone), &nightphone_ind);
	Define(22, faxphone, sizeof(faxphone), &faxphone_ind);
	Define(23, creation, sizeof(creation));
	Define(24, email, sizeof(email));
	Define(25, &count);
	Define(26, &credit_card_on_file,1);
	Define(27, &good_credit,1);
	Define(28, gender, sizeof(gender));
	Define(29, &interests_1);
	Define(30, &interests_2);
	Define(31, &interests_3);
	Define(32, &interests_4);
	Define(33, &partnerId);
	Define(34, &siteId);	// nsacco 07/02/99
	Define(35, &coPartnerId);	// nsacco 07/06/99
	Define(36, &reqEmailCount);
	Define(37, topSellerInitiatedDate, sizeof(topSellerInitiatedDate), &topSellerInitiatedDate_ind);
	Define(38, &topSellerLevel);

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserAndInfoById);
		SetStatement(NULL);
		return NULL;
	}

	// Handle nulls
	if (dayphone_ind == -1)
		pDayPhone	= NULL;
	else
		pDayPhone	= dayphone;

	if (nightphone_ind == -1)
		pNightPhone	= NULL;
	else
		pNightPhone	= dayphone;

	if (faxphone_ind == -1)
		pFaxPhone	= NULL;
	else
		pFaxPhone	= dayphone;

	if (company_ind == -1)
		pCompany	= NULL;
	else
		pCompany	= company;

	// set boolean values for credit info
	if (credit_card_on_file == '1')
		bccof	= true;
	else
		bccof	= false;

	if (good_credit == '1')
		bgc	= true;
	else
		bgc	= false;

	// Make it !
	ORACLE_DATEToTime(creation, &creation_time);
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;
	
	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	if (topSellerInitiatedDate_ind != -1)
		ORACLE_DATEToTime(topSellerInitiatedDate, &topsellerinitiateddate_time);
	else
		topsellerinitiateddate_time = 0;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	pUser	= new clsUser(marketPlace,
						  id, 
						  userid,
						  (UserStateEnum)user_state,
						  password,
						  salt, 
						  last_modified_time,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,						// UVRating
						  uvdetail,						// UVDetail
						  host,
						  name,
						  pCompany,
						  address,
						  city,
						  state,
						  zip,
						  country,
						  pDayPhone,
						  pNightPhone,
						  pFaxPhone,
						  creation_time,
						  email,
						  count,
						  bccof,
						  bgc,
						  gender,
						  interests_1,
						  interests_2,
						  interests_3,
						  interests_4,
						  partnerId,
						  siteId,	// nsacco 07/02/99
						  coPartnerId,	// nsacco 07/06/99
						  reqEmailCount,
						  topsellerinitiateddate_time,
						  topSellerLevel);

	// Clean up 
	Close(&mpCDAGetUserAndInfoById);
	SetStatement(NULL);

	return pUser;
}


static const char *SQL_GetUserInfo =
  "select	host,								\
			name,								\
			company,							\
			address,							\
			city,								\
			state,								\
			zip,								\
			country,							\
			dayphone,							\
			nightphone,							\
			faxphone,							\
			TO_CHAR(creation,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			count,								\
			credit_card_on_file,				\
			good_credit,						\
			gender,								\
			interests_1	,						\
			interests_2,						\
			interests_3,						\
			interests_4,						\
			NVL(partner_id, 0),					\
			NVL(req_email_count, 0),			\
			TO_CHAR(topsellerinitiateddate,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(topsellerlevel, 0)				\
	from ebay_user_info							\
	where	id = :id";	

clsUser *clsDatabaseOracle::GetUserInfo(clsUser *pUser)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		id;
	char	host[128];
	char	name[128];
	char	company[128];
	sb2		company_ind;
	char	address[128];
	char	city[128];
	char	state[128];
	char	zip[128];
	char	country[128];
	char	dayphone[33];
	sb2		dayphone_ind;
	char	nightphone[33];
	sb2		nightphone_ind;
	char	faxphone[33];
	sb2		faxphone_ind;
	char	creation[32];
	int		count;
	char	credit_card_on_file[2];
	char	good_credit[2];
	char	gender[32];
	int		interests_1;
	int		interests_2;
	int		interests_3;
	int		interests_4;
	int		partnerId;
	int		reqEmailCount;
	bool	bccof;
	bool	bgc;
	char	topSellerInitiatedDate[32];
	int		topsellerlevel;

	char	*pDayPhone;
	char	*pNightPhone;
	char	*pFaxPhone;
	char	*pCompany;

	time_t	creation_time;
	time_t	topsellerinitiateddate_time;
	sb2		topSellerInitiatedDate_ind;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserInfo, SQL_GetUserInfo);

	id			= pUser->GetId();

	// Ok, let's do some binds
	Bind(":id", &id);

	// And zee defines
	Define(1, host, sizeof(host));
	Define(2, name, sizeof(name));
	Define(3, company, sizeof(company), &company_ind);
	Define(4, address, sizeof(address));
	Define(5, city, sizeof(city));
	Define(6, state, sizeof(state));
	Define(7, zip, sizeof(zip));
	Define(8, country, sizeof(country));
	Define(9, dayphone, sizeof(dayphone), &dayphone_ind);
	Define(10, nightphone, sizeof(nightphone), &nightphone_ind);
	Define(11, faxphone, sizeof(faxphone), &faxphone_ind);
	Define(12, creation, sizeof(creation));
	Define(13, &count);
	Define(14, (char *)credit_card_on_file, sizeof(credit_card_on_file));
	Define(15, (char *)good_credit, sizeof(good_credit));
	Define(16, gender, sizeof(gender));
	Define(17, &interests_1);
	Define(18, &interests_2);
	Define(19, &interests_3);
	Define(20, &interests_4);
	Define(21, &partnerId);
	Define(22, &reqEmailCount);
	Define(23, topSellerInitiatedDate, sizeof(topSellerInitiatedDate), &topSellerInitiatedDate_ind);
	Define(24, &topsellerlevel);

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserInfo);
		SetStatement(NULL);
		return NULL;
	}

	// Handle nulls
	if (dayphone_ind == -1)
		pDayPhone	= NULL;
	else
		pDayPhone	= dayphone;

	if (nightphone_ind == -1)
		pNightPhone	= NULL;
	else
		pNightPhone	= nightphone;

	if (faxphone_ind == -1)
		pFaxPhone	= NULL;
	else
		pFaxPhone	= faxphone;

	if (company_ind == -1)
		pCompany = NULL;
	else
		pCompany = company;

	// set boolean values for credit info
	if (credit_card_on_file[0] == '1')
		bccof	= true;
	else
		bccof	= false;

	if (good_credit[0] == '1')
		bgc	= true;
	else
		bgc	= false;

	// put it into clsUser!
	ORACLE_DATEToTime(creation, &creation_time);

	if (topSellerInitiatedDate_ind == -1)
		topsellerinitiateddate_time = 0;
	else
		ORACLE_DATEToTime(topSellerInitiatedDate, &topsellerinitiateddate_time);

	pUser->AddUserInfo(	host,
						name,
						pCompany,
						address,
						city,
						state,
						zip,
						country,
						pDayPhone,
						pNightPhone,
						pFaxPhone,
						creation_time,
						count,
						bccof,
						bgc,
						gender,
						interests_1,
						interests_2,
						interests_3,
						interests_4,
						partnerId,
						reqEmailCount,
						topsellerinitiateddate_time,
						topsellerlevel);

	// Clean up 
	Close(&mpCDAGetUserInfo);
	SetStatement(NULL);

	return pUser;
}

static const char *SQL_UpdateUserCreation =
 "update ebay_user_info								\
	set	creation =									\
		TO_DATE(:newtime, 'YYYY-MM-DD HH24:MI:SS')	\
	where id = :id";

void clsDatabaseOracle::UpdateUserCreation(clsUser *pUser)
{
	int				id;
	char			cUserCreated[32];

	id			= pUser->GetId();

	TimeToORACLE_DATE(pUser->GetCreated(),
					  cUserCreated);

	OpenAndParse(&mpCDAOneShot, SQL_UpdateUserCreation);

	Bind(":id", &id);
	Bind(":newtime", cUserCreated);

	// Let's do it!
	Execute();

	// Test for now rows processed here!

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


static const char *SQL_HasUserInfoId =
  "select	count(id)					\
	from ebay_user_info					\
	where	id = :id";	

// checks if a user already has an info record
bool clsDatabaseOracle::HasUserInfo(clsUser *pUser)
{
	int		id;
	int		count;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_HasUserInfoId);

	id			= pUser->GetId();

	// Ok, let's do some binds
	Bind(":id", &id);

	// And zee defines
	Define(1, &count);

	// Do it
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return (count == 0);

}

// 
// GetUserByUserId
//	Given a userid, make a user object (does not get UserInfo)
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserByUserId =
  "select	id,								\
			email,							\
			user_state,						\
			password,						\
			salt,							\
			TO_CHAR(last_modified,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
			flags,							\
			country_id,                     \
			NVL(uvrating, -99999),			\
			NVL(uvdetail, 0),	            \
			siteid,							\
			co_partnerid					\
	from ebay_users							\
	where	userid = :id					\
	and		marketplace = :marketplace";	

static const char *SQL_GetIdForPriorAlias =
  "select	id												\
   from ebay_user_past_aliases								\
   where	marketplace = :marketplace						\
   and		alias =  :alias									\
   and		aliasflag	= 0									\
   and		modified >=										\
		TO_DATE(:aliaslimit, 'YYYY-MM-DD HH24:MI:SS')		\
   and		rownum = 1";

clsUser *clsDatabaseOracle::GetUserByUserId(int marketPlace,
											char *pUserId)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		id;
	int		user_state;
	char	email[128];
	char	password[128];
	char	salt[128];
	char	last_modified[32];
	time_t	last_modified_time;
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	time_t	nowTime;
	time_t	aliasLimitTime;
	char	cAliasLimitTime[32];
	int		aliasId;
	sb2		last_change_ind;
	int		user_flags;
	int     country_id;
	sb2     country_id_ind;
	int		uvrating;
	int		uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	clsUser	*pUser;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserByUserId,
				 SQL_GetUserByUserId);

	// Ok, let's do some binds
	Bind(":marketplace", &marketPlace);
	Bind(":id", pUserId);

	// And zee defines
	Define(1, &id);
	Define(2, email, sizeof (email));
	Define(3, &user_state);
	Define(4, password, sizeof(password));
	Define(5, salt, sizeof(salt));
	Define(6, last_modified, sizeof(last_modified));
	Define(7, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(8, &user_flags);
	Define(9, &country_id, &country_id_ind);
	Define(10, &uvrating);
	Define(11, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(12, &siteId);
	Define(13, &coPartnerId);


	// Do it
	ExecuteAndFetch();

	//
	// If THAT didn't work, let's see if the user's used
	// a prior alias recently. 
	//
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserByUserId);
		SetStatement(NULL);

		nowTime			= time(0);
		aliasLimitTime	= nowTime - 
			(EBAY_USERID_EMBARGO_OLD_USERID_DAYS * 60 * 60 * 24);
		TimeToORACLE_DATE(aliasLimitTime, cAliasLimitTime);

		OpenAndParse(&mpCDAGetIdForPriorAlias,
					 SQL_GetIdForPriorAlias);

		// binds
		Bind(":marketplace", &marketPlace);
		Bind(":alias", pUserId);
		Bind(":aliaslimit", cAliasLimitTime);

		// defines
		Define(1, &aliasId);

		// Get it
		ExecuteAndFetch();
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAGetIdForPriorAlias);
			SetStatement(NULL);
			return NULL;
		}

		Close(&mpCDAGetIdForPriorAlias);
		SetStatement(NULL);

		// return value
		return GetUserById(aliasId);
	}

	// Make it !
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	
	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;

	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// nsacco 07/06/99 - create with site and partner
	pUser = new clsUser(marketPlace,
						  id, 
						  pUserId,
						  email,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  last_modified_time,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	// Clean up 
	Close(&mpCDAGetUserByUserId);
	SetStatement(NULL);

	return pUser;
}

//
//	GetUserByEmail
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserByEmail =
  "select	u.id,							\
			u.userid,						\
			u.email,						\
			u.user_state,					\
			u.password,						\
			u.salt,							\
			TO_CHAR(u.last_modified,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,	\
				'YYYY-MM-DD HH24:MI:SS'),	\
			u.flags,						\
			u.country_id,					\
			NVL(u.uvrating, -99999),		\
			NVL(u.uvdetail, 0),				\
			siteid,							\
			co_partnerid					\
	from  ebay_users u						\
	where	u.email = :email				\
	and		u.marketplace = :marketplace";

static const char *SQL_GetIdForPriorEmail =
  "select	id												\
   from ebay_user_past_aliases								\
   where	marketplace = :marketplace						\
   and		alias =  :id									\
   and		aliasflag	= 1									\
   and		modified >=										\
		TO_DATE(:aliaslimit, 'YYYY-MM-DD HH24:MI:SS')		\
   and		rownum = 1";


clsUser *clsDatabaseOracle::GetUserByEmail(int marketPlace,
											char *pEmail)
{
	// Intermediate places to store things.
	// **** NOTE ****
	// Right now, I don't know of any limits
	// on the sizes of these things, so I'm 
	// making them these huge values. We need
	// to figure this out ;-)
	// **** NOTE ****
	int		id;
	char	userid[EBAY_MAX_USERID_SIZE+1];
	char	email[EBAY_MAX_USERID_SIZE + 1];
	int		user_state;
	char	password[EBAY_MAX_EMAIL_SIZE+1];
	char	salt[128];
	char	last_modified[32];
	time_t	last_modified_time;
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	time_t	nowTime;
	time_t	aliasLimitTime;
	char	cAliasLimitTime[32];
	int		aliasId;
	sb2		last_change_ind;
	int		user_flags;
	int     country_id;
	sb2     country_id_ind;
	int     uvrating;
	int     uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	clsUser	*pUser;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserByEmail,
				 SQL_GetUserByEmail);

	// Ok, let's do some binds
	Bind(":marketplace", &marketPlace);
	Bind(":email", pEmail);

	// And zee defines
	Define(1, &id);
	Define(2, userid, sizeof(userid));
	Define(3, email, sizeof (email));
	Define(4, &user_state);
	Define(5, password, sizeof(password));
	Define(6, salt, sizeof(salt));
	Define(7, last_modified, sizeof(last_modified));
	Define(8, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(9, &user_flags);
	Define(10, &country_id, &country_id_ind);
	Define(11, &uvrating);
	Define(12, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(13, &siteId);
	Define(14, &coPartnerId);

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserByEmail);
		SetStatement(NULL);

		nowTime			= time(0);
		aliasLimitTime	= nowTime - 
			(EBAY_USERID_EMBARGO_OLD_EMAIL_DAYS * 60 * 60 * 24);
		TimeToORACLE_DATE(aliasLimitTime, cAliasLimitTime);

		OpenAndParse(&mpCDAGetIdForPriorEmail,
					 SQL_GetIdForPriorEmail);
		// binds
		Bind(":marketplace", &marketPlace);
		Bind(":id", pEmail);
		Bind(":aliaslimit", cAliasLimitTime);

		// defines
		Define(1, &aliasId);

		// Get it
		ExecuteAndFetch();
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAGetIdForPriorEmail);
			SetStatement(NULL);
			return NULL;
		}

		Close(&mpCDAGetIdForPriorEmail);
		SetStatement(NULL);

		// return value
		return GetUserById(aliasId);
	}

	// Make it !
	ORACLE_DATEToTime(last_modified, &last_modified_time);
	
	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;

	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// nsacco 07/06/99 - create with site and partner
	pUser = new clsUser(marketPlace,
						  id, 
						  userid,
						  email,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  last_modified_time,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	// Clean up 
	Close(&mpCDAGetUserByEmail);
	SetStatement(NULL);

	return pUser;
}

// 
// GetUserAndFeedbackByUserId
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserAndFeedbackByUserId =
  "select	u.id,								\
			u.email,							\
			u.user_state,						\
			u.password,							\
			u.salt,								\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),            \
			NVL(u.uvdetail, 0),                 \
			u.siteid,							\
			u.co_partnerid,						\
			f.flags,							\
			f.score,							\
			f.split								\
	from	ebay_users u,						\
			ebay_feedback f						\
	where	u.userid = :id						\
	and		u.marketplace = :marketplace		\
	and		u.id = f.id (+)";	


void clsDatabaseOracle::GetUserAndFeedbackByUserId(int marketPlace,
												   char *pUserId,
												   clsUser **ppUser,
												   clsFeedback **ppFeedback)
{
	int		id;
	int		user_state;
	char	email[128];
	char	password[128];
	char	salt[128];
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	int		user_flags;
	int     country_id;
	sb2     country_id_ind;
	int		uvrating;
	int		uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	time_t	nowTime;
	time_t	aliasLimitTime;
	char	cAliasLimitTime[32];
	int		aliasId;

	int		fb_flags;

	int		score;
	sb2		score_ind;
	sb2		last_change_ind;
	char	split[2];


	clsUser		*pUser;
	clsFeedback	*pFeedback;

	// Clean
	*ppUser		= NULL;
	*ppFeedback	= NULL;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserAndFeedbackByUserId,
				 SQL_GetUserAndFeedbackByUserId);

	// Ok, let's do some binds
	Bind(":marketplace", &marketPlace);
	Bind(":id", pUserId);

	// And zee defines
	Define(1, &id);
	Define(2, email, sizeof (email));
	Define(3, &user_state);
	Define(4, password, sizeof(password));
	Define(5, salt, sizeof(salt));
	Define(6, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(7, &user_flags);
	Define(8, &country_id, &country_id_ind);
	Define(9, &uvrating);
	Define(10, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(11, &siteId);
	Define(12, &coPartnerId);
	Define(13, &fb_flags);	
	Define(14, &score, &score_ind);
	Define(15, split, sizeof(split));

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserAndFeedbackByUserId);
		SetStatement(NULL);

		nowTime			= time(0);
		aliasLimitTime	= nowTime - 
			(EBAY_USERID_EMBARGO_OLD_USERID_DAYS * 60 * 60 * 24);
		TimeToORACLE_DATE(aliasLimitTime, cAliasLimitTime);

		OpenAndParse(&mpCDAGetIdForPriorAlias,
					 SQL_GetIdForPriorAlias);

		// binds
		Bind(":marketplace", &marketPlace);
		Bind(":alias", pUserId);
		Bind(":aliaslimit", cAliasLimitTime);

		// defines
		Define(1, &aliasId);

		// Get it
		ExecuteAndFetch();
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAGetIdForPriorAlias);
			SetStatement(NULL);
			*ppUser		= NULL;
			*ppFeedback	= NULL;
			return;
		}

		Close(&mpCDAGetIdForPriorAlias);
		SetStatement(NULL);

		// return value
		GetUserAndFeedbackById(aliasId,
							   ppUser, ppFeedback);

		return;
	}

	// Make it !
	
	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;

	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// nsacco 07/06/99 create with site and partner
	pUser = new clsUser(marketPlace,
						  id, 
						  pUserId,
						  email,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  0,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	*ppUser	= pUser;

	if (score_ind != -1)
	{
		pFeedback	= new clsFeedback(id, true, score, fb_flags, false, 0, NULL, split[0]=='1');
		*ppFeedback	= pFeedback;
	}
	else
		*ppFeedback	= NULL;

	// Clean up 
	Close(&mpCDAGetUserAndFeedbackByUserId);
	SetStatement(NULL);

	return;
}

//
//	GetUserAndFeedbackByEmail
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserAndFeedbackByEmail =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			u.password,							\
			u.salt,								\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,						\
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			f.flags,							\
			f.score,							\
			f.split								\
	from	ebay_users u,						\
			ebay_feedback f						\
	where	u.email = :email					\
	and		u.marketplace = :marketplace		\
	and		u.id = f.id (+)";	

void clsDatabaseOracle::GetUserAndFeedbackByEmail(int marketPlace,
												  char *pEmail,
												  clsUser **ppUser,
												  clsFeedback **ppFeedback)
{
	int		id;
	char	userId[64];
	int		user_state;
	char	password[128];
	char	salt[128];
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	int		user_flags;
	int     country_id;
	sb2     country_id_ind;
	int     uvrating;
	int     uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	time_t	nowTime;
	time_t	aliasLimitTime;
	char	cAliasLimitTime[32];
	int		aliasId;
	int		fb_flags;

	int		score;
	sb2		score_ind;
	sb2		last_change_ind;
	char	split[2];


	clsUser		*pUser;
	clsFeedback	*pFeedback;

	// Clean
	*ppUser		= NULL;
	*ppFeedback	= NULL;

	// The usual suspects
	OpenAndParse(&mpCDAGetUserAndFeedbackByEmail,
				 SQL_GetUserAndFeedbackByEmail);

	// Ok, let's do some binds
	Bind(":marketplace", &marketPlace);
	Bind(":email", pEmail);

	// And zee defines
	Define(1, &id);
	Define(2, userId, sizeof(userId));
	Define(3, &user_state);
	Define(4, password, sizeof(password));
	Define(5, salt, sizeof(salt));
	Define(6, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(7, &user_flags);
	Define(8, &country_id, &country_id_ind);	
	Define(9, &uvrating);
	Define(10, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(11, &siteId);
	Define(12, &coPartnerId);
	Define(13, &fb_flags);
	Define(14, &score, &score_ind);
	Define(15, split, sizeof(split));

	// Do it
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetUserAndFeedbackByEmail);
		SetStatement(NULL);

		nowTime			= time(0);
		aliasLimitTime	= nowTime - 
			(EBAY_USERID_EMBARGO_OLD_EMAIL_DAYS * 60 * 60 * 24);
		TimeToORACLE_DATE(aliasLimitTime, cAliasLimitTime);

		OpenAndParse(&mpCDAGetIdForPriorEmail,
					 SQL_GetIdForPriorEmail);
		// binds
		Bind(":marketplace", &marketPlace);
		Bind(":id", pEmail);
		Bind(":aliaslimit", cAliasLimitTime);

		// defines
		Define(1, &aliasId);

		// Get it
		ExecuteAndFetch();
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAGetIdForPriorEmail);
			SetStatement(NULL);
			*ppUser		= NULL;
			*ppFeedback	= NULL;
			return;
		}

		Close(&mpCDAGetIdForPriorEmail);
		SetStatement(NULL);

		// return value
		GetUserAndFeedbackById(aliasId,
							   ppUser, ppFeedback);

		return;
	}

	// Make it !
	if (last_change_ind != -1)
		ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
	else
		userid_last_change_time = 0;
	
	if (country_id_ind == -1)
		country_id = clsCountries::COUNTRY_NONE;

	// nsacco 07/06/99
	if (siteId == -1)
	{
		siteId = SITE_EBAY_MAIN;
	}

	if (coPartnerId == -1)
	{
		coPartnerId = PARTNER_EBAY;
	}

	// nsacco 07/06/99 added site and partner
	pUser = new clsUser(marketPlace,
						  id, 
						  userId,
						  pEmail,
						  (UserStateEnum)user_state,
						  password,
						  salt,
						  0,
						  userid_last_change_time,
						  user_flags,
						  country_id,
						  uvrating,
						  uvdetail,
						  siteId,
						  coPartnerId);

	*ppUser	= pUser;

	if (score_ind != -1)
	{
		pFeedback	= new clsFeedback(id, true, score, 0, false, 0, NULL, split[0] == '1');
		*ppFeedback	= pFeedback;
	}
	else
		*ppFeedback	= NULL;

	// Clean up 
	Close(&mpCDAGetUserAndFeedbackByEmail);
	SetStatement(NULL);

	return;
}




static const char *SQL_GetUserIdsByFeedback =
"select id from ebay_feedback where score >= :score";


void clsDatabaseOracle::GetUserIdsByFeedback(int minVal,
											 vector<int> *pvUsers)
{
	int			id;
	
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUserIdsByFeedback);

	Bind(":score", &minVal);

	Define(1, &id);

	Execute();

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		pvUsers->push_back(id);
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// Grabs all users above a certain feedback threshhold and 
// orders them in descending order. Passes back a vector
// containing user objects referencing feedback objects.
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUserIdsAndFeedbackByFeedback =
  "select	u.marketplace,						\
			u.id,								\
			u.userid,							\
			u.email,							\
			u.user_state,						\
			u.userid_last_change,				\
			u.flags,							\
			u.country_id,						\
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			f.flags,							\
			f.score								\
			f.split,							\
	from 	ebay_users u, ebay_feedback f		\
	where	f.score > :score					\
	and		f.id = u.id							\
	order by score desc";


void clsDatabaseOracle::GetUserIdsAndFeedbackByFeedback(
										int minVal,
										vector<clsUserPtr> *pvUsers)
{
	// As commented earlier in this file, there are apparently no
	// limits on some sizes here, so we're making them big to avoid
	// trouble.
	int		marketplace;
	int		id;
	char	userid[128];
	char	email[128];
	int		user_state;
	char	userid_last_change[32];
	time_t	userid_last_change_time;
	int		score;
	sb2		score_ind;
	sb2		last_change_ind;
	int		user_flags;
	char	split[2];
	int		fb_flags;
	int     country_id;
	sb2     country_id_ind;
	int     uvrating;
	int     uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int		siteId = SITE_EBAY_MAIN;
	int		coPartnerId = PARTNER_NONE;

	clsUser		*pUser;
	clsFeedback	*pFeedback;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUserIdsAndFeedbackByFeedback);

	// We have a minimum threshhold.
	Bind(":score", &minVal);

	// And zee defines
	Define(1, &marketplace);
	Define(2, &id);
	Define(3, userid, sizeof(userid));
	Define(4, email, sizeof (email));
	Define(5, &user_state);
	Define(6, userid_last_change, sizeof(userid_last_change), &last_change_ind);
	Define(7, &user_flags);
	Define(8, &country_id, &country_id_ind);
	Define(9, &uvrating);
	Define(10, &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(11, &siteId);
	Define(12, &coPartnerId);
	Define(13, &fb_flags);
	Define(14, &score, &score_ind);
	Define(15, split, sizeof(split));

	Execute();

	// Cycle through what we found. The cool part is that the users
	// have already been sorted by feedback ranking!
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		if ( (UserStateEnum)user_state == UserConfirmed)
		{
			// Construct the basic user object

			if (last_change_ind != -1)
				ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
			else
				userid_last_change_time = 0;

			if (country_id_ind == -1)
				country_id = clsCountries::COUNTRY_NONE;
			
			// nsacco 07/06/99
			if (siteId == -1)
			{
				siteId = SITE_EBAY_MAIN;
			}

			if (coPartnerId == -1)
			{
				coPartnerId = PARTNER_EBAY;
			}

			// nsacco 07/06/99 - create with site and partner
			pUser	= new clsUser(marketplace,
								  id, 
								  userid,
								  email,
								  (UserStateEnum)user_state,
								  (char *)"", /* pw   */
								  (char *)"", /* salt */
								  0,
								  userid_last_change_time,
								  user_flags,
								  country_id,
								  uvdetail,
								  uvrating,
								  siteId,
								  coPartnerId);

			// 
			// Construct the feedback object
			//
			if (score_ind != -1)
				pFeedback	= new clsFeedback(id, true, score, fb_flags, false, 0, NULL, split[0]=='1');
			else
				pFeedback	= NULL;

			pUser->SetFeedback(pFeedback);

			pvUsers->push_back(clsUserPtr(pUser));

		} // this is a confirmed user

	} // loop until no rows found, at which time we break.

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//inna
static const char *SQL_GetUserIdsUnsplit =
 "select id from ebay_feedback where "
 "id >= :start_id and id < :end_id and split<>'1' "
 "order by id";


void clsDatabaseOracle::GetUserIdsUnsplit(int start_id, int end_id,
											 vector<int> *pvUsers)
{
	int		id;

	
	// Open and Parse
	pvUsers->reserve(end_id - start_id);

	
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUserIdsUnsplit);

	Bind(":start_id", &start_id);
	Bind(":end_id", &end_id);

	Define(1, &id);

	Execute();
	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		pvUsers->push_back(id);
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
//end inna
//
// GetNextUserId
//
// Retrieves the next availible user id. Whether
// this is done with a sequence, or a column in
// a table is irrelevant
//
static const char *SQL_GetNextUserId =
 "select ebay_users_sequence.nextval from dual";

int clsDatabaseOracle::GetNextUserId()
{
	int			nextId;

	// Not used often, so we don't need a persistent
	// cursor
	OpenAndParse(&mpCDAOneShot, SQL_GetNextUserId);
	Define(1, &nextId);

	// Execute
	ExecuteAndFetch();

	// Close and Clean
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return nextId;
}


//
// AddUser
// Does not add user info; 
// need to explicitly add by AddUserInfo
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_AddUser =
 "insert into ebay_users					\
	(	marketplace,						\
		id,									\
		userid,								\
		email,								\
		user_state,							\
		password,							\
		salt,								\
		last_modified,						\
		userid_last_change,					\
		flags,								\
		country_id,							\
		uvrating,							\
		uvdetail,							\
		siteid,								\
		co_partnerid						\
	)										\
	values									\
	(	:marketplace,						\
		:id,								\
		:user_id,							\
		:email,								\
		:user_state,						\
		:pass,								\
		:salt,								\
		sysdate,							\
		sysdate,							\
		:flags,								\
		:country_id,						\
		:uvrating,							\
		:uvdetail,							\
		:site_id,							\
		:co_partner_id						\
	)";


void clsDatabaseOracle::AddUser(clsUser *pUser)
{
	// Things extracted from the user object
	int				marketPlace;
	int				id;
	UserStateEnum	userState;
	int				flags;
	int             country_id;
	int				uvrating;
	int				uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int				siteId;
	int				coPartnerId;

	marketPlace	= pUser->GetMarketPlace();
	id			= pUser->GetId();
	userState	= pUser->GetUserState();
	flags       = pUser->GetUserFlags();
	country_id  = pUser->GetCountryId();
	uvrating	= pUser->GetUVRating();
	uvdetail	= pUser->GetUVDetail();
	// nsacco 07/06/99 added siteid and co_partnerid
	siteId		= pUser->GetSiteId();
	coPartnerId = pUser->GetCoPartnerId();

	// Date conversions

	OpenAndParse(&mpCDAOneShot, SQL_AddUser);

	// Bind that input
	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);
	Bind(":user_id", pUser->GetUserId());
	Bind(":email", pUser->GetEmail());
	Bind(":user_state", (int *)&userState);
	Bind(":pass", pUser->GetPassword());
	Bind(":salt", pUser->GetSalt());
//	Bind(":last_modified", last_update);
	Bind(":flags", &flags);
	Bind(":country_id", &country_id);
	Bind(":uvrating", &uvrating);
	Bind(":uvdetail", &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Bind(":site_id", &siteId);
	Bind(":co_partner_id", &coPartnerId);
	// Let's do it!

	Execute();
	Commit();

	// Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;

}

//
// AddUserInfo
// doesn't make sense to update creation date
//
// NOTE: interim period: email is included for new users only
// TO BE REMOVED ONCE ALL EMAILMOVE DLL IS IN PRODUCTION.
//
static const char *SQL_AddUserInfo =
 "insert into ebay_user_info				\
	(	id,									\
		email,								\
		host,								\
		name,								\
		company,							\
		address,							\
		city,								\
		state,								\
		zip,								\
		country,							\
		dayphone,							\
		nightphone,							\
		faxphone,							\
		creation,							\
		count,								\
		credit_card_on_file,				\
		good_credit,						\
		gender,								\
		interests_1,						\
		interests_2,						\
		interests_3,						\
		interests_4,						\
		partner_id,							\
		req_email_count,					\
		topsellerinitiateddate,				\
		topsellerlevel						\
	)										\
	values									\
	(	:id,								\
		:email,								\
		:host,								\
		:name,								\
		:company,							\
		:address,							\
		:city,								\
		:state,								\
		:zip,								\
		:country,							\
		:dayphone,							\
		:nightphone,						\
		:faxphone,							\
		TO_DATE(:creation,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:count,								\
		:ccof,								\
		:gc,								\
		:gender,							\
		:interests_1,						\
		:interests_2,						\
		:interests_3,						\
		:interests_4,						\
		:partner_id,						\
		:reqemailcount,						\
		TO_DATE(:topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:topsellerlevel						\
	)";

void clsDatabaseOracle::AddUserInfo(clsUser *pUser)
{
	int				rc;
	int				id;
	char			*pCompany;
	char			*pNullCompany = "";
	sb2				company_ind;
	sb2				dayphone_ind;
	sb2				nightphone_ind;
	sb2				faxphone_ind;
	char			*pNullPhone	= "";
	char			*pDayPhone;
	char			*pNightPhone;
	char			*pFaxPhone;
	char			good_credit[2];
	char			credit_card_on_file[2];
	int				count;
	int				interest_1;
	int				interest_2;
	int				interest_3;
	int				interest_4;
	int				partnerId;
	int				reqemailcount;
	char			creationDate[32];
	char			topsellerinitiateddate[32];
	int				topsellerlevel;

	// Date conversions
	TimeToORACLE_DATE(pUser->GetCreated(),
						   creationDate);

	TimeToORACLE_DATE(pUser->GetTopSellerInitiatedDate(),
						   topsellerinitiateddate);

	OpenAndParse(&mpCDAOneShot, SQL_AddUserInfo);

	// Get some credit info
	id					= pUser->GetId();
	if (pUser->GetGoodCredit())
		strcpy(good_credit, "1");
	else
		strcpy(good_credit, "0");
	if (pUser->GetCreditCardOnFile())
		strcpy(credit_card_on_file, "1");
	else
		strcpy(credit_card_on_file, "0");

	count				= pUser->GetCount();
	interest_1			= pUser->GetInterests_1();
	interest_2			= pUser->GetInterests_2();
	interest_3			= pUser->GetInterests_3();
	interest_4			= pUser->GetInterests_4();
	partnerId			= pUser->GetPartnerId();
	reqemailcount		= pUser->GetReqEmailCount();
	topsellerlevel		= pUser->GetTopSellerLevel();

	if (!pUser->GetCompany())
	{
		company_ind		= -1;
		pCompany		= pNullCompany;
	}
	else
	{
		company_ind		= 0;
		pCompany		= pUser->GetCompany();
	}

	if (!pUser->GetDayPhone())
	{
		dayphone_ind	= -1;
		pDayPhone		= pNullPhone;
	}
	else
	{
		dayphone_ind	= 0;
		pDayPhone		= pUser->GetDayPhone();
	}


	if (!pUser->GetNightPhone())
	{
		nightphone_ind	= -1;
		pNightPhone		= pNullPhone;
	}
	else
	{
		nightphone_ind	= 0;
		pNightPhone		= pUser->GetNightPhone();
	}

	if (!pUser->GetFaxPhone())
	{
		faxphone_ind	= -1;
		pFaxPhone		= pNullPhone;
	}
	else
	{
		faxphone_ind	= 0;
		pFaxPhone		= pUser->GetFaxPhone();
	}


	// Bind
	Bind(":id", &id);
	// Remove once email is no longer in ebay_user_info.
	Bind(":email", pUser->GetEmail());
	Bind(":host", pUser->GetHost());
	Bind(":name", pUser->GetName());
	Bind(":company", pCompany, &company_ind);
	Bind(":address", pUser->GetAddress());
	Bind(":city", pUser->GetCity());
	Bind(":state", pUser->GetState());
	Bind(":zip", pUser->GetZip());
	Bind(":country", pUser->GetCountry());
	Bind(":dayphone", pDayPhone, &dayphone_ind);
	Bind(":nightphone", pNightPhone, &nightphone_ind);
	Bind(":faxphone", pFaxPhone, &faxphone_ind);
	Bind(":creation", creationDate);
	Bind(":count", &count);
	Bind(":ccof", (char *)credit_card_on_file);
	Bind(":gc", (char *)good_credit);
	Bind(":gender", pUser->GetGender());
	Bind(":interests_1",&interest_1);
	Bind(":interests_2",&interest_2);
	Bind(":interests_3",&interest_3);
	Bind(":interests_4",&interest_4);
	Bind(":partner_id", &partnerId);
	Bind(":reqemailcount", &reqemailcount);
	Bind(":topsellerinitiateddate", topsellerinitiateddate);
	Bind(":topsellerlevel", &topsellerlevel);

	// Do it!
	rc	= oexec((struct cda_def *)mpCDACurrent);

	//
	// If we got an ORA-0001 (Unique constraint violated), there's
	// already a user info record out there. Let's just update it
	// This won't work if email also has unique constraint.
	//
	if (((struct cda_def *)mpCDACurrent)->rc == 1)
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);


		/* Commented out by poon on 06-04-98
		// check if same email exists or id exists
		if (HasUserInfo(pUser))
		{
			UpdateUserInfo(pUser);
			return;
		}
		*/

		return;
	}

	// Otherwise, the usual
	Check(rc);
	Commit();

	// Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}



//
// UpdateUser
//
// Right now, we just update EVERYTHING. It may make
// sense some day to have methods to just update 
// selected columns.
//
// ** Note **
// It really doesn't make any sense that we're updating
// creation date, now does it?
// ** Note **
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_UpdateUser =
 "update ebay_users									\
	set	marketplace = :marketplace,					\
		userid = :user_id,							\
		user_state = :user_state,					\
		email = :email,								\
		password = :password,						\
		salt = :salt,								\
		last_modified =	sysdate,					\
		userid_last_change =						\
		TO_DATE(:chgtime, 'YYYY-MM-DD HH24:MI:SS'),	\
		flags = :user_flags,						\
		country_id = :country_id,					\
		uvrating = :uvrating,						\
		uvdetail = :uvdetail,						\
		siteid = :site_id,							\
		co_partnerid = :co_partner_id				\
	where id = :id";

void clsDatabaseOracle::UpdateUser(clsUser *pUser)
{
	// Things extracted from the user object
	int				marketPlace;
	int				id;
	UserStateEnum	userState;
	int				user_flags;
	int             country_id;
	int				uvrating;
	int				uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int				siteId;
	int				coPartnerId;
	
//	char			lastModifiedDate[32];
	char			cUserIdLastChanged[32];

	marketPlace	= pUser->GetMarketPlace();
	id			= pUser->GetId();
	userState	= pUser->GetUserState();
	user_flags  = pUser->GetUserFlags();
	country_id  = pUser->GetCountryId();
	uvrating	= pUser->GetUVRating();
	uvdetail	= pUser->GetUVDetail();
	// nsacco 07/06/99 added siteid and co_partnerid
	siteId		= pUser->GetSiteId();
	coPartnerId	= pUser->GetCoPartnerId();

	// Date conversions
//	TimeToORACLE_DATE(pUser->GetLastModified(),
//							lastModifiedDate);
	TimeToORACLE_DATE(pUser->GetUserIdLastModified(),
					  cUserIdLastChanged);

	OpenAndParse(&mpCDAOneShot, SQL_UpdateUser);

	// Bind that input
	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);
	Bind(":user_id", pUser->GetUserId());
	Bind(":email", pUser->GetEmail());
	Bind(":user_state", (int *)&userState);
	Bind(":password", pUser->GetPassword());
	Bind(":salt", pUser->GetSalt());
//	Bind(":lastupdate", lastModifiedDate);
	Bind(":chgtime", cUserIdLastChanged);
	Bind(":user_flags", &user_flags);
	Bind(":country_id", &country_id);
	Bind(":uvrating", &uvrating);
	Bind(":uvdetail", &uvdetail);
	// nsacco 07/06/99 added siteid and co_partnerid
	Bind(":site_id", &siteId);
	Bind(":co_partner_id", &coPartnerId);

	// Let's do it!
	Execute();

	// Test for now rows processed here!

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


// UpdateUserInfo

static const char *SQL_UpdateUserInfo =
 "update ebay_user_info						\
	set	host = :host,						\
		name = :name,						\
		email = :email,						\
		company = :company,					\
		address = :address,					\
		city = :city,						\
		state = :state,						\
		zip = :zip,							\
		country = :country,					\
		dayphone = :dayphone,				\
		nightphone = :nightphone,			\
		faxphone = :faxphone,				\
		count = :count,						\
		credit_card_on_file = :ccof,		\
		good_credit = :gc,					\
		gender = :gender,					\
		interests_1 = :interests_1,			\
		interests_2 = :interests_2,			\
		interests_3 = :interests_3,			\
		interests_4	= :interests_4,			\
		partner_id = :partner_id,			\
		req_email_count = :mcount,			\
		topsellerinitiateddate =			\
		TO_DATE(:topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),	\
		topsellerlevel = :topsellerlevel	\
	where id = :id";

void clsDatabaseOracle::UpdateUserInfo(clsUser *pUser)
{
	// Things extracted from the user object
	int				id;

	sb2				dayphone_ind;
	sb2				nightphone_ind;
	sb2				faxphone_ind;
	sb2				company_ind;

	char			*pNullPhone	= "";
	char			*pDayPhone;
	char			*pNightPhone;
	char			*pFaxPhone;
	char			*pCompany;
	char			*pNullCompany = "";

	char			good_credit[2];
	char			credit_card_on_file[2];
	int 			count;
	int				interest_1;
	int				interest_2;
	int				interest_3;
	int				interest_4;
	int				partnerId;
	int				emailCount;
	char			topsellerinitiateddate[32];
	int				topsellerlevel;

//	char			creationDate[32];

	id					= pUser->GetId();
	if (pUser->GetGoodCredit())
		strcpy(good_credit, "1");
	else
		strcpy(good_credit, "0");
	if (pUser->GetCreditCardOnFile())
		strcpy(credit_card_on_file, "1");
	else
		strcpy(credit_card_on_file, "0");

	if (!pUser->GetCompany())
	{
		company_ind		= -1;
		pCompany		= pNullCompany;
	}
	else
	{
		company_ind		= 0;
		pCompany		= pUser->GetCompany();
	}

	if (!pUser->GetDayPhone())
	{
		dayphone_ind	= -1;
		pDayPhone		= pNullPhone;
	}
	else
	{
		dayphone_ind	= 0;
		pDayPhone		= pUser->GetDayPhone();
	}


	if (!pUser->GetNightPhone())
	{
		nightphone_ind	= -1;
		pNightPhone		= pNullPhone;
	}
	else
	{
		nightphone_ind	= 0;
		pNightPhone		= pUser->GetNightPhone();
	}

	if (!pUser->GetFaxPhone())
	{
		faxphone_ind	= -1;
		pFaxPhone		= pNullPhone;
	}
	else
	{
		faxphone_ind	= 0;
		pFaxPhone		= pUser->GetFaxPhone();
	}


	count				= pUser->GetCount();
	interest_1			= pUser->GetInterests_1();
	interest_2			= pUser->GetInterests_2();
	interest_3			= pUser->GetInterests_3();
	interest_4			= pUser->GetInterests_4();	
	partnerId			= pUser->GetPartnerId();
	emailCount			= pUser->GetReqEmailCount();
	topsellerlevel		= pUser->GetTopSellerLevel();

	// Date conversions
//	TimeToORACLE_DATE(pUser->GetCreated(),
//						   creationDate);

	TimeToORACLE_DATE(pUser->GetTopSellerInitiatedDate(),
						   topsellerinitiateddate);

	OpenAndParse(&mpCDAOneShot, SQL_UpdateUserInfo);

	// Bind
	Bind(":id", &id);
	Bind(":host", pUser->GetHost());
	Bind(":name", pUser->GetName());
	Bind(":email", pUser->GetEmail());
	Bind(":company", pCompany, &company_ind);
	Bind(":address", pUser->GetAddress());
	Bind(":city", pUser->GetCity());
	Bind(":state", pUser->GetState());
	Bind(":zip", pUser->GetZip());
	Bind(":country", pUser->GetCountry());
	Bind(":dayphone", pDayPhone, &dayphone_ind);
	Bind(":nightphone", pNightPhone, &nightphone_ind);
	Bind(":faxphone", pFaxPhone, &faxphone_ind);
//	Bind(":creation", creationDate);
	Bind(":count", &count);
	Bind(":ccof", credit_card_on_file);
	Bind(":gc", good_credit);
	Bind(":gender", pUser->GetGender());
	Bind(":interests_1",&interest_1);
	Bind(":interests_2",&interest_2);
	Bind(":interests_3",&interest_3);
	Bind(":interests_4",&interest_4);
	Bind(":partner_id", &partnerId);
	Bind(":mcount", &emailCount);
	Bind(":topsellerinitiateddate", topsellerinitiateddate);
	Bind(":topsellerlevel", &topsellerlevel);
	
	// Let's do it!
	Execute();

	// If we get no rows processed here, add user info
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		AddUserInfo(pUser);
		return;
	}

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}
//
// DeleteUser
// 
// *** NOTE ***
// ebay_user_info, too!
// *** NOTE ***
//

const char *SQL_DeleteIdInBidderLists =
" delete from ebay_bidder_item_lists			\
	where	id = :oldid";

const char *SQL_DeleteIdInSellerLists =
" delete from ebay_seller_item_lists			\
	where	id = :oldid";

void clsDatabaseOracle::DeleteUserLists(clsUser *pUser)
{
	// Things extracted from the user object
	int				id;
	
	id	= pUser->GetId();

	OpenAndParse(&mpCDAOneShot, SQL_DeleteIdInBidderLists);
	Bind(":oldid", &id);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_DeleteIdInSellerLists);
	Bind(":oldid", &id);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

const char *SQL_DeleteUser =
" delete from ebay_users			\
	where id = :id";

const char *SQL_DeleteUserInfo =
" delete from ebay_user_info			\
	where id = :id";

void clsDatabaseOracle::DeleteUserInfo(clsUser *pUser)
{
	// Things extracted from the user object
	int				id;
	
	id	= pUser->GetId();

	if (!(pUser->GetUserState() == UserGhost) ||
		!(pUser->GetUserState() == UserDeleted))
	{
		// delete User Info table
		OpenAndParse(&mpCDAOneShot, SQL_DeleteUserInfo);

		Bind(":id", &id);

		Execute();
		Commit();
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	return;
}

void clsDatabaseOracle::DeleteUser(clsUser *pUser)
{
	// Things extracted from the user object
	int				id;
	id	= pUser->GetId();
	
	// first delete user info
	// we do not call DeleteUserInfo because this is done in a single commit
	if ((pUser->GetUserState() != UserGhost) &&
		(pUser->GetUserState() != UserDeleted))
	{
		// delete User Info table
		OpenAndParse(&mpCDAOneShot, SQL_DeleteUserInfo);

		Bind(":id", &id);

		Execute();
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	// delete User table
	OpenAndParse(&mpCDAOneShot, SQL_DeleteUser);

	Bind(":id", &id);

	Execute();
	// to ensure deletion is in a transaction
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// RenameUser
// 
// *** NOTE ***
// We do email, too, since they're the same for
// now!
// *** NOTE ***
//
const char *SQL_RenameUser =
" update ebay_users						\
	set		userid = :newuserid			\
	where	marketplace = :marketplace	\
	and		userid = :olduserid";

void clsDatabaseOracle::RenameUser(MarketPlaceId marketPlace,
								   char *pOldUserId,
								   char *pNewUserId)
{

	OpenAndParse(&mpCDAOneShot, SQL_RenameUser);

	Bind(":marketplace", (int *)&marketPlace);
	Bind(":newuserid", pNewUserId);
	Bind(":olduserid", pOldUserId);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// AddRenamedUser
//
static const char *SQL_AddRenamedUser =
	"insert into ebay_renamed_users			\
	 (	fromuserid,							\
	    touserid							\
	 )										\
	 values									\
	 (	:fromuserid,						\
		:touserid							\
	)";

void clsDatabaseOracle::AddRenamedUser(char *pFromUser, char *pToUser)
{
	
	// Get the cursor ready
	// The usual suspects
	OpenAndParse(&mpCDAOneShot,
				 SQL_AddRenamedUser);

	// Bind
	Bind(":fromuserid", pFromUser);
	Bind(":touserid", pToUser);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// DeleteRenamedUser
// not used anymore
static const char *SQL_DeleteRenamedUser =
	"delete from ebay_renamed_users			\
	 where fromuserid = :fromuserid"; 

void clsDatabaseOracle::DeleteRenamedUser(char *pFromUser)
{
	
	// Get the cursor ready
	// The usual suspects
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteRenamedUser);

	// Bind
	Bind(":fromuserid", pFromUser);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

const char *SQL_RenameRenamedUser =
" update ebay_renamed_users				\
	set		touserid = :newuserid			\
	where	touserid = :olduserid";

void clsDatabaseOracle::RenameRenamedUser(char *pOldUserId,
								   char *pNewUserId)
{
	OpenAndParse(&mpCDAOneShot, SQL_RenameRenamedUser);

	Bind(":newuserid", pNewUserId);
	Bind(":olduserid", pOldUserId);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}



//
// AddManyUsers
//
//	This method is intended purely as a migration
//	tool for loading an existing database of users
//	into the system. 
//
//	It accepts arrays of information which must
//	conform to Oracle's array processing spec --
//	namely that all array elements are of equal 
//	length. 
//
//	Ids are generated by using a sequence, rather 
//	than letting the caller assign them
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_AddManyUsers =
 "insert into ebay_users					\
	(	marketplace,						\
		id,									\
		userid,								\
		email,								\
		user_state,							\
		password,							\
		salt,								\
		last_modified,						\
		siteid,								\
		co_partnerid						\
		)									\
	values									\
	(	:marketplace,						\
		:id,								\
		:user_id,							\
		:email,								\
		:user_state,						\
		:pass,								\
		:salt,								\
		TO_DATE(:lastupdate,				\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:site_id,							\
		:co_partner_id						\
	)";

// nsacco 07/06/99 added siteid and co_partnerid
void clsDatabaseOracle::AddManyUsers(
						int count,
						int	*pMarketPlaces,
						int *pIds,
						char *pUserIds,
						int userIdLen,
						char *pEmails,
						int emailLen,
						UserStateEnum *pUserStates,
						char *pPasswords,
						int passwordLen,
						char *pSalts,
						int saltLen,
						char *pLastUpdates,
						int lastUpdateLen,
						int *pSiteIds,
						int *pCoPartnerIds
						)
{
	int	rc;
	// Do our cursor thing
	OpenAndParse(&mpCDAAddManyUsers, SQL_AddManyUsers);


	// Now, the binds
	Bind(":marketplace", pMarketPlaces);
	Bind(":id", pIds);
	Bind(":user_id", pUserIds, userIdLen);
	Bind(":email", pEmails, emailLen);
	Bind(":user_state", (int *)pUserStates);
	Bind(":pass", pPasswords, passwordLen);
	Bind(":salt", pSalts, saltLen);
	Bind(":lastupdate", pLastUpdates, lastUpdateLen);
	// nsacco 07/06/99 added siteid and co_partnerid
	Bind(":site_id", pSiteIds);
	Bind(":co_partner_id", pCoPartnerIds);
	// I am ignoring the user flags. They should be 0 anyway
	// if we're updating an old user from Auction Web -- if this
	// ever even still happens!

	// Call oexn directly
	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	// Close stuff
	Commit();
	Close(&mpCDAAddManyUsers);
	SetStatement(NULL);

	return;
}

const char *SQL_AddManyUsersInfo =
 "insert into ebay_user_info				\
	(	id,									\
		host,								\
		name,								\
		company,							\
		address,							\
		city,								\
		state,								\
		zip,								\
		country,							\
		phone,								\
		creation,							\
		count,								\
		credit_card_on_file,				\
		good_credit,						\
		gender,								\
		interests_1	,						\
		interests_2,						\
		interests_3,						\
		interests_4							\
	)										\
	values									\
	(	:id,								\
		:host,								\
		:name,								\
		:company,							\
		:address,							\
		:city,								\
		:state,								\
		:zip,								\
		:country,							\
		:phone,								\
		TO_DATE(:creation,					\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:count,								\
		:ccof,								\
		:gc,								\
		:gender,							\
		:interests_1,						\
		:interests_2,						\
		:interests_3,						\
		:interests_4						\
	)";

void clsDatabaseOracle::AddManyUsersInfo(
						int count,
						int *pIds,
						char *pHosts,
						int hostLen,
						char *pNames,
						int nameLen,
						char *pCompanies,
						int companyLen,
						char *pAddresses,
						int addressLen,
						char *pCitys,
						int cityLen,
						char *pStates,
						int stateLen,
						char *pZips,
						int zipLen,
						char *pCountrys,
						int countryLen,
						char *pPhones,
						int phoneLen,
						char *pCreations,
						int creationLen,
						int *pCounts,
						char *pCredit_cards, // note: must be char instead
						char *pGood_credits, // of bools for AddManyUserInfo
						char *pGenders,
						int genderLen,
						int *pInterests_1,
						int *pInterests_2,
						int *pInterests_3,
						int *pInterests_4)
{
	int	rc;

	// Do our cursor thing
	OpenAndParse(&mpCDAAddManyUsersInfo, SQL_AddManyUsersInfo);

	// Now, the binds
	Bind(":id", pIds);
	Bind(":host", pHosts, hostLen);
	Bind(":name", pNames, nameLen);
	Bind(":company", pCompanies, companyLen);
	Bind(":address", pAddresses, addressLen);
	Bind(":city", pCitys, cityLen);
	Bind(":state", pStates, stateLen);
	Bind(":zip", pZips, zipLen);
	Bind(":country", pCountrys, countryLen);
	Bind(":phone", pPhones, phoneLen);
	Bind(":creation", pCreations, creationLen);
	Bind(":count", pCounts);
	Bind(":ccof", pCredit_cards,1);
	Bind(":gc", pGood_credits,1);
	Bind(":gender", pGenders, genderLen);
	Bind(":interests_1",pInterests_1);
	Bind(":interests_2",pInterests_2);
	Bind(":interests_3",pInterests_3);
	Bind(":interests_4",pInterests_4);

	// Call oexn directly
	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	// Close stuff
	Commit();
	Close(&mpCDAAddManyUsersInfo);
	SetStatement(NULL);



	return;
}
							
// reid user's assets

const char *SQL_RenameIdInBoards =
" update ebay_bulletin_boards			\
	set		user_id = :newid			\
	where	user_id = :oldid";

const char *SQL_RenameIdInUserAccounts =
" update ebay_accounts			\
	set		id = :newid			\
	where	id = :oldid";

const char *SQL_RenameIdInUserAccountXref =
" update ebay_account_xref		\
	set		id = :newid			\
	where	id = :oldid";

const char *SQL_RenameIdInUserMigratedAccount =
" update ebay_migrated_accounts		\
	set		id = :newid				\
	where	id = :oldid";

const char *SQL_RenameIdInUserBids =
" update ebay_bids			\
	set		user_id = :newid			\
	where	user_id = :oldid";

const char *SQL_RenameIdInUserItemSeller =
" update ebay_items			\
	set		seller = :newid, owner = :newid2			\
	where	seller = :oldid";

const char *SQL_RenameIdInUserItemHighBidder =
" update ebay_items			\
	set		high_bidder = :newid			\
	where	high_bidder = :oldid";

const char *SQL_RenameIdInUserBidsEnded =
" update ebay_bids_ended			\
	set		user_id = :newid			\
	where	user_id = :oldid";

const char *SQL_RenameIdInUserItemSellerEnded =
" update ebay_items_ended			\
	set		seller = :newid, owner = :newid2			\
	where	seller = :oldid";

const char *SQL_RenameIdInUserItemHighBidderEnded =
" update ebay_items_ended			\
	set		high_bidder = :newid			\
	where	high_bidder = :oldid";

const char *SQL_DeleteIdInUserAttributes =
" delete ebay_user_attributes			\
	where user_id = :oldid";
 
const char *SQL_RenameIdInUserSurveys =
" update ebay_user_survey_responses		\
	set		user_id = :newid			\
	where	user_id = :oldid";

const char *SQL_RenameIdInUserAdmin =
" update ebay_admin			\
	set		id = :newid			\
	where	id = :oldid";

const char *SQL_RenameIdInUserAliases =
" update ebay_user_past_aliases			\
	set		id = :newid			\
	where	id = :oldid";

void clsDatabaseOracle::RenameIdInUserAssets(int fromid,
									int toid)
{
	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInBoards);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserAccounts);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);
	Commit();
	Execute();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

//	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserAccountXref);

//	Bind(":newid", &toid);
//	Bind(":oldid", &fromid);

//	Execute();
//	commit();
//	Close(&mpCDAOneShot);
//	SetStatement(NULL);

//	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserMigratedAccount);

//	Bind(":newid", &toid);
//	Bind(":oldid", &fromid);

//	Execute();
//	Commit();
//	Close(&mpCDAOneShot);
//	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserBids);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	
	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserItemSeller);

	Bind(":newid", &toid);
	Bind(":newid2", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserItemHighBidder);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	
	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserBidsEnded);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	
	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserItemSellerEnded);

	Bind(":newid", &toid);
	Bind(":newid2", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserItemHighBidderEnded);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_DeleteIdInUserAttributes);

	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	
	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserSurveys);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserAdmin);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_RenameIdInUserAliases);

	Bind(":newid", &toid);
	Bind(":oldid", &fromid);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);


	return;
}

const char *SQL_GetUserAccountXref =
" select awid from ebay_account_xref	\
	where	id = :id";

int clsDatabaseOracle::IsUserAccountXref(int id)
{
	int		awid;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_GetUserAccountXref);

	Bind(":id", &id);

	// Bind output variable. 
	Define(1, &awid);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		awid = 0;
	}

	// Now everything is where it's supposed
	// to be.

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return awid;
}

const char *SQL_DeleteUserAccountXref =
" delete ebay_account_xref	\
	where	id = :id";

const char *SQL_DeleteUserMigratedAccount =
" delete ebay_migrated_accounts	\
	where	id = :id";

void clsDatabaseOracle::DeleteUserAccountXref(int id)
{
	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_DeleteUserAccountXref);
	Bind(":id", &id);

	// Let's do the SQL
	Execute();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	OpenAndParse(&mpCDAOneShot, SQL_DeleteUserMigratedAccount);
	Bind(":id", &id);

	// Let's do the SQL
	Execute();

	Close (&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


const char *SQL_GetUserAdminInfo =
" select count (*) from ebay_admin			\
	where	marketplace = :marketplace		\
	and		id = :id						\
	and		adcode = :code";

bool clsDatabaseOracle::GetUserAdminInfo(clsUser *pUser, int adcode)
{
	int		id;
	int		marketPlace;
	int		count;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_GetUserAdminInfo);

	id			= pUser->GetId();
	marketPlace = pUser->GetMarketPlace();

	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);
	Bind(":code", &adcode);
		// Bind those happy little output variables. 
	Define(1, &count);

	// Let's do the SQL
	Execute();

	Fetch();

	if (CheckForNoRowsFound())
	{
		// do nothing; should never happen;
	}

	// Now everything is where it's supposed
	// to be.

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	if (count==0)
		return false;
	else
		return true;
}

// insert only if no rows found

const char *SQL_SetUserAdminInfo =
" insert into ebay_admin			\
	(	marketplace,				\
		id,							\
		adcode						\
	)								\
	values							\
	(	:marketplace,				\
		:id,							\
		:code)";

void clsDatabaseOracle::SetUserAdminInfo(clsUser *pUser, bool /* doThey */, int code)
{
	int		id;
	int		marketPlace;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_SetUserAdminInfo);

	id			= pUser->GetId();
	marketPlace = pUser->GetMarketPlace();

	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);
	Bind(":code", &code);

	// Let's do the SQL
	Execute();
	Commit();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

static const char *SQL_IsUserSpecial =
 "select	userid						\
	from	ebay_special_users			\
	where	userid = :userid";

bool clsDatabaseOracle::IsUserSpecial(char *pUserId)
{
	char	userid[EBAY_MAX_USERID_SIZE + 1];
	bool	found;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_IsUserSpecial);

	// Define and Bind
	Define(1, (char *)userid, sizeof(userid));
	Bind(":userid", pUserId);

	// Execute and Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
		found	= false;
	else
		found	= true;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return found;
}

// is User Pending a rename?
static const char *SQL_IsUserRenamePending =
 "select	count(*)					\
	from	ebay_rename_pending			\
	where	id = :id	\
	and		touserid = :touserid";

bool clsDatabaseOracle::IsUserRenamePending(clsUser *pUser, char *pNewUserId)
{
	int		count;
	bool	found;
	int		id;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_IsUserRenamePending);

	id = pUser->GetId();

	// Define and Bind
	Define(1, &count);
	Bind(":id", &id);
	Bind(":touserid", pNewUserId);

	// Execute and Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound() || count == 0)
		found	= false;
	else
		found	= true;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return found;
}

// is User Pending a rename?
static const char *SQL_GetUserRenamePendingCode =
 "select	count(*)					\
	from	ebay_rename_pending			\
	where	id = :id	\
	and		touserid = :touserid		\
	and		salt = :salt";

bool clsDatabaseOracle::GetUserRenamePendingCode(clsUser *pUser, char *pNewUserId, char *pSalt)
{
	int count;
	bool isGood;
	int	id;

	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUserRenamePendingCode);

	id = pUser->GetId();
	// Define and Bind
	Define(1, &count);
	Bind(":id", &id);
	Bind(":touserid", pNewUserId);
	Bind(":salt", pSalt);

	// Execute and Fetch
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (count > 0)
		isGood = true;
	else isGood = false;
	return isGood;
}


//
//search user by user Id
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByUserIdSubString =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			siteid,								\
			co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		u.userid like :searcharg			\
	and		u.id = ui.id (+)					\
	order by u.userid";	
//
// search user by user name
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByUserNameSubString =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		ui.name like :searcharg		    	\
	and		u.id = ui.id (+)					\
	order by u.userid";	

//
//search users by address
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByUserAddressSubString =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		ui.address like :searcharg			\
	and		u.id = ui.id (+)					\
	order by u.userid";	

// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByUserIdExact =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		u.userid =:searcharg				\
	and		u.id = ui.id (+)";	

//
//search user by city
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByCitySubString =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.siteid,							\
			u.co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		Upper(ui.city) like Upper(:searcharg)			    \
	and		u.id = ui.id (+)					\
	order by u.userid";	

//
//search by state
//
// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetUsersByStateSubString =
  "select	u.id,								\
			u.userid,							\
			u.user_state,						\
			TO_CHAR(u.last_modified,			\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(u.userid_last_change,		\
				'YYYY-MM-DD HH24:MI:SS'),		\
			u.flags,							\
			u.country_id,                       \
			NVL(u.uvrating, -99999),			\
			NVL(u.uvdetail, 0),					\
			u.siteid,							\
			u.co_partnerid,						\
			ui.host,							\
			ui.name,							\
			ui.company,							\
			ui.address,							\
			ui.city,							\
			ui.state,							\
			ui.zip,								\
			ui.country,							\
			ui.dayphone,						\
			ui.nightphone,						\
			ui.faxphone,						\
			TO_CHAR(ui.creation,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			u.email,							\
			ui.count,							\
			ui.credit_card_on_file,				\
			ui.good_credit,						\
			ui.gender,							\
			ui.interests_1	,					\
			ui.interests_2,						\
			ui.interests_3,						\
			ui.interests_4,						\
			NVL(ui.req_email_count, 0),			\
			TO_CHAR(ui.topsellerinitiateddate,	\
				'YYYY-MM-DD HH24:MI:SS'),		\
			NVL(ui.topsellerlevel, 0)			\
	from ebay_users u,							\
		 ebay_user_info ui						\
	where	u.marketplace	= :marketplace		\
	and		Upper(ui.state) like Upper(:searcharg)			    \
	and		u.id = ui.id (+)					\
	order by u.userid";	

void clsDatabaseOracle::GetUsersBySubstring(MarketPlaceId marketplace,
											   UserSearchTypeEnum how,
											   char *pString,
											   vector<clsUser *> *pvUsers)
{
	char		*pSearchArg;

	// Where things go
	int				id;
	char			userid[EBAY_MAX_USERID_SIZE + 1];
	UserStateEnum	userstate;
	char			last_modified[32];
	char			userid_last_change[32];
	int				user_flags;
	int             country_id;
	sb2             country_id_ind;
	int             uvrating;
	int             uvdetail;
	// nsacco 07/06/99 added siteid and co_partnerid
	int				siteId = SITE_EBAY_MAIN;
	int				coPartnerId = PARTNER_NONE;
	char			host[128];
	sb2				host_ind;
	char			name[128];
	sb2				name_ind;
	char			company[128];
	sb2				company_ind;
	char			address[128];
	sb2				address_ind;
	char			city[128];
	sb2				city_ind;
	char			state[128];
	sb2				state_ind;
	char			zip[128];
	sb2				zip_ind;
	char			country[128];
	sb2				country_ind;
	char			dayphone[33];
	sb2				dayphone_ind;
	char			nightphone[33];
	sb2				nightphone_ind;
	char			faxphone[33];
	sb2				faxphone_ind;
	char			creation[32];
	sb2				creation_ind;
	char			email[128];
	sb2				email_ind;
	int				count;
	sb2				count_ind;
	char			credit_card_on_file[2];
	sb2				credit_card_on_file_ind;
	char			good_credit[2];
	sb2				good_credit_ind;
	char			gender[32];
	sb2				gender_ind;
	int				interests_1;
	sb2				interests_1_ind;
	int				interests_2;
	sb2				interests_2_ind;
	int				interests_3;
	sb2				interests_3_ind;
	int				interests_4;
	sb2				interests_4_ind;
	bool			bccof;
	bool			bgc;
	sb2				last_change_ind;
	int				reqEmailCount;

	char			*pDayPhone;
	char			*pNightPhone;
	char			*pFaxPhone;
	char			*pCompany;

	time_t			creation_time;
	time_t			lastmodified_time;
	time_t			userid_last_change_time;
	time_t			topsellerinitiateddate_time;

	clsUser			*pUser;
	char			topSellerInitiatedDate[32];
	sb2				topSellerInitiatedDate_ind;
	int				topSellerLevel;

	// 
	if (how == UserSearchByUserIdExact)
	{
		// if UserSearchByUserIdExact call the existing function
		pUser = GetUserByUserId(marketplace, pString);
		if (!pUser && strchr(pString, '@'))
		{
			pUser = GetUserByEmail(marketplace, pString);
		}
		if (pUser)
			pvUsers->push_back(pUser);

		return;
	}
	else
	{
		// Let's build the search argument
		pSearchArg	= new char[strlen(pString) + 2 + 1];

		if (how != UserSearchByUserIdExact)
		{
			strcpy(pSearchArg, "%");
			strcat(pSearchArg, pString);
			strcat(pSearchArg, "%");
		}
		else
			strcpy(pSearchArg, pString);

		// Open the right thing
		switch (how)
		{
			case	UserSearchByUserIdSubstring:
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByUserIdSubString);
				break;
			case    UserSearchByNameSubstring: 
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByUserNameSubString);
				break;
			case    UserSearchByAddressSubstring:
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByUserAddressSubString);
				break;
			case	UserSearchByUserIdExact:
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByUserIdExact);
				break;
			case    UserSearchByCitySubstring:
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByCitySubString);
				break;
			case	UserSearchByStateSubstring:
				OpenAndParse(&mpCDAOneShot,
							 SQL_GetUsersByStateSubString);
				break;
			default:
				delete	[] pSearchArg;
				return;
		}

		// Binds and Defines
		// Ok, let's do some binds
		Bind(":marketplace", (int *)&marketplace);
		Bind(":searcharg", pSearchArg);

		// And zee defines
		Define(1, &id);
		Define(2, userid, sizeof(userid));
		Define(3, (int *)&userstate);
		Define(4, last_modified, sizeof(last_modified));
		Define(5, userid_last_change, sizeof(userid_last_change), &last_change_ind);
		Define(6, &user_flags);
		Define(7, &country_id, &country_id_ind);
		Define(8, &uvrating);
		Define(9, &uvdetail);
		// nsacco 07/06/99 added siteid and co_partnerid
		Define(10, &siteId);
		Define(11, &coPartnerId);
		Define(12, host, sizeof(host), &host_ind);
		Define(13, name, sizeof(name), &name_ind);
		Define(14, company, sizeof(company), &company_ind);
		Define(15, address, sizeof(address), &address_ind);
		Define(16, city, sizeof(city), &city_ind);
		Define(17, state, sizeof(state), &state_ind);
		Define(18, zip, sizeof(zip), &zip_ind);
		Define(19, country, sizeof(country), &country_ind);
		Define(20, dayphone, sizeof(dayphone), &dayphone_ind);
		Define(21, nightphone, sizeof(nightphone), &nightphone_ind);
		Define(22, faxphone, sizeof(faxphone), &faxphone_ind);
		Define(23, creation, sizeof(creation), &creation_ind);
		Define(24, email, sizeof(email), &email_ind);
		Define(25, &count, &count_ind);
		Define(26, (char *)credit_card_on_file, sizeof(credit_card_on_file),
			   &credit_card_on_file_ind);
		Define(27, (char *)good_credit, sizeof(good_credit),
			   &good_credit_ind);
		Define(28, gender, sizeof(gender),
			   &gender_ind);
		Define(29, &interests_1,
			   &interests_1_ind);
		Define(30, &interests_2,
			   &interests_2_ind);
		Define(31, &interests_3,
			   &interests_3_ind);
		Define(32, &interests_4,
			   &interests_4_ind);
		Define(33, &reqEmailCount);
		Define(34, topSellerInitiatedDate, sizeof(topSellerInitiatedDate), &topSellerInitiatedDate_ind);
		Define(35, &topSellerLevel);

		// Execute
		Execute();

		// See if we found nothing
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			delete [] pSearchArg;
			return;
		}

		// Now, fetch until we drop
		while(1)
		{
			Fetch();

			if (CheckForNoRowsFound())
				break;

			if (userstate != UserGhost)
			{
				// Handle nulls
				if (dayphone_ind == -1)
					pDayPhone	= NULL;
				else
					pDayPhone	= dayphone;

				if (nightphone_ind == -1)
					pNightPhone	= NULL;
				else
					pNightPhone	= nightphone;

				if (faxphone_ind == -1)
					pFaxPhone	= NULL;
				else
					pFaxPhone	= faxphone;

				if (company_ind == -1)
					pCompany	= NULL;
				else
					pCompany	= company;

				// set boolean values for credit info
				if (credit_card_on_file[0] == '1')
					bccof	= true;
				else
					bccof	= false;

				if (good_credit[0] == '1')
					bgc	= true;
				else
					bgc	= false;

				ORACLE_DATEToTime(creation, &creation_time);
			}

			// put it into clsUser!
			ORACLE_DATEToTime(last_modified, &lastmodified_time);
			
			if (last_change_ind != -1)
				ORACLE_DATEToTime(userid_last_change, &userid_last_change_time);
			else
				userid_last_change_time = 0;

			if (country_id_ind == -1)
				country_id = clsCountries::COUNTRY_NONE;

			if (topSellerInitiatedDate_ind != -1)
				ORACLE_DATEToTime(topSellerInitiatedDate, &topsellerinitiateddate_time);
			else
				topsellerinitiateddate_time = 0;

			// nsacco 07/06/99
			if (siteId == -1)
			{
				siteId = SITE_EBAY_MAIN;
			}

			if (coPartnerId == -1)
			{
				coPartnerId = PARTNER_EBAY;
			}

			// Now, make a user
			// nsacco 07/06/99 - create with site and partner
			pUser	= new clsUser(marketplace,
								  id,
								  userid,
								  email,
								  userstate,
								  (char *)"",
								  (char *)"",
								  lastmodified_time,
								  userid_last_change_time,
								  user_flags,
								  country_id,
								  uvrating,
								  uvdetail,
								  siteId,
								  coPartnerId);

			// If the user's a ghost, we're done
			if (userstate	!= UserGhost)
			{
				pUser->AddUserInfo(	host,
									name,
									pCompany,
									address,
									city,
									state,
									zip,
									country,
									pDayPhone,
									pNightPhone,
									pFaxPhone,
									creation_time,
									count,
									bccof,
									bgc,
									gender,
									interests_1,
									interests_2,
									interests_3,
									interests_4,
									reqEmailCount,	//lint !e644 we know everything initted ok
									topsellerinitiateddate_time,
									topSellerLevel);
			}

			pvUsers->push_back(pUser);

		}

		// Clean!
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		delete [] pSearchArg;
	}

	return;
}



//
// AddAWCreditCardOnFile
//
static const char *SQL_GetAWCreditStatus =
" select	credit_card_on_file,				\
			good_credit							\
  from		ebay_aw_credit_status				\
  where		userid=:userid";

static const char *SQL_AddAWCreditStatus =
" insert into ebay_aw_credit_status				\
	(	userid,									\
		credit_card_on_file,					\
		good_credit								\
	)											\
	values										\
	(	:userid,								\
		:cc,									\
		:gc										\
	)";

static const char *SQL_SetAWCreditStatus =
" update	ebay_aw_credit_status				\
  set		credit_card_on_file = :cc,			\
			good_credit = :gc					\
  where		userid=:userid";

void clsDatabaseOracle::AddAWCreditCardOnFile(char *pUserId)
{
	char	good_credit[2];
	sb2		good_credit_ind;

	char	credit_card_on_file[2];
	sb2		credit_card_on_file_ind;

	bool	notOnFile;

	// First, let's git what we got (if anything)
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAWCreditStatus);

	Bind(":userid", pUserId);
	Define(1, credit_card_on_file, sizeof(credit_card_on_file), &credit_card_on_file_ind);
	Define(2, good_credit, sizeof(good_credit), &good_credit_ind);

	credit_card_on_file[0]	= '0';
	credit_card_on_file[1]	= '\0';
	good_credit[0]				= '0';
	good_credit[1]				= '\0';

	ExecuteAndFetch();

	notOnFile	= CheckForNoRowsFound();

	if (!notOnFile)
	{
		if (good_credit_ind == -1)
		{
			good_credit[0] = '0';
			good_credit[1] = '\0';
		}

		if (credit_card_on_file_ind == -1)
		{
			credit_card_on_file[0]	= '0';
			credit_card_on_file[1]	= '\0';
		}
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// Already set?
	if (credit_card_on_file[0] == '1')
		return;

	// Now, set it...
	if (notOnFile)
	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_AddAWCreditStatus);
	}
	else
	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_SetAWCreditStatus);
	}

	credit_card_on_file[0]	= '1';
	credit_card_on_file[1]	= '\0';

	Bind(":userid", pUserId);
	Bind(":cc", credit_card_on_file);
	Bind(":gc",good_credit);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// AddAWGoodCredit
//
void clsDatabaseOracle::AddAWGoodCredit(char *pUserId)
{
	char	good_credit[2];
	sb2		good_credit_ind;

	char	credit_card_on_file[2];
	sb2		credit_card_on_file_ind;

	bool	notOnFile;

	// First, let's git what we got (if anything)
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAWCreditStatus);

	Bind(":userid", pUserId);
	Define(1, credit_card_on_file, sizeof(credit_card_on_file), &credit_card_on_file_ind);
	Define(2, good_credit, sizeof(good_credit), &good_credit_ind);

	credit_card_on_file[0]	= 0x00;
	good_credit[0]			= 0x00;

	ExecuteAndFetch();

	notOnFile	= CheckForNoRowsFound();

	if (!notOnFile)
	{
		if (good_credit_ind == -1)
		{
			good_credit[0] = '0';
			good_credit[1] = '\0';
		}

		if (credit_card_on_file_ind == -1)
		{
			credit_card_on_file[0]	= '0';
			credit_card_on_file[1]	= '\0';
		}
	}

	Close(&mpCDAOneShot);

	// Already set?
	if (good_credit[0] == '1')
		return;

	// Now, set it...
	if (notOnFile)
	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_AddAWCreditStatus);
	}
	else
	{
		OpenAndParse(&mpCDAOneShot,
					 SQL_SetAWCreditStatus);
	}

	good_credit[0]	= '1';
	good_credit[1]	= '\0';

	Bind(":userid", pUserId);
	Bind(":cc", credit_card_on_file);
	Bind(":gc",good_credit);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


//
// GetAWCreditStatus
//
void clsDatabaseOracle::GetAWCreditStatus(char *pUserId,
										  bool *pCreditCardOnFile,
										  bool *pGoodCredit)
{
	char	good_credit[2];
	sb2		good_credit_ind;

	char	credit_card_on_file[2];
	sb2		credit_card_on_file_ind;

	bool	notOnFile;

	// First, let's git what we got (if anything)
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAWCreditStatus);

	Bind(":userid", pUserId);
	Define(1, credit_card_on_file, sizeof(credit_card_on_file), &credit_card_on_file_ind);
	Define(2, good_credit, sizeof(good_credit), &good_credit_ind);

	credit_card_on_file[0]	= 0x00;
	good_credit[0]			= 0x00;

	ExecuteAndFetch();

	notOnFile	= CheckForNoRowsFound();

	if (!notOnFile)
	{
		if (good_credit_ind == -1)
		{
			good_credit[0] = '0';
			good_credit[1] = '\0';
		}

		if (credit_card_on_file_ind == -1)
		{
			credit_card_on_file[0]	= '0';
			credit_card_on_file[1]	= '\0';
		}
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	*pGoodCredit		= false;
	*pCreditCardOnFile	= false;

	if (good_credit[0] == '1')
		*pGoodCredit	= true;

	if (credit_card_on_file[0] == '1')
		*pCreditCardOnFile	= true;

	return;
}

const char *SQL_SetUserRenamePending =
" insert into ebay_rename_pending	\
	(	marketplace,				\
		id,					\
		touserid,					\
		password,					\
		salt,						\
		created						\
	)								\
	values							\
	(	:marketplace,				\
		:id,				\
		:touserid,					\
		:password,					\
		:salt,						\
		sysdate)";

void clsDatabaseOracle::SetUserRenamePending(clsUser *pUser, 
										char *pNewId, 
										char *pPass,
										char *pSalt)
{
	int	 marketPlace;
	int	 id;

	// The usual suspects
	OpenAndParse(&mpCDAOneShot, SQL_SetUserRenamePending);

	marketPlace = pUser->GetMarketPlace();
	id = pUser->GetId();

	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);
	Bind(":touserid", pNewId);
	Bind(":password", pPass);
	Bind(":salt", pSalt);

	// Let's do the SQL
	Execute();
	Commit();

	Close (&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

const char *SQL_DeleteUserRenamePending =
	"delete from ebay_rename_pending			\
	 where id = :id"; 

void clsDatabaseOracle::DeleteUserRenamePending(int Id)
{
	
	// Get the cursor ready
	// The usual suspects
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteUserRenamePending);

	// Bind
	Bind(":id", &Id);

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}


const char *SQL_ExpireUserRenamePending =
	"delete from ebay_rename_pending			\
	 where created = TO_DATE(:axdate,			\
				'YYYY-MM-DD HH24:MI:SS')"; 

void clsDatabaseOracle::ExpireUserRenamePending(time_t endTime)
{
	char axTime[32];
	
	// Date conversions
	TimeToORACLE_DATE(endTime,
						   axTime);
	
	// Get the cursor ready
	OpenAndParse(&mpCDAOneShot,
				 SQL_ExpireUserRenamePending);
	
	// Bind
	Bind(":created", axTime, sizeof(axTime));

	// Do it!
	Execute();

	// Commit, and complete
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// SetDateAndHost
//
//	This method is used to set a user's creation date and
//	origionating host from the AW files. I did it this
//	way (instead of using UpdateUser) because it's faster
//
//	** NOTE **
//	Migration only
//	** NOTE **

const char *SQL_SetUserDateAndHost = 
	"update ebay_user_info								\
		set creation = 									\
			TO_DATE(:creation,							\
				'YYYY-MM-DD HH24:MI:SS'),				\
			host = :thehost								\
		where	id = :theid";

void clsDatabaseOracle::SetUserDateAndHost(int id,
										   time_t when,
										   char *pHost)
{
	char			creationDate[32];

	// Time Conversion
	TimeToORACLE_DATE(when,
					  creationDate);

	OpenAndParse(&mpCDAOneShot,
				 SQL_SetUserDateAndHost);

	Bind(":creation", creationDate);
	Bind(":thehost", pHost);
	Bind(":theid", &id);

	Execute();
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
// ChangeUserId
//
//	This is an atomic method (either it works, or it doesn't, with 
//	no unvcertainty) for changing a user's userid
//
const char *SQL_ChangeUserId =
	"update	ebay_users								\
		set		userid=:newid						\
		where	marketplace = :marketplace			\
		and		id = :id";

bool clsDatabaseOracle::ChangeUserId(int marketPlace,
									 int id,
									 char *pNewUserId)
{
	int		rc;

	OpenAndParse(&mpCDAOneShot,
				 SQL_ChangeUserId);

	Bind(":newid", pNewUserId);
	Bind(":marketplace", &marketPlace);
	Bind(":id", &id);

	// Do it!
	rc	= oexec((struct cda_def *)mpCDACurrent);

	//
	// If we got an ORA-0001 (Unique constraint violated), there's
	// already a user info record out there. Let's just update it
	//
	if (((struct cda_def *)mpCDACurrent)->rc == 1)
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return false;
	}

	// Otherwise, the usual
	Check(rc);
	Commit();

	// Clean!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return(true);
}

//
// AddUserAlias
//
//	Adds a user's alias to the past aliases table
//
const char *SQL_AddUserAlias =
" insert into ebay_user_past_aliases				\
	(	marketplace,								\
		id,											\
		alias,										\
		aliasflag,									\
		modified,									\
		host										\
	)												\
	values											\
	(	:marketplace,								\
		:id,										\
		:alias,										\
		0,											\
		TO_DATE(:chgtime, 'YYYY-MM-DD HH24:MI:SS'),	\
		:host										\
	)";

void clsDatabaseOracle::AddUserAlias(int marketplace,
									 int id,
									 char *pAlias,
									 char *pHost,
									 time_t changeTime)
{
	char	cChangeTime[32];

	TimeToORACLE_DATE(changeTime, cChangeTime);

	OpenAndParse(&mpCDAOneShot,
				 SQL_AddUserAlias);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":alias", pAlias);
	Bind(":chgtime", cChangeTime);
	Bind(":host", pHost);

	Execute();
	
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
//	Adds a user's alias to the past aliases table
//
const char *SQL_AddEmailAlias =
" insert into ebay_user_past_aliases				\
	(	marketplace,								\
		id,											\
		alias,										\
		aliasflag,									\
		modified,									\
		host										\
	)												\
	values											\
	(	:marketplace,								\
		:id,										\
		:alias,										\
		1,											\
		TO_DATE(:chgtime, 'YYYY-MM-DD HH24:MI:SS'),	\
		:host										\
	)";

void clsDatabaseOracle::AddEmailAlias(int marketplace,
									 int id,
									 char *pAlias,
									 char *pHost,
									 time_t changeTime)
{
	char	cChangeTime[32];

	TimeToORACLE_DATE(changeTime, cChangeTime);

	OpenAndParse(&mpCDAOneShot,
				 SQL_AddEmailAlias);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":alias", pAlias);
	Bind(":chgtime", cChangeTime);
	Bind(":host", pHost);

	Execute();
	
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

//
// UserIdChangedInInterval
//
//	Determines if the userid has changed in the last 
//	n seconds
//
/* NOT USED!

const char *SQL_UserIdChangedInInterval =
"select	count(*)									\
	from	ebay_user_past_aliases					\
	where	marketplace = :marketplace				\
	and		id = :id								\
	and		aliasflag = 0							\
	and		modified >=								\
		TO_DATE(:limit, 'YYYY-MM-DD HH24:MI:SS')";

bool clsDatabaseOracle::UserIdChangedInInterval(int marketplace,
												int id,
												int interval)
{
	int				limitTime;
	char			cLimitTime[32];

	int				count = 0;

	limitTime	= time(0) - interval;
	TimeToORACLE_DATE(limitTime, cLimitTime);

	OpenAndParse(&mpCDAOneShot,
				 SQL_UserIdChangedInInterval);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":limit", cLimitTime);

	Define(1, &count);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (count > 0)
		return true;
	else
		return false;
}

//
// EMailChangedInInterval
//
//	Determines if the eMail has changed in the last 
//	n seconds
//
const char *SQL_EMailChangedInInterval =
"select	count(*)									\
	from	ebay_user_past_aliases					\
	where	marketplace = :marketplace				\
	and		id = :id								\
	and		aliasflag = 1							\
	and		modified >=								\
		TO_DATE(:limit, 'YYYY-MM-DD HH24-MI-SS')";

bool clsDatabaseOracle::EMailChangedInInterval(int marketplace,
											   int id,
											   int interval)
{
	int				limitTime;
	char			cLimitTime[32];

	int				count = 0;

	limitTime	= time(0) - interval;
	TimeToORACLE_DATE(limitTime, cLimitTime);

	OpenAndParse(&mpCDAOneShot,
				 SQL_EMailChangedInInterval);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);
	Bind(":limit", cLimitTime);

	Define(1, &count);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	if (count > 0)
		return true;
	else
		return false;
}
*/

//
// GetAliasHistoryVector
//
const char *SQL_GetAliasHistory	= 
"select	alias,									\
		aliasflag,								\
		TO_CHAR(modified,						\
				'YYYY-MM-DD HH24-MI-SS'),		\
		host									\
	from	ebay_user_past_aliases				\
	where	marketplace = :marketplace			\
	and		id = :id							\
	order by	modified";

void clsDatabaseOracle::GetAliasHistory(int marketplace,
										int id,
										UserAliasHistoryVector *pVAlias)
{
	int					type;
	char				alias[256];
	char				cWhen[32];
	char				host[256];

	UserAliasTypeEnum	aliasType;
	time_t				whenTime;

	clsUserAliasHistory	*pAliasHistory;

	OpenAndParse(&mpCDAGetAliasHistory,
				 SQL_GetAliasHistory);

	Bind(":marketplace", &marketplace);
	Bind(":id", &id);

	Define(1, alias, sizeof(alias));
	Define(2, &type);
	Define(3, cWhen, sizeof(cWhen));
	Define(4, host, sizeof(host));

	Execute();

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		ORACLE_DATEToTime(cWhen, &whenTime);
		if (type == 0)
			aliasType	= UserIdAlias;
		else
			aliasType	= EMailAlias;

		pAliasHistory	= new clsUserAliasHistory(aliasType,
												  alias,
												  whenTime,
												  host);

		pVAlias->push_back(pAliasHistory);
	}

	Close(&mpCDAGetAliasHistory);
	SetStatement(NULL);

	return;
}

static char *SQL_AddReqEmailCount = 
 "update ebay_user_info								\
	set Req_email_count = req_email_count + :delta		\
	where id = :id";

// Add to user's request for email counts
void clsDatabaseOracle::AddReqEmailCount(int uid,
								int delta)
{

	// Open + Parse
	OpenAndParse(&mpCDAAddReqEmailCount, SQL_AddReqEmailCount);

	// Bind
	Bind(":id", &uid);
	Bind(":delta", &delta);

	// Do it!
	Execute();
	Commit();

	// Close 
	Close(&mpCDAAddReqEmailCount);
	SetStatement(NULL);

	return;
}

static char *SQL_ResetReqEmailCount = 
 "update ebay_user_info			\
	set Req_email_count = 0		\
	where id = :id";

// Add to user's request for email counts
void clsDatabaseOracle::ResetReqEmailCount(int uid)
{

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_ResetReqEmailCount);

	// Bind
	Bind(":id", &uid);

	// Do it!
	Execute();
	Commit();

	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetIdbyAlias
//
const char *SQL_GetIdByAlias	= 
"select	id,										\
        TO_CHAR(modified,						\
				'YYYY-MM-DD HH24-MI-SS')		\
	from	ebay_user_past_aliases				\
	where	marketplace = :marketplace			\
	and     aliasflag = 0						\
	and		alias = :alias						\
	order by	modified";

void clsDatabaseOracle::GetIdByAlias(int marketplace,
									 char *alias,
									 UserIdAliasHistoryVector *pvUsers)
{
	int					id;
	char				cWhen[32];
	
	time_t				whenTime;

	clsUserIdAliasHistory	*pAliasHistory;

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetIdByAlias);

	Bind(":marketplace", &marketplace);
	Bind(":alias", alias);

	Define(1, &id);
	Define(2, cWhen, sizeof(cWhen));

	Execute();

	while(1)
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		ORACLE_DATEToTime(cWhen, &whenTime);
		
		pAliasHistory	= new clsUserIdAliasHistory(id, whenTime);
												
		pvUsers->push_back(pAliasHistory);
        
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetManyUsersForCreditBatch
//
//	This routine is highly optimized to get a gaggle of
//	users for credit batches. Since credit-batches are
//	only interested in a FEW fields, that's all it gets.
//
//	It also gets many users at once, so as to use array
//	fetch. We use the funky query below to take care of
//	it.
//
//
#define ORA_CREDIT_BATCH_USER_ARRAYSIZE 20

// nsacco 08/02/99 added siteid and copartnerid
static const char *SQL_GetManyUsersForCreditBatch =
 "select	u.id,										\
			u.userid,									\
			u.email,									\
			u.user_state,								\
			u.site_id,									\
			u.co_partnerid								\
	from ebay_users u									\
	where	u.marketplace = :marketplace				\
	and													\
	(	u.id = :i00 or u.id = :i01 or					\
		u.id = :i02 or u.id = :i03 or					\
		u.id = :i04 or u.id = :i05 or					\
		u.id = :i06 or u.id = :i07 or					\
		u.id = :i08 or u.id = :i09 or					\
		u.id = :i10 or u.id = :i11 or					\
		u.id = :i12 or u.id = :i13 or					\
		u.id = :i14 or u.id = :i15 or					\
		u.id = :i16 or u.id = :i17 or					\
		u.id = :i18 or u.id = :i19						\
	)";


void clsDatabaseOracle::GetManyUsersForCreditBatch(
								MarketPlaceId marketplace,
								list<unsigned int> *pUserIdList,
								UserList *pUsers)
{
	// Itcherator
	list<unsigned int>::iterator	iUser;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iUserInStatement;

	// This thing is for up to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE slots
	// for item ids
	int							predicateUserIds[ORA_CREDIT_BATCH_USER_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	int					id[ORA_CREDIT_BATCH_USER_ARRAYSIZE];
	char				userid[ORA_CREDIT_BATCH_USER_ARRAYSIZE][EBAY_MAX_USERID_SIZE + 1];
	char				email[ORA_CREDIT_BATCH_USER_ARRAYSIZE][EBAY_MAX_EMAIL_SIZE + 1];
	UserStateEnum		state[ORA_CREDIT_BATCH_USER_ARRAYSIZE];

	// nsacco 08/02/99
	int					siteId[ORA_CREDIT_BATCH_USER_ARRAYSIZE];
	int					coPartnerId[ORA_CREDIT_BATCH_USER_ARRAYSIZE];

	// An oozer
	clsUser				*pUser;

	// See if there's work to be done ;-)
	if (pUserIdList->size() < 1)
		return;


	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyUsersForCreditBatch, SQL_GetManyUsersForCreditBatch);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", &predicateUserIds[0]);
	Bind(":i01", &predicateUserIds[1]);
	Bind(":i02", &predicateUserIds[2]);
	Bind(":i03", &predicateUserIds[3]);
	Bind(":i04", &predicateUserIds[4]);
	Bind(":i05", &predicateUserIds[5]);
	Bind(":i06", &predicateUserIds[6]);
	Bind(":i07", &predicateUserIds[7]);
	Bind(":i08", &predicateUserIds[8]);
	Bind(":i09", &predicateUserIds[9]);
	Bind(":i10", &predicateUserIds[10]);
	Bind(":i11", &predicateUserIds[11]);
	Bind(":i12", &predicateUserIds[12]);
	Bind(":i13", &predicateUserIds[13]);
	Bind(":i14", &predicateUserIds[14]);
	Bind(":i15", &predicateUserIds[15]);
	Bind(":i16", &predicateUserIds[16]);
	Bind(":i17", &predicateUserIds[17]);
	Bind(":i18", &predicateUserIds[18]);
	Bind(":i19", &predicateUserIds[19]);



	// Now, define the output variables. 
	Define(1, &id[0]);
	Define(2, (char *)userid, EBAY_MAX_USERID_SIZE + 1);
	Define(3, (char *)email, EBAY_MAX_EMAIL_SIZE + 1);
	Define(4, (int *)&state[0]);
	// nsacco 08/02/99
	Define(5, &siteId[0]);
	Define(6, &coPartnerId[0]);

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iUserInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iUser = pUserIdList->begin();
		 ;
		 iUser++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iUser == pUserIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iUserInStatement < ORA_CREDIT_BATCH_USER_ARRAYSIZE;
				 iUserInStatement++)
			{
				predicateUserIds[iUserInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateUserIds[iUserInStatement] = (*iUser);
			iUserInStatement++;

			// Let's see we've "filled up" a statement
			if (iUserInStatement >= ORA_CREDIT_BATCH_USER_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_CREDIT_BATCH_USER_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyUsersForCreditBatch);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (id[i] == 0)
					continue;

				// nsacco 08/02/99
				if (siteId[i] == -1)
				{
					siteId[i] = SITE_EBAY_MAIN;
				}

				if (coPartnerId[i] == -1)
				{
					coPartnerId[i] = PARTNER_EBAY;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				// nsacco 08/02/99 - create with site and partner
				pUser	= new clsUser(marketplace,		// Marketplace
									  id[i],			// Id
									  userid[i],		// Userid
									  email[i],			// email
									  state[i],			// State
									  (char *)"",		// Password
									  (char *)"",		// Salt
									  0,				// LastModified
									  0,				// req email count
									  0,			    // user choices
				                      0,				// country id
									  clsUserVerificationServices::UV_RATING_NOT_CALCULATED,	// uvrating
									  0,                // uvdetail
									  siteId[i],			// siteId
									  coPartnerId[i]);		// coPartnerId

				pUsers->push_back(clsUserPtr(pUser));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_USER_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iUserInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyUsersForCreditBatch);
	SetStatement(NULL);

	return;
}

//
// GetManyUsers
//

#define ORA_USER_ARRAYSIZE 20

// nsacco 07/06/99 added siteid and co_partnerid
static const char *SQL_GetManyUsers =
 "select	/*+ index(u rusers_pk_qio ) */				\
			u.id,										\
			u.user_state,								\
			u.userid,									\
			u.email,									\
			TO_CHAR(u.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),				\
			u.flags,									\
			NVL(u.uvrating, -99999),					\
			NVL(u.uvdetail, 0),							\
			u.country_id,								\
			feedback.score,				 				\
			u.siteid,									\
			u.co_partnerid,								\
			feedback.split, 							\
			feedback.flags								\
	from ebay_users u,									\
		 ebay_feedback feedback							\
	where	u.marketplace = :marketplace				\
	and													\
	(	u.id = :i00 or u.id = :i01 or					\
		u.id = :i02 or u.id = :i03 or					\
		u.id = :i04 or u.id = :i05 or					\
		u.id = :i06 or u.id = :i07 or					\
		u.id = :i08 or u.id = :i09 or					\
		u.id = :i10 or u.id = :i11 or					\
		u.id = :i12 or u.id = :i13 or					\
		u.id = :i14 or u.id = :i15 or					\
		u.id = :i16 or u.id = :i17 or					\
		u.id = :i18 or u.id = :i19						\
	)													\
	and	u.id = feedback.id (+)";


void clsDatabaseOracle::GetManyUsers(MarketPlaceId marketplace,
									 list<UserId> *pUserIdList,
									 list<UserId> *pMissingUserIdList,
									 UserList *pUsers)
{
	// Itcherator
	list<unsigned int>::iterator	iUser;

	// Things to manage our SQL statement
	bool						doneWithStatement;
	bool						doneWithList;
	int							iUserInStatement;

	// This thing is for up to ORA_USER_ARRAYSIZE slots
	// for item ids
	int							predicateUserIds[ORA_USER_ARRAYSIZE];

	// Array fetch goodies
	int					rowsFetched;
	int					n;
	int					i;
	int					rc;

	// Temporary slots for things to live in
	int					id[ORA_USER_ARRAYSIZE];
	int					user_state[ORA_USER_ARRAYSIZE];
	char				userid[ORA_USER_ARRAYSIZE][EBAY_MAX_USERID_SIZE + 1];
	char				email[ORA_USER_ARRAYSIZE][EBAY_MAX_EMAIL_SIZE + 1];
	char				userid_last_change[ORA_USER_ARRAYSIZE][32];
	sb2					userid_last_change_ind[ORA_USER_ARRAYSIZE];
	int					flags[ORA_USER_ARRAYSIZE];
	int					uvrating[ORA_USER_ARRAYSIZE];
	int					uvdetail[ORA_USER_ARRAYSIZE];
	// nsacco 07/06/99 added siteid and co_partnerid
	int					siteId[ORA_USER_ARRAYSIZE];
	int					coPartnerId[ORA_USER_ARRAYSIZE];
	int					country[ORA_USER_ARRAYSIZE];
	int					score[ORA_USER_ARRAYSIZE];
	sb2					score_ind[ORA_USER_ARRAYSIZE];
	char				split[ORA_USER_ARRAYSIZE][2];
	int					fb_flags[ORA_USER_ARRAYSIZE];

	// For Time
	time_t				userid_last_change_time;

	// For feedback
	bool				user_has_feedback;


	// An oozer
	clsUser				*pUser;
	clsFeedback			*pFeedback;

	// See if there's work to be done ;-)
	if (pUserIdList->size() < 1)
		return;


	// Let's get our statement ready
	OpenAndParse(&mpCDAGetManyUsers, SQL_GetManyUsers);

	// Ok, first we bind 
	Bind(":marketplace", (int *)&marketplace);
	Bind(":i00", &predicateUserIds[0]);
	Bind(":i01", &predicateUserIds[1]);
	Bind(":i02", &predicateUserIds[2]);
	Bind(":i03", &predicateUserIds[3]);
	Bind(":i04", &predicateUserIds[4]);
	Bind(":i05", &predicateUserIds[5]);
	Bind(":i06", &predicateUserIds[6]);
	Bind(":i07", &predicateUserIds[7]);
	Bind(":i08", &predicateUserIds[8]);
	Bind(":i09", &predicateUserIds[9]);
	Bind(":i10", &predicateUserIds[10]);
	Bind(":i11", &predicateUserIds[11]);
	Bind(":i12", &predicateUserIds[12]);
	Bind(":i13", &predicateUserIds[13]);
	Bind(":i14", &predicateUserIds[14]);
	Bind(":i15", &predicateUserIds[15]);
	Bind(":i16", &predicateUserIds[16]);
	Bind(":i17", &predicateUserIds[17]);
	Bind(":i18", &predicateUserIds[18]);
	Bind(":i19", &predicateUserIds[19]);



	// Now, define the output variables. 
	Define(1, &id[0]);
	Define(2, &user_state[0]);
	Define(3, (char *)userid, EBAY_MAX_USERID_SIZE + 1);
	Define(4, (char *)email, EBAY_MAX_EMAIL_SIZE + 1);
	Define(5, (char *)userid_last_change, sizeof(userid_last_change[0]),
		   &userid_last_change_ind[0]);
	Define(6, &flags[0]);
	Define(7, &uvrating[0]);
	Define(8, &uvdetail[0]);
	// nsacco 07/06/99 added siteid and co_partnerid
	Define(9, &siteId[0]);
	Define(10, &coPartnerId[0]);
	Define(11, &country[0]);
	Define(12, &score[0], &score_ind[0]);
	Define(13, (char*)split, sizeof(split[0]));
	Define(14, &fb_flags[0]);

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 20 possible items in it. We now
	// need to traverse our list of items, and fill these in, one
	// by one. 

	iUserInStatement	= 0;
	doneWithStatement	= false;
	doneWithList		= false;

	for (iUser = pUserIdList->begin();
		 ;
		 iUser++)
	{
		// If we're at the end of the list, fill out the rest
		// of the predicate item ids
		if (iUser == pUserIdList->end())
		{
			// iItemInStatement is where we are now, fill it 
			// out to ORA_CREDIT_BATCH_ITEM_ARRAYSIZE...
			for (;
				 iUserInStatement < ORA_USER_ARRAYSIZE;
				 iUserInStatement++)
			{
				predicateUserIds[iUserInStatement] = 0;
			}

			doneWithList		= true;
			doneWithStatement	= true;
		}
		else
		{
			predicateUserIds[iUserInStatement] = (*iUser);
			iUserInStatement++;

			// Let's see we've "filled up" a statement
			if (iUserInStatement >= ORA_USER_ARRAYSIZE)
				doneWithStatement	= true;
		}

		// If we're not "done" filling in the predicate variables
		// for the statement, then just continue to get to the next
		// item in the list.
		if (!doneWithStatement)
			continue;

		// Ah! We're done filling in the predicates, so let's 
		// Execute the statement.
		Execute();

		// Now, we do the standard array fetch thing.
		rowsFetched = 0;
		do
		{
			rc = ofen((struct cda_def *)mpCDACurrent,
					  ORA_USER_ARRAYSIZE);

			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				ocan((struct cda_def *)mpCDACurrent);
				Close(&mpCDAGetManyUsers);
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{
				// If the item id is 0, then this row doesn't count
				if (id[i] == 0)
					continue;

				if (userid_last_change_ind[i] != -1)
					ORACLE_DATEToTime(userid_last_change[i], 
									  &userid_last_change_time);
				else
					userid_last_change_time = 0;

				// nsacco 07/06/99
				if (siteId[i] == -1)
				{
					siteId[i] = SITE_EBAY_MAIN;
				}

				if (coPartnerId[i] == -1)
				{
					coPartnerId[i] = PARTNER_EBAY;
				}

				// Now everything is where it's supposed
				// to be. Fill in the item
				// nsacco 07/06/99 - create with site and partner
				pUser = new clsUser(marketplace,	// int marketplace
					id[i],							// int  id,
					userid[i],						// char *pUserId,
					email[i],						// char *pEmail,
					(UserStateEnum)user_state[i],	// UserStateEnum state,
					(char *)"",						// char *pPassword,
					(char *)"",						// char *pSalt,
					0L,								// long lastModified,
					userid_last_change_time,		// long useridLastChanged,
					flags[i],						// int user_flags,
					country[i],						// int country_id,
					uvrating[i],					// int UVRating,
					uvdetail[i],					// int UVDetail,
					siteId[i],						// int siteId,
					coPartnerId[i]					// int coPartnerId
				);


				if (score_ind[i] == -1)
				{
					user_has_feedback	= false;
					score[i]			= 0;
				}
				else
					user_has_feedback	= true;

				pFeedback	= new clsFeedback(id[i],
											  user_has_feedback,
											  score[i],
											  fb_flags[i],
											  false,
											  0,
											  NULL,
											  split[i][0]=='1');

				pUser->SetFeedback(pFeedback);

				pUsers->push_back(clsUserPtr(pUser));
			}
		} while (!CheckForNoRowsFound());

		// Ok, we've handled ORA_CREDIT_BATCH_USER_ARRAYSIZE items from
		// the array. If we're not done yet, let's reset some things,
		// and move on, otherwise, just break!
		if (doneWithList)
			break;
		
		iUserInStatement	= 0;
		doneWithStatement	= false;
	}	

	// Clean up
	Close(&mpCDAGetManyUsers);
	SetStatement(NULL);

	return;
}

//
// Check whether an email is anonymous email
//
const char *SQL_GetAnonymousEmail = 
"select	email								\
	from	ebay_anon_emails				\
	where	email = :email";					

bool clsDatabaseOracle::IsAnonymousEmail(char* pEmail)
{
	char	Email[64];
	bool	IsAnon = true;
	char*	pTemp;
	
	// invalid pointer, but don't know what to do
	if (pEmail == NULL)
		return IsAnon;
	
	// emails in the table have only domain name
	// so only get the domail from the input
	pTemp = strchr(pEmail, '@');
	if (pTemp)
	{
		pTemp++;
	}
	else
	{
		pTemp = pEmail;
	}

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetAnonymousEmail);

	Bind(":email", pTemp);

	Define(1, Email, sizeof(Email));

	Execute();
	Fetch();

	if (CheckForNoRowsFound())
	{
		// don't find the email from the table, it is no an anonymous email
		IsAnon = false;
	}

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return IsAnon;
}

static const char *SQL_GetLastCountAndTime =
"select req_info_count,								\
	TO_CHAR(req_info_date, 'YYYY-MM-DD HH24:MI:SS'),\
	TO_CHAR(sysdate, 'YYYY-MM-DD HH24:MI:SS')\
 from ebay_user_info where id = :id";

static const char *SQL_UpdateReceiveInfo =
"update ebay_user_info								\
	set req_info_count = :req_info_count,			\
	req_info_date = sysdate,						\
	req_info_host = :host							\
 where id = :id";

static const int sRequestTimeout = 3;
static const int sMaxRequest = 50;
static const int sMaxReqCounter = 9990;

// This returns true if we are allowed to send (id) information
// about another user, and false if we aren't -- it also updates
// our recors -- incrementing their 'requested count' by 1, and
// storing their host (and possibly alerting us if someone is
// requesting too much).
int clsDatabaseOracle::CanReceiveInfo(int id,
									   const char *pHost)
{
	bool isOkay = true;
	bool isExceeded = false;
	int count;
	char when[32];
	char whenOracle[32];
	sb2		when_ind;
	sb2		count_ind;
	time_t theTime;
	time_t oracleTime;

	OpenAndParse(&mpCDAGetLastCountAndTime, SQL_GetLastCountAndTime);

	Bind(":id", &id);

	Define(1, &count, &count_ind);
	Define(2, when, sizeof (when), &when_ind);
	Define(3, whenOracle, sizeof (whenOracle));

	Execute();

	if (CheckForNoRowsFound())
		return false;

	Fetch();

	if (count_ind == -1 || when_ind == -1)
	{
		isOkay = true;
	}
	else
	{
		ORACLE_DATEToTime(when, &theTime);
		ORACLE_DATEToTime(whenOracle, &oracleTime);

		// If they haven't waited enough time, they can't do it!
		if ((oracleTime - theTime) < sRequestTimeout)
			isOkay = false;

		if (count >= sMaxRequest)
		{
			if (count == sMaxRequest)
				isExceeded = true;
			isOkay = false;
		}
	}

	Close(&mpCDAGetLastCountAndTime);
	SetStatement(NULL);

	// make sure count is not going to overflow
		if (count >= sMaxReqCounter)
			count = sMaxReqCounter;
		else
			count = count + 1;

	// Now update what we know about them!

	OpenAndParse(&mpCDAUpdateReceiveInfo, SQL_UpdateReceiveInfo);

	Bind(":id", &id);
	Bind(":host", pHost, strlen(pHost) + 1);
	Bind(":req_info_count", &count);

	Execute();
	Commit();

	Close(&mpCDAUpdateReceiveInfo);
	SetStatement(NULL);

	// If they've exceeded the allowable count, notify someone.
	if (isExceeded)
		return -1;

	if (isOkay)
		return 0;
	else
		return 1;
}

static const char *SQL_ResetCanReceive =
"update ebay_user_info set req_info_count = 0	\
	where id = :id";

// Just reset it to 0.
void clsDatabaseOracle::ResetCanReceiveInfo(int id)
{
	OpenAndParse(&mpCDAOneShot, SQL_ResetCanReceive);

	Bind(":id", &id);

	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}

//
// Set the user flags. (Set some or all through clsUser.)
//
static const char *SQL_SetUserFlags = 
 "update ebay_users			\
	set flags = :flags		\
	where id = :id";

void clsDatabaseOracle::SetUserFlags(int id, int flags)
{
	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_SetUserFlags);

	// Bind
	Bind(":flags", &flags);
	Bind(":id", &id);

	// Do it!
	Execute();
	Commit();

	// Close 
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// 
// Get the user flags. (Get one or all through clsUser.)
//
static const char *SQL_GetUserFlags = 
"select	flags from ebay_users where id = :id";

int clsDatabaseOracle::GetUserFlags(int id)
{
	int					flags = 0;  

	OpenAndParse(&mpCDAOneShot, SQL_GetUserFlags);

	Bind(":id", &id);

	Define(1, &flags);

	// Do it
	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return flags;
}


#define ORA_USER_ID_ARRAYSIZE 10000

//
// Get all notified validate Users 
//
static const char SQL_ActiveUsers[] =
"select id, flags from ebay_users "
"where marketplace =:marketplace "
" and  user_state = 1 "
" and  flags >= 2 order by id";

void clsDatabaseOracle::GetActiveUsers( MarketPlaceId marketplace,
										vector<int> *pvIds)
{
	int		id[ORA_USER_ID_ARRAYSIZE];
	int		flags[ORA_USER_ID_ARRAYSIZE];
	int		i, n, rowsFetched, rc;

	OpenAndParse(&mpCDAOneShot,
				 SQL_ActiveUsers);

	// Binds and Defines
	// Ok, let's do some binds
	Bind(":marketplace", (int *)&marketplace);

	// And zee defines
	Define(1, id);
	Define(2, flags);

	// Execute
	Execute();

	// Now, we do the standard array fetch thing.
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_USER_ID_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_USER_ID_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			if (flags[i] & UserFlagChangesToAgreement)
				pvIds->push_back(id[i]);
		}
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

// Get sellers newer than the specified age and with more than
// the specified number of items.
void clsDatabaseOracle::GetNewBigSellers(MarketPlaceId marketplace,
										 int maxAge,
										 int minItems,
										 vector<int> &userVector)
{
	static const char SQL_NewBigSellers[] =
		"select info.id "
		" from ebay_user_info info, ebay_seller_item_lists itemlist"
		" where"
//		"	info.marketplace = :marketplace and"
		"   info.creation > sysdate - :maxAge and"
		"   itemlist.id = info.id and "
		"   itemlist.item_count >= :minItems";

	OpenAndParse(&mpCDAOneShot,
				 SQL_NewBigSellers);

	int id[ORA_USER_ID_ARRAYSIZE];

//	Bind(":marketplace", (int *)&marketplace);
	Bind(":maxAge", &maxAge);
	Bind(":minItems", &minItems);

	Define(1, id);
	Execute();
	
	int rowsFetched = 0;
	cda_def *pCDACurrent = (cda_def *)mpCDACurrent;
	do
	{
		int rc = ofen(pCDACurrent, ORA_USER_ID_ARRAYSIZE);
		if ((rc < 0 || rc >= 4)  && 
			pCDACurrent->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan(pCDACurrent);
			break;
		}
		int n = pCDACurrent->rpc - rowsFetched;
		rowsFetched += n;
		copy(id, id + n, back_inserter(userVector));
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);
}

//
// Get all users regardless of everything 
//
static const char SQL_AllUsers[] =
"select id from ebay_users";
//" where marketplace =:marketplace";
//" order by id";
static const char SQL_AllUsersInRange[] =
"select id from ebay_users where id >= :minid and id < :maxid";

void clsDatabaseOracle::GetAllUsers( MarketPlaceId marketplace,	vector<unsigned int> *pvIds,
									int minId, int maxId)
{
	int		id[ORA_USER_ID_ARRAYSIZE];
//	int		flags[ORA_USER_ID_ARRAYSIZE];
	int		i, n, rowsFetched, rc;

	if ((minId != maxId) && (minId < maxId))
		OpenAndParse(&mpCDAOneShot, SQL_AllUsersInRange);
	else
		OpenAndParse(&mpCDAOneShot, SQL_AllUsers);

	// Binds and Defines
	if ((minId != maxId) && (minId < maxId))
	{
	// Ok, let's do some binds
		Bind(":minid", (int *)&minId);
		Bind(":maxid", (int *)&maxId);
	}

	// And zee defines
	Define(1, id);

	// Execute
	Execute();

	// Now, we do the standard array fetch thing.
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent, ORA_USER_ID_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && ((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_USER_ID_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++) pvIds->push_back(id[i]);

	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// Check whether a user participated a survey
//
const char *SQL_GetUserIdBySurvey = 
"select	user_id								\
	from	ebay_user_survey_record			\
	where	survey_id = :survey_id			\
	and		user_id    = :user_id";					

bool clsDatabaseOracle::IsParticipatedSurvey(int survey_id, int user_id)
{
	bool	IsParticipated;
	int		id;

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetUserIdBySurvey);

	// Ok, let's do some binds
	Bind(":survey_id", &survey_id);
	Bind(":user_id", &user_id);

	Define(1, &id);

	Execute();
	Fetch();

	IsParticipated = !CheckForNoRowsFound();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return IsParticipated;
}

//
// AddUserSurveyRecord
//
//	Adds a user's alias to the past aliases table
//
const char *SQL_AddUserSurveyRecord =
" insert into ebay_user_survey_record				\
	(	survey_id,										\
		user_id									\
	)												\
	values											\
	(	:survey_id,									\
		:user_id									\
	)";

void clsDatabaseOracle::AddUserToSurveyRecord(int survey_id, int user_id)
{

	OpenAndParse(&mpCDAOneShot,
				 SQL_AddUserSurveyRecord);

	Bind(":user_id", &user_id);
	Bind(":survey_id", &survey_id);
	
	Execute();
	
	Commit();
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


// Top Sellers

//
// Get all Top Sellers
//
static const char *SQL_GetAllTopSellers =
  "select id				\
  from ebay_user_info		\
  where topsellerlevel > 0	\
  order by email";

static const char *SQL_GetSelectedTopSellers =
  "select id						\
  from ebay_user_info				\
  where topsellerlevel = :thelevel	\
  order by email";


void clsDatabaseOracle::GetTopSellers(MarketPlaceId marketplace, int level, vector<int> *pvIds)
{
	int		id[ORA_USER_ID_ARRAYSIZE];
	//	int		id;
	int		rowsFetched;
	int		rc, n, i;
	//	int		topSellerLevel;
	
	if(level == -1) {
		// The usual suspects
		OpenAndParse(&mpCDAOneShot,
			SQL_GetAllTopSellers);
	} else {
		// The usual suspects
		OpenAndParse(&mpCDAOneShot,
			SQL_GetSelectedTopSellers);

		// Ok, let's do some binds
		Bind(":thelevel", &level);
	}
	
	// And zee defines
	Define(1, id);

	Execute();


/*	do
	{
		Fetch();

		if (CheckForNoRowsFound())
			break;

		pvIds->push_back(id);
	} while (1==1);

*/
	// Now, we do the standard array fetch thing.
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
				  ORA_USER_ID_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_USER_ID_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
				pvIds->push_back(id[i]);
		}
	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// Get all unconfirmed users
//
static const char SQL_GetUnconfirmedUsers[] =
	"select         u.id                                    "
	"from           ebay_users u,                           "
	"               ebay_user_info ui                       "
	"where          marketplace = :marketplace     			"
	"and            user_state = 2                          "
	"and            u.id = ui.id                            "
	"and		    ui.creation < sysdate - :age		    "
	"order by       ui.creation                             ";

void clsDatabaseOracle::GetUnconfirmedUsers(MarketPlaceId marketplace,  
	vector<int> &vIds, 
	int age)
{
	int             id[ORA_USER_ID_ARRAYSIZE];
	int             n, rowsFetched, rc;

	OpenAndParse(&mpCDAOneShot, SQL_GetUnconfirmedUsers);

	// Binds and Defines
	// Ok, let's do some binds
	Bind(":marketplace", (int *)&marketplace);
	Bind(":age", &age);

	// And zee defines
	Define(1, id);

	// Execute
	Execute();

	// Now, we do the standard array fetch thing.
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,
			ORA_USER_ID_ARRAYSIZE);

		if ((rc < 0 || rc >= 4)  &&
			((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
		{
			Check(rc);
			ocan((struct cda_def *)mpCDACurrent);
			break;
		}

		// rpc is cumulative, so find out how many rows to display this time
		// (always <= ORA_USER_ID_ARRAYSIZE).
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		copy(id, id + n, back_inserter(vIds));

	} while (!CheckForNoRowsFound());

	Close(&mpCDAOneShot);
	SetStatement(NULL);

}
