Zip file contents:
class.HiQFMConfig.php - Class for processing the config file.
er_footer_def.htm - Default error display footer file.
er_header_def.htm - Default error display header file.
footer_def.htm - default footer file.
header_def.htm - default header file.
HiQFM.php - Form processor script.
HiQFMConfg.conf - Default config file.
Defconfig.conf - File which contains the default settings.
readme.txt - This file.
readme_config.txt

TEMP_FILES - A directory used for temporary storage when doing safe mode attachments.
             See safe mode instructions for usage.
-------------------------------------------------------

Under normal curcumstances it should not be necessary to edit the HiQFM.php file.

The only required form entry would be similar to:
  <FORM ENCTYPE="multipart/form-data" ACTION="HiQFM/HiQFM.php" METHOD="POST">
--NOTE-- This assumes the HiQFM directory is in the same directory as the form.  
Personally I like the idea of keeping my forms in the same directory as the handler.

On most servers file/directory names are CASE sensative so use caution.

For proper operation it is IMPORTANT that the zip file directory structure be maintained.
--NOTE-- The directory and/or script(HiQFM.php) names can be changed but the directory 
structure must remain intact.

For script configurations see the mailerdoc.htm file.

------------------------------------------------------- 
HiQ Formmail takes a different approach to processing form generated email.  Instead of
configurating the script by way of hidden form fields as most form handlers do the 
HiQ Formmail script is configured from information contained in 1 or more files.  I feel
that while a bit more of a job to configure there is a higher level of security and 
flexability.

I want to thank Gerard <last name?> for encouragement, advice and providing the class script.

