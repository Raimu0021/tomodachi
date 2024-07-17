document.addEventListener('click', function(e) {
    if (e.target.closest('.like-btn')) {
        const button = e.target.closest('.like-btn');
        const userId = button.dataset.userId;
        const icon = button.querySelector('i');
        
        fetch('../PHP/handler/like_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ 'liked_user_id': userId }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => response.text()) // JSONとしてではなくテキストとしてレスポンスを取得
        .then(text => {
            try {
                const data = JSON.parse(text); // テキストをパースしてJSONに変換
                if (data.success) {
                    if (data.liked) {
                        icon.classList.remove('fa-regular', 'fa-heart');
                        icon.classList.add('fa-solid', 'fa-heart');
                    } else {
                        icon.classList.remove('fa-solid', 'fa-heart');
                        icon.classList.add('fa-regular', 'fa-heart');
                    }
                }
            } catch (error) {
                console.error('Parsing error:', error, text); // パースエラーとレスポンス内容をログ
            }
        })
        .catch(error => console.error('Fetch error:', error));
    }
});