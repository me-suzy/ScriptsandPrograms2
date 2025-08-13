/*	$Id: clsDatabaseGDBM.h,v 1.2 1998/06/23 04:27:58 josh Exp $	*/
//
//	File:		clsDatabaseGDBM.h
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
#ifndef CLSDATABASEGDBM_INCLUDED

#include "clsDatabase.h"

extern "C"
{
#ifndef _MSC_VER
#include "ndbm-gdbm.h"
#endif /* _MSC_VER */
}


class clsDatabaseGDBM : public clsDatabase
{
	public:

		// Constructor, Destructor
		clsDatabaseGDBM(char *pHost);
		~clsDatabaseGDBM();

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
		//
		// Get an item
		//
		bool GetItem(int id,
						 char **ppTitle,
						 char **ppLocation,
						 char **ppOwner,
						 int *pPass,
						 int *pCategory,
						 int *pNumBids,
						 int *pQuantity,
						 long *pStartTime,
						 long *pEndTime,
						 long *pStatus,
						 double *pPrice,
						 double *pStartPrice,
						 double *pReservePrice,
						 char **ppHiBidder);

      //
      // Get an Item, old style
      //
      char *GetItemNewlineDelimited(char *pItemNo)
		{ return (char *)0; };


		//
		// Add an item
		//
		void AddItem(char *pItemNo,
						 char *pTitle,
						 char *pLocation,
						 char *pOwner,
						 int pass,
						 int category,
						 int numBids,
						 int quantity,
						 long startTime,
						 long endTime,
						 long status,
						 double price,
						 double startPrice,
						 double reservePrice,
						 char *pHiBidder);

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



	private:
		// The following boolean tells us if
		// the caller has issued a "Begin()", 
		// which means we can keep the item cache
		// open across operations. 
		bool			mInTransaction;
		
		// The currently open item cache. 0 if
		// the isn't one
		int			mItemCache;

		//	Feedback DB
		bool			mFeedbackDbOpen;
		DB				*mpFeedbackDb;
		

		
};

#define CLSDATABASEGDBM_INCLUDED 1
#endif /* CLSDATABASEGDBM_INCLUDED */
