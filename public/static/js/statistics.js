$(document).ready(function() {
    $(".list-group-item.resource").click(function() {
        makeItemActive($(this));
        getDetails($(this).attr('data-name'));
    });
});

function makeItemActive(currentItem)
{
    $('.list-group-item.resource').removeClass('active');
    $(currentItem).addClass('active');
}

function getDetails(resource)
{
    var url = "statistics/resource_details?resource=" + encodeURIComponent(resource);
    $.getJSON(url, function( data ) {
        if (data.status == 'success') {
            $('.resource-details').html(data.html);
        }
    });
}

