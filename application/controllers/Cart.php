<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load models and libraries
        $this->load->model('Produk_model');
        // Cart library is already autoloaded
    }

    // View shopping cart
    public function index() {
        $data['title'] = 'Keranjang Belanja - Toko Sepatu Premium';
        $data['cart_items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        
        $this->load->view('keranjang_view', $data);
    }

    // Add item to cart
    public function add() {
        $id_produk = $this->input->post('id_produk');
        $ukuran = $this->input->post('ukuran');
        $warna = $this->input->post('warna');
        $qty = (int)$this->input->post('qty');

        if ($qty <= 0) $qty = 1;

        // Get product details
        $produk = $this->Produk_model->get_product_by_id($id_produk);
        if (!$produk) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Get specific variation details (check stock)
        $variation = $this->Produk_model->get_variation_by_details($id_produk, $ukuran, $warna);
        if (!$variation) {
            $this->session->set_flashdata('error', 'Ukuran atau Warna yang dipilih tidak tersedia!');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Check if stock is sufficient
        if ($variation['stok'] < $qty) {
            $this->session->set_flashdata('error', 'Stok tidak mencukupi! Hanya tersisa ' . $variation['stok'] . ' pasang.');
            redirect($_SERVER['HTTP_REFERER']);
        }

        // Prepare cart data
        // For cart row matching, we use a unique ID based on product and variation
        $cart_data = array(
            'id'      => $id_produk,
            'qty'     => $qty,
            'price'   => $produk['harga'],
            'name'    => $produk['nama_produk'],
            'options' => array(
                'Size' => $ukuran,
                'Color' => $warna,
                'Image' => $produk['gambar'],
                'VariationId' => $variation['id_detail']
            )
        );

        // Insert into CodeIgniter Cart
        $this->cart->insert($cart_data);

        $this->session->set_flashdata('success', 'Produk berhasil ditambahkan ke keranjang!');
        redirect('cart');
    }

    // Update cart quantity
    public function update() {
        $rowid = $this->input->post('rowid');
        $qty = (int)$this->input->post('qty');

        if ($qty <= 0) {
            // Remove item
            $this->cart->remove($rowid);
            $this->session->set_flashdata('success', 'Item berhasil dihapus dari keranjang!');
            redirect('cart');
        }

        // Check stock before updating quantity
        $cart_item = $this->cart->product_options($rowid);
        $item = $this->cart->get_item($rowid);
        
        if ($item) {
            $id_produk = $item['id'];
            $ukuran = $item['options']['Size'];
            $warna = $item['options']['Color'];
            
            $variation = $this->Produk_model->get_variation_by_details($id_produk, $ukuran, $warna);
            if ($variation && $variation['stok'] < $qty) {
                $this->session->set_flashdata('error', 'Stok tidak mencukupi! Hanya tersisa ' . $variation['stok'] . ' pasang.');
                redirect('cart');
            }

            // Update quantity
            $data = array(
                'rowid' => $rowid,
                'qty'   => $qty
            );
            $this->cart->update($data);
            $this->session->set_flashdata('success', 'Keranjang berhasil diperbarui!');
        }

        redirect('cart');
    }

    // Remove single item
    public function remove($rowid) {
        if ($this->cart->remove($rowid)) {
            $this->session->set_flashdata('success', 'Item berhasil dihapus dari keranjang!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus item!');
        }
        redirect('cart');
    }

    // Clear whole cart
    public function clear() {
        $this->cart->destroy();
        $this->session->set_flashdata('success', 'Keranjang belanja dikosongkan!');
        redirect('cart');
    }
}
