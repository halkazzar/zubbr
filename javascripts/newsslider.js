function mycarousel_itemLoadCallback(carousel, state){
    // Check if the requested items already exist
    if (carousel.has(carousel.first, carousel.last)){
        return;
    }
 
    var tag = $("#newscarousel ul").attr("id");
 
    $.get(
        '/include/_news.php',
        {
            first: carousel.first,
            last: carousel.last,
            invoker: 'news',
            ajax: true,
            tag_id: tag 
        },
        function(xml){
            mycarousel_itemAddCallback(carousel, carousel.first, carousel.last, xml);
        },
        'xml'
    );
};
 
function mycarousel_itemAddCallback(carousel, first, last, xml){
    // Set the size of the carousel
    carousel.size(parseInt($('total', xml).text()));
 
    $('result', xml).each(function(i) {
        var res_id = $(this).find('res_id').text();
        var res_data = $(this).find('res_data').text();
        
        carousel.add(first + i, mycarousel_getItemHTML(res_id, res_data));
        
    });
    
    
};
 
/**
 * Item html creation helper.
 */
function mycarousel_getItemHTML(res_id, res_data){
    var result = '<div class="result'+res_id+'"> </div>' + res_data; 
    return result;
};
 
$(document).ready(function() {
    $('#newscarousel').jcarousel({
        vertical: true,
        visible: 2,
        itemLoadCallback: mycarousel_itemLoadCallback
    });
});