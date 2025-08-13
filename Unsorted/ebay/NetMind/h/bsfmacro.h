/* Copyright (C) RSA Data Security, Inc. created 1997.  This is an
   unpublished work protected as such under copyright law.  This work
   contains proprietary, confidential, and trade secret information of
   RSA Data Security, Inc.  Use, disclosure or reproduction without the
   express written authorization of RSA Data Security, Inc. is
   prohibited.
 */

/* This file lists all the possible values macros may take on.
 */

#ifndef _BSAFE_MACRO_H_
#define _BSAFE_MACRO_H_ 1

#ifdef __cplusplus
extern "C" {
#endif

#define RSA_DISABLED  0
#define RSA_ENABLED   1

#define RSA_BIG_ENDIAN     0
#define RSA_LITTLE_ENDIAN  1

#define RSA_DOMESTIC_VERSION  0
#define RSA_EXPORT_VERSION    1

#define RSA_16_BIT_REGISTER  16
#define RSA_32_BIT_REGISTER  32
#define RSA_64_BIT_REGISTER  64

#define RSA_FETCH_ALIGNED_ONLY  0
#define RSA_FETCH_UNALIGNED     1

/* The following are the values defining the platform and compiler.
     RSA <chip/machine> <OS/compiler> [register size]
     They are to be used to indicate which optimizations are available.
     The default is C code, so the first value is a platform independent macro.
 */
#define RSA_C_CODE                   0

#define RSA_INTEL_MSVC15_16_BIT     10
#define RSA_INTEL_MSVC20_32_BIT     12
#define RSA_INTEL_MSVC40_32_BIT     14
#define RSA_INTEL_BORLAND45_16_BIT  20
#define RSA_INTEL_BORLAND45_32_BIT  22
#define RSA_INTEL_LINUX21_32_BIT    30
#define RSA_INTEL_LINUX30_32_BIT    32
#define RSA_INTEL_SCO50_32_BIT      40
#define RSA_SPARC_SUN_OS_412        100
#define RSA_SPARC_SUN_SOLARIS25     102
#define RSA_MAC_68K_CODE_WARRIER    120
#define RSA_MAC_68K_SYMANTEC        122
#define RSA_MAC_PPC_CODE_WARRIER    130
#define RSA_MAC_PPC_SYMANTEC        132
#define RSA_ALPHA_DEC_OSF_UNIX      140
#define RSA_ALPHA_DEC_NT_MSVC40     142
#define RSA_ALPHA_DEC_VMS           144
#define RSA_MIPS_R2000_IRIX53       160
#define RSA_MIPS_R4000_IRIX60       162
#define RSA_HP_UX_10                180
#define RSA_AIX_414                 200
#define RSA_I386_486		    210
#ifdef __cplusplus
}
#endif

#endif /* _BSAFE_MACRO_H_ */
