
function StoreCart(  ) {
    this.products = [];
}

StoreCart.prototype.push = function(product)
{
    this.products[product.id] = {
        product: product,
        num: 1,
        inc: function(num) {
            this.num += num;
        },
        dec: function(num) {
            this.num -+ num;
        }
    };
};

StoreCart.prototype.pop = function(product)
{
    if (product.id in this.products) {
        delete this.products[product.id];
    }
};

StoreCart.prototype.addToCart = function(product, num) 
{
    if (! product.id in this.products ) {
        this.push(product);
    } else {
        this.products[product.id].inc(num);
    }
};

StoreCart.prototype.removeFromCart = function(product, num)
{
    if (product.id in this.products) {
        this.products[product.id].dec(num);
        if (this.products[product.id].num <= 0) {
            this.pop(product);
        }
    }
};

StoreCart.prototype.emptyCart = function()
{
    for( var i in this.products ) {
        this.pop(i);
    }
}; 
