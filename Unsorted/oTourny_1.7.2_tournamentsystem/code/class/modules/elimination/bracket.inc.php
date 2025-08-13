<? /*************************************************************************************************

Copyright (c) 2003, 2004 Nathan 'Random' Rini

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*************************************************************************************************/ if(!defined('CONFIG')) die;

 /*

  Elimination Bracket Class

 */



 //bracket types

 $GLOBALS["elmination_bracket_type"]["single"] = 1;

 $GLOBALS["elmination_bracket_type"]["double"] = 2;



 //Common Elimination Bracket Functions

 class elimination_bracket extends db_tourny_module {

  function write_match_status(&$match, $edit){global $tourny, $tpl, $users, $teams;

   $tpl->splice("STATUS", "module_elim_match_status.tpl");



   //grab team count

   $tpm = $this->get("teamspermatch");



   for($i=0;$i < $tpm;$i++){

    if($tourny->type() == $GLOBALS["tourny_type_single"])

     $team =& $users->user($match->get_team($i));

    if($tourny->type() == $GLOBALS["tourny_type_team"])

     $team =& $teams->team($match->get_team($i));



    if($team->id > 0) //valid team

     $tpl->assign("STATUS->STATUS_TEAM_NAME", $team->get("name"));

    else //null team

     $tpl->parse("STATUS->STATUS_TEAM_NAME", "STATUS->STATUS_TEAM_NAME");



    //null out all the checks

    $tpl->assign(array(

      "FIELD_WIN_CHK_0"   => '',

      "FIELD_WIN_CHK_1"   => '',

      "FIELD_WIN_CHK_2"   => '',

      "FIELD_WIN_CHK_3"   => ''

     ));



    //assign checked value

    if($match->get_team($i, "status") >= $GLOBALS["MATCH_STATUS"]["LBOUND"] && $match->get_team($i, "status") <= $GLOBALS["MATCH_STATUS"]["HBOUND"])

     $tpl->assign("FIELD_WIN_CHK_" . $match->get_team($i, "status"), " checked ");

    else //make sure something is selected

     $tpl->assign("FIELD_WIN_CHK_0", " checked ");



    $tpl->parse("STATUS->STATUS_TEAM_LIST", "STATUS->STATUS_TEAM_LIST", true, array(

      "TEAM_LINK" => "?page=profile&type=".$tourny->type()."&id=".$team->id,

      "FIELD_WIN_NAME"    => "STATUS_".$i,

      "FIELD_WIN_VALUE_3" => $GLOBALS["MATCH_STATUS"]["Winner"],

      "FIELD_WIN_VALUE_2" => $GLOBALS["MATCH_STATUS"]["Loser"],

      "FIELD_WIN_VALUE_1" => $GLOBALS["MATCH_STATUS"]["Forfeit"],

      "FIELD_WIN_VALUE_0" => $GLOBALS["MATCH_STATUS"]["Undecided"],

      "FIELD_WIN_DIS_3"   => $edit ? "" : "disabled",

      "FIELD_WIN_DIS_2"   => $edit ? "" : "disabled",

      "FIELD_WIN_DIS_1"   => $edit ? "" : "disabled",

      "FIELD_WIN_DIS_0"   => $edit ? "" : "disabled"

     ));

   }



   $tpl->parse("MATCH->STATUS_EDIT", "STATUS");

  }



  //check that given status is a valid solution

   //defeat - check that they are giving up

  function validate_match_status(&$match, $status, $score, $defeat = false){global $teams, $tourny, $user;

   //undo any previous match movements

   if((BOOL) $match->get("decided")) $this->move_back_match($match);



   //all fake teams are forfeits -- force override

   for($t=0;$t < $this->tpm();$t++)

    if(!$match->get_team($t) > 0) $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];



   if($defeat) //The defeated must admit defeat - So if they dont - they forfeit

   for($t=0;$t < $this->tpm();$t++){

    //Single Player

    if($tourny->type() == $GLOBALS["tourny_type_single"]) //force them to lose

     if($match->get_team($t) == $user->id) //make sure they are loser or forfieting

      if(!($status[$t] == $GLOBALS["MATCH_STATUS"]["Loser"] || $status[$t] == $GLOBALS["MATCH_STATUS"]["Forfeit"]))

       $cheat[] = $t; //dont let it unForfeit them



    //Team

    if($tourny->type() == $GLOBALS["tourny_type_team"]){

     //grab team for match spot

     $team =& $teams->team($match->get_team($t));



     if($team->is_user($user->id)){ //make sure they are loser or forfieting

      if(!($status[$t] == $GLOBALS["MATCH_STATUS"]["Loser"] || $status[$t] == $GLOBALS["MATCH_STATUS"]["Forfeit"]))

       $cheat[] = $t; //dont let it unForfeit them

    }}



    if(is_array($cheat)) //void match for evil cheaters

     for($t=0;$t < $this->tpm();$t++){ //make everyone undecided but cheater(s)

      if(!in_array($t, $cheat)) $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];

      else $status[$t] = $GLOBALS["MATCH_STATUS"]["Forfeit"]; //evil cheater

     }

   }



   //make sure there is only 1 winner

   for($t=0;$t < $this->tpm();$t++)

    if($status[$t] == $GLOBALS["MATCH_STATUS"]["Winner"]){

     //there can only be 1 winner - first come first serve

     if($win_set) $status[$t] = $GLOBALS["MATCH_STATUS"]["Loser"];



     $win_set = true;

    }



   if($defeat){ //admins can fail everyone

    if($win_set){ //if valid status = those who dont play forfiet

     for($t=0;$t < $this->tpm();$t++)

      if($status[$t] == $GLOBALS["MATCH_STATUS"]["Undecided"])

       $status[$t] = $GLOBALS["MATCH_STATUS"]["Forfeit"];

    }else //no winner set - void match

     if(!is_array($cheat)) //make sure not to cancel cheaters matchs

      for($t=0;$t < $this->tpm();$t++) //make everyone undecided

       $status[$t] = $GLOBALS["MATCH_STATUS"]["Undecided"];

   }



   //save scores and status

   for($t=0;$t < $this->tpm();$t++)

    if($status[$t] >= $GLOBALS["MATCH_STATUS"]["LBOUND"] && $status[$t] <= $GLOBALS["MATCH_STATUS"]["HBOUND"]){//valid status

     $match->set_team($t, "status", $status[$t]); //team



     for($m=0;$m < $this->get("mapspermatch");$m++) //map

      $match->set_score($t, $m, $score[$t][$m]);

    }



   //verify that it is decided

   $match->check_decided();



   //move all the teams to their respective postions

   if(!is_array($cheat) || !$defeat) //admins have full power

    if($win_set && (BOOL) $match->get("decided")) //dont advance canceled matchs

     $this->move_next_match($match);

  }



  //converts status to route type

   //route codes are diff than whats shown to pub

  function convert_route_type($route_types, $type){

   //find the route type to use from team status

   switch((INT) $type){

    case $GLOBALS["MATCH_STATUS"]["Winner"]:

     $rtype = $route_types["Winner"];

     break;

    default:

     $rtype = $route_types["Loser"];

     break;

   }



   return $rtype;

  }



  //Generate Bracket

  function gen_bracket($teamc = 2){global $tpl, $tourny;

   $this->del_matchs(); //clear old matchs



   $bracket = new elimination_bracket_master_template($this->id, $this->type(), $this->get("teamspermatch"), $teamc);



   $this->set(array(

     "rounds"    => $bracket->rounds,  //round count

     "generated" => 1, //save that we made bracket

     "tpl"       => "module_" . $this->id . "_tpl.tpl" //unique id

    ));



   //save template

   $tpl->save($this->get("tpl"), $bracket->tpl);

  }



  //team list should be order from best players (ie more byes) to loser players

  function seed($teamlst){global $tourny;

   //grab all matchs from round 1

   $matchs =& $this->matchs(1, FALSE, TRUE, TRUE);

   $matchs =& $matchs["ref"]; //grab list only



   //assign round to 1

   $this->set("round", 1);

   //match count

   $matchc = count($matchs);

   //grab some stats

   $teamcount = count($teamlst);

   //total possible teams - teams expected

   $teamexp   = (INT) $this->tpm() * $matchc;

   //match byes - Have to use eq since it keeps shifting

   //e = b*(tpm-1) + t

   $match_bye  = floor(($teamexp - $teamcount) / ($this->tpm() - 1));

   //full matchs that will be played

   $match_full = $matchc - $match_bye;

   //missing teams in first match

   //e = b*(tpm-1) + t + mi

   $match_full_missing = $teamexp - $match_bye * ($this->tpm() - 1) - $teamcount;



   //fill array with blanks for byes

   for($bye=0;$bye < $match_bye;$bye++){

    $match[$bye][0] = (INT) $teamlst[$bye]; //add bye team



    //add blank teams

    for($blank=1;$blank < $this->tpm();$blank++)

     $match[$bye][$blank] = (INT) 0;

   }



   //add match with missing player

   if($match_full_missing > 0){

    for($t=0;$t < $this->tpm() - $match_full_missing;$t++)

     $match[$bye][$t] = (INT) $teamlst[$t + $match_bye]; //add team + adjust for bye teams



    //add blank teams

    for($blank=$t;$blank <= $match_full_missing + $t;$blank++)

     $match[$bye][$blank] = (INT) 0;

   }



   $m = count($match) - 1; //remove one since it will add one instantly

   $p = 0; //position

   //add real matchs

   for($i=$bye + $t;$i < $teamcount;$i++){

    if($p++ % $this->tpm() == 0) $m++;



    $match[$m][] = (INT) $teamlst[$i];

   }



   for($i=0;$i < $matchc;$i++)

    $matchsplit[round($i % 2)][] = $match[$i];



   //flip bottom matchs

   $matchsplit[1] = array_reverse($matchsplit[1]);



   unset($match);



   foreach($matchsplit[1] as $matchsplititem)

    $matchsplit[0][] = $matchsplititem;



   //rename for ease of use

   $match = $matchsplit[0];



   unset($matchsplit);



   //put teams in matchs

   for($i=0;$i < $matchc;$i++){

    if(!$match[$i][1] > 0){ //bye round

     //tell engine match is done

     $matchs[$i]->set("decided", 1);

     $matchs[$i]->set_team(0, "status", $GLOBALS["MATCH_STATUS"]["Winner"]);



     //grab winner route to grab next match

     $route = $matchs[$i]->get_route($matchs[$i]->route_types["Winner"], 0);

     $route = $route[0]; //grab first route



     //grab match

     $matchbye =& $tourny->match($route->match);



     //assign team

     $matchbye->set_team($route->team, "id", $match[$i][0]);



     unset($matchbye); unset($route);

    }



    //assign team to match

    for($t=0;$t < $this->tpm();$t++)

     $matchs[$i]->set_team($t, 'id', $match[$i][$t]);

   }



   //assign out servers to round 1

   $this->assign_servers_round(1);

  }

 }



 //Generates Bracket Template and Match Routes

 class elimination_bracket_master_template {

  var $bracket_class; //classes used in bracket gen



  var $moduleid = 0; //owner module id

  var $type   = 0; //bracket type

  var $tpm    = 2; //teams per match - default atleast 2

  var $matchs = 1; //match count - default atleast 1

  var $bracket;    //array of bracket tpl gen classes

  var $routes;     //match routing codes

  var $rounds = 0; //final round count



  function elimination_bracket_master_template($moduleid, $type, $teamspermatch, $teamcount){

   set_time_limit(600); //6 min max -- special override for generating only



   $this->moduleid = $moduleid;

   $this->type   = $type;

   $this->tpm    = $teamspermatch >= 2   ? ceil($teamspermatch) : 2; //force atleast 2

   $this->matchs = $this->match_count_min($teamcount, $this->tpm);



   //set min

   if($this->matchs < 1) $this->matchs = 1;



   //declare the class names - using a tpl is a waste just for this

   $this->bracket_class = array(

    "spacerall"  => "spacerall",

    "topdivider" => "topdivider",

    "spacer"     => "spacer",

    "botdivider" => "botdivider"

   );



   //allocate the bracket classes

   $this->allocate();



   //create match routes

   $this->route();



   //create whole bracket template

   $this->render();



   //create matchs

   $this->matches();



   //remove mem from since it is only needed for generation

   unset($this->bracket);

   unset($this->routes);

  }



  //Calculates Min matchs required for all teams

   //Geometric Min

  function match_count_min($team_count = 0, $tpm){

   if($team_count < 1) return 0; //dont waste cpu for no teams



   //force atleast 2 tpm

   $tpm = $tpm >= 2 ? ceil($tpm) : 2;



   //Number of matchs per team count (usually a float)

   $matchs = (FLOAT) $team_count / $tpm;



   //force matchs to a power of tpm -- Dont change very sensitive

   return (INT) pow($tpm, (INT) ceil(log($matchs) / log($tpm)));

  }



  //allocate proper bracket(s) according to type in order in $this->bracket

  function allocate(){

   switch($this->type){

    case $GLOBALS["elmination_bracket_type"]["single"]:

     //elmination bracket

     $this->bracket[] = new elimination_bracket_template(0, 0, $GLOBALS["elmination_bracket_type"]["single"], $this->matchs, $this->tpm, $this->bracket_class, 0, 0);



     break;

    case $GLOBALS["elmination_bracket_type"]["double"]:

     //elmination bracket

     $this->bracket[] = new elimination_bracket_template(0, 0, $GLOBALS["elmination_bracket_type"]["single"], $this->matchs, $this->tpm, $this->bracket_class, 0, 0);



     //run through a loser bracket for each team

     for($i=1; $i < $this->tpm; $i++)

      $this->bracket[] = new elimination_bracket_template($i, 1, $GLOBALS["elmination_bracket_type"]["double"], $this->matchs / $this->tpm, $this->tpm, $this->bracket_class, 1, ($this->tpm > 2 ? $i : ''));



     //finals bracket

     $this->bracket[] = new elimination_bracket_template($i, $this->bracket[count($this->bracket) - 1]->round_count, $GLOBALS["elmination_bracket_type"]["single"], 1, $this->tpm, $this->bracket_class, 2, 0);

     break;

   }

  }



  //render the whole template

  function render(){global $tpl;

   //run through and grab all the templates

   for($i=0; $i < count($this->bracket); $i++)

    $this->tpl .= $this->bracket[$i]->tpl;

  }



  /*

    $this->routes[bracket][round][match][team][row]

    $this->routes[bracket][round][match][team][col]



    $this->routes[bracket][round][match][team][win][] ["bracket"]["round"]["team"]["match"]

    $this->routes[bracket][round][match][team][los][] ["bracket"]["round"]["team"]["match"]



    $this->routes[bracket][round][match][team][sub][row]

    $this->routes[bracket][round][match][team][sub][col]

  */

  function route(){global $tpl;

   /*

    run through each bracket and grab all the information we need

    resort the order in an easier form for second parts

   */



   //grab all the double elim bracket ids

   for($b=1;$b < count($this->bracket) - 1; $b++)

    $losers[] = $b;



   $bracketcount = count($this->bracket);



   for($i=0;$i < $bracketcount; $i++){ //bracket

    $bracket =& $this->bracket[$i];



    //bracket type

    $this->routes[$i]["type"] = $bracket->type;



    for($col=1;$col <= count($bracket->bracket);$col++) //col

     for($row=0;$row < count($bracket->bracket[$col]);$row++) //row

      if(isset($bracket->bracket[$col][$row]["team"])) //valid position on bracket

      {//reference location for ease of reading

       $match =& $bracket->bracket[$col][$row];



       //quick grab the info

       $r = $match["round"];

       $m = $match["match"];

       $t = $match["team"];



       //reference location for ease of reading

       $route =& $this->routes[$i][$r][$m][$t];



       //position in table

       $route["row"] = $row;

       $route["col"] = $col;



       //sub text postion in table

       $route["sub"]["row"] = $row + 1; //always + 1

       $route["sub"]["col"] = $col;



       //Winner Route

       $win["bracket"] = $i;

       $win["round"]   = $r + 1;



       //find next team position

       if($route["col"] != $bracket->winner["col"]){ //dont set winner route here

        if($route["col"] + 1 == $bracket->winner["col"]){

         //col before winner - override



         //winner pos

         $win[0]["bracket"] = $i;

         $win[0]["team"]  = 0;

         $win[0]["match"] = 1;

         $win[0]["round"] = 0; //override for final since its doesnt have round

         //final brackets

         $win[1]["bracket"] = $bracketcount - 1;

         $win[1]["team"]  = $i;

         $win[1]["match"] = 1;

         $win[1]["round"] = 1; //override for final since its doesnt have round

        } elseif($this->routes[$i]["type"] == $GLOBALS["elmination_bracket_type"]["double"] && $r % 2 != 0) {

          //only even rounds need special override

         //winner moves straight right

         $win["team"]  = 0;

        $win["match"] = $m;

        } else { //normal bracket

         $win["team"]  = ($m % $this->tpm) - 1;

         $win["match"] = floor(($m - 1) / $this->tpm) + 1;



         //last team needs minor corrections

         if($win["team"] == -1)

         $win["team"]   = $this->tpm - 1;

       }}



       //Loser Route -- Only apply if double elim

       if($this->type == $GLOBALS["elmination_bracket_type"]["double"])

        if($i == 0) {//You can loose only once - Only Single Bracket

         //Loser can goto any of the loser brackets

         if($r == 1){ //first round needs special override

          $lose["team"]  = ($m % $this->tpm); //this causes the teams to be semi reversed but it works

          $lose["match"] = floor(($m - 1) / $this->tpm) + 1;

          $lose["round"] = 2;

         } else { //simple move down

          $lose["team"]  = ($m % $this->tpm); //this causes the teams to be semi reversed but it works

          $lose["match"] = $m;

          $lose["round"] = (2*($r - 1)) + 1; //every other round

         }



         //only include teh double elimination brackets

         $lose["bracket"] = $losers;

        }



       //copy to route array

       $route["win"]  = $win;

       if(isset($lose)) $route["lose"] = $lose;



       //unset so we dont have any repeats

       unset($win);

       unset($lose);

      }



    //winner route

    $route =& $this->routes[$i][0][1][0]; //round is 0 as override



    //notify engine - round is fake

    $route["null"] = TRUE;



    //position in table

    $route["row"] = $bracket->winner["row"];

    $route["col"] = $bracket->winner["col"];



    //sub text postion in table

    $route["sub"]["row"] = $bracket->winner["row"] + 1; //always + 1

    $route["sub"]["col"] = $bracket->winner["col"];

   }

  }



  /*

   Run through and Create all the Matchs => (ie) Abuse mysql

  */

  function matches(){global $tpl, $tourny;

   //save the round count

   $this->rounds = $this->bracket[count($this->bracket)-1]->round_count;



   //run through and make the matchs and create each match

   //need to have a reference for each route for later linking

   for($b=0;$b < count($this->routes); $b++) //bracket

    for($r=0;$r < count($this->routes[$b]); $r++) //round

     for($m=1;$m <= count($this->routes[$b][$r]); $m++) //match

      if(!empty($this->routes[$b][$r][$m])){

       //echo $b ."-". $r ."-". $m . "<br>";

       //create match

       $match = &$tourny->match(0,1);



       //save round

       $match->set(array(

         "moduleid" => $this->moduleid,

         "round"    => $r > 0 ? $r + $this->bracket[$b]->round_offset : 0 //adjust round for offset and dont fubar fake rounds

        ));



       //save match id for next loop to create routes

       $this->routes[$b][$r][$m]["matchid"] = $match->id;



       unset($match);

      }



   //run through and make the matchs and create routes

   for($b=0;$b < count($this->routes); $b++) //bracket

    for($r=0;$r < count($this->routes[$b]); $r++) //round

     for($m=1;$m <= count($this->routes[$b][$r]); $m++) //match

      for($t=0;$t < count($this->routes[$b][$r][$m]);$t++) //team

       if(!empty($this->routes[$b][$r][$m][$t])){

        $match = &$tourny->match($this->routes[$b][$r][$m]["matchid"]);

        $route = &$this->routes[$b][$r][$m][$t];



        //Winner Routes

        if(is_array($route["win"][0])){ //check if there is an subarray

         $wcount = count($route["win"]) - 2; //account for ["bracket"] && ["round"]



         for($w=0;$w < $wcount;$w++) //make sure everythign is declared

          if($this->routes[$route["win"][$w]["bracket"]][$route["win"][$w]["round"]][$route["win"][$w]["match"]]["matchid"] > 0){

           $match_route = new db_tourny_match_result_pos($this->routes

            [$route["win"][$w]["bracket"]]

            [$route["win"][$w]["round"]]

            [$route["win"][$w]["match"]]

            ["matchid"], $route["win"][$w]["team"]);



           $match->set_route($match->route_types["Winner"], $t, $match_route);



           unset($match_route);

          }

        }else

         //make sure everythign is declared

         if($this->routes[$route["win"]["bracket"]][$route["win"]["round"]][$route["win"]["match"]]["matchid"] > 0){

          $match_route = new db_tourny_match_result_pos($this->routes

            [$route["win"]["bracket"]]

            [$route["win"]["round"]]

            [$route["win"]["match"]]

            ["matchid"], $route["win"]["team"]);



          $match->set_route($match->route_types["Winner"], $t, $match_route);



          unset($match_route);

         }



        //Loser Routes

        if(isset($route["lose"])) //are there loser routes

         if(is_array($route["lose"]["bracket"])){ //valid with bracket array

          for($loser_bracket=0;$loser_bracket < count($route["lose"]["bracket"]);$loser_bracket++)

           //make sure everythign is declared

           if(

            $this->routes

             [$route["lose"]["bracket"][$loser_bracket]]

             [$route["lose"]["round"]]

             [$route["lose"]["match"]]

             ["matchid"] > 0

           ){

            $match_route = new db_tourny_match_result_pos($this->routes

              [$route["lose"]["bracket"][$loser_bracket]]

              [$route["lose"]["round"]]

              [$route["lose"]["match"]]

              ["matchid"], $route["lose"]["team"], TRUE);



            $match->set_route($match->route_types["Loser"], $t, $match_route);



            unset($match_route);

           }

         }

        //parse out position var name

        $tpl->parsefile("POS", "module_bracket_elimination_pos.tpl", array(

          "ID"  => $b,

          "COL" => $route["col"],

          "ROW" => $route["row"]

         ), 1);



        //save position

        $match->set_team($t, "position", $tpl->fetch("POS"));



        unset($match); unset($route);

       }

  }

 }





 /*

  Bracket Template Generator Class

   Creates 1 bracket template and Bracket Array with Alot of Data

 */

 class elimination_bracket_template {

  var $id;    //id held in db

  var $rows;  //match count

  var $tpm;   //teams per match

  var $type;  //bracket type

  var $bracket; //bracket array

  var $bracket_class; //classes for bracket



  var $round; //array of which cols are which round

  var $round_offset; //round off set applied to round labels

  var $round_count;  //last round number



  var $tpl;   //rendered template of bracket



  var $cols;  //rendered col count

  var $rows;  //rendered row count

  var $nametype;   //bracket name type as listed in tpl

  var $nameoffset; //used for loser brackets - its bracket number



  var $winner; //holds winner col/row/round



  function elimination_bracket_template($id, $round_offset, $type, $rows, $teamspermatch, $bracket_class, $nametype, $nameoffset){

   $this->id    = $id;   //unique ID for this bracket

   $this->round_offset = $round_offset; //round off set applied to round labels

   $this->rows  = $rows; //count of matchs in first round

   $this->tpm   = $teamspermatch; //number of teams in each match

   $this->type  = $type; //specify for rendering $GLOBALS["elmination_bracket_type"]["double"] or $GLOBALS["elmination_bracket_type"]["single"]

   $this->bracket_class = $bracket_class; //array of class names for rendering    s

   $this->nametype   = $nametype; //bracket name type as listed in tpl

   $this->nameoffset = $nameoffset; //used for loser brackets - its bracket number



   //create bracket

   switch($this->type){

    case $GLOBALS["elmination_bracket_type"]["single"]:

     $this->bracket = $this->generate_single();

     break;

    case $GLOBALS["elmination_bracket_type"]["double"]:

     $this->bracket = $this->generate_double();

     break;

   }



   //adjust the match data

   $this->assign_matchs();



   //render out bracket template

   $this->tpl = $this->render();

  }



  //generate single bracket

  function generate_single(){

   $round = 1; //start at 1



   for($col = 1; $col < 250; $col++) //250 is a safety - shouldnt ever hit that many

   {

    if($col == 1) $maxrows = $this->rows; //first iteration, assign max rows

    else $maxrows = $maxrows / $this->tpm;   //each column, the rows halve



    $rowi = 0; //start row count at 0



    if($col == 1){//defaults for first row

     $topsize    = 0;

     $middlesize = 1;

    }else { //row repostioning

     $topsize    = round($middlesize * ($this->tpm / 2)) + floor(($this->tpm - 2) / 2);

     $middlesize = ($middlesize * $this->tpm) + $this->tpm - 1;

    }



    //write top spacer

    for($i = 1; $i <= $topsize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];



    //add every col to a round

    if($maxrows >= 1) $this->round[$col] = $round++;



    for($row = 1; $row < $maxrows + 1; $row++)

    {

     if($maxrows < 1){ //make sure colums maxed out dont show in error

      $table[$col][$rowi++]["class"] = $this->bracket_class["topdivider"];



      return $table;

     } else {///full bracket

      $table[$col][$rowi]["match"]   = $row; //assign new match # from row when changed

      $table[$col][$rowi++]["class"] = $this->bracket_class["topdivider"];



      //add extra teams past the normal 2

      for($t = 1; $t <= $this->tpm - 2; $t++){

       if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];

       else $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];



       $table[$col][$rowi++]["class"]  = $this->bracket_class["botdivider"];

      }



      if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];

      else $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];



      $table[$col][$rowi++]["class"]   = $this->bracket_class["botdivider"];



      if($row != $maxrows){

       if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

       else $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

      }

   }}}



   die("Bracket is too big.");

  }



  //generate hanging bracket

  function generate_double(){

   $round = 2; //doubles start at 2



   for($col = 1; $col < 250; $col++) //250 is a safety - shouldnt ever hit that many

   {

    //every other col is hanging

    if(($col - 1) % 2) $hang = TRUE;

    else $hang = FALSE;



    if($col == 1) $maxrows = $this->rows; //first iteration, assign max rows

    else if(!$hang) //maintain maxrows for hanging cols

      $maxrows = $maxrows / $this->tpm;   //each column, the rows halve



    //add every col to a round for 2 tpm

    if($this->tpm == 2 && $maxrows >= 1) $this->round[$col] = $round++;



    $rowi = 0; //start row count at 0



    if($col == 1){//defaults for first row

     $topsize    = 0;

     $middlesize = 1;

    }else if(!$hang) //dont reposition for hanging cols

     { //row repostioning

      $topsize    = round($middlesize * ($this->tpm / 2)) + floor(($this->tpm - 2) / 2);

      $middlesize = ($middlesize * $this->tpm) + $this->tpm - 1;

     }



    //write top spacer

    for($i = 1; $i <= $topsize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];



    if($hang)

     for($i = 1; $i <= $topsize + ceil(($middlesize - $topsize) / 2); $i++)

      $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

    else

     for($i = 1; $i <= $topsize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];



    for($row = 1; $row < $maxrows + 1; $row++)

    {

     if($maxrows < 1){ //make sure colums maxed out dont show in error

      $table[$col][$rowi++]["class"] = $this->bracket_class["topdivider"];



      //dont split up 2 team

      if($this->tpm == 2) return $table;



      //make a null for 0

      $tableOut[] = array();



      //insert the spacer cols between hanging sections

      for($icol = 1; $icol <= $col; $icol++){

       $tableOut[] = $table[$icol];



       //add the round # for the col count

       if($icol + 1 <= $col) $this->round[count($tableOut) - 1] = $round++;



       if($icol < $col && $icol % 2 != 0)

        if(!empty($tabletop[$icol + 1]))

         $tableOut[] = $tabletop[$icol + 1];

      }



      return $tableOut;

     } else {///full bracket

      $table[$col][$rowi]["match"]   = $row; //assign new match # from row when changed

      $table[$col][$rowi++]["class"] = $this->bracket_class["topdivider"];



      //keep track of first tops for spacer col

      if($this->tpm > 2)

       $tabletop[$col][$rowi - 1]["class"] = $this->bracket_class["topdivider"];



      //add extra teams past the normal 2

      for($t = 1; $t <= $this->tpm - 2; $t++){

       if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];

       else $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];



       $table[$col][$rowi++]["class"] = $this->bracket_class["botdivider"];

      }



      if($hang){

       if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];

       else $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];



       $table[$col][$rowi++]["class"] = $this->bracket_class["botdivider"];



       if($row != $maxrows){

        if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

        else $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

       }

      } else { //not hanging

       if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];

       else $table[$col][$rowi++]["class"] = $this->bracket_class["spacer"];



       $table[$col][$rowi++]["class"] = $this->bracket_class["botdivider"];



       if($row != $maxrows){

        if($col != 1) for($i = 1; $i <= $middlesize; $i++) $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

        else $table[$col][$rowi++]["class"] = $this->bracket_class["spacerall"];

   }}}}}



   die("Bracket is too big.");

  }



  /*  run through and assign out all the matchs to their respective positions

      Need to run it after the generators since the generaters

       create the relavent data at different times

  */

  function assign_matchs(){

   for($col=1;$col < count($this->bracket);$col++){

    for($row=0;$row < count($this->bracket[$col]);$row++){

     //match is assigned at every change, grab it and assign all below

     if($this->bracket[$col][$row]["match"] > 0){

      //grab the match

      $match = $this->bracket[$col][$row]["match"];



      //set as first team

      $team = 0;

     }



     //set the team - increment each time

     if($this->bracket[$col][$row]["class"] == $this->bracket_class["topdivider"]

        || $this->bracket[$col][$row]["class"] == $this->bracket_class["botdivider"])

         $this->bracket[$col][$row]["team"] = $team++;



     //set the match

     $this->bracket[$col][$row]["match"] = $match;



     //set each section's round

     $this->bracket[$col][$row]["round"] = $this->round[$col];

    }



    //add an extra row to each col

    $this->bracket[$col][$row]["class"] = 'spacerall';

    $this->bracket[$col][$row]["match"] = $this->bracket[$col][$row - 1]["match"];

    $this->bracket[$col][$row]["round"] = $this->round[$col];

   }



   //find the winner team

   switch($this->type){

    case $GLOBALS["elmination_bracket_type"]["single"]:

     $col = count($this->bracket);

     $row = count($this->bracket[$col]) - 1;

     break;

    case $GLOBALS["elmination_bracket_type"]["double"]:

     if($this->tpm == 2){ //dont include the spacers

      $col = count($this->bracket);

      $row = count($this->bracket[$col]) - 1;

     } else { //above 2

      $col = count($this->bracket) - 1;

      $row = count($this->bracket[$col]) - 2;

     }

     break;

   }



   //mark the winner team position

   $this->winner["col"]   = $col;

   $this->winner["row"]   = $row;

  }



  //render bracket

  function render(){global $tpl;

   $tpl->splice("BRACKET", "module_bracket_elimination.tpl");



   //number of cols

   $maxcols = count($this->bracket);



   switch($this->type){

    case $GLOBALS["elmination_bracket_type"]["single"]:

     $maxrows = count($this->bracket[1]) - 1;

     break;

    case $GLOBALS["elmination_bracket_type"]["double"]:

     if($this->tpm == 2) //dont include the spacers

      $maxrows = count($this->bracket[$maxcols - 1]) - 1;

     else { //above 2

      $maxrows =  count($this->bracket[$maxcols - 2]) - 1;

      $maxcols -= 1; //fix extra row that always shows

     }

     break;

   }



   //copy max rows/cols for later saving

   $this->cols = $maxcols;

   $this->rows = $maxrows;



   //clear any old data

   $tpl->clear("BRACKET");

   $tpl->clear("BRACKET->HCOL");

   $tpl->clear("BRACKET->COL");

   $tpl->clear("BRACKET->ROW");



   //parse out the name

   $tpl->parse("BRACKET->NAME", "BRACKET->NAME_".$this->nametype, array(

     "BRACKET_NUMBER" => $this->nameoffset,

     "BRACKET_ID"     => $this->id

    ));



   //parse out headers

   for($col=1;$col <= $maxcols;$col++){

    /* Hanging Brackets follow simple sequence - RSRRSRRSRRS...

       Using Bracket->round array make it simple and allows for correlation */

    if($this->round[$col] > 0){

     $this->round_count = $this->round[$col] + $this->round_offset;



     $tpl->parse("BRACKET->ROUND", "BRACKET->ROUND", array(

       "HREF"  => "{A_ROUND_". $this->round_count ."_HREF}",

       "ROUND" => $this->round_count

      ));



    }else //hide round

     $tpl->assign("BRACKET->ROUND",'');



    $tpl->parse("BRACKET->HCOL", "BRACKET->HCOL", 1);

   }



   //run through each row

   for($row=0;$row <= $maxrows;$row++){

    //run through each col

    for($col=1;$col <= $maxcols;$col++){

     //default assign

     $assign = FALSE;



     //space is bottom divider

     if($this->bracket[$col][$row]["class"] == $this->bracket_class["botdivider"])

      $assign = TRUE;



     //space is below bottom divider

     if($this->bracket[$col][$row - 1]["class"] == $this->bracket_class["botdivider"])

      $assign = FALSE; //space for extra txt



     //space is top divider - make sure isnt a spacer for hanging

     if($this->bracket[$col][$row]["class"] == $this->bracket_class["topdivider"] && $this->bracket[$col + 1][$row]["class"] != $this->bracket_class["topdivider"])

      $assign = TRUE;



     //space is below top divider - make sure isnt a spacer for hanging

     if($this->bracket[$col][$row - 1]["class"] == $this->bracket_class["topdivider"] && $this->bracket[$col + 1][$row - 1]["class"] != $this->bracket_class["topdivider"])

      $assign = FALSE; //space for extra txt



     if($assign) //pass the var indicating the location

      $tpl->parse("BRACKET->COL_TEXT", "BRACKET->COL_TEXT", array(

        "COL" => $col,

        "ROW" => $row,

        "ID"  => $this->id

       ));

     else $tpl->assign("BRACKET->COL_TEXT", ''); //clear as default



     $tpl->parse("BRACKET->COL", "BRACKET->COL", 1, array(

       "COL_CLASS" => $this->bracket[$col][$row]["class"]

      ));

    }



    $tpl->parse("BRACKET->ROW", "BRACKET->ROW", 1);

    $tpl->clear("BRACKET->COL");

   }



   return $tpl->parse("BRACKET", "BRACKET", array(

     "COL_COUNT" => $maxcols,

    ));

  }

 }



?>