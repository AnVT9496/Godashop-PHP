<?php
class ContactController
{
    function form()
    {
        require "view/contact/form.php";
    }

    function send()
    {
        $name = $_POST["fullname"];
        $email =  $_POST["email"];
        $message = $_POST["content"];
        $mobile = $_POST["mobile"];

        $emailService = new EmailService();
        $to = EMAIL_SHOP;
        $subject = "[Godashop] Khách hàng $email liên hệ";
        $content = "
        Chào chủ shop,<br>
        Khách hàng có emai $email liên hệ,<br>
        Số điện thoại của KH là: $mobile,<br>
        Nội dung liên hệ là: <br>
        $message
        ";
        if ($emailService->send($to, $subject, $content)) {
            echo "Đã gởi mail thành công";
        }
        else {
            echo $emailService->getError();
        }
    }
}
