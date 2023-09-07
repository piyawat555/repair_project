<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>วิธีรับค่า POST ในหน้าเดียว</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <input type="text" name="user" id="">
        <input type="password" name="password" id="">
        <button type="submit" id="submit">submit</button>
    </form>

    <?php 
        if($_SERVER["REQUEST_METHOD"] == "POST"){
           $user =$_POST['user'];
           $password =$_POST['password'];
            echo $user;
            echo $password;
        }
    ?>
</body>
</html>