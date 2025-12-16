<?php
    error_reporting(E_ALL ^ E_DEPRECATED);
    require_once 'model/connect.php';

    if (isset($_POST['sendcontact'])) 
    {
        $namect = $_POST['contact-name'];
        $emailct = $_POST['contact-email'];
        $subject = $_POST['contact-subject'];
        $contentct = $_POST['contact-content'];

        $sql = "INSERT INTO contacts(name, email, title, contents, created) VALUES('$namect', '$emailct', '$subject', '$contentct', now())";
        $result = mysqli_query($conn,$sql);
        if ($result) 
        {
            header("location:lienhe.php?cs=success");
        } 
        else 
        {
            header("location:lienhe.php?cf=failed");
        }
    }
?>