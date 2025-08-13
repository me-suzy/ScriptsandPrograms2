/*	$Id: clsRebuildListApp.cpp,v 1.5.390.1 1999/08/06 02:26:57 nsacco Exp $	*/
//
//	File:		clsRebuildListApp.cpp
//
// Class:	clsRebuildListApp
//
//	Author:	Wen Wen
//
//	Function:
//			Rebuild list function
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsRebuildListApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsListingItem.h"
#include "clsFileName.h"
#include "clsSubHTMLPage.h"
#include "clsTopHTMLPage.h"
#include "clsCategoryOverview.h"
#include "clsTemplate.h"

#include <stdio.h>

#ifdef _MSC_VER
#define strcasecmp(x, y) stricmp(x, y)
#endif

// default template files
static const char* pDefTopCategoryTemplate[] = 
{
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate.going"
};

static const char* pDefCategoryTemplate[] = 
{
	"templates/DefCategoryTemplate",
	"templates/DefCategoryTemplate",
	"templates/DefCategoryTemplate",
	"templates/DefCategoryTemplate"
};

static const char* pDefItemTemplate[] = 
{
	"templates/DefItemTemplate",
	"templates/DefItemTemplate",
	"templates/DefItemTemplate",
	"templates/DefItemTemplate.completed",
	"templates/DefItemTemplate"
};

static const char* pDefExt[] =
{
	"current",
	"newtoday",
	"endtoday",
	"completed",
	"going"
};


clsRebuildListApp::clsRebuildListApp()
{
	mpDatabase			= (clsDatabase *)0;
	mpMarketPlaces		= (clsMarketPlaces *)0;
	mpCurrMarketPlace	= (clsMarketPlace *)0;
	mpCategories		= (clsCategories *)0;
	mpItems				= (clsItems *) 0;

	// Get the current system time
	time(&mTime);

	// open a log file
	mLogStream.open("RebuildList.log", ios::out | ios::app);

	// db time
	mDBTime = 0;

	// templates
	mTemplateCount = 0;

	// template file name
	mpTemplateFileName = NULL;

	// days
	mBuildDay		= 0;
	mPrevDay		= 0;
	mNextDay		= 0;
	mpTodayString		= NULL;
	mpPreviousDayString	= NULL;
	mpNextDayString		= NULL;

	return;
}


clsRebuildListApp::~clsRebuildListApp()
{
	int i;

	delete mpFileName;

	// close the log file
	mLogStream.close();

	// delete template
	for (i = 0; i < mTemplateCount; i++)
	{
		delete [] mpTemplateNames[i];
		delete mpTemplates[i];
	}

	// delete template file name
	delete [] mpTemplateFileName;

	delete [] mpTodayString;
	delete [] mpPreviousDayString;
	delete [] mpNextDayString;

	return;
}

//
// Where are all actions
//
bool clsRebuildListApp::Run(char* TestingPath, bool BuildingCompleted/*=false*/)
{

	// Initialize
	Initialize(TestingPath, BuildingCompleted);

        time_t          CurrentTime = time(0);
        struct tm*      LocalTime = localtime(&CurrentTime);
        char            TimeString[256];

        memset(TimeString, 0, 256);

        sprintf(TimeString, "%2d/%2d/%2d %2d:%2d:%2d\t",
                LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
                LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	if (BuildingCompleted)
	{
		// set build completed
		mTimeToBuildCompleted = true;

		LogMessage("start BuildCompletedPages");
		mpItems->PrepareCompletedListingItems(mTime);
		LogMessage("Got completed data");

		// build completed
		BuildCompletedPages();
		LogMessage("end BuildCompletedPages");
	}
	else
	{
		// Prepare listings items
		mpItems->PrepareActiveListingItems(mTime);

		// Build the category overview page
		if (!BuildCategoryOverview())
			return false;

		// Build the top page
		LogMessage("start BuildTopCategory");
		if (!BuildTopPages())
			return false;
		LogMessage("end BuildTopCategory");

		// Build the pages
		LogMessage("start BuildHTMLPages");
		if (!BuildHTMLPages())
			return false;
		LogMessage("end BuildHTMLPages");
	}

	mpItems->RemoveListingItems();

	return true;
}

//
//	Initialization
//
void clsRebuildListApp::Initialize(char* TestingPath, bool BuildingCompleted /*=false*/)
{
        struct tm*      pTheDay;
	time_t		NextDay;
	time_t		PreviousDay;
	time_t		TempDay;
	time_t		Today = time(0);

	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	mpCurrMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();

	mpCategories = mpCurrMarketPlace->GetCategories();
	mpItems = mpCurrMarketPlace->GetItems();

	mpFileName = new clsFileName(mpCurrMarketPlace, TestingPath);
	mpFileName->CreateBasePaths();

//	if ( (Today - mTime) < ONE_DAY)
//	{
		TempDay = mTime - ONE_DAY;
//	}
//	else
//	{
//		TempDay = mTime;
//	}

	pTheDay = localtime(&TempDay);
	mBuildDay = pTheDay->tm_mday;
	if (BuildingCompleted)
	{
		// Get the day of the month
		mpTodayString = new char[15];
		strftime(mpTodayString, 15, "%b. %d, %Y", pTheDay);

		// Get the next day string
		if ((Today - mTime) > ONE_DAY)
		{
			NextDay = TempDay + ONE_DAY;
			pTheDay = localtime(&NextDay);
			mNextDay = pTheDay->tm_mday;
			mpNextDayString = new char [15];
			strftime(mpNextDayString, 15, "%b. %d, %Y", pTheDay);
		}

		// Get the previous day string
		PreviousDay = TempDay - ONE_DAY;
		pTheDay = localtime(&PreviousDay);
		mPrevDay = pTheDay->tm_mday;
		mpPreviousDayString = new char [15];
		strftime(mpPreviousDayString, 15, "%b. %d, %Y", pTheDay);
	}
}


//
// Get Categories
//
clsCategories*	clsRebuildListApp::GetCategories()
{
	return mpCategories;
}

//
//
//
clsMarketPlace* clsRebuildListApp::GetMarketPlace()
{
	return mpCurrMarketPlace;
}

//
// Get Items
//
clsItems* clsRebuildListApp::GetItems()
{
	return mpItems;
}

//
// Create the category overview page
//
bool clsRebuildListApp::BuildCategoryOverview()
{
	clsCategoryOverview OverviewPage;

	return OverviewPage.CreatePage();
}

//
//	Build top category pages
//
bool clsRebuildListApp::BuildTopPages()
{
	int				TimeStamp;
	CategoryVector	Categories;
	ListingItemVector		FeaturedItems;
	ListingItemVector		HotItems;
	ListingItemVector*		pFeaturedItems;
	ListingItemVector*		pHotItems;
	ListingItemVector		SubFeaturedItems;
	ListingItemVector		SubHotItems;
	clsTopHTMLPage*	pHTMLPage;
	bool		Ret = true;

	// Reserve space for vectors
	Categories.reserve(15);
	SubFeaturedItems.reserve(100);
	SubHotItems.reserve(50);

	// Get the top level categories
	mpCategories->TopLevel(&Categories);

	// Get super feature items and the hot items
	mpItems->GetSuperFeaturedListingItems(&FeaturedItems);
	mpItems->GetHotListingItems(&HotItems);

	// sort items
	if (FeaturedItems.size() > 0)
	{
		RemoveAdultItems(&FeaturedItems);
		sort(FeaturedItems.begin(), FeaturedItems.end(), SaleStartDescend);
	}

	if (HotItems.size() > 0)
	{
		RemoveAdultItems(&HotItems);
		sort(HotItems.begin(), HotItems.end(), SaleStartDescend);
	}

	for (TimeStamp = LISTING; TimeStamp <= END_TODAY; TimeStamp++)
	{
		switch (TimeStamp)
		{
		case LISTING:
			SubFeaturedItems = FeaturedItems;
			SubHotItems = HotItems;
			break;

		case NEW_TODAY:
			ExtractNewItems(&FeaturedItems, &SubFeaturedItems);
			ExtractNewItems(&HotItems, &SubHotItems);
			break;

		case END_TODAY:
			ExtractItemsEndingInHours(&FeaturedItems, &SubFeaturedItems, ONE_DAY);
			ExtractItemsEndingInHours(&HotItems, &SubHotItems, ONE_DAY);
			break;

 		}
		pFeaturedItems = &SubFeaturedItems;
		pHotItems = &SubHotItems;

		// Set the category to NULL
		pHTMLPage = new clsTopHTMLPage( &Categories, 
										pFeaturedItems, 
										pHotItems, 
										(TimeCriterion) TimeStamp, 
										mpFileName);
		if (pHTMLPage->Initialize() == false)
		{
			// Terminate
			delete pHTMLPage;
			Ret = false;
			break;
		}

		if (pHTMLPage->CreatePage() == false)
		{
			delete pHTMLPage;
			Ret = false;
			break;
		}

		delete pHTMLPage;
	}


	// Build top page for going
	BuildGoingTopPage(&Categories);

	CleanUpVector(&Categories);
	CleanUpVector(&FeaturedItems);
	CleanUpVector(&HotItems);

	return Ret;
}

//
//      Build going pages
//
bool clsRebuildListApp::BuildGoingTopPage(CategoryVector* pCategories)
{
        ListingItemVector*      pItems;
        clsSubHTMLPage*         pHTMLPage;
        bool                    Ret = true;
        CategoryVector          Categories;
        CategoryVector          OrderedCategories;

        // get items ending in 3 hours
        pItems = mpItems->GetAllGoingItemList();

        RemoveAdultItems(pItems);

        // sort the pitem by its ending time
        if (pItems->size() > 0)
                sort(pItems->begin(), pItems->end(), clsRebuildListApp::SaleEndAscend);

        // build the top page
        pHTMLPage = new clsSubHTMLPage(NULL, pCategories, pItems, GOING, mpFileName);
        if ((Ret = pHTMLPage->Initialize()) == true)
        {
                Ret = pHTMLPage->CreatePage();
        }
        delete pHTMLPage;

		return Ret;
}

//
//	Build pages
//
bool clsRebuildListApp::BuildCompletedPages()
{
	if (!BuildTopCompletedPages() || !BuildHTMLPages())
		return false;

	return true;
}

//
//	Build top completed category pages
//
bool clsRebuildListApp::BuildTopCompletedPages()
{
	CategoryVector	Categories;
	clsTopHTMLPage*	pHTMLPage;
	bool		Ret = true;

	LogMessage("start BuildTopCompletedPages");

	// Reserve space for vectors
	Categories.reserve(15);

	// Get the top level categories
	mpCategories->TopLevel(&Categories);

	// Set the category to NULL
	pHTMLPage = new clsTopHTMLPage( &Categories, 
									NULL, 
									NULL, 
									COMPLETED, 
									mpFileName);
	if ( (Ret = pHTMLPage->Initialize()) == true)
	{
		Ret =  pHTMLPage->CreatePage();
	}

	delete pHTMLPage;
	CleanUpVector(&Categories);

	LogMessage("end BuildTopCompletedPages");

	return Ret;
}

//
//	Build category pages
//
bool clsRebuildListApp::BuildHTMLPages()
{
	CategoryVector::iterator	iCategory;
	CategoryVector	Categories;
	bool		Ret = true;

	// reserve space
	Categories.reserve(350);

	// Retrieve all categories from database
	mpCategories->All(&Categories);

	// Create all the directories
	for (iCategory = Categories.begin(); iCategory != Categories.end(); iCategory++)
	{
		// Create dirs for completed items if neede
		if (mTimeToBuildCompleted)
		{
			mpFileName->CreateCompletedCategoryDirs((*iCategory)->GetId());
		}
		else
		{
			// Create paths for each cateogry
			mpFileName->CreateCategoryDirs((*iCategory)->GetId());
		}
	}

	// Create a HTML page for each category
	for (iCategory = Categories.begin();
		  iCategory != Categories.end();
		  iCategory++)
	{
		if (mTimeToBuildCompleted)
		{
			if ( (*iCategory)->catLevel() > 1)
			{
				// build completed item page
				Ret = BuildCompletedItemPages(*iCategory);
			}
		}
		else
		{
			Ret = BuildItemPages(*iCategory);
		}

		if (!Ret)
		{
			CleanUpVector(&Categories);
			return false;
		}

		if ((*iCategory)->catLevel() == 1)
		{
			// Build pages for completed only
			if (!BuildCategoryPages(*iCategory, mTimeToBuildCompleted ? COMPLETED : LISTING))
			{
				CleanUpVector(&Categories);
				return false;
			}
		}
	}

	CleanUpVector(&Categories);

	return true;
}


//
//	Build category pages
//
bool clsRebuildListApp::BuildCategoryPages(clsCategory* pCategory, int TimeStamp)
{
	CategoryVector		Children;
	clsSubHTMLPage*		pHTMLPage;
	bool			Ret = true;

	// reserve page for category
	Children.reserve(15);

	// get child categories
	mpCategories->Children(&Children, pCategory);

	pHTMLPage = new clsSubHTMLPage(pCategory, &Children, NULL, (TimeCriterion) TimeStamp, mpFileName);
	if ((Ret = pHTMLPage->Initialize()) == true)
	{
		Ret = pHTMLPage->CreatePage();
	}
	delete pHTMLPage;

	// clean up the children
	CleanUpVector(&Children);
	return Ret;
}

//
//	Build items pages
//
bool clsRebuildListApp::BuildItemPages(clsCategory* pCategory)
{
	ListingItemVector	Items;
	ListingItemVector*	pItems;
	ListingItemVector	SubItems;
	int			TimeStamp;
	clsSubHTMLPage*		pHTMLPage;
	CategoryVector		ChildCategories;
	bool			Ret;

	// reserve space for vectors
	ChildCategories.reserve(20);
	SubItems.reserve(4000);

	// Build pages for current listing, new-today, and end-today first
	//
	// get items and child categories in the category
	mpItems->GetListingItemsInCategory(pCategory->GetId(), &Items);
	mpCategories->Children(&ChildCategories, pCategory);

	if (pCategory->GetId() == 99) // Miscellaneous
	{
		RemoveAdultItems(&Items);
	}

	// sort items
	if (Items.size() > 0)
		sort(Items.begin(), Items.end(), SaleStartDescend);

	pItems = &Items;

	for (TimeStamp = LISTING; TimeStamp <= GOING; TimeStamp++)
	{
		if (TimeStamp == COMPLETED)
		{
			continue;
		}

		if (TimeStamp == LISTING && pCategory->catLevel() == 1)
		{
			// pages for level 1 category listings is done in BuildCategoryPages()
			continue;
		}

		if (Items.size())
		{
			switch (TimeStamp)
			{
			case LISTING:
				SubItems = Items;
				break;

			case NEW_TODAY:
				ExtractNewItems(&Items, &SubItems);
				break;

			case END_TODAY:
				ExtractItemsEndingInHours(&Items, &SubItems, ONE_DAY);
				break;

			case GOING:
				ExtractItemsEndingInHours(&Items, &SubItems, THREE_HOURS);
				break;
			}
		}
		pItems = &SubItems;

		pHTMLPage = new clsSubHTMLPage(pCategory, &ChildCategories, pItems, (TimeCriterion) TimeStamp, mpFileName);
		if ((Ret = pHTMLPage->Initialize()) == false)
		{
			// Terminate
			delete pHTMLPage;
			break;
		}

		if( (Ret = pHTMLPage->CreatePage()) == false)
		{
			delete pHTMLPage;
			break;
		}

		delete pHTMLPage;
	}

	// clean up the items
	CleanUpVector(&Items);
	CleanUpVector(&ChildCategories);

	return Ret;
}


bool clsRebuildListApp::BuildCompletedItemPages(clsCategory* pCategory)
{
	ListingItemVector	Items;
	clsSubHTMLPage*		pHTMLPage;
	CategoryVector		ChildCategories;
	bool			Ret;

	// reserve space for vectors
	ChildCategories.reserve(20);

	// Build pages for current listing, new-today, and end-today first
	//
	// get child categories in the category
	mpCategories->Children(&ChildCategories, pCategory);

	//  Build the page for the items completed
	if (pCategory->catLevel() > 1)
	{
		// retrieve items from database
		mpItems->GetListingItemsInCategory(pCategory->GetId(), &Items);

		// sort the items
		if (Items.size() > 0)
			sort(Items.begin(), Items.end(), SaleEndDescend);

		pHTMLPage = new clsSubHTMLPage(pCategory, &ChildCategories, &Items, COMPLETED, mpFileName);
		if ((Ret = pHTMLPage->Initialize()) == true)
		{
			Ret = pHTMLPage->CreatePage();
		}
		delete pHTMLPage;

		// clean up the items
		CleanUpVector(&Items);
	}
	CleanUpVector(&ChildCategories);

	return Ret;
}


void clsRebuildListApp::ExtractNewItems(ListingItemVector *pItems, ListingItemVector *pSubItems)
{
	ListingItemVector::iterator	iItem;

	// reset subitems
	pSubItems->erase(pSubItems->begin(), pSubItems->end());

	if (pItems->size() == 0)
	{
		return;
	}

	// push items listed less than 24 hours in subitems
	for (iItem = pItems->begin(); iItem != pItems->end(); iItem++)
	{
		if (difftime(mTime, (*iItem)->GetStartTime()) <= ONE_DAY)
		{
			pSubItems->push_back(*iItem);
		}
		else
		{
			// assume that the pitems has been sorted by starting time
			return;
		}
	}
	
}

void clsRebuildListApp::ExtractItemsEndingInHours(ListingItemVector *pItems, 
												  ListingItemVector *pSubItems,
												  int Hours)
{
	ListingItemVector::iterator	iItem;

	// reset subitems
	pSubItems->erase(pSubItems->begin(), pSubItems->end());

	if (pItems->size() == 0)
	{
		return;
	}

	// sort the pitem by its ending time
	sort(pItems->begin(), pItems->end(), clsRebuildListApp::SaleEndAscend);

	// push items ending within 3 hours in subitems
	for (iItem = pItems->begin(); iItem != pItems->end(); iItem++)
	{
		if (difftime((*iItem)->GetEndTime(), mTime) <= Hours)
		{
			pSubItems->push_back(*iItem);
		}
		else
		{
			//return;
		}
	}

}

void clsRebuildListApp::RemoveAdultItems(ListingItemVector* pItems)
{
	clsCategory*	pCategory;
	ListingItemVector::iterator	iItem;

	if (pItems == NULL || pItems->size() == 0)
	{
		return;
	}

	for (iItem = pItems->end()-1; iItem != pItems->begin(); iItem--)
	{
		pCategory = mpCategories->GetCategory((*iItem)->GetCategoryId());
		if (pCategory && pCategory->isAdult())
		{
			pItems->erase(iItem);
		}
		delete pCategory;
	}
}


bool clsRebuildListApp::SaleEndDescend(clsListingItem* pItem1, clsListingItem* pItem2)
{
	return pItem1->GetEndTime() > pItem2->GetEndTime();
}

bool clsRebuildListApp::SaleEndAscend(clsListingItem* pItem1, clsListingItem* pItem2)
{
	return pItem1->GetEndTime() < pItem2->GetEndTime();
}

bool clsRebuildListApp::SaleStartDescend(clsListingItem* pItem1, clsListingItem* pItem2)
{
	if (pItem1 != NULL &&
		 pItem2 != NULL)
	{
		return pItem1->GetStartTime() > pItem2->GetStartTime();
	}
	else
		return false;
}


//
// return the template file name for the top category
//
const char* clsRebuildListApp::GetDefTopCategoryTemplate(TimeCriterion TimeStamp)
{
	return pDefTopCategoryTemplate[TimeStamp];
}

//
// return the template file name for a category
//
const char* clsRebuildListApp::GetDefaultCategoryTemplate(TimeCriterion TimeStamp)
{
	return pDefCategoryTemplate[TimeStamp];
}

//
// return the template file name for a leaf category
//
const char* clsRebuildListApp::GetDefaultItemTemplate(TimeCriterion TimeStamp)
{
	return pDefItemTemplate[TimeStamp];
}

const char* clsRebuildListApp::GetCategoryTemplate(char* pFileReference, TimeCriterion TimeStamp)
{
	// if reference is null or default, use default template
	if (pFileReference == NULL || strcasecmp(pFileReference, "default") == 0)
	{
		return GetDefaultCategoryTemplate(TimeStamp);
	}

	// creat template name based on the reference name
	delete [] mpTemplateFileName;
	mpTemplateFileName = new char[strlen(pFileReference) + 35];
	sprintf(mpTemplateFileName, "templates/%s.category.%s", pFileReference, pDefExt[TimeStamp]);

	// check whether the file exist
	if (!FileExist( mpTemplateFileName ))
	{
		// if not, try with the file without the extension
		sprintf(mpTemplateFileName, "templates/%s.category", pFileReference);

		// check whether the file exist
		if (!FileExist( mpTemplateFileName ))
		{
			// if not, use default
			return GetDefaultCategoryTemplate(TimeStamp);
		}
	}

	return mpTemplateFileName;
}

const char* clsRebuildListApp::GetItemTemplate(char* pFileReference, TimeCriterion TimeStamp)
{
	// if reference is null or default, use default template
	if (pFileReference == NULL || strcasecmp(pFileReference, "default") == 0)
	{
		return GetDefaultItemTemplate(TimeStamp);
	}

	// creat template name based on the reference name
	delete [] mpTemplateFileName;
	mpTemplateFileName = new char[strlen(pFileReference) + 35];
	sprintf(mpTemplateFileName, "templates/%s.item.%s", pFileReference, pDefExt[TimeStamp]);

	// check whether the file exist
	if (!FileExist( mpTemplateFileName ))
	{
		// if not, try with the file without the extension
		sprintf(mpTemplateFileName, "templates/%s.item", pFileReference);

		// check whether the file exist
		if (!FileExist( mpTemplateFileName ))
		{
			// if not, use default
			return GetDefaultItemTemplate(TimeStamp);
		}
	}

	return mpTemplateFileName;
}

bool clsRebuildListApp::FileExist(char* pFileName)
{
	FILE*	FileHandle;

	if ((FileHandle = fopen( pFileName, "r" )))
	{
		fclose(FileHandle);
		return true;
	}

	return false;
}

//
// return the rebuild starting time
//
time_t clsRebuildListApp::GetCreatingTime()
{
	return mTime;
}

//
// return the pointer to the clsFileName object
//
clsFileName* clsRebuildListApp::GetFileName()
{
	return mpFileName;
}

//
// remove the content of a CategoryVector
//
void clsRebuildListApp::CleanUpVector(CategoryVector* pCategories)
{
	CategoryVector::iterator	iCategory;

	// clean up categoires
	for (iCategory = pCategories->begin(); iCategory != pCategories->end(); iCategory++)
	{
		delete *iCategory;
	}

	pCategories->erase(pCategories->begin(), pCategories->end());
}

//
// remove the content of a ListingItemVector
//
void clsRebuildListApp::CleanUpVector(ListingItemVector* pItems)
{
	// database deletes the items
	pItems->erase(pItems->begin(), pItems->end());
}

//
// log a piece of message to the log file
//
void clsRebuildListApp::LogMessage(const char* pMessage)
{
    time_t          CurrentTime = time(0);
    struct tm*      LocalTime = localtime(&CurrentTime);
    char            TimeString[256];

    memset(TimeString, 0, 256);

    sprintf(TimeString, "%2d/%2d/%2d %2d:%2d:%2d\t", 
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// write the message to the file
	mLogStream << TimeString
			   << pMessage
			   << "\n";

	mLogStream.flush();
}

clsTemplate* clsRebuildListApp::GetTemplate(const char* pTemplateFileName)
{
	// this is a temperately solution.
	//
	int		Index;

	for (Index = 0; Index < mTemplateCount; Index++)
	{
		if (strcmp(mpTemplateNames[Index], pTemplateFileName) == 0)
		{
			break;
		}	
	}

	if (Index == mTemplateCount)
	{
		// Create a new template
		mpTemplates[Index] = new clsTemplate(pTemplateFileName);
		mpTemplates[Index]->Parse();

		// Record new template file name
		mpTemplateNames[Index] = new char[_MAX_PATH];
		strcpy(mpTemplateNames[Index], pTemplateFileName);
		mTemplateCount++;
	}

	return mpTemplates[Index];
} 


