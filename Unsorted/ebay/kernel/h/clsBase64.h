/*	$Id: clsBase64.h,v 1.4 1998/09/30 02:58:33 josh Exp $	*/
/*
Copyright (c) 1991 Bell Communications Research, Inc. (Bellcore)

Permission to use, copy, modify, and distribute this material 
for any purpose and without fee is hereby granted, provided 
that the above copyright notice and this permission notice 
appear in all copies, and that the name of Bellcore not be 
used in advertising or publicity pertaining to this 
material without the specific, prior written permission 
of an authorized representative of Bellcore.  BELLCORE 
MAKES NO REPRESENTATIONS ABOUT THE ACCURACY OR SUITABILITY 
OF THIS MATERIAL FOR ANY PURPOSE.  IT IS PROVIDED "AS IS", 
WITHOUT ANY EXPRESS OR IMPLIED WARRANTIES.
*/

//
//	File:	clsBase64.cpp
//
//	Class:	clsBase64
//
//	Author:	Wen Wen
//
//		A class based on the algorithm (see above) to encode and decode
//		a base64 string
//
// Modifications:
//				- 08/04/98	Wen - Created

#ifndef	__CLSBASE64_INCLUDE__
#define __CLSBASE64_INCLUDE__

#include <stdlib.h>

class clsBase64
{
public:
	clsBase64() {mpDes = NULL;}
	~clsBase64(){ delete [] mpDes; }

	const char* Encode(const char* pSrc, int SrcLength);
	const char* Decode(const char* Src, int& DesLength);

protected:
	void Make64Chunk(unsigned char c1, unsigned char c2, unsigned char c3, int pads, char* pDes);

	char*	mpDes;
};

#endif // __CLSBASE64_INCLUDE__
