/* $Id: clsDirectoryWalker.cpp,v 1.2 1999/02/21 02:22:17 josh Exp $ */
//
// File: clsDirectoryWalker
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: See clsDirectoryWalker.h
//

//#include "eBayKernel.h"
#include "clsDirectoryWalker.h"
#include <string.h>
#include <errno.h>

clsDirectoryWalker::clsDirectoryWalker(const char* directory, const char* pattern) :
	mDone(false),
	mError(0),
	mFirstTime(true)
{
	char searchPattern[1024];
	strcpy(searchPattern, directory);
	strcat(searchPattern, pattern);

	mSearchHandle = _findfirst(searchPattern, &mFindData);

	if (mSearchHandle == -1L)
	{
		mDone = true;
		mError = errno;
	}
}

clsDirectoryWalker::~clsDirectoryWalker()
{
	if (mSearchHandle != -1L)
		_findclose(mSearchHandle);
}

bool clsDirectoryWalker::GetNextItem()
{
	if (mError)
		return false;

	if (mFirstTime)
	{
		mFirstTime = false;
		return true;
	}

	if (_findnext(mSearchHandle, &mFindData))
	{
		mError = errno;

		return false;
	}

	return true;
}

