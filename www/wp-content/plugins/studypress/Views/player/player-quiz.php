<?php

// Interdire l'accée direct...
if ( !defined( 'ABSPATH' ) ) exit;



$type = "quiz";
$sp_sizePlayer = $spConfiguration['sizePlayer'];

require_once __ROOT_PLUGIN__ .'Views/player/includeCSSPlayer.php';

//Responsive
if($spConfiguration['responsive'] === 'true')
    require_once __ROOT_PLUGIN__ .'Views/player/includeResponsiveCSS.php';


?>





<div id="fullscreen-sp-player" class="sp-player">
    <span class="sp-powered">Powered by StudyPress</span>
	<div class="sp-player-left hide">
		<div class="sp-player-profil">
            <a href="" class="sp-img-profil"></a>
            <a href="" class="sp-name-profil"></a>
            <div class="hr"></div>
            <h4 class="sp-title-quiz"></h4>
        </div>
         <div class="sp-player-tabs">
            <div class="tabs tabs-style-topline">
            <nav>
                <ul>
                    <li><a href="#section-topline-1" class="icon"><span>Menu</span></a></li>
                    
                    
                    <?php if($spConfiguration['showTag'] === 'true') : ?>
                    
                    <li><a href="#section-topline-2" class="icon"><span>Tags</span></a></li>
                    
                    <?php endif; if($spConfiguration['showGlossary'] === 'true') : ?>
                    
                    <li><a href="#section-topline-3" class="icon"><span><?php $tr->_e("Glossaries"); ?></span></a></li>

                    <?php endif; ?>

                </ul>
            </nav>
            <div class="content-wrap">
                <section id="section-topline-1">
                    <ul>

                        <!-- Les titres des  slides -->

                    </ul>
                </section>
                
                <?php if($spConfiguration['showTag'] === 'true') : ?>
                <section id="section-topline-2">
                    <ul>
                    <?php
                    foreach ($quiz->getTags() as $note) {
                        echo "<li>". $note ."</li>";
                    }


                    ?>
                    </ul>
                </section>
                
                <?php endif; if($spConfiguration['showGlossary'] === 'true') : ?>
                <section id="section-topline-3">
                    <ul>
                    <?php
                    $g = $quiz->getGlossary();
                    for ($i=0;$i<count($g->name);$i++) {
                        echo "<li><b>".$g->name[$i] ."</b> : ".$g->desc[$i]."</li>";
                    }


                    ?>
                    </ul>
                </section>
                <?php endif; ?>

            </div><!-- /content -->
        </div><!-- /tabs -->
        </div>

	</div>


	<div class="sp-player-right">
        <div class="sp-player-header">
            <span class="sp-player-nbr_questions">Question n°<span class="current-question"></span>/<span class="nbr-question"></span></span>
            <span class="sp-player-minuteur"><span id="hours">00</span>:<span id="minutes">00</span>:<span id="seconds">00</span></span>
        </div>
		<div class="sp-player-right-top">
			<div id="carousel" class="slide">


            <!-- Les Slides sont chargés depuis un fichier Json -->
            <div class="owl-item">
                <?php echo  slide_presentation_quiz($quiz,$sp_userName) ?>
            </div>

      		</div>
		</div>
		<div class="sp-player-right-bottom">
            <button class="btn-begin"><?php $tr->_e("Start the Quiz");  ?></button>
            <div class="buttons-control hide">
				<button class="btn-next">Next</button>
				<button class="btn-prev">Prev</button>
            </div>

            <div class="div-share">

                <?php echo $btn_social_share; ?>
                <?php echo $btn_buddypress_share; ?>
            </div>

            <div class="buttons-control-left">
                <?php if($btn_social_share!=="" || $btn_buddypress_share!=="") echo $sp_btn_share; ?>
                <button class="full-screen" title="Plein écran">fullScreen</button>
            </div>
		</div>
	</div>

    <?php
    $user =  new StudyPressUserWP();
    if($spConfiguration['showRate'] === 'true')
        require_once __ROOT_PLUGIN__ ."Views/inc/html/rateSystem.php";
    ?>





    <!-- Modal Share -->
    <?php
    if($btn_buddypress_share!==""):
    ?>

        <div id="login-box" class="login-popup">
            <div class="loading hide"></div>
            <a href="#" class="close"><img src="<?php echo  __ROOT_PLUGIN__2 . "images/close.png" ?>" class="btn_close" title="Close Window" alt="Close" /></a>
            <div class="content"></div>
        </div>

    <?php
    endif;
    ?>
</div>


<script src="<?php echo  __ROOT_PLUGIN__2 . "js/owl.carousel.min.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/jquery.rateyo.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/rating-function.js" ?>"></script>
        <!-- tabs Mary lou -->
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/cbpFWTabs.js" ?>"></script>
<script src="<?php echo  __ROOT_PLUGIN__2 . "js/minuteur.js" ?>"></script>
<?php
$unCountingSlide = 2;
if($spConfiguration['showRate'] === 'true')
{
    $unCountingSlide = 3;
    require_once __ROOT_PLUGIN__ ."Views/inc/js/rateSystem.php";
}

?>


<script>
    (function($) {
        $(document).ready(function () {


            var sp_owl;
            var unCountingSlide = <?php echo $unCountingSlide ?>;


            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }




            function validate() {
                $('.loading').removeClass("hide");


                var question = {
                    accounting: []
                };
                $(".sp-player .sp-qcm").each(function () {

                    $(this).find("li").each(function () {
                        var prop = $(this).find("input").is(':checked') ? true : false;
                        var id_question = $(this).find("input").data("id");
                        var id_prop = $(this).find("input").data("prop");

                        console.log(id_question +" -> " +id_prop);
                        question.accounting.push({"idQuestion": id_question, "idProp": id_prop, "true": prop});

                    });
                });


                //Envoyer la requête ajax...
                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/validate-qcm.php",
                    {
                        type: "validate",
                        question: question,
                        id_quiz: <?php echo  $id ?>
                    }
                    //Si y pas d'erreur:
                    , function (data) {
                        //console.log(data);
                        if(trimStr(data.result) === "true") {
                        $(".sp-player .sp-result").html(data.content);
                            writeResult(true);
                            //reactualiser la page
                            if(data.connected === 'true')
                            {
                                
                                $.ajax({
                                    url:"" ,
                                    context: document.body,
                                    success: function (s, x) {
                                        $('body').html(s);
                                    }
                                });
                            }


                            $(".sp-player .slide .sp-qcm").each(function (index) {
                                    $(this).html(data.qcm[index]);
                            });




                        }
                        else
                        {
                            $(".sp-player .sp-result").html("");
                        }



                    },'json').error(function (data) {

                        //$("body").append(data);

                    }).always(function () {
                        $('.loading').addClass("hide");
                    });
            }



            function saveDateBegin() {


                var d = new Date().getTime();
                var date = <?php echo  ($result && !$result->isValide())?"'".$result->getDateBegin()."'":"d" ?>;
                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/validate-qcm.php",
                    {
                        type: "start",
                        date: date,
                        quizId: <?php echo  $id ?>
                    }
                    //Si y pas d'erreur:
                    , function (data) {
                        if (trimStr(data) === "true") {
                            minuteur(date, <?php echo  $quiz->getDuration() ?>);
                        }


                    }).error(function (data) {

                        //$("body").append(data);

                    }).always(function () {
                        $('.loading').addClass("hide");
                    });

            }



            function writeResult(vide) {
                if (!vide)
                    $(".sp-player-minuteur").html("<?php echo $tr->__("You obtained") ?> " + "<?php echo  ($result!==null)?$result->getNote():""?>%");
                else
                    $(".sp-player-minuteur").html("");
            }








            // pour differencé en cas ou on aura 2 lecteurs dans la meme page...
            sp_owl = $(".sp-player #carousel");

            var optionsOwl = {
                jsonPath: "<?php echo  __ROOT_PLUGIN__2 . $path_json ?>",
                jsonSuccess: setSlides,
                singleItem: true,
                lazyLoad: true,
                pagination: false,
                addClassActive: true,
                rewindNav: false,
                afterMove: afterMoving

            };


            $(".sp-player").on("click", ".btn-begin", function () {
                startQuiz();
                setTimeout(function () {
                    sp_owl.trigger("owl.next");
                }, 1000);



            });


            function startQuiz() {
                sp_owl.owlCarousel(optionsOwl);
                $(".sp-player .btn-begin").addClass('hide');
                $(".sp-player .sp-player-left").removeClass('hide');
                $(".sp-player .buttons-control").removeClass('hide');

            }

            <?php echo  ($result && $result->isValide())?"startQuiz();writeResult(false);":""; ?>




            function setSlides(data) {
                var htmlContent = "";
                var htmlName = "";

                $(".sp-title-quiz").html(data['title']);

                $(".sp-img-profil").attr("href", data['authorLink']).html(data['authorImg']);


                $(".sp-name-profil").attr("href", data['authorLink']).html(data['authorName']);


                for (i = 0; i < data['items'].length; i++) {

                    var nameSlide = data["items"][i]['name'];
                    var contentSlide = data["items"][i]['content'];

                    var selected = (i == 0) ? " selected " : "";
                    if (nameSlide !== "") {
                        htmlName += "<li><a href='#' class='slide-name " + selected + "'>" + nameSlide + "</a></li>";
                    }

                    htmlContent += "<div>" + contentSlide + "</div>";


                }


                $('#section-topline-1 ul').html(htmlName);
                $('#carousel').html(htmlContent);

                <?php
                if($result && !$result->isValide() && $quiz->getDuration()>0)
                  echo "saveDateBegin();";
                  else if(!$result && $quiz->getDuration()>0)
                    echo "saveDateBegin();";
                ?>


            }


            /*-------------------------------------------------------------------
             | Aprés chaque changement dans le slideshow cette fonction s'execute.
             |--------------------------------------------------------------------
             |
             */

            function afterMoving() {
                $('.sp-player .selected').removeClass('selected');
                //var index = $('.sp-player .owl-item.active').index();
                var owl = sp_owl.data('owlCarousel');
                $(".sp-player #section-topline-1 ul li:eq(" + owl.currentItem + ")").find("a").addClass('selected');
                $(".nbr-question").html((owl.itemsAmount - unCountingSlide) + "");
                if ((owl.itemsAmount - unCountingSlide) >= owl.currentItem)
                    $(".current-question").html((owl.currentItem) + "");
                if (owl.currentItem + 1 === owl.itemsAmount)
                    showRater();
                else
                    hideRater();
            }


            $(".sp-player #section-topline-1").on("click", ".slide-name", function (e) {
                e.preventDefault();
                var pos = $(this).parent().index();
                sp_owl.trigger("owl.goTo", pos);
            });

            $("body").keydown(function (e) {
                // left arrow
                if ((e.keyCode || e.which) == 37) {
                    sp_owl.trigger("owl.prev");
                    // do something
                }
                // right arrow
                if ((e.keyCode || e.which) == 39) {
                    sp_owl.trigger("owl.next");
                    // do something
                }
            });


            $(".btn-next").on("click", function () {
                sp_owl.trigger("owl.next");
            });

            $(".btn-prev").on("click", function () {
                sp_owl.trigger("owl.prev");
            });

            $(".sp-player .full-screen").on("click", function () {
                if (supportFullScreen()) {
                    toggleFullScreen(document.getElementById("fullscreen-sp-player"));
                    //$(".sp-player .full-screen").toggleClass("full-screen-active");

                    /* setTimeout(function() {
                     $("#carousel").data('owlCarousel').reinit(optionsOwl);
                     }, 1000);*/


                }
                else
                    alert("Your navigator don't support full screen mode");

            });


            $(".sp-btn-rater").click(toggleRater);


            function toggleRater() {
                $(".sp-rater").animate({
                    height: ($(".sp-rater").height() == "0") ? "100%" : "0"
                }, 500, function () {
                    $(".sp-btn-rater").attr('id', ($(".sp-btn-rater").attr('id') === 'sp-down' ? '' : 'sp-down'));
                    $(".sp-content-rater").toggleClass("hide");
                });

                if($(".sp-rater").height() !== "0")
                {
                    var owl = sp_owl.data('owlCarousel');
                    if (owl.currentItem + 1 === owl.itemsAmount)
                    {
                        sp_owl.trigger("owl.prev");
                    }
                }
            }

            function showRater() {
                $(".sp-rater").animate({
                    height: "100%"
                }, 500, function () {
                    $(".sp-btn-rater").attr('id', 'sp-down');
                    $(".sp-content-rater").removeClass("hide");
                });
            }


            function hideRater() {
                if ($(".sp-btn-rater").attr('id') !== '') {
                    $(".sp-rater").animate({
                        height: "0"
                    }, 500, function () {
                        $(".sp-btn-rater").attr('id', '');
                        $(".sp-content-rater").addClass("hide");
                    });
                }
            }


            [].slice.call(document.querySelectorAll('.sp-player .tabs')).forEach(function (el) {
                new CBPFWTabs(el);
            });



            $('.sp-player').on("click","#sp-validate",function(){
                //var button = $(event.relatedTarget);// Button that triggered the modal

                validate();

            });


            function supportFullScreen(){
                var doc = document.documentElement;
                return ('requestFullscreen' in doc) || ('mozRequestFullScreen' in doc && document.mozFullScreenEnabled) || ('webkitRequestFullScreen' in doc);
            }

            function toggleFullScreen(elem) {
                if (!document.fullscreenElement &&    // alternative standard method
                    !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {  // current working methods
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.msRequestFullscreen) {
                        elem.msRequestFullscreen();
                    } else if (elem.mozRequestFullScreen) {
                        elem.mozRequestFullScreen();
                    } else if (elem.webkitRequestFullscreen) {
                        elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.msExitFullscreen) {
                        document.msExitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        document.webkitExitFullscreen();
                    }
                }
            }





            //le minuteur
            var $hours = $(".sp-player #hours");
            var $minutes = $(".sp-player #minutes");
            var $seconds = $(".sp-player #seconds");
            var $minuteur = $(".sp-player .sp-player-minuteur");

            function minuteur(dateTime, time) {
                var launch = dateTime / 1000 + time * 60;
                setDate(launch);
            }

            function setDate(launch) {
                var now = new Date();

                var s = launch - now.getTime() / 1000;


                if (s > 0) {
                    if (s < 31) {
                        $minuteur.addClass("sp-player-animate-minuteur");
                    }
                    var h = Math.floor(s / 3600);
                    $hours.html(h + "");

                    s -= h * 3600;


                    var m = Math.floor(s / 60);
                    $minutes.html(m + "");

                    s -= m * 60;


                    s = Math.floor(s);
                    $seconds.html(s + "");


                    setTimeout(function () {
                        setDate(launch)
                    }, 1000);
                }
                else {
                    setTimeout(function () {

                        $minuteur.removeClass("sp-player-animate-minuteur");

                        var owl = sp_owl.data('owlCarousel');

                        sp_owl.trigger("owl.goTo", owl.itemsAmount - 2);


                        validate();

                    }, 2000);

                }


            }


            <?php
            if($btn_buddypress_share!=="")
            {
            ?>


            $('.sp-player').on("click",'.btn-buddypress',function(){


                //Getting the variable's value from a link
                var loginBox = ".sp-player #login-box";

                    //Fade in the Popup
                    $(loginBox).fadeIn(300);

                    $(loginBox).find(".loading").removeClass("hide");





                    // Add the mask to body
                    $('.sp-player').append('<div id="mask"></div>');
                    $('#mask').fadeIn(300);


                //Envoyer la requête ajax...
                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/share.controller.php",
                    {
                        type : "get-groups",
                        quizId: <?php echo $id ?>
                    }
                    //Si y pas d'erreur:
                    ,function(data){
                        //console.log(data);
                        if(trimStr(data.result) === "true") {
                            $(".sp-player .content").html(data.content);

                        }
                        else{

                            console.log(data);
                        }

                    },"json").error(function(data){
                        console.log(data.responseText);



                    }).always(function(){
                        $(loginBox).find('.loading').addClass("hide");
                    });


                    return false;
                });

// When clicking on the button close or the mask layer the popup closed
                $('.sp-player').on('click','.close, #mask', function() {
                    $('#mask , .login-popup').fadeOut(300 , function() {
                        $('#mask').remove();
                    });
                    return false;
                });



            $('.sp-player').on("change","#login-box input:checkbox",function(){
                checked = $(this);
                if((checked.val() === '0') && (!checked.is(':checked')))
                {
                    $('#login-box input:checkbox').removeAttr("checked");
                }

                if((checked.val() !== '0') && (checked.is(':checked')))
                {

                    $("#login-box input:checkbox[value='0']").prop('checked', true);
                }

            });


            $('.sp-player').on("click","#login-box button",function(){

                var values = [];
                if(!$('#login-box input[type=checkbox]:checked').length)
                {
                   alert("<?php echo $tr->__("Please select at least one !") ?>") ;
                }
                else
                {
                    $("#login-box input[type=checkbox]:checked").each(function()
                    {
                        values.push( $(this).val());
                    });

                    //Getting the variable's value from a link
                    var loginBox = ".sp-player #login-box";


                    $(loginBox).find('.loading').removeClass("hide");

                    var comment = $(loginBox).find("textarea[name=comment]").val();
                    var type_share = $(loginBox).find("input[type='radio']:checked").val();

                    console.log(type_share);
                    //Envoyer la requête ajax...
                    $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/share.controller.php",
                        {
                            type : "share-groups",
                            quizId: <?php echo $id ?>,
                            groups : values,
                            comment : comment,
                            type_share : type_share
                        }
                        //Si y pas d'erreur:
                        ,function(data){
                            //console.log(data);
                            if(trimStr(data) === "true") {
                                $(".sp-player .content").html("<?php echo "Success !!" ?>");
                                setTimeout(function () {
                                    $('#mask , .login-popup').fadeOut(300 , function() {
                                        $('#mask').remove();
                                    });
                                },2000);


                            }
                            else{
                                $(".sp-player .content").html("Error !");

                            }

                        }).error(function(data){
                            console.log(data.responseText);



                        }).always(function(){
                            $(loginBox).find('.loading').addClass("hide");
                        });



                }

            });


            <?php
            }
             ?>


            //Visite
            $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/visite.php",
                {
                    id: <?php echo $quiz->getId() ?>
                });





            //Reseaux Sociaux

            $('.sp-player').on("click",'.btn-share', function () {
                $('.div-share').toggle(100);
            });


            <?php if($btn_social_share !== "") : ?>
            function openDialogSocial(url){
                window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=700');
            }







            $(".btn-twitter").on("click",function(e){
                e.preventDefault();
                openDialogSocial("https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>");
            });


            $(".btn-google").on("click",function(e){
                e.preventDefault();
                openDialogSocial("https://plus.google.com/share?url=<?php the_permalink(); ?>&hl=fr");
            });


            $(".btn-facebook").on("click",function(e){
                e.preventDefault();
                openDialogSocial("https://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>");
            });

            $(".btn-linkedin").on("click",function(e){
                e.preventDefault();
                openDialogSocial("https://www.linkedin.com/shareArticle?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>");
            });

            <?php endif; ?>

        });
    })(jQuery);


</script>




