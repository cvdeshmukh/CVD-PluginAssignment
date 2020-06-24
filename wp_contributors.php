<?php
/*Plugin Name: WP Contributors
Plugin URI: http://localhost/wamp/www/wordpress-3.0/wordpress/
Description:  A plugin to display list of contributors on a post.
Author: cvdeshmukh
Author URI: http://localhost/wamp/www/wordpress-3.0/wordpress/
Text Domain: wp_contributors
Version: 1.0.0
*/

/** 
 * The wp_contributors Class.
 */
if(!class_exists(wp_contributors)):
class wp_contributors {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		//hook for post edit screen
		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'add_wp_contributors_meta_box' ) );
			add_action( 'save_post', array( $this, 'save_wp_contributors' ) );
		}
		//Filter the content to append contributors list on a singe post.
		add_filter( 'the_content', array( $this, 'append_wp_contributors') );
	}

	//Filter the content to append contributors list on a singe post.
	function append_wp_contributors($content) {
		global $post;
		//Check for post type and singe post template.
		if ($post->post_type == 'post' && is_single())
		{
			if ($wp_contributors = get_post_meta( $post->ID, 'wp_contributors',true))
			{
				$contributors = '<div style="border:1px solid grey;padding:5px;"><b>Contributors:</b> <ul style="list-style:none;">';
				foreach($wp_contributors as $wp_contributor){
					//List all author with the display name and email
					$author = get_userdata($wp_contributor);
					$contributors .= '<li><a href="'. get_author_posts_url( $author->ID ) .'">';
					$contributors .= get_avatar($author->user_email,24);
					$contributors .= ' ' . $author->display_name . '</a></li>';
				}
				$contributors .= "</ul></div>";
				$content .= $contributors;
			}
		}
		return $content;
	}
	/**
	 * Adds the meta box container.
	 */
	 
	 
	 	    
	 
function add_wp_contributors_meta_box($post){
global $post;
        
echo'<b> Select the contributors that have contributed to this post: </b>';
echo '<br><br>';   
wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
global $wpdb;
$authors = $wpdb->get_results("SELECT ID, user_nicename from $wpdb->users 
ORDER BY user_nicename");
$i=0;
$n=count($authors);
foreach($authors as $author) {
    echo"<input type='checkbox' id='my_meta_box_check' 
    name='my_meta_box_check'";
    echo"value=";
    the_author_meta('user_nicename', $author->ID);
    echo">";
    echo"<label for='author'.$i>";
    the_author_meta('user_nicename', $author->ID);
    echo"</label>";
    echo "<br />";
  }

 echo"<input type='submit' id='submit_btn' name='submit' value='Submit'>";
  }
//save custom data when our post is saved
 function save_custom_data($post_id) 
 {  
  global $post;
  $contributor=get_post_meta($post->ID,'my_meta_box_check',true);
  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;     

 if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( 
 $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

// if our current user can't edit this post, bail
if( !current_user_can( 'edit_post' ) ) return;
if ( isset($_POST['my_meta_box_check']) ) 
{        

    $data=serialize($_POST['my_meta_box_check']);   
    update_post_meta($post_id, 'my_meta_box_check',$data);      
 } 
else {
    delete_post_meta($post_id, 'my_meta_box_check');
 
 } 
 
 add_action( 'save_post', 'save_custom_data' );
}

function displaymeta()
{

    global $post;
    $m_meta_description = get_post_meta($post->ID, 'my_meta_box_check', 
  true);
    echo 'Meta box value: ' . unserialize($m_meta_description);
	
	add_filter( 'the_content', 'displaymeta' );
}


//function wp_contributors_display_callback( $post ) 
//{
  //  include 'cform1.php';
//}



	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_wp_contributors( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['wp_contributors_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['wp_contributors_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'wp_contributors_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( 'post' != $_POST['post_type'] )
			return $post_id;
		
		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;

		/* OK, its safe for us to save the data now. */
		$wp_contributors =  $_POST['wp_contributors'];

		// Update the meta field.
		update_post_meta( $post_id, 'wp_contributors', $wp_contributors );
	}






	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wp_contributors_inner_custom_box', 'wp_contributors_inner_custom_box_nonce' );
		
		
		// Use get_post_meta to retrieve an existing value from the database.
		if (!$wp_contributors = get_post_meta( $post->ID, 'wp_contributors',true))
		{
			$wp_contributors = array();
		}
		echo '<ul class="post-revisions">';
		//get all authors list
		
		function wp_contributor_author_list()
		{
		$authors=get_users(array('who' => 'authors'));
		
		foreach($authors as $author){
			//List all author with the display name and email
			echo '<li><input type="checkbox" name="wp_contributors[]" value="' . $author->ID . '"';
			echo (in_array($author->ID, $wp_contributors)) ? 'checked >' : '>';
			echo get_avatar($author->user_email,24);
			echo ' ' . $author->display_name . ' (' . $author->user_email . ')</li>';
		}
		}
		echo "</ul>";
		}
}
endif;

 
add_filter( 'tutsplus_remove_vowels', 'tutsplus_remove_vowels_callback', 10, 1 );
function tutsplus_remove_vowels_callback( $content ) {
    return preg_replace( '$[aeiou]$i', '', $content );
}
 
add_filter( 'tutsplus_lowercase_all', 'tutsplus_lowercase_all_callback', 10, 1 );
function tutsplus_lowercase_all_callback( $content ) {
    return strtolower( $content );
}
 
add_filter( 'the_content', 'tutsplus_the_content' );
function tutsplus_the_content( $content ) {
 
    // Don't proceed with this function if we're not viewing a single post.
    if ( ! is_single() ) {
        return $content;
    }
 
    return apply_filters( 'tutsplus_lowercase_all',
                    apply_filters( 'tutsplus_remove_vowels', $content )
                 );
}


/**
* Calls the class on the post edit screen.
*/
new wp_contributors();



?>