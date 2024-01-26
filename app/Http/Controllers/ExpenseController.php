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

class ExpenseController extends Controller
{
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
