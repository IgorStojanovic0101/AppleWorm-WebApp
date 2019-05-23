/* 
 * Zamena tabova u podesavanjima
 * Zamena delova koji se prikazuju
 * 
 */
window.onload=function(){
    
    
    //======================================== promena usr,pass,img (sidebar) ==================================
    var tabs = document.querySelectorAll(".div-lista a");
    for(var i=0;i<tabs.length;i++){
        tabs[i].onclick=aktiviraj;
    }
        
    var dSlika = document.getElementById("dSlika");
    var dUsername = document.getElementById("dUsername");
    var dPass = document.getElementById("dPass");
    
    function aktiviraj(){
        for(var i=0;i<tabs.length;i++)
            tabs[i].className="";
        
        this.className="activeLink";
        
        var divs = document.querySelectorAll(".bottom-sidebar [id^=d]");
        for(var i=0;i<divs.length;i++)
            divs[i].style.display="none";
        
        if(this.id === "aSlika")
            dSlika.style.display="block";
        else if(this.id === "aUsername")
            dUsername.style.display="block";
        else
            dPass.style.display="block";
        
        
        return false;
    }
    
    
    //==========================================glavne forme ==========================================
    
    /*
    //pronadji linkove i dodeli im funkciju na onclick
    var linkovi = document.querySelectorAll(".middle-sidebar a");
    linkovi.forEach(function(el){
       el.onclick=akcija; 
       console.log(el);
    });
    
    
    //sakriva sve forme i prikazuje samo izabranu (switch na osnovu linka)
    function akcija(){
        sakrij();
        switch(this.id){
            case "aDodNam":document.getElementById("glNamirnicaDodaj").style.display="block";break;
            case "aPriNam":document.getElementById("glPrikazNamirnica").style.display="block";break;
            case "aDodAkt":document.getElementById("glAktivnostDodaj").style.display="block";break;
            case "aPriAkt":document.getElementById("glAktivnostIzmeni").style.display="block";break;
            //case "aZahNam":document.getElementById("glNamirnicaIzmeni").style.display="block";break;
            //case "aZahAkt":document.getElementById("glNamirnicaIzmeni").style.display="block";break;
            default: break;
        }
        return false;
    }*/
    
    
    function sakrij(){
        var glavneForme = document.querySelectorAll("content [id^=gl]");
        for(var i=0;i<glavneForme.length;i++){
            glavneForme[i].style.display="none";
        }
        //sakrivanje liste namirnica
        var lista = document.getElementById("glNamirnice");
        if(lista!=='undefined' && lista!==null){
            lista.style.display="none";
        }
        console.log(lista);
    }
    
    
    
    
    
    

    
    
    var mod = window.location.href;
    mod = mod.split('mode=').pop();
    niz = mod.split('&');
    mod = niz[0];
    console.log(mod);
    switch(mod){
        case "http://localhost/AppleWorm/sajt/admin.php":   //pocetna strana(prom. mod je ostala ista)
            document.getElementById("glNamirnicaDodaj").style.display="block";break;
        case "nova_namirnica#cont":
            sakrij();
            document.getElementById("glNamirnicaDodaj").style.display="block";break;
        case "nova_aktivnost#cont":
            sakrij();
            document.getElementById("glAktivnostDodaj").style.display="block";break;
        case "prikaz_namirnica#cont":
            sakrij();
            document.getElementById("glPrikazNamirnica").style.display="block";break;
        case "prikaz_aktivnosti#cont":
            sakrij();
            document.getElementById("glPrikazAktivnosti").style.display="block";break;
        case "lista_aktivnosti":        //klik na dugme prikazi ne moze da ima u link # osim na kraj, a na kraj je dugme...
        case "lista_aktivnosti#cont":    
            sakrij();
            document.getElementById("glAktivnosti").style.display="block";break;
        case "lista_namirnica":
        case "lista_namirnica#cont":
            sakrij();
            document.getElementById("glNamirnice").style.display="block";break;
        case "zahtevi_namirnica#cont":
        case "zahtevi_namirnica":
            sakrij();
            document.getElementById("glZahtevi").style.display="block";break;
        case "zahtevi_aktivnost#cont":
        case "zahtevi_aktivnost":
            sakrij();
            document.getElementById("glZahtevi").style.display="block";break;
                
        default:console.log("krije");
            sakrij();break;       
    }
};
