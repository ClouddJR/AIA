const Cart = require('./cart');
const Mutex = require('./mutex');
const url = require('url');
const mysql = require('mysql');

class ApiRouter {
    constructor(app, db) {
        this.app = app;
        this.db = db;
        this.mutex = new Mutex();
    }

    init() {
        this.handlePutItemToCart();
        this.handleDeleteItemFromCart();
        this.handleDeleteAllItemsFromCart();
        this.handlePurchaseAllItems();
    }

    handlePutItemToCart() {
        this.app.put('/api/cart-management/item', (req, res) => {
            var item = req.body;

            if (!req.session.cart) {
                req.session.cart = new Cart();
            }

            //check whether the item that a user wants to add is already purchased
            var query = 'SELECT * FROM products WHERE id = ?';
            this.db.query(query, [item.id], (error, results) => {
                var itemInDB = results[0];

                if (!itemInDB.is_purchased) {
                    var cart = Object.assign(new Cart, req.session.cart);
                    cart.addItem(item);
                } else {
                    req.session.message = 'This item has already been purchased';
                }

                this.sendUserTo('/', res, req)
            })
        })
    }

    handleDeleteItemFromCart() {
        this.app.delete('/api/cart-management/item', (req, res) => {
            var item = req.body

            var cart = Object.assign(new Cart, req.session.cart)
            cart.removeItem(item)

            this.sendUserTo('/checkout', res, req)
        })
    }

    handleDeleteAllItemsFromCart() {
        this.app.delete('/api/cart-management/items', (req, res) => {
            var cart = Object.assign(new Cart, req.session.cart)
            cart.removeItems()

            req.session.message = 'Your shopping cart has been successfuly cleared'

            this.sendUserTo('/', res, req)
        })
    }

    handlePurchaseAllItems() {
        this.app.post('/api/cart-management/purchase', (req, res) => {
            this.mutex.lock().then(() => {
                var cart = Object.assign(new Cart, req.session.cart)

                var cartItemIds = []
                cart.items.forEach(item => {
                    cartItemIds.push(item.id)
                });

                var query = 'SELECT * FROM products WHERE id IN (?);'
                this.db.query(query, [cartItemIds], (error, results) => {
                    if (error) {
                        res.status(400).send('Error during database operation' + error);
                    } else {
                        var wasAnyItemBoughtBefore = false
                        results.forEach(itemInDB => {
                            if (itemInDB.is_purchased) {
                                wasAnyItemBoughtBefore = true
                                cart.removeItem(itemInDB)
                            }
                        });

                        if (wasAnyItemBoughtBefore) {
                            req.session.message = 'Someone had already purchased one/some of your items'
                            this.sendUserTo('/checkout', res, req)
                        } else {
                            var updateQueries = ''
                            cart.items.forEach(item => {
                                updateQueries += mysql.format('UPDATE products SET is_purchased = TRUE WHERE id = ?;', item.id)
                            });

                            this.db.query(updateQueries, (error, _) => {
                                if (error) {
                                    res.status(400).send('Error during database operation');
                                } else {
                                    req.session.cart = new Cart()
                                    req.session.message = 'Successfully purchased all items!'
                                    this.sendUserTo('/', res, req)
                                }
                            })

                        }
                    }
                    this.mutex.release()
                })

            })
        })
    }

    sendUserTo(path, res, req) {
        var requrl = url.format({
            protocol: req.protocol,
            host: req.get('Host'),
            pathname: path,
        });
        res.send({ redirectUrl: requrl });
    }

}

module.exports = ApiRouter