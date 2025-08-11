<?php 
 include 'conection.php';
$result = mysql_query('SELECT title, description, date FROM gs_news limit 5');
while ( $row = mysql_fetch_array($result) ) { ?>
<table border=0 cellpadding=0 cellspacing=0 bgcolor="#003366" width="150" >

	<tr>

	<td>

 <table border=0 cellpadding=1 cellspacing=1 bgcolor="#003366" width="400" >

				<tr>

    			<td>

			  		   <table border=0 cellpadding=1 cellspacing=1 background="images/back.gif" width=100%>

			  		   <tr>

    		  		   <td align="left"><b><?php echo $row['title']; ?></b></td><td align="right"><b>Submited on: <?php echo $row['date']; ?></b></td>

			  		   </tr>

			  		   </table>

					   <table border=0 cellpadding=1 cellspacing=1 bgcolor="#ffffff" width=100%>

			  		   <tr>

    		  		   <td align="left">

		   <p class="inner"><?php echo $row['description']; ?></p>

					    </td>

			  		   </tr>

			  		   </table>

					  

			  </td>

			  </tr>

			  </table>

 </td>

			  </tr>

			  </table>
<?php 
 }
?>