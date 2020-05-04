const Cart = require('./cart');

class BasicRouter {
    constructor(app, db) {
        this.app = app;
        this.db = db;
    }

    init() {
        this.handleHomePath();
        this.handleCheckoutPath();
    }

    handleHomePath() {
        this.app.get('/', (req, res) => {
            var cart = Object.assign(new Cart, req.session.cart);

            if (req.session.message) {
                var message = req.session.message.slice();
                req.session.message = undefined;
            }

            this.db.query("SELECT id, name FROM products WHERE is_purchased = FALSE;", (error, results) => {
                if (error) {
                    res.status(400).send('Error during database operation');
                } else {
                    res.render('index', { 'items': results, 'items_cart': cart.items, 'message': message });
                }
            });
        });
    }

    handleCheckoutPath() {
        this.app.get('/checkout', function (req, res) {
            var cart = Object.assign(new Cart, req.session.cart);

            if (req.session.message) {
                var message = req.session.message.slice();
                req.session.message = undefined;
            }

            res.render('checkout', { 'items_cart': cart.items, 'message': message });
        });
    }

}

module.exports = BasicRouter