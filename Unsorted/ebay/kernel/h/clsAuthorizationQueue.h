/*	$Id: clsAuthorizationQueue.h,v 1.4 1998/09/30 02:58:32 josh Exp $	*/
//
//	File:	clsAuthorizationQueue.h
//
//	Class:	clsAuthorizationQueue
//
//	Author:	Sam Paruchuri
//
//	Function:
//			Class that queue's requests for Credit Card Authorization.
//
// Modifications:
//				- 05/11/98	Sam - Created
//
//
#ifndef clsAuthorizationQueue_INCLUDED
#define clsAuthorizationQueue_INCLUDED

#include <stdio.h>
#include <time.h>
#include <vector.h>
#include <iterator.h>

// consts
#define EBAY_MAX_CC_AUTH_ATTEMPTS		10

// Forward Decl
class clsAuthorizationQueue;

// Data types
typedef vector<clsAuthorizationQueue *> AuthorizationVector;

enum BillingAccountType	{
						CC_ON_FILE, ANON_USER_CC_ON_FILE,
						ANON_USER_NO_CC_ON_FILE, ONE_TIME_PAYMENT,
						UNKNOWN
						};
typedef enum BillingAccountType eAccountType;


enum TransactionState	{
						New, VIP, Retry, 
						Valid, Reject, Processed
						};
typedef enum TransactionState eTransState;


enum TransactionType	{
						Authorization, Reversal,
						Settlement, Registration
						};
typedef enum TransactionType eTransType;


struct TransactionResult { 
		  				eTransState	status;
						char		trans_id[17];
						char		timestamp[11];
						char		val_code[5];
						char		author_code[7];
						char		resp_code[3];
						char		avs_resp_code[2];
						};
typedef struct TransactionResult aTransResp;



class clsAuthorizationQueue
{
	public:

	// CTOR
	clsAuthorizationQueue();

	// Fancy CTOR
	clsAuthorizationQueue(
						  int			id,
						  int			refID,
						  char			*pCCNumber,
						  time_t		CCExpiryDate, 
						  float			amount,
						  short			priority,
						  int			status,
						  time_t		timestamp,
						  int			invoice_batch_id,
						  int			transaction_type
						  ) :
							mid(id),
							mrefID(refID),
							mpCCNumber(pCCNumber),
							mCCExpiryDate(CCExpiryDate),
							mamount(amount),
							mpriority(priority),
							mstatus((eTransState)status),
							mtimestamp(timestamp),
							minvoice_batch_id(invoice_batch_id),
							mtransaction_type((eTransType)transaction_type)
	{
			return;
	}

	// DTOR
	~clsAuthorizationQueue();

	// Add request entry to cc_authorize where authorization information is the Credit 
	// Card Number, Credit Card expiry date in MMYY format, Amount to charge if 
	// any or 0, priority, example '1' for  real time verification.
	// clsAuthorizationQueue * is returned, this will contain all data including the
	// reference id that is generated using Oracle DB Sequence.
	clsAuthorizationQueue *Enqueue (char *pCCNumber, time_t CCExpiryDate, 
									short priority=1, int id=0, float Amount=1.0, 
									eTransType transaction_type=Authorization,
									char *accholdername=NULL, char *street_addr=NULL, 
									char *city_addr=NULL, char *stateprov_addr=NULL,
									char *zipcode_addr=NULL, char *country_addr=NULL,
									char *billingaccounttype="0");

	// Returns entry from cc_authorize to send for FDMS processing. 
	// Important: This method should be used to set the criterion for the
	// list to be generated for FDMS processing. This will set an internal vector
	// with items from the DB that have the specified "Transaction Type", "Priority"
	// and "status" based on the results of DB Query.
	// Caller must specify whether the state of the item that was retrieved
	// is to be changed. The new state must be provided in newStatus.
	// An example of state change is from New to VIP (Validation in Progress)
	// Returns NULL if no item is available.
	clsAuthorizationQueue *GetQueueEntryData(eTransType trans_type, 
											 int priority,
											 eTransState status=New, 
											 bool bChangeState=true,
											 eTransState newStatus=VIP);								

	// Returns next entry from cc_authorize to send for FDMS processing. 
	// Important: The criterion for the entry "type" to be retrieved should already
	// be set using "GetQueueEntryData". This simply returns the next available item
	// in the internal item vector and *DOES NOT* result in a DB query.
	// Caller must PRECEDE a call to "GetQueueEntryData" before invoking this
	// method to obtain meaningful data.
	// Ideally, used for batch transactions.
	// Returns NULL after last item has been processed.
	clsAuthorizationQueue *GetNextQueueEntryData(bool			bChangeState,
												 eTransState	newStatus);

	// Set the result from CC transaction from FDMS or other organizations. For realtime
	// transactions this will be called within method Enqueue. For other operations 
	// this can be called from the CC transcation specific service.
	void UpdateTransactionStatus(	char	   *referenceId, 
									aTransResp aResult
								 );

	// Remove object from cc_authorize, this can be issued by the originator or
	// the service that handles the request, generally after the request is processed.
	// Warning: This will remove queue entry in any state.
	void Remove (int referenceID);


	// Once CC has been authorized from either the CC Update or CC Reg confirmation page
	// copy the data to cc_billing for persistent storage from cc_authorize.
	void StoreCCUpdate(	int id, int refid);


	// Find a specific item in cc_authorize, doesExist() test
	clsAuthorizationQueue *Find (int referenceID);

	// Number of items in cc_authorize
	int Length ();		

	// Check CC Status
	eTransState GetAuthorizationStatus ();

	// Set CC Authorization Attempts in cc_billing
	void SetAuthAttemptsCount(int id, int count, bool isResetRequired);
	// Get CC Authorization Attempts from cc_billing
	int GetAuthAttemptsCount(int id);
	// Get Settlement File id from cc_settlement
	int	GetSettlementFileId();


	// ISO 8583 Data Foramtting routines
	// Returns a 12 byte string conforming to ISO requirements
	// Caller must free memory that is allocated.
	char *ISOTransactionAmountFmt(float amount);
	// Returns a 4 byte string conforming to ISO requirements
	char *ISOCreditCardExpirationFmt(time_t expDate);
	// Returns a 30 byte string conforming to ISO requirements
	char *ISOBillingAddressFmt(	char *st_addr, char *zip_addr);
	// Returns a 6 byte string conforming to ISO requirements
	char *ISOSystemTraceFmt(int refid);


	// Set's 
	void SetQueueLength(int length) 
		{ mlength = length; }
	void SetReferenceID(int ref_id) 
		{ mrefID = ref_id; }
	void SetTransactionState(eTransState TransState)
		{ mstatus = TransState; } 
	// Get's
	int GetReferenceId()
		{ return mrefID; }
	int GetId()
		{ return mid; }
	char *GetCC()
		{ return mpCCNumber; }
	time_t GetCCExpiryDate()
		{ return mCCExpiryDate; }
	float GetChargeAmount()
		{ return mamount; }
	eTransState GetCCTransState()
		{ return mstatus; }
	eTransType GetCCTransType()
		{ return mtransaction_type; }

	private:
		int					 mid;
		int					 mrefID;
		int					 mlength;	// number of items in the table
		char				*mpCCNumber;
		time_t				 mCCExpiryDate;
		float				 mamount;
		eTransState			 mstatus;
		short				 mpriority;
		time_t				 mtimestamp;
		char				*mtransID;
		char				*mvalcode;
		int					 minvoice_batch_id;
		eTransType			 mtransaction_type;
		char				*mst_billing_addr;
		char				*mzip_billing_addr;
		time_t				 mtrans_timestamp;
		AuthorizationVector  mvAuthorizationItems;
		AuthorizationVector ::iterator mAuthIter;
};


#endif // clsAuthorizationQueue_INCLUDED
