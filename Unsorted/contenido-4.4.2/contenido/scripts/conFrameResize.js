/**
 * conFrameResize Class
 *
 * Controls the frame resizing and
 * correct reloading in Contenido
 *
 * @author Jan Lengowski <Jan.Lengowski@4fb.de>
 * @copyright four for business AG <www.4fb.de>
 */
function conFrameResize( parentFrameId, frameOne, frameTwo, frameThree, frameFour ) {

    /* class correctly initialized */
    this.ok = false;

    /* Id of the frameset, contenido default
       is 'contentFrame' */
    this.frameSetId = parentFrameId || 'contentFrame';

    /* Name of the frames
       LeftTop, LeftBottom, RightTop, RightBottom */
    this.frameNames = new Array();
    this.frameNames[1] = frameOne   || 'left_top';
    this.frameNames[2] = frameTwo   || 'left_bottom';
    this.frameNames[3] = frameThree || 'right_top';
    this.frameNames[4] = frameFour  || 'right_bottom';

    /* Array storing the sources of the
       individual frames to reload them
       correctly in netscape */
    this.frameSrc = new Array();
    this.frameSrc[1] = '';
    this.frameSrc[2] = '';
    this.frameSrc[3] = '';
    this.frameSrc[4] = '';
    
    /* Object reference to the left top frame */
    this.frameSet = '';

    /* Object reference to the left image */
    this.imgLeft = document.getElementById( 'toggleImage' );
    
    /* Left image source */
    this.imgLeftSrc = 'images/toggle_frame_left.gif';
    
    /* Object reference to the right image */
    this.imgRight = '';

    /* Right image source */
    this.imgRightSrc = 'images/toggle_frame_right.gif';

    /* Neutral image src */
    this.spacerImage = 'images/spacer.gif'

    /* Status of the frame 'hidden' or 'normal' */
    this.status = 'normal';

    /* Value of the col attribute in normal pos */
    this.colValHidden = '*,6,100%';
    
    /* Value of the col attribute in hidden pos */
    this.colValNormal = '200,6,100%';
    
    this.toggle = conFrameResize_toggle;
    this.init = conFrameResize_init;
}

function conFrameResize_init() {
   /* Create reference to other frames with this
      init method because of different load times */
   this.frameSet = document.getElementById( this.frameSetId );
   this.imgRight = window.frames[ this.frameNames[3] ].document.getElementById( 'toggleimage' );
   this.ok = true;
   //alert("Frameset: " + this.frameSet + "\nImage: " + this.imgRight);
}

function conFrameResize_toggle() {

    if ( 'normal' == this.status && this.ok ) {

        /* Save frame locations */
        for ( i = 1; i <= 4; i ++ ) {
            this.frameSrc[i] = window.frames[ this.frameNames[i] ].src;
        }
        
        /* Change image sources */
        this.imgRight.src = this.imgRightSrc;
        
        /* Cursor style */
        this.imgRight.style.cursor = "hand";
        
        /* Resize frameset */
        this.frameSet.cols = this.colValHidden;
        this.status = 'hidden';

        /* Reload frames for netscape */
        //for ( i = 1; i <= 4; i ++ ) {
        //    parent.frames[ this.frameNames[i] ].src = this.frameSrc[i];
        //}

    } else if ( 'hidden' == this.status && this.ok ) {

        /* Save frame locations */
        //for ( i = 1; i <= 4; i ++ ) {
        //    this.frameSrc[i] = window.frames[ this.frameNames[i] ].src;
        //}

        /* Change image sources */
        this.imgRight.src = this.spacerImage;

        /* Cursor style */
        this.imgRight.style.cursor = "default";

        /* Resize frameset */
        this.frameSet.cols = this.colValNormal;
        
        /* Set status to normal */
        this.status = 'normal';


        /* Reload frames for netscape */
        //for ( i = 1; i <= 4; i ++ ) {
        //    window.frames[ this.frameNames[i] ].src = this.frameSrc[i];
        //}

    }
    
}

