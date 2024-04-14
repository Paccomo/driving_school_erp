function cardHeightEquazile() {
    var rows = document.querySelectorAll('.list-card-row');
    rows.forEach(function(row) {
        var cards = row.querySelectorAll('.list-card');
        var maxHeight = 0;

        cards.forEach(function(card) {
            var cardHeight = card.offsetHeight;
            if (cardHeight > maxHeight) {
                maxHeight = cardHeight;
            }
        });

        cards.forEach(function(card) {
            card.style.height = maxHeight + 'px';
        });
    });
}