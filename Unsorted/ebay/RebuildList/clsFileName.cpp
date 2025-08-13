/*	$Id: clsFileName.cpp,v 1.3 1999/02/21 02:23:50 josh Exp $	*/
//
//	File:	clsFileName.cpp
//
//	Class:	clsFileName
//
//	Author:	Wen Wen
//
//	Function:
//			Create a file based on the file format, category id, 
//			and current HTML page
//
// Modifications:
//				- 07/07/97	Wen - Created
//

#include "clsRebuildListApp.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsFileName.h"

#ifdef _MSC_VER
#include <direct.h>
#endif

#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <sys/stat.h>

//
// constructor
//
clsFileName::clsFileName(clsMarketPlace* pMarketPlace, char* TestingPath)
	: clsListingFileName(pMarketPlace, TestingPath)
{
	mpFilePath				= new char[_MAX_PATH];
	mpAdultFilePath			= new char[_MAX_PATH];
	mpCatOverviewFileName	= new char[_MAX_PATH];
	GetFileFormats();

}

//
// destructor
//
clsFileName::~clsFileName()
{
	int TimeStamp;

	for (TimeStamp = LISTING; TimeStamp <= GOING; TimeStamp += 1)
	{
		delete [] mpFileFormats[TimeStamp];
	}

	delete [] mpFilePath;
	delete [] mpAdultFilePath;
	delete [] mpCatOverviewFileName;

}

//
// return the output file name for a category
//
char* clsFileName::GetName(clsCategory* pCategory, 
						   TimeCriterion TimeStamp /*=LISTING*/,
						   int PageNumber /*=1*/, int Day /*=0*/)
{ 
	return GetName(pCategory ? pCategory->GetId() : 0,
		TimeStamp, PageNumber, Day);
}

//
// return the output file name for a category
//
char* clsFileName::GetName(int CategoryId, 
						   TimeCriterion TimeStamp /*=LISTING*/,
						   int PageNumber /*=1*/, int Day /*=0*/)
{ 
	char PageName[20];
	char DirectoryName[_MAX_PATH];

	// directory name
	GetDirectoryName(CategoryId, DirectoryName);

	// page name
	GetPageName(PageNumber, PageName, Day, TimeStamp);

	sprintf(mpFilePath, mpFileFormats[TimeStamp], DirectoryName, PageName);

	return mpFilePath;
}

//
// return the output file name for the adult auction page
//
char* clsFileName::GetAdultFileName(clsCategory* pCategory,
									TimeCriterion TimeStamp /*=LISTING*/)
{
	return GetAdultFileName(pCategory ? pCategory->GetId() : 0, TimeStamp);
}

//
// return the output file name for the adult auction page
//
char* clsFileName::GetAdultFileName(int CategoryId,
									TimeCriterion TimeStamp /*=LISTING*/)
{
	char DirectoryName[_MAX_PATH];

	GetDirectoryName(CategoryId, DirectoryName);

	sprintf(mpAdultFilePath, mpFileFormats[TimeStamp], 
		DirectoryName, ADULT_PAGE);

	return mpAdultFilePath;
}

//
// Initialize the file formats
//
void clsFileName::GetFileFormats()
{
	int TimeStamp;

	for (TimeStamp = LISTING; TimeStamp <= GOING; TimeStamp++)
	{
		mpFileFormats[TimeStamp] = new char[_MAX_PATH];
	}

	sprintf(mpFileFormats[0], "%s/%s/%s", TEMP_LISTING_FILE_PATH, LIST_PATH,	"%s/%s");
	sprintf(mpFileFormats[1], "%s/%s/%s", TEMP_LISTING_FILE_PATH, NEWTODAY_PATH,  "%s/%s");
	sprintf(mpFileFormats[2], "%s/%s/%s", TEMP_LISTING_FILE_PATH, ENDTODAY_PATH,  "%s/%s"); 
	sprintf(mpFileFormats[3], "%s/%s/%s", TEMP_LISTING_FILE_PATH, COMPLETED_PATH, "%s/%s");
	sprintf(mpFileFormats[4], "%s/%s/%s", TEMP_LISTING_FILE_PATH, GOING_PATH,	  "%s/%s");
}

char* clsFileName::GetCatOverviewFileName()
{
	sprintf(mpCatOverviewFileName, "%s/%s", TEMP_LISTING_FILE_PATH, "overview.html");

	return mpCatOverviewFileName;
}


//
// Create based paths
//
void clsFileName::CreateBasePaths()
{
	char path[_MAX_PATH];

#ifndef _MSC_VER

	mode_t mode;

	mode = S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH;

	// make the basis path
	mkdir(TEMP_LISTING_FILE_PATH, mode);

	// make the paths for differen item status
	sprintf(path, "%s/%s", TEMP_LISTING_FILE_PATH, LIST_PATH);
	mkdir(path, mode);

	sprintf(path, "%s/%s", TEMP_LISTING_FILE_PATH, NEWTODAY_PATH);
	mkdir(path, mode);

	sprintf(path, "%s/%s", TEMP_LISTING_FILE_PATH, ENDTODAY_PATH);
	mkdir(path, mode);

	sprintf(path, "%s/%s", TEMP_LISTING_FILE_PATH, COMPLETED_PATH);
	mkdir(path, mode);

	sprintf(path, "%s/%s", TEMP_LISTING_FILE_PATH, GOING_PATH);
	mkdir(path, mode);

#endif
}

//
// Create Category directory
//
void clsFileName::CreateCategoryDirs(int CategoryId)
{
	char path[_MAX_PATH];
#ifndef _MSC_VER
	mode_t mode;

	mode = S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH;

	if (CategoryId > 0)
	{
		// make the paths for the category
		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, LIST_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);

		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, NEWTODAY_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);

		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, ENDTODAY_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);

		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, GOING_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);
	}
#endif
}

//
// Create Category directory for completed items
//
void clsFileName::CreateCompletedCategoryDirs(int CategoryId)
{
	char path[_MAX_PATH];
#ifndef _MSC_VER
	mode_t mode;

	mode = S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH;
	if (CategoryId > 0)
	{
		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, COMPLETED_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);
	}
#endif
}

//
// Create Category directory
//
void clsFileName::CreateGoingCategoryDirs(int CategoryId)
{
	char path[_MAX_PATH];
#ifndef _MSC_VER
	mode_t mode;

	mode = S_IRWXU | S_IRWXG | S_IROTH | S_IXOTH;

	if (CategoryId > 0)
	{
		// make the paths for the category
		sprintf(path, "%s/%s/%s%d", TEMP_LISTING_FILE_PATH, GOING_PATH, CATEGORY_PATH, CategoryId);
		mkdir(path, mode);
	}
#endif
}


