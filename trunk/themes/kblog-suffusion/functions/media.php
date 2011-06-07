<?php
/**
 * Functions specific to processing media. E.g. attachments
 */

/**
 * Fetches the attachment for a post. This delegates the call to the handler for the appropriate attachment type.
 * The theme has inbuilt functions for image, audio, application, text and video. You can add your own handlers for other types
 * by creating a function called suffusion_your_type_attachment, where replace your_type with the new mime type.
 * Replace all occurrences of "." and "-" by "_". E.g. vnd-ms-powerpoint will give you suffusion_vnd_ms_powerpoint_attachment.
 *
 * @param bool $echo
 * @return mixed|string|void
 */
function suffusion_attachment($echo = true) {
	$file = wp_get_attachment_url();
	$mime = get_post_mime_type();
	$mime_type = explode('/', $mime);
	// Reverse the array so that in mime type audio/mpeg, a call to the function for mpeg gets precedence over audio
	$mime_type = array_reverse($mime_type);
	$attachment = wp_get_attachment_link();

	foreach ($mime_type as $type) {
		$type = str_replace(array('.','-'), '_', $type);
		if (function_exists("suffusion_{$type}_attachment")) {
			$attachment = call_user_func("suffusion_{$type}_attachment", $attachment, $mime, $file);
			$attachment = apply_filters('suffusion_attachment_html', $attachment);
			if ($echo) echo $attachment;
			return $attachment;
		}
	}
	$mime_type_class = suffusion_get_mime_type_class();
	$attachment = "<div class='$mime_type_class'>$attachment</div>";
	if ($echo) echo $attachment;
	return $attachment;
}

/**
 * Uses the mime type of an attachment to determine the class. It first splits by "/", then replaces the occurrences of
 * "." and "_" with "-"
 *
 * @param string $mime
 * @return string
 */
function suffusion_get_mime_type_class($mime = '') {
	if ($mime == '') {
		$mime = get_post_mime_type();
	}

	$raw_mime_type = explode('/', $mime);
	$mime_type = array();
	foreach ($raw_mime_type as $mime_type_component) {
		$mime_type[] = str_replace(array('.', '_'), '-', $mime_type_component);
	}
	$mime_type_class = implode(' ', $mime_type);
	return $mime_type_class;
}

/**
 * Displays an image attachment. If enabled, the EXIF data for the image is displayed too.
 *
 * @param string $attachment
 * @param string $mime
 * @param string $file
 * @return string
 */
function suffusion_image_attachment($attachment = '', $mime = '', $file = '') {
	global $suf_image_show_exif;
	$mime_type_class = suffusion_get_mime_type_class($mime);
	$display = apply_filters('suffusion_can_display_attachment', 'link', 'image');
	if ($display == false) {
		return;
	}

	$ret = $attachment;
	if ($suf_image_show_exif == 'show') {
		$ret .= suffusion_get_image_exif_data();
	}

	return $ret;
}

/**
 * Returns the EXIF data for images.
 *
 * @return string
 * @see http://www.walkernews.net/2009/04/13/turn-on-wordpress-feature-to-display-photo-exif-data-and-iptc-information/
 */
function suffusion_get_image_exif_data() {
	global $suf_image_exif_pieces;

	$exif_pieces = explode(',', $suf_image_exif_pieces);

	$ret = '<table class="exif">';
	$image_meta = wp_get_attachment_metadata();

	// Start to display EXIF and IPTC data of digital photograph
	if (in_array('file', $exif_pieces))
		$ret .= "<tr>\n\t<td>".__('File', 'suf_theme')."</td>\n\t<td>{$image_meta['file']}</td>\n</tr>\n";

	if (in_array('width', $exif_pieces))
		$ret .= "<tr>\n\t<td>".__('Width', 'suf_theme')."</td>\n\t<td>{$image_meta['width']}px</td>\n</tr>\n";

	if (in_array('height', $exif_pieces))
		$ret .= "<tr>\n\t<td>".__('Height', 'suf_theme')."</td>\n\t<td>{$image_meta['height']}px</td>\n</tr>\n";

	if (in_array('created_timestamp', $exif_pieces) && $image_meta['image_meta']['created_timestamp'])
		$ret .= "<tr>\n\t<td>".__('Date Taken', 'suf_theme')."</td>\n\t<td>".date(get_option('date_format'), $image_meta['image_meta']['created_timestamp'])."</td>\n</tr>\n";

	if (in_array('copyright', $exif_pieces) && $image_meta['image_meta']['copyright'])
		$ret .= "<tr>\n\t<td>".__('Copyright', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['created_timestamp']}</td>\n</tr>\n";

	if (in_array('credit', $exif_pieces) && $image_meta['image_meta']['credit'])
		$ret .= "<tr>\n\t<td>".__('Credit', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['credit']}</td>\n</tr>\n";

	if (in_array('title', $exif_pieces) && $image_meta['image_meta']['title'])
		$ret .= "<tr>\n\t<td>".__('Title', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['title']}</td>\n</tr>\n";

	if (in_array('caption', $exif_pieces) && $image_meta['image_meta']['caption'])
		$ret .= "<tr>\n\t<td>".__('Caption', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['caption']}</td>\n</tr>\n";

	if (in_array('camera', $exif_pieces) && $image_meta['image_meta']['camera'])
		$ret .= "<tr>\n\t<td>".__('Camera', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['camera']}</td>\n</tr>\n";

	if (in_array('focal_length', $exif_pieces) && $image_meta['image_meta']['focal_length'])
		$ret .= "<tr>\n\t<td>".__('Focal Length', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['focal_length']}</td>\n</tr>\n";

	if (in_array('aperture', $exif_pieces) && $image_meta['image_meta']['aperture'])
		$ret .= "<tr>\n\t<td>".__('Aperture', 'suf_theme')."</td>\n\t<td>f/{$image_meta['image_meta']['aperture']}</td>\n</tr>\n";

	if (in_array('iso', $exif_pieces) && $image_meta['image_meta']['iso'])
		$ret .= "<tr>\n\t<td>".__('ISO', 'suf_theme')."</td>\n\t<td>{$image_meta['image_meta']['iso']}</td>\n</tr>\n";

	// Convert the shutter speed retrieved from database to fraction
	if (in_array('shutter_speed', $exif_pieces) && $image_meta['image_meta']['shutter_speed'] != 0) {
		if ((1 / $image_meta['image_meta']['shutter_speed']) > 1) {
				if ((number_format((1 / $image_meta['image_meta']['shutter_speed']), 1)) == 1.3 || number_format((1 / $image_meta['image_meta']['shutter_speed']), 1) == 1.5 ||
						number_format((1 / $image_meta['image_meta']['shutter_speed']), 1) == 1.6 || number_format((1 / $image_meta['image_meta']['shutter_speed']), 1) == 2.5) {
					$shutter_speed = "1/" . number_format((1 / $image_meta['image_meta']['shutter_speed']), 1, '.', '') . " second";
				}
				else {
					$shutter_speed = "1/" . number_format((1 / $image_meta['image_meta']['shutter_speed']), 0, '.', '') . " second";
				}
			}
			else {
				$shutter_speed = $image_meta['image_meta']['shutter_speed'] . " seconds";
			}
		$ret .= "<tr>\n\t<td>".__('Shutter Speed', 'suf_theme')."</td>\n\t<td>$shutter_speed</td>\n</tr>\n";
	}

	$ret .= '</table>';
	return $ret;
}
/**
 * Displays an audio attachment either as a link or as a browser-embedded object.
 *
 * @param string $attachment
 * @param string $mime
 * @param string $file
 * @return string
 */
function suffusion_audio_attachment($attachment = '', $mime = '', $file = '') {
	$mime_type_class = suffusion_get_mime_type_class($mime);
	$display = apply_filters('suffusion_can_display_attachment', 'link', 'audio');
	if ($display == false) {
		return;
	}
	else if ($display == 'link') {
		return "<div class='attachment $mime_type_class'><span class='icon'>&nbsp;</span>$attachment</div>";
	}

	$ret = '<object type="' . $mime . '" class="player '.$mime_type_class.'" data="' . $file . '">';
	$ret .= '<param name="src" value="' . $file . '" />';
	$ret .= '<param name="autostart" value="false" />';
	$ret .= '<param name="controller" value="true" />';
	$ret .= '</object>';

	return $ret;
}

/**
 * Displays an application attachment either as a link or as a browser-embedded object.
 *
 * @param string $attachment
 * @param string $mime
 * @param string $file
 * @return string
 */
function suffusion_application_attachment($attachment = '', $mime = '', $file = '' ) {
	$mime_type_class = suffusion_get_mime_type_class($mime);
	$display = apply_filters('suffusion_can_display_attachment', 'link', 'application');
	if ($display == false) {
		return;
	}
	else if ($display == 'link') {
		return "<div class='attachment $mime_type_class'><span class='icon'>&nbsp;</span>$attachment</div>";
	}

	$ret = '<object class="'.$mime_type_class.'" type="' . $mime . '" data="' . $file . '" width="400">';
	$ret .= '<param name="src" value="' . $file . '" />';
	$ret .= '</object>';

	return $ret;
}

/**
 * Displays a text attachment either as a link or as a browser-embedded object.
 *
 * @since 0.3
 * @param string $mime attachment mime type
 * @param string $file attachment file URL
 * @return string
 */
function suffusion_text_attachment($attachment = '', $mime = '', $file = '' ) {
	$mime_type_class = suffusion_get_mime_type_class($mime);
	$display = apply_filters('suffusion_can_display_attachment', 'link', 'text');
	if ($display == false) {
		return;
	}
	else if ($display == 'link') {
		return "<div class='attachment $mime_type_class'><span class='icon'>&nbsp;</span>$attachment</div>";
	}

	$ret = '<object class="'.$mime_type_class.'" type="' . $mime . '" data="' . $file . '">';
	$ret .= '<param name="src" value="' . $file . '" />';
	$ret .= '</object>';

	return $ret;
}

/**
 * Displays a video attachment either as a link or as a browser-embedded object.
 *
 * @param string $attachment
 * @param string $mime
 * @param string $file
 * @return string
 */
function suffusion_video_attachment($attachment = '', $mime = '', $file = '' ) {
	$mime_type_class = suffusion_get_mime_type_class($mime);
	$display = apply_filters('suffusion_can_display_attachment', 'link', 'video');
	if ($display == false) {
		return;
	}
	else if ($display == 'link') {
		return "<div class='attachment $mime_type_class'><span class='icon'>&nbsp;</span>$attachment</div>";
	}

	if ( $mime == 'video/asf' )
		$mime = 'video/x-ms-wmv';

	$ret = '<object type="' . $mime . '" class="player '.$mime_type_class.'" data="' . $file . '">';
	$ret .= '<param name="src" value="' . $file . '" />';
	$ret .= '<param name="autoplay" value="false" />';
	$ret .= '<param name="allowfullscreen" value="true" />';
	$ret .= '<param name="controller" value="true" />';
	$ret .= '</object>';

	return $ret;
}
?>