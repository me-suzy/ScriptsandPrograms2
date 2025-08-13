#ifndef _NM_EXPORT_H_
#define _NM_EXPORT_H_

/* SR - for DLL support */
#if defined(WIN32)

#if defined (BUILD_DLL)
#define NMEXPORT __declspec(dllexport)

#elif defined (BUILD_STATIC)
#define NMEXPORT

#else
#define NMEXPORT __declspec(dllimport)
#endif  // BUILD_DLL

#else /* for Solaris */

#define NMEXPORT

#endif  /* if defined WIN32 */

#endif 
