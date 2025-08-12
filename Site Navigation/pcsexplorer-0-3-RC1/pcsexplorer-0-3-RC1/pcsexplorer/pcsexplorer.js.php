<!--
// --------------------------------------------------------------------------------
// PhpConcept Script - PcsExplorer
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - March 2005
// http://www.phpconcept.net
// --------------------------------------------------------------------------------
// Overview :
//   PcsExplorer is a Javascript/PHP script that compose a file or directory
//   selection window.
//
// Description :
//   PcsExplorer is composed of a Javascript file 'pcsexplorer.js', a PHP file
//   'pcsexplorer.php3' and a folder 'images'.
//   The PHP calling script must include the javascript file, and call PcsExplorer
//   like this (sample) :
//     <script language="javascript" src="pcsexplorer.js"></script>
//     <FORM  name="formulaire" method="POST">
//     File : <INPUT TYPE="TEXT" size=50 name="file"><br>
//     <INPUT TYPE="button" name="brows_button" value="Browse" onClick='PcjsOpenExplorer("path/pcsexplorer.php3", "forms.formulaire.file.value", "type=file", "calling_dir=< ? echo dirname($PATH_INFO); ? >", "start_dir=../..")'>
//     </FORM>
//   The arguments took by PcjsOpenExplorer() are described in the function header
//   bellow.
//   The arguments took by the PcsExplorer PHP script are described in
//   pcsexplorer.php3 file.
// --------------------------------------------------------------------------------
// CVS : $Id: pcsexplorer.js,v 1.0 2001/12/27 15:49:14 vblavet Exp $
// --------------------------------------------------------------------------------

  // ----- Global variable
  var v_win=0;
  var g_pcjsexplorer_url = '<?php echo dirname($_SERVER["PATH_INFO"]); ?>/pcsexplorer.php';

  // --------------------------------------------------------------------------------
  // Function : PcjsOpenExplorer(p_url, p_target, ... p_properties ...)
  // Description :
  // Parameters :
  //   p_url : The URL where sit the pcsexplorer.php3 PHP script. Should be
  //           an absolute value ("http://www.mysite.com/pcsexplorer.php3") or
  //           a relative value from the calling PHP script ("../pcsexplorer.php3")
  //   p_target : The javascript value target. For example for a form with name
  //              'my_form', with a TEXT INPUT, with name 'my_text', it should be
  //              "forms.my_form.my_text.value". Any javascript object contained
  //              in object "document" can be used.
  //   p_properties : A variable list of optional properties. Each element of this
  //                  must be a string, with the name of the property, equal the
  //                  value ("property_name=my_value").
  //                  Warning : No space " " is allowed defore and after the "=".
  //                  The following properties are defined :
  //                    type=file|dir : PcsExplorer explore files & dir,
  //                                    or only dir.
  //                    filter=my_filter : my_filter is a regular expression
  //                                       (future feature).
  //                    position=absolute|relative : absolute means absolute from
  //                                                 site home dir, relative means
  //                                                 relative to the calling script
  //                                                 position.
  //                    calling_dir=<dirname> : the absolute path from site root
  //                                            of the calling PHP script.
  //                    start_dir=<dirname> : the relative path from the calling PHP
  //                                          script where to start the exploration.
  // --------------------------------------------------------------------------------
  function PcjsOpenExplorer(p_url, p_target /*, p_properties, p_properties, ... */)
  {
    // ----- Check the number of arguments
    if (arguments.length < 2)
    {
      alert("Invalid number of arguments for PcjsOpenExplorer()");
      return false;
    }

    // ----- Compose the basic called URL
    var v_url = p_url+"?a_target="+escape(p_target);

    // ----- Look for optional properties
    for (i=2; i<arguments.length; i++)
    {
      // ----- Extract the property name and property value
      var v_item = arguments[i].split("=", 2);

      // ----- Complete the URL
      v_url = v_url+"&a_"+escape(v_item[0])+"="+escape(v_item[1]);
    }

    // ----- Set & calculate window size
    var v_width=340;
    var v_height=400;
    var v_left = (screen.width-v_width)/2;
    var v_top = (screen.height-v_height)/2;

    // ----- Set window properties
    var v_settings = 'width='+v_width+',height='+v_height+',top='+v_top+',left='+v_left+',scrollbars=no,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';

    // ----- Open window
    v_win = window.open(v_url,"PhpConceptExplorer",v_settings);

    // ----- Give focus to window
    v_win.focus();
    
    /*
    var myTitle = "This is a custom title";
    var myText = showModalDialog(v_url,
                                 myTitle,
                                 "resizable: yes; help: no; status: no; scroll: no; ");
    if (myText) { return(myText); }
    else { return('Pas de contenu'); }
    */

    
  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcjsExplorerConfigure(p_properties, p_properties, ...)
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PcjsExplorerConfigure(p_properties /*, p_properties, p_properties, ... */)
  {

  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcjsExplorerSetProperties(p_properties, p_properties, ...)
  // Description :
  // Parameters :
  // --------------------------------------------------------------------------------
  function PcjsExplorerSetProperties(p_properties /*, p_properties, p_properties, ... */)
  {

  }
  // --------------------------------------------------------------------------------

  // --------------------------------------------------------------------------------
  // Function : PcjsExplorer(p_properties, p_properties, ...)
  // Description :
  // Parameters :
  //   p_url : The URL where sit the pcsexplorer.php3 PHP script. Should be
  //           an absolute value ("http://www.mysite.com/pcsexplorer.php3") or
  //           a relative value from the calling PHP script ("../pcsexplorer.php3")
  //   p_target : The javascript value target. For example for a form with name
  //              'my_form', with a TEXT INPUT, with name 'my_text', it should be
  //              "forms.my_form.my_text.value". Any javascript object contained
  //              in object "document" can be used.
  //   p_properties : A variable list of optional properties. Each element of this
  //                  must be a string, with the name of the property, equal the
  //                  value ("property_name=my_value").
  //                  Warning : No space " " is allowed defore and after the "=".
  //                  The following properties are defined :
  //                    target_type=value|url
  //                    target="forms.my_form.my_text.value"
  //                    type=file|dir : PcsExplorer explore files & dir,
  //                                    or only dir.
  //                    filter=my_filter : my_filter is a regular expression
  //                                       (future feature).
  //                    position=absolute|relative : absolute means absolute from
  //                                                 site home dir, relative means
  //                                                 relative to the calling script
  //                                                 position.
  //
  //                    result_ref_dir=<dirpath> and
  //                    result_ref_dir_from_root=<dirpath> :
  //                               The relative path of the reference dir
  //                               from the DOCUMENT_ROOT
  //                    result_ref_dir_from_fs=<dirpath> :
  //                               The relative path of the reference dir
  //                               from the filesystem
  //
  //                    start_dir_from_root=<dirpath> :
  //                               Relative path from the DOCUMENT_ROOT
  //                               of the web site.
  //                    start_dir_from_fs=<dirpath> :
  //                               Start directory from file system.
  //                               Absolute path from the root filesystem.
  //                    start_dir=<dirpath> and
  //                    start_dir_from_result=<dirpath>
  //                               Relative path from the path indicated in
  //                               'result_ref_dir'.
  //                    root_dir=<dirpath>
  //                               The root path of the web site.
  //                    base_dir=<dirpath>
  //                               The open_base_dir restriction.
  //                               PcsExplorer will not navigate outside this
  //                               folder. Will not give a result outside it.
  //                               By default equal root_dir.
  //
  //
  // target_type = opener, input_text
  // target_argument_name =
  // target =
  // a_filter_extension = list of extension separated by a single blank, without the dot
  // --------------------------------------------------------------------------------
  function PcjsExplorer(p_properties /*, p_properties, p_properties, ... */)
  {
    // ----- Check the number of arguments
    if (arguments.length < 1)
    {
      alert("Invalid number of arguments for PcjsExplorer()");
      return false;
    }

    // ----- Set & calculate window size
    var v_width=340;
    var v_height=400;
    var v_left = (screen.width-v_width)/2;
    var v_top = (screen.height-v_height)/2;

    // ----- Set window properties
    var v_settings = 'width='+v_width+',height='+v_height+',top='+v_top+',left='+v_left+',scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=yes';

    // ----- Open window
    v_win = window.open("","PhpConceptExplorer",v_settings);

    // ----- Compose the 'virtual post' in order to give the action parameters to pcsexplorer PHP code
    v_win.document.write('<html><body>');
    //v_win.document.write('Formulaire<br>');
    v_win.document.write('<FORM  name="pcjsexplorerformulaire" method="POST" action="'+g_pcjsexplorer_url+'">');

    // ----- Set the parameters to transmit
    for (i=0; i<arguments.length; i++)
    {
      // ----- Extract the property name and property value
      var v_item = arguments[i].split("=");

      // ----- Set the arguments as hidden input in a form
      //       The use of a loop for v_item is to support argument=value=value-of-the-value
      v_win.document.write('<input type="hidden" name="a_'+escape(v_item[0])+'" value="');
      //v_win.document.write('<input type="hidden" name="a_'+v_item[0]+'" value="');
      for (j=1; j<v_item.length; j++)
      {
        v_win.document.write(v_item[j]);
        if (j != (v_item.length-1))
          v_win.document.write('=');
      }
      v_win.document.write('">');

    }
    //v_win.document.write('<input type="hidden" name="a_at_least_one_empty" value=""');
    //v_win.document.write('<input type="submit" name="Submit" value="Submit"');
    v_win.document.write('</FORM>');
    v_win.document.write('</body></html>');
    v_win.document.forms.pcjsexplorerformulaire.submit();

    // ----- Give focus to window
    v_win.focus();
  }
  // --------------------------------------------------------------------------------


// -->

