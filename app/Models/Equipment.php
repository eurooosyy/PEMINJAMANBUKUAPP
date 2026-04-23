<?php

namespace App\Models;

class Equipment extends Book
{
    protected $table = 'books';

    public function getNamaPeralatanAttribute(): ?string
    {
        return $this->title;
    }

    public function getMerkAttribute(): ?string
    {
        return $this->author;
    }

    public function getKategoriAttribute(): ?string
    {
        return $this->category;
    }

    public function getNomorIdentitasAttribute(): ?string
    {
        return $this->isbn;
    }
}
