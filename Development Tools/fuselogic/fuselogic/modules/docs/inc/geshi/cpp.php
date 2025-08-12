<?php
/*************************************************************************************
 * cpp.php
 * -------
 * Author: Dennis Bayer (Dennis.Bayer@mnifh-giessen.de)
 * Copyright: (c) 2004 Dennis Bayer
 * Release Version: 1.0.0
 * CVS Revision Version: $Revision: 1.3 $
 * Date Started: 2004/09/27
 * Last Modified: $Date: 2004/10/27 03:18:39 $
 *
 * C++ language file for GeSHi.
 *
 * CHANGES
 * -------
 * 2004/10/27 (1.0.0)
 *  -  First Release
 *
 * TODO (updated 2004/XX/XX)
 * -------------------------
 *
 *************************************************************************************
 *
 *     This file is part of GeSHi.
 *
 *   GeSHi is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   GeSHi is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with GeSHi; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ************************************************************************************/

$language_data = array (
	'LANG_NAME' => 'C++',
	'COMMENT_SINGLE' => array(1 => '//', 2 => '#'),
	'COMMENT_MULTI' => array('/*' => '*/'),
	'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
	'QUOTEMARKS' => array("'", '"'),
	'ESCAPE_CHAR' => '\\',
	'KEYWORDS' => array(
		1 => array(
			'case', 'continue', 'default', 'do', 'else', 'for', 'goto', 'if', 'return',
			'switch', 'while'
			),
		2 => array(
			'break', 'class', 'enum', 'extern', 'false', 'function', 'namespace',
			'null', 'public', 'private', 'true', 'virtual'
			),
		3 => array(
			'cin', 'cerr', 'clog', 'cout', 'delete', 'new', 'sizeof', 'this', 'length',
			'assert',
			'fstream', 'ifstream', 'ofstream',
			'fgetc', 'fgets', 'fread', 'fscanf', 'getc', 'getchar', 'gets', 'scanf', 'ungetc',
			'fprintf', 'fputc', 'fputs', 'fwrite', 'printf', 'putc', 'putchar', 'puts', 'vfprintf', 'vprintf',
			'fflush', 'fseek', 'fsetpos', 'rewind',
			'clearerr', 'fclose', 'feof', 'ferror', 'fgetpos', 'fopen', 'fpos_t', 'fread',
			'freopen', 'fscanf', 'ftell', 'perror', 'remove', 'rename setbuf', 'setvbuf,',
			'sprintf', 'sscanf', 'stderr', 'stdin', 'stdout',
			'size_t', 'strcat', 'strchr', 'strcmp', 'strcoll', 'strcpy', 'strcspn', 'strerror',
			'strlen', 'strncat', 'strncmp', 'strncpy', 'strpbrk', 'strrchr', 'strspn',
			'strstr', 'strtok', 'strxfrm',
			'abs', 'acos', 'asin', 'atan', 'atan2', 'ceil', 'cos', 'cosh', 'exp', 'fabs',
			'floor', 'fmod', 'frexp', 'ldexp', 'log', 'log10', 'modf', 'pow', 'sin',
			'sinh', 'sqrt', 'tan', 'tanh','acosf', 'asinf', 'atanf', 'atan2f', 'ceilf',
			'cosf', 'coshf', 'expf', 'fabsf', 'floorf', 'fmodf', 'frexpf', 'ldexpf',
			'logf', 'log10f', 'modff', 'pow', 'sinf', 'sinhf', 'sqrtf', 'tanf', 'tanhf',
			'acosl', 'asinl', 'atanl', 'atan2l', 'ceill', 'cosl', 'coshl', 'expl',
			'fabsl', 'floorl', 'fmodl', 'frexpl', 'ldexpl', 'logl', 'log10l', 'modfl',
			'pow', 'sinl', 'sinhl', 'sqrtl', 'tanl', 'tanhl'
			),
		4 => array(
			'auto', 'bool', 'char', 'const', 'double', 'float', 'int', 'long', 'longint',
			'register', 'short', 'shortint', 'signed', 'static', 'struct',
			'typedef', 'union', 'unsigned', 'void', 'volatile'
			),
		),
	'SYMBOLS' => array(
		'(', ')', '{', '}', '[', ']', '=', '+', '-', '*', '/', '!', '%', '^', '&', ':'
		),
	'CASE_SENSITIVE' => array(
		GESHI_COMMENTS => true,
		1 => false,
		2 => false,
		3 => false,
		4 => false,
		),
	'STYLES' => array(
		'KEYWORDS' => array(
			1 => 'color: #b1b100;',
			2 => 'color: #000000; font-weight: bold;',
			3 => '',
			4 => 'color: #993333;'
			),
		'COMMENTS' => array(
			1 => 'color: #808080; font-style: italic;',
			2 => 'color: #339933;',
			'MULTI' => 'color: #808080; font-style: italic;'
			),
		'ESCAPE_CHAR' => array(
			0 => 'color: #000099; font-weight: bold;'
			),
		'BRACKETS' => array(
			0 => 'color: #66cc66;'
			),
		'STRINGS' => array(
			0 => 'color: #ff0000;'
			),
		'NUMBERS' => array(
			0 => 'color: #cc66cc;'
			),
		'METHODS' => array(
			0 => 'color: #202020;'
			),
		'SYMBOLS' => array(
			0 => 'color: #66cc66;'
			),
		'REGEXPS' => array(
			),
		'SCRIPT' => array(
			)
		),
	'URLS' => array(
		),
	'OOLANG' => true,
	'OBJECT_SPLITTER' => '.',
	'REGEXPS' => array(
		),
	'STRICT_MODE_APPLIES' => GESHI_NEVER,
	'SCRIPT_DELIMITERS' => array(
		),
	'HIGHLIGHT_STRICT_BLOCK' => array(
		)
);

?>


