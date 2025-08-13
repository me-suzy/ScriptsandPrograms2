/***************************************************************************/
/*                                                                         */
/* MODULE:  GearEStr.h - AccuSoft ImageGear Error String file.             */
/*                                                                         */
/*                                                                         */
/* Date created:        07/17/1996 SW                                      */
/*                                                                         */
/*    $Date: 1999/02/21 02:22:45 $                                             */
/*    $Revision: 1.2 $                                             */
/*                                                                         */
/* Copyright (c) 1996-97, AccuSoft Corporation.  All rights reserved.      */
/*                                                                         */
/***************************************************************************/


#ifndef __GEARESTR_H__
#define __GEARESTR_H__


typedef struct
{
   AT_ERRCODE		ErrCode;
   LPSTR				ErrString;
}IG_ERROR_STRING, FAR *LPIG_ERROR_STRING;


const IG_ERROR_STRING ErrString[] = 
{
{IGE_ALPHA_NOT_PRESENT,						"Alpha channel is not present"},
{IGE_SUCCESS,									"Success"},
{IGE_FAILURE,									"Failure"},
{IGE_NOT_DONE_YET,							"Not done yet"},
{IGE_NOT_IMPLEMENTED,						"Not implemented"},
{IGE_PRO_GOLD_FEATURE,						"Pro Gold feature"},
{IGE_NOT_LITE_FEATURE,						"This function is not supported by the Image Gear LT Product"},
{IGE_NOT_SUPPORTED_BY_PLATFORM,			"The function is not supported by 16-bit Pro Gold products"},
{IGE_ERROR_COMPRESSION,						"Compression error"},
{IGE_EXTENSION_NOT_LOADED,					"ImageGear extension not present or could not be loaded"},
{IGE_INVALID_CONTROL_OPTION,				"Invalid image control option ID"},
{IGE_INVALID_EXTENSION_MODULE,			"Specified ImageGear extension file is invalid"},
{IGE_EXTENSION_INITIALIZATION_FAILED,	"Specified ImageGear extension was unable to initialize"},
{IGE_FUNCTIONALITY_NOT_SUPPORTED,		"This functionality is not supported by the platform"},
{IGE_OUT_OF_MEMORY,							"Global memory has been depleted"},
{IGE_EVAL_DLL_TIMEOUT_HAS_EXPIRED,		"Evaluation version has expired - please contact AccuSoft"},
{IGE_INVALID_STANDARD_KERNEL,				"Kernel size is invalid"},
{IGE_INTERNAL_ERROR,							"An internal error has occurred"},
{IGE_INVALID_RECTANGLE,						"Rectangle values are invalid"},
{IGE_NO_CLIPBOARD_IMAGE_AVAILABLE,		"No image is available in clipboard"},
{IGE_CLIPBOARD_OPEN_FAILED,				"Clipboard open has failed"},
{IGE_SETCLIPBOARDDATA_FAILED,				"Could not put data into clipboard"},
{IGE_COULD_NOT_GET_DDB_DIMENSIONS,		"Could not get dimensions of DDB"},
{IGE_COULD_NOT_GET_DDB_BITS,				"Could not get DDB image data"},
{IGE_CREATE_BITMAP_FAILED,					"Could not create a new DDB"},
{IGE_COULD_DISPLAY_DDB,						"Could not display the DDB"},
{IGE_INVALID_PATTERN_BITMAP,				"Invalid DDB"},
{IGE_PASSWORD_INVALID,						"Extension password is invalid for user code"}, 
{IGE_THUMBNAIL_NOT_PRESENT,				"This image does not contain a thumbnail"},
{IGE_THUMBNAIL_READ_ERROR,					"Read error occured while loading a thumbnail"},
{IGE_THUMBNAIL_NOT_SUPPORTED,				"This format does not support thumbnails"},
{IGE_PAGE_NOT_PRESENT,						"The specified page does not exist in this file"},
{IGE_PAGE_INVALID,							"The specified page is outside the valid range"},
{IGE_PAGE_COULD_NOT_BE_READ,				"The specified page could not be read"},
{IGE_CANT_DETECT_FORMAT,					"Could not detect the format of this file"},
{IGE_FILE_CANT_BE_OPENED,					"File open failed"},
{IGE_FILE_CANT_BE_CREATED,					"File create failed"},
{IGE_FILE_CANT_BE_CLOSED,					"File close failed"},
{IGE_FILE_TO_SMALL_TO_BE_BMFH,			"File too small to be a BMP"},
{IGE_FILE_IS_NOT_BMP,						"File is not a BMP"},
{IGE_FILE_TO_SMALL_TO_BE_BMIH,			"File too small to be valid"},
{IGE_BMP_IS_COMPRESSED,						"BMP image is compressed"},
{IGE_FILE_SIZE_WRITE_ERROR,				"BMP Could not write file size field to BMP"},
{IGE_CANT_READ_PALETTE,						"Can't read palette"},
{IGE_CANT_READ_PIXELS,						"Can't read pixel data"},
{IGE_CANT_READ_HEADER,						"Can't read header"},
{IGE_INVALID_FILE_TYPE,						"Invalid file type"},
{IGE_INVALID_HEADER,							"Invalild file header"},
{IGE_CANT_WRITE_PALETTE,					"Can't write palette"},
{IGE_CANT_WRITE_PIXELS,						"Can't write pixel data"},
{IGE_CANT_WRITE_HEADER,						"Can't write header"},
{IGE_FORMAT_NOT_DETECTABLE,				"Save format can not be detected from file extension used"},
{IGE_INVALID_COMPRESSION,					"Invalid compression"},
{IGE_INSTANCE_FAILURE,						"Instance failure"},
{IGE_CANT_READ_FILE,							"Can't read file"},
{IGE_INVALID_IMAGE_FORMAT,					"Invalid image format"},
{IGE_FILE_FORMAT_IS_READONLY,				"File is read only"},
{IGE_INVALID_BITCOUNT_FOR_FORMAT,		"Invalid bitcount (depth) for this format"},
{IGE_INTERRUPTED_BY_USER,					"Interrupted by user"},
{IGE_NO_BITMAP_REGION,						"No bitmap region"},
{IGE_BAD_FILE_FORMAT,						"Bad file format"},
{IGE_EPS_NO_PREVIEW,							"EPS file has no screen preview to read"},
{IGE_CANT_WRITE_FILE,						"Can't write file"},
{IGE_NO_BITMAP_FOUND,						"No raster image found in file to load"},
{IGE_PALETTE_FILE_TYPE_INVALID,			"Palette file type is invalid"},
{IGE_PALETTE_FILE_WRITE_ERROR,			"Palette file write error"},
{IGE_PALETTE_FILE_READ_ERROR,				"Palette file read error"},
{IGE_PALETTE_FILE_NOT_DETECTED,			"Palette file not detected"},
{IGE_PALETTE_FILE_INVALID_HALO_PAL,		"Invalid Halo palette file"},
{IGE_G4_PREMATURE_EOF_AT_SCAN_LINE,		"Group 4 premature EOF"},
{IGE_G4_PREMATURE_EOL_AT_SCAN_LINE,		"Group 4 premature EOL"},
{IGE_G4_BAD_2D_CODE_AT_SCAN_LINE,		"Group 4 invalid 2D code"},
{IGE_G4_BAD_DECODING_STATE_AT_SCAN_LINE, "Group 4 bad decoding state"},
{IGE_G3_PREMATURE_EOF_AT_SCAN_LINE,		"Group 3 premature EOF"},
{IGE_G3_BAD_1D_CODE_AT_SCAN_LINE,		"Group 3 bad 1D code"},
{IGE_G3_PREMATURE_EOL_AT_SCAN_LINE,		"Group 3 premature EOL"},
{IGE_BITDEPTH_NOTSUPPORTED,				"This Bit-Depth is not supported for this write format"},
{IGE_DIRECTORY_CREATE_ERROR,				"Unable to create Destination Directory"},
{IGE_LOG_FILE_CREATE_ERROR,				"Unable to create Batch Log File"},
{IGE_NAME_CONV_NOT_SUPPORTED,				"Batch Naming configuration not supported"},

{IGE_IMNET_INVALID_WIDTH,					"Invalid width for IMNET"},

{IGE_PJPEG_INVALID_SCAN_CONFIGURATION,	"Invalid configuration of scans for progressive JPEG write"},
{IGE_PJPEG_INVALID_SCAN_COUNT,			"Invalid number of scans for progresive JPEG write"},
{IGE_JPG_UNRECOGNIZED,						"Unrecognized JPEG marker encountered"},
{IGE_JPG_INVALID_QTABLE_ID,				"Invalid quantization table decriptor"},
{IGE_JPG_INVALID_QTABLE_PRECISION,		"Invalid quantization table precision"},

{IGE_JPG_INVALID_HUFFMAN_ID,				"Invalid huffman table decriptor"},
{IGE_JPG_INVALID_HUFFMAN_TABLE,			"Invalid huffman table"},
{IGE_PJPEG_NOT_SUPPORTED,					"Progressive JPEG feature is not supported"},

{IGE_OPERATION_IS_NOT_ALLOWED,			"This operation is not allowed"},
{IGE_PROC_INVAL_FOR_RUNS_DIB,				"This function can not be used on DIBs in the Runs format - convert first IG_IP_convert_runs_to_DIB"},
{IGE_CAN_NOT_OPEN_TEMP_FILE,				"The temporary file need for this function could not be opened/created"},
{IGE_ALLOC_SELECTOR_FAILED,				"AllocSelector() failed, couldn't get an entry into the Global Descriptor Table"},
{IGE_LOAD_FUNCTION_GET_FAILED,			"Was not able to intialize the filter load function"},
{IGE_STANDARD_FEATURE,						"Standard version feature, not available in this version"},
{IGE_PNG_CHUNK_WRITE_FAILED,				"Failed to write the correct number of bytes for PNG  chunk"},
{IGE_PNG_WRITE_FAILED,						"Failed to write PNG data"},
{IGE_PNG_CHUNK_READ_FAILED,				"Failed to READ the correct number of bytes for PNG chunk"},
{IGE_PNG_READ_FAILED,						"Failed to READ PNG data"},
{IGE_PNG_NO_IDAT_CHUNK,						"Failed to READ a manditory IDAT PNG chunk"},

{IGE_NOT_SUPPORTED_COMP,					"Compression is not supported by Filter at this time"},
{IGE_UNDEFNIED_COLOR_SPACE_ID,			"Color space ID is not defnied"},

{IGE_DIB_RES_UNITS_NOT_SUPPORTED,		"DIB resolution units is not supported"},
/***************************************************************************/
/* TIFF filter specific errors                                             */
/***************************************************************************/

{IGE_INVALID_TAG,								"Invalid TIFF tag"},
{IGE_INVALID_IFD,								"Invalid TIFF IFD"},
{IGE_IFD_PROC_FAILURE,						"TIFF IFD proc failed"},
{IGE_SEEK_FAILURE,							"TIFF seek failure"},
{IGE_INVALID_BYTE_ORDER,					"TIFF invalid byte order"},
{IGE_CANT_READ_TAG_DATA,					"TIFF can't read tag data"},
{IGE_INVALID_BITS_PER_SAMPLE,				"TIFF invalid bits per sample"},
{IGE_INVALID_COLOR_MAP,						"TIFF invalid color map"},
{IGE_INVALID_PHOTOMETRIC,					"TIFF invalid photometric interpretation value"},
{IGE_INVALID_REQ_INFO,						"TIFF required information missing"},
{IGE_COMP_NOT_SUPPORTED,					"TIFF compression is not supported"},   /* only used during development */
{IGE_RASTER_FEED_ERROR,						"TIFF raster feed error"},
{IGE_IMAGE_DATA_READ_ERROR,				"TIFF image data read error"},
{IGE_HEADER_WRITE_FAILED,					"TIFF header write failure"},
{IGE_DIB_GET_FAILURE,						"TIFF DIB get failure"},
{IGE_CANT_REALLOC_MEM,						"TIFF can't realloc memory error"},
{IGE_IFD_WRITE_ERROR,						"TIFF IFD write error"},
{IGE_TAG_WRITE_ERROR,						"TIFF tag write error"},
{IGE_IMAGE_DATA_WRITE_ERROR,				"TIFF image data write error"},
{IGE_PLANAR_CONFIG_ERROR,					"TIFF planar config error"},
{IGE_RASTER_TO_LONG,							"TIFF raster too long"},
{IGE_LZW_ERROR,								"TIFF LZW error"},
{IGE_INVALID_IMG_DEM,						"TIFF invalid image dimension"},
{IGE_BAD_DATA_TYPE,							"TIFF bad data type"},
{IGE_PAGE_COUNT_FAILURE,					"TIFF count not count the number of pages in the file"},
{IGE_CORRUPTED_FILE,							"TIFF data in file was not what was expected and could not be interp"},
{IGE_INVALID_STRIP_BYTE_CNT,				"TIFF strip byte count was zero and could not be estimated"},
{IGE_INVALID_COMP_BIT_DEPTH,				"TIFF bit depth is invalid for this compression scheme"},
{IGE_REPAGE_FAILED,							"TIFF unable to write new page numbers while repaging file"},
{IGE_PRIV_TAG_TYPE_INVALID,				"TIFF private user tag had an invalid type"},
{IGE_LZW_EXTENSION_NOT_LOADED,			"The IG LZW Extension is not loaded and is required to load this image"},
{IGE_ABIC_EXTENSION_NOT_LOADED,			"The IG ABIC Extension is not loaded and is required to load this image"},
{IGE_JBIG_EXTENSION_NOT_LOADED,			"The IG JBIG Extension is not loaded and is required to load this image"},
{IGE_JBG_IMG_CNTRL_NOT_FOUND,				"The IG JBIG Extension Image Control not found for save"},
{IGE_CLP_INVALID_FORMAT_ID,				"CLP invalid format ID"},
{IGE_ICA_COMP_NOT_SUPPORTED,				"IOCA/MO:DCA compression is not supported"},
{IGE_ICA_IBM_MMR_COMP_ERROR,				"Error in the IBM MMR IOCA/MO:DCA compression"},
{IGE_TIF_INVALID_CLASS_F_IMAGE,			"Error writing TIFF Class F format. Bad source image"}, 
{IGE_TILE_NOT_PRESENT,						"Tile is not present"},
{IGE_RASTER_WRITE_FAILURE,					"Unable to write Raster to Output Device (Full Device)"},
{IGE_JBIG_STREAM_OPEN_FAILURE,			"JBIG Extension returned general return code"},


{IGE_CANNT_OPEN_FTP_FILE,					"Can't open FTP file"},
{IGE_CANNT_OPEN_HTTP_FILE,					"Can't open HTTP file"},
{IGE_CANNT_OPEN_GOPHER_FILE,				"Can't open Gopher file"},
{IGE_CANNT_OPEN_TEMPORARY_FILE,			"Can't open temporary file"},
{IGE_CANNT_OPEN_INTERNET_CONNECTION,	"Can't open internet connection (InternetConnect fail)"},
{IGE_CANNT_OPEN_INTERNET_SESSION,		"Can't open internet session (InternetOpen fail)"},

/* OCX error codes */
{IGE_OCX_CANT_DELETE_PAGE,					"OCX can't delete page"},
{IGE_OCX_CANT_LOADMEM,						"OCX can't load mem"},
{IGE_OCX_CANT_GET_FILETYPE,				"OCX can't get file type"},
{IGE_OCX_CANT_GET_COMPRESS_TYPE,			"OCX can't get compress type"},
{IGE_OCX_CANT_SET_IMAGE_INDEX,			"OCX can't set image index"},
{IGE_OCX_COLOR_SEPARATE_FAILED,			"OCX color separate failed"},
{IGE_OCX_DOC_NODE_IS_INVALID,				"OCX doc node is invalid"},
{IGE_OCX_CAN_GET_PAGE_COUNT,				"OCX can't get page count"},
{IGE_OCX_CANT_LOAD_DOC,						"OCX can't load doc"},
{IGE_OCX_COLOR_COMBINE_FAILED,			"OCX color combine failed"},
	
/***************************************************************************/
/* Image Processing Error codes                                            */
/***************************************************************************/

{IGE_WRONG_DIB_BIT_COUNT,					"Invalid bit count for this function"},
{IGE_LOCK_FAILED,								"Memory lock failed"},
{IGE_ALLOC_FAILED,							"Memory alloc failed"},
{IGE_FREE_FAILED,								"Memory free failed"},
{IGE_BAD_KERN_TYPE,							"Bad kernel type"},
{IGE_AI_HANDLES_USED_UP,					"Maximum number of handles have been used"},
{IGE_AI_HANDLE_INVALID,						"Invalid handle"},
{IGE_DIBS_ARE_INCOMPATIBLE,				"Incompatible DIBs"},
{IGE_DIB_DIMENSIONS_NOT_EQUAL,			"DIB dimensions not equal"},
{IGE_DIB_BIT_COUNTS_NOT_EQUAL,			"DIB bit counts not equal"},
{IGE_DIB_HAS_NO_PALETTE,					"DIB palette missing"},
{IGE_ROI_WRONG_TYPE,							"Region of interest is wrong type"},
{IGE_REQUIRES_CONVEX_ROI,					"Function requires convex ROI"},
{IGE_INVALID_RAMP_DIRECTION,				"Invalid ramp direction"},
{IGE_INVALID_LUT_ARITH_FUNC,				"Invalid LUT arithmetic function"},
{IGE_INVALID_KERN_MOTION_EXTENT,			"Invalid kernel motion extent"},
{IGE_INVALID_NOISE_TYPE,					"Invalid noise type"},
{IGE_INVALID_KERN_NORMALIZER,				"Invalid kernel normalizer"},
{IGE_INVALID_SIGMA,							"Invalid sigma"},
{IGE_INVALID_SKEW_POINTS,					"Invalid skew points"},
{IGE_TILE_IS_LARGER_THAN_IMAGE,			"Tile is larger than image"},
{IGE_COLOR_SPACE_INVALID,					"Invalid color space"},
{IGE_DIB_POINTER_IS_NULL,					"DIB pointer is NULL"},
{IGE_PROC_INVAL_FOR_BIT_COUNT,			"Bit count not supported by this function"},
{IGE_PROC_INVAL_FOR_PALETTE_IMG,			"Function does not support 8 bit images"},
{IGE_PARAMETER_OUT_OF_LIMITS,				"Parameters are out of limits"},
{IGE_INVALID_POINTER,						"Invalid pointer"},
{IGE_INVALID_ENCRYPT_MODE,					"Invalid encryption mode"},
{IGE_PASSWORD_LENGTH_INVALID,				"Invalid password length"},
{IGE_PROC_REQUIRE_8BIT_GRAYSCALE,		"This functionn can be used on 8-bit grayscale images only"},
{IGE_INVALID_RESOLUTION_UNIT,				"The units of the image resolution are not supported"},
{IGE_POINTER_IS_NULL,						"Pointer passed to an IP function is NULL"},
{IGE_INVALID_BIT_MASK,						"The red, green and blue components of the bit mask overlap"},
{IGE_DIB_DIMENSIONS_ARE_INVALID,			"Either height or width of the DIB is wrong"},
{IGE_PROC_INVAL_FOR_8BIT_INDEXED,		"Function does not work on the 8-bit indexed image, please convert to gray"},
{IGE_INVALID_CLIPING_RECT,				"Invalid clipping rectangle"},
	

/***************************************************************************/
/* TWAIN Scanning Function Error codes                                     */
/***************************************************************************/

{IGE_TW_SM_SUCCESS,							"Twain function successful"},
{IGE_TW_SM_BUMMER,							"General TWAIN error"},
{IGE_TW_SM_LOWMEMORY,						"TWAIN low memory"},
{IGE_TW_SM_NODS,								"TWAIN no source"},
{IGE_TW_SM_MAXCONNECTIONS,					"TWAIN maximum connections"},
{IGE_TW_SM_OPERATIONERROR,					"TWAIN source or source manager reported error"},
{IGE_TW_SM_BADCAP,							"TWAIN capability incompatible"},
{IGE_TW_SM_BADPROTOCOL,						"TWAIN bad protocol"},
{IGE_TW_SM_BADVALUE,							"TWAIN bad value"},
{IGE_TW_SM_SEQERROR,							"TWAIN operation sequence error"},
{IGE_TW_SM_BADDEST,							"TWAIN unknown destination"},
{IGE_TW_CANT_OPENDSM,						"TWAIN cannot load source manager"},
{IGE_TW_CANT_OPENDS,							"TWAIN cannot load data source"},
{IGE_TW_CANT_ENABLEDS,						"TWAIN cannot enable data source"},
{IGE_TW_CANT_FINDDSM,						"TWAIN cannot find source manager"},
{IGE_TW_CANT_LOADDSM,						"TWAIN cannot load source manager"},
{IGE_TW_CANT_SCAN_PAGES,					"TWAIN cannot scan pages"},
{IGE_TW_CANT_TRANSFERIMAGE,				"TWAIN cannot transfer image to application"},
{IGE_TW_CANT_GETDSMADDR,					"TWAIN cannot get address of source manager"},
{IGE_TW_CANT_PROCESSMSG,					"TWAIN cannot process message"},
{IGE_TW_INVALID_DIBHANDLE,					"TWAIN invalid DIB handle"},
{IGE_TW_CANT_SET_ICAP_PIX_FLAVOR,		"TWAIN cannot set Pixel Flavor Caps"}, 
{IGE_TW_CANT_SET_ICAP_PIXELTYPE,			"TWAIN cannot set Pixel Type Caps"},
{IGE_TW_CANT_SET_ICAP_BITDEPTH,			"TWAIN cannot set Bit Depth Caps"},
{IGE_TW_CANT_SET_ICAP_UNITS,				"TWAIN cannot set Res Units Caps"},
{IGE_TW_CANT_SET_ICAP_XRESOLUTION,		"TWAIN cannot set X-Res Units Caps"},
{IGE_TW_CANT_SET_ICAP_YRESOLUTION,		"TWAIN cannot set Y-Res Units Caps"},
{IGE_TW_CANT_SET_IMAGELAYOUT,				"TWAIN cannot set Image Layout Caps"},
{IGE_TW_CANT_SET_ICAP_BRIGHTNESS,		"TWAIN cannot set Brightness Caps"},
{IGE_TW_CANT_SET_ICAP_CONTRAST,			"TWAIN cannot set Contrast Caps"},
{IGE_TW_CANT_SET_SCAN_PARAMETETRS,		"TWAIN cannot set Scan Parameters"},
{IGE_TW_CANT_OPEN_DEF_SRC,					"TWAIN cannot open Default DS"},
{IGE_TW_USER_CANCEL_SEL_SRC,				"TWAIN selection of DS is canceled"},
{IGE_TW_IMAGE_TRANSFER_IS_CANCELED,		"TWAIN user canceled transfer of image"},
{IGE_TW_CAN_NOT_GET_IMAGE_INFO,			"TWAIN can not get image information"},
{IGE_TW_WHERE_IS_NO_TWAIN_DLL,			"TWAIN TWAIN.DLL/TWAIN_32.DLL could not be found"},
{IGE_TW_CAN_NOT_ENABLE_ADF,				"TWAIN can not enable ADF"},
{IGE_TW_CAN_NOT_DISABLE_ADF,				"TWAIN can not disable ADF"},
{IGE_TW_SCANNING_IS_CANCELED,				"TWAIN scanning operation has been canceled"},
{IGE_TW_ERROR_GET_FILE_VERSION,			"TWAIN can not get file version"},

{IGE_TW_BAD_VALUE_FOR_BITS_PER_PIX,		"TWAIN invalid value for the bits per pixel cap"},
{IGE_TW_CANT_CLOSE_DSM,						"TWAIN can't close DSM"},
{IGE_TW_CANT_OPEN_DS,						"TWAIN can't open DS"},
{IGE_TW_CANT_CLOSE_DS,						"TWAIN can't close DS"},
{IGE_TW_CANT_ENABLE_DS,						"TWAIN can't enable DS"},
{IGE_TW_CANT_DISABLE_DS,					"TWAIN can't disable DS"},
{IGE_TW_WRONG_CONT_TYPE,					"TWAIN wrong type of the container"},

/* begin IG7-TWAIN */
/* Non native transfer modes	*/
{IGE_TW_TRANSFER_MODE_REQUEST_FAILED,		"TWAIN request DS for the supported transfer modes failed"},
{IGE_TW_DS_DOES_NOT_SUPPORT_TRANSFER_MODE,"TWAIN DS does not support the transfer mode"},
{IGE_TW_FILE_FORMATS_REQUEST_FAILED,		"TWAIN request DS for the supported file formats failed"},
{IGE_TW_DS_DOESN_SUPPORT_FILE_FORMAT,		"TWAIN DS can not save file to the file format specified"},
{IGE_TW_CAN_NOT_SET_TRANSFER_PARAMETERS,	"TWAIN can't set transfer parameters"},
{IGE_TW_CANT_SET_TRANSFER_MODE,				"TWAIN can't set transfer mode"},
{IGE_TW_DISK_FILE_TRANSFER_FAILED,			"TWAIN disk file transfer failed"},
{IGE_TW_CAN_NOT_GET_TRANSFER_PARAMETERS,	"TWAIN can not get DS's transfer parameters"},
{IGE_TW_INVALID_IMAGE_PARAMETERS,			"TWAIN image parameters are invalid"},
{IGE_TW_COMPRESSION_IS_NOT_SUPPORTED,		"TWAIN compression currently does not supported"},
{IGE_TW_INVALID_TRANSFER_MODE,				"TWAIN invalid transfer mode"},

{IGE_TW_IMAGE_TRANSFER_FAILED,				"TWAIN image transfer failed"},

/* More caps support */
{IGE_TW_CANT_ALLOC_CONTAINER,					"TWAIN can't alloc container"},
{IGE_TW_CANT_SET_ICAP_COMPRESSION,			"TWAIN can't set ICAP_COMPRESSION"},
{IGE_TW_CANT_SET_ICAP_AUTOBRIGHT,			"TWAIN can't set ICAP_AUTOBRIGHT"},
{IGE_TW_CANT_SET_ICAP_GAMMA,					"TWAIN can't set ICAP_GAMMA"},
{IGE_TW_WRONG_ICAP_ROTATION_VALUE,			"TWAIN wrong value for ICAP_ROTATION"},
{IGE_TW_CANT_SET_ICAP_ROTATION,				"TWAIN can't set ICAP_ROTATION"},
{IGE_TW_WRONG_ICAP_HIGHLIGHT_VALUE,			"TWAIN wrong value for ICAP_HIGHLIGHT"},
{IGE_TW_CANT_SET_ICAP_HIGHLIGHT,				"TWAIN can't set ICAP_HIGHLIGHT"},
{IGE_TW_WRONG_ICAP_SHADOW_VALUE,				"TWAIN wrong value for ICAP_SHADOW"},
{IGE_TW_CANT_SET_ICAP_SHADOW,					"TWAIN can't set ICAP_SHADOW"},
{IGE_TW_WRONG_CAP_FO_VALUE,					"TWAIN wrong value for the CCITT FillOrder cap"},
{IGE_TW_CANT_SET_ICAP_FO,						"TWAIN can't set the CCITT FillOrder cap"},
{IGE_TW_WRONG_CAP_KFACTOR_VALUE,				"TWAIN wrong value for the CCITT K-factor cap"},
{IGE_TW_CANT_SET_ICAP_KFACTOR,				"TWAIN can't set the CCITT K-factor cap"},
{IGE_TW_WRONG_CAP_PMI_VALUE,					"TWAIN wrong value for the CCITT Pmi cap"},
{IGE_TW_CANT_SET_ICAP_PMI,						"TWAIN can't set the CCITT Pmi cap"},
{IGE_TW_WRONG_ICAP_XSCALING_VALUE,			"TWAIN wrong value for ICAP_XSCALING"},
{IGE_TW_CANT_SET_ICAP_XSCALING,				"TWAIN can't set ICAP_XSCALING"},
{IGE_TW_WRONG_ICAP_YSCALING_VALUE,			"TWAIN wrong value for ICAP_YSCALING"},
{IGE_TW_CANT_SET_ICAP_YSCALING,				"TWAIN can't set ICAP_YSCALING"},
{IGE_TW_CANT_SET_ICAP_TILES,					"TWAIN can't set ICAP_TILE"},
{IGE_TW_CANT_SET_ICAP_ORIENTATION,			"TWAIN can't set ICAP_ORIENTATION"},
{IGE_TW_CANT_SET_ICAP_JPEGPIXELTYPE,		"TWAIN can't set ICAP_JPEGPIXELTYPE"},

/* DS' enumeration */
{IGE_TW_CANT_FIND_DS_SPECIFIED,				"TWAIN can't find the DS specified"},
{IGE_TW_DS_ENUMARATE_FAILED,					"TWAIN DS enumeration failed"},
{IGE_TW_DS_INFO_IS_NOT_AVAIL,					"TWAIN internal IG DS list does not exist"},
{IGE_TW_CANT_GET_DEFAULT_DS,					"TWAIN can't the default DS"},

/* query caps */
{IGE_CANT_LOCK_CONTAINER,						"TWAIN can't lock container"},
{IGE_TW_UNSUPPORTED_CONT_TYPE,				"TWAIN the type of container is not supported for now"},
{IGE_TW_CAP_QUERY_FAILED,						"TWAIN cap query failed"},
{IGE_TW_CAP_ID_NOT_FOUND,						"TWAIN cap Id is not found"},
{IGE_TW_CANT_GET_CAP_STORAGE,					"TWAIN can't get the storage with caps"},
{IGE_TW_WRONG_CAP_INTERNAL_TYPE,				"TWAIN wrong cap internal type"},
{IGE_TW_NO_MORE_CAP_VALUES,					"TWAIN no more cap values left"},
{IGE_TW_NO_NEXT_CAP_VALUE_INFO,				"TWAIN next cap value is not stored"},
{IGE_TW_NO_DFLT_CAP_VALUE_INFO,				"TWAIN default cap value is not stored"},
{IGE_TW_NO_CURR_CAP_VALUE_INFO,				"TWAIN current cap value is not stored"},
{IGE_TW_CANT_QUERY_CAP,							"TWAIN can't query the cap specified"},
{IGE_TW_CANT_SET_FEEDER,						"TWAIN can't set feeder"},
{IGE_TW_WRONG_CAP_TYPE,							"TWAIN wrong type of cap"},
{IGE_TW_WROND_INDEX,								"TWAIN wrong index in the enumeration container"},
{IGE_TW_CANT_GET_CURRENT_VALUE,				"TWAIN can't get current value"},
{IGE_TW_PIX_TYPE_MISMATCH,						"TWAIN pixel type and bit depth. mismatch"},
{IGE_TW_CANT_GET_PALETTE,						"TWAIN can't get palette"},
{IGE_PALETTE_TYPE_NOT_SUPPORTED,				"TWAIN type of palette is not supported"},
{IGE_TW_PALETTE_SIZE_MISMATCH,				"TWAIN palette size and bit depth. mismatch"},
{IGE_TW_PIXEL_TYPE_UNSUPPORTED,				"TWAIN pixel type is not supported"},

/* TWAIN CB */
{IGE_TW_WRONG_CB_TYPE,							"TWAIN wrong CallBack type"},
{IGE_TW_CAN_NOT_GET_IMAGE_LAYOUT,			"TWAIN can't get image layout"},
{IGE_TW_TRANSFER_CB_CANCELED,					"TWAIN transfer canceled via CallBack"},

/* end IG7-TWAIN */

/***************************************************************************/
/* Disk File Access Error codes                                            */
/***************************************************************************/

{IGE_FILE_CANT_OPEN,							"Open file failure"},
{IGE_FILE_CANT_SAVE,							"Save file failure"},
{IGE_FILE_CANT_DELETE,						"Delete file failure"},
{IGE_FILE_INVALID_FILENAME,				"Invalid file name"},
{IGE_FILE_INVALID_PATH,						"Invalid path"},


/***************************************************************************/
/* GUI Function Error codes                                                */
/***************************************************************************/

{IGE_REGISTER_CLASS_FAILED,				"RegisterClass failed"},
{IGE_CREATE_WINDOW_FAILED,					"CreateWindow failed"},
{IGE_WINDOW_NOT_ASSOCATED,					"Window not associated"},
{IGE_INVALID_WINDOW,							"Invalid window handle"},


/***************************************************************************/
/* VBX/OCX level error codes                                               */
/***************************************************************************/

{IGE_VBX_INVALID_FUNCTION_NUM,			"Invalid VBX function number"},
{IGE_ROTATE_ENUMERATED_VALUES_NOT_USED,"The rotate enumerated values were not used"},
{IGE_GUIWINDOW_TYPE_INVALID,				"GUIWindow type invalid or not selected"},


/***************************************************************************/
/* OS2 error codes                                               */
/***************************************************************************/

{IGE_UNREGISTERED_HAB,						"HAB is not resgistered"},


/***************************************************************************/
/* Auto Detect error codes                                                 */
/***************************************************************************/

{IGE_FILE_IS_SYSTEM_FILE,					"The image file passed in is really one of the following system files and not an image"},
{IGE_FILE_IS_EXE,								"File is a EXE, DLL, DRV, FNT, OCX, or 386"},
{IGE_FILE_IS_ZIP,								"File is a PKZIP file"},
{IGE_FILE_IS_DOC,								"File is a Microsoft DOC file"},
{IGE_FILE_IS_HLP,								"File is a Microsoft system Help file"},


/***************************************************************************/
/* Application error codes must be less that the following last number used*/
/***************************************************************************/

/* Keep this one last - it is used to mark the end of the list */
{IGE_LAST_ERROR_NUMBER,						""}
};


/*#ifndef __GEARESTR_H__*/
#endif
