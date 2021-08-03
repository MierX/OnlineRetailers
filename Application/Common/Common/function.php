<?php

// 有选择性的过滤xss攻击（吃性能，少用）
if (!function_exists('removeXSS')) {
    /**
     * @param $data
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
    }
}

