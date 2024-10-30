<?php
/**
 * Plugin Name: Buzz Contact Form
 * Plugin URI: http://pkvehicles.com/
 * Description: Simple but efficient contact form which enables site visitors to contact website administrator.
 * Version: 1.0
 * Author: Rizwan Aziz
 * Author URI: http://pkvehicles.com/
*/

// Declare function for form settings
add_action('admin_menu', 'bcf_formSettings');
// Call settings function
function bcf_formSettings() {
    add_submenu_page(
        'options-general.php',
        'Buzz Contact Form Settings',
        'Buzz Contact Form',
        'manage_options',
        'Buzz_Contact_Form_Settings',
        'bcf_generateSettingsPage'
    );
}
// Call function to generate settings page
function bcf_generateSettingsPage() {
    // Include functions file
    require_once('includes/functions.php');
    // Check if form submitted
    if (isset($_POST['BCF_Admin_Email']) && current_user_can('update_plugins')) {
        // Check referer
        check_admin_referer('bcf_change_settings', 'bcf_settings_nonce');
        // Declare setting variables
        $BCF_Admin_Email = sanitize_email($_POST['BCF_Admin_Email']);
        $BCF_Success_Message = sanitize_text_field($_POST['BCF_Success_Message']);
        $BCF_Invalid_Email_Message = sanitize_text_field($_POST['BCF_Invalid_Email_Message']);
        $BCF_Empty_Field_Message = sanitize_text_field($_POST['BCF_Empty_Field_Message']);
        $BCF_Invalid_Captcha_Message = sanitize_text_field($_POST['BCF_Invalid_Captcha_Message']);
        $BCF_Field_Error_Message_Color = sanitize_text_field($_POST['BCF_Field_Error_Message_Color']);
        $BCF_Success_Message_Color = sanitize_text_field($_POST['BCF_Success_Message_Color']);
        $BCF_Button_Color = sanitize_text_field($_POST['BCF_Button_Color']);
        $BCF_Button_Text_Color = sanitize_text_field($_POST['BCF_Button_Text_Color']);
        // Validate submitted data
        if (is_email($BCF_Admin_Email) && bcf_checkLength($BCF_Success_Message) && bcf_checkLength($BCF_Invalid_Email_Message) && bcf_checkLength($BCF_Empty_Field_Message) && bcf_checkLength($BCF_Invalid_Captcha_Message)) {
            // Update setting variables
            update_option('BCF_Admin_Email', $BCF_Admin_Email);
            update_option('BCF_Success_Message', $BCF_Success_Message);
            update_option('BCF_Invalid_Email_Message', $BCF_Invalid_Email_Message);
            update_option('BCF_Empty_Field_Message', $BCF_Empty_Field_Message);
            update_option('BCF_Invalid_Captcha_Message', $BCF_Invalid_Captcha_Message);
            update_option('BCF_Field_Error_Message_Color', $BCF_Field_Error_Message_Color);
            update_option('BCF_Success_Message_Color', $BCF_Success_Message_Color);
            update_option('BCF_Button_Color', $BCF_Button_Color);
            update_option('BCF_Button_Text_Color', $BCF_Button_Text_Color);
            // Set acknowledgement message
            $bcf_adminMsg = 'Settings Updated Successfully!';
            // Set admin message class
            $bcf_adminMsgCass = 'bcf_frmSuccessAdmin';
        } else {
            // Sanitize email so that it properly appears in text box
            $BCF_Admin_Email = filter_var($_POST['BCF_Admin_Email'], FILTER_SANITIZE_EMAIL);
            // Set error message
            $bcf_adminMsg = 'Please fill all fields properly.';
            // Set admin message class
            $bcf_adminMsgCass = 'bcf_frmErrorAdmin';
        }
    } else {
        // Declare setting variables
        $BCF_Admin_Email = get_option('BCF_Admin_Email');
        $BCF_Success_Message = get_option('BCF_Success_Message');
        $BCF_Invalid_Email_Message = get_option('BCF_Invalid_Email_Message');
        $BCF_Empty_Field_Message = get_option('BCF_Empty_Field_Message');
        $BCF_Invalid_Captcha_Message = get_option('BCF_Invalid_Captcha_Message');
        $BCF_Field_Error_Message_Color = get_option('BCF_Field_Error_Message_Color');
        $BCF_Success_Message_Color = get_option('BCF_Success_Message_Color');
        $BCF_Button_Color = get_option('BCF_Button_Color');
        $BCF_Button_Text_Color = get_option('BCF_Button_Text_Color');
    }
?>
    <div class="condiv">
        <h1>Buzz Contact Form Settings</h1>
        <?php if (isset($bcf_adminMsg)) { ?>
            <div class="bcf_mtop <?php echo $bcf_adminMsgCass; ?>"><?php echo $bcf_adminMsg; ?></div>
        <?php } ?>
        <div class="bcf_txt">
            <form method="post" autocomplete="off">
                <?php wp_nonce_field('bcf_change_settings', 'bcf_settings_nonce', true, true); ?>
                <div class="bcf_mtop">To embed <strong>Buzz Contact Form</strong> into your site, just add the following text at the place where you want the contact form to be displayed.</div>
                <div class="bcf_mtop"><strong>[BuzzContactForm]</strong></div>
                <div class="bcf_mtop">Use the below form to update contact settings.</div>
                <div class="bcf_mtop"><span  class="fheading">Email: (Used to receive user queries)</span><br />
                    <input type="text" name="BCF_Admin_Email" id="BCF_Admin_Email" class="BCFInput" value="<?php echo filter_var($BCF_Admin_Email, FILTER_SANITIZE_EMAIL); ?>" />
                    <div class="bcf_frmerr <?php if (is_email($BCF_Admin_Email)) { ?>bcf_dn<?php } ?>">Please enter a valid email address.</div>
                </div>
                <div class="bcf_mtop"><span  class="fheading">Success Message: (Displayed after successful form submission)</span><br />
                    <textarea rows="3" class="BCFInput" name="BCF_Success_Message" id="BCF_Success_Message"><?php echo wp_unslash(esc_attr($BCF_Success_Message)); ?></textarea>
                    <div class="bcf_frmerr <?php if (bcf_checkLength($BCF_Success_Message)) { ?>bcf_dn<?php } ?>">This message is empty or too short.</div>
                </div>
                <div class="bcf_mtop"><span  class="fheading">Message For Invalid Email Entry:</span><br />
                    <input type="text" name="BCF_Invalid_Email_Message" id="BCF_Invalid_Email_Message" class="BCFInput" value="<?php echo wp_unslash(esc_attr($BCF_Invalid_Email_Message)); ?>" />
                    <div class="bcf_frmerr <?php if (bcf_checkLength($BCF_Invalid_Email_Message)) { ?>bcf_dn<?php } ?>">This message is empty or too short.</div>
                </div>
                <div class="bcf_mtop"><span  class="fheading">Message For Empty Field:</span><br />
                    <input type="text" name="BCF_Empty_Field_Message" id="BCF_Empty_Field_Message" class="BCFInput" value="<?php echo wp_unslash(esc_attr($BCF_Empty_Field_Message)); ?>" />
                    <div class="bcf_frmerr <?php if (bcf_checkLength($BCF_Empty_Field_Message)) { ?>bcf_dn<?php } ?>">This message is empty or too short.</div>
                </div>
                <div class="bcf_mtop"><span  class="fheading">Message For Invalid Captcha Entry:</span><br />
                    <input type="text" name="BCF_Invalid_Captcha_Message" id="BCF_Invalid_Captcha_Message" class="BCFInput" value="<?php echo wp_unslash(esc_attr($BCF_Invalid_Captcha_Message)); ?>" />
                    <div class="bcf_frmerr <?php if (bcf_checkLength($BCF_Invalid_Captcha_Message)) { ?>bcf_dn<?php } ?>">This message is empty or too short.</div>
                </div>
                <div class="bcf_mtop"><span  class="fheading">Error Message Color For Field:</span><br />
                    <input type="color" name="BCF_Field_Error_Message_Color" id="BCF_Field_Error_Message_Color" value="<?php echo wp_unslash(esc_attr($BCF_Field_Error_Message_Color)); ?>" /></div>
                <div class="bcf_mtop"><span  class="fheading">Success Message Color:</span><br />
                    <input type="color" name="BCF_Success_Message_Color" id="BCF_Success_Message_Color" value="<?php echo wp_unslash(esc_attr($BCF_Success_Message_Color)); ?>" /></div>
                <div class="bcf_mtop"><span  class="fheading">Submit Button Color:</span><br />
                    <input type="color" name="BCF_Button_Color" id="BCF_Button_Color" value="<?php echo wp_unslash(esc_attr($BCF_Button_Color)); ?>" /></div>
                <div class="bcf_mtop"><span  class="fheading">Submit Button Text Color:</span><br />
                    <input type="color" name="BCF_Button_Text_Color" id="BCF_Button_Text_Color" value="<?php echo wp_unslash(esc_attr($BCF_Button_Text_Color)); ?>" /></div>
                <div><br /><input type="submit" value="Save Changes" class="BCFSubmit" /></div>
            </form>
        </div>
    </div>
<?php
}

// Include js and css for admin
add_action('admin_print_scripts', 'bcf_includeAdminFiles');
function bcf_includeAdminFiles() {
    // Begin style sheet inclusion
    wp_register_style( 'BCFStyle', plugins_url() . '/buzz-contact-form/css/style.css' );
    wp_enqueue_style('BCFStyle');
    // End style sheet inclusion

    // Begin color picker files inclusion
    wp_register_script('BCF_color_picker', 'wp-content/plugins/buzz-contact-form/js/color-picker/spectrum.js');
    wp_enqueue_script('BCF_color_picker');
    wp_register_style( 'BCFColorStyle', plugins_url() . '/buzz-contact-form/js/color-picker/spectrum.css' );
    wp_enqueue_style('BCFColorStyle');
    // End color picker files inclusion
}

// Include css for front end
add_action('wp_enqueue_scripts', 'bcf_includeFrontFiles');
function bcf_includeFrontFiles() {
    include('css/style.php' );
}

// Set activation function
register_activation_hook(__FILE__, 'bcf_activate');
function bcf_activate() {
    update_option('BCF_Admin_Email', get_option('admin_email'));
    update_option('BCF_Success_Message', 'Form has been submitted successfully.');
    update_option('BCF_Invalid_Email_Message', 'Please enter a valid email address.');
    update_option('BCF_Empty_Field_Message', 'This field can\'t be empty.');
    update_option('BCF_Invalid_Captcha_Message', 'Invalid captcha entered.');
    update_option('BCF_Field_Error_Message_Color', '#ff0000');
    update_option('BCF_Success_Message_Color', '#00ff00');
    update_option('BCF_Button_Color', '#910404');
    update_option('BCF_Button_Text_Color', '#FFFFFF');
}

// Set deactivation function
register_deactivation_hook(__FILE__, 'bcf_deActivate');
function bcf_deActivate() {
    delete_option('BCF_Admin_Email');
    delete_option('BCF_Success_Message');
    delete_option('BCF_Invalid_Email_Message');
    delete_option('BCF_Empty_Field_Message');
    delete_option('BCF_Field_Error_Message_Color');
    delete_option('BCF_Success_Message_Color');
    delete_option('BCF_Button_Color');
    delete_option('BCF_Button_Text_Color');
    delete_option('BCF_Invalid_Captcha_Message');
}

// Create function display contact form
add_shortcode('BuzzContactForm', 'bcf_createBuzzContactForm');
function bcf_createBuzzContactForm() {
    // Include captcha file
    require_once 'securimage/securimage.php';
    // Set captcha validation variable
    $captcha_val = 1;
    // Include functions file
    require_once('includes/functions.php');
    // Check if form submitted
    if (isset($_POST['yname'])) {
        $securimage = new Securimage();
        // Update captcha validation variable
        $captcha_val = $securimage->check($_POST['ct_captcha']);
        // Sanitize submitted info
        $yname = sanitize_text_field($_POST['yname']);
        $email = sanitize_email($_POST['email']);
        $subject = sanitize_text_field($_POST['subject']);
        $note = sanitize_text_field($_POST['note']);
        // Validate submitted values
        if (bcf_checkLengthSmall($yname) && is_email($email) && bcf_checkLength($subject) && bcf_checkLength($note) && $captcha_val == 1) {
            //Email body
            $body = "<b>" . 'Dear Admin,' . "</b><br /><br />" .
            'Following are the details regarding a user contact from ' . site_url() . "<br /><br />" .
            "<b>" . 'Name:' . "</b><br />" . $yname . "<br /><br />" .
            "<b>" . 'Email:' . "</b><br />" . $email . "<br /><br />" .
            "<b>" . 'Subject:' . "</b><br />" . $subject . "<br /><br />" .
            "<b>" . 'Message:' . "</b><br />" . nl2br($note) . "<br /><br /><br />" .
            "<b>" . 'Thanks' . "</b>";
        
            //Set headers
            $headers = "MIME-version:1.0\r\n";
            $headers .= "content-type:text/html;charset=iso-8859-1\r\n";
            $headers .= "From:" . $email . "\r\n";
            wp_mail(esc_attr(get_option('BCF_Admin_Email')), $subject, $body, $headers);
            // Set acknowledgement variable
            $frm_success = 'yes';
            // Unset variables
            unset($yname);
            unset($_POST['email']);
            unset($subject);
            unset($note);
        }
    }
?>
    <div class="bcf_well">
        <form method="post" autocomplete="off">
            <?php if ($frm_success == 'yes') { ?>
                <div class="frm_success"><?php echo esc_attr(get_option('BCF_Success_Message')); ?></div>
            <?php } ?>
            <div>
                <p class="fldname">Your Name:</p>
                <input placeholder="Your Name" type="text" class="txtfld" name="yname" id="yname" value="<?php echo @$yname; ?>" />
                <div class="frmerr <?php if (!isset($yname) || bcf_checkLengthSmall(@$yname)) { ?>vh<?php } ?>"><?php echo (@$yname == '') ? wp_unslash(esc_attr(get_option('BCF_Empty_Field_Message'))) : 'Your name is too short.'; ?></div>
            </div>
            <div>
                <p class="fldname">Email:</p>
                <input placeholder="Email" type="text" class="txtfld" name="email" id="email" value="<?php echo filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); ?>" />
                <div class="frmerr <?php if (!isset($_POST['email']) || is_email(@$email)) { ?>vh<?php } ?>"><?php echo wp_unslash(esc_attr(get_option('BCF_Invalid_Email_Message'))); ?></div>
            </div>
            <div>
                <p class="fldname">Subject:</p>
                <input placeholder="Subject" type="text" class="txtfld" name="subject" id="subject" value="<?php echo @$subject; ?>" />
                <div class="frmerr <?php if (!isset($subject) || bcf_checkLength(@$subject)) { ?>vh<?php } ?>"><?php echo (@$subject == '') ? wp_unslash(esc_attr(get_option('BCF_Empty_Field_Message'))) : 'Subject is too short.'; ?></div>
            </div>
            <div>
                <p class="fldname">Message:</p>
                <textarea placeholder="Message" class="txtfld" name="note" id="note" rows="5"><?php echo @$note; ?></textarea>
                <div class="frmerr <?php if (!isset($note) || bcf_checkLength(@$note)) { ?>vh<?php } ?>"><?php echo (@$note == '') ? wp_unslash(esc_attr(get_option('BCF_Empty_Field_Message'))) : 'Message is too short.'; ?></div>
            </div>
            <div class="bcf_captcha">
                <?php
                // show captcha HTML using Securimage::getCaptchaHtml()
                $options = array();
                $options['input_name'] = 'ct_captcha'; // change name of input element for form post
                $options['disable_flash_fallback'] = false; // allow flash fallback

                if (!empty($_SESSION['ctform']['captcha_error'])) {
                    // error html to show in captcha output
                    $options['error_html'] = $_SESSION['ctform']['captcha_error'];
                }

                echo "<div id='captcha_container_1'>\n";
                echo Securimage::getCaptchaHtml($options);
                echo "\n</div>\n";
                ?>
            </div>
            <?php if ($captcha_val != 1) { ?>
                <div class="frmerr"><?php echo wp_unslash(esc_attr(get_option('BCF_Invalid_Captcha_Message'))); ?></div>
            <?php } ?>
            <br />
            <button type="submit" class="submitbtn">Submit</button>
        </form>
    </div>
<?php } ?>
