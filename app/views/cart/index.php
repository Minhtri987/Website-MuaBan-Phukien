
<?php
include_once 'app/views/share/header.php';

// Kiểm tra xem session cart có tồn tại không
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div class='container mt-5 text-center'>"; // Added text-center class
    echo "<h2>Giỏ hàng trống!</h2>"; // Centered message
    echo "<br>";
    echo "<a href='/chieu21' class='btn btn-primary' >Go to Home</a>";
    echo "</div>";
} else {
    // Hiển thị danh sách sản phẩm trong giỏ hàng
    echo "<div class='container mt-5'>";
    echo "<h2 class='mb-4'>Danh sách giỏ hàng</h2>";
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
    $formattedPrices = [];
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $item) {
        $totalPrice += $item->quantity * $item->price;
        $formattedPrice = number_format($item->price * $item->quantity / 1000000, 0) . ' Tr';
        $formattedPrices[] = $formattedPrice;
    }
    $formattedTotalPrice = number_format($totalPrice / 1000000, 0) . ' Triệu đồng';
    $index = 0;
    foreach ($_SESSION['cart'] as $item) {
        echo "<tr>";
        echo "<td>$item->id</td>";
        echo "<td>$item->name</td>";
        echo "<td>";
        echo "<li>
                <form method='post' action='/chieu21/cart/updateQuality/$item->id' >
                <style>li { list-style-type: none; }</style>
                <button type='button' onclick='this.form.quality.value--;if(this.form.quality.value<1)this.form.quality.value=1;this.form.submit()'>-</button>
                <input name='quality' type='number' style='text-align:center;' value=".$item->quantity." readonly/>
                <button type='button' onclick='this.form.quality.value++;this.form.submit()'>+</button>
            </form>
            </li>";
        echo "</td>";
        echo "<td>";
        if (isset($formattedPrices[$index])) {
            echo $formattedPrices[$index];
            $index+=1;
        }
        echo "</td>";
        echo "<td>";
        echo "<form method='post' action='/chieu21/cart/remove/$item->id'>";
        echo "<input type='submit' value='Xóa' class='btn btn-danger mt-2' onclick=\"return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')\" />";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    // Hiển thị tổng tiền
    echo "<p class='lead'>Tổng tiền: <span class='font-weight-bold'>" . $formattedTotalPrice . "</span></p>";
    // Hiển thị nút Checkout
    echo "<form action='checkout.php' method='post'>";
    echo "<div style='display: flex; justify-content: space-between;'>
        <a href='/chieu21' class='btn btn-primary' >Go to Home</a>
        <button type='submit' class='btn btn-primary' onclick='checkout()'>Checkout</button></div>";
    echo "</form>";
    echo "</div>";
}

include_once 'app/views/share/footer.php';
?>
