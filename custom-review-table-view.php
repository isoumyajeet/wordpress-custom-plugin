<?php
/*
Plugin Name: Toggle Reviews
Description: A custom plugin to view your reviews.
Version: 1.0
Author: Soumyajeet
 */
?>
<?php
    if($_POST['action'] == 'update')// pass the value of the recordset of the table to update in table using get method as it is the backend plugin
    {
        global $wpdb;
        $sid = $_POST['upd_id'];
        $var2 = $wpdb->get_results( "SELECT `status` from `{$wpdb->prefix}table-name` where `id` ='".$sid."'" );

        if($var2[0]->status==0){
                $status = 1;
        }else{
                $status = 0;
        }

        $data = array('status' => $status);//pass the status value as a flag in array, name of the table field name and the array key name must be same to update the value
        $wpdb->update( "{$wpdb->prefix}table-name", $data, array('id' => $sid) );// update the table name if user want to hide and show the table data in front-end
        echo "success";
        exit;
    }
    
    function adminForMenu() {   // add the custom plugin to the wordpress dashboard
        add_menu_page(__('View Reviews', 'menu-test'), __('View Reviews', 'menu-test'), 'manage_options', 'sub-page', 'new_plugin');
    }

    add_action( 'admin_menu', 'adminForMenu' );// hook the custom plugin function name
	
    function new_plugin(){
    global $wpdb;
    //$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->users" );
    //$var = $wpdb->get_results( "SELECT `id`,`name`, `email`, `comments`, `status` from `{$wpdb->prefix}table-name`;" );
    $var = $wpdb->get_results( "SELECT * from `{$wpdb->prefix}table-name`;" );
       //print_r($var);
    ?>
    <div>
        <form method="post" action="" name="newsltr_send" id="newsltr_send">
            <table class="widefat" style="width:95%; margin-top:20px; margin-left:15px;">
                <tr style="text-align: center;">
                    <td colspan="11"><h2>View User Reviews</h2></td>
                </tr>
                <tr>
                    <td><strong>ID</strong></td>
                    <td><strong>Name</strong></td>
                    <td><strong>Email</strong></td>
                    <td><strong>Ratings</strong></td>
                    <td><strong>Comment</strong></td>
                    <td><strong>Status</strong></td>
                </tr>
                <?php
                foreach ($var as $v):
                    //print_r($v); 
                    ?>
                    <tr>
                        <td><?php echo $v->id; ?></td>
                        <td><?php echo $v->name; ?></td>
                        <td><?php echo $v->email; ?></td>
                        <td><?php echo $v->rating; ?></td>
                        <td><?php echo $v->comments; ?></td>
                        <?php if ($v->status == 0) { ?>
                            <td><input type="checkbox" id="<?php echo $v->id; ?>" class="rvflag" name="status" value="<?php echo $v->status; ?>"></td>
                        <?php } else { ?>
                            <td><input type="checkbox" id="<?php echo $v->id; ?>"  class="rvflag" name="status" value="<?php echo $v->status; ?>" checked></td>
                        <?php } ?>
                    <!--<td><?php //echo $v->status;  ?></td>-->
                    <?php endforeach;
                    ?>
                </tr>
            </table> 
        </form>
        <div class="msg" style="text-align: center; font-weight: bold;"></div>
    </div>
    <script type="text/javascript">
    jQuery(function(){
        jQuery('.rvflag').on('click', function() {
            jQuery(this).each(function(){   //get the value of each user recordset
           /* var id = jQuery(this).val();
            alert(id);    */    
            //rv.preventDefault();
            var upd_id = jQuery(this).attr('id');
             //alert(upd_id);
                jQuery.ajax({
                type: "POST",
                url: "admin.php?page=sub-page",
                data: {upd_id:upd_id,action:'update'},
                success: function(data){
                    if(data == 'success')
                    {
                        jQuery('.msg').html("Status changed successfully.").css('color', 'green');
                    }
                    }
                });
            });
        });
    });        
    </script>
<?php
    } 
?>
