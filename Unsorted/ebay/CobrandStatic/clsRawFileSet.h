/*	$Id: clsRawFileSet.h,v 1.3 1999/02/21 02:21:26 josh Exp $	*/
#ifndef clsRawFileSet_h
#define clsRawFileSet_h

class ostream;

class clsRawFile
{
private:

	unsigned long mNumCGITokens;
	unsigned long mNumHTMLTokens;
	unsigned long mNumTotalPieces;

	time_t mLastModified;

	char *mpTextBase;
	unsigned long mTextBaseSize;

	char **mpPieces;

	char mHeader[256];

	unsigned long mParsedNoTokensSize;
	int mPageType;

public:

	clsRawFile() :
	  mNumCGITokens(0), mNumHTMLTokens(0), mNumTotalPieces(0), mLastModified(0L), mpTextBase(NULL), 
		  mTextBaseSize(0), mpPieces(NULL), mParsedNoTokensSize(0), mValid(false), mPageType(0)
	  { mHeader[0] = '\0'; }

	~clsRawFile()
	{ if (mpTextBase) delete [] (mpTextBase - 1); /* We allocate funny. */ delete [] mpPieces; mName[0] = '\0'; }

	void ParseFile(WIN32_FIND_DATA *pFileInfo);
	void ParseFile(FILE *pFile, const char *pName, unsigned long length);

	void WriteToStream(const char *pCGIToken,
		const char *pHTMLToken,
		const char *pHeader,
		const char *pFooter,
		ostream *pStream);

	int GetPageType() { return mPageType; }

	bool mValid;
	char mName[256];
};

void Invalidate(const char *pName);

clsRawFile *GetFile(const char *pName);

void StartFileSet();
void EndFileSet();

#endif /* clsRawFileSet_h */
