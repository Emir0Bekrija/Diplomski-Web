$("#filterButton").on("click", function () {
  let selectedProductID = $("#productSelect").val();
  //let selectedProductName = $("#productSelect option:selected").text();

  if (selectedProductID) {
    console.log("Selected Product ID:", selectedProductID);
    //console.log("Selected Product Name:", selectedProductName);
  } else {
    console.log("No product selected.");
  }
});
