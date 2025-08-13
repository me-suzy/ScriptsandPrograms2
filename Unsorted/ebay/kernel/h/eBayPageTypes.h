/*	$Id: eBayPageTypes.h,v 1.16.2.3.42.3 1999/08/05 18:59:13 nsacco Exp $	*/
//
//	File:		eBayPageTypes.h
//
// Class:	eBayPageTypes
//
//	Function:
//
//				Page type
//
// Modifications:
//				- 01/15/98 craig	- Created
//				- 08/22/98 mila		- added new feedback pages; add ePicsServerType
//									  to CoPageRec structure
//				- 09/28/98 mila		- Added deadbeat pages
//				- 10/13/98 mila		- Added PageViewDeadbeatUsers
//				- 04/07/99 kaz		- Added PagePoliceBadgeLogin
//				- 04/18/99 kaz		- Removed PageOptinChange, Renamed PageOptinSave to PageOptinconfirm
//										and properly alphabetized PageIIS_Server_Status
//				- 04/06/99 alexp	- Added eSecondaryPageType to struct CoPageRec
//				- 04/15/99 kaz		- Added PagePoliceBadgeLogin
//				- 05/24/99 jennifer - Added Admin Gallery Tool Pages
//				- 06/15/99 petra	- commented out admin category pages
//
#ifndef EBAYPAGETYPES_INCLUDED
#include "eBayTypes.h"
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp


typedef enum
{
	PageUnknown							= -1,
	PageAcceptBid,
	PageAccountingBatch,
	PageAddAnnouncement,
// petra	PageAddNewCategory,
	PageAddNewItem,
	PageAddNoteAboutUser,
	PageAddNoteAboutUserResult,
	PageAddToBoard,
	PageAddToItem,
	PageAdminAddCobrandAd,
	PageAdminAddCobrandAdConfirm,
	PageAdminAddCobrandAdShow,
	PageAdminAddCobrandAdToSitePage,
	PageAdminAddCobrandAdToSitePageConfirm,
	PageAdminAddExchangeRate,
	PageAdminAddFilter,
	PageAdminAddFilterShow,
	PageAdminAddMessage,
	PageAdminAddMessageShow,
	PageAdminAddNoteAboutItem,
	PageAdminAddNoteAboutItemResult,
	PageAdminAddPartnerAd,
	PageAdminAddPartnerAdConfirm,
	PageAdminAddPartnerAdShow,
	PageAdminAddPartnerAdToSitePage,
	PageAdminAddPartnerAdToSitePageConfirm,
	PageAdminAddPartnerAdToSitePageShow,
	PageAdminAddScreeningCriteria,
	PageAdminAddScreeningCriteriaShow,
	PageAdminAnnouncement,
	PageAdminBoardChange,
	PageAdminBoardChangeShow,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageAdminChangeEmail,
	PageAdminChangeEmailConfirm,
	PageAdminChangeEmailShow,
	PageAdminChangeUserId,
	PageAdminChangeUserIdAskConfirm,
	PageAdminChangeUserIdShow,
	PageAdminCombineUserConf,
	PageAdminCombineUsers,
	PageAdminCreditBatch,
	PageAdminEndAllAuctions,
	PageAdminEndAllAuctionsConfirm,
	PageAdminEndAllAuctionsResult,
	PageAdminEndAuction,
	PageAdminEndAuctionConfirm,
	PageAdminEndAuctionResult,
	PageAdminEndAuctionShow,
	PageAdminGalleryItemDelete,
	PageAdminGalleryItemDeleteConfirm,
	PageAdminGalleryItemView,
	PageAdminModifyFilter,
	PageAdminModifyMessage,
	PageAdminMoveAuctionShow,
	PageAdminMoveAuctionConfirm,
	PageAdminMoveAuctionResult,
	PageAdminRebalanceUserAccount,
	PageAdminReinstateAuction,
	PageAdminReinstateAuctionConfirm,
	PageAdminReinstateAuctionResult,
	PageAdminReinstateAuctionShow,
	PageAdminReInstateItem,
	PageAdminReInstateItemLogin,	
	PageAdminReinstateUser,
	PageAdminReinstateUserConfirm,
	PageAdminReinstateUserResult,
	PageAdminReinstateUserShow,
	PageAdminRemoveItem,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageAdminRequestPassword,
	PageAdminResetReqEmailCount,
	PageAdminSelectCobrandAdPartnerAndPageShow,
	PageAdminSelectCobrandAdSiteShow,
	PageAdminSetTopSellerLevel,
	PageAdminSetTopSellerLevelConfirmation,
	PageAdminSetTopSellerLevelMultiple,
	PageAdminShillRelationshipsByFeedback,
	PageAdminShillRelationshipsByItem,
	PageAdminShillRelationshipsByUsers,
	PageAdminShowBiddersSellers,
	PageAdminShowCommonAuctions,
	PageAdminShowNote,
	PageAdminShowNoteShowResult,
	PageAdminShowTopSellers,
	PageAdminShowTopSellerStatus,
	PageAdminSpecialItemAdd,
	PageAdminSpecialItemDelete,
	PageAdminSpecialItemFlush,
	PageAdminSpecialItemsTool,
	PageAdminSuspendUser,
	PageAdminSuspendUserConfirm,
	PageAdminSuspendUserResult,
	PageAdminSuspendUserShow,	
	PageAdminUnflagUser,
	PageAdminUnflagUserConfirm,
	PageAdminUnflagUserResult,
	PageAdminUnflagUserShow,
	PageAdminViewBids,
	PageAdminViewBlockedItem,
	PageAdminViewDailyFinance,
	PageAdminViewDailyStats,
	PageAdminViewOldItem,
	PageAdminViewScreeningCriteria,
	PageAdminViewScreeningCriteriaShow,
	PageAdminWarnUser,
	PageAdminWarnUserConfirm,
	PageAdminWarnUserResult,
	PageAdultLogin,
	PageAdultLoginShow,
	PageAggregateReport,
	PageAOLRegisterComplete,
	PageAOLRegisterConfirm,
	PageAOLRegisterPreview,
	PageAOLRegisterShow,
	PageAOLRegisterUserAcceptAgreement,
	PageAOLRegisterUserAgreement,
	PageAOLRegisterUserID,
	PageBetaConfirmation,
	PageBetaConfirmationPreview,
	PageBetaConfirmationShow,
	PageBetterSeller,
	PageCCRegConfirm,
	PageCCRegistrationAcceptAgreement,
	PageCancelBid,
	PageCategorizeUserPage,
	PageCategoryAdminRun,
	PageCategoryHomePage,
	PageChangeCategory,
	PageChangeCategoryShow,
	PageChangeCobrandHeader,
	PageChangeEmail,
	PageChangeEmailConfirm,
	PageChangeEmailConfirmShow,
	PageChangeEmailShow,
	PageChangeFeedbackOptions,
	PageChangeItemInfo,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageChangePassword,
	PageChangePasswordCrypted,
	PageChangePreferences,
	PageChangePreferencesShow,
	PageChangeRegistration,
	PageChangeRegistrationPreview,
	PageChangeRegistrationShow,
	PageChangeSecretPassword,
	PageChangeUserId,
	PageChangeUserIdShow,
	PageChineseAuctionCreditReq,
	PageConfirmByCountry,
	PageConfirmNewGalleryImage,
	PageConfirmUser,
	PageContacteBay,
	PageCreateAccount,
	PageCreateCobrandPartner,
	PageCreditBatch,
// petra	PageDeleteCategory,
	PageDeleteDeadbeatItem,
	PageDisplayGalleryImagePage,
	PageDutchAuctionCreditReq,
	PageEmailAuctionToFriend,
	PageEndAllAuctions,	
	PageEndAllAuctionsAndCreditFees,
	PageEndAuctionAndCreditFees,
	PageEnterNewGalleryImage,
	PageFeatured,
	PageFeedbackForum,
	PageFixGalleryImage,
	PageFollowUpFeedback,
	PageFollowUpFeedbackShow,
	PageGetBidderEmails,
	PageGetFeedbackScore,
	PageGetItemInfo,
	PageGetUserAboutMe,
	PageGetUserEmail,
	PageGetUserIdHistory,
	PageGoToSurvey,
	PageHomePage,
	PageIEscrowLogin,
	PageIEscrowShowData,
	PageIEscrowSendData,
	PageIIS_Server_status,  //new outage code	kaz: 04/18/99 alphabetized!
	PageInvalidateList,
	PageItemCreditReq,
	PageItemInfo,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageLeaveFeedback,
	PageLeaveFeedbackShow,
	PageListings,
	PageListItemForSale,
	PageLocationsTesting,
	PageMakeBid,
// petra	PageMakeDelete,
	PageMakeFeatured,
// petra	PageMakeMove,
	PageMemberSearchShow,
// petra	PageMoveCategory,
	PageMultipleEmails,
	PageMyEbay,
	PageMyEbayBidder,
	PageMyEbaySeller,
// petra	PageNewCategory,
	PageNewItem,
	PageNewItemQuick,
	PageNewTutorial,
	PageOptinConfirm,
	PageOptinLogin,
// petra	PageOrderCategory,
	PageParseError,
	PagePassRecognizer,
	PagePastEssay,
	PagePayCoupon,
	PagePersonalizedFeedbackLogin,
	PagePersonalShopperViewSearches,
	PagePersonalShopperAddSearch,
	PagePersonalShopperDeleteSearchView,
	PagePersonalShopperDeleteSearch,
	PagePersonalShopperSaveSearch,
	PagePoliceBadgeLogin,
	PagePowerSellerRegister,
	PagePowerSellerRegisterShow,
	PageRecomputeChineseBids,
	PageRecomputeDutchBids,
	PageRecomputeScore,
	PageRedirectEnter,
	PageRegister,
	PageRegisterByCountry,
	PageRegisterCC,
	PageRegisterConfirm,
	PageRegisterLinkButtons,
	PageRegisterPreview,
	PageRegisterShow,
	PageRegistrationAcceptAgreement,
	PageReinstateUser,
	PageRemoveUserIdCookie,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageRemoveUserPage,
	PageReportQuestionableItem,
	PageReportQuestionableItemShow,
	PageRequestGiftAlert,
	PageRequestPassword,
	PageRequestRefund,
	PageResendConfirmationEmail,
	PageRespondFeedback,
	PageRespondFeedbackShow,
	PageRetractAllBids,
	PageRetractBid,
	PageReturnUserEmail,
	PageReturnUserIdHistory,
	PageRewriteCobrandHeaders,
	PageSaveUserPage,
	PageSendGiftAlert,
	PageSendQueryEmail,
	PageSendQueryEmailShow,
	PageShowCobrandHeaders,
	PageShowCobrandPartners,
	PageShowEmailAuctionToFriend,
	PageShowRegistrationForm,
	PageStop,
	PageSurveyResponse,
	PageSuspendUser,
	PageTimeShow,
	PageUp4SaleTestPassword,
	PageUpdateAnnouncement,
	PageUpdateCC,
	PageUpdateCCConfirm,
// petra	PageUpdateCategory,
	PageUpdateItemInfo,
	PageUserAgreementAccept,
	PageUserAgreementFAQ,
	PageUserAgreementForBidding,	
	PageUserAgreementForSelling,
	PageUserItemVerification,
	PageUserPageAcceptAgreement,
	PageUserPageConfirmHTMLEditingChoice,
	PageUserPageConfirmTemplateEditingChoice,
	PageUserPageEditing,
	PageUserPageGoToHTMLPreview,
	PageUserPageHandleHTMLPreviewOptions,
	PageUserPageHandleStyleOptions,
	PageUserPageHandleTemplateOptions,
	PageUserPageHandleTemplatePreviewOptions,
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageUserQuery,
	PageUserSearch,
	PageValidateUserForSurvey,
	PageVerifyAddToItem,
// petra	PageVerifyNewCategory,
	PageVerifyNewItem,
	PageVerifyStop,
// petra	PageVerifyUpdateCategory,
	PageVerifyUpdateItem,
	PageViewAccount,
	PageViewAliasHistory,
	PageViewAllItems,
	PageViewBidDutchHighBidderEmails,
	PageViewBidItems,
	PageViewBidderWithEmails,
	PageViewBids,
	PageViewBidsDutchHighBidder,
	PageViewBoard,
// petra	PageViewCategory,
	PageViewDeadbeatUser,
	PageViewDeadbeatUsers,
	PageViewEssay,
	PageViewFeedback,
	PageViewFeedbackLeft,
	PageViewGiftAlert,
	PageViewGiftCard,
	PageViewGiftItem,
	PageViewItem,
	PageViewListedItems,
	PageViewListedItemsLinkButtons,
	PageViewListedItemsWithEmails,
	PageViewPersonalizedFeedback,
	PageViewUserPage,
	PageWackoFlagChange,
	PageWackoFlagChangeConfirm,
// All new page numbers belong ABOVE this marker.	
// KEEP THIS LIST IN ALPHABETICAL ORDER.
// Warning warning -- this table must match the sequence in
// clsPartners.cpp
	PageLastPossiblePage
} PageEnum;

struct CoPageRec 
{
	PageEnum		ePageEnum;
	PageTypeEnum	ePageType;	
	PageTypeEnum	eSecondaryPageType;	
	int eCGIServerType;
	int eHTMLServerType;	
	int ePicsServerType;	
	
// kakiyama 07/19/99
	int eSearchServerType;
} ;

typedef enum
{
	CGI_Machine_0		= 1,
	HTML_Machine		= 2,
	CGI_Machine_1		= 3,
	ADMIN_Machine		= 4,
	CGI_Beta_Machine	= 5,
	SECURE_Machine      = 6,
	CGI_AboutMe_Machine = 7,
	Pics_Machine		= 8,
	CGI_Machine_2		= 9,
	CGI_Machine_3		= 10,
	CGI_Machine_4		= 11,
	CGI_Machine_5		= 12,
	CGI_Machine_6		= 13,
	// Be sure to add new entries ABOVE this line!!!
	LAST_Machine
} ServerMachineEnum;

typedef const char *tServerMachines[LAST_Machine];

#define EBAYPAGETYPES_INCLUDED
#endif /* EBAYPAGETYPES_INCLUDED */
