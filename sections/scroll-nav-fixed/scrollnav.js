jQuery(document).ready(function() {
    
    //local vars
    var         $               =           jQuery;
    var         parseClass      =           '.scroll-header';
    var         plContent       =           $('#dynamic-content');
    var         ul              =           $('.scrollnav > ul');

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
            self.config = $.extend({},self.defaults,self.options);
            link = ul.find('a');
            link.on('click.scrollNav', $.proxy(self.handleClick, self));
        },
        handleClick: function(e){
            e.preventDefault();
            var targetData = $(e.currentTarget).data('sntarget');
            var targetOffset = $('#'+targetData).offset().top;
            $("html, body").animate({ scrollTop: targetOffset + self.config.scrollOffset }, self.config.scrollSpeed);
        }
    }
    //local wars
    $.fn.scrollNav = function(options) {
        return new ScrollNav(options).init();
    };
    
   
    //exec
    plContent.find(parseClass).each(function(){
        var me = $(this);
        myTitle = me.attr('title');
        myId = me.attr('id');
        ul.append('<li><a data-sntarget="' + myId + '" class="scroll-nav-anchor" href="#"><span class="snav-title snav-dom-title">' + myTitle + '</span></a></li>');
        
    });

});