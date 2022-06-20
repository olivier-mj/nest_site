document.addEventListener("scroll", handleScroll);
// get a reference to our predefined button
let scrollToTopBtn = document.querySelector(".scrollToTop");

function handleScroll() {
    let scrollableHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    let GOLDEN_RATIO = 0.5;

    if ((document.documentElement.scrollTop / scrollableHeight) > GOLDEN_RATIO) {
        //show button
        if (!scrollToTopBtn.classList.contains("showScroll"))
            scrollToTopBtn.classList.add("showScroll")
    } else {
        //hide button
        if (scrollToTopBtn.classList.contains("showScroll"))
            scrollToTopBtn.classList.remove("showScroll")
    }
}

scrollToTopBtn.addEventListener("click", scrollToTop);

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}