<?php
// For Custom Notification 
// Registering Custom Componet 
function custom_filter_notifications_get_registered_components( $component_names = array() ) {
 
    // Force $component_names to be an array
    if ( ! is_array( $component_names ) ) {
        $component_names = array();
    }

    // Add 'custom' component to registered components array
    array_push( $component_names, 'custom' );
 
    // Return component's with 'custom' appended
    return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'custom_filter_notifications_get_registered_components' );

// Formatting custom with respect to action
function bp_custom_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
 		
 		//$item_id this your id which you can use to get your respected data
    	$data  = get_my_values_custom_function($item_id); // this is custom function it depend on your needs and data
    	$custom_title = $data->title;
    	$custom_link  = $data->link;
    	$custom_text  = $data->text;

    // New custom notifications
    if ( 'custom_action' === $action ) {
        
        // WordPress Toolbar
        if ( 'string' === $format ) {
            $return = apply_filters( 'custom_filter','Your custom notification for <a href="'.$custom_link.'">'.$custom_title.'</a> ', $custom_text, $custom_link );
 
        // Deprecated BuddyBar
        } else {
            $return = apply_filters( 'custom_filter', array(
                'text' => $custom_text,
                'link' => $custom_link
            ), $custom_link, (int) $total_items, $custom_text, $custom_title );
        }
        
        return $return;
        
    }
   
}
add_filter( 'bp_notifications_get_notifications_for_user', 'bp_custom_format_buddypress_notifications', 10, 5 );

// Adding custom Notification in DB 
function bp_custom_notification( $item_id, $author_id ) {

    if ( bp_is_active( 'notifications' ) ) {   // if notification is active from admin panel
    	// if notification is active from admin panel bp_notifications_add_notification function to add notification into database
        bp_notifications_add_notification( array(                        
            'user_id'           => $author_id, // User to whom notification has to be send
            'item_id'           => $item_id,  // Id of thing you want to show it can be item_id or post or custom post or anything
            'component_name'    => 'custom', //  component that we registered
            'component_action'  => 'custom_action', // Our Custom Action 
            'date_notified'     => bp_core_current_time(), // current time
            'is_new'            => 1, // It say that is new notification
        ) );
    }
}
add_action( 'custom_hooks', 'bp_custom_notification', 10, 2);
/**
* custom_hooks is action name which will be call by do_action() function
* bp_custom_notification your function name
* 10 is priority number
* 2 is number of parameter 
*/


/****
* Now Where to call custom_hooks and how 
*/

do_action('custom_hooks', $item_id, $author_id );

/****
* place it where you want to call this action and pass parameter
*/
