<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->get()->toArray();

        return response()->json([
            'invoices' => $invoices
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', Invoice::class);

        $request->validate([
            'supplied_at' => 'required|date',
            'comment' => 'string|nullable',
            'number' => 'required|string'
        ]);

        $invoice = Invoice::create($request->only(['supplied_at', 'number', 'comment']));

        return response()->json([
            'invoice' => $invoice
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->authorize('update', $invoice);

        $request->validate([
            'supplied_at' => 'required|date',
            'comment' => 'string',
            'number' => 'required|string'
        ]);

        $invoice->update($request->only(['supplied_at', 'number', 'comment']));

        return response()->json([
            'invoice' => $invoice
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->authorize('delete', $invoice);

        $invoice->delete();

        return response()->json([
            'invoice' => $invoice
        ]);
    }
}
