<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\UserType;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request){
        $user = Auth::user();

        $host = $user->hosts()->first();

        if (!$host){
            throw ValidationException::withMessages([
                'host' => ['Você precisa ter um anfitrião configurado para adicionar produtos'],
            ]);
        }

        $request->validate([
            'imageBase64' => 'required|string',
            'name' => 'required|string|max:255',
            'link' => 'nullable|string|max:2048',
        ]);

        $product = Product::create([
            'hostid'=> $host->id,
            'productimg'=> $request['imageBase64'],
            'name'=> $request['name'],
            'link'=> $request['link'],
        ]);

        return response()->json(['message' => 'Produto adicionado com sucesso!', 'product' => $product], 201);

    }

    public function removeProduct(Request $request, $productId)
    {
        $user = Auth::user();

        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        $product->load('host.user');

        if (!$product->host || !$product->host->user) {
            return response()->json(['message' => 'Não autorizado a remover este produto.'], 403);
        }

        if ($product->host->user->id !== $user->id) {
            return response()->json(['message'=> 'Você não tem permissão para remover este produto'], 404);
        }

        try {
            $product->delete();
            return response()->json(['message'=> 'Produto removido com sucesso'], 200);

        } catch (\Exception $e) {
            return response()->json(['message'=> $e], 500);
        }

    }

    public function storeProductId(Request $request, string $productId)
    {
        $user = Auth::user();

        $product = Product::findOrFail($productId);

        $request->validate( [
            'guestNames' => 'required|array|min:1',
            'guestNames.*.name' => 'required|string|max:255',
        ]);

        if ($user->typeuser != UserType::Guest) {
            return response()->json(['message' => 'Apenas convidados podem escolher produtos'], 403);
        }

        if ($product->chooseproducts()->exists()) {
            return response()->json(['message' => 'Este produto já foi escolhido'], 422);
        }

        DB::beginTransaction();
        try {
            $chooseProduct = $product->chooseProducts()->create([
                'productid' => $product->id,
                'userid' => $user->id,
            ]);

            foreach ($request->input('guestNames') as $guest) {
                $chooseProduct->guestNames()->create([
                    'name' => $guest['name'],
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Produto escolhido com sucesso!'], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e], 500);
        }
    }
}
