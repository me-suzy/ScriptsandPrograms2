/*	$Id: clsRawFiletSet.cpp,v 1.3 1999/02/21 02:21:28 josh Exp $	*/
#define STRICT
#include <windows.h>
#include <Httpext.h>
#include <HttpFilt.h>
#include "CobrandStatic.h"

#include <time.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <stdlib.h>
#include <stdio.h>
#include <crtdbg.h>
#include <strstrea.h>

#include "clsRawFileSet.h"
#undef STRICT

#ifdef _MSC_VER
#define _stat stat
#endif // _MSC_VER

static int min4(const char *p1, const char *p2, const char *p3, const char *p4)
{
	const char *pWinner;

	if (p1 == NULL && p2 == NULL && p3 == NULL && p4 == NULL)
		return 0;

	// Let p1 and p2 battle it out.
	if (!p2 || (p1 && p1 < p2))
		pWinner = p1;
	else
		pWinner = p2;

	// Now, let pWinner and p3 battle it out.
	if (!pWinner || (p3 && p3 < pWinner))
		pWinner = p3;

	// Now, let pWinner and p4 battle it out.
	if (!pWinner || (p4 && p4 < pWinner))
		pWinner = p4;

	// Now, return the right one.
	if (!pWinner)
		return 0; // This shouln't occur, because of the first check...

	if (pWinner == p1)
		return 1;

	if (pWinner == p2)
		return 2;

	if (pWinner == p3)
		return 3;

	if (pWinner == p4)
		return 4;

	return 0;
}

// Our tokens.
static const char *sCGIToken = "/awsub-cgi/";
static const char *sHTMLToken = "/awsub/";
static const char *sHeaderToken = "<!--header-->";
static const char *sFooterToken = "<!--footer-->";

static const int sCGITokenLength = strlen(sCGIToken);
static const int sHTMLTokenLength = strlen(sHTMLToken);
static const int sHeaderTokenLength = strlen(sHeaderToken);
static const int sFooterTokenLength = strlen(sFooterToken);

void clsRawFile::ParseFile(WIN32_FIND_DATA *pInfo)
{
	unsigned long length = pInfo->nFileSizeLow;
	FILE *pFile;

	pFile = fopen(pInfo->cFileName, "r");

	ParseFile(pFile, pInfo->cFileName, length);
}

void clsRawFile::ParseFile(FILE *pFile, const char *pName, unsigned long length)
{
	char *pWalk;
	bool hasHeader;
	bool hasFooter;
	char **pCurrentPiece;
	int winner;
	struct stat theStat;

	char *pRace1, *pRace2, *pRace3, *pRace4;

	// Reset these, in case we've been reading some other file.
	mNumCGITokens = 0;
	mNumHTMLTokens = 0;
	mNumTotalPieces = 0;
	mLastModified = 0L;
	
	delete [] mpPieces;
	mpPieces = NULL;

	strcpy(mName, pName);

	if (!stat(pName, &theStat))
	{
		if (!length)
			length = theStat.st_size;
		mLastModified = theStat.st_mtime;
	}
	else
		mLastModified = time(NULL);

	// If we don't have a file, open it and such.
	if (!pFile)
	{
		pFile = fopen(pName, "r");
	}

	// We don't reset mpTextBase or mTextBaseSize, because these are possibly reusable.
	// We need to check that it's large enough, though.
	if (length >= mTextBaseSize + 1)
	{
		if (mpTextBase) // Yes, we actually have to check, since in this case it is not safe to delete, since it wouldn't be 'NULL'
			delete [] (mpTextBase - 1); // We allocate funny.
		mTextBaseSize = length + 2; // One for the beginning token.
		mpTextBase = new char [mTextBaseSize] + 1; // Keep a safe char at the beginning.
	}

	if (!pFile)
	{
		// If we don't have the file, we set that indicator in ourselves,
		// and then set mValid to true -- the intent here is that a bad
		// file link will not try to look up the file each time.
		// When we 'draw' the file we'll give a 404.
		mNumTotalPieces = 0; 
		mValid = true;
		return;
	}

	if (!(length = fread(mpTextBase, sizeof (char), length, pFile)))
	{
		fclose(pFile);
		return;
	}

	fclose(pFile);

	mpTextBase[length] = '\0';
	// Start out with being full length.
	mParsedNoTokensSize = length;

	// Find the page type.
	pWalk = strstr(mpTextBase, "<!--page type ");
	if (pWalk)
	{
		pWalk += strlen("<!--page type ");
		mPageType = atoi(pWalk);
	}
	else
		mPageType = 0;

	// Now we count how many 'pieces' we have.

	// This is how many HTML pieces.
	pWalk = mpTextBase;
	while ((pWalk = strstr(pWalk, sHTMLToken)))
	{
		pWalk = pWalk + 1;
		++mNumHTMLTokens;
	}

	// This is how many CGI pieces.
	pWalk = mpTextBase + 1;
	while ((pWalk = strstr(pWalk, sCGIToken)))
	{
		pWalk = pWalk + 1;
		++mNumCGITokens;
	}

	hasHeader = strstr(mpTextBase, sHeaderToken) != NULL;
	hasFooter = strstr(mpTextBase, sFooterToken) != NULL;

	// Number of pieces is one greater than number of dividers...
	// Three -- one for header, one for footer, one because we need it, one for NULL.
	mNumTotalPieces = mNumHTMLTokens + mNumCGITokens + 1 + 1 + 1 + 1;
	mpPieces = new char* [mNumTotalPieces];
	pCurrentPiece = mpPieces;

	// Now, fill the pieces.

	if (!hasHeader)
	{
		// If we don't have a header, then we need to make it our first piece.
		*pCurrentPiece = "H";
		++pCurrentPiece;
	}

	*(mpTextBase - 1) == 'N';
	*pCurrentPiece = (mpTextBase - 1);
	++pCurrentPiece;

	// Now we begin the great run through our tags -- the trick is that we have to do them
	// in order...
	// So, we queue up the stuff...
	pWalk = mpTextBase;

	pRace1 = pRace2 = pRace3 = pRace4 = NULL;

	if (hasHeader)
		pRace1 = strstr(pWalk, sHeaderToken);

	if (hasFooter)
		pRace2 = strstr(pWalk, sFooterToken);

	if (mNumCGITokens)
		pRace3 = strstr(pWalk, sCGIToken);

	if (mNumHTMLTokens)
		pRace4 = strstr(pWalk, sHTMLToken);

	while ((winner = min4(pRace1, pRace2, pRace3, pRace4)))
	{
		switch (winner)
		{
		case 1:
			// Zero our previous string.
			*pRace1 = '\0';

			// Set our marker.
			pRace1 += sHeaderTokenLength - 1;
			*pRace1 = 'H';

			// Add it to the pieces.
			*pCurrentPiece = pRace1;
			++pCurrentPiece;

			// And advance our racer.
			pRace1 = NULL;

			// Decrement our size.
			mParsedNoTokensSize -= sHeaderTokenLength;
			break;

		case 2:
			// Zero our previous string.
			*pRace2 = '\0';

			// Set our marker.
			pRace2 += sFooterTokenLength - 1;
			*pRace2 = 'F';

			// Add it to the pieces.
			*pCurrentPiece = pRace2;
			++pCurrentPiece;

			// And advance our racer.
			pRace2 = NULL;
			mParsedNoTokensSize -= sFooterTokenLength;
			break;

		case 3:
			// Zero our previous string.
			*pRace3 = '\0';

			// Set our marker.
			pRace3 += sCGITokenLength - 1;
			*pRace3 = 'C';

			// Add it to the pieces.
			*pCurrentPiece = pRace3;
			++pCurrentPiece;

			// And advance our racer.
			pRace3 = strstr(pRace3, sCGIToken);

			// Decrement our size.
			mParsedNoTokensSize -= sCGITokenLength;
			break;

		case 4:
			// Zero our previous string.
			*pRace4 = '\0';

			// Set our marker.
			pRace4 += sHTMLTokenLength - 1;
			*pRace4 = 'T';

			// Add it to the pieces.
			*pCurrentPiece = pRace4;
			++pCurrentPiece;

			// And advance our racer.
			pRace4 = strstr(pRace4, sHTMLToken);

			// Decrement our size.
			mParsedNoTokensSize -= sHTMLTokenLength;
			break;
		default:
			break;
		}
	}

	if (!hasFooter)
	{
		*pCurrentPiece = "F";
		++pCurrentPiece;
	}

	*pCurrentPiece = NULL;

	// Initialize the header string...

	struct tm *pTm;
	time_t theTime;
	char lastModifiedTime[32];
	char expiredTime[32];

	pTm = gmtime(&mLastModified);

	strftime(lastModifiedTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTm);

	theTime = time(NULL);
	if (theTime - mLastModified < (60 * 20)) // Min of 20 minute difference...
		theTime = mLastModified + (60 * 20);

	theTime = theTime + (theTime - mLastModified);
	// Temporary, for testing.
	theTime = 100L;
	pTm = gmtime(&theTime);

	strftime(expiredTime, 32, "%a, %d %b %Y %H:%M:%S GMT", pTm);

    sprintf(mHeader, "Content-Type: text/html\r\n"
        "Expires: %s\r\n"
        "Last-Modified: %s\r\n"
        "Content-Length: ########\r\n\r\n",
		expiredTime,
		lastModifiedTime);

	mValid = true;
}

void clsRawFile::WriteToStream(const char *pCGIToken,
							   const char *pHTMLToken,
							   const char *pHeader,
							   const char *pFooter,
							   ostream *pStream)
{
	char **ppPiece;
	unsigned long i;

	if (!mNumTotalPieces)
	{
		*pStream << "HTTP/1.0 404 Not Found\r\n" // Odd dialog boxes returning 404...
					"Content-Length: ########\r\n\r\n"
					"<body><h1>HTTP/1.0 404 Object Not Found</h1></body>";
		return;
	}

	*pStream << "HTTP/1.0 200 OK\r\n"
			 << mHeader;

	for (i = 0, ppPiece = mpPieces; i < mNumTotalPieces && *ppPiece; ++i, ++ppPiece)
	{
		switch (**ppPiece)
		{
		case 'H': // Header.
			*pStream << pHeader;
			break;
		case 'F': // Footer.
			*pStream << pFooter;
			break;
		case 'T': // hTml.
			*pStream << pHTMLToken;
			break;
		case 'C': // Cgi.
			*pStream << pCGIToken;
		case 'N': // Normal.
		default:
			break;
		}
		
		// Now, if we have more string after the token indicator, we glom that on as well.
		if (*((*ppPiece) + 1))
			*pStream << ((*ppPiece) + 1);
	}
}

class clsRawFileLink
{
public:
	clsRawFile *pNode;
	clsRawFileLink *pNext;
};

static clsRawFileLink *sHead = NULL;
static int sNumInvalids = 0;
static const int sMaxInvalids = 256;

CRITICAL_SECTION sCrit;

void StartFileSet()
{
	InitializeCriticalSection(&sCrit);
}

void EndFileSet()
{
	DeleteCriticalSection(&sCrit);
}

static clsRawFile *RealGetFile(const char *pName)
{
	clsRawFileLink *pCurrent;

	pCurrent = sHead;

	while (pCurrent)
	{
		if (!strcmp(pName, pCurrent->pNode->mName) && pCurrent->pNode->mValid)
			return pCurrent->pNode;

		pCurrent = pCurrent->pNext;
	}

	return NULL;
}

void Invalidate(const char *pName)
{
	clsRawFile *pFile = RealGetFile(pName);

	if (!pFile)
		return;

	// We need exclusive access to invalidate...
	EnterCriticalSection(&sCrit);
	try
	{
		pFile->mValid = false;
		++sNumInvalids;
	}
	catch(...)
	{
		LeaveCriticalSection(&sCrit);
		throw;
	}
	LeaveCriticalSection(&sCrit);
}

static clsRawFile *AddFile(const char *pName)
{
	clsRawFile *pRet;
	bool madeNew = false;

	// Enter a critical section, so nobody else messes with this...
	EnterCriticalSection(&sCrit);

	try
	{
		// First, see if it got added while we weren't looking...
		pRet = RealGetFile(pName);

		// It did.
		if (pRet)
		{
			LeaveCriticalSection(&sCrit);
			return pRet;
		}

		// Otherwise, we need to make it ourselves...

		// Should we construct an object?
		if (sNumInvalids < sMaxInvalids)
		{
			pRet = new clsRawFile;
			madeNew = true;
		}
		else
		{
			// Reuse one of our invalids...
			clsRawFileLink *pCurrent;

			pCurrent = sHead;

			while (pCurrent && pCurrent->pNode->mValid)
			{
				pCurrent = pCurrent->pNext;
			}

			// Okay, we don't have an invalid after all... this is probably an error. Hmm.
			if (!pCurrent)
			{
				pRet = new clsRawFile;
				madeNew = true;
			}
			else
			{
				pRet = pCurrent->pNode;
				--sNumInvalids;
			}
		}

		// Now we have a pRet.
		// Parse the file.
		pRet->ParseFile(NULL, pName, 0L);

		// If we made a new one, we need to add it to the list. We'll make it the head.
		if (madeNew)
		{
			clsRawFileLink *pLink = new clsRawFileLink;
			pLink->pNode = pRet;
			pLink->pNext = sHead;
			sHead = pLink;
		}
	}
	catch(...)
	{
		LeaveCriticalSection(&sCrit);
		throw;
	}

	LeaveCriticalSection(&sCrit);
	return pRet;
}

clsRawFile *GetFile(const char *pName)
{
	clsRawFile *pRet;

	pRet = RealGetFile(pName);

	if (pRet)
		return pRet;

	pRet = AddFile(pName);
	return pRet;
}

