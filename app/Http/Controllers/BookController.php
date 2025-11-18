<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $books = Book::orderBy('created_at', 'desc')->get();

            Log::info('Retrieved books successfully', ['count' => $books->count()]);

            return response()->json([
                'success' => true,
                'data' => $books,
                'message' => 'Data buku berhasil diambil',
                'count' => $books->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving books: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data buku: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request): JsonResponse
{
    try {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kategori' => 'required|string|max:255',
            'status' => 'required|in:Tersedia,Dipinjam',
            'isbn' => 'nullable|string|max:20',
            'jumlah_halaman' => 'nullable|integer|min:1',
            'deskripsi' => 'nullable|string'
        ]);

        $book = Book::create($validated);

        return response()->json([
            'success' => true,
            'data' => $book,
            'message' => 'Buku berhasil ditambahkan'
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal menambahkan buku: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                Log::warning('Book not found', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $book,
                'message' => 'Data buku berhasil diambil'
            ]);

        } catch (\Exception $e) {
            Log::error('Error retrieving book: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data buku: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            Log::info('Update book request', ['id' => $id, 'data' => $request->all()]);

            $book = Book::find($id);

            if (!$book) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'judul' => 'sometimes|required|string|max:255',
                'pengarang' => 'sometimes|required|string|max:255',
                'penerbit' => 'sometimes|required|string|max:255',
                'tahun_terbit' => 'sometimes|required|integer|min:1900|max:' . (date('Y') + 1),
                'kategori' => 'sometimes|required|string|max:255',
                'status' => 'sometimes|required|in:Tersedia,Dipinjam',
                'isbn' => 'nullable|string|max:20',
                'jumlah_halaman' => 'nullable|integer|min:1',
                'deskripsi' => 'nullable|string'
            ]);

            $book->update($validated);

            Log::info('Book updated successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'data' => $book,
                'message' => 'Buku berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Book update validation failed', $e->errors());

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating book: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui buku: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                Log::warning('Book not found for deletion', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'message' => 'Buku tidak ditemukan'
                ], 404);
            }

            $book->delete();

            Log::info('Book deleted successfully', ['id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting book: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus buku: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search books
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $keyword = $request->get('q') ?? $request->get('keyword') ?? '';

            Log::info('Searching books', ['keyword' => $keyword]);

            $books = Book::where('judul', 'like', "%{$keyword}%")
                        ->orWhere('pengarang', 'like', "%{$keyword}%")
                        ->orWhere('penerbit', 'like', "%{$keyword}%")
                        ->orWhere('kategori', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%")
                        ->orderBy('created_at', 'desc')
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $books,
                'message' => 'Pencarian berhasil',
                'count' => $books->count(),
                'keyword' => $keyword
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching books: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Bulk store books
     */
    public function bulkStore(Request $request): JsonResponse
    {
        try {
            $booksData = $request->all();

            Log::info('Bulk create request', ['count' => count($booksData)]);

            $created = [];
            $errors = [];

            foreach ($booksData as $index => $bookData) {
                try {
                    $validator = validator($bookData, [
                        'judul' => 'required|string|max:255',
                        'pengarang' => 'required|string|max:255',
                        'penerbit' => 'required|string|max:255',
                        'tahun_terbit' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                        'kategori' => 'required|string|max:255',
                        'status' => 'required|in:Tersedia,Dipinjam',
                        'isbn' => 'nullable|string|max:20',
                        'jumlah_halaman' => 'nullable|integer|min:1',
                        'deskripsi' => 'nullable|string'
                    ]);

                    if ($validator->fails()) {
                        $errors[] = [
                            'index' => $index,
                            'judul' => $bookData['judul'] ?? 'Unknown',
                            'errors' => $validator->errors()->toArray()
                        ];
                        continue;
                    }

                    $book = Book::create($bookData);
                    $created[] = $book;

                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'judul' => $bookData['judul'] ?? 'Unknown',
                        'error' => $e->getMessage()
                    ];
                }
            }

            Log::info('Bulk create completed', [
                'created' => count($created),
                'errors' => count($errors)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bulk create completed',
                'data' => [
                    'created' => $created,
                    'errors' => $errors
                ],
                'summary' => [
                    'total_processed' => count($booksData),
                    'successful' => count($created),
                    'failed' => count($errors)
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in bulk store: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal dalam bulk create: ' . $e->getMessage()
            ], 500);
        }
    }
}
