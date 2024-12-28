const ProductService = {
  getProducts: function () {
    $.ajax({
      url: "http://localhost/diplomski/backend/products/all",
      type: "GET",
      dataType: "json",
      success: function (response) {
        let filterHtml = "";
        let products = response.data; // Access the 'data' property from the response

        if (products.length !== 0) {
          filterHtml += `<select class="form-control" id="productSelect">
                          <option value="">Select a product</option>`; // Adding default option

          for (let i = 0; i < products.length; i++) {
            let product = products[i];
            // Optionally handle the case when 'Picture' is null
            let pictureUrl = product.Picture
              ? product.Picture
              : "path/to/default-image.png";

            // Create an option for each product
            filterHtml += `
              <option value="${product.ProductID}">${product.ProductName}</option>
            `;
          }

          filterHtml += `</select>`; // Closing the select tag
        }

        $.ajax({
          url: "http://localhost/diplomski/backend/websites/all",
          type: "GET",
          dataType: "json",
          success: function (response) {
            let filterHtml = "";
            let websites = response.data; // Access the 'data' property from the response

            if (websites.length !== 0) {
              for (let i = 0; i < websites.length; i++) {
                let website = websites[i];
                filterHtml += `
                <div class="checkbox">
                    <label><input type="checkbox" id="${website.WebsiteID}" />${website.Name}</label>
                </div>
              `;
              }
            }
            $(".websites").html(filterHtml); // Add the generated HTML to the '.websites' div
          },
        });

        $(".products").html(filterHtml); // Add the generated HTML to the '.websites' div
      },
      error: function (xhr, status, error) {
        console.error("Error fetching data from file:", error);
      },
    });
  },

  getAllProductsDetails: function () {
    $.ajax({
      url: "http://localhost/diplomski/backend/prices/all_prices_from_products",
      type: "GET",
      dataType: "json",
      success: function (response) {
        let products = response.data; // Access the 'data' property from the response
        console.log("NUmber of products: " + products.length);
        let productHtml = ``;
        if (products.length !== 0) {
          for (let i = 0; i < products.length; i++) {
            let product = products[i];
            console.log(product);
            // Optionally handle the case when 'Picture' is null
            let pictureUrl = product.Picture
              ? product.Picture
              : "path/to/default-image.png";

            productHtml += `<a href="product.html" onclick="ProductService.saveIDs(${product.ProductID}, ${product.WebsiteID})">`;

            // Create an option for each product
            if (product.WebsiteID == 2) {
              productHtml += `<div class="item-panel panel panel-primary">`;
            } else if (product.WebsiteID == 1) {
              productHtml += `<div class="item-panel panel panel-danger">`;
            } else if (product.WebsiteID == 3) {
              productHtml += `<div class="item-panel panel panel-success">`;
            } else if (product.WebsiteID == 4) {
              productHtml += `<div class="item-panel panel panel-warning">`;
            } else if (product.WebsiteID == 5) {
              productHtml += `<div class="item-panel panel panel-info">`;
            } else {
              productHtml += `<div class="item-panel panel panel-default">`;
            }
            productHtml += `
              <div class="panel-heading">${product.ProductName}</div>
                <div class="panel-body">
                  <img
                    src="samsungs24fe.jpg"
                    class="img-responsive"
                    style="width: 100%"
                    alt="Image"
                  />
                </div>
                <div class="panel-footer">Cijena:  ${product.Price}</div>
                <div class="panel-footer">Website:  ${product.WebsiteName}</div>
              </div></a>
            `;
          }
        }
        $(".content-section").html(productHtml);
      },
    });
  },

  saveIDs: function (productid, websiteid) {
    localStorage.setItem("product_id", JSON.stringify(productid));
    localStorage.setItem("website_id", JSON.stringify(websiteid));
  },

  GetProductsDetailsByProductID: function (productID, websiteID) {
    $.ajax({
      url: "http://localhost/diplomski/backend/prices/get_all_prices_by_product_id",
      type: "POST",
      dataType: "json",
      data: {
        // Include ProductID and WebsiteID in the request
        product_id: productID,
        website_id: websiteID,
      },
      success: function (response) {
        console.log(response);

        // Parse the response data
        const products = response.data;

        // Prepare the data for Highcharts
        const dates = products.map((product) => product.ScrapedDate); // Extract dates
        const prices = products.map(
          (product) =>
            parseFloat(product.Price.replace(/[^0-9,.]/g, "").replace(",", ".")) // Extract and parse prices
        );

        // Initialize the Highcharts chart
        Highcharts.chart("container", {
          title: {
            text: "Product Price Trends",
            align: "left",
          },
          subtitle: {
            text: "Scraped prices over time",
            align: "left",
          },
          yAxis: {
            title: {
              text: "Price (KM)",
            },
          },
          xAxis: {
            categories: dates, // Use scraped dates as x-axis labels
            title: {
              text: "Date",
            },
          },
          legend: {
            layout: "vertical",
            align: "right",
            verticalAlign: "middle",
          },
          plotOptions: {
            series: {
              label: {
                connectorAllowed: false,
              },
              pointStart: 0,
            },
          },
          series: [
            {
              name: "Price (KM)",
              data: prices, // Use parsed prices as series data
            },
          ],
          responsive: {
            rules: [
              {
                condition: {
                  maxWidth: 500,
                },
                chartOptions: {
                  legend: {
                    layout: "horizontal",
                    align: "center",
                    verticalAlign: "bottom",
                  },
                },
              },
            ],
          },
        });
      },
      error: function (xhr, status, error) {
        console.error("Error fetching product details:", error);
      },
    });
  },

  HomePageDetails: function () {
    $.ajax({
      url: "http://localhost/diplomski/backend/prices/lowest_prices",
      type: "GET",
      dataType: "json",
      success: function (response) {
        let products = response.data; // Access the 'data' property from the response
        let productHtml = ``;
        if (products.length !== 0) {
          products.forEach((product) => {
            let pictureUrl = product.Picture || "path/to/default-image.png";
            let panelClass = "panel-default";
            switch (product.WebsiteId) {
              case 2:
                panelClass = "panel-primary";
                break;
              case 1:
                panelClass = "panel-danger";
                break;
              case 3:
                panelClass = "panel-success";
                break;
              case 4:
                panelClass = "panel-warning";
                break;
              case 5:
                panelClass = "panel-info";
                break;
            }

            productHtml += `
                        <div class="col-sm-4">
                            <a href="youtube.com">
                                <div class="item-panel panel ${panelClass}">
                                    <div class="panel-heading" title="${product.ProductName}">
                                        ${product.ProductName}
                                    </div>
                                    <div class="panel-body">
                                        <img
                                            src="${pictureUrl}"
                                            class="img-responsive"
                                            alt="${product.ProductName}"
                                        />
                                    </div>
                                    <div class="panel-footer">Price: ${product.Price}</div>
                                    <div class="panel-footer">Website: ${product.Name}</div>
                                </div>
                            </a>
                        </div>`;
          });
        }
        $(".content-section").html(productHtml);
      },
    });
  },
};
