<?php
global $wpdb;
if(isset($_POST['galleryId'])) {
	$wpdb->query( "DELETE FROM wp_easy_gallery_images WHERE gid = '".$_POST['galleryId']."'" );
	$wpdb->query( "DELETE FROM wp_easy_gallery WHERE Id = '".$_POST['galleryId']."'" );
		
	?>  
	<div class="updated"><p><strong><?php _e('Gallery has been deleted.' ); ?></strong></p></div>  
	<?php	
}

$galleryResults = $wpdb->get_results( "SELECT * FROM wp_easy_gallery" );
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
                <td><input type="text" size="40" value="[EasyGallery id=<?php echo $gallery->slug; ?>]" /></td>
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
     <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PMZ2FPNJPH59U" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>
</div>