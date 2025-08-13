/* $Id: ebay.h,v 1.2 1998/06/23 04:21:16 josh Exp $ */
#ifndef _EBAY_H
#define _EBAY_H

// Standard default, copy, and assignment so Lint doesn't complain;
// the intent is that these not be implemented, just defined.
#define CopyAssign(typename) \
	typename(const typename&);\
	typename& operator= (const typename&)

#define Defaults(typename)\
	typename();\
	CopyAssign(typename)


// Fixed-length types
typedef long	int32_t;
typedef short	int16_t;
typedef char	int8_t;

#ifdef _MSC_VER
#define strcasecmp strcmpi 
#endif

#endif	//_EBAY_H

