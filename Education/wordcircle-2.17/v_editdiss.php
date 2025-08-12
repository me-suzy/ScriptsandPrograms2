<?php

$GLOBALS['page']->head("wordcircle","","This page is for the person who created the course only, nobody else can access this page");

	if(isset($_POST['categorize'])){
	
			foreach($_POST as $key=>$value){
			
			if(strstr($key,"did")){
				$did = str_replace('did','',$key);
				$GLOBALS['db']->execQuery("update discussions set category_id = ".$value." where discussion_id = ".$did." and group_id = ".$_GET['gid']); 
			}
			
			}
			
			$GLOBALS['page']->pleaseWait("Please wait, re-assigning categories","index.php?a=editdiss&cat=1&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
	}

	if(isset($_GET['moveup'])){
	
	$reslow = $GLOBALS['db']->execQuery("select category_id,order_number from categories where group_id = ".$_GET['gid']." order by order_number asc");
	$i=1;
	$takeAway=false;
	$addBack=false;
	$addNormal=true;
	while ($row = mysql_fetch_assoc($reslow)) {
	if($addBack == true){$addBack=false;$takeAway=false;$i=$i+2;$addNormal=true;}
	elseif($takeAway == true){$i--;$addBack=true;}
	elseif($_GET['cid'] == $row['category_id']){$i++;$takeAway=true;$addNormal=false;}
	$GLOBALS['db']->execQuery("update categories set order_number = ".($i)." where category_id = ".$row['category_id']);
	if($addNormal == true){
	$i++;
	}
	}
	$GLOBALS['page']->pleaseWait("Please wait, re-ordering categories","index.php?a=editdiss&cat=1&gid=".$_GET['gid']);
			
			include("v_footer.php");
			exit;
	
	}
	
		if(isset($_GET['movedn'])){	
	$reslow = $GLOBALS['db']->execQuery("select category_id,order_number from categories where group_id = ".$_GET['gid']." order by order_number desc");
	$i=mysql_num_rows($reslow);
	$takeAway=false;
	$addBack=false;
	$addNormal=true;
	while ($row = mysql_fetch_assoc($reslow)) {
	if($addBack == true){$addBack=false;$takeAway=false;$i=$i-2;$addNormal=true;}
	elseif($takeAway == true){$i++;$addBack=true;}
	elseif($_GET['cid'] == $row['category_id']){$i--;$takeAway=true;$addNormal=false;}
	$GLOBALS['db']->execQuery("update categories set order_number = ".($i)." where category_id = ".$row['category_id']);
	if($addNormal == true){
	$i--;
	}
	}
	$GLOBALS['page']->pleaseWait("Please wait, re-ordering categories","index.php?a=editdiss&cat=1&gid=".$_GET['gid']);
			
			include("v_footer.php");
			exit;
	
	}

	if(isset($_GET['delcat'])){

	$GLOBALS['db']->execQuery("delete from categories where category_id = ".$_GET['cid']." and group_id = ".$_GET['gid']);
	$GLOBALS['db']->execQuery("update discussions set category_id = null where category_id = ".$_GET['cid']." and group_id = ".$_GET['gid']);
	
	$GLOBALS['page']->pleaseWait("Please wait, category being removed","index.php?a=editdiss&cat=1&gid=".$_GET['gid']);
			
			include("v_footer.php");
			exit;
	
	}

	if (isset($_GET['cat'])){
	
	if(isset($_POST['submit'])){
		
		
		$GLOBALS['db']->checkLen($_POST['category_name'],61,"Category names must be less than 60 characters");
$GLOBALS['db']->checkTyped($_POST['category_name'],"You must enter a category name");
		
	
			if(count($GLOBALS['error'])==0){
			$resmax = $GLOBALS['db']->execQuery("select max(order_number) as max_order from categories where group_id = ".$_GET['gid']);
			$new_order = (mysql_result($resmax,0,"max_order") + 1);
			$GLOBALS['db']->execQuery("insert into categories(category_name,group_id,order_number) values('".$_POST['category_name']."',".$_GET['gid'].",".$new_order.")");
			$GLOBALS['page']->pleaseWait("Please wait, new category being added","index.php?a=editdiss&cat=1&gid=".$_GET['gid']);
			
			include("v_footer.php");
			exit;
			}
	
	}
	
		$did = array();
		$di = array();
		$i=0;
		
		$result = $GLOBALS['db']->execQuery("select * from categories where group_id = ".$_GET['gid']." order by order_number asc");
		$result2 = $result;
		while ($row = mysql_fetch_assoc($result)) { 
	   $di[$i]['category_id'] = $row['category_id'];
	   $di[$i]['category_name'] = $row['category_name'];
       $did[$i]['category'] = $row["category_name"]; 
	  
	    $did[$i][''] = "<a href='index.php?a=editdiss&movedn=".($row['order_number']-1)."&cid=".$row['category_id']."&gid=".$_GET['gid']."'><img src='icon_up.gif' width='14' height='14' border=0 align='top'>move</a>&nbsp;<a href='index.php?a=editdiss&moveup=".($row['order_number']+1)."&cid=".$row['category_id']."&gid=".$_GET['gid']."'><img src='icon_down.gif' width='14' height='14' border=0 align='top'>move</a>&nbsp;<a href='index.php?a=editdiss&delcat=1&cid=".$row['category_id']."&gid=".$_GET['gid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this category?'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this item' border='0'>remove</a>";
	   $i++;
   }
   
   
   
	$reslist = $GLOBALS['db']->execQuery("select discussion_name,category_id, discussion_id from discussions where group_id = ".$_GET['gid']." order by last_message");
   
   	$GLOBALS['page']->tableStart("","100%","TAB","Create / Delete Categories");
		echo("<br><img src='icon_tools.gif' width='16' height='16' align='top' hspace='1'><a href='index.php?a=editdiss&gid=".$_GET['gid']."'>Click here to go back to modify discussions page</a><br>");
		
		$GLOBALS['page']->tableStart("","100%","FORM");
		//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
		$GLOBALS['page']->text("","category_name","inputs","Category Name:",30,1);
		$GLOBALS['page']->submit("Add Category","inputs");
		$GLOBALS['page']->tableEnd("FORM");
	
   	if(count($did)>0){
	;
	

	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($did,"odd","even","");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no categories available");
	$GLOBALS['page']->tableEnd("TEXT");
	}
	
	echo("<br>");
	if(mysql_num_rows($reslist)>0){
	echo("
	<div align='center'>categorize your discussions by selecting a category for each one</div>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	while ($resrows = mysql_fetch_assoc($reslist)) { 
	echo("<tr><td align='right'>".$resrows['discussion_name']." category:</td><td><select name='did".$resrows['discussion_id']."' class='inputs'><option value='0'>uncategorized</option>");
		for ($tt=0;$tt<count($di);$tt++){ 
		echo("<option value='".$di[$tt]['category_id']."' ".(($di[$tt]['category_id'] == $resrows['category_id'])?"selected":"").">".$di[$tt]['category_name']."</option>");
		}
	echo("</select></td></tr>");
	}
	echo("<tr><td>&nbsp;</td><td><input type='submit' class='inputs' name='categorize' value='categorize'></td></tr>");
	$GLOBALS['page']->tableEnd("FORM");
	}
	$GLOBALS['page']->tableEnd("TAB");
	
		include("v_footer.php");
		exit;
	}


	if (isset($_GET['deldiss'])){
	
		$GLOBALS['db']->execQuery("delete from discussions where discussion_id = ".$_GET['did']);
		$GLOBALS['db']->execQuery("delete from topics where discussion_id = ".$_GET['did']);
		$GLOBALS['db']->execQuery("delete from messages where discussion_id = ".$_GET['did']);
		$GLOBALS['page']->pleaseWait("Please wait while we delete this discussion","index.php?a=editdiss&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
		}
	
	if (isset($_POST['submit'])){
	
	
	$GLOBALS['db']->checkLen($_POST['discussion_name'],61,"Discussion names must be less than 60 characters");
$GLOBALS['db']->checkTyped($_POST['discussion_name'],"You must enter a discussion name");
		
	switch($_POST['submit']){	
	
		case "Add Discussion":
		
		
			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("insert into discussions(discussion_name,group_id,category_id) values('".trim($_POST['discussion_name'])."',".$_GET['gid'].",".$_POST['category_id'].")");
			$GLOBALS['page']->pleaseWait("Please wait while we add this discussion","index.php?a=editdiss&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		break;
		case "Edit Discussion":

			if(count($GLOBALS['error'])==0){
			$GLOBALS['db']->execQuery("update discussions set discussion_name = '".trim($_POST['discussion_name'])."' where discussion_id = ".$_GET['did']);
			$GLOBALS['page']->pleaseWait("Please wait while your discussion is updated","index.php?a=editdiss&gid=".$_GET['gid']);
			include("v_footer.php");
			exit;
			}
		break;
	}
		
	}
	
//get all the categories for this course...
	
	$rescat = $GLOBALS['db']->execQuery("select category_name, order_number, category_id from categories where group_id = ".$_GET['gid']);

//start discuss list

	$result = $GLOBALS['db']->execQuery("select discussion_id,discussion_name,date_format(last_message,'%m.%d.%y') as  last_message,total_messages, categories.category_id, category_name,categories.order_number from discussions left outer join categories on  categories.category_id = discussions.category_id where discussions.group_id = ".$_GET['gid']." order by order_number asc, last_message");
	$i = 0;
	$diss = array();
	$catArray = array();
	while ($row = mysql_fetch_assoc($result)) {
	   if($row['category_name'] <> "" and in_array($row['category_name'],$catArray) == false){
	   $diss[$i]['discussions'] = '<strong>&nbsp;&nbsp;'.$row['category_name'].'</strong>';
	    $diss[$i]['last post'] = ""; 
	   $diss[$i]['total posts'] = "";
	    $diss[$i][''] ="";
	   array_push($catArray,$row['category_name']);
	   $i++;
	   }
       $did[$i]['discussion id'] = $row["discussion_id"]; 
	   $diss[$i]['discussions'] = '<img src="icon_group.gif" width="14" height="17" border=0 hspace=1 align="top">'."<a href='index.php?a=discuss&did=".$row['discussion_id']."&gid=".$_GET['gid']."'>".$row["discussion_name"]."</a>";
       $diss[$i]['last post'] = $row["last_message"]; 
	   $diss[$i]['total posts'] = '&nbsp;&nbsp;&nbsp;&nbsp;'.$row["total_messages"];
	    $diss[$i][''] = "<a href='index.php?a=editdiss&deldiss=1&did=".$row['discussion_id']."&gid=".$_GET['gid']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this discussion?\\nALL topics and messages for the discussion will be deleted as well!'); return confirmDownload;\"><img src='icon_delete.gif' width='16' height='16' alt='delete this item' border='0'>remove</a>";
	   $i++;
   }
	
	$GLOBALS['page']->tableStart("","100%","TAB","Discussions");
	
		
	echo('<br>');
	if (isset($_GET['revise'])){
		$diss = "";
		
		$result2 = $GLOBALS['db']->execQuery("select discussion_name from discussions where discussion_id = ".$_GET['did']);
		while ($row2 = mysql_fetch_assoc($result2)) { 
		$diss = $row2['discussion_name'];
   		}	
		
		echo("<a name='form'></a>");
		$GLOBALS['page']->tableStart("","100%","FORM");
		//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
		$GLOBALS['page']->text($diss,"discussion_name","inputs","Edit Discussion Name:",30,1);
		$GLOBALS['page']->submit("Edit Discussion","inputs");
				echo("<tr><td>&nbsp;</td><td><a href='index.php?a=editdiss&deldiss=1&gid=".$_GET['gid']."&did=".$_GET['did']."' onClick=\"confirmDownload = confirm('Are you sure you want to delete this discussion?\\nALL topics and messages for the discussion will be deleted as well!'); return confirmDownload;\">[Delete this discussion by clicking here]</a></td></tr>");
echo("<tr><td>&nbsp;</td><td><a href='index.php?a=editdiss&gid=".$_GET['gid']."&did=".$_GET['did']."'>[Cancel by clicking here]</a></td></tr>");
		$GLOBALS['page']->tableEnd("FORM");
		}else{
	 
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo('Discussion names should be concise yet descriptive<br>
Examples: <em>Matt, Jim and Sarah\'s Discussion Group</em> <strong>or</strong> <em>All about Latin Languages</em><br><br>

<a href="index.php?a=editdiss&gid='.$_GET['gid'].'&cat=1"><img src="icon_category.gif" width="16" height="16" border="0" align="top" alt="">You can modify discussion categories by clicking here</a>');
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function textarea($value,$name,$class,$rows,$cols,$desc,$chngeOnPost){
	$GLOBALS['page']->text("","discussion_name","inputs","Enter Discussion Name:",30,1);
	echo('<tr><td align="right" valign="top">Category:</td><td><select name="category_id" class="inputs"><option value="0">No category</option>');
	while ($row4 = mysql_fetch_assoc($rescat)) { 
	echo("<option value='".$row4['category_id']."'>".$row4['category_name']);
	}
	
	echo('</select></td></tr>');
	$GLOBALS['page']->submit("Add Discussion","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	
	}
	
	if(count($diss)>0){
	;
	
	$GLOBALS['page']->tableStart("","100%","GRID");
	$GLOBALS['page']->rows($diss,"odd","even","");
	$GLOBALS['page']->tableEnd("GRID");
	}else{
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo("There are no discussions available");
	$GLOBALS['page']->tableEnd("TEXT");
	}

	$GLOBALS['page']->tableEnd("TAB");
	
?>