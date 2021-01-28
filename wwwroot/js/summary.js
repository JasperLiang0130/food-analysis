function updateSummary(summary) {
    $(".summary p").empty().each(function (index, value) {
        $(this).append(summary[index]);
    });
}