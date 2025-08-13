<script LANGUAGE="JavaScript">
        var format="HTML"
        var sStatusBarMes = "<?=$lang['current_wysiwyg']?>";
        var tmpFontFamily
        var tmpFontSize
        var tmpBgColor1
        var tmpText
        var tmpBgColor3
        var framingStatus = 0
        var imageWin
        var tableWin
        var linkWin
        var zoomValue = 100;

        var inputMethod = <?=$input_method?>;
        var useRelPaths = <?=$option[6]?>;
        var useCSS = "<?=$option[7]?>";
        var Ok = "false"
        var name =  navigator.appName
        var version =  parseFloat(navigator.appVersion)
        var platform = navigator.platform

        if (platform == "Win32" && name == "Microsoft Internet Explorer" && version >= 4){
                Ok = "true";
        } else {
                alert('<?=$lang[activex_warning]?>');
        }


        <?
        if(($input_method == 3) || ($input_method == 4)) {
                echo "document.write('<form name=\"fHtmlEditor\" method=\"POST\" action=\"_editor.php\" style=\"margin:0px;\">'); \n";
                echo "document.write('<input type=hidden name=\"op\" value=\"save2db\">'); \n";
                echo "document.write('<textarea name=\"EditorValue\" style=\"display: none;\"></textarea>'); \n";
                echo "document.write('<input type=hidden name=\"dbname\" value=\"".$dbname."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbtable\" value=\"".$dbtable."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbfield\" value=\"".$dbfield."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbai\" value=\"".$dbai."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbrecord\" value=\"".$dbrecord."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbsafe\" value=\"".$dbsafe."\">'); \n";
                echo "document.write('<input type=hidden name=\"dbreturn\" value=\"".$dbreturn."\">'); \n";
                echo "document.write('</form>'); \n";
        } else if($input_method == 5) {
                echo "document.write('<form name=\"fHtmlEditor\" method=\"POST\" action=\"_editor.php\" style=\"margin:0px;\">'); \n";
                echo "document.write('<input type=hidden name=\"op\" value=\"save2file\">'); \n";
                echo "document.write('<textarea name=\"EditorValue\" style=\"display: none;\"></textarea>'); \n";
                echo "document.write('<input type=hidden name=\"filename\" value=\"".$filename."\">'); \n";
                echo "document.write('<input type=hidden name=\"filereturn\" value=\"".$filereturn."\">'); \n";
                echo "document.write('</form>'); \n";
        } else {
                echo "document.write('<form name=\"fHtmlEditor\" method=\"POST\" action=\"_editor.php\" style=\"margin:0px;\">'); \n";
        }
        ?>


function setFocus() {
        textEdit.focus()
}


function Swap_it() {
        if (format=="HTML") {

                if(inputMethod == 5) {
                        content = textEdit.document.documentElement.outerHTML;
                } else {
                        content = textEdit.document.body.innerHTML;
                }

                tmpFontFamily = textEdit.document.body.style.fontFamily;
                tmpFontSize = textEdit.document.body.style.fontSize;
                tmpBgColor1 = textEdit.document.body.bgColor;
                tmpText = textEdit.document.body.text;
                tmpLineHeight = textEdit.document.body.style.lineHeight;
                tmpBgColor3 = document.body.style.backgroundColor;

                if(useRelPaths == 1) {
                        content = content.replace(new RegExp('<?=$abs_path?>','g'),'.');
                }

                content = content.replace(new RegExp('&amp;','g'),'&');
                textEdit.document.body.innerText = content

                colorize();

                textEdit.document.body.style.fontFamily = "Verdana";
                textEdit.document.body.style.fontSize = "11px";
                textEdit.document.body.style.bgColor = "#FFFFFF";
                textEdit.document.body.style.text = "#000000";
                textEdit.document.body.style.lineHeight = "18px";
                textEdit.document.body.style.backgroundColor = "#FFFFFF";

                format="Text";
                sStatusBarMes = "<?=$lang['current_html']?>";
        } else {
                if(tmpFontFamily == "") {
                        textEdit.document.body.style.removeAttribute("fontFamily");
                } else {
                        textEdit.document.body.style.fontFamily = tmpFontFamily;
                }
                if(tmpFontSize == "") {
                        textEdit.document.body.style.removeAttribute("fontSize");
                } else {
                        textEdit.document.body.style.fontSize = tmpFontSize;
                }
                if(tmpBgColor1 == "") {
                        textEdit.document.body.style.removeAttribute("bgColor");
                } else {
                        textEdit.document.body.style.bgColor = tmpBgColor1;
                }
                if(tmpBgColor3 == "") {
                        textEdit.document.body.style.removeAttribute("backgroundColor");
                } else {
                        textEdit.document.body.style.backgroundColor = tmpBgColor3;
                }
                if(tmpText == "") {
                        textEdit.document.body.style.removeAttribute("text");
                } else {
                        textEdit.document.body.style.text = tmpText;
                }
                if(tmpLineHeight == "") {
                        textEdit.document.body.style.removeAttribute("lineHeight");
                } else {
                        textEdit.document.body.style.lineHeight = tmpLineHeight;
                }

                textEdit.document.body.innerHTML = textEdit.document.body.innerText;

                format="HTML";
                sStatusBarMes = "<?=$lang['current_wysiwyg']?>";
                setFocus();
        }
        updateStatusBar();
}

        function doFormat(what) {
                if(what == "FontName") {
                        if(arguments[1] != 1){
                                execCommand(what, arguments[1]);
                                document.all.font.selectedIndex = 0;
                        }
                } else if(what == "FontSize") {
                        if(arguments[1] != 0 ){
                                execCommand(what, arguments[1]);
                                document.all.size.selectedIndex = 0;
                        }
                } else if(what == "formatBlock") {
                        if(arguments[1] != 0 ){
                                execCommand(what, arguments[1]);
                                document.all.format.selectedIndex = 0;
                        }
                } else {
                        execCommand(what, arguments[1]);
                }
        }

        function execCommand(command) {
                setFocus()
                        if (format=="HTML") {
                        var edit = textEdit.document.selection.createRange()
                                if (arguments[1]==null)
                                edit.execCommand(command)
                                else
                                edit.execCommand(command,false,arguments[1])
                                edit.select()
                                setFocus()
                        } else alert("<?=$lang['alert_wysiwyg_image']?>")
        }

        function applyStyle(style) {
                setFocus()
                        if (format=="HTML") {
                                var style=document.all.css_style.options[document.all.css_style.selectedIndex].value;
                                if(style == "update_list") {
                                        updateCSS();
                                } else {
                                        var seledit = textEdit.document.selection.createRange().text;
                                        if(seledit != "") {
                                                var editStyle = '<SPAN CLASS="'+ style +'">'+ seledit +'</SPAN>';
                                                textEdit.document.selection.createRange().pasteHTML(editStyle);
                                        }
                                }
                        } else alert("<?=$lang['alert_wysiwyg_image']?>")
                document.all.css_style.selectedIndex = 0;
        }

        function setFontSize(howmuch) {
                setFocus()
                        if (format=="HTML") {
                                var seledit = textEdit.document.selection.createRange().text
                                var editTag = '<SPAN STYLE="FONT-SIZE:' + howmuch + 'pt;">'+ seledit +'</SPAN>';
                                textEdit.document.selection.createRange().pasteHTML(editTag)
                        } else alert("<?=$lang['alert_wysiwyg_image']?>")
                document.all.size.selectedIndex = 0;
        }

        function defWindows(subH) {
                var totalH = document.body.offsetHeight
                var editH = totalH - 96
                document.all['textEdit'].height = editH-subH;
                document.all['subMenu'].height = subH-6;
        }

        function blockDefault() {
                defWindows(22);
                var subHTML = ""
                subHTML += "<body topmargin=0 leftmargin=0>"
                subHTML += "<table cellspacing=3 cellpadding=0 border=0>"
                subHTML += "<tr><td>"
                subHTML += "<font style=\"font: 9.9px verdana; color: #000000;\">yaas Editor <? echo str_replace('_','.',$settings[version]); ?> (build <?=$settings[build]?>) &nbsp&nbsp - <?=$settings['copyright']?> All Rights Reserved&nbsp; - <? //$lang['language'] ?>  <a href=\"mailto:<?=$lang['translator_email']?>\" target=\"_blank\"><font color=\"#CE0000\"><?=$lang['translator']?></font></a>"
                subHTML += "</font></td></tr></table>"
                subHTML += "</body>"
                subMenu.document.open();
                subMenu.document.write(subHTML);
                subMenu.document.close();
                setFocus();
        }


        function blockLocIMG() {
                var leftPos = (screen.availWidth - 520) / 2
                var topPos = (screen.availHeight - 380) / 2
                if (window.frames('textEdit').document.selection.type == "Control") {
                        var imageWin;
                        var myRange = window.frames('textEdit').document.selection.createRange();
                        if(myRange(0).tagName.toUpperCase() == "IMG") {
                                blockDefault();
                                launchWindow(1,'_editor.php?op=image_edit&w=edit',leftPos,topPos,520,380);
                        } else {
                                launchWindow(1,'_editor.php?op=image_edit',leftPos,topPos,520,380);
                        }
                } else {
                        launchWindow(1,'_editor.php?op=image_edit',leftPos,topPos,520,380);
                }
        }

        function launchWindow(win,path,leftPos,topPos,w,h) {
                if(win==1) {
                        imageWin = window.open(path,'','width=' + w +',height=' + h +',help=no,center=yes,status=yes,scroll=no,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
                }
                if(win==2) {
                        tableWin = window.open(path,'','width=' + w +',height=' + h +',help=no,center=yes,status=yes,scroll=no,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
                }
                if(win==3) {
                        linkWin = window.open(path,'','width=' + w +',height=' + h +',help=no,center=yes,status=yes,scroll=no,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
                }
        }


        function blockTable() {
                CssFramingOn();
                var leftPos = (screen.availWidth - 580) / 2
                var topPos = (screen.availHeight - 340) / 2
                if (window.frames('textEdit').document.selection.type == "Control") {
                        var myRange = window.frames('textEdit').document.selection.createRange();
                        if(myRange(0).tagName.toUpperCase() == "TABLE") {
                                launchWindow(2,'_editor.php?op=table_edit&tab=preload',leftPos,topPos,580,340);
                        } else {
                                launchWindow(2,'_editor.php?op=table_edit',leftPos,topPos,580,340);
                        }
                } else {
                        launchWindow(2,'_editor.php?op=table_edit',leftPos,topPos,580,340);
                }

        }


        function blockCreateLink() {
                var leftPos = (screen.availWidth - 500) / 2
                var topPos = (screen.availHeight - 300) / 2
                if (format=="HTML") {
                        if (window.frames('textEdit').document.selection.type != "Control") {
                                launchWindow(3,'_editor.php?op=link_select&method=<?=$option[6]?>',leftPos,topPos,500,300);
                        } else {
                                oRange = window.frames('textEdit').document.selection.createRange();
                                        if (oRange(0).tagName.toUpperCase() == "IMG") {
                                                launchWindow(3,'_editor.php?op=link_select&method=<?=$option[6]?>',leftPos,topPos,500,300);
                                        } else {
                                                alert("<?=$lang[alert_selection]?>");

                                        }
                        }
                } else alert("<?=$lang['alert_wysiwyg_table']?>")
        }

        function blockPicker(which) {
                defWindows(102);
                window.frames.subMenu.location.href = '_editor.php?op=color_picker&which=' + which
        }

        function editTable() {
                if (format=="HTML") {
                        if (window.frames('textEdit').document.selection.type != "Control") {
                                var leftPos = (screen.availWidth - 580) / 2
                                var topPos = (screen.availHeight - 370) / 2
                                launchWindow(2,'_editor.php?op=table_cell',leftPos,topPos,580,370);
                        } else alert("<?=$lang[no_cell_selected]?>");
                } else alert("<?=$lang['alert_wysiwyg_table']?>")
        }

        function newDoc() {
                textEdit.document.open();
                textEdit.document.write("");
                textEdit.document.close();
<?php
        if($option[7] != "") {
                echo "\t\ttextEdit.document.createStyleSheet('".$option[7]."')\n";
                echo "\t\tpopulateCSS();\n";
        }
?>
                setFocus();
        }


        function initEditor() {
        <?
        if($input_method == 1) {
                echo "pool.document.body.innerHTML = window.opener.document.".$formname.".".$inputname.".value; ";

        } elseif($input_method == 2) {
                echo "pool.document.body.innerHTML = window.opener.document.getElementById(\"".$id."\").value; ";

        }
        ?>

                textEdit.document.designMode="On";
                textEdit.document.open();

                if(inputMethod == 5) {
                        var htmlString = pool.document.documentElement.outerHTML;
                } else {
                        var htmlString = pool.document.body.innerHTML;
                }

                textEdit.document.write(htmlString);
                textEdit.document.close()
                setFocus();

<?php
                if($option[7] != "") {
                        echo "\t\ttextEdit.document.createStyleSheet('".$option[7]."');\n";
                        echo "\t\tsetTimeout(\"populateCSS();\",200);\n";
                }
?>
                setFocus();
                updateStatusBar();
        }

        function copyValue() {
                var theHtml = document.frames("textEdit").document.body.innerHTML
                pool.document.body.innerHTML = theHtml;

        }

        function filteredOutput(pool) {
                if(useRelPaths == 1) {
                        pool = pool.replace(new RegExp('<?=$abs_path?>','g'),'.');
                        pool = pool.replace(new RegExp('&amp;','g'),'&');
                }

                return pool
        }


        function OnFormSubmit() {
                if (format=="HTML") {
                        if(confirm("<?=$lang['confirm_submit']?>")) {
                                if(framingStatus == 1) CssFramingOff();
                                copyValue();
                                closeChildWins()

                                <?
                                if($input_method == 1) {
                                        echo "window.opener.document.".$formname.".".$inputname.".value = filteredOutput(pool.document.body.innerHTML); ";
                                        echo "self.close(); ";
                                } else if($input_method == 2) {
                                        echo "window.opener.document.getElementById(\"".$id."\").value = filteredOutput(pool.document.body.innerHTML); ";
                                        echo "self.close(); ";
                                } else if(($input_method == 3) || ($input_method == 4) || ($input_method == 5)) {
                                        echo "document.fHtmlEditor.EditorValue.value = filteredOutput(pool.document.body.innerHTML); ";
                                        echo "document.fHtmlEditor.submit(); \n";

                                }
                                ?>

                        }
                } else alert("<?=$lang['alert_wysiwyg_save']?>")

        }

        function populateCSS() {
                if (window.frames('textEdit').document.styleSheets && (window.frames('textEdit').document.readyState == 'complete')) {

                        var otitle, title="";
                        for(var i=0; i<textEdit.document.styleSheets(0).rules.length; i++) {
                                var title = textEdit.document.styleSheets(0).rules.item(i).selectorText;
                                        if (title != "" && title != otitle) {

                                                if(!approvedCSS(title)) {
                                                        var title2 = title.replace(".","");
                                                        document.fHtmlEditor.css_style.options[document.fHtmlEditor.css_style.options.length] = new Option(title, title2);
                                                }
                                                otitle=title;
                                        }
                        }
                }
        var newOption = new Option('update CSS list','update_list');
        document.fHtmlEditor.css_style.options[document.fHtmlEditor.css_style.options.length] = newOption;
        newOption.className = "select_update";
        }

        function updateCSS() {
                // clear current style selector
                document.fHtmlEditor.css_style.options.length = 0;
                document.fHtmlEditor.css_style.options[document.fHtmlEditor.css_style.options.length] = new Option('CSS Style','');
                populateCSS();
        }

        function approvedCSS(title) {
                var o;

                o = title.indexOf(title.toLowerCase('a.'))
                if(o > -1) return false;

                var approvalArr = new Array("#","input","select","option","textarea","button","body","thead","tbody","tfoot","table","th","tr","td","font","span","form","fieldset","legend","hr","li","ol","ul","p","h1","h2","h3","h4","h5","h6");

                for(var i=0; i<approvalArr.length; i++) {
                        o = title.indexOf(title.toLowerCase(approvalArr[i]))
                        if(o == 0) return false;
                }
                return true;
        }


        function colorTag(htmlcode,tag,color) {
                        var tagColor = "<font color=" + color +">";

                        // opening upper case
                        var ouc = new RegExp("&lt;"+tag,"g");
                        htmlcode = htmlcode.replace(ouc,""+tagColor+"&lt;"+tag+"");

                        // opening lower case
                        var olc = new RegExp("&lt;"+tag.toLowerCase(),"g");
                        htmlcode = htmlcode.replace(olc,""+tagColor+"&lt;"+tag+"");

                        // closing upper case
                        var cuc = new RegExp("&lt;/"+tag,"g");
                        htmlcode = htmlcode.replace(cuc,""+tagColor+"&lt;/"+tag+"");

                        // closing lower case
                        var clc = new RegExp("&lt;/"+tag.toLowerCase(),"g");
                        htmlcode = htmlcode.replace(clc,""+tagColor+"&lt;/"+tag+"");

                        return htmlcode;
        }

        function colorize() {
                var hc = textEdit.document.body.innerHTML;

                // closing tags - add ending font tag
                hc = hc.replace(/&gt;/g,"&gt;</font>");

                var tagsStandard = new Array("HTML","HEAD","TITLE","BASE","META","BODY","DIV","H1","H2","H3","H4","H5","H6","SPAN","FONT","STRONG","EM","U","P","BR","HR","LI","UL","OL");
                // note <B and <BR are mixing together ... generates duplicite tag
                for(i=0; i<tagsStandard.length; i++) {
                        hc = colorTag(hc,tagsStandard[i],'mediumblue');
                }

                var tagsTable = new Array("TABLE","TBODY","TH","TR","TD");
                // note <B and <BR are mixing together ... generates duplicite tag
                for(i=0; i<tagsTable.length; i++) {
                        hc = colorTag(hc,tagsTable[i],'darkblue');
                }

                var tagsForm = new Array("FORM","INPUT","SELECT","OPTION","BUTTON","TEXTAREA","FIELDSET","LEGEND");
                // note <B and <BR are mixing together ... generates duplicite tag
                for(i=0; i<tagsForm.length; i++) {
                        hc = colorTag(hc,tagsForm[i],'teal');
                }

                        // images
                        hc = colorTag(hc,'IMG','forestgreen');

                        // links
                        hc = colorTag(hc,'A','purple');

                        //javascripts
                        hc = colorTag(hc,'SCRIPT','green');

                        // PHP scripts
                        var tc = "<font color=deepskyblue>"
                        hc = hc.replace(/&lt;\?/g,tc+"&lt;?");

                textEdit.document.body.innerHTML = hc;
        }


        function switchFraming() {
                if(framingStatus == 0) {
                        CssFramingOn();
                        framingStatus = 1;
                        textEdit.focus();
                        } else {
                        CssFramingOff();
                        framingStatus = 0;
                        location.reload;
                        textEdit.focus();
                }
        }

        function CssFramingOn() {
                var tEdit;
                if (!textEdit.document.body.BehaviorStyleSheet){
                        textEdit.document.body.BehaviorStyleSheet = textEdit.document.createStyleSheet()
                }
                tEdit = textEdit.document.body.BehaviorStyleSheet;
                tEdit.addRule('TD','border:1px dashed silver;');
                tEdit.addRule('Table','border:1px dashed silver;');
                tEdit.addRule('TD.claim','border:1px dashed #CE0000;');
                tEdit.addRule('Table.claim','border:1px dashed #CE0000;');
        }

        function CssFramingOff() {
                var tEdit;
                tEdit = textEdit.document.body.BehaviorStyleSheet;
                tEdit.removeRule('TD','border:1px dashed silver;');
                tEdit.removeRule('Table','border:1px dashed silver;');
                tEdit.removeRule('TD.claim','border:1px dashed #CE0000;');
                tEdit.removeRule('Table.claim','border:1px dashed #CE0000;');
        }

        function closeChildWins() {
                if (imageWin) imageWin.close()
                if (linkWin) linkWin.close()
                if (tableWin) tableWin.close()
        }


        function Help() {
                window.open("http://localhost/editbox/help.php?host=<?=$PHP_URI?>","wHelp", "toolbar=0, scrollbars=yes, width=666, height=460");
        }

        function Update() {
                window.open("http://localhost/update.php?version=<?=$version?>&build=<?=$build?>","wUpdate", "toolbar=yes, scrollbars=yes, statusbar=yes, width=640, height=480");
        }

        function watchKeys() {
                if (event.keyCode == 107 && event.ctrlKey){
                        zoomin();
                }
                if (event.keyCode == 109 && event.ctrlKey){
                        zoomout();
                }
                if (event.keyCode == 96 && event.ctrlKey){
                        zoomrestore();
                }
                updateStatusBar();
        }

        function zoomin(){
                if(textEdit.document.body.style.zoom!=0) {
                        textEdit.document.body.style.zoom*=1.2;
                        zoomValue*=1.2;
                } else {
                        textEdit.document.body.style.zoom=1.2;
                        zoomValue=100*1.2;
                }
        }

        function zoomout(){
                if(textEdit.document.body.style.zoom!=0) {
                        textEdit.document.body.style.zoom = textEdit.document.body.style.zoom / 1.2;
                        zoomValue = zoomValue / 1.2;
                } else {
                        textEdit.document.body.style.zoom = textEdit.document.body.style.zoom / 1.2;
                        zoomValue = 100 / 1.2;
                }
        }

        function zoomrestore(){
                if(textEdit.document.body.style.zoom!=0) {
                        textEdit.document.body.style.zoom*=0;
                        zoomValue=100;
                } else {
                        textEdit.document.body.style.zoom=0;
                        zoomValue=100;
                }
        }

        function updateStatusBar() {
                statusMessage = window.status = sStatusBarMes;
                statusMessage += "   |   zoom: " + Math.round(zoomValue) + "%";
                window.status = statusMessage;
        }

</script>