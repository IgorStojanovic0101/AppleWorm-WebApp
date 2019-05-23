window.onload=function()
{
    var tabLogin = document.getElementById("aLogin");
    var tabSignin = document.getElementById("aSignin");
    var formaSignin = document.getElementById("fSignin");
    var formaLogin = document.getElementById("fLogin");
    
    tabSignin.onclick=aktiviraj;
    tabLogin.onclick=aktiviraj;
   
    
    function aktiviraj(){
        
        if(this.id==="aLogin")
        {
            formaSignin.style.display="none";
            tabSignin.className="tab";
            tabLogin.className="tab activeTab";
            formaLogin.style.display="initial";
        }
        else
        {
            formaLogin.style.display="none";
            tabLogin.className="tab";
            tabSignin.className="tab activeTab";
            formaSignin.style.display="initial";
        }
        return false;   //fora da se ne prikazuje #login ili #signin u link kad se klikne na tab!!!
                        //Verovatno se prvo izvrsi f-ja, a kad treba da ode na odg lokaciju 
                        //ne ode nego se vrati false...Verovatno bi trebalo ova f-ja da vrati true 
                        //da bi se otislo dalje na link
    }
    
}
/*NIKAKO NE TREBA STAVLJATI tabovi[i].onclick = function(){aktiviraj();} JER U TOM SLUCAJU
 * KLJUCNA REC THIS NE MOZE DA SE KORISTI U F-JI AKTIVIRAJ.
 * https://www.quirksmode.org/js/this.html
 * Dakle, ono sto na sajtu kaze jeste da kad se direktno napise tabovi[i].onclick = aktiviraj
 * svaki element ponaosob, tj njegov onclick property ima posebnu funckiju aktiviraj,
 * a kad se upise tabovi[i].onclick = function(){aktiviraj();}, u onclick property-ju ce 
 * samo da stoji referenca na funkciju, tako da se ne moze koristiti this u toj f-ji jer ce 
 * tad this da pokazuje na window.
 * Ono sto se moze tad uraditi jeste da se salje this kao parametar...tabovi[i].onclick = function(){aktiviraj(this);}*/

/*
 * Pre sam radio ovako, ali posto ima samo dva taba moze i direktno
 * if(tabovi !== undefined){
        for(var i=0; i<tabovi.length;i++){
            tabovi[i].onclick = aktiviraj;
        }
    }*/


/*On promeni stanje ali opet ucita novu stranicu na koju ode i tamo 
 * vrati na staro...tj da home bude aktivna klasa.
 * PHP iskoriscen za ovu svrhu. Pogledati header.php fajl.
 * 
 * Ne mozes da koristis javascript da bi promenio ovo jer se fajl dobavlja od servera.Jedino kad
 * fajl stigne nesto moze da se uradi...*/