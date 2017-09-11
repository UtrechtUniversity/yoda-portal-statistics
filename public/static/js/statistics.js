$(document).ready(function() {
    $(".list-group-item.resource").click(function() {
        makeItemActive($(this));
        getDetails($(this).attr('data-name'));
    });

    $(".list-group-item.group").click(function() {
        makeItemActive($(this));
        getGroupDetails($(this).attr('data-name'));
    });
});

function makeItemActive(currentItem)
{
    if ($(currentItem).hasClass('resource')) {
        $('.list-group-item.resource').removeClass('active');
    } else if ($(currentItem).hasClass('group')) {
        $('.list-group-item.group').removeClass('active');
    }

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

function getGroupDetails(group)
{
    var url = "statistics/group_details?group=" + encodeURIComponent(group);
    $.getJSON(url, function( data ) {
        if (data.status == 'success') {

            $('.group-details').html(data.html);

            var ctx = $('.storage-data');
            var datasets = [];
            var labels = [];

            $.each(data.storageData.tiers, function( name, storageData ) {

                var storageChartData = [];
                $.each(data.storageData.months, function( index, month ) {
                    if ( $.inArray(month, labels) === -1) {
                        labels.push(month);
                    }

                    storageChartData.push(storageData[month]);
                });

                var tierObject = {
                    label: name,
                    data: storageChartData,
                    backgroundColor: randomColorGenerator()
                };

                datasets.push(tierObject);
            });

            var chartData = {
                labels: labels,
                datasets: datasets,
            };

            var chartOptions = {
                scales: {

                    xAxes: [{
                        barPercentage: 1,
                        categoryPercentage: 0.6
                    }],
                }
            };

            var chart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: chartOptions
            });
        }
    });
}

var randomColorGenerator = function () {
    return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
};

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
            if (data.status == 'Success') {
                $('#messages').html('<div class="alert alert-success"><button class="close" data-dismiss="alert"><span>×</span></button><p>Updated  ' + resource + ' properties.</p></div>');
            } else {
                $('#messages').html('<div class="alert alert-danger"><button class="close" data-dismiss="alert"><span>×</span></button><p>Could not update ' + resource + ' properties  due to an internal error.</p></div>');
            }

            $('.list-group-item.active .resource-tier').attr('title', htmlDecode(val));

            var text = val;
            if (text.length > 10) {
                text = val.substr(0, 10) + '...';
            }
            $('.list-group-item.active .resource-tier').text(htmlDecode(text));

            resetSubmitButton($('.update-resource-properties-btn'));
        });
}

function resetSubmitButton($el) {
    $el.removeClass('disabled').val('Update');
}

function htmlDecode(inp){
    var replacements = {'&lt;':'<','&gt;':'>','&sol;':'/','&quot;':'"','&apos;':'\'','&amp;':'&','&laquo;':'«','&raquo;':'»','&nbsp;':' ','&copy;':'©','&reg;':'®','&deg;':'°'};
    for(var r in replacements){
        inp = inp.replace(new RegExp(r,'g'),replacements[r]);
    }
    return inp.replace(/&#(\d+);/g, function(match, dec) {
        return String.fromCharCode(dec);
    });
}