/*	$Id: clsCCAuthBuddy.cpp,v 1.5 1999/04/17 20:22:03 wwen Exp $	*/
//
//	File:	clsCCAuthBuddy.cpp
//
//	Class:	clsCCAuthBuddy
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:	Gather authorization items that have been queued
//				in cc_authorize have them authorized via FDMS.
//				Currently, all items that are of type "Authorization"
//				in state New and with priority 1 are picked up.
//
//
//  Notes:	1.	Ideally an internal Authorization vector should be
//				maintained that will contain all requests that are
//				queued into cc_authorize. However, there is a problem
//				using stl elements during linking. Alternatively, there
//				will be 2 sets of requests to cc_authorize, one to send
//				authorizations to FDMS and other to process results.
//			2.	No increment in Authorization_Attempt counter is made by
//				this program and no check as well is done.
//
//
// Modifications:
//				- 06/17/98 sam	- Created
//				- 07/13/98 sam  - added support for email notices
//				- 07/17/98 sam  - error recovery logic added
//

#include "clsCCAuthBuddy.h"
#include "clsAuthorize.h"
#include "clsDatabase.h"
#include "clsAccountDetail.h"
#include "clsMail.h"

#define  AMINUTE		60

clsCCAuthBuddy::clsCCAuthBuddy()
{
	mApp.InitShell();
	pMarketPlaces = gApp->GetMarketPlaces();
	pMarketPlace  = pMarketPlaces->GetCurrentMarketPlace();
}


clsCCAuthBuddy::~clsCCAuthBuddy()
{

}


// Uses clsAuthorize to dispatch Authorization requests
// Mode:batch
int clsCCAuthBuddy::ProcessEnqueuedRequests()
{
	clsAuthorizationQueue	*pAuthEntry=NULL, *pBillingEntry=NULL;
	clsUser					*pUser=NULL;
	clsAccount				*pAccount=NULL;
	clsAccountDetail	    *pAccountDetail=NULL;
	int						 CC4Id=0;
	time_t					 CurrentTime;
	char					 str[5];
	int						 FDMSResp=0;


	time(&CurrentTime);

	pAuthEntry = mAuthQueue.GetQueueEntryData(	Authorization, 
												0,			// priority is 0 for real time
												AnyState);	// process all entries in any state

	if (!pAuthEntry)
		return -1; // either error or no entries available for Authorization


	while (pAuthEntry)
	{
		// Check for redundant entries and delete if true
		pBillingEntry = mAuthQueue.Find(pAuthEntry->GetId(), cc_billing);
		if (pBillingEntry)
		{
			if (strcmp(pBillingEntry->GetCC(), pAuthEntry->GetCC()) == 0)
			{
				if (pBillingEntry->GetCCExpiryDate() >= pAuthEntry->GetCCExpiryDate())
				{
					pAuthEntry->Remove(pAuthEntry->GetReferenceId(), cc_authorize, true);
					delete pBillingEntry;
					delete pAuthEntry;
					continue;
				}
			}
			delete pBillingEntry;
		}

		pUser = new clsUser(pAuthEntry->GetId());
		memset(str, 0x00, sizeof(str));
		CC4Id = atoi(strncpy(str, pAuthEntry->GetCC(),4));

		// Check the state and process accordingly
		switch (pAuthEntry->GetCCTransState())
		{
			case New:
					// Don't process entry if it is New, give atleast 1 minute window,
					// pick the entry in next run of the batch program
					if	(pAuthEntry->GetFDMSTransTimestamp() == 0	&& 
						(CurrentTime - pAuthEntry->GetTransactionTimestamp()) <= AMINUTE)
					{
						delete pUser;
						delete pAuthEntry;
						continue;
					}
					else if (CurrentTime - pAuthEntry->GetTransactionTimestamp() > AMINUTE)
					// User was notified that he will be sent an email after authorization
					// due to queue being busy
						ProcessUserForCCAuthorization(pAuthEntry);
			break;
			case VIP:
					// Don't delete entry if it is current, give atleast 1 minute window,
					// pick the entry in next run of the batch program
					if	(pAuthEntry->GetFDMSTransTimestamp() == 0	&& 
						(CurrentTime - pAuthEntry->GetTransactionTimestamp()) <= AMINUTE	)
					{
						delete pUser;
						delete pAuthEntry;
						continue;
					}
			break;
			case Error:
					// User was notified that he will be sent an email after authorization
					ProcessUserForCCAuthorization(pAuthEntry);
			break;
			case Valid:
					switch(pAuthEntry->GetUpdateStatus())
					{
						case NOTKNOWN:
						{
							// All Updates Required
							pAuthEntry->AddSettlementRecord();
							pAuthEntry->StoreCCUpdate();
							if (!pUser->GetCreditCardOnFile())
							{
								pUser->SetCreditCardOnFile(true);
								// And update db
								pUser->UpdateUser();
								pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																SET_CC_ON_FILE);
							}
							pAccount = pUser->GetAccount();
							pAccount->UpdateCCDetails(	pAuthEntry->GetId(),	
														CC4Id,			
														pAuthEntry->GetCCExpiryDate(),			
														pAuthEntry->GetTransactionTimestamp());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															UPDATED_EBAY_ACCOUNT_BALANCES);
							pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 								 (double)pAuthEntry->GetChargeAmount(),
																 "CC New/Update Authorization thru FDMS");

							pAccount->AddRawAccountDetail(pAccountDetail, 0);
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																UPDATED_EBAY_ACCOUNTS);

							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);
						}
						break;
						case UPDATED_CC_SETTLEMENT:
						{
							// All Updates after update to cc_settlement
							pAuthEntry->StoreCCUpdate();
							if (!pUser->GetCreditCardOnFile())
							{
								pUser->SetCreditCardOnFile(true);
								// And update db
								pUser->UpdateUser();
								pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																SET_CC_ON_FILE);
							}
							pAccount = pUser->GetAccount();
							pAccount->UpdateCCDetails(	pAuthEntry->GetId(),	
														CC4Id,			
														pAuthEntry->GetCCExpiryDate(),			
														pAuthEntry->GetTransactionTimestamp());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															UPDATED_EBAY_ACCOUNT_BALANCES);
							pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 								 (double)pAuthEntry->GetChargeAmount(),
																 "CC New/Update Authorization thru FDMS");

							pAccount->AddRawAccountDetail(pAccountDetail, 0);
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																UPDATED_EBAY_ACCOUNTS);

							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);

						}
						break;
						case UPDATED_CC_BILLING:
						{
							// All Updates after update to cc_billing
							if (!pUser->GetCreditCardOnFile())
							{
								pUser->SetCreditCardOnFile(true);
								// And update db
								pUser->UpdateUser();
								pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																SET_CC_ON_FILE);
							}
							pAccount = pUser->GetAccount();
							pAccount->UpdateCCDetails(	pAuthEntry->GetId(),	
														CC4Id,			
														pAuthEntry->GetCCExpiryDate(),			
														pAuthEntry->GetTransactionTimestamp());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															UPDATED_EBAY_ACCOUNT_BALANCES);
							pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 								 (double)pAuthEntry->GetChargeAmount(),
																 "CC New/Update Authorization thru FDMS");

							pAccount->AddRawAccountDetail(pAccountDetail, 0);
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																UPDATED_EBAY_ACCOUNTS);

							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);

						}
						break;
						case SET_CC_ON_FILE:
						{
							// All Updates after CC_ON_FILE was set
							pAccount = pUser->GetAccount();
							pAccount->UpdateCCDetails(	pAuthEntry->GetId(),	
														CC4Id,			
														pAuthEntry->GetCCExpiryDate(),			
														pAuthEntry->GetTransactionTimestamp());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															UPDATED_EBAY_ACCOUNT_BALANCES);
							pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 								 (double)pAuthEntry->GetChargeAmount(),
																 "CC New/Update Authorization thru FDMS");

							pAccount->AddRawAccountDetail(pAccountDetail, 0);
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																UPDATED_EBAY_ACCOUNTS);

							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);

						}					
						break;
						case UPDATED_EBAY_ACCOUNT_BALANCES:
						{
							// All Updates after UpdateCC
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															UPDATED_EBAY_ACCOUNT_BALANCES);
							pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 								 (double)pAuthEntry->GetChargeAmount(),
																 "CC New/Update Authorization thru FDMS");

							pAccount->AddRawAccountDetail(pAccountDetail, 0);
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
																UPDATED_EBAY_ACCOUNTS);

							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);

						}
						break;
						case UPDATED_EBAY_ACCOUNTS:
						{
							// All Updates after adding entry in ebay_accounts
							pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
							pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
															ADJUSTED_EBAY_ACCOUNT_BALANCES);

							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);

						}
						break;
						case ADJUSTED_EBAY_ACCOUNT_BALANCES:
						{
							// All Updates after adjusting ebay_account_balances
							SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);

							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);
						}
						break;
						case UPDATED_ALL:
							// All Updates done, simply mark entry as processed
							pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
																	  Processed);
						break;					
					} // switch
			break;
			case Reject:
			{
				// Send reject email and remove entry only if it was left behind
				if (CurrentTime - pAuthEntry->GetTransactionTimestamp() > AMINUTE)
				{
					FDMSResp = pAuthEntry->GetFDMSResponseCode(pAuthEntry->GetReferenceId());
					SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);
				}
			}
			break;
		}

		// Removes Entry in any state
		pAuthEntry->Remove(pAuthEntry->GetReferenceId(), cc_authorize, true);
		// Reset FDMS code
		FDMSResp=0;

		// Cleanup for this entry
		delete pAuthEntry;
		pAuthEntry		= NULL;
		delete pUser;
		pUser			= NULL;
		delete pAccount;
		pAccount		= NULL;
		delete pAccountDetail;
		pAccountDetail	= NULL;

		// Next one
		pAuthEntry = mAuthQueue.GetNextQueueEntryData(true, VIP);
	}

	return 0;
}


void clsCCAuthBuddy::SendAuthorizationEmail(int CC4Id, int FDMSResp, 
											clsAuthorizationQueue *pAuthEntry)
{
	char					 sendBuf[1024];
	clsMail					*pMail=NULL;
	ostream					*pMStream=NULL;
	char					*subject;



	if (FDMSResp == 0)
	{
		// Approval email
		sprintf(sendBuf, pAuthEntry->GetAuthErrorMsg(FDMSResp, "EMAIL"), 
						 pAuthEntry->GetId(),
						 pAuthEntry->GetAccHolderName(),
						 CC4Id,
						 pAuthEntry->GetAccHolderName(),
						 pMarketPlace->GetHTMLPath(),
						 pMarketPlace->GetHTMLPath());
		subject	=	SubFDMSApprovedCC;
	}
	else
	{
		// Reject email
		sprintf(sendBuf, pAuthEntry->GetAuthErrorMsg(FDMSResp, "EMAIL"),
						 pAuthEntry->GetId(),
						 pAuthEntry->GetAccHolderName(),
						 CC4Id,
						 pAuthEntry->GetAccHolderName(),
						 pMarketPlace->GetSecureHTMLPath());
		subject	=	SubFDMSErrorCC;
	}

	// Send Mail
	pMail	 = new clsMail;
	pMStream = pMail->OpenStream();
	*pMStream << sendBuf;
	pMail->Send("sam@ebay.com", 
				(char *)pMarketPlace->GetBillingEmail(),
				subject);

	delete pMail;
}


void clsCCAuthBuddy::CheckAndAlertAdmin(clsAuthorizationQueue *pAuthEntry, int CC4Id)
{
	char				buf[512];
	char				str[5];
	clsMail				*pMail=NULL;
	ostream				*pMStream=NULL;
	bool				UsedByMultAcc=false;

	AuthorizationVector vAuthItems;
	AuthorizationVector ::iterator mAuthIter;


	// Get all records from cc_billing that have the same CC
	pAuthEntry->GetAllAccountsWithSameCC(pAuthEntry->GetCC(), &vAuthItems);

	if (vAuthItems.empty())
		// do nothing, no other accounts have same CC
		return;

	// Send email to Admin with a listing of all accounts id's that 
	// use this CC
	pMail	 = new clsMail;
	pMStream = pMail->OpenStream();
	memset(str, 0x00, sizeof(str));
	memset(buf, 0x00, sizeof(buf));

	sprintf(buf, pAuthEntry->GetAdminAlertMsg("Authorization"),
				 pAuthEntry->GetId(),
				 pAuthEntry->GetAccHolderName(),
				 CC4Id);
	*pMStream	<<	buf;

    for (mAuthIter  = vAuthItems.begin();
         mAuthIter != vAuthItems.end();
         mAuthIter++)
    {

	   if ( (*mAuthIter)->GetId() != pAuthEntry->GetId() )
	   {
			*pMStream	<< (*mAuthIter)->GetId()
						<< "\n";		
			UsedByMultAcc = true;
	   }
	   delete (*mAuthIter);
	}

	if ( UsedByMultAcc )
	{
		*pMStream <<	"\n\n"
						"Please take appropriate action to prevent disruption in billing\n"
						"and other account related services.";
		// Send Mail
		pMail->Send("sam@ebay.com", 
		//			(char *)mpMarketPlace->GetBillingEmail(),
					"Credit Card Update Page",
					"Admin Alert! - Credit Card Authorization failure.");

	}

	delete pMail;
	vAuthItems.erase(vAuthItems.begin(),
					vAuthItems.end());

}


void clsCCAuthBuddy::ProcessUserForCCAuthorization (clsAuthorizationQueue *pAuthEntry)
{

	clsUser				*pUser			= NULL;
	clsAccount			*pAccount		= NULL;
	clsAccountDetail	*pAccountDetail = NULL;
	char				str[5];
	int					CC4Id;
	int					FDMSResp=-1;


	pUser = new clsUser(pAuthEntry->GetId());
	memset(str, 0x00, sizeof(str));
	CC4Id = atoi(strncpy(str, pAuthEntry->GetCC(),4));
	// This will set status to Valid, Reject or Error from VIP
	FDMSResp = pAuthEntry->SendAndUpdateTransactionStatus(); 
	// Process Valid State
	if (pAuthEntry->GetCCTransState() == Valid)
	{
		// Add entry to cc_settlement to be dispatched for settling the authorized amount
		pAuthEntry->AddSettlementRecord();
		// Commit to billing table
		pAuthEntry->StoreCCUpdate();
		// Set CC_ON_FILE
		// If New User then set CC_ON_FILE, Update Accounts table
		// If old User then update Accounts table
		// Update User Info, CC_ON_FILE if needed
		if (!pUser->GetCreditCardOnFile())
		{
			pUser->SetCreditCardOnFile(true);
			// And update db
			pUser->UpdateUser();
			pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
											SET_CC_ON_FILE);
		}

		// Update ebay_account_balances
		pAccount = pUser->GetAccount();
		pAccount->UpdateCCDetails(	pAuthEntry->GetId(),	
									CC4Id,			
									pAuthEntry->GetCCExpiryDate(),			
									pAuthEntry->GetTransactionTimestamp());

		pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
										UPDATED_EBAY_ACCOUNT_BALANCES);

		// Update ebay_accounts table
		pAccountDetail = new clsAccountDetail(AccountDetailPaymentCC,
								 			 (double)pAuthEntry->GetChargeAmount(),
											 "CC New/Update Authorization thru FDMS");

		pAccount->AddRawAccountDetail(pAccountDetail, 0);
		pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
											UPDATED_EBAY_ACCOUNTS);

		// Update ebay_account_balances table to reflect credit
		pAccount->AdjustBalance(pAuthEntry->GetChargeAmount());
		pAuthEntry->SetEbayUpdateStatus(pAuthEntry->GetReferenceId(), 
										ADJUSTED_EBAY_ACCOUNT_BALANCES);

		// All is well, send email to user
		SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);
		// Set state
		pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
												  Processed);
	} // Valid State
	else if (pAuthEntry->GetCCTransState() == Reject)
	{
		// Send auth fail email to user
		SendAuthorizationEmail(CC4Id, FDMSResp, pAuthEntry);
		// Set state
		pAuthEntry->SetAuthorizationStateInDB(pAuthEntry->GetReferenceId(),
												  Processed);
		// Admin Alert Check
		CheckAndAlertAdmin(pAuthEntry, CC4Id);
	}

	// Cleanup
	delete pUser;
	delete pAccount;
	delete pAccountDetail;
}
