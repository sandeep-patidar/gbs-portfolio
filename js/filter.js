jQuery('#filters li a').click(function(){
	jQuery(this).addClass('active');
	return false;
});

jQuery(document).ready(function () {
var filterList = {		
			init: function () {
				jQuery('#portfoliolist').mixItUp({
  				selectors: {
    			  target: '.portfolio',
    			  filter: '.filter'	
    		  }, 
			});								
			}
			// Run the show!	
		}
	filterList.init();
});

(function($) {
  // Open Lightbox
  $( ".zoom_btn" ).click(function(e) {
    var image = $(this).prev('.hovereffect img').attr('src');
    $('html').addClass('no-scroll');
    $('body').append('<div class="lightbox-opened"><img src="' + image + '"></div>');
  });
  
  // Close Lightbox
    $('body').on('click', '.lightbox-opened', function() {
    $('html').removeClass('no-scroll');
    $('.lightbox-opened').remove();
  });
  
})(jQuery);

