<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TransactionController extends Controller
{
    #[OA\Get(
        path: "/api/transactions",
        operationId: "indexTransactions",
        summary: "Get all transactions (Paginated)",
        description: "Fetch a list of all transactions for the authenticated user with pagination support.",
        security: [["bearerAuth" => []]],
        tags: ["Transactions"],
        parameters: [
            new OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer", default: 1)),
            new OA\Parameter(name: "per_page", in: "query", description: "Number of items per page", required: false, schema: new OA\Schema(type: "integer", default: 10))
        ]
    )]
    #[OA\Response(response: 200, description: "Paginated list of transactions")]
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $transactions = $request->user()->transactions()->latest()->paginate($perPage);

        return TransactionResource::collection($transactions)
            ->additional(['success' => true])
            ->response()
            ->setStatusCode(200);
    }


    #[OA\Post(path: "/api/transactions", operationId: "storeTransaction", summary: "Add a new transaction", description: "Creates a transaction (income or expense) and returns the created resource.", security: [["bearerAuth" => []]], tags: ["Transactions"])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "application/x-www-form-urlencoded",
            schema: new OA\Schema(
                required: ["title", "amount"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Grocery Store"),
                    new OA\Property(property: "amount", type: "number", format: "float", example: -50.25)
                ]
            )
        )
    )]
    #[OA\Response(response: 201, description: "Transaction created")]
    public function store(TransactionRequest $request): JsonResponse
    {
        $transaction = $request->user()->transactions()->create($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new TransactionResource($transaction),
            'message' => 'Transaction added successfully.',
        ], 201);
    }

    #[OA\Put(path: "/api/transactions/{id}", operationId: "updateTransaction", summary: "Update an existing transaction", description: "Updates the details of a specific transaction.", security: [["bearerAuth" => []]], tags: ["Transactions"], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))])]
    #[OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: "application/x-www-form-urlencoded",
            schema: new OA\Schema(
                required: ["title", "amount"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Updated Grocery Store"),
                    new OA\Property(property: "amount", type: "number", format: "float", example: -60.00)
                ]
            )
        )
    )]
    #[OA\Response(response: 200, description: "Transaction updated")]
    public function update(TransactionRequest $request, Transaction $transaction): JsonResponse
    {
        $this->authorizeAction($request, $transaction);

        $transaction->update($request->validated());

        return response()->json([
            'success' => true,
            'data'    => new TransactionResource($transaction),
            'message' => 'Transaction updated successfully.',
        ]);
    }

    #[OA\Delete(path: "/api/transactions/{id}", operationId: "deleteTransaction", summary: "Delete a transaction", description: "Removes a transaction from the database.", security: [["bearerAuth" => []]], tags: ["Transactions"], parameters: [new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))])]
    #[OA\Response(response: 200, description: "Transaction deleted")]
    public function destroy(Request $request, Transaction $transaction): JsonResponse
    {
        $this->authorizeAction($request, $transaction);

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction deleted successfully.',
        ]);
    }

    #[OA\Get(path: "/api/balance", operationId: "getBalance", summary: "Get total balance", description: "Calculates the sum of all transaction amounts for the authenticated user.", security: [["bearerAuth" => []]], tags: ["Transactions"])]
    #[OA\Response(response: 200, description: "Total balance details")]
    public function balance(Request $request): JsonResponse
    {
        $balance = (float) $request->user()->transactions()->sum('amount');

        return response()->json([
            'success' => true,
            'data'    => [
                'balance' => $balance,
                'status'  => $balance < 0 ? 'deficit' : 'surplus',
            ],
        ]);
    }

    /**
     * Ensure the user owns the transaction.
     */
    protected function authorizeAction(Request $request, Transaction $transaction): void
    {
        if ($transaction->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
