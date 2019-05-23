<?php

class ZahtevNamirnica{
    public $id;
    public $korisnik_id;
    public $naziv;
    public $kategorija;
    public $kalorije;
    public $proteini;
    public $masti;
    public $ugljeni_hidrati;
    
    public function __construct($id,$korisnik_id,$naziv,$kategorija,$kalorije,$proteini,$masti,$uglj_hid) {
        $this->id = $id;
        $this->korisnik_id = $korisnik_id;
        $this->naziv = $naziv;
        $this->kategorija= $kategorija;
        $this->kalorije = $kalorije;
        $this->proteini = $proteini;
        $this->masti = $masti;
        $this->ugljeni_hidrati = $uglj_hid;
    }
}

