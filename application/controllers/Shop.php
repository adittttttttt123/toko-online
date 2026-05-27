<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produk_model');
        $this->load->model('Pesanan_model');
    }

    // Catalog page with product listing and sidebar filters
    public function index() {
        // Collect filters from GET request
        $filters = [
            'category' => $this->input->get('category'),
            'size'     => $this->input->get('size'),
            'color'    => $this->input->get('color'),
            'price_max'=> $this->input->get('price_max'),
            'search'   => $this->input->get('search')
        ];

        $data['title'] = 'Katalog Sepatu Premium - Toko Sepatu';
        $data['products'] = $this->Produk_model->get_all_products($filters);
        $data['categories'] = $this->Produk_model->get_categories();
        
        // Fetch unique sizes and colors across database for sidebar filters
        $attributes = $this->Produk_model->get_unique_attributes();
        $data['sizes'] = $attributes['sizes'];
        $data['colors'] = $attributes['colors'];

        // Keep active filters in array to populate UI fields
        $data['active_filters'] = $filters;

        $this->load->view('katalog_view', $data);
    }

    // Product detail page
    public function detail($id) {
        $product = $this->Produk_model->get_product_by_id($id);
        if (!$product) {
            show_404();
        }

        $data['title'] = $product['nama_produk'] . ' - Detail Produk';
        $data['product'] = $product;
        
        // Get size & color variations with stock for this product
        $data['variations'] = $this->Produk_model->get_product_variations($id);

        $this->load->view('detail_view', $data);
    }

    // Checkout page
    public function checkout() {
        if ($this->cart->total_items() <= 0) {
            $this->session->set_flashdata('error', 'Keranjang Anda kosong! Silakan berbelanja terlebih dahulu.');
            redirect('cart');
        }

        $data['title'] = 'Proses Checkout - Toko Sepatu Premium';
        $data['cart_items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();

        // Provinces and Cities for shipping simulation
        $data['provinces'] = $this->_get_provinces();

        $this->load->view('checkout_view', $data);
    }

    // Process checkout form submission
    public function process_checkout() {
        if ($this->cart->total_items() <= 0) {
            $this->session->set_flashdata('error', 'Keranjang Anda kosong!');
            redirect('cart');
        }

        $this->form_validation->set_rules('nama_penerima', 'Nama Penerima', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'required');
        $this->form_validation->set_rules('provinsi', 'Provinsi', 'required');
        $this->form_validation->set_rules('kota', 'Kota', 'required');
        $this->form_validation->set_rules('kurir', 'Kurir', 'required');
        $this->form_validation->set_rules('layanan', 'Layanan Pengiriman', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->checkout();
            return;
        }

        // Determine shipping cost based on courier and service selected
        $layanan_raw = $this->input->post('layanan'); // e.g. "REG|15000" or "YES|22000"
        $layanan_parts = explode('|', $layanan_raw);
        $layanan_nama = $layanan_parts[0];
        $ongkir = isset($layanan_parts[1]) ? (float)$layanan_parts[1] : 15000;

        // Generate invoice number e.g. INV-YYYYMMDD-XXXX
        $invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $order_data = [
            'invoice_no'    => $invoice_no,
            'nama_penerima' => $this->input->post('nama_penerima'),
            'email'         => $this->input->post('email'),
            'telepon'       => $this->input->post('telepon'),
            'alamat'        => $this->input->post('alamat'),
            'provinsi'      => $this->input->post('provinsi'),
            'kota'          => $this->input->post('kota'),
            'kurir'         => $this->input->post('kurir'),
            'layanan'       => $layanan_nama,
            'ongkir'        => $ongkir,
            'total_bayar'   => $this->cart->total() + $ongkir,
            'status'        => 'Pending', // Initially pending until payment is confirmed
            'tgl_pesan'     => date('Y-m-d H:i:s')
        ];

        // Create order in database
        $id_pesanan = $this->Pesanan_model->create_order($order_data, $this->cart->contents());

        if ($id_pesanan) {
            // Empty the cart
            $this->cart->destroy();
            
            // Redirect to success screen
            redirect('order/success/' . $id_pesanan);
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat memproses pesanan Anda. Silakan coba lagi.');
            redirect('checkout');
        }
    }

    // Success order screen
    public function order_success($id) {
        $order = $this->Pesanan_model->get_order_by_id($id);
        if (!$order) {
            show_404();
        }

        $data['title'] = 'Pesanan Berhasil Dibuat - ' . $order['invoice_no'];
        $data['order'] = $order;
        $data['items'] = $this->Pesanan_model->get_order_details($id);

        $this->load->view('order_success_view', $data);
    }

    // AJAX Endpoint for fetching cities based on Province (Simulates RajaOngkir dynamic listing)
    public function get_cities_ajax() {
        $province = $this->input->post('province');
        $cities = $this->_get_cities($province);
        
        echo json_encode($cities);
    }

    // AJAX Endpoint for fetching shipping costs (Simulates RajaOngkir checking cost)
    public function get_shipping_costs_ajax() {
        $city = $this->input->post('city');
        $courier = $this->input->post('courier');

        // Simulate costs depending on courier and destination
        $services = [];
        if ($courier === 'JNE') {
            $services = [
                ['service' => 'OKE (Ongkos Kirim Ekonomis)', 'cost' => 12000, 'etd' => '3-4 Hari'],
                ['service' => 'REG (Reguler)', 'cost' => 17000, 'etd' => '2-3 Hari'],
                ['service' => 'YES (Yakin Esok Sampai)', 'cost' => 28000, 'etd' => '1 Hari']
            ];
        } elseif ($courier === 'J&T') {
            $services = [
                ['service' => 'EZ (Reguler)', 'cost' => 15000, 'etd' => '2-3 Hari'],
                ['service' => 'SPS (Sameday)', 'cost' => 32000, 'etd' => '1 Hari']
            ];
        } elseif ($courier === 'POS') {
            $services = [
                ['service' => 'Kilat Khusus', 'cost' => 14000, 'etd' => '3-5 Hari'],
                ['service' => 'Express Next Day', 'cost' => 25000, 'etd' => '1 Hari']
            ];
        }

        // Adjust cost slightly based on distance (city names)
        if (strpos($city, 'Jakarta') === false) {
            // Outer region, add 5000 IDR
            foreach ($services as &$s) {
                $s['cost'] += 6000;
            }
        }

        echo json_encode($services);
    }

    // --- Private Helper Methods for RajaOngkir-like Simulations ---
    private function _get_provinces() {
        return [
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'Jawa Timur',
            'Banten',
            'DI Yogyakarta',
            'Bali'
        ];
    }

    private function _get_cities($province) {
        $cities = [];
        switch ($province) {
            case 'DKI Jakarta':
                $cities = ['Jakarta Selatan', 'Jakarta Pusat', 'Jakarta Barat', 'Jakarta Utara', 'Jakarta Timur'];
                break;
            case 'Jawa Barat':
                $cities = ['Bandung', 'Bogor', 'Depok', 'Bekasi', 'Cirebon', 'Tasikmalaya'];
                break;
            case 'Jawa Tengah':
                $cities = ['Semarang', 'Surakarta (Solo)', 'Magelang', 'Pekalongan', 'Salatiga'];
                break;
            case 'Jawa Timur':
                $cities = ['Surabaya', 'Malang', 'Sidoarjo', 'Gresik', 'Kediri', 'Madiun'];
                break;
            case 'Banten':
                $cities = ['Tangerang', 'Serang', 'Cilegon', 'Tangerang Selatan'];
                break;
            case 'DI Yogyakarta':
                $cities = ['Yogyakarta', 'Sleman', 'Bantul', 'Kulon Progo', 'Gunungkidul'];
                break;
            case 'Bali':
                $cities = ['Denpasar', 'Badung', 'Gianyar', 'Tabanan', 'Buleleng'];
                break;
            default:
                $cities = ['Kota Lainnya'];
        }
        return $cities;
    }
}
