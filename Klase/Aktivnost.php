<?php

class Aktivnost {
    
    public $id;
    public $naziv;
    public $met_vrednost;
    public $slika;
    
    public function __construct($id,$naziv,$met_vrednost,$slika) {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->met_vrednost = $met_vrednost;
        $this->slika = $slika;
    }
}
