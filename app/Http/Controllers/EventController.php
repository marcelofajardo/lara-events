<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all()->sortByDesc('created_at');

        return view('welcome', ['events' => $events]);
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $event = new Event();

        $event->title = $request->title;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;

        $event->items = $request->items;

        // image upload

        if($request->hasFile('image') && $request->file('image')->isValid()) {
            $fileImage = $request->image;

            $extension = $fileImage->extension();
            $imageName = md5($fileImage->getClientOriginalName() . strtotime("now") . "." . $extension);

            $fileImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }

        $event->save();

        return redirect('/')->with('success', 'Evento criado com sucesso!');
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }
}
