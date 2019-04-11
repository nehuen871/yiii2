<?php
   namespace app\assets;
   use yii\web\AssetBundle;
   use yii\web\View;
   /*class DemoAsset extends AssetBundle {
      public $basePath = '@webroot';
      public $baseUrl = '@web';
      public $js = ['js/demo.js'];
      public  $jsOptions = ['position' => View::POS_HEAD];
   }*/

   class DemoAsset extends AssetBundle {
      public $basePath = '@webroot';
      public $baseUrl = '@web';
      public $js = [
         'js/demo.js',
         'js/greeting.ts'
      ];
      public  $jsOptions = ['position' => View::POS_HEAD];
   }
?>