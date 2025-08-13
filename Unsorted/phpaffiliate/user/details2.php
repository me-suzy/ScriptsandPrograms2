<?
  session_start();

  // check session variable

  if (session_is_registered("valid_user"))
  {
        include "header.290"; 
        include "../config.php"; 
      echo "<p><font face=arial>
    
    	Change Your Details<br>
    	<a href=clicks.php>View Your Click Throughs</a><br>
    	<a href=sales.php>View Your Sales</a><br>
    	<a href=banners.php>Choose A Banner Or Link</a><br>
    	<a href=contact.php>Contact Us</a><br>";
   
{ 
mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 6)"); 
mysql_db_query($database, "UPDATE affiliates SET pass = '$password', company = '$clientcompany', payableto = '$clientpayableto', title = '$clienttitle', firstname = '$clientfirstname', lastname = '$clientlastname', email = '$clientemail', street = '$clientstreet', town = '$clienttown', county = '$clientcounty', country = '$clientcountry', postcode = '$clientpostcode', website = '$webpage', phone = '$clientphone', fax = '$clientfax' WHERE refid = '$valid_user'") or die ("Database INSERT Error (line 7)"); 
 
}
  
  print "<p><p>Your Details Have Now Been Changed</p><br></p><br>";
  
    	
  mysql_connect($server, $db_user, $db_pass) or die ("Database CONNECT Error (line 18)"); 
  $result = mysql_db_query($database, "select * from affiliates where refid = '$valid_user'") or die ("Database INSERT Error (line 19)"); 

    if (mysql_num_rows($result)) {
    while ($qry = mysql_fetch_array($result)) {
      print "<form action=details2.php method=post ENCTYPE=multipart/form-data>
        <p><font face=verdana><small> </small></font><font face=verdana><small></small></font></p>
        <font face=verdana><small>
        <div align=center> 
          <p> 
          <table border=0>
            <tr> 
              <td width=170>Affiliate ID: </td>
              <td width=152>$valid_user</td>
            </tr>
            <tr> 
              <td>Password: </td>
              <td> 
                <input type=password name=password value=\"$qry[pass]\">
              </td>
            </tr>
            <tr> 
              <td>Company: </td>
              <td> 
                <input type=text name=clientcompany value=\"$qry[company]\">
              </td>
            </tr>
            <tr> 
              <td>Cheque Payable To: </td>
              <td> 
                <input type=text name=clientpayableto value=\"$qry[payableto]\">
              </td>
            </tr>
            <tr> 
              <td>Title: </td>
              <td> 
                <select name=clienttitle>
                  <option value=Mr>Mr</option>
                  <option value=Mrs>Mrs</option>
                  <option value=Miss>Miss</option>
                  <option value=Ms>Ms</option>
                  <option value=Dr>Dr</option>
                </select>
              </td>
            </tr>
            <tr> 
              <td>First Name: </td>
              <td> 
                <input type=text name=clientfirstname value=\"$qry[firstname]\">
              </td>
            </tr>
            <tr> 
              <td>Last Name: </td>
              <td> 
                <input type=text name=clientlastname value=\"$qry[lastname]\">
              </td>
            </tr>
            <tr> 
              <td>Email: </td>
              <td> 
                <input type=text name=clientemail value=\"$qry[email]\">
              </td>
            </tr>
            <tr> 
              <td>Street: </td>
              <td> 
                <input type=text name=clientstreet value=\"$qry[street]\">
              </td>
            </tr>
            <tr> 
              <td>Town: </td>
              <td> 
                <input type=text name=clienttown value=\"$qry[town]\">
              </td>
            </tr>
            <tr> 
              <td>County/State: </td>
              <td> 
                <input type=text name=clientcounty value=\"$qry[county]\">
              </td>
            </tr>
            <tr> 
              <td>Country: </td>
              <td> 
                <select name=clientcountry class=dropdown value=\"$qry[country]\">
                  <option value=AF>Afghanistan 
                  <option value=AL>Albania 
                  <option value=DZ>Algeria 
                  <option value=AS>American Samoa 
                  <option value=AD>Andorra 
                  <option value=AO>Angola 
                  <option value=AI>Anguilla 
                  <option value=AQ>Antarctica 
                  <option value=AG>Antigua and Barbuda 
                  <option value=AR>Argentina 
                  <option value=AM>Armenia 
                  <option value=AW>Aruba 
                  <option value=AU>Australia 
                  <option value=AT>Austria 
                  <option value=AZ>Azerbaidjan 
                  <option value=BS>Bahamas 
                  <option value=BH>Bahrain 
                  <option value=BD>Banglades 
                  <option value=BB>Barbados 
                  <option value=BY>Belarus 
                  <option value=BE>Belgium 
                  <option value=BZ>Belize 
                  <option value=BJ>Benin 
                  <option value=BM>Bermuda 
                  <option value=BO>Bolivia 
                  <option value=BA>Bosnia-Herzegovina 
                  <option value=BW>Botswana 
                  <option value=BV>Bouvet Island 
                  <option value=BR>Brazil 
                  <option value=IO>British Indian O. Terr. 
                  <option value=BN>Brunei Darussalam 
                  <option value=BG>Bulgaria 
                  <option value=BF>Burkina Faso 
                  <option value=BI>Burundi 
                  <option value=BT>Buthan 
                  <option value=KH>Cambodia 
                  <option value=CM>Cameroon 
                  <option value=CA>Canada 
                  <option value=CV>Cape Verde 
                  <option value=KY>Cayman Islands 
                  <option value=CF>Central African Rep. 
                  <option value=TD>Chad 
                  <option value=CL>Chile 
                  <option value=CN>China 
                  <option value=CX>Christmas Island 
                  <option value=CC>Cocos (Keeling) Isl. 
                  <option value=CO>Colombia 
                  <option value=KM>Comoros 
                  <option value=CG>Congo 
                  <option value=CK>Cook Islands 
                  <option value=CR>Costa Rica 
                  <option value=HR>Croatia 
                  <option value=CU>Cuba 
                  <option value=CY>Cyprus 
                  <option value=CZ>Czech Republic 
                  <option value=CS>Czechoslovakia 
                  <option value=DK>Denmark 
                  <option value=DJ>Djibouti 
                  <option value=DM>Dominica 
                  <option value=DO>Dominican Republic 
                  <option value=TP>East Timor 
                  <option value=EC>Ecuador 
                  <option value=EG>Egypt 
                  <option value=SV>El Salvador 
                  <option value=GQ>Equatorial Guinea 
                  <option value=EE>Estonia 
                  <option value=ET>Ethiopia 
                  <option value=FK>Falkland Isl.(Malvinas) 
                  <option value=FO>Faroe Islands 
                  <option value=FJ>Fiji 
                  <option value=FI>Finland 
                  <option value=FR>France 
                  <option value=FX>France (European Ter.) 
                  <option value=TF>French Southern Terr. 
                  <option value=GA>Gabon 
                  <option value=GM>Gambia 
                  <option value=GE>Georgia 
                  <option value=DE>Germany 
                  <option value=GH>Ghana 
                  <option value=GI>Gibraltar 
                  <option value=GB>Great Britain (UK) 
                  <option value=GR>Greece 
                  <option value=GL>Greenland 
                  <option value=GD>Grenada 
                  <option value=GP>Guadeloupe (Fr.) 
                  <option value=GU>Guam (US) 
                  <option value=GT>Guatemala 
                  <option value=GN>Guinea 
                  <option value=GW>Guinea Bissau 
                  <option value=GY>Guyana 
                  <option value=GF>Guyana (Fr.) 
                  <option value=HT>Haiti 
                  <option value=HM>Heard & McDonald Isl. 
                  <option value=HN>Honduras 
                  <option value=HK>Hong Kong 
                  <option value=HU>Hungary 
                  <option value=IS>Iceland 
                  <option value=IN>India 
                  <option value=ID>Indonesia 
                  <option value=IR>Iran 
                  <option value=IQ>Iraq 
                  <option value=IE>Ireland 
                  <option value=IL>Israel 
                  <option value=IT>Italy 
                  <option value=CI>Ivory Coast 
                  <option value=JM>Jamaica 
                  <option value=JP>Japan 
                  <option value=JO>Jordan 
                  <option value=KZ>Kazachstan 
                  <option value=KE>Kenya 
                  <option value=KG>Kirgistan 
                  <option value=KI>Kiribati 
                  <option value=KP>Korea (North) 
                  <option value=KR>Korea (South) 
                  <option value=KW>Kuwait 
                  <option value=LA>Laos 
                  <option value=LV>Latvia 
                  <option value=LB>Lebanon 
                  <option value=LS>Lesotho 
                  <option value=LR>Liberia 
                  <option value=LY>Libya 
                  <option value=LI>Liechtenstein 
                  <option value=LT>Lithuania 
                  <option value=LU>Luxembourg 
                  <option value=MO>Macau 
                  <option value=MG>Madagascar 
                  <option value=MW>Malawi 
                  <option value=MY>Malaysia 
                  <option value=MV>Maldives 
                  <option value=ML>Mali 
                  <option value=MT>Malta 
                  <option value=MH>Marshall Islands 
                  <option value=MQ>Martinique (Fr.) 
                  <option value=MR>Mauritania 
                  <option value=MU>Mauritius 
                  <option value=MX>Mexico 
                  <option value=FM>Micronesia 
                  <option value=MD>Moldavia 
                  <option value=MC>Monaco 
                  <option value=MN>Mongolia 
                  <option value=MS>Montserrat 
                  <option value=MA>Morocco 
                  <option value=MZ>Mozambique 
                  <option value=MM>Myanmar 
                  <option value=NA>Namibia 
                  <option value=NR>Nauru 
                  <option value=NP>Nepal 
                  <option value=AN>Netherland Antilles 
                  <option value=NL>Netherlands 
                  <option value=NT>Neutral Zone 
                  <option value=NC>New Caledonia (Fr.) 
                  <option value=NZ>New Zealand 
                  <option value=NI>Nicaragua 
                  <option value=NE>Niger 
                  <option value=NG>Nigeria 
                  <option value=NU>Niue 
                  <option value=NF>Norfolk Island 
                  <option value=MP>Northern Mariana Isl. 
                  <option value=NO>Norway 
                  <option value=OM>Oman 
                  <option value=PK>Pakistan 
                  <option value=PW>Palau 
                  <option value=PA>Panama 
                  <option value=PG>Papua New 
                  <option value=PY>Paraguay 
                  <option value=PE>Peru 
                  <option value=PH>Philippines 
                  <option value=PN>Pitcairn 
                  <option value=PL>Poland 
                  <option value=PF>Polynesia (Fr.) 
                  <option value=PT>Portugal 
                  <option value=PR>Puerto Rico (US) 
                  <option value=QA>Qatar 
                  <option value=RE>Reunion (Fr.) 
                  <option value=RO>Romania 
                  <option value=RU>Russian Federation 
                  <option value=RW>Rwanda 
                  <option value=LC>Saint Lucia 
                  <option value=WS>Samoa 
                  <option value=SM>San Marino 
                  <option value=SA>Saudi Arabia 
                  <option value=SN>Senegal 
                  <option value=SC>Seychelles 
                  <option value=SL>Sierra Leone 
                  <option value=SG>Singapore 
                  <option value=SK>Slovak Republic 
                  <option value=SI>Slovenia 
                  <option value=SB>Solomon Islands 
                  <option value=SO>Somalia 
                  <option value=ZA>South Africa 
                  <option value=SU>Soviet Union 
                  <option value=ES>Spain 
                  <option value=LK>Sri Lanka 
                  <option value=SH>St. Helena 
                  <option value=PM>St. Pierre & Miquelon 
                  <option value=ST>St. Tome and Principe 
                  <option value=KN>St.Kitts Nevis Anguilla 
                  <option value=VC>St.Vincent & Grenadines 
                  <option value=SD>Sudan 
                  <option value=SR>Suriname 
                  <option value=SJ>Svalbard & Jan Mayen Is 
                  <option value=SZ>Swaziland 
                  <option value=SE>Sweden 
                  <option value=CH>Switzerland 
                  <option value=SY>Syria 
                  <option value=TJ>Tadjikistan 
                  <option value=TW>Taiwan 
                  <option value=TZ>Tanzania 
                  <option value=TH>Thailand 
                  <option value=TG>Togo 
                  <option value=TK>Tokelau 
                  <option value=TO>Tonga 
                  <option value=TT>Trinidad & Tobago 
                  <option value=TN>Tunisia 
                  <option value=TR>Turkey 
                  <option value=TM>Turkmenistan 
                  <option value=TC>Turks & Caicos Islands 
                  <option value=TV>Tuvalu 
                  <option value=UM>US Minor outlying Isl. 
                  <option value=UG>Uganda 
                  <option value=UA>Ukraine 
                  <option value=AE>United Arab Emirates 
                  <option value=UK>United Kingdom 
                  <option value=US>United States 
                  <option value=UY>Uruguay 
                  <option value=UZ>Uzbekistan 
                  <option value=VU>Vanuatu 
                  <option value=VA>Vatican City State 
                  <option value=VE>Venezuela 
                  <option value=VN>Vietnam 
                  <option value=VG>Virgin Islands (British) 
                  <option value=VI>Virgin Islands (US) 
                  <option value=WF>Wallis & Futuna Islands 
                  <option value=EH>Western Sahara 
                  <option value=YE>Yemen 
                  <option value=YU>Yugoslavia 
                  <option value=ZR>Zaire 
                  <option value=ZM>Zambia 
                  <option value=ZW>Zimbabwe 
                </select>
              </td>
            </tr>
            <tr> 
              <td>Postcode/Zip: </td>
              <td> 
                <input type=text name=clientpostcode value=\"$qry[postcode]\">
              </td>
            </tr>
            <tr> 
              <td>Web Site Address: </td>
              <td> 
                <input type=text name=webpage value=\"$qry[website]\">
              </td>
            </tr>
            <tr> 
              <td>Phone Number: </td>
              <td> 
                <input type=text name=clientphone value=\"$qry[phone]\">
              </td>
            </tr>
            <tr> 
              <td>Fax Number: </td>
              <td> 
                <input type=text name=clientfax value=\"$qry[fax]\">
              </td>
            </tr>
            <tr> 
              <td height=37 colspan=2 valign=top> 
                <center>
                  <input type=submit name=Submit>
                </center>
              </td>
            </tr>
          </table>
          <p></p>
        </div>
        </small></font> 
      </form>
    
      </p>";
      }
      }
  }
  else
  {
            include "header.290"; 
    echo "<p>Only logged in members may see this page.</p>";
  }

  
  include "footer.290"; 
  
?>

        