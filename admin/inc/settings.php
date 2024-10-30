<?php
/* save info */
if( current_user_can( 'administrator' ) ){
	if(isset($_POST['action']) && $_POST["action"] == "_save_info"){
		if ( ! wp_verify_nonce( $_POST["nonce"], 'cs_nonce' ) ){
			die ( 'Invalid nonce!');
		}
		if(isset($_POST["captcha_solution_status"])){
			update_option( 'ESSITCO_esscaptcha_solution_status', 1);
		}else{
			update_option( 'ESSITCO_esscaptcha_solution_status', 0);
		}
		$attempts =  (intval($_POST['captcha_solution_attempts']) && $_POST['captcha_solution_attempts'] > 0) ? (int)$_POST['captcha_solution_attempts'] : false;
		$attempts ? update_option( 'ESSITCO_captcha_solution_attempts',$attempts) : "";
	}
	$captcha_solution_status = get_option("ESSITCO_esscaptcha_solution_status",false);
	$captcha_solution_attempts = get_option("ESSITCO_captcha_solution_attempts",false);

?>
<form method="post">
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="captcha_solution_status">Status*</label></th>
			<td>
				<label><input id="captcha_solution_status" type="checkbox" name="captcha_solution_status" value="1" <?php echo $captcha_solution_status ? 'checked="checked"':'' ?> /> Enable </label>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="captcha_solution_attempts">CAPTCHA Attempts</label></th>
			<td>
				<input id="captcha_solution_attempts" min="1" type="number" name="captcha_solution_attempts" value="<?php echo esc_attr($captcha_solution_attempts); ?>" />
			</td>
		</tr>
	</tbody>
</table>
<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( "cs_nonce" ); ?>" />
<input type="hidden" name="action" value="_save_info" />
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
</form>
<?php if(isset($sent)): ?>
	<div class="updated notice">
		<p>Settings saved.</p>
	</div>
<?php endif; ?>
<?php }else{ ?>
<h2>You don't have permissions.Only administrator can changes. </h2>
<?php } ?>