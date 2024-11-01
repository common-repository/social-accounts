<?php
add_action('widgets_init', create_function('', 'register_widget("ffto_social_accounts_widget");'));

class FFTO_Social_Accounts_Widget extends WP_Widget{
	var $styles = array(
		'icons_titles'	=> 'Icons &amp; Titles',
		'icons'			=> 'Icons only',
		'titles'		=> 'Titles only'
	);
	
	public function __construct() {
		parent::__construct(
	 		'ffto_social_accounts_widget', // Base ID
			'Social Accounts', // Name
			array('description' => __('Display the list of Social Accounts'))
		);		
	}
	
	function update($new_instance, $old_instance) {
		$instance 						= $old_instance;
		$instance['title'] 				= strip_tags($new_instance['title']);
		$instance['style'] 				= $new_instance['style'];
		$instance['content'] 			= $new_instance['content'];
		$instance['content_position'] 	= $new_instance['content_position'];
		return $instance;
	}

	function form($instance) {
		$instance 	= wp_parse_args((array) $instance, array('title' => '', 'style'=>'', 'content'=>''));
		$title 		= strip_tags($instance['title']);
		$content	= strip_tags($instance['content'], '<p><a><b><strong><i><em><u><ul><li><ol>');
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content (optional):'); ?></label>
			<textarea id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" class="widefat" rows="4"><?php echo esc_attr($instance['content']); ?></textarea>
		</p>
        <p>
            <label for="<?php echo $this->get_field_id('content_position'); ?>"><?php _e('Content Position'); ?></label>
			<select name="<?php echo $this->get_field_name('content_position'); ?>" class="widefat" id="<?php echo $this->get_field_id('content_position'); ?>">
				<?php foreach (array('before'=>'Before the accounts', 'after'=>'After the accounts') as $id=>$name): ?>
				<option value="<?php echo $id; ?>" <?php selected($instance['content_position'], $id); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style'); ?></label>
			<select name="<?php echo $this->get_field_name('style'); ?>" class="widefat" id="<?php echo $this->get_field_id('style'); ?>">
				<option value="">(none)</option>
				<?php foreach ($this->styles as $id=>$name): ?>
				<option value="<?php echo $id; ?>" <?php selected($instance['style'], $id); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
			</select>
        </p>
		<?php
	}

	function widget($args, $instance){
		extract($instance);
		$output = array();

		if ($style) wp_enqueue_style('ffto-social-accounts-front-style', FFTO_SOCIAL_ACCOUNTS_PLUGIN.'/style.css');		

		echo $args['before_widget'];
		if ($title) echo $args['before_title'].apply_filters('widget_title', $title).$args['after_title'];
		
		$accounts 	= the_social_accounts('container_class=social-accounts '.$style, false);
		$content	= $content ? '<div class="extra-content">'.apply_filters('widget_content', wpautop($content)).'</div>' : '';
		
		if ($content_position == 'before') echo $content;
		echo $accounts;
		if ($content_position == 'after') echo $content;
		
		echo $args['after_widget'];
	}
	
}
