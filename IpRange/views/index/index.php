<?php
/* @var $this \yii\web\View */
/* @var $uploadModel \app\modules\tools\extensions\IpRange\Interfaces\IUploadModel */
/* @var $searchProvider \app\modules\tools\extensions\IpRange\Interfaces\IProvider */
/* @var $searchModel \app\modules\tools\extensions\IpRange\Interfaces\ISearchModel */
/* @var $gridColumns array */
/* @var $gridAttributeOptions array */
/* @var $gridWidgets \app\modules\tools\extensions\IpRange\Interfaces\IMethodWidget[] */
?>
<div class="contentBox col-lg-12 ipRangeBox">
<?php
echo $this->render('form/upload_file', ['uploadModel' => $uploadModel]);
echo $this->render('grid/index', ['searchProvider' => $searchProvider, 'gridColumns' =>
    $gridColumns, 'searchModel' => $searchModel, 'gridAttributeOptions' => $gridAttributeOptions,
    'gridWidgets' => $gridWidgets]);
?>
</div>
<div class="clearfix"></div>
