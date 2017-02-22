<?php

    include_once('config.php');
    require_once('libs/phpmailer/PHPMailerAutoload.php');
    require_once('libs/uap/parser.php');




    function replaceFirePlugin($fpid) {


        return $fpid;


    }




    function createColor($field, $color) {

        if(preg_match("/\brgba\b/i", $color) == false) {

            return $field.': '.$color.';';

        }

        else {

            $colorWork = str_replace('rgba(', '', $color);
            $colorWork = str_replace(')', '', $colorWork);

            $colorArray = explode(',', $colorWork);

            $colorRgb = 'rgb('.$colorArray[0].', '.$colorArray[1].', '.$colorArray[2].')';

            return $field.': '.$colorRgb.'; '.$field.': '.$color.';';

        }

    }


    function createLightColor($field, $color) {


        $myColor = str_replace('rgba(', '', str_replace('rgb(', '', str_replace(')', '', $color)));

        $colorArray = explode(',', $myColor);

        return $field.':rgb('.trim($colorArray[0]).','.trim($colorArray[1]).','.trim($colorArray[2]).');'.$field.':rgba('.trim($colorArray[0]).','.trim($colorArray[1]).','.trim($colorArray[2]).',0.6);';


    }



    function unlinkr($dir, $pattern = "*") {
        
        // find all files and folders matching pattern
        $files = glob($dir . "/$pattern"); 
        //interate thorugh the files and folders
        foreach($files as $file){ 
            //if it is a directory then re-call unlinkr function to delete files inside this directory     
            if (is_dir($file) and !in_array($file, array('..', '.')))  {
                unlinkr($file, $pattern);
                //remove the directory itself
                rmdir($file);
                } else if(is_file($file) and ($file != __FILE__)) {
                // make sure you don't delete the current script
                unlink($file); 
            }
        }
        
        rmdir($dir);

    }



	function safeString($string) {

  		$search = array(
    		'@<script[^>]*?>.*?</script>@si',   // Strip out javascript
    		'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
    		'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments
  		);
 
  		$output = preg_replace($search, '', $string);

  		return addslashes($output);

	}




	function makeNumber($string) {

		return preg_replace('/[^0-9]/', '', safeString($string));

	}

	function makeLetter($string) {

        return preg_replace('/[^a-zA-Z]/', '', safeString($string));

    }

	function makeChar($string) {

		return preg_replace('/[^äöüÄÖÜß#?=-_!,.[]+A-Za-z0-9 @]/', '', safeString($string));

	}



	function redirect($url) {

		header("Location: ".$url);
		die();

	}


    function br2nl( $input ) {
        
        return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
    
    }



    function stripslashesFull($input) {

        if (is_array($input)) {
            $input = array_map('stripslashesFull', $input);
        } elseif (is_object($input)) {
            $vars = get_object_vars($input);
            foreach ($vars as $k=>$v) {
                $input->{$k} = stripslashesFull($v);
            }
        } else {
            $input = stripslashes($input);
        }
        return $input;

    }



    function convertWebName($name) {

        $webname = stripslashesFull(trim($name));
        $webname = str_replace('\'', '', $webname);
        $webname = str_replace('Ü', 'UE', $webname);
        $webname = str_replace('Ö', 'OE', $webname);
        $webname = str_replace('Ä', 'AE', $webname);
        $webname = str_replace('ü', 'ue', $webname);
        $webname = str_replace('ö', 'oe', $webname);
        $webname = str_replace('ä', 'ae', $webname);
        $webname = str_replace('&', '_', $webname);
        $webname = strtolower($webname);
        $webname = str_replace(' ', '-', $webname);


        return $webname;

    }




    function getjpegsize($img_loc) {
    $handle = fopen($img_loc, "rb") or die("Invalid file stream.");
    $new_block = NULL;
    if(!feof($handle)) {
        $new_block = fread($handle, 32);
        $i = 0;
        if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
            $i += 4;
            if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
                // Read block size and skip ahead to begin cycling through blocks in search of SOF marker
                $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                $block_size = hexdec($block_size[1]);
                while(!feof($handle)) {
                    $i += $block_size;
                    $new_block .= fread($handle, $block_size);
                    if($new_block[$i]=="\xFF") {
                        // New block detected, check for SOF marker
                        $sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
                        if(in_array($new_block[$i+1], $sof_marker)) {
                            // SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
                            $size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
                            $unpacked = unpack("H*", $size_data);
                            $unpacked = $unpacked[1];
                            $height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
                            $width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
                            return array($width, $height);
                        } else {
                            // Skip block marker and read block size
                            $i += 2;
                            $block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
                            $block_size = hexdec($block_size[1]);
                        }
                    } else {
                        return FALSE;
                    }
                }
            }
        }
    }
    return FALSE;
}




    function truncate($string,$length=300,$append="&hellip;") {
        
        $string = trim($string);

        if(strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }

        return $string;

    }



    function array_insert(&$array, $position, $insert) {

        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
        
    }




    function getLatLng($loc) {

        $details = "https://maps.googleapis.com/maps/api/geocode/json?address=".rawurlencode($loc);

        $json = file_get_contents($details);

        $details = json_decode($json, TRUE);

        $lat = $details['results'][0]['geometry']['location']['lat'];
        $lng = $details['results'][0]['geometry']['location']['lng'];

        return $lat.','.$lng;

        // echo "<pre>"; print_r($details); echo "</pre>";

    }



    function writeCookie($cookie_name, $cookie_value, $cookie_expire = 0) {

        if($cookie_expire == 0) {
            $cookieExpire = 3600 * 24 * 60;
        }
        else {
            $cookieExpire = $cookie_expire;
        }

        if(setcookie($cookie_name, $cookie_value, time() + $cookieExpire, '/') == true) {
            // echo 'yes';
        }
        else {
            // echo 'no';
        }

    }

    function readCookie($cookie_name) {

        if(!isset($_COOKIE[$cookie_name])) {
    
            return false;

        }

        else {

            return $_COOKIE[$cookie_name];

        }

    }



    function hasAlpha($imgdata) {
        
        $w = imagesx($imgdata);
        $h = imagesy($imgdata);

        if($w>50 || $h>50){ //resize the image to save processing if larger than 50px:
            $thumb = imagecreatetruecolor(10, 10);
            imagealphablending($thumb, FALSE);
            imagecopyresized( $thumb, $imgdata, 0, 0, 0, 0, 10, 10, $w, $h );
            $imgdata = $thumb;
            $w = imagesx($imgdata);
            $h = imagesy($imgdata);
        }

        //run through pixels until transparent pixel is found:
        for($i = 0; $i<$w; $i++) {
            for($j = 0; $j < $h; $j++) {
                $rgba = imagecolorat($imgdata, $i, $j);
                if(($rgba & 0x7F000000) >> 24) return true;
            }
        }
        return false;

    }

	function validateImageType($image_path_or_resource) {
    
    	$img = @getimagesize($image_path_or_resource);
    
    	if(!empty( $img[2]))
    
        return image_type_to_mime_type($img[2]);
    
    	return false;
	
	}



    function getRandomBytes($nbBytes = 32) {

        $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
        if (false !== $bytes && true === $strong) {
            return $bytes;
        }
        else {
            throw new \Exception("Unable to generate secure token from OpenSSL.");
        }

    }

    function generateString($length) {

        return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode(getRandomBytes($length+1))),0,$length);

    }


    function sha256($string) {

        return hash('sha256', $string);

    }



    function formatEmail($email) {

        return trim(str_replace(' ', '', strtolower($email)));

    }



    function isBot() {

        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider|phpservermon/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
        else {
            return false;
        }

    }


    function logVisit($wid, $page, $referrer, $visitorId) {

        if(!isBot()) {

            
            $ua_info = parse_user_agent();

            $country = simplexml_load_file('http://www.geoplugin.net/xml.gp?ip='.$_SERVER['REMOTE_ADDR']);


            $stats = ORM::for_table('mwstats')->create();

            $stats->visitorId = $visitorId;
            $stats->websiteId = $wid;
            $stats->page = $page;
            $stats->ip = $_SERVER['REMOTE_ADDR'];
            $stats->os = $ua_info['platform'];
            $stats->browser = $ua_info['browser'];
            $stats->version = $ua_info['version'];
            $stats->country = $country->geoplugin_countryCode;

            $stats->save();


        }

    }



	function mymail($subject, $content, $receiver, $sender = 'FireBuild', $reply = 'info@firebuild.de') {


        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;

        $mail->SetLanguage ("de", "./phpmailer");
        $mail->isSMTP();
        $mail->Host = 'sslout.df.eu';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@mightyone.de';
        $mail->Password = '2209*pec07';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'utf-8'; 

        $mail->setFrom('info@mightyone.de', 'MightyOne');
        $mail->addAddress($receiver);
        $mail->addReplyTo($reply, $sender);

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $content.'<br><br><br><a href="https://www.mightyone.de" target="_blank"><img src="https://www.mightyone.de/img/logo.png" style="height:36px;"></a><br>';
        $mail->AltBody = strip_tags($content, '<br><br/>');;

        if(!$mail->send()) {

            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return 'fail';

        } else {

            return 'done';

        }


    }




    function compress($code) {
        $code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
        $code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $code);
        $code = str_replace('{ ', '{', $code);
        $code = str_replace(' }', '}', $code);
        $code = str_replace('; ', ';', $code);

        return $code;
    }




    function full_copy($source, $target) {
        if ( is_dir( $source ) ) {
            @mkdir( $target );
            $d = dir( $source );
            while ( FALSE !== ( $entry = $d->read() ) ) {
                if ( $entry == '.' || $entry == '..' ) {
                    continue;
                }
                $Entry = $source . '/' . $entry; 
                if ( is_dir( $Entry ) ) {
                    full_copy( $Entry, $target . '/' . $entry );
                    continue;
                }
                copy( $Entry, $target . '/' . $entry );
            }

            $d->close();
        }else {
            copy( $source, $target );
        }
    }



    function ftpUpload($ftpcon, $local, $server = '') {


        $list = scandir($local);
        for($i = 0; $i < count($list); $i++) {


            if($list[$i] != '.' && $list[$i] != '..') {


                // is dir
                if(strpos($list[$i], '.') == false) {


                    @ftp_mkdir($ftpcon, $list[$i]);

                    ftpUpload($ftpcon, $local.'/'.$list[$i], $list[$i]);


                }

                // is file
                else {


                    if($server == '') {

                        ftp_put($ftpcon, $list[$i], $local.'/'.$list[$i], FTP_BINARY);

                    }

                    else {

                        ftp_put($ftpcon, $server.'/'.$list[$i], $local.'/'.$list[$i], FTP_BINARY);

                    }



                }


            }


        }


    }



    function ftpTidy($ftpcon, $server = '.') {


        $list = ftp_nlist($ftpcon, $server);
        for($i = 0; $i < count($list); $i++) {


            if($list[$i] != '.' && $list[$i] != '..') {


                // is dir
                if(strpos($list[$i], '.') == false) {


                    ftpTidy($ftpcon, $list[$i]);

                    @ftp_rmdir($ftpcon, $list[$i]);


                }

                // is file
                else {


                    @ftp_delete($ftpcon, $list[$i]);


                }


            }


        }



    }





?>