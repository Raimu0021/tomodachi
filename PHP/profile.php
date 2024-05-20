<?php require './common/header.php'; ?>
<div class="container">
    <div class="profile">
        <img id="profileImage" src="" alt="プロフィール画像">
        <p>名前: <span id="profileName">John Doe</span></p>
        <p>年齢/性別: <span id="profileAgeGender">25歳 / 男性</span></p>
        <p>学校: <span id="profileSchool">Example University</span></p>
        <p>学年: <span id="profileGrade">3年生</span></p>
        <p>自己紹介: <span id="profileBio">こんにちは</span></p>
    </div>
    <div class="edit-profile">
        <a href="profilehenshu.html">プロフィールを編集</a>
    </div>
</div>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    #header {
        background-color: #333;
        color: white;
        padding: 10px;
        text-align: center;
    }

    .container {
        padding: 20px;
    }

    .profile {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 20px;
    }

    .profile img {
        max-width: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .edit-profile {
        text-align: center;
    }

    .edit-profile a {
        text-decoration: none;
        color: #333;
        border: 1px solid #333;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const name = localStorage.getItem('profile_name') || 'John Doe';
        const ageGender = localStorage.getItem('profile_ageGender') || '25歳 / 男性';
        const school = localStorage.getItem('profile_school') || 'Example University';
        const grade = localStorage.getItem('profile_grade') || '3年生';
        const bio = localStorage.getItem('profile_bio') || 'こんにちは';
        const profileImage = localStorage.getItem('profile_image');
        
        document.getElementById('profileName').textContent = name;
        document.getElementById('profileAgeGender').textContent = ageGender;
        document.getElementById('profileSchool').textContent = school;
        document.getElementById('profileGrade').textContent = grade;
        document.getElementById('profileBio').textContent = bio;

        if (profileImage) {
            document.getElementById('profileImage').src = profileImage;
        } else {
            document.getElementById('profileImage').src = 'path/to/default-profile-image.jpg';
        }
    });
    </script>
<?php require './footer.php'?>