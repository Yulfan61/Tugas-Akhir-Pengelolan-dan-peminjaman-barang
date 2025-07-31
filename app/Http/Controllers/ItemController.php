<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category', 'location')->paginate(10);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:items',
            'name' => 'required',
            'category_id' => 'required',
            'location_id' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        Item::create($request->all());

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $locations = Location::all();
        return view('items.edit', compact('item', 'categories', 'locations'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required',
            'category_id' => 'required',
            'location_id' => 'required',
            'stock' => 'required|integer|min:0',  // validasi stock
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }


    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function getItemsByLocation($locationId)
    {
        $items = Item::where('location_id', $locationId)->get(['id', 'name']);
        return response()->json($items);
    }

    public function damaged()
    {
        $items = Item::whereIn('condition', ['rusak ringan', 'rusak berat'])->get();
        return view('items.damaged', compact('items'));
    }

}
