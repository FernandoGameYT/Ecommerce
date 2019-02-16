function drawProducts(products, productContainer) {
    if(products.length == 0) {
        productContainer.html(`<div style='text-align: center;margin-top: 20px;'>
        <h2>No se a encontrado ningún producto.</h2>
        <span class='fa fa-search' style='font-size: 60px;'></span>
        </div>`);
    }else{
        productContainer.html("");
    
        products.forEach(product => {
            productContainer.append(`
                <div class="card">
                    <img src="images/products/${product.Image}" alt="${product.Name}">
                    <div class="info">
                        <h4 class="name">${product.Name}</h4>
                        <a href="Products/${product.Name}-${product.Id}/">
                            <button class="btn btn-primary">
                                Ver mas
                            </button>
                        </a>
                    </div>
                </div>
            `);
        });
    }
}