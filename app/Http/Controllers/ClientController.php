<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $clientRepository = new ClientRepository($this->client);
        if($request->has('filters')) {
            $clientRepository->setFilters($request->filters);
        }
        if($request->has('params')) {
            $clientRepository->setParams($request->params);
        }
        return response()->json($clientRepository->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->client->rules());


        // $client = $this->client->create($request->all());
        $client = $this->client->create([
            'name' => $request->get('name'),
        ]);

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $client = $this->client->find($id);
        if($client) {
            return response()->json($client, 200);
        }
        return response()->json(['message' => 'marca não encontrada'], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $client = $this->client->find($id);

        if($client) {
            $defaultRules = $this->client->rules();
            $params = $request->all();
            $rules = 'PUT' === $request->method()
                ? $defaultRules
                : array_filter($defaultRules , function($key) use ($params) {
                    return array_key_exists($key, $params);
                }, ARRAY_FILTER_USE_KEY);

            $request->validate($rules);

            $client->fill($params);

            $client->save();

            // $client->update($request->all());
            return response()->json($client, 200);
        }
        return response()->json(['message' => 'cliente não encontrado'], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $client = $this->client->find($id);
        if($client) {
            $client->delete();
            return response()->json(['message' => 'deleted'], 200);
        }
        return response()->json(['message' => 'cliente não encontrado'], 404);
    }
}
