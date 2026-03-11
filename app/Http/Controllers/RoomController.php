<?php
namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index() {
    // DEBUG : Affiche quelle vue va être chargée
    \Log::info('RoomController@index appelé - Vue : rooms.index');
    
    $rooms = \App\Models\Room::orderBy('room_number')->paginate(10);
    
    // DEBUG : Vérifie que la vue existe
    if (!view()->exists('rooms.index')) {
        \Log::error('Vue rooms.index N\'EXISTE PAS !');
        return response('❌ Vue rooms.index introuvable !', 500);
    }
    
    return view('rooms.index', compact('rooms'));
}


    public function create() {
        return view('rooms.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms',
            'type' => 'required|in:single,double,suite,presidential,bungalow',
            'floor' => 'required|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,maintenance,reserved',
        ]);
        Room::create($validated);
        return redirect()->route('rooms.index')->with('success', 'Chambre créée ✅');
    }

    public function show(Room $room) {
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room) {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room) {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms,room_number,'.$room->id,
            'type' => 'required|in:single,double,suite,presidential,bungalow',
            'floor' => 'required|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,maintenance,reserved',
        ]);
        $room->update($validated);
        return redirect()->route('rooms.index')->with('success', 'Chambre mise à jour ✅');
    }

    public function destroy(Room $room) {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Chambre supprimée ✅');
    }
    
}