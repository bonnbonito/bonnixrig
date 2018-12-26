/* create load more function */
( function( $ ) {
	// eslint-disable-next-line lines-around-comment
	// Expressions
	$( document ).ready( function() {
		$( document ).on( 'click', '.loadmore_btn', function() {
			var button = $( this ),
				paged = button.data( 'page' ),
				data = {
				'action': 'loadmore',
				'page': loadmoreBtnParams.current_page
			};
			console.log( data );
			$.ajax({ // you can also use $.post here
				url: loadmoreBtnParams.ajaxurl, // AJAX handler
				data: data,
				type: 'POST',
				beforeSend: function( xhr ) {
					button.text( 'Loading...' ); // change the button text, you can also add a preloader image
				},
				success: function( data ) {
					console.log( data );
					if ( data ) {

						button.text( 'More posts' ).prev().before( data.post ); // insert new posts
						//loadmoreBtnParams.current_page++;
						//if ( loadmoreBtnParams.current_page == loadmoreBtnParams.max_page ) {
							//button.remove(); // if last page, remove the button
						// you can also fire the "post-load" event here if you use a plugin that requires it
						// $( document.body ).trigger( 'post-load' );
						//}
					} else {

						//button.remove(); // if no data, remove the button as well.
					}
				}
			});
		});
	});
}( jQuery ) );
