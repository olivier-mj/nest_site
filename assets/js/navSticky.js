const header  = document.getElementById('header');

addClassHeader = () => {
    header.classList.add("sticky");
    header.classList.remove("bg-nav");
}

removeClassHeader = () => {
    header.classList.remove("sticky");
    header.classList.add("bg-nav");

}

window.addEventListener('scroll', function () {
    let getScrollPosition = window.scrollY;
    if (getScrollPosition > 0) {
        addClassHeader();
    } else {
        removeClassHeader();
    }
});