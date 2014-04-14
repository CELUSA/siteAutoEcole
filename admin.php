<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "team-osn@hotmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "f9299f" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'7C4C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7QkMZQxkaHaYGIIu2sjY6tDoEiKCIiTQ4THV0YEEWmwJUEejogOK+qGmrVmZmZiG7j9FBpIG1Ea4ODFkbgGKhgShiIkDo0IhqRwBQp0MjqlsCGrC4eYDCj4oQi/sAbTDMehbbmr4AAAAASUVORK5CYII=',
			'7430' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZWhmBGEW0lWEqa6PDVAdUsVCGhoCAAGSxKYyuDI2ODiLI7otaunTV1JVZ05Dcx+gg0oqkDgxZG0RDHRoCUcREQLag2QFkt6K7BSSG4eYBCj8qQizuAwBAKcxni42QuwAAAABJRU5ErkJggg==',
			'AA66' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaY6IImxBjCGMDo6BAQgiYlMYW1lbXB0EEASC2gVaXQFmYDkvqil01amTl2ZmoXkPrA6R0cU80JDRUNdGwIdRDDMwxRzRHMLSMwBzc0DFX5UhFjcBwBjL8z9IA7xjQAAAABJRU5ErkJggg==',
			'DFC4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QgNEQx1CHRoCkMQCpog0MDoENKKItYo0sDYItGKKMUwJQHJf1NKpYUuBVBSS+yDqgCZi6GUMDcG0A5tbUMRCA0QaGNDcPFDhR0WIxX0ASdnPcU70bXMAAAAASUVORK5CYII=',
			'691B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYQximMIY6IImJTGFtZQhhdAhAEgtoEWl0BIqJIIs1iDQ6TIGrAzspMmrp0qxpK0OzkNwXMoUxEEkdRG8rA1gvinmtLBhiYLeg6QW5mTHUEcXNAxV+VIRY3AcAQdLLhmI/KiQAAAAASUVORK5CYII=',
			'5232' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QwQ2AMAhF6aEb1H1wA5oUD07THtigdQMvTGmNF4weNSk/4fBC4AXQR2UYKb/4cXLJMTQ0jLIXX5DoxkLBHDEYFgkKdhqM37Lprk11tX4C9Zo0lwWobxXrQuKw92pZqD6fLpZ5mnhmx2mA/32YF78D9UHNXlB2sCoAAAAASUVORK5CYII=',
			'B79E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgNEQx1CGUMDkMQCpjA0Ojo6OiCrC2hlaHRtCEQVm8LQyooQAzspNGrVtJWZkaFZSO4DqgtgCEHT28oI5KOLsTYwYtgh0sCI5pbQAJEGBjQ3D1T4URFicR8AVT/LXFCEuGQAAAAASUVORK5CYII=',
			'2B4C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANEQxgaHaYGIImJTBFpZWh1CBBBEgtoFQGqcnRgQdbdClQX6OiA4r5pU8NWZmZmobgvQKSVtRGuDgwZHUQaXUMDUcRYG4B2NKLaIdIAtKMR1S2hoZhuHqjwoyLE4j4AyNDL4xo8fKEAAAAASUVORK5CYII=',
			'7D1C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQximMEwNQBZtFWllCGEIEEEVa3QMYXRgQRabItLoMIXRAcV9UdNWZgERsvsYHVDUgSFrA6aYCFQM2Y6ABqBbpqC6JaBBNIQx1AHVzQMUflSEWNwHAN0Dy1BxVsB+AAAAAElFTkSuQmCC',
			'B61A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QgMYQximMLQiiwVMYW1lCGGY6oAs1irSCFQZEICiTqSBYQqjgwiS+0KjpoWtmrYyaxqS+wKmiLYiqYOb5zCFMTQEUwxVHcgtaGIgNzOGOqKIDVT4URFicR8AaeDMVzz/ifUAAAAASUVORK5CYII=',
			'E97F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA0NDkMQCGlhbGRoCHRhQxEQaHbCJNTrCxMBOCo1aujRr6crQLCT3BTQwBjpMYUTTy9DoEIAuxgI0DV2MtZW1AVUM7GY0sYEKPypCLO4DAGLRyykUuNAbAAAAAElFTkSuQmCC',
			'CD26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WENEQxhCGaY6IImJtIq0Mjo6BAQgiQU0ijS6NgQ6CCCLNYg0OgDFkN0XtWrayqyVmalZSO4Dq2tlRDUPJDaF0UEEzQ6HAFQxsFscGFD0gtzMGhqA4uaBCj8qQizuAwC2a8yTs5ciMAAAAABJRU5ErkJggg==',
			'C939' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WEMYQxhDGaY6IImJtLK2sjY6BAQgiQU0ijQ6NAQ6iCCLNQDFGh1hYmAnRa1aujRr6qqoMCT3BTQwBjo0OkxF1csANA9oAoodLCAxFDuwuQWbmwcq/KgIsbgPAAsZzbek6M+EAAAAAElFTkSuQmCC',
			'0A84' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGRoCkMRYAxhDGB0dGpHFRKawtrI2BLQiiwW0ijQ6OjpMCUByX9TSaSuzQldFRSG5D6LO0QFVr2ioa0NgaAiKHSKNrkCXoLoFbAeKGKODSKMDmpsHKvyoCLG4DwDQps3Zonm0XQAAAABJRU5ErkJggg==',
			'C628' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WEMYQxhCGaY6IImJtLK2Mjo6BAQgiQU0ijSyNgQ6iCCLNYB4ATB1YCdFrZoWtmpl1tQsJPcFNIi2MrQyoJrXINLoMIUR1TygHQ4BqGJgtzig6gW5mTU0AMXNAxV+VIRY3AcADFPMETu/lfIAAAAASUVORK5CYII=',
			'396F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYQxhCGUNDkMQCprC2Mjo6OqCobBVpdG1AE5sCEmOEiYGdtDJq6dLUqStDs5DdN4Ux0BXDPAag3kA0MRYMMWxugboZVe8AhR8VIRb3AQAdccmdgZbsowAAAABJRU5ErkJggg==',
			'4690' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37pjCGMIQytKKIhbC2Mjo6THVAEmMMEWlkbQgICEASY50i0sDaEOggguS+adOmha3MjMyahuS+gCmirQwhcHVgGBoq0ujQgCrGMEWk0RHNDoYpmG7B6uaBCj/qQSzuAwBKYMup1zuSQwAAAABJRU5ErkJggg==',
			'4728' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsQ2AMAwEP0U2gH1MQW8kvARTmIINTHYgUxKQkBKgBIG/O72tkxEvo/hT3vGzWkgwU856jE1DzBlzibXaUZUxb5igfPR2pRBiiMswD5kfGzg1i3sijmCuuAfzCj6zSlOz2N2YFy6dv/rfc7nxWwFwnct75iFOTQAAAABJRU5ErkJggg==',
			'3BD6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7RANEQ1hDGaY6IIkFTBFpZW10CAhAVtkq0ujaEOgggCwGUgcUQ3bfyqipYUtXRaZmIbsPog6reSIExLC5BZubByr8qAixuA8A9LzM3v7PVIQAAAAASUVORK5CYII=',
			'D463' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QgMYWhlCGUIdkMQCpjBMZXR0dAhAFgOqYm1waBBBEWN0ZQXRSO6LWgoEU1ctzUJyX0CrSCuro0MDqnmioa5AEVTzGFpZ0cWmMLSiuwWbmwcq/KgIsbgPAHwEzhlrTI2YAAAAAElFTkSuQmCC',
			'A5F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nM2QsRGAMAhFScEGuE8aewqxcBos2MCMkCZTalJhtNRTfvfu4L8DymUU/pRX/EIcBIVTdAyZFBWYHaOtshDJMTaaHGtKS045S1lm58cG66iQ/K5IY9rdq6zrQOtd2MLRCyfnr/73YG78dp8UzBACkuw/AAAAAElFTkSuQmCC',
			'B9C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHaY6IIkFTGFtZXQICAhAFmsVaXRtEHQQQFEHEmN0QHZfaNTSpamrVqZmIbkvYApjIFAdmnkMYL0iKGIsYDtECLgFm5sHKvyoCLG4DwDAzs1wj/Ki/AAAAABJRU5ErkJggg==',
			'5AD6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMYAlhDGaY6IIkFNDCGsDY6BASgiLG2sjYEOgggiQUGiDS6AsWQ3Rc2bdrK1FWRqVnI7msFq0Mxj6FVNBSkVwTZDog6FDGRKUAxNLewguxFc/NAhR8VIRb3AQDByM2lAk1i4wAAAABJRU5ErkJggg==',
			'1E5C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDHaYGIImxOog0sDYwBIggiYmCxRgdWFD0AsWmMjogu29l1tSwpZmZWcjuA6ljaAh0YEDTi02MFSiGbgejowOqW0JEQxlCGVDcPFDhR0WIxX0AdPbHplN2/LAAAAAASUVORK5CYII=',
			'8050' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHVqRxUSmMIawNjBMdUASC2hlbQWKBQSgqBNpdJ3K6CCC5L6lUdNWpmZmZk1Dch9InUNDIEwd1DxsYiA7AtDsYAxhdHRAcQvIzQyhDChuHqjwoyLE4j4Aw2rL41NSTgkAAAAASUVORK5CYII=',
			'E279' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA6Y6IIkFNLC2AsmAABQxkUaHhkAHERQxhkaHRkeYGNhJoVGrlgJhVBiS+4DqpgDhVDS9AUDYgCrG6MDowIBmBysQMqC4JTRENNS1gQHFzQMVflSEWNwHAKQ/zRzCe4pLAAAAAElFTkSuQmCC',
			'1BE5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHUMDkMRYHURaWYEyyOpEHUQaXdHEGCHqXB2Q3Lcya2rY0tCVUVFI7oOoY2gQQdULNA+bGJDEsIMhANl9oiEgNztMdRgE4UdFiMV9ABMHyFVZE/XFAAAAAElFTkSuQmCC',
			'1727' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUNDkMRYHRgaHR0dGkSQxESBYq4NAShijA4MrQxAsQAk963MWjUNSAAphPuA6gKAKltR7QWKTgFCFDHWBqDKAFQxEYhaZLeEiDSwhgaiiA1U+FERYnEfAMnOyENgvoMDAAAAAElFTkSuQmCC',
			'0AE0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHVqRxVgDGENYGximOiCJiUxhbQWKBQQgiQW0ijS6Ak0QQXJf1NJpK1NDV2ZNQ3IfmjqomGgoupjIFJA6VDtYA8BiKG4B6mp0RXPzQIUfFSEW9wEAMCHLs4+YSlEAAAAASUVORK5CYII=',
			'0359' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDHaY6IImxBoi0sjYwBAQgiYlMYWh0BaoWQRILaGVoZZ0KFwM7KWrpqrClmVlRYUjuA6kDklPR9DY6NAQ0iGDYEYBiB8gtjI4OKG4BuZkhlAHFzQMVflSEWNwHAPeay025YSU+AAAAAElFTkSuQmCC',
			'EE80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkNEQxlCGVqRxQIaRBoYHR2mOqCJsTYEBARgqHN0EEFyX2jU1LBVoSuzpiG5D00dknmBWMSw2YHqFmxuHqjwoyLE4j4AnirMk/VywHsAAAAASUVORK5CYII=',
			'CB3E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WENEQxhDGUMDkMREWkVaWRsdHZDVBTSKNDo0BKKKAVUyINSBnRS1amrYqqkrQ7OQ3IemDiaGaR4WO7C5BZubByr8qAixuA8ApuHLyWJxov0AAAAASUVORK5CYII=',
			'C70A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WENEQx2mMLQii4m0MjQ6hDJMdUASC2hkaHR0dAgIQBZrYGhlbQh0EEFyX9SqVdOWrorMmobkPqC6ACR1UDFGB6BYaAiKHawNjEBLRFDcAuSFMqKIsYYAeVNQxQYq/KgIsbgPAAK4y74NHGP5AAAAAElFTkSuQmCC',
			'407F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpI37pjAEsIYGhoYgi4UwhjA0BDogq2MMYW1FF2OdItLo0OgIEwM7adq0aSuzlq4MzUJyXwBI3RRGFL2hoUCxAFQxhimsrYwO6GJAmxvQxYBuRhcbqPCjHsTiPgCgY8kWdSFhFwAAAABJRU5ErkJggg==',
			'91F2' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDA6Y6IImJTGEMYG1gCAhAEgtoZQWKMTqIoIgxgNQ1iCC5b9rUVVFLQ1etikJyH6srWF0jsh0MEL2tyG4RgIhNYUBxC1gsANXNrKFAt4SGDILwoyLE4j4AfsjJDsHSqtsAAAAASUVORK5CYII=',
			'34A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RAMYWhmmMIYGIIkFTGGYyhDK6ICishUo4uiIKjaF0ZW1IdDVAcl9K6OWLl26KjIqCtl9U0RaWRsCGkRQzBMNdQ1FF2MAqgt0EEF1C0hvALL7QG4Gik11GAThR0WIxX0A8YrLotLzYZkAAAAASUVORK5CYII=',
			'E057' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHUNDkMQCGhhDWIG0CIoYayummEij61QQjXBfaNS0lamZWSuzkNwHUufQENDKgKYXKDaFAcOOgAAGNLcwOjo6oLuZIZQRRWygwo+KEIv7ANVRzHoV/H6IAAAAAElFTkSuQmCC',
			'182D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMdkMRYHVhbGR0dHQKQxEQdRBpdGwIdRFD0srYyIMTATlqZtTJs1crMrGlI7gOra2VE0yvS6DAFi1gAuhjQLQ6MqG4JYQxhDQ1EcfNAhR8VIRb3AQCbp8e6b+EKkAAAAABJRU5ErkJggg==',
			'161B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YQximMIY6IImxOrC2MoQwOgQgiYk6iDQyAsVEUPQCeVPg6sBOWpk1LWzVtJWhWUjuY3QQbUVSB9Pb6DAFwzwsYqwYekVDgC4JdURx80CFHxUhFvcBAHUTx8w8yyK0AAAAAElFTkSuQmCC',
			'0843' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHUIdkMRYA1hbGVodHQKQxESmiDQ6THVoEEESC2gFqgt0aAhAcl/U0pVhKzOzlmYhuQ+kjrURrg4qJtLoGhqAYh7YjkZUO8BuaUR1CzY3D1T4URFicR8AoTbNZ1fn7xcAAAAASUVORK5CYII=',
			'5BBA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDGVqRxQIaRFpZGx2mOqCKNbo2BAQEIIkFBoDUOTqIILkvbNrUsKWhK7OmIbuvFUUdTAxoXmBoCLIdEDEUdSJTMPWyBoDczIhq3gCFHxUhFvcBAFf6zNj4oA9RAAAAAElFTkSuQmCC',
			'198D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMdkMRYHVhbGR0dHQKQxEQdRBpdGwIdRFD0ijQ6AtWJILlvZdbSpVmhK7OmIbkPaEcgkjqoGAMW81iwiGFxSwimmwcq/KgIsbgPADIjyEERrY0lAAAAAElFTkSuQmCC',
			'5B57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHUNDkMQCGkRaWYG0CKpYoyuaWGAAUN1UkBzCfWHTpoYtzcxamYXsvlaRVqCqVhSbW0UaHRoCpiCLBbSC7AgIQBYTmSLSyujo6IAsxhogGsIQyogiNlDhR0WIxX0AsvnMPwMxMwkAAAAASUVORK5CYII=',
			'DA25' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QgMYAhhCGUMDkMQCpjCGMDo6OiCrC2hlbWVtCEQTE2l0aAh0dUByX9TSaSuzVmZGRSG5D6yulaFBBEWvaKjDFHQxoLoARgcUsSkijUCXBCC7LzRApNE1NGCqwyAIPypCLO4DADpYzWaT5VCpAAAAAElFTkSuQmCC',
			'875A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQ11DHVqRxUSmMDS6NjBMdUASC2gFiwUEoKprZZ3K6CCC5L6lUaumLc3MzJqG5D6gugCGhkCYOqh5jA5AsdAQFDHWBlY0dSJTRBoYHR1RxFgDgLxQRhSxgQo/KkIs7gMAxdvLkMntebQAAAAASUVORK5CYII=',
			'8F62' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7WANEQx1CGaY6IImJTBFpYHR0CAhAEgtoFWlgbXB0EEFTxwqikdy3NGpq2NKpq1ZFIbkPrM7RodEBw7yAVgZMsSkMWNyC6magjaGMoSGDIPyoCLG4DwCrq8yKkTcCVAAAAABJRU5ErkJggg==',
			'00A8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEx1QBJjDWAMYQhlCAhAEhOZwtrK6OjoIIIkFtAq0ujaEABTB3ZS1NJpK1NXRU3NQnIfmjqEWGgginkgO1gbUMVAbmFF0wtyM1AMxc0DFX5UhFjcBwDhLMwwM77LlAAAAABJRU5ErkJggg==',
			'A3FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7GB1YQ1hDA0MDkMRYA0RaWYEyyOpEpjA0uqKJBbQyIKsDOylq6aqwpaErQ7OQ3IemDgxDQ7Gah0UM0y0BrUA3NzCiuHmgwo+KEIv7AOsGydNM+8vnAAAAAElFTkSuQmCC',
			'25DC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQ1lDGaYGIImJTBFpYG10CBBBEgtoBYo1BDqwIOtuFQkBiaG4b9rUpUtXRWahuC+AodEVoQ4MGR0wxVgbRMBiyHYAbW1Fd0toKGMIupsHKvyoCLG4DwB0rcukD+JGjgAAAABJRU5ErkJggg==',
			'2861' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMRRYLaBVpdG1wCEXR3craytoA1wtx07SVYUunrlqK4r4AoDpHBxQ7GB1A5gWgiLE2YIqJNIDdgiIWGgp2c2jAIAg/KkIs7gMAQ+TLjjQoivUAAAAASUVORK5CYII=',
			'D0D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7QgMYAlhDGRoCkMQCpjCGsDY6NKKItbK2sgJJVDGRRleg6gAk90UtnbYydVVUVBSS+yDqAh0w9QaGhmDagc0tKGLY3DxQ4UdFiMV9ACFj0CyWpmHAAAAAAElFTkSuQmCC',
			'3494' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RAMYWhlCGRoCkMQCpjBMZXR0aEQWA6libQhoRRGbwugKFJsSgOS+lVFLl67MjIqKQnbfFJFWhpBAB1TzREMdGgJDQ1DtaGUEugTNLa1At6CIYXPzQIUfFSEW9wEAUhfNKkJvZl4AAAAASUVORK5CYII=',
			'1AFF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA0NDkMRYHRhDWEEySGKiDqyt6GKMDiKNrggxsJNWZk1bmRq6MjQLyX1o6qBioqGYYtjUYYqJhmCKDVT4URFicR8AQGzGzpWx2VUAAAAASUVORK5CYII=',
			'F7E7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQ11DHUNDkMQCGhgaXYG0CGGxVlYIDXdfaNSqaUtDV63MQnIfUD4AqK6VAUUvowNQbAqqGCsQMgSgiokAxRgdMMRCHVHEBir8qAixuA8A69DMbhEb34EAAAAASUVORK5CYII=',
			'4CAB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGgrADslgIa6NDKKNDAJIYY4hIg6Ojo4MIkhjrFJEG1oZAmDqwk6ZNm7Zq6arI0Cwk9wWgqgPD0FCgWGgginkMQHWuDehirI2uaHpB7gWah+rmgQo/6kEs7gMANRDMh2SUV2IAAAAASUVORK5CYII=',
			'1AA9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAhimMEx1QBJjdWAMYQhlCAhAEhN1YG1ldHR0EEHRK9Lo2hAIEwM7aWXWtJWpq6KiwpDcB1EXMBVVr2ioa2hAA6Z5AVjsCEB1SwjEPGQ3D1T4URFicR8AeyzKpC0mEWkAAAAASUVORK5CYII=',
			'771B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNFQx2mMIY6IIu2MjQ6hDA6BKCJOQLFRJDFpgBFp8DVQdwUtWraqmkrQ7OQ3MfowBCApA4MWUGiU1DNEwGKoosFAEXR9YLEGEMdUd08QOFHRYjFfQAX2sqOBUWdWQAAAABJRU5ErkJggg==',
			'9727' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QMQ6AIAxFy8DugPeBgb0mcglPUQZugNwBTmnjYlFHjfKSDi9/eAHa5RH8iVf6NI7BBhVm4UyG6JwlIxwmiJ7w7BJf5ugrayutLnURfdoDwr4VJGUhM8INSRMvEboWQ8rytms2pMPUua/+70Fu+jYutMralSXFqAAAAABJRU5ErkJggg==',
			'D6EE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVElEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDHUMDkMQCprC2sjYwOiCrC2gVacQi1oAkBnZS1NJpYUtDV4ZmIbkvoFUUq3muxIhhcQs2Nw9U+FERYnEfAIjvyui2rixKAAAAAElFTkSuQmCC',
			'9AE5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHUMDkMREpjCGsDYwOiCrC2hlbcUUE2l0bWB0dUBy37Sp01amhq6MikJyH6srSB3QXGSbW0VD0cUEIOY5IIuJTAHrDUB2H2sAUCzUYarDIAg/KkIs7gMAmuPLTRUJYX0AAAAASUVORK5CYII=',
			'8920' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMdUASC2gVaXRtCAgIQFEn0ujQEOggguS+pVFLl2atzMyahuQ+kSmMgQ6tjDB1UPMYGh2moIuxNDoEMKDZAXSLAwOKW0BuZg0NQHHzQIUfFSEW9wEAK33MHJf0LmgAAAAASUVORK5CYII=',
			'7EB7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDGUNDkEVbRRpYGx0aRNDFGgJQxaZA1AUguy9qatjS0FUrs5Dcx+gAVteKbC9rA9i8KchiIhCxAGQxkI2sjY4OqGJgN6OIDVT4URFicR8AAPbL64lfQ0UAAAAASUVORK5CYII=',
			'DBED' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHUMdkMQCpoi0sjYwOgQgi7WKNLoCxURQxcDqRJDcF7V0atjS0JVZ05Dch6YOn3mYYljcgs3NAxV+VIRY3AcArXPMlhCL8okAAAAASUVORK5CYII=',
			'75AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7QkNFQxmmMIYGIIu2ijQwhDI6MKCJMTo6oopNEQlhbQiEiUHcFDV16dJVkaFZSO4DmtToilAHhqwNQLFQVDGRBhEMdQENrK2sGGKMIHtR3TxA4UdFiMV9AOApyrMtK+94AAAAAElFTkSuQmCC',
			'4FE5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpI37poiGuoY6hgYgi4WINLA2MDogq2PEIsY6BSzm6oDkvmnTpoYtDV0ZFYXkvgCwOoYGESS9oaGYYgwQ8xwwxRgCAtDFQh2mOgyG8KMexOI+AOTCyppoHIiBAAAAAElFTkSuQmCC',
			'58D7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDGUNDkMQCGlhbWRsdGkRQxEQaXUEkklhgAFAdUCwAyX1h01aGLV0VtTIL2X2tYHWtKDa3gs2bgiwWABELQBYTmQJyi6MDshhrANjNKGIDFX5UhFjcBwCfD8z/V2wmKAAAAABJRU5ErkJggg==',
			'83BC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7WANYQ1hDGaYGIImJTBFpZW10CBBBEgtoZWh0bQh0YEFRxwBU5+iA7L6lUavCloauzEJ2H5o6FPOwiaHagekWbG4eqPCjIsTiPgAS2MwlwCGlJwAAAABJRU5ErkJggg==',
			'E7DB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QkNEQ11DGUMdkMQCGhgaXRsdHQLQxRoCHURQxVpZgWIBSO4LjVo1bemqyNAsJPcB5QOQ1EHFGB1YMcxjbcAUE2lgRXNLaAhQDM3NAxV+VIRY3AcA4YPNalUPqxYAAAAASUVORK5CYII=',
			'36AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7RAMYQximMLQiiwVMYW1lCGWY6oCsslWkkdHRISAAWWyKSANrQ6CDCJL7VkZNC1u6KjJrGrL7poi2IqmDm+caGhgagi6Gpg7kFnS9IDdjmDdA4UdFiMV9AKdzy+Gcqt7QAAAAAElFTkSuQmCC',
			'88FE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUklEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0MDkMREprC2sjYwOiCrC2gVaXRFE0NTB3bS0qiVYUtDV4ZmIbmPWPOIsAPh5gZGFDcPVPhREWJxHwAMF8mqPT8eiAAAAABJRU5ErkJggg==',
			'F630' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMZQxhDGVqRxQIaWFtZGx2mOqCIiTQCyYAAVLEGhkZHBxEk94VGTQtbNXVl1jQk9wU0iLYiqYOb59AQiEUM3Q5sbsF080CFHxUhFvcBAJuZzkByYdlKAAAAAElFTkSuQmCC',
			'2B4E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANEQxgaHUMDkMREpoi0MrQ6OiCrC2gVaXSYiirG0ApUFwgXg7hp2tSwlZmZoVnI7gsQaWVtRNXL6CDS6BoaiCLG2gC0A02dSAPQDjSx0FBMNw9U+FERYnEfAGAGysK6UVn8AAAAAElFTkSuQmCC',
			'7A18' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMZAhimMEx1QBZtZQxhCGEICEARYwWKMjqIIItNEWl0mAJXB3FT1LSVWdNWTc1Cch9QF7I6MGRtEA11mIJqnkgDSB2qWEADpl6QmGOoA6qbByj8qAixuA8Azo/MWzb/OtIAAAAASUVORK5CYII=',
			'236D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANYQxhCGUMdkMREpoi0Mjo6OgQgiQW0MjS6Njg6iCDrbmVoZW1ghIlB3DRtVdjSqSuzpiG7LwCozhFVL6MDyLxAFDHWBkwxkQZMt4SGYrp5oMKPihCL+wDoQMpp1CFvNQAAAABJRU5ErkJggg==',
			'9032' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WAMYAhhDGaY6IImJTGEMYW10CAhAEgtoZW1laAh0EEERE2l0aHRoEEFy37Sp01ZmTV21KgrJfayuYHWNyHYwgPQCTUB2iwDYjoApDFjcgulmxtCQQRB+VIRY3AcA5gLMo3w6eWkAAAAASUVORK5CYII=',
			'5A03' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIQ6IIkFNDCGMIQyOgSgiLG2Mjo6NIggiQUGiDS6AmUCkNwXNm3aytRVUUuzkN3XiqIOKiYaChJDNi8AqM4RzQ6RKSKNDmhuYQXa64Dm5oEKPypCLO4DANwAzbN7Y8xZAAAAAElFTkSuQmCC',
			'E2AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkMYQximMIaGIIkFNLC2MoQyOjCgiIk0Ojo6ookxNLo2BMLEwE4KjVq1dOmqyNAsJPcB1U1hRaiDiQWwhqKLMTpgqmNtQBcLDRENdUUTG6jwoyLE4j4AKT7La/lXPt0AAAAASUVORK5CYII=',
			'FD57' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QkNFQ1hDHUNDkMQCGkRaWYG0CKpYoys2sakgGuG+0KhpK1Mzs1ZmIbkPpM6hIaCVAU0vUGwKuphrQ0AAmlgro6OjA6qYaAhDKCOK2ECFHxUhFvcBAJ/EzfL8wyS9AAAAAElFTkSuQmCC',
			'7477' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkMZWllDA0NDkEVbGaYyNAQ0iKCKhWKITWF0ZWh0AIoiuS9q6dJVS1etzEJyH6ODSCvDFKAJSHpZG0RDHQKAokhiQLNbGR0YApDFgGa3sgJNICQ2UOFHRYjFfQBimcs12k2pEgAAAABJRU5ErkJggg==',
			'109C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGaYGIImxOjCGMDo6BIggiYk6sLayNgQ6sKDoFWl0BYohu29l1rSVmZmRWcjuA6lzCIGrQ4g1oIuxtjJi2IHFLSGYbh6o8KMixOI+AI82x7762VLEAAAAAElFTkSuQmCC',
			'2831' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQxhDGVqRxUSmsLayNjpMRRYLaBVpdGgICEXR3craytDoANMLcdO0lWGrpq5aiuK+ABR1YMjoADYPRYy1AVNMpAHsFhSx0FCwm0MDBkH4URFicR8Ak/TMlb68EG8AAAAASUVORK5CYII=',
			'A9F5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0MDkMRYA1hbWYEyyOpEpog0uqKJBbSCxVwdkNwXtXTp0tTQlVFRSO4LaGUMdAWZgaQ3NJShEV0soJUFbAeqGMgtDAEBKGJANzcwTHUYBOFHRYjFfQAQgsuuw/peQwAAAABJRU5ErkJggg==',
			'E0A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIYGIIkFNDCGMIQyOjCgiLG2Mjo6oomJNLo2BLo6ILkvNGraytRVkVFRSO6DqAOS6HpD0cVYW1kbAh1E0NzC2hAQgOw+kJuBYlMdBkH4URFicR8Ax33NEgF26+AAAAAASUVORK5CYII=',
			'977C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQ11DA6YGIImJTGFodGgICBBBEgtoBYkFOrCgigFFHR2Q3Tdt6qppq5auzEJ2H6srQwDDFEYHFJtbgfwAVDGBVtYGRgdGFDtEpog0sDYwoLiFNQAshuLmgQo/KkIs7gMAD+HK0mi8/isAAAAASUVORK5CYII=',
			'7ED6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDGaY6IIu2ijSwNjoEBKCLNQQ6CCCLTYGIobgvamrY0lWRqVlI7mN0AKtDMY+1AaJXBElMBItYQAOmWwIasLh5gMKPihCL+wDIwswDiAP3rAAAAABJRU5ErkJggg==',
			'6B24' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxhCGRoCkMREpoi0Mjo6NCKLBbSINLo2BLSiiDWItALJKQFI7ouMmhq2amVWVBSS+0KA5jG0Mjqg6G0VaXSYwhgagi4WgMUtDqhiIDezhgagiA1U+FERYnEfAJAezhQUgFVsAAAAAElFTkSuQmCC',
			'0466' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGaY6IImxBjBMZXR0CAhAEhOZwhDK2uDoIIAkFtDK6MoKMgHJfVFLgWDqytQsJPcFtIq0sjo6opgX0Coa6toQ6CCCakcrK5oY0C2t6G7B5uaBCj8qQizuAwAxssq3PHyn0gAAAABJRU5ErkJggg==',
			'1E85' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGUMDkMRYHUQaGB0dHZDViQLFWBsCHVD1gtW5OiC5b2XW1LBVoSujopDcB1Hn0CCCppe1IQCLWKADuhhQbwCy+0RDQG5mmOowCMKPihCL+wB7T8ffrAaAPAAAAABJRU5ErkJggg==',
			'2245' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeElEQVR4nM2QsQ2AMAwEncIbhH0+Bb2RSJMN2AIX2SCwAQWZktAZQQkS/u5k60+mepuZ/pRP/FjcSBqiGOYLZ8oBdk+yVyxXRpkUQ+hh/da67dOUkvUTKqyYvbl1IOHWahmfVAMs8422FrF+MXYRzQY/+N+LefA7AHd3y7xml5KJAAAAAElFTkSuQmCC',
			'0ACA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCHVqRxVgDGEMYHQKmOiCJiUxhbWVtEAgIQBILaBVpdAWaIILkvqil01amrlqZNQ3JfWjqoGKioUCx0BAUO0DqBFHUsQaINDo6BKKIAU1qdAh1RBEbqPCjIsTiPgBA98uQxy+FpQAAAABJRU5ErkJggg==',
			'DD13' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQximMIQ6IIkFTBFpZQhhdAhAFmsVaXQMYWgQQRNzmAKkkdwXtXTayqxpq5ZmIbkPTR2KGDbzRNDdMgXVLSA3M4Y6oLh5oMKPihCL+wCkAs8Ju7PpMgAAAABJRU5ErkJggg==',
			'3834' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7RAMYQxhDGRoCkMQCprC2sjY6NCKLMbSKNDo0BLSiiAHVMTQ6TAlAct/KqJVhq6auiopCdh9YnaMDpnmBoSGYdmBzC4oYNjcPVPhREWJxHwBCnM6zPvJg9wAAAABJRU5ErkJggg==',
			'D700' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QgNEQx2mMLQiiwVMYWh0CGWY6oAs1srQ6OjoEBCAKtbK2hDoIILkvqilq6YtXRWZNQ3JfUB1AUjqoGKMDphirA2M6HZMAapAc0toAFAMzc0DFX5UhFjcBwAsMc2k1PG7iAAAAABJRU5ErkJggg==',
			'2973' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QwQ2AMAhFaSIbdCDcgCayhFPQQzdo3IBLp7R6otGjRvm3Fz55AdplFP6UV/yQw4LCQo7FigU0ETvGJWZS1ujbBzup89vMVmu2ej8OiSqovxcIMjEM91CnPNPIomLBvu27It1ZYXD+6n8P5sZvBxs3zKpHKWCpAAAAAElFTkSuQmCC',
			'752F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGUNDkEVbRRoYHR0dGNDEWBsCUcWmiIQwIMQgboqaunTVyszQLCT3MTowNDq0MqLoZW0Aik1BFRNpEGl0CEAVC2hgBepEF2MMYQ1Fc8sAhR8VIRb3AQA8s8j4mX/AzwAAAABJRU5ErkJggg==',
			'6254' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QsQ2AMAwEP4U3gH1CQW+kpCAbwBSORDYII9AwJSljoASBvzu9rZOxX0bwp7ziR2wceStcsSZTIkGsGS9N7AVJsdLpV2Su/Mawb9s0h1D5uYwMGazaTeDCvFPMWCpXTy5iOu1H3HrrodhX/3swN34HeBzOAYXjtGwAAAAASUVORK5CYII=',
			'2FB3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WANEQ11DGUIdkMREpog0sDY6OgQgiQW0AsUaAhpEkHWDxBodGgKQ3TdtatjS0FVLs5DdF4CiDgwZHTDNY23AFBNpwHRLaChQDM3NAxV+VIRY3AcASqzNEbdBzSkAAAAASUVORK5CYII=',
			'0951' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDHVqRxVgDWFtZGximIouJTBFpdG1gCEUWC2gFik1lgOkFOylq6dKlqZlZS5HdF9DKGOgAJFH1MjSii4lMYQHaEYDhFkZHVPeB3Ax0SWjAIAg/KkIs7gMAox3LzSafPvQAAAAASUVORK5CYII=',
			'94BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYWllDGUMdkMREpjBMZW10dAhAEgtoZQhlbQh0EEERY3RFUgd20rSpS5cuDV0ZmoXkPlZXkVZ08xhaRUNd0cwTaAW6BU0M6BYMvdjcPFDhR0WIxX0AQB7Lax/Q2UcAAAAASUVORK5CYII=',
			'8F9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQx1CGVqRxUSmiDQwOjpMdUASC2gVaWBtCAgIQFPH2hDoIILkvqVRU8NWZkZmTUNyH0gdQwhcHdw8hobA0BA0McYGVHUQtziiiLEGAHmhjChiAxV+VIRY3AcAr+TLnSKh2UEAAAAASUVORK5CYII='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>