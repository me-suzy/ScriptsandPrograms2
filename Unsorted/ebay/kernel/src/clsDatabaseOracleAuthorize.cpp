/*	$Id: clsDatabaseOracleAuthorize.cpp,v 1.4 1998/12/06 05:31:51 josh Exp $	*/
//
//	File:	clsDatabaseOracleAuthorize.cc
//
//	Class:	clsDatabaseOracleAuthorize
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:
//  All Credit Card Authorization related DB activities.
//
// Modifications:
//				- 05/12/98 sam	- Created.


#include "eBayKernel.h"


//
// Add an authorization request entry to cc_authorize
//
static const char *SQL_XGetNextReferenceId = 
 "select cc_refid_sequence.nextval		\
	from dual";

static const char *SQL_UpdateCCAuthorizationInfo =
 "insert into cc_authorize					\
	(	refid,								\
		id,									\
		cc,									\
		cc_expiry_date,						\
		state,								\
		priority,							\
		timestamp,							\
		amount,								\
		trans_type,							\
		accholder_name,						\
		st_bill_addr,						\
		city_bill_addr,						\
		state_bill_addr,					\
		country_bill_addr,					\
		zip_bill_addr,						\
		account_type						\
	)										\
	values									\
	(	:refid,								\
		:id,								\
		:cc,								\
		TO_DATE(:cc_expiry_date,			\
				'YYYY-MM-DD HH24:MI:SS'),	\
		:state,								\
		:priority,							\
		sysdate,							\
		:amount,							\
		:trans_type,						\
		:accholder_name,					\
		:street_addr,						\
		:city_addr,							\
		:stateprov_addr,					\
		:country_addr,						\
		:zipcode_addr,						\
		:account_type						\
	)";

clsAuthorizationQueue *clsDatabaseOracle::AddAuthorizationEntry(int      id,
																char	*pCCNumber, 
																time_t   cc_ExpiryDate,
																int		 priority,
																float    Amount, 
																int		 transaction_type,
																char	*pAccholdername, 
																char	*pStreet_addr, 
																char	*pCity_addr, 
																char	*pStateprov_addr,
																char	*pZipcode_addr, 
																char	*pCountry_addr,
																char	*pBillingaccounttype)																
{
	int			refid;
	struct tm	*pcc_Expirydate;
	char		ccc_Expirydate[32];
	int			State=0;
	char		AccountType[2];
	char		Accholdername[40];
	char		Street_addr[32];
	char		City_addr[24];
	char		Stateprov_addr[16];
	char		Zipcode_addr[16];
	char		Country_addr[16];


	clsAuthorizationQueue *pAuthorizationQueue;

	// Next Sequence ID
	OpenAndParse(&mpCDAOneShot, SQL_XGetNextReferenceId);

	Define(1, &refid);

	ExecuteAndFetch();

	Close(&mpCDAOneShot);
	SetStatement(NULL);


	// Update CC_Authorize
	OpenAndParse(&mpCDAOneShot,	 SQL_UpdateCCAuthorizationInfo);

	// Initialize
	memset(Accholdername, 0x00, sizeof(Accholdername));
	memset(AccountType, 0x00, sizeof(AccountType));
	memset(Street_addr, 0x00, sizeof(Street_addr));
	memset(City_addr, 0x00, sizeof(City_addr));
	memset(Stateprov_addr, 0x00, sizeof(Stateprov_addr));
	memset(Zipcode_addr, 0x00, sizeof(Zipcode_addr));
	memset(Country_addr, 0x00, sizeof(Country_addr));

	pcc_Expirydate	= localtime(&cc_ExpiryDate);
	TM_STRUCTToORACLE_DATE(pcc_Expirydate, ccc_Expirydate);
	if (pBillingaccounttype)
		strncpy(AccountType, pBillingaccounttype, 1);

	if (pAccholdername)
		strncpy(Accholdername, pAccholdername, sizeof(Accholdername));
	if (pStreet_addr)
		strncpy(Street_addr, pStreet_addr, sizeof(Street_addr));
	if (City_addr)
		strncpy(City_addr, pCity_addr, sizeof(City_addr));
	if (pAccholdername)
		strncpy(Stateprov_addr, pStateprov_addr, sizeof(Stateprov_addr));
	if (Zipcode_addr)
		strncpy(Zipcode_addr, pZipcode_addr, sizeof(Zipcode_addr));
	if (pAccholdername)
		strncpy(Country_addr, pCountry_addr, sizeof(Country_addr));


	// Bind
	Bind(":refid", &refid);
	Bind(":id", &id);
	Bind(":cc", pCCNumber);
	Bind(":cc_expiry_date", ccc_Expirydate);
	Bind(":state", &State);
	Bind(":priority", &priority);
	Bind(":amount", &Amount);
	Bind(":trans_type", &transaction_type);
	Bind(":accholder_name", Accholdername);
	Bind(":street_addr", Street_addr);
	Bind(":city_addr", City_addr);
	Bind(":stateprov_addr", Stateprov_addr);
	Bind(":zipcode_addr", Zipcode_addr);
	Bind(":country_addr", Country_addr);
	Bind(":account_type", AccountType);

	// Do it
	Execute();

	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

    pAuthorizationQueue = new clsAuthorizationQueue(
													id,
													refid,
													pCCNumber,
													cc_ExpiryDate, 
													Amount,
													priority,
													New,			// Transaction state
													0,				// timestamp
													0,				// invoice_batch_id,
													Authorization	// Transaction Type
													);

	return pAuthorizationQueue;

}


// Delete Row by Reference ID
//
static const char *SQL_DeleteRowByRefID = 
" delete from cc_authorize				\
	where		refid = :refid";

void clsDatabaseOracle::RemoveAuthorizationEntry(int refid)
{
		// OpenAndParse
	OpenAndParse(&mpCDAOneShot,
				 SQL_DeleteRowByRefID);
	// Bind
	Bind(":refid", &refid);

	// Do
	Execute();

	// Commit
	Commit();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);
	return;
}


clsAuthorizationQueue *clsDatabaseOracle::GetAuthorizationItemWithID(int refID)
{
	return (clsAuthorizationQueue *)NULL;

}


int clsDatabaseOracle::GetAuthorizationTableSize()
{

	return 0;
}


//
// GetAuthorizationItems
//
#define ORA_AUTHORIZATION_ARRAYSIZE	500

static const char *SQL_GetAuthorizationItems =
 "select				refid,                                          \
						id,												\
						cc,												\
                        TO_CHAR(cc_expiry_date,                         \
                                        'YYYY-MM-DD HH24:MI:SS'),       \
                        TO_CHAR(timestamp,								\
                                        'YYYY-MM-DD HH24:MI:SS'),       \
						amount											\
  from  cc_authorize													\
  where trans_type = :trans_type										\
  and   priority   = :priority											\
  and   state      = :status";

void clsDatabaseOracle::GetAuthorizationItems(int trans_type, int priority, int status,
											  bool bChangeState, int newStatus,	
											  AuthorizationVector *pvAuthorizationItems)
{
        int             refid[ORA_AUTHORIZATION_ARRAYSIZE];
        sb2             refid_ind[ORA_AUTHORIZATION_ARRAYSIZE];
        int             id[ORA_AUTHORIZATION_ARRAYSIZE];
        sb2             id_ind[ORA_AUTHORIZATION_ARRAYSIZE];
		char			cc[ORA_AUTHORIZATION_ARRAYSIZE][32];
		sb2				cc_ind[ORA_AUTHORIZATION_ARRAYSIZE];
        char            cc_expiry_date[ORA_AUTHORIZATION_ARRAYSIZE][32];
        sb2             cc_expiry_date_ind[ORA_AUTHORIZATION_ARRAYSIZE];
        time_t          the_cc_expiry_date;
        char            first_modified[ORA_AUTHORIZATION_ARRAYSIZE][32];
        sb2             first_modified_ind[ORA_AUTHORIZATION_ARRAYSIZE];
        time_t          the_timestamp;
        float			amount[ORA_AUTHORIZATION_ARRAYSIZE];
        sb2             amount_ind[ORA_AUTHORIZATION_ARRAYSIZE];
        int             rowsFetched;
        int             rc;
        int             i,n;
		clsAuthorizationQueue *pAuthorizationQueue;


        // Open and Parse
        OpenAndParse(&mpCDAOneShot,
                                 SQL_GetAuthorizationItems);

		// Bind
		Bind(":trans_type", &trans_type);
		Bind(":priority", &priority);
		Bind(":status", &status);

        // Define
		Define(1,  &refid[0], &refid_ind[0]);
		Define(2,  &id[0], &id_ind[0]);
        Define(3,  (char *)cc[0], sizeof(cc[0]), &cc_ind[0]);
        Define(4,  (char*)cc_expiry_date[0],
					sizeof(cc_expiry_date[0]),
                    &cc_expiry_date_ind[0]);
        Define(5,  (char*)first_modified[0],
					sizeof(first_modified[0]),
                   &first_modified_ind[0]);
        Define(6,  &amount[0], &amount_ind[0]);

        // Execute
        Execute();

        // Now we fetch until we're done
        rowsFetched = 0;
        do
        {

                rc = ofen((struct cda_def *)mpCDACurrent, ORA_AUTHORIZATION_ARRAYSIZE);

                if ((rc < 0 || rc >= 4)  &&
                        ((struct cda_def *)mpCDACurrent)->rc != 1403)   // something wrong
                {
                        Check(rc);
                        Close(&mpCDAOneShot);
                        SetStatement(NULL);
                        return;
                }

                // rpc is cumulative, so find out how many rows to display this time
                // (always <= ORA_AUTHORIZATION_ARRAYSIZE).
                n = ((struct cda_def *)mpCDACurrent)->rpc - rowsFetched;
                rowsFetched += n;

                for (i=0; i < n; i++)
                {
                        // NULL Check
                        if (refid_ind[i] == -1)
                                refid[i] = 0;
                        if (id_ind[i] == -1)
                                id[i] = 0;
                        if (cc_ind[i] == -1)
                                *cc[i] = '\0';
                        if (amount_ind[i] == -1)
                                amount[i] = 0.0;

                        // Convert time
                        if (cc_expiry_date_ind[i] != -1)
                                ORACLE_DATEToTime(cc_expiry_date[i],
                                                   &the_cc_expiry_date);
                        else
                                the_cc_expiry_date = (time_t)0;

                        if (first_modified_ind[i] != -1)
                                ORACLE_DATEToTime(first_modified[i],
                                                  &the_timestamp);
                        else
                                the_timestamp = (time_t)0;


                        pAuthorizationQueue = new clsAuthorizationQueue(
															id[i],
															refid[i],
															cc[i],
															the_cc_expiry_date, 
															amount[i],
															priority,
															status,
															the_timestamp,
															0,		// invoice_batch_id,
															trans_type
															);
                        pvAuthorizationItems->push_back(pAuthorizationQueue);
                }

        } while (!CheckForNoRowsFound());

        Close(&mpCDAOneShot);
        SetStatement(NULL);

		// Change state if that needs to be done

        return;

}


//
// Update the Transaction response from FDMS
//
static const char *SQL_UpdateAuthorizationInfo =
 "Update	cc_authorize									\
	Set	state			= :state,							\
		trans_id		= :trans_id,						\
		trans_timestamp	= TO_DATE(:trans_timestamp,			\
								'YYYY-MM-DD HH24:MI:SS'),	\
		val_code		= :val_code,						\
		author_code		= :author_code,						\
		resp_code		= :resp_code,						\
		avs_resp_code	= :avs_resp_code					\
	where	refid = :refid";


void clsDatabaseOracle::SetAuthorizationStatusForRefID(int refID, aTransResp aResult)
{
	struct tm	*pcc_TransTimeStamp;
	char		 ccc_TransTimeStamp[32];
	int			 DD, MM, hh, mm, ss;
	time_t		 ltime;
	struct tm	*today;
	int			 status;
	char		 trans_id[16];
	char		 val_code[5];
	char		 author_code[7];
	char		 resp_code[3];
	char		 avs_resp_code[2];


	// Open + Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_UpdateAuthorizationInfo);


	memset(trans_id, 0x00, sizeof(trans_id));
	memset(val_code, 0x00, sizeof(val_code));
	memset(author_code, 0x00, sizeof(author_code));
	memset(resp_code, 0x00, sizeof(resp_code));
	memset(avs_resp_code, 0x00, sizeof(avs_resp_code));
	memset(ccc_TransTimeStamp, 0x00, sizeof(ccc_TransTimeStamp));

	time( &ltime );
	today = localtime( &ltime );
	if (aResult.timestamp) // FDMS Time Format is MMDDhhmmss
	{
		sscanf(aResult.timestamp, "%2d%2d%2d%2d%2d", MM, DD, hh, mm, ss);
		pcc_TransTimeStamp->tm_year = today->tm_year;
		pcc_TransTimeStamp->tm_mon	= MM;
		pcc_TransTimeStamp->tm_mday	= DD;
		pcc_TransTimeStamp->tm_hour	= hh;
		pcc_TransTimeStamp->tm_min	= mm;
		pcc_TransTimeStamp->tm_sec	= ss;	
	}
	else
		pcc_TransTimeStamp = today;
	// Convert to Oracle Time structure
	TM_STRUCTToORACLE_DATE(pcc_TransTimeStamp, ccc_TransTimeStamp);

	if (aResult.trans_id)
		strncpy(trans_id, aResult.trans_id,15);
	if (aResult.val_code)
		strncpy(val_code, aResult.val_code,4);
	if (aResult.author_code)
		strncpy(author_code, aResult.author_code,6);
	if (aResult.resp_code)
		strncpy(resp_code, aResult.resp_code,2);
	if (aResult.avs_resp_code)
		strncpy(avs_resp_code, aResult.avs_resp_code,1);

	status = aResult.status;


	// Bind
	Bind(":refid", &refID);
	Bind(":state", &status);
	Bind(":trans_id", trans_id);
	Bind(":trans_timestamp", ccc_TransTimeStamp);
	Bind(":val_code", val_code);
	Bind(":author_code", author_code);
	Bind(":resp_code", resp_code);
	Bind(":avs_resp_code", avs_resp_code);

	// Do it
	Execute();

	// A user account with id must already be available
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;


}


static const char *SQL_GetAuthorizationStatusForRefid =
  "select	state											\
   from		cc_authorize									\
   where	refid =  :refid";								

int clsDatabaseOracle::GetAuthorizationStatusForID(int refID)
{
	int			state=0;
	sb2			state_ind;


	OpenAndParse(&mpCDAOneShot, SQL_GetAuthorizationStatusForRefid);

	// Bind And Define
	Bind(":refid", &refID);
	Define(1, &state, &state_ind);

	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return 0;
	}

    if (state_ind == -1)
		state = 0;

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return state;
}



static const char *SQL_GetCDataForIdRefid =
  "select	cc,												\
            TO_CHAR(cc_expiry_date,                         \
                         'YYYY-MM-DD HH24:MI:SS'),			\
            TO_CHAR(trans_timestamp,						\
                         'YYYY-MM-DD HH24:MI:SS'),			\
			accholder_name,									\
			st_bill_addr,									\
			city_bill_addr,									\
			state_bill_addr,								\
			country_bill_addr,								\
			zip_bill_addr,									\
			account_type									\
   from cc_authorize										\
   where	id = :id										\
   and		refid =  :refid";								

static const char *SQL_InsertIntoBilling =
  "insert	into	cc_billing					\
	(											\
			id,									\
			cc,									\
			cc_expiry_date,						\
			date_authorized,					\
			accholder_name,						\
			st_bill_addr,						\
			city_bill_addr,						\
			state_bill_addr,					\
			country_bill_addr,					\
			zip_bill_addr,						\
			account_type						\
	)											\
	values										\
	(											\
			:id,								\
			:cc,								\
            TO_DATE(:cc_expiry_date,            \
                      'YYYY-MM-DD HH24:MI:SS'), \
            TO_DATE(:date_authorized,           \
                      'YYYY-MM-DD HH24:MI:SS'), \
			:accholder_name,					\
			:st_bill_addr,						\
			:city_bill_addr,					\
			:state_bill_addr,					\
			:country_bill_addr,					\
			:zip_bill_addr,						\
			:account_type						\
	)";

static const char *SQL_UpdateBilling =
 "Update	cc_billing											\
	Set	cc					= :cc,								\
		cc_expiry_date		= TO_DATE(:cc_expiry_date,			\
									'YYYY-MM-DD HH24:MI:SS'),	\
		date_authorized		= TO_DATE(:date_authorized,         \
									'YYYY-MM-DD HH24:MI:SS'),	\
		accholder_name		= :accholder_name,					\
		st_bill_addr		= :st_bill_addr,					\
		city_bill_addr		= :city_bill_addr,					\
		state_bill_addr		= :state_bill_addr,					\
		country_bill_addr	= :country_bill_addr,				\
		zip_bill_addr		= :zip_bill_addr,					\
		account_type		= :account_type						\
	where	id = :id";

static const char *SQL_CheckForUserInBilling =
	"Select count(*) from cc_billing							\
		where id = :id";



void clsDatabaseOracle::CommitCCBillingData(int id, int refID)
{
	char		cc[32];
	char		cc_expiry_date[32];
	char		date_authorized[32];				
	struct tm	*pcc_date_authorized;
	time_t		long_time;
	char		accholder_name[40];
	char		st_bill_addr[32];
	char		city_bill_addr[24];
	char		state_bill_addr[16];
	char		country_bill_addr[16];
	char		zip_bill_addr[16];
	char		account_type[2];
	int			count=0;



	OpenAndParse(&mpCDAOneShot,
				 SQL_GetCDataForIdRefid);

	// Setup
	memset(cc, 0x00, sizeof(cc));
	memset(cc_expiry_date, 0x00, sizeof(cc_expiry_date));
	memset(date_authorized, 0x00, sizeof(date_authorized));
	memset(accholder_name, 0x00, sizeof(accholder_name));
	memset(st_bill_addr, 0x00, sizeof(st_bill_addr));
	memset(city_bill_addr, 0x00, sizeof(city_bill_addr));
	memset(state_bill_addr, 0x00, sizeof(state_bill_addr));
	memset(country_bill_addr, 0x00, sizeof(country_bill_addr));
	memset(zip_bill_addr, 0x00, sizeof(zip_bill_addr));
	memset(account_type, 0x00, sizeof(account_type));

	// Ok, let's do some binds
	Bind(":id", &id);
	Bind(":refid", &refID);

    // Define
    Define(1, cc, sizeof(cc));
    Define(2, cc_expiry_date, sizeof(cc_expiry_date));
    Define(3, date_authorized, sizeof(date_authorized));
    Define(4, accholder_name, sizeof(accholder_name));
    Define(5, st_bill_addr, sizeof(st_bill_addr));
    Define(6, city_bill_addr, sizeof(city_bill_addr));
    Define(7, state_bill_addr, sizeof(state_bill_addr));
    Define(8, country_bill_addr, sizeof(country_bill_addr));
    Define(9, zip_bill_addr, sizeof(zip_bill_addr));
    Define(10, account_type, sizeof(account_type));

	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
	}

	// Close your nose
	Close(&mpCDAOneShot);
	SetStatement(NULL);



	// Check to see if entry is new or an update
	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_CheckForUserInBilling);

	// Bind and Define
	Bind(":id", &id);
	Define(1, &count);

	// Execute
	ExecuteAndFetch();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);



	// If count is 0 then this is the first time user is submitting information
	if (count == 0)
		OpenAndParse(&mpCDAOneShot, SQL_InsertIntoBilling);
	else
		OpenAndParse(&mpCDAOneShot, SQL_UpdateBilling);


	if (strlen(date_authorized) == 0)
	{
        time( &long_time );                
		pcc_date_authorized	= localtime(&long_time);
		TM_STRUCTToORACLE_DATE(pcc_date_authorized, date_authorized);
	}

	// Now for update into cc_billing	
	// Ok, let's do some binds
	Bind(":id", &id);
	Bind(":cc", cc);
	Bind(":cc_expiry_date", cc_expiry_date);
	Bind(":date_authorized", date_authorized);
	Bind(":accholder_name", accholder_name);
	Bind(":st_bill_addr", st_bill_addr);
	Bind(":city_bill_addr", city_bill_addr);
	Bind(":state_bill_addr", state_bill_addr);
	Bind(":country_bill_addr", country_bill_addr);
	Bind(":zip_bill_addr", zip_bill_addr);
	Bind(":account_type", account_type);

	// Do it
	Execute();

	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

}


//
// Update users update attempts counter in cc_billing
//

static const char *SQL_UpdateAttemptCount =								
 "Update	cc_billing													\
	Set	auth_attempt_count	= :count									\
	where	id = :id";

static const char *SQL_ResetAttemptCount = 
 "Update	cc_billing													\
	Set	auth_attempt_count	= 0											\
	where	id = :id";

void clsDatabaseOracle::SetAuthorizationAttemptCount(int id, int count, bool isResetRequired)
{

	if (isResetRequired)
		OpenAndParse(&mpCDAOneShot, SQL_ResetAttemptCount);
	else
	{
		// First get count to see if it was ever set
		if(GetAuthorizationAttemptCount(id) == -1)
			count = 1; // very first attempt

		OpenAndParse(&mpCDAOneShot, SQL_UpdateAttemptCount);
		Bind(":count", &count);
	}

	// Do it
	Execute();

	// A user account with id must already be available
	Commit();

	// Done
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return;

}


//
// Get users cc update attempts counter info. from cc_billing
//
static const char *SQL_GetAttemptCount =
 "Select auth_attempt_count from cc_billing						\
		where	id = :id";

int clsDatabaseOracle::GetAuthorizationAttemptCount(int id)
{
	int			attempts=0;
	sb2			attempts_ind;

	OpenAndParse(&mpCDAOneShot, SQL_GetAttemptCount);

	// Bind And Define
	Bind(":id", &id);
	Define(1, &attempts, &attempts_ind);

	// Get and Do
	ExecuteAndFetch();

	if (CheckForNoRowsFound())
	{
		Close(&mpCDAOneShot);
		SetStatement(NULL);
		return -1;
	}

    if (attempts_ind == -1)
		attempts = -1; // count was never set

	Close(&mpCDAOneShot);
	SetStatement(NULL);

	return attempts;
}


static const char *SQL_InsertIntoSettlement =
  "insert	into	cc_settlement								\
	(															\
		settlement_date,										\
		counter_val												\
	)															\
	values														\
	(															\
		sysdate,												\
		1														\
	)";

static const char *SQL_GetSettlementTableCount =
		"Select count(*) from cc_settlement";

static const char *SQL_GetSettlementData =
 "Select	TO_CHAR(settlement_date,							\
					'YYYY-MM-DD HH24:MI:SS'),					\
			counter_val											\
	From cc_settlement";

static const char *SQL_UpdateSettlement = 
 "Update	cc_settlement										\
	Set	settlement_date	= TO_DATE(:settlement_date,				\
									'YYYY-MM-DD HH24:MI:SS'),	\
		counter_val		= :counter_val";


int clsDatabaseOracle::GetNextSettlementFileId()
{
	int		count=0;
    char    settle_date[32];
    time_t	ltime;    
	struct	tm *today;
	int		dbYYYY, dbMM, dbDD;
	char	ch;
    sb2     settle_date_ind = 0;


	// Open and Parse
	OpenAndParse(&mpCDAOneShot,
				 SQL_GetSettlementTableCount);

	// Define
	Define(1, &count);

	// Execute
	ExecuteAndFetch();

	// Close
	Close(&mpCDAOneShot);
	SetStatement(NULL);

	// If count is 0 then this is the very first settlement operation
	if (count == 0)
	{
		OpenAndParse(&mpCDAOneShot, SQL_InsertIntoSettlement);
		// Do it
		Execute();
		// A user account with id must already be available
		Commit();
		// Done
		Close(&mpCDAOneShot);
		SetStatement(NULL);

		return 1;
	}
	else
	{
		// First Get Current date and counter values
		OpenAndParse(&mpCDAOneShot, SQL_GetSettlementData);
		memset(settle_date, 0x00, sizeof(settle_date));
		Define(1, settle_date, sizeof(settle_date), &settle_date_ind);
		Define(2, &count);
		// Get and Do
		ExecuteAndFetch();
		if (CheckForNoRowsFound())
		{
			Close(&mpCDAOneShot);
			SetStatement(NULL);
			return 0;
		}
        if (settle_date_ind != -1)
		{
			sscanf(settle_date, "%4d%c%2d%c%2d", &dbYYYY, &ch, &dbMM, &ch, &dbDD);
			time( &ltime );
			today = localtime( &ltime );
			if (today->tm_mon+1 == dbMM)	// Same Month
			{
				if (today->tm_mday == dbDD) // Same Day
				{
					count = count + 1;		// Increment File Counter
				}
				else						// Different Day
				{
					count = 1;				
					TM_STRUCTToORACLE_DATE(today, settle_date);
				}
			}
			else							// Different Month
			{
				count = 1;				
				TM_STRUCTToORACLE_DATE(today, settle_date);
			}

			Close(&mpCDAOneShot);
			SetStatement(NULL);

			// Now update cc_settlement with new values
			OpenAndParse(&mpCDAOneShot, SQL_UpdateSettlement);
			Bind(":counter_val", &count);
			Bind(":settlement_date", settle_date);
			Execute();
			Commit();
			// Done
			Close(&mpCDAOneShot);
			SetStatement(NULL);

			return count;
		}

		return count;			
	}	// else

}
