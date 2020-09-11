<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgendaRequest;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    protected $dataModel;

    /**
     * Display a listing of the resource.
     *
     * @param Agenda $agenda
     */

    public function __construct(Agenda $agenda)
    {
//        $this->middleware('auth');
//        $this->middleware('password.check.change');
        $this->dataModel = $agenda;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $filtro = $request['filtro'];
//
//        if ((isset($filtro)) && (!(trim($filtro) === ''))) {
//            $consulta = 'match(
//            nome, razao_social, cnpj, email, contato, telefone,
//            endereco, numero, bairro, cidade, cep, observacao
//            ) against("' . $filtro . '" IN BOOLEAN MODE)';
//
//            if ($filtro === '*') {
//                $consulta = '1=1';
//            }
//
//            $resultados = Canal::
//            with('originadores')
//                ->whereRaw($consulta)
//                ->get();
//
//        } else {
//            $resultados = Canal::with('originadores')
//                ->where('id', '0')
//                ->get();
//        }

        $resultados = $this->dataModel->all();

        return response()->json($resultados);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AgendaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgendaRequest $request)
    {
//        dd($request->all());
        $input = $request->all();
//        $input['user_id'] = Auth::id();

//        unset($input['originadores']);

        $register = $this->dataModel->create($input);

//        if (isset($request['originadores'])) {
//            $register->originadores()->sync(collect($request['originadores'])->pluck('id'));
//            $register->save();
//            unset($request['originadores']);
//        }
//
//        $register->save();

        if ($request->ajax()) {
            return response()->json('Registro incluído com sucesso', 200);
        }

        return redirect()->route('agenda.index')->with('status', 'registro incluido com sucesso');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $return = $this->dataModel
//            ->with('originadores')
            ->find($id);

        return response()->json($return);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AgendaRequest $request, $id)
    {
        if (isset($request['dataFormatted'])) {
            unset($request['dataFormatted']);
        }

        $register = $this->dataModel->find($id);

//        dd($request['originadores']);

//        if (isset($request['originadores'])) {
//            $register->originadores()->sync(collect($request['originadores'])->pluck('id'));
//            $register->save();
//            unset($request['originadores']);
//        }

        $register->update($request->all());

//        $this->delete_permissoes_users($id);

        if ($request->ajax()) {
            return response()->json('Registro alterado com sucesso', 200);
        }

        return redirect()->route('canal.index')->with('status', 'Registro alterado com sucesso');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $register = $this->dataModel->find($id);

//        filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);


//        $register->originadores()->detach();
//        $this->delete_permissoes_users($id);
        $register->delete();

        if ($request->ajax()) {
            return response()->json('Registro excluído com sucesso', 200);
        }

        return redirect()->route('canal.index')->with('status', 'Registro excluído com sucesso');
    }

}
