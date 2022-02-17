<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\{Component, WithFileUploads};

class CreatePost extends Component
{
    use WithFileUploads;

    public $isOpen=false;
    public $image;
    public $titulo, $contenido, $status;
    protected $rules=[
        'titulo'=>['required', 'string', 'min:3', 'unique:posts,titulo'],
        'contenido'=>['required', 'string', 'min:10'],
        'status'=>['required'],
        'image'=>['required', 'image', 'max:1024']
    ];

    public function render()
    {
        return view('livewire.create-post');
    }

    public function guardar(){
        $this->validate();
        //si paso ed aquí todo ha ido bien, guardamos
        //Guardo fisicamente la imagen
        $imagen = $this->image->store('posts');
        //guardamos el registro
        Post::create([
            'titulo'=>$this->titulo,
            'contenido'=>$this->contenido,
            'status'=>$this->status,
            'image'=>$imagen,
            'user_id'=>auth()->user()->id
        ]);
        $this->reset(['isOpen', 'titulo', 'image', 'contenido', 'status']);
        $this->emitTo('show-user-posts', 'renderizarVista');
        $this->emit('info', "Registro Creado.");

    }
}
