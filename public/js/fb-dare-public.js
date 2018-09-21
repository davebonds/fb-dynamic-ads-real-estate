jQuery(document).ready(function( $ ) {

    if (typeof dare_events === 'undefined') {
        return;
    }
    
    regularEvents();

    /**
     * Process Init, PageView, ViewContent, and Search
     * In case if delay param is present - event will be fired after desired timeout.
     */
    function regularEvents() {

        for( var i = 0; i < dare_events.length; i++ ) {

            var eventData = dare_events[i];

            if( eventData.hasOwnProperty('delay') == false || eventData.delay == 0 ) {

                fbq( eventData.type, eventData.name, eventData.params );

            } else {

                setTimeout( function( type, name, params ) {
                    fbq( type, name, params );
                }, eventData.delay * 1000, eventData.type, eventData.name, eventData.params );

            }

        }

    }

    // Favorite button. Sends InitiateCheckout event.
    $(FBDARE.initiate_checkout).click(function(e){

        for( var i = 0; i < dare_events.length; i++ ) {

            var eventData = dare_events[i];
            //console.log(eventData);
            if( eventData.name == 'ViewContent' ) {
                fbq( 'track', 'InitiateCheckout', eventData.params );
            }
        }

    });

    // Property contact form button. Sends Purchase event.
    $(FBDARE.purchase).click(function(e){

        for( var i = 0; i < dare_events.length; i++ ) {

            var eventData = dare_events[i];
            //console.log(eventData);
            if( eventData.name == 'ViewContent' ) {
                fbq( 'track', 'Purchase', eventData.params );
            }
        }

    });

});