<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  This part was extracted from a package of free sha
  algorithms if found in the www. If someone wants to 
  stand here - tell me

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
if (!function_exists('str_split')) {
    function str_split($string, $split_length = 1)
    {
        $r = array();

        if ($split_length < 1) {
            return false;
        } 
        if ($split_length > strlen($string)) {
            return array($string);
        } 
        for($i = 0; $i < strlen($string); $i += $split_length) {
            $r[] = substr($string, $i, $split_length);
        } 
        return $r;
    } 
} 
// shift right
function shr($x, $n)
{
    return (0x80000000 &$x) ? (($x >> 1) &~0x80000000 | 0x40000000) >> ($n - 1) : ($x >> $n);
} 
// rotate left
function rotl($x, $n)
{
    return ($x << $n) | shr($x, (32 - $n));
} 
// rotate right
function rotr($x, $n)
{
    return shr($x, $n) | ($x << (32 - $n));
} 
function sha2($str, $raw_output = false)
{
    $h0 = 0x6a09e667;
    $h1 = 0xbb67ae85;
    $h2 = 0x3c6ef372;
    $h3 = 0xa54ff53a;
    $h4 = 0x510e527f;
    $h5 = 0x9b05688c;
    $h6 = 0x1f83d9ab;
    $h7 = 0x5be0cd19;
    $k = array
    (0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
        0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174,
        0xe49b69c1, 0xefbe4786, 0x0fc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da,
        0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x06ca6351, 0x14292967,
        0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85,
        0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070,
        0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3,
        0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2
        );
    $l = strlen($str);
    $str = str_pad("$str\x80\x0\x0\x0\x0", ($l &~ 63) + ((($l &63) < 56) ? 60 : 124), "\x0") . pack('N', $l << 3);
    $chunks = str_split($str, 64);

    foreach($chunks as $chunk) {
        $w = array_values(unpack('N16', $chunk));

        for($i = 16; $i <= 63; $i++) {
            $s0 = rotr($w[$i - 15], 7) ^ rotr($w[$i - 15], 18) ^ shr($w[$i - 15], 3);
            $s1 = rotr($w[$i - 2], 17) ^ rotr($w[$i - 2], 19) ^ shr($w[$i - 2], 10);
            $w[$i] = $s1 + $w[$i - 7] + $s0 + $w[$i - 16];
        } 
        $a = $h0;
        $b = $h1;
        $c = $h2;
        $d = $h3;
        $e = $h4;
        $f = $h5;
        $g = $h6;
        $h = $h7;

        for($i = 0; $i <= 63; $i++) {
            $s0 = rotr($a, 2) ^ rotr($a, 13) ^ rotr($a, 22);
            $s1 = rotr($e, 6) ^ rotr($e, 11) ^ rotr($e, 25);
            $ch = ($e &$f) ^ (~$e &$g);
            $t1 = (int) ($h + $s1 + $ch + $k[$i] + $w[$i]);
            $maj = ($a &$b) ^ ($b &$c) ^ ($c &$a);
            $t2 = (int) ($s0 + $maj);

            $h = $g;
            $g = $f;
            $f = $e;
            $e = $d + $t1;
            $d = $c;
            $c = $b;
            $b = $a;
            $a = $t1 + $t2;
        } 
        $h0 = $h0 + $a;
        $h1 = $h1 + $b;
        $h2 = $h2 + $c;
        $h3 = $h3 + $d;
        $h4 = $h4 + $e;
        $h5 = $h5 + $f;
        $h6 = $h6 + $g;
        $h7 = $h7 + $h;
    } 
    $hash = pack('N*', $h0, $h1, $h2, $h3, $h4, $h5, $h6, $h7);

    if ($raw_output) {
        return $hash;
    } 
    return bin2hex($hash);
} 

?> 