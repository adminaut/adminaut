(function($){
    $.fn.adminautDataTable = function(options) {
        var defaultOptions = {
            paging : true,
            lengthChange : true,
            searching : true,
            ordering : true,
            info : true,
            autoWidth : false,
            scrollX : true,
            pageLength : 10,
            searchDelay : 1000,
            responsive : false,
            columns : null,
            processing : true,
            serverSide : true,
            initComplete : function( settings, json ) {
                var api = this.api();

                if(filterable.length) {
                    var $tableFilter = $('<tr class="table-filter"></tr>').appendTo($('#'+ $table.attr('id') +'_wrapper').find('thead:first'));
                    $.each(options.columns, function(index, column){
                        if(column.visible === false) {
                            $('<td style="display:none!important;"></td>').appendTo($tableFilter);
                            return;
                        }

                        var filterCell = null;
                        if(column.filterable) {
                            filterCell = $('<td><select></select></td>')
                                .appendTo($tableFilter)
                                .find('select').select2().on('change', function () {
                                    var select = $(this);
                                    if(select.find('option').length > 0) {
                                        var val = select.val();
                                        select.data('last-selected', val);
                                        api.column(index).search(val, false, false).draw();
                                    }
                                });

                            filterCell = filterCell.parents('td');
                        } else {
                            filterCell = $('<td></td>').appendTo($tableFilter);
                        }

                        if(initResponsiveColumns[index] === false || initResponsiveColumns[index] === '-') {
                            filterCell.addClass("hidden");
                        }
                    });
                }
            },
            ajax: {
                method : "POST",
                deferRender : true,
                complete: function(response) {
                    var filters = response.responseJSON.filters;

                    if ( filters !== undefined ) {
                        $.each(filters, function (index, filterOptions) {
                            var $filterSelect = $('#'+ $table.attr('id') +'_wrapper').find('.table-filter td:nth-child(' + (parseInt(index) + 1) + ') select').html('<option value="">All</option>');
                            $.each(filterOptions, function (i, filterOption) {
                                if (typeof filterOption === "Object" || typeof filterOption === "object") {
                                    $filterSelect.append('<option value="' + filterOption.id + '"' + (filterOption.id == $filterSelect.data('last-selected') ? ' selected' : '') + '>' + filterOption.name + '</option>');
                                } else {
                                    $filterSelect.append('<option value="' + filterOption + '"' + (filterOption == $filterSelect.data('last-selected') ? ' selected' : '') + '>' + filterOption + '</option>');
                                }
                            });
                        });
                        $table.data('get-filter', false);
                    }
                }
            }
        };

        options = deepmerge(defaultOptions, options);

        var $table = $(this).data('get-filter', true);
        var initResponsiveColumns = [];
        var filterable = [];

        $.each(options.columns, function(index, column){
            if(column.filterable === true) {
                filterable.push(index);
            }
        });

        $table.DataTable(options)
            .on( 'responsive-resize.dt', function ( e, datatable, columns ) {
                $.each(columns, function(index, column){
                    var $filterRow = $table.find('.table-filter');

                    if(column === false || column === '-') {
                        $filterRow.find('td:nth-child('+ (parseInt(index)+1) +')').addClass("hidden");
                    } else {
                        $filterRow.find('td:nth-child('+ (parseInt(index)+1) +')').removeClass("hidden");
                    }
                });

                initResponsiveColumns = columns;
            } );

        $(document).on('init.dt', function(e, settings){
            var api = new $.fn.dataTable.Api(settings);
            var searchTimer = null;

            $('.dataTables_filter input').unbind().bind("input", function(e) {
                if(this.value.length >= 2 || e.keyCode == 13) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function(){$table.data('get-filter', true); api.search($('.dataTables_filter input')[0].value).draw();}, searchDelay);
                }

                if(this.value == "") {
                    clearTimeout(searchTimer);
                    $table.data('get-filter', true);
                    api.search("").draw();
                }
                return;
            });
        });

        return $table;
    }
})(jQuery);