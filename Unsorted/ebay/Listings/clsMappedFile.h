/* $Id: clsMappedFile.h,v 1.2.516.1 1999/07/24 00:00:44 dnguyen Exp $ */
//
// File: clsMappedFile
//
// Author: Thorsten Lockert (tholo@ebay.com)
//
// Description: Wraps up 'memory mapping' of a file
// for Windows NT. Let's us use a binary file as
// if it were just a very large allocated block of memory.
//
#ifndef CLSMAPPEDFILE_INCLUDE
#define CLSMAPPEDFILE_INCLUDE

#ifndef _EBAY_H
#include "ebay.h"
#endif

#ifdef _MSC_VER
#include <windows.h>
#endif
#include <stdio.h>

class clsMappedFile {

private:
	LPSTR mlpFileName;
	bool mbWrite;
#if 0
	HANDLE mhFile;
	HANDLE mhMapFile;
#else
	FILE* mFile;
#endif
	LPVOID mpBase;

	void MapFile(LPCSTR lpFileName, bool bWrite);
	void UnMapFile();

public:
    Defaults(clsMappedFile);
    
	explicit clsMappedFile(LPCSTR lpFileName, bool bWrite = false) {
		mlpFileName = new char[strlen(lpFileName) + 1];
		strcpy(mlpFileName, lpFileName);
		mbWrite = bWrite;
		MapFile(mlpFileName, mbWrite);
	}

	~clsMappedFile() {
		UnMapFile();
		delete[] mlpFileName;
	}

	void RefreshMap(void (*ReplaceFile)(LPCSTR lpOldFile, LPCSTR lpNewFile), LPCSTR lpNewFile);

	LPVOID GetBaseAddress() {
		return mpBase;
	}
};

#endif /* CLSMAPPEDFILEINCLUDE */
