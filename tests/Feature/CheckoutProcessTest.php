<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Order;

class CheckoutProcessTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product1;
    private Product $product2;

    /**
     * Thiết lập môi trường test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Tạo người dùng
        $this->user = User::factory()->create();

        // Tạo sản phẩm
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $this->product1 = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'stock_quantity' => 10,
            'price' => 1000,
        ]);
        $this->product2 = Product::factory()->create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'stock_quantity' => 5,
            'price' => 2000,
        ]);
    }

    /** @test */
    public function mot_nguoi_dung_da_dang_nhap_co_the_thanh_toan_thanh_cong()
    {
        // Thêm sản phẩm vào giỏ hàng
        CartItem::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product1->id,
            'quantity' => 2,
        ]);
        CartItem::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product2->id,
            'quantity' => 1,
        ]);

        $checkoutData = [
            'full_name' => 'Test User',
            'phone' => '123456789',
            'address' => '123 Test Street',
            'city' => 'Testville',
            'postal_code' => '12345',
            'payment_method' => 'cod',
        ];

        $response = $this->actingAs($this->user)
                         ->post(route('checkout.process'), $checkoutData);

        // Kiểm tra đơn hàng đã được tạo
        $this->assertDatabaseCount('orders', 1);
        $order = Order::first();
        $this->assertEquals($this->user->id, $order->user_id);
        $this->assertEquals((2 * 1000) + (1 * 2000), $order->total_amount);
        $this->assertEquals('pending', $order->status);

        // Kiểm tra các mục trong đơn hàng
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseHas('order_items', ['product_id' => $this->product1->id, 'quantity' => 2]);
        $this->assertDatabaseHas('order_items', ['product_id' => $this->product2->id, 'quantity' => 1]);

        // Kiểm tra số lượng tồn kho đã được cập nhật
        $this->assertDatabaseHas('products', ['id' => $this->product1->id, 'stock_quantity' => 8]); // 10 - 2
        $this->assertDatabaseHas('products', ['id' => $this->product2->id, 'stock_quantity' => 4]); // 5 - 1

        // Kiểm tra giỏ hàng đã được xóa
        $this->assertDatabaseCount('cart_items', 0);

        // Kiểm tra chuyển hướng và thông báo thành công
        $response->assertRedirect(route('orders.show', $order));
        $response->assertSessionHas('success');
    }

    /** @test */
    public function thanh_toan_that_bai_khi_san_pham_khong_du_ton_kho()
    {
        // Thêm sản phẩm với số lượng vượt quá tồn kho
        CartItem::factory()->create([
            'user_id' => $this->user->id,
            'product_id' => $this->product1->id,
            'quantity' => 11, // Tồn kho chỉ có 10
        ]);

        $checkoutData = ['full_name' => 'Test User', 'phone' => '123456789', 'address' => '123 Test Street', 'city' => 'Testville', 'postal_code' => '12345', 'payment_method' => 'cod'];

        $response = $this->actingAs($this->user)->post(route('checkout.process'), $checkoutData);

        // Kiểm tra không có đơn hàng nào được tạo
        $this->assertDatabaseCount('orders', 0);

        // Kiểm tra tồn kho không thay đổi
        $this->assertDatabaseHas('products', ['id' => $this->product1->id, 'stock_quantity' => 10]);

        // Kiểm tra giỏ hàng không bị xóa
        $this->assertDatabaseCount('cart_items', 1);

        // Kiểm tra chuyển hướng và thông báo lỗi
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('chỉ còn 10 sản phẩm trong kho', session('error'));
    }

    /** @test */
    public function thanh_toan_that_bai_khi_gio_hang_trong()
    {
        $checkoutData = ['full_name' => 'Test User', 'phone' => '123456789', 'address' => '123 Test Street', 'city' => 'Testville', 'postal_code' => '12345', 'payment_method' => 'cod'];

        $response = $this->actingAs($this->user)->post(route('checkout.process'), $checkoutData);

        $this->assertDatabaseCount('orders', 0);
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error', 'Giỏ hàng của bạn đang trống');
    }

    /** @test */
    public function thanh_toan_that_bai_voi_du_lieu_khong_hop_le()
    {
        CartItem::factory()->create(['user_id' => $this->user->id, 'product_id' => $this->product1->id, 'quantity' => 1]);

        // Thiếu `full_name`
        $checkoutData = ['phone' => '123456789', 'address' => '123 Test Street', 'city' => 'Testville', 'postal_code' => '12345', 'payment_method' => 'cod'];

        $response = $this->actingAs($this->user)->post(route('checkout.process'), $checkoutData);

        $response->assertSessionHasErrors('full_name');
        $this->assertDatabaseCount('orders', 0);
    }

    /** @test */
    public function nguoi_dung_chua_dang_nhap_khong_the_thanh_toan()
    {
        $checkoutData = ['full_name' => 'Test User', 'phone' => '123456789', 'address' => '123 Test Street', 'city' => 'Testville', 'postal_code' => '12345', 'payment_method' => 'cod'];

        $response = $this->post(route('checkout.process'), $checkoutData);

        $response->assertRedirect(route('login'));
    }
}
