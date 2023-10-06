<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/app.css',
        'css/test-view.css',
        'css/btns.css',
        'css/student-test-view.css',
        'css/fonts.css'
       
        
    ];
    public $js = [
        'js/app.js',
        'js/sidebar.js',
        'assets/607c3756/yii2-dynamic-form.min.js',
        'js/courseList.js',
        'js/testCreate.js',
        'js/timer.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
