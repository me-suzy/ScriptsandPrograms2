/*	$Id: eBayTypes.h,v 1.12.2.5.52.3 1999/08/03 05:39:30 nsacco Exp $	*/
//
//	File:		eBayTypes.h
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Common eBay Types and #defines
//
// Modifications:
//				- 02/07/97 michael	- Created
//				- 02/26/97 Wen		- Defined enum type ServerType
//				- 02/23/99 anoop	- Added UserActionEnum to enumerate various user actions
//				- 02/02/99 Wen		- Defined enum type for Login Pages
//				- 04/06/99 AlexP	- Added more entries into PageTypeEnum
//				- 05/24/99 jennifer - added functions for Gallery Admin Tool
//				- 05/12/99 nsacco	- Added Australia, Canada, Germany countries and currencies
//				- 06/16/99 nsacco	- Added new fees for AutoListing, AutoFinalValue, RealEstateListing
//				- 07/27/99 nsacco	- Added a new enumerated type for languages, for shipping options and
//										for shipping regions
//				- 08/02/99 nsacco	- Added SITE_EBAY_JP and new ListingLevelFees
//
#ifndef EBAYTYPES_INCLUDED

#ifdef _MSC_VER
#define IS_MULTITHREADED
#define IS_ISAPI
#endif

// This pragma avoid annoying warning messages
// about overlength names generated for STL
#pragma warning( disable : 4666 )
#pragma warning( disable : 4786 )


//#define UK_ONLY


#include <string.h>
#include <time.h>

// Class forward
class clsApp;

// The following icky variables are
// to support "gApp" in a multithreaded
// environment.
#ifdef IS_ISAPI
extern unsigned long g_tlsindex;
#endif

void SetApp(clsApp *pTheApp);
clsApp *GetApp();

#define gApp GetApp()

// Popular Enums

// User State values
typedef enum
{
	UserUnknown		= -1,
	UserSuspended	= 0,
	UserConfirmed	= 1,
	UserUnconfirmed	= 2,
	UserGhost		= 3,
	UserInMaintenance = 4,
	UserDeleted		= 5,
	UserCCVerify	= 6
} UserStateEnum;

// User action types
typedef enum
{
	MakeBid,
	AcceptBid,
	VerifyNewItem,
	AddNewItem,
	LeaveFeedback,
	AddToBoard
} UserActionEnum;


// AppTypeEnum indicates what sort of environment the 
// application is running in.
typedef enum
{
	APP_UNKNOWN			= 0,
	APP_SHELL			= 1,
	APP_CGI				= 2,
	APP_APACHE_MODULE	= 3,
	APP_ISAPI			= 4
} AppTypeEnum;

typedef enum
{
	PageTypeUnknown						= -1,
	PageType0							= 0,
	PageType1							= 1,
	PageType2							= 2,
    PageType3		                    = 3, 
	PageType4		                    = 4, 
	PageType5		                    = 5, 
	PageType6		                    = 6, 
	PageType7		                    = 7, 
	PageType8		                    = 8, 
	PageType9		                    = 9, 
	PageType10		                    = 10, 
	PageType11		                    = 11, 
	PageType12		                    = 12, 
	PageType13		                    = 13, 
	PageType14		                    = 14, 
	PageType15		                    = 15, 
	PageType16		                    = 16, 
	PageType17		                    = 17, 
	PageType18		                    = 18, 
	PageType19		                    = 19, 
	PageType20		                    = 20, 
	PageType21		                    = 21, 
	PageType22		                    = 22, 
	PageType23		                    = 23, 
	PageType24		                    = 24, 
	PageType25		                    = 25, 
	PageTypeLast						= 26 // Always make this highest.
} PageTypeEnum;

// Important definitions of limits
#define	EBAY_MAX_USERID_SIZE		64
#define	EBAY_MAX_PASSWORD_SIZE		64
#define EBAY_MAX_NAME_SIZE			64
#define EBAY_MAX_ADDRESS_SIZE		64
#define EBAY_MAX_CITY_SIZE			64
#define EBAY_MAX_STATE_SIZE			64
#define EBAY_MAX_ZIP_SIZE			12
#define EBAY_MAX_COUNTRY_SIZE		64
#define EBAY_MAX_PHONE_SIZE			32
#define EBAY_MAX_EMAIL_SIZE			64
#define EBAY_MAX_COMPANY_SIZE		64
#define EBAY_MAX_TITLE_SIZE			45
#define EBAY_MAX_LOCATION_SIZE		45
#define	EBAY_MAX_PICURL_SIZE		255
#define	EBAY_MAX_ZIP_SIZE			12
#define	EBAY_MAX_KEYWORD_SIZE		64
#define	EBAY_MAX_MESSAGETEXT_SIZE	2048

//
// 12/19/97 Charles added
// PRIVACY USER ID
// The new User ID (not the e-mail) will have
// a minimum length and a maximum length that we will define here
//
#define LOGINPROMPT			"User ID"
#define	EBAY_MIN_USERID_SIZE	2

// The length of item numbers
#define EBAY_MAX_ITEM_SIZE			10

// The following limit limits amounts to
// 10,000,000, or 1,000,000.00. I don't 
// think this is a problem
// NOTE: EBAY_MAX_xxx_SIZE limits the number of characters that can be entered
#define EBAY_MAX_DOLLAR_SIZE		11
#define EBAY_MAX_DOLLAR_AMOUNT		10000000

// UK
#define EBAY_MAX_POUND_SIZE			11
#define EBAY_MAX_POUND_AMOUNT		10000000

// PH added 04/26/99 (we'll have to increase this for Italy :-) )
// Germany
#define EBAY_MAX_DEM_SIZE			11
#define EBAY_MAX_DEM_AMOUNT			20000000

// nsacco added 05/21/99
// Canada
#define EBAY_MAX_CAD_SIZE			11
#define EBAY_MAX_CAD_AMOUNT			16000000

// Australia
#define EBAY_MAX_AUD_SIZE			11
#define EBAY_MAX_AUD_AMOUNT			16000000

// nsacco 07/13/99
// France
#define EBAY_MAX_FRF_SIZE			12
#define EBAY_MAX_FRF_AMOUNT			70000000
// Japan
#define EBAY_MAX_JPY_SIZE			11
#define EBAY_MAX_JPY_AMOUNT			1250000000
// Euro
#define EBAY_MAX_EUR_SIZE			11
#define EBAY_MAX_EUR_AMOUNT			10000000
// Sweden
#define EBAY_MAX_SEK_SIZE			12
#define EBAY_MAX_SEK_AMOUNT			90000000
// China
#define EBAY_MAX_CNY_SIZE			12
#define EBAY_MAX_CNY_AMOUNT			90000000
// Spain
#define EBAY_MAX_ESP_SIZE			11
#define EBAY_MAX_ESP_AMOUNT			1700000000
// Norway
#define EBAY_MAX_NOK_SIZE			12
#define EBAY_MAX_NOK_AMOUNT			90000000
// Denmark
#define EBAY_MAX_DKK_SIZE			12
#define EBAY_MAX_DKK_AMOUNT			80000000
// Finland
#define EBAY_MAX_FIM_SIZE			12
#define EBAY_MAX_FIM_AMOUNT			60000000

// The following limit limits quantities to
// 100,000. 
#define EBAY_MAX_QUANTITY_SIZE		6
#define EBAY_MAX_QUANTITY_AMOUNT	100000		

#define ONE_DAY 24*60*60

// MarketPlaceId identifies a marketplace
typedef unsigned int MarketPlaceId;

// CategoryId identifies a category
typedef unsigned int CategoryId;

// ItemId identifies an item id
typedef unsigned int ItemId;

// UserId identifies a numeric user id
typedef unsigned int UserId;

// TransactionId identifies a transaction
typedef unsigned int TransactionId;

// Blah, Blah, blah Blah
typedef	unsigned int BulletinBoardId;

// eqstr is used all over the place to manage
// hash maps and sets
struct eqstr
{
	bool operator()(const char* s1, const char* s2) const
	{
		return strcmp(s1, s2) == 0;
	}
};

// eqint is used all over the place to manage
// hash maps and sets
struct eqint
{
        bool operator()(const int s1, const int s2) const
        {
                return s1 == s2;
        }
};

// eqtime is used all over the place to manage
// hash maps and sets
struct eqtime
{
        bool operator()(const time_t s1, const time_t s2) const
        {
                return s1 == s2;
        }
};


// ServerTypeEnum indicates what kind of server 
// extension is.
typedef enum
{
	SERVER_UNKNOWN			= 0,
	SERVER_USER				= 1,
	SERVER_REGISTER			= 2,
	SERVER_ITEM				= 3,
	SERVER_BID				= 4,
	SERVER_CATEGORY			= 5,
	SERVER_ADMIN			= 6,
	SERVER_EBAY				= 7,
	SERVER_LAST				= 8			// this has to be the last item on the list
} ServerTypeEnum;

extern ServerTypeEnum gServerId;

// auction item status
//
typedef enum
{
	LISTING		= 0,
	NEW_TODAY	= 1,
	END_TODAY	= 2,
	COMPLETED	= 3,
	GOING		= 4
} TimeCriterion;

// statistics
//
typedef enum
{
	DIALY_STATISTICS = 0
} StatisticsEnum;

// Transaction
typedef enum
{
	NewChineseAuction		= 0,
	CompletedChineseAuction = 1,
	NewDutchAuction			= 2,
	CompletedDutchAuction	= 3,
	NewReserveAuction		= 4,
	CompletedReserverAuction = 5,
	NewPrivateAuction		= 6,
	CompletedPrivateAuction = 7
} TransactionEnum;


typedef enum
{
	Category			= 1,
	Announcement		= 2,
	Financial			= 3,
	Statistics			= 4,
	Billing				= 5
} AdminFunctionEnum;


// 
// This Enum tells us how to sort
// a list of items
//
typedef enum
{
	SortItemsByUnknown				= 0,	
	SortItemsById					= 1,
	SortItemsByStartTime			= 2,
	SortItemsByEndTime				= 3,
	SortItemsByPrice				= 4,
	SortItemsByTitle				= 5,
	SortItemsByHighBidder			= 6,		
	SortItemsByIdReverse			= 7,
	SortItemsByStartTimeReverse		= 8,	
	SortItemsByEndTimeReverse		= 9,		
	SortItemsByPriceReverse			= 10,
	SortItemsByStartPrice			= 11,
	SortItemsByStartPriceReverse	= 12,	
	SortItemsByReservePrice			= 13,
	SortItemsByReservePriceReverse	= 14,
	SortItemsByBidCount				= 15,
	SortItemsByBidCountReverse		= 16,
	SortItemsByQuantity				= 17,
	SortItemsByQuantityReverse		= 18,
	SortItemsByTitleReverse			= 19,
    SortItemsByBADVALUE             = 20
} ItemListSortEnum; 

// which reciprocalLink picture we're using
typedef enum
{
	RecipLinkHomePic = 1,
	RecipLinkMyAuctionsPic = 2
} RecipLinkEnum;


// Occasion for Gift Alert
typedef enum
{
	GiftOccasionUnknown				= 0,
	GiftOccasionDecemberHolidays	= 1,
	// All new entries go ABOVE this line!!!
	GiftOccasionLast
} GiftOccasionEnum;

typedef struct
{
	char *	eName;
	char *	eGreeting;
	char *	eHeaderFilename;
	char *	eFooterFilename;
} GiftOccasionRec;

typedef enum
{
	eGetActive,
	eGetSuperFeatured,
	eGetHot,
	eGetCompleted,
	eGetEnding,
	eGetStaffPicks,
	eGetBlackList,
	eGetAllFeatured,
	eGetHotNonDutch,
	eGetActiveRandom,
	eGetHighTicket,
	eGetGalleryList
} GetItemIdsEnum;

typedef enum {
		FeaturedFee,
		NewFeaturedFee,
		CategoryFeaturedFee,
		NewCategoryFeaturedFee,
		BoldFee,
		GiftIconFee,
		GalleryFee,
		GalleryFeaturedFee,
		ItemMoveFee,
		// nsacco 06/16/99
		AutoListingFee,
		RealEstateListingFee,
		AutoFinalValueFee,
		// nsacco 08/02/99
		ListingLevel1Fee,
		ListingLevel2Fee,
		ListingLevel3Fee,
		ListingLevel4Fee,
		ListingLevel1Range,
		ListingLevel2Range,
		ListingLevel3Range,
		FinalValueLevel1Cutoff,
		FinalValueLevel2Cutoff,
		FeeEnumSize
} FeeEnum;

typedef enum
{
	REGULAR_POOL		= 0,
	REG_POOL			= 1,
	HELP_POOL			= 2
} MailPoolEnum;

typedef struct
{
	int IconType;
	char *IconName;
	char *IconImage;
} IconInfo;

typedef enum
{
	GiftIconUnknown								= 0,
	Father										= 1,	
	RosieIcon									= 2,
	Anniversary									= 3,
	Baby										= 4,		
	Birthday									= 5,	
	Christmas									= 6,	
	Easter										= 7,		
	Graduation									= 8,
	Halloween									= 9,
	Hanukah										= 10,
	July4th										= 11,		
	Mother										= 12,		
	Stpatrick									= 13,
	Thanksgiving								= 14,	
	Valentine									= 15,
	Wedding										= 16

} GiftIconType;

// nsacco 07/27/99
// languages
typedef enum {
	English = 0,
	German = 1,
	French = 2,
	Japanese = 3,
	Norwegian = 4,
	Swedish = 5,
	Finnish = 6,
	Spanish = 7,
	Danish = 8
} LanguageEnum;
// end languages

// nsacco 07/27/99
// shipping regions
typedef enum {
	ShipRegion_None =			0x00000000,
	ShipRegion_NorthAmerica =	0x00000001,
	ShipRegion_Europe =			0x00000002,
	ShipRegion_Oceania =		0x00000004,
	ShipRegion_Asia =			0x00000008,
	ShipRegion_SouthAmerica =	0x00000010,
	ShipRegion_Africa =			0x00000020,
	ShipRegion_LatinAmerica =	0x00000040,
	ShipRegion_MiddleEast =		0x00000080,
	ShipRegion_Caribbean =		0x00000100
} ShippingRegionsEnum;
// end shipping regions

// nsacco 07/27/99
// shipping options
typedef enum {
	SiteOnly = 0,
	SitePlusRegions = 1,
	Worldwide = 2
} ShippingOptionsEnum;
// end shipping options

// For countries
typedef enum {
	Country_None  = 0,		// default global site
	Country_US    = 1,		// US
	Country_CA    = 2,		// Canada
	Country_UK    = 3,		// UK
	Country_DE    = 77,		// Germany
	Country_AU	  = 15,		// Australia nsacco 05/24/99
	Country_JP	  = 104,	// Japan
	Country_FR	  = 71		// France
/*
2">Canada</OPTION>
3">United Kingdom</OPTION>
4">Afghanistan</OPTION>
5">Albania</OPTION>
6">Algeria</OPTION>
7">American Samoa</OPTION>
8">Andorra</OPTION>
9">Angola</OPTION>
10">Anguilla</OPTION>
11">Antigua and Barbuda</OPTION>
12">Argentina</OPTION>
13">Armenia</OPTION>
14">Aruba</OPTION>
15">Australia</OPTION>
16">Austria</OPTION>
17">Azerbaijan Republic</OPTION>
18">Bahamas</OPTION>
19">Bahrain</OPTION>
20">Bangladesh</OPTION>
21">Barbados</OPTION>
22">Belarus</OPTION>
23">Belgium</OPTION>
24">Belize</OPTION>
25">Benin</OPTION>
26">Bermuda</OPTION>
27">Bhutan</OPTION>
28">Bolivia</OPTION>
29">Bosnia and Herzegovina</OPTION>
30">Botswana</OPTION>
31">Brazil</OPTION>
32">British Virgin Islands</OPTION>
33">Brunei Darussalam</OPTION>
34">Bulgaria</OPTION>
35">Burkina Faso</OPTION>
36">Burma</OPTION>
37">Burundi</OPTION>
38">Cambodia</OPTION>
39">Cameroon</OPTION>
40">Cape Verde Islands</OPTION>
41">Cayman Islands</OPTION>
42">Central African Republic</OPTION>
43">Chad</OPTION>
44">Chile</OPTION>
45">China</OPTION>
46">Colombia</OPTION>
47">Comoros</OPTION>
48">Congo, Democratic Republic of the</OPTION>
49">Congo, Republic of the</OPTION>
50">Cook Islands</OPTION>
51">Costa Rica</OPTION>
52">Cote d Ivoire (Ivory Coast)</OPTION>
53">Croatia, Democratic Republic of the</OPTION>
54">Cuba</OPTION>
55">Cyprus</OPTION>
56">Czech Republic</OPTION>
57">Denmark</OPTION>
58">Djibouti</OPTION>
59">Dominica</OPTION>
60">Dominican Republic</OPTION>
61">Ecuador</OPTION>
62">Egypt</OPTION>
63">El Salvador</OPTION>
64">Equatorial Guinea</OPTION>
65">Eritrea</OPTION>
66">Estonia</OPTION>
67">Ethiopia</OPTION>
68">Falkland Islands (Islas Makvinas)</OPTION>
69">Fiji</OPTION>
70">Finland</OPTION>
71">France</OPTION>
72">French Guiana</OPTION>
73">French Polynesia</OPTION>
74">Gabon Republic</OPTION>
75">Gambia</OPTION>
76">Georgia</OPTION>
77">Germany</OPTION>
78">Ghana</OPTION>
79">Gibraltar</OPTION>
80">Greece</OPTION>
81">Greenland</OPTION>
82">Grenada</OPTION>
83">Guadeloupe</OPTION>
84">Guam</OPTION>
85">Guatemala</OPTION>
86">Guernsey</OPTION>
87">Guinea</OPTION>
88">Guinea-Bissau</OPTION>
89">Guyana</OPTION>
90">Haiti</OPTION>
91">Honduras</OPTION>
92">Hong Kong</OPTION>
93">Hungary</OPTION>
94">Iceland</OPTION>
95">India</OPTION>
96">Indonesia</OPTION>
97">Iran</OPTION>
98">Iraq</OPTION>
99">Ireland</OPTION>
100">Israel</OPTION>
101">Italy</OPTION>
102">Jamaica</OPTION>
103">Jan Mayen</OPTION>
104">Japan</OPTION>
105">Jersey</OPTION>
106">Jordan</OPTION>
107">Kazakhstan</OPTION>
108">Kenya Coast Republic</OPTION>
109">Kiribati</OPTION>
110">Korea, North</OPTION>
111">Korea, South</OPTION>
112">Kuwait</OPTION>
113">Kyrgyzstan</OPTION>
114">Laos</OPTION>
115">Latvia</OPTION>
116">Lebanon, South</OPTION>
117">Lesotho</OPTION>
118">Liberia</OPTION>
119">Libya</OPTION>
120">Liechtenstein</OPTION>
121">Lithuania</OPTION>
122">Luxembourg</OPTION>
123">Macau</OPTION>
124">Macedonia</OPTION>
125">Madagascar</OPTION>
126">Malawi</OPTION>
127">Malaysia</OPTION>
128">Maldives</OPTION>
129">Mali</OPTION>
130">Malta</OPTION>
131">Marshall Islands</OPTION>
132">Martinique</OPTION>
133">Mauritania</OPTION>
134">Mauritius</OPTION>
135">Mayotte</OPTION>
136">Mexico</OPTION>
137">Moldova</OPTION>
138">Monaco</OPTION>
139">Mongolia</OPTION>
140">Montserrat</OPTION>
141">Morocco</OPTION>
142">Mozambique</OPTION>
143">Namibia</OPTION>
144">Nauru</OPTION>
145">Nepal</OPTION>
146">Netherlands</OPTION>
147">Netherlands Antilles</OPTION>
148">New Caledonia</OPTION>
149">New Zealand</OPTION>
150">Nicaragua</OPTION>
151">Niger</OPTION>
152">Nigeria</OPTION>
153">Niue</OPTION>
154">Norway</OPTION>
155">Oman</OPTION>
156">Pakistan</OPTION>
157">Palau</OPTION>
158">Panama</OPTION>
159">Papua New Guinea</OPTION>
160">Paraguay</OPTION>
161">Peru</OPTION>
162">Philippines</OPTION>
163">Poland</OPTION>
164">Portugal</OPTION>
165">Puerto Rico</OPTION>
166">Qatar</OPTION>
167">Romania</OPTION>
168">Russian Federation</OPTION>
169">Rwanda</OPTION>
170">Saint Helena</OPTION>
171">Saint Kitts-Nevis</OPTION>
172">Saint Lucia</OPTION>
173">Saint Pierre and Miquelon</OPTION>
174">Saint Vincent and the Grenadines</OPTION>
175">San Marino</OPTION>
176">Saudi Arabia</OPTION>
177">Senegal</OPTION>
178">Seychelles</OPTION>
179">Sierra Leone</OPTION>
180">Singapore</OPTION>
181">Slovakia</OPTION>
182">Slovenia</OPTION>
183">Solomon Islands</OPTION>
184">Somalia</OPTION>
185">South Africa</OPTION>
186">Spain</OPTION>
187">Sri Lanka</OPTION>
188">Sudan</OPTION>
189">Suriname</OPTION>
190">Svalbard</OPTION>
191">Swaziland</OPTION>
192">Sweden</OPTION>
193">Switzerland</OPTION>
194">Syria</OPTION>
195">Tahiti</OPTION>
196">Taiwan</OPTION>
197">Tajikistan</OPTION>
198">Tanzania</OPTION>
199">Thailand</OPTION>
200">Togo</OPTION>
201">Tonga</OPTION>
202">Trinidad and Tobago</OPTION>
203">Tunisia</OPTION>
204">Turkey</OPTION>
205">Turkmenistan</OPTION>
206">Turks and Caicos Islands</OPTION>
207">Tuvalu</OPTION>
208">Uganda</OPTION>
209">Ukraine</OPTION>
210">United Arab Emirates</OPTION>
211">Uruguay</OPTION>
212">Uzbekistan</OPTION>
213">Vanuatu</OPTION>
214">Vatican City State</OPTION>
215">Venezuela</OPTION>
216">Vietnam</OPTION>
217">Virgin Islands (U.S.)</OPTION>
218">Wallis and Futuna</OPTION>
219">Western Sahara</OPTION>
220">Western Samoa</OPTION>
221">Yemen</OPTION>
222">Yugoslavia</OPTION>
223">Zambia</OPTION>
224">Zimbabwe</OPTION>
225">APO/FPO</OPTION>
*/
} CountryCodes;

// For currencies
// nsacco 6/11/99
// NOTE: As these are uncommented, increase num currencies in clsCurrencies.cpp
// and add entries to database table ebay_currencies

typedef enum {
	Currency_None  = 0,
	Currency_USD   = 1,		// US dollars
	Currency_CAD   = 2,		// Canada dollars
	Currency_GBP   = 3,		// Great Britain pounds
	Currency_DEM   = 4,		// Germany marks
	Currency_AUD   = 5,		// Australia dollars nsacco 05/24/99
	Currency_JPY   = 6,		// Japan yen
	Currency_EUR   = 7,		// euros
	Currency_FRF   = 8,		// France francs
	Currency_ARP   = 9,		// Argentina pesos
	Currency_ATS   = 10,	// Austria schillings
	Currency_BEF   = 11,	// Belgium francs
	Currency_BRL   = 12,	// Brazil reals
	Currency_CHF   = 13,	// Switzerland francs
	Currency_CNY   = 14,	// China yuan renminbi
	Currency_CLP   = 15,	// Chile pesos
	Currency_CZK   = 16,	// Czech korunas
	Currency_DKK   = 17,	// Denmark krones
	Currency_EGP   = 18,	// Egypt pounds
	Currency_ESP   = 19,	// Spain peseta
	Currency_FIM   = 20,	// Finland markkas
	Currency_GRD   = 21,	// Greece drachmas
	Currency_HKD   = 22,	// Hong Kong dollars
	Currency_HUF   = 23,	// Hungary forint
	Currency_IDR   = 24,	// Indonesia rupee
	Currency_IEP   = 25,	// Ireland pounds
	Currency_ILS   = 26,	// Israel new shekels
	Currency_ITL   = 27,	// Italy lira
	Currency_KRW   = 28,	// South Korea won
	Currency_LUF   = 29,	// Luxembourg francs
	Currency_MXP   = 30,	// Mexico pesos
	Currency_NLG   = 31,	// Netherlands guilder
	Currency_NOK   = 32,	// Norway krone
	Currency_NZD   = 33,	// New Zealand dollars
	Currency_PHP   = 34,	// Philippines pesos	
	Currency_PLZ   = 35,	// Poland zloty
	Currency_PTE   = 36,	// Portugal escudo
	Currency_RUR   = 37,	// Russia ruble
	Currency_SEK   = 38,	// Sweden kronor
	Currency_SGD   = 39,	// Singapore dollars
	Currency_THB   = 40,	// Thailand baht
	Currency_TWD   = 41,	// Taiwan new dollars
	Currency_VEB   = 42,	// Venezuela bolivar
	Currency_ZAR   = 43		// South Africa rands
} CurrencyIdEnum;


// For regions
typedef enum 
{
	Region_None		= 0,
	Region_LA
}   RegionCodes;

typedef enum
{
	ItemRelisted				=	0x00000001,
	ItemCreditInsertion			=	0x00000002,
	ItemCreditFeatured			=	0x00000004,
	ItemCreditCategoryFeatured	=	0x00000008,
	ItemCreditBold				=	0x00000010,
	ItemCreditFVF				=	0x00000020,
	ItemCreditNoSale			=	0x00000040,
	ItemRelisting				=	0x00000080,
	ItemUpdated					=	0x00000100,
// Item's T's and C's 
	PaymentMOCashiers			=   0x00000200,
	PaymentPersonalCheck		=	0x00000400,
	PaymentVisaMaster			=   0x00000800,
	PaymentAmEx					=   0x00001000,
	PaymentDiscover				=   0x00002000,
	PaymentOther				=	0x00004000,
	PaymentEscrow				=	0x00008000,
	PaymentCOD					=   0x00010000,
	PaymentSeeDescription		=   0x00020000,
	SellerPaysShipping			=	0x00040000,
	BuyerPaysShippingFixed		=	0x00080000,
	BuyerPaysShippingActual		=	0x00100000,
	ShippingSeeDescription		=	0x00200000,
	ShippingToCanada			=   0x00400000,
	ShippingInternationally		=	0x00800000,
	ItemCreditGallery			=   0x01000000,
	ItemCreditFeaturedGallery	=	0x02000000,
	ItemCreditGiftIcon			=	0x04000000
} ItemFeatureTypeEnum;

// this enum is defined for various login pages.
typedef enum
{
	eLoginGetEmail,
	eLoginPersonalShopper
} LoginTypeEnum;



// limit on the length of file path
//
#ifndef _MSC_VER
#define _MAX_PATH 256
#endif

// Define "assert" just for the benefit of Lint.
#ifdef assert
#undef assert
#endif
#ifndef _lint
#define assert(x)
#else
#include <assert.h>
#endif

// Fixed-length types
typedef long	int32_t;
typedef short	int16_t;
typedef char	int8_t;

typedef unsigned int	FilterId;
typedef unsigned int	MessageId;

//
// message type
//
typedef enum
{
	MessageTypeUnknown						= 0x00,
	MessageTypeCategorySellerWhenListing	= 0x01,
	MessageTypeCategoryBidderWhenBidding	= 0x02,
	MessageTypeItemBlockedWhenListing		= 0x04,
	MessageTypeItemFlaggedWhenListing		= 0x08,
	MessageTypeFilteringEmailText			= 0x10,
	MessageTypeBuddyEmailText				= 0x20,
};

typedef unsigned int MessageType;

//
// action type
//
typedef enum
{
	ActionTypeDoNothing						= 0x00,
	ActionTypeBlockListing					= 0x01,
	ActionTypeFlagListing					= 0x02,
	ActionTypeWarnUser						= 0x04,
	ActionTypeReinstateListing				= 0x08
};

typedef unsigned int ActionType;

//
// notify type
//
typedef enum
{
	NotifyTypeNone							= 0x00,
	NotifyTypeFilteringEmailAddresses		= 0x01,
	NotifyTypeBuddyEmailAddresses			= 0x02
};

typedef unsigned int NotifyType;

//
// screen item type
//
typedef enum
{
	ScreenItemOnListing			= 0,
	ScreenItemOnUpdateInfo		= 1,
	ScreenItemOnAddToDesc		= 2,
	ScreenItemOnChangeCategory	= 3
} ScreenItemType;

// these should probably stay in sync with the country codes where possible
// NOTE: regions should migrate here.
enum SiteTypeEnum
{
	SITE_EBAY_MAIN				= 0,
	SITE_EBAY_US				= 1,	// if there is ever a US only site
	SITE_EBAY_CA				= 2,	// Canada
	SITE_EBAY_UK				= 3,	// United Kingdom
	SITE_EBAY_DE				= 77,	// Germany
	SITE_EBAY_AU				= 15,	// Australia
	SITE_EBAY_JP				= 104	// Japan
};

enum PartnerEnum
{
	PARTNER_NONE				= 0,
	PARTNER_EBAY				= 1,
	PARTNER_EBAY_QA				= 121,
	PARTNER_AOL					= 500	// AOL cobrand partner ID
};

enum HeaderType
{
	HeaderHomePage				= 0,
	HeaderBrowse				= 1,
	HeaderSell					= 2,
	HeaderServices				= 3,
	HeaderSearch				= 4,
	HeaderHelp					= 5,
	HeaderCommunity				= 6
};

enum HeaderSubType
{
	HeaderSubCategory			= 1,
	HeaderSubFeatured			= 2,
	HeaderSubHot				= 3,
	HeaderSubGrabbag			= 4,
	HeaderSubGreatGift			= 5,
	HeaderSubBigTicket			= 6,
	HeaderSubAllItem			= 7,
	HeaderSubGallery			= 8,
	HeaderSubItemView			= 9
};


#define EBAYTYPES_INCLUDED 1
#endif /* EBAYTYPES_INCLUDED */
