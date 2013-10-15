<?php
/*
	Section: Scroll Nav Fixed
	Author: bestrag
	Author URI: http://bestrag.net
	Class Name: ScrollNavFixed
	Demo: http://bestrag.net/scroll-nav/demo
	Description: Scroll Nav helps users build a custom one-page navigation menu. It offers full style control with different layout options, is easy to customize, and can be placed anywhere!
	Version: 3.1
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
		$snav_layout_string	= ($this->opt('snav_layout')) ? ($this->opt('snav_layout')) : 'txt subtxt icon';
		$snav_layout		= explode(" ", $snav_layout_string);
		//put all menu item elements into arrays
		$snav_item_count	= ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : 4;
		$snav_icon_array	= array();
		$snav_txt_array		= array();
		$snav_subtxt_array	= array();
        for($i = 1; $i <= $snav_item_count; $i++){
			$snav_icon_array[$i]	= ($this->opt('snav_item'.$i.'_icon')) ? '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-'.$this->opt('snav_item'.$i.'_icon').'"></i></span>' : '';
			$snav_txt_array[$i]		= ($this->opt('snav_item'.$i.'_txt')) ? '<span class="snav-title">'.$this->opt('snav_item'.$i.'_txt').'</span>' : '';
			$snav_subtxt_array[$i]	= ($this->opt('snav_item'.$i.'_subtxt')) ? '<span class="snav-subtitle">'.$this->opt('snav_item'.$i.'_subtxt').'</span>' : '';
        }
		?>
		<script type="text/javascript">
			var snav				= '';
			var snavStickyWraper	= '';
			var snavContainer		= '';
			var ul					= '';
			var canvasOffset		= '';
			var snavContainerHeight	= '';
			var targetOffset		= '';
			var menuOffset			= '';
			var snavLinks			= '';
			var stickyFix			= 10;
			var animated			= '';
			jQuery(document).ready(function(){
				$ = jQuery;
				//get menu item elements
				var layout			= <?php echo json_encode($snav_layout); ?>;
				var snavTxt			= <?php echo json_encode($snav_txt_array); ?>;
				var snavSubtxt		= <?php echo json_encode($snav_subtxt_array); ?>;
				var snavIcon		= <?php echo json_encode($snav_icon_array); ?>;
				//copy vars from dom
				snav				= $('section#scroll-nav-fixed'+'<?php echo $this->meta['clone']; ?>');
				snavStickyWraper	= $('.pl-area-pad', snav);
				snavContainer		= $('.scrollnav', snav);
				ul					= $('ul.nav', snavContainer);
				//offset calc		- for menu positioning and target offset
				canvasOffset		= $('#page div.page-canvas').offset().top;
				targetOffset		= - (canvasOffset + <?php print $snav_target_offset; ?> + 85);
				//initialize scrollNav()
				snavContainer.scrollNav({
						scrollSpeed:    <?php print $snav_speed; ?> ,
						scrollOffset:   targetOffset,
				});
				//append/substitute menu item elements
				ul.children().each(function( i ){
					i++;
					a = $(this).find('a');
					//menu item element that uses $('scroll-header[title]') atribute
					domTitle = $('span.snav-dom-title',a);
					//append item elements and apply item layout
					if( $.inArray('txt', layout) !== -1 ){
						if( snavTxt[i] ) domTitle.hide();
					}else{
						domTitle.hide();
						snavTxt[i] = '';
					}
					if($.inArray('subtxt', layout)	=== -1) snavSubtxt[i] = '';
					if($.inArray('icon', layout)	=== -1) snavIcon[i] = '';
					a.prepend(snavIcon[i]).append(snavTxt[i], snavSubtxt[i]);
				});
				//add scroll to top
				if('<?php print $snav_to_top;?>'){
					var topTxt = '';
					var topSubtxt = '';
					var topIcon = '';
					//prepend item and apply item layout
					if(($.inArray('txt', layout) 	!== -1) && '<?php print $snav_to_top_txt;?>'){
						topTxt		= '<span class="snav-title"><?php print $snav_to_top_txt;?></span>';
					}
					if(($.inArray('subtxt', layout)	!== -1) && '<?php print $snav_to_top_subtxt;?>'){
						topSubtxt	= '<span class="snav-subtitle"><?php print $snav_to_top_subtxt;?></span>';
					}
					if(($.inArray('icon', layout) 	!== -1) && '<?php print $snav_to_top_icon;?>'){
						topIcon		= '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-<?php print $snav_to_top_icon;?>"></i></span>';
					}
					var topItem = '<li><a href="#" data-sntarget="" class="scroll-nav-anchor to-top">' + topIcon + topTxt + topSubtxt + '</a></li>';
					ul.prepend(topItem);
					//scroll to top animate
					$('a.to-top', ul).click(function(e){
						e.preventDefault();
						$("html, body").animate( { scrollTop: 0 }, <?php print $snav_speed; ?> );
					});
				}
				//add external link
				if('<?php print $snav_custom_link;?>'){
					var customTxt		= '';
					var customSubtxt	= '';
					var customIcon		= '';
					if(($.inArray('txt', layout) 	!== -1) && '<?php print $snav_custom_txt;?>'){
						customTxt		= '<span class="snav-title"><?php print $snav_custom_txt;?></span>';
					}
					if(($.inArray('subtxt', layout)	!== -1) && '<?php print $snav_custom_subtxt;?>'){
						customSubtxt	= '<span class="snav-subtitle"><?php print $snav_custom_subtxt;?></span>';
					}
					if(($.inArray('icon', layout) 	!== -1) && '<?php print $snav_custom_icon;?>'){
						customIcon		= '<span class="snav-icon-holder pl-animation pl-appear"><i class="icon-<?php print $snav_custom_icon;?>"></i></span>';
					}
					var customItem = '<li><a href="' + '<?php print $snav_custom_link;?>' + '" target="_blank" class="scroll-nav-anchor snav-custom">' + customIcon + customTxt + customSubtxt + '</a></li>';
					ul.append(customItem);
				}
			});
			jQuery(window).load(function(){
				$ = jQuery;
				targetOffset  = stickyFix - targetOffset;
				//fix menu to top
				snavContainerHeight	= snavContainer.innerHeight();
				menuOffset   		= canvasOffset + <?php print $snav_menu_offset; ?>;
				if( '<?php print $snav_page_offset; ?>' ) snavStickyWraper.height( outerHeight(snavContainer) );
				snavContainer.css({'top': '+=' + menuOffset, 'position':'fixed'});
				//active class
				snavLinks		= $('a[data-sntarget]', ul);
				snavLinks.each(function(){
					var me	= $(this);
					target	= '#' + me.data('sntarget');
			        $(target).waypoint({handler: function(direction) {
						var snavLink     = $('a[data-sntarget=' + $(this).attr('id') + ']', ul);
						console.log(me, snavLink);
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
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			'span'	=> 2,
			'opts'	=> array(
				array(
					'key'			=> 'snav_template',
					'type' 			=> 'select',
					'default'		=> $this->default_template,
					'label' 		=> __( 'Scroll Nav Template', 'pagelines' ),
					'opts'			=> $this->get_template_selectvalues(),
				),
				array(
                    'key'			=> 'snav_item_count',
                    'type'          => 'count_select',
                    'count_start'   => '1',
                    'default'		=> '4',
                    'count_number'  => '15',
                    'label'    => __( 'Number of Items in the Menu', 'pagelines' )
                ),
                array(
					'type' 			=> 'select',
					'key'			=> 'snav_layout',
					'default'		=> 'icon txt subtxt',
					'label' 		=> __( 'Menu Items Layout', 'pagelines' ),
					'opts'=> array(
						'txt'               => array( 'name' => __( 'Title Menu', 'pagelines' ) ),
						'txt subtxt'        => array( 'name' => __( 'Title and Subtitle Menu', 'pagelines' ) ),
						'icon'              => array( 'name' => __( 'Icon Menu', 'pagelines' ) ),
						'icon txt'          => array( 'name' => __( 'Icon and Title Menu', 'pagelines' ) ),
						'icon txt subtxt'   => array( 'name' => __( 'All (default)', 'pagelines' ) )
					)
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
			'title' => __( 'Scroll Nav Config', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			//'span'	=> 2,
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
					'label' 		=> __( 'Push page content bellow Scroll Nav', 'pagelines' ),
				)
			)
		);
		$options[] = array(
			'title' => __( 'Scroll To Top', 'pagelines' ),
			'key'	=> 'snav_conf',
			'type'	=> 'multi',
			//'span'	=> 2,
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
			'span'	=> 2,
			'opts'	=> array(
			)
		);
		//
		$item_num = ($this->opt('snav_item_count')) ? $this->opt('snav_item_count') : 4;
		for($i = 1; $i <= $item_num; $i++){
            $options[3]["opts"][] = array(
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
			//'span'	=> 2,
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

	/* section_persistent */
	function section_persistent(){
		//add_action( 'template_redirect',array(&$this, 'snav_less') );
	}

	/* handle less template */
	function snav_less(){
		$template 		= ($this->meta['set']['snav_template']) ? $this->meta['set']['snav_template'] : $this->default_template;
		$template_file 	= sprintf('%s/less/%s.less', $this->base_dir, $template);
		pagelines_insert_core_less( $template_file );
	}
}