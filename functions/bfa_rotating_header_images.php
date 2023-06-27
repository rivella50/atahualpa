<?php
function bfa_rotating_header_images() {
	global $bfa_ata;

	$files = array();
	$img_folder = $bfa_ata['ata_images_dir'];

    if(!isset($bfa_ata['ata_images_dir']) 
    OR ($bfa_ata['ata_images_dir'] == '') ) {	
       	$img_folder = 'ata-images'; } 

    if($bfa_ata['images_root'] != "wp-content") {
           $imgpath = get_template_directory() . '/images/header/';
           $imgdir  = get_template_directory_uri() . '/images/header/';
        } else {
           $imgpath = ABSPATH . 'wp-content/' . $img_folder . '/header/';
           $imgdir  = content_url() . '/' . $img_folder . '/header/';
        }

		$dh  = @opendir($imgpath);
		if ($dh == FALSE) {
			echo $imgpath . ' - ' . $bfa_ata['images_root'] . '<br /><strong><span style="color: #ff0000;">the folder permission or path to your header image folder is incorrect </color></span><br />';
			return(FALSE);
		}
		while (FALSE !== ($filename = readdir($dh))) {
			if( preg_match('/\.jpg/i', $filename) || preg_match('/\.gif/i', $filename) || preg_match('/\.png/i', $filename) ) {
		   $files[] = $filename;
		   }
		}

		if(isset($bfa_ata['header_image_sort_or_shuffle'])) {
			if ($bfa_ata['header_image_sort_or_shuffle'] == "Sort") {
				sort($files); } 
			else { 
				shuffle($files); }
		}
		
		closedir($dh);

		foreach($files as $value) {
			$bfa_header_images[] = '\'' . $imgdir . $value . '\'';
		} 

return $bfa_header_images;
}
?>