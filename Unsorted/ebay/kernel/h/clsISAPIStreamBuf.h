/*	$Id: clsISAPIStreamBuf.h,v 1.2 1998/06/23 04:28:10 josh Exp $	*/
#ifndef CLSISAPISTREAMBUF_INCLUDED

#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */ 
// #include <afxisapi.h>

//
// clsISAPIStreamBuf
//
class clsISAPIStreamBuf : public streambuf
{
	public:
		clsISAPIStreamBuf(char *pBuffer, int bufferSize);
		~clsISAPIStreamBuf();

		//
		// SetResponseObject
		//
		// Sets the address of the ASP Response object
		//
		void SetContext(unsigned char *pCtx);

	    int sync ();
		int overflow (int ch);
		int underflow();
	private:
		unsigned char	*mpContext;
		char			*mpBuffer;
		char			*mpBufferEnd;
		char			*mpCurrentBufferPosition;
		int				mBufferSize;
};

#define CLSISAPISTREAMBUF_INCLUDED 1
#endif /* CLSISAPISTREAMBUF_INCLUDED */
