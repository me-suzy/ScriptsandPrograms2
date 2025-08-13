/*	$Id: eBayISAPI.cpp,v 1.21.2.13.34.5 1999/08/06 20:31:55 nsacco Exp $	*/
// EBAYISAPI.CPP - Implementation file for your Internet Server
//    eBayISAPI Extension
// Modifications
//	06/21/99	nsacco	- modified ShowCobrandPartners to take a site id and
//						  CreateCobrandPartner to take a site id and dir name
//	07/02/99	nsacco	- added siteId and coPartnerId to Register() and AOL functions
//	07/27/99	nsacco	- Added new params to VerifyNewItem, AddNewItem, UpdateItemInfo,
//							VerifyUpdateItem
//	07/30/99	nsacco	- Added redirect for NewItem and NewItemQuick which were replaced
//							by ListItemForSale
//	08/05/99	nsacco	- Fixed html in Outage Message by adding </body></html>

#include "ebihdr.h"
#include "eBayExceptions.h"
#include "nmtcp.h"

// Included to disable memory dialog boxes.
#include <crtdbg.h>

// #include "pure.h"

//#undef ISAPITRACE
//#define ISAPITRACE bogus
//static int bogus(const char*, ...) { return 1;}

static const char *InternalErrorMessage =
	"\n"
	"</pre>"
	"<h2>"
	"An Internal Error has occurred"
	"</h2>"
	"<br>"
	"Please try again. If this message persists "
	"please report this problem to <a href=\"http://cgi3.ebay.com/aw-cgi/eBayISAPI.dll?SendQueryEmailShow\">Customer Support</a>.";


///////////////////////////////////////////////////////////////////////
// The one and only CWinApp object
// NOTE: You may remove this object if you alter your project to no
// longer use MFC in a DLL.

CWinApp theApp;

///////////////////////////////////////////////////////////////////////
// command-parsing map

BEGIN_PARSE_MAP(CEBayISAPIExtension, CHttpServer)
	// 
	// Bids
	//
//  ON_PARSE_COMMAND(DebugTest, CEBayISAPIExtension, ITS_EMPTY)	

	ON_PARSE_COMMAND(ValidateInternals, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(MakeBid, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item maxbid quant=1 accept=default decline=default notify=0")

	ON_PARSE_COMMAND(AcceptBid, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item key userid pass maxbid quant accept=default decline=default notify=0")

	ON_PARSE_COMMAND(RetractBid, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass item info")

	ON_PARSE_COMMAND(CancelBid, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("selleruserid pass item userid info")

	ON_PARSE_COMMAND(ViewBids, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(ViewBidderWithEmails, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0 userid=default pass=default acceptcookie=0")

	ON_PARSE_COMMAND(ViewBidsDutchHighBidder, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(GetBidderEmails, CEBayISAPIExtension,
					 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item pagetype")

	ON_PARSE_COMMAND(ViewBidDutchHighBidderEmails, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0 userid=default pass=default acceptcookie=0")


	//
	// items
	//
	ON_PARSE_COMMAND(ViewItem, CEBayISAPIExtension, ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item r=0 t=0 tc=0")

	ON_PARSE_COMMAND(VerifyAddToItem, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass itemno desc")

	ON_PARSE_COMMAND(AddToItem, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass itemno desc")

	ON_PARSE_COMMAND(VerifyStop, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass item")

	ON_PARSE_COMMAND(Stop, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item userid pass")

	ON_PARSE_COMMAND(Featured, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(MakeFeatured, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass itemno typeSuper=off typeFeature=off")

	ON_PARSE_COMMAND(ChangeCategoryShow, CEBayISAPIExtension, ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0 oldstyle=0")

	ON_PARSE_COMMAND(ChangeCategory, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4
 					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR) // added dummy params catmenu0..3
	ON_PARSE_COMMAND_PARAMS("userid pass item newcategory"
							" catmenu_0=null"
							" catmenu_1=null"
							" catmenu_2=null"
							" catmenu_3=null")

	ON_PARSE_COMMAND(NewItem, CEBayISAPIExtension, ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item=0 category=0")

	ON_PARSE_COMMAND(NewItemQuick, CEBayISAPIExtension, ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item=0 category=0")

	ON_PARSE_COMMAND(ListItemForSale, CEBayISAPIExtension, ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0 category=0 oldstyle=0")
	
	ON_PARSE_COMMAND(BetterSeller, CEBayISAPIExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND(VerifyNewItem, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR 
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_I4	  ITS_I4   ITS_PSTR
					 ITS_I4  ITS_PSTR  ITS_I4  ITS_I4 ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)			// added dummy params catmenu_0..3

	// nsacco 07/27/99 new shipping params plus siteid and language
	ON_PARSE_COMMAND_PARAMS("userid=default			\
							 pass=default			\
							 title=default			\
							 location=default		\
							 reserve=default		\
							 startprice=default		\
							 quant=1				\
							 duration=default		\
							 bold=off				\
							 featured=off			\
							 superfeatured=off		\
							 private=off			\
							 desc=default			\
							 picurl=default			\
							 category1=default		\
							 category2=default		\
							 category3=default		\
							 category4=default		\
							 category5=default		\
							 category6=default		\
							 category7=default		\
							 category8=default		\
							 category9=default		\
							 category10=default		\
							 category11=default		\
							 category12=default		\
							 olditem=default		\
							 oldkey=default 		\
							 accept=default			\
							 decline=default		\
							 notify=1				\
							 moneyOrderAccepted=off \
							 personalChecksAccepted=off \
					         visaMasterCardAccepted=off \
					         discoverAccepted=off \
					         amExAccepted=off        \
							 otherAccepted=off       \
							 onlineEscrow=off		\
							 paymentCOD=off		\
					         paymentSeeDescription=off \
							 sellerPaysShipping=off  \
							 buyerPaysShippingFixed=off \
						     buyerPaysShippingActual=off \
							 shippingSeeDescription=off \
							 shippingInternationally=siteonly\
							 northamerica=off\
							 europe=off\
							 oceania=off\
							 asia=off\
							 southamerica=off\
							 africa=off\
							 siteid=0\
							 language=0\
							 giftIcon=default\
							 gallery=0\
							 galleryurl=default\
							 countryid=1\
							 currencyid=1\
							 zip=default\
							 catmenu_0=null\
							 catmenu_1=null\
							 catmenu_2=null\
							 catmenu_3=null\
							 ")

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND(AddNewItem, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_I4   ITS_I4
					 ITS_PSTR ITS_I4 
					 ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR)

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND_PARAMS("userid=default			\
							 pass=default			\
							 itemno=default			\
							 title=default			\
							 reserve=default		\
							 startprice=default		\
							 quant=default			\
							 duration=default		\
							 location=default		\
							 bold=default			\
							 featured=default		\
							 superfeatured=default	\
							 private=default		\
							 desc=default			\
							 picurl=default			\
							 category=default		\
							 key=default			\
							 olditem=default		\
							 oldkey=default			\
							 moneyOrderAccepted=off \
							 personalChecksAccepted=off \
					         visaMasterCardAccepted=off \
					         discoverAccepted=off \
					         amExAccepted=off        \
							 otherAccepted=off       \
							 onlineEscrow=off		\
							 paymentCOD=off		\
					         paymentSeeDescription=off \
							 sellerPaysShipping=off  \
							 buyerPaysShippingFixed=off \
						     buyerPaysShippingActual=off \
							 shippingSeeDescription=off\
							 shippingInternationally=siteonly\
							 northamerica=off\
							 europe=off\
							 oceania=off\
							 asia=off\
							 southamerica=off\
							 africa=off\
							 siteid=0\
							 language=0\
							 giftIcon=off\
							 gallery=0\
							 galleryurl=default\
							 countryid=1\
							 currencyid=1\
							 zip=default"
							 )

	ON_PARSE_COMMAND(RecomputeDutchBids, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(RecomputeChineseBids, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(UserItemVerification, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item=default")



	//
	// Users
	//
	ON_PARSE_COMMAND(ViewListedItems, CEBayISAPIExtension,
   					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
   	ON_PARSE_COMMAND_PARAMS("userid=default completed=0 sort=0 since=-1 include=0 page=1 rows=0")

	ON_PARSE_COMMAND(ViewListedItemsWithEmails, CEBayISAPIExtension,
   					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
   	ON_PARSE_COMMAND_PARAMS("userid=default pass=default requested=default completed=0 \
		sort=0 since=-1 acceptcookie=0 page=1 rows=0")

	ON_PARSE_COMMAND(ViewListedItemsLinkButtons, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default completed=0 sort=0 since=-1 include=0 page=1 rows=0")

	ON_PARSE_COMMAND(ViewBidItems, CEBayISAPIExtension,
   					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
   	ON_PARSE_COMMAND_PARAMS("userid=default completed=0 sort=0 all=0 page=1 rows=0")

	ON_PARSE_COMMAND(ViewAllItems, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default completed=0 sort=0 since=-1")

	ON_PARSE_COMMAND(ViewFeedback, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default page=1 items=25")

	ON_PARSE_COMMAND(PersonalizedFeedbackLogin, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default items=25")

	ON_PARSE_COMMAND(ViewPersonalizedFeedback, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default page=1 items=25")

	ON_PARSE_COMMAND(ViewFeedbackLeft, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default")

	ON_PARSE_COMMAND(LeaveFeedback, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS(
		"userid=default pass=default otheruserid=default itemno=default which=default comment=default confirm=0")

	ON_PARSE_COMMAND(LeaveFeedbackShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("useridto=default useridfrom=default item=0")

	ON_PARSE_COMMAND(RespondFeedbackShow, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("commentor time commentee page=1 items=0")

	ON_PARSE_COMMAND(RespondFeedback, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("commentor time commentee pass response page=1 items=0")

	ON_PARSE_COMMAND(FollowUpFeedbackShow, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("commentor time commentee")

	ON_PARSE_COMMAND(FollowUpFeedback, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("commentor time commentee pass followup")

	ON_PARSE_COMMAND(ChangeFeedbackOptions, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default option=default page=1 items=0")

	ON_PARSE_COMMAND(GetFeedbackScore, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(FeedbackForum, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(ViewBoard, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("name=default limit=default")

	ON_PARSE_COMMAND(ViewEssay, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("name=default")

	ON_PARSE_COMMAND(PastEssay, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(AddToBoard, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("name=default userid=default pass=default info=default limit=default fromessay=0")

	ON_PARSE_COMMAND(RecomputeScore, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")
	//added back 
	ON_PARSE_COMMAND(RegisterShow, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(RegisterByCountry, CEBayISAPIExtension, ITS_I4 ITS_I4)
    ON_PARSE_COMMAND_PARAMS("country=0 UsingSSL=0")

	ON_PARSE_COMMAND(ConfirmByCountry, CEBayISAPIExtension, ITS_I4 ITS_I4)
    ON_PARSE_COMMAND_PARAMS("country=0 withcc=0")

	ON_PARSE_COMMAND(ShowRegistrationForm, CEBayISAPIExtension, ITS_I4 ITS_I4)
    ON_PARSE_COMMAND_PARAMS("cid UsingSSL=0")

	ON_PARSE_COMMAND(ResendConfirmationEmail, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("email=default")

	// nsacco 07/02/99
	ON_PARSE_COMMAND(Register, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR					 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 email=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 zip=default		\
							 country=other	    \
							 countryid=0        \
							 dayphone1=default	\
							 dayphone2=default	\
							 dayphone3=default	\
  							 dayphone4=default	\
							 nightphone1=default	\
							 nightphone2=default	\
							 nightphone3=default	\
							 nightphone4=default	\
							 faxphone1=default	\
							 faxphone2=default	\
							 faxphone3=default	\
							 faxphone4=default	\
							 gender=u		\
							 Q1=1	\
							 Q17=default	\
							 Q18=default	\
							 Q19=default \
							 Q20=default \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1 \
							 withcc=0 \
							 UsingSSL=0 \
							 siteid=0 \
							 copartnerid=0")	

							 // Q1 = how did you first hear?
							 // Q17 = priority code
							 // Q18 = priority code
							 // Q19 = priority code
							 // Q20 = friend email
							 // Q7 = personal or business use
							 // Q14 = most interested in
							 // Q3 = age
							 // Q4 = education
							 // Q5 = income
							 // Q16 = survey


	// nsacco 07/02/99
	ON_PARSE_COMMAND(RegisterPreview, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 email=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 zip=default		\
							 country=other   	\
							 countryid=0        \
							 dayphone1=default	\
							 dayphone2=default	\
							 dayphone3=default	\
  							 dayphone4=default	\
							 nightphone1=default	\
							 nightphone2=default	\
							 nightphone3=default	\
							 nightphone4=default	\
							 faxphone1=default	\
							 faxphone2=default	\
							 faxphone3=default	\
							 faxphone4=default	\
							 gender=u		\
							 Q1=1	\
							 Q17=default	\
							 Q18=default	\
							 Q19=default \
							 Q20=default \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1	\
							 UsingSSL=0 \
							 siteid=0 \
							 copartnerid=0") 	// nsacco 07/02/99	



	ON_PARSE_COMMAND(RegisterConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("email=default		\
							 userid=default		\
							 pass=default		\
							 newpass=default	\
							 newpass2=default   \
							 notify=0           \
							 countryid=0")

 	ON_PARSE_COMMAND(UpdateCC, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default	\
							 ccnumber=default	\
							 month=default	\
							 day=default	\
							 year=default")

	ON_PARSE_COMMAND(UpdateCCConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							ccnumber=default	\
							date=default")

 	ON_PARSE_COMMAND(RegisterCC, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("useforpayment=0		\
							 email=default			\
							 oldpass=default		\
							 userid=default			\
							 newpass=default		\
							 newpassagain=default	\
							 username=default		\
							 streetaddr=default		\
							 cityaddr=default		\
							 stateprovaddr=default	\
							 zipcodeaddr=default	\
							 countryaddr=default	\
							 cc=default				\
							 month=default			\
							 day=default			\
							 year=default			\
							 notify=0")

 	ON_PARSE_COMMAND(ChangeEmail, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(ChangeEmailShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default			\
							 newmail=default")

	ON_PARSE_COMMAND(ChangeEmailConfirm, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(ChangeEmailConfirmShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 newmail=default		\
							 pass=default")

	ON_PARSE_COMMAND(ChangePassword, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 newpass1=default	\
							 newpass2=default")

	ON_PARSE_COMMAND(UserQuery, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 otheruserid=default")
	// 12/16/97 Charles Added
	ON_PARSE_COMMAND(ChangeUserId, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(ChangeUserIdShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("olduserid=default		\
							 pass=default			\
							 newuserid=default")

	ON_PARSE_COMMAND(ChangeRegistrationShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default UsingSSL=0")

	ON_PARSE_COMMAND(ChangeRegistration, CEBayISAPIExtension,
				 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
				 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
				 ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 otherstate=default \
							 zip=default		\
							 country=other  	\
							 countryid=0        \
							 dayphone=default	\
							 nightphone=default	\
							 faxphone=default	\
							 gender=u			\
							 UsingSSL=0") 		

	ON_PARSE_COMMAND(ChangeRegistrationPreview, CEBayISAPIExtension,
				 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
				 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4
				 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 otherstate=default \
							 zip=default		\
							 countryid=0  	    \
							 dayphone=default	\
							 nightphone=default	\
							 faxphone=default	\
							 gender=u			\
							 UsingSSL=0") 

	ON_PARSE_COMMAND(ChangePreferencesShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default oldstyle=0")

	ON_PARSE_COMMAND(ChangePreferences, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4			// interest1..4
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR	// added dummy params catmenu1_0..3
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR	// added dummy params catmenu2_0..3
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR	// added dummy params catmenu3_0..3
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)	// added dummy params catmenu4_0..3
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 interest1=default	\
							 interest2=default	\
							 interest3=default	\
							 interest4=default	\
							 catmenu1_0=null	\
							 catmenu1_1=null	\
							 catmenu1_2=null	\
							 catmenu1_3=null	\
							 catmenu2_0=null	\
							 catmenu2_1=null	\
							 catmenu2_2=null	\
							 catmenu2_3=null	\
							 catmenu3_0=null	\
							 catmenu3_1=null	\
							 catmenu3_2=null	\
							 catmenu3_3=null	\
							 catmenu4_0=null	\
							 catmenu4_1=null	\
							 catmenu4_2=null	\
							 catmenu4_3=null	\
						   ") 	

	ON_PARSE_COMMAND(RequestPassword, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(AdminRequestPassword, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(ViewAccount, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 entire=0			\
							 sinceLastInvoice=0	\
							 daysback=default	\
							 startdate=default	\
							 enddate=default")

	ON_PARSE_COMMAND(MyEbay, CEBayISAPIExtension,
		ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default first=default	\
							 sellersort=9 biddersort=9 dayssince=0	\
							 p1=0 p2=0 p3=0 p4=0 p5=0")


	ON_PARSE_COMMAND(MyEbaySeller, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default sort=9 dayssince=0")

	ON_PARSE_COMMAND(MyEbayBidder, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default sort=9 dayssince=0")

	ON_PARSE_COMMAND(PayCoupon, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default pymtType=default")

	ON_PARSE_COMMAND(RequestRefund, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default")

	ON_PARSE_COMMAND(BetaConfirmationShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default")

	ON_PARSE_COMMAND(BetaConfirmation, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 email=default		\
							 pass=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 zip=default		\
							 country=other  	\
							 dayphone=default	\
							 nightphone=default	\
							 faxphone=default	\
							 gender=u")

	ON_PARSE_COMMAND(BetaConfirmationPreview, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 email=default		\
							 pass=default		\
							 name=default		\
							 company=default	\
							 address=default	\
							 city=default		\
							 state=default		\
							 zip=default		\
							 country=other  	\
							 dayphone=default	\
							 nightphone=default	\
							 faxphone=default	\
							 gender=u")


	ON_PARSE_COMMAND(CreateAccount, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default")

	ON_PARSE_COMMAND(GetUserEmail, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(ReturnUserEmail, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("requested userid=default pass=default acceptcookie=0")

	ON_PARSE_COMMAND(GetUserIdHistory, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(ReturnUserIdHistory, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("requested userid=default pass=default acceptcookie=0")

	ON_PARSE_COMMAND(MultipleEmails, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userids userid=default pass=default acceptcookie=0")

	ON_PARSE_COMMAND(GetMultipleEmails, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(ViewAliasHistory, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid pass")

	ON_PARSE_COMMAND(GetUserByAlias, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("requested requestor pass")


 	ON_PARSE_COMMAND(TimeShow, CEBayISAPIExtension,
 					 ITS_EMPTY)
 
	ON_PARSE_COMMAND(RemoveUserIdCookie, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(GetItemInfo, CEBayISAPIExtension, ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=default userid=default pass=default oldstyle=0")

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND(UpdateItemInfo, CEBayISAPIExtension,
					 ITS_PSTR	//  itemno 
					 ITS_PSTR	//  userid 
					 ITS_PSTR	//  pass 
					 ITS_PSTR	//  title 
					 ITS_PSTR	//  desc 
					 ITS_PSTR	//  picurl 
					 ITS_PSTR	//  category=default 
					 ITS_PSTR	//  moneyOrderAccepted=off 
					 ITS_PSTR	//  personalChecksAccepted=off 
					 ITS_PSTR	//  visaMasterCardAccepted=off 
					 ITS_PSTR	//  discoverAccepted=off 
					 ITS_PSTR	//  amExAccepted=off 
					 ITS_PSTR	//  otherAccepted=off 
					 ITS_PSTR	//  onlineEscrow=off 
					 ITS_PSTR	//  paymentCOD=off 
					 ITS_PSTR	//  paymentSeeDescription=off 
					 ITS_PSTR	//  sellerPaysShipping=off 
					 ITS_PSTR	//  buyerPaysShippingFixed=off 
					 ITS_PSTR	//  buyerPaysShippingActual=off 
					 ITS_PSTR	//  shippingSeeDescription=off 
					 ITS_PSTR	//  shippingInternationally=siteonly 
					 ITS_PSTR	//	northamerica=off
					 ITS_PSTR	//	europe=off
					 ITS_PSTR	//	oceania=off
					 ITS_PSTR	//	asia=off
					 ITS_PSTR	//	southamerica=off
					 ITS_PSTR	//	africa=off
					 ITS_I4		//	siteid=0
					 ITS_I4		//	language=0
					 )

	 // nsacco 07/27/99 added new params
	 ON_PARSE_COMMAND_PARAMS(
					 " itemno "
					 " userid "
					 " pass "
					 " title "
					 " desc "
					 " picurl "
					 " category=default "
					 " moneyOrderAccepted=off "
					 " personalChecksAccepted=off "
					 " visaMasterCardAccepted=off "
					 " discoverAccepted=off "
					 " amExAccepted=off "
					 " otherAccepted=off "
					 " onlineEscrow=off "
					 " paymentCOD=off "
					 " paymentSeeDescription=off "
					 " sellerPaysShipping=off "
					 " buyerPaysShippingFixed=off "
					 " buyerPaysShippingActual=off "
					 " shippingSeeDescription=off "
					 " shippingInternationally=siteonly "
					 " northamerica=off "
					 " europe=off "
					 " oceania=off "
					 " asia=off "
					 " southamerica=off "
					 " africa=off "
					 " siteid=0 "
					 " language=0 "
					 )

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND(VerifyUpdateItem, CEBayISAPIExtension,
											ITS_PSTR   // pUserId,
											ITS_PSTR   // pPass,
											ITS_PSTR   // pItemNo,
											ITS_PSTR   // pTitle,
										//	ITS_PSTR   // pQuantity, 
											ITS_PSTR   // pDesc,
											ITS_PSTR   // pPicUrl,
											ITS_PSTR   // pCategory1,
											ITS_PSTR   // pCategory2,
											ITS_PSTR   // pCategory3,
											ITS_PSTR   // pCategory4,
											ITS_PSTR   // pCategory5,
											ITS_PSTR   // pCategory6,
											ITS_PSTR   // pCategory7,
											ITS_PSTR   // pCategory8,
											ITS_PSTR   // pCategory9,
											ITS_PSTR   // pCategory10,
											ITS_PSTR   // pCategory11,
											ITS_PSTR   // pCategory12,
											ITS_PSTR   // pMoneyOrderAccepted,
											ITS_PSTR   // pPersonalChecksAccepted,
											ITS_PSTR   // pVisaMasterCardAccepted,
											ITS_PSTR   // pDiscoverAccepted,
											ITS_PSTR   // pAmExAccepted,
											ITS_PSTR   // pOtherAccepted,
											ITS_PSTR   // pOnlineEscrowAccepted,
											ITS_PSTR   // pCODAccepted,
											ITS_PSTR   // pPaymentSeeDescription,
											ITS_PSTR   // pSellerPaysShipping,
											ITS_PSTR   // pBuyerPaysShippingFixed,
											ITS_PSTR   // pBuyerPaysShippingActual,
											ITS_PSTR   // pShippingSeeDescription,
											ITS_PSTR   // pShippingInternationally
											ITS_PSTR   // pShipToNorthAmerica
											ITS_PSTR   // pShipToEurope
											ITS_PSTR   // pShipToOceania
											ITS_PSTR   // pShipToAsia
											ITS_PSTR   // pShipToSouthAmerica
											ITS_PSTR   // pShipToAfrica
											ITS_I4	   // siteId
											ITS_I4	   // descLang
											ITS_PSTR   // catmenu_0
											ITS_PSTR   // catmenu_1
											ITS_PSTR   // catmenu_2
											ITS_PSTR   // catmenu_3
											)

	// nsacco 07/27/99 added new params
	ON_PARSE_COMMAND_PARAMS("userid pass item title desc picurl "
							 " category1=default "
							 " category2=default "
							 " category3=default "
							 " category4=default "
							 " category5=default "
							 " category6=default "
							 " category7=default "
							 " category8=default "
							 " category9=default "
							 " category10=default "
							 " category11=default "
							 " category12=default "
							 " moneyOrderAccepted=off "
							 " personalChecksAccepted=off "
					         " visaMasterCardAccepted=off "
					         " discoverAccepted=off "
					         " amExAccepted=off "
							 " otherAccepted=off "
							 " onlineEscrow=off "
							 " paymentCOD=off "
					         " paymentSeeDescription=off "
							 " sellerPaysShipping=off "
							 " buyerPaysShippingFixed=off "
						     " buyerPaysShippingActual=off "
							 " shippingSeeDescription=off "
							 " shippingInternationally=siteonly "
							 " northamerica=off "
							 " europe=off "
							 " oceania=off "
							 " asia=off "
							 " southamerica=off "
							 " africa=off "
							 " siteid=0 "
							 " language=0 "
							 " catmenu_0=null "
							 " catmenu_1=null "
							 " catmenu_2=null "
							 " catmenu_3=null "
							 )


	ON_PARSE_COMMAND(DisplayGalleryImagePage, CEBayISAPIExtension,
	   ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")

	ON_PARSE_COMMAND(EnterNewGalleryImage, CEBayISAPIExtension,
	   ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid password item")

	ON_PARSE_COMMAND(FixGalleryImage, CEBayISAPIExtension,
	   ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid password item url")

	//
	// Admin
	//

	ON_PARSE_COMMAND(AdminAddExchangeRate, CEBayISAPIExtension,
						ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 
						ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("login password month=1 day=1 year=99 \
							 fromcurrency=3 tocurrency=1 newrate=1")

	ON_PARSE_COMMAND(AdminViewBids, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	// Shill Tool functions
	ON_PARSE_COMMAND(AdminShillRelationshipsByItem, CEBayISAPIExtension,
		ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("details=off item=-1 limit=30")

	ON_PARSE_COMMAND(AdminShillRelationshipsByUsers, CEBayISAPIExtension,
		ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("details=off userlist=none limit=30")

	ON_PARSE_COMMAND(AdminShillRelationshipsByFeedback, CEBayISAPIExtension,
		ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("details=off user=default left=off count=20 age=0 limit=30")

	ON_PARSE_COMMAND(AdminShowBiddersSellers, CEBayISAPIExtension,
		ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("bidder")

	ON_PARSE_COMMAND(AdminShowCommonAuctions, CEBayISAPIExtension,
		ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userlist=none")

	ON_PARSE_COMMAND(AdminGetShillCandidates, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(AdminShowBiddersRetractions, CEBayISAPIExtension, ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("id=0 limit=30")

	ON_PARSE_COMMAND(UserSearch, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("string how")

	ON_PARSE_COMMAND(CreditBatch, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("text doit pass")

	ON_PARSE_COMMAND(CreditBatch2, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("text doit pass")

	ON_PARSE_COMMAND(CreditDump, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("username password")

	ON_PARSE_COMMAND(ItemCreditReq, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=defaut itemno=default morecredits=0")

	ON_PARSE_COMMAND(ChineseAuctionCreditReq, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("itemno=default arc=0 waspaid=0 amt=default reason=0")

	ON_PARSE_COMMAND(DutchAuctionCreditReq, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR
					 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR
					 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR
					 ITS_I4 ITS_PSTR ITS_I4 ITS_PSTR ITS_I4)

	ON_PARSE_COMMAND_PARAMS("itemno=default arc=0 \
							 waspaid1=0 amt1=default reason1=0 email1=default \
							 waspaid2=0 amt2=default reason2=0 email2=default \
							 waspaid3=0 amt3=default reason3=0 email3=default \
							 waspaid4=0 amt4=default reason4=0 email4=default \
							 waspaid5=0 amt5=default reason5=0 email5=default \
							 morecredits=0")

	ON_PARSE_COMMAND(AccountBatch, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("text doit pass")

	ON_PARSE_COMMAND(AdminWarnUserShow, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminWarnUserConfirm, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminWarnUser, CEBayISAPIExtension,			\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR								\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default					\
							 emailsubject=default emailtext=default")


	ON_PARSE_COMMAND(AdminSuspendUserShow, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminSuspendUserConfirm, CEBayISAPIExtension,	\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminSuspendUser, CEBayISAPIExtension,			\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR								\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default					\
							 emailsubject=default emailtext=default")

	ON_PARSE_COMMAND(AdminReinstateUserShow, CEBayISAPIExtension,	\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminReinstateUserConfirm, CEBayISAPIExtension,\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminReinstateUser, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR								\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default					\
							 emailsubject=default emailtext=default")

	ON_PARSE_COMMAND(AdminResetReqEmailCount, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid")

	ON_PARSE_COMMAND(AdminResetReqUserCount, CEBayISAPIExtension,
					ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid")

	ON_PARSE_COMMAND(ConfirmUser, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid")

	ON_PARSE_COMMAND(AdminEndAuctionShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR										\
					 ITS_PSTR ITS_I4 ITS_I4									\
					 ITS_I4													\
					 ITS_I4 ITS_I4											\
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default					\
							 item=default suspended=0 creditfees=0			\
							 emailbidders=1									\
							 type=0 buddy=0									\
							 text=default")

	ON_PARSE_COMMAND(AdminEndAuctionConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR								\
					 ITS_I4	ITS_I4 ITS_I4									\
					 ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default item=default		\
							 suspended=0 creditfees=0 emailbidders=0		\
							 type=0 buddy=0 text=default")

	ON_PARSE_COMMAND(AdminEndAuction, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR									\
					 ITS_I4 ITS_I4 ITS_I4										\
					 ITS_I4 ITS_I4 ITS_PSTR										\
					 ITS_PSTR ITS_PSTR											\
					 ITS_PSTR ITS_PSTR											\
					 ITS_PSTR													\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default item=default				\
							 suspended=0 creditfees=0 emailbidders=0				\
							 type=0 buddy=0 text=default							\
							 selleremailsubject=default selleremailtext=default		\
							 bidderemailsubject=default bidderemailtext=default		\
							 buddyemailaddress=default								\
							 buddyemailsubject=default buddyemailtext=default")


	ON_PARSE_COMMAND(AdminEndAllAuctionsShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR										\
					 ITS_PSTR												\
					 ITS_I4 ITS_I4 ITS_I4									\
					 ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default					\
							 targetuser=default								\
							 suspended=0 creditfees=0 emailbidders=1		\
							 type=0	buddy=0									\
							 text=default")

	ON_PARSE_COMMAND(AdminEndAllAuctionsConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR										\
					 ITS_PSTR												\
					 ITS_I4 ITS_I4 ITS_I4									\
					 ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default					\
							 targetuser=default								\
							 suspended=0 creditfees=0 emailbidders=0		\
							 type=0	buddy=0 text=default")

	ON_PARSE_COMMAND(AdminEndAllAuctions, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR											\
					 ITS_PSTR													\
					 ITS_I4 ITS_I4 ITS_I4										\
					 ITS_I4 ITS_I4 ITS_PSTR										\
					 ITS_PSTR ITS_PSTR											\
					 ITS_PSTR ITS_PSTR											\
					 ITS_PSTR													\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default						\
							 targetuser=default									\
							 suspended=0 creditfees=0 emailbidders=0			\
							 type=0 buddy=0 text=default						\
							 selleremailsubject=default selleremailtext=default	\
							 bidderemailsubject=default bidderemailtext=default	\
							 buddyemailaddress=default							\
							 buddyemailsubject=default buddyemailtext=default")


	ON_PARSE_COMMAND(AdminMoveAuctionShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR										\
					 ITS_PSTR ITS_I4 ITS_I4									\
					 ITS_I4													\
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default					\
							 item=default category=0 emailsellers=1			\
							 chargesellers=0								\
							 text=default")

	ON_PARSE_COMMAND(AdminMoveAuctionConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR										\
					 ITS_PSTR ITS_I4 ITS_I4									\
					 ITS_I4													\
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default					\
							 item=default category=0 emailsellers=1			\
							 chargesellers=0								\
							 text=default")

	ON_PARSE_COMMAND(AdminMoveAuction, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR									\
					 ITS_I4 ITS_I4 ITS_I4										\
					 ITS_PSTR													\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default item=default			\
							 category=0 emailsellers=1 chargesellers=0			\
							 text=default										\
							 selleremailsubject=default selleremailtext=default")


	ON_PARSE_COMMAND(RetractAllBids, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid cautiontothewind=0")

	ON_PARSE_COMMAND(AdminCombineUsers, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("oldid oldpass newid newpass")

	ON_PARSE_COMMAND(AdminCombineUserConf, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("oldid oldpass newid newpass")

	ON_PARSE_COMMAND(AdminChangeEmail, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(AdminChangeEmailShow, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid email")

	ON_PARSE_COMMAND(AdminChangeEmailConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid email change")

	// 01/06/98 Charles Added
	ON_PARSE_COMMAND(AdminChangeUserIdShow, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(AdminChangeUserId, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("olduserid=default		\
							 pass=default			\
							 newuserid=default		\
							 confirm=0")

	//admin update iteminfo
	ON_PARSE_COMMAND(ItemInfo, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("action item title quantity cEndTime cEndTimeHour cEndTimeMin cEndTimeSec featured=0 superfeatured=0 description=default galleryfeatured=0 gallery=0 giftIcon")

	//Admin Change item info show
	ON_PARSE_COMMAND(ChangeItemInfo, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS(" item ")

	ON_PARSE_COMMAND(AdminViewOldItem, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item")

	// Category Admin
	ON_PARSE_COMMAND(CategoryAdmin, CEBayISAPIExtension, 
					 ITS_EMPTY)	

	ON_PARSE_COMMAND(CategoryChecker, CEBayISAPIExtension, 
					 ITS_EMPTY)	
// petra 06/15/99 wired off functions
//	ON_PARSE_COMMAND(ViewCategory, CEBayISAPIExtension, 
//					 ITS_EMPTY
//					 )	
//
//	ON_PARSE_COMMAND(VerifyUpdateCategory, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 )	
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 category=default")
//
//	ON_PARSE_COMMAND(UpdateCategory, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 )	
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 category=default		\
//							 cname=default			\
//							 cdesc=default			\
//							 fileref=default		\
//							 featuredcost=default	\
//							 adult=0				\
//							 expired=default")
//
//	ON_PARSE_COMMAND(NewCategory, CEBayISAPIExtension, 
//					 ITS_EMPTY)	
//
//	ON_PARSE_COMMAND(VerifyNewCategory, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 )
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 cname=default			\
//							 cdesc=default			\
//							 adult=0				\
//							 featuredcost=default	\
//							 fileref=default		\
//							 category=default		\
//							 addaction=default")
//
//	ON_PARSE_COMMAND(AddNewCategory, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 )
//
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 cname=default			\
//							 cdesc=default			\
//							 adult=0				\
//							 featuredcost=default	\
//							 fileref=default		\
//							 category=default		\
//							 addaction=default")
//
//
//	ON_PARSE_COMMAND(DeleteCategory, CEBayISAPIExtension, 
//					 ITS_EMPTY
//					 )
//
//	ON_PARSE_COMMAND(MakeDelete, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 )
//
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 category=default")
//
//	ON_PARSE_COMMAND(MoveCategory, CEBayISAPIExtension, 
//					 ITS_EMPTY
//					 )
//
//	ON_PARSE_COMMAND(MakeMove, CEBayISAPIExtension, 
//					 ITS_PSTR ITS_PSTR ITS_PSTR
//					 ITS_PSTR
//					 )
//
//	ON_PARSE_COMMAND_PARAMS("userid=default			\
//							 pass=default			\
//							 fromcategory=default	\
//							 tocategory=default")
//
//	ON_PARSE_COMMAND(OrderCategory, CEBayISAPIExtension, 
//					 ITS_EMPTY
//					 )

	// Daily statistics
	ON_PARSE_COMMAND(AdminViewDailyStats, CEBayISAPIExtension, 
					 ITS_I4 ITS_I4 ITS_I4 
					 ITS_I4 ITS_I4 ITS_I4 
					 ITS_PSTR ITS_PSTR
					 )

	ON_PARSE_COMMAND_PARAMS("startmon startday startyear \
							 endmon endday endyear		 \
							 email pass")

	// Daily Finance
	ON_PARSE_COMMAND(AdminViewDailyFinance, CEBayISAPIExtension, 
					 ITS_I4 ITS_I4 ITS_I4 
					 ITS_I4 ITS_I4 ITS_I4 
					 ITS_PSTR ITS_PSTR
					 )

	ON_PARSE_COMMAND_PARAMS("startmon startday startyear \
							 endmon endday endyear		 \
							 email pass")


	// Announcement Admin
	ON_PARSE_COMMAND(AdminAnnouncement, CEBayISAPIExtension, 
					 ITS_I4 ITS_I4)	
	ON_PARSE_COMMAND_PARAMS("siteid=0 \
							partnerid=1")

	ON_PARSE_COMMAND(UpdateAnnouncement, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 )	
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 id=default			\
							 loc=default		\
							 siteid=0			\
							 partnerid=1")

	ON_PARSE_COMMAND(AddAnnouncement, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 )	
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 id=default			\
							 loc=default		\
							 code=default		\
							 desc=default		\
							 siteid=0			\
							 partnerid=1")

	ON_PARSE_COMMAND(SurveyResponse, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 survey=default		\
							 question=default	\
							 response=default")

	// Redirect and cookies
	ON_PARSE_COMMAND(RedirectEnter, CEBayISAPIExtension,
					ITS_PSTR ITS_PSTR
					)
	ON_PARSE_COMMAND_PARAMS("loc=default		\
							 partner=default")


	// Cobranding
	// nsacco 06/21/99 - ShowCobrandPartners takes a site id
	ON_PARSE_COMMAND(ShowCobrandPartners, CEBayISAPIExtension,
					ITS_I4)
	ON_PARSE_COMMAND_PARAMS("siteid=0")

	ON_PARSE_COMMAND(RewriteCobrandHeaders, CEBayISAPIExtension,
					ITS_EMPTY)

	ON_PARSE_COMMAND(ShowCobrandHeaders, CEBayISAPIExtension,	\
					ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("partner=0 siteid=0")

	ON_PARSE_COMMAND(ChangeCobrandHeader, CEBayISAPIExtension,	\
					ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("header=default isheader=0			\
							pagetype=0 partner=0 pagetype2=0 siteid=0")

	// nsacco 06/21/99 added siteid and dirname
	ON_PARSE_COMMAND(CreateCobrandPartner, CEBayISAPIExtension,	\
					ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("newname=default desc=default siteid=0 dirname=default")

	ON_PARSE_COMMAND(UpdateCobrandCaching, CEBayISAPIExtension,
					ITS_EMPTY)

	//email auction to friend stuff
	ON_PARSE_COMMAND(ShowEmailAuctionToFriend, CEBayISAPIExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item")

	ON_PARSE_COMMAND(EmailAuctionToFriend, CEBayISAPIExtension, \
	ITS_I4 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item userid password email=default message=default htmlenable=default")

	// Admin Board functions
	ON_PARSE_COMMAND(AdminBoardChangeShow, CEBayISAPIExtension, \
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("BoardName=default")

	ON_PARSE_COMMAND(AdminBoardChange, CEBayISAPIExtension, \
					 ITS_PSTR ITS_PSTR ITS_PSTR	ITS_PSTR 	\
					 ITS_I4 ITS_I4 ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("BoardName=default			\
							 BoardShortName=default		\
							 BoardShortDesc=default		\
							 PicURL=default				\
							 MaxPostAge=0				\
							 MaxPostCount=0				\
							 BoardDesc=default			\
							 BoardPostable=0				\
							 BoardAvailable=0")

	// invalidate lists

	ON_PARSE_COMMAND(AdminInvalidateList, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default code=0")


	ON_PARSE_COMMAND(PassRecognizer, CEBayISAPIExtension, 
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid code=0")			

	ON_PARSE_COMMAND(ChangeSecretPassword, CEBayISAPIExtension, 
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("pass=default")			

	ON_PARSE_COMMAND(ChangePasswordCrypted, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default		\
							 pass=default		\
							 newpass1=default	\
							 newpass2=default")

	// reciprocal links
	ON_PARSE_COMMAND(RegisterLinkButtons, CEBayISAPIExtension, \
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid password homepage=0 myauctions=0 urls")

	
	ON_PARSE_COMMAND(IIS_Server_status, CEBayISAPIExtension,
			 ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("pIIS_Server_status=0 pTimeDelay=default pOperatorMessage=default")

	ON_PARSE_COMMAND(IIS_Server_status_broadcast, CEBayISAPIExtension,
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR ITS_PSTR \
			 ITS_PSTR ITS_PSTR)

	 ON_PARSE_COMMAND_PARAMS("maintenance  maintenance_select  maintenance_message \
							python  python_select  python_message \
							allcgi allcgi_select allcgi_message \
							cgi cgi_select cgi_message \
							cgi1 cgi1_select cgi1_message \
							cgi2 cgi2_select cgi2_message \
							cgi3 cgi3_select cgi3_message \
							cgi4 cgi4_select cgi4_message \
							cgi5 cgi5_select cgi5_message \
							cgi6 cgi6_select cgi6_message \
							cgi7 cgi7_select cgi7_message \
							cgi8 cgi8_select cgi8_message \
							cgi9 cgi9_select cgi9_message \
							cgi10 cgi10_select cgi10_message \
							members members_select members_message \
							listings listings_select listings_message \
							search search_select search_message \
							pages pages_select pages_message \
							cobrand cobrand_select cobrand_message \
							sitesearch sitesearch_select sitesearch_message \
							future1 future1_select future1_message \
							future2 future2_select future2_message \
							future3 future3_select future3_message \
							future4 future4_select future4_message \
							future5 future5_select future5_message \
							future6 future6_select future6_message \
							future7 future7_select future7_message \
							future8 future8_select future8_message \
							future9 future9_select future9_message \
							future10 future10_select future10_message \
							userid password")

	// opt-in/opt-out
	ON_PARSE_COMMAND(OptinLogin, CEBayISAPIExtension, \
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid password")
	
	// kaz: 04/18/99 renamed from OptinSave as part of a cleanup
	ON_PARSE_COMMAND(OptinConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid \
							ChangesToAgreementOption=0 \
							ChangesToPrivacyOption=0 \
							TakePartInSurveysOption=0 \
                            SpecialOfferOption=0 \
                            EventPromotionOption=0 \
							NewsletterOption=0 \
							EndofAuctionOption=0 \
							BidOption=0 \
							OutBidOption=0 \
							ListOption=0 \
							DailyStatusOption=0")

	// User Agreement
	ON_PARSE_COMMAND(RegistrationAcceptAgreement, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("notify=0 accept=default decline=default countryid=0")

	ON_PARSE_COMMAND(CCRegistrationAcceptAgreement, CEBayISAPIExtension,
					 ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("notify=0 accept=default decline=default")

	ON_PARSE_COMMAND(UserAgreementAccept, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid password notify=0 accept=default decline=default")

	// inna - Admin Rebalance user account
 	ON_PARSE_COMMAND(AdminRebalanceUserAccount, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid")
	//inna - Admin Remove item
	ON_PARSE_COMMAND(AdminRemoveItem, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS(" item ")

	//Admin Change - Items to bring back to live auction -- Gurinder 04/30/99
	ON_PARSE_COMMAND(AdminReInstateItem, CEBayISAPIExtension, 
					 ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item userid password")
	
	ON_PARSE_COMMAND(AdminReInstateItemLogin, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item")
	//Gurinder  - 04/30/99

	//inna - admin update wacko flag
	ON_PARSE_COMMAND(WackoFlagChange, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=default wackoFlag")

	ON_PARSE_COMMAND(WackoFlagChangeConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=default wackoFlag=0")

	ON_PARSE_COMMAND(AdultLogin, CEBayISAPIExtension,
					ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default password=default")

	// kaz: 4/7/99: Police Badge T&C
	ON_PARSE_COMMAND(PoliceBadgeLoginForSelling, CEBayISAPIExtension,
					ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default accept=default decline=default")

    // gallery admin
	ON_PARSE_COMMAND(AdminGalleryItemView, CEBayISAPIExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")
	ON_PARSE_COMMAND(AdminGalleryItemDelete, CEBayISAPIExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")
	ON_PARSE_COMMAND(AdminGalleryItemDeleteConfirm, CEBayISAPIExtension, ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")
	
	// About Me

    ON_PARSE_COMMAND(ViewUserPage, CEBayISAPIExtension,
                     ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid=default page=0")

    ON_PARSE_COMMAND(CategorizeUserPage, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid=default password=default \
                            title=default remove=0 category=-1 page=0")

	// Editing User Pages

    ON_PARSE_COMMAND(UserPageLogin, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid password")

    ON_PARSE_COMMAND(UserPageAcceptAgreement, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid password notify=0 accept=default decline=default")

	// HTML editing for About Me
	// (I drag around the html from page to page.)
	ON_PARSE_COMMAND(UserPageGoToHTMLPreview, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR )
    ON_PARSE_COMMAND_PARAMS("userid password html=default")

	// petra  add two action parms
	ON_PARSE_COMMAND(UserPageHandleHTMLPreviewOptions, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid password action1=default action2=default action3=default html=default")

	ON_PARSE_COMMAND(UserPageShowConfirmHTMLEditingChoices, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password html which=0")

	// petra add one action parm
	ON_PARSE_COMMAND(UserPageConfirmHTMLEditingChoice, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid password html which=0 action1=default action2=default")

	// Template editing for About Me
	// (I drag around all the template form parameters from page to page.)
	// petra add two action* parms, init all of them to default
	ON_PARSE_COMMAND(UserPageHandleStyleOptions, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR \
					ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password \
				action1=default action2=default action3=default \
				templateLayout=0 pageTitle=default \
				textAreaTitle1=default textArea1=default \
				textAreaTitle2=default textArea2=default \
				pictureCaption=default pictureURL=http:// \
				showUserIdEmail=no \
				feedbackNumComments=10 \
				itemlistNumItems=0 itemlistCaption=default \
				favoritesDescription1=default favoritesName1=default favoritesLink1=http:// \
				favoritesDescription2=default favoritesName2=default favoritesLink2=http:// \
				favoritesDescription3=default favoritesName3=default favoritesLink3=http:// \
				item1CaptionChoice=0 item1=0 \
				item2CaptionChoice=0 item2=0 \
				item3CaptionChoice=0 item3=0 \
				pageCount=no dateTime=no bgPattern=0")

	// petra add one action parm
	ON_PARSE_COMMAND(UserPageHandleTemplateOptions, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR\
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR \
					ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password action1=default action2=default \
				templateLayout=0 pageTitle=default \
				textAreaTitle1=default textArea1=default \
				textAreaTitle2=default textArea2=default \
				pictureCaption=default pictureURL=http:// \
				showUserIdEmail=no \
				feedbackNumComments=10 \
				itemlistNumItems=0 itemlistCaption=default \
				favoritesDescription1=default favoritesName1=default favoritesLink1=http:// \
				favoritesDescription2=default favoritesName2=default favoritesLink2=http:// \
				favoritesDescription3=default favoritesName3=default favoritesLink3=http:// \
				item1CaptionChoice=0 item1=0 \
				item2CaptionChoice=0 item2=0 \
				item3CaptionChoice=0 item3=0 \
				pageCount=no dateTime=no bgPattern=0")

	// add 3 more action button parameters
	ON_PARSE_COMMAND(UserPageHandleTemplatePreviewOptions, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR \
					ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password \
				action1=default action2=default action3=default action4=default \
				templateLayout=0 pageTitle=default \
				textAreaTitle1=default textArea1=default \
				textAreaTitle2=default textArea2=default \
				pictureCaption=default pictureURL=http:// \
				showUserIdEmail=no \
				feedbackNumComments=10 \
				itemlistNumItems=0 itemlistCaption=default \
				favoritesDescription1=default favoritesName1=default favoritesLink1=http:// \
				favoritesDescription2=default favoritesName2=default favoritesLink2=http:// \
				favoritesDescription3=default favoritesName3=default favoritesLink3=http:// \
				item1CaptionChoice=0 item1=0 \
				item2CaptionChoice=0 item2=0 \
				item3CaptionChoice=0 item3=0 \
				pageCount=no dateTime=no bgPattern=0")

	ON_PARSE_COMMAND(UserPageShowConfirmTemplateEditingChoices, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR \
					ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password which=0\
				templateLayout=0 pageTitle=default \
				textAreaTitle1=default textArea1=default \
				textAreaTitle2=default textArea2=default \
				pictureCaption=default pictureURL=http:// \
				showUserIdEmail=no \
				feedbackNumComments=10 \
				itemlistNumItems=0 itemlistCaption=default \
				favoritesDescription1=default favoritesName1=default favoritesLink1=http:// \
				favoritesDescription2=default favoritesName2=default favoritesLink2=http:// \
				favoritesDescription3=default favoritesName3=default favoritesLink3=http:// \
				item1CaptionChoice=0 item1=0 \
				item2CaptionChoice=0 item2=0 \
				item3CaptionChoice=0 item3=0 \
				pageCount=no dateTime=no bgPattern=0")

	// petra add another action parm
	ON_PARSE_COMMAND(UserPageConfirmTemplateEditingChoice, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR \
					ITS_PSTR \
					ITS_I4 \
					ITS_I4 ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_PSTR ITS_PSTR ITS_PSTR \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_I4 ITS_I4 \
					ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid password action1=default action2=default which=0 \
				templateLayout=0 pageTitle=default \
				textAreaTitle1=default textArea1=default \
				textAreaTitle2=default textArea2=default \
				pictureCaption=default pictureURL=http:// \
				showUserIdEmail=no \
				feedbackNumComments=10 \
				itemlistNumItems=0 itemlistCaption=default \
				favoritesDescription1=default favoritesName1=default favoritesLink1=http:// \
				favoritesDescription2=default favoritesName2=default favoritesLink2=http:// \
				favoritesDescription3=default favoritesName3=default favoritesLink3=http:// \
				item1CaptionChoice=0 item1=0 \
				item2CaptionChoice=0 item2=0 \
				item3CaptionChoice=0 item3=0 \
				pageCount=no dateTime=no bgPattern=0")

	ON_PARSE_COMMAND(AdultLoginShow, CEBayISAPIExtension, ITS_I4)
    ON_PARSE_COMMAND_PARAMS("t=0")

	//
	// Gift Alert
	//
	ON_PARSE_COMMAND(ViewGiftAlert, CEBayISAPIExtension, ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("item userid=default")
	
	ON_PARSE_COMMAND(RequestGiftAlert, CEBayISAPIExtension, ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("item userid=default")
	
	ON_PARSE_COMMAND(SendGiftAlert, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR \
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 \
					 ITS_PSTR ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid password fromname item \
							 toname destemail message=default occasion=0 \
							 month=default day=default year=default")
	
	ON_PARSE_COMMAND(ViewGiftCard, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("senderuserid sendername recipientname item \
							 occasion=0 opendate=default")
	
	ON_PARSE_COMMAND(ViewGiftCard2, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("uid sn rn iid od=default occ=0")
	
	ON_PARSE_COMMAND(ViewGiftItem, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("uid sn iid od=default occ=0")

	ON_PARSE_COMMAND(SendQueryEmail, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default \
							 pass=default \
							 subject=default \
							 message=default \
							 maildestination=0")

	ON_PARSE_COMMAND(SendQueryEmailShow, CEBayISAPIExtension, ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("subject=default")

	// Report questionable item to support
	ON_PARSE_COMMAND(ReportQuestionableItem, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default \
							 pass=default \
							 itemtype=default \
							 item=0			\
							 message=default")
	ON_PARSE_COMMAND(ReportQuestionableItemShow, CEBayISAPIExtension, ITS_I4)
    ON_PARSE_COMMAND_PARAMS("item=0")

	//
	// Notes
	//	Only usable by Admin now
	//
	ON_PARSE_COMMAND(AdminAddNoteAboutUserShow, CEBayISAPIExtension,\
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("aboutuser=default")

	ON_PARSE_COMMAND(AdminAddNoteAboutUser, CEBayISAPIExtension,	\
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR			\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default							\
							 pass=default							\
							 aboutuser=default						\
							 subject=default 						\
							 type=0 								\
							 text=default")

	ON_PARSE_COMMAND(AdminAddNoteAboutItemShow, CEBayISAPIExtension,\
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("aboutitem=default")

	ON_PARSE_COMMAND(AdminAddNoteAboutItem, CEBayISAPIExtension,	\
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR			\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default							\
							 pass=default							\
							 aboutitem=default						\
							 subject=default 						\
							 type=0 								\
							 text=default")


	ON_PARSE_COMMAND(AdminShowNoteShow, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR ITS_PSTR						\
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default							\
							 pass=default							\
							 aboutfilter=default					\
							 typefilter=0")

	ON_PARSE_COMMAND(AdminShowNote, CEBayISAPIExtension,			\
					 ITS_PSTR ITS_PSTR ITS_PSTR						\
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default							\
							 pass=default							\
							 aboutfilter=default					\
							 typefilter=0")

	


	// Routines for testing the location-related routines
	ON_PARSE_COMMAND(LocationsCompareZipToAC, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("zip ac")

	ON_PARSE_COMMAND(LocationsCompareZipToState, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("zip state")
	
	ON_PARSE_COMMAND(LocationsCompareStateToAC, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("state ac")

	ON_PARSE_COMMAND(LocationsCompareZipToCity, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("zip city")

	ON_PARSE_COMMAND(LocationsCompareCityToAC, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("city ac")

	ON_PARSE_COMMAND(LocationsIsValidZip, CEBayISAPIExtension,
                    ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("zip")

	ON_PARSE_COMMAND(LocationsIsValidAC, CEBayISAPIExtension,
                    ITS_I4)
    ON_PARSE_COMMAND_PARAMS("ac")

	ON_PARSE_COMMAND(LocationsIsValidCity, CEBayISAPIExtension,
                    ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("city")

	ON_PARSE_COMMAND(LocationsDistanceZipAC, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("zip ac")

	ON_PARSE_COMMAND(LocationsDistanceZipZip, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("zip1 zip2")

	ON_PARSE_COMMAND(LocationsDistanceACAC, CEBayISAPIExtension,
                    ITS_I4 ITS_I4)
    ON_PARSE_COMMAND_PARAMS("ac1 ac2")


	ON_PARSE_COMMAND(AsparagusBananaSandwich, CEBayISAPIExtension,
					ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("ren=default stimpy=default")

	//inna, sam: iEscrow stuff
	ON_PARSE_COMMAND(IEscrowLogin, CEBayISAPIExtension, ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0 type=default bidderno=0")

	ON_PARSE_COMMAND(IEscrowShowData, CEBayISAPIExtension, 
					ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid pass item=0 type=initial bidderno=0")


	ON_PARSE_COMMAND(IEscrowSendData, CEBayISAPIExtension, 
					ITS_PSTR ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("partyone item=0 type=initial qty=1 bidderno=0 partytwo")

	// deadbeats
	ON_PARSE_COMMAND(ViewDeadbeatUser, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("deadbeatuserid")

	ON_PARSE_COMMAND(ViewDeadbeatUsers, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(DeleteDeadbeatItem, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("selleruserid bidderuserid itemno confirm=0")

	//Validate the user for survey
	ON_PARSE_COMMAND(ValidateUserForSurvey, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("surveyid=0")

	ON_PARSE_COMMAND(GoToSurvey, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default surveyid=0")

    // Top Seller
    ON_PARSE_COMMAND(ShowTopSellerStatus, CEBayISAPIExtension,
                    ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid=default")

    ON_PARSE_COMMAND(SetTopSellerLevelConfirmation, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid=default level=-1")

    ON_PARSE_COMMAND(SetTopSellerLevel, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("userid=default level=-1")

    ON_PARSE_COMMAND(SetMultipleTopSellers, CEBayISAPIExtension,
                    ITS_PSTR ITS_I4)
    ON_PARSE_COMMAND_PARAMS("text=default level=-1")

    ON_PARSE_COMMAND(ShowTopSellers, CEBayISAPIExtension,
                    ITS_I4)
    ON_PARSE_COMMAND_PARAMS("level=-1")

	ON_PARSE_COMMAND(PowerSellerRegisterShow, CEBayISAPIExtension,
                    ITS_PSTR ITS_PSTR)
    ON_PARSE_COMMAND_PARAMS("userid=default pass=default")

	ON_PARSE_COMMAND(PowerSellerRegister, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=0 pass=default accept=default decline=default")


	//Contact eBay
	ON_PARSE_COMMAND(ContacteBay, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("item=0")

/*	ON_PARSE_COMMAND(TurnOnBidNoticesChinese, CEBayISAPIExtension,
					 ITS_EMPTY)
	ON_PARSE_COMMAND(TurnOffBidNoticesChinese, CEBayISAPIExtension,
					 ITS_EMPTY)
	ON_PARSE_COMMAND(TurnOnBidNoticesDutch, CEBayISAPIExtension,
					 ITS_EMPTY)
	ON_PARSE_COMMAND(TurnOffBidNoticesDutch, CEBayISAPIExtension,
					 ITS_EMPTY)
	ON_PARSE_COMMAND(TurnOnOutBidNoticesChinese, CEBayISAPIExtension,
					 ITS_EMPTY)
	ON_PARSE_COMMAND(TurnOffOutBidNoticesChinese, CEBayISAPIExtension,
					 ITS_EMPTY)
*/
	ON_PARSE_COMMAND(InstallNewMailMachineList, CEBayISAPIExtension,
					 ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("machines=default poolType=0")

	ON_PARSE_COMMAND(ToggleMailMachineBidStatus, CEBayISAPIExtension,
					 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("bidType=-1 state=-1")

	ON_PARSE_COMMAND(ShowMailMachineStatus, CEBayISAPIExtension,
					 ITS_EMPTY)

	// Legal Buddies
	ON_PARSE_COMMAND(AdminAddScreeningCriteria, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("categoryid=0")

	ON_PARSE_COMMAND(AdminAddScreeningCriteriaShow, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("categoryid=0 filterid=0 messageid=0 action=0")

	ON_PARSE_COMMAND(AdminViewScreeningCriteria, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(AdminViewScreeningCriteriaShow, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("categoryid=0")

	ON_PARSE_COMMAND(AdminAddFilter, CEBayISAPIExtension,
					 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("action=0 filterid=0")

	ON_PARSE_COMMAND(AdminModifyFilter, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("action=1")

	ON_PARSE_COMMAND(AdminAddFilterShow, CEBayISAPIExtension,	
					 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4
					 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("action=0 filterid=0 name=default					\
							expression=default actiontype=0						\
							notifytype=0 blockedmessage=0 flaggedmessage=0		\
							filteremailtext=0 buddyemailtext=0					\
							filteremailaddress=default buddyemailaddress=default")
	
	ON_PARSE_COMMAND(AdminAddMessage, CEBayISAPIExtension,
					 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("action=0 messageid=0")

	ON_PARSE_COMMAND(AdminModifyMessage, CEBayISAPIExtension,
					 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("action=1")

	ON_PARSE_COMMAND(AdminAddMessageShow, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_PSTR ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("action=0 messageid=0 name=default message=default messagetype=0")


	ON_PARSE_COMMAND(AdminViewBlockedItem, CEBayISAPIExtension,
						ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("item r=0 t=0 tc=0")

	ON_PARSE_COMMAND(AdminReinstateAuctionShow, CEBayISAPIExtension,\
					 ITS_I4 ITS_PSTR								\
					 ITS_PSTR ITS_PSTR								\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("action=0 userid=default				\
								pass=default item=default			\
								type=0 text=default")

	ON_PARSE_COMMAND(AdminReinstateAuctionConfirm, CEBayISAPIExtension,\
					 ITS_I4 ITS_PSTR									\
					 ITS_PSTR ITS_PSTR								\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("action=0 userid=default				\
								pass=default item=default			\
								type=0 text=default")

	ON_PARSE_COMMAND(AdminReinstateAuction, CEBayISAPIExtension,	\
					 ITS_I4 ITS_PSTR ITS_PSTR						\
					 ITS_PSTR ITS_I4 ITS_PSTR						\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("action=0 userid=default				\
								pass=default item=default			\
								type=0 text=default					\
								emailsubject=default				\
								emailtext=default")

	ON_PARSE_COMMAND(AdminUnflagUserShow, CEBayISAPIExtension,		\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminUnflagUserConfirm, CEBayISAPIExtension,	\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default")

	ON_PARSE_COMMAND(AdminUnflagUser, CEBayISAPIExtension,			\
					 ITS_PSTR ITS_PSTR								\
					 ITS_PSTR										\
					 ITS_I4 ITS_PSTR								\
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default pass=default			\
							 target=default							\
							 type=0 text=default					\
							 emailsubject=default emailtext=default")


	// Personal Shopper
	ON_PARSE_COMMAND(PersonalShopperViewSearches, CEBayISAPIExtension,			\
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default							\
							 pass=default							\
							 acceptcookie=0							\
							 agree=n")
	ON_PARSE_COMMAND(PersonalShopperAddSearch, CEBayISAPIExtension,		\
						ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR		\
						ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR	\
						ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default								\
							 pass=default								\
							 acceptcookie=0								\
							 query=default								\
							 srchdesc=n									\
							 minPrice=default							\
							 maxPrice=default							\
							 psfreq=default							\
							 psdura=default							\
							 psreg=default								\
							 agree=n")

	ON_PARSE_COMMAND(PersonalShopperSaveSearch, CEBayISAPIExtension,		\
						ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR		\
						ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default								\
							 pass=default								\
							 acceptcookie=0								\
							 query=default								\
							 srchdesc=n									\
							 minPrice=default							\
							 maxPrice=default							\
							 psfreq=default							\
							 psdura=default							\
							 psreg=default")


	ON_PARSE_COMMAND(PersonalShopperDeleteSearchView, CEBayISAPIExtension,		\
						ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR ITS_PSTR 		\
						ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default								\
							 pass=default								\
							 acceptcookie=0								\
							 query=default								\
							 srchdesc=n									\
							 minPrice=default							\
							 maxPrice=default							\
							 psfreq=default							\
							 psdura=default							\
							 psreg=default")
  
	ON_PARSE_COMMAND(PersonalShopperDeleteSearch, CEBayISAPIExtension,		\
						ITS_PSTR ITS_PSTR ITS_I4 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default								\
							 pass=default								\
							 acceptcookie=0								\
							 psreg=default")

	//get about me page by userid
	ON_PARSE_COMMAND(GetUserAboutMe, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("userid=default")

	ON_PARSE_COMMAND(MemberSearchShow, CEBayISAPIExtension,
					 ITS_EMPTY)

    // admin tool for adding/deleting special items into table ebay_special_items
	ON_PARSE_COMMAND(AdminSpecialItemsTool, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(AdminSpecialItemAdd, CEBayISAPIExtension, ITS_PSTR ITS_I4)
	ON_PARSE_COMMAND_PARAMS("itemno kind")

	ON_PARSE_COMMAND(AdminSpecialItemDelete, CEBayISAPIExtension, ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("itemno")

	ON_PARSE_COMMAND(AdminSpecialItemFlush, CEBayISAPIExtension, ITS_EMPTY)

	ON_PARSE_COMMAND(AdminAddCobrandAdShow, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(AdminAddCobrandAdConfirm, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("name text") 

	ON_PARSE_COMMAND(AdminAddCobrandAd, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("name text") 

	ON_PARSE_COMMAND(AdminSelectCobrandAdSiteShow, CEBayISAPIExtension,
					 ITS_EMPTY)

	ON_PARSE_COMMAND(AdminSelectCobrandAdPartnerAndPageShow, CEBayISAPIExtension,
					 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("ad=0 site=-1")

	ON_PARSE_COMMAND(AdminAddCobrandAdToSitePageConfirm, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("ad=0				\
							 site=-1			\
							 partner=-1			\
							 pagetype1=-1		\
							 pagetype2=-1")

	ON_PARSE_COMMAND(AdminAddCobrandAdToSitePage, CEBayISAPIExtension,
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)
	ON_PARSE_COMMAND_PARAMS("ad=0				\
							 site=-1			\
							 partner=-1			\
							 pagetype1=-1		\
							 pagetype2=-1		\
							 contextvalue=-1")

	// AOL Registration Stuff
	ON_PARSE_COMMAND(AOLRegisterShow, CEBayISAPIExtension,
					 ITS_PSTR)
	ON_PARSE_COMMAND_PARAMS("aolname=''")

	// nsacco 07/07/99 added siteid and copartnerid
	ON_PARSE_COMMAND(AOLRegisterPreview, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=''		\
							 email=''		\
							 name=''		\
							 company=''	\
							 address=''	\
							 city=''		\
							 state=''		\
							 zip=''		\
							 country=other   	\
							 countryid=0        \
							 dayphone1=''	\
							 dayphone2=''	\
							 dayphone3=''	\
  							 dayphone4=''	\
							 nightphone1=''	\
							 nightphone2=''	\
							 nightphone3=''	\
							 nightphone4=''	\
							 faxphone1=''		\
							 faxphone2=''	\
							 faxphone3=''	\
							 faxphone4=''	\
							 gender=u		\
							 Q1=1	\
							 Q17=''	\
							 Q18=''	\
							 Q19='' \
							 Q20='' \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1	\
							 newpass=''		\
							 newpass2=''	\
							 partnerID=0	\
							 siteid=0		\
							 copartnerid=0	\
							 UsingSSL=0		\
							 verify=0") 		

	// nsacco 07/07/99 added siteid and copartnerid
	ON_PARSE_COMMAND(AOLRegisterUserID, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4  
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=''		\
							 email=''		\
							 name=''		\
							 company=''	\
							 address=''	\
							 city=''		\
							 state=''		\
							 zip=''		\
							 country=other   	\
							 countryid=0        \
							 dayphone1=''	\
							 dayphone2=''	\
							 dayphone3=''	\
  							 dayphone4=''	\
							 nightphone1=''	\
							 nightphone2=''	\
							 nightphone3=''	\
							 nightphone4=''	\
							 faxphone1=''		\
							 faxphone2=''	\
							 faxphone3=''	\
							 faxphone4=''	\
							 gender=u		\
							 Q1=1	\
							 Q17=''	\
							 Q18=''	\
							 Q19='' \
							 Q20='' \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1	\
							 newpass=''		\
							 newpass2=''	\
							 partnerID=0	\
							 siteid=0		\
							 copartnerid=0	\
							 UsingSSL=0		\
							 verify=0") 		

	// nsacco 07/07/99 added siteid and copartnerid
	ON_PARSE_COMMAND(AOLRegisterUserAgreement, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4  
					 ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=''		\
							 email=''		\
							 name=''		\
							 company=''	\
							 address=''	\
							 city=''		\
							 state=''		\
							 zip=''		\
							 country=other   	\
							 countryid=0        \
							 dayphone1=''	\
							 dayphone2=''	\
							 dayphone3=''	\
  							 dayphone4=''	\
							 nightphone1=''	\
							 nightphone2=''	\
							 nightphone3=''	\
							 nightphone4=''	\
							 faxphone1=''		\
							 faxphone2=''	\
							 faxphone3=''	\
							 faxphone4=''	\
							 gender=u		\
							 Q1=1	\
							 Q17=''	\
							 Q18=''	\
							 Q19='' \
							 Q20='' \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1	\
							 newpass=''		\
							 partnerID=0	\
							 siteid=0		\
							 copartnerid=0	\
							 UsingSSL=0		\
							 verify=0") 		

	// nsacco 07/07/99 added siteid and copartnerid
	ON_PARSE_COMMAND(AOLRegisterUserAcceptAgreement, CEBayISAPIExtension,
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR  
					 ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR ITS_PSTR 
					 ITS_I4 ITS_PSTR ITS_PSTR
					 ITS_PSTR ITS_PSTR ITS_PSTR
					 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4  
					 ITS_PSTR ITS_PSTR ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4 ITS_I4)

	ON_PARSE_COMMAND_PARAMS("userid=''		\
							 email=''		\
							 name=''		\
							 company=''	\
							 address=''	\
							 city=''		\
							 state=''		\
							 zip=''		\
							 country=other   	\
							 countryid=0        \
							 dayphone1=''	\
							 dayphone2=''	\
							 dayphone3=''	\
  							 dayphone4=''	\
							 nightphone1=''	\
							 nightphone2=''	\
							 nightphone3=''	\
							 nightphone4=''	\
							 faxphone1=''		\
							 faxphone2=''	\
							 faxphone3=''	\
							 faxphone4=''	\
							 gender=u		\
							 Q1=1	\
							 Q17=''	\
							 Q18=''	\
							 Q19='' \
							 Q20='' \
							 Q7=1	\
							 Q14=1	\
							 Q3=1	\
							 Q4=1	\
							 Q5=1	\
							 Q16=1	\
							 newpass=''		\
							 buttonPressed1=default  \
							 buttonPressed2=default  \
							 notify=0		\
							 agreementQ1=0	\
							 agreementQ2=0	\
							 partnerID=0	\
							 siteid=0		\
							 copartnerid=0	\
							 UsingSSL=0		\
							 verify=0") 		

	ON_PARSE_COMMAND(AOLRegisterComplete, CEBayISAPIExtension, ITS_PSTR)

	ON_PARSE_COMMAND_PARAMS("userid=''")

	ON_PARSE_COMMAND(AOLRegisterConfirm, CEBayISAPIExtension, 
						ITS_I4 ITS_PSTR ITS_I4)

	ON_PARSE_COMMAND_PARAMS("number=0		\
								userid=''	\
								verify=0")


END_PARSE_MAP(CEBayISAPIExtension)

char IIS_Server_down_message[200]= " Our Database is down.  This screen is automatically generated.  Soon our operators will input more informed messages.";
char IIS_Server_down_time[100]= "Our operator has not determine when service will be back up.";

void display_IIS_Server_down_page(clseBayApp *pApp) 
{ 
	// TODO - remove www2 ?

	*pApp->mpStream <<	"<b>We're sorry, but the eBay system is temporarily unavailable.</b>\n"
					<<	"<p>\n"
					<<	"We are extremely sorry for this inconvenience."
					<<	"<p>Please see the "
					<<	"<a href=\"http://www2.ebay.com/aw/announce.shtml\">"
					<<	"The eBay Announcements Board</a> for more information.\n"
					<<	"<p>The <a href=\"http://pages.ebay.com/help/community/png-extn.html\">Automatic Auction Extension Policy</a> provides details about when eBay will automatically extend auctions following an unscheduled outage.\n"
					<<	"\n";
	pApp->CleanUp();
	*pApp->mpStream << flush;
}

// There must be a better place to put this. I'm just not quite
// sure where at the moment. Or maybe it does belong here...?

// FillElements helps the UserPage features from needing to pass around a
// gazillion parameters. It takes the ISAPI parameters and sets the
// arrays in a structure.
void FillElements(TemplateElements *elements, 
	int   templateLayout,
	char *pPageTitle,
	char *pTextAreaTitle1,
	char *pTextArea1,
	char *pTextAreaTitle2,
	char *pTextArea2,
	char *pPictureCaption,
	char *pPictureURL,
	char *pShowUserIdEmail, /* true/false */
	int   feedbackNumComments,
	int   itemlistNumItems,
	char *pItemlistCaption,
	char *pFavoritesDescription1,
	char *pFavoritesName1,
	char *pFavoritesLink1,
	char *pFavoritesDescription2,
	char *pFavoritesName2,
	char *pFavoritesLink2,
	char *pFavoritesDescription3,
	char *pFavoritesName3,
	char *pFavoritesLink3,
	int   item1CaptionChoice,
	int   item1,
	int   item2CaptionChoice,
	int   item2,
	int   item3CaptionChoice,
	int   item3,
	char *pPageCount, /* true/false */
	char *pDateTime,  /* true/false */
	int   bgPattern)
{

	elements->templateLayout = templateLayout;
	
	if (strcmp(pPageTitle, "default") == 0)
		elements->pPageTitle = NULL;
	else 
		elements->pPageTitle = pPageTitle;

	if (strcmp(pTextAreaTitle1, "default") == 0)
		elements->pTextAreaTitle1 = NULL;
	else
		elements->pTextAreaTitle1 = pTextAreaTitle1;

	if (strcmp(pTextArea1, "default") == 0)
		elements->pTextArea1 = NULL;
	else
		elements->pTextArea1 = pTextArea1;

	if (strcmp(pTextAreaTitle2, "default") == 0)
		elements->pTextAreaTitle2 = NULL;
	else
		elements->pTextAreaTitle2 = pTextAreaTitle2;

	if (strcmp(pTextArea2, "default") == 0)
		elements->pTextArea2 = NULL;
	else
		elements->pTextArea2 = pTextArea2;

	if (strcmp(pPictureCaption, "default") == 0)
		elements->pPictureCaption = NULL;
	else
		elements->pPictureCaption = pPictureCaption;
	
	elements->pPictureURL = pPictureURL;

	elements->showUserIdEmail  = strcmp(pShowUserIdEmail, "no") != 0;

	elements->feedbackNumComments = feedbackNumComments;
	elements->itemlistNumItems = itemlistNumItems;

	if (strcmp(pItemlistCaption, "default") == 0)
		elements->pItemlistCaption = NULL;
	else
		elements->pItemlistCaption = pItemlistCaption;

	if (strcmp(pFavoritesDescription1, "default") == 0)
		elements->pFavoritesDescription1 = NULL;
	else 
		elements->pFavoritesDescription1 = pFavoritesDescription1;

	if (strcmp(pFavoritesName1, "default") == 0)
		elements->pFavoritesName1 = NULL;
	else 
		elements->pFavoritesName1 = pFavoritesName1;

	if (strcmp(pFavoritesLink1, "default") == 0)
		elements->pFavoritesLink1 = NULL;
	else 
		elements->pFavoritesLink1 = pFavoritesLink1;

	if (strcmp(pFavoritesDescription2, "default") == 0)
		elements->pFavoritesDescription2 = NULL;
	else 
		elements->pFavoritesDescription2 = pFavoritesDescription2;

	if (strcmp(pFavoritesName2, "default") == 0)
		elements->pFavoritesName2 = NULL;
	else 
		elements->pFavoritesName2 = pFavoritesName2;

	if (strcmp(pFavoritesLink2, "default") == 0)
		elements->pFavoritesLink2 = NULL;
	else 
		elements->pFavoritesLink2 = pFavoritesLink2;

	if (strcmp(pFavoritesDescription3, "default") == 0)
		elements->pFavoritesDescription3 = NULL;
	else 
		elements->pFavoritesDescription3 = pFavoritesDescription3;

	if (strcmp(pFavoritesName3, "default") == 0)
		elements->pFavoritesName3 = NULL;
	else 
		elements->pFavoritesName3 = pFavoritesName3;

	if (strcmp(pFavoritesLink3, "default") == 0)
		elements->pFavoritesLink3 = NULL;
	else 
		elements->pFavoritesLink3 = pFavoritesLink3;


	elements->item1CaptionChoice = item1CaptionChoice;
	elements->item1 = item1;
	elements->item2CaptionChoice = item2CaptionChoice;
	elements->item2 = item2;
	elements->item3CaptionChoice = item3CaptionChoice;
	elements->item3 = item3;
	
	// Not active for now:
	elements->pageCount = strcmp(pPageCount, "no") != 0;

	elements->dateTime  = strcmp(pDateTime, "no") != 0;
	elements->bgPattern = bgPattern;

	return;
}


///////////////////////////////////////////////////////////////////////
// The one and only CEBayISAPIExtension object

CEBayISAPIExtension theExtension;


///////////////////////////////////////////////////////////////////////
// CEBayISAPIExtension implementation
// Debugging and exception handling stuff
//#define DUMPING_FOR_DEBUGGING
#ifdef DUMPING_FOR_DEBUGGING
bool dumping_for_debugging = true;
#else
bool dumping_for_debugging = false;
#endif

void MaybeDumpStackAndRegisters(CHttpServerContext *pCtxt)
{
#ifdef DUMPING_FOR_DEBUGGING
#pragma message("Warning: Dumping for debugging is enabled: " __FILE__)
	*pCtxt << "<pre>Stack trace follows:\n" << StackTraceBuffer << "</pre>";
#endif
}

extern "C" void NtStackTrace(char *msg);
int MyReportHook( int reportType, char *message, int *returnValue )
{
#ifdef _DEBUG
	if (reportType != _CRT_WARN)
	{
		NtStackTrace(message);
		*returnValue = 0;
		return TRUE;
	}
#endif
	*returnValue = 0;
	return FALSE;
}

CEBayISAPIExtension::CEBayISAPIExtension()
{
	// Set the report hook to our dumper.
	_CrtSetReportHook(MyReportHook);

	// Let's see if we have any thread local storage. If
	// not, make some
	if (g_tlsindex == 0xDEADDEAD)
		g_tlsindex	= TlsAlloc();


	//g_tlsindex	= GetTlsIndex();
	//gServerId	= SERVER_EBAY;

	tcp_start(0,0);

	return;
}

CEBayISAPIExtension::~CEBayISAPIExtension()
{
	clseBayApp	*pApp;

	pApp	= (clseBayApp *)GetApp();

	if (pApp)
		delete	pApp;

	CLEARPOINTER;

	tcp_finish();

	return;
}

BOOL CEBayISAPIExtension::GetExtensionVersion(HSE_VERSION_INFO* pVer)
{
	// Call default implementation for initialization
	CHttpServer::GetExtensionVersion(pVer);

	// Load description string
	TCHAR sz[HSE_MAX_EXT_DLL_NAME_LEN+1];
	ISAPIVERIFY(::LoadString(AfxGetResourceHandle(),
			IDS_SERVER, sz, HSE_MAX_EXT_DLL_NAME_LEN));
	_tcscpy(pVer->lpszExtensionDesc, sz);

	// Make the debugger _not_ pop up dialog boxes, since
// this _kills_ the threads.
//	_CrtSetReportMode(_CRT_WARN, _CRTDBG_MODE_FILE);
//	_CrtSetReportMode(_CRT_ERROR, _CRTDBG_MODE_FILE);
//	_CrtSetReportMode(_CRT_ASSERT, _CRTDBG_MODE_FILE);
//	_CrtSetReportFile(_CRT_WARN, _CRTDBG_FILE_STDERR);
//	_CrtSetReportFile(_CRT_ERROR, _CRTDBG_FILE_STDERR);
//	_CrtSetReportFile(_CRT_ASSERT, _CRTDBG_FILE_STDERR);


	return TRUE;
}

void CEBayISAPIExtension::StartContent(CHttpServerContext *pCtxt) const
{
	clseBayCookie *pCookie;
	const char *pHeader;

	if (!gApp)
	{
		CHttpServer::StartContent(pCtxt);
		return;
	}

	pCookie = ((clseBayApp *) gApp)->GetCookie();

	if (!pCookie || !(pHeader = pCookie->GetCookieHeader()))
	{
		CHttpServer::StartContent(pCtxt);
	}
	else
	{
		pCtxt->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER,
			NULL,
			NULL,
			(unsigned long *) pHeader);
		// Comment this out, since the documentation says StartContent does this,
		// but it doesn't really look like it does.
//		((clseBayApp *) gApp)->SendString("<HTML><HEAD>");
	}
}



//
//
//

eBayISAPIAuthEnum DetermineAuthorization(CHttpServerContext *pCtxt)
{
	char			path[256];
	DWORD			pathSize		= sizeof(path);

	memset(&path, 0x00, sizeof(path));
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_USER",
										&path,
										&pathSize);

	if (strcmp(path, "EBAY\\support") == 0 ||
		strcmp(path, "support") == 0)
	{
		return eBayISAPIAuthAdmin;
	}

	return eBayISAPIAuthUser;
}

// check to see if the remote host is in our list of trusted domains
char *eBayDomains[] = {
	"209.1.128",
	"216.32.114",
	"216.32.120",
	"216.33.16"
};

bool RemoteAddrIneBayDomain(clseBayApp	*pApp)
{
	char *pHost = pApp->GetEnvironment()->GetRemoteAddr();
	bool retvalue = false;
	int i = 0, j = sizeof (eBayDomains) / sizeof (char *);
	
	// see if we are in one of our known domains
	if(pHost) {
		while(i < j) {
				if(strncmp(pHost, eBayDomains[i], strlen(eBayDomains[i])) == 0) {
				retvalue = true;
				break;
			}
			i++;
		}
	}
	
	return retvalue;
}

clseBayApp *CEBayISAPIExtension::CreateeBayApp()
{
	clseBayApp	*pApp;

	pApp = new clseBayApp;
	SetApp(gServerId, pApp);
    pApp->SetUp();
	return pApp;
}
//
// MakeFeatured
//
void CEBayISAPIExtension::ValidateInternals(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ValidateInternals\n",
				GetCurrentThreadId(), GetCurrentThreadId());



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY

	pApp->SetCurrentPage(PageFeatured);
	pApp->ValidateInternals();
	*pApp->mpStream << flush;

	MYCATCH("ValidateInternals")

	EndContent(pCtxt);
}




#if 0		// This routine is just for experimentation
int CEBayISAPIExtension::DebugTest(CHttpServerContext *pCtxt)
{
	ISAPITRACE("0x%x %d DebugTest\n",
				GetCurrentThreadId(), GetCurrentThreadId());
	
	StartContent(pCtxt);

	clseBayApp	*pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
		<< "About to crash.\n";


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	*pApp->mpStream << "<HTML><BODY>"
	//_ASSERT(0);
	// Force a null dereference.
	char *p = NULL;

	char x;

	x = *p;

	*pApp->mpStream	<< "</BODY></HTML>" << flush;

	EndContent(pCtxt);

	return callOK;
}
#endif	// experimentation

//
// Bids
//
int CEBayISAPIExtension::MakeBid(CHttpServerContext* pCtxt,
									 int item,
									 //LPTSTR pUserId,
									// LPTSTR pPass,
									 LPTSTR pMaxbid,
									 int quant,
									 LPSTR pAccept,
									 LPSTR pDecline,
									 int notify)
{
	clseBayApp	*pApp;
	UAChoice	uaChoice;
	char		pItem[32];

	// Sanity Checks
	if (//!ValidateUserId((char *)pUserId) || 
//		!ValidatePassword((char *)pPass)	||
		!AfxIsValidAddress(pMaxbid, 1, false)					||
		!AfxIsValidAddress(pAccept, 1, false)					||
		!AfxIsValidAddress(pDecline, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d MakeBid %d  %s %d %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				item, pMaxbid, quant, pAccept, pDecline, notify);
	
	int acceptButton = strcmp(pAccept, "default");
	int declineButton = strcmp(pDecline, "default");

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}
	
	sprintf(pItem, "%d", item); // for MonsterBugSanityCheck
	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "MakeBid", pItem, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	// Did the user click a user agreement button? 
	// If so, which one? And did the user click the
	// checkbox for being notified if the user
	// agreement ever changes? So many questions...

	if (acceptButton == 0 && declineButton == 0)
		uaChoice = UAShowAgreement;
	else if (declineButton != 0)
		uaChoice = UADeclined;
	else if (notify == 1)
		uaChoice = UAAcceptedWithNotify;
	else
		uaChoice = UAAcceptedWithoutNotify;

	MYTRY
	pApp->SetCurrentPage(PageMakeBid);

	pApp->MakeBid((CEBayISAPIExtension *)this, item, 
	//			   (char *)pUserId, 
				   //(char *)pPass,
				   pMaxbid, quant, uaChoice, pCtxt);
	*pApp->mpStream << flush;

	MYCATCH("MakeBid")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}

int CEBayISAPIExtension::AcceptBid(CHttpServerContext* pCtxt,
									int item,
									LPTSTR pKey,
									LPTSTR pUserId,
									LPTSTR pPass,
									LPTSTR pMaxbid,
									int quant,
									LPSTR pAccept,
									LPSTR pDecline,
									int notify)
{
	clseBayApp	*pApp;
	UAChoice	uaChoice;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	||
		!AfxIsValidAddress(pMaxbid, 1, false)					||
		!AfxIsValidAddress(pKey, 1, false)						||
		!AfxIsValidAddress(pAccept, 1, false)					||
		!AfxIsValidAddress(pDecline, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AcceptBid %d %s %s %s %d %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				item, pUserId, pPass, pMaxbid, quant, pAccept, pDecline, notify);


	int acceptButton = strcmp(pAccept, "default");
	int declineButton = strcmp(pDecline, "default");

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();


	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "AcceptBid", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	// Did the user click a user agreement button? 
	// If so, which one? And did the user click the
	// checkbox for being notified if the user
	// agreement ever changes? So many questions... vicki

	if (acceptButton == 0 && declineButton == 0)
		uaChoice = UAShowAgreement;
	else if (declineButton != 0)
		uaChoice = UADeclined;
	else if (notify == 1)
		uaChoice = UAAcceptedWithNotify;
	else
		uaChoice = UAAcceptedWithoutNotify;

	MYTRY
	pApp->SetCurrentPage(PageAcceptBid);

	pApp->AcceptBid((CEBayISAPIExtension *)this, item, 
					(char*) pKey,
					(char *)pUserId, 
					(char *)pPass,
					 pMaxbid, quant, uaChoice, pCtxt);
	*pApp->mpStream << flush;

	MYCATCH("AcceptBid")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::RetractBid(CHttpServerContext* pCtxt,
									   LPTSTR pUserId,
									   LPTSTR pPass,
									   int item,
									   LPTSTR pReason)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d RetractBid %s %s %d %.40s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUserId, pPass, item, pReason);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageRetractBid);

	pApp->RetractBid((CEBayISAPIExtension *)this, item, 
					  (char *)pUserId, (char *)pPass,
					  (char *)pReason);
	*pApp->mpStream << flush;

	MYCATCH("RetractBid")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::CancelBid(CHttpServerContext* pCtxt,
									   LPTSTR pSellerUserId,
									   LPTSTR pSellerPass,
									   int item,
									   LPTSTR pUserId,
									   LPTSTR pReason)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pSellerUserId) || !ValidatePassword(pSellerPass) || 
		!ValidateUserId(pUserId) || !AfxIsValidAddress(pReason, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d CancelBid %s %s %d %s %.40s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pSellerUserId, pSellerPass, item, pUserId, pReason);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageCancelBid);

	pApp->CancelBid((CEBayISAPIExtension *)this,
					 (char *)pSellerUserId, (char *)pSellerPass,
					 item,
					 (char *)pUserId, (char *)pReason);
	*pApp->mpStream << flush;

	MYCATCH("CancelBid")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::ViewBids(CHttpServerContext* pCtxt,
								 int item)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ViewBids %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageViewBids);

	pApp->ViewBids((CEBayISAPIExtension *)this,
				   item);
	*pApp->mpStream << flush;

	MYCATCH("ViewBids")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::ViewBidderWithEmails(CHttpServerContext* pCtxt,
								 int item,
								 LPCTSTR	pUserId,
								 LPCTSTR	pPass,
								 int acceptCookie)

{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ViewBids %d %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item, pUserId, pPass);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	if ((acceptCookie == 1) && pApp->DropUserIdCookie((char *) pUserId, (char *) pPass, pCtxt))
	{
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUserValidation()->
			SetSoftValidation(true, pUserId);
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
	pApp->SetCurrentPage(PageViewBidderWithEmails);

	pApp->ViewBidderWithEmails((CEBayISAPIExtension *)this,
				   item, (char *)pUserId, (char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("ViewBidderWithEmails")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}


int CEBayISAPIExtension::ViewBidsDutchHighBidder(CHttpServerContext* pCtxt,
								 int item)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ViewBidsDutchHighBidder %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	
	MYTRY
	pApp->SetCurrentPage(PageViewBidsDutchHighBidder);

	pApp->ViewBidsDutchHighBidder((CEBayISAPIExtension *)this,
				   item);
	*pApp->mpStream << flush;

	MYCATCH("ViewBidsDutchHighBidder")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}

int CEBayISAPIExtension::GetBidderEmails(CHttpServerContext* pCtxt,
								 int item, int PageType)
{
	ISAPITRACE("0x%x %d GetBidderEmails %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item, PageType);

	if (PageType == PageViewBidDutchHighBidderEmails)
		return ViewBidDutchHighBidderEmails(pCtxt, item, "default", "default", 0);

	return ViewBidderWithEmails(pCtxt, item, "default", "default", 0);
}

int CEBayISAPIExtension::ViewBidDutchHighBidderEmails(CHttpServerContext* pCtxt,
								 int		Item,
								 LPCTSTR	pUserId,
								 LPCTSTR	pPass,
								 int acceptCookie)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ViewBidDutchHighBidderEmails %d %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), Item, pUserId, pPass);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	if ((acceptCookie == 1) && pApp->DropUserIdCookie((char *) pUserId, (char *) pPass, pCtxt))
	{
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUserValidation()->
			SetSoftValidation(true, pUserId);
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
	pApp->SetCurrentPage(PageViewBidDutchHighBidderEmails);

	pApp->ViewBidDutchHighBidderEmails((CEBayISAPIExtension *)this,
				   Item, (char *)pUserId, (char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("ViewBidDutchHighBidderEmails")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}


//
// Items
//
void CEBayISAPIExtension::ViewItem(CHttpServerContext* pCtxt,
								   LPCTSTR pItemNo, LPCTSTR pItemRow,
								   int timeStamp, LPCTSTR tc)
{
	// tc is a tracking code only. It is not passed on to 
	// clseBayApp or used in any way other than to show up in the logs.

	clseBayApp		*pApp;

	// Do this FIRST so ISAPITRACE doesn't bite it!
	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ViewItem %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItemNo, tc);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		*pApp->mpStream << flush;
		EndContent(pCtxt);
		return;
	}


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
/*		*pApp->mpStream <<"<H1>Heavy traffic. Please try again in 20 minutes</H1><BR>\n";
		pApp->CleanUp();
		*pApp->mpStream << flush;
*/
		return;  
    }


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ViewItem", pItemNo, true))
	{
		EndContent(pCtxt);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageViewItem);

	pApp->Run(this, (char *)pItemNo, (char *)pItemRow, (long) timeStamp, pCtxt);
	*pApp->mpStream << flush;

	MYCATCH("ViewItem")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

//
// AddToItem
//
void CEBayISAPIExtension::AddToItem(CHttpServerContext* pCtxt,
									LPCTSTR pUserId,
									LPCTSTR pPass,
									LPCTSTR pItemNo,
									LPCTSTR pAddition)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass) || 
		!AfxIsValidAddress(pItemNo, 1, false) || !AfxIsValidAddress(pAddition, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	char smallAddition[32];

	strncpy(smallAddition, pAddition, 31);
	smallAddition[31] = '\0';

	ISAPITRACE("0x%x %d AddToItem %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo, smallAddition);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	||
		!AfxIsValidAddress(pItemNo, 1, false)					||
		!AfxIsValidAddress(pAddition, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAddToItem);

	pApp->AddToItem(this,	(char *)pUserId,
							(char *)pPass,
							(char *)pItemNo,
							(char *)pAddition);
	*pApp->mpStream << flush;

	MYCATCH("AddToItem")

	EndContent(pCtxt);
}


//
// VerifyAddToItem
//
void CEBayISAPIExtension::VerifyAddToItem(CHttpServerContext* pCtxt,
										  LPCTSTR pUserId,
										  LPCTSTR pPass,
										  LPCTSTR pItemNo,
										  LPCTSTR pAddition)
{
	clseBayApp	*pApp;

	char smallAddition[32];


	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass) || 
		!AfxIsValidAddress(pItemNo,1, false) || !AfxIsValidAddress(pAddition, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	strncpy(smallAddition, pAddition, 31);
	smallAddition[31] = '\0';

	ISAPITRACE("0x%x %d VerifyAddToItem %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo, smallAddition);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	||
		!AfxIsValidAddress(pItemNo, 1, false)					||
		!AfxIsValidAddress(pAddition, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageVerifyAddToItem);

	pApp->VerifyAddToItem(this,	(char *)pUserId,
								(char *)pPass,
								(char *)pItemNo,
								(char *)pAddition);
	*pApp->mpStream << flush;

	MYCATCH("VerifyAddToItem")

	EndContent(pCtxt);
}

//
// VerifyStop
//
void CEBayISAPIExtension::VerifyStop(CHttpServerContext* pCtxt,
									 LPCTSTR pUserId,
									 LPCTSTR pPass,
									 LPCTSTR pItemNo)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass) || 
		!AfxIsValidAddress(pItemNo,1,false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d VerifyStop %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	 ||
		!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	pApp->InitISAPI((unsigned char *)pCtxt);

	
	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageVerifyStop);

	pApp->VerifyStop(this, (char *)pItemNo,
						   (char *)pUserId,
						   (char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("VerifyStop")

	EndContent(pCtxt);
}

//
// Stop
//
void CEBayISAPIExtension::Stop(CHttpServerContext* pCtxt,
							   LPCTSTR pItemNo,
							   LPCTSTR pUserId,
							   LPCTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId((char*)pUserId) || !ValidatePassword((char*)pPass) || 
		!AfxIsValidAddress(pItemNo,1,false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d Stop %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pItemNo, pUserId, pPass);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	 ||
		!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	
	MYTRY
	pApp->SetCurrentPage(PageStop);

	pApp->Stop(this, (char *)pItemNo,
					 (char *)pUserId,
					 (char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("Stop");

	EndContent(pCtxt);
}

//
// MakeFeatured
//
void CEBayISAPIExtension::MakeFeatured(CHttpServerContext* pCtxt,
									   LPCTSTR pUserId,
									   LPCTSTR pPass,
									   LPCTSTR pItemNo,
									   LPCTSTR pTypeSuper,
									   LPCTSTR pTypeFeature)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	 ||
		!AfxIsValidAddress(pItemNo, 1, false) ||
		!AfxIsValidAddress(pTypeSuper, 1, false)||
		!AfxIsValidAddress(pTypeFeature, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d MakeFeatured %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass)	 ||
		!AfxIsValidAddress(pItemNo, 1, false) ||
		!AfxIsValidAddress(pTypeSuper, 1, false)||
		!AfxIsValidAddress(pTypeFeature, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageMakeFeatured);

	pApp->MakeFeatured(this, (char *)pItemNo,
							 (char *)pUserId,
							 (char *)pPass,
							 (char *)pTypeSuper,
							 (char *)pTypeFeature);
	*pApp->mpStream << flush;

	MYCATCH("MakeFeatured")

	EndContent(pCtxt);
}

//
// MakeFeatured
//
void CEBayISAPIExtension::Featured(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d Featured\n",
				GetCurrentThreadId(), GetCurrentThreadId());


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageFeatured);

	pApp->Featured(this);
	*pApp->mpStream << flush;

	MYCATCH("Featured")

	EndContent(pCtxt);
}

//
// Enter New Item
//
void CEBayISAPIExtension::NewItem(CHttpServerContext* pCtxt,
										  LPCTSTR pItemNo,
										  LPCTSTR pCatNo)
{
	clseBayApp	*pApp;
	
	// nsacco 07/30/99 - old page, need to redirect to new one
	char		reDirectURL[512];

	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pCatNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}
	
	ISAPITRACE("0x%x %d NewItem %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pCatNo);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	

//	MYTRY
//	pApp->SetCurrentPage(PageNewItem);

	// nsacco 07/30/99 redirect to ListItemForSale
	strcpy(reDirectURL, pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCGIPath(PageListItemForSale));
	strcat(reDirectURL, "eBayISAPI.dll?ListItemForSale");
	if (strcmp(pItemNo, "0") != 0)
	{
		strcat(reDirectURL, "&item=");
		strcat(reDirectURL, pItemNo);
	}


	if (strcmp(pCatNo, "0") != 0)
	{
		strcat(reDirectURL, "&category=");
		strcat(reDirectURL, pCatNo);
	}

	EbayRedirect(pCtxt, reDirectURL);
	return;
	// end of redirect code

// nsacco 07/30/99 commented out unused code
//	MYTRY
//	pApp->SetCurrentPage(PageNewItem);
//
//	pApp->NewItem(this, (char *)pItemNo, (char *)pCatNo);
//	*pApp->mpStream << flush;
//
//	MYCATCH("NewItem")
//
//	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
}

//
// Enter New Item
// if item number is non-zero, seed the fields with
//  the item's properties
void CEBayISAPIExtension::NewItemQuick(CHttpServerContext* pCtxt,
										  LPCTSTR pItemNo,
										  LPCTSTR pCatNo)
{
	clseBayApp	*pApp;

	// nsacco 07/30/99 - old page, need to redirect to new one
	char		reDirectURL[512];

	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pCatNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d NewItemQuick %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pCatNo);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	// nsacco 07/30/99 redirect to ListItemForSale
	strcpy(reDirectURL, pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCGIPath(PageListItemForSale));
	strcat(reDirectURL, "eBayISAPI.dll?ListItemForSale");
	if (strcmp(pItemNo, "0") != 0)
	{
		strcat(reDirectURL, "&item=");
		strcat(reDirectURL, pItemNo);
	}

	if (strcmp(pCatNo, "0") != 0)
	{
		strcat(reDirectURL, "&category=");
		strcat(reDirectURL, pCatNo);
	}

	EbayRedirect(pCtxt, reDirectURL);
	return;
	// end of redirect code

// nsacco 07/30/99 commented out unused code
//
//	MYTRY
//	pApp->SetCurrentPage(PageNewItemQuick);
//
//	pApp->NewItemQuick(this, (char *)pItemNo, (char *)pCatNo);
//	*pApp->mpStream << flush;
//
//	MYCATCH("NewItemQuick")
//
//	EndContent(pCtxt);
//
	
//	PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

//
//
// Enter New Item
// if item number is non-zero, seed the fields with
//  the item's properties
void CEBayISAPIExtension::ListItemForSale(CHttpServerContext* pCtxt,
										  LPCTSTR pItemNo,
										  LPCTSTR pCatNo,
										  int oldStyle)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pCatNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ListItemForSale %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pCatNo);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		*pApp->mpStream << flush;
		EndContent(pCtxt);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageListItemForSale);

	pApp->ListItemForSale(this, (char *)pItemNo, (char *)pCatNo, (oldStyle==1));
	*pApp->mpStream << flush;

	MYCATCH("ListItemForSale")

	EndContent(pCtxt);
	
//	PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

//
// To be a better seller
// if item number is non-zero, seed the fields with
//  the item's properties
void CEBayISAPIExtension::BetterSeller(CHttpServerContext* pCtxt,
										  int ItemNo)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d BetterSeller %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				ItemNo);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageBetterSeller);

	pApp->BetterSeller(this, ItemNo);
	*pApp->mpStream << flush;

	MYCATCH("BetterSeller")

	EndContent(pCtxt);
}



// nsacco 07/27/99 added new params
void CEBayISAPIExtension::VerifyNewItem(CHttpServerContext* pCtxt, 
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
					  LPSTR   pAccept,
					  LPSTR   pDecline,
					  int	  notify,
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
					  int	  siteId,
					  int	  descLang,
					  LPCTSTR pGiftIcon,
					  int	  gallery,
					  LPCTSTR pGalleryUrl,
					  int	  countryId,
					  int     currencyId,
					  LPCTSTR pZip,
					  LPCTSTR pCatMenu_0,		// dummy
					  LPCTSTR pCatMenu_1,		// dummy
					  LPCTSTR pCatMenu_2,		// dummy
					  LPCTSTR pCatMenu_3		// dummy
					  )

{
	clseBayApp	*pApp;
	UAChoice	uaChoice;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					|| 
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pTitle, 1, false)				||
		!AfxIsValidAddress(pLocation, 1, false)				||
		!AfxIsValidAddress(pReserve, 1, false)				||
		!AfxIsValidAddress(pStartPrice, 1, false)			||
		!AfxIsValidAddress(pQuantity, 1, false)				||
		!AfxIsValidAddress(pDuration, 1, false)				||
		!AfxIsValidAddress(pBold, 1, false)					||
		!AfxIsValidAddress(pFeatured, 1, false)				||
		!AfxIsValidAddress(pSuperFeatured, 1, false)		||
		!AfxIsValidAddress(pPrivate, 1, false)				||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pPicUrl, 1, false)				||
		!AfxIsValidAddress(pCategory1, 1, false)			||
		!AfxIsValidAddress(pCategory2, 1, false)			||
		!AfxIsValidAddress(pCategory3, 1, false)			||
		!AfxIsValidAddress(pCategory4, 1, false)			||
		!AfxIsValidAddress(pCategory5, 1, false)			||
		!AfxIsValidAddress(pCategory6, 1, false)			||
		!AfxIsValidAddress(pCategory7, 1, false)			||
		!AfxIsValidAddress(pCategory8, 1, false)			||
		!AfxIsValidAddress(pCategory9, 1, false)			||
		!AfxIsValidAddress(pCategory10, 1, false)			||
		!AfxIsValidAddress(pCategory11, 1, false)			||
		!AfxIsValidAddress(pCategory12, 1, false)			||
		!AfxIsValidAddress(pOldItemNo, 1, false)			||
		!AfxIsValidAddress(pOldKey, 1, false)				||
		!AfxIsValidAddress(pAccept, 1, false)				||
		!AfxIsValidAddress(pDecline, 1, false)				||
		!AfxIsValidAddress(pMoneyOrderAccepted, 1, false)	||
		!AfxIsValidAddress(pPersonalChecksAccepted, 1, false) ||
		!AfxIsValidAddress(pVisaMasterCardAccepted, 1, false)	||
		!AfxIsValidAddress(pDiscoverAccepted, 1, false)	||
		!AfxIsValidAddress(pAmExAccepted, 1, false)		||
		!AfxIsValidAddress(pOtherAccepted, 1, false)		||
		!AfxIsValidAddress(pOnlineEscrowAccepted, 1, false)		||
		!AfxIsValidAddress(pCODAccepted, 1, false)	||
		!AfxIsValidAddress(pPaymentSeeDescription, 1, false)	||
		!AfxIsValidAddress(pSellerPaysShipping, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingFixed, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingActual, 1, false)||
		!AfxIsValidAddress(pShippingSeeDescription, 1, false) ||
		!AfxIsValidAddress(pShippingInternationally, 1, false) ||
		// nsacco 07/27/99 validate new params
		!AfxIsValidAddress(pShipToNorthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToEurope, 1, false) ||
		!AfxIsValidAddress(pShipToOceania, 1, false) ||
		!AfxIsValidAddress(pShipToAsia, 1, false) ||
		!AfxIsValidAddress(pShipToSouthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToAfrica, 1, false) ||
		// end validate new params
		!AfxIsValidAddress(pGiftIcon, 1, false)				||
		!AfxIsValidAddress(pGalleryUrl, 1, false)			||
		!AfxIsValidAddress(pZip, 1, false)
		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	int acceptButton = strcmp(pAccept, "default");
	int declineButton = strcmp(pDecline, "default");

	ISAPITRACE("0x%x %d VerifyNewItem %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPassword);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

//	char tbuf[128];
//	sprintf(tbuf, "Verify: %20.20s %20.20s %20.20s", 
//		pUserId, pTitle, pDesc);
//	pApp->LogEvent(tbuf);


	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


		// Did the user click a user agreement button? 
	// If so, which one? And did the user click the
	// checkbox for being notified if the user
	// agreement ever changes? So many questions,
	// so little time...

	if (acceptButton == 0 && declineButton == 0)
		uaChoice = UAShowAgreement;
	else if (declineButton != 0)
		uaChoice = UADeclined;
	else if (notify == 1)
		uaChoice = UAAcceptedWithNotify;
	else
		uaChoice = UAAcceptedWithoutNotify;

	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "VerifyNewItem", pCategory1, false))
	{
		EndContent(pCtxt);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageVerifyNewItem);

	// nsacco 07/27/99 added new params
	pApp->VerifyNewItem(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pTitle,
						  (char *)pLocation,
						  (char *)pReserve,
						  (char *)pStartPrice,
						  (char *)pQuantity,
						  (char *)pDuration,
						  (char *)pBold,
						  (char *)pFeatured,
						  (char *)pSuperFeatured,
						  (char *)pPrivate,
						  (char *)pDesc,
						  (char *)pPicUrl,
						  (char *)pCategory1,
						  (char *)pCategory2,
						  (char *)pCategory3,
						  (char *)pCategory4,
						  (char *)pCategory5,
						  (char *)pCategory6,
						  (char *)pCategory7,
						  (char *)pCategory8,
						  (char *)pCategory9,
						  (char *)pCategory10,
						  (char *)pCategory11,
						  (char *)pCategory12,
						  (char *)pOldItemNo,
						  (char *)pOldKey,
						          uaChoice,
						  (char *)pMoneyOrderAccepted,
						  (char *)pPersonalChecksAccepted,
					      (char *)pVisaMasterCardAccepted,
					      (char *)pDiscoverAccepted,
					      (char *)pAmExAccepted,
					      (char *)pOtherAccepted,
					      (char *)pOnlineEscrowAccepted,
					      (char *)pCODAccepted,
					      (char *)pPaymentSeeDescription,
					      (char *)pSellerPaysShipping,
					      (char *)pBuyerPaysShippingFixed,
					      (char *)pBuyerPaysShippingActual,
					      (char *)pShippingSeeDescription,
					      (char *)pShippingInternationally,
						  (char *)pShipToNorthAmerica,
						  (char *)pShipToEurope,
						  (char *)pShipToOceania,
						  (char *)pShipToAsia,
						  (char *)pShipToSouthAmerica,
						  (char *)pShipToAfrica,
						  siteId,
						  descLang,
						  pCtxt,
					      (char *)pGiftIcon,
						  gallery,
						  (char *)pGalleryUrl,
						  countryId,
						  currencyId,
						  (char *)pZip
							);
	*pApp->mpStream << flush;

	MYCATCH("VerifyNewItem")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

// nsacco 07/27/99 added new params
void CEBayISAPIExtension::AddNewItem(CHttpServerContext* pCtxt, 
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
					  int	  siteId,
					  int	  descLang,
					  LPCTSTR pGiftIcon,
					  int	  gallery,
					  LPCTSTR pGalleryUrl,
					  int	  countryId,
					  int	  currencyId,
					  LPCTSTR pZip
					  )

{
	clseBayApp	*pApp;

	// Sanity Checks
	// nsacco 07/27/99 added checks for new params
	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pTitle, 1, false)				||
		!AfxIsValidAddress(pLocation, 1, false)				||
		!AfxIsValidAddress(pReserve, 1, false)				||
		!AfxIsValidAddress(pStartPrice, 1, false)			||
		!AfxIsValidAddress(pQuantity, 1, false)				||
		!AfxIsValidAddress(pDuration, 1, false)				||
		!AfxIsValidAddress(pBold, 1, false)					||
		!AfxIsValidAddress(pFeatured, 1, false)				||
		!AfxIsValidAddress(pSuperFeatured, 1, false)		||		
		!AfxIsValidAddress(pPrivate, 1, false)				||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pPicUrl, 1, false)				||
		!AfxIsValidAddress(pCategory, 1, false)				||
		!AfxIsValidAddress(pMoneyOrderAccepted, 1, false)	||
		!AfxIsValidAddress(pPersonalChecksAccepted, 1, false) ||
		!AfxIsValidAddress(pVisaMasterCardAccepted, 1, false)	||
		!AfxIsValidAddress(pDiscoverAccepted, 1, false)	||
		!AfxIsValidAddress(pAmExAccepted, 1, false)		||
		!AfxIsValidAddress(pOtherAccepted, 1, false)		||
		!AfxIsValidAddress(pOnlineEscrowAccepted, 1, false)		||
		!AfxIsValidAddress(pCODAccepted, 1, false)		||
		!AfxIsValidAddress(pPaymentSeeDescription, 1, false)	||
		!AfxIsValidAddress(pSellerPaysShipping, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingFixed, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingActual, 1, false)||
		!AfxIsValidAddress(pShippingSeeDescription, 1, false),
		!AfxIsValidAddress(pShippingInternationally, 1, false) ||
		!AfxIsValidAddress(pShipToNorthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToEurope, 1, false) ||
		!AfxIsValidAddress(pShipToOceania, 1, false) ||
		!AfxIsValidAddress(pShipToAsia, 1, false) ||
		!AfxIsValidAddress(pShipToSouthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToAfrica, 1, false) ||
		!AfxIsValidAddress(pGiftIcon, 1, false)					||
		!AfxIsValidAddress(pGalleryUrl, 1, false)				||
		!AfxIsValidAddress(pZip, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AddNewItem %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItemNo);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


//	char tbuf[128];
//	sprintf(tbuf, "AddNewItem: %20.20s %20.20s %20.20s", 
//		pUserId, pTitle, pDesc);
//	pApp->LogEvent(tbuf);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "AddNewItem", pUserId, true))
	{
		EndContent(pCtxt);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageAddNewItem);

	// nsacco 07/27/99 added new params
	pApp->AddNewItem(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pItemNo,
						  (char *)pTitle,
						  (char *)pReserve,
						  (char *)pStartPrice,
						  (char *)pQuantity,
						  (char *)pDuration,
						  (char *)pLocation,
						  (char *)pBold,
						  (char *)pFeatured,
						  (char *)pSuperFeatured,
						  (char *)pPrivate,
						  (char *)pDesc,
						  (char *)pPicUrl,
						  (char *)pCategory,
						  (char *)pKey,
						  (char *)pOldItemNo,
						  (char *)pOldKey,
						  (char *)pMoneyOrderAccepted,
						  (char *)pPersonalChecksAccepted,
						  (char *)pVisaMasterCardAccepted,
						  (char *)pDiscoverAccepted,
						  (char *)pAmExAccepted,
						  (char *)pOtherAccepted,
						  (char *)pOnlineEscrowAccepted,
						  (char *)pCODAccepted,
						  (char *)pPaymentSeeDescription,
						  (char *)pSellerPaysShipping,
						  (char *)pBuyerPaysShippingFixed,
						  (char *)pBuyerPaysShippingActual,
						  (char *)pShippingSeeDescription,
						  (char *)pShippingInternationally,
						  (char *)pShipToNorthAmerica,
						  (char *)pShipToEurope,
						  (char *)pShipToOceania,
						  (char *)pShipToAsia,
						  (char *)pShipToSouthAmerica,
						  (char *)pShipToAfrica,
						  siteId,
						  descLang,
						  pCtxt,
						  (char *)pGiftIcon,
						  gallery,
						  (char *)pGalleryUrl,
						  countryId,
						  currencyId,
						  (char*) pZip
							);
	*pApp->mpStream << flush;

	MYCATCH("AddNewItem")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
}

//
// ChangeCategoryShow
//
void CEBayISAPIExtension::ChangeCategoryShow(CHttpServerContext* pCtxt,
											 int item, bool oldStyle)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ChangeCategoryShow %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	
	MYTRY
	pApp->SetCurrentPage(PageChangeCategoryShow);

	pApp->ChangeCategoryShow(this, item, (oldStyle==1));
	*pApp->mpStream << flush;

	MYCATCH("ChangeCategoryShow")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
}

//
// MakeFeatured
//
void CEBayISAPIExtension::ChangeCategory(CHttpServerContext* pCtxt,
										LPCTSTR pUserId,
										LPCTSTR pPass,
										int item,
										int	newCategory,
										LPCTSTR pCatMenu_0,	// dummy
										LPCTSTR pCatMenu_1,	// dummy
										LPCTSTR pCatMenu_2,	// dummy
										LPCTSTR pCatMenu_3)	// dummy
{
	clseBayApp	*pApp;


	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}
	
	ISAPITRACE("0x%x %d ChangeCategory %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, item, newCategory);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);



	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	
	MYTRY
	pApp->SetCurrentPage(PageChangeCategory);

	pApp->ChangeCategory(this, 
						  (char *)pUserId,
						  (char *)pPass,
						  item,
						  newCategory);

	*pApp->mpStream << flush;

	MYCATCH("ChangeCategory")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
}

void CEBayISAPIExtension::RecomputeDutchBids(CHttpServerContext* pCtxt,
								   LPCTSTR pItem)
{
	clseBayApp		*pApp;

	if (!AfxIsValidAddress(pItem, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d RecomputeDutchBids %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItem);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageRecomputeDutchBids);

	pApp->RecomputeDutchBids((CEBayISAPIExtension *)this, (char *)pItem);
	*pApp->mpStream << flush;

	MYCATCH("RecomputeDutchBids")

	// ISAPITRACE((LPCTSTR)"clsApp @ 0x%x\n", pApp);

	//_CrtMemCheckpoint(&postMemState);
	//_CrtMemDifference(&diffState, &preMemState, &postMemState);
	//_CrtMemDumpStatistics(&diffState);
	//_CrtMemDumpStatistics(&postMemState);
	//_CrtMemDumpAllObjectsSince(&preMemState);

	//_CrtDumpMemoryLeaks();

	EndContent(pCtxt);
	return;
}

void CEBayISAPIExtension::RecomputeChineseBids(CHttpServerContext* pCtxt,
								   LPCTSTR pItem)
{
	clseBayApp		*pApp;


	if (!AfxIsValidAddress(pItem, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}
	
	ISAPITRACE("0x%x %d RecomputeChineseBids %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItem);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageRecomputeChineseBids);

	pApp->RecomputeChineseBids((CEBayISAPIExtension *)this, (char *)pItem);
	*pApp->mpStream << flush;

	MYCATCH("RecomputeChineseBids")

	EndContent(pCtxt);
	return;
}


//
// Users
//
int CEBayISAPIExtension::ViewListedItems(CHttpServerContext* pCtxt,
										  LPTSTR pUser,
										  int complete,
										  int sort,
										  int daysSince,
     									  int include,
     									  int startingPage,
     									  int rowsPerPage)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	
	ISAPITRACE("0x%x %d ViewListedItems %s %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, complete, sort, daysSince, include);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewListedItems);

		pApp->ViewListedItems((CEBayISAPIExtension *)this, 
							  (char *)pUser,
							  complete == 0 ? false : true,
							  (ItemListSortEnum)sort,
							  daysSince,
     							  include == 0 ? false : true,
     							  startingPage,
     							  rowsPerPage);
	*pApp->mpStream << flush;

	MYCATCH("ViewListedItems")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}


int CEBayISAPIExtension::ViewListedItemsWithEmails(CHttpServerContext* pCtxt,
										  LPTSTR pRequester,
										  LPTSTR pPass,
										  LPTSTR pUser,
										  int complete,
										  int sort,
     									  int daysSince,
										  int acceptCookie,
     									  int startingPage,
     									  int rowsPerPage)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser) || !ValidateUserId(pRequester) || !ValidatePassword(pPass))
	{
		StartContent(pCtxt);
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	
	ISAPITRACE("0x%x %d ViewListedItemsWithEmails %s %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, complete, sort, daysSince);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	if ((acceptCookie == 1) && pApp->DropUserIdCookie((char *) pRequester, (char *) pPass, pCtxt))
	{
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetUsers()->GetUserValidation()->
			SetSoftValidation(true, pRequester);
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PageViewListedItems);

		pApp->ViewListedItemsWithEmails((CEBayISAPIExtension *)this,
							  (char *)pRequester,
							  (char *)pPass,
							  (char *)pUser,
							  (complete ? true : false),
							  (ItemListSortEnum)sort,
     						  daysSince,
     						  startingPage,
     						  rowsPerPage);
	*pApp->mpStream << flush;

	MYCATCH("ViewListedItemsWithEmails")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::ViewListedItemsLinkButtons(CHttpServerContext* pCtxt,
													LPTSTR pUser,
													int complete,
													int sort,
													int daysSince,
													int include,
													int startingPage,
													int rowsPerPage)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	
	ISAPITRACE("0x%x %d ViewListedItemsLinkButtons %s %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, complete, sort, daysSince, include, startingPage, rowsPerPage);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewListedItems);
	 
		// call the real thing
		pApp->ViewListedItems((CEBayISAPIExtension *)this, 
							  (char *)pUser,
							  complete == 0 ? false : true,
							  (ItemListSortEnum)sort,
							  daysSince,
							  include == 0 ? false : true,
     							  startingPage,
     							  rowsPerPage);
	*pApp->mpStream << flush;

	MYCATCH("ViewListedItemsLinkButtons")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::ViewBidItems(CHttpServerContext* pCtxt,
									  LPTSTR pUser,
									  int complete,
									  int sort,
     									  int allItems,
     									  int startingPage,
     									  int rowsPerPage)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	
	ISAPITRACE("0x%x %d ViewBidItems %s %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
     				pUser, complete, sort, allItems, startingPage, rowsPerPage);



	StartContent(pCtxt);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewBidItems);

		pApp->ViewBidItems((CEBayISAPIExtension *)this, 
						   (char *)pUser,
						   (complete ? true : false),
						   (ItemListSortEnum)sort,
     						   (allItems ? true : false), 
     						   startingPage, 
     						   rowsPerPage);
	*pApp->mpStream << flush;

	MYCATCH("ViewBidItems")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::ViewAllItems(CHttpServerContext* pCtxt,
									  LPTSTR pUser,
									  int complete,
									  int sort,
									  int daysSince)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ViewAllItems %s %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, complete, sort, daysSince);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)CreateeBayApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewAllItems);

		pApp->ViewAllItems((CEBayISAPIExtension *)this, 
						   (char *)pUser,
						   (complete ? true : false),
						   (ItemListSortEnum)sort,
						   daysSince);
	*pApp->mpStream << flush;

	MYCATCH("ViewAllItems")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::ViewFeedback(CHttpServerContext* pCtxt,
									  LPTSTR pUser,
									  int startingPage,
									  int itemsPerPage)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	ISAPITRACE("0x%x %d ViewFeedback %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, startingPage, itemsPerPage);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		*pApp->mpStream << flush;
		EndContent(pCtxt);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewFeedback);

		pApp->ViewFeedback((CEBayISAPIExtension *)this, 
						   (char *)pUser, startingPage, itemsPerPage);
		*pApp->mpStream << flush;


	MYCATCH("ViewFeedback")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}

int CEBayISAPIExtension::ViewFeedbackLeft(CHttpServerContext* pCtxt,
										  LPTSTR pUser,
										  LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ViewFeedbackLeft %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PageViewFeedbackLeft);

		pApp->ViewFeedbackLeft((CEBayISAPIExtension *)this, 
							   (char *)pUser, (char *)pPass);
		*pApp->mpStream << flush;

	MYCATCH("ViewFeedbackLeft")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::PersonalizedFeedbackLogin(CHttpServerContext* pCtxt,
												   LPTSTR pUser,
												   int itemsPerPage)

{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d PersonalizedFeedbackLogin %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, itemsPerPage);

	StartContent(pCtxt);

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}



	MYTRY
		pApp->SetCurrentPage(PagePersonalizedFeedbackLogin);

		pApp->PersonalizedFeedbackLogin((CEBayISAPIExtension *)this, 
								   (char *)pUser, itemsPerPage);
		*pApp->mpStream << flush;


	MYCATCH("PersonalizedFeedbackLogin")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}

int CEBayISAPIExtension::ViewPersonalizedFeedback(CHttpServerContext* pCtxt,
												 LPTSTR pUser,
												 LPTSTR pPass,
												 int startingPage,
												 int itemsPerPage)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ViewPersonalizedFeedback %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, startingPage, itemsPerPage);

	StartContent(pCtxt);

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageViewPersonalizedFeedback);

		pApp->ViewPersonalizedFeedback((CEBayISAPIExtension *)this, 
								   (char *)pUser, (char *)pPass, 
								   startingPage, itemsPerPage);
		*pApp->mpStream << flush;


	MYCATCH("ViewPersonalizedFeedback")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;
}

int CEBayISAPIExtension::LeaveFeedbackShow(CHttpServerContext* pCtxt,
									   LPTSTR pUserTo,
									   LPTSTR pUserFrom,
									   int itemNo)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d LeaveFeedbackShow %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserTo, pUserFrom, itemNo);

	StartContent(pCtxt);

	// Sanity
	if (!ValidateUserId(pUserTo) || !ValidateUserId(pUserFrom))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	
	MYTRY
		pApp->SetCurrentPage(PageLeaveFeedbackShow);

		pApp->LeaveFeedbackShow((CEBayISAPIExtension *)this, 
						   (char *)pUserTo,
						   (char *)pUserFrom,
						   itemNo);
		*pApp->mpStream << flush;

	MYCATCH("LeaveFeedbackShow")
	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::LeaveFeedback(CHttpServerContext* pCtxt,
									   LPTSTR pUser,
									   LPTSTR pPass,
									   LPTSTR pForUser,
									   LPTSTR pItemNo,
									   LPTSTR pType,
									   LPTSTR pComment,
									   int confirmNegative)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || 
		!ValidatePassword(pPass) || 
		!ValidateUserId(pForUser) || 
		!AfxIsValidAddress(pItemNo, 1, false) ||
		!AfxIsValidAddress(pType, 1, false) ||
		!AfxIsValidAddress(pComment, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}


	ISAPITRACE("0x%x %d LeaveFeedback %s %s %s %s %s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, pForUser, pItemNo, pType, pComment,
				confirmNegative);

	char		hostAddr[256];
	DWORD		hostAddrSize	= sizeof(hostAddr);

	// First, let's get the host name, since we'll need
	// it
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_ADDR",
										&hostAddr,
										&hostAddrSize);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageLeaveFeedback);

		pApp->LeaveFeedback((CEBayISAPIExtension *)this, 
						   (char *)pUser,
						   (char *)pPass,
						   (char *)pForUser,
						   (char *)pItemNo,
						   (char *)pType,
						   (char *)pComment,
						   (char *)hostAddr,
						   confirmNegative);
		*pApp->mpStream << flush;

	MYCATCH("LeaveFeedback")
	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::RespondFeedbackShow(CHttpServerContext *pCtxt,
											 int commentor,
											 int commentDate,
											 int commentee,
											 int startingPage,
											 int itemsPerPage)
{
	clseBayApp	*pApp;


	ISAPITRACE("0x%x %d RespondFeedbackShow %d %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				commentor, commentDate, commentee, startingPage, itemsPerPage);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	
	MYTRY
		pApp->SetCurrentPage(PageRespondFeedbackShow);

		pApp->RespondFeedbackShow((CEBayISAPIExtension *)this, 
								  commentor,
								  commentDate,
								  commentee,
								  startingPage,
								  itemsPerPage);
		*pApp->mpStream << flush;

	MYCATCH("RespondFeedbackShow")
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::RespondFeedback(CHttpServerContext *pCtxt,
										 int commentorId,
										 time_t commentDate,
										 LPTSTR pCommentee,
										 LPTSTR pPass,
										 LPTSTR pResponse,
										 int startingPage,
										 int itemsPerPage)
{
	clseBayApp	*pApp;





	// Sanity
	if (!ValidateUserId(pCommentee) || 
		!ValidatePassword(pPass) || 
		!AfxIsValidAddress(pResponse, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RespondFeedback %d %d %.20s %.20s %.20s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				commentorId, commentDate, pCommentee, pPass, 
				pResponse, startingPage, itemsPerPage);
	
	StartContent(pCtxt);
	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageRespondFeedback);

		pApp->RespondFeedback((CEBayISAPIExtension *)this, 
							  commentorId,
							  commentDate,
							  (char*) pCommentee,
							  (char*) pPass,
							  (char*) pResponse,
							  startingPage,
							  itemsPerPage);
		*pApp->mpStream << flush;

	MYCATCH("RespondFeedback")
	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::FollowUpFeedbackShow(CHttpServerContext *pCtxt,
							 int Commentor,
							 int CommentDate,
							 int Commentee)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d FollowUpFeedbackShow %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				Commentor, CommentDate, Commentee);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageFollowUpFeedbackShow);

		pApp->FollowUpFeedbackShow((CEBayISAPIExtension *)this, 
								   Commentor,
								   CommentDate,
								   Commentee);
		*pApp->mpStream << flush;

	MYCATCH("FollowUpFeedbackShow")
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::FollowUpFeedback(CHttpServerContext *pCtxt,
						 int CommentorId,
						 time_t CommentDate,
						 LPTSTR pCommentee,
						 LPTSTR pPass,
						 LPTSTR pFollowUp)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pCommentee) || 
		!ValidatePassword(pPass) || 
		!AfxIsValidAddress(pFollowUp, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d FollowUpFeedback %d %d %s %s %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				CommentorId, CommentDate, pCommentee, pPass, pFollowUp);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageFollowUpFeedback);

		pApp->FollowUpFeedback((CEBayISAPIExtension *)this, 
							CommentorId,
							CommentDate,
							(char*) pCommentee,
							(char*) pPass,
							(char*) pFollowUp);
		*pApp->mpStream << flush;

	MYCATCH("FollowUpFeedback")
	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::ChangeFeedbackOptions(CHttpServerContext* pCtxt,
											   LPTSTR pUser,
											   LPTSTR pPass,
											   LPTSTR pOption,
											   int startingPage,
											   int itemsPerPage)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || 
		!ValidatePassword(pPass) || 
		!AfxIsValidAddress(pOption, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}


	ISAPITRACE("0x%x %d ChangeFeedbackOptions %s %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, pOption, startingPage, itemsPerPage);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeFeedbackOptions);

		pApp->ChangeFeedbackOptions((CEBayISAPIExtension *)this, 
									(char *)pUser,
									(char *)pPass,
									(char *)pOption,
									startingPage,
									itemsPerPage);
		*pApp->mpStream << flush;

	MYCATCH("ChangeFeedbackOptions")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::GetFeedbackScore(CHttpServerContext* pCtxt,
										  LPTSTR pUser)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	try
	{
		pApp->SetCurrentPage(PageGetFeedbackScore);

		pApp->GetFeedbackScore((CEBayISAPIExtension *)this, 
							   (char *)pUser);
	}											
	catch(eBayOracleException &e)				
	{
		LogQueryString("GetFeedbackScore");
		LogOracleException("GetFeedbackScore");				
		*pCtxt <<	"(Click for Feedback)";		
		try										
		{										
			delete	pApp;
			pApp = NULL;
		}										
		catch(...)								
		{										
			;									
		}										
		pApp	= NULL;
		CLEARPOINTER;
	}											
	catch (/* unsigned */ int &e)						
	{
		LogQueryString("GetFeedbackScore");
		LogCException("GetFeedbackScore");						
		*pCtxt <<	"(Click for Feedback)";		
		try										
		{										
			delete pApp;
			pApp = NULL;
		}										
		catch (...)								
		{										
			;									
		}										
		pApp	= NULL;
		CLEARPOINTER;
	}											
	catch(CException &e)						
	{
		LogQueryString("GetFeedbackScore");
		LogMFCException("GetFeedbackScore");					
		*pCtxt <<	"(Click for Feedback)";		
		try										
		{										
			delete	pApp;
			pApp = NULL;
		}										
		catch(...)								
		{										
			;									
		}										
		pApp	= NULL;
		CLEARPOINTER;
	}
	catch(eBayStructuredException &e)			
	{											
		LogQueryString("GetFeedbackScore");					
		LogStructuredException("GetFeedbackScore");			
		*pCtxt <<	"(Click for Feedback)";		
		try										
		{										
			delete	pApp;						
		}										
		catch(...)								
		{										
			;									
		}										
		pApp	= NULL;							
		CLEARPOINTER							
	}											
	catch(...)									
	{				
		LogQueryString("GetFeedbackScore");
		LogException("GetFeedbackScore");						
		*pCtxt <<	"(Click for Feedback)";		
		try										
		{										
			delete	pApp;
			pApp = NULL;
		}										
		catch(...)								
		{										
			;									
		}										
		pApp	= NULL;
		CLEARPOINTER;
	}								

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::FeedbackForum(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d FeedbackForum",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageFeedbackForum);

		pApp->FeedbackForum((CEBayISAPIExtension *)this);
		*pApp->mpStream << flush;

	MYCATCH("FeedbackForum")

	EndContent(pCtxt);

	return callOK;
}

BOOL CEBayISAPIExtension::OnParseError(CHttpServerContext* pCtxt, int nCause)
{
	clseBayApp	*pApp;

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return true;
	}


	pApp->SetCurrentPage(PageParseError);

	pApp->ParseError(nCause);
	*pApp->mpStream << flush;
	EndContent(pCtxt);

	return true;
}

int CEBayISAPIExtension::AddToBoard(CHttpServerContext* pCtxt,
									LPTSTR pBoardName,
									LPTSTR pUser,
									LPTSTR pPass,
									LPTSTR pInfo,
									LPTSTR pLimit,
									int	   FromEssayBoard)
{
	clseBayApp		*pApp;
	bool			ok;
	char			reDirectURL[512];
	unsigned long	reDirectURLLen;


	// Sanity
	if (!ValidateUserId(pUser)								||
		!ValidatePassword(pPass)							||
		!AfxIsValidAddress(pBoardName, 1, false)			||
		!AfxIsValidAddress(pInfo, 1, false)					||
		!AfxIsValidAddress(pLimit, 1, false)
	   )
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	
	ISAPITRACE("0x%x %d AddToBoard %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pBoardName, pUser, pPass);




	// Assume we failed in case we get an exception
	ok	= false;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageAddToBoard);

		ok = pApp->AddToBoard((CEBayISAPIExtension *)this, 
							  (char *)pUser,
							  (char *)pPass,
							  (char *)pInfo,
							  (char *)pBoardName,
							  (char *)pLimit,
							  reDirectURL,
							  FromEssayBoard==1);
		*pApp->mpStream << flush;

	MYCATCH("AddToBoard")

	if (ok)
	{
		EbayRedirect(pCtxt, reDirectURL);
		reDirectURLLen	= strlen(reDirectURL);
/*		pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP,
									 reDirectURL,
									 &reDirectURLLen,
									 (DWORD *)NULL); */
	}
	else
		EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::RecomputeScore(CHttpServerContext* pCtxt,
										LPTSTR pUser)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RecomputeScore %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRecomputeScore);

		pApp->RecomputeScore((CEBayISAPIExtension *)this, 
							   (char *)pUser);
		*pApp->mpStream << flush;

	MYCATCH("RecomputeScore")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::ViewBoard(CHttpServerContext* pCtxt,
								   LPTSTR pBoardName,
								   LPTSTR pTimeLimit)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pBoardName, 1, false) ||
		!AfxIsValidAddress(pTimeLimit, 1, false)	)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	ISAPITRACE("0x%x %d ViewBoard %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pBoardName, pTimeLimit);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		*pApp->mpStream << flush;
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewBoard);

		pApp->ViewBoard((CEBayISAPIExtension *)this, 
						(char *)pBoardName,
						(char *)pTimeLimit);
		*pApp->mpStream << flush;

	MYCATCH("ViewBoard")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::ViewEssay(CHttpServerContext* pCtxt,
								   LPTSTR pBoardName)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ViewEssay %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pBoardName);

	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pBoardName, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewEssay);

		pApp->ViewEssay((CEBayISAPIExtension *)this, 
						(char *)pBoardName);
		*pApp->mpStream << flush;

	MYCATCH("ViewEssay")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

int CEBayISAPIExtension::PastEssay(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d PastEssay\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PagePastEssay);

		pApp->PastEssay((CEBayISAPIExtension *)this);
		*pApp->mpStream << flush;

	MYCATCH("PastEssay")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::RegisterByCountry(CHttpServerContext* pCtxt,
								           int countryId,
										   int UsingSSL)

{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d %d RegisterByCountry\n",
				GetCurrentThreadId(), GetCurrentThreadId(), countryId);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRegisterByCountry);

		pApp->RegisterByCountry((CEBayISAPIExtension *)this, pCtxt, countryId, UsingSSL);

		*pApp->mpStream << flush;

	MYCATCH("RegisterByCountry")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ConfirmByCountry(CHttpServerContext* pCtxt,
								          int countryId,
										  int withCC)

{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d %d ConfirmByCountry\n",
				GetCurrentThreadId(), GetCurrentThreadId(), countryId);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageConfirmByCountry);

		pApp->ConfirmByCountry((CEBayISAPIExtension *)this, pCtxt, countryId, withCC);

		*pApp->mpStream << flush;

	MYCATCH("ConfirmByCountry")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ShowRegistrationForm(CHttpServerContext* pCtxt,
								           int countryId,
										   int UsingSSL)

{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d %d RegisterByCountry\n",
				GetCurrentThreadId(), GetCurrentThreadId(), countryId);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageShowRegistrationForm);

		pApp->ShowRegistrationForm((CEBayISAPIExtension *)this, countryId, UsingSSL);

		*pApp->mpStream << flush;

	MYCATCH("ShowRegistrationForm")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ResendConfirmationEmail(CHttpServerContext *pCtxt,
												 LPTSTR pEmail)
{
	clseBayApp	*pApp;
	
	// Sanity
	if (!AfxIsValidAddress(pEmail, 1, false) )
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	ISAPITRACE("0x%x %d ResendConfirmationEmail\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageResendConfirmationEmail);

		pApp->ResendConfirmationEmail((CEBayISAPIExtension *)this, 
					   (char *) pEmail);
		

		*pApp->mpStream << flush;

	MYCATCH("ResendConfirmationEmail")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;

}

int CEBayISAPIExtension::Register(CHttpServerContext *pCtxt,
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
								  int siteId,		// nsacco 07/02/99
								  int coPartnerId
								  )
{
	clseBayApp	*pApp;
	char *pStr;
	char cookieBuffer[4096];
	unsigned long cookieLength;
	int partnerId;

	// Sanity
	if (!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pEmail,		1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pCompany,	1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pAddress,	1,	false)	||
		!AfxIsValidAddress(pCity,		1,	false)	||
		!AfxIsValidAddress(pState,		1,	false)	||
		!AfxIsValidAddress(pZip,		1,	false)	||
		!AfxIsValidAddress(pCountry,	1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	1,	false)	||
		!AfxIsValidAddress(pGender,		1,	false)	||
		!AfxIsValidAddress(pTradeshow_source1,			1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,			1,	false)	||
		!AfxIsValidAddress(pFriend_email,				1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d Register %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	cookieLength = 4095;
		if (pCtxt->GetServerVariable("HTTP_COOKIE", cookieBuffer, &cookieLength))
		{
			// Already set. Return.
			pStr = strstr(cookieBuffer, "p=");
			if (pStr && ((pStr == cookieBuffer) || isspace(*(pStr - 1))))
			{
				partnerId = atoi(pStr + 2);
			}
			else
			{
				partnerId = 0;
			}
		}
		else
		{
			partnerId = 0;
		}
		pApp->SetCurrentPage(PageRegister);

		pApp->Register((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,   
					   referral,                    /* Q1  */
					   (char *) pTradeshow_source1, /* Q17 */
					   (char *) pTradeshow_source2, /* Q18 */
					   (char *) pTradeshow_source3, /* Q19 */
					   (char *) pFriend_email,      /* Q20 */
					   purpose,                     /* Q7  */
					   interested_in,               /* Q14 */
					   age,                         /* Q3  */
					   education,                   /* Q4  */
					   income,                      /* Q5  */
					   survey,                      /* Q16 */
					   withcc,
					   partnerId,
					   siteId,		// nsacco 07/02/99
					   coPartnerId,
					   UsingSSL
					   );
		*pApp->mpStream << flush;

	MYCATCH("Register")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

int CEBayISAPIExtension::RegisterConfirm(CHttpServerContext *pCtxt,
										 LPTSTR pEmail,
										 LPTSTR pUserId,
										 LPTSTR pPass,
										 LPTSTR pNewPass,
										 LPTSTR pNewPass2,
										 int notify,
										 int countryId)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pEmail,		1,	false)	||
		!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,		1,	false)	||
		!AfxIsValidAddress(pNewPass,	1,	false)	||
		!AfxIsValidAddress(pNewPass2,	1,	false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RegisterConfirm %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pNewPass, pNewPass2);



	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRegisterConfirm);

		pApp->RegisterConfirm((CEBayISAPIExtension *)this,
							  pCtxt,
							  (char *) pEmail,
							  (char *) pUserId,
							  (char *) pPass,
							  (char *) pNewPass,
							  (char *) pNewPass2,
							  notify,
							  countryId);

		*pApp->mpStream << flush;

	MYCATCH("RegisterConfirm")

	EndContent(pCtxt);
	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

// sam, added, 02/23/1998
int CEBayISAPIExtension::UpdateCC(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pPass,
								  LPTSTR  pccNumber,
								  LPTSTR  pMonth,
								  LPTSTR  pDay,
								  LPTSTR  pYear)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,		1,	false)	||
		!AfxIsValidAddress(pccNumber,	1,	false)	||
		!AfxIsValidAddress(pMonth,	1,	false)		||
		!AfxIsValidAddress(pDay,	1,	false)		||
		!AfxIsValidAddress(pYear,	1,	false)	)

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d UpdateCC %s %s %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pccNumber, pMonth, pDay, pYear);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageUpdateCC);
	 
		pApp->UpdateCC((CEBayISAPIExtension *)this,
							 (CHttpServerContext *) pCtxt,
							 (char *) pUserId,
							 (char *) pPass,
							 (char *) pccNumber,
							 (char *) pMonth,
							 (char *) pDay,
							 (char *) pYear);
		*pApp->mpStream << flush;

	MYCATCH("UpdateCC")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::UpdateCCConfirm(CHttpServerContext *pCtxt,
										 LPTSTR		pUserId,
										 LPTSTR		pCCNumber,
										 int		expDate)
{
	clseBayApp	*pApp;

	// Sanity
	if	( !AfxIsValidAddress(pUserId,		1,	false) ||
		  !AfxIsValidAddress(pCCNumber,		1,	false) )

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d UpdateCCConfirm %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pCCNumber, expDate);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageUpdateCCConfirm);
	 
		pApp->UpdateCCConfirm((CEBayISAPIExtension *)this,
										(CHttpServerContext *) pCtxt,
										(char *) pUserId,
										(char *) pCCNumber,
												 expDate);
		*pApp->mpStream << flush;

	MYCATCH("UpdateCCConfirm")

	EndContent(pCtxt);

	return callOK;

}



int CEBayISAPIExtension::RegisterCC(CHttpServerContext *pCtxt,
									int	   UseForPayment,
									LPTSTR pEmail,
									LPTSTR pOldPass,
									LPTSTR pUserId,
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
									int    notify)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pEmail,			1,	false)		||
		!AfxIsValidAddress(pOldPass,		1,	false)		||
		!AfxIsValidAddress(pUserId,			1,	false)		||
		!AfxIsValidAddress(pNewPass,		1,	false)		||
		!AfxIsValidAddress(pNewPassAgain,	1,	false)		||
		!AfxIsValidAddress(pUserName,		1,	false)		||
		!AfxIsValidAddress(pStreetAddr,		1,	false)		||
		!AfxIsValidAddress(pCityAddr,		1,	false)		||
		!AfxIsValidAddress(pStateProvAddr,	1,	false)		||
		!AfxIsValidAddress(pZipCodeAddr,	1,	false)		||
		!AfxIsValidAddress(pCountryAddr,	1,	false)		||
		!AfxIsValidAddress(pCC,				1,	false)		||
		!AfxIsValidAddress(pMonth,			1,	false)		||
		!AfxIsValidAddress(pDay,			1,	false)		||
		!AfxIsValidAddress(pYear,			1,	false)  )


	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RegisterCC %d %d %.20s %.20s %.20s %.20s %.20s %.20s %.20s %.20s %.20s %.20s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				UseForPayment, pEmail, pOldPass, pUserId, pNewPass, pNewPassAgain,
				pUserName, pStreetAddr, pCityAddr, pStateProvAddr,
				pZipCodeAddr, pCountryAddr, pCC, pMonth, pDay, pYear, notify);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRegisterCC);
	 
		pApp->RegisterCC((CEBayISAPIExtension *)this,
								(CHttpServerContext *) pCtxt,
								(char *) pEmail,
								(char *) pOldPass,
								(char *) pUserId,
								(char *) pNewPass,
								(char *) pNewPassAgain,
								(char *) pUserName,
								(char *) pStreetAddr,
								(char *) pCityAddr,
								(char *) pStateProvAddr,
								(char *) pZipCodeAddr,
								(char *) pCountryAddr,
								(char *) pCC,
								(char *) pMonth,
								(char *) pDay,
								(char *) pYear,
										 UseForPayment,
										 notify);

	*pApp->mpStream << flush;

	MYCATCH("RegisterCC")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::ChangeEmail(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ChangeEmail\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeEmail);

		pApp->ChangeEmail((CEBayISAPIExtension *)this);
		*pApp->mpStream << flush;

	MYCATCH("ChangeEmail")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangeEmailShow(CHttpServerContext *pCtxt,
								  LPTSTR pUserId,
								  LPTSTR pPass,
								  LPTSTR pNewEmail)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,			1,	false)	||
		!AfxIsValidAddress(pNewEmail,		1,	false)	||
		!AfxIsValidAddress(pPass,			1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	ISAPITRACE("0x%x %d ChangeEmailShow %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pNewEmail);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangeEmailShow", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeEmailShow);

		pApp->ChangeEmailShow((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pPass,
					   (char *) pNewEmail);
		*pApp->mpStream << flush;

	MYCATCH("ChangeEmailShow")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangeEmailConfirm(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ChangeEmailConfirm\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeEmailConfirm);

		pApp->ChangeEmailConfirm((CEBayISAPIExtension *)this);
		*pApp->mpStream << flush;

	MYCATCH("ChangeEmailConfirm")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangeEmailConfirmShow(CHttpServerContext *pCtxt,
										 LPTSTR pUserId,
										 LPTSTR pNewUserId,
										 LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pNewUserId,	1,	false)	||
		!AfxIsValidAddress(pPass,		1,	false)	)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeEmailConfirmShow %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pNewUserId, pPass);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangeEmailConfirmShow", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}
	
	MYTRY
		pApp->SetCurrentPage(PageChangeEmailConfirmShow);

		pApp->ChangeEmailConfirmShow((CEBayISAPIExtension *)this, 
							  (char *) pUserId,
							  (char *) pNewUserId,
							  (char *) pPass);
		*pApp->mpStream << flush;

	MYCATCH("ChangeEmailConfirmShow")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangePassword(CHttpServerContext *pCtxt,
										LPTSTR pUserId,
										LPTSTR pPass,
										LPTSTR pNewPass,
										LPTSTR pNewPass2)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,		1,	false)	||
		!AfxIsValidAddress(pNewPass,	1,	false)	||
		!AfxIsValidAddress(pNewPass2,	1,	false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangePassword %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pNewPass, pNewPass2);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangePassword", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangePassword);

		pApp->ChangePassword((CEBayISAPIExtension *)this, 
							 (char *) pUserId,
							 (char *) pPass,
							 (char *) pNewPass,
							 (char *) pNewPass2);
		*pApp->mpStream << flush;

	MYCATCH("ChangePassword")

	EndContent(pCtxt);

	return callOK;

}

// 12/16/97  Charles added
int CEBayISAPIExtension::ChangeUserId(CHttpServerContext *pCtxt,LPTSTR pUserId)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,	1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeUserId %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageChangeUserId);

		pApp->ChangeUserId((CEBayISAPIExtension *)this, (char *) pUserId);
		*pApp->mpStream << flush;

	MYCATCH("ChangeUserId")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::ChangeUserIdShow(CHttpServerContext *pCtxt,
											LPTSTR pOldUserId,
											LPTSTR pPass,
											LPTSTR pNewUserId)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pOldUserId,		1,	false)	||
		!AfxIsValidAddress(pNewUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,			1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeUserIdShow %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pOldUserId, pPass, pNewUserId);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangeUserIdShow", pOldUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeUserIdShow);

		pApp->ChangeUserIdShow((CEBayISAPIExtension *)this, 
					   (char *) pOldUserId,
					   (char *) pPass,
					   (char *) pNewUserId);
		*pApp->mpStream << flush;

	MYCATCH("ChangeUserIdShow")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::UserQuery(CHttpServerContext *pCtxt,
								   LPTSTR pUser,
								   LPTSTR pPass,
								   LPTSTR pOtherUser)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass) ||
		!ValidateUserId(pOtherUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d UserQuery %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, pOtherUser);



	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserQuery);

		pApp->UserQuery((CEBayISAPIExtension *)this, 
							 (char *) pUser,
							 (char *) pPass,
							 (char *) pOtherUser);
		*pApp->mpStream << flush;

	MYCATCH("UserQuery")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangeRegistrationShow(CHttpServerContext *pCtxt,
												LPTSTR pUser,
												LPTSTR pPass,
												int UsingSSL)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeRegistrationShow %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangeRegistrationShow", pUser, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY									
		pApp->SetCurrentPage(PageChangeRegistrationShow);

		pApp->ChangeRegistrationShow((CEBayISAPIExtension *)this, 
									 (char *) pUser,
									 (char *) pPass,
									 UsingSSL);
		*pApp->mpStream << flush;

	MYCATCH("ChangeRegistrationShow")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::ChangeRegistration(CHttpServerContext *pCtxt,
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
											int	   UsingSSL
											)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId)						||
		!ValidatePassword(pPass)					||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pCompany,	1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pAddress,	1,	false)	||
		!AfxIsValidAddress(pCity,		1,	false)	||
		!AfxIsValidAddress(pState,		1,	false)	||
		!AfxIsValidAddress(pOtherState,	1,	false)	||
		!AfxIsValidAddress(pZip,		1,	false)	||
		!AfxIsValidAddress(pCountry,	1,	false)	||
		!AfxIsValidAddress(pDayPhone,	1,	false)	||
		!AfxIsValidAddress(pNightPhone,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone,	1,	false)	||
		!AfxIsValidAddress(pGender,		1,	false))			   
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeRegistration %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangeRegistration", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeRegistration);

		pApp->ChangeRegistration((CEBayISAPIExtension *)this, 
								 (char *) pUserId,
								 (char *) pPass,
								 (char *) pName,
								 (char *) pCompany,
								 (char *) pAddress,
								 (char *) pCity,
								 (char *) pState,
								 (char *) pOtherState,
								 (char *) pZip,
								 (char *) pCountry,
								          countryId,
								 (char *) pDayPhone,
								 (char *) pNightPhone,
								 (char *) pFaxPhone,
								 (char *) pGender,
								 UsingSSL
							 );
		*pApp->mpStream << flush;

	MYCATCH("ChangeRegistration")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangeRegistrationPreview(CHttpServerContext *pCtxt,
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
											)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId)						||
		!ValidatePassword(pPass)					||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pCompany,	1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pAddress,	1,	false)	||
		!AfxIsValidAddress(pCity,		1,	false)	||
		!AfxIsValidAddress(pState,		1,	false)	||
		!AfxIsValidAddress(pOtherState,	1,	false)	||
		!AfxIsValidAddress(pZip,		1,	false)	||
		!AfxIsValidAddress(pDayPhone,	1,	false)	||
		!AfxIsValidAddress(pNightPhone,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone,	1,	false)	||
		!AfxIsValidAddress(pGender,		1,	false))			   
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeRegistrationPreview %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangeRegistrationPreview);

		pApp->ChangeRegistrationPreview((CEBayISAPIExtension *)this, 
								 (char *) pUserId,
								 (char *) pPass,
								 (char *) pName,
								 (char *) pCompany,
								 (char *) pAddress,
								 (char *) pCity,
								 (char *) pState,
								 (char *) pOtherState,
								 (char *) pZip,
								          countryId,
								 (char *) pDayPhone,
								 (char *) pNightPhone,
								 (char *) pFaxPhone,
								 (char *) pGender,
								 UsingSSL
							 );
		*pApp->mpStream << flush;

	MYCATCH("ChangeRegistrationPreview")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ChangePreferencesShow(CHttpServerContext *pCtxt,
												LPTSTR pUser,
												LPTSTR pPass,
												bool   oldStyle)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangePreferencesShow %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY									
		pApp->SetCurrentPage(PageChangePreferencesShow);
	 
		pApp->ChangePreferencesShow((CEBayISAPIExtension *)this, 
									 (char *) pUser,
									 (char *) pPass,
									 (oldStyle==1));
		*pApp->mpStream << flush;

	MYCATCH("ChangePreferencesShow")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::ChangePreferences(CHttpServerContext *pCtxt,
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
						    )
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId)						||
		!ValidatePassword(pPass))			   
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangePreferences %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangePreferences);
	 
		pApp->ChangePreferences((CEBayISAPIExtension *)this, 
								 (char *) pUserId,
								 (char *) pPass,
								 interest_1,
								 interest_2,
								 interest_3,
								 interest_4
								 );
		*pApp->mpStream << flush;

	MYCATCH("ChangePreferences")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::RequestPassword(CHttpServerContext *pCtxt,
										 LPTSTR pUserId)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RequestPassword %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRequestPassword);

		pApp->RequestPassword((CEBayISAPIExtension *)this, 
							  (char *) pUserId);
		*pApp->mpStream << flush;

	MYCATCH("RequestPassword")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::AdminRequestPassword(CHttpServerContext *pCtxt,
										 LPTSTR pUserId)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminRequestPassword %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminRequestPassword);

		pApp->AdminRequestPassword((CEBayISAPIExtension *)this, 
							  (char *) pUserId, auth);
		*pApp->mpStream << flush;

	MYCATCH("AdminRequestPassword")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::ViewAccount(CHttpServerContext *pCtxt,
									 LPTSTR pUser,
									 LPTSTR pPass,
									 int entire,
									 int sinceLastInvoice,
									 int daysback,
									 LPTSTR pStartDate,
									 LPTSTR pEndDate)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass)	||
		!AfxIsValidAddress(pStartDate,	1,	false)			||
		!AfxIsValidAddress(pEndDate,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ViewAccount %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, entire);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ViewAccount", pUser, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewAccount);

		pApp->ViewAccount((CEBayISAPIExtension *)this, 
						   (char *) pUser,
						   (char *) pPass,
						   (entire ? true : false),
						   sinceLastInvoice == 0 ? false : true,
						   daysback,
						   pStartDate,
						   pEndDate);
		*pApp->mpStream << flush;

	MYCATCH("ViewAccount")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::MyEbay(CHttpServerContext *pCtxt,
									 LPTSTR pUser,
									 LPTSTR pPass,
									 LPTSTR pFirst,
									 /*LPTSTR pZone,*/
									 int sellerSort,
									 int bidderSort,
									 int daysSince,
									 int prefFavo,
									 int prefFeed,
									 int prefBala,
									 int prefSell,
									 int prefBidd)
{
	clseBayApp	*pApp;
	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass) || !AfxIsValidAddress(pFirst, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d MyEbay %s %s %s %d %d %d %d %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, pFirst, /*pZone,*/ sellerSort, bidderSort, daysSince,
				prefFavo, prefFeed, prefBala, prefSell, prefBidd);

	// Is the user logging in with cleartext password?
	// If so, do a redirect using the encrypted password for safety
	// Note: don't call StartContent or EndContent, as the redirection doesn't
	//  like it, especially with IIS4
	if(pFirst[0] == 'y' || pFirst[0] == 'Y')
	{
		pApp	= (clseBayApp *)GetApp();
		if (!pApp)
			pApp	= CreateeBayApp();

		pApp->InitISAPI((unsigned char *)pCtxt);

		if (IIS_Server_is_down()) {
			display_IIS_Server_down_page(pApp);
			return callOK;
		}

		MYTRY
			pApp->SetCurrentPage(PageMyEbay);

			pApp->MyEbayRedirect(this, 
							pCtxt,
							   (char *) pUser,
							   (char *) pPass,
							   (char *) pFirst,
							   /*(char *) pZone,*/
							   (int)sellerSort,
							   (int)bidderSort,
							   (int)daysSince,
							   (int)prefFavo,
							   (int)prefFeed,
							   (int)prefBala,
							   (int)prefSell,
							   (int)prefBidd);
		MYCATCH("MyEbay")

		return callOK;
	}

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		*pApp->mpStream << flush;
		EndContent(pCtxt);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "My eBay", pUser, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageMyEbay);

		pApp->MyEbay(this, 
						pCtxt,
						   (char *) pUser,
						   (char *) pPass,
						   (char *) pFirst,
						   /*(char *) pZone,*/
						   (int)sellerSort,
						   (int)bidderSort,
						   (int)daysSince,
						   (int)prefFavo,
						   (int)prefFeed,
						   (int)prefBala,
						   (int)prefSell,
						   (int)prefBidd);
		*pApp->mpStream << flush;

	MYCATCH("MyEbay")

	EndContent(pCtxt);

//	PurifyNewLeaks(); ISAPITRACE1("MyEbay: New In Use %d\n", PurifyNewInuse());

	return callOK;

}

int CEBayISAPIExtension::MyEbaySeller(CHttpServerContext *pCtxt,
									 LPTSTR pUser,
									 LPTSTR pPass,
									 int sort,
									 int daysSince)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d MyEbaySeller %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, sort, daysSince);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageMyEbaySeller);

		pApp->MyEbaySeller((CEBayISAPIExtension *)this, 
						   (char *) pUser,
						   (char *) pPass,
						   (int)sort,
						   (int)daysSince);
		*pApp->mpStream << flush;

	MYCATCH("MyEbaySeller")

	EndContent(pCtxt);

//	PurifyNewLeaks(); ISAPITRACE1("MyEbaySeller: New In Use %d\n", PurifyNewInuse());

	return callOK;

}

int CEBayISAPIExtension::MyEbayBidder(CHttpServerContext *pCtxt,
									 LPTSTR pUser,
									 LPTSTR pPass,
									 int sort,
									 int daysSince)
{
	clseBayApp	*pApp;


	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d MyEbayBidder %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass, sort, daysSince);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageMyEbayBidder);

		pApp->MyEbayBidder((CEBayISAPIExtension *)this, 
						   (char *) pUser,
						   (char *) pPass,
						   (int)sort,
						   (int)daysSince);
		*pApp->mpStream << flush;

	MYCATCH("MyEbayBidder")

	EndContent(pCtxt);

//	PurifyNewLeaks(); ISAPITRACE1("MyEbayBidder: New In Use %d\n", PurifyNewInuse());

	return callOK;

}


int CEBayISAPIExtension::PayCoupon(CHttpServerContext *pCtxt,
								   LPTSTR pUser, LPTSTR pPass, LPTSTR pymtType)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d PayCoupon %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PagePayCoupon);

		pApp->PayCoupon((CEBayISAPIExtension *)this, 
						   (char *) pUser, (char *) pPass, (char *) pymtType);

		*pApp->mpStream << flush;

	MYCATCH("PayCoupon")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::RequestRefund(CHttpServerContext *pCtxt,
								   LPTSTR pUser, LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RequestRefund %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRequestRefund);

		pApp->RequestRefund((CEBayISAPIExtension *)this, 
						   (char *) pUser, (char *) pPass);

		*pApp->mpStream << flush;

	MYCATCH("RequestRefund")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::BetaConfirmationShow(CHttpServerContext *pCtxt,
											  LPTSTR pUser,
											  LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser) || !ValidatePassword(pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d BetaConfirmationShow %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, pPass);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY									
		pApp->SetCurrentPage(PageBetaConfirmationShow);

		pApp->BetaConfirmationShow((CEBayISAPIExtension *)this, 
								   (char *) pUser,
								   (char *) pPass);
		*pApp->mpStream << flush;

	MYCATCH("BetaConfirmationShow")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::BetaConfirmation(CHttpServerContext *pCtxt,
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
										  LPTSTR pGender)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId)					||
		!ValidateUserId(pEmail)						||
		!ValidatePassword(pPass)					||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pCompany,	1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pAddress,	1,	false)	||
		!AfxIsValidAddress(pCity,		1,	false)	||
		!AfxIsValidAddress(pState,		1,	false)	||
		!AfxIsValidAddress(pZip,		1,	false)	||
		!AfxIsValidAddress(pCountry,	1,	false)	||
		!AfxIsValidAddress(pDayPhone,	1,	false)	||
		!AfxIsValidAddress(pNightPhone,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone,	1,	false)	||
		!AfxIsValidAddress(pGender,		1,	false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d BetaConfirmation %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageBetaConfirmation);

		pApp->BetaConfirmation((CEBayISAPIExtension *)this, 
							   (char *) pUserId,
							   (char *) pEmail,
							   (char *) pPass,
							   (char *) pName,
							   (char *) pCompany,
							   (char *) pAddress,
							   (char *) pCity,
							   (char *) pState,
							   (char *) pZip,
							   (char *) pCountry,
							   (char *) pDayPhone,
							   (char *) pNightPhone,
							   (char *) pFaxPhone,
							   (char *) pGender);

			*pApp->mpStream << flush;

	MYCATCH("BetaConfirmation")

	EndContent(pCtxt);

	return callOK;

}

int CEBayISAPIExtension::BetaConfirmationPreview(CHttpServerContext *pCtxt,
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
										  LPTSTR pGender)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUserId)					||
		!ValidateUserId(pEmail)						||
		!ValidatePassword(pPass)					||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pCompany,	1,	false)	||
		!AfxIsValidAddress(pName,		1,	false)	||
		!AfxIsValidAddress(pAddress,	1,	false)	||
		!AfxIsValidAddress(pCity,		1,	false)	||
		!AfxIsValidAddress(pState,		1,	false)	||
		!AfxIsValidAddress(pZip,		1,	false)	||
		!AfxIsValidAddress(pCountry,	1,	false)	||
		!AfxIsValidAddress(pDayPhone,	1,	false)	||
		!AfxIsValidAddress(pNightPhone,	1,	false)	||
		!AfxIsValidAddress(pFaxPhone,	1,	false)	||
		!AfxIsValidAddress(pGender,		1,	false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d BetaConfirmationPreview %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageBetaConfirmationPreview);

		pApp->BetaConfirmationPreview((CEBayISAPIExtension *)this, 
							   (char *) pUserId,
							   (char *) pEmail,
							   (char *) pPass,
							   (char *) pName,
							   (char *) pCompany,
							   (char *) pAddress,
							   (char *) pCity,
							   (char *) pState,
							   (char *) pZip,
							   (char *) pCountry,
							   (char *) pDayPhone,
							   (char *) pNightPhone,
							   (char *) pFaxPhone,
							   (char *) pGender);

			*pApp->mpStream << flush;

	MYCATCH("BetaConfirmationPreview")

	EndContent(pCtxt);

	return callOK;

}
//
// CreateAccount
//
int CEBayISAPIExtension::CreateAccount(CHttpServerContext* pCtxt,
									   LPTSTR pUserId,
									   LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d CreateAccount %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageCreateAccount);

	pApp->CreateAccount(this,	(char *)pUserId,
								(char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("CreateAccount")

	EndContent(pCtxt);

	return callOK;

}

void CEBayISAPIExtension::GetUserEmail(CHttpServerContext *pCtxt,
								   LPTSTR pUser)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d GetUserEmail %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageGetUserEmail);

		pApp->GetUserEmail((CEBayISAPIExtension *)this, 
						   (char *) pUser);

		*pApp->mpStream << '\0'
						<<	flush;

	MYCATCH("GetUserEmail")

	EndContent(pCtxt);

}

void CEBayISAPIExtension::ValidateUserForSurvey(CHttpServerContext *pCtxt, int surveyid)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ValidateUserForSurvey %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), surveyid);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}
/*
	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pRequestorUserId, pRequestorPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else */
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PageValidateUserForSurvey);

		pApp->ValidateUserForSurvey((CEBayISAPIExtension *)this, surveyid);

		*pApp->mpStream << '\0'
						<<	flush;

	MYCATCH("ValidateUserForSurvey")

	EndContent(pCtxt);

}

int CEBayISAPIExtension::GoToSurvey(CHttpServerContext *pCtxt,
				   char *pUserId, char *pPassword, int surveyID)
{
	clseBayApp	*pApp;
	bool ok;
	char reDirectURL[512];

	ISAPITRACE("0x%x %d GoToSurvey, %s, %s, %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUserId, pPassword, surveyID);

	StartContent(pCtxt);
	ok = false;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageGoToSurvey);

		ok = pApp->GoToSurvey((CEBayISAPIExtension *)this, pUserId, pPassword, surveyID, reDirectURL);

		*pApp->mpStream << '\0'
						<<	flush;

	MYCATCH("GoToSurvey")

	if(ok)
		EbayRedirect(pCtxt, reDirectURL);
	else
		EndContent(pCtxt);

	return ok;
}

// Contatc eBay
void CEBayISAPIExtension::ContacteBay(CHttpServerContext *pCtxt, int itemID)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ContacteBay %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), itemID);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageContacteBay);

		pApp->ContacteBay((CEBayISAPIExtension *)this, itemID);

		*pApp->mpStream << '\0'
						<<	flush;

	MYCATCH("ContacteBay")

	EndContent(pCtxt);

}

void CEBayISAPIExtension::ReturnUserEmail(CHttpServerContext *pCtxt,
								   LPTSTR pRequestedUserId,
								   LPTSTR pRequestorUserId,
								   LPTSTR pRequestorPass,
								   int AcceptCookie)
{
	clseBayApp	*pApp;


	// Sanity Checks
	if (!ValidateUserId((char *)pRequestedUserId) || !ValidateUserId((char *)pRequestorUserId) || 
		!ValidatePassword((char *)pRequestorPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ReturnUserEmail %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pRequestedUserId);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pRequestorUserId, pRequestorPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PageReturnUserEmail);

		pApp->ReturnUserEmail((CEBayISAPIExtension *)this, 
						   (char *) pRequestedUserId,
						   (char *) pRequestorUserId,
						   (char *) pRequestorPass);

		*pApp->mpStream << flush;

	MYCATCH("ReturnUserEmail")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::GetUserIdHistory(CHttpServerContext *pCtxt,
								   LPTSTR pUser)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d GetUserIdHistory %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageGetUserIdHistory);

		pApp->GetUserIdHistory((CEBayISAPIExtension *)this, 
						   (char *) pUser);

		*pApp->mpStream << flush;

	MYCATCH("GetUserIdHistory")

	EndContent(pCtxt);

}


void CEBayISAPIExtension::ReturnUserIdHistory(CHttpServerContext *pCtxt,
								   LPTSTR pRequestedUserId,
								   LPTSTR pRequestorUserId,
								   LPTSTR pRequestorPass,
								   int AcceptCookie)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pRequestedUserId) || !ValidateUserId((char *)pRequestorUserId) || 
		!ValidatePassword((char *)pRequestorPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ReturnUserIdHistory %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pRequestedUserId);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pRequestorUserId, pRequestorPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PageReturnUserIdHistory);

		pApp->ReturnUserIdHistory((CEBayISAPIExtension *)this, 
						   (char *) pRequestedUserId,
						   (char *) pRequestorUserId,
						   (char *) pRequestorPass);

		*pApp->mpStream << flush;

	MYCATCH("ReturnUserIdHistory")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::MultipleEmails(CHttpServerContext *pCtxt,
							 LPTSTR pRequestedUserIds,
							 LPTSTR pRequestorUserId,
							 LPTSTR pRequestorPass,
							 int AcceptCookie)
{
	clseBayApp	*pApp;


	// Sanity Checks
	if (!AfxIsValidAddress(pRequestedUserIds, 1, false) || !ValidateUserId((char *)pRequestorUserId) || 
		!ValidatePassword((char *)pRequestorPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d MultipleEmails %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pRequestorUserId);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pRequestorUserId, pRequestorPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PageMultipleEmails);

		pApp->MultipleEmails((CEBayISAPIExtension *)this, 
						   (char *) pRequestedUserIds,
						   (char *) pRequestorUserId,
						   (char *) pRequestorPass);

		*pApp->mpStream << flush;

	MYCATCH("MultipleEmails")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::GetMultipleEmails(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d GetMultipleEmailss\n",
				GetCurrentThreadId(), GetCurrentThreadId());


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
		pApp->SetCurrentPage(PageMultipleEmails);
	 
		pApp->GetMultipleEmails((CEBayISAPIExtension *)this); 

		*pApp->mpStream << flush;

	MYCATCH("GetMultipleEmails")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::ViewAliasHistory(CHttpServerContext *pCtxt,
								   LPTSTR pUserId,
								   LPTSTR pPass)
{
	clseBayApp	*pApp;


	// Sanity Checks
	if (!ValidateUserId((char *)pUserId) || !ValidatePassword((char *)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ViewAliasHistory %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewAliasHistory);
		pApp->ViewAliasHistory((CEBayISAPIExtension *)this, 
						   (char *) pUserId,
						   (char *) pPass);

		*pApp->mpStream << flush;

	MYCATCH("ViewAliasHistory")

	EndContent(pCtxt);
}


//
// Admin
//
int CEBayISAPIExtension::AdminAddExchangeRate(CHttpServerContext* pCtxt,
											     LPTSTR login, 
												 LPTSTR password,
												 int    month,
												 int	day,
												 int	year,
												 int	fromcurrency,
												 int	tocurrency,
												 LPCSTR newrate)
{
	clseBayApp  *pApp;

	ISAPITRACE("0x%x %d AdminAddExchangeRate %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				login);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddExchangeRate);
	
	pApp->AdminAddExchangeRate(login,
								 password,
								 month,
								 day,
								 year,
								 fromcurrency,
								 tocurrency,
								 newrate);
	
	*pApp->mpStream << flush;

	MYCATCH("AdminAddExchangeRate")

	EndContent(pCtxt);

	return callOK;
} 


int CEBayISAPIExtension::AdminViewBids(CHttpServerContext* pCtxt,
										int item)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AdminViewBids %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				item);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminViewBids);

	pApp->AdminViewBids((CEBayISAPIExtension *)this,
						item,
						auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminViewBids")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminShillRelationshipsByItem(CHttpServerContext* pCtxt,
										LPCTSTR details, int item, int limit)
{
	clseBayApp	*pApp;

	if ((!AfxIsValidAddress(details, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}


	ISAPITRACE("0x%x %d AdminShillRelationshipsByItem %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				details, item);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminShillRelationshipsByItem);

	pApp->AdminShillRelationshipsByItem(details, item, limit, auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminShillRelationshipsByItem")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminShillRelationshipsByUsers(CHttpServerContext* pCtxt,
										LPCTSTR details, LPCTSTR users, int limit)
{
	clseBayApp	*pApp;

	if (!AfxIsValidAddress(details, 1, false) ||
		!AfxIsValidAddress(users, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	
	ISAPITRACE("0x%x %d AdminShillRelationshipsByUsers %s %.40s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				details, users);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminShillRelationshipsByUsers);

	pApp->AdminShillRelationshipsByUsers(details, users, limit, auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminShillRelationshipsByUsers")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminShillRelationshipsByFeedback(CHttpServerContext *pCtxt,
		LPCSTR details,
		LPCSTR user,
		LPCSTR left,
		int count,
		int age, 
		int limit)
{
	clseBayApp	*pApp;
	
	if (!AfxIsValidAddress(details, 1, false) ||
		!AfxIsValidAddress(user, 1, false) ||
		!AfxIsValidAddress(left, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}
	

	ISAPITRACE("0x%x %d AdminShillRelationshipsByFeedback %s %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				details, user, left, count, age);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminShillRelationshipsByFeedback);

		pApp->AdminShillRelationshipsByFeedback(details, user, left, count, age, limit, auth);

		*pApp->mpStream << flush;

	MYCATCH("AdminShillRelationshipsByFeedback")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminShowBiddersSellers(CHttpServerContext* pCtxt,
										LPCTSTR bidder)
{
	clseBayApp	*pApp;

	if (!AfxIsValidAddress(bidder, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	
	ISAPITRACE("0x%x %d AdminShowBiddersSellers %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				bidder);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminShowBiddersSellers);

	pApp->AdminShowBiddersSellers(bidder, auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminShowBiddersSellers")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminShowCommonAuctions(CHttpServerContext *pCtxt, LPCTSTR userlist)
{
	clseBayApp	*pApp;
	if (!AfxIsValidAddress(userlist, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminShowCommonAuctions %.40s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				userlist);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminShowCommonAuctions);

	pApp->AdminShowCommonAuctions(userlist, auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminShowCommonAuctions")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminGetShillCandidates(CHttpServerContext* pCtxt)
{
	ISAPITRACE("0x%x %d AdminGetShillCandidates\n",
		GetCurrentThreadId(), GetCurrentThreadId());
	eBayISAPIAuthEnum auth = DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	clseBayApp *pApp = (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminViewOldItem);

	pApp->AdminGetShillCandidates(auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminGetShillCandidates")

	EndContent(pCtxt);
	return callOK;
}

int CEBayISAPIExtension::AdminShowBiddersRetractions(CHttpServerContext *pCtxt,
													  int id, int limit)
{
	ISAPITRACE("0x%x %d AdminShowBiddersRetractions: %d %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(), id, limit);


	eBayISAPIAuthEnum auth = DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	clseBayApp *pApp = (clseBayApp *)GetApp();

	if (!pApp)
		pApp = (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminShowCommonAuctions);
		pApp->AdminShowBiddersRetractions(auth, id, limit);
		*pApp->mpStream << flush;

		MYCATCH("AdminShowBiddersRetractions")

	EndContent(pCtxt);

	return callOK;
}


void CEBayISAPIExtension::AdminViewOldItem(CHttpServerContext* pCtxt,
								   LPCTSTR pItemNo)
{
	clseBayApp		*pApp;
	eBayISAPIAuthEnum	auth;

	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ViewOldItem %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminViewOldItem);

	pApp->AdminViewOldItem(this, (char *)pItemNo, auth);
	*pApp->mpStream << flush;

	MYCATCH("ViewOldItem")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

int CEBayISAPIExtension::UserSearch(CHttpServerContext* pCtxt,
									 LPTSTR pString,
									 int how)
{
	clseBayApp			*pApp;
	eBayISAPIAuthEnum	auth;

	if ((!AfxIsValidAddress(pString, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d UserSearch %.60s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pString, how);


	auth	= DetermineAuthorization(pCtxt);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageUserSearch);

	pApp->UserSearch((CEBayISAPIExtension *)this,
					 pString,
					 how,
					 auth);
	*pApp->mpStream << flush;

	MYCATCH("UserSearch")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::CreditBatch(CHttpServerContext* pCtxt,
									  LPTSTR pText,
									  int doit,
									  LPTSTR pPassword)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pText, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d CreditBatch\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageCreditBatch);

	pApp->CreditBatch((CEBayISAPIExtension *)this,
					  pText,
					  doit,
					  pPassword,
					  auth);
	*pApp->mpStream << flush;

	MYCATCH("CreditBatch")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::CreditBatch2(CHttpServerContext* pCtxt,
									  LPTSTR pText,
									  int doit,
									  LPTSTR pPassword)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pText, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d CreditBatch2 %40.40s %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pText, doit, pPassword);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageCreditBatch);
	 
	pApp->CreditBatch2((CEBayISAPIExtension *)this,
				       pText,
					   doit,
					   pPassword,
					   auth);
	*pApp->mpStream << flush;

	MYCATCH("CreditBatch2")

	EndContent(pCtxt);

	return callOK;
}



int CEBayISAPIExtension::AccountBatch(CHttpServerContext* pCtxt,
									  LPTSTR pText,
									  int doit,
									  LPTSTR pPassword)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pText, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AccountBatch\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAccountingBatch);

	pApp->AccountingBatch((CEBayISAPIExtension *)this,
						  pText,
						  doit,
						  pPassword,
						  auth);
	*pApp->mpStream << flush;

	MYCATCH("AccountingBatch")

	EndContent(pCtxt);

	return callOK;
}

void CEBayISAPIExtension::AdminWarnUserShow(CHttpServerContext *pCtxt,
										    LPTSTR pUser,
										    LPTSTR pPass,
										    LPTSTR pTarget,
										    int type,
										    LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminWarnUserShow %s warns %s for %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUser, pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminWarnUser);	 
		pApp->AdminWarnUserShow(pUser, pPass, pTarget, type, pText,
								auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminWarnUserShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminWarnUserConfirm(CHttpServerContext *pCtxt,
											   LPTSTR pUser,
											   LPTSTR pPass,
											   LPTSTR pTarget,
											   int type,
											   LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminWarnUserConfirm %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminWarnUserConfirm);	 
		pApp->AdminWarnUserConfirm(pUser, pPass, 
								   pTarget, 
								   type, pText,
								   auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminWarnUserConfirm")

	EndContent(pCtxt);
}

int CEBayISAPIExtension::CreditDump(CHttpServerContext* pCtxt, 
									LPTSTR pUserId, LPTSTR pPass)

{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d CreditDump %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass);

	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageAdminCreditBatch);
	 
	pApp->CreditDump((CEBayISAPIExtension *)this,
				       (char *)pUserId,
					   (char *)pPass);
	*pApp->mpStream << flush;

	MYCATCH("CreditDump")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::ItemCreditReq(CHttpServerContext* pCtxt,
									   LPTSTR pUserId,
									   LPTSTR pPass,
									   LPTSTR pItemNo,
									   int	  moreCredits)
{
	clseBayApp	*pApp;

	if ((!AfxIsValidAddress(pUserId, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false))	||
		(!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ItemCreditReq %s %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo, moreCredits);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return  callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageItemCreditReq);
	 
	pApp->ItemCreditReq((CEBayISAPIExtension *)this,
				        (char *)pUserId,
					    (char *)pPass,
					    (char *)pItemNo,
						moreCredits);

	*pApp->mpStream << flush;

	MYCATCH("ItemCreditReq")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::ChineseAuctionCreditReq(CHttpServerContext* pCtxt,
												LPTSTR	pItemNo,
												int		arc,
												int		wasPaid,
												LPTSTR	pAmt,
												int		reason)
{
	clseBayApp	*pApp;

	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pAmt, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChineseAuctionCreditReq %s %d %d %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pItemNo, arc, wasPaid, pAmt, reason);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageChineseAuctionCreditReq);

	
	pApp->ChineseAuctionCreditReq((CEBayISAPIExtension *)this,
									(char *)pItemNo,
									arc,
									wasPaid,
									(char *)pAmt,
									reason);

  *pApp->mpStream << flush;

	MYCATCH("ChineseAuctionCreditReq")

	EndContent(pCtxt);

	return callOK;

}


void CEBayISAPIExtension::AdminWarnUser(CHttpServerContext *pCtxt,
									    LPTSTR pUser,
									    LPTSTR pPass,
									    LPTSTR pTarget,
									    int type,
									    LPTSTR pText,
									    LPTSTR pEmailSubject,
									    LPTSTR pEmailText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))			||
		(!AfxIsValidAddress(pPass, 1, false))			||
		(!AfxIsValidAddress(pTarget, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))			||
		(!AfxIsValidAddress(pEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminWarnUser %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminWarnUserResult);	 
		pApp->AdminWarnUser(pUser, pPass, 
						    pTarget, 
						    type, pText,
						    pEmailSubject, pEmailText,
						    auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminWarnUser")

	EndContent(pCtxt);
}


int CEBayISAPIExtension::DutchAuctionCreditReq(	CHttpServerContext* pCtxt,
												LPTSTR pItemNo,
												int    arc,
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
											)
{
	clseBayApp	*pApp;

	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pAmt1, 1, false))	||
		(!AfxIsValidAddress(pEmail1, 1, false))	||
		(!AfxIsValidAddress(pAmt2, 1, false))	||
		(!AfxIsValidAddress(pEmail2, 1, false))	||
		(!AfxIsValidAddress(pAmt3, 1, false))	||
		(!AfxIsValidAddress(pEmail3, 1, false))	||
		(!AfxIsValidAddress(pAmt4, 1, false))	||
		(!AfxIsValidAddress(pEmail4, 1, false))	||
		(!AfxIsValidAddress(pAmt5, 1, false))	||
		(!AfxIsValidAddress(pEmail5, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d DutchAuctionCreditReq %.20s %d %d %.20s %d %.20s %d %.20s %d %.20s %d %.20s %d \
				%.20s %d %.20s %d %.20s %d %.20s %d %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pItemNo, arc,
				wasPaid1, pAmt1, reason1, pEmail1,
				wasPaid2, pAmt2, reason2, pEmail2,
				wasPaid3, pAmt3, reason3, pEmail3,
				wasPaid4, pAmt4, reason4, pEmail4,
				wasPaid5, pAmt5, reason5, pEmail5, 
				moreCredits);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageDutchAuctionCreditReq);

	
	pApp->DutchAuctionCreditReq((CEBayISAPIExtension *)this,
								(char *)pItemNo,
								arc,
								wasPaid1,
								(char *)pAmt1,
								reason1,
								(char *)pEmail1,
								wasPaid2,
								(char *)pAmt2,
								reason2,
								(char *)pEmail2,
								wasPaid3,
								(char *)pAmt3,
								reason3,
								(char *)pEmail3,
								wasPaid4,
								(char *)pAmt4,
								reason4,
								(char *)pEmail4,
								wasPaid5,
								(char *)pAmt5,
								reason5,
								(char *)pEmail5,
								moreCredits);

  *pApp->mpStream << flush;

	MYCATCH("DutchAuctionCreditReq")

	EndContent(pCtxt);

	return callOK;
}





void CEBayISAPIExtension::AdminSuspendUserShow(CHttpServerContext *pCtxt,
											   LPTSTR pUser,
											   LPTSTR pPass,
											   LPTSTR pTarget,
											   int type,
											   LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminSuspendUserShow %s suspends %s for %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUser, pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminSuspendUser);	 
		pApp->AdminSuspendUserShow(pUser, pPass, pTarget, type, pText,
								   auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminSuspendUserShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminSuspendUserConfirm(CHttpServerContext *pCtxt,
												  LPTSTR pUser,
												  LPTSTR pPass,
												  LPTSTR pTarget,
												  int type,
												  LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;
	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminSuspendUserConfirm %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminSuspendUserConfirm);	 
		pApp->AdminSuspendUserConfirm(pUser, pPass, 
									  pTarget, 
									  type, pText,
									  auth);

		*pApp->mpStream << flush;
	MYCATCH("AdminSuspendUserConfirm")
	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminSuspendUser(CHttpServerContext *pCtxt,
										   LPTSTR pUser,
										   LPTSTR pPass,
										   LPTSTR pTarget,
										   int type,
										   LPTSTR pText,
										   LPTSTR pEmailSubject,
										   LPTSTR pEmailText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))			||
		(!AfxIsValidAddress(pPass, 1, false))			||
		(!AfxIsValidAddress(pTarget, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))			||
		(!AfxIsValidAddress(pEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminSuspendUser %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminSuspendUserResult);	 
		pApp->AdminSuspendUser(pUser, pPass, 
							   pTarget, 
							   type, pText,
							   pEmailSubject, pEmailText,
							   auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminSuspendUser")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminReinstateUserShow(CHttpServerContext *pCtxt,
												 LPTSTR pUser,
												 LPTSTR pPass,
												 LPTSTR pTarget,
												 int type,
												 LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminReinstateUserShow %s suspends %s for %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUser, pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateUser);	 
		pApp->AdminReinstateUserShow(pUser, pPass, pTarget, type, pText,
								   auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateUserShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminReinstateUserConfirm(CHttpServerContext *pCtxt,
													LPTSTR pUser,
													LPTSTR pPass,
													LPTSTR pTarget,
													int type,
													LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminReinstateUserConfirm %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateUserConfirm);	 
		pApp->AdminReinstateUserConfirm(pUser, pPass, 
									    pTarget, 
									    type, pText,
									    auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateUserConfirm")

	EndContent(pCtxt);
}



void CEBayISAPIExtension::AdminReinstateUser(CHttpServerContext *pCtxt,
											 LPTSTR pUser,
											 LPTSTR pPass,
											 LPTSTR pTarget,
											 int type,
											 LPTSTR pText,
											 LPTSTR pEmailSubject,
											 LPTSTR pEmailText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))			||
		(!AfxIsValidAddress(pPass, 1, false))			||
		(!AfxIsValidAddress(pTarget, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))			||
		(!AfxIsValidAddress(pEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminReinstateUser %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateUserResult);	 
		pApp->AdminReinstateUser(pUser, pPass, 
								 pTarget, 
								 type, pText,
								 pEmailSubject, pEmailText,
								 auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateUser")

	EndContent(pCtxt);
}



int CEBayISAPIExtension::AdminResetReqEmailCount(CHttpServerContext* pCtxt,
									 LPTSTR pUserId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminResetReqEmailCount %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);


	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminResetReqEmailCount);

	pApp->AdminResetReqEmailCount((CEBayISAPIExtension *)this,
					  pUserId,
					  auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminResetReqEmailCount")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminResetReqUserCount(CHttpServerContext* pCtxt,
									 LPTSTR pUserId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminResetReqUserCount %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);


	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminResetReqEmailCount);

	pApp->AdminResetReqUserCount((CEBayISAPIExtension *)this,
					  pUserId,
					  auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminResetReqUserCount")

	EndContent(pCtxt);

	return callOK;
}

//
// admin Change Item Info (title, quantity, and description)
//
int CEBayISAPIExtension::ChangeItemInfo(CHttpServerContext* pCtxt,
														  LPTSTR pItemNo)

{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ChangeItemInfo %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageChangeItemInfo);

	pApp->ChangeItemInfo((CEBayISAPIExtension *)this,
					   pItemNo);
	*pApp->mpStream << flush;

	MYCATCH("ChangeItemInfo")

	EndContent(pCtxt);

	return callOK;

} 

int CEBayISAPIExtension::ItemInfo(CHttpServerContext* pCtxt,
									  LPTSTR pAction,
									  LPTSTR pItemNo,
									  LPTSTR pTitle,
									  LPTSTR pQuantity,
									  LPTSTR pcEndTime,
									  LPTSTR pcEndTimeHour,
									  LPTSTR pcEndTimeMin,
									  LPTSTR pcEndTimeSec,
									  int featured, 
									  int superfeatured, 
									  LPTSTR pDescription,
									  int galleryfeatured,
									  int gallery,
									  LPTSTR pGiftIcon) 
{
	clseBayApp	*pApp;
	bool			ok;
	char			reDirectURL[512];
	unsigned long	reDirectURLLen;

	// Sanity
	if ((!AfxIsValidAddress(pAction, 1, false)) ||
		(!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pTitle, 1, false)) ||
		(!AfxIsValidAddress(pQuantity, 1, false)) ||
		(!AfxIsValidAddress(pcEndTime, 1, false)) ||
		(!AfxIsValidAddress(pcEndTimeHour, 1, false)) ||
		(!AfxIsValidAddress(pcEndTimeMin, 1, false)) ||
		(!AfxIsValidAddress(pcEndTimeSec, 1, false)) ||
		(!AfxIsValidAddress(pDescription, 1, false)) ||
		(!AfxIsValidAddress(pGiftIcon, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ItemInfo %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);


	StartContent(pCtxt);
	ok = false;
	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageItemInfo);

	ok = pApp->ItemInfo((CEBayISAPIExtension *)this,
					  pAction,
					  pItemNo,
					  pTitle,
					  pQuantity,
					  pcEndTime,
					  pcEndTimeHour,
					  pcEndTimeMin,
					  pcEndTimeSec,
					  featured,	 
					  superfeatured,
					  pDescription,
					  gallery,	 
					  galleryfeatured,	    
					  pGiftIcon,
					  reDirectURL);
	*pApp->mpStream << flush;

	MYCATCH("ItemInfo")
	if (ok && (strcmp(reDirectURL, "-") != 0))
	{
		EbayRedirect(pCtxt, reDirectURL);
		reDirectURLLen	= strlen(reDirectURL);
/*		pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP,
									 reDirectURL,
									 &reDirectURLLen,
									 (DWORD *)NULL); */
	}
	else
		EndContent(pCtxt);

	return callOK;

} 




#ifdef NOT
int CEBayISAPIExtension::ReinstateUser(CHttpServerContext* pCtxt,
									   LPTSTR pUserId)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ReinstateUser %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageReinstateUser);

	pApp->ReinstateUser((CEBayISAPIExtension *)this,
						pUserId,
						auth);
	*pApp->mpStream << flush;

	MYCATCH("ReinstateUser")

	EndContent(pCtxt);

	return callOK;
}
#endif /* NOT */


int CEBayISAPIExtension::ConfirmUser(CHttpServerContext* pCtxt,
									 LPTSTR pUserId)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d ConfirmUser %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageConfirmUser);

	pApp->ConfirmUser((CEBayISAPIExtension *)this,
					  pUserId,
					  auth);
	*pApp->mpStream << flush;

	MYCATCH("ConfirmUser")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminEndAuctionShow(CHttpServerContext* pCtxt,
											 LPTSTR pUser,
											 LPTSTR pPass,
											 LPTSTR pItemId,
											 int suspended,
											 int creditfees,
											 int emailbidders,
											 int type,
											 int buddy,
											 LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)) ||
		(!AfxIsValidAddress(pItemId, 1, false)) ||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAuctionShow %d %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				suspended, creditfees, pUser);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminEndAuctionShow);

	pApp->AdminEndAuctionShow((CEBayISAPIExtension *)this,
							  pUser,
							  pPass,
							  pItemId,
							  suspended,
							  creditfees,
							  emailbidders,
							  type,
							  buddy,
							  pText,
							  auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminEndAuctionShow")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminEndAuctionConfirm(CHttpServerContext* pCtxt,
												LPTSTR pUser,
												LPTSTR pPass,
												char *pItemId,
												int suspended,
												int creditfees,
												int emailbidders,
												int type,
												int buddy,
												LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false))	||
		(!AfxIsValidAddress(pPass, 1, false))	||
		(!AfxIsValidAddress(pItemId, 1, false)) ||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAuctionConfirm %d %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				suspended, creditfees, pUser);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminEndAuction);

	pApp->AdminEndAuctionConfirm((CEBayISAPIExtension *)this,
								  pUser,
								  pPass,
								  pItemId,
								  suspended,
								  creditfees,
								  emailbidders,
								  type,
								  buddy,
								  pText,
								  auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminEndAuctionConfirm")

	EndContent(pCtxt);

	return callOK;
}



int CEBayISAPIExtension::AdminEndAuction(CHttpServerContext* pCtxt,
										 LPTSTR pUser,
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
										 LPTSTR	pBidderEmailSubject,
										 LPTSTR pBidderEmailText,
										 LPTSTR pBuddyEmailAddress,
										 LPTSTR pBuddyEmailSubject,
										 LPTSTR pBuddyEmailText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false))				||
		(!AfxIsValidAddress(pPass, 1, false))				||
		(!AfxIsValidAddress(pItemId, 1, false))				||
		(!AfxIsValidAddress(pText, 1, false))				||
		(!AfxIsValidAddress(pSellerEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pSellerEmailText, 1, false))	||
		(!AfxIsValidAddress(pBidderEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pBidderEmailText, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailAddress, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAuction %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, suspended, creditfees);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageEndAuctionAndCreditFees);


	pApp->AdminEndAuction((CEBayISAPIExtension *)this,
						  pUser,
						  pPass,
						  pItemId,
						  (suspended != 0 ? true : false),
						  (creditfees != 0 ? true : false),
						  (emailbidders != 0 ? true : false),
						  type,
						  buddy,
						  pText,
						  pSellerEmailSubject,
						  pSellerEmailText,
						  pBidderEmailSubject,
						  pBidderEmailText,
						  pBuddyEmailAddress,
						  pBuddyEmailSubject,
						  pBuddyEmailText,
						  auth);

	*pApp->mpStream << flush;

	MYCATCH("EndAuction")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminEndAllAuctionsShow(CHttpServerContext* pCtxt,
												 LPTSTR pUser,
												 LPTSTR pPass,
												 LPTSTR pTargetUser,
												 int suspended,
												 int creditfees,
												 int emailbidders,
												 int type,
												 int buddy,
												 LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTargetUser, 1, false))	||
		(!AfxIsValidAddress(pText, 1, false))			)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAllAuctionsShow %s %d %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pTargetUser, suspended, creditfees, pUser);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminEndAllAuctions);

	pApp->AdminEndAllAuctionsShow(pUser,
								  pPass,
								  pTargetUser,
								  suspended,
								  creditfees,
								  emailbidders,
								  type,
								  buddy,
								  pText,
								  auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminEndAllAuctionsShow")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminEndAllAuctionsConfirm(CHttpServerContext* pCtxt,
													LPTSTR pUser,
													LPTSTR pPass,
													LPTSTR pTargetUser,
													int suspended,
													int creditfees,
													int emailbidders,
													int type,
													int buddy,
													LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)) ||
		(!AfxIsValidAddress(pTargetUser, 1, false)) ||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAllAuctionsConfirm %s %d %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pTargetUser, suspended, creditfees, pUser);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminEndAllAuctionsConfirm);

	pApp->AdminEndAllAuctionsConfirm(pUser,
									 pPass,
									 pTargetUser,
									 suspended,
									 creditfees,
									 emailbidders,
									 type,
									 buddy,
									 pText,
									 auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminEndAllAuctionsConfirm")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminEndAllAuctions(CHttpServerContext* pCtxt,
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
											 LPTSTR pBuddyEmailText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false))				||
		(!AfxIsValidAddress(pPass, 1, false))				||
		(!AfxIsValidAddress(pTargetUser, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))				||
		(!AfxIsValidAddress(pSellerEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pSellerEmailText, 1, false))	||
		(!AfxIsValidAddress(pBidderEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pBidderEmailText, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailAddress, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pBuddyEmailText, 1, false))			)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminEndAllAuctions %s by %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pTargetUser, pUserId);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminEndAllAuctionsResult);

	pApp->AdminEndAllAuctions(pUserId, pPass,
							  pTargetUser,
							  suspended, creditfees, emailbidders,
							  type, buddy, pText,
							  pSellerEmailSubject, pSellerEmailText,
							  pBidderEmailSubject, pBidderEmailText, 
							  pBuddyEmailAddress,
							  pBuddyEmailSubject, pBuddyEmailText,
							  auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminEndAllAuctions")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminMoveAuctionShow(CHttpServerContext* pCtxt,
											  LPTSTR pUser,
											  LPTSTR pPass,
											  LPTSTR pItemId,
											  int category,
											  int emailsellers,
											  int chargesellers,
											  LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)) ||
		(!AfxIsValidAddress(pItemId, 1, false)) ||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminMoveAuctionShow %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				category, emailsellers, chargesellers);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminMoveAuctionConfirm);

	pApp->AdminMoveAuctionShow((CEBayISAPIExtension *)this,
							   pUser,
							   pPass,
							   pItemId,
							   category,
							   emailsellers,
							   chargesellers,
							   pText,
							   auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminMoveAuctionShow")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminMoveAuctionConfirm(CHttpServerContext* pCtxt,
												LPTSTR pUser,
												LPTSTR pPass,
												char *pItemId,
												int category,
												int emailsellers,
												int chargesellers,
												LPTSTR pText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false))	||
		(!AfxIsValidAddress(pPass, 1, false))	||
		(!AfxIsValidAddress(pItemId, 1, false)) ||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminMoveAuctionConfirm %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				category, emailsellers, chargesellers);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminMoveAuctionConfirm);

	pApp->AdminMoveAuctionConfirm((CEBayISAPIExtension *)this,
								  pUser,
								  pPass,
								  pItemId,
								  category,
								  emailsellers,
								  chargesellers,
								  pText,
								  auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminMoveAuctionConfirm")

	EndContent(pCtxt);

	return callOK;
}



int CEBayISAPIExtension::AdminMoveAuction(CHttpServerContext* pCtxt,
										 LPTSTR pUser,
										 LPTSTR pPass,
										 char *pItemId,
										 int category,
										 int emailsellers,
										 int chargesellers,
										 LPTSTR pText,
										 LPTSTR pSellerEmailSubject,
										 LPTSTR pSellerEmailText)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUser, 1, false))				||
		(!AfxIsValidAddress(pPass, 1, false))				||
		(!AfxIsValidAddress(pItemId, 1, false))				||
		(!AfxIsValidAddress(pText, 1, false))				||
		(!AfxIsValidAddress(pSellerEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pSellerEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminMoveAuction %s %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser, category, emailsellers, chargesellers);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminMoveAuctionResult);


	pApp->AdminMoveAuction((CEBayISAPIExtension *)this,
						   pUser,
						   pPass,
						   pItemId,
						   category,
						   emailsellers,
						   chargesellers,
						   pText,
						   pSellerEmailSubject,
						   pSellerEmailText,
						   auth);

	*pApp->mpStream << flush;

	MYCATCH("AdminMoveAuction")

	EndContent(pCtxt);

	return callOK;
}



int CEBayISAPIExtension::RetractAllBids(CHttpServerContext* pCtxt,
										LPTSTR pUserId,
										int cautionToTheWind)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d RetractAllBids %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, cautionToTheWind);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageRetractAllBids);

	pApp->RetractAllBids((CEBayISAPIExtension *)this,
						 pUserId,
						 auth,
						 (cautionToTheWind ? true : false));

	*pApp->mpStream << flush;

	MYCATCH("RetractAllBids")

	EndContent(pCtxt);

	return callOK;
}


// change email - admin function

int CEBayISAPIExtension::AdminCombineUsers(CHttpServerContext* pCtxt,
										LPTSTR pOldUserId,
										LPTSTR pOldPass,
										LPTSTR pNewUserId,
										LPTSTR pNewPass)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pOldUserId, 1, false)) ||
		(!AfxIsValidAddress(pOldPass, 1, false)) ||
		(!AfxIsValidAddress(pNewUserId, 1, false)) ||
		(!AfxIsValidAddress(pNewPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminCombineUsers %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pOldUserId, pOldPass, pNewUserId, pNewPass);


	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminCombineUsers);

	pApp->AdminCombineUsers((CEBayISAPIExtension *)this,
						 pOldUserId,
						 pOldPass,
						 pNewUserId,
						 pNewPass,
						 auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminCombineUsers")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminCombineUserConf(CHttpServerContext* pCtxt,
										LPTSTR pOldUserId,
										LPTSTR pOldPass,
										LPTSTR pNewUserId,
										LPTSTR pNewPass)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pOldUserId, 1, false)) ||
		(!AfxIsValidAddress(pOldPass, 1, false)) ||
		(!AfxIsValidAddress(pNewUserId, 1, false)) ||
		(!AfxIsValidAddress(pNewPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminCombineUserConf %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pOldUserId, pOldPass, pNewUserId, pNewPass);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminCombineUserConf);

	pApp->AdminCombineUserConf((CEBayISAPIExtension *)this,
						 pOldUserId,
						 pOldPass,
						 pNewUserId,
						 pNewPass,
						 auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminCombineUserConf")

	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminChangeEmail(CHttpServerContext* pCtxt,
										LPTSTR pUserId)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminChangeEmail %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminChangeEmail);

	pApp->AdminChangeEmail((CEBayISAPIExtension *)this,
						 pUserId);
	*pApp->mpStream << flush;

	MYCATCH("AdminChangeEmail")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminChangeEmailShow(CHttpServerContext* pCtxt,
										LPTSTR pUserId,
										LPTSTR pNewEmail)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)) ||
		(!AfxIsValidAddress(pNewEmail, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminChangeEmailShow %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pNewEmail);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminChangeEmailShow);

	pApp->AdminChangeEmailShow((CEBayISAPIExtension *)this,
						 pUserId, pNewEmail);
	*pApp->mpStream << flush;

	MYCATCH("AdminChangeEmailShow")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AdminChangeEmailConfirm(CHttpServerContext* pCtxt,
										LPTSTR pUserId,
										LPTSTR pNewEmail,
										int		Change)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)) ||
		(!AfxIsValidAddress(pNewEmail, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminChangeEmailConfirm %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pNewEmail, Change);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminChangeEmailConfirm);

	pApp->AdminChangeEmailConfirm((CEBayISAPIExtension *)this,
						 pUserId, pNewEmail, Change);
	*pApp->mpStream << flush;

	MYCATCH("AdminChangeEmailConfirm")

	EndContent(pCtxt);

	return callOK;
}



// 01/06/98  Charles added
int CEBayISAPIExtension::AdminChangeUserIdShow(CHttpServerContext *pCtxt,LPTSTR pUserId)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminChangeUserIdShow %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUserId);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminChangeUserIdShow);

		pApp->AdminChangeUserIdShow((CEBayISAPIExtension *)this, 
									(char *) pUserId,
									auth);
		*pApp->mpStream << flush;

	MYCATCH("AdminChangeUserIdShow")

	EndContent(pCtxt);

	return callOK;

}


int CEBayISAPIExtension::AdminChangeUserId(CHttpServerContext *pCtxt,
											LPTSTR pOldUserId,
											LPTSTR pPass,
											LPTSTR pNewUserId,
											int	   confirm)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if (!AfxIsValidAddress(pOldUserId,		1,	false)	||
		!AfxIsValidAddress(pNewUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,			1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	ISAPITRACE("0x%x %d AdminChangeUserId %s %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pOldUserId, pPass, pNewUserId, confirm);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminChangeUserId);

		pApp->AdminChangeUserId((CEBayISAPIExtension *)this, 
					   (char *) pOldUserId,
					   (char *) pPass,
					   (char *) pNewUserId,
					   (int)	confirm,
					   auth);
		*pApp->mpStream << flush;

	MYCATCH("AdminChangeUserId")

	EndContent(pCtxt);

	return callOK;

}


//
// Category Admin 
//
void CEBayISAPIExtension::CategoryAdmin(CHttpServerContext* pCtxt)
{

	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageCategoryAdminRun);

	pApp->CategoryAdminRun(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("CategoryAdmin")

	ISAPITRACE((LPCTSTR)"clsApp @ 0x%x\n", pApp);

	EndContent(pCtxt);
}

//
// Category Integrity Checker 
//
void CEBayISAPIExtension::CategoryChecker(CHttpServerContext* pCtxt)
{

	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	ISAPITRACE("0x%x %d CategoryChecker\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageCategoryAdminRun);

	pApp->CategoryChecker(this, auth);

	*pApp->mpStream << flush;

	MYCATCH("CategoryChecker")

	EndContent(pCtxt);
}

/* petra 06/15/99 wired off ---------------------------------------------------

// view Category details

void CEBayISAPIExtension::ViewCategory(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageViewCategory);

	pApp->ViewCategory(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("ViewCategory")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::VerifyUpdateCategory(CHttpServerContext* pCtxt,
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pCategory)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					|| 
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pCategory, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageVerifyUpdateCategory);

	pApp->VerifyUpdateCategory(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pCategory,
						  auth);
	*pApp->mpStream << flush;

	MYCATCH("VerifyUpdateCategory")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::UpdateCategory(CHttpServerContext* pCtxt,
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pCategory,
					  LPCTSTR pName,
					  LPCTSTR pDesc,
					  LPCTSTR pFileRef,
					  LPCTSTR pFeaturedCost,
					  LPCTSTR pAdult,
					  LPCTSTR pExpired
					  )
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					|| 
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pCategory, 1, false)				||
		!AfxIsValidAddress(pName, 1, false)					||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pFileRef, 1, false)				||
		!AfxIsValidAddress(pFeaturedCost, 1, false)			||
		!AfxIsValidAddress(pAdult, 1, false)				||
		!AfxIsValidAddress(pExpired, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);



	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageUpdateCategory);

	pApp->UpdateCategory(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pCategory,
						  (char *)pName,
						  (char *)pDesc,
						  (char *)pFileRef,
						  (char *)pFeaturedCost,
						  (char *)pAdult,
						  (char *)pExpired,
						  auth
						  );
	*pApp->mpStream << flush;

	MYCATCH("UpdateCategory")

	EndContent(pCtxt);
}
---------------------------------------------------- petra *
//
// Enter New Category
//
void CEBayISAPIExtension::NewCategory(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageNewCategory);

	pApp->NewCategory(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("NewCategory")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::VerifyNewCategory(CHttpServerContext* pCtxt, 
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pName,
					  LPCTSTR pDesc,
					  LPCTSTR pAdult,
					  LPCTSTR pFeaturedCost,
					  LPCTSTR pFileRef,
					  LPCTSTR pCategory,
					  LPCTSTR pAddAction
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					|| 
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pName, 1, false)					||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pAdult, 1, false)				||
		!AfxIsValidAddress(pFeaturedCost, 1, false)			||
		!AfxIsValidAddress(pFileRef, 1, false)				||
		!AfxIsValidAddress(pCategory, 1, false)				||
		!AfxIsValidAddress(pAddAction, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageVerifyNewCategory);

	pApp->VerifyNewCategory(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pName,
						  (char *)pDesc,
						  (char *)pAdult,
						  (char *)pFeaturedCost,
						  (char *)pFileRef,
						  (char *)pCategory,
						  (char *)pAddAction,
						  auth
							);
	*pApp->mpStream << flush;

	MYCATCH("VerifyNewCategory")

	EndContent(pCtxt);
}
* petra 06/15/99 wired off ------------------------------------------------
void CEBayISAPIExtension::AddNewCategory(CHttpServerContext* pCtxt, 
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pName,
					  LPCTSTR pDesc,
					  LPCTSTR pAdult,
					  LPCTSTR pFeaturedCost,
					  LPCTSTR pFileRef,
					  LPCTSTR pCategory,
					  LPCTSTR pAddAction
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pName, 1, false)					||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pAdult, 1, false)				||
		!AfxIsValidAddress(pFeaturedCost, 1, false)			||
		!AfxIsValidAddress(pFileRef, 1, false)				||
		!AfxIsValidAddress(pCategory, 1, false)				||
		!AfxIsValidAddress(pAddAction, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAddNewCategory);

	pApp->AddNewCategory(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pName,
						  (char *)pDesc,
						  (char *)pAdult,
						  (char *)pFeaturedCost,
						  (char *)pFileRef,
						  (char *)pCategory,
						  (char *)pAddAction,
						  auth
							);
	*pApp->mpStream << flush;

	MYCATCH("AddNewCategory")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::DeleteCategory(CHttpServerContext* pCtxt
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageDeleteCategory);

	pApp->DeleteCategory(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("DeleteCategory")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::MakeDelete(CHttpServerContext* pCtxt, 
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pCategory
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pCategory, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageMakeDelete);

	pApp->MakeDelete(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pCategory,
						  auth
							);
	*pApp->mpStream << flush;

	MYCATCH("MakeDelete")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::MoveCategory(CHttpServerContext* pCtxt
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageMoveCategory);

	pApp->MoveCategory(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("MoveCategory")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::OrderCategory(CHttpServerContext* pCtxt
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageOrderCategory);

	pApp->OrderCategory(this, auth);
	*pApp->mpStream << flush;

	MYCATCH("OrderCategory")

	EndContent(pCtxt);
}
------------------------------------------------ petra */
void CEBayISAPIExtension::AdminViewDailyStats(CHttpServerContext* pCtxt,
						 int	StartMon,
						 int	StartDay,
						 int	StartYear,
						 int	EndMon,
						 int	EndDay,
						 int	EndYear,
						 LPCTSTR pEmail,
						 LPCTSTR pPass)
{
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(pEmail, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminViewDailyStats);

		pApp->AdminViewDailyStats(this,
							StartMon,
							StartDay,
							StartYear,
							EndMon,
							EndDay,
							EndYear,
							(char *)pEmail,
							(char *)pPass);

		*pApp->mpStream << flush;

	MYCATCH("AdminViewDailyStats")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminViewDailyFinance(CHttpServerContext* pCtxt,
						 int	StartMon,
						 int	StartDay,
						 int	StartYear,
						 int	EndMon,
						 int	EndDay,
						 int	EndYear,
						 LPCTSTR pEmail,
						 LPCTSTR pPass)
{
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(pEmail, 1, false)) ||
		(!AfxIsValidAddress(pPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminViewDailyFinance);

		pApp->AdminViewDailyFinance(this,
							StartMon,
							StartDay,
							StartYear,
							EndMon,
							EndDay,
							EndYear,
							(char *)pEmail,
							(char *)pPass);

		*pApp->mpStream << flush;

	MYCATCH("AdminViewDailyFinance")

	EndContent(pCtxt);
}
/* petra 06/15/99 wired off -----------------------------------------
void CEBayISAPIExtension::MakeMove(CHttpServerContext* pCtxt, 
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pFromCategory,
					  LPCTSTR pToCategory
					  )

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pFromCategory, 1, false)			||
		!AfxIsValidAddress(pToCategory, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageMakeMove);

	pApp->MakeMove(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pFromCategory,
						  (char *)pToCategory,
						  auth
							);
	*pApp->mpStream << flush;

	MYCATCH("MakeMove")

	EndContent(pCtxt);
}

---------------------------------------------- petra */
void CEBayISAPIExtension::AdminAnnouncement(CHttpServerContext* pCtxt, int SiteId, int PartnerId)
{

	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);
	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminAnnouncement);

	pApp->AdminAnnouncement(this, auth, SiteId, PartnerId);
	*pApp->mpStream << flush;

	MYCATCH("AdminAnnouncement")

	ISAPITRACE((LPCTSTR)"clsApp @ 0x%x\n", pApp);

	EndContent(pCtxt);
}

void CEBayISAPIExtension::UpdateAnnouncement(CHttpServerContext* pCtxt,
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pId,
					  LPCTSTR pLoc,
					  LPCTSTR pSiteId,
					  LPCTSTR pPartnerId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);
	StartContent(pCtxt);

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)			||
		!ValidatePassword((char *)pPassword)		||
		!AfxIsValidAddress(pId, 1, false)			|| 
		!AfxIsValidAddress(pLoc, 1, false)			||
		!AfxIsValidAddress(pSiteId, 1, false)		||
		!AfxIsValidAddress(pPartnerId, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageUpdateAnnouncement);

	pApp->UpdateAnnouncement(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pId,
						  (char *)pLoc,
						  auth,
						  (char *)pSiteId,
						  (char *)pPartnerId);

	*pApp->mpStream << flush;

	MYCATCH("UpdateAnnouncement")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AddAnnouncement(CHttpServerContext* pCtxt,
					  LPCTSTR pUserId,
					  LPCTSTR pPassword,
					  LPCTSTR pId,
					  LPCTSTR pLoc,
					  LPCTSTR pCode,
					  LPCTSTR pDesc,
					  LPCTSTR pSiteId,
					  LPCTSTR pPartnerId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPassword)				||
		!AfxIsValidAddress(pId, 1, false)					|| 
		!AfxIsValidAddress(pLoc, 1, false)					||
		!AfxIsValidAddress(pSiteId, 1, false)				||
		!AfxIsValidAddress(pPartnerId, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);



	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAddAnnouncement);

	pApp->AddAnnouncement(this,
						  (char *)pUserId,
						  (char *)pPassword,
						  (char *)pId,
						  (char *)pLoc,
						  (char *)pCode,
						  (char *)pDesc,
						  auth,
						  (char *)pSiteId,
						  (char *)pPartnerId);

	*pApp->mpStream << flush;

	MYCATCH("AddAnnouncement")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::SurveyResponse(CHttpServerContext* pCtxt,
										 LPCTSTR pUserId,
										 LPCTSTR pPassword,
										 LPCTSTR pSurveyId,
										 LPCTSTR pQuestionId,
										 LPCTSTR pResponse)
{
	clseBayApp	*pApp;

	// Sanity Checks
	if (!ValidateUserId((char *)pUserId)				||
		!ValidatePassword((char *)pPassword)			||
		!AfxIsValidAddress(pSurveyId, 1, false)			|| 
		!AfxIsValidAddress(pQuestionId, 1, false)		||
		!AfxIsValidAddress(pResponse, 1, false))

	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);



	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageSurveyResponse);

	pApp->SurveyResponse(this,
						 (char *)pUserId,
						 (char *)pPassword,
						 (char *)pSurveyId,
						 (char *)pQuestionId,
						 (char *)pResponse);

	*pApp->mpStream << flush;

	MYCATCH("SurveyResponse")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::RedirectEnter(CHttpServerContext* pCtxt,
										LPCTSTR pLocation,
										LPCTSTR pPartnerName)
{
	clseBayApp	*pApp;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	// No, we don't start content here, because we're sending headers.
	// StartContent(pCtxt);

	if (!AfxIsValidAddress(pLocation, 1, false) ||
		!AfxIsValidAddress(pPartnerName, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageRedirectEnter);

	pApp->RedirectEnter(this,
						pCtxt,
						(char *) pLocation,
						(char *) pPartnerName);

	*pApp->mpStream << flush;

	MYCATCH("RedirectEnter")

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	// Don't end the content either.
	//EndContent(pCtxt);
}

// nsacco 06/21/99
void CEBayISAPIExtension::ShowCobrandPartners(CHttpServerContext *pCtxt, int siteId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageShowCobrandPartners);

	// nsacco 06/21/99
	pApp->ShowCobrandPartners(auth, siteId);

	*pApp->mpStream << flush;

	MYCATCH("ShowCobrandPartners")

	EndContent(pCtxt);

	return;
}


void CEBayISAPIExtension::ShowCobrandHeaders(CHttpServerContext *pCtxt,
											 int partnerId,
											 int siteId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageShowCobrandHeaders);

	pApp->ShowCobrandHeaders(auth, partnerId, siteId);

	*pApp->mpStream << flush;

	MYCATCH("ShowCobrandHeaders")

	EndContent(pCtxt);

	return;
}


void CEBayISAPIExtension::RewriteCobrandHeaders(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageRewriteCobrandHeaders);

	pApp->RewriteCobrandHeaders(auth);

	*pApp->mpStream << flush;

	MYCATCH("RewriteCobrandHeaders")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::RemoveUserIdCookie(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;
	bool		Success;

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	Success = pApp->RemoveACookie(pCtxt, COOKIE_USERID);
	if (Success)
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
	pApp->SetCurrentPage(PageRemoveUserIdCookie);

	pApp->RemoveUserIdCookie(pCtxt, Success);
	 
	*pApp->mpStream << flush;

	MYCATCH("RemoveUserIdCookie")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::ChangeCobrandHeader(CHttpServerContext *pCtxt,
											  LPCTSTR pNewDescription,
											  int isHeader,
											  int pageType,
											  int partnerId,
											  int pageType2,
											  int siteId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress(pNewDescription, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageChangeCobrandHeader);

	pApp->ChangeCobrandHeader(auth,
						(const char *) pNewDescription,
						isHeader,
						pageType,
						partnerId,
						pageType2,
						siteId);

	*pApp->mpStream << flush;

	MYCATCH("ChangeCobrandHeader")

	EndContent(pCtxt);

	return;
}

// nsacco 06/21/99 added siteId and pParsedString
void CEBayISAPIExtension::CreateCobrandPartner(CHttpServerContext *pCtxt,
							LPCTSTR pName, 
							LPCTSTR pDesc,
							int siteId,
							LPCTSTR pParsedString)
{
	clseBayApp *pApp;
	eBayISAPIAuthEnum auth;

	if (!AfxIsValidAddress(pName, 1, false) ||
		!AfxIsValidAddress(pDesc, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);


	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageCreateCobrandPartner);

	// nsacco 06/21/99 added siteId and pParsedString
	pApp->CreateCobrandPartner(auth,
								(const char *) pName,
								(const char *) pDesc, 
								siteId,
								(const char *) pParsedString);

	*pApp->mpStream << flush;

	MYCATCH("CreateCobrandPartner");

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::ShowEmailAuctionToFriend(CHttpServerContext *pCtxt, int item )									   
{
	clseBayApp	*pApp;

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d ShowEmailAuctionToFriend %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageShowEmailAuctionToFriend);

	pApp->ShowEmailAuctionToFriend((CEBayISAPIExtension *)this, item);
	*pApp->mpStream << flush;

	MYCATCH("ShowEmailAuctionToFriend")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::EmailAuctionToFriend(CHttpServerContext *pCtxt,
				int item, 
				char *userid,
				char *password,
	//			char *friendname,
				char *email,
				char *message,
				char *htmlenable)									   
{
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(userid, 1, false)) ||
		(!AfxIsValidAddress(password, 1, false)) ||
		(!AfxIsValidAddress(email, 1, false)) ||
		(!AfxIsValidAddress(htmlenable, 1, false)) ||
		(!AfxIsValidAddress(message, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d EmailAuctionToFriend %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), item);


	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageEmailAuctionToFriend);	 
	//pApp->EmailAuctionToFriend((CEBayISAPIExtension *)this, item, userid, password, friendname, email, message, htmlenable);		
	pApp->EmailAuctionToFriend((CEBayISAPIExtension *)this, item, userid, password, email, message, htmlenable);		
	*pApp->mpStream << flush;
	MYCATCH("EmailAuctionToFriend")
	EndContent(pCtxt);
	return;
}

void CEBayISAPIExtension::GetUserByAlias(CHttpServerContext *pCtxt,
								   LPTSTR pRequestedUserId,
								   LPTSTR pRequestorUserId,
								   LPTSTR pRequestorPass)
{
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(pRequestedUserId, 1, false)) ||
		(!AfxIsValidAddress(pRequestorUserId, 1, false)) ||
		(!AfxIsValidAddress(pRequestorPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d GetUserByAlias %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pRequestedUserId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	
	MYTRY
		pApp->SetCurrentPage(PageReturnUserIdHistory);

		pApp->GetUserByAlias((CEBayISAPIExtension *)this, 
						   (char *) pRequestedUserId,
						   (char *) pRequestorUserId,
						   (char *) pRequestorPass);

		*pApp->mpStream << flush;

	MYCATCH("GetUserByAlias")

	EndContent(pCtxt);
}


int CEBayISAPIExtension::AdminInvalidateList(CHttpServerContext* pCtxt,
										LPTSTR pUser, int code)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId(pUser))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminInvalidateList %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageInvalidateList);

		pApp->AdminInvalidateList((CEBayISAPIExtension *)this, 
							   (char *)pUser, code);
		*pApp->mpStream << flush;

	MYCATCH("AdminInvalidateList")

	EndContent(pCtxt);

	return callOK;
}

void CEBayISAPIExtension::AdminBoardChangeShow(CHttpServerContext *pCtxt,
											   LPCTSTR pName)									   
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pName, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d AdminBoardChangeShow %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pName);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminBoardChangeShow);	 
	pApp->AdminBoardChangeShow((CEBayISAPIExtension *)this, 
							   pName,
							   auth);		
	*pApp->mpStream << flush;
	MYCATCH("AdminBoardChangeShow")
	EndContent(pCtxt);
	return;
}

void CEBayISAPIExtension::AdminBoardChange(CHttpServerContext *pCtxt,
										   LPCTSTR pBoardName,
										   LPCTSTR pBoardShortName,
										   LPCTSTR pBoardShortDesc,
										   LPCTSTR pBoardPicture,
										   int maxPostCount,
										   int maxPostAge,
										   LPCTSTR pBoardDesc,
										   int	boardPostable,
										   int	boardAvailable)									   
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress(pBoardName, 1, false)			||
		!AfxIsValidAddress(pBoardShortName, 1, false)		||
		!AfxIsValidAddress(pBoardShortDesc, 1, false)		||
		!AfxIsValidAddress(pBoardPicture, 1, false)			||
		!AfxIsValidAddress(pBoardDesc, 1, false)				)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d AdminBoardChange %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pBoardName, pBoardShortName);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminBoardChange);	 
	pApp->AdminBoardChange((CEBayISAPIExtension *)this, 
						   pBoardName,
						   pBoardShortName,
						   pBoardShortDesc,
						   pBoardPicture,
						   maxPostCount,
						   maxPostAge,
						   pBoardDesc,
						   boardPostable,
						   boardAvailable,
						   auth);		
	*pApp->mpStream << flush;
	MYCATCH("AdminBoardChange")
	EndContent(pCtxt);
	return;
}


void CEBayISAPIExtension::PassRecognizer(CHttpServerContext *pCtxt,									  									  								      
									  char *userid, int code)									  
{
	char		hostAddr[256];
	DWORD		hostAddrSize	= sizeof(hostAddr);
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(userid, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	// First, let's get the host name, since we'll need
	// it
	(pCtxt->m_pECB->GetServerVariable)(pCtxt->m_pECB->ConnID,
										"REMOTE_ADDR",
										&hostAddr,
										&hostAddrSize);
	StartContent(pCtxt);

	ISAPITRACE("0x%x %d PassRecognizer %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), userid);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PagePassRecognizer);	 	
	pApp->PassRecognizer((CEBayISAPIExtension *)this, 
		userid, 
		code,
		(char *)hostAddr);		
	*pApp->mpStream << flush;
	MYCATCH("PassRecognizer")
	EndContent(pCtxt);
	return;
}



void CEBayISAPIExtension::ChangeSecretPassword(CHttpServerContext *pCtxt,									  									  								      
									  char *pass)
{
	clseBayApp	*pApp;


	// Sanity
	if ((!AfxIsValidAddress(pass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	StartContent(pCtxt);

	ISAPITRACE("0x%x %d ChangeSecretPassword %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pass);
	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageChangeSecretPassword);	 	
	pApp->ChangeSecretPassword((CEBayISAPIExtension *)this, pass);		
	*pApp->mpStream << flush;
	MYCATCH("ChangeSecretPassword")
	EndContent(pCtxt);
	return;
}



int CEBayISAPIExtension::ChangePasswordCrypted(CHttpServerContext *pCtxt,
										LPTSTR pUserId,
										LPTSTR pPass,
										LPTSTR pNewPass,
										LPTSTR pNewPass2)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(pUserId,		1,	false)	||
		!AfxIsValidAddress(pPass,		1,	false)	||
		!AfxIsValidAddress(pNewPass,	1,	false)	||
		!AfxIsValidAddress(pNewPass2,	1,	false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	ISAPITRACE("0x%x %d ChangePasswordCrypted %s %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pNewPass, pNewPass2);

	StartContent(pCtxt);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ChangePasswordCrypted", pUserId, true))
	{
		EndContent(pCtxt);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageChangePasswordCrypted);
	 
		pApp->ChangePasswordCrypted((CEBayISAPIExtension *)this, 
							 (char *) pUserId,
							 (char *) pPass,
							 (char *) pNewPass,
							 (char *) pNewPass2);
		*pApp->mpStream << flush;

	MYCATCH("ChangePasswordCrypted")

	EndContent(pCtxt);

	return callOK;

}

extern int gPartnersVersion;

void CEBayISAPIExtension::UpdateCobrandCaching(CHttpServerContext *pCtxt)
{
	const char *pResp = "204 No Content\r\n\r\n";
	unsigned long length = strlen(pResp);

	++gPartnersVersion;

	// Send something back saying we're not sending anything back (!)
	// so that browsers aren't confused.
	((CHttpServerContext *)pCtxt)->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER,
		(void *) pResp, &length, NULL);

	return;
}


void CEBayISAPIExtension::RegisterLinkButtons(CHttpServerContext *pCtxt,
								   LPTSTR pUserid,
								   LPTSTR pPassword,
								   int pHomepage,
								   int pMypage,
								   LPTSTR pUrls)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pUserid, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)) ||
		(!AfxIsValidAddress(pUrls, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d RegisterLinkButtons %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserid);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageRegisterLinkButtons);	 
		pApp->RegisterLinkButtons((CEBayISAPIExtension *)this, 
						   (char *) pUserid,
						   (char *) pPassword,
									pHomepage,
									pMypage,
						   (char *) pUrls);


		*pApp->mpStream << flush;

	MYCATCH("RegisterLinkButtons")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::OptinLogin(CHttpServerContext *pCtxt,
								   LPTSTR pUserid,
								   LPTSTR pPassword)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pUserid, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d OptinLogin %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserid);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageOptinLogin);	 
		pApp->OptinLogin((CEBayISAPIExtension *)this, 
						   (char *) pUserid,
						   (char *) pPassword);


	*pApp->mpStream << flush;

	MYCATCH("OptinLogin")

	EndContent(pCtxt);
}


void goupper(char *in) {

	int i;
	if (in==0) return;
	for (i=0;i<strlen(in);i++)
		in[i]=toupper(in[i]);

}

#define CHECKED(x)	(!strcmp(x,"on"))

char seps[]   = " ,\t\n";

void CEBayISAPIExtension::IIS_Server_status_broadcast(CHttpServerContext *pCtxt, 
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
						    LPTSTR pUserid,  LPTSTR pPassword)
{
   extern bool gIIS_Server_is_down_flag;
	//char *newURL;
   FILE *stream;   
   char line[200], *pool, *machine, *param1, *param2;
   char outage_login[30], outage_password[30];
   clseBayApp	*pApp;


	ISAPITRACE("CEBayISAPIExtension::IIS_Server_status_broadcast\n");
	goupper(cgi);
	goupper(cgi1);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


   if ( (stream = fopen( "c:\\bin\\machine.txt", "r" )) != NULL )   {
	  while ( fgets( line, 200, stream ) != NULL) {
		param1 = strtok( line, seps );
		goupper(param1);
		param2 = strtok( NULL, seps );
		if (!param1 || !param2) continue;
		if (!strcmp("LOGIN",param1)) strcpy(outage_login,	param2);
		if (!strcmp("PASSWORD",param1)) strcpy(outage_password, param2);
	  }
   fclose( stream );
   }

   if (strcmp(outage_password, pPassword) ||
	   strcmp(outage_login, pUserid) ) {
 	   *pApp->mpStream <<"<br>\n"
					<<"<HTML>"
					<<"<HEAD>"
   					<<"<TITLE>Outage Message</TITLE>"
					<<"</HEAD>"
					<<"<body bgcolor=\"#FFFFFF\">"
					<<"<form method=\"post\" action=\"C:\\bin\\outagecontrol.htm\">\n"
					<<"<H1>Sorry, Admin Machine User ID or Password is incorrect. <br>Please retry.</H1><br>\n"
					<<"<br><br><table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"590\">"
					<<"<tr><td><p><blockquote>&nbsp; "
					<<"<input type=submit value=\"Return to Outage Manager\">&nbsp<p></p>"
					<<"</blockquote></td></tr></table><br>"
					<<"</form>"
					// nsacco 08/05/99
					<<"</body></html>";
       *pApp->mpStream << flush;
	   EndContent(pCtxt);
       return;
   }

	*pApp->mpStream <<"\n"
					<<"<HTML>"
					<<"<HEAD>"
   					<<"<TITLE>Outage Message</TITLE>"
					<<"</HEAD>"
					<<"<body bgcolor=\"#FFFFFF\">"
					<<"<form method=\"post\" action=\"C:\\bin\\outagecontrol.htm\">\n"
					<<"<H1>Below are machine sent with outage message:</H1><br>\n";

   
   if ( (stream = fopen( "c:\\bin\\machine.txt", "r" )) != NULL )   {
	  while ( fgets( line, 200, stream ) != NULL) {
		if (line[0]=='#') continue;

		pool=0;
		machine=0;
		machine = strtok( line, seps );
		goupper(machine);
		pool = strtok( NULL, seps );
		goupper(pool);
		if (!pool || !machine) continue;

			if ((!strcmp(allcgi, "out") || !strcmp(allcgi,"ok")) &&
				(!strcmp("CGI",pool) || !strcmp("CGI1",pool) || !strcmp("CGI2",pool) || !strcmp("CGI3",pool) ||
				 !strcmp("CGI4",pool)|| !strcmp("CGI5",pool) || !strcmp("CGI6",pool) || !strcmp("CGI7",pool) ||
				 !strcmp("CGI8",pool) || !strcmp("CGI9",pool) || !strcmp("CGI10",pool)) ) {
				send_server_msg(pCtxt, pApp, allcgi, allcgi_select, allcgi_message, pool,machine);	  
				}
			else if ((!strcmp(maintenance, "out") || !strcmp(maintenance,"ok")) &&
				(!strcmp("CGI",pool) || !strcmp("CGI1",pool) || !strcmp("CGI2",pool) || !strcmp("CGI3",pool) ||
				 !strcmp("CGI4",pool)|| !strcmp("CGI5",pool) || !strcmp("CGI6",pool) || !strcmp("CGI7",pool) ||
				 !strcmp("CGI8",pool) || !strcmp("CGI9",pool) || !strcmp("CGI10",pool)) ) {
				send_server_msg(pCtxt, pApp, maintenance, maintenance_select, maintenance_message, pool,machine);	  
				}
			else if ((!strcmp(python, "out") || !strcmp(python,"ok")) &&
				(!strcmp("CGI",pool) || !strcmp("CGI1",pool) || !strcmp("CGI2",pool) || !strcmp("CGI3",pool) ||
				 !strcmp("CGI4",pool)|| !strcmp("CGI5",pool) || !strcmp("CGI6",pool) || !strcmp("CGI7",pool) ||
				 !strcmp("CGI8",pool) || !strcmp("CGI9",pool) || !strcmp("CGI10",pool)) ) {
				send_server_msg(pCtxt, pApp, python, python_select, python_message, pool,machine);	  
				}
			else {

			if (!strcmp("CGI",pool))
				send_server_msg(pCtxt, pApp, cgi,cgi_select,cgi_message,pool,machine);	  

			if (!strcmp("CGI1",pool)) 
				send_server_msg(pCtxt, pApp, cgi1,cgi1_select,cgi1_message,pool,machine);	  

			if (!strcmp("CGI2",pool)) 
				send_server_msg(pCtxt, pApp, cgi2,cgi2_select,cgi2_message,pool,machine);	  

			if (!strcmp("CGI3",pool)) 
				send_server_msg(pCtxt, pApp, cgi3,cgi3_select,cgi3_message,pool,machine);	  

			if (!strcmp("CGI4",pool)) 
				send_server_msg(pCtxt, pApp, cgi4,cgi4_select,cgi4_message,pool,machine);	  

			if (!strcmp("CGI5",pool)) 
				send_server_msg(pCtxt, pApp, cgi5,cgi5_select,cgi5_message,pool,machine);	  

			if (!strcmp("CGI6",pool)) 
				send_server_msg(pCtxt, pApp, cgi6,cgi6_select,cgi6_message,pool,machine);	  

			if (!strcmp("CGI7",pool)) 
				send_server_msg(pCtxt, pApp, cgi7,cgi7_select,cgi7_message,pool,machine);	  

			if (!strcmp("CGI8",pool)) 
				send_server_msg(pCtxt, pApp, cgi8,cgi8_select,cgi8_message,pool,machine);	  

			if (!strcmp("CGI9",pool)) 
				send_server_msg(pCtxt, pApp, cgi9,cgi9_select,cgi9_message,pool,machine);	  

			if (!strcmp("CGI10",pool)) 
				send_server_msg(pCtxt, pApp, cgi10,cgi10_select,cgi10_message,pool,machine);	  
			
			}
			
			if (!strcmp("MAINTENANCE",pool)) 
				send_server_msg(pCtxt, pApp, maintenance,maintenance_select ,maintenance_message , pool,machine);

			if (!strcmp("PYTHON",pool)) 
				send_server_msg(pCtxt, pApp, python ,python_select ,python_message , pool,machine);

			if (!strcmp("MEMBERS",pool)) 
				send_server_msg(pCtxt, pApp, members,members_select,members_message, pool,machine);

			if (!strcmp("LISTINGS",pool)) 
				send_server_msg(pCtxt, pApp, listings,listings_select,listings_message, pool,machine);

			if (!strcmp("SEARCH",pool)) 
				send_server_msg(pCtxt, pApp, search ,search_select ,search_message ,pool,machine);

			if (!strcmp("PAGES",pool)) 
				send_server_msg(pCtxt, pApp, pages ,pages_select ,pages_message ,pool,machine);

			if (!strcmp("COBRAND",pool)) 
				send_server_msg(pCtxt, pApp, cobrand,cobrand_select,cobrand_message, pool,machine);

			if (!strcmp("SITESEARCH",pool)) 
				send_server_msg(pCtxt, pApp, sitesearch,sitesearch_select,sitesearch_message, pool,machine);

			if (!strcmp("FUTURE1",pool))
				send_server_msg(pCtxt, pApp, future1,future1_select,future1_message, pool,machine);

			if (!strcmp("FUTURE2",pool)) 
				send_server_msg(pCtxt, pApp, future2,future2_select,future2_message, pool,machine);
	  
			if (!strcmp("FUTURE3",pool))
				send_server_msg(pCtxt, pApp, future3,future3_select,future3_message, pool,machine);
	  
			if (!strcmp("FUTURE4",pool)) 
				send_server_msg(pCtxt, pApp, future4,future4_select,future4_message, pool,machine);

			if (!strcmp("FUTURE5",pool)) 
				send_server_msg(pCtxt, pApp, future5,future5_select,future5_message, pool,machine);
	  
			if (!strcmp("FUTURE6",pool)) 
				send_server_msg(pCtxt, pApp, future6,future6_select,future6_message, pool,machine);

			if (!strcmp("FUTURE7",pool)) 
				send_server_msg(pCtxt, pApp, future7,future7_select,future7_message, pool,machine);

			if (!strcmp("FUTURE8",pool)) 
				send_server_msg(pCtxt, pApp, future8,future8_select,future8_message, pool,machine);

			if (!strcmp("FUTURE9",pool)) 
				send_server_msg(pCtxt, pApp, future9,future9_select,future9_message, pool,machine);

			if (!strcmp("FUTURE10",pool)) 
				send_server_msg(pCtxt, pApp, future10,future10_select,future10_message, pool,machine);
	  } 
   fclose( stream );
   }

	//pApp->CleanUp();
	*pApp->mpStream <<"<br><br><table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"590\">"
					<<"<tr><td><p><blockquote>&nbsp; "
					<<"<input type=submit value=\"Return to Outage Manager\">&nbsp<p></p>"
					<<"</blockquote></td></tr></table><br>"
					<<"</form>"
					// nsacco 08/05/99
					<<"</body></html>";
   
   *pApp->mpStream << flush;
	EndContent(pCtxt);

	
	return;
}

//#define TEST_LOCAL_MACHINE
void CEBayISAPIExtension::send_server_msg(CHttpServerContext *pCtxt,
									      clseBayApp	*pApp,
										  char *server_name, 
										  char *delay_time, 
										  char *outage_msg, 
										  char *pool, 
										  char *machine) 
{

	//char *newURL, *summary_msg;
	char new_delay_time[512], new_outage_msg[512];
	char newURL[512], summary_msg[512];
	int i;

	goupper(server_name);
	if (strcmp(server_name, "OUT") && strcmp(server_name,"OK")) return;
//	newURL = (char *)malloc(200);
//	summary_msg = (char *)malloc(200);

	strcpy(new_delay_time, delay_time);
	for (i=0;new_delay_time[i]!='\0';i++) 
		if (new_delay_time[i]==' ') new_delay_time[i]='+';

	strcpy(new_outage_msg, outage_msg);
	for (i=0;new_outage_msg[i]!='\0';i++) 
		if (new_outage_msg[i]==' ') new_outage_msg[i]='+';

#ifdef TEST_LOCAL_MACHINE
	sprintf(newURL, "http://%s/aw-cgi/eBayISAPI.dll?IIS_Server_status&pIIS_Server_status=%d&pTimeDelay=%s&pOperatorMessage=%s"
			, machine, strcmp(server_name,"OUT")?1:0, new_delay_time, new_outage_msg);

	EbayRedirect(pCtxt, newURL);
	sprintf(summary_msg, "Pool %s, Machine %s, Status %s"
			, pool, machine, server_name);
	
#else
	int systemReturn;
	sprintf(newURL, "c:\\bin\\wget -t 10 -o x -O dontcare -T 5 \"http://%s/aw-cgi/eBayISAPI.dll?IIS_Server_status&pIIS_Server_status=%d&pTimeDelay=%s&pOperatorMessage=%s\""
		,machine,strcmp(server_name,"OUT")?1:0, new_delay_time, new_outage_msg);
	systemReturn = system(newURL);
//	systemReturn = 0;
	sprintf(summary_msg, "Pool %s, Machine %s, Status %s, Send return code (0 success, 1 fail)=%d"
			, pool, machine, server_name, systemReturn);
	
#endif

	*pApp->mpStream <<"<br>\n";
	*pApp->mpStream << summary_msg;
	*pApp->mpStream <<"<br>\n";

//	free(newURL);
//	free(summary_msg);
}


void CEBayISAPIExtension::IIS_Server_status(CHttpServerContext *pCtxt, 
								   int pIIS_Server_status,
								   LPTSTR pTimeDelay,
								   LPTSTR pOperatorMessage)
{
//	clseBayApp	*pApp;
	extern bool gIIS_Server_is_down_flag;

	ISAPITRACE("0x%x %d IIS_Server_status %d %.20s %.20s\n",
 				GetCurrentThreadId(), GetCurrentThreadId(),
 				pIIS_Server_status, pTimeDelay, pOperatorMessage);

	if (pIIS_Server_status==0) {
// HACK.. CGI Down feature turned off for the moment
//			gIIS_Server_is_down_flag=true; 
			strcpy(IIS_Server_down_time,pTimeDelay); 
			strcpy(IIS_Server_down_message,pOperatorMessage); 
	}
	else {
			gIIS_Server_is_down_flag=false; 
			strcpy(IIS_Server_down_message," Our Database is down.  This screen is automatically generated.  Soon our operators will input more informed messages.");
			strcpy(IIS_Server_down_time,"Our operator has not determine when service will be back up.");
	}

    return;
}


// kaz: 04/18/99 Renamed from OptinSave as part of a cleanup
void CEBayISAPIExtension::OptinConfirm(CHttpServerContext *pCtxt,
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
							int DailyStatusOption)
{
	clseBayApp	*pApp;

	// Sanity
	if ((!AfxIsValidAddress(pUserid, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d OptinConfirm %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserid);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageOptinConfirm);	 

		pApp->OptinConfirm((CEBayISAPIExtension *)this, 
						   (char *) pUserid,
							ChangesToAgreementOption,
							ChangesToPrivacyOption,
							TakePartInSurveysOption,
							SpecialOfferOption,
							EventPromotionOption,
							NewsletterOption,
							EndofAuctionOption,
							BidOption,
							OutBidOption,
							ListOption,
							DailyStatusOption);

		*pApp->mpStream << flush;

	MYCATCH("OptinConfirm")

	EndContent(pCtxt);
}	// OptinConfirm

void CEBayISAPIExtension::RegistrationAcceptAgreement(CHttpServerContext *pCtxt,
											int notifySelected,
											LPSTR accept,
											LPSTR decline,
											int countryId)											  
{
	clseBayApp	*pApp;
	bool agree = false;
	bool notify = false;


	// Sanity
	if ((!AfxIsValidAddress(accept, 1, false)) ||
		(!AfxIsValidAddress(decline, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d RegistrationAcceptAgreement\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageRegistrationAcceptAgreement);

// petra		if (accept && strcmp(accept, "I Accept") == 0)
		if (decline && strcmp(decline, "default") == 0)	// petra
			agree = true;
		if (notifySelected == 1)
			notify = true;

		pApp->RegistrationAcceptAgreement((CEBayISAPIExtension *)this, 
						agree,
						notify,
						countryId);

		*pApp->mpStream << flush;

	MYCATCH("RegistrationAcceptAgreement")

	EndContent(pCtxt);

}

void CEBayISAPIExtension::CCRegistrationAcceptAgreement(CHttpServerContext *pCtxt,
											int notifySelected,
											LPSTR accept,
											LPSTR decline)											  
{
	clseBayApp	*pApp;
	bool agree = false;
	bool notify = false;

	// Sanity
	if ((!AfxIsValidAddress(accept, 1, false)) ||
		(!AfxIsValidAddress(decline, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d CCRegistrationAcceptAgreement\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageCCRegistrationAcceptAgreement);

// petra		if (accept && strcmp(accept, "I Accept") == 0)
		if (decline && strcmp(decline, "default") == 0)	// petra
			agree = true;
		if (notifySelected == 1)
			notify = true;

		pApp->CCRegistrationAcceptAgreement((CEBayISAPIExtension *)this, 
						agree,
						notify);

		*pApp->mpStream << flush;

	MYCATCH("CCRegistrationAcceptAgreement")

	EndContent(pCtxt);

}


void CEBayISAPIExtension::AdultLoginShow(CHttpServerContext *pCtxt, int whichText)
{
	clseBayApp *pApp;
	
	ISAPITRACE("0x%x %d AdultLoginShow %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(), whichText);

	pApp = (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	// Notice -- no start content call just yet. We'll do the cookies first.

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdultLoginShow);

		StartContent(pCtxt);
		pApp->AdultLoginShow(whichText);
		*pApp->mpStream << flush;

	MYCATCH("AdultLoginShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdultLogin(CHttpServerContext *pCtxt,
									 LPSTR userid,
									 LPSTR password)
{
	clseBayApp *pApp;


	// Sanity
	if ((!AfxIsValidAddress(userid, 1, false)) ||
		(!AfxIsValidAddress(password, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}
	
	ISAPITRACE("0x%x %d AdultLogin\n",
		GetCurrentThreadId(), GetCurrentThreadId());

	pApp = (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	// Notice -- no start content call just yet. We'll do the cookies first.

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdultLogin);

		pApp->AdultLogin((char *) userid,
			(char *) password);

		StartContent(pCtxt);

		pApp->ShowAdultLogin((char *) userid,
			(char *) password);

		*pApp->mpStream << flush;

	MYCATCH("AdultLogin")

	EndContent(pCtxt);
}

// kaz: 4/7/99: Support for Police Badge T&C
void CEBayISAPIExtension::PoliceBadgeLoginForSelling(CHttpServerContext *pCtxt,
					  LPSTR userid,
					  LPSTR password,
					  LPSTR pAgree,
					  LPSTR pDontAgree)
{
	clseBayApp		*pApp;
	bool			agree = false;

	// Sanity
	if ((!AfxIsValidAddress(userid, 1, false)) ||
		(!AfxIsValidAddress(password, 1, false)) ||
		(!AfxIsValidAddress(pAgree, 1, false)) ||
		(!AfxIsValidAddress(pDontAgree, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PoliceBadgeLoginForSelling\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PagePoliceBadgeLogin);

// petra		if (pAgree && strcmp(pAgree, "I Accept") == 0)
		if (pDontAgree && strcmp(pDontAgree, "default") == 0)	// petra
			agree = true;
		
		// This verifies the user, updates the flag, and outputs a success | fail message
		pApp->PoliceBadgeLogin((char *) userid, (char *) password, agree);

		*pApp->mpStream << flush;

	MYCATCH("PoliceBadgeLoginForSelling")

	EndContent(pCtxt);
}	// PoliceBadgeLoginForSelling


void CEBayISAPIExtension::UserAgreementAccept(CHttpServerContext *pCtxt,
											LPSTR userid,
											LPSTR password,
											int notifySelected,
											LPSTR pAgree,
											LPSTR pDontAgree)											  
{
	clseBayApp	*pApp;
	bool agree = false;
	bool notify = false;


	// Sanity
	if ((!AfxIsValidAddress(userid, 1, false)) ||
		(!AfxIsValidAddress(password, 1, false)) ||
		(!AfxIsValidAddress(pAgree, 1, false)) ||
		(!AfxIsValidAddress(pDontAgree, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d UserAgreementAccept\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserAgreementAccept);

//		if (pAgree && strcmp(pAgree, "I Accept") == 0)
		if (pDontAgree && strcmp(pDontAgree, "default") == 0)
			agree = true;
		if (notifySelected == 1)
			notify = true;

		pApp->UserAgreementAccept((CEBayISAPIExtension *)this, 
						(char *) userid,
						(char *) password,
						agree,
						notify);

		*pApp->mpStream << flush;

	MYCATCH("UserAgreementAccept")

	EndContent(pCtxt);

}

// Do not edit the following lines, which are needed by ClassWizard.
#if 0
BEGIN_MESSAGE_MAP(CEBayISAPIExtension, CHttpServer)
	//{{AFX_MSG_MAP(CEBayISAPIExtension)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()
#endif	// 0



///////////////////////////////////////////////////////////////////////
// If your extension will not use MFC, you'll need this code to make
// sure the extension objects can find the resource handle for the
// module.  If you convert your extension to not be dependent on MFC,
// remove the comments arounn the following AfxGetResourceHandle()
// and DllMain() functions, as well as the g_hInstance global.

/****

static HINSTANCE g_hInstance;

HINSTANCE AFXISAPI AfxGetResourceHandle()
{
	return g_hInstance;
}

BOOL WINAPI DllMain(HINSTANCE hInst, ULONG ulReason,
					LPVOID lpReserved)
{
	if (ulReason == DLL_PROCESS_ATTACH)
	{
		g_hInstance = hInst;
	}

	return TRUE;
}

****/
 

int CEBayISAPIExtension::TimeShow(CHttpServerContext *pCtxt)
{
   	clseBayApp	*pApp;
	
   	ISAPITRACE("0x%x %d TimeShow\n",
		GetCurrentThreadId(), GetCurrentThreadId());
	
   	StartContent(pCtxt);	
	
   	pApp	= (clseBayApp *)GetApp();
   	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageTimeShow);
	 
		pApp->TimeShow((CEBayISAPIExtension *)this);

		*pApp->mpStream << flush;

	MYCATCH("TimeShow")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;
}

//inna 
int CEBayISAPIExtension::AdminRebalanceUserAccount(CHttpServerContext* pCtxt,
									 LPTSTR pUserId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pUserId, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminRebalanceUserAccount %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);


	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminRebalanceUserAccount);

	pApp->AdminRebalanceUserAccount((CEBayISAPIExtension *)this,
					  pUserId,
					  auth);
	*pApp->mpStream << flush;

	MYCATCH("AdminRebalanceUserAccount")

	EndContent(pCtxt);

	return callOK;
}
//end inna
//inna 
int CEBayISAPIExtension::AdminRemoveItem(CHttpServerContext* pCtxt,
												LPTSTR pItemNo)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;


	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminRemoveItem %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminRemoveItem);

	pApp->AdminRemoveItem((CEBayISAPIExtension *)this,
					   pItemNo, auth);

	MYCATCH("AdminRemoveItem");

	EndContent(pCtxt);

	return callOK;

} 
//end inna
//inna 
int CEBayISAPIExtension::WackoFlagChangeConfirm(CHttpServerContext* pCtxt,
												LPTSTR pItemNo,
												int wackoFlag)


{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d WackoFlagChangeConfirm %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, wackoFlag);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageWackoFlagChangeConfirm);

	pApp->WackoFlagChangeConfirm((CEBayISAPIExtension *)this,
						pItemNo,
						(wackoFlag != 0 ? true : false),
						auth);

	*pApp->mpStream << flush;

	MYCATCH("WackoFlagChangeConfirm")

	EndContent(pCtxt);

	return callOK;

} 

int CEBayISAPIExtension::WackoFlagChange(CHttpServerContext* pCtxt,
												LPTSTR pItemNo,
												int wackoFlag)
{
	clseBayApp	*pApp;

	//eBayISAPIAuthEnum	auth;

	// Sanity
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d WackoFlagChange %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, wackoFlag);

	//we do not need to check autorization 
	//this is called from dynamic page and autorization was checked in 
	//the method that calls thsi function
	//auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageWackoFlagChange);

	pApp->WackoFlagChange((CEBayISAPIExtension *)this,
						pItemNo,
						(wackoFlag != 0 ? true : false));

	*pApp->mpStream << flush;

	MYCATCH("WackoFlagChange")

	EndContent(pCtxt);

	return callOK;

} 
//end inna
int CEBayISAPIExtension::RegisterPreview(CHttpServerContext *pCtxt,
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
								  int referral,
								  LPTSTR pTradeshow_source1,
								  LPTSTR pTradeshow_source2,
								  LPTSTR pTradeshow_source3,
								  LPTSTR pFriend_email,
								  int purpose,
								  int interested_in,
								  int age,
								  int education,
								  int income,
								  int survey,
								  int UsingSSL,
								  int siteId,	// nsacco 07/02/99
								  int coPartnerId
								  )
{
	clseBayApp	*pApp;
	char *pStr;
	char cookieBuffer[4096];
	unsigned long cookieLength;
	int partnerId;

	ISAPITRACE("0x%x %d Register %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false)	||
		!AfxIsValidAddress(pEmail,		 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pCompany,  	 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pAddress,	 1,	false)	||
		!AfxIsValidAddress(pCity,		 1,	false)	||
		!AfxIsValidAddress(pState,		 1,	false)	||
		!AfxIsValidAddress(pZip,		 1,	false)	||
		!AfxIsValidAddress(pCountry,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	 1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	 1,	false)	||
		!AfxIsValidAddress(pGender,		 1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,	1,	false) ||
		!AfxIsValidAddress(pFriend_email,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	cookieLength = 4095;
		if (pCtxt->GetServerVariable("HTTP_COOKIE", cookieBuffer, &cookieLength))
		{
			// Already set. Return.
			pStr = strstr(cookieBuffer, "p=");
			if (pStr && ((pStr == cookieBuffer) || isspace(*(pStr - 1))))
			{
				partnerId = atoi(pStr + 2);
			}
			else
			{
				partnerId = 0;
			}
		}
		else
		{
			partnerId = 0;
		}
		pApp->SetCurrentPage(PageRegisterPreview);

		pApp->RegisterPreview((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,
					   referral,
					   (char *) pTradeshow_source1,
					   (char *) pTradeshow_source2,
					   (char *) pTradeshow_source3,
					   (char *) pFriend_email,
					   purpose,
					   interested_in,
					   age,
					   education,
					   income,
					   survey,   
					   partnerId,
					   siteId,	// nsacco 07/02/99
					   coPartnerId,
					   UsingSSL
					   );
		*pApp->mpStream << flush;

	MYCATCH("RegisterPreview")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

//
// Get itemInfo 
//
void CEBayISAPIExtension::GetItemInfo(CHttpServerContext* pCtxt,
										  LPTSTR pItemNo,
										  LPTSTR pUserId,
										  LPTSTR pPass,
										  bool	 oldStyle)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d GetItemInfo %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pUserId, pPass);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	if ((!AfxIsValidAddress(pItemNo, 1, false)) ||
		(!AfxIsValidAddress(pUserId, 1, false))	||
		(!AfxIsValidAddress(pPass, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageGetItemInfo);

	pApp->GetItemInfo(this, (char *)pItemNo, (char *)pUserId, 
					(char *)pPass, (oldStyle==1) );
	*pApp->mpStream << flush;

	MYCATCH("GetItemInfo")

	EndContent(pCtxt);
	
//	PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}


// About Me -- User Pages

// Called to show a user's page.
int CEBayISAPIExtension::ViewUserPage(CHttpServerContext *pCtxt,
                                      LPSTR userid,
                                      int page)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d ViewUserPage\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewUserPage);

        pApp->ViewUserPage((char *) userid,
						   page);

		*pApp->mpStream << flush;

	MYCATCH("ViewUserPage")

	EndContent(pCtxt);
    return callOK;
}

// We may never need to call this directly as an ISAPI funciton.
// This is currently only called in conjunction with editing,
// where the user first confirms they want to remove the page,
// then we take the user to the beginning of the editing process.
// But in case we do need it later, I simply commented it out for now.
#ifdef REMOVE_USER_PAGE_NEEDED
int CEBayISAPIExtension::RemoveUserPage(CHttpServerContext *pCtxt,
                                        LPSTR userid,
                                        LPSTR password,
                                        int page)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d RemoveUserPage\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
		pApp->SetCurrentPage(PageRemoveUserPage);

        pApp->RemoveUserPage((char *) userid,
							 (char *) password,
							 page);

		*pApp->mpStream << flush;

	MYCATCH("RemoveUserPage")

	EndContent(pCtxt);
    return callOK;
}
#endif



// Organizes user pages into categories.
void CEBayISAPIExtension::CategorizeUserPage(CHttpServerContext *pCtxt,
                                             LPSTR pUserId,
                                             LPSTR pPassword,
                                             LPSTR pTitle,
                                             int remove,
                                             int category,
                                             int page)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d CategorizeUserPage\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageSaveUserPage);

        pApp->CategorizeUserPage((char *) pUserId,
            (char *) pPassword,
            (char *) pTitle,
            remove != 0,
            category,
            page);

		*pApp->mpStream << flush;

	MYCATCH("CategorizeUserPage")

	EndContent(pCtxt);
    return;
}

// Log in to begin creating and editing your user page.
void CEBayISAPIExtension::UserPageLogin(CHttpServerContext *pCtxt,
                                       LPSTR pUserId,
                                       LPSTR pPassword)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageLogin\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

        pApp->UserPageLogin((CEBayISAPIExtension *)this, 
							(char *) pUserId,
						    (char *) pPassword);

		*pApp->mpStream << flush;

	MYCATCH("UserPageLogin")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::UserPageAcceptAgreement(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		int   notifySelected,
		LPSTR pAgree,
		LPSTR pDontAgree)
{
	clseBayApp	*pApp;
	bool         agree = false;
	bool         notify;

    ISAPITRACE("0x%x %d UserPageAcceptAgreement\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

// petra		if (pAgree && strcmp(pAgree, "I Accept") == 0)
		if (pDontAgree && strcmp(pDontAgree, "default") == 0)	// petra
			agree = true;
		if (notifySelected == 1)
			notify = true;

        pApp->UserPageAcceptAgreement((CEBayISAPIExtension *)this, 
							(char *) pUserId,
						    (char *) pPassword,
							agree,
							notify);

		*pApp->mpStream << flush;

	MYCATCH("UserPageLogin")

	EndContent(pCtxt);
    return;
}


//update unbit item infomation for user

// nsacco 07/27/99 added new params
int CEBayISAPIExtension::UpdateItemInfo(CHttpServerContext* pCtxt,
											LPCTSTR pItemNo,
											LPCTSTR pUserId,
											LPCTSTR pPass,
											LPCTSTR pTitle,
										//	LPCTSTR pQuantity, 
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
											)
{
	clseBayApp	*pApp;
	bool			ok;
	char			reDirectURL[512];
//	unsigned long	reDirectURLLen;

		// Sanity Checks
	if (!AfxIsValidAddress(pItemNo, 1, false)				||
		!ValidateUserId((char *)pUserId)					|| 
		!ValidatePassword((char *)pPass)					||
		!AfxIsValidAddress(pTitle, 1, false)				||
//		!AfxIsValidAddress(pQuantity, 1, false)				||
		!AfxIsValidAddress(pDesc, 1, false)					||
		!AfxIsValidAddress(pPicUrl, 1, false)				||
		!AfxIsValidAddress(pCategory, 1, false)				||
		!AfxIsValidAddress(pMoneyOrderAccepted, 1, false)	||
		!AfxIsValidAddress(pPersonalChecksAccepted, 1, false)	||
		!AfxIsValidAddress(pVisaMasterCardAccepted, 1, false)	||
		!AfxIsValidAddress(pDiscoverAccepted, 1, false)			||
		!AfxIsValidAddress(pAmExAccepted, 1, false)				||
		!AfxIsValidAddress(pOtherAccepted, 1, false)			||
		!AfxIsValidAddress(pOnlineEscrowAccepted, 1, false)		||
		!AfxIsValidAddress(pCODAccepted, 1, false)				||
		!AfxIsValidAddress(pPaymentSeeDescription, 1, false)	||
		!AfxIsValidAddress(pSellerPaysShipping, 1, false)		||
		!AfxIsValidAddress(pBuyerPaysShippingFixed, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingActual, 1, false)	||
		!AfxIsValidAddress(pShippingSeeDescription, 1, false)   ||
		// nsacco 07/27/99 check new params
		!AfxIsValidAddress(pShippingInternationally, 1, false) ||
		!AfxIsValidAddress(pShipToNorthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToEurope, 1, false) ||
		!AfxIsValidAddress(pShipToOceania, 1, false) ||
		!AfxIsValidAddress(pShipToAsia, 1, false) ||
		!AfxIsValidAddress(pShipToSouthAmerica, 1, false) ||
		!AfxIsValidAddress(pShipToAfrica, 1, false) 
		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		
	}

	ISAPITRACE("0x%x %d UpdateItemInfo %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				 pItemNo);


	StartContent(pCtxt);
	ok = false;
	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageUpdateItemInfo);

	// nsacco 07/27/99 added new params
	ok = pApp->UpdateItemInfo((CEBayISAPIExtension *)this,
								pItemNo,
								pUserId,
								pPass,
								pTitle,
//								pQuantity,
								pDesc,
								pPicUrl,
								pCategory,
								pMoneyOrderAccepted,
								pPersonalChecksAccepted,
								pVisaMasterCardAccepted,
								pDiscoverAccepted,
								pAmExAccepted,
								pOtherAccepted,
								pOnlineEscrowAccepted,
								pCODAccepted,
								pPaymentSeeDescription,
								pSellerPaysShipping,
								pBuyerPaysShippingFixed,
								pBuyerPaysShippingActual,
								pShippingSeeDescription,
								pShippingInternationally,
								pShipToNorthAmerica,
								pShipToEurope,
								pShipToOceania,
								pShipToAsia,
								pShipToSouthAmerica,
								pShipToAfrica,
								siteId,
								descLang,
								reDirectURL
								);

	*pApp->mpStream << flush;

	MYCATCH("UpdateItemInfo")
	if (ok)
	{

		EbayRedirect(pCtxt, reDirectURL);
/*
		reDirectURLLen	= strlen(reDirectURL);

		pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP,
									 reDirectURL,
									 &reDirectURLLen,
									 (DWORD *)NULL); 
*/
	}
	else
		EndContent(pCtxt);

	return callOK;

} 

// Verify update item
// nsacco 07/27/99 added new params
int CEBayISAPIExtension::VerifyUpdateItem(CHttpServerContext* pCtxt,
										  LPCTSTR pUserId,
										  LPCTSTR pPass,
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
										  )

{

	if (!AfxIsValidAddress(pItemNo, 1, false)					||
		!ValidateUserId((char *)pUserId)						|| 
		!ValidatePassword((char *)pPass)						||
		!AfxIsValidAddress(pTitle, 1, false)					||
//		!AfxIsValidAddress(pQuantity, 1, false)					||
		!AfxIsValidAddress(pDesc, 1, false)						||
		!AfxIsValidAddress(pPicUrl, 1, false)					||
		!AfxIsValidAddress(pCategory1, 1, false)				||
		!AfxIsValidAddress(pCategory2, 1, false)				||
		!AfxIsValidAddress(pCategory3, 1, false)				||
		!AfxIsValidAddress(pCategory4, 1, false)				||
		!AfxIsValidAddress(pCategory5, 1, false)				||
		!AfxIsValidAddress(pCategory6, 1, false)				||
		!AfxIsValidAddress(pCategory7, 1, false)				||
		!AfxIsValidAddress(pCategory8, 1, false)				||
		!AfxIsValidAddress(pCategory9, 1, false)				||
		!AfxIsValidAddress(pCategory10, 1, false)				||
		!AfxIsValidAddress(pCategory11, 1, false)				||
		!AfxIsValidAddress(pCategory12, 1, false)				||
		!AfxIsValidAddress(pMoneyOrderAccepted, 1, false)		||
		!AfxIsValidAddress(pPersonalChecksAccepted, 1, false)	||
		!AfxIsValidAddress(pVisaMasterCardAccepted, 1, false)	||
		!AfxIsValidAddress(pDiscoverAccepted, 1, false)			||
		!AfxIsValidAddress(pAmExAccepted, 1, false)				||
		!AfxIsValidAddress(pOtherAccepted, 1, false)			||
		!AfxIsValidAddress(pOnlineEscrowAccepted, 1, false)		||
		!AfxIsValidAddress(pCODAccepted, 1, false)				||
		!AfxIsValidAddress(pPaymentSeeDescription, 1, false)	||
		!AfxIsValidAddress(pSellerPaysShipping, 1, false)		||
		!AfxIsValidAddress(pBuyerPaysShippingFixed, 1, false)	||
		!AfxIsValidAddress(pBuyerPaysShippingActual, 1, false)	||
		!AfxIsValidAddress(pShippingSeeDescription, 1, false)   ||
		// nsacco 07/27/99 added new params
		!AfxIsValidAddress(pShippingInternationally, 1, false)  ||
		!AfxIsValidAddress(pShipToNorthAmerica, 1, false)  ||
		!AfxIsValidAddress(pShipToEurope, 1, false)  ||
		!AfxIsValidAddress(pShipToOceania, 1, false)  ||
		!AfxIsValidAddress(pShipToAsia, 1, false)  ||
		!AfxIsValidAddress(pShipToSouthAmerica, 1, false)  ||
		!AfxIsValidAddress(pShipToAfrica, 1, false)  
		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		
	}

	ISAPITRACE("0x%x %d VerifyUpdateItem %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				 pItemNo);


	StartContent(pCtxt);
	clseBayApp *pApp = (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);


	MYTRY
		pApp->SetCurrentPage(PageVerifyUpdateItem);

	
		// nsacco 07/27/99 added new params
		pApp->VerifyUpdateItem(pUserId,
								pPass,
								pItemNo,
								pTitle,
								pDesc,
								pPicUrl,
								pCategory1,
								pCategory2,
								pCategory3,
								pCategory4,
								pCategory5,
								pCategory6,
								pCategory7,
								pCategory8,
								pCategory9,
								pCategory10,
								pCategory11,
								pCategory12,
								pMoneyOrderAccepted,
								pPersonalChecksAccepted,
								pVisaMasterCardAccepted,
								pDiscoverAccepted,
								pAmExAccepted,
								pOtherAccepted,
								pOnlineEscrowAccepted,
								pCODAccepted,
								pPaymentSeeDescription,
								pSellerPaysShipping,
								pBuyerPaysShippingFixed,
								pBuyerPaysShippingActual,
								pShippingSeeDescription,
								pShippingInternationally,
								pShipToNorthAmerica,
								pShipToEurope,
								pShipToOceania,
								pShipToAsia,
								pShipToSouthAmerica,
								pShipToAfrica,
								siteId,
								descLang
								);

		*pApp->mpStream << flush;

	MYCATCH("VerifyUpdateItem")
	EndContent(pCtxt);

	return callOK;
}

//
// Get itemInfo and verify the seller
//
void CEBayISAPIExtension::UserItemVerification(CHttpServerContext* pCtxt,
										  LPTSTR pItemNo)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d UserItemVerification %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);



	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}


	MYTRY
	pApp->SetCurrentPage(PageUserItemVerification);

	pApp->UserItemVerification(this, (char *)pItemNo);
	*pApp->mpStream << flush;

	MYCATCH("UserItemVerification")

	EndContent(pCtxt);
	
//	PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}

// Preview your page, converting the raw HTML with widgets to a page.
// The "Edit" button takes you back to HTML editing.
void CEBayISAPIExtension::UserPageGoToHTMLPreview(CHttpServerContext *pCtxt,
									   LPSTR pUserId,
									   LPSTR pPassword,
									   LPSTR pHTML)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageGoToHTMLPreview\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		pApp->UserPageGoToHTMLPreview((CEBayISAPIExtension *)this, 
									  (char *) pUserId,
				                      (char *) pPassword,
								      (char *) pHTML);

		*pApp->mpStream << flush;

	MYCATCH("UserPageGoToHTMLPreview")

	EndContent(pCtxt);
    return;
}

// Decides what to do when the user hits a button the HTML preview page.
void CEBayISAPIExtension::UserPageHandleHTMLPreviewOptions(CHttpServerContext *pCtxt,
									   LPSTR pUserId,
									   LPSTR pPassword,
                                       LPSTR pActionButton1,	// petra
									   LPSTR pActionButton2,	// petra
									   LPSTR pActionButton3,	// petra
									   LPSTR pHTML)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageHandleHTMLPreviewOptions\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

// petra		if (!strcmp(pActionButton, "Edit some more"))
		if ((pActionButton2 && strcmp(pActionButton2, "default") == 0) &&	// petra
			(pActionButton3 && strcmp(pActionButton3, "default") == 0))		// petra
		{
			pApp->UserPageEditFromText((CEBayISAPIExtension *)this, 
									   (char *) pUserId,
				                       (char *) pPassword,
								       (char *) pHTML);
		}
// petra		else if (!strcmp(pActionButton, "Start over"))
		else if ((pActionButton1 && strcmp(pActionButton1, "default") == 0) &&	// petra
				 (pActionButton2 && strcmp(pActionButton2, "default") == 0))	// petra
		{
			pApp->UserPageShowConfirmHTMLEditingChoices(
								  (CEBayISAPIExtension *)this, 
				                  (char *) pUserId,
				                  (char *) pPassword,
								  (char *) pHTML,
								  UserPageHTMLEditingStartOver);
		}
		else /* Save my page */
		{
			pApp->SaveUserPage((CEBayISAPIExtension *)this, 
							   (char *) pUserId,
							   (char *) pPassword,
							   (char *) pHTML,
							   0);
		}

		*pApp->mpStream << flush;

	MYCATCH("UserPageHandleHTMLPreviewOptions")

	EndContent(pCtxt);
    return;
}

// Decides what to do when the user selects a layout style
// when editing in template mode. (Just passes the user along
// to picking the elements in the page at the moment.)
void CEBayISAPIExtension::UserPageHandleStyleOptions(CHttpServerContext *pCtxt,
		LPSTR pUserId,
		LPSTR pPassword,
		LPSTR pActionButton1,	// petra
		LPSTR pActionButton2,	// petra
		LPSTR pActionButton3,	// petra
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
		int   bgPattern)

{
	TemplateElements elements;
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageHandleStyleOptions\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

// petra	if (strcmp(pActionButton, "Two column layout") == 0)
	if ((pActionButton2 && strcmp(pActionButton2, "default") == 0) &&	// petra
		(pActionButton3 && strcmp(pActionButton3, "default") == 0))		// petra
		templateLayout = 0;
// petra 	else if (strcmp(pActionButton, "Newspaper layout") == 0)
	else if ((pActionButton1 && strcmp(pActionButton1, "default") == 0) &&	// petra	
			 (pActionButton3 && strcmp(pActionButton3, "default") == 0))	// petra
		templateLayout = 1;
 	else  // "Centered"
		templateLayout = 2;

	FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments,
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		pApp->UserPageSelectTemplateElements((CEBayISAPIExtension *)this, 
											 (char *) pUserId,
										     (char *) pPassword,
										     &elements);

		*pApp->mpStream << flush;

	MYCATCH("UserPageHandleStyleOptions")

	EndContent(pCtxt);
    return;
}

// Collects the elements the user enters and passes the user along
// to the preview page for templates. Or, allows the user to start
// over by picking a new layout.
void CEBayISAPIExtension::UserPageHandleTemplateOptions(CHttpServerContext *pCtxt,
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
		int   bgPattern)
{
	TemplateElements elements;
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageHandleTemplateOptions\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments,
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

// petra	if (!strcmp(pActionButton, "Preview your page"))
		if (pActionButton2 && strcmp(pActionButton2, "default") == 0)	// petra
		{
			pApp->UserPageGoToTemplatePreview((CEBayISAPIExtension *)this, 
											  (char *) pUserId,
											  (char *) pPassword,
											  &elements);
		}
		else // "Choose new layout"
		{
			pApp->UserPageSelectTemplateStyles((CEBayISAPIExtension *)this, 
											   (char *) pUserId,
											   (char *) pPassword,
											   &elements,
											   true);
		} 

		*pApp->mpStream << flush;

	MYCATCH("UserPageHandleTemplateOptions")

	EndContent(pCtxt);
    return;
}

// When the user clicks a button the preview page for templates,
// this gets invoked to figure out what to do.
void CEBayISAPIExtension::UserPageHandleTemplatePreviewOptions(CHttpServerContext *pCtxt,
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
		int   bgPattern)
{
	TemplateElements elements;
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageHandleTemplatePreviewOptions\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments,
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

// petra		if (!strcmp(pActionButton, "Edit some more"))
		if ((pActionButton2 && strcmp(pActionButton2, "default") == 0) &&	// petra
			(pActionButton3 && strcmp(pActionButton3, "default") == 0) &&	// petra
			(pActionButton4 && strcmp(pActionButton4, "default") == 0))		// petra
		{
			pApp->UserPageSelectTemplateElements((CEBayISAPIExtension *)this, 
										 (char *) pUserId,
									     (char *) pPassword,
									     &elements);
		}
// petra		else if (!strcmp(pActionButton, "Edit using HTML"))
		else if ((pActionButton1 && strcmp(pActionButton1, "default") == 0) &&	// petra
				 (pActionButton2 && strcmp(pActionButton2, "default") == 0) &&	// petra
				 (pActionButton4 && strcmp(pActionButton4, "default") == 0))	// petra
		{
			pApp->UserPageShowConfirmTemplateEditingChoices(
								  (CEBayISAPIExtension *)this, 
				                  (char *) pUserId,
				                  (char *) pPassword,
								  &elements,
								  UserPageTemplateToHTMLEditing);
		} 
// petra		else if (!strcmp(pActionButton, "Start over"))
		else if ((pActionButton1 && strcmp(pActionButton1, "default") == 0) &&	// petra
				 (pActionButton2 && strcmp(pActionButton2, "default") == 0) &&	// petra
				 (pActionButton3 && strcmp(pActionButton3, "default") == 0))	// petra
		{
			pApp->UserPageShowConfirmTemplateEditingChoices(
								  (CEBayISAPIExtension *)this, 
				                  (char *) pUserId,
				                  (char *) pPassword,
								  &elements,
								  UserPageTemplateEditingStartOver);

		}
		else // "Save my page"
		{
			pApp->UserPageShowConfirmTemplateEditingChoices(
								  (CEBayISAPIExtension *)this, 
				                  (char *) pUserId,
				                  (char *) pPassword,
								  &elements,
								  UserPageTemplateEditingSave);
			/*
			pApp->SaveUserPage((CEBayISAPIExtension *)this, 
							   (char *) pUserId,
							   (char *) pPassword,
							   &elements,
							   0);
			*/
		}

		*pApp->mpStream << flush;

	MYCATCH("UserPageHandleTemplatePreviewOptions")

	EndContent(pCtxt);
    return;
}

// There are some choices the user can make when editing in HTML
// mode that we have to verify. This code shows them the verification
// page.
void CEBayISAPIExtension::UserPageShowConfirmHTMLEditingChoices(CHttpServerContext *pCtxt,
			LPSTR pUserId,
			LPSTR pPassword,
			LPSTR pHTML,
			int   which)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageShowConfirmHTMLEditingChoices\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		// Start over warning page, or Don't save warning page
		pApp->UserPageShowConfirmHTMLEditingChoices((CEBayISAPIExtension *)this, 
									(char *)pUserId,
									(char *)pPassword,
									(char *)pHTML,
									(UserPageEditingEnum)which);

		*pApp->mpStream << flush;

	MYCATCH("UserPageShowConfirmHTMLEditingChoices")

	EndContent(pCtxt);
    return;
}

// Once they have verified or cancelled out of their choices,
// this function figures that out and sends them on their merry way.
void CEBayISAPIExtension::UserPageConfirmHTMLEditingChoice(CHttpServerContext *pCtxt,
			LPSTR pUserId,
			LPSTR pPassword,
			LPSTR pHTML,
			int   which,
			LPSTR pActionButton1,	// petra
			LPSTR pActionButton2)	// petra
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d UserPageConfirmHTMLEditingChoice\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY

		pApp->SetCurrentPage(PageUserPageEditing);

		switch ((UserPageEditingEnum)which)
		{
		case UserPageHTMLEditingStartOver:

// petra			if (strcmp(pActionButton, "Delete and start over") == 0) 
			if (pActionButton2 && strcmp(pActionButton2, "default") == 0)	// petra
			{
				// Delete...
				pApp->RemoveUserPage((char *) pUserId,
					                 (char *) pPassword,
									 0);

				// ... and start over.
				pApp->UserPageSelectTemplateStyles((CEBayISAPIExtension *)this,
									 (char *)pUserId, 
									 (char *)pPassword,
									 NULL, 
									 true);
				
			}
			else
			{
				// Go back to the html preview page
				pApp->UserPageGoToHTMLPreview((CEBayISAPIExtension *)this, 
									  (char *) pUserId,
				                      (char *) pPassword,
								      (char *) pHTML);
			}
			break;
		}
		*pApp->mpStream << flush;

	MYCATCH("UserPageConfirmHTMLEditingChoice")

	EndContent(pCtxt);
    return;
}

// Confirm that the choice the user is about to make when editing
// in template mode is the one the user really does want to make.
// We need a different set of ISAPI functions for confirming choices
// in HTML vs. Template mode because of the parameters we're passing
// around from page to page.
void CEBayISAPIExtension::UserPageShowConfirmTemplateEditingChoices(CHttpServerContext *pCtxt,
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
			int   bgPattern)
{	
	clseBayApp	*pApp;
	TemplateElements elements;

    ISAPITRACE("0x%x %d UserPageShowConfirmTemplateEditingChoices\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments, 
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

		// Show a delete warning or an HTML edit warning
		pApp->UserPageShowConfirmTemplateEditingChoices((CEBayISAPIExtension *)this, 
									(char *)pUserId,
									(char *)pPassword,
									&elements,
									(UserPageEditingEnum)which);

		*pApp->mpStream << flush;

	MYCATCH("UserPageShowConfirmTemplateEditingChoices")

	EndContent(pCtxt);
    return;
}

// Handle the choice the user makes when confirming a template
// editing choice.
void CEBayISAPIExtension::UserPageConfirmTemplateEditingChoice(CHttpServerContext *pCtxt,
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
			int   bgPattern)
{	
	clseBayApp	*pApp;
	TemplateElements elements;

    ISAPITRACE("0x%x %d UserPageConfirmTemplateEditingChoice\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments, 
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

		pApp->SetCurrentPage(PageUserPageEditing);

		switch ((UserPageEditingEnum)which)
		{
		case UserPageTemplateToHTMLEditing:

// petra			if (strcmp(pActionButton, "Edit using HTML") == 0) 
			if (pActionButton2 && strcmp(pActionButton2, "default") == 0)	// petra
			{
				ISAPITRACE("EditFromElements 1");				
				pApp->UserPageEditFromElements((CEBayISAPIExtension *)this, 
										       (char *) pUserId,
						                       (char *) pPassword,
									           &elements); 
			}
			else
			{
				// Go back to the template preview page
				ISAPITRACE("GoToTemplatePreview 2");
				pApp->UserPageGoToTemplatePreview((CEBayISAPIExtension *)this, 
									  (char *) pUserId,
				                      (char *) pPassword,
								      &elements);
			}
			break;

		case UserPageTemplateEditingStartOver:

// petra			if (strcmp(pActionButton, "Delete and start over") == 0) 
			if (pActionButton2 && strcmp(pActionButton2, "default") == 0)	// petra
			{
				ISAPITRACE("RemoveUserPage 3");
				pApp->RemoveUserPage((char *) pUserId,
					                 (char *) pPassword,
									 0);

				ISAPITRACE("SelectTemplateStyles 4");
				pApp->UserPageSelectTemplateStyles((CEBayISAPIExtension *)this, 
											   (char *) pUserId,
											   (char *) pPassword,
											   NULL,
											   true);
			}
			else
			{
				// Go back to the template preview page
				ISAPITRACE("GoToTemplatePreview 5");
				pApp->UserPageGoToTemplatePreview((CEBayISAPIExtension *)this, 
									  (char *) pUserId,
				                      (char *) pPassword,
								      &elements);
			}
			break;

		case UserPageTemplateEditingSave:

// petra			if (strcmp(pActionButton, "Save my page") == 0) 
			if (pActionButton2 && strcmp(pActionButton2, "default") == 0)	// petra
			{
				// "Save my page"
				ISAPITRACE("SaveUserPage 6");
				pApp->SaveUserPage((CEBayISAPIExtension *)this, 
								   (char *) pUserId,
								   (char *) pPassword,
								   &elements,
								   0);
			}
			else
			{
				// Go back to the template preview page
				ISAPITRACE("GoToTemplatePreview 7");
				pApp->UserPageGoToTemplatePreview((CEBayISAPIExtension *)this, 
									  (char *) pUserId,
				                      (char *) pPassword,
								      &elements);
			}
			break;
			
		}

		*pApp->mpStream << flush;

	MYCATCH("UserPageConfirmTemplateEditingChoice")

	EndContent(pCtxt);
    return;
}

// Preview the page when editing in template mode.			
void CEBayISAPIExtension::UserPageGoToTemplatePreview(CHttpServerContext *pCtxt,
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
			int   bgPattern)
{	
	clseBayApp	*pApp;
	TemplateElements elements;

    ISAPITRACE("0x%x %d UserPageGoToTemplatePreview\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUserPageEditing);

		FillElements(&elements, templateLayout, 
				 pPageTitle, 
				 pTextAreaTitle1, pTextArea1,
				 pTextAreaTitle2, pTextArea2,
				 pPictureCaption, pPictureURL, 
				 pShowUserIdEmail, 
				 feedbackNumComments, 
				 itemlistNumItems, pItemlistCaption, 
				 pFavoritesDescription1, pFavoritesName1, pFavoritesLink1, 
				 pFavoritesDescription2, pFavoritesName2, pFavoritesLink2, 
				 pFavoritesDescription3, pFavoritesName3, pFavoritesLink3,
				 item1CaptionChoice, item1, 
				 item2CaptionChoice, item2,
				 item3CaptionChoice, item3,
				 pPageCount, pDateTime, bgPattern);

		pApp->UserPageGoToTemplatePreview((CEBayISAPIExtension *)this, 
								  (char *) pUserId,
			                      (char *) pPassword,
							      &elements);

		*pApp->mpStream << flush;

	MYCATCH("UserPageGoToTemplatePreview")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ViewGiftAlert(CHttpServerContext *pCtxt,
												LPSTR pItemNo,
												LPSTR pUserId)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pUserId) ||
		!AfxIsValidAddress((char *)pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d ViewGiftAlert %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pUserId);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewGiftAlert);

		// Start over warning page, or Don't save warning page
		pApp->ViewGiftAlert((CEBayISAPIExtension *)this, 
							   (char *)pItemNo,
							   (char *)pUserId);

		*pApp->mpStream << flush;

	MYCATCH("ViewGiftAlert")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::RequestGiftAlert(CHttpServerContext *pCtxt,
										   LPSTR pItemNo,
										   LPSTR pUserId)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pUserId) ||
		!AfxIsValidAddress((char *)pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d RequestGiftAlert %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, pUserId);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageRequestGiftAlert);

		// Start over warning page, or Don't save warning page
		pApp->RequestGiftAlert((CEBayISAPIExtension *)this, 
							   (char *)pItemNo,
							   (char *)pUserId);

		*pApp->mpStream << flush;

	MYCATCH("RequestGiftAlert")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::SendGiftAlert(CHttpServerContext *pCtxt,
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
										LPSTR pOpenYear)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPass)					||
		!AfxIsValidAddress((char *)pFromName, 1, false)		||
		!AfxIsValidAddress((char *)pItemNo, 1, false)		||
		!AfxIsValidAddress((char *)pToName, 1, false)		||
		!AfxIsValidAddress((char *)pDestEmail, 1, false)	||
		!AfxIsValidAddress((char *)pMessage, 1, false)	||
		!AfxIsValidAddress((char *)pOpenMonth, 1, false)	||
		!AfxIsValidAddress((char *)pOpenDay, 1, false)	||
		!AfxIsValidAddress((char *)pOpenYear, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d SendGiftAlert %.20s %.20s %.20s %.20s %.20s %.20s %.20s %d %.20s %.20s %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pFromName, pItemNo, pToName, pDestEmail,
				pMessage, occasion, pOpenMonth, pOpenDay, pOpenYear);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageSendGiftAlert);

		// Start over warning page, or Don't save warning page
		pApp->SendGiftAlert((CEBayISAPIExtension *)this,
							(char *)pUserId,
							(char *)pPass,
							(char *)pFromName,
							(char *)pItemNo,
							(char *)pToName,
							(char *)pDestEmail,
							(char *)pMessage,
							(GiftOccasionEnum)occasion,
							(char *)pOpenMonth,
							(char *)pOpenDay,
							(char *)pOpenYear);

		*pApp->mpStream << flush;

	MYCATCH("SendGiftAlert")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ViewGiftCard(CHttpServerContext *pCtxt,
									   LPSTR pFromUserId,
									   LPSTR pFromName,
									   LPSTR pToName,
									   LPSTR pItemNo,
									   int occasion,
									   LPSTR pOpenDate)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pFromUserId) ||
		!AfxIsValidAddress((char *)pFromName, 1, false) ||
		!AfxIsValidAddress((char *)pToName, 1, false) ||
		!AfxIsValidAddress((char *)pItemNo, 1, false) ||
		!AfxIsValidAddress((char *)pOpenDate, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d ViewGiftCard %s %s %s %s %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pFromUserId, pFromName, pToName, pItemNo, occasion, pOpenDate);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewGiftCard);

		// Start over warning page, or Don't save warning page
		pApp->ViewGiftCard((CEBayISAPIExtension *)this, 
						   (char *)pFromUserId,
						   (char *)pFromName,
						   (char *)pToName,
						   (char *)pItemNo,
						   occasion,
						   (char *)pOpenDate);

		*pApp->mpStream << flush;

	MYCATCH("ViewGiftCard")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ViewGiftCard2(CHttpServerContext *pCtxt,
									    LPSTR pFromUserId,
									    LPSTR pFromName,
									    LPSTR pToName,
									    LPSTR pItemNo,
									    LPSTR pOpenDate,
									    int occasion)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pFromUserId) ||
		!AfxIsValidAddress((char *)pFromName, 1, false) ||
		!AfxIsValidAddress((char *)pToName, 1, false) ||
		!AfxIsValidAddress((char *)pItemNo, 1, false) ||
		!AfxIsValidAddress((char *)pOpenDate, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d ViewGiftCard2 %s %s %s %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pFromUserId, pFromName, pToName, pItemNo, pOpenDate, occasion);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewGiftCard);

		// Start over warning page, or Don't save warning page
		pApp->ViewGiftCard((CEBayISAPIExtension *)this, 
						   (char *)pFromUserId,
						   (char *)pFromName,
						   (char *)pToName,
						   (char *)pItemNo,
						   occasion,
						   (char *)pOpenDate);

		*pApp->mpStream << flush;

	MYCATCH("ViewGiftCard2")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ViewGiftItem(CHttpServerContext *pCtxt,
									   LPSTR pFromUserId,
									   LPSTR pFromName,
									   LPSTR pItemNo,
									   LPSTR pOpenDate,
									   int occasion)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pFromUserId) ||
		!AfxIsValidAddress((char *)pFromName, 1, false) ||
		!AfxIsValidAddress((char *)pItemNo, 1, false) ||
		!AfxIsValidAddress((char *)pOpenDate, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d ViewGiftItem %s %s %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pFromUserId, pFromName, pItemNo, pOpenDate, occasion);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageViewGiftItem);

		// Start over warning page, or Don't save warning page
		pApp->ViewGiftItem((CEBayISAPIExtension *)this, 
						   (char *)pFromUserId,
						   (char *)pFromName,
						   (char *)pItemNo,
						   occasion,
						   (char *)pOpenDate);

		*pApp->mpStream << flush;


	MYCATCH("ViewGiftItem")

	EndContent(pCtxt);
    return;
}


void CEBayISAPIExtension::AdminAddNoteAboutUserShow(CHttpServerContext *pCtxt,
													LPTSTR pUser)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUser, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminAddNoteAboutUserShow %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAddNoteAboutUser);	 
		pApp->AdminAddNoteAboutUserShow((CEBayISAPIExtension *)this,
										pUser,
										auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminAddNoteAboutUserShow")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminAddNoteAboutUser(CHttpServerContext *pCtxt,
											    LPTSTR pUserId,
											    LPTSTR pPass,
											    LPTSTR pAboutUser,
											    LPTSTR pSubject,
											    int type,
											    LPTSTR pText)
{
	clseBayApp			*pApp;
	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||
		!AfxIsValidAddress(pAboutUser, 1, false)	||
		!AfxIsValidAddress(pSubject, 1, false)		||
		!AfxIsValidAddress(pText, 1, false)				)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminAddNoteAboutUser %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pAboutUser, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAddNoteAboutUserResult);	 
		pApp->AdminAddNoteAboutUser((CEBayISAPIExtension *)this, 
								    (char *) pUserId,
								    (char *) pPass,
								    (char *) pAboutUser,
								    (char *) pSubject,
								    type,
								    (char *) pText,
								    auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminAddNoteAboutUser")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminAddNoteAboutItemShow(CHttpServerContext *pCtxt,
													LPTSTR pItem)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pItem, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminAddNoteAboutItemShow %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItem);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminAddNoteAboutItem);	 
		pApp->AdminAddNoteAboutItemShow((CEBayISAPIExtension *)this,
										pItem,
										auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminAddNoteAboutItemShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminAddNoteAboutItem(CHttpServerContext *pCtxt,
											    LPTSTR pUserId,
											    LPTSTR pPass,
											    LPTSTR pAboutItem,
											    LPTSTR pSubject,
											    int type,
											    LPTSTR pText)
{
	clseBayApp			*pApp;
	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||
		!AfxIsValidAddress(pAboutItem, 1, false)	||
		!AfxIsValidAddress(pSubject, 1, false)		||
		!AfxIsValidAddress(pText, 1, false)				)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminAddNoteAboutUser %s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pAboutItem, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminAddNoteAboutItemResult);	 
		pApp->AdminAddNoteAboutItem((CEBayISAPIExtension *)this, 
								    (char *) pUserId,
								    (char *) pPass,
								    (char *) pAboutItem,
								    (char *) pSubject,
								    type,
								    (char *) pText,
								    auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminAddNoteAboutItem")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminShowNoteShow(CHttpServerContext *pCtxt,
											LPTSTR pUserId,
											LPTSTR pPass,
											LPTSTR pAboutFilter,
											int typeFilter)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||
		!AfxIsValidAddress(pAboutFilter, 1, false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminShowNoteShow\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminShowNote);	 
		pApp->AdminShowNoteShow((CEBayISAPIExtension *)this, 
								(char *)pUserId,
								(char *)pPass,
								(char *)pAboutFilter,
								typeFilter,
								auth);
		*pApp->mpStream << flush;

	MYCATCH("AdminShowNoteShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminShowNote(CHttpServerContext *pCtxt,
										LPTSTR pUserId,
										LPTSTR pPass,
										LPTSTR pAboutFilter,
										int typeFilter)
{
	clseBayApp			*pApp;
	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||
		!AfxIsValidAddress(pAboutFilter, 1, false)		)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminShowNote %s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pAboutFilter, typeFilter);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageAdminShowNoteShowResult);	 
		pApp->AdminShowNote((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPass,
							(char *) pAboutFilter,
							typeFilter,
							auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminShowNote")

	EndContent(pCtxt);
}

bool CEBayISAPIExtension::MonsterBugSanityCheck(CHttpServerContext *pCtxt, 
												clseBayApp *pApp, 
												const char *pFunctionName, 
												const char *pTarget,
												bool BlockIt)
{
	int		BufLength = 0;
	char*	pBuf;
	bool	bRet = true;

	// sanity check
	if (!pCtxt->m_pECB || !pCtxt->m_pECB->lpszMethod)
	{
		EDEBUG('*', "%s Monster Bug detected (ECD or lpszMethod corrupted)", pFunctionName);
		*pApp->mpStream <<	"Request failed. Please try again." << flush;
		return false;
	}

	// check 
	if (stricmp(pTarget, "default") == 0)
	{
		// we have better control in the function for default
		return bRet;
	}

	// Monster-bug prevention code
	//
	// find out which query method is using
	if (stricmp(pCtxt->m_pECB->lpszMethod, "GET") == 0)
	{
		// method Get
		char  query_string[256];
		DWORD dwLen = sizeof(query_string);

		pCtxt->GetServerVariable("QUERY_STRING", query_string, &dwLen);

		// find out the length
		BufLength = strlen(query_string) + 1;

		pBuf = new char [BufLength];

		// convert the escape chars to ascii
		clsUtilities::ExcapeToAscii(query_string, pBuf, BufLength);
	}
	else
	{
		// method post

		// find out the length
		if (!pCtxt->m_pECB->lpbData)
		{
			EDEBUG('*', "%s Monster Bug detected (lpbData corrupted)", pFunctionName);
			*pApp->mpStream <<	"Request failed. Please try again." << flush;
			return false;
		}

		BufLength = pCtxt->m_pECB->cbAvailable + 1;

		pBuf = new char [BufLength];

		// convert the escape chars to ascii
		clsUtilities::ExcapeToAscii((const char*) pCtxt->m_pECB->lpbData, pBuf, BufLength);
	}

	// check that target can be found in the buffer.
	//  in theory, it should always find it.
	if (!strstr(pBuf, pTarget))
	{
		// WOW, we've detected a monster bug!

		// only the part of the buffer
		if (BufLength > 128)
			pBuf[127] = 0;

		EDEBUG('*', "%s Monster Bug Detected!: %s %s", pFunctionName, pBuf, pTarget);

		if (BlockIt)
		{
			*pApp->mpStream <<	"Request failed. Please try again." << flush;
			bRet = false;
		}
	}

	delete [] pBuf;

	return bRet;	// all is well

}



// Issues a redirect command to the IIS server. For some reason,
//  IIS4's HSE_REQ_SEND_URL_REDIRECT_RESP doesn't work, so we
//  use the more general HSE_REQ_SEND_RESPONSE_HEADER instead.
// Returns whether or not successful
int CEBayISAPIExtension::EbayRedirect(CHttpServerContext *pCtxt, const char* pURL) const
{
	char	pEntireCommand[1024];
	unsigned long length;

	// safety
	if ((!pCtxt) || (!pURL)) return false;

	// create the command
	sprintf(pEntireCommand, "Location: %s\r\n", pURL);
	length = strlen(pEntireCommand);

	// do it
	return pCtxt->ServerSupportFunction(HSE_REQ_SEND_RESPONSE_HEADER, 
		"302 Object Moved", &length, (unsigned long *) pEntireCommand);
}

// Routines for testing the location-related routines
int CEBayISAPIExtension::LocationsCompareZipToAC(CHttpServerContext *pCtxt, LPSTR zip, int ac)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsCompareZipToAC %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip, ac);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsCompareZipToAC((CEBayISAPIExtension *)this, (char *) zip, ac);
		*pApp->mpStream << flush;

	MYCATCH("LocationsCompareZipToAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsCompareZipToState(CHttpServerContext *pCtxt, LPSTR zip, LPSTR state)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip,	1,	false) || !AfxIsValidAddress(state,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsCompareZipToState %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip, state);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsCompareZipToState((CEBayISAPIExtension *)this, (char *) zip, (char *) state);
		*pApp->mpStream << flush;

	MYCATCH("LocationsCompareZipToState")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsCompareStateToAC(CHttpServerContext *pCtxt, LPSTR state, int ac)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(state,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsCompareStateToAC %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				state, ac);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsCompareStateToAC((CEBayISAPIExtension *)this, (char *) state, ac);
		*pApp->mpStream << flush;

	MYCATCH("LocationsCompareStateToAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsCompareZipToCity(CHttpServerContext *pCtxt, LPSTR zip, LPSTR city)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip,	1,	false) || !AfxIsValidAddress(city,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsCompareZipToCity %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip, city);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsCompareZipToCity((CEBayISAPIExtension *)this, (char *) zip, (char *) city);
		*pApp->mpStream << flush;

	MYCATCH("LocationsCompareZipToCity")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsCompareCityToAC(CHttpServerContext *pCtxt, LPSTR city, int ac)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(city,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsCompareCityToAC %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				city, ac);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsCompareCityToAC((CEBayISAPIExtension *)this, (char *) city, ac);
		*pApp->mpStream << flush;

	MYCATCH("LocationsCompareCityToAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsIsValidZip(CHttpServerContext *pCtxt, LPSTR zip)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsIsValidZip %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsIsValidZip((CEBayISAPIExtension *)this, (char *) zip);
		*pApp->mpStream << flush;

	MYCATCH("LocationsIsValidZip")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsIsValidAC(CHttpServerContext *pCtxt, int ac)
{
	clseBayApp	*pApp;

	// Trace
	ISAPITRACE("0x%x %d LocationsIsValidAC %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				ac);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsIsValidAC((CEBayISAPIExtension *)this, ac);
		*pApp->mpStream << flush;

	MYCATCH("LocationsIsValidAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsIsValidCity(CHttpServerContext *pCtxt, LPSTR city)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(city,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsIsValidCity %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				city);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsIsValidCity((CEBayISAPIExtension *)this, (char *) city);
		*pApp->mpStream << flush;

	MYCATCH("LocationsIsValidCity")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::LocationsDistanceZipAC(CHttpServerContext *pCtxt, LPSTR zip, int ac)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsDistanceZipAC %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip, ac);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsDistanceZipAC((CEBayISAPIExtension *)this, (char *) zip, ac);
		*pApp->mpStream << flush;

	MYCATCH("LocationsDistanceZipAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::LocationsDistanceZipZip(CHttpServerContext *pCtxt, LPSTR zip1, LPSTR zip2)
{
	clseBayApp	*pApp;

	// Sanity
	if (!AfxIsValidAddress(zip1,	1,	false) || !AfxIsValidAddress(zip2,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	// Trace
	ISAPITRACE("0x%x %d LocationsDistanceZipZip %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				zip1, zip2);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) 
		pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsDistanceZipZip((CEBayISAPIExtension *)this, (char *) zip1, (char *) zip2);
		*pApp->mpStream << flush;

	MYCATCH("LocationsDistanceZipZip")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}



int CEBayISAPIExtension::LocationsDistanceACAC(CHttpServerContext *pCtxt, int ac1, int ac2)
{
	clseBayApp	*pApp;

	// Trace
	ISAPITRACE("0x%x %d LocationsDistanceACAC %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				ac1, ac2);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageLocationsTesting);
		pApp->LocationsDistanceACAC((CEBayISAPIExtension *)this, ac1, ac2);
		*pApp->mpStream << flush;

	MYCATCH("LocationsDistanceACAC")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::AdminGalleryItemDelete(CHttpServerContext* pCtxt,
												int pItemNo)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminGalleryItemDelete %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminGalleryItemDelete);

	pApp->AdminGalleryItemDelete((CEBayISAPIExtension *)this,
					   pCtxt, pItemNo, auth);

	MYCATCH("AdminGalleryItemDelete");

	EndContent(pCtxt);

	return callOK;

} 

int CEBayISAPIExtension::AdminGalleryItemView(CHttpServerContext* pCtxt,
												int pItemNo)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminGalleryItemView %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminGalleryItemView);

	pApp->AdminGalleryItemView((CEBayISAPIExtension *)this,
					   pItemNo, auth);

	MYCATCH("AdminGalleryItemView");

	EndContent(pCtxt);

	return callOK;

} 

int CEBayISAPIExtension::AdminGalleryItemDeleteConfirm(CHttpServerContext* pCtxt,
												int pItemNo)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminGalleryItemDeleteConfirm %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
	pApp->SetCurrentPage(PageAdminGalleryItemDeleteConfirm);

	pApp->AdminGalleryItemDeleteConfirm((CEBayISAPIExtension *)this,
					   pItemNo, auth);

	MYCATCH("AdminGalleryItemDeleteConfirm");

	EndContent(pCtxt);

	return callOK;

} 

int CEBayISAPIExtension::DisplayGalleryImagePage(CHttpServerContext *pCtxt,
									     int item)
{
	clseBayApp	*pApp;

	// Trace
	ISAPITRACE("DisplayGalleryImagePage %d\n",
			    item);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageDisplayGalleryImagePage);
		pApp->DisplayGalleryImagePage((CEBayISAPIExtension *)this, item);
		*pApp->mpStream << flush;

	MYCATCH("DisplayGalleryImagePage")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::EnterNewGalleryImage(CHttpServerContext *pCtxt,
											  char *pUserId,
											  char *pPassword,
											  int   item)
{
	clseBayApp	*pApp;

	// Trace
	ISAPITRACE("EnterNewGalleryImage %s\n",
			    pUserId);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageEnterNewGalleryImage);
		pApp->EnterNewGalleryImage((CEBayISAPIExtension *)this, pUserId, pPassword, item);
		*pApp->mpStream << flush;

	MYCATCH("EnterNewGalleryImage")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}


int CEBayISAPIExtension::FixGalleryImage(CHttpServerContext *pCtxt,
											  char *pUserId,
											  char *pPassword,
											  int   item,
											  char *pURL)
{
	clseBayApp	*pApp;

	// Trace
	ISAPITRACE("FixGalleryImage %s\n",
			    pUserId);

	// Send HTTP header
	StartContent(pCtxt);

	// Get the app
	pApp = (clseBayApp *)GetApp();
	if (!pApp) pApp	= CreateeBayApp();

	// Stream setup
	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	// Do it
	MYTRY
		pApp->SetCurrentPage(PageFixGalleryImage);
		pApp->FixGalleryImage((CEBayISAPIExtension *)this, pUserId, pPassword, item, pURL);
		*pApp->mpStream << flush;

	MYCATCH("FixGalleryImage")

	// Finish off
	EndContent(pCtxt);

	return callOK;
}
	

void CEBayISAPIExtension::AsparagusBananaSandwich(CHttpServerContext *pCtxt,
												   LPSTR pUserid,
												   LPSTR pPassword)
{
	clseBayApp *pApp;


	// Sanity
	if ((!AfxIsValidAddress(pUserid, 1, false)) ||
		(!AfxIsValidAddress(pPassword, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}
	
	ISAPITRACE("0x%x %d AsparagusBanananSandwich\n",
		GetCurrentThreadId(), GetCurrentThreadId());

	pApp = (clseBayApp *)GetApp();
	if (!pApp)
		pApp = CreateeBayApp();

	// Notice -- no start content call just yet. We'll do the cookies first.

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PageUp4SaleTestPassword);

		StartContent(pCtxt);

		pApp->Up4SaleTestPassword((char *) pUserid,
								  (char *) pPassword);

		*pApp->mpStream << flush;

	MYCATCH("AsparagusBanananSandwich")

	EndContent(pCtxt);
}

// iescrow
void CEBayISAPIExtension::IEscrowLogin(CHttpServerContext* pCtxt,
									   LPTSTR pItemNo, LPTSTR ptype, int bidderno)
{
	clseBayApp		*pApp;

	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d IEscrowLogin %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItemNo, ptype);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageIEscrowLogin);

	pApp->IEscrowLogin(this, pItemNo, ptype, bidderno);
	*pApp->mpStream << flush;

	MYCATCH("IEscrowLogin")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::IEscrowShowData(CHttpServerContext* pCtxt,
											LPTSTR pUserId,
											LPTSTR pPass,
											LPTSTR pItemNo,
											LPTSTR ptype,
											int	   bidderno)
{
	clseBayApp		*pApp;

	if ( !ValidateUserId((char *)pUserId)		||
		 !ValidatePassword((char *)pPass)		||
		 !AfxIsValidAddress(pItemNo, 1, false)	||
		 !AfxIsValidAddress(ptype, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d IEscrowShowData %s %s %.20s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUserId, pPass, pItemNo,
				ptype, bidderno);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageIEscrowShowData);

	pApp->IEscrowShowData(this, pUserId, pPass, pItemNo, ptype, bidderno);
	*pApp->mpStream << flush;

	MYCATCH("IEscrowShowData")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::IEscrowSendData(CHttpServerContext* pCtxt,
											LPTSTR pPartyOne,
											LPTSTR pItemNo,
											LPTSTR ptype,
											int	   Qty,
											int	   bidderno,
											LPTSTR pPartyTwo)
{
	clseBayApp		*pApp;

	if ( !ValidateUserId((char *)pPartyOne)		||
		 !ValidateUserId((char *)pPartyTwo)		||
		 !AfxIsValidAddress(pItemNo, 1, false)	||
		 !AfxIsValidAddress(ptype, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d IEscrowSendData %s %.20s %.20s %d %d %.20s\n",
					GetCurrentThreadId(), GetCurrentThreadId(),
					pPartyOne, pItemNo, ptype, 
					Qty, bidderno, pPartyTwo);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageIEscrowSendData);

	pApp->IEscrowSendData(this, pPartyOne, pItemNo, ptype, Qty, pPartyTwo, bidderno);
	*pApp->mpStream << flush;

	MYCATCH("IEscrowSendData")

	EndContent(pCtxt);
}
//iescrow


void CEBayISAPIExtension::ViewDeadbeatUser(CHttpServerContext *pCtxt,
										   LPSTR deadbeatuserid)
{
   	clseBayApp	*pApp;
	
	// Sanity
	if (!ValidateUserId((char*)deadbeatuserid))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d ViewDeadbeatUser %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				deadbeatuserid);

   	StartContent(pCtxt);	
	
   	pApp	= (clseBayApp *)GetApp();
   	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
	 
		pApp->SetCurrentPage(PageViewDeadbeatUser);
	
		pApp->ViewDeadbeatUser((CEBayISAPIExtension *)this, 
							   (char *) deadbeatuserid);

		*pApp->mpStream << flush;

	MYCATCH("ViewDeadbeatUser")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::ViewDeadbeatUsers(CHttpServerContext *pCtxt)
{
   	clseBayApp	*pApp;
	
	ISAPITRACE("0x%x %d ViewDeadbeatUsers\n",
				GetCurrentThreadId(), GetCurrentThreadId());

   	StartContent(pCtxt);	
	
   	pApp	= (clseBayApp *)GetApp();
   	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
	 
		pApp->SetCurrentPage(PageViewDeadbeatUsers);
	
		pApp->ViewDeadbeatUsers((CEBayISAPIExtension *)this);

		*pApp->mpStream << flush;

	MYCATCH("ViewDeadbeatUsers")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::DeleteDeadbeatItem(CHttpServerContext *pCtxt,
										     LPSTR selleruserid,
										     LPSTR bidderuserid,
										     int itemno,
										     int confirm)
{
   	clseBayApp	*pApp;
	
	// Sanity
	if (!ValidateUserId((char*)selleruserid) || 
		!ValidateUserId((char*)bidderuserid))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d DeleteDeadbeatItem %s %s %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				selleruserid, bidderuserid, itemno, confirm);

   	StartContent(pCtxt);	
	
   	pApp	= (clseBayApp *)GetApp();
   	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
	 
		pApp->SetCurrentPage(PageDeleteDeadbeatItem);
	
		pApp->DeleteDeadbeatItem((CEBayISAPIExtension *)this, 
			                     (char *) selleruserid,
				                 (char *) bidderuserid,
					             itemno,
								 confirm
							    );

		*pApp->mpStream << flush;

	MYCATCH("DeleteDeadbeatItem")

	EndContent(pCtxt);

	return;
}

void CEBayISAPIExtension::SendQueryEmail(CHttpServerContext *pCtxt,
									    LPSTR pUserId,
									    LPSTR pPass,
									    LPSTR pSubject,
										LPSTR pMessage,
										int MailDestination
									    )
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPass)					||
		!AfxIsValidAddress((char *)pSubject, 1, false)		||
		!AfxIsValidAddress((char *)pMessage, 1, false))	
		
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d SendQueryEmail %s %s %.20s %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pSubject, pMessage, MailDestination);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageSendQueryEmail);

		// Start over warning page, or Don't save warning page
		pApp->SendQueryEmail((CEBayISAPIExtension *)this,
							(char *)pUserId,
							(char *)pPass,
							(char *)pSubject,
							(char *)pMessage,
							MailDestination);


		*pApp->mpStream << flush;

	MYCATCH("SendQueryEmail")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::SendQueryEmailShow(CHttpServerContext *pCtxt,
										    LPSTR pSubject)
{
	clseBayApp	*pApp;
	char		cRedirectURL[512];
	bool		bRedirect = false;

	if (!AfxIsValidAddress((char *)pSubject, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d SendQueryEmailShow %.20s \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pSubject);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		bRedirect = pApp->SendQueryEmailShow((CEBayISAPIExtension *)this,
											 (char *)pSubject,
											 cRedirectURL);

		*pApp->mpStream << flush;

	MYCATCH("SendQueryEmailShow")

	if(bRedirect)
		EbayRedirect(pCtxt, cRedirectURL);
	else
		EndContent(pCtxt);

    return;
}

// Top Seller

void CEBayISAPIExtension::ShowTopSellerStatus(CHttpServerContext *pCtxt,
							 LPTSTR pUserId)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	ISAPITRACE("0x%x %d ShowTopSellerStatus %s\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		pUserId);
	
	auth	= DetermineAuthorization(pCtxt);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
			
		pApp->ShowTopSellerStatus((CEBayISAPIExtension *)this, pUserId, auth);
		
		*pApp->mpStream << flush;
	
	MYCATCH("ShowTopSellerStatus")
		
	EndContent(pCtxt);
}

void CEBayISAPIExtension::SetTopSellerLevelConfirmation(CHttpServerContext *pCtxt,
							 LPTSTR pUserId,
							 int level)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	ISAPITRACE("0x%x %d SetTopSellerLevelConfirmation %s %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		pUserId, level);
	
	auth	= DetermineAuthorization(pCtxt);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
			
		pApp->SetTopSellerLevelConfirmation((CEBayISAPIExtension *)this, pUserId, level, auth);
		
		*pApp->mpStream << flush;
	
	MYCATCH("SetTopSellerLevelConfirmation")
		
	EndContent(pCtxt);
}

void CEBayISAPIExtension::SetTopSellerLevel(CHttpServerContext *pCtxt,
							 LPTSTR pUserId,
							 int level)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	ISAPITRACE("0x%x %d SetTopSellerLevel %s %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		pUserId, level);
	
	auth	= DetermineAuthorization(pCtxt);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
			
		pApp->SetTopSellerLevel((CEBayISAPIExtension *)this, pUserId, level, auth);
		
		*pApp->mpStream << flush;
	
	MYCATCH("SetTopSellerLevel")
		
	EndContent(pCtxt);
}

void CEBayISAPIExtension::SetMultipleTopSellers(CHttpServerContext *pCtxt,
							 LPTSTR text,
							 int level)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	ISAPITRACE("0x%x %d SetMultipleTopSellers %.40s %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		text);
	
	auth	= DetermineAuthorization(pCtxt);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
			
		pApp->SetMultipleTopSellers((CEBayISAPIExtension *)this, text, level, auth);
		
		*pApp->mpStream << flush;
	
	MYCATCH("SetMultipleTopSellers")
		
	EndContent(pCtxt);
}

void CEBayISAPIExtension::ShowTopSellers(CHttpServerContext *pCtxt,
							 int level)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	ISAPITRACE("0x%x %d ShowTopSellers %d\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		level);
	
	auth	= DetermineAuthorization(pCtxt);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
			
		pApp->ShowTopSellers((CEBayISAPIExtension *)this, level, auth);
		
		*pApp->mpStream << flush;
	
	MYCATCH("ShowTopSellers")
		
	EndContent(pCtxt);
}


int CEBayISAPIExtension::RegisterShow(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d RegisterShow\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageRegisterShow);

		pApp->RegisterShow((CEBayISAPIExtension *)this, pCtxt);

		*pApp->mpStream << flush;

	MYCATCH("RegisterShow")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());
	return callOK;

}

// Report questionable item to ebay support
void CEBayISAPIExtension::ReportQuestionableItem(CHttpServerContext *pCtxt,
									    LPSTR pUserId,
									    LPSTR pPass,
									    LPSTR pItemType,
										int itemID,
										LPSTR pMessage)
{
	clseBayApp	*pApp;

	if (!ValidateUserId((char *)pUserId)					||
		!ValidatePassword((char *)pPass)					||
		!AfxIsValidAddress((char *)pItemType, 1, false)		||
		!AfxIsValidAddress((char *)pMessage, 1, false))	
		
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d ReportQuestionableItem %.20s %.10s %.10s  %d %.20s \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pItemType, itemID, pMessage);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageReportQuestionableItem);

		// Start over warning page, or Don't save warning page
		pApp->ReportQuestionableItem((CEBayISAPIExtension *)this,
							(char *)pUserId,
							(char *)pPass,
							(char *)pItemType,
							itemID,
							(char *)pMessage);


		*pApp->mpStream << flush;

	MYCATCH("ReportQuestionableItem")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ReportQuestionableItemShow(CHttpServerContext *pCtxt,
										    int itemID)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d ReportQuestionableItemShow %d \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				itemID);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageReportQuestionableItemShow);

		// Start over warning page, or Don't save warning page
		pApp->ReportQuestionableItemShow((CEBayISAPIExtension *)this,
							itemID);

		*pApp->mpStream << flush;

	MYCATCH("ReportQuestionableItemShow")

	EndContent(pCtxt);
    return;
}

/*
void CEBayISAPIExtension::TurnOnBidNoticesChinese(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOnBidNoticesChinese\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(bidNoticesChinese, true);

		*pApp->mpStream << flush;

	MYCATCH("TurnOnBidNoticesChinese")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::TurnOffBidNoticesChinese(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOffBidNoticesChinese\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(bidNoticesChinese, false);

		*pApp->mpStream << flush;

	MYCATCH("TurnOffBidNoticesChinese")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::TurnOnBidNoticesDutch(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOnBidNoticesDutch\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(bidNoticesDutch, true);

		*pApp->mpStream << flush;

	MYCATCH("TurnOnBidNoticesDutch")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::TurnOffBidNoticesDutch(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOffBidNoticesDutch\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(bidNoticesDutch, false);

		*pApp->mpStream << flush;

	MYCATCH("TurnOffBidNoticesDutch")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::TurnOnOutBidNoticesChinese(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOnOutBidNoticesChinese\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(outBidNoticesChinese, true);

		*pApp->mpStream << flush;

	MYCATCH("TurnOnOutBidNoticesChinese")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::TurnOffOutBidNoticesChinese(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;

    ISAPITRACE("0x%x %d TurnOffOutBidNoticesChinese\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		// Start over warning page, or Don't save warning page
		pApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetMailControl()->SetMailBidNoticesState(outBidNoticesChinese, false);

		*pApp->mpStream << flush;

	MYCATCH("TurnOffOutBidNoticesChinese")

	EndContent(pCtxt);
    return;
}

*/

void CEBayISAPIExtension::ToggleMailMachineBidStatus(CHttpServerContext *pCtxt,
										    int bidType,
											int state)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

    ISAPITRACE("0x%x %d ToggleMailMachineBidStatus %d %d \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				bidType, state);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		if(RemoteAddrIneBayDomain(pApp)) 
		// cheat and use this for failure
			auth = eBayISAPIAuthAdmin;
		else
			auth = eBayISAPIAuthUser;	
		
		// Start over warning page, or Don't save warning page
		pApp->ToggleMailMachineBidStatus((CEBayISAPIExtension *)this,
							bidType, state, auth);

		*pApp->mpStream << flush;

	MYCATCH("ToggleMailMachineBidStatus")

	EndContent(pCtxt);
    return;
}


void CEBayISAPIExtension::InstallNewMailMachineList(CHttpServerContext *pCtxt,
										    LPSTR machines,
											int poolType)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	if (!AfxIsValidAddress((char *)machines, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

    ISAPITRACE("0x%x %d InstallNewMailMachineList %.60s \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				machines);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

    // see if we are in one of our domains
		if(RemoteAddrIneBayDomain(pApp)) 
			auth = eBayISAPIAuthAdmin;
		else
			auth = eBayISAPIAuthUser;	
		
		// Start over warning page, or Don't save warning page
		pApp->InstallNewMailMachineList((CEBayISAPIExtension *)this,
							(char *)machines,
							poolType, auth);

		*pApp->mpStream << flush;

	MYCATCH("InstallNewMailMachineList")

	EndContent(pCtxt);
    return;
}

void CEBayISAPIExtension::ShowMailMachineStatus(CHttpServerContext *pCtxt)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

    ISAPITRACE("0x%x %d ShowMailMachineStatus\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
//		pApp->SetCurrentPage(PageSendQueryEmailShow);

		if(RemoteAddrIneBayDomain(pApp)) 
			// cheat and use this for failure
			auth = eBayISAPIAuthAdmin;
		else
			auth = eBayISAPIAuthUser;	
	
		// Start over warning page, or Don't save warning page
		pApp->ShowMailMachineStatus((CEBayISAPIExtension *)this,
							auth);

		*pApp->mpStream << flush;

	MYCATCH("ShowMailMachineStatus")

	EndContent(pCtxt);
    return;
}

// Legal Buddy code
void CEBayISAPIExtension::AdminAddScreeningCriteria(CHttpServerContext* pCtxt,
														CategoryId categoryid)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	

	ISAPITRACE("0x%x %d AdminAddScreeningCriteria %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				categoryid);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddScreeningCriteria);

	pApp->AdminAddScreeningCriteria((CEBayISAPIExtension *)this,
										categoryid, auth);

	MYCATCH("AdminAddScreeningCriteria");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminAddScreeningCriteriaShow(CHttpServerContext* pCtxt,
															CategoryId categoryid,
															FilterId filterid,
															MessageId messageid,
															int action)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminAddScreeningCriteriaShow %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				categoryid, filterid, messageid, action);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddScreeningCriteriaShow);

	pApp->AdminAddScreeningCriteriaShow((CEBayISAPIExtension *)this,
											categoryid, filterid,
											messageid, action, auth);

	MYCATCH("AdminAddScreeningCriteriaShow");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminViewScreeningCriteria(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	

	ISAPITRACE("0x%x %d AdminViewScreeningCriteria %d\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminViewScreeningCriteria);

	pApp->AdminViewScreeningCriteria((CEBayISAPIExtension *)this,
										auth);

	MYCATCH("AdminViewScreeningCriteria");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminViewScreeningCriteriaShow(CHttpServerContext* pCtxt,
															CategoryId categoryid)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminViewScreeningCriteriaShow %d \n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				categoryid);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminViewScreeningCriteriaShow);

	pApp->AdminViewScreeningCriteriaShow((CEBayISAPIExtension *)this,
											categoryid, auth);

	MYCATCH("AdminViewScreeningCriteriaShow");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminAddFilter(CHttpServerContext* pCtxt,
												int action, 
												FilterId filterid)

{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminAddFilter %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action, filterid);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddFilter);

	pApp->AdminAddFilter((CEBayISAPIExtension *)this,
						  action, filterid, auth);

	MYCATCH("AdminAddFilter");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminAddMessage(CHttpServerContext* pCtxt,
											int action, MessageId messageid)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminAddMessage %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action, messageid);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddMessage);

	pApp->AdminAddMessage((CEBayISAPIExtension *)this,
						   action, messageid, auth);

	MYCATCH("AdminAddMessage");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminModifyMessage(CHttpServerContext* pCtxt,
											 int action)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminModifyMessage %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminModifyMessage);

	pApp->AdminModifyMessage((CEBayISAPIExtension *)this, action, auth);

	MYCATCH("AdminModifyMessage");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminAddMessageShow(CHttpServerContext* pCtxt,
												int action, MessageId messageid,
												LPSTR pName, LPSTR pMessage,
												MessageType message_type)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminAddMessageShow %d %d %.20s, %.20s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action, messageid, pName, pMessage, message_type);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddMessageShow);

	pApp->AdminAddMessageShow((CEBayISAPIExtension *)this,
								action, messageid, pName, pMessage, 
								message_type, auth);

	MYCATCH("AdminAddMessageShow");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminModifyFilter(CHttpServerContext* pCtxt,
											 int action)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminModifyFilter %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminModifyFilter);

	pApp->AdminModifyFilter((CEBayISAPIExtension *)this, action, auth);

	MYCATCH("AdminModifyFilter");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminAddFilterShow(CHttpServerContext* pCtxt,
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
												LPSTR pBuddyEmailAddress)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;

	ISAPITRACE("0x%x %d AdminAddFilterShow %d %d %.20s, %.20s\n %d %d %d %d %d %d\n %.20s, %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				action, filterid, pName, pExpression, actiontype,
				notifytype, blockedmessage, flaggedmessage, 
				filteremailtext, buddyemailtext, pFilterEmailAddress,
				pBuddyEmailAddress);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminAddFilterShow);

	pApp->AdminAddFilterShow((CEBayISAPIExtension *)this,
								action, filterid, pName, pExpression,
								actiontype, notifytype, blockedmessage,
								flaggedmessage,	filteremailtext, buddyemailtext,
								pFilterEmailAddress, pBuddyEmailAddress, auth);

	MYCATCH("AdminAddFilterShow");

	EndContent(pCtxt);

	return;
} 

//
// AdminViewBlockedItem
//
void CEBayISAPIExtension::AdminViewBlockedItem(CHttpServerContext* pCtxt,
											   LPSTR pItemNo, LPSTR pItemRow,
											   int timeStamp, LPSTR tc)
{
	eBayISAPIAuthEnum	auth;

	// tc is a tracking code only. It is not passed on to 
	// clseBayApp or used in any way other than to show up in the logs.

	clseBayApp		*pApp;

	// Do this FIRST so ISAPITRACE doesn't bite it!
	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminViewBlockedItem %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pItemNo, tc);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	// Check for monster bug.
	//  If monster, then block user
	if (!MonsterBugSanityCheck(pCtxt, pApp, "ViewItem", pItemNo, true))
	{
		EndContent(pCtxt);
		return;
	}

	MYTRY
	pApp->SetCurrentPage(PageAdminViewBlockedItem);

	pApp->AdminViewBlockedItem(this, pItemNo, pItemRow, timeStamp, 
								auth, pCtxt);

	*pApp->mpStream << flush;

	MYCATCH("AdminViewBlockedItem")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

}


void CEBayISAPIExtension::AdminReinstateAuctionShow(CHttpServerContext *pCtxt,
														int action,
														LPTSTR pUser,
														LPTSTR pPass,
														LPTSTR pItemNo,
														int type,
														LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pItemNo, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminReinstateAuctionShow %d %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				action, pUser, pItemNo, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateAuctionShow);	 
		pApp->AdminReinstateAuctionShow(action, pUser, pPass, pItemNo,
											type, pText, auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateAuctionShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminReinstateAuctionConfirm(CHttpServerContext *pCtxt,
														int action,
														LPTSTR pUser,
														LPTSTR pPass,
														LPTSTR pItemNo,
														int type,
														LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pItemNo, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminReinstateAuctionConfirm %d %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				action, pUser, pItemNo, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateAuctionConfirm);	 
		pApp->AdminReinstateAuctionConfirm(action, pUser, pPass, 
											pItemNo, type, pText, auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateAuctionConfirm")

	EndContent(pCtxt);
}



void CEBayISAPIExtension::AdminReinstateAuction(CHttpServerContext *pCtxt,
													int action,
													LPTSTR pUser,
													LPTSTR pPass,
													LPTSTR pItemNo,
													int type,
													LPTSTR pText,
													LPTSTR pEmailSubject,
													LPTSTR pEmailText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))			||
		(!AfxIsValidAddress(pPass, 1, false))			||
		(!AfxIsValidAddress(pItemNo, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))			||
		(!AfxIsValidAddress(pEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminReinstateAuction %d %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				action, pUser, pItemNo, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminReinstateAuctionResult);	 
		pApp->AdminReinstateAuction(action, pUser, pPass, 
										pItemNo, type, pText,
										pEmailSubject, pEmailText,
										auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminReinstateAuction")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminUnflagUserShow(CHttpServerContext *pCtxt,
											  LPTSTR pUser,
											  LPTSTR pPass,
											  LPTSTR pTarget,
											  int type,
											  LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminUnflagUserShow %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				pUser, pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminUnflagUserShow);	 
		pApp->AdminUnflagUserShow(pUser, pPass, pTarget, type, pText,
								  auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminUnflagUserShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminUnflagUserConfirm(CHttpServerContext *pCtxt,
												 LPTSTR pUser,
												 LPTSTR pPass,
												 LPTSTR pTarget,
												 int type,
												 LPTSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))		||
		(!AfxIsValidAddress(pPass, 1, false))		||
		(!AfxIsValidAddress(pTarget, 1, false))		||
		(!AfxIsValidAddress(pText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminUnflagUserConfirm %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);


	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminUnflagUserConfirm);	 
		pApp->AdminUnflagUserConfirm(pUser, pPass, 
									 pTarget, 
									 type, pText,
									 auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminUnflagUserConfirm")

	EndContent(pCtxt);
}



void CEBayISAPIExtension::AdminUnflagUser(CHttpServerContext *pCtxt,
										  LPTSTR pUser,
										  LPTSTR pPass,
										  LPTSTR pTarget,
										  int type,
										  LPTSTR pText,
										  LPTSTR pEmailSubject,
										  LPTSTR pEmailText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if ((!AfxIsValidAddress(pUser, 1, false))			||
		(!AfxIsValidAddress(pPass, 1, false))			||
		(!AfxIsValidAddress(pTarget, 1, false))			||
		(!AfxIsValidAddress(pText, 1, false))			||
		(!AfxIsValidAddress(pEmailSubject, 1, false))	||
		(!AfxIsValidAddress(pEmailText, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}


	ISAPITRACE("0x%x %d AdminUnflagUser %s %s %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), pUser,
				pTarget, type);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminUnflagUser);	 
		pApp->AdminUnflagUser(pUser, pPass, 
							  pTarget, 
							  type, pText,
							  pEmailSubject, pEmailText,
							  auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminUnflagUser")

	EndContent(pCtxt);
}


//
// Personal Shopper
//
void CEBayISAPIExtension::PersonalShopperViewSearches(CHttpServerContext *pCtxt,
						 LPTSTR pUserId,
						 LPTSTR	pPassword,
						 int AcceptCookie,
						 LPTSTR pAgree)
{
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)	||
		!AfxIsValidAddress(pPassword, 1, false)	||
		!AfxIsValidAddress(pAgree, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PersonalShopperViewSearches %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPassword);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pUserId, pPassword, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PagePersonalShopperViewSearches);
		pApp->PersonalShopperViewSearches((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPassword,
							(char *) pAgree);


		*pApp->mpStream << flush;

	MYCATCH("PersonalShopperViewSearches")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::PersonalShopperAddSearch(CHttpServerContext *pCtxt,
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
						 LPTSTR pAgree)
{
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||	
		!AfxIsValidAddress(pQuery, 1, false)		||	
		!AfxIsValidAddress(pSearchDesc, 1, false)	||	
		!AfxIsValidAddress(pMinPrice, 1, false)		||	
		!AfxIsValidAddress(pMaxPrice, 1, false)		||
		!AfxIsValidAddress(EmailFrequency, 1, false) ||	
		!AfxIsValidAddress(EmailDuration, 1, false) ||
		!AfxIsValidAddress(pRegId, 1, false)		||
		!AfxIsValidAddress(pAgree, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PersonalShopperViewSearches %s %s %.40s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pQuery, pRegId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pUserId, pPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PagePersonalShopperAddSearch);
		pApp->PersonalShopperAddSearch((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPass,
							(char *) pQuery,
							(char *) pSearchDesc,
							(char *) pMinPrice,
							(char *) pMaxPrice,
							(char *) EmailFrequency,
							(char *) EmailDuration,
							(char *) pRegId,
							(char *) pAgree);

		*pApp->mpStream << flush;

	MYCATCH("PersonalShopperAddSearch")

	EndContent(pCtxt);

}


void CEBayISAPIExtension::PersonalShopperSaveSearch(CHttpServerContext *pCtxt,
					 LPTSTR pUserId,
					 LPTSTR pPass,
					 int	AcceptCookie,
					 LPTSTR pQuery,
					 LPTSTR	pSearchDesc,
					 LPTSTR pMinPrice,
					 LPTSTR pMaxPrice,
					 LPTSTR	EmailFrequency,
					 LPTSTR	EmailDuration,
					 LPTSTR	pRegId)
{
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPass, 1, false)			||
		!AfxIsValidAddress(pQuery, 1, false)		||
		!AfxIsValidAddress(pSearchDesc, 1, false)	||
		!AfxIsValidAddress(pMinPrice, 1, false)		||
		!AfxIsValidAddress(pMaxPrice, 1, false)		||
		!AfxIsValidAddress(EmailFrequency, 1, false)||
		!AfxIsValidAddress(EmailDuration, 1, false) ||
		!AfxIsValidAddress(pRegId, 1, false)	)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PersonalShopperSaveSearch %s %s %.40s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPass, pQuery, pRegId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pUserId, pPass, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PagePersonalShopperSaveSearch);
		pApp->PersonalShopperSaveSearch((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPass,
							(char *) pQuery,
							(char *) pSearchDesc,
							(char *) pMinPrice,
							(char *) pMaxPrice,
							(char *) EmailFrequency,
							(char *) EmailDuration,
							(char *) pRegId);

		*pApp->mpStream << flush;

	MYCATCH("PersonalShopperSaveSearch")

	EndContent(pCtxt);


}

void CEBayISAPIExtension::PersonalShopperDeleteSearchView(CHttpServerContext *pCtxt,
					 LPTSTR pUserId,
					 LPTSTR pPassword,
					 int	AcceptCookie,
					 LPTSTR pQuery,
					 LPTSTR	pSearchDesc,
					 LPTSTR pMinPrice,
					 LPTSTR pMaxPrice,
					 LPTSTR	EmailFrequency,
					 LPTSTR	EmailDuration,
					 LPTSTR	pRegId)
{
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)		||
		!AfxIsValidAddress(pPassword, 1, false)		||
		!AfxIsValidAddress(pQuery, 1, false)		||
		!AfxIsValidAddress(pSearchDesc, 1, false)	||
		!AfxIsValidAddress(pMinPrice, 1, false)		||
		!AfxIsValidAddress(pMaxPrice, 1, false)		||
		!AfxIsValidAddress(EmailFrequency, 1, false)||
		!AfxIsValidAddress(EmailDuration, 1, false) ||
		!AfxIsValidAddress(pRegId, 1, false)	)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PersonalShopperDeleteSearchView %s %s %.40s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPassword, pQuery, pRegId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pUserId, pPassword, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PagePersonalShopperDeleteSearchView);
		pApp->PersonalShopperDeleteSearchView((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPassword,
							(char *) pQuery,
							(char *) pSearchDesc,
							(char *) pMinPrice,
							(char *) pMaxPrice,
							(char *) EmailFrequency,
							(char *) EmailDuration,
							(char *) pRegId);

		*pApp->mpStream << flush;

	MYCATCH("PersonalShopperDeleteSearchView")

	EndContent(pCtxt);


}

void CEBayISAPIExtension::PersonalShopperDeleteSearch(CHttpServerContext *pCtxt,
					 LPTSTR pUserId,
					 LPTSTR pPassword,
					 int	AcceptCookie,
					 LPTSTR	pRegId)
{
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pUserId, 1, false)	||
		!AfxIsValidAddress(pPassword, 1, false) ||
		!AfxIsValidAddress(pRegId, 1, false)	)
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PersonalShopperDeleteSearch %s %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pPassword, pRegId);


	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (AcceptCookie == 1 && pApp->DropUserIdCookie(pUserId, pPassword, pCtxt))
	{
		*pApp->mpStream << "<HTML><HEAD>";
	}
	else
	{
		StartContent(pCtxt);
	}

	MYTRY
		pApp->SetCurrentPage(PagePersonalShopperDeleteSearch);
		pApp->PersonalShopperDeleteSearch((CEBayISAPIExtension *)this, 
							(char *) pUserId,
							(char *) pPassword,
							(char *) pRegId);

		*pApp->mpStream << flush;

	MYCATCH("PersonalShopperDeleteSearch")

	EndContent(pCtxt);
}

//Show TopSller info and ask to sign user agreement -- vicki
void CEBayISAPIExtension::PowerSellerRegisterShow(CHttpServerContext *pCtxt,
							 LPTSTR pUserId,
							 LPTSTR pPass)
{
	clseBayApp	*pApp;

	// Sanity
	if (!ValidateUserId((char *)pUserId) || 
		!ValidatePassword((char *)pPass))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	
	ISAPITRACE("0x%x %d PowerSellerRegisterShow %s %s\n",
		GetCurrentThreadId(), GetCurrentThreadId(),
		pUserId, pPass);
	
	StartContent(pCtxt);
	
	pApp	= (clseBayApp *)GetApp();
	
	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();
	
	pApp->InitISAPI((unsigned char *)pCtxt);
	
	MYTRY
		pApp->SetCurrentPage(PagePowerSellerRegisterShow);
	
		pApp->PowerSellerRegisterShow((CEBayISAPIExtension *)this,
										pUserId, pPass);
		
		*pApp->mpStream << flush;
	
	MYCATCH("PowerSellerRegisterShow")
		
	EndContent(pCtxt);
}

void CEBayISAPIExtension::PowerSellerRegister(CHttpServerContext *pCtxt,
							 LPTSTR pUserId,
							 LPTSTR pPass,
							 LPSTR accept,
							 LPSTR decline)
						
{
	clseBayApp	*pApp;
	bool agree = false;
	bool notify = false;

	// Sanity
	if (!ValidateUserId((char *)pUserId) || 
		!ValidatePassword((char *)pPass)	||
		(!AfxIsValidAddress(accept, 1, false)) ||
		(!AfxIsValidAddress(decline, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d PagePowerSellerRegister\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);	

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return;
	}

	MYTRY
		pApp->SetCurrentPage(PagePowerSellerRegister);

// petra		if (accept && strcmp(accept, "Agree") == 0)
		if (decline && strcmp(decline, "default") == 0)		// petra
			agree = true;

		pApp->PowerSellerRegister((CEBayISAPIExtension *)this,
						pUserId, pPass, agree);

		*pApp->mpStream << flush;

	MYCATCH("PowerSellerRegister")

	EndContent(pCtxt);

}

///////////////////////////////////////////////////////////////////////////////////////
//Gurinder - 04/30/1999
//Admin option - AdminReInstateItem
int CEBayISAPIExtension::AdminReInstateItem(CHttpServerContext * pCtxt, LPTSTR pItemNo, LPTSTR pUserId, LPTSTR pPassword)
{
	clseBayApp	*pApp;
	eBayISAPIAuthEnum	auth;
	
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminReInstateItem %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminReInstateItem);

		pApp->AdminReInstateItem((CEBayISAPIExtension *)this,
				   			       pItemNo, auth, pUserId, pPassword);

			MYCATCH("AdminReInstateItem");

	EndContent(pCtxt);

	return callOK;

}


////////////////////////////////////////////////////////
//Gurinder
//Shows login page for AdminReInstateItem function
int CEBayISAPIExtension::AdminReInstateItemLogin(CHttpServerContext * pCtxt, LPTSTR pItemNo)
{
	clseBayApp	*pApp;
	
	if ((!AfxIsValidAddress(pItemNo, 1, false)))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return callOK;
	}

	ISAPITRACE("0x%x %d AdminReInstateItemLogin %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}


	MYTRY
		pApp->SetCurrentPage(PageAdminReInstateItemLogin);		
		pApp->AdminReInstateItemLogin(pItemNo);
		*pApp->mpStream << flush;

	MYCATCH("AdminReInstateItemLogin")

	EndContent(pCtxt);

	return callOK;
}
//Gurinder - 04/30/1999


int CEBayISAPIExtension::GetUserAboutMe(CHttpServerContext* pCtxt,
									  LPSTR pUser)
									  
{
	clseBayApp	*pApp;
	bool			ok;
	char			reDirectURL[512];
	unsigned long	reDirectURLLen;

	
	ISAPITRACE("0x%x %d GetUserAboutMe %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUser);


	StartContent(pCtxt);
	ok = false;
	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageGetUserAboutMe);

	ok = pApp->GetUserAboutMe((CEBayISAPIExtension *)this,
								pUser,
					  			reDirectURL);

	*pApp->mpStream << flush;

	MYCATCH("GetUserAboutMe")
	if (ok && (strcmp(reDirectURL, "-") != 0))
	{
		EbayRedirect(pCtxt, reDirectURL);
		reDirectURLLen	= strlen(reDirectURL);
/*		pCtxt->ServerSupportFunction(HSE_REQ_SEND_URL_REDIRECT_RESP,
									 reDirectURL,
									 &reDirectURLLen,
									 (DWORD *)NULL); */
	}
	else
		EndContent(pCtxt);

	return callOK;

} 


void CEBayISAPIExtension::MemberSearchShow(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d MemberSearchShow\n",
				GetCurrentThreadId(), GetCurrentThreadId());


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageMemberSearchShow);

	pApp->MemberSearchShow(this);
	*pApp->mpStream << flush;

	MYCATCH("MemberSearchShow")

	EndContent(pCtxt);
}

// Admin Special Tool to pick out approved gallery items, or black listed, or
// staff picks.
// Main Page
void CEBayISAPIExtension::AdminSpecialItemsTool(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	

	ISAPITRACE("0x%x %d AdminSpecialItemsTool %d\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminSpecialItemsTool);

	pApp->AdminSpecialItemsTool((CEBayISAPIExtension *)this, auth);

	MYCATCH("AdminSpecialItemsTool");

	EndContent(pCtxt);

	return;
} 

// Add
void CEBayISAPIExtension::AdminSpecialItemAdd(CHttpServerContext* pCtxt,
											  LPCTSTR pItemNo, int kind)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	
	// Do this FIRST so ISAPITRACE doesn't bite it!
	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminSpecialItemAdd %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pItemNo, kind);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminSpecialItemAdd);

	pApp->AdminSpecialItemAdd((CEBayISAPIExtension *)this, 
							   (char *)pItemNo, kind, auth);

	MYCATCH("AdminSpecialItemAdd");

	EndContent(pCtxt);

	return;
} 

// Delete
void CEBayISAPIExtension::AdminSpecialItemDelete(CHttpServerContext* pCtxt,
												 LPCTSTR pItemNo)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	
	// Do this FIRST so ISAPITRACE doesn't bite it!
	if (!AfxIsValidAddress(pItemNo, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	ISAPITRACE("0x%x %d AdminSpecialItemDelete %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),pItemNo);

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminSpecialItemDelete);

	pApp->AdminSpecialItemDelete((CEBayISAPIExtension *)this,
							     (char *)pItemNo, auth);

	MYCATCH("AdminSpecialItemDelete");

	EndContent(pCtxt);

	return;
} 

// Flush ended auction
void CEBayISAPIExtension::AdminSpecialItemFlush(CHttpServerContext* pCtxt)
{
	clseBayApp	*pApp;

	eBayISAPIAuthEnum	auth;
	

	ISAPITRACE("0x%x %d AdminSpecialItemFlush %d\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	auth	= DetermineAuthorization(pCtxt);

	StartContent(pCtxt);

	pApp	= (clseBayApp *)GetApp();

	if (!pApp)
		pApp	= (clseBayApp *)CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
	pApp->SetCurrentPage(PageAdminSpecialItemFlush);

	pApp->AdminSpecialItemFlush((CEBayISAPIExtension *)this, auth);

	MYCATCH("AdminSpecialItemFlush");

	EndContent(pCtxt);

	return;
} 

void CEBayISAPIExtension::AdminSelectCobrandAdSiteShow(CHttpServerContext *pCtxt)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	ISAPITRACE("0x%x %d AdminSelectCobrandAdSiteShow\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminSelectCobrandAdSiteShow);	 
		pApp->AdminSelectCobrandAdSiteShow(this, auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminSelectCobrandAdSiteShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminSelectCobrandAdPartnerAndPageShow(CHttpServerContext *pCtxt,
																 int adId,
																 int siteId)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	ISAPITRACE("0x%x %d AdminSelectCobrandAdPartnerAndPageShow %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(), adId, siteId);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminSelectCobrandAdPartnerAndPageShow);	 
		pApp->AdminSelectCobrandAdPartnerAndPageShow(this, adId, siteId, auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminSelectCobrandAdPartnerAndPageShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminAddCobrandAdToSitePageConfirm(CHttpServerContext *pCtxt,
															 int adId,
															 int siteId,
															 int partnerId,
															 int pageType1,	// primary page type
															 int pageType2)	// secondary page type
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	ISAPITRACE("0x%x %d AdminAddCobrandAdToSitePageConfirm %d %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				adId, siteId, partnerId, pageType1, pageType2);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
		pApp->SetCurrentPage(PageAdminAddCobrandAdToSitePageConfirm);	 
		pApp->AdminAddCobrandAdToSitePageConfirm(this, 
												 adId,
												 siteId,
												 partnerId, 
												 (PageTypeEnum)pageType1, 
												 (PageTypeEnum)pageType2,
												 auth);

		*pApp->mpStream << flush;
	MYCATCH("AdminAddCobrandAdToSitePageConfirm")
	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminAddCobrandAdToSitePage(CHttpServerContext *pCtxt,
													  int adId,
													  int siteId,
													  int partnerId,
													  int pageType1,	// primary
													  int pageType2,	// secondary
													  int contextValue)	// context-sensitive
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	// Sanity
	ISAPITRACE("0x%x %d AdminAddCobrandAdToSitePage %d %d %d %d %d %d\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				adId, siteId, partnerId, pageType1, pageType2, contextValue);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
		pApp->SetCurrentPage(PageAdminAddCobrandAdToSitePage);	 
		pApp->AdminAddCobrandAdToSitePage(this,
										  adId,
										  siteId, 
										  partnerId, 
										  (PageTypeEnum)pageType1,
										  (PageTypeEnum)pageType2,
										  contextValue,
										  auth);

		*pApp->mpStream << flush;

	MYCATCH("AdminAddCobrandAdToSitePage")

	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminAddCobrandAdShow(CHttpServerContext *pCtxt)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;


	ISAPITRACE("0x%x %d AdminAddCobrandAdShow\n",
				GetCurrentThreadId(), GetCurrentThreadId());

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);
	MYTRY
		pApp->SetCurrentPage(PageAdminAddCobrandAdShow);	 
		pApp->AdminAddCobrandAdShow(this, auth);


		*pApp->mpStream << flush;

	MYCATCH("AdminAddCobrandAdShow")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AdminAddCobrandAdConfirm(CHttpServerContext *pCtxt,
												   LPSTR pName,
												   LPSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pName, 1, false) ||
		!AfxIsValidAddress(pText, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	// Sanity
	ISAPITRACE("0x%x %d AdminAddCobrandAdConfirm %.20s %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				(char *)pName, (char *)pText);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
		pApp->SetCurrentPage(PageAdminAddCobrandAdConfirm);	 
		pApp->AdminAddCobrandAdConfirm(this, (char *)pName, (char *)pText, auth);

		*pApp->mpStream << flush;
	MYCATCH("AdminAddCobrandAdConfirm")
	EndContent(pCtxt);
}


void CEBayISAPIExtension::AdminAddCobrandAd(CHttpServerContext *pCtxt,
											LPSTR pName,
											LPSTR pText)
{

	eBayISAPIAuthEnum	auth;
	clseBayApp			*pApp;

	if (!AfxIsValidAddress(pName, 1, false) ||
		!AfxIsValidAddress(pText, 1, false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
		return;
	}

	// Sanity
	ISAPITRACE("0x%x %d AdminAddCobrandAd %.20s %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(), 
				(char *)pName, (char *)pText);

	StartContent(pCtxt);

	auth	= DetermineAuthorization(pCtxt);

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY
		pApp->SetCurrentPage(PageAdminAddCobrandAd);	 
		pApp->AdminAddCobrandAd(this, (char *)pName, (char *)pText, auth);

		*pApp->mpStream << flush;

	MYCATCH("AdminAddCobrandAd")

	EndContent(pCtxt);
}

void CEBayISAPIExtension::AOLRegisterShow( CHttpServerContext *pCtxt,
											LPSTR	pAOLName)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AOLRegisterShow %.20s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pAOLName);


	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	StartContent(pCtxt);

	pApp->InitISAPI((unsigned char *)pCtxt);

	MYTRY

	pApp->SetCurrentPage(PageAOLRegisterShow);

	pApp->AOLRegisterShow((CEBayISAPIExtension *)this,
							pAOLName);

	*pApp->mpStream << flush;

	MYCATCH("AOLRegisterShow")

	EndContent(pCtxt);
}

// nsacco 07/07/99 - added siteId and coPartnerId
int CEBayISAPIExtension::AOLRegisterPreview(CHttpServerContext *pCtxt,
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
								  int referral,
								  LPTSTR pTradeshow_source1,
								  LPTSTR pTradeshow_source2,
								  LPTSTR pTradeshow_source3,
								  LPTSTR pFriend_email,
								  int purpose,
								  int interested_in,
								  int age,
								  int education,
								  int income,
								  int survey,
								  LPTSTR pNewPass,
								  LPTSTR pNewPass2,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify
								  )
{
	clseBayApp	*pApp;
	char *pStr;
	char cookieBuffer[4096];
	unsigned long cookieLength;
	int partnerId;

	ISAPITRACE("0x%x %d AOLRegisterPreview %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false)	||
		!AfxIsValidAddress(pEmail,		 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pCompany,  	 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pAddress,	 1,	false)	||
		!AfxIsValidAddress(pCity,		 1,	false)	||
		!AfxIsValidAddress(pState,		 1,	false)	||
		!AfxIsValidAddress(pZip,		 1,	false)	||
		!AfxIsValidAddress(pCountry,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	 1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	 1,	false)	||
		!AfxIsValidAddress(pGender,		 1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,	1,	false) ||
		!AfxIsValidAddress(pFriend_email,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	cookieLength = 4095;
		if (pCtxt->GetServerVariable("HTTP_COOKIE", cookieBuffer, &cookieLength))
		{
			// Already set. Return.
			pStr = strstr(cookieBuffer, "p=");
			if (pStr && ((pStr == cookieBuffer) || isspace(*(pStr - 1))))
			{
				partnerId = atoi(pStr + 2);
			}
			else
			{
				partnerId = 0;
			}
		}
		else
		{
			partnerId = 0;
		}
		pApp->SetCurrentPage(PageAOLRegisterPreview);

		// nsacco 07/07/99 - added siteId and coPartnerId
		pApp->AOLRegisterPreview((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,
					   referral,
					   (char *) pTradeshow_source1,
					   (char *) pTradeshow_source2,
					   (char *) pTradeshow_source3,
					   (char *) pFriend_email,
					   purpose,
					   interested_in,
					   age,
					   education,
					   income,
					   survey,   
					   pNewPass,
					   pNewPass2,
					   nPartnerId,
					   siteId,
					   coPartnerId,
					   UsingSSL,
					   nVerify
					   );
		*pApp->mpStream << flush;

	MYCATCH("AOLRegisterPreview")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

// nsacco 07/07/99 - added siteId and coPartnerId
int CEBayISAPIExtension::AOLRegisterUserID(CHttpServerContext *pCtxt,
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
								  int referral,
								  LPTSTR pTradeshow_source1,
								  LPTSTR pTradeshow_source2,
								  LPTSTR pTradeshow_source3,
								  LPTSTR pFriend_email,
								  int purpose,
								  int interested_in,
								  int age,
								  int education,
								  int income,
								  int survey,
								  LPTSTR pNewPass,
								  LPTSTR pNewPass2,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify
								  )
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AOLRegisterUserID %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false)	||
		!AfxIsValidAddress(pEmail,		 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pCompany,  	 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pAddress,	 1,	false)	||
		!AfxIsValidAddress(pCity,		 1,	false)	||
		!AfxIsValidAddress(pState,		 1,	false)	||
		!AfxIsValidAddress(pZip,		 1,	false)	||
		!AfxIsValidAddress(pCountry,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	 1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	 1,	false)	||
		!AfxIsValidAddress(pGender,		 1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,	1,	false) ||
		!AfxIsValidAddress(pFriend_email,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageAOLRegisterUserID);

	// nsacco 07/07/99 - added siteId and coPartnerId
	pApp->AOLRegisterUserID((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,
					   referral,
					   (char *) pTradeshow_source1,
					   (char *) pTradeshow_source2,
					   (char *) pTradeshow_source3,
					   (char *) pFriend_email,
					   purpose,
					   interested_in,
					   age,
					   education,
					   income,
					   survey,   
					   pNewPass,
					   pNewPass2,
					   nPartnerId,
					   siteId,
					   coPartnerId,
					   UsingSSL,
					   nVerify
					   );
	*pApp->mpStream << flush;

	MYCATCH("AOLRegisterUserID")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

// nsacco 07/07/99 - added siteId and coPartnerId
int CEBayISAPIExtension::AOLRegisterUserAgreement(CHttpServerContext *pCtxt,
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
								  int referral,
								  LPTSTR pTradeshow_source1,
								  LPTSTR pTradeshow_source2,
								  LPTSTR pTradeshow_source3,
								  LPTSTR pFriend_email,
								  int purpose,
								  int interested_in,
								  int age,
								  int education,
								  int income,
								  int survey,
								  LPTSTR pNewPass,
								  int nPartnerId,
								  int siteId,
								  int coPartnerId,
								  int UsingSSL,
								  int nVerify
								  )
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AOLRegisterUserAgreement %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false)	||
		!AfxIsValidAddress(pEmail,		 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pCompany,  	 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pAddress,	 1,	false)	||
		!AfxIsValidAddress(pCity,		 1,	false)	||
		!AfxIsValidAddress(pState,		 1,	false)	||
		!AfxIsValidAddress(pZip,		 1,	false)	||
		!AfxIsValidAddress(pCountry,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	 1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	 1,	false)	||
		!AfxIsValidAddress(pGender,		 1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,	1,	false) ||
		!AfxIsValidAddress(pFriend_email,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageAOLRegisterUserAgreement);

	// nsacco 07/07/99 - added siteId and coPartnerId
	pApp->AOLRegisterUserAgreement((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,
					   referral,
					   (char *) pTradeshow_source1,
					   (char *) pTradeshow_source2,
					   (char *) pTradeshow_source3,
					   (char *) pFriend_email,
					   purpose,
					   interested_in,
					   age,
					   education,
					   income,
					   survey,   
					   pNewPass,
					   nPartnerId,
					   siteId,
					   coPartnerId,
					   UsingSSL,
					   nVerify
					   );
		*pApp->mpStream << flush;

	MYCATCH("AOLRegisterUserAgreement")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

// nsacco 07/07/99 - added siteId and coPartnerId
int CEBayISAPIExtension::AOLRegisterUserAcceptAgreement(CHttpServerContext *pCtxt,
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
								  int referral,
								  LPTSTR pTradeshow_source1,
								  LPTSTR pTradeshow_source2,
								  LPTSTR pTradeshow_source3,
								  LPTSTR pFriend_email,
								  int purpose,
								  int interested_in,
								  int age,
								  int education,
								  int income,
								  int survey,
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
								  int nVerify
								  )
{
	clseBayApp	*pApp;
	int nAccept = false;

	ISAPITRACE("0x%x %d AOLRegisterUserAcceptAgreement %s %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId, pEmail);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false)	||
		!AfxIsValidAddress(pEmail,		 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pCompany,  	 1,	false)	||
		!AfxIsValidAddress(pName,		 1,	false)	||
		!AfxIsValidAddress(pAddress,	 1,	false)	||
		!AfxIsValidAddress(pCity,		 1,	false)	||
		!AfxIsValidAddress(pState,		 1,	false)	||
		!AfxIsValidAddress(pZip,		 1,	false)	||
		!AfxIsValidAddress(pCountry,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone1,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone2,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone3,	 1,	false)	||
		!AfxIsValidAddress(pDayPhone4,	 1,	false)	||
		!AfxIsValidAddress(pNightPhone1, 1,	false)	||
		!AfxIsValidAddress(pNightPhone2, 1,	false)	||
		!AfxIsValidAddress(pNightPhone3, 1,	false)	||
		!AfxIsValidAddress(pNightPhone4, 1,	false)	||
		!AfxIsValidAddress(pFaxPhone1,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone2,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone3,	 1,	false)	||
		!AfxIsValidAddress(pFaxPhone4,	 1,	false)	||
		!AfxIsValidAddress(pGender,		 1,	false)	||
		!AfxIsValidAddress(pTradeshow_source3,	1,	false) ||
		!AfxIsValidAddress(pFriend_email,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY

// petra	if (pButtonPressed && (strstr(pButtonPressed, "Accept") != 0))
	if (pButtonPressed2 && strcmp(pButtonPressed2, "default") == 0)	// petra
		nAccept = true;

	pApp->SetCurrentPage(PageAOLRegisterUserAcceptAgreement);

	// nsacco 07/07/99 - added siteId and coPartnerId
	pApp->AOLRegisterUserAcceptAgreement((CEBayISAPIExtension *)this, 
					   (char *) pUserId,
					   (char *) pEmail,
					   (char *) pName,
					   (char *) pCompany,
					   (char *) pAddress,
					   (char *) pCity,
					   (char *) pState,
					   (char *) pZip,
					   (char *) pCountry,
					   countryId,
					   (char *) pDayPhone1,
					   (char *) pDayPhone2,
					   (char *) pDayPhone3,
					   (char *) pDayPhone4,
					   (char *) pNightPhone1,
					   (char *) pNightPhone2,
					   (char *) pNightPhone3,
					   (char *) pNightPhone4,
					   (char *) pFaxPhone1,
					   (char *) pFaxPhone2,
					   (char *) pFaxPhone3,
					   (char *) pFaxPhone4,
					   (char *) pGender,
					   referral,
					   (char *) pTradeshow_source1,
					   (char *) pTradeshow_source2,
					   (char *) pTradeshow_source3,
					   (char *) pFriend_email,
					   purpose,
					   interested_in,
					   age,
					   education,
					   income,
					   survey,   
					   pNewPass,
					   nAccept,
					   nNotify,
					   nAgreementQ1,
					   nAgreementQ2,
					   nPartnerId,
					   siteId,
					   coPartnerId,
					   UsingSSL,
					   nVerify
					   );
		*pApp->mpStream << flush;

	MYCATCH("AOLRegisterUserAcceptAgreement")

	EndContent(pCtxt);

	// PurifyNewLeaks(); ISAPITRACE1("New In Use %d\n", PurifyNewInuse());

	return callOK;

}

int CEBayISAPIExtension::AOLRegisterComplete(CHttpServerContext *pCtxt,
										  LPTSTR pUserId)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AOLRegisterComplete %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				pUserId);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,		 1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageAOLRegisterComplete);

	pApp->AOLRegisterComplete((CEBayISAPIExtension *)this, pUserId);
		
	*pApp->mpStream << flush;

	MYCATCH("AOLRegisterComplete")

	EndContent(pCtxt);

	return callOK;
}

int CEBayISAPIExtension::AOLRegisterConfirm(CHttpServerContext *pCtxt,
											int nConfirmation,
											LPTSTR pUserId,
											int nVerify)
{
	clseBayApp	*pApp;

	ISAPITRACE("0x%x %d AOLRegisterConfirm %d %s\n",
				GetCurrentThreadId(), GetCurrentThreadId(),
				nConfirmation, pUserId);



	StartContent(pCtxt);

	// Sanity
	if (!AfxIsValidAddress(pUserId,	1,	false))
	{
		OnParseError(pCtxt, clsEBayHttpServer::callBadParam);
	}

	pApp	= (clseBayApp *)GetApp();
	if (!pApp)
		pApp	= CreateeBayApp();

	pApp->InitISAPI((unsigned char *)pCtxt);

	if (IIS_Server_is_down()) {
		display_IIS_Server_down_page(pApp);
		return callOK;
	}

	MYTRY
	pApp->SetCurrentPage(PageAOLRegisterConfirm);

	pApp->AOLRegisterConfirm((CEBayISAPIExtension *)this, nConfirmation,
											pUserId,
											nVerify);
		
	*pApp->mpStream << flush;

	MYCATCH("AOLRegisterConfirm")

	EndContent(pCtxt);

	return callOK;
}


