# Basic PHP Framework
An easy to read micro PHP framework to give you a jumpstart on your PHP projects. A custom framework you can start using in minutes instead of days.

## Installation ##
Simply clone this repo into your project's root directory. Make sure to keep the .htaccess file intact. 

Set yourself as the owner and give 0755 file permissions.

## Usage ##
The `index.php` page contains the code for routing. You can edit your desired routes there. The routing is handled by **Xesau Router**. 

For details: [https://github.com/Xesau/Router](https://github.com/Xesau/Router)

Put your class files in the `resources/` folder. Put your view files (can be HTML or PHP) in the `views/` folder. The PHP templating is handled by **Simple Template Engine**. 

For details: [https://github.com/ddycai/simple-template-engine](https://github.com/ddycai/simple-template-engine)

## Handy Functions ##
If you would like handy functions like: 

sanitizeString($str)<br />
sanitizeEditor($description)<br />
isMobile()<br />
strContains($haystack, $needle)<br />
sentenceContains($str, $arr)<br />
time\_elapsed\_string($datetime, $full = false)<br />
uploadImageFile($maxDim, $forceSquare, $target_dir, $fileSuffix = "")<br />

You can run `include('resources/utils.php');` to make those functions accessible.