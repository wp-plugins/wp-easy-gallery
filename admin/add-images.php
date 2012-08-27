<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
global $easy_gallery_table;
global $easy_gallery_image_table;

$imageResults = null;

$galleryResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_table" );

//Select gallery
if(isset($_POST['select_gallery']) || isset($_POST['galleryId'])) {
	$gid = (isset($_POST['select_gallery'])) ? $_POST['select_gallery'] : $_POST['galleryId'];
	$imageResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_image_table WHERE gid = $gid ORDER BY sortOrder ASC" );
	$gallery = $wpdb->get_row( "SELECT * FROM $easy_gallery_table WHERE Id = $gid" );
}

//Add image
if(isset($_POST['galleryId']) && !isset($_POST['switch'])) {
	$gid = $_POST['galleryId'];
	$imagePath = $_POST['upload_image'];
	$imageTitle = $_POST['image_title'];
	$imageDescription = $_POST['image_description'];
	$sortOrder = 0 + $_POST['image_sortOrder'];
	$imageAdded = $wpdb->insert( $easy_gallery_image_table, array( 'gid' => $gid, 'imagePath' => $imagePath, 'title' => $imageTitle, 'description' => $imageDescription, 'sortOrder' => $sortOrder ) );
	
	if($imageAdded) {
	?>
		<div class="updated"><p><strong><?php _e('Image saved.' ); ?></strong></p></div>  
	<?php }
	//Reload images
	$imageResults = $wpdb->get_results( "SELECT * FROM $easy_gallery_image_table WHERE gid = $gid ORDER BY sortOrder ASC" );
}

//Edit image
if(isset($_POST['edit_image'])) {
	$imageEdited = $wpdb->update( $easy_gallery_image_table, array( 'imagePath' => $_POST['edit_imagePath'], 'title' => $_POST['edit_imageTitle'], 'description' => $_POST['edit_imageDescription'], 'sortOrder' => $_POST['edit_imageSort'] ), array( 'Id' => $_POST['edit_image'] ) );
		
		?>  
        <div class="updated"><p><strong><?php _e('Image has been edited.' ); ?></strong></p></div>  
        <?php
}

// Delete image
if(isset($_POST['delete_image'])) {
	$wpdb->query( "DELETE FROM $easy_gallery_image_table WHERE Id = '".$_POST['delete_image']."'" );
		
		?>  
        <div class="updated"><p><strong><?php _e('Image has been deleted.' ); ?></strong></p></div>  
        <?php	
}

?>

<div class='wrap'>
	<h2>Easy Gallery</h2>    
    <p>Add new images to gallery</p>
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
    <p style="float: left;"><input type="submit" name="Submit" class="button-primary" value="Switch Gallery" /></p>
    </form>
    <p style="float: right;"><a href="http://labs.hahncreativegroup.com/wordpress-plugins/wp-easy-gallery-pro-simple-wordpress-gallery-plugin/?src=wpeg" target="_blank"><strong><em>Try WP Easy Gallery Pro</em></strong></a></p>
    <div style="Clear: both;"></div>
    
    <form name="add_image_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
    <input type="hidden" name="galleryId" value="<?php echo $gallery->Id; ?>" />
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
            <th width="340">Image Path</th>
            <th width="150">Image Title</th>
            <th>Image Description</th>
            <th width="90">Sort Order</th>
            <th width="115"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Image Path</th>
            <th>Image Title</th>
            <th>Image Description</th>
            <th>Sort Order</th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>
        	<tr>
            	<td><input id="upload_image" type="text" size="36" name="upload_image" value="" />
					<input id="upload_image_button" type="button" value="Upload Image" /></td>
                <td><input type="text" name="image_title" size="20" value="" /></td>
                <td><input type="text" name="image_description" size="45" value="" /></td>
                <td><input type="text" name="image_sortOrder" size="10" value="" /></td>
                <td class="major-publishing-actions"><input type="submit" name="Submit" class="button-primary" value="Add Image" /></td>
            </tr>        	
        </tbody>
     </table>
     </form>
     <?php } ?>
     <?php
	 if(count($imageResults) > 0) {
	 ?>
     <br />
     <hr />
     <p>Edit existing images in this gallery</p>
    <table class="widefat post fixed" cellspacing="0">
    	<thead>
        <tr>
        	<th width="80">Image Preview</th>
            <th width="700">Image Info</th>
            <th width="115"></th>
            <th></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th>Image Preview</th>
            <th>Image Info</th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>        	
        	<?php foreach($imageResults as $image) { ?>				
            <tr>
            	<td><img src="<?php echo $image->imagePath; ?>" width="75" border="0" /></td>
                <td>
                	<form name="edit_image_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
                	<input type="hidden" name="edit_image" value="<?php echo $image->Id; ?>" />
                	<p><strong>Image Path:</strong> <input type="text" name="edit_imagePath" size="75" value="<?php echo $image->imagePath; ?>" /></p>
                    <p><strong>Image Title:</strong> <input type="text" name="edit_imageTitle" size="20" value="<?php echo $image->title; ?>" /></p>
                    <p><strong>Image Description:</strong> <input type="text" name="edit_imageDescription" size="75" value="<?php echo $image->description; ?>" /></p>
                    <p><strong>Sort Order:</strong> <input type="text" name="edit_imageSort" size="10" value="<?php echo $image->sortOrder; ?>" /></p>
                </td>
                <td class="major-publishing-actions">                
                <input type="submit" name="Submit" class="button-primary" value="Edit Image" />
                </form>
                </td>
                <td class="major-publishing-actions">
                <form name="delete_image_form" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
                <input type="hidden" name="delete_image" value="<?php echo $image->Id; ?>" />
                <input type="submit" name="Submit" class="button-primary" value="Delete Image" />
                </form>
                </td>
            </tr>
			<?php } ?>
        </tbody>
     </table>
     <?php } ?>
     <br />
<p><strong>Try WP Easy Gallery Pro</strong><br /><em>Pro Features include: Multi-image uploader, Enhanced admin section for easier navigation, Image preview pop-up, and more...</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/wp-easy-gallery-pro-simple-wordpress-gallery-plugin/?src=wpeg" target="_blank"><img title="WP-Easy-Gallery-Pro_468x88" src="http://labs.hahncreativegroup.com/wp-content/uploads/2012/02/WP-Easy-Gallery-Pro_468x88.gif" alt="" border="0" width="468" height="88" /></a></p>
<p><strong>Try Custom Post Donations Pro</strong><br /><em>This WordPress plugin will allow you to create unique customized PayPal donation widgets to insert into your WordPress posts or pages and accept donations. Features include: Multiple Currencies, Multiple PayPal accounts, Custom donation form display titles, and more.</em></p>
<p><a href="http://labs.hahncreativegroup.com/wordpress-plugins/custom-post-donations-pro/?src=wpeg"><img src="http://labs.hahncreativegroup.com/wp-content/uploads/2011/10/CustomPostDonationsPro-Banner.gif" width="374" height="60" border="0" alt="Custom Post Donations Pro" /></a></p>
<p><strong>Try ReFlex Gallery</strong><br /><em>A fully responsive WordPress image gallery plugin that is actually two galleries in one.</em><br />
<a href="http://wordpress-photo-gallery.com/" target="_blank">ReFlex Gallery</a></p>
<p><strong>Try Email Obfuscate</strong><br /><em>Email Obfuscate is a Lightweight jQuery plugin that prevents spam-bots from harvesting your email addresses by dynamically obfuscating email addresses on your site.</em><br /><a href="http://codecanyon.net/item/jquery-email-obfuscate-plugin/721738/?ref=HahnCreativeGroup" target="_blank">Email Obfuscate Plugin</a></p>
<br />
<p><em>Please consider making a donatation for the continued development of this plugin. Thanks.</em></p>
<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PMZ2FPNJPH59U" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></p>
</div>