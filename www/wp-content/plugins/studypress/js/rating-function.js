/**
 * Created by Salim on 08/03/2015.
 */



    function requestRate($this, rate, path) {

    (function($) {
        var domain = parseInt($this.attr('data-domain')), // get the average of all rates
            user = parseInt($this.attr('data-user')), // get the average of all rates
            idBox = parseInt($this.attr('data-id')), // get the id of the box
            $thisItem = $this.parent();// Width of the color Container

        $thisItem.find('.serverResponse p').html("loading...");


        $.post(path, {
                idBox: idBox,
                rate: rate,
                user: user,
                domain: domain,
                action: 'rating'
            },
            function (data) {
                if (!data.error) {
                    /** ONLY FOR THE DEMO, YOU CAN REMOVE THIS CODE **/
                    $thisItem.find('.serverResponse p').html(data.server);
                    /** END ONLY FOR THE DEMO **/


                }
                else {

                    /** ONLY FOR THE DEMO, YOU CAN REMOVE THIS CODE **/
                    $thisItem.find('.serverResponse p').html(data.server);
                    /** END ONLY FOR THE DEMO **/


                }
            },
            'json'
        );

    })(jQuery);


    }



