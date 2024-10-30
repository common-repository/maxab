<?php
require('../../../../wp-load.php');

if (isset($_GET['url'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'maxab_experiments';
    $experiments = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $table_name . " WHERE conversion_url = %s AND status = 'running'", $_GET['url']));
    
    foreach ($experiments as $experiment) {
        $cookie_name = 'maxab_experiment_' . $experiment->id;
        
        if (isset($_COOKIE[$cookie_name])) {
            $cookie_value = $_COOKIE[$cookie_name];

            if (isset($cookie_value)) {
                switch ($cookie_value) {
                    case 0: // Original page
                        $conversion_visitors_from_original = $experiment->conversion_visitors_from_original + 1;
                        $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET conversion_visitors_from_original = %d WHERE id = %d", $conversion_visitors_from_original, $experiment->id));
                        break;
                    case 1: // Variation page 1
                        $conversion_visitors_from_variation1 = $experiment->conversion_visitors_from_variation1 + 1;
                        $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET conversion_visitors_from_variation1 = %d WHERE id = %d", $conversion_visitors_from_variation1, $experiment->id));
                        break;
                    case 2: // Variation page 2
                        $conversion_visitors_from_variation2 = $experiment->conversion_visitors_from_variation2 + 1;
                        $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET conversion_visitors_from_variation2 = %d WHERE id = %d", $conversion_visitors_from_variation2, $experiment->id));
                        break;
                    case 3: // Variation page 3
                        $conversion_visitors_from_variation3 = $experiment->conversion_visitors_from_variation3 + 1;
                        $wpdb->query($wpdb->prepare("UPDATE " . $table_name . " SET conversion_visitors_from_variation3 = %d WHERE id = %d", $conversion_visitors_from_variation3, $experiment->id));
                        break;
                }
                
                // Delete the cookie by setting its value to an empty string and setting its
                // expiration to 30 days ago (opposite of when we initially set the cookie).
                // We delete the cookie to ensure that the conversion count is incremented
                // only once. For example, if we didn't delete the cookie, then when the user
                // refreshes the conversion page, it will get counted again.
                setcookie($cookie_name, '', time() - (60 * 60 * 24 * 30), '/');
            }
        }
    }
}

die();
?>