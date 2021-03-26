jQuery( '.single-movie .hover' ).click( function(){
  jQuery( this ).parent().toggleClass( 'active' );
  jQuery( "input.addToFavorites" ).change( function(){
      jQuery( '.single-movie.active' ).toggleClass( 'checked' );
  })
});

jQuery( '.close' ).click(function(event){
    jQuery( '.single-movie' ).removeClass( 'active' );
});

jQuery( '#pageSubmit').on( 'click', function ( event ) {
    var myVal = jQuery( '#pageSelect' ).val();
    jQuery( 'form' ).attr( 'action', function( i, value ) {
        return value + myVal;
    });
});

jQuery( "input.addToFavorites" ).change( function(){
    jQuery( this ).parent().toggleClass( "active", this.checked );
});