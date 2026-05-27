<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pesanan_model');
        $this->load->model('Produk_model');
        $this->load->library('form_validation');
    }

    // Dashboard index showing charts, sales summaries, and recent activity
    public function index() {
        $data['title'] = 'Dashboard Admin - Toko Sepatu Premium';
        
        // Fetch rich stats from model
        $data['stats'] = $this->Pesanan_model->get_sales_stats();

        $this->load->view('admin/dashboard_view', $data);
    }

    // List all products in backend
    public function products() {
        $data['title'] = 'Manajemen Produk - Toko Sepatu Premium';
        $data['products'] = $this->Produk_model->get_all_products();
        $this->load->view('admin/products_list_view', $data);
    }

    // Add new product
    public function add_product() {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Produk Baru';
            $data['categories'] = $this->Produk_model->get_categories();
            $this->load->view('admin/product_form_view', $data);
        } else {
            // Handle image upload
            $config['upload_path']   = './assets/images/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = 'shoe_' . time();

            $this->load->library('upload', $config);
            $gambar = 'hero_banner.png'; // default fallback image

            if ($this->upload->do_upload('gambar')) {
                $upload_data = $this->upload->data();
                $gambar = $upload_data['file_name'];
            }

            $product_data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga'       => $this->input->post('harga'),
                'id_kategori' => $this->input->post('id_kategori'),
                'deskripsi'   => $this->input->post('deskripsi'),
                'gambar'      => $gambar
            ];

            $id_produk = $this->Produk_model->insert_product($product_data);

            // Handle variations (sizes & colors)
            $sizes = $this->input->post('sizes');
            $colors = $this->input->post('colors');
            $stocks = $this->input->post('stocks');

            if (!empty($sizes) && is_array($sizes)) {
                for ($i = 0; $i < count($sizes); $i++) {
                    if (!empty($sizes[$i]) && !empty($colors[$i])) {
                        $this->Produk_model->insert_variation([
                            'id_produk' => $id_produk,
                            'ukuran'    => $sizes[$i],
                            'warna'     => $colors[$i],
                            'stok'      => (int)$stocks[$i]
                        ]);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
            redirect('admin/products');
        }
    }

    // Edit product
    public function edit_product($id) {
        $product = $this->Produk_model->get_product_by_id($id);
        if (!$product) {
            show_404();
        }

        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Produk - ' . $product['nama_produk'];
            $data['product'] = $product;
            $data['categories'] = $this->Produk_model->get_categories();
            $data['variations'] = $this->Produk_model->get_product_variations($id);
            $this->load->view('admin/product_form_view', $data);
        } else {
            // Handle image upload
            $gambar = $product['gambar']; // keep old image

            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path']   = './assets/images/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 2048;
                $config['file_name']     = 'shoe_' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];
                }
            }

            $product_data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga'       => $this->input->post('harga'),
                'id_kategori' => $this->input->post('id_kategori'),
                'deskripsi'   => $this->input->post('deskripsi'),
                'gambar'      => $gambar
            ];

            $this->Produk_model->update_product($id, $product_data);

            // Re-sync variations (delete existing, then re-insert)
            $this->Produk_model->delete_variations($id);

            $sizes = $this->input->post('sizes');
            $colors = $this->input->post('colors');
            $stocks = $this->input->post('stocks');

            if (!empty($sizes) && is_array($sizes)) {
                for ($i = 0; $i < count($sizes); $i++) {
                    if (!empty($sizes[$i]) && !empty($colors[$i])) {
                        $this->Produk_model->insert_variation([
                            'id_produk' => $id,
                            'ukuran'    => $sizes[$i],
                            'warna'     => $colors[$i],
                            'stok'      => (int)$stocks[$i]
                        ]);
                    }
                }
            }

            $this->session->set_flashdata('success', 'Produk berhasil diperbarui!');
            redirect('admin/products');
        }
    }

    // Delete product
    public function delete_product($id) {
        if ($this->Produk_model->delete_product($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk!');
        }
        redirect('admin/products');
    }

    // List all incoming orders
    public function orders() {
        $data['title'] = 'Manajemen Pesanan - Toko Sepatu Premium';
        $orders = $this->Pesanan_model->get_all_orders();
        
        // Fetch items details for each order to present comprehensive list
        foreach ($orders as &$o) {
            $o['items'] = $this->Pesanan_model->get_order_details($o['id_pesanan']);
        }
        $data['orders'] = $orders;

        $this->load->view('admin/orders_list_view', $data);
    }

    // Confirm order payment (reduces shoe stocks)
    public function confirm_order($id) {
        if ($this->Pesanan_model->confirm_payment($id)) {
            $this->session->set_flashdata('success', 'Pembayaran berhasil dikonfirmasi! Stok produk telah dikurangi otomatis.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengonfirmasi pembayaran!');
        }
        redirect('admin/orders');
    }

    // Cancel order (restores stock if was Paid)
    public function cancel_order($id) {
        if ($this->Pesanan_model->cancel_order($id)) {
            $this->session->set_flashdata('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal membatalkan pesanan!');
        }
        redirect('admin/orders');
    }
}
