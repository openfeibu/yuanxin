<?php

use Illuminate\Support\Facades\Request;
use App\Facades\Hashids;
use App\Facades\Trans;

if (!function_exists('hashids_encode')) {
    /**
     * Encode the given id.
     *
     * @param int/array $id
     *
     * @return string
     */
    function hashids_encode($idorarray)
    {
        return Hashids::encode($idorarray);
    }

}

if (!function_exists('hashids_decode')) {
    /**
     * Decode the given value.
     *
     * @param string $value
     *
     * @return array / int
     */
    function hashids_decode($value)
    {
        $return = Hashids::decode($value);

        if (empty($return)) {
            return null;
        }

        if (count($return) == 1) {
            return $return[0];
        }

        return $return;
    }

}

if (!function_exists('folder_new')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function folder_new($prefix = null, $sufix = null)
    {
        $folder        = date('Y/m/d/His') . rand(100, 999);
        return $folder;
    }
}

if (!function_exists('blade_compile')) {
    /**
     * Get new upload folder pathes.
     *
     * @param string $prefix
     * @param string $sufix
     *
     * @return array
     */
    function blade_compile($string, array $args = [])
    {
        $compiled = \Blade::compileString($string);
        ob_start() and extract($args, EXTR_SKIP);

        // We'll include the view contents for parsing within a catcher

        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try
        {
            eval('?>' . $compiled);
        }

            // If we caught an exception, we'll silently flush the output

            // buffer so that no partially rendered views get thrown out
            // to the client and confuse the user with junk.
        catch (\Exception $e) {
            ob_get_clean();throw $e;
        }

        $content = ob_get_clean();
        $content = str_replace(['@param  ', '@return  ', '@var  ', '@throws  '], ['@param ', '@return ', '@var ', '@throws '], $content);

        return $content;

    }

}


if (!function_exists('trans_url')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function trans_url($url)
    {
        return Trans::to($url);
    }

}

if (!function_exists('trans_dir')) {
    /**
     * Return the direction of current language.
     *
     * @return string (ltr|rtl)
     *
     */
    function trans_dir()
    {
        return Trans::getCurrentTransDirection();
    }

}

if (!function_exists('trans_setlocale')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function trans_setlocale($locale = null)
    {
        return Trans::setLocale($locale);
    }

}

if (!function_exists('checkbox_array')) {
    /**
     * Convert array to use in form check box
     *
     * @param array $array
     * @param string $name
     * @param array $options
     *
     * @return array
     */
    function checkbox_array(array $array, $name, $options = [])
    {
        $return = [];

        foreach ($array as $key => $val) {
            $return[$val] = array_merge(['name' => "{$name}[{$key}]"], $options);
        }

        return $return;
    }

}

if (!function_exists('pager_array')) {
    /**
     * Return request values to be used in paginator
     *
     * @return array
     */
    function pager_array()
    {

        return Request::only(
            config('database.criteria.params.search', 'search'),
            config('database.criteria.params.searchFields', 'searchFields'),
            config('database.criteria.params.columns', 'columns'),
            config('database.criteria.params.sortBy', 'sortBy'),
            config('database.criteria.params.orderBy', 'orderBy'),
            config('database.criteria.params.with', 'with')
        );
    }

}

if (!function_exists('user_type')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_type($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        $provider = config("auth.guards." . $guard . ".provider", 'users');
        return config("auth.providers.$provider.model", App\User::class);
    }

}

if (!function_exists('user_id')) {
    /**
     * Get user id.
     *
     * @param string $guard
     *
     * @return int
     */
    function user_id($guard = null)
    {

        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->id;
        }
        return null;
    }

}

if (!function_exists('get_guard')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function get_guard($property = 'guard')
    {
        switch ($property) {
            case 'url':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'route':
                return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
                break;
            case 'model':
                $provider = config("auth.guards." . getenv('guard') . ".provider", 'users');
                return config("auth.providers.$provider.model", App\User::class);
                break;
            default:
                return getenv('guard');
        }
    }

}

if (!function_exists('guard_url')) {
    /**
     * Return thr property of the guard for current request.
     *
     * @param string $property
     *
     * @return mixed
     */
    function guard_url($url, $translate = true)
    {
        $prefix = empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
        if ($translate){
            return trans_url($prefix . '/' . $url);
        }
        return $prefix . '/' . $url;
    }

}

if (!function_exists('set_route_guard')) {
    /**
     * Set local for the translation
     *
     * @param string $locale
     *
     * @return string
     */
    function set_route_guard($sub = 'web', $guard=null,$theme=null)
    {
        $i = ($sub == 'web') ? 1 : 2;
        $theme ? set_theme($theme) : '';
        //check whether guard is the first parameter of the route
        $guard = is_null($guard) ? request()->segment($i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        //check whether guard is the second parameter of the route
        $guard = is_null($guard) ? request()->segment(++$i) : $guard;
        if (!empty(config("auth.guards.$guard"))){
            putenv("guard={$guard}.{$sub}");
            app('auth')->shouldUse("{$guard}.{$sub}");
            return $guard;
        }

        putenv("guard=client.{$sub}");
        app('auth')->shouldUse("client.{$sub}");
        return $sub;
    }

}
if(!function_exists('set_theme'))
{
    function set_theme($theme = '')
    {
        if(!empty($theme))
        {
            putenv("theme={$theme}");
        }
    }
}


if (!function_exists('users')) {
    /**
     * Get upload folder.
     *
     * @param string $folder
     *
     * @return string
     */
    function users($property, $guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;

        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user()->$property;
        }
        return null;
    }

}

if (!function_exists('user')) {
    /**
     * Return the user model
     * @param type|null $guard
     * @return type
     */
    function user($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        if (Auth::guard($guard)->check()) {
            return Auth::guard($guard)->user();
        }

        return null;
    }

}

if (!function_exists('user_check')) {
    /**
     * Check whether user is logged in
     * @param type|null $guard
     * @return type
     */
    function user_check($guard = null)
    {
        $guard = is_null($guard) ? getenv('guard') : $guard;
        return Auth::guard($guard)->check();
    }

}

if (!function_exists('format_date')) {
    /**
     * Format date
     *
     * @param string $date
     * @param string $format
     *
     * @return date
     */
    function format_date($date, $format = 'd M Y')
    {
        if (empty($date)) return null;
        return date($format, strtotime($date));
    }

}

if (!function_exists('format_date_time')) {
    /**
     * Format datetime
     *
     * @param date $datetime
     * @param string $format
     *
     * @return datetime
     */
    function format_date_time($datetime, $format = 'd M Y h:i A')
    {
        return date($format, strtotime($datetime));
    }

}

if (!function_exists('format_time')) {
    /**
     * Format time.
     *
     * @param string $time
     * @param string $format
     *
     * @return time
     */
    function format_time($time, $format = 'h:i A')
    {
        return date($format, strtotime($time));
    }

}
if (!function_exists('theme_asset')) {
    /**
     * Get translated url.
     *
     * @param string $url
     *
     * @return string
     */
    function theme_asset($file)
    {
        return app('theme')->asset()->url($file);
    }
}
if (!function_exists('replace_image_url')) {
    function replace_image_url($content,$url)
    {
        if($url)
        {
            preg_match_all("/<img(.*)src=\"([^\"]+)\"[^>]+>/isU", $content, $matches);
            $img = "";
            if(!empty($matches)) {
                $img = $matches[2];
            }
            if(!empty($img))
            {
                $patterns= array();
                $replacements = array();
                foreach($img as $imgItem){
                    if(strpos($imgItem,'http') === false)
                    {
                        $final_imgUrl = $url.$imgItem;
                        $replacements[] = $final_imgUrl;
                        $img_new = "/".preg_replace("/\//i","\/",$imgItem)."/";
                        $patterns[] = $img_new;
                    }
                }
                ksort($patterns);
                ksort($replacements);
                $vote_content = preg_replace($patterns, $replacements, $content);
                return $vote_content;
            } else {
                return $content;
            }
        } else {
            return $content;
        }
    }
}
if (!function_exists('get_substr')) {
    function get_substr($str, $len = 12, $dot = true)
    {
        $i = 0;
        $l = 0;
        $c = 0;
        $a = array();
        while ($l < $len) {
            $t = substr($str, $i, 1);
            if (ord($t) >= 224) {
                $c = 3;
                $t = substr($str, $i, $c);
                $l += 2;
            } elseif (ord($t) >= 192) {
                $c = 2;
                $t = substr($str, $i, $c);
                $l += 2;
            } else {
                $c = 1;
                $l++;
            }
            $i += $c;
            if ($l > $len) break;
            $a[] = $t;
        }
        $re = implode('', $a);
        if (substr($str, $i, 1) !== false) {
            array_pop($a);
            ($c == 1) and array_pop($a);
            $re = implode('', $a);
            $dot and $re .= '...';
        }
        return $re;
    }
}
if (!function_exists('handle_image_url')) {
    function handle_image_url($image_url = '', $host = '')
    {
        $host = $host ? $host : config('app.image_url') . '/';
        if (!empty($image_url) && strpos($image_url, 'http') === false) {
            $image_url = $host . $image_url;
        }
        return $image_url;
    }
}
if (!function_exists('first_image')) {
    function first_image($content)
    {
        $data['content'] = $content;
        $soContent = $data['content'];
        $soImages = '~<img [^>]* />~';
        preg_match_all($soImages, $soContent, $thePics);
        $allPics = count($thePics[0]);
        preg_match('/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|PNG))\"?.+>/i', $thePics[0][0], $match);
        $data['ig'] = $thePics[0][0];
        if ($allPics > 0) {
            return $match[1];
        } else {
            return null;
        }
    }
}
if (!function_exists('list_image_url_absolute')) {
    function list_image_url_absolute($list, $size = 'sm')
    {
        foreach ($list as $key => $data) {
            $list[$key]['image'] = image_url_absolute($data['image'], $size);
        }
        return $list;
    }
}
if (!function_exists('image_url_absolute')) {
    function image_url_absolute($image, $size = 'sm')
    {
        return $image ? url("/image/" . $size . $image) : '';
    }
}
if (!function_exists('handle_images')) {
    function handle_images($images, $host = '')
    {
        foreach ($images as $key => $image) {
            $images[$key] = handle_image_url($image, $host);
        }
        return $images;
    }
}
if (!function_exists('setting')) {
    function setting($slug, $value = 'value')
    {
        return \App\Models\Setting::where('slug', $slug)->value($value);
    }
}
if (!function_exists('logo')) {
    function logo()
    {
        $logo =  \App\Models\Setting::where('slug', 'logo')->value('value');
        return url('/image/original/'.$logo);
    }
}
if (!function_exists('page')) {
    function page($slug, $value = 'content')
    {
        return \App\Models\Page::where('slug', $slug)->value($value);
    }
}
if (!function_exists('date_html')) {
    function date_html($date)
    {
        $month = date('M',strtotime($date));
        $day = date('d',strtotime($date));
        $html = '<div class="date"><p>'.$day.'</p><span>'.$month.'</span></div>';
        return $html;
    }
}
/*
* ============================== ???????????? html?????????????????? =========================
* @param (string) $str   ??????????????????
* @param (int)  $lenth  ????????????
* @param (string) $repalce ??????????????????$repalce????????????????????????????????????html?????????????????????
* @param (string) $anchor ?????????????????????????????????????????????????????????????????????????????????
* @return (string) $result ?????????
* @demo  $res = cut_html_str($str, 256, '...'); //??????256???????????????????????????'...'??????
* ===============================================================================
*/
if (!function_exists('cut_html_str')) {
    function cut_html_str($str, $lenth, $replace = '......', $anchor = '<!-- break -->')
    {
        $_lenth = mb_strlen($str, "utf-8"); // ?????????????????????????????????????????????????????????
        if ($_lenth <= $lenth) {
            return $str;    // ?????????????????????????????????????????????????????????
        }
        $strlen_var = strlen($str);     // ????????????????????????UTF8?????????-?????????3????????????????????????????????????
        if (strpos($str, '<') === false) {
            return mb_substr($str, 0, $lenth);  // ????????? html ?????? ???????????????
        }
        if ($e = strpos($str, $anchor)) {
            return mb_substr($str, 0, $e);  // ???????????????????????????
        }
        $html_tag = 0;  // html ????????????
        $result = '';   // ???????????????
        $html_array = array('left' => array(), 'right' => array()); //???????????????????????????????????? html ???????????????=>left,??????=>right
        /*
        * ??????????????????<h3><p><b>a</b></h3>?????????p???????????????????????????array('left'=>array('h3','p','b'), 'right'=>'b','h3');
        * ????????? html ?????????<? <% ???????????????????????????????????????????????????
        */
        for ($i = 0; $i < $strlen_var; ++$i) {
            if (!$lenth) break;  // ?????????????????????
            $current_var = substr($str, $i, 1); // ????????????
            if ($current_var == '<') { // html ????????????
                $html_tag = 1;
                $html_array_str = '';
            } else if ($html_tag == 1) { // ?????? html ????????????
                if ($current_var == '>') {
                    $html_array_str = trim($html_array_str); //???????????????????????? <br / > < img src="" / > ???????????????????????????
                    if (substr($html_array_str, -1) != '/') { //????????????????????????????????? /??????????????????????????????????????????
                        // ??????????????????????????? /????????????????????? right ??????
                        $f = substr($html_array_str, 0, 1);
                        if ($f == '/') {
                            $html_array['right'][] = str_replace('/', '', $html_array_str); // ?????? '/'
                        } else if ($f != '?') { // ???????????????? PHP ???????????????
                            // ????????????????????????????????????????????????????????? html ???????????????<h2 class="a"> <p class="a">
                            if (strpos($html_array_str, ' ') !== false) {
                                // ?????????2??????????????????????????????????????????<h2 class="" id="">
                                $html_array['left'][] = strtolower(current(explode(' ', $html_array_str, 2)));
                            } else {
                                //???????????????????????????????????? html ???????????????<b> <p> ???????????????????????????
                                $html_array['left'][] = strtolower($html_array_str);
                            }
                        }
                    }
                    $html_array_str = ''; // ???????????????
                    $html_tag = 0;
                } else {
                    $html_array_str .= $current_var; //???< >????????????????????????????????????,???????????? html ??????
                }
            } else {
                --$lenth; // ??? html ???????????????
            }
            $ord_var_c = ord($str{$i});
            switch (true) {
                case (($ord_var_c & 0xE0) == 0xC0): // 2 ??????
                    $result .= substr($str, $i, 2);
                    $i += 1;
                    break;
                case (($ord_var_c & 0xF0) == 0xE0): // 3 ??????
                    $result .= substr($str, $i, 3);
                    $i += 2;
                    break;
                case (($ord_var_c & 0xF8) == 0xF0): // 4 ??????
                    $result .= substr($str, $i, 4);
                    $i += 3;
                    break;
                case (($ord_var_c & 0xFC) == 0xF8): // 5 ??????
                    $result .= substr($str, $i, 5);
                    $i += 4;
                    break;
                case (($ord_var_c & 0xFE) == 0xFC): // 6 ??????
                    $result .= substr($str, $i, 6);
                    $i += 5;
                    break;
                default: // 1 ??????
                    $result .= $current_var;
            }
        }
        if ($html_array['left']) { //???????????? html ????????????????????????
            $html_array['left'] = array_reverse($html_array['left']); //??????left?????????????????????????????? html ?????????????????????
            foreach ($html_array['left'] as $index => $tag) {
                $key = array_search($tag, $html_array['right']); // ?????????????????????????????? right ???
                if ($key !== false) { // ???????????? right ??????????????????
                    unset($html_array['right'][$key]);
                } else { // ???????????????????????????
                    $result .= '</' . $tag . '>';
                }
            }
        }
        return $result . $replace;
    }
}
if (!function_exists('drop_blank')) {
    function drop_blank($str)
    {
        $str = preg_replace("/\t/", "", $str); //?????????????????????????????????????????????????????????????????????????????????
        $str = preg_replace("/\r\n/", "", $str);
        $str = preg_replace("/\r/", "", $str);
        $str = preg_replace("/\n/", "", $str);
        $str = preg_replace("/ /", "", $str);
        $str = preg_replace("/  /", "", $str);  //??????html????????????
        return trim($str); //???????????????
    }
}
if (!function_exists('build_order_sn')) {
    function build_order_sn()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}
if (!function_exists('isVaildImage')) {
    function isVaildImage($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.img_type'))){
                $error.= $name."????????????;";
            }
            if($file->getClientSize() > config('common.img_size')){
                $img_size = config('common.img_size')/(1024*1024);
                $error.= $name.'??????'.$img_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('isVaildFile')) {
    function isVaildFile($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->extension()),config('common.file_type'))){
                $error.= $name."????????????;";
            }
            if($file->getClientSize() > config('common.file_size')){
                $file_size = config('common.file_size')/(1024*1024);
                $error.= $name.'??????'.$file_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('isVaildReportFile')) {
    function isVaildReportFile($files)
    {
        $error = '';

        foreach($files as $key => $file)
        {
            $name = $file->getClientOriginalName();
            if(!$file->isValid())
            {
                $error.= $name.$file->getErrorMessage().';';
            }
            if(!in_array( strtolower($file->getClientOriginalExtension()),config('common.report_file_type'))){
                $error.= $name."????????????;";
            }
            if($file->getClientSize() > config('common.file_size')){
                $file_size = config('common.file_size')/(1024*1024);
                $error.= $name.'??????'.$file_size.'M';
            }
        }
        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('isVaildExcel')) {
    function isVaildExcel($file)
    {
        $error = '';
        $name = $file->getClientOriginalName();
        if(!$file->isValid())
        {
            $error.= $name.$file->getErrorMessage().';';
        }
//        if(!in_array( strtolower($file->extension()),config('common.excel_type'))){
//            $error.= $name."???".strtolower($file->extension())."????????????Excel??????;";
//        }
        if($file->getClientSize() > config('common.file_size')){
            $file_size = config('common.file_size')/(1024*1024);
            $error.= $name.'??????'.$file_size.'M';
        }

        if($error)
        {
            throw new \App\Exceptions\OutputServerMessageException($error);
        }
    }
}
if (!function_exists('image_png_size_add')) {
    function image_png_size_add($imgsrc, $imgdst,$max_width=1000,$size=0.9)
    {
        list($width, $height, $type) = getimagesize($imgsrc);
        $ratio = $width > $max_width ? $max_width / $width : 1;
        $new_width = $ratio * $width * $size;
        $new_height = $ratio * $height * $size;

        switch ($type) {
            case 1:
                $giftype = check_gifcartoon($imgsrc);
                if ($giftype) {
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagegif($image_wp, $imgdst, 75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 75);
                imagedestroy($image_wp);
                break;
            case 3:
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagesavealpha($image, true);
                imagealphablending($image_wp, false);
                imagesavealpha($image_wp, true);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagepng($image_wp, $imgdst);
                imagedestroy($image_wp);
                break;
        }

    }
}
if (!function_exists('check_gifcartoon')) {
    function check_gifcartoon($image_file)
    {
        $fp = fopen($image_file, 'rb');
        $image_head = fread($fp, 1024);
        fclose($fp);
        return true;
    }
}
if (!function_exists('generate_token')) {
    function generate_token()
    {
        return md5(uniqid());
    }
}
if (!function_exists('guard_prefix')) {
    function guard_prefix()
    {
        return empty(getenv('guard')) ? 'user' : current(explode(".", getenv('guard')));
    }
}

if (!function_exists('validateParameter')) {
    function validateParameter($rules,$message=[])
    {

        $validator = Validator::make(Request::all(), $rules,$message);
        if ($validator->fails()) {
            throw new \App\Exceptions\OutputServerMessageException($validator->errors()->first());
        } else {
            return true;
        }
    }
}
if (!function_exists('validateCustomParameter')) {
    function validateCustomParameter($data,$rules,$message=[])
    {

        $validator = Validator::make($data, $rules,$message);
        if ($validator->fails()) {
            throw new \App\Exceptions\OutputServerMessageException($validator->errors()->first());
        } else {
            return true;
        }
    }
}
if (!function_exists('validateData')) {
    function validateData($value, $custom)
    {
        if (preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $value)) {
            throw new \App\Exceptions\OutputServerMessageException($custom . "???????????????");
        }
        return true;
    }
}
if (!function_exists('visible_data')) {
    function visible_data($data,$keys)
    {
        $return_data = [];
        foreach ($keys as $key)
        {
            $return_data[$key] = $data[$key];
        }
        return $return_data;
    }
}
if (!function_exists('avatar')) {
    function avatar($avatar)
    {
        return $avatar ? url('image/original'.$avatar) : url('image/original'.config('common.default_avatar'));
    }
}
if (!function_exists('generate_order_sn')) {
    function generate_order_sn($prefix='')
    {
        $order_sn = $prefix.date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        return $order_sn;
    }
}
/**
 * ?????????????????????
 *
 * @param  int    $sTime ??????????????????
 * @param  string $type  ??????. normal | mohu | full | ymd | other
 * @param  string $alt   ?????????
 * @return string
 */
if(!function_exists('friendly_date')){
    function friendly_date($sTime, $type = 'mohu', $alt = 'false')
    {
        if (!$sTime) {
            return '';
        }
        //	var_dump($sTime);exit;
        $sTime = strtotime($sTime);
        //sTime=????????????cTime=???????????????dTime=?????????
        $cTime = time();
        $dTime = $cTime - $sTime;
        $dDay = ceil($dTime/3600/24);
        //$dDay     =   intval($dTime/3600/24);
        $dYear = intval(date('Y', $cTime)) - intval(date('Y', $sTime));
        //normal???n?????????n????????????n??????????????????
        if ($type == 'normal') {
            if ($dTime < 60) {
                if ($dTime < 10) {
                    return '??????';    //by yangjs
                } else {
                    return intval(floor($dTime / 10) * 10).'??????';
                }
            } elseif ($dTime < 3600) {
                return intval($dTime / 60).'?????????';
                //???????????????.????????????.????????????.
            } elseif ($dYear == 0 && $dDay == 0) {
                //return intval($dTime/3600)."?????????";
                return '??????'.date('H:i', $sTime);
            } elseif ($dYear == 0) {
                return date('m???d??? H:i', $sTime);
            } else {
                return date('Y-m-d H:i', $sTime);
            }
        }elseif ($type == 'weixin'){
            //???????????????
            $timeStr = "";
            //??????????????????
            $addTime = explode(',', date('Y,n,j,w,a,h,i,y', $sTime));//????????????????????????????????????????????????
            $nowTime = explode(',', date('Y,n,j,w,a,h,i,y', $cTime));

            $dayPerMonthAddTime = getDayPerMonth($addTime[0]);
            $week = array(0=>"?????????",1=>"?????????",2=>"?????????",3=>"?????????",4=>"?????????",5=>"?????????",6=>"?????????");
            //??????????????????????????????,??????????????? ????????? / ????????? ?????????
            if($addTime[0] == $nowTime[0] && $addTime[1] == $nowTime[1] && $addTime[2] == $nowTime[2]) {
                $timeStr .= $addTime[5] . ":" . $addTime[6];
            } else if(($addTime[0] == $nowTime[0] && $addTime[1] == $nowTime[1] && $addTime[2] == $nowTime[2]-1)
                || ($addTime[0] == $nowTime[0] && $nowTime[1]-$addTime[1] == 1 && $dayPerMonthAddTime[$addTime[1]] == $addTime[2] && $nowTime[2] == 1)
                || ($nowTime[0]-$addTime[0] == 1 && $addTime[1] == 12 && $addTime[2] == 31 && $nowTime[1] == 1 && $nowTime[2] == 1)) {
                //????????????????????????,??????????????????????????????????????????????????????????????????????????????????????????????????????????????? ???:??? ??????/??????
                $timeStr .= "?????? " . $addTime[5] . ":" . $addTime[6] . " ";
            } else if(($addTime[0] == $nowTime[0] && $addTime[1] == $nowTime[1] && $nowTime[2] - $addTime[2] < 7)
                || ($addTime[0] == $nowTime[0] && $nowTime[1]-$addTime[1] == 1 && $dayPerMonthAddTime[$addTime[1]]-$addTime[2]+$nowTime[2] < 7
                    || ($nowTime[0]-$addTime[0] == 1 && $addTime[1] == 12 && $nowTime[1] == 1 && 31-$addTime[2]+$nowTime[2] < 7))) { //???????????????????????????????????????,?????????????????????????????????????????? ???:??? ??????/??????

                $timeStr .= $week[$addTime[3]] . " " . $addTime[5] . ":" . $addTime[6];
            } else { //??????????????????/???/??? ???:??? ??????/??????
                $timeStr .= $addTime[1] . "/" . $addTime[2] . "/" . $addTime[7] . " " . $addTime[5] . ":" . $addTime[6];
            }

            if($addTime[4] == "am") {
                $timeStr .= " ??????";
            } else if($addTime[4] == "pm") {
                $timeStr .= " ??????";
            }

            return $timeStr;
        }elseif ($type == 'mohu') {
            if ($dTime < 60) {
                return $dTime.'??????';
            } elseif ($dTime < 3600) {
                return intval($dTime / 60).'?????????';
            } elseif ($dTime >= 3600 && $dTime < 3600 * 24) {
                return intval($dTime / 3600).'?????????';
            } elseif ($dDay > 0 && $dDay <= 7) {
                return intval($dDay).'??????';
            } elseif ($dDay > 7 &&  $dDay <= 30) {
                return intval($dDay / 7).'??????';
            } elseif ($dDay > 30) {
                return intval($dDay / 30).'?????????';
            }
            var_dump($dDay);exit;
            //full: Y-m-d , H:i:s
        } elseif ($type == 'full') {
            return date('Y-m-d , H:i:s', $sTime);
        } elseif ($type == 'ymd') {
            return date('Y-m-d', $sTime);
        } else {
            if ($dTime < 60) {
                return $dTime.'??????';
            } elseif ($dTime < 3600) {
                return intval($dTime / 60).'?????????';
            } elseif ($dTime >= 3600 && $dDay == 0) {
                return intval($dTime / 3600).'?????????';
            } elseif ($dYear == 0) {
                return date('Y-m-d H:i:s', $sTime);
            } else {
                return date('Y-m-d H:i:s', $sTime);
            }
        }
    }
}
if(!function_exists('rid_two')) {
//??????????????????
    function rid_two($num)
    {
        return floor($num * 100) / 100;
    }
}
//??????????????????
if(!function_exists('sensitive_address')) {
    function sensitive_address($str)
    {
        $str = preg_replace('#\d{3,}#', '***', $str);
        return $str;
    }
}
//??????????????????
if(!function_exists('str_filter')) {
    function str_filter($str)
    {
        $str = str_replace('`', '', $str);
        $str = str_replace('??', '', $str);
        $str = str_replace('~', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('@', '', $str);
        $str = str_replace('#', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('??????', '', $str);
        $str = str_replace('&', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('-', '', $str);
        $str = str_replace('_', '', $str);
        $str = str_replace('??????', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace(':', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace(',', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('.', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('???', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('???', '', $str);
        return trim($str);
    }
}
if(!function_exists('get_end_day')) {
    function get_end_day($start = 'now', $offset = 0, $exception = '', $allow = '')
    {
        date_default_timezone_set('PRC');
        //???????????????????????????????????????????????????
        $starttime = strtotime($start);
        $endtime = $starttime + $offset * 24 * 3600;
        $end = date('Y-m-d', $endtime);
        //???????????????????????????????????????
        $weekday = date('N', $starttime);//??????????????????1-7
        $remain = $offset % 7;
        $newoffset = 2 * ($offset - $remain) / 7;//??????????????????????????????
        if ($remain > 0) {//????????????
            $tmp = $weekday + $remain;
            if ($tmp >= 7) {
                $newoffset += 2;
            } else if ($tmp == 6) {
                $newoffset += 1;
            }
            //????????????????????????????????????
            if ($weekday == 6) {
                $newoffset -= 1;
            } else if ($weekday == 7) {
                $newoffset -= 2;
            }
        }
        //?????????????????????????????????
        if (is_array($exception)) {//???????????????
            foreach ($exception as $day) {
                $tmp_time = strtotime($day);
                if ($tmp_time > $starttime && $tmp_time <= $endtime) {//?????????(a,b]???
                    $weekday_t = date('N', $tmp_time);
                    if ($weekday_t <= 5) {//??????????????????????????????
                        $newoffset += 1;
                    }
                }
            }
        } else {//???????????????
            if ($exception != '') {
                $tmp_time = strtotime($exception);
                if ($tmp_time > $starttime && $tmp_time <= $endtime) {
                    $weekday_t = date('N', $tmp_time);
                    if ($weekday_t <= 5) {
                        $newoffset += 1;
                    }
                }
            }

        }
        //??????????????????????????????????????????
        if ($newoffset > 0) {
            #echo "[{$start} -> {$offset}] = [{$end} -> {$newoffset}]"."<br />\n";
            return $this->getEndDay($end, $newoffset, $exception, $allow);
        } else {
            return $end;
        }
    }
}
/**
 * ???????????? N ???????????????
 */
if (!function_exists('get_future_days')) {
    function get_future_days($time = '', $format = 'Y-m-d',$days = 7,$workday=false)
    {
        $time = $time != '' ? $time : time();
        //????????????
        $dates = [];
        $i = $j = 1 ;
        while($i<=$days)
        {
            if($workday)
            {
                $date = date($format, strtotime('+' . $j - 1 . ' days', $time));
                $w = date('w',strtotime($date));
                if($w == 0 || $w == 6)
                {
                    $j++;
                    continue;
                }
                else{
                    $dates[$i] = date($format, strtotime('+' . $j - 1 . ' days', $time));
                    $i++;$j++;
                }
            }else{
                $dates[$i] = date($format, strtotime('+' . $j - 1 . ' days', $time));
                $i++;
            }
        }

        return $dates;
    }
}
if (!function_exists('get_appointment_code')) {
    function get_appointment_code()
    {
        $code = get_random();
        if(\App\Models\Appointment::where('code',$code)->where('status','unchecked')->first(['id']))
        {
            return get_appointment_code();
        }
        return $code;

    }
}
if (!function_exists('get_random')) {
    function get_random($length = 6)
    {
        $min = pow(10, ($length - 1));

        $max = pow(10, $length) - 1;

        return mt_rand($min, $max);

    }
}