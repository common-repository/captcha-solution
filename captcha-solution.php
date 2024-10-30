<?php
/**
 * Plugin Name: Captcha Solution 
 * Plugin URI: https://www.essitco.com/
 * Description: Captcha Solution is a 100% effective CAPTCHA for WordPress that integrates Contact Form 7 
 * Version: 1.0
 * Author: Essitco
 * Author URI: https://profiles.wordpress.org/essitco
 * Developer: Rajat Saini
 */

add_action( 'admin_menu', 'ESSITCO_captcha_solution_admin_default_setup' );

function ESSITCO_captcha_solution_admin_default_setup() {
    add_options_page( __( 'Captcha Solution', 'essitco_captcha_solution' ), __( 'Captcha Solution', 'essitco_captcha_solution' ), 'manage_options', 'essitco_captcha_solution', 'essitco_captcha_solution' );
}

function essitco_captcha_solution() {
	include('admin/settings.php');
}
 
if (is_admin())
{
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'ESSITCO_captcha_solution_setting_link');
}
 
function ESSITCO_captcha_solution_setting_link($links)
{
	$links[] = '<a href="admin.php?page=essitco_captcha_solution&tab=settings">Settings</a>';

	return $links;
}

register_activation_hook( __FILE__, 'ESSITCO_esscs_active_save_settings' );
function ESSITCO_esscs_active_save_settings(){
	update_option( 'ESSITCO_esscaptcha_solution_status', 1);
	update_option( 'ESSITCO_captcha_solution_attempts', 5);
}

register_deactivation_hook( __FILE__, 'ESSITCO_esscs_remove_settings' );
function ESSITCO_esscs_remove_settings(){
	delete_option('ESSITCO_esscaptcha_solution_status');
	delete_option('ESSITCO_captcha_solution_attempts');
}

add_action( 'wp_head', 'inline_css', 0 );
function inline_css() {
  echo '<style>.cs-captcha-img:after{color: #f56e28;content: "\f463";font: 400 20px/1 dashicons;cursor: pointer;}</style>';
  echo '<script>function reLoadCaptcha(){var cs_random = Math.random( );document.getElementById("cs-captcha-id").src="?cs_captcha="+cs_random;}</script>';
}

$RJ_captcha_solution_is_active = get_option("ESSITCO_esscaptcha_solution_status",false);
if($RJ_captcha_solution_is_active){
	add_action( 'admin_init', 'ESSITCO_add_tag_generator_captcha_solution', 45 );
}

/* tag generator*/

function ESSITCO_add_tag_generator_captcha_solution() {
	if ( ! function_exists( 'wpcf7_add_tag_generator' ) )
		return;

	wpcf7_add_tag_generator( 'cs_captcha', __( 'Captcha Solution', 'cs_captcha' ), 'wpcf7-essitco_captcha_solution', 'ESSITCO_tg_pane_captcha_solution' );
}
function ESSITCO_tg_pane_captcha_solution( $contact_form ) {
	echo '
	<div class="control-box">
		<fieldset>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="esscs-tag-generator-panel-captcha_solution-name">' . esc_html__( 'Name', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="name" class="tg-name oneline" id="esscs-tag-generator-panel-captcha_solution-name" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="esscs-tag-generator-panel-captcha_solution-id">' . esc_html__( 'Id attribute', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="id" class="idvalue oneline option" id="esscs-tag-generator-panel-captcha_solution-id" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="esscs-tag-generator-panel-captcha_solution-class">' . esc_html__( 'Class attribute', 'contact-form-7' ) . '</label>
						</th>
						<td>
							<input type="text" name="class" class="classvalue oneline option" id="esscs-tag-generator-panel-captcha_solution-class" />
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</div>
	<div class="insert-box">
		<input type="text" name="captcha_solution" class="tag code" readonly="readonly" onfocus="this.select();">
		<div class="submitbox">
			<input type="button" class="button button-primary insert-tag" value="' . esc_attr__( 'Insert Tag', 'contact-form-7' ) . '">
		</div>
		<br class="clear">
	</div>';
}

/* code */
add_action( 'wpcf7_init', 'ESSITCO_add_shortcode_captcha_solution' );
function ESSITCO_add_shortcode_captcha_solution() {
    wpcf7_add_shortcode( 'captcha_solution', 'ESSITCO_captcha_solution_shortcode_handler', true );
}


function ESSITCO_captcha_solution_shortcode_handler($tag) {
    if (!is_array($tag) && !is_object($tag)) return '';
	
	$tag = (array)$tag;
    $name = $tag['name'];
    if (empty($name)) return '';
	$captcha_solution_is_active = get_option("ESSITCO_esscaptcha_solution_status",false);
	if($captcha_solution_is_active){
		$html = '<span class="wpcf7-form-control-wrap '.esc_attr($name).'"><span class="cs-captcha-main"><img width="auto" height="auto" alt="captcha" id="cs-captcha-id" src="?cs_captcha='.time().'" /><span class="cs-captcha-img" onclick="reLoadCaptcha()"></span></span><br><input type="text" name="cs_captcha" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false"></span>';
	}else{
		$html = '';
	}
    return $html;
}

class ESSITCO_Admin{
	var $font = __dir__ . '/monofont.ttf';

	function ESSITCO_generateCode($characters,$id) {
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) {
				$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
				$i++;
		}			
		return $code;
	}
	
	function ESSITCO_Admin($width,$height,$characters,$id)
	{
		if(!session_id()) {
			session_start();
		}

		$code = $this->ESSITCO_generateCode($characters,$id);
		$font_size = $height * 0.50;
		@imagecreate($width, $height) ? $image = @imagecreate($width, $height) :  die('Cannot initialize new GD image stream');
		$background_color = imagecolorallocate($image, 78,56,120);
		$text_color = imagecolorallocate($image, 255, 255, 255);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		$orange = imagecolorallocate($image, 220, 210, 60);
		imageline($image, 10, 80, 50, 10, $orange);
		imageline($image, 10, 30, 50, 10, $orange);
		imageline($image, 30, 80, 70, 10, $orange);
		imageline($image, 50, 80, 90, 10, $orange);
		imagettfbbox($font_size, 0, $this->font, $code) ? $textbox = imagettfbbox($font_size, 0, $this->font, $code) :  die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) ? "" :  die('Error in imagettftext function');
		
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$_SESSION['security_code_recaptcha'] = $code;
	}
}
 
if(isset($_GET['cs_captcha'])){
	$captcha = new ESSITCO_Admin('120','40','6','a');
	die;
}

add_filter( 'wpcf7_validate_captcha_solution', 'ESSITCO_captcha_solution_validation_filter', 20, 2 );

function ESSITCO_captcha_solution_validation_filter( $result, $tag ) {
	if(!session_id()) {
		session_start();
	}

	if (strpos($tag->name, 'cs_captcha') !== false) {
		$captcha_solution_is_active = get_option("ESSITCO_esscaptcha_solution_status",false);
		$cs_attempts = get_option("ESSITCO_captcha_solution_attempts",false);
		if(!$captcha_solution_is_active){
			return $result;
		}
		if(isset($_SESSION['cs_expire'])){
			if(time() > $_SESSION['cs_expire']){
				unset($_SESSION['cs_expire']);
				$_SESSION["cs_login_attempts"] = 1;
			}
		}
		$attempted = $_SESSION["cs_login_attempts"];
		if($attempted >= $cs_attempts){
			$result->invalidate($tag, "CAPTCHA expired for 5 minutes. Please try after 5 minutes." );
			if(!isset($_SESSION['cs_expire'])){
				$_SESSION['cs_expire'] = time() + (5 * 60) ;
			}
			return $result;
		}
		if(!isset($_POST['cs_captcha'])){
			$result->invalidate($tag, "CAPTCHA is required field." );
		}else if($_POST['cs_captcha'] == ""){
			$result->invalidate($tag, "The field is required." );
		}else{
			$security_code_recaptcha = $_SESSION["security_code_recaptcha"];
			if(strcmp($_POST['cs_captcha'],$security_code_recaptcha) != 0){
				if($attempted > 1){
					$atmpd = (int)$cs_attempts - (int)$attempted;
					$Invalid_msg = "Invalid CAPTCHA. ".$atmpd." attempts left!";
				}else{
					$Invalid_msg = "Invalid CAPTCHA.";
				}
				$_SESSION["cs_login_attempts"]++;
				$result->invalidate($tag, $Invalid_msg );
			}
		}
    }
    return $result;
}
?>