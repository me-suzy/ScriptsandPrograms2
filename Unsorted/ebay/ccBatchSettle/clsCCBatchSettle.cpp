/*	$Id: clsCCBatchSettle.cpp,v 1.3 1999/02/21 02:30:43 josh Exp $	*/
//
//	File:	clsBatchSettle.cpp
//
//	Class:	clsBatchSettle
//
//	Author:	Sam Paruchuri (sam@ebay.com)
//
//	Function:	Gather settlement items that have been queued
//				in cc_settlement and dump them to the settlement
//				record file to be dispatched to FDMS for final settlement
//
// Modifications:
//				- 07/13/98 sam	- Created
//

#include <iostream.h>
#include "clsCCBatchSettle.h"
#include "clsAuthorize.h"
#include "clsDatabase.h"
#include "clsAccountDetail.h"
#include "clsMail.h"
#include "clsSettlement.h"
#include "clsAuthorizationQueue.h"


static const char *ErrorFailedAuthorization = 
"Customer Account:\tE%d\n"
"Customer Name:\t\t%s\n"
"Credit Card Account:\t%d-XXXX-XXXX-XXXX\n"
"Payment Due:\t\t$%.2f\n"
"\n\n"
"Dear %s,"
"\n\n"
"We were unable to authorize a charge for $%.2f on your credit card. "
"Please make sure that your credit card on file at eBay is valid and "
"has enough credit to allow this payment. You may use one of the following methods "
"to allow the payment to go through. \n\n"
"1. Use our online secure Credit Card update page at %s to update credit card information.\n\n"
"2. Email Customer Support mailto:support@ebay.com to make alternate payment arrangements.\n\n\n"
"Thank you for using eBay!";



clsBatchSettle::clsBatchSettle()
{
	mApp.InitShell();
	pMarketPlaces = gApp->GetMarketPlaces();
	pMarketPlace  = pMarketPlaces->GetCurrentMarketPlace();

}


clsBatchSettle::~clsBatchSettle()
{

}


void clsBatchSettle::DumpSettlementRecordsToFile()
{
    clsSettlement				 *pclsSettlement=NULL;
	clsSettlementEBayInformation *pClsSettlementEBayInfo=NULL;
	char						 *pSettlementFileName       = "settlement.txt";
	char						 *pSettlementAckReqFileName = "settlementAckreq.txt";
	TransactionResult			  outAuthResponse;
	bool						  bWriteSettleAckReqRcd = true;
	SettlementVector			  vToSettleItems;
    SettlementVector            ::iterator i;
	char						 *pszCardExpDateInput=NULL;


	// Mark state as valid for all entries, in the case of error
	// the state must be reset to Reject
	mAuthQueue.GetNewSettlementRecords(&vToSettleItems, 
										New,
										true, Valid);

	if (vToSettleItems.empty())
		return; // either error or no entries available for Authorization

	// Get File Sequence number
	i = vToSettleItems.begin();
	pClsSettlementEBayInfo	= new clsSettlementEBayInformation(pSettlementFileName,
															   (*i)->batch_id);
    pclsSettlement			= new  clsSettlement(pClsSettlementEBayInfo);


    for (i =  vToSettleItems.begin();
         i != vToSettleItems.end();
         i++)
	{

		strcpy(outAuthResponse.timestamp, (*i)->date_authorized);
		strcpy((char *)outAuthResponse.pszSystemTrace, (*i)->refid);
		strcpy((char *)outAuthResponse.trans_amount, (*i)->Amount);
		strcpy(outAuthResponse.resp_code, (*i)->resp_code);
		strcpy(outAuthResponse.trans_id, (*i)->trans_id);
		strcpy(outAuthResponse.val_code, (*i)->val_code);
		strcpy(outAuthResponse.author_code, (*i)->author_code);
		strcpy(outAuthResponse.avs_resp_code, (*i)->avs_resp_code);
		outAuthResponse.status = Valid;
		// In Loop
		pszCardExpDateInput	= mAuthQueue.ISOCreditCardExpirationFmt((*i)->cc_expiry_date);
		pclsSettlement->WriteSaleSettlementRecord(outAuthResponse,
												 (*i)->cc,
												 pszCardExpDateInput);

		// Cleanup and Next record
		delete pszCardExpDateInput;
		pszCardExpDateInput = NULL;
	}

	// Terminate record and close the open settlement record file
	pclsSettlement->Done();

	// Cleanup
	vToSettleItems.erase(vToSettleItems.begin(), vToSettleItems.end());
	delete pClsSettlementEBayInfo;
	delete pclsSettlement;
}
