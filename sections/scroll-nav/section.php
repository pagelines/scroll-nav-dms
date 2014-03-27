<?php
/*
	Section: Scroll Nav
	Author: bestrag
	Author URI: http://bestrag.net
	Class Name: ScrollNav
	Demo: http://bestrag.net/scroll-nav/demo
	Description: Scroll Nav allows users to build custom one-page navigation menu. It offers default blueprint set that is easy to customize or place on various portions of your page.
	Version: 3.4.0
	V3: true
	Filter: nav
*/
class ScrollNav extends PageLinesSection {
	var $lud_opts 		= array();
	var $default_template	= 'top-center-blueprint';
	var $section_id		= 'scroll-nav';
	var $prefix		= 'snav';
	var $clone		= '';
	var $ico 		= '';

	/* section_styles */
	function section_scripts(){
		wp_enqueue_script( 'scrollnav', $this->base_url.'/scrollnav.js', array( 'jquery' ), true );
		wp_enqueue_script( 'waypoints', $this->base_url.'/waypoints.min.js', array( 'jquery' ), true );
	}

	function setup_oset($clone){
		$this->update_lud_colors();
		//fontAwesome for DMS 2.0
		global $platform_build;
		$ver = intval(substr($platform_build, 0, 1));
		$this->ico = ($ver === 2) ? 'fa' : 'icon';
	}

	function section_styles(){
		$snav_bg		= ($this->opt('snav_bg')) ? pl_hashify($this->opt('snav_bg')) : '#FFFFFF';
		$snav_menu_bg	= ($this->opt('snav_menu_bg')) ? pl_hashify($this->opt('snav_menu_bg')) : '#FFFFFF';
		$snav_item_bg		= ($this->opt('snav_item_bg')) ? pl_hashify($this->opt('snav_item_bg')) : '#FFFFFF';
		$snav_icon_color	= ($this->opt('snav_icon_color')) ? pl_hashify($this->opt('snav_icon_color')) : '#225E9B';
		$snav_txt_color		= ($this->opt('snav_txt_color')) ? pl_hashify($this->opt('snav_txt_color')) : '#225E9B';
		$snav_item_hover	= ($this->opt('snav_item_hover')) ? pl_hashify($this->opt('snav_item_hover')) : '#225E9B';
		$snav_icon_hover	= ($this->opt('snav_icon_hover')) ? pl_hashify($this->opt('snav_icon_hover')) : '#FFFFFF';
		$snav_txt_hover	= ($this->opt('snav_txt_hover')) ? pl_hashify($this->opt('snav_txt_hover')) : '#FFFFFF';
		$snav_icon_size	= ($this->opt('snav_icon_size')) ? $this->opt('snav_icon_size') : '48px';
		if(is_numeric($snav_icon_size)) $snav_icon_size .= 'px';
		$snav_mobile		= ($this->opt('snav_mobile')) ? $this->opt('snav_mobile') : '769px';

		$colors=array(
			'snav_bg'		=> array('.scrollnav' ,$snav_bg, 'background-color'),
			'snav_menu_bg'	=> array('.scrollnav .nav', $snav_menu_bg, 'background-color'),
			'snav_item_bg'		=> array('.scrollnav .nav li a',$snav_item_bg, 'background-color'),
			'snav_icon_color'	=> array('.scrollnav .snav-icon-holder', $snav_icon_color, 'color'),
			'snav_txt_color'		=> array('.scrollnav .nav li a', $snav_txt_color, 'color'),
			'snav_item_hover1'	=> array('.scrollnav .nav li a.active:hover', $snav_item_hover, 'background-color'),
			'snav_item_hover2'	=> array('.scrollnav .nav li a.active', $snav_item_hover, 'background-color'),
			'snav_item_hover3'	=> array('.scrollnav .nav li a:hover', $snav_item_hover, 'background-color'),
			'snav_icon_hover1'	=> array('.scrollnav .nav li a.active .snav-icon-holder', $snav_icon_hover, 'color'),
			'snav_icon_hover2'	=> array('.scrollnav .nav li a.active:hover .snav-icon-holder', $snav_icon_hover, 'color'),
			'snav_icon_hover3'	=> array('.scrollnav .nav li a:hover .snav-icon-holder', $snav_icon_hover, 'color'),
			'snav_txt_hover1'	=> array('.scrollnav .nav li a.active', $snav_txt_hover, 'color'),
			'snav_txt_hover2'	=> array('.scrollnav .nav li a.active:hover', $snav_txt_hover, 'color'),
			'snav_txt_hover3'	=> array('.scrollnav .nav li a:hover', $snav_txt_hover, 'color'),
			'snav_icon_size'		=> array('.scrollnav .snav-icon-holder', $snav_icon_size, 'font-size'),
			'snav_mobile'		=> array('', '#', 'display'),
		);


		$css_code = '';
		foreach ($colors as $key => $value) {
			if($value[1] && $value[1] !== '#' && $value[1] !== 'px' ){
				$css_code .= sprintf('#%4$s%5$s %1$s{%2$s:%3$s;}', $value[0], $value[2], $value[1], $this->section_id, $this->meta['clone']);
			}
			if($key === 'snav_mobile') $css_code .= sprintf('@media (max-width: %3$s) {body section#%1$s%2$s div.scrollnav{display:none;}}', $this->section_id, $this->meta['clone'], $snav_mobile);
		}
		if ($css_code) {
			$lud_style = sprintf('<style type="text/css" id="%1$s-custom-%2$s">%3$s</style>', $this->prefix, $this->meta['clone'], $css_code);
			echo $lud_style;
		}
	}

	/* section_head */
	function section_head() {
		$this->lud_opts['snav_template']	= ($this->opt('snav_template')) ? ($this->opt('snav_template')) : $this->default_template;
		//scroll options
		$this->lud_opts['snav_speed']		= ($this->opt('snav_speed')) ? intval($this->opt('snav_speed')) : 800;
		$this->lud_opts['snav_target_offset']	= ($this->opt('snav_target_offset')) ? intval($this->opt('snav_target_offset')) : 0;
		$this->lud_opts['snav_menu_offset']	= ($this->opt('snav_menu_offset')) ? intval($this->opt('snav_menu_offset')) : 0;
		$this->lud_opts['snav_animated']	= ($this->opt('snav_animated')) ? true : false;
		$this->lud_opts['snav_editor']		= (current_user_can( 'edit_theme_options' )) ? true : false ;
		//To top options
		$this->lud_opts['snav_to_top']		= ($this->opt('snav_to_top')) ? true : false;
		$this->lud_opts['snav_to_top_txt']	= ($this->opt('snav_to_top_txt')) ? ($this->opt('snav_to_top_txt')) : '';
		$this->lud_opts['snav_to_top_subtxt']	= ($this->opt('snav_to_top_subtxt')) ? ($this->opt('snav_to_top_subtxt')) : '';
		$this->lud_opts['snav_to_top_icon']	= ($this->opt('snav_to_top_icon')) ? ($this->opt('snav_to_top_icon')) : '';
		//custom link options
		$this->lud_opts['snav_custom_link']	= ($this->opt('snav_custom_link')) ? ($this->opt('snav_custom_link')) : '';
		$this->lud_opts['snav_custom_txt']	= ($this->opt('snav_custom_txt')) ? ($this->opt('snav_custom_txt')) : '';
		$this->lud_opts['snav_custom_subtxt']	= ($this->opt('snav_custom_subtxt')) ? ($this->opt('snav_custom_subtxt')) : '';
		$this->lud_opts['snav_custom_icon']	= ($this->opt('snav_custom_icon')) ? ($this->opt('snav_custom_icon')) : '';
		//accordion opts
		//accordion opts
		$snav_acc_item = array('snav_item_txt' =>'', 'snav_item_subtxt' =>'', 'snav_item_icon' =>'');
		for ($i=1; $i < 4; $i++) {
			$def_array['item'.$i] = $snav_acc_item;
		}
		$snav_acc = ( !$this->opt('snav_acc') || $this->opt('snav_acc') == 'false' || !is_array($this->opt('snav_acc')) ) ? $def_array  : $this->opt('snav_acc');
		//check if plugin update
		$snav_item_count	= ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : '';
		//menu items layout
		$snav_elem		= array();
		$snav_elem[0]		= ($this->opt('snav_elem1')) ? ($this->opt('snav_elem1')) : 'icon';
		$snav_elem[1]		= ($this->opt('snav_elem2')) ? ($this->opt('snav_elem2')) : 'txt';
		$snav_elem[2]		= ($this->opt('snav_elem3')) ? ($this->opt('snav_elem3')) : 'subtxt';
		$this->lud_opts['snav_elem']		= $snav_elem;
		//menu content
		$this->item_elems($snav_acc, $def_array, $snav_item_count);
		//go json
		$lud_opts = json_encode($this->lud_opts);
		?>
		<script type="text/javascript">
			var ludOpts		= {};
			var ludSelectors	= {};
			jQuery(document).ready(function(){
				$ = jQuery;
				var topItem = [];
				var customItem = [];
				var cloneID 		= '<?php echo $this->meta['clone']; ?>';
				var sectionPrefix	= '<?php echo $this->prefix; ?>';
				var sectionClone	= jQuery('section#'+'<?php echo $this->section_id; ?>' + cloneID);

				ludSelectors[cloneID] = {
					'sectionPrefix'	: sectionPrefix,
					'sectionClone'	: sectionClone,
					'sectionId'	: '<?php echo $this->section_id; ?>',
					'StickyWraper'	: jQuery('.pl-section-pad', sectionClone),
					'container'	: jQuery('.scrollnav', sectionClone),
					'wraper'	: jQuery('ul.nav', sectionClone),
					'ludItem'	: jQuery('li', sectionClone),
					'link'		: jQuery('li > a', sectionClone)
				};
				//get options
				ludOpts[cloneID]	= <?php echo $lud_opts; ?>;
				//console.log(ludOpts[cloneID]);
				ludSelectors[cloneID]['container'].scrollNav(ludSelectors[cloneID], ludOpts[cloneID]);
			});
		</script>
		<?php
		/* menu font */
		$font_selector = 'section#scroll-nav'.$this->meta['clone'].' div.scrollnav';
		if ( $this->opt( 'snav_text_font' ) ) {
				echo load_custom_font( $this->opt( 'snav_text_font' ), $font_selector );
		}
	}

	/* section_template */
	function section_template() {
		$snav_template = ($this->opt('snav_template')) ? ($this->opt('snav_template')) : $this->default_template;
		?>
		<div class="scrollnav snav-<?php echo $snav_template ;?>">
			<ul class="nav">
			</ul>
		</div>
		<?php
	}


	/* section_V3_options */
	function section_opts(){
		$options = array();
		$options[] = array(
			'title' => __( 'Template Config', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			'col'	=> 1,
			'opts'	=> array(
				array(
					'key'			=> 'snav_template',
					'type' 			=> 'select',
					'default'		=> $this->default_template,
					'label' 		=> __( 'Scroll Nav Template', 'pagelines' ),
					'opts'			=> $this->get_template_selectvalues(),
				),
				array(
					'key'			=> 'snav_animated',
					'type' 			=> 'check',
					'label' 		=> __( 'Enable Animated Top Menu', 'pagelines' ),
				),
				array(
					'key'		=> 'snav_docs',
					'type'		=> 'link',
					'classes'	=> 'btn-info',
					'url'		=> 'http://bestrag.net/scroll-nav/doc',
					'label'		=> __( 'Docs and Config', 'pagelines' )
				)
			)
		);
		$options[] = array(
			'title' => __( 'Layout Config', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			'col'	=> 2,
			'opts'	=> array(
				array(
					'type' 			=> 'select',
					'key'			=> 'snav_elem1',
					'default'		=> 'icon',
					'label' 		=> __( 'First Element', 'pagelines' ),
					'opts'=> array(
						'txt'               => array( 'name' => __( 'Title', 'pagelines' ) ),
						'subtxt'        => array( 'name' => __( 'Subtitle', 'pagelines' ) ),
						'icon'              => array( 'name' => __( 'Icon', 'pagelines' ) ),
						'none'		=> array( 'name' => __( 'None', 'pagelines' ) ),
					)
				),
				array(
					'type' 			=> 'select',
					'key'			=> 'snav_elem2',
					'default'		=> 'txt',
					'label' 		=> __( 'Second Element', 'pagelines' ),
					'opts'=> array(
						'txt'               => array( 'name' => __( 'Title', 'pagelines' ) ),
						'subtxt'        => array( 'name' => __( 'Subtitle', 'pagelines' ) ),
						'icon'              => array( 'name' => __( 'Icon', 'pagelines' ) ),
						'none'		=> array( 'name' => __( 'None', 'pagelines' ) ),
					)
				),
				array(
					'type' 			=> 'select',
					'key'			=> 'snav_elem3',
					'default'		=> 'subtxt',
					'label' 		=> __( 'Third Element', 'pagelines' ),
					'opts'=> array(
						'txt'               => array( 'name' => __( 'Title', 'pagelines' ) ),
						'subtxt'        => array( 'name' => __( 'Subtitle', 'pagelines' ) ),
						'icon'              => array( 'name' => __( 'Icon', 'pagelines' ) ),
						'none'		=> array( 'name' => __( 'None', 'pagelines' ) ),
					)
				)
			)
		);
		$options[] = array(
			'title' => __( 'Scroll Nav Config', 'pagelines' ),
			'col'	=> 3,
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			//'span'	=> 2,
			'opts'	=> array(
				array(
					'key'			=> 'snav_target_offset',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Target Offset', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_speed',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Scroll Speed', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_menu_offset',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Scroll Menu Offset', 'pagelines' ),
				)
			)
		);
		$options[] = array(
			'title' => __( 'Scroll To Top', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			//'span'	=> 2,
			'col'	=> 1,
			'opts'	=> array(
				array(
					'key'			=> 'snav_to_top',
					'type' 			=> 'check',
					'label' 		=> __( 'Enable Scroll to Top', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_txt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Scroll to Top Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_subtxt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Scroll to Top Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_icon',
					'type' 			=> 'select_icon',
					'default'		=>  '',
					'label' 		=> __( 'Scroll to Top Icon', 'pagelines' ),
				)
			)
		);
		$options[] = 	array(
			'title' => __( 'Custom Menu Content', 'pagelines' ),
			'key'	=> 'snav_acc',
			'col'	=> 3,
			'type'	=> 'accordion',
			'post_type'	=> __('Menu Item', 'pagelines'),
			'opts'	=> array(
				array(
					'key'			=> 'snav_item_txt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label'			=> 'Title'
				),
				array(
					'key'			=> 'snav_item_subtxt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Item Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_item_icon',
					'type' 			=> 'select_icon',
					'default'		=>  '',
					'label' 		=> __( 'Item Icon', 'pagelines' ),
				)
			)
		);

		$options[] = array(
			'title' => __( 'Custom Link Item', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			//'span'	=> 2,
			'col'	=> 2,
			'opts'	=> array(
				array(
					'key'			=> 'snav_custom_link',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Custom Link Adress', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_txt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Custom Link Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_subtxt',
					'type' 			=> 'text',
					'defalut'		=> '',
					'label' 		=> __( 'Custom Link Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_icon',
					'type' 			=> 'select_icon',
					'default'		=>  '',
					'label' 		=> __( 'Custom Link Icon', 'pagelines' ),
				)
			)
		);
		$options[] = array(
					'key'			=> 'snav_text_font',
					'type' 			=> 'type',
					'col'	=> 1,
					'label' 		=> __( 'Scroll Nav Font', 'pagelines' ),
		);
		$options[] =array(
			'type' 	=> 	'multi',
			'key'	=> 'bgcolors',
			'col'	=> 2,
			'title' => __( 'Background Colors', 'pagelines' ),
			'opts'	=> array(
				array(
					'key'           => 'snav_bg',
					'type'          => 'color',
					'label'    => __( 'Scroll Nav Background', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_menu_bg',
					'type'       => 'color',
					'label' => __( 'Menu Background', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_item_bg',
					'type'       => 'color',
					'label' => __( 'Menu Item Background', 'pagelines' ),
					'default'	=> '',
				),
			),
		);
		$options[] =array(
			'type' 	=> 	'multi',
			'key'	=> 'icon-text',
			'col'	=> 3,
			'title' => __( 'Icon and Text color & size', 'pagelines' ),
			'opts'	=> array(
				array(
					'key'           => 'snav_icon_color',
					'type'         => 'color',
					'label'   => __( 'Icon Color', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_txt_color',
					'type'         => 'color',
					'label'   => __( 'Text Color', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_icon_size',
					'type'          => 'text',
					'label'    => __( 'Icon Size in pixels', 'pagelines' ),
				),
			),
		);
		$options[] =array(
			'type' 	=> 	'multi',
			'key'	=> 'active-hover',
			'col'	=> 3,
			'title' => __( 'Active/Hover Colors', 'pagelines' ),
			'opts'	=> array(
				array(
					'key'           => 'snav_item_hover',
					'type'         => 'color',
					'label'   => __( 'Hover/Active Background', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_icon_hover',
					'type'         => 'color',
					'label'   => __( 'Hover/Active Icon', 'pagelines' ),
					'default'	=> '',
				),
				array(
					'key'           => 'snav_txt_hover',
					'type'         => 'color',
					'label'   => __( 'Hover/Active Text', 'pagelines' ),
					'default'	=> '',
				),
			)
		);
		$options[] =array(
			'type' 			=> 'select',
			'key'			=> 'snav_mobile',
			'col'			=> 1,
			'label' 		=> __( 'Hide Scroll Nav DMS on mobile devices', 'pagelines' ),
			'opts'=> array(
				'1024px'            => array( 'name' => __( 'Tablet Landscape', 'pagelines' ) ),
				'768px'        	=> array( 'name' => __( 'Tablet', 'pagelines' ) ),
				'600px'              => array( 'name' => __( 'Mobile', 'pagelines' ) ),
				'1px'		=> array( 'name' => __( "Don't hide", 'pagelines' ) ),
			)
		);
		return $options;
	}

	//template list for section_opts()
	function get_template_selectvalues(){
		$dir 	= $this->base_dir.'/less/';
		$files = glob($dir.'*.less');
		$array 	= array();
		foreach ($files as $filename) {
			$file 			= basename($dir.$filename, ".less");
			$array[$file] 	= array( 'name' => $file );
		}
		return $array;

	}

	/* site options metapanel */
	function get_meta_array( $settings ){

		$settings[ $this->id ] = array(
				'name'  => $this->name,
				//'icon'  => $this->icon,
				'opts'  => $this->sec_site_options()
		);
		return $settings;
	}

	function sec_site_options(){
		$options_array = array(
			array(
				'type' 	=> 	'multi',
				'col'	=> 1,
				'title' => __( 'Background Colors', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => 'snav_bg',
						'type'          => 'color',
						'col'	=> 3,
						'label'    => __( 'Scroll Nav Background', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
					array(
						'key'           => 'snav_menu_bg',
						'type'       => 'color',
						'col'	=> 3,
						'label' => __( 'Menu Background', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
					array(
						'key'           => 'snav_item_bg',
						'type'       => 'color',
						'col'	=> 3,
						'label' => __( 'Menu Item Background', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
				),
			),
			array(
				'type' 	=> 	'multi',
				'col'	=> 2,
				'title' => __( 'Icon and Text color & size', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => 'snav_icon_color',
						'type'         => 'color',
						'col'	=> 3,
						'label'   => __( 'Icon Color', 'pagelines' ),
						'default'	=> '#225E9B',
					),
					array(
						'key'           => 'snav_txt_color',
						'type'         => 'color',
						'col'	=> 3,
						'label'   => __( 'Text Color', 'pagelines' ),
						'default'	=> '#225E9B',
					),
					array(
						'key'           => 'snav_icon_size',
						'type'          => 'text',
						'col'	=> 3,
						'label'    => __( 'Icon Size (e.g. 28px, 4em)', 'pagelines' ),
					),
				),
			),
			array(
				'type' 	=> 	'multi',
				'col'	=> 3,
				'title' => __( 'Active/Hover Colors', 'pagelines' ),
				'opts'	=> array(
					array(
						'key'           => 'snav_item_hover',
						'type'         => 'color',
						'col'	=> 3,
						'label'   => __( 'Hover/Active Background', 'pagelines' ),
						'default'	=> '#225E9B',
					),
					array(
						'key'           => 'snav_icon_hover',
						'type'         => 'color',
						'col'	=> 3,
						'label'   => __( 'Hover/Active Icon', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
					array(
						'key'           => 'snav_txt_hover',
						'type'         => 'color',
						'col'	=> 3,
						'label'   => __( 'Hover/Active Text', 'pagelines' ),
						'default'	=> '#FFFFFF',
					),
				)
			),
			array(
				'type' 			=> 'select',
				'key'			=> 'snav_mobile',
				'default'		=> '979px',
				'label' 		=> __( 'Hide Scroll Nav DMS on mobile devices', 'pagelines' ),
				'opts'=> array(
					'1024px'               => array( 'name' => __( 'Wide Tablet', 'pagelines' ) ),
					'768px'        => array( 'name' => __( 'Tablet', 'pagelines' ) ),
					'600px'              => array( 'name' => __( 'Mobile', 'pagelines' ) ),
					'1px'		=> array( 'name' => __( "Don't hide", 'pagelines' ) ),
				)
			)
		);
		return $options_array;
	}

	//collect menu item elements
	function item_elems($array, $def_array, $number){
		//put all menu item elements into arrays
		$snav_items		= array();
		$title_check		= array();
		//update old values
		if($number){
			$format = array(
				'snav_item_txt'		=> 'snav_item%s_txt',
				'snav_item_subtxt'	=> 'snav_item%s_subtxt',
				'snav_item_icon'	=> 'snav_item%s_icon',
			);
			//update
			$array = $this->acc_upgrade( 'snav_acc', $def_array, $format, $number);
			// do only once
			$this->opt_update('snav_item_count', null, 'local');
		}
		//snav items default
		for($i = 0; $i < 15; $i++){
			$ii = $i+1;
			$snav_items['txt'][] ='<span class="snav-title">Title</span>';
			$snav_items['subtxt'][] ='<span class="snav-subtitle">Subitle</span>';
			$snav_items['icon'][] ='<span class="snav-icon-holder pl-animation pl-appear"><i class="'.$this->ico.' '.$this->ico.'-anchor"></i></span>';
			$title_check[] = 1;
		}
		$count = 0;
		//update items
		if($array & is_array($array)){
			foreach ($array as $i => $value) {
				if($value){
					if(array_key_exists('snav_item_txt', $value) && $value['snav_item_txt']){
						$snav_items['txt'][$count] =  '<span class="snav-title">'.$value['snav_item_txt'].'</span>';
						$title_check[$count] = 0;
					}
					 if(array_key_exists('snav_item_icon', $value) && $value['snav_item_icon']) $snav_items['icon'][$count]	= '<span class="snav-icon-holder pl-animation pl-appear"><i class="'.$this->ico.' '.$this->ico.'-'.$value['snav_item_icon'].'"></i></span>';
					 if(array_key_exists('snav_item_subtxt', $value) && $value['snav_item_subtxt']) $snav_items['subtxt'][$count]	= '<span class="snav-subtitle">'.$value['snav_item_subtxt'].'</span>';
				}
				$count++;
			}
		}
		//collect
		$this->lud_opts['snav_items']	= $snav_items;
		$this->lud_opts['title_check']	= $title_check;
	}

	//acordion options upgrade - $this->upgrade_to_array_format()
	function acc_upgrade( $acc_key, $def_array, $format, $count ){
			$updated_opts = array();
			for($i = 1; $i <= $count; $i++){
				foreach( $format as $new_key => $old_key ){
					  if( $this->opt( sprintf($old_key, $i) ) ) $updated_opts['item'.$i][ $new_key ] =  $this->opt( sprintf($old_key, $i) );
				}
			}
			$this->opt_update( $acc_key, $updated_opts, 'local' );
			if( !PL_LESS_DEV ) pl_flush_draft_caches();
			return $updated_opts;
	}

	//update section specific colors - moved from global to local in ver. 1.2
	function update_lud_colors(){
		$global_colors = array('snav_bg' => '', 'snav_menu_bg' => '', 'snav_item_bg' => '', 'snav_icon_color' => '', 'snav_txt_color' => '', 'snav_icon_size' => '', 'snav_item_hover' => '', 'snav_icon_hover' => '', 'snav_txt_hover' => '', 'snav_mobile' => '');
		foreach ($global_colors as $key => $value) {
			$global_color = pl_setting($key);
			if($global_color && $global_color !== $value){
				$this->opt_update($key, $global_color, 'local');
				$this->meta['set'][$key] = $global_color;
				pl_setting_update($key);
			}
		}
	}
}
