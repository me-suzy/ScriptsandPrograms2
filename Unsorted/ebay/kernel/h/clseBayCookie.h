/*	$Id: clseBayCookie.h,v 1.5 1999/02/21 02:46:59 josh Exp $	*/
//
//      File:           clseBayCookie.h
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
//
#ifndef __CLSEBAYCOOKIE_INCLUDE__
#define __CLSEBAYCOOKIE_INCLUDE__

#include "clseBayCookieEntry.h"

typedef enum 
{
	COOKIE_USERID	= 1,
    CookieAdult     = 2,
} COOKIE_ID;

class clseBayCookie
{
public:
	clseBayCookie();
	~clseBayCookie();

#ifndef _NO_STL
	void  SetCookie(COOKIE_ID Id, const char* pValue, bool NeedCrypt, time_t ExprationTime = 0); // default as session cookie
	void  RemoveCookie(COOKIE_ID Id);
	const char* GetCookieHeader();
#endif // _NO_STL

	const char* GetCookie(COOKIE_ID Id);
		// In the non-stl version the cookie value is only good until the next call
		// to GetCookie
	void  SetCookiesFromClient(const char* pCookies);
	static void BuildAdultCookie(unsigned char *pBuffer, const char* browserName);

protected:
	char* ParseCookiesFromClient(const char* pCookies, char* pName);
	char  GetCheckSumChar(const char* pSrc, int Length);
//	bool  ValidateCookie(const char* pCookie, int Legnth);
//	void  CreateKey(char* pDes, const char* pSrc, int SrcLength);

#ifndef _NO_STL
	void  ReleaseCookies();
	void  ParseCookieIntoVector(const char* pCookies, int Length, CookieEntryVector* pVector);
	char* BuildCookie(CookieEntryVector* pvCookies, bool Session);
	clseBayCookieEntry* FindCookie(COOKIE_ID Id, bool RemoveIt);
#endif // _NO_STL

	char* mpCookies;
	int	  mVersion;
	bool  mCookieRemoved;
	unsigned int   mCookieLength;
#ifdef _NO_STL
	char mLastCookieValue[512];	
		// Contains the last cookie value - only good until next call to GetCookie
#endif

#ifndef _NO_STL
	CookieEntryVector	mvSessionCookies;
	CookieEntryVector	mvNonSessionCookies;
#endif // _NO_STL
};

#endif // __CLSEBAYCOOKIE_INCLUDE__
