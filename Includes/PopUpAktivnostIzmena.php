<div id="pozadina">
    
</div>
<div id="popup">
    <?php 
       // var_dump($aktivnost);
    ?>
    <div id="popup_header">
        <label id="obavestenje">
            Izmena aktivnosti
        </label>
    </div>
    <div id="popup_body">
    <form method="post" action="admin.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo "$aktivnost->id"?>" /> <!--Mora jer se pravi obj na osnovu parametara koji se odavde salju-->
        <label>Naziv: </label> <input type="text" name="naziv" value="<?php echo"$aktivnost->naziv";?>"/><br />
        <label>MET vrednost: </label> <input type="number" step="0.1" name="met_vrednost" value="<?php echo"$aktivnost->met_vrednost";?>" /><br />
        <label>Slika: </label> <input class="slika" type="file" name="slika" /><br />

        <input type="submit" class="dugme" name="btnSacuvajIzmAkt" value="Sacuvaj" />
    </form>
    </div>
    <div id="popup_footer">
        
    </div>
</div>

<!--stil-->
<style>
    #pozadina{
        display:none;
        width:100%;
        height:100%;
        opacity: 0.7;
        position:absolute;
        top:0;
        left:0;
        background-color:lightgray;
    }
    #popup{
        display:none;
        position:absolute;
        background-color: gray;
        width:30%;
        border: 1px solid blue;
        border-radius: 5px;
        padding:0.5%;
    }
    #popup_header{
        background-color: #428bdd;
        padding: 2%;
    }
    #popup_body{
        background-color: white;
        text-align: center;
        padding: 3%;
    }
    #popup_body input{
        margin:2%;
        border-radius: 5px;
        width:50%;
    }
    #popup_body .slika{
        width:65%;
    }
    #popup_body .dugme{
        margin-top: 10%;
        width:40%;
    }
    #popup_body label{
        float:left;
    }
    
    #popup_footer{
        background-color: whitesmoke;
        padding: 2%;
        text-align: right;
    }
</style>
<!--skripta-->
<script>
        
    (function prikazi(){
        setTimeout(function(){},1000);
        var pozadina = document.getElementById("pozadina");
        var popup = document.getElementById("popup");
        
        pozadina.style.display = "block";
        popup.style.display = "block";
        
        var winwd = window.innerWidth;
        
        /*izracuna se sirina ekrana pri ucitavanju stranice, tako da resize-ovanje nece raditi,
         * ali npr. ako se stranica ucita odma u mobilnom rezimu, radice pravilno tj. pop-up ce biti na sredini*/
        popup.style.left = (winwd/2)-(winwd*30)/200+"px"; 
        popup.style.top = "20%";
    })()
    /* 
       var btnOk = document.getElementById("btnOk");
    
       btnOk.onclick=sakrij;
       function sakrij(){
        var pozadina = document.getElementById("pozadina");
        var popup = document.getElementById("popup");
        
        pozadina.style.display = "none";
        popup.style.display = "none";
    }*/
</script>