Plupload
========

Updated Yii Wrapper for Plupload v1.5.7

Recently upgraded this for a project needing IE7 uploads
Just so no one else has to go through the pain I just had to go through. I hope this helps someone.

https://github.com/woody1ks/Plupload.git

Update Comments:

 - upgraded to latest Plupload v1.5.7

 - Added preferred support for HTML5

 - removed redundant gears code thanks to browser plus

 - Added yii model support

 - Added HTML Attributes support.

<?php 
$this->widget('application.extensions.Plupload.PluploadWidget', array(
   'config' => array(
       'runtimes' => 'flash',
       'url' => '/image/upload/',
   ),
   'model'=>$model,
   'attribute'=>'files',
   'id' => 'uploader',
   'htmlAttributes'=> array(
       'class' => 'myUploaderClass',
   ),
)); 
?>
