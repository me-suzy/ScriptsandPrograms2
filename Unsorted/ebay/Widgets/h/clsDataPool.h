/* $Id: clsDataPool.h,v 1.2 1998/10/16 01:00:34 josh Exp $ */
//
// File: clsDataPool
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: A data pool class, provides a common
// data pool and returns offset values for data --
// used for making binary files.
//
#ifndef clsDataPool_h
#define clsDataPool_h

class clsDataPool
{
private:
	char *mpBuffer;
	long mMaxSize;
	long mCurrentSize;

	void GrowBuffer(unsigned long length);

public:
	// Returns the offset of the string or data added.
	// Does no string sharing or anything fancy.

    // Adds a full string.
	long AddString(const char *pString);
    // Adds 'N' characters of a string, and null terminates.
    long AddStringN(const char *pString, unsigned long length);
    // Adds raw data. Guarantees to add it on machine word boundaries.
    long AddData(const void *pData, unsigned long length);

	const char *GetBuffer();

	// A size that will respect word alignment, so we don't get bus errors
	// or their equivalent.
	long GetSafeWriteSize();

	clsDataPool();
	~clsDataPool();
};

#endif /* clsDataPool_h */