<?php

namespace common\assets;

class PrimerMarkdownAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/primer-markdown/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'user-content.min.css',
    ];
}