/*  *********************************************************************
     File: ssl.h

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

     File: ssl.h        Main header for use of SSL Plus

     This file should be the only one directly included by the SSL
     application; all data structures and routines not found in here or in
     the headers this includes are private and not for use by clients.

     ****************************************************************** */


#ifndef _SSL_H_
#define _SSL_H_

#include "cdglobal.h"

#ifndef _SSLERRS_H_
#include "sslerrs.h"
#endif

#ifdef __cplusplus
extern "C" {
#endif

typedef struct
{   uint32  high;
    uint32  low;
} uint64;

#ifndef _SSLDEBUG_H_
#include "ssldebug.h"
#endif

#ifndef _SSLCRYPT_H_
#include "sslcrypt.h"
#endif

#ifndef _ASN1OIDS_H_
#include "asn1oids.h"
#endif

#define SSL_NULL_WITH_NULL_NULL                 0x0000
#define SSL_RSA_WITH_NULL_MD5                   0x0001
#define SSL_RSA_WITH_NULL_SHA                   0x0002
#define SSL_RSA_EXPORT_WITH_RC4_40_MD5          0x0003
#define SSL_RSA_WITH_RC4_128_MD5                0x0004
#define SSL_RSA_WITH_RC4_128_SHA                0x0005
#define SSL_RSA_EXPORT_WITH_RC2_CBC_40_MD5      0x0006
#define SSL_RSA_WITH_IDEA_CBC_SHA               0x0007
#define SSL_RSA_EXPORT_WITH_DES40_CBC_SHA       0x0008
#define SSL_RSA_WITH_DES_CBC_SHA                0x0009
#define SSL_RSA_WITH_3DES_EDE_CBC_SHA           0x000A
#define SSL_DH_DSS_EXPORT_WITH_DES40_CBC_SHA    0x000B
#define SSL_DH_DSS_WITH_DES_CBC_SHA             0x000C
#define SSL_DH_DSS_WITH_3DES_EDE_CBC_SHA        0x000D
#define SSL_DH_RSA_EXPORT_WITH_DES40_CBC_SHA    0x000E
#define SSL_DH_RSA_WITH_DES_CBC_SHA             0x000F
#define SSL_DH_RSA_WITH_3DES_EDE_CBC_SHA        0x0010
#define SSL_DHE_DSS_EXPORT_WITH_DES40_CBC_SHA   0x0011
#define SSL_DHE_DSS_WITH_DES_CBC_SHA            0x0012
#define SSL_DHE_DSS_WITH_3DES_EDE_CBC_SHA       0x0013
#define SSL_DHE_RSA_EXPORT_WITH_DES40_CBC_SHA   0x0014
#define SSL_DHE_RSA_WITH_DES_CBC_SHA            0x0015
#define SSL_DHE_RSA_WITH_3DES_EDE_CBC_SHA       0x0016
#define SSL_DH_anon_EXPORT_WITH_RC4_40_MD5      0x0017
#define SSL_DH_anon_WITH_RC4_128_MD5            0x0018
#define SSL_DH_anon_EXPORT_WITH_DES40_CBC_SHA   0x0019
#define SSL_DH_anon_WITH_DES_CBC_SHA            0x001A
#define SSL_DH_anon_WITH_3DES_EDE_CBC_SHA       0x001B
#define SSL_FORTEZZA_DMS_WITH_NULL_SHA          0x001C
#define SSL_FORTEZZA_DMS_WITH_FORTEZZA_CBC_SHA  0x001D
#define SSL_RSA_WITH_RC2_CBC_MD5                0xFF80  /* These are included to provide tags for */
#define SSL_RSA_WITH_IDEA_CBC_MD5               0xFF81  /*  SSL 2 cipher kinds which are not specified */
#define SSL_RSA_WITH_DES_CBC_MD5                0xFF82  /*  for SSL 3 */
#define SSL_RSA_WITH_3DES_EDE_CBC_MD5           0xFF83

typedef struct
{   uint32  length;
    uint8   *data;
} SSLBuffer;

typedef enum
{   SSL_ServerSide = 1,
    SSL_ClientSide = 2
} SSLProtocolSide;

typedef enum {
    SSL_CompleteIO = 0,
    SSL_PartialIO = 1
} SSLIOStyle;

typedef enum
{   SSL_Version_Undetermined = 0,
    SSL_Version_3_0_With_2_0_Hello = 100,
    SSL_Version_3_0_Only = 101,
    SSL_Version_2_0 = 0x0002,
    SSL_Version_3_0 = 0x0300
} SSLProtocolVersion;

typedef enum
{   SSL_AllCrypto,
    SSL_StrongCryptoOnly,
    SSL_ExportCryptoOnly,
    SSL_ServerGatedCrypto
} SSLCryptoStrength;

struct                      SSLContext;
typedef struct SSLContext   SSLContext;

typedef struct SSLCertificateChain
{   struct SSLCertificateChain  *next;
    SSLBuffer                   berCert;
    uint32                      identifier;
    void                        *certData;
} SSLCertificateChain;

typedef struct
{   int             oid;
    uint32          level;
    int             tag;
    SSLBuffer       avaData;
} SSLAVA;

typedef struct SSLAVAList
{   struct SSLAVAList   *next;
    SSLAVA              ava;
} SSLAVAList;

typedef struct
{   int             oid;
    int             critical;
    SSLBuffer       extensionData;
} SSLExtension;

typedef struct SSLExtensionList
{   struct SSLExtensionList     *next;
    SSLExtension                ext;
} SSLExtensionList;

typedef struct {
    int             oid;
    int             tag;
    SSLBuffer       data;
} SSLAttribute;

typedef struct SSLAttributeList {
    struct SSLAttributeList *next;
    SSLAttribute            attribute;
} SSLAttributeList;

typedef struct
{   SSLAVAList              *name;
    SSLRSAPublicKey         key;
    struct SSLAttributeList *attributes;
} SSLCertificateRequest;

typedef struct SSLCiphersuiteInfo {
    uint16      id;
    uint16      keyBits;
    uint8       exportable;
    const char  *suiteName;
    const char  *keyExchangeMethod;
    const char  *cipherName;
    const char  *cipherMode;
    const char  *hashName;
} SSLCiphersuiteInfo;


/* User provided functions */
/* Callback function pointer types */
typedef SSLErr (*SSLIOFunc) (SSLBuffer data, uint32 *processed, void *connRef);
typedef SSLErr (*SSLRandomFunc) (SSLBuffer data, void *randomRef);
typedef SSLErr (*SSLSurrenderFunc) (void *surrenderRef);
typedef SSLErr (*SSLAddSessionFunc) (SSLBuffer sessionKey, SSLBuffer sessionData, void *sessionRef);
typedef SSLErr (*SSLGetSessionFunc) (SSLBuffer sessionKey, SSLBuffer *sessionData, void *sessionRef);
typedef SSLErr (*SSLDeleteSessionFunc) (SSLBuffer sessionKey, void *sessionRef);
typedef SSLErr (*SSLCheckCertificateFunc) (int certCount, SSLBuffer *derCerts, void *checkCertificateRef);
typedef SSLErr (*SSLCheckCertificateChainFunc) (SSLCertificateChain *certs, uint32 trustedCert, SSLErr validateErr, void *checkCertificateRef);
typedef SSLErr (*SSLCipherNotifyFunc) (uint16 cipherSuite, void *cipherNotifyRef);

/* SSLREF API */
/* Context creation & deletion APIs */
uint32 SSLContextSize(void);
SSLErr SSLInitContext(SSLContext *ctx);
SSLErr SSLDeleteContext(SSLContext *ctx);
SSLErr SSLDuplicateContext(SSLContext *src, SSLContext *dest, void *ioRef);

/* Connection configuration APIs */
SSLErr SSLSetProtocolSide(SSLContext *ctx, SSLProtocolSide side);
SSLErr SSLSetProtocolVersion(SSLContext *ctx, SSLProtocolVersion version);
SSLErr SSLSetPrivateKey(SSLContext *ctx, SSLRSAPrivateKey *privKey);
SSLErr SSLSetExportPrivateKey(SSLContext *ctx, SSLRSAPrivateKey *privKey);
SSLErr SSLSetDHAnonParams(SSLContext *ctx, SSLDHParams *dhAnonParams);
SSLErr SSLSetRequestClientCert(SSLContext *ctx, int requestClientCert);
SSLErr SSLAddCertificate(SSLContext *ctx, SSLBuffer derCert, int parent, int complete);
SSLErr SSLAddDistinguishedName(SSLContext *ctx, SSLBuffer derDN);
SSLErr SSLAddTrustedCertificate(SSLContext *ctx, SSLBuffer berCert, uint32 identifier);
SSLErr SSLSetPeerID(SSLContext *ctx, SSLBuffer peerID);

/* Context configuration APIs */
SSLErr SSLSetRandomFunc(SSLContext *ctx, SSLRandomFunc random);
SSLErr SSLSetRandomRef(SSLContext *ctx, void* randomRef);
SSLErr SSLSetSurrenderFunc(SSLContext *ctx, SSLSurrenderFunc surrender);
SSLErr SSLSetSurrenderRef(SSLContext *ctx, void* surrenderRef);
SSLErr SSLSetReadFunc(SSLContext *ctx, SSLIOFunc read);
SSLErr SSLSetWriteFunc(SSLContext *ctx, SSLIOFunc write);
SSLErr SSLSetIORef(SSLContext *ctx, void *ioRef);
SSLErr SSLSetAddSessionFunc(SSLContext *ctx, SSLAddSessionFunc addSession);
SSLErr SSLSetGetSessionFunc(SSLContext *ctx, SSLGetSessionFunc getSession);
SSLErr SSLSetDeleteSessionFunc(SSLContext *ctx, SSLDeleteSessionFunc deleteSession);
SSLErr SSLSetSessionRef(SSLContext *ctx, void *sessionRef);
SSLErr SSLSetCheckCertificateFunc(SSLContext *ctx, SSLCheckCertificateFunc checkCertificate);
SSLErr SSLSetCheckCertificateRef(SSLContext *ctx, void *checkCertificateRef);
SSLErr SSLSetCheckCertificateChainFunc(SSLContext *ctx, SSLCheckCertificateChainFunc checkCertChain);
SSLErr SSLSetCipherSuites(SSLContext *ctx, SSLBuffer *ciphers);
SSLErr SSLSetCryptographicStrength(SSLContext *ctx, SSLCryptoStrength strength);
SSLErr SSLSetCipherNotifyFunc(SSLContext *ctx, SSLCipherNotifyFunc cipherNotify);
SSLErr SSLSetCipherNotifyRef(SSLContext *ctx, void *cipherNotifyRef);

/* Context access APIs */
SSLErr SSLGetProtocolVersion(SSLContext *ctx, SSLProtocolVersion *version);
SSLErr SSLGetPeerCertificateChainLength(SSLContext *ctx, int *chainLen);
SSLErr SSLGetPeerCertificate(SSLContext *ctx, int index, SSLBuffer *derCert);
SSLErr SSLGetPeerCertificateRef(SSLContext *ctx, int index, void **certDataPtr);
SSLErr SSLGetTrustedCertificateRef(SSLContext *ctx, uint32 identifier, void **certDataPtr);
SSLErr SSLGetNegotiatedCipher(SSLContext *ctx, uint16 *cipherSuite);
SSLErr SSLGetWritePendingSize(SSLContext *ctx, uint32 *waitingBytes);
SSLErr SSLGetReadPendingSize(SSLContext *ctx, uint32 *waitingBytes);

/* I/O APIs */
SSLErr SSLHandshake(SSLContext *ctx);
SSLErr SSLServiceWriteQueue(SSLContext *ctx);
SSLErr SSLWrite(void *data, uint32 *length, SSLContext *ctx);
SSLErr SSLRead(void *data, uint32 *length, SSLContext *ctx);
SSLErr SSLClose(SSLContext *ctx);
SSLErr SSLSetIOSemantics(SSLContext *ctx, SSLIOStyle ioStyle);
SSLErr SSLRequestRenegotiation(SSLContext *ctx);

/* Yielding utility for use by callbacks */
SSLErr SSLCallSurrenderFunc(SSLContext *ctx);

/* Certificate data extraction APIs */
SSLErr SSLExtractSubjectDNField(void *certData, int oid, SSLAVA *avaData);
SSLErr SSLCountSubjectDNFields(void *certData, uint32 *fieldCount);
SSLErr SSLExtractSubjectDNFieldIndex(void *certData, uint32 fieldIndex, SSLAVA *avaData);
SSLErr SSLExtractExtension(void *certData, int oid, SSLExtension *extensionData);
SSLErr SSLCountExtensions(void *certData, uint32 *extensionCount);
SSLErr SSLExtractExtensionIndex(void *certData, uint32 extensionIndex, SSLExtension *extensionData);
SSLErr SSLExtractValidityDates(void *certData, uint32 *start, uint32 *end);
SSLErr SSLExtractSerialNumber(void *certData, SSLBuffer *serNo);

/* API for adding entries to global OID dictionary */
SSLErr SSLAddOIDValue( int oidValue, char *oid);

/* Certificate utility functions */
SSLErr SSLLoadLocalIdentity(SSLContext *ctx, char *identityFile, char *passPhrase);
SSLErr SSLLoadTrustedCertificateFile(SSLContext *ctx, char *certFile, int *numberLoaded);
SSLErr SSLEncodeCertificateRequest(SSLCertificateRequest *requestData, SSLRSAPrivateKey *privKey,
            SSLBuffer *derRequest);
SSLErr SSLFormatCertificateRequest( SSLBuffer *derRequest, SSLBuffer *asciiRequest );

/* Key utility functions */
SSLErr SSLGeneratePrivateKey(int size, SSLRSAPublicKey *pubKey, SSLRSAPrivateKey *privKey,
                             SSLRandomFunc randomFunc, void *randomRef);
SSLErr SSLGenerateExportPrivateKey(SSLRSAPrivateKey *privKey, SSLRandomFunc randomFunc,
                                   void *randomRef);
SSLErr SSLFormatPrivateKey( SSLContext *ctx, SSLRSAPrivateKey *privKey,
                            char *passPhrase, SSLBuffer *asciiOutput );
SSLErr SSLConstructPrivateKey( SSLBuffer *encodedKey, SSLRSAPrivateKey *privKey,
                               char *passPhrase );

/* Cipher utility functions */
SSLErr SSLGetCiphersuiteInfo( uint16 ciphersuite, SSLCiphersuiteInfo *result );
SSLErr SSLGetIndexedCiphersuiteInfo( uint32 index, SSLCiphersuiteInfo *result );
uint16 SSLCiphersuiteNameToNumber( const char *ciphersuiteName );
uint32 SSLGetCiphersuiteCount( void );



#ifdef __cplusplus
}
#endif

#endif /* _SSL_H_ */
