<?php
error_reporting(7);

// -------------------------
//Weather Mk. 2 by Justin J. "JJR512" Rebbert
// -------------------------

$templatesused="index_show_nopermission,index_weather_main,index_weather_select,index_weather_redirect_updatethanks";

require ("./global.php");

if(!$bbuserinfo[userid]) {
  eval("dooutput(\"".show_nopermission()."\");");
}

$user = $DB_site->query_first("SELECT username FROM user WHERE userid=$bbuserinfo[userid]");
$bbusername = $user[username];

if ($citycode) {
  $usersettings[accid] = $citycode;
} else {
  $usersettings = $DB_site->query_first("SELECT * FROM weather_usersettings WHERE userid=$bbuserinfo[userid]");
  if (!isset($usersettings[userid])) {
    $usersettings[accid] = "USNY0996";
    $usersettings[tpc] = "1";
    $usersettings[tps] = "1";
    $DB_site->query("INSERT INTO weather_usersettings (userid,accid,tpc,tps) VALUES ('$bbuserinfo[userid]','$usersettings[accid]','$usersettings[tpc]','$usersettings[tps]')");
  }
}

// ###################### Parse Raw Data #######################
if (!isset($action) or $action=="getdata") {
  $userdata = $DB_site->query_first("SELECT * FROM weather_userdata WHERE userid=$bbuserinfo[userid]");
  $datecut = $userdata[time];
  if ((time()-7200)>$datecut or $forceupdate=="yes" or $citycode) {
    $rawdata = fsockopen("www.msnbc.com",80,$num_error,$str_error,30);
    if(!$rawdata) {
      $weather[error_num] = $num_error;
      $weather[error_str] = $str_error;
    } else {
      fputs($rawdata,"GET /m/chnk/d/weather_d_src.asp?acid=$usersettings[accid] HTTP/1.0\n\n");

      while (!feof($rawdata)) {
        $getbit = fgets($rawdata,4096);
        $getbit = trim($getbit)."\n";
        if (substr($getbit,7,4) == "City") {
          $weather[city] = substr($getbit,15,40);
          $weather[city] = substr($weather[city],0,strlen($weather[city])-3);
        }
        if (substr($getbit,7,6) == "SubDiv") {
          $weather[subdiv] = substr($getbit,17,20);
          $weather[subdiv] = substr($weather[subdiv],0,strlen($weather[subdiv])-3);
        }
        if (substr($getbit,7,7) == "Country") {
          $weather[country] = substr($getbit,18,20);
          $weather[country] = substr($weather[country],0,strlen($weather[country])-3);
        }
        if (substr($getbit,7,6) == "Region") {
          $weather[region] = substr($getbit,17,20);
          $weather[region] = substr($weather[region],0,strlen($weather[region])-3);
        }
        if (substr($getbit,7,5) == "Temp ") {
          $weather[temp] = substr($getbit,15,20);
          $weather[temp] = substr($weather[temp],0,strlen($weather[temp])-3);
        }
        if (substr($getbit,7,5) == "CIcon") {
          $weather[cicon] = substr($getbit,16,20);
          $weather[cicon] = substr($weather[cicon],0,strlen($weather[cicon])-3);
        }
        if (substr($getbit,7,5) == "WindS") {
          $weather[wind_spd] = substr($getbit,16,20);
          $weather[wind_spd] = substr($weather[wind_spd],0,strlen($weather[wind_spd])-3);
        }
        if (substr($getbit,7,5) == "WindD") {
          $weather[wind_dir] = substr($getbit,16,20);
          $weather[wind_dir] = substr($weather[wind_dir],0,strlen($weather[wind_dir])-3);
        }
        if (substr($getbit,7,4) == "Baro") {
          $weather[barometer] = substr($getbit,15,20);
          $weather[barometer] = substr($weather[barometer],0,strlen($weather[barometer])-3);
        }
        if (substr($getbit,7,5) == "Humid") {
          $weather[humidity] = substr($getbit,16,20);
          $weather[humidity] = substr($weather[humidity],0,strlen($weather[humidity])-3);
        }
        if (substr($getbit,7,4) == "Real") {
          $weather[realfeel] = substr($getbit,15,20);
          $weather[realfeel] = substr($weather[realfeel],0,strlen($weather[realfeel])-3);
        }
        if (substr($getbit,7,2) == "UV") {
          $weather[uv]  = substr($getbit,13,20);
          $weather[uv]  = substr($weather[uv],0,strlen($weather[uv])-3);
        }
        if (substr($getbit,7,3) == "Vis") {
          $weather[vis] = substr($getbit,14,20);
          $weather[vis] = substr($weather[vis],0,strlen($weather[vis])-3);
        }
        if (substr($getbit,7,6) == "LastUp") {
          $weather[lastup] = substr($getbit,17,25);
          $weather[lastup] = substr($weather[lastup],0,strlen($weather[lastup])-3);
        }
        if (substr($getbit,7,7) == "ConText") {
          $weather[context] = substr($getbit,18,25);
          $weather[context] = substr($weather[context],0,strlen($weather[context])-3);
        }
        if (substr($getbit,7,4) == "Fore") {
          $forecast = substr($getbit,15,200);
          $forecast = substr($forecast,0,strlen($forecast)-3);
          $forecast = explode("|",$forecast);
        }
        if (substr($getbit,7,4) == "Acid") {
		  $weather[acid] = substr($getbit,15,20);
		  $weather[acid] = substr($weather[acid],0,strlen($weather[acid])-3);
        }
      }

      // Location Info
      $weatherdata[city] = $weather[city];
      $weatherdata[subdiv] = $weather[subdiv];
      $weatherdata[country] = $weather[country];
      $weatherdata[region] = $weather[region];

      // Current Conditions
      $weatherdata[temp] = convert_temp($weather[temp],$usersettings[tpc]);
      $weatherdata[cicon] = $weather[cicon];
      $weatherdata[wind_dir] = $weather[wind_dir];
      $weatherdata[wind_spd] = convert_speed($weather[wind_spd],$usersettings[tps]);
      $weatherdata[barometer] = convert_press($weather[barometer],$usersettings[tps]);
      $weatherdata[humidity] = $weather[humidity];
      $weatherdata[uv] = $weather[uv];
      $weatherdata[realfeel] = convert_temp($weather[realfeel],$usersettings[tpc]);
      $weatherdata[vis] = convert_length($weather[vis],$usersettings[tps]);
      $weatherdata[lastup] = $weather[lastup];
      $weatherdata[context] = $weather[context];

      // Forecast Day of Week
      $weatherdata[forecastday1] = getdayofweek($forecast[0]);
      $weatherdata[forecastday2] = getdayofweek($forecast[1]);
      $weatherdata[forecastday3] = getdayofweek($forecast[2]);
      $weatherdata[forecastday4] = getdayofweek($forecast[3]);
      $weatherdata[forecastday5] = getdayofweek($forecast[4]);

      // Forecast Icons
      $weatherdata[forecasticon1] = $forecast[10];
      $weatherdata[forecasticon2] = $forecast[11];
      $weatherdata[forecasticon3] = $forecast[12];
      $weatherdata[forecasticon4] = $forecast[13];
      $weatherdata[forecasticon5] = $forecast[14];

      // Forecast Types
      $weatherdata[forecasttype1] = getweathertype($forecast[15]);
      $weatherdata[forecasttype2] = getweathertype($forecast[16]);
      $weatherdata[forecasttype3] = getweathertype($forecast[17]);
      $weatherdata[forecasttype4] = getweathertype($forecast[18]);
      $weatherdata[forecasttype5] = getweathertype($forecast[19]);

      // Forecast Highs
      $weatherdata[forecasthigh1] = convert_temp($forecast[20],$usersettings[tpc]);
      $weatherdata[forecasthigh2] = convert_temp($forecast[21],$usersettings[tpc]);
      $weatherdata[forecasthigh3] = convert_temp($forecast[22],$usersettings[tpc]);
      $weatherdata[forecasthigh4] = convert_temp($forecast[23],$usersettings[tpc]);
      $weatherdata[forecasthigh5] = convert_temp($forecast[24],$usersettings[tpc]);

      // Forecast Lows
      $weatherdata[forecastlow1] = convert_temp($forecast[40],$usersettings[tpc]);
      $weatherdata[forecastlow2] = convert_temp($forecast[41],$usersettings[tpc]);
      $weatherdata[forecastlow3] = convert_temp($forecast[42],$usersettings[tpc]);
      $weatherdata[forecastlow4] = convert_temp($forecast[43],$usersettings[tpc]);
      $weatherdata[forecastlow5] = convert_temp($forecast[44],$usersettings[tpc]);

      fclose($rawdata);
    }

    if (!$citycode) {
      $DB_site->query("DELETE FROM weather_userdata WHERE userid='$bbuserinfo[userid]'");
      $DB_site->query("
      INSERT INTO weather_userdata
    	(userid,time,city,subdiv,country,region,temp,cicon,wind_spd,wind_dir,barometer,humidity,realfeel,uv,vis,lastup,context,forecastday1,forecastday2,forecastday3,forecastday4,forecastday5,forecasticon1,forecasticon2,forecasticon3,forecasticon4,forecasticon5,forecasttype1,forecasttype2,forecasttype3,forecasttype4,forecasttype5,forecasthigh1,forecasthigh2,forecasthigh3,forecasthigh4,forecasthigh5,forecastlow1,forecastlow2,forecastlow3,forecastlow4,forecastlow5)
      VALUES
    	('$bbuserinfo[userid]','".time()."','$weatherdata[city]','$weatherdata[subdiv]','$weatherdata[country]','$weatherdata[region]','$weatherdata[temp]','$weatherdata[cicon]','$weatherdata[wind_spd]','$weatherdata[wind_dir]','$weatherdata[barometer]','$weatherdata[humidity]','$weatherdata[realfeel]','$weatherdata[uv]','$weatherdata[vis]','$weatherdata[lastup]','$weatherdata[context]','$weatherdata[forecastday1]','$weatherdata[forecastday2]','$weatherdata[forecastday3]','$weatherdata[forecastday4]','$weatherdata[forecastday5]','$weatherdata[forecasticon1]','$weatherdata[forecasticon2]','$weatherdata[forecasticon3]','$weatherdata[forecasticon4]','$weatherdata[forecasticon5]','$weatherdata[forecasttype1]','$weatherdata[forecasttype2]','$weatherdata[forecasttype3]','$weatherdata[forecasttype4]','$weatherdata[forecasttype5]','$weatherdata[forecasthigh1]','$weatherdata[forecasthigh2]','$weatherdata[forecasthigh3]','$weatherdata[forecasthigh4]','$weatherdata[forecasthigh5]','$weatherdata[forecastlow1]','$weatherdata[forecastlow2]','$weatherdata[forecastlow3]','$weatherdata[forecastlow4]','$weatherdata[forecastlow5]')
      ");
    }

  } else {
    $weatherdata = $DB_site->query_first("SELECT * FROM weather_userdata WHERE userid=$bbuserinfo[userid]");
  }

  if ($weatherdata[subdiv]) {
    $weatherdata[showsubdiv] = "$weatherdata[subdiv], ";
  } else {
    $weatherdata[showsubdiv] = "";
  }

  $time_lastup = strtotime($weatherdata[lastup]);
  $weather[updatedate] = vbdate($dateformat,$time_lastup);
  $weather[updatetime] = vbdate($timeformat,$time_lastup);

  eval("dooutput(\"".gettemplate("index_weather_main")."\");");
}

// ###################### Modify Settings #######################
if ($action=="modifysettings") {
  $usersettings = $DB_site->query_first("SELECT * FROM weather_usersettings WHERE userid=$bbuserinfo[userid]");
  if (!isset($usersettings[userid])) {
    $usersettings[accid] = "USNY0996";
    $usersettings[tpc] = "1";
    $usersettings[tps] = "1";
    $DB_site->query("INSERT INTO weather_usersettings (userid,accid,tpc,tps) VALUES ('$bbuserinfo[userid]','$usersettings[accid]','$usersettings[tpc]','$usersettings[tps]')");
  }
  $current_subdiv = $DB_site->query_first("SELECT subdivid FROM weather_city WHERE accid='$usersettings[accid]'");
  $cities = $DB_site->query("SELECT accid,city_title FROM weather_city WHERE subdivid=$current_subdiv[subdivid] ORDER BY city_title");
  $select_city = "";
  while ($city=$DB_site->fetch_array($cities)) {
    if ($city[accid]==$usersettings[accid]) {
      $cityselected = " selected";
    } else {
      $cityselected = "";
    }
    $select_city .= "<option value=\"$city[accid]\"$cityselected>$city[city_title]</option>";
  }
  $current_country = $DB_site->query_first("SELECT countryid FROM weather_subdiv WHERE subdivid=$current_subdiv[subdivid]");
  $subdivs = $DB_site->query("SELECT subdivid,subdiv_title FROM weather_subdiv WHERE countryid=$current_country[countryid] ORDER BY subdiv_title");
  $select_subdiv = "";
  while ($subdiv=$DB_site->fetch_array($subdivs)) {
    if ($subdiv[subdivid]==$current_subdiv[subdivid]) {
      $subdivselected = " selected";
    } else {
      $subdivselected = "";
    }
    $select_subdiv .= "<option value=\"$subdiv[subdivid]\"$subdivselected>$subdiv[subdiv_title]</option>";
  }
  $current_region = $DB_site->query_first("SELECT regionid FROM weather_country WHERE countryid=$current_country[countryid]");
  $countries = $DB_site->query("SELECT countryid,country_title FROM weather_country WHERE regionid=$current_region[regionid] ORDER BY country_title");
  $select_country = "";
  $selectedcountry = $current_country[countryid];
  while ($country=$DB_site->fetch_array($countries)) {
    if ($country[countryid]==$current_country[countryid]) {
      $countryselected = " selected";
    } else {
      $countryselected = "";
    }
    $select_country .= "<option value=\"$country[countryid]\"$countryselected>$country[country_title]</option>";
  }
  $regions = $DB_site->query("SELECT * FROM weather_region ORDER BY region_title");
  $select_region = "";
  $selectedregion = $current_region[regionid];
  while ($region=$DB_site->fetch_array($regions)) {
    if ($region[regionid]==$current_region[regionid]) {
      $regionselected = " selected";
    } else {
      $regionselected = "";
    }
    $select_region .= "<option value=\"$region[regionid]\"$regionselected>$region[region_title]</option>";
  }
  if ($usersettings[tpc]=="1") {
    $fahrenheit_checked = " checked";
    $celsius_checked = "";
  } else {
    $fahrenheit_checked = "";
    $celsius_checked = " checked";
  }
  if ($usersettings[tps]=="1") {
    $standard_checked = " checked";
    $metric_checked = "";
  } else {
    $standard_checked = "";
    $metric_checked = " checked";
  }
  $btnstatus = "";

  eval("dooutput(\"".gettemplate("index_weather_select")."\");");
}

// ###################### Update Settings #######################
if ($action=="updatesettings") {

  if ($update=="region") {
    $regions = $DB_site->query("SELECT * FROM weather_region ORDER BY region_title");
    $select_region = "";
    while ($region=$DB_site->fetch_array($regions)) {
      if ($region[regionid]==$selectedregion) {
        $regionselected = " selected";
      } else {
        $regionselected = "";
      }
      $select_region .= "<option value=\"$region[regionid]\"$regionselected>$region[region_title]</option>";
    }
    $count_countries = $DB_site->query_first("SELECT count(*) AS count FROM weather_country WHERE regionid=$selectedregion");
    if ($count_countries[count]==1) {
      $country = $DB_site->query_first("SELECT * FROM weather_country WHERE regionid=$selectedregion");
      $select_country = "<option value=\"$country[countryid]\" selected>$country[country_title]</option>";
      $selectedcountry = $country[countryid];
      $count_subdivs = $DB_site->query_first("SELECT count(*) as count FROM weather_subdiv WHERE countryid=$selectedcountry");
      if ($count_subdivs[count]==1) {
        $subdiv = $DB_site->query_first("SELECT * FROM weather_subdiv WHERE countryid=$selectedcountry");
        $select_subdiv = "<option value=\"$subdiv[subdivid]\" selected>$subdiv[subdiv_title]</option>";
        $selectedsubdiv = $subdiv[subdivid];
        $cities = $DB_site->query("SELECT * FROM weather_city WHERE subdivid=$selectedsubdiv ORDER BY city_title");
        $select_city = "";
        while ($city=$DB_site->fetch_array($cities)) {
          $select_city .= "<option value=\"$city[accid]\">$city[city_title]</option>";
        }
        $btnstatus = "";
      } else {
        $subdivs = $DB_site->query("SELECT * FROM weather_subdiv WHERE countryid=$selectedcountry ORDER BY subdiv_title");
        $select_subdiv = "";
        while ($subdiv=$DB_site->fetch_array($subdivs)) {
          $select_subdiv .= "<option value=\"$subdiv[subdivid]\">$subdiv[subdiv_title]</option>";
        }
        $btnstatus = " disabled";
      }
    } else {
      $countries = $DB_site->query("SELECT * FROM weather_country WHERE regionid=$selectedregion ORDER BY country_title");
      $select_country = "";
      while ($country=$DB_site->fetch_array($countries)) {
        $select_country .= "<option value=\"$country[countryid]\">$country[country_title]</option>";
      }
      $select_subdiv = "";
      $select_city = "";
      $btnstatus = " disabled";
    }
  }

  if ($update=="country") {
    $regions = $DB_site->query("SELECT * FROM weather_region ORDER BY region_title");
    $select_region = "";
    while ($region=$DB_site->fetch_array($regions)) {
      if ($region[regionid]==$selectedregion) {
        $regionselected = " selected";
      } else {
        $regionselected = "";
      }
      $select_region .= "<option value=\"$region[regionid]\"$regionselected>$region[region_title]</option>";
    }
    $countries = $DB_site->query("SELECT * FROM weather_country WHERE regionid=$selectedregion ORDER BY country_title");
    $select_country = "";
    while ($country=$DB_site->fetch_array($countries)) {
      if ($country[countryid]==$selectedcountry) {
        $countryselected = " selected";
      } else {
        $countryselected = "";
      }
      $select_country .= "<option value=\"$country[countryid]\"$countryselected>$country[country_title]</option>";
    }
    $count_subdivs = $DB_site->query_first("SELECT count(*) AS count FROM weather_subdiv WHERE countryid=$selectedcountry");
    if ($count_subdivs[count]==1) {
      $subdiv = $DB_site->query_first("SELECT * FROM weather_subdiv WHERE countryid=$selectedcountry");
      $select_subdiv = "<option value=\"$subdiv[subdivid]\" selected>$subdiv[subdiv_title]</option>";
      $selectedsubdiv = $subdiv[subdivid];
      $cities = $DB_site->query("SELECT * FROM weather_city WHERE subdivid=$selectedsubdiv ORDER BY city_title");
      $select_city = "";
      while ($city=$DB_site->fetch_array($cities)) {
        $select_city .= "<option value=\"$city[accid]\">$city[city_title]</option>";
      }
      $btnstatus = "";
    } else {
      $subdivs = $DB_site->query("SELECT * FROM weather_subdiv WHERE countryid=$selectedcountry ORDER BY subdiv_title");
      $select_subdiv = "";
      while ($subdiv=$DB_site->fetch_array($subdivs)) {
        $select_subdiv .= "<option value=\"$subdiv[subdivid]\">$subdiv[subdiv_title]</option>";
      }
      $select_city = "";
      $btnstatus = " disabled";
    }
  }

  if ($update=="subdiv") {
    $regions = $DB_site->query("SELECT * FROM weather_region ORDER BY region_title");
    $select_region = "";
    while ($region=$DB_site->fetch_array($regions)) {
      if ($region[regionid]==$selectedregion) {
        $regionselected = " selected";
      } else {
        $regionselected = "";
      }
      $select_region .= "<option value=\"$region[regionid]\"$regionselected>$region[region_title]</option>";
    }
    $countries = $DB_site->query("SELECT * FROM weather_country WHERE regionid=$selectedregion ORDER BY country_title");
    $select_country = "";
    while ($country=$DB_site->fetch_array($countries)) {
      if ($country[countryid]==$selectedcountry) {
        $countryselected = " selected";
      } else {
        $countryselected = "";
      }
      $select_country .= "<option value=\"$country[countryid]\"$countryselected>$country[country_title]</option>";
    }
    $subdivs = $DB_site->query("SELECT * FROM weather_subdiv WHERE countryid=$selectedcountry ORDER BY subdiv_title");
    $select_subdiv = "";
    while ($subdiv=$DB_site->fetch_array($subdivs)) {
      if ($subdiv[subdivid]==$selectedsubdiv) {
        $subdivselected = " selected";
      } else {
        $subdivselected = "";
      }
      $select_subdiv .= "<option value=\"$subdiv[subdivid]\"$subdivselected>$subdiv[subdiv_title]</option>";
    }
    $cities = $DB_site->query("SELECT * FROM weather_city WHERE subdivid=$selectedsubdiv ORDER BY city_title");
    $select_city = "";
    while ($city=$DB_site->fetch_array($cities)) {
      $select_city .= "<option value=\"$city[accid]\">$city[city_title]</option>";
    }
    $btnstatus = "";
  }

  if ($update=="city") {
    $DB_site->query("UPDATE weather_usersettings SET accid='$selectedcity', tpc='$select_tpc', tps='$select_tps' WHERE userid='$bbuserinfo[userid]'");
    $DB_site->query("UPDATE weather_userdata SET time='0' WHERE userid='$bbuserinfo[userid]'");

    $goto = "weather.php?s=$session[sessionhash]&action=getdata&forceupdate=yes";
    eval("standardredirect(\"".gettemplate("index_weather_redirect_updatethanks")."\",\"$goto\");");
  }

  // $usersettings = $DB_site->query_first("SELECT tpc,tps FROM weather_usersettings WHERE userid=$bbuserinfo[userid]");
  if ($usersettings[tpc]=="1") {
    $fahrenheit_checked = " checked";
    $celsius_checked = "";
  } else {
    $fahrenheit_checked = "";
    $celsius_checked = " checked";
  }
  if ($usersettings[tps]=="1") {
    $standard_checked = " checked";
    $metric_checked = "";
  } else {
    $standard_checked = "";
    $metric_checked = " checked";
  }

  eval("dooutput(\"".gettemplate("index_weather_select")."\");");
}

?>