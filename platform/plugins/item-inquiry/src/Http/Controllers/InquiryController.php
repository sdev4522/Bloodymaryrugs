<?php

namespace Botble\ItemInquiry\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\ItemInquiry\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends BaseController
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'name'       => 'required|string|max:255',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email|max:255',
            'message'    => 'required|string|max:2000',
        ]);

        Inquiry::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inquiry submitted successfully!'
        ]);
    }

    public function index()
    {
        $inquiries = Inquiry::latest()->paginate(20);
        return view('item-inquiry::index', compact('inquiries'));
    }


    public function detail($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('item-inquiry::inquiry', compact('inquiry'));
    }

    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return redirect()->route('index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}
