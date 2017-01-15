<?php
use cacatuidae\ipRange\Module;
/* @var $this \yii\web\View */
/* @var $url string */
?>
<div class="pull-right">
    <a href="#" class="btn btn-success disabled" id="ip_range_extracts_ip">
        <?php echo Module::t('BTN_EXTRACTS_IP', 'ip_range')?></a>
</div>
<script type="text/javascript">
$(function() {
    var grid = $('#ipRangeGrid');
    var grid_ready = grid.find('tbody').find('tr').length;
    if(grid_ready && grid.find('tbody').find('tr').find('.empty').length)
        grid_ready = false;
    var ip_range_extracts_ip = $('#ip_range_extracts_ip');
    if(grid_ready)
        ip_range_extracts_ip.removeClass('disabled');
    ip_range_extracts_ip.click(function(e) {
        e.preventDefault();
        window.location.href = '<?php echo $url?>' + window.location.search;
    });
});
</script>
