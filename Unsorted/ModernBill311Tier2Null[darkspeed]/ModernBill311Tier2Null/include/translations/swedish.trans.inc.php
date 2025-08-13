<?
/*
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
** THIS IS THE ENGLISH TRANSLATION FILE
**
** To translate into a new language:
**    1) copy this file to your_language.trans.inc.php
**    2) add your_language to the $language_types array
**       in include/misc/sb_select_menus.inc.php
*/

define(CHARSET,           "ISO-8859-1");
define(TEXTDIRECTION,     "LTR"); //dir = LTR | RTL
define(TABLE_ALIGN,       "left");

## VERSION 3.0 ADDITIONS

# v3 - Misc Dates
define(SUN,               "Sön");
define(MON,               "Mån");
define(TUE,               "Tis");
define(WED,               "Ons");
define(THR,               "Tor");
define(FRI,               "Fre");
define(SAT,               "Lör");

# v3 - Misc Domain Terms
define(THREEYEARS,        "3 År");
define(FOURYEARS,         "4 År");
define(FIVEYEARS,         "5 År");
define(SIXYEARS,          "6 År");
define(SEVENYEARS,        "7 År");
define(EIGHTYEARS,        "8 År");
define(NINEYEARS,         "9 År");
define(TENYEARS,          "10 År");

# v3 - A
define(ACCOUNTREGISTER,    "Registrera Konto");
define(ADDFEATURE,         "Lägg till Ny Egenskap");
define(ADDONS,             "Add-Ons");
define(ADDTOORDER,         "Lägg till Er Order!");
define(ADDTOMYACCOUNT,     "Fortsätt (Addera till Mitt Konto)");
define(ADDITIONALINFO,     "Ytterligare med Information");
define(AFFILIATES,         "Partners");
define(AFFILIATECONFIG,    "Konfigurera Partner");
define(AFFCODE,            "Kod");
define(AFFHITS,            "Träffar");
define(AFFCOUNT,           "Räkna");
define(AFFPAYTYPE,         "Typ");
define(AFFPAYSUM,          "Summa");
define(ALLOWAUTOSEARCH,    "AutoSökning");
define(ALLHOSTS,           "Sök Subdomän");
define(AMERICANEXPRESS,    "American Express");
define(ANSWER,             "Svar");
define(APPLYCOUPON,        "Validera Kupong");
define(APPLYFOREVEYRENEWAL,"Påför för varje förnyelse period.");
define(AVERAGE,            "Genomsnitt");
define(APPLYTAX,           "Lägg till skatt");
define(ARINWHOIS,          "ARIN Whois: American Registry for Internet Numbers");
define(ASSIGNTOME,         "Öppna & Ange till Mig");
define(AUTOSIGNUPFORM,     "Automatiskt Beställningsformulär");

# v3 - B
define(BANNED,             "Bannlyst");
define(BANNEDCONFIG,       "Konfigurera Bannlysta");
define(BANNEDIP,           "Bannlysta IP Adresser");
define(BANNEDEMAIL,        "Bannlyst E-Post Adresser");
define(BANKNAME,           "Bank Namn");
define(BADLOGIN,           "Fel. Er e-post adress eller lösenord är felaktigt. Var vänlig försök igen.");
define(BANKABACODE,        "Bankkonto ABA Kod");
define(BANKACCOUNTNUM,     "Bankkonto Nummer");
define(BUILDPACKAGE,       "Bygg Ert eget Paket");

# v3 - C
define(CACHE,              "Cache");
define(CALLDETAILS,        "Call Detaljer");
define(CALLID,             "Call ID");
define(CATEGORY,           "Kategori");
define(COPYTHEMEDIR,       "Kopiera katalogen för standard temat och byt sedan namn på det.<br><br>T.ex.: inkludera/konfigurera/tema/NYKATALOG");
define(CANCELED,           "Annullera");
define(CCEXPIRED,          "CC Förfallodatum");
define(CCEXPEMAILS,        "Notiser om Förfallna Kreditkort");
define(CHECKTOSEND,        "Bocka för för att skicka kvitto per e-post.");
define(CHILDREN,           "Delprodukter");
define(CHILDINSTRUCTIONS,  "[+] = Lägg Till DelPaket, [-] = Tag Bort DelPaket");
define(CLIENTINFO,         "Klient Information");
define(CLIENTREGISTER,     "Registrera Klient");
define(CLIENTPACKS2,       "Uppskattad månadsvis inkomst från nya paket!");
define(CLOSED,             "Stängd");
define(CLEARBATCH,         "Rensa Batch");
define(CLOSETHISWIN,       "Stäng Window");
define(CLOSECALL,          "Stäng Ärende(n)");
define(CLOSETHISWIN,       "Stäng detta fönster");
define(CLIENTRESPONSE,     "Kundens Kommentar");
define(COUNT,              "Träffar");
define(CREATENEWTHEME,     "Skapa ett Nytt Tema.");
define(COMPAREPACAKGES,    "Jämför Paket");
define(COST,               "Kostnad");
define(COUPONS,            "Kuponger");
define(COUPONCODE,         "Kupong Kod");
define(COUPONCODES,        "Kupong Koder");
define(COUPONCONFIG,       "Kupong Konfigurera");
define(COUPONSTATS,        "Kupong Statistik");
define(COUPONSEARCH,       "Kupong Sök");
define(COUPONCOUNT,        "Räkna");
define(COPYVORTECHDIR,     "Kopiera Vortechs standardkatalog \"signup\" och byt namn på det.");
define(CPU,                "CPU");
define(CREATENEWVORTECH,   "Skapa ett nytt Vortech Beställningsformulär.");
define(CREATEYOUROWN,      "Exportera Databas (Wizard)");
define(CURRENTPHPVERSION,  "Nuvarande PHP Version");
define(CUTERROR,           "Klipp och Klistra<br>Erhållna Fel");
define(CREATECALL,         "Skapa Ärende");
define(COUPON,             "Kupong");
define(CARDCODE,           "SäkerhetsKod");
define(CHECKAVAIL,         "Kontrollera om Tillgänglig");
define(COMPLETESECTIONA,   "Slutför Del A");
define(COMPLETESECTIONB,   "Slutför Del B");
define(CORRECTMYDATA,      "Korrigera Mib Information");
define(CREDITCARDINFO,     "Information om KreditKort");
define(CVV2IS,             "<b>CVV2</b> är ett nytt autentisering schema som har skapats av kreditkortsföretagen för att ytterligare reducera antal bedrägliga korttransaktioner på Internet. För att bevisa att kortet finns fysiskt tillgängligt måste dess CVV2 nummer anges vid varje transaktion.");
define(CVV2VISA,           "Detta nummer finns på baksidan av ert MasterCard &amp; Visa kort, i närheten av det område där ni skrivit er signatur. (Det är de 3 sista siffrorna <ul>efter</ul> ert kreditkortsnummer som finns angivet där ni skrivit er signatur).");
define(CVV2AMEX,           "På American Express kort finner ni ert CVV2 ovanför, till höger om kortnummret på framsidan av kortet.");
define(CLIENTSCONFIG15,    "Extra Kund Variabler 1-5");
define(CLIENTSCONFIG610,   "Extra Kund Variabler 6-10");

# v3 - D
define(DRIVERSLICENSE,     "Korkortsnummer");
define(DRIVERSESTATE,      "Län som körkortet utfärdats i");
define(DRIVERSDOB,         "Personnummer");
define(DOMAINERROR,        "Var vänlig och ange ett giltligt domännamn.");
define(DOMSEARCHRESULTS,   "Resultat av DomänSökning");
define(DOMAINONLYORSELECT, "Bara Domän eller Välj Huvud Paket.");
define(DUPLICATEUSER,      "Fel. Er e-post adress finns redan i vårt system. Var vänlig och försök igen.");
define(DATE,               "Datum");
define(DAYSBEFORE,         "Dagar Före Förfallodag");
define(DEBIT,              "Debit");
define(DEBUG,              "Debug Output");
define(DELETE_t,           "Tag bort");
define(DEBITS,             "Debiteringar");
define(DBTABLE,            "DatabasTabell");
define(DBEXPORT,           "Databas Export");
define(DEFAULTCURRENCY,    "Standard Valuta");
define(DEFAULTTRANSLATION, "Standard Språk");
define(DEFAULTEMAILSTYPE,  "Standard E-Post Typ");
define(DOLLARDISCOUNT,     "Rabatt");
define(DOMREPORTS,         "Domäner & Rapporter");
define(DUPLICATEORDER,     "Er order har redan genomförts under denna sessions.");

# v3 - E
define(ECHECKINFO,         "eCheck Information");
define(EMAILERROR,         "Er E-Post adress är inte giltlig. Var vänlig försök igen.");
define(EXISTINGCUSTOMERS,  "BEFINTLIGA KUNDER");
define(EXISTCUSTTEXT,      "Var vänlig och logga in för att lägga till denna order till ert konto.");
define(EDITTHEMECONFIGFILE,"Editera theme.config.inc.php filen och sätt \$config_type variabeln till det NYA \"theme_??\" värdet.");
define(EDITNEWVORTECHVARIABLES,"Editera  <b>Vortech: \"vortech_type??\"</b> som ni just skapade ovan. Det är allt!");
define(EDITNEWTHEMEVARIABLES,"Editera <b>Tema: \"theme_??\"</b> som ni just skapade ovan. Det är allt!");
define(EDITVORTECHCONFIGFILE,"Editera config.php filen i den nya Vortech katalogen och sätt  \$config_type variabeln till det NYA \"vortech_type??\" värdet.");
define(EMAILTEMPLATES,     "E-Post Mallar");
define(EMERGENCY,          "Nödsituation");
define(ENCRYPTIONKEYREASON,"Krav på att dekryptera kreditkortsnummer.");
define(ENDDATE,            "Slutdatum");
define(ENTERTRANSACTION,   "För in Transaktion");
define(EXTENSION,          "Utvidgning");
define(EXPIREDTEXT,        "Förfallen Text");
define(EXPIRED,            "Förfallen");
define(ERRORPLEASETRYAGAIN,"<b>VARNING:</b> Er e-post adress hittas inte och er access från <b>$REMOTE_ADDR</b> har loggats.");

# v3 - F
define(FAQCONFIG,          "FAQ Konfig");
define(FAQSEARCH,          "FAQ Sökning");
define(FAQSTATS,           "FAQ Statistik");
define(FAQQUESTIONS,       "FAQ Frågor");
define(FAQCATEGORIES,      "FAQ Kategorier");
define(FORGOTYOURPASSWORD, "Glömt ert lösenord?");
define(FORMAT,             "Format");
define(FRAUD,              "Bedrägeri");

# v3 - G
define(GENERALEMAILS,      "Generella Emails");
define(GENINVOICE,         "Genererade Fakturor");

# v3 - H
define(HOST,               "Host");
define(HOST2IP,            "Host Name to IP");
define(HAVEACOUPON,        "Har i en kupong? För in den här!");

# v3 - I
define(IMSORRYCOUPONEXPIRED,"Tyvärr är denna kupong inte giltlig längre.");
define(IAMANEWCUSTOMER,    "Fortsätt (Jag Är en Ny Kund)");
define(ISAVAILABLE,        "Finns Tillgänglig! Beställ Nu");
define(INVOICEEMAILS,      "Faktura relaterade Emails");
define(INVOICESNOWDUE,     "Fakturor Nu Förfallna");
define(INSERTVORTECHDEFAULTCONFIG,"Sätt in en standard konfigurationstabell in i databasen genom att använda Vortech namn från steg 2. Formatet måste vara  vortech_typeX där X är nästa nummer i sekvensen.");
define(INSERTTHEMEDEFAULTCONFIG,"Sätt in en standard konfigurationstabell in i databasen genom att använda samma tema namn som från steg 2. Formatet måste vara theme_NYTTNAMN.");
define(IP2HOST,            "IP till Host Namn");
define(INVOICECAPS,        "F A K T U R A");
define(INVOICESPARTIAL,    "Delfakturor");

# v3 - J

# v3 - K
define(KERNAL,             "Kernel");

# v3 - L
define(LAUNCHTOOLS,        "Starta upp Verktyg");
define(LOGIN,              "Logga In");

# v3 - M
define(MAINPACKAGE,       "Huvud Paket");
define(MAXNUMBEROFREDEMPTIONS, "Maximalt antal att lösa in.");
define(MAXREDEMPTIONS,    "Max Inlösen");
define(MANUALPAYMENT,     "Manuell Betalning");
define(MARGIN,            "Marginal");
define(MBSUPPORT,         "ModernBill Support");
define(MBMANUAL,          "ModernBill Manual & FAQ");
define(MBFORUMS,          "ModernBill Support Forum");
define(MBINFO,            "ModernBill Information");
define(MBDOWNLOADS,       "ModernBill Downloads");
define(MBNEWS,            "ModernBill Nyheter & Tillkännagivanden");
define(MBRESELLERS,       "ModernBill Återförsäljare");
define(MBVERSION,         "Senaste Version");
define(MENU,              "Menu");
define(MGHZ,              "MHz Processor(s)");
define(MXRECORDS,         "MX Records");
define(MYSUPPORT,           "Min Support");
define(MONTH,             "Välj Månad");
define(M_JANUARY,         "Januari");
define(M_FEBRUARY,        "Februari");
define(M_MARCH,           "Mars");
define(M_APRIL,           "April");
define(M_MAY,             "Maj");
define(M_JUNE,            "Juni");
define(M_JULY,            "Juli");
define(M_AUGUST,          "Augusti");
define(M_SEPTEMBER,       "Setember");
define(M_OCTOBER,         "Oktober");
define(M_NOVEMBER,        "November");
define(M_DECEMBER,        "December");
define(MYSQL,             "MySQL");
define(MSSQL,             "MsSQL");

# v3 - N
define(NETWORKINGTOOLS,   "Nätverksverktyg");
define(NEXTPAYMENTDATE,   "kommer att förfalla den");
define(NUMBER,            "Räkna");
define(NEWPACKAGES,       "Nya Paket");
define(NETTOOLSTEXT,      "<b>Obs:</b> Vissa förfrågningar använder  \"system\" funktionen och fungerar INTE om der php installation har safe_mode aktiverat ELLER ni kör på en WINDOWS server.");
define(NEWCLIENTSONLY,    "Nya Kunder Bara");
define(NEWTHISWEEK,       "Nya Beställningar Denna Vecka");
define(NEWCALL,           "Nya Ärenden");
define(NEWCUSTOMERS,      "NYA KUNDER");
define(NEWCUSTTEXT,       "Var vänlig och ange er e-post adress<br>för att skapa ett nytt konto.");
define(NOSPACEALLOWED,    "Inga mellanslag är tillåtna i Host fältet!");
define(NSLOOKUP,          "Name Server Uppslagning");

# v3 - O
define(OPEN,              "Öppen");
define(OPTIMIZEGOOD,      "Optimerade Tabeller = <b>OK</b>");
define(OPTIMIZEBAD,       "Optimerade Tabeller = <b>INTE OK</b>");
define(OS,                "OS");
define(OTHERINFO,         "Annan Information");
define(ORSELECTB,         "eller Välj B");
define(ORSELECTC,         "eller Välj C");
define(ORDERCRESULTS,     "Order Resultat");

# v3 - P
define(PACKAGEADDONS,     "Förbättra Ert Paket");
+ define(PERCORDOLLARNOTBOTH, "Ange rabatt i procent eller rabatt i kr, men inte både och.");
define(PLEASEVERIFY,      "Var vänlig och verifiera er beställning nedan och skicka sedan in er order genom att klicka på knappen.");
define(PRORATE,           "Efterhandsbetalning (ProRate)");
define(PROGRESS,          "Order Progress");
define(PRIMARYEMAIL,      "Primär Email");
define(PARENT,            "Huvudprodukt");
define(PASSWORDREMINDER,  "Påminnelse om Lösenord");
define(PACKAGERELATIONSHIPS,"Kopplingar mellan Paket");
define(PRECENTDISCOUNT,   "% Rabatt");
define(PRINTSCREEN,       "Skriv ut Skärmen");
define(PRICEOVERRIDE,     "Åsidosätt Pris");
define(PLEASESELECTCAT,   "Var vänlig och välj en kategori nedan.");
define(PHPINFORMATION,    "PHP Information");
define(PHPSETTINGS,       "PHP Konfiguration");
define(PING,              "Ping");
define(PORT,              "Port");
define(POSTGRES,          "PostGres");
define(PROFIT,            "Förtjänst");
define(PROCESSING,        "Bearbetning");

# v3 - Q
define(QUESTION,          "Frågor");
define(QUICKPAYMENTS,     "Snabbetalningar");

# v3 - R
define(REACCURRING,       "Återkommande");
define(RAWPASSWORD,       "Raw Lösenord");
define(RESPONSE,          "Mina Kommentarer");
define(RENEWPACKAGES,     "Förnyade Paket");
define(REMINDME,          "Påminn Mig");
define(REGISTERBALANCE,   "Registrera Balans");
define(REGISTEREDON,      "Registrerad på");
define(REQUIREDFILEDS,    "Var vänlig och fyll in alla <font color=\"red\">*</font> markerade fält. Dessa är obligatoriska.");
define(REGISTERED,        "Registrerade");

# v3 - S
define(SALESTAX,          "MOMS");
define(SEARCHTRANSACTIONS,"Sök Transaktioner");
define(SECURELOGIN,       "Säker Server");
define(SECONDARYCONTACT,  "Andrahands Kontakt");
define(SECONDARYEMAIL,    "Andrahands E-Post");
define(SERVERINFO,        "Server Information");
define(SERVERSTATS,       "Server Statistik");
define(SETUPRELATION,     "Konfigurera delpaket");
define(SETTINGS,          "Konfiguration");
define(SELECTEMAIL,       "Välj E-Post Mall");
define(SELECTPACKAGE,     "Välj Paket");
define(SELECTINVOICE,     "Välj Faktura");
define(SETACTIVE,         "Sätt Aktiv");
define(SUPPORT,           "Support");
define(SUPPORTDESK,       "Support Desk");
define(SUPPORTSEARCH,     "Support Sök");
define(SYSTEMTIME,        "System Tid");
define(SYSTEMUTILITIES,   "System Hjälpmedel");
define(SYSTEMSETUP,       "System Konfig");
define(SUPPORTLOGS,       "Support Loggar");
define(SEARCHAGAIN,       "Sök Igen");
define(SECONDARYCONTACTNAME,"Andrahands Kontakt");
define(SECTIONA,          "Del A");
define(SECTIONB,          "Del B");
define(SELECT2,           "Välj");
define(SELECTA,           "Välj A");
define(SELECTPACKAGE,     "Välj Ert Paket");
define(SIGNUPINVOICE,     "Beställning Faktura");
define(SIGNUPPAYMENT,     "Beställning Betalning");
define(SIGNINTOADDTOYOURACCOUNT,"Logga in för att lägga till denna order till ert konto.");
define(SKIPDOMAIN,        "Behöver Ingen Domän");
define(STARTOVER,         "Börja Om");

# v3 - T
define(THISORDERADDED,    "<br>Denna order har by lagts till till ert konto.<br><br>Ni kan logga in för att titta på eller betala er faktura online.<br>");
define(TRANSFERMYDOMAIN,  "Transferera Min Domän");
define(TECH,              "Tekniker");
define(TLDCONFIG,         "TLD Konfig");
define(TLDSTATS,          "TLD Statistik");
define(TRACEROUTE,        "TraceRoute");
define(TOTALAFFILIATEHITS,"Totalt antal aktiva partner träffar.");
define(TOTALCOUPONHITS,   "Totalt antal aktiva kupong träffar.");

# v3 - U
define(UPGRADETEXT,       "Ni bör beakta en uppgradering!");
define(UPGRADENOTNEEDED,  "Ni har den senaste versionen!");
define(UPTIME,            "Uptid");
define(UTILITIES,         "Hjälpmedel");
define(UPDATE,            "Uppdatera");
define(USER,              "Användare");

# v3 - V
define(VALIDONLYFORNEW,   "Endast giltlig för NYA kunder.");
define(VAT,               "MOMS");
define(VIEWGRAPH,         "Visa Graf");
define(VIEWFEATURES,      "Visa Egenskaper");
define(VORTECHPACKAGESETUP,"Signup Package Setup & Stats");
define(VALIDATEEMAIL,     "Validera E-Post");
define(VERIFYPASSWORD,    "Verifiera Lösenord");
define(VERIFYMYORDER,     "Verifiera Min Order");
define(VARIABLE,          "Variabel");
define(VISAMASTERCARD,    "Visa &amp; MasterCard");
define(VVORTECH,          "Aktivera Vortech Display");
define(VVALUE,            "Ingångsvärde som Standard (Default)");
define(VAPPEND,           "Variabel Lägg till");
define(VADMINONLY,        "Aktivera Bara Admin");
define(VMAXLENGTH,        "Input MaxLängd");
define(VSIZE,             "Input Storlek");
define(VTYPE,             "Input Typ");
define(VTITLE,            "Input Titel");
define(VREQUIRED,         "Variabel Obligatorisk");
define(VACTIVE,           "Aktivera Variabel");
define(VEXTRACLIENTINFOCONFIG, "Anpassade Kund Info Fält");

# v3 - W
define(WORLDPAYPAYMENT,   "WordPay Payment");
define(WELCOMEEMAIL,      "Välkomstbrev");
define(WHOISMATCH,        "Whois Match");
define(WHOISSERVER,       "Whois Server");
define(WHATISCVV2,        "Vad är ett CVV2 nummer?");

# v3 - Y
define(YOURVERSION,       "Er Version");
define(YOURLOGININFORMATION,"Er Konto Information");
define(YOURLOGININFOEMAILED,"Er konto information har<br>skickats per e-post till er.");
define(YOURCUSTOMORDER,   "Sammanfattning av Er Beställning");

##
##
##
##
##

## VERSION 2.02 ADDITIONS (NOT COMPLETE)
define(UPDATESTATUS,      "Ändra Status");
define(DELETEALL,         "tag bort allt");
define(TOTALDECLINED,     "Totalt Ej Godkända Debiteringar");
define(TOTALERROR,        "Totalt Felaktiga Debiteringar");
define(NORENEWAL,         "FÖRNYA INTE");

## VERSION 2.0 ADDITIONS (NOT COMPLETE)
define(ACCOUNTDBS,        "Konto DBs");
define(ACCOUNTPOPS,       "Konto POPs");
define(ADMINCONFIG,       "Admin Konfig");
define(APPLYPAYMENTSOR,   "Påför Betalningar eller Skicka om Fakturor!");
define(BYCLIENT,          "Efter Klient Namn");
define(BYDOMAIN,          "Efter DomänNamn");
define(BYEMAIL,           "Efter E-post Adress");
define(CLIENTPACKS,       "Klient Paket ... Beräknad Månatlig Inkomst!");
define(CONFIG,            "Konfig");
define(CONFIRMEMAIL,      "Verifiera E-Post Adress");
define(CURRENTEMAIL,      "Er Nuvarande E-Post Adress");
define(DBT,               "DBType");
define(DOMAINEXT,         "Domän Ext");
define(DOMAINSTATSSEE,    "Domän Statistik ... Visa Förfallodatum!");
define(MAINCONFIG,        "Huvudsystem Konfig");
define(NEWCLIENTS,        "Nya Klienter ... Sätta upp & Ändra Status!");
define(NEWTODOITEMS,      "Nya AttGöra Saker ... Var vänlig Visa!");
define(PAYMENTSCONFIG,    "Betalningar Konfig");
define(PAYWITHWORLDPAY,   "Betala Med  WorldPay");
define(QUICKSTATS,        "Översikt Statistik");
define(QUICKFIND,         "SnabbSökning");
define(SELECT,            "--- Välj ---");
define(SEEDETAILS,        "Se Detaljer");
define(SETTLE,            "Fastställa");
define(SUBTOTAL,          "Delsumma");
define(SYSTEMCONFIG,      "System Konfig");
define(SYSTEMDISPLAY,     "System Visa");
define(TAXDUE,            " MOMS");
define(THEMEBLUECONFIG,   "Tema: Blue Konfig");
define(THEMEGREENCONFIG,  "Tema: Green Konfig");
define(THEMEDEFAULTCONFIG,"Tema: Default Konfig");
define(TOTALPACKAGES,     "Totalt Paket");
define(VORTECHCONFIG,     "Vortech Beställning Konfig Type1");
define(VORTECHCONFIG2,    "Vortech Beställning Konfig Type2");
define(VORTECHSF,         "Vortech Beställning Formulär");
define(WHATSTHIS,         "Vad är Detta?");
define(WHOISSTATS,        "Whois Statistik");
define(WORLDPAY,          "WorldPay");

## A-Z Listing of ENGLISH Defines
# <-- Added For Vortech Signoff Form --> #
define(ACCOUNTINFO,       "Konto Information");
define(ACCOUNTSETUPASAP,  "Ert konto kommer att sättas upp så fort som möjligt.");
define(ADDFRONTPAGE,      "Lägg till FrontPage Extensions");
define(ANINVOICESENT,     "En faktura eller ett kvitto har skickats till er.");
define(CALCPRICE,         "Räkna Ut Pris");
define(CARDTYPE,          "Typ av kort");
define(CCCODE,            "CVV2");
define(CHANGEPACKAGE,     "Ändra Paket eller Betalningsperiod");
define(CHECK,             "PostGiro");
define(CHECKINVOICE,      "PostGiro");
define(CHOOSEAPACKAGE,    "Välj ett Webbhotell Paket");
define(CLEAR,             "Rensa");
define(CONFIGERROR,       "Konfigurationen stämmer inte.. Var vänlig korrigera eventuella fel i er konfiguration.");
define(CONTRACTTERM,      "Betalningsperiod");
define(CONTACTINFO,       "Kontakt Information");
define(CREDITCARD,        "Kreditkort");
define(DATATRASNFER,      "Data Transfer");
define(DOMAINNAMESEARCH,  "Sök Efter Domännman");
define(DOMAINSTATUS,      "Status Domännman");
define(DOMAINSTATUSREG,   "Status Domännman: Redan Registrerad - Behövs Inte Registreras.");
define(DOMAINVERIFICATION,"Verifiering av Domännman");
define(DONOTREGISTER,     "Registrera INTE just nu.");
define(ERRORS,            "Fel");
define(FEADDRESS,         "Var vänlig ange er fullständiga adress!");
define(FECCINFO,          "Var vänlig ange ALL information angående ert kreditkort!");
define(FECCINVALID,       "Ert kreditkortnummer är ogiltligt!");
define(FEDOMAIN,          "Ert domännman saknas!");
define(FEEMAIL,           "Er e-post adress är ogiltlig!");
define(FEEXPDATE,         "Ert kreditkorts giltlighetstid är ogiltligt!");
define(FENAME,            "Var vänlig ange ert namn!");
define(FEUSERNAME,        "Var vänlig välj ett användarnamn!");
define(FEPASSWORD,        "Angivna lösenord överensstämmer inte!");
define(FEPAYMENT,         "Det är något fel med er betalningsmetod!");
define(FEPHONE,           "Var vänlig ange ert telefonnummer!");
define(FETERMS,           "Ni har inte godkänt våra \"Villkor och Förutsättningar för användning\"!");
define(FREE,              "GRATIS");
define(FRONTPAGE,         "FrontPage");
define(FOR1YEAR,          "för 1 år");
define(FOR2YEARS,         "för 2 år");
define(FRAUDCHECK1,       "För att skydda oss gentemot bedrägerier har vi");
define(FRAUDCHECK2,       "är er IP adress. Denna har vi nu dokumenterat.");
define(FRAUDCHECK3,       "dokumenterat den tidpunkt vid vilken ni lade er beställning.");
define(IHAVEREAD,         "Jag har läst och godkänt de Villkor och Förutsättningar För Användning som gäller");
define(MYSQLDB,           "MySQL Databas");
define(NOIAMNOTTHEOWNER,  "Nej, jag äger inte");
define(NOMATCH,           "Ingen Överensstämde");
define(OTS,               "Uppläggningsavgift");
define(ONEYEAR,           "1 år");
define(PAYMENTMETHOD,     "Betalningsmetod");
define(PAYWITHPAYPAL,     "Ni kan betala genom PayPal genom att klicka på länken nedan:");
define(PAYPAL,            "PayPal");
define(PPT,               "Pris Per Period");
define(PRA,               "Betalning För Denna Månad");
define(PLEASEGOBACK,      "Var vänlig Gå Tillbaka Till");
define(PLEASETRYAGAIN,    "Var vänlig försök igen.");
define(PLEASEPRINT,       "Var vänlig och skriv ut denna sida som framtida referens.");
define(PLEASEPICKADOMAIN, "Var vänlig och välj det domännamn som ni kommer att använda med webbhotellet.");
define(PROCESSMYORDER,    "Utför Min Beställning");
define(PURCHASES,         "Beställning(ar)");
define(PURCHASEINFO,      "Beställningsinformation");
define(REFERREDBY,        "Hänvisad av");
define(REGFEE,            "Registreringsavgift");
define(REGISTER,          "Registrera");
define(REGISTERDOMAIN,    "Registrera Domännamn");
define(REGISTERFORME,     "Registrera denna domän åt mig");
define(RESULTSFOR,        "Resultat för");
define(RESULTS,           "Resultat");
define(SENDPAYMENTTO,     "Ställ Betalning Till");
define(SENDQUESTIONS,     "Skicka frågor till");
define(SERVICESIGNUP,     "Service Beställning");
define(SIGNUPCOMPLETED,   "Beställningsprocessen Klar!");
define(SIGNUPEMAILSUBJECT,"Faktura/Kvitto för");
define(SUBMITINFO,        "Skicka Information");
define(SUBMITCHECK1,      "Innan ni skickar iväg formuläret, verifiera att");
define(SUBMITCHECK2,      "all nödvändig information har fyllts i");
define(SUBMITCHECK3,      "all information är utan fel");
define(SUBMITCHECK4,      "all information är korrekt och sann");
define(SKIPERROR1,        "Ni försöker gå förbi de nödvändiga stegen för att beställa");
define(SKIPERROR2,        "Tjänster.");
define(STEP4,             "Steg 4");
define(STEP5,             "Steg 5");
define(THANKYOUFOR,       "Tack för att beställt av");
define(THEREARENOPACKS,   "Det finns inga Webbhotell paket tillgängliga");
define(THISDOMAININVALID, "Detta domännman är ogiltligt.");
define(TRANSFER,          "Överföra");
define(TWOYEARS,          "2 år");
define(VIEWSITE,          "Visa Webbplats");
define(VIEWWHOIS,         "Visa WHOIS Resultat");
define(WEBSPACE,          "Webbutrymme");
define(YESIAMTHEOWNER,    "Ja, jag äger");
define(YEARREG,           "Års Domännamnregistrering");
define(ZIPPOSTAL,         "Postnummer");

# <-- Added in Version 1.9.3 --> #
define(BILLINGINFO,       "Fakturainformation");
define(CARDINFORMATION,   "Kreditkort Information");
define(CARDHOLDER,        "Innehavares Namn");
define(CARDBANK,          "Utförande bank Bank");
define(CCNUMBER,          "Kredidkortnummer");
define(CLICKONLYONCE,     "KLICKA _EN_ GÅNG FÖR ATT PLACERA ER ORDER.");
define(CONTINUE_t,        "Fortsätt");
define(CUSTOMSIGNUPEMAIL, "Vortech");
define(DISPLAY,           "Visa");
define(EXPDATELONG,       "Giltlighetsdatum");
define(PACKINFOSELECT,    "Information om Paket");
define(PLEASEVERIFY,      "Var vänlig och verifiera er order nedan och klicka sedan på Skicka för att placera ordern.");
define(PLEASEFILLINALL,   "Var vänlig och fyll i alla  $is_required nödvändiga fält.");
define(SIGNUPDISPLAY,     "Vortech Typ");
define(TELEPHONE,         "Telefonnummer");
define(VIEWCC,            "Visa Decrypterat CC");
define(YOUWILLVERIFY,     "Ni kommer att få möjlighet att verifiera er order innan den placeras.");

# <-- Added in Version 1.9.2 --> #
define(NOMATCHESFOUND,    "Fanns inget som stämde med era kriteria.");
define(PACKAGESUMMARY,    "Sammanfattning Paket");
define(DOMAINSUMMARY,     "Sammanfattning Domän");
define(INVOICESUMMARY,    "Sammanfattning Faktura");
define(NEXTRENEWAL,       "Nästa Förnyelse");
define(PRORATED,          "ProRated");
define(PAYPERIOD,         "PayPeriod");
define(SUBTOTAL,          "Delsumma");
define(CREDIT,            "Kredit");

# <-- General Transaltions --> #
# A
define(ACCESSDENIED,      "Åtkomst Nekad");
define(ACCOUNT,           "Konto");
define(ACCOUNTDETAILS,    "Konto Detaljer");
define(ACTION,            "Action");
define(ACTIVE,            "Aktiv");
define(ADD,               "+");
define(ADDRESS,           "Adress");
define(ADDITIONALSQL,     "Ytterligare SQL Genvägar");
define(ADMIN,             "Admin");
define(ALL,               "ALLA");
define(ALLINVOICE,        "Alla Fakturarelaterade Emails.");
define(ALLGENERAL,        "Alla Allmänna Emails.");
define(ALREADYCLICKED,    "Ni har redan klickat på Skicka knappen. Var vänlig och vänta....");
define(AMEX,              "American Express");
define(AMOUNT,            "Summa");
define(AMOUNTPAID,        "Betald Summa");
define(ANNUALLY,          "Årligen");
define(AUTHCODE,          "AuthKod");
define(AUTHNETBATCH,      "Authorize.net Batch");
define(AUTHRET,           "AuthRet");
define(AUTOUPDATED,       "Uppdateras Automatiskt");
define(APPLYPAYMENT,      "Påför Betalning");
define(ASPERCENTAGE,      "i procent");
define(AVS,               "AVS");
define(AVSCODE,           "AVSKod");

# B
define(BATCH,             "Batch");
define(BATCHDATE,         "Batch Datum");
define(BATCHDATEINVLAID,  "BatchStamp Datum Är Ogiltligt");
define(BATCHDETAILS,      "Batch Detaljer");
define(BATCHID,           "BatchID");
define(BATCHINFO,         "Batch Info");
define(BATCHREPORTS,      "Batch Rapporter");
define(BATCHSETUP,        "Batch Setup");
define(BILLINGCYCLE,      "Faktureringsperiod");
define(BILLINGMETHOD,     "Faktureringssätt");
define(BILLINGREPORTS,    "Fakturering Rapporter");
define(BODY,              "Body");
define(BYMONTH,           "Per Månad (YYYY/MM)");

# C
define(CANNOTSAY,         "Ni kan inte uppge");
define(CC,                "CC");
define(CCA,               "Kreditera Kundkonto");
define(CCBATCH,           "CC Batch");
define(CCEXAMPLETRANSLATE,"Typ av Kreditkort & Last 4 digits of the CC on file: Ex. MasterCard - 0005");
define(CCNUM,             "CC Nummer");
define(CCNUMINVALID,      "Kreditkortnummer är ogiltligt");
define(CCSINGLE,          "CC Single");
define(CHANGECLIENTPW,    "Ändra Kundens Lösenord");
define(CHANGEMYPASSWORD,  "Ändra Mitt Lösenord");
define(CHARGEIT,          "Charge It");
define(CHECK,             "PostGiro");
define(CHECKFORPRINT,     "Kryssa i för <i>Skrivarvänligt Format</i>.");
define(CLASSICBLUE,       "Klassikt Blå");
define(CLIENT,            "Kund");
define(CLIENTNOTES,       "Kund Anteckningar");
define(CLIENTPWNOMATCH,   "Kund Lösenord Stämmer Inte Överens");
define(CLIENTPWTOOSHORT,  "Kund Lösenord är för kort");
define(CLIENTREPORTS,     "Kund Rapporter");
define(CLIENTID,          "KundID");
define(CLIENTS,           "Kunder");
define(CLIENTSEARCH,      "Kund Sökning");
define(CLIENTSTATUS,      "Kund Status");
define(CLIENTSTATS,       "Kund Statistik");
define(CLIENTADMIN,       "Kund Admin");
define(CONTACTUS,         "Kontakta Oss");
define(CONTINUETOCOMPOSE, "Fortsätt för att skriva E-post brev");
define(CITY,              "Stad");
define(COMINGSOON,        "Kommer Snart");
define(COMMENTS,          "Kommentar");
define(COMPANY,           "Företag");
define(COMPORDOM,         "Företag eller domännamn");
define(COMPLETED,         "Utförd");
define(COMPOSE,           "Skapa");
define(CONTACTINFO,       "Kontaktinformation");
define(COUNTRY,           "Land");
define(COUNTRYEXAMPLE,    "EX: \"SE\"");
define(CREATEDON,         "Skapad den");
define(CREATEDINVALID,    "Skapat Datum Ogiltligt");
define(CREDITS,           "Krediteringar");
define(CURRENT_BATCH,     "Nuvarande Batch");
define(CURRENTPW,         "Nuvarande Lösenord");

# D
define(DATECREATED,       "Datum Skapad");
define(DATEFORMAT,        "MM/YYYY");
define(DATEFORMAT2,       "YYYY/MM/DD");
define(DATEFORMAT3,       "YYYY/MM/01");
define(DATEPAID,          "Datum Betald");
define(DBN,               "DBName");
define(DBU,               "DBUser");
define(DBP,               "DBPass");
define(DEFAULTMB,         "Default MB");
define(DESCRIPTION,       "Beskrivning");
define(DETAILS,           "Detaljer");
define(DINERSCLUB,        "Diners Club/Carte Blanche");
define(DISCOVER,          "Discover Card");
define(DISCOUNT,          "Rabatt");
define(DOADD,             "Lägg till Ny");
define(DOEDIT,            "Editera");
define(DOMAIN,            "Domän");
define(DOMAINS,           "Domäner");
define(DOMAINMENU,        "Domän Menu");
define(DOMAINNAME,        "Domän Namn");
define(DOMAINSTATS,       "Domän Statistik");
define(DOMAINREPORTS,     "Domän Rapporter");
define(DOMUSER,           "DomUser");
define(DOMPASS,           "DomPass");
define(DOWNLOADNOW,       "Ladda Ned NU");
define(DETAILS,           "Detaljer");
define(DUE,               "Förfaller");
define(DUEDATE,           "Förfallodatum");
define(DUEINVALID,        "Förfallodatum är ogiltligt");

# E
define(EB,                "Exportera Batch");
define(ECHECK,            "eCheck");
define(EINTAPM,           "Ange Faktura Nummer för \"Påför Betalning\" Manuellt");
define(EMAIL,             "E-Post");
define(EMAILADMIN,        "E-Post Admin");
define(EXIT_t,            "exit");
define(EMAILCONFIG,       "E-Post Konfig");
define(EMAILMSG,          "E-Post Meddelande");
define(EMAILERRORMSG,     "Ett fel uppstod med e-post servern.<br>Var vänlig och försök igenom om ca 1 timma.<br>Tack.");
define(EMAILINVALID,      "E-Post Adress är Ogiltlig");
define(EMAILSHORTCUTS,    "E-Post Genvägar");
define(EMAILSUCCESS1,     "Ert E-post brev har skickats till ");
define(EMAILSUCCESS2,     "Vi kommer att svara er inom 12-24 timmar.<br>Tack.");
define(EMAILSTATUS,       "E-Post Status");
define(EMAILSTATS,        "E-Post Statistik");
define(EMAILSEARCH,       "E-Post Sök");
define(EMAILID,           "E-PostID");
define(EMPTY_t,           "tom");
define(ENCRYPTCC,         "Krypera Kreditkort");
define(ENCRYPTIONKEY,     "Krypteringsnyckel");
define(ENGLISH,           "Engelska");
define(ENROUTE,           "enRoute");
define(ERROR,             "fel");
define(ERRORPLEASELOGIN,  "Ett fel har uppstått. Logga in igen.");
define(EXP,               "Exp.");
define(EXPIRATIONDATE,    "Förfallodatum");
define(EXPIRATIONDATE2,   "Förf. datum");
define(EXPIRESINVALID,    "Förfallodatum är Ogiltligt");
define(EXPIRES,           "Förfaller");
define(EXPIRING,          "Förfallande");
define(EXPIRINGDOM,       "Förfallande Domäner");
define(EXPORT,            "Exportera");
define(EXPORTBATCH,       "Export Batch");
define(EXPTHISMONTH,      "Förf. Denna Månad");
define(EXPNEXTMONTH,      "Förf. Nästa Månad");

# F
define(FAX,               "Fax");
define(FAXINVALID,        "Telefon (Fax) Är Ogiltlig");
define(FEATURE,           "Egenskap");
define(FEATURES,          "Egenskaper");
define(FILTER,            "Filter");
define(FIRST,             "Först");
define(FIRSTNAME,         "Förnamn");
define(FR,                "Första Förnyelser");
define(FOOTER,            "Footer");
define(FOREMAILONLY,      "FOR EMAIL_ID \"1\" ONLY");
define(FORM,              "Formulär");
define(FROM,              "Från");

# G
define(GI,                "Generera Fakturor");
define(GB,                "Generera Batch");
define(GO,                "Gå");
define(GOBACK,            "Gå Tillbaka");

# H
define(HEADING,           "Huvud");
define(HELPDOCS,          "Hjälpdokumentation");
define(HELLO,             "Hej");
define(HOME,              "Framsida");
define(HIGH,              "High");
define(HINT,              "TIPS");
define(HOWDOESITWORK,     "Hur fungerar det?");
define(HOWDOESITWORKSTEPS,"\"Steg 1\" kan utföras när som helst under månaden. Fakturering kommer att ske i efterhand (pro-rated) och alla klient paket  uppdateras automatiskt!<br><br>\"Steg 2\" kommer att förbereda batch hanteringen.<br><br>\"Steg 3\" kommer att genomföra alla batchar genom  Authorize.net, uppdatera varje faktura, och förbereda batchen för nästa månad.<br><br>Obs: Om ni exporterar batchen, då kommer ni att behöva uppdatera varje faktura manuellt och likaså sluföra batchen inför nästa månad.");
define(HTMLOUTPUT,        "Detta är det faktiska HTML resultatet av faktura tabellen.");

# I
define(ID,                "ID");
define(IDORNUM,           "ID eller Check Nummer");
define(IATB,              "Faktura Adderad till Batch");
define(IFCC,              "Om CC");
define(INACTIVE,          "Inaktiv");
define(INVALIDPASSWORD,   "Ogiltligt Lösenord");
define(INVNUM,            "InvNum");
define(INVTYPE,           "Fakturatyp");
define(INVNOWDUE,         "Fakturor som Förfaller Nu");
define(INVANDBILLING,     "Fakturor");
define(INVOVERDUE,        "Fakturor som Har Förfallit");
define(INVOICE,           "Faktura");
define(INVOICESTATS,      "Faktura Statistik");
define(INVOICESEARCH,     "Faktura Sök");
define(INVOICENUM,        "Faktura Nummer");
define(INVOICES,          "Fakturor");
define(INVOICESPAID,      "Betalda Fakturor");
define(INVOICESDUE,       "Förfallna Fakturor");
define(IP,                "IP");
define(IPFORMAT,          "IP Adress eller Parkerad Domän");
define(IWRITEOWN,         "Jag skriver min egen.");

# J
define(JOKER,             "Joker");
define(JCB,               "JCB");

# K
define(KEYWORDS,          "Nyckelord");

# L
define(LANGUAGE,          "Språk");
define(LAST,              "Last");
define(LASTNAME,          "Efternman");
define(LEVEL,             "Nivå");
define(LOGINAS,           "Logga in som");
define(LOW,               "Låg");

# M
define(MAKEPAYMENTS,      "utför betalningar");
define(MASTERCARD,        "MasterCard");
define(MATCHESFOR,        "träffar för");
define(MATCHESFOUND,      "träffar hittade för");
define(MESSAGE,           "Meddelande");
define(MEDIUM,            "Medium");
define(MEMBERSINCE,       "Medlem Sedan");
define(METHOD,            "Metod");
define(MISC,              "Div");
define(MISCREPORTS,       "Div. Rapporter");
define(MISSINGORINVALID,  "Saknad eller Ogiltlig Faktura Id.");
define(MONITOR,           "Bevaka");
define(MONTHLY,           "Månadsvis");
define(MORE,              "mer");
define(MYDOMAINS,         "Mina Domäner");
define(MYINVOICES,        "Mina Fakturor");
define(MYINFORMATION,     "Min Information");
define(MYMENU,            "Min Menu");
define(MYPACKAGES,        "Mina Paket");
define(MYSEARCHHELP,      "Ni kan genomföra säker betalning med kreditkort on-line genom att klicka på en faktura som ej betalts.<br>Klicka sedan helt enkelt på \"Betala Online\" knappen för att betala just den fakturan med kreditkort.<br><br><a href=$page?op=view&tile=$tile&id=due>See Alla Obetalda Fakturor</a>.");
define(MYSTATS,           "Min Statistik");
define(MYSTATUS,          "Min Status");

# N
define(NA,                "n/a");
define(NAME,              "Namn");
define(NAMEREG,           "Name Registrars");
define(NETSOLUTIONS,      "Network Solutions");
define(NEW_t,             "Ny");
define(NEWCC,             "Nytt CC Number");
define(NEWPW,             "Nytt Lösenord");
define(NEWEXPDATE,        "Nytt Förfallodatum");
define(NEWPWMATCH,        "Nytt Lösenord Stämmer INTE överens");
define(NEWPWSHORT,        "Nytt Lösenord är För Kort");
define(NEXT,              "nästa");
define(NEXTMONTH,         "Nästa Månad");
define(NO,                "Nej");
define(NOIVOICENUM1,      "Det finns inget Faktura Nummer");
define(NOIVOICENUM2,      "I Ert Konto.");
define(NONE,              "ingen");
define(NOPACKSEARCH,      "Inga paket funna som matchar er sökning för ");
define(NOPACKFOUND,       "Inga paket funna som matchar er sökning.");
define(NOINVSEARCH,       "Inga fakturor funna som matchar er sökning för");
define(NOINVFOUND,        "Inga fakturor funna som matchar er sökning.");
define(NODOMSEARCH,       "Inga domäner funna som matchar er sökning för");
define(NODOMFOUND,        "Inga domäner funna som matchar er sökning.");
define(NORECFOUND,        "Inget funnet för");
define(NORMAL,            "Normal");
define(NOTHINGENTERED,    "inget angivet");
define(NOWDUE,            "A&nbsp;T&nbsp;T&nbsp;<br>B&nbsp;E&nbsp;T&nbsp;A&nbsp;L&nbsp;A");
define(NUMAPPROVED,       "Antal Godkända");
define(NUMDECLINED,       "Antal Avslagna");
define(NUMERROR,          "Antal Felaktiga");
define(NT,                "NT");

# O
define(ONETIME,           "Engång");
define(OTHER,             "Annat");
define(OR_t,              "eller");
define(OVERDUE,           "F&nbsp;Ö&nbsp;R&nbsp;<br>F&nbsp;A&nbsp;L&nbsp;L&nbsp;I&nbsp;T");

# P
define(PACKAGE,           "Paket");
define(PACKAGES,          "Paket");
define(PACKAGEID,         "PaketID");
define(PACKAGEMENU,       "Paket Menu");
define(PACKAGENAME,       "Paket Namn");
define(PACKAGESTATS,      "Paket Stat");
define(PACKAGESEARCH,     "Sök Paket");
define(PAID,              "Betald");
define(PAID2,             "B&nbsp;E&nbsp;T&nbsp;A&nbsp;L&nbsp;D");
define(PAIDINVALID,       "Ogiltligt Betalningsdatum");
define(PAGE,              "sida");
define(PACKAGEADMIN,      "Paket Admin");
define(PASSWORD_t,        "Lösenord");
define(PLEASELOGINTOBEGIN,"Var vänlig och logga in.<br>Har ni glömt er lösenord, kontakta support@etableraweb.com.");
define(PAYMENTINFO,       "Betalningsinformation");
define(PAYMENTMETHOD,     "Betalningssätt");
define(PAYONLINE,         "Betala Online");
define(PENDING,           "Avvaktande");
define(PHONE,             "Telefon");
define(PHONEFORMAT,       "00-000 00 00");
define(PHONEIVALID,       "Ogiltligt Telefonnummer");
define(PLEASEFILLIN,      "Var vänlig och fyll i följande fält.");
define(PLEASESELOPTIONS,  "Var vänlig ange era alternativ för visning.");
define(PLEASESELMENU,     "Var vänlig och välj från menyn.");
define(POSTPONED,         "Uppskjuten");
define(PREV,              "föreg");
define(PRIORITY,          "Prioritet");
define(PRICE,             "Pris");
define(PRICEFORMAT,       "0.00");
define(PWFORMAT,          "($password_length tecken: Bara Aa-Zz & 0-9, dvs inga svenska tecken.)");
define(PWREQUIRED,        "Lösenord är nödvändigt för att ta bort.");

# Q
define(QUARTERLY,         "Kvartalsvis");
define(QTY,               "Ant."); // Quantity
define(QUANTITY,          "Kvantitet");

# R
define(REALNAME,          "Verkligt Namn");
define(RENEWDATE,         "Förnyelse Datum");
define(RENEWDATEINVALID,  "Förnyelse Datum är Ogiltligt");
define(RENEWONINVALID,    "Förnyelse Detta Datum är Ogiltligt");
define(RENEWON,           "Förnyat den");
define(REQUIRED,          "obligatoriskt");
define(REGISTRAR,         "Registrar");
define(REPORTS,           "Rapporter");
define(RESEND,            "Skicka om Faktura");
define(RTM,               "Förnyad Denna Månad");
define(RUN,               "Kör");
define(RUNBATCH,          "Kör Batch");

# S
define(SAD,               "Sätt Upp Konto Detaljer");
define(SCP,               "Sätt Upp Kund Paket");
define(SCD,               "Sätt Upp Kund Domäner");
define(SEARCH,            "Sök");
define(SEARCHBATCHDETAILS,"Sök Batch Detaljer");
define(SECUREPAYMENTS,    "ModernBill .:. Säker Betalning");
define(S_E_L_E_C_T,       "V Ä L J");
define(SELECTREPORT,      "Välj en Rapport");
define(SEMIANNUALLY,      "Halvårsvis");
define(SEND,              "Skicka");
define(SENDEMAIL,         "Skicka E-Post");
define(SENDPACKSUM,       "Skicka Paket Sammanfattning");
define(SENDDOMSUM,        "Skicka Domän Sammanfattning");
define(SENDACTDETAILS,    "Skicka Konto Detaljer");
define(SENDINVHISTORY,    "Skicka Faktura Historia");
define(SETUP,             "Sätt upp");
define(SEPBYCOMMA,        "Separerad med komman");
define(SERVNAME,          "ServName");
define(SERVTYPE,          "Server Typ");
define(SERVTYPE2,         "ServType");
define(SHORTCUT,          "Genväg");
define(SHORTCUTHINTS,     "Dessa kan användas för GENERAL & INVOICE e-post mallar!");
define(SIGNATURE,         "Signatur");
define(SNC,               "Sätt upp Ny Kund");
define(SPECIALSHORTCUTS,  "Speciella Genvägar för Fakturor BARA");
define(SPECSHORTCUTHINTS, "ALWAYS USE EMAIL_ID \"1\" AS THE EMAIL TEMPLATE FOR \"CREDIT CARD PAYMENT METHODS\" AND EMAIL_ID \"2\" AS THE EMAIL TEMPLATE FOR \"CHECK PAYMENT METHODS\"!");
define(SQLWARNING,        "Att tillåta SQL anrop kan innebära en viss säkerhetsrisk. Detta kan stängas av i konfig filen!");
define(STARTDATE,         "Start Datum");
define(STARDATEINVALID,   "Start Datum är Ogiltligt");
define(STATE,             "Kommun");
define(STATEEXAMPLE,      "EX: \"STH\"");
define(STATEREGION,       "Kommun/Region");
define(STATS,             "Statistik");
define(STATUS,            "Status");
define(STEP1,             "Steg 1");
define(STEP2,             "Steg 2");
define(STEP3,             "Steg 3");
define(STRREPLANCEHINT,   "%%INVOICE_xxx%% kommer att översättas endast om \"Faktura Typ\" är vald ovan!");
define(STM,               "Startad denna Månad");
define(SUBMIT,            "Skicka");
define(SUBJECT,           "Ämne");
define(SYSTEM,            "System");

# T
define(TESS,              "Totalt E-Post brev Framgångsrikt Skickade");
define(TENS,              "Totalt E-Post brev INTE SKICKADE [Fel]");
define(THEME,             "Tema");
define(THETABLE1,         "Tabellen");
define(THETABLE2,         "kan inte editeras.");
define(THETABLE3,         "finns inte i databasen");
define(THISMONTH,         "Denna Månad");
define(THISISADMININTER,  "Detta är <b>\"admin gränssnittet\"</b>.");
define(THREEDIGIT,        "Visa or MasterCard: 3-Digit Security Kod");
define(TIGEN,             "Totalt Fakturor Genererade");
define(TIMESTAMP,         "TidsStämpel");
define(TITLE,             "Titel");
define(TO,                "Till");
define(TODO,              "AttGöra");
define(TODOSTATS,         "AttGöra Statistik");
define(TODOSEARCH,        "AttGöra Sök");
define(TODOID,            "AttGöraID");
define(TODOLIST,          "AttGöra Lista");
define(TOTAL,             "Totalt");
define(TOTAL,             "Totalt");
define(TOTALACTIVE,       "Totalt Aktiva");
define(TOTALCLIENTS,      "Totalt Kunder");
define(TOTALCURRENTBATCH, "Totalt Current Batch");
define(TOTALINACTIVE,     "Totalt Inaktiva");
define(TOTALINVOICES,     "Totalt Fakturor");
define(TOTALDUE,          "Totalt Fakturerat");
define(TOTALNOWDUE,       "Totalt Förfallna");
define(TOTALNEW,          "Totalt Nytt");
define(TOTALPAID,         "Totalt Betalda");
define(TOTALTODO,         "Totalt AttGöra");
define(TOTALWIP,          "Totalt WIP");
define(TOTALPENDING,      "Totalt Avvaktande");
define(TOTALCOMPLETED,    "Totalt Utförda");
define(TOTALPOSTPONED,    "Totalt Uppskjutna");
define(TTLAPPROVED,       "Ttl Apprvd");
define(TTLDECLINED,       "Ttl Declined");
define(TTLDOMS,           "Total Domäner");
define(TTLERROR,          "Ttl Error");
define(TTLPACKS,          "Totalt Paket");
define(TRANSLATESTO,      "Översätts Till");
define(TRANSID,           "TransID");
define(TRANSIDORCHECK,    "TransID eller Check #");
define(TTLEMAILCONFIG,    "Totalt E-Post Konfig");
define(TYPE,              "Typ");

# U
define(UNIX,              "UNIX");
define(UNKOWN,            "okänd");
define(UPDATECC,          "Ändra Kredit Kort");
define(UPDATEMYINFO,      "Ändra Min Information");
define(UPDATEMYCC,        "Ändra Min CC");
define(UPDATEPW,          "Ändra Lösenord");
define(USER,              "Användare");
define(USERNAME,          "Användarnamn");

# V
define(VERIFYDEL,         "Verifiera Tag Bort");
define(VERIFYPW,          "Verifiera Lösenord");
define(VIEWALL,           "visa alla");
define(VIEWALLMYDOMAINS,  "Visa Alla Mina Domäner");
define(VIEWALLPACKAGES,   "Visa Tillg. Paket");
define(VIEWAUTHNETBATCH,  "Visa Authnet Batch");
define(VIEWBATCHSUMM,     "Visa Batch Summary");
define(VIEWCLIENTS,       "Visa Kunder");
define(VIEWCLIENTCREDITS, "Visa Kund Krediter");
define(VIEWCLIENTPACKAGES,"Visa Kund Paket");
define(VIEWDOMAINNAMES,   "Visa Domän Namn");
define(VIEWEXPDOMAINS,    "Visa Förfallande Domäner");
define(VIEWINVOICES,      "Visa Fakturor");
define(VIEWMYINFO,        "Visa Mina Info");
define(VIEWMYPACKAGES,    "Visa Mina Paket");
define(VIEWOVERINVOICES,  "Visa Förfallna Fakturor");
define(VISA,              "Visa");

# W
define(WARNING,           "varning");
define(WELCOME,           "Välkommen");
define(WELCOMEUSERFROM,   "Välkommen! Ni kommer från");
define(WHOIS,             "Whois");
define(WIP,               "AP"); // Work-in-progress Arbete Pågår

# Y
define(YAHOODOM,          "Yahoo Domäns");
define(YES,               "Ja");
define(YOUCANFILTER,      "Ni kan filtrera följande med nedanstående");
define(YOUCANSELECTANY,   "Ni kan välja vilket kombination som helst från nedanstående");
define(YOUCANUSE,         "Ni kan använda");
define(YOURBILLINGMETHOD, "Ert betalninfssätt måste först ändras till \"CC Batch\"");
define(YOURORDERDECLINED, "Er order kan inte utföras för tillfället. Ert kredirkort godtogs inte. Var vänlig och försök senare.");
define(YOURORDEREERROR,   "Ett fel uppstod med er beställning och er beställning kan inte utföras just nu. Var vänlig och försök senare.");
define(YOURORDERSUCCESS,  "Er order har utförts");
define(YOURPW,            "Ert Lösenord");
define(YOURPWINVALID,     "Ert Lösenord är Ogiltligt");
define(YOURSELECTIONS,    "era val är inkluderade här så att ni kan modifiera dessa");

# Z
define(ZIP,               "Postnummer");
define(ZIPFORMAT,         "000 00");
define(ZIPINVALID,        "Postnummer är Ogiltligt");
?>