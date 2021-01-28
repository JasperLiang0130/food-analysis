// Menu Toggle Script
$("#menu-toggle").click(function (e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
    if ($("#menu-toggle").text() == 'Hide Menu') {
        $("#menu-toggle").text("Show Menu");
    } else {
        $("#menu-toggle").text("Hide Menu");
    }
});



function setToday() {
    flatpickr("#myDate", { dateFormat: "Y-m-d" });
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2); //less than 9, add 0
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $("#myDate").val(today);
    //console.log(today);
}

function setDateFormat(date) {
    var day = ("0" + date.getDate()).slice(-2); //less than 9, add 0
    var month = ("0" + (date.getMonth() + 1)).slice(-2);
    return date.getFullYear() + "-" + (month) + "-" + (day);
}

function genColors(arr) {
    var colors = [];
    var num = Object.keys(arr).length;
    for (let index = 0; index < num; index++) {
        colors.push(randomColor());
    }
    return colors;
}

function randomColor() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgb(" + r + "," + g + "," + b + ")";
}

function getKeys(arr, name) {
    res = [];
    arr.forEach(element => {
        res.push(element[name]);
    });
    return res;
}
