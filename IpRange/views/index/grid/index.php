<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
/* @var $this \yii\web\View */
/* @var $searchProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \app\modules\tools\extensions\IpRange\Interfaces\ISearchModel */
/* @var $gridColumns array */
/* @var $gridAttributeOptions array */
/* @var $gridWidgets \app\modules\tools\extensions\IpRange\Interfaces\IMethodWidget[] */
?>
<div class="tab-content">
    <div class="well well-sm well-n ipRangeGridHeader">
        <div class="col-lg-6">
            <?php if(count($gridAttributeOptions)) : ?>
                <div class="ipRangeMergeBox row">
                    <?php echo Html::beginForm(['index'], 'get', ['id' => 'ipRangeOptionsForm'])?>
                    <?php foreach (array_keys($gridAttributeOptions) as $attribute) : ?>
                        <?php echo Html::activeCheckbox($searchModel, $attribute)?>
                    <?php endforeach;?>
                    <?php echo Html::endForm();?>
                </div>
            <?php endif;?>
        </div>
        <div class="col-lg-6">
            <?php foreach ($gridWidgets as $gridWidget) : ?>
                <?php echo $gridWidget::widget()?>
            <?php endforeach;?>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php echo GridView::widget([
        'id' => 'ipRangeGrid',
        'dataProvider' => $searchProvider,
        'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-hover ipRangeTable',
        ],
    ]); ?>
</div>

<script type="text/javascript">
    var optionsForm = $('#ipRangeOptionsForm');
    optionsForm.change(function() {$(this).trigger('submit')});
</script>
