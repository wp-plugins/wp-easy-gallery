<?php
	if(isset($_POST['hcg_add_gallery']))
	{
		if($_POST['galleryName'] != "") {
		  $galleryName = $_POST['galleryName'];
		  $galleryDescription = $_POST['galleryDescription'];	  
		  $slug = strtolower(str_replace(" ", "", $_POST['galleryName']));
		  $imagepath = str_replace("\\", "", $_POST['upload_image']);
		  $thumbwidth = $_POST['gallerythumbwidth'];
		  $thumbheight = $_POST['gallerythumbheight'];
		  
		  global $wpdb;
		  
		  $galleryAdded = $wpdb->insert( 'wp_easy_gallery', array( 'name' => $galleryName, 'slug' => $slug, 'description' => $galleryDescription, 'thumbnail' => $imagepath, 'thumbwidth' => $thumbwidth, 'thumbheight' => $thumbheight ) );
		  
		  if($galleryAdded) {
		  ?>  
		  <div class="updated"><p><strong><?php _e('Gallery Added.' ); ?></strong></p></div>  
		  <?php
		  }
		}
		else {
			?>  
		  <div class="updated"><p><strong><?php _e('Please enter a gallery name.' ); ?></strong></p></div>  
		  <?php
		}
	}
?>
<div class='wrap'>
	<h2>Easy Gallery - Add Galleries</h2>
    <?php
	if($galleryAdded) {
	?>
    <div class="updated"><p>Copy and paste this code into the page or post that you would like to display the gallery.</p>
    <p><input type="text" name="galleryCode" value="[EasyGallery id='<?php echo $slug; ?>']" size="40" /></p></div>
    <?php }
	else {
	?>
    <p>This is where you can create new galleries. Once the new gallery has been added, a short code will be provided for use in posts.</p>
    <?php } ?>
	
    <form name="hcg_add_gallery_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="hcg_add_gallery" value="true" />
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
        	<th width="250">Field Name</th>
            <th>Entry</th>
            <th>Description</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th>Field Name</th>
            <th>Entry</th>
            <th>Description</th>
        </tr>
        </tfoot>
        <tbody>
        	<tr>
            	<td><strong>Enter Gallery Name:</strong></td>
                <td><input type="text" size="30" name="galleryName" value="<?php echo $galleryName; ?>" /></td>
                <td>This name is the internal name for the gallery.<br />Please avoid non-letter characters such as ', ", *, etc.</td>
            </tr>
            <tr>
            	<td><strong>Enter Gallery Description:</strong></td>
                <td><input type="text" size="50" name="galleryDescription" value="<?php echo $galleryDescription; ?>" /></td>
                <td>This description is for internal use.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Imagepath:</strong></td>
                <td><input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo $imagepath; ?>" />
					<input id="upload_image_button" type="button" value="Upload Image" /></td>
                <td>This is the file path for the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Width:</strong></td>
                <td><input type="text" size="10" name="gallerythumbwidth" value="<?php echo $thumbwidth; ?>" /></td>
                <td>This is the width of the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Height:</strong></td>
                <td><input type="text" size="10" name="gallerythumbheight" value="<?php echo $thumbheight; ?>" /></td>
                <td>This is the height of the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Add Gallery" /></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
	</table>
    </form>
<br />
<p><strong>Try Custom Post Donations Pro</strong><br /><em>This WordPress plugin will allow you to create unique customized PayPal donation widgets to insert into your WordPress posts or pages and accept donations.</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/custom-post-donations-pro/"><img src="http://labs.hahncreativegroup.com/wp-content/uploads/2011/10/CustomPostDonationsPro-Banner.gif" width="374" height="60" border="0" alt="Custom Post Donations Pro" /></a><br /><em>Only $14.95</em></p>
<br />
<p><em>Please consider making a donatation for the continued development of this plugin. Thanks.</em></p>
<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PMZ2FPNJPH59U" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>
</div>