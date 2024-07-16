document.addEventListener('click', function(e) {
    if (e.target.matches('.like-btn')) {
        const userId = e.target.dataset.userId;
        fetch('like_handler.php', {
            method: 'POST',
            body: new URLSearchParams({ 'liked_user_id': userId }),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.liked) {
                    e.target.classList.add('liked');
                } else {
                    e.target.classList.remove('liked');
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }
});