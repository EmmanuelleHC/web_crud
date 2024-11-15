<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;

class InvoiceController extends Controller
{
    // Display a listing of the invoices
    public function index()
    {
        $invoices = Invoice::with(['items','client'])->get();
        return response()->json($invoices);
    }

    // Store a new invoice
    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices',
            'invoice_date' => 'required|date',
            'client_number' => 'required',
            'client_address' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $grandTotal = 0;
        $itemsData = [];

        foreach ($request->items as $itemData) {
            $itemData['total_price'] = $itemData['quantity'] * $itemData['unit_price'];
            $grandTotal += $itemData['total_price'];
            $itemsData[] = $itemData; 
        }

        $invoice = Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'client_number' => $request->client_number,
            'client_address' => $request->client_address,
            'grand_total' => $grandTotal,
        ]);

        foreach ($itemsData as $itemData) {
            $invoice->items()->create($itemData);
        }

        return response()->json($invoice->load('items'), 201);
    }



    // Display a single invoice
    public function show($id)
    {
        $invoice = Invoice::with(['items','client'])->findOrFail($id);
        return response()->json($invoice);
    }

    // Update an invoice
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'invoice_date' => 'required|date',
            'client_number' => 'required',
            'client_address' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'client_number' => $request->client_number,
            'client_address' => $request->client_address,
        ]);

        $grandTotal = 0;
        $itemsData = [];

        foreach ($request->items as $itemData) {
            $itemData['total_price'] = $itemData['quantity'] * $itemData['unit_price'];
            $grandTotal += $itemData['total_price'];
            $itemsData[] = $itemData;
        }

        $invoice->items()->delete();
        foreach ($itemsData as $itemData) {
            $invoice->items()->create($itemData);
        }
        $invoice->update(['grand_total' => $grandTotal]);

        return response()->json($invoice->load('items'));
    }



    // Delete an invoice
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json(null, 204);
    }
    public function generatePdf($id)
    {
        $invoice = Invoice::with(['items', 'client'])->findOrFail($id);
    
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
    
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
    
        return $pdf->download('invoice_' . $invoice->invoice_number . '.pdf');
    }
    

}
