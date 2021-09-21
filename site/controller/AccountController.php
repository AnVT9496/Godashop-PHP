<?php 
class AccountController {
	function profile() {
		$email = $_SESSION["email"];
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($email);
		require "view/account/profile.php";
	}

	function updateProfile() {
		$email = $_SESSION["email"];
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($email);
		$customer->setName($_POST["fullname"]);
		$customer->setMobile($_POST["mobile"]);
		if (!empty($_POST["password"])) {
			if (md5($_POST["old-password"]) != $customer->getPassword()) {
				$_SESSION["error"] = "Mật khẩu hiện tại không đúng";
				header("location: index.php?c=account&a=profile");
				exit;
			}
			$customer->setPassword(md5($_POST["password"]));

		}

		if ($customerRepository->update($customer)) {
			$_SESSION["success"] = "Cập nhật tài khoản thành công";
			$_SESSION["name"] =  $customer->getName();
		}
		else {
			$_SESSION["error"] = "Cập nhật tài khoản thất bậi";
		}
		header("location: index.php?c=account&a=profile");

	}

	function orders() {
		$email = $_SESSION["email"];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        $orderRepository = new OrderRepository();
		$orders = $orderRepository->getByCustomerId($customer->getId());
		require "view/account/orders.php";
	}

	function orderDetail() {
		$orderRepository = new OrderRepository();
		$id = $_GET["id"];
		$order = $orderRepository->find($id);
		require "view/account/orderDetail.php";
	}

	function defaultShipping() {

        $email = $_SESSION["email"];
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->findEmail($email);

        include "layout/variable_address.php";
		require "view/account/defaultShipping.php";
	}

	function updateDefaultShipping() {
		$email = $_SESSION["email"];
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($email);
		$customer->setShippingName($_POST["fullname"]);
		$customer->setShippingMobile($_POST["mobile"]);
		$customer->setHousenumberStreet($_POST["address"]);
		$customer->setWardId($_POST["ward"]);

		if ($customerRepository->update($customer)) {
			$_SESSION["success"] = "Cập nhật địa chỉ giao hàng mặc định thành công";
		}
		else {
			$_SESSION["error"] = "Cập nhật địa chỉ giao hàng mặc định thất bại";
		}
		header("location: index.php?c=account&a=defaultShipping");
	}
}

 ?>