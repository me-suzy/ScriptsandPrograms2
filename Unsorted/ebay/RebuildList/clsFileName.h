/*	$Id: clsFileName.h,v 1.3 1999/02/21 02:23:51 josh Exp $	*/
//
//	File:	clsFileName.h
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
#ifndef CLSFILENAME_INCLUDED
#define CLSFILENAME_INCLUDED

#include "clsListingFileName.h"

class clsFileName : public clsListingFileName
{
public:
	// Constructor
	clsFileName(clsMarketPlace* pMarketPlace, char* TestingPath=NULL);

	// Destrutor
	~clsFileName();

	// Get the file and link formats
	void  GetFileFormats();
  
	// Get the name of the output file based on the current category and
	// printing page
	char* GetName(clsCategory* pCategory, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);
	char* GetName(int CategoryId, TimeCriterion TimeStamp = LISTING, int PageNumber = 1, int Day = 0);

	// File name for adult auction
	char* GetAdultFileName(clsCategory* pCategory, TimeCriterion TimeStamp = LISTING);
	char* GetAdultFileName(int CategoryId, TimeCriterion TimeStamp = LISTING);

	// Get category overview file name
	char* GetCatOverviewFileName();

	// Create paths
	void CreateBasePaths();
	void CreateCategoryDirs(int CategoryId);
	void CreateCompletedCategoryDirs(int CategoryId);
	void CreateGoingCategoryDirs(int CategoryId);

private:
	char*	mpFileFormats[5];
	char*	mpFilePath;
	char*	mpAdultFilePath;

	char*	mpCatOverviewFileName;
};

#endif // CLSFILENAME_INCLUDED
