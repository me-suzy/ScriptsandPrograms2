/*MAN
MODULE
     port.h
DESCRIPTION
AUTHORS
     Santosh Rau
USERS
UPDATES
		05 Jan 98 SR	Moved typedefs from nmbasics.h (created by Ross)
*/

#ifndef _PORT_H_
#define _PORT_H_

/* arrange for ifdef structures to prefer POSIX to SOLARIS.  Then,
   if you want to run POSIX threads on Solaris, just -DPOSIX to
   override the native SOLARIS thread code. */
#ifdef LINUX
#define POSIX
#endif

#ifdef __cplusplus
extern "C" {
#endif

/*--------------------------------------------------------------------*/
/* All header files here */
/*--------------------------------------------------------------------*/

#if defined(SOLARIS) || defined(LINUX)
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <ctype.h>
#include <string.h>
#endif /* solaris || linux */

#ifdef LINUX
#include <sys/ioctl.h>
#include <time.h>
#include <rpc/rpc.h>
#endif

#ifdef SOLARIS
#include <sys/filio.h>
#endif

/*--------------------------------------------------------------------*/
/* General typedefs to be used on all platforms */ 
/*--------------------------------------------------------------------*/


typedef unsigned char uchar;

typedef long int NmInt32, *NmPInt32;
typedef unsigned long int NmUInt32, *NmPUInt32;
typedef short NmInt16, *NmPInt16;
typedef unsigned short NmUInt16, *NmPUInt16;

typedef float NmReal, *NmPReal;
typedef double NmDouble, *NmPDouble;
typedef long double NmQuadReal, NmPQuadReal;

typedef NmInt32 NmBool, *NmPBool;

#ifndef TRUE
#define TRUE  1
#define FALSE 0
#endif

/*--------------------------------------------------------------------*/
/* All types, defines etc */
/*--------------------------------------------------------------------*/

#if defined(WIN32)

typedef unsigned long ulong;
typedef unsigned short ushort;
#define nm_ctime_r(time, string, len) strncpy((string), ctime(time), (len))

#elif defined(SOLARIS) || defined(LINUX)

/* define winsock equivalents */
typedef int SOCKET;
typedef struct sockaddr_in SOCKADDR_IN;
typedef struct sockaddr * PSOCKADDR;
#define INVALID_SOCKET -1
#define SOCKET_ERROR -1

#ifdef SOLARIS
#define INADDR_NONE -1
#define nm_ctime_r(time, string, len) ctime_r((time), (string), (len))
#define nm_getpwnam(account, pwEntry, buffer, len, rslt) \
     (rslt) = getpwnam_r((account), (pwEntry), (buffer), (len))
#endif

#ifdef LINUX
#define nm_ctime_r(time, string, len) ctime_r((time), (string))
#define nm_getpwnam(account, pwEntry, buffer, len, rslt) \
     getpwnam_r((account), (pwEntry), (buffer), (len), &(rslt))
#endif

#endif

/*--------------------------------------------------------------------*/
/* All prototypes */ 
/*--------------------------------------------------------------------*/

#ifdef __cplusplus
}
#endif

#endif /* _PORT_H_ */

