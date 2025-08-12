<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>ibzi.net countdown script</title>
</head>
<body>
<form action="<?=$PHP_SELF?>">
<p>
Enter your birthday:<br>
<select name="month" size="1">
<option value="-1">
</option>
<option value="01" selected>January
</option>
<option value="02">
February</option>
<option value="03">
March</option>
<option value="04">
April</option>
<option value="05">
May</option>
<option value="06">
June</option>
<option value="07">
July</option>
<option value="08">
August</option>
<option value="09">
September</option>
<option value="10">
October</option>
<option value="11">
November</option>
<option value="12">
December</option>
</select><select name="day" size="1">
<option value="-1">
</option>
<option value="01" selected>1
</option>
<option value="02">
2</option>
<option value="03">
3</option>
<option value="04">
4</option>
<option value="05">
5</option>
<option value="06">
6</option>
<option value="07">
7</option>
<option value="08">
8</option>
<option value="09">
9</option>
<option value="10">
10</option>
<option value="11">
11</option>
<option value="12">
12</option>
<option value="13">
13</option>
<option value="14">
14</option>
<option value="15">
15</option>
<option value="16">
16</option>
<option value="17">
17</option>
<option value="18">
18</option>
<option value="19">
19</option>
<option value="20">
20</option>
<option value="21">
21</option>
<option value="22">
22</option>
<option value="23">
23</option>
<option value="24">
24</option>
<option value="25">
25</option>
<option value="26">
26</option>
<option value="27">
27</option>
<option value="28">
28</option>
<option value="29">
29</option>
<option value="30">
30</option>
<option value="31">
31</option>
</select><input maxLength="4" size="6" name="year" value="2006"><br>
<br>
Hour of birth:
<select name="hour" size="1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12" selected>12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
</select>
Minute of birth:
<select name="minute" size="1">
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
<option value="32">32</option>
<option value="33">33</option>
<option value="34">34</option>
<option value="35">35</option>
<option value="36">36</option>
<option value="37">37</option>
<option value="38">38</option>
<option value="39">39</option>
<option value="40">40</option>
<option value="41">41</option>
<option value="42">42</option>
<option value="43">43</option>
<option value="44">44</option>
<option value="45">45</option>
<option value="46">46</option>
<option value="47">47</option>
<option value="48">48</option>
<option value="49">49</option>
<option value="50">50</option>
<option value="51">51</option>
<option value="52">52</option>
<option value="53">53</option>
<option value="54">54</option>
<option value="55">55</option>
<option value="56">56</option>
<option value="57">57</option>
<option value="58">58</option>
<option value="59">59</option>
<option value="60">60</option>
</select><br>
<input type="submit" value="Submit">
</form>
<br><b><a href="http://www.ibzi.net">Please add a link to ibzi.net!</a></b><br><br>
</body>

</html>
<?
if (!$year) { die; }
// ibzi.net script!
// countdiff function (year,month,day,hour,minute);
// ibzi.net script!
countdiff($year,$month,$day,$hour,$minute);
function countdiff($y, $mo, $d, $h, $m)
{
$cdate = mktime($h, $m, 0, $mo, $d, $y, -1);
$today = time();
$difference = $cdate - $today;
if ($difference < 0) { $difference = 0; }
$dleft = floor($difference/60/60/24);
$hleft = floor(($difference - $dleft*60*60*24)/60/60);
$mleft = floor(($difference - $dleft*60*60*24 - $hleft*60*60)/60);
$countdowndate = date("F j, Y, g:i a",$cdate);
echo "<b>$dleft days</b>, <b>$hleft hours</b> and <b>$mleft minutes</b> left until <b>$countdowndate</b>";
}
// ibzi.net script!
?>

