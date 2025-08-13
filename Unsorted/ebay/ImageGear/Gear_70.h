/***************************************************************************/
/* 																								*/
/* MODULE:	Gear_70.h - Add-on for Master AccuSoft ImageGear include file	*/
/* 							contains IG7.0 stuff											*/
/* 																								*/
/* DATE CREATED:	12/07/1997																*/ 
/* 																								*/
/* $Date: 1999/02/21 02:22:43 $																					*/
/* $Revision: 1.2 $																			*/
/* 																								*/
/* Copyright (c) 1996-97, AccuSoft Corporation.  All rights reserved.		*/
/* 																								*/
/***************************************************************************/

/*#ifdef IMAGEGEAR_7_0*/

/***************************************************************************/
/* Compile time version macros                                             */
/***************************************************************************/

#ifdef GEAR_REV_MAJOR
#undef GEAR_REV_MAJOR
#define GEAR_REV_MAJOR  7
#endif

#ifdef GEAR_REV_MINOR
#undef GEAR_REV_MINOR
#define GEAR_REV_MINOR  0
#endif

#ifdef GEAR_REV_UPDATE
#undef GEAR_REV_UPDATE
#define GEAR_REV_UPDATE 28
#endif


#define IG_INTERPOLATION_PRESERVE_WHITE	6
#define IG_INTERPOLATION_PRESERVE_BLACK	7
/***************************************************************************/
/**Batch Processing Defines*************************************************/

#define IG_BATCH_USE_SRC_NAME	0x00

/* values for PROMOTE function                                             */
#define IG_PROMOTE_TO_32            4  

/***************************************************************************/
/***************************************************************************/

#define IG_ROI_IS_RECTANGLE	0x00
#define IG_ROI_IS_ELLIPSE		0x01
#define IG_ROI_IS_POLYGON		0x02

/* values for *lpRegionType in IG_clipboard_paste_available_ex */
#define IG_REGION_IS_RECT			0x00			
#define IG_REGION_IS_NON_RECT		0x01
#define IG_REGION_IS_NOT_AVAIL	0xFF

/* values for nAliasType parameter of IG_display_alias_set()               */
#define IG_DISPLAY_ALIAS_PRESERVE_WHITE   5

/***************************************************************************/
/*non_rect ROI constants*/

#define	IG_CONTROL_NR_ROI_DIB						0x0000	/*read/write, NRA mask HIGEAR*/
#define	IG_CONTROL_NR_ROI_REFERENCE_POINT		0x0001	/*r/w, mask reference point*/
#define	IG_CONTROL_NR_ROI_CONDITION				0x0002	/*r/w, whether to use mask, TRUE == use*/
#define	IG_CONTROL_NR_ROI_REFERENCE_POINT_LEFT	0x0003	/*r/w, mask reference point x component*/
#define	IG_CONTROL_NR_ROI_REFERENCE_POINT_TOP	0x0004	/*r/w, mask reference point y component*/
#define	IG_CONTROL_NR_ROI_VALIDATE					0x0005	/*r, is there valid mask associated*/

/***************************************************************************/
/***************************************************************************/
/* TWAIN control constants */
#define IG_SCAN_ICAP_MODE_TRANSFER					0x0041
#define IG_SCAN_ICAP_FILE_FORMAT					0x0042
#define IG_SCAN_CONTROL_FILE_NAME					0x0043
#define IG_SCAN_CONTROL_MEM_BUFFER_SIZE				0x0044


#define IG_SCAN_ICAP_COMPRESSION					0x0045	/* LONG, use of the IG_COMPRESSION_* constants */
#define IG_SCAN_ICAP_HIGHLIGHT						0x0046	/* DOUBLE, */
#define IG_SCAN_ICAP_SHADOW							0x0047	/* DOUBLE, */
#define IG_SCAN_ICAP_CCITT_FO						0x0048	/* LONG, use of the IG_LSBFIRST/IG_MSBFIRST constants */
#define IG_SCAN_ICAP_CCITT_KFACTOR					0x0049	/* LONG */
#define IG_SCAN_ICAP_CCITT_PMI						0x004A	/* use of chocolate/vanilla constants */
#define IG_SCAN_ICAP_XSCALING						0x004B	/* DOUBLE, */
#define IG_SCAN_ICAP_YSCALING						0x004C	/* DOUBLE, */
#define IG_SCAN_ICAP_TILES							0x004D	/* LONG, use of 1 or 0 */
#define IG_SCAN_ICAP_JPEG_PIXELTYPE					0x004E	/* LONG */
#define IG_SCAN_ICAP_UNITS							0x004F	/* LONG, use IG_SCAN_UNITS_* constants */
#define IG_SCAN_CONTROL_DS_NAME						0x0050	/* LONG, use of LPVOID */

/* More caps for query */
#define IG_SCAN_CAP_AUTHOR							0x0070
#define IG_SCAN_CAP_CAPTION							0x0071
#define IG_SCAN_CAP_FEEDERENABLED					0x0072
#define IG_SCAN_CAP_FEEDERLOADED					0x0073
#define IG_SCAN_CAP_TIMEDATE						0x0074
#define IG_SCAN_CAP_SUPPORTEDCAPS					0x0075
#define IG_SCAN_CAP_EXTENDEDCAPS					0x0076
#define IG_SCAN_CAP_AUTOFEED						0x0077
#define IG_SCAN_CAP_CLEARPAGE						0x0078
#define IG_SCAN_CAP_FEEDPAGE						0x0079
#define IG_SCAN_CAP_REWINDPAGE						0x007a
#define IG_SCAN_CAP_INDICATORS						0x007b
#define IG_SCAN_CAP_SUPPORTEDCAPSEXT				0x007c
#define IG_SCAN_CAP_PAPERDETECTABLE					0x007d
#define IG_SCAN_CAP_UICONTROLLABLE					0x007e
#define IG_SCAN_CAP_DEVICEONLINE					0x007f
#define IG_SCAN_CAP_AUTOSCAN						0x0080
#define IG_SCAN_ICAP_AUTOBRIGHT						0x0081
#define IG_SCAN_ICAP_CUSTHALFTONE					0x0082
#define IG_SCAN_ICAP_EXPOSURETIME					0x0083
#define IG_SCAN_ICAP_FILTER							0x0084
#define IG_SCAN_ICAP_FLASHUSED						0x0085
#define IG_SCAN_ICAP_GAMMA							0x0086
#define IG_SCAN_ICAP_HALFTONES						0x0087
#define IG_SCAN_ICAP_LAMPSTATE						0x0088
#define IG_SCAN_ICAP_LIGHTSOURCE					0x0089
#define IG_SCAN_ICAP_ORIENTATION					0x008a
#define IG_SCAN_ICAP_PHYSICALWIDTH					0x008b
#define IG_SCAN_ICAP_PHYSICALHEIGHT					0x008c
#define IG_SCAN_ICAP_FRAMES							0x008d
#define IG_SCAN_ICAP_XNATIVERESOLUTION				0x008e
#define IG_SCAN_ICAP_YNATIVERESOLUTION				0x008f
#define IG_SCAN_ICAP_MAXFRAMES						0x0090
#define IG_SCAN_ICAP_BITORDER						0x0091
#define IG_SCAN_ICAP_LIGHTPATH						0x0092
#define IG_SCAN_ICAP_PLANARCHUNKY					0x0093
#define IG_SCAN_ICAP_ROTATION						0x0094
#define IG_SCAN_ICAP_SUPPORTEDSIZES					0x0095
#define IG_SCAN_ICAP_THRESHOLD						0x0096
#define IG_SCAN_ICAP_TIMEFILL						0x0097
#define IG_SCAN_ICAP_BITDEPTHREDUCTION				0x0098
#define IG_SCAN_ICAP_UNDEFINEDIMAGESIZE				0x0099
#define IG_SCAN_CAP_CUSTOMBASE						0x0400

/* new TWAIN constant values */
#define IG_SCAN_MODE_TRANSFER_NATIVE				0x0001
#define IG_SCAN_MODE_TRANSFER_DISK_FILE				0x0002
#define IG_SCAN_MODE_TRANSFER_BUFFERED_MEMORY		0x0003

#define IG_LSBFIRST										0
#define IG_MSBFIRST										1

#define IG_SCAN_UNITS_INCHES							0
#define IG_SCAN_UNITS_CENTIMETERS						1
#define IG_SCAN_UNITS_PICAS								2
#define IG_SCAN_UNITS_POINTS							3
#define IG_SCAN_UNITS_TWIPS								4
#define IG_SCAN_UNITS_PIXELS							5


/* return types from the scan cap query */
#define IG_TW_BOOL			1
#define IG_TW_WORD			2
#define IG_TW_BYTE			3
#define IG_TW_SHORT			4
#define IG_TW_DOUBLE			5

#define IG_TW_ONEVALUE		1
#define IG_TW_ENUMERATION	2
#define IG_TW_RANGE			3
#define IG_TW_ARRAY			4

#define	IG_SCANCB_BEFORE_PAGE		1
#define	IG_SCANCB_AFTER_PAGE			2
#define	IG_SCANCB_AFTER_DOC			3

#define	IG_SCANCB_TRANSFORM_IMAGE	1
#define	IG_SCANCB_DELIVER_IMAGE		2

/**************************************************************/
/*****************   Scanner GUI             ******************/
/**************************************************************/

#define IG_GUI_SCAN_PAGE_HANDLE			0x8000	/*get handle of internal pagebrowser, readonly*/
#define IG_GUI_SCAN_UI_SHOW				0x8001	/*whetjer to use UI*/
#define IG_GUI_SCAN_AUTO_DELETE			0x8002	/*whether to delete images when win destroyed*/
#define IG_GUI_SCAN_CAP_LAYOUT_RECT		0x8003	/*similar to IG_SCAN_CAP_LAYOUT_....*/
#define IG_GUI_SCAN_CM_ACQUIRE			0x8004	/*To simulate menu command Single Page Acquire*/
#define IG_GUI_SCAN_CM_MACQUIRE			0x8005	/*To simulate menu command Multiple Pages Acquire*/
#define IG_GUI_SCAN_CM_SCAN_DLG			0x8006	/*To simulate menu command Set Scan Parameters*/
#define IG_GUI_SCAN_CM_TRNSF_DLG			0x8007	/*To simulate menu command Non Native Transfer*/


/***************************************************************************/
/***************************************************************************/


/* new color components */
#define  IG_COLOR_COMP_C            0x0020
#define  IG_COLOR_COMP_M            0x0040
#define  IG_COLOR_COMP_Y            0x0080
#define  IG_COLOR_COMP_K            0x0100

#define  IG_COLOR_COMP_CMYK         (IG_COLOR_COMP_C | IG_COLOR_COMP_M | IG_COLOR_COMP_Y | IG_COLOR_COMP_K )

/* color space support level */
#define IG_FULL_SUPPORT					2


/************************************************************************************/
/* ImageGear image control option IDs                                               */
/************************************************************************************/
/*      Option ID                            Format        | Opt #    lpData type   */
/*      --------------------------------     ---------------------    ------------- */
#define IG_CONTROL_JPG_ENTROPY_OPTIMIZE      (IG_FORMAT_JPG|0x0C00)  /* BOOL   (optimize entropy coding for non progressive lossy coding */		

#define IG_CONTROL_TIF_TILE_H_COUNT				(IG_FORMAT_TIF|0x1200)  /* LONG        */
#define IG_CONTROL_TIF_TILE_V_COUNT				(IG_FORMAT_TIF|0x1300)  /* LONG        */
#define IG_CONTROL_TIF_TILE_WIDTH				(IG_FORMAT_TIF|0x1400)  /* LONG        */
#define IG_CONTROL_TIF_TILE_HEIGHT				(IG_FORMAT_TIF|0x1500)  /* LONG        */
#define IG_CONTROL_TIF_MISSING_COMPRESSION	(IG_FORMAT_TIF|0x1600)  /* AT_MODE     */
#define IG_CONTROL_TIF_WRITE70					(IG_FORMAT_TIF|0x1700)  /* BOOL        */
#define IG_CONTROL_TIF_DO_NOT_WRITE_PALETTE	(IG_FORMAT_TIF|0x1800)	/* BOOL			*/

#define IG_CONTROL_XWD_TYPE						(IG_FORMAT_XWD|0x0100)	/* AT_MODE		*/


#define	IG_XWD_TYPE_XYBITMAP						0
#define	IG_XWD_TYPE_XYPIXMAP						1
#define	IG_XWD_TYPE_ZPIXMAP						2


/*********************************************************************************************/
/* COLOR LEVEL SUPPORT API *******************************************************************/
/*********************************************************************************************/

AT_ERRCOUNT	ACCUAPI	IG_color_space_level_set(
		AT_MODE		nColorSpace,
		AT_MODE		nSupportLevel
		);

AT_ERRCOUNT	ACCUAPI IG_color_space_level_get(
		AT_MODE		nColorSpace,
		LPAT_MODE	lpnSupportLevel
		);

/*********************************************************************************************/
/* MMX SUPPORT API ***************************************************************************/
/*********************************************************************************************/

AT_ERRCOUNT LACCUAPI IG_util_MMX_usage_set(

   BOOL	bMMXUsage
   );

AT_ERRCOUNT LACCUAPI IG_util_MMX_usage_get(

   LPBOOL	lpbMMXUsage
   );


/*********************************************************************************************/
/*NR-ROI API ProtoTypes********************************************************************/
/*********************************************************************************************/

AT_ERRCOUNT	ACCUAPI	IG_IP_NR_ROI_mask_associate(

	HIGEAR				hIGear,												/* higear to which mask will be associated*/
	LPAT_NR_ROI_MASK	lpMask,												/* mask to associate*/
	BOOL					bState												/* initial state of associate flag*/
	);

AT_ERRCOUNT	ACCUAPI	IG_IP_NR_ROI_mask_unassociate(

	HIGEAR				hIGear												/* Higear whose associate mask flag will be cleared*/
	);

AT_ERRCOUNT	ACCUAPI	IG_IP_NR_ROI_mask_delete(

	LPAT_NR_ROI_MASK	lpMask												/* Mask structure to delete*/			
	);

AT_ERRCOUNT	ACCUAPI	IG_IP_NR_ROI_control_set(

	HIGEAR			hIGear,
	AT_MODE			nAttributeID,											/* ID of attribute to set	*/
	const LPVOID	lpData													/* Attribute data				*/
	);

AT_ERRCOUNT	ACCUAPI	IG_IP_NR_ROI_control_get(

	HIGEAR			hIGear,
	AT_MODE			nAttributeID,											/* ID of attribute to set	*/
	VOID FAR32		*lpData													/* Attribute data		*/
	);

AT_ERRCOUNT ACCUAPI IG_IP_NR_ROI_to_HIGEAR_mask(
	AT_MODE	nSimpleAreaTypeID,											/*one of predefined simple area types ID*/
	LPVOID lpAreaSegmentDesc,												/*array of parameters to describe area. These 							   parameters  may be points in the case of polygons, 
																					points and angles in the case of ellipse sectors and so 						   on */
	LPAT_NR_ROI_MASK lpNR_ROI												/*area handler to return*/
	);

AT_ERRCOUNT ACCUAPI IG_clipboard_paste_available_ex(

	LPBOOL            lpPasteStatus,										/* TRUE - Ok to paste image    */
	LPAT_MODE			lpRegionType
	);

AT_ERRCOUNT ACCUAPI IG_clipboard_paste_merge_ex(

   HIGEAR					hIGear,											/* Image Gear handle           */
   AT_PIXPOS            nLeftPos,										/* X position of new image     */
   AT_PIXPOS            nTopPos											/* Y position of new image     */
   );

/*********************************************************************************************/
/*Stitching API ProtoTypes********************************************************************/
/*********************************************************************************************/

AT_ERRCOUNT ACCUAPI IG_load_tiles_stitch(

   const LPSTR  lpszFileName,    /* File name to stitch from */
   UINT         nPage,           /* Page number to load      */
   LPAT_STITCH  lpStitch,        /* Stitch record            */
   LPHIGEAR     lphIGear         /* Image Gear handle        */
   );

AT_ERRCOUNT ACCUAPI IG_load_tiles_stitch_FD(

   INT          fd,              /* File descriptor             */
   LONG         lOffset,         /* Offset to image             */
   UINT         nPage,           /* Page number to load         */
   LPAT_STITCH  lpStitch,        /* Stitch record               */
   LPHIGEAR     lphIGear         /* Image Gear handle           */
   );

AT_ERRCOUNT ACCUAPI IG_load_tiles_stitch_mem(

   VOID FAR32*  lpImage,         /* Address of image in memory */
   DWORD        dwImageSize,     /* Size of image in memory    */ 
   UINT         nPage,           /* Page number to read        */
   LPAT_STITCH  lpStitch,        /* Stitch record              */
   LPHIGEAR     lphIGear         /* Image handle return        */
   );


/*********************************************************************************************/
/*SCANNING API ProtoTypes********************************************************************/
/*********************************************************************************************/
AT_ERRCOUNT ACCUAPI IG_scan_get_default_DS(

		HWND		hMainWnd,
		LPSTR		lpDSName
		);

AT_ERRCOUNT ACCUAPI IG_scan_get_first_DS(

		HWND		hMainWnd,
		LPSTR		lpDSName
		);
		
AT_ERRCOUNT ACCUAPI IG_scan_get_next_DS(

		HWND		hMainWnd,
		LPSTR		lpDSName
		);

AT_ERRCOUNT ACCUAPI IG_scan_cap_get_next_value(

		HWND					hMainWnd,
		LPVOID				lpData
		);

AT_ERRCOUNT ACCUAPI IG_scan_cap_get_default_value(

		HWND					hMainWnd,
		LPVOID				lpData
		);

AT_ERRCOUNT ACCUAPI IG_scan_cap_get_current_value(

		HWND					hMainWnd,
		LPVOID				lpData
		);

AT_ERRCOUNT ACCUAPI IG_scan_cap_query(

		HWND					hMainWnd,
		AT_MODE				ScanCapType,
		LPAT_MODE			lpContType,
		LPAT_MODE			lpCapType,
		LPLONG				lpNCapItems
		);
		

		
AT_ERRCOUNT ACCUAPI IG_scan_CB_register(

		LPVOID	lpfnScanCB,
		AT_MODE	nCBType,
		AT_MODE	nCBSubType,		/* has to be 0 */
		LPLONG	lpOptions,		/* for the after_page CB type */
		LONG		nNumbOptions,	/* length of the array options */
		LPVOID	lpPrivate
		);

/* return: 1 - continue to scan, 0 - stop, other values are reserved 
	for the future */
typedef LONG (LPACCUAPI LPFNIG_SCAN_BEFORE_PAGE)(

   LONG							nPageNumb,     /* Number of page going to be scanned */
   DOUBLE						dblLeft,
   DOUBLE						dblTop,   
   DOUBLE						dblRight,
   DOUBLE						dblBottom,
   LPVOID						lpPrivate
   );

/* return: 1 - continue to scan, 0 - stop, other values are reserved 
	for the future */   
typedef LONG (LPACCUAPI LPFNIG_SCAN_AFTER_PAGE)(

   LONG							nPageNum,		/* Number of page just scanned */
   HIGEAR						hIGear,			/* HIGEAR, got */
   LONG							nPendCount,		/* number of images pending */
   const LPSTR					lpFileName,
   LPVOID						lpPrivate
   );

/* return: should always be 0, other values are reserved for the
	future */
typedef LONG (LPACCUAPI LPFNIG_SCAN_AFTER_DOC)(

   LONG							nPageCount,     /* Number of pages scanned */
	LONG							nADFLoaded,
   LPVOID						lpPrivate
   );

/*********************************************************************************************/
/*Batch Processing API ProtoTypes*************************************************************/
/*********************************************************************************************/

AT_ERRCOUNT ACCUAPI IG_image_batch_convert(

   LPAT_SRCINFO			lpSrcInfo,
   LPAT_DSTINFO			lpDstInfo,
   const LPSTR				lpcszLogFileName
   );

/*********************************************************************************************/
/*Raw Image Data Processing API ProtoTypes****************************************************/
/*********************************************************************************************/
AT_ERRCOUNT ACCUAPI IG_load_raw_file(

   const LPSTR		lpszFileName,	/* File name of image to load */
   LONG				lOffset,		/* Offset to image            */ 
   AT_DIMENSION		nWidth,			/* Width of merged image   */
   AT_DIMENSION		nHeight,		/* Height of merged image  */
   UINT				nBitsPerPixel,	/* Bits/Pix of Raw data    */
   AT_MODE			nFillOrder,		/* IG_FILL_					  */
   AT_MODE			nCompression,	/* IG_COMPRESSION_ used    */
   LPHIGEAR			lphIGear		/* ImageGear handle           */
   );

AT_ERRCOUNT ACCUAPI IG_load_raw_FD(

   INT            fd,           /* File descriptor         */
   AT_DIMENSION   nWidth,       /* Width of merged image   */
   AT_DIMENSION   nHeight,      /* Height of merged image  */
   UINT           nBitsPerPixel,/* Bits/Pix of Raw data    */
   AT_MODE        nFillOrder,   /* IG_FILL_					  */
   AT_MODE        nCompression, /* IG_COMPRESSION_ used    */
   LPHIGEAR       lphIGear      /* Image handle return     */
   );

AT_ERRCOUNT ACCUAPI IG_load_raw_mem(

   LPVOID         lpImage,      /* Address of image in memory */
   DWORD          dwImageSize,  /* Size of image in memory    */
   AT_DIMENSION   nWidth,       /* Width of merged image      */
   AT_DIMENSION   nHeight,      /* Height of merged image     */
   UINT           nBitsPerPixel,/* Bits/Pix of Raw data    */
   AT_MODE        nFillOrder,   /* Fill order: LSB or MSB     */
   AT_MODE        nCompression, /* IG_COMPRESSION_ used    */
   LPHIGEAR       lphIGear      /* Image handle return        */
   );
/*********************************************************************************************/
/*********************************************************************************************/



AT_ERRCOUNT ACCUAPI IG_GUI_scan_window_create(

	HWND					hwndParent, 		/* Parent window handle 		 */
	DWORD 				dwStyle, 			/* Window style bits 			 */
	const LPSTR 		lpcszTitle, 		/* Window title					 */
	INT					x, 					/* X Position of window 		 */
	INT					y, 					/* Y Position of window 		 */
	INT					nWidth,				/* Width of window				 */
	INT					nHeight, 			/* Height of window				 */
	HWND FAR 			*lphwndScan 		/* Scan window handle return	 */
	);

AT_ERRCOUNT ACCUAPI IG_GUI_scan_attribute_get(

	HWND				hwndScan,		/* Scan window handle		*/
	AT_MODE			nAttributeID,	/* ID of attribute to get	*/
	LPVOID			lpData			/* Attribute data 			*/
	);

AT_ERRCOUNT ACCUAPI IG_GUI_scan_attribute_set(

	HWND				hwndScan,		/* Scan window handle		*/
	AT_MODE			nAttributeID,	/* ID of attribute to get	*/
	const LPVOID	lpData			/* Attribute data 			*/
	);


AT_ERRCOUNT ACCUAPI  IG_IP_color_combine_ex( 

   LPHIGEAR    lphIGear_result,  /* A created image of the combined image  */
   HIGEAR      hIGear1,          /* Channel 1                              */
   HIGEAR      hIGear2,          /* Channel 2                              */
   HIGEAR      hIGear3,          /* Channel 3                              */
   HIGEAR      hIGear4,          /* Channel 4                              */
   AT_MODE     color_space,      /* IG_COLOR_SPACE_                        */
	AT_MODE		dst_color_space
   );

#ifdef WIN32
AT_ERRCOUNT	ACCUAPI	IG_load_internet( 
		const LPCHAR			HostName,
		const LPCHAR			URLPath,
		const LPCHAR			UserName,
		const LPCHAR			Password,
		const LONG				nServerPort,
		DWORD						dwService,
		LPHIGEAR					lphIGear			/* Image handle return         */
		);
#endif

AT_ERRCOUNT	ACCUAPI	IG_IP_color_convert(
		HIGEAR			hIGear,
		AT_MODE			color_space
		);

/*#endif //IMAGEGEAR_7_0*/

