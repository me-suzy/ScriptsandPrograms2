<?php
#IPW METAZO 2.0 OBJECT
$_objoldid = 340316;
function objfile_340316 () {
$obj = owNew('template');
$objdata['name'] = "standard_sitemap";
$objdata['content'] = "{readstructure name=\"main\" assign=\"res\"}
    {assign var=\"currentlevel\" value=0}
    {section name=\"i\" loop=\$res}
    {if \$res[i].level < 6}
    
    {if \$currentlevel == 0}
        <ul id=\"menuList\" class=\"adxm\">
    {elseif \$res[i].level > \$currentlevel}
        <ul id=\"m{\$res[i].objectid}Menu\">     
    {/if}
   
    {section name=\"levels\" loop=6}
        {if \$res[i].level < \$currentlevel-\$smarty.section.levels.index}</ul></li>{/if}
    {/section}
    
    {if \$res[i].level > 0}
        {if \$res[i].pageid == \$get.pageid}{assign var=\"active\" value=\"id='active'\"}{else}{assign var=\"active\" value=\"\"}{/if}
    
        {if \$res[i].object.haschild == 1}
            <li class=\"submenu\" {\$active}>        
        {else}
            <li {\$active}>
        {/if}
        {if \$res[i].urltype != 0}<a href=\"{\$res[i].url}\">{/if}{\$res[i].name}{if \$res[i].urltype != 0}</a>{/if}
    {/if}
    {assign var=\"currentlevel\" value=\$res[i].level}
        {if \$res[i].object.haschild != 1}
            </li>
        {/if}
    
    {/if}
    {/section}
    </ul>";
$objdata['tpltype'] = "2";
$objdata['htmledit'] = "0";
$objdata['header'] = "";
$objdata['style'] = "";
$objdata['param'] = "";
$objdata['setting'] = "";
$objdata['config'] = "";
$objdata['doctype'] = "0";
$obj->createObject($objdata);
return $obj;
}
?>
