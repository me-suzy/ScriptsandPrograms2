/* $Id: clsParser.h,v 1.2 1999/05/19 02:34:45 josh Exp $ */
//
// File: clsParser.h
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: xxxx
//

#include "eBayKernel.h"
#include <string>

class clsParser
{
public:
	clsParser() {}
	clsParser(const char* pattern);
	virtual ~clsParser() {}
	virtual bool Match(const char* target) = 0;
};

class clsSimpleParser : public clsParser
{
public:
	enum Error
	{
		kNoError = 0,
		kParseError
	};

	clsSimpleParser() : clsParser() {}
	clsSimpleParser(const char* pattern);
	~clsSimpleParser();
	bool Match(const char* target);
	void DumpPattern();
	Error GetPatternError() { return mPatternError; }

private:
	class PatternTokenizer
	{
	public:
		enum Token
		{
			kEOF = -1,
			kLiteral = 1,
			kLeftParen,
			kRightParen,
			kExplanationPoint,
			kOr
		};

#ifdef _MSC_VER
		std::string mLiteralValue;
#else
		string mLiteralValue;
#endif

		PatternTokenizer(const char* pattern);

		Token GetNextToken();
		Token PeekNextToken();
		int Eof();

	private:
		const char* mInput;
		const char* mNextPos;
		const char* mEndPos;
		bool mHasPeek;
		Token mPeekToken;

		// Buffer management
		int GetChar();
		int PeekChar();
		int UngetChar(int c);
		int EatSpace();
	};

	struct Pattern
	{
		Pattern();
		~Pattern();

		char* mSearchPattern;
		vector<char*> mNeutralizePatterns;
		bool mActive;
	};

	typedef vector<Pattern*> LetterPatterns;
	typedef vector<LetterPatterns*> AllPatterns;

	AllPatterns mAllPatterns;
	Error mPatternError;

	int GetIndex(char c);
	void Reset();

	Error ParseRoot(PatternTokenizer& tokenizer);
	Error ParseStatement(PatternTokenizer& tokenizer);
};
