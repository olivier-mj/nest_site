
import 'suneditor/dist/css/suneditor.min.css'
import suneditor from 'suneditor'
import plugins from 'suneditor/src/plugins'
// How to import plugins

import {
    blockquote,
    align,
    font,
    fontSize,
    fontColor,
    formatBlock,
    hiliteColor,
    horizontalRule,
    lineHeight,
    list,
    paragraphStyle,
    table,
    textStyle,
    image,
    link,
    video,
    audio,
    imageGallery
} from 'suneditor/src/plugins'

import lang from 'suneditor/src/lang'
import fr from 'suneditor/src/lang/fr'

let sideBar = document.getElementById("mobile-nav");
let openSidebar = document.getElementById("openSideBar");
let closeSidebar = document.getElementById("closeSideBar");

sideBar.style.transform = "translateX(-260px)";

openSidebar.addEventListener('click', (e) => {
    sideBar.style.transform = "translateX(0px)";
    sideBar.classList.remove('hidden')
    // openSidebar.classList.add('hidden')
    closeSidebar.classList.add("flex");
    closeSidebar.classList.remove("hidden");
})

closeSidebar.addEventListener('click', (e) => {
    sideBar.style.transform = "translateX(-260px)";
    // sideBar.classList.add('hidden')
    closeSidebar.classList.add("hidden");
    openSidebar.classList.remove("hidden");
})


let adminBtn = document.getElementById('admin-btn');
let adminMenu = document.getElementById('admin-menu');

if(adminMenu){
    adminBtn.addEventListener('click', function(event) {
        if(adminMenu.classList.contains('hidden')){
            adminMenu.classList.remove('hidden')
        } else {
            adminMenu.classList.add('hidden')
        }
    })
}


import TomSelect from 'tom-select';

async function jsonFect(url) {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json'
        }
    })
    if (response.status === 204) {
        return null
    }

    if (response.ok) {
        return await response.json();
    }
    throw response;
}




    /**
 * @params {HTMLSelectElement} select
 */
 function bindSelect(select) {
    new TomSelect('#blog_tags', {
        hideSelected: true,
        closeAfterSelect: true,
        valueField: select.dataset.value,
        labelField: select.dataset.label,
        searchField: select.dataset.label,
        load: async (query, callback) => {
            const url = `${select.dataset.remote}?q=${encodeURIComponent(query)}`
            callback(await jsonFect(url))
        },
        plugins: {
            remove_button: {
                title: 'Supprimer cet élément'
            }
        },
        persist: false,
        create: true,
    });
}


Array.from(document.querySelectorAll('select[multiple]')).map(bindSelect);






const blogContent =  document.getElementById('blog_content');
const host = window.location.protocol + "//" + window.location.host;




if (blogContent) {
    const editor = suneditor.create('blog_content', {
        plugins: [
            blockquote,

            align,
            font,
            fontColor,
            fontSize,
            formatBlock,
            // hiliteColor,
            horizontalRule,
            lineHeight,
            list,
            paragraphStyle,
            table,
            // textStyle,
            image,
            link,
            video,
            audio,

            imageGallery,
            imageGallery.drawItems
        ],
        buttonList: [
            ['undo', 'redo',
                'formatBlock',
                // 'paragraphStyle', 
                'blockquote',
                'bold', 'underline', 'italic', 'strike',
                //  'subscript', 'superscript',
                // 'fontColor', 'hiliteColor',
                'removeFormat',
                // 'outdent', 'indent',
                'align', 'horizontalRule', 'list', 'lineHeight',
                'table', 'link', 'image', 'video', 'audio', /** 'math', */ // You must add the 'katex' library at options to use the 'math' plugin.
                'imageGallery', // You must add the "imageGalleryUrl".
                'fullScreen',
                // 'showBlocks',
                'codeView',
                'preview',
            ]
        ],
        lang: lang.fr,
        imageUploadUrl: "/admin/filemanager/upload/image",
        imageGalleryUrl: "/admin/filemanager/gallery.json",
        imageAccept: ".jpg, jpeg, .png, .webp"
    });



    document.addEventListener('keyup', function (event) {
        if (!event.target.matches('.sun-editor-editable')) return;

        event.preventDefault();

        console.log(event.target);
        blogContent.value = editor.getContents();

    })

    document.addEventListener('click', function (event) {
        if (!event.target.matches('.sun-editor-editable')) return;

        event.preventDefault();

        console.log(event.target);
        blogContent.value = editor.getContents();

    })
    const test = blogContent.value = editor.getContents();
    // console.log('contents', editor.getContents())
    console.log()
}


let deleteBtn = document.getElementById('del-button')
let deleteModal = document.getElementById('delete-modal')
let confirmedBtn = document.getElementById('confirmed-button')
let canceledBtn = document.getElementById('canceled-button')
 
deleteBtn.addEventListener('click', function(event){
    event.preventDefault();
    console.log('delete')
    deleteModal.classList.remove('hidden')
})

canceledBtn.addEventListener('click', function(event){
    event.preventDefault();
    console.log('canceled')
    deleteModal.classList.add('hidden')
})

// confirmedBtn.addEventListener('click', function(event){
//     // event.preventDefault();
//     console.log('confirmed')
// })


let createdAt = document.getElementById('blog_createdAt')
createdAt.value = now.toISOString().slice(0, 16);
let updatedAt = document.getElementById('blog_updatedAt')
updatedAt.value = now.toISOString().slice(0, 16);