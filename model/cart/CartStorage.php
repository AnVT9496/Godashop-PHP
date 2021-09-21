<?php 
class CartStorage {
	function store($cart) {
		session_id() || session_start();
		$_SESSION["cart"] = serialize($cart);
		setcookie("cart", serialize($cart),  time()+24*60*60);//keep one day
	}

	function fetch() {
		session_id() || session_start();
		if (empty($_SESSION["cart"])) {
			if (empty($_COOKIE["cart"])) {
				$cart = new Cart();
				return $cart;
			}
			//update session;
			$_SESSION["cart"] = $_COOKIE["cart"];
		}
		$cart = unserialize($_SESSION["cart"]);
		return $cart;
	}

	function clear() {
		session_id() || session_start();
		unset($_SESSION["cart"]);
		setcookie("cart", null,  time()-24*60*60);//keep one day
	}
}
 ?>