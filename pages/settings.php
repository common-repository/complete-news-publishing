<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Check if news deletion requested
if (@$_GET['act'] == 'del') {
    // Check for numeric id
    if (is_numeric(@$_GET['id'])) {
        check_admin_referer('compnp_del_news', 'compnp_del_news_nonce');
        // Delete News
        $wpdb->query("DELETE FROM compnp_news WHERE news_id = '" . $_GET['id'] . "'");
        // Set message class
        $Msg_Class = 'compnp_smsg';
        // Set message
        $Msg = 'News Deleted Successfully!';
    }
}

// Check if delay update requested
if (is_numeric(@$_POST['delay'])) {
    check_admin_referer('compnp_delay', 'compnp_delay_nonce');
    // Update delay
    update_option('compnp_delay', $_POST['delay']);
    // Set message class
    $Msg_Class = 'compnp_smsg';
    // Set message
    $Msg = 'Delay updated Successfully!';
}

// Get all news
$news = $wpdb->get_results('SELECT * FROM compnp_news ORDER BY news_order ASC', ARRAY_A);

// Check for received message
if (isset($_GET['msg'])) {
    // Set message class
    $Msg_Class = 'compnp_smsg';
    // Set message
    if ($_GET['msg'] == 1) {
        $Msg = 'News added Successfully!';
    }else if ($_GET['msg'] == 2) {
        $Msg = 'News updated Successfully!';
    }
}
?>
<div class="compnp_main">
	<h1>Complete News Publishing</h1>
	<div><a href="<?php echo get_option('siteurl') . '/wp-admin/options-general.php?page=compnp_mainSettingsSlug&compnp_page=add'; ?>"><button type="button" class="compnp_btn">Add News</button></a></div>
	<form method="post" action="<?php echo get_option('siteurl') . '/wp-admin/options-general.php?page=compnp_mainSettingsSlug'; ?>">
	<?php wp_nonce_field('compnp_delay', 'compnp_delay_nonce', true, true); ?>
		<div><br />Fade Delay: <input type="text" name="delay" value="<?php echo esc_attr(get_option('compnp_delay')); ?>" maxlength="4" /> <input type="submit" value="Update" class="compnp_btn" /></div>
	</form>
    <?php if (@$Msg) { ?>
    <div class="compnp_tc <?php echo $Msg_Class; ?>"><?php echo $Msg; ?></div>
    <?php } ?>
    <div class="compnp_mt10">
    	<div class="compnp_newscont">
    		<div class="compnp_newscontent"><strong>News</strong></div>
    		<div class="compnp_newsoption"><strong>News Order</strong></div>
    		<div class="compnp_newsoption"><strong>Status</strong></div>
    		<div class="compnp_newsoption"><strong>Action</strong></div>
    	</div>
    	<?php
    	foreach ($news as $i) {
    	    $row_class = (@$row_class == 'compnp_white') ? 'compnp_grey' : 'compnp_white';
    	?>
    	<div class="compnp_newscont <?php echo $row_class; ?>">
    		<div class="compnp_newscontent"><?php echo $i['news_content']; ?></div>
    		<div class="compnp_newsoption"><?php echo $i['news_order']; ?></div>
    		<div class="compnp_newsoption"><?php echo $i['news_status']; ?></div>
    		<div class="compnp_newsoption"><a href="<?php echo get_option('siteurl') . '/wp-admin/options-general.php?page=compnp_mainSettingsSlug&compnp_page=edit&id=' . $i['news_id']; ?>"><button type="button" class="compnp_btn">Edit</button></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo wp_nonce_url(get_option('siteurl') . '/wp-admin/options-general.php?page=compnp_mainSettingsSlug&id=' . $i['news_id'] . '&act=del', 'compnp_del_news', 'compnp_del_news_nonce'); ?>"><button type="button" class="compnp_btn" onclick="return confirm('Delete this news?');">Delete</button></a></div>
    	</div>
    	<?php } ?>
	</div>
</div>