let share = document.getElementById('#social')

let popupShare = function (url, title, width, height) {
    let popupWidth = width || 640
    let popupHeight = height || 320
    let windowLeft = window.screenLeft || window.screenX
    let windowTop = window.screenTop || window.screenY
    let windowWidth =
        window.innerWidth || document.documentElement.clientWidth
    let windowHeight =
        window.innerHeight || document.documentElement.clientHeight
    let popupLeft = windowLeft + windowWidth / 2 - popupWidth / 2
    let popupTop = windowTop + windowHeight / 2 - popupHeight / 2
    let popup = window.open(
        url,
        title,
        'scrollbars=yes, width=' +
        popupWidth +
        ', height=' +
        popupHeight +
        ', top=' +
        popupTop +
        ', left=' +
        popupLeft
    )
    popup.focus()
    return true
}

if (typeof (share) !== 'undefined' || share !== null) {

    document
        .querySelector('.share_twt')
        .addEventListener('click', function (e) {
            e.preventDefault()
            let url = this.getAttribute('data-url')
            let shareUrl =
                'https://twitter.com/intent/tweet?text=' +
                encodeURIComponent(document.title) +
                '&url=' +
                encodeURIComponent(url)
            popupShare(shareUrl, 'Partager sur Twitter')
        })
    document
        .querySelector('.share__facebook')
        .addEventListener('click', function (e) {
            e.preventDefault()
            let url = this.getAttribute('data-url')
            let shareUrl =
                'https://www.facebook.com/sharer/sharer.php?u=' +
                encodeURIComponent(url)
            popupShare(shareUrl, 'Partager sur facebook')
        })
    document
        .querySelector('.share_whatsapp')
        .addEventListener('click', function (e) {
            e.preventDefault()
            let url = this.getAttribute('data-url')
            let shareUrl = 'whatsapp://send?text=' + encodeURIComponent(url)
            open(shareUrl, 'Partager sur Whatsapp')
        })
    document
        .querySelector('.share_reddit')
        .addEventListener('click', function (e) {
            e.preventDefault()
            let url = this.getAttribute('data-url')
            let shareUrl =
                'http://www.reddit.com/submit?url=' + encodeURIComponent(url)
            popupShare(shareUrl, 'Partager sur Reddit')
        })
    document
        .querySelector('.share_pocket')
        .addEventListener('click', function (e) {
            e.preventDefault()
            let url = this.getAttribute('data-url')
            let shareUrl =
                'https://getpocket.com/edit.php?url=' +
                encodeURIComponent(url) +
                ' &title=' +
                encodeURIComponent(document.title)
            popupShare(shareUrl, 'Sauvegarder sur Pocket')
        })
    document.querySelector('.share_email').addEventListener('click', function (e) {
        e.preventDefault()
        let encoded_body = encodeURIComponent(window.location.href)
        let encoded_subject = encodeURIComponent(document.title)
        let shareUrl =
            'mailto:?subject=' + encoded_subject + '&body=' + encoded_body
        open(shareUrl, 'Partager par courriel')
    })
}

