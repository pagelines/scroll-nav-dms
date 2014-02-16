jQuery(document).ready(function() {

	//local vars
	//var         $               =           jQuery;
	var         parseClass      =           '.scroll-header';
	var         plContent       =           jQuery('#dynamic-content');
	var         ul              =           jQuery('.scrollnav > ul');

	var ScrollNav = function(options){
		this.options = options;
	};

	//prototype
	ScrollNav.prototype = {
		defaults: {
			scrollSpeed: 800,
			scrollOffset: 0,
			easing: 'swing',
		},
		init: function() {
			self = this;
			self.config = jQuery.extend({},self.defaults,self.options);
			link = ul.find('a');
			link.on('click.scrollNav', jQuery.proxy(self.handleClick, self));
		},
		handleClick: function(e){
			e.preventDefault();
			var targetData = jQuery(e.currentTarget).data('sntarget');
			var targetOffset = jQuery('#'+targetData).offset().top;
			jQuery("html, body").animate({ scrollTop: targetOffset + this.config.scrollOffset  +'px'}, this.config.scrollSpeed);
		}
	}
	//local wars
	jQuery.fn.scrollNav = function(options) {
		return new ScrollNav(options).init();
	};


	//exec
	plContent.find(parseClass).each(function(){
		var me = jQuery(this);
		myTitle = me.attr('title');
		myId = me.attr('id');
		ul.append('<li><a data-sntarget="' + myId + '" class="scroll-nav-anchor" href="#" data-dom-title="'+ myTitle +'"></a></li>');
	});

});