/*	$Id: clsTextPool.cpp,v 1.2 1998/06/23 04:21:28 josh Exp $	*/
//
// File: clsTextPool
//
// Author: Chad Musick (chad@ebay.com)
//
// Description: A text pool class, provides a common
// text pool and returns offset values for strings --
// used for making binary files.
//
#include "clsTextPool.h"

#include <string.h>

// Allocate some memory for the buffer, and
// set the counters correctly.
clsTextPool::clsTextPool()
{
	mpBuffer = new char [1024]; // Start with 1K.
	mMaxSize = 1024;

	mCurrentSize = 0;
}

clsTextPool::~clsTextPool()
{
	delete [] mpBuffer;
}

// Grows the buffer when it is too small.
void clsTextPool::GrowBuffer(unsigned long length)
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
long clsTextPool::GetSafeWriteSize()
{
	return mCurrentSize + (4 - (mCurrentSize % 4));
}

// Add the string and return the offset where it was added.
long clsTextPool::AddString(const char *pString)
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

const char *clsTextPool::GetBuffer()
{ 
	return mpBuffer; 
}
