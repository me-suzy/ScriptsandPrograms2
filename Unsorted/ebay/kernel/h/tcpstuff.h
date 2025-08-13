/*	$Id: tcpstuff.h,v 1.2 1998/06/23 04:29:17 josh Exp $	*/
int init_connection(const char *, const char *, int = 0); 	// open service on machine
void getmachinename(char *);				// get machine name (with domain)
void getdomainname(char *);				// get domain name 
int sendstring(int, const char *, int); 		// send string <name> on <socket>
							// with options <options>
int sendstring(int, int, const char *, ...);		// send string, but with same format
							// as printf
int getline(int, char *);				// get a line from socket <socket>
#ifdef _MSC_VER
void stopwinsockets();					// stop winsock library
int startwinsockets();					// init winsock library
#endif /* _MSC_VER */
