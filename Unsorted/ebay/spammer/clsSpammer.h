/*	$Id: clsSpammer.h,v 1.2.692.1 1999/08/09 18:45:07 nsacco Exp $	*/
//
//	File:	clsSpammer.h
//
//	Class:	clsSpammer
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//			builds a text file of all or incremental auction items
//
// Modifications:
//				- 07/22/97	pete - Created
//
#ifndef clsSpammer_INCLUDED
#define	clsSpammer_INCLUDED

#include <fstream.h>
#include <stdio.h>
#include <string.h>
#include <time.h>

#include "clsApp.h"
#include "clsCategories.h"
#include "clsItems.h"
#include "clsUsers.h"

#include "clseBayHotYahooWidget.h"

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */

// temp directory
#ifdef WIN32
const char * const TEMP_PATH = "d:\\temp\\%s";
#else
const char * const TEMP_PATH = "/tmp/%s";
#endif

// output file for inews files
const char * const TEST_FILE = "testNewsFile";

// templates: spammer or yahoo
#ifdef WIN32
const char * const 	SPAMMER_TEMPLATE = "c:\\ebay\\spammer\\template\\spammer_base";
const char * const 	YAHOO_TEMPLATE = "c:\\ebay\\spammer\\template\\yahoo_base";
#else
const char * const 	SPAMMER_TEMPLATE = "template/spammer_base";
const char * const 	YAHOO_TEMPLATE = "template/yahoo_base";
#endif

// TODO - replace?
const char * const LISTINGS_URL = "http://cayman.ebay.com/aw/listings/list/";

// special cases for tags
const char * const HOT_ITEMS = "ebay_hotitems";
const char * const RECENT_ITEMS = "ebay_recentitems";
const char * const NEWSGROUP = "newsgroup";
const char * const AUCTION_NAME = "auction";
const char * const CATEGORY_URL = "ebay_category_link";
const char * const CATEGORY_COUNT = "ebay_num_items";
const char * const DATE = "date";
const char * const BASE_URL = "base_url";
const char * const YAHOO_HEADLINES = "yahoo_headlines";

// mailing address & subject - guide-ebayauctions@feed.yahoo.com, pete@ebay.com
const char * const MAIL_TO_ADDRESS = "pete@ebay.com";
const char * const MAIL_SUBJECT = "";


// default item counts
const int ITEM_COUNT = 50;
const int HOT_COUNT = 30;

// Class forward
class clsDatabase;
class clsMarketPlaces;
class clsMarketPlace;
class clsCategories;
class clsCategory;
class clsItems;
class clsItem;
class clsUsers;

typedef struct
{
  char	newsgroup[500];       // buffer to hold all newsgroups
  short	categories[50];
  short	hotItems;
  short	recentItems;
  short	individualItems[50];
  short	id;
} CategoryToNewsTransformIn;

// 
// This Enum tells us the list of item time
//

class clsFile 
{
	public:

		clsFile(char *name);
		~clsFile();

		void WriteIt(char *tempStr);
		char *TransformInput(char *pString, bool transformHTML);
		void Print(clsItem *pItem);
		void WriteHeader(char *title, char *newsgroup);
		void WritePreamble(char *title);

	private:
  
		ofstream OutputStream;
};

class clsSpammer : public clsApp
{
	public:
		void CreateYahooHeadlines(ostrstream * theStream);
		void GetDate(ostrstream * theStream);
		char baseTemplate[255];
		bool yahoo;
		void TimeStamp(ostrstream *theStream);
		void SendNews(ostrstream *theStream);
		bool yahooDoIt();
		
		// Constructor, Destructor
		clsSpammer();
		~clsSpammer();

		// Action
		void Run();

		// Setup
		void Initialize();

		// which routine to call
		bool				getHot;
		bool				getNew;
		bool				getCompleted;
		bool				verbose;
	
		clsSpammer	*mpApp;
		CategoryToNewsTransformIn catStringIn[500];
 
	private:
		void ExtractActiveItems();
		void ExtractNewlyModifiedItems();
		void ExtractCompletedItems();
		bool GetStateFile();
		void PutStateFile();
		void WalkThroughTransforms();
		void writeCategories();
		bool readCategoriesTemplate();
		clsCategory *GetTheCategory(CategoryId id);
		void ReturnTitle(clsCategory* pCategory, char *buffer);
		bool readNewsControlFile();
		bool GetTheItems(short categoryID);
		void PadString(char *str, short length);
		void PadNumber(char *str, short length);
//		void ItemToStream(ostrstream *theStream, clsItem *pItem);
		void ItemToStream(ostrstream *theStream, clsListingItem *pItem);
		void WriteToStream(ostrstream *theStream, char *buffer, size_t size);
		void SendMail(ostrstream *theStream);
		void SendUnixMail(ostrstream *theStream);

		// template
		bool FirstPass(ostrstream *theStream);

		clsDatabase*		mpDatabase;
		clsMarketPlaces*	mpMarketPlaces;
		clsMarketPlace*		mpCurrMarketPlace;
		clsCategories*		mpCategories;
		clsItems*			mpItems;
		clsUsers*			mpUsers;

		ifstream			catStreamIn;


		time_t				mTime;
		time_t				lastRunTime;
		
		ifstream			StateStreamIn;
		ofstream			StateStreamOut;

		CategoryToNewsTransformIn catTransform[250];
		CategoryToNewsTransformIn *catTransformPtr;
		short                     numOfTransforms;

		ListingItemVector  recentItems, hotItems;
		// ItemVector  recentItems, hotItems;
		char		mCategoryTitle[256];

		short     totalCategoryCount;
	
		clseBayHotYahooWidget *yahooHotWidget;
};

#endif // clsSpammer_INCLUDED









