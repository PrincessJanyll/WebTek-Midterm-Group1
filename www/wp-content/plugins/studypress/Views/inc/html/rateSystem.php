<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 18/03/2015
 * Time: 17:04
 */

// Interdire l'accée direct...
if ( !defined( 'ABSPATH' ) ) exit;


$managerRate = new RateQualityManager();

?>
<!-- Div rating-->
    <div class="sp-rater">
        <div class="sp-btn-rater"></div>
            <div class="sp-content-rater hide">




                <!-- Rate Quality -->
                <div class="sp-rater-quality">
                    <?php
                    $managerDomain = new DomainManager();
                    $managerRateDomain = new RateDomainManager();
                    $domains = $managerDomain->getAll();
                    ?>
                    <h2><?php if ($domains) { echo  $tr->__("Overall rating"); } else { echo $tr->__("Rating"); } ?></h2>

                    <div class="sp-rate-quality" data-id="<?php echo  $id ?>"  data-user="<?php echo  $user->id() ?>" ></div>
                    <?php
                    echo $tr->__("Number of raters") . ": " . $managerRate->countRate($id) ."<br/>";
                    echo $tr->__("Average") . ": " . round((float) $managerRate->AVG($id),2) ."<br/>";
?>
                    <div class="serverResponse"><p>&nbsp;</p></div>
                </div>




                <!-- Rate Domains -->
                <div class="sp-rater-domain">
                <?php
                

                foreach ($domains as $domain) :
                    ?>

                    <div class="div-rate-domain">

                        <?php echo  $domain->getName() ?>
                        <div class="sp-rate-domain" data-average="<?php echo  ($user->isLoggedIn())?(($managerRateDomain->voteExist($id,$user->id(),$domain->getId()))?$managerRateDomain->voteExist($id,$user->id(),$domain->getId())->getValue():"0"):"0" ?>" data-id="<?php echo  $id ?>" data-domain="<?php echo  $domain->getId() ?>" data-user="<?php echo  $user->id() ?>"></div>
                        <?php
                        echo $tr->__("Number of raters") . ": " . $managerRateDomain->countRate($id,$domain->getId()) ."<br/>";
                        echo $tr->__("Average") . ": " . round((float) $managerRateDomain->AVG($id,$domain->getId()),2) ."<br/>";
                        ?>
                        <div class="serverResponse"><p>&nbsp;</p></div>

                    </div>
                <?php
                endforeach;
                ?>

            </div>

        </div>
</div>


