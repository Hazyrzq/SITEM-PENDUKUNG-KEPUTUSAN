<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\User;
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
        // Ambil alternatif yang dibuat oleh admin (user_id = null)
        $adminAlternatives = Alternative::whereNull('user_id')->get();
        
        // Ambil alternatif yang dibuat oleh user
        $userAlternatives = Alternative::whereNotNull('user_id')->with('user')->get();
        
        return view('admin.alternatives.index', compact('adminAlternatives', 'userAlternatives'));
    }
    
    /**
     * Menampilkan daftar alternatif berdasarkan user yang membuatnya
     */
    public function userAlternatives($userId)
    {
        $user = User::findOrFail($userId);
        $alternatives = Alternative::where('user_id', $userId)->get();
        
        return view('admin.alternatives.user-alternatives', compact('alternatives', 'user'));
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
            'user_id' => null, // Ditambahkan untuk kejelasan bahwa ini alternatif admin
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
        
        // Jika alternatif dibuat oleh user, tampilkan informasi user
        $creator = null;
        if ($alternative->user_id) {
            $creator = User::find($alternative->user_id);
        }
            
        return view('admin.alternatives.show', compact('alternative', 'userValueCount', 'creator'));
    }
    
    /**
     * Menampilkan form untuk mengedit alternatif
     */
    public function edit(Alternative $alternative)
    {
        // Admin hanya bisa mengedit alternatif yang dibuat oleh admin
        if ($alternative->user_id !== null) {
            return redirect()->route('admin.alternatives.index')
                ->with('error', 'Admin hanya dapat mengedit alternatif yang dibuat oleh admin.');
        }
        
        return view('admin.alternatives.edit', compact('alternative'));
    }
    
    /**
     * Mengupdate alternatif di database
     */
    public function update(Request $request, Alternative $alternative)
    {
        // Admin hanya bisa mengedit alternatif yang dibuat oleh admin
        if ($alternative->user_id !== null) {
            return redirect()->route('admin.alternatives.index')
                ->with('error', 'Admin hanya dapat mengedit alternatif yang dibuat oleh admin.');
        }
        
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
        
        $user = User::find($userId);
            
        return view('admin.alternatives.user-values', compact('alternative', 'values', 'userId', 'user'));
    }
    
    /**
     * Melihat semua alternatif yang sudah dinilai
     */
    public function ratedAlternatives()
    {
        $alternatives = Alternative::whereHas('values')->get();
        
        foreach ($alternatives as $alternative) {
            $alternative->user_count = AlternativeValue::where('alternative_id', $alternative->id)
                ->distinct('user_id')
                ->count('user_id');
                
            // Cek jika alternatif dibuat oleh user
            if ($alternative->user_id) {
                $alternative->creator = User::find($alternative->user_id);
            }
        }
        
        return view('admin.alternatives.rated', compact('alternatives'));
    }
}