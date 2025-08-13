/* $Id: clsDataPool.cpp,v 1.3 1998/12/06 05:22:39 josh Exp $ */
//
// File: clsDataPool
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: A data pool class, provides a common
// data pool and returns offset values for data --
// used for making binary files.
//
#include "widgets.h"

#include <string.h>

// Allocate some memory for the buffer, and
// set the counters correctly.
clsDataPool::clsDataPool()
{
	mpBuffer = new char [1024]; // Start with 1K.
	mMaxSize = 1024;

	mCurrentSize = 0;
}

clsDataPool::~clsDataPool()
{
	delete [] mpBuffer;
}

// Grows the buffer when it is too small.
void clsDataPool::GrowBuffer(unsigned long length)
{
	char *pNewBuffer;

	// Keep growing until we can hold the length.
	while ((length + mCurrentSize) > (unsigned long) (mMaxSize - 4))
		mMaxSize *= 2; // Double the size

	pNewBuffer = new char [mMaxSize];

	// Copy and replace.
	memcpy(pNewBuffer, mpBuffer, mCurrentSize);
	
	delete [] mpBuffer;
	mpBuffer = pNewBuffer;
}

// This will return up to 4 bytes longer than the
// string.
long clsDataPool::GetSafeWriteSize()
{
	return mCurrentSize + (4 - (mCurrentSize % 4));
}

// Add the string and return the offset where it was added.
long clsDataPool::AddString(const char *pString)
{
	unsigned long length;
	long nextString = mCurrentSize;

	length = strlen(pString) + 1;

	// We subtract 4 here for the word alignment,
	// so that we don't accidentally overread when
	// we write it out.
	if (length + mCurrentSize > (unsigned long) (mMaxSize - 4))
		GrowBuffer(length);

	memcpy(mpBuffer + mCurrentSize, pString, length);
	mCurrentSize += length;

	return nextString;
}

long clsDataPool::AddStringN(const char *pString, unsigned long length)
{
    long nextString = mCurrentSize;

    if (length > strlen(pString))
        return AddString(pString);

    if ((length + 1) + mCurrentSize > (unsigned long) (mMaxSize - 4))
        GrowBuffer(length);

    memcpy(mpBuffer + mCurrentSize, pString, length);	//lint !e671 nonsense
    // Add the terminator.
    mpBuffer[mCurrentSize + length] = '\0';
    mCurrentSize += length + 1;

    return nextString;
}

long clsDataPool::AddData(const void *pData, unsigned long length)
{
    long nextString = mCurrentSize;

    // Check to see if we need to bump up to the next word.
    if (mCurrentSize % 4)
        nextString = mCurrentSize = GetSafeWriteSize();

    if (length + mCurrentSize > (unsigned long) (mMaxSize - 4))
        GrowBuffer(length);

    memcpy(mpBuffer + mCurrentSize, pData, length);		//lint !e671 nonsense
    mCurrentSize += length;

    return nextString;
}

const char *clsDataPool::GetBuffer()
{ 
	return mpBuffer; 
}
