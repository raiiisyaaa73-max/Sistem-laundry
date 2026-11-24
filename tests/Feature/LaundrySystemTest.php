<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundrySystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $kasir;

    protected Layanan $reguler;

    protected Layanan $ekspres;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@laundry.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->kasir = User::create([
            'name' => 'Kasir Test',
            'email' => 'kasir@laundry.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        $this->reguler = Layanan::create([
            'nama_layanan' => 'Reguler',
            'harga_per_kg' => 7000,
            'estimasi_waktu' => '3-4 hari',
        ]);

        $this->ekspres = Layanan::create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        $responseHome = $this->get('/');
        $responseHome->assertRedirect('/login');
    }

    public function test_login_success_for_admin_and_kasir(): void
    {
        // Test Admin Login
        $response = $this->post('/login', [
            'email' => 'admin@laundry.com',
            'password' => 'password',
        ]);
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->admin);

        // Logout
        $this->post('/logout');
        $this->assertGuest();

        // Test Kasir Login
        $responseKasir = $this->post('/login', [
            'email' => 'kasir@laundry.com',
            'password' => 'password',
        ]);
        $responseKasir->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->kasir);
    }

    public function test_dashboard_displays_correct_metrics(): void
    {
        Transaksi::create([
            'layanan_id' => $this->reguler->id,
            'nama_pelanggan' => 'Andi',
            'no_hp' => '081234567890',
            'berat' => 3.0,
            'total_harga' => 21000,
            'status' => 'Selesai',
        ]);

        Transaksi::create([
            'layanan_id' => $this->ekspres->id,
            'nama_pelanggan' => 'Dewi',
            'no_hp' => '089876543210',
            'berat' => 2.0,
            'total_harga' => 24000,
            'status' => 'Proses',
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Transaksi');
        $response->assertSee('21.000'); // Selesai revenue
        $response->assertSee('Andi');
        $response->assertSee('Dewi');
    }

    public function test_admin_can_crud_layanan(): void
    {
        // Create
        $response = $this->actingAs($this->admin)->post('/layanan', [
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'estimasi_waktu' => '5 jam selesai',
        ]);
        $response->assertRedirect('/layanan');
        $this->assertDatabaseHas('layanans', ['nama_layanan' => 'Superkilat', 'harga_per_kg' => 25000]);

        $layanan = Layanan::where('nama_layanan', 'Superkilat')->first();

        // Update
        $responseUpdate = $this->actingAs($this->admin)->put("/layanan/{$layanan->id}", [
            'nama_layanan' => 'Superkilat VIP',
            'harga_per_kg' => 28000,
            'estimasi_waktu' => '3 jam selesai',
        ]);
        $responseUpdate->assertRedirect('/layanan');
        $this->assertDatabaseHas('layanans', ['nama_layanan' => 'Superkilat VIP', 'harga_per_kg' => 28000]);

        // Delete
        $responseDelete = $this->actingAs($this->admin)->delete("/layanan/{$layanan->id}");
        $responseDelete->assertRedirect('/layanan');
        $this->assertDatabaseMissing('layanans', ['id' => $layanan->id]);
    }

    public function test_kasir_cannot_modify_or_delete_layanan(): void
    {
        // Kasir can view layanans
        $responseView = $this->actingAs($this->kasir)->get('/layanan');
        $responseView->assertStatus(200);

        // Kasir cannot create
        $responseCreate = $this->actingAs($this->kasir)->post('/layanan', [
            'nama_layanan' => 'Ilegal Service',
            'harga_per_kg' => 15000,
            'estimasi_waktu' => '1 hari',
        ]);
        $responseCreate->assertStatus(403);

        // Kasir cannot update
        $responseUpdate = $this->actingAs($this->kasir)->put("/layanan/{$this->reguler->id}", [
            'nama_layanan' => 'Hacked Reguler',
            'harga_per_kg' => 1000,
            'estimasi_waktu' => '1 hari',
        ]);
        $responseUpdate->assertStatus(403);

        // Kasir cannot delete
        $responseDelete = $this->actingAs($this->kasir)->delete("/layanan/{$this->reguler->id}");
        $responseDelete->assertStatus(403);
    }

    public function test_kasir_and_admin_can_record_transaction_with_auto_calculated_price(): void
    {
        // Kasir records transaction: 4.5 kg Reguler @ Rp 7.000 = Rp 31.500
        $response = $this->actingAs($this->kasir)->post('/transaksi', [
            'nama_pelanggan' => 'Rina Wijaya',
            'no_hp' => '081344556677',
            'layanan_id' => $this->reguler->id,
            'berat' => 4.5,
        ]);

        $response->assertRedirect('/transaksi');
        $this->assertDatabaseHas('transaksis', [
            'nama_pelanggan' => 'Rina Wijaya',
            'berat' => 4.5,
            'total_harga' => 31500,
            'status' => 'Proses',
        ]);
    }

    public function test_can_update_transaction_status_from_proses_to_selesai(): void
    {
        $transaksi = Transaksi::create([
            'layanan_id' => $this->ekspres->id,
            'nama_pelanggan' => 'Farhan',
            'no_hp' => '081299887766',
            'berat' => 3.0,
            'total_harga' => 36000,
            'status' => 'Proses',
        ]);

        // Kasir updates status to Selesai
        $response = $this->actingAs($this->kasir)->patch("/transaksi/{$transaksi->id}/status", [
            'status' => 'Selesai',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('transaksis', [
            'id' => $transaksi->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_can_view_nota_and_cetak_laporan(): void
    {
        $transaksi = Transaksi::create([
            'layanan_id' => $this->reguler->id,
            'nama_pelanggan' => 'Tono',
            'no_hp' => '081122334455',
            'berat' => 2.0,
            'total_harga' => 14000,
            'status' => 'Selesai',
        ]);

        $responseNota = $this->actingAs($this->kasir)->get("/transaksi/{$transaksi->id}/nota");
        $responseNota->assertStatus(200);
        $responseNota->assertSee('Tono');
        $responseNota->assertSee('14.000');

        $responseLaporan = $this->actingAs($this->kasir)->get('/transaksi/laporan/cetak');
        $responseLaporan->assertStatus(200);
        $responseLaporan->assertSee('LAPORAN TRANSAKSI');
    }
}
