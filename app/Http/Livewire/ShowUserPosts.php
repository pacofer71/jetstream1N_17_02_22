<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\{Component,WithPagination, WithFileUploads};

class ShowUserPosts extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $campo='id', $orden='desc';
    public $isOpen=false, $isOpenShow=false;
    public Post $post;
    public $image;

    protected $listeners=['renderizarVista'=>'render'];
    protected $rules=[
        'post.titulo'=>'',
        'post.contenido'=>['required', 'string', 'min:10'],
        'post.status'=>['required'],
        'image'=>['nullable', 'image', 'max:1024']
    ];

    public function mount(){
        $this->post=new Post;
    }

    public function render()
    {
        $posts = Post::where('user_id', auth()->user()->id)
        ->orderBy($this->campo, $this->orden)->paginate(5);
        
        return view('livewire.show-user-posts', compact('posts'));
    }

    public function ordenarPor($campo){
        if($campo==$this->campo){
            $this->orden = ($this->orden=='desc') ? 'asc' : 'desc';
        }
        else
            $this->campo=$campo;
    }

    public function borrar(Post $post){
        //1.- Borro el archivo de imagen
        Storage::delete($post->image);
        //Borro el post
        $post->delete();
        $this->emit('borrar', 'Registro Borrado');

    }
    public function editar(Post $post){
        $this->post=$post;
        $this->isOpen=true;

    }
    public function update(){
        $this->validate([
            'post.titulo'=>['required', 'string', 'min:3', 'unique:posts,titulo,'.$this->post->id]
        ]);
        //Queremos editar el registro
        if($this->image){
            //he subido una imagen
            //1.- Borro la imagen antigua
            Storage::delete($this->post->image);
            $imagenNueva = $this->image->store('posts');
            $this->post->image = $imagenNueva;
        }
        $this->post->save();
        $this->emit('info', "Registro Actualizado.");
        $this->reset(['isOpen', 'image']);


    }
    
    public function show(Post $post){
        $this->post=$post;
        $this->isOpenShow=true;
    }

    public function cambiarStatus(Post $post){
        $this->post=$post;
        $this->post->status=($this->post->status==1) ? 2 : 1;
        $this->post->save();
        $this->emit('info', "Se ha cambiado el estado del post");
    }

}
