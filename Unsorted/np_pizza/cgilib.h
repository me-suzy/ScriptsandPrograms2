char *get_request( void );
void fixstring( unsigned char *s);
int split_and_parse( char *query, 
		     char **names, 
		     char **vals, 
		     int maxfields);
