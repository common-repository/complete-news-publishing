<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Include functions file
require_once(dirname(__FILE__) . '/../include/functions.php');

// Get news info
if (is_numeric(@$_GET['id'])) {
    $News = $wpdb->get_results("SELECT * FROM compnp_news WHERE news_id = '" . $_GET['id'] . "'", ARRAY_A);
    $news_content = $News['0']['news_content'];
    $news_link = $News['0']['news_link'];
    $news_order = $News['0']['news_order'];
    $news_status = $News['0']['news_status'];
} else {
    echo 'Invalid News!';
    exit;
}

// Check if form submitted
if (isset($_POST['news_order'])) {
    $news_content = sanitize_text_field($_POST['news_content']);
    $news_link = esc_url($_POST['news_link']);
    $news_order = $_POST['news_order'];
    $news_status = $_POST['news_status'];
    // Validate submitted data
    if (compnp_checklink($news_link) && compnp_checkLength($news_content) && is_numeric($_POST['news_order'])) {
        $wpdb->query("UPDATE compnp_news SET news_content = '$news_content', news_link = '$news_link', news_order = '" . $_POST['news_order'] . "', news_status = '" . sanitize_text_field($_POST['news_status']) . "', news_date = '" . date('Y-m-d H:i:s') . "' WHERE news_id = '" . $_GET['id'] . "'");
        header('location:' . admin_url('admin.php?page=compnp_mainSettingsSlug&msg=2'));
        exit;
    }
    require_once(dirname(__FILE__) . '/../../../../wp-admin/admin-header.php');
}
?>
<div class="compnp_main">
	<h1>Complete News Publishing</h1>
	<h2>Update News</h2>
	<?php if (@$Msg) { ?>
    <div class="compnp_tc <?php echo $Msg_Class; ?>"><?php echo $Msg; ?></div>
    <?php } ?>
    <form method="post" action="<?php echo admin_url('admin.php?page=compnp_mainSettingsSlug&noheader=true&compnp_page=edit&id=' . $_GET['id']); ?>" enctype="multipart/form-data">
    	<div>
            <p class="compnp_fldname"><br />News Order:</p>
            <select name="news_order" id="news_order" class="compnp_txtflds">
            	<?php for ($i = 1; $i <= 50; $i++) { ?>
            	<option value="<?php echo $i; ?>" <?php echo ($news_order == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
            	<?php } ?>
            </select>
        </div>
        <div>
            <p class="compnp_fldname">News Status:</p>
            <select name="news_status" id="news_status" class="compnp_txtflds">
            	<option value="Yes" <?php echo ($news_status == 'Yes') ? 'selected' : ''; ?>>Active</option>
            	<option value="No" <?php echo ($news_status == 'No') ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        <div>
            <p class="compnp_fldname">News Link:</p>
            <input type="text" placeholder="News Link" class="compnp_txtfld" name="news_link" id="news_link" value="<?php echo $news_link; ?>" />
            <div class="compnp_frmerr <?php if (compnp_checklink($news_link)) { ?>compnp_dn<?php } ?>">Please enter a valid link.</div>
        </div>
        <div>
            <p class="compnp_fldname">News Content:</p>
            <textarea placeholder="News Content" class="compnp_txtfld" name="news_content" id="news_content" rows="5"><?php echo wp_unslash($news_content); ?></textarea>
            <div class="compnp_frmerr <?php if (compnp_checkLength($news_content)) { ?>compnp_dn<?php } ?>">News is too short.</div>
        </div>
        <div>
            <br /><input type="submit" value="Update News" class="compnp_btn" />
        </div>
    </form>
</div>