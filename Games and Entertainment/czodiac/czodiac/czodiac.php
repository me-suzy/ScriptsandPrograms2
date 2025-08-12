<?php

$showtable = true;

if (!empty($birthdaysubmit)) {

$cdate_monthdata=array(
0=>array(8,0,0,0,0,0,0,0,0,0,0,0,29,30,7,1),
1=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,8,2),
2=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,9,3),
3=>array(5,29,30,29,30,29,29,30,29,29,30,30,29,30,10,4),
4=>array(0,30,30,29,30,29,29,30,29,29,30,30,29,0,1,5),
5=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,2,6),
6=>array(4,29,30,30,29,30,29,30,29,30,29,30,29,30,3,7),
7=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,4,8),
8=>array(0,30,29,29,30,30,29,30,29,30,30,29,30,0,5,9),
9=>array(2,29,30,29,29,30,29,30,29,30,30,30,29,30,6,10),
10=>array(0,29,30,29,29,30,29,30,29,30,30,30,29,0,7,11),
11=>array(6,30,29,30,29,29,30,29,29,30,30,29,30,30,8,12),
12=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,9,1),
13=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,10,2),
14=>array(5,30,30,29,30,29,30,29,30,29,30,29,29,30,1,3),
15=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,2,4),
16=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,3,5),
17=>array(2,30,29,29,30,29,30,30,29,30,30,29,30,29,4,6),
18=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,5,7),
19=>array(7,29,30,29,29,30,29,29,30,30,29,30,30,30,6,8),
20=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,7,9),
21=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,8,10),
22=>array(5,30,29,30,30,29,29,30,29,29,30,29,30,30,9,11),
23=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,10,12),
24=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,1,1),
25=>array(4,30,29,30,29,30,30,29,30,30,29,30,29,30,2,2),
26=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,3,3),
27=>array(0,30,29,29,30,29,30,29,30,29,30,30,30,0,4,4),
28=>array(2,29,30,29,29,30,29,29,30,29,30,30,30,30,5,5),
29=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,6,6),
30=>array(6,29,30,30,29,29,30,29,29,30,29,30,30,29,7,7),
31=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,8,8),
32=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,9,9),
33=>array(5,29,30,30,29,30,30,29,30,29,30,29,29,30,10,10),
34=>array(0,29,30,29,30,30,29,30,29,30,30,29,30,0,1,11),
35=>array(0,29,29,30,29,30,29,30,30,29,30,30,29,0,2,12),
36=>array(3,30,29,29,30,29,29,30,30,29,30,30,30,29,3,1),
37=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,4,2),
38=>array(7,30,30,29,29,30,29,29,30,29,30,30,29,30,5,3),
39=>array(0,30,30,29,29,30,29,29,30,29,30,29,30,0,6,4),
40=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,7,5),
41=>array(6,30,30,29,30,30,29,30,29,29,30,29,30,29,8,6),
42=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,9,7),
43=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,10,8),
44=>array(4,30,29,30,29,30,29,30,29,30,30,29,30,30,1,9),
45=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,2,10),
46=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,3,11),
47=>array(2,30,30,29,29,30,29,29,30,29,30,29,30,30,4,12),
48=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,5,1),
49=>array(7,30,29,30,30,29,30,29,29,30,29,30,29,30,6,2),
50=>array(0,29,30,30,29,30,30,29,29,30,29,30,29,0,7,3),
51=>array(0,30,29,30,30,29,30,29,30,29,30,29,30,0,8,4),
52=>array(5,29,30,29,30,29,30,29,30,30,29,30,29,30,9,5),
53=>array(0,29,30,29,29,30,30,29,30,30,29,30,29,0,10,6),
54=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,1,7),
55=>array(3,29,30,29,30,29,29,30,29,30,29,30,30,30,2,8),
56=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,3,9),
57=>array(8,30,29,30,29,30,29,29,30,29,30,29,30,29,4,10),
58=>array(0,30,30,30,29,30,29,29,30,29,30,29,30,0,5,11),
59=>array(0,29,30,30,29,30,29,30,29,30,29,30,29,0,6,12),
60=>array(6,30,29,30,29,30,30,29,30,29,30,29,30,29,7,1),
61=>array(0,30,29,30,29,30,29,30,30,29,30,29,30,0,8,2),
62=>array(0,29,30,29,29,30,29,30,30,29,30,30,29,0,9,3),
63=>array(4,30,29,30,29,29,30,29,30,29,30,30,30,29,10,4),
64=>array(0,30,29,30,29,29,30,29,30,29,30,30,30,0,1,5),
65=>array(0,29,30,29,30,29,29,30,29,29,30,30,29,0,2,6),
66=>array(3,30,30,30,29,30,29,29,30,29,29,30,30,29,3,7),
67=>array(0,30,30,29,30,30,29,29,30,29,30,29,30,0,4,8),
68=>array(7,29,30,29,30,30,29,30,29,30,29,30,29,30,5,9),
69=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,6,10),
70=>array(0,30,29,29,30,29,30,30,29,30,30,29,30,0,7,11),
71=>array(5,29,30,29,29,30,29,30,29,30,30,30,29,30,8,12),
72=>array(0,29,30,29,29,30,29,30,29,30,30,29,30,0,9,1),
73=>array(0,30,29,30,29,29,30,29,29,30,30,29,30,0,10,2),
74=>array(4,30,30,29,30,29,29,30,29,29,30,30,29,30,1,3),
75=>array(0,30,30,29,30,29,29,30,29,29,30,29,30,0,2,4),
76=>array(8,30,30,29,30,29,30,29,30,29,29,30,29,30,3,5),
77=>array(0,30,29,30,30,29,30,29,30,29,30,29,29,0,4,6),
78=>array(0,30,29,30,30,29,30,30,29,30,29,30,29,0,5,7),
79=>array(6,30,29,29,30,29,30,30,29,30,30,29,30,29,6,8),
80=>array(0,30,29,29,30,29,30,29,30,30,29,30,30,0,7,9),
81=>array(0,29,30,29,29,30,29,29,30,30,29,30,30,0,8,10),
82=>array(4,30,29,30,29,29,30,29,29,30,29,30,30,30,9,11),
83=>array(0,30,29,30,29,29,30,29,29,30,29,30,30,0,10,12),
84=>array(10,30,29,30,30,29,29,30,29,29,30,29,30,30,1,1),
85=>array(0,29,30,30,29,30,29,30,29,29,30,29,30,0,2,2),
86=>array(0,29,30,30,29,30,30,29,30,29,30,29,29,0,3,3),
87=>array(6,30,29,30,29,30,30,29,30,30,29,30,29,29,4,4),
88=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,5,5),
89=>array(0,30,29,29,30,29,29,30,30,29,30,30,30,0,6,6),
90=>array(5,29,30,29,29,30,29,29,30,29,30,30,30,30,7,7),
91=>array(0,29,30,29,29,30,29,29,30,29,30,30,30,0,8,8),
92=>array(0,29,30,30,29,29,30,29,29,30,29,30,30,0,9,9),
93=>array(3,29,30,30,29,30,29,30,29,29,30,29,30,29,10,10),
94=>array(0,30,30,30,29,30,29,30,29,29,30,29,30,0,1,11),
95=>array(8,29,30,30,29,30,29,30,30,29,29,30,29,30,2,12),
96=>array(0,29,30,29,30,30,29,30,29,30,30,29,29,0,3,1),
97=>array(0,30,29,30,29,30,29,30,30,29,30,30,29,0,4,2),
98=>array(5,30,29,29,30,29,29,30,30,29,30,30,29,30,5,3),
99=>array(0,30,29,29,30,29,29,30,29,30,30,30,29,0,6,4),
100=>array(0,30,30,29,29,30,29,29,30,29,30,30,29,0,7,5),
101=>array(4,30,30,29,30,29,30,29,29,30,29,30,29,30,8,6),
102=>array(0,30,30,29,30,29,30,29,29,30,29,30,29,0,9,7),
103=>array(0,30,30,29,30,30,29,30,29,29,30,29,30,0,10,8),
104=>array(2,29,30,29,30,30,29,30,29,30,29,30,29,30,1,9),
105=>array(0,29,30,29,30,29,30,30,29,30,29,30,29,0,2,10),
106=>array(7,30,29,30,29,30,29,30,29,30,30,29,30,30,3,11),
107=>array(0,29,29,30,29,29,30,29,30,30,30,29,30,0,4,12),
108=>array(0,30,29,29,30,29,29,30,29,30,30,29,30,0,5,1),
109=>array(5,30,30,29,29,30,29,29,30,29,30,29,30,30,6,2),
110=>array(0,30,29,30,29,30,29,29,30,29,30,29,30,0,7,3),
111=>array(0,30,29,30,30,29,30,29,29,30,29,30,29,0,8,4),
112=>array(4,30,29,30,30,29,30,29,30,29,30,29,30,29,9,5),
113=>array(0,30,29,30,29,30,30,29,30,29,30,29,30,0,10,6),
114=>array(9,29,30,29,30,29,30,29,30,30,29,30,29,30,1,7),
115=>array(0,29,30,29,29,30,29,30,30,30,29,30,29,0,2,8),
116=>array(0,30,29,30,29,29,30,29,30,30,29,30,30,0,3,9),
117=>array(6,29,30,29,30,29,29,30,29,30,29,30,30,30,4,10),
118=>array(0,29,30,29,30,29,29,30,29,30,29,30,30,0,5,11),
119=>array(0,30,29,30,29,30,29,29,30,29,29,30,30,0,6,12),
120=>array(4,29,30,30,30,29,30,29,29,30,29,30,29,30,7,1)
);
         
$cdate_zodiacarray=array("null","Rat","Ox","Tiger","Rabbit","Dragon","Snake","Horse","Sheep","Monkey","Rooster","Dog","Pig");

$cdate_zodicdescrptionarray[1] = "People born under the sign of Rat are noted for their charm and attraction for the opposite sex. They are hardworking and consequently often financially wealthy. And are likely to be perfectionists. Rat people are easily angered and love to gossip. Their ambitions are big, and they are usually very successful. The Mouse is very social and has many friends who he supports in generous ways. The family is important; a Mouse is loyal to his family and will fight for it if needed.They are most compatible with people born in the years of the Dragon, Monkey, and Ox.";
$cdate_zodicdescrptionarray[2] = "People born under the sign of Ox are patient, taciturn, and inspire confidence in others. They tend to be eccentric, and bigoted, and they anger easily. They have fierce tempers and although they speak little, when they do they are quite eloquent. Ox people are mentally and physically alert. Generally easy-going, they are remarkably stubborn, and they hate to fail or be opposed. They are most compatible with Snake, Rooster, and Rat people.";
$cdate_zodicdescrptionarray[3] = "People born under the sign of Tiger are sensitive, given to deep thinking, capable of great sympathy. They can be extremely short-tempered, however. Other people have great respect for them, but sometimes tiger people come into conflict with older people or those in authority. sometimes Tiger people cannot make up their minds, which can result in a poor, hasty decision or a sound decision arrived at too late. They are suspicious of others, but they are courageous and powerful. Tigers are most compatible with Horses, Dragons, and Dogs";
$cdate_zodicdescrptionarray[4] = "People born in the Year of the Rabbit are articulate, talented, and ambitious. They are virtuous, reserved, and have excellent taste. Rabbit people are admired, trusted, and are often financially lucky. They are fond of gossip but are tactful and generally kind. Rabbit people seldom lose their temper. They are clever at business and being conscientious, never back out of a contract. They would make good gamblers for they have the uncanny gift of choosing the right thing. However, they seldom gamble, as they are conservative and wise. They are most compatible with those born in the years of the Sheep, Pig, and Dog.";
$cdate_zodicdescrptionarray[5] = "People born in the Year of the Dragon are healthy, energetic, excitable, short-tempered, and stubborn. They are also honest, sensitive, brave, and they inspire confidence and trust. Dragon people are the most eccentric of any in the eastern zodiac. They neither borrow money nor make flowery speeches, but they tend to be soft-hearted which sometimes gives others an advantage over them. They are compatible with Rats, Snakes, Monkeys, and Roosters.";
$cdate_zodicdescrptionarray[6] = "People born in the Year of the Snake are deep. They say little and possess great wisdom. They never have to worry about money; they are financially fortunate. Snake people are often quite vain, selfish, and a bit stingy. Yet they have tremendous sympathy for others and try to help those less fortunate. Snake people tend to overdo, since they have doubts about other people's judgment and prefer to rely on themselves. They are determined in whatever they do and hate to fail. Although calm on the surface, they are intense and passionate. Snake people are usually good-looking and sometimes have martial problems because they are fickle. They are most compatible with the Ox and Rooster.";
$cdate_zodicdescrptionarray[7] = "People born in the Year of the Horse are popular. They are cheerful, skillful with money, and perceptive, although they sometimes talk too much. The are wise, talented, good with their hands, and sometimes have a weakness for members of the opposite sex. They are impatient and hot-blooded about everything except their daily work. They like entertainment and large crowds. They are very independent and rarely listen to advice. They are most compatible with Tigers, Dogs, and Sheep.";
$cdate_zodicdescrptionarray[8] = "People born in the Year of Sheep are elegant and highly accomplished in the arts. They seem to be, at first glance, better off than those born in the zodiac's other years. But sheep year people are often shy, pessimistic, and puzzled about life. They are usually deeply religious, yet timid by nature. Sometimes clumsy in speech, they are always passionate about what they do and what they believe in. Sheep people never have to worry about having the best in life for their abilities make money for them, and they are able to enjoy the creature comforts that they like. Sheep people are wise, gentle, and compassionate. They are compatible with Rabbits, Pigs, and Horses.";
$cdate_zodicdescrptionarray[9] = "People born in the Year of the Monkey are the erratic geniuses of the cycle. Clever, skillful, and flexible, they are remarkably inventive and original and can solve the most difficult problems with ease. There are few fields in which Monkey people wouldn't be successful but they have a disconcerting habit of being too agreeable. They want to do things now, and if they cannot get started immediately, they become discouraged and sometimes leave their projects. Although good at making decisions, they tend to look down on others. Having common sense, Monkey people have a deep desire for knowledge and have excellent memories. Monkey people are strong willed but their anger cools quickly. They are most compatible with the Dragon and Rat.";
$cdate_zodicdescrptionarray[10] = "People born in the Year of the Rooster are deep thinkers, capable, and talented. They like to be busy and are devoted beyond their capabilities and are deeply disappointed if they fail. People born in the Rooster Year are often a bit eccentric, and often have rather difficult relationship with others. They always think they are right and usually are! They frequently are loners and though they give the outward impression of being adventurous, they are timid. Rooster people's emotions like their fortunes, swing very high to very low. They can be selfish and too outspoken, but are always interesting and can be extremely brave. They are most compatible with Ox, Snake, and Dragon.";
$cdate_zodicdescrptionarray[11] = "People born in the Year of the Dog possess the best traits of human nature. They have a deep sense of loyalty, are honest, and inspire other people's confidence because they know how to keep secrets. But Dog People are somewhat selfish, terribly stubborn, and eccentric. They care little for wealth, yet somehow always seem to have money. They can be cold emotionally and sometimes distant at parties. They can find fault with many things and are noted for their sharp tongues. Dog people make good leaders. They are compatible with those born in the Years of the Horse, Tiger, and Rabbit.";
$cdate_zodicdescrptionarray[12] = "People born in the Year of the Pig are chivalrous and gallant. Whatever they do, they do with all their strength. For Boar Year people, there is no left or right and there is no retreat. They have tremendous fortitude and great honesty. They don't make many friends but they make them for life, and anyone having a Boar Year friend is fortunate for they are extremely loyal. They don't talk much but have a great thirst for knowledge. They study a great deal and are generally well informed. Boar people are quick tempered, yet they hate arguments and quarreling. They are kind to their loved ones. No matter how bad problems seem to be, Boar people try to work them out, honestly if sometimes impulsively. They are most compatible with Rabbits and Sheep. ";
         
$cdate_total=11;
$cdate_cntotal=0;

for ($y=1901;$y<$year;$y++){
      $cdate_total+=365;
      if ($y%4==0) $cdate_total ++;
}

switch ($month){
         case 12:
              $cdate_total+=30;
         case 11:
              $cdate_total+=31;
         case 10:
              $cdate_total+=30;
         case 9:
              $cdate_total+=31;
         case 8:
              $cdate_total+=31;
         case 7:
              $cdate_total+=30;
         case 6:
              $cdate_total+=31;
         case 5:
              $cdate_total+=30;
         case 4:
              $cdate_total+=31;
         case 3:
              $cdate_total+=28;              
         case 2:
              $cdate_total+=31;              
}  

if ($year%4==0 and $month>2){
     $cdate_total++;
}

$cdate_total = $cdate_total+($day-1);

$myeardiff = $year-1900;

for ($x=0;$x<=$myeardiff;$x++){
	for ($y=1;$y<=13;$y++){
		if ($cdate_cntotal<$cdate_total){
		$cdate_cntotal+=$cdate_monthdata[$x][$y];
		$cdate_cnyear = $x;
		$cdate_cnmonth = $y;
		}
	}
}

$cdate_zodiacnumber = $cdate_monthdata[$cdate_cnyear][15];
$cdate_zodiac = $cdate_zodiacarray[$cdate_zodiacnumber];
$cdate_zodicdescrption = $cdate_zodicdescrptionarray[$cdate_zodiacnumber];

$showtable = false;

}

?>

<html>
<head>
<title>Chinese zodiac animals</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="./style/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td class="table_top" align="center"> 
      <table width="770" border="0" cellspacing="0" cellpadding="0" height="30">
        <tr> 
          <td align="right"><a href="http://www.china-on-site.com/flexphpsite/"><img src="images/logo.gif" width="195" height="26" border="0"></a> 
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#CCCCCC" align="center" height="2"></td>
  </tr>
</table>

<table width="770" border="0" cellspacing="0" cellpadding="4" align="center">
<tr> 
    <td>&nbsp;</td>
</tr>
<tr> 
    <td bgcolor="#CCCCCC" align="center" height="2"></td>
</tr>
</table>
<table width="770" border="0" cellspacing="1" cellpadding="0" align="center" class="table_01">  
  <tr> 
    <td class="table_02" width="160" valign="top"> 
      <table width="160" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>      
      
    </td>
    <td class="menu" bgcolor="#FFFFFF" valign="top" width="410"> 
      <table width="410" border="0" cellspacing="0" cellpadding="4">
        <tr> 
          <td bgcolor="#F2F2F2" class="menu_in">::Chinese zodiac</td>
        </tr>       
        <tr> 
          <td><?php
if ($showtable){
	?>
<form action="<? print "$PHP_SELF"; ?>" method="POST">
<table border=0 cellpadding=2 cellspacing=2>
<tr>
<td align="right">Your birthday:</td>
</td>
<td>
<select name="year">
<option>2020</option>
  <option>2019</option>
  <option>2018</option>
  <option>2017</option>
  <option>2016</option>
  <option>2015</option>
  <option>2014</option>
  <option>2013</option>
  <option>2012</option>
  <option>2011</option>
  <option>2010</option>
  <option>2009</option>
  <option>2008</option>
  <option>2007</option>
  <option>2006</option>
  <option>2005</option>
  <option>2004</option>
  <option>2003</option>
  <option>2002</option>
  <option>2001</option>
  <option>2000</option>
  <option>1999</option>
  <option>1998</option>
  <option>1997</option>
  <option>1996</option>
  <option>1995</option>
  <option>1994</option>
  <option>1993</option>
  <option>1992</option>
  <option>1991</option>
  <option>1990</option>
  <option>1989</option>
  <option>1988</option>
  <option>1987</option>
  <option>1986</option>
  <option>1985</option>
  <option>1984</option>
  <option>1983</option>
  <option>1982</option>
  <option>1981</option>
  <option>1980</option>
  <option>1979</option>
  <option>1978</option>
  <option>1977</option>
  <option>1976</option>
  <option>1975</option>
  <option>1974</option>
  <option>1973</option>
  <option>1972</option>
  <option>1971</option>
  <option>1970</option>
  <option>1969</option>
  <option>1968</option>
  <option>1967</option>
  <option>1966</option>
  <option>1965</option>
  <option>1964</option>
  <option>1963</option>
  <option>1962</option>
  <option>1961</option>
  <option>1960</option>
  <option>1959</option>
  <option>1958</option>
  <option>1957</option>
  <option>1956</option>
  <option>1955</option>
  <option>1954</option>
  <option>1953</option>
  <option>1952</option>
  <option>1951</option>
  <option>1950</option>
  <option>1949</option>
  <option>1948</option>
  <option>1947</option>
  <option>1946</option>
  <option>1945</option>
  <option>1944</option>
  <option>1943</option>
  <option>1942</option>
  <option>1941</option>
  <option>1940</option>
  <option>1939</option>
  <option>1938</option>
  <option>1937</option>
  <option>1936</option>
  <option>1935</option>
  <option>1934</option>
  <option>1933</option>
  <option>1932</option>
  <option>1931</option>
  <option>1930</option>
  <option>1929</option>
  <option>1928</option>
  <option>1927</option>
  <option>1926</option>
  <option>1925</option>
  <option>1924</option>
  <option>1923</option>
  <option>1922</option>
  <option>1921</option>
  <option>1920</option>
  <option>1919</option>
  <option>1918</option>
  <option>1917</option>
  <option>1916</option>
  <option>1915</option>
  <option>1914</option>
  <option>1913</option>
  <option>1912</option>
  <option>1911</option>
  <option>1910</option>
  <option>1909</option>
  <option>1908</option>
  <option>1907</option>
  <option>1906</option>
  <option>1905</option>
  <option>1904</option>
  <option>1903</option>
  <option>1902</option>
  <option>1901</option>
</select>
<select name="month">
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
  <option>11</option>
  <option>12</option>  
</select>
<select name="day">
  <option>1</option>
  <option>2</option>
  <option>3</option>
  <option>4</option>
  <option>5</option>
  <option>6</option>
  <option>7</option>
  <option>8</option>
  <option>9</option>
  <option>10</option>
  <option>11</option>
  <option>12</option>
  <option>13</option> 
  <option>14</option> 
  <option>15</option>
  <option>16</option> 
  <option>17</option>
  <option>18</option>
  <option>19</option>
  <option>20</option>
  <option>21</option>
  <option>22</option>
  <option>23</option>
  <option>24</option>
  <option>25</option>
  <option>26</option>
  <option>27</option>
  <option>28</option>
  <option>29</option>
  <option>30</option>
  <option>31</option>
</select>
</td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="birthdaysubmit" value="OK"></td>
</tr>
</table>
</form>
<?php
}
else{
?>
<table border=0 cellpadding=2 cellspacing=2>
<tr>
<td align="center">
<p>Your Chinese zodiac is <? print "$cdate_zodiac"; ?></p>
<img src="images/czodiac<? print "$cdate_zodiacnumber"; ?>.gif"><br>
<? print "$cdate_zodicdescrption"; ?><br>
</td>
</tr>
</table>
<?php
}
?>
</td>
        </tr>
        <tr>
          <td align="right">&nbsp; </td>
        </tr>
      </table>
                     
    </td>
    <td class="table_02" background="./images/right_bg.gif" valign="top">       
      <table width="200" border="0" cellspacing="0" cellpadding="6">
        <tr> 
          <td>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="770" border="0" cellspacing="0" cellpadding="0" align="center" height="40">
  <tr> 
    <td class="en_g"><p>Copyright &copy; 2000-2002 China-on-site.com All rights 
        reserved.<br>
        Optimized for IE 5.5 @ 1024x768</p>
      <p align="center">Powered by <a href="http://www.china-on-site.com/flexphpsite/">FlexPHPSite</a></p>
      <p align="center">&nbsp;</p></td>
  </tr>
</table>
</body>
</html>