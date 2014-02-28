<?php
/*
	Section: Scroll Nav Fixed
	Author: bestrag
	Author URI: http://bestrag.net
	Class Name: ScrollNavFixed
	Demo: http://bestrag.net/scroll-nav/fixed-demo
	Description: Scroll Nav Fixed allows users to build custom one-page navigation menu. It offers default blueprint set that is easy to customize or place on various portions of your page.
	Version: 3.3
	V3: true
	Filter: full-width, nav
*/
class ScrollNavFixed extends PageLinesSection {
	var $lud_opts 		= array();
	var $section_id		= 'scroll-nav-fixed';
	var $prefix		= 'snav';
	var $default_template = 'top-center-blueprint';
	/* section_styles */
	function section_scripts(){
		wp_enqueue_script( 'scrollnav', $this->base_url.'/scrollnav.js', array( 'jquery' ), true );
	}

	/* section_head */
	function section_head() {
		$this->lud_opts['snav_template']	= ($this->opt('snav_template')) ? ($this->opt('snav_template')) : $this->default_template;
		//scroll options
		$this->lud_opts['snav_speed']		= ($this->opt('snav_speed')) ? intval($this->opt('snav_speed')) : 800;
		$this->lud_opts['snav_target_offset']	= ($this->opt('snav_target_offset')) ? intval($this->opt('snav_target_offset')) : 0;
		$this->lud_opts['snav_menu_offset']	= ($this->opt('snav_menu_offset')) ? intval($this->opt('snav_menu_offset')) : 0;
		//$this->lud_opts['snav_animated']	= ($this->opt('snav_animated')) ? true : false;
		$this->lud_opts['snav_animated']	= false;
		//snav fixed specific
		$this->lud_opts['snav_page_offset']		= ($this->opt('snav_page_offset')) ? false : true;
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
		$snav_acc_item = array('snav_item_txt' =>'', 'snav_item_subtxt' =>'', 'snav_item_icon' =>'');
		for ($i=1; $i < 4; $i++) {
			$def_array['item'.$i] = $snav_acc_item;
		}
		$snav_acc = ( !$this->opt('snav_acc') || $this->opt('snav_acc') == 'false' || !is_array($this->opt('snav_acc')) ) ? $def_array  : $this->opt('snav_acc');
		//check if plugin update
		$snav_item_count	= ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : '';
		//menu items layout
		$snav_elem = array();
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
					'StickyWraper'	: jQuery('.pl-area-pad', sectionClone),
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
		$font_selector = 'section#scroll-nav-fixed'.$this->meta['clone'].' div.scrollnav';
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
			'key'	=> 'snav_template_multi',
			'type'	=> 'multi',
			'col'	=> 1,
			'opts'	=> array(
				array(
					'key'			=> 'snav_template',
					'type' 			=> 'select',
					'default'		=> $this->default_template,
					'label' 		=> __( 'Scroll Nav Fixed Template', 'pagelines' ),
					'opts'			=> $this->get_template_selectvalues(),
				),
				array(
					'key'		=> 'snav_docs',
					'type'		=> 'link',
					'classes'		=> 'btn-info',
					'url'		=> 'http://bestrag.net/scroll-nav/doc',
					'label'		=> __( 'Docs and Config', 'pagelines' )
				)
			)
		);
		$options[] = array(
			'title' => __( 'Layout Config', 'pagelines' ),
			'key'	=> 'snav_layout_multi',
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
			'title' => __( 'Scroll Nav Fixed Config', 'pagelines' ),
			'key'	=> 'snav_conf_multi',
			'type'	=> 'multi',
			'col'	=> 3,
			'opts'	=> array(
				array(
					'key'			=> 'snav_target_offset',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Target Offset', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_speed',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Scroll Speed', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_menu_offset',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Scroll Menu Top Position', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_page_offset',
					'type' 			=> 'check',
					'default'		=> true,
					'label' 		=> __( 'Push page content below Scroll Nav', 'pagelines' ),
				)
			)
		);
		$options[] = array(
			'title' => __( 'Scroll To Top', 'pagelines' ),
			'key'	=> 'snav_top_item',
			'type'	=> 'multi',
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
					'default'	=> '',
					'label' 		=> __( 'Scroll to Top Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_subtxt',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Scroll to Top Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_icon',
					'type' 			=> 'select_icon',
					'default'	=> '',
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
			'col'	=> 2,
			'opts'	=> array(
				array(
					'key'			=> 'snav_custom_link',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Custom Link Adress', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_txt',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Custom Link Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_subtxt',
					'type' 			=> 'text',
					'default'	=> '',
					'label' 		=> __( 'Custom Link Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_icon',
					'type' 			=> 'select_icon',
					'default'	=> '',
					'label' 		=> __( 'Custom Link Icon', 'pagelines' ),
				)
			)
		);
		$options[] = array(
			'key'			=> 'snav_text_font',
			'type' 			=> 'type',
			'col'	=> 2,
			'label' 		=> __( 'Scroll Nav Font', 'pagelines' ),
		);
		return $options;
	}

	//template list for section_opts()
	function get_template_selectvalues(){
		$dir 	= $this->base_dir.'/less/';
		$files 	= glob($dir.'*.less');
		$array 	= array();
		foreach ($files as $filename) {
			$file 			= basename($dir.$filename, ".less");
			$array[$file] 	= array( 'name' => $file );
		}
		return $array;
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
			$snav_items['icon'][] ='<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-anchor"></i></span>';
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
					 if(array_key_exists('snav_item_icon', $value) && $value['snav_item_icon']) $snav_items['icon'][$count]	= '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-'.$value['snav_item_icon'].'"></i></span>';
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
		$updated_opts = $def_array;
		for($i = 1; $i <= $count; $i++){
			foreach( $format as $new_key => $old_key ){
				  if( $this->opt( sprintf($old_key, $i) ) ) $updated_opts['item'.$i][ $new_key ] =  $this->opt( sprintf($old_key, $i) );
			}
		}
		$this->opt_update( $acc_key, $updated_opts, 'local' );
		if( !PL_LESS_DEV ) pl_flush_draft_caches();
		return $updated_opts;
	}
}