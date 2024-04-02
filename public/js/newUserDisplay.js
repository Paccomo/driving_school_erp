function toggleVisibility(originalText) {
    var cell = document.getElementById('toggleCell');
    if (cell.innerText === originalText) {
        cell.innerText = '******';
    } else {
        cell.innerText = originalText;
    }
}