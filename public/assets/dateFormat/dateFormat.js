function getdateFormat(date)
{
    var today = new Date(date);
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
    dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    return yyyy + '-' + mm + '-' + dd;
}

function addDays(date,days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
}

function subtractDays(date,days) {
    var result = new Date(date);
    result.setDate(result.getDate() - days);
    return result;
}
