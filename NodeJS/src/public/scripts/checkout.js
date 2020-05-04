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

function removeItemFromCart() {
    var row = this.parentNode.parentNode
    var item = itemsInCart[row.rowIndex - 1]

    fetch('api/cart-management/item', {
        method: 'DELETE',
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

function removeAllItemsFromCart() {
    fetch('api/cart-management/items', {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
        .then(res => res.json())
        .then(res => {
            if (res.redirectUrl) {
                changeWindowLocation(res.redirectUrl)
            }
        })
}

function purchaseAllItems() {
    fetch('api/cart-management/purchase', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
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

var tableButtons = document.getElementsByClassName('btn-remove-item')
for (var i = 0; i < tableButtons.length; i++) {
    tableButtons[i].addEventListener('click', removeItemFromCart, false);
}

var buttonRemoveAll = document.getElementById("btn-remove-all-items")
if (buttonRemoveAll) {
    buttonRemoveAll.addEventListener('click', removeAllItemsFromCart, false)
}

var buttonPurchaseAll = document.getElementById("btn-purchase-all-items")
if (buttonPurchaseAll) {
    buttonPurchaseAll.addEventListener('click', purchaseAllItems, false)
}

var buttonMessage = document.getElementById("button-ok")
if (buttonMessage) {
    buttonMessage.addEventListener('click', dismissMessageContainer, false)
}