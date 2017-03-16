<?php


global $tr;

require_once  __ROOT_PLUGIN__ ."Views/includeCSS.php";
require_once  __ROOT_PLUGIN__ ."Views/inc/html/help.php";



?>

<input type="hidden" name="quiz[id]" value="<?php echo  $_GET['id'] ?>"/>

<style>

    .modal-body
    {
        background: #FEFEFE;
    }

    .sp-qcm
    {
        background: #FFF;
        box-shadow: 0px 0px 3px #444;
        margin-bottom: 20px;
        padding: 5px;
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


    .sp-qcm table
    {
        margin: 0;
        padding: 0;
        font-size: 12px;
        width : 100%;
        background: #EEE;
    }

    .sp-qcm table td
    {
        padding: 0;
        margin: 0;
        border: none;
        line-height: 1.34;

    }

    .sp-qcm > p
    {
        font-size: 1.3em;

    }

    .sp-qcm ul
    {
        list-style-type: none;
    }

    .sp-qcm ul > li
    {
        position: relative;
        padding: 10px;
        overflow: hidden;
        border-radius: 5px;
        margin-top: 5px;
        background: #EEE;
    }



    .sp-qcm li.sp-checked
    {
        background: #Ded777;
    }


    .sp-qcm table td:first-child
    {
        padding: 5px 5px 0 0;
        width: 5%;
    }

    .sp-qcm table td:nth-child(3)
    {
        width: 5%;
    }
    .sp-player .sp-qcm table td:nth-child(2)
    {
        width: 90%;
    }


    .sp-qcm li label
    {
        text-align: left;
        font-size: 1.1em;
        vertical-align: middle;
    }

    .sp-qcm li.false
    {
        box-shadow:inset 0px 0px 3px #E92836;
    }
    .sp-qcm li.true
    {
        box-shadow:inset 0px 0px 3px #009900;
    }

    .red,
    .green
    {
        font-weight: bold;
        font-size: 1.2em;
    }
    .red
    {
        color: #ac2925;

    }

    .green
    {
        color: #3e8f3e;
    }



</style>

<h1>
    <?php $tr->_e("Quiz Results"); ?>


</h1>

<div class="container-fluid">

    <?php
    sp_display_link_help();
    ?>


    <div class="row">

        <div class="col-md-12">

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th><?php $tr->_e('User'); ?></th>
                    <th><?php $tr->_e('Percentage'); ?></th>
                    <th><?php $tr->_e('Ratio'); ?></th>
                    <th><?php $tr->_e('Date'); ?></th>
                    <th><?php $tr->_e('Details'); ?></th>
                </tr>
                </thead>
                <tbody>

                <?php
                $__results = $managerResult->getResultByQuiz($_GET['id']);
                if(empty($__results))
                {
                    echo "<tr><td colspan='7'>". $tr->__('No results') ."</td></tr>";
                }
                else {
                foreach ($__results as $row) {
                    $user = new StudyPressUserWP($row->getUserId());
                    ?>
                    <tr>
                        <td> <?php echo  $user->displayName() ?> </a></td>
                        <td class="<?php echo  ($row->getNote()>=50)?"green":"red"?>"> <?php echo  $row->getNote() ?>%</td>
                        <td> <?php echo  $row->getNbrCorrectResponse() ."/". $row->getNbrQuestions() ?></td>
                        <td> <?php echo  $row->getDateResult() ?></td>
                        <td>
                            <a id="get-responses" data-id ="<?php echo  $user->id() ?>" href="#">
                                <span class="glyphicon glyphicon-new-window"  data-toggle="modal" data-target="#myModal" aria-hidden="true" title="Afficher"></span>
                            </a>
                        </td>
                    </tr>

                <?php
                }


                ?>
                </tbody>

                <?php
                }
                ?>

            </table>

    </div>

</div>
</div>


<!-- Modal d'ajout de leçon  -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="loading hide"></div>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-user" id="myModalLabel"></h4>
                <h4 class="modal-pourcentage" ></h4>
                <h4 class="modal-quiz" ></h4>
            </div>
            <div class="modal-body">
                    <!-- ICI les reponses -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo  $tr->__("Close") ?></button>

            </div>
        </div>
    </div>
</div>


<?php
$content = "<p>This page displays the detailed result of the selected quiz for each user that has taken the quiz.</p>";
$msg ="This feature is available for users who have <b>administrator</b>, <b>editor</b> or <b>author</b> rights.";
sp_display_modal_help($content,$msg);
?>





<script>

    (function($) {
        $(document).ready(function () {


            var modal = $('#myModal');
            var modalBody = modal.find('.modal-body');
            var modalUser = modal.find('.modal-user');
            var modalPourcentage = modal.find('.modal-pourcentage');
            var modalQuiz = modal.find('.modal-quiz');


            function reinitialiserModal() {
                //Reinitialiser

                modalBody.html("");
                modalUser.html("");
                modalPourcentage.html("");
                modalQuiz.html("");
            }


            $('.table').on("click", '#get-responses', function (e) {
                e.preventDefault();
                reinitialiserModal();
                id_slide = $(this).data("id");
                var quizId = $('input[name="quiz[id]"]').val();
                var userId = $(this).data("id");

                getResponses(quizId, userId);

            });


            function getResponses(quizId, userId) {
                $(".loading").removeClass('hide');

                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/resultQuiz.controller.php",
                    {
                        type: "get-responses",
                        quizId: quizId,
                        userId: userId
                    }
                    //Si y pas d'erreur:
                    , function (data) {

                        if (trimStr(data.result) === "true") {
                            modalQuiz.html(data.quiz);
                            modalPourcentage.html(data.pourcentage);
                            modalUser.html(data.user);
                            modalBody.html(data.body);

                        }


                    }, 'json').error(function () {

                        modalBody.html("Error !! Try Again").addClass("red");

                    }).always(function () {
                        $(".loading").addClass('hide');
                    });
            }


            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }


        })
    })(jQuery);


</script>