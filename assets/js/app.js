/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// start the Stimulus application
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

