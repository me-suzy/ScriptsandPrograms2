/* Copyright (C) RSA Data Security, Inc. created 1990.  This is an
   unpublished work protected as such under copyright law.  This work
   contains proprietary, confidential, and trade secret information of
   RSA Data Security, Inc.  Use, disclosure or reproduction without the
   express written authorization of RSA Data Security, Inc. is
   prohibited.
 */

#ifndef _AGLOBAL_H_
#define _AGLOBAL_H_ 1

#include "bsfmacro.h"
#include "bsfplatf.h"

#ifdef __cplusplus
extern "C" {
#endif

/* POINTER defines a generic pointer type */
typedef unsigned char *POINTER;

/* UINT2 defines a two byte word */
typedef unsigned short int UINT2;

typedef struct {
  unsigned char *data;
  unsigned int len;
} ITEM;

typedef struct {
  int (RSA_CALLING_CONV *Surrender) PROTO_LIST ((POINTER));
  POINTER handle;
  POINTER reserved;
} A_SURRENDER_CTX;

/* UINT4 defines a four byte word */
#if RSA_REGISTER_SIZE == RSA_16_BIT_REGISTER || \
    RSA_REGISTER_SIZE == RSA_32_BIT_REGISTER
typedef unsigned long int UINT4;
#endif
#if RSA_REGISTER_SIZE == RSA_64_BIT_REGISTER
typedef unsigned int UINT4;
#endif

#define NULL_PTR ((POINTER)0)

#define UNUSED_ARG(x) x = *(&x);

#ifdef __cplusplus
}
#endif

#endif /* end _AGLOBAL_H_ */
