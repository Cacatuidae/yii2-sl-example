<?php
/* @var $this \yii\web\View */
/* @var $uploadModel \cacatuidae\ipRange\Interfaces\IUploadModel*/
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use cacatuidae\ipRange\Module;
?>
<div class="well well-sm well-n">
<?php $form = ActiveForm::begin(['id' => 'IpRangeUploadForm', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php echo $form->errorSummary($uploadModel);?>
    <div class="pull-left">
        <?php echo $form->field($uploadModel, $uploadModel->getFileAttribute())->fileInput()?>
    </div>
    <div class="pull-left IpRangeUploadFormBtn">
        <?php echo Html::submitButton(Module::t('BTN_UPLOAD_FILE', 'ip_range'), ['class' => 'btn btn-primary'])?>
    </div>
    <div class="clearfix"></div>
<?php ActiveForm::end(); ?>
</div>