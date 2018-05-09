function getXHR() {
    var xhr;

    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    }

    else {
        xhr = new ActiveXObject('Microsoft.XMLHTTP');
    }

    return xhr;
}