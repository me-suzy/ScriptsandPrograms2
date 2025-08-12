limit_multiple_users = true 
sequence = "Qmpro40"

   //Relative positioned icon images (flow with main menu or sub item text)

	dqm__icon_image0 = "contentimage/icons/pfeil.gif"
	dqm__icon_rollover0 = "contentimage/icons/pfeil.gif"
	dqm__icon_image_wh0 = "3,6"

    //Absolute positioned icon images (coordinate positioned, sub menus only)

	dqm__2nd_icon_image0 = "contentimage/icons/pfeil.gif"
	dqm__2nd_icon_rollover0 = "contentimage/icons/pfeil.gif"
	dqm__2nd_icon_image_wh0 = "3,6"
	dqm__2nd_icon_image_xy0 = "0,4"


/*---------------------------------------------
Main Menu Borders Dividers and Layout
-----------------------------------------------*/

	dqm__main_border_width = 0;		//the thickness of the border in pixels, 0 = no border
	dqm__main_border_color = "#FFFFFF"	//HEX color or set to 'transparent'

	dqm__main_use_dividers = true		//When true the item gap setting is ignored
						//and the border width and color are used to
						//separate each main menu item.
											
	dqm__main_item_gap = 5			//the gap between main menu items in pixels
	
	dqm__align_items_bottom_and_right = false	//align items of different size to the bottom
							//or right edge of the largest main menu item
							//depending on the horizontal or vertical layout
							//of the main menu bar - false aligns items to
							//the top and left

/*---------------------------------------------
Menu Item Settings
-----------------------------------------------*/

	dqm__main_fontweight = "bold"		//set to: 'normal', or 'bold'
	dqm__main_fontstyle = "normal"		//set to: 'normal', or 'italic' 	
	dqm__main_hl_bgcolor = "#ECECEC"
	dqm__main_text_alignment = "center"		//set to: 'left', 'center' or 'right'
	dqm__main_margin_bottom = 1
	dqm__main_margin_left = 1
	dqm__main_margin_right = 1

/*---------------------------------------------
SubMenu Item Settings
-----------------------------------------------*/

	dqm__sub_menu_width = 180
	dqm__urltarget = "_self"		//default URL target: _self, _parent, _new, or "my frame name"

	dqm__border_width = 1
	dqm__divider_height = 0

	dqm__mouse_off_delay = 100		//defined in milliseconds (activated after mouse stops)
	dqm__nn4_mouse_off_delay = 500		//defined in milliseconds (activated after leaving sub)

/*-------------------------------------------
Font settings and margins
--------------------------------------------*/
	dqm__hl_bgcolor = "#e6e6e6" 	
	dqm__fontsize_ie4 = 9			//Defined with point sizing
	dqm__fontweight = "normal"		//set to: 'normal', or 'bold'
	dqm__fontstyle = "normal"		//set to: 'normal', or 'italic' 	

    //Margins and text alignment

	dqm__text_alignment = "left"		//set to: 'left', 'center' or 'right'
	dqm__margin_top = 2
	dqm__margin_bottom = 3
	dqm__margin_left = 5
	dqm__margin_right = 4

	dqm__show_urls_statusbar = false

/*-------------------------------------------
Internet Explorer Transition Effects
--------------------------------------------*/

    //Options include - none | fade | pixelate |iris | slide | gradientwipe | checkerboard | radialwipe | randombars | randomdissolve |stretch

	dqm__sub_menu_effect = "none"
	dqm__sub_item_effect = "none"

    //Define the effect duration in seconds below.
   
	dqm__sub_menu_effect_duration = .4
	dqm__sub_item_effect_duration = .4

    //Specific settings for various transitions.

	dqm__effect_pixelate_maxsqare = 25
	dqm__effect_iris_irisstyle = "CIRCLE"		//CROSS, CIRCLE, PLUS, SQUARE, or STAR
	dqm__effect_checkerboard_squaresx = 14
	dqm__effect_checkerboard_squaresY = 14
	dqm__effect_checkerboard_direction = "RIGHT"	//UP, DOWN, LEFT, RIGHT

    //Opacity and drop shadows.

	dqm__sub_menu_opacity = 100			//1 to 100
	dqm__dropshadow_color = "none"			//Hex color value or 'none'
	dqm__dropshadow_offx = 5			//drop shadow width
	dqm__dropshadow_offy = 5			//drop shadow height

/*-------------------------------------------
Browser Bug fixes and Workarounds
--------------------------------------------*/

    //Mac offset fixes, adjust until sub menus position correctly.
 
	dqm__ie4mac_offset_x = -10
	dqm__ie4mac_offset_Y = -45

    //Netscape 4 resize bug workaround.

	dqm__nn4_reaload_after_resize = true
	dqm__nn4_resize_prompt_user = false
	dqm__nn4_resize_prompt_message = "To reinitialize the navigation menu please click the 'Reload' button."
   
    //Opera 5 & 6, set to true if the menu is the only item on the HTML page.

	dqm__use_opera_div_detect_fix = true

    //Pre-defined sub menu item heights for the Espial Escape browser.

	dqm__escape_item_height = 20
	dqm__escape_item_height0_0 = 70
	dqm__escape_item_height0_1 = 70