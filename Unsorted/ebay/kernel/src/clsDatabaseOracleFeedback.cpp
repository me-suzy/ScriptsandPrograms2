/*	$Id: clsDatabaseOracleFeedback.cpp,v 1.5.166.1 1999/07/29 07:34:19 josh Exp $	*/
//
//	File:	clsDatabaseOracleFeedback.cpp
//
//	Class:	clsDatabaseOracle
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 09/04/98 mila		- Created (moved feedback methods from file
//									  clsDatabaseOracle.cpp)
//				- 12/04/98 wen		- Added missing parameters for new clsFeedback

#include "eBayKernel.h"
#define	ISLENDIAN	1  // for NT only!

static bool SortItemDetailTimeDescend(clsFeedbackItem* pItem1, clsFeedbackItem* pItem2)
{
	if (pItem1 != NULL &&
		 pItem2 != NULL)
	{
		return pItem1->mTime > pItem2->mTime;
	}
	else
		return false;
}

//
// AllocateFeedbackListBuffer
//
//	Common method used to determine if the current feedback cache
//	buffer is large enough, and if not, re-allocate it.
//
bool clsDatabaseOracle::AllocateFeedbackListBuffer(int rowCountNeeded)
{
	int		bufferSizeNeeded;

	bufferSizeNeeded	= rowCountNeeded * FEEDBACK_LIST_ROWID_SIZE;

	if (mpFeedbackListBuffer	== NULL)
	{
		mFeedbackListBufferSize	= bufferSizeNeeded;
		mpFeedbackListBuffer	= new unsigned char[mFeedbackListBufferSize];
		return true;
	}

	// If we don't have enough buffer, reallocate, and recurse
	if (bufferSizeNeeded > mFeedbackListBufferSize)
	{
		delete[]	mpFeedbackListBuffer;
		mpFeedbackListBuffer	= NULL;
		mFeedbackListBufferSize	= bufferSizeNeeded;
		mpFeedbackListBuffer	= new unsigned char[mFeedbackListBufferSize];

		return true;
	}

	return false;
}




//
// AddFeedback
//	Creates a summary feedback record for a user. 
//	Usually called internally
//
static const char *SQL_AddFeedback =
"insert into ebay_feedback			\
	(	id,							\
		created,					\
		last_update,				\
		score,						\
		flags,						\
		split						\
	)								\
	values							\
	(	:id,						\
		sysdate,					\
		sysdate,					\
		:score,						\
		:flags,						\
		:split						\
	)";

void clsDatabaseOracle::AddFeedback(int id,
									int score,
									int flags,
									bool split)
{
	char cSplit = split? '1' : '0';

	OpenAndParse(&mpCDAOneShot, SQL_AddFeedback);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":score", &score);
	Bind(":flags", &flags);
	Bind(":split", &cSplit, 1);

	// Do it...
	Execute();
	Commit();

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}

// GetFeedBack
//
// GetFeedBack - beefed up to include extended score
//

// **** TEST TEST TEST ****
// WIRE OFF GETTING SPLIT 
// **** TEST TEST TEST ****
#pragma message ("*TEST* WIRED OFF GETTING SPLIT *TEST*")


static const char *SQL_GetFeedback = 
 "select	TO_CHAR(created,					\
					'YYYY-MM-DD HH24:MI:SS'),	\
			TO_CHAR(last_update,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			score,								\
			flags,								\
			valid_ext,							\
			TO_CHAR(ext_calc_date,				\
					'YYYY-MM-DD HH24:MI:SS'),	\
			pos_comment,						\
			pos_count,							\
			neg_comment,						\
			neg_count,							\
			neut_comment,						\
			neut_from_suspended,				\
			interval1,							\
			interval2,							\
			interval3,							\
			comments_in_int1,					\
			comments_in_int2,					\
			comments_in_int3,					\
			pos_comment_in_int1,				\
			pos_comment_in_int2,				\
			pos_comment_in_int3,				\
			neg_comment_in_int1,				\
			neg_comment_in_int2,				\
			neg_comment_in_int3,				\
			neut_comment_in_int1,				\
			neut_comment_in_int2,				\
			neut_comment_in_int3,				\
			'0' /* split */						\
  from ebay_feedback							\
  where id = :id";

clsFeedback *clsDatabaseOracle::GetFeedback(int id)
{
	char		created[32];
	char		last_update[32];
	int			score;
	int			flags;

	char		cValidExt[2];
	sb2			ValidExtInd;
	bool		isValidExt;
	char		cExtCalcDate[32];
	sb2			cExtCalcDateInd;
	long		CalcDate;

	int			PosComment;
	sb2			PosCommentInd;
	int			PosCount;
	sb2			PosCountInd;
	int			NegComment;
	sb2			NegCommentInd;
	int			NegCount;
	sb2			NegCountInd;
	int			NeutComment;
	sb2			NeutCommentInd;
	int			NeutFromSuspended;
	sb2			NeutFromSuspendedInd;
	int			Interval1;
	sb2			Interval1Ind;
	int			Interval2;
	sb2			Interval2Ind;
	int			Interval3;
	sb2			Interval3Ind;
	int			CommentsInInt1;
	sb2			CommentsInInt1Ind;
	int			CommentsInInt2;
	sb2			CommentsInInt2Ind;
	int			CommentsInInt3;
	sb2			CommentsInInt3Ind;
	int			PosCommentsInInt1;
	sb2			PosCommentsInInt1Ind;
	int			PosCommentsInInt2;
	sb2			PosCommentsInInt2Ind;
	int			PosCommentsInInt3;
	sb2			PosCommentsInInt3Ind;
	int			NegCommentsInInt1;
	sb2			NegCommentsInInt1Ind;
	int			NegCommentsInInt2;
	sb2			NegCommentsInInt2Ind;
	int			NegCommentsInInt3;
	sb2			NegCommentsInInt3Ind;
	int			NeutCommentsInInt1;
	sb2			NeutCommentsInInt1Ind;
	int			NeutCommentsInInt2;
	sb2			NeutCommentsInInt2Ind;
	int			NeutCommentsInInt3;
	sb2			NeutCommentsInInt3Ind;
	char		split[2];
	clsFeedback	*pFeedback;
	clsFeedbackExtendedScore *pFeedbackExtendedScore;

	// This is a popular query, so we'll try 
	// and parse the cursor ONCE ;-)
	OpenAndParse(&mpCDAGetFeedback,
				 SQL_GetFeedback);

	// Bind that input variable
	Bind(":id", &id);

	// Bind the output
	Define(1, created, sizeof(created));
	Define(2, last_update, sizeof(last_update));
	Define(3, &score);
	Define(4, &flags);
	Define(5, cValidExt, sizeof(cValidExt),&ValidExtInd);
	Define(6, cExtCalcDate, sizeof(cExtCalcDate), &cExtCalcDateInd);
	Define(7, &PosComment, &PosCommentInd);
	Define(8, &PosCount, &PosCountInd);
	Define(9, &NegComment, &NegCommentInd);
	Define(10, &NegCount, &NegCountInd);
	Define(11, &NeutComment, &NeutCommentInd);
	Define(12, &NeutFromSuspended, &NeutFromSuspendedInd);
	Define(13, &Interval1, &Interval1Ind);
	Define(14, &Interval2, &Interval2Ind);
	Define(15, &Interval3, &Interval3Ind);
	Define(16, &CommentsInInt1, &CommentsInInt1Ind);
	Define(17, &CommentsInInt2, &CommentsInInt2Ind);
	Define(18, &CommentsInInt3, &CommentsInInt3Ind);
	Define(19, &PosCommentsInInt1, &PosCommentsInInt1Ind);
	Define(20, &PosCommentsInInt2, &PosCommentsInInt2Ind);
	Define(21, &PosCommentsInInt3, &PosCommentsInInt3Ind);
	Define(22, &NegCommentsInInt1, &NegCommentsInInt1Ind);
	Define(23, &NegCommentsInInt2, &NegCommentsInInt2Ind);
	Define(24, &NegCommentsInInt3, &NegCommentsInInt3Ind);
	Define(25, &NeutCommentsInInt1, &NeutCommentsInInt1Ind);
	Define(26, &NeutCommentsInInt2, &NeutCommentsInInt2Ind);
	Define(27, &NeutCommentsInInt3, &NeutCommentsInInt3Ind);
	Define(28, split, sizeof(split));	

	// Let's get it.
	//
	// *** NOTE ***
	// Need a catch here! For now, we'll initialize
	// the variables and hope they don't get modified
	//
	created[0]		= '\0';
	last_update[0]	= '\0';
	score			= 0;
	flags			= 0;

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		pFeedback	= 
			new clsFeedback(id, false);
	}
	else
	{
		if ((ValidExtInd == -1) || (cValidExt[0] == 'N'))
			isValidExt	= false;
		else
			isValidExt	= true;

		// check if extended feedback score is null
		// we assume if ExtCalcDate is present, then extended score
		// is there

		if ((cExtCalcDateInd == -1) || !isValidExt)
		{	// null, no valid extended feedback score
			pFeedback	= 
				new clsFeedback(id, true, score, flags, false, 0,
							NULL,
							split[0]=='1');							
		}
		else
		{
		
			ORACLE_DATEToTime(cExtCalcDate, &CalcDate);

			// null means 0
			if (PosCommentInd == -1)
				PosComment = 0;
			if (PosCountInd == -1)
				PosCount = 0;
			if (NegCommentInd == -1)
				NegComment = 0;
			if (NeutCommentInd == -1)
				NeutComment = 0;
			if (NeutFromSuspendedInd == -1)
				NeutFromSuspended = 0;
			if (Interval1Ind == -1)
				Interval1 = 0;
			if (Interval2Ind == -1)
				Interval2 = 0;
			if (Interval3Ind == -1)
				Interval3 = 0;
			if (CommentsInInt1Ind == -1)
				CommentsInInt1 = 0;
			if (CommentsInInt2Ind == -1)
				CommentsInInt2 = 0;
			if (CommentsInInt3Ind == -1)
				CommentsInInt3 = 0;
			if (PosCommentsInInt1Ind == -1)
				PosCommentsInInt1 = 0;
			if (PosCommentsInInt2Ind == -1)
				PosCommentsInInt2 = 0;
			if (PosCommentsInInt3Ind == -1)
				PosCommentsInInt3 = 0;
			if (NegCommentsInInt1Ind == -1)
				NegCommentsInInt1 = 0;
			if (NegCommentsInInt2Ind == -1)
				NegCommentsInInt2 = 0;
			if (NegCommentsInInt3Ind == -1)
				NegCommentsInInt3 = 0;
			if (NeutCommentsInInt1Ind == -1)
				NeutCommentsInInt1 = 0;
			if (NeutCommentsInInt2Ind == -1)
				NeutCommentsInInt2 = 0;
			if (NeutCommentsInInt3Ind == -1)
				NeutCommentsInInt3 = 0;

			pFeedbackExtendedScore = new
				clsFeedbackExtendedScore(
				score,
				PosComment,
				PosCount,
				NegComment,
				NegCount,
				NeutComment,
				NeutFromSuspended,
				Interval1,
				Interval2,
				Interval3,
				CommentsInInt1,
				CommentsInInt2,
				CommentsInInt3,
				PosCommentsInInt1,
				PosCommentsInInt2,
				PosCommentsInInt3,
				NegCommentsInInt1,
				NegCommentsInInt2,
				NegCommentsInInt3,
				NeutCommentsInInt1,
				NeutCommentsInInt2,
				NeutCommentsInInt3
				);

			pFeedback	= 
				new clsFeedback(id, true, score,
							flags, isValidExt, CalcDate,
							pFeedbackExtendedScore,
							split[0]=='1');	
		}
	}

	Close(&mpCDAGetFeedback);
	SetStatement(NULL);

	return pFeedback;
}

//
// GetFeedbackDetail
//
#define ORA_FEEDBACKDETAIL_ARRAYSIZE	50

static const char *SQL_GetFeedbackDetail =
 "select	TO_CHAR(detail.time,							\
					'YYYY-MM-DD HH24:MI:SS'),				\
			detail.comment_type,							\
			detail.commenting_id,							\
			users.userid,									\
			users.user_state,								\
			feedback.score,									\
 			detail.commenting_host,							\
			detail.comment_text,							\
 			detail.item,									\
			detail.response,								\
			detail.followup,								\
			TO_CHAR(users.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),					\
			users.email,									\
			detail.rowid									\
			from	%s detail,								\
					ebay_users users,						\
					ebay_feedback feedback					\
			where	detail.id = :id							\
			and		detail.commenting_id = users.id (+)		\
			and		detail.commenting_id = feedback.id (+)	\
			order by time desc";

void clsDatabaseOracle::GetFeedbackDetail(int id,
										  FeedbackItemVector *pvFeedbackDetail,
										  bool Split)
{
	char				time[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];		//lint !e578 don't worry about time
	FeedbackTypeEnum	type[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cId[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cUserId[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	sb2					cUserIdInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cEmail[ORA_FEEDBACKDETAIL_ARRAYSIZE][65];
	sb2					cEmailInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cUserState[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cScore[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	sb2					cScoreInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cHost[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];
	char				text[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 and don't worry about text
	int					item[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	sb2					itemInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				response[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 and don't worry about text
	sb2					responseInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				followup[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 and don't worry about text
	sb2					followUpInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				detailRowId[ORA_FEEDBACKDETAIL_ARRAYSIZE]
								   [FEEDBACK_LIST_ROWID_SIZE + 1];

	// so we can show mask of commenting user
	time_t				cUser_id_last_modified_time[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cUser_id_last_modified[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];
	sb2					cUser_last_change_ind[ORA_FEEDBACKDETAIL_ARRAYSIZE];

	int					rowsFetched;
	time_t				theTime;
	clsFeedbackItem		*pFeedbackItem;
	int					rc;
	int					i,n;
	int					SubTable = Split ? id % 10 : 10 ;
	char*				pTheStatement;

	// Fill in the table name before parsing the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetFeedbackDetail, SubTable);
	OpenAndParse(&(mpCDAGetFeedbackDetail[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the input variable
	Bind(":id", &id);

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!

	Define(1, (char *)time, sizeof(time[0]));
	Define(2, (int *)type);
	Define(3, (int *)cId);
	Define(4, (char *)cUserId, sizeof(cUserId[0]), &cUserIdInd[0]);
	Define(5, (int *)cUserState);
	Define(6, (int *)cScore, &cScoreInd[0]);
	Define(7, (char *)cHost, sizeof(cHost[0]));
	Define(8, (char *)text, sizeof(text[0]));
	Define(9, (int *)item, itemInd);
	Define(10, (char *)response, sizeof(response[0]), responseInd);
	Define(11, (char *)followup, sizeof(followup[0]), followUpInd);
	Define(12, cUser_id_last_modified[0], sizeof(cUser_id_last_modified[0]), &cUser_last_change_ind[0]);
	Define(13, (char *)cEmail, sizeof(cEmail[0]), &cEmailInd[0]);
	Define(14, (char *)detailRowId, sizeof(detailRowId[0]));	

	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&(mpCDAGetFeedbackDetail[SubTable]));
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_FEEDBACKDETAIL_ARRAYSIZE);

		assert(mpCDACurrent);
		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&(mpCDAGetFeedbackDetail[SubTable]));
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{
			// Convert time
			ORACLE_DATEToTime(time[i], &theTime);
			if (cUser_last_change_ind[i] != -1)
				ORACLE_DATEToTime(cUser_id_last_modified[i], &cUser_id_last_modified_time[i]);
			else
				cUser_id_last_modified_time[i] = 0;

			// Check for NULL score
			if (cScoreInd[i] == -1)
				cScore[i] = 0;

			if (itemInd[i] == -1)
				item[i] = 0;

			pFeedbackItem	= 
				new clsFeedbackItem(id,
									theTime,
									type[i],
									cId[i],
									cUserId[i],
									cEmail[i],
									cUserState[i],
									cScore[i],
									cHost[i],
									text[i],
									cUser_id_last_modified_time[i],
									detailRowId[i],
									0,
									0,
									item[i],
									response[i],
									followup[i]);
			pvFeedbackDetail->push_back(pFeedbackItem);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&(mpCDAGetFeedbackDetail[SubTable]));
	SetStatement(NULL);

	return;		//lint !e429 Don't worry about pFeedbackItem
}

//
// Get All rows from ebay_feedback_details for a user
//
static const char *SQL_GetFeedbackDetailToSplit =
 "select TO_CHAR(time,"
 "	'YYYY-MM-DD HH24:MI:SS'),"
 "	commenting_id,"
 "	commenting_host,"
 "	comment_type,"
 "	comment_score,"
 "	comment_text,"
 "	response,"
 "	followup,"
 "	item, "
 "	rowid	"
 "	from %s	 "
 "		where	id = :id";

void clsDatabaseOracle::GetFeedbackDetailToSplit(
					  int id, int Split,
					  FeedbackItemVector *pvFeedbackDetail
										 )
{

	char				theTime[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];
	int					cId[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cHost[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	FeedbackTypeEnum	cType[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cScore[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	sb2					cScoreInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cText[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	char				response[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	sb2					responseInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				followup[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	sb2					followUpInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					item[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	sb2					itemInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				detailRowId[ORA_FEEDBACKDETAIL_ARRAYSIZE]
								   [FEEDBACK_LIST_ROWID_SIZE + 1];

	int					rowsFetched;
	time_t				tTime;
	clsFeedbackItem		*pFeedbackItem;
	int					rc;
	int					i,n;

	int			SubTable = Split ? id % 10 : 10 ;
	char*		pTheStatement;

	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetFeedbackDetailToSplit, SubTable);
	OpenAndParse(&(mpCDAGetFeedbackDetailToSplit[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the input variable
	Bind(":id", &id);

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!

	Define(1, theTime[0], sizeof(theTime[0]));
	Define(2, cId);
	Define(3, cHost[0], sizeof(cHost[0]));
	Define(4, (int *)cType);
	Define(5, cScore, cScoreInd);
	Define(6, cText[0], sizeof(cText[0]));
	Define(7, response[0], sizeof(response[0]), responseInd);
	Define(8, followup[0], sizeof(followup[0]), followUpInd);
	Define(9, item, itemInd);
	Define(10, (char *)detailRowId, sizeof(detailRowId[0]));	


	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&(mpCDAGetFeedbackDetailToSplit[SubTable]));
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_FEEDBACKDETAIL_ARRAYSIZE);

		assert(mpCDACurrent);
		if ((rc < 0 || rc >= 4)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			Close(&(mpCDAGetFeedbackDetailToSplit[SubTable]));
			SetStatement(NULL);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this theTime 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{

			// Convert theTime
			ORACLE_DATEToTime(theTime[i], &tTime);
			// Check for NULL score
			if (cScoreInd[i] == -1)
				cScore[i] = 0;

			if (itemInd[i] == -1)
				item[i] = 0;

			pFeedbackItem	= 
				new clsFeedbackItem(id,
									tTime,
									cType[i],
									cId[i],
									(char *)"",
									(char *)"",
									0,
									cScore[i],
									cHost[i],
									cText[i],
									0,
									detailRowId[i],
									0,
									0,
									item[i],
									response[i],
									followup[i]
									);

			pvFeedbackDetail->push_back(pFeedbackItem);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&(mpCDAGetFeedbackDetailToSplit[SubTable]));
	SetStatement(NULL);

	return;		
}

const int ORA_FEEDBACKDETAIL_PAGESIZE = 25;




//
// GetMinimalFeedbackDetail
//
static const char *SQL_GetMinimalFeedbackDetail =
"select		comment_type, "
"			commenting_id, "
"			rowid "
"	from	%s "
"	where	id = :id "
"			order by time desc";

void clsDatabaseOracle::GetMinimalFeedbackDetail(
					  int id,
					  MinimalFeedbackItemVector *pvFeedbackDetail,
					  bool Split)
{
	FeedbackTypeEnum		type[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int						cId[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char					cRowId[ORA_FEEDBACKDETAIL_ARRAYSIZE]
								  [FEEDBACK_LIST_ROWID_SIZE + 1];

	int						rowsFetched;

	clsMinimalFeedbackItem	*pFeedbackItem;

	int						rc;
	int						i,n;
	int						SubTable = Split ? id % 10 : 10 ;
	char*					pTheStatement;


	// Fill in the table name before parsing the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetMinimalFeedbackDetail, 
		SubTable);

	OpenAndParse(&(mpCDAGetMinimalFeedbackDetail[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the input variable
	Bind(":id", &id);

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!
	Define(1, (int *)type);
	Define(2, cId);
	Define(3, (char *)cRowId, sizeof(cRowId[0]));
	
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&(mpCDAGetMinimalFeedbackDetail[SubTable]));
		SetStatement(NULL);
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{

		rc = ofen((struct cda_def *)mpCDACurrent,ORA_FEEDBACKDETAIL_ARRAYSIZE);
		assert(mpCDACurrent);
		if ((rc != 0)  && 
			((struct cda_def *)mpCDACurrent)->rc != 1403)
		{
			Check(rc);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{

			pFeedbackItem	= 
				new clsMinimalFeedbackItem(type[i],
										   cId[i],
										   cRowId[i]);
			pvFeedbackDetail->push_back(pFeedbackItem);
		}

	} while (!CheckForNoRowsFound());


	// Close 
	Close(&(mpCDAGetMinimalFeedbackDetail[SubTable]));
	SetStatement(NULL);

	return;		//lint !e429 Don't worry about pFeedbackItem
}

//
// GetFeedbackDetailLeftByUser
//
static const char *SQL_GetFeedbackDetailLeftByUser =
 "select	TO_CHAR(detail.time,						\
					'YYYY-MM-DD HH24:MI:SS'),			\
			detail.comment_type,						\
			detail.id,									\
			users.userid,								\
			feedback.score,								\
			detail.commenting_host,						\
			detail.comment_text,						\
			feedback.flags,								\
			detail.item,								\
			detail.response,							\
			detail.followup,							\
			TO_CHAR(users.userid_last_change,			\
				'YYYY-MM-DD HH24:MI:SS'),				\
			users.email,								\
			detail.rowid								\
			from	ebay_feedback_detail detail,		\
					ebay_users users,					\
					ebay_feedback feedback				\
			where	detail.commenting_id = :id			\
			and		detail.id = users.id (+)			\
			and		detail.id = feedback.id (+)			\
			order by time desc";



void clsDatabaseOracle::GetFeedbackDetailLeftByUser(
					  int id,
					  FeedbackItemVector *pvFeedbackDetail)
{

	char				time[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];	//lint !e578 Don't worry about time
	FeedbackTypeEnum	type[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cId[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cUserId[ORA_FEEDBACKDETAIL_ARRAYSIZE][256];
	sb2					cUserIdInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cEmail[ORA_FEEDBACKDETAIL_ARRAYSIZE][65];
	sb2					cEmailInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					cScore[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	sb2					cScoreInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cHost[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];
	char				text[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 nor about text
	int					cIdFlag[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				response[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 nor about text
	sb2					responseInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				followup[ORA_FEEDBACKDETAIL_ARRAYSIZE][128];	//lint !e578 nor about text
	sb2					followUpInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	int					item[ORA_FEEDBACKDETAIL_ARRAYSIZE];	//lint !e578 nor about text
	sb2					itemInd[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				detailRowId[ORA_FEEDBACKDETAIL_ARRAYSIZE]
								   [FEEDBACK_LIST_ROWID_SIZE + 1];

	// so we can show mask of user
	time_t				cUser_id_last_modified_time[ORA_FEEDBACKDETAIL_ARRAYSIZE];
	char				cUser_id_last_modified[ORA_FEEDBACKDETAIL_ARRAYSIZE][32];
	sb2					cUser_last_change_ind[ORA_FEEDBACKDETAIL_ARRAYSIZE];

	int					rowsFetched;
	time_t				theTime;
	clsFeedbackItem		*pFeedbackItem;
	int					rc;
	int					i,n;

	OpenAndParse(&mpCDAGetFeedbackDetailLeftByUser,
				 SQL_GetFeedbackDetailLeftByUser);

	// Bind the input variable
	Bind(":id", &id);

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!

	Define(1, (char *)time, sizeof(time[0]));
	Define(2, (int *)type);
	Define(3, cId);
	Define(4, (char *)cUserId, sizeof(cUserId[0]), &cUserIdInd[0]);
	Define(5, cScore, &cScoreInd[0]);
	Define(6, (char *)cHost, sizeof(cHost[0]));
	Define(7, (char *)text, sizeof(text[0]));
	Define(8, cIdFlag);
	Define(9, (int *)item, itemInd);
	Define(10, (char *)response, sizeof(response[0]), responseInd);
	Define(11, (char *)followup, sizeof(followup[0]), followUpInd);
	Define(12, cUser_id_last_modified[0], sizeof(cUser_id_last_modified[0]), &cUser_last_change_ind[0]);
	Define(13, (char *)cEmail, sizeof(cEmail[0]), &cEmailInd[0]);
	Define(14, (char *)detailRowId, sizeof(detailRowId[0]));
		
	Execute();

	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&mpCDAGetFeedbackDetailLeftByUser);
		SetStatement(NULL);
			
		return;
	}

	// Now we fetch until we're done
	rowsFetched = 0;
	do
	{
		rc = ofen((struct cda_def *)mpCDACurrent,ORA_FEEDBACKDETAIL_ARRAYSIZE);
		assert(mpCDACurrent);
		if (rc && ((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
		{
			Check(rc);
			return;
		}

		// rpc is cumulative, so find out how many rows to display this time 
		// (always <= ORA_FEEDBACKDETAIL_ARRAYSIZE). 
		n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
		rowsFetched += n;

		for (i=0; i < n; i++)
		{

			// Convert time
			ORACLE_DATEToTime((char *)time[i], &theTime);
			if (cUser_last_change_ind[i] != -1)
				ORACLE_DATEToTime(cUser_id_last_modified[i], &cUser_id_last_modified_time[i]);
			else
				cUser_id_last_modified_time[i] = 0;

			// check for NULL score
			if (cScoreInd[i] == -1)
				cScore[i] = 0;

			if (itemInd[i] == -1)
				item[i] = 0;

			pFeedbackItem	= 
				new clsFeedbackItem(id,
									theTime,
									type[i],
									cId[i],
									cUserId[i],
									cEmail[i],
									UserConfirmed,
									cScore[i],
									cHost[i],
									text[i],
									cUser_id_last_modified_time[i],
									detailRowId[i],
									cIdFlag[i],
									0,
									item[i],
									response[i],
									followup[i]);

			pvFeedbackDetail->push_back(pFeedbackItem);
		}

	} while (!CheckForNoRowsFound());

	// close cusors
	Close(&mpCDAGetFeedbackDetailLeftByUser);
		
	SetStatement(NULL);


	// sort the array
	if (pvFeedbackDetail->size() > 0)
	{
		sort(pvFeedbackDetail->begin(), 
			 pvFeedbackDetail->end(), 
			 SortItemDetailTimeDescend);
	}

	return;		//lint !e429 Don't worry about pFeedbackItem
}

static const char *SQL_InvalidateExtendedFeedback =
"update	ebay_feedback					\
 set	valid_ext = \'N\'				\
 where	id = :owner";

void clsDatabaseOracle::InvalidateExtendedFeedback(int id)
{
	// Simple
	OpenAndParse(&mpCDAInvalidateExtendedFeedback,
				 SQL_InvalidateExtendedFeedback);

	Bind(":owner", &id);

	Execute();

	Commit();

	Close(&mpCDAInvalidateExtendedFeedback);
	SetStatement(NULL);

	return;
}


// only update extended feedback score associated with a feedback
// does not update feedback score itself?
static const char *SQL_UpdateExtendedFeedback =
 "update ebay_feedback						\
	set valid_ext = :isValidExt,			\
	ext_calc_date = TO_DATE(:calcdate,		\
				'YYYY-MM-DD HH24:MI:SS'),	\
	pos_comment = :poscomment,				\
	pos_count = :poscount,					\
	neg_comment = :negcomment,				\
	neg_count = :negcount,					\
	neut_comment = :neutcomment,			\
	neut_from_suspended = :neutsuspend,		\
	interval1 = :int1,						\
	interval2 = :int2,						\
	interval3 = :int3,						\
	comments_in_int1 = :commentsint1,		\
	comments_in_int2 = :commentsint2,		\
	comments_in_int3 = :commentsint3,		\
	pos_comment_in_int1 = :poscommentsint1,\
	pos_comment_in_int2 = :poscommentsint2,\
	pos_comment_in_int3 = :poscommentsint3,\
	neg_comment_in_int1 = :negcommentsint1,		\
	neg_comment_in_int2 = :negcommentsint2,		\
	neg_comment_in_int3 = :negcommentsint3,		\
	neut_comment_in_int1 = :neutcommentsint1,		\
	neut_comment_in_int2 = :neutcommentsint2,		\
	neut_comment_in_int3 = :neutcommentsint3		\
	where id = :id";

// assuming that user has feedback record if they have
// extended score, no?
void clsDatabaseOracle::UpdateExtendedFeedback(
						clsFeedback *pFeedback)
{
	char		cValidExt[2];
	char		cExtCalcDate[32] = {0};
	int			PosComment;
	int			PosCount;
	int			NegComment;
	int			NegCount;
	int			NeutComment;
	int			NeutFromSuspended;
	int			Interval1;
	int			Interval2;
	int			Interval3;
	int			CommentsInInt1;
	int			CommentsInInt2;
	int			CommentsInInt3;
	int			PosCommentsInInt1;
	int			PosCommentsInInt2;
	int			PosCommentsInInt3;
	int			NegCommentsInInt1;
	int			NegCommentsInInt2;
	int			NegCommentsInInt3;
	int			NeutCommentsInInt1;
	int			NeutCommentsInInt2;
	int			NeutCommentsInInt3;
	struct tm	*pTheTime;
	time_t		tTime;
	int			id;

	clsFeedbackExtendedScore	*pExtScore;

	id = pFeedback->GetId();

	pExtScore = pFeedback->GetExtendedScore();

	if (pFeedback->IsValidExtendedScore())
		strcpy(cValidExt, "Y");
	else
		strcpy(cValidExt, "N");

	tTime			= pFeedback->GetExtDateCalc();
	if (tTime == 0)
		tTime = time(0);
	pTheTime	= localtime(&tTime);
	TM_STRUCTToORACLE_DATE(pTheTime,   cExtCalcDate);

	PosComment  = pExtScore->mPositiveComments;
	PosCount = pExtScore->mPositiveCommentsThatCount; 
	NegComment = pExtScore->mNegativeComments;
	NegCount = pExtScore->mNegativeCommentsThatCount;
	NeutComment = pExtScore->mNeutralComments;
	NeutFromSuspended = pExtScore->mNeutralCommentsFromSuspendedUsers;
	Interval1 = pExtScore->mInterval1Boundry;
	Interval2 = pExtScore->mInterval2Boundry;
	Interval3 = pExtScore->mInterval3Boundry;
	CommentsInInt1 = pExtScore->mCommentsInInterval1;
	CommentsInInt2 = pExtScore->mCommentsInInterval2;
	CommentsInInt3 = pExtScore->mCommentsInInterval3;
	PosCommentsInInt1 = pExtScore->mPositiveCommentsInInterval1;
	PosCommentsInInt2 = pExtScore->mPositiveCommentsInInterval2;
	PosCommentsInInt3 = pExtScore->mPositiveCommentsInInterval3;
	NegCommentsInInt1 = pExtScore->mNegativeCommentsInInterval1;
	NegCommentsInInt2 = pExtScore->mNegativeCommentsInInterval2;
	NegCommentsInInt3 = pExtScore->mNegativeCommentsInInterval3;
	NeutCommentsInInt1 = pExtScore->mNeutralCommentsInInterval1;
	NeutCommentsInInt2 = pExtScore->mNeutralCommentsInInterval2;
	NeutCommentsInInt3 = pExtScore->mNeutralCommentsInInterval3;

	OpenAndParse(&mpCDAUpdateExtendedFeedback, SQL_UpdateExtendedFeedback);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":isValidExt", (char *)cValidExt);
	Bind(":calcdate", (char *)cExtCalcDate);
	Bind(":poscomment", &PosComment);
	Bind(":poscount", &PosCount);
	Bind(":negcomment", &NegComment);
	Bind(":negcount", &NegCount);
	Bind(":neutcomment", &NeutComment);
	Bind(":neutsuspend", &NeutFromSuspended);
	Bind(":int1", &Interval1);
	Bind(":int2", &Interval2);
	Bind(":int3", &Interval3);
	Bind(":commentsint1", &CommentsInInt1);
	Bind(":commentsint2", &CommentsInInt2);
	Bind(":commentsint3", &CommentsInInt3);
	Bind(":poscommentsint1", &PosCommentsInInt1);
	Bind(":poscommentsint2", &PosCommentsInInt2);
	Bind(":poscommentsint3", &PosCommentsInInt3);
	Bind(":negcommentsint1", &NegCommentsInInt1);
	Bind(":negcommentsint2", &NegCommentsInInt2);
	Bind(":negcommentsint3", &NegCommentsInInt3);
	Bind(":neutcommentsint1", &NeutCommentsInInt1);
	Bind(":neutcommentsint2", &NeutCommentsInInt2);
	Bind(":neutcommentsint3", &NeutCommentsInInt3);
	
	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	if (CheckForNoRowsUpdated())
	{
		// this should be an exception?!
	}

	Commit();
	Close(&mpCDAUpdateExtendedFeedback);
	SetStatement(NULL);

	return;

}


// 
// Recent feedback from users, hosts
//
static const char *SQL_GetRecentFeedbackDetailFromUser =
"select	TO_CHAR(detail.time,							\
					'YYYY-MM-DD HH24:MI:SS'),				\
			detail.comment_type,							\
			users.userid,									\
			users.user_state,								\
			feedback.score,									\
 			detail.commenting_host,							\
			detail.comment_text,							\
			TO_CHAR(users.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),					\
			users.email										\
	from	ebay_feedback_detail detail,					\
			ebay_users users,								\
			ebay_feedback feedback							\
	where	detail.id = :id									\
			and		detail.commenting_id = :cid				\
			and		detail.time >							\
						TO_DATE(:limit,						\
								'YYYY-MM-DD HH24:MI:SS')	\
			and		detail.commenting_id = users.id (+)		\
			and		detail.commenting_id = feedback.id (+)	\
			and		rownum = 1";


static const char *SQL_GetRecentNegativeFeedbackDetailFromUser =
"select		TO_CHAR(detail.time,							\
					'YYYY-MM-DD HH24:MI:SS'),				\
			detail.comment_type,							\
			users.userid,									\
			users.user_state,								\
			feedback.score,									\
 			detail.commenting_host,							\
			detail.comment_text,							\
			TO_CHAR(users.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),					\
			users.email										\
	from	ebay_feedback_detail detail,					\
			ebay_users users,								\
			ebay_feedback feedback							\
	where	detail.id = :id									\
			and		detail.commenting_id = :cid				\
			and		detail.time >							\
						TO_DATE(:limit,						\
								'YYYY-MM-DD HH24:MI:SS')	\
			and		detail.comment_type = 2					\
			and		detail.commenting_id = users.id (+)		\
			and		detail.commenting_id = feedback.id (+)	\
			and		rownum = 1";

clsFeedbackItem *clsDatabaseOracle::RecentFeedbackFromUser(
								int id,
								int commentingId,
								time_t timeLimit,
								bool negativeFeedbackOnly)
{
	time_t		nowTime;
	time_t		nowTimeMinusLimit;
	char		cNowTimeMinusLimit[32];

	char				cTheTime[32];
	time_t				theTime;
	FeedbackTypeEnum	type;
	char				cUserId[256];
	sb2					cUserIdInd;
	char				cEmail[65];
	sb2					cEmailInd;
	int					cUserState;
	int					cScore;
	sb2					cScoreInd;
	char				cHost[32];
	char				text[128];	//lint !e578 Don't worry about text
	clsFeedbackItem		*pFeedbackItem = NULL;

	// so we can show mask of commenting user
	time_t				cUser_id_last_modified_time;
	char				cUser_id_last_modified[32];
	sb2					cUser_last_change_ind;

	// Let's do the time first
	nowTime				= time(0);
	nowTimeMinusLimit	= nowTime - timeLimit;

	TimeToORACLE_DATE(nowTimeMinusLimit,
					  cNowTimeMinusLimit);


	// Now, the usual ;-)
	if (!negativeFeedbackOnly)
	{
			OpenAndParse(&mpCDARecentFeedbackFromUser,
						 SQL_GetRecentFeedbackDetailFromUser);
	}
	else
	{
			OpenAndParse(&mpCDARecentNegativeFeedbackFromUser,
						 SQL_GetRecentNegativeFeedbackDetailFromUser);
	}

	Bind(":id", &id);
	Bind(":cid", &commentingId);
	Bind(":limit", cNowTimeMinusLimit);

	Define(1, cTheTime, sizeof(cTheTime));
	Define(2, (int *)&type);
	Define(3, cUserId, sizeof(cUserId), &cUserIdInd);
	Define(4, &cUserState);
	Define(5, &cScore, &cScoreInd);
	Define(6, cHost, sizeof(cHost));
	Define(7, text, sizeof(text));
	Define(8, cUser_id_last_modified, sizeof(cUser_id_last_modified), &cUser_last_change_ind);
	Define(9, cEmail, sizeof(cEmail), &cEmailInd);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
		return NULL;

	ORACLE_DATEToTime(cTheTime, &theTime);
	if (cUser_last_change_ind != -1)
		ORACLE_DATEToTime(cUser_id_last_modified, &cUser_id_last_modified_time);
	else
		cUser_id_last_modified_time = 0;

	// Check for NULL score
	if (cScoreInd == -1)
		cScore = 0;

	pFeedbackItem	= 
		new clsFeedbackItem(id,
							theTime,
							type,
							commentingId,
							cUserId,
							cEmail,
							cUserState,
							cScore,
							cHost,
							text,
							cUser_id_last_modified_time,
							"0");

	// Close
	if (!negativeFeedbackOnly)
		Close(&mpCDARecentFeedbackFromUser);
	else
		Close(&mpCDARecentNegativeFeedbackFromUser);
		
	return pFeedbackItem;
}

static const char *SQL_GetRecentFeedbackDetailFromHost =
"select		TO_CHAR(detail.time,							\
					'YYYY-MM-DD HH24:MI:SS'),				\
			detail.comment_type,							\
			detail.commenting_id,							\
			users.userid,									\
			users.user_state,								\
			feedback.score,									\
 			commenting_host,								\
			comment_text,									\
			TO_CHAR(users.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),					\
			users.email										\
	from	ebay_feedback_detail detail,					\
			ebay_users users,								\
			ebay_feedback feedback							\
	where	detail.id = :id									\
			and		detail.commenting_host = :host			\
			and		detail.time >							\
						TO_DATE(:limit,						\
								'YYYY-MM-DD HH24:MI:SS')	\
			and		detail.commenting_id = users.id (+)		\
			and		detail.commenting_id = feedback.id (+)	\
			and		rownum = 1								\
			and		split = '0'";

static const char *SQL_GetRecentNegativeFeedbackDetailFromHost =
"select		TO_CHAR(detail.time,							\
					'YYYY-MM-DD HH24:MI:SS'),				\
			detail.comment_type,							\
			detail.commenting_id,							\
			users.userid,									\
			users.user_state,								\
			feedback.score,									\
 			commenting_host,								\
			comment_text,									\
			TO_CHAR(users.userid_last_change,				\
				'YYYY-MM-DD HH24:MI:SS'),					\
			users.email										\
	from	ebay_feedback_detail detail,					\
			ebay_users users,								\
			ebay_feedback feedback							\
	where	detail.id = :id									\
			and		detail.commenting_host = :host			\
			and		detail.time >							\
						TO_DATE(:limit,						\
								'YYYY-MM-DD HH24:MI:SS')	\
			and		detail.comment_type = 2					\
			and		detail.commenting_id = users.id (+)		\
			and		detail.commenting_id = feedback.id (+)	\
			and		rownum = 1								\
			and		split = '0'";



clsFeedbackItem *clsDatabaseOracle::RecentFeedbackFromHost(
								int id,
								char *pCommentingHost,
								time_t timeLimit,
								bool negativeFeedbackOnly)
{
	time_t		nowTime;
	time_t		nowTimeMinusLimit;
	char		cNowTimeMinusLimit[32];


	char				cTheTime[32];
	time_t				theTime;
	FeedbackTypeEnum	type;
	int					cId;
	char				cUserId[256];
	sb2					cUserIdInd;
	char				cEmail[65];
	sb2					cEmailInd;
	int					cUserState;
	int					cScore;
	sb2					cScoreInd;
	char				cHost[32];
	char				text[128];		//lint !e578 Don't worry about text
	clsFeedbackItem		*pFeedbackItem = NULL;

	// so we can show mask of commenting user
	time_t				cUser_id_last_modified_time;
	char				cUser_id_last_modified[32];
	sb2					cUser_last_change_ind;

	// Let's do the time first
	nowTime				= time(0);
	nowTimeMinusLimit	= nowTime - timeLimit;

	// fetch twice, one from the old table and the other from
	// 10x tables
	int		FetchTimes = 0;

	TimeToORACLE_DATE(nowTimeMinusLimit,
					  cNowTimeMinusLimit);


	// Now, the usual ;-)
	if (!negativeFeedbackOnly)
	{
			OpenAndParse(&mpCDARecentFeedbackFromHost,
						 SQL_GetRecentFeedbackDetailFromHost);
	}
	else
	{
			OpenAndParse(&mpCDARecentNegativeFeedbackFromHost,
						 SQL_GetRecentNegativeFeedbackDetailFromHost);
	}

	Bind(":id", &id);
	Bind(":host", pCommentingHost);
	Bind(":limit", cNowTimeMinusLimit);

	Define(1, cTheTime, sizeof(cTheTime));
	Define(2, (int *)&type);
	Define(3, &cId);
	Define(4, cUserId, sizeof(cUserId), &cUserIdInd);
	Define(5, &cUserState);
	Define(6, &cScore, &cScoreInd);
	Define(7, cHost, sizeof(cHost));
	Define(8, text, sizeof(text));
	Define(9, cUser_id_last_modified, sizeof(cUser_id_last_modified), &cUser_last_change_ind);
	Define(10, cEmail, sizeof(cEmail), &cEmailInd);

	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		if (!negativeFeedbackOnly)
			Close(&mpCDARecentFeedbackFromHost);

		else
			Close(&mpCDARecentNegativeFeedbackFromHost);
		
		return NULL;
	}

	ORACLE_DATEToTime(cTheTime, &theTime);
	if (cUser_last_change_ind != -1)
		ORACLE_DATEToTime(cUser_id_last_modified, &cUser_id_last_modified_time);
	else
		cUser_id_last_modified_time = 0;

	// check for NULL score
	if (cScoreInd == -1)
		cScore = 0;

	pFeedbackItem	= 
		new clsFeedbackItem(id,
							theTime,
							type,
							cId,
							cUserId,
							cEmail,
							cUserState,
							cScore,
							cHost,
							text,
							cUser_id_last_modified_time,
							0);

	if (!negativeFeedbackOnly)
		Close(&mpCDARecentFeedbackFromHost);

	else
		Close(&mpCDARecentNegativeFeedbackFromHost);
	

	return pFeedbackItem;
}

//
// VoidFeedbackLeftByUser
//
static const char *SQL_VoidFeedbackLeftByUser =
"update %s										\
	set		comment_type = comment_type + 3		\
	where	commenting_id = :cid				\
			and		(comment_type = 1			\
			or		comment_type = 2)";

static bool
sort_feedback_by_commenting_id(clsFeedbackItem *p1,
					clsFeedbackItem *p2)
{
	return (p1->mCommentingId < p2->mCommentingId);
}

void clsDatabaseOracle::VoidFeedbackLeftByUser(int commenting_id)
{
	int	  Tables;
	char* pTheStatement;

	// Some asserts to make sure we haven't changed these
	// values.
	//lint --e(506, 774) Lint hates these const values boolean.
	if (!((FEEDBACK_POSITIVE == 1) &&
		  (FEEDBACK_NEGATIVE == 2) &&	
		  (FEEDBACK_POSITIVE_SUSPENDED == 4) &&
		  (FEEDBACK_NEGATIVE_SUSPENDED == 5)))
	{
#ifdef _MSC_VER
		gApp->LogEvent("VERY BAD thing in clsDatabaseOracle::VoidFeedbackLeftByUser, "
			"values for feedback types changed. No feedback voided.\n");
#endif
		return;
	}

	FeedbackItemVector *pFeedbackDetailVec = new (FeedbackItemVector);
	FeedbackItemVector::iterator i;
	clsFeedback *pFeedback;
	int last_id_seen = -1;

	//
	// NOTE: mCommentingId is the user id who received the feedback
	// 
	GetFeedbackDetailLeftByUser(commenting_id, pFeedbackDetailVec);

	// If they haven't left any feedback, we're done.
	if (!pFeedbackDetailVec->size())
	{
		delete pFeedbackDetailVec;
		return;
	}

	// First, we set all of their positive comments to positive
	// suspended, and all of their negative comments to negative
	// suspended. That way we can undo this if we want.

	// begin transaction
	// 02/25/99 AlexP: removed Begin/End
	// Begin();

	// There are 11 tables, let's go throught them
	// wired off the feedback split!
	for (Tables = 10; Tables <=10;  Tables++)
	{
		// fill in the table name before parsing the statement
		pTheStatement = FillFeedbackDetailTableName(SQL_VoidFeedbackLeftByUser, 
			Tables);

		OpenAndParse(&(mpCDAVoidFeedbackLeftByUser[Tables]), pTheStatement);

		// Bind it, baby
		Bind(":cid", &commenting_id);

		// Do it...
		Execute();

		Commit();
		Close(&(mpCDAVoidFeedbackLeftByUser[Tables]));
		SetStatement(NULL);

		delete [] pTheStatement;
	}

	// If they haven't left any feedback, we're done.
	if (pFeedbackDetailVec->size() > 0)
	{
		sort(pFeedbackDetailVec->begin(), pFeedbackDetailVec->end(), 
			sort_feedback_by_commenting_id);
	}

	for (i = pFeedbackDetailVec->begin(); i != pFeedbackDetailVec->end(); ++i)
	{
		if ((*i)->mCommentingId == last_id_seen || (*i)->mType == FEEDBACK_NEUTRAL)
		{
			delete *i;
			continue;
		}

		last_id_seen = (*i)->mCommentingId;

		pFeedback = GetFeedback((*i)->mCommentingId);
		pFeedback->SetScore(pFeedback->RecomputeScore());

		delete pFeedback;
		delete *i;
	}

	// Commit transaction
	// 02/25/99 AlexP: removed Begin/End
	// End();

	pFeedbackDetailVec->erase(pFeedbackDetailVec->begin(), 
							 pFeedbackDetailVec->end());
	delete pFeedbackDetailVec;

	return;
}

//
// RestoreFeedbackLeftByUser
// to invalidate all users in the id list
//
static const char *SQL_RestoreFeedbackLeftByUser =
"update %s					\
	set		comment_type = comment_type - 3		\
	where	commenting_id = :cid				\
			and		( comment_type = 4			\
			or		comment_type = 5)";

void clsDatabaseOracle::RestoreFeedbackLeftByUser(int commenting_id)
{
	int	  Tables;
	char* pTheStatement;

	// Some asserts to make sure we haven't changed these
	// values.
	//lint --e(506, 774) Lint hates these const values boolean.
	if (!((FEEDBACK_POSITIVE == 1) &&
		  (FEEDBACK_NEGATIVE == 2) &&
		  (FEEDBACK_POSITIVE_SUSPENDED == 4) &&
		  (FEEDBACK_NEGATIVE_SUSPENDED == 5)))
	{
#ifdef _MSC_VER
		gApp->LogEvent("VERY BAD thing in clsDatabaseOracle::RestoreFeedbackLeftByUser, "
			"values for feedback types changed. No feedback restored.\n");
#endif
		return;
	}

	FeedbackItemVector *pFeedbackDetailVec = new (FeedbackItemVector);
	FeedbackItemVector::iterator i;
	clsFeedback *pFeedback;
	int last_id_seen = -1;

	GetFeedbackDetailLeftByUser(commenting_id, pFeedbackDetailVec);

	// If they haven't left any feedback, we're done.
	if (!pFeedbackDetailVec->size())
	{
		delete pFeedbackDetailVec;
		return;
	}

	// First, we set all of their positive suspended comments to positive
	// and all of their negative suspended comments to negative

	// start transaction
	// 02/25/99 AlexP: removed Begin/End
	// Begin();

	// There are 11 tables, let's go throught them
	// wired off the feedback split!
	for (Tables = 10; Tables <=10;  Tables++)
	{
		// fill in the table name before parsing the statement
		pTheStatement = FillFeedbackDetailTableName(SQL_RestoreFeedbackLeftByUser, 
			Tables);

		OpenAndParse(&(mpCDARestoreFeedbackLeftByUser[Tables]), pTheStatement);

		// Bind it, baby
		Bind(":cid", &commenting_id);

		// Do it...
		Execute();

		Commit();
		Close(&(mpCDARestoreFeedbackLeftByUser[Tables]));
		SetStatement(NULL);

		delete [] pTheStatement;
	}

	// Next, we need to recompute the feedback scores of all the
	// people who had feedback left by this user.

	// If they haven't left any feedback, we're done.
	if (pFeedbackDetailVec->size() > 0)
	{
		sort(pFeedbackDetailVec->begin(), pFeedbackDetailVec->end(), 
			 sort_feedback_by_commenting_id);
	}

	for (i = pFeedbackDetailVec->begin(); i != pFeedbackDetailVec->end(); ++i)
	{
		// Skip them if we've seen a non-neutral comment, or if this
		// comment is neutral (and thus wouldn't be affected anyway.)
		if ((*i)->mCommentingId == last_id_seen || (*i)->mType == FEEDBACK_NEUTRAL)
		{
			delete *i;
			continue;
		}

		last_id_seen = (*i)->mCommentingId;

		pFeedback = GetFeedback((*i)->mCommentingId);
		pFeedback->InvalidateExtendedFeedback();
		pFeedback->SetScore(pFeedback->RecomputeScore());
		delete pFeedback;
		delete *i;
	}

	// Commit transaction
	// 02/25/99 AlexP: removed Begin/End
	// End();

	pFeedbackDetailVec->erase(pFeedbackDetailVec->begin(), 
							 pFeedbackDetailVec->end());
	delete pFeedbackDetailVec;

	return;
}

//	
// UserHasFeedbackFromUser
//
static const char *SQL_UserHasFeedbackFromUser = 
"select count(*) from %s					\
	where	id = :id						\
	and		commenting_id = :cid			\
	and		(comment_type = 1				\
			  or comment_type = 2)";

bool clsDatabaseOracle::UserHasFeedbackFromUser(int id,
												int commenting_id,
												bool Split)
{
	int		count;
	int		SubTable = Split ? id % 10 : 10 ;
	char*	pTheStatement;

	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_UserHasFeedbackFromUser, SubTable);
	OpenAndParse(&(mpCDAUserHasFeedbackFromUser[SubTable]), pTheStatement);
	delete [] pTheStatement;

	Bind(":id", &id);
	Bind(":cid", &commenting_id);

	Define(1, &count);

	ExecuteAndFetch();

	Close(&(mpCDAUserHasFeedbackFromUser[SubTable]));
	SetStatement(NULL);

	if (count > 0)
		return true;
	else
		return false;
}

//
// SetFeedbackScore
//
// Updates the user's score (and last_update date)
// 
static const char *SQL_SetFeedbackScore =
 "update ebay_feedback						\
	set score = :score,						\
		last_update = sysdate				\
	where id = :id";

void clsDatabaseOracle::SetFeedbackScore(int id,
											int score)
{
	// Mirroring feedback to ebay_users.
	static const char *SQL_SetFeedbackScoreToUsers =
		"update ebay_users "
		"  set score = :score "
		"where id = :id";
	OpenAndParse(&mpCDASetFeedbackScoreToUsers, SQL_SetFeedbackScoreToUsers);
	Bind(":id", &id);
	Bind(":score", &score);
	Execute();
	Commit();
	Close(&mpCDASetFeedbackScoreToUsers);
	SetStatement(NULL);


	// And then do it in ebay_feedback
	OpenAndParse(&mpCDASetFeedbackScore, SQL_SetFeedbackScore);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":score", &score);

	// Do it...
	Execute();

	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDASetFeedbackScore);
		SetStatement(NULL);
		AddFeedback(id, score, 0);
	}
	else
	{
		Commit();
		Close(&mpCDASetFeedbackScore);
		SetStatement(NULL);
	}

	return;
}
//
// UpdateFeedbackFlags
//
// Updates the user's flags (and last_update date)
//
static const char *SQL_UpdateFeedbackFlags =
 "update ebay_feedback						\
	set flags = :flags,						\
		last_update = sysdate				\
	where id = :id";

void clsDatabaseOracle::UpdateFeedbackFlags(int id,
											int flags)
{
	OpenAndParse(&mpCDAOneShot, SQL_UpdateFeedbackFlags);

	// Bind it, baby
	Bind(":id", &id);
	Bind(":flags", &flags);

	// Do it...
	Execute();


	// If there were no rows processed, then 
	// there's no summary record for the user,
	// and we need to add one
	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		AddFeedback(id, 0, flags);
	}
	else
	{
		Commit();
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	Commit();

	return;
}

// inna start
//
// UpdateFeedbackSplitFlag
//
// Updates the user's split flag (and last_update date)
//
static const char *SQL_UpdateFeedbackSplitFlag =
"update		ebay_feedback						\
	set		split = '1',						\
			last_update = sysdate				\
	where	id = :id";

void clsDatabaseOracle::UpdateFeedbackSplitFlag(int id)
{
	OpenAndParse(&mpCDAOneShot, SQL_UpdateFeedbackSplitFlag);

	// Bind it
	Bind(":id", &id);

	// Do it...
	Execute();

	if (CheckForNoRowsUpdated())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return;
	}

	// Otherwise, we're done
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

}
//inna end

//
// AddFeedbackDetail
//
// Inserts a feedback detail item into the database
//
static const char *SQL_AddFeedbackDetail = 
 "insert into %s							\
  (	id,										\
	time,									\
	commenting_id,							\
	commenting_host,						\
	comment_type,							\
	comment_score,							\
	comment_text,							\
	item									\
  )											\
  values									\
  (	:id,									\
	sysdate,								\
	:cid,									\
	:host,									\
	:type,									\
	:score,									\
	:text,									\
	:item									\
  )";
	
void clsDatabaseOracle::AddFeedbackDetail(
									int id,
									int commentingId,
									char *pCommentingHost,
									FeedbackTypeEnum type,
									int score,
									char *pText,
									bool Split,
									int	item/*=0*/
									   )
{
	int			SubTable = Split ? id % 10 : 10 ;
	char*		pTheStatement;

	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_AddFeedbackDetail, SubTable);
	OpenAndParse(&(mpCDAAddFeedbackDetail[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind it, baby
	Bind(":id", &id);
	Bind(":cid", &commentingId);
	Bind(":host", pCommentingHost);
	Bind(":type", (int *)&type);
	Bind(":score", &score);
	Bind(":text", pText);
	Bind(":item", &item);

	// Do it...
	Execute();
	Commit();

	// Leave it!
	Close(&(mpCDAAddFeedbackDetail[SubTable]));
	SetStatement(NULL);

	return;
}

// inna start
// SplitFeedbackDetail
//
// Inserts a feedback detail into the new ebay_feedback_detailXX table
// the inteserted row is exact copy of a row in ebay_feedback_detail table
//
static const char *SQL_SplitFeedbackDetail = 
 "insert into %s							\
  (	id,										\
	time,									\
	commenting_id,							\
	commenting_host,						\
	comment_type,							\
	comment_score,							\
	comment_text,							\
	response,								\
	followup,								\
	item									\
  )											\
  values									\
  (	:id,									\
	TO_DATE(:time,							\
				'YYYY-MM-DD HH24:MI:SS'),	\
	:cid,									\
	:chost,									\
	:ctype,									\
	:cscore,								\
	:ctext,									\
	:response,								\
	:followup,								\
	:item									\
  )";

void clsDatabaseOracle::SplitFeedbackDetail(clsFeedbackItem *pFeedbackItem)
{
	int			SubTable = pFeedbackItem->mId % 10;
	char*		pTheStatement;
	time_t		theTime=pFeedbackItem->mTime;		
	struct tm	*pLocalTime;
	char		ctheTime[32];

	int		id=pFeedbackItem->mId;
	int		commentingId=pFeedbackItem->mCommentingId;
	char	*pCommentingHost=pFeedbackItem->mHost;
	int		commentType=pFeedbackItem->mType;
	int		commentScore=pFeedbackItem->mCommentingUserScore;
	char	*pCommentText=pFeedbackItem->mText;
	char	*pResponse=pFeedbackItem->mResponse;
	char	*pFollowUp=pFeedbackItem->mFollowUp;
	int		item=pFeedbackItem->mItem;


	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_SplitFeedbackDetail, SubTable);
	OpenAndParse(&(mpCDASplitFeedbackDetail[SubTable]), pTheStatement);
	delete [] pTheStatement;

	pLocalTime	= localtime(&theTime);
	TM_STRUCTToORACLE_DATE(pLocalTime, ctheTime);
 
	// Bind
	Bind(":id", &id);
	Bind(":time", ctheTime);
	Bind(":cid", &commentingId);
	Bind(":chost", pCommentingHost);
	Bind(":ctype", &commentType);
	Bind(":cscore", &commentScore);
	Bind(":ctext", pCommentText);
	Bind(":response", pResponse);								 
	Bind(":followup", pFollowUp);							 
	Bind(":item", &item);

	// Do it...
	Execute();

	//inna do not commit every record do it in calling program every 20
	//Commit();

	// Leave it!
	Close(&(mpCDASplitFeedbackDetail[SubTable]));
	SetStatement(NULL);

	return;
}
//inna end

//
// 
//TransferFeedback
static const char *SQL_TransferFeedback = 
"update %s									\
	set		id = :toid						\
	where	id = :fromid";

static const char *SQL_TransferFeedback_Two =
"insert into %s (select * from %s where id=:fromid)";

static const char *SQL_DeleteFeedbackDetail =
"delete from %s where id=:fromid";

void clsDatabaseOracle::TransferFeedback(clsUser *pFromUser,
										 clsUser *pToUser)
{
	int			fromId;
	int			toId;
	clsFeedback* pFromFeedback;
	clsFeedback* pToFeedback;
	int			fromTable;
	int			toTable;
	char*		pTheStatement;

	fromId = pFromUser->GetId();
	toId = pToUser->GetId();

	pFromFeedback = pFromUser->GetFeedback();
	pToFeedback = pToUser->GetFeedback();

	gApp->GetDatabase()->InvalidateFeedbackList(pFromFeedback->GetId());
	gApp->GetDatabase()->InvalidateFeedbackList(pToFeedback->GetId());

	// determine whether the feedbacks are in the same table
	fromTable = pFromFeedback->IsSplit() ? fromId % 10 : 10;
	toTable   = pToFeedback->IsSplit() ? toId % 10 : 10;

	// Start transaction
	Begin();

	if (fromTable != toTable)
	{
		// copy feedback between two tables
		pTheStatement = FillFeedbackDetailTableNames(SQL_TransferFeedback_Two, 
			toTable,
			fromTable);

		// Open
		OpenAndParse(&mpCDAOneShot, pTheStatement);
		delete [] pTheStatement;

		Bind(":fromid", &fromId);

		// Execute + commit
		Execute();
		Commit();

		Close(&mpCDAOneShot);
		SetStatement(NULL);

		// delete the feedback from the old table
		pTheStatement = FillFeedbackDetailTableName(SQL_DeleteFeedbackDetail, 
			fromTable);

		// Open
		OpenAndParse(&mpCDAOneShot, pTheStatement);
		delete [] pTheStatement;

		Bind(":fromid", &fromId);

		// Execute + commit
		Execute();
		Commit();

		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	// update the id (in the same table or after copying)
	pTheStatement = FillFeedbackDetailTableName(SQL_TransferFeedback, toTable);

	// Open
	OpenAndParse(&mpCDAOneShot, pTheStatement);

	delete [] pTheStatement;

	Bind(":toid", &toId);
	Bind(":fromid", &fromId);

	// Execute + commit
	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// End Transaction
	End();

	return;
}

//
// TransferFeedbackLeft
//
static const char *SQL_TransferFeedbackLeft = 
"update %s									\
	set		commenting_id = :toid			\
	where	commenting_id = :fromid";

void clsDatabaseOracle::TransferFeedbackLeft(clsUser *pFromUser,
											 clsUser *pToUser)
{
	int		fromId;
	int		toId;
	int		Tables;
	char*	pTheStatement;

	fromId	= pFromUser->GetId();
	toId	= pToUser->GetId();

	// Start transaction
	Begin();

	// There are 11 tables, let's go throught them
	for (Tables = 0; Tables <=10;  Tables++)
	{
		// fill in the table name before parsing the statement
		pTheStatement = FillFeedbackDetailTableName(SQL_TransferFeedbackLeft, 
			Tables);

		OpenAndParse(&(mpCDATransferFeedbackLeft[Tables]), pTheStatement);

		// Bind
		Bind(":toid", &toId);
		Bind(":fromid", &fromId);

		// Execute + commit
		Execute();
		Commit();

		// Done
		Close(&(mpCDATransferFeedbackLeft[Tables]));
		SetStatement(NULL);

		delete [] pTheStatement;
	}

	// End transaction
	End();

	return;
}

//
// DeleteFeedback
//
// Deletes a feedback record and its details
// Comment out deletion of details for the moment;
// Too dangerous!!!
//
static const char *SQL_DeleteFeedback =
 "delete from ebay_feedback				\
	where	id = :id";

// static const char *SQL_DeleteFeedbackDetail =
//  "delete from ebay_feedback_detail "
// 	"where	id = :id";

void clsDatabaseOracle::DeleteFeedback(int id)
{
	// delete feedback detail first
//	Open(&mpCDAOneShot);
//	SetStatement(mpCDAOneShot);
//	Parse(SQL_DeleteFeedbackDetail);

	// Ok, let's do some binds
//	Bind(":id", &id);

	// Just do it!
//	Execute();
//	Close(&mpCDAOneShot);
	DeleteFeedbackList(id);

	OpenAndParse(&mpCDAOneShot, SQL_DeleteFeedback);

	// Ok, let's do some binds
	Bind(":id", &id);

	// Just do it!
	Execute();
	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


static const char *SQL_AddManyFeedbackDetail = 
 "insert into ebay_feedback_detail			\
  (	id,										\
	time,									\
	commenting_id,							\
	commenting_host,						\
	comment_type,							\
	comment_score,							\
	comment_text							\
  )											\
  values									\
  (	:id,									\
	TO_DATE(:fbtime,						\
			'YYYY-MM-DD HH24:MI:SS'),		\
	:cid,									\
	:host,									\
	:type,									\
	:score,									\
	:text									\
  )";

void clsDatabaseOracle::AddManyFeedbackDetail(
								int	count,
								int *pIds,
								char *pTimes,
								int	timeLen,
								int *pCommentingIds,
								char *pCommentingHosts,
								int hostLen,
								FeedbackTypeEnum *pTypes,
								int *pScores,
								char *pTexts,
								int textLen
											 )
{
	int	rc;

	// need to invalidate feedback list for every single user in this list!

	// Open + Parse
	OpenAndParse(&mpCDAOneShot, SQL_AddManyFeedbackDetail);

	// Bind it, baby
	Bind(":id", pIds);
	Bind(":fbtime", pTimes, timeLen); 
	Bind(":cid", pCommentingIds);
	Bind(":host", pCommentingHosts, hostLen);
	Bind(":type", (int *)pTypes);
	Bind(":score", pScores);
	Bind(":text", pTexts, textLen);

	// Do it...
	// Call oexn directly
	rc	= oexn((cda_def *)mpCDACurrent, count, 0);
	Check(rc);

	Commit();

	// Leave it!
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;

}

//
// update the response column in the table of ebay_feedback_detail
//
const char *SQL_UpdateFeedbackResponse = 
"update %s														\
	set response = :response									\
	where	id = :commentee and									\
			time = TO_DATE(:time, 'YYYY-MM-DD HH24:MI:SS') and	\
			commenting_id = :commentor";					

void clsDatabaseOracle::UpdateResponse(int Commentor, 
									   time_t CommentDate, 
									   int Commentee, 
									   const char* pResponse,
									   bool Split)
{
	char CommentTime[32];
	int	 SubTable = Split ? Commentee % 10 : 10 ;
	char* pTheStatement;

	// fill in table name before parsing the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_UpdateFeedbackResponse, SubTable);

	OpenAndParse(&(mpCDAUpdateFeedbackResponse[SubTable]), pTheStatement);
	delete [] pTheStatement;
	
	// Date conversions
	TimeToORACLE_DATE(CommentDate, CommentTime);
	
	// Bind
	Bind(":commentee", &Commentee);
	Bind(":time", CommentTime, sizeof(CommentTime));
	Bind(":commentor", &Commentor);
	Bind(":response", pResponse);

	// Let's do it!
	Execute();

	// Test for now rows processed here!

	Commit();

	Close(&(mpCDAUpdateFeedbackResponse[SubTable]));
	SetStatement(NULL);

	return;
}

//
// update the followup column in the table of ebay_feedback_detail
//
const char *SQL_UpdateFeedbackFollowUp = 
"update %s														\
	set followup = :followup									\
	where	id = :commentee and									\
			time = TO_DATE(:time, 'YYYY-MM-DD HH24:MI:SS') and	\
			commenting_id = :commentor";					

void clsDatabaseOracle::UpdateFollowUp(int Commentor, 
									   time_t CommentDate, 
									   int Commentee, 
									   const char* pFollowUp,
									   bool Split)
{
	char CommentTime[32];
	int	 SubTable = Split ? Commentee % 10 : 10 ;
	char* pTheStatement;

	// fill in table name before parsing the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_UpdateFeedbackFollowUp, SubTable);

	OpenAndParse(&(mpCDAUpdateFeedbackFollowUp[SubTable]), pTheStatement);
	delete [] pTheStatement;
	
	// Date conversions
	TimeToORACLE_DATE(CommentDate, CommentTime);
	
	// Bind
	Bind(":commentee", &Commentee);
	Bind(":time", CommentTime, sizeof(CommentTime));
	Bind(":commentor", &Commentor);
	Bind(":followup", pFollowUp);

	// Let's do it!
	Execute();

	// Test for now rows processed here!

	Commit();

	Close(&(mpCDAUpdateFeedbackFollowUp[SubTable]));
	SetStatement(NULL);

	return;
}

//
// GetFeedbackDetailCount
//
// Get the number of the feedback detail records
//

static const char* SQL_GetFeedbackDetailCount =
" select count(*) from %s		\
	where id = :id";

int clsDatabaseOracle::GetFeedbackDetailCount(int id, bool Split)
{
	int Count;
	int	 SubTable = Split ? id % 10 : 10 ;
	char* pTheStatement;

	// fill in table name before parsing the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetFeedbackDetailCount, SubTable);

	OpenAndParse(&(mpCDAGetFeedbackDetailCount[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the rest of the input variables
	Bind(":id", &id);
	
	// Bind those happy little output variables. 
	Define(1, &Count);

	// Let's do the SQL
	Execute();

	Fetch();

	Close (&(mpCDAGetFeedbackDetailCount[SubTable]));
	SetStatement(NULL);

	return Count;
}

//
// HasResponse
//
// Get the number of the feedback detail records
//

static const char *SQL_GetResponse =
 "select	response								\
			from	%s								\
			where	id = :id						\
			and		commenting_id = :commentor		\
			and		time = TO_DATE(:comment_date, 'YYYY-MM-DD HH24:MI:SS')";

bool clsDatabaseOracle::HasResponse(int Commentor, 
									time_t CommentDate, 
									int Commentee,
									bool Split)
{
	char Response[81];
	char CommentTime[15];
	bool HasIt = false;
	sb2	 ResponseInd;
	int	 SubTable = Split ? Commentee % 10 : 10 ;
	char* pTheStatement;

	// Date conversions
	TimeToORACLE_DATE(CommentDate, CommentTime);

	pTheStatement = FillFeedbackDetailTableName(SQL_GetResponse, SubTable);

	OpenAndParse(&(mpCDAGetResponse[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the rest of the input variables
	Bind(":commentor", &Commentor);
	Bind(":comment_date", CommentTime);
	Bind(":id", &Commentee);
	
	// Bind those happy little output variables. 
	Define(1, Response, sizeof(Response), &ResponseInd);

	// Let's do the SQL
	Execute();

	Fetch();

	if (!CheckForNoRowsFound())
	{
		if (ResponseInd != -1 && Response[0])
		{
			HasIt = true;
		}
	}

	Close (&(mpCDAGetResponse[SubTable]));
	SetStatement(NULL);

	return HasIt;
}

//
// HasFollowUp
//
// Get the number of the feedback detail records
//

static const char *SQL_GetFollowUp =
 "select	followup							\
			from	%s							\
			where	id = :id					\
			and		commenting_id = :commentor	\
			and		time = TO_DATE(:comment_date, 'YYYY-MM-DD HH24:MI:SS')";

bool clsDatabaseOracle::HasFollowUp(int Commentor, 
									time_t CommentDate, 
									int Commentee,
									bool Split)
{
	char FollowUp[81];
	char CommentTime[15];
	bool HasIt = false;
	sb2	 FollowUpInd;
	int	 SubTable = Split ? Commentee % 10 : 10 ;
	char* pTheStatement;

	// Date conversions
	TimeToORACLE_DATE(CommentDate, CommentTime);

	pTheStatement = FillFeedbackDetailTableName(SQL_GetFollowUp, SubTable);
	OpenAndParse(&(mpCDAGetFollowUp[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the rest of the input variables
	Bind(":commentor", &Commentor);
	Bind(":comment_date", CommentTime);
	Bind(":id", &Commentee);
	
	// Bind those happy little output variables. 
	Define(1, FollowUp, sizeof(FollowUp), &FollowUpInd);

	// Let's do the SQL
	Execute();

	Fetch();

	if (!CheckForNoRowsFound())
	{
		if (FollowUpInd != -1 && FollowUp[0])
		{
			HasIt = true;
		}
	}

	Close (&(mpCDAGetFollowUp[SubTable]));
	SetStatement(NULL);

	return HasIt;
}

static const char *SQL_GetFeedbackItem =
"select "
	"detail.comment_type, "
	"detail.commenting_id, "
	"users.userid, "
	"users.user_state, "
	"feedback.score, "
 	"detail.commenting_host, "
	"detail.comment_text, "
	"detail.item, "
	"detail.response, "
	"detail.followup, "
	"detail.rowid, "
	"TO_CHAR(users.userid_last_change, 'YYYY-MM-DD HH24:MI:SS'), "
	"users.email "
"from %s detail, "
	"ebay_users users, "
	"ebay_feedback feedback "
"where   detail.id = :id "
	"and detail.commenting_id = :commentor "
	"and detail.time = TO_DATE(:commenting_date, 'YYYY-MM-DD HH24:MI:SS') "
	"and detail.commenting_id = users.id (+) "
	"and detail.commenting_id = feedback.id (+)";

clsFeedbackItem* clsDatabaseOracle::GetFeedbackItem(
					  int Commentor,
					  time_t CommentingDate,
					  int Id,
					  bool Split)
{
	FeedbackTypeEnum	type;
	int					cId;
	char				cUserId[256];
	char				cEmail[65];
	sb2					cEmailInd;
	int					cUserState;
	int					cScore;
	sb2					cScoreInd;
	char				cHost[32];
	char				text[128];	//lint !e578 and don't worry about text
	int					item;	//lint !e578 and don't worry about text
	sb2					itemInd;
	char				response[128];	//lint !e578 and don't worry about text
	sb2					responseInd;
	char				followup[128];	//lint !e578 and don't worry about text
	sb2					followUpInd;

	// so we can show mask of commenting user
	time_t				cUser_id_last_modified_time;
	char				cUser_id_last_modified[32];
	char				cRowId[FEEDBACK_LIST_ROWID_SIZE + 1];

	clsFeedbackItem		*pFeedbackItem = NULL;
	int					SubTable = Split ? Id % 10 : 10 ;
	char*				pTheStatement;
	char				CommentTime[15];

	// Date conversions
	TimeToORACLE_DATE(CommentingDate, CommentTime);

	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetFeedbackItem, SubTable);
	OpenAndParse(&(mpCDAGetFeedbackItem[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Bind the input variable
	Bind(":id", &Id);
	Bind(":commentor", &Commentor);
	Bind(":commenting_date", CommentTime);

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!

	Define(1, (int*) &type);
	Define(2, &cId);
	Define(3, cUserId, sizeof(cUserId));
	Define(4, &cUserState);
	Define(5, &cScore, &cScoreInd);
	Define(6, cHost, sizeof(cHost));
	Define(7, text, sizeof(text));
	Define(8, &item, &itemInd);
	Define(9, response, sizeof(response), &responseInd);
	Define(10, followup, sizeof(followup), &followUpInd);
	Define(11, cRowId, sizeof(cRowId));
	Define(12, cUser_id_last_modified, sizeof(cUser_id_last_modified));
	Define(13, cEmail, sizeof(cEmail), &cEmailInd);
	
	Execute();
	Fetch();
	if (CheckForNoRowsFound())
	{
		ocan((struct cda_def *)mpCDACurrent);
		Close(&(mpCDAGetFeedbackItem[SubTable]));
		SetStatement(NULL);
		return NULL;
	}

	// convert time
	ORACLE_DATEToTime(cUser_id_last_modified, &cUser_id_last_modified_time);

	// check for NULL score
	if (cScoreInd == -1)
		cScore = 0;

	if (itemInd == -1)
		item = 0;

	pFeedbackItem	= 
		new clsFeedbackItem(Id,
							CommentingDate,
							type,
							cId,
							cUserId,
							cEmail,
							cUserState,
							cScore,
							cHost,
							text,
							cUser_id_last_modified_time,
							cRowId,
							0,
							0,
							item,
							response,
							followup);

	// Close 
	Close(&(mpCDAGetFeedbackItem[SubTable]));
	SetStatement(NULL);

	return pFeedbackItem;		//lint !e429 Don't worry about pFeedbackItem
}

//
// Fill in the table name for feedback detail
//
static char* FEEDBACK_DETAIL_TABLE =
"ebay_feedback_detail";

char* clsDatabaseOracle::FillFeedbackDetailTableName(const char* pSrcStatement,
													 int SubTable)
{
	char	TableName[23];
	char*	pDesStatement = new char[strlen(pSrcStatement) + strlen(FEEDBACK_DETAIL_TABLE) + 3];

	if (SubTable == 10)
	{
		// the old table
		sprintf(pDesStatement, pSrcStatement, FEEDBACK_DETAIL_TABLE);
	}
	else
	{
		// 10 x tables
		sprintf(TableName, "%s_%01d", FEEDBACK_DETAIL_TABLE, SubTable);
		sprintf(pDesStatement, pSrcStatement, TableName);
	}

	return pDesStatement;
}


char* clsDatabaseOracle::FillFeedbackDetailTableNames(const char* pSrcStatement,
													 int SubTable1,
													 int SubTable2)
{
	char	TableName1[23];
	char	TableName2[23];
	char*	pDesStatement = new char[strlen(pSrcStatement) + strlen(FEEDBACK_DETAIL_TABLE) + 3];

	if (SubTable1 == 10)
	{
		sprintf(TableName1, "%s", FEEDBACK_DETAIL_TABLE);
	}
	else
	{
		// 10 x tables
		sprintf(TableName1, "%s%02d", FEEDBACK_DETAIL_TABLE, SubTable1);
	}

	if (SubTable2 == 10)
	{
		sprintf(TableName2, "%s", FEEDBACK_DETAIL_TABLE);
	}
	else
	{
		// 10 x tables
		sprintf(TableName2, "%s%02d", FEEDBACK_DETAIL_TABLE, SubTable2);
	}

	sprintf(pDesStatement, pSrcStatement, TableName1, TableName2);

	return pDesStatement;
}

// Server side RecomputeScore - need x10 tables!
//
// select SUM(effects) from
// (select (score / ABS(score)) effects from
// (select SUM(comment_score) score, commenting_id
// from ebay_feedback_detail where id = 
// (select id from ebay_users where userid= 'shaq') and
// (comment_type = 1 or comment_type = 2)
// group by commenting_id)
// where score != 0)
static const char *SQL_GetRecomputedFeedbackScore = 
 "select sum(effects) from							\
  (select sum(score / ABS(score)) effects from		\
   (select sum(comment_score) score, commenting_id	\
	from %s where id = :id							\
	and (comment_type = 1 or comment_type = 2)		\
	group by commenting_id)							\
	where score != 0)";

// recompute score server version
int clsDatabaseOracle::GetRecomputedFeedbackScore(int id,
												bool Split)
{
	int			SubTable = Split ? id % 10 : 10 ;
	char*		pTheStatement;
	int			fbscore;

	// Make fbscore 0, in case we get no rows
	fbscore	= 0;

	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetRecomputedFeedbackScore, SubTable);
	OpenAndParse(&(mpCDAGetRecomputedFeedbackScore[SubTable]), pTheStatement);
	delete [] pTheStatement;

	Bind(":id", &id);
	Define(1, &fbscore);
	Execute();
	Fetch();

	Close (&(mpCDAGetRecomputedFeedbackScore[SubTable]));
	SetStatement(NULL);

	return fbscore;
}


// gets feedback detail from feedback detail cache list if
// available and is valid; otherwise, get feedback detail
// and update the cache
//
// GetFeedbackListFromFeedbackList
//
//	Acquires a <list> of feedback rowid, commentor, type
//  for a given user from the feedback list database (cached).
//
//	This method returns TRUE if we got a legit feedback list this
//	way. It returns FALSE if it couldn't get a list, or got 
//	one, and it's been declared invalid.
//
//	This method uses a buffer for a long raw which grows as it's
//	needed. 
//

static const char *SQL_GetFeedbackListFromFeedbackList =
"select		fb_count,									\
			fb_list_size,								\
			fb_list_size_used,							\
			fb_list_valid,								\
			TO_CHAR(fb_last_modified,'YYYY-MM-DD HH24:MI:SS'), \
			fb_endian,								\
			fb_list									\
 from		ebay_feedback_lists						\
 where		id = :id";

bool clsDatabaseOracle::GetFeedbackListFromFeedbackList(int id,
					FeedbackRowItemVector *pvFeedbackRows)
{
	// Things we'll get ;-)
	int				fbCount;
	int				fbListSize;
	int				fbListSizeUsed;
	char			fbListValid[2];
	char			modDate[32];
	char			fbEndian[2];

	// Indicators, etc
	sb2				fb_list_ind;
	bool			isLittleEndian;

	// Misc
	int				i;
	unsigned char	*pCurrentFbListItem;
	char			cRowId[FEEDBACK_LIST_ROWID_SIZE + 1];

	// We're not sure how much buffer we'll need, so lets just
	// try for the minimum
	AllocateFeedbackListBuffer(INITIAL_FEEDBACK_LIST_ROWID_COUNT);


	// Let's go!
	OpenAndParse(&mpCDAGetFeedbackListFromFeedbackList,
				 SQL_GetFeedbackListFromFeedbackList);

	Bind(":id", &id);

	Define(1, &fbCount);
	Define(2, &fbListSize);
	Define(3, &fbListSizeUsed);
	Define(4, fbListValid, sizeof(fbListValid));
	Define(5, modDate, sizeof(modDate));
	Define(6, fbEndian, sizeof(fbEndian));
	DefineLongRaw(7, mpFeedbackListBuffer, 
					 mFeedbackListBufferSize,
					 &fb_list_ind);

	ExecuteAndFetch();

	// If we didn't get anything, return false
	if (CheckForNoRowsFound())
	{
		Close(&mpCDAGetFeedbackListFromFeedbackList);
		SetStatement(NULL);
		return false;
	}

	// We don't need no statement no more ;-)
	Close(&mpCDAGetFeedbackListFromFeedbackList);
	SetStatement(NULL);

	// If, somehow, the feedback list is null, then we'll assume the
	// list is invalid, and return false!
	if (fb_list_ind == -1)
		return false;

	// If itemListEmptied is set, then "something" has declared
	// this list null and void, and we return "false", which 
	// indicates that the list is to be rebuilt.
	if (fbListValid[0] == 'N')
		return false;

	// If, the count is 0, then we're done!
	if (fbCount == 0)
		return true;


#ifdef _MSC_VER
	// Paranoid code.
	// 1) make sure fbListSizeUsed is fbCount*(sizeof(int) + sizeof(int) + sizeof(rowid))
	// 2) check to see that fbListSizeUsed isn't something wacky (like greater than 50000 feedback)
	// 3) make sure buffer is valid
	if (fbListSizeUsed != (fbCount*FEEDBACK_LIST_ROWID_SIZE)) EDEBUG('*', "EDEBUG: fbCount is not in sync with fbListSizeUsed!!");
	if ((fbListSizeUsed < 0) || (fbListSizeUsed > (50000*FEEDBACK_LIST_ROWID_SIZE))) EDEBUG('*', "EDEBUG: fblistSizeUsed=%d!!", fbListSizeUsed);
	if (!AfxIsValidAddress(mpFeedbackListBuffer, mFeedbackListBufferSize, true)) EDEBUG('*', "EDEBUG: mpFeedbackListBuffer is BAD!!");
#endif

	// 
	// See if there was enough space in the buffer for all the 
	// rows. This is a bit indirect, since we know how much
	// was needed (fbListSizeUsed), but we'll live.
	//
	// If AllocateFeedbackListBuffer returns true, then it had
	// to reallocate the buffer, and we need to recurse.
	//
	if (AllocateFeedbackListBuffer(fbCount))
	{
		return GetFeedbackListFromFeedbackList(id,
											   pvFeedbackRows);
	}

	// check endianality, but really no need...
	if (fbEndian[0] == '1')
		isLittleEndian	= true;
	else
		isLittleEndian	= false;


	// Well! Looks like we got something. Let's get it! Now, we can't
	// guarantee that there's nice alignment in the buffer, so we step
	// through, copying each one TO a nice place, and build the object.
	for (i = 0,
		 pCurrentFbListItem = mpFeedbackListBuffer;
		 i < fbCount;
		 i++,
		 pCurrentFbListItem = pCurrentFbListItem + FEEDBACK_LIST_ROWID_SIZE)
	{
		memcpy(&cRowId, pCurrentFbListItem, FEEDBACK_LIST_ROWID_SIZE);
		cRowId[FEEDBACK_LIST_ROWID_SIZE]	= '\0';

		pvFeedbackRows->push_back(new clsFeedbackRowItem(cRowId));
	}

	// And, that's that! We're done
	return true;
}

//
// UpdateFeedbackList
//
//	Takes a vector of MinimalFeedbackDetailVector, 
//  and builds a nice, "fast"
//	access row for the feedback list table. 
//
//	To keep the table from fragmenting, tries to get the size of 
//	the existing list, and use that, if possible.
//
static const char *SQL_GetFeedbackListSize =
"select	fb_list_size_used							\
 from	ebay_feedback_lists							\
 where	id = :id";

static const char *SQL_UpdateFeedbackList = 
"update	ebay_feedback_lists							\
 set	fb_count			= :fbcount,				\
		fb_list_size		= :listsize,			\
		fb_list_size_used	= :listused,			\
		fb_list_valid		= \'Y\',				\
		fb_last_modified	= sysdate,				\
		fb_endian			= :endianality,			\
		fb_list			= :fblist					\
 where	id = :id";

static const char *SQL_AddFeedbackList =
"insert into ebay_feedback_lists					\
 (													\
	id,												\
	fb_count,										\
	fb_list_size,									\
	fb_list_size_used,								\
	fb_list_valid,									\
	fb_last_modified,								\
	fb_endian,										\
	fb_list											\
 )													\
 values												\
 (													\
	:id,											\
	:fbcount,										\
	:listsize,										\
	:listused,										\
	'Y',											\
	sysdate,										\
	:endianality,									\
	:fblist											\
 )";

void clsDatabaseOracle::UpdateFeedbackList(int Id,
					 FeedbackItemVector *pvFeedback)
{
	int			rc;

	// Do we need a new record?
	int			currentListSize;
	bool		needNewRow			= false;

	// Things about the new record, if any
	int			fbCount;
	int			listSize;
	int			listSizeUsed;
	char		endian[2];

	FeedbackItemVector::iterator i;
	unsigned char				*pCurrentListItem;

	//
	// Make sure we've got room to build the list
	//
	AllocateFeedbackListBuffer(pvFeedback->size());

	fbCount			= pvFeedback->size();
	listSize		= fbCount * FEEDBACK_LIST_ROWID_SIZE;
	listSizeUsed	= listSize;

	// Make pretty
	memset(mpFeedbackListBuffer, 0x00, mFeedbackListBufferSize);

	// First, see if we have a record to use ;-)
	OpenAndParse(&mpCDAGetFeedbackListSize,
				 SQL_GetFeedbackListSize);

	Bind(":id", &Id);
	Define(1, &currentListSize);

	ExecuteAndFetch();

	// If we didn't find a row, well, remember it.
	if (CheckForNoRowsFound())
	{
		needNewRow		= true;
		currentListSize	= 0;
	}

	// We're done with the cursor
	Close(&mpCDAGetFeedbackListSize);
	SetStatement(NULL);

	// Ok, at this point, we know that the current buffer is 
	// big enough for the items in the list, so we can go ahead
	// and build the list in the buffer.
	
	pCurrentListItem = mpFeedbackListBuffer;

	for (i = pvFeedback->begin();
		 i != pvFeedback->end();
		 i++)
	{
		// We can't guarantee the alignment of the buffer, so 
		// we'll use memcpy.
		memcpy(pCurrentListItem, (*i)->mRowId,
			   FEEDBACK_LIST_ROWID_SIZE);

		pCurrentListItem = pCurrentListItem + FEEDBACK_LIST_ROWID_SIZE;
	}

	if (ISLENDIAN)
		strcpy(endian, "1");
	else
		strcpy(endian, "0");

	// We're all ready to "do the right thing"!
	if (needNewRow)
	{
		OpenAndParse(&mpCDAAddFeedbackList,
					 SQL_AddFeedbackList);
	}
	else
	{
		OpenAndParse(&mpCDAUpdateFeedbackList,
					 SQL_UpdateFeedbackList);
	}

	Bind(":id", &Id);
	Bind(":fbcount", &fbCount);
	Bind(":listsize", &listSize);
	Bind(":listused", &listSizeUsed);
	Bind(":endianality", endian);
	BindLongRaw(":fblist", mpFeedbackListBuffer, listSize);
	
	// Now, we can get an integrity violation here if someone
	// else just added a record while we weren't looking. 
	rc	= oexec((struct cda_def *)mpCDACurrent);

	//
	// If we got an ORA-0001 (Unique constraint violated), we just
	// close our cursors and recurse. We don't HAVE to do this, but
	// there's a chance the "new" list is out of date. 
	//
	if (needNewRow &&
		((struct cda_def *)mpCDACurrent)->rc == 1)
	{
		Close(&mpCDAAddFeedbackList);
		SetStatement(NULL);
		UpdateFeedbackList(Id,
						 pvFeedback);
		return;
	}
	else
		Check(rc);

	Commit();

	// Well, THAT would be that ;p
	if (needNewRow)
		Close(&mpCDAAddFeedbackList);
	else
		Close(&mpCDAUpdateFeedbackList);
	SetStatement(NULL);

	return;
}

//
// InvalidateFeedbackList
//
//	Indicates that the user's feedback list cached row, if any, is now
//	invalid.
//
static const char *SQL_InvalidateFeedbackList =
"update	ebay_feedback_lists					\
 set	fb_count = 0,							\
		fb_list_size_used = 0,				\
		fb_list_valid = \'N\'					\
 where	id = :id";

void clsDatabaseOracle::InvalidateFeedbackList(int Id)
{
	// Simple
	OpenAndParse(&mpCDAInvalidateFeedbackList,
				 SQL_InvalidateFeedbackList);

	Bind(":id", &Id);

	Execute();

	Commit();

	Close(&mpCDAInvalidateFeedbackList);
	SetStatement(NULL);

	return;
}

// this is only used when a user is "deleted", or merged into another user
// so there is no need for a seller list record; otherwise, use invalidate.
static const char *SQL_DeleteFeedbackList =
"delete	from ebay_feedback_lists					\
 where	id = :id";

void clsDatabaseOracle::DeleteFeedbackList(int Id)
{
	// Simple
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteFeedbackList);

	Bind(":id", &Id);

	Execute();

	Commit();

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;
}

//
// GetFeedbackDetailFromList
//
//	This is the thing which gets feedback details for a
//  user from the feedback cache list.
//
#define ORA_FEEDBACK_LISTED_ARRAYSIZE 20
 
static const char *SQL_GetFeedbackDetailFromList =
 "select /*+ rowid(detail ) */ TO_CHAR(detail.time, 'YYYY-MM-DD HH24:MI:SS'), "
 "	detail.comment_type, "
 "	detail.commenting_id, "
 "	users.userid, "
 "	users.user_state, "
 "	feedback.score, "
 "	detail.commenting_host, "
 "	detail.comment_text, "
 "	detail.response, "
 "  detail.followup, "
 "	detail.item, "
 "	TO_CHAR(users.userid_last_change, 'YYYY-MM-DD HH24:MI:SS'), "
 "	users.email,	"
 "  detail.rowid "
 "	from	%s detail, "
 "			ebay_users users, "
 "			ebay_feedback feedback "
 "	where	(detail.rowid=:rowid00 or detail.rowid=:rowid01 or "
 "			 detail.rowid=:rowid02 or detail.rowid=:rowid03 or "
 "			 detail.rowid=:rowid04 or detail.rowid=:rowid05 or "
 "			 detail.rowid=:rowid06 or detail.rowid=:rowid07 or "
 "			 detail.rowid=:rowid08 or detail.rowid=:rowid09 or "
 "			 detail.rowid=:rowid10 or detail.rowid=:rowid11 or "
 "			 detail.rowid=:rowid12 or detail.rowid=:rowid13 or "
 "			 detail.rowid=:rowid14 or detail.rowid=:rowid15 or "
 "			 detail.rowid=:rowid16 or detail.rowid=:rowid17 or "
 "			 detail.rowid=:rowid18 or detail.rowid=:rowid19 or "
 "			 detail.rowid=:rowid20 or detail.rowid=:rowid21 or "
 "			 detail.rowid=:rowid22 or detail.rowid=:rowid23 or "
 "			 detail.rowid=:rowid24 "
 "			) "
 "	and		detail.commenting_id = users.id "
 "	and		detail.commenting_id = feedback.id (+) "
 "	order by time desc";


//
// does the work of getting feedback detail
// overloaded; if Offset = 0 and Length = 0, we get it all
//
void clsDatabaseOracle::GetFeedbackDetailFromList(int id,
										  FeedbackItemVector *pvFeedbackDetail,
										  bool Split, int Offset, int Length,
										  int *pTotalItems)
{
	// Temporary slots for things to live in
	char				time[ORA_FEEDBACKDETAIL_PAGESIZE][32];		//lint !e578 don't worry about time
	FeedbackTypeEnum	type[ORA_FEEDBACKDETAIL_PAGESIZE];
	int					cId[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				cUserId[ORA_FEEDBACKDETAIL_PAGESIZE][256];
	sb2					cUserIdInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				cEmail[ORA_FEEDBACKDETAIL_PAGESIZE][65];
	sb2					cEmailInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	int					cUserState[ORA_FEEDBACKDETAIL_PAGESIZE];
	int					cScore[ORA_FEEDBACKDETAIL_PAGESIZE];
	sb2					cScoreInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				cHost[ORA_FEEDBACKDETAIL_PAGESIZE][32];
	char				text[ORA_FEEDBACKDETAIL_PAGESIZE][128];	//lint !e578 and don't worry about text
	char				response[ORA_FEEDBACKDETAIL_PAGESIZE][128];	//lint !e578 and don't worry about text
	sb2					responseInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				followup[ORA_FEEDBACKDETAIL_PAGESIZE][128];	//lint !e578 and don't worry about text
	sb2					followUpInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	int					item[ORA_FEEDBACKDETAIL_PAGESIZE];
	sb2					itemInd[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				detailRowId[ORA_FEEDBACKDETAIL_PAGESIZE][FEEDBACK_LIST_ROWID_SIZE];

	// so we can show mask of commenting user
	time_t				cUser_id_last_modified_time[ORA_FEEDBACKDETAIL_PAGESIZE];
	char				cUser_id_last_modified[ORA_FEEDBACKDETAIL_PAGESIZE][32];
	sb2					cUser_last_change_ind[ORA_FEEDBACKDETAIL_PAGESIZE];

	int					rowsFetched;
	time_t				theTime;
	clsFeedbackItem		*pFeedbackItem;
	int					rc;
	int					i,n, count;

	bool				doneWithRequest;
	bool				doneWithStatement;
	int					RowIdInStatement;
	int					rowsInStatement;

	char				Rowid[8];
	char				RowidHolder[ORA_FEEDBACKDETAIL_PAGESIZE][FEEDBACK_LIST_ROWID_SIZE + 1];
	int					SubTable = Split ? id % 10 : 10 ;
	char*				pTheStatement;

	
	// Our list of items ;-)
	FeedbackRowItemVector			vFeedbackRows;
	FeedbackRowItemVector::iterator	ivFeedbackRows;
	bool							listGood;

	// Let's get the list of rowid(s) for the feedback detail.
	listGood	= 
		GetFeedbackListFromFeedbackList(id,&vFeedbackRows);

	// If the rowid list / cache wasn't good, we'll just go the
	// "slow" way, and update the cache. We can just return what
	// we get the "slow" way..
	if (!listGood)
	{
		GetFeedbackDetail(id, pvFeedbackDetail, Split);
		UpdateFeedbackList(id, pvFeedbackDetail);
		if (pTotalItems != NULL)
			*pTotalItems	= pvFeedbackDetail->size();
		return;
	}

	// Well, well. We've got a list of rowids. If there aren't any,
	// then we'll just ditch.
	if (vFeedbackRows.size() < 1)
		return;

	if (pTotalItems != NULL)
		*pTotalItems	= vFeedbackRows.size();

	// Let's get our statement ready
	// Fill in the table name before parse the statement
	pTheStatement = FillFeedbackDetailTableName(SQL_GetFeedbackDetailFromList, SubTable);
	OpenAndParse(&(mpCDAGetFeedbackDetailFromList[SubTable]), pTheStatement);
	delete [] pTheStatement;

	// Define the output. The fact that we're using array fetch
	// doesn't matter one little bit!

	Define(1, (char *)time, sizeof(time[0]));
	Define(2, (int *)type);
	Define(3, cId);
	Define(4, (char *)cUserId, sizeof(cUserId[0]), &cUserIdInd[0]);
	Define(5, cUserState);
	Define(6, cScore, &cScoreInd[0]);
	Define(7, (char *)cHost, sizeof(cHost[0]));
	Define(8, (char *)text, sizeof(text[0]));
	Define(9, (char *)response, sizeof(response[0]), responseInd);
	Define(10, (char *)followup, sizeof(followup[0]), followUpInd);
	Define(11, (int *)item, itemInd);
	Define(12, cUser_id_last_modified[0], sizeof(cUser_id_last_modified[0]), &cUser_last_change_ind[0]);
	Define(13, (char *)cEmail, sizeof(cEmail[0]), &cEmailInd[0]);
	Define(14, (char *)detailRowId, sizeof(detailRowId[0]));

	// fill the actual rowids in the rowid holder, then fetch

	// Ok, now, this is weird. In order to get the benefits of array
	// fetch, we needed a way to ask for _multiple_ items in one 
	// query. We either kludged this or did it very elegantly by 
	// having an "or" clause with 25 possible feedback details in it. We now
	// need to traverse our list of feedback details, and fill these in, one
	// by one. 

	// check for offset; if 0 => start from beginning
	// check for length; if 0 => all
	if (Length == 0)
	{
		Length = vFeedbackRows.size();
	}

	// iterate over rowids in RowidHolder
	RowIdInStatement	= 0;
	doneWithRequest		= false;

	ivFeedbackRows	= vFeedbackRows.begin() + Offset;

	// count is the number of feedback details we got
	count				= 0;

	do
	{
		//
		// If we've gotten at least "length" rows, we're done
		//
		if (count >= Length)
			break;

		// Start with the 0'th row in the statement
		RowIdInStatement	= 0;
		rowsInStatement		= 0;
		doneWithStatement	= false;

		// Let's fill the rowids into the bind variables.
		do
		{
			// Are we done with the statement?
			if (RowIdInStatement >= ORA_FEEDBACKDETAIL_PAGESIZE)
				break;

			// If we're at the end of the array of rowids, or we've got
			// enough rows for the request, then fill out the variables
			// with the LAST rowid. This is a cute trick, since the "or"
			// in the SQL will cause us to fetch that last row ONCE, even
			// though it occurs multiple times.
			//
			// Since we're out of rows to fetch, we're done, so we'll 
			// "force" ourselves to be done by setting count = length.
			//
			if (ivFeedbackRows >= vFeedbackRows.end() || count >= Length)
			{
				// Fill in rowid holder with 0
				for ( ; RowIdInStatement < ORA_FEEDBACKDETAIL_PAGESIZE; 
						RowIdInStatement++)
				{
					memcpy(RowidHolder[RowIdInStatement],
						   RowidHolder[rowsInStatement - 1],
						   sizeof(RowidHolder[RowIdInStatement]));
				}

				count = Length + 1;

				break;
			} 
			else
			{
				// Fill in the bind variable from the vector
				strcpy(RowidHolder[RowIdInStatement], (*ivFeedbackRows)->mRowId);

				// Advance #of rowids filled into statement, #of rows retrieved
				// (or planned!), rows in statement, and advance vector.
				RowIdInStatement++;
				rowsInStatement++;
				count++;
				ivFeedbackRows++;
			}

		} while (1 == 1);

		// Bind the input rowid variables
		for (i = 0; i < ORA_FEEDBACKDETAIL_PAGESIZE; i++)
		{
			sprintf(Rowid, "rowid%02d", i);
			Bind(Rowid, RowidHolder[i]);
		}


		// Statement alllll built..
		Execute();

		if (CheckForNoRowsFound())
		{
			ocan((struct cda_def *)mpCDACurrent);
			Close(&(mpCDAGetFeedbackDetailPages[SubTable]));
			SetStatement(NULL);
			return;
		}

		// Now we fetch until we're done
		rowsFetched = 0;
		do
		{
			for (i = 0; i < ORA_FEEDBACKDETAIL_PAGESIZE; i++)
				itemInd[i] = -1;

			rc = ofen((struct cda_def *)mpCDACurrent,ORA_FEEDBACKDETAIL_PAGESIZE);

			assert(mpCDACurrent);
			if ((rc < 0 || rc >= 4)  && 
				((struct cda_def *)mpCDACurrent)->rc != 1403)	// something wrong
			{
				Check(rc);
				Close(&(mpCDAGetFeedbackDetail[SubTable]));
				SetStatement(NULL);
				return;
			}

			// rpc is cumulative, so find out how many rows to display this time 
			// (always <= ORA_FEEDBACKDETAIL_PAGESIZE). 
			n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
			rowsFetched += n;

			for (i=0; i < n; i++)
			{

				// Convert time
				ORACLE_DATEToTime(time[i], &theTime);
				if (cUser_last_change_ind[i] != -1)
					ORACLE_DATEToTime(cUser_id_last_modified[i], &cUser_id_last_modified_time[i]);
				else
					cUser_id_last_modified_time[i] = 0;

				// Check for NULL score
				if (cScoreInd[i] == -1)
					cScore[i] = 0;

				// Check for NULL item
				if (itemInd[i] == -1)
					item[i]	= 0;

				pFeedbackItem	= 
					new clsFeedbackItem(id,
										theTime,
										type[i],
										cId[i],
										cUserId[i],
										cEmail[i],
										cUserState[i],
										cScore[i],
										cHost[i],
										text[i],
										cUser_id_last_modified_time[i],
										detailRowId[i],
										0,
										0,
										item[i],
										response[i],
										followup[i]);
				pvFeedbackDetail->push_back(pFeedbackItem);

			}
		} while (!CheckForNoRowsFound());

		// reset rowid in statement
		RowIdInStatement = 0;
	} while (count <= Length);

	// Close 
	Close(&(mpCDAGetFeedbackDetailFromList[SubTable]));
	SetStatement(NULL);

	// Free the objects allocated within vFeedbackRows
	for (ivFeedbackRows	= vFeedbackRows.begin(); ivFeedbackRows != vFeedbackRows.end(); ++ivFeedbackRows)
	{
		delete *ivFeedbackRows;
	}
	

	return;		//lint !e429 Don't worry about pFeedbackItem
}


// Same as above; minimal version, but may not be needed, so it is
// only a stub right now.
void clsDatabaseOracle::GetFeedbackDetailFromListMinimal(int id,
						  MinimalFeedbackItemVector *pvMinimalFeedbackDetail,
										  bool Split, int Offset, int Length)
{

}