/* $Id: clsMappedFile.cpp,v 1.2.516.1 1999/07/24 00:01:07 dnguyen Exp $ */
//
// File: clsMappedFile
//
// Author: Thorsten Lockert (tholo@ebay.com)
//
// Description: Wraps up 'memory mapping' of a file
// for Windows NT. Let's us use a binary file as
// if it were just a very large allocated block of memory.
//
#include "clsMappedFile.h"
#include <sys\stat.h>
#include <io.h>

#if 0
void clsMappedFile::MapFile(LPCSTR lpFileName, bool bWrite)
{
	mhFile = CreateFile(lpFileName,
		GENERIC_READ | (bWrite ? GENERIC_WRITE : 0),
		FILE_SHARE_READ,
		NULL,
		OPEN_EXISTING,
		FILE_FLAG_RANDOM_ACCESS,
		NULL);
	
    if (mhFile == INVALID_HANDLE_VALUE)
		throw(GetLastError());
//	AfxThrowFileException(CFileException::generic, GetLastError(), lpFileName);

	mhMapFile = CreateFileMapping(mhFile,
		NULL,
		(bWrite ? PAGE_READWRITE : PAGE_READONLY) | SEC_COMMIT,
		0, 0,
		NULL);
	if (mhMapFile == NULL) {
		CloseHandle(mhFile);
		throw(GetLastError());
//		AfxThrowFileException(CFileException::generic, GetLastError(), lpFileName);
	}
	mpBase = MapViewOfFile(mhMapFile,
		(bWrite ? FILE_MAP_WRITE : FILE_MAP_READ),
		0, 0,
		0);
	if (mpBase == NULL) {
		CloseHandle(mhMapFile);
		CloseHandle(mhFile);
		throw(0);
//		AfxThrowMemoryException();
	}

}

void clsMappedFile::UnMapFile()
{
	UnmapViewOfFile(mpBase);
	CloseHandle(mhMapFile);
	CloseHandle(mhFile);
}

void clsMappedFile::RefreshMap(void (*ReplaceFile)(LPCSTR lpOldFile, LPCSTR lpNewFile), LPCSTR lpNewFile)
{
	UnMapFile();
	ReplaceFile(mlpFileName, lpNewFile);
	MapFile(mlpFileName, mbWrite);
}
#else
void clsMappedFile::MapFile(LPCSTR lpFileName, bool bWrite)
{
	mFile = fopen(lpFileName, "rb");
	if (!mFile)
		throw(GetLastError());

	struct stat statInfo;

	int statResult = stat(lpFileName, &statInfo);
	if (statResult)
		throw(GetLastError());

	mpBase = malloc(statInfo.st_size);
	if (!mpBase)
	{
		fclose(mFile);
		throw(GetLastError());
	}

	int amountRead = fread(mpBase, 1, statInfo.st_size, mFile);
	if (amountRead != statInfo.st_size)
	{
		fclose(mFile);
		throw(GetLastError());
	}

	fclose(mFile);
}

void clsMappedFile::UnMapFile()
{
	free(mpBase);
	mFile = 0;
	mpBase = 0;
}

void clsMappedFile::RefreshMap(void (*ReplaceFile)(LPCSTR lpOldFile, LPCSTR lpNewFile), LPCSTR lpNewFile)
{
	UnMapFile();
	ReplaceFile(mlpFileName, lpNewFile);
	MapFile(mlpFileName, mbWrite);
}
#endif