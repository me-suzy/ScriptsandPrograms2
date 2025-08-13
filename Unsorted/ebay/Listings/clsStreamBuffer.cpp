/* $Id: clsStreamBuffer.cpp,v 1.3 1998/10/30 00:37:42 josh Exp $ */
//
//	File:	clsStreamBuffer.cc
//
//	Class:	clsStreamBuffer
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Surrogate stream functionality for apache
//
// Modifications:
//				- 02/06/97 michael	- Created
//				- 03/12/98 chad - modified to work in a filter instead.
//

// Defines EXTENSION_CONTROL_BLOCK
#include <Httpext.h>

#include "clsStreamBuffer.h"
#include <afxisapi.h>

#include <stdio.h>

static const int initial_buffer_size = (1 << 15);

//
// clsStreamBuffer
//
clsStreamBuffer::clsStreamBuffer() :
	streambuf()
{
	mpBuffer = new char [initial_buffer_size];
	mBufferSize = initial_buffer_size;
	mpBufferEnd = mpBuffer + mBufferSize - 1;
	mpCurrentBufferPosition = mpBuffer;
	mpECB = NULL;
}

//
// ~clsStreamBuffer
//
clsStreamBuffer::~clsStreamBuffer()
{
	delete [] mpBuffer;
     return;
} 

// sync() is called when << flush is called. It is supposed to clear
// the stream, if possible.
// In order to do an asynch write we have to only do one (1) of them,
// so we can't really do anything about flushing -- we have to wait
// until we're all done, and then send off the write request.
// If you implement this, you'll take out the possibility to do asynch
// socket writes.
int clsStreamBuffer::sync()
{
	return 0;
}

void clsStreamBuffer::GrowBuffer()
{
	char *pNewBuffer;
	unsigned long length;

	// Find the current length.
	length = mpBufferEnd - mpBuffer;

	// Double it and add 1.
	pNewBuffer = new char [(length * 2) + 1];

	// Copy it over.
	memcpy(pNewBuffer, mpBuffer, length);

	// Do this before mpBuffer, so that we can calculate length.
	mpCurrentBufferPosition = pNewBuffer + (mpCurrentBufferPosition - mpBuffer);
	// And set the buffer end position.
	mpBufferEnd = pNewBuffer + (length * 2) + 1;

	// Delete the old buffer.
	delete [] mpBuffer;
	// And set it.
	mpBuffer = pNewBuffer;
}

// We can't just flush out the buffer, since we can only do that once
// if we want to do asynch writes. (See comment above).
// This expands the buffer instead.
// This gets called with every character inserted. It should always return 0.
int clsStreamBuffer::overflow (int ch)
{
	// If we're too short, expand the buffer.
	if (mpCurrentBufferPosition >= mpBufferEnd)
		GrowBuffer();

	*mpCurrentBufferPosition	= (char) ch;
	mpCurrentBufferPosition++;

	return 0;
}

int clsStreamBuffer::underflow()
{
	return 0;
}

// This function does the asynch write.
// When we do the write it copies the buffer, so we can re-use it
// for the next request.
void clsStreamBuffer::WriteItOut()
{
	unsigned long length;
    unsigned long content_length;
    char *pContentLengthPosition;

	// Zero off our string. We always have one spare character for this.
	*mpCurrentBufferPosition = '\0';
	length = mpCurrentBufferPosition - mpBuffer;

    // Find and set the content-length header.
    pContentLengthPosition = strstr(mpBuffer, "########");
    if (pContentLengthPosition != NULL)
    {
        // 13 is: 8 for the #'s, and 4 for two sets of \r\n and 1 for
        // good luck (Actually, because the length is always 1 longer
        // than the difference.)
		if (length < (unsigned long) (pContentLengthPosition + 13 - mpBuffer))
			content_length = 0;
		else
	        content_length = length - (pContentLengthPosition + 13 - mpBuffer) + 1;

		// If we have no content_length, _don't_ send that header. The normal
		// case for this is a HEAD request.
		if (content_length == 0)
		{
			// We overwrite this -- strcpy puts the null in.
			pContentLengthPosition = strstr(mpBuffer, "Content-Length");
			strcpy(pContentLengthPosition, "\r\n");
			length = strlen(mpBuffer);
		}
		else
		{
			sprintf(pContentLengthPosition, "%8d", content_length);
			// Compensate for placed null.
			*(pContentLengthPosition + 8) = '\r';
		}
    }

	// Set up to use asynch. We don't actually supply an asynch callback, since
	// we don't care about this connection anymore. (That's what all the NULL stuff
	// is about.)
	mpECB->ServerSupportFunction(mpECB->ConnID, HSE_REQ_IO_COMPLETION,
		NULL, NULL, NULL);

	// Send it off with WriteClient.
	mpECB->WriteClient(mpECB->ConnID, mpBuffer, &length, HSE_IO_ASYNC);

	// And reset the buffers for the next request.
	mpCurrentBufferPosition = mpBuffer;
}
