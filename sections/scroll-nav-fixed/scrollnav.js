// ver: 1.1
var 	snavOpts	= {};

jQuery(document).ready(function() {
	//local vars
	var         parseClass	=           '.scroll-header';
	var         plContent	=           jQuery('#dynamic-content');
	var         ul		=           jQuery('.scrollnav > ul');
	//render items
	plContent.find(parseClass).each(function(){
		var me = jQuery(this);
		myTitle = me.attr('title');
		myId = me.attr('id');
		ul.append('<li><a data-sntarget="' + myId + '" class="scroll-nav-anchor" href="#" data-dom-title="'+ myTitle +'"></a></li>');
	});

	var ScrollNav = function(selectors, options){
		this.selectors = selectors;
		this.options = options;
	};
	//prototype
	ScrollNav.prototype = {
		defaults:{},
		config: {
			scrollSpeed: 800,
			scrollOffset: 0,
			easing: 'swing',
		},
		init: function() {
			snavOpts = this;
			snavOpts.renderElements();
			if(snavOpts.options.snav_to_top) snavOpts.toTop();
			if(snavOpts.options.snav_custom_link) snavOpts.customLink();
			snavOpts.calcHeights();
			snavOpts.selectors.link.on('click.scrollNav', jQuery.proxy(snavOpts.handleClick, snavOpts));
			if(snavOpts.selectors.sectionId === 'scroll-nav-fixed') snavOpts.snavFixed();
			if(snavOpts.selectors.sectionId === 'scroll-nav')snavOpts.snavAnimated();

		},
		renderElements: function(){
			if (snavOpts.selectors.ludItem.length !== 0) {
				//append/substitute menu item elements
				snavOpts.selectors.ludItem.each(function( i ){
					a = jQuery(this).find('a');
					//menu item element that uses jQuery('scroll-header[title]') atribute
					domTitle = a.data('domTitle') ;
					if(snavOpts.options.title_check[i] && domTitle !== 'undefined') snavOpts.options.snav_items['txt'][i] = '<span class="snav-title snav-dom-title">' + domTitle + '</span>';
					elem1	= (snavOpts.options.snav_elem[0] !== 'none') ? snavOpts.options.snav_items[snavOpts.options.snav_elem[0]][i] : '';
					elem2	= (snavOpts.options.snav_elem[1] !== 'none') ? snavOpts.options.snav_items[snavOpts.options.snav_elem[1]][i] : '';
					elem3	= (snavOpts.options.snav_elem[2] !== 'none') ? snavOpts.options.snav_items[snavOpts.options.snav_elem[2]][i] : '';
					a.append(elem1, elem2, elem3);
				});
			} else  {
				//no menu items
				if( snavOpts.options.snav_editor ){
					var snavInfo = '<li style="width:220px; padding-top: 4px; text-align: center;">Add scroll menu anchors to page.<a href="http://bestrag.net/scroll-nav/docs" class="btn tn-info" style="color: black; margin: 0 auto; width: 120px; padding: 0;">Find out how <i class="fa fa-external-link"></i></a></div>';
					snavOpts.selectors.wraper.append(snavInfo);
				}
			}
		},
		toTop: function(){
			topTxt = (snavOpts.options.snav_to_top_txt) ? snavOpts.options.snav_to_top_txt : 'To Top';
			topSub = (snavOpts.options.snav_to_top_subtxt) ? snavOpts.options.snav_to_top_subtxt : 'Subtitle';
			topIcon =  (snavOpts.options.snav_to_top_icon) ? snavOpts.options.snav_to_top_icon : 'home';
			var topItem = {};
			topItem['txt'] = '<span class="snav-title">'+topTxt+'</span>';
			topItem['subtxt'] = '<span class="snav-subtitle">'+topSub+'</span>';
			topItem['icon'] = '<span class="snav-icon-holder pl-animation pl-appear"><i class="fa fa-'+topIcon+'"></i></span>';

			topElem1	= (snavOpts.options.snav_elem[0] !== 'none') ? topItem[snavOpts.options.snav_elem[0]] : '';
			topElem2	= (snavOpts.options.snav_elem[1] !== 'none') ? topItem[snavOpts.options.snav_elem[1]] : '';
			topElem3	= (snavOpts.options.snav_elem[2] !== 'none') ? topItem[snavOpts.options.snav_elem[2]] : '';
			//prepend item and apply item layout
			var topLi = '<li><a href="#" data-sntarget="" class="scroll-nav-anchor to-top">'  + topElem1 + topElem2  + topElem3 + '</a></li>';
			ul.prepend(topLi);
			//scroll to top animate
			jQuery('a.to-top', ul).click(function(e){
				e.preventDefault();
				jQuery("html, body").animate( { scrollTop: 0 }, snavOpts.options.snav_speed );
			});
		},
		customLink:function(){
			customTxt = (snavOpts.options.snav_custom_txt) ? snavOpts.options.snav_custom_txt : 'External';
			customSub = (snavOpts.options.snav_custom_subtxt) ? snavOpts.options.snav_custom_subtxt : 'Subtitle';
			customIcon =  (snavOpts.options.snav_custom_icon) ? snavOpts.options.snav_custom_icon : 'external-link';
			var customItem = {};
			customItem['txt'] = '<span class="snav-title">'+customTxt+'</span>';
			customItem['subtxt'] = '<span class="snav-subtitle">'+customSub+'</span>';
			customItem['icon'] = '<span class="snav-icon-holder pl-animation pl-appear"><i class="fa fa-'+customIcon+'"></i></span>';

			customElem1	= (snavOpts.options.snav_elem[0] !== 'none') ? customItem[snavOpts.options.snav_elem[0]] : '';
			customElem2	= (snavOpts.options.snav_elem[1] !== 'none') ? customItem[snavOpts.options.snav_elem[1]] : '';
			customElem3	= (snavOpts.options.snav_elem[2] !== 'none') ? customItem[snavOpts.options.snav_elem[2]] : '';
						//prepend item and apply item layout

			var customLi = '<li><a href="' + snavOpts.options.snav_custom_link + '" target="_blank" class="scroll-nav-anchor snav-custom">'  + customElem1 + customElem2 + customElem3 + '</a></li>';
			ul.append(customLi);
		},
		calcHeights: function(){
			snavOpts.options.canvasOffset		= jQuery('#page div.page-canvas').offset().top;
			snavOpts.options.snavHeight			= snavOpts.selectors.sectionClone.outerHeight();
			snavOpts.options.snavContainerHeight	= snavOpts.selectors.container.outerHeight();
			snavOpts.options.targetOffset		= - (snavOpts.options.canvasOffset*1 + snavOpts.options.snav_target_offset*1 + snavOpts.options.snavHeight*1);
			snavOpts.options.stickyFix			= 10;

		},
		handleClick: function(e){
			e.preventDefault();
			var targetData = jQuery(e.currentTarget).data('sntarget');
			var targetOffset = jQuery('#'+targetData).offset().top;
			jQuery("html, body").animate({ scrollTop: targetOffset + this.options.targetOffset  +'px'}, this.options.snav_speed);
		},
		snavFixed: function () {
			fixedOffset   		= snavOpts.options.canvasOffset + snavOpts.options.snav_menu_offset;
			if( snavOpts.options.snav_page_offset ) snavOpts.selectors.StickyWraper.css({ 'height': snavOpts.options.snavContainerHeight });
			snavOpts.selectors.container.css({'top': '+=' + fixedOffset, 'position':'fixed'});
			if( snavOpts.options.snav_editor ) jQuery('.pl-area-controls',snavOpts.selectors.sectionClone).appendTo(snavOpts.selectors.container);
			//snavContainer.animate({"opacity":1},500);
		},
		snavAnimated: function (){
			if(!snavOpts.options.snav_animated){
				menuOffset   	= snavOpts.options.canvasOffset + snavOpts.options.snav_menu_offset + snavOpts.options.stickyFix;
				snavOpts.selectors.container.waypoint('sticky',{ offset: menuOffset }).css('top', snavOpts.options.canvasOffset);
			}else{
				menuOffset   	= snavOpts.options.canvasOffset + snavOpts.options.snav_menu_offset - snavOpts.options.snavContainerHeight - 20;
				snavOpts.selectors.container.css('top', menuOffset);
				snavOpts.selectors.StickyWraper.waypoint({
					handler: function(direction) {
						if (direction == 'down') {
							snavOpts.selectors.StickyWraper.css({ 'height': snavOpts.options.snavContainerHeight });
							snavOpts.selectors.container.stop().addClass("stuck").delay(200).animate({"top":snavOpts.options.canvasOffset},600);
						} else {
							snavOpts.selectors.StickyWraper.css({ 'height':'auto' });
							snavOpts.selectors.container.stop().animate({"top":menuOffset},600).removeClass("stuck");
						}
					},
					offset: menuOffset
				});
			}
		}
	};
	//local wars
	jQuery.fn.scrollNav = function(selectors,options) {
		return new ScrollNav(selectors, options).init();
	};
});
jQuery(window).load(function(){
	targetOffset  = snavOpts.options.stickyFix - snavOpts.options.targetOffset;
	//active class
	snavLinks	= jQuery('a[data-sntarget]', snavOpts.selectors.wraper);
	snavLinks.click(function(){
		var me	= jQuery(this);
		target	= '#' + me.data('sntarget');
		jQuery(target).waypoint({handler: function(direction) {
			var snavLink     = jQuery('a[data-sntarget=' + jQuery(this).attr('id') + ']', snavOpts.selectors.wraper);
			var snavLinkPrev = snavLink.closest('li').prev().children('a');
			if (direction === "up") snavLink = snavLinkPrev;
			snavLinks.removeClass('active');
			snavLink.addClass('active');
			},offset: targetOffset
		});
	});
});