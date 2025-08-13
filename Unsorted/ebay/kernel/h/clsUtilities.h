/*	$Id: clsUtilities.h,v 1.12.118.1 1999/08/01 03:02:12 barry Exp $	*/
//
//	File:	clsUtilities.h
//
//	Class:	clsUtilities
//
//	Author:	Poon
//
//	Function:
//			A class to hold miscellaneous utility functions.
//			
//			All of these functions are static so that clients need
//			not instanitate this class. Clients call these functions by
//				1) including clsUtilities.h
//				2) calling clsUtilities::functionname()
//
//
// Modifications:
//				- 10/14/97	Poon - Created
//				- 10/17/97	Chad - Moved Scramble from clsScramble
//				- 01/27/99	mila - added method MakeSafeUserId()
//				- 01/28/99	mila	- Added new method MakeSafeString()
//				- 04/22/99  Anoop	- Added new method SendEmail(...)
//				- 06/01/99	petra	- added new method HasSpecialCharacters(..)
//
#ifndef CLSUTILITIES_INCLUDED
#define CLSUTILITIES_INCLUDED

#include <time.h>
#include <vector.h>
#include "clsMail.h"

class ostream;

// macro utility functions
#define IseBayAlpha(c)		(((c >= 'A') && (c <= 'Z')) || ((c >= 'a') && (c <= 'z')))
#define IseBayDigit(c)		((c >= '0') && (c <= '9'))
#define IseBayAlnum(c)		(IseBayAlpha(c) || IseBayDigit(c))

class clsUtilities  
{
public:

	clsUtilities() {};
	virtual ~clsUtilities() {};

	// returns true if the given text contains more than threshold% captial letters
	static bool TooLoud(char* questionableText, int threshold = 50);

	// returns true if the given text contains >=threshold consecutive ugly symbols
	static bool TooUgly(char* questionableText, int threshold = 3);

	// returns a mixed-case version of the loudText
	static char* MakeQuiet(char* loudText);

	// returns the original text with consecutive symbols removed
	static char* MakePretty(char* uglyText, int threshold = 3);

	// calls MakeQuiet and MakePretty using default thresholds and returns the result.
	//  note: only calles MakeQuiet if it's TooLoud, but always calls MakePretty.
	static char* SuperClean(char* loudUglyText);

	// inserts spaces into the given text for any span of threshold characters
	//  without a space (this is useful for ensuring that an item title will wrap
	//  inside a table cell)
	static char* Delimit(char* runOnText, int threshold = 20);

	// returns true if the given text contains vulgar words.
	//  also returns the vulgar word and the class of vulgar word if asked for by the caller.
	//  Note: caller must supply memory for badWord
	enum
	{	RESERVED = 0x0001,
	    VULGAR   = 0x0002, 
		HOMEPAGE = 0x0004
	};
	static bool TooVulgar(char *questionableText,
					int *badWordClass  = 0, char *badWord = NULL, int *badWordCombination = NULL);

	// replaces special HTML characters with their &; counterparts
	static char* StripHTML(char* dangerousText);

	// removes html tags from text; i.e. anything between < and >
	static char* RemoveHTMLTag(char *descriptionText);

	// Do not use the Scramble function on any HTML code.
	// The HTML won't parse properly after you do (that's
	// the idea!).
	static char *Scramble(const char *to_scramble);

	// Round any time_t value to the nearest week
	static time_t RoundToWeek(time_t theTime);

	static void GetDateAndTime(time_t tTime, char *pStrDate, char *pStrTime);
	static void GetDateTime(time_t tTime, char *pStrTime);

	// Return true if given time is today.
	static bool IsToday(long calcDate);

	// Strip out the given parameter from an ISAPI URL
	static char* RemoveISAPIParameter(const char* URL, const char* parameter);

	// Draw out random HTML, making sure the tags are 'safe'.
	static const char *FindEndOfTag(const char *pString);
	static const char *DrawSafeHTML(const char *pString);

	// Copied from clseBayApp
	static char *ChangeHTMLQuoteToQuote(char *pString);

	// Remove the not allowed characters
	static char *CleanUpUserId(char *pUserId);

	// 06/01/99 petra
	// Check for invalid characters in userId
	static bool HasSpecialCharacters(char *pUserId);

	// Change '%' to '%25'
	static char *DrawSafeEmail(char* pEmail);

	// Sends an email. 
	static int SendEmail(char *pTo, char *pFrom, char *pSubject, char *pMsg, 
						 char **pvCC = NULL, char **pvBCC = NULL);

	// Convert the string to lowercase and return it
	static char* StringLower(char* s);

	// Convert the string to lowercase and return it
	static char* StringUpper(char* s);

	static int CountIsoInTitle(char *pTitle);

	// Given a string, returns a copy that has only digits left.
	// Caller is responsible for deleting the returned char*
	static char* StripEverythingButDigits(const char *pStr);

	// Given a string, returns a lowercase copy that has all non-alphanumerics stripped out.
	// Caller is responsible for deleting the returned char*
	static char* StripNonAlphaNumsAndMakeLower(const char *pStr);

	// Given two strings, do a case-insensitive compare that first strips out all non-Alphanumerics
	static int SmartAlphaNumStringCompare(const char *pStr1, const char *pStr2);


#if 0 // mlh 8/27/98 moved to clseBayCookie
   // pBuffer must be 16 characters long.
	static void BuildAdultCookie(unsigned char *pBuffer);
#endif

	static int32_t FixByteOrder32(int32_t target);

	// Draw with backslashes before quites.
	static void DrawWithEscapedQuotes(ostream *pStream, const char *pValue);


	// Replace all unserscores with spaces
	static char *ReplaceUnderscoresWithSpaces(char *pString);

	// Replace all spaces with underscores
	static char *ReplaceSpacesWithUnderscores(char *pString);

	// Remove delimiting quotes, if any
	static char *RemoveDelimitingQuotes(char *pString);

	// convert the escape chars in pSrc to ASCII chars and save the
	// result in pDes. pDes must be one char longer than pSrc
	static void ExcapeToAscii(const char* pSrc, char* pDes, int DesLength);

	// Given a time_t, compares the time_t to a given date/time.
	// Returns -1 if time_t is BEFORE given date/time.
	// Returns +1 if time_t is AFTER given date/time.
	// Returns 0 if time_t is EXACTLY the given date/time.
	//  Note: month ranges from 1-12, and day ranges from 1-31
	static int CompareTimeToGivenDate(time_t t, int month, int day, int year, int hour, int minute, int sec);

	// Just creates a time_t out of a date, but it makes the code that calls it much more readable.
	static time_t MakeADate(int month, int day, int year, int hour, int minute, int sec);

	// returns the original text with all '&' characters replaced with "%26"
	static char* MakeSafeString(char* pString);

	// check whether the first "Length" bytes of the string are digits
	static bool AreDigits(const char* pZip, int Length);

	// Set hr, min, sec to 0 for a date.
	static void TimeToMidnight(time_t *t);

	// Constructs and returns a character string composed of words/phrases 
	// contained in the given vector, separated by the specified separator string
	// (e.g., if pvWords = ["word1", "word2", "word3"] and pSeparator = "\n",
	// then the returned string would be "word1\nword2\nword3")
	static char *StringVectorToString(vector<char *> *pvWords, char *pSeparator);

	// Get domain token
	static const char* GetDomainToken(int Site, int Partner);
	// TODO - remove?
	// Get the html site path token
	const char* GetPathToken(int siteId, int partnerId);
//  TODO - remove?
//	static const char* GetCGIToken(int Site, int Partner);
//	static const char* GetHTMLToken(int Site, int Partner);

	static void GetSiteIDAndPartnerID(const char* pServerName, 
										const char* pURL, 
										int& SiteId, 
										int& PartnerId);

private:
	static char HexToAscii(const char* pSrc);
};



#endif // CLSUTILITIES_INCLUDED
