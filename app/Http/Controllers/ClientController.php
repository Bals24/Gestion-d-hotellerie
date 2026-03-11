<?php
namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // ❌ PAS DE __construct() avec middleware()

    public function index(Request $request) {
        $query = Client::query();
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        $clients = $query->orderBy('last_name')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create() {
        return view('clients.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string',
            'cin' => 'nullable|unique:clients',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);
        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Client ajouté ✅');
    }

    public function show(Client $client) {
        $client->load('reservations.room');
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client) {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client) {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'phone' => 'required|string',
            'cin' => 'nullable|unique:clients,cin,'.$client->id,
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client mis à jour ✅');
    }

    public function destroy(Client $client) {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'Client supprimé ✅');
    }
}