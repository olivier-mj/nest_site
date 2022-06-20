import 'lazysizes';
import './bootstrap';

if (document.getElementById('onTop')) {
    require('./navSticky.js');
}
if (document.getElementById('onTop')) {
    require('./scrollTotop.js');
}

if (document.getElementById('btn-menu')) {
    document.addEventListener('DOMContentLoaded', function () {
        let toggleBtn = document.getElementById('btn-menu')
        let menu = document.getElementById('mobile-menu')

        toggleBtn.addEventListener('click', () => {
            menu.classList.toggle("hidden")
        })
    })
}

let userMenu = document.getElementById('user-menu');
let dropdown = document.getElementById('dropdown')

if(userMenu) {
    userMenu.addEventListener('click', (e) => {
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden')
        console.log('remove hidden')
    } else {
        dropdown.classList.add('hidden')
        console.log('add hidden')

    }
})
}
