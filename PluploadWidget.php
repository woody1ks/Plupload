<?php
/**
 * Copyright (c) 2010, Gareth Bond, http://www.gazbond.co.uk
 * Upgraded by Woody Whitman 2013, latest Plupload and vairous other items
 * woodywhitman@gmial.com
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided
 * that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice, this list of conditions and the
 *     following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 *     the following disclaimer in the documentation and/or other materials provided with the distribution.
 *   * Neither the name of Yii Software LLC nor the names of its contributors may be used to endorse or
 *     promote products derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
 * PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Yii widget wrapper for Pupload: http://www.plupload.com/
 * Allows you to upload files using HTML5 Gears, Silverlight, Flash, BrowserPlus or normal forms,
 * providing some unique features such as upload progress, image resizing and chunked uploads.
 *
 * Config options: http://www.plupload.com/documentation.php
 *
 * Usage:
 * <pre>
 * <?php $this->widget('application.extensions.Plupload.PluploadWidget', array(
 *   'config' => array(
 *       'runtimes' => 'flash',
 *       'url' => '/image/upload/',
 *   ),
 *   'id' => 'uploader'
 * )); ?>
 * </pre>
 *
 * @author gazbond
 */ 
class PluploadWidget extends CWidget {

    const ASSETS_DIR_NAME       = 'assets';
    const PLUPLOAD_FILE_NAME    = 'plupload.full.js';
    const JQUERYQUEUE_FILE_NAME = 'jquery.plupload.queue/jquery.plupload.queue.js';
    const GEARS_FILE_NAME       = 'gears_init.js';  //DEPRICATED
    const BROWSER_PLUS_URL      = 'http://bp.yahooapis.com/2.4.21/browserplus-min.js';
    const FLASH_FILE_NAME       = 'plupload.flash.swf';
    const SILVERLIGHT_FILE_NAME = 'plupload.silverlight.xap';
    const DEFAULT_RUNTIMES      = 'html5,gears,flash,silverlight,browserplus,html4';
    const PUPLOAD_CSS_PATH      = 'jquery.plupload.queue/css/jquery.plupload.queue.css';

    public $config = array();
    
    public $model = null;
    
    public $attribute = null;
    
    public function init() {        
        
        $localPath = dirname(__FILE__) . "/" . self::ASSETS_DIR_NAME;
        $publicPath = Yii::app()->getAssetManager()->publish($localPath);
        
        Yii::app()->clientScript->registerScriptFile(self::BROWSER_PLUS_URL);

        $pluploadPath = $publicPath . "/" . self::PLUPLOAD_FILE_NAME;
        Yii::app()->clientScript->registerScriptFile($pluploadPath);

        $jQueryQueuePath = $publicPath . "/" . self::JQUERYQUEUE_FILE_NAME;
        Yii::app()->clientScript->registerScriptFile($jQueryQueuePath);

        $cssPath = $publicPath . "/" . self::PUPLOAD_CSS_PATH;
        Yii::app()->clientScript->registerCssFile($cssPath);

        if(!isset($this->config['flash_swf_url'])) {
          $flashUrl = $publicPath . "/" . self::FLASH_FILE_NAME;
          $this->config['flash_swf_url'] = $flashUrl;
        }

        if(!isset($this->config['silverlight_xap_url'])) {
          $silverLightUrl = $publicPath . "/" . self::SILVERLIGHT_FILE_NAME;
          $this->config['silverlight_xap_url'] = $silverLightUrl;
        }

        if(!isset($this->config['runtimes'])) {
          $this->config['runtimes'] = self::DEFAULT_RUNTIMES;
        }    
        
        if ($this->model&&$this->attribute) {
          $this->config['file_data_name'] = get_class($this->model)."[$this->attribute]";
        }

        $jsConfig = json_encode($this->config);
        $jqueryScript = "jQuery('#$this->id').pluploadQueue({$jsConfig});";
        $uniqueId = 'Yii.' . __CLASS__ . '#' . $this->id;
        Yii::app()->clientScript->registerScript($uniqueId, stripcslashes($jqueryScript), CClientScript::POS_READY);
    }

    public function run()
    {
      $fall_back_copy = CHtml::tag('p',array(),"You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.");
      echo CHtml::tag('div', array('id'=>$this->id), $fall_back_copy);
    }
}
?>
