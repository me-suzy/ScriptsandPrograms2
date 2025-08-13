/* $Id: ByteOrdering.h,v 1.2 1999/02/21 02:22:12 josh Exp $ */
//
// File: ThumbDBBuild
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This a db for thumbnail image sizes.
//

#ifndef _BYTEORDERING_H
#define _BYTEORDERING_H

#if 0
// A macro to reverse the byte order. Define this to be semantically null
// if the byte order of the producing machine and the byte
// order of the target machine are the same.

// long
#define FIX_BYTE_ORDER32(x)	(x) = ((((x) >> 24) & 0xFF) | \
				       (((x) >> 16) & 0xFF) << 8 | \
				       (((x) >> 8) & 0xFF) << 16 | \
					((x) & 0xFF) << 24)

// short
#define FIX_BYTE_ORDER16(x)	(x) = ((((x) >> 8) & 0xFF) | \
					((x) & 0xFF) << 8)
#else
// long
#define FIX_BYTE_ORDER32(x)

// short
#define FIX_BYTE_ORDER16(x)

#endif

#endif