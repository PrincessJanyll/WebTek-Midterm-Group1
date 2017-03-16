<?php
/**
 * Created by PhpStorm.
 * User: Salim
 * Date: 18/03/2015
 * Time: 17:24
 */

?>

<script>
    (function($) {
        $(document).ready(function () {


            //Qualité Rating...

            var pathQuality = "<?php echo  __ROOT_PLUGIN__2 ?>controllers/ratingQuality.controller.php";

            var rateQuality = $(".sp-rate-quality").rateYo({
                starWidth: "40px",
                rating: "<?php echo  ($user->isLoggedIn())?(($managerRate->voteExist($id,$user->id()))?$managerRate->voteExist($id,$user->id())->getValue():"0"):"0" ?>",
                fullStar: true,
                onChange: function (rating, rateYoInstance) {

                    //console.log("this is a new function");
                    //$(this).next().text(rating);
                }

            }).on("rateyo.set", function (e, data) {

                requestRate($(this), data.rating, pathQuality);
            });




            //Domain Rating
            var pathDomain = "<?php echo  __ROOT_PLUGIN__2 ?>controllers/ratingDomain.controller.php";

            $(".sp-rate-domain").each(function () {
                var item = $(this);
                item.rateYo({
                    starWidth: "30px",
                    rating: $(this).data("average"),
                    fullStar: true
                }).on("rateyo.set", function (e, data) {
                    console.log($.data(this, "average"));
                    requestRate($(this), data.rating, pathDomain);
                });
            });

        });
    })(jQuery);
</script>