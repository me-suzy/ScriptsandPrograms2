/*  *********************************************************************
     File: asn1oids.h

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

     File: asn1oids.h   A table of ASN.1 object IDs

     Maps DER-encoded ASN.1 object IDs to enumerated values, allowing them
     to be compared more easily.

     ****************************************************************** */


#ifndef _ASN1OIDS_H_
#define _ASN1OIDS_H_ 1

#ifdef __cplusplus
extern "C" {
#endif

enum
{   OID_noMatch = 0,
    OID_md2,
    OID_md4,
    OID_md5,
    OID_rsaEncryption,
    OID_md2WithRSA,
    OID_md4WithRSA,
    OID_md5WithRSA,
    OID_sha1WithRSA,
    OID_dhKeyAgreement,
    OID_pbeWithMD2AndDES_CBC,
    OID_pbeWithMD5AndDES_CBC,
    OID_emailAddress,
    OID_unstructuredName,
    OID_contentType,
    OID_messageDigest,
    OID_signingTime,
    OID_counterSignature,
    OID_challengePassword,
    OID_unstructuredAddress,
    OID_extendedCertificateAttributes,
    OID_commonName,
    OID_surName,
    OID_serialNumber,
    OID_countryName,
    OID_localityName,
    OID_stateProvinceName,
    OID_streetAddress,
    OID_organizationName,
    OID_organizationalUnitName,
    OID_title,
    OID_description,
    OID_businessCategory,
    OID_postalAddress,
    OID_postalCode,
    OID_postOfficeBox,
    OID_physicalDeliveryOfficeName,
    OID_telephoneNumber,
    OID_telexNumber,
    OID_telexTerminalIdentifier,
    OID_facsimileTelephoneNumber,
    OID_x_121Address,
    OID_internationalISDNNumber,
    OID_registeredAddress,
    OID_destinationIndicator,
    OID_preferredDeliveryMethod,
    OID_presentationAddress,
    OID_supportedApplicationContext,
    OID_member,
    OID_owner,
    OID_roleOccupant,

    OID_mysteryPKCS7_PKCS5,

    OID_netscapeCertType,
    OID_netscapeBaseURL,
    OID_netscapeRevocationURL,
    OID_netscapeCARevocationURL,
    OID_netscapeCertRenewalURL,
    OID_netscapeCAPolicyURL,
    OID_netscapeSSLServerName,
    OID_netscapeComment,

    OID_subjectDirectoryAttributes,
    OID_subjectKeyIdentifier,
    OID_keyUsage,
    OID_privateKeyUsagePeriod,
    OID_subjectAltName,
    OID_issuerAltName,
    OID_basicConstraints,
    OID_crlNumber,
    OID_crlReason,
    OID_holdInstructionCode,
    OID_invalidityDate,
    OID_deltaCRLIndicator,
    OID_issuingDistributionPoint,
    OID_nameConstraints,
    OID_certificatePolicies,
    OID_policyMappings,
    OID_policyConstraints,
    OID_authorityKeyIdentifier,
    OID_extendedKeyUsage,

    OID_pkixSubjectInfoAccess,
    OID_pkixAuthorityInfoAccess,
    OID_pkixCPS,
    OID_pkixUserNotice,
    OID_pkixKPServerAuth,
    OID_pkixKPClientAuth,
    OID_pkixKPCodeSigning,
    OID_pkixKPEmailProtection,
    OID_pkixKPIPSECEndSystem,
    OID_pkixKPIPSECTunnel,
    OID_pkixKPIPSECUser,
    OID_pkixKPTimeStamping,

    OID_netscapeKPStepUp,
    OID_microsoftKPServerGatedCrypto
};

enum
{   TagBOOLEAN              = 1,
    TagINTEGER              = 2,
    TagBIT_STRING           = 3,
    TagOCTET_STRING         = 4,
    TagNULL                 = 5,
    TagOBJECT_IDENTIFIER    = 6,
    TagOBJECT_DESCRIPTOR    = 7,
    TagEXTERNAL             = 8,
    TagREAL                 = 9,
    TagENUMERATED           = 10,
    TagSEQUENCE             = 16,
    TagSET                  = 17,
    TagNumericString        = 18,
    TagPrintableString      = 19,
    TagT61String            = 20,
    TagVideotexString       = 21,
    TagIA5String            = 22,
    TagUTCTime              = 23,
    TagGraphicString        = 25,
    TagVisibleString        = 26,
    TagGeneralString        = 27
};

#ifdef __cplusplus
}
#endif

#endif /* _ASN1OIDS_H_ */
