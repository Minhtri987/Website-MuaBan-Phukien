<?php
include_once 'app/views/share/header.php';
?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="col mb-5">
                    <div class="card h-100 position-relative">
                        <!-- Product image -->
                        <img class="card-img-top"
                             src="<?php echo empty($row['image']) || !file_exists($row['image']) ? 'https://dummyimage.com/500x375/dee2e6/6c757d.jpg' : '/chieu21/' . $row['image']; ?>"
                             alt="Product Image"
                             style="width: 242px; height: auto;"
                        />
                        <!-- Product description -->
                        <div class="description-box position-absolute start-0 p-2 d-none">
                            <?php echo $row['description']; ?>
                        </div>
                        <!-- Product details -->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name -->
                                <h5 class="fw-bolder" style="font-weight: bold;"><?php echo $row['name']; ?></h5>
                                <!-- Product price -->
                                <span style="color: red;">$<?php echo $row['price']; ?></span>
                            </div>
                        </div>
                        <!-- Product actions -->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <form method="post" action="/chieu21/cart/add/<?php echo $row['id']; ?>">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" class="btn btn-outline-dark mt-auto" value="Add to cart" onclick="return alert('Đã thêm sản phẩm vào giỏ hàng thành công')">
                                    <br>
                                    <?php if ( isset($_SESSION['role']) && $_SESSION['role'] == 'admin') : ?>
                                    <a href="/chieu21/product/edit/<?=$row['id']?>">Edit</a>
                                    |
                                    <a href="/chieu21/product/delete/<?=$row['id']?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">Delete</a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<style>
    .description-box {
        background-color: #dcdcdc;
        color: blue;
        width: 200px;
        white-space: normal;
        z-index: 10;
        border: 1px solid black;
        border-radius: 10px;
        padding: 10px;
    }
    .description-left {
        left: -210px;
        top: 0;
    }
    .description-right {
        right: -210px;
        top: 0;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {
            const descriptionBox = card.querySelector('.description-box');

            card.addEventListener('mouseover', () => {
                // Position the description box to the left or right based on card's position
                const rect = card.getBoundingClientRect();
                const isLeft = rect.left > window.innerWidth / 2;

                descriptionBox.classList.toggle('description-left', isLeft);
                descriptionBox.classList.toggle('description-right', !isLeft);

                descriptionBox.classList.remove('d-none');
            });

            card.addEventListener('mouseout', () => {
                descriptionBox.classList.add('d-none');
            });
        });
    });
</script>


<?php
include_once 'app/views/share/footer.php';
?>
