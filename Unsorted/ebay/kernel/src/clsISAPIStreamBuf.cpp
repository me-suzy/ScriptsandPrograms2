/*	$Id: clsISAPIStreamBuf.cpp,v 1.3 1998/06/30 09:11:30 josh Exp $	*/
//
//	File:	clsISAPIStreamBuf.cc
//
//	Class:	clsISAPIStreamBuf
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Surrogate stream functionality for apache
//
// Modifications:
//				- 02/06/97 michael	- Created
//
#include "eBayKernel.h"
#include "clsISAPIStreamBuf.h"
#include <afxisapi.h>

//
// clsISAPIStreamBuf
//
clsISAPIStreamBuf::clsISAPIStreamBuf(char *pBuffer, int bufferSize) :
	streambuf()
{
	mpBuffer	= pBuffer;
	mpBufferEnd	= pBuffer + bufferSize - 1;
	mBufferSize	= bufferSize;
	return;
}

//
// ~clsISAPIStreamBuf
//
clsISAPIStreamBuf::~clsISAPIStreamBuf()
{
	return;
}

int clsISAPIStreamBuf::sync ()
{
	*mpCurrentBufferPosition	= '\0';
	(*(CHttpServerContext *)mpContext) << mpBuffer;
	mpCurrentBufferPosition		= mpBuffer;
	memset(mpBuffer, 0x00, mBufferSize);
	return 0;
}

int clsISAPIStreamBuf::overflow (int ch)
{

	*mpCurrentBufferPosition	= ch;
	mpCurrentBufferPosition++;
	if (mpCurrentBufferPosition >= mpBufferEnd)
	{
		*mpCurrentBufferPosition	= '\0';
		(*(CHttpServerContext *)mpContext) << mpBuffer;
		mpCurrentBufferPosition	= mpBuffer;
		memset(mpBuffer, 0x00, mBufferSize);
		return 0;
	}


	return 0;
}

int clsISAPIStreamBuf::underflow()
{
	return 0;
}




void clsISAPIStreamBuf::SetContext(unsigned char *pCtx)
{
	mpContext				= pCtx;
	mpCurrentBufferPosition	= mpBuffer;
	memset(mpBuffer, 0x00, mBufferSize);
	return;
}
