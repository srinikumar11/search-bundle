function applyTypeAhead(objectId){
    var searchKeywordWidget = $(objectId);
    var engine = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('title'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/autocomplete/search'+'/%QUERY',
            wildcard: '%QUERY'
        },
        limit               : 10
    });

    engine.initialize();

    searchKeywordWidget.typeahead({
            hint        : true,
            highlight   : true,
            minLength   : 1,
            limit       : 10
        },
        {
            name: 'engine',
            displayKey: 'title',
            source: engine.ttAdapter(),
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'No matching keywords found',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<div><a class="clearfix" href="javascript:void(0);">{{title}} - {{path}}</a></div>')
            }
        });
}

$(document).ready(function(){
    applyTypeAhead('#beecms-search-input');
});


