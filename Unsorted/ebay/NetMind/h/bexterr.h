/* Copyright (C) RSA Data Security, Inc. created 1996.  This is an
   unpublished work protected as such under copyright law.  This work
   contains proprietary, confidential, and trade secret information of
   RSA Data Security, Inc.  Use, disclosure or reproduction without the
   express written authorization of RSA Data Security, Inc. is
   prohibited.
 */

#ifndef _BEXTERR_H_
#define _BEXTERR_H_ 1

#include "resizeob.h"

typedef struct B_ExtendedError {
  POINTER AM;
  ResizeContext errorContext;
} B_ExtendedError;

void B_ExtendedErrorDestructor PROTO_LIST ((B_ExtendedError *));
void B_ExtendedErrorConstructor PROTO_LIST ((B_ExtendedError *));
#endif
