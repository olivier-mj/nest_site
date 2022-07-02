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

const textarea = document.getElementById('blog_new_content');
const host = window.location.protocol + "//" + window.location.host;

if (textarea) {
    const editor = suneditor.create(textarea, {
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
        textarea.value = editor.getContents();

    })

    document.addEventListener('click', function (event) {
        if (!event.target.matches('.sun-editor-editable')) return;

        event.preventDefault();

        console.log(event.target);
        textarea.value = editor.getContents();

    })
    const test = textarea.value = editor.getContents();
    // console.log('contents', editor.getContents())
    console.log(test)
}



/**
 * @params {HTMLSelectElement} select
 */
 function bindSelect(select) {
    new TomSelect('#blog_new_tags', {
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