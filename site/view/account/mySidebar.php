<aside class="col-md-3">
    <div class="inner-aside">
        <div class="category">
            <ul>
                <li class="<?=$a=="profile" ? "active": ""?>">
                    <a href="index.php?c=account&a=profile" title="Thông tin tài khoản" target="_self">Thông tin tài khoản
                    </a>
                </li>
                <li class="<?=$a=="defaultShipping" ? "active": ""?>">
                    <a href="index.php?c=account&a=defaultShipping" title="Địa chỉ giao hàng mặc định" target="_self">Địa chỉ giao hàng mặc định
                    </a>
                </li>
                <li class="<?=$a=="orders" || $a == "orderDetail" ? "active": ""?>">
                    <a href="index.php?c=account&a=orders" target="_self">Đơn hàng của tôi
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>