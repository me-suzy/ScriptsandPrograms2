/*	$Id: clsItemsToHTMLCore.h,v 1.2 1999/02/21 02:22:54 josh Exp $	*/
/*

clsItemsToHTMLCore.h

*/

#ifndef CLSITEMSTOHTMLCORE_INCLUDED

#define NO_KERNEL
#define EVENT_REPORTING

// different operations we support
#undef NIGHTLY_EVERYTHING_PARSE
#define EXPIRE_CURRENT
#define EXPIRE_COMPLETED
#undef HOURLY_PARSE
#undef DESTROY_CATEGORY

#ifdef DEBUG_PETE
// new system
// #define currentAuctionsNew "D:\\activeSearchItems.txt"
#define currentAuctionsNew "\\activeSearchItems.txt"
#define currentAuctionPathNew "D:\\InetPub\\eBayProducts\\new\\current\\%s"
#define currentAuctionPathNoCharNew "D:\\InetPub\\eBayProducts\\new\\current\\"

// #define modifiedAuctionsNew "D:\\modifiedSearchItems.txt"
#define modifiedAuctionsNew "\\modifiedSearchItems.txt"
#define modifiedAuctionPathNew "D:\\InetPub\\eBayProducts\\new\\current\\%s"
#define modifiedAuctionPathNoCharNew "D:\\InetPub\\eBayProducts\\new\\current\\"

// #define completedAuctionsNew "D:\\completedSearchItems.txt"
#define completedAuctionsNew "\\completedSearchItems.txt"
#define completedAuctionPathNew "D:\\InetPub\\eBayProducts\\new\\completed\\%s"
#define completedAuctionPathNoCharNew "D:\\InetPub\\eBayProducts\\new\\completed\\"

// old system
#define currentAuctions "D:searchUpdate.txt"
#define completedAuctions "D:searchUpdateComp.txt"
#define currentAuctionsEverything "D:searchUpdateEverything.txt"
#define completedAuctionsEverything "D:searchUpdateEverythingComp.txt"
#define expiredAuctions "D:searchUpdateExpired.txt"
#define currentAuctionPath "D:\\InetPub\\eBayProducts\\current\\%c"
#define completedAuctionPath "D:\\InetPub\\eBayProducts\\completed\\%c"
#define currentAuctionPathNoChar "D:\\InetPub\\eBayProducts\\current\\"
#define completedAuctionPathNoChar "D:\\InetPub\\eBayProducts\\completed\\"
#define holdingPenPathNoChar "D:\\holding\\"

#else

// new system
// #define currentAuctionsNew "D:activeSearchItems.txt"
#define currentAuctionsNew "\\activeSearchItems.txt"
#define currentAuctionPathNew "F:\\InetPub\\eBayProducts\\current\\%s"
#define currentAuctionPathNoCharNew "F:\\InetPub\\eBayProducts\\current\\"

// #define modifiedAuctionsNew "D:modifiedSearchItems.txt"
#define modifiedAuctionsNew "\\modifiedSearchItems.txt"
#define modifiedAuctionPathNew "F:\\InetPub\\eBayProducts\\current\\%s"
#define modifiedAuctionPathNoCharNew "F:\\InetPub\\eBayProducts\\current\\"

// #define completedAuctionsNew "D:completedSearchItems.txt"
#define completedAuctionsNew "\\completedSearchItems.txt"
#define completedAuctionPathNew "H:\\InetPub\\eBayProducts\\completed\\%s"
#define completedAuctionPathNoCharNew "H:\\InetPub\\eBayProducts\\completed\\"

// old system
#define currentAuctions "D:searchUpdate.txt"
#define completedAuctions "D:searchUpdateComp.txt"
#define currentAuctionsEverything "D:searchUpdateEverything.txt"
#define completedAuctionsEverything "D:searchUpdateEverythingComp.txt"
#define expiredAuctions "D:searchUpdateExpired.txt"
#define currentAuctionPath "D:\\InetPub\\eBayProducts\\current\\%c"
#define completedAuctionPath "D:\\InetPub\\eBayProducts\\completed\\%c"
#define currentAuctionPathNoChar "D:\\InetPub\\eBayProducts\\current\\"
#define completedAuctionPathNoChar "D:\\InetPub\\eBayProducts\\completed\\"
#define holdingPenPathNoChar "D:\\holding\\"
#endif

#define ADULT_CATEGORY 64

#define ADULT_GENERAL           320
#define ADULT_VIDEO             321
#define ADULT_CD                322
#define ADULT_PHOTOGRAHPIC      323
#define ADULT_BOOKS_MAGS		379

#define MOVE_IT			0
#define DELETE_IT		1
#define DELETE_CATEGORY	2

#define APP_NAME "ItemsToHTML"

#endif // CLSITEMSTOHTMLCORE_INCLUDED
