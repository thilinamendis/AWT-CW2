var xmlhttp;
 
function fetch(t)
{
    // note - this doesn't work in IE browsers - surprised?
    xmlhttp = new XMLHttpRequest();
    // now build the URL of the server-side resource we want to
    // communicate with
    var url = "/index.php/dict/lookup?t=" + t;
    xmlhttp.onreadystatechange = myfunc;
    xmlhttp.open("GET",url);
    xmlhttp.send();
}
 
function myfunc()
{
    if (xmlhttp != null && xmlhttp.readyState == 4) {
        var txt = xmlhttp.responseText;
        document.getElementById('mydiv').innerHTML = txt;
    }
}