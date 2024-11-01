<?php
/*
Plugin Name: Social Accounts
Plugin URI: http://imfreshfromtheoven.com/plugins/social-accounts
Description: Add an option page under Settings where the user can add all his Social Accounts URLs. Includes a Social Account Widget.
Author: Maxime Lefrancois
Version: 1.1.2
Author URI: http://imfreshfromtheoven.com
*/

if(defined('FFTO_SOCIAL_ACCOUNTS')) return;
define('FFTO_SOCIAL_ACCOUNTS', '1.1.2');
define('FFTO_SOCIAL_ACCOUNTS_PATH', dirname(__FILE__));
define('FFTO_SOCIAL_ACCOUNTS_FOLDER', basename(FFTO_SOCIAL_ACCOUNTS_PATH));
define('FFTO_SOCIAL_ACCOUNTS_PLUGIN', plugins_url().'/'.FFTO_SOCIAL_ACCOUNTS_FOLDER);

require FFTO_SOCIAL_ACCOUNTS_PATH.'/ffto-social-accounts-widget.php';

class FFTO_Social_Accounts {
	// Constants
	// =====================================================================

	// Variables
	// =====================================================================
	var $id					= 'ffto-social-accounts';
	var $settings_id		= 'ffto-social-accounts-settings';
	var $option_id			= 'ffto_social_account';
	var $title 				= 'Social Accounts';

	var $next_extra_id		= 0;
	var $extra_image		= '';
	var $default_accounts	= '';

	var $accounts			= array();

	// Constructor
	// =====================================================================
	function __construct (){
		$this->icon_set		= 'icons'.$this->get_value('icon_size', NULL, '16').'/';
		$this->images_url	= FFTO_SOCIAL_ACCOUNTS_PLUGIN.'/images/';

		$this->default_accounts = array(
			array('field'=>'text', 	'id'=>'behance', 		'name'=>'Behance', 		'icon'=> 'behance.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'blogger', 		'name'=>'Blogger', 		'icon'=> 'blogger.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'codepen', 		'name'=>'Codepen', 		'icon'=> 'codepen.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'delicious', 		'name'=>'Delicious', 	'icon'=> 'delicious.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'deviantart',		'name'=>'DeviantArt', 	'icon'=> 'deviantart.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'dribbble', 		'name'=>'Dribbble', 	'icon'=> 'dribbble.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'facebook', 		'name'=>'Facebook', 	'icon'=> 'facebook.png'),
			array('field'=>'text', 	'id'=>'flickr', 		'name'=>'Flickr', 		'icon'=> 'flickr.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'forrst', 		'name'=>'Forrst',	 	'icon'=> 'forrst.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'foursquare', 	'name'=>'FourSquare', 	'icon'=> 'foursquare.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'github',			'name'=>'Github', 		'icon'=> 'github.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'googleplus',		'name'=>'Google+', 		'icon'=> 'googleplus.png'),
			array('field'=>'text', 	'id'=>'instagram',		'name'=>'Instagram', 	'icon'=> 'instagram.png'),
			array('field'=>'text', 	'id'=>'lastfm', 		'name'=>'Last.fm', 		'icon'=> 'lastfm.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'linkedin', 		'name'=>'LinkedIn', 	'icon'=> 'linkedin.png'),
			array('field'=>'text', 	'id'=>'myspace', 		'name'=>'MySpace', 		'icon'=> 'myspace.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'orkut',	 		'name'=>'Orkut', 		'icon'=> 'orkut.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'pinterest', 		'name'=>'Pinterest', 	'icon'=> 'pinterest.png'),
			array('field'=>'text', 	'id'=>'plurk', 			'name'=>'Plurk', 		'icon'=> 'plurk.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'slideshare', 	'name'=>'SlideShare', 	'icon'=> 'slideshare.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'tumblr', 		'name'=>'Tumblr', 		'icon'=> 'tumblr.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'twitter', 		'name'=>'Twitter', 		'icon'=> 'twitter.png'),
			array('field'=>'text', 	'id'=>'vimeo', 			'name'=>'Vimeo', 		'icon'=> 'vimeo.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'wordpress',		'name'=>'WordPress', 	'icon'=> 'wordpress.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'yelp',	 		'name'=>'Yelp', 		'icon'=> 'yelp.png', 'rare'=>true),
			array('field'=>'text', 	'id'=>'youtube', 		'name'=>'Youtube', 		'icon'=> 'youtube.png'),
			array('field'=>'email',	'id'=>'email', 			'name'=>'Email', 		'icon'=> 'email.png'),
			array('field'=>'email',	'id'=>'gmail', 			'name'=>'Gmail', 		'icon'=> 'gmail.png', 'rare'=>true),
			array('field'=>'check', 'id'=>'rss', 			'name'=>'RSS Feed', 	'icon'=> 'rss.png')
		);

		$this->accounts 	= $this->get_accounts(true);
		$this->extra_image	= 'extra.png';

		add_action('admin_menu', array($this, 'admin_menu_add_option_page'));
	}

	// Private functions
	// =====================================================================
	private function get_langs (){
		global $sitepress;

		$languages = function_exists( 'icl_get_languages' ) ? icl_get_languages('skip_missing=0&orderby=KEY&order=DIR') : array();
		foreach ($languages as &$lang){
			if ($sitepress->get_default_language() == $lang['language_code']) $lang = NULL;
		}
		$languages = array_values(array_filter($languages));

		return $languages;
	}

	private function get_lang (){
		//global $sitepress;
		if (defined('ICL_LANGUAGE_CODE')){
			return ICL_LANGUAGE_CODE;
		}else{
			return NULL;
		}
	}

	private function get_name ($type, $account=NULL){
		return $this->option_id.'['.$type.']'.(!is_null($account)?'['.$account.']':'');
	}

	private function get_id ($type, $account=NULL){
		return $this->option_id.'_'.$type.(!is_null($account)?'_'.$account:'');
	}

	private function get_value ($type, $account=NULL, $default=NULL){
		$options = get_option($this->option_id);

		if (!$options || !isset($options[$type])) return $default;
		$value = $account ? $options[$type][$account] : $options[$type];
		return isset($value) ? $value : $default;
	}

	private function get_data ($type, $account=NULL, $default=NULL){
		return (object)array(
			'name'	=> $this->get_name($type, $account),
			'id'	=> $this->get_id($type, $account),
			'value'	=> $this->get_value($type, $account, $default)
		);
	}

	private function get_extras (){
		$extraNames		= $this->get_value('extra_name');
		$extraValues	= $this->get_value('extra_value');
		$lastId			= 0;
		$extras			= array();

		if (empty($extraNames)) return array();

		foreach ($extraNames as $i=>$name){
			if ($i==='%value%') continue;
			$extras[] = array('id'=>$i, 'name'=>$name, 'value'=>$extraValues[$i]);
			$lastId = max((int)$i, $lastId);
		}

		$this->next_extra_id = $lastId+1;

		return $extras;
	}

	// Functions
	// =====================================================================
	public function get_accounts ($all=false){
		$defaults	= $this->default_accounts;
		$extras 	= $this->get_extras();
		$order		= $this->get_value('order');
		$visible	= $this->get_value('visible_accounts');
		$lang		= $this->get_lang();
		$accounts	= array();

		if ($order){
			$order = array_flip(explode(',', $order));
			foreach ($defaults as $account){
				$order[$account['id']] = array_merge($account, array(
					'type'		=> 'default',
					'value'		=> $this->get_value('value', $account['id']),
					'new_name'	=> $this->get_value($lang?'alt_name_'.$lang:'name', $account['id'])
				));
			}
			foreach ($extras as $account){
				$order['extra_'.$account['id']] = array_merge($account, array(
					'type'		=> 'extra',
					'new_name'	=> $lang?$this->get_value('extra_alt_name_'.$lang, $account['id']):NULL
				));
			}
			$accounts = $order;
		}else{
			foreach ($defaults as $account) $accounts[] = array_merge($account, array(
				'type'		=> 'default',
				'value'		=> $this->get_value('value', $account['id']),
				'new_name'	=> $this->get_value($lang?'alt_name_'.$lang:'name', $account['id'])
			));
			foreach ($extras as $account) $accounts[] = array_merge($account, array(
				'type'		=> 'extra',
				'new_name'	=> $lang?$this->get_value('extra_alt_name_'.$lang, $account['id']):NULL
			));
		}

		if (!$all){
			$filtered = array();
			foreach ($accounts as $account){
				if (($visible=='popular' && $account['rare']==true) || !$account['value']) continue;

				$image 	= '';
				$custom = $this->get_value('custom_image', $account['id']);

				if ($custom)							$image = $custom;
				else if ($account['type']=='extra')		$image = $this->images_url.$this->icon_set.$this->extra_image;
				else									$image = $this->images_url.$this->icon_set.$account['icon'];

				$account['full_icon'] = $image;
				$filtered[] = $account;
			}
			$accounts = $filtered;
		}

		return $accounts;
	}

	public function urlize ($url){
		$isHttps = strpos($url, 'https') !== false;
		$url = preg_replace('/https?\:\/\//', '', $url);
		return 'http'.($isHttps?'s':'').'://'.$url;
	}

	// Hooks
	// =====================================================================
	function admin_menu_add_option_page (){
		$submenu = add_submenu_page( 'options-general.php', $this->title, $this->title, 'manage_options', $this->id, array($this, 'output_option_page' ) );
		add_action('admin_init', array($this, 'register_options'));
		add_action('admin_print_scripts-'.$submenu, array($this, 'add_js'));
	}

	function add_js (){
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-draggable');
	}

	function register_options (){
		register_setting($this->settings_id, $this->option_id);
	}

	function output_option_page (){
		wp_enqueue_style('thickbox');
		//wp_enqueue_style('wp-pointer');
		wp_enqueue_style('ffto-social-accounts-style', plugins_url('admin-style.css', __FILE__));

		//wp_enqueue_script('wp-pointer');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('ffto-social-accounts-scripts', plugins_url('admin-script.js', __FILE__));

		wp_localize_script('ffto-social-accounts-scripts', 'ffto_social_accounts', array(
			'next_extra_id'	=> $this->next_extra_id,
			'error_extra'	=> __('Please fill the fields to add an account')
		));
	?>
	<br clear="all" />
	<div class="wrap">
		<div id="icon-options-social-accounts" class="icon32"></div>
		<h2><?php echo $this->title; ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields($this->settings_id); ?>

			<h3><?php _e('Settings'); ?></h3>

			<table id="social_settings" class="form-table">
				<tr>
					<th><label><?php _e('Visible accounts'); ?></label></th>
					<td>
						<?php $value = $this->get_value('visible_accounts', NULL, 'popular'); ?>
						<input type="radio" id="<?php echo $this->get_id('visible_accounts'); ?>_popular" name="<?php echo $this->get_name('visible_accounts'); ?>" <?php checked($value, 'popular'); ?> value="popular" />
						<label for="<?php echo $this->get_id('visible_accounts'); ?>_popular"><?php _e('Popular accounts only'); ?></label>
						<br />
						<input type="radio" id="<?php echo $this->get_id('visible_accounts'); ?>_all" name="<?php echo $this->get_name('visible_accounts'); ?>" <?php checked($value, 'all'); ?> value="all" />
						<label for="<?php echo $this->get_id('visible_accounts'); ?>_all"><?php _e('All accounts'); ?></label>
					</td>
				</tr>
				<tr>
					<th><label><?php _e('Icon size'); ?></label></th>
					<td>
						<?php $value = $this->get_value('icon_size', NULL, '16'); ?>
						<input type="radio" id="<?php echo $this->get_id('icon_size'); ?>_16" name="<?php echo $this->get_name('icon_size'); ?>" <?php checked($value, '16'); ?> value="16" />
						<label for="<?php echo $this->get_id('icon_size'); ?>_16">
							<?php _e('16px'); ?>
							<span class="icons">
							<?php foreach ($this->default_accounts as $a): if ($a['rare']) continue; ?>
							<img src="<?php echo $this->images_url.'icons16/'.$a['icon']; ?>" />
							<?php endforeach; ?>
							</span>
						</label>
						<br />
						<input type="radio" id="<?php echo $this->get_id('icon_size'); ?>_32" name="<?php echo $this->get_name('icon_size'); ?>" <?php checked($value, '32'); ?> value="32" />
						<label for="<?php echo $this->get_id('icon_size'); ?>_32">
							<?php _e('32px'); ?>
							<span class="icons">
							<?php foreach ($this->default_accounts as $a): if ($a['rare']) continue; ?>
							<img src="<?php echo $this->images_url.'icons32/'.$a['icon']; ?>" />
							<?php endforeach; ?>
							</span>
						</label>
					</td>
				</tr>
			</table>

			<h3><?php _e('Accounts'); ?></h3>
			<p><?php _e('Add your accounts URL in the fields below. You can re-order the account list by drag-dropping the rows. The image can be changed by clicking on any of the account icon.'); ?></p>

			<table id="social_accounts" class="form-table">
			<?php $langs = $this->get_langs(); ?>
			<?php foreach ($this->accounts as $i=>$account): ?>
				<?php if ($account['type'] == 'default'): ?>
				<tr data-account="<?php echo $account['id']; ?>" class="<?php echo isset($account['rare']) ? 'rare' : ''; ?>">
					<?php
					$name	= $this->get_data('name', $account['id'], $account['name']);
					$value 	= $this->get_data('value', $account['id']);
					$image	= $this->get_data('custom_image', $account['id']);
					?>
					<th scope="row">
						<span id="<?php echo $image->id; ?>" class="upload">
							<img src="<?php echo $this->images_url.$this->icon_set.$account['icon']; ?>" class="old" />
							<span class="new">
              	            	<button type="button" class="remove_icon"></button>
                                <img src="<?php echo $image->value; ?>" />
							</span>
							<span class="edit_icon"></span>
							<input type="hidden" id="<?php echo $image->id; ?>" name="<?php echo $image->name; ?>" value="<?php echo $image->value; ?>" class="image_source" />
						</span>

						<div class="names">
							<input class="name" type="text" placeholder="<?php echo $account['name']; ?>" id="<?php echo $name->id; ?>" name="<?php echo $name->name; ?>" value="<?php echo $name->value; ?>" />
							<?php foreach ($langs as $lang): $alt = $this->get_data('alt_name_'.$lang['language_code'], $account['id']); ?>
							<input class="alt_name name" type="text" placeholder="<?php echo $account['name']; ?>" id="<?php echo $alt->id; ?>" name="<?php echo $alt->name; ?>" value="<?php echo $alt->value; ?>" style="background-image:url(<?php echo $lang['country_flag_url']; ?>);" />
							<?php endforeach; ?>
						</div>
					</th>
					<td>
						<?php if ($account['field'] == 'check'): ?>
						<input type="checkbox" name="<?php echo $value->name; ?>" id="<?php echo $value->id; ?>" <?php checked($value->value, 'true'); ?> value="true" />
						<?php else: ?>
						<input type="text" name="<?php echo $value->name; ?>" id="<?php echo $value->id; ?>" value="<?php echo $value->value; ?>" class="regular-text" />
						<?php endif; ?>
					</td>
				</tr>
				<?php else: ?>
				<tr data-account="extra_<?php echo $account['id']; ?>">
					<?php
					$btn	= $this->get_data('custom_btn_id', $account['id']);
					$image	= $this->get_data('custom_value', $account['id']);
					$name	= $this->get_data('extra_name', $account['id']);
					$value	= $this->get_data('extra_value', $account['id']);
					?>
					<th scope="row">
						<span id="<?php echo $btn->id; ?>" class="upload">
							<img src="<?php echo $this->images_url.$this->icon_set.$this->extra_image; ?>" class="old" />
							<span class="new">
              	            	<button type="button" class="remove_icon"></button>
                                <img src="<?php echo $image->value; ?>" />
							</span>
							<span class="edit_icon"></span>
							<input type="hidden" id="<?php echo $image->id; ?>" name="<?php echo $image->name; ?>" value="<?php echo $image->value; ?>" class="image_source" />
						</span>

						<div class="names">
							<input class="name extra_name" type="text" id="<?php echo $name->id; ?>" name="<?php echo $name->name; ?>" value="<?php echo $name->value; ?>" />
							<?php foreach ($langs as $lang): $alt = $this->get_data('extra_alt_name_'.$lang['language_code'], $account['id']); ?>
							<input class="name alt_name extra_name" type="text" id="<?php echo $alt->id; ?>" name="<?php echo $alt->name; ?>" value="<?php echo $alt->value; ?>" style="background-image:url(<?php echo $lang['country_flag_url']; ?>);" />
							<?php endforeach; ?>
						</div>
					</th>
					<td>
						<input type="text" name="<?php echo $value->name; ?>" id="<?php echo $value->id; ?>" value="<?php echo $value->value; ?>" class="extra_value regular-text" />
						<button class="remove"><img src="<?php echo $this->images_url; ?>delete.png" alt="<?php _e('Remove'); ?>" /></button>
					</td>
				</tr>
				<?php endif; ?>
			<?php endforeach; ?>
				<?php
				$btn	= $this->get_data('custom_btn_id', '%value%');
				$image	= $this->get_data('custom_value', '%value%');
				$name	= $this->get_data('extra_name', '%value%');
				$value	= $this->get_data('extra_value', '%value%');
				?>
				<tr id="extra_template" style="display:none;">
					<th scope="row">
						<span id="<?php echo $btn->id; ?>" class="upload">
							<img src="<?php echo $this->images_url.$this->icon_set.$this->extra_image; ?>" class="old" />
							<span class="new">
              	            	<button type="button" class="remove_icon"></button>
                                <img src="" />
							</span>
							<span class="edit_icon"></span>
							<input type="hidden" id="<?php echo $image->id; ?>" name="<?php echo $image->name; ?>" value="<?php echo $image->value; ?>" class="image_source" />
						</span>

						<div class="names">
							<input class="name extra_name" type="text" id="<?php echo $name->id; ?>" name="<?php echo $name->name; ?>" value="<?php echo $name->value; ?>" />
							<?php foreach ($langs as $lang): $alt = $this->get_data('extra_alt_name_'.$lang['language_code'], '%value%'); ?>
							<input class="name alt_name extra_name" type="text" id="<?php echo $alt->id; ?>" name="<?php echo $alt->name; ?>" value="<?php echo $alt->value; ?>" style="background-image:url(<?php echo $lang['country_flag_url']; ?>);" />
							<?php endforeach; ?>
						</div>
					</th>
					<td>
						<input type="text" name="<?php echo $value->name; ?>" id="<?php echo $value->id; ?>" value="<?php echo $value->value; ?>" class="extra_value regular-text" />
						<button class="remove"><img src="<?php echo $this->images_url; ?>delete.png" alt="<?php _e('Remove'); ?>" /></button>
					</td>
				</tr>
			</table>

			<input type="text" name="<?php echo $this->get_name('order'); ?>" id="<?php echo $this->get_id('order'); ?>" value="<?php echo $this->get_value('order'); ?>" style="display:none;" />

			<h3><?php _e('Add Account'); ?></h3>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e('Website Name'); ?></th>
					<td><input type="text" id="extra_account_name" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Profile URL'); ?></th>
					<td><input type="text" id="extra_account_value" class="regular-text" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e('Custom image (optional)'); ?></th>
					<td>
                    	<input type="text" id="extra_account_custom" />
                        <button type="button" id="extra_upload" class="button action"><?php _e('upload image'); ?></button>
                    </td>
				</tr>
				<tr valign="top">
					<th>&nbsp;</th>
					<td><input id="extra_add" type="button" class="button action" value="<?php _e('Add Account') ?>" /></td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>

			<h3><?php _e('For Developpers'); ?></h3>
			<p>
				<?php _e('Functions to use in the theme development for showing the Social Accounts: <code>get_social_accounts()</code> will returns a list of the active accounts and <code>the_social_accounts()</code> output the list of active accounts.'); ?>
				<br />
				<?php _e('To use them properly, paste this in your code <code>if (function_exists(\'the_social_accounts\')) the_social_accounts();</code>'); ?>
			</p>
			<p>
				<?php _e('Shortcodes to use <code>[social_accounts style=icons]</code> (icons only), <code>[social_accounts style=titles]</code> (titles only) or without the style parameter for both.'); ?>
			</p>
		</form>
	</div>
	<?php
	}
}

$ffto_social_accounts = new FFTO_Social_Accounts();

function get_social_accounts (){
	global $ffto_social_accounts;
	$accounts = array();

	foreach ($ffto_social_accounts->get_accounts() as $account){
		$classes = array();
		$classes[] = 'account';
		$classes[] = $account['type'] == 'extra' ? 'extra' : 'default';
		$classes[] = $account['type'] == 'extra' ? sanitize_title(trim($account['name'])) : $account['id'];

		$url = trim($account['value']);

		if ($account['id'] == 'rss')			$url = get_bloginfo('rss_url');
		elseif ($account['field'] == 'email')	$url = 'mailto:'.$url;
		else									$url = $ffto_social_accounts->urlize($url);

		$accounts[] = (object) array(
			'classes'	=> $classes,
			'id'		=> $account['id'],
			'name'		=> $account['new_name']?$account['new_name']:$account['name'],
			'type'		=> $account['type'],
			'icon'		=> $account['full_icon'],
			'url'		=> $url
		);
	}

	return apply_filters('get_social_accounts', $accounts);
}

function the_social_accounts ($args='', $echo=true){
	$accounts 	= get_social_accounts();
	$output		= array();

	$args = wp_parse_args( $args , array(
		'style'				=> '',
		'container_id'		=> 'social-accounts',
		'container_class'	=> 'social-accounts'
	) );
	extract( $args , EXTR_SKIP );

	if (empty($accounts)) return '';

	if ($style) wp_enqueue_style('ffto-social-accounts-front-style', FFTO_SOCIAL_ACCOUNTS_PLUGIN.'/style.css');

	$output[] = '<ul id="'.$container_id.'" class="'.$container_class.' '.$style.'">';
	foreach ($accounts as $account){
		$output[] = '<li class="'.implode(' ', $account->classes).'" title="'.esc_attr($account->name).'"><a href="'.$account->url.'" title="'.esc_attr($account->name).'" target="_blank">';
		$output[] = apply_filters('the_social_accounts_icon', '<img src="'.$account->icon.'" alt="'.esc_attr($account->name).'" />', $account->icon, $account->name);
		$output[] = apply_filters('the_social_accounts_name', '<span>'.$account->name.'</span>', $account->name);
		$output[] = '</a></li>';
	}
	$output[] = '</ul>';

	$output = apply_filters('the_social_accounts', implode('', $output));
	if ($echo) echo $output;
	return $output;
}

function ffto_social_accounts_shortcode ($atts){
	return the_social_accounts($atts, false);
}
add_shortcode('social_accounts', 'ffto_social_accounts_shortcode');
