/*  *********************************************************************
     File: sslcrypt.h

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

     File: sslcrypt.h   Cryptography-related typedefs and #includes


     ****************************************************************** */


#ifndef _SSLCRYPT_H_
#define _SSLCRYPT_H_

#define PROTOTYPES 1

#ifndef _GLOBAL_H_
#include "aglobal.h"
#endif

#ifndef _BSAFE_H_
#include "bsafe.h"
#endif

#ifdef __cplusplus
extern "C" {
#endif

typedef B_ALGORITHM_OBJ SSLDHParams;

typedef B_KEY_OBJ SSLRSAPrivateKey;
typedef B_KEY_OBJ SSLRSAPublicKey;
typedef B_ALGORITHM_OBJ SSLRandomCtx;

#define NO_RAND ((B_ALGORITHM_OBJ)0)

#ifdef __cplusplus
}
#endif

#endif /* _SSLCRYPT_H_ */

