<?php namespace App\Engine\Libraries;

class Library {


    // CSRF Field
    public static function csrf_field() {
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$_SESSION['csrf_token']."\" />";
    }


    // CSRF Hash
    public static function csrf_hash() {
        return $_SESSION['csrf_token'];
    }
    
    
    // Forms
    public static function getForm(string $val) {
        
        if (self::hasFlashData('form'))
            return self::getFlashData('form')->{$val};
        else 
            return null;
    }
    
    public static function setForm(array $data) {
        
        self::setFlashData('form', $data);
    }
    
    


    // Loading custon helper files
    public static function helpers(array $arrayPaths) {

        foreach ($arrayPaths as $path) {
            if (!file_exists(APPROOT . "/Helpers/{$path}.php")) die("Wrong helper file path for - <b>{$path}.php</b>");

            require_once APPROOT . "/Helpers/{$path}.php";
        }
    }

    // Page not found
    public static function notFound($params = ["code" => 404, "url" => NULL, "text" => NULL]) {

        $code = $params["code"] ?? 404;
        $url = $params["url"] ?? NULL;
        $text = $params["text"] ?? 'Sorry, but the page you are looking for is not found. Please make sure you have typed the correct url.';
        
        $status = self::statuc_code($params['code']);

        if (!$url) {
            $dom = '';
            
            $dom .= '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Page not found</title>
                <style>
                    html, body {margin: 0; padding: 0; font-family: sans-serif;
                    width: 100%; height: 100%; color: #606472}
                    a {text-decoration: none; color: #2196f3;}
                    h1, h2, h3 {font-size: 50px; color: #606472;
                        margin: 0; padding: 0; margin-bottom: 15px;}
                    h4, h5, h6 {font-size: 30px; color: #606472;
                        margin: 0; padding: 0; margin-bottom: 15px;}
                    p {line-height: 28px;}
                    .text-large {font-size: 130px; margin-bottom: 0;}
                    .container {
                        padding: 0 45px;
                        max-width: 1200px;
                        margin: auto;
                        height: 100%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <center>
                        <div style="margin-bottom: 25px;">
                            <h1 class="text-large">'.$code.'</h1>
                            <h4>'.$status["text"].'</h4>
                        </div>

                        <div style="margin-bottom: 25px;">';
                        $dom .= '<p>'.$text.'</p>';
                        $dom .= '</div>
                        <a href="'.URLROOT.'">Back to home page &#8594;</a>
                    </center>
                </div>
            </body>
            </html>';

            echo $dom;
            die();
        } else {
            require_once APPROOT . "/views/{$params['url']}.php";
            die();
        }
    }


    // Status codes
    protected static function statuc_code($code = NULL) {

        $text = '';

        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }

            

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }

        return [
            "code" => $code,
            "text" => $text
        ];
    }


    // Debug
    public static function dd($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }


    // Check if URL exists
    public static function urlExists($url) {
        $url_headers = @get_headers($url);
        if (strpos($url_headers[0],'200')) {
            return true;
        } else {
            return false;
        }
    }


    // Log to blowser console
    public static function clog($data = []) {
        echo '<script>';
        echo 'console.log('. self::toJSON( $data ) .')';
        echo '</script>';
    }


    // Check if string is JSON
    public static function isJSON($string){
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }


    // Create url from string
    public static function str2url($str, $options = array()) {
        // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
        
        $defaults = array(
            'delimiter' => '-',
            'limit' => null,
            'lowercase' => true,
            'replacements' => array(),
            'transliterate' => false,
        );
        
        // Merge options
        $options = array_merge($defaults, $options);
        
        $char_map = array(
            // Latin
            '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'A', '??' => 'AE', '??' => 'C', 
            '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'E', '??' => 'I', '??' => 'I', '??' => 'I', '??' => 'I', 
            '??' => 'D', '??' => 'N', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', '??' => 'O', 
            '??' => 'O', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'U', '??' => 'Y', '??' => 'TH', 
            '??' => 'ss', 
            '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'a', '??' => 'ae', '??' => 'c', 
            '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'e', '??' => 'i', '??' => 'i', '??' => 'i', '??' => 'i', 
            '??' => 'd', '??' => 'n', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', '??' => 'o', 
            '??' => 'o', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'u', '??' => 'y', '??' => 'th', 
            '??' => 'y',

            // Latin symbols
            '??' => '(c)',

            // Greek
            '??' => 'A', '??' => 'B', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Z', '??' => 'H', '??' => '8',
            '??' => 'I', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => '3', '??' => 'O', '??' => 'P',
            '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'Y', '??' => 'F', '??' => 'X', '??' => 'PS', '??' => 'W',
            '??' => 'A', '??' => 'E', '??' => 'I', '??' => 'O', '??' => 'Y', '??' => 'H', '??' => 'W', '??' => 'I',
            '??' => 'Y',
            '??' => 'a', '??' => 'b', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'z', '??' => 'h', '??' => '8',
            '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => '3', '??' => 'o', '??' => 'p',
            '??' => 'r', '??' => 's', '??' => 't', '??' => 'y', '??' => 'f', '??' => 'x', '??' => 'ps', '??' => 'w',
            '??' => 'a', '??' => 'e', '??' => 'i', '??' => 'o', '??' => 'y', '??' => 'h', '??' => 'w', '??' => 's',
            '??' => 'i', '??' => 'y', '??' => 'y', '??' => 'i',

            // Turkish
            '??' => 'S', '??' => 'I', '??' => 'C', '??' => 'U', '??' => 'O', '??' => 'G',
            '??' => 's', '??' => 'i', '??' => 'c', '??' => 'u', '??' => 'o', '??' => 'g', 

            // Russian
            '??' => 'A', '??' => 'B', '??' => 'V', '??' => 'G', '??' => 'D', '??' => 'E', '??' => 'Yo', '??' => 'Zh',
            '??' => 'Z', '??' => 'I', '??' => 'J', '??' => 'K', '??' => 'L', '??' => 'M', '??' => 'N', '??' => 'O',
            '??' => 'P', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', '??' => 'F', '??' => 'H', '??' => 'C',
            '??' => 'Ch', '??' => 'Sh', '??' => 'Sh', '??' => '', '??' => 'Y', '??' => '', '??' => 'E', '??' => 'Yu',
            '??' => 'Ya',
            '??' => 'a', '??' => 'b', '??' => 'v', '??' => 'g', '??' => 'd', '??' => 'e', '??' => 'yo', '??' => 'zh',
            '??' => 'z', '??' => 'i', '??' => 'j', '??' => 'k', '??' => 'l', '??' => 'm', '??' => 'n', '??' => 'o',
            '??' => 'p', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u', '??' => 'f', '??' => 'h', '??' => 'c',
            '??' => 'ch', '??' => 'sh', '??' => 'sh', '??' => '', '??' => 'y', '??' => '', '??' => 'e', '??' => 'yu',
            '??' => 'ya',

            // Ukrainian
            '??' => 'Ye', '??' => 'I', '??' => 'Yi', '??' => 'G',
            '??' => 'ye', '??' => 'i', '??' => 'yi', '??' => 'g',

            // Czech
            '??' => 'C', '??' => 'D', '??' => 'E', '??' => 'N', '??' => 'R', '??' => 'S', '??' => 'T', '??' => 'U', 
            '??' => 'Z', 
            '??' => 'c', '??' => 'd', '??' => 'e', '??' => 'n', '??' => 'r', '??' => 's', '??' => 't', '??' => 'u',
            '??' => 'z', 

            // Polish
            '??' => 'A', '??' => 'C', '??' => 'e', '??' => 'L', '??' => 'N', '??' => 'o', '??' => 'S', '??' => 'Z', 
            '??' => 'Z', 
            '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'l', '??' => 'n', '??' => 'o', '??' => 's', '??' => 'z',
            '??' => 'z',

            // Latvian
            '??' => 'A', '??' => 'C', '??' => 'E', '??' => 'G', '??' => 'i', '??' => 'k', '??' => 'L', '??' => 'N', 
            '??' => 'S', '??' => 'u', '??' => 'Z',
            '??' => 'a', '??' => 'c', '??' => 'e', '??' => 'g', '??' => 'i', '??' => 'k', '??' => 'l', '??' => 'n',
            '??' => 's', '??' => 'u', '??' => 'z'
        );
        
        // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
        
        // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }
        
        // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
        
        // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
        
        // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
        
        // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);
        
        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }



    // Pagination
    // $totalPages = R::count('comments');
    // $currentPage = $_GET["page"] ?? 1;
    // if ($currentPage < 1 OR $currentPage > $totalPages) $currentPage = 1;
    // $limit = 12;
    // $offset = ($currentPage - 1) * $limit;

    // $pagingData = pager([
    //     'total' => $totalPages,
    //     'limit' => $limit,
    //     'current' => $currentPage
    // ]);

    // $comments = R::find("comments", "order by timestamp asc limit $limit offset $offset");

    public static function pager(array $params) {

        $currentSiteUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $currentSiteUrl = explode('&page=', $currentSiteUrl)[0];

        if (strpos($currentSiteUrl, '?') && !strpos($currentSiteUrl, '?page')) {
            $currentSiteUrl = $currentSiteUrl . '&';
        } else if (strpos($currentSiteUrl, '?') && strpos($currentSiteUrl, '?page')) {
            $currentSiteUrl = explode('?page', $currentSiteUrl)[0] . '?';
        } else {
            $currentSiteUrl = $currentSiteUrl . '?';
        }
    
        $total = $params["total"] ?? die('Total page must provided as integer');
        $limit = $params["limit"] ?? die('Items by page must provided as integer');
        $currectPage = $params["current"] ?? die('Currennt page state must provided as integer');
        
        $outer = '<ul class="uk-pagination">%s</ul>';
        $inner = '';
        
        
        //get the last page number
        $last = ceil( $total / $limit );
    
        //calculate start of range for link printing
        $start = ( ( $currectPage - 2 ) > 0 ) ? $currectPage - 2 : 1;
    
        //calculate end of range for link printing
        $end = ( ( $currectPage + 2 ) < $last ) ? $currectPage + 2 : $last;
        
        
        // Previous page
        $currentLink = $currectPage > 1 ? $currectPage - 1 : $currectPage;
        $inner .= '<li class="page-item"><a href="'.$currentSiteUrl.'page='.$currentLink.'">&laquo;</a></li>';
        
        if ( $start > 1 ) {
            $inner .= '<li class="page-item"><a href="'.$currentSiteUrl.'page=1">1</a></li>';
            $inner .= '<li class="page-item uk-disabled"><a href="#"><span>...</span></a></li>';
        }
        
        for ($i = $start ; $i <= $end; $i++) {
            if ($currectPage == $i) {
                $inner .= '<li class="uk-active"><a href="'.$currentSiteUrl.'page='.$i.'">'.$i.'</a></li>';    
            } else {
                $inner .= '<li><a href="'.$currentSiteUrl.'page='.$i.'">'.$i.'</a></li>';    
            }
            
        }
        
        if ( $end < $last ) { //print ... before next page (>>> link)
            $inner .= '<li class="page-item uk-disabled"><a href="#"><span>...</span></a></li>';
            $inner .= '<li class="page-item"><a href="'.$currentSiteUrl.'page='.$last.'">'.$last.'</a></li>';
        }
        
        // Next page
        $nextLink = $currectPage < $last ? $currectPage + 1 : $currectPage;
        $inner .= '<li><a href="'.$currentSiteUrl.'page='.$nextLink.'">&raquo;</a></li>';
        
        
        return sprintf($outer, $inner);
    }



    // Remove directory recursively
    public static function rrmdir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir);
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    self::rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object); 
                } 
            }
            rmdir($dir); 
        } 
    }


    /////////////////////////////////// WORKING WITH ARRAYS ///////////////////////////////////

    // Return JSON
    public static function toJSON($fileArray) {
        $json = json_encode($fileArray, JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        return $json;
    }


    // Object to array
    public static function toArray($array) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = self::toArray($value);
                }
                if (is_object($value)) {
                    $array[$key] = self::toArray((array)$value);
                }
            }
        }
        if (is_object($array)) {
            return self::toArray((array)$array);
        }

        return $array;
    }

    // Sort arary with another array values
    public static function orderArrayByArray(array $array, array $order) {
        static $newArray = [];
        foreach ($order as $index => $id) {
            foreach ($array as $key => $item) {
                if ($item->id == $id)
                    $newArray[$index] = $item;
            }
        }
        return array_reverse($newArray);
    }

    // Search value in array
    // array_search_index($array, 'keyname', 'valuename');
    public static function array_search_index(array $products, string $field, string $value, $index = true) {
        foreach($products as $key => $product) {
            if ( $product[$field] == $value )
                return $index ? $key : 1;
        }
        return false;
    }

    /*
    Woody Array Recursive Order
    array_value_multisort( array, key of the value by which you going to make sorting, 
    under which key next level array is located )
    */
    public static function array_value_multisort(&$array, $key, $nextArrayKey) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            if (is_array($va[$nextArrayKey])) {
                self::array_value_multisort($va[$nextArrayKey], $key, $nextArrayKey);
            }
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);

        foreach ($sorter as $ii => $va) {
            if (is_array($array[$ii][$nextArrayKey])) {
                self::array_value_multisort($array[$ii][$nextArrayKey], $key, $nextArrayKey);
            }
            $ret[$ii]=$array[$ii];
        }
        $array = $ret;
    }


    /////////////////////////////////////////////// COOKIE FUNCTIONS ///////////////////////////////////////////////
    // Set cookie
    public static function set_cookie(array $data) {

        $tobeStored = [
           'name' => $data['name'] ?? null,
           'value' => isset($data['value']) ? self::toJSON($data['value']) : null,
           'expire' => isset($data['expire']) ? time() + $data['expire'] : time() + 86400,
           'path' => $data['path'] ?? '/',
           'domain' => $data['domain'] ?? "",
           'secure' => $data['secure'] ?? false,
           'httponly' => $data['httponly'] ?? false,
        ];

        setcookie($tobeStored['name'], $tobeStored['value'], $tobeStored['expire'], $tobeStored['path'], $tobeStored['domain'], $tobeStored['secure'], $tobeStored['httponly']);
    }


    // Get cookie
    public static function get_cookie(string $name) {

        if (isset($_COOKIE[$name])) {
            return json_decode($_COOKIE[$name]);
        } else {
            return false;
        }
    }


    // Delete cookie
    public static function delete_cookie(string $name) {

        if (isset($_COOKIE[$name])) setcookie($name, null, time() - 3600, '/');
        return true;
    }




    /////////////////////////// FLASH DATA / TEMPORARY DATA TO FLASH ///////////////////////////
    // Set session for one request only
    public static function setFlashData(string $key, $data) {

        self::set_cookie([
            'name' => $key,
            'value' => $data
        ]);
    }


    // If issset flash data
    public static function hasFlashData(string $key) {
        if (isset($_COOKIE[$key]))
            return true;

        return false;
    }

    
    // Get flash data
    public static function getFlashData(string $key) {

        $flashData = null;

        if (self::get_cookie($key)) {
            $flashData = self::get_cookie($key);
            self::delete_cookie($key);
        }
        
        return $flashData;
    }


    // Get user ip
    public static function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}