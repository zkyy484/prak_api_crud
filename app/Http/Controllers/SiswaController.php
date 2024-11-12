<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SiswaController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Siswa::all(), 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving students: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data siswa',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s]+$/'
                ],
                'kelas' => [
                    'required',
                    'string',
                    'regex:/^X{0,3}(IX|V?I{0,3})\s?(IPA|IPS)\s?\d{1}$/'
                ],
                'umur' => [
                    'required',
                    'integer',
                    'between:6,18'
                ],
            ]);

            $siswa = Siswa::create($validatedData);
            return response()->json($siswa, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing student data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal menyimpan data siswa',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json($siswa, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Siswa tidak ditemukan',
                'message' => 'Data siswa dengan ID ' . $id . ' tidak ditemukan.'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::findOrFail($id);

            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer',
            ]);

            $siswa->update($validatedData);
            return response()->json($siswa, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validasi gagal',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal memperbarui data siswa',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            return response()->json([
                'message' => 'Data Berhasil dihapus',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Gagal menghapus data siswa',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
