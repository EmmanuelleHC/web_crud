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

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'client_id' => 'required',
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $today = now()->format('Ymd'); 
        $lastInvoice = Invoice::whereDate('created_at', now()->toDateString())
                            ->orderBy('id', 'desc')
                            ->first();

        $runningNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 8)) + 1 : 1;
        $invoiceNumber = $today . str_pad($runningNumber, 6, '0', STR_PAD_LEFT);

        $grandTotal = 0;
        $itemsData = [];

        foreach ($request->items as $itemData) {
            $itemData['total_price'] = $itemData['quantity'] * $itemData['unit_price'];
            $grandTotal += $itemData['total_price'];
            $itemsData[] = $itemData;
        }

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $request->invoice_date,
            'client_id' => $request->client_id,
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.item_id' => 'required|integer|distinct',
            'items.*.item_name' => 'required|string|distinct',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
    
        $invoice = Invoice::findOrFail($id);
    
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
