<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlternativeController extends Controller
{
    /**
     * Menampilkan daftar semua alternatif
     */
    public function index()
    {
        $alternatives = Alternative::all();
        return view('alternatives.index', compact('alternatives'));
    }
    
    /**
     * Menampilkan form untuk membuat alternatif baru
     */
    public function create()
    {
        return view('alternatives.create');
    }
    
    /**
     * Menyimpan alternatif baru ke database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:alternatives',
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        Alternative::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);
        
        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan detail alternatif dan nilai-nilainya
     */
    public function show(Alternative $alternative)
    {
        $alternative->load('values.criteria');
        return view('alternatives.show', compact('alternative'));
    }
    
    /**
     * Menampilkan form untuk mengedit alternatif
     */
    public function edit(Alternative $alternative)
    {
        return view('alternatives.edit', compact('alternative'));
    }
    
    /**
     * Mengupdate alternatif di database
     */
    public function update(Request $request, Alternative $alternative)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:alternatives,code,' . $alternative->id,
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $alternative->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);
        
        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }
    
    /**
     * Menghapus alternatif dari database
     */
    public function destroy(Alternative $alternative)
    {
        $alternative->delete();
        
        return redirect()->route('alternatives.index')
            ->with('success', 'Alternatif berhasil dihapus.');
    }
    
    /**
     * Menampilkan form untuk mengatur nilai alternatif
     */
    public function editValues(Alternative $alternative)
    {
        $criteria = Criteria::orderBy('rank')->get();
        $values = $alternative->values->pluck('value', 'criteria_id')->toArray();
        
        return view('alternatives.values', compact('alternative', 'criteria', 'values'));
    }
    
    /**
     * Menyimpan nilai alternatif
     */
    public function storeValues(Request $request, Alternative $alternative)
    {
        $criteria = Criteria::all();
        
        $rules = [];
        foreach ($criteria as $criterion) {
            $rules["values.{$criterion->id}"] = 'required|numeric';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        foreach ($request->values as $criteriaId => $value) {
            AlternativeValue::updateOrCreate(
                [
                    'alternative_id' => $alternative->id,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'value' => $value,
                ]
            );
        }
        
        return redirect()->route('alternatives.show', $alternative)
            ->with('success', 'Nilai alternatif berhasil disimpan.');
    }
}