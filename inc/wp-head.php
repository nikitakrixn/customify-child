<?php
if ( !defined( 'ABSPATH' ) ) exit;


add_action('wp_head', 'tpk_script_to_header');

function tpk_script_to_header() {


if ( ! current_user_can( 'administrator' ) ) {

if (is_user_logged_in()) {

  global $current_user;
        // Make sure $current_user has the correct information.
        get_currentuserinfo();
  ?>

<script src="//fast.appcues.com/125826.js"></script>
<script type="text/javascript">
  window.AppcuesSettings = {
    enableURLDetection: true
  };
window.Appcues.identify("<?= $current_user->ID ?>", {
  email: "<?= $current_user->user_email ?>",
});
</script>

<?php
}



?>
  




<?php
}

}
