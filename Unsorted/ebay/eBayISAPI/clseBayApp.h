/*	$Id: clseBayApp.h,v 1.20.2.7.34.2 1999/08/04 00:59:50 phofer Exp $	*/
//
//	File:	clseBayApp.h
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 08/25/97 michael	- Created
//				- 11/12/97 poon		- moved sorting stuff to kernel\clseBayItems.h
//				- 08/13/98 mila		- new feedback forum stuff
//				- 08/15/98 mila		- added args to feedback pagination methods so
//									  the methods will work with regular and personalized
//									  feedback profiles
//				- 08/22/98 mila		- added methods for feedback response/follow-up
//				- 08/27/98 mila		- assigned default values to two parameters for
//									  methods ViewFeedback and ViewPersonalizedFeedback
//				- 11/03/98 mila		- changed gift alert method prototype args of
//									  type GiftOccasionEnum to type int
//				- 02/23/99 anoop	- Added ValidateOrBlockAction method
//				- 09/25/98 mila		- added methods ViewDeadbeatUser, DeleteDeadbeatItem,
//									  and DeleteDeadbeatItemConfirm
//				- 10/13/98 mila		- added method ViewDeadbeatUsers
//				- 12/16/98 mila		- Deleted admin user ID, password, and auth level 
//									  parameters from Deadbeat methods where appropriate 
//									  since functionality is accessible only from 
//									  password-protected support page.
//				- 04/07/99 kaz		- Added kPoliceBadgeCatID for checking for T&C page
//				- 04/15/99 kaz		- Added kPoliceBadgeCatID for checking for T&C page
//										Added name & pwd params to CheckUpdatedItemInfo
//				- 05/24/99 jennifer - added functions for Gallery Admin Tool
//				- 06/15/99 petra	- wired off category admin functions
//				- 06/21/99 nsacco	- Added siteid to ShowCobrandPartners
//									  and siteId and pParsedString to CreateCobrandPartner()
//				- 06/28/99 petra	- changed parm list of MailSupportAboutCountryChange
//				- 07/02/99 nsacco	- Added siteId and coPartnerId to register
//				- 07/27/99 nsacco	- Added siteId, descLang, pShipToNorthAmerica, pShipToEurope,
//										pShipToOceania, pShipToAsia, pShipToSouthAmerica, pShipToAfrica
//										to VerifyNewItem. Updated UserAgreementForSelling to take all the
//										same parameters as VerifyNewItem except server context and ua choice.
//										Added new shipping params to CheckItemData. Added new params to
//										AddNewItem, CheckUpdatedItemInfo, VerifyUpdateItem, UpdateItemInfo.
//
#ifndef CLSEBAYAPP_INCLUDED
#define CLSEBAYAPP_INCLUDED 1

#include "clsItems.h"
#include "clsApp.h"
#include "eBayPageTypes.h"
#include "clseBayCookie.h"
#include "clsNote.h"
#include "clsGiftOccasion.h"
#include "clsNote.h"
#include <time.h>
#include <strstrea.h>
#include "clsCountries.h"
#include "clsCurrencies.h"
#include "clsFilter.h"
#include "clsPSSearch.h"

#ifndef VECTOR_H
#include "vector.h"
#endif

extern int allspace(const char *);
//
// This macro is a quick way to see if a user 
// omitted a parameter. Besides the usual 
// checks for null and zero-length, it also 
// checks for ths ISAPI value of "default"
//
#define FIELD_OMITTED(pIt)				\
	(!pIt ||							\
	 (strlen(pIt) == 0) ||				\
	 (strcmp(pIt, "default") == 0) ||   \
     allspace(pIt)) 

#define kPoliceBadgeCatID		929		// kaz: 4/7/99: Added for checking the category ID

// Class forward
class CEBayISAPIExtension;
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsItems;
class clsUsers;
class clsStatistics;
class clsItem;
class clsUser;
class clsFeedback;
class clsFeedbackItem;
class clsBid;
class CeBayISAPIExtension;
class ostream;
class clsCategories;
class clsCategory;
class CHttpServerContext;
class clsDailyStatistics;
class clsAnnouncements;
class clsAnnouncement;
class clsBulletinBoard;
class clsAccount;
class clsAuthorizationQueue;
class clsNameValuePair;
class clsGiftOccasion;
class clsLocations;
class clsUserVerificationServices;
class clsPSSearches;
class clsSite;
class clsPartner;
class clsAd;


// This Enum tells us the level of authorization
// the user has
//
typedef enum
{
	eBayISAPIAuthUser		= 0,
	eBayISAPIAuthSupport	= 1,
	eBayISAPIAuthAdmin		= 3
} eBayISAPIAuthEnum;

// What the user decided when the user agreement
// popped up in the flow of site activity.
typedef enum
{
	UAShowAgreement			= 0,
	UAAcceptedWithNotify	= 1,
	UAAcceptedWithoutNotify	= 2,
	UADeclined				= 3
} UAChoice;

// 
// This little struct is used to build
// drop-down lists
//
typedef struct
{
	char	*pValue;
	char	*pLabel;
} DropDownSelection;


// And a few lists
extern const DropDownSelection	StateSelection[];
extern const DropDownSelection	CountrySelection[];
extern const DropDownSelection	GenderSelection[];
extern const DropDownSelection  QueryEmailSubject[];
extern const DropDownSelection  GiftIconSelection[];
extern const DropDownSelection  ItemTypeSelection[];

const int ROWS_PER_PAGE = 40;
const int FEEDBACK_ITEMS_PER_PAGE = 25;
const int STARTING_PAGE = 1;
const int MAX_ROWS = 200;

//inna
const double FEE_REDEPOSIT_CHECK = 3.00;
const double FEE_RETURNED_CHECK = 15.00;

// And a routine to use them!
bool EmitDropDownList(ostream *pStream,
					  char *pListName,
					  DropDownSelection *pSelectionList,
					  char *pSelectedValue,
					  char *pUnSelectedValue,
					  char *pUnSelectedLabel);

// If there's a default value, use this...
bool EmitScrollingList(ostream *pStream,
					  char *pListName,
					  int   numInView,
					  ScrollingSelection *pSelectionList,
					  int   selectedValue, 
					  int defaultValue,
					  const char* pDefaultEntry);

// If there's no default value (other than the current selection),
// use this... (emitName indicates whether to write out the
// beginning <SELECT> tag.
bool EmitScrollingList(ostream *pStream,
					  char *pListName,
					  int   numInView,
					  ScrollingSelection *pSelectionList,
					  int   selectedValue, 
					  bool emitName);

typedef struct
{
	int   templateLayout;
	char *pPageTitle;
	char *pTextAreaTitle1;
	char *pTextArea1;
	char *pTextAreaTitle2;
	char *pTextArea2;
	char *pPictureCaption;
	char *pPictureURL;
	bool  showUserIdEmail;
	int   feedbackNumComments;
	int   itemlistNumItems;
	char *pItemlistCaption;
	char *pFavoritesDescription1;
	char *pFavoritesName1;
	char *pFavoritesLink1;
	char *pFavoritesDescription2;
	char *pFavoritesName2;
	char *pFavoritesLink2;
	char *pFavoritesDescription3;
	char *pFavoritesName3;
	char *pFavoritesLink3;
	int   item1CaptionChoice;
	int   item1;
	int   item2CaptionChoice;
	int   item2;
	int   item3CaptionChoice;
	int   item3;
	bool  pageCount;
	bool  dateTime;
	int   bgPattern;
} TemplateElements;


typedef enum
{
	UserPageHTMLEditingDontSave		 = 0,
	UserPageHTMLEditingStartOver	 = 1,
	UserPageTemplateToHTMLEditing	 = 2,
	UserPageTemplateEditingDontSave  = 3,
	UserPageTemplateEditingStartOver = 4,
	UserPageTemplateEditingSave		 = 5
} UserPageEditingEnum;


//
// BCC list for automated emails
//
const char *AutomatedSupportEmailBccList[];
const char *AutomatedSupportEmailBccListAuctionEnded[];


//
// This class maps Support eNote Types to their
// e-Mail templates. See the private method
// GetEmailTemplateForNoteType(); The actual data
// if found in the file AdminMapNoteTypeToMailTemplate.cpp.
//
class clsMapNoteTypeToMailTemplate
{
	public:
		unsigned int	mNoteType;
		const char *	mpEmailSubject;
		const char *	mpEmailTemplate;
		const char *	mpBidderEmailSubject;
		const char *	mpBidderEmailTemplate;
		const char *	mpBidderEmailSellerSuspendedTemplate;
		const char *	mpBuddyEmailSubject;
		const char *	mpBuddyEmailTemplate;

};

//
// This class maps Copyright/patent "Buddy" ids to their
// names, and other useful information about the "Buddy".
// It's used by the admin end auction functions. The
// actual data is found in AdminCopyrightBuddyInfo.cpp .
//
class clsCopyrightBuddyInfo
{
	public:
		unsigned int	mBuddyId;
		
		// The buddies name as it appears in the drop
		// down.
		const char		*mpBuddyDropDownName;
		
		// The buddies name as it appears in the emails
		// to violating sellers and bidders on their auctions
		const char		*mpBuddyEmailName;

		// The email address of the contact at the buddy.
		const char		*mpBuddyContactEmail;
};

//
// This routine will retrieve the path name and, from 
// it, determine the user's authorization level. It's 
// based on the fact that if the user could GET to the
// directory, then they belong here...
//
eBayISAPIAuthEnum DetermineAuthorization(CHttpServerContext *pCtxt);

class clseBayApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clseBayApp();
		~clseBayApp();

		// Setup
		void SetUp();

		// Mr. Cleaner Upper
		void CleanUp();

		void InitISAPI(unsigned char *pCtx);

		void display_IIS_server_down_page(clseBayApp *pApp); //new outage code
		friend bool IIS_Server_is_down(void); //new outage code

		//
		// Common Parse Error
		//
		void ParseError(int cause);

		// Send a string.
		void SendString(const char *pString);

		// Cookie manipulation.
		void SetCookie(COOKIE_ID id,
						   const char *pValue,
						   bool needCrypt,
						   time_t expires = NULL);

		void RemoveCookie(COOKIE_ID id);

		clseBayCookie *GetCookie();

        bool HasAdultCookie();

		void ValidateInternals();

		//

		// User Verification
		//
		//
		// Validate (and allow) or block certain actions such as bidding, selling, 
		// leaving feedback, posting to board, etc.
		// mpUser should be valid when this method is called.
		//
		bool	ValidateOrBlockAction(bool printHeader = false);

		//
		// Bids
		//
		void MakeBid(CEBayISAPIExtension *pThis,
					 int item,
//					 char *pUserId,
//					 char *pPass,
					 char *pMaxbid,
					 int quantity,
					 UAChoice uaChoice,
					 CHttpServerContext* pCtxt
					 );

		void AcceptBid(CEBayISAPIExtension *pThis,
					   int item,
					   char *pKey,
					   char *pUserId,
					   char *pPass,
					   char *pMaxbid,
					   int quantity,
					   UAChoice uaChoice,
					   CHttpServerContext* pCtxt
					   );

		void RetractBid(CEBayISAPIExtension *pThis,
						int item,
						char *pUserId,
						char *pPass,
						char *pReason);

		void CancelBid(CEBayISAPIExtension* pCtxt,
					   char *pSellerUserId,
					   char *pSellerPass,
					   int item,
					   char *pUserId,
					   char *pReason);

		void ViewBids(CEBayISAPIExtension* pCtxt,
					  int item);

		void ViewBidderWithEmails(CEBayISAPIExtension* pCtxt,
					  int item, char * pUserId, char * pPass);

		void ViewBidsDutchHighBidder(CEBayISAPIExtension* pCtxt,
						int item);

		void GetBidderEmails(CEBayISAPIExtension* pCtxt,
					 int item, int PageType);

		void ViewBidDutchHighBidderEmails(CEBayISAPIExtension* pCtxt,
					 int item, char * pUserId, char * pPass);

		//
		// Items
		//
		void Run(CEBayISAPIExtension *pServer,
				 char *pItemNo,
				 char *pRowNo = NULL,
				 time_t delta = 0,
				 CHttpServerContext* pCtxt = NULL);

		// Add to an item's description
		void VerifyAddToItem(CEBayISAPIExtension *pServer,
							 char *pItemNo,
							 char *pUserId,
							 char *pPass,
							 char *pAddition);

		void AddToItem(CEBayISAPIExtension *pServer,
					   char *pItemNo,
					   char *pUserId,
					   char *pPass,
					   char *pAddition);

		// Verify that the user wants to stop an 
		// auction
		void VerifyStop(CEBayISAPIExtension *pServer,
						char *pItemNo,
						char *pUserId,
						char *pPass);

		// Actually stop the auction
		void Stop(CEBayISAPIExtension *pServer,
				  char *pItemNo,
				  char *pUserId,
				  char *pPass);

		// Emit the "Super Featured" page
		void Featured(CEBayISAPIExtension *pServer);

		// Make the auction super featured
		void MakeFeatured(CEBayISAPIExtension *pServer,
						  char *pItemNo,
						  char *pUserId,
						  char *pPass,
						  char *pTypeSuper,
						  char *pTypeFeature);

		// Emit the "Change Category" page for an item
		void ChangeCategoryShow(CEBayISAPIExtension *pServer,
								int item, bool oldStyle);

		// Change an item's category
		void ChangeCategory(CEBayISAPIExtension *pServer,
							char *pUserId,
							char *pPass,
							int item,
							int newCategory);



		// Emit the "Enter a new item" page
		void NewItem(CEBayISAPIExtension *pServer, char *pItemNo, char *pCatNo);

		// Emit the "Enter a new item Quick Entry Form" page
		//  if item number is non-zero, seed the form with
		//  the properties of the item (useful for relisting)
		// Also can pass in the categoryid of the category in which to list the item
		void NewItemQuick(CEBayISAPIExtension *pServer, char *pItemNo, char *pCatNo);


		// Emit the "Enter a new item Quick Entry Form" page
		//  if item number is non-zero, seed the form with
		//  the properties of the item (useful for relisting)
		// Also can pass in the categoryid of the category in which to list the item
		void ListItemForSale(CEBayISAPIExtension *pServer, char *pItemNo, char *pCatNo, bool oldStyle);


		void BetterSeller(CEBayISAPIExtension *pServer, int ItemNo);

		// Verify the new item
		// nsacco 07/27/99 added new params for shipping and lang
		void VerifyNewItem(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pTitle,
						  char *pLocation,
						  char *pReserve,
						  char *pStartPrice,
						  char *pQuantity,
						  char *pDuration,
						  char *pBold,
						  char *pFeatured,
						  char *pSuperFeatured,
						  char *pPrivate,
						  char *pDesc,
						  char *pPicUrl,
						  char *pCategory1,
						  char *pCategory2,
						  char *pCategory3,
						  char *pCategory4,
						  char *pCategory5,
						  char *pCategory6,
						  char *pCategory7,
						  char *pCategory8,
						  char *pCategory9,
						  char *pCategory10,
						  char *pCategory11,
						  char *pCategory12,
						  char *pOldItemNo,
						  char *pOldKey,
						  UAChoice uaChoice,
						  char *pMoneyOrderAccepted,
						  char *pPersonalChecksAccepted,
						  char *pVisaMasterCardAccepted,
						  char *pDiscoverAccepted,
						  char *pAmExAccepted,
						  char *pOtherAccepted,
						  char *pOnlineEscrowAccepted,
						  char *pCODAccepted,
						  char *pPaymentSeeDescription,
						  char *pSellerPaysShipping,
						  char *pBuyerPaysShippingFixed,
						  char *pBuyerPaysShippingActual,
						  char *pShippingSeeDescription,
						  char *pShippingInternationally,
						  char *pShipToNorthAmerica,
						  char *pShipToEurope,
						  char *pShipToOceania,
						  char *pShipToAsia,
						  char *pShipToSouthAmerica,
						  char *pShipToAfrica,
						  int  siteId,
						  int  descLang,
						  CHttpServerContext* pCtxt,
						  char *pGiftIcon,
						  int  gallery,
						  char *pGalleryUrl,
						  int  countryId,
						  int  currencyId,
						  char *pZip
						);

		// Add the new item
		// nsacco 07/27/99 added new shipping params plus siteid and desclang.
		void AddNewItem(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPassword,
						  char *pItemNo,
						  char *pTitle,
						  char *pReserve,
						  char *pStartPrice,
						  char *pQuantity,
						  char *pDuration,
						  char *pLocation,
						  char *pBold,
						  char *pFeatured,
						  char *pSuperFeatured,
						  char *pPrivate,
						  char *pDesc,
						  char *pPicUrl,
						  char *pCategory,
						  char *pCryptedItemNo,
  						  char *pOldItemNo,
						  char *pOldKey,
						  char *pMoneyOrderAccepted,
						  char *pPersonalChecksAccepted,
						  char *pVisaMasterCardAccepted,
						  char *pDiscoverAccepted,
						  char *pAmExAccepted,
						  char *pOtherAccepted,
						  char *pOnlineEscrowAccepted,
						  char *pCODAccepted,
						  char *pPaymentSeeDescription,
						  char *pSellerPaysShipping,
						  char *pBuyerPaysShippingFixed,
						  char *pBuyerPaysShippingActual,
						  char *pShippingSeeDescription,
						  char *pShippingInternationally,
						  char *pShipToNorthAmerica,
						  char *pShipToEurope,
						  char *pShipToOceania,
						  char *pShipToAsia,
						  char *pShipToSouthAmerica,
						  char *pShipToAfrica,
						  int  siteId,
						  int  descLang,
						  CHttpServerContext* pCtxt,
						  char *pGiftIcon,
						  int  gallery,
						  char *pGalleryUrl,
						  int  countryId,
						  int  currencyId,
						  char *pZip
						);

		// Chech the returned item no
		bool ValidateItemNo(char* pItemNo, char* pCryptedItemNo, int UserId);

		// check whether it is a free relisting item
		bool IsRelistFree(const char* pItemNo, int Quantity, double dPrice);

		void RecomputeDutchBids(CEBayISAPIExtension *pServer,
				 char *pItemNo);
		void RecomputeChineseBids(CEBayISAPIExtension *pServer,
				 char *pItemNo);


	    void InsertPaginationControl(ItemList *pItemList,
                                             clsUser *pUser,
											 bool showCount,
											 int *currentItem, int *lastItem, int *startingItem,
											 int include,
                                             int startingPage, 
                                             int rowsPerPage,
											 int daysSince,			// ViewListedItems
											 int sortOrder,
											 char *pDLLCmd,
											 char *pRequester,		// ViewListedItems
											 char *pPass,			// ViewListedItems
											 char *pAllFlag,		// ViewBidItems
											 bool showCompleted);	// ViewBidItems

		//
		// Users
		//
		//
		// ViewListedItems
		//
		//	Views all listed items (same as old seller-list
		//	functionality
		//
		void ViewListedItems(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 bool completed,
							 ItemListSortEnum sort,
							 int daysSince,
							 bool include,
							 int startingPage,
							 int rowsPerPage);

		void ViewListedItemsWithEmails(CEBayISAPIExtension *pThis,
									char* pRequester,
									char* pPass,
									char* pUserId,
									bool completed,
									ItemListSortEnum sort,
									int daysSince,
							        int startingPage,
								    int rowsPerPage);

		void ViewListedItemsLinkButtons(CEBayISAPIExtension *pThis,
								 char *pUserId,
								 bool completed,
								 ItemListSortEnum sort,
								 int daysSince,
								 bool include,
							        int startingPage,
								    int rowsPerPage);


		//
		// ViewBidItems
		//
		//	Views all items which the user has bid on
		//
		void ViewBidItems(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 bool completed,
							 ItemListSortEnum sort,
							 bool allItems,
							 int startingPage,
							 int rowsPerPage);

		//
		// ViewAllItems
		//
		//	Views all items which the user has bid on
		//
		void ViewAllItems(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 bool completed,
							 ItemListSortEnum sort,
							 int daysSince);

		//
		// ViewFeedback
		//
		// Views all the feedback targeted to this user
		//
		void ViewFeedback(CEBayISAPIExtension *pThis,
							 char *pUserId,
							 int startingPage = STARTING_PAGE,
							 int itemsPerPage = FEEDBACK_ITEMS_PER_PAGE);

		//
		// LeaveFeedback
		//
		// Lets you leave feedback for a user
		//
		void LeaveFeedback(CEBayISAPIExtension *pThis,
						   char *pUser,
						   char *pPass,
						   char *pForUser,
						   char *pItemNo,
						   char *pType,
						   char *pComment,
						   char *pHostAddr,
						   int confirmNegative);

		void LeaveFeedbackShow(CEBayISAPIExtension *pThis,
							   char *pUserTo,
							   char *pUserFrom,
							   int itemNo);

		//
		// show the page where an user can respond to his/her feedback
		//
		void RespondFeedbackShow(CEBayISAPIExtension *pThis,
								 int Commentor,
								 time_t CommentDate,
								 int Commentee,
								 int startingPage,
								 int itemsPerPage);

		void RespondFeedback(CEBayISAPIExtension *pThis,
							 int CommentorId,
							 time_t CommentDate,
							 char* pCommentee,
							 char* pPassword,
							 char* pResponse,
							 int startingPage,
							 int itemsPerPage);

		//
		// show the page where an user can follow up on his/her feedback
		//
		void FollowUpFeedbackShow(CEBayISAPIExtension *pThis,
								  int Commentor,
								  time_t CommentDate,
								  int Commentee);

		void FollowUpFeedback(CEBayISAPIExtension *pThis,
							  int CommentorId,
							  time_t CommentDate,
							  char* pCommentee,
							  char* pPassword,
							  char* pFollowUp);
		//
		// ViewFeedbackLeft
		//
		void ViewFeedbackLeft(CEBayISAPIExtension *pThis,
							  char *pUser,
							  char *pPass);

		//
		// PersonalizedFeedbackLogin
		//
		void PersonalizedFeedbackLogin(CEBayISAPIExtension *pThis,
									   char *pUser,
									   int itemsPerPage);

		//
		// ViewPersonalizedFeedback
		//
		void ViewPersonalizedFeedback(CEBayISAPIExtension *pThis,
									char *pUser,
									char *pPass,
									int startingPage = STARTING_PAGE,
									int itemsPerPage = FEEDBACK_ITEMS_PER_PAGE);

		//
		// ChangeFeedbackOptions
		//
		void ChangeFeedbackOptions(CEBayISAPIExtension *pThis,
								   char *pUser,
								   char *pPass,
								   char *pOption,
								   int startingPage = STARTING_PAGE,
								   int itemsPerPage = 0);

		//
		// GetFeedbackScore
		//
		//	Used to remotely query a user's feedback
		//
		void GetFeedbackScore(CEBayISAPIExtension *pThis,
							  char *pUser);

		//
		// FeedbackForum
		//
		void FeedbackForum(CEBayISAPIExtension *pThis);

		//
		// RecomputeScore
		//
		void RecomputeScore(CEBayISAPIExtension *pThis,
							char *pUser);

		//
		// Temporary!
		//
		bool AddToBoard(CEBayISAPIExtension *pThis,
						char *pUser,
						char *pPass,
						char *pString,
						char *pBoardName,
						char *pLimit,
						char *pRedirectURL,
						bool FromEssayBoard=false);

		void ViewBoard(CEBayISAPIExtension *pThis,
					   char *pBoardName,
					   char *pTimeLimit);

		void ViewEssay(CEBayISAPIExtension *pThis,
					   char *pBoardName);

		void PastEssay(CEBayISAPIExtension *pThis);

		void RegisterByCountry(CEBayISAPIExtension *pServer,
								   CHttpServerContext* pCtxt,
								   int countryId,
								   int UsingSSL);

		void ConfirmByCountry(CEBayISAPIExtension *pServer,
								   CHttpServerContext* pCtxt,
								   int countryId,
								   int withCC);

		void TimeShow(CEBayISAPIExtension *pServer);


		void clseBayApp::ResendConfirmationEmail(CEBayISAPIExtension *pServer,
							char * pEmail);

		void RegisterPreview(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  int partnerId,
					  int siteId,	// nsacco 07/02/99
					  int coPartnerId,
					  int UsingSSL=0
					  );

		void Register(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  bool withcc,
					  int partnerId,
					  int siteId,	// nsacco 07/02/99
					  int coPartnerId,
					  int Using
					  );

		void RegisterConfirm(CEBayISAPIExtension *pServer,
					         CHttpServerContext *pCtxt,
							 char * Email,
							 char * pUserId,
							 char * pPass,
							 char * pNewPass,
							 char * pNewPass2,
							 int notify,
							 int countryId);

		void ShowRegistrationForm(CEBayISAPIExtension *pServer,
								      int countryId,
									  int UsingSSL=0);

		void UpdateCC(CEBayISAPIExtension *pServer,
					  CHttpServerContext *pCtxt,
					  char * pUserId,
					  char * pPass,
					  char * pccNumber,
					  char * pMonth,
					  char * pDay,
					  char * pYear);

		void UpdateCCConfirm(CEBayISAPIExtension *pServer,
							CHttpServerContext  *pCtxt,
							char * pUserId,
							char * pCCNumber,
							int    ExpDate);

		void RegisterCC(CEBayISAPIExtension *pServer,
						CHttpServerContext *pCtxt,
						char * pEmail,
						char * pOldPass,
						char * pUserId,
						char * pNewPass,
						char * pNewPassAgain,
						char * pUserName,
						char * pStreetAddr,
						char * pCityAddr,
						char * pStateProvAddr,
						char * pZipCodeAddr,
						char * pCountryAddr,
						char * pCC,
						char * pMonth,
						char * pDay,
						char * pYear,
						int	   UseForPayment,
						int    notify);


		void ChangeEmail(CEBayISAPIExtension *pServer);

		void ChangeEmailShow(CEBayISAPIExtension *pServer,
						char *pUserId,
						char *pPassword,
						char *pNewEmail);

		void ChangeEmailConfirm(CEBayISAPIExtension *pServer);
		
		void ChangeEmailConfirmShow(CEBayISAPIExtension *pServer,
							 char * pUserId,
							 char * pNewEmail,
							 char * pPass);

		void ChangePassword(CEBayISAPIExtension *pServer,
							 char * pUserId,
							 char * pPass,
							 char * pNewPass,
							 char * pNewPass2);

		void ChangeUserId(CEBayISAPIExtension *pServer,
							char	*pUserId);

		bool ValidateUserIdChange(char *pUserId,ostream *pStream);

		int  MailUserChangeUserIdNotice(char		*pOldUserId,
										char		*pNewUserId, 
										const char	*pEmail);

		void TellTheUserWhatHappen(char		*pOldUserId,
								   char		*pNewUserId,
								   ostream	*pStream); 

		void ChangeUserIdShow(CEBayISAPIExtension *pServer,
						char *pOldUserId,
						char *pPassword,
						char *pNewUserId);

		void UserQuery(CEBayISAPIExtension *pServer,
					   char * pUserId,
					   char * pPass,
					   char * pRequestedUserId);

		void ChangeRegistrationShow(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									int UsingSSL);

		void RegisterShow(CEBayISAPIExtension *pServer,
						   CHttpServerContext* pCtxt);

		void ChangeRegistration(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pName,
								char * pCompany,
								char * pAddress,
								char * pCity,
								char * pState,
								char * pOtherState,
								char * pZip,
								char * pCountry,
								int    countryId,
								char * pDayPhone,
								char * pNightPhone,
								char * pFaxPhone,
								char * pGender,
								int UsingSSL
								);

		void ChangeRegistrationPreview(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								char * pName,
								char * pCompany,
								char * pAddress,
								char * pCity,
								char * pState,
								char * pOtherState,
								char * pZip,
								int    countryId,
								char * pDayPhone,
								char * pNightPhone,
								char * pFaxPhone,
								char * pGender,
								int UsingSSL
								);

		void ChangePreferencesShow(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass,
									bool oldStyle);

		void ChangePreferences(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pPass,
								int interest_1,
								int interest_2,
								int interest_3,
								int interest_4
								);

		void RequestPassword(CEBayISAPIExtension *pServer,
							 char * pUserId);

		void AdminRequestPassword(CEBayISAPIExtension *pServer,
							 char * pUserId,
							 eBayISAPIAuthEnum authLevel);

		void ViewAccount(CEBayISAPIExtension *pServer,
						 char *pUserId,
						 char *pPass,
						 bool entire,
						 bool sinceLastInvoice,
						 int monthsBack,
						 LPTSTR pStartDate,
						 LPTSTR pEndDate);

		bool MyEbayRedirect(CEBayISAPIExtension *pServer,
					CHttpServerContext* pCtxt,
						 char *pUserId,
						 char *pPass,
						 char *pFirst,
						 /* char *pZone, */
						 int sellerSort,
						 int bidderSort,
						 int daysSince,
						 int prefFavo,
						 int prefFeed,
						 int prefBala,
						 int prefSell,
						 int prefBidd);

		void MyEbay(CEBayISAPIExtension *pServer,
					CHttpServerContext* pCtxt,
						 char *pUserId,
						 char *pPass,
						 char *pFirst,
						 /* char *pZone, */
						 int sellerSort,
						 int bidderSort,
						 int daysSince,
						 int prefFavo,
						 int prefFeed,
						 int prefBala,
						 int prefSell,
						 int prefBidd);

		void MyEbaySeller(CEBayISAPIExtension *pServer,
						 char *pUserId,
						 char *pPass,
						 int sort,
						 int daysSince);

		void MyEbayBidder(CEBayISAPIExtension *pServer,
						 char *pUserId,
						 char *pPass,
						 int sort,
						 int daysSince);

		void PayCoupon(CEBayISAPIExtension *pServer,
					   char *pUserId,
					   char *pPass,
					   char *pymtType);

		void RequestRefund(CEBayISAPIExtension *pServer,
					   char *pUserId,
						 char *pPass);

		void BetaConfirmationShow(CEBayISAPIExtension *pServer,
									char * pUserId,
									char * pPass);

		void BetaConfirmation(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pEmail,
								char * pPass,
								char * pName,
								char * pCompany,
								char * pAddress,
								char * pCity,
								char * pState,
								char * pZip,
								char * pCountry,
								char * pDayPhone,
								char * pNightPhone,
								char * pFaxPhone,
								char * pGender);

		void BetaConfirmationPreview(CEBayISAPIExtension *pServer,
								char * pUserId,
								char * pEmail,
								char * pPass,
								char * pName,
								char * pCompany,
								char * pAddress,
								char * pCity,
								char * pState,
								char * pZip,
								char * pCountry,
								char * pDayPhone,
								char * pNightPhone,
								char * pFaxPhone,
								char * pGender);

		void CreateAccount(CEBayISAPIExtension *pServer,
						   char *pUserId,
						   char *pPass);


		void GetUserEmail(CEBayISAPIExtension *pServer,
						   char *pUserId);

		//get user about me page 
		bool GetUserAboutMe(CEBayISAPIExtension *pServer,
							char *pUser,
							char *pRedirectURL);

		void MemberSearchShow(CEBayISAPIExtension *pServer);

		// Survey
		void ValidateUserForSurvey(CEBayISAPIExtension *pServer, int surveyID);
		bool GoToSurvey(CEBayISAPIExtension *pServer,
						   char *pUserId, char *pPassword, int surveyID, char *pRedirectURL);

		// Contact eBay
		void ContacteBay(CEBayISAPIExtension *pServer, int itemID);


		void ReturnUserEmail(CEBayISAPIExtension *pServer,
						   char *pRequestedUserId,
						   char *pRequestorUserId,
						   char *pRequestorEmail);

		//check item and display item info to allow seller to change it
		void GetItemInfo(CEBayISAPIExtension *pServer, 
										char *pItemNo, 
										char *pUserId,
										char *pPass,
										bool oldStyle);
	
		void UserItemVerification(CEBayISAPIExtension *pServer, 
										char *pItemNo);

		// nsacco 07/27/99 added new params
		bool UpdateItemInfo(CEBayISAPIExtension *pServer, 
			const char *pItemNo,
			const char *pUserId,
			const char *pPass,
			const char *pTitle, 	 
			const char *pDesc,
			const char *pPicUrl,
			const char *pCategory,
			const char *pMoneyOrderAccepted,
			const char *pPersonalChecksAccepted,
			const char *pVisaMasterCardAccepted,
			const char *pDiscoverAccepted,
			const char *pAmExAccepted,
			const char *pOtherAccepted,
			const char *pOnlineEscrowAccepted,
			const char *pCODAccepted,
			const char *pPaymentSeeDescription,
			const char *pSellerPaysShipping,
			const char *pBuyerPaysShippingFixed,
			const char *pBuyerPaysShippingActual,
			const char *pShippingSeeDescription,
			const char *pShippingInternationally,
			const char *pShipToNorthAmerica,
			const char *pShipToEurope,
			const char *pShipToOceania,
			const char *pShipToAsia,
			const char *pShipToSouthAmerica,
			const char *pShipToAfrica,
			int siteId,
			int descLang,
			char *pRedirectURL
			);

		// nsacco 07/27/99 added new params
		bool VerifyUpdateItem(const char *pUserId,
			const char *pPass,
			const char *pItemNo,
			const char *pTitle,
			const char *pDesc,
			const char *pPicURL,
			const char *pCategory1,
			const char *pCategory2,
			const char *pCategory3,
			const char *pCategory4,
			const char *pCategory5,
			const char *pCategory6,
			const char *pCategory7,
			const char *pCategory8,
			const char *pCategory9,
			const char *pCategory10,
			const char *pCategory11,
			const char *pCategory12,
			const char *pMoneyOrderAccepted,
			const char *pPersonalChecksAccepted,
			const char *pVisaMasterCardAccepted,
			const char *pDiscoverAccepted,
			const char *pAmExAccepted,
			const char *pOtherAccepted,
			const char *pOnlineEscrowAccepted,
			const char *pCODAccepted,
			const char *pPaymentSeeDescription,
			const char *pSellerPaysShipping,
			const char *pBuyerPaysShippingFixed,
			const char *pBuyerPaysShippingActual,
			const char *pShippingSeeDescription,
			const char *pShippingInternationally,
			const char *pShipToNorthAmerica,
			const char *pShipToEurope,
			const char *pShipToOceania,
			const char *pShipToAsia,
			const char *pShipToSouthAmerica,
			const char *pShipToAfrica,
			int siteId,
			int descLang
			);
			

		void ViewAliasHistory(CEBayISAPIExtension *pServer, 
							char *pUserId,
							char *pPass);

		void GetUserIdHistory(CEBayISAPIExtension *pServer,
						   char *pUserId);

		void ReturnUserIdHistory(CEBayISAPIExtension *pServer,
						   char *pRequestedUserId,
						   char *pRequestorUserId,
						   char *pRequestorEmail);

		void MultipleEmails(CEBayISAPIExtension *pServer, 
							 char *pRequestedUserIds,
							 char *pRequestorUserId,
							 char *pRequestorPass);

		void GetMultipleEmails(CEBayISAPIExtension *pServer);

		void GetUserByAlias(CEBayISAPIExtension *pServer,
						     char *pRequestedUserId,
							 char *pRequestorUserId,
							 char *pRequestorPass);
		//
		// Administration
		//

		// Add currency exchange rate by Admin
		void AdminAddExchangeRate(const char *login,
									const char *password,
									int			month,
									int			day,
									int			year,
									int			fromcurrency,
									int			tocurrency,
									const char *rate);

		// Admin Version of View Bids
		void AdminViewBids(CEBayISAPIExtension* pCtxt,
						   int item,
						   eBayISAPIAuthEnum authLevel);

		// Shill tool
		void AdminShillRelationshipsByItem(const char *details,
										  int item,
										  int limit,
										  eBayISAPIAuthEnum authLevel);

		void AdminShillRelationshipsByUsers(const char *details,
										   const char *userlist,	
										   int limit,
										   eBayISAPIAuthEnum authLevel);

		void AdminShillRelationshipsByFeedback(const char *details,
											  const char *user,
											  const char *left,
											  int count,
											  int age,
											  int limit,
											  eBayISAPIAuthEnum authLevel);

		
		void AdminShowBiddersSellers(const char *bidder, eBayISAPIAuthEnum authLevel);
		void AdminShowCommonAuctions(const char *userids, eBayISAPIAuthEnum authLevel);
		// Helper functions for shill tool etc.
		bool UseridListToUserVector(vector<char *> vUserIds, vector<clsUser *>& vUsers);
		void AdminGetShillCandidates(eBayISAPIAuthEnum authLevel);
		void AdminShowBiddersRetractions(eBayISAPIAuthEnum authLevel, int id, int limit);

		void AdminViewOldItem(CEBayISAPIExtension *pServer,
						   char *pItemNo,
						   eBayISAPIAuthEnum authLevel);

		// Admin Credit Batch
		void CreditBatch(CEBayISAPIExtension* pCtxt,
						 char *pString,
						 int how,
						 char *pPassword,
						 eBayISAPIAuthEnum authLevel);

		// New and Improved!
		void CreditBatch2(CEBayISAPIExtension* pCtxt,
						  char *pString,
						  int how,
						  char *pPassword,
						  eBayISAPIAuthEnum authLevel);

		void CreditDump(CEBayISAPIExtension* pCtxt,
						char *pUserId,
						char *pPass);

		void ItemCreditReq(CEBayISAPIExtension* pCtxt,
						   char *pUserId,
						   char *pPass,
						   char *pItemNo,
						   int	 moreCredits);

		void ChineseAuctionCreditReq(CEBayISAPIExtension* pCtxt,
									char *pItemNo,
									int   arc,
									int	  wasPaid,
									char *pAmt,
									int	  reason);

		void DutchAuctionCreditReq(	CEBayISAPIExtension* pCtxt,
									char *pItemNo,
									int   arc,
									int	  wasPaid1,
									char *pAmt1,
									int	  reason1,
									char *pEmail1,
									int	  wasPaid2,
									char *pAmt2,
									int	  reason2,
									char *pEmail2,
									int	  wasPaid3,
									char *pAmt3,
									int	  reason3,
									char *pEmail3,
									int	  wasPaid4,
									char *pAmt4,
									int	  reason4,
									char *pEmail4,
									int	  wasPaid5,
									char *pAmt5,
									int	  reason5,
									char *pEmail5,
									int	  moreCredits
									);

		void AccountingBatch(CEBayISAPIExtension* pCtxt,
							 char *pString,
							 int how,
							 char *pPassword,
							 eBayISAPIAuthEnum authLevel);


		// Admin Version of item information
		bool ItemInfo(CEBayISAPIExtension* pThis,
			             char *pAction,
			             char *pItemNo,
						 char *pTitle,
						 char *pQuantity,
						 char *pcEndTime,
						 char *pcEndTimeHour,
						 char *pcEndTimeMin,
						 char *pcEndTimeSec,
				         int   featured,		
				         int   superfeatured,
						 char *pDescription,
					     int  gallery,	 
					     int  galleryfeatured,	 
						 char *pGiftIcon,
						 char *pRedirectURL);

		//AdminVersion of View an Item info
        void ChangeItemInfo(CEBayISAPIExtension* pCtxt,
						char *pItemNo);

		//
		// User Search
		//
		//	Note the "how" is really a UserSearchTypeEnum, but
		//	our caller (AdminISAPI) doesn't know anything about
		//	that..
		//
		void UserSearch(CEBayISAPIExtension* pCtxt,
						char *pString,
						int how,
						eBayISAPIAuthEnum authLevel);

		//
		// User state changers
		//

		void AdminWarnUserShow(char *pUserId,
							   char *pPass,
							   char *pTarget,
							   int type,
							   char *pText,
							   eBayISAPIAuthEnum authLevel);

		void AdminWarnUserConfirm(char *pUserId,
								  char *pPass,
								  char *pTarget,
								  int type,
								  char *pText,
								  eBayISAPIAuthEnum authLevel);

		void AdminWarnUser(char *pUserId,
						   char *pPass,
						   char *pTarget,
						   int type,
						   char *pText,
						   char *pEmailSubject,
						   char *pEmailText,
						   eBayISAPIAuthEnum authLevel);

		void AdminSuspendUserShow(char *pUser,
								  char *pPass,
								  char *pTarget,
								  int type,
								  char *pText,
								  eBayISAPIAuthEnum authLevel);

		void AdminSuspendUserConfirm(char *pUserId,
									 char *pPass,
									 char *pTarget,
									 int type,
									 char *pText,
									 eBayISAPIAuthEnum authLevel);

		void AdminSuspendUser(char *pUserId,
							  char *pPass,
							  char *pTarget,
							  int type,
							  char *pText,
							  char *pEmailSubject,
							  char *pEmailText,
							  eBayISAPIAuthEnum authLevel);


		bool AdminSuspendUserNoShow(char *pUserId,
									char *pPass,
									char *pTarget,
									int type,
									char *pText,
									char *pEmailSubject,
									char *pEmailText,
									eBayISAPIAuthEnum authLevel);

		void AdminReinstateUserShow(char *pUser,
								    char *pPass,
								    char *pTarget,
								    int type,
								    char *pText,
								    eBayISAPIAuthEnum authLevel);

		void AdminReinstateUserConfirm(char *pUserId,
									   char *pPass,
									   char *pTarget,
									   int type,
									   char *pText,
									   eBayISAPIAuthEnum authLevel);

		void AdminReinstateUser(char *pUserId,
							    char *pPass,
							    char *pTarget,
							    int type,
							    char *pText,
							    char *pEmailSubject,
							    char *pEmailText,
							    eBayISAPIAuthEnum authLevel);

		void AdminReinstateUserInternal(char *pUserId,
									    char *pPass,
									    char *pTarget,
									    int type,
									    char *pText,
									    char *pEmailSubject,
									    char *pEmailText,
									    eBayISAPIAuthEnum authLevel);

		//void ReinstateUser(CEBayISAPIExtension* pCtxt,
		//				   char *pUserId,
		//				   eBayISAPIAuthEnum authLevel);

		void AdminResetReqEmailCount(CEBayISAPIExtension* pCtxt,
						 char *pUserId,
						 eBayISAPIAuthEnum authLevel);

		//inna - new reabalance account admin function
		void AdminRebalanceUserAccount(CEBayISAPIExtension* pCtxt,
						 char *pUserId,
						 eBayISAPIAuthEnum authLevel);
		//inna - new remove item admin function
        void AdminRemoveItem(CEBayISAPIExtension* pCtxt,
						char *pItemNo,
						eBayISAPIAuthEnum authLevel);
		//inna - wacko items admin page
        void WackoFlagChangeConfirm(CEBayISAPIExtension* pCtxt,
						char *pItemNo,
						bool wackoFlag,
						eBayISAPIAuthEnum authLevel);
		void WackoFlagChange(CEBayISAPIExtension* pCtxt,
						char *pItemNo,
						bool wackoFlag);

		void AdminResetReqUserCount(CEBayISAPIExtension *pCtxt,
			char *pUserId, eBayISAPIAuthEnum authLevel);


		void ConfirmUser(CEBayISAPIExtension* pCtxt,
						 char *pUserId,
						 eBayISAPIAuthEnum authLevel);

		void AdminMoveAuctionShow(CEBayISAPIExtension *pThis, 
								   char *pUserId,
								   char *pPass,
								   char *pItemIds,
								   int category,
								   int  emailsellers,
								   int  chargesellers,
								   char *pText,
								   eBayISAPIAuthEnum authLevel);

		void AdminMoveAuctionConfirm(CEBayISAPIExtension *pThis, 
									 char *pUserId,
									 char *pPass,
									 char *pItemId,
									 int category,
									 int  emailsellers,
									 int  chargesellers,
									 char *pText,
									 eBayISAPIAuthEnum authLevel);

		void AdminMoveAuction(CEBayISAPIExtension *pServer,
							  char *pUserId,
							  char *pPass,
							  char *pItemIds,
							  int emailsellers,
							  int chargesellers,
							  int emailbidders,
							  char *pText,
							  char *pSellerEmailSubjectTemplate,
							  char *pSellerEmailTemplate,
							  eBayISAPIAuthEnum authLevel);

		void AdminEndAuctionShow(CEBayISAPIExtension *pServer,
								 char *pUserId,
								 char *pPass,
								 char *pItemId,
								 int  suspended,
								 int  creditfees,
								 int  emailbidders,
								 int type,
								 int buddy,
								 char *pText,
								 eBayISAPIAuthEnum authLevel);

		void AdminEndAuctionConfirm(CEBayISAPIExtension *pServer,
									char *pUserId,
									char *pPass,
									char *pItemId,
									int  suspended,
									int  creditfees,
									int	 emailbiddes,
									int type,
									int buddy,
									char *pText,
									eBayISAPIAuthEnum authLevel);

		void AdminEndAuction(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 char *pPass,
							 char *pItemId,
							 int  suspended,
							 int  creditfees,
							 int  emailbidders,
							 int type,
							 int buddy,
							 char *pText,
							 char *pSellerEmailSubject,
							 char *pSellerEmailText,
							 char *pBidderEmailSubject,
							 char *pBidderEmailText,
							 char *pBuddyEmailAddress,
							 char *pBuddyEmailSubject,
							 char *pBuddyEmailText,
							 eBayISAPIAuthEnum authLevel);


		void AdminEndAllAuctionsShow(char *pUserId,
									 char *pPass,
									 char *pTargetUser,
									 int  suspended,
									 int  creditFees,
									 int  emailbidders,
									 int type,
									 int buddy,
									 char *pText,
									 eBayISAPIAuthEnum authLevel);

		void AdminEndAllAuctionsConfirm(char *pUserId,
										char *pPass,
										char *pTargetUser,
										int  suspended,
										int  creditFees,
										int	 emailbidders,
										int type,
										int buddy,
										char *pText,
										eBayISAPIAuthEnum authLevel);

		void AdminEndAllAuctions(char *pUserId,
								 char *pPass,
								 char *pTargetUser,
								 int suspended,
								 int creditfees,
								 int emailbidders,
								 int type,
								 int buddy,
								 char *pText,
								 char *pSellerEmailSubject,
								 char *pSellerEmailText,
								 char *pBidderEmailSubject,
								 char *pBidderEmailText,
								 char *pBuddyEmailAddress,
								 char *pBuddyEmailSubject,
								 char *pBuddyEmailText,
								 eBayISAPIAuthEnum authLevel);

#if 0
		void AdminBlockAuctionShow(CEBayISAPIExtension *pServer,
								 char *pUserId,
								 char *pPass,
								 char *pItemId,
								 int  suspended,
								 int  creditfees,
								 int  emailbidders,
								 int type,
								 int buddy,
								 char *pText,
								 eBayISAPIAuthEnum authLevel);

		void AdminBlockAuctionConfirm(CEBayISAPIExtension *pServer,
									char *pUserId,
									char *pPass,
									char *pItemId,
									int  suspended,
									int  creditfees,
									int	 emailbiddes,
									int type,
									int buddy,
									char *pText,
									eBayISAPIAuthEnum authLevel);

		void AdminBlockAuction(CEBayISAPIExtension *pServer,
							 char *pUserId,
							 char *pPass,
							 char *pItemId,
							 int  suspended,
							 int  creditfees,
							 int  emailbidders,
							 int type,
							 int buddy,
							 char *pText,
							 char *pSellerEmailSubject,
							 char *pSellerEmailText,
							 char *pBidderEmailSubject,
							 char *pBidderEmailText,
							 char *pBuddyEmailAddress,
							 char *pBuddyEmailSubject,
							 char *pBuddyEmailText,
							 eBayISAPIAuthEnum authLevel);

		bool ValidateBlockAuctionInput(char *pUserId,
									 char *pPass,
									 char *pItemId,
									 int suspended,
									 int creditFees,
									 int emailbidders,
									 int type,
									 int buddy,
									 char *pText);

		void BlockAuctionShow(char *pUserId,
							char *pPass,
							char *pItemId,
							int suspended,
							int creditFees,
							int emailbidders,
							int type,
							int buddy,
							char *pText);

		void BlockAuctionConfirm(char *pUserId,
							   char *pPass,
							   char *pItemId,
							   int suspended,
							   int creditFees,
							   int emailbidders,
							   int type,
							   int buddy,
							   char *pText);
#endif

		void RetractAllBids(CEBayISAPIExtension* pCtxt,
							char *pUserId,
							eBayISAPIAuthEnum authLevel,
							bool cautionToTheWind = false);

		void AdminCombineUsers(CEBayISAPIExtension* pCtxt,
							char *pOldUserId,
							char *pOldPass,
							char *pNewUserId,
							char *pNewPass,
							eBayISAPIAuthEnum authLevel);

		void AdminCombineUserConf(CEBayISAPIExtension* pCtxt,
							char *pOldUserId,
							char *pOldPass,
							char *pNewUserId,
							char *pNewPass,
							eBayISAPIAuthEnum authLevel);

		void AdminChangeEmail(CEBayISAPIExtension* pCtxt,
							char * pUserId);

		void AdminChangeEmailShow(CEBayISAPIExtension* pCtxt,
							char * pUserId,
							char * pNewEmail);

		void AdminChangeEmailConfirm(CEBayISAPIExtension* pCtxt,
							char * pUserId,
							char * pNewEmail,
							int	   Change);


		// Added by Charles
		void AdminChangeUserId(CEBayISAPIExtension *pServer,
							char *pOldUserId,
							char *pPassword,
							char *pNewUserId,
							int	 confirm,
							eBayISAPIAuthEnum authLevel);

		void AdminChangeUserIdShow(CEBayISAPIExtension *pServer,
							char	*pUserId,
							eBayISAPIAuthEnum authLevel);

		// Category Admin
		// Runner
		void CategoryAdminRun(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel);

		// category integrity checker
		void CategoryChecker(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel);

/* petra ------------------------------------
		// emit the category hierarchy page for viewing category details
		void ViewCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel);

		// emit the category detail page
		void VerifyUpdateCategory(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pCategory,
						  eBayISAPIAuthEnum authLevel,
						  int currencyId = Currency_USD);

		// updates category detail page
		void UpdateCategory(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pCategory,
						  char *pName,
						  char *pDesc,
						  char *pFileRef,
						  char *pFeaturedCost,
						  char *pAdult,
						  char *pExpired,
						  eBayISAPIAuthEnum authLevel
						  );


		// Emit the "Enter a new CategoryAdmin" page
		void NewCategory(CEBayISAPIExtension *pServer,
						    eBayISAPIAuthEnum authLevel,
							int currencyId = Currency_USD);

		// Verify the new CategoryAdmin
		void VerifyNewCategory(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pName,
						  char *pDesc,
						  char *pAdult,
						  char *pFeaturedCost,
						  char *pFileRef,
						  char *pCategory,
						  char *pAddAction,
						  eBayISAPIAuthEnum authLevel
						);

		// Add the new CategoryAdmin
		void AddNewCategory(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPassword,
						  char *pName,
						  char *pDesc,
						  char *pAdult,
						  char *pFeaturedCost,
						  char *pFileRef,
						  char *pCategory,
						  char *pAddAction,
						  eBayISAPIAuthEnum authLevel
						);

		void DeleteCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel
						);

		void MakeDelete(CEBayISAPIExtension *pServer,
							char *pUserId,
							char *pPassword,
							char *pCategory,
						  eBayISAPIAuthEnum authLevel
						);

		void MoveCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel
						);

		void MakeMove(CEBayISAPIExtension *pServer,
							char *pUserId,
							char *pPassword,
							char *pFromCategory,
							char *pToCategory,
						  eBayISAPIAuthEnum authLevel
						);

		void OrderCategory(CEBayISAPIExtension *pServer,
						  eBayISAPIAuthEnum authLevel
						);
---------------------------------------- petra */

		//
		// Statistics
		//
		void AdminViewDailyStats(CEBayISAPIExtension *pServer,
							 int	StartMon,
							 int	StartDay,
							 int	StartYear,
							 int	EndMon,
							 int	EndDay,
							 int	EndYear,
							 char *pEmail,
							 char *pPass);

		// print a protion of the data
		void PrintDailyStatsPortion(int Items, int Bids, double Dollars);


		void FormatString(int Value, char* pFormatedValue);
		void FormatString(double Value, char* pFormatedValue);

		//
		// Finance
		//
		void AdminViewDailyFinance(CEBayISAPIExtension *pServer,
							 int	StartMon,
							 int	StartDay,
							 int	StartYear,
							 int	EndMon,
							 int	EndDay,
							 int	EndYear,
							 char *pEmail,
							 char *pPass);

		// Get and show the daily finance
		//
		void GetAndShowDailyFinance(time_t StartTime, time_t EndTime);

		// Print a single row finance data
		void PrintAFinanceRow(
						char*	pColor,
						char*	pDate,
						double	Insertion,
						double	Bold,
						double	CatFeature,
						double	SuperFeature,
						double	GiftIcon,
						double	Gallery,
						double	FeatureGallery,
						double	FinalValue,
						double	CourtesyCredit,
						double	RelistingCredit,
						double	NoSaleCredits,
						double	OtherCRDR);

		// announcement admin functions
		void AdminAnnouncement(CEBayISAPIExtension *pServer,
							eBayISAPIAuthEnum authLevel,
						  int SiteId,
						  int PartnerId);

		// update announcements
		void UpdateAnnouncement(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pId,
						  char *pLoc,
						  eBayISAPIAuthEnum authLevel,
						  char *pSiteId,
						  char *pPartnerId);

		void AddAnnouncement(CEBayISAPIExtension *pServer,
						  char *pUserId,
						  char *pPass,
						  char *pId,
						  char *pLoc,
						  char *pCode,
						  char *pDesc,
						  eBayISAPIAuthEnum authLevel,
						  char *pSiteId,
						  char *pPartnerId
							);

		// User Survey
		void SurveyResponse(CEBayISAPIExtension* pCtxt,
						    char *pUserId,
						    char *pPassword,
						    char *pSurveyId,
						    char *pQuestionId,
						    char *pResponse);

		// Redirect + cookie
		void RedirectEnter(CEBayISAPIExtension* pServer,
						   CHttpServerContext* pCtxt,
						   char *pLocation,
						   char *pPartnerName);

		// Cobranding management.

		void ShowCobrandPartners(eBayISAPIAuthEnum authLevel, int siteId);	// nsacco 06/21/99

		void RewriteCobrandHeaders(eBayISAPIAuthEnum authLevel);

		void ShowCobrandHeaders(eBayISAPIAuthEnum authLevel, int partnerId, int siteId);
		void ChangeCobrandHeader(eBayISAPIAuthEnum authLevel,
								const char *pNewDescription,
								int isHeader,
								int pageType,
								int partnerId,
								int pageType2,
								int siteId);
		// nsacco 06/21/99 added siteId and pParsedString
		void CreateCobrandPartner(eBayISAPIAuthEnum authLevel,
			const char *pName, const char *pDesc, int siteId, const char *pParsedString);

		// Cobrand
		PageEnum GetCurrentPage() { return mePage; }
		void SetCurrentPage(PageEnum ePage) {mePage = ePage;}

		// eMail auction to friend stuff		
		void ShowEmailAuctionToFriend(CEBayISAPIExtension *pThis,
							  int item);

		void EmailAuctionToFriend(CEBayISAPIExtension *pThis,
							  int item,
							  char *userid,
							  char *password,
							  //char *friendname,
							  char *email,
							  char *message,
							  char *htmlenable);	
		
		void AdminInvalidateList(CEBayISAPIExtension *pThis,
							char *pUser, int code);
		//
		// Bulletin Board Admin
		//
		void AdminBoardChangeShow(CEBayISAPIExtension *pServer,
								  const char *pName,
								  eBayISAPIAuthEnum authLevel);

		void AdminBoardChange(CEBayISAPIExtension *pServer,
							  const char *pBoardName,
							  const char *pBoardShortName,
							  const char *pBoardShortDesc,
							  const char *pBoardPicture,
							  const int maxPostCount,
							  int maxPostAge,
							  const char *pBoardDesc,
							  int boardPostable,
							  int boardAvailable,
							  eBayISAPIAuthEnum authLevel);

		// Login Dialog
		void LoginDialog(char *Action,
						 int Paris, 
						 clsNameValuePair* pNameValues,
						 bool FiledsOnly=false,
						 LoginTypeEnum LoginType=eLoginGetEmail);

		//
		// Cookies
		//
		bool DropUserIdCookie(char* pUserId, char* pPassword, CHttpServerContext* pCtxt);
		void RemoveUserIdCookie(CHttpServerContext* pCtxt, bool Success);
		bool RemoveACookie(CHttpServerContext* pCtxt, int Id);

		void PassRecognizer(CEBayISAPIExtension *pCtxt,									  									  								      
										  char *userid,
										  int code,
			  							  char *pHostAddr);	

		void ChangeSecretPassword(CEBayISAPIExtension *pCtxt,									  									  								      
										  char *pass);

		void ChangePasswordCrypted(CEBayISAPIExtension *pServer,
								 char * pUserId,
								 char * pPass,
								 char * pNewPass,
								 char * pNewPass2);

		void RegisterLinkButtons(CEBayISAPIExtension *pThis,
								   char *pUserid,
								   char *pPassword,
								   int pHomepage,
								   int pMypage,
								   char *pUrls);


		void OptinLogin(CEBayISAPIExtension *pThis,
								   char *pUserid,
								   char *pPassword);
/*
		void OptinChange(CEBayISAPIExtension *pThis,
			char *pUserid,
			int ChangesToAgreementOption,
			int ChangesToPrivacyOption,
			int TakePartInSurveysOption,
			int SpecialOfferOption,
			int EventPromotionOption,						
			int NewsletterOption,
			int EndofAuctionOption,
			int BidOption,
			int OutBidOption,
			int ListOption,
			int DailyStatusOption);
*/
		void OptinConfirm(CEBayISAPIExtension *pThis,
						    char *pUserid,
							int fChangesToAgreementOption,
							int fChangesToPrivacyOption,
							int fTakePartInSurveysOption,
							int fSpecialOfferOption, 
							int fEventPromotionOption,
							int fNewsletterOption,
							int fEndofAuctionOption,
							int fBidOption,
							int fOutBidOption,
							int fListOption,
							int fDailyStatusOption);

		void UserAgreementAccept(CEBayISAPIExtension *pThis,
								   char *pUserId,
								   char *pPassword,
								   bool agree,
								   bool notify);

		void RegistrationAcceptAgreement(CEBayISAPIExtension *pThis,
							 	   bool agree,
								   bool notify,
								   int  countryId);

		void CCRegistrationAcceptAgreement(CEBayISAPIExtension *pThis,
							 	   bool agree,
								   bool notify);

		void AdultLoginShow(int whichText = 0);

		void AdultLogin(char *pUserId,
			char *pPassword);

		void ShowAdultLogin(char *pUserId,
			char *pPassword);
		
		// kaz: 4/15/99: Support for Police Badge T&C page
		void PoliceBadgeLogin(char *pUserId, char *pPassword, bool agree);
		void PoliceBadgeAgreementForSelling(char *pUserId, char *pPass);

		// About Me pages
		// Comments indicate which files the source is in.

		// UserPageView 
        void ViewUserPage(char *pUserId, 
						  int   page = 0);

		// UserPageRemove 
        void RemoveUserPage(char *pUserId,
							char *pPassword, 
							int   page = 0,
							bool  display = false);

		// UserPageCategorize 
        void CategorizeUserPage(char *pUserId,
								char *pPassword,
								char *pTitle,
								bool  remove,
								int   category,
								int   page = 0);

		// UserPageLogin 
		void UserPageLogin(CEBayISAPIExtension *pThis,
 						   char *pUserId,
						   char *pPassword);

		void UserPageAcceptAgreement(CEBayISAPIExtension *pThis,
						   char *pUserId,
						   char *pPassword,
						   int	 agree,
						   int	 notify);

		void ProcessUserPageLogin(CEBayISAPIExtension *pThis,
						   char *pUserId,
						   char *pPassword);

		// UserPagePreview 
		void UserPageGoToHTMLPreview(CEBayISAPIExtension *pThis,
									 char *pUserId,
								     char *pPassword,
								     char *pHTML,
									 bool  htmlSupplied = true,
								     int   page = 0);

		void UserPageGoToTemplatePreview(CEBayISAPIExtension *pThis,
									char *pUserId,
		 						    char *pPassword,
									TemplateElements *elements);

		// UserPageHTMLEdit
		void UserPageEditFromText(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									char *pText,
									int   page = 0);
		void UserPageEditFromElements(CEBayISAPIExtension *pThis,
									  char *pUserId,
									  char *pPassword,
									  TemplateElements *elements);

		void UserPageShowConfirmHTMLEditingChoices(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									char *pHTML,
									UserPageEditingEnum which);

		void UserPageConfirmHTMLEditingChoice(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									char *pHTML,
									char *pActionButton,
									UserPageEditingEnum which);

		// UserPageTemplateEdit
		void UserPageSelectTemplateElements(CEBayISAPIExtension *pThis,
									char *pUserId,
								    char *pPassword,
								    TemplateElements *elements);

		void UserPageSelectTemplateStyles(CEBayISAPIExtension *pThis,
									char *pUserId,
				 					char *pPassword,
									TemplateElements *elements,
									bool writeHeader);

		void UserPageShowConfirmTemplateEditingChoices(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									TemplateElements *elements,
									UserPageEditingEnum which);

		// UsePageSave
        void SaveUserPage(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									char *pHTML,
									int   page);

        void SaveUserPage(CEBayISAPIExtension *pThis,
									char *pUserId,
									char *pPassword,
									TemplateElements *elements,
									int   page);

		// Top Seller
		void ShowTopSellerStatus(CEBayISAPIExtension *pServer, 
			char *pUserId,
			eBayISAPIAuthEnum authLevel);
		void SetTopSellerLevelConfirmation(CEBayISAPIExtension *pServer, 
			char *pUserId,
			int level,
			eBayISAPIAuthEnum authLevel);
		void SetTopSellerLevel(CEBayISAPIExtension *pServer, 
			char *pUserId,
			int level,
			eBayISAPIAuthEnum authLevel);
		void SetMultipleTopSellers(CEBayISAPIExtension *pServer, 
			char *text,
			int level,
			eBayISAPIAuthEnum authLevel);
		void ShowTopSellers(CEBayISAPIExtension *pServer, 
			int level,
			eBayISAPIAuthEnum authLevel);

		void PowerSellerRegisterShow(CEBayISAPIExtension *pServer, 
			char *pUserId, char *pPass);
		
		void PowerSellerRegister(CEBayISAPIExtension *pServer, 
			char *pUserId, char *pPass, bool agree);

		int MailSupportUserDelinePowerSellerAgreement(clsUser *pUser);
													  
		

		//
		// Gift Alert
		//
		void ViewGiftAlert(CEBayISAPIExtension *pThis,
						   char *pItemNo,
						   char *pUserId);

		void GiftAlertShow(char *pUserId, clsItem *pItem);

		void RequestGiftAlert(CEBayISAPIExtension *pThis,
							  char *pItemNo,
							  char *pUserId);

		void RequestGiftAlertShow(char *pUserId, clsItem *pItem);

		void SendGiftAlert(CEBayISAPIExtension *pThis,
						   char *pUserId,
						   char *pPass,
						   char *pFromName,
						   char *pItemNo,
						   char *pToName,
						   char *pDestEmail,
						   char *pMessage,
						   int occasion,
						   char *pOpenMonth,
						   char *pOpenDay,
						   char *pOpenYear);

		void ViewGiftCard(CEBayISAPIExtension *pThis,
						  char *pFromUserId,
						  char *pFromName,
						  char *pToName,
						  char *pItemNo,
						  int occasion,
						  char *pOpenDate);

		void GetAndShowGiftCard(clsUser *pFromUser,
								char *pFromName,
								char *pToName,
								int item,
								clsGiftOccasion *pOccasion,
								time_t openDate);

		void ViewGiftItem(CEBayISAPIExtension *pThis,
						  char *pFromUserId,
						  char *pFromName,
						  char *pItemNo,
						  int occasion,
						  char *pOpenDate);

		void GetAndShowGiftItem(clsUser *pFromUser,
								char *pFromName,
								int item,
								clsGiftOccasion *pOccasion,
								time_t openDate);

		void SendQueryEmail(CEBayISAPIExtension *pServer,
						   char *pUserId,
						   char *pPass,
						   char *pSubject,
						   char *pMessage,
						   int MailDestination);
		
		bool SendQueryEmailShow(CEBayISAPIExtension *pServer,
								char *pSubject,
								char *pRedirectURL);

		// Report questionable items to eBay support
		void ReportQuestionableItem(CEBayISAPIExtension *pServer,
						   char *pUserId,
						   char *pPass,
						   char *pItemType,
						   int   itemID,
						   char *pMessage);
		
		void ReportQuestionableItemShow(CEBayISAPIExtension *pServer,
							int itemID);



		// 
		// Notes
		//
		void AdminAddNoteAboutUserShow(CEBayISAPIExtension *pThis,
									   char *pUser,
									   eBayISAPIAuthEnum authLevel);

		void AdminAddNoteAboutUser(CEBayISAPIExtension *pThis, 
								   char *pUserid,
								   char *pPass,
								   char *pAboutUser,
								   char *pSubject,
								   int type,
								   char *pText,
								   eBayISAPIAuthEnum authLevel);

		void AdminAddNoteAboutItemShow(CEBayISAPIExtension *pThis,
									   char *pItem,
									   eBayISAPIAuthEnum authLevel);

		void AdminAddNoteAboutItem(CEBayISAPIExtension *pThis, 
								   char *pUserid,
								   char *pPass,
								   char *pAboutItem,
								   char *pSubject,
								   int type,
								   char *pText,
								   eBayISAPIAuthEnum authLevel);

		void AdminShowNoteShow(CEBayISAPIExtension *pThis, 
							   char *pUserid,
							   char *pPass,
							   char *pAboutFilter,
							   int typeFilter, 
							   eBayISAPIAuthEnum authLevel);

		void AdminShowNote(CEBayISAPIExtension *pThis, 
						   char *pUserid,
						   char *pPass,
						   char *pAboutFilter,
						   int typeFilter,
						   eBayISAPIAuthEnum authLevel);

		// Routines for testing the location-related routines
		void LocationsCompareZipToAC(CEBayISAPIExtension* pCtxt, char* zip, int ac);
		void LocationsCompareZipToState(CEBayISAPIExtension* pCtxt, char* zip, char* state);
		void LocationsCompareStateToAC(CEBayISAPIExtension* pCtxt, char* state, int ac);
		void LocationsCompareZipToCity(CEBayISAPIExtension* pCtxt, char* zip, char* city);
		void LocationsCompareCityToAC(CEBayISAPIExtension* pCtxt, char* city, int ac);

		void LocationsIsValidZip(CEBayISAPIExtension* pCtxt, char* zip);
		void LocationsIsValidAC(CEBayISAPIExtension* pCtxt, int ac);
		void LocationsIsValidCity(CEBayISAPIExtension* pCtxt, char* city);

		void LocationsDistanceZipAC(CEBayISAPIExtension* pCtxt, char* zip, int ac);
		void LocationsDistanceZipZip(CEBayISAPIExtension* pCtxt, char* zip1, char* zip2);
		void LocationsDistanceACAC(CEBayISAPIExtension* pCtxt, int ac1, int ac2);

		// Gallery admin
		void AdminGalleryItemView(CEBayISAPIExtension *pServer,
			int	itemId,
			eBayISAPIAuthEnum authLevel);
		void AdminGalleryItemDelete(CEBayISAPIExtension *pServer,
            CHttpServerContext *pCtxt,
			int	itemId,
			eBayISAPIAuthEnum authLevel);
		void AdminGalleryItemDeleteConfirm(CEBayISAPIExtension *pServer,
			int	itemId,
			eBayISAPIAuthEnum authLevel);
									  
		void DisplayGalleryImagePage(CEBayISAPIExtension* pThis,
					 				 int   item);

		void EnterNewGalleryImage(CEBayISAPIExtension* pThis,
								  char *pUserId,
								  char *pPassword,
								  int   item);

		void FixGalleryImage(CEBayISAPIExtension* pThis,
			  				 char *pUserId,
	  					     char *pPassword,
							 int   item,
							 char *pURL);

		void Up4SaleTestPassword(char *pUserId,
								 char *pPassword);

		//iEscrow functions
		void IEscrowLogin(CEBayISAPIExtension *pServer,
							char *pItemNo, char *ptype, int bidderno);
		void IEscrowShowData(CEBayISAPIExtension *pServer,
							char *pUserid,
							char *pPass,
							char *pItemNo,
							char *ptype,
							int   bidderno);

		void IEscrowSendData(CEBayISAPIExtension *pServer,
						 	 char *pPartyOne,
							 char *pItemNo,
							 char *ptype,
							 int   Qty,
							 char *pSecondParty,
							 int   bidderno);



		// -------------------------------------------------------

		//
		// Deadbeats
		//
		void ViewDeadbeatUser(CEBayISAPIExtension *pThis,
							  char *pDeadbeatUserId);

		void ViewDeadbeatUsers(CEBayISAPIExtension *pThis);

		void DeleteDeadbeatItem(CEBayISAPIExtension *pThis,
								char *pSellerUserId,
								char *pBidderUserId,
								int itemNumber,
								int confirm);
		void DeleteDeadbeatItemConfirm(char *pSellerUserId,
									   char *pBidderUserId,
									   int itemNumber);


		int	SendDeadbeatEmail(int nDeadbeatId,
							  int nItemId, 
							  char *pTitle,
							  char *pPassword,
							  eBayISAPIAuthEnum authLevel);

		
		// check for firearms listings
		bool CheckForFirearmsListing(int nCategory);

		void ShowBidMailStatus(CEBayISAPIExtension *pThis, 
									   char *bidtype, bool oldstatus, bool newstatus,
									   eBayISAPIAuthEnum authLevel);

		void InstallNewMailMachineList(CEBayISAPIExtension *pThis, 
							   char *machines, int poolType,
							   eBayISAPIAuthEnum authLevel);


		void ShowMailMachineStatus(CEBayISAPIExtension *pThis, 
								   eBayISAPIAuthEnum authLevel);

		void ToggleMailMachineBidStatus(CEBayISAPIExtension *pThis, 
								   int bidType, int state,
								   eBayISAPIAuthEnum authLevel);

		void DrawMailMachineStatus(char *host);

		//----------------------
		// Legal buddies
		void AdminAddScreeningCriteria(CEBayISAPIExtension* pThis,
											CategoryId categoryid, 
											eBayISAPIAuthEnum authLevel);

		// Add the legal buddy item
		void AdminAddScreeningCriteriaShow(CEBayISAPIExtension* pThis,
												CategoryId categoryid,
												FilterId filterid,
												MessageId messageid,
												int action,
												eBayISAPIAuthEnum authLevel);

		// Select what category we want to see the Screening criteria on
		void AdminViewScreeningCriteria(CEBayISAPIExtension* pThis,
											eBayISAPIAuthEnum authLevel);

		// Show the current Screening Config on the selected Category
		void AdminViewScreeningCriteriaShow(CEBayISAPIExtension* pThis,
												CategoryId categoryid,
												eBayISAPIAuthEnum authLevel);

		// Add a message to the db
		void AdminAddMessage(CEBayISAPIExtension* pThis,
							 int action,
							 MessageId messageid,
							 eBayISAPIAuthEnum authLevel);
	
		// Modify or Delete a message 
		void AdminModifyMessage(CEBayISAPIExtension* pThis,
								int action,
								eBayISAPIAuthEnum authLevel);
	
		// Add a message to the db
		void AdminAddMessageShow(CEBayISAPIExtension* pThis,
									int	action,
									MessageId messageid,
									LPSTR pName,
									LPSTR pMessage,
									MessageType message_type,
									eBayISAPIAuthEnum authLevel);

		// Add a filter to the db
		void AdminAddFilter(CEBayISAPIExtension* pThis,
							int action, 
							FilterId filterid,
						    eBayISAPIAuthEnum authLevel);


		// Modify or Delete a filter 
		void AdminModifyFilter(CEBayISAPIExtension* pThis,
								int action,
								eBayISAPIAuthEnum authLevel);
	
		// Add a filter to the db
		void AdminAddFilterShow(CEBayISAPIExtension* pThis,
									int	action,
									FilterId messageid,
									LPSTR pName,
									LPSTR pExpression,
									ActionType action_type,
									NotifyType notify_type,
									MessageId blocked_message,
									MessageId flagged_message,
									MessageId filter_email_text,
									MessageId buddy_email_text,
									LPSTR pFilterEmailAddress,
									LPSTR pBuddyEmailAddress,
									eBayISAPIAuthEnum authLevel);

		// View a blocked item
		void AdminViewBlockedItem(CEBayISAPIExtension *pServer,
									LPSTR pItemNo, LPSTR pRowNo,
									time_t delta,
									eBayISAPIAuthEnum authLevel,
									CHttpServerContext* pCtxt);

		//Gurinder - 04/30/99
		void AdminReInstateItemLogin(char* pItemNo);
		void AdminReInstateItem(CEBayISAPIExtension *pCtxt, char *pItemNo,
								 eBayISAPIAuthEnum authLevel,
								 char* pUserId, char* pPass);
		// gurinder's code ends here

		void AdminReinstateAuctionShow(int action,
										char *pUser,
								 		char *pPass,
								 		char *pItem,
								 		int type,
								 		char *pText,
								 		eBayISAPIAuthEnum authLevel);
		
		void AdminReinstateAuctionConfirm(int action,
											char *pUserId,
											char *pPass,
											char *pItem,
											int type,
											char *pText,
							   				eBayISAPIAuthEnum authLevel);

		void AdminReinstateAuction(int action,
									char *pUserId,		
									char *pPass,
									char *pItem,
									int type,
									char *pText,
									char *pEmailSubject,
									char *pEmailText,
									eBayISAPIAuthEnum authLevel);


		bool ValidateReinstateAuctionInput(char *pUser,
										   char *pPass,
										   char *pItemNo,
										   int type,
										   char *pText);

		void AdminUnflagUserShow(char *pUser,
								 char *pPass,
								 char *pTarget,
								 int type,
								 char *pText,
								 eBayISAPIAuthEnum authLevel);

		void AdminUnflagUserConfirm(char *pUserId,
									char *pPass,
									char *pTarget,
									int type,
									char *pText,
									eBayISAPIAuthEnum authLevel);

		void AdminUnflagUser(char *pUserId,
							 char *pPass,
							 char *pTarget,
							 int type,
							 char *pText,
							 char *pEmailSubject,
							 char *pEmailText,
							 eBayISAPIAuthEnum authLevel);

		
		//
		// PERSONAL SHOPPER FUNCTIONS
		//
		void PersonalShopperViewSearches(CEBayISAPIExtension *pExt,
										 char * pUserId,
										 char * pPass,
										 char * pAgree);

		void PersonalShopperAddSearch(CEBayISAPIExtension *pExt,
							 char * pUserId,
							 char * pPass,
							 char * pQuery,
							 char *	pSearchDesc,
							 char * pMinPrice,
							 char * pMaxPrice,
							 char *	EmailFrequency,
							 char *	EmailDuration,
							 char *	pRegId,
							 char * pAgree);

		void PersonalShopperSaveSearch(CEBayISAPIExtension *pExt,
							 char * pUserId,
							 char * pPass,
							 char * pQuery,
							 char *	pSearchDesc,
							 char * pMinPrice,
							 char * pMAxPrice,
							 char *	EmailFrequency,
							 char *	EmailDuration,
							 char *	pRegId);

		void PersonalShopperDeleteSearchView(CEBayISAPIExtension *pExt,
							 char * pUserId,
							 char * pPass,
							 char * pQuery,
							 char *	pSearchDesc,
							 char * pMinPrice,
							 char * pMAxPrice,
							 char *	EmailFrequency,
							 char *	EmailDuration,
							 char *	pRegId);

		void PersonalShopperDeleteSearch(CEBayISAPIExtension *pExt,
							 char * pUserId,
							 char * pPass,
							 char *	pRegId);

		// Admin Tool to pick out special items
		void AdminSpecialItemsTool(CEBayISAPIExtension *pThis,
								   eBayISAPIAuthEnum authLevel);

		void AdminSpecialItemAdd(CEBayISAPIExtension *pThis,
								 char *pItemNo, 
								 int kind,
								 eBayISAPIAuthEnum authLevel);

		void AdminSpecialItemDelete(CEBayISAPIExtension *pThis,
								    char *pItemNo,
								    eBayISAPIAuthEnum authLevel);

		void AdminSpecialItemFlush(CEBayISAPIExtension *pThis,
								   eBayISAPIAuthEnum authLevel);

		//
		// Cobrand Ads
		//
		void AdminAddCobrandAdShow(CEBayISAPIExtension *pThis,
								   eBayISAPIAuthEnum authLevel);

		void AdminAddCobrandAdConfirm(CEBayISAPIExtension *pThis,
									  char *pName,
									  char *pText,
									  eBayISAPIAuthEnum authLevel);

		void AdminAddCobrandAd(CEBayISAPIExtension *pThis,
							   char *pName,
							   char *pText,
							   eBayISAPIAuthEnum authLevel);

		void EmitHTMLSiteNameOptions(const char *pOptionMenuName,
									 vector<clsSite *> *pvSites);

		void EmitHTMLPartnerNameOptions(const char *pOptionMenuName,
										vector<clsPartner *> *pvPartners);

		void EmitHTMLPageTypeOptions(const char *pOptionMenuName,
									 bool primary);

		void EmitAdNameList(char *pListName, vector<clsAd *> *pvAds);

		void AdminSelectCobrandAdSiteShow(CEBayISAPIExtension *pThis,
										  eBayISAPIAuthEnum authLevel);

		void AdminSelectCobrandAdPartnerAndPageShow(CEBayISAPIExtension *pThis,
													int adId,
													int siteId,
													eBayISAPIAuthEnum authLevel);

		void AdminAddCobrandAdToSitePageConfirm(CEBayISAPIExtension *pThis,
												int adId,
											    int siteId,
											    int partnerId,
											    PageTypeEnum pageType1,
											    PageTypeEnum pageType2,
											    eBayISAPIAuthEnum authLevel);

		void AdminAddCobrandAdToSitePage(CEBayISAPIExtension *pThis,
										 int adId,
										 int siteId,
										 int partnerId,
										 PageTypeEnum pageType1,
										 PageTypeEnum pageType2,
										 int contextSensitiveValue,
										 eBayISAPIAuthEnum authLevel);
		void AOLRegisterShow(CEBayISAPIExtension *pExt,
								char *pAOLName);
	
		// nsacco 07/07/99 - added siteId and coPartnerId
		void AOLRegisterPreview(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  LPTSTR pNewPass,
					  LPTSTR pNewPass2,
					  int nPartnerID,
					  int siteId = 0,
					  int coPartnerId = 0,	// TODO - should this be 500?
					  int UsingSSL=0,
					  int nVerify=0);

		// nsacco 07/07/99 - added siteId and coPartnerId
		void AOLRegisterUserID(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  LPTSTR pNewPass,
					  LPTSTR pNewPass2,
					  int nPartnerID,
					  int siteId = 0,
					  int coPartnerId = 0,	// TODO - should this be 500?
					  int UsingSSL=0,
					  int nVerify=0);

		// nsacco 07/07/99 - added siteId and coPartnerId
		void AOLRegisterUserAgreement(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  LPTSTR pNewPass,
					  int nPartnerID,
					  int siteId = 0,
					  int coPartnerId = 0,	// TODO - should this be 500?
					  int UsingSSL=0,
					  int nVerify=0);

		// nsacco 07/07/99 - added siteId and coPartnerId
		void AOLRegisterUserAcceptAgreement(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  LPTSTR pNewPass,
					  int nAccept,
					  int nNotify,
					  int nAgreementQ1,
					  int nAgreementQ2,
					  int nPartnerID,
					  int siteId = 0,
					  int coPartnerId = 0,	// TODO - should this be 500?
					  int UsingSSL=0,
					  int nVerify=0);

	void AOLRegisterConfirm(CEBayISAPIExtension *pServer,
								int nConfirmation,	
								char * pUserId,
								int nVerify);

	void AOLRegisterComplete(CEBayISAPIExtension *pServer,
					  char * pUserId);

	bool AOLVerifyConfirmation(LPTSTR pUserId, int nConfirmation, 
								ostream *pTheStream);

	bool AOLValidateRegistration(int * pUVrating,
									int * pUVdetail,
									char* pEmail, 
									char* pName, 
									char* pAddress, 
									char* pCity, 
									char* pState, 
									char* pZip, 
									char* pCountry, 
									int countryId,			// PH 05/03/99 
									char* pDayPhone1,
									char* pDayPhone2,
									char* pDayPhone3,
									char* pDayPhone4,
									ostream *pTheStream) const;
		
	bool AOLValidateUserID(	LPTSTR pEmail, 
								LPTSTR pUserId, 
								LPTSTR pNewPass,
								LPTSTR pNewPass2,
								ostream *pTheStream) const; 

	// nsacco 07/07/99 added siteId and coPartnerId
	bool AOLRegister(CEBayISAPIExtension *pServer,
					  char * pUserId,
					  char * pEmail,
					  char * pName,
					  char * pCompany,
					  char * pAddress,
					  char * pCity,
					  char * pState,
					  char * pZip,
					  char * pCountry,
					  int countryId,
					  char * pDayPhone1,
					  char * pDayPhone2,
					  char * pDayPhone3,
					  char * pDayPhone4,
					  char * pNightPhone1,
					  char * pNightPhone2,
					  char * pNightPhone3,
					  char * pNightPhone4,
					  char * pFaxPhone1,
					  char * pFaxPhone2,
					  char * pFaxPhone3,
					  char * pFaxPhone4,
					  char * pGender,
					  int referral,
					  char * pTradeshow_source1,
					  char * pTradeshow_source2,
					  char * pTradeshow_source3,
					  char * pFriend_email,
					  int purpose,
					  int interested_in,
					  int age,
					  int education,
					  int income,
					  int survey,
					  char * pNewPass,
					  int notify,
					  int UsingSSL,
					  int nPartnerId,
					  int siteId,
					  int coPartnerId,
					  ostream *pTheStream					
					  );

		int AOLMailUserRegistrationNotice(char *pUserId,
											   char *pEmail,
											   int	nId,
											   bool  WithCC);

		void AOLOutputConfirmationPath(ostream	*pStream, bool WithCC, int nId);



private:
		//
		// Common Item
		//
		bool GetAndCheckItem(int item, char *pRowNo = NULL, time_t delta = 0);
		bool GetAndCheckItem(char *pItemNo, char *pRowNo = NULL, time_t delta = 0);

		bool GetAndCheckOldItem(char *pItemNo);

		//
		// Bids
		//
		bool GetAndCheckUserForBid(char *pUser);
		bool CheckBid(int quantity, double maxBid,
					  double *pNewPrice);

		void AdjustPrice();
		void NotifyOutBid(char *pEndTime);
		void NotifyBidder(char *pEmail,
						  int quantity,
						  double maxBid,
						  double newPrice,
						  char *pEndTime);

		void DisplayDutchHighBidders(int item, bool IncludingEmail=false);
		void DisplayBids(int item, bool IncludingEmail);


		//
		// Items
		//
		// nsacco 07/27/99 added new params
		bool CheckItemData(	char *pTitle,
							char *pLocation,
							char *pReserve,
							char *pStartPrice,
							char *pQuantity,
							char *pDuration,
							char *pBold,
							char *pFeatured,
							char *pSuperFeatured,
							char *pPrivate,
							char *pDesc,
							char *pPicUrl,
							char *pCategory,
							char *pMoneyOrderAccepted,
							char *pPersonalChecksAccepted,
							char *pVisaMasterCardAccepted,
							char *pDiscoverAccepted,
							char *pAmExAccepted,
							char *pOtherAccepted,
							char *pOnlineEscrowAccepted,
							char *pCODAccepted,
							char *pPaymentSeeDescription,
							char *pSellerPaysShipping,
							char *pBuyerPaysShippingFixed,
							char *pBuyerPaysShippingActual,
							char *pShippingSeeDescription,
							char *pShippingInternationally,
							char *pShipToNorthAmerica,
							char *pShipToEurope,
							char *pShipToOceania,
							char *pShipToAsia,
							char *pShipToSouthAmerica,
							char *pShipToAfrica,
							int  siteId,
							int  descLang,
						    int	 gallery,
							char *pGalleryUrl,
							int  currencyId
						   );

		// nsacco 07/27/99 added new params
		bool CheckUpdatedItemInfo(const char *pTitle,
			const char *pDesc,
			const char *pPicUrl,
			const char *pCategory,
			const char *pMoneyOrderAccepted,
			const char *pPersonalChecksAccepted,
			const char *pVisaMasterCardAccepted,
			const char *pDiscoverAccepted,
			const char *pAmExAccepted,
			const char *pOtherAccepted,
			const char *pOnlineEscrowAccepted,
			const char *pCODAccepted,
			const char *pPaymentSeeDescription,
			const char *pSellerPaysShipping,
			const char *pBuyerPaysShippingFixed,
			const char *pBuyerPaysShippingActual,
			const char *pShippingSeeDescription,
			const char *pShippingInternationally,
			const char *pShipToNorthAmerica,
			const char *pShipToEurope,
			const char *pShipToOceania,
			const char *pShipToAsia,
			const char *pShipToSouthAmerica,
			const char *pShipToAfrica,
			int siteId,
			int descLang,
			const char *pUserId,						// kaz: 4/15/99 passed so we can check name & pwd
			const char *pPass);
		
		
		char *CleanUpDescription(char *pDescription);
		char *CleanUpTitle(char *pTitle);
		char *UnstripHTML(char *pString);
		char *ChangeHTMLQuoteToQuote(char *pString);

		//
		// Users
		//
		void GetAndShowListedItems(clsUser *pUser,
								   int daysSince,
								   ItemListSortEnum sort,
								   char *pCmd = NULL,
								   bool include=false,
								   char* pRequester=NULL,
								   char* pPass=NULL,
								   int startingPage=STARTING_PAGE,
								   int rowsPerPage=ROWS_PER_PAGE);

		void GetAndShowBidItems(clsUser *pUser,
								bool showCompleted,
								ItemListSortEnum sort,
								bool allItems,
								char *pCmd = NULL, 
								int startingPage=STARTING_PAGE, 
								int rowsPerPage=ROWS_PER_PAGE);

		//
		// ShowPersonalizedFeedbackLoginPage
		//
		// Outputs login page to view personalized feedback.
		//
		void ShowPersonalizedFeedbackLoginPage(char *pUserId,
											   int itemsPerPage);

		//
		// ShowFeedbackCommentPage
		//
		// Outputs page to leave feedback for a user.
		//
		void ShowFeedbackCommentPage(char *pUserIdTo,
									 char *pUserIdFrom,
									 int itemNo);

		//
		// Internal routine to get all the feedback for
		// user pUser. "honorHidden" tells us whether
		// or not to honor the "hidden" flag for the
		// user
		//
		// This routine is in clsUserAppViewFeedback.cpp
		//
		void GetAndShowFeedback(clsUser *pUser,
								int startingPage,
								int itemsPerPage,
								bool honorHidden);

		void GetAndShowPersonalizedFeedback(clsUser *pUser,
											int startingPage,
											int itemsPerPage,
											bool honorHidden);

		void GetAndShowFeedbackLeft(clsUser *pUser,
									bool honorHidden);

		void PrintNewFeedbackFeaturesMessage(ostream *mpStream);

		void PrintFeedbackItem(ostream *mpStream,
							   clsFeedbackItem *pItem);

		void PrintPersonalizedFeedbackItem(ostream *mpStream,
										   clsUser *pUser,
										   clsFeedbackItem *pItem,
										   int startingPage,
										   int itemsPerPage,
										   bool honorHidden);

		void PrintFeedbackItemLeft(ostream *mpStream,
								   clsFeedbackItem *pItem,
								   bool honorHidden);


		void PrintFeedbackPageStats(ostream *mpStream,
					   						int firstItem,
											int lastItem,
											int totalItems);

		void EmitFeedbackPageLink(ostream *mpStream,
								  char *pUserId,
								  char *pPass,
								  int pageNum,
								  int itemsPerPage,
								  PageEnum page);

		void PrintFeedbackPagingInfo(ostream *mpStream,
											int lastItem,
											int totalItems,
											int itemsPerPage,
											PageEnum pageToView,
											clsUser *pUser);

		void PrintFeedbackPaginationControl(ostream *mpStream,
											int firstItem,
											int lastItem,
											int totalItems,
											int itemsPerPage,
											bool controlOnTop,
											PageEnum pageToView,
											clsUser *pUser);

		//
		// LeaveFeedbackConfirm
		//
		// Prints out a confirmation page for negative feedback
		// Called only by LeaveFeedback
		//
		void LeaveFeedbackConfirm(char *pUserId,
								  char *pPass,
								  char *pForUser,
								  char *pItemNo,
								  char *pComment);

		// Temporary
		char *TransformInput(char *pString, 
							 bool transformHTML);

		bool ValidatePhone(char *pPhone,
						   bool international,
						   char *pWhichPhone,
						   ostream *pStream);

		bool ValidateEmail(char *pAddress) const;

		int	 MailUserRegistrationNotice(char *pUserId,
										char *pEmail,
										char *pPassword,
										bool WithCC=false);

		int MailUserChangeEmailNotice(char *pUserId,
											char *pEmail,
											char *pPassword);

		int MailUserChangeEmailConfirmatiom(clsUser* pUser,
											char* pNewMail);

		void ShowRegistration(char *pUserId, char *pPass, int UsingSSL);

		void ShowRegistrationInfo(char *pUserId, char *pPass);

		void ShowConfirmation(char *pUserId, char *pPass);

		bool ValidateNonRequiredRegistrationInfo(const char * pCompany,
								      const char * pNightPhone1,
								      const char * pNightPhone2,
								      const char * pNightPhone3,
								      const char * pNightPhone4,
									  const char * pFaxPhone1,
									  const char * pFaxPhone2,
									  const char * pFaxPhone3,
									  const char * pFaxPhone4,
									  const char * pGender,
									  char * pFriend_email) const;

		bool ValidateAndReviewRequiredInfo(int * pUVrating,
											int * pUVdetail,
											bool ShowReview,
											const char* pEmail, 
											const char* pName, 
											const char* pAddress, 
											const char* pCity, 
											const char* pState, 
											const char* pZip, 
											const char* pCountry, 
											int countryId,			// PH 05/03/99 
											const char* pDayPhone1,
											const char* pDayPhone2,
											const char* pDayPhone3,
											const char* pDayPhone4) const;

		void ConfirmInstruction(bool WithCC=false);

		bool IsAnonymousEmail(char* pEmail);

		void RegisterWithAnonymousEmail(
							char * pUserId,
							char * pEmail,
							char * pName,
							char * pCompany,
							char * pAddress,
							char * pCity,
							char * pState,
							char * pZip,
							char * pCountry,
							int countryId,			// PH 05/04/99
							char * pDayPhone1,
							char * pDayPhone2,
							char * pDayPhone3,
							char * pDayPhone4,
							char * pNightPhone1,
							char * pNightPhone2,
							char * pNightPhone3,
							char * pNightPhone4,
							char * pFaxPhone1,
							char * pFaxPhone2,
							char * pFaxPhone3,
							char * pFaxPhone4,
							char * pGender,	
							int referral,
							char * pTradeshow_source1,
							char * pTradeshow_source2,
							char * pTradeshow_source3,
							char * pFriend_email,
							int purpose,
							int interested_in,
							int age,
							int education,
							int income,
							int survey,							
							int partnerId,
							int siteId,	// nsacco 07/02/99
							int brandPartnerId,
							int UsingSSL);

		void ConfirmError(int ErrorCode,
							char *pEmail,
							char *pPass,
							char *pUserId,
							char *pNewPass,
							char *pNewPass2,
							int   notify,
							int   countryId);

		const char* CCGetColor(int ErrorCode, int FieldCode);

		void PrintErrorMsg(bool& FirstTimeHasError, const char * pMsg, strstream* pErrorStream);

		char* clseBayApp::GetSuggestedUserId(clsUser* pUser, char *pUserId);

		//
		// Internal routine to end an auction and credit fees
		//
		bool EndAuctionInternal(clsItem *pItem,
								ostream *pMessageStream,
								ostrstream *pMailStream);

		void EndAuctionRefundFees(clsItem *pItem,
								  clsUser *pSeller,
								  clsAccount *pSellerAccount,
								  ostream *pMessageStream,
								  ostrstream *pMailStream);

		void EndAuctions(list<unsigned int> *pItemIdList,
						 clsUser *pEndingUser,
						 eNoteTypeEnum type,
						 int buddy,
						 char *pText,
						 char *pSellerEmailSubjectTemplate,
						 char *pSellerEmailTemplate,
						 char *pBidderEmailSubjectTemplate,
						 char *pBidderEmailTemplate,
						 char *pBuddyEmailAddress,
						 char *pBuddyEmailSubjectTemplate,
						 char *pBuddyEmailTemplate,
						 bool refundFees,
						 bool suspended,
						 bool emailbidders);


		void OutputConfirmationPath(ostream *mpStream, 
									bool WithCC);


		void AdminShowUserHistory(	char* pUserId,
									char* pEmail,
									clsUser* pUser);


//INNA 2 email methods
		int MailCreditCardNotOnFileNotice(clsUser *pUser);
		int MailCCNoticeErrors(clsUser *pUser);
		int MailUnfeatureNotice(clsUser *pUser);

		// Category Admin
		bool GetAndCheckCategory(char *pCategory);
		bool GetAndCheckUser(char *pEmail, char *pPass);

		bool CheckCategoryData(	char *pName,
							char *pDesc,
							char *pAdult,
							char *pFeaturedCost,
							char *pFileRef,
							char *pCategory,
							char *pAddAction,
							int   feeCurrencyId
						   );

		bool CheckCategoryName(	char *pName);

		// 
		// Credit Card
		//
		bool   CheckCCChecksum(char *pccNumber);
		time_t CheckCCDate(	char *pDay, 
							char *pMonth, 
							char *pYear);
		bool	GetAndCheckCCInfo(char * pUserId,
								 char * pccNumber, 
								 time_t expTime,
								 clsAccount *pAccount);
		bool	AddEntryToAccessImportFile(	char   *pEmail,
											char   *pccNumber,
											time_t expDate);

		void	ErrBlankField (char *fldName);

		void CCConfirmError(int ErrorCode,
							char *pEmail,
							char *pOldPass,
							char *pNewPass,
							char *pUserId,
							char *pNewPassAgain,
							char *pUserName,
							char *pStreetAddr,
							char *pCityAddr,
							char *pStateProvAddr,
							char *pZipCodeAddr,
							char *pCountryAddr,
							char *pCC,
							char *pMonth,
							char *pDay,
							char *pYear,
							int	  UseForPayment,
							int   notify);



		// Announcements
		bool CheckAnnouncementData(
								char *pEmail,
								char *pPass,
								char *pId,
								char *pLoc,
								char *pCode,
								char *pDesc,
								char *pSiteId,
								char *pPartnerId
							  );

		void AnnouncementEntry(clsAnnouncement *pAnnounce, int siteid, int partnerid);

		//
		// Statistics
		//
		void ShowNewDailyStatistics( void* pvBidStats,
								void* pvRegularStats, 
								void* pvDutchStats);

		void ShowCompletedDailyStatistics(
								void* pvRegularStats, 
								void* pvDutchStats);

		bool ConvertToTime_t(int StartMon, int StartDay, int StartYear, time_t* pTimeTValue);

		void GetAndShowDailyStatistics(time_t StartTime, time_t EndTime);

		static bool SortByTime(clsDailyStatistics *pStats1, clsDailyStatistics *pStats2);

		// ViewListedItem
		void ViewListedItemUserIdPassword(char* pUserId,
										 bool completed,
										 ItemListSortEnum sort,
										 int daysSince,
										 int startingPage,
										 int rowsPerPage);

		// Opt-in/Opt-out
		void ProduceOptinReview(char* pUserId,
								ostream *pStream);
		
		// What to do if the user does not accept, and other
		// documents produced via ISAPI functions.
		void ProduceUserAgreementFAQ();
		void ProduceUserAgreementTopPart();
		void ProduceUserAgreementIntroForBiddingAndSelling();
		void ProduceUserAgreementIntroForAboutMe();
		void ProduceUserAgreementFormAfterAction();

		void UserAgreementForBidding(int   item,
						   			 char *pUserId,
									 char *pPass,
								     char *pMaxBid,
								     int   quantity,
									 char *pKey);

		// nsacco 07/27/99 updated params
		void UserAgreementForSelling(char *pUserId,
							    char *pPass,
							    char *pTitle,
								char *pLocation,
								char *pReserve,
								char *pStartPrice,
								char *pQuantity,
								char *pDuration,
								char *pBold,
								char *pFeatured,
								char *pSuperFeatured,
								char *pPrivate,
								char *pDesc,
								char *pPicUrl,
								char *pCategory1,
								char *pCategory2,
								char *pCategory3,
								char *pCategory4,
								char *pCategory5,
								char *pCategory6,
								char *pCategory7,
								char *pCategory8,
								char *pCategory9,
								char *pCategory10,
								char *pCategory11,
								char *pCategory12,
								char *pOldItemNo,
								char *pOldKey,
								char *pMoneyOrderAccepted,
							    char *pPersonalChecksAccepted,
							    char *pVisaMasterCardAccepted,
							    char *pDiscoverAccepted,
							    char *pAmExAccepted,
							    char *pOtherAccepted,
							    char *pOnlineEscrowAccepted,
							    char *pCODAccepted,
							    char *pPaymentSeeDescription,
							    char *pSellerPaysShipping,
							    char *pBuyerPaysShippingFixed,
							    char *pBuyerPaysShippingActual,
							    char *pShippingSeeDescription,
							    char *pShippingInternationally,
							    char *pShipToNorthAmerica,
							    char *pShipToEurope,
							    char *pShipToOceania,
							    char *pShipToAsia,
							    char *pShipToSouthAmerica,
							    char *pShipToAfrica,
							    int  siteId,
							    int  descLang,
							    char *pGiftIcon,
							    int  gallery,
							    char *pGalleryUrl,
							    int  countryId,
							    int  currencyId,
							    char *pZip);

		// UserPageUtilities
		void UserPageShowDonePage(char *pUserId,
			                      char *pPassword);
		void ShowEditingPage(char *pUserId,
			                 char *pPassword,
							 char *pText);
		void WriteTemplateElementsParams(TemplateElements *elements, bool layout = true);
		void InsertBar(int hr);
		void ExpireThePage();
		void DrawPageFromHTML(char *pHTML);
		void ShowNoAboutMeMessage();

		// UserPageTemplates
		void UserPageConvertTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render);
		void UserPageConvertSideBySideTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render);
		void UserPageConvertNewspaperTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render);
		void UserPageConvertCenteredTemplateToHTML(ostream *pStream, TemplateElements *elements, bool render);

		//
		// Common Admin routine to show a board's control info
		//
		void AdminBoardShow(clsBulletinBoard *pBoard);

		//
		// For Admin Statistics 
		//
		void AdminStatistics();
		//
		//
		// A couple of conceivably useful tools
		void EmitHeader(const char *title);
		bool CheckAuthorization(eBayISAPIAuthEnum authLevel);
		//
		// Controller for ShillTool
		void AdminShillRelationshipsKernel(vector<char *> &users,
			vector<clsUser *> &pUsers,
			const char *details,
			int limit);


		// Helper functions for auto credits
		void GenerateCreditRequestPage(int moreCredits, bool isArc);
		bool CheckItemCreditData(char *pItemNo, int wasPaid, char *pEmail, 
								 char *pAmt, int reason,
								 CreditsVector *bidderVector,
								 BidVector *pvBids,
								 int arc, short ord_num);
		//
		// Common notes routines
		//
		void ShowNote(clsNote *pNote);

		void ShowNotes(char *pAboutFilter,
					   int categoryFilter,
					   clsUser *pAboutUser = NULL);

		void ShowAddNoteAboutUser(char *pUser,
								  char *pPass,
								  char *pAboutUser,
								  char *pSubject,
								  int type,
								  char *pText);

		void ShowAddNoteAboutItem(char *pUser,
								  char *pPass,
								  char *pAboutItem,
								  char *pSubject,
								  int type,
								  char *pText);

		void AdminInternalShowNoteShow(char *pUser,
									   char *pPass,
									   char *pAboutFilter,
									   unsigned int typeFilter);

		//
		// Routines to display front-end forms for admin functions,
		// and validate input
		//
		bool ValidateWarningInput(char *pUser,
									 char *pPass,
									 char *pTarget,
									 int type,
									 char *pText);

		void WarnUserShow(char *pUser,
						  char *pPass,
						  char *pTarget,
						  int type,
						  char *pText);

		void WarnUserConfirm(char *pUser,
							 char *pPass,
							 char *pTarget,
							 int type,
							 char *pText);

		bool ValidateSuspensionInput(char *pUser,
									 char *pPass,
									 char *pTarget,
									 int type,
									 char *pText);

		void SuspendUserShow(char *pUser,
							 char *pPass,
							 char *pTarget,
							 int type,
							 char *pText);

		void SuspendUserConfirm(char *pUser,
								char *pPass,
								char *pTarget,
								int type,
								char *pText);

		bool ValidateReinstateInput(char *pUser,
									char *pPass,
									char *pTarget,
									int type,
									char *pText);

		void ReinstateUserShow(char *pUser,
							   char *pPass,
							   char *pTarget,
							   int type,
							   char *pText);

		void ReinstateUserConfirm(char *pUser,
								  char *pPass,
								  char *pTarget,
								  int type,
								  char *pText);

		// Moving auctions
		void ChargeMoveFee(clsItem *pItem,
						   clsUser *pSeller);

		bool ValidateMoveAuctionInput(char *pUserId,
									  char *pPass,
									  char *pItemId,
									  int category,
									  int emailsellers,
									  int chargesellers,
									  char *pText);


		void MoveAuctionShow(char *pUserId,
							 char *pPass,
							 char *pItemIds,
							 int category,
							 int emailsellers,
							 int chargesellers,
							 char *pText);

		void MoveAuctionConfirm(char *pUserId,
								char *pPass,
								char *pItemId,
								int category,
								int emailsellers,
								int chargesellers,
								char *pText);

		void MoveAuctions(list<unsigned int> *pItemIdList,
						  clsUser *pEndingUser,
						  int category,
						  bool emailsellers,
						  bool chargesellers,
						  char *pText,
						  char *pSellerEmailSubjectTemplate,
						  char *pSellerEmailTemplate);

		bool MoveAuctionInternal(clsItem *pItem,
								 int category,
								 ostream *pStream,
								 ostrstream *pMailStream);


		// Ending auctions
		bool ItemsToItemIdList(char *pItems,
							   list<unsigned int> *plItemIds);

		bool ValidateEndAuctionInput(char *pUserId,
									 char *pPass,
									 char *pItemId,
									 int suspended,
									 int creditFees,
									 int emailbidders,
									 int type,
									 int buddy,
									 char *pText);

		void EndAuctionShow(char *pUserId,
							char *pPass,
							char *pItemId,
							int suspended,
							int creditFees,
							int emailbidders,
							int type,
							int buddy,
							char *pText);

		void EndAuctionConfirm(char *pUserId,
							   char *pPass,
							   char *pItemId,
							   int suspended,
							   int creditFees,
							   int emailbidders,
							   int type,
							   int buddy,
							   char *pText);

		bool ValidateEndAllAuctionsInput(char *pUserId,
										 char *pPass,
										 char *pTargetUser,
										 int suspended,
										 int creditFees,
										 int emailbidders,
										 int type,
										 int buddy,
										 char *pText);


		void EndAllAuctionsShow(char *pUserId,
								char *pPass,
								char *pTargetUser,
								int suspended,
								int creditFees,
								int emailbidders,
								int type,
								int buddy,
								char *pText);

		void EndAllAuctionsConfirm(char *pUserId,
								   char *pPass,
								   char *pTargetUser,
								   int suspended,
								   int creditFees,
								   int emailbidders,
								   int type,
								   int buddy,
								   char *pText);


		void UnflagUserShow(char *pUser,
							char *pPass,
							char *pTarget,
							int type,
							char *pText);

		void UnflagUserConfirm(char *pUser,
							   char *pPass,
							   char *pTarget,
							   int type,
							   char *pText);

		bool ValidateUnflagInput(char *pUser,
								 char *pPass,
								 char *pTarget,
								 int type,
								 char *pText);

		void ReinstateAuctionShow(int action,
									char *pUser,
									char *pPass,
									char *pItemNo,
									int type,
									char *pText);

		void ReinstateAuctionConfirm(char *pUser,
								     char *pPass,
								     char *pItemNo,
								     int type,
								     char *pText);

		//
		// This method gets the e-mail template for various
		// support eNote types. 
		//
		const char *GetEmailTemplateForNoteType(unsigned int type);
		const char *GetEmailSubjectForNoteType(unsigned int type);
		const char *GetBidderEmailTemplateForNoteType(unsigned int type);
		const char *GetBidderEmailSellerSuspendedTemplateForNoteType(unsigned int type);
		const char *GetBidderEmailSubjectForNoteType(unsigned int type);
		const char *GetBuddyEmailTemplateForNoteType(unsigned int type);
		const char *GetBuddyEmailSubjectForNoteType(unsigned int type);


		// The actual data for the above method
		static clsMapNoteTypeToMailTemplate	mMapNoteTypeToMailTemplate[];

		// Some international related routines.
		int MailSupportAboutCountryChange(clsUser *pUser,
// petra								          char *pNewCountry);
											int oldCountryId,	// petra
											int newCountryId);	// petra

		//
		// Private routines to access buddy data
		//
		const clsCopyrightBuddyInfo *GetBuddyInfo(unsigned int buddyId);
		void EmitBuddyInfoAsHTMLOptions(unsigned int currentBuddyId);

		//
		// The actual data for the above methods
		static clsCopyrightBuddyInfo mCopyrightBuddyInfo[];

		// Helper function to show welcome info when registering.
		void ShowWelcomeToEBay();

		void TurnOnBidNoticesChinese();
		void TurnOffBidNoticesChinese();
		void TurnOnBidNoticesDutch();
		void TurnOffBidNoticesDutch();
		void TurnOnOutBidNoticesChinese();
		void TurnOffOutBidNoticesChinese();

		// Display category-specific message(s) to bidder
		void EmitBidderMessages(CategoryId categoryId, ostream *pStream);

		// Display category-specific message(s) to seller
		void EmitSellerMessages(CategoryId categoryId, ostream *pStream);

		// Display message explaining why listing of the item is denied
		void EmitItemListingDenied(clsItem *pItem,
								   FilterVector *pvFilters,
								   ostream *pStream);

		// Display message explaining why adding to the item description
		// is denied
		void EmitItemAddToDescDenied(clsItem *pItem,
									 FilterVector *pvFilters,
									 ostream *pStream);

		// Display message explaining why listing of the item is denied
		void EmitItemUpdateInfoDenied(clsItem *pItem,
									  FilterVector *pvFilters,
									  ostream *pStream);

		// Display message explaining why changing theitem category
		// is denied
		void EmitChangeCategoryDenied(clsItem *pItem,
									  FilterVector *pvFilters,
									  ostream *pStream);

		// Send email to ebay support about a 'bad' item.
		void AdminSendEmail(clsItem *pItem, FilterVector *pvFilters, char *pSubject);

		// Report blocked and flagged items to appropriate folks
		void AdminReportScreenedItem(clsItem *pItem,
									 clsUser *pUser,
									 FilterVector *pvFilters,
									 ActionType action,
									 ScreenItemType when);

		// Report listing of new item or change to existing item in
		// flagged category by user previously flagged for blocked items
		void AdminReportItemByFlaggedUser(clsItem *pItem,
										  clsUser *pUser,
										  FilterVector *pvFilters,
										  ScreenItemType when);

		// Screen an item in a flagged category and generate
		// enotes and emails as necessary
		ActionType AdminScreenItem(clsItem *pItem,
								   clsUser *pUser,
								   FilterVector *pBadKeywords,
								   ScreenItemType when,
								   ostream *pStream);

		//
		// Personal Shopper
		//
		void DisplaySearchDetails(char *pUserId,
									char *pPassword,
									char *pQuery,
									char *pSearchDesc,
									char *pMinPrice,
									char *pMaxPrice,
									char *pEmailFrequency,
									char *pEmailDuration,
									char *pRegId);

		void DisplaySearchDetailsStatic(char *pUserId,
									char *pPassword,
									char *pQuery,
									char *pSearchDesc,
									char *pMinPrice,
									char *pMaxPrice,
									char *pEmailFrequency,
									char *pEmailDuration,
									char *pRegId);

		void DisplayPSNetMindTC(char *pAction,
							 int Pairs, 
							 clsNameValuePair* pNameValue);

		// functions for personal shopper to emit html page and buttons 
		void EmitPSSearch(const char* pEmail, const char* pPassword, PSSearchVector* pSearches);
		void EmitSearchButton(clsPSSearch* pPSSearch);
		void EmitModifyButton(const char* pEmail, const char* pPassword, clsPSSearch* pPSSearch);
		void EmitDeleteButton(const char* pEmail, const char* pPassword, clsPSSearch* pPSSearch);

		// return the pointer for Personal Searches
		clsPSSearches* GetPSSearches();

		//

		// Alllll together now!
		//
		clsDatabase		*mpDatabase;
		clsMarketPlaces	*mpMarketPlaces;
		clsMarketPlace	*mpMarketPlace;
		clsCategories	*mpCategories;
		clsLocations	*mpLocations;
		clsUserVerificationServices *mpUserVerificationServices;
		clsCategory		*mpCategory;
		clsItems		*mpItems;
		clsUsers		*mpUsers;
		clsStatistics	*mpStatistics;
		clsAnnouncements *mpAnnouncements;

		// The follow are used during a given
		// "Run()" of the application
		clsUser			*mpUser;
		clsItem			*mpItem;
		clsFeedback		*mpFeedback;

		// Real Time CC Authorization
		clsAuthorizationQueue	*mpAuthorizationQueue;

		// This is used to store partner names, for cookie setting or whatever.
		vector<const char *>	*mpvPartners;
		PageEnum				mePage;

		// These are used for timing in Admin Applications
		time_t					mBatchBeginTime;
		time_t					mBatchEndTime;
		time_t					mValidateBeginTime;
		time_t					mValidateEndTime;
		time_t					mItemGetBeginTime;
		time_t					mItemGetEndTime;
		time_t					mUserGetBeginTime;
		time_t					mUserGetEndTime;
		time_t					mCommitBeginTime;
		time_t					mCommitEndTime;


		// The cookie.
		clseBayCookie *mpCookie;
        int mHasAdultCookie; // -1 is unknown, 0 is false, 1 is true.

		//
		// Legal Buddies util methods
		//
		// Builds a combo box full of filter titles
		void BuildFilterComboBox(char *pTitle);
		
		// Builds a combo box full of message titles
		void BuildMessageComboBox(char *pTitle, int message_type, int selected);

		bool GetAndCheckBlockedItem(char *pItemNo, 
									char *pRowNo = NULL, 
									time_t delta = 0);

		// Add eNote for Appeals
		void AdminAppealeNote(char *pUserId, char *pPass,
								char *pItemNo, int type,
								char *pText, eBayISAPIAuthEnum authLevel);


		// Personal Shopper
		clsPSSearches	*mpPSSearches;

};




#endif /* CLSEBAYAPP_INCLUDED */
