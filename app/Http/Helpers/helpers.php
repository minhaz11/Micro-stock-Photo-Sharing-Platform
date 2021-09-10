<?php

use App\GeneralSetting;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


function sidebarVariation(){

    /// for sidebar
    $variation['sidebar'] = 'bg--black';
    $variation['sidebar'] = 'bg--white';
    $variation['sidebar'] = 'bg--15';
    $variation['sidebar'] = 'bg--dark';
    $variation['sidebar'] = 'bg--indigo';
    $variation['sidebar'] = 'bg--brown';
    $variation['sidebar'] = 'bg--1';
    $variation['sidebar'] = 'bg--3';
    $variation['sidebar'] = 'bg--10';
    $variation['sidebar'] = 'bg--11';
    $variation['sidebar'] = 'bg--12';
    $variation['sidebar'] = 'bg--13';
    $variation['sidebar'] = 'bg--15';
    $variation['sidebar'] = 'bg--17';
    $variation['sidebar'] = 'bg--19';
    $variation['sidebar'] = 'bg--gradi-1';
    $variation['sidebar'] = 'bg--gradi-17';
    $variation['sidebar'] = 'bg--gradi-19';
    $variation['sidebar'] = 'bg--gradi-20';
    $variation['sidebar'] = 'bg--gradi-24';
    $variation['sidebar'] = 'bg--gradi-32';
    $variation['sidebar'] = 'bg--gradi-50';
    $variation['sidebar'] = 'bg--gradi-49';
    $variation['sidebar'] = 'bg--gradi-12';
    $variation['sidebar'] = 'bg_img';

    //for selector
    $variation['selector'] = 'capsule--rounded2';
    $variation['selector'] = 'capsule--rounded';

    //for overlay
    $variation['overlay'] = 'overlay--gradi-1'; // 1-50
    $variation['overlay'] = 'none';
    $variation['overlay'] = 'overlay--indigo';

    //Opacity
    $variation['opacity'] = 'overlay--opacity-8'; // 1-10

    return $variation;

}

function systemDetails()
{
    $system['name'] = 'StocLab';
    $system['version'] = '1.2';
    return $system;
}

function getLatestVersion()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/version/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}


function shortDescription($string, $length = 120)
{
    return Illuminate\Support\Str::limit($string, $length);
}


function shortCodeReplacer($shortCode, $replace_with, $template_string)
{
    return str_replace($shortCode, $replace_with, $template_string);
}


function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = 0;
    while ($length > 0 && $length--) {
        $max = ($max * 10) + 9;
    }
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



//moveable
function uploadImage($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();

    $image = Image::make($file);

    if (!empty($size)) {
        $size = explode('x', strtolower($size));
        $image->resize($size[0], $size[1]);
    }
    $image->save($location . '/' . $filename);

    if (!empty($thumb)) {

        $thumb = explode('x', $thumb);
        Image::make($file)->resize($thumb[0], $thumb[1])->save($location . '/thumb_' . $filename);
    }

    return $filename;
}

function userPhotoUpload($file, $location, $size = null, $old = null, $thumb = null)
{
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
        removeFile($location . '/thumb_' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();

    $image = Image::make($file);

    if (!empty($size)) {
        $size = explode('x', strtolower($size));
        $image->resize($size[0], $size[1],function($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        });
    }
    $image->save($location . '/' . $filename);

    if (!empty($thumb)) {

        $thumb = explode('x', $thumb);
        Image::make($file)->resize($thumb[0], $thumb[1],function($constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save($location . '/thumb_' . $filename);
    }

    return $filename;
}



function uploadFile($file, $location, $size = null, $old = null){
    $path = makeDirectory($location);
    if (!$path) throw new Exception('File could not been created.');

    if (!empty($old)) {
        removeFile($location . '/' . $old);
    }

    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $file->move($location,$filename);
    return $filename;
}

function makeDirectory($path)
{
    if (file_exists($path)) return true;
    return mkdir($path, 0755, true);
}


function removeFile($path)
{
    return file_exists($path) && is_file($path) ? @unlink($path) : false;
}


function activeTemplate($asset = false)
{

    $gs = GeneralSetting::first(['active_template']);

    $template = $gs->active_template;

    $sess = session()->get('template');

    if (trim($sess) != null) {
        $template = $sess;
    }
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $gs = GeneralSetting::first(['active_template']);
    $template = $gs->active_template;
    $sess = session()->get('template');
    if (trim($sess) != null) {
        $template = $sess;
    }
    return $template;
}


function reCaptcha()
{
    $reCaptcha = \App\Extension::where('act', 'google-recaptcha2')->where('status', 1)->first();
    return $reCaptcha ? $reCaptcha->generateScript() : '';
}


function analytics()
{
    $analytics = \App\Extension::where('act', 'google-analytics')->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function tawkto()
{
    $tawkto = \App\Extension::where('act', 'tawk-chat')->where('status', 1)->first();
    return $tawkto ? $tawkto->generateScript() : '';
}


function fbcomment()
{
    $comment = \App\Extension::where('act', 'fb-comment')->where('status',1)->first();
    return  $comment ? $comment->generateScript() : '';
}

function getCustomCaptcha($height = 46, $width = '300px', $bgcolor = '#003', $textcolor = '#abc')
{
    $textcolor = '#'.App\GeneralSetting::first()->base_color;
    $captcha = \App\Extension::where('act', 'custom-captcha')->where('status', 1)->first();

    $code = rand(100000, 999999);
    $char = str_split($code);
    $ret = '<link href="https://fonts.googleapis.com/css?family=Henny+Penny&display=swap" rel="stylesheet">';
    $ret .= '<div style="height: ' . $height . 'px; line-height: ' . $height . 'px; width:' . $width . '; text-align: center; background-color: ' . $bgcolor . '; color: ' . $textcolor . '; font-size: ' . ($height - 20) . 'px; font-weight: bold; letter-spacing: 20px; font-family: \'Henny Penny\', cursive;  -webkit-user-select: none; -moz-user-select: none;-ms-user-select: none;user-select: none;  display: flex; justify-content: center;">';
    foreach ($char as $value) {
        $ret .= '<span style="    float:left;     -webkit-transform: rotate(' . rand(-60, 60) . 'deg);">' . $value . '</span>';
    }
    $ret .= '</div>';
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    $ret .= '<input type="hidden" name="captcha_secret" value="' . $captchaSecret . '">';
    return $ret;
}


function captchaVerify($code, $secret)
{
    $captcha = \App\Extension::where('act', 'custom-captcha')->where('status', 1)->first();
    $captchaSecret = hash_hmac('sha256', $code, $captcha->shortcode->random_key->value);
    if ($captchaSecret == $secret) {
        return true;
    }
    return false;
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function currency(){
    $data['crypto'] = 8;
    $data['fiat'] = 2;
    return $data;
}


function getAmount($amount, $length = 0)
{
    if(0 < $length){
        return number_format($amount + 0, $length);
    }
    return $amount + 0;
}

function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet, $amount, $crypto = null)
{

    $varb = $wallet . "?amount=" . $amount;
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8";
}

//moveable
function curlContent($url)
{
    //open connection
    $ch = curl_init();
    //set the url, number of POST vars, POST data

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    return $result;
}

//moveable
function curlPostContent($url, $arr = null)
{
    if ($arr) {
        $params = http_build_query($arr);
    } else {
        $params = '';
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function inputTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}


function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}


function str_slug($title = null)
{
    return \Illuminate\Support\Str::slug($title);
}

function str_limit($title = null, $length = 10)
{
    return \Illuminate\Support\Str::limit($title, $length);
}

//moveable
function getIpInfo()
{
    $ip = null;
    $deep_detect = TRUE;

    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }


    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);


    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');


    return $data;
}

//moveable
function osBrowser(){
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    $data['os_platform'] = $os_platform;
    $data['browser'] = $browser;

    return $data;
}

function site_name()
{
    $general = GeneralSetting::first();
    $sitname = str_word_count($general->sitename);
    $sitnameArr = explode(' ', $general->sitename);
    if ($sitname > 1) {
        $title = "<span>$sitnameArr[0] </span> " . str_replace($sitnameArr[0], '', $general->sitename);
    } else {
        $title = "<span>$general->sitename</span>";
    }

    return $title;
}


//moveable
function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $result = curlPostContent($url, $param);
    if ($result) {
        return $result;
    } else {
        return null;
    }
}


function getPageSections($arr = false)
{

    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}



function notify($user, $type, $shortCodes = null)
{

    send_email($user, $type, $shortCodes);
    send_sms($user, $type, $shortCodes);
}


/*SMS EMIL moveable*/

function send_sms($user, $type, $shortCodes = [])
{
    $general = GeneralSetting::first(['sn', 'sms_api']);
    $sms_template = \App\SmsTemplate::where('act', $type)->where('sms_status', 1)->first();
    if ($general->sn == 1 && $sms_template) {

        $template = $sms_template->sms_body;

        foreach ($shortCodes as $code => $value) {
            $template = shortCodeReplacer('{{' . $code . '}}', $value, $template);
        }
        $template = urlencode($template);

        $message = shortCodeReplacer("{{number}}", $user->mobile, $general->sms_api);
        $message = shortCodeReplacer("{{message}}", $template, $message);
        $result = @file_get_contents($message);
    }
}

function send_email($user, $type = null, $shortCodes = [])
{
    $general = GeneralSetting::first();

    $email_template = \App\EmailTemplate::where('act', $type)->where('email_status', 1)->first();
    if ($general->en != 1 || !$email_template) {
        return;
    }


    $message = shortCodeReplacer("{{name}}", $user->username, $general->email_template);
    $message = shortCodeReplacer("{{message}}", $email_template->email_body, $message);

    if (empty($message)) {
        $message = $email_template->email_body;
    }

    foreach ($shortCodes as $code => $value) {
        $message = shortCodeReplacer('{{' . $code . '}}', $value, $message);
    }
    $config = $general->mail_config;

    if ($config->name == 'php') {
        send_php_mail($user->email, $user->username, $general->email_from, $email_template->subj, $message);
    } else if ($config->name == 'smtp') {
        send_smtp_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    } else if ($config->name == 'sendgrid') {
        send_sendGrid_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    } else if ($config->name == 'mailjet') {
        send_mailjet_mail($config, $user->email, $user->username, $general->email_from, $general->sitetitle, $email_template->subj, $message);
    }
}


function send_php_mail($receiver_email, $receiver_name, $sender_email, $subject, $message)
{
    $gnl = GeneralSetting::first();
    $headers = "From: $gnl->sitename <$sender_email> \r\n";
    $headers .= "Reply-To: $gnl->sitename <$sender_email> \r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=utf-8\r\n";
    @mail($receiver_email, $subject, $message, $headers);
}


function send_smtp_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $mail = new PHPMailer(true);
    $gnl = GeneralSetting::first();
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = $config->host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $config->username;
        $mail->Password   = $config->password;
        if ($config->enc == 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        }else{
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }
        $mail->Port       = $config->port;
        $mail->CharSet = 'UTF-8';
        //Recipients
        $mail->setFrom($sender_email, $sender_name);
        $mail->addAddress($receiver_email, $receiver_name);
        $mail->addReplyTo($sender_email, $gnl->sitename);
        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();
    } catch (Exception $e) {
        throw new Exception($e);
    }
}


function send_sendGrid_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $sendgridMail = new \SendGrid\Mail\Mail();
    $sendgridMail->setFrom($sender_email, $sender_name);
    $sendgridMail->setSubject($subject);
    $sendgridMail->addTo($receiver_email, $receiver_name);
    $sendgridMail->addContent("text/html", $message);
    $sendgrid = new \SendGrid($config->appkey);
    try {
        $response = $sendgrid->send($sendgridMail);
    } catch (Exception $e) {
        // echo 'Caught exception: '. $e->getMessage() ."\n";
    }
}


function send_mailjet_mail($config, $receiver_email, $receiver_name, $sender_email, $sender_name, $subject, $message)
{
    $mj = new \Mailjet\Client($config->public_key, $config->secret_key, true, ['version' => 'v3.1']);
    $body = [
        'Messages' => [
            [
                'From' => [
                    'Email' => $sender_email,
                    'Name' => $sender_name,
                ],
                'To' => [
                    [
                        'Email' => $receiver_email,
                        'Name' => $receiver_name,
                    ]
                ],
                'Subject' => $subject,
                'TextPart' => "",
                'HTMLPart' => $message,
            ]
        ]
    ];
    $response = $mj->post(\Mailjet\Resources::$Email, ['body' => $body]);
}


function getPaginate($paginate = 15)
{
    return $paginate;
}


function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function getImage($image,$size = null)
{
    $clean = '';
    $size = $size ? $size : 'undefined';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }else{
        return route('placeholderImage',$size);
    }
}


function imagePath()
{
    $data['gateway'] = [
        'path' => 'assets/images/gateway',
        'size' => '800x800',
    ];
    $data['verify'] = [
        'withdraw'=>[
            'path'=>'assets/images/verify/withdraw'
        ],
        'deposit'=>[
            'path'=>'assets/images/verify/deposit'
        ]
    ];
    $data['image'] = [
        'default' => 'assets/images/default.png',
    ];
    $data['withdraw'] = [
        'method' => [
            'path' => 'assets/images/withdraw/method',
            'size' => '800x800',
        ]
    ];

    $data['watermark'] = [
        'path' => 'assets/images/',
         'size' => '800x400'
    ];
    $data['ticket'] = [
        'path' => 'assets/images/support',
    ];
    $data['language'] = [
        'path' => 'assets/images/lang',
        'size' => '64x64'
    ];
    $data['logoIcon'] = [
        'path' => 'assets/images/logoIcon',
    ];
    $data['favicon'] = [
        'size' => '128x128',
    ];
    $data['extensions'] = [
        'path' => 'assets/images/extensions',
    ];
    $data['seo'] = [
        'path' => 'assets/images/seo',
        'size' => '600x315'
    ];
    $data['profile'] = [
        'user'=> [
            'path'=>'assets/images/user/profile',
            'size'=>'350x300'
        ],
        'admin'=> [
            'path'=>'assets/admin/images/profile',
            'size'=>'400x400'
        ]
    ];
    return $data;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'd M, Y h:i A')
{
    $lang = session()->get('lang');
    \Carbon\Carbon::setlocale($lang);
    return \Carbon\Carbon::parse($date)->translatedFormat($format);
}

//moveable
function send_general_email($email, $subject, $message, $receiver_name = '')
{

    $general = GeneralSetting::first();


    if ($general->en != 1 || !$general->email_from) {
        return;
    }


    $message = shortCodeReplacer("{{message}}", $message, $general->email_template);
    $message = shortCodeReplacer("{{name}}", $receiver_name, $message);

    $config = $general->mail_config;



    if ($config->name == 'php') {
        send_php_mail($email, $receiver_name, $general->email_from, $subject, $message);
    } else if ($config->name == 'smtp') {
        send_smtp_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'sendgrid') {
        send_sendgrid_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    } else if ($config->name == 'mailjet') {
        send_mailjet_mail($config, $email, $receiver_name, $general->email_from, $general->sitename, $subject, $message);
    }
}

function getContent($data_keys, $singleQuery = false, $limit = null,$orderById = false)
{
    if ($singleQuery) {
        $content = \App\Frontend::where('data_keys', $data_keys)->latest()->first();
    } else {
        $article = \App\Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if($orderById){
            $content = $article->where('data_keys', $data_keys)->orderBy('id')->get();
        }else{
            $content = $article->where('data_keys', $data_keys)->latest()->get();
        }
    }
    return $content;
}

function gatewayRedirectUrl(){

    $plan = session()->get('pricePlan');
    $route = $plan? 'user.subscriptions': 'user.deposit';
    session()->forget('pricePlan');
    return $route;

}


function activeMenu($routeName, $type = null)
{
    if ($type == 1) {
        $class = 'open';
    } elseif ($type == 2) {
        $class = 'menuactive';
    } else {
        $class = '';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}

function watermark($image,$old=null,$oldThumb=null)
{
    $gnl = GeneralSetting::first();
    $directory = date("Y")."/".date("m")."/".date("d");
    $location = 'assets/contributor/watermark/'.$directory;
    ftpConfig($gnl);
    if($gnl->select_storage == 2){
        $driver = Storage::disk('custom-ftp');
    }
    if($gnl->select_storage == 3){
        $driver = Storage::disk('s3');
    }
    if (!empty($old)) {
        if(!removeFile('assets/contributor/watermark/'. $old)){
            removeFtpImage($old,$driver);
        }
        if(!removeFile('assets/contributor/watermark/'. $oldThumb)){
            removeFtpImage($oldThumb,$driver);
        }
    }
    $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
    
    $thumb = Image::make($image);
    $thumb->resize(335,350, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    
    $img = Image::make($image);
    $img->resize(1024,null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });

    
    $watermark = Image::make('assets/images/watermark.png')->opacity(65)->rotate(45)->greyscale()->fit($img->width(), $img->height());
    $img->insert($watermark,'center');

    if($gnl->select_storage == 1){
        makeDirectory($location);
        $img->save($location.'/'.$filename);
        $thumb->save($location.'/thumb_'.$filename);
    } else {
        makeDirectoryFtp($location,$driver);
        $mainImage = $img->stream();
        $driver->put($location.'/'.$filename,  $mainImage->__toString());
        $imageThumb = $thumb->stream();
        $driver->put($location.'/thumb_'.$filename, $imageThumb->__toString());

    }
    
    return [
        $directory.'/'.$filename,
        $directory.'/thumb_'.$filename,
    ];
    
}


function makeDirectoryFtp($location,$driver){
    if ($driver->exists($location)) {
        return true;
    }
    $driver->makeDirectory($location);
}


function removeFtpImage($file,$driver){
    $path = 'assets/contributor/watermark/'.$file;
    if($driver->exists($path)){
        $driver->delete($path);
    }
}
function removeFtpFile($file,$driver){
    $path = 'assets/contributor/files/'.$file;
    if($driver->exists($path)){
        $driver->delete($path);
    }
}

function ftpConfig($gnl){
    if($gnl->select_storage == 2){
        Config::set('filesystems.disks.custom-ftp.driver','ftp');
        Config::set('filesystems.disks.custom-ftp.host',@$gnl->ftp->host);
        Config::set('filesystems.disks.custom-ftp.username',@$gnl->ftp->username);
        Config::set('filesystems.disks.custom-ftp.password',@$gnl->ftp->password);
        Config::set('filesystems.disks.custom-ftp.port',@$gnl->ftp->port);
        Config::set('filesystems.disks.custom-ftp.root',@$gnl->ftp->root_path);
    }
    if($gnl->select_storage == 3){
        Config::set('filesystems.disks.s3.driver','s3');
        Config::set('filesystems.disks.s3.key',@$gnl->aws->key);
        Config::set('filesystems.disks.s3.secret',@$gnl->aws->secret);
        Config::set('filesystems.disks.s3.region',@$gnl->aws->region);
        Config::set('filesystems.disks.s3.bucket',@$gnl->aws->bucket);
        Config::set('filesystems.disks.s3.url',@$gnl->aws->url);
        Config::set('filesystems.disks.s3.endpoint',@$gnl->aws->endpoint);
    }

}

function thumb($image,$name)
{
    $img = Image::make($image);
    $img->resize(335,350, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
    $img->save('assets/contributor/thumb/'.$name);
}

function imageUrl($image,$size=null){
    $gnl = GeneralSetting::first(['select_storage','ftp','aws']);
    if($gnl->select_storage == 1){
        return getImage('assets/contributor/watermark/'.$image,$size);
    } else if($gnl->select_storage == 2) {
        if($image){
            return @$gnl->ftp->host_domain.'/assets/contributor/watermark/'.$image;
        } 
        return getImage('/',$size);
    } else {
        if($image){
           return Storage::disk('s3')->get('/assets/contributor/watermark/'.$image);
        }
        return getImage('/',$size);
    }
}
function fileUrl($file){
    $gnl = GeneralSetting::first(['select_storage','ftp','aws']);
    if($gnl->select_storage == 1){
        return 'assets/contributor/files/'.$file;
    } else if($gnl->select_storage == 2){
        return @$gnl->ftp->host_domain.'/assets/contributor/files/'.$file;
    } else {
        return  Storage::disk('s3')->get('/assets/contributor/files/'.$file);
    }
}


function getExtension($path)
{
$ext = pathinfo($path, PATHINFO_EXTENSION);
return $ext;
}

function number_format_short( $n, $precision = 1 ) {
if ($n < 900) {
    // 0 - 900
    $n_format = number_format($n, $precision);
    $suffix = '';
} else if ($n < 900000) {
    // 0.9k-850k
    $n_format = number_format($n / 1000, $precision);
    $suffix = 'K';
} else if ($n < 900000000) {
    // 0.9m-850m
    $n_format = number_format($n / 1000000, $precision);
    $suffix = 'M';
} else if ($n < 900000000000) {
    // 0.9b-850b
    $n_format = number_format($n / 1000000000, $precision);
    $suffix = 'B';
} else {
    // 0.9t+
    $n_format = number_format($n / 1000000000000, $precision);
    $suffix = 'T';
}
// Remove unecessary zeroes after decimal.

if ( $precision > 0 ) {
    $dotzero = '.' . str_repeat( '0', $precision );
    $n_format = str_replace( $dotzero, '', $n_format );
}
return $n_format . $suffix;
}

function paginateLinks($data, $design = 'admin.partials.paginate'){
    return $data->appends(request()->all())->links($design);
 }

 
function queryBuild($key, $value)
{
    $queries = request()->query();
    if(count($queries) > 0){
        $delimeter = '&';
    } else {
        $delimeter = '?';
    }
    if(request()->has($key)){
      $url = request()->getRequestUri();
      $pattern = "\?$key";
      $match = preg_match("/$pattern/",$url);
      if($match != 0){
        return  preg_replace('~(\?|&)'.$key.'[^&]*~', "\?$key=$value", $url);
      }
       $filteredURL = preg_replace('~(\?|&)'.$key.'[^&]*~', '', $url);
       return  $filteredURL.$delimeter."$key=$value";
    }
  
    return  request()->getRequestUri().$delimeter."$key=$value";
    

}

