<?php
error_reporting(0);
/**
 * PHP Instagram downloader API
 *
 * PHP version 5
 *
 * @category  Tools
 * @package   Instagram_API
 * @author    romaaji
 * @copyright 2010-2017 notepy
 * @license   MIT http://opensource.org/licenses/MIT
 * @link      https://notepy.gitlab.io
 */

/**
 * Fungsi mengecek URL
 *
 * @param string $url instagram url
 *
 * @return bool
 **/
function checkUrl($url)
{
    // jika tidak kosong
    if (!empty($url)) {
        $parse = parse_url($url);
        // jika url host instagram
        if ($parse['host'] == "instagram.com" || $parse['host'] == "www.instagram.com") {
            return true;
        } else {
            // bukan instagram
            return false;
        }
    } else {
        // url kosong
        return false;
    }
}//end checkUrl()

/**
 * Fungsi mendapatkan instagram download link
 *
 * @param string $content instagram url
 *
 * @return void
 **/


function genLink($content)
{
    // mendeteksi type
    preg_match('/<meta name="medium" content="(.*?)" \/>/', $content, $type);
    preg_match('/<meta property="og:title" content="(.*?)" \/>/', $content, $caption);
    preg_match('/@(.*?)\)/', $content, $author);
    preg_match('/<meta content="(.*?) Likes,/', $content, $like);
    preg_match('/Likes, (.*?) Comments -/', $content, $comment);
    // menemukan type
    if (!empty($type[1])) {
        // jika type video
        if ($type[1] == "video") {
            // video url
            preg_match('/<meta property="og:video" content="(.*?)" \/>/', $content, $videourl);



            // video img
            preg_match('/<meta property="og:image" content="(.*?)" \/>/', $content, $videoimg);
            $res = array(
                    "code"      => 200,
                    "type"        => "video",
                    "author"      => $author[1],
                    "caption"      => $caption[1],
                    "like"         => $like[1],
                    "comment"         => $comment[1],
                    "video_url"   => $videourl[1],
                    "video_thumb" => $videoimg[1],
                   );
        } else {
            // type image
            preg_match('/<meta property="og:image" content="(.*?)" \/>/', $content, $image);
            
            
            $repl = str_replace("\\u0026","&",$content);
            preg_match_all('/"display_url":"(.*?)","displ/', $repl, $matches, PREG_PATTERN_ORDER);

            $gambaru = array();
            for ($i = 0; $i < count($matches[1]); $i++) {
                $gambaru[] = $matches[1][$i];
                $total_record = $i;
            }

            $out = array_unique($gambaru, SORT_REGULAR);


            if($total_record > 0){
                $res = array(
                    "code"    => 200,
                    "type"      => "bulk-image",
                    "author"      => $author[1],
                    "caption"      => $caption[1],
                    "like"         => $like[1],
                    "comment"         => $comment[1],
                    "images_url" =>  array(
                        $out
                    ) ,
                    "total_record" => $total_record
                );            
            }else {
                $res = array(
                    "code"    => 200,
                    "type"      => "image",
                    "author"      => $author[1],
                    "caption"      => $caption[1],
                    "like"         => $like[1],
                    "comment"         => $comment[1],
                    "image_url" => $image[1],
                );
            }
            
            


        }//end if
    } else {
        // tidak ditemukan type
        $res = array(
                "code"  => "error",
                "err_msg" => "nothing found!",
               );
    }//end if
    // output
    return json_encode($res, (JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}//end genLink()
    
/**
 * Fungsi mendapatkan instagram download link untuk cli
 *
 * @param string $content instagram url
 *
 * @return void
**/
function getLink($content)
{
    //mendeteksi type
    preg_match('/<meta name="medium" content="(.*?)" \/>/', $content, $type);
    //menemukan type
    if (!empty($type[1])) {
        //jika type video
        if ($type[1] == "video") {
            //video url
            preg_match('/<meta property="og:video" content="(.*?)" \/>/', $content, $videourl);
            //video img
            preg_match('/<meta property="og:image" content="(.*?)" \/>/', $content, $videoimg);
            $res = $videourl[1];
        } //type image
        else {
            preg_match('/<meta property="og:image" content="(.*?)" \/>/', $content, $image);
            $res = $image[1];
        }
    } //tidak ditemukan type
    else {
        $res = false;
    }
    //output
    return $res;
} // end getLink()
    

    /**
 * Fungsi CLI interface download instagram content
**/
function promCon()
{
    while (true) :
        echo"instagram post url:";
         $url = fgets(STDIN);
        $url = str_replace("\n", "", $url);
        if ($url=="exit()") :
            break;
        endif;
        if (checkUrl($url)) :
            $link=getLink(file_get_contents($url));
            if ($link!==false) :
                $name=parse_url(basename($link));
                $name=$name['path'];
                 $targetFile = fopen("download/$name", 'w');
                 $ch = curl_init($link);
                 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                 curl_setopt($ch, CURLOPT_FILE, $targetFile);
                 curl_exec($ch);
                 curl_close($ch);
                fclose($targetFile);
                 echo"saved as download/$name complete!\n";
            else :
                    echo"\033[31mNOTHING FOUND!  \033[0m  \n";
            endif;
        else :
                echo"\033[31mINVALID URL!  \033[0m  \n";
        endif;
    endwhile;
} // end promCon()
    
    /**
 * Fungsi CLI interface download instagram profile image
**/

function promProf()
{
    while (true) :
        echo"instagram username:";
        $user = fgets(STDIN);
        $user=str_replace("\n", "", $user);
        if ($user=="exit()") :
            break;
        endif;
        //process data
        $data=json_decode(file_get_contents("https://www.instagram.com/$user/?__a=1"), true);
        if (isset($data['user']['profile_pic_url_hd'])) :
            $urldownload=$data['user']['profile_pic_url_hd'];
            $targetFile = fopen("download/$user.jpg", 'w');
            $ch = curl_init($urldownload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FILE, $targetFile);
            curl_exec($ch);
            curl_close($ch);
            fclose($targetFile);
            echo"saved as download/$user.jpg complete!\n";
        else :
                echo"\033[31mInvalid instagram username!  \033[0m  \n";
        endif;
    endwhile;
} // end promProf()
