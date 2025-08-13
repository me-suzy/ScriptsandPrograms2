/*	$Id: clsPartners.cpp,v 1.19.2.9.4.4 1999/08/10 01:19:52 nsacco Exp $	*/
//
// File Name: clsPartners.cpp
//
// Description: Container object for the clsPartners,
//              also does implementation hiding for such
//              things as the 'default partner'.
//
// Authors:     Chad Musick, Craig Huang
// Modifications:
//				- 04/07/99 kaz		- Added PagePoliceBadgeLogin
//				- 04/18/99 kaz		- Removed PageOptinChange, renamed PageOptinSave to PageOptinConfirm,
//										and moved PageIIS_Server_Status so it's alphabetized
//				- 05/24/99 jennifer - Added Admin Gallery Tool pages
//				- 07/20/99 nsacco	- default partner is now eBay not ebay
//				- 05/25/99 nsacco	- Modified LoadPartners() and GetCurrentPartner() to use mSiteId
//									  and added FindPartnerFromServerName
//				- 06/15/99 petra	- commented out admin category pages
//


#include "eBayKernel.h"
#include "clsPartners.h"
#include "clsEnvironment.h"

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif

// #defines of machines have been changed to enum
// ServerMachinesEnum, which is defined in eBayPageTypes.h
// (mila -- 11/17/98)

#define HTML HTML_Machine
#define PT(x) PageType##x
#define CGI(x) CGI_Machine_##x
#define ADMIN ADMIN_Machine
#define ABOUTME CGI_AboutMe_Machine
#define SECURE SECURE_Machine
#define BETA CGI_Beta_Machine
#define PICS Pics_Machine


// Mila B 02/05/99
//
// Moved all feedback from CGI(3) to CGI(2)

// Alex P 12/04/98
//
// Bidding, Selling, Registration = CGI(0)
// View Feedback = CGI(2)
// Leave Feedback, Seller Search, Bidder Search, Boards, Accounts, and Everything Else = CGI(3)
// My eBay = CGI(1)
// CGI(4) is a spare
// BETA is unused.
// About Me = ABOUTME (though will probably consist of the same machines as CGI(3))

// PageFunction, PageGroup, CGI Machine, HTML Machine
// KEEP THIS LIST IN ALPHABETICAL ORDER.

CoPageRec CoBrandArray[]=
{		  
	{PageUnknown							,   PT(Unknown),	PT(Unknown),CGI(3),	    HTML,	PICS} , 	
		
	{PageAcceptBid							,		  PT(1),	PT(0),		CGI(0),		HTML,	PICS} ,
	{PageAccountingBatch					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAddAnnouncement					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
// petra	{PageAddNewCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAddNewItem							,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,
	{PageAddNoteAboutUser					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAddNoteAboutUserResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAddToBoard							,		  PT(6),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageAddToItem							,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
	{PageAdminAddCobrandAd					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddCobrandAdConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddCobrandAdShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddCobrandAdToSitePage		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddCobrandAdToSitePageConfirm	,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddExchangeRate				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddFilter						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddFilterShow					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddMessage					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddMessageShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddNoteAboutItem				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminAddNoteAboutItemResult		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminAddPartnerAd					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddPartnerAdConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddPartnerAdShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddPartnerAdToSitePage		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddPartnerAdToSitePageConfirm	,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddPartnerAdToSitePageShow	,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddScreeningCriteria			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAddScreeningCriteriaShow		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminAnnouncement					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminBoardChange					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminBoardChangeShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminChangeEmail					,		  PT(0),	PT(0),		ADMIN,	    HTML,	PICS} ,
	{PageAdminChangeEmailConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminChangeEmailShow				,		  PT(0),	PT(0),		ADMIN,	    HTML,	PICS} ,
	{PageAdminChangeUserId					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminChangeUserIdAskConfirm		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminChangeUserIdShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminCombineUserConf				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminCombineUsers					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminCreditBatch					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminEndAllAuctions				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminEndAllAuctionsConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},	
	{PageAdminEndAllAuctionsResult			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminEndAuction					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminEndAuctionConfirm				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PageAdminEndAuctionResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminEndAuctionShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminGalleryItemDelete			    ,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminGalleryItemDeleteConfirm	    ,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminGalleryItemView			    ,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminModifyFilter					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminModifyMessage					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminMoveAuctionShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminMoveAuctionConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminMoveAuctionResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminRebalanceUserAccount			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReinstateAuction				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReinstateAuctionConfirm		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReinstateAuctionResult		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReinstateAuctionShow			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReInstateItem					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReInstateItemLogin			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminReinstateUser					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminReinstateUserConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminReinstateUserResult			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminReinstateUserShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminRemoveItem					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminRequestPassword				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminResetReqEmailCount			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminSelectCobrandAdPartnerAndPageShow,	  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminSelectCobrandAdSiteShow		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminSetTopSellerLevel				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminSetTopSellerLevelConfirmation	,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminSetTopSellerLevelMultiple		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminShillRelationshipsByFeedback  ,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShillRelationshipsByItem		,		  PT(0),	PT(0),      ADMIN,		HTML,	PICS},
	{PageAdminShillRelationshipsByUsers		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowBiddersSellers			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowCommonAuctions			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowNote						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowNoteShowResult			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowTopSellers				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminShowTopSellerStatus			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminSpecialItemAdd				,		  PT(0),    PT(0),      ADMIN,      HTML,   PICS},
	{PageAdminSpecialItemDelete				,		  PT(0),    PT(0),      ADMIN,      HTML,   PICS},
	{PageAdminSpecialItemFlush				,		  PT(0),    PT(0),      ADMIN,      HTML,   PICS},
	{PageAdminSpecialItemsTool				,		  PT(0),    PT(0),      ADMIN,      HTML,   PICS},
	{PageAdminSuspendUser					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminSuspendUserConfirm			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminSuspendUserResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminSuspendUserShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminUnflagUser					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminUnflagUserConfirm				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminUnflagUserResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminUnflagUserShow				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewBids						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewBlockedItem				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewDailyFinance				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewDailyStats				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewOldItem					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewScreeningCriteria			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminViewScreeningCriteriaShow		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAdminWarnUser						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminWarnUserConfirm				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdminWarnUserResult				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS},
	{PageAdultLogin                         ,         PT(1),	PT(1),      CGI(3),		SECURE,	PICS} ,
	{PageAdultLoginShow                     ,         PT(1),	PT(1),      CGI(3),		SECURE,	PICS} ,
	{PageAggregateReport					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageAOLRegisterComplete				,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterConfirm					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterPreview					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterShow					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterUserAcceptAgreement 	,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterUserAgreement			,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageAOLRegisterUserID					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageBetaConfirmation					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageBetaConfirmationPreview			,		  PT(3),	PT(2),		CGI(4),		HTML,	PICS} ,
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PageBetaConfirmationShow				,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageBetterSeller					    ,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,		
	{PageCCRegConfirm					    ,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,		
	{PageCCRegistrationAcceptAgreement		,		  PT(3),	PT(2),		SECURE,		HTML,	PICS} ,
	{PageCancelBid							,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageCategorizeUserPage					,		  PT(3),	PT(4),		ABOUTME,	HTML,	PICS},
	{PageCategoryAdminRun					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageCategoryHomePage					,		  PT(1),	PT(1),		CGI(3),	    HTML,	PICS} ,
	{PageChangeCategory						,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
	{PageChangeCategoryShow					,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
	{PageChangeCobrandHeader				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageChangeEmail						,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeEmailConfirm					,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeEmailConfirmShow				,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeEmailShow					,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeFeedbackOptions				,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageChangeItemInfo						,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageChangePassword						,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangePasswordCrypted				,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,		
	{PageChangePreferences					,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangePreferencesShow				,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeRegistration					,		  PT(3),	PT(4),		CGI(0),		HTML,	PICS} ,
	{PageChangeRegistrationPreview		    ,		  PT(3),	PT(4),		CGI(4),		HTML,	PICS} ,
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PageChangeRegistrationShow				,		  PT(3),	PT(4),		CGI(0),		HTML,	PICS} ,
	{PageChangeSecretPassword				,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,		
	{PageChangeUserId						,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChangeUserIdShow					,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageChineseAuctionCreditReq			,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageConfirmByCountry					,		  PT(3),	PT(2),		CGI(4),		HTML,	PICS} ,
	{PageConfirmNewGalleryImage             ,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageConfirmUser						,		  PT(3),	PT(2),		ADMIN,		HTML,	PICS} ,
	{PageContacteBay						,		  PT(6),	PT(8),		CGI(3),		HTML,	PICS} ,
	{PageCreateAccount						,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageCreateCobrandPartner				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageCreditBatch						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
// petra	{PageDeleteCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageDeleteDeadbeatItem					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageDisplayGalleryImagePage            ,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageDutchAuctionCreditReq				,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageEmailAuctionToFriend				,		  PT(1),	PT(0),		CGI(3),		HTML,	PICS} ,	
	{PageEndAllAuctions						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageEndAllAuctionsAndCreditFees		,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageEndAuctionAndCreditFees			,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageEnterNewGalleryImage               ,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
	{PageFeatured							,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageFeedbackForum						,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageFixGalleryImage                    ,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageFollowUpFeedback					,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageFollowUpFeedbackShow				,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageGetBidderEmails					,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageGetFeedbackScore					,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageGetItemInfo						,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageGetUserAboutMe						,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,	
	{PageGetUserEmail						,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageGetUserIdHistory					,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageGoToSurvey							,		  PT(6),	PT(8),		CGI(3),		HTML,	PICS} ,	
	{PageHomePage							,		  PT(0),	PT(0),		CGI(3),	    HTML,	PICS} ,
	{PageIEscrowLogin						,		  PT(3),	PT(7),		CGI(3),		HTML,	PICS} ,
	{PageIEscrowShowData					,		  PT(3),	PT(7),		CGI(3),		HTML,	PICS} ,
	{PageIEscrowSendData					,		  PT(3),	PT(7),		CGI(3),		HTML,	PICS} ,
	{PageIIS_Server_status					,		  PT(6),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageInvalidateList						,		  PT(4),	PT(0),		CGI(3),		HTML,	PICS} ,
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PageItemCreditReq						,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageItemInfo							,		  PT(0),	PT(0),		CGI(0),		HTML,	PICS} ,
	{PageLeaveFeedback						,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageLeaveFeedbackShow					,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageListings							,		  PT(0),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PageListItemForSale					,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,
	{PageLocationsTesting				    ,		  PT(0),	PT(0),		CGI(0),		HTML,	PICS} ,
	{PageMakeBid							,		  PT(1),	PT(0),		CGI(0),	    HTML,	PICS} ,
// petra	{PageMakeDelete							,		  PT(1),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageMakeFeatured						,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
// petra	{PageMakeMove							,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageMemberSearchShow					,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,	
// petra	{PageMoveCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageMultipleEmails						,		  PT(4),	PT(2),		CGI(3),	    HTML,	PICS} ,
	{PageMyEbay								,		  PT(3),	PT(4),		CGI(1),		HTML,	PICS} ,
	{PageMyEbayBidder						,		  PT(3),	PT(4),		CGI(1),		HTML,	PICS} ,
	{PageMyEbaySeller						,		  PT(3),	PT(4),		CGI(1),		HTML,	PICS} ,
// petra	{PageNewCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageNewItem							,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,
	{PageNewItemQuick						,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,
	{PageNewTutorial						,         PT(5),	PT(1),      ABOUTME,	HTML,	PICS} ,
	{PageOptinConfirm						    ,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,		
	{PageOptinLogin						    ,		  PT(3),	PT(4),		CGI(3),		HTML,	PICS} ,		
// petra	{PageOrderCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageParseError							,		  PT(0),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PagePassRecognizer						,		  PT(3),	PT(2),		CGI(3),		HTML,	PICS} , 		
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PagePastEssay							,		  PT(6),	PT(5),		CGI(3),		HTML,	PICS} ,
	{PagePayCoupon							,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PagePersonalizedFeedbackLogin			,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PagePersonalShopperViewSearches		,		  PT(4),	PT(3),		CGI(6),		HTML,	PICS} ,	
	{PagePersonalShopperAddSearch			,		  PT(4),	PT(3),		CGI(6),		HTML,	PICS} ,	
	{PagePersonalShopperDeleteSearchView	,		  PT(4),	PT(3),		CGI(6),		HTML,	PICS} ,	
	{PagePersonalShopperDeleteSearch		,		  PT(4),	PT(3),		CGI(6),		HTML,	PICS} ,	
	{PagePersonalShopperSaveSearch			,		  PT(4),	PT(3),		CGI(6),		HTML,	PICS} ,	
	{PagePoliceBadgeLogin                   ,         PT(2),	PT(1),      CGI(5),		SECURE,	PICS} ,
	{PagePowerSellerRegister                ,         PT(3),	PT(3),		CGI(1),		HTML,	PICS} ,
	{PagePowerSellerRegisterShow            ,         PT(3),	PT(3),		CGI(1),		HTML,	PICS} ,
	{PageRecomputeChineseBids				,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageRecomputeDutchBids					,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageRecomputeScore						,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageRedirectEnter						,		  PT(0),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PageRegister							,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageRegisterByCountry                  ,         PT(3),	PT(2),      CGI(4),     HTML,	PICS} ,
	{PageRegisterCC                         ,         PT(3),	PT(2),      SECURE,     HTML,	PICS} ,
	{PageRegisterConfirm					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageRegisterLinkButtons				,		  PT(3),	PT(2),		CGI(3),		HTML,	PICS} ,	
	{PageRegisterPreview					,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageRegisterShow						,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageRegistrationAcceptAgreement		,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,		
	{PageReinstateUser						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageRemoveUserIdCookie                 ,         PT(3),	PT(3),      CGI(3),		HTML,	PICS} ,
	{PageRemoveUserPage						,         PT(3),	PT(5),      ABOUTME,	HTML,	PICS} ,
	{PageReportQuestionableItem             ,         PT(6),	PT(8),      CGI(3),		HTML,	PICS} ,
	{PageReportQuestionableItemShow			,         PT(6),	PT(8),      CGI(3),		HTML,	PICS} ,
	{PageRequestGiftAlert					,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} , 		
	{PageRequestPassword					,		  PT(3),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageRequestRefund						,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageResendConfirmationEmail			,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageRespondFeedback					,		  PT(3),	PT(2),		CGI(2),		HTML,	PICS} ,
	{PageRespondFeedbackShow				,		  PT(3),	PT(2),		CGI(2),		HTML,	PICS} ,
	{PageRetractAllBids						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageRetractBid							,		  PT(3),	PT(3),		CGI(0),		HTML,	PICS} ,
	{PageReturnUserEmail					,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageReturnUserIdHistory				,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageRewriteCobrandHeaders				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageSaveUserPage                       ,         PT(3),	PT(5),      ABOUTME,	HTML,	PICS} ,
	{PageSendGiftAlert						,		  PT(1),	PT(0),		CGI(3),		HTML,	PICS} ,		
	{PageSendQueryEmail						,		  PT(5),	PT(6),		CGI(3),		HTML,	PICS} ,
	{PageSendQueryEmailShow					,		  PT(5),	PT(6),		CGI(3),		HTML,	PICS} ,
	{PageShowCobrandHeaders					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageShowCobrandPartners				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageShowEmailAuctionToFriend			,		  PT(1),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PageShowRegistrationForm				,		  PT(3),	PT(2),		CGI(4),		HTML,	PICS} ,
	{PageStop								,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageSurveyResponse						,		  PT(6),	PT(8),		CGI(3),		HTML,	PICS} ,
	{PageSuspendUser						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	{PageTimeShow							,		  PT(1),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PageUp4SaleTestPassword				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageUpdateAnnouncement					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageUpdateCC                           ,         PT(3),	PT(4),      SECURE,     HTML,	PICS} ,
	{PageUpdateCCConfirm                    ,         PT(3),	PT(3),      SECURE,     HTML,	PICS} ,
// petra	{PageUpdateCategory						,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageUpdateItemInfo						,		  PT(3),	PT(4),		CGI(0),		HTML,	PICS} ,
	{PageUserAgreementAccept				,		  PT(3),	PT(2),		CGI(5),		HTML,	PICS} ,		
	{PageUserAgreementFAQ				    ,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,		
	{PageUserAgreementForBidding			,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageUserAgreementForSelling			,		  PT(3),	PT(2),		CGI(0),		HTML,	PICS} ,
	{PageUserItemVerification				,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
	{PageUserPageAcceptAgreement			,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageConfirmHTMLEditingChoice	,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageConfirmTemplateEditingChoice,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageEditing					,         PT(3),	PT(5),      ABOUTME,	HTML,	PICS} ,
	{PageUserPageGoToHTMLPreview			,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageHandleHTMLPreviewOptions	,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageHandleStyleOptions			,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageHandleTemplateOptions		,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserPageHandleTemplatePreviewOptions,		  PT(3),	PT(5),		ABOUTME,	HTML,	PICS},
	{PageUserQuery							,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageUserSearch							,		  PT(4),	PT(2),		ADMIN,		HTML,	PICS} ,
	{PageValidateUserForSurvey				,		  PT(6),	PT(8),		CGI(3),		HTML,	PICS} ,	
	{PageVerifyAddToItem					,		  PT(3),	PT(3),		CGI(5),		HTML,	PICS} ,
// petra	{PageVerifyNewCategory					,		  PT(4),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageVerifyNewItem						,		  PT(2),	PT(1),		CGI(5),		HTML,	PICS} ,
	{PageVerifyStop							,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
// petra	{PageVerifyUpdateCategory				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageVerifyUpdateItem					,		  PT(2),	PT(1),		CGI(0),		HTML,	PICS} ,
	{PageViewAccount						,		  PT(3),	PT(3),		CGI(3),		HTML,	PICS} ,
	{PageViewAliasHistory					,		  PT(4),	PT(2),		CGI(3),		HTML,	PICS} ,
	{PageViewAllItems						,		  PT(1),	PT(0),		CGI(3),		HTML,	PICS} ,
	{PageViewBidDutchHighBidderEmails		,		  PT(1),	PT(9),		CGI(3),	    HTML,	PICS} ,
	{PageViewBidItems						,		  PT(4),	PT(1),		CGI(3),		HTML,	PICS} ,
	{PageViewBidderWithEmails				,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,
	{PageViewBids							,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,
	{PageViewBidsDutchHighBidder			,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,
	{PageViewBoard							,		  PT(6),	PT(3),		CGI(3),		HTML,	PICS} ,
// petra	{PageViewCategory						,		  PT(4),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageViewDeadbeatUser					,		  PT(4),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageViewDeadbeatUsers					,		  PT(4),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageViewEssay							,		  PT(6),	PT(4),		CGI(3),		HTML,	PICS} ,
	{PageViewFeedback						,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageViewFeedbackLeft					,		  PT(3),	PT(6),		CGI(2),		HTML,	PICS} ,
	{PageViewGiftAlert						,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,
	{PageViewGiftCard						,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,		
	{PageViewGiftItem						,		  PT(1),	PT(9),		CGI(3),		HTML,	PICS} ,
	{PageViewItem							,		  PT(1),	PT(9),		CGI(0),		HTML,	PICS} ,
	{PageViewListedItems					,		  PT(4),	PT(1),		CGI(3),		HTML,	PICS} ,
	{PageViewListedItemsLinkButtons			,		  PT(4),	PT(1),		CGI(3),		HTML,	PICS},
	{PageViewListedItemsWithEmails			,		  PT(4),	PT(1),		CGI(3),		HTML,	PICS},
	{PageViewPersonalizedFeedback			,		  PT(3),	PT(3),		CGI(2),		HTML,	PICS} ,
	{PageViewUserPage						,		  PT(4),	PT(2),		ABOUTME,	HTML,	PICS} ,
	{PageWackoFlagChange					,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,
	{PageWackoFlagChangeConfirm				,		  PT(0),	PT(0),		ADMIN,		HTML,	PICS} ,

	// Warning warning. Make VERY sure that this table is in the exact same order
	// as the enum table in eBayPageTypes.h
	// KEEP THIS LIST IN ALPHABETICAL ORDER
	// All new entries go ABOVE this item.
    {PageLastPossiblePage					,   PT(Unknown),	PT(Unknown),CGI(3),	    HTML,	PICS}
};					
#undef HTML
#undef PT
#undef CGI
#undef ADMIN
#undef ABOUTME
#undef SECURE
#undef PICS

int sNumPageToPageType =
	sizeof (CoBrandArray) / sizeof (CoPageRec) - 1;

// Constructor.
clsPartners::clsPartners(int siteId)
{
	mpvPartners = new vector<clsPartner *>;
	mpCurrentPartner = NULL;
	mpDefaultPartner = NULL;
	// nsacco 05/28/99
	mSiteId = siteId;
	LoadPartners();
}

// Destructor.
clsPartners::~clsPartners()
{
	vector<clsPartner *>::iterator i;

	for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
		delete *i;

	mpvPartners->erase(mpvPartners->begin(), mpvPartners->end());
	delete mpvPartners;

	return;
}

// Loads up the partners (though not the partner header and
// footers), and sets the 'default partner', which does special
// things.
void clsPartners::LoadPartners()
{
	clsDatabase *pDatabase;
	vector<clsPartner *>::iterator i;

	if (!mpvPartners->empty())
	{
		for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
			delete *i;

		mpvPartners->erase(mpvPartners->begin(), mpvPartners->end());
	}

	pDatabase = gApp->GetDatabase();

	pDatabase->LoadPartners(mpvPartners, mSiteId);

	// the default partner has a site id of 0
	// TODO - should this always be dynamically created and use the correct site id?
	mpDefaultPartner = GetPartner("eBay");
	if (!mpDefaultPartner)
	{
		// nsacco 05/28/99 the id for no partners is 0
		mpDefaultPartner = new clsPartner("eBay", PARTNER_EBAY, "Constructed in code");
		mpvPartners->push_back(mpDefaultPartner);
	}

	return;
}

// Gets the current partner, based on the last id set
// (which may be determined from the environment)
clsPartner *clsPartners::GetCurrentPartner()
{
	// nsacco 05/25/99 
	// rewritten 
	char *pServerName;
	char *pScriptName;
	int siteId;
	int	partnerId = INVALID_PARTNER;

	if (mpCurrentPartner == NULL)
	{
		if (gApp->GetEnvironment())
		{
			pServerName = gApp->GetEnvironment()->GetServerName();
			pScriptName = gApp->GetEnvironment()->GetScriptName();
		}
		else
		{
			pServerName = NULL;
			pScriptName = NULL;
		}

		if ((pServerName && *pServerName) && (pScriptName && *pScriptName))
		{
			// from the ServerName determine the partner id
			clsUtilities::GetSiteIDAndPartnerID(pServerName, 
										pScriptName, 
										siteId, 
										partnerId);
		}
		mpCurrentPartner = GetPartner(partnerId);
	}

	return mpCurrentPartner;
}

// Gets a partner by name.
clsPartner *clsPartners::GetPartner(const char *pName)
{
	vector<clsPartner *>::iterator i;

	for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
	{
		if (*i && !strcasecmp((*i)->GetName(), pName))
			return (*i);
	}

	return mpDefaultPartner;
}

// Gets a partner by number.
clsPartner *clsPartners::GetPartner(int id)
{
	// nsacco 05/28/99 
	// modified to actually search for the id in the vector
	if (id < 0)
		return mpDefaultPartner;

	vector<clsPartner *>::iterator i;

	for (i = mpvPartners->begin(); i != mpvPartners->end(); ++i)
	{
		if (*i && ((*i)->GetId() == id))
			return (*i);
	}

	return mpDefaultPartner;
}

// Copies the whole vector -- this is used
// in partner management functions.
void clsPartners::GetAllPartners(vector<clsPartner *> *pvPartners)
{
	pvPartners->insert(pvPartners->end(), mpvPartners->begin(), 
		mpvPartners->end());

	return;
}

// Gets the ePage header in the current partner
// Uses the default partner if there's no header for the
// current partner
const char *clsPartners::GetCurrentHeader(PageEnum ePage, bool withAnnouncements /*=true*/)
{
	const char *pRet;
	PageTypeEnum ePageType;
	PageTypeEnum eSecondaryPageType;										// AlexP
	clsPartner * pPartner;

	pPartner = GetCurrentPartner();
	if (ePage >= sNumPageToPageType || ePage < 0)
		return NULL;

	ePageType = CoBrandArray[ePage + 1].ePageType;
	eSecondaryPageType = CoBrandArray[ePage + 1].eSecondaryPageType;		// AlexP

	pRet = NULL;
	if ( pPartner )
	{		
		if (withAnnouncements) 
			pRet = pPartner->GetHeader(ePageType, eSecondaryPageType);
		else
			pRet = pPartner->GetHeaderWithoutAnnouncement(ePageType, eSecondaryPageType);
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		if (withAnnouncements) 
			pRet = mpDefaultPartner->GetHeader(ePageType, eSecondaryPageType);
		else
			pRet = mpDefaultPartner->GetHeaderWithoutAnnouncement(ePageType, eSecondaryPageType);

	return pRet;
}

// Gets the ePage footer in the current partner
// Uses the default partner if there's no footer for the
// current partner
const char *clsPartners::GetCurrentFooter(PageEnum ePage, bool getAds)
{
	const char *pRet;
	PageTypeEnum ePageType;
	PageTypeEnum eSecondaryPageType;										// AlexP
	clsPartner * pPartner;

	pPartner = GetCurrentPartner();
	if (ePage >= sNumPageToPageType || ePage < 0)
		return NULL;

	ePageType = CoBrandArray[ePage + 1].ePageType;
	eSecondaryPageType = CoBrandArray[ePage + 1].eSecondaryPageType;		// AlexP

	pRet = NULL;
	if ( pPartner )
	{
		// nsacco 07/14/99 no secondary type for footers!
		//pRet = pPartner->GetFooter(ePageType, eSecondaryPageType, getAds);			// AlexP
		pRet = pPartner->GetFooter(ePageType, (PageTypeEnum)0, getAds);	
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
	{
		// nsacco 07/14/99 no secondary type for footers!
		//pRet = mpDefaultPartner->GetFooter(ePageType, eSecondaryPageType, getAds);	// AlexP
		pRet = mpDefaultPartner->GetFooter(ePageType, (PageTypeEnum)0, getAds);	
	}

	return pRet;
}

// GetCurrentCGIPath
// Gets the CGI Path in the current partner for the
// given task.
const char *clsPartners::GetCurrentCGIPath(PageEnum ePage)
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetCGIPath(ePage);
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetCGIPath(ePage);

	return pRet;
}

// GetCurrentHTMLPath
// Gets the HTML Path in the current partner for the
// given task.
const char *clsPartners::GetCurrentHTMLPath(PageEnum ePage)
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		pRet = pPartner->GetHTMLPath(ePage);
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetHTMLPath(ePage);

	return pRet;
}

// GetCurrentPicsPath
// Gets the Pics Path in the current partner for the
// given task.
const char *clsPartners::GetCurrentPicsPath(PageEnum ePage)
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		pRet = pPartner->GetPicsPath(ePage);
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetPicsPath(ePage);

	return pRet;
}

// kakiyama 07/19/99
// GetCurrentSearchPath
// Gets the Search Path in the current partner for the
// given task.
const char *clsPartners::GetCurrentSearchPath(PageEnum ePage)
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		pRet = pPartner->GetSearchPath(ePage);
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetSearchPath(ePage);

	return pRet;
}

// kakiyama 07/19/99
// GetCurrentGalleryListingPath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentGalleryListingPath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetGalleryListingPath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetGalleryListingPath();

	return pRet;
}

// GetCurrentCGIRelativePath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentCGIRelativePath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetCGIRelativePath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetCGIRelativePath();

	return pRet;
}

// GetCurrentHTMLRelativePath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentHTMLRelativePath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetHTMLRelativePath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetHTMLRelativePath();

	return pRet;
}

// GetCurrentPicsLRelativePath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentPicsRelativePath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetPicsRelativePath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetPicsRelativePath();

	return pRet;
}

// GetCurrentListingPath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentListingPath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetListingPath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetListingPath();

	return pRet;
}

// GetCurrentCGIRelativePath
// The path in the current (or default) partner
const char *clsPartners::GetCurrentListingRelativePath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		return pPartner->GetListingRelativePath();
	}
	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetListingRelativePath();

	return pRet;	
}



// Get the description of what the 'page groups' are.
// (Right now it is, in fact, "Page Group")
const char *clsPartners::GetPageDescription(PageTypeEnum ePage)
{
	return "Page Group ";
}

// CreatePartner
// Database hiding function to create a new partner.
// Does not create it in the clsPartners object, only
// in the database, and then the clsPartners object will
// need to be reloaded.
// nsacco 06/21/99 new params siteId and pParsedString
int clsPartners::CreatePartner(const char *pName, const char *pDesc, int siteId, 
							   const char *pParsedString)
{
	return gApp->GetDatabase()->CreateCobrandPartner(pName, pDesc, siteId, pParsedString);
}

// GetPageTypeFromPageEnum
// Do a translation from the task to the type.
PageTypeEnum clsPartners::GetPageTypeFromPageEnum(PageEnum ePage)
{
	PageTypeEnum ePageType;	
	if (ePage >= sNumPageToPageType || ePage < 0)
		return PageTypeUnknown;	
	ePageType = CoBrandArray[ePage + 1].ePageType;
	return ePageType;
}


// GetCurrentAdPicsPath
// Gets the Ad Pics Path in the current partner for the
// given task.
const char *clsPartners::GetCurrentAdPicsPath()
{
	const char *pRet;
	clsPartner *pPartner;

	pRet = NULL;
	pPartner = GetCurrentPartner();
	if (pPartner != NULL)
	{
		pRet = pPartner->GetAdPicsPath();
	}

	if ((!pRet || !*pRet) && mpDefaultPartner)
		pRet = mpDefaultPartner->GetAdPicsPath();

	return pRet;
}