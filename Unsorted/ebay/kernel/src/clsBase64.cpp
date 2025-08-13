/*	$Id: clsBase64.cpp,v 1.5 1998/12/06 05:31:41 josh Exp $	*/
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

#include "eBayKernel.h"
#include "clsBase64.h"
#include <string.h>

static char basis_64[] =
   "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

static char index_64[128] = {
    -1,-1,-1,-1, -1,-1,-1,-1, -1,-1,-1,-1, -1,-1,-1,-1,
    -1,-1,-1,-1, -1,-1,-1,-1, -1,-1,-1,-1, -1,-1,-1,-1,
    -1,-1,-1,-1, -1,-1,-1,-1, -1,-1,-1,62, -1,-1,-1,63,
    52,53,54,55, 56,57,58,59, 60,61,-1,-1, -1,-1,-1,-1,
    -1, 0, 1, 2,  3, 4, 5, 6,  7, 8, 9,10, 11,12,13,14,
    15,16,17,18, 19,20,21,22, 23,24,25,-1, -1,-1,-1,-1,
    -1,26,27,28, 29,30,31,32, 33,34,35,36, 37,38,39,40,
    41,42,43,44, 45,46,47,48, 49,50,51,-1, -1,-1,-1,-1
};

#define char64(c)  (((c) < 0 || (c) > 127) ? -1 : index_64[(c)])


//
// Encode a source string into base64 format. The encoded string
// is null terminated
//
const char* clsBase64::Encode(const char* pSrc, int SrcLength)
{
	int		Units;
	int		DesLength;
	int		i;
	char*	pTempDes;

	// find out the length of the encoded string
	Units = SrcLength / 3;

	if (SrcLength % 3 != 0)
	{
		Units++;
	}
	DesLength = Units * 4;

	delete [] mpDes;
	mpDes = new char[DesLength + 1];
	pTempDes = mpDes;

	// encode src 3 chars a time
	for (i = 0; i < SrcLength; i += 3, pTempDes += 4)
	{
		if (i + 1 >= SrcLength)
		{
			Make64Chunk(pSrc[i], 0, 0, 2, pTempDes);
		} 
		else if (i + 2 >= SrcLength)
		{
			Make64Chunk(pSrc[i], pSrc[i+1], 0, 1, pTempDes);
		} 
		else
		{
			Make64Chunk(pSrc[i], pSrc[i+1], pSrc[i+2], 0, pTempDes);
		}
	}

	// terminate it
	mpDes[DesLength] = 0;

	return mpDes;
}

//
// Convert 3 chars to 4 bytes
//
void clsBase64::Make64Chunk(unsigned char c1, unsigned char c2, unsigned char c3, int pads, char* pDes)
{
    pDes[0] = basis_64[c1>>2];
    pDes[1] = basis_64[((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4)];
    if (pads == 2)
	{
        pDes[2] = '*';
        pDes[3] = '*';
    }
	else if (pads == 1) 
	{
        pDes[2] = basis_64[((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6)];
        pDes[3] = '*';
    } 
	else 
	{
        pDes[2] = basis_64[((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6)];
        pDes[3] = basis_64[c3 & 0x3F];
    }
}

//
// decode
//
const char * clsBase64::Decode(const char* pSrc, int& DesLength)
{
    char	c1, c2, c3, c4;
	const char*	pTemp;

	// not a valid base 64 encoded string
	if (strlen(pSrc) % 4 != 0)
		return NULL;

	// estmate the length for decoded string, to allocate the memory
	DesLength = (strlen(pSrc) / 4) * 3;
	delete [] mpDes;
	mpDes = new char [DesLength + 1];

	// decode 4 char a time
	DesLength = 0;
	for (pTemp = pSrc; *pTemp !=  0; pTemp += 4)
	{
		c1 = pTemp[0];
		c2 = pTemp[1];
		c3 = pTemp[2];
		c4 = pTemp[3];

        if (c1 == '*' || c2 == '*') 
		{
			break;
        }

        c1 = char64(c1);
        c2 = char64(c2);
		mpDes[DesLength++] = (c1<<2) | ((c2&0x30)>>4);

        if (c3 == '*') 
		{
           break;
        } 
		else 
		{
            c3 = char64(c3);
            mpDes[DesLength++] = ((c2&0XF) << 4) | ((c3&0x3C) >> 2);
            if (c4 == '*') 
			{
                break;
            } 
			else 
			{
                c4 = char64(c4);
                mpDes[DesLength++] = ((c3&0x03) <<6) | c4;
            }
        }
    }

	return mpDes;
}

