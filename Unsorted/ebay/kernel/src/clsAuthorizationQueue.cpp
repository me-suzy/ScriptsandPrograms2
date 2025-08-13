/*	$Id: clsAuthorizationQueue.cpp,v 1.5 1998/12/06 05:31:40 josh Exp $	*/
//
//	File:	clsAuthorizationQueue.cpp
//
//	Class:	clsAuthorizationQueue
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:
//
//				Worker class to allow Credit Card authorization related DB queries,
//				and interfacing with clsAuthorizeQueue.
//
// Modifications:
//				- 05/10/98 sam	- Created
//

#include "eBayKernel.h"
#include "clsAuthorize.h"



clsAuthorizationQueue::clsAuthorizationQueue()
{
	mrefID = 0;
}


clsAuthorizationQueue::~clsAuthorizationQueue()
{

}


// For real time authorizations, priority is assumed to be 0, for batch requests priority
// should be set to >=1.
clsAuthorizationQueue *clsAuthorizationQueue::Enqueue ( char *pCCNumber, time_t CCExpiryDate, 
														short priority, int id, float Amount, 
														eTransType transaction_type,
														char *accholdername, char *street_addr, 
														char *city_addr, char *stateprov_addr,
														char *zipcode_addr, char *country_addr,
														char *billingaccounttype)

{
	
	char		 *pszTransAmountInput;
	char		 *pszSystemTraceInput;
	char		 *pszCardExpDateInput;
	char		 *pszBillingAddrInput;
	int			  rc=0;
	clsUser		 *pUser=NULL;
	clsUser		  myUser;
	clsAuthorize *pAuthorize=NULL;

	// clsDatabaseOracleAuthorize
	clsAuthorizationQueue *pAuthorizationQueue=NULL;	

	// Possible that none of the user profile is set
	if (strlen(street_addr) == 0)
	{
		myUser.SetId(id);
		pUser = gApp->GetDatabase()->GetUserInfo(&myUser);
	}
	if (pUser)
	{
		if (strlen(accholdername) == 0)
			accholdername	= pUser->GetName();
		if (strlen(street_addr) == 0)
			street_addr		= pUser->GetAddress();
		if (strlen(city_addr) == 0)
			city_addr		= pUser->GetCity();
		if (strlen(stateprov_addr) == 0)
			stateprov_addr	= pUser->GetState();
		if (strlen(zipcode_addr) == 0)
			zipcode_addr = pUser->GetZip();
		if (strlen(country_addr) == 0)
			country_addr = pUser->GetCountry();

	}

	pAuthorizationQueue = gApp->GetDatabase()->AddAuthorizationEntry(id, pCCNumber, 
														CCExpiryDate, priority, 				
														Amount, transaction_type,
														accholdername, street_addr, 
														city_addr, stateprov_addr,
														zipcode_addr, country_addr,
														billingaccounttype);
	if (!pAuthorizationQueue)
		return NULL;
	else
		// New entry
		pAuthorizationQueue->SetTransactionState(New);

	// Now that the entry has been queued, return for batch modes
	// Return for Anonymous Registration users, this may change in future
	// to allow anonymous users to put CC_ON_FILE
	if (priority != 0									|| 
		transaction_type == ANON_USER_NO_CC_ON_FILE		||
		transaction_type == ANON_USER_CC_ON_FILE)
			return pAuthorizationQueue;


	// Must set FDMS env variables before calling this
	// Port number of the machine communicating with FDMS.
	// eBayFDMS = 7001
	// IP Address of the machine communicating with FDMS.
	// eBayFDMSAddress = 209.1.128.164 
	pAuthorize = new clsAuthorize;
	if (!pAuthorize) 
	{
		// Failed for some reason.
		delete pAuthorizationQueue;
		return NULL;
	}


	// ISO 8583 Data Conversion routines
	pszTransAmountInput = ISOTransactionAmountFmt(Amount);
	pszCardExpDateInput = ISOCreditCardExpirationFmt(CCExpiryDate);
	pszBillingAddrInput = ISOBillingAddressFmt(street_addr, zipcode_addr);
	pszSystemTraceInput = ISOSystemTraceFmt(pAuthorizationQueue->GetReferenceId());

	// Send over for FDMS authorization, database update upon 
	// response should happen from there.
	// Check for priority, if real time (priority 0) then issue request to
	// clsAuthorize
	if (pAuthorize && priority == 0)
		rc = pAuthorize->DispatchAuthorizationRequest (	pCCNumber,
														pszTransAmountInput,
														pszSystemTraceInput,
														pszCardExpDateInput,
														pszBillingAddrInput,
														false );
	if (rc == -1)
	{
		// Error from FDMS
		delete pAuthorizationQueue;
		pAuthorizationQueue =NULL;
	}
	else // rc == 0
		pAuthorizationQueue->SetTransactionState(Valid);
	
	// Cleanup and Return
	if (pszTransAmountInput)
		free(pszTransAmountInput);
	if (pszCardExpDateInput)
		free(pszCardExpDateInput);
	if (pszBillingAddrInput)
		free(pszBillingAddrInput);
	if (pszSystemTraceInput)
		free(pszSystemTraceInput);
	delete pAuthorize;
	delete pUser;

	return pAuthorizationQueue;
}


clsAuthorizationQueue *clsAuthorizationQueue::GetQueueEntryData(eTransType	trans_type, 
																int			priority,
																eTransState status,
																bool		bChangeState,
																eTransState	newStatus)

{
	// Once CC requests are queued up in cc_authorize, use this method to get
	// the first entry in table that has the specified trans_type, priority and 
	// status. If ChangeState flag is set then change the state to newStatus
	clsAuthorizationQueue *pAuthorizationQueue;
	aTransResp			   resp;
	char				  RefId[10];	

	if (mvAuthorizationItems.empty())
	{
		// Go Fetch, authorization items in the vector
		gApp->GetDatabase()->GetAuthorizationItems( trans_type, priority, status,
													bChangeState, newStatus,
													&mvAuthorizationItems);
		mAuthIter = mvAuthorizationItems.begin();
	}

	if (mAuthIter == mvAuthorizationItems.end())
	{
		// Cleanup
		mvAuthorizationItems.erase(mvAuthorizationItems.begin(),
									mvAuthorizationItems.end());
		return (clsAuthorizationQueue *)NULL;
	}

	pAuthorizationQueue = *mAuthIter;
	// First change state and then return
	if (bChangeState)
	{
		resp.status			=	newStatus;
		memset(resp.timestamp, 0x00, sizeof(resp.timestamp));
		memset(resp.trans_id, 0x00, sizeof(resp.trans_id));
		memset(resp.val_code, 0x00, sizeof(resp.val_code));
		memset(resp.author_code, 0x00, sizeof(resp.author_code));
		memset(resp.resp_code, 0x00, sizeof(resp.resp_code));
		memset(resp.avs_resp_code, 0x00, sizeof(resp.avs_resp_code));
		sprintf(RefId, "%s", pAuthorizationQueue->GetReferenceId());

		UpdateTransactionStatus (RefId, resp);
	}

	mAuthIter++;

	return pAuthorizationQueue;
	
}


clsAuthorizationQueue *clsAuthorizationQueue::GetNextQueueEntryData(bool		bChangeState,
																	eTransState	newStatus)

{
	// Once CC requests are queued up in cc_authorize, use this method to get
	// the next entry in table that has the specified trans_type, priority and 
	// status. If ChangeState flag is set then change the state to
	clsAuthorizationQueue	*pAuthorizationQueue;
	aTransResp				 resp;
	char					 RefId[10];	

	if (mAuthIter == mvAuthorizationItems.end())
	{
		// Cleanup
		mvAuthorizationItems.erase(mvAuthorizationItems.begin(),
									mvAuthorizationItems.end());
		return (clsAuthorizationQueue *)NULL;
	}

	pAuthorizationQueue = *mAuthIter;
	// First change state and then return
	if (bChangeState)
	{
		resp.status			=	newStatus;
		memset(resp.timestamp, 0x00, sizeof(resp.timestamp));
		memset(resp.trans_id, 0x00, sizeof(resp.trans_id));
		memset(resp.val_code, 0x00, sizeof(resp.val_code));
		memset(resp.author_code, 0x00, sizeof(resp.author_code));
		memset(resp.resp_code, 0x00, sizeof(resp.resp_code));
		memset(resp.avs_resp_code, 0x00, sizeof(resp.avs_resp_code));
		sprintf(RefId, "%s", pAuthorizationQueue->GetReferenceId());

		UpdateTransactionStatus (RefId, resp);
	}

	mAuthIter++;

	return pAuthorizationQueue;
	
}



void clsAuthorizationQueue::UpdateTransactionStatus(char *referenceId, aTransResp aResult)
{
	int refID;

	refID = atoi(referenceId);
	// clsDatabaseOracleAuthorize	
	SetTransactionState(aResult.status);
	gApp->GetDatabase()->SetAuthorizationStatusForRefID(refID, aResult);

	return;
}


void clsAuthorizationQueue::Remove (int referenceID)
{
	// clsDatabaseOracleAuthorize	
	// Remove cc_authorize table entry for any instance
	// Useful for cleanup 
   	gApp->GetDatabase()->RemoveAuthorizationEntry(referenceID);

}




void clsAuthorizationQueue::StoreCCUpdate(int id, int refid)
{

	gApp->GetDatabase()->CommitCCBillingData(id, refid);
}



clsAuthorizationQueue *clsAuthorizationQueue::Find (int referenceID)
{
	clsAuthorizationQueue *pAuthorizationQueue=NULL;

	// clsDatabaseOracleAuthorize	
   	pAuthorizationQueue = gApp->GetDatabase()->GetAuthorizationItemWithID(referenceID);

	return pAuthorizationQueue;

}


int clsAuthorizationQueue::Length ()
{
	int length=0;

	// clsDatabaseOracleAuthorize	
	length = gApp->GetDatabase()->GetAuthorizationTableSize();

	return length;
}


eTransState clsAuthorizationQueue::GetAuthorizationStatus()
{
	eTransState status;
	// clsDatabaseOracleAuthorize	
	status = (eTransState)gApp->GetDatabase()->GetAuthorizationStatusForID(mrefID);

	return status;
}


// Set CC Authorization Attempts in cc_billing
void clsAuthorizationQueue::SetAuthAttemptsCount(int id, int count, bool isResetRequired)
{
	gApp->GetDatabase()->SetAuthorizationAttemptCount(id, count, isResetRequired);

}

// Get CC Authorization Attempts from cc_billing
int clsAuthorizationQueue::GetAuthAttemptsCount(int id)
{
	return (gApp->GetDatabase()->GetAuthorizationAttemptCount(id));

}

int	clsAuthorizationQueue::GetSettlementFileId()
{
	return (gApp->GetDatabase()->GetNextSettlementFileId());
}


// ISO 8583 Foramtting routines
// Returns a 12 byte string conforming to ISO requirements
char *clsAuthorizationQueue::ISOTransactionAmountFmt(float amount)
{
	char *amt;
	char amtFmt[14];
	int i,j;

	amt = (char *)malloc(13);
	memset(amtFmt, 0x00, sizeof(amtFmt));

	// Force maximum 2 digits on right side of the decimal
	sprintf(amtFmt, "%013.2f", amount); // this will append null at strlen(amtFmt)
	
	for (i=0,j=0; i<strlen(amtFmt); i++)
	{
		if (amtFmt[i] != '.')
		{
			amt[j] = amtFmt[i];
			j++;
		}
	}

	amt[12] = 0x00;
	return amt;

}

// Returns a 4 byte string conforming to ISO requirements
char *clsAuthorizationQueue::ISOCreditCardExpirationFmt(time_t expDate)
{
	char		*cExpiryDate;
	struct tm	*pDate;
	int			 Year;

	cExpiryDate = (char *)malloc(5);

	pDate = localtime(&expDate);
	Year = pDate->tm_year;	
	if (Year >=100)
		Year = Year - 100; // Year is 2000 or higher, normalize to 00, 01..

	sprintf(cExpiryDate, "%02d%02d", Year, pDate->tm_mon+1);
	cExpiryDate[4] = 0x00;

	return cExpiryDate;

}

// Returns a 30 byte string conforming to ISO requirements
char *clsAuthorizationQueue::ISOBillingAddressFmt(char *st_addr, char *zip_addr)
{
	char	*addr;
	char	tmpZip[10];
	char	tmpStAddr[22];
	int		i, j;

	addr = (char *)malloc(31);
	memset(tmpZip, '0', sizeof(tmpZip)); // 0 padded
	tmpZip[9] = 0x00; // NULL Terminate
	memset(tmpStAddr, ' ', sizeof(tmpStAddr)); // blank padded
	tmpStAddr[21] = 0x00;

	// Zip code is most important, if not specified return NULL
	if (!zip_addr)
		return NULL; 
	// Form Zip Code String
	for (i=0,j=0; j<strlen(zip_addr)&&i<10; i++)
	{
		if (zip_addr[i] != '-')
		{
			tmpZip[j] = zip_addr[i];
			j++;
		}
	}
	// Form street address string
	for (i=0,j=0; j<strlen(st_addr)&&i<22; i++, j++)
		tmpStAddr[j] = st_addr[i];

	// Now for the remaining address
	sprintf(addr, "%s%s", tmpZip, tmpStAddr);
	addr[30] = 0x00;

	return addr;

}

// Returns a 6 byte string conforming to ISO requirements
char *clsAuthorizationQueue::ISOSystemTraceFmt(int refid)
{
	char *trace;

	trace = (char *)malloc(7);
	sprintf(trace, "%06d", refid);
	trace[6] = 0x00;

	return trace;
}
