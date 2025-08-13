/*	$Id: clseBayCookieEntry.cpp,v 1.6 1999/04/17 20:22:45 wwen Exp $	*/
//
//      File:           clseBayCookieEntry.cpp
//
//      Class:          clseBayCookieEntry
//
//      Author:         Wen Wen (wen@ebay.com)
//
//      Function:
//                      a basic class for contructing a cookie
//
//      Modifications:
//                      - 07/31/98 Wen - Created
//
#include "eBayKernel.h"
#include "clseBayCookieEntry.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>


#ifndef ONE_DAY
#define ONE_DAY 86400
#endif

extern "C"
{
char *crypt(char *pPassword, char *pSalt);
};

#define COOKIE_INT_METHODS(variable)			\
int clseBayCookieEntry::Get##variable()	const	\
{												\
	return m##variable;							\
}												\
void clseBayCookieEntry::Set##variable(int newval)	\
{												\
	m##variable	= newval;						\
	return;										\
} 

#define COOKIE_BOOL_METHODS(variable)				\
bool clseBayCookieEntry::Get##variable()	const	\
{													\
	return m##variable;								\
}													\
void clseBayCookieEntry::Set##variable(bool newval)	\
{													\
	m##variable	= newval;							\
	return;											\
} 

#define COOKIE_TIME_METHODS(variable)				\
time_t clseBayCookieEntry::Get##variable()	const	\
{												\
	return m##variable;							\
}												\
void clseBayCookieEntry::Set##variable(time_t newval)	\
{												\
	m##variable	= newval;						\
	return;										\
} 

COOKIE_INT_METHODS(Version);
COOKIE_INT_METHODS(Id);
COOKIE_INT_METHODS(Length);
COOKIE_TIME_METHODS(CreationTime);
COOKIE_TIME_METHODS(ExpirationTime);

clseBayCookieEntry::clseBayCookieEntry()
{
	mVersion		= 0;
	mId				= 0;
	mLength			= 0;
	mCreationTime	= 0;
	mExpirationTime	= 0;
	mpValue			= NULL;
	mpPackedValue	= NULL;
	mNeedCrypt		= false;
}

clseBayCookieEntry::~clseBayCookieEntry()
{
	delete [] mpValue;
	delete [] mpPackedValue;
}


//
// Set the infomation for a new cookie
//
void clseBayCookieEntry::SetInfo(int Version,
								int Id,
								const char*	pValue,
								bool needCrypt,
								int	ExpirationTime
								)
{
	mVersion = Version;
	mId		 = Id;
	SetValue(pValue);
	mNeedCrypt		= needCrypt;
	if (ExpirationTime)
	{
		mExpirationTime	= ExpirationTime;
	}
	else
	{
		SetExpirationForSessionCookie();
	}
	mCreationTime	= time(0);
}

//
// Set cookie value
//
void clseBayCookieEntry::SetValue(const char* pValue)
{
	// if pValue is null, it is disable the cookie
	delete [] mpValue;
	delete [] mpPackedValue;
	mpValue = NULL;
	mpPackedValue = NULL;
	if (pValue && strlen(pValue) > 0)
	{
		mpValue = new char[strlen(pValue) + 1];
		strcpy(mpValue, pValue);
		BuildPackedValue();
	}
}

//
// Set Packed cookie value
//
void clseBayCookieEntry::SetPackedValue(int Version,
										int Id,
										const char* pPackedValue,
										int Length)
{
	mVersion = Version;

	// if pPackedValue is null, it is disable the cookie
	delete [] mpPackedValue;
	delete [] mpValue;
	mpPackedValue = NULL;
	mpValue = NULL;

	mId = Id;
	mLength = Length;
	if (pPackedValue && Length > 0)
	{
		mpPackedValue = new char [Length + 1];
		memcpy(mpPackedValue, pPackedValue, Length); // have to memcpy, pPackedValue doesn't have terminator
		mpPackedValue[Length] = 0;
		ParsePackedValue();
	}
}

// 
// Get cookie value
//
const char* clseBayCookieEntry::GetValue() const
{
	return mpValue;
}

// 
// ParsePackedValue()
//		try to extract value from the existing cookie value
//
void clseBayCookieEntry::ParsePackedValue()
{
	char*	pTemp;
	char*	pCookie;
	char*	pCryptedValue;
	char*	pSalt = GetSalt();

	// clear out the value first
	delete [] mpValue;
	mpValue = NULL;

	// invalid version number or cookie id (we have only one version so far)
	if (mVersion != 1 || mpPackedValue == NULL)
	{
		return;
	}

	// sanity check
	// the packed cookie format:
	// [creation][expiration][crypted][cookieValue]
	// The first two fields are time_t and the third one is char.
	// The length should be at lease holding the above fields
	if (mLength <= 2 * sizeof(time_t) + 1)
	{
		return;
	}

	// get creation time
	pTemp = mpPackedValue;
	memcpy(&mCreationTime, pTemp, sizeof(time_t));
	pTemp += sizeof(time_t);

	// get expiration time
	memcpy(&mExpirationTime, pTemp, sizeof(time_t));
	pTemp += sizeof(time_t);

	// get whether the value is crypted
	if (*pTemp == '1')
	{
		mNeedCrypt = true;
	}
	else
	{
		mNeedCrypt = false;
	}
	pTemp++;

	// get the value by finding the space between the value and crypted value
	pCookie = pTemp;

	if (mNeedCrypt)
	{
		if (pTemp = strchr(pCookie, ' '))
		{
			mpValue = new char[pTemp - pCookie + 1];
			strncpy(mpValue, pCookie, pTemp - pCookie);
			mpValue[pTemp - pCookie] = 0;

			// try to valid the value has not been modified
			pCryptedValue = crypt(mpValue, pSalt);
			if (strcmp(pCryptedValue, pTemp + 1) != 0)
			{
				// failed to verified
				// expire it
				mExpirationTime = time(0) -1;
				delete [] mpValue;
				mpValue = NULL;
			}
			free(pCryptedValue);
		}
	}
	else
	{
		mpValue = new char[strlen(pTemp) + 1];
		strcpy(mpValue, pTemp);
	}
}

//
// GetExistingValue 
//
const char* clseBayCookieEntry::GetPackedValue()
{
	if (mpPackedValue)
		return mpPackedValue;

	if (mpValue)
	{
		BuildPackedValue();
		return mpPackedValue;
	}

	return NULL;
}

//
// BuildPackedValue
//		Use the existing information to construct the packed cookie
//
void clseBayCookieEntry::BuildPackedValue()
{
	char*	pSalt = GetSalt();
	char*	pCryptedValue;
	char*	pTemp;

	delete [] mpPackedValue;
	mpPackedValue = NULL;

	if (mVersion != 1 || mpValue == NULL || mpValue[0] == 0)
	{
		return;
	}

	// get the crypted value
	pCryptedValue = crypt(mpValue, pSalt);

	// Set expiration time for session cookie
	if (mExpirationTime == 0)
		SetExpirationForSessionCookie();

	// put all stuff in to the specified format
	//
	// determine the size
	mLength = 2 * sizeof(time_t) + strlen(mpValue) + 1;
	
	if (mNeedCrypt)
	{
		// if need to crypt, include sizes of the Crypted value and space
		mLength += strlen(pCryptedValue) + 1;
	}

	mpPackedValue = new char[mLength + 1];
	pTemp = mpPackedValue;

	memcpy(pTemp, &mCreationTime, sizeof(time_t));
	pTemp += sizeof(time_t);
	
	memcpy(pTemp, &mExpirationTime, sizeof(time_t));
	pTemp += sizeof(time_t);
	
	if (mNeedCrypt)
	{
		memcpy(pTemp, "1", 1);
	}
	else
	{
		memcpy(pTemp, "0", 1);
	}
	pTemp++;

	memcpy(pTemp, mpValue, strlen(mpValue));
	pTemp += strlen(mpValue);
	
	if (mNeedCrypt)
	{
		// space to separate value and Crypted value if needed
		*pTemp = ' ';
		pTemp++;
		memcpy(pTemp, pCryptedValue, strlen(pCryptedValue));
		pTemp += strlen(pCryptedValue);
	}

	// terminator
	*pTemp = 0;

	free(pCryptedValue);
}

//
// IsValid
//		Check whether the cookie is valid
//
bool clseBayCookieEntry::IsValid()
{
	if (mVersion == 0 || mId == 0 || mpValue == NULL ||
		(time(0) >= mExpirationTime))
		return false;

	return true;
}

//
// Get salt
//
char* clseBayCookieEntry::GetSalt()
{
	// for version 1
	return "EM_51_0017";
}

//
// SetExpirationForSessionCookie
//		Set expiration time for session cookie, for now it is set
//		to be end of the day (or begining of the next day)
//
void clseBayCookieEntry::SetExpirationForSessionCookie()
{
	struct tm*	pExpirationTm;
	
	// Set to one more day from the current time
	mExpirationTime = time(0) + ONE_DAY;

	// then set to the zero hour of the next day
	pExpirationTm = localtime(&mExpirationTime);
    pExpirationTm->tm_sec = 0;
    pExpirationTm->tm_min = 0;
    pExpirationTm->tm_hour = 0;

	// set expiration time to the zero hour of the next day
	mExpirationTime = mktime(pExpirationTm);
}

//
// IsExpired
//		Check whether the cookie has been expired
//
bool clseBayCookieEntry::IsExpired()
{
	return time(0) >= mExpirationTime;
}

