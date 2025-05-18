<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlternativeController extends Controller
{
      protected array $middleware = ['admin'];

    
    /**
     * Menampilkan daftar semua alternatif
     */
    public function index()
    {
        $alternatives = Alternative::all();
        return view('admin.alternatives.index', compact('alternatives'));
    }
    
    /**
     * Menampilkan form untuk membuat alternatif baru
     */
    public function create()
    {
        return view('admin.alternatives.create');
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
        
        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Alternatif berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan detail alternatif
     */
    public function show(Alternative $alternative)
    {
        // Menampilkan jumlah user yang telah memberi nilai untuk alternatif ini
        $userValueCount = AlternativeValue::where('alternative_id', $alternative->id)
            ->distinct('user_id')
            ->count('user_id');
            
        return view('admin.alternatives.show', compact('alternative', 'userValueCount'));
    }
    
    /**
     * Menampilkan form untuk mengedit alternatif
     */
    public function edit(Alternative $alternative)
    {
        return view('admin.alternatives.edit', compact('alternative'));
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
        
        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Alternatif berhasil diperbarui.');
    }
    
    /**
     * Menghapus alternatif dari database
     */
    public function destroy(Alternative $alternative)
    {
        // Hapus semua nilai alternatif
        AlternativeValue::where('alternative_id', $alternative->id)->delete();
        
        // Hapus alternatif
        $alternative->delete();
        
        return redirect()->route('admin.alternatives.index')
            ->with('success', 'Alternatif berhasil dihapus.');
    }
    
    /**
     * Melihat nilai-nilai yang diberikan oleh user untuk alternatif tertentu
     */
    public function viewUserValues(Alternative $alternative, $userId)
    {
        $values = AlternativeValue::where('alternative_id', $alternative->id)
            ->where('user_id', $userId)
            ->with('criteria')
            ->get();
            
        return view('admin.alternatives.user-values', compact('alternative', 'values', 'userId'));
    }
}