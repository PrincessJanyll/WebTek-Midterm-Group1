/**
 * Created by Salim on 17/04/2015.
 */
(function($) {
    $(document).ready(function() {


        //Les requetes ajax ...
        var ajaxGet = new XMLHttpRequest();
        var ajaxAdd = new XMLHttpRequest();
        var ajaxLoad = new XMLHttpRequest();


        var pictureUrl = sp_picture_url;


        $('#picture').popover({
            placement : 'bottom',
            content : function() {
                return "<img src='" + pictureUrl + "' width='100' height='100'/>";
            },
            html  : true
        });



        function addslashes(str) {
            return str.replace(/\"/g, " ");
        }

        reload_slides();

        var type_modal_sp = "add";
        var modal = $('#myModal');
        var id_question;
        var contentBody = modal.find(".modal-body").html();



        $("#sortable-question").sortable({
            placeholder: "ui-sortable-placeholder"
        });
        $("#sortable-question").disableSelection();
        $("#sortable-question").on("sortupdate", function (event, ui) {
            $("#update-order").prop("disabled", false);

        });


        function reinitialiserModal() {

            ajaxAdd.abort();
            ajaxGet.abort();
            ajaxLoad.abort();

            //Reinitialiser
            var alert = modal.find(".alert");
            alert.find("p").html("");
            alert.addClass("hide");
            modal.find('input[type=text]').val("");
            modal.find(" table tbody input:checkbox").removeAttr('checked');
            modal.find(".modal-body").html(contentBody);
            modal.find(".back").css('display','none');
            $(".save-new-question").css("display","none");


            //Supprimer les lignes ajoutees
            modal.find(" table tbody tr:gt(1)").remove();
        }

        $("#add-new-quiz").on("click", function () {

            var alert = modal.find(".alert");
            //Creation
            type_modal_sp = "add";
            modal.find('.modal-title').text(sp_tr_modal_new_quest);

            reinitialiserModal();


        });


        function trimStr(str) {
            return str.replace(/^\s+|\s+$/gm, '');
        }


        modal.on("click",".save-new-question", function () {
            //var button = $(event.relatedTarget);// Button that triggered the modal
            var alert = modal.find(".alert");


            $('.loading').removeClass("hide");

            //Reinitialiser
            alert.find("p").html("");
            alert.addClass("hide");

            //mettre le bouton en loading...
            var btn = $(this).button('loading');


            // recuperer les donnees...
            var typeQcm = modal.find('input[name=type-qcm]').val();
            var question = modal.find('input[name=question]').val();
            var id_quiz = $('input[name="quiz[id]"]').val();
            var checked = [];
            var value = [];

            modal.find("table tbody tr").each(function () {
                checked.push(($(this).find("input[name='true[]']").is(':checked')) ? true : false);
                value.push($(this).find("input[name='prop[]']").val());
            });


            //Envoyer la requete ajax...
            ajaxAdd = $.ajax({
                url : sp_root_plugin+"controllers/question.controller.php",
                type: "POST",
                timeout: 10000,
                data: {
                    type: type_modal_sp + "-question",
                    question: question,
                    id_quiz: id_quiz,
                    id_question: id_question,
                    value: value,
                    checked: checked,
                    typeQcm : typeQcm
                },
                success: function (data) {
                    //console.log(data);
                    if (trimStr(data) === "true") {
                        console.log(data);
                        reload_slides();
                        modal.modal('hide');

                    }
                    else {
                        alert.removeClass("hide");
                        alert.find("p").append(data);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        alert.removeClass("hide");
                        alert.find("p").append(sp_tr_modal_time_out);
                    }

                    alert.removeClass("hide");
                    alert.find("p").append(jqXHR.responseText);
                },
                complete: function () {
                    btn.button('reset');
                    $('.loading').addClass("hide");
                }
            }); //end ajax

        });


        $("#update-order").on("click", function () {
            var $btn = $(this);
            $btn.button('loading');
            var order = [];
            $("#sortable-question li").each(function (index, element) {
                order[index] = $(element).data("id");
            });
            $.post(sp_root_plugin+"controllers/question.controller.php",
                {
                    type: "order-question",
                    order: order

                }
                //Si y pas d'erreur:
                , function (data) {
                    console.log(data);
                    if (trimStr(data) === "true") {
                        reload_slides();
                    }
                }
            ).error(function () {

                }).always(function () {
                    $btn.button('reset');


                });

            return false;

        });


        $('#sortable-question').on("click", ".glyphicon-remove", function () {
            if (confirm(sp_tr_modal_time_out)) {
                var id_question = $(this).data("id");
                var id_quiz = $('input[name="quiz[id]"]').val();

                $.post(sp_root_plugin+"controllers/question.controller.php",
                    {
                        type: "remove-question",
                        id_question: id_question,
                        id_quiz: id_quiz
                    }
                    //Si y pas d'erreur:
                    , function (data) {
                        console.log(data);
                        if (trimStr(data) === "true") {
                            reload_slides();
                        }
                    }
                ).error(function () {

                    })
            }
            return false;

        });


        $('#sortable-question').on("click", ".glyphicon-pencil", function () {
            type_modal_sp = "update";
            modal.find('.modal-title').text(sp_tr_modal_edit_quest);
            reinitialiserModal();

            var type = $(this).data("type");
            loadPropositions(type,$(this));

        });


        function getContentSlide(id_question, id_quiz) {
            $(".loading").removeClass('hide');

            var alert = modal.find(".alert");


            ajaxGet = $.ajax({
                url: sp_root_plugin+"controllers/question.controller.php",
                type: "POST",
                timeout: 10000,
                dataType: "json",
                data: {
                    type: "get-question",
                    id_question: id_question,
                    id_quiz: id_quiz
                },
                success: function (data) {
                    if (trimStr(data.result) === "true") {
                        /**************************************************************/
                        modal.find('input[name=question]').val(data.content);

                        for (var i = 0; i < data.propositions.length; ++i) {
                            if (i > 1) addNewRow();

                            var tr = modal.find("table tbody tr:nth-child(" + (i + 1) + ")");

                            tr.find("input[name='prop[]']").val(data.propositions[i].content);

                            if (trimStr(data.propositions[i].true) === "true")
                                tr.find("input[name='true[]']").prop('checked', true);

                        }


                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        alert.removeClass("hide");
                        alert.find("p").append(sp_tr_modal_time_out);
                        setTimeout(function () {
                            modal.modal('hide');
                        }, 3000);
                    }

                },
                complete: function () {
                    $(".loading").addClass('hide');
                }
            }); //end ajax
        }


        function reload_slides() {
            var ul = $("#sortable-question");
            ul.css('background', "url('"+sp_root_plugin+"images/loading.gif') no-repeat 50% 50%");
            ul.html("");

            var id_quiz = $('input[name="quiz[id]"]').val();
            //Envoyer la requete ajax...
            $.post(sp_root_plugin+"Views/reload/questions.php",
                {
                    id_quiz: id_quiz
                }

                //Si y pas d'erreur:
                , function (data) {

                    ul.html(data);

                }
            ).error(function (data) {


                }).always(function () {
                    ul.css('background', "#FFF");
                    $("#update-order").prop("disabled", true);
                });
        };


        //Ajouter une note
        $("#add-new-note").on("click", function () {
            var note = $("input[name=note]").val();
            var id_quiz = $('input[name="quiz[id]"]').val();
            //@TODO Amelioration d'ajout de notes (escape,htmlSpecialChars....).
            if (trimStr(note) != "") {
                $("#sortable-note").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-note'> <span class='float-left' title=\"" + addslashes(note) + "\">" + note.substring(0, 35) + "...</span><a href=''><span class='glyphicon glyphicon-remove float-right delete-note' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='quiz[note][]' value=\"" + addslashes(note) + "\"/></li>");

                $("input[name=note]").val("");
            }

        });

        $("#sortable-note").on("click", "li .delete-note", function (e) {
            e.preventDefault();
            if(confirm(sp_tr_alert_delete_tag))
            {
                $(this).parent().parent().remove();
            }



        });


        //Ajouter glossaire
        $("#add-new-glossary").on("click", function () {
            var name = $("input[name=glossary-name]").val();
            var desc = $("input[name=glossary-desc]").val();

            var id_quiz = $('input[name="quiz[id]"]').val();

            if ((trimStr(name) != "") && (trimStr(desc) != "")) {
                $("#sortable-glossary").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-glossary'> " +
                "               <span class='float-left' title=\"" + addslashes(name + " : " + desc) + "\">" + ("<b>" + name + "</b>" + " : " + desc).substr(0, 35) + "...</span>" +
                "<a href=''><span class='glyphicon glyphicon-remove float-right delete-glossary' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='quiz[glossary][name][]' value=\"" + addslashes(name) + "\" />" +
                "<input type='hidden' name='quiz[glossary][desc][]' value=\"" + addslashes(desc) + "\" /> </li>");

                $("input[name=glossary-name]").val("");
                $("input[name=glossary-desc]").val("");
            }

        });

        $("#sortable-glossary").on("click", "li .delete-glossary", function (e) {
            e.preventDefault();
            if(confirm(sp_tr_alert_delete_glossary))
            {
                $(this).parent().parent().remove();
            }


        });

        $('.select-picture').click(function (e) {
            var $el = $(this).parent();
            e.preventDefault();
            console.log('test');
            var uploader = wp.media({
                title: sp_tr_tiny_upload_img,
                button: {
                    text: sp_tr_tiny_select_img
                },
                library: {
                    type: 'image'
                },
                multiple: false
            })
                .on('select', function () {
                    var selection = uploader.state().get('selection');
                    var attachment = selection.first().toJSON();
                    $("input[name='quiz[pictureurl]']").val(attachment.id);
                    $(document.getElementById('picture'), $el).val(attachment.url);
                    pictureUrl = attachment.url;

                })
                .open();
        })


        modal.on("click","#add-new-proposition", function () {
            //console.log("msdkf");
            addNewRow();
        });


        modal.on("click", ("#remove-proposition"), function () {
            $(this).parent().parent().fadeOut(300, function () {
                $(this).remove()
            });
        });


        function addNewRow() {
            var typeQcm = modal.find(".modal-body input[name='type-qcm']").val();
            if(typeQcm==="multiple" || typeQcm==="unique")
            {
                var type = (typeQcm ==="multiple")?"checkbox":"radio";
                modal.find('.table tbody tr:last').after("<tr><td><input type='"+type+"' name='true[]'/></td><td><input type='text' class='form-control' id='prop' name='prop[]'  /><button type='button' class='close' id='remove-proposition'  aria-label='Close'><span aria-hidden='true'>&times;</span></button></td></tr>");
            }

        }


        modal.on("click",".choice-type-qcm",function(e){
            e.preventDefault();
            var type = $(this).data("value");
            modal.find('.back').css('display','inline-block');
            loadPropositions(type,false);
        });





        function loadPropositions(type,getContent)
        {
            var alert = modal.find(".alert");

            $('.loading').removeClass("hide");
            ajaxLoad  = $.ajax({
                url: sp_root_plugin+"Views/model/index.php",
                type: "POST",
                timeout: 10000,
                data: {
                    type: type
                },
                success: function (data) {
                    modal.find(".modal-body").html(data);
                    $(".save-new-question").css("display","inline-block");
                    if(getContent!==false)
                    {
                        id_question = getContent.data("id");
                        var id_quiz = $('input[name="quiz[id]"]').val();
                        getContentSlide(id_question, id_quiz);
                    }

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus === 'timeout') {
                        alert.removeClass("hide");
                        alert.find("p").append(sp_tr_modal_time_out);
                        setTimeout(function () {
                            modal.modal('hide');
                        }, 3000);
                    }

                },
                complete: function () {
                    if(getContent === false)
                        $(".loading").addClass('hide');
                }
            }); //end ajax
        }

        modal.on("click",".back",function(){
            reinitialiserModal();
        })

    });
})(jQuery);