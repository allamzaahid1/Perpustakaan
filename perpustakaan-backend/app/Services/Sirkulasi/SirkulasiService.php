<?php

namespace App\Services\Sirkulasi;

use App\Models\Sirkulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SirkulasiService
{
    /**
     * Create a new sirkulasi record.
     */
    // Menghindari middleware auth di method create
    public function create(Request $request)
    {
        $tglPengembalianRules = 'nullable|date';
        if ($request->input('status') === 'kembali') {
            $tglPengembalianRules .= '|required';
        }

        $fields = $request->validate([
            'id_buku' => 'required|exists:bukus,id',
            'id_user' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali' => 'required|date',
            'tgl_pengembalian' => $tglPengembalianRules,
            'status' => 'required|string|in:dipinjam,kembali',
        ]);

        $sirkulasi = Sirkulasi::create($fields);

        return $sirkulasi->load(['buku:id,judul', 'user:id,name']);
    }

    /**
     * Update an existing sirkulasi record.
     */
    public function update(Request $request, $id)
    {
        $tglPengembalianRules = 'nullable|date';
        if ($request->input('status') === 'kembali') {
            $tglPengembalianRules .= '|required';
        }

        $fields = $request->validate([
            'id_buku' => 'sometimes|exists:bukus,id',
            'id_user' => 'sometimes|exists:users,id',
            'tgl_pinjam' => 'sometimes|date',
            'tgl_kembali' => 'sometimes|date',
            'tgl_pengembalian' => $tglPengembalianRules,
            'status' => 'sometimes|string|in:dipinjam,kembali',
        ]);

        $sirkulasi = Sirkulasi::findOrFail($id);

        $sirkulasi->update($fields);

        return $sirkulasi->load(['buku:id,judul', 'user:id,name']);
    }

    /**
     * Delete a sirkulasi record.
     */
    public function delete($id)
    {
        $sirkulasi = Sirkulasi::findOrFail($id);
        $sirkulasi->delete();

        return ['message' => 'Sirkulasi berhasil dihapus'];
    }


    /**
     * Retrieve a single sirkulasi record by ID.
     */
    public function getById($id)
    {
        return Sirkulasi::with(['buku:id,judul', 'user:id,name'])->findOrFail($id);
    }

    public function getAll()
    {
        return Sirkulasi::with(['buku:id,judul', 'user:id,name'])->get();
    }

    /**
     * Retrieve all sirkulasi records with optional filters.
     */
    public function index(Request $request)
    {
        $query = Sirkulasi::with(['buku:id,judul', 'user:id,name']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('tgl_pinjam', [$request->start_date, $request->end_date]);
        }

        return $query->get();
    }

    /**
     * Retrieve a single sirkulasi record by ID.
     */
    public function show(Sirkulasi $sirkulasi)
    {
        // Pastikan relasi dengan buku dan user sudah terload dengan benar
        return $sirkulasi->load(['buku:id,judul', 'user:id,name']);
    }
}
