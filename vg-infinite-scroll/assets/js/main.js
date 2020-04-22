window.onload = function () {
    let loadMoreButtonObj = document.querySelector('.vg-infinite.load-more');

    if (loadMoreButtonObj) {
        loadMoreButtonObj.onclick = function loadMore() {
            actionAjax();
        }
    }

}

function actionAjax() {
    fetch(fetch_remaining_post.ajaxurl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new this.URLSearchParams({
            'action': 'fetch_remaining_posts',
            'offset': document.querySelectorAll('#vg-infinite-container .vg-main-wrapper .vg-post-excerpt').length
        })
    })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            document.querySelector('#vg-infinite-container .vg-main-wrapper').insertAdjacentHTML('beforeend', data)
        })
}