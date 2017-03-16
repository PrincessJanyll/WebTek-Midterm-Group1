<h1><?php $tr->__('Domains'); ?></h1>

<div class="container-fluid">

    <div class="row">



        <div class="col-md-8">
            <h3><?php $tr->_e('All Domains'); ?></h3>
            <div class="alert alert-danger" role="alert" <?php echo  ($error_domain_remove=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_domain_remove ?> </div>
        <form action="" method="post">

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?php $tr->_e('Nom'); ?></th>
                    <th><?php $tr->_e('Description'); ?></th>
                </tr>
                </thead>
                <tbody>

                <?php
                $__domains = $managerDomain->getAll();
                if(empty($__domains))
                {
                    echo "<tr><td colspan='4'>". $tr->__('No domains') ."</td></tr>";
                }
                else {
                foreach ($__domains as $row) {
                    ?>
                    <tr>
                        <td><input type='checkbox' name="id[]" value='<?php echo  $row->getId() ?>'/></td>
                        <td><a class="update" href="" data-toggle="modal" data-target="#myModal" data-id="<?php echo  $row->getId() ?>"><?php echo  $row->getName() ?> </a></td>
                        <td class="description"> <?php echo  $row->getNiceDescription() ?></td>
                    </tr>

                    <?php



                }


                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="4">
                        <button type="submit" name="remove" <?php echo  $confirm ?> class="btn btn-danger"><?php $tr->_e('Delete'); ?></button>
                    </td>
                </tr>
                </tfoot>
                <?php
                }
                ?>

            </table>
        </form>

    </div>
    <div class="col-md-4">



        <h3><?php $tr->_e('New Domain'); ?></h3>
        <form method="post" action="">
            <div class="panel panel-default">

                <div class="panel-body">
                    <div class="alert alert-danger" role="alert" <?php echo  ($error_domain_add=='')?'style=\'display:none\'':'' ?>"> <?php echo  $error_domain_add ?>
                </div>
                <div class="form-group">
                    <label for="name"><?php $tr->_e('Name'); ?>*</label>
                    <input type="text" class="form-control" id="name" name="name" required="required" />
                </div>

                <div class="form-group">
                    <label for="desc"><?php $tr->_e('Description'); ?> <small>(<?php $tr->_e('Optional'); ?>)</small></label>
                    <textarea class="form-control" rows="3" id="desc" name="desc"></textarea>
                </div>
                </div>
                <div class="panel-footer">
                    <button type="submit" name="add" class="btn btn-primary center-block"><?php $tr->_e('Validate'); ?></button>

                </div>
                </form>

    </div>



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?php $tr->_e("Change domain"); ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="loading hide"></div>

                    <div class="alert alert-danger alert-dismissible hide" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <p><!-- Le message d'erreur --></p>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="id" value=""/>
                        <label for="name"><?php $tr->_e("Name"); ?></label>
                        <input type="text" autocomplete="off" class="form-control" id="name" name="name" required="required" />
                    </div>


                    <div class="form-group">
                        <label for="desc"><?php $tr->_e("Description"); ?></label>
                        <textarea class="form-control" rows="3" id="desc" name="desc"></textarea>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="button" data-loading-text="<?php $tr->_e("Loading..."); ?>" class="btn btn-primary"><?php $tr->_e("Save"); ?></button>
                </div>
            </div>
        </div>
    </div>



</div>
</div>



<?php
$contentDomain = "You can use domains to activate multi criteria ratings of lessons and quizzes. If no domain is defined, web site users can only rate the quality of the lesson or the quiz.";
$msgDomainAccess="This feature is available for users who have <b>administrator</b> rights.";
sp_display_modal_help($contentDomain,$msgDomainAccess,"modal-help-domain");





?>

</div>

<script>

    (function($) {
        $(document).ready(function() {

            var trSelected;

            function trimStr(str) {
                return str.replace(/^\s+|\s+$/gm, '');
            }

            var modal = $("#myModal");
            var alert = modal.find(".alert");

            $(".update").on("click", function (e) {
                trSelected = $(this);
                e.preventDefault();
                var id = $(this).data('id');
                var name = $(this).html();
                var desc = $(this).parent().parent().find(".description").html();


                modal.find("input[name=name]").val(name);
                modal.find("input[name=id]").val(id);
                modal.find("textarea[name=desc]").val(desc);


            });


            $('#myModal .btn-primary').on("click", function () {
               
                $('.loading').removeClass("hide");

                
                alert.find("p").html("");
                alert.addClass("hide");

                var btn = $(this).button('loading');


                var name = modal.find('input[name=name]').val();
                var desc = modal.find('textarea[name=desc]').val();
                var id = modal.find('input[name=id]').val();


                $.post("<?php echo  __ROOT_PLUGIN__2 ?>controllers/domain.controller.php",
                    {
                        type: "update-ajax",
                        name: name,
                        desc: desc,
                        id: id
                    }
                  
                    , function (data) {
                      
                        if (trimStr(data) === "true") {
                            modal.modal('hide');
                            trSelected.html(name);
                            trSelected.parent().parent().find(".description").html(desc);


                        }
                        else {

                            alert.removeClass("hide");
                            alert.find("p").append(data);
                        }

                    }).error(function (data) {

                        alert.removeClass("hide");
                        alert.find("p").append(data.responseText);


                    }).always(function () {
                        btn.button('reset');
                        $('.loading').addClass("hide");
                    });


            });
        });
    })(jQuery);

</script>