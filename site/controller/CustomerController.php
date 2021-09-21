<?php 
use \Firebase\JWT\JWT;
class CustomerController {
	function encode() {
		$key = "Godashopk34key";
		$payload = [
			"email" => "test@gmail.com",
			"time" => time()
		];
		$token = JWT::encode($payload, $key);
		echo $token;
		echo get_domain_site();
	}

	function decode() {
		try {
			$key = "Godashopk34key";
			$token = $_GET["token"];
			$decoded = JWT::decode($token, $key, array('HS256'));
			print_r($decoded);
		} catch(Exception $e) {
			echo "Try hack!";
		}
		
	}
	function register() {
		$secret = GOOGLE_RECAPTCHA_SECRET;
		$remoteIp = "127.0.0.1";
		$recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$gRecaptchaResponse = $_POST["g-recaptcha-response"];
		$resp = $recaptcha->setExpectedHostname(get_host_name())
		->verify($gRecaptchaResponse, $remoteIp);

		if ($resp->isSuccess()) {
			// Verified!
		    //Lưu xuống database
			$data = [];
			$data["name"] = $_POST["fullname"];
			$data["password"] = md5($_POST["password"]);
			$data["mobile"] = $_POST["mobile"];
			$data["email"] = $_POST["email"];
			$data["login_by"] = "form";
			$data["shipping_name"] = $_POST["fullname"];
			$data["shipping_mobile"] =  $_POST["mobile"];
			$data["ward_id"] = null;
			$data["is_active"] = 0;
			$data["housenumber_street"] = "";
			$customerRepository = new CustomerRepository();
			if ($customerRepository->save($data)) {

				$_SESSION["success"] = "Bạn đã tạo được tài khoản thành công. Vui lòng vào email để kích hoạt tài khoản";
		    	//Gởi mail để kích hoạt tài khoản
				$emailService = new EmailService();
				$to = $_POST["email"];
				$subject = "Godashop: Active Account";
				$name = $_POST["fullname"];

				$key = JWT_SECRET_KEY;
				$payload = array(
					"email" => $to,
					"timestamp" => time()
				);

				$token = JWT::encode($payload, $key);

				$linkActiveAccount = get_domain_site()."/index.php?c=customer&a=activeAccount&token=$token";
				$message = "
				Dear $name,
				Please click bellow button to active your account
				<br>
				<a href='$linkActiveAccount'>Active Account</a>
				";
				$emailService->send($to, $subject, $message);

			}
			else {
				$_SESSION["error"] = $customerRepository->getError();

			}
		}
		else {
			$_SESSION["error"] = "Xác thực recaptcha thất bại";
		}

		header("location: index.php");
	}

	function existing() {
		$email = $_GET["email"];
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($email);
		$result = ["existing" => 0];
		if (!empty($customer)) {
			$result = ["existing" => 1];
		}
		echo json_encode($result);
	}

	function activeAccount() {
		try {
			$key = JWT_SECRET_KEY;
			$token  = $_GET["token"];
			$payload = JWT::decode($token, $key, array('HS256'));
			$timestamp = $payload->timestamp;
			$now = time();
			$duration = $now - $timestamp;
			if ($duration > 5 * 60) {//5 phút
				$_SESSION["error"] = "Bạn đã quá thời gian kích hoạt tài khoản. Thời gian kích hoạt hợp lệ là 5 phút";
				header("location: index.php");
				exit;
			}
			$email = $payload->email;
			$customerRepository = new CustomerRepository();
			$customer = $customerRepository->findEmail($email);
			if (!empty($customer)) {
				$customer->setIsActive(1);
				$customerRepository->update($customer);
				$_SESSION["success"] = "Kích hoạt tài khoản thành công";
			}
			else {
				$_SESSION["error"] = "$email không tồn tại";
			}
			header("location: index.php");
		} catch(Exception $e) {
			$_SESSION["error"] = "You hack!!!";
			header("location: index.php");
		}
		
	}

	function sendEmailResetPassword() {
		$emailService = new EmailService();
		$to = $_POST["email"];
		$subject = "Godashop: Reset Password";

		$key = JWT_SECRET_KEY;
		$payload = array(
			"email" => $to,
			"timestamp" => time()
		);

		$token = JWT::encode($payload, $key);

		$linkResetPassword = get_domain()."/site/index.php?c=customer&a=resetPassword&token=$token";
		$message = "
		Dear $to,
		Please click bellow button to reset password
		<br>
		<a href='$linkResetPassword'>ResetPassword</a>
		";
		if ($emailService->send($to, $subject, $message)) {
			$_SESSION["success"] = "Vui lòng kiểm tra email để thiết lặp lại mật khẩu";
		}
		else {
			$_SESSION["error"] = "Không thể gởi mail reset password";
		}
		header("location: index.php");
	}

	function resetPassword() {
		try {
			$key = JWT_SECRET_KEY;
			$token  = $_GET["token"];
			$payload = JWT::decode($token, $key, array('HS256'));
			$timestamp = $payload->timestamp;
			$now = time();
			$duration = $now - $timestamp;
			if ($duration > 5 * 60) {//5 phút
				$_SESSION["error"] = "Bạn đã quá thời gian để reset password. Thời gian hợp lệ là 5 phút";
				header("location: index.php");
				exit;
			}
			$email = $payload->email;
			require ABSPATH_SITE . "view/customer/resetPassword.php";
		} catch(Exception $e) {
			$_SESSION["error"] = "You hack!!!";
			header("location: index.php");
		}
		
	}

	function updatePassword(){
		try {
			$key = JWT_SECRET_KEY;
			$token  = $_POST["token"];
			$payload = JWT::decode($token, $key, array('HS256'));
			$timestamp = $payload->timestamp;
			$now = time();
			$duration = $now - $timestamp;
			if ($duration > 5 * 60) {//5 phút
				$_SESSION["error"] = "Bạn đã quá thời gian để reset password. Thời gian hợp lệ là 5 phút";
				header("location: index.php");
				exit;
			}
			$email = $payload->email;
			$customerRepository = new CustomerRepository();
			$customer = $customerRepository->findEmail($email);
			if (!empty($customer)) {
				$password = $_POST["password"];
				$customer->setPassword(md5($password));
				$customerRepository->update($customer);
				$_SESSION["success"] = "Thiết lập mật khẩu mới thành công";
			}
			else {
				$_SESSION["error"] = "$email không tồn tại";
			}
			header("location: index.php");
		} catch(Exception $e) {
			$_SESSION["error"] = "You hack!!!";
			header("location: index.php");
		}
	}
}

?>