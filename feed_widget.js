var cached_file_url = "http://localhost/feed_widget/html/feed_widget.html";
var xml_http = null;

function getArticleHTML() {
    xml_http = new XMLHttpRequest();
    xml_http.onreadystatechange = processArticleHTML;
    xml_http.open("GET", cached_file_url, true);
    xml_http.send(null);
}

function processArticleHTML() {
    if (xml_http.readyState == 4 && xml_http.status == 200) {
        document.getElementById("feed_widget_container").innerHTML = xml_http.responseText;
    }
}

window.onload = getArticleHTML;