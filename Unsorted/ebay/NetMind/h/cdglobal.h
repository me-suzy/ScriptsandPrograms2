/*  *********************************************************************
     File: cdglobal.h

     SSL Plus: Security Integration Suite(tm)
     Version 2.0 -- May 7, 1998

     Copyright (c) 1996, 1997, 1998 by Consensus Development Corporation

     Portions of this software are based on SSLRef(tm) 3.0, which is
     Copyright (c) 1996 by Netscape Communications Corporation. SSLRef(tm)
     was developed by Netscape Communications Corporation and Consensus
     Development Corporation.

     In order to obtain this software, your company must have signed
     either a PRODUCT EVALUATION LICENSE (a copy of which is included in
     the file "LICENSE.PDF"), or a PRODUCT DEVELOPMENT LICENSE. These
     licenses have different limitations regarding how you are allowed to
     use the software. Before retrieving (or using) this software, you
     *must* ascertain which of these licenses your company currently
     holds. Then, by retrieving (or using) this software you agree to
     abide by the particular terms of that license. If you do not agree
     to abide by the particular terms of that license, than you must
     immediately delete this software. If your company does not have a
     signed license of either kind, then you must either contact
     Consensus Development and execute a valid license before retrieving
     (or using) this software, or immediately delete this software.

     *********************************************************************

     File: cdglobal.h   Global types, definitions, and functions for Conse


     ****************************************************************** */


#ifndef _CDGLOBAL_H_
#define _CDGLOBAL_H_ 1

#ifdef __cplusplus
extern "C" {
#endif

/* gcc on solaris defines unix, but not UNIX */
#if defined(unix)
#define UNIX 1
#endif

#if !defined(MAC) && !defined(WIN32) && !defined(UNIX) && !defined(_WIN16)
#define MAC 1
#endif

#ifdef _WIN16
    #ifndef WIN16_FAR
        #ifdef FAR
            #define WIN16_FAR FAR
        #else
            #define WIN16_FAR __far
        #endif
    #endif
#else
    #define WIN16_FAR
#endif

#ifndef UNUSED_PARAM
#define UNUSED_PARAM(v) (void)(v);
#endif

#ifndef SIMPLE_TYPES
#define SIMPLE_TYPES 1
typedef unsigned char   uint8;
typedef unsigned short  uint16;
typedef unsigned long   uint32;
typedef signed long     sint32;

typedef struct
{   uint32  length;
    uint8   *data;
    uint8   allocated;      /* This should be non-zero if the memory was allocated specifically for this Buffer */
} Buffer;
#endif

/* User provided functions */
/* Prototypes for linked functions */
extern void* CD_malloc(uint32 size);
extern void CD_free(void *block);
extern void* CD_realloc(void *block, uint32 newSize);
extern void CD_memset(void *block, uint8 value, uint32 length);
extern void CD_memcpy(void *dest, void *src, uint32 length);
extern int CD_memcmp(void *a, void *b, uint32 length);
extern uint32 CD_time(void);

#ifdef __cplusplus
}
#endif

#endif /* _CDGLOBAL_H_ */
