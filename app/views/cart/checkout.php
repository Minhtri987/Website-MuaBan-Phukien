<?php
include_once 'app/views/share/header.php';

// Kiểm tra xem session cart có tồn tại không
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng trống!";
    echo "<br>";
    echo "<a href='/chieu21' class='btn btn-primary' >Go to Home</a>";
} else {
    // Hiển thị danh sách sản phẩm trong gi�� hàng
    echo "<div class='container mt-5'>";
    echo "<h2 class='mb-4'>Danh sách gi�� hàng</h2>";
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>ID</th>";
    echo "<th scope='col'>Tên sản phẩm</th>";
    echo "<th scope='col'>Số lượng</th>";
    echo "<th scope='col'>Giá</th>";
    echo "<th scope='col'>Tác vụ</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalPrice += $item->quantity * $item->price;
        $formattedPrice = number_format($item->price * $item->quantity / 1000000, 0). ' Tr';
        echo "<tr>";
        echo "<td>$item->id</td>";
        echo "<td>$item->name</td>";
        echo "<td>";
        echo "<li>
                <form method='post' action='/chieu21/cart/updateQuality/$item->id' >
                <style>li { list-style-type: none; }</style>
                <button type='button' onclick='this.form.quality.value++;this.form.submit()'>+</button>
                <input name='quality' type='number' style='text-align:center;' value=".$item->quantity." readonly/>
                <button type='button' onclick='this.form.quality.value--;if(this.form.quality.value<1)this.form.quality.value=1;this.form.submit()'>-</button>
            </form>
            </li>";
        echo "</td>";
        echo "<td>$formattedPrice</td>";
        echo "<td>";
        echo "<form method='post' action='/chieu21/cart/remove/$item->id'>";
        echo "<input type='submit' value='Xóa' class='btn btn-danger mt-2' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')\" />";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    // Hiển thị t��ng tiền
    echo "<p class='lead'>Tổng tiền: <span class='font-weight-bold'>". number_format($totalPrice, 0). " VND</span></p>";
    // Hiển thị nút Tiến hành thanh toán
    echo "<form action='/chieu21/cart/checkout' method='post'>";
    echo "<button type='submit' class='btn btn-primary mt-4'>Tiến hành thanh toán</button>";
    echo "</form>";
    echo "</div>";
}

include_once 'app/views/share/footer.php';
?>