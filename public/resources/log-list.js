class LogList
{
    url = '';

    constructor()
    {
    }

    applyFilters(filters)
    {
        window.location.href = location.protocol + '//' + this.url + '/' + filters.join('/');
    }

    formatDateTime(value)
    {
        let date = new Date(value);

        return date.getFullYear() + '-'
            + ('0' + (date.getMonth() + 1)).slice(-2) + '-'
            + ('0' + date.getDate()).slice(-2) + ' '
            + ('0' + date.getHours()).slice(-2) + ':'
            + ('0' + date.getMinutes()).slice(-2) + ':'
            + ('0' + date.getSeconds()).slice(-2) + '.'
            + ('00' + date.getMilliseconds()).slice(-3);
    }

    parseFilters()
    {
        let filters = [];
        let genIds = [];

        $('#generators').find('input').each(function()
        {
            if ($(this).is(':checked'))
                genIds.push($(this).val());
        });

        if (genIds.length > 0)
            filters.push('generatorId=' + genIds.join(','));

        if ($('#date-from').val())
            filters.push('dateFrom=' + logList.formatDateTime($('#date-from').val()));

        if ($('#date-to').val())
            filters.push('dateTo=' + logList.formatDateTime($('#date-to').val()));

        return filters;
    }
}

$(".checkbox-menu").on("change", "input[type='checkbox']", function()
{
    $(this).closest("li").toggleClass("active", this.checked);
});

$(document).on('click', '.allow-focus', function (e)
{
    e.stopPropagation();
});

var logList = new LogList();

$(function()
{
    $('#date-from').datetimepicker({
        timeFormat: "HH:mm:ss.l"
    });

    $('#date-to').datetimepicker({
        timeFormat: "HH:mm:ss.l"
    });

    $('#btn-apply-filters').click(function(){
        let filters = logList.parseFilters();
        filters.push('page=1');
        logList.applyFilters(filters);
    });

    $('#select-page').change(function(){
        let filters = logList.parseFilters();
        filters.push('page=' + $(this).val());
        logList.applyFilters(filters);
    });
});