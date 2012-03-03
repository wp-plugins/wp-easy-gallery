<?php
global $wpdb;
global $easy_gallery_table;
global $easy_gallery_image_table;

$galleryResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_table" );

//Select gallery
if(isset($_POST['select_gallery']) || isset($_POST['galleryId'])) {
	$gid = (isset($_POST['select_gallery'])) ? $_POST['select_gallery'] : $_POST['galleryId'];
	$imageResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_image_table WHERE gid = $gid ORDER BY sortOrder ASC" );
	$gallery = $wpdb->get_row( "SELECT * FROM $easy_gallery_table WHERE Id = $gid" );
}
	
if(isset($_POST['hcg_edit_gallery']))
{
	if($_POST['galleryName'] != "") {
	  $galleryName = $_POST['galleryName'];
	  $galleryDescription = $_POST['galleryDescription'];	  
	  $slug = strtolower(str_replace(" ", "", $_POST['galleryName']));
	  $imagepath = str_replace("\\", "", $_POST['upload_image']);
	  $thumbwidth = $_POST['gallerythumbwidth'];
	  $thumbheight = $_POST['gallerythumbheight'];
	  
	  
	  
	  if(isset($_POST['hcg_edit_gallery'])) {
		  $imageEdited = $wpdb->update( $easy_gallery_table, array( 'name' => $galleryName, 'slug' => $slug, 'description' => $galleryDescription, 'thumbnail' => $imagepath, 'thumbwidth' => $thumbwidth, 'thumbheight' => $thumbheight ), array( 'Id' => $_POST['hcg_edit_gallery'] ) );
			  
			  ?>  
			  <div class="updated"><p><strong><?php _e('Gallery has been edited.' ); ?></strong></p></div>  
			  <?php
	  }
	}}
?>
<div class='wrap'>
	<h2>Easy Gallery - Edit Galleries</h2>
    <?php if(!isset($_POST['select_gallery']) && !isset($_POST['galleryId'])) { ?>
    <p>Select a galley</p>		
    <form name="gallery" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    	<select name="select_gallery" onchange="gallery.submit()">
        	<option> - SELECT A GALLERY - </option>
			<?php
				foreach($galleryResults as $gallery) {
					?><option value="<?php echo $gallery->Id; ?>"><?php echo $gallery->name; ?></option>
                <?php
				}
			?>
        </select>
    </form>
    <?php } else if(isset($_POST['select_gallery']) || isset($_POST['galleryId'])) { ?>    
    <h3>Gallery: <?php echo $gallery->name; ?></h3>
    <form name="switch_gallery" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="switch" value="true" />
    <p><input type="submit" name="Submit" class="button-primary" value="Switch Gallery" /></p>
    </form>
	
    <p>This is where you can edit existing galleries.</p>
    
    <form name="hcg_add_gallery_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="hcg_edit_gallery" value="<?php echo $gid; ?>" />
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
                <td><input type="text" size="30" name="galleryName" value="<?php echo $gallery->name; ?>" /></td>
                <td>This name is the internal name for the gallery.<br />Please avoid non-letter characters such as ', ", *, etc.</td>
            </tr>
            <tr>
            	<td><strong>Enter Gallery Description:</strong></td>
                <td><input type="text" size="50" name="galleryDescription" value="<?php echo $gallery->description; ?>" /></td>
                <td>This description is for internal use.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Imagepath:</strong></td>
                <td><input id="upload_image" type="text" size="36" name="upload_image" value="<?php echo $gallery->thumbnail; ?>" />
					<input id="upload_image_button" type="button" value="Upload Image" /></td>
                <td>This is the file path for the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Width:</strong></td>
                <td><input type="text" size="10" name="gallerythumbwidth" value="<?php echo $gallery->thumbwidth; ?>" /></td>
                <td>This is the width of the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td><strong>Enter Thumbnail Height:</strong></td>
                <td><input type="text" size="10" name="gallerythumbheight" value="<?php echo $gallery->thumbheight; ?>" /></td>
                <td>This is the height of the gallery thumbnail image.</td>
            </tr>
            <tr>
            	<td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Edit Gallery" /></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
	</table>
    </form>
    <?php } ?>
    <br />
     <p><em>Please consider making a donatation for the continued development of this plugin. Thanks.</em></p>
<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PMZ2FPNJPH59U" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>
<br />
<p><strong>Try WP Easy Gallery Pro</strong><br /><em>Pro Features include: Multi-image uploader, Enhanced admin section for easier navigation, Image preview pop-up, and more...</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/wp-easy-gallery-pro-simple-wordpress-gallery-plugin/?src=wpeg" target="_blank"><img title="WP-Easy-Gallery-Pro_468x88" src="http://labs.hahncreativegroup.com/wp-content/uploads/2012/02/WP-Easy-Gallery-Pro_468x88.gif" alt="" border="0" width="468" height="88" /></a></p>
<p><strong>Try Custom Post Donations Pro</strong><br /><em>This WordPress plugin will allow you to create unique customized PayPal donation widgets to insert into your WordPress posts or pages and accept donations. Features include: Multiple Currencies, Multiple PayPal accounts, Custom donation form display titles, and more.</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/custom-post-donations-pro/?src=wpeg"><img src="http://labs.hahncreativegroup.com/wp-content/uploads/2011/10/CustomPostDonationsPro-Banner.gif" width="374" height="60" border="0" alt="Custom Post Donations Pro" /></a><br /><em>Only $14.95</em></p>
</div>