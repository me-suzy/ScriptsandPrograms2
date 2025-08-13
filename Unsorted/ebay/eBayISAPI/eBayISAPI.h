/*	$Id: eBayISAPI.h,v 1.20.2.4.42.2 1999/08/04 00:59:55 phofer Exp $	*/
#if !defined(AFX_EBAYISAPI_H__62697925_1DC8_11D1_B6FA_00A024D30D0B__INCLUDED_)
#define AFX_EBAYISAPI_H__62697925_1DC8_11D1_B6FA_00A024D30D0B__INCLUDED_

// EBAYISAPI.H - Header file for your Internet Server
//    eBayISAPI Extension
// Modifications:
//	06/21/99	nsacco	- Added siteId to ShowCobrandPartners() and
//						  siteId and pParsedString to CreateCobrandPartner()
//	07/02/99	nsacco	- Added siteId and coPartnerId to Register and RegisterPreview
//  07/07/99	nsacco	- Added siteId and coPartnerId to AOL registration functions
//	07/27/99	nsacco	- Added new params to VerifyNewItem, AddNewItem, UpdateItemInfo,
//							VerifyUpdateItem

#include "resource.h"

// min/max macros conflict with STL's
#ifdef _MSC_VER
#undef min
#undef max
#endif

#include "clsEBayHttpServer.h"
#ifndef CLSEBAYAPP_INCLUDED
#include "clseBayApp.h"
#endif

class CEBayISAPIExtension : public clsEBayHttpServer
{
public:
	CEBayISAPIExtension();
	~CEBayISAPIExtension();
	
	// Overrides
	// ClassWizard generated virtual function overrides
	// NOTE - the ClassWizard will add and remove member functions here.
	//    DO NOT EDIT what you see in these blocks of generated code !
	//{{AFX_VIRTUAL(CEBayISAPIExtension)
public:
	virtual BOOL GetExtensionVersion(HSE_VERSION_INFO* pVer);
	//}}AFX_VIRTUAL
	
	// TODO: Add handlers for your commands here.
	// For example:
	
	//
	// User Functions

	friend bool IIS_server_is_down(void); //new outage code

//	int DebugTest(CHttpServerContext* pCtxt);	// Test only

	void StartContent(CHttpServerContext* pCtxt) const;

	void ValidateInternals(CHttpServerContext *pCtxt);

	int ViewListedItems(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int complete,
		int sort,
		int daysSince,
		int include,
		int startingPage,
		int rowsPerPage);
	
	int ViewListedItemsWithEmails(CHttpServerContext* pCtxt,
		LPTSTR pRequester,
		LPTSTR pPass,
		LPTSTR pUser,
		int complete,
		int sort,
		int daysSince,
		int acceptCookie,
		int startingPage,
		int rowsPerPage);
	
	int ViewListedItemsLinkButtons(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int complete,
		int sort,
		int daysSince,
		int include,
		int startingPage,
		int rowsPerPage);

	int ViewBidItems(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int complete,
		int sort,
		int allItems,
		int startingPage,
		int rowsPerPage);
	
	int ViewAllItems(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int complete,
		int sort,
		int daysSince);
	
	int PersonalizedFeedbackLogin(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int itemsPerPage);
	
	int ViewFeedback(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		int startingPage,
		int itemsPerPage);
	
	int ViewPersonalizedFeedback(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		LPTSTR pPass,
		int startingPage,
		int itemsPerPage);
	
	int ViewFeedbackLeft(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		LPTSTR pPass);
	
	int LeaveFeedback(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		LPTSTR pPass,
		LPTSTR pForUser,
		LPTSTR pItemNo,
		LPTSTR pType,
		LPTSTR pComment,
		int confirmNegative);

	int LeaveFeedbackShow(CHttpServerContext* pCtxt,
		LPTSTR pUserTo,
		LPTSTR pUserFrom,
		int itemNo);
	
	//
	// show the page where an user can respond to his/her feedback
	//
	int RespondFeedbackShow(CHttpServerContext *pCtxt,
		int Commentor,
		int CommentDate,
		int Commentee,
		int startingPage,
		int itemsPerPage);

	int RespondFeedback(CHttpServerContext *pCtxt,
		int CommentorId,
		time_t CommentDate,
		LPTSTR pCommentee,
		LPTSTR pPassword,
		LPTSTR pResponse,
		int startingPage,
		int itemsPerPage);
	
	//
	// show the page where an user can follow up on his/her feedback
	//
	int FollowUpFeedbackShow(CHttpServerContext *pCtxt,
		int Commentor,
		int CommentDate,
		int Commentee);

	int FollowUpFeedback(CHttpServerContext *pCtxt,
		int CommentorId,
		time_t CommentDate,
		LPTSTR pCommentee,
		LPTSTR pPassword,
		LPTSTR pFollowUp);
	
	int ChangeFeedbackOptions(CHttpServerContext* pCtxt,
		LPTSTR pUser,
		LPTSTR pPass,
		LPTSTR pOption,
		int startingPage,
		int	itemsPerPage);
	
	int GetFeedbackScore(CHttpServerContext *pCtxt,
		LPTSTR pUser);
	
	int FeedbackForum(CHttpServerContext *pCtxt);
	
	int AddToBoard(CHttpServerContext* pCtxt,
		LPTSTR pBoardName,
		LPTSTR pUser,
		LPTSTR pPass,
		LPTSTR pInfo,
		LPTSTR pLimit,
		int	   FromEssayBoard);
	
	int ViewBoard(CHttpServerContext* pCtxt,
		LPTSTR pBoardName,
		LPTSTR pTimeLimit);
	
	int ViewEssay(CHttpServerContext* pCtxt,
		LPTSTR pBoardName);
	
	int PastEssay(CHttpServerContext* pCtxt);

	int RecomputeScore(CHttpServerContext *pCtxt,
		LPTSTR pUser);
	
	int RegisterShow(CHttpServerContext *pCtxt);
	
	int RegisterByCountry(CHttpServerContext* pCtxt,
						  int countryId,
						  int UsingSSL);

	int ConfirmByCountry(CHttpServerContext* pCtxt,
			   		     int countryId,
						 int withCC);

	int ShowRegistrationForm(CHttpServerContext* pCtxt,
							int countryId,
							int UsingSSL);

	int TimeShow(CHttpServerContext *pCtxt);

	int ResendConfirmationEmail(CHttpServerContext *pCtxt,
							LPTSTR pEmail);
	
	int RegisterPreview(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                 /* Q16 */
								  int UsingSSL,
								  int siteId,	// nsacco 07/02/99
								  int coPartnerId
		);

	
	int Register(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                /* Q16 */
								  bool withcc,
								  int UsingSSL,
								  int siteId,	// nsacco 07/02/99
								  int coPartnerId
		);
	
	int RegisterConfirm(CHttpServerContext *pCtxt,
		LPTSTR pEmail,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pNewPass,
		LPTSTR pNewPass2,
		int notify,
		int countryId);
	
	// Credit Card
	int UpdateCC(CHttpServerContext* pCtxt,
				LPTSTR pUserId,
				LPTSTR pPassword,
				LPTSTR pccNumber,
				LPTSTR pDay,
				LPTSTR pMonth,
				LPTSTR pYear
				);
	int UpdateCCConfirm(CHttpServerContext* pCtxt,
						LPTSTR  userid,
						LPTSTR	ccNumber,
						int		expDate);

	int RegisterCC(	CHttpServerContext *pCtxt,
					int	   UseForPayment,
					LPTSTR pEamil,
					LPTSTR pOldPass,
					LPTSTR pUserID,
					LPTSTR pNewPass,
					LPTSTR pNewPassAgain,
					LPTSTR pUserName,
					LPTSTR pStreetAddr,
					LPTSTR pCityAddr,
					LPTSTR pStateProvAddr,
					LPTSTR pZipCodeAddr,
					LPTSTR pCountryAddr,
					LPTSTR pCC,
					LPTSTR pMonth,
					LPTSTR pDay,
					LPTSTR pYear,
					int    notify);

	int ChangeEmail(CHttpServerContext *pCtxt);
	
	int ChangeEmailShow(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPassword,
		LPTSTR pNewEmail);
	
	int ChangeEmailConfirm(CHttpServerContext *pCtxt);
	
	int ChangeEmailConfirmShow(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pNewUserId,
		LPTSTR pPass);
	
	int ChangePassword(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pNewPass,
		LPTSTR pNewPass2);
	
	int	UserQuery(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pRequestedUserId);
	
	int ChangeUserId(CHttpServerContext *pCtxt,
		LPTSTR pUserId);
	
	int ChangeUserIdShow(CHttpServerContext *pCtxt,
		LPTSTR pOldUserId,
		LPTSTR pPassword,
		LPTSTR pNewUserId);
	
	int	ChangeRegistrationShow(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		int    UsingSSL);
	
	int ChangeRegistration(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pName,
		LPTSTR pCompany,
		LPTSTR pAddress,
		LPTSTR pCity,
		LPTSTR pState,
		LPTSTR pOtherState,
		LPTSTR pZip,
		LPTSTR pCountry,
		int    countryId,
		LPTSTR pDayPhone,
		LPTSTR pNightPhone,
		LPTSTR pFaxPhone,
		LPTSTR pGender,
		int    UsingSSL);

	int ChangeRegistrationPreview(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pName,
		LPTSTR pCompany,
		LPTSTR pAddress,
		LPTSTR pCity,
		LPTSTR pState,
		LPTSTR pOtherState,
		LPTSTR pZip,
		int    countryId,
		LPTSTR pDayPhone,
		LPTSTR pNightPhone,
		LPTSTR pFaxPhone,
		LPTSTR pGender,
		int    UsingSSL
		);

	int	ChangePreferencesShow(CHttpServerContext *pCtxt,
							   LPTSTR pUserId,
							   LPTSTR pPass,
							   bool   oldStyle);

	int ChangePreferences(CHttpServerContext *pCtxt,
							LPTSTR pUserId,
							LPTSTR pPass,
							int interest_1,
							int interest_2,
							int interest_3,
							int interest_4,
							LPCTSTR pCatMenu1_0,	// dummy
							LPCTSTR pCatMenu1_1,	// dummy
							LPCTSTR pCatMenu1_2,	// dummy
							LPCTSTR pCatMenu1_3,	// dummy
							LPCTSTR pCatMenu2_0,	// dummy
							LPCTSTR pCatMenu2_1,	// dummy
							LPCTSTR pCatMenu2_2,	// dummy
							LPCTSTR pCatMenu2_3,	// dummy
							LPCTSTR pCatMenu3_0,	// dummy
							LPCTSTR pCatMenu3_1,	// dummy
							LPCTSTR pCatMenu3_2,	// dummy
							LPCTSTR pCatMenu3_3,	// dummy
							LPCTSTR pCatMenu4_0,	// dummy
							LPCTSTR pCatMenu4_1,	// dummy
							LPCTSTR pCatMenu4_2,	// dummy
							LPCTSTR pCatMenu4_3		// dummy
							);
	
	int	RequestPassword(CHttpServerContext *pCtxt,
		LPTSTR pUserId);
	
	int	AdminRequestPassword(CHttpServerContext *pCtxt,
		LPTSTR pUserId);
	
	
	int	ViewAccount(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		int entire,
		int sinceLastInvoice,
		int monthsback,
		LPTSTR pStartDate,
		LPTSTR pEndDate);
	
	int	MyEbay(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pFirst,
		/* LPTSTR pZone, */
		int sellerSort,
		int bidderSort,
		int daysSince,
		int prefFavo,
		int prefFeed,
		int prefBala,
		int prefSell,
		int prefBidd);
	
	int	MyEbaySeller(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		int sort,
		int daysSince);
	
	int	MyEbayBidder(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		int sort,
		int daysSince);
	
	int	PayCoupon(CHttpServerContext *pCtxt,
					LPTSTR pUserId,
				    LPTSTR pPass,
					LPTSTR pymtType);

	int	RequestRefund(CHttpServerContext *pCtxt,
					LPTSTR pUserId,
				    LPTSTR pPass);

	int	BetaConfirmationShow(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass);
	
	int BetaConfirmation(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pEmail,
		LPTSTR pPass,
		LPTSTR pName,
		LPTSTR pCompany,
		LPTSTR pAddress,
		LPTSTR pCity,
		LPTSTR pState,
		LPTSTR pZip,
		LPTSTR pCountry,
		LPTSTR pDayPhone,
		LPTSTR pNightPhone,
		LPTSTR pFaxPhone,
		LPTSTR pGender);
		
	int BetaConfirmationPreview(CHttpServerContext *pCtxt,
		LPTSTR pUserId,
		LPTSTR pEmail,
		LPTSTR pPass,
		LPTSTR pName,
		LPTSTR pCompany,
		LPTSTR pAddress,
		LPTSTR pCity,
		LPTSTR pState,
		LPTSTR pZip,
		LPTSTR pCountry,
		LPTSTR pDayPhone,
		LPTSTR pNightPhone,
		LPTSTR pFaxPhone,
		LPTSTR pGender);

	int CreateAccount(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		LPTSTR pPassword);
	
	void GetUserByAlias(CHttpServerContext* pCtxt,
		LPTSTR pRequestedUserId,
		LPTSTR pRequestorUserId,
		LPTSTR pRequestorPass);

	void GetItemInfo(CHttpServerContext* pCtxt, 
				LPTSTR pItemNo, 
				LPTSTR pUserId,
				LPTSTR pPass,
				bool   oldStyle);

	// nsacco 07/27/99 added new params
	int VerifyUpdateItem(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pItemNo,
		LPCTSTR pTitle,
		LPCTSTR pDesc,
		LPCTSTR pPicUrl,
		LPCTSTR pCategory1,
		LPCTSTR pCategory2,
		LPCTSTR pCategory3,
		LPCTSTR pCategory4,
		LPCTSTR pCategory5,
		LPCTSTR pCategory6,
		LPCTSTR pCategory7,
		LPCTSTR pCategory8,
		LPCTSTR pCategory9,
		LPCTSTR pCategory10,
		LPCTSTR pCategory11,
		LPCTSTR pCategory12,
		LPCTSTR pMoneyOrderAccepted,
		LPCTSTR pPersonalChecksAccepted,
		LPCTSTR pVisaMasterCardAccepted,
		LPCTSTR pDiscoverAccepted,
	    LPCTSTR pAmExAccepted,
		LPCTSTR pOtherAccepted,
		LPCTSTR pOnlineEscrowAccepted,
		LPCTSTR pCODAccepted,
		LPCTSTR pPaymentSeeDescription,
		LPCTSTR pSellerPaysShipping,
		LPCTSTR pBuyerPaysShippingFixed,
		LPCTSTR pBuyerPaysShippingActual,
		LPCTSTR pShippingSeeDescription,
		LPCTSTR pShippingInternationally,
		LPCTSTR pShipToNorthAmerica,
		LPCTSTR pShipToEurope,
		LPCTSTR pShipToOceania,
		LPCTSTR pShipToAsia,
		LPCTSTR pShipToSouthAmerica,
		LPCTSTR pShipToAfrica,
		int siteId,
		int descLang,
		LPCTSTR pCatMenu_0,		// dummy
		LPCTSTR pCatMenu_1,		// dummy
		LPCTSTR pCatMenu_2,		// dummy
		LPCTSTR pCatMenu_3		// dummy
		);

	// nsacco 07/27/99 added new params
	int UpdateItemInfo(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pTitle,
		LPCTSTR pDesc,
		LPCTSTR pPicUrl,
		LPCTSTR pCategory,
		LPCTSTR pMoneyOrderAccepted,
		LPCTSTR pPersonalChecksAccepted,
		LPCTSTR pVisaMasterCardAccepted,
		LPCTSTR pDiscoverAccepted,
	    LPCTSTR pAmExAccepted,
		LPCTSTR pOtherAccepted,
		LPCTSTR pOnlineEscrowAccepted,
		LPCTSTR pCODAccepted,
		LPCTSTR pPaymentSeeDescription,
		LPCTSTR pSellerPaysShipping,
		LPCTSTR pBuyerPaysShippingFixed,
		LPCTSTR pBuyerPaysShippingActual,
		LPCTSTR pShippingSeeDescription,
		LPCTSTR pShippingInternationally,
		LPCTSTR pShipToNorthAmerica,
		LPCTSTR pShipToEurope,
		LPCTSTR pShipToOceania,
		LPCTSTR pShipToAsia,
		LPCTSTR pShipToSouthAmerica,
		LPCTSTR pShipToAfrica,
		int siteId,
		int descLang
		);


	void UserItemVerification(CHttpServerContext* pCtxt, 
				LPTSTR pItemNo);

	void GetUserEmail(CHttpServerContext* pCtxt,
		LPTSTR pUserId);

	int GetUserAboutMe(CHttpServerContext* pCtxt,
		LPTSTR pUserId);

	void MemberSearchShow(CHttpServerContext* pCtxt);

	void ValidateUserForSurvey(CHttpServerContext* pCtxt, int surveyid);

	int GoToSurvey(CHttpServerContext* pCtxt,
				   LPTSTR pUserId, LPTSTR pPassword, int surveyID);

	void ContacteBay(CHttpServerContext* pCtxt, int itemID);
	
	void ReturnUserEmail(CHttpServerContext* pCtxt,
		LPTSTR pRequestedUserId,
		LPTSTR pRequestorUserId,
		LPTSTR pRequestorPass,
		int acceptCookie);
	
	void ViewAliasHistory(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass);
	
	void GetUserIdHistory(CHttpServerContext* pCtxt,
		LPTSTR pUserId);
	
	void ReturnUserIdHistory(CHttpServerContext* pCtxt,
		LPTSTR pRequestedUserId,
		LPTSTR pRequestorUserId,
		LPTSTR pRequestorPass,
		int acceptCookie);
	
	void MultipleEmails(CHttpServerContext *pCtxt, 
		LPTSTR pRequestedUserIds,
		LPTSTR pRequestorUserId,
		LPTSTR pRequestorPass,
		int acceptCookie);

	void GetMultipleEmails(CHttpServerContext *pCtxt);

	//
	// Item Functions
	//
	void ViewItem(CHttpServerContext* pCtxt, LPCTSTR pItemNo,
		LPCTSTR pItemRow, 
		int     time,
		LPCTSTR tc);
	
	void AddToItem(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo,
		LPCTSTR pUserId,
		LPCTSTR pPass,
		LPCTSTR pAddition);
	
	void VerifyAddToItem(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo,
		LPCTSTR pUserId,
		LPCTSTR pPass,
		LPCTSTR pAddition);
	
	void VerifyStop(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo,
		LPCTSTR pUserId,
		LPCTSTR pPass);
	
	void Stop(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo,
		LPCTSTR pUserId,
		LPCTSTR pPass);
	
	void Featured(CHttpServerContext* pCtxt);
	
	void MakeFeatured(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPass,
		LPCTSTR pItemNo,
		LPCTSTR pTypeSuper,
		LPCTSTR pTypeFeature);
	
	void ChangeCategoryShow(CHttpServerContext* pCtxt, 
		int item, bool oldStyle);
	
	
	void ChangeCategory(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPass,
		int item,
		int newCategory,
		LPCTSTR pCatMenu_0,		// dummy
		LPCTSTR pCatMenu_1,		// dummy
		LPCTSTR pCatMenu_2,		// dummy
		LPCTSTR pCatMenu_3		// dummy
		);
	
	
	void NewItem(CHttpServerContext* pCtxt, LPCTSTR pItemNo, LPCTSTR pCatNo);
	
	void NewItemQuick(CHttpServerContext* pCtxt, LPCTSTR pItemNo, LPCTSTR pCatNo);

	void ListItemForSale(CHttpServerContext* pCtxt, LPCTSTR pItemNo, LPCTSTR pCatNo, int oldStyle);
	
	void BetterSeller(CHttpServerContext* pCtxt, int ItemNo);
	
	// nsacco 07/27/99 added new params
	void VerifyNewItem(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pTitle,
		LPCTSTR pLocation,
		LPCTSTR pReserve,
		LPCTSTR pStartPrice,
		LPCTSTR pQuantity,
		LPCTSTR pDuration,
		LPCTSTR pBold,
		LPCTSTR pFeatured,
		LPCTSTR pSuperFeatured,
		LPCTSTR pPrivate,
		LPCTSTR pDesc,
		LPCTSTR pPicUrl,
		LPCTSTR pCategory1,
		LPCTSTR pCategory2,
		LPCTSTR pCategory3,
		LPCTSTR pCategory4,
		LPCTSTR pCategory5,
		LPCTSTR pCategory6,
		LPCTSTR pCategory7,
		LPCTSTR pCategory8,
		LPCTSTR pCategory9,
		LPCTSTR pCategory10,
		LPCTSTR pCategory11,
		LPCTSTR pCategory12,
		LPCTSTR pOldItemNo,
		LPCTSTR pOldKey,
		LPSTR   accept,
		LPSTR   decline,
		int     notify,
		LPCTSTR pMoneyOrderAccepted,
		LPCTSTR pPersonalChecksAccepted,
		LPCTSTR pVisaMasterCardAccepted,
		LPCTSTR pDiscoverAccepted,
	    LPCTSTR pAmExAccepted,
		LPCTSTR pOtherAccepted,
		LPCTSTR pOnlineEscrowAccepted,
		LPCTSTR pCODAccepted,
		LPCTSTR pPaymentSeeDescription,
		LPCTSTR pSellerPaysShipping,
		LPCTSTR pBuyerPaysShippingFixed,
		LPCTSTR pBuyerPaysShippingActual,
		LPCTSTR pShippingSeeDescription,
		LPCTSTR pShippingInternationally,
		LPCTSTR pShipToNorthAmerica,
		LPCTSTR pShipToEurope,
		LPCTSTR pShipToOceania,
		LPCTSTR pShipToAsia,
		LPCTSTR pShipToSouthAmerica,
		LPCTSTR pShipToAfrica,
		int		siteId,
		int		descLang,
		LPCTSTR pGiftIcon,
		int     gallery,
		LPCTSTR	pGalleryUrl,
		int		countryId,
		int     currencyId,
		LPCTSTR pZip,
		LPCTSTR pCatMenu_0,		// dummy
		LPCTSTR pCatMenu_1,		// dummy
		LPCTSTR pCatMenu_2,		// dummy
		LPCTSTR pCatMenu_3		// dummy
		);
	
	// nsacco 07/27/99 added new shipping params, language, siteid
	void AddNewItem(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pItemNo,
		LPCTSTR pTitle,
		LPCTSTR pReserve,
		LPCTSTR pStartPrice,
		LPCTSTR pQuantity,
		LPCTSTR pDuration,
		LPCTSTR pLocation,
		LPCTSTR pBold,
		LPCTSTR pFeatured,
		LPCTSTR pSuperFeatured,
		LPCTSTR pPrivate,
		LPCTSTR pDesc,
		LPCTSTR pPicUrl,
		LPCTSTR pCategory,
		LPCTSTR pKey,
		LPCTSTR pOldItemNo,
		LPCTSTR pOldKey,
		LPCTSTR pMoneyOrderAccepted,
		LPCTSTR pPersonalChecksAccepted,
		LPCTSTR pVisaMasterCardAccepted,
		LPCTSTR pDiscoverAccepted,
		LPCTSTR pAmExAccepted,
		LPCTSTR pOtherAccepted,
		LPCTSTR pOnlineEscrowAccepted,
		LPCTSTR pCODAccepted,
		LPCTSTR pPaymentSeeDescription,
		LPCTSTR pSellerPaysShipping,
		LPCTSTR pBuyerPaysShippingFixed,
		LPCTSTR pBuyerPaysShippingActual,
		LPCTSTR pShippingSeeDescription,
		LPCTSTR pShippingInternationally,
		LPCTSTR pShipToNorthAmerica,
		LPCTSTR pShipToEurope,
		LPCTSTR pShipToOceania,
		LPCTSTR pShipToAsia,
		LPCTSTR pShipToSouthAmerica,
		LPCTSTR pShipToAfrica,
		int siteId,
		int descLang,
		LPCTSTR pGiftIcon,
		int 	gallery,
		LPCTSTR	pGalleryUrl,
		int		countryId,
		int     currencyId,
		LPCTSTR pZip
		);
	
	void RecomputeDutchBids(CHttpServerContext* pCtxt, LPCTSTR pItemNo);
	void RecomputeChineseBids(CHttpServerContext* pCtxt, LPCTSTR pItemNo);
	
	
	//
	// Bid Functions
	//
	int MakeBid(CHttpServerContext* pCtxt,
		int item,
//		LPTSTR pUserId,
//		LPTSTR pPass,
		LPTSTR pMaxbid,
		int quant,
		LPSTR accept,
		LPSTR decline,
		int notify);
	
	int AcceptBid(CHttpServerContext* pCtxt,
		int item,
		LPTSTR pKey,
		LPTSTR pUserId,
		LPTSTR pPass,
		LPTSTR pMaxbid,
		int quant,
		LPSTR accept,
		LPSTR decline,
		int notify);
	
	int RetractBid(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		LPTSTR pPass,
		int item,
		LPTSTR pReason);
	
	int CancelBid(CHttpServerContext* pCtxt,
		LPTSTR pSellerUserId,
		LPTSTR pSellerPass,
		int item,
		LPTSTR pUserId,
		LPTSTR pReason);
	
	int ViewBids(CHttpServerContext* pCtxt,
		int item);
	
	int	ViewBidderWithEmails(CHttpServerContext* pCtxt,
		int item, LPCTSTR pUserId, LPCTSTR pPass, int acceptCookie);
	
	int ViewBidsDutchHighBidder(CHttpServerContext* pCtxt,
		int item);
	
	int GetBidderEmails(CHttpServerContext* pCtxt,
		int item, int PageType);
	
	int ViewBidDutchHighBidderEmails(CHttpServerContext* pCtxt,
		int item, LPCTSTR pUserId, LPCTSTR pPass, int acceptCookie);
	
	//
	// Admin
	//
	int AdminAddExchangeRate(CHttpServerContext* pCtxt,
							     LPTSTR login, 
								 LPTSTR password,
								 int    month,
								 int	day,
								 int	year,
								 int    fromcurrency,
								 int    tocurrency,
								 LPCSTR newrate);

	int AdminViewBids(CHttpServerContext* pCtxt,
		int item);

	int AdminShillRelationshipsByItem(CHttpServerContext *pCtxt, LPCTSTR details, int item, int limit);
	int AdminShillRelationshipsByUsers(CHttpServerContext* pCtxt, LPCTSTR details, LPCTSTR userlist, int limit);
	int AdminShowBiddersSellers(CHttpServerContext* pCtxt, LPCTSTR bidder);
	int AdminShowCommonAuctions(CHttpServerContext *pCtxt, LPCTSTR userlist);
	int AdminShillRelationshipsByFeedback(CHttpServerContext *pCtxt,
		LPCSTR details,
		LPCSTR user,
		LPCSTR left,
		int count,
		int age,
		int limit);
	int AdminGetShillCandidates(CHttpServerContext *pCtxt);
	int AdminShowBiddersRetractions(CHttpServerContext *pCtxt, int id, int limit);

	void AdminViewOldItem(CHttpServerContext* pCtxt, 
		LPCTSTR pItemNo);
	
	int UserSearch(CHttpServerContext* pCtxt,
		LPTSTR pString,
		int how);
	
	int	ItemInfo(CHttpServerContext* pCtxt,
		LPTSTR pAction,
		LPTSTR pItemNo,
		LPTSTR pTitle,
		LPTSTR pQuantity,
		LPTSTR pcEndTime,
		LPTSTR pcEndTimeHour,
		LPTSTR pcEndTimeMin,
		LPTSTR pcEndTimeSec,
		int    featured,		
		int    superfeatured,	
		LPTSTR pDescription,
		int galleryfeatured,
		int gallery,
		LPTSTR pGiftIcon);
	
	int ChangeItemInfo(CHttpServerContext* pCtxt,
		LPTSTR pItemNo);
	
	int	CreditBatch(CHttpServerContext* pCtxt,
					LPTSTR pText,
					int doit,
					LPTSTR pPassword); 

	int	CreditBatch2(CHttpServerContext* pCtxt,
					 LPTSTR pText,
					 int doit,
					 LPTSTR pPassword);

	int CreditDump(	CHttpServerContext* pCtxt,
					LPTSTR pUserId,
					LPTSTR pPass);

	int ItemCreditReq( CHttpServerContext* pCtxt,
					   LPTSTR pUserId,
					   LPTSTR pPass,
					   LPTSTR pItemNo,
					   int	  moreCredits
					   );

	int ChineseAuctionCreditReq(CHttpServerContext* pCtxt,
								LPTSTR	pItemNo,
								int		arc,
								int		wasPaid,
								LPTSTR	pAmt,
								int		reason);

	int DutchAuctionCreditReq(	CHttpServerContext* pCtxt,
								LPTSTR pItemNo,
								int		arc,
								int	   wasPaid1,
								LPTSTR pAmt1,
								int	   reason1,
								LPTSTR pEmail1,
								int	   wasPaid2,
								LPTSTR pAmt2,
								int	   reason2,
								LPTSTR pEmail2,
								int	   wasPaid3,
								LPTSTR pAmt3,
								int	   reason3,
								LPTSTR pEmail3,
								int	   wasPaid4,
								LPTSTR pAmt4,
								int	   reason4,
								LPTSTR pEmail4,
								int	   wasPaid5,
								LPTSTR pAmt5,
								int	   reason5,
								LPTSTR pEmail5,
								int	   moreCredits
							);

	int	AccountBatch(CHttpServerContext* pCtxt,
		LPTSTR pText,
		int doit,
		LPTSTR pPassword);

	void AdminWarnUserShow(CHttpServerContext *pCtxt,
						   LPTSTR pUser,
						   LPTSTR pPass,
						   LPTSTR pTarget,
						   int type,
						   LPTSTR pText);

	void AdminWarnUserConfirm(CHttpServerContext *pCtxt,
						   LPTSTR pUser,
						   LPTSTR pPass,
						   LPTSTR pTarget,
						   int type,
						   LPTSTR pText);

	void AdminWarnUser(CHttpServerContext *pCtxt,
						   LPTSTR pUser,
						   LPTSTR pPass,
						   LPTSTR pTarget,
						   int type,
						   LPTSTR pText,
						   LPTSTR pEmailSubject,
						   LPTSTR pEmailText);

	void AdminSuspendUserShow(CHttpServerContext *pCtxt,
							  LPTSTR pUser,
							  LPTSTR pPass,
							  LPTSTR pTarget,
							  int type,
							  LPTSTR pText);

	void AdminSuspendUserConfirm(CHttpServerContext *pCtxt,
							     LPTSTR pUser,
							     LPTSTR pPass,
							     LPTSTR pTarget,
							     int type,
							     LPTSTR pText);

	void AdminSuspendUser(CHttpServerContext *pCtxt,
						  LPTSTR pUser,
						  LPTSTR pPass,
						  LPTSTR pTarget,
						  int type,
						  LPTSTR pText,
						  LPTSTR pEmailSubject,
						  LPTSTR pEmailText);

	void AdminReinstateUserShow(CHttpServerContext *pCtxt,
							    LPTSTR pUser,
							    LPTSTR pPass,
							    LPTSTR pTarget,
							    int type,
							    LPTSTR pText);

	void AdminReinstateUserConfirm(CHttpServerContext *pCtxt,
							       LPTSTR pUser,
							       LPTSTR pPass,
							       LPTSTR pTarget,
							       int type,
							       LPTSTR pText);

	void AdminReinstateUser(CHttpServerContext *pCtxt,
						    LPTSTR pUser,
						    LPTSTR pPass,
						    LPTSTR pTarget,
						    int type,
						    LPTSTR pText,
						    LPTSTR pEmailSubject,
						    LPTSTR pEmailText);

	
	//int SuspendUser(CHttpServerContext* pCtxt,
	//	LPTSTR pUserId);
	
	int AdminResetReqEmailCount(CHttpServerContext* pCtxt,
		LPTSTR pUserId);
	
	int AdminResetReqUserCount(CHttpServerContext* pCtxt,
		LPTSTR pUserId);

	//int ReinstateUser(CHttpServerContext* pCtxt,
	//	LPTSTR pUserId);
	
	int	ConfirmUser(CHttpServerContext* pCtxt,
		LPTSTR pUserId);
	
	int	AdminEndAuctionShow(CHttpServerContext* pCtxt,
							LPTSTR pUserId,
							LPTSTR pPass,
							char *pItemId,
							int suspended,
							int creditfees,
							int emailbidders,
							int type,
							int buddy,
							LPTSTR pText);

	int	AdminEndAuctionConfirm(CHttpServerContext* pCtxt,
							   LPTSTR pUserId,
							   LPTSTR pPass,
							   char *pItemId,
							   int suspended,
							   int creditfees,
							   int emailbidders,
							   int type,
							   int buddy,
							   LPTSTR pText);

	int	AdminEndAuction(CHttpServerContext* pCtxt,
						LPTSTR pUserId,
						LPTSTR pPass,
						char *pItemId,
						int suspended,
						int creditfees,
						int emailbidders,
						int type,
						int buddy,
						LPTSTR pText,
						LPTSTR pSellerEmailSubject,
						LPTSTR pSellerEmailText,
						LPTSTR pBidderEmailSubject,
						LPTSTR pBidderEmailText,
						LPTSTR pBuddyEmailAddress,
						LPTSTR pBuddyEmailSubject,
						LPTSTR pBuddyEmailText);

	int	AdminEndAllAuctionsShow(CHttpServerContext* pCtxt,
								LPTSTR pUserId,
								LPTSTR pPass,
								LPTSTR pTargetUser,
								int suspended,
								int creditfees,
								int emailbidders,
								int type,
								int buddy,
								LPTSTR pText);

	int	AdminEndAllAuctionsConfirm(CHttpServerContext* pCtxt,
								   LPTSTR pUserId,
								   LPTSTR pPass,
								   LPTSTR pTargetUser,
								   int suspended,
								   int creditfees,
								   int emailbidders,
								   int type,
								   int buddy,
								   LPTSTR pText);

	int	AdminEndAllAuctions(CHttpServerContext* pCtxt,
							LPTSTR pUserId,
							LPTSTR pPass,
							LPTSTR pTargetUser,
							int suspended,
							int creditfees,
							int emailbidders,
							int type,
							int buddy,
							LPTSTR pText,
							LPTSTR pSellerEmailSubject,
							LPTSTR pSellerEmailText,
							LPTSTR pBidderEmailSubject,
							LPTSTR pBidderEmailText,
							LPTSTR pBuddyEmailAddress,
							LPTSTR pBuddyEmailSubject,
							LPTSTR pBuddyEmailText);
	
	int AdminMoveAuctionShow(CHttpServerContext* pCtxt,
							 LPTSTR pUser,
							 LPTSTR pPass,
							 LPTSTR pItemId,
							 int category,
							 int emailsellers,
							 int chargesellers,
							 LPTSTR pText);

	int AdminMoveAuctionConfirm(CHttpServerContext* pCtxt,
								LPTSTR pUser,
								LPTSTR pPass,
								char *pItemId,
								int category,
								int emailsellers,
								int chargesellers,
								LPTSTR pText);

	int AdminMoveAuction(CHttpServerContext* pCtxt,
						 LPTSTR pUser,
						 LPTSTR pPass,
						 char *pItemId,
						 int category,
						 int emailseller,
						 int chargeseller,
						 LPTSTR pText,
						 LPTSTR pSellerEmailSubject,
						 LPTSTR pSellerEmailText);

	int	RetractAllBids(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		int CautionToTheWind);
	
	int AdminCombineUsers(CHttpServerContext* pCtxt,
		LPTSTR pOldUserId,
		LPTSTR pOldPass,
		LPTSTR pNewUserId,
		LPTSTR pNewPass);
	
	int AdminCombineUserConf(CHttpServerContext* pCtxt,
		LPTSTR pOldUserId,
		LPTSTR pOldPass,
		LPTSTR pNewUserId,
		LPTSTR pNewPass);
	
	int AdminChangeEmail(CHttpServerContext* pCtxt,
		LPTSTR pUserId);
	
	int AdminChangeEmailShow(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		LPTSTR pNewEmail);
	
	int AdminChangeEmailConfirm(CHttpServerContext* pCtxt,
		LPTSTR pUserId,
		LPTSTR pNewEmail,
		int	Change);
	
	// Added by Charles
	int AdminChangeUserId(CHttpServerContext *pCtxt,
		LPTSTR pOldUserId,
		LPTSTR pPassword,
		LPTSTR pNewUserId,
		int	   confirm);
	
	int AdminChangeUserIdShow(CHttpServerContext *pCtxt,
		LPTSTR pUserId);
	
	BOOL OnParseError(CHttpServerContext* pCtxt, int nCause);
	
	// Category Admin functions
	
	void CategoryAdmin(CHttpServerContext* pCtxt);
	
	void CategoryChecker(CHttpServerContext* pCtxt);

/* petra 06/15/99 wired off ----------------------------------------
	void ViewCategory(CHttpServerContext* pCtxt);
	
	void VerifyUpdateCategory(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pCategory);
	
	void UpdateCategory(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pCategory,
		LPCTSTR pName,
		LPCTSTR pDesc,
		LPCTSTR pFileRef,
		LPCTSTR pFeaturedCost,
		LPCTSTR pAdult,
		LPCTSTR pExpired
		);
------------------------------------------ petra *
	
	void NewCategory(CHttpServerContext* pCtxt);
	
	void VerifyNewCategory(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pName,
		LPCTSTR pDesc,
		LPCTSTR pAdult,
		LPCTSTR pFeaturedCost,
		LPCTSTR pFileRef,
		LPCTSTR pCategory,
		LPCTSTR pAddAction
		);

* petra 06/15/99 wired off ------------------------------------------	
	void AddNewCategory(CHttpServerContext* pCtxt, 
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pName,
		LPCTSTR pDesc,
		LPCTSTR pAdult,
		LPCTSTR pFeaturedCost,
		LPCTSTR pFileRef,
		LPCTSTR pCategory,
		LPCTSTR pAddAction
		);
	
	void DeleteCategory(CHttpServerContext* pCtxt);
	
	void MakeDelete(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pCategory);
	
	void MoveCategory(CHttpServerContext* pCtxt);
	
	void MakeMove(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pFromCategory,
		LPCTSTR pToCategory);
	
	void OrderCategory(CHttpServerContext* pCtxt);
--------------------------------------------- petra */
	
	//
	// Statistics
	//
	void AdminViewDailyStats(CHttpServerContext* pCtxt,
		int	StartMon,
		int	StartDay,
		int	StartYear,
		int	EndMon,
		int	EndDay,
		int	EndYear,
		LPCTSTR pEmail,
		LPCTSTR pPass);
	
	//
	// Daily Finance
	//
	void AdminViewDailyFinance(CHttpServerContext* pCtxt,
		int	StartMon,
		int	StartDay,
		int	StartYear,
		int	EndMon,
		int	EndDay,
		int	EndYear,
		LPCTSTR pEmail,
		LPCTSTR pPass);
	
	// Announcement Admin functions
	
	void AdminAnnouncement(CHttpServerContext* pCtxt, int SiteId, int PartnerId);
	
	void UpdateAnnouncement(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPass,
		LPCTSTR pId,
		LPCTSTR pLoc,
		LPCTSTR pSiteId,
		LPCTSTR pPartnerId);
	
	void AddAnnouncement(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPass,
		LPCTSTR pId,
		LPCTSTR pLoc,
		LPCTSTR pCode,
		LPCTSTR pDesc,
		LPCTSTR pSiteId,
		LPCTSTR pPartnerId
		);
	
	void SurveyResponse(CHttpServerContext* pCtxt,
		LPCTSTR pUserId,
		LPCTSTR pPassword,
		LPCTSTR pSurveyId,
		LPCTSTR pQuestionId,
		LPCTSTR pResponse);
	
	void RedirectEnter(CHttpServerContext* pCtxt,
		LPCTSTR pLocation,
		LPCTSTR pPartnerName);
	
	// nsacco 06/21/99
	void ShowCobrandPartners(CHttpServerContext *pCtxt,
		int siteId);
	void RewriteCobrandHeaders(CHttpServerContext *pCtxt);
	
	void ShowCobrandHeaders(CHttpServerContext *pCtxt,
		int partnerId, int siteId);
	
	void ChangeCobrandHeader(CHttpServerContext *pCtxt,
		LPCTSTR pNewDescription,
		int isHeader,
		int pageType,
		int partnerId,
		int pageType2,
		int siteId);
	
	// nsacco 06/21/99 added siteId and pParsedString
	void CreateCobrandPartner(CHttpServerContext *pCtxt,
		LPCTSTR pName, 
		LPCTSTR pDesc,
		int siteId,
		LPCTSTR pParsedString);
	
	void UpdateCobrandCaching(CHttpServerContext *pCtxt);
	
	void ShowEmailAuctionToFriend(CHttpServerContext* pCtxt,int item);				  
	void EmailAuctionToFriend(CHttpServerContext *pCtxt,
		int item, 
		char *userid,
		char *password,
		//char *friendname,
		char *email,
		char *message,
		char *htmlenable);
	
	int AdminInvalidateList(CHttpServerContext *pCtxt,
		LPTSTR pUser, int code);
	
	void AdminBoardChangeShow(CHttpServerContext *pCtxt,
		LPCTSTR pName);
	
	void AdminBoardChange(CHttpServerContext *pCtxt,
						  LPCTSTR pBoardName,
						  LPCTSTR pBoardShortName,
						  LPCTSTR pBoardShortDesc,
						  LPCTSTR pBoardPicture,
						  int maxPostAge,
						  int maxPostCount,
						  LPCTSTR pBoardDesc,
						  int boradPostable,
						  int boardAvailable);

	void PassRecognizer(CHttpServerContext *pCtxt,									  									  								      
									  char *userid, int code);	
	void ChangeSecretPassword(CHttpServerContext *pCtxt,									  									  								      
									  char *pass);
	int ChangePasswordCrypted(CHttpServerContext *pCtxt,
						LPTSTR pUserId,
						LPTSTR pPass,
						LPTSTR pNewPass,
						LPTSTR pNewPass2);


	void RegisterLinkButtons(CHttpServerContext *pCtxt,
								   LPTSTR pUserid,
								   LPTSTR pPassword,
								   int pHomepage,
								   int pMypage,
								   LPTSTR pUrls);

	void OptinLogin(CHttpServerContext *pCtxt,
								   LPTSTR pUserid,
								   LPTSTR pPassword);

	void IIS_Server_status(CHttpServerContext *pCtxt,		
								   int pIIS_Server_status,
								   LPTSTR pTimeDelay,
								   LPTSTR pOperatorMessage);
								   //LPTSTR pServerName);
									/*
								   LPTSTR pEventLogMessage);
								   int EmailService,
								   int EmailMarketing,
								   int EmailEngineering); */

	void IIS_Server_status_broadcast(CHttpServerContext *pCtxt, 
							LPTSTR maintenance , LPTSTR maintenance_select , LPTSTR maintenance_message , 
							LPTSTR python , LPTSTR python_select , LPTSTR python_message , 
							LPTSTR allcgi , LPTSTR allcgi_select , LPTSTR allcgi_message , 
							LPTSTR cgi , 	LPTSTR cgi_select , LPTSTR cgi_message , 
							LPTSTR cgi1 , 	LPTSTR cgi1_select , LPTSTR cgi1_message , 
							LPTSTR cgi2 , 	LPTSTR cgi2_select , LPTSTR cgi2_message , 
							LPTSTR cgi3 ,	LPTSTR cgi3_select ,LPTSTR cgi3_message ,
							LPTSTR cgi4 ,	LPTSTR cgi4_select ,LPTSTR cgi4_message ,
							LPTSTR cgi5 ,	LPTSTR cgi5_select ,LPTSTR cgi5_message ,
							LPTSTR cgi6 ,	LPTSTR cgi6_select ,LPTSTR cgi6_message ,
							LPTSTR cgi7 ,	LPTSTR cgi7_select ,LPTSTR cgi7_message ,
							LPTSTR cgi8 ,	LPTSTR cgi8_select ,LPTSTR cgi8_message ,
							LPTSTR cgi9 ,	LPTSTR cgi9_select ,LPTSTR cgi9_message ,
							LPTSTR cgi10 ,	LPTSTR cgi10_select ,LPTSTR cgi10_message ,
							LPTSTR members, LPTSTR members_select, 	LPTSTR members_message, 
							LPTSTR listings, LPTSTR listings_select, LPTSTR listings_message, 
							LPTSTR search ,	LPTSTR search_select ,LPTSTR search_message ,
							LPTSTR pages ,	LPTSTR pages_select ,LPTSTR pages_message ,
							LPTSTR cobrand, LPTSTR cobrand_select, LPTSTR cobrand_message, 
							LPTSTR sitesearch, LPTSTR sitesearch_select, LPTSTR sitesearch_message,
							LPTSTR future1,	LPTSTR future1_select,LPTSTR future1_message,
							LPTSTR future2,	LPTSTR future2_select,LPTSTR future2_message,
							LPTSTR future3,	LPTSTR future3_select,LPTSTR future3_message,
							LPTSTR future4,	LPTSTR future4_select,LPTSTR future4_message,
							LPTSTR future5,	LPTSTR future5_select,LPTSTR future5_message,
							LPTSTR future6,	LPTSTR future6_select,LPTSTR future6_message,
							LPTSTR future7,	LPTSTR future7_select,LPTSTR future7_message,
							LPTSTR future8, LPTSTR future8_select,LPTSTR future8_message,
							LPTSTR future9, LPTSTR future9_select,LPTSTR future9_message,
							LPTSTR future10,LPTSTR future10_select,LPTSTR future10_message,
						    LPTSTR pUserid,  LPTSTR pPassword
							);

	void CEBayISAPIExtension::send_server_msg(CHttpServerContext *pCtxt,
										  clseBayApp	*pApp,
										  char *server_name, 
										  char *delay_time, 
										  char *outage_msg, 
										  char *pool, 
										  char *machine);


	void RemoveUserIdCookie(CHttpServerContext *pCtxt);
	
	// kaz: 04/18/99 Renamed from PageOptinSave
	void OptinConfirm(CHttpServerContext *pCtxt,
						   LPTSTR pUserid,
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


	void RegistrationAcceptAgreement(CHttpServerContext *pCtxt,
									int notifySelected,
									LPSTR pAgree,
									LPSTR pDontAgree,
									int countryId);

	void CCRegistrationAcceptAgreement(CHttpServerContext *pCtxt,
									int notifySelected,
									LPSTR pAgree,
									LPSTR pDontAgree);

	void UserAgreementAccept(CHttpServerContext *pCtxt,
									LPSTR userid,
									LPSTR password,
									int notifySelected,
									LPSTR pAgree,
									LPSTR pDontAgree);

	//inna
	int AdminRebalanceUserAccount(CHttpServerContext* pCtxt,
	    LPTSTR pUserId);
	//inna
	int AdminRemoveItem(CHttpServerContext* pCtxt,
	    LPTSTR pItemId);


	//Gurinder
	int AdminReInstateItem(CHttpServerContext* pCtxt, LPTSTR pItemNo, LPTSTR pUserId, LPTSTR pPassword);
	int AdminReInstateItemLogin(CHttpServerContext* pCtxt, LPTSTR pItemNo);
	

	//inna - wacko items admin page
    int WackoFlagChangeConfirm(CHttpServerContext* pCtxt,
						LPTSTR pItemNo,
						int wackoFlag);

	int WackoFlagChange(CHttpServerContext* pCtxt,
						LPTSTR pItemNo,
						int wackoFlag);

	void AdultLoginShow(CHttpServerContext *pCtxt, int whichText);

	void AdultLogin(CHttpServerContext *pCtxt,
					 LPSTR userid,
					 LPSTR password);
	
	// kaz: 4/7/99: Support for Police Badge T&C
	void PoliceBadgeLoginForSelling(CHttpServerContext *pCtxt,
					  LPSTR userid,
					  LPSTR password,
					  LPSTR pAccept,
					  LPSTR pDecline);

	// Gallery admin
	int AdminGalleryItemView(CHttpServerContext* pCtxt,
												int pItemNo);
	int AdminGalleryItemDelete(CHttpServerContext* pCtxt,
												int pItemNo);
	int AdminGalleryItemDeleteConfirm(CHttpServerContext* pCtxt,
												int pItemNo);

	// Top Seller
	void ShowTopSellerStatus(CHttpServerContext *pCtxt,
		LPSTR userid);
	void SetTopSellerLevelConfirmation(CHttpServerContext *pCtxt,
		 LPTSTR pUserId,
		 int level);
	void SetTopSellerLevel(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		int level);
	void SetMultipleTopSellers(CHttpServerContext *pCtxt,
		LPTSTR text,
		int level);
	void ShowTopSellers(CHttpServerContext *pCtxt,
		int level);

	void PowerSellerRegisterShow(CHttpServerContext *pCtxt,
								LPSTR pUserId, LPSTR pPass);

	void PowerSellerRegister(CHttpServerContext *pCtxt,
								LPSTR pUserId, 
								LPSTR pPass, 
								LPSTR pAgree,
								LPSTR pDecline);

	// About Me

    int ViewUserPage(CHttpServerContext *pCtxt,
        LPSTR userid,
        int   page);

    void CategorizeUserPage(CHttpServerContext *pCtxt,
        LPSTR pUserId,
        LPSTR pPassword,
        LPSTR pTitle,
        int   remove,
        int   category,
        int   page);

	void UserPageLogin(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword);

	void UserPageAcceptAgreement(CHttpServerContext *pCtxt,
		LPSTR userid,
		LPSTR password,
		int   notifySelected,
		LPSTR pAgree,
		LPSTR pDontAgree);

	void UserPageGoToHTMLPreview(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pHTML);

	void UserPageHandleHTMLPreviewOptions(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pAction1,	// petra
		LPSTR pAction2,	// petra
		LPSTR pAction3, // petra
		LPSTR pHTML);

	void UserPageHandleStyleOptions(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR action1,	// petra
		LPSTR action2,	// petra
		LPSTR action3,	// petra
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgpattern);

	void UserPageHandleTemplateOptions(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pActionButton1,	// petra
		LPSTR pActionButton2,	// petra
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgpattern);

	void UserPageHandleTemplatePreviewOptions(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pActionButton1,	// petra
		LPSTR pActionButton2,	// petra
		LPSTR pActionButton3,	// petra
		LPSTR pActionButton4,	// petra
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgPattern);

	void UserPageShowConfirmHTMLEditingChoices(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pHTML,
		int   which);

	void UserPageConfirmHTMLEditingChoice(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pHTML,
		int   which,
		LPSTR pActionButton1,	// petra
		LPSTR pActionButton2);	// petra

	void UserPageShowConfirmTemplateEditingChoices(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		int   which,
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgPattern);

		void UserPageConfirmTemplateEditingChoice(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pActionButton1,	// petra
		LPSTR pActionButton2,	// petra
		int   which,
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgPattern);

	void UserPageGoToTemplatePreview(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
	    int   templateLayout,
	    LPSTR pPageTitle,
		LPSTR pTextAreaTitle1,
		LPSTR pTextArea1,
		LPSTR pTextAreaTitle2,
		LPSTR pTextArea2,
		LPSTR pPictureCaption,
		LPSTR pPictureURL,
		LPSTR pShowUserIdEmail,
		int   feedbackNumComments,
		int   itemlistNumItems,
		LPSTR pItemlistCaption,
		LPSTR pFavoritesDescription1,
		LPSTR pFavoritesName1,
		LPSTR pFavoritesLink1,
		LPSTR pFavoritesDescription2,
		LPSTR pFavoritesName2,
		LPSTR pFavoritesLink2,
		LPSTR pFavoritesDescription3,
		LPSTR pFavoritesName3,
		LPSTR pFavoritesLink3,
		int   item1CaptionChoice,
		int   item1,
		int   item2CaptionChoice,
		int   item2,
		int   item3CaptionChoice,
		int   item3,
		LPSTR pPageCount,
		LPSTR pDateTime,
		int   bgPattern);


	void ViewGiftAlert(CHttpServerContext *pCtxt,
						  LPSTR pItemNo,
						  LPSTR pUserId);

	void RequestGiftAlert(CHttpServerContext *pCtxt,
						  LPSTR pItemNo,
						  LPSTR pUserId);

	void SendGiftAlert(CHttpServerContext *pCtxt,
					   LPSTR pUserId,
					   LPSTR pPass,
					   LPSTR pFromName,
					   LPSTR pItemNo,
					   LPSTR pToName,
					   LPSTR pDestEmail,
					   LPSTR pMessage,
					   int occasion,
					   LPSTR pOpenMonth,
					   LPSTR pOpenDay,
					   LPSTR pOpenYear);

	void ViewGiftCard(CHttpServerContext *pCtxt,
					  LPSTR pFromUserId,
					  LPSTR pFromName,
					  LPSTR pToName,
					  LPSTR pItemNo,
					  int occasion,
					  LPSTR pOpenDate);

	void ViewGiftCard2(CHttpServerContext *pCtxt,
					   LPSTR pFromUserId,
					   LPSTR pFromName,
					   LPSTR pToName,
					   LPSTR pItemNo,
					   LPSTR pOpenDate,
					   int occasion);

	void ViewGiftItem(CHttpServerContext *pCtxt,
					  LPSTR pFromUserId,
					  LPSTR pFromName,
					  LPSTR pItemNo,
					  LPSTR pOpenDate,
					  int occasion);



	//
	// Notes
	//
	void AdminAddNoteAboutUserShow(CHttpServerContext *pCtxt,
								   LPTSTR pAboutUser);

	void AdminAddNoteAboutUser(CHttpServerContext *pCtxt,
							   LPTSTR pUserId,
							   LPTSTR pPass,
							   LPTSTR pAboutUser,
							   LPTSTR pSubject,
							   int keepdays,
							   LPTSTR pText);

	void AdminAddNoteAboutItemShow(CHttpServerContext *pCtxt,
								   LPTSTR pAboutItem);

	void AdminAddNoteAboutItem(CHttpServerContext *pCtxt,
							   LPTSTR pUserId,
							   LPTSTR pPass,
							   LPTSTR pAboutItem,
							   LPTSTR pSubject,
							   int keepdays,
							   LPTSTR pText);


	void AdminShowNoteShow(CHttpServerContext *pCtxt,
						   LPTSTR pUserId,
						   LPTSTR pPass,
						   LPTSTR pAboutFilter,
						   int typeFilter);

	void AdminShowNote(CHttpServerContext *pCtxt,
					   LPTSTR pUserId,
					   LPTSTR pPass,
					   LPTSTR pAboutFilter,
					   int typeFilter);


	int EbayRedirect(CHttpServerContext *pCtxt, const char* pURL) const;

	// Routines for testing the location-related routines
	int LocationsCompareZipToAC(CHttpServerContext *pCtxt, LPSTR zip, int ac);
	int LocationsCompareZipToState(CHttpServerContext *pCtxt, LPSTR zip, LPSTR state);
	int LocationsCompareStateToAC(CHttpServerContext *pCtxt, LPSTR state, int ac);
	int LocationsCompareZipToCity(CHttpServerContext *pCtxt, LPSTR zip, LPSTR city);
	int LocationsCompareCityToAC(CHttpServerContext *pCtxt, LPSTR city, int ac);

	int LocationsIsValidZip(CHttpServerContext *pCtxt, LPSTR zip);
	int LocationsIsValidAC(CHttpServerContext *pCtxt, int ac);
	int LocationsIsValidCity(CHttpServerContext *pCtxt, LPSTR city);

	int LocationsDistanceZipAC(CHttpServerContext *pCtxt, LPSTR zip, int ac);
	int LocationsDistanceZipZip(CHttpServerContext *pCtxt, LPSTR zip1, LPSTR zip2);
	int LocationsDistanceACAC(CHttpServerContext *pCtxt, int ac1, int ac2);

	int DisplayGalleryImagePage(CHttpServerContext *pCtxt,
	 							  int   item);

	int EnterNewGalleryImage(CHttpServerContext *pCtxt,
							 char *pUserId,
							 char *pPassword,
							 int   item);

	int ConfirmNewGalleryImage(CHttpServerContext *pCtxt,
						       char *pUserId,
	  				           char *pPassword,
						       int   item,
						       char *pURL);

	int FixGalleryImage(CHttpServerContext *pCtxt,
						char *pUserId,
	  				    char *pPassword,
						int   item,
						char *pURL);

	//inna, sam: iEscrow stuff
	void IEscrowLogin(CHttpServerContext* pCtxt,LPSTR pItemNo, 
					  LPSTR ptype, int bidderno);
	void IEscrowShowData(CHttpServerContext* pCtxt,
						LPTSTR pUserId,
						LPTSTR pPass,
						LPSTR  pItemNo,
						LPTSTR ptype,
						int	   bidderno);
	void IEscrowSendData(CHttpServerContext* pCtxt,
						LPTSTR pPartyOne,
						LPTSTR pItemNo,
						LPTSTR ptype,
						int	   Qty,
						int	   bidderno,
						LPTSTR pPartyTwo);

	void SendQueryEmail(CHttpServerContext *pCtxt,
					   LPSTR pUserId,
					   LPSTR pPass,
					   LPSTR pSubject,
					   LPSTR pMessage,
					   int   MailDestination);

	void SendQueryEmailShow(CHttpServerContext *pCtxt,
					   LPSTR pSubject);

	// Report questionable item to support
	void ReportQuestionableItem(CHttpServerContext *pCtxt,
					   LPSTR pUserId,
					   LPSTR pPass,
					   LPSTR pItemType,
					   int   itemID,
					   LPSTR pMessage);

	void ReportQuestionableItemShow(CHttpServerContext *pCtxt,
					   int itemID);

	//
	// This little thing accepts a userid and password, and returns
	// a simple "Y" or "N"
	//
	void AsparagusBananaSandwich(CHttpServerContext *pCtxt,
								 char *pUserId,
	  							 char *pPassword);

	// RESET FLAGS FOR THE USER AGREEMENT. ONLY FOR TESTING.
	// STRIP OUT IF DESIRED JUST BEFORE E105_prod ROLLOUT.
	void ResetFlag(CHttpServerContext *pCtxt,
				   LPSTR userid);

	// deadbeats
	void ViewDeadbeatUser(CHttpServerContext *pCtxt,
						  LPSTR deadbeatuserid);

	void ViewDeadbeatUsers(CHttpServerContext *pCtxt);

	void DeleteDeadbeatItem(CHttpServerContext *pCtxt,
							LPSTR selleruserid,
							LPSTR bidderuserid,
							int itemno,
							int confirm);

	// for mail tweaking
/*	void TurnOnBidNoticesChinese(CHttpServerContext *pCtxt);
	void TurnOffBidNoticesChinese(CHttpServerContext *pCtxt);
	void TurnOnBidNoticesDutch(CHttpServerContext *pCtxt);
	void TurnOffBidNoticesDutch(CHttpServerContext *pCtxt);
	void TurnOnOutBidNoticesChinese(CHttpServerContext *pCtxt);
	void TurnOffOutBidNoticesChinese(CHttpServerContext *pCtxt);
*/	void InstallNewMailMachineList(CHttpServerContext *pCtxt,
										    LPSTR machines,
											int poolType);

	void ToggleMailMachineBidStatus(CHttpServerContext *pCtxt,
										    int bidType,
											int state);
	void ShowMailMachineStatus(CHttpServerContext *pCtxt);

	// Legal Buddies
	void AdminAddScreeningCriteria(CHttpServerContext* pCtxt,
									CategoryId categoryid);

	void AdminAddScreeningCriteriaShow(CHttpServerContext* pCtxt,
										CategoryId categoryid,
										FilterId filterid,
										MessageId messageid,
										int action);

	void AdminViewScreeningCriteria(CHttpServerContext* pCtxt);

	void AdminViewScreeningCriteriaShow(CHttpServerContext* pCtxt,
										CategoryId categoryid);

	void AdminAddFilter(CHttpServerContext* pCtxt,
						int action,
						FilterId filterid);

	void AdminModifyFilter(CHttpServerContext* pCtxt,
							int action);

	void AdminAddFilterShow(CHttpServerContext* pCtxt,
								int action,
								FilterId filterid,
								LPSTR pName,
								LPSTR pExpression,
								ActionType actiontype,
								NotifyType notifytype,
								MessageId blockedmessage,
								MessageId flaggedmessage,
								MessageId filteremailtext,
								MessageId buddyemailtext,
								LPSTR pFilterEmailAddress,
								LPSTR pBuddyEmailAddress);

	void AdminAddMessage(CHttpServerContext* pCtxt,
							int action,
							MessageId messageid);

	void AdminModifyMessage(CHttpServerContext* pCtxt,
							int action);

	void AdminAddMessageShow(CHttpServerContext* pCtxt,
								int action,
								MessageId messageid,
								LPSTR pName,
								LPSTR pMessage,
								MessageType messagetype);
	void AdminViewBlockedItem(CHttpServerContext* pCtxt,
								LPSTR pItemNo,
								LPSTR pItemRow,
								int     time,
								LPSTR tc);
	
	void AdminReinstateAuctionShow(CHttpServerContext *pCtxt,
									int action,
									LPTSTR pAuction,
									LPTSTR pPass,
									LPTSTR pItemNo,
									int type,
									LPTSTR pText);

	void AdminReinstateAuctionConfirm(CHttpServerContext *pCtxt,
										int action,
										LPTSTR pAuction,
										LPTSTR pPass,
										LPTSTR pItemNo,
										int type,
										LPTSTR pText);

	void AdminReinstateAuction(CHttpServerContext *pCtxt,
								int action,
								LPTSTR pAuction,
								LPTSTR pPass,
								LPTSTR pItemNo,
								int type,
								LPTSTR pText,
								LPTSTR pEmailSubject,
								LPTSTR pEmailText);

	void AdminUnflagUserShow(CHttpServerContext *pCtxt,
							 LPTSTR pUser,
							 LPTSTR pPass,
							 LPTSTR pTarget,
							 int type,
							 LPTSTR pText);

	void AdminUnflagUserConfirm(CHttpServerContext *pCtxt,
							    LPTSTR pUser,
							    LPTSTR pPass,
							    LPTSTR pTarget,
							    int type,
							    LPTSTR pText);

	void AdminUnflagUser(CHttpServerContext *pCtxt,
						 LPTSTR pUser,
						 LPTSTR pPass,
						 LPTSTR pTarget,
						 int type,
						 LPTSTR pText,
						 LPTSTR pEmailSubject,
						 LPTSTR pEmailText);

	// PERSONAL SHOPPER
	void PersonalShopperViewSearches(CHttpServerContext *pCtxt,
									 LPTSTR pUserId,
									 LPTSTR pPass,
									 int	AcceptCookie,
									 LPTSTR pAgree);

	void PersonalShopperAddSearch(CHttpServerContext *pCtxt,
						 LPTSTR pUserId,
						 LPTSTR pPass,
						 int	AcceptCookie,
						 LPTSTR pQuery,
						 LPTSTR	pSearchDesc,
						 LPTSTR pMinPrice,
						 LPTSTR pMaxPrice,
						 LPTSTR	EmailFrequency,
						 LPTSTR	EmailDuration,
						 LPTSTR	pRegId,
						 LPTSTR pAgree);

	void PersonalShopperSaveSearch(CHttpServerContext *pCtxt,
						 LPTSTR pUserId,
						 LPTSTR pPass,
						 int	AcceptCookie,
						 LPTSTR pQuery,
						 LPTSTR	pSearchDesc,
						 LPTSTR pMinPrice,
						 LPTSTR pMAxPrice,
						 LPTSTR	EmailFrequency,
						 LPTSTR	EmailDuration,
						 LPTSTR	pRegId);

	void PersonalShopperDeleteSearchView(CHttpServerContext *pCtxt,
						 LPTSTR pUserId,
						 LPTSTR pPass,
						 int	AcceptCookie,
						 LPTSTR pQuery,
						 LPTSTR	pSearchDesc,
						 LPTSTR pMinPrice,
						 LPTSTR pMAxPrice,
						 LPTSTR	EmailFrequency,
						 LPTSTR	EmailDuration,
						 LPTSTR	pRegId);

	void PersonalShopperDeleteSearch(CHttpServerContext *pCtxt,
						 LPTSTR pUserId,
						 LPTSTR pPass,
						 int	AcceptCookie,
						 LPTSTR	pRegId);

 	// Admin Special Tool to pick out approved gallery items, or black listed, or
	// staff picks.
	void AdminSpecialItemsTool(CHttpServerContext* pCtxt);
	void AdminSpecialItemAdd(CHttpServerContext* pCtxt, 
												  LPCTSTR pItemNo, int kind);
	void AdminSpecialItemDelete(CHttpServerContext* pCtxt,
													 LPCTSTR pItemNo);

	void AdminSpecialItemFlush(CHttpServerContext* pCtxt);

	void AdminAddCobrandAdShow(CHttpServerContext *pCtxt);

	void AdminAddCobrandAdConfirm(CHttpServerContext *pCtxt,
								  LPTSTR pName,
								  LPTSTR pText);

	void AdminAddCobrandAd(CHttpServerContext *pCtxt,
						   LPTSTR pName,
						   LPTSTR pText);

	void AdminSelectCobrandAdSiteShow(CHttpServerContext *pCtxt);

	void AdminSelectCobrandAdPartnerAndPageShow(CHttpServerContext *pCtxt,
												int adId,
												int siteId);

	void AdminAddCobrandAdToSitePageConfirm(CHttpServerContext *pCtxt,
											int adId,
											int siteId,
											int partnerId,
											int pageType1,		// primary page type
											int pageType2);	// secondary page type

	void AdminAddCobrandAdToSitePage(CHttpServerContext *pCtxt,
									 int adId,
									 int site,
									 int partner,
									 int pageType1,	// primary page type
									 int pageType2,	// secondary page type
									 int contextSensitiveValue);

	void AOLRegisterShow( CHttpServerContext *pCtxt,
							LPSTR	pAOLName);

	// nsacco 07/07/99 added siteId and coPartnerId
	int AOLRegisterPreview(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                 /* Q16 */
								  LPTSTR pNewPass,
								  LPTSTR pNewPass2,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify);

	// nsacco 07/07/99 added siteId and coPartnerId
	int AOLRegisterUserID(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                 /* Q16 */
								  LPTSTR pNewPass,
								  LPTSTR pNewPass2,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify);

	// nsacco 07/07/99 added siteId and coPartnerId
	int AOLRegisterUserAgreement(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                 /* Q16 */
								  LPTSTR pNewPass,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify);

	// nsacco 07/07/99 added siteId and coPartnerId
	int AOLRegisterUserAcceptAgreement(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pEmail,
								  LPTSTR pName,
								  LPTSTR pCompany,
								  LPTSTR pAddress,
								  LPTSTR pCity,
								  LPTSTR pState,
								  LPTSTR pZip,
								  LPTSTR pCountry,
								  int countryId,
								  LPTSTR pDayPhone1,
								  LPTSTR pDayPhone2,
								  LPTSTR pDayPhone3,
								  LPTSTR pDayPhone4,
								  LPTSTR pNightPhone1,
								  LPTSTR pNightPhone2,
								  LPTSTR pNightPhone3,
								  LPTSTR pNightPhone4,
								  LPTSTR pFaxPhone1,
								  LPTSTR pFaxPhone2,
								  LPTSTR pFaxPhone3,
								  LPTSTR pFaxPhone4,
								  LPTSTR pGender,
								  int referral,              /* Q1  */
								  LPTSTR pTradeshow_source1, /* Q17 */
								  LPTSTR pTradeshow_source2, /* Q18 */
								  LPTSTR pTradeshow_source3, /* Q19 */
								  LPTSTR pFriend_email,      /* Q20 */
								  int purpose,               /* Q7  */
								  int interested_in,         /* Q14 */
								  int age,                   /* Q3  */
								  int education,             /* Q4  */
								  int income,                /* Q5  */
								  int survey,                 /* Q16 */
								  LPTSTR pNewPass,
								  LPTSTR pButtonPressed1,	// petra
								  LPTSTR pButtonPressed2,	// petra
								  int nNotify,
								  int nAgreementQ1,
								  int nAgreementQ2,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify);

	int AOLRegisterComplete(CHttpServerContext *pCtxt,
								  LPTSTR pUserId);

	int AOLRegisterConfirm(CHttpServerContext *pCtxt,
									int nConfirmation,
									LPTSTR pUserId,
									int nVerify);

	DECLARE_PARSE_MAP()
        
private:
	clseBayApp	*CreateeBayApp();

	// Returns false if found a monster bug
	//  pFunctionName = e.g. "My eBay"
	//  pTarget is the string you want to look for in the query_string or data block, e.g. pUserId
	//  BlockIt tells the function whether to log the bug only or block it.
	//		BlockIt = true ->  block and return false if a bug occurs
	//		BlockIt - false -> log the bug only and return true
	bool MonsterBugSanityCheck(CHttpServerContext *pCtxt, 
								clseBayApp *pApp, 
								const char *pFunctionName, 
								const char *pTarget,
								bool BlockIt);

				//{{AFX_MSG(CEBayISAPIExtension)
				//}}AFX_MSG
};
        
        
        //{{AFX_INSERT_LOCATION}}
        // Microsoft Developer Studio will insert additional declarations immediately before the previous line.
        
#endif // !defined(AFX_EBAYISAPI_H__62697925_1DC8_11D1_B6FA_00A024D30D0B__INCLUDED)
