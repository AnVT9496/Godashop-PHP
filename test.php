<?php
// Các giá trị được xem là empty: 0, false, chỉ số không tồn tại, "", null, không tồn tại biến
$x = 0;

if (empty($x)) {
    echo "x empty";
}

echo "<br>";
$z = [3, 5];
if (empty($z[2])){
    echo "phần tử có chỉ số 2 hok tồn tại";
}
echo "<br>";
// isset là tồn tại, có hay không. vd: biến tồn tại, hoặc chỉ số có tồn tại

// not của isset là tập hợp con của empty
if (isset($y)) {
    echo "y tồn tại";
}
else {
    echo "y không tồn tại";
}
?>