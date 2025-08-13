/* Copyright (C) RSA Data Security, Inc. created 1997.  This is an
   unpublished work protected as such under copyright law.  This work
   contains proprietary, confidential, and trade secret information of
   RSA Data Security, Inc.  Use, disclosure or reproduction without the
   express written authorization of RSA Data Security, Inc. is
   prohibited.
 */

#ifndef _BSAFE_PLATFORM_H_
#define _BSAFE_PLATFORM_H_ 1

/* The include file bsfmacro.h contains all the alternatives for macro
     definitions. */
#include "bsfmacro.h"

#define _ITEM_ 1
#define RSA_STD_MEM_FUNCS  RSA_ENABLED
#define RSA_STD_ALLOC_FUNCS  RSA_ENABLED

#if RSA_PLATFORM == RSA_I386_486
#include "bsplti32.h"
#else

#ifdef __cplusplus
extern "C" {
#endif

/* Is this BSAFE to use full, domestic strength key sizes or export
     strength? */
#define RSA_BSAFE_DESTINATION  RSA_DOMESTIC_VERSION

/* The default calling convention is C, the macro RSA_CALLING_CONV should be
     "nothing".
     If the application is using the PASCAL calling convention then
     RSA_CALLING_CONV should be defined approrpriately so that the
     application can call the public API accordingly.
   For example, the PASCAL calling convention on Windows 95 is

#define RSA_CALLING_CONV ???

 */
#define RSA_CALLING_CONV
/* temporary, when all files have converted, take this next line out. */
#define CALL_CONV

/* RSA_GLOBAL_FUNCTION_POINTERS should be set to RSA_ENABLED if and only if
     the compiler can intitialize global or static function pointers.
     If not (such as with MAC code resource), function pointers can be
     set only at run time through an initializing routine. If that is the
     case, set RSA_GLOBAL_FUNCTION_POINTERS to RSA_DISABLED.
 */
#define RSA_GLOBAL_FUNCTION_POINTERS RSA_ENABLED

/* Set RSA_ENDIAN to RSA_LITTLE_ENDIAN for little endian machines (Intel,
     for instance). Set it to RSA_BIG_ENDIAN for big endian machines (Mac and
     most UNIX, for example).
 */
#define RSA_ENDIAN RSA_LITTLE_ENDIAN

/* Set RSA_FETCH_UINT4 to RSA_FETCH_ALIGNED_ONLY if the CPU can fetch UINT4
     values only from byte-aligned addresses. Set it to RSA_FETCH_UNALIGNED
     if it is possible to fetch from unaligned addresses. Unaligned fetches
     enables speedups in the handling of 4 and 8 byte quantities (e.g., in
     CBC). Old Sun SPARC chips, for instance, cannot do this.
 */
#define RSA_FETCH_UINT4 RSA_FETCH_ALIGNED_ONLY

/* RSA_REGISTER_SIZE defines the bit size of a register, or word. For instance,
     a Pentium's register is 32 bits, so set the value to RSA_32_BIT_REGISTER.
     On the other hand, an Alpha's register is 64 bits, so set the value to
     RSA_64_BIT_REGISTER.
 */
#define RSA_REGISTER_SIZE RSA_32_BIT_REGISTER

/* If the platform accepts prototyping in the function definition, set
     RSA_PROTOTYPES to RSA_ENABLED. If not, set the value to RSA_DISABLED.
 */
#define RSA_PROTOTYPES RSA_ENABLED

/* PROTO_LIST is defined depending on how RSA_PROTOTYPES is defined. If
     enabled, then PROTO_LIST returns the list, otherwise it returns an
     empty list.
 */
#if RSA_PROTOTYPES == RSA_ENABLED
#define PROTO_LIST(list) list
#endif
#if RSA_PROTOTYPES == RSA_DISABLED
#define PROTO_LIST(list) ()
#endif

/* On the outside chance that some strange platform exists that does not
     use 8-bit bytes, define BITS_PER_BYTE.
 */
#define RSA_BITS_PER_BYTE 8

/* The following are all the optimizations we have made so far. An optimization
     may exist on a certain platform or it may not. If the optimization exists,
     set the macro to the platform macro. If not leave it as C code.
     For instance, on Alpha NT, there is a CMP multiply  optimization, but
     no MD5 transform optimization. Hence, set
     RSA_CMP_MULT_WORDS_OPT to RSA_ALPHA_DEC_NT_MSVC40
     but leave
     RSA_MD5_TRANSFORM_OPT at RSA_C_CODE
 */
#define RSA_CMP_WORD_SIZE         RSA_C_CODE
#define RSA_CMP_MULT_WORDS_OPT    RSA_C_CODE
#define RSA_CMP_VECTOR_MULT_OPT   RSA_C_CODE
#define RSA_CMP_ADD_IN_TRACE_OPT  RSA_C_CODE
#define RSA_CMP_MONT_PRODUCT_OPT  RSA_C_CODE
#define RSA_CMP_MONT_SQUARE_OPT   RSA_C_CODE

#define RSA_DES_ENCRYPT_OPT			RSA_C_CODE
#define RSA_DES_INIT_ENCRYPT_OPT		RSA_C_CODE
#define RSA_DES_INIT_DECRYPT_OPT		RSA_C_CODE		
	
#define RSA_RC2_ENCRYPT_OPT  RSA_C_CODE
#define RSA_RC2_DECRYPT_OPT  RSA_C_CODE
#define RSA_RC2_INIT_OPT  RSA_C_CODE	

#define RSA_RC4_UPDATE_OPT  RSA_C_CODE

#define RSA_RC5_ENCRYPT_OPT  RSA_C_CODE
#define RSA_RC5_DECRYPT_OPT  RSA_C_CODE

#define RSA_RC5_64ENCRYPT_OPT RSA_C_CODE
#define RSA_RC5_64DECRYPT_OPT RSA_C_CODE
#define RSA_RC5_INIT_64_OPT RSA_C_CODE	
	
#define RSA_MD5_TRANSFORM_OPT  RSA_C_CODE

#define RSA_MAC_UPDATE_OPT  RSA_C_CODE

#define RSA_SHA1_UPDATE_OPT	RSA_C_CODE

/* The following are included to smooth the transition. Take them out
     when all files are converted to the new style.
 */
#define BITS_PER_BYTE  RSA_BITS_PER_BYTE
#define RSA_PRIME_BITS(modulusBits)  (((modulusBits) + 1) / 2)
#define RSA_PRIME_LEN(modulusBits)   \
  ((RSA_PRIME_BITS (modulusBits) + RSA_BITS_PER_BYTE - 1) \
                                                   / RSA_BITS_PER_BYTE)
#define BITS_TO_LEN(modulusBits)     \
  (((modulusBits) + RSA_BITS_PER_BYTE - 1) / RSA_BITS_PER_BYTE)
#define MAX_RSA_MODULUS_BITS         2048
#define MIN_RSA_MODULUS_BITS         256
#define A_MIN_DIGEST_LEN             16
#define A_MAX_DIGEST_LEN             32
#define GLOBAL_FUNCTION_POINTERS     1

#ifdef __cplusplus
}
#endif
#endif /* RSA_PLATFORM == I386 */
#endif /* _BSAFE_PLATFORM_H_ */
