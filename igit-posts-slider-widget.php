<?php /**

 * Plugin Name: IGIT Posts Slider Widget

 * Plugin URI: http://www.hackingethics.com/blog/wordpress-plugins/igit-posts-slider-widget/

 * Description: A widget slider that embed posts into your sidebar category, tags. Also you can show latest posts,old posts and posts by any order you want in this slider wherver your theme supoorts widgets.

 * Version: 1.4

 * Author: Ankur Gandhi

 * Author URI: http://www.hackingethics.com/

 * License: GNU General Public License (GPL), v3 (or newer)
	
   License URI: http://www.gnu.org/licenses/gpl-3.0.html
   
   Tags:Related posts, related post with images
   
   Copyright (c) 2010 - 2012 Ankur Gandhi. All rights reserved.
 
   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

 * 

 */



/**

 * Add function to widgets_init that'll load our widget.

 * @since 1.0

 */

if (!defined('ABSPATH'))

    die("Aren't you supposed to come here via WP-Admin?");

if (!defined('WP_CONTENT_URL'))

    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (!defined('WP_CONTENT_DIR'))

    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (!defined('WP_PLUGIN_URL'))

    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');

if (!defined('igit_oldp_slider_REL_CSS_URL'))

    define('igit_oldp_slider_REL_CSS_URL', WP_CONTENT_URL . '/plugins/igit-posts-slider-widget/css');

	

//wp_enqueue_script('igit_oldp','/wp-content/plugins/igit-posts-slider-widget/js/igit_oldp.js'); 

wp_register_script( 'jquery.simplyscroll', '/wp-content/plugins/igit-posts-slider-widget/js/jquery.simplyscroll.js', array( 'jquery' ) );

wp_enqueue_script('jquery.simplyscroll');

wp_enqueue_style('my-style', '/wp-content/plugins/igit-posts-slider-widget/css/jquery.simplyscroll.css');







require_once(dirname(__FILE__) . '/inc/get-the-image.php');

add_action('widgets_init', 'igit_po_slider_widgets');

function igit_po_slider_widgets()

{

    register_widget('IGIT_Posts_Slider_Widget');

}

class IGIT_Posts_Slider_Widget extends WP_Widget

{

    function IGIT_Posts_Slider_Widget()

    {

        $widget_ops  = array(

            'classname' => 'example',

            'description' => __('Widget that displays posts in slider way.', 'example')

        );

        $control_ops = array(

            'width' => 300,

            'height' => 350,

            'id_base' => 'igit-posts-slider-widget'

        );

        $this->WP_Widget('igit-posts-slider-widget', __('IGIT Posts Slider Widget', 'example'), $widget_ops, $control_ops);

    }

    function widget($args, $instance)

    {

        extract($args);

        global $wpdb, $post, $single, $WP_PLUGIN_URL;

        $title            = apply_filters('widget_title', $instance['title']);

        $pstwidcount      = $instance['igit_oldp_slider_show_post_count'];

        $showigiteidimage = isset($instance['igit_oldp_slider_show_image']) ? $instance['igit_oldp_slider_show_image'] : false;

        $igitoldp_limit    = $pstwidcount;

		$pstwidheight      = $instance['igit_oldp_slider_container_height'];

		$pstwidcategory      = $instance['igit_oldp_slider_category'];

		$pstwidcategoryname      = $instance['igit_oldp_slider_category_name'];

		$pstwidtag      = $instance['igit_oldp_slider_tag'];

		$pstwidorderby      = $instance['igit_oldp_slider_order_by'];

		$pstwidorder      = $instance['igit_oldp_slider_order'];

		 $igitoldp_height = $instance['igit_oldp_slider_image_height'];

		$igitoldp_result_counter = 0;

		$args = array(

		'numberposts'     => $pstwidcount,

		'category'        => $pstwidcategory,

		'category_name'   => $pstwidcategoryname,

		'tag'  			  => $pstwidtag,

		'offset'          => 0,		

		'orderby'         => $pstwidorderby,

		'order'           => $pstwidorder,

		'post_type'       => 'post',

		'post_status'     => 'publish' );

		$igitoldp_results        = get_posts( $args );

		

	

        $igitoldp_output = '<style type="text/css">



.vert .simply-scroll-clip {

		width: 100%;

		height: '.$pstwidheight.'px;

		margin-top:15px;

	}

#igit_wid_oldp_main_image{

	margin-right:5px;

}

</style>

		<script type="text/javascript">

		(function($) {

	jQuery(function() {

		jQuery("#vertical-ticker").simplyScroll({orientation:"vertical",customClass:"vert"});

	});

})(jQuery);

	</script>

		<div id="wrapper">';

        if ($igitoldp_results) {

            $igitoldp_width  = $instance['igit_oldp_slider_image_width'];

            $igitoldp_height = $instance['igit_oldp_slider_image_height'];

            if ($showigiteidimage) {

                $igitoldp_output .= '<ul id="vertical-ticker">';

            }

            if (!$showigiteidimage) {

                $igitoldp_output .= '<ul style="padding-left:5px;list-style-type:disc;">';

            }

            $igitoldp_nodatacnt = 0;

            $widcnt            = 1;

            foreach ($igitoldp_results as $igitoldp_result) {

                $igitoldp_pstincat = false;

                $igitoldp_title    = trim(stripslashes($igitoldp_result->post_title));

                $igitoldp_image    = "";

                preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $igitoldp_result->post_content, $igitoldp_matches);

                if (isset($igitoldp_matches)) {

                    $igitoldp_image = $igitoldp_matches[1][0];

                    $igitoldp_bgurl = get_bloginfo('url');

                    if (!strstr($igitoldp_image, $igitoldp_bgurl)) {

                        $igitoldp_image = WP_PLUGIN_URL . '/igit-posts-slider-widget/images/noimage.gif';

                    }

                }

                if (strlen(trim($image)) == 0) {

                    $igitoldp_image = WP_PLUGIN_URL . '/igit-posts-slider-widget/images/noimage.gif';

                }

                $igitoldp_image = parse_url($igitoldp_image, PHP_URL_PATH);

                if ($showigiteidimage) {

                    $igitoldp_divlnk = "onclick=location.href='" . get_permalink($igitoldp_result->ID) . "'; style=cursor:pointer;";

                    $igitoldp_output .= '<li ' . $igitoldp_divlnk . '>';

                    $igitoldp_output .= '<div id="igit_wid_oldp_main_image"  style="float:left;"><a href="' . get_permalink($igitoldp_result->ID) . '" target="_top"><img id="igit_oldp_thumb" src="' . WP_PLUGIN_URL . '/igit-posts-slider-widget/timthumb.php?src=' . IGIT_igitslider_get_the_image(array(

                        'post_id' => $igitoldp_result->ID

                    )) . '&w=' . $igitoldp_width . '&h=' . $igitoldp_height . '&zc=1"/></a></div>';

                    $igitoldp_output .= '<a href="' . get_permalink($igitoldp_result->ID) . '" target="_top">' . $igitoldp_title . '</a></li>';

                    $igitoldp_nodatacnt = 1;

                }

                if (!$showigiteidimage) {

                    $igitoldp_divlnk = "onclick=location.href='" . get_permalink($igitoldp_result->ID) . "'; style=cursor:pointer;";

                    $igitoldp_output .= '<li id="igit_wid_oldp_li"' . $igitoldp_divlnk . '  style="list-style-type:disc;">' . $widcnt . '. <a href="' . get_permalink($igitoldp_result->ID) . '" target="_top">' . $igitoldp_title . '</a></li>';

                    $igitoldp_nodatacnt = 1;

                    $widcnt++;

                }

                $igitoldp_result_counter++;

                if ($igitoldp_result_counter == $igitoldp_limit)

                    break;

            }

			

            if ($igitoldp_nodatacnt == 0) {

                $igitoldp_output = '<div id="crp_wid_related">';

                $output .= ($igitoldp_crp_settings['blank_output']) ? ' ' : '<p>' . __("No related posts.", CRP_LOCAL_NAME) . '</p>';

            }

            $output .= '</ul>';

        } else {

            $igitoldp_output = '<div id="crp_wid_related">';

            $igitoldp_output .= ($igitoldp_crp_settings['blank_output']) ? ' ' : '<p>' . __($igitoldp_igit_oldp['no_related_post_text'], CRP_LOCAL_NAME) . '</p>';

        }

        $igitoldp_output .= '</ul></div>';

     

        echo $before_widget;

        if ($title)

            echo $before_title . $title . $after_title;

        if ($igitoldp_output)

            echo $igitoldp_output;

        echo $after_widget;

    }

    function update($new_instance, $old_instance)

    {

        $instance                                = $old_instance;

        $instance['title']                       = strip_tags($new_instance['title']);

        $instance['igit_oldp_slider_show_post_count'] = strip_tags($new_instance['igit_oldp_slider_show_post_count']);

        $instance['igit_oldp_slider_show_image']      = $new_instance['igit_oldp_slider_show_image'];

        $instance['igit_oldp_slider_image_width']     = $new_instance['igit_oldp_slider_image_width'];

        $instance['igit_oldp_slider_image_height']    = $new_instance['igit_oldp_slider_image_height'];

		$instance['igit_oldp_slider_container_height']    = $new_instance['igit_oldp_slider_container_height'];

		

		$instance['igit_oldp_slider_category']    = $new_instance['igit_oldp_slider_category'];

		$instance['igit_oldp_slider_category_name']    = $new_instance['igit_oldp_slider_category_name'];

		$instance['igit_oldp_slider_tag']    = $new_instance['igit_oldp_slider_tag'];

		$instance['igit_oldp_slider_order_by']    = $new_instance['igit_oldp_slider_order_by'];

		$instance['igit_oldp_slider_order']    = $new_instance['igit_oldp_slider_order'];

		

        return $instance;

    }

    function form($instance)

    { 

        $defaults = array(

            'title' => __('Related Posts', 'relatedposts'),

            'igit_oldp_slider_show_post_count' => __('8', 'example'),

            'igit_oldp_slider_show_image' => 'yes',

            'igit_oldp_slider_image_width' => '50',

            'igit_oldp_slider_image_height' => '50',

            'igit_oldp_slider_container_height' => '280',

            'igit_oldp_slider_category' => '',

            'igit_oldp_slider_category_name' => '',

            'igit_oldp_slider_tag' => '',

            'igit_oldp_slider_order_by' => 'rand',

            'igit_oldp_slider_order' => 'ASC'

        );

        $instance = wp_parse_args((array) $instance, $defaults);

?>



		<!-- Widget Title: Text Input -->

        

        <p>

            <label for="<?php

        echo $this->get_field_id('title');

?>">

            <?php

        _e('Title:', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('title');

?>" name="<?php

        echo $this->get_field_name('title');

?>" value="<?php

        echo $instance['title'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

        

        

        

		



		<!-- Your Name: Text Input -->

        

        

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_show_post_count');

?>">

            <?php

        _e('Enter records to show:', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_show_post_count');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_show_post_count');

?>" value="<?php

        echo $instance['igit_oldp_slider_show_post_count'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

		

		

		



		<!-- Show Sex? Checkbox -->

        <?php

        if ($instance['igit_oldp_slider_show_image'] == "yes") {

            $chkdstr = "checked='checked'";

        } else {

            $chkdstr = "";

        }

?>

		<p>

			<input class="checkbox" type="checkbox" <?php

        echo $chkdstr;

?> id="<?php

        echo $this->get_field_id('igit_oldp_slider_show_image');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_show_image');

?>" value="yes"/> 

			<label for="<?php

        echo $this->get_field_id('igit_oldp_slider_show_image');

?>"><?php

        _e('Show Image?', 'example');

?></label>

		</p>

        

        

        <!-- Your Name: Text Input -->

        

        

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_image_width');

?>">

            <?php

        _e('Image Width:', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_image_width');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_image_width');

?>" value="<?php

        echo $instance['igit_oldp_slider_image_width'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

             

        <!-- Your Name: Text Input -->

        

        

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_image_height');

?>">

            <?php

        _e('Image Height:', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_image_height');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_image_height');

?>" value="<?php

        echo $instance['igit_oldp_slider_image_height'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

            

            <!-- Your Name: Text Input -->

        

        

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_container_height');

?>">

            <?php

        _e('Container Height:', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_container_height');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_container_height');

?>" value="<?php

        echo $instance['igit_oldp_slider_container_height'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            <!-- Your Name: Text Input -->

        

        

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_category');

?>">

            <?php

        _e('<strong>Category</strong> (Category Id seprated by comma):', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_category');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_category');

?>" value="<?php

        echo $instance['igit_oldp_slider_category'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

            

             <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_category_name');

?>">

            <?php

        _e('<strong>Category Name</strong> (Only show posts from this category name or category slug):', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_category_name');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_category_name');

?>" value="<?php

        echo $instance['igit_oldp_slider_category_name'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_tag');

?>">

            <?php

        _e('<strong>Tag slug</strong> (Only show posts with this tag slug,multiple tag slugs separated by commas,all results matching any tag will be returned):', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_tag');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_tag');

?>" value="<?php

        echo $instance['igit_oldp_slider_tag'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

            <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_order_by');

?>">

            <?php

        _e('<strong>Order By</strong> (Sort posts by values : author,category,content,date,ID,menu_order, mime_type,modified,name,parent,password, rand,status,title,type):', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_order_by');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_order_by');

?>" value="<?php

        echo $instance['igit_oldp_slider_order_by'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

             <p>

            <label for="<?php

        echo $this->get_field_id('igit_oldp_slider_order');

?>">

            <?php

        _e('<strong>Order</strong> (Values : ASC OR DESC):', 'hybrid');

?>

            </label>

            

            

            

            <input id="<?php

        echo $this->get_field_id('igit_oldp_slider_order');

?>" name="<?php

        echo $this->get_field_name('igit_oldp_slider_order');

?>" value="<?php

        echo $instance['igit_oldp_slider_order'];

?>" style="width:100%;" class="widefat"/>

            

            </p>

            

		



	<?php

    }

    function igit_oldp_slider_show_rel_post($pstwidcount, $showigiteidimage)

    {

    }

}



?>