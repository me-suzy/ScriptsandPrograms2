/*	$Id: clsUtilities.cpp,v 1.17.118.4 1999/08/05 18:59:19 nsacco Exp $	*/
//
//	File:	clsUtilities.cpp
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
//				- 10/14/97	Poon	- Created
//				- 02/19/97	Charles - added CleanUpUserId()
//				- 01/27/99	mila	- modified DrawSafeEmail to replace '&'
//									  as well;  added method MakeSafeString()
//				- 04/22/99  Anoop	- Added new method SendEmail(...)
//		        - 06/01/99  petra  	- added HasSpecialCharacters() for userId check
//				- 07/22/99	petra	- change date/time formatting routines to use clseBayTimeWidget
//
//
#include "eBayKernel.h"
#include <stdlib.h>
#include <string.h>
#include <stdio.h>
#include <ctype.h>
#include <time.h>
#include <ctype.h>
#include "clseBayTimeWidget.h"		// petra

extern "C"
{
#include "md5.h"
}

#include "clsEnvironment.h"

#ifdef _MSC_VER
#define CASEBLIND_STRCMP(x,y) stricmp(x, y)
#else
#define CASEBLIND_STRCMP(x,y) strcasecmp(x, y)
#endif


// nsacco 06/01/99
// the domains
#define DOMAIN_EBAY_MAIN ".ebay.com"
#define DOMAIN_EBAY_US ".ebay.com"
#define DOMAIN_EBAY_CA ".canada.ebay.com"	// TODO fix
#define DOMAIN_EBAY_UK ".ebay.co.uk"
#define DOMAIN_EBAY_DE ".de.ebay.com"	// TODO - fix
#define DOMAIN_EBAY_AU ".ebay.com.au"
#define DOMAIN_EBAY_MAIN_AOL ".ebay.aol.com"
#define DOMAIN_EBAY_US_AOL ".ebay.aol.com"

// this is for qa
#define DOMAIN_EBAY_QA "-qa.corp.ebay.com"
#define DOMAIN_EBAY_QA_AOL "-aol.corp.ebay.com"


int allspace(const char *s)
{
    const char *p;
    for (p = s; *p; p++)
        if (! isspace((int)(*p)))
            return 0; /* not a space */
    return 1; /* all space */
}


// returns true if the given text contains more than threshold% captial letters
bool clsUtilities::TooLoud(char* questionableText, int threshold /* = 50 */)
{
	int caps, letters;
	int i, length;

	// safety
	if (!questionableText) return false;

	length = strlen(questionableText);

	// count the number of caps versus the number of letters
	caps = letters = 0;
	for (i=0; i<length; i++)
	{
		if (isupper(questionableText[i]))
		{
			caps++;
			letters++;
			continue;
		}
		if (IseBayAlpha(questionableText[i]))
		{
			letters++;
			continue;
		}
	}

	// safety
	if (letters==0) letters++;

	// it's considered too many caps if more than 60% of the letters are caps
	if ((caps*100/letters) >= threshold) return true;

	return false;
}

// returns true if the given text contains >=threshold consecutive ugly symbols
bool clsUtilities::TooUgly(char* questionableText, int threshold /* = 3 */)
{
	char uglies[] = "!@#$%^&*_-+=/\\|<>~";

	int numConsecutiveUglies;
	int i, length;

	// safety
	if (!questionableText) return false;

	length = strlen(questionableText);

	// count the number of consecutive uglies
	numConsecutiveUglies = 0;
	for (i=0; i<length; i++)
	{
		if (strchr(uglies, questionableText[i]))
		{
			numConsecutiveUglies++;		// found another, so increment
			if (numConsecutiveUglies >= threshold) return true;
		}
		else
		{
			numConsecutiveUglies = 0;	// found a good one, so reset
		}

	}

	return false;	// passed the test
}

// returns a mixed-case version of the loudText
char* clsUtilities::MakeQuiet(char* loudText)
{
	char delimiters[] = " .,;:!@#$%^&*()[]{}_-+=/\\|<>~";

	char *quietText;
	char *spareCopy;
	char *token;
	char *p;
	int i, length, tokenLength;

	// safety
	if (!loudText) return NULL;

	length = strlen(loudText);

	// make two copies of the original
	quietText = new char[length+1];
	spareCopy = new char[length+1];
	strcpy(quietText, loudText);
	strcpy(spareCopy, loudText);

	// find words and mix-case them
	token = strtok(spareCopy, delimiters);
	while (token)
	{
		// get pointer to the word
		p = strstr(quietText, token);

		// convert all letters of the word (except first letter) to lowercase
		tokenLength = strlen(token);
		for (i=1; i<tokenLength; i++) p[i]=tolower(p[i]);	
		
		// find next word
		token = strtok(NULL, delimiters);
	}

	// don't need this anymore
	delete [] spareCopy;

	return quietText;
}

// returns the original text with consecutive symbols removed
char* clsUtilities::MakePretty(char* uglyText, int threshold /* = 3 */)
{
	char uglies[] = "!@#$%^&*_-+=/\\|<>~";

	int numConsecutiveUglies;
	int i, j, length;

	char *prettyText;

	// safety
	if (!uglyText) return NULL;

	length = strlen(uglyText);

	// pretty version will be same length or shorter
	prettyText = new char[length+1];

	// count the number of consecutive uglies
	numConsecutiveUglies = 0;
	j = 0;
	for (i=0; i<length; i++)
	{
		if (strchr(uglies, uglyText[i]))
		{
			numConsecutiveUglies++;		// found another, so increment
			if (numConsecutiveUglies >= threshold) continue;	// skip
		}
		else
		{
			numConsecutiveUglies = 0;	// found a good one, so reset
		}

		// copy good chars one at a time
		prettyText[j++] = uglyText[i];

	}

	// finish it off
	prettyText[j++] = '\0';

	return prettyText;
}


// calls MakeQuiet and MakePretty using default thresholds and returns the result.
//  note: only calles MakeQuiet if it's TooLoud, but always calls MakePretty.
char* clsUtilities::SuperClean(char* loudUglyText)
{
	char *quietText = NULL;
	char *superCleanText = NULL;
	bool madeQuiet = false;

	// safety
	if (!loudUglyText) return NULL;

	// MakeQuiet
	if (clsUtilities::TooLoud(loudUglyText))
	{
		quietText = clsUtilities::MakeQuiet(loudUglyText);
		madeQuiet = true;
	}
	else
	{
		quietText = loudUglyText;
		madeQuiet = false;
	}

	// safety
	if (!quietText) return NULL;

	// MakePretty
	superCleanText = clsUtilities::MakePretty(quietText);

	// don't need this one anymore
	if (madeQuiet) delete [] quietText;

	return superCleanText;
}

// inserts spaces into the given text for any span of threshold characters
//  without a space (this is useful for ensuring that an item title will wrap
//  inside a table cell)
char* clsUtilities::Delimit(char* runOnText, int threshold /* = 20 */)
{
	int numConsecutiveNonSpaces;
	int i, j, length;

	char *delimitedText;

	// safety
	if (!runOnText) return NULL;

	length = strlen(runOnText);

	// pretty version will be at the very most twice as long
	delimitedText = new char[length*2+1];

	// count the number of consecutive uglies
	numConsecutiveNonSpaces = 0;
	j = 0;
	for (i=0; i<length; i++)
	{
		if (runOnText[i] != ' ')
		{
			numConsecutiveNonSpaces++;
			if (numConsecutiveNonSpaces >= threshold)
			{
				delimitedText[j++] = ' ';		// insert a space
				numConsecutiveNonSpaces = 0;	// reset
			}
		}
		else
		{
			numConsecutiveNonSpaces = 0;	// found a space, so reset
		}

		// copy chars one at a time
		delimitedText[j++] = runOnText[i];

	}

	// finish it off
	delimitedText[j++] = '\0';

	return delimitedText;
}

// returns true if the given text contains vulgar words.
//  also returns the vulgar word and the class of vulgar word if asked for by the caller.
//  Note: caller must supply memory for badWord
bool clsUtilities::TooVulgar(char *questionableText,
							 int *badWordClass /* = 0 */, char *badWord /* = NULL */, 
							 int *badWordCombination)
{
	char*	copiedText;
	int		length, i, j;
	bool	bad = false;

	// the dirty words. eventually we may want to store these words
	//  somewhere in the database for easy updating.
	// took out shit, tits and twat, boobs "kike", 
	char* vulgarWords[] = {"asshole", 
							"bastard", "bitch", "butthole", "blowjob", 
							"clitor", "cocksucker", "cunt", 
							"deepthroat", "dickface", "dickhead",
							"faggot", "fuck", 
							"hustler", 
							"nigger", "negro", 
							"orgasm", "orgy",
							"penis", "penthouse", "playgirl", 
							"rectum", 
							"scrotum", "scumbag", "slut", "smegma", 
							"titties",
							"vulva", "vagina", 
							"whitetrash", "whore", "pussy", "dickwad"};
	char* reservedWords[] = {"ebay"};
	char* homepageWords[] = {"anarch", "naughty", "steal", "stole", "feedback", "stun", "gun", "sex", "rich", "money", "grenade", "pistol", "nudity", "insane", "success", "idiot", "snipe", "pathetic", "bid", "free", "auction", "make", "crossbow", "spy", "rifle", "dirty", "bann", "warning", "hacker", "credit", "explosive", "nude", "slave", "detective", "breast", "swastika", "nazi", "sleep", "clinton", "monica", "lewinsky", "cigar", "president", "hillary", "chelsea", "impeach", "starr", "dress"};

	// safety
	if (!questionableText) return false;

	// make a lowercase copy, and copy only alphas, so that something like
	// "F-U-C-K" won't make it through
	length = strlen(questionableText);
	copiedText = new char[length+1];
	j=0;
	for (i=0; i < length; i++)
		if (IseBayAlpha(questionableText[i]))
			copiedText[j++] = tolower(questionableText[i]);
	copiedText[j++] = '\0';

	//to be safe
	if (badWordCombination) *badWordCombination = 0;

	// check for vulgar words first because they are the worst
	if (!bad || badWordCombination)
	{
		for (i=0; i < (sizeof(vulgarWords) / sizeof(char *)); i++)
		{
			if (strstr(copiedText, vulgarWords[i]))
			{
				if (badWord) strcpy(badWord, vulgarWords[i]);
				if (badWordClass) *badWordClass = clsUtilities::VULGAR;
				if (badWordCombination) *badWordCombination |= clsUtilities::VULGAR;

				bad = true;
				break;
			}
		}
	}


	// check for forbidden homepage words next
	if (!bad || badWordCombination)
	{
		for (i=0; i < (sizeof(homepageWords) / sizeof(char *)); i++)
		{
			if (strstr(copiedText, homepageWords[i]))
			{
				if (badWord) strcpy(badWord, homepageWords[i]);
				if (badWordClass) *badWordClass = clsUtilities::HOMEPAGE;
				if (badWordCombination) *badWordCombination |= clsUtilities::HOMEPAGE;

				bad = true;
				break;
			}
		}
	}

	// check for reserved words last
	if (!bad || badWordCombination)
	{
		for (i=0; i < (sizeof(reservedWords) / sizeof(char *)); i++)
		{
			if (strstr(copiedText, reservedWords[i]))
			{
				if (badWord) strcpy(badWord, reservedWords[i]);
				if (badWordClass) *badWordClass = clsUtilities::RESERVED;
				if (badWordCombination) *badWordCombination |= clsUtilities::RESERVED;

				bad = true;
				break;
			}
		}
	}



	// don't need the copy
	delete [] copiedText;

	return bad;	
}

// replaces special HTML characters with their &; counterparts
//  note: this was essentially copied from clseBayApp::CleanUpTitle()
char *clsUtilities::StripHTML(char *dangerousText)
{
	char		*pIt;
	char		*pItOut;
	int			sizeCount;
	int			newSize;
	char		*safeText;

	// First, count the evil special characters
	sizeCount	= 0;

	for (pIt	= dangerousText;
		 *pIt	!= '\0';
		 pIt++)
	{
		switch (*pIt)
		{
		case '\"': 
			sizeCount += 4; 
			break;
		case '<': case '>': 
			sizeCount += 4; 
			break;
		case '&': case '%': case '\'':
			sizeCount += 4; 
			break;
		}
	}

	// The new size is the origional size + sizeCount
	newSize	= strlen(dangerousText) + sizeCount + 1;

	safeText	= new char[newSize];

	// Now, go through and replace those characters
	for (pIt	= dangerousText,
		 pItOut	= safeText;
		 *pIt	!= '\0';
		 pIt++)
	{
		 switch (*pIt)
		 {
		 case '\"':
			 memcpy(pItOut, "&#34;", 5);
			 pItOut += 5;
			 continue;
		 case '<':
			 memcpy(pItOut, "&#60;", 5);
			 pItOut += 5;
			 continue;
		 case '>':
			 memcpy(pItOut, "&#62;", 5);
			 pItOut += 5;
			 continue;
		 case '&':
			 // Allow # escape sequences.
			 if (*(pIt + 1) == '#' && isdigit(*(pIt + 2)) &&
				 isdigit(*(pIt + 3)) && (*(pIt + 4) == ';' || 
				 (isdigit(*(pIt + 4)) && *(pIt + 5) == ';')))
			 {
				 *pItOut = *pIt;
				 ++pItOut;
				 continue;
			 }
			 memcpy(pItOut, "&#38;", 5);
			 pItOut += 5;
			 continue;
		 case '%':
			 memcpy(pItOut, "&#37;", 5);
			 pItOut += 5;
			 continue;
		 case '\'':
			 memcpy(pItOut, "&#39;", 5);
			 pItOut += 5;
			 continue;
		 default:
			 *pItOut = *pIt;
			 ++pItOut;
			 continue;
		 }
	}

	*pItOut	= '\0';

	return	safeText;
}

// remove html tags from text; basically anything between < and >
// replaces special HTML characters with their &; counterparts
//  note: this was essentially copied from clseBayApp::CleanUpTitle()
char *clsUtilities::RemoveHTMLTag(char *descriptionText)
{
	char		*pIt;
	char		*pItOut;
	int			newSize;
	char		*safeText;
	bool		intag;

	// safety
	if (!descriptionText) return NULL;

	// The new size is at least the original size since we remove stuff 
	newSize	= strlen(descriptionText) + 1;
	intag = false;
	safeText	= new char[newSize];

	// get rid of anything within <>
	for (pIt	= descriptionText,
		 pItOut	= safeText;
		 *pIt	!= '\0';
		 pIt++)
	{
		 switch (*pIt)
		 {
		 case '<':
			 intag = true;
			 continue;
		 case '>':
			 intag = false;
			 continue;
		 default:
			 if (!intag)
			 {
			 *pItOut = *pIt;
			 ++pItOut;
			 }
			 continue;
		 }
	 }
	*pItOut	= '\0';

	return	safeText;
}

char *clsUtilities::Scramble(const char *to_scramble)
{
	char *pReturnString;
	char *pOutString;
	const char *pInString;
	char c;
	int length;

	// We have no translations lengths longer than 6 at the moment.
	length = strlen(to_scramble) * 6 + 1;

	pReturnString = new char [length];
	srand((long) pReturnString);

	pOutString = pReturnString;
	pInString = to_scramble;

	while ((c = *pInString) != '\0')
	{
		++pInString;
		if (c >= 'a' && c <= 'z')
		{
			if (rand() & 1)
			{
				*pOutString = c;
				++pOutString;
				continue;
			}

			sprintf(pOutString, "&#%d;", (c - 'a') + 97);
			pOutString += 5;
			if (c >= 'd') // 'd' is 100, so we're into three digits then.
				++pOutString;
			continue;
		}

		if (c >= 'A' && c <= 'Z')
		{
			if (rand() & 1)
			{
				*pOutString = c;
				++pOutString;
				continue;
			}

			sprintf(pOutString, "&#%d;", (c - 'A') + 65);
			pOutString += 5;
			continue;
		}

		if (c >= '0' && c <= '9')
		{
			if (rand() & 1)
			{
				*pOutString = c;
				++pOutString;
				continue;
			}

			sprintf(pOutString, "&#%d;", (c - '0') + 48);
			pOutString += 5;
			continue;
		}

		switch (c)
		{
		case '@':
			strncpy(pOutString, "&#64;", 5);
			pOutString += 5;
			break;
		case '.':
			strncpy(pOutString, "&#46;", 5);
			pOutString += 5;
			break;
		default:
			*pOutString = c;
			++pOutString;
			break;
		}
	}

	*pOutString = '\0';
	return pReturnString;
}

//
// RoundToWeek
//	Round a time_t value to the beginning of the week
//
time_t clsUtilities::RoundToWeek(time_t theTime)
{
	struct tm		*pTheTimeTM;
	struct tm		theWeekTimeTM;
	time_t			theWeekTime;

	//
	//	First, we get the time as a tm struct
	//
	pTheTimeTM	= localtime(&theTime);
	memcpy(&theWeekTimeTM, pTheTimeTM, sizeof(theWeekTimeTM));

	//
	//	So, the time_t value for the beginning of the week
	//	is the origional value, minus :
	//	- Seconds
	//	- Minutes * 60
	//	- Hours * 60 * 60
	//	- Days since Sunday * 60 * 60 * 24
	//
	theWeekTime	= theTime -
				  (pTheTimeTM->tm_sec)				-
				  (pTheTimeTM->tm_min * 60)			-
				  (pTheTimeTM->tm_hour * 60 * 60)	-
				  (pTheTimeTM->tm_wday * 60 * 60 * 24);

	return theWeekTime;
}

// petra changed to use clseBayTimeWidget
void clsUtilities::GetDateAndTime(time_t tTime, char *pStrDate, char *pStrTime)
{
// petra	struct tm*	pTime;
// petra
// petra	pTime = localtime(&tTime);
// petra
// petra	if (pTime)
// petra	{
// petra		sprintf(pStrDate, "%2.2d/%2.2d/%2.2d", 
// petra			pTime->tm_mon+1, 
// petra			pTime->tm_mday,
// petra			pTime->tm_year);
// petra	
// petra		sprintf(pStrTime, "%2.2d:%2.2d:%2.2d %s", 
// petra			pTime->tm_hour, 
// petra			pTime->tm_min,
// petra			pTime->tm_sec,
// petra			pTime->tm_isdst ? "PDT" : "PST");
// petra	}
// petra	else
// petra	{
// petra		sprintf(pStrDate, "*Error*");
// petra		sprintf(pStrTime, "*Error*");
// petra	}

	clseBayTimeWidget timeWidget (gApp->GetMarketPlaces()->GetCurrentMarketPlace(),		// petra
									1, -1, tTime);	// petra
	timeWidget.EmitString (pStrDate);							// petra
	timeWidget.SetDateTimeFormat (-1, 2);						// petra
	timeWidget.EmitString (pStrTime);							// petra

}

// petra changed to use clseBayTimeWidget
void clsUtilities::GetDateTime(time_t tTime, char *pStrTime)
{
	clseBayTimeWidget timeWidget (gApp->GetMarketPlaces()->GetCurrentMarketPlace(),		// petra
									1, 2, tTime);	// petra
	timeWidget.EmitString (pStrTime);							// petra

// petra	struct tm*	pTime;
// petra
// petra	pTime = localtime(&tTime);
// petra
// petra	if (pTime)
// petra	{
// petra	sprintf(pStrTime, "%2.2d/%2.2d/%2.2d %2.2d:%2.2d:%2.2d %s", 
// petra		pTime->tm_mon+1, 
// petra		pTime->tm_mday,
// petra		pTime->tm_year,
// petra		pTime->tm_hour, 
// petra		pTime->tm_min,
// petra		pTime->tm_sec,
// petra		pTime->tm_isdst ? "PDT" : "PST");
// petra	}
// petra	else
// petra	{
// petra		sprintf(pStrTime, "*Error*");
// petra	}
}

bool clsUtilities::IsToday(long calcDate)
{
	// actually must know whether the date is today's date
	// so can't compare longs by itself.
	time_t nowTime;
	struct tm	*pTime;
	int nowday;
	int nowmonth;
	int nowyear;

	nowTime		= time(0);
	pTime	= localtime(&nowTime);
	// save the values
	nowday = pTime->tm_mday;
	nowmonth = pTime->tm_mon;
	nowyear = pTime->tm_year;

	pTime	= localtime(&calcDate);

	// same day, month and year.
	return ((pTime->tm_year == nowyear) &&
           (pTime->tm_mon == nowmonth) &&
           (pTime->tm_mday == nowday));
}

// Strip out the given parameter from an ISAPI URL
char* clsUtilities::RemoveISAPIParameter(const char* URL, const char* parameter)
{
	char target[255];
	char *p, *q;
	char *newURL;

	// safety
	if ((!URL) || (!parameter)) return NULL;

	// make a copy
	newURL = new char[strlen(URL)+1];
	strcpy(newURL, URL);

	// create target string as "&parameter="
	sprintf(target, "&%s=", parameter);

	// find the target string
	p = strstr(newURL, target);

	// parameter not found, so just return newURL
	if (!p) return newURL;

	// ok, we found it, so let's chop it off upto the beginning of the parameter
	p[0] = '\0';

	// now find the rest of the string following the parameter
	p++;
	q = strstr(p, "&");

	// if no more parameters, let's just return what we have
	if (!q) return newURL;

	// add the rest
	strcat(newURL, q);

	return newURL;

}

// These need to be sorted.
static const char *sDoubleTags[] =
{
	"A",
	"ADDRESS",
	"B",
	"BIG",
	"BLINK",
	"BLOCKQUOTE",
	"CAPTION",
	"CENTER",
	"CITE",
	"CODE",
	"COL",
	"COLGROUP",
	"COMMENT",
	"DFN",
	"DIR",
	"DIV",
	"DL",
	"EM",
	"FONT",
	"FORM",
	"H1",
	"H2",
	"H3",
	"H4",
	"H5",
	"H6",
	"I",
	"INPUT",
	"KBD",
	"LI",
	"LINK",
	"LISTING",
	"MENU",
	"NOBR",
	"OL",
	"OPTION",
	"PLAINTEXT",
	"PRE",
	"S",
	"SAMP",
	"SELECT",
	"SMALL",
	"STRIKE",
	"STRONG",
	"SUB",
	"SUP",
	"TABLE",
	"TBODY",
	"TD",
	"TEXTAREA",
	"TFOOT",
	"TH",
	"THEAD",
	"TR",
	"TT",
	"U",
	"UL",
	"VAR",
	"XMP"
};

static const int sNumTags = sizeof (sDoubleTags) / sizeof (const char *);

static const char *sForbiddenTags[] =
{
	"BASE",
	"BGSOUND",
	"BODY",
	"EMBED",
	"FRAME",
	"FRAMESET",
	"HEAD",
	"HTML",
	"ISINDEX",
	"LINK",
	"MARQUEE",
	"META",
	"NEXTID",
	"NOFRAMES",
	"SCRIPT",
	"TITLE"
};

static const int sNumForbiddenTags = sizeof (sForbiddenTags) / sizeof (const char *);

static const char *sTableTags[] =
{
	"CAPTION",
	"TBODY",
	"TD",
	"THEAD",
	"TFOOT",
	"TH",
	"TR"
};

static const int sNumTableTags = sizeof (sTableTags) / sizeof (const char *);

static int sCompareStrings(const char *ppS1, const char *ppS2)
{
	return CASEBLIND_STRCMP(ppS1, ppS2) < 0;
}


// Assumes the beginning of the string is _not_ being passed, and that this
// has been validated as a tag.

// The end of a tag is found thus:
// If you see the pattern: name = "*, ignore everything until a " is found.
// Otherwise, just find a >
// Return NULL if the end of the string is reached before '>' is found.
const char *clsUtilities::FindEndOfTag(const char *pString)
{
	bool sawAttributeName;
	bool sawEqualSign;
	bool openQuote;

	const char *pWalker;

	sawAttributeName = sawEqualSign = openQuote = false;

	pWalker = pString;

	while (*pWalker)
	{
		if (*pWalker == '>' && !openQuote)
			return pWalker;

		if (isspace(*pWalker))
		{
			++pWalker;
			continue;
		}

		if (openQuote)
		{
			if (*pWalker == '"')
				openQuote = false;

			++pWalker;
			continue;
		}

		if (sawAttributeName && (*pWalker == '='))
		{
			if (sawEqualSign)
			{
				sawEqualSign = sawAttributeName = false;
			}
			else
				sawEqualSign = true;
			++pWalker;
			continue;
		}

		if (sawEqualSign && (*pWalker == '"'))
		{
			openQuote = true;
			++pWalker;
			continue;
		}
		
		if (isalnum(*pWalker))
		{
			while (*++pWalker && isalnum(*pWalker)) ;
			sawAttributeName = true;
			sawEqualSign = false;
			--pWalker; // We went one too far.
		}
		else
		{
			sawAttributeName = false;
			sawEqualSign = false;
		}

		++pWalker;
		continue;
	}

	return NULL;
}

// Draws 'safe' html -- that is, it contains no forbidden tags, doesn't close off
// any tags it didn't open, and closes off the tags it did open.
// A couple of special exceptions for tables are made --
// --Table tags aren't closed off for nesting problems.
// --Tags that belong 'in' tables, such as TD and TR are not allowed outside of them.

// This code has one known 'fail' point, which is an oddity in the HTML rather than
// a failure of the parsing --
//
// If the text appears in a narrow strip to the left or right of the description,
// the user has set a 'flow' attribute in their table. The only way I've found
// to correct this is to change from <blockquote> (which we are currently using)
// to
// "<TABLE BORDER=\"0\" CELLSPACING=\"0\" CELLPADDING=\"15\" COLS=\"1\" WIDTH=\"100%\"><TR><TD>\n"
// This will make it look _slightly_ different as far as width, but should be okay
// in all cases.
// (To close it, use "\n</TD></TR></TABLE>")
//
const char *clsUtilities::DrawSafeHTML(const char *pString)
{
	int distance;
	int isEnding;
	int isInTable;
	char tagBuffer[64];
	char *pBufferEnd;
	const char *pTagEnd;
	const char *pAt;
	char *pOut;
	char *pOutPosition;
	
	vector<const char *> vOpenTags;
	vector<const char *>::reverse_iterator j;
	vector<const char *>::reverse_iterator k;
	const char **ppTable;
	const char **ppI;
	int numTablesSkipped;

	isInTable = 0;

	ppTable = lower_bound(sDoubleTags + 0, sDoubleTags + sNumTags,
		"TABLE", sCompareStrings);

	pOut = new char [strlen(pString) * 3 + 1];
	if (!pOut)
		return NULL;

	pOut[0] = '\0';
	pOutPosition = pOut;

	while ((pAt = strchr(pString, '<')) != NULL)
	{
		distance = (int) (pAt - pString);
		memcpy(pOutPosition, pString, distance);
		pOutPosition += distance;
		//pStream->write(pString, distance);

		if (*(pAt + 1) && *(pAt + 1) == '/')
		{
			isEnding = 1;
			strncpy(tagBuffer, pAt + 2, 63);
		}
		else
		{
			strncpy(tagBuffer, pAt + 1, 63);
			isEnding = 0;
		}

		pBufferEnd = tagBuffer;
		while (*pBufferEnd)
		{
			if (!isalnum(*pBufferEnd))
				*pBufferEnd = '\0';
			else
				++pBufferEnd;
		}

		if (isEnding)
		{
			for (j = vOpenTags.rbegin(); j != vOpenTags.rend(); ++j)
			{
				if (!CASEBLIND_STRCMP(tagBuffer, *j))
					break;
			}

			// Close off all tags seen after this one -- no nesting.
			if (j != vOpenTags.rend())
			{
				if (!CASEBLIND_STRCMP(tagBuffer, "TABLE"))
					--isInTable;

				k = j;
				numTablesSkipped = 0;
				for (j = vOpenTags.rbegin(); j != k; ++j)
				{
					if (*j == *ppTable) // Yes, compare the pointers.
						++numTablesSkipped;
					else
					{
						memcpy(pOutPosition, "</", 2);
						pOutPosition += 2;
						memcpy(pOutPosition, *j, strlen(*j));
						pOutPosition += strlen(*j);
						*pOutPosition = '>';
						++pOutPosition;
						//*pStream << "</" << *j << ">";
					}

					vOpenTags.pop_back();
				}
				memcpy(pOutPosition, "</", 2);
				pOutPosition += 2;
				memcpy(pOutPosition, *k, strlen(*k));
				pOutPosition += strlen(*k);
				*pOutPosition = '>';
				++pOutPosition;
				//*pStream << "</" << *k << ">";
				vOpenTags.pop_back();

				while (numTablesSkipped--)
					vOpenTags.push_back(*ppTable);

				pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 2);
				if (!pTagEnd)
					pTagEnd = pString + strlen(pString) - 1;

				pString = pTagEnd + 1;
				continue;
			}
			else if (binary_search(sDoubleTags + 0, sDoubleTags + sNumTags,
				tagBuffer, sCompareStrings) || // A valid closing tag, but not opened.
				binary_search(sForbiddenTags + 0, sForbiddenTags + sNumForbiddenTags,
				tagBuffer, sCompareStrings)) // A forbidden closing tag.
			{
				// Skip the tag!
				pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 2);
				if (!pTagEnd)
					pTagEnd = pString + strlen(pString) - 1; // Take that, foul HTML.

				pString = pTagEnd + 1;
				continue;
			}
			else // A closing tag, but none we know, so we'll put it out.
			{
				pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 2);
				if (pTagEnd != NULL)
				{
					memcpy(pOutPosition, pAt, (int) (pTagEnd - pAt));
					pOutPosition += (int) (pTagEnd - pAt);
					//pStream->write(pAt, (int) (pTagEnd - pAt));
					pString = pTagEnd;
				}
				else
				{
					memcpy(pOutPosition, pAt, strlen(pAt));
					pOutPosition += strlen(pAt);
					*pOutPosition = '>';
					++pOutPosition;
					//*pStream << pAt << ">";
					pString = pString + strlen(pString);
				}

				continue;
			}
		}

		// A forbidden tag. Nasty user! Skip it.
		if (binary_search(sForbiddenTags + 0, sForbiddenTags + sNumForbiddenTags,
			tagBuffer, sCompareStrings))
		{
			pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 1);
			if (pTagEnd == NULL)
				pString = pString + strlen(pString) - 1;

			pString = pTagEnd + 1;
			continue;
		}

		// A table tag, and we're not in a table.
		if (!isInTable && binary_search(sTableTags + 0, sTableTags + sNumTableTags,
			tagBuffer, sCompareStrings))
		{
			pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 1);
			if (pTagEnd == NULL)
				pString = pString + strlen(pString) - 1;

			pString = pTagEnd + 1;
			continue;
		}

		ppI = lower_bound(sDoubleTags + 0, sDoubleTags + sNumTags,
			tagBuffer, sCompareStrings);

		if (!CASEBLIND_STRCMP(tagBuffer, *ppI))
		{
			vOpenTags.push_back(*ppI);
			if (!CASEBLIND_STRCMP(*ppI, "TABLE"))
				++isInTable;
		}

		pTagEnd = FindEndOfTag(pAt + strlen(tagBuffer) + 1);
		if (pTagEnd != NULL)
		{
			memcpy(pOutPosition, pAt, (int) (pTagEnd - pAt));
			pOutPosition += (int) (pTagEnd - pAt);
			//pStream->write(pAt, (int) (pTagEnd - pAt));
			pString = pTagEnd;
		}
		else
		{
			memcpy(pOutPosition, pAt, strlen(pAt));
			pOutPosition += strlen(pAt);
			//*pStream << pAt << ">";
			pString = pString + strlen(pString);
		}
		continue;
	}

	if (*pString)
	{
		memcpy(pOutPosition, pString, strlen(pString));
		pOutPosition += strlen(pString);
		//*pStream << pString;
	}

	// Clean up any sloppiness.
	for (j = vOpenTags.rbegin(); j != vOpenTags.rend(); ++j)
	{
		memcpy(pOutPosition, "</", 2);
		pOutPosition += 2;
		memcpy(pOutPosition, *j, strlen(*j));
		pOutPosition += strlen(*j);
		*pOutPosition = '>';
		++pOutPosition;
		//*pStream << "</" << *j << ">";
	}

	*pOutPosition = '\0';

	vOpenTags.erase(vOpenTags.begin(), vOpenTags.end());

	return pOut;
}

//
// HTMLQuoteToQuote
//
// Does the opposite of what the Cleanup routines do --
// this routine converts &quot; to " in a string.
//
char *clsUtilities::ChangeHTMLQuoteToQuote(char *pString)
{
	char		*pIt;
	char		*pItLast;
	char		*pItOut;
	int			newSize;
	char		*pNewString;

	// We'll just make a new buffer the same size
	// as the old one. We're making the string SHORTER,
	// not longer!
	newSize	= strlen(pString) +
			  1;

	pNewString	= new char[newSize];
	memset(pNewString, 0x00, newSize);

	pIt		= pString;
	pItLast	= pString + strlen(pString);
	pItOut	= pNewString;

	do
	{
		if ((pItLast - pIt) < 6)
			break;

		if (memcmp(pIt, "&quot;", 6) != 0)
		{
			*pItOut	= *pIt;
			pIt++;
			pItOut++;
			continue;
		}

		*pItOut	=	'\"';
		pIt	= pIt + 6;
		pItOut++;
	} while (1==1);

	// Copy the last bit of the input (plus the trailng null)
	strcat(pItOut, pIt);

	// all done. We can't delete the origional string
	// since it belongs to ISAPI (probably)
	return	pNewString;
}


//
// CleanUpUserId
//
// Remove the spaces and the special characters
// not allowed in a User ID
// Return the clean string who has been allocated by
// the method (take care)
//
char *clsUtilities::CleanUpUserId(char *pUserId)
{
	char	*pBuff;
	char	*pBuffCopy;
	int		i = 0, j = 0;
	bool	isEmail;
	char	*acceptableUserIdCharacters = "@*$!-()._";

	// Is there something to test ????
	if( !pUserId || (strlen(pUserId) == 0 ) )
	{
		return((char *)NULL);
	}

	pBuff     = new char[strlen(pUserId) + 1];
	pBuffCopy = new char[strlen(pUserId) + 1];
	strcpy(pBuffCopy,pUserId);
	memset(pBuff,0,strlen(pUserId)+1);

	// @ sign means email
	// this was dilberately coded to be easy to read, as mike and tini
	//  were looking over my shoulder and making funny comments when
	//  typed isEmail = (strchr(pUserId, '@') != NULL)
	if (strchr(pUserId, '@'))
		isEmail = true;
	else
		isEmail = false;

	// this loop just makes a copy of pBuffCopy into pBuff, but skips
	//  spaces, tabs, or any weirdo characters
	j = -1;		// seed
	while(pBuffCopy[++j])
	{
		// skip this character if it's a space or tab
		if ((pBuffCopy[j] == ' ') || (pBuffCopy[j] == '\t'))
			continue;

		// do additional checks if the userid isn't an email address
		if (!isEmail)
		{
			// skip if it's a nonalphanumeric character and doesn't fall into the acceptable characters list
			if ((!IseBayAlnum(pBuffCopy[j])) && (!strchr(acceptableUserIdCharacters, pBuffCopy[j])))
				continue;
		}

		// ok, we've gotten this far, which means the character is
		//  acceptable. let's copy it over.
		pBuff[i] = pBuffCopy[j];
		i++;
	}

	// We don't need this again
	delete [] pBuffCopy;

	// Still there something in the string 
	if(strlen(pBuff) == 0)
	{
		delete [] pBuff;
		return((char *)NULL);
	}

#ifdef _MSC_VER
	// convert to lower case
	strlwr(pBuff);
#endif

	// All Done 
	return	pBuff;

}

//
// HasSpecialCharacters
//
// CleanUpUserId just skips weird characters but never tells the user about
// it. So in order to be able to tell the user at registration time that we're unhappy
// with his/her choice of userId, we call this routine first, and only if this 
// returns true, we do the CleanUpUserId. If not, we still have a chance to
// ask politely for another UserId instead of just silently changing it.
// (Adding this routine instead of changing CleanUpUserId means we don't have to
// worry about any other instances where CleanUpUserId is used)
// 06/01/99 petra
//
bool clsUtilities::HasSpecialCharacters(char *pUserId)
{
	int		j = -1;
	bool	isEmail;
	char	*acceptableUserIdCharacters = "@*$!-()._";

	// Is there something to test ????
	if( !pUserId || (strlen(pUserId) == 0 ) )
	{
		return((char *)NULL);
	}

	// @ sign means email
	if (strchr(pUserId, '@'))
		isEmail = true;
	else
		isEmail = false;

	while(pUserId[++j])
	{
        // do additional checks if the userid isn't an email address
		if (!isEmail)
		{
			// skip if it's a nonalphanumeric character and doesn't fall into the acceptable characters list
			if ((!IseBayAlnum(pUserId[j])) && (!strchr(acceptableUserIdCharacters, pUserId[j])))
				return (true);
		}
	}
	return	(false);
}

// DrawSafeEmail to change '%' in email to '%25', or '&' to '%26'
char* clsUtilities::DrawSafeEmail(char* pEmail)
{
	char*	pNewEmail = NULL;
	int		Count = 0;
	int		i, j;

	if (pEmail)
	{
		// counting how many % in email
		for (i = 0; i < strlen(pEmail); i++)
		{
			if (pEmail[i] == '%' || pEmail[i] == '&')
			{
				Count++;
			}
		}

		if (Count == 0)
		{
			// just copy the string and return cuz there's nothing to replace
			pNewEmail = new char [strlen(pEmail) + 1];
			strcpy(pNewEmail, pEmail);
			return pNewEmail;
		}

		// allocate memory for the new email
		pNewEmail = new char [strlen(pEmail) + 2 * Count + 1];

		// copy to the email, expand it if needed
		for (i = 0, j = 0; i < strlen(pEmail); i++, j++)
		{
			if (pEmail[i] == '%')
			{
				strcpy(pNewEmail+j, "%25");
				j += 2;
			}
			else if (pEmail[i] == '&')
			{
				strcpy(pNewEmail+j, "%26");
				j += 2;
			}
			else
			{
				pNewEmail[j] = pEmail[i];
			}
		}

		// terminate
		pNewEmail[j] = '\0';
	}

	return pNewEmail;
}


int clsUtilities::SendEmail(char *pTo, char *pFrom, char *pSubject, char *pMsg, 
						    char **pvCC, char **pvBCC)
{
	clsMail     *pMail;
	ostrstream  *pMailStream;
	int          retVal;

	pMail	    = new clsMail();
	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream <<	pMsg
				 <<	ends;

	retVal = pMail->Send(pTo, pFrom, pSubject, pvCC, pvBCC);

	delete	pMail;
	
	return  retVal;
}  /* SendEmail */


char* clsUtilities::StringLower(char* s)
{
	int i,j;

	// safety
	if (!s) return NULL;

	j = strlen(s);
	for (i = 0; i < j; i++)
		s[i] = tolower(s[i]);

	return s;
}

char* clsUtilities::StringUpper(char* s)
{
	int i,j;

	// safety
	if (!s) return NULL;

	j = strlen(s);
	for (i = 0; i < j; i++)
		s[i] = toupper(s[i]);

	return s;
}

// find how many iso characters are in pTitle
int clsUtilities::CountIsoInTitle(char *pTitle)
{
	int countingIso = 0;
	int foundIso = 0;
	char		*pI;

	for (pI = pTitle; *pI != '\0'; ++pI)
	{
		if (!countingIso && (*pI != '&'))
			continue;
		switch (countingIso)
		{
		case 0:
			if (*pI != '&')
				continue;
			break;
		case 1:
			if (*pI != '#')
			{
				if (*pI == '&')
					--pI;
				countingIso = 0;
				continue;
			}
			break;
		case 2: case 3: case 4:
			if (!isdigit(*pI))
			{
				if (*pI == '&')
					--pI;
				countingIso = 0;
				continue;
			}
			break;
		case 5:
			if (*pI != ';')
			{
				countingIso = 0;
				if (*pI == '&')
					--pI; // This is the '&', so back up.
				continue;
			}
			++foundIso;
			countingIso = 0;
			continue;
		}
		++countingIso;
	}
	return countingIso;
}

int32_t clsUtilities::FixByteOrder32(int32_t target)
{
	return (((target >> 24) & 0xFF) |
		((target >> 16) & 0xFF) << 8 |
		((target >> 8) & 0xFF) << 16 |
		(target & 0xFF) << 24);
}

void clsUtilities::DrawWithEscapedQuotes(ostream *pStream, const char *pValue)
{
	const char *pQuote;
	const char *pLastDraw;

	pLastDraw = pValue;
	pQuote = pValue - 1; // So that we can add + 1.

	while ((pQuote = strchr(pQuote + 1, '\"')))
	{
		if (pQuote - pLastDraw)
			pStream->write(pLastDraw, pQuote - pLastDraw);
		*pStream << "\\\"";
		
		pLastDraw = pQuote + 1;
	}

	*pStream << pLastDraw;
}


// Convert escape char in pSrc to ASCII. The result is saved in pDes.
// pDes should be at less one char longer than pSrc
void clsUtilities::ExcapeToAscii(const char* pSrc, char* pDes, int DesLength)
{
	int i, j;

	for (i = 0, j = 0; pSrc[j] && i < DesLength-1; i++, j++)
	{
		// replace + with space
		if (pSrc[j] == '+') 
		{
			pDes[i] = ' ';
		}
		else if (pSrc[j] == '%')
		{
			// replace the escape chars
			pDes[i] = HexToAscii(&(pSrc[j+1]));
			j += 2;
		}
		else
		{
			pDes[i] = pSrc[j];
		}
	}

	// put in null
	pDes[i] = 0;
}

// convert hex to ascii
char clsUtilities::HexToAscii(const char* pSrc)
{
	char c;

	c = (pSrc[0] >= 'A' ? ((pSrc[0] & 0xDF) - 'A') + 10 : (pSrc[0] - '0'));
	c *= 16;
	c += (pSrc[1] >= 'A' ? ((pSrc[1] & 0xDF) - 'A') + 10 : (pSrc[1] - '0'));

	return c;
}

char *clsUtilities::ReplaceUnderscoresWithSpaces(char *pString)
{
	char *pTemp;

	// Replace any underscores with spaces
	pTemp = pString;
	while ((pTemp = strchr(pTemp, '_')) != NULL)
	{
		*pTemp = ' ';
	}

	return pString;
}

char *clsUtilities::ReplaceSpacesWithUnderscores(char *pString)
{
	char *pTemp;

	// Replace any underscores with spaces
	pTemp = pString;
	while ((pTemp = strchr(pTemp, ' ')) != NULL)
	{
		*pTemp = '_';
	}

	return pString;
}

char *clsUtilities::RemoveDelimitingQuotes(char *pString)
{
	int		length;

	length = strlen(pString);

	if (length > 1)
	{
		// remove ending quote first
		if (pString[length-1]=='\"')
		{
			pString[length-1]='\0';
		}

		// now remove starting quote
		if (pString[0]=='\"')
		{
			pString++;
		}
	}

	return pString;
}


// Given a string, returns a copy that has only digits left.
// Caller is responsible for deleting the returned char*
char* clsUtilities::StripEverythingButDigits(const char *pStr)
{
	int		i, j, length;
	char	*copiedText;

	length = strlen(pStr);
	copiedText = new char[length+1];
	j=0;
	for (i=0; i < length; i++)
		if (IseBayDigit(pStr[i]))
			copiedText[j++] = pStr[i];
	copiedText[j++] = '\0';

	return copiedText;
}

// Given a string, returns a copy that has all non-alphanumerics stripped out.
// Caller is responsible for deleting the returned char*
char* clsUtilities::StripNonAlphaNumsAndMakeLower(const char *pStr)
{
	int		i, j, length;
	char	*copiedText;

	length = strlen(pStr);
	copiedText = new char[length+1];
	j=0;
	for (i=0; i < length; i++)
		if (IseBayAlnum(pStr[i]))
			copiedText[j++] = tolower(pStr[i]);
	copiedText[j++] = '\0';

	return copiedText;
}


// Given two strings, do a case-insensitive compare that first strips out all non-Alphanumerics
int clsUtilities::SmartAlphaNumStringCompare(const char *pStr1, const char *pStr2)
{
	char *pSmart1;
	char *pSmart2;
	int	comparisonValue;

	// make lowercase copies, and copy only alphanumerics
	pSmart1 = clsUtilities::StripNonAlphaNumsAndMakeLower(pStr1);
	pSmart2 = clsUtilities::StripNonAlphaNumsAndMakeLower(pStr2);

	// do it
	comparisonValue = strcmp(pSmart1, pSmart2);

	// don't need the copies anymore
	delete [] pSmart1;
	delete [] pSmart2;

	// we're done
	return comparisonValue;
}

// Given a time_t, compares the time_t to a given date/time.
// Returns -1 if time_t is BEFORE given date/time.
// Returns +1 if time_t is AFTER given date/time.
// Returns 0 if time_t is EXACTLY the given date/time.
	//  Note: month ranges from 1-12, and day ranges from 1-31
int clsUtilities::CompareTimeToGivenDate(time_t t, int month, int day, int year, int hour, int minute, int sec)
{
	struct tm*      pGivenDateTimeTm;
	time_t			givenDate;
	
	givenDate = time(0);	// just to seed it

	pGivenDateTimeTm = localtime(&givenDate);
	pGivenDateTimeTm->tm_sec = sec;
	pGivenDateTimeTm->tm_min = minute;
	pGivenDateTimeTm->tm_hour = hour;
	pGivenDateTimeTm->tm_mday = day;	
	pGivenDateTimeTm->tm_mon = month - 1;		// Note!
	pGivenDateTimeTm->tm_year = year;

	givenDate= mktime(pGivenDateTimeTm);

	// now do the comparison
	if (t < givenDate) return -1;
	if (t > givenDate) return +1;

	return 0;

}

time_t clsUtilities::MakeADate(int month, int day, int year, int hour, int minute, int sec)
{
	struct tm*      pGivenDateTimeTm;
	time_t			givenDate;
	
	givenDate = time(0);	// just to seed it

	pGivenDateTimeTm = localtime(&givenDate);
	pGivenDateTimeTm->tm_sec = sec;
	pGivenDateTimeTm->tm_min = minute;
	pGivenDateTimeTm->tm_hour = hour;
	pGivenDateTimeTm->tm_mday = day;	
	pGivenDateTimeTm->tm_mon = month - 1;		// Note!
	pGivenDateTimeTm->tm_year = year;

	givenDate = mktime(pGivenDateTimeTm);

	return givenDate;
}

// returns the original string with all '&' replaced with "%26"
char* clsUtilities::MakeSafeString(char* pString)
{
	int i, j;
	int length;

	char *pSafeString;

	// safety
	if (pString == NULL) return NULL;

	length = strlen(pString);

	// shortcut...?
	if (strchr(pString, '&') == NULL)
	{
		pSafeString = new char[length+1];
		strcpy(pSafeString, pString);
		return pSafeString;
	}

	// safe version will be at most 3 times as long (if all chars are '&')
	pSafeString = new char[(length*3)+1];

	j = 0;
	for (i = 0; i < length; i++)
	{
		if (pString[i] == '&')
		{
			// replace '&' in user ID with "%26" in safe user ID
			strcpy(&pSafeString[j], "%26");
			j += 3;
		}
		else
		{
			// copy good chars one at a time
			pSafeString[j++] = pString[i];
		}
	}

	// finish it off
	pSafeString[j++] = '\0';

	return pSafeString;
}

// check whether the first "Length" bytes of the string are digits
bool clsUtilities::AreDigits(const char* pZip, int Length)
{
	int	i;

	if (!pZip)
		return false;

	for (i = 0; pZip[i] && i < Length; i++)
	{
		// check whether they are digit
		if (!IseBayDigit(pZip[i]))
			return false;
	}

	return (i == Length);
}

void clsUtilities::TimeToMidnight(time_t *t)
{	
	struct tm dateAtMidnight;
	struct tm *pTempTime;
	memset(&dateAtMidnight, '\0', sizeof (struct tm));

	// Set hours, mins, secs to 0.
	pTempTime = localtime(t);

	dateAtMidnight.tm_mday  = pTempTime->tm_mday;
	dateAtMidnight.tm_mon   = pTempTime->tm_mon;
	dateAtMidnight.tm_year  = pTempTime->tm_year;
	dateAtMidnight.tm_isdst = pTempTime->tm_isdst;

	*t = mktime(&dateAtMidnight);
}


// Constructs and returns a character string composed of words/phrases 
// contained in the given vector, separated by the specified separator string
// (e.g., if pvWords = ["word1", "word2", "word3"] and pSeparator = "\n",
// then the returned string would be "word1\nword2\nword3")
char * clsUtilities::StringVectorToString(vector<char *> *pvWords, char *pSeparator)
{
	char *			pWordsString = NULL;
	unsigned int	maxWordListLen = 0;
	unsigned int	numWords = 0;

	vector<char *>::iterator	i;

	if (pvWords == NULL || pSeparator == NULL)
		return NULL;

	// Get number of words in vector
	numWords = pvWords->size();

	// Compute how much memory we need to allocate (note that
	// we add 1 char per word/phrase in the vector cuz we need
	// it for separator between words/phrases and for the NULL
	// terminator)
	maxWordListLen = numWords * (EBAY_MAX_KEYWORD_SIZE + 1);
	if (maxWordListLen == 0)
		return NULL;

	// Allocate and initialize memory for string
	pWordsString = new char[maxWordListLen];
	memset(pWordsString, 0, maxWordListLen);

	// Construct string of words formatted as "%s\n%s\n..."
	// where each %s is a word in the input vector
	for (i = pvWords->begin(); i != pvWords->end(); ++i)
	{
		if (*i != NULL)
		{

			strcat(pWordsString, *i);
			strcat(pWordsString, pSeparator);
		}
	}

	return pWordsString;
}


// Get domain 
const char* clsUtilities::GetDomainToken(int siteId, int partnerId)
{
	switch (siteId)
	{
	case SITE_EBAY_US:
	case SITE_EBAY_MAIN:
		switch (partnerId)
		{
		case PARTNER_AOL:
			//return DOMAIN_EBAY_MAIN_AOL;
			return DOMAIN_EBAY_QA_AOL;				// this is for test only
			
		case PARTNER_EBAY_QA:
			return DOMAIN_EBAY_QA;

		default:
			return DOMAIN_EBAY_MAIN;
		}
		break;
	case SITE_EBAY_CA:
		return DOMAIN_EBAY_CA;
		break;
	case SITE_EBAY_DE:
		return DOMAIN_EBAY_DE;
		break;
	case SITE_EBAY_UK:
		return DOMAIN_EBAY_UK;
		break;
	case SITE_EBAY_AU:
		return DOMAIN_EBAY_AU;
		break;
	default:
		return DOMAIN_EBAY_MAIN;
	}

	return DOMAIN_EBAY_MAIN;
}

// nsacco 06/14/99
// Get site path 
const char* clsUtilities::GetPathToken(int siteId, int partnerId)
{
	clsSite* pSite;
	clsPartner* pPartner;
	char* theReturnString = NULL;

	pSite = gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetSites()->GetSite(siteId);
	pPartner = pSite->GetPartners()->GetPartner(partnerId);

	theReturnString = new char[256];
	strcpy(theReturnString, pSite->GetParsedString());

	if (strcmp(theReturnString, "") != 0)
	{
		strcat(theReturnString, "/");
	}

	strcat(theReturnString, pPartner->GetParsedString());

	if (strcmp(pPartner->GetParsedString(), "") != 0)
	{
		strcat(theReturnString, "/");
	}

	return theReturnString;
}


/*
void clsUtilities::GetSiteIDAndPartnerID(const char* pServerName, 
										const char* pURL, 
										int& SiteId, 
										int& PartnerId)
{
	//TODO - Rewrite this function so that it is more generic and tokenizes
	// the server name and url (and actually looks at the url). May the programming
	// gods forgive me for this hack.

	// TODO - remove the setcurrentcountry calls once parsed_string code is in place

	// AOL qa
	if (strstr(pServerName, DOMAIN_EBAY_QA))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_EBAY_QA;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_US);
		return;
	}

	// AOL
	else if (strstr(pServerName, DOMAIN_EBAY_QA_AOL))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_AOL;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_US);
		return;
	}

	// Canada
	else if (strstr(pServerName, DOMAIN_EBAY_CA))
	{
		SiteId = SITE_EBAY_CA;
		PartnerId = PARTNER_NONE;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_CA);
		return;
	}

	// UK
	else if (strstr(pServerName, DOMAIN_EBAY_UK))
	{
		SiteId = SITE_EBAY_UK;
		PartnerId = PARTNER_NONE;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_UK);
		return;
	}

	// Germany
	else if (strstr(pServerName, DOMAIN_EBAY_DE))
	{
		SiteId = SITE_EBAY_DE;
		PartnerId = PARTNER_NONE;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_DE);
		return;
	}

	// Australia
	else if (strstr(pServerName, DOMAIN_EBAY_AU))
	{
		SiteId = SITE_EBAY_AU;
		PartnerId = PARTNER_EBAY;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_AU);
		return;
	}

	// TODO - remove
	if (strstr(pServerName, "127.0.0.1"))//".ebay.aol.com"))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_AOL;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_US);
		return;
	}

	// TODO - remove
	else if (strstr(pServerName, "nsacco.corp.ebay.com"))
	{
		SiteId = SITE_EBAY_AU;
		PartnerId = PARTNER_EBAY;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_AU);
		return;
	}
	
	else
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_EBAY;
		gApp->GetMarketPlaces()->GetCurrentMarketPlace()->GetCountries()->SetCurrentCountry(Country_US);
	}
}
*/


// Find site id and partner id
// (1) tokenize (parse) url/server name
// (2) check each parsed string
// kakiyama 06/21/99
void clsUtilities::GetSiteIDAndPartnerID(const char* pServerName, 
										const char* pURL, 
										int& SiteId, 
										int& PartnerId)
{
	// a vector to maintain tokenized character strings.
	char *pTokenizedStr;

	// vectors for site and partner ids and parsed strings
	vector<char *> vSiteParsedString;
	vector<int> vSiteId;
	vector<char *> vPartnerParsedString;
	vector<int> vPartnerId;

	// let's instantiate clsSites first to determine the site id
	clsSites sites;
	vector<clsSite *> vSite;
	
	// iterator for site
	vector<clsSite *>::iterator iSite;	

	// server and script names are parsed and stored in a vector 
	vector<char *> vServerAndScriptNameStrings;
	
	// iterator for server and script names
	vector<char *>::iterator iString;

	// temp string 
	char tempString[512];
	
	// delimiters:  strtok function looks for ' ', '.' and '/'
	char seps[]   = " ./";

	// call a method of class sites to get all foreign sites
	// TODO - rename
	sites.GetForeignSites(&vSite); 

	// set default 
	SiteId = SITE_EBAY_MAIN;			// 0
	PartnerId = PARTNER_EBAY;			// 1

	// server name and script name will be stored in temp string
	strcpy(tempString, pServerName);
	strcat(tempString, pURL);

	// let's tokenize the string
	pTokenizedStr = strtok(tempString, seps);
	vServerAndScriptNameStrings.push_back(pTokenizedStr);

	while(pTokenizedStr != NULL)
	{
		pTokenizedStr = strtok(NULL, seps);
		if (pTokenizedStr != NULL)
			vServerAndScriptNameStrings.push_back(pTokenizedStr);
	}


	// let's compare user's tokenized strings with site strings first	
	iString = vServerAndScriptNameStrings.begin();
	
	while ((SiteId == SITE_EBAY_MAIN) && (iString != vServerAndScriptNameStrings.end()))
	{		

		iSite = vSite.begin();
		
		while ((SiteId == SITE_EBAY_MAIN) && (iSite != vSite.end()))
		{
			if ((*iSite)->GetParsedString() != NULL)
			{
				// check if the token matches the site's identifying string
				if (strcmp(*iString, (*iSite)->GetParsedString()) == 0)
					SiteId = (*iSite)->GetId();
			}

			iSite++;
		}

		iString++;
	}

	// let's instantiate clsPartners now since we know the site id
	clsPartners partners(SiteId);
	vector<clsPartner *> vPartner;
	
	// iterator for partner
	vector<clsPartner *>::iterator iPartner;

	// call a method of class partners to get partners for the site 
	partners.GetAllPartners(&vPartner);

	// now compare user's tokenized strings with partner strings
	iString = vServerAndScriptNameStrings.begin();

	while ((PartnerId == PARTNER_EBAY) && (iString != vServerAndScriptNameStrings.end()))
	{	
		iPartner = vPartner.begin();
		
		while ((PartnerId == PARTNER_EBAY) && (iPartner != vPartner.end()))
		{
			if ((*iPartner)->GetParsedString() != NULL)
			{
				// check if the token matches the partner's identifying string
				if (strcmp(*iString, (*iPartner)->GetParsedString()) == 0)
					PartnerId = (*iPartner)->GetId();
			}
				
			iPartner++;

		}

		iString++;
	}

	// QA AND DEVELOPER TESTING CODE
	// AOL qa
	if (strstr(pServerName, DOMAIN_EBAY_QA))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_EBAY_QA;
		return;
	}

	// AOL
	else if (strstr(pServerName, DOMAIN_EBAY_QA_AOL))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_AOL;
		return;
	}

	// TODO - remove
	else if (strstr(pServerName, "127.0.0.1"))//".ebay.aol.com"))
	{
		SiteId = SITE_EBAY_MAIN;
		PartnerId = PARTNER_AOL;
		return;
	}

	// TODO - remove
	else if (strstr(pServerName, "nsacco.corp.ebay.com"))
	{
		SiteId = SITE_EBAY_UK;
		PartnerId = PARTNER_EBAY;
		return;
	}
}