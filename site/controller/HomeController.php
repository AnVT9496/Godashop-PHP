<?php 
class HomeController{
	function list() {
		$page = 1;
		$item_per_page = 4;
		$productRepository = new ProductRepository();
		$conds = [];
		$sorts = ["featured" => "DESC"];

		$featuredProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

		$sorts = ["created_date" => "DESC"];
		$latestProducts = $productRepository->getBy($conds, $sorts, $page, $item_per_page);

		$categoryRepository = new CategoryRepository();
		$categories = $categoryRepository->getAll();
		$categoryProducts = [];
		foreach ($categories as $category) {
			$conds = [
				"category_id" => [
					"type" => "=",
					"val" => $category->getId()
				]
			];
			$products = $productRepository->getBy($conds, $sorts, $page, $item_per_page);
			$categoryProducts[$category->getName()] = $products;
		}
		require "view/home/list.php";
	}
}
?>
