/* $Id: clsStreamBuffer.h,v 1.2 1998/06/23 04:21:13 josh Exp $ */
#ifndef clsStreamBuffer_INCLUDED

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */ 

#ifndef _EBAY_H
#include "ebay.h"
#endif

//
// clsStreamBuffer
//
class clsStreamBuffer : public streambuf
{
	public:
		clsStreamBuffer();
		CopyAssign(clsStreamBuffer);
		~clsStreamBuffer();

		// Sets the connection object, to be used in processing.
		void SetConnection(EXTENSION_CONTROL_BLOCK *pECB)
		{ mpECB = pECB; }

		// Grows the buffer when necessary.
		void GrowBuffer();

		// Sets up new buffers for us.
		void ResetBuffer();

		// Writes it out asynchronously.
		void WriteItOut();

	    int sync ();
		int overflow (int ch);
		int underflow();
	private:
		EXTENSION_CONTROL_BLOCK *mpECB;

		char			*mpBuffer;
		char			*mpBufferEnd;
		char			*mpCurrentBufferPosition;
		int				mBufferSize;
};

#define clsStreamBuffer_INCLUDED 1
#endif /* clsStreamBuffer_INCLUDED */
