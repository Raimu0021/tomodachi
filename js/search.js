$(document).ready(function() {
    $('#school_name').on('input', function() {
        let text = $(this).val();
        if (text === '') {
            $('#school_predictions').html('');
            return; // テキストが空の場合はここで処理を終了
        }
        $.get('./common/searchSchool.php', {text: text}, function(data) {
            let schools = JSON.parse(data);
            if (schools.length === 0) {
                $('#school_predictions').html(''); // 結果が空の場合はリンクを表示しない
                return;
            }
            
            let html = '';
            for(let key in schools) {
                if(schools.hasOwnProperty(key)) {
                    html += '<div><a href="search.php?school_id=' + schools[key].school_id + '">' + schools[key].school_name + '</a></div>';
                }
            }
            $('#school_predictions').html(html);
        });
    });

    $('form').on('submit', function(event) {
        event.preventDefault(); // フォームのsubmitを防止
    });
});