/*	$Id: clseBayAppAdminAccountingBatch.cpp,v 1.12.2.4.40.2 1999/08/05 18:58:51 nsacco Exp $	*/
//
//	File:	clseBayAppAccountingBatch.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Handles traditional AW Accounting batches
//
//	*** NOTE ***
//	The code in here which parses the actual batch
//	records needs to be somewhere where a batch 
//	(non-ISAPI) program can access it
//	*** NOTE ***
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 06/16/98 inna 	- added e-mail for declined Credit Card
//				- 06/19/98 inna 	- added 'u' transaction
//				- 06/29/98 inna		- chg not to hardcode passwords for account/item
//									  view, get it from class MarketPlace. 
//				- 07/01/98 inna		- fix not to compare $ on the checks to transaction$
//									  if this is a fee transaction for returned check		
//				- 06/29/99 sam		- removed support for items related codes i,b,a,g,v,l
//				- 06/29/99 sam		- added codes for Collectors Universe
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "ebihdr.h"

//
// This structure describes a command
//
typedef struct
{
	char					command;
	AccountDetailTypeEnum	accountType;
	int						creditOrdebit;	// 1 for credit, -1 for debit
	bool					justItemStyle;
	AccountDetailTypeEnum	requiredAccountType;
} AccountBatchCommand;

// inna - added 'u'
static const AccountBatchCommand BatchCommands[] =
{
	{	'c',	AccountDetailPaymentCheck,				1,	false,
				AccountDetailUnknown										},
	{	'k',	AccountDetailPaymentCC,					1,	false,
				AccountDetailUnknown										},
	{	'h',	AccountDetailPaymentCash,				1,	false,
				AccountDetailUnknown										},
	{	'o',	AccountDetailPaymentCCOnce,				1,	false,
				AccountDetailUnknown										},
	{	'y',	AccountDetailPaymentMoneyOrder,			1,	false,
				AccountDetailUnknown										},
	{	't',	AccountDetailRefundCC,					-1,	false,
				AccountDetailUnknown										},
	{	's',	AccountDetailRefundCheck,				-1,	false,
				AccountDetailUnknown										},

	// Sam, 6/29/99, Collectors Universe credit codes
	{	'a',	AccountDetailCreditPSA,					1,	false,
				AccountDetailUnknown										},
	{	'b',	AccountDetailCreditPCGS,				1,	false,
				AccountDetailUnknown										},
		
	{	'r',	AccountDetailFeeReturnedCheck,			-1,	false,
				AccountDetailPaymentCheck									},
	{	'f',	AccountDetailFeeRedepositCheck,			-1,	false,
				AccountDetailPaymentCheck									},
	{	'n',	AccountDetailFeeNSFCheck,				-1, false,
				AccountDetailPaymentCheck									},
	{	'd',	AccountDetailFeeReturnCheckClose,		-1,	false,
				AccountDetailPaymentCheck									},
	{	'm',	AccountDetailMemo,						1,	false,			
				AccountDetailUnknown										},
	{	'q',	AccountDetailCreditCardOnFile,			0,	false,			
				AccountDetailUnknown										},
	{	'e',	AccountDetailCreditCardNotOnFile,		0,	false,			
				AccountDetailUnknown										},
	{	'z',	AccountDetailCreditCourtesy,			1,	false,			
				AccountDetailUnknown										},
	{	'w',	AccountDetailCreditTransferFrom,		1,	false,
				AccountDetailUnknown										},
	{	'x',	AccountDetailDebitTransferTo,			-1,	false,			
				AccountDetailUnknown										},
	{	'p',	AccountDetaileBayDebit,					-1,	false,			
				AccountDetailUnknown										},
	{	'j',	AccountDetaileBayCredit,				1,	false,			
				AccountDetailUnknown										},
	{	'u',	AccountDetailCCNotOnFilePerCustReq,		0,	false,			
				AccountDetailUnknown										},
	{	'\0',	AccountDetailUnknown,					1,	false,
				AccountDetailUnknown										}
};

//
// Errors
// 
typedef enum
{
	BatchErrorNoError				= 0,
	BatchErrorBadCommand			= 1,
	BatchErrorMissingItem			= 2,
	BatchErrorBadItem				= 3,
	BatchErroreBayItemWithUser		= 4,
	BatchErroreBaySellerNotFound	= 5,
	BatchErrorAWItemNoUser			= 6,
	BatchErrorAWSellerNotFound		= 7,
	BatchErrorNoUser				= 8,
	BatchErrorBadUser				= 9,
	BatchErrorNoAmount				= 10,
	BatchErrorMemoTooLarge			= 11,
	BatchErrorMissingTransaction	= 12,
	BatchErrorMissingMemo			= 13,
	BatchErrorMemoTooBig			= 14,
	BatchErrorNoAccount				= 15
} BatchErrorEnum;


//
// clsAccountBatchDetail
//	Describes an accounting batch detail 
//
class clsAccountBatchDetail
{
	public:
		char					*mpLine;
		char					mCommand;
		bool					mItemStyle;
		int						mCreditOrDebit;
		AccountDetailTypeEnum	mType;
		int						mItemId;
		char					mOldItemId[16];
		double					mAmount;
		char					mEmail[EBAY_MAX_EMAIL_SIZE + 1];
		char					mMemo[256];
		BatchErrorEnum			mError;
		bool					mErrorFatal;
		AccountDetailTypeEnum	mMissingAccountType;
		double					mAccountBalance;

		clsAccountBatchDetail() :
			mpLine(NULL),
			mCommand(' '),
			mItemStyle(false),
			mType(AccountDetailUnknown),
			mItemId(0),
			mAmount(0),
			mError(BatchErrorNoError),
			mErrorFatal(false),
			mMissingAccountType(AccountDetailUnknown),
			mAccountBalance(0)
		{
			memset(mOldItemId, 0x00, sizeof(mOldItemId));
			memset(mEmail, 0x00, sizeof(mEmail));
			memset(mMemo, 0x00, sizeof(mMemo));
		};



};
		
//
// Generic routine to parse an accounting batch line.
// This is NOT a member function so we can move it 
// later.
//
// For item-style lines (insertion credits, etc), lines
// consist of:
//	Action
//	Tab (\t)
//	Item
//	Tab (\t)
//	Userid (optional, for AW items only)
//
//	Action 'm' (Memo)
//	Tab	(\t)
//	Memo
//
//	Action 'e' or 'f' (Credit card status)
//	Tab (\t)
//	Userid
//
// For all others:
//	Action
//	Tab (\t)
//	Userid
//	Tab (\t)
//	Amount
//	Tab (\t)
//	Memo
//	
//
clsAccountBatchDetail *ParseAccountBatchLine(char *pLine,
											clsUsers *pUsers,
											clsItems *pItems,
											clsDatabase *pDatabase)
{
	// The thing of things
	clsAccountBatchDetail	*pDetail;
	char					*pMyLine;

	// Tokenizers and such
	char					*pToken;

	// To figure out what's up
	const AccountBatchCommand		*pCommand;
	bool							foundCommand	= false;

	// Things
	clsUser					*pUser		= NULL;
	clsItem					*pItem		= NULL;
	clsAccount				*pAccount	= NULL;

	// Details about user transactions
	AccountDetailVector				vAccountDetail;
	AccountDetailVector::iterator	vAI;
	bool							foundRequiredTransaction;

	int						awAccountId;
	int						eBayAccountId;

	char			item[EBAY_MAX_ITEM_SIZE + 1];

	// Let's make a thing
	pDetail		= new clsAccountBatchDetail;

	pMyLine		= new char[strlen(pLine) + 1];
	strcpy(pMyLine, pLine);
	pDetail->mpLine	= pMyLine;

	// Ok, let's get the action
	pToken			= strtok(pLine, "\t");
	if (!pToken)
	{
		pDetail->mError			= BatchErrorBadCommand;
		pDetail->mErrorFatal	= true;
		return pDetail;
	}

	for (pCommand = &BatchCommands[0];
		 pCommand->command != '\0';
		 pCommand++)
	{
		if (pCommand->command == *pToken)
		{
			foundCommand	= true;
			break;
		}
		continue;
	}

	if (!foundCommand)
	{
		pDetail->mError			= BatchErrorBadCommand;
		pDetail->mErrorFatal	= true;

		return pDetail;
	}

	pDetail->mCommand		= *pToken;
	pDetail->mItemStyle		= pCommand->justItemStyle;
	pDetail->mType			= pCommand->accountType;
	pDetail->mCreditOrDebit	= pCommand->creditOrdebit;

	// Now, things get dicey, depending on what we are
	if (pDetail->mType == AccountDetailMemo)
	{
		// Look for userid
		pToken	= strtok(NULL, "\t\r\n");
		if (!pToken)
		{
			pDetail->mError			= BatchErrorNoUser;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}
		if (strlen(pToken) > EBAY_MAX_USERID_SIZE)
		{
			pDetail->mError	= BatchErrorBadUser;
			pDetail->mErrorFatal	= true;

			return pDetail;
		}

		strcpy(pDetail->mEmail, pToken);

		// Look for Memo
		pToken	= strtok(NULL, "\t\r\n");
		if (!pToken)
		{
			pDetail->mError	= BatchErrorMissingMemo;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}
		if (strlen(pToken) > sizeof(pDetail->mMemo))
		{
			pDetail->mError	= BatchErrorMemoTooBig;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}
		pDetail->mAmount	= 0;
		strcpy(pDetail->mMemo, pToken);
		
		//inna before returning detail, validate email
		// Let's pretend it's a userid first
		if (!pUser)
			pUser	= pUsers->GetUser(pDetail->mEmail);

		// If that didn't work, try an account-id
		if (!pUser)
		{
			awAccountId		= 0;
			eBayAccountId	= 0;

			awAccountId	= atoi(pDetail->mEmail);
			if (awAccountId == 0)
			{
				// 
				// Let's see if the first character is E or e, which
				// indicates an eBay account. 
				//
				if (pDetail->mEmail[0] == 'e' ||
					pDetail->mEmail[0] == 'E')
				{
					eBayAccountId	= atoi(&pDetail->mEmail[1]);
					if (eBayAccountId != 0)
						pUser	= pUsers->GetUser(eBayAccountId);
				}
			
				//
				// We get here if:
				//	- the userid wasn't valid
				//	- the userid began with 'E'/'e', and the 
				//	  the remainer isn't a valid #
				//	- the resulting ebay account number after 
				//	  removing the 'E'/'e' wasn't valid
				//
				if (!pUser)
				{
					pDetail->mError	= BatchErrorBadUser;
					pDetail->mErrorFatal	= true;
					delete	pItem;
					return pDetail;
				}
					
				strcpy(pDetail->mEmail, pUser->GetEmail());

			}
			else
			{
				//
				// Otherwise, let's see if it's a legitimate AW account
				// number. If it's not, we'll just try it as an eBay
				// account number (a.k.a userid).
				//
				pDatabase->GeteBayAccountCrossReference(awAccountId,
													  &eBayAccountId);

				if (eBayAccountId != 0)
				{
					pUser	= pUsers->GetUser(eBayAccountId);
				}

				if (!pUser)
				{
					pDetail->mError	= BatchErrorBadUser;
					pDetail->mErrorFatal	= true;
					delete	pItem;
					return pDetail;
				}

				strcpy(pDetail->mEmail, pUser->GetEmail());
			}
		}
		return pDetail;
	}
	// inna - chg codition 
	else if (pDetail->mType == AccountDetailCreditCardOnFile ||
			 pDetail->mType == AccountDetailCreditCardNotOnFile ||
			 pDetail->mType == AccountDetailCCNotOnFilePerCustReq)
	{
		// Look for userid
		pToken	= strtok(NULL, "\t\r\n");
		if (!pToken)
		{
			pDetail->mError			= BatchErrorNoUser;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}
		if (strlen(pToken) > EBAY_MAX_USERID_SIZE)
		{
			pDetail->mError	= BatchErrorBadUser;
			pDetail->mErrorFatal	= true;

			return pDetail;
		}
		strcpy(pDetail->mEmail, pToken);
	}
	// inna - special case for returned checks fees 
	else if (pCommand->accountType == AccountDetailFeeReturnedCheck ||
			 pDetail->mType == AccountDetailFeeRedepositCheck)
	{
		// Look for userid
		pToken	= strtok(NULL, "\t\r\n");
		if (!pToken)
		{
			pDetail->mError			= BatchErrorNoUser;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}
		if (strlen(pToken) > EBAY_MAX_USERID_SIZE)
		{
			pDetail->mError	= BatchErrorBadUser;
			pDetail->mErrorFatal	= true;

			return pDetail;
		}

		strcpy(pDetail->mEmail, pToken);		

		//default amounts:
		// inna - it is a little ugly 
		if (pCommand->accountType == AccountDetailFeeReturnedCheck)
		{
			pDetail->mAmount = FEE_RETURNED_CHECK;
		}

		if (pCommand->accountType == AccountDetailFeeRedepositCheck)
		{
				pDetail->mAmount = FEE_REDEPOSIT_CHECK;
		}
	// looks like there are no memos for this type
	}
	else if (pCommand->justItemStyle)
	{
		// Look for the sitem #
		pToken	= strtok(NULL, "\t\r\n");
		if (!pToken)
		{
			pDetail->mError	= BatchErrorMissingItem;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}

		// Let's see how long the item is
		if (strlen(pToken) > EBAY_MAX_ITEM_SIZE)
		{
			pDetail->mError = BatchErrorBadItem;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}

		// Which kind?
		strcpy(item, pToken);
		if (!isdigit(*pToken))
		{
			strcpy(pDetail->mOldItemId, item);
		}
		else
		{
			pDetail->mItemId	= atoi(item);
		}

		// Next would be userid
		pToken	= strtok(NULL, "\t\n\r");
		if (!pToken)
		{
			pDetail->mEmail[0] = '\0';		
		} 
		else 
		{
			if (strlen(pToken) > EBAY_MAX_USERID_SIZE)
			{
				pDetail->mError	= BatchErrorBadUser;
				pDetail->mErrorFatal	= true;

				return pDetail;
			}

			strcpy(pDetail->mEmail, pToken);
		}
	}
	else
	{
		// Expect Userid...
		pToken	= strtok(NULL, "\t\n\r");
		if (!pToken)
		{
			pDetail->mError	= BatchErrorNoUser;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}

		if (strlen(pToken) > EBAY_MAX_USERID_SIZE)
		{
			pDetail->mError	= BatchErrorBadUser;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}

		strcpy(pDetail->mEmail, pToken);

		// and amount...
		pToken	= strtok(NULL, "\t\n\r");
		if (!pToken)
		{
			pDetail->mError	= BatchErrorNoAmount;
			pDetail->mErrorFatal	= true;
			return pDetail;
		}

		pDetail->mAmount	= atof(pToken);

		// how about a memo
		pToken	= strtok(NULL, "\t\n\r");
		if (pToken)
		{
			if (strlen(pToken) > sizeof(pDetail->mMemo))
			{
				pDetail->mError	= BatchErrorMemoTooLarge;
				pDetail->mErrorFatal	= true;
				return pDetail;
			}

			strcpy(pDetail->mMemo, pToken);
		}
	}


	// 
	// Let's validate some of the data we collected. 
	// This is pretty simple stuff at first
	//

	// 
	// If it's an item style, they're only allowed
	// to have a userid IF it's an AW item
	//
	if (pCommand->justItemStyle)
	{
		if (pDetail->mItemId != 0)
		{
			if (pDetail->mEmail[0] != '\0')
			{
				pDetail->mError = BatchErroreBayItemWithUser;
				pDetail->mErrorFatal	= true;
				return pDetail;
			}
		}
	}

	// If it's an item, let's see if we can see if 
	// it exists. We can only do this for eBay items,
	// not for AW items
	if (pCommand->justItemStyle)
	{
		if (pDetail->mItemId != 0)
		{
			pItem	= pItems->GetItem(pDetail->mItemId);
			if (!pItem)
			{
				pItem = pItems->GetItemArc(pDetail->mItemId);
			};

			if (!pItem)
			{
				pDetail->mError	= BatchErrorBadItem;
				pDetail->mErrorFatal	= true;
				return pDetail;
			}

			// Let's get the seller
			pUser	=	pUsers->GetUser(pItem->GetSeller());
			if (!pUser)
			{
				pDetail->mError	= BatchErroreBaySellerNotFound;
				pDetail->mErrorFatal	= true;
				delete	pItem;
			}

			// Let's remember the seller's email address
			strcpy(pDetail->mEmail, pUser->GetEmail());
		}
	}

	//
	// Now, let's validate the user. We know they're "allowed" to have a 
	// user at this point
	//
	if (pDetail->mEmail[0] != '\0')
	{
		// Let's pretend it's a userid first
		if (!pUser)
			pUser	= pUsers->GetUser(pDetail->mEmail);

		// If that didn't work, try an account-id
		if (!pUser)
		{
			awAccountId		= 0;
			eBayAccountId	= 0;

			awAccountId	= atoi(pDetail->mEmail);
			if (awAccountId == 0)
			{
				// 
				// Let's see if the first character is E or e, which
				// indicates an eBay account. 
				//
				if (pDetail->mEmail[0] == 'e' ||
					pDetail->mEmail[0] == 'E')
				{
					eBayAccountId	= atoi(&pDetail->mEmail[1]);
					if (eBayAccountId != 0)
						pUser	= pUsers->GetUser(eBayAccountId);
				}
			
				//
				// We get here if:
				//	- the userid wasn't valid
				//	- the userid began with 'E'/'e', and the 
				//	  the remainer isn't a valid #
				//	- the resulting ebay account number after 
				//	  removing the 'E'/'e' wasn't valid
				//
				if (!pUser)
				{
					pDetail->mError	= BatchErrorBadUser;
					pDetail->mErrorFatal	= true;
					delete	pItem;
					return pDetail;
				}
					
				strcpy(pDetail->mEmail, pUser->GetEmail());

			}
			else
			{
				//
				// Otherwise, let's see if it's a legitimate AW account
				// number. If it's not, we'll just try it as an eBay
				// account number (a.k.a userid).
				//
				pDatabase->GeteBayAccountCrossReference(awAccountId,
													  &eBayAccountId);

				if (eBayAccountId != 0)
				{
					pUser	= pUsers->GetUser(eBayAccountId);
				}

				if (!pUser)
				{
					pDetail->mError	= BatchErrorBadUser;
					pDetail->mErrorFatal	= true;
					delete	pItem;
					return pDetail;
				}

				strcpy(pDetail->mEmail, pUser->GetEmail());
			}
		}
	}

	// Get the user's account, since we'll need it
	pAccount	= pUser->GetAccount();
	if (!pAccount->Exists())
	{
		pDetail->mError			= BatchErrorNoAccount;
		pDetail->mErrorFatal	= false;

		delete	pAccount;
		delete	pUser;
		delete	pItem;
		return pDetail;
	}

	// Let's keep their balance...
	pDetail->mAccountBalance	= pAccount->GetBalance();

	// If this is an item style, we'll want to see if 
	// the transaction makes sense
	if (pCommand->justItemStyle)
	{
		// Let's get all the transactions for this item for
		// this user.
		if (pDetail->mItemId != 0)
			pAccount->GetAccountDetailForItem(pDetail->mItemId,
											  &vAccountDetail);
		else
			pAccount->GetAccountDetailForItem(pDetail->mOldItemId,
											  &vAccountDetail);

		// First, let's see if we got ANY transactions for this
		// item
		if (vAccountDetail.size() < 1)
		{
			pDetail->mError	= BatchErrorMissingTransaction;
			pDetail->mErrorFatal	= true;

			pDetail->mMissingAccountType	= 
				pCommand->requiredAccountType;
			delete	pAccount;
			delete	pUser;
			delete	pItem;
			return	pDetail;
		}

		// Now, let's look for the prerequisites
		foundRequiredTransaction	= true;
		for (vAI = vAccountDetail.begin();
			 vAI != vAccountDetail.end();
			 vAI++)
		{
			if ((*vAI)->mType == pCommand->requiredAccountType)
			{
				pDetail->mAmount			= (*vAI)->mAmount;
				foundRequiredTransaction	= true;
				break;
			}
		}

		if (!foundRequiredTransaction)
		{
			pDetail->mError	= BatchErrorMissingTransaction;
			pDetail->mMissingAccountType	= 
				pCommand->requiredAccountType;
		}

		// Clean up
		for (vAI = vAccountDetail.begin();
			 vAI != vAccountDetail.end();
			 vAI++)
		{
			delete	(*vAI);
		}
		vAccountDetail.erase(vAccountDetail.begin(),
							 vAccountDetail.end());

		delete	pAccount;
		delete	pUser;
		delete	pItem;

		if (pDetail->mError != BatchErrorNoError)
			return	pDetail;
	}
	else
	{
		// If it's not an item style, there are some other
		// transactions we can check on. In particular, we
		// can check for matching check payments. We look
		// for an exact amount match here.
		//
		// This code isn't very "table driven", since it "knows"
		// that all the Check transactions 
		if (pCommand->accountType == AccountDetailFeeReturnedCheck	||
			pCommand->accountType == AccountDetailFeeRedepositCheck ||
			pCommand->accountType == AccountDetailFeeNSFCheck		||
			pCommand->accountType == AccountDetailFeeReturnCheckClose	)
		{
			pAccount->GetAccountDetailByType(pCommand->requiredAccountType,
											  &vAccountDetail);

			foundRequiredTransaction	= false;
			for (vAI = vAccountDetail.begin();
				 vAI != vAccountDetail.end();
				 vAI++)
			{
				// inna f and f need only one entry in this vector, no $ match required
			    if (pCommand->accountType == AccountDetailFeeReturnedCheck	||
					pCommand->accountType == AccountDetailFeeRedepositCheck )
				{
						foundRequiredTransaction	= true;
						break;
				}
				else 
				{
					// inna add round to cents
					if ((*vAI)->mType == pCommand->requiredAccountType)
					{
						if (RoundToCents((*vAI)->mAmount) == pDetail->mAmount)
						{
							foundRequiredTransaction	= true;
							break;
						}
					}
				}
			}

			if (!foundRequiredTransaction)
			{
				pDetail->mError	= BatchErrorMissingTransaction;
				pDetail->mErrorFatal	= true;
				pDetail->mMissingAccountType	= 
					pCommand->requiredAccountType;
			}

			// Clean up
			for (vAI = vAccountDetail.begin();
				 vAI != vAccountDetail.end();
				 vAI++)
			{
				delete	(*vAI);
			}
			vAccountDetail.erase(vAccountDetail.begin(),
								 vAccountDetail.end());

			delete	pAccount;
			delete	pUser;
			delete	pItem;

			if (pDetail->mError != BatchErrorNoError)
			{
				return	pDetail;
			}
		}
	}

	// Account for unintended sign changes
	if (pDetail->mCreditOrDebit > 0)
	{
		if (pDetail->mAmount < 0)
			pDetail->mAmount = -pDetail->mAmount;
	}
	else if (pDetail->mCreditOrDebit < 0)
	{
		if (pDetail->mAmount > 0)
			pDetail->mAmount = -pDetail->mAmount;
	}



	// Well, we made it here, so we're cool!
	return pDetail;
}
		
			
//
// A little routine to translate errors into strings
//
static const char *BatchErrorMessages[] =
{
	"No error",
	"Unrecognized command",
	"Required item number missing",
	"Item not found!",
	"Users may not be specified with eBay items",
	"eBay Item Seller not on file!",
	"Please include userid with AW items!",
	"Seller not found for AW item!",
	"Please include required userid!",
	"UserId not found!",
	"Please include required amount!",
	"Memo field too long!",
	"Can\'t find %s for item!",
	"Please include a memo for this item!",
	"Memo field too long!",
	"Warning! No eBay Account found for %s!"
};
// inna all 3 static var changes to get password from clsMarketPlace
// kakiyama 07/09/99 - TODO: 
// resource the following using clsIntlResource::GetFResString

static const char *eBayItemLine = 
"%s: Item <a href=http://skippy.ebay.com/aw-cgi/admin/eBayISAPI.dll?ViewItem"
"&item=%d>%d</a>, Seller : <a href=http://skippy.ebay.com/aw-cgi/admin/eBayISAPI.dll?ViewAccount"
"&userid=%s&pass=%s>%s</a>, Amount <font color=green>%8.2f</font>";

static const char *AWItemLine = 
"%s: Item <a href=http://skippy.ebay.com/aw-cgi/admin/eBayISAPI.dll?ViewItem"
"&item=%s>%s</a>, Seller : <a href=http://skippy.ebay.com/aw-cgi/admin/eBayISAPI.dll?ViewAccount"
"&userid=%s&pass=%s>%s</a>, Amount <font color=green>%8.2f</font>";

static const char *OtherLine =
"%s: User : <a href=http://skippy.ebay.com/aw-cgi/admin/eBayISAPI.dll?ViewAccount"
"&userid=%s&pass=%s>%s</a>, Amount <font color=green>%8.2f</font>, Balance "
"<font color=green>%8.2f</font>";


char *ReportError(clsAccountBatchDetail *pDetail)
{
	char	*pErrorMsg;

	pErrorMsg	= new char[512];

	if (pDetail->mError == BatchErrorNoError)
	{

		if (pDetail->mItemStyle)
		{
			if (pDetail->mItemId == 0)
			{
				sprintf(pErrorMsg,
						AWItemLine,
						clsAccount::GetAccountDetailDescriptor(pDetail->mType),
						pDetail->mOldItemId,
						pDetail->mOldItemId,
						pDetail->mEmail,
						gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(),
						pDetail->mEmail,
						pDetail->mAmount);
			}
			else
			{
				sprintf(pErrorMsg,
						eBayItemLine,
						clsAccount::GetAccountDetailDescriptor(pDetail->mType),
						pDetail->mItemId,
						pDetail->mItemId,
						pDetail->mEmail,
						gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(),
						pDetail->mEmail,
						pDetail->mAmount);
			}
		}
		else
		{
			sprintf(pErrorMsg,
					OtherLine,
					clsAccount::GetAccountDetailDescriptor(pDetail->mType),
					pDetail->mEmail,
					gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSpecialPassword(),
					pDetail->mEmail,
					pDetail->mAmount,
					pDetail->mAccountBalance);
		}

		return pErrorMsg;
	}

	if (pDetail->mError != BatchErrorMissingTransaction)
	{
		if (pDetail->mError == BatchErrorNoAccount)
		{
			sprintf(pErrorMsg,
					(char *)BatchErrorMessages[(int)pDetail->mError],
					pDetail->mEmail);
		}
		else
		{
			strcpy(pErrorMsg, (char *)BatchErrorMessages[(int)pDetail->mError]);
		}
		return pErrorMsg;
	}
	else
	{
		sprintf(pErrorMsg,
				"Can\'t find %s for item!",
				clsAccount::GetAccountDetailDescriptor(pDetail->mMissingAccountType));
		return pErrorMsg;
	}

	return "Huh?";

}
/** INNA Start ***/

// 
// MailCreditCardNotOnFile Notification to the user
//
int clseBayApp::MailCreditCardNotOnFileNotice(clsUser *pUser)
/* so far I only pass UserID, If need more info from 
   pAccountDetail can pass it to this method */
{
	clsMail		*pMail;
	ostream		*pMStream;
	char		subject[256];
	int			mailRc;
	clsAnnouncement			*pAnnouncement;
	char*	pTemp;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();


	// Start Message Text
	*pMStream 
		<<	"Dear eBay User:"
		<<	"\n\n";
		// emit CCReject announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(CCReject,Header,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};

	*pMStream	<<	"\n"
				<<	"We are sorry, but we were unable to place your credit card"
				<<	"\n"
				<<  "information on file at eBay."
				<<	"\n"
				<<	"\n"

				<<  "Please check with your card issuer to make sure that your credit card is"
				<<	"\n"
				<<  "active." 
				<<	"\n"
				<<	"\n"

				<<  "If you have any questions about your current account status, you can view"
				<<	"\n"
				<<  "your account at: " 
				<<  mpMarketPlace->GetHTMLPath()
				<<	"services/buyandsell/account-status.html"
				<<	"\n"
				<<	"\n"

				<<  "Please also check to make sure that the credit card number and"
				<<	"\n"
				<<  "expiration date you provided to eBay were correct. You may resubmit your" 
				<<	"\n"
				<<  "credit card information to eBay at any time either:" 
				<<	"\n"
				<<	"\n"

				<<  "1. Online (through our secure server) at"
				<<	"\n"
				<<  "https://arribada.ebay.com/aw-secure/cc-update.html." 
				<<	"\n"
				<<	"\n"

				<<  "2. By Mail or Fax (using the form on the page listed below) at"
				<<	"\n"
				<<	mpMarketPlace->GetSecureHTMLPath()
				<<  "cc-update.html" 
				<<	"\n"
				<<	"\n"
				<<  "When you place your credit card on file, your card will be charged an"
				<<	"\n"
				<<  "initial $10 which will be applied immediately to your account for usage at" 
				<<	"\n"
				<<  "eBay. Please note that this is not a fee!  If you do not use your $10"
				<<	"\n"
				<<  "credit, you may contact eBay for a refund." 
				<<	"\n"
				<<	"\n"


				<<  "If you do not wish to enter a new credit card and still want to make a"
				<<	"\n"
				<<  "payment to your account, please go to our  payment coupon page at:" 
				<<	"\n"
				<<	"\n"

	//			<<  "http://pages.ebay.com/services/buyandsell/pay-coupon.html" 
	// kakiyama 07/16/99
				<<  mpMarketPlace->GetHTMLPath()
				<<  "services/buyandsell/pay-coupon.html"

				<<	"\n"
				<<	"\n"

				<<  "Thanks for your understanding," 
				<<	"\n"
				<<	"\n"
				<<	"\n"

				<<  "eBay Billing and Customer Support" 
				<<	"\n"				
				<<  "support@ebay.com" 
				<<	"\n";

	*pMStream	<<	flush;

	// emit CCReject announcements
	pAnnouncement = mpAnnouncements->GetAnnouncement(CCReject,Footer,
		mpMarketPlace->GetCurrentPartnerId(), mpMarketPlace->GetCurrentSiteId());
	if (pAnnouncement)
	{
		pTemp = clsUtilities::RemoveHTMLTag(pAnnouncement->GetDesc());
		*pMStream << pTemp;
		*pMStream << "\n";
		delete pAnnouncement;
		delete pTemp;
	};


	// Send
	sprintf(subject, "Status of %s Credit Card",
			mpMarketPlace->GetCurrentPartnerName());

	// Inna's note 2nd parm is From User; who should it be? 
	// this should be ccard@ebay.com, it is not a member of class marketplace
	// i can just hard coded it here, or add another static data member to 
	// marketplace. This how it would look if I did chage market place
	   mailRc =	pMail->Send(pUser->GetEmail(), 
					(char *)mpMarketPlace->GetCCardEmail(),
							subject);



	// All done!
	delete	pMail;

	return mailRc;
}
/** INNA End ***/

/** INNA Start ***/

// 
// MailCCNoticeErrors information to the sender of Notifications
//
int clseBayApp::MailCCNoticeErrors(clsUser *pUser)
{
	clsMail		*pMail;
	ostream		*pMStream;
	char		subject[256];
	int			mailRc;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();

	// Start Message Text

		*pMStream	<< "Error occured while sending "
					   "Credit Card Rejected Notice to "
                    <<"\n" 
					<<   pUser->GetEmail()
					<< " address. ";

		*pMStream	<<	flush;

	// Send
	sprintf(subject, "%s Error occured while sending Credit Card Rejected Notice.",
			mpMarketPlace->GetCurrentPartnerName());

	mailRc =pMail->Send((char *)mpMarketPlace->GetCCardEmail(), 
					(char *)mpMarketPlace->GetCCardEmail(), subject);

 	/** debug only mailRc =pMail->Send("@qqqq@aol.com", 
					(char *)mpMarketPlace->GetCCardEmail(),
							subject); **/


	// All done!
	delete	pMail;

	return mailRc;
}
/** INNA End ***/

void clseBayApp::AccountingBatch(CEBayISAPIExtension* pCtxt,
							  char *pText,
							  int how,
							  char *pPassword,
							  eBayISAPIAuthEnum authLevel)
{


	
	// The vector of requests
	vector<clsAccountBatchDetail *>				vBatch;

	// Itcherator
	vector<clsAccountBatchDetail *>::iterator	vI;

	clsAccountBatchDetail		*pDetail;
	clsAccountBatchDetail		*pOtherDetail;
	clsAccountDetail			*pAccountDetail;

	// Tokenizers and such
	bool						badLine;
	bool						doneWithLine;
	bool						done;
	char						*pStartOfLine;
	char						*pEndOfLine;
	int							lineLen;
	char						line[1024];

	// Other things
	clsUser							*pUser;
	clsAccount						*pAccount;
	AccountDetailVector				vAccountDetail;


	char							*pMessage;
	bool							error;

	time_t							nowTime;
	struct							tm *pNowTimeAsTm;
	char							cNowTime[64];

	double							batchBalance = 0;

	BatchErrorEnum					saveError;

	// INNA
	int								mailRc=1;

	// Common Setup
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Accounting Batch"
					"</title>"
					"</HEAD>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}


	// Let's parse those lines
	done			= false;
	error			= false;
	pStartOfLine	= pText;

	do
	{
		badLine			= false;
		doneWithLine	= false;

		// Let's see if we're done
		if (*pStartOfLine == '\0')
		{
			done	= true;
			continue;
		}

		// Let's look for an end-of-line
		pEndOfLine	= strchr(pStartOfLine, '\n');
		if (!pEndOfLine)
			pEndOfLine	= strchr(pStartOfLine, '\r');
		
		// If no newline just use the end of this line, and
		// this will be the LAST line...
		if (!pEndOfLine)
		{
			pEndOfLine	= pStartOfLine + strlen(pStartOfLine);
			done		= true;
		}

		// If it's 0 length, we're done.
		lineLen	= pEndOfLine - pStartOfLine;
		if (lineLen < 1)
			break;

		// We've got a line. Prime strtok
		memcpy(line, pStartOfLine, lineLen);
		line[lineLen]	= '\0';

		// Reset Start-of-line for next line
		pStartOfLine	= pEndOfLine + 1;

		// Get it parsed
		pDetail	= ParseAccountBatchLine(line,
										mpUsers,
										mpItems,
										mpDatabase);

		// Let's remember if we had an error. 
		if (pDetail->mError != BatchErrorNoError &&
			pDetail->mErrorFatal)
			error	= true;

		//
		// ** SPECIAL CASE **
		// If this is an AccountDetailPaymentCC, and the user does 
		// NOT have a credit card on file, let's add a transaction
		// to put one on file first
		//
		if (pDetail->mType == AccountDetailPaymentCC)
		{
			pUser	= mpUsers->GetUser(pDetail->mEmail);
			if (!pUser->HasCreditCardOnFile())
			{
				pOtherDetail		= new clsAccountBatchDetail;
				pOtherDetail->mpLine	= new char[1 + 1 + EBAY_MAX_USERID_SIZE + 1];
				strcpy(pOtherDetail->mpLine, "q\t");
				strcat(pOtherDetail->mpLine, pDetail->mEmail);
				pOtherDetail->mCommand			= 'q';
				pOtherDetail->mItemStyle		= false;
				pOtherDetail->mCreditOrDebit	= 0;
				pOtherDetail->mType				= AccountDetailCreditCardOnFile;
				pOtherDetail->mItemId			= 0;
				pOtherDetail->mOldItemId[0]		= '\0';
				pOtherDetail->mAmount			= 0;
				strcpy(pOtherDetail->mEmail, pDetail->mEmail);
				pOtherDetail->mMemo[0]			= '\0';
				pOtherDetail->mError			= BatchErrorNoError;
				pOtherDetail->mErrorFatal		= false;
				pOtherDetail->mMissingAccountType	= AccountDetailUnknown;
				pOtherDetail->mAccountBalance		= 0;
				vBatch.push_back(pOtherDetail);
			}
		}
		vBatch.push_back(pDetail);

	} while (!done);


	//
	// Now, let's review what we saw
	//
	if (error)
	{
		*mpStream <<	"<h2>Errors in input are in <font color=red>red</font></h2>"
						"<br>";
	}


	*mpStream <<	"<TABLE WIDTH=100% BORDER=1>";

	for (vI = vBatch.begin();
		 vI != vBatch.end();
		 vI++)
	{
		*mpStream <<	"<TR>"
						"<TD WIDTH=25% ALIGN=LEFT>";

		if ((*vI)->mError != BatchErrorNoError)
		{
			if ((*vI)->mErrorFatal)
				*mpStream <<	"<FONT COLOR=RED>";
			else
				*mpStream <<	"<FONT COLOR=GREEN>";
		}

		*mpStream <<	(*vI)->mpLine
				  <<	"</TD>"
				  <<	"<TD WIDTH=75% ALIGN=LEFT>";

		//
		// If we're committing, AND this was a warning, clear the
		// error flag so we can report it properly
		//
		if (how && !(*vI)->mErrorFatal)
		{
			saveError		= (*vI)->mError;
			(*vI)->mError	= BatchErrorNoError;
			pMessage = ReportError((*vI));
			(*vI)->mError	= saveError;
		}
		else
			pMessage = ReportError((*vI));


		*mpStream <<	pMessage;
		delete	pMessage;

		*mpStream <<	"</TD>";

		if ((*vI)->mError != BatchErrorNoError)
			*mpStream <<	"</FONT>";

		*mpStream <<	"</TR>\n";

		if ((*vI)->mError == BatchErrorNoError ||
			(*vI)->mErrorFatal == false)
		{
			batchBalance	=	batchBalance + 
								((*vI)->mAmount * (*vI)->mCreditOrDebit);
		}

	}

	*mpStream <<	"</TABLE>"
					"<BR>";

	// Ok, let's see what we do NOW
	if (error)
	{
		*mpStream <<	"<b>Errors</b> in input. No action performed";
	}
	else if (how)
	{
			if (strcmp(pPassword, mpMarketPlace->GetAdminSpecialPassword()) != 0)
		{
			*mpStream <<	"<b>Password invalid</b>. No changes made</b>"
							"<br>"
					  <<	mpMarketPlace->GetFooter();
			return;
		} 

		*mpStream <<	"<b>No errors</b> found. Committing";

		for (vI = vBatch.begin();
			 vI != vBatch.end();
			 vI++)
		{
			// We'll need a user
			pUser		= mpUsers->GetUser((*vI)->mEmail);
			pAccount	= pUser->GetAccount();
			// Ok, let's make an naked account detail
			pAccountDetail	= new clsAccountDetail;

			// Fill it in with what we know
			pAccountDetail->mTime		= time(0);
			pAccountDetail->mType		= (*vI)->mType;
			pAccountDetail->mpMemo		= (char *)&(*vI)->mMemo;
			pAccountDetail->mAmount		= (*vI)->mAmount * (*vI)->mCreditOrDebit;

			// Account for unintended sign changes
			if ((*vI)->mCreditOrDebit > 0)
			{
				if (pAccountDetail->mAmount < 0)
					pAccountDetail->mAmount = -pAccountDetail->mAmount;
			}
			else if ((*vI)->mCreditOrDebit < 0)
			{
				if (pAccountDetail->mAmount > 0)
					pAccountDetail->mAmount = -pAccountDetail->mAmount;
			}

			if ((*vI)->mItemStyle)
			{
				if ((*vI)->mItemId == 0)
				{
					strcpy(pAccountDetail->mOldItemId, (*vI)->mOldItemId);
					pAccountDetail->mItemId	= 0;
				}
				else
					pAccountDetail->mItemId	= (*vI)->mItemId;
			}

			pAccount->AddRawAccountDetail(pAccountDetail);

			if ((*vI)->mItemStyle)
			{
				if ((*vI)->mItemId != 0)
				{
					gApp->GetDatabase()->AddAccountItemXref(
											pAccountDetail->mTransactionId,
											(*vI)->mItemId);
				}
				else
				{
					gApp->GetDatabase()->AddAccountAWItemXref(1, 
											 &pAccountDetail->mTransactionId,
											 (char *)&(*vI)->mOldItemId);
				}
			}

			//
			// If it's a Credit Card on File code, then let's make it so.
			// OR, if it's a credit card payment, then they MUST have a 
			// Credit card on file, and thus..
			//
			if (pAccountDetail->mType == AccountDetailCreditCardOnFile)
			{
				// Get Credit Card status to force us to get user info
				pUser->HasCreditCardOnFile();
				pUser->SetHasCreditCardOnFile(true);
				pUser->UpdateUser();
			}

			// 
			// Or not...
			//
			// inna chg condition
			if (pAccountDetail->mType == AccountDetailCreditCardNotOnFile ||
				pAccountDetail->mType == AccountDetailCCNotOnFilePerCustReq)
			{
				// Get Credit Card status to force us to get user info
				pUser->HasCreditCardOnFile();
				pUser->SetHasCreditCardOnFile(false);
				pUser->UpdateUser();
				//INNA start
				if (pAccountDetail->mType == AccountDetailCreditCardNotOnFile)
				{
					//Send this user an email about his card being BAD
					mailRc = MailCreditCardNotOnFileNotice(pUser);

					if (!mailRc)
					{
						// This will only take care of error while sending
						// *** Undelivered e-mails will come back to sender
						// later - can be a couple of days.
						// test hint: add @ in front of name
           
						//  display bad info on the screen
						*mpStream	<<	"<br><b> Error</b> occured while sending "
										"Credit Card Rejected Notice to "
									<<	pUser->GetEmail()
									<<	".";
					              
						//send CCEmail message
						mailRc = MailCCNoticeErrors(pUser);

						if (!mailRc)
						{
							//CCEmail could not get your e-mail
							//inform him on the screen
							*mpStream	<< "<font color=red> Could not email info to "
										<<  mpMarketPlace->GetCCardEmail()
										<< ". </font> Please take an note "
										<< " of the email address in error.";

						}
						else
							//email to CCEmail was sent OK
							*mpStream	<< "This info was emailed to "
										<<  mpMarketPlace->GetCCardEmail()
										<< ".";

					}
				}// end INNA

			}

			// Rather than completely rebalancing the account (which is
			// expensive), we use AdjustBalance, which not only adjusts
			// the balance, it "adjusts" for past due.

			pAccount->AdjustBalance(pAccountDetail->mAmount);

			// Reset the detail's memo pointer to 0, 'cause it
			// doesn't own that storage.
			pAccountDetail->mpMemo	= NULL;

			delete	pAccountDetail;
			delete	pAccount;
			delete	pUser;
		
		}
	
		

		// Timestamp
		nowTime			= time(0);
		pNowTimeAsTm	= localtime(&nowTime);

		strftime(cNowTime, sizeof(cNowTime),
				 "%m/%d %H:%M:%S PDT",
				 pNowTimeAsTm);

		*mpStream <<	"<br>"
						"Batch Complete at "
				  <<	cNowTime
				  <<	"<br>";



	}

	// If there wasn't an error, print the batch balance
	if (!error)
	{
		*mpStream <<	"<br>"
				  <<	"<font size=+1>Batch Balance: "
				  <<	batchBalance
				  <<	"</font>"
						"<br>";
	}

	// Clean up the list
	for (vI = vBatch.begin();
	     vI != vBatch.end();
	     vI++)
	{
		// Delete the User
		delete	(*vI);
	}

	vBatch.erase(vBatch.begin(), vBatch.end());

	// Finish

	*mpStream <<	mpMarketPlace->GetFooter();

	// Clean

	CleanUp();

	return;
}
