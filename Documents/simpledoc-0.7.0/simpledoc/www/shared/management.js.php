<?php
require '../config.php';
?>
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | management.js                                                      |
// | Some functions, stuff for documents & folders management.          |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

function Fader(id) {
    this.start = function() {
        this.timerId = setInterval(change, 80);
    };
    this.stop = function() {
        clearInterval(this.timerId);
        this.opacity = 80;
        this.direction = 0;
        document.getElementById(this.id).style.opacity = 1;
        document.getElementById(this.id).style.MozOpacity = 1;
        document.getElementById(this.id).style.filter = 80;
    };
    function change() {
        self.opacity += (self.direction ? 10 : -10);
        document.getElementById(self.id).style.opacity = self.opacity/100;
        document.getElementById(self.id).style.MozOpacity = self.opacity/100;
        document.getElementById(self.id).style.filter = "alpha(opacity="+self.opacity+")";
        if (self.opacity == 20) { self.direction = 1; }
        if (self.opacity == 80) { self.direction = 0; }
    }
    var self = this;
    this.id = id;
    this.timerId = null;
    this.opacity = 80;
    this.direction = 0;
}

var tabsLoading = new Fader("tabs-loading");
var tabsSaving = new Fader("tabs-saving");

function tabsLoadingOn() { document.getElementById("tabs-loading").style.display = "block"; tabsLoading.start(); }
function tabsLoadingOff() { document.getElementById("tabs-loading").style.display = "none"; tabsLoading.stop(); }
function tabsSavingOn() { document.getElementById("tabs-saving").style.display = "block"; tabsSaving.start(); }
function tabsSavingOff() { document.getElementById("tabs-saving").style.display = "none"; tabsSaving.stop(); }

function documentInfo() {
    if (!tree.active || !tree.getActiveNode().isDocument()) {
        alert("You have to select a document in the Tree View to perform this action.");
        return;
    }
    checkContentSaved();
    //tabsLoadingOn();
    var html = httpLoad("tab-document-info.php?id="+escape(treeGetId()));
    if (html) {
        el('tab1').className = "tab-active";
        el('tab2').className = "tab right";
        el('tabs-data').innerHTML = html;
    }
    //tabsLoadingOff();
}

var ste = null;

function editContent() {
    if (!tree.active || !tree.getActiveNode().isDocument()) {
        alert("You have to select a document in the Tree View to perform this action.");
        return;
    }
    checkContentSaved();
    //tabsLoadingOn();
    var html = httpLoad("tab-edit-content.php?id="+escape(treeGetId()));
    if (html) {
        el('tab1').className = "tab";
        el('tab2').className = "tab-active right";
        el('tabs-data').innerHTML = html;
        ste = new SimpleTextEditor("body", "ste");
        ste.path = "shared/SimpleTextEditor/";
        ste.charset = "<?php echo $CONFIG['encoding']; ?>";
        ste.init();
        ste.frame.document.onkeydown = keyPress;
        // aaa!! next IE bug, editor images not loading :\
        if (document.all) {
            setTimeout(loadEditorImages, 50);
        }
        el('body-tmp').value = el('body').value;
    }
    //tabsLoadingOff();
}

function loadEditorImages() {
    var a = el('tabs-data').getElementsByTagName("img");
    for (var i = 0; i < a.length; ++i) {
        if (!a[i].complete && a[i].outerHTML) {
            a[i].outerHTML = a[i].outerHTML;
        }
    }
}

var savedTimerID = null;

function saveContent() {
    el('save-document').disabled = true;
    //el('tabs-data').style.visibility = "hidden";
    //el('tabs-data').style.zIndex = -1;
    //tabsSavingOn();
    var data = {"body": el('body').value}
    var save = httpSave("tab-save-content.php?id="+escape(treeGetId()), data);
    if ((typeof save == "boolean" && !save) || typeof save == "string") {
        alert("Unknown error, cannot save document.");
    } else {
        el("saved").innerHTML = "Saved successfuly on "+(new Date()+"<br>(this message will disappear in 3 seconds)");
        if (savedTimerID) clearTimeout(savedTimerID);
        savedTimerID = setTimeout(function(){ el("saved").innerHTML = ""; }, 3000);
    }
    //tabsSavingOff();
    //el('tabs-data').style.visibility = "visible";
    //el('tabs-data').style.zIndex = 1;
    el('save-document').disabled = false;
    el('body-tmp').value = el('body').value;
}

function isContentSaved() {
    if (ste && el("body") && el("save-document")) {
        ste.submit();
        if (el("body").value != el("body-tmp").value) {
            return false;
        }
    }
    return true;
}
function checkContentSaved() {
    if (!isContentSaved()) {
        if (confirm("Content has not been saved since last change.\nSave it ?")) {
            saveContent();
        }
    }
}

function clearTabs() {
    el('tab1').className = "tab";
    el('tab2').className = "tab right";
    el('tabs-data').innerHTML = "";
}

function keyPress(e) {
    if (!e) {
        if (!this.parentWindow) return;
        e = this.parentWindow.event;
    }
    if (e.ctrlKey && e.keyCode == 83) {
        if (el("body") && el("save-document")) {
            el("save-document").click();
        }
    }
    if (e.altKey && e.keyCode == 49) documentInfo();
    if (e.altKey && e.keyCode == 50) editContent();
}
document.onkeydown = keyPress;