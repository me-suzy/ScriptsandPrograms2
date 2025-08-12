// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | actions.js                                                         |
// | Tree actions.                                                      |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

var treeElements = ["tree-moveUp", "tree-moveDown", "tree-moveLeft", "tree-moveRight", "tree-insert", "tree-remove"];
var treeTooltips = ["Move Up", "Move Down", "Move Left", "Move Right", "Insert", "Delete"];

function treeTooltipOn() { document.getElementById("tree-tooltip").innerHTML = treeTooltips[treeElements.indexOf(this.id)]; }
function treeTooltipOff() { document.getElementById("tree-tooltip").innerHTML = ""; }

for (var i = 0; i < treeElements.length; i++) {
    document.getElementById(treeElements[i]).onmouseover = treeTooltipOn;
    document.getElementById(treeElements[i]).onmouseout = treeTooltipOff;
}

function treeGetId() {
    if (tree.active) {
        return tree.active.substr("tree-".length);
    }
    return "";
}

function treeMoveUp() {
    if (tree.mayMoveUp() && httpSave("node.php?do=moveUp&id="+escape(treeGetId()))) {
        tree.moveUp();
    } else {
        alert('Cannot move up');
    }
}
function treeMoveDown() {
    if (tree.mayMoveDown() && httpSave("node.php?do=moveDown&id="+escape(treeGetId()))) {
        tree.moveDown();
    } else {
        alert('Cannot move down');
    }
}
function treeMoveLeft() {
    if (tree.mayMoveLeft() && httpSave("node.php?do=moveLeft&id="+escape(treeGetId()))) {
        tree.moveLeft();
    } else {
        alert('Cannot move left');
    }
}
function treeMoveRight() {
    if (tree.mayMoveRight() && httpSave("node.php?do=moveRight&id="+escape(treeGetId()))) {
        tree.moveRight();
    } else {
        alert('Cannot move right');
    }
}
function treeInsert() {
    document.getElementById("tree-insert-form").style.display = "block";
    document.getElementById("tree-insert-where-div").style.display = (tree.active ? "" : "none");
    if (tree.active) {
        var where = document.getElementById("tree-insert-where");
        if (tree.mayInsertInside()) {
            if (!where.options[2] && !where.options[3]) {
                where.options[2] = new Option("Inside at start", "inside_start");
                where.options[3] = new Option("Inside at end", "inside_end");
            }
        } else if (where.options[2] && where.options[3]) {
            where.options[2] = null;
            where.options[3] = null;
            where.options.length = 2;
        }
    }
}

/* only event - blur */
function treeInsertExecute() {
    var where = document.getElementById("tree-insert-where");
    var type = document.getElementById("tree-insert-type");
    var name = document.getElementById("tree-insert-name");
    name.value = name.value.trim();
    if (!name.value) {
        alert("Name is empty");
        return;
    }
    if (name.value.substr(-5) == ".html") {
        name.value = name.value.substr(0, name.value.length-5);
    }
    var id = name.value;
    if (type.value != "folder") {
        id = name.value + ".html";
    }
    if (tree.active) {
        switch (where.value) {
            case "before":
                if (httpSave("node.php?do=insertBefore&id="+escape(treeGetId())+"&name="+id+"&is_folder="+(type.value=="folder" ? 1 : 0))) {
                    tree.insertBefore(id, name.value, type.value);
                }
                break;
            case "after":
                if (httpSave("node.php?do=insertAfter&id="+escape(treeGetId())+"&name="+id+"&is_folder="+(type.value=="folder" ? 1 : 0))) {
                    tree.insertAfter(id, name.value, type.value);
                }
                break;
            case "inside_start":
                if (httpSave("node.php?do=insertInsideAtStart&id="+escape(treeGetId())+"&name="+id+"&is_folder="+(type.value=="folder" ? 1 : 0))) {
                    tree.insertInsideAtStart(id, name.value, type.value);
                }
                break;
            case "inside_end":
                if (httpSave("node.php?do=insertInsideAtEnd&id="+escape(treeGetId())+"&name="+id+"&is_folder="+(type.value=="folder" ? 1 : 0))) {
                    tree.insertInsideAtEnd(id, name.value, type.value);
                }
                break;
        }
    } else {
        if (httpSave("node.php?do=insert&id="+escape(treeGetId())+"&name="+id+"&is_folder="+(type.value=="folder" ? 1 : 0))) {
            tree.insert(id, name.value, type.value);
        }
    }
    name.value = "";
    this.blur();
}

function treeHideInsert() {
    var name = document.getElementById("tree-insert-name");
    name.value = "";
    document.getElementById("tree-insert-form").style.display = "none";
}

function treeRemove() {
    if (tree.mayRemove()) {
        if (confirm("Delete current node ?")) {
            if (httpSave("node.php?do=remove&id="+escape(treeGetId()))) {
                tree.remove();
                if (document.getElementById("tree-insert-form").style.display == "block") {
                    treeInsert();
                }
                clearTabs();
            }
        }
    } else {
        alert('Cannot remove. You have not selected any node or the folder is not empty.');
    }
}

document.getElementById("tree-moveUp").onclick    = treeMoveUp;
document.getElementById("tree-moveDown").onclick  = treeMoveDown;
document.getElementById("tree-moveLeft").onclick  = treeMoveLeft;
document.getElementById("tree-moveRight").onclick = treeMoveRight;

if (document.all && !/opera/i.test(navigator.userAgent)) {
    document.getElementById("tree-moveUp").ondblclick    = treeMoveUp;
    document.getElementById("tree-moveDown").ondblclick  = treeMoveDown;
    document.getElementById("tree-moveLeft").ondblclick  = treeMoveLeft;
    document.getElementById("tree-moveRight").ondblclick = treeMoveRight;
}

document.getElementById("tree-insert").onclick    = treeInsert;
document.getElementById("tree-remove").onclick    = treeRemove;

document.getElementById("tree-insert-button").onclick = treeInsertExecute;
document.getElementById("tree-insert-cancel").onclick = treeHideInsert;

tree.textClickListener.add(function() { if (document.getElementById("tree-insert-form").style.display == "block") { treeInsert(); } });
tree.textClickListener.add(function() {
    clearTabs();
    if (tree.active && tree.getActiveNode().isDocument()) {
        if (getCookie('openEditContent')) editContent();
        else documentInfo();
    }
});