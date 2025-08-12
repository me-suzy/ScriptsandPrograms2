<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

   	
require_once('../classes/pnt/unit/classPntTestCase.php');

class StringConverterTest extends PntTestCase {
	
	var $cnv;
	var $infiniteBig=999999999999999999999999999999999999999999999999999999.0;
	var $infiniteSmall=-999999999999999999999999999999999999999999999999999999.0;
	
	function setUp() {
		includeClass('PntStringConverter', 'pnt/web');
		$this->cnv = new PntStringConverter();
		$this->cnv->type = "string";
	}

	function test_statics()
	{
		//no tests yet
	}
	
	function test_convertToBoolean()
	{
		$this->cnv->type = "boolean";
		
		$string = 'true';
		$this->assertTrue(
			$this->cnv->convert($string), 'true'
			);
		$this->assertNull(
			$this->cnv->error, 'true'
			);

		$string = 'false';
		$this->assertFalse(
			$this->cnv->convert($string), 'false'
			);
		$this->assertNull(
			$this->cnv->error, 'false'
			);

		$string = '';
		$this->assertNull(
			$this->cnv->convert($string), 'empty'
			);
		$this->assertNull(
			$this->cnv->error, 'empty'
			);

		$string = null;
		$this->assertNull(
			$this->cnv->convert($string), 'null'
			);
		$this->assertNull(
			$this->cnv->error, 'empty'
			);

		$string = 'xxxx';
		$this->assertTrue(
			$this->cnv->convert($string), 'xxxx'
			);
		$this->assertEquals(
			"invalid boolean, expected: true/false"
			, $this->cnv->error, 'xxxx'
			);
	}

	function test_convertToNumber() {
		$this->cnv->type = "number";
		$this->cnv->decimalPrecision = 4;

		$string = '12,345.6789';
		$this->assertSame(
			12345.6789
			, $this->cnv->convert($string), '12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '12,345.6789'
			);

		$string = number_format(-12345.6789, 4,'.',',');
		$this->assertSame(
			- 12345.6789
			, $this->cnv->convert($string), '-12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '-12,345.6789'
			);
		$string = '- 12,345.6789';
		$this->assertSame(
			0.0
			, $this->cnv->convert($string), '- 12,345.6789'
			);
		$this->assertEquals(
			"invalid number, expected is like: -4.1234"
			, $this->cnv->error, '- 12,345.6789'
			);

		// dutch format tests		
		$this->cnv->decimal = ',';
		$this->cnv->thousends = '.';
		$string = '12,345.6789';
		$this->assertSame(
			12.3456789
			, $this->cnv->convert($string), '12,345.6789 dutch'
			);
		$this->assertNull(
			$this->cnv->error, '12,345.6789 dutch'
			);

		$string = number_format(-12345.6789, 4,',','.');
		$this->assertSame(
			- 12345.6789
			, $this->cnv->convert($string), '-12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '-12.345,6789'
			);

		$string = 'xxxx';
		$this->assertSame(
			0.0
			, $this->cnv->convert($string), 'xxxx'
			);
		$this->assertEquals(
			"invalid number, expected is like: -4,1234"
			, $this->cnv->error, 'xxxx'
			);

		$this->cnv->decimalPrecision = 0;
		$string = "12345";
		$this->assertSame(
			12345.0
			, $this->cnv->convert($string), '12345'
			);
		$this->assertNull(
			$this->cnv->error, '12345'
			);
	}
	
	function test_convertToDate() 
	{
		$this->cnv->type = "date";

		$string = '2002-01-31';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$this->cnv->dateFormat = "d-m-Y";

		$string = '10-01-2002';
		$this->assertSame(
			'2002-01-10'
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);

		$string = 'Invalid datestring';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid date, expected is like: ".date($this->cnv->dateFormat)
			, $this->cnv->error,  $string
			);

		$string = '1-13-2002';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid date, expected is like: ".date($this->cnv->dateFormat)
			, $this->cnv->error,  $string
			);

		$string = '29-02-2003';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid date, expected is like: ".date($this->cnv->dateFormat)
			, $this->cnv->error,  $string
			);
	}

	function test_convertToTime() 
	{
		$this->cnv->type = "time";

		$string = '21:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$string = 'Invalid time string';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid time, expected is like: ".date($this->cnv->timeFormat)
			, $this->cnv->error,  $string
			);

		$string = '11:03:60';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid time, expected is like: ".date($this->cnv->timeFormat)
			, $this->cnv->error,  $string
			);

		$string = '31:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid time, expected is like: ".date($this->cnv->timeFormat)
			, $this->cnv->error,  $string
			);

		$string = '11:63:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid time, expected is like: ".date($this->cnv->timeFormat)
			, $this->cnv->error,  $string
			);

		$string = '11:61:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid time, expected is like: ".date($this->cnv->timeFormat)
			, $this->cnv->error,  $string
			);


		$this->cnv->timeFormat = "s:i:H";
		$string = '12:03:11';
		$this->assertSame(
			'11:03:12'
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);

	}

	function test_convertToTimestamp() 
	{
		$this->cnv->type = "timestamp";

		$string = '2002-01-31 21:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$string = 'Invalid timestamp string';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid timestamp, expected is like: ".date($this->cnv->timestampFormat)
			, $this->cnv->error,  $string
			);

		$string = '1-13-2002 11:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid timestamp, expected is like: ".date($this->cnv->timestampFormat)
			, $this->cnv->error,  $string
			);

		$string = '29-02-2003 11:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid timestamp, expected is like: ".date($this->cnv->timestampFormat)
			, $this->cnv->error,  $string
			);

		$string = '2002-01-31 31:03:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid timestamp, expected is like: ".date($this->cnv->timestampFormat)
			, $this->cnv->error,  $string
			);

		$string = '2002-01-31 11:61:12';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertEquals(
			"invalid timestamp, expected is like: ".date($this->cnv->timestampFormat)
			, $this->cnv->error,  $string
			);


		$this->cnv->timestampFormat = "s:i:H d-m-Y";
		$string = '12:03:11 31-01-2002';
		$this->assertSame(
			'2002-01-31 11:03:12'
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);

	}

	function test_convertToString()
	{
		$this->cnv->type = "string";

		$string = '123456';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
		$string = 123456;
		$this->assertSame(
			'123456'
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
	}

	function test_convertToEmail()
	{
		$type = "email";

		$string = '123456';
		$this->assertSame(
			$string
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
		$string = 123456;
		$this->assertSame(
			'123456'
			, $this->cnv->convert($string), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
	}


	function test_fromLabel()
	{
		$this->cnv->type = 'number';
		$value = '12';
		$this->assertSame(
			12.0,
			$this->cnv->fromLabel($value), $value
		);		
		$this->assertNull(
			$this->cnv->error , $value
		);
	}

	function test_labelFromBoolean()
	{
		$type = "boolean";
		
		$value = true;
		$this->assertSame(
			'true',
			$this->cnv->toLabel($value, $type), 'true'
			);
		$this->assertNull(
			$this->cnv->error, 'true'
			);

		$value = false;
		$this->assertSame(
			'false',
			$this->cnv->toLabel($value, $type), 'false'
			);
		$this->assertNull(
			$this->cnv->error, 'false'
			);


		$value = null;
		$this->assertSame(
			'',
			$this->cnv->toLabel($value, $type), 'null'
			);
		$this->assertNull(
			$this->cnv->error, 'null'
			);

	}

	function test_labelFromNumber() {
		$type = "number";
		$this->cnv->decimalPrecision = 4;

		$string = '12,345.6789';
		$value = 12345.6789;
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), '12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '12,345.6789'
			);

		$value = -12345.6789;
		$string = number_format($value, 4,'.',',');
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), '-12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '-12,345.6789'
			);

		// dutch format tests		
		$this->cnv->decimal = ',';
		$this->cnv->thousends = '.';
		$string = '12.345,6789';
		$value = 12345.6789;
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), '12,345.6789 dutch'
			);
		$this->assertNull(
			$this->cnv->error, '12,345.6789 dutch'
			);

		$value = -12345.6789;
		$string = '-12.345,6789';
		
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), '-12,345.6789'
			);
		$this->assertNull(
			$this->cnv->error, '-12.345,6789'
			);
		
	}
	
	function test_labelFromDate() 
	{
		$type = "date";

		$string = '2002-01-31';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($string, $type), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$this->cnv->dateFormat = "d-m-Y";

		$string = '10-01-2002';
		$value = '2002-01-10';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);
	}

	function test_labelFromTime() 
	{
		$type = "time";

		$string = '21:03:12';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($string, $type), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$this->cnv->timeFormat = "s:i:H";
		$string = '12:03:11';
		$value = '11:03:12';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);

	}

	function test_labelFromTimestamp() 
	{
		$type = "timestamp";

		$string = '2002-01-31 21:03:12';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($string, $type), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
			
		$this->cnv->timestampFormat = "s:i:H d-m-Y";
		$string = '12:03:11 31-01-2002';
		$value = '2002-01-31 11:03:12';
		$this->assertSame(
			$string
			, $this->cnv->toLabel($value, $type), $string
			);
		$this->assertNull(
			$this->cnv->error, $string
			);

	}

	function test_labelFromString()
	{
		$type = "string";

		$string = '123456';
		$this->assertSame(
			$string
			, $this->cnv->convert($string, $type), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
		$string = 123456;
		$this->assertSame(
			'123456'
			, $this->cnv->convert($string, $type), $string
			);
		$this->assertNull(
			$this->cnv->error,  $string
			);
	}


}

return 'StringConverterTest';
?>
