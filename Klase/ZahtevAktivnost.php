<?php

class ZahtevAktivnost{
    public $id;
    public $korisnik_id;
    public $naziv;
    public $met_vrednost;
    
    public function __construct($id,$korisnik_id,$naziv,$met_vrednost) {
        $this->id = $id;
        $this->korisnik_id = $korisnik_id;
        $this->naziv = $naziv;
        $this->met_vrednost = $met_vrednost;
    }
}

