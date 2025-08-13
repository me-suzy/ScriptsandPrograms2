/* $Id: clsDirectoryWalker.h,v 1.2 1999/02/21 02:22:18 josh Exp $ */
//
// File: clsDirectoryWalker
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This is used for walking through the items in a directory.
//				The primary benefit over direct system calls is that
//				the destructor frees up system resources. Useful when
//				using exceptions or middle of routine returns.
//				Utilized in the Gallery project.
//

#ifndef _DIRECTORYWALKER_H
#define _DIRECTORYWALKER_H

#include <io.h>

class clsDirectoryWalker
{
public:
	clsDirectoryWalker(const char* directory, const char* pattern);
	~clsDirectoryWalker();

	bool GetNextItem();

	const char* GetName()
	{
		return mFindData.name;
	}

	time_t GetCreationTime()
	{
		return mFindData.time_create;
	}

	bool IsDirectory()
	{
		return mFindData.attrib & _A_SUBDIR ? true : false;
	}

	bool IsFileCurrentlyUnaccessed()
	{
		return (mFindData.attrib & _A_NORMAL) || (mFindData.attrib & _A_ARCH);
	}

	int GetLastError()
	{
		return mError;
	}

private:
	_finddata_t mFindData;
	long mSearchHandle;
	bool mDone;
	int mError;
	bool mFirstTime;

};

#endif
