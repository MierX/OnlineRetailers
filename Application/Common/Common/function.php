<?php

/**
 * 有选择性的过滤xss攻击（吃性能，少用）
 */

use Think\Image;
use Think\Upload;

/**
 * 过滤XSS
 */
if (!function_exists('removeXSS')) {
    /**
     * @param $data
     * @return mixed
     */
    function removeXSS($data)
    {

        require_once './HtmlPurifier/library/HTMLPurifier.auto.php';
        $_clean_xss_config = HTMLPurifier_Config::createDefault();
        $_clean_xss_config->set('Core.Encoding', 'UTF-8');
        $_clean_xss_config->set('HTML.Allowed', 'div,b,strong,i,em,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]');
        $_clean_xss_config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
        $_clean_xss_config->set('HTML.TargetBlank', TRUE);
        $_clean_xss_obj = new HTMLPurifier($_clean_xss_config);
        $_clean_xss_obj->purify($data);
        return $data;
    }
}

/**
 * 上传图片
 */
if (!function_exists('uploadOne')) {
    /**
     * @param $imgName
     * @param $dirName
     * @param array $thumb
     * @return array
     */
    function uploadOne($imgName, $dirName, $thumb = [])
    {

        if (isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0) {
            $ci = C('IMAGE_CONFIG');

            $upload = new Upload(['rootPath' => $ci['rootPath']]);
            $upload->maxSize = (int)$ci['maxSize'] * 1024 * 1024;
            $upload->exts = $ci['exts'];
            $upload->savePath = $dirName . '/';
            $info = $upload->upload([$imgName => $_FILES[$imgName]]);
            if (!$info) {
                return [
                    'code' => 1,
                    'msg' => $upload->getError(),
                    'data' => [],
                ];
            } else {
                $data = [
                    'logo' => $info['logo']['savepath'] . $info['logo']['savename'],
                    'sm_logo' => $info['logo']['savepath'] . 'sm_' . $info['logo']['savename'],
                    'mid_logo' => $info['logo']['savepath'] . 'mid_' . $info['logo']['savename'],
                    'big_logo' => $info['logo']['savepath'] . 'big_' . $info['logo']['savename'],
                    'mbig_logo' => $info['logo']['savepath'] . 'mbig_' . $info['logo']['savename'],
                ];

                $image = new Image();
                $image->open("{$ci['rootPath']}/{$data['logo']}");
                $image->thumb($thumb['sm'][0], $thumb['sm'][1])->save("{$ci['rootPath']}/{$data['sm_logo']}", $thumb['sm'][2]);
                $image->thumb($thumb['mid'][0], $thumb['mid'][1])->save("{$ci['rootPath']}/{$data['mid_logo']}", $thumb['mid'][2]);
                $image->thumb($thumb['big'][0], $thumb['big'][1])->save("{$ci['rootPath']}/{$data['big_logo']}", $thumb['big'][2]);
                $image->thumb($thumb['mbig'][0], $thumb['mbig'][1])->save("{$ci['rootPath']}/{$data['mbig_logo']}", $thumb['mbig'][2]);

                return [
                    'code' => 0,
                    'msg' => '保存成功',
                    'data' => $data,
                ];
            }
        }
        return [
            'code' => 1,
            'msg' => '没有文件上传',
            'data' => [],
        ];
    }
}

/**
 * 显示图片
 */
if (!function_exists('showImage')) {
    /**
     * @param $url
     * @param string $width
     * @param string $height
     */
    function showImage($url, $width = '', $height = '')
    {

        $ic = C('IMAGE_CONFIG');
        if ($width) {
            $width = "width=$width";
        }
        if ($height) {
            $height = "height=$height";
        }
        echo "<img alt='' $width $height src='{$ic['viewPath']}$url' >";
    }
}

/**
 * 删除图片
 */
if (!function_exists('deleteImage')) {
    /**
     * @param array $image
     */
    function deleteImage($image = [])
    {

        $savePath = C('IMAGE_CONFIG.rootPath');
        unlink("$savePath/{$image['logo']}");
        unlink("$savePath/{$image['sm_logo']}");
        unlink("$savePath/{$image['mid_logo']}");
        unlink("$savePath/{$image['big_logo']}");
        unlink("$savePath/{$image['mbig_logo']}");
    }
}

