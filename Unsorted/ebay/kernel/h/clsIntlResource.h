/*	$Id: clsIntlResource.h,v 1.1.4.1 1999/08/05 19:01:10 nsacco Exp $ */
//
//	File:	clsIntlResource.h
//
//	Class:	clsIntlResource
//
//	Author:	Robin Kennedy (rkennedy@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 08/02/90 Robin Kennedy - Created
//
//

#ifndef __IntlResource__
#define __IntlResource__

#ifdef _UNICODE_
typedef unsigned short IntlChar;
#else
typedef char IntlChar;
#endif

typedef IntlChar * IntlString;

class clsIntlStringList
{
private:
	unsigned long	mStringBlockSize;	// Total size of the allocated string block in BYTES.
	IntlString		mpStringBlock;		// Pointer to the start of the string block
	unsigned long	mStringCount;		// Total count of the number of strings in the list.
	unsigned long * mpOffsetList;		// Index array to each string in the string block.	
	bool			mCompressed;		// True if the string data is compressed.

	unsigned long	StrByteCount(IntlString s);

public:
	clsIntlStringList();
	~clsIntlStringList();

	unsigned long	Count();					// Returns the string count.
	IntlString		Add(IntlString s);			// Add a string to the end of the list.
	IntlString		Get(unsigned long Index);	// Returns a pointer to the indexed string.
	long			Size(unsigned long Index);	// Returns the size of the string in characters. In 
												// the case of MBCS, the number of BYTES are returned.
};

class clsIntlStringBuffer
{
private:
	long	mBufferSize;	// Size of buffer, will grow as required.
	long	mBytesUsed;		// Size of string including the zero terminator in bytes.
	long	mNextChar;		// Index position for next write opp.
	bool	GrowBuffer();	// Extend the buffer.

public:
	IntlString		mpBuffer;

	clsIntlStringBuffer();

	~clsIntlStringBuffer();

	void	Clear();						// Reset the buffer.
											// Note: All Add() members grow the buffer if required.
	void	Add(IntlChar c);				// Adds a character to the end of the buffer, and zero terminates
											// the buffer.
	void	Add(IntlString s, int l = -1);	// Adds max l characters to the end of the buffer, and zero 
											// terminates the buffer.
};
	
class clsIntlResContainer
{
public:
	short					mResId;			// Resource ID e.g. US English is 1, German is 2.
	IntlChar				mpResName[21];	// Name of resource e.g. en_US, en_GB, fr_CA
	unsigned long			mUserCount;		// Count of number of users of this resource.
	short					mVersion;		// The version number of the eBay code.
	short					mRevision;		// The revision number of this language.
	clsIntlStringList	*	mpStringList;	// Pointer to the resource string list.
	
	clsIntlResContainer *	mpNext;			// Pointer to the next resource, or NULL if this is last one.
	clsIntlResContainer *	mpPrevious;		// Pointer to previous resource, or NULL if this is the first.
};

class clsIntlResource
{
private:
	clsIntlResContainer	*	mpResContainer;			// Pointer to this resource container.
	clsIntlStringList	*	mpToStringList;			// Used by ToString to hold var strings.
	clsIntlStringList	*	LoadResource(short Id);	// Resource loader.
	IntlString				Get_Formatted_String(long Id, IntlString UsString, va_list vl);
	IntlString				Get_Res_String(long Id, IntlString UsString);

	int						IntlStrToInt(IntlString s);
	
	
	
public:
	clsIntlStringBuffer	*	mpFormatBuffer;
	clsIntlResource(short Id);
	//clsIntlResource(IntlString Name);
	~clsIntlResource();

	static IntlString		GetFResString(long Id, IntlString UsString, ...);
	static IntlString		GetResString(long Id, IntlString UsString);
	static IntlString		ToString(IntlString s);
	static IntlString		ToString(const char * s);
	static IntlString		ToString(int i);

};

#endif __IntlResource__