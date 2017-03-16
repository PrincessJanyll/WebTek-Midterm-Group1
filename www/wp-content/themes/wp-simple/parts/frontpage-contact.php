<?php 
$section_bg=nimbus_get_option('fp-contact-background-image');
if (!empty($section_bg)) {
    $nimbus_parallax="data-parallax='scroll' data-image-src='" . esc_url($section_bg) . "' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'";
    $parallax_active="parallax_active";
} 
if (nimbus_get_option('contact-toggle') == '1') { ?>
    <section id="<?php if (nimbus_get_option('fp-contact-slug')=='') {echo "contact";} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>" class="frontpage-row frontpage-contact <?php if(isset($parallax_active)){echo $parallax_active;} ?>" <?php if(isset($nimbus_parallax)){echo $nimbus_parallax;} ?>>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php if (nimbus_get_option('fp-contact-title') != '') { ?>
                        <div class="contact-title h1"><?php echo esc_html(nimbus_get_option('fp-contact-title')); ?></div>
                    <?php } ?>
                    <?php if (nimbus_get_option('fp-contact-sub-title') != '') { ?>
                        <div class="contact-sub-title h4"><?php echo esc_html(nimbus_get_option('fp-contact-sub-title')); ?></div>
                    <?php } ?>
                    <?php 
                    if(isset($_POST['submitted'])) { 
                        if(isset($_POST['myname']) && isset($_POST['email'])) {
                            if((trim($_POST['myname']) != "" ) && (trim($_POST['email']) != "" )) { ?>
                                <p class="bg-success"><?php _e('Thanks for contacting us!', 'wp-simple' ); ?></p>
                            <?php } else { ?>
                                <p class="bg-danger"><?php _e('Please enter your name and email address.', 'wp-simple' ); ?></p>
                            <?php 
                            }
                        }
                    }
                    if(isset($_POST['submitted'])) {
                        $venture_contact_recipient_email = nimbus_get_option('contact-mailto');
                        $venture_contact_sender_email = nimbus_get_option('contact-mailfrom');
                        if($venture_contact_recipient_email != '' && $venture_contact_sender_email != '') {
                            extract($_POST);
                            $blog_name = get_bloginfo('name');
                            $message = "Name: $myname
                                        Email: $email
                                        Website: $website
                                        Comments: $comments
                            ";
                            $headers = 'From: '.$blog_name.' <'.$venture_contact_sender_email.'>' . "\r\n";
                            wp_mail($venture_contact_recipient_email, 'Contact Form', $message, $headers);
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                             <form class="contact-form" name="contact-form" method="post">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="myname" name="myname" placeholder="Name*" class="form-control input-lg" /></div>
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="email" name="email" placeholder="Email*" class="form-control input-lg" /></div>
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="website" name="website" placeholder="Website" class="form-control input-lg" /></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea data-sr="wait 0.3s, enter right and move 50px after 1s" id="comments" name="comments" class="form-control input-lg" rows="6" cols="" placeholder="Comments"></textarea>
                                    </div>
                                </div>
                                <br />
                                <div class="text-center">
                                    <input type="hidden" name="scrolltoform" value="<?php if (nimbus_get_option('fp-contact-slug')=='') {echo "contact";} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>">
                                    <input type="hidden" name="submitted" id="submitted" value="true" />
                                    <input data-sr="wait 0.3s, enter right and move 50px after 1s" type="submit" name="submit_button" id="submit_button" value="<?php _e('Submit', 'wp-simple' ); ?>" class="contact-submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } else if (nimbus_get_option('contact-toggle') == '3') {
    // Don't do anything
} else { ?>  
    <section id="<?php if (nimbus_get_option('fp-contact-slug')=='') {echo "contact";} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>" class="frontpage-row frontpage-contact preview parallax_active" data-parallax='scroll' data-image-src='<?php echo get_template_directory_uri(); ?>/assets/images/preview/chairs.jpg' style='background: transparent;padding:220px 0 200px;background: rgba(0, 0, 0, 0.3);'>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="contact-title h1"><?php _e('Contact Us','wp-simple'); ?></div>
                    <div class="contact-sub-title h4"><?php _e('Let us know what you are thinking.','wp-simple'); ?></div>
                    <?php 
                    if(isset($_POST['submitted'])) { 
                        if(isset($_POST['myname']) && isset($_POST['email'])) {
                            if((trim($_POST['myname']) != "" ) && (trim($_POST['email']) != "" )) { ?>
                                <p class="bg-success"><?php _e('Thanks for contacting us!', 'wp-simple' ); ?></p>
                            <?php } else { ?>
                                <p class="bg-danger"><?php _e('Please enter your name and email address.', 'wp-simple' ); ?></p>
                            <?php 
                            }
                        }
                    }
                    if(isset($_POST['submitted'])) {
                        // Don't do anything because this is a demo form
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                             <form class="contact-form" name="contact-form" method="post">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="myname" name="myname" placeholder="Name*" class="form-control input-lg" /></div>
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="email" name="email" placeholder="Email*" class="form-control input-lg" /></div>
                                        <div class="form-group"><input data-sr="wait 0.3s, enter left and move 50px after 1s" type="text" id="website" name="website" placeholder="Website" class="form-control input-lg" /></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <textarea data-sr="wait 0.3s, enter right and move 50px after 1s" id="comments" name="comments" class="form-control input-lg" rows="6" cols="" placeholder="Comments"></textarea>
                                    </div>
                                </div>
                                <br />
                                <div class="text-center">
                                    <input type="hidden" name="scrolltoform" value="<?php if (nimbus_get_option('fp-contact-slug')=='') {echo "contact";} else {echo esc_attr(nimbus_get_option('fp-contact-slug'));} ?>">
                                    <input type="hidden" name="submitted" id="submitted" value="true" />
                                    <input data-sr="wait 0.3s, enter right and move 50px after 1s" type="submit" name="submit_button" id="submit_button" value="Submit" class="contact-submit" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div>    
        </div>    
    </section> 
<?php } ?> 