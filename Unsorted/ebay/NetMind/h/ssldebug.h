/*  *********************************************************************
     File: ssldebug.h

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

     File: ssldebug.h   Debug utilities

     Simple message printing and data dumping functions for use when the
     DEBUG macro is non-zero.

     ****************************************************************** */


#ifndef _SSLDEBUG_H_
#define _SSLDEBUG_H_ 1

#ifdef __cplusplus
extern "C" {
#endif

#ifndef DEBUG
    #define DEBUG   0
#endif /* DEBUG */

#ifndef DEBUGASSERT
    #if DEBUG >= 1
        #define DEBUGASSERT 1
    #endif /* DEBUG >= 1 */
#endif /* DEBUGASSERT */

#ifndef DEBUGERR
    #if DEBUG >= 2
        #define DEBUGERR 1
    #endif /* DEBUG >= 2 */
#endif /* DEBUGERR */

#ifndef DEBUGMESSAGE
    #if DEBUG >= 3
        #define DEBUGMESSAGE 1
    #endif /* DEBUG >= 3 */
#endif /* DEBUGMESSAGE */

#ifndef DEBUGDATA
    #if DEBUG >= 4
        #define DEBUGDATA 1
    #endif /* DEBUG >= 4 */
#endif /* DEBUGDATA */

#if DEBUGASSERT
    #define CDC_ASSERT(val)     do {if (!(val)) AssertMessage("Assert failed: '" #val "'", __FILE__, __LINE__);} while (0)
    #define ASSERTPTR(val)  do {if ((val) == 0) AssertMessage("Pointer assert failed: '" #val "'", __FILE__, __LINE__);} while (0)
    #define ASSERTMSG(msg)  AssertMessage("Assert: " msg, __FILE__, __LINE__)
#else
    #define CDC_ASSERT(val)     ((void)0)
    #define ASSERTPTR(val)  ((void)0)
    #define ASSERTMSG(msg)  ((void)0)
#endif

#if DEBUGERR
    #define ERR(err)    DebugError(err, __FILE__, __LINE__)
#else
    #define ERR(err)    (err)
#endif

#if DEBUGMESSAGE
    #define DEBUGMSG(msg)                   DebugMessage(msg, __FILE__, __LINE__)
    #define DEBUGVAL1(msg, v1)              DebugValMessage((msg), __FILE__, __LINE__, (uint32)(v1), 0, 0, 0)
    #define DEBUGVAL2(msg, v1, v2)          DebugValMessage((msg), __FILE__, __LINE__, (uint32)(v1), (uint32)(v2), 0, 0)
    #define DEBUGVAL3(msg, v1, v2, v3)      DebugValMessage((msg), __FILE__, __LINE__, (uint32)(v1), (uint32)(v2), (uint32)(v3), 0)
    #define DEBUGVAL4(msg, v1, v2, v3, v4)  DebugValMessage((msg), __FILE__, __LINE__, (uint32)(v1), (uint32)(v2), (uint32)(v3), (uint32)(v4))
#else
    #define DEBUGMSG(msg)                   ((void)0)
    #define DEBUGVAL1(msg, v1)              ((void)0)
    #define DEBUGVAL2(msg, v1, v2)          ((void)0)
    #define DEBUGVAL3(msg, v1, v2, v3)      ((void)0)
    #define DEBUGVAL4(msg, v1, v2, v3, v4)  ((void)0)
#endif

#if DEBUGDATA
    #define DUMP_BUFFER_PTR(marker, value, buffer) DebugDumpDataValue((marker), (uint32)(value), (buffer) .data, (buffer) .length)
    #define DUMP_BUFFER_NAME(name, buffer) DebugDumpDataName((name), (buffer) .data, (buffer) .length)
    #define DUMP_DATA_PTR(marker, value, data, length) DebugDumpDataValue((marker), (uint32)(value), (data), (length))
    #define DUMP_DATA_NAME(name, data, length) DebugDumpDataName((name), (data), (length))
#else
    #define DUMP_BUFFER_PTR(marker, value, buffer) ((void)0)
    #define DUMP_BUFFER_NAME(name, buffer) ((void)0)
    #define DUMP_DATA_PTR(marker, value, data, length) ((void)0)
    #define DUMP_DATA_NAME(name, data, length) ((void)0)
#endif

void AssertMessage(char *message, char *file, int line);
SSLErr DebugError(SSLErr err, char *file, int line);
void DebugMessage(char *message, char *file, int line);
void DebugValMessage(char *message, char *file, int line, uint32 v1, uint32 v2, uint32 v3, uint32 v4);
void DebugDumpDataValue(char *message, uint32 value, void *data, uint32 length);
void DebugDumpDataName(char *name, void *data, uint32 length);

#if defined(_MSC_VER)       /* MSVC */
#pragma warning( disable : 4127)    /* Constant conditional; triggered by CDC_ASSERT() macro */
#pragma warning( disable : 4514)    /* Following triggered by Windows headers */
#pragma warning( disable : 4201)
#pragma warning( disable : 4115)
#pragma warning( disable : 4214)
#endif

#ifdef __cplusplus
}
#endif

#endif /* _SSLDEBUG_H_ */

