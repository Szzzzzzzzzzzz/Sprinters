<?php
/*
Plugin Name: File Download
Description: A simple file download widget.
Text Domain: download-widget
Version: 1.0.2
Author: Fortuner
Author URI: https://theaword.com/
License: GPLv2 or later
*/

class Download_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'download_widget', // Base ID
            esc_html__( 'File Download', 'download-widget' ), // Name
            array( 'description' => esc_html__( 'A File Download Widget', 'download-widget' ), ) // Args
        );
    	add_action('wp_head', array('Download_Widget', 'file_download_css'));
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
    	$type = @end(explode('.', $instance['the_file']));
    	if($type == 'docx' || $type == 'doc'){
    		$icon_src = plugins_url('svg/docx-file-icon.svg', __FILE__); // dark blue
    	}else if ($type == 'pdf'){
            $icon_src = plugins_url('svg/pdf-file-icon.svg', __FILE__); // red
        }else if ($type == 'xls' || $type == 'xlsx' ){
            $icon_src = plugins_url('svg/xlsx-file-icon.svg', __FILE__); // dark green
        }else if ($type == 'mp3'){
            $icon_src = plugins_url('svg/mp3-file-icon.svg', __FILE__); // purple
        }else if ($type == 'mp4'){
            $icon_src = plugins_url('svg/mp4-file-icon.svg', __FILE__); // mauve
        }else if ($type == 'txt'){
            $icon_src = plugins_url('svg/txt-file-icon.svg', __FILE__); // pink
        }else if ($type == 'zip'){
            $icon_src = plugins_url('svg/zip-file-icon.svg', __FILE__); // yellow
        }else if ($type == 'jpg' || $type == 'jpeg' || $type == 'png' || $type == 'gif'){
            $icon_src = plugins_url('svg/photo-file-icon.svg', __FILE__); // violet
        }else if ($type == 'exe'){
    		$icon_src = plugins_url('svg/exe-file-icon.svg', __FILE__); //brown
    	}else{
    		$icon_src = plugins_url('svg/generic-file-icon.svg', __FILE__); //black
    	}
        echo "<p><a href='".$instance['the_file']."' download class='file-download-anchor'><img src='".$icon_src."' class='file-download-icon'><span>".$instance['title']."</span></a></p>";
    }

    public static function file_download_css(){
    	echo "<style>.file-download-anchor{border: 1px solid gainsboro;display: inline-block;padding: 8px 30px 8px 8px;line-height: 31px;}.file-download-anchor:hover{box-shadow: 0px 0px 17px 0px rgba(0,0,0,.125);}.file-download-icon{float: left;margin-right: 5px;width:30px}</style>";
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'File', 'download-widget' );
        $the_file = ! empty( $instance['the_file'] ) ? $instance['the_file'] : esc_html__( 'https://...', 'download-widget' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'File Name:', 'download-widget' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'the_file' ) ); ?>"><?php esc_attr_e( 'Path of the file:', 'download-widget' ); ?></label> 
        <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'the_file' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'the_file' ) ); ?>" type="text" value="<?php echo esc_attr( $the_file ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['the_file'] = ( ! empty( $new_instance['the_file'] ) ) ? sanitize_text_field( $new_instance['the_file'] ) : '';

        return $instance;
    }

} // class Foo_Widget

// register Foo_Widget widget
function register_download_widget() {
    register_widget( 'Download_Widget' );
}
add_action( 'widgets_init', 'register_download_widget' );



