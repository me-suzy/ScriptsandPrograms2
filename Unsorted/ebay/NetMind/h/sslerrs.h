/*  *********************************************************************
     File: sslerrs.h

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

     File: sslerrs.h    Errors SSL Plus can return


     ****************************************************************** */


#ifndef _SSLERRS_H_
#define _SSLERRS_H_ 1

#ifdef __cplusplus
extern "C" {
#endif

enum
{   SSLNoErr = 0,
    SSLMemoryErr = -7000,
    SSLUnsupportedErr = -6999,
    SSLOverflowErr = -6998,
    SSLUnknownErr = -6997,
    SSLProtocolErr = -6996,
    SSLNegotiationErr = -6995,
    SSLFatalAlert = -6994,
    SSLWouldBlockErr = -6993,
    SSLIOErr = -6992,
    SSLSessionNotFoundErr = -6991,

    SSLConnectionClosedGraceful = -6990,
    SSLConnectionClosedError = -6989,

    ASNBadEncodingErr = -6988,
    ASNIntegerTooBigErr = -6987,

    X509CertChainInvalidErr = -6986,
    X509CertExpiredErr = -6985,
    X509NamesNotEqualErr = -6984,
    X509CertChainIncompleteErr = -6983,
    X509DataNotFoundErr = -6982,

    SSLBadParameterErr = -6981,

    SSLIOClosedOverrideGoodbyeKiss = -6980,

    SSLFileNotFound = -6979,
    SSLDataNotFound = -6978,
    SSLDecryptFailed = -6977,

    X509UnauthorizedCA = -6976,
    X509UnknownCriticalExtension = -6975,
    X509UnauthorizedCertificate = -6974
};

typedef int SSLErr;

#ifdef __cplusplus
}
#endif

#endif

