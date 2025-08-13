/*	$Id: clseBayAppCreditBatch.cpp,v 1.7.158.1 1999/08/01 03:01:12 barry Exp $	*/
//
//	File:	clseBayAppCreditBatch.cpp
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	Handles traditional AW Credit batches
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "clseBayTimeWidget.h"		// petra

//
// Errors
// 
typedef enum
{
	CreditErrorNoError				= 0,
	CreditErrorNoItem				= 1,
	CreditErrorBadItem				= 2,
	CreditErrorBadUser				= 3,
	CreditErrorNoChargeForItem		= 4,
	CreditErrorCreditExistsForItem	= 5,
	CreditErrorNoFinalFeeForItem	= 6,
	CreditErrorWrongUser			= 7
} CreditErrorEnum;

//
// clsCredit
//	Describes a credit action, and result
//
class clsCredit
{
	public:
		int				mItemId;
		char			mOldItemId[16];
		double			mAmount;
		char			mOverrideEmail[EBAY_MAX_EMAIL_SIZE + 1];
		CreditErrorEnum	mError;
		double			mFinalValueFee;
		double			mInsertionFee;
		int				mSellerId;

		clsCredit() :
			mItemId(0),
			mAmount(0),
			mError(CreditErrorNoError),
			mFinalValueFee(0),
			mInsertionFee(0),
			mSellerId(0)
		{
			memset(mOldItemId, 0x00, sizeof(mOldItemId));
			memset(mOverrideEmail, 0x00, sizeof(mOverrideEmail));
		};


		clsCredit(int itemId,
				  char *pOldItemId,
				  double amount,
				  char *pOverrideEmail) :
			mItemId(itemId),
			mAmount(amount),
			mError(CreditErrorNoError),
			mFinalValueFee(0),
			mInsertionFee(0),
			mSellerId(0)
		{
			if (pOldItemId)
			{
				strcpy(mOldItemId, pOldItemId);
			}
			else
				memset(mOldItemId, 0x00, sizeof(mOldItemId));

			if (pOverrideEmail)
			{
				strcpy(mOverrideEmail, pOverrideEmail);
			}
			else
				memset(mOverrideEmail, 0x00, sizeof(mOverrideEmail));
		}
};
				
			


void clseBayApp::CreditBatch(CEBayISAPIExtension* pCtxt,
							  char *pText,
							  int how,
							  char *pPassword,
							  eBayISAPIAuthEnum authLevel)
{
	// The vector of requests
	vector<clsCredit *>			vCredits;

	// Itcherator
	vector<clsCredit *>::iterator	vI;

	clsCredit					*pCredit;

	// Tokenizers and such
	bool						badLine;
	bool						doneWithLine;
	bool						done;
	char						*pStartOfLine;
	char						*pEndOfLine;
	int							lineLen;
	char						line[1024];
	char						*pItemNo;
	char						*pAmount;
	char						*pOverrideEmail;

	char						item[16];
	char						oldItemId[16];
	int							theItem;
	char						amount[16];
	double						theAmount;
	char						overrideEmail[EBAY_MAX_USERID_SIZE + 1];
	int							overrideEmailLen;

	// Other things
	clsItem							*pItem;
	clsUser							*pUser;
	clsAccount						*pAccount;
	AccountDetailVector				vAccountDetail;
	AccountDetailVector::iterator	vAI;

	bool							foundFinalValueFee;

	bool							error;

	double							theFee;

// petra	time_t							nowTime;
// petra	struct							tm *pNowTimeAsTm;
// petra	char							cNowTime[64];
	
	// Common Setup
	SetUp();

	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Administrative Credit Batch"
					"</title>"
					"</head>"
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

		oldItemId[0]		= '\0';
		theItem				= 0;
		theAmount			= 0;
		overrideEmail[0]	= '\0';

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

		// Find item #
		pItemNo	= strtok(line, "\t\n\r");

		// If pItem is NULL, then we didn't find a
		// tab of newline. 
		if (!pItemNo )
		{
			error	= CreditErrorNoItem;
			badLine	= true;
			error	= true;
		}

		if (!badLine)
		{
			strcpy(item, pItemNo);
		}

		// Now, we could have an amount
		pAmount	= strtok(NULL, "\t\n\r");
		if (pAmount && (strlen(pAmount) != 0))
		{
			strcpy(amount, pAmount);
			theAmount	= atof(amount);
		}
		else
		{
			amount[0]		= '\0';
			doneWithLine	= true;
			theAmount		= 0;
		}

		// And we could have an overriding email
		if (!doneWithLine)
		{
			pOverrideEmail	= strtok(NULL, "\t\n\r");
			if (pOverrideEmail &&
				*pOverrideEmail != '\0')
			{
				overrideEmailLen	= strlen(pOverrideEmail);
				strcpy(overrideEmail, pOverrideEmail);
				if ((overrideEmail[overrideEmailLen - 1] == '\n') ||
					(overrideEmail[overrideEmailLen - 1] == '\r'))
					overrideEmail[overrideEmailLen - 1] = '\0';
			}
			else
			{
				overrideEmail[0]	= '\0';
				doneWithLine		= true;
			}
		}

		// Ok, now let's make an object out of it
		if (!isdigit(item[0]))
		{
			strcpy(oldItemId, item);
			theItem	= 0;
		}
		else
			theItem	= atoi(item);

		pCredit	= new clsCredit(theItem,
								oldItemId,
								theAmount,
								overrideEmail);

		vCredits.push_back(pCredit);

	} while (!done);

	//
	// Now, let's validate the list
	//
	pItem		= NULL;
	pUser		= NULL;
	pAccount	= NULL;

	for (vI = vCredits.begin();
	     vI != vCredits.end();
	     vI++)
	{
		// Clean up after last pass
		delete	pAccount;
		pAccount	= NULL;
		delete	pUser;
		pUser		= NULL;
		delete	pItem;
		pItem		= NULL;

		// Let's see if this is even worth pursuing
		if ((*vI)->mError != CreditErrorNoError)
			continue;

		// If it's an item, let's see if it exists
		pItem	= NULL;
		if ((*vI)->mItemId != 0)
		{
			pItem	= mpItems->GetItem((*vI)->mItemId);
			// if not pItem, get it from archive
			if (!pItem)
			{
				pItem = mpItems->GetItemArc((*vI)->mItemId);
			};

			if (!pItem)
			{
				(*vI)->mError	= CreditErrorBadItem;
				error			= true;
				continue;
			}
		}

		// Look up the user
		pUser	= NULL;
		if ((*vI)->mOverrideEmail[0] != '\0')
		{
			pUser	= mpUsers->GetUser((*vI)->mOverrideEmail);
		}
		else
		{
			if (pItem)
				pUser	= mpUsers->GetUser(pItem->GetSeller());
		}

		if (!pUser)
		{
			(*vI)->mError		= CreditErrorBadUser;
			error				= true;
			continue;
		}

		//
		// If we have the item, make sure the seller matches
		// the user
		//
		if (pItem)
		{
			if (pUser->GetId() != pItem->GetSeller())
			{
				(*vI)->mError	= CreditErrorWrongUser;
				error			= true;
				continue;
			}
		}

		// Remember who the user is 
		(*vI)->mSellerId	= pUser->GetId();

		// Get the user's account
		pAccount	= pUser->GetAccount();

		// Now, we really don't need ALL of the user's account
		// for this transaction. Just the things to do with this
		// item (if any)
		if ((*vI)->mItemId != 0)
			pAccount->GetAccountDetailForItem((*vI)->mItemId,
											  &vAccountDetail);
		else
			pAccount->GetAccountDetailForItem((*vI)->mOldItemId,
											  &vAccountDetail);

		// If we didn't get any details for this item, then
		// obviously we can't give a credit for it
		if (vAccountDetail.size() < 1)
		{
			(*vI)->mError		= CreditErrorNoChargeForItem;
			error				= true;
			continue;
		}

		// Now, let's make sure we don't already have a credit
		// for this item
		foundFinalValueFee	= false;

		for (vAI = vAccountDetail.begin();
			 vAI != vAccountDetail.end();
			 vAI++)
		{
			// If we've already seen ANY kind of credit for this item,
			// then we can't accept this one.
			if ((*vAI)->mType == AccountDetailCreditDuplicateListing ||
				(*vAI)->mType == AccountDetailCreditNoSale ||
				(*vAI)->mType == AccountDetailCreditCourtesy ||
				(*vAI)->mType == AccountDetailCreditPartialSale)
			{
				(*vI)->mError	= CreditErrorCreditExistsForItem;
				error			= true;
				break;
			}

			// If this is a final value fee, then indicate we found
			// it. Record the fee, too to make things easier later
			if ((*vAI)->mType == AccountDetailFeeFinalValue)
			{
				(*vI)->mFinalValueFee	= (*vAI)->mAmount;
				foundFinalValueFee	= true;
			}

			// Gather insertion fee
			if ((*vAI)->mType == AccountDetailFeeInsertion)
			{
				(*vI)->mInsertionFee	= (*vAI)->mAmount;
			}

		}

		// Let's see if we found a FV fee
		if ((*vI)->mError == CreditErrorNoError &&
			!foundFinalValueFee)
		{
			(*vI)->mError	= CreditErrorNoFinalFeeForItem;
			error			= true;
		}

		// Ok, clean up the vector
		for (vAI = vAccountDetail.begin();
			 vAI != vAccountDetail.end();
			 vAI++)
		{
			delete	(*vAI);
		}

		vAccountDetail.erase(vAccountDetail.begin(),
							 vAccountDetail.end());
	}

	//
	// Now, let's review what we saw
	//
	if (error)
	{
		*mpStream <<	"<h2>Errors in input are in <font color=red>red</font></h2>"
						"<br>"
						"<pre>"
						"\n";
	}
	else
	{
		*mpStream <<	"<h2>No Errors found. Transactions follow</h2>"
						"<br>"
						"<pre>"
						"\n";
	}


	for (vI = vCredits.begin();
		 vI != vCredits.end();
		 vI++)
	{
		*mpStream <<	"\n";

		if ((*vI)->mError == CreditErrorNoError)
		{
			pUser	= mpUsers->GetUser((*vI)->mSellerId);
			
			if ((*vI)->mOldItemId[0] == '\0')
				*mpStream <<	(*vI)->mItemId;
			else
				*mpStream <<	(*vI)->mOldItemId;

			*mpStream <<	"\t"
					  <<	(*vI)->mAmount
					  <<	"\t"
					  <<	(*vI)->mOverrideEmail
					  <<	"\t"
							"User: "
					  <<	pUser->GetEmail()
					  <<	" "
					  <<	"Final Value Fee:"
					  <<	(*vI)->mFinalValueFee
					  <<	" ";

			delete	pUser;
			pUser	= NULL;

			if ((*vI)->mAmount > 0)
			{
				if ((*vI)->mAmount >= 25)
				{
					theFee = (25 * 0.05) + (((*vI)->mAmount - 25) * 0.025);
				}
				else
				{
					theFee = (*vI)->mAmount * 0.05;
				}

				// I don't know WHY we used to do this (deduct the insertion
				// fee from the credit), but I don't do it any more. In any
				// case, if we DO need to do it, then we need to ADD the 
				// insertion fee back in since it's negative. 
				// theFee	= theFee + (*vI)->mInsertionFee;
			}
			else
				theFee	= 0;

			if (theFee < 0)
				theFee	= -theFee;

			*mpStream <<	"Credit Amount:"
					  <<	theFee
					  <<	" ";

		}
		else
		{
			*mpStream <<	"<FONT COLOR=RED>";
			if ((*vI)->mOldItemId[0] == '\0')
				*mpStream <<	(*vI)->mItemId;
			else
				*mpStream <<	(*vI)->mOldItemId;

			*mpStream <<	"\t"
					  <<	(*vI)->mAmount
					  <<	"\t"
					  <<	(*vI)->mOverrideEmail
					  <<	"\t"
							"</font>";

			switch ((*vI)->mError)
			{
				case CreditErrorNoItem:
					*mpStream <<	"No Item Number!";
					break;
				case CreditErrorBadItem:
					*mpStream <<	"Item not found!";
					break;
				case CreditErrorBadUser:
					*mpStream <<	"User not found for item!";
					break;
				case CreditErrorNoChargeForItem:
					*mpStream <<	"No charges found for item!";
					break;
				case CreditErrorCreditExistsForItem:
					*mpStream <<	"Credit already exists for item!";
					break;
				case CreditErrorNoFinalFeeForItem:
					*mpStream <<	"No Final Value fee charged for item!";
					break;
				case CreditErrorWrongUser:
					*mpStream <<	"Item not sold by "
							  <<	(*vI)->mOverrideEmail
							  <<	"!";
					break;
				default:
					*mpStream <<	"*Unknown Error*";
					break;
			}

			*mpStream <<	"\n";
		}
	}
	*mpStream <<	"</pre>"
					"<br>";

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

		for (vI = vCredits.begin();
			 vI != vCredits.end();
			 vI++)
		{
			//
			// If it's an eBay Item, get it again
			//
			if ((*vI)->mItemId != 0)
			{
				// We need the item (again)
				pItem	= mpItems->GetItem((*vI)->mItemId);
				if (!pItem)
				{
					pItem = mpItems->GetItemArc((*vI)->mItemId);
				};
				if (!pItem)
				{
					*mpStream <<	"<h2>Error! Could not get item #"
							  <<	(*vI)->mItemId
							  <<	" on second pass!</h2>"
							  <<	"<p>"
							  <<	mpMarketPlace->GetFooter();
					CleanUp();
					return;
				}
			}
			else
				pItem	= NULL;

			//
			// Now, of course, we need the user and their account
			//
			pUser	= mpUsers->GetUser((*vI)->mSellerId);
			if (!pUser)
			{
				*mpStream <<	"<h2>Error! Could not get user #"
						  <<	(*vI)->mSellerId
						  <<	" on second pass!</h2>"
						  <<	"<p>"
						  <<	mpMarketPlace->GetFooter();
				CleanUp();
				return;
			}

			pAccount	= pUser->GetAccount();

			//
			// First, we refund the final value fee. Note that
			// the fee is -ve (because it's a fee), So we need
			// to change it's sign
			//
			if ((*vI)->mAmount == 0)
			{
				if (pItem)
					pAccount->ApplyNoSaleCredit(pItem,
												-(*vI)->mFinalValueFee);
				else
					pAccount->ApplyNoSaleCredit((*vI)->mOldItemId,
												-(*vI)->mFinalValueFee);
			}
			else
			{
				if (pItem)
					pAccount->ApplyPartialSaleCredit(pItem,
													 -(*vI)->mFinalValueFee);
				else
					pAccount->ApplyPartialSaleCredit((*vI)->mOldItemId,
													 -(*vI)->mFinalValueFee);
			}

			//
			// Now, let's see if there's a partial fee due
			//
			if ((*vI)->mAmount > 0)
			{
				if ((*vI)->mAmount >= 25)
				{
					theFee = (25 * 0.05) + (((*vI)->mAmount - 25) * 0.025);
				}
				else
				{
					theFee = (*vI)->mAmount * 0.05;
				}

				// I don't know WHY we used to do this (deduct the insertion
				// fee from the credit), but I don't do it any more. In any
				// case, if we DO need to do it, then we need to ADD the 
				// insertion fee back in since it's negative. 
				// theFee	= theFee + (*vI)->mInsertionFee;
			
				if (pItem)
					pAccount->ChargePartialSaleFee(pItem,
												   theFee);
				else
					pAccount->ChargePartialSaleFee((*vI)->mOldItemId,
												   theFee);
			}

			// Clean up
			delete		pUser;
			pUser	= NULL;

			delete		pItem;
			pItem	= NULL;
		}

		// Timestamp
// petra		nowTime			= time(0);
// petra		pNowTimeAsTm	= localtime(&nowTime);

// petra		strftime(cNowTime, sizeof(cNowTime),
// petra				 "%m/%d %H:%M:%S PDT",
// petra				 pNowTimeAsTm);

		*mpStream <<	"Batch Complete at ";
		clseBayTimeWidget timeWidget (mpMarketPlace, 0, 2, (time_t)0);
		timeWidget.EmitHTML (mpStream);
// petra				  <<	cNowTime
		*mpStream <<	"<br>";

	}

	// Clean up the list
	for (vI = vCredits.begin();
	     vI != vCredits.end();
	     vI++)
	{
		// Delete the User
		delete	(*vI);
	}

	vCredits.erase(vCredits.begin(), vCredits.end());


	// Clean

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;
}
