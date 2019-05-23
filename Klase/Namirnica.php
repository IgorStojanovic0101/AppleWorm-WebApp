<?php

class Namirnica {
    public $id;
    public $naziv;
    public $kategorija;
    public $kalorije;
    public $proteini;
    public $masti;
    public $ugljeni_hidrati;
    public $slika;
    
    public function __construct($id,$naziv,$kategorija,$kalorije,$proteini,$masti,$uglj_hid,$slika) {
        $this->id = $id;
        $this->naziv = $naziv;
        $this->kategorija= $kategorija;
        $this->kalorije = $kalorije;
        $this->proteini = $proteini;
        $this->masti = $masti;
        $this->ugljeni_hidrati = $uglj_hid;
        $this->slika = $slika;
    }
}
