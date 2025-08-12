<div align=center><font class=head>Banners Editor</font>

<br>

<p align=justify style="width:500px">

&nbsp;&nbsp;The banners that appear on each page of the site on the system could be added, edited and deleted from here.

</p>

<?=$mesg?>

         <form name=plans action=index.php method=POST>

            <input type="hidden" name=action value=insert>

            <input type="hidden" name=cmd value=banners>

            <table border=0 align=center width=450>

            <tr>

            <td bgcolor=D0D0D0>

            <table border=0 cellspacing=1 cellpadding=2 width=100%>

                  <tr><td bgcolor=F5F5F5 height=21 colspan=2 class=text align=center><b>Add Banner</b></td></tr>

                  <tr>

                  <td height=25 bgcolor=white align=right class=text>Banner Source:&nbsp;</td>

                  <td bgcolor=white class=little align=left>&nbsp;<textarea name=src cols=29 style="scrollbar-face-color:EEEEEE;scrollbar-highlight-color:F1F1F1;scrollbar-shadow-color:909090;scrollbar-3dlight-color:909090;scrollbar-arrow-color:909090;scrollbar-track-color:DADADA;scrollbar-darkshadow-color:f0f0f0;" rows=3></textarea></td>

                  </tr>

            </table>

            </td>

            </tr>

            </table>

            <br>

            <input type="submit" value="Add Banner" class=box>

            </form>

            <br>

            <form name=delt action=index.php method=POST>

            <input type="hidden" name=action value=delete>

            <input type="hidden" name=cmd value=banners>

            <table border=0 align=center width=450>

            <tr>

            <td bgcolor=D0D0D0>

            <table border=0 cellspacing=1 cellpadding=2 width=100%>

                  <tr><td bgcolor=F5F5F5 height=21 colspan=2 class=text align=center><b>Delete Banner</b></td></tr>

                  <tr>

                  <td height=25 bgcolor=white align=right class=text>Banner ID:&nbsp;</td>

                  <td bgcolor=white class=little align=left>&nbsp;

                  <?

                   global $_Config;



                     $dc->query("SELECT * FROM ".$_Config["database_banners"].";");

                     $lines  = "<select name=planID onChange=\"changePlan(this.options.selectedIndex)\"><option value=''>Select a banner to delete</option>";

                     $ulines = "<select name=planID onChange=\"updPlan(this.options.selectedIndex)\"><option value=''>Select a banner to edit</option>";

                     $bannerData = "\"\",";

                     for ($i=0;$i<$dc->num_rows();$i++)

                        {

                           $dc->next_record();

                           $lines  .= "<option value='".$dc->get("id")."'>Banner ".$dc->get("id")."</option>\n";

                           $ulines .= "<option value='".$dc->get("id")."'>Banner ".$dc->get("id")."</option>\n";



                           $whichs = str_replace("/n", "", $dc->get("banner_code"));

                           $whichs = str_replace("/t", "", $whichs);

                           $whichs = str_replace("/r", "", $whichs);

                           $whichs = str_replace("/f", "", $whichs);

                           $whichs = str_replace("\"", "\\\"", $whichs);



                           if ($i < ($dc->num_rows() - 1))

                              {



                                 $bannerData .=  "\"".$whichs."\", ";

                              }

                           else

                              {

                                 $bannerData .=  "\"".$whichs."\"";

                              }



                        }

                     $lines .= "</select>";

                     $ulines .= "</select>";

                  print $lines;

                  ?>

                  </td>

                  </tr>

                  <tr>

                  <td height=25 bgcolor=white align=right class=text>Banner Source:&nbsp;</td>

                  <td bgcolor=white class=little align=left>&nbsp;<textarea name=src cols=29 style="scrollbar-face-color:EEEEEE;scrollbar-highlight-color:F1F1F1;scrollbar-shadow-color:909090;scrollbar-3dlight-color:909090;scrollbar-arrow-color:909090;scrollbar-track-color:DADADA;scrollbar-darkshadow-color:f0f0f0;" rows=3></textarea></td>

                  </tr>

                  <tr>

                  <td height=55 bgcolor=white align=right class=text>Banner Look:&nbsp;</td>

                  <td bgcolor=white class=little valign=middle align=center>&nbsp;<div id=ban1></div></td>

                  </tr>

            </table>

            </td>

            </tr>

            </table>

            <br>

            <input type="submit" value="Delete Banner" class=box>

            </form>

            <br>

            <form name=upd action=index.php method=POST>

            <input type="hidden" name=cmd value=banners>

            <input type="hidden" name=action value=update>

            <table border=0 align=center width=450>

            <tr>

            <td bgcolor=D0D0D0>

            <table border=0 cellspacing=1 cellpadding=2 width=100%>

                  <tr><td bgcolor=F5F5F5 height=21 colspan=2 class=text align=center><b>Update Banner</b></td></tr>

                  <tr>

                  <td height=25 bgcolor=white align=right class=text>Banner ID:&nbsp;</td>

                  <td bgcolor=white class=little align=left>&nbsp;

                  <? print $ulines; ?>

                  </td>

                  </tr>

                  <tr>

                  <td height=25 bgcolor=white align=right class=text>Banner Source:&nbsp;</td>

                  <td bgcolor=white class=little align=left>&nbsp;<textarea name=src cols=29 style="scrollbar-face-color:EEEEEE;scrollbar-highlight-color:F1F1F1;scrollbar-shadow-color:909090;scrollbar-3dlight-color:909090;scrollbar-arrow-color:909090;scrollbar-track-color:DADADA;scrollbar-darkshadow-color:f0f0f0;" rows=3></textarea></td>

                  </tr>

                  <tr>

                  <td height=55 bgcolor=white align=right class=text>Banner Look:&nbsp;</td>

                  <td bgcolor=white class=little align=center valign=middle>&nbsp;<div id=ban2></div></td>

                  </tr>

            </table>

            </td>

            </tr>

            </table>

            <br>

            <input type="submit" value="Update Banner" class=box>

            </form>

                  <script language="javascript">

                  <!--

                  bannerData = [<?=$bannerData?>];

                  f = document.delt;

                  g = document.upd;

                  function changePlan(where)

                     {

                        f.src.value    = bannerData[where];

                        ban1.innerHTML = bannerData[where];

                     }

                  function updPlan(where)

                     {

                        g.src.value    = bannerData[where];

                        ban2.innerHTML = bannerData[where];

                     }

                  //-->

                  </script>

            <br>