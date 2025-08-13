/*	$Id: clsBuildAdvancedSearchPageApp.cpp,v 1.2.390.1 1999/08/01 02:51:07 barry Exp $	*/
//
//	File:		clsBuildAdvancedSearchPageApp.cpp
//
// Class:	clsBuildAdvancedSearchPageApp
//
//	Author:	Wen Wen
//
//	Function:
//			Rebuild list function
//
// Modifications:
//				- 07/07/97	Wen - Created
//				- 07/19/99	nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsBuildAdvancedSearchPageApp.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsItem.h"


// default template files
static const char* pDefTopCategoryTemplate[] = 
{
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate",
	"templates/DefTopCategoryTemplate"
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
	"templates/DefItemTemplate"
};

static const char pDefShallowItemTemplate[] = "templates/DefShallowItemTemplate";

static const char pDefShallowCategoryTemplate[] = "templates/DefShallowCategoryTemplate";

clsBuildAdvancedSearchPageApp::clsBuildAdvancedSearchPageApp()
{
	mpDatabase			= (clsDatabase *)0;
	mpMarketPlaces		= (clsMarketPlaces *)0;
	mpCurrMarketPlace	= (clsMarketPlace *)0;
	mpCategories		= (clsCategories *)0;
	mpItems				= (clsItems *) 0;

	// Get the current system time
	time(&mTime);

	// open a log file
	mLogStream.open("BuildAdvancedSearchPage.log", ios::out);

	// db time
	mDBTime = 0;

	return;
}


clsBuildAdvancedSearchPageApp::~clsBuildAdvancedSearchPageApp()
{
	if (mpDatabase)
		delete mpDatabase;

	if (mpMarketPlaces)
		delete mpMarketPlaces;

	if (mpCurrMarketPlace)
		delete mpCurrMarketPlace;

	if (mpCategories)
		delete mpCategories;

	if (mpItems)
		delete mpItems;

	// close the log file
	mLogStream.close();

	return;
}

//
// Where are all actions
//
bool clsBuildAdvancedSearchPageApp::Run(char* TestingPath)
{
	// Initialize
	Initialize(TestingPath);

	// build the page
	BuildSearchPage();

	return true;
}

//
//	Initialization
//
void clsBuildAdvancedSearchPageApp::Initialize(char* TestingPath)
{
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();
	mpCurrMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpCategories = mpCurrMarketPlace->GetCategories();
	mpItems = mpCurrMarketPlace->GetItems();

//	mpFileName = new clsFileName(mpCurrMarketPlace, TestingPath);
//	mpFileName->CreateBasePaths();

//	CreateShallowDirs();
}

//
// Get Categories
//
clsCategories*	clsBuildAdvancedSearchPageApp::GetCategories()
{
	return mpCategories;
}

//
//
//
clsMarketPlace* clsBuildAdvancedSearchPageApp::GetMarketPlace()
{
	return mpCurrMarketPlace;
}

//
// Get Items
//
clsItems* clsBuildAdvancedSearchPageApp::GetItems()
{
	return mpItems;
}


//
//	Build category pages
//
bool clsBuildAdvancedSearchPageApp::BuildSearchPage()
{
	CategoryVector::iterator	iCategory;
	CategoryVector	Categories;
	time_t			StartTime;
	time_t			EndTime;
	ofstream		OutputStream;

	// Retrieve all categories from database
	StartTime = time(0);
	mpCategories->All(&Categories);

	// open the file
	OutputStream.open("advancedpage.html", ios::out);

	mpDefHeader = GetMarketPlace()->GetHeader();
	mpDefFooter = GetMarketPlace()->GetFooter();
	// nsacco 07/19/99
	mpMarketPlaceName = GetMarketPlace()->GetCurrentPartnerName();

	// get the header
	OutputStream << mpDefHeader;

	OutputStream << "<br><br>";

	OutputStream << "<form ACTION=\"/scripts/srchadm/ebaySrch/category.idq\" method=\"GET\">";

	EmitStandardSearchToPage(&OutputStream);

	OutputStream << "<br><br>";

	EmitCurrentTimeToPage(&OutputStream);
	
/*
	EmitHTMLLeafSelectionSearchList(&OutputStream,
											"search",
											0,
											"0",
											"Not Selected",
											&Categories);
*/

	EmitPriceSearchToPage(&OutputStream);
	
	OutputStream << "<br><br>";
	
	EmitDateSearchToPage(&OutputStream);
	
	OutputStream << "<br><br>";
	
	EmitHTMLLeafSelectionSearchList(&OutputStream,
											"WhichCategory",
											0,
											"0",
											"Not Selected",
											&Categories);

		OutputStream << "</form>";

		OutputStream << "<br><br>";


		OutputStream << mpDefFooter;

		// close the file
		OutputStream.close();
		
		return true;
}


//
// emit the standard search block
//
void clsBuildAdvancedSearchPageApp::EmitStandardSearchToPage(ostream *pStream)
{

 	*pStream	<<	"<TABLE>\n"
				<<	"<TR>\n"
				<<	"<TD> <INPUT TYPE=\"TEXT\" NAME=\"TextRestriction\" SIZE=\"30\" MAXLENGTH=\"100\" VALUE=\"\"></TD>\n"
				<<	"</TR>\n"
				<<  "</TABLE>\n"

				<<	"<TABLE>\n"
				<<	"<TR>\n"
				<<	"<TD><SELECT NAME=\"SortProperty\">\n"
                <<	"<OPTION SELECTED VALUE=\"MetaEndSort\">Ending Date\n"
                <<	"<OPTION VALUE=\"MetaSeller\">Seller\n"
                <<	"<OPTION VALUE=\"MetaCurrentPriceSort\">Bid Price\n"
                <<	"<OPTION VALUE=\"rank\">Search ranking\n"
                <<	"<OPTION VALUE=\"None\">None\n"
				<<	"</SELECT></TD>\n"
				<<	"<TD><INPUT TYPE=\"SUBMIT\" VALUE=\"Go!\"></TD>\n"
				<<	"<TD ALIGN=right>\n"
            	<<	"<A HREF=\"http://206.79.255.83/scripts/srchadm/ebaySrch/tips.htm\">Tips</A>\n"
				<<	"</TD>\n"
				<<	"</TR>\n"
				<<	"</TABLE>\n"

				<<	"<TABLE>\n"
				<<	"<TR>\n"
				<<	"<TD><INPUT TYPE=\"Radio\" NAME=\"whichIndex\" VALUE=\"current\" CHECKED>Current</TD>\n"
				<<	"<TD><INPUT TYPE=\"Radio\" NAME=\"whichIndex\" VALUE=\"completed\">Completed</TD>\n"
				<<	"</TR>\n"
				<<	"</TABLE>\n"

				<<	"<INPUT TYPE=\"HIDDEN\" NAME=\"HTMLQueryForm\" VALUE=\"/srchadm/ebaySrch/category.htm\">\n"
				<<	"<INPUT TYPE=\"hidden\" NAME=\"SortOrder\" VALUE=\"[d]\">\n"
 				<<	"<INPUT TYPE=\"HIDDEN\" NAME=\"maxRecordsPerPage\" VALUE=\"75\">\n"
				<<	"<INPUT TYPE=\"hidden\" NAME=\"maxRecordsReturned\" VALUE=\"2500\">\n";
}





void clsBuildAdvancedSearchPageApp::EmitCurrentTimeToPage(ostream *pStream)
{
	*pStream	<<	"<INPUT TYPE=\"HIDDEN\" NAME=\"THETIME\" VALUE=\""
				<<	mTime
				<<	"\"> \n";
}

void clsBuildAdvancedSearchPageApp::EmitDateSearchToPage(ostream *pStream)
{
    *pStream << "<h3>Date</h3>\n";

    *pStream << "Auctions \n";

	*pStream	<<	"<select name=\"WhichDateSort\" size=\"1\">\n"
				<<	"<option selected value=\"new\">new in the last</option>\n"
				<<	"<option value=\"ending\">ending in the next</option>\n"
				<<	"</select>\n";

    *pStream <<	"<select name=\"WhichDateSortPeriod\"  size=\"1\">\n";
    *pStream <<	"<option value=\"3600\">1 hour</option>\n";
    *pStream <<	"<option value=\"10800\">3 hours</option>\n";
    *pStream <<	"<option value=\"21600\">6 hours</option>\n";
    *pStream <<	"<option value=\"43200\">12 hours</option>\n";
    *pStream <<	"<option selected value=\"86400\">24 hours</option>\n";
    *pStream <<	"<option value=\"172800\">48 hours</option>\n";
    *pStream <<	"<option value=\"604800\">1 week</option>\n";
    *pStream <<	"<option value=\"1209600\">2 weeks</option>\n";
    *pStream <<	"<option value=\"2419200\">1 month</option>\n";
    *pStream <<	"</select>\n";
}



void clsBuildAdvancedSearchPageApp::EmitPriceSearchToPage(ostream *pStream)
{
     *pStream << "<h3>Price</h3>\n";

	 *pStream << "Auctions: Greater than \n";

    *pStream <<	"<input type=\"text\" size=\"8\" name=\"WhichPriceSortLow\" value=\"0\">\n";
    *pStream << " but less than \n";
    *pStream <<	"<input type=\"text\" size=\"8\" name=\"WhichPriceSortHigh\" value=\"0\">\n";
}

//
//
//
void clsBuildAdvancedSearchPageApp::EmitHTMLLeafSelectionSearchList(ostream *pStream,
											  char *pListName,
											  CategoryId selectedValue,
											  char *pUnSelectedValue,
											  char *pUnSelectedLabel,
											  CategoryVector *vCategories)
{
	CategoryVector::iterator	i;

	bool						foundit	= false;

    *pStream << "<h3>Category</h3>\n";

	// Let's get them if its empty vector
	if (vCategories->size() < 1)
		mpCategories->AllSorted(vCategories);

	// Emit the first part
	*pStream	<<	"<SELECT NAME=\""
				<<	pListName
				<<	"\"> ";

	// Now, emit the items
	for (i = vCategories->begin();
		 i != vCategories->end();
		 i++)
	{

		if ((*i)->GetIsExpired() == false)
		{
			*pStream <<	"<OPTION ";
		
			if (selectedValue == (*i)->GetId())
			{
				*pStream <<	"SELECTED ";
				foundit	= true;
			}
		
		
			*pStream <<	" VALUE=\""
				 <<	(int)(*i)->GetId()
//				 << "@MetaCategory1="
//				 <<	(int)(*i)->GetId()
//				 << " OR @MetaCategory2="
//				 <<	(int)(*i)->GetId()
//				 << " OR @MetaCategory3="
//				 <<	(int)(*i)->GetId()
//				 << " OR @MetaCategory4="
//				 <<	(int)(*i)->GetId()
				 <<	"\">";

			// Emit the "label", which is a concatenation
			// of the various level names.

			// Let's get the qualified name
			mpCategories->EmitHTMLQualifiedName(pStream, (*i));

			*pStream << "</OPTION>\n";
		}
	};

	// If we didn't find the "Selected" value, then the
	// state is "unselected", and if the user provided
	// such a value/label, we'll use it
	if (!foundit &&
		pUnSelectedValue != NULL &&
		pUnSelectedLabel != NULL)
	{
		*pStream <<	"<OPTION SELECTED "
					"VALUE = "
					"\""
				 <<	pUnSelectedValue
				 <<	"\""
					">"
				 <<	pUnSelectedLabel
				 <<	"</OPTION>\n";
	}

	*pStream <<	"</SELECT>";

	return;
}


bool clsBuildAdvancedSearchPageApp::EndDescend(clsItem* pItem1, clsItem* pItem2)
{
	return pItem1->GetEndTime() < pItem2->GetEndTime();
}

//
// return the template file name for a category
//
const char* clsBuildAdvancedSearchPageApp::GetDefaultCategoryTemplate(TimeCriterion TimeStamp)
{
	return pDefCategoryTemplate[TimeStamp];
}

//
// return the template file name for a leaf category
//
const char* clsBuildAdvancedSearchPageApp::GetDefaultItemTemplate(TimeCriterion TimeStamp)
{
	return pDefItemTemplate[TimeStamp];
}

//
// return the rebuild starting time
//
time_t clsBuildAdvancedSearchPageApp::GetCreatingTime()
{
	return mTime;
}

//
// return the pointer to the clsFileName object
//
clsFileName* clsBuildAdvancedSearchPageApp::GetFileName()
{
	return mpFileName;
}

//
// remove the content of a CategoryVector
//
void clsBuildAdvancedSearchPageApp::CleanUpVector(CategoryVector* pCategories)
{
	CategoryVector::iterator	iCategory;

	// clean up categoires
	for (iCategory = pCategories->begin(); iCategory < pCategories->end(); iCategory++)
	{
		delete *iCategory;
	}

	pCategories->erase(pCategories->begin(), pCategories->end());
}

//
// remove the content of a ItemVector
//
void clsBuildAdvancedSearchPageApp::CleanUpVector(ItemVector* pItems)
{
	ItemVector::iterator	iItem;

	// clean up categoires
	for (iItem = pItems->begin(); iItem < pItems->end(); iItem++)
	{
		delete *iItem;
	}

	pItems->erase(pItems->begin(), pItems->end());
}

//
// log a piece of message to the log file
//
void clsBuildAdvancedSearchPageApp::LogMessage(const char* pMessage)
{
    time_t          CurrentTime = time(0);
    struct tm*      LocalTime = localtime(&CurrentTime);
    char            TimeString[256];

    sprintf(TimeString, "%2d/%2d/%2d %2d:%2d:%2d\t", 
		LocalTime->tm_mon+1, LocalTime->tm_mday, LocalTime->tm_year,
		LocalTime->tm_hour, LocalTime->tm_min, LocalTime->tm_sec);

	// write the message to the file
	mLogStream << TimeString
			   << pMessage
			   << "\n";

	mLogStream.flush();
}

void clsBuildAdvancedSearchPageApp::IncrementDBTime(double Delta)
{
	mDBTime += Delta;
}

double clsBuildAdvancedSearchPageApp::GetDBTime()
{
	return mDBTime;
}

