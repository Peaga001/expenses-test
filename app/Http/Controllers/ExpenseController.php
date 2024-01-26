<?php

namespace App\Http\Controllers;

//Requests
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\Expense\{
    Update,
    Store
};

//Resources
use App\Http\Resources\ExpenseResource;

//Models
use App\Models\{
    Expense,
    User
};

//Miscellaneous
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\CreatedExpense;

/**
 * @OA\Info(
 *     title="Desafio Onfly",
 *     version="1.0.0",
 *     description="Api referente ao crud de despesas.",
 *     @OA\Contact(
 *         email="phalmeida001@gmail.com",
 *         name="Pedro Henrique"
 *     ),
 * )
 */
class ExpenseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/expenses",
     *     tags={"expenses"},
     *     summary="Retorna todas as despesas",
     *     description="Retorna todas as despesas registradas no sistema",
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso na requisição",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="value", type="string"),
     *                      @OA\Property(property="date", type="string"),
     *                      @OA\Property(property="user_id", type="integer")
     *                  )
     *             ),
     *         ),
     *     ),
     * )
     */
    public function index(): object
    {
        $expenses = Expense::all();

        return response(
            content: [
                'message' => 'Busca realizada com sucesso!',
                'data'    => ExpenseResource::collection($expenses)
            ]
        )->setStatusCode(
            code: Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *     path="/api/expenses",
     *     tags={"expenses"},
     *     summary="Insere uma nova despesa",
     *     description="Insere uma nova despesa no sistema",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *                  @OA\Property(property="description", type="string"),
     *                  @OA\Property(property="value", type="string"),
     *                  @OA\Property(property="date", type="string"),
     *                  @OA\Property(property="user_id", type="integer")
     *          ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sucesso ao inserir",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="value", type="string"),
     *                      @OA\Property(property="date", type="string"),
     *                      @OA\Property(property="user_id", type="integer")
     *                  )
     *             ),
     *         ),
     *     ),
     * )
     */
    public function store(Store $request): object
    {
        /**
         * @var User $user
         * @var Expense $expense
         */

        $expense = Expense::query()->make($request->toArray());
        $expense->save();

        $user = User::query()->find($request->input('user_id'));
        $user->notify(new CreatedExpense($expense));

        return response(
            content: [
                'message' => 'Despesa criada com sucesso!',
                'data'    => new ExpenseResource($expense)
            ]
        )->setStatusCode(
            code: Response::HTTP_CREATED
        );
    }

    /**
     * @OA\Get(
     *     path="/api/expenses/{id}",
     *     tags={"expenses"},
     *     summary="Retorna despesa específica",
     *     description="Retorna uma despesa específica a partir do seu id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa encontrada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer"),
     *                      @OA\Property(property="description", type="string"),
     *                      @OA\Property(property="value", type="string"),
     *                      @OA\Property(property="date", type="string"),
     *                      @OA\Property(property="user_id", type="integer")
     *                  )
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ação não autorizada!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function show(string $id): object
    {
        try {

            $expense = Expense::query()->findOrFail($id);

            $this->authorize('view', $expense);

            return response(
                content: [
                    'message' => 'Despesa encontrada com sucesso!',
                    'data'    => new ExpenseResource($expense)
                ]
            )->setStatusCode(
                code: Response::HTTP_OK
            );

        }catch (ModelNotFoundException){
            return response(
                content: [
                    'message' => 'Despesa não encontrada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_NOT_FOUND
            );
        } catch (AuthorizationException) {
            return response(
                content: [
                    'message' => 'Ação não autorizada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * @OA\Put(
     *     path="/api/expenses/{id}",
     *     tags={"expenses"},
     *     summary="Atualiza uma despesa específica",
     *     description="Atualiza uma despesa específica a partir do seu id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="date", type="string"),
     *             @OA\Property(property="user_id", type="integer")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa atualizada com sucesso!",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *              @OA\Property(property="data", type="array",
     *                   @OA\Items(
     *                       type="object",
     *                       @OA\Property(property="id", type="integer"),
     *                       @OA\Property(property="description", type="string"),
     *                       @OA\Property(property="value", type="string"),
     *                       @OA\Property(property="date", type="string"),
     *                       @OA\Property(property="user_id", type="integer")
     *                   )
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Despesa não encontrada!",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Ação não autorizada!",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string"),
     *          ),
     *     ),
     *     )
     */
    public function update(Update $request, string $id): object
    {
        try {

            $expense = Expense::query()->findOrFail($id);

            $this->authorize('view', $expense);

            $expense->fill($request->all())->save();

            return response(
                content: [
                    'message' => 'Despesa atualizada com sucesso!',
                    'data'    => new ExpenseResource($expense)
                ]
            )->setStatusCode(
                code: Response::HTTP_OK
            );

        }catch (ModelNotFoundException){
            return response(
                content: [
                    'message' => 'Despesa não encontrada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_NOT_FOUND
            );
        } catch (AuthorizationException) {
            return response(
                content: [
                    'message' => 'Ação não autorizada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/expenses/{id}",
     *     tags={"expenses"},
     *     summary="Exclui uma despesa específica",
     *     description="Remove uma despesa a partir do seu id",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da despesa",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Despesa removida com sucesso!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Despesa não encontrada!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Ação não autorizada!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */
    public function destroy(string $id): object
    {
        try {

            $expense = Expense::query()->findOrFail($id);
            $this->authorize('view', $expense);

            $expense->delete();

            return response(
                content: [
                    'message' => 'Despesa removida com sucesso!'
                ]
            )->setStatusCode(
                code: Response::HTTP_OK
            );

        }catch (ModelNotFoundException){
            return response(
                content: [
                    'message' => 'Despesa não encontrada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_NOT_FOUND
            );
        } catch (AuthorizationException) {
            return response(
                content: [
                    'message' => 'Ação não autorizada!'
                ]
            )->setStatusCode(
                code: Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
