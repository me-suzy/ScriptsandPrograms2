      // load the plugins that we will use
      // loading is necessary ONLY ONCE, regardless on how many editors you create
      // basically calling the following functions will load the plugin files as if
      // we would have wrote script src="..." but with easier and cleaner code

      HTMLArea.loadPlugin("TableOperations");
      HTMLArea.loadPlugin("CSS");
      HTMLArea.loadPlugin("ImageManager");
      //HTMLArea.loadPlugin("HTMLTidy");

      // Requires PERL
      // HTMLArea.loadPlugin("SpellChecker");
      // this function will get called at body.onload

      var editors = ["textarea"];
      var hiddenEditors = [];
      var editorLanguages = ["en","fr"];
      var editorToGenerate = Array();
      
      function delayedGenerate() {
        if (editorToGenerate.length) {
            var editor = editorToGenerate.shift();
            editor.generate();
            if (editorToGenerate.length) {
                // IMPORTANT: if we don't give it a timeout, the first editor will
                // not function in Mozilla.  Soon I'll think about starting to
                // implement some kind of event that will fire when the editor
                // finished creating, then we'll be able to chain the generate()
                // calls in an elegant way.  But right now there's no other solution
                // than the following.
                setTimeout(delayedGenerate, 500);
            }
         }
      }

      function initDocument() {
      	 showEditors(editors);
      	 try {
          	 // This creates a dependency to getCookie in advanced-options.js, so wrap in try.
          	 if (getCookie('advancedOptions') == true)
          	     showHiddenEditors();
        } catch (e) {}
      }

     function showHiddenEditors() {
        if (document.editNew.content_type[3].checked) {
            var x = hiddenEditors;
            hiddenEditors = Array(); // do this only once
            showEditors(x);
        }
     }



      function showEditors(editorList) {
       // cache these values as we need to pass it for both editors
       var css_plugin_args = {
          combos : [
            { label: "Syntax",
                         // menu text       // CSS class
              options: { "None"           : "",
                         "Code" : "code",
                         "String" : "string",
                         "Comment" : "comment",
                         "Variable name" : "variable-name",
                         "Type" : "type",
                         "Reference" : "reference",
                         "Preprocessor" : "preprocessor",
                         "Keyword" : "keyword",
                         "Function name" : "function-name",
                         "Html tag" : "html-tag",
                         "Html italic" : "html-helper-italic",
                         "Warning" : "warning",
                         "Html bold" : "html-helper-bold"
                       },
              context: "pre"
            },
            { label: "Info",
              options: { "None"           : "",
                         "Quote"          : "quote",
                         "Highlight"      : "highlight",
                         "Deprecated"     : "deprecated"
                       }
            }
          ]
        };
 
        //---------------------------------------------------------------------
        // GENERAL PATTERN
        //
        //  1. Instantitate an editor object.
        //  2. Register plugins (note, it's required to have them loaded).
        //  3. Configure any other items in editor.config.
        //  4. generate() the editor
        //
        // The above are steps that you use to create one editor.  Nothing new
        // so far.  In order to create more than one editor, you just have to
        // repeat those steps for each of one.  Of course, you can register any
        // plugins you want (no need to register the same plugins for all
        // editors, and to demonstrate that we'll skip the TableOperations
        // plugin for the second editor).  Just be careful to pass different
        // ID-s in the constructor (you don't want to _even try_ to create more
        // editors for the same TEXTAREA element ;-)).
        //
        // So much for the noise, see the action below.
        //---------------------------------------------------------------------
        var editor;
        for (var j=0; j<editorLanguages.length;j++) {
             for (var i = 0; i<editorList.length;i++) {
                var editorName = editorList[i]+"-"+editorLanguages[j];
                editor = new HTMLArea(editorName);
                
                // plugins must be registered _per editor_.  Therefore, we register
                // plugins for the first editor here, and we will also do this for the
                // second editor.
                editor.registerPlugin(TableOperations);
                editor.registerPlugin(CSS, css_plugin_args);
                //editor.registerPlugin('HtmlTidy');
        
                // editor.registerPlugin(SpellChecker);
        
                // custom config must be done per editor.  Here we're importing the
                // stylesheet used by the CSS plugin.
                editor.config.pageStyle = "@import url(custom.css);";
                editorToGenerate.push(editor);
            }
        }
        delayedGenerate();
};

// Back-End Contributed Code
function BE_chooseEditor(loadOnce) {

   // if IE 5.5 or W3C/Moz 1.3+ - Can't combine the two due to problems with reload() & IE
   // if((document.all && document.designMode) || (document.designMode)) { 
   
   // IE 5.5 - Eliminating reload() because it pulled down new form data
   if(document.all && document.designMode) {
      // WIKI Text
      if (document.editNew.content_type[0].checked) {
      } 
      // Plain Text
      if (document.editNew.content_type[1].checked) {
      } 
      // Extended Translation
      if (document.editNew.content_type[2].checked) {
      }
      // HTML or htmlArea
      if (document.editNew.content_type[3].checked) {
         // HTMLArea.replace('en'); HTMLArea.replace('fr');
         initDocument();
      } 

   // W3C/Moz 1.3+ - reload() without adjusting form data
   } else if(document.designMode) { 
      // WIKI Text
      if (document.editNew.content_type[0].checked) {
         if (loadOnce == 1) {
            loadOnce = 2;
            window.location.reload();
         }
      } 
      // Plain Text
      if (document.editNew.content_type[1].checked) {
         if (loadOnce == 1) {
            loadOnce = 2;
            window.location.reload();
         }
      } 
      // Extended Translation
      if (document.editNew.content_type[2].checked) {
         if (loadOnce == 1) {
            loadOnce = 2;
            window.location.reload();
         }
      }
      // HTML or htmlArea
      if (document.editNew.content_type[3].checked) {
         // HTMLArea.replace('en'); HTMLArea.replace('fr');
         initDocument();
      } 
   }
}
