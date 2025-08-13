/*	$Id: clsDatabaseODBC.h,v 1.2 1998/06/23 04:28:00 josh Exp $	*/
//
//	File:	clsDatabaseGDBM.h
//
// Class:	clsDatabaseGDBM.h
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 02/09/97 michael	- Created
//
#ifndef CLSDATABASEODBC_INCLUDED

#include "clsDatabase.h"

#include "WINDOWS.H"
#include "SQL.H"
#include "SQLEXT.H"


class clsDatabaseODBC : public clsDatabase
{
	public:

		// Constructor, Destructor
		clsDatabaseODBC(char *pHost);
		~clsDatabaseODBC();

		//
		// Begin, End
		//
		void Begin();
		void End();

		//
		// CancelQuery
		//
		void CancelQuery();


		//
		// ClearAllItems
		//
		void ClearAllItems();


		//
		// Get an item 
		//
		// **** NOTE ****
		// This uses the old-style character item
		// id.
		//
		bool GetItem(char *pItemId,
					 clsItem *pItem);
		//
		//
		// Get an item
		//
		bool GetItem(int id,
					 clsItem *pItem);


		//
		// Get the next availible item id
		//
		int GetNextItemId();

		//
		// Add an item
		//
		void AddItem(clsItem *pItem);


		//
		// Get bids on an item
		//
		bool GetBids(
						char *pItemNo,
						DbBidCallBack *pBidCallBack,
						unsigned char *pArbitrary
						);

		//
		// Get a user's feedback score
		//
		bool GetFeedbackScore(
						char *pUserid,
						clsFeedback *pFeedback
									);

		//
		// Get a user's feedback
		//
		bool GetFeedbackItems(
						char *pUserid,
						clsFeedback *pFeedback
									);


	private:
		// 
		// Common Check Routine
		//
		void Check(RETCODE rc);

		//
		// Common state routine
		//
		void SetStatement(HSTMT currentStmt);

		//
		// Helpers
		// 
		void BindSDWORDToINT(int position,
							 SDWORD *pVar);
		void BindTIMESTAMPToTIMESTAMP(int position,
								 TIMESTAMP_STRUCT *pVar);
		void BindUCHARToVARCHAR(int position,
							    UCHAR *pVar,
								SDWORD *pPcbValue);
		void BindSFLOATToFLOAT(int position,
							   SFLOAT *pVar);
		void BindUCHARToLONGVARBINARY(int position,
									  UCHAR *pVar,
									  SDWORD *pPcbValue);

		void BindColToCHAR(int position,
						   UCHAR *pTarget,
						   SDWORD targetSize,
						   SDWORD *pReturnedLength);
		void BindColToSDWORD(int position,
							 SDWORD *pTarget,
							 SDWORD *pReturnedLength);
		void BindColToSFLOAT(int position,
							 SFLOAT *pTarget,
							 SDWORD *pReturnedLength);
		void BindColToTIMESTAMP(int position,
								 TIMESTAMP_STRUCT *pTarget,
							 SDWORD *pReturnedLength);

		//
		// Adds a cross reference between an old
		// character item id and a new numeric one
		//
		void AddItemXREF(char *pId,
						 SDWORD id);

		//
		// GetItemXREF
		//
		// Translates old style character item 
		// numbers into real ones ;-)
		//
		int GetItemXREF(char *pItemId);



		//
		// State
		//
		bool		mHaveEnv;
		bool		mHaveDbc;
		bool		mConnected;
		HSTMT		mCurrentHStmt;

		//
		// Statements/Cursors
		//
		HSTMT		mStmtGetItemXREF;
		HSTMT		mStmtGetSingleItem;

		//
		// ODBC Environment handle
		//
		HENV		mHEnv;

		//
		// ODBC Connection handle
		//
		HDBC		mHDbc;

		//
		// Error Stuff
		//
		UCHAR		mErrorState;
		SDWORD		mErrorNativeError;
		UCHAR		mErrorMsg[512];
		SWORD		mErrorMsgSize;
		
};

#define CLSDATABASEODBC_INCLUDED 1
#endif /* CLSDATABASEODBC_INCLUDED */
