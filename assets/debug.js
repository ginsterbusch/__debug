jQuery( function() {
	var strClassHideDebug = 'hide-debug-content';
	
	if( jQuery( '.theme__debug .debug-content' ).length > 0 ) {
		jQuery( '.theme__debug .debug-content' ).addClass( strClassHideDebug );
		
		jQuery( '.theme__debug').on('click', '.debug-content', function() {
			var classList = this.getAttribute('class');
			
			if( classList.indexOf( strClassHideDebug ) > 0 ) {
				jQuery(this).removeClass( strClassHideDebug );
			}
		});
	}
});


	
