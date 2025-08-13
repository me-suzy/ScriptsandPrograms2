/*	$Id: clsNote.cpp,v 1.8 1999/05/19 02:34:59 josh Exp $	*/
//
//	File:	clsNote.cpp
//
//	Class:	clsNote
//
//	Author:	michael (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 07/05/98 michael	- Created
//
#include "eBayKernel.h"

//
// Fancy constructor, I 
//
// ** NOTE **
// This version is used ONLY by the database layer, where it's had
// to allocate the storage for the instance variables, and "gives"
// them to the instance to manage. 
// ** NOTE **

clsNote::clsNote(clsNoteAddressList *pTo,
					  clsNoteAddressList *pFrom,
					  clsNoteAddressList *pCC,
					  clsNoteAddressList *pAbout,
					  eClsNoteFromTypes fromType,
					  unsigned int type,
					  unsigned int visibility,
					  time_t when,
				      time_t expiration,
					  char *pSubject,
					  char *pText)
{
	mpTo		= pTo;
	mpFrom		= pFrom;
	mpCC		= pCC;
	mpAbout		= pAbout;
	mFromType	= mFromType;
	mType		= type;
	mVisibility	= visibility;
	mWhen		= when;
	mExpiration	= expiration;
	mpSubject	= pSubject;
	mpText		= pText;
}


//
// Fancy CTOR, II
//
// This CTOR is used to create a note about a user.
//
clsNote::clsNote(UserId uTo,
				 UserId uFrom,
				 UserId uCC,
				 int uAbout,
				 eClsNoteFromTypes fromType,
				 unsigned int type,
				 unsigned int visibility,
				 time_t when,
				 time_t expiration,
				 char *pSubject,
				 char *pText)
{

	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uTo);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpFrom			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uFrom);
	mpFrom->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpCC			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uCC);
	mpCC->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpAbout			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uAbout);
	mpAbout->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mFromType	= fromType;
	mType		= type;
	mVisibility	= visibility;
	mWhen		= when;
	mExpiration	= expiration;
	
	mpSubject	= pSubject;
	mpText		= pText;
}

//
// Fancy CTOR, III
//
// This CTOR is used to create a note about an item
//
clsNote::clsNote(UserId uTo,
				 UserId uFrom,
				 UserId uCC,
				 int iAbout,
				 int uAbout,
				 eClsNoteFromTypes fromType,
				 unsigned int type,
				 unsigned int visibility,
				 time_t when,
				 time_t expiration,
				 char *pSubject,
				 char *pText)
{

	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uTo);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpFrom			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uFrom);
	mpFrom->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpCC			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uCC);
	mpCC->push_back(*pNoteAddress);
	delete	pNoteAddress;

	mpAbout			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressItem(iAbout);
	mpAbout->push_back(*pNoteAddress);
	delete	pNoteAddress;
	
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uAbout);
	mpAbout->push_back(*pNoteAddress);
	delete	pNoteAddress;


	mFromType	= fromType;
	mType		= type;
	mVisibility	= visibility;
	mWhen		= when;
	mExpiration	= expiration;
	
	mpSubject	= pSubject;
	mpText		= pText;
}

//
// DTOR
//
clsNote::~clsNote()
{
	delete mpTo;
	delete mpFrom;
	delete mpCC;
	delete mpAbout;

	return;
}

//
// Getters
//
clsNoteAddressList *clsNote::GetTo()
{
	return	mpTo;
}

clsNoteAddressList *clsNote::GetFrom()
{
	return	mpFrom;
}

clsNoteAddressList *clsNote::GetCC()
{
	return	mpCC;
}

clsNoteAddressList *clsNote::GetAbout()
{
	return	mpAbout;
}

eClsNoteFromTypes clsNote::GetFromType()
{
	return	mFromType;
}

unsigned int clsNote::GetType()
{
	return	mType;
}

unsigned int clsNote::GetVisibility()
{
	return	mVisibility;
}

time_t clsNote::GetWhen()
{
	return	mWhen;
}

time_t clsNote::GetExpiration()
{
	return	mExpiration;
}

char *clsNote::GetSubject()
{
	return mpSubject;
}

char *clsNote::GetText()
{
	return	mpText;
}

// 
// Setters
//
void clsNote::SetTo(clsNoteAddressList *pTo)
{
	mpTo	= pTo;
}

void clsNote::SetFrom(clsNoteAddressList *pFrom)
{
	mpFrom	= pFrom;
}


void clsNote::SetCC(clsNoteAddressList *pCC)
{
	mpCC	= pCC;
}

void clsNote::SetAbout(clsNoteAddressList *pAbout)
{
	mpAbout	= pAbout;
}


void clsNote::SetFromType(eClsNoteFromTypes type)
{
	mFromType	= type;
}

void clsNote::SetType(int type)
{
	mType		= type;
}

void clsNote::SetWhen(time_t when)
{
	mWhen		= when;
}

void clsNote::SetExpiration(time_t expiration)
{
	mExpiration	= expiration;
}

void clsNote::SetSubject(char *pSubject)
{
	mpSubject	= pSubject;
}

void clsNote::SetText(char *pText)
{
	mpText		= pText;
}

//
// Special Setters
//
void clsNote::SetTo(UserId to)
{
	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(to);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;
}

void clsNote::SetFrom(UserId from)
{
	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(from);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;
}

void clsNote::SetCC(UserId cc)
{
	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(cc);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;
}

void clsNote::SetAboutUser(UserId uAbout)
{
	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressUser(uAbout);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;
}

void clsNote::SetAboutItem(ItemId iAbout)
{
	clsNoteAddress	*pNoteAddress;

	mpTo			= new clsNoteAddressList();
	pNoteAddress	= new clsNoteAddress();
	pNoteAddress->SetAddressItem(iAbout);
	mpTo->push_back(*pNoteAddress);
	delete	pNoteAddress;
}

//
// Special Getters
//
// These Getters return the first (and, presumably, only) 
// address in a clsNoteAddressList. They're mainly to 
// accomodate our initial implementation, which supports
// only one addressee
//
UserId clsNote::GetUserIdTo()
{
	clsNoteAddressList::iterator	iAddress;

	if (mpTo == NULL || mpTo->size() == 0)
		return 0;

	iAddress	= mpTo->begin();
	return (*iAddress).GetAddressUser();
}

UserId clsNote::GetUserIdFrom()
{
	clsNoteAddressList::iterator	iAddress;

	if (mpFrom == NULL || mpFrom->size() == 0)
		return 0;

	iAddress	= mpFrom->begin();
	return (*iAddress).GetAddressUser();
}

UserId clsNote::GetUserIdCC()
{
	clsNoteAddressList::iterator	iAddress;

	if (mpCC == NULL || mpCC->size() == 0)
		return 0;

	iAddress	= mpCC->begin();
	return (*iAddress).GetAddressUser();
}

UserId clsNote::GetAboutUser()
{
	clsNoteAddressList::iterator	iAddress;


	if (mpAbout == NULL || mpAbout->size() == 0)
		return 0;

	for (iAddress = mpAbout->begin();
		 iAddress != mpAbout->end();
		 iAddress++)
	{
		if ((*iAddress).GetType() == eClsNoteAddressUser)
			return (*iAddress).GetAddressUser();
	}
	return 0;
}

UserId clsNote::GetAboutItem()
{
	clsNoteAddressList::iterator	iAddress;

	if (mpAbout == NULL || mpAbout->size() == 0)
		return 0;

	for (iAddress = mpAbout->begin();
		 iAddress != mpAbout->end();
		 iAddress++)
	{
		if ((*iAddress).GetType() == eClsNoteAddressItem)
			return (*iAddress).GetAddressItem();
	}
	return 0;
}

//
// This routine checks to see if a passed note type
// is valid
//
bool clsNote::CheckNoteType(unsigned int type)
{
	clsNoteType		*pNoteType;

	for (pNoteType	= mNoteTypes;
		 pNoteType->mType != 0;
		 pNoteType++)
	{
		if (pNoteType->mType == type)
			return true;
	}

	return false;
}

//
// This routine returns the description for a 
// note type
//
const char *clsNote::GetNoteTypeDescription(unsigned int type)
{
	clsNoteType		*pNoteType;

	for (pNoteType	= mNoteTypes;
		 pNoteType->mType != 0;
		 pNoteType++)
	{
		if (pNoteType->mType == type)
			return pNoteType->mpDisplayDescription;
	}

	return NULL;
}


//
// This routine emits a nice list of all the
// note types as HTML Option tags
//
void clsNote::EmitNoteTypesAsHTMLOptions(ostream *pStream,
										 eNoteMajorTypeEnum majorType,
										 unsigned int selectedValue)
{
	clsNoteType		*pNoteType;

	for (pNoteType	= mNoteTypes;
		 pNoteType->mType != 0;
		 pNoteType++)
	{
		if (majorType != eNoteMajorTypeUnknown &&
			pNoteType->mMajorType != majorType)
		{
			continue;
		}
		*pStream <<	"<OPTION VALUE=\""
				 <<	pNoteType->mType
				 <<	"\"";

		if (pNoteType->mType == selectedValue)
			*pStream <<	" SELECTED";

		*pStream <<	">"
				 <<	pNoteType->mpDescription
				 << "</OPTION>";
	}

	return;
}


//
// GetItemInfo
//
// This routine will extract some interesting information about
// an item, and form a nice text string for the caller to prepend
// to the note. 
//
// The caller should append <br><br> to the text for proper spacing.
//
// The caller OWNS the text, and must delete it.
//
char *clsNote::GetItemInfo(int id, clsItem *pPassedItem)
{
	clsItem				*pItem;
	char				text[2048];
	char				*pText;
	char				*pCleanTitle;

	if (pPassedItem != NULL)
		pItem	= pPassedItem;
	else
		pItem	=
			gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetItems()->GetItem(id);
	
	if (!pItem)
		return NULL;

	pCleanTitle	= clsUtilities::StripHTML(pItem->GetTitle());

	sprintf(text,"<b>Item Title</b>: %s <br>", pCleanTitle);

	if (pItem->GetQuantity() < 2)
	{
		sprintf(text + strlen(text),
				"<b>Item Info:</b> Start price: $%8.2f, reserve $%8.2f, current $%8.2f, %d bids",
				pItem->GetStartPrice(),
				pItem->GetReservePrice(),
				pItem->GetPrice(),
				pItem->GetBidCount());
	}
	else
	{

		sprintf(text + strlen(text),
				"<b> Dutch Item Info:</b> Quantity %d, Start price: $%8.2f, reserve $%8.2f, current $%8.2f, %d bids",
				pItem->GetQuantity(),
				pItem->GetStartPrice(),
				pItem->GetReservePrice(),
				pItem->GetPrice(),
				pItem->GetBidCount());

		if (pItem->GetBidCount() > 0)
		{
			sprintf(text + strlen(text),
					", high bidder %s(%s)",
					pItem->GetHighBidderUserId(),
					pItem->GetHighBidderEmail());
		}

	}


	pText	= new char[strlen(text) + 1];
	strcpy(pText, text);

	// If the item wasn't passed, we got it, and should delete it.
	if (pPassedItem == NULL)
		delete	pItem;

	return pText;
}

//
// GetUserInfo
//
// This routine will extract some interesting information about
// a user, and form a nice text string for the caller to prepend
// to the note. 
//
// The caller should append <br><br> to the text for proper spacing.
//
// The caller OWNS the text, and must delete it.
//
char *clsNote::GetUserInfo(int id, clsUser *pPassedUser)
{
	clsUser				*pUser;
	clsFeedback			*pFeedback;
	char				text[2048];
	char				*pText;

	if (pPassedUser != NULL)
		pUser	= pPassedUser;
	else
		pUser	=
			gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUser(id);
	
	if (!pUser)
		return NULL;

	pFeedback	= pUser->GetFeedback();

	sprintf(text,
			"<b>User Info:</b>Userid %s, Email %s, Feedback score %d",
			pUser->GetUserId(),
			pUser->GetEmail(),
			pFeedback->GetScore());

	pText	= new char[strlen(text) + 1];
	strcpy(pText, text);

	if (pPassedUser == NULL)
		delete	pUser;

	return pText;
}

//
// This routine returns a boolean true or false as to whether
// or not a subject is required for this note type
//
const bool clsNote::IsSubjectRequired(unsigned int type)
{
	clsNoteType		*pNoteType;

	for (pNoteType	= mNoteTypes;
		 pNoteType->mType != 0;
		 pNoteType++)
	{
		if (pNoteType->mType == type)
			return pNoteType->mSubjectRequired;
	}

	return true;
}

//
// This routine returns a boolean true or false as to whether
// or not a long text description is required for this note type
//
const bool clsNote::IsTextRequired(unsigned int type)
{
	clsNoteType		*pNoteType;

	for (pNoteType	= mNoteTypes;
		 pNoteType->mType != 0;
		 pNoteType++)
	{
		if (pNoteType->mType == type)
			return pNoteType->mTextRequired;
	}

	return true;
}


clsNoteType clsNote::mNoteTypes[] =
{	{	eNoteMajorTypeGeneralSupport,	
		eNoteTypeGeneralSupport,		
		"General support note",
		"General support note",
		true,	true
	},
	
	{	eNoteMajorTypeGeneralBilling,
		eNoteTypeGeneralBilling,		
		"General billing note",
		"General billing note",
		true,	true
	},	
	
	{	eNoteMajorTypeSupportWarning,
		eNoteTypeProceduralWarning,		
		"General Warning",
		"Procedural warning issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningFeedbackSolicitation,		
		"Feedback Solicitation",
		"Warning for feedback solicitation issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningShillBidding,		
		"Shill bidding",
		"Warning for shill bidding issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBidShielding,		
		"Bid shielding",
		"Warning for bid shielding issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningShillFeedbackDefensive,		
		"Shill feedback, defensive",
		"Warning for shill feedback, defensive issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningSpam,		
		"SPAM",
		"Warning for SPAM issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningFeedbackExtortion,		
		"Feedback extortion",
		"Warning for feedback extortion issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBidSiphoning,		
		"Bid siphoning",
		"Warning for bid siphoning issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningMisrepresentationIdentityeBay,		
		"Identity misrepresentation - eBay",
		"Warning for identity misrepresentation - eBay issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningMisrepresentationIdentityUser,		
		"Identity misrepresentation - user",
		"Warning for identity misrepresentation - user issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningAuctionInterception,		
		"Auction interception",
		"Warning for auction interception issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBidManipulationRetraction,		
		"Bid manipulation (retraction)",
		"Warning for Bid manipulation (retraction) issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBidManipulationHot,		
		"Bid manipulation (hot)",
		"Warning for Bid manipulation (hot) issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBidManipulationChronic,		
		"Bid manipulation (chronic)",
		"Warning for Bid manipulation (chronic) issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningListingFormatAbuse,		
		"Listing format abuse",
		"Warning for listing format abuse issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningAuctionNonperformanceChronic,		
		"Auction non-performance (chronic)",
		"Warning for auction non-performance (chronic) issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningSiteInterference,		
		"Site Interference",
		"Warning for site interference issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBadContactInformation,		
		"False, missing or omissive contact information",
		"Warning for false, missing or omissive contact information issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningPatentlyFalseContactInformation,		
		"Patently false contact information",
		"Warning for patently false contact information issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningInvalidEmailAddress,		
		"Invalid email address",
		"Warning for invalid email address issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningUnderAgeUser,		
		"Underage user",
		"Warning for underage user issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningMinusFourFeedback,		
		"Feedback rating of -4",
		"Warning for feedback rating of -4 issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningUnwelcomeBids,		
		"Unwelcome bidding",
		"Warning for unwelcome bidding issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningPublishingContactInformation,		
		"Publishing contact information",
		"Warning for publishing contact information issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningPiratedBootlegIllegalItems,		
		"Bootleg, pirated, or illegal items",
		"Warning for bootleg, pirated, or illegal items issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningBadLanguage,		
		"Bad language",
		"Warning for bad language issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningAuctionInterference,		
		"Auction interference",
		"Warning for auction interference issued",
		true,	true
	},

	{	eNoteMajorTypeSupportWarning,	
		eNoteTypeWarningPublicBoardAbuse,		
		"Public board abuse",
		"Warning for public board abuse issued",
		true,
		true
	},

	//
	// Suspensions start here
	//
	
	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspension,			
		"General Suspension",
		"General Suspension",
		true,
		true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionFeedbackSolicitation,		
		"Feedback Solicitation",
		"Suspended forfeedback solicitation",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionShillBidding,		
		"Shill bidding",
		"Suspended forshill bidding",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBidShielding,		
		"Bid shielding",
		"Suspended for bid shielding",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionShillFeedbackDefensive,		
		"Shill feedback, defensive",
		"Suspended for shill feedback, defensive",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionSpam,		
		"SPAM",
		"Suspended for SPAM",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionFeedbackExtortion,		
		"Feedback extortion",
		"Suspended for feedback extortion",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBidSiphoning,		
		"Bid siphoning",
		"Suspended for bid siphoning",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionMisrepresentationIdentityeBay,		
		"Identity misrepresentation - eBay",
		"Suspended for identity misrepresentation - eBay",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionMisrepresentationIdentityUser,		
		"Identity misrepresentation - user",
		"Suspended for identity misrepresentation - user",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionAuctionInterception,		
		"Auction interception",
		"Suspended for auction interception",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBidManipulationRetraction,		
		"Bid manipulation (retraction)",
		"Suspended for Bid manipulation (retraction)",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBidManipulationHot,		
		"Bid manipulation (hot)",
		"Suspended for Bid manipulation (hot)",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBidManipulationChronic,		
		"Bid manipulation (chronic)",
		"Suspended for Bid manipulation (chronic)",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionListingFormatAbuse,		
		"Listing format abuse",
		"Suspended for listing format abuse",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionAuctionNonperformanceChronic,		
		"Auction non-performance (chronic)",
		"Suspended for auction non-performance (chronic)",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionSiteInterference,		
		"Site Interference",
		"Suspended for site interference",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBadContactInformation,		
		"False, missing or omissive contact information",
		"Suspended for false, missing or omissive contact information",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionPatentlyFalseContactInformation,		
		"Patently false contact information",
		"Suspended for patently false contact information",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionInvalidEmailAddress,		
		"Invalid email address",
		"Suspended for invalid email address",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionUnderAgeUser,		
		"Underage user",
		"Suspended for underage user",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionMinusFourFeedback,		
		"Feedback rating of -4",
		"Suspended for feedback rating of -4",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionUnwelcomeBids,		
		"Unwelcome bidding",
		"Suspended for unwelcome bidding",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionPublishingContactInformation,		
		"Publishing contact information",
		"Suspended for publishing contact information",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionPiratedBootlegIllegalItems,		
		"Bootleg, pirated, or illegal items",
		"Suspended for bootleg, pirated, or illegal items",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionBadLanguage,		
		"Bad language",
		"Suspended for bad language",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionAuctionInterference,		
		"Auction interference",
		"Suspended for auction interference",
		true,	true
	},

	{	eNoteMajorTypeSuspension,	
		eNoteTypeSuspensionPublicBoardAbuse,		
		"Public board abuse",
		"Suspended for public board abuse",
		true,
		true
	},	

	//
	// Reinstatement
	//
	
	{	eNoteMajorTypeReinstatement,
		eNoteTypeReinstatement,			
		"Reinstated",
		"Reinstated",
		true,
		true
	},
	
	//
	// Auction Endings start here
	//
	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndChoice,		
		"Dutch Auction Choice",
		"Dutch Auction Choice Ended",
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndCrossPost,	
		"Crossposting Service/Info Items",
		"Auction ended - Crossposting Service/Info Items",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndCounterfeit,	
		"Counterfeit items",
		"Auction ended - Counterfeit items",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBulkEmail,	
		"Bulk Email",
		"Auction ended - Bulk Email",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBootlegsEtc,	
		"Bootlegs, Pirated media, etc",
		"Auction ended - Bootlegs, Pirated media, etc",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBonusesEtc,	
		"Bonuses, Giveaways, Prizes",
		"Auction ended - Bonuses, Giveaways, Prizes",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBestiality,	
		"Beastiality",
		"Auction ended - Bestiality",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAdvertisement,	
		"Advertisement",
		"Auction ended - Advertisement",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndDoNotBidSingleItem,	
		"\"Do Not Bid - Single Item\"",
		"Auction ended - \"Do Not Bid - Single Item\"",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndSignPost,	
		"Signpost",
		"Auction ended - Signpost",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndUsedUndergarments,	
		"Used undergarments",
		"Auction ended - Used undergarments",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndSellerReserve,	
		"Seller indicates \"reserve\"",
		"Auction ended - Seller indicates \"reserve\"",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndRisqueTitlesFeatured,	
		"Risque Titles for Featured Auctions",
		"Auction ended - Risque Titles for Featured Auctions",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndReplicas,	
		"Replicas",
		"Auction ended - Replicas",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndRaffles,	
		"Raffles, Lotteries",
		"Auction ended - Raffles, Lotteries",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndPerBidAdditionalPurchase,	
		"Per bid additional purchase",
		"Auction ended - Per bid additional purchase",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndMultiListing,	
		"Multi-listing",
		"Auction ended - Multi-listing",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndMLM,	
		"Multi Level Marketing",
		"Auction ended - Multi Level Marketing",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndLiveAnimals,	
		"Live Animals",
		"Auction ended - Live Animals",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndLinksOtherAuctionServices,	
		"Links to other auction services",
		"Auction ended - Links to other auction services",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndDirectSale,	
		"Item for Direct Sale",
		"Auction ended - Item for Direct Sale",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndItemEmailedBeforeAuctionEnd,	
		"Item Emailed Before Auction Ends",
		"Auction ended - Item Emailed Before Auction Ends",	
		false,
		false
	},


	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndIllegalAdobeItems,	
		"Illegal Adobe Items",
		"Auction ended - Illegal Adobe Items",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndHighShippingCharges,	
		"High shipping charges",
		"Auction ended - High shipping charges",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndGenericLegal,	
		"Generic, legal",
		"Auction ended - Generic, legal",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndFireworksExplosives,	
		"Fireworks, Explosives",
		"Auction ended - Fireworks, Explosives",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndFeaturedAuctions,	
		"Featured Auctions",
		"Auction ended - Featured Auctions",	
		false,
		false
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBuddy,	
		"Buddy Program",
		"Auction ended - Buddy Program",	
		false,
		false
	},

	
	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndOther,		
		"Other",
		"Auction Ended",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBuddyAreadyEnded,		
		"Buddy Program -- Auction Already Ended",
		"Auction already ended - Buddy Program",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAlreadyEnded,		
		"Auction already ended",
		"Auction already ended",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAlreadyEndedBootlegPiratedReplica,		
		"Auction already ended - Bootleg, Pirated Media, or Replica",
		"Auction already ended - Bootleg, Pirated Media, or Replica",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAdultItemInappropriateCategory,		
		"Adult Item in inppropriate category",
		"Auction ended - Adult Item in inppropriate category",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAlreadyEndedAdultItemInappropriateCategory,		
		"Auction already ended - Adult Item in inppropriate category",
		"Auction already ended - Adult Item in inppropriate category",
		true,
		true,
	},

	//
	// NEW Warnings
	//
	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningeBayTrademarkViolation,		
		"eBay Trademark Violation",
		"Warning for eBay Trademark Violation",
		true,	true
	},

	//
	// NEW Suspensions
	//
	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspension30Days,		
		"30 Day Suspension",
		"Suspended for 30 days",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionSampleForMultipleAccounts,		
		"Multiple account suspension sample",
		"Sample Suspension for multiple accounts",
		true,	true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionSampleForSingleAccount,		
		"Single account suspension sample",
		"Sample Suspension for single account",
		true,	true
	},

	//
	// Item(s) moved to a new category
	//
	{	eNoteMajorTypeItemMoved,
		eNoteTypeItemMovedItemMovedToAppropriateCategory,		
		"Item(s) moved to appropriate category",
		"Item(s) moved to appropriate category",
		true,	true
	},


	//
	// NEW Auction Endings -- Microsoft
	//
	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndMicrosoft,		
		"Microsoft infringing item",
		"Auction ended - Microsoft infringing item",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAlreadyEndedMicrosoft,		
		"Auction already ended - Microsoft infringing item",
		"Auction already ended - Microsoft infringing item",
		true,
		true,
	},

	//
	// NEW Suspensions
	//
	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionRepeatOffense,		
		"Repeat offense",
		"Suspended for repeat offenses",
		true,
		true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionPreviouslySuspendedUser,		
		"Previously suspended user",
		"Suspended - Previously suspended user",
		true,
		true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspension24Hours,		
		"24-hour suspension",
		"Suspended - 24 hour suspension",
		true,
		true
	},

	{	eNoteMajorTypeSuspension,
		eNoteTypeSuspensionUnregisteredPerUserRequest,		
		"Per User Request",
		"Suspended - Un-registered per user request",
		true,
		true
	},

	//
	// NEW Auction end
	//
	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndSellerSuspended,		
		"Seller Suspended",
		"Auction ended - Seller Suspended",
		true,
		true,
	},

	//
	// new Reinstatement
	//
	
	{	eNoteMajorTypeReinstatement,
		eNoteTypeReinstatementAfterShillWarning,			
		"Reinstated after shill warning",
		"Reinstated after shill warning",
		true,
		true
	},

	//
	// NEW Warning
	//
	{	eNoteMajorTypeSupportWarning,
		eNoteTypeWarningRegistrationVerification,		
		"Registration verification",
		"Warning for registration verification",
		true,	true
	},

	//
	// NEW Auction End
	//

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndInappropriateItems,		
		"Inappropriate Items",
		"Auction ended - Inappropriate Items",
		true,
		true,
	},


	//
	// NEW Auction Ending - 04/22/99
	//

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndBuddyIDSA,		
		"IDSA Buddies (Nintendo, Sega, Sony, Electronic Arts)",
		"Auction ended - IDSA Buddies (Nintendo, Sega, Sony, Electronic Arts)",
		true,
		true,
	},

	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeAuctionEndAlreadyEndedBuddyIDSA,		
		"IDSA Buddies already ended (Nintendo, Sega, Sony, Electronic Arts)",
		"Auction already ended - IDSA Buddies (Nintendo, Sega, Sony, Electronic Arts)",
		true,
		true,
	},


	//
	// NEW Item Blocking
	//

	{	eNoteMajorTypeItemBlocked,
		eNoteTypeItemBlockedUponListing,		
		"Screened Item Blocked",
		"Item Blocked - Rejected at Initial Screening",
		true,
		true,
	},


	{	eNoteMajorTypeAuctionEnd,
		eNoteTypeItemBlockedAfterReview,		
		"Screened Item Blocked after Review",
		"Screened Item Blocked - Rejected after Review",
		true,
		true,
	},


	//
	// NEW Item Flagged
	//

	{	eNoteMajorTypeItemFlagged,
		eNoteTypeItemFlaggedUponListing,		
		"Screened Item Flagged",
		"Item Flagged - Inappropriate Wording in Title or Description",
		true,
		true,
	},


	{	eNoteMajorTypeItemFlagged,
		eNoteTypeItemFlaggedAddToDescr,		
		"Amended Item Description Flagged",
		"Item Flagged - Inappropriate Wording in Added Description",
		true,
		true,
	},


	{	eNoteMajorTypeItemFlagged,
		eNoteTypeItemFlaggedUpdateItemInfo,		
		"Item Update Info Flagged",
		"Item Flagged - Inappropriate Wording in Updated Info",
		true,
		true,
	},


	{	eNoteMajorTypeItemFlagged,
		eNoteTypeItemFlaggedChangeCategory,		
		"Item Category Change Flagged",
		"Item Flagged - Inappropriate Wording for New Category",
		true,
		true,
	},


	{	eNoteMajorTypeItemReinstatement,
		eNoteTypeItemReinstatementAfterReview,		
		"Blocked Item Reviewed - Approved for Reinstatement",
		"Item reinstated - Review of previously blocked item",
		true,
		true,
	},


	{	eNoteMajorTypeSellerFlagged,
		eNoteTypeSellerFlaggedForBlockedItems,		
		"Seller Has Blocked Item(s)",
		"Seller flagged - Listing blocked items",
		true,
		true,
	},


	{	eNoteMajorTypeSellerUnflagged,
		eNoteTypeSellerFlagClearedForBlockedItems,		
		"Clear Seller Flag for Blocked Item(s)",
		"Seller unflagged - Previously blocked item(s) deemed acceptable",
		true,
		true,
	},


	{	eNoteMajorTypeFlaggedSellerActivity,
		eNoteTypeFlaggedSellerListNewItem,		
		"New Item by Flagged Seller",
		"Flagged seller activity - List item in high-risk category",
		true,
		true,
	},


	{	eNoteMajorTypeFlaggedSellerActivity,
		eNoteTypeFlaggedSellerAddToItemDescription,		
		"Add to Item Description by Flagged Seller",
		"Flagged seller activity - Add to item description",
		true,
		true,
	},


	{	eNoteMajorTypeFlaggedSellerActivity,
		eNoteTypeFlaggedSellerUpdateItemInfo,		
		"Update Item Info by Flagged Seller",
		"Flagged seller activity - Update item info",
		true,
		true,
	},


	{	eNoteMajorTypeFlaggedSellerActivity,
		eNoteTypeFlaggedSellerChangeCategory,		
		"Item Category Change by Flagged Seller",
		"Flagged seller activity - Change item category",
		true,
		true,
	},


	//
	// NEW Appealed Results
	//

	{	eNoteMajorTypeAppealResults,
		eNoteTypeItemBlockedAppealDenied,		
		"Blocked Item Appeal Denied",
		"Screened Item Blocked - Rejected after Review",
		true,
		true,
	},

	//
	// 05/06/99 : New note notifying CSR supervisors when
	// someone tries to end a top seller's auctions
	//

	{	eNoteMajorTypeAuctionEndAttempt,
		eNoteTypeAuctionEndAttemptForGoodSeller,		
		"Attempt to end good seller\'s auction(s)",
		"Attempt to end good seller\'s auction(s)",
		false,
		false,
	},

	//
	// Last one -- don't put anything after this one!!
	//
	{	eNoteMajorTypeUnknown,
		eNoteTypeUnknown,
		"",
		"",
		false,
		false
	}
};
