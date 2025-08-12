<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
// do not cache
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    	        // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate");  	        // HTTP/1.1
header ("Pragma: no-cache");                          	        // HTTP/1.0
 
// Restrict acces to this page

//this page clearance
$arr = array (  
  '0' => 'ADMIN',
  '1' => 'ARTICLES_MASTER',
  '2' => 'COMMENTS_MASTER',  
  );
define('CONSTANT_ARRAY',serialize($arr));

// check
include 'restrict_access.php';

//configuration file
include 'config_inc.php'; 
?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link href="cms_style.css" rel="stylesheet" type="text/css">

<style type="text/css">
body
{
background-image: 
url("images/app/page_bg.jpg");
background-repeat: 
repeat-y;
background-attachment: 
fixed;
}
</style>

<script language="Javascript1.2">
//function to print this page
function printpage() {
window.print();  
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" leftmargin="22" rightmargin="0"  topmargin="0" marginwidth="0" marginheight="0" class="maintext">

<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
  <tr> 
    <td class="titletext0">Help:<span class="titletext0blue"> End User License 
      Agreement</span></td>
  </tr>
</table>
<br>
<br>
<table width="700" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr>
    <td align="left" valign="top"><span class="maintext"> </span> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td valign="top"><span class="titletext0">100janCMS Articles Control<br>
            End User License Agreement (EULA)</span><span class="titletext0blue"><br>
            </span><span class="maintext"><strong>One (1) copy installation license</strong></span></td>
          <td align="right" valign="top"><a href="javascript:printpage();" class="2"><img src="images/app/printer32.jpg" alt="print this page" width="33" height="33" border="0" align="top"></a></td>
        </tr>
      </table>
      <span class="maintext"><br>
      </span> <strong>100janCMS Articles Control</strong> is licensed per copy.<br>
      <br>
      This End User License Agreement lets you install and use one (1) copy of 
      the &quot;100janCMS Articles Control&quot; product. If you want to install 
      and use more than one copy of the &quot;100janCMS Articles Control&quot; 
      product you are required to purchase more licenses, equal to the number 
      of copies you want to use. You may install multiple copies on the same computer 
      and/or domains, complying with the term that you purchase license for each 
      copy that you will use. You may use your own logos with the application, 
      however all of our copyright notices must remain visible. YOU CANNOT RESELL, 
      RENT, REVERSE ENGINEER, DECOMPILE, DISASSAMBLE, REBRAND OR LEASE THIS APPLICATION. 
      Doing so without our written permission will result in legal prosecution.<br> <br>
      <strong>IMPORTANT-READ CAREFULLY:</strong> This End User License Agreement 
      (in further text refered to as &quot;EULA&quot;) is a legal agreement between 
      you and Stojan Vujkov (in further text refered to as &quot;STOJAN VUJKOV&quot;, 
      as a owner and a holder of all product rights), for the product identified 
      above, which includes computer software and may include associated media, 
      images, printed materials, and online or electronic documentation (in further 
      text refered to as &quot;SOFTWARE PRODUCT&quot;). By ordering, paying for, 
      downloading, installing, copying, modifying, storing, displaying or otherwise 
      using the SOFTWARE PRODUCT, you agree to be bound by the terms of this EULA. 
      If you do not agree to the terms of this EULA, you may not use the SOFTWARE 
      PRODUCT.<br>
      <br>
      <strong><br>
      SOFTWARE PRODUCT LICENSE<br>
      </strong>Copyright laws and international copyright treaties, as well as 
      other intellectual property laws and treaties protect the SOFTWARE PRODUCT. 
      The SOFTWARE PRODUCT is licensed, not sold. The SOFTWARE PRODUCT is owned 
      and copyrighted by STOJAN VUJKOV. This license does not give you title or 
      ownership over the SOFTWARE PRODUCT and is not a sale of any rights in the 
      Software.<br>
      <br>
      <br>
      <strong>1. INTRODUCTION.<br>
      </strong>The SOFTWARE PRODUCT consist of any and all of the following components: 
      the core files, the database schema, image files, documentation, online 
      files. This EULA grants your rights with respect to the SOFTWARE PRODUCT. 
      The following terms govern your use of the SOFTWARE PRODUCT, unless you 
      have a separate written agreement with STOJAN VUJKOV.<br>
      <br>
      <br>
      <strong>2. GRANT OF LICENSE.</strong> <br>
      This EULA grants you the following rights:<br>
      <br>
      <strong>2.1. STANDARD USE.</strong><br>
      STOJAN VUJKOV grants you a license to install and use one (1) copy of the 
      SOFTWARE PRODUCT. You are required to provide information to STOJAN VUJKOV 
      that contains the name of the company/person using SOFTWARE PRODUCT and 
      domain name or a local network name on which the SOFTWARE PRODUCT will be 
      installed and used. &quot;Use&quot; means storing, loading, installing, 
      executing or displaying the SOFTWARE PRODUCT. STOJAN VUJKOV will provide 
      free installation of the SOFTWARE PRODUCT during a time period of sixty 
      (60) days starting at the same date of the SOFTWARE PRODUCT is purchased, 
      complying with the system requirements specified by STOJAN VUJKOV for SOFTWARE 
      PRODUCT.<br>
      <br>
      <strong>2.2. MODIFICATION USE.</strong><br>
      STOJAN VUJKOV grants you a limited license, complying with the modification 
      requirements stated below, to modify and use the SOFTWARE PRODUCT. The modified 
      SOFTWARE PRODUCT should not be made available for sale, resale, distribution, 
      redistribution or publication in any way under any circumstances. STOJAN 
      VUJKOV will not provide technical support nor upgrades for the modified 
      SOFTWARE PRODUCT. STOJAN VUJKOV will not provide installation of the modified 
      SOFTWARE PRODUCT. You may use number of copies of modified SOFTWARE PRODUCT 
      equal to the number of copies you purchased from STOJAN VUJKOV.<br>
      <br>
      <strong>2.2.1 MODIFICATION USE REQUIREMENTS.</strong> <br>
      If you use the Modification Use rights described above, you agree to:<br>
      &#8226; Only exercise the modification rights described above on the SOFTWARE 
      PRODUCT installed on the company/person registered with STOJAN VUJKOV for 
      use of the SOFTWARE PRODUCT. <br>
      &#8226; Limit the modified SOFTWARE PRODUCT to the same parameters as unmodified 
      SOFTWARE PRODUCT (number of copies, specified in this EULA). <br>
      &#8226; Not use STOJAN VUJKOV's name, logo, or trademarks to identify the 
      modified SOFTWARE PRODUCT without STOJAN VUJKOVs written permission. You 
      must clearly state that it is a modified version of original SOFTWARE PRODUCT, 
      with respect to SOFTWARE PRODUCT.<br>
      &#8226; Reproduce and maintain all STOJAN VUJKOV copyright notices in the 
      original SOFTWARE PRODUCT on all modifications, yet stating that it is modified 
      version. <br>
      &#8226; Indemnify, hold harmless, and defend STOJAN VUJKOV from and against 
      any claims or lawsuits, including attorney's fees, that arise or result 
      from use of the modified SOFTWARE PRODUCT. <br>
      &#8226; Not disable any licensing control features of the SOFTWARE PRODUCT, 
      if applicable. <br>
      &#8226; Not modify, alter, reverse engineer nor change in any other way 
      any and all of the following files: help_eula.php<br>
      <br>
      <strong>2.2.2 SOFTWARE PRODUCT IMAGE FILES.</strong> <br>
      If you use any of the Image Files, you agree to:<br>
      &#8226; Not use the Image Files to disparage STOJAN VUJKOV, its products 
      or services or for promotional goods or for products which, in STOJAN VUJKOV's 
      sole judgment, may diminish or otherwise damage STOJAN VUJKOV's goodwill 
      in the SOFTWARE PRODUCT including but not limited to uses which could be 
      deemed under applicable law to be obscene or pornographic, uses which are 
      excessively violent, unlawful, or which purpose is to encourage unlawful 
      activities.<br>
      &#8226; Not use the Image files to imply STOJAN VUJKOV's sponsorship, endorsement 
      or approval of your SOFTWARE PRODUCT modification, service or content provided 
      by your company.<br>
      &#8226; Not alter the Image Files in any way.<br>
      &#8226; Not combine the Image Files with any other object, including, but 
      not limited to, other logos, words, graphics, photos, slogans, numbers, 
      design features or symbols.<br>
      <br>
      <br>
      <strong>3. SOFTWARE PRODUCT ACCOMPANYING LICENSES</strong><br>
      <br>
      <strong>3.1.1 HTMLAREA LICENSE</strong><br>
      SOFTWARE PRODUCT incorporates a &quot;htmlArea&quot; software (in further 
      text refered to as &quot;HTMLAREA&quot;), created by interactivetools.com, 
      inc., wich is distributed under a licensing agreement with interactivetools.com, 
      inc. HTMLAREA software is a convinient additional feature of SOFTWARE PRODUCT, 
      and is provided &quot;AS IS&quot;, without warranties of any kind on behalf 
      of STOJAN VUJKOV, you use it at your own risk. Refer to Technical Specification 
      of SOFTWARE PRODUCT, for more information on HTMLAREA and the way it is 
      incorporated into SOFTWARE PRODUCT. STOJAN VUJKOV will not provide technical 
      support nor upgrades for the HTMLAREA software. STOJAN VUJKOV will not provide 
      installation of the HTMLAREA software.<br>
      <br>
      <strong>3.1.2 HTMLAREA DISTRIBUTION LICENSE</strong><br>
      <br>
      <strong>htmlArea License (based on BSD license)</strong><br>
      Copyright (c) 2002-2003, interactivetools.com, inc. All rights reserved.<br>
      <br>
      Redistribution and use in source and binary forms, with or without modification, 
      are permitted provided that the following conditions are met:<br>
      <br>
      1. Redistributions of source code must retain the above copyright notice, 
      this list of conditions and the following disclaimer. <br>
      2. Redistributions in binary form must reproduce the above copyright notice, 
      this list of conditions and the following disclaimer in the documentation 
      and/or other materials provided with the distribution. <br>
      3. Neither the name of interactivetools.com, inc. nor the names of its contributors 
      may be used to endorse or promote products derived from this software without 
      specific prior written permission.<br>
      <br>
      THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS &quot;AS 
      IS&quot; AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED 
      TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
      PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS 
      BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL 
      DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS 
      OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
      CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, 
      OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE 
      USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.<br>
      <br>
      <br>
      <strong>4. DESCRIPTION OF OTHER RIGHTS AND LIMITATIONS.</strong><br>
      <br>
      <strong>4.1. SUPPORT SERVICES.</strong> <br>
      STOJAN VUJKOV will provide support services related to the SOFTWARE PRODUCT 
      (in further text refered to as &quot;SUPPORT SERVICES&quot;). All SUPPORT 
      SERVICES will be provided by email for a time period of sixty (60) days 
      starting the same day that the SOFTWARE PRODUCT is purchased. Any supplemental 
      software code or alteration in database schema provided to you as part of 
      the SUPPORT SERVICES shall be considered part of the SOFTWARE PRODUCT and 
      subject to the terms and conditions of this EULA. With respect to technical 
      information you provide to STOJAN VUJKOV as part of the SUPPORT SERVICES, 
      STOJAN VUJKOV may use such information for its business purposes, including 
      but not limited to for product support and development. STOJAN VUJKOV will 
      not utilize such technical information in a form that personally identifies 
      you.<br>
      <br>
      <strong>4.2. LICENSE TERM VERIFICATION.</strong> <br>
      STOJAN VUJKOV reserves the right to check all licensees to verify compliance 
      with this EULA. In order to verify this compliance, you understand and acknowledge 
      that the SOFTWARE PRODUCT may collect and send back information to 100JAN 
      DESIGN STUDIO including but not limited to the location where the SOFTWARE 
      PRODUCT has been installed, if applicable. You also agree to provide such 
      information to STOJAN VUJKOV on its request, wich will be used only to verify 
      this compliance.<br>
      <br>
      <strong>4.3. CONFIDENTIAL INFORMATION.</strong> <br>
      The term &quot;CONFIDENTIAL INFORMATION&quot; means any information or material, 
      which is proprietary to STOJAN VUJKOV, whether or not owned or developed 
      by STOJAN VUJKOV, which is not generally known other than by STOJAN VUJKOV, 
      and which you have obtained through STOJAN VUJKOV. CONFIDENTIAL INFORMATION 
      includes without limitation: trade secrets, technical information, product 
      design information, database scripts, database schema, source code and/or 
      object code, copyrights and other intellectual property associated with 
      the SOFTWARE PRODUCT.<br>
      <br>
      <strong>4.4. PROTECTION OF CONFIDENTIAL INFORMATION.</strong> <br>
      You understand and acknowledge that the CONFIDENTIAL INFORMATION associated 
      with the SOFTWARE PRODUCT has been developed or obtained by STOJAN VUJKOV 
      by the investment of significant time, effort and expense, and that the 
      CONFIDENTIAL INFORMATION is a valuable, special and unique asset of STOJAN 
      VUJKOV, which provides STOJAN VUJKOV with a significant competitive advantage, 
      and needs to be protected from improper disclosure. You agree to hold in 
      confidence and to not disclose the CONFIDENTIAL INFORMATION to any person 
      or entity, except those who are required to have access to the CONFIDENTIAL 
      INFORMATION in order to perform their job duties in connection with the 
      limited purposes of this agreement.<strong><br>
      <br>
      4.5. LIMITATIONS ON REVERSE ENGINEERING, DECOMPILATION, AND DISASSEMBLY.</strong> 
      <br>
      You may not reverse engineer, decompile, or disassemble the SOFTWARE PRODUCT, 
      except and only to the extent that such activity is expressly permitted 
      by applicable law notwithstanding this limitation.<br>
      <br>
      <strong>4.6. RENTAL.</strong> <br>
      You may not rent, lease or lend the SOFTWARE PRODUCT.<br>
      <br>
      <strong>4.7. SOFTWARE TRANSFER.</strong> <br>
      You may permanently transfer all of your rights under this EULA, only in 
      case you retain no copies, you transfer all of the SOFTWARE PRODUCT (including 
      all component parts, the media and printed materials, any upgrades, this 
      EULA, and, if applicable, the Certificate of Authenticity), and the recipient 
      agrees to the terms of this EULA.<br>
      <br>
      <br>
      <strong>5. ALL RIGHTS NOT EXPRESSLY GRANTED HEREIN ARE RESERVED BY STOJAN 
      VUJKOV.</strong>
	  <br>
      <br>
      <br>
      <strong>6. TERMINATION.</strong> <br>
      Without prejudice to any other rights, STOJAN VUJKOV may terminate this 
      EULA if you fail to comply with the terms and conditions of this EULA. In 
      case of termination, you must destroy all copies of the SOFTWARE PRODUCT 
      and all of its component parts. In case of termination, STOJAN VUJKOV under 
      no circumstances will be in the obligation of returning the amount paid 
      for the SOFTWARE PRODUCT.<br>
      <br>
      <br>
      <strong>7. COPYRIGHT.</strong> <br>
      All title and copyrights in and to the SOFTWARE PRODUCT (including but not 
      limited to any images, photographs, animations, video, audio, music, text, 
      and applets incorporated into the SOFTWARE PRODUCT), the accompanying printed 
      materials, the accompanying online materials, and any copies of the SOFTWARE 
      PRODUCT are owned by STOJAN VUJKOV or its suppliers. Copyright laws and 
      international treaty provisions protect the SOFTWARE PRODUCT. Therefore, 
      you must treat the SOFTWARE PRODUCT like any other copyrighted material. 
      You may not copy the printed or online materials accompanying the SOFTWARE 
      PRODUCT.<br>
      <br>
      <br>
      <strong>8. LIMITED WARRANTY.</strong> <br>
      STOJAN VUJKOV guarantees that: <br>
      &#8226; During a time period of sixty (60) days starting at the same date 
      of the SOFTWARE PRODUCT is purchased, the SOFTWARE PRODUCT will work and 
      operate conforming to the terms and conditions of the provided Technical 
      Specification and documentation.<br>
      &#8226; That SUPPORT SERVICES provided by STOJAN VUJKOV will be provided 
      according to this EULA. In case of failure of SOFTWARE PRODUCT to operate 
      as specified in its Techical Specification STOJAN VUJKOV will be either 
      in position to (a) repair the SOFTWARE PRODUCT or (b) replace the SOFTWARE 
      PRODUCT. The SOFTWARE PRODUCT replacement will be guaranteed during the 
      rest of the original warranty time period or during thirty (30) days, choosing 
      from both periods the larger one. The present warranty will be terminated 
      if the SOFTWARE PRODUCT fails as result of an accident, abuse or misuse. 
      <br>
      <br>
      <br>
      <strong>9. NO OTHER WARRANTIES.</strong> <br>
      To the maximum extent permitted by applicable law, STOJAN VUJKOV and its 
      suppliers disclaim all warranties and conditions, either express or implied, 
      including, but not limited to, implied warranties of merchantability, fitness 
      for a particular purpose, title and non-infringement, with regard to the 
      software product, and the provision of or failure to provide support services.<br>
      <br>
      <br>
      <strong>10. LIMITATION OF LIABILITY.</strong> <br>
      To the maximum extent permitted by applicable law, in no event shall STOJAN 
      VUJKOV or its suppliers be liable for any special, incidental, indirect, 
      or consequential damages whatsoever (including, without limitation, damages 
      for loss of business profits, business interruption, loss of business information, 
      or any other pecuniary loss) arising out of the use of or inability to use 
      the software product or the provision of or failure to provide support services, 
      even if STOJAN VUJKOV has been advised of the possibility of such damages.<br>
      <br>
      <br>
      <strong>11. ALL SALES ARE FINAL.</strong> <br>
      When you purchase the SOFTWARE PRODUCT you are acquiring source code and 
      digital information. The SOFTWARE PRODUCT sale is final. STOJAN VUJKOV under 
      no circumstances will be in the obligation of returning the amount paid 
      for the SOFTWARE PRODUCT.<br>
      <br>
      <br>
      <strong>12. LICENSE EXCLUSION</strong><br>
      The files wich original filename starts with &quot;test&quot; are distributed 
      with SOFTWARE PRODUCT for testing purposes, therefore provided &quot;as-is&quot;, 
      and are excluded from EULA.<br>
      <br>
      <br>
      <strong>13. MISCELLANEOUS</strong><br>
      Wherever you acquire this software your local law may apply.<br>
      <br>
      <br> 
	  </td>
  </tr>
</table>

<br>
<br>
<br>

</body>
</html>
