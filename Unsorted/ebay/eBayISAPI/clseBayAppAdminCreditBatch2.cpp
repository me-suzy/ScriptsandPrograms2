/*	$Id: clseBayAppAdminCreditBatch2.cpp,v 1.16.2.1.102.1 1999/08/01 02:51:43 barry Exp $	*/
//
//	File:	clseBayAppAdminCreditBatch2.cpp
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
//				- 06/26/98 inna		- fixed not to allow no sale credit due to reserve
//									  price not met	
//				- 07/07/98 inna		- fixed use reserve price and quantity if applicable
//									  when calculating insertion fee credit amount
//				- 08/15/98 inna		- added Final Value Fee credit due to ebay's fault to
//									  a list of actions that cause FVF flag to be updated
//									  on item; this will help not to double credit	
//				- 09/17/98 inna		- fixed double current balance adjustment in
//									  case of partial credit					
//				- 09/28/98 mila		- add item to deadbeat items table in database when
//									  no-sale credit is applied		
//				- 12/14/98 mila		- first pass at breaking up CreditBatch2() code
//									  into smaller functions to improve readability;
//									  go through clsDeadbeat object to add new deadbeat
//									  item to the database; changed all occurrences of
//									  vector<clsCredit2 *> to CreditVector, where the
//									  latter is tyepdef'ed to the former
//				- 12/16/98 mila		- added commented out code to set high bidder
//									  and quantity for deadbeat items in dutch
//									  auctions; this will need to be uncommented
//									  when auto-credits is implemented
//				- 02/01/99 sam		- added creditdump function to allow billing admin to
//									  create tab delimited file from ebay_auction_credits
//				- 02/03/99 sam		- Changed reason for chinese auction to be picked from
//									- ebay_auction_credits instead of 
//				- 02/25/99 mila		- modified calculation of credit amount for Chinese and
//									  Dutch auctions
//				- 02/28/99 mila		- restored calculation of credit amount to that in E110;
//									  added commented out calls to CreditBatchCleanUp() and
//									  CleanUp() to plug memory leaks; will uncomment after
//									  initial rollout of E111 to admin to allow for adequate
//									  testing
//				- 02/28/99 Wen		- added the last entry to CodeToCreditTypes as a terminator.
//				- 02/28/99 Wen		- set mLine to null in the credit2 constructor.
//				- 03/23/99 mila		- uncommented calls to CreditBatchCleanUp() and CleanUp()
//									  to plug memory leaks
//				- 04/15/99 sam		- mail autocredits dump file to billing
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4786 )
#include "ebihdr.h"
#include "hash_map.h"


#define AUTOCREDITSDIR	"c:\\autocredits\\"
// Should be a more generic mail box but kirsti for now
#define MAILTO			"kirsti@ebay.com"
//
// Errors
// 
typedef enum
{
	CreditErrorNoError				= 0,
	CreditErrorNoItem				= 1,
	CreditErrorBadItem				= 2,
	CreditErrorNoChargeForItem		= 4,
	CreditErrorCreditExistsForItem	= 5,
	CreditErrorNoFinalFeeForItem	= 6,
	CreditErrorAuctionNotOver		= 7,
	CreditErrorBadCode				= 8,
	CreditErrorAmountNotAllowed		= 9,
	CreditErrorNotCategoryFeatured	= 10,
	CreditErrorNotFeatured			= 11,
	CreditErrorNotBold				= 12,
	CreditErrorInternal1			= 13,
	CreditErrorInternal2			= 14,
	CreditErrorInternal3			= 15,
	CreditErrorInternal4			= 16,
	CreditErrorAllBidsArchived		= 17,
	CreditErrorNotGallery			= 18,
	CreditErrorNotFeaturedGallery	= 19,
	CreditErrorNotGiftIcon			= 20,
	CreditErrorCreditedGiftIcon		= 21,
	CreditErrorRosieIcon			= 22,
	PartialCreditNotAllowed			= 23


} CreditErrorEnum;

//
// CodeToCreditType
//
//	This structure, which we'll use in a second, maps 
//	"codes" to credit types.
//
typedef struct
{
	char					mCode[3];
	AccountDetailTypeEnum	mType;
} CodeToCreditType;

static const CodeToCreditType CodeToCreditTypes[] =
{
	{	{	'b', '\0', '\0'	},	AccountDetailCreditBold				},
	{	{	'c', '\0', '\0'	},	AccountDetailCreditCategoryFeatured	},
	{	{	'f', '\0', '\0'	},	AccountDetailCreditFeatured			},
	{	{	'i', '\0', '\0' },	AccountDetailCreditInsertion		},
	{	{	'n', '\0', '\0'	},	AccountDetailCreditNoSale			},
	{	{	'p', '\0', '\0'	},	AccountDetailCreditPartialSale		},
	{	{	'g', '\0', '\0'	},	AccountDetailCreditGallery			},
	{	{	'y', '\0', '\0'	},	AccountDetailCreditFeaturedGallery	},
	{	{	'v', '\0', '\0'	},	AccountDetailCreditFinalValue		},
	{	{	'z', '\0', '\0'	},	AccountDetailCreditGiftIcon			},
	{	{	'\0', '\0', '\0'},	AccountDetailUnknown				} // this has to be the last entry

};

//
// clsCredit2
//	Describes a credit action, and result
//
//	** NOTE **
//	I'm not sure I like the idea of hijacking the clsAccountDetail
//	"types" here, but it seemed liek the Right Thing To Do as I
//	was coding ANOTHER enum for each credit type.
//
class clsCredit2
{
	public:
		AccountDetailTypeEnum	mType;
		char					mReasonCode[3];	// must match CodeToCreditType.mCode
		int						mItemId;			
		double					mAmount;		
		CreditErrorEnum			mError;
		double					mFinalValueFee;
		double					mCreditAmount;
		int						mSellerId;
		clsItem					*mpItem;
		char					mLine[64];

		clsCredit2() :
			mType(AccountDetailUnknown),
			mItemId(0),
			mAmount(0),
			mCreditAmount(0),
			mError(CreditErrorNoError),
			mFinalValueFee(0),
			mSellerId(0),
			mpItem(NULL)
		{
			memset(mLine, '\0', sizeof(mLine));
			mReasonCode[0]	= '\0';
		};


		clsCredit2(int itemId,
				  double amount) :
			mType(AccountDetailUnknown),
			mItemId(itemId),
			mAmount(amount),
			mCreditAmount(0),
			mError(CreditErrorNoError),
			mFinalValueFee(0),
			mSellerId(0),
			mpItem(NULL)
		{
			memset(mLine, '\0', sizeof(mLine));
			mReasonCode[0]	= '\0';

		}
};

typedef vector<clsCredit2 *> CreditVector;

//
// clsUserHash
//
//	An object to represent a user, keyed by id, and with a 
//	pointer to the user object. 
//
class clsUserHash
{
	public:
		unsigned int	mId;			// Paranoia
		clsUser			*mpUser;		// Pointer to user object


		clsUserHash()
		{
			mId		= 0;
			mpUser	= NULL;
		}

		clsUserHash(unsigned int id)
		{
			mId		= id;
			mpUser	= NULL;
		}

		~clsUserHash()
		{
			delete	mpUser;
			mpUser	= NULL;
			return;
		}

};

void clseBayApp::AdminStatistics()
{
	mBatchEndTime	= time(0);

	// Statistics
	*mpStream <<	"<br>"
					"Item Fetch Time:  "
			  <<	mItemGetEndTime - mItemGetBeginTime
			  <<	" seconds"
					"<br>"
					"User Fetch Time:  "
			  <<	mUserGetEndTime - mUserGetBeginTime
			  <<	" seconds"
					"<br>"
					"Validation Time:  "
			  <<	mValidateEndTime - mValidateBeginTime
			  <<	" seconds"
					"<br>"
					"Commit Time:      "
			  <<	mCommitEndTime - mCommitBeginTime
			  <<	" seconds"
					"<br>"
					"Total Time:      "
			  <<	mBatchEndTime - mBatchBeginTime
			  <<	" seconds"
					"<br>";

	return;
}


//
//	ParseCreditBatchLine
//
//	Parse the given input line into item number and code tokens
//	(and maybe an amount token, too).
//
bool ParseCreditBatchLine(char *pLine,
						  CreditVector *pvCredits,
						  list<unsigned int> *plItemIds)
{
	bool							badLine = false;
	bool							error = false;

	clsCredit2 *					pCredit;

	char *							pCode;
	const CodeToCreditType *		pCodeToCreditType;

	char *							pAmount;
	char *							pItemNo;

	list<unsigned int>::iterator	iItemIds;



	// check for NULL pointers (mila)
	if (pLine == NULL || pvCredits == NULL || plItemIds == NULL)
		return true;

	// Make a base credit object
	pCredit	= new clsCredit2();

	// Remember line
	strncpy(pCredit->mLine, pLine, sizeof(pCredit->mLine) - 1);

	// Find code
	pCode	= strtok(pLine, "\t\n\r");
	if (!pCode)
	{
		pCredit->mError	= CreditErrorBadCode;
		badLine	= true;
		error = true;
	}
	else
	{
		for (pCodeToCreditType	= CodeToCreditTypes;
			 pCodeToCreditType->mCode[0] != '\0';
			 pCodeToCreditType++)
		{
			if (strcmp(pCode, pCodeToCreditType->mCode) == 0)
				break;
		}

		if (pCodeToCreditType->mCode[0] == '\0')
		{
			pCredit->mError	= CreditErrorBadCode;
			badLine	= true;
			error = true;
		}
	}

	if (!badLine)
	{
		pCredit->mType	= pCodeToCreditType->mType;
		strcpy(pCredit->mReasonCode, pCodeToCreditType->mCode);
	}

	// Find item #
	pItemNo	= strtok(NULL, "\t\n\r");

	// If pItem is NULL, then we didn't find a
	// tab of newline. 
	if (!pItemNo)
	{
		pCredit->mItemId = 0;
		pCredit->mError	= CreditErrorNoItem;
		badLine	= true;
		error = true;
	}
	else
		pCredit->mItemId = atoi(pItemNo);

	// Now, we could have an amount
	pAmount	= strtok(NULL, "\t\n\r");
	if (pAmount && (strlen(pAmount) != 0))
	{
		pCredit->mAmount = atof(pAmount);
	}
	else
	{
		pCredit->mAmount = 0;
	}

	// Now, if it's NOT an Partial Sale credit, then there
	// better not be an amount!
	if (pCredit->mType != AccountDetailCreditPartialSale)
	{
		if (pCredit->mAmount != 0)
		{
			pCredit->mError	= CreditErrorAmountNotAllowed;
			error = true;
		}
	}

	pvCredits->push_back(pCredit);

	// If we haven't seen it, push the item onto the list. Sigh,
	// I think I should have made this a hash. Well, considering
	// that the list is never more than 20 or so long, it seems
	// kind of silly.
	for (iItemIds = plItemIds->begin();
		 iItemIds != plItemIds->end();
		 iItemIds++)
	{
		if ((*iItemIds) == pCredit->mItemId)
			break;
	}

	if (iItemIds == plItemIds->end())
	{
		plItemIds->push_back((unsigned int)pCredit->mItemId);
	}

	return error;
}


//
//	ParseCreditBatchInput
//
//	Parse the input, creating a vector of credits and a list of item IDs.
//	The vector and list will be passed back to the calling method.
//
bool ParseCreditBatchInput(char *pText,
						   CreditVector *pvCredits,
						   list<unsigned int> *plItemIds)
{
	bool	badLine;
	bool	done = false;

	char *	pStartOfLine = pText;
	char *	pEndOfLine;
	int		lineLen;
	char	line[1024];

	bool	error = false;

	// check for NULL pointers (mila)
	if (pText == NULL || pvCredits == NULL || plItemIds == NULL)
		return true;

	do
	{
		badLine	= false;

		// Let's see if we're done
		if (*pStartOfLine == '\0')
		{
			done = true;
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
			pEndOfLine = pStartOfLine + strlen(pStartOfLine);
			done = true;
		}

		// If it's 0 length, we're done.
		lineLen	= pEndOfLine - pStartOfLine;
		if (lineLen < 1)
			break;

		// We've got a line. Prime strtok().
		memcpy(line, pStartOfLine, lineLen);
		line[lineLen] = '\0';

		// Reset Start-of-line for next line
		pStartOfLine = pEndOfLine + 1;

		ParseCreditBatchLine(line, pvCredits, plItemIds);

	} while (!done);

	return error;
}


//
//	ComputeCreditAmount
//
//	Compute the credit amount so we can share the computation
//	for final value, no sale, and partial credit refunds.
//
bool ComputeCreditAmount(clsItem *pItem, clsCredit2 *pCredit)
{
	// bids
	BidVector				vBidders;
	BidVector::iterator		iBidders;
	int						qtysold;

	// Inna price used to figure out the listing fee for an item
	double					ItemListPrice;

	bool					error = false;
	clsMarketPlace			*pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	// check for NULL pointers (mila)
	if (pItem == NULL || pCredit == NULL || pMarketPlace == NULL)
		return true;	// error!!!

	switch (pCredit->mType)
	{
		case AccountDetailCreditNoSale:
		case AccountDetailCreditPartialSale:
		case AccountDetailCreditFinalValue:

			// Compute the credit amount
			if (pItem->GetAuctionType() != AuctionDutch)
			{
				//pCredit->mCreditAmount = pItem->GetListingFee(pItem->GetPrice());
				pCredit->mCreditAmount = pItem->GetListingFee();
			}
			else
			{
				pItem->GetDutchHighBidders(&vBidders);
				
				if (vBidders.size() == 0)
				{
					//inna comment out reference to bids arc table
					//until there is index, this call will take SOOOOOOO long
					//Let uncomment and see if any problems with index
					pItem->GetDutchHighBidders(&vBidders, true);
					if (vBidders.size() == 0)
					{
						pCredit->mError	= CreditErrorAllBidsArchived;
						error			= true;
						break;
					};
				}

				qtysold = 0;
				for (iBidders = vBidders.begin();
					 iBidders != vBidders.end();
					 iBidders++)
				{
					qtysold = qtysold + (*iBidders)->mQuantity;
				}

				for (iBidders = vBidders.begin();
					 iBidders != vBidders.end();
					 iBidders++)
				{
					delete (*iBidders);
					*iBidders = NULL;
				}

				vBidders.erase(vBidders.begin(), vBidders.end());
				//12/17 inna bug fix:
				//See if the qtysold exceeds the item's quantity, and,
				// if it does, force it to the quantity. This accounts
				// for the case where the last Dutch high bidder didn't
				// get "all" of their order, but we only bill for the
				// quantity
				if (qtysold > pItem->GetQuantity())
					qtysold	= pItem->GetQuantity();

				pCredit->mCreditAmount = pItem->GetListingFee(pItem->GetPrice() * qtysold);

			}

			break;

		case AccountDetailCreditInsertion:
			// Inna figure out price for listing price to find listing fee
			if (pItem->GetQuantity() > 1)
			{
				ItemListPrice = pItem->GetQuantity() * pItem->GetStartPrice();
			}
			else
			{
				if (pItem->GetReservePrice() >= pItem->GetStartPrice())
					ItemListPrice = pItem->GetReservePrice();
				else
					ItemListPrice = pItem->GetStartPrice();
			}					

			pCredit->mCreditAmount= pItem->GetInsertionFee(ItemListPrice);
			/*pCredit->mCreditAmount	=	
				gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetInsertionFee(pItem->GetStartPrice()); inna */
			break;

		case AccountDetailCreditFeatured:
		// Lena - new featured price
			pCredit->mCreditAmount	=	
				pItem->GetFeaturedFee(pItem->GetStartTime());
			break;

		case AccountDetailCreditCategoryFeatured:
		// Lena - new category featured price
			pCredit->mCreditAmount	=	
				pItem->GetCategoryFeaturedFee(pItem->GetStartTime());
			break;

		case AccountDetailCreditBold:
			pCredit->mCreditAmount = pItem->GetBoldFee(0);
			break;

		case AccountDetailCreditGallery:
			pCredit->mCreditAmount = pItem->GetGalleryFee();
			break;

		case AccountDetailCreditFeaturedGallery:
			pCredit->mCreditAmount	= pItem->GetFeaturedGalleryFee();
			break;

		case AccountDetailCreditGiftIcon:
			pCredit->mCreditAmount	= pItem->GetGiftIconFee(pItem->GetGiftIconType());
			break;

		default:
			break;
	}

	return error;
}


//
//	ValidateItemCredits
// 
//	Let's iterate through the credit requests. We'll fill in:
//	1. Seller-Id of the item in question.
//	2. Computed Final Value Fee.
//
//	And, we'll check to see:
//	1. Is there already a credit for this item?
//
bool ValidateItemCredits(CreditVector *pvCredits,
						 ItemList *plItems,
						 time_t nowTime)
{
	// credits
	CreditVector::iterator	vI;

	// items
	ItemList::iterator		iItems;
	clsItem *				pItem;

	bool					error = false;
	bool					retError = false;

	clsMarketPlace *		pMarketPlace;
	
	pMarketPlace = gApp->GetMarketPlaces()->GetCurrentMarketPlace();

	// check for NULL pointers (mila)
	if (pvCredits == NULL || plItems == NULL || pMarketPlace == NULL)
		return true;

	for (vI = pvCredits->begin();
		 vI != pvCredits->end();
		 vI++)
	{
		// If this credit's already got an error, let's skip it
		if ((*vI)->mError != CreditErrorNoError)
			continue;

		// Make ready
		pItem = NULL;

		// Let's look up the item. We have to loop through the items 
		// since there's no hash associated with them, but this isn't 
		// a big deal. We could always do a hash later if we wanted.
		for (iItems = plItems->begin();
			 iItems != plItems->end();
			 iItems++)
		{
			if (iItems != NULL && (*iItems).mpItem != NULL)	// check for NULL first (mila)
			{
				if ((*iItems).mpItem->GetId() == (*vI)->mItemId)
					break;
			}
			else
			{
				(*vI)->mError	= CreditErrorInternal1;
				error = true;
			}
		}

		if (iItems == plItems->end())
		{
			(*vI)->mError	= CreditErrorInternal1;
			error			= true;
			continue;
		}

		// Shorthand pointer
		pItem				= (*iItems).mpItem;

		// Fill the seller-id into the credit object
		(*vI)->mSellerId	= pItem->GetSeller();

		// And the Item pointer
		(*vI)->mpItem		= pItem;

		// Have we already got this sort of Credit?
		switch ((*vI)->mType)
		{
			case AccountDetailCreditNoSale:
			case AccountDetailCreditPartialSale:
			case AccountDetailCreditFinalValue:
				//
				// Obviously, this doesn't make any sense if the
				// auction's not over.
				//
				if (pItem->GetEndTime() > nowTime)
				{
					(*vI)->mError	= CreditErrorAuctionNotOver;
					error			= true;
					break;
				}

				//
				// Or, if the final price was 0 or there were no 
				// bids

				// inna - or if there was reserve price and reserve was not met
				// also can NOT give no sale credit

				if (pItem->GetPrice() == 0 ||
					pItem->GetBidCount() == 0 ||
					pItem->GetPrice() < pItem->GetReservePrice())
				{
					(*vI)->mError	= CreditErrorNoFinalFeeForItem;
					error			= true;
					break;
				}

				if (pItem->HasNoSaleCredit() ||
					pItem->HasFVFCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					break;
				}

				//do not allow credits for partial sale
				//for special pricing categories
				if ((*vI)->mType == AccountDetailCreditPartialSale) 
				{
					if (pItem->CheckForAutomotiveListing() 
							|| pItem->CheckForRealEstateListing())
					{
						(*vI)->mError	= PartialCreditNotAllowed;
						error			= true;
						break;
					}
				}


				//
				// *** NOTE ***
				// When we fix BillingNotice to set the "billed" flag in
				// the item, we can check for that, too
				//
				break;


			case AccountDetailCreditInsertion:
				if (pItem->HasInsertionCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditCategoryFeatured:
				if (pItem->HasCategoryFeaturedCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}

				if (!pItem->GetFeatured())
				{
					(*vI)->mError	= CreditErrorNotCategoryFeatured;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditFeatured:
				if (pItem->HasFeaturedCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}
				if (!pItem->GetSuperFeatured())
				{
					(*vI)->mError	= CreditErrorNotFeatured;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditBold:
				if (pItem->HasBoldCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}

				if (!pItem->GetBoldTitle())
				{
					(*vI)->mError	= CreditErrorNotBold;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditGiftIcon:
				if (pItem->HasGiftIconCredit())
				{
					(*vI)->mError	= CreditErrorCreditedGiftIcon;
					error			= true;
					continue;
				}

				if (pItem->GetGiftIconType() <= 0)
				{
					(*vI)->mError	= CreditErrorNotGiftIcon;
					error			= true;
					continue;
				}
				if (pItem->GetGiftIconType() == 2)
				{
					(*vI)->mError	= CreditErrorRosieIcon;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditGallery:
				if (pItem->HasGalleryCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}

				if (pItem->GetGalleryType() == NoneGallery)
				{
					(*vI)->mError	= CreditErrorNotGallery;
					error			= true;
					continue;
				}
				break;

			case AccountDetailCreditFeaturedGallery:
				if (pItem->HasFeaturedGalleryCredit())
				{
					(*vI)->mError	= CreditErrorCreditExistsForItem;
					error			= true;
					continue;
				}

				if (pItem->GetGalleryType() != FeaturedGallery)
				{
					(*vI)->mError	= CreditErrorNotFeaturedGallery;
					error			= true;
					continue;
				}
				break;

			default:
				(*vI)->mError	= CreditErrorInternal2;
				error			= true;
				continue;
				break;
		}

		//
		// Now, compute the credit amount. We do this here so we can share
		// the computation for final value, no sale, and partial credit refunds
		//
		retError = ComputeCreditAmount(pItem, (*vI));
		error = error || retError;
	}

	return error;
}


//
//	EmitCreditErrorMessage
//
//	Emit an error meesage based on the error type in the clsCredit2 object.
//
void EmitCreditErrorMessage(clsCredit2 *pCredit, ostream *pStream)
{
	// check for NULL pointers (mila)
	if (pCredit == NULL || pStream == NULL)
		return;

	switch (pCredit->mError)
	{
		case CreditErrorNoItem:
			*pStream <<	"No Item Number!";
			break;
		case CreditErrorBadItem:
			*pStream <<	"Item not found!";
			break;
		case CreditErrorNoChargeForItem:
			*pStream <<	"No charges found for item!";
			break;
		case CreditErrorCreditExistsForItem:
			*pStream <<	"Credit already exists for item!";
			break;
		case PartialCreditNotAllowed:
			*pStream <<	"Partial Credits are not allowed for fixed priced categories";
			break;
		case CreditErrorNoFinalFeeForItem:
			*pStream <<	"No Final Value fee charged for item!";
			break;
		case CreditErrorAuctionNotOver:
			*pStream <<	"Auction is not over yet!";
			break;
		case CreditErrorBadCode:
			*pStream <<	"Code not recognized!";
			break;
		case CreditErrorAmountNotAllowed:
			*pStream <<	"Amount only allowed for partial sale credits!";
			break;
		case CreditErrorNotCategoryFeatured:
			*pStream <<	"NOT a category featured item!";
			break;
		case CreditErrorNotFeatured:
			*pStream <<	"NOT a featured item!";
			break;
		case CreditErrorNotBold:
			*pStream <<	"NOT a bold item!!";
			break;
		case CreditErrorInternal1:
			*pStream <<	"<i>Internal Error 1</i>: Missing Item!";
			break;
		case CreditErrorInternal2:
			*pStream <<	"<i>Internal Error 2</i>: Bad Type!";
			break;
		case CreditErrorInternal3:
			*pStream <<	"<i>Internal Error 3</i>: No Account!";
			break;
		case CreditErrorInternal4:
			*pStream <<	"<i>Internal Error 4</i>: Bad Type!";
			break;
		//inna add better explanation for old items error
		case CreditErrorAllBidsArchived:
			*pStream <<	"Item too old! Can not find any bids!";
			break;
		case CreditErrorNotGallery:
			*pStream <<	"NOT a gallery or featured gallery item!!";
			break;
		case CreditErrorNotFeaturedGallery:
			*pStream <<	"NOT a featured gallery item!!";
			break;
		case CreditErrorNotGiftIcon:
			*pStream <<	"NOT a gift icon item!!";
			break;
		case CreditErrorCreditedGiftIcon:
			*pStream <<	"Gift icon credit already exists for item";
			break;
		case CreditErrorRosieIcon:
			*pStream <<	"Rosie Icon, NOT a gift icon item!!";
			break;
		default:
			*pStream <<	"*Unknown Error*";
			break;
	}
}

			
//
//	AddAccountDetailRecord
//
//	Create a new account detail record, fill it with data, and add
//	it to the database.  Return the transaction sequence number for
//	the added row in the database table.
TransactionId AddAccountDetailRecord(clsAccount *pAccount,
									 clsCredit2 *pCredit,
									 clsItem *pItem)
{
	TransactionId		transactionId;
	clsAccountDetail	*pAccountDetail;

	// check for NULL pointers (mila)
	if (pAccount == NULL || pCredit == NULL || pItem == NULL)
		return 0;

	// Create an empty account detail
	pAccountDetail = new clsAccountDetail;

	// Fill it in with what we know
	pAccountDetail->mTime			= time(0);
	pAccountDetail->mType			= pCredit->mType;
	pAccountDetail->mpMemo			= NULL;
	pAccountDetail->mAmount			= pCredit->mCreditAmount;
	pAccountDetail->mItemId			= pCredit->mItemId;

	//
	// Let's add the raw account detail and the transaction xref
	//
	pAccount->AddRawAccountDetail(pAccountDetail);
	gApp->GetDatabase()->AddAccountItemXref(
									pAccountDetail->mTransactionId,
									pCredit->mItemId);
	transactionId = pAccountDetail->mTransactionId;

	delete pAccountDetail;
	pAccountDetail = NULL;

	return transactionId;
}


//
//	SetItemCreditFlag
//
//	Set the appropriate credit flag in the given item.
//
void SetItemCreditFlag(clsCredit2 *pCredit, clsItem *pItem)
{
	// check for NULL pointers (mila)
	if (pCredit == NULL || pItem == NULL)
		return;

	switch (pCredit->mType)
	{
		case AccountDetailCreditNoSale:
			pItem->SetHasFVFCredit(true);
			break;

		case AccountDetailCreditPartialSale:
		//inna
		case AccountDetailCreditFinalValue:
			pItem->SetHasFVFCredit(true);
			break;
		
		case AccountDetailCreditInsertion:
			pItem->SetHasInsertionCredit(true);
			break;

		case AccountDetailCreditFeatured:
			pItem->SetHasFeaturedCredit(true);
			break;

		case AccountDetailCreditCategoryFeatured:
			pItem->SetHasCategoryFeaturedCredit(true);
			break;

		case AccountDetailCreditBold:
			pItem->SetHasBoldCredit(true);
			break;

		case AccountDetailCreditGallery:
			pItem->SetHasGalleryCredit(true);
			break;
		case AccountDetailCreditFeaturedGallery:
			pItem->SetHasFeaturedGalleryCredit(true);
			break;
		case AccountDetailCreditGiftIcon:
			pItem->SetHasGiftIconCredit(true);
			break;

		default:
			pCredit->mError = CreditErrorInternal4;
			break;
	}
}


//
// Cleanup routine
//
// Now, I didn't want to put the things needed by this in the 
// app object, since we'd have hashes, and lists, and who knows
// what for Everyone. And I didn't want to subclass the app, since
// that would have made things a bit messier. So I did this.
//

void CreditBatchCleanUp(CreditVector *pvCredits,
						list<unsigned int> *plItemIds,
						list<unsigned int> *plMissingItemIds,
						ItemList *plItems,
						hash_map<const int, clsUserHash *, hash<int>, eqint> *phUsers,
						list<unsigned int> *plUserIds,
						UserList *plUsers)
{
	CreditVector::iterator			vI;
	ItemList::iterator				iItems;
	hash_map<const int, clsUserHash *, hash<int>, eqint>::
		const_iterator				ihUsers;
	UserList::iterator				iUsers;

	// Do the simple lists first. Since the data is contained in the list(s),
	// we just need to do an erase.
	if (plItemIds != NULL)	// check for NULL first (mila)
		plItemIds->erase(plItemIds->begin(), plItemIds->end());

	if (plMissingItemIds != NULL)	// check for NULL first (mila)
		plMissingItemIds->erase(plMissingItemIds->begin(), plMissingItemIds->end());

	if (plUserIds != NULL)	// check for NULL first (mila)
		plUserIds->erase(plUserIds->begin(), plUserIds->end());

	// Now, the Items
	if (plItems != NULL)	// check for NULL first (mila)
	{
		for (iItems = plItems->begin();
			 iItems != plItems->end();
			 iItems++)
		{
			delete (*iItems).mpItem;
		}

		plItems->erase(plItems->begin(), plItems->end());
	}

	// Users
	if (plUsers != NULL)	// check for NULL first (mila)
	{
		for (iUsers = plUsers->begin();
			 iUsers != plUsers->end();
			 iUsers++)
		{
			delete (*iUsers).mpUser;
		}

		plUsers->erase(plUsers->begin(), plUsers->end());
	}

	// And, the hash
	if (phUsers != NULL)	// check for NULL first (mila)
		phUsers->erase(phUsers->begin(), phUsers->end());

	// Finally, the credits
	if (pvCredits != NULL)	// check for NULL first (mila)
	{
		for (vI = pvCredits->begin();
			 vI != pvCredits->end();
			 vI++)
		{
			delete (*vI);
		}

		pvCredits->erase(pvCredits->begin(), pvCredits->end());
	}

	return;
}


//
//	CreditBatch2
//
//	Parse batch credit input and apply credits as applicable.
//	Check to make sure that credit has not been applied to
//	item already.
void clseBayApp::CreditBatch2(CEBayISAPIExtension* pCtxt,
							  char *pText,
							  int how,
							  char *pPassword,
							  eBayISAPIAuthEnum authLevel)
{
	// The vector of requests
	CreditVector					vCredits;
	CreditVector::iterator			vI;

	// The list of item #'s we want
	list<unsigned int>				lItemIds;
	list<unsigned int>::iterator	iItemIds;

	list<unsigned int>				lMissingItemIds;
	list<unsigned int>::iterator	iMissingItemIds;

	// And the list of items we get
	ItemList						lItems;
	ItemList::iterator				iItems;

	// Now, a hash of the users we're gonna need. We 
	// use a hash so we can quickly see if we've got them
	hash_map<const int, clsUserHash *, hash<int>, eqint>
		hUsers;

	// hasherator
	hash_map<const int, clsUserHash *, hash<int>, eqint>::
		const_iterator			ihUsers;

	// A user hash object
	clsUserHash					*pUserHash;

	// Lists of users we need
	list<unsigned int>				lUserIds;
	list<unsigned int>::iterator	iUserIds;

	UserList						lUsers;
	UserList::iterator				iUsers;

	// Other things
	time_t						nowTime;
	clsItem						*pItem;
	clsUser						*pUser;
	clsAccount					*pAccount;

	float						balanceAdjustment;

	bool						error = false;
	bool						retError = false;

	double						theFee;

	struct tm					*pNowTimeAsTm;
	char						cNowTime[64];

	bool						colorSwitch;
	const char					*pColor;

	float						batchTotal = 0;

	clsDeadbeat					*pSellerDeadbeat;
	clsDeadbeat					*pBidderDeadbeat;
	clsDeadbeatItem				*pDeadbeatItem;

	TransactionId				transactionId;
	CreditsVector				vBidderVector;
	vector<sItemCredits *>::iterator iBidder;
	char						reason_code[3];

	// Timing
	mBatchBeginTime		= 0;
	mBatchEndTime		= 0;
	mValidateBeginTime	= 0;
	mValidateEndTime	= 0;
	mItemGetBeginTime	= 0;
	mItemGetEndTime		= 0;
	mUserGetBeginTime	= 0;
	mUserGetEndTime		= 0;
	mCommitBeginTime	= 0;
	mCommitEndTime		= 0;

	mBatchBeginTime	= mValidateBeginTime = time(0);

	// Common Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

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

	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	//
	// Parse the text input to get a vector of credit objects 
	// and a list of item IDs.
	//
	ParseCreditBatchInput(pText, &vCredits, &lItemIds);

	//
	// Ok, let's try and get the nice items.
	//
	mItemGetBeginTime	= time(0);
	mpItems->GetManyItemsForCreditBatch(&lItemIds,
										&lItems,
										&lMissingItemIds);
	mItemGetEndTime		= time(0);

	//
	// If any items went missing, let's mark the credit
	// objects accordingly
	//
	if (!lMissingItemIds.empty())
	{
		// EVIL! EVIL! Missing items! Now, we have to go
		// through ALL the credits each time, since more
		// than one credit can reference the same item!
		for (iMissingItemIds = lMissingItemIds.begin();
			 iMissingItemIds != lMissingItemIds.end();
			 iMissingItemIds++)
		{
			// Find the victims in the list
			for (vI = vCredits.begin();
				 vI != vCredits.end();
				 vI++)
			{
				if ((*vI)->mItemId == (*iMissingItemIds))
				{
					(*vI)->mError = CreditErrorBadItem;
				}
			}
		}

		// Not just yet, but the jig is up!
		error	= true;
	}

	//
	// Ok, now we go thru and figure out which users we care about
	// by examining the seller ids in the items. We need this for
	// the syntax check so we can get the userid/email for the 
	// display
	//
	for (iItems = lItems.begin();
		 iItems != lItems.end();
		 iItems++)
	{
		// check for NULL pointer (mila)
		if ((*iItems).mpItem == NULL)
			continue;

		// Let's see if we've seen them
		ihUsers	= hUsers.find((*iItems).mpItem->GetSeller());

		// If we haven't got it, add it
		if (ihUsers == hUsers.end())
		{
			pUserHash = new clsUserHash((*iItems).mpItem->GetSeller());

			hUsers[(*iItems).mpItem->GetSeller()] = pUserHash;
		}
	}
	//
	// Push the users into a list. There's probably some other interesting
	// way to do this.
	//
	for (ihUsers = hUsers.begin();
		 ihUsers != hUsers.end();
		 ihUsers++)
	{
		lUserIds.push_back((*ihUsers).first);
	}

	// Let's get those users. Now, deep down inside, database
	// constraints keep us from having items with sellers that
	// don't exist, so we don't care about missing users.
	mUserGetBeginTime	= time(0);
	mpUsers->GetManyUsersForCreditBatch(&lUserIds,
									    &lUsers);
	mUserGetEndTime		= time(0);

	//
	// Now, do a few things with each user we got:
	//	a. Plug the clsUser object's pointer into the clsUserHash
	//	   entry for the user.
	//
	for (iUsers = lUsers.begin();
		 iUsers != lUsers.end();
		 iUsers++)
	{
		ihUsers	= hUsers.find((*iUsers).mpUser->GetId());

		if (ihUsers == hUsers.end())
		{
			// What!
			continue;
		}

		(*ihUsers).second->mpUser = (*iUsers).mpUser;
	}

	// 
	// WELL, now we've got all the goodies we need to go forward. Let's
	// iterate through the credit requests. We'll fill in:
	//	1. Seller-Id of the item in question.
	//	2. Computed Final Value Fee.
	//
	// And, we'll check to see:
	//	1. Is there already a credit for this item?
	//	
	nowTime	= time(0);

	retError = ValidateItemCredits(&vCredits, &lItems, nowTime);
	error = error || retError;

	mValidateEndTime	= time(0);

	//
	// Now, let's review what we saw
	//
	if (error)
	{
		*mpStream <<	"<h2>Errors in input are in <font color=red>red</font></h2>"
						"<br>"
						"\n";
	}
	else
	{
		*mpStream <<	"<h2>No Errors found. Transactions follow:</h2>"
						"<br>"
						"\n";
	}

	//
	// A Nice Header
	//
	*mpStream <<	"<table border=1 width=100% cellspacing=0 BGCOLOR=#009900>"
					"<tr>"
					"<th width=20% align=center>Input</th>"
					"<th width=10% align=center>Item</th>"
					"<th width=20% align=center>Email</th>"
					"<th width=10% align=center>Credit Amount</th>"
					"<th width=39% align=center>Comment or Error</th>"
					"</tr>"
					"</table>";

	colorSwitch	= true;

	pUser		= NULL;

	for (vI = vCredits.begin();
		 vI != vCredits.end();
		 vI++)
	{
		// Table adornments
		if (colorSwitch)
		{
			pColor		= "#FFFFCC";
			colorSwitch	= false;
		}
		else
		{
			pColor		= "#FFFFFF";
			colorSwitch	= true;
		}

		*mpStream <<	"<table width=100% border=1 cellspacing=0 bgcolor="
				  <<	pColor
				  <<	">"
				  <<	"<tr>";

		// Input line
		*mpStream <<	"<td width=20% align=left>"
				  <<	(*vI)->mLine
				  <<	"</td>";

		// Item
		*mpStream <<	"<td width=10% align=left>"
				  <<	(*vI)->mItemId
				  <<	"</td>";

		// Seller's email, maybe
		*mpStream <<	"<td width=20% align=left>";

		if ((*vI)->mSellerId != 0)
		{
			ihUsers	= hUsers.find((*vI)->mSellerId);

			if (ihUsers != hUsers.end())
			{
				pUser	= (*ihUsers).second->mpUser;
			}
			else
			{
				pUser	= NULL;
			}
			
			if (pUser)
			{
				*mpStream <<	"<A HREF=\""
						  <<	mpMarketPlace->GetCGIPath(PageViewAccount)
						  <<	"ebayISAPI.dll?ViewAccount"
						  <<	"&userid="
						  <<	pUser->GetEmail()
						  <<	"&pass="
						  <<	mpMarketPlace->GetSpecialPassword()
						  <<	"&sinceLastInvoice=0"
								"&daysback=60"
								"\">"
						  <<	pUser->GetEmail()
						  <<	"</A>";
			}
			else
			{
				*mpStream <<	"&nbsp;";
			}
		}

		*mpStream <<	"</td>";

		*mpStream <<	"<td width=10% align=left>"
				  <<	(*vI)->mCreditAmount
				  <<	"</td>";

		*mpStream <<	"<td width=39% align=left>";

		if ((*vI)->mError == CreditErrorNoError)
		{
			*mpStream <<	"<font color=green>"
					  <<	clsAccount::GetAccountDetailDescriptor((*vI)->mType)
					  <<	"</font>";

			batchTotal	+=	(*vI)->mCreditAmount;
		}
		else
		{
			*mpStream <<	"<font color=red>";

			EmitCreditErrorMessage((*vI), mpStream);

			*mpStream <<	"</font>";
		}

		*mpStream <<	"</td>";

		*mpStream <<	"</tr>"
						"</table>"
						"\n";
	}

	// 
	// Print the batch total, such as it is (could be incomplete
	// if there are errors
	//
	*mpStream <<	"<br><br>"
					"<b>Batch Total:</b> "
			  <<	batchTotal
			  <<	"<br><br>";

	// 
	// If we had errors, let's clean up and return
	//
	if (error)
	{
		*mpStream <<	"<b>Errors</b> in input. No action performed"
				  <<	"<br>";

		AdminStatistics();
				  
		*mpStream <<	mpMarketPlace->GetFooter();

		CreditBatchCleanUp(&vCredits,
						   &lItemIds,
						   &lMissingItemIds,
						   &lItems,
						   &hUsers,
						   &lUserIds,
						   &lUsers);

		// We have to call CleanUp() as well!! (mila 2/28/99)
		CleanUp();
		return;
	}

	// 
	// If they're not committing, we're done.
	if (!how)
	{
		AdminStatistics();
		*mpStream <<	mpMarketPlace->GetFooter();

		// We have to call both CreditBatchCleanUp() and CleanUp()!! (mila 2/28/99)
		CreditBatchCleanUp(&vCredits,
						   &lItemIds,
						   &lMissingItemIds,
						   &lItems,
						   &hUsers,
						   &lUserIds,
						   &lUsers);
		CleanUp();
		return;
	}

	// Looks like they want to commit. Check password.
	if (pPassword == NULL || strcmp(pPassword, mpMarketPlace->GetAdminSpecialPassword()) != 0)
	{
		*mpStream <<	"<b>Password invalid</b>. No changes made</b>"
						"<br>";
		AdminStatistics();

		*mpStream <<	mpMarketPlace->GetFooter();

		CreditBatchCleanUp(&vCredits,
						   &lItemIds,
						   &lMissingItemIds,
						   &lItems,
						   &hUsers,
						   &lUserIds,
						   &lUsers);

		// We have to call CleanUp() as well!! (mila 2/28/99)
		CleanUp();

		return;
	}

	//
	// All righty! Let's do it!
	//
	// Now, to make this fast, we're going to do it by USER, since we'll
	// need the user's account object to add the refunds. So, we traverse
	// the list of user objects, and then find their credits in the list.
	//
 	mCommitBeginTime	= time(0);

	*mpStream <<	"<br>"
					"<font color=green><b>"
					"No errors"
					"</b></font>"
					" found. Committing...";

	for (iUsers = lUsers.begin();
		 iUsers != lUsers.end();
		 iUsers++)
	{
		// Shorthand
		pUser = (*iUsers).mpUser;
		if (pUser == NULL)
		{
			(*vI)->mError	= CreditErrorInternal1;
			error			= true;
			continue;
		}

		pAccount = NULL;

		//
		// To make it fast, we accumulate all the balance adjustments
		// for the user, and adjust it once, instead of for each credit.
		// This could be inaccurate if we die in the middle of applying
		// the user's updates, but it's a _memo_ balance.
		//
		balanceAdjustment	= 0;

		//
		// Now, traverse the credits for credits for this user.
		//
		for (vI = vCredits.begin();
			 vI != vCredits.end();
			 vI++)
		{
			// If this isn't for the current user, then skip it.
			if ((*vI)->mSellerId != pUser->GetId())
				continue;

			// See if we've got their account object
			if (pAccount == NULL)
			{
				pAccount	= pUser->GetAccount();

				if (pAccount == NULL)
				{
					(*vI)->mError	= CreditErrorInternal3;
					error			= true;
					continue;
				}
			}

			// 
			// We'll be a needing the item pointer
			//
			pItem = (*vI)->mpItem;
			if (pItem == NULL)
			{
				(*vI)->mError	= CreditErrorInternal1;
				error			= true;
				continue;
			}

			//
			// Do the right kind of accounting here
			//

			transactionId = AddAccountDetailRecord(pAccount, (*vI), pItem);
			if (transactionId == 0)
			{
				(*vI)->mError	= CreditErrorInternal2;
				error			= true;
				continue;
			}

			// Accumulate balance adjustment
			balanceAdjustment		+= (*vI)->mCreditAmount;

			//
			// Now, if this was a partial credit, we need to re-charge
			// the user for the goods they DID sell. In this case, 
			// the amount of the final sale is included in the credit
			// object as mAmount.
			//
			//	NOTE that ChargePartialSaleFee will change the sign of
			//	the fee from +ve to -ve (as it should be), so we don't
			//	worry about that here.
			//
			if ((*vI)->mType == AccountDetailCreditPartialSale)
			{
				// XXX why not just call mpMarketPlace->GetListingFee((*vI)->mAmount)???
				// (mila 2/28/99)
				//inna -add 1000 condition

				/*
				if ((*vI)->mAmount >= 1000)
				{
					theFee = (25 * 0.05) + (975 * 0.025)+(((*vI)->mAmount - 1000) * 0.0125);
				}
				else if ((*vI)->mAmount >= 25)
				{
					theFee = (25 * 0.05) + (((*vI)->mAmount - 25) * 0.025);
				}
				else
				{
					theFee = (*vI)->mAmount * 0.05;
				}
				*/
				theFee = pItem->GetListingFee((*vI)->mAmount); // Takes exchange rates into account

				pAccount->ChargePartialSaleFee(pItem, theFee);

				// Adjust the balance adjustment, here we have to pay attention
				// to the sign.
				//inna balance gets decremented in the ChargePartialSaleFee above.
				//balanceAdjustment			-= theFee;			
			}	

			// Add a record to the deadbeat items table, if applicable.
			if ((*vI)->mType == AccountDetailCreditNoSale ||
				(*vI)->mType == AccountDetailCreditPartialSale)
			{
				// XXX
				// This next part will need to be modified for auto-credits
				// to handle Dutch auctions as well.  For Dutch auctions,
				// the same will apply, but pDeadbeatItem->SetHighBidder()
				// and pDeadbeatItem->SetQuantity() will need to be called
				// before the call to AddDeadbeatItem().
				if (pItem->GetAuctionType() == AuctionChinese)
				{
					if ((*vI)->mSellerId == pItem->GetSeller() &&
						(*vI)->mItemId == pItem->GetId())
					{
						// Get the credit list for this item and process
						// Only one item returned in vector
  						pItem->GetAllItemCredits(pItem->GetId(), &vBidderVector);
						iBidder = vBidderVector.begin();
						if (iBidder != vBidderVector.end() && pItem->isDeadbeatCreditReq((*iBidder)->reason_code))
						{
							// Get a copy of the item that has all the info.
							pDeadbeatItem = new clsDeadbeatItem(pItem);
							if (pDeadbeatItem != NULL)
							{
								if (!pDeadbeatItem->IsDeadbeat())
								{
									// pDeadbeatItem->SetReasonCode((*vI)->mReasonCode);
									// More accurate reason is provided by seller
									sprintf(reason_code, "%d", (*iBidder)->reason_code);
									pDeadbeatItem->SetReasonCode(reason_code);
									pDeadbeatItem->SetTransactionId(transactionId);
									pSellerDeadbeat = pUser->GetDeadbeat();
									if (pSellerDeadbeat != NULL)
									{
										pBidderDeadbeat = new clsDeadbeat((*iBidder)->bidder_id);

										// send deadbeat user email notice
										if (SendDeadbeatEmail(pBidderDeadbeat->GetId(),
															  pItem->GetId(),
															  pItem->GetTitle(),
															  (char *)mpMarketPlace->GetSpecialPassword(),
															  authLevel))
										{
											// Email was sent to deadbeat so set Notified flag
											pDeadbeatItem->SetNotified(true);
											pBidderDeadbeat->InvalidateWarningCount();
										} 

										pSellerDeadbeat->AddDeadbeatItem(pDeadbeatItem);
										pSellerDeadbeat->InvalidateCreditRequestCount();
										pBidderDeadbeat->InvalidateDeadbeatScore();

										delete pBidderDeadbeat;
										// don't delete the seller's clsDeadbeat object 'cuz
										// the clsUser destructor will destroy it!!!
									}
								}
								delete pDeadbeatItem;
								pDeadbeatItem = NULL;
							}
						}
						// Clean up
						if (iBidder != vBidderVector.end())
							delete	(*iBidder);
						vBidderVector.erase(vBidderVector.begin(), vBidderVector.end());
					}
				}
				else if (pItem->GetAuctionType() == AuctionDutch)
				{
					if ((*vI)->mSellerId == pItem->GetSeller() &&
						(*vI)->mItemId == pItem->GetId())
					{
						// Get the credit list for this item and process
						pItem->GetAllItemCredits(pItem->GetId(), &vBidderVector);
						for (iBidder = vBidderVector.begin(); iBidder != vBidderVector.end(); iBidder++)
						{
							// Get a copy of the item that has all the info.
							if (pItem->isDeadbeatCreditReq((*iBidder)->reason_code))
							{
								pDeadbeatItem = new clsDeadbeatItem(pItem);
								if (pDeadbeatItem != NULL)
								{
									// We must set the bidder id before we can ask if it's
									// already recorded as a deadbeat transaction!!
									pDeadbeatItem->SetBidder((*iBidder)->bidder_id);
									if (!pDeadbeatItem->IsDeadbeat())
									{
										sprintf(reason_code, "%d", (*iBidder)->reason_code);
										pDeadbeatItem->SetReasonCode(reason_code);
										pDeadbeatItem->SetTransactionId(transactionId);
										pDeadbeatItem->SetQuantity((*iBidder)->quantity);
										pSellerDeadbeat = pUser->GetDeadbeat();
										if (pSellerDeadbeat != NULL)
										{
											pBidderDeadbeat = new clsDeadbeat((*iBidder)->bidder_id);

											// send deadbeat user email notice
											if (SendDeadbeatEmail(pBidderDeadbeat->GetId(),
																  pItem->GetId(),
																  pItem->GetTitle(),
																  (char *)mpMarketPlace->GetSpecialPassword(),
																  authLevel))
											{
												// Email was sent to deadbeat so set Notified flag
												pDeadbeatItem->SetNotified(true);
												pBidderDeadbeat->InvalidateWarningCount();
											} 

											pSellerDeadbeat->AddDeadbeatItem(pDeadbeatItem);
											pSellerDeadbeat->InvalidateCreditRequestCount();
											pBidderDeadbeat->InvalidateDeadbeatScore();

											delete pBidderDeadbeat;
											// don't delete the seller's clsDeadbeat object 'cuz
											// the clsUser destructor will destroy it!!!
										}
									}
									delete pDeadbeatItem;
									pDeadbeatItem = NULL;
								}
							}
						}
						// Cleanup
						for (iBidder = vBidderVector.begin(); 
							 iBidder != vBidderVector.end(); iBidder++)
						{
							if(*iBidder !=NULL)
								delete	(*iBidder);
						}
						vBidderVector.erase(vBidderVector.begin(), vBidderVector.end());
					}
				}
			}

			//
			// Finally, indicate we've got a credit.
			//
			SetItemCreditFlag((*vI), pItem);
		}

		//
		// Now, adjust the user's balance, once.
		//
		pAccount->AdjustBalance(balanceAdjustment);

		// All done with this user, we don't need their account any more
		if (pAccount != NULL)
		{
			delete pAccount;
			pAccount = NULL;
		}
	}

	mCommitEndTime	= time(0);

	// Timestamp
	nowTime			= time(0);
	pNowTimeAsTm	= localtime(&nowTime);
	
	if (pNowTimeAsTm->tm_isdst)
	{
		strftime(cNowTime, sizeof(cNowTime),
				 "%m/%d %H:%M:%S PST",
				 pNowTimeAsTm);
	}
	else
	{
		strftime(cNowTime, sizeof(cNowTime),
				 "%m/%d %H:%M:%S PDT",
				 pNowTimeAsTm);
	}

	*mpStream <<	"<br>"
					"Batch Complete at "
			  <<	cNowTime
			  <<	"<br>";

	AdminStatistics();

	// Clean

	*mpStream <<	"<br>"
			  <<	mpMarketPlace->GetFooter();

	CreditBatchCleanUp(&vCredits,
					   &lItemIds,
					   &lMissingItemIds,
					   &lItems,
					   &hUsers,
					   &lUserIds,
					   &lUsers);

	// We have to call CleanUp() as well!! (mila 2/28/99)
	CleanUp();

	return;
}


//
//	CreditDump
//

void clseBayApp::CreditDump(CEBayISAPIExtension* pCtxt,
							char *pUserId,
							char *pPass)
{
	int					accessLevel = 0;
	struct tm			*pFileDate;
	time_t				fileDate;
	char				buf[256];
	char				filebuf[40];
	bool				bImportOpen=true;
	int					Year;
	short				batch_id=0;
	char				lpFilePath[128];
	FILE				*fp=NULL;
	CreditsVector		vCredits;
	vector<sItemCredits *>::iterator iCredits;
	clsItem				*pItem=NULL;
	float				amt=0.0;
	int					quantity=0;
	BidVector			*pvBids;
	vector<clsBid *>::iterator iBids;
	bool				isArc=false;
	clsMail				*pMail=NULL;
	ostream				*pMStream=NULL;


	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);


	// Heading, etc
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<title>"
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Billing Credit Dump Function"
					"</title>"
					"</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	if (pUserId == NULL || pPass == NULL)
	{
		*mpStream <<    "Bad user ID or password parameter.";
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) == 0)
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												true);			// Admin Query
	}
	else
	{
		mpUser = 
			mpUsers->GetAndCheckUserAndPassword(pUserId,		// Duh
												pPass,			// Duh
												mpStream,		// Duh
												true,			// Header sent alredy
												NULL,			// NO action
												false,			// Ghosts ok?
												false,			// Feedback needed?
												false,			// Account needed?
												true,			// Test Crypted?
												false);			// Admin Query
	}

	// If we didn't get the user, we're done
	if (mpUser==NULL)
	{   
		*mpStream	<<	"<br>"
					<<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}

	// check if the user gave his password or super-super-extra password
	accessLevel = mpUser->GetAccessLevel(pPass);
	if ( accessLevel < 2 )
	{   
		*mpStream <<    "Not a valid user or password.";
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Let's see if the user can administer billing
	if (!mpUser->HasAdmin(Billing))
	{
		*mpStream <<	"<H2>Auto Credit Error!</H2>"
						"You do not have Billing Administration privileges."
						"<p>\n"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Get Credits to be processed
	batch_id = mpItems->GetNextCreditBatchId();
	if (batch_id <= 0)
	{
		*mpStream << "<H2>Auto Credit Error!</H2>"
				  << "There are currently no new credit entries that are to be processed.<br>"
				  << "Please attempt this operation later.<br>"
				  << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Done with checks, create notepad file
	// Initialize buffers
	memset(lpFilePath, '\0', sizeof(lpFilePath));
	memset(buf, '\0', sizeof(buf));
	memset(filebuf, '\0', sizeof(filebuf));


	// Collect current date/time and Normalize expiration date
	fileDate	= time(0);
	pFileDate	= localtime(&fileDate);
	Year = pFileDate->tm_year;
	if (Year >=100)
		Year = Year - 100; // Year is 2000 or higher, normalize to 00, 01..
	sprintf(filebuf, "%02d%02d%02d_autocredit_%d.txt", 
					 pFileDate->tm_mon+1, pFileDate->tm_mday, Year,
					 batch_id);

	sprintf(lpFilePath, "%s%s", AUTOCREDITSDIR, filebuf);

	// Open the file for write, previous contents destroyed
	// First check if the file already exists
	if ((fp = fopen(lpFilePath, "r")) != NULL)
	{
		sprintf(buf, "File: \"%s\" already exists on disk!\n", lpFilePath);
		*mpStream << "<H2>Auto Credit Error!</H2>"
				  << buf
				  << "<p>Please take necessary action and try operation again.<br>" 
				  << mpMarketPlace->GetFooter();

		fclose(fp);
		CleanUp();
		return;
	}
	if ((fp = fopen(lpFilePath, "w")) == NULL)
	{
		// File Creation error
		sprintf(buf, "An error occured while trying to create file: %s\n", lpFilePath);
		*mpStream << "<H2>Auto Credit Error!</H2>"
				  << buf
				  << "<p>Please try this operation again or contact Engineering.<br>" 
				  << mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Get all entries
	mpItems->GetAllUnProcessedCredits(batch_id, &vCredits);

	// Check the size before proceeding
	if (vCredits.size() == 0)
	{
		*mpStream << "<H2>Auto Credit Error!</H2>"
				  << "There are currently no new credit entries that are to be processed.<br>"
				  << "Please attempt this operation at a later time.<br>"
				  << mpMarketPlace->GetFooter();

		if (fp)
			fclose(fp);
		CleanUp();
		return;
	}

	// Initialize mail buffers
	pMail	 = new clsMail;
	pMStream = pMail->OpenStream();

	// Everything okay, do the dance
	for (iCredits =  vCredits.begin();
		 iCredits != vCredits.end();
		 iCredits++)
	{
		memset(buf, '\0', sizeof(buf));
		// For each credit request we need to make sure all conditions
		// have been met
		pItem = mpItems->GetItem((*iCredits)->item_id, true);
		if (!pItem)
		{
			pItem = mpItems->GetItemArcDet((*iCredits)->item_id);
			isArc = true;
		}

		if (!pItem)
		{
			delete (*iCredits);
			continue;
		}
		// Check for dutch auctions, if credit request is 'n' (no sale)
		// this is in the event all high bidders backed out
		if (pItem->GetAuctionType() == AuctionDutch)
		{
			// Always default to partial for dutch auctions
			strcpy((*iCredits)->credit_type, "p");
			// We need to compute quantities that are eligible for FVF credit
			// This is different from the total quantities that were put on sale
			// prepare vector
			pvBids = new BidVector;
			// get the high bidders
			pItem->GetDutchHighBidders(pvBids, isArc);
			for (iBids = pvBids->begin(); iBids != pvBids->end(); iBids++)
				// Get matching id and set quantity
				// *WARNING* The quantity here does not represent the quantities
				// that may have been won sucessfully, these are the bid quantities
				quantity += (*iBids)->mQuantity;

			// Check to see the quantity that the last bidder successfully won
			// Force it to be the total quantity on bid
			if (quantity > pItem->GetQuantity())
			{
				quantity = pItem->GetQuantity();
				(*iCredits)->quantity = pItem->GetQuantity();
			}

			// We have the quantites that were not picked for this item
			// and any partial amounts received
			// So the amount that the seller received should be the quantites
			// that got picked plus any partial amount received
			amt =	(pItem->GetPrice() *
					(quantity - (*iCredits)->quantity)) +
					(*iCredits)->amt;
			// In the rare event of amt being 0, all high bidders backed out
			if (amt == 0.0)
				strcpy((*iCredits)->credit_type, "n");
			// Cleanup storage for this item
			for (iBids =  pvBids->begin();
				 iBids != pvBids->end();
				 iBids++)
			{
				// Delete the bid
				delete	(*iBids);
			}
			pvBids->erase(pvBids->begin(), pvBids->end());
			quantity=0;
		}

		// Write details to the notepad file
		if (pItem->GetAuctionType() == AuctionChinese)
			amt = (*iCredits)->amt;
		if (strcmpi((*iCredits)->credit_type, "n") == 0) // no sale credit
			sprintf(buf, "%s\t%d\t\n", (*iCredits)->credit_type, (*iCredits)->item_id);
		else // partial credit
			sprintf(buf, "%s\t%d\t%.2f\n", (*iCredits)->credit_type, (*iCredits)->item_id, 
										   RoundToCents(amt));

		// Write to File
		fprintf(fp, "%s", buf);
		// Write to Mail Buffer
		*pMStream	<<	buf;

		// Delete the credit struct
		delete (*iCredits);
		delete pItem;
		pItem = NULL;
		isArc = false;
	}
	vCredits.erase(vCredits.begin(), vCredits.end());
	fclose(fp);

	// Send Mail to Billing
	pMail->Send(MAILTO,						// somebody in billing 
				"Auto Credit Dumper",		// Sender
				filebuf);					// auto credit file name);

	// All done
	*mpStream << "<H2>Auto Credit File Creation Complete!</H2>"
			  << "Credit entries were processed and written to file: <b>"
			  << filebuf
			  << "</b> in the \"AutoCredits\" folder.<br>"
			  << "A copy of the file has also been mailed to: \""
			  << MAILTO
			  << "\".<br><br>"
			  << "File entries must further be processed using credit-batch2 program.<br>"
			  << mpMarketPlace->GetFooter();

	// Cleanup
	delete pMail;
	CleanUp();
	return;
}
