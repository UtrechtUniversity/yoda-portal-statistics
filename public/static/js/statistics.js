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

            // Select2 plugin - Select tier
            select2Tier();

            $( "#resource-properties-form" ).submit(function( event ) {
                event.preventDefault();
                $('.update-resource-properties-btn').addClass('disabled').val('Updating...');

                var value = $('.tier-select').data('select2').val();
                editTier(resource, value);
            });
        }
    });
}

function select2Tier()
{
    $('.tier-select').select2({
        ajax: {
            delay:    250,
            url:      YodaPortal.baseUrl + 'statistics/get_tiers',
            type:     'get',
            dataType: 'json',
            data: function (term, page) {
                return { query: term };
            },
            results: function (tiers) {
                var results = [];
                var query   = $('.tier-select').data('select2').search.val();
                var inputMatches = false;

                tiers.forEach(function(tier) {
                    if (query === tier) {
                        inputMatches = true;
                    }
                    results.push({
                        id:   tier,
                        text: tier
                    });
                });
                if (!inputMatches && query.length) {
                    results.push({
                        id:     query,
                        text:   query,
                        exists: false
                    });
                }

                return { results: results };
            },
        },
        formatResult: function(result, $container, query, escaper) {
            return escaper(result.text)
                + (
                    'exists' in result && !result.exists
                        ? ' <span class="grey">(create)</span>'
                        : ''
                );
        },
        initSelection: function($el, callback) {
            callback({ id: $el.val(), text: $el.val() });
        }
    });
}

function editTier(resource, val)
{
    var tokenName = YodaPortal.csrf.tokenName;
    var tokenValue = YodaPortal.csrf.tokenValue;
    $.post( YodaPortal.baseUrl + 'statistics/edit_tier', { resource: resource, value: val, csrf_yoda: tokenValue})
        .done(function( data ) {
            var data = $.parseJSON(data);
            if (data.status == 'SUCCESS') {
                $('#messages').html('<div class="alert alert-success"><button class="close" data-dismiss="alert"><span>×</span></button><p>Updated  ' + resource + ' properties.</p></div>');
            } else {
                $('#messages').html('<div class="alert alert-danger"><button class="close" data-dismiss="alert"><span>×</span></button><p>Could not update ' + resource + ' properties  due to an internal error.</p></div>');
            }

            resetSubmitButton($('.update-resource-properties-btn'));
        });
}

function resetSubmitButton($el) {
    $el.removeClass('disabled').val('Update');
}