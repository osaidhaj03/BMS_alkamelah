<?php

namespace App\Livewire\Components;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FavoriteButton extends Component
{
    public Model $model;
    public bool $isFavorited = false;
    public int $favoritesCount = 0;

    public function mount(Model $model)
    {
        $this->model = $model;
        $this->isFavorited = $this->model->isFavoritedBy(Auth::user());
        $this->favoritesCount = $this->model->favorites_count ?? 0;
    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            // Dispatch event to handle guest favorite in frontend (AlpineJS)
            $this->dispatch('guest-toggle-favorite', [
                'modelType' => get_class($this->model),
                'modelId' => $this->model->id
            ]);
            return;
        }

        /** @var User $user */
        $user = Auth::user();

        $this->isFavorited = $this->model->toggleFavorite($user);
        $this->favoritesCount = $this->model->favorites_count ?? 0; 
    }

    public function render()
    {
        return view('livewire.components.favorite-button');
    }
}
