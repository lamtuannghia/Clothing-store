// dashboard


// // product
// document.addEventListener("DOMContentLoaded", function() {
//     const searchInput = document.getElementById("search");
//     searchInput.addEventListener("input", function() {
//         let filter = searchInput.value.toLowerCase();
//         let rows = document.querySelectorAll("#productList tr");
        
//         rows.forEach(row => {
//             let productName = row.cells[2].textContent.toLowerCase();
//             if (productName.includes(filter)) {
//                 row.style.display = "";
//             } else {
//                 row.style.display = "none";
//             }
//         });
//     });
// });

// orders
// document.addEventListener("DOMContentLoaded", function() {
//     const searchInput = document.getElementById("search");
//     searchInput.addEventListener("input", function() {
//         let filter = searchInput.value.toLowerCase();
//         let rows = document.querySelectorAll("#orderList tr");
        
//         rows.forEach(row => {
//             let customerName = row.cells[1].textContent.toLowerCase();
//             let phone = row.cells[2].textContent.toLowerCase();
//             let status = row.cells[4].textContent.toLowerCase();
//             if (customerName.includes(filter) || phone.includes(filter) || status.includes(filter)) {
//                 row.style.display = "";
//             } else {
//                 row.style.display = "none";
//             }
//         });
//     });
// });
