<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 29/03/2015
 * Time: 15:53
 */


function sp_display_link_help($id="modal-help"){
    global $tr;
?>
    <!-- Help Link-->
    <a class="sp-help" href="#" data-toggle="modal" data-target="#<?php echo $id; ?>"><span class="glyphicon glyphicon-exclamation-sign"></span><?php $tr->_e("Help"); ?></a>
    <!-- -->
<?php
}



function sp_display_modal_help($content,$msgAccess,$id="modal-help"){
    global $tr;
?>
    <!-- Modal d'aide  -->
<div class="modal fade" id="<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php $tr->_e("Help") ?> !</h4>
            </div>
            <div class="modal-body">
                <?php echo $content ?>
                <div class="sp-access-right">
                    <b><?php echo $tr->__("Right Access"); ?>:</b>
                    <p class="text-help"><img src="<?php echo __ROOT_PLUGIN__2 ?>/images/acces.png" alt="Right acces" /><span><?php echo $msgAccess; ?></span></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php $tr->_e("Close"); ?></button>
            </div>
        </div>
    </div>
</div>
<?php
}