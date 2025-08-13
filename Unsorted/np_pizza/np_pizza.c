/* CGI program that handles multiple forms */
/* Each form includes a hidden field that specifies the name of the form */

#include <stdio.h>
#include <string.h>
#include <sys/types.h>
#include <time.h>
#include <stdlib.h>
#include <ctype.h>

#include "cgilib.h"	/* simple CGI request parsing routines */

/* print HTML error message and exit */

void fatal_error( char *s) {
  printf("<H1 ALIGN=CENTER><FONT COLOR=RED>\n");
  printf("%s</FONT></H1>\n",s);
  exit(1);
}


/* Here is a routine that simply prints the contents of a file 
   This is used so we can save most of the HTML we need to generate 
   in files, so we can change the appearance of the pizza system
   without recompiling */

void sendfile( char *filename ) {
  FILE *f;
  char buf[1000];
  
  if ( (f=fopen(filename,"r")) == NULL) {
    fatal_error("can't find a file");
  }

  while (fgets(buf,1000,f)) {
    printf("%s",buf);
  }
  fclose(f);
}


#define MAXFIELDS 10 	/* Max number of fields we will handle */


/* see if a string contains any nonblank chars */
int empty( char *s) {
  return(strlen(s)==strspn( s, " \t\n"));
}


/* Some file names */

char *logo = "logo";	         /* the file containing the pizza logo */
char *login = "loginform.tmpl";  /* the file with the login form */
char *order = "orderform.tmpl";  /* most of the order form */
char *baduser = "baduser";       /* Nasty bad user message */
char *nicetry = "nicetry";       /* another bad user message */


char *pwfile="passwords";

/* validate a user by seeing of a (name,password) pair is found
   in the password database (not the system password database,
    just a file we use to keep track of names and passwords 
*/

int validate_user(char *name, char *password) {
  FILE *pw;
  char u[100],p[100];

  /* empty name or password is invalid */

  if ((empty(name)) || (empty(password)))
      return(0);

  /* open the password database */

  if ( (pw=fopen(pwfile,"r"))==NULL)
    fatal_error("Sorry we lost our password file");


  /* check each entry in the database against the 
     name and password we got with the query. The file
     contains one word per line, the first line holds a
     name, the second a password (and so on).
  */

  while (!feof(pw)) {
    /* read a username and password from the database */
    if (!fgets(u,100,pw)) { fclose(pw); return(0); }
    if (!fgets(p,100,pw)) { fclose(pw); return(0); }

    /* Eliminate trailing whitespace */
    while (isspace(u[strlen(u)-1]))
      u[strlen(u)-1]=0;

    while (isspace(p[strlen(p)-1]))
      p[strlen(p)-1]=0;

    /* see if we got a match */
    if ( (strcmp(u,name)==0) && (strcmp(p,password)==0) ) {
      fclose(pw);
      return(1);	/* we found it! */
    }
  }
  fclose(pw);
  return(0);	/* we didn't find it */
}
 

/* takes care of login requests. Each valid login request should
   include the following fields:
       name
       password
    The names of the fields submitted in the query are in the
    array names, and the field values are in vals
    We ignore any other fields.
*/

void handle_login( char **names, char **vals, int n) {
  char *name;
  char *password;
  int i;

  /* find the name and password entered */
  name=password=NULL;

  for (i=0;i<n;i++) {
    if (strcasecmp(names[i],"name")==0) {
      name = vals[i];
    } else if (strcasecmp(names[i],"password")==0) {
      password = vals[i];
    }
  }

  /* validate the user name and password here */
  if (!validate_user(name,password)) {
    sendfile(baduser);
    sendfile(login);
    return;
  }
    
  /* Valid username and password - we need to send back the
     order form. We need to insert some hidden fields in the
     order form so when we get it we know who it's for!

     Most of the order form is in the file order, we need to
     take care of the form declaration and the hidden fields
     first
  */
  printf("<H2 ALIGN=CENTER>Hello ");
  printf("<FONT COLOR=BLUE>%s</FONT>, place your order</H2>\n",name);
  printf("<FORM METHOD=GET ACTION=np_pizza.cgi>\n");
  printf("<INPUT TYPE=HIDDEN NAME=name VALUE=\"%s\">\n",name);
  printf("<INPUT TYPE=HIDDEN NAME=password VALUE=\"%s\">\n",password);

  /* the rest of the form is in a file */
  sendfile(order);

}

char *validpizzas[] = { "Cheese", "Veggie", "Pepperoni", "Sausage" };
int n_validpizzas = sizeof(validpizzas)/sizeof(char *);

int validate_pizza(char *p) {
  int i;

  if ((p==NULL) || (empty(p)))
    return(0);

  for (i=0;i<n_validpizzas;i++) {
    if (strcmp(validpizzas[i],p)==0) 
      return(1);
  }
  return(0);
}

char *validsizes[] = { "large", "medium", "small" };
int n_validsizes = sizeof(validsizes)/sizeof(char *);

int validate_size(char *p) {
  int i;

  if ((p==NULL) || (empty(p)))
    return(0);

  for (i=0;i<n_validsizes;i++) {
    if (strcmp(validsizes[i],p)==0) 
      return(1);
  }
  return(0);
}


/* handle a request that is for an order. This request must
   include the following fields:

   name
   password
   pizza
   size
*/

void handle_order(char **names,char **vals, int n) {
  char *name;
  char *password;
  char *pizza;
  char *size;
  int i;
  struct tm *timerec;
  time_t t;

  /* get the current time and date */

  t = time(NULL);
  timerec = localtime( &t );


  /* find the name and password entered */
  name=password=pizza=size=NULL;

  for (i=0;i<n;i++) {
    if (strcasecmp(names[i],"name")==0) {
      name = vals[i];
    } else if (strcasecmp(names[i],"password")==0) {
      password = vals[i];
    } else if (strcasecmp(names[i],"pizza")==0) {
      pizza = vals[i];
    } else if (strcasecmp(names[i],"size")==0) {
      size = vals[i];
    }
  }

  /* validate the user name and password here */
  if (!validate_user(name,password)) {
    sendfile(nicetry); /* indicate there is some problem */
    sendfile(baduser); /* indicate the specific problem */
    sendfile(login);   /* send back the login form so they can try again */
    return;
  }

  /* Make sure the pizza is valid */
  if (! validate_pizza(pizza)) {
    sendfile(nicetry);  /* indicate that there is some problem */

    /* now send a customized error message explaining that
       the type of pizza requested is not valid, and
       send back an order form
    */

    printf("<H3 ALIGN=CENTER><FONT COLOR=BLUE>\n");
    printf("Your pizza selection is invalid! (%s)\n",pizza);
    printf("<H2 ALIGN=CENTER>Hello ");
    printf("<FONT COLOR=BLUE>%s</FONT>, place your order</H2>\n",name);
    printf("<FORM METHOD=POST ACTION=np_pizza.cgi>\n");
    printf("<INPUT TYPE=HIDDEN NAME=name VALUE=\"%s\">\n",name);
    printf("<INPUT TYPE=HIDDEN NAME=password VALUE=\"%s\">\n",password);
    sendfile(order);
    return;
  }

  /* Make sure the size is valid */
  if (! validate_size(size)) {
    sendfile(nicetry);  /* indicate that there is some problem */

    /* now send a customized error message explaining that
       the pizza size requested is not valid, and
       send back an order form
    */

    printf("<H3 ALIGN=CENTER><FONT COLOR=BLUE>\n");
    printf("Your size selection is invalid! (%s)\n",size);
    printf("<H2 ALIGN=CENTER>Hello ");
    printf("<FONT COLOR=BLUE>%s</FONT>, place your order</H2>\n",name);
    printf("<FORM METHOD=POST ACTION=np_pizza.cgi>\n");
    printf("<INPUT TYPE=HIDDEN NAME=name VALUE=\"%s\">\n",name);
    printf("<INPUT TYPE=HIDDEN NAME=password VALUE=\"%s\">\n",password);
    sendfile(order);
    return;
  }

  /* Valid order - send back a receipt */

  printf("<H2 ALIGN=CENTER>Thanks for your order %s</H2><P>\n",name);


  printf("<TABLE BORDER=0><TR>\n");
  printf("<TR><TH COLSPAN=2 ALIGN=CENTER>RECEIPT</TH></TR>\n");
  printf("<TR><TH ALIGN=RIGHT>Name:</TH><TD>%s</TD></TR>\n",name);

  printf("<TR><TH ALIGN=RIGHT>Date:</TH>");
  printf("<TD>%d/%d/%d</TD></TR>\n",
	    timerec->tm_mon+1,timerec->tm_mday,timerec->tm_year+1900);

  printf("<TR><TH ALIGN=RIGHT>Time:</TH>");
  printf("<TD>%d:%d</TD></TR>\n",
	    timerec->tm_hour,timerec->tm_min);

  printf("<TR><TH ALIGN=RIGHT>Pizza:</TH><TD>%s</TD></TR>\n",pizza);
  printf("<TR><TH ALIGN=RIGHT>Size:</TH><TD>%s</TD></TR>\n",size);
  printf("<TH>Time Ready:</TH>");
  printf("<TD>%d:%d</TD></TR>\n",
	    timerec->tm_hour+1,timerec->tm_min);
  printf("<TR><TH ALIGN=RIGHT>Amount Due:</TH>");
  if (strcmp(size,"small")==0)
      printf("<TD>$6.50</TD></TR>\n");
  else if (strcmp(size,"medium")==0)
      printf("<TD>$8.50</TD></TR>\n");
  else
      printf("<TD>$10.50</TD></TR>\n");
  printf("</TABLE>\n");
}


/* Pizza system main program - gets the request and determines whether
   this is a login request or an order request. If neither - it just sends
   back a login form (so the user can log in).

   A login request or an order request will include a field named
   "formname" which can have the value "login" or "order".

*/

int main() {

  char *query;
  char *names[MAXFIELDS];	/* will hold field names */
  char *vals[MAXFIELDS];	/* will hold field values */
  char *form;		

  int i,n;

  /* always tell the browser what kind of document we are sending */
  /* NOTE: the web server that started the CGI program will send
     back the appropriate HTTP status line !!! */

  printf("Content-type: text/html\n\n");

  /* now send the Pizza logo */
  sendfile(logo);

  /* get the query */
  query = get_request();

  if ((query==NULL)||(strlen(query)==0)) {
    /* we got nothing - we need to send back the login form */
    sendfile(login);
  } else {
    /* we got a query, for now just figure out which form we 
       are handling */

    /* parse the request string and chop into fields */
    n = split_and_parse(query,names,vals,MAXFIELDS);

    form=NULL;

    for (i=0;i<n;i++) {
      if (strcasecmp(names[i],"formname")==0) {
	form = vals[i];
	break;
      }
    }

    if (form==NULL) {
      /* We did not get a formname - this request is invalid! */
      fatal_error("ERROR - Invalid request");
    }

    /* now figure out which form and call the appropriate routine */
    if (strcmp("login",form)==0) {
      /* handling the login form */
      handle_login(names,vals,n);
    } else if (strcmp("order",form)==0) {
      /* handling the order form */
      handle_order(names,vals,n);
    } else {
      /* something is wrong - invalid form name */
      fatal_error("ERROR - Invalid request");
    }
  }
  return(0);
}


