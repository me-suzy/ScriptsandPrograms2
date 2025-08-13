/* $Id: clsParser.cpp,v 1.2 1999/05/19 02:35:00 josh Exp $ */
//
// File: clsParser.cpp
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: xxxxx
//

#include "eBayKernel.h"
#include "clsParser.h"

clsSimpleParser::PatternTokenizer::PatternTokenizer(const char* pattern)
{
	mInput = pattern;
	mNextPos = pattern;
	mEndPos = pattern + strlen(pattern);
	mHasPeek = false;
}

int clsSimpleParser::PatternTokenizer::Eof()
{
	return mNextPos >= mEndPos;
}

int clsSimpleParser::PatternTokenizer::GetChar()
{
	if (Eof())
		return -1;
	else
		return *mNextPos++;
}

int clsSimpleParser::PatternTokenizer::PeekChar()
{
	if (Eof())
		return -1;
	else
		return *mNextPos;
}

int clsSimpleParser::PatternTokenizer::UngetChar(int c)
{
	if (mNextPos <= mInput)
		return -1;
	else
		--mNextPos;

	return 0;
}

int clsSimpleParser::PatternTokenizer::EatSpace()
{
	int c = PeekChar();

	while (c > 0)
	{
		if (isspace(c) && c != '\n')
			GetChar();	// Eat white space
		else
			return 0;

		c = PeekChar();
	}

	return -1;
}

clsSimpleParser::PatternTokenizer::Token clsSimpleParser::PatternTokenizer::GetNextToken()
{
	if (mHasPeek)
		return mPeekToken;

	mHasPeek = false;

	EatSpace();

	int c = GetChar();
	if (c == -1)
		return kEOF;

	switch (c)
	{
	case '(':
		return kLeftParen;
	case ')':
		return kRightParen;
	case '!':
		return kExplanationPoint;
	case '|':
		return kOr;
	default:
		break;
	}

#ifdef _MSC_VER
	mLiteralValue.erase();
#else
	mLiteralValue.remove(mLiteralValue.begin(), mLiteralValue.end());
#endif
	
	while (c != -1)
	{
		if (!isalnum(c) && !isspace(c))
		{
			UngetChar(c);
			break;
		}

		if (!isspace(c))
		{
			mLiteralValue.append(1, tolower(c));
		}
		
		c = GetChar();
	}

	return kLiteral;
}

clsSimpleParser::PatternTokenizer::Token clsSimpleParser::PatternTokenizer::PeekNextToken()
{
	if (mHasPeek)
		return mPeekToken;

	mPeekToken = GetNextToken();
	mHasPeek = true;

	return mPeekToken;
}

clsSimpleParser::Pattern::Pattern() 
	: mSearchPattern(NULL), mActive(false)
{
}

clsSimpleParser::Pattern::~Pattern()
{
	if (mSearchPattern)
		free(mSearchPattern); 
	// We use free instead of delete because the object
	// was made with strdup and not new.
}


/*
	(Z64) | (wild card) | (pirated game ! not pirated ! not at all pirated)
*/

int clsSimpleParser::GetIndex(char c)
{
	if (isdigit(c))
		return c - '0';
	else
		return 10 + (c - 'a');
}

clsSimpleParser::Error clsSimpleParser::ParseStatement(PatternTokenizer& tokenizer)
{
	// Get left paren
	PatternTokenizer::Token token = tokenizer.GetNextToken();
	if (token != PatternTokenizer::kLeftParen)
		return kParseError;
	
	// Get search pattern
	token = tokenizer.GetNextToken();
	if (token != PatternTokenizer::kLiteral)
		return kParseError;

	Pattern* pattern = new Pattern();
	pattern->mSearchPattern = strdup(tokenizer.mLiteralValue.c_str());
	pattern->mActive = false;

	token = tokenizer.GetNextToken();

	// Get the neutralizer patterns
	while (token == PatternTokenizer::kExplanationPoint)
	{
		token = tokenizer.GetNextToken();
		if (token != PatternTokenizer::kLiteral)
			goto BAD;
		char* literal = strdup(tokenizer.mLiteralValue.c_str());
		pattern->mNeutralizePatterns.push_back(literal);
	
		token = tokenizer.GetNextToken();
	}

	// Do we have the right paren?
	if (token != PatternTokenizer::kRightParen)
		goto BAD;

	// Find home for pattern
	{
		int i;

		char c = pattern->mSearchPattern[0];
		if (!isalnum(c))
			goto BAD;

		i = GetIndex(c);
		
		if (mAllPatterns[i] == NULL)
		{
			mAllPatterns[i] = new LetterPatterns();
		}

		mAllPatterns[i]->push_back(pattern);
	}

	return kNoError;

BAD:
	delete pattern;
	return kParseError;
}

clsSimpleParser::Error clsSimpleParser::ParseRoot(PatternTokenizer& tokenizer)
{
	Error error = ParseStatement(tokenizer);
	if (error)
		return error;

	while (!tokenizer.Eof())
	{
		PatternTokenizer::Token token = tokenizer.GetNextToken();

		if (token == PatternTokenizer::kEOF)
			return kNoError;

		if (token != PatternTokenizer::kOr)
			return kParseError;

		error = ParseStatement(tokenizer);
		if (error)
			return error;
	}

	return kNoError;
}

clsSimpleParser::clsSimpleParser(const char* pattern)
{
#ifdef _MSC_VER
	mAllPatterns.resize(('z' - 'a') + 1 + 10, NULL);
#else
	// Hack, not sure how to do this more elegantly, 
	// lazy me. jpg
	mAllPatterns.resize(('z' - 'a') + 1 + 10);
	AllPatterns::iterator begin = mAllPatterns.begin();
	AllPatterns::iterator end = mAllPatterns.end();
	for (AllPatterns::iterator i=begin; i != end; i++)
		*i = NULL;
#endif
	

	mPatternError = kNoError;

	PatternTokenizer tokenizer(pattern);

	mPatternError = ParseRoot(tokenizer);
}

clsSimpleParser::~clsSimpleParser()
{
	AllPatterns::iterator begin = mAllPatterns.begin();
	AllPatterns::iterator end = mAllPatterns.end();

	for (; begin != end; ++begin)
	{
		LetterPatterns* letterPatterns = *begin;

		if (letterPatterns)
		{
			LetterPatterns::iterator begin = letterPatterns->begin();
			LetterPatterns::iterator end = letterPatterns->end();

			for (; begin != end; ++begin)
			{
				Pattern* pattern = *begin;

				delete pattern;
			}

			delete letterPatterns;
		}
	}
}

void clsSimpleParser::DumpPattern()
{
	AllPatterns::iterator begin = mAllPatterns.begin();
	AllPatterns::iterator end = mAllPatterns.end();
	int i = 0;

	for (; begin != end; ++begin, ++i)
	{
		LetterPatterns* letterPatterns = *begin;

		if (letterPatterns)
		{
			{
				char c;

				if (i <= 9)
				{
					c = '0' + i;
				}
				else
				{
					c = 'a' + (i - 10);
				}

				cout << c << ":" << endl;
			}

			LetterPatterns::iterator begin = letterPatterns->begin();
			LetterPatterns::iterator end = letterPatterns->end();

			for (; begin != end; ++begin)
			{
				Pattern* pattern = *begin;

				cout << "\t" << pattern->mSearchPattern << endl;

				vector<char*>::iterator nBegin = pattern->mNeutralizePatterns.begin();
				vector<char*>::iterator nEnd = pattern->mNeutralizePatterns.end();

				for (; nBegin != nEnd; ++nBegin)
				{
					cout << "\t" << *nBegin << endl;
				}
			}

		}
	}
}

void clsSimpleParser::Reset()
{
	AllPatterns::iterator begin = mAllPatterns.begin();
	AllPatterns::iterator end = mAllPatterns.end();

	for (; begin != end; ++begin)
	{
		LetterPatterns* letterPatterns = *begin;

		if (letterPatterns)
		{
			LetterPatterns::iterator begin = letterPatterns->begin();
			LetterPatterns::iterator end = letterPatterns->end();

			for (; begin != end; ++begin)
			{
				Pattern* pattern = *begin;
				pattern->mActive = true;
			}
		}
	}
}

#ifdef _MSC_VER
static void StripAndPackString(std::string& s, const char* src)
#else
static void StripAndPackString(string& s, const char* src)
#endif

{
	const char* begin = src;
	const char* end = src + strlen(src);

	// Strip and pack the target into a new string
	for (; begin != end; ++begin)
	{
		char c = *begin;

		// This needs internationalization in a big way
		if (
			(c >= '0' && c <= '9') 
			 ||
			(c >= 'A' && c <= 'Z')
			 ||
			(c >= 'a' && c <= 'z')
		   )
		{
			s.append(1, tolower(c));	
		}
	}
}

static bool MatchString(const char* pattern, const char* begin, const char* end)
{
	while (begin != end)
	{
		char c = *pattern++;
		
		if (c == 0)
			return true;

		if (c != *begin++)
			return false;
	}

	return *pattern == 0;
}

bool clsSimpleParser::Match(const char* target)
{
	Reset();

#ifdef _MSC_VER
	std::string searchString;
#else
	string searchString;
#endif
	
	StripAndPackString(searchString, target);

	const char* start = searchString.c_str();
	const char* begin = start;
	const char* end = begin + strlen(begin);

	for (; begin != end; ++begin)
	{
		char c = *begin;
		int index = GetIndex(c);

		LetterPatterns* letterPatterns = mAllPatterns[index];

		if (letterPatterns)
		{
			LetterPatterns::iterator lBegin = letterPatterns->begin();
			LetterPatterns::iterator lEnd = letterPatterns->end();

			for (; lBegin != lEnd; ++lBegin)
			{
				Pattern* pattern = *lBegin;

				if (!pattern->mActive)
					continue;

				bool foundSearchString = MatchString(pattern->mSearchPattern, begin, end);

				if (!foundSearchString)
					continue;

				vector<char*>::iterator nBegin = pattern->mNeutralizePatterns.begin();
				vector<char*>::iterator nEnd = pattern->mNeutralizePatterns.end();

				for (; nBegin != nEnd; ++nBegin)
				{
					char* locationFound = strstr(start, *nBegin);

					if (locationFound)
					{
						pattern->mActive = false;
						break;
					}
				}

				if (pattern->mActive)
					return true;
			}
		}
	}

	return false;
}
