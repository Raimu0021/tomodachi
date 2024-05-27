<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'common/header.php';
include 'common/db-connect.php';
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f8f8;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-bottom: 15px;
            color: #f00;
        }
    </style>

    <div class="container">
        <h1>メールアドレス変更</h1>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="current-email">現在のメールアドレス</label>
                <input type="text" id="current-email" name="current-email" value="<?php echo htmlspecialchars($current_email); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="new-email">新しいメールアドレス</label>
                <input type="email" id="new-email" name="new-email" required>
            </div>
            <div class="form-group">
                <label for="confirm-email">新しいメールアドレス確認</label>
                <input type="email" id="confirm-email" name="confirm-email" required>
            </div>
            <button type="submit">変更する</button>
        </form>
    </div>
<?php require 'common/footer.php' ?>


