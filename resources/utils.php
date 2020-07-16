<?php

/**
* Sanitize Method
* Desc: Method that prevents injection and data hijack
* 
* Inputs: Any string variable
* Outputs: String variable
*/

function sanitizeString($str)
{
	// To protect MySQL injection
	$sanitizeString = trim($str);
	$sanitizeString = strip_tags($sanitizeString);
	$sanitizeString = htmlspecialchars($sanitizeString);
	
	return $sanitizeString;
}

/**
* Name: strContains
* Desc: Checks if string contains a keyword
* 
* Inputs: haystack, needle
* Given: none
* Outputs: True if haystack contains needle
*/

function strContains($haystack, $needle)
{ 
    if (strpos($haystack, $needle) !== false) {
        return true;
    }
    else {
        return false;
    }
} 

/**
* Name: sentenceContains
* Desc: Checks if string contains one of the items in the array
* 
* Inputs: str, array
* Given: none
* Outputs: True if str contains an item in array
*/

function sentenceContains($str, $arr)
{
    foreach($arr as $a) {
        if ($str == $a) return true;
    }
    return false;
}

/**
* Sanitize Method for CKeditor
* Desc: Method that prevents injection and data hijack
* 
* Inputs: Any string variable
* Outputs: String variable
*/

function sanitizeEditor($description)
{
	//Description uses ckeditor. We want to strip malicious tags but not lose formatting
	$description = trim(preg_replace('/\t+/', '', $description));	//remove tabs and spaces (MUST for DB)
	$allowedTags = "<s><p><a><br><br /><em><i><b><strong><ol><ul><li><blockquote><h1><h2><h3><h4><code><pre><img><sup><sub><strike><hr>";
	$description = strip_tags($description,$allowedTags);
	$description = htmlentities($description);
	
	return $description;
}

/**
* Name: Upload Image File
* Desc: Uploads an image under FILE['image'], crops to 1:1 aspect ratio to a defined dimension
* 
* Inputs: maxDim, forceSquare, target_dir (folder relative to the function to store pics), fileSuffix (highly recommended)
* Given: $_FILES['image']  (a file upload)
* Outputs: Saves the processed file in destination. Return the final filename
*/

function uploadImageFile($maxDim, $forceSquare, $target_dir, $fileSuffix = "")
{ 
	//$maxDim = 800;	//the max dimensions for new image (1:1 ratio)
	//$target_dir = "../uploads/campaigns/";
	$forceWidth = $maxDim;
	$forceHeight = $maxDim;
	
	if ($forceSquare != true) {		//if false, we force to 4:3 ratio (rectangle)
		$forceWidth = $maxDim * 1.3334;	//width of 4:3
	}
	
	$file_name = $_FILES['image']['tmp_name'];	//temporary name of uploaded file

	// Check file size
	if ($_FILES["image"]["size"] > 7000000) {
		return "errFileTooBig";
	}
	
	$fileInfo = pathinfo($_FILES["image"]["name"]);	//we will need this to get extension
	$file_justname = basename($_FILES["image"]["name"],'.'.$fileInfo['extension']);		//name w/o extension
	$file_extension = '.'.$fileInfo['extension'];	//just the extension
	
	$destinationFilePath = $target_dir . $file_justname . $fileSuffix . $file_extension;	//full path of where we want to save image
	$imageFileType = strtolower(pathinfo($destinationFilePath, PATHINFO_EXTENSION));	//get the file type
	list($width, $height, $type, $attr) = getimagesize( $file_name );	//we can get the image $width and $height

	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
		return "errInvalidFileType";
	}

	if ( $width > $maxDim || $height > $maxDim ) {	//if width exceeds maxDim, or height exceeds maxDim, then we need croppping
		$target_filename = $file_name;	//set target file
		$ratio = $width/$height;	//find the ratio
		
		if ( $ratio > 1) {	//if the ratio not 1:1, then we need to make it 1:1
			$new_height =   $forceHeight;
			$new_width  =   floor($width * ($new_height / $height));
			$crop_x     =   ceil(($width - $height) / 2);	//for square img only
			if ($forceSquare != true) {		//change crop_x since we have a 4:3 rectangle img
				$unscaledWidth = $height * 1.3334;
				$crop_x     =   ceil(($width / 2) - ($unscaledWidth / 2));	//for square img only
			}
			$crop_y     =   0;
		} else {
			$new_width  =   $forceWidth;
			$new_height =   floor( $height * ( $new_width / $width ));
			$crop_x     =   0;
			$crop_y     =   ceil(($height - $width) / 2);	//account for the head
			
			if ($ratio < 0.7) {	//vertical photo
				$crop_y -= 70;	//only if image is vertical and not square
			}
		}
		$src = imagecreatefromstring( file_get_contents( $file_name ) );
		$dst = imagecreatetruecolor($forceWidth, $forceHeight);	//temporary image
		// Resize and crop
		imagecopyresampled($dst, $src, 0, 0, $crop_x, $crop_y, $new_width, $new_height, $width, $height);
					// canvas, source, origins, new origins,	new resolutions,	original resolutions
		
		imagedestroy( $src );
		imagejpeg($dst, $destinationFilePath, 92);	//save JPG img w/ OK quality
		//imagepng( $dst, $target_filename ); // adjust format as needed
		imagedestroy( $dst );
		return $file_justname . $fileSuffix . $file_extension;
	}
	else {	//if it cannot meet the maxDim requirements, then we reject file because img too small
		return "errImgTooSmall";
	}
} 

/**
* Check mobile
* Desc: Method that checks if mobile
* 
* Inputs: none
* Outputs: 1 or 0
*/

function isMobile()
{
	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	return 1;
	else
	return 0;
}

/**
* Seconds to Time Method
* Desc: Converts seconds to time
* 
* Inputs: Unix time in seconds
* Outputs: Return string with human-readable amount of time
*/

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

/**
* Time Elapsed String
* Desc: Converts seconds passed to human readable time
* 
* Inputs: Unix time in seconds
* Outputs: Return string with human-readable amount of time
*/

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime("@{$datetime}");
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}