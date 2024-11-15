<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Invoice;
use App\Models\Client;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_invoices()
    {
        $this->withoutMiddleware();

        Invoice::factory()->count(5)->create();

        $response = $this->getJson(route('invoices.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_store_creates_invoice_and_items()
    {
        $this->withoutMiddleware();

        $client = Client::factory()->create(); // Ensure a client exists
        $data = [
            'invoice_date' => now()->toDateString(),
            'client_id' => $client->id,
            'items' => [
                [
                    'item_id' => 1,
                    'item_name' => 'Test Item',
                    'quantity' => 2,
                    'unit_price' => 50.00,
                ],
                [
                    'item_id' => 2,
                    'item_name' => 'Another Item',
                    'quantity' => 1,
                    'unit_price' => 100.00,
                ],
            ],
        ];

        $response = $this->postJson(route('invoices.store'), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'invoice_number',
            'items' => [
                '*' => ['id', 'item_id', 'item_name', 'quantity', 'unit_price', 'total_price']
            ]
        ]);
    }

    public function test_show_returns_invoice_with_items_and_client()
    {
        $this->withoutMiddleware();

        $invoice = Invoice::factory()->hasItems(3)->create();

        $response = $this->getJson(route('invoices.show', $invoice->id));

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $invoice->id]);
        $response->assertJsonCount(3, 'items');
    }

    public function test_update_modifies_invoice_items()
    {
        $this->withoutMiddleware();

        $invoice = Invoice::factory()->hasItems(3)->create();
        $newItems = [
            [
                'item_id' => 4,
                'item_name' => 'Updated Item',
                'quantity' => 2,
                'unit_price' => 75.00,
            ],
        ];

        $response = $this->putJson(route('invoices.update', $invoice->id), ['items' => $newItems]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['grand_total' => 150.00]);
        $response->assertJsonCount(1, 'items');
    }

    public function test_destroy_deletes_invoice()
    {
        $this->withoutMiddleware();

        $invoice = Invoice::factory()->create();

        $response = $this->deleteJson(route('invoices.destroy', $invoice->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('invoices', ['id' => $invoice->id]);
    }

    public function test_generate_pdf_returns_pdf_file()
    {
        $this->withoutMiddleware();

        $invoice = Invoice::factory()->hasItems(2)->create();

        $response = $this->getJson(route('invoices.generatePdf', ['id' => $invoice->id]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
