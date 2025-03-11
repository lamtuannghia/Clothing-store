let lastScrollTop = 0;
const header = document.getElementById('myDiv');
const nav = document.getElementById('myNav');

window.addEventListener('scroll', function() {
if (window.scrollY === 0) {
    // Khi cuộn lên đầu trang, hiện thẻ div
    nav.style.position = "relative";
    header.style.display = "block";
} else {
    // Khi cuộn xuống, ẩn thẻ div
    nav.style.position = "fixed";
    header.style.display = "none";
}
});