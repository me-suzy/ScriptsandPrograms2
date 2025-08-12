<?

// this pregnancy script was written by michelle of http://usr-bin-mom.com
// feel free to use it on your site, and don't hesitate to link back to me!

// some of the information used to write this script was gleaned from: 
// http://scriptygoddess.com/
// http://php.net/
// http://pregnancy.about.com/
// http://womens-place.com/

// disclaimer: this script is JUST FOR FUN!  it's a toy.  if you read something here, 
// and your doctor or midwife tells you something different, believe them!  
// they are trained professionals, and i am just a geeky homeschool mom with some time on my hands.

// almost everything this script does is based on the date of your last period.  
// it is based on a 28 day cycle.  your cycle may be longer or shorter, so if the results seem wrong to you, 
// fiddle with that date until you are satisfied. 

// ----- these are the values that you need to edit -----

$today = strtotime ("today");		// don't touch this one
$sixweeks = $today - 12280223;		// don't touch this one

$period  = "May 25, 2003";	// fill in the date of the first day of your last period.  use the mm/dd/yyyy format.  for European dates, change $due in line 500 to $dueEuro
$duedate = "void";			// if you know your due date, fill it in here.  if you leave this as void, the script will automatically guess a due date for you.

$gender = "neutral";			// neutral, boy, girl, or twins.  if you are having twins, fill in "twins" here.  otherwise leave as "neutral" until the gender is determined.  then fill in either "boy" or "girl".
$twingender = "void";			// if you are having twins, fill in the genders here.  for example, "a boy and a girl" or "two boys".  otherwise, leave as "void".

$name = "void";				// if you are having one baby, fill in the name here (when you decide).  for example, "Seamus Riley".  for twins, leave this spot as "void".
$twinname = "void";			// if you are having twins, fill in both names here (when you decide).  for example, "Aryanna Elizabeth and Aoghdan Alan".  for one baby, leave this spot as "void".

$iorwe = "we";				// choose whether you want the announcements to say "I" or "We" (as in "We are having a baby!" or "I am having a baby!").

$link = "yes";				// if you want to use the default link back to me, leave this as "yes".  if you change it to "no" i'd still appreciate a link on your site somewhere!

// ----- edit these values after the baby is born -----

$born = "no";				// when the baby is born, change this to "yes" to activate the birth announcement.
$weight = "8 pounds, 14 ounces";	// fill in the weight, including "pounds" or "kilos".
$birthdate = $duedate;			// if the baby was not born on his or her due date, fill in the actual birthdate here.
$birthtime = "4:10 p.m.";		// fill in the time(s) of birth (format like "2:10 and 3:01" for twins).
$finalgender = $gender;			// if your single baby turned out to be a different gender than anticipated, fill it in here.
$finaltwingender = $twingender;		// if your twins turned out to be different genders than anticipated, fill them in here.
$finalname = $name;			// if you changed your mind about your single baby's name, fill the final decision in here.
$finaltwinname = $twinname;		// if you changed your mind about your twins' names, fill the final decisions in here.

// ----- end of the values that you need to edit -----

if ($born == "no")
	{

		// this customizes the script to use I or we:
		
		if ($iorwe == "we")
			{
				$we = "We";
				$were = "We're";
			}
		else
			{
				$we = "I";
				$were = "I'm";
			}
			
		// this customizes the script to your baby's gender/number:
		
		if ($gender == "boy")
			{
				$he = "he";
				$heu = "He";
				$him = "him";
				$himu = "Him";
				$his = "his";
				$hisu = "His";
				$baby = "baby";
				$babyu = "Baby";
				$is = "is";
				$was = "was";
				$has = "has";
				$babys = "baby's";
				$s = "";
				$a = "a";
				$announce = "having a boy";
			}
		elseif ($gender == "girl")
			{
				$he = "she";
				$heu = "She";
				$him = "her";
				$himu = "Her";
				$his = "her";
				$hisu = "Her";
				$baby = "baby";
				$babyu = "Baby";
				$is = "is";
				$was = "was";	
				$has = "has";
				$babys = "baby's";
				$s = "";
				$a = "a";
				$announce = "having a girl";
			}
		elseif ($gender == "twins")
			{
				$he = "they";
				$heu = "They";
				$him = "them";
				$himu = "Them";
				$his = "their";
				$hisu = "Their";
				$baby = "babies";
				$babyu = "Babies";
				$is = "are";
				$was = "were";
				$has = "have";
				$babys = "babies'";
				$s = "s";
				$a = "";
				$announce = "having twins";
			}
		else
			{
				$he = "baby";
				$heu = "Baby";
				$him = "the baby";
				$himu = "The baby";
				$his = "baby's";
				$hisu = "Baby's";
				$baby = "baby";
				$babyu = "Baby";
				$is = "is";
				$was = "was";
				$has = "has";
				$babys = "baby's";
				$s = "";
				$a = "a";
				$announce = "having a baby";
			}
			
		// this announces that you are having a baby:
		
		if ($twingender == "void")
			{
				print"$were $announce!  ";
			}
		else
			{
				print"$were $announce, $twingender!  ";
			}
				
		// this announces the name of your baby:
		
		if ($name == "void")
			{
				print"";
			}
		else
			{
				print"$we have chosen the name $name.";
			}
		
		if ($twinname == "void")
			{
				print"";
			}
		else
			{
				print"$we have chosen the names $twinname.";
			}
		
		// a line break:
		
		print"<br><br>";
		
		// this calculates how many weeks pregnant you are:
		
		$now = strtotime ("now");
		$then = strtotime ("$period");
		$difference = $now - $then ;
		$num = ($difference/86400)/7;
		$weeks = intval($num);
		
		// this determines your due date:
		
		if ($duedate == "void")
			{
				$start3 = strtotime ("$period");
				$difference3 = "24256045 ";
				$then3 = $start3 + $difference3;
				$due  = date("F d, Y",$then3);
				$dueEuro  = date("d F, Y",$then3);
			}
		else
			{
				$due = $duedate;
			}
		
		// this calculates how many days until your due date:
		
		$then2 = strtotime ("$due");
		$difference2 = $then2 - $now;
		$num2 = $difference2/86400;
		$days = intval($num2);
		
		// this figures the state of your baby's development:
		
		if ($weeks == "2")
		{
		$dev = "My $baby $was conceived this week!  $heu $is busy dividing and growing at an amazing rate!";
		}
		elseif ($weeks == "3")
		{
		$dev = "My $baby $is now implanted in my uterus.  A yolk sac is developed to feed $him until the placenta is completed.";
		}
		elseif ($weeks == "4")
		{
		$dev = "It is now possible for my pregnancy to show up on blood tests, and even HPTs.  My $baby $has $a heartbeat$s!  Brain$s and spine$s are forming, and you can distinguish $his head$s from the body!";
		}
		elseif ($weeks == "5")
		{
		$dev = "My $baby now $has $a ciculatory system$s.  $heu $is beginning to sprout buds where the arms and legs will be.";
		}
		elseif ($weeks == "6")
		{
			if ($gender == "twins")
				{
					$dev = "Those little limb buds are looking a lot like arms and legs now!  Indentations are forming where $his fingers and toes will be.";
				}
			else
				{
					$dev = "Those little limb buds are looking a lot like arms and legs now!  Indentations are forming where $his fingers and toes will be.  My $baby $is now about 1/2 an inch long.";
				}
		
		}
		elseif ($weeks == "7")
		{
		$dev = "This week my $baby will develop either testes or ovaries, becoming $a boy$s or $a girl$s.  My $baby can also move around now, even though I can't feel it yet!";
		}
		elseif ($weeks == "8")
		{
			if ($gender == "twins")
				{
					$dev = "My $babys elbows are present, and $his fingers, toes, and face are all becoming more recognizable.";
				}
			else
				{
					$dev = "$hisu weight is now about 1 gram, or as much as a grape!  Elbows are present, and $his fingers, toes, and face are all becoming more recognizable.";
				}
		
		}
		elseif ($weeks == "9")
		{
			if ($gender == "twins")
				{
					$dev = "Ankles and wrists are completely formed this week.  Wide open eyes begin to fuse shut and won't open again until 25 - 27 weeks.  External genitalia$s $is differentiating, fingers, toes and ears are perfect, the placenta is beginning to produce progesterone.";
				}
			else
				{
					$dev = "Ankles and wrists are completely formed this week.  Wide open eyes begin to fuse shut and won't open again until 25 - 27 weeks.  External genitalia$s $is differentiating, fingers, toes and ears are perfect, the placenta is beginning to produce progesterone, and my $baby $is 1 3/4 of an inch long.";
				}
		
		}
		elseif ($weeks == "10")
		{
			if ($gender == "twins")
				{
					$dev = "All of my $babys organs are formed.  The irises are starting to develop, as are fingernails.";
				}
			else
				{
					$dev = "At about 7 grams, all of $his organs are formed.  The irises are starting to develop, as are fingernails.";
				}
		}
		elseif ($weeks == "11")
		{
			if ($gender == "twins")
				{
					$dev = "My $babys head$s $is becoming more rounded, and $he $has eyelids.  $hisu muscles are developing, so $he can move around a lot more.";
				}
			else
				{
					$dev = "My $babys head$s $is becoming more rounded, and $he $has eyelids.  $hisu muscles are developing, so $he can move around a lot more.  Right now my $baby $is about 2 1/2 inches and 1/2 an ounce.";
				}
		}
		elseif ($weeks == "12")
		{
		$dev = "My $baby $is now $a fully formed human being$s.  All that's left is to grow big enough to survive outside of my belly!";
		}
		elseif ($weeks == "13")
		{
			if ($gender == "twins")
				{
					$dev = "Hair is appearing, including eyebrows!  My $babys heart$s can be heard on the ultrasound, now.  $we can't wait to hear that!  All of my $babys nutrition is coming from the placenta now, and $he can even drink the amniotic fluid and urinate.";
				}
			else
				{
					$dev = "Hair is appearing, including eyebrows!  My $babys heart$s can be heard on the ultrasound, now.  $we can't wait to hear that!  All of my $babys nutrition is coming from the placenta now, and $he can even drink the amniotic fluid and urinate.  $heu $is now 3 3/4 inches long.";
				}
		}
		elseif ($weeks == "14")
		{
		$dev = "My $babys hair is getting thicker now.  $heu might even be developing dark pigment if $his hair is going to be dark.";
		}
		elseif ($weeks == "15")
		{
			if ($gender == "twins")
				{
					$dev = "Fingernails and toenails are now formed, and my $baby $is growing fine down all over.";
				}
			else
				{
					$dev = "Fingernails and toenails are now formed, and my $baby $is growing fine down all over.  $heu $is now 6 3/4 inches long and 5 whole ounces!";
				}
		}
		elseif ($weeks == "16")
		{
		$dev = "From now on my $baby will weigh more than the placenta.  $heu might be starting to become aware of sounds outside my body.  I wonder if $he can hear my voice when I sing to $him?";
		}
		elseif ($weeks == "17")
		{
			if ($gender == "twins")
				{
					$dev = "My $baby $is now learning about $his reflexes.  $heu $is kicking, grasping, and even sucking.  Some babies even learn to suck their thumbs before they are born!  I wonder if $he will?";
				}
			else
				{
					$dev = "My $baby $is now learning about $his reflexes.  $heu $is kicking, grasping, and even sucking.  Some babies even learn to suck their thumbs before they are born!  I wonder if $he will?  My $baby $is now about 8 inches long.";
				}
		}
		elseif ($weeks == "18")
		{
		$dev = "Buds for tiny little baby teeth have already formed, and now buds for permanent teeth are forming behind those.";
		}
		elseif ($weeks == "19")
		{
			if ($gender == "twins")
				{
					$dev = "My $baby $is forming a protective layer over $his body-covering down.";
				}
			else
				{
					$dev = "My $baby $is now about 10 inches long, and $a protective layer$s $is forming and clinging to $his body-covering down.";
				}
		}
		elseif ($weeks == "20")
		{
			if ($gender == "twins")
				{
					$dev = "My $baby $has plenty of room to move around.  Sometimes I can feel $him kicking now!";
				}
			else
				{
					$dev = "At less than 1 pound, my $baby $has plenty of room to move around.  Sometimes I can feel $him kicking now!";
				}
		
		}
		elseif ($weeks == "21")
		{
		$dev = "My $baby $is starting to settle into a pattern.  Sometimes $he will sleep, and other times $he will play.  It always seems that $he $is most active when I am resting!";
		}
		elseif ($weeks == "22")
		{
		$dev = "My $baby may now be feeling Braxton Hicks contractions, gripping and massaging $him.";
		}
		elseif ($weeks == "23")
		{
			if ($gender == "twins")
				{
					$dev = "Most of $his organs are mature by now, but $his lungs are not ready for the outside air, yet.";
				}
			else
				{
					$dev = "What a big baby!  $heu $is now almost 13 inches and more than 1 1/4 pounds!  Most of $his organs are mature by now, but $his lungs are not ready for the outside air, yet.";
				}
		}
		elseif ($weeks == "24")
		{
		$dev = "The centers of my $babys bones are beginning to harden.";
		}
		elseif ($weeks == "25")
		{
		$dev = "My $babys skin has been very thin and transparent so far, but now it is becoming opaque.";
		}
		elseif ($weeks == "26")
		{
		$dev = "My $babys skin is wrinkled right now, but $he still has that protective layer covering $him.";
		}
		elseif ($weeks == "27")
		{
			if ($gender == "twins")
				{
					$dev = "This week marks the point where my $baby $is legally viable, though some hospitals can care for babies even younger.";
				}
			else
				{
					$dev = "At 14 inches and around 2 pounds, this week marks the point where my $baby $is legally viable, though some hospitals can care for babies even younger.";
				}
		}
		elseif ($weeks == "28")
		{
		$dev = "By now my $babys head$s $is more or less in proportion with the rest of $his body, though still proportionally larger than an adult's head would be.";
		}
		elseif ($weeks == "29")
		{
		$dev = "My $baby $is probably noticing those Braxton Hicks contractions at regular intervals now, even when I don't.";
		}
		elseif ($weeks == "30")
		{
			if ($gender == "twins")
				{
					$dev = "Though I may be starting to feel breathless when I exert myself, the $baby $is getting plenty of oxygen.";
				}
			else
				{
					$dev = "Though I may be starting to feel breathless when I exert myself, the $baby $is getting plenty of oxygen.  $heu $is now nearly 4 pounds.";
				}
		}
		elseif ($weeks == "31")
		{
			if ($gender == "twins")
				{
					$dev = "Fat reserves are being laid down under my $babys skin.  Now $he $is very much like a normal baby.";
				}
			else
				{
					$dev = "Fat reserves are being laid down under my $babys skin.  At 16 inches, $he $is very much like a normal baby.";
				}
		}
		elseif ($weeks == "32")
		{
		$dev = "If $he $has not already, my $baby will soon turn head$s down in preparation for birth.";
		}
		elseif ($weeks == "33")
		{
		$dev = "My $baby can now tell the difference between dark and light.  When I am out in the sun $he $is bathed in a red glow.  $hisu skin is becoming nice and pink.";
		}
		elseif ($weeks == "34")
		{
			if ($gender == "twins")
				{
					$dev = "35% of twins are born by this point.";
				}
			else
				{
					$dev = "My $baby $is around 18 inches and 5 1/2 pounds.";
				}
		}
		elseif ($weeks == "35")
		{
			if ($gender == "twins")
				{
					$dev = "My $baby $is almost ready to be born.  Any time now $he may drop into my pelvis in preparation for labor.";
				}
			else
				{
					$dev = "At more than 18 inches, my $baby $is almost ready to be born.  Any time now $he may drop into my pelvis in preparation for labor.";
				}
		}
		elseif ($weeks == "36")
		{
		$dev = "When I am very still I can sometimes see the rythmic patterns of my $baby practicing breathing, though there is no air in $his lungs, yet.  Sometimes this breathing practice gives $him the hiccups!";
		}
		elseif ($weeks == "37")
		{
		$dev = "My $baby might be gaining as much as an ounce of weight every day now!  I wonder how big $he will be?";
		}
		elseif ($weeks == "38")
		{
		$dev = "My $babys amniotic fluid is now replaced every three hours.";
		}
		elseif ($weeks == "39")
		{
			if ($gender == "twins")
				{
					$dev = "My $baby $is getting ready to embark on the first big adventure of $his life.  $we can't wait!";
				}
			else
				{
					$dev = "My $baby may be as long as 20 inches now, maybe more!  $heu $is getting ready to embark on the first big adventure of $his life.  $we can't wait!";
				}
		}
		elseif ($weeks == "40")
		{
			if ($gender == "twins")
				{
					$dev = "Welcome to the world, $twinname!  ($we hope!)";
				}
			else
				{
					$dev = "Welcome to the world, $name!  ($we hope!)";
				}
		}
		else
		{}
		
		// this prints your information:
		
		print"I am $weeks weeks pregnant.  There are $days days until my due date on $due.<br><br>$dev";
		
	}
else
	{
		if ($finalgender == "twins")
			{
				print"$finaltwinname, $finaltwingender, were born on $birthdate at $birthtime.<br><br>They were $weight, respectively.<br><br>Welcome to the world!";
			}
		elseif ($finalgender == "boy")
			{
				print"$finalname was born on $birthdate at $birthtime.<br><br>He was $weight.<br><br>Welcome to the world!";
			}
		elseif ($finalgender == "girl")
			{
				print"$finalname was born on $birthdate at $birthtime.<br><br>She was $weight.<br><br>Welcome to the world!";
			}
		else
			{}
	}

// this links back to my site so that others can download the pregnancy script.
	
if ($link == "yes")
	{
		print"<br><br>Get your own <a href=\"http://www.usr-bin-mom.com/journal.php?id=402\" target=\"_blank\">pregnancy script</a>!";
	}
else
	{
		print"";
	}		
?>
