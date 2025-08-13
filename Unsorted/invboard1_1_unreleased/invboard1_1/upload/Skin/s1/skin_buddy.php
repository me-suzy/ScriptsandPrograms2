<?php

class skin_buddy {


function buddy_js() {
global $ibforums;
return <<<EOF
<script language='javascript'>
<!--
 function redirect_to(where, closewin)
 {
 	opener.location= '$ibforums->base_url' + where;
 	
 	if (closewin == 1)
 	{
 		self.close();
 	}
 }
 
 function check_form(helpform)
 {
 	opener.name = "ibfmain";
 
 	if (helpform == 1) {
 		document.theForm2.target = 'ibfmain';
 	} else {
 		document.theForm.target = 'ibfmain';
 	}
 	
 	return true;
 }
 
 function shrink()
 {
 	window.resizeTo('200','75');
 }
 
 function expand()
 {
 	window.resizeTo('200','450');
 }
 
 
 //-->
 </script>
 
EOF;
}


function build_away_msg() {
global $ibforums;
return <<<EOF

	{$ibforums->lang['new_posts']}
	<br>
	{$ibforums->lang['my_replies']}
 
EOF;
}

function append_view($url="") {
global $ibforums;
return <<<EOF
	( <b><a href='javascript:redirect_to("$url", 0)'>{$ibforums->lang['view_link']}</a></b> )
EOF;
}


function main($away_text="") {
global $ibforums;
return <<<EOF
<table cellpadding='0' cellspacing='0' border='0' width='100%' height='100%' class='row1' align='center'>
  <tr>
   <td valign='top'>
	<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor='<{tbl_border}>' align='center'>
	 <tr>
	  <td>
		<table cellpadding='5' cellspacing='1' border='0' width='100%' height='100%'>
		  <tr>
			 <td class='titlemedium' align='center'>{$ibforums->lang['page_title']}&nbsp;&nbsp;&nbsp;[ <a href='javascript:shrink()' style='text-decoration:none'>-</a>&nbsp;&nbsp;<a href='javascript:expand()' style='text-decoration:none'>+</a> ]</td>
		  </tr>
		  
		  <tr>
			 <td class='category' valign='middle' align='center'>{$ibforums->lang['while_away']}</td>
		  </tr>
		  <tr>
		   <td align='left' class='row1'>$away_text</td>
		  </tr>
		  
		  
		  <tr>
			 <td class='category' valign='middle' align='center'>{$ibforums->lang['show_me']}</td>
		  </tr>
		  <tr>
		   <td align='left' class='row1'>
		   	<table cellpadding='5' cellspacing='1' border='0' width='100%'>
		   	 <tr>
		   	  <td align='left' width='1%'><img src='{$ibforums->vars['img_url']}/nav_m_dark.gif' border='0'></td>
			  <td align='left' width='99%'><a href='javascript:redirect_to("&act=Stats&CODE=leaders",0)'>{$ibforums->lang['sm_forum_leaders']}</a></td>
			 </tr>
			 <tr>
			  <td align='left' width='1%'><img src='{$ibforums->vars['img_url']}/nav_m_dark.gif' border='0'></td>
			  <td align='left' class='row1'><a href='javascript:redirect_to("&act=Search&CODE=getactive",0)'>{$ibforums->lang['sm_todays_posts']}</a></td>
			 </tr>
			 <tr>
			  <td align='left' width='1%'><img src='{$ibforums->vars['img_url']}/nav_m_dark.gif' border='0'></td>
			  <td align='left' class='row1'><a href='javascript:redirect_to("&act=Stats",0)'>{$ibforums->lang['sm_today_posters']}</a></td>
			 </tr>
			 <tr>
			  <td align='left' width='1%'><img src='{$ibforums->vars['img_url']}/nav_m_dark.gif' border='0'></td>
			  <td align='left' class='row1'><a href='javascript:redirect_to("&act=Members&max_results=10&sort_key=posts&sort_order=desc",0)'>{$ibforums->lang['sm_all_posters']}</a></td>
			 </tr>
			 <tr>
			  <td align='left' width='1%'><img src='{$ibforums->vars['img_url']}/nav_m_dark.gif' border='0'></td>
			  <td align='left' class='row1'><a href='javascript:redirect_to("&act=Search&CODE=lastten",0)'>{$ibforums->lang['sm_my_last_posts']}</a></td>
			 </tr>
			</table>
		   </td>
		  </tr>
		  <tr>
			<td class='category' valign='middle' align='center'>{$ibforums->lang['search_forums']}</td>
		  </tr>
		  <tr>
		   <td align='center' class='row1' valign='middle'><form action="{$ibforums->base_url}&act=Search&CODE=01&smethod=simple&forums=all&cat_forum=forum&search_in=posts&result_type=topics" method="post" name='theForm' onSubmit='return check_form();'><input type='text' size='17' name='keywords' class='forminput'>&nbsp;<input type='submit' value='{$ibforums->lang['go']}'></form></td>
		  </tr>
		  <tr>
			<td class='category' valign='middle' align='center'>{$ibforums->lang['search_help']}</td>
		  </tr>
		  <tr>
		   <td align='center' class='row1' valign='middle'><form action="{$ibforums->base_url}&act=Help&CODE=02" method="post" name='theForm2' onSubmit='return check_form(1);'><input type='text' size='17' name='search_q' class='forminput'>&nbsp;<input type='submit' value='{$ibforums->lang['go']}'></form></td>
		  </tr>
		  </tr>
		 </table>
		</td>
	   </tr>
	  </table>
	 </td>
	</tr>
	<tr>
	 <td>
	  <!--CLOSE.LINK-->
	 </td>
	</tr>
  </table>
EOF;
}



function login() {
global $ibforums;
return <<<EOF

 <form action="{$ibforums->base_url}&act=Login&CODE=01&CookieDate=1&buddy=1" method="post" name='theForm' onSubmit='return check_form();'>
 <table cellpadding='0' cellspacing='0' border='0' width='100%' height='100%' class='row1' align='center'>
  <tr>
   <td valign='top'>
	<table cellpadding='0' cellspacing='0' border='0' width='100%' bgcolor='<{tbl_border}>' align='center'>
	 <tr>
	  <td>
		<table cellpadding='5' cellspacing='1' border='0' width='100%' height='100%'>
		  <tr>
			 <td class='titlemedium' align='center'>{$ibforums->lang['page_title']}</td>
		  </tr>
		  <tr>
			 <td class='category' valign='middle' align='center'>{$ibforums->lang['log_in_needed']}</td>
		  </tr>
		  <tr>
		   <td align='left' class='row1'>
			  {$ibforums->lang['no_guests']}
			  <br><br>
			  <center>
			  <b>{$ibforums->lang['log_in']}</b>
			  <br><br>
			  {$ibforums->lang['lin_name']}<br><input type='text' name='UserName' class='forminput'>
			  <br>
			  {$ibforums->lang['lin_pass']}<br><input type='password' name='PassWord' class='forminput'>
			  <br>
			  <input type='submit' value='{$ibforums->lang['log_in']}' class='forminput'>
			  </center>
			  <br><br>
			  {$ibforums->lang['reg_text']}
			  <br><br>
			  <center><a href='javascript:redirect_to("&act=Reg", 1);'>{$ibforums->lang['reg_link']}</a></center>
		   </td>
		  </tr>
		 </table>
		</td>
	   </tr>
	  </table>
	 </td>
	</tr>
	<tr>
	 <td>
	  <!--CLOSE.LINK-->
	 </td>
	</tr>
  </table>
 </form>

EOF;
}


function closelink() {
global $ibforums;
return <<<EOF

 <table cellpadding='4' cellspacing='0' border='0' width='100%' class='row1' align='center'>
  <tr>
   <td class='category' valign='middle' align='center'>[ <a href="javascript:window.location=window.location;">{$ibforums->lang['refresh']}</a> ] | [ <a href='javascript:self.close();'>{$ibforums->lang['close_win']}</a> ]</td>
  </tr>
 </table>
 

EOF;
}

}
?>