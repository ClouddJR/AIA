const request = require('request');
const jsdom = require("jsdom");
const { JSDOM } = jsdom;

const base_url = 'https://singlemalt.pl/prezenty-i-upominki?limit=80'

request(base_url, function (error, response, body) {
    getListOfProducts(body)
});

function getListOfProducts(body) {
    var whiskyProductList = [];

    const dom = new JSDOM(body);
    var whiskyList = dom.window.document.querySelector("ul.products-grid").querySelectorAll("li");

    var promises = []
    whiskyList.forEach((whisky) => {
        var productLink = whisky.querySelector("h2.product-name a").href;
        var priceSpan = whisky.querySelector("div.details-section-bottom span.price")
        if (priceSpan) {
            var price = priceSpan.textContent.trim().slice(0, -3).replace(",", ".");
            promises.push(getWhiskyProductFromLink(productLink, price))
        }
    })

    Promise.all(promises).then(function (values) {
        values.forEach((product) => {
            if (product.price && product.maturation && product.capacity) {
                whiskyProductList.push(product)
            }
        });

        printWhiskyProducts(whiskyProductList)
    });
}

function getWhiskyProductFromLink(url, price) {
    return new Promise(function (resolve, reject) {
        request(url, function (error, response, body) {
            if (error) reject(error);
            const dom = new JSDOM(body);

            var capacity, maturation;

            rows = [...dom.window.document.getElementsByTagName('tr')];
            rows.forEach((row) => {

                //get capacity
                if (row.querySelector("th").innerHTML == "Pojemność butelki") {
                    let re = /^((\d,)?\d+) litra$/;
                    let cellContent = row.querySelector("td").innerHTML;
                    let matches = cellContent.match(re);
                    if (matches && matches.length > 1) {
                        capacity = matches[1].replace(",", ".");
                    }
                }

                //get maturation
                if (row.querySelector("th").innerHTML == "Wiek / lata leżakowania") {
                    let re = /^(\d+) lat$/;
                    let cellContent = row.querySelector("td").innerHTML;
                    let matches = cellContent.match(re);
                    if (matches && matches.length > 1) {
                        maturation = matches[1];
                    }
                }
            })
            let whiskyProduct = new WhiskyProduct(maturation, price, capacity)
            resolve(whiskyProduct)
        });
    });
}

function printWhiskyProducts(list) {
    computeUnitValue(list);
    list.sort(compare);
    console.log(list)
}

function compare(a, b) {
    if (a.unitValue < b.unitValue) {
        return 1;
    }
    if (a.unitValue > b.unitValue) {
        return -1;
    }
    return 0;
}

function computeUnitValue(list) {
    list.forEach((whisky) => {
        var price = parseFloat(whisky.price);
        var maturation = parseInt(whisky.maturation);
        var capacity = parseFloat(whisky.capacity) * 10;
        whisky.unitValue = price / capacity / maturation;
    })
}

class WhiskyProduct {
    constructor(maturation, price, capacity) {
        this.maturation = maturation;
        this.price = price;
        this.capacity = capacity;
        this.unitValue = "";
    }
}