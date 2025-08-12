<? include "tpl/top.ihtml" ?>
<?
   if($action == "submit"){
     require "conf/sys.conf";
     require "lib/mysql.lib";
     require "lib/mail.lib";
     require "lib/bann.lib";

     $db = c();


     if($login == "") $es = "Enter username!";
     else{
       if(!e(q("select id from members where login='$login'"))) $es = "$login username already exists in our database, please, choose another!";
     }

     if($pswd_1 == "" || $pswd_2 == "" || $pswd_1 != $pswd_2)
	$es = "Password and its confirmation must be identy!";
    if($email == "") $es = "E-mail required!";

     if($es == ""){
 	// there sould be mail sending ..
	$dt1=strtotime(date("d M Y H:i:s"));

	q("insert into members values('0','$login','$pswd_1','$fname','$lname','$email','$city','$state','$country','$zip','$phone','$fax','','0','$dt1')");
    $link = $ROOT_HOST."confirm.php?mid=$login&stamp=$dt1".($affid != ""?"&affid=$affid":"");
	$pswd = $pswd_1;

	MsgFromTpl($email,"Registration Details !","tpl/register.mtl");
	echo "<center><font face=verdana size=2>Thank you for registering in our system!<br>Soon you will get confirmation e-mail.</font></center>";
     }
     d($db);
   }

  if($action != "submit" || $es != "" || ($action == "" && $es == "")){
?>

<font color=555555 face=verdana size=4><b>&nbsp; Registration &gt;</b></font>
<p>
  <table width="500" border=0 align=center>
    <form action="register.php?action=submit" method=post>
      <?
if($es != "")
  echo"<tr>
         <td colspan=2><font color=C00000><font face=verdana size=2><b>Error: $es</td>
       </tr>";
?>
      <tr> 
        <td colspan="2"><p><font face=verdana size=2><strong>Account details</strong></p></td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Username *</td>
        <td> <input type="text" name="login">
          <font size="1" face=verdana> (i.e. webexpert) </font></td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Password *</td>
        <td> <input type="password" name="pswd_1">
          <font size="1" face=verdana>(i.e. adrenalin ) </font></td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Confirm password *</td>
        <td> <input type="password" name="pswd_2">
          <font size="1" face=verdana> (the same as password) </font></td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>E-mail address *</td>
        <td> <input type="text" name="email">
          <font size="1" face=verdana>(i.e. webm2005@yahoo.com)</font> </td>
      </tr>
      <tr> 
        <td colspan="2"><p>&nbsp;</p>
          <p><font face=verdana size=2><strong>Owner Details</strong></p></td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>First name</td>
        <td> <input type="text" name="fname"> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Last name</td>
        <td> <input type="text" name="lname"> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>City</td>
        <td> <input type="text" name="city"> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>State/County</td>
        <td> <select name="state">
            <option>Select One</option>
            <option value="International">International</option>
            <option value='Alabama'>Alabama</option>
            <option value='Alaska'>Alaska</option>
            <option value='Arizona'>Arizona</option>
            <option value='Arkansas'>Arkansas</option>
            <option value='California'>California</option>
            <option value='Colorado'>Colorado</option>
            <option value='Connecticut'>Connecticut</option>
            <option value='Washington D.C.'>Washington D.C.</option>
            <option value='Delaware'>Delaware</option>
            <option value='Florida'>Florida</option>
            <option value='Georgia'>Georgia</option>
            <option value='Hawaii'>Hawaii</option>
            <option value='Idaho'>Idaho</option>
            <option value='Illinois'>Illinois</option>
            <option value='Indiana'>Indiana</option>
            <option value='Iowa'>Iowa</option>
            <option value='Kansas'>Kansas</option>
            <option value='Kentucky'>Kentucky</option>
            <option value='Louisiana'>Louisiana</option>
            <option value='Maine'>Maine</option>
            <option value='Maryland'>Maryland</option>
            <option value='Massachusetts'>Massachusetts</option>
            <option value='Michigan'>Michigan</option>
            <option value='Minnesota'>Minnesota</option>
            <option value='Mississippi'>Mississippi</option>
            <option value='Missouri'>Missouri</option>
            <option value='Montana'>Montana</option>
            <option value='Nebraska'>Nebraska</option>
            <option value='Nevada'>Nevada</option>
            <option value='New Hampshire'>New Hampshire</option>
            <option value='New Jersey'>New Jersey</option>
            <option value='New Mexico'>New Mexico</option>
            <option value='New York'>New York</option>
            <option value='North Carolina'>North Carolina</option>
            <option value='North Dakota'>North Dakota</option>
            <option value='Ohio'>Ohio</option>
            <option value='Oklahoma'>Oklahoma</option>
            <option value='Oregon'>Oregon</option>
            <option value='Pennsylvania'>Pennsylvania</option>
            <option value='Puerto Rico'>Puerto Rico</option>
            <option value='Rhode Island'>Rhode Island</option>
            <option value='South Carolina'>South Carolina</option>
            <option value='South Dakota'>South Dakota</option>
            <option value='Tennessee'>Tennessee</option>
            <option value='Texas'>Texas</option>
            <option value='Utah'>Utah</option>
            <option value='Vermont'>Vermont</option>
            <option value='Virginia'>Virginia</option>
            <option value='Washington'>Washington</option>
            <option value='West Virginia'>West Virginia</option>
            <option value='Wisconsin'>Wisconsin</option>
            <option value='Wyoming'>Wyoming</option>
          </select> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Country</td>
        <td> <select name="country">
            <option>Select One</option>
            <option value='United States of America'>United States of America</option>
            <option value='Afghanistan'>Afghanistan</option>
            <option value='Albania'>Albania</option>
            <option value='Algeria'>Algeria</option>
            <option value='American Samoa'>American Samoa</option>
            <option value='Andorra'>Andorra</option>
            <option value='Angola'>Angola</option>
            <option value='Anguilla'>Anguilla</option>
            <option value='Antarctica'>Antarctica</option>
            <option value='Antigua and Barbuda'>Antigua and Barbuda</option>
            <option value='Argentina'>Argentina</option>
            <option value='Armenia'>Armenia</option>
            <option value='Aruba'>Aruba</option>
            <option value='Australia'>Australia</option>
            <option value='Austria'>Austria</option>
            <option value='Azerbaijan'>Azerbaijan</option>
            <option value='Bahamas'>Bahamas</option>
            <option value='Bahrain'>Bahrain</option>
            <option value='Bangladesh'>Bangladesh</option>
            <option value='Barbados'>Barbados</option>
            <option value='Belarus'>Belarus</option>
            <option value='Belgium'>Belgium</option>
            <option value='Belize'>Belize</option>
            <option value='Benin'>Benin</option>
            <option value='Bermuda'>Bermuda</option>
            <option value='Bhutan'>Bhutan</option>
            <option value='Bolivia'>Bolivia</option>
            <option value='Bosnia and Herzegowina'>Bosnia and Herzegowina</option>
            <option value='Botswana'>Botswana</option>
            <option value='Bouvet Island'>Bouvet Island</option>
            <option value='Brazil'>Brazil</option>
            <option value='Brunei Darussalam'>Brunei Darussalam</option>
            <option value='Bulgaria'>Bulgaria</option>
            <option value='Burkina Faso'>Burkina Faso</option>
            <option value='Burundi'>Burundi</option>
            <option value='Cambodia'>Cambodia</option>
            <option value='Cameroon'>Cameroon</option>
            <option value='Canada'>Canada</option>
            <option value='Cape Verde'>Cape Verde</option>
            <option value='Cayman Islands'>Cayman Islands</option>
            <option value='Central African Republic'>Central African Republic</option>
            <option value='Chad'>Chad</option>
            <option value='Chile'>Chile</option>
            <option value='China'>China</option>
            <option value='Christmas Island'>Christmas Island</option>
            <option value='Cocos (Keeling) Islands'>Cocos (Keeling) Islands</option>
            <option value='Colombia'>Colombia</option>
            <option value='Comoros'>Comoros</option>
            <option value='Congo'>Congo</option>
            <option value='Cook Islands'>Cook Islands</option>
            <option value='Costa Rica'>Costa Rica</option>
            <option value='Cote D'Ivoire'>Cote D'Ivoire</option>
            <option value='Croatia'>Croatia</option>
            <option value='Cuba'>Cuba</option>
            <option value='Cyprus'>Cyprus</option>
            <option value='Czech Republic'>Czech Republic</option>
            <option value='Denmark'>Denmark</option>
            <option value='Djibouti'>Djibouti</option>
            <option value='Dominica'>Dominica</option>
            <option value='Dominican Republic'>Dominican Republic</option>
            <option value='East Timor'>East Timor</option>
            <option value='Ecuador'>Ecuador</option>
            <option value='Egypt'>Egypt</option>
            <option value='El Salvador'>El Salvador</option>
            <option value='Equatorial Guinea'>Equatorial Guinea</option>
            <option value='Eritrea'>Eritrea</option>
            <option value='Estonia'>Estonia</option>
            <option value='Ethiopia'>Ethiopia</option>
            <option value='Falkland Islands'>Falkland Islands</option>
            <option value='Faroe Islands'>Faroe Islands</option>
            <option value='Fiji'>Fiji</option>
            <option value='Finland'>Finland</option>
            <option value='France'>France</option>
            <option value='France, Metropolitan'>France, Metropolitan</option>
            <option value='French Guiana'>French Guiana</option>
            <option value='French Polynesia'>French Polynesia</option>
            <option value='Gabon'>Gabon</option>
            <option value='Gambia'>Gambia</option>
            <option value='Georgia'>Georgia</option>
            <option value='Germany'>Germany</option>
            <option value='Ghana'>Ghana</option>
            <option value='Gibraltar'>Gibraltar</option>
            <option value='Greece'>Greece</option>
            <option value='Greenland'>Greenland</option>
            <option value='Grenada'>Grenada</option>
            <option value='Guadeloupe'>Guadeloupe</option>
            <option value='Guam'>Guam</option>
            <option value='Guatemala'>Guatemala</option>
            <option value='Guinea'>Guinea</option>
            <option value='Guinea-Bissau'>Guinea-Bissau</option>
            <option value='Guyana'>Guyana</option>
            <option value='Haiti'>Haiti</option>
            <option value='Honduras'>Honduras</option>
            <option value='Hong Kong SAR, PRC'>Hong Kong SAR, PRC</option>
            <option value='Hungary'>Hungary</option>
            <option value='Iceland'>Iceland</option>
            <option value='India'>India</option>
            <option value='Indonesia'>Indonesia</option>
            <option value='Iran'>Iran</option>
            <option value='Iraq'>Iraq</option>
            <option value='Ireland'>Ireland</option>
            <option value='Israel'>Israel</option>
            <option value='Italy'>Italy</option>
            <option value='Jamaica'>Jamaica</option>
            <option value='Japan'>Japan</option>
            <option value='Jordan'>Jordan</option>
            <option value='Kazakhstan'>Kazakhstan</option>
            <option value='Kenya'>Kenya</option>
            <option value='Kiribati'>Kiribati</option>
            <option value='D.P.R. Korea'>D.P.R. Korea</option>
            <option value='Korea'>Korea</option>
            <option value='Kuwait'>Kuwait</option>
            <option value='Kyrgyzstan'>Kyrgyzstan</option>
            <option value='Lao People's Republic'>Lao People's Republic</option>
            <option value='Latvia'>Latvia</option>
            <option value='Lebanon'>Lebanon</option>
            <option value='Lesotho'>Lesotho</option>
            <option value='Liberia'>Liberia</option>
            <option value='Libyan Arab Jamahiriya'>Libyan Arab Jamahiriya</option>
            <option value='Liechtenstein'>Liechtenstein</option>
            <option value='Lithuania'>Lithuania</option>
            <option value='Luxembourg'>Luxembourg</option>
            <option value='Macau'>Macau</option>
            <option value='Macedonia'>Macedonia</option>
            <option value='Madagascar'>Madagascar</option>
            <option value='Malawi'>Malawi</option>
            <option value='Malaysia'>Malaysia</option>
            <option value='Maldives'>Maldives</option>
            <option value='Mali'>Mali</option>
            <option value='Malta'>Malta</option>
            <option value='Marshall Islands'>Marshall Islands</option>
            <option value='Martinique'>Martinique</option>
            <option value='Mauritania'>Mauritania</option>
            <option value='Mauritius'>Mauritius</option>
            <option value='Mayotte'>Mayotte</option>
            <option value='Mexico'>Mexico</option>
            <option value='Micronesia'>Micronesia</option>
            <option value='Moldova'>Moldova</option>
            <option value='Monaco'>Monaco</option>
            <option value='Mongolia'>Mongolia</option>
            <option value='Montserrat'>Montserrat</option>
            <option value='Morocco'>Morocco</option>
            <option value='Mozambique'>Mozambique</option>
            <option value='Myanmar'>Myanmar</option>
            <option value='Namibia'>Namibia</option>
            <option value='Nauru'>Nauru</option>
            <option value='Nepal'>Nepal</option>
            <option value='Netherlands'>Netherlands</option>
            <option value='Netherlands Antilles'>Netherlands Antilles</option>
            <option value='New Caledonia'>New Caledonia</option>
            <option value='New Zealand'>New Zealand</option>
            <option value='Nicaragua'>Nicaragua</option>
            <option value='Niger'>Niger</option>
            <option value='Nigeria'>Nigeria</option>
            <option value='Niue'>Niue</option>
            <option value='Norfolk Island'>Norfolk Island</option>
            <option value='Northern Mariana Islands'>Northern Mariana Islands</option>
            <option value='Norway'>Norway</option>
            <option value='Oman'>Oman</option>
            <option value='Pakistan'>Pakistan</option>
            <option value='Palau'>Palau</option>
            <option value='Panama'>Panama</option>
            <option value='Papua New Guinea'>Papua New Guinea</option>
            <option value='Paraguay'>Paraguay</option>
            <option value='Peru'>Peru</option>
            <option value='Philippines'>Philippines</option>
            <option value='Pitcairn'>Pitcairn</option>
            <option value='Poland'>Poland</option>
            <option value='Portugal'>Portugal</option>
            <option value='Puerto Rico'>Puerto Rico</option>
            <option value='Qatar'>Qatar</option>
            <option value='Reunion'>Reunion</option>
            <option value='Romania'>Romania</option>
            <option value='Russian Federation'>Russian Federation</option>
            <option value='Rwanda'>Rwanda</option>
            <option value='Saint Kitts and Nevis'>Saint Kitts and Nevis</option>
            <option value='Saint Lucia'>Saint Lucia</option>
            <option value='Samoa'>Samoa</option>
            <option value='San Marino'>San Marino</option>
            <option value='Sao Tome and Principe'>Sao Tome and Principe</option>
            <option value='Saudi Arabia'>Saudi Arabia</option>
            <option value='Senegal'>Senegal</option>
            <option value='Seychelles'>Seychelles</option>
            <option value='Sierra Leone'>Sierra Leone</option>
            <option value='Singapore'>Singapore</option>
            <option value='Slovakia'>Slovakia</option>
            <option value='Slovenia'>Slovenia</option>
            <option value='Solomon Islands'>Solomon Islands</option>
            <option value='Somalia'>Somalia</option>
            <option value='South Africa'>South Africa</option>
            <option value='Spain'>Spain</option>
            <option value='Sri Lanka'>Sri Lanka</option>
            <option value='St Helena'>St Helena</option>
            <option value='St Pierre and Miquelon'>St Pierre and Miquelon</option>
            <option value='Sudan'>Sudan</option>
            <option value='Suriname'>Suriname</option>
            <option value='Swaziland'>Swaziland</option>
            <option value='Sweden'>Sweden</option>
            <option value='Switzerland'>Switzerland</option>
            <option value='Syrian Arab Republic'>Syrian Arab Republic</option>
            <option value='Taiwan Region'>Taiwan Region</option>
            <option value='Tajikistan'>Tajikistan</option>
            <option value='Tanzania'>Tanzania</option>
            <option value='Thailand'>Thailand</option>
            <option value='Togo'>Togo</option>
            <option value='Tokelau'>Tokelau</option>
            <option value='Tonga'>Tonga</option>
            <option value='Trinidad and Tobago'>Trinidad and Tobago</option>
            <option value='Tunisia'>Tunisia</option>
            <option value='Turkey'>Turkey</option>
            <option value='Turkmenistan'>Turkmenistan</option>
            <option value='Turks and Caicos Islands'>Turks and Caicos Islands</option>
            <option value='Tuvalu'>Tuvalu</option>
            <option value='Uganda'>Uganda</option>
            <option value='Ukraine'>Ukraine</option>
            <option value='United Arab Emirates'>United Arab Emirates</option>
            <option value='United Kingdom'>United Kingdom</option>
            <option value='Uruguay'>Uruguay</option>
            <option value='Uzbekistan'>Uzbekistan</option>
            <option value='Vanuatu'>Vanuatu</option>
            <option value='Vatican City State'>Vatican City State</option>
            <option value='Venezuela'>Venezuela</option>
            <option value='Viet Nam'>Viet Nam</option>
            <option value='Virgin Islands (British)'>Virgin Islands (British)</option>
            <option value='Virgin Islands (US)'>Virgin Islands (US)</option>
            <option value='Wallis and Futuna Islands'>Wallis and Futuna Islands</option>
            <option value='Yugoslavia'>Yugoslavia</option>
            <option value='Yemen'>Yemen</option>
            <option value='Zaire'>Zaire</option>
            <option value='Zambia'>Zambia</option>
            <option value='Zimbabwe'>Zimbabwe</option>
            <option value='Other-Not Shown'>Other-Not Shown</option>
          </select> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Postal/ZIP Code</td>
        <td> <input type="text" name="zip"> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Phone</td>
        <td> <input type="text" name="phone"> </td>
      </tr>
      <tr> 
        <td><font face=verdana size=2>Fax</td>
        <td> <input type="text" name="fax"> </td>
      </tr>
      <tr> 
        <td colspan="2"> <div align="center">
            <input type="reset" name="Submit2" value="Reset">
            <input type="submit" name="Submit" value="Register">
            <input type="hidden" name="action" value="submit">
          </div></td>
      </tr>
      <?
  	if($affid != "")
  	 	echo "<input type=hidden name=affid value=$affid>\n";
?>
    </form>
  </table>
  <br>
  <font face=verdana size=2>To start advertising after you confirm your registration, create a website campaign, 
  edit your web pages and insert the html code. Add some credits to your campaign 
  using 'manage credits' in campaigns page, to start receiving traffic fast.<br>
</blockquote>
<? } ?>
<? include "tpl/bottom.ihtml" ?>
