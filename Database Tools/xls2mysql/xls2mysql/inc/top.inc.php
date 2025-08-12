<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<!--
tabs : http://concepts.waetech.com/dhtml_tabs/
-->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>xls2mysql</title>
  <style type="text/css" media="screen">@import "jss/style.css";</style>

    <script language="JavaScript" type="text/javascript">
      var panels = new Array('panel1', 'panel2');
      var selectedTab = null;
      function showPanel(tab, name)
      {
	  selectedTab = document.getElementById("current_tab");
       if (selectedTab) 
        {
		  selectedTab.id="";
		  selectedTab.parentNode.id="";
        }
        selectedTab = tab;
		selectedTab.id="current_tab";
		selectedTab.parentNode.id="current";
		 
        for(i = 0; i < panels.length; i++)
        {
          document.getElementById(panels[i]).style.display = (name == panels[i]) ? 'block':'none';
        }
        return false;
      }
	 
    </script>
	
</head>
<body >


<div id=container>