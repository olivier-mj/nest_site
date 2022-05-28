
import 'lazysizes';
import './bootstrap';
import './navSticky.js'
import './scrollTotop'

document.addEventListener('DOMContentLoaded', function () {
    let toggleBtn = document.getElementById('btn-menu')
    let menu = document.getElementById('mobile-menu')

    toggleBtn.addEventListener('click', () => {
        menu.classList.toggle("hidden")
    })
})

