<?php
class CartController
{

    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }


    public function updateQuality($id)
    {
        $newQuantity = $_POST['quality'];
        foreach ($_SESSION['cart'] as &$item) {
            if ($item->id == $id) {
                $item->quantity = $newQuantity;

                break;
            }
        }
        header('Location: /chieu21/cart/show');
    }

    public function Add($id)
    {
        // Khởi tạo một phiên cart nếu chưa tồn tại
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Lấy sản phẩm từ ProductModel bằng $id
        $product = $this->productModel->getProductById($id);

        // Nếu sản phẩm tồn tại, thêm vào giỏ hàng
        if ($product) {
            // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
            $productExist = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item->id == $id) {
                    $item->quantity++;
                    $productExist = true;
                    break;
                }
            }

            // Nếu sản phẩm chưa tồn tại trong giỏ hàng, thêm mới vào
            if (!$productExist) {
                $product->quantity = 1;
                $_SESSION['cart'][] = $product;
            }

            header('Location: /chieu21');
        } else {
            echo "Không tìm thấy sản phẩm với ID này!";
        }
    }

    public function remove($id)
    {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item->id == $id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }

        header('Location: /chieu21/cart/show');
    }
    function show()
    {
        include_once 'app/views/cart/index.php';
    }
    public function checkout()
    {
        // Lấy thông tin khách hàng từ form đăng nhập
        $customer = [
            'name' => $_POST['name'],
            'email' => $_POST['email']
        ];

        // Tạo đơn hàng mới
        $order = new Order();
        $order->customer = $customer;
        $order->date_order = date('Y-m-d H:i:s');
        $order->total = $_SESSION['cart.total'];

        // Lưu đơn hàng và chi tiết đơn hàng vào csdl
        $this->db->insert('orders', $order);
        $orderId = $this->db->insert_id();

        foreach ($_SESSION['cart'] as $item) {
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $orderId;
            $orderDetail->product_id = $item->id;
            $orderDetail->quantity = $item->quantity;
            $orderDetail->price = $item->price;

            $this->db->insert('order_details', $orderDetail);
        }

        // Xóa gi�� hàng
        unset($_SESSION['cart']);
        unset($_SESSION['cart.total']);

        // Chuyển hướng đến trang thanh toán thành công
        header('Location: /chieu21/cart/success');
    }
}
