<?php
/*
	Section: Scroll Nav Fixed
	Author: bestrag
	Author URI: http://bestrag.net
	Class Name: ScrollNavFixed
	Demo: http://bestrag.net/scroll-nav/fixed-demo
	Description: Scroll Nav Fixed allows users to build custom one-page navigation menu. It offers default blueprint set that is easy to customize or place on various portions of your page.
	Version: 3.2.1
	V3: true
	Filter: full-width, nav
*/
class ScrollNavFixed extends PageLinesSection {

	var $default_template = 'top-center-blueprint';
	/* section_styles */
	function section_scripts(){
		wp_enqueue_script( 'scrollnav', $this->base_url.'/scrollnav.js', array( 'jquery' ), true );
	}

	/* section_head */
	function section_head() {
		$snav_template		= ($this->opt('snav_template')) ? ($this->opt('snav_template')) : $this->default_template;
		$snav_editor		= (current_user_can( 'edit_theme_options' )) ? 'true' : '' ;
		//scroll options
		$snav_speed			= ($this->opt('snav_speed')) ? ($this->opt('snav_speed')) : '800';
		$snav_target_offset	= ($this->opt('snav_target_offset')) ? ($this->opt('snav_target_offset')) : '0';
		$snav_menu_offset	= ($this->opt('snav_menu_offset')) ? ($this->opt('snav_menu_offset')) : '0';
		$snav_animated		= ($this->opt('snav_animated')) ? 'true' : '';
		$snav_page_offset		= ($this->opt('snav_page_offset')) ? 'true' : '';

		//To top options
		$snav_to_top		= ($this->opt('snav_to_top')) ? 'true' : '';
		$snav_to_top_txt	= ($this->opt('snav_to_top_txt')) ? ($this->opt('snav_to_top_txt')) : '';
		$snav_to_top_subtxt	= ($this->opt('snav_to_top_subtxt')) ? ($this->opt('snav_to_top_subtxt')) : '';
		$snav_to_top_icon	= ($this->opt('snav_to_top_icon')) ? ($this->opt('snav_to_top_icon')) : '';
		//custom link options
		$snav_custom_link	= ($this->opt('snav_custom_link')) ? ($this->opt('snav_custom_link')) : '';
		$snav_custom_txt	= ($this->opt('snav_custom_txt')) ? ($this->opt('snav_custom_txt')) : '';
		$snav_custom_subtxt	= ($this->opt('snav_custom_subtxt')) ? ($this->opt('snav_custom_subtxt')) : '';
		$snav_custom_icon	= ($this->opt('snav_custom_icon')) ? ($this->opt('snav_custom_icon')) : '';
		//menu items layout
		$snav_elem		= array();
		$snav_elem[0]		= ($this->opt('snav_elem1')) ? ($this->opt('snav_elem1')) : 'icon';
		$snav_elem[1]		= ($this->opt('snav_elem2')) ? ($this->opt('snav_elem2')) : 'txt';
		$snav_elem[2]		= ($this->opt('snav_elem3')) ? ($this->opt('snav_elem3')) : 'subtxt';
		$snav_elem		= json_encode($snav_elem);
		//put all menu item elements into arrays
		$snav_item_count	= ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : 4;
		$snav_items		= array();
		$snav_icon_array	= array();
		$snav_txt_array	= array();
		$snav_subtxt_array	= array();
		for($i = 0; $i < $snav_item_count; $i++){
			$ii = $i+1;
			$snav_txt_array[$i]	= ($this->opt('snav_item'.$ii.'_txt')) ? '<span class="snav-title">'.$this->opt('snav_item'.$ii.'_txt').'</span>' : '';

			$snav_icon_array[$i]	= ($this->opt('snav_item'.$ii.'_icon')) ? '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-'.$this->opt('snav_item'.$ii.'_icon').'"></i></span>' : '';

			$snav_subtxt_array[$i]	= ($this->opt('snav_item'.$ii.'_subtxt')) ? '<span class="snav-subtitle">'.$this->opt('snav_item'.$ii.'_subtxt').'</span>' : '';
		}
		$snav_items['txt']		= $snav_txt_array;
		$snav_items['subtxt']		= $snav_subtxt_array;
		$snav_items['icon']		= $snav_icon_array;
		$snav_items			= json_encode($snav_items);

		?>
		<script type="text/javascript">
			var snav			= '';
			var snavStickyWraper		= '';
			var snavContainer		= '';
			var ul				= '';
			var lia				='';
			var canvasOffset		= '';
			var snavContainerHeight		= '';
			var targetOffset			= '';
			var menuOffset			= '';
			var snavLinks			= '';
			var stickyFix			= 10;
			var animated			= '';
			jQuery(document).ready(function(){
				$ = jQuery;
				//get menu item elements
				//var layout			= <?php echo json_encode($snav_layout); ?>;
				//var snavTxt			= <?php echo json_encode($snav_txt_array); ?>;
				//var snavSubtxt		= <?php echo json_encode($snav_subtxt_array); ?>;
				//var snavIcon		= <?php echo json_encode($snav_icon_array); ?>;
				var snavItems		= <?php echo $snav_items; ?>;
				var elem		= <?php echo $snav_elem; ?>;
				var topElem1		= '';
				var topElem2		= '';
				var topElem3		= '';
				var customElem1	= '';
				var customElem2	= '';
				var customElem3	= '';
				var topItem 		= [];
				var customItem 	= [];
				//copy vars from dom
				snav			= $('section#scroll-nav-fixed'+'<?php echo $this->meta['clone']; ?>');
				snavStickyWraper	= $('.pl-area-pad', snav);
				snavContainer		= $('.scrollnav', snav);
				ul			= $('ul.nav', snavContainer);
				//offset calc		- for menu positioning and target offset
				canvasOffset		= $('#page div.page-canvas').offset().top;
				snavHeight			= snav.outerHeight();
				snavContainerHeight	= snavContainer.outerHeight();
				targetOffset		= - (canvasOffset + <?php print $snav_target_offset; ?> + snavHeight);
				//initialize scrollNav()
				snavContainer.scrollNav({
						scrollSpeed:    <?php print $snav_speed; ?> ,
						scrollOffset:   targetOffset,
				});
				//append/substitute menu item elements
				ul.children().each(function( i ){
					a = $(this).find('a');
					//menu item element that uses $('scroll-header[title]') atribute
					domTitle = $('span.snav-dom-title',a);
					//append item elements and apply item layout
					domTitle = a.data('domTitle') ;
					if(snavItems['txt'][i] === '') snavItems['txt'][i] = '<span class="snav-title snav-dom-title">' + domTitle + '</span>';
					elem1	= (elem[0] !== 'none') ? snavItems[elem[0]][i] : '';
					elem2	= (elem[1] !== 'none') ? snavItems[elem[1]][i] : '';
					elem3	= (elem[2] !== 'none') ? snavItems[elem[2]][i] : '';
					a.append(elem1, elem2, elem3);

				});
				//add scroll to top
				if('<?php print $snav_to_top;?>'){
					//prepend item and apply item layout
					topItem['txt'] = '<span class="snav-title"><?php print $snav_to_top_txt;?></span>';
					topItem['subtxt'] = '<span class="snav-subtitle"><?php print $snav_to_top_subtxt;?></span>';
					topItem['icon'] = '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-<?php print $snav_to_top_icon;?>"></i></span>';

					topElem1	= (elem[0] !== 'none') ? topItem[elem[0]] : '';
					topElem2	= (elem[1] !== 'none') ? topItem[elem[1]] : '';
					topElem3	= (elem[2] !== 'none') ? topItem[elem[2]] : '';
					var topLi = '<li><a href="#" data-sntarget="" class="scroll-nav-anchor to-top">'  + topElem1 + topElem2  + topElem3 + '</a></li>';
					ul.prepend(topLi);
					//scroll to top animate
					$('a.to-top', ul).click(function(e){
						e.preventDefault();
						$("html, body").animate( { scrollTop: 0 }, <?php print $snav_speed; ?> );
					});
				}
				//add external link
				if('<?php print $snav_custom_link;?>'){
					customItem['txt'] = '<span class="snav-title"><?php print $snav_custom_txt;?></span>';
					customItem['subtxt'] = '<span class="snav-subtitle"><?php print $snav_custom_subtxt;?></span>';
					customItem['icon'] = '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-<?php print $snav_custom_icon;?>"></i></span>';

					customElem1	= (elem[0] !== 'none') ? customItem[elem[0]] : '';
					customElem2	= (elem[1] !== 'none') ? customItem[elem[1]] : '';
					customElem3	= (elem[2] !== 'none') ? customItem[elem[2]] : '';
					//prepend item and apply item layout

					var customLi = '<li><a href="' + '<?php print $snav_custom_link;?>' + '" target="_blank" class="scroll-nav-anchor snav-custom">'  + customElem1 + customElem2 + customElem3 + '</a></li>';
					ul.append(customLi);
				}
			});
			jQuery(window).load(function(){
				$ = jQuery;
				targetOffset  = stickyFix - targetOffset;
				//fix menu to top
				snavContainerHeight = snavContainer.outerHeight();
				menuOffset   		= canvasOffset + <?php print $snav_menu_offset; ?>;
				if( '<?php print $snav_page_offset; ?>' ) snavStickyWraper.height( snavContainerHeight );
				snavContainer.css({'top': '+=' + menuOffset, 'position':'fixed'});
				//active class
				snavLinks		= $('a[data-sntarget]', ul);
				snavLinks.each(function(){
					var me	= $(this);
					target	= '#' + me.data('sntarget');
					$(target).waypoint({handler: function(direction) {
					var snavLink     = $('a[data-sntarget=' + $(this).attr('id') + ']', ul);
					var snavLinkPrev = snavLink.closest('li').prev().children('a');
					if (direction === "up") snavLink = snavLinkPrev;
					snavLinks.removeClass('active');
					snavLink.addClass('active');
					},offset: targetOffset
					});
				});
				if( '<?php print $snav_editor; ?>' ) $('.pl-area-controls',snav).appendTo(snavContainer);
				//snavContainer.animate({"opacity":1},500);
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
					'key'		=> 'snav_item_count',
					'type'          	=> 'count_select',
					'count_start'   	=> '1',
					'default'		=> '4',
					'count_number'	=> '15',
					'label'    		=> __( 'Number of Items in the Menu', 'pagelines' )
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
					'label' 		=> __( 'Target Offset', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_speed',
					'type' 			=> 'text',
					'label' 		=> __( 'Scroll Speed', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_menu_offset',
					'type' 			=> 'text',
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
					'label' 		=> __( 'Scroll to Top Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_subtxt',
					'type' 			=> 'text',
					'label' 		=> __( 'Scroll to Top Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_to_top_icon',
					'type' 			=> 'select_icon',
					'label' 		=> __( 'Scroll to Top Icon', 'pagelines' ),
				)
			)
		);
		$options[] = array(
			'title' => __( 'Menu Items Config', 'pagelines' ),
			'key'	=> 'snav_items_config',
			'type'	=> 'multi',
			'col'	=> 3,
			'opts'	=> array(
			)
		);
		//
		$item_num = ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : 4;
		for($i = 1; $i <= $item_num; $i++){
			$options[4]["opts"][] = array(
				'title' => __( 'Custom Menu Content', 'pagelines' ),
				'key'	=> 'snav_multi',
				'type'	=> 'multi',
				'opts'	=> array(
					array(
						'key'			=> 'snav_item'.$i.'_header',
						'type' 			=> 'template',
						'template'		=> __( '<span style="font-size: 1.2em; font-weight:bold; color: white; display:block; margin-bottom: 15px; border-bottom: 1px solid white;">Menu Item '.$i.'</span>', 'pagelines' ),
					),
					array(
						'key'			=> 'snav_item'.$i.'_txt',
						'type' 			=> 'text',
						'label' 		=> __( 'Item '.$i.' Alternative Title', 'pagelines' ),
					),
					array(
						'key'			=> 'snav_item'.$i.'_subtxt',
						'type' 			=> 'text',
						'label' 		=> __( 'Item '.$i.' Subtitle', 'pagelines' ),
					),
					array(
						'key'			=> 'snav_item'.$i.'_icon',
						'type' 			=> 'select_icon',
						'label' 		=> __( 'Item '.$i.' Icon', 'pagelines' ),
					)
				)
			);
		}
		$options[] = array(
			'title' => __( 'Custom Link Item', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			'col'	=> 2,
			'opts'	=> array(
				array(
					'key'			=> 'snav_custom_link',
					'type' 			=> 'text',
					'label' 		=> __( 'Custom Link Adress', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_txt',
					'type' 			=> 'text',
					'label' 		=> __( 'Custom Link Title', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_subtxt',
					'type' 			=> 'text',
					'label' 		=> __( 'Custom Link Subtitle', 'pagelines' ),
				),
				array(
					'key'			=> 'snav_custom_icon',
					'type' 			=> 'select_icon',
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
}