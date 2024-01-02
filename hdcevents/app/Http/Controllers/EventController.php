<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    public function index() {

        $search = request('search');

        if($search) {
            $events = Event::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();
        } else {
            //Retorna todos os dados da tabela
            $events = Event::all();
        }

        return view('welcome',['events' => $events, 'search' => $search]);
    }

    public function create(){
        return view('events.create');
    }

    public function store(Request $request){

        $event = new Event;

        $event->title = $request->title;
        $event->date = $request->date;
        $event->city = $request->city;
        $event->private = $request->private;
        $event->description = $request->description;
        $event->items = $request->items;

        //Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);

            $event->image = $imageName;
        }

        // Busca os dados do usuário logado
        $user = auth()->user();
        $event->user_id = $user->id;

        $event->save();

        return redirect('/')->with('msg', 'Evento criado com sucesso!');
    }

    public function show($id) {

        $event = Event::findOrFail($id);

        $eventOwner = User::where('id', $event->user_id)->first()->toArray();

        $inscrito = false;
        $user = auth()->user();

        // Verifica se o usuário está logado
        if($user){

            foreach($user->eventsAsParticipant as $eventAsParticipant){
                if($eventAsParticipant->id == $id){
                    $inscrito = true;
                }
            }
        }

        return view('events.show', ['event' => $event, 'eventOwner' => $eventOwner, 'inscrito' => $inscrito]);
    }

    public function dashboard() {

        $user = auth()->user();

        //Aqui ele está acessando o Model "User" e utilizando o mátodo events()
        $events = $user->events;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('events.dashboard', ['events' => $events, 'eventsasparticipant' => $eventsAsParticipant]);
    }

    // public function destroy($id) {

    //     $event = Event::findOrFail($id);

    //     if ($event) {
    //         $event->delete();

    //         return redirect('/dashboard')->with('msg', 'Registro excluído com sucesso.');
    //     } else {
    //         return redirect('/dashboard')->with('msg', 'Registro não encontrado.');
    //     }
    // }

    public function destroy($id)
    {
        $events = auth()->user()->events;

        foreach ($events as $event) {

            //Verifica se o evento é o que está para ser excluído
            if ($event->id == $id) {

                //Exclui o registro do Banco de Dados
                Event::findOrFail($id)->delete();

                $caminho_da_imagem = public_path('img/events/' . $event->image);

                //Exclui o arquivo físico da imagem
                if (File::exists($caminho_da_imagem)) {
                    File::delete($caminho_da_imagem);
                }

                //Redireciona e dá uma mensagem
                return redirect('/dashboard')->with('msg', 'Evento excluído com sucesso!');
            }
        }

        return redirect('/dashboard')->with('msg', 'Sem permissão para exclusão desse evento!');
    }

    public function edit($id) {

        $user = auth()->user();

        $event = Event::findOrFail($id);

        if ($user->id != $event->user_id) {
            return redirect('/dashboard');
        }

        return view('events.edit', ['event' => $event]);
    }

    public function update(Request $request) {

        $data = $request->all();

        //Image Upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $requestImage = $request->image;

            $Event = Event::findOrFail($request->id);

            $caminho_da_imagem = public_path('img/events/' . $Event->image);

            //Exclui o arquivo físico da imagem antiga
            if (File::exists($caminho_da_imagem)) {
                File::delete($caminho_da_imagem);
            }

            //Adiciona a imagem nova na pasta
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;
            $requestImage->move(public_path('img/events'), $imageName);

            $data['image'] = $imageName;
        }

        $event = Event::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Evento editado com sucesso!');
    }

    public function joinEvent($id) {

        $user = auth()->user();

        $user->eventsAsParticipant()->attach($id);

        $event = Event::findOrFail($id);

        return redirect('/events/'.$id)->with('msg', 'Sua presença está confirmada no evento ' . $event->title);
    }

    public function leaveEvent($id, Request $request) {

        $user = auth()->user();

        $user->eventsAsParticipant()->detach($id);

        $event = Event::findOrFail($id);

        if ($request->origem == 'dashboard') {
            $url = '/dashboard';
        } else {
            $url = '/events/'.$id;
        }

        return redirect($url)->with('msg', 'Sua presença foi cancelada do evento ' . $event->title);
    }
}
