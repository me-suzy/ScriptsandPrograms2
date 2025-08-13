/*	$Id: clsRegion.cpp,v 1.3 1999/04/28 05:35:22 josh Exp $	*/
//
//	File:	clsRegion.cpp
//
//	Class:	clsRegion
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents region
//
// Modifications:
//				- 04/14/99 wen	- Created

#include "eBayKernel.h"
#include "clsRegion.h"

// comparign zip
bool zip_comp(const char* pZip1, const char* pZip2)
{
	return (strcmp(pZip1, pZip2) < 0);
}

// constructor
clsRegion::clsRegion(int RegionID, const char* pName)
{
	mpName = NULL;
	mID = RegionID;
	SetName(pName);
	mSorted = false;
}

clsRegion::clsRegion()
{
	mID = 0;
	mpName = NULL;
	mSorted = false;
}


	// destructor
clsRegion::~clsRegion()
{
	if(mpName != NULL) 
	{	
		delete [] mpName;
		mpName = NULL;
	}

	for (int i = 0; i < mZips.size(); i++)
	{
		delete [] mZips[i];
	}
}

// 
void clsRegion::SetName(const char* pName)
{
	if(mpName != NULL) 
	{	
		delete [] mpName;
		mpName = NULL;
	}


	if (pName)
	{
		mpName = new char[strlen(pName) + 1];
		strcpy(mpName, pName);
	}
	else
	{
		mpName = NULL;
	}
}

// Add a zip code to the region. pZip is released by clsRegion
void clsRegion::AddZip(char* pZip)
{
	if (pZip == NULL || pZip[0] == '\0')
		return;

	char* pZipCopy = new char[strlen(pZip) + 1];
	strcpy(pZipCopy, pZip);

	mZips.push_back(pZipCopy);

	// reset sorted
	mSorted = false;
}


// testing whether pZip belongs to the region
bool clsRegion::IsInRegion(const char* pZip)
{
	// if it is empty or a null string, return false
	if (mZips.size() == 0 || pZip == NULL || pZip[0] == '\0')
		return false;

	// sort the vector if it is not sorted
	if (mSorted == false)
	{
		sort(mZips.begin(), mZips.end(), zip_comp);
		mSorted = true;
	}

	// test it
	return binary_search(mZips.begin(), mZips.end(), pZip, zip_comp);
}
