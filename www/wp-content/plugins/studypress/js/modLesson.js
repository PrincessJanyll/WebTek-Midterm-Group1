/**
 * Created by Salim on 17/04/2015.
 */
(function($) {
    $(document).ready(function(){

        //Les requetes ajax ...
        var ajaxGet = new XMLHttpRequest();
        var ajaxAdd = new XMLHttpRequest();


        var pictureUrl = sp_picture_url;


        $('#picture').popover({
            placement : 'bottom',
            content : function() {
                return "<img src='" + pictureUrl + "' width='100' height='100'/>";
            },
            html  : true
        });



        function addslashes(str) {
            return str.replace(/\"/g," ");
        }


        reload_slides();

        var type_modal_sp = "add";
        var modal = $('#myModal');
        var id_slide;
        var alert = modal.find(".alert");


        $( "#sortable-slide" ).sortable({
            placeholder: "ui-sortable-placeholder"
        });
        $( "#sortable-slide" ).disableSelection();
        $( "#sortable-slide" ).on( "sortupdate", function( event, ui ) {
            $("#update-order").prop("disabled", false);

        } );


        function reinitialiserModal(){
            //Reinitialiser
            ajaxAdd.abort();
            ajaxGet.abort();

            $('.loading').addClass("hide");

            alert.find("p").html("");
            alert.addClass("hide");
            modal.find('input[name=name]').val("");
            tmce_setContent("",'content-slide','content-slide');

        }

        $("#add-new-slide").on("click",function(){

            //Creation
            type_modal_sp ="add";
            modal.find('.modal-title').text(sp_tr_modal_new_slide);

            reinitialiserModal();


        });



        function trimStr(str) {
            return str.replace(/^\s+|\s+$/gm,'');
        }




           //add or update slide
        $('#myModal .btn-primary').on("click",function() {
            //var button = $(event.relatedTarget);// Button that triggered the modal

            $('.loading').removeClass("hide");

            //Reinitialiser
            alert.find("p").html("");
            alert.addClass("hide");

            //mettre le bouton en loading...
            var btn = $(this).attr('disabled', 'disabled');


            // recuperer les donnees...
            var name = modal.find('input[name=name]').val();
            var content = tmce_getContent('content-slide','content-slide');//modal.find('textarea[name=content-slide]').val();
            var id_lesson = $('input[name="lesson[id]"]').val();


            //Envoyer la requete ajax...
            ajaxAdd = $.ajax({
                url: sp_root_plugin+"controllers/slide.controller.php",
                type: "POST",
                timeout: 10000,
                data: {
                    type: type_modal_sp + "-slide",
                    name: name,
                    content: content,
                    id_lesson: id_lesson,
                    id_slide: id_slide
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
                    btn.removeAttr('disabled');
                    $('.loading').addClass("hide");
                }
            }); //end ajax

        });

        $("#update-order").on("click", function ()
        {
            var $btn = $(this);
            $btn.attr('disabled','disabled');
            var order =[];
            $("#sortable-slide li" ).each(function( index,element ) {
                order[index]  = $(element).data("id");
                console.log(index);
            });
            $.post( sp_root_plugin+"controllers/slide.controller.php",
                {
                    type: "order-slide",
                    order: order

                }
                //Si y pas d'erreur:
                , function (data) {
                    console.log(data);
                    if(trimStr(data) === "true") {
                        reload_slides();
                    }
                }
            ).error(function(){

                }).always(function(){
                    $btn.removeAttr('disabled');


                });

            return false;

        });


        $('#sortable-slide').on("click",".glyphicon-remove",function(){
            if(confirm(sp_tr_alert_delete_slide)) {
                var id_slide = $(this).data("id");
                var id_lesson = $('input[name="lesson[id]"]').val();

                $.post(sp_root_plugin+"controllers/slide.controller.php",
                    {
                        type: "remove-slide",
                        id_slide: id_slide,
                        id_lesson: id_lesson
                    }
                    //Si y pas d'erreur:
                    , function (data) {
                        console.log(data);
                        if(trimStr(data) === "true") {
                            reload_slides();
                        }
                    }
                ).error(function(){

                    })
            }
            return false;

        });






        $('#sortable-slide').on("click",".glyphicon-pencil",function(){
            type_modal_sp = "update";
            modal.find('.modal-title').text(sp_tr_modal_edit_slide);
            reinitialiserModal();
            id_slide = $(this).data("id");
            var id_lesson = $('input[name="lesson[id]"]').val();
            getContentSlide(id_slide,id_lesson);

        });


        function getContentSlide(id_slide,id_lesson){
            $(".loading").removeClass('hide');

            ajaxGet = $.ajax({
                url: sp_root_plugin+"controllers/slide.controller.php",
                type: "POST",
                timeout: 10000,
                dataType: "json",
                data: {
                    type: "get-slide",
                    id_slide: id_slide,
                    id_lesson: id_lesson
                },
                success: function (data) {

                    if (trimStr(data.result) === "true") {
                        modal.find('input[name=name]').val(data.nameSlide);
                        tmce_setContent(data.contentSlide,'content-slide','content-slide');
                        
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


        function reload_slides(){
            var ul =$("#sortable-slide");
            ul.css('background',"url('"+sp_root_plugin+"images/loading.gif') no-repeat 50% 50%");
            ul.html("");

            var id_lesson = $('input[name="lesson[id]"]').val();
            //Envoyer la requete ajax...
            $.post(sp_root_plugin+"Views/reload/slides.php",
                {
                    id_lesson: id_lesson
                }

                //Si y pas d'erreur:
                ,function(data){

                    ul.html(data);

                }


            ).error(function(data){



                }).always(function(){
                    ul.css('background',"#FFF");
                    $("#update-order").prop("disabled", true);
                });
        };




        //Ajouter une note
        $("#add-new-note").on("click",function(){
            var note = $("input[name=note]").val();
            var id_lesson = $('input[name="lesson[id]"]').val();
            //@TODO Amelioration d'ajout de notes (escape,htmlSpecialChars....).
            if(trimStr(note) != "")
            {
                $("#sortable-note").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-note'> <span class='float-left' title=\""+addslashes(note)+"\">"+note.substring(0,35)+"...</span><a href=''><span class='glyphicon glyphicon-remove float-right delete-note' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='lesson[note][]' value=\""+addslashes(note)+"\"/></li>");

                $("input[name=note]").val("");
            }

        });

        $("#sortable-note").on("click","li .delete-note",function(e){
            e.preventDefault();
            if(confirm(sp_tr_alert_delete_tag))
            {
                $(this).parent().parent().remove();
            }


        });



        //Ajouter glossaire
        $("#add-new-glossary").on("click",function(){
            var name = $("input[name=glossary-name]").val();
            var desc = $("input[name=glossary-desc]").val();

            var id_lesson = $('input[name="lesson[id]"]').val();

            if((trimStr(name) != "") &&(trimStr(desc) != ""))
            {
                $("#sortable-glossary").append("<li id='li-non-sortable' class='ui-state-default btn btn-default sp-glossary'> " +
                "               <span class='float-left' title=\""+addslashes(name+" : "+desc)+"\">"+("<b>"+name+"</b>"+" : "+desc).substr(0,35)+"...</span>" +
                "<a href=''><span class='glyphicon glyphicon-remove float-right delete-glossary' id='red'  aria-hidden='true'></span></a>" +
                "<input type='hidden' name='lesson[glossary][name][]' value=\""+addslashes(name)+"\" />" +
                "<input type='hidden' name='lesson[glossary][desc][]' value=\""+addslashes(desc)+"\" /> </li>");

                $("input[name=glossary-name]").val("");
                $("input[name=glossary-desc]").val("");
            }

        });

        $("#sortable-glossary").on("click","li .delete-glossary",function(e){
            e.preventDefault();
            if(confirm(sp_tr_alert_delete_glossary))
            {
                $(this).parent().parent().remove();
            }


        });

        $('.select-picture').click(function(e){
            var $el=$(this).parent();
            e.preventDefault();
            var uploader=wp.media({
                title : sp_tr_tiny_upload_img,
                button : {
                    text: sp_tr_tiny_select_img
                },
                library :{
                    type : 'image'
                },
                multiple: false
            })
                .on('select',function(){
                    var selection=uploader.state().get('selection');
                    var attachment=selection.first().toJSON();
                    $("input[name='lesson[pictureurl]']").val(attachment.id);
                    $(document.getElementById('picture'),$el).val(attachment.url);
                    pictureUrl = attachment.url;


                })
                .open();
        })


        
        
        function tmce_getContent(editor_id, textarea_id) {
            if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
            if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
  
            if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
                return tinyMCE.get(editor_id).getContent();
            }else{
                return jQuery('#'+textarea_id).val();
            }
        }
 
        function tmce_setContent(content, editor_id, textarea_id) {
          if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
          if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;

          if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
            return tinyMCE.get(editor_id).setContent(content);
          }else{
            return jQuery('#'+textarea_id).val(content);
          }
        }

        function tmce_focus(editor_id, textarea_id) {
          if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
          if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;

          if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
            return tinyMCE.get(editor_id).focus();
          }else{
            return jQuery('#'+textarea_id).focus();
          }
        }
    });

})(jQuery);