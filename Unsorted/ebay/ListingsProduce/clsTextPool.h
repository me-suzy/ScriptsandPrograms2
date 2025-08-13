/*	$Id: clsTextPool.h,v 1.2 1998/06/23 04:21:29 josh Exp $	*/
//
// File: clsTextPool
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: A text pool class, provides a common
// text pool and returns offset values for strings --
// used for making binary files.
//
#ifndef CLSTEXTPOOL_INCLUDE
#define CLSTEXTPOOL_INCLUDE

class clsTextPool
{
private:
	char *mpBuffer;
	long mMaxSize;
	long mCurrentSize;

	void GrowBuffer(unsigned long length);

public:
	// Returns the offset of the string added.
	// Does no string sharing or anything fancy.
	long AddString(const char *pString);

	const char *GetBuffer();

	// A size that will respect word alignment, so we don't get bus errors
	// or their equivalent.
	long GetSafeWriteSize();

	clsTextPool();
	~clsTextPool();
};

#endif /* CLSTEXTPOOL_INCLUDE */