//https://stackoverflow.com/a/43792563
// Store a copy of the fetch function
var _oldFetch = fetch;

// Create our new version of the fetch function
window.fetch = function () {

    // Create hooks
    var fetchStart = new Event('fetchStart', { 'view': document, 'bubbles': true, 'cancelable': false });
    var fetchEnd = new Event('fetchEnd', { 'view': document, 'bubbles': true, 'cancelable': false });

    // Pass the supplied arguments to the real fetch function
    var fetchCall = _oldFetch.apply(this, arguments);

    // Trigger the fetchStart event
    document.dispatchEvent(fetchStart);

    fetchCall.then(function () {
        // Trigger the fetchEnd event
        document.dispatchEvent(fetchEnd);
    }).catch(function () {
        // Trigger the fetchEnd event
        document.dispatchEvent(fetchEnd);
    });

    return fetchCall;
};

document.addEventListener('fetchStart', function () {
    document.querySelector('.vg-infinite.load-more').classList.add('vg-spinner');
    document.querySelector('.vg-infinite.load-more').style.pointerEvents = 'none';
});

document.addEventListener('fetchEnd', function () {
    document.querySelector('.vg-infinite.load-more').classList.remove('vg-spinner');
    document.querySelector('.vg-infinite.load-more').style.pointerEvents = 'auto';

    setTimeout(() => {
        if (get_loaded_post_count() == parseInt(fetch_remaining_post.vg_post_count)) {
            document.querySelector('.vg-infinite.load-more').classList.add('vg-hide');
        }
    }, 1); //To avoid race condition with post ajax dom render
});

window.onload = function () {
    let loadMoreButtonObj = document.querySelector('.vg-infinite.load-more');

    if (loadMoreButtonObj) {
        loadMoreButtonObj.onclick = function loadMore() {
            actionAjax();
        }
    }

}

function get_loaded_post_count() {
    return document.querySelectorAll('#vg-infinite-container .vg-main-wrapper .vg-post-excerpt').length;
}

function actionAjax() {
    fetch(fetch_remaining_post.ajaxurl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new this.URLSearchParams({
            'action': 'fetch_remaining_posts',
            'offset': get_loaded_post_count()
        })
    })
        .then(response => response.text())
        .then(data => {
            document.querySelector('#vg-infinite-container .vg-main-wrapper').insertAdjacentHTML('beforeend', data)
        })
}
