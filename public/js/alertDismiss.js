setTimeout(function() {
    var elements = document.getElementsByClassName("auto-dismiss");
    for (var i = 0; i < elements.length; i++) {
        var element = elements[i];
        element.style.transition = "opacity 2s";
        element.style.opacity = 0;
        setTimeout(function() {
            element.parentNode.removeChild(element);
        }, 2000);
    }
}, 5000); 