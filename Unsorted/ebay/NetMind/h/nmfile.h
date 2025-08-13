/*
 * File: nmfile.h
 *
 * Portable file I/O interface, modeled on low-level Unix
 * file I/O.
 */

#ifndef _NM_FILE_H_
#define _NM_FILE_H_

#ifdef WIN32
#include <windows.h>
typedef HANDLE NmFileHandle;

/* see module documentation */
#ifndef O_CREAT
#define O_CREAT  GENERIC_ALL
#endif

#ifndef O_RDONLY
#define O_RDONLY GENERIC_READ
#endif

#ifndef O_WRONLY
#define O_WRONLY GENERIC_WRITE
#endif

#ifndef O_RDWR
#define O_RDWR   GENERIC_READ | GENERIC_WRITE
#endif

#else
#include <fcntl.h>		/* for O_RDWR, etc */
typedef int NmFileHandle;
#define INVALID_HANDLE_VALUE -1
#endif

#define FILE_BUF_SIZE        1024

#include "nmtypes.h"

#ifdef __cplusplus
extern "C" {
#endif

NMEXPORT NmBool nm_open_file(const char * file, ulong oflag, NmFileHandle * handle);
NMEXPORT NmBool nm_close_file(NmFileHandle handle);
NMEXPORT long nm_read_file(NmFileHandle handle, void * what, ulong size);
NMEXPORT long nm_write_file(NmFileHandle handle, void * what, ulong size);
NMEXPORT NmBool nm_copy_file(const char * src, const char * dest);
NMEXPORT NmBool nm_move_file(const char * src, const char * dest);
NMEXPORT NmBool nm_load_file(NmFileHandle fh, char ** buf, ulong * len);
NMEXPORT NmBool nm_access(const char * path, int mode);

#ifdef __cplusplus
}
#endif

#endif /* _NM_FILE_H_ */
