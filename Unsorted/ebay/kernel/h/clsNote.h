/*	$Id: clsNote.h,v 1.8 1999/05/19 02:34:45 josh Exp $	*/
//
//	File:	clsNote.h
//
//	Class:	clsNote
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//	clsNote is a "note" from one user or users to another
//	user or users.
// 
//
// Modifications:
//				- 04/08/97 michael	- Created
//
#ifndef CLSNOTE_INCLUDED

#include "eBayTypes.h"
#include "clsNoteAddressList.h"
#include "clsNoteAddress.h"

//
// Class forward
//
class clsUser;
class clsItem;

// 
// This enum describes the type of sender for the 
// note. This could be defined by the "from" field,
// but, what the heck ;-)
//
typedef enum
{
	// This message is an auto-post from an admin
	// function
	eClsNoteFromTypeAutoAdminPost	= 1,

	// This message is from a batch job
	eClsNoteFromTypeBatch			= 2,

	// This message is from a user
	eClsNoteFromTypeUser			= 3,

	// This note is from a support/ebay person
	eClsNoteFromTypeeBay			= 4
} eClsNoteFromTypes;


// This enum describes what the note is about. It's
// a bitmask too!
typedef enum
{
	// This is a note about a user
	eClsNoteAboutUser				= 0x00000001,

	// This is a note about an item
	eClsNoteAboutItem				= 0x00000002,

	// This is a note about a board post.
	eClsNoteAboutBoardPost			= 0x00000004
};

//
// The enum describes the major note types. It's only
// used (so far) to divide the types up for the purpose
// of choosing a set of types to display
//
typedef enum
{
	eNoteMajorTypeUnknown				= 0,
	eNoteMajorTypeGeneralSupport		= 1,
	eNoteMajorTypeGeneralBilling		= 2,
	eNoteMajorTypeSupportWarning		= 3,
	eNoteMajorTypeBillingWarning		= 4,
	eNoteMajorTypeSuspension			= 5,
	eNoteMajorTypeAuctionEnd			= 6,
	eNoteMajorTypeReinstatement			= 7,
	eNoteMajorTypeItemMoved				= 8,
	eNoteMajorTypeItemBlocked			= 9,
	eNoteMajorTypeItemFlagged			= 10,
	eNoteMajorTypeItemReinstatement		= 11,
	eNoteMajorTypeSellerFlagged			= 12,
	eNoteMajorTypeSellerUnflagged		= 13,
	eNoteMajorTypeFlaggedSellerActivity = 14,
	eNoteMajorTypeAppealResults			= 15,
	eNoteMajorTypeAuctionEndAttempt		= 16

} eNoteMajorTypeEnum;

//
// This enum describes the note types. 
//
typedef enum
{
	eNoteTypeUnknown												= 0,
	eNoteTypeGeneralSupport											= 1,
	eNoteTypeGeneralBilling											= 2,
	eNoteTypeProceduralWarning										= 3,
	eNoteTypeWarningFeedbackSolicitation							= 4,
	eNoteTypeWarningShillBidding									= 5,
	eNoteTypeWarningBidShielding									= 6,
	eNoteTypeWarningShillFeedbackDefensive							= 7,
	eNoteTypeWarningSpam											= 8,
	eNoteTypeWarningFeedbackExtortion								= 9,
	eNoteTypeWarningBidSiphoning									= 10,
	eNoteTypeWarningMisrepresentationIdentityeBay					= 11,
	eNoteTypeWarningMisrepresentationIdentityUser					= 12,
	eNoteTypeWarningAuctionInterception								= 13,
	eNoteTypeWarningBidManipulationRetraction						= 14,
	eNoteTypeWarningBidManipulationHot								= 15,
	eNoteTypeWarningBidManipulationChronic							= 16,
	eNoteTypeWarningListingFormatAbuse								= 17,
	eNoteTypeWarningAuctionNonperformanceChronic					= 18,
	eNoteTypeWarningSiteInterference								= 19,
	eNoteTypeWarningBadContactInformation							= 20,
	eNoteTypeWarningPatentlyFalseContactInformation					= 21,
	eNoteTypeWarningInvalidEmailAddress								= 22,
	eNoteTypeWarningUnderAgeUser									= 23,
	eNoteTypeWarningMinusFourFeedback								= 24,
	eNoteTypeWarningUnwelcomeBids									= 25,
	eNoteTypeWarningPublishingContactInformation					= 26,
	eNoteTypeWarningPiratedBootlegIllegalItems						= 27,
	eNoteTypeWarningBadLanguage										= 28,
	eNoteTypeWarningAuctionInterference								= 29,
	eNoteTypeWarningPublicBoardAbuse								= 30,
	eNoteTypeSuspension												= 31,
	eNoteTypeSuspensionFeedbackSolicitation							= 32,
	eNoteTypeSuspensionShillBidding									= 33,
	eNoteTypeSuspensionBidShielding									= 34,
	eNoteTypeSuspensionShillFeedbackDefensive						= 35,
	eNoteTypeSuspensionSpam											= 36,
	eNoteTypeSuspensionFeedbackExtortion							= 37,
	eNoteTypeSuspensionBidSiphoning									= 38,
	eNoteTypeSuspensionMisrepresentationIdentityeBay				= 39,
	eNoteTypeSuspensionMisrepresentationIdentityUser				= 40,
	eNoteTypeSuspensionAuctionInterception							= 41,
	eNoteTypeSuspensionBidManipulationRetraction					= 42,
	eNoteTypeSuspensionBidManipulationHot							= 43,
	eNoteTypeSuspensionBidManipulationChronic						= 44,
	eNoteTypeSuspensionListingFormatAbuse							= 45,
	eNoteTypeSuspensionAuctionNonperformanceChronic					= 46,
	eNoteTypeSuspensionSiteInterference								= 47,
	eNoteTypeSuspensionBadContactInformation						= 48,
	eNoteTypeSuspensionPatentlyFalseContactInformation				= 49,
	eNoteTypeSuspensionInvalidEmailAddress							= 50,
	eNoteTypeSuspensionUnderAgeUser									= 51,
	eNoteTypeSuspensionMinusFourFeedback							= 52,
	eNoteTypeSuspensionUnwelcomeBids								= 53,
	eNoteTypeSuspensionPublishingContactInformation					= 54,
	eNoteTypeSuspensionPiratedBootlegIllegalItems					= 55,
	eNoteTypeSuspensionBadLanguage									= 56,
	eNoteTypeSuspensionAuctionInterference							= 57,
	eNoteTypeSuspensionPublicBoardAbuse								= 58,
	eNoteTypeReinstatement											= 59,
	eNoteTypeAuctionEndChoice										= 60,
	eNoteTypeAuctionEndCrossPost									= 61,
	eNoteTypeAuctionEndCounterfeit									= 62,
	eNoteTypeAuctionEndBulkEmail									= 63,
	eNoteTypeAuctionEndBootlegsEtc									= 64,
	eNoteTypeAuctionEndBonusesEtc									= 65,
	eNoteTypeAuctionEndBestiality									= 66,
	eNoteTypeAuctionEndAdvertisement								= 67,
	eNoteTypeAuctionEndDoNotBidSingleItem							= 68,
	eNoteTypeAuctionEndSignPost										= 69,
	eNoteTypeAuctionEndUsedUndergarments							= 70,
	eNoteTypeAuctionEndSellerReserve								= 71,
	eNoteTypeAuctionEndRisqueTitlesFeatured							= 72,
	eNoteTypeAuctionEndReplicas										= 73,
	eNoteTypeAuctionEndRaffles										= 74,
	eNoteTypeAuctionEndPerBidAdditionalPurchase						= 75,
	eNoteTypeAuctionEndMultiListing									= 76,
	eNoteTypeAuctionEndMLM											= 77,
	eNoteTypeAuctionEndLiveAnimals									= 78,
	eNoteTypeAuctionEndLinksOtherAuctionServices					= 79,
	eNoteTypeAuctionEndDirectSale									= 80,
	eNoteTypeAuctionEndItemEmailedBeforeAuctionEnd					= 81,
	eNoteTypeAuctionEndIllegalAdobeItems							= 82,
	eNoteTypeAuctionEndHighShippingCharges							= 83,
	eNoteTypeAuctionEndGenericLegal									= 84,
	eNoteTypeAuctionEndFireworksExplosives							= 85,
	eNoteTypeAuctionEndFeaturedAuctions								= 86,
	eNoteTypeAuctionEndBuddy										= 87,
	eNoteTypeAuctionEndOther										= 88,
	eNoteTypeAuctionEndBuddyAreadyEnded								= 89,
	eNoteTypeAuctionEndAlreadyEnded									= 90,
	eNoteTypeAuctionEndAlreadyEndedBootlegPiratedReplica			= 91,
	eNoteTypeAuctionEndAdultItemInappropriateCategory				= 92,
	eNoteTypeAuctionEndAlreadyEndedAdultItemInappropriateCategory	= 93,
	eNoteTypeWarningeBayTrademarkViolation							= 94,
	eNoteTypeSuspension30Days										= 95,
	eNoteTypeSuspensionSampleForMultipleAccounts					= 96,
	eNoteTypeSuspensionSampleForSingleAccount						= 97,
	eNoteTypeItemMovedItemMovedToAppropriateCategory				= 98,
	eNoteTypeAuctionEndMicrosoft									= 99,
	eNoteTypeAuctionEndAlreadyEndedMicrosoft						= 100,
	eNoteTypeSuspensionRepeatOffense								= 101,
	eNoteTypeSuspensionPreviouslySuspendedUser						= 102,
	eNoteTypeSuspension24Hours										= 103,
	eNoteTypeSuspensionUnregisteredPerUserRequest					= 104,
	eNoteTypeAuctionEndSellerSuspended								= 105,
	eNoteTypeReinstatementAfterShillWarning							= 106,
	eNoteTypeWarningRegistrationVerification						= 107,
	eNoteTypeAuctionEndInappropriateItems							= 108,
	eNoteTypeAuctionEndBuddyIDSA									= 109,
	eNoteTypeAuctionEndAlreadyEndedBuddyIDSA						= 110,
	eNoteTypeItemBlockedUponListing									= 111,		
	eNoteTypeItemBlockedAfterReview									= 112,		
	eNoteTypeItemFlaggedUponListing									= 113,		
	eNoteTypeItemFlaggedAddToDescr									= 114,		
	eNoteTypeItemFlaggedUpdateItemInfo								= 115,		
	eNoteTypeItemFlaggedChangeCategory								= 116,		
	eNoteTypeItemReinstatementAfterReview							= 117,		
	eNoteTypeSellerFlaggedForBlockedItems							= 118,		
	eNoteTypeSellerFlagClearedForBlockedItems						= 119,		
	eNoteTypeFlaggedSellerListNewItem								= 120,		
	eNoteTypeFlaggedSellerAddToItemDescription						= 121,		
	eNoteTypeFlaggedSellerUpdateItemInfo							= 122,		
	eNoteTypeFlaggedSellerChangeCategory							= 123,		
	eNoteTypeItemAddToDescrDenied									= 124,		
	eNoteTypeItemUpdateItemInfoDenied								= 125,		
	eNoteTypeItemChangeCategoryDenied								= 126,		
	eNoteTypeItemBlockedAppealDenied								= 127,
	eNoteTypeAuctionEndAttemptForGoodSeller							= 128

} eNoteTypeEnum;

// 
// This structure defines the valid note "types"
// that exist today. Types are like structured subjects
// for an eNote. Each note can have only one type.
// 
// Each entry contains the id for the type, and
// the string describing it. The string can be emitted
// in any number of formats using some static functions
// included here.
//
//
// ** NOTE **
// Sigh. This all belongs in a database somewhere.
// ** NOTE **
//
class clsNoteType
{
	public:
		eNoteMajorTypeEnum	mMajorType;
		eNoteTypeEnum		mType;
		char				*mpDescription;
		char				*mpDisplayDescription;
		bool				mSubjectRequired;
		bool				mTextRequired;
};



// 
// This enum describes the note's visibility. It's
// a bit mask too.
//
typedef enum
{
	// This note is visible ONLY to support
	eClsNoteVisibleSupportOnly		= 0x00000001
};



class clsNote
{
	public:
		//
		// CTOR, DTOR
		//
		clsNote();

		//
		// Fancy CTOR
		//
		// ** NOTE **
		// This version is used ONLY by the database layer, where it's had
		// to allocate the storage for the instance variables, and "gives"
		// them to the instance to manage. 
		// ** NOTE **
		//
		clsNote(clsNoteAddressList *pTo,
				clsNoteAddressList *pFrom,
				clsNoteAddressList *pCC,
				clsNoteAddressList *pAbout,
				eClsNoteFromTypes fromType,
				unsigned int type,
				unsigned int visibility,
				time_t when,
				time_t expiration,
				char *pSubject,
				char *pTheText);

		//
		// The CTOR to use for notes with 
		// single users for to, from, and cc for
		// note about a user. 
		//
		clsNote(UserId uTo,
				UserId uFrom,
				UserId uCC,
				int uAbout,
				eClsNoteFromTypes fromType,
				unsigned int type,
				unsigned int visibility,
				time_t when,
				time_t expiration,
				char *pSubject,
				char *pTheText);

		//
		// The CTOR to use for notes with 
		// single users for to, from, and cc for
		// note about an item. It differs from the
		// CTOR about in that it ALSO includes 
		// a field for the seller as the "About"
		// user. 
		//
		clsNote(UserId uTo,
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
				char *pTheText);

		//
		// DTOR
		//
		~clsNote();

		//
		// These methods will extract interesting information about an
		// item or user and emit a string about them. They'll either
		// use the (optional) passed object, or fetch the item or
		// user themselves.
		//
		static char *GetItemInfo(int id, clsItem *pItem = NULL);
		static char *GetUserInfo(int id, clsUser *pUser = NULL);

		// 
		// Different note types have information associated with them.
		// such as their "description" for drop down boxes, description
		// for display, whether or not a subject is required, etc. These
		// methods access that information.
		//
		static bool CheckNoteType(unsigned int type);

		static void EmitNoteTypesAsHTMLOptions(ostream *pStream,
											   eNoteMajorTypeEnum majorType,
											   unsigned int selectedValue);

		static const char *GetNoteTypeDescription(unsigned int type);

		static const bool clsNote::IsSubjectRequired(unsigned int type);
		static const bool clsNote::IsTextRequired(unsigned int type);
		static const char *GetEmailTemplate(unsigned int type);

		//
		// These would be the getters and setters
		//
		clsNoteAddressList	*GetTo();
		clsNoteAddressList	*GetFrom();
		clsNoteAddressList	*GetCC();
		clsNoteAddressList	*GetAbout();
		eClsNoteFromTypes	GetFromType();
		unsigned int		GetType();
		unsigned int		GetVisibility();
		time_t				GetWhen();
		time_t				GetExpiration();
		char				*GetSubject();
		char				*GetText();

		void				SetTo(clsNoteAddressList *pTo);
		void				SetFrom(clsNoteAddressList *pFrom);
		void				SetCC(clsNoteAddressList *pCC);
		void				SetAbout(clsNoteAddressList *pAbout);
		void				SetFromType(eClsNoteFromTypes type);
		void				SetType(int type);
		void				SetVisibility(unsigned int visibility);
		void				SetWhen(time_t when);
		void				SetExpiration(time_t expiration);
		void				SetSubject(char *pSubject);
		void				SetText(char *pText);

		// These are special setters which set ONE user, or item,
		// for the to, from, etc fields. They keep the called from
		// having to deal with clsNoteAddressList(s).
		void				SetTo(UserId to);
		void				SetFrom(UserId to);
		void				SetCC(UserId to);
		void				SetAboutUser(UserId uAbout);
		void				SetAboutItem(ItemId iAbout);

		// These funny little Getters are for getting single
		// Tos, Froms, etc. It handles the current implementation,
		// and insulates the caller from dealing with clsNoteAddressList(s)
		UserId				GetUserIdTo();
		UserId				GetUserIdFrom();
		UserId				GetUserIdCC();
		UserId				GetAboutUser();
		ItemId				GetAboutItem();

	private:
		static clsNoteType	mNoteTypes[];

		clsNoteAddressList	*mpTo;
		clsNoteAddressList	*mpFrom;
		clsNoteAddressList	*mpCC;
		clsNoteAddressList	*mpAbout;	
		eClsNoteFromTypes	mFromType;
		unsigned int		mType;
		unsigned int		mVisibility;
		time_t				mWhen;
		time_t				mExpiration;
		char				*mpSubject;
		char				*mpText;

};

// This is a list of notes
typedef list<clsNote *> clsNoteList;

// And a vector
typedef vector<clsNote *> clsNoteVector;



#define CLSNOTE_INCLUDED
#endif /* CLSNOTE_INCLUDED */
