/*	$Id: clsDatabaseOracleBulletinBoard.cpp,v 1.11 1998/12/06 05:31:52 josh Exp $	*/
//
//	File:	clsDatabaseOracleBulletinBoard.cc
//
//	Class:	clsDatabaseOracleUsers
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//  All bulletin board access for Oracle.
//
// Modifications:
//

#include "eBayKernel.h"
#include "clsEnvironment.h"

// GetBulletinBoardControlEntries
//
//	Gets all the control entries for the bulletin boards. Note the use
//	of the "order by" to make sure we get them in the _right_ order.
//

static char *SQL_GetBulletinBoardCount =
"select count(*)										\
 from	ebay_bulletin_board_control";

static char *SQL_GetBulletinBoardMaxDescriptionLength = 
"select	max(board_description_len)						\
 from	ebay_bulletin_board_control";

static char *SQL_GetBulletinBoardControlEntries =
"select	board_id,										\
		board_name,										\
		board_short_name,								\
		board_short_description,						\
		board_pic,										\
		board_description_len,							\
		board_max_post_count,							\
		board_max_post_age,								\
		board_flags,									\
		board_type,										\
		TO_CHAR(board_last_post_time,					\
				'YYYY-MM-DD HH24:MI:SS'),				\
		board_description								\
 from	ebay_bulletin_board_control						\
 order by	board_id";

void clsDatabaseOracle::GetBulletinBoardControlEntries(BulletinBoardVector *pvBoards)
{
	// Oracle return, for Array fetch
	int			rc;

	// Number of boards
	int			boardCount;
	int			i;

	// Maximum description size
	int			maxDescriptionSize;

	// Arrays of things
	int				*pBoardIds;
	char			*pBoardNames;
	char			*pBoardShortNames;
	char			*pBoardShortDescriptions;
	char			*pBoardPictures;
	sb2				*pBoardPictureInds;
	int				*pBoardDescriptionLens;
	unsigned char	*pBoardDescriptions;
	sb2				*pBoardDescriptionInds;
	int				*pBoardMaxPostCounts;
	int				*pBoardMaxPostAges;
	unsigned int	*pBoardFlags;
	unsigned int	*pBoardTypes;
	char			*pBoardLastPostTimes;

	// And the current things
	int				*pBoardId;
	char			*pBoardName;
	char			*pBoardShortName;
	char			*pBoardShortDescription;
	char			*pBoardPicture;
//	sb2				*pBoardPictureInd;
	int				*pBoardDescriptionLen;
	unsigned char	*pBoardDescription;
	sb2				*pBoardDescriptionInd;
	int				*pBoardMaxPostCount;
	int				*pBoardMaxPostAge;
	unsigned int	*pBoardFlag;
	unsigned int	*pBoardType;
	char			*pBoardLastPostTime;

	// To be efficent, these are the _real_ data we stick
	// in the clsBulletinBoard objects.
	char			*pTheBoardDescription;

	time_t			lastPostTime;

	// And, the object!
	clsBulletinBoard	*pBoard;


	// First, we need to know how many.
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBulletinBoardCount);

	Define(1, &boardCount);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// And...how big!
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBulletinBoardMaxDescriptionLength);

	Define(1, &maxDescriptionSize);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);


	// Now, allocate...
	pBoardIds				= new int[boardCount];
	pBoardNames				= 
		new char[boardCount * BULLETIN_BOARD_MAX_NAME_LENGTH];
	pBoardShortNames		= 
		new char[boardCount * BULLETIN_BOARD_MAX_SHORT_NAME_LENGTH];
	pBoardShortDescriptions	=
		new char[boardCount * BULLETIN_BOARD_MAX_SHORT_DESC_LENGTH];
	pBoardPictures			=
		new char[boardCount * BULLETIN_BOARD_MAX_PICTURE_LENGTH];
	pBoardPictureInds		= new sb2[boardCount];
	pBoardDescriptionLens	= new int[boardCount];
	pBoardDescriptions		= 
		new unsigned char[boardCount * (maxDescriptionSize + 1)];
	pBoardDescriptionInds	= new sb2[boardCount];
	pBoardMaxPostCounts		= new int[boardCount];
	pBoardMaxPostAges		= new int[boardCount];
	pBoardFlags				= new unsigned int[boardCount];
	pBoardTypes				= new unsigned int[boardCount];
	pBoardLastPostTimes		= new char[boardCount * 32];

	// Let's get them! 
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetBulletinBoardControlEntries);

	Define(1, pBoardIds);
	Define(2, pBoardNames, BULLETIN_BOARD_MAX_NAME_LENGTH);
	Define(3, pBoardShortNames, BULLETIN_BOARD_MAX_SHORT_NAME_LENGTH);
	Define(4, pBoardShortDescriptions, BULLETIN_BOARD_MAX_SHORT_DESC_LENGTH);
	Define(5, pBoardPictures, BULLETIN_BOARD_MAX_PICTURE_LENGTH,
		   pBoardPictureInds);
	Define(6, pBoardDescriptionLens);
	Define(7, pBoardMaxPostCounts);
	Define(8, pBoardMaxPostAges);
	Define(9, pBoardFlags);
	Define(10, pBoardTypes);
	Define(11, pBoardLastPostTimes, 32);
	DefineLongRaw(12, pBoardDescriptions, maxDescriptionSize + 1,
				  pBoardDescriptionInds);

	Execute();

	rc = ofen((struct cda_def *)mpCDACurrent,
			  boardCount);

	Check(rc);

	// Now, let's make entries out of them!
	if (((struct cda_def *)mpCDACurrent)->rpc < boardCount)
		boardCount = ((struct cda_def *)mpCDACurrent)->rpc;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	for (i = 0,
		 pBoardId				= pBoardIds,
		 pBoardName				= pBoardNames,
		 pBoardShortName		= pBoardShortNames,
		 pBoardShortDescription	= pBoardShortDescriptions,
		 pBoardPicture			= pBoardPictures,
//		 pBoardPictureInd		= pBoardPictureInds,
		 pBoardDescriptionLen	= pBoardDescriptionLens,
		 pBoardDescription		= pBoardDescriptions,
		 pBoardDescriptionInd	= pBoardDescriptionInds,
		 pBoardMaxPostCount		= pBoardMaxPostCounts,
		 pBoardMaxPostAge		= pBoardMaxPostAges,
		 pBoardFlag				= pBoardFlags,
		 pBoardType				= pBoardTypes,
		 pBoardLastPostTime		= pBoardLastPostTimes;
		 i < boardCount;
		 i++,
		 pBoardId++,
		 pBoardName = pBoardName + BULLETIN_BOARD_MAX_NAME_LENGTH,
		 pBoardShortName = pBoardShortName + BULLETIN_BOARD_MAX_SHORT_NAME_LENGTH,
		 pBoardShortDescription = 
			pBoardShortDescription + BULLETIN_BOARD_MAX_SHORT_DESC_LENGTH,
		 pBoardPicture = 
			pBoardPicture + BULLETIN_BOARD_MAX_PICTURE_LENGTH,
//		 pBoardPictureInd++,
		 pBoardDescriptionLen++,
		 pBoardDescription = pBoardDescription + maxDescriptionSize + 1,
		 pBoardDescriptionInd++,
		 pBoardMaxPostCount++,
		 pBoardMaxPostAge++,
		 pBoardFlag++,
		 pBoardType++,
		 pBoardLastPostTime++)
	{
		// Let's make just-so copies of the Names, etc
		if (*pBoardDescriptionInd != -1)
		{
			pTheBoardDescription	= new char[(*pBoardDescriptionLen) + 1];

			memcpy(pTheBoardDescription, pBoardDescription, *pBoardDescriptionLen);
			*(pTheBoardDescription + *pBoardDescriptionLen) = 0x00;
		}
		else
		{
			pTheBoardDescription	= NULL;
		}

		ORACLE_DATEToTime(pBoardLastPostTime, &lastPostTime);

		pBoard	= new clsBulletinBoard(*pBoardId,
									   pBoardName,
									   pBoardShortName,
									   pBoardShortDescription,
									   pBoardPicture,
									   pTheBoardDescription,
									   *pBoardMaxPostCount,
									   *pBoardMaxPostAge,
									   *pBoardFlag,
									   *pBoardType,
									   lastPostTime);
		pvBoards->push_back(pBoard);
		delete[] pTheBoardDescription;
	}

	// Now, clean up!
	delete[]	pBoardIds;
	delete[]	pBoardNames;
	delete[]	pBoardShortNames;
	delete[]	pBoardDescriptionLens;
	delete[]	pBoardDescriptions;
	delete[]	pBoardMaxPostCounts;
	delete[]	pBoardMaxPostAges;
	delete[]	pBoardFlags;
	delete[]	pBoardShortDescriptions;
	delete[]	pBoardPictureInds;
	delete[]	pBoardDescriptionInds;
	delete[]	pBoardPictures;
	delete[]	pBoardTypes;
	delete[]	pBoardLastPostTimes;

	return;
}

//
// AddBulletinBoardControlEntry
//
//	Using the passed clsBulletinBoard object, creates a new
//	bulletin board control entry. The bulletin board "id" is
//	retrieved from a sequence.
//
static const char *SQL_GetNextBulletinBoardId =
"select	ebay_bulletin_board_seq.nextval from dual";

static const char *SQL_InsertBulletinBoardControlEntry =
"insert into ebay_bulletin_board_control					\
	(	board_id,											\
		board_name,											\
		board_short_name,									\
		board_short_description,							\
		board_pic,											\
		board_description_len,								\
		board_description,									\
		board_max_post_count,								\
		board_max_post_age,									\
		board_flags											\
		board_type,											\
		board_last_post_time								\
	)														\
	values													\
	(	:id,												\
		:bname,												\
		:bsname,											\
		:bsdesc,											\
		:bpic,												\
		:desclen,											\
		:bdesc,												\
		:mpostcnt,											\
		:mpostage,											\
		:bflags												\
		:btype,												\
		sysdate												\
	)";

void clsDatabaseOracle::AddBulletinBoardControlEntry(clsBulletinBoard *pBoard)
{
	BulletinBoardId	boardId;
	int				descLen;
	int				maxPostCount;
	int				maxPostAge;
	unsigned int	flags;
	unsigned int	type;

	descLen	= strlen(pBoard->GetDescription());

	OpenAndParse(&mpCDAOneShot,
				 SQL_GetNextBulletinBoardId);

	Define(1, (int *)&boardId);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	pBoard->SetId(boardId);

	// Now, actually add it. We need to get some things out
	// of the object to bind them properly
	maxPostCount	= pBoard->GetMaxPostCount();
	maxPostAge		= pBoard->GetMaxPostAge();
	flags			= pBoard->GetControlFlags();
	type			= pBoard->GetType();

	OpenAndParse(&mpCDAOneShot,
				 SQL_InsertBulletinBoardControlEntry);

	Bind(":id", (int *)&boardId);
	Bind(":bname", (char *)pBoard->GetName());
	Bind(":bsname", (char *)pBoard->GetShortName());
	Bind(":bsdesc", (char *)pBoard->GetShortDescription());
	Bind(":bpic", (char *)pBoard->GetPicture());
	Bind(":desclen", &descLen);
	BindLongRaw(":bdesc", (unsigned char *)pBoard->GetDescription(), descLen);
	Bind(":mpostcnt", &maxPostCount);
	Bind(":mpostage", &maxPostAge);
	Bind(":bflags", &flags);
	Bind(":btype", &type);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// UpdateBulletinBoardControlEntry
//
static const char *SQL_UpdateBulletinBoardControlEntry =
"update	ebay_bulletin_board_control			\
 set	board_name = :bname,				\
		board_short_name = :bsname,			\
		board_short_description = :bsdesc,	\
		board_pic = :bpic,					\
		board_description_len = :desclen,	\
		board_description = :bdesc,			\
		board_max_post_count = :mpostcnt,	\
		board_max_post_age = :mpostage,		\
		board_flags = :bflags,				\
		board_type = :btype,				\
		board_last_post_time = sysdate		\
 where	board_id = :bid";

void clsDatabaseOracle::UpdateBulletinBoardControlEntry(clsBulletinBoard *pBoard)
{
	BulletinBoardId	boardId;
	int				descLen;
	int				maxPostCount;
	int				maxPostAge;
	unsigned int	flags;
	unsigned int	type;

	// Now, actually add it. We need to get some things out
	// of the object to bind them properly
	boardId			= pBoard->GetId();
	maxPostCount	= pBoard->GetMaxPostCount();
	maxPostAge		= pBoard->GetMaxPostAge();
	flags			= pBoard->GetControlFlags();
	descLen			= strlen(pBoard->GetDescription());
	type			= pBoard->GetType();

	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateBulletinBoardControlEntry);

	Bind(":bid", (int *)&boardId);
	Bind(":bname", (char *)pBoard->GetName());
	Bind(":bsname", (char *)pBoard->GetShortName());
	Bind(":bsdesc", (char *)pBoard->GetShortDescription());
	Bind(":bpic", (char *)pBoard->GetPicture());
	Bind(":desclen", &descLen);
	BindLongRaw(":bdesc", (unsigned char *)pBoard->GetDescription(), descLen);
	Bind(":mpostcnt", &maxPostCount);
	Bind(":mpostage", &maxPostAge);
	Bind(":bflags", &flags);
	Bind(":btype", &type);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

		

// 
// AddBulletinBoardEntry
//
static const char *SQL_AddBulletinBoardEntry =
  "insert into ebay_bulletin_boards			\
	(	board_id,							\
		when,								\
		user_id,							\
		host,								\
		entry_len,							\
		entry								\
	)										\
	values									\
	(										\
		:boardid,							\
		sysdate,							\
		:userid,							\
		:host,								\
		:entrylen,							\
		:entry								\
	)";

static const char *SQL_UpdateBBLastPostTime =
	"update ebay_bulletin_board_control		\
		set board_last_post_time = sysdate	\
	 where	board_id = :boardid";

void clsDatabaseOracle::AddBulletinBoardEntry(BulletinBoardId board_id,
											   int id,
											   char *pEntry)
{
	int								entryLen;

	// The usual suspects
	OpenAndParse(&mpCDAAddBBEntry, SQL_AddBulletinBoardEntry);

	// Ok, let's do some binds
	Bind(":boardid", &board_id);
	Bind(":userid", &id);
	Bind(":host", gApp->GetEnvironment()->GetRemoteAddr());

	entryLen	= strlen(pEntry);
	Bind(":entrylen", &entryLen);
	BindLongRaw(":entry", (unsigned char *)pEntry, entryLen);

	// Do it
	Execute();
	Commit();

	// Clean up 
	Close(&mpCDAAddBBEntry);
	SetStatement(NULL);

	// Update the "last post time"
	OpenAndParse(&mpCDAUpdateBBLastPostTime, SQL_UpdateBBLastPostTime);

	Bind(":boardid", &board_id);

	Execute();
	Commit();

	Close(&mpCDAUpdateBBLastPostTime);
	SetStatement(NULL);

	return;
}

//
// GetAllBulletinBoardEntries
//
//	Well, this is the height of something ;-)
//
static const char *SQL_GetBulletinBoardStatistics =
 "select	count(*),						\
			max(entry_len)					\
  from		ebay_bulletin_boards			\
  where		board_id = :boardid				\
  and		when >=							\
	TO_DATE(:limit, 'YYYY-MM-DD HH24:MI:SS')";

static const char *SQL_GetBulletinBoardEntries =
"select	TO_CHAR(bb.when, 'YYYY-MM-DD HH24:MI:SS'), "
		"bb.user_id, "
		"u.userid, "
		"u.email, "
		"TO_CHAR(u.userid_last_change, 'YYYY-MM-DD HH24:MI:SS'), "
		"u.flags, "
		"fb.score, "
		"bb.host, "
		"bb.entry_len, "
		"bb.entry "
"from	ebay_bulletin_boards bb, "
		"ebay_feedback fb, "
		"ebay_users u "
"where	bb.board_id = :boardid "
  "and	bb.user_id = u.id (+) "
  "and	bb.user_id = fb.id (+) "
  "and	when >= TO_DATE(:limit, 'YYYY-MM-DD HH24:MI:SS') "
"order by when desc";

void clsDatabaseOracle::GetAllBulletinBoardEntries(BulletinBoardId board_id,
												   BulletinBoardEntryList *plEntries,
												   int maxPostAgeSeconds)
{
	int								rc;
	
	int								boardCount = 0;
	int								maxEntrySize = 0;

	time_t							nowTime;
	time_t							limitTime;
	char							limitCTime[32];

	// Arrays for...you guessed it...
	int								whenLen;
	char							*pWhens;
	int								*pIds;
	char							*pUserIds;
	sb2								*pUserIdInds;
	char							*pEmails;
	sb2								*pEmailInds;
	char							*pUserIdLastChangeds;
	sb2								*pUserIdLastChangedInds;
	int								*pUserFlags;
	int								*pScores;
	sb2								*pScoreInds;
	char							*pHosts;
	int								*pEntryLens;
	char							*pEntries;

	char							*pCurrentWhen;
	int								*pCurrentId;
	char							*pCurrentUserId;
//	sb2								*pCurrentUserIdInd;
	char							*pCurrentEmail;
//	sb2								*pCurrentEmailInd;
	char							*pCurrentUserIdLastChanged;
//	sb2								*pCurrentUserIdLastChangedInd;
	int								*pCurrentUserFlags;
	int								*pCurrentScore;
	sb2								*pCurrentScoreInd;
	char							*pCurrentHost;
	int								*pCurrentEntryLen;
	char							*pCurrentEntry;
	int								i;

	time_t							theTime;
	int								score;
	char							*pString;
	char							*pUserId;
	char							*pEmail;
	time_t							theLastChangeTime;

	clsBulletinBoardEntry			*pEntry;


	//
	// Let's figure out our limits here
	//
	nowTime	= time(NULL);

	if (maxPostAgeSeconds == 0)
		limitTime	= nowTime - (30 * ONE_DAY);
	else
		limitTime	= nowTime - maxPostAgeSeconds;

	TimeToORACLE_DATE(limitTime, limitCTime);

	// This is part one of the gross of the gross. Since we're
	// going to use array fetch, we need to allocate buffers
	// as big as the biggest field we'll get. This could be
	// incredibly gross. Anyway, this SQL will get the 
	// pertinent info for us.

	OpenAndParse(&mpCDAGetBulletinBoardStatistics,
				 SQL_GetBulletinBoardStatistics);

	Bind(":boardid", &board_id);
	Bind(":limit", limitCTime);

	Define(1, &boardCount);
	Define(2, &maxEntrySize);

	ExecuteAndFetch();
	Close(&mpCDAGetBulletinBoardStatistics);
	SetStatement(NULL);

	// If there aren't any entries, we're done
	if (boardCount == 0)
		return;

	// Gross of the gross
	whenLen					= strlen("YYYY-MM-DD HH:MM:SS") + 1;
	pWhens					= new char[whenLen * boardCount];
	pIds					= new int[boardCount];
	pUserIds				= new char[(EBAY_MAX_USERID_SIZE + 1) * boardCount];
	pUserIdInds				= new sb2[boardCount];
	pEmails					= new char[(EBAY_MAX_EMAIL_SIZE + 1) * boardCount];
	pEmailInds				= new sb2[boardCount];
	pUserIdLastChangeds		= new char[whenLen * boardCount];
	pUserIdLastChangedInds	= new sb2[boardCount];
	pUserFlags				= new int[boardCount];
	pScores					= new int[boardCount];
	pScoreInds				= new sb2[boardCount];
	pHosts					= new char[256 * boardCount];
	pEntryLens				= new int[boardCount];
	pEntries				= new char[(maxEntrySize + 1) * boardCount];
	
	memset(pWhens, 0x00, whenLen * boardCount);
	memset(pEntries, 0x00, (maxEntrySize + 1) * boardCount);
	memset(pHosts, 0x00, 256 * boardCount);



	// Weird of the weird
	OpenAndParse(&mpCDAGetBulletinBoardEntries,
				 SQL_GetBulletinBoardEntries);

	// Bind
	Bind(":boardid", &board_id);
	Bind(":limit", limitCTime);

	// Defines
	Define(1, pWhens, whenLen);
	Define(2, pIds);
	Define(3, pUserIds, (EBAY_MAX_USERID_SIZE + 1), pUserIdInds);
	Define(4, pEmails, (EBAY_MAX_EMAIL_SIZE + 1), pEmailInds);
	Define(5, pUserIdLastChangeds, whenLen, pUserIdLastChangedInds);
	Define(6, pUserFlags);
	Define(7, pScores, pScoreInds);
	Define(8, pHosts, 256);
	Define(9, pEntryLens);
	DefineLongRaw(10, (unsigned char *)pEntries, maxEntrySize + 1);

	Execute();

	rc = ofen((struct cda_def *)mpCDACurrent,
			  boardCount);

	Check(rc);

	// Ok, now let's loop through and create the right
	// entries for the vector

	// Now, between the time we got the counts and now, the number of 
	// rows could have decreased due to trimming. We avoid ugliness by
	// setting boardcount to the LESSER of the count of rows processed
	// and the prior boardcount. It can never be GREATER, because 
	// of the parameter passed to ofen. 
	if (((struct cda_def *)mpCDACurrent)->rpc < boardCount)
		boardCount = ((struct cda_def *)mpCDACurrent)->rpc;

	for (i = 0,
		 pCurrentWhen					= pWhens,
		 pCurrentId						= pIds,
		 pCurrentUserId					= pUserIds,
//		 pCurrentUserIdInd				= pUserIdInds,
		 pCurrentEmail					= pEmails,
//		 pCurrentEmailInd				= pEmailInds,
		 pCurrentUserIdLastChanged		= pUserIdLastChangeds,
//		 pCurrentUserIdLastChangedInd	= pUserIdLastChangedInds,
		 pCurrentUserFlags				= pUserFlags,
		 pCurrentScore					= pScores,
		 pCurrentScoreInd				= pScoreInds,
		 pCurrentHost					= pHosts,
		 pCurrentEntryLen				= pEntryLens,
		 pCurrentEntry					= pEntries;
		 i < boardCount;
		 i++,
		 pCurrentWhen		+= whenLen,
		 pCurrentId++,
		 pCurrentUserId		+= (EBAY_MAX_USERID_SIZE + 1),
//		 pCurrentUserIdInd++,
		 pCurrentEmail		+= (EBAY_MAX_EMAIL_SIZE + 1),
//		 pCurrentEmailInd++,
		 pCurrentUserIdLastChanged	+= whenLen,
//		 pCurrentUserIdLastChangedInd++,
		 pCurrentUserFlags++,
		 pCurrentScore++,
		 pCurrentScoreInd++,
		 pCurrentHost		+= 256,
		 pCurrentEntryLen++,
		 pCurrentEntry		+= maxEntrySize + 1)
	{

		ORACLE_DATEToTime(pCurrentWhen, &theTime);

		int actualLen = min(maxEntrySize, (*pCurrentEntryLen));
		pString	= new char[actualLen + 1];
		memset(pString, 0x00, actualLen + 1);
		strncpy(pString, pCurrentEntry, actualLen);

		pUserId	= new char[strlen(pCurrentUserId) + 1];
		strcpy(pUserId, pCurrentUserId);

		pEmail	= new char[strlen(pCurrentEmail) + 1];
		strcpy(pEmail, pCurrentEmail);

		ORACLE_DATEToTime(pCurrentUserIdLastChanged, &theLastChangeTime);

		// If we didn't get a score (which probably means
		// the user doesn't have any feedback), then indicate
		// it by passing INT_MIN
		if (*pCurrentScoreInd == -1)
			score	= INT_MIN;
		else
			score	= *pCurrentScore;

		pEntry	= new clsBulletinBoardEntry(
								theTime,
								*pCurrentId,
								pUserId,
								pEmail,
								theLastChangeTime,
								score,
								pCurrentHost,
								*pCurrentEntryLen,
								pString,
								*pCurrentUserFlags
										   );

		plEntries->push_back(pEntry);
	}


	Close(&mpCDAGetBulletinBoardEntries);
	SetStatement(NULL);

	// Clean
	delete[]	pWhens;
	delete[]	pIds;
	delete[]	pUserIds;
	delete[]	pUserIdInds;
	delete[]	pUserFlags;
	delete[]	pScores;
	delete[]	pScoreInds;
	delete[]	pHosts;
	delete[]	pEntryLens;
	delete[]	pEntries;
	delete[]	pEmails;
	delete[]	pEmailInds;
	delete[]	pUserIdLastChangedInds;
	delete[]	pUserIdLastChangeds;

	return;
}

//
// TrimBulletinBoard
//	
//	I'm SURE there's a better way to do this
//

static const char *SQL_GetBulletinBoardTimes = 
 "select	TO_CHAR(when,						\
					'YYYY-MM-DD HH24:MI:SS')	\
	from	ebay_bulletin_boards				\
	where	board_id = :boardid					\
	order by when desc";

static const char *SQL_DeleteBulletinBoardEntries =
 "delete from ebay_bulletin_boards				\
	where	board_id = :boardid					\
	and		when < TO_DATE(:when,				\
							'YYYY-MM-DD HH24:MI:SS')";


void clsDatabaseOracle::TrimBulletinBoard(BulletinBoardId board_id,
										  int count)
{
	int		rc;

	char	*pTimes;
	int		timeLen;

	int		rowCount;

	// First, let's allocate the array of times
	timeLen	= strlen("YYYY-MM-DD HH24:MI:SS") + 1;

	pTimes	= new char[(count + 1) * timeLen];


	// This is part one of the gross of the gross. Since we're
	// going to use array fetch, we need to allocate buffers
	// as big as the biggest field we'll get. This could be
	// incredibly gross. Anyway, this SQL will get the 
	// pertinent info for us.

	// Now, the usual stuff
	OpenAndParse(&mpCDAGetBulletinBoardTimes,
				 SQL_GetBulletinBoardTimes);

	// Define where the times go. It's a big fat array
	Bind(":boardid", &board_id);

	Define(1, pTimes, timeLen);

	// Do
	Execute();

	rc = ofen((struct cda_def *)mpCDACurrent,
			  count + 1);

	if ((rc < 0 || rc >= 4)  && 
		((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		Check(rc);


	// If we got less than count rows, we're done
	rowCount	= ((struct cda_def *)mpCDACurrent)->rpc;

	// We can close now
	Close(&mpCDAGetBulletinBoardTimes);
	SetStatement(NULL);


	if (rowCount <= count)
	{
		delete[]	pTimes;
		return;
	}



	// We're not done. The time of the LAST entry governs
	// which entries to delete
	OpenAndParse(&mpCDADeleteBulletinBoardEntries,
				 SQL_DeleteBulletinBoardEntries);

	Bind(":boardid", &board_id);
	Bind(":when", pTimes + (count * timeLen));

	Execute();
	Commit();

	// all done
	Close(&mpCDADeleteBulletinBoardEntries);
	SetStatement(NULL);

	delete[] pTimes;

	return;
}
