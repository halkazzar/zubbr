function mycarousel_itemLoadCallback(carousel, state)
{
    // Check if the requested items already exist
    if (carousel.has(carousel.first, carousel.last)) {
        return;
    }
 
    $.get(
        '/include/_jobpostings.php',
        {
            first: carousel.first,
            last: carousel.last,
            invoker: 'job',
            ajax: true
        },
        function(xml) {
            mycarousel_itemAddCallback(carousel, carousel.first, carousel.last, xml);
        },
        'xml'
    );
};
 
function mycarousel_itemAddCallback(carousel, first, last, xml)
{
    // Set the size of the carousel
    carousel.size(parseInt($('total', xml).text()));
 
    $('result', xml).each(function(i) {
        var res_id = $(this).find('res_id').text();
        var res_data = $(this).find('res_data').text();
        
        carousel.add(first + i, mycarousel_getItemHTML(res_id, res_data));
        
        var h = $('.jcarousel-container-vertical').find('.result'+res_id).css('height');
    });
    
    
};
 
/**
 * Item html creation helper.
 */
function mycarousel_getItemHTML(res_id, res_data)
{
     var result = '<div class="result'+res_id+'"> </div>' + res_data; 
    return result;
};
 
$(document).ready(function() {
    $('#jobcarousel').jcarousel({
        // Uncomment the following option if you want items
        // which are outside the visible range to be removed
        // from the DOM.
        // Useful for carousels with MANY items.
 
        // itemVisibleOutCallback: {onAfterAnimation: function(carousel, item, i, state, evt) { carousel.remove(i); }},
        vertical: true,
        scroll: 1,
        itemLoadCallback: mycarousel_itemLoadCallback
    });
});