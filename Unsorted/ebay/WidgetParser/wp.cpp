#include <stdio.h>
# define U(x) x
# define NLSTATE yyprevious=YYNEWLINE
# define BEGIN yybgin = yysvec + 1 +
# define INITIAL 0
# define YYLERR yysvec
# define YYSTATE (yyestate-yysvec-1)
# define YYOPTIM 1
# define YYLMAX BUFSIZ
#ifndef __cplusplus
# define output(c) (void)putc(c,yyout)
#else
# define lex_output(c) (void)putc(c,yyout)
#endif

#if defined(__cplusplus) || defined(__STDC__)

#if defined(__cplusplus) && defined(__EXTERN_C__)
extern "C" {
#endif
	int yyback(int *, int);
	int yyinput(void);
	int yylook(void);
	void yyoutput(int);
	int yyracc(int);
	int yyreject(void);
	void yyunput(int);
	int yylex(void);
#ifdef YYLEX_E
	void yywoutput(wchar_t);
	wchar_t yywinput(void);
#endif
#ifndef yyless
	int yyless(int);
#endif
#ifndef yywrap
	int yywrap(void);
#endif
#ifdef LEXDEBUG
	void allprint(char);
	void sprint(char *);
#endif
#if defined(__cplusplus) && defined(__EXTERN_C__)
}
#endif

#ifdef __cplusplus
extern "C" {
#endif
	void exit(int);
#ifdef __cplusplus
}
#endif

#endif
# define unput(c) {yytchar= (c);if(yytchar=='\n')yylineno--;*yysptr++=yytchar;}
# define yymore() (yymorfg=1)
#ifndef __cplusplus
# define input() (((yytchar=yysptr>yysbuf?U(*--yysptr):getc(yyin))==10?(yylineno++,yytchar):yytchar)==EOF?0:yytchar)
#else
# define lex_input() (((yytchar=yysptr>yysbuf?U(*--yysptr):getc(yyin))==10?(yylineno++,yytchar):yytchar)==EOF?0:yytchar)
#endif
#define ECHO fprintf(yyout, "%s",yytext)
# define REJECT { nstr = yyreject(); goto yyfussy;}
int yyleng;
char yytext[YYLMAX];
int yymorfg;
extern char *yysptr, yysbuf[];
int yytchar;
FILE *yyin = {stdin}, *yyout = {stdout};
extern int yylineno;
struct yysvf { 
	struct yywork *yystoff;
	struct yysvf *yyother;
	int *yystops;};
struct yysvf *yyestate;
extern struct yysvf yysvec[], *yybgin;

# line 2 "wp.lex.cpp"
/*	$Id: wp.cpp,v 1.6 1999/04/28 05:35:03 josh Exp $	*/
// Lex source code that will replace <eBay> tags with
//  output from appropriate widgets
#include "eBayTypes.h"
#include "clseBayWidget.h"
#include "clsWidgetHandler.h"
#include "clsWidgetParserApp.h"
#include "assert.h"
#include <stream.h>
#include <fstream.h>

bool eBayTagMode = false;		// true if "<eBay" tag found and haven't yet reached ">"
char *cParam;					// for storing a parameter
vector<char *> vArgs;			// for storing the parameter list
int i;							// for iterating the parameter list
clseBayWidget *pWidget = NULL;	// for emiting the HTML
char *cLastToken;				// holds previous token
int lastTokenBufLength = 1024;	// how much space cLastToken has
int x;							// strlen
bool debugMode;					// if true, print debugging information
ostream *loggingFile;			// for logging stats

FILE *inFile;					// the FILE we are currently reading
FILE *outFile;					// the FILE we are currently writing
char **fileList = NULL;			// the list of files to process
int nFiles = 0;					// number of files to process
int currentFile = 0;			// the file we are currently processing 0...nfiles-1

// store last token, allocating more space if necessary
void storeLastToken()
{
	int x;
	x = strlen(yytext)+1;
	if (x > lastTokenBufLength)
	{
		delete [] cLastToken;
		lastTokenBufLength=x;
		cLastToken = new char[lastTokenBufLength];
	}
	strcpy(cLastToken, yytext);
}
# define YYNEWLINE 10
yylex(){
int nstr; extern int yyprevious;
#ifdef __cplusplus
/* to avoid CC and lint complaining yyfussy not being used ...*/
static int __lex_hack = 0;
if (__lex_hack) goto yyfussy;
#endif
while((nstr = yylook()) >= 0)
yyfussy: switch(nstr){
case 0:
if(yywrap()) return(0); break;
case 1:

# line 52 "wp.lex.cpp"
	{	/*---------- begin of eBay tag ----------*/
						if (!eBayTagMode)
						{
							eBayTagMode = true;			// signify finding ebay tag
							assert(vArgs.size()==0);	// should be no parameters yet
							cParam = new char[strlen(yytext+1)+1];
							strcpy(cParam, yytext+1);	// save away the tag name
							vArgs.push_back(cParam);	// add tagname to the list

							if (debugMode)
								cerr << "\nTag found: " << cParam << ".";
						}
						storeLastToken();
					}
break;
case 2:

# line 67 "wp.lex.cpp"
	{	/*---------- end of eBay tag ----------*/
						if (eBayTagMode)
						{

							if (debugMode)
							{
								// show parameters
								if (vArgs.size() > 1)
								{
									for (i=1; i<vArgs.size(); i++)
										cerr << "\n  " << vArgs[i];
		
								}
							}

							eBayTagMode = false;
							pWidget = ((clsWidgetParserApp*)gApp)->mpWidgetHandler->GetWidget(vArgs[0]);	// use tagname to get widget
							if (pWidget)
							{
								ostrstream theStream;
								char *theStr;
								pWidget->SetLoggingStream(loggingFile);		// for stats logging
								pWidget->SetParams(&vArgs);					// set parameters of widget
								pWidget->EmitHTML(&theStream);				// do it!
								theStream << '\0';
								theStr = theStream.str();
								fwrite(theStr, sizeof(char), strlen(theStr), yyout);  // printf mangles the percent symbols
								delete [] theStr;
								delete pWidget;

							}
							else
							{
								// report unknown ebay tag
								cerr << "\nWarning: Ignoring tag \"" << vArgs[0] << ".\"";

							}
							
							// check for unhandled parameters and report them
							for (i=1; i<vArgs.size(); i++)
								if (vArgs[i] && strlen(vArgs[i])) cerr << "\nUnhandled parameter: " << vArgs[0] << " " << vArgs[i];


							// reset for the next widget
							for (i=0; i<vArgs.size(); i++)
								delete [] vArgs[i];
							vArgs.erase(vArgs.begin(), vArgs.end());

						}
						else
						{
							ECHO;
						}
						storeLastToken();
					}
break;
case 3:

# line 124 "wp.lex.cpp"
case 4:

# line 125 "wp.lex.cpp"
			{	/*---------- any word without a >, tab, newline or space ----------*/
						if (eBayTagMode)
						{
							// decide whether or not this token is a new parameter or
							//  part of the previous parameter
							x = strlen(cLastToken); if (!x) x=1;
							if ((cLastToken[x-1]==' ') || (cLastToken[x-1]=='\t'))
							{
								// this is a new parameter
								cParam = new char[strlen(yytext)+1];
								strcpy(cParam, yytext);		// save away the parameter
								vArgs.push_back(cParam);	// add it to the list
							}
							else
							{
								// add this to the previous parameter
								if (vArgs.size() != 0)
								{
									cParam = new char[strlen(vArgs[vArgs.size()-1])+strlen(yytext)+1];
									strcpy(cParam, vArgs[vArgs.size()-1]);
									strcat(cParam, yytext);
									delete [] vArgs[vArgs.size()-1];
									vArgs[vArgs.size()-1] = cParam;
								}
							}
						}
						else
						{
							ECHO;
						}
						storeLastToken();
					}
break;
case 5:

# line 159 "wp.lex.cpp"
		{
						if (!eBayTagMode) ECHO;
						if ((yytext[0]=='\"'))
							cerr << "\nFound unmatched " << yytext << ".";
						storeLastToken();
					}
break;
case -1:
break;
default:
(void)fprintf(yyout,"bad switch yylook %d",nstr);
} return(0); }
/* end of yylex */

char *makeOutFileFromInFile(char *pInFile)
{
  char *p;
  p = strrchr(pInFile, '/');

  if (!p)
    p = pInFile;  // no /, so just start at beginning
  else
    p++;         // advance over the /

  if (strlen(p) > 4)
    p += 4;  // skip over the eBay
  else
    p = NULL;

  return p;
}


int setupNextFile()
{
	char *pOutFile = NULL;

	// close out last files
	if (yyin != stdin) fclose(yyin);
	if (yyout != stdout) fclose(yyout);

	// setup the next file
	while (currentFile < nFiles)
	{
		inFile = fopen(fileList[currentFile], "r");
		currentFile++;

		// try to open the input and output files
		if (inFile)
		{
			pOutFile = makeOutFileFromInFile(fileList[currentFile-1]);
			if (pOutFile) outFile = fopen(pOutFile, "w");
			if (pOutFile && outFile)
			{
				// success!!
				yyin = inFile;
				yyout = outFile;
				cerr << "\n\nInput file: " << fileList[currentFile-1] << "\n";
				cerr << "Output file: " << pOutFile << "\n";
				return 1;
			}

		}
		if (!inFile) cerr << "\n\nError opening input file " << fileList[currentFile-1] << ". Skipping.\n";
		if (!outFile && pOutFile) cerr << "Error opening output file " << pOutFile << ". Skipping.\n";
	}

	return 0;		// no more files to process

}

int main(int argc, char *argv[ ])
{

#ifdef _MSC_VER
	g_tlsindex = 0;			// needed to avoid GPF in NT Oracle console apps
#endif
	bool status;
	clsWidgetParserApp* pApp;

	// display matched tags and errors
	debugMode = true;

	// make sure partner id was specified (for cobranding compatibility)
	if (argc<2)
	{
		cerr <<	"Usage: " << argv[0] << " partnerID file1 file2 ...\n";
		return 1;
	}

	// get the file list
	nFiles = argc-2;	// number of files is number of args minus partner and command
	fileList = argv+2;

	// setup the first file
	setupNextFile();

	// initialization of cLastToken
	cLastToken = new char[lastTokenBufLength];
	strcpy(cLastToken, " ");

	// for stats logging
	loggingFile = new ofstream("widgets.log", ios::app);

	// create the app and let the parsing begin!
	pApp = new clsWidgetParserApp(atoi(argv[1]));	// pass in partner id
	status = pApp->Run();

	// cleanup
	delete [] cLastToken;
	delete loggingFile;

	return status;

}

// redefining yywrap
int yywrap()
{
	if (setupNextFile())
		return 0;			// continue with more parsing

	return 1;				// we're done
}


int yyvstop[] = {
0,

4,
5,
0,

5,
0,

5,
0,

5,
0,

2,
0,

4,
0,

3,
0,

1,
0,
0};
# define YYTYPE unsigned char
struct yywork { YYTYPE verify, advance; } yycrank[] = {
0,0,	0,0,	1,3,	0,0,	
5,9,	3,8,	0,0,	0,0,	
0,0,	0,0,	1,4,	0,0,	
5,9,	3,0,	3,0,	0,0,	
8,0,	8,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	1,5,	
3,0,	5,10,	3,0,	8,0,	
0,0,	8,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	1,3,	0,0,	5,9,	
3,8,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	1,6,	2,6,	1,7,	
3,0,	5,9,	3,0,	8,0,	
11,12,	8,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	12,13,	0,0,	
0,0,	0,0,	6,11,	0,0,	
0,0,	0,0,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
0,0,	0,0,	0,0,	0,0,	
0,0,	0,0,	13,14,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	0,0,	0,0,	0,0,	
0,0,	0,0,	0,0,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	14,15,	14,15,	14,15,	
14,15,	0,0,	0,0,	0,0,	
0,0};
struct yysvf yysvec[] = {
0,	0,	0,
yycrank+-1,	0,		0,	
yycrank+-2,	yysvec+1,	0,	
yycrank+-4,	0,		yyvstop+1,
yycrank+0,	0,		yyvstop+4,
yycrank+-3,	0,		yyvstop+6,
yycrank+1,	0,		yyvstop+8,
yycrank+0,	0,		yyvstop+10,
yycrank+-7,	yysvec+3,	yyvstop+12,
yycrank+0,	yysvec+5,	0,	
yycrank+0,	0,		yyvstop+14,
yycrank+2,	0,		0,	
yycrank+1,	0,		0,	
yycrank+1,	0,		0,	
yycrank+58,	0,		0,	
yycrank+0,	yysvec+14,	yyvstop+16,
0,	0,	0};
struct yywork *yytop = yycrank+180;
struct yysvf *yybgin = yysvec+1;
char yymatch[] = {
  0,   1,   1,   1,   1,   1,   1,   1, 
  1,   9,   9,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  9,   1,  34,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
 48,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,   1,   1,   9,   1,  62,   1, 
  1,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,   1,   1,   1,   1,   1, 
  1,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,  48,  48,  48,  48,  48, 
 48,  48,  48,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
  1,   1,   1,   1,   1,   1,   1,   1, 
0};
char yyextra[] = {
0,0,0,0,0,0,0,0,
0};
/*	Copyright (c) 1989 AT&T	*/
/*	  All Rights Reserved  	*/

/*	THIS IS UNPUBLISHED PROPRIETARY SOURCE CODE OF AT&T	*/
/*	The copyright notice above does not evidence any   	*/
/*	actual or intended publication of such source code.	*/

#pragma ident	"@(#)ncform	6.8	95/02/11 SMI"

int yylineno =1;
# define YYU(x) x
# define NLSTATE yyprevious=YYNEWLINE
struct yysvf *yylstate [YYLMAX], **yylsp, **yyolsp;
char yysbuf[YYLMAX];
char *yysptr = yysbuf;
int *yyfnd;
extern struct yysvf *yyestate;
int yyprevious = YYNEWLINE;
#if defined(__cplusplus) || defined(__STDC__)
int yylook(void)
#else
yylook()
#endif
{
	register struct yysvf *yystate, **lsp;
	register struct yywork *yyt;
	struct yysvf *yyz;
	int yych, yyfirst;
	struct yywork *yyr;
# ifdef LEXDEBUG
	int debug;
# endif
	char *yylastch;
	/* start off machines */
# ifdef LEXDEBUG
	debug = 0;
# endif
	yyfirst=1;
	if (!yymorfg)
		yylastch = yytext;
	else {
		yymorfg=0;
		yylastch = yytext+yyleng;
		}
	for(;;){
		lsp = yylstate;
		yyestate = yystate = yybgin;
		if (yyprevious==YYNEWLINE) yystate++;
		for (;;){
# ifdef LEXDEBUG
			if(debug)fprintf(yyout,"state %d\n",yystate-yysvec-1);
# endif
			yyt = yystate->yystoff;
			if(yyt == yycrank && !yyfirst){  /* may not be any transitions */
				yyz = yystate->yyother;
				if(yyz == 0)break;
				if(yyz->yystoff == yycrank)break;
				}
#ifndef __cplusplus
			*yylastch++ = yych = input();
#else
			*yylastch++ = yych = lex_input();
#endif
			if(yylastch > &yytext[YYLMAX]) {
				fprintf(yyout,"Input string too long, limit %d\n",YYLMAX);
				exit(1);
			}
			yyfirst=0;
		tryagain:
# ifdef LEXDEBUG
			if(debug){
				fprintf(yyout,"char ");
				allprint(yych);
				putchar('\n');
				}
# endif
			yyr = yyt;
			if ( (int)yyt > (int)yycrank){
				yyt = yyr + yych;
				if (yyt <= yytop && yyt->verify+yysvec == yystate){
					if(yyt->advance+yysvec == YYLERR)	/* error transitions */
						{unput(*--yylastch);break;}
					*lsp++ = yystate = yyt->advance+yysvec;
					if(lsp > &yylstate[YYLMAX]) {
						fprintf(yyout,"Input string too long, limit %d\n",YYLMAX);
						exit(1);
					}
					goto contin;
					}
				}
# ifdef YYOPTIM
			else if((int)yyt < (int)yycrank) {		/* r < yycrank */
				yyt = yyr = yycrank+(yycrank-yyt);
# ifdef LEXDEBUG
				if(debug)fprintf(yyout,"compressed state\n");
# endif
				yyt = yyt + yych;
				if(yyt <= yytop && yyt->verify+yysvec == yystate){
					if(yyt->advance+yysvec == YYLERR)	/* error transitions */
						{unput(*--yylastch);break;}
					*lsp++ = yystate = yyt->advance+yysvec;
					if(lsp > &yylstate[YYLMAX]) {
						fprintf(yyout,"Input string too long, limit %d\n",YYLMAX);
						exit(1);
					}
					goto contin;
					}
				yyt = yyr + YYU(yymatch[yych]);
# ifdef LEXDEBUG
				if(debug){
					fprintf(yyout,"try fall back character ");
					allprint(YYU(yymatch[yych]));
					putchar('\n');
					}
# endif
				if(yyt <= yytop && yyt->verify+yysvec == yystate){
					if(yyt->advance+yysvec == YYLERR)	/* error transition */
						{unput(*--yylastch);break;}
					*lsp++ = yystate = yyt->advance+yysvec;
					if(lsp > &yylstate[YYLMAX]) {
						fprintf(yyout,"Input string too long, limit %d\n",YYLMAX);
						exit(1);
					}
					goto contin;
					}
				}
			if ((yystate = yystate->yyother) && (yyt= yystate->yystoff) != yycrank){
# ifdef LEXDEBUG
				if(debug)fprintf(yyout,"fall back to state %d\n",yystate-yysvec-1);
# endif
				goto tryagain;
				}
# endif
			else
				{unput(*--yylastch);break;}
		contin:
# ifdef LEXDEBUG
			if(debug){
				fprintf(yyout,"state %d char ",yystate-yysvec-1);
				allprint(yych);
				putchar('\n');
				}
# endif
			;
			}
# ifdef LEXDEBUG
		if(debug){
			fprintf(yyout,"stopped at %d with ",*(lsp-1)-yysvec-1);
			allprint(yych);
			putchar('\n');
			}
# endif
		while (lsp-- > yylstate){
			*yylastch-- = 0;
			if (*lsp != 0 && (yyfnd= (*lsp)->yystops) && *yyfnd > 0){
				yyolsp = lsp;
				if(yyextra[*yyfnd]){		/* must backup */
					while(yyback((*lsp)->yystops,-*yyfnd) != 1 && lsp > yylstate){
						lsp--;
						unput(*yylastch--);
						}
					}
				yyprevious = YYU(*yylastch);
				yylsp = lsp;
				yyleng = yylastch-yytext+1;
				yytext[yyleng] = 0;
# ifdef LEXDEBUG
				if(debug){
					fprintf(yyout,"\nmatch ");
					sprint(yytext);
					fprintf(yyout," action %d\n",*yyfnd);
					}
# endif
				return(*yyfnd++);
				}
			unput(*yylastch);
			}
		if (yytext[0] == 0  /* && feof(yyin) */)
			{
			yysptr=yysbuf;
			return(0);
			}
#ifndef __cplusplus
		yyprevious = yytext[0] = input();
		if (yyprevious>0)
			output(yyprevious);
#else
		yyprevious = yytext[0] = lex_input();
		if (yyprevious>0)
			lex_output(yyprevious);
#endif
		yylastch=yytext;
# ifdef LEXDEBUG
		if(debug)putchar('\n');
# endif
		}
	}
#if defined(__cplusplus) || defined(__STDC__)
int yyback(int *p, int m)
#else
yyback(p, m)
	int *p;
#endif
{
	if (p==0) return(0);
	while (*p) {
		if (*p++ == m)
			return(1);
	}
	return(0);
}
	/* the following are only used in the lex library */
#if defined(__cplusplus) || defined(__STDC__)
int yyinput(void)
#else
yyinput()
#endif
{
#ifndef __cplusplus
	return(input());
#else
	return(lex_input());
#endif
	}
#if defined(__cplusplus) || defined(__STDC__)
void yyoutput(int c)
#else
yyoutput(c)
  int c; 
#endif
{
#ifndef __cplusplus
	output(c);
#else
	lex_output(c);
#endif
	}
#if defined(__cplusplus) || defined(__STDC__)
void yyunput(int c)
#else
yyunput(c)
   int c; 
#endif
{
	unput(c);
	}
