window.onpageshow = function (event) {
    if (event.persisted) {
        window.location.reload();
    }
};

function changeWindowLocation(url) {
    if (window.location.href == url) {
        window.location.reload()
    } else {
        window.location.href = url
    }
}

function addItemToCart() {
    var row = this.parentNode.parentNode
    var item = items[row.rowIndex - 1]

    fetch('api/cart-management/item', {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(item),
    })
        .then(res => res.json())
        .then(res => {
            if (res.redirectUrl) {
                changeWindowLocation(res.redirectUrl)
            }
        })
}

function dismissMessageContainer() {
    this.parentNode.remove()
}

var tableButtons = document.getElementsByClassName('btn-add-to-cart')
for (var i = 0; i < tableButtons.length; i++) {
    tableButtons[i].addEventListener('click', addItemToCart, false);
}

var buttonMessage = document.getElementById("button-ok")
if (buttonMessage) {
    buttonMessage.addEventListener('click', dismissMessageContainer, false)
}