class Cart {
    constructor() {
        this.items = []
    }

    addItem(item) {
        this.items.push(item)
    }

    removeItem(item) {
        for (var i = 0; i < this.items.length; i++) {
            if (this.items[i].id == item.id) {
                this.items.splice(i, 1)
            }
        }
    }

    removeItems() {
        while (this.items.length > 0) {
            this.items.pop()
        }
    }
}

module.exports = Cart