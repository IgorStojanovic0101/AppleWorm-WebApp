window.onload=function(){
    
    var m_sidebar = document.getElementById("divPoruke");
    var autor1 = document.getElementById("autorPoruke");
    
    var poruke = [];
    var autor = [];
    poruke[0]="Koristi hranu koja telu prija i aktivnost koja ga stimuliše!";
    autor[0]="(Biotička)";
    poruke[1]="Jedi zdravo da bi imao dug život!";
    autor[1]="(Biološka)";
    poruke[2]="Zdravlje na usta ulazi!";
    autor[2]="(Narodna)";
    poruke[3]="Šta god da je otac bolesti majka joj je loša ishrana!";
    autor[3]="(Kineska)";
    poruke[4]="Doručkuj kao kralj, ručaj kao princ, a večeraj kao prosjak.";
    autor[4]="(Stara izreka)";
    poruke[5]="Nepravilna ishrana ne prašta grehe!";
    autor[5]="(Medicinksa)";
    poruke[6]="Neka hrana bude tvoj lek i neka tvoj lek bude hrana.";
    autor[6]="(Hipokrat)";
    poruke[7]="Mi smo kopija stila života!";
    autor[7]="(Savremena)";
    poruke[8]="Čovek je ono što jede i kako živi!";
    autor[8]="(Prirodna)";
    poruke[9]="Lični izbor jela zavisi od sklonosti, stila života i energetskog utroška u toku dana!";
    autor[9]="";
    
    var i=1;
    setInterval(promeni,5000);
    function promeni(){
        m_sidebar.innerHTML=poruke[i];
        autor1.innerHTML=autor[i];
        if(++i===10)
            i=0;
    }
}

