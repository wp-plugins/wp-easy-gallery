<?php
global $wpdb;
global $easy_gallery_table;

if(isset($_POST['galleryId'])) {
	$wpdb->query( "DELETE FROM $easy_gallery_table WHERE gid = '".$_POST['galleryId']."'" );
	$wpdb->query( "DELETE FROM $easy_gallery_table WHERE Id = '".$_POST['galleryId']."'" );
		
	?>  
	<div class="updated"><p><strong><?php _e('Gallery has been deleted.' ); ?></strong></p></div>  
	<?php	
}

$galleryResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_table" );
?>
<div class='wrap'>
	<h2>Easy Gallery</h2>
    <p>This is a listing of all galleries.</p>
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
        	<th>Gallery Name</th>
            <th>Gallery Short Code</th>
            <th>Description</th>
            <th width="136"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th>Gallery Name</th>
            <th>Gallery Short Code</th>
            <th>Description</th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>
        	<?php foreach($galleryResults as $gallery) { ?>				
            <tr>
            	<td><?php echo $gallery->name; ?></td>
                <td><input type="text" size="40" value="[EasyGallery id='<?php echo $gallery->slug; ?>']" /></td>
                <td><?php echo $gallery->description; ?></td>
                <td class="major-publishing-actions">
                <form name="delete_gallery_<?php echo $gallery->Id; ?>" method ="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                	<input type="hidden" name="galleryId" value="<?php echo $gallery->Id; ?>" />
                    <input type="submit" name="Submit" class="button-primary" value="Delete Gallery" />
                </form>
                </td>
            </tr>
			<?php } ?>
        </tbody>
     </table>
     <br />
     <p><em>Please consider making a donatation for the continued development of this plugin. Thanks.</em></p>
<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PMZ2FPNJPH59U" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>
<br />
<p><strong>Try WP Easy Gallery Pro</strong><br /><em>Pro Features include: Multi-image uploader, Enhanced admin section for easier navigation, Image preview pop-up, and more...</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/wp-easy-gallery-pro-simple-wordpress-gallery-plugin/" target="_blank"><img title="WP-Easy-Gallery-Pro_468x88" src="http://labs.hahncreativegroup.com/wp-content/uploads/2012/02/WP-Easy-Gallery-Pro_468x88.gif" alt="" border="0" width="468" height="88" /></a></p>
<p><strong>Try Custom Post Donations Pro</strong><br /><em>This WordPress plugin will allow you to create unique customized PayPal donation widgets to insert into your WordPress posts or pages and accept donations. Features include: Multiple Currencies, Multiple PayPal accounts, Custom donation form display titles, and more.</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/custom-post-donations-pro/"><img src="http://labs.hahncreativegroup.com/wp-content/uploads/2011/10/CustomPostDonationsPro-Banner.gif" width="374" height="60" border="0" alt="Custom Post Donations Pro" /></a><br /><em>Only $14.95</em></p>
</div>