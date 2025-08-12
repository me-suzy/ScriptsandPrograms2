
	var TreeObject;

	// ConfigParameter
	var left		   = 0; // Offset left
	var top 		   = 50; // Offset top
	var collapseButton = "img/collapse.gif";
	var expandButton   = "img/expand.gif";
	var shimImg 	   = "img/shim.gif";
	var LinePaging	   = 0;
	var LineSpacing    = 1;
	var ButtonWidth    = 16;
	var ButtonHeight   = 16;
	var leftSpan	   = 12;  // Für Blätter
	var leftSpanNodes  = [0,10,20,30,40,50]; // Für Knoten
	var cssSyle 	   = "Node";
	var treeBgColor    = "";

	function set_left (new_left) {this.left = new_left; }
	function set_top  (new_top)  {this.top = new_top; }

	function BrowserDetection() {
		var is_major = parseInt(navigator.appVersion);
		this.ver=navigator.appVersion;
		this.agent=navigator.userAgent;
		this.dom=document.getElementById?1:0;
		this.opera=this.agent.indexOf("Opera")>-1;
		this.ie5=(this.ver.indexOf("MSIE 5")>-1 && this.dom && !this.opera)?1:0;
		this.ie6=(this.ver.indexOf("MSIE 6")>-1 && this.dom && !this.opera)?1:0;
		this.ie4=(document.all && !this.dom && !this.opera)?1:0;
		this.ie=this.ie4||this.ie5||this.ie6;
		this.mac=this.agent.indexOf("Mac")>-1;
		this.ns6=(this.dom && parseInt(this.ver) >= 5) ?1:0;
		this.ie3 = (this.ver.indexOf("MSIE") && (is_major < 4));
		this.hotjava = (this.agent.toLowerCase().indexOf('hotjava') != -1)? 1:0;
		this.ns4=(document.layers && !this.dom && !this.hotjava)?1:0;
		this.bw=(this.ie6 || this.ie5 || this.ie4 || this.ns4 || this.ns6 || this.opera);
		this.ver3 = (this.hotjava || this.ie3);
		return this;
	}

	function TreeFormat( tree ) {
		this.init = function( ) {

			this.back	= new TreeBack(left, top, treeBgColor, 'clstree_back');
			this.e = new Image();
            this.e.src = collapseButton;
			this.e1 = new Image();
			this.e1.src = expandButton;
			this.e5 = new Image();
			this.e5.src = shimImg;
		}

		this.idn = function( lvl ) {
			var r = ( is_undefined(leftSpanNodes[lvl]) ) ? leftSpanNodes[0]*lvl : leftSpanNodes[lvl];
			return r;
		}

		this.init();
	}

	// ===============================================================================
	// Baum zeichnen
	function draw_me (tree) {
		tree.currTop = top;
		tree.maxHeight =0;
		tree.maxWidth=0;
		for (var i = 0; i < tree.rootNode.children.length; i++)    {
			   tree.rootNode.children[i].draw();
		}
		tree.fmt.back.resize(tree.maxWidth-left, tree.maxHeight - top);
		if (tree.ondraw != null) tree.ondraw();
	}

	function rebuildTree (tree) {
		var s = "";
		for (var i = 0; i < tree.Nodes.length; i++){
			s += tree.Nodes[i].init();
			//s += "\n";
		}
		document.write(s);

		var s_debug = "";
		s_debug = s.replace (/</g, "&lt;");
		s_debug = s_debug.replace (/>/g, "&gt;");
		s_debug = s_debug.replace (/"/g, "&quot;");
		//document.writeln ("<pre>" + s_debug + "</pre>");

		for (var i = 0; i < tree.Nodes.length; i++) {
			if (tree.ns4) {
				tree.Nodes[i].el = document.layers[tree.Nodes[i].id()+"d"];
				tree.Nodes[i].nb = tree.Nodes[i].el.document.images[tree.Nodes[i].id()+"nb"];
			} else {
				tree.Nodes[i].el = document.all? document.all[tree.Nodes[i].id()+"d"] : document.getElementById(tree.Nodes[i].id()+"d");
				tree.Nodes[i].nb = document.all? document.all[tree.Nodes[i].id()+"nb"] : document.getElementById(tree.Nodes[i].id()+"nb");
			 }
		}
	}

	// ===============================================================================
	// Knoten hinzufügen
	function addNode (tree, node) {
		var parentNode = node.parentNode;
		tree.Nodes = tree.Nodes.concat([node]);
		node.index = tree.Nodes.length - 1;
		if (parentNode == null) {
			tree.rootNode.children = tree.rootNode.children.concat([node]);
		}
		else
			parentNode.children = parentNode.children.concat([node]);
		return node;
	}

	function updateImages ( node ) {
		var srcB = node.expanded? expandButton : collapseButton;
		if (node.nb && node.nb.src != srcB) node.nb.src = srcB;
	}

	function parseNodes (tree, nodes) {
		// Knoten aus Array einlesen...
		var ind = 0;
		var par = null;

		function readOne( arr , tree)
		{
			if (is_undefined(arr)) return;
			var text = arr[0];
			var url = arr[1] == null? "": arr[1];
			var targ = arr[2] == null? "": arr[2];

			var node = addNode(tree, new TreeNode(tree, par, text, url, targ))
			var i = 3;
			while (!is_undefined(arr[i]))
			{
				par = node;
				readOne(arr[i], tree);
				i++;
			}
		}

		if (is_undefined(nodes) || is_undefined(nodes[0]) || is_undefined(nodes[0][0])) return;
		for (var i = 0; i < nodes.length; i++){
			par = null;
			readOne(nodes[i], tree);
		}
	}

	function WWMTree(nodes, expandIcon, collapseIcon) {

		TreeObject = this;
        expandButton   = expandIcon;
		collapseButton = collapseIcon;

		TreeObject.bw = new BrowserDetection();
		TreeObject.ns4 = TreeObject.bw.ns4;
		TreeObject.fmt = new TreeFormat(this);
		TreeObject.Nodes = new Array();

		TreeObject.rootNode = new TreeNode(null, "", "", "", null);

		TreeObject.rootNode.treeView = this;
		TreeObject.selectedNode = null;
		TreeObject.maxWidth = 0;
		TreeObject.maxHeight = 0;
		TreeObject.ondraw = null;

		parseNodes(TreeObject, nodes);
		rebuildTree(TreeObject);
		draw_me(TreeObject);
	}

	// ================================================================================
	// Funktionen zur Steuerung des Baums nachdem er gezeichent wurde
	//
	//
	// ================================================================================

	function expand_me( tree, index ) {

		var node = tree.Nodes[index];
		var pNode = node.parentNode ? node.parentNode : null;
		if (!is_undefined(node) && hasChildren(node)) {
			node.expanded = !node.expanded;
			updateImages(node);
			if (!node.expanded) { hideChildren(node); }

			draw_me(tree);
		}
	}

	function expand_all ( tree, bRedraw ) {
		for (var i = 0; i < tree.Nodes.length; i++){
			tree.Nodes[i].expanded = true;
			updateImages(tree.Nodes[i]);
		}
		if (bRedraw) draw_me(tree);
	}

	function collapse_all (tree, bRedraw) {
		for (var i = 0; i < tree.Nodes.length; i++){
			if (tree.Nodes[i].parentNode != tree.rootNode) {
				//tree.Nodes[i].show(false);
				show (tree.Nodes[i], false);
			}
			tree.Nodes[i].expanded = false;
			updateImages(tree.Nodes[i]);
		}
		if (bRedraw) draw_me(tree);
	}

	// ================================================================================
	// TreeNode
	// ================================================================================

	function hasChildren (node) {
		return node.children.length > 0;
	}

	function level (node) {
		var i = 0;
		while (node.parentNode != null){
		   i++;
		   node = node.parentNode;
		}
		return i;
	}

	function getContent (node) {
			function buttonSquare(node){

				var img = node.expanded ? expandButton : collapseButton;
				var w = ButtonWidth; var h = ButtonHeight;
				return '<td valign=\"middle\" width="'+w+'"><a dhref="javascript:empty_expression()" href="javascript:expand_me(TreeObject,'+node.index+')"><img name=\''+node.id()+'nb\' id=\''+node.id()+'nb\' src="' + img + '" width="'+w+'" height="'+h+'" border=0></a></td>\n';
			}
			function blankSquare(node, ww){
				var img = shimImg;
				return "<td width=\""+ww+"\"><img src=\"" + img + "\" width="+ww+" height=1 border=0></td>\n"
			}

			var s = '';
			var ll = level(node);
			s += '<table cellpadding='+LinePaging+' cellspacing='+LineSpacing+' border=0 class="clstree_back'+ll+'"><tr>';

			var idn = node.treeView.fmt.idn(ll);
			if (idn > 0)
				s += blankSquare(node, idn);
			s += hasChildren(node) ? buttonSquare(node) : blankSquare(node, leftSpan);
			if ( node.url == "") {
				s += node.treeView.ns4? '<td nowrap=\"1\" valign=middle><a class=\"Node\" href="javascript:empty_expression()" onclick="javascript:TreeObject.expand_me('+node.index+')">'+node.text+'</a></td></tr></table>' : '<td nowrap=\"1\"  valign=middle><a class=\"Node\" href="javascript:expand_me(TreeObject,'+node.index+')">'+node.text+'</a></td></tr></table>';;
			} else {
				if (node.target== "_jsopen") {
					//node.url = 'get_mail.php?dfdsf*';
					var myArray = node.url.split ("*");
					var myURL	= myArray[0];
					var myName	= myArray[1];
					var myParam = myArray[2];
					s += '<td nowrap=\"1\"	valign=middle><a class=\"Node\" href="javascript:open_me(\''+myURL+'\', \''+myName+'\',\''+myParam+'\');">'+node.text+'</a></td></tr></table>';
				}
				else
					s += '<td nowrap=\"1\"	valign=middle><a class=\"Node\" href="'+node.url+'" target="'+node.target+'" onclick="javascript:expand_me(TreeObject,'+node.index+')">'+node.text+'</a></td></tr></table>';
			}


			return s;
	}

	function moveTo (node, x,y) {
		if (node.treeView.ns4)
			node.el.moveTo(x,y);
		else {
			node.el.style.left=x;
			node.el.style.top=y;
		}
	}

	function show (node, sh) {
		if (node.visible == sh)
			return;
		node.visible = sh;
		var vis = node.treeView.ns4 ? (sh ? 'show': 'hide') : (sh ? 'visible': 'hidden');
		if (node.treeView.ns4)
			node.el.visibility=vis;
		else
		   node.el.style.visibility = vis;
	}

	function hideChildren (node) {
		show(node, false);
		for (var i = 0; i < node.children.length; i++)
				hideChildren(node.children[i]);
	}

	function getParentNode (index) {
		return TreeObject.Nodes[index].parentNode;
	}

	function TreeNode( treeView, parentNode , text, url, target){

		this.index = -1;
		this.treeView = treeView;
		this.parentNode = parentNode;
		this.text = text;
		this.url = url;
		this.target = target;
		this.expanded = false;
		this.children = new Array();

		this.init = function(){
			var s = "";
			if (this.treeView.ns4) {
				s = '<layer id="'+this.id()+'d" z-index="'+this.index+10+'" visibility="hidden">'+getContent(this)+'</layer>';
			} else {
				s = '<div id="'+this.id()+'d" style="position:absolute;visibility:hidden;z-index:'+this.index+10+';">'+getContent(this)+'</div>';
			}
			return s;
		}
		this.getH = function(){return this.treeView.ns4 ? this.el.clip.height : this.el.offsetHeight;}
		this.getW = function(){return this.treeView.ns4 ? this.el.clip.width : this.el.offsetWidth;}
		this.id = function(){return 'nttree'+this.index;}


		this.draw = function() {

			moveTo(this, left, this.treeView.currTop);
			if (left+this.getW() > this.treeView.maxWidth)
				this.treeView.maxWidth = left+this.getW();
			show(this, true);

			this.treeView.currTop += this.getH();
			if (this.treeView.currTop > this.treeView.maxHeight)
				this.treeView.maxHeight = this.treeView.currTop;
			if (this.expanded && hasChildren(this) )
				for (var i = 0; i < this.children.length; i++)
					this.children[i].draw();
		}
	}

	// ================================================================================
	// TreeBack
	// ================================================================================

	function TreeBack( aleft, atop, color)
	{
		this.bw = new BrowserDetection();
		this.ns4 = this.bw.ns4;

		 this.resize = function(w,h){
			if (this.ns4) { this.el.resizeTo(w,h); }
			else {
				this.el.style.width=w;
				this.el.style.height=h;
				if (this.r) this.el2.style.top=h+top-5;
			}
		}

		if (this.ns4) {
		   var bgc = color == ""? "" : ' bgcolor="'+color+'" ';
		   document.write('<layer '+bgc+' top="'+top+'" left="'+left+'" id="tree" z-index="0"></layer>');
		   this.el = document.layers['tree'];
		}
		else {
		   var bgc = this.color == ""? "" : " background-color:"+color+";";
		   document.write('<div id="tree" style="'+bgc+'position:absolute;z-index:0;top:'+top+'px;left:'+left+'px"></div>');
		   this.el = document.all? document.all['tree'] : document.getElementById('tree');
		}

	}

	// ================================================================================
	// Hilfsfunktionen
	// ================================================================================

	function empty_expression(){}
	function is_undefined(val) { return typeof(val) == 'undefined'; }


	function save_current (tree, session, doc) {
		var param = "|";
		for (var i = 0; i < tree.Nodes.length; i++){
			if (tree.Nodes[i].expanded)
			   param += i+"|";
		}
		link = "../../save_current_tree.php?current_tree="+param;
		doc.parent.executeframe.location.href=link;
	}

	function actualize (tree, session, doc) {
		var param = "|";
		for (var i = 0; i < tree.Nodes.length; i++){
			if (tree.Nodes[i].expanded)
			   param += i+"|";
		}
		//link = "left.php?show_nodes="+param;
		link = "index.php?command=navigation";
		document.location.href=link;
	}

	function open_me (myURL, myName, myParam) {
		window.open (myURL, myName, myParam);
	}
