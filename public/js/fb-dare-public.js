jQuery(document).ready(function( $ ) {

    if (typeof dare_events === 'undefined') {
        return;
    }

    // load FB pixel
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.agent='dvwpfbdare';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');

    /**
     * Setup Events Handlers
     */
    !function setupEventHandlers() {

    }();
    
    regularEvents();

    /**
     * Process Init, General, Search, Standard (except custom code), WooCommerce (except AJAX AddToCart, Affiliate and
     * PayPal events. In case if delay param is present - event will be fired after desired timeout.
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

});