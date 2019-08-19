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

                $.each(options.columns, function(index, column) {
                    $('.dataTables_scrollHead .table-filter td:nth-child('+ (index+1) +') select').select2().on('change', function () {
                        var select = $(this);
                        if(select.find('option').length > 0) {
                            var val = select.val();
                            select.data('last-selected', val);
                            api.column(index).search(val, false, false).draw();

                            if (select.val() !== "") {
                                select.parents('td').find('.select2-selection').css('background', '#fffaca');
                            } else {
                                select.parents('td').find('.select2-selection').removeAttr('style');
                            }
                        }
                    });
                });
            },
            ajax: {
                method : "POST",
                deferRender : true,
                complete: function(response) {
                    var api = $table.DataTable();
                    var filters = response.responseJSON.filters;

                    if ( filters !== undefined ) {
                        $.each(filters, function (index, filterOptions) {
                            var column = api.columns(index);
                            var columnIndex = $(column.header()).index() + 1;

                            if ( columnIndex !== -1 ) {
                                var $filterSelect = $('#' + $table.attr('id') + '_wrapper').find('.table-filter td:nth-child(' + columnIndex + ') select').html('<option value="">All</option>');
                                $.each(filterOptions, function (i, filterOption) {
                                    if (typeof filterOption === "Object" || typeof filterOption === "object") {
                                        $filterSelect.append('<option value="' + filterOption.id + '"' + (filterOption.id == $filterSelect.data('last-selected') ? ' selected' : '') + '>' + filterOption.name + '</option>');
                                    } else {
                                        $filterSelect.append('<option value="' + filterOption + '"' + (filterOption == $filterSelect.data('last-selected') ? ' selected' : '') + '>' + filterOption + '</option>');
                                    }

                                    if (i === filterOptions.length - 1) {
                                        api.columns.adjust();
                                    }
                                });
                            }
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
            var $tableFilter = $table.find('tr.table-filter').length ? $table.find('tr.table-filter') : $('<tr class="table-filter"></tr>').prependTo($table.find('thead'));

            if(column.filterable === true) {
                var filterCell = $('<td><select></select></td>').appendTo($tableFilter);
            } else {
                $('<td></td>').appendTo($tableFilter);
            }
        });

        $table.DataTable(options);

        $(document).on('init.dt', function(e, settings){
            var api = new $.fn.dataTable.Api(settings);
            var searchTimer = null;

            $('.dataTables_filter input').unbind().bind("input", function(e) {
                if(this.value.length >= 2 || e.keyCode == 13) {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function(){$table.data('get-filter', true); api.search($('.dataTables_filter input')[0].value).draw();}, 500);
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