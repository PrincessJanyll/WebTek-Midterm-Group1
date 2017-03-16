<?php



global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";

$stringCheck = "checked='checked'";
$stringSelected = "selected='selected'";

$showRateChecked = ($configuration['showRate']=== 'true')?$stringCheck:"";
$responsiveChecked = ($configuration['responsive']=== 'true')?$stringCheck:"";
$bp_shareActivityChecked = ($configuration['bp_shareActivity']=== 'true')? $stringCheck:"";
$bp_shareResultChecked = ($configuration['bp_shareResult']=== 'true')?$stringCheck:"";
$share_socialNetwork = ($configuration['share_socialNetwork']=== 'true')?$stringCheck:"";
$showTagChecked = ($configuration['showTag']=== 'true')?$stringCheck:"";
$showGlossaryChecked = ($configuration['showGlossary']=== 'true')?$stringCheck:"";
$selectedMedium = ($configuration['sizePlayer']=== 'medium')?$stringSelected:"";
$selectedSmall = ($configuration['sizePlayer']=== 'small')?$stringSelected:"";
$selectedLarge = ($configuration['sizePlayer']=== 'large')?$stringSelected:"";

if( function_exists('bp_is_active') )
    $disabled = (bp_is_active('groups'))?"":"disabled='disabled'";
else
    $disabled = "disabled='disabled'";

$classLabel = ($disabled!=="")?"class='disabled'":"";


$confirm = "onclick='return confirm(\"". $tr->__("Do you want to delete this / these Domain(s)?") ."\")'";

$settingsActive = (!isset($_POST['add']) && !isset($_POST['remove']))?true:false;

?>
<style>
    label.disabled
    {
        color : #aaa;
    }
    .container-fluid
    {
        margin-top: 20px;
    }

    .loading{
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.9;
        z-index: 1060;
        background: url('<?php echo  __ROOT_PLUGIN__2 ?>images/loading.gif') no-repeat 50% 50%,#FFF;
    }

</style>
<div class="container-fluid">




    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" ><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><b><?php $tr->_e("Settings"); ?></b></a></li>
                <li role="presentation" > <a href="#domains" aria-controls="domains" role="tab" data-toggle="tab"><b><?php $tr->_e("Domains"); ?></b></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane" id="settings">

                    <?php
                    sp_display_link_help("modal-help-config");
                    ?>



    <div class= "col-md-6">

        <h2><?php $tr->_e("Configuration"); ?></h2>
        <div class="panel panel-default">

            <div class="panel-body">
                <form action="" method="post">
                    <div class="checkbox">
                        <label><input type="checkbox" name="showRate" <?php echo $showRateChecked ?>  /> <?php $tr->_e("Show rating"); ?></label>
                        <br/>
                        <br/>
                        
                        
                        <label><input type="checkbox" name="responsive" <?php echo $responsiveChecked ?> /><?php $tr->_e("Responsive player"); ?> </label>           
                        <br/>
                        <br/>
                        
                        
                        <label><input type="checkbox" name="showTag"  <?php echo $showTagChecked ?> /> <?php $tr->_e("show Tags in player"); ?></label>
                        <br/>
                        <br/>
                        
                        
                        
                        <label><input type="checkbox" name="showGlossary"  <?php echo $showGlossaryChecked ?> /> <?php $tr->_e("show glossary in player"); ?></label>
                        <br/>
                        <br/>
                        
                        <label><?php echo $tr->__("Size of player"); ?> </label><select name="sizePlayer" id="sizePlayer">
                            <option value="small" <?php echo $selectedSmall ?>><?php echo $tr->__("Small"); ?></option>
                            <option value="medium" <?php echo $selectedMedium ?>><?php echo $tr->__("Medium"); ?></option>
                            <option value="large" <?php echo $selectedLarge ?>><?php echo $tr->__("Large"); ?></option>
                        </select>
                        <br/>
                        <br/>
                        
                        
                                                                      
                        <label <?php echo $classLabel ?>><input type="checkbox" name="bp_shareActivity"  <?php echo $bp_shareActivityChecked ?> <?php echo $disabled ?> /><?php $tr->_e("Share automatically lessons and quizzes when published, on buddypress"); ?> </label>
                        <br/>
                        <br/>
                        
                        
                        
                        <label><input type="checkbox" name="share_socialNetwork"  <?php echo $share_socialNetwork ?> /> <?php $tr->_e("Users can share lessons and quizzes on socials network"); ?></label>
                        <br/>
                        <br/>
                        
                        
                        
                        <label <?php echo $classLabel ?>><input type="checkbox" name="bp_shareResult"  <?php echo $bp_shareResultChecked ?> <?php echo $disabled ?> /> <?php $tr->_e("Users can share lessons and quizzes on buddypress"); ?></label>
                        <br/>
                        <br/>


                    </div>


                    <button type="submit" name="validate" class="btn btn-primary center-block" > Validate </button>
                </form>
            </div>


    </div>
        </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="domains">

                    <!-- Help Link-->
                    <?php
                    sp_display_link_help("modal-help-domain");
                    ?>
                    <!-- -->


                    <?php require_once __ROOT_PLUGIN__ . "controllers/domain.controller.php"; ?>
                </div>
                </div>
            </div>
        </div>
    </div>


<?php


$contentConfig = "You can use this setting to configure the following options:
<ul style='list-style :disc;margin:20px'>   
<li><b>Show rating:</b><br/> display the rating (5 stars) at the end of each lesson and quiz;</li>
<li><b>Responsive player:</b><br/> the lesson or quiz player size follow the width of the page;</li>
<li><b>show Tags in player:</b><br/> Display the \"Tags\" tab in the lesson and quiz player;</li>
<li><b>show glossary in player:</b><br/> Display the \"Glossary\" tab in the lesson ans quiz player;</li>
<li><b>Size of player:</b><br/> Set the size of the lesson and quiz player. Three preconfigured sizes are proposed;</li>
<li><b>Share automatically lessons and quizzes when published on buddypress:</b><br/> activate the sharing option of lessons and quizzes on buddypress (an integrated social network in wordpress)</li>
<li><b>Users can share lessons and quizzes on socials network:</b><br/> activate the sharing option of lessons and quizzes on social networks (facebook, twitter, g+ and linkedin)</li>
<li><b>Users can share lessons and quizzes on buddypress:</b><br/> activate the sharing option of quizzes result on buddypress</li></ul>";
$msgAccess= "This feature is available for users who have <b>administrator</b> rights.";
sp_display_modal_help($contentConfig,$msgAccess,"modal-help-config");





?>




<script>
    (function($) {
        $(".sp-help").css("top","50px");
        $(document).ready(function () {

            $('.nav a:<?php echo ($settingsActive)?"first":"last"; ?>').tab('show');
        })
    })(jQuery);
</script>

