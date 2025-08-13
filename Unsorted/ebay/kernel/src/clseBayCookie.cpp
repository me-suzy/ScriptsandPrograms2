/*	$Id: clseBayCookie.cpp,v 1.9.250.1 1999/08/01 03:02:36 barry Exp $	*/
//
//      File:           clseBayCookie.cpp
//
//      Class:          clseBayCookie
//
//      Author:         Wen Wen (wen@ebay.com)
//
//      Function:
//                              class for constructing and parsing cookie
//
//      Modifications:
//                              - 05/05/98 Wen - Created
//								- 06/01/99 nsacco	- added site and partner id to BuildCookie
//
#include "eBayKernel.h"
#include "clseBayCookie.h"
#include "clsBase64.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>


#ifndef ONE_DAY
#define ONE_DAY 86400
#endif

extern "C"
{
#include "md5.h"
}


//
// constructor
//
clseBayCookie::clseBayCookie()
{
	mVersion = 1; // defalut to first version
	mpCookies = NULL;
	mCookieRemoved = false;
}

//
// Destructor
//
clseBayCookie::~clseBayCookie()
{
#ifdef _NO_STL
	delete [] mpCookies;
#else // _NO_STL
	ReleaseCookies();
#endif // _NO_STL
}

//
// SetCookiesFromClient
//		Parse in the cookies received from client
//
void clseBayCookie::SetCookiesFromClient(const char* pCookies)
{
	char*		pTemp;
	const char*	pACookie;
	int			CookieLength = 0;
	clsBase64	Base64;

	// Get session cookies
	if (pTemp = ParseCookiesFromClient(pCookies, "s="))
	{
		// decode the cookies
		pACookie = Base64.Decode(pTemp, CookieLength);
		mCookieLength = CookieLength;
		delete pTemp;

		if (pACookie)
		{
#ifdef _NO_STL
			delete mpCookies;
			mpCookies = new char [CookieLength + 1];
			memcpy(mpCookies, pACookie, CookieLength);
			mpCookies[CookieLength] = '\0';
#else // _NO_STL
			// put it into vector
			ParseCookieIntoVector(pACookie, CookieLength, &mvSessionCookies);
#endif // _NO_STL
		}
	}

	// Get non-session cookie
	if (pTemp = ParseCookiesFromClient(pCookies, "ns="))
	{
		// decode the cookies
		pACookie = Base64.Decode(pTemp, CookieLength);
		mCookieLength = CookieLength;
		delete pTemp;

		if (pACookie)
		{
#ifdef _NO_STL
			// Nothing.
#else // _NO_STL
			// put it into vector
			ParseCookieIntoVector(pACookie, CookieLength, &mvNonSessionCookies);
#endif // _NO_STL
		}
	}
}

#ifndef _NO_STL
//
// ParseCookieIntoVector
//		Parse a combined cookie into a vector
//
void clseBayCookie::ParseCookieIntoVector(const char* pCookies, int CookieLength, CookieEntryVector* pVector)
{
	int		NumCookies;
	int		Offset, i = 0;
	int		Length;
	int		Version;
	COOKIE_ID	Id;
	clseBayCookieEntry* pCookieEntry;

	// sanity check
	// The cookie format: [vesion][numberOfCookie][cookieID][length][packedCookie]
	// The first four fields are integer. 
	// So the CookieLength is at lease 4 times size of integer.
	if (CookieLength <= 4 * sizeof(int))
		return;

	// get version number
	memcpy(&Version , pCookies, sizeof(int));
	Offset = sizeof(int);

	// get number of cookies
	memcpy(&NumCookies, pCookies + Offset, sizeof(int));
	Offset += sizeof(int);

	for (i = 0; i < NumCookies; i++)
	{
		// sanity check
		// For each cookie: [cookieID][length][packedCookie]
		// So the Cookie length should be at lease long enough
		// to hold [cookieID] and [length]
		if (CookieLength <= 2 * sizeof(int) + Offset)
			break;

		// get cookie id
		memcpy(&Id, pCookies + Offset, sizeof(COOKIE_ID));
		Offset += sizeof(int);

		// get cookie length
		memcpy(&Length, pCookies + Offset, sizeof(int));
		Offset += sizeof(int);

		// sanity check
		// The cookie length should be at lease long enough
		// to hold the packed cookie value
		if (CookieLength < Length + Offset)
			break;

		pCookieEntry = FindCookie(Id, true);

		if (pCookieEntry == NULL)
		{
			// create a cookie entry object and put it in the vector
			pCookieEntry = new clseBayCookieEntry;
		}

		pCookieEntry->SetPackedValue(Version, Id, 
									 (const char*) (pCookies + Offset), 
									 Length);
		Offset += Length;

		// if the cookie has been expired, don't add it to the vector
		if (!pCookieEntry->IsValid())
		{
			delete pCookieEntry;
		}
		else
		{
			pVector->push_back(pCookieEntry);
		}
	}
}
#endif // _NO_STL

//
// ParseCookieFromClient
//		Parse the cookie from client
//
char* clseBayCookie::ParseCookiesFromClient(const char* pCookies, char* pName)
{
	char*	pTemp1, *pTemp2;
	char*	pACookie = NULL;
	int		Length;
	char	c;

	// Get the session cookie
	pTemp1 = strstr(pCookies, pName);
	if (pTemp1)
	{
		pTemp1 += strlen(pName);
		pTemp2=strchr(pTemp1, ';');
		if (pTemp2)
		{
			Length = pTemp2-pTemp1;
		}
		else
		{
			Length = strlen(pTemp1);
		}

		c = GetCheckSumChar(pTemp1, Length - 1);
		if (c == pTemp1[Length - 1])
//		if (ValidateCookie(pTemp1, Length))
		{
			// pass checksum test,
			// make a copy of the cookie value
			// (discard the checksum chararctor)
			pACookie = new char[Length];
			strncpy(pACookie, pTemp1, Length-1);
			pACookie[Length-1] = 0;
		}
	}
	
	return pACookie;
}

/*
//
// Check to whether the cookie has been modified
//
bool clseBayCookie::ValidateCookie(const char* pCookie, int Length)
{
	const char*	p;
	char  pEncryptedString[25]; // the size should larger than 6 * 4 / 3

	// find the separator
	p = strchr(pCookie, '_');
	if (p == NULL || p - pCookie >= Length-1)
		return false;

	// get encrypted string
	CreateKey(pEncryptedString, pCookie, p-pCookie);

	if (strncmp(pEncryptedString, (p + 1), strlen(pEncryptedString)))
	{
		// user touched the cookie
		return false;
	}

	return true;
}

//
// Create a scret key to detect use to modify the cookie
//
void clseBayCookie::CreateKey(char* pDes, const char* pSrc, int SrcLength)
{
	clsBase64	theBase;
    MD5_CTX ctx;
	unsigned char MD5String[16];
    char buffer[256];
    const unsigned char *pUnsigned;

    pUnsigned = (const unsigned char *) buffer;

    MD5Init(&ctx);

	// add ebay birth day
	strcpy(buffer, "Labour Day 1995");
	MD5Update(&ctx, pUnsigned, strlen(buffer));

	// put in a key
    strcpy(buffer, "just a simple secret key");
	MD5Update(&ctx, pUnsigned, strlen(buffer));

    // And the string.
    MD5Update(&ctx, (const unsigned char*) pSrc, SrcLength);

    MD5Final(MD5String, &ctx);

	// encode it
	strcpy(pDes, theBase.Encode((const char*)MD5String, sizeof(MD5String)));
}
*/

#ifndef _NO_STL
//
//	Construct cookie headers
//
const char* clseBayCookie::GetCookieHeader()
{
	int		Length = 0;
	char*	pSessionCookie = NULL;
	char*	pNonSessionCookie = NULL;

	// Combine session cookies into one
	pSessionCookie = BuildCookie(&mvSessionCookies, true);
	Length += strlen(pSessionCookie);

	// combine non-session cookies
	pNonSessionCookie = BuildCookie(&mvNonSessionCookies, false);
	Length += strlen(pNonSessionCookie);

	// clean up
	delete mpCookies;
	mpCookies = NULL;

	if (Length)
	{
		mpCookies = new char [Length + 1];
		mpCookies[0] = 0;

		if(pSessionCookie)
		{
			strcat(mpCookies, pSessionCookie);
			delete pSessionCookie;
		}

		if(pNonSessionCookie)
		{
			strcat(mpCookies, pNonSessionCookie);
			delete pNonSessionCookie;
		}
	}

	return mpCookies;
}

//
// Clean cookie
//
void clseBayCookie::ReleaseCookies()
{
	CookieEntryVector::iterator	iEntry;

	delete [] mpCookies;
	mpCookies = NULL;

	// remove entries
	for (iEntry = mvSessionCookies.begin(); iEntry != mvSessionCookies.end(); iEntry++)
	{
		delete (*iEntry);
	}
	mvSessionCookies.erase(mvSessionCookies.begin(), mvSessionCookies.end());

	for (iEntry = mvNonSessionCookies.begin(); iEntry != mvNonSessionCookies.end(); iEntry++)
	{
		delete (*iEntry);
	}
	mvNonSessionCookies.erase(mvNonSessionCookies.begin(), mvNonSessionCookies.end());

}

//
// BuildCookie
//
char* clseBayCookie::BuildCookie(CookieEntryVector* pvCookies, bool Session)
{
	char*	pCookies;
	char	Temp[4097];
	int		i = 0;
	int		NumberOfCookies;
	const char* pEncodedCookie;
	char	c;
	int		Length;
	COOKIE_ID	Id;
	// nsacco 06/01/99
	int siteId;
	int partnerId;
	clsSite* pSite;
	char pDomainTmp[256];
	char pPath[256];
	char* pDomain;
	char* p;

	CookieEntryVector::iterator	iCookie;

	// nsacco 06/01/99
	pSite = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->GetCurrentSite();
	siteId = pSite->GetId();
	partnerId = pSite->GetPartners()->GetCurrentPartner()->GetId();

	// wen 06/07/99. get the right domain format
	strcpy(pDomainTmp, clsUtilities::GetDomainToken(siteId, partnerId));
	pDomain = pDomainTmp;
	p = strchr(pDomainTmp, '.');
	if (p)
		pDomain = p;

	// wen 06/07/99. get the right path
	p = strchr(pDomainTmp, '/');
	if (p)
	{
		strcpy(pPath, p);
		p = '\0';
	}
	else
	{
		pPath[0] = '/';
		pPath[1] = '\0';
	}

	if ((NumberOfCookies = pvCookies->size()) <= 0)
	{
		pCookies = new char[100];
		pCookies[0] = 0;

		if (mCookieRemoved)
		{
			// expire the cookie to make sure the cookie is removed
			// use pDomain instead of .ebay.com
			sprintf(pCookies, 
				"Set-Cookie: %s=x; path=%s; domain=%s; expires=Fri, 01-Sep-1995 00:00:00 GMT\r\n",
				Session ? "s" : "ns", pPath, pDomain);
		}
		return pCookies;
	}

	// get number of valid cookies
	NumberOfCookies = 0;
	for (iCookie = pvCookies->begin(); iCookie != pvCookies->end(); iCookie++)
	{
		if ((*iCookie)->IsValid())
		{
			NumberOfCookies++;
		}
	}

	// put in version
	memcpy(Temp, &mVersion, sizeof(int));
	i += sizeof(int);

	// put in number of cookies
	memcpy(Temp + i, &NumberOfCookies, sizeof(int));
	i += sizeof(int);

	// put in information for each cookie
	for (iCookie = pvCookies->begin(); iCookie != pvCookies->end(); iCookie++)
	{
		if ((*iCookie)->IsValid())
		{
			// copy the cookie id
			Id = (COOKIE_ID) (*iCookie)->GetId();
			memcpy(Temp + i, &Id, sizeof(COOKIE_ID));
			i += sizeof(int);

			// copy the length of a cookie
			Length = (*iCookie)->GetLength();
			memcpy(Temp + i, &Length, sizeof(int));
			i += sizeof(int);

			// copy the packed value of the cookie
			memcpy(Temp + i, (*iCookie)->GetPackedValue(), Length);
			i += Length;
		}
	}

	// encode it with base64
	clsBase64	Base64;
	pEncodedCookie = Base64.Encode(Temp, i);

	// construct a secret key
//	char	Key[25];
//	CreateKey(Key, pEncodedCookie, strlen(pEncodedCookie));
	c = GetCheckSumChar(pEncodedCookie, strlen(pEncodedCookie));

	// make it into a cookie format
	pCookies = new char[strlen(pEncodedCookie) + 100];
	pCookies[0] = 0;
	if (Session)
	{
		// session cookie
		// nsacco 06/01/99 use pDomain instead of .ebay.com
		sprintf(pCookies, 
			"Set-Cookie: s=%s%c; path=%s; domain=%s\r\n", 
			pEncodedCookie, c, pPath, pDomain);
	}
	else
	{
		// non-session cookie
		struct tm*	tmGMT;
		time_t		ExpireTime = time(0) + 365 * ONE_DAY;	// exipre in one year
		char		Expires[30];

		tmGMT = gmtime( &ExpireTime );
		strftime(Expires, sizeof(Expires), "%a, %d-%b-%Y %H:%M:%S GMT", tmGMT);

		// nsacco 06/01/99 use pDomain instead of .ebay.com
		sprintf(pCookies, 
			"Set-Cookie: ns=%s%c; path=%s; domain=%s; Expires=%s\r\n", 
			pEncodedCookie, c, pPath, pDomain, Expires);
	}

	return pCookies;
}
#endif // _NO_STL

//
// GetCheckSumChar
//		Get a mod 10 check sum charactor for a string
//
char clseBayCookie::GetCheckSumChar(const char* pSrc, int Length)
{
	int		Sum = 0;
	char	c = 0;
	int		i;

	for (i = 0; i < Length && pSrc[i] != 0; i++)
	{
		Sum += pSrc[i];
	}

	c = (char) (10 - Sum % 10) + 100;

	return c;
}

#ifndef _NO_STL
//
// SetCookie
//		Set a cookie
//
void clseBayCookie::SetCookie(COOKIE_ID Id, 
							  const char* pValue,
							  bool NeedCrypt,
							  time_t ExpirationTime /*= 0*/)
{
	clseBayCookieEntry*	pCookieEntry;

	if (ExpirationTime != 0 && (time(0) >= ExpirationTime))
	{
		// the new cookie is expired, do nothing
		return;
	}

	// find the cookie and remove from vector
	pCookieEntry = FindCookie(Id, true);

	// not found, create a new one
	if (pCookieEntry == NULL)
	{
		pCookieEntry = new clseBayCookieEntry;
	}

	// set the new info into the cookie entry
	pCookieEntry->SetInfo(mVersion, Id, pValue, NeedCrypt, ExpirationTime);

	// put the entry in the vector
	if (ExpirationTime == 0)
	{
		mvSessionCookies.push_back(pCookieEntry);
	}
	else
	{
		mvNonSessionCookies.push_back(pCookieEntry);
	}
}
#endif // _NO_STL
//
// GetCookie
//		Get cookie
//
const char* clseBayCookie::GetCookie(COOKIE_ID findId)
{
#ifdef _NO_STL
	int		NumCookies;
	int		Offset, i = 0;
	unsigned int		Length;
	int		Version;
	COOKIE_ID	Id;

	if (!mpCookies)
		return NULL;

	// Sanity check
	// The cookie format: [vesion][numberOfCookie][cookieID][length][packedCookie]
	// The first four fields are integer. 
	// So the CookieLength is at lease 4 times size of integer.
	if (mCookieLength <= 4 * sizeof(int))
	{
		return NULL;
	}

	// get version number
	memcpy(&Version , mpCookies, sizeof(int));
	Offset = sizeof(int);

	// get number of cookies
	memcpy(&NumCookies, mpCookies + Offset, sizeof(int));
	Offset += sizeof(int);

	for (i = 0; i < NumCookies; i++)
	{
		// Sanity check
		// For each cookie: [cookieID][length][packedCookie]
		// So the Cookie length should be at lease long enough
		// to hold [cookieID] and [length]
		if (mCookieLength <= 2 * sizeof(int) + Offset)
		{
			return NULL;
		}

		// get cookie id
		memcpy(&Id, mpCookies + Offset, sizeof(COOKIE_ID));
		Offset += sizeof(int);

		// get cookie length
		memcpy(&Length, mpCookies + Offset, sizeof(int));
		Offset += sizeof(int);

		// Sanity check
		// The cookie length should be at lease long enough
		// to hold the packed cookie value
		if (mCookieLength < Length + Offset)
		{
			return NULL;
		}

		if (Id != findId)
		{
			Offset += Length;
			continue;
		}

		clseBayCookieEntry theEntry;
		theEntry.SetPackedValue(Version, Id, (const char *) (mpCookies + Offset),
			Length);

		if (theEntry.IsExpired())
			return NULL;

		const char* value = theEntry.GetValue();
		if (!value || strlen(value) >= (sizeof(mLastCookieValue) - 1) )
			return NULL;

		strcpy(mLastCookieValue, value);
		return mLastCookieValue;
	}
	return NULL;

#else // _NO_STL
	clseBayCookieEntry*	pCookieEntry;

	// find the cookie but not remove from vector
	pCookieEntry = FindCookie(findId, false);

	if (pCookieEntry)
	{
		return pCookieEntry->GetValue();
	}

	return NULL;
#endif // _NO_STL
}

#ifndef _NO_STL
//
// FindCookie
//		Find a cookie by scan through session and non-session vector
//
clseBayCookieEntry* clseBayCookie::FindCookie(COOKIE_ID Id, bool RemoveIt)
{
	CookieEntryVector::iterator	iEntry;
	clseBayCookieEntry*	pEntry = NULL;

	for (iEntry = mvSessionCookies.begin(); iEntry != mvSessionCookies.end(); iEntry++)
	{
		if ( (*iEntry)->GetId() == Id)
		{
			pEntry = *iEntry;
			if (RemoveIt)
			{
				mvSessionCookies.erase(iEntry);
			}

			return pEntry;
		}
	}

	for (iEntry = mvNonSessionCookies.begin(); iEntry != mvNonSessionCookies.end(); iEntry++)
	{
		if ( (*iEntry)->GetId() == Id)
		{
			pEntry = *iEntry;
			if (RemoveIt)
			{
				mvNonSessionCookies.erase(iEntry);
			}

			return pEntry;
		}
	}

	return NULL;
}

//
// RemoveCookie
//		Remove a cookie specified by id
//
void clseBayCookie::RemoveCookie(COOKIE_ID Id)
{
	clseBayCookieEntry*	pEntry;

	pEntry = FindCookie(Id, true);

	if (pEntry)
	{
		mCookieRemoved = true;
		delete pEntry;
	}
}

#endif // _NO_STL


void clseBayCookie::BuildAdultCookie(unsigned char *pBuffer, const char* browserName)
{
    MD5_CTX ctx;
    time_t theTime;
    char buffer[256];
    const unsigned char *pUnsigned;

    pUnsigned = (const unsigned char *) buffer;

    MD5Init(&ctx);

    // The first string we use is the time string, which we have to build.
    theTime = time(NULL) / 86400; // This makes the cookie expire every 24 hours.
    memset(buffer, '\0', sizeof (buffer));
    sprintf(buffer, "%d", theTime);

    MD5Update(&ctx, pUnsigned, strlen(buffer));

    // Now we get the browser string.
    memset(buffer, '\0', sizeof (buffer));
    strncpy(buffer, browserName, sizeof (buffer) - 1);
    //strncpy(buffer, gApp->GetEnvironment()->GetBrowser(), sizeof (buffer) - 1);
    buffer[sizeof (buffer) - 1] = '\0';

    MD5Update(&ctx, pUnsigned, strlen(buffer));

    // And a secret string.
    memset(buffer, '\0', sizeof (buffer));
    strcpy(buffer, "This is the adult cookie.");
    MD5Update(&ctx, pUnsigned, strlen(buffer));

    MD5Final(pBuffer, &ctx);
}

